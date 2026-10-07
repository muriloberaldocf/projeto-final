<?php
require_once __DIR__ . '/../config/db.php';

$total = $pdo->query("SELECT COUNT(*) FROM questions")->fetchColumn();
echo "TOTAL ATUAL DE QUESTÕES NO BANCO: $total\n\n";

$avgLen = $pdo->query("SELECT AVG(CHAR_LENGTH(question_text)) FROM questions")->fetchColumn();
echo "COMPRIMENTO MÉDIO DO ENUNCIADO: " . round($avgLen) . " caracteres (antes era 94 em matemática, 112 em português!)\n\n";

echo "QUESTÕES CURTAS (< 160 caracteres): " . $pdo->query("SELECT COUNT(*) FROM questions WHERE CHAR_LENGTH(question_text) < 160")->fetchColumn() . " (antes eram 902!)\n\n";

echo "DISTRIBUIÇÃO POR BANCA:\n";
foreach ($pdo->query("SELECT exam_source, COUNT(*) c FROM questions GROUP BY exam_source ORDER BY c DESC LIMIT 20") as $r) {
    echo " - {$r['exam_source']}: {$r['c']}\n";
}

echo "\nAMOSTRA DE 5 QUESTÕES ALEATÓRIAS DO NOVO BANCO:\n";
foreach ($pdo->query("SELECT id, exam_source, LEFT(question_text, 250) as snippet, CHAR_LENGTH(question_text) as len FROM questions ORDER BY RAND() LIMIT 5") as $q) {
    echo "\n#{$q['id']} [{$q['exam_source']}] ({$q['len']} caracteres)\n";
    echo "{$q['snippet']}...\n";
}
