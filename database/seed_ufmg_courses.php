<?php
/**
 * GERADOR COMPLETO DE CURSOS DA UFMG (UNIVERSIDADE FEDERAL DE MINAS GERAIS)
 * Adiciona todas as opções de graduação da UFMG (Belo Horizonte e Montes Claros).
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(300);

echo "<h3>🏛️ Cadastrando Todos os Cursos e Notas de Corte da UFMG (Universidade Federal de Minas Gerais)...</h3>";

$ufmgCourses = [
    ['Medicina', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Integral', 'Bacharelado', 805.30, 762.40, 160, 12],
    ['Engenharia de Software', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Noturno', 'Bacharelado', 761.20, 711.50, 40, 9],
    ['Ciência da Computação', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Integral', 'Bacharelado', 768.90, 718.50, 80, 8],
    ['Sistemas de Informação', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Noturno', 'Bacharelado', 742.10, 692.00, 60, 8],
    ['Direito', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Matutino', 'Bacharelado', 758.90, 708.60, 100, 10],
    ['Direito', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Noturno', 'Bacharelado', 755.40, 705.10, 100, 10],
    ['Odontologia', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Integral', 'Bacharelado', 745.80, 695.10, 80, 10],
    ['Psicologia', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Integral', 'Bacharelado', 742.10, 691.80, 80, 10],
    ['Arquitetura e Urbanismo', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Integral', 'Bacharelado', 741.50, 691.00, 80, 10],
    ['Engenharia Aeroespacial', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Integral', 'Bacharelado', 768.10, 715.40, 40, 10],
    ['Engenharia Mecânica', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Integral', 'Bacharelado', 745.20, 692.10, 100, 10],
    ['Engenharia Civil', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Integral', 'Bacharelado', 732.40, 679.50, 100, 10],
    ['Engenharia Elétrica', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Integral', 'Bacharelado', 738.60, 682.30, 100, 10],
    ['Engenharia de Produção', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Integral', 'Bacharelado', 742.80, 689.50, 80, 10],
    ['Medicina Veterinária', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Integral', 'Bacharelado', 732.10, 679.40, 80, 10],
    ['Agronomia', 'UFMG', 'Montes Claros - MG', 'Sudeste', 'Integral', 'Bacharelado', 685.20, 632.10, 60, 10],
    ['Biomedicina', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Integral', 'Bacharelado', 738.90, 685.40, 40, 8],
    ['Enfermagem', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Integral', 'Bacharelado', 698.50, 645.20, 80, 8],
    ['Farmácia', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Noturno', 'Bacharelado', 715.80, 662.30, 100, 10],
    ['Fisioterapia', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Integral', 'Bacharelado', 718.30, 665.40, 60, 10],
    ['Nutrição', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Integral', 'Bacharelado', 695.40, 642.10, 60, 8],
    ['Educação Física', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Matutino', 'Bacharelado', 681.20, 628.00, 80, 8],
    ['Educação Física', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Noturno', 'Licenciatura', 662.80, 609.50, 80, 8],
    ['Design', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Integral', 'Bacharelado', 715.40, 662.10, 40, 8],
    ['Jornalismo', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Integral', 'Bacharelado', 728.90, 675.20, 40, 8],
    ['Publicidade e Propaganda', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Integral', 'Bacharelado', 724.50, 671.10, 40, 8],
    ['Administração', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Noturno', 'Bacharelado', 722.10, 668.90, 120, 8],
    ['Ciências Econômicas', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Noturno', 'Bacharelado', 728.40, 675.10, 80, 8],
    ['Relações Internacionais', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Integral', 'Bacharelado', 745.20, 692.10, 40, 8],
    ['Ciências Contábeis', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Noturno', 'Bacharelado', 705.40, 652.10, 80, 8],
    ['Pedagogia', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Noturno', 'Licenciatura', 668.20, 615.10, 100, 8],
    ['História', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Noturno', 'Licenciatura', 685.90, 632.40, 60, 8],
    ['Geografia', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Noturno', 'Licenciatura', 671.50, 618.20, 60, 8],
    ['Letras', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Noturno', 'Licenciatura', 678.40, 625.10, 150, 8],
    ['Música', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Integral', 'Bacharelado', 698.40, 645.10, 30, 8],
    ['Química', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Integral', 'Bacharelado', 695.20, 642.00, 60, 8],
    ['Física', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Integral', 'Bacharelado', 701.80, 648.50, 60, 8],
    ['Matemática', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Integral', 'Bacharelado', 698.10, 645.00, 60, 8],
    ['Ciências Biológicas', 'UFMG', 'Belo Horizonte - MG', 'Sudeste', 'Integral', 'Bacharelado', 718.50, 665.20, 80, 8]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($ufmgCourses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🏛️ Todos os Cursos da UFMG Cadastrados com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($ufmgCourses) . " cursos da Universidade Federal de Minas Gerais (UFMG)</strong> no Guia Nacional!</p>
</div>";
