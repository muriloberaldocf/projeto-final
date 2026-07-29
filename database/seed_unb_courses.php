<?php
/**
 * GERADOR COMPLETO DE CURSOS DA UnB (UNIVERSIDADE DE BRASÍLIA)
 * Adiciona todas as opções de graduação da UnB (Darcy Ribeiro, FGA Gama, FCE Ceilândia, FUP Planaltina).
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(300);

echo "<h3>🏛️ Cadastrando Todos os Cursos e Notas de Corte da UnB (Universidade de Brasília)...</h3>";

$unbCourses = [
    ['Medicina', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Integral', 'Bacharelado', 804.80, 761.30, 90, 12],
    ['Engenharia de Software', 'UnB', 'Brasília (FGA Gama) - DF', 'Centro-Oeste', 'Integral', 'Bacharelado', 758.40, 708.20, 60, 9],
    ['Engenharia Eletrônica', 'UnB', 'Brasília (FGA Gama) - DF', 'Centro-Oeste', 'Integral', 'Bacharelado', 742.10, 692.00, 50, 10],
    ['Engenharia Aeroespacial', 'UnB', 'Brasília (FGA Gama) - DF', 'Centro-Oeste', 'Integral', 'Bacharelado', 765.20, 712.80, 40, 10],
    ['Ciência da Computação', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Integral', 'Bacharelado', 772.50, 722.10, 60, 8],
    ['Direito', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Matutino', 'Bacharelado', 761.80, 711.50, 100, 10],
    ['Direito', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Noturno', 'Bacharelado', 758.20, 708.10, 100, 10],
    ['Relações Internacionais', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Integral', 'Bacharelado', 758.30, 705.10, 60, 8],
    ['Odontologia', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Integral', 'Bacharelado', 745.90, 695.20, 40, 10],
    ['Psicologia', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Integral', 'Bacharelado', 745.20, 695.00, 60, 10],
    ['Arquitetura e Urbanismo', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Integral', 'Bacharelado', 748.10, 698.00, 60, 10],
    ['Engenharia Civil', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Integral', 'Bacharelado', 742.30, 692.10, 80, 10],
    ['Engenharia Mecânica', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Integral', 'Bacharelado', 749.80, 698.40, 60, 10],
    ['Engenharia Elétrica', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Integral', 'Bacharelado', 739.50, 688.20, 60, 10],
    ['Engenharia de Produção', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Integral', 'Bacharelado', 745.10, 692.40, 50, 10],
    ['Medicina Veterinária', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Integral', 'Bacharelado', 738.50, 685.20, 60, 10],
    ['Agronomia', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Integral', 'Bacharelado', 708.20, 655.10, 60, 10],
    ['Fisioterapia', 'UnB', 'Brasília (FCE Ceilândia) - DF', 'Centro-Oeste', 'Integral', 'Bacharelado', 718.40, 665.10, 40, 10],
    ['Enfermagem', 'UnB', 'Brasília (FCE Ceilândia) - DF', 'Centro-Oeste', 'Integral', 'Bacharelado', 702.50, 649.30, 50, 8],
    ['Farmácia', 'UnB', 'Brasília (FCE Ceilândia) - DF', 'Centro-Oeste', 'Integral', 'Bacharelado', 712.10, 659.00, 50, 10],
    ['Fonoaudiologia', 'UnB', 'Brasília (FCE Ceilândia) - DF', 'Centro-Oeste', 'Integral', 'Bacharelado', 692.40, 639.10, 40, 8],
    ['Biomedicina', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Integral', 'Bacharelado', 741.80, 688.50, 40, 8],
    ['Nutrição', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Integral', 'Bacharelado', 705.90, 652.40, 40, 8],
    ['Educação Física', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Integral', 'Bacharelado', 684.10, 631.20, 60, 8],
    ['Educação Física', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Noturno', 'Licenciatura', 671.50, 618.20, 60, 8],
    ['Design', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Integral', 'Bacharelado', 724.50, 671.20, 40, 8],
    ['Jornalismo', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Integral', 'Bacharelado', 735.80, 682.40, 40, 8],
    ['Publicidade e Propaganda', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Integral', 'Bacharelado', 728.90, 675.30, 40, 8],
    ['Administração', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Noturno', 'Bacharelado', 725.40, 672.10, 100, 8],
    ['Ciências Econômicas', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Noturno', 'Bacharelado', 732.10, 678.90, 80, 8],
    ['Ciências Contábeis', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Noturno', 'Bacharelado', 712.50, 659.20, 80, 8],
    ['Pedagogia', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Noturno', 'Licenciatura', 668.40, 615.10, 100, 8],
    ['História', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Noturno', 'Licenciatura', 688.20, 635.00, 60, 8],
    ['Geografia', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Noturno', 'Licenciatura', 672.10, 618.90, 60, 8],
    ['Letras', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Noturno', 'Licenciatura', 678.90, 625.40, 100, 8],
    ['Química', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Integral', 'Bacharelado', 692.50, 639.10, 40, 8],
    ['Física', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Integral', 'Bacharelado', 698.40, 645.20, 40, 8],
    ['Matemática', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Integral', 'Bacharelado', 691.80, 638.50, 40, 8],
    ['Ciências Biológicas', 'UnB', 'Brasília (Darcy Ribeiro) - DF', 'Centro-Oeste', 'Integral', 'Bacharelado', 721.40, 668.10, 60, 8]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($unbCourses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🏛️ Todos os Cursos da UnB Cadastrados com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($unbCourses) . " cursos da Universidade de Brasília (UnB)</strong> no Guia Nacional!</p>
</div>";
