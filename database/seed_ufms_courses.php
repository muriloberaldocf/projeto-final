<?php
/**
 * GERADOR COMPLETO DE CURSOS DA UFMS (UNIVERSIDADE FEDERAL DE MATO GROSSO DO SUL)
 * Adiciona todas as opções de graduação da UFMS (Campo Grande, Três Lagoas, Ponta Porã, Chapadão do Sul).
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(300);

echo "<h3>🏛️ Cadastrando Todos os Cursos e Notas de Corte da UFMS (Universidade Federal de Mato Grosso do Sul)...</h3>";

$ufmsCourses = [
    ['Medicina', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Integral', 'Bacharelado', 798.90, 754.50, 90, 12],
    ['Medicina', 'UFMS', 'Três Lagoas - MS', 'Centro-Oeste', 'Integral', 'Bacharelado', 791.20, 747.00, 60, 12],
    ['Engenharia de Software', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Integral', 'Bacharelado', 735.40, 685.10, 50, 9],
    ['Ciência da Computação', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Integral', 'Bacharelado', 748.50, 698.20, 50, 8],
    ['Sistemas de Informação', 'UFMS', 'Ponta Porã - MS', 'Centro-Oeste', 'Noturno', 'Bacharelado', 708.20, 655.10, 40, 8],
    ['Direito', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Matutino', 'Bacharelado', 748.20, 698.00, 100, 10],
    ['Direito', 'UFMS', 'Três Lagoas - MS', 'Centro-Oeste', 'Noturno', 'Bacharelado', 732.50, 682.10, 60, 10],
    ['Odontologia', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Integral', 'Bacharelado', 738.90, 688.40, 50, 10],
    ['Psicologia', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Integral', 'Bacharelado', 735.10, 685.00, 40, 10],
    ['Arquitetura e Urbanismo', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Integral', 'Bacharelado', 728.90, 678.50, 40, 10],
    ['Engenharia Civil', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Integral', 'Bacharelado', 721.40, 668.20, 60, 10],
    ['Engenharia de Produção', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Integral', 'Bacharelado', 715.80, 662.40, 50, 10],
    ['Engenharia Elétrica', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Integral', 'Bacharelado', 712.30, 659.10, 50, 10],
    ['Agronomia', 'UFMS', 'Chapadão do Sul - MS', 'Centro-Oeste', 'Integral', 'Bacharelado', 678.50, 625.10, 60, 10],
    ['Medicina Veterinária', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Integral', 'Bacharelado', 721.80, 668.50, 60, 10],
    ['Biomedicina', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Integral', 'Bacharelado', 728.40, 675.20, 40, 8],
    ['Enfermagem', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Integral', 'Bacharelado', 691.50, 638.20, 60, 8],
    ['Farmácia', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Integral', 'Bacharelado', 698.20, 645.00, 60, 10],
    ['Fisioterapia', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Integral', 'Bacharelado', 705.40, 652.10, 40, 10],
    ['Nutrição', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Integral', 'Bacharelado', 688.90, 635.40, 40, 8],
    ['Educação Física', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Integral', 'Bacharelado', 661.20, 608.00, 60, 8],
    ['Educação Física', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Noturno', 'Licenciatura', 649.50, 596.20, 60, 8],
    ['Design', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Integral', 'Bacharelado', 695.20, 642.00, 40, 8],
    ['Jornalismo', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Integral', 'Bacharelado', 708.40, 655.10, 40, 8],
    ['Publicidade e Propaganda', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Integral', 'Bacharelado', 702.10, 648.90, 40, 8],
    ['Administração', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Noturno', 'Bacharelado', 705.80, 652.40, 80, 8],
    ['Ciências Econômicas', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Noturno', 'Bacharelado', 698.50, 645.20, 60, 8],
    ['Ciências Contábeis', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Noturno', 'Bacharelado', 692.10, 638.90, 80, 8],
    ['Pedagogia', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Noturno', 'Licenciatura', 655.40, 602.10, 80, 8],
    ['História', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Noturno', 'Licenciatura', 668.90, 615.40, 60, 8],
    ['Geografia', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Noturno', 'Licenciatura', 661.40, 608.10, 60, 8],
    ['Letras (Português/Inglês)', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Noturno', 'Licenciatura', 665.20, 612.00, 80, 8],
    ['Química', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Integral', 'Bacharelado', 675.80, 622.40, 40, 8],
    ['Física', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Integral', 'Bacharelado', 681.20, 628.00, 40, 8],
    ['Matemática', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Integral', 'Bacharelado', 674.50, 621.20, 40, 8],
    ['Ciências Biológicas', 'UFMS', 'Campo Grande - MS', 'Centro-Oeste', 'Integral', 'Bacharelado', 701.40, 648.10, 60, 8]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($ufmsCourses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🏛️ Todos os Cursos da UFMS Cadastrados com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($ufmsCourses) . " cursos da Universidade Federal de Mato Grosso do Sul (UFMS)</strong> no Guia Nacional!</p>
</div>";
