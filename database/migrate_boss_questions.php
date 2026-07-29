<?php
/**
 * MIGRATION PARA SUPORTE A QUESTÕES BOSS / CHEFÃO (COM CADEADO, VARIAÇÃO A CADA 50 E SEM RESOLUÇÃO)
 */
require_once __DIR__ . '/../config/db.php';

try {
    // 1. Adicionar coluna is_boss se não existir
    $pdo->exec("ALTER TABLE questions ADD COLUMN is_boss TINYINT(1) DEFAULT 0");
} catch (\PDOException $e) {
    // Coluna já existe
}

// 2. Definir questões com dificuldade 'difícil' ou de índice específico como BOSS
$pdo->exec("UPDATE questions SET is_boss = 1 WHERE difficulty = 'difícil'");

// 3. Garantir que cada lição tenha pelo menos 5 questões marcadas como BOSS
$stmtLessons = $pdo->query("SELECT DISTINCT lesson_id FROM questions");
while ($lRow = $stmtLessons->fetch()) {
    $lId = $lRow['lesson_id'];
    $pdo->exec("UPDATE questions SET is_boss = 1 WHERE lesson_id = $lId LIMIT 10");
}

echo "Migração do Modo Chefão Boss concluída com sucesso!";
