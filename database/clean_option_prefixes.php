<?php
/**
 * RECONSTRUTOR E FORMATADOR DE QUESTÕES REAIS DE VESTIBULARES (ENEM, FUVEST, UNICAMP, VUNESP, SENAI, UERJ)
 * Remove prefixos duplicados ("A) A)") e garante enunciados limpos e fiéis aos vestibulares.
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(600);

echo "<h3>✨ Limpando e Formatando Questões Reais de Vestibulares...</h3>";

// 1. Limpar opções que tenham "A) ", "B) ", "C) ", "D) ", "E) " duplicados no banco
$pdo->exec("
    UPDATE questions 
    SET 
        option_a = REGEXP_REPLACE(option_a, '^[A-Ea-e][\\)\\.]\\s*', ''),
        option_b = REGEXP_REPLACE(option_b, '^[A-Ea-e][\\)\\.]\\s*', ''),
        option_c = REGEXP_REPLACE(option_c, '^[A-Ea-e][\\)\\.]\\s*', ''),
        option_d = REGEXP_REPLACE(option_d, '^[A-Ea-e][\\)\\.]\\s*', ''),
        option_e = REGEXP_REPLACE(option_e, '^[A-Ea-e][\\)\\.]\\s*', '')
");

// Rodar uma segunda limpeza simples para garantir
$pdo->exec("UPDATE questions SET option_a = TRIM(SUBSTRING_INDEX(option_a, ') ', -1)) WHERE option_a LIKE 'A) %' OR option_a LIKE 'a) %'");
$pdo->exec("UPDATE questions SET option_b = TRIM(SUBSTRING_INDEX(option_b, ') ', -1)) WHERE option_b LIKE 'B) %' OR option_b LIKE 'b) %'");
$pdo->exec("UPDATE questions SET option_c = TRIM(SUBSTRING_INDEX(option_c, ') ', -1)) WHERE option_c LIKE 'C) %' OR option_c LIKE 'c) %'");
$pdo->exec("UPDATE questions SET option_d = TRIM(SUBSTRING_INDEX(option_d, ') ', -1)) WHERE option_d LIKE 'D) %' OR option_d LIKE 'd) %'");
$pdo->exec("UPDATE questions SET option_e = TRIM(SUBSTRING_INDEX(option_e, ') ', -1)) WHERE option_e LIKE 'E) %' OR option_e LIKE 'e) %'");

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🎉 Formatação Limpa de Questões Concluída!</h2>
    <p>Todas as prefixações duplicadas ('A) A)') foram completamente removidas!</p>
</div>";
