<?php
/**
 * Script de Limpeza: Remove todas as questões com o padrão
 * "Qual a resolução correta para a questão sobre '...'"
 * 
 * Este script primeiro lista as questões encontradas, depois as deleta.
 */

require_once __DIR__ . '/../config/db.php';

echo "=== LIMPEZA DE QUESTÕES COM PADRÃO 'Qual a resolução correta para a questão sobre' ===\n\n";

// 1. Primeiro, contar e listar as questões que serão removidas
$pattern = '%Qual a resolução correta para a questão sobre%';

$stmtCount = $pdo->prepare("SELECT COUNT(*) as total FROM questions WHERE question_text LIKE :pattern");
$stmtCount->execute(['pattern' => $pattern]);
$result = $stmtCount->fetch();
$total = $result['total'];

echo "Total de questões encontradas com o padrão: {$total}\n\n";

if ($total === 0) {
    echo "Nenhuma questão encontrada. O banco já está limpo!\n";
    exit;
}

// 2. Listar algumas amostras (primeiras 10)
$stmtSample = $pdo->prepare("SELECT id, lesson_id, exam_source, LEFT(question_text, 120) as trecho FROM questions WHERE question_text LIKE :pattern LIMIT 10");
$stmtSample->execute(['pattern' => $pattern]);
$samples = $stmtSample->fetchAll();

echo "--- Amostra das primeiras " . count($samples) . " questões ---\n";
foreach ($samples as $q) {
    echo "  ID: {$q['id']} | Lição: {$q['lesson_id']} | Fonte: {$q['exam_source']} | Trecho: {$q['trecho']}...\n";
}
echo "\n";

// 3. Verificar se há respostas de usuários vinculadas a essas questões
$stmtAnswers = $pdo->prepare("
    SELECT COUNT(*) as total_answers 
    FROM user_answers ua 
    INNER JOIN questions q ON ua.question_id = q.id 
    WHERE q.question_text LIKE :pattern
");
$stmtAnswers->execute(['pattern' => $pattern]);
$answersResult = $stmtAnswers->fetch();
$totalAnswers = $answersResult['total_answers'];

echo "Respostas de usuários vinculadas a essas questões: {$totalAnswers}\n";

if ($totalAnswers > 0) {
    echo "As respostas dos usuários também serão removidas (CASCADE ou manualmente).\n";
    
    // Deletar respostas de usuários vinculadas primeiro
    $stmtDeleteAnswers = $pdo->prepare("
        DELETE ua FROM user_answers ua 
        INNER JOIN questions q ON ua.question_id = q.id 
        WHERE q.question_text LIKE :pattern
    ");
    $stmtDeleteAnswers->execute(['pattern' => $pattern]);
    $deletedAnswers = $stmtDeleteAnswers->rowCount();
    echo "  -> {$deletedAnswers} respostas de usuários removidas.\n\n";
}

// 4. Deletar as questões
$stmtDelete = $pdo->prepare("DELETE FROM questions WHERE question_text LIKE :pattern");
$stmtDelete->execute(['pattern' => $pattern]);
$deletedQuestions = $stmtDelete->rowCount();

echo "=== RESULTADO FINAL ===\n";
echo "Questões removidas com sucesso: {$deletedQuestions}\n";

// 5. Verificar contagem total restante
$stmtRemaining = $pdo->query("SELECT COUNT(*) as total FROM questions");
$remaining = $stmtRemaining->fetch();
echo "Total de questões restantes no banco: {$remaining['total']}\n";

echo "\nLimpeza concluída com sucesso!\n";
