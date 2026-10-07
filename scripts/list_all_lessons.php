<?php
require_once __DIR__ . '/../config/db.php';

$lessons = $pdo->query("
    SELECT s.name as subject_name, s.slug as subject_slug, u.title as unit_title, l.id as lesson_id, l.title as lesson_title, COUNT(q.id) as q_count
    FROM subjects s
    JOIN units u ON u.subject_id = s.id
    JOIN lessons l ON l.unit_id = u.id
    LEFT JOIN questions q ON q.lesson_id = l.id
    GROUP BY l.id
    ORDER BY s.id, u.order_index, l.order_index
")->fetchAll();

echo "Total de lições: " . count($lessons) . "\n\n";

$bySubject = [];
foreach ($lessons as $l) {
    $bySubject[$l['subject_name']][] = $l;
}

foreach ($bySubject as $subName => $list) {
    echo "=== {$subName} (" . count($list) . " lições) ===\n";
    foreach ($list as $item) {
        echo " - Lição {$item['lesson_id']}: {$item['lesson_title']} ({$item['q_count']} questões)\n";
    }
    echo "\n";
}
