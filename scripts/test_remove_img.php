<?php
require_once __DIR__ . '/../config/db.php';

$totalBefore = $pdo->query("SELECT COUNT(*) FROM questions")->fetchColumn();
$withImg = $pdo->query("SELECT COUNT(*) FROM questions WHERE question_text LIKE '%![%'")->fetchColumn();
echo "Total antes: $totalBefore | Com markdown de imagem ![ : $withImg\n";

$remaining = $totalBefore - $withImg;
echo "Restariam: $remaining questões 100% autossuficientes sem imagens!\n\n";

// Verificar lições com as questões restantes
$stmt = $pdo->query("
    SELECT l.id, l.title, s.name as sub, COUNT(q.id) as q_count
    FROM lessons l
    JOIN units u ON l.unit_id = u.id
    JOIN subjects s ON u.subject_id = s.id
    LEFT JOIN questions q ON q.lesson_id = l.id AND q.question_text NOT LIKE '%![%'
    GROUP BY l.id
");

$zeroCount = 0;
$below4 = 0;
foreach ($stmt as $r) {
    if ($r['q_count'] == 0) $zeroCount++;
    elseif ($r['q_count'] < 4) $below4++;
}

echo "Lições com 0 questões restantes: $zeroCount\n";
echo "Lições com 1 a 3 questões restantes: $below4\n";
