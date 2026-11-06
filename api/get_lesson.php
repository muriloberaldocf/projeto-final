<?php
/**
 * API DE OBTER QUESTÕES DA LIÇÃO - APROVAQUEST
 * - Sistema de vidas removido.
 * - Filtra para NÃO REPETIR questões que o usuário já acertou nos últimos 5 dias.
 */
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

$userId = (int)$_SESSION['user_id'];
$lessonId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: (int)($_GET['id'] ?? 0);
$requestedStage = filter_input(INPUT_GET, 'stage', FILTER_VALIDATE_INT) ?: 0;
$requestedMode = $_GET['mode'] ?? '';

if (!$lessonId) {
    echo json_encode(['success' => false, 'message' => 'ID da lição inválido']);
    exit;
}

// Buscar o progresso atual do usuário nesta lição (Etapa 1 a 4)
$stmtUserProg = $pdo->prepare("SELECT stage, max_stage, score_percent FROM user_progress WHERE user_id = ? AND lesson_id = ?");
$stmtUserProg->execute([$userId, $lessonId]);
$lessonProgress = $stmtUserProg->fetch(PDO::FETCH_ASSOC);

$currentLessonStage = $requestedStage > 0 ? min(4, max(1, $requestedStage)) : ($lessonProgress ? (int)$lessonProgress['stage'] : 1);
$maxUnlockedStage = $lessonProgress ? (int)$lessonProgress['max_stage'] : 1;

// 1. Total de questões resolvidas pelo usuário para calcular a variação a cada 50
$stmtAns = $pdo->prepare("SELECT COUNT(*) FROM user_answers WHERE user_id = ?");
$stmtAns->execute([$userId]);
$totalAnswered = (int) $stmtAns->fetchColumn();
$bossVariant = floor($totalAnswered / 50);

$isBossMode = ($requestedMode === 'boss' || $currentLessonStage === 4);
$isSkipAhead = ($requestedMode === 'skip_ahead');

