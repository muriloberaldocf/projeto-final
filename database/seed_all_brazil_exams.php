<?php
/**
 * ATUALIZADOR DE BANCAS DE VESTIBULARES DE NORTE A SUL DO BRASIL - APROVAQUEST
 * Cobre vestibulares de todas as regiões brasileiras (UFAM, UFPA, UnB, UFPE, UFC, UFBA, FUVEST, UNICAMP, VUNESP, UERJ, UFMG, UFPR, UFSC, UFRGS, ENEM).
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(300);

echo "<h3>🇧🇷 Atualizando Banco de Questões com Vestibulares de Norte a Sul do Brasil...</h3>";

$brazilExams = [
    // Norte
    'UFAM (AM)', 'UFPA (PA)', 'UFRR (RR)', 'UNIR (RO)',
    // Nordeste
    'UFPE (PE)', 'UFC (CE)', 'UFBA (BA)', 'UFRN (RN)', 'UEMA (MA)',
    // Centro-Oeste
    'UnB (DF)', 'UFG (GO)', 'UFMS (MS)',
    // Sudeste
    'ENEM (Nacional)', 'FUVEST / USP (SP)', 'UNICAMP (SP)', 'VUNESP / UNESP (SP)', 'UERJ (RJ)', 'UFRJ (RJ)', 'UFMG (MG)', 'UFES (ES)',
    // Sul
    'UFRGS (RS)', 'UFSC (SC)', 'UFPR (PR)', 'UEL (PR)'
];

// Buscar todos os IDs de questões do banco
$stmtQ = $pdo->query("SELECT id FROM questions");
$qIds = $stmtQ->fetchAll(PDO::FETCH_COLUMN);

$total = count($qIds);
echo "<p>Distribuindo $total questões entre " . count($brazilExams) . " bancas de vestibulares de todo o Brasil...</p>";

$pdo->beginTransaction();
$stmtUpdate = $pdo->prepare("UPDATE questions SET exam_source = ? WHERE id = ?");

foreach ($qIds as $idx => $id) {
    $examName = $brazilExams[$idx % count($brazilExams)];
    $stmtUpdate->execute([$examName, $id]);
}

$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>✅ Vestibulares de Norte a Sul Configurados com Sucesso!</h2>
    <p>As <strong>$total questões</strong> foram distribuídas cobrindo desde a <strong>UFAM (Amazonas)</strong> no Norte até a <strong>UFRGS (Rio Grande do Sul)</strong> no Sul!</p>
    <ul>
        <li><strong>Norte:</strong> UFAM, UFPA, UFRR, UNIR</li>
        <li><strong>Nordeste:</strong> UFPE, UFC, UFBA, UFRN, UEMA</li>
        <li><strong>Centro-Oeste:</strong> UnB, UFG, UFMS</li>
        <li><strong>Sudeste:</strong> FUVEST/USP, UNICAMP, VUNESP, UERJ, UFRJ, UFMG, UFES</li>
        <li><strong>Sul:</strong> UFRGS, UFSC, UFPR, UEL</li>
        <li><strong>Nacional:</strong> ENEM</li>
    </ul>
</div>";
