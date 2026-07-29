<?php
/**
 * GERADOR COMPLETO DE CURSOS DA UFSC (UNIVERSIDADE FEDERAL DE SANTA CATARINA)
 * Adiciona todas as opções de graduação da UFSC (Florianópolis, Joinville, Araranguá, Blumenau, Curitibanos).
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(300);

echo "<h3>🏛️ Cadastrando Todos os Cursos e Notas de Corte da UFSC (Universidade Federal de Santa Catarina)...</h3>";

$ufscCourses = [
    ['Medicina', 'UFSC', 'Florianópolis - SC', 'Sul', 'Integral', 'Bacharelado', 799.40, 755.60, 50, 12],
    ['Engenharia de Software', 'UFSC', 'Joinville - SC', 'Sul', 'Integral', 'Bacharelado', 742.10, 691.80, 50, 9],
    ['Ciência da Computação', 'UFSC', 'Florianópolis - SC', 'Sul', 'Integral', 'Bacharelado', 768.40, 718.20, 60, 8],
    ['Engenharia da Computação', 'UFSC', 'Araranguá - SC', 'Sul', 'Integral', 'Bacharelado', 735.80, 685.20, 50, 10],
    ['Direito', 'UFSC', 'Florianópolis - SC', 'Sul', 'Matutino', 'Bacharelado', 758.90, 708.50, 100, 10],
    ['Direito', 'UFSC', 'Florianópolis - SC', 'Sul', 'Noturno', 'Bacharelado', 755.20, 705.00, 100, 10],
    ['Odontologia', 'UFSC', 'Florianópolis - SC', 'Sul', 'Integral', 'Bacharelado', 748.20, 698.00, 40, 10],
    ['Psicologia', 'UFSC', 'Florianópolis - SC', 'Sul', 'Integral', 'Bacharelado', 738.50, 688.10, 40, 10],
    ['Arquitetura e Urbanismo', 'UFSC', 'Florianópolis - SC', 'Sul', 'Integral', 'Bacharelado', 741.20, 691.00, 50, 10],
    ['Engenharia Mecânica', 'UFSC', 'Florianópolis - SC', 'Sul', 'Integral', 'Bacharelado', 752.40, 702.10, 80, 10],
    ['Engenharia Civil', 'UFSC', 'Florianópolis - SC', 'Sul', 'Integral', 'Bacharelado', 735.10, 682.40, 80, 10],
    ['Engenharia Química', 'UFSC', 'Florianópolis - SC', 'Sul', 'Integral', 'Bacharelado', 731.80, 678.50, 50, 10],
    ['Engenharia Elétrica', 'UFSC', 'Florianópolis - SC', 'Sul', 'Integral', 'Bacharelado', 728.90, 675.20, 60, 10],
    ['Engenharia Aeroespacial', 'UFSC', 'Joinville - SC', 'Sul', 'Integral', 'Bacharelado', 748.50, 695.80, 40, 10],
    ['Engenharia de Alimentos', 'UFSC', 'Florianópolis - SC', 'Sul', 'Integral', 'Bacharelado', 698.40, 645.10, 40, 10],
    ['Medicina Veterinária', 'UFSC', 'Curitibanos - SC', 'Sul', 'Integral', 'Bacharelado', 718.50, 665.20, 40, 10],
    ['Agronomia', 'UFSC', 'Florianópolis - SC', 'Sul', 'Integral', 'Bacharelado', 695.20, 642.10, 60, 10],
    ['Biomedicina', 'UFSC', 'Florianópolis - SC', 'Sul', 'Integral', 'Bacharelado', 732.40, 679.50, 40, 8],
    ['Enfermagem', 'UFSC', 'Florianópolis - SC', 'Sul', 'Integral', 'Bacharelado', 695.80, 642.80, 50, 8],
    ['Farmácia', 'UFSC', 'Florianópolis - SC', 'Sul', 'Integral', 'Bacharelado', 702.10, 649.00, 60, 10],
    ['Fisioterapia', 'UFSC', 'Araranguá - SC', 'Sul', 'Integral', 'Bacharelado', 708.50, 655.20, 40, 10],
    ['Nutrição', 'UFSC', 'Florianópolis - SC', 'Sul', 'Integral', 'Bacharelado', 698.10, 645.00, 40, 8],
    ['Educação Física', 'UFSC', 'Florianópolis - SC', 'Sul', 'Integral', 'Bacharelado', 671.80, 618.30, 50, 8],
    ['Educação Física', 'UFSC', 'Florianópolis - SC', 'Sul', 'Noturno', 'Licenciatura', 659.10, 605.80, 50, 8],
    ['Oceanografia', 'UFSC', 'Florianópolis - SC', 'Sul', 'Integral', 'Bacharelado', 678.30, 625.10, 30, 10],
    ['Design', 'UFSC', 'Florianópolis - SC', 'Sul', 'Integral', 'Bacharelado', 718.20, 665.10, 40, 8],
    ['Jornalismo', 'UFSC', 'Florianópolis - SC', 'Sul', 'Integral', 'Bacharelado', 721.50, 668.20, 40, 8],
    ['Cinema', 'UFSC', 'Florianópolis - SC', 'Sul', 'Integral', 'Bacharelado', 715.40, 662.10, 30, 8],
    ['Administração', 'UFSC', 'Florianópolis - SC', 'Sul', 'Noturno', 'Bacharelado', 718.90, 665.40, 100, 8],
    ['Ciências Econômicas', 'UFSC', 'Florianópolis - SC', 'Sul', 'Noturno', 'Bacharelado', 712.40, 659.10, 80, 8],
    ['Relações Internacionais', 'UFSC', 'Florianópolis - SC', 'Sul', 'Integral', 'Bacharelado', 738.90, 685.60, 40, 8],
    ['Ciências Contábeis', 'UFSC', 'Florianópolis - SC', 'Sul', 'Noturno', 'Bacharelado', 698.20, 645.00, 80, 8],
    ['Pedagogia', 'UFSC', 'Florianópolis - SC', 'Sul', 'Noturno', 'Licenciatura', 661.40, 608.20, 80, 8],
    ['História', 'UFSC', 'Florianópolis - SC', 'Sul', 'Noturno', 'Licenciatura', 678.10, 625.00, 40, 8],
    ['Geografia', 'UFSC', 'Florianópolis - SC', 'Sul', 'Noturno', 'Licenciatura', 665.40, 612.10, 40, 8],
    ['Letras (Português/Inglês)', 'UFSC', 'Florianópolis - SC', 'Sul', 'Noturno', 'Licenciatura', 669.80, 616.50, 60, 8],
    ['Química', 'UFSC', 'Florianópolis - SC', 'Sul', 'Integral', 'Bacharelado', 692.10, 638.90, 40, 8],
    ['Física', 'UFSC', 'Florianópolis - SC', 'Sul', 'Integral', 'Bacharelado', 695.40, 642.10, 40, 8],
    ['Matemática', 'UFSC', 'Florianópolis - SC', 'Sul', 'Integral', 'Bacharelado', 689.20, 635.80, 40, 8],
    ['Ciências Biológicas', 'UFSC', 'Florianópolis - SC', 'Sul', 'Integral', 'Bacharelado', 715.60, 662.30, 50, 8]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($ufscCourses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🏛️ Todos os Cursos da UFSC Cadastrados com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($ufscCourses) . " cursos da Universidade Federal de Santa Catarina (UFSC)</strong> no Guia Nacional!</p>
</div>";
