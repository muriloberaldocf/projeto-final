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

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

$userId = (int)$_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

$lessonId = filter_var($data['lesson_id'] ?? 0, FILTER_VALIDATE_INT);
$stage = filter_var($data['stage'] ?? 1, FILTER_VALIDATE_INT);
$stage = max(1, min(4, (int)$stage));
$scorePercent = filter_var($data['score_percent'] ?? 0, FILTER_VALIDATE_INT);
$scorePercent = max(0, min(100, (int)$scorePercent));
$mode = trim($data['mode'] ?? '');

if (!$lessonId) {
    echo json_encode(['success' => false, 'message' => 'Dados de lição inválidos']);
    exit;
}

$isSkipAhead = ($mode === 'skip_ahead');

// REGRA DE APROVAÇÃO (Mínimo de 60% para etapa normal, ou 70% para Simulado de Avanço)
$passThreshold = $isSkipAhead ? 70 : 60;
$passed = ($scorePercent >= $passThreshold);

$xpGained = 0;
$baseXp = 0;
$accuracyBonus = 0;
$leveledUp = false;
$nextStage = $stage;
$lessonCompleted = false;
$unlockedPreviousCount = 0;

// Buscar progresso atual
$stmtCurProg = $pdo->prepare("SELECT stage, max_stage, score_percent FROM user_progress WHERE user_id = ? AND lesson_id = ?");
$stmtCurProg->execute([$userId, $lessonId]);
$curProg = $stmtCurProg->fetch(PDO::FETCH_ASSOC);

$curMaxStage = $curProg ? (int)$curProg['max_stage'] : 1;
$curStage = $curProg ? (int)$curProg['stage'] : 1;

if ($passed) {
    if ($isSkipAhead) {
        $curMaxStage = 4;
        $nextStage = 4;
        $lessonCompleted = true;
    } else {
        // Se passou na etapa atual, desbloqueia a próxima etapa até o limite de 4
        if ($stage >= $curMaxStage) {
            $curMaxStage = min(4, $stage + 1);
        }
        $nextStage = min(4, $stage + 1);
        if ($stage === 4) {
            $lessonCompleted = true;
        }
    }
}

// 3. Buscar Dados do Usuário
$stmtUser = $pdo->prepare("SELECT xp, level, streak_days, last_active_date FROM users WHERE id = ?");
$stmtUser->execute([$userId]);
$user = $stmtUser->fetch();

$oldXp = $user['xp'] ?? 0;
$oldLevel = $user['level'] ?? 1;
$currentStreak = (int)($user['streak_days'] ?? 0);
$newStreak = $currentStreak;

