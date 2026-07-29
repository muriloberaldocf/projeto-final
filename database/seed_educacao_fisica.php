<?php
/**
 * GERADOR DEDICADO DE CURSOS DE EDUCAÇÃO FÍSICA NO BRASIL - APROVAQUEST
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(300);

echo "<h3>⚽ Adicionando Variações de Educação Física (Bacharelado e Licenciatura) em Universidades do Brasil...</h3>";

$edFisicaCourses = [
    ['Educação Física', 'USP', 'São Paulo - SP', 'Sudeste', 'Integral', 'Bacharelado', 685.30, 632.10, 90, 8],
    ['Educação Física', 'USP', 'São Paulo - SP', 'Sudeste', 'Noturno', 'Licenciatura', 668.50, 615.20, 90, 8],
    ['Educação Física', 'UNICAMP', 'Campinas - SP', 'Sudeste', 'Integral', 'Bacharelado', 692.10, 639.40, 60, 8],
    ['Educação Física', 'UNESP', 'Rio Claro - SP', 'Sudeste', 'Integral', 'Bacharelado', 672.40, 619.10, 50, 8],
    ['Educação Física', 'UFRJ', 'Rio de Janeiro - RJ', 'Sudeste', 'Integral', 'Bacharelado', 678.90, 625.30, 100, 8],
    ['Educação Física', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Matutino', 'Bacharelado', 681.20, 628.00, 80, 8],
    ['Educação Física', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Noturno', 'Licenciatura', 662.80, 609.50, 80, 8],
    ['Educação Física', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 675.40, 622.10, 60, 8],
    ['Educação Física', 'UFSC', 'Florianópolis - SC', 'Sul', 'Integral', 'Bacharelado', 671.80, 618.30, 50, 8],
    ['Educação Física', 'UFPR', 'Curitiba - PR', 'Sul', 'Matutino', 'Bacharelado', 669.30, 616.00, 60, 8],
    ['Educação Física', 'UnB', 'Brasília - DF', 'Centro-Oeste', 'Integral', 'Bacharelado', 684.10, 631.20, 60, 8],
    ['Educação Física', 'UFPE', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 665.70, 612.40, 80, 8],
    ['Educação Física', 'UFC', 'Fortaleza - CE', 'Nordeste', 'Integral', 'Bacharelado', 661.90, 608.50, 80, 8],
    ['Educação Física', 'UFBA', 'Salvador - BA', 'Nordeste', 'Integral', 'Bacharelado', 658.40, 605.10, 80, 8],
    ['Educação Física', 'UFAM', 'Manaus - AM', 'Norte', 'Integral', 'Bacharelado', 645.20, 592.10, 50, 8],
    ['Educação Física', 'UFPA', 'Belém - PA', 'Norte', 'Integral', 'Licenciatura', 648.90, 595.60, 60, 8]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($edFisicaCourses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>⚽ Cursos de Educação Física Adicionados com Sucesso!</h2>
    <p>Foram adicionadas <strong>16 variações de Educação Física (Bacharelado e Licenciatura)</strong> de universidades de todas as regiões do Brasil (USP, UNICAMP, UNESP, UFRJ, UFMG, UFRGS, UFSC, UFPR, UnB, UFPE, UFC, UFBA, UFAM, UFPA)!</p>
</div>";
