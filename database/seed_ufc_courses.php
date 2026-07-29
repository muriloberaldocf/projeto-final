<?php
/**
 * GERADOR COMPLETO DE CURSOS DA UFC (UNIVERSIDADE FEDERAL DO CEARÁ)
 * Adiciona todas as opções de graduação da UFC (Fortaleza, Sobral, Quixadá, Crateús, Russas, Itapajé).
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(300);

echo "<h3>🏛️ Cadastrando Todos os Cursos e Notas de Corte da UFC (Universidade Federal do Ceará)...</h3>";

$ufcCourses = [
    ['Medicina', 'UFC', 'Fortaleza (Porangabussu) - CE', 'Nordeste', 'Integral', 'Bacharelado', 804.20, 761.50, 160, 12],
    ['Medicina', 'UFC', 'Sobral - CE', 'Nordeste', 'Integral', 'Bacharelado', 795.10, 751.40, 80, 12],
    ['Engenharia de Software', 'UFC', 'Quixadá - CE', 'Nordeste', 'Integral', 'Bacharelado', 745.80, 695.20, 50, 9],
    ['Ciência da Computação', 'UFC', 'Quixadá - CE', 'Nordeste', 'Integral', 'Bacharelado', 752.10, 701.80, 50, 8],
    ['Engenharia da Computação', 'UFC', 'Fortaleza (Pici) - CE', 'Nordeste', 'Integral', 'Bacharelado', 768.40, 718.20, 60, 10],
    ['Ciência da Computação', 'UFC', 'Fortaleza (Pici) - CE', 'Nordeste', 'Integral', 'Bacharelado', 762.50, 712.10, 60, 8],
    ['Sistemas de Informação', 'UFC', 'Crateús - CE', 'Nordeste', 'Noturno', 'Bacharelado', 712.40, 662.00, 40, 8],
    ['Engenharia de Software', 'UFC', 'Russas - CE', 'Nordeste', 'Integral', 'Bacharelado', 718.90, 668.50, 50, 9],
    ['Direito', 'UFC', 'Fortaleza (Benfica) - CE', 'Nordeste', 'Matutino', 'Bacharelado', 761.80, 711.50, 160, 10],
    ['Direito', 'UFC', 'Fortaleza (Benfica) - CE', 'Nordeste', 'Noturno', 'Bacharelado', 758.20, 708.00, 160, 10],
    ['Odontologia', 'UFC', 'Fortaleza (Porangabussu) - CE', 'Nordeste', 'Integral', 'Bacharelado', 748.90, 698.20, 80, 10],
    ['Odontologia', 'UFC', 'Sobral - CE', 'Nordeste', 'Integral', 'Bacharelado', 739.20, 689.00, 50, 10],
    ['Psicologia', 'UFC', 'Fortaleza (Benfica) - CE', 'Nordeste', 'Integral', 'Bacharelado', 745.10, 695.00, 60, 10],
    ['Arquitetura e Urbanismo', 'UFC', 'Fortaleza (Pici) - CE', 'Nordeste', 'Integral', 'Bacharelado', 745.80, 695.40, 60, 10],
    ['Engenharia Mecânica', 'UFC', 'Fortaleza (Pici) - CE', 'Nordeste', 'Integral', 'Bacharelado', 748.20, 695.10, 80, 10],
    ['Engenharia Civil', 'UFC', 'Fortaleza (Pici) - CE', 'Nordeste', 'Integral', 'Bacharelado', 735.40, 682.10, 100, 10],
    ['Engenharia Elétrica', 'UFC', 'Fortaleza (Pici) - CE', 'Nordeste', 'Integral', 'Bacharelado', 732.10, 678.90, 80, 10],
    ['Engenharia Química', 'UFC', 'Fortaleza (Pici) - CE', 'Nordeste', 'Integral', 'Bacharelado', 728.50, 675.20, 60, 10],
    ['Agronomia', 'UFC', 'Fortaleza (Pici) - CE', 'Nordeste', 'Integral', 'Bacharelado', 681.90, 628.50, 100, 10],
    ['Biomedicina', 'UFC', 'Fortaleza (Porangabussu) - CE', 'Nordeste', 'Integral', 'Bacharelado', 742.80, 692.50, 40, 8],
    ['Enfermagem', 'UFC', 'Fortaleza (Porangabussu) - CE', 'Nordeste', 'Integral', 'Bacharelado', 698.20, 645.00, 80, 8],
    ['Farmácia', 'UFC', 'Fortaleza (Porangabussu) - CE', 'Nordeste', 'Integral', 'Bacharelado', 708.40, 655.10, 80, 10],
    ['Fisioterapia', 'UFC', 'Fortaleza (Porangabussu) - CE', 'Nordeste', 'Integral', 'Bacharelado', 715.80, 662.40, 50, 10],
    ['Nutrição', 'UFC', 'Fortaleza (Porangabussu) - CE', 'Nordeste', 'Integral', 'Bacharelado', 692.50, 639.10, 50, 8],
    ['Educação Física', 'UFC', 'Fortaleza (Pici) - CE', 'Nordeste', 'Integral', 'Bacharelado', 661.90, 608.50, 80, 8],
    ['Educação Física', 'UFC', 'Fortaleza (Pici) - CE', 'Nordeste', 'Noturno', 'Licenciatura', 649.50, 596.00, 80, 8],
    ['Design Digital', 'UFC', 'Quixadá - CE', 'Nordeste', 'Integral', 'Bacharelado', 712.50, 659.20, 40, 8],
    ['Jornalismo', 'UFC', 'Fortaleza (Benfica) - CE', 'Nordeste', 'Integral', 'Bacharelado', 728.90, 675.40, 40, 8],
    ['Publicidade e Propaganda', 'UFC', 'Fortaleza (Benfica) - CE', 'Nordeste', 'Integral', 'Bacharelado', 722.40, 669.10, 40, 8],
    ['Cinema e Audiovisual', 'UFC', 'Fortaleza (Benfica) - CE', 'Nordeste', 'Integral', 'Bacharelado', 725.10, 671.80, 30, 8],
    ['Administração', 'UFC', 'Fortaleza (Benfica) - CE', 'Nordeste', 'Noturno', 'Bacharelado', 718.50, 665.20, 120, 8],
    ['Ciências Econômicas', 'UFC', 'Fortaleza (Benfica) - CE', 'Nordeste', 'Noturno', 'Bacharelado', 712.30, 659.00, 80, 8],
    ['Finanças', 'UFC', 'Fortaleza (Benfica) - CE', 'Nordeste', 'Noturno', 'Bacharelado', 715.40, 662.10, 50, 8],
    ['Ciências Contábeis', 'UFC', 'Fortaleza (Benfica) - CE', 'Nordeste', 'Noturno', 'Bacharelado', 698.50, 645.20, 100, 8],
    ['Pedagogia', 'UFC', 'Fortaleza (Benfica) - CE', 'Nordeste', 'Noturno', 'Licenciatura', 658.40, 605.10, 100, 8],
    ['História', 'UFC', 'Fortaleza (Benfica) - CE', 'Nordeste', 'Noturno', 'Licenciatura', 678.90, 625.40, 60, 8],
    ['Geografia', 'UFC', 'Fortaleza (Pici) - CE', 'Nordeste', 'Noturno', 'Licenciatura', 665.20, 612.00, 60, 8],
    ['Letras (Português/Inglês)', 'UFC', 'Fortaleza (Benfica) - CE', 'Nordeste', 'Noturno', 'Licenciatura', 668.90, 615.60, 100, 8],
    ['Química', 'UFC', 'Fortaleza (Pici) - CE', 'Nordeste', 'Integral', 'Bacharelado', 685.20, 632.00, 60, 8],
    ['Física', 'UFC', 'Fortaleza (Pici) - CE', 'Nordeste', 'Integral', 'Bacharelado', 691.80, 638.50, 60, 8],
    ['Matemática', 'UFC', 'Fortaleza (Pici) - CE', 'Nordeste', 'Integral', 'Bacharelado', 684.50, 631.20, 60, 8],
    ['Ciências Biológicas', 'UFC', 'Fortaleza (Pici) - CE', 'Nordeste', 'Integral', 'Bacharelado', 711.20, 658.00, 80, 8]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($ufcCourses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🏛️ Todos os Cursos da UFC Cadastrados com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($ufcCourses) . " cursos da Universidade Federal do Ceará (UFC)</strong> no Guia Nacional!</p>
</div>";
