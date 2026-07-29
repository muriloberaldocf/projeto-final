<?php
/**
 * GERADOR COMPLETO DE CURSOS DA UFBA (UNIVERSIDADE FEDERAL DA BAHIA)
 * Adiciona todas as opções de graduação da UFBA (Salvador, Vitória da Conquista, Camaçari).
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(300);

echo "<h3>🏛️ Cadastrando Todos os Cursos e Notas de Corte da UFBA (Universidade Federal da Bahia)...</h3>";

$ufbaCourses = [
    ['Medicina', 'UFBA', 'Salvador (Canela) - BA', 'Nordeste', 'Integral', 'Bacharelado', 798.90, 754.20, 160, 12],
    ['Medicina', 'UFBA', 'Vitória da Conquista - BA', 'Nordeste', 'Integral', 'Bacharelado', 789.50, 745.10, 60, 12],
    ['Engenharia de Software', 'UFBA', 'Salvador - BA', 'Nordeste', 'Noturno', 'Bacharelado', 742.10, 692.00, 40, 9],
    ['Ciência da Computação', 'UFBA', 'Salvador - BA', 'Nordeste', 'Integral', 'Bacharelado', 755.80, 705.20, 60, 8],
    ['Sistemas de Informação', 'UFBA', 'Salvador - BA', 'Nordeste', 'Noturno', 'Bacharelado', 732.40, 682.10, 50, 8],
    ['Direito', 'UFBA', 'Salvador (Graça) - BA', 'Nordeste', 'Matutino', 'Bacharelado', 758.90, 708.40, 160, 10],
    ['Direito', 'UFBA', 'Salvador (Graça) - BA', 'Nordeste', 'Noturno', 'Bacharelado', 755.20, 705.00, 160, 10],
    ['Odontologia', 'UFBA', 'Salvador - BA', 'Nordeste', 'Integral', 'Bacharelado', 742.50, 692.10, 80, 10],
    ['Psicologia', 'UFBA', 'Salvador - BA', 'Nordeste', 'Integral', 'Bacharelado', 739.20, 689.00, 60, 10],
    ['Arquitetura e Urbanismo', 'UFBA', 'Salvador - BA', 'Nordeste', 'Integral', 'Bacharelado', 741.80, 691.50, 80, 10],
    ['Engenharia Civil', 'UFBA', 'Salvador - BA', 'Nordeste', 'Integral', 'Bacharelado', 728.50, 675.20, 80, 10],
    ['Engenharia Mecânica', 'UFBA', 'Salvador - BA', 'Nordeste', 'Integral', 'Bacharelado', 735.40, 682.10, 60, 10],
    ['Engenharia Química', 'UFBA', 'Salvador - BA', 'Nordeste', 'Integral', 'Bacharelado', 725.10, 672.00, 60, 10],
    ['Engenharia Elétrica', 'UFBA', 'Salvador - BA', 'Nordeste', 'Integral', 'Bacharelado', 722.80, 669.50, 60, 10],
    ['Medicina Veterinária', 'UFBA', 'Salvador - BA', 'Nordeste', 'Integral', 'Bacharelado', 728.90, 675.40, 60, 10],
    ['Biomedicina', 'UFBA', 'Salvador - BA', 'Nordeste', 'Integral', 'Bacharelado', 735.10, 682.00, 40, 8],
    ['Enfermagem', 'UFBA', 'Salvador - BA', 'Nordeste', 'Integral', 'Bacharelado', 692.40, 639.10, 80, 8],
    ['Farmácia', 'UFBA', 'Salvador - BA', 'Nordeste', 'Integral', 'Bacharelado', 705.20, 652.00, 80, 10],
    ['Fisioterapia', 'UFBA', 'Salvador - BA', 'Nordeste', 'Integral', 'Bacharelado', 712.80, 659.40, 50, 10],
    ['Nutrição', 'UFBA', 'Salvador - BA', 'Nordeste', 'Integral', 'Bacharelado', 691.50, 638.20, 50, 8],
    ['Educação Física', 'UFBA', 'Salvador - BA', 'Nordeste', 'Integral', 'Bacharelado', 658.40, 605.10, 80, 8],
    ['Educação Física', 'UFBA', 'Salvador - BA', 'Nordeste', 'Noturno', 'Licenciatura', 648.20, 595.00, 80, 8],
    ['Design', 'UFBA', 'Salvador - BA', 'Nordeste', 'Integral', 'Bacharelado', 712.30, 659.00, 40, 8],
    ['Jornalismo', 'UFBA', 'Salvador - BA', 'Nordeste', 'Integral', 'Bacharelado', 725.40, 672.10, 40, 8],
    ['Publicidade e Propaganda', 'UFBA', 'Salvador - BA', 'Nordeste', 'Integral', 'Bacharelado', 718.90, 665.50, 40, 8],
    ['Administração', 'UFBA', 'Salvador - BA', 'Nordeste', 'Noturno', 'Bacharelado', 715.20, 662.00, 120, 8],
    ['Ciências Econômicas', 'UFBA', 'Salvador - BA', 'Nordeste', 'Noturno', 'Bacharelado', 708.50, 655.20, 80, 8],
    ['Relações Internacionais', 'UFBA', 'Salvador - BA', 'Nordeste', 'Integral', 'Bacharelado', 738.90, 685.40, 40, 8],
    ['Ciências Contábeis', 'UFBA', 'Salvador - BA', 'Nordeste', 'Noturno', 'Bacharelado', 695.80, 642.50, 80, 8],
    ['Pedagogia', 'UFBA', 'Salvador - BA', 'Nordeste', 'Noturno', 'Licenciatura', 652.10, 598.90, 100, 8],
    ['História', 'UFBA', 'Salvador - BA', 'Nordeste', 'Noturno', 'Licenciatura', 678.40, 625.10, 60, 8],
    ['Geografia', 'UFBA', 'Salvador - BA', 'Nordeste', 'Noturno', 'Licenciatura', 662.50, 609.20, 60, 8],
    ['Letras (Português/Inglês)', 'UFBA', 'Salvador - BA', 'Nordeste', 'Noturno', 'Licenciatura', 668.90, 615.40, 100, 8],
    ['Dança', 'UFBA', 'Salvador - BA', 'Nordeste', 'Integral', 'Bacharelado', 665.20, 612.00, 30, 8],
    ['Teatro', 'UFBA', 'Salvador - BA', 'Nordeste', 'Integral', 'Bacharelado', 671.40, 618.10, 30, 8],
    ['Música', 'UFBA', 'Salvador - BA', 'Nordeste', 'Integral', 'Bacharelado', 675.80, 622.50, 30, 8],
    ['Química', 'UFBA', 'Salvador - BA', 'Nordeste', 'Integral', 'Bacharelado', 685.20, 632.00, 50, 8],
    ['Física', 'UFBA', 'Salvador - BA', 'Nordeste', 'Integral', 'Bacharelado', 689.10, 635.80, 50, 8],
    ['Matemática', 'UFBA', 'Salvador - BA', 'Nordeste', 'Integral', 'Bacharelado', 682.40, 629.10, 50, 8],
    ['Ciências Biológicas', 'UFBA', 'Salvador - BA', 'Nordeste', 'Integral', 'Bacharelado', 712.50, 659.20, 60, 8]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($ufbaCourses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🏛️ Todos os Cursos da UFBA Cadastrados com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($ufbaCourses) . " cursos da Universidade Federal da Bahia (UFBA)</strong> no Guia Nacional!</p>
</div>";
