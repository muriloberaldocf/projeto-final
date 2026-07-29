<?php
/**
 * GERADOR COMPLETO DE CURSOS DA UFPE (UNIVERSIDADE FEDERAL DE PERNAMBUCO)
 * Adiciona todas as opções de graduação da UFPE (Recife, Caruaru - CAA, Vitória de Santo Antão - CAV).
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(300);

echo "<h3>🏛️ Cadastrando Todos os Cursos e Notas de Corte da UFPE (Universidade Federal de Pernambuco)...</h3>";

$ufpeCourses = [
    ['Medicina', 'UFPE', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 805.40, 762.10, 140, 12],
    ['Medicina', 'UFPE', 'Caruaru (CAA) - PE', 'Nordeste', 'Integral', 'Bacharelado', 795.80, 751.20, 80, 12],
    ['Engenharia de Software', 'UFPE', 'Caruaru (CAA) - PE', 'Nordeste', 'Integral', 'Bacharelado', 745.20, 695.10, 40, 9],
    ['Ciência da Computação', 'UFPE / CIn', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 789.50, 739.20, 80, 8],
    ['Engenharia da Computação', 'UFPE / CIn', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 781.20, 731.00, 60, 10],
    ['Sistemas de Informação', 'UFPE / CIn', 'Recife - PE', 'Nordeste', 'Noturno', 'Bacharelado', 755.40, 705.10, 60, 8],
    ['Direito', 'UFPE / FDR', 'Recife - PE', 'Nordeste', 'Matutino', 'Bacharelado', 768.90, 718.50, 160, 10],
    ['Direito', 'UFPE / FDR', 'Recife - PE', 'Nordeste', 'Noturno', 'Bacharelado', 765.20, 715.00, 160, 10],
    ['Odontologia', 'UFPE', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 748.20, 698.00, 60, 10],
    ['Psicologia', 'UFPE', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 745.10, 695.00, 60, 10],
    ['Arquitetura e Urbanismo', 'UFPE', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 749.80, 699.20, 60, 10],
    ['Engenharia Mecânica', 'UFPE / CTG', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 752.40, 702.10, 80, 10],
    ['Engenharia Civil', 'UFPE / CTG', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 738.90, 685.40, 100, 10],
    ['Engenharia Elétrica', 'UFPE / CTG', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 732.50, 679.10, 80, 10],
    ['Engenharia Química', 'UFPE / CTG', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 728.90, 675.30, 60, 10],
    ['Engenharia de Produção', 'UFPE / CTG', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 741.80, 688.50, 60, 10],
    ['Biomedicina', 'UFPE', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 748.50, 698.10, 40, 8],
    ['Enfermagem', 'UFPE', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 698.20, 645.00, 80, 8],
    ['Enfermagem', 'UFPE', 'Vitória (CAV) - PE', 'Nordeste', 'Integral', 'Bacharelado', 689.50, 636.20, 60, 8],
    ['Farmácia', 'UFPE', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 708.40, 655.10, 80, 10],
    ['Fisioterapia', 'UFPE', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 718.90, 665.40, 50, 10],
    ['Nutrição', 'UFPE', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 695.80, 642.50, 50, 8],
    ['Educação Física', 'UFPE', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 665.70, 612.40, 80, 8],
    ['Educação Física', 'UFPE', 'Recife - PE', 'Nordeste', 'Noturno', 'Licenciatura', 652.80, 599.50, 80, 8],
    ['Design', 'UFPE', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 721.40, 668.10, 40, 8],
    ['Design', 'UFPE', 'Caruaru (CAA) - PE', 'Nordeste', 'Noturno', 'Bacharelado', 702.10, 648.90, 40, 8],
    ['Jornalismo', 'UFPE', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 732.10, 678.90, 40, 8],
    ['Publicidade e Propaganda', 'UFPE', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 728.50, 675.20, 40, 8],
    ['Cinema e Audiovisual', 'UFPE', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 735.80, 682.40, 30, 8],
    ['Administração', 'UFPE', 'Recife - PE', 'Nordeste', 'Noturno', 'Bacharelado', 724.50, 671.20, 120, 8],
    ['Ciências Econômicas', 'UFPE', 'Recife - PE', 'Nordeste', 'Noturno', 'Bacharelado', 718.20, 665.00, 80, 8],
    ['Relações Internacionais', 'UFPE', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 748.90, 695.50, 40, 8],
    ['Ciências Contábeis', 'UFPE', 'Recife - PE', 'Nordeste', 'Noturno', 'Bacharelado', 705.40, 652.10, 100, 8],
    ['Pedagogia', 'UFPE', 'Recife - PE', 'Nordeste', 'Noturno', 'Licenciatura', 661.80, 608.50, 100, 8],
    ['História', 'UFPE', 'Recife - PE', 'Nordeste', 'Noturno', 'Licenciatura', 682.40, 629.10, 60, 8],
    ['Geografia', 'UFPE', 'Recife - PE', 'Nordeste', 'Noturno', 'Licenciatura', 668.90, 615.60, 60, 8],
    ['Letras (Português/Inglês)', 'UFPE', 'Recife - PE', 'Nordeste', 'Noturno', 'Licenciatura', 672.50, 619.20, 100, 8],
    ['Química', 'UFPE', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 689.10, 635.80, 60, 8],
    ['Física', 'UFPE', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 698.40, 645.10, 60, 8],
    ['Matemática', 'UFPE', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 691.20, 638.00, 60, 8],
    ['Ciências Biológicas', 'UFPE', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 715.80, 662.50, 80, 8]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($ufpeCourses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🏛️ Todos os Cursos da UFPE Cadastrados com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($ufpeCourses) . " cursos da Universidade Federal de Pernambuco (UFPE)</strong> no Guia Nacional!</p>
</div>";
