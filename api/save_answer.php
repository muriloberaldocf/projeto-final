<?php
/**
 * API DE REGISTRO DE RESPOSTA EM TEMPO REAL - APROVAQUEST
 * Grava no banco user_answers se a resposta foi correta ou incorreta para alimentar a janela de 5 dias sem repetição.
 */
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

$userId = $_SESSION['user_id'] ?? 1;
$data = json_decode(file_get_contents('php://input'), true);

$questionId = filter_var($data['question_id'] ?? 0, FILTER_VALIDATE_INT);
$chosenOption = strtolower(trim($data['chosen_option'] ?? ''));
$isCorrect = filter_var($data['is_correct'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;

if (!$questionId || empty($chosenOption)) {
    echo json_encode(['success' => false, 'message' => 'Parâmetros inválidos']);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO user_answers (user_id, question_id, chosen_option, is_correct, answered_at) VALUES (?, ?, ?, ?, NOW())");
$stmt->execute([$userId, $questionId, $chosenOption, $isCorrect]);

echo json_encode(['success' => true]);
