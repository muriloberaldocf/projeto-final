<?php
/**
 * SCRIPT DE IMPORTAÇÃO DE QUESTÕES - VESTILINGO
 * Popula automaticamente o banco de dados com lote de questões adicionais de vestibulares.
 */
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

$bulkQuestions = [
    [
        'lesson_id' => 1,
        'exam_source' => 'ENEM 2022',
        'question_text' => 'Um investidor aplicou um capital de R$ 10.000,00 a juros simples, sob uma taxa de 2% ao mês, durante um período de 12 meses. Ao final desse prazo, qual foi o montante total resgatado pelo investidor?',
        'option_a' => 'A) R$ 12.400,00',
        'option_b' => 'B) R$ 12.000,00',
        'option_c' => 'C) R$ 11.200,00',
        'option_d' => 'D) R$ 10.240,00',
        'option_e' => 'E) R$ 14.400,00',
        'correct_option' => 'a',
        'explanation_text' => 'Fórmula dos juros simples: J = C * i * t.\nJ = 10.000 * 0,02 * 12 = R$ 2.400,00 de juros.\nMontante M = C + J = 10.000 + 2.400 = R$ 12.400,00.',
        'difficulty' => 'médio'
    ],
    [
        'lesson_id' => 3,
        'exam_source' => 'UNICAMP 2024',
        'question_text' => 'Dada a função quadrática f(x) = x² - 6x + 8, determine o ponto de mínimo dessa parábola (as coordenadas do seu vértice V(xv, yv)):',
        'option_a' => 'A) V(3, -1)',
        'option_b' => 'B) V(-3, 1)',
        'option_c' => 'C) V(6, 8)',
        'option_d' => 'D) V(3, 8)',
        'option_e' => 'E) V(0, 8)',
        'correct_option' => 'a',
        'explanation_text' => 'Xv = -b / (2a) = -(-6) / (2*1) = 6 / 2 = 3.\nYv = f(3) = 3² - 6(3) + 8 = 9 - 18 + 8 = -1.\nPortanto, o Vértice V = (3, -1).',
        'difficulty' => 'médio'
    ]
];

$insertedCount = 0;
$stmt = $pdo->prepare("INSERT INTO questions (lesson_id, exam_source, question_text, option_a, option_b, option_c, option_d, option_e, correct_option, explanation_text, difficulty) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

foreach ($bulkQuestions as $q) {
    $stmt->execute([
        $q['lesson_id'],
        $q['exam_source'],
        $q['question_text'],
        $q['option_a'],
        $q['option_b'],
        $q['option_c'],
        $q['option_d'],
        $q['option_e'],
        $q['correct_option'],
        $q['explanation_text'],
        $q['difficulty']
    ]);
    $insertedCount++;
}

echo json_encode([
    'success' => true,
    'message' => "$insertedCount questões de vestibular importadas com sucesso!"
]);
