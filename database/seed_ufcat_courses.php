<?php
/**
 * GERADOR COMPLETO DE CURSOS DA UFCAT (UNIVERSIDADE FEDERAL DE CATALÃO - GO)
 * Adiciona todas as opções de graduação da UFCAT e suas notas de corte.
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(300);

echo "<h3>🏛️ Cadastrando Todos os Cursos e Notas de Corte da UFCAT (Universidade Federal de Catalão - GO)...</h3>";

$ufcatCourses = [
    ['Medicina', 'UFCAT', 'Catalão - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 792.40, 748.10, 50, 12],
    ['Engenharia de Software', 'UFCAT', 'Catalão - GO', 'Centro-Oeste', 'Noturno', 'Bacharelado', 718.50, 668.20, 40, 9],
    ['Ciência da Computação', 'UFCAT', 'Catalão - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 728.90, 678.40, 40, 8],
    ['Engenharia Civil', 'UFCAT', 'Catalão - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 705.40, 652.10, 50, 10],
    ['Engenharia Mecânica', 'UFCAT', 'Catalão - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 712.30, 659.00, 40, 10],
    ['Engenharia de Minas', 'UFCAT', 'Catalão - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 685.20, 632.00, 40, 10],
    ['Engenharia de Produção', 'UFCAT', 'Catalão - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 702.10, 648.90, 40, 10],
    ['Psicologia', 'UFCAT', 'Catalão - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 721.40, 671.00, 40, 10],
    ['Enfermagem', 'UFCAT', 'Catalão - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 682.90, 629.50, 50, 8],
    ['Administração', 'UFCAT', 'Catalão - GO', 'Centro-Oeste', 'Noturno', 'Bacharelado', 692.50, 639.10, 60, 8],
    ['Pedagogia', 'UFCAT', 'Catalão - GO', 'Centro-Oeste', 'Noturno', 'Licenciatura', 645.80, 592.30, 60, 8],
    ['História', 'UFCAT', 'Catalão - GO', 'Centro-Oeste', 'Noturno', 'Licenciatura', 658.20, 605.00, 40, 8],
    ['Geografia', 'UFCAT', 'Catalão - GO', 'Centro-Oeste', 'Noturno', 'Licenciatura', 651.40, 598.10, 40, 8],
    ['Letras (Português/Inglês)', 'UFCAT', 'Catalão - GO', 'Centro-Oeste', 'Noturno', 'Licenciatura', 655.90, 602.40, 50, 8],
    ['Química', 'UFCAT', 'Catalão - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 668.40, 615.10, 40, 8],
    ['Física', 'UFCAT', 'Catalão - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 672.10, 618.90, 40, 8],
    ['Matemática', 'UFCAT', 'Catalão - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 665.30, 612.00, 40, 8],
    ['Ciências Biológicas', 'UFCAT', 'Catalão - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 689.50, 636.20, 50, 8]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($ufcatCourses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🏛️ Todos os Cursos da UFCAT Cadastrados com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($ufcatCourses) . " cursos da Universidade Federal de Catalão (UFCAT - GO)</strong> no Guia Nacional!</p>
</div>";
