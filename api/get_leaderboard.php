<?php
/**
 * API DE CLASSIFICAÇÃO / LEADERBOARD - APROVAQUEST
 * Retorna os top usuários por XP e seus avatares equipados.
 */
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

$userId = $_SESSION['user_id'] ?? 0;

$stmt = $pdo->query("
    SELECT id, name, xp, level, streak_days, avatar, avatar_icon 
    FROM users 
    WHERE avatar IS NOT NULL AND avatar != '' AND avatar != 'default_student.png'
    ORDER BY xp DESC, level DESC 
    LIMIT 50
");
$rankings = $stmt->fetchAll();

echo json_encode([
    'success' => true,
    'current_user_id' => $userId,
    'rankings' => $rankings
]);