// MODO SIMULADO DE AVANÇO CUMULATIVO (JUMP AHEAD)
if ($isSkipAhead) {
    // 1. Descobrir a unidade e matéria desta lição alvo
    $stmtTarget = $pdo->prepare("
        SELECT l.id, l.order_index AS lesson_order, u.id AS unit_id, u.order_index AS unit_order, u.subject_id 
        FROM lessons l 
        JOIN units u ON l.unit_id = u.id 
        WHERE l.id = ?
    ");
    $stmtTarget->execute([$lessonId]);
    $targetLesson = $stmtTarget->fetch(PDO::FETCH_ASSOC);

    $questions = [];
    $accumulatedLessonIds = [];
    if ($targetLesson) {
        $subjectId = $targetLesson['subject_id'];
        $targetUnitOrder = $targetLesson['unit_order'];
        $targetLessonOrder = $targetLesson['lesson_order'];

        // Buscar todos os IDs de lições da mesma matéria até esta lição
        $stmtCumulLessons = $pdo->prepare("
            SELECT l.id, l.title 
            FROM lessons l 
            JOIN units u ON l.unit_id = u.id 
            WHERE u.subject_id = ? 
              AND (u.order_index < ? OR (u.order_index = ? AND l.order_index <= ?))
            ORDER BY u.order_index ASC, l.order_index ASC
        ");
        $stmtCumulLessons->execute([$subjectId, $targetUnitOrder, $targetUnitOrder, $targetLessonOrder]);
        $cumulLessons = $stmtCumulLessons->fetchAll(PDO::FETCH_ASSOC);
        $accumulatedLessonIds = array_column($cumulLessons, 'id');
    }

    if (!empty($accumulatedLessonIds)) {
        $inPlaceholders = implode(',', array_fill(0, count($accumulatedLessonIds), '?'));
        
        // Pega até 10 questões distribuídas entre esses tópicos acumulados
        $sqlSkip = "
            SELECT q.id, q.exam_source, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.option_e, 
                   q.correct_option, q.explanation_text, q.difficulty, q.is_boss, l.title AS topic_title
            FROM questions q
            JOIN lessons l ON q.lesson_id = l.id
            WHERE q.lesson_id IN ($inPlaceholders)
            ORDER BY RAND()
            LIMIT 10
        ";
        $stmtQ = $pdo->prepare($sqlSkip);
        $stmtQ->execute($accumulatedLessonIds);
        $questions = $stmtQ->fetchAll(PDO::FETCH_ASSOC);
    }

    // Se por acaso tiver poucas questões cadastradas no acumulado, busca fallback
    if (count($questions) < 5) {
        $stmtQ = $pdo->prepare("
            SELECT q.id, q.exam_source, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.option_e, 
                   q.correct_option, q.explanation_text, q.difficulty, q.is_boss, l.title AS topic_title
            FROM questions q
            JOIN lessons l ON q.lesson_id = l.id
            WHERE q.lesson_id = ?
            ORDER BY RAND()
            LIMIT 10
        ");
        $stmtQ->execute([$lessonId]);
        $questions = $stmtQ->fetchAll(PDO::FETCH_ASSOC);
    }

    foreach ($questions as &$q) {
        $q['is_boss'] = false;
        $q['hide_resolution'] = false;
    }

    // Buscar informações da lição
    $stmtLInfo = $pdo->prepare("
        SELECT 
            l.title, 
            l.intro_text, 
            l.video_url, 
            l.video_title, 
            l.xp_reward, 
            u.title AS unit_title, 
            s.name AS subject_name, 
            s.slug AS subject_slug,
            s.color_hex AS subject_color
        FROM lessons l
        JOIN units u ON l.unit_id = u.id
        JOIN subjects s ON u.subject_id = s.id
        WHERE l.id = ?
    ");
    $stmtLInfo->execute([$lessonId]);
    $lessonDetails = $stmtLInfo->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'is_skip_ahead' => true,
        'is_boss_mode' => false,
        'boss_variant' => 1,
        'current_stage' => 1,
        'max_unlocked_stage' => 4,
        'total_stages' => 1,
        'accumulated_lessons_count' => count($accumulatedLessonIds),
        'total_answered' => $totalAnswered,
        'lesson_title' => 'Simulado de Avanço: ' . ($lessonDetails['title'] ?? 'Trilha'),
        'intro_text' => 'Acerte ao menos 70% deste simulado cumulativo para desbloquear e gabaritar todas as fases anteriores de uma só vez!',
        'video_url' => null,
        'video_title' => null,
        'xp_reward' => 100 + (count($accumulatedLessonIds) * 25),
        'unit_title' => $lessonDetails['unit_title'] ?? 'Simulado de Avanço',
        'subject_name' => $lessonDetails['subject_name'] ?? 'Geral',
        'subject_slug' => $lessonDetails['subject_slug'] ?? 'matematica',
        'subject_color' => $lessonDetails['subject_color'] ?? '#f59e0b',
        'total_questions' => count($questions),
        'questions' => $questions
    ]);
    exit;
}

// 2. Query que EXCLUI questões que o usuário JÁ ACERTOU nos últimos 5 dias
$sqlExcludeCorrect = "
    SELECT q.id, q.exam_source, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.option_e, q.correct_option, q.explanation_text, q.difficulty, q.is_boss 
    FROM questions q 
    WHERE q.lesson_id = ? 
      AND q.id NOT IN (
          SELECT question_id 
          FROM user_answers 
          WHERE user_id = ? 
            AND is_correct = 1 
            AND answered_at >= DATE_SUB(NOW(), INTERVAL 5 DAY)
      )
";

// 3. MODO POR ETAPAS (4 etapas por lição: 5 questões por etapa)
if ($currentLessonStage === 1) {
    // Etapa 1: Fundamentos (Fácil / Médio)
    $stmtQ = $pdo->prepare($sqlExcludeCorrect . " AND (q.difficulty = 'fácil' OR q.difficulty = 'médio') ORDER BY RAND() LIMIT 5");
    $stmtQ->execute([$lessonId, $userId]);
    $questions = $stmtQ->fetchAll();
} else if ($currentLessonStage === 2) {
    // Etapa 2: Aprofundamento (Médio)
    $stmtQ = $pdo->prepare($sqlExcludeCorrect . " AND q.difficulty = 'médio' ORDER BY RAND() LIMIT 5");
    $stmtQ->execute([$lessonId, $userId]);
    $questions = $stmtQ->fetchAll();
} else if ($currentLessonStage === 3) {
    // Etapa 3: Nível Vestibular Avançado (Médio / Difícil)
    $stmtQ = $pdo->prepare($sqlExcludeCorrect . " AND (q.difficulty = 'difícil' OR q.difficulty = 'médio') ORDER BY RAND() LIMIT 5");
    $stmtQ->execute([$lessonId, $userId]);
    $questions = $stmtQ->fetchAll();
} else {
    // Etapa 4: Desafio Mestre / Boss da Lição (Difícil / Boss)
    $stmtQ = $pdo->prepare($sqlExcludeCorrect . " AND (q.is_boss = 1 OR q.difficulty = 'difícil') ORDER BY RAND() LIMIT 5");
    $stmtQ->execute([$lessonId, $userId]);
    $questions = $stmtQ->fetchAll();
}

// Fallback universal: se o filtro não retornou pelo menos 4 questões, preenche com aleatórias do tópico
if (count($questions) < 4) {
    $stmtQ = $pdo->prepare("SELECT id, exam_source, question_text, option_a, option_b, option_c, option_d, option_e, correct_option, explanation_text, difficulty, is_boss 
                            FROM questions 
                            WHERE lesson_id = ? 
                            ORDER BY RAND() 
                            LIMIT 5");
    $stmtQ->execute([$lessonId]);
    $questions = $stmtQ->fetchAll();
}

foreach ($questions as &$q) {
    $q['is_boss'] = ($currentLessonStage === 4);
    $q['hide_resolution'] = false;
}

// Buscar informações ricas da lição (título, explicação detalhada, vídeo-aula, matéria e unidade)
$stmtLInfo = $pdo->prepare("
    SELECT 
        l.title, 
        l.intro_text, 
        l.video_url, 
        l.video_title, 
        l.xp_reward, 
        u.title AS unit_title, 
        s.name AS subject_name, 
        s.slug AS subject_slug,
        s.color_hex AS subject_color
    FROM lessons l
    JOIN units u ON l.unit_id = u.id
    JOIN subjects s ON u.subject_id = s.id
    WHERE l.id = ?
");
$stmtLInfo->execute([$lessonId]);
$lessonDetails = $stmtLInfo->fetch(PDO::FETCH_ASSOC);

echo json_encode([
    'success' => true,
    'is_boss_mode' => $isBossMode,
    'boss_variant' => $bossVariant + 1,
    'current_stage' => $currentLessonStage,
    'max_unlocked_stage' => $maxUnlockedStage,
    'total_stages' => 4,
    'total_answered' => $totalAnswered,
    'lesson_title' => $lessonDetails['title'] ?? 'Lição',
    'intro_text' => $lessonDetails['intro_text'] ?? null,
    'video_url' => $lessonDetails['video_url'] ?? null,
    'video_title' => $lessonDetails['video_title'] ?? null,
    'xp_reward' => (int)($lessonDetails['xp_reward'] ?? 35),
    'unit_title' => $lessonDetails['unit_title'] ?? 'Unidade de Estudos',
    'subject_name' => $lessonDetails['subject_name'] ?? 'Geral',
    'subject_slug' => $lessonDetails['subject_slug'] ?? 'matematica',
    'subject_color' => $lessonDetails['subject_color'] ?? '#6842c2',
    'total_questions' => count($questions),
    'questions' => $questions
]);
