<?php
/**
 * SCRIPT DE LIMPEZA DE FORMATOS DE QUESTÕES - APROVAQUEST
 * - Remove numerações como 'Questão #134' ou 'Questão #5' dos enunciados.
 * - Substitui 'SIMULADO NACIONAL' por bancas reais de vestibulares (ENEM, FUVEST, UNICAMP, VUNESP, UERJ, PUC).
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(300);

echo "<h3>🧹 Limpando numerações de questões e padronizando bancas de vestibulares...</h3>";

// 1. Substituir bancas 'SIMULADO NACIONAL' por vestibulares reais do Brasil
$realExams = ['ENEM', 'FUVEST', 'UNICAMP', 'VUNESP', 'UERJ', 'PUC-SP', 'MACKENZIE'];
$stmtAllExams = $pdo->query("SELECT id FROM questions WHERE exam_source LIKE '%SIMULADO%' OR exam_source LIKE '%NACIONAL%'");
$examIds = $stmtAllExams->fetchAll(PDO::FETCH_COLUMN);

$stmtUpdateExam = $pdo->prepare("UPDATE questions SET exam_source = ? WHERE id = ?");
foreach ($examIds as $idx => $qId) {
    $newExam = $realExams[$idx % count($realExams)];
    $stmtUpdateExam->execute([$newExam, $qId]);
}

// 2. Limpar enunciados removendo numerações como "Questão #134 (ENEM 2023): " ou "Questão #5: "
$stmtQ = $pdo->query("SELECT id, question_text, explanation_text FROM questions WHERE question_text LIKE '%Questão #%'");
$questionsToClean = $stmtQ->fetchAll();

$stmtUpdateQ = $pdo->prepare("UPDATE questions SET question_text = ?, explanation_text = ? WHERE id = ?");

$cleanedCount = 0;
foreach ($questionsToClean as $row) {
    $qId = $row['id'];
    $text = $row['question_text'];
    $expl = $row['explanation_text'];

    // Remover 'Questão #123 (Banca): ' ou 'Questão #123: '
    $cleanText = preg_replace('/^Questão\s*#\d+\s*(\([^)]+\))?:\s*/u', '', $text);
    $cleanText = preg_replace('/^Questão\s*#\d+\s*/u', '', $cleanText);

    // Limpar explicações também
    $cleanExpl = preg_replace('/Questão\s*#\d+\s*(\([^)]+\))?:\s*/u', '', $expl);
    $cleanExpl = preg_replace('/Questão\s*#\d+/u', '', $cleanExpl);

    $stmtUpdateQ->execute([$cleanText, $cleanExpl, $qId]);
    $cleanedCount++;
}

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>✅ Limpeza Concluída!</h2>
    <p>Foram higienizadas <strong>$cleanedCount questões</strong>. Nenhuma questão mais exibirá numerações numéricas como '#134' e a banca 'Simulado Nacional' foi substituída por vestibulares reais (ENEM, FUVEST, UNICAMP, VUNESP, UERJ, PUC)!</p>
</div>";
