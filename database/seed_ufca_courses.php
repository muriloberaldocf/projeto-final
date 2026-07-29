<?php
/**
 * GERADOR COMPLETO DE CURSOS DA UFCA (UNIVERSIDADE FEDERAL DO CARIRI - CE)
 * Adiciona todas as opções de graduação da UFCA (Barbalha, Juazeiro do Norte, Crato, Brejo Santo).
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(300);

echo "<h3>🏛️ Cadastrando Todos os Cursos e Notas de Corte da UFCA (Universidade Federal do Cariri - CE)...</h3>";

$ufcaCourses = [
    ['Medicina', 'UFCA', 'Barbalha - CE', 'Nordeste', 'Integral', 'Bacharelado', 792.10, 748.50, 80, 12],
    ['Engenharia de Software', 'UFCA', 'Juazeiro do Norte - CE', 'Nordeste', 'Integral', 'Bacharelado', 721.40, 671.20, 40, 9],
    ['Ciência da Computação', 'UFCA', 'Juazeiro do Norte - CE', 'Nordeste', 'Integral', 'Bacharelado', 732.50, 682.00, 40, 8],
    ['Engenharia Civil', 'UFCA', 'Juazeiro do Norte - CE', 'Nordeste', 'Integral', 'Bacharelado', 708.90, 655.40, 50, 10],
    ['Engenharia de Materiais', 'UFCA', 'Juazeiro do Norte - CE', 'Nordeste', 'Integral', 'Bacharelado', 672.10, 618.90, 40, 10],
    ['Agronomia', 'UFCA', 'Crato - CE', 'Nordeste', 'Integral', 'Bacharelado', 668.50, 615.20, 60, 10],
    ['Medicina Veterinária', 'UFCA', 'Crato - CE', 'Nordeste', 'Integral', 'Bacharelado', 705.80, 652.40, 50, 10],
    ['Enfermagem', 'UFCA', 'Barbalha - CE', 'Nordeste', 'Integral', 'Bacharelado', 685.20, 632.00, 50, 8],
    ['Jornalismo', 'UFCA', 'Juazeiro do Norte - CE', 'Nordeste', 'Integral', 'Bacharelado', 698.40, 645.10, 40, 8],
    ['Design de Produto', 'UFCA', 'Juazeiro do Norte - CE', 'Nordeste', 'Integral', 'Bacharelado', 688.90, 635.60, 40, 8],
    ['Administração', 'UFCA', 'Juazeiro do Norte - CE', 'Nordeste', 'Noturno', 'Bacharelado', 692.10, 638.90, 60, 8],
    ['Administração Pública', 'UFCA', 'Juazeiro do Norte - CE', 'Nordeste', 'Noturno', 'Bacharelado', 678.50, 625.10, 50, 8],
    ['Pedagogia', 'UFCA', 'Brejo Santo - CE', 'Nordeste', 'Noturno', 'Licenciatura', 641.80, 588.50, 60, 8],
    ['Música', 'UFCA', 'Juazeiro do Norte - CE', 'Nordeste', 'Integral', 'Licenciatura', 652.40, 599.10, 30, 8],
    ['Filosofia', 'UFCA', 'Juazeiro do Norte - CE', 'Nordeste', 'Noturno', 'Licenciatura', 638.90, 585.40, 40, 8],
    ['Química', 'UFCA', 'Brejo Santo - CE', 'Nordeste', 'Integral', 'Licenciatura', 651.20, 598.00, 40, 8],
    ['Física', 'UFCA', 'Brejo Santo - CE', 'Nordeste', 'Integral', 'Licenciatura', 655.80, 602.50, 40, 8],
    ['Matemática', 'UFCA', 'Brejo Santo - CE', 'Nordeste', 'Integral', 'Licenciatura', 648.50, 595.20, 40, 8],
    ['Ciências Biológicas', 'UFCA', 'Brejo Santo - CE', 'Nordeste', 'Integral', 'Licenciatura', 665.40, 612.10, 50, 8]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($ufcaCourses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🏛️ Todos os Cursos da UFCA Cadastrados com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($ufcaCourses) . " cursos da Universidade Federal do Cariri (UFCA - CE)</strong> no Guia Nacional!</p>
</div>";
