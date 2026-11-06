<?php
/**
 * API DE REGISTRO DE RESPOSTA EM TEMPO REAL — HIPOGABARITO
 * - Autenticação obrigatória (sem fallback arbitrário para user 1).
 * - O servidor consulta o gabarito oficial e determina se a resposta foi correta (Anti-Cheat).
 * - Grava no banco user_answers com integridade garantida.
 */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

$userId = (int)$_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

$questionId = filter_var($data['question_id'] ?? 0, FILTER_VALIDATE_INT);
$chosenOption = strtolower(trim($data['chosen_option'] ?? ''));

if (!$questionId || !in_array($chosenOption, ['a', 'b', 'c', 'd', 'e'])) {
    echo json_encode(['success' => false, 'message' => 'Parâmetros de resposta inválidos.']);
    exit;
}

// O servidor busca a resposta oficial no banco — nunca confia no cliente
$stmtQ = $pdo->prepare("SELECT correct_option, explanation_text FROM questions WHERE id = ?");
$stmtQ->execute([$questionId]);
$qRow = $stmtQ->fetch();

if (!$qRow) {
    echo json_encode(['success' => false, 'message' => 'Questão não encontrada no banco.']);
    exit;
}

$correctOption = strtolower(trim($qRow['correct_option']));
$explanationText = trim($qRow['explanation_text'] ?? '');
$isCorrect = ($correctOption === $chosenOption) ? 1 : 0;

$stmt = $pdo->prepare("INSERT INTO user_answers (user_id, question_id, chosen_option, is_correct, answered_at) VALUES (?, ?, ?, ?, NOW())");
$stmt->execute([$userId, $questionId, $chosenOption, $isCorrect]);

// Checar Combo de Acertos Consecutivos (10x e 20x)
$comboMilestone = null;
if ($isCorrect) {
    $currentStreak = getConsecutiveCorrectStreak($pdo, $userId);
    if ($currentStreak === 20) {
        $comboMilestone = [
            'type' => 'combo_20x',
            'title' => 'Sequência Imparável 20x! 🎯',
            'message' => 'Incrível! Você acertou 20 questões seguidas sem nenhum erro!'
        ];
        unlockAchievement($pdo, $userId, 'combo_20x');
        recordMuralEvent(
            $pdo, 
            $userId, 
            'combo_20x', 
            'Sequência Imparável 20x 🎯', 
            'Acertou 20 questões consecutivas nos exercícios de vestibular sem errar nada!',
            'bi-bullseye',
            'amber'
        );
    } else if ($currentStreak === 10) {
        $comboMilestone = [
            'type' => 'combo_10x',
            'title' => 'Sequência 10x Quente! 🔥',
            'message' => 'Você está em chamas! 10 acertos seguidos!'
        ];
        unlockAchievement($pdo, $userId, 'combo_10x');
        recordMuralEvent(
            $pdo, 
            $userId, 
            'combo_10x', 
            'Sequência 10x Quente 🔥', 
            'Emplacou 10 acertos seguidos sem nenhum erro nos exercícios!',
            'bi-fire',
            'amber'
        );
    }
}

echo json_encode([
    'success' => true,
    'is_correct' => (bool)$isCorrect,
    'correct_option' => $correctOption,
    'explanation_text' => $explanationText,
    'combo_milestone' => $comboMilestone
]);
