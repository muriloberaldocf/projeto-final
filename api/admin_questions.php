<?php
/**
 * API DO PAINEL DO PROFESSOR (CRUD DE QUESTÕES COM 5 ALTERNATIVAS A, B, C, D, E)
 */
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($action === 'create') {
    $lessonId = filter_input(INPUT_POST, 'lesson_id', FILTER_VALIDATE_INT);
    $examSource = trim($_POST['exam_source'] ?? '');
    $questionText = trim($_POST['question_text'] ?? '');
    $optionA = trim($_POST['option_a'] ?? '');
    $optionB = trim($_POST['option_b'] ?? '');
    $optionC = trim($_POST['option_c'] ?? '');
    $optionD = trim($_POST['option_d'] ?? '');
    $optionE = trim($_POST['option_e'] ?? '');
    $correctOption = strtolower(trim($_POST['correct_option'] ?? 'a'));
    $explanationText = trim($_POST['explanation_text'] ?? '');
    $difficulty = $_POST['difficulty'] ?? 'médio';

    if (!$lessonId || empty($questionText) || empty($optionA) || empty($optionB) || empty($optionC) || empty($optionD) || empty($optionE)) {
        echo json_encode(['success' => false, 'message' => 'Preencha todas as 5 alternativas (A, B, C, D, E) e o enunciado!']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO questions (lesson_id, exam_source, question_text, option_a, option_b, option_c, option_d, option_e, correct_option, explanation_text, difficulty) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$lessonId, $examSource, $questionText, $optionA, $optionB, $optionC, $optionD, $optionE, $correctOption, $explanationText, $difficulty]);

    echo json_encode(['success' => true, 'message' => 'Questão cadastrada com sucesso!']);
    exit;
}

if ($action === 'delete') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if ($id) {
        $stmt = $pdo->prepare("DELETE FROM questions WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true, 'message' => 'Questão removida!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'ID inválido']);
    }
    exit;
}

if ($action === 'list') {
    $stmt = $pdo->query("SELECT q.*, l.title as lesson_title, u.title as unit_title, s.name as subject_name 
                         FROM questions q 
                         JOIN lessons l ON q.lesson_id = l.id 
                         JOIN units u ON l.unit_id = u.id 
                         JOIN subjects s ON u.subject_id = s.id 
                         ORDER BY q.id DESC");
    echo json_encode(['success' => true, 'questions' => $stmt->fetchAll()]);
    exit;
}
