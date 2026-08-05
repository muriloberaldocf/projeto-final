<?php
/**
 * API DE CLASSIFICAÇÃO / LEADERBOARD ENTRE AMIGOS - HIPO GABARITO
 * Retorna o ranking contendo APENAS o usuário atual e seus amigos adicionados.
 */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

$userId = $_SESSION['user_id'];

try {
    // Buscar Usuário Atual e Amigos adicionados (status 'accepted')
    $stmt = $pdo->prepare("
        SELECT DISTINCT u.id, u.name, u.xp, u.level, u.streak_days, u.avatar, u.avatar_icon
        FROM users u
        LEFT JOIN user_friends f 
               ON (f.user_id = u.id AND f.friend_id = :uid) 
               OR (f.friend_id = u.id AND f.user_id = :uid)
        WHERE u.id = :uid 
           OR (f.status = 'accepted' AND (f.user_id = :uid OR f.friend_id = :uid))
        ORDER BY u.xp DESC, u.level DESC
    ");
    $stmt->execute(['uid' => $userId]);
    $rankings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Se o usuário não tiver outros amigos na lista, adicionar contagem de amigos
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
