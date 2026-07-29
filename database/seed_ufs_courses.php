<?php
/**
 * GERADOR COMPLETO DE CURSOS DA UFS (UNIVERSIDADE FEDERAL DE SERGIPE)
 * Adiciona todas as opções de graduação da UFS (São Cristóvão, Lagarto, Itabaiana, Laranjeiras, Nossa Senhora da Glória).
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(300);

echo "<h3>🏛️ Cadastrando Todos os Cursos e Notas de Corte da UFS (Universidade Federal de Sergipe)...</h3>";

$ufsCourses = [
    ['Medicina', 'UFS', 'São Cristóvão (Aracaju) - SE', 'Nordeste', 'Integral', 'Bacharelado', 798.50, 754.10, 100, 12],
    ['Medicina', 'UFS', 'Lagarto - SE', 'Nordeste', 'Integral', 'Bacharelado', 789.20, 745.00, 60, 12],
    ['Engenharia de Software', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Integral', 'Bacharelado', 735.40, 685.20, 40, 9],
    ['Ciência da Computação', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Integral', 'Bacharelado', 748.20, 698.00, 50, 8],
    ['Sistemas de Informação', 'UFS', 'Itabaiana - SE', 'Nordeste', 'Noturno', 'Bacharelado', 712.50, 662.10, 40, 8],
    ['Direito', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Matutino', 'Bacharelado', 751.80, 701.50, 120, 10],
    ['Direito', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Noturno', 'Bacharelado', 748.50, 698.20, 120, 10],
    ['Odontologia', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Integral', 'Bacharelado', 742.10, 692.00, 50, 10],
    ['Odontologia', 'UFS', 'Lagarto - SE', 'Nordeste', 'Integral', 'Bacharelado', 735.40, 685.10, 50, 10],
    ['Psicologia', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Integral', 'Bacharelado', 738.90, 688.40, 40, 10],
    ['Arquitetura e Urbanismo', 'UFS', 'Laranjeiras - SE', 'Nordeste', 'Integral', 'Bacharelado', 731.40, 681.20, 40, 10],
    ['Engenharia Civil', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Integral', 'Bacharelado', 721.80, 668.50, 60, 10],
    ['Engenharia Mecânica', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Integral', 'Bacharelado', 725.40, 672.10, 50, 10],
    ['Engenharia Elétrica', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Integral', 'Bacharelado', 718.50, 665.20, 50, 10],
    ['Engenharia Química', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Integral', 'Bacharelado', 712.30, 659.00, 40, 10],
    ['Agronomia', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Integral', 'Bacharelado', 678.90, 625.30, 60, 10],
    ['Medicina Veterinária', 'UFS', 'Nossa Senhora da Glória - SE', 'Nordeste', 'Integral', 'Bacharelado', 708.40, 655.10, 40, 10],
    ['Zootecnia', 'UFS', 'Nossa Senhora da Glória - SE', 'Nordeste', 'Integral', 'Bacharelado', 658.20, 605.00, 40, 10],
    ['Biomedicina', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Integral', 'Bacharelado', 732.10, 678.90, 40, 8],
    ['Enfermagem', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Integral', 'Bacharelado', 688.40, 635.10, 60, 8],
    ['Enfermagem', 'UFS', 'Lagarto - SE', 'Nordeste', 'Integral', 'Bacharelado', 681.20, 628.00, 50, 8],
    ['Farmácia', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Integral', 'Bacharelado', 695.80, 642.50, 60, 10],
    ['Fisioterapia', 'UFS', 'Lagarto - SE', 'Nordeste', 'Integral', 'Bacharelado', 705.40, 652.10, 40, 10],
    ['Nutrição', 'UFS', 'Lagarto - SE', 'Nordeste', 'Integral', 'Bacharelado', 682.90, 629.50, 40, 8],
    ['Educação Física', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Integral', 'Bacharelado', 655.40, 602.10, 60, 8],
    ['Educação Física', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Noturno', 'Licenciatura', 645.10, 592.00, 60, 8],
    ['Design', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Integral', 'Bacharelado', 701.80, 648.50, 40, 8],
    ['Jornalismo', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Integral', 'Bacharelado', 715.20, 662.00, 40, 8],
    ['Publicidade e Propaganda', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Integral', 'Bacharelado', 708.90, 655.60, 40, 8],
    ['Administração', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Noturno', 'Bacharelado', 705.10, 651.80, 80, 8],
    ['Ciências Econômicas', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Noturno', 'Bacharelado', 698.40, 645.10, 60, 8],
    ['Relações Internacionais', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Integral', 'Bacharelado', 735.20, 682.00, 40, 8],
    ['Ciências Contábeis', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Noturno', 'Bacharelado', 689.50, 636.20, 80, 8],
    ['Pedagogia', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Noturno', 'Licenciatura', 648.50, 595.20, 80, 8],
    ['História', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Noturno', 'Licenciatura', 668.20, 615.00, 60, 8],
    ['Geografia', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Noturno', 'Licenciatura', 658.10, 604.90, 60, 8],
    ['Letras (Português/Inglês)', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Noturno', 'Licenciatura', 662.40, 609.10, 80, 8],
    ['Química', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Integral', 'Bacharelado', 672.50, 619.20, 40, 8],
    ['Física', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Integral', 'Bacharelado', 675.80, 622.50, 40, 8],
    ['Matemática', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Integral', 'Bacharelado', 668.90, 615.60, 40, 8],
    ['Ciências Biológicas', 'UFS', 'São Cristóvão - SE', 'Nordeste', 'Integral', 'Bacharelado', 698.20, 645.00, 60, 8]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($ufsCourses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🏛️ Todos os Cursos da UFS Cadastrados com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($ufsCourses) . " cursos da Universidade Federal de Sergipe (UFS)</strong> no Guia Nacional!</p>
</div>";
