<?php
/**
 * GERADOR COMPLETO DE CURSOS DA UFR (UNIVERSIDADE FEDERAL DE RONDONÓPOLIS - MT)
 * Adiciona todas as opções de graduação da UFR e suas notas de corte.
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(300);

echo "<h3>🏛️ Cadastrando Todos os Cursos e Notas de Corte da UFR (Universidade Federal de Rondonópolis - MT)...</h3>";

$ufrCourses = [
    ['Medicina', 'UFR', 'Rondonópolis - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 790.80, 746.50, 60, 12],
    ['Engenharia de Software', 'UFR', 'Rondonópolis - MT', 'Centro-Oeste', 'Noturno', 'Bacharelado', 712.40, 662.10, 40, 9],
    ['Ciência da Computação', 'UFR', 'Rondonópolis - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 721.80, 671.50, 40, 8],
    ['Sistemas de Informação', 'UFR', 'Rondonópolis - MT', 'Centro-Oeste', 'Noturno', 'Bacharelado', 702.50, 649.20, 40, 8],
    ['Direito', 'UFR', 'Rondonópolis - MT', 'Centro-Oeste', 'Noturno', 'Bacharelado', 738.90, 688.40, 60, 10],
    ['Engenharia Agrícola e Ambiental', 'UFR', 'Rondonópolis - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 672.40, 619.10, 40, 10],
    ['Engenharia Mecânica', 'UFR', 'Rondonópolis - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 705.80, 652.30, 40, 10],
    ['Engenharia Civil', 'UFR', 'Rondonópolis - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 698.50, 645.20, 40, 10],
    ['Agronomia', 'UFR', 'Rondonópolis - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 681.20, 628.00, 60, 10],
    ['Zootecnia', 'UFR', 'Rondonópolis - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 665.40, 612.10, 40, 10],
    ['Psicologia', 'UFR', 'Rondonópolis - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 715.20, 665.00, 40, 10],
    ['Enfermagem', 'UFR', 'Rondonópolis - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 674.80, 621.50, 50, 8],
    ['Administração', 'UFR', 'Rondonópolis - MT', 'Centro-Oeste', 'Noturno', 'Bacharelado', 688.90, 635.40, 60, 8],
    ['Ciências Econômicas', 'UFR', 'Rondonópolis - MT', 'Centro-Oeste', 'Noturno', 'Bacharelado', 682.10, 628.90, 50, 8],
    ['Ciências Contábeis', 'UFR', 'Rondonópolis - MT', 'Centro-Oeste', 'Noturno', 'Bacharelado', 678.50, 625.10, 60, 8],
    ['Pedagogia', 'UFR', 'Rondonópolis - MT', 'Centro-Oeste', 'Noturno', 'Licenciatura', 639.50, 586.20, 60, 8],
    ['História', 'UFR', 'Rondonópolis - MT', 'Centro-Oeste', 'Noturno', 'Licenciatura', 648.20, 595.10, 40, 8],
    ['Geografia', 'UFR', 'Rondonópolis - MT', 'Centro-Oeste', 'Noturno', 'Licenciatura', 641.80, 588.50, 40, 8],
    ['Letras (Português/Inglês)', 'UFR', 'Rondonópolis - MT', 'Centro-Oeste', 'Noturno', 'Licenciatura', 645.20, 592.00, 50, 8],
    ['Química', 'UFR', 'Rondonópolis - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 658.90, 605.60, 40, 8],
    ['Física', 'UFR', 'Rondonópolis - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 662.10, 608.90, 40, 8],
    ['Matemática', 'UFR', 'Rondonópolis - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 655.40, 602.10, 40, 8],
    ['Ciências Biológicas', 'UFR', 'Rondonópolis - MT', 'Centro-Oeste', 'Integral', 'Bacharelado', 678.90, 625.40, 50, 8]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($ufrCourses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🏛️ Todos os Cursos da UFR Cadastrados com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($ufrCourses) . " cursos da Universidade Federal de Rondonópolis (UFR - MT)</strong> no Guia Nacional!</p>
</div>";
