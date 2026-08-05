<?php
/**
 * API DE GERENCIAMENTO DE AMIGOS - APROVAQUEST / HIPOGABARITO
 */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

$userId = $_SESSION['user_id'];
$action = $_GET['action'] ?? $_POST['action'] ?? '';

// 1. Buscar Alunos por Nome ou Email
if ($action === 'search') {
    $query = trim($_GET['q'] ?? '');
    if (strlen($query) < 2) {
        echo json_encode(['success' => true, 'users' => []]);
        exit;
    }

    try {
        $search = "%{$query}%";
        // Buscar usuários exceto ele mesmo
        $stmt = $pdo->prepare("
            SELECT u.id, u.name, u.email, u.level, u.xp, u.avatar, u.avatar_icon,
                   (SELECT status FROM user_friends f WHERE (f.user_id = ? AND f.friend_id = u.id) OR (f.user_id = u.id AND f.friend_id = ?)) as friend_status
            FROM users u
            WHERE u.id != ? AND (u.name LIKE ? OR u.email LIKE ?)
            LIMIT 15
        ");
        $stmt->execute([$userId, $userId, $userId, $search, $search]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'users' => $results]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao buscar usuários: ' . $e->getMessage()]);
        exit;
    }
}

// 2. Adicionar Amigo
if ($action === 'add_friend') {
    $friendId = filter_input(INPUT_POST, 'friend_id', FILTER_VALIDATE_INT);
    if (!$friendId || $friendId === $userId) {
        echo json_encode(['success' => false, 'message' => 'Usuário amigo inválido.']);
        exit;
    }

    try {
        // Verificar se usuário existe
        $stmtCheck = $pdo->prepare("SELECT name FROM users WHERE id = ?");
        $stmtCheck->execute([$friendId]);
        $friend = $stmtCheck->fetch();

        if (!$friend) {
            echo json_encode(['success' => false, 'message' => 'Estudante não encontrado.']);
            exit;
        }

        // Inserir amizade aceita (amizade direta em ambos os lados)
        $stmtInsert = $pdo->prepare("
            INSERT INTO user_friends (user_id, friend_id, status) 
            VALUES (?, ?, 'accepted'), (?, ?, 'accepted')
            ON DUPLICATE KEY UPDATE status = 'accepted'
        ");
        $stmtInsert->execute([$userId, $friendId, $friendId, $userId]);

        echo json_encode([
            'success' => true, 
            'message' => "Você e {$friend['name']} agora são amigos de estudos!"
        ]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao adicionar amigo: ' . $e->getMessage()]);
        exit;
    }
}

// 3. Remover Amigo
if ($action === 'remove_friend') {
    $friendId = filter_input(INPUT_POST, 'friend_id', FILTER_VALIDATE_INT);
    if (!$friendId) {
        echo json_encode(['success' => false, 'message' => 'Amigo inválido.']);
        exit;
    }

    try {
        $stmtDelete = $pdo->prepare("
            DELETE FROM user_friends 
            WHERE (user_id = ? AND friend_id = ?) OR (user_id = ? AND friend_id = ?)
        ");
        $stmtDelete->execute([$userId, $friendId, $friendId, $userId]);

        echo json_encode(['success' => true, 'message' => 'Amigo removido da sua lista.']);
        exit;
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao remover amigo: ' . $e->getMessage()]);
        exit;
    }
}

// 4. Listar Meus Amigos
if ($action === 'list_friends') {
    try {
        $stmt = $pdo->prepare("
            SELECT u.id, u.name, u.xp, u.level, u.streak_days, u.avatar, u.avatar_icon
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
