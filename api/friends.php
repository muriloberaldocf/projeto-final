<?php
/**
 * API DE GERENCIAMENTO DE AMIGOS E SOLICITAÇÕES - HIPOGABARITO
 * Suporte a Envio de Solicitação, Aceite e Recusa de Amizade.
 */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

$userId = $_SESSION['user_id'];
$action = $_GET['action'] ?? $_POST['action'] ?? '';

// 1. Buscar Alunos por Nome ou Email com Status da Relação
if ($action === 'search') {
    $query = trim($_GET['q'] ?? '');
    if (strlen($query) < 2) {
        echo json_encode(['success' => true, 'users' => []]);
        exit;
    }

    try {
        $search = "%{$query}%";
        
        $stmt = $pdo->prepare("
            SELECT u.id, u.name, u.email, u.level, u.xp, u.avatar, u.avatar_icon, u.avatar_frame,
                   (
                       SELECT 
                           CASE 
                               WHEN status = 'accepted' THEN 'accepted'
                               WHEN user_id = ? AND status = 'pending' THEN 'pending_sent'
                               WHEN friend_id = ? AND status = 'pending' THEN 'pending_received'
                               ELSE status
                           END
                       FROM user_friends 
                       WHERE (user_id = ? AND friend_id = u.id) 
                          OR (user_id = u.id AND friend_id = ?)
                       LIMIT 1
                   ) as friend_status
            FROM users u
            WHERE u.id != ? AND (u.name LIKE ? OR u.email LIKE ?)
            LIMIT 15
        ");
        $stmt->execute([$userId, $userId, $userId, $userId, $userId, $search, $search]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'users' => $results]);
        exit;
    } catch (Exception $e) {
        error_log("Erro ao buscar usuários: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Erro ao processar a busca de estudantes.']);
        exit;
    }
}

// 2. Enviar Solicitação de Amizade (Status: 'pending')
if ($action === 'send_request' || $action === 'add_friend') {
    $friendId = filter_input(INPUT_POST, 'friend_id', FILTER_VALIDATE_INT);
    if (!$friendId || $friendId === $userId) {
        echo json_encode(['success' => false, 'message' => 'Usuário amigo inválido.']);
        exit;
    }

    try {
        $stmtCheck = $pdo->prepare("SELECT name FROM users WHERE id = ?");
        $stmtCheck->execute([$friendId]);
        $friend = $stmtCheck->fetch();

        if (!$friend) {
            echo json_encode(['success' => false, 'message' => 'Estudante não encontrado.']);
            exit;
        }

        // Verificar se já existe registro entre ambos
        $stmtRel = $pdo->prepare("SELECT id, status, user_id FROM user_friends WHERE (user_id = ? AND friend_id = ?) OR (user_id = ? AND friend_id = ?)");
        $stmtRel->execute([$userId, $friendId, $friendId, $userId]);
        $existing = $stmtRel->fetch();

        if ($existing) {
            if ($existing['status'] === 'accepted') {
                echo json_encode(['success' => false, 'message' => 'Você e ' . $friend['name'] . ' já são amigos!']);
                exit;
            } else if ($existing['status'] === 'pending') {
                if ($existing['user_id'] == $userId) {
                    echo json_encode(['success' => false, 'message' => 'Você já enviou uma solicitação para este estudante.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Este estudante já lhe enviou uma solicitação! Verifique em Solicitações Pendentes.']);
                }
                exit;
            }
        }

        // Criar registro de solicitação pendente: user_id (solicitante) -> friend_id (destinatário)
        $stmtInsert = $pdo->prepare("
            INSERT INTO user_friends (user_id, friend_id, status) 
            VALUES (?, ?, 'pending')
            ON DUPLICATE KEY UPDATE status = 'pending', user_id = VALUES(user_id), friend_id = VALUES(friend_id)
        ");
        $stmtInsert->execute([$userId, $friendId]);

        echo json_encode([
            'success' => true, 
            'message' => "Solicitação de amizade enviada para {$friend['name']}!"
        ]);
        exit;
    } catch (Exception $e) {
        error_log("Erro ao enviar solicitação: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Erro ao enviar solicitação de amizade.']);
        exit;
    }
}

// 3. Aceitar Solicitação de Amizade
if ($action === 'accept_request') {
    $senderId = filter_input(INPUT_POST, 'sender_id', FILTER_VALIDATE_INT) ?: filter_input(INPUT_POST, 'friend_id', FILTER_VALIDATE_INT);
    if (!$senderId) {
        echo json_encode(['success' => false, 'message' => 'Solicitação inválida.']);
        exit;
    }

    try {
        $stmtCheck = $pdo->prepare("SELECT name FROM users WHERE id = ?");
        $stmtCheck->execute([$senderId]);
        $sender = $stmtCheck->fetch();

        if (!$sender) {
            echo json_encode(['success' => false, 'message' => 'Usuário não encontrado.']);
            exit;
        }

        // Atualizar status para accepted na solicitação
        $stmtUpd = $pdo->prepare("
            UPDATE user_friends 
            SET status = 'accepted' 
            WHERE (user_id = ? AND friend_id = ?) OR (user_id = ? AND friend_id = ?)
        ");
        $stmtUpd->execute([$senderId, $userId, $userId, $senderId]);

        echo json_encode([
            'success' => true, 
            'message' => "Você aceitou a solicitação! Agora você e {$sender['name']} são amigos de estudos!"
        ]);
        exit;
    } catch (Exception $e) {
        error_log("Erro ao aceitar solicitação: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Erro ao aceitar solicitação de amizade.']);
        exit;
    }
}

// 4. Recusar / Cancelar Solicitação ou Remover Amigo
if ($action === 'reject_request' || $action === 'cancel_request' || $action === 'remove_friend') {
    $targetId = filter_input(INPUT_POST, 'target_id', FILTER_VALIDATE_INT) ?: filter_input(INPUT_POST, 'friend_id', FILTER_VALIDATE_INT) ?: filter_input(INPUT_POST, 'sender_id', FILTER_VALIDATE_INT);
    if (!$targetId) {
        echo json_encode(['success' => false, 'message' => 'Identificador de usuário inválido.']);
        exit;
    }

    try {
        $stmtDelete = $pdo->prepare("
            DELETE FROM user_friends 
            WHERE (user_id = ? AND friend_id = ?) OR (user_id = ? AND friend_id = ?)
        ");
        $stmtDelete->execute([$userId, $targetId, $targetId, $userId]);

        $msg = 'Solicitação recusada.';
        if ($action === 'cancel_request') $msg = 'Solicitação de amizade cancelada.';
        if ($action === 'remove_friend') $msg = 'Amigo removido da sua lista.';

        echo json_encode(['success' => true, 'message' => $msg]);
        exit;
    } catch (Exception $e) {
        error_log("Erro ao processar ação de amizade: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Erro ao processar a ação solicitada.']);
        exit;
    }
}

// 5. Listar Solicitações Pendentes Recebidas e Enviadas
if ($action === 'list_pending') {
    try {
        // Recebidas (Alunos que enviaram solicitação para mim)
        $stmtReceived = $pdo->prepare("
            SELECT u.id, u.name, u.email, u.level, u.xp, u.avatar, u.avatar_icon, u.avatar_frame, f.created_at
            FROM user_friends f
            JOIN users u ON u.id = f.user_id
            WHERE f.friend_id = ? AND f.status = 'pending'
            ORDER BY f.created_at DESC
        ");
        $stmtReceived->execute([$userId]);
        $received = $stmtReceived->fetchAll(PDO::FETCH_ASSOC);

        // Enviadas (Solicitações enviadas por mim aguardando resposta)
        $stmtSent = $pdo->prepare("
            SELECT u.id, u.name, u.email, u.level, u.xp, u.avatar, u.avatar_icon, u.avatar_frame, f.created_at
            FROM user_friends f
            JOIN users u ON u.id = f.friend_id
            WHERE f.user_id = ? AND f.status = 'pending'
            ORDER BY f.created_at DESC
        ");
        $stmtSent->execute([$userId]);
        $sent = $stmtSent->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true, 
            'received' => $received,
            'sent' => $sent,
            'total_pending' => count($received)
        ]);
        exit;
    } catch (Exception $e) {
        error_log("Erro ao listar solicitações: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Erro ao listar solicitações pendentes.']);
        exit;
    }
}

// 6. Listar Meus Amigos Aceitos
if ($action === 'list_friends') {
    try {
        $stmt = $pdo->prepare("
            SELECT u.id, u.name, u.xp, u.level, u.streak_days, u.avatar, u.avatar_icon, u.avatar_frame
            FROM users u
            JOIN user_friends f ON (f.friend_id = u.id AND f.user_id = ?) OR (f.user_id = u.id AND f.friend_id = ?)
            WHERE u.id != ? AND f.status = 'accepted'
            GROUP BY u.id
            ORDER BY u.name ASC
        ");
        $stmt->execute([$userId, $userId, $userId]);
        $friends = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'friends' => $friends]);
        exit;
    } catch (Exception $e) {
        error_log("Erro ao listar amigos: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Erro ao listar amigos cadastrados.']);
        exit;
    }
}

// 7. Sugestões de Estudantes para Adicionar
if ($action === 'suggestions') {
    try {
        $stmt = $pdo->prepare("
            SELECT u.id, u.name, u.xp, u.level, u.streak_days, u.avatar, u.avatar_icon, u.avatar_frame
            FROM users u
            WHERE u.id != ?
              AND u.id NOT IN (
                  SELECT friend_id FROM user_friends WHERE user_id = ?
                  UNION
                  SELECT user_id FROM user_friends WHERE friend_id = ?
              )
            ORDER BY u.xp DESC, u.level DESC
            LIMIT 6
        ");
        $stmt->execute([$userId, $userId, $userId]);
        $suggestions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'suggestions' => $suggestions]);
        exit;
    } catch (Exception $e) {
        error_log("Erro ao buscar sugestões: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Erro ao carregar sugestões.']);
        exit;
    }
}

// 8. Feed do Mural de Conquistas (Usuário + Amigos Aceitos)
if ($action === 'mural_feed') {
    try {
        $stmt = $pdo->prepare("
            SELECT 
                e.id as event_id,
                e.user_id,
                u.name as user_name,
                u.avatar,
                u.avatar_icon,
                u.avatar_frame,
                u.level as user_level,
                e.event_type,
                e.title,
                e.description,
                e.icon,
                e.theme_color,
                e.created_at,
                (SELECT COUNT(*) FROM friend_mural_kudos k WHERE k.event_id = e.id) as kudos_count,
                (SELECT COUNT(*) FROM friend_mural_kudos k WHERE k.event_id = e.id AND k.from_user_id = ?) as user_has_sent_boa
            FROM friend_mural_events e
            JOIN users u ON u.id = e.user_id
            WHERE e.user_id = ?
               OR e.user_id IN (
                   SELECT CASE WHEN user_id = ? THEN friend_id ELSE user_id END
                   FROM user_friends
                   WHERE (user_id = ? OR friend_id = ?) AND status = 'accepted'
               )
            ORDER BY e.created_at DESC
            LIMIT 30
        ");
        $stmt->execute([$userId, $userId, $userId, $userId, $userId]);
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Formatar tempo relativo amigável
        foreach ($events as &$ev) {
            $ts = strtotime($ev['created_at']);
            $diff = time() - $ts;
            if ($diff < 60) {
                $ev['time_ago'] = 'Agora mesmo';
            } else if ($diff < 3600) {
                $ev['time_ago'] = 'Há ' . floor($diff / 60) . ' min';
            } else if ($diff < 86400) {
                $ev['time_ago'] = 'Há ' . floor($diff / 3600) . 'h';
            } else {
                $ev['time_ago'] = date('d/m', $ts);
            }
            $ev['is_me'] = ((int)$ev['user_id'] === $userId);
            $ev['user_has_sent_boa'] = ((int)$ev['user_has_sent_boa'] > 0);
        }
        unset($ev);

        echo json_encode(['success' => true, 'events' => $events]);
        exit;
    } catch (Exception $e) {
        error_log("Erro ao carregar feed do mural: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Erro ao carregar mural de conquistas.']);
        exit;
    }
}

// 9. Enviar ou Alternar "Boa! 👊" em uma Conquista
if ($action === 'send_boa') {
    $eventId = filter_input(INPUT_POST, 'event_id', FILTER_VALIDATE_INT);
    if (!$eventId && isset($_POST['event_id'])) {
        $eventId = (int)$_POST['event_id'];
    }
    if (!$eventId) {
        echo json_encode(['success' => false, 'message' => 'Evento inválido.']);
        exit;
    }

    try {
        // Verificar se já deu boa
        $stmtCheck = $pdo->prepare("SELECT id FROM friend_mural_kudos WHERE event_id = ? AND from_user_id = ?");
        $stmtCheck->execute([$eventId, $userId]);
        $existing = $stmtCheck->fetch();

        if ($existing) {
            // Remover Boa
            $del = $pdo->prepare("DELETE FROM friend_mural_kudos WHERE id = ?");
            $del->execute([$existing['id']]);
            $sent = false;
        } else {
            // Adicionar Boa
            $ins = $pdo->prepare("INSERT INTO friend_mural_kudos (event_id, from_user_id, created_at) VALUES (?, ?, NOW())");
            $ins->execute([$eventId, $userId]);
            $sent = true;
        }

        // Contar novo total
        $cntStmt = $pdo->prepare("SELECT COUNT(*) FROM friend_mural_kudos WHERE event_id = ?");
        $cntStmt->execute([$eventId]);
        $newCount = (int)$cntStmt->fetchColumn();

        echo json_encode([
            'success' => true,
            'sent' => $sent,
            'kudos_count' => $newCount,
            'message' => $sent ? 'Você mandou um Boa! 👊' : 'Boa desfeito.'
        ]);
        exit;
    } catch (Exception $e) {
        error_log("Erro ao processar Boa: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Erro ao processar reação.']);
        exit;
    }
}

// 10. Perfil e Conquistas do Amigo (Mini-Perfil Modal)
if ($action === 'friend_profile') {
    $friendId = filter_input(INPUT_GET, 'friend_id', FILTER_VALIDATE_INT);
    if (!$friendId && isset($_GET['friend_id'])) {
        $friendId = (int)$_GET['friend_id'];
    }
    if (!$friendId) {
        echo json_encode(['success' => false, 'message' => 'Estudante inválido.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT id, name, xp, level, streak_days, last_active_date, avatar, avatar_icon, avatar_frame FROM users WHERE id = ?");
        $stmt->execute([$friendId]);
        $friend = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$friend) {
            echo json_encode(['success' => false, 'message' => 'Estudante não encontrado.']);
            exit;
        }

        // Buscar Conquistas deste amigo
        $stmtAch = $pdo->prepare("
            SELECT a.title, a.description, a.icon, a.xp_bonus, ua.unlocked_at
            FROM user_achievements ua
            JOIN achievements a ON a.id = ua.achievement_id
            WHERE ua.user_id = ?
            ORDER BY ua.unlocked_at DESC
        ");
        $stmtAch->execute([$friendId]);
        $achievements = $stmtAch->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'friend' => $friend,
            'achievements' => $achievements,
            'total_achievements' => count($achievements)
        ]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao carregar perfil do colega.']);
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Ação não reconhecida.']);
