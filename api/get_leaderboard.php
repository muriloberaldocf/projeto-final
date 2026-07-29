<?php
/**
 * API DE CLASSIFICAÇÃO / LEADERBOARD - APROVAQUEST
 * Retorna os top usuários por XP e seus avatares equipados.
 */
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

$userId = $_SESSION['user_id'] ?? 0;

$stmt = $pdo->query("SELECT id, name, xp, level, streak_days, avatar_icon FROM users ORDER BY xp DESC, level DESC LIMIT 50");
$rankings = $stmt->fetchAll();

echo json_encode([
    'success' => true,
    'current_user_id' => $userId,
    'rankings' => $rankings
]);
