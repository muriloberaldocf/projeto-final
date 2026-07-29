<?php
/**
 * GERADOR COMPLETO DE CURSOS DA UFJ (UNIVERSIDADE FEDERAL DE JATAÍ - GO)
 * Adiciona todas as opções de graduação da UFJ e suas notas de corte.
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(300);

echo "<h3>🏛️ Cadastrando Todos os Cursos e Notas de Corte da UFJ (Universidade Federal de Jataí - GO)...</h3>";

$ufjCourses = [
    ['Medicina', 'UFJ', 'Jataí - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 791.50, 747.20, 60, 12],
    ['Engenharia de Software', 'UFJ', 'Jataí - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 715.40, 665.10, 40, 9],
    ['Ciência da Computação', 'UFJ', 'Jataí - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 724.80, 674.20, 40, 8],
    ['Direito', 'UFJ', 'Jataí - GO', 'Centro-Oeste', 'Noturno', 'Bacharelado', 735.20, 685.00, 60, 10],
    ['Agronomia', 'UFJ', 'Jataí - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 685.90, 632.40, 60, 10],
    ['Medicina Veterinária', 'UFJ', 'Jataí - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 712.40, 659.10, 50, 10],
    ['Zootecnia', 'UFJ', 'Jataí - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 668.20, 615.00, 40, 10],
    ['Engenharia Florestal', 'UFJ', 'Jataí - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 662.50, 609.20, 40, 10],
    ['Psicologia', 'UFJ', 'Jataí - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 718.90, 668.50, 40, 10],
    ['Enfermagem', 'UFJ', 'Jataí - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 678.50, 625.10, 50, 8],
    ['Fisioterapia', 'UFJ', 'Jataí - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 695.20, 642.00, 40, 10],
    ['Biomedicina', 'UFJ', 'Jataí - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 712.80, 659.50, 40, 8],
    ['Educação Física', 'UFJ', 'Jataí - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 648.90, 595.40, 50, 8],
    ['Pedagogia', 'UFJ', 'Jataí - GO', 'Centro-Oeste', 'Noturno', 'Licenciatura', 642.10, 588.90, 60, 8],
    ['História', 'UFJ', 'Jataí - GO', 'Centro-Oeste', 'Noturno', 'Licenciatura', 652.40, 599.10, 40, 8],
    ['Geografia', 'UFJ', 'Jataí - GO', 'Centro-Oeste', 'Noturno', 'Licenciatura', 648.50, 595.20, 40, 8],
    ['Letras (Português/Inglês)', 'UFJ', 'Jataí - GO', 'Centro-Oeste', 'Noturno', 'Licenciatura', 651.80, 598.50, 50, 8],
    ['Química', 'UFJ', 'Jataí - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 662.10, 608.90, 40, 8],
    ['Física', 'UFJ', 'Jataí - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 665.40, 612.10, 40, 8],
    ['Matemática', 'UFJ', 'Jataí - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 658.90, 605.60, 40, 8],
    ['Ciências Biológicas', 'UFJ', 'Jataí - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 682.40, 629.10, 50, 8]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($ufjCourses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🏛️ Todos os Cursos da UFJ Cadastrados com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($ufjCourses) . " cursos da Universidade Federal de Jataí (UFJ - GO)</strong> no Guia Nacional!</p>
</div>";
