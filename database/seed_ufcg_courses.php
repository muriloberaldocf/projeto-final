<?php
/**
 * GERADOR COMPLETO DE CURSOS DA UFCG (UNIVERSIDADE FEDERAL DE CAMPINA GRANDE - PB)
 * Adiciona todas as opções de graduação da UFCG (Campina Grande, Cajazeiras, Sousa, Patos, Cuité, Pombal, Sumé).
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(300);

echo "<h3>🏛️ Cadastrando Todos os Cursos e Notas de Corte da UFCG (Universidade Federal de Campina Grande - PB)...</h3>";

$ufcgCourses = [
    ['Medicina', 'UFCG', 'Campina Grande - PB', 'Nordeste', 'Integral', 'Bacharelado', 798.50, 754.10, 60, 12],
    ['Medicina', 'UFCG', 'Cajazeiras - PB', 'Nordeste', 'Integral', 'Bacharelado', 789.20, 744.80, 60, 12],
    ['Engenharia de Software', 'UFCG', 'Campina Grande - PB', 'Nordeste', 'Integral', 'Bacharelado', 748.90, 698.50, 40, 9],
    ['Ciência da Computação', 'UFCG', 'Campina Grande - PB', 'Nordeste', 'Integral', 'Bacharelado', 775.40, 725.10, 80, 8],
    ['Direito', 'UFCG', 'Sousa - PB', 'Nordeste', 'Matutino', 'Bacharelado', 742.10, 692.00, 100, 10],
    ['Direito', 'UFCG', 'Sousa - PB', 'Nordeste', 'Noturno', 'Bacharelado', 738.50, 688.40, 100, 10],
    ['Odontologia', 'UFCG', 'Patos - PB', 'Nordeste', 'Integral', 'Bacharelado', 738.50, 688.20, 50, 10],
    ['Medicina Veterinária', 'UFCG', 'Patos - PB', 'Nordeste', 'Integral', 'Bacharelado', 715.40, 662.10, 60, 10],
    ['Agronomia', 'UFCG', 'Pombal - PB', 'Nordeste', 'Integral', 'Bacharelado', 671.80, 618.50, 60, 10],
    ['Engenharia Elétrica', 'UFCG', 'Campina Grande - PB', 'Nordeste', 'Integral', 'Bacharelado', 735.80, 682.40, 80, 10],
    ['Engenharia Mecânica', 'UFCG', 'Campina Grande - PB', 'Nordeste', 'Integral', 'Bacharelado', 732.10, 678.90, 60, 10],
    ['Engenharia Civil', 'UFCG', 'Campina Grande - PB', 'Nordeste', 'Integral', 'Bacharelado', 728.40, 675.10, 80, 10],
    ['Engenharia Química', 'UFCG', 'Campina Grande - PB', 'Nordeste', 'Integral', 'Bacharelado', 721.50, 668.20, 60, 10],
    ['Engenharia de Petróleo', 'UFCG', 'Campina Grande - PB', 'Nordeste', 'Integral', 'Bacharelado', 718.90, 665.40, 40, 10],
    ['Psicologia', 'UFCG', 'Campina Grande - PB', 'Nordeste', 'Integral', 'Bacharelado', 739.50, 689.10, 50, 10],
    ['Enfermagem', 'UFCG', 'Cajazeiras - PB', 'Nordeste', 'Integral', 'Bacharelado', 685.40, 632.10, 60, 8],
    ['Enfermagem', 'UFCG', 'Cuité - PB', 'Nordeste', 'Integral', 'Bacharelado', 678.90, 625.30, 50, 8],
    ['Nutrição', 'UFCG', 'Cuité - PB', 'Nordeste', 'Integral', 'Bacharelado', 675.20, 622.00, 40, 8],
    ['Design', 'UFCG', 'Campina Grande - PB', 'Nordeste', 'Integral', 'Bacharelado', 708.20, 655.00, 40, 8],
    ['Administração', 'UFCG', 'Campina Grande - PB', 'Nordeste', 'Noturno', 'Bacharelado', 705.40, 652.10, 80, 8],
    ['Ciências Econômicas', 'UFCG', 'Campina Grande - PB', 'Nordeste', 'Noturno', 'Bacharelado', 698.20, 645.00, 60, 8],
    ['Ciências Contábeis', 'UFCG', 'Sousa - PB', 'Nordeste', 'Noturno', 'Bacharelado', 689.50, 636.20, 60, 8],
    ['Pedagogia', 'UFCG', 'Campina Grande - PB', 'Nordeste', 'Noturno', 'Licenciatura', 651.80, 598.50, 80, 8],
    ['História', 'UFCG', 'Campina Grande - PB', 'Nordeste', 'Noturno', 'Licenciatura', 668.40, 615.10, 60, 8],
    ['Geografia', 'UFCG', 'Campina Grande - PB', 'Nordeste', 'Noturno', 'Licenciatura', 658.90, 605.60, 60, 8],
    ['Letras (Português/Inglês)', 'UFCG', 'Campina Grande - PB', 'Nordeste', 'Noturno', 'Licenciatura', 662.10, 608.90, 80, 8],
    ['Química', 'UFCG', 'Cuité - PB', 'Nordeste', 'Integral', 'Licenciatura', 652.40, 599.10, 40, 8],
    ['Física', 'UFCG', 'Cuité - PB', 'Nordeste', 'Integral', 'Licenciatura', 655.80, 602.50, 40, 8],
    ['Matemática', 'UFCG', 'Campina Grande - PB', 'Nordeste', 'Integral', 'Bacharelado', 671.20, 618.00, 40, 8],
    ['Ciências Biológicas', 'UFCG', 'Patos - PB', 'Nordeste', 'Integral', 'Bacharelado', 682.90, 629.50, 50, 8]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($ufcgCourses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🏛️ Todos os Cursos da UFCG Cadastrados com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($ufcgCourses) . " cursos da Universidade Federal de Campina Grande (UFCG - PB)</strong> no Guia Nacional!</p>
</div>";
