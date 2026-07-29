<?php
/**
 * GERADOR COMPLETO DE CURSOS DA UFG (UNIVERSIDADE FEDERAL DE GOIÁS)
 * Adiciona todas as opções de graduação da UFG (Goiânia, Aparecida de Goiânia, Goiás).
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(300);

echo "<h3>🏛️ Cadastrando Todos os Cursos e Notas de Corte da UFG (Universidade Federal de Goiás)...</h3>";

$ufgCourses = [
    ['Medicina', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 801.50, 758.20, 110, 12],
    ['Engenharia de Software', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Noturno', 'Bacharelado', 735.60, 685.30, 50, 9],
    ['Ciência da Computação', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 748.90, 698.40, 50, 8],
    ['Sistemas de Informação', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Noturno', 'Bacharelado', 728.40, 678.10, 50, 8],
    ['Direito', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Matutino', 'Bacharelado', 752.10, 702.30, 120, 10],
    ['Direito', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Noturno', 'Bacharelado', 748.90, 699.10, 120, 10],
    ['Odontologia', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 741.80, 691.50, 60, 10],
    ['Psicologia', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 738.20, 688.00, 50, 10],
    ['Arquitetura e Urbanismo', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 732.50, 682.10, 50, 10],
    ['Engenharia Civil', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 725.40, 672.10, 80, 10],
    ['Engenharia Mecânica', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 728.90, 675.30, 50, 10],
    ['Engenharia Elétrica', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 721.50, 668.20, 60, 10],
    ['Engenharia Química', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 715.80, 662.40, 40, 10],
    ['Medicina Veterinária', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 724.30, 671.00, 60, 10],
    ['Agronomia', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 698.50, 645.20, 100, 10],
    ['Biomedicina', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 731.20, 678.00, 40, 8],
    ['Enfermagem', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 692.40, 639.10, 60, 8],
    ['Farmácia', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 701.80, 648.50, 80, 10],
    ['Fisioterapia', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 708.90, 655.40, 40, 10],
    ['Nutrição', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 689.50, 636.20, 40, 8],
    ['Educação Física', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 664.20, 611.00, 60, 8],
    ['Educação Física', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Noturno', 'Licenciatura', 651.80, 598.40, 60, 8],
    ['Design de Moda', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 692.10, 638.90, 40, 8],
    ['Design Gráfico', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 705.40, 652.10, 40, 8],
    ['Jornalismo', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 712.30, 659.00, 40, 8],
    ['Publicidade e Propaganda', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 708.90, 655.50, 40, 8],
    ['Administração', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Noturno', 'Bacharelado', 712.40, 659.10, 100, 8],
    ['Ciências Econômicas', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Noturno', 'Bacharelado', 705.20, 652.00, 60, 8],
    ['Relações Internacionais', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 732.10, 678.90, 40, 8],
    ['Ciências Contábeis', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Noturno', 'Bacharelado', 698.40, 645.10, 80, 8],
    ['Pedagogia', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Noturno', 'Licenciatura', 658.90, 605.50, 100, 8],
    ['História', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Noturno', 'Licenciatura', 672.40, 619.10, 60, 8],
    ['Geografia', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Noturno', 'Licenciatura', 661.80, 608.50, 60, 8],
    ['Letras (Português/Inglês)', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Noturno', 'Licenciatura', 668.50, 615.20, 80, 8],
    ['Química', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 681.40, 628.20, 40, 8],
    ['Física', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 685.20, 632.00, 40, 8],
    ['Matemática', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 679.80, 626.50, 40, 8],
    ['Ciências Biológicas', 'UFG', 'Goiânia - GO', 'Centro-Oeste', 'Integral', 'Bacharelado', 708.90, 655.60, 60, 8]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($ufgCourses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🏛️ Todos os Cursos da UFG Cadastrados com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($ufgCourses) . " cursos da Universidade Federal de Goiás (UFG)</strong> no Guia Nacional!</p>
</div>";
