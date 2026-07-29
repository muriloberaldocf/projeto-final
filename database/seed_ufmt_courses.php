<?php
/**
 * GERADOR COMPLETO DE CURSOS DA UFMT (UNIVERSIDADE FEDERAL DE MATO GROSSO)
 * Adiciona todas as opções de graduação da UFMT (Cuiabá, Sinop, Araguaia).
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(300);

echo "<h3>🏛️ Cadastrando Todos os Cursos e Notas de Corte da UFMT (Universidade Federal de Mato Grosso)...</h3>";

$ufmtCourses = [
    ['Medicina', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 795.40, 751.20, 80, 12],
    ['Medicina', 'UFMT', 'Sinop - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 788.90, 744.80, 60, 12],
    ['Engenharia de Software', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 728.50, 678.10, 40, 9],
    ['Ciência da Computação', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 741.20, 691.00, 50, 8],
    ['Sistemas de Informação', 'UFMT', 'Sinop - MT', 'Centro-Oeste', 'Noturno', 'Bacharelado', 715.40, 665.10, 40, 8],
    ['Direito', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Matutino', 'Bacharelado', 745.80, 695.30, 100, 10],
    ['Direito', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Noturno', 'Bacharelado', 742.10, 691.80, 100, 10],
    ['Odontologia', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 735.10, 685.00, 40, 10],
    ['Psicologia', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 731.40, 681.20, 40, 10],
    ['Arquitetura e Urbanismo', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 725.90, 675.40, 50, 10],
    ['Engenharia Civil', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 718.20, 665.10, 60, 10],
    ['Engenharia Florestal', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 685.40, 632.10, 60, 10],
    ['Engenharia Agrícola e Ambiental', 'UFMT', 'Sinop - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 678.90, 625.30, 40, 10],
    ['Agronomia', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 692.50, 639.20, 80, 10],
    ['Medicina Veterinária', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 718.90, 665.40, 50, 10],
    ['Medicina Veterinária', 'UFMT', 'Sinop - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 712.40, 659.10, 50, 10],
    ['Biomedicina', 'UFMT', 'Araguaia - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 721.50, 668.20, 40, 8],
    ['Enfermagem', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 688.40, 635.10, 60, 8],
    ['Farmácia', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 695.80, 642.50, 60, 10],
    ['Fisioterapia', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 702.10, 649.00, 40, 10],
    ['Nutrição', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 685.20, 632.00, 40, 8],
    ['Educação Física', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 658.90, 605.40, 60, 8],
    ['Educação Física', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Noturno', 'Licenciatura', 648.20, 595.10, 60, 8],
    ['Design', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 692.40, 639.10, 40, 8],
    ['Jornalismo', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 705.80, 652.30, 40, 8],
    ['Publicidade e Propaganda', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 701.20, 648.00, 40, 8],
    ['Administração', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Noturno', 'Bacharelado', 702.40, 649.10, 80, 8],
    ['Ciências Econômicas', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Noturno', 'Bacharelado', 698.10, 645.00, 60, 8],
    ['Ciências Contábeis', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Noturno', 'Bacharelado', 689.50, 636.20, 80, 8],
    ['Pedagogia', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Noturno', 'Licenciatura', 652.40, 599.10, 80, 8],
    ['História', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Noturno', 'Licenciatura', 665.80, 612.40, 60, 8],
    ['Geografia', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Noturno', 'Licenciatura', 658.20, 605.00, 60, 8],
    ['Letras (Português/Inglês)', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Noturno', 'Licenciatura', 662.10, 608.90, 80, 8],
    ['Química', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 672.40, 619.10, 40, 8],
    ['Física', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 678.50, 625.20, 40, 8],
    ['Matemática', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 671.80, 618.50, 40, 8],
    ['Ciências Biológicas', 'UFMT', 'Cuiabá - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 698.20, 645.10, 60, 8]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($ufmtCourses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🏛️ Todos os Cursos da UFMT Cadastrados com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($ufmtCourses) . " cursos da Universidade Federal de Mato Grosso (UFMT)</strong> no Guia Nacional!</p>
</div>";
