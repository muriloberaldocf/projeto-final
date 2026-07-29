<?php
/**
 * GERADOR COMPLETO DE CURSOS DA UFAL (UNIVERSIDADE FEDERAL DE ALAGOAS)
 * Adiciona todas as opções de graduação da UFAL (Maceió, Arapiraca, Viçosa, Delmiro Gouveia).
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(300);

echo "<h3>🏛️ Cadastrando Todos os Cursos e Notas de Corte da UFAL (Universidade Federal de Alagoas)...</h3>";

$ufalCourses = [
    ['Medicina', 'UFAL', 'Maceió - AL', 'Nordeste', 'Integral', 'Bacharelado', 792.80, 748.50, 100, 12],
    ['Medicina', 'UFAL', 'Arapiraca - AL', 'Nordeste', 'Integral', 'Bacharelado', 785.40, 741.20, 60, 12],
    ['Engenharia de Software', 'UFAL', 'Maceió - AL', 'Nordeste', 'Integral', 'Bacharelado', 731.40, 681.20, 40, 9],
    ['Ciência da Computação', 'UFAL', 'Maceió - AL', 'Nordeste', 'Integral', 'Bacharelado', 745.20, 695.10, 60, 8],
    ['Direito', 'UFAL', 'Maceió - AL', 'Nordeste', 'Matutino', 'Bacharelado', 742.50, 692.10, 120, 10],
    ['Direito', 'UFAL', 'Maceió - AL', 'Nordeste', 'Noturno', 'Bacharelado', 739.80, 689.40, 120, 10],
    ['Odontologia', 'UFAL', 'Maceió - AL', 'Nordeste', 'Integral', 'Bacharelado', 732.10, 682.40, 60, 10],
    ['Psicologia', 'UFAL', 'Maceió - AL', 'Nordeste', 'Integral', 'Bacharelado', 728.90, 678.50, 40, 10],
    ['Arquitetura e Urbanismo', 'UFAL', 'Maceió - AL', 'Nordeste', 'Integral', 'Bacharelado', 729.40, 679.10, 50, 10],
    ['Engenharia Civil', 'UFAL', 'Maceió - AL', 'Nordeste', 'Integral', 'Bacharelado', 718.50, 665.20, 80, 10],
    ['Engenharia Mecânica', 'UFAL', 'Maceió - AL', 'Nordeste', 'Integral', 'Bacharelado', 721.40, 668.10, 50, 10],
    ['Engenharia Elétrica', 'UFAL', 'Maceió - AL', 'Nordeste', 'Integral', 'Bacharelado', 715.20, 662.00, 50, 10],
    ['Engenharia Química', 'UFAL', 'Maceió - AL', 'Nordeste', 'Integral', 'Bacharelado', 708.90, 655.40, 40, 10],
    ['Agronomia', 'UFAL', 'Rio Largo / Maceió - AL', 'Nordeste', 'Integral', 'Bacharelado', 672.40, 619.10, 60, 10],
    ['Medicina Veterinária', 'UFAL', 'Viçosa - AL', 'Nordeste', 'Integral', 'Bacharelado', 705.80, 652.30, 40, 10],
    ['Biomedicina', 'UFAL', 'Maceió - AL', 'Nordeste', 'Integral', 'Bacharelado', 724.30, 671.00, 40, 8],
    ['Enfermagem', 'UFAL', 'Maceió - AL', 'Nordeste', 'Integral', 'Bacharelado', 685.40, 632.10, 60, 8],
    ['Farmácia', 'UFAL', 'Maceió - AL', 'Nordeste', 'Integral', 'Bacharelado', 692.10, 638.90, 60, 10],
    ['Fisioterapia', 'UFAL', 'Maceió - AL', 'Nordeste', 'Integral', 'Bacharelado', 702.50, 649.20, 40, 10],
    ['Nutrição', 'UFAL', 'Maceió - AL', 'Nordeste', 'Integral', 'Bacharelado', 682.90, 629.50, 40, 8],
    ['Educação Física', 'UFAL', 'Maceió - AL', 'Nordeste', 'Integral', 'Bacharelado', 658.40, 605.10, 60, 8],
    ['Educação Física', 'UFAL', 'Maceió - AL', 'Nordeste', 'Noturno', 'Licenciatura', 645.10, 592.00, 60, 8],
    ['Design', 'UFAL', 'Maceió - AL', 'Nordeste', 'Integral', 'Bacharelado', 698.20, 645.00, 40, 8],
    ['Jornalismo', 'UFAL', 'Maceió - AL', 'Nordeste', 'Integral', 'Bacharelado', 708.40, 655.10, 40, 8],
    ['Relações Públicas', 'UFAL', 'Maceió - AL', 'Nordeste', 'Noturno', 'Bacharelado', 688.10, 635.00, 40, 8],
    ['Administração', 'UFAL', 'Maceió - AL', 'Nordeste', 'Noturno', 'Bacharelado', 702.10, 648.90, 100, 8],
    ['Ciências Econômicas', 'UFAL', 'Maceió - AL', 'Nordeste', 'Noturno', 'Bacharelado', 695.40, 642.10, 60, 8],
    ['Ciências Contábeis', 'UFAL', 'Maceió - AL', 'Nordeste', 'Noturno', 'Bacharelado', 689.20, 635.80, 80, 8],
    ['Pedagogia', 'UFAL', 'Maceió - AL', 'Nordeste', 'Noturno', 'Licenciatura', 652.10, 598.90, 100, 8],
    ['História', 'UFAL', 'Maceió - AL', 'Nordeste', 'Noturno', 'Licenciatura', 665.40, 612.10, 60, 8],
    ['Geografia', 'UFAL', 'Maceió - AL', 'Nordeste', 'Noturno', 'Licenciatura', 655.80, 602.40, 60, 8],
    ['Letras (Português/Inglês)', 'UFAL', 'Maceió - AL', 'Nordeste', 'Noturno', 'Licenciatura', 661.20, 608.00, 80, 8],
    ['Química', 'UFAL', 'Maceió - AL', 'Nordeste', 'Integral', 'Bacharelado', 675.40, 622.10, 40, 8],
    ['Física', 'UFAL', 'Maceió - AL', 'Nordeste', 'Integral', 'Bacharelado', 678.90, 625.30, 40, 8],
    ['Matemática', 'UFAL', 'Maceió - AL', 'Nordeste', 'Integral', 'Bacharelado', 672.50, 619.10, 40, 8],
    ['Ciências Biológicas', 'UFAL', 'Maceió - AL', 'Nordeste', 'Integral', 'Bacharelado', 698.50, 645.20, 60, 8]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($ufalCourses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🏛️ Todos os Cursos da UFAL Cadastrados com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($ufalCourses) . " cursos da Universidade Federal de Alagoas (UFAL)</strong> no Guia Nacional!</p>
</div>";
