<?php
require_once __DIR__ . '/../config/db.php';

echo "=== ATUALIZANDO BANCAS PARA SEPARAR TEMAS REAIS DE POSSÍVEIS TEMAS ===\n";

// 1. Atualizar todos os simulados / temas inéditos para 'POSSIVEIS'
$stmt = $pdo->prepare("UPDATE redacao_temas SET banca = 'POSSIVEIS' WHERE origem LIKE '%Simulado%' OR origem LIKE '%Inédit%' OR banca = 'SIMULADO'");
$stmt->execute();
echo "Simulados / Propostas Inéditas atualizadas para 'POSSIVEIS': " . $stmt->rowCount() . "\n";

// 2. Garantir que temas reais do ENEM estão com banca = 'ENEM'
$stmt = $pdo->prepare("UPDATE redacao_temas SET banca = 'ENEM' WHERE origem LIKE 'ENEM%'");
$stmt->execute();
echo "Temas Reais do ENEM garantidos como 'ENEM': " . $stmt->rowCount() . "\n";

// 3. Garantir que temas reais da FUVEST estão com banca = 'FUVEST'
$stmt = $pdo->prepare("UPDATE redacao_temas SET banca = 'FUVEST' WHERE origem LIKE 'FUVEST%'");
$stmt->execute();
echo "Temas Reais da FUVEST garantidos como 'FUVEST': " . $stmt->rowCount() . "\n";

// 4. Garantir que temas reais da UNESP estão com banca = 'UNESP'
$stmt = $pdo->prepare("UPDATE redacao_temas SET banca = 'UNESP' WHERE origem LIKE 'UNESP%'");
$stmt->execute();
echo "Temas Reais da UNESP garantidos como 'UNESP': " . $stmt->rowCount() . "\n";

// 5. Garantir que temas reais da UNICAMP estão com banca = 'UNICAMP'
$stmt = $pdo->prepare("UPDATE redacao_temas SET banca = 'UNICAMP' WHERE origem LIKE 'UNICAMP%'");
$stmt->execute();
echo "Temas Reais da UNICAMP garantidos como 'UNICAMP': " . $stmt->rowCount() . "\n";

// 6. Garantir que temas reais da UERJ estão com banca = 'UERJ'
$stmt = $pdo->prepare("UPDATE redacao_temas SET banca = 'UERJ' WHERE origem LIKE 'UERJ%'");
$stmt->execute();
echo "Temas Reais da UERJ garantidos como 'UERJ': " . $stmt->rowCount() . "\n";

// 7. Redigir
$stmt = $pdo->prepare("UPDATE redacao_temas SET banca = 'REDIGIR' WHERE origem LIKE 'Plataforma Redigir%'");
$stmt->execute();
echo "Temas da Plataforma Redigir garantidos como 'REDIGIR': " . $stmt->rowCount() . "\n";

echo "\n=== CONTAGEM FINAL POR BANCA ===\n";
$stmt = $pdo->query("SELECT banca, COUNT(*) as c FROM redacao_temas GROUP BY banca ORDER BY c DESC");
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    echo "{$row['banca']}: {$row['c']} temas\n";
}
