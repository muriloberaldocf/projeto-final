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
        echo json_encode(['success' => false, 'message' => 'Erro ao buscar usuários: ' . $e->getMessage()]);
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
        echo json_encode(['success' => false, 'message' => 'Erro ao enviar solicitação: ' . $e->getMessage()]);
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
        echo json_encode(['success' => false, 'message' => 'Erro ao aceitar solicitação: ' . $e->getMessage()]);
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
        echo json_encode(['success' => false, 'message' => 'Erro ao processar ação: ' . $e->getMessage()]);
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
        echo json_encode(['success' => false, 'message' => 'Erro ao listar solicitações: ' . $e->getMessage()]);
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
        echo json_encode(['success' => false, 'message' => 'Erro ao listar amigos: ' . $e->getMessage()]);
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Ação não reconhecida.']);
