<?php
require_once __DIR__ . '/../config/db.php';

echo "=== RELATÓRIO FINAL DO BANCO DE QUESTÕES ===\n\n";

// Total geral
$total = $pdo->query("SELECT COUNT(*) FROM questions")->fetchColumn();
echo "TOTAL DE QUESTÕES NO BANCO: {$total}\n\n";

// Por matéria
$stmt = $pdo->query("
    SELECT s.name as materia, COUNT(q.id) as total_q
    FROM subjects s
    JOIN units u ON u.subject_id = s.id
    JOIN lessons l ON l.unit_id = u.id
    LEFT JOIN questions q ON q.lesson_id = l.id
    GROUP BY s.id, s.name
    ORDER BY s.order_index
");
echo "--- POR MATÉRIA ---\n";
foreach ($stmt as $r) {
    echo "  {$r['materia']}: {$r['total_q']} questões\n";
}

// Lições sem questão
$stmt2 = $pdo->query("
    SELECT l.id, l.title
    FROM lessons l
    LEFT JOIN questions q ON q.lesson_id = l.id
    WHERE q.id IS NULL
    ORDER BY l.id
");
$empty = $stmt2->fetchAll();
echo "\n--- LIÇÕES SEM QUESTÃO: " . count($empty) . " ---\n";
foreach ($empty as $r) {
    echo "  Lição [{$r['id']}] {$r['title']}\n";
}

// Verificar se há questões genéricas restantes
$generic = $pdo->query("SELECT COUNT(*) FROM questions WHERE question_text LIKE '%Qual a resolução correta%'")->fetchColumn();
echo "\nQuestões genéricas ('Qual a resolução correta...'): {$generic}\n";

// Boss questions
$boss = $pdo->query("SELECT COUNT(*) FROM questions WHERE is_boss = 1")->fetchColumn();
echo "Questões Boss: {$boss}\n";

// Distribuição de fontes
echo "\n--- POR FONTE DO VESTIBULAR ---\n";
$stmt3 = $pdo->query("SELECT exam_source, COUNT(*) as c FROM questions GROUP BY exam_source ORDER BY c DESC");
foreach ($stmt3 as $r) {
    echo "  {$r['exam_source']}: {$r['c']}\n";
}

// Distribuição de dificuldade
echo "\n--- POR DIFICULDADE ---\n";
$stmt4 = $pdo->query("SELECT difficulty, COUNT(*) as c FROM questions GROUP BY difficulty ORDER BY FIELD(difficulty, 'fácil', 'médio', 'difícil')");
foreach ($stmt4 as $r) {
    echo "  {$r['difficulty']}: {$r['c']}\n";
}
