<?php
/**
 * MIGRAÇÃO: Resumos Teóricos Completos para Todas as 120 Lições
 * 
 * Este script substitui os resumos genéricos por conteúdo educacional
 * real e específico para cada lição do HipoGabarito.
 * 
 * Uso: php database/migrate_complete_theory.php
 */

require_once __DIR__ . '/../config/db.php';

echo "=== MIGRAÇÃO: Resumos Teóricos Completos ===\n\n";

// Carregar conteúdo de cada matéria
$files = [
    'Matemática & Raciocínio' => __DIR__ . '/theory_matematica.php',
    'Física'                  => __DIR__ . '/theory_fisica.php',
    'Química'                 => __DIR__ . '/theory_quimica.php',
    'Biologia'                => __DIR__ . '/theory_biologia.php',
    'Português & Literatura'  => __DIR__ . '/theory_portugues.php',
    'História & Geografia'    => __DIR__ . '/theory_humanas.php',
];

$totalUpdated = 0;
$totalErrors = 0;

$stmt = $pdo->prepare("UPDATE lessons SET intro_text = ? WHERE id = ?");

foreach ($files as $subject => $file) {
    if (!file_exists($file)) {
        echo "⚠️  ARQUIVO NÃO ENCONTRADO: {$file}\n";
        continue;
    }

    echo "📚 Processando: {$subject}...\n";
    $theories = require $file;

    if (!is_array($theories)) {
        echo "   ❌ ERRO: O arquivo não retornou um array válido.\n";
        $totalErrors++;
        continue;
    }

    $count = 0;
    foreach ($theories as $lessonId => $content) {
        try {
            $stmt->execute([$content, (int)$lessonId]);
            $count++;
        } catch (Exception $e) {
            echo "   ❌ Erro na lição #{$lessonId}: " . $e->getMessage() . "\n";
            $totalErrors++;
        }
    }

    echo "   ✅ {$count} lições atualizadas.\n";
    $totalUpdated += $count;
}

echo "\n=== RESULTADO FINAL ===\n";
echo "✅ Total de lições atualizadas: {$totalUpdated}\n";
echo "❌ Total de erros: {$totalErrors}\n";

// Verificação
$check = $pdo->query("
    SELECT 
        COUNT(*) as total,
        AVG(LENGTH(intro_text)) as avg_len,
        MIN(LENGTH(intro_text)) as min_len,
        MAX(LENGTH(intro_text)) as max_len
    FROM lessons
    WHERE id <= 120
")->fetch(PDO::FETCH_ASSOC);

echo "\n=== VERIFICAÇÃO ===\n";
echo "Total de lições (ID <= 120): {$check['total']}\n";
echo "Tamanho médio do resumo: " . round($check['avg_len']) . " caracteres\n";
echo "Menor resumo: {$check['min_len']} caracteres\n";
echo "Maior resumo: {$check['max_len']} caracteres\n";

// Amostra
echo "\n=== AMOSTRA (Lição #1) ===\n";
$sample = $pdo->query("SELECT intro_text FROM lessons WHERE id = 1")->fetchColumn();
echo substr($sample, 0, 500) . "...\n";

echo "\n✅ Migração concluída com sucesso!\n";