if ($passed) {
    $today = date('Y-m-d');

    // 1. Registrar Progresso da Etapa da Lição
    $stmtProg = $pdo->prepare("
        INSERT INTO user_progress (user_id, lesson_id, score_percent, stage, max_stage, completed_at) 
        VALUES (?, ?, ?, ?, ?, NOW()) 
        ON DUPLICATE KEY UPDATE 
            score_percent = GREATEST(score_percent, VALUES(score_percent)), 
            stage = VALUES(stage),
            max_stage = GREATEST(max_stage, VALUES(max_stage)),
            completed_at = NOW()
    ");
    $stmtProg->execute([$userId, $lessonId, $scorePercent, $nextStage, $curMaxStage]);

    // SE FOI MODO SKIP AHEAD: marcar todas as lições anteriores da matéria como concluídas!
    if ($isSkipAhead) {
        $stmtTarget = $pdo->prepare("
            SELECT l.id, l.order_index AS lesson_order, u.order_index AS unit_order, u.subject_id 
            FROM lessons l 
            JOIN units u ON l.unit_id = u.id 
            WHERE l.id = ?
        ");
        $stmtTarget->execute([$lessonId]);
        $targetInfo = $stmtTarget->fetch(PDO::FETCH_ASSOC);

        if ($targetInfo) {
            $stmtPrevLessons = $pdo->prepare("
                SELECT l.id 
                FROM lessons l 
                JOIN units u ON l.unit_id = u.id 
                WHERE u.subject_id = ? 
                  AND (u.order_index < ? OR (u.order_index = ? AND l.order_index <= ?))
            ");
            $stmtPrevLessons->execute([
                $targetInfo['subject_id'], 
                $targetInfo['unit_order'], 
                $targetInfo['unit_order'], 
                $targetInfo['lesson_order']
            ]);
            $prevLessons = $stmtPrevLessons->fetchAll(PDO::FETCH_COLUMN);

            if (!empty($prevLessons)) {
                $stmtBatchProg = $pdo->prepare("
                    INSERT INTO user_progress (user_id, lesson_id, score_percent, stage, max_stage, completed_at)
                    VALUES (?, ?, ?, 4, 4, NOW())
                    ON DUPLICATE KEY UPDATE
                        stage = 4,
                        max_stage = 4,
                        score_percent = GREATEST(score_percent, VALUES(score_percent)),
                        completed_at = NOW()
                ");
                foreach ($prevLessons as $prevLessonId) {
                    $stmtBatchProg->execute([$userId, $prevLessonId, $scorePercent]);
                    $unlockedPreviousCount++;
                }
            }
        }
    }

    // 2. Buscar recompensas da lição
    $stmtL = $pdo->prepare("SELECT xp_reward FROM lessons WHERE id = ?");
    $stmtL->execute([$lessonId]);
    $lesson = $stmtL->fetch();

    $isBoss = ($mode === 'boss' || $stage === 4);
    if ($isSkipAhead) {
        $baseXp = 100 + ($unlockedPreviousCount * 25);
    } else {
        $baseXp = $isBoss ? 50 : ($lesson['xp_reward'] ?? 35);
    }

    if ($scorePercent === 100) {
        $accuracyBonus = 25; // Bônus Perfeito
    } else if ($scorePercent >= 80) {
        $accuracyBonus = 15; // Bônus Excelente
    } else if ($scorePercent >= 60) {
        $accuracyBonus = 10; // Bônus de Aprovação
    }

    $xpGained = $baseXp + $accuracyBonus;

    $newXp = $oldXp + $xpGained;
    $newLevel = max(1, floor($newXp / 100) + 1);
    $leveledUp = ($newLevel > $oldLevel);

    // 4. LÓGICA DA OFENSIVA DIÁRIA (DAILY STREAK)
    // Atualizar Usuário no Banco de Dados
    $stmtUpdate = $pdo->prepare("UPDATE users SET xp = ?, level = ? WHERE id = ?");
    $stmtUpdate->execute([$newXp, $newLevel, $userId]);

    $newStreak = recordUserActivityStreak($pdo, $userId);

    // 5. REGISTRAR ATIVIDADE DIÁRIA REAL (Meta Diária de XP e Desafio de Lição Concluída)
    try {
        $stmtDaily = $pdo->prepare("
            INSERT INTO user_daily_activity (user_id, activity_date, xp_earned, lessons_completed)
            VALUES (?, ?, ?, 1)
            ON DUPLICATE KEY UPDATE
                xp_earned = xp_earned + VALUES(xp_earned),
                lessons_completed = lessons_completed + 1
        ");
        $stmtDaily->execute([$userId, $today, $xpGained]);
    } catch (Exception $e) {
        // Fallback silencioso
    }

    // 6. GATILHOS DE CONQUISTAS & MURAL DE AMIGOS
    try {
        // Conquista: Primeiro Passo
        unlockAchievement($pdo, $userId, 'first_lesson');

        // Checar matéria da lição
        $stmtSub = $pdo->prepare("
            SELECT s.slug, s.name, l.title 
            FROM lessons l 
            JOIN units u ON l.unit_id = u.id 
            JOIN subjects s ON u.subject_id = s.id 
            WHERE l.id = ?
        ");
        $stmtSub->execute([$lessonId]);
        $subInfo = $stmtSub->fetch();
        $subSlug = $subInfo['slug'] ?? '';
        $subName = $subInfo['name'] ?? 'Vestibular';
        $lessonTitle = $subInfo['title'] ?? 'Lição';

        // Conquista: Lição Perfeita por Matéria (100%)
        if ($scorePercent === 100) {
            $achMap = [
                'fisica' => ['code' => 'perfect_fisica', 'title' => 'Física Perfeita ⚡', 'icon' => 'bi-lightning-charge-fill', 'color' => 'cyan'],
                'matematica' => ['code' => 'perfect_matematica', 'title' => 'Mestre do Cálculo 📐', 'icon' => 'bi-calculator-fill', 'color' => 'indigo'],
                'quimica' => ['code' => 'perfect_quimica', 'title' => 'Alquimista Nota 10 🧪', 'icon' => 'bi-eyedropper', 'color' => 'emerald'],
                'biologia' => ['code' => 'perfect_biologia', 'title' => 'DNA Gabaritado 🧬', 'icon' => 'bi-heart-pulse-fill', 'color' => 'rose'],
                'humanas' => ['code' => 'perfect_humanas', 'title' => 'Pensador Crítico 🌍', 'icon' => 'bi-globe-americas', 'color' => 'amber'],
                'portugues' => ['code' => 'perfect_portugues', 'title' => 'Domínio das Palavras 📚', 'icon' => 'bi-journal-check', 'color' => 'purple']
            ];

            if (isset($achMap[$subSlug])) {
                $achData = $achMap[$subSlug];
                unlockAchievement($pdo, $userId, $achData['code']);
                recordMuralEvent(
                    $pdo,
                    $userId,
                    $achData['code'],
                    $achData['title'],
                    "Gabaritou 100% na lição \"{$lessonTitle}\" de {$subName} sem errar nenhuma questão!",
                    $achData['icon'],
                    $achData['color']
                );
            } else {
                recordMuralEvent(
                    $pdo,
                    $userId,
                    'perfect_generic',
                    "Lição Perfeita (100%) em {$subName} 🎯",
                    "Gabaritou 100% na lição \"{$lessonTitle}\" sem nenhum erro!",
                    'bi-patch-check-fill',
                    'emerald'
                );
            }
        }

        // Conquista: Salto Quântico / Simulado de Avanço
        if ($isSkipAhead) {
            recordMuralEvent(
                $pdo,
                $userId,
                'skip_ahead_master',
                'Salto Relâmpago na Trilha! ⚡🚀',
                "Dominou o Simulado Cumulativo de {$subName} com {$scorePercent}% e pulou {$unlockedPreviousCount} lições de uma só vez!",
                'bi-lightning-charge-fill',
                'amber'
            );
        }

        // Conquista: Modo Boss
        if ($isBoss) {
            unlockAchievement($pdo, $userId, 'boss_slayer');
            recordMuralEvent(
                $pdo,
                $userId,
                'boss_slayer',
                'Derrubou o Chefão! 👑',
                "Venceu o Desafio Boss de {$subName} na lição \"{$lessonTitle}\"!",
                'bi-trophy-fill',
                'rose'
            );
        }

        // Conquistas de Ofensiva (3 e 7 dias)
        if ($newStreak >= 7) {
            unlockAchievement($pdo, $userId, 'streak_7');
            recordMuralEvent(
                $pdo,
                $userId,
                'streak_7',
                'Semana Invicta (7 Dias) 🔥',
                'Completou 7 dias seguidos de estudos na plataforma!',
                'bi-calendar-check-fill',
                'amber'
            );
        } else if ($newStreak >= 3) {
            unlockAchievement($pdo, $userId, 'streak_3');
        }
    } catch (Exception $e) {
        error_log("Erro ao processar conquistas pós lição: " . $e->getMessage());
    }
} else {
    $newXp = $oldXp;
    $newLevel = $oldLevel;
}

// 7. Calcular Sequência Atual de Acertos Consecutivos (Streak de questões)
$consecutiveStreak = getConsecutiveCorrectStreak($pdo, $userId);

echo json_encode([
    'success' => true,
    'passed' => $passed,
    'stage' => $stage,
    'next_stage' => $nextStage,
    'lesson_completed' => $lessonCompleted,
    'score_percent' => $scorePercent,
    'xp_gained' => $xpGained,
    'base_xp' => $baseXp,
    'accuracy_bonus' => $accuracyBonus,
    'total_xp' => $newXp,
    'level' => $newLevel,
    'leveled_up' => $leveledUp,
    'streak_days' => $newStreak,
    'consecutive_streak' => $consecutiveStreak,
    'is_skip_ahead' => $isSkipAhead,
    'unlocked_previous_count' => $unlockedPreviousCount
]);

