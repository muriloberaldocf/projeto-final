<?php
/**
 * API DE CLASSIFICAÇÃO / LEADERBOARD ENTRE AMIGOS - HIPO GABARITO
 * Retorna o ranking contendo o próprio usuário logado E todos os seus amigos com status 'accepted'.
 */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

$userId = (int)$_SESSION['user_id'];

try {
    // 1. Buscar o próprio usuário + todos os amigos aceitos
    $stmt = $pdo->prepare("
        SELECT u.id, u.name, u.xp, u.level, u.streak_days, u.avatar, u.avatar_icon, u.avatar_frame
        FROM users u
        WHERE u.id = ?
           OR u.id IN (
               SELECT CASE WHEN user_id = ? THEN friend_id ELSE user_id END
               FROM user_friends
               WHERE (user_id = ? OR friend_id = ?) AND status = 'accepted'
           )
        ORDER BY u.xp DESC, u.level DESC
    ");
    $stmt->execute([$userId, $userId, $userId, $userId]);
    $rankings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rankings as &$r) {
        $r['is_me'] = ((int)$r['id'] === $userId);
    }
    unset($r);

    // 2. Contar número total de amigos aceitos
    $stmtFriendsCount = $pdo->prepare("
        SELECT COUNT(DISTINCT CASE WHEN user_id = ? THEN friend_id ELSE user_id END) 
        FROM user_friends 
        WHERE (user_id = ? OR friend_id = ?) AND status = 'accepted'
    ");
    $stmtFriendsCount->execute([$userId, $userId, $userId]);
    $totalFriends = (int)$stmtFriendsCount->fetchColumn();

    echo json_encode([
        'success' => true,
        'current_user_id' => $userId,
        'total_friends' => $totalFriends,
        'rankings' => $rankings
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao carregar ranking de amigos: ' . $e->getMessage()]);
}
