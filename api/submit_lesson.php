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

// 1. Registrar Progresso da Lição
$stmtProg = $pdo->prepare("INSERT INTO user_progress (user_id, lesson_id, score_percent) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE score_percent = GREATEST(score_percent, VALUES(score_percent))");
$stmtProg->execute([$userId, $lessonId, $scorePercent]);

// 2. Buscar recompensas da lição
$stmtL = $pdo->prepare("SELECT xp_reward FROM lessons WHERE id = ?");
$stmtL->execute([$lessonId]);
$lesson = $stmtL->fetch();

// DINÂMICA DE XP MELHORADA
$isBoss = ($mode === 'boss');
$baseXp = $isBoss ? 50 : ($lesson['xp_reward'] ?? 35);

$accuracyBonus = 0;
if ($scorePercent === 100) {
    $accuracyBonus = 25; // Bônus de Precisão Perfeita!
} else if ($scorePercent >= 80) {
    $accuracyBonus = 15; // Bônus Excelente
} else if ($scorePercent >= 60) {
    $accuracyBonus = 5;  // Bônus Bom
}

$xpGained = $baseXp + $accuracyBonus;

// 3. Buscar Dados do Usuário
$stmtUser = $pdo->prepare("SELECT xp, level, streak_days, last_active_date FROM users WHERE id = ?");
$stmtUser->execute([$userId]);
$user = $stmtUser->fetch();

$oldXp = $user['xp'] ?? 0;
$oldLevel = $user['level'] ?? 1;

$newXp = $oldXp + $xpGained;
$newLevel = max(1, floor($newXp / 100) + 1);
$leveledUp = ($newLevel > $oldLevel);

// 4. LÓGICA DA OFENSIVA DIÁRIA (DAILY STREAK)
// Para o Fogo/Ofensiva acender de verdade, é preciso ter completado lições em pelo menos 2 DIAS SEGUIDOS CONSECUTIVOS.
$today = date('Y-m-d');
$lastActive = $user['last_active_date'];
$currentStreak = (int)($user['streak_days'] ?? 0);
$newStreak = $currentStreak;

if (empty($lastActive)) {
    // Primeiro dia estudando no sistema -> Começa em 1 (Chama ainda apagada/em progresso)
    $newStreak = 1;
} else if ($lastActive !== $today) {
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    if ($lastActive === $yesterday) {
        // Estudo no dia consecutivo (2º dia em diante) -> incrementa o streak!
        $newStreak = max(2, $currentStreak + 1);
    } else {
        // Quebrou a sequência (passou mais de 1 dia) -> volta para 1 dia
        $newStreak = 1;
    }
}

// Atualizar Usuário no Banco de Dados
$stmtUpdate = $pdo->prepare("UPDATE users SET xp = ?, level = ?, streak_days = ?, last_active_date = ? WHERE id = ?");
$stmtUpdate->execute([$newXp, $newLevel, $newStreak, $today, $userId]);

echo json_encode([
    'success' => true,
    'xp_gained' => $xpGained,
    'base_xp' => $baseXp,
    'accuracy_bonus' => $accuracyBonus,
    'total_xp' => $newXp,
    'level' => $newLevel,
    'leveled_up' => $leveledUp,
    'streak_days' => $newStreak
]);
