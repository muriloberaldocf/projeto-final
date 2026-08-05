<?php
/**
 * API DE OBTER QUESTÕES DA LIÇÃO - APROVAQUEST
 * - Sistema de vidas removido.
 * - Filtra para NÃO REPETIR questões que o usuário já acertou nos últimos 5 dias.
 */
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

$userId = $_SESSION['user_id'] ?? 1;
$lessonId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$requestedMode = $_GET['mode'] ?? '';

if (!$lessonId) {
    echo json_encode(['success' => false, 'message' => 'ID da lição inválido']);
    exit;
}

// 1. Total de questões resolvidas pelo usuário para calcular a variação a cada 50
$stmtAns = $pdo->prepare("SELECT COUNT(*) FROM user_answers WHERE user_id = ?");
$stmtAns->execute([$userId]);
$totalAnswered = (int) $stmtAns->fetchColumn();
$bossVariant = floor($totalAnswered / 50);

$isBossMode = ($requestedMode === 'boss');

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

if ($isBossMode) {
    // MODO CHEFÃO BOSS: Exclui as já acertadas nos últimos 5 dias e seleciona as com is_boss = 1
    $offset = ($bossVariant * 5) % 100;
    
    $stmtQ = $pdo->prepare($sqlExcludeCorrect . " AND (q.is_boss = 1 OR q.difficulty = 'difícil') LIMIT 5 OFFSET ?");
    $stmtQ->execute([$lessonId, $userId, $offset]);
    $questions = $stmtQ->fetchAll();

    // Fallback: se respondeu a todas recentemente, pega qualquer uma do tópico
    if (count($questions) < 5) {
        $stmtQ = $pdo->prepare("SELECT id, exam_source, question_text, option_a, option_b, option_c, option_d, option_e, correct_option, explanation_text, difficulty, is_boss 
                                FROM questions 
                                WHERE lesson_id = ? 
                                ORDER BY is_boss DESC, RAND() 
                                LIMIT 5");
        $stmtQ->execute([$lessonId]);
        $questions = $stmtQ->fetchAll();
    }

    foreach ($questions as &$q) {
        $q['is_boss'] = true;
        $q['hide_resolution'] = true;
    }
} else {
    // MODO PADRÃO: 10 questões que o usuário NÃO ACERTOU nos últimos 5 dias
    $stmtQ = $pdo->prepare($sqlExcludeCorrect . " ORDER BY RAND() LIMIT 10");
    $stmtQ->execute([$lessonId, $userId]);
    $questions = $stmtQ->fetchAll();

    // Fallback: se já acertou todas no período de 5 dias, recarrega questões do tópico
    if (count($questions) < 5) {
        $stmtQ = $pdo->prepare("SELECT id, exam_source, question_text, option_a, option_b, option_c, option_d, option_e, correct_option, explanation_text, difficulty, is_boss 
                                FROM questions 
                                WHERE lesson_id = ? 
                                ORDER BY RAND() 
                                LIMIT 10");
        $stmtQ->execute([$lessonId]);
        $questions = $stmtQ->fetchAll();
    }

    foreach ($questions as &$q) {
        $q['is_boss'] = false;
        $q['hide_resolution'] = false;
    }
}

// Buscar informações da lição (título e explicação prévia intro_text)
$stmtLInfo = $pdo->prepare("SELECT title, intro_text FROM lessons WHERE id = ?");
$stmtLInfo->execute([$lessonId]);
$lessonDetails = $stmtLInfo->fetch(PDO::FETCH_ASSOC);

echo json_encode([
    'success' => true,
    'is_boss_mode' => $isBossMode,
    'boss_variant' => $bossVariant + 1,
    'total_answered' => $totalAnswered,
    'lesson_title' => $lessonDetails['title'] ?? 'Lição',
    'intro_text' => $lessonDetails['intro_text'] ?? null,
    'questions' => $questions
]);
