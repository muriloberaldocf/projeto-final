<?php
/**
 * API DE SUBMISSÃO E FINALIZAÇÃO DE LIÇÃO - APROVAQUEST
 * Sistema de vidas totalmente removido.
 */
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

$userId = $_SESSION['user_id'] ?? 1;
$data = json_decode(file_get_contents('php://input'), true);

$lessonId = filter_var($data['lesson_id'] ?? 0, FILTER_VALIDATE_INT);
$scorePercent = filter_var($data['score_percent'] ?? 0, FILTER_VALIDATE_INT);

if (!$lessonId) {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
    exit;
}

// 1. Registrar Progresso
$stmtProg = $pdo->prepare("INSERT INTO user_progress (user_id, lesson_id, score_percent) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE score_percent = GREATEST(score_percent, VALUES(score_percent))");
$stmtProg->execute([$userId, $lessonId, $scorePercent]);

// 2. Buscar recompensas da lição
$stmtL = $pdo->prepare("SELECT xp_reward FROM lessons WHERE id = ?");
$stmtL->execute([$lessonId]);
$lesson = $stmtL->fetch();
$baseXp = $lesson['xp_reward'] ?? 20;

// Bônus por nota perfeita
$xpGained = ($scorePercent === 100) ? ($baseXp + 15) : $baseXp;

// 3. Atualizar XP, Nível e Ofensiva (Streak) - SEM VIDAS!
$stmtUser = $pdo->prepare("SELECT xp, level, streak_days, last_active_date FROM users WHERE id = ?");
$stmtUser->execute([$userId]);
$user = $stmtUser->fetch();

$newXp = ($user['xp'] ?? 0) + $xpGained;
$newLevel = floor($newXp / 100) + 1;

// Lógica da Ofensiva Diária
$today = date('Y-m-d');
$lastActive = $user['last_active_date'];
$newStreak = $user['streak_days'] ?? 1;

if ($lastActive !== $today) {
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    if ($lastActive === $yesterday) {
        $newStreak += 1;
    } else {
        $newStreak = 1;
    }
}

$stmtUpdate = $pdo->prepare("UPDATE users SET xp = ?, level = ?, streak_days = ?, last_active_date = ? WHERE id = ?");
$stmtUpdate->execute([$newXp, $newLevel, $newStreak, $today, $userId]);

echo json_encode([
    'success' => true,
    'xp_gained' => $xpGained,
    'total_xp' => $newXp,
    'level' => $newLevel,
    'streak_days' => $newStreak
]);
