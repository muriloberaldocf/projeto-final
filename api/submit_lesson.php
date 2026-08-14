<?php
/**
 * API DE SUBMISSÃO E FINALIZAÇÃO DE LIÇÃO - APROVAQUEST
 * - Sistema de Vidas 100% Removido.
 * - Dinâmica Avançada de XP: Base + Bônus de Precisão (100%, 80%, 60%).
 * - Cálculo de Nível (100 XP por Nível) com detecção de Level Up.
 * - Ofensiva Diária (Streak) com checagem do dia consecutivo.
 */
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

$userId = $_SESSION['user_id'] ?? 1;
$data = json_decode(file_get_contents('php://input'), true);

$lessonId = filter_var($data['lesson_id'] ?? 0, FILTER_VALIDATE_INT);
$scorePercent = filter_var($data['score_percent'] ?? 0, FILTER_VALIDATE_INT);
$mode = trim($data['mode'] ?? '');

if (!$lessonId) {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
    exit;
}

// REGRA DE APROVAÇÃO (Mínimo de 75% de acertos)
$passed = ($scorePercent >= 75);

$xpGained = 0;
$baseXp = 0;
$accuracyBonus = 0;
$leveledUp = false;

// 3. Buscar Dados do Usuário
$stmtUser = $pdo->prepare("SELECT xp, level, streak_days, last_active_date FROM users WHERE id = ?");
$stmtUser->execute([$userId]);
$user = $stmtUser->fetch();

$oldXp = $user['xp'] ?? 0;
$oldLevel = $user['level'] ?? 1;
$currentStreak = (int)($user['streak_days'] ?? 0);
$newStreak = $currentStreak;

if ($passed) {
    // 1. Registrar Progresso da Lição (apenas se passou com >= 75%)
    $stmtProg = $pdo->prepare("INSERT INTO user_progress (user_id, lesson_id, score_percent) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE score_percent = GREATEST(score_percent, VALUES(score_percent))");
    $stmtProg->execute([$userId, $lessonId, $scorePercent]);

    // 2. Buscar recompensas da lição
    $stmtL = $pdo->prepare("SELECT xp_reward FROM lessons WHERE id = ?");
    $stmtL->execute([$lessonId]);
    $lesson = $stmtL->fetch();

    $isBoss = ($mode === 'boss');
    $baseXp = $isBoss ? 50 : ($lesson['xp_reward'] ?? 35);

    if ($scorePercent === 100) {
        $accuracyBonus = 25; // Bônus Perfeito
    } else if ($scorePercent >= 80) {
        $accuracyBonus = 15; // Bônus Excelente
    } else if ($scorePercent >= 75) {
        $accuracyBonus = 10; // Bônus de Aprovação
    }

    $xpGained = $baseXp + $accuracyBonus;

    $newXp = $oldXp + $xpGained;
    $newLevel = max(1, floor($newXp / 100) + 1);
    $leveledUp = ($newLevel > $oldLevel);

    // 4. LÓGICA DA OFENSIVA DIÁRIA (DAILY STREAK)
    $today = date('Y-m-d');
    $lastActive = $user['last_active_date'];

    if (empty($lastActive)) {
        $newStreak = 1;
    } else if ($lastActive !== $today) {
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        if ($lastActive === $yesterday) {
            $newStreak = max(2, $currentStreak + 1);
        } else {
            $newStreak = 1;
        }
    }

    // Atualizar Usuário no Banco de Dados
    $stmtUpdate = $pdo->prepare("UPDATE users SET xp = ?, level = ?, streak_days = ?, last_active_date = ? WHERE id = ?");
    $stmtUpdate->execute([$newXp, $newLevel, $newStreak, $today, $userId]);
} else {
    $newXp = $oldXp;
    $newLevel = $oldLevel;
}

echo json_encode([
    'success' => true,
    'passed' => $passed,
    'score_percent' => $scorePercent,
    'xp_gained' => $xpGained,
    'base_xp' => $baseXp,
    'accuracy_bonus' => $accuracyBonus,
    'total_xp' => $newXp,
    'level' => $newLevel,
    'leveled_up' => $leveledUp,
    'streak_days' => $newStreak
]);

