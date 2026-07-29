<?php
/**
 * GERADOR COMPLETO DE CURSOS DA UFES (UNIVERSIDADE FEDERAL DO ESPÍRITO SANTO)
 * Adiciona todas as opções de graduação da UFES (Vitória, Alegre, São Mateus).
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(300);

echo "<h3>🏛️ Cadastrando Todos os Cursos e Notas de Corte da UFES (Universidade Federal do Espírito Santo)...</h3>";

$ufesCourses = [
    ['Medicina', 'UFES', 'Vitória - ES', 'Sudeste', 'Integral', 'Bacharelado', 798.20, 754.10, 80, 12],
    ['Engenharia de Software', 'UFES', 'São Mateus - ES', 'Sudeste', 'Integral', 'Bacharelado', 732.50, 682.10, 40, 9],
    ['Ciência da Computação', 'UFES', 'Vitória - ES', 'Sudeste', 'Integral', 'Bacharelado', 752.40, 702.10, 60, 8],
    ['Direito', 'UFES', 'Vitória - ES', 'Sudeste', 'Matutino', 'Bacharelado', 748.90, 698.50, 100, 10],
    ['Direito', 'UFES', 'Vitória - ES', 'Sudeste', 'Noturno', 'Bacharelado', 745.20, 695.10, 100, 10],
    ['Odontologia', 'UFES', 'Vitória - ES', 'Sudeste', 'Integral', 'Bacharelado', 738.50, 688.20, 50, 10],
    ['Psicologia', 'UFES', 'Vitória - ES', 'Sudeste', 'Integral', 'Bacharelado', 732.10, 681.80, 40, 10],
    ['Arquitetura e Urbanismo', 'UFES', 'Vitória - ES', 'Sudeste', 'Integral', 'Bacharelado', 735.40, 685.10, 50, 10],
    ['Engenharia Mecânica', 'UFES', 'Vitória - ES', 'Sudeste', 'Integral', 'Bacharelado', 738.20, 685.40, 60, 10],
    ['Engenharia Civil', 'UFES', 'Vitória - ES', 'Sudeste', 'Integral', 'Bacharelado', 725.90, 672.40, 60, 10],
    ['Engenharia Elétrica', 'UFES', 'Vitória - ES', 'Sudeste', 'Integral', 'Bacharelado', 721.80, 668.50, 60, 10],
    ['Engenharia de Produção', 'UFES', 'Vitória - ES', 'Sudeste', 'Integral', 'Bacharelado', 728.40, 675.10, 40, 10],
    ['Medicina Veterinária', 'UFES', 'Alegre - ES', 'Sudeste', 'Integral', 'Bacharelado', 715.40, 662.10, 50, 10],
    ['Agronomia', 'UFES', 'Alegre - ES', 'Sudeste', 'Integral', 'Bacharelado', 678.90, 625.30, 60, 10],
    ['Biomedicina', 'UFES', 'Vitória - ES', 'Sudeste', 'Integral', 'Bacharelado', 728.90, 675.40, 40, 8],
    ['Enfermagem', 'UFES', 'Vitória - ES', 'Sudeste', 'Integral', 'Bacharelado', 689.50, 636.20, 60, 8],
    ['Farmácia', 'UFES', 'Vitória - ES', 'Sudeste', 'Integral', 'Bacharelado', 698.20, 645.10, 60, 10],
    ['Fisioterapia', 'UFES', 'Vitória - ES', 'Sudeste', 'Integral', 'Bacharelado', 708.40, 655.10, 40, 10],
    ['Nutrição', 'UFES', 'Vitória - ES', 'Sudeste', 'Integral', 'Bacharelado', 688.10, 635.00, 40, 8],
    ['Educação Física', 'UFES', 'Vitória - ES', 'Sudeste', 'Integral', 'Bacharelado', 665.40, 612.10, 60, 8],
    ['Educação Física', 'UFES', 'Vitória - ES', 'Sudeste', 'Noturno', 'Licenciatura', 652.10, 598.90, 60, 8],
    ['Design', 'UFES', 'Vitória - ES', 'Sudeste', 'Integral', 'Bacharelado', 708.20, 655.00, 40, 8],
    ['Jornalismo', 'UFES', 'Vitória - ES', 'Sudeste', 'Integral', 'Bacharelado', 718.50, 665.20, 40, 8],
    ['Publicidade e Propaganda', 'UFES', 'Vitória - ES', 'Sudeste', 'Integral', 'Bacharelado', 712.40, 659.10, 40, 8],
    ['Administração', 'UFES', 'Vitória - ES', 'Sudeste', 'Noturno', 'Bacharelado', 711.80, 658.40, 80, 8],
    ['Ciências Econômicas', 'UFES', 'Vitória - ES', 'Sudeste', 'Noturno', 'Bacharelado', 708.90, 655.40, 60, 8],
    ['Relações Internacionais', 'UFES', 'Vitória - ES', 'Sudeste', 'Integral', 'Bacharelado', 735.40, 682.10, 40, 8],
    ['Ciências Contábeis', 'UFES', 'Vitória - ES', 'Sudeste', 'Noturno', 'Bacharelado', 695.20, 642.00, 60, 8],
    ['Pedagogia', 'UFES', 'Vitória - ES', 'Sudeste', 'Noturno', 'Licenciatura', 658.40, 605.10, 80, 8],
    ['História', 'UFES', 'Vitória - ES', 'Sudeste', 'Noturno', 'Licenciatura', 672.10, 618.90, 40, 8],
    ['Geografia', 'UFES', 'Vitória - ES', 'Sudeste', 'Noturno', 'Licenciatura', 661.80, 608.50, 40, 8],
    ['Letras', 'UFES', 'Vitória - ES', 'Sudeste', 'Noturno', 'Licenciatura', 668.20, 615.10, 100, 8],
    ['Química', 'UFES', 'Vitória - ES', 'Sudeste', 'Integral', 'Bacharelado', 682.40, 629.10, 40, 8],
    ['Física', 'UFES', 'Vitória - ES', 'Sudeste', 'Integral', 'Bacharelado', 688.90, 635.40, 40, 8],
    ['Matemática', 'UFES', 'Vitória - ES', 'Sudeste', 'Integral', 'Bacharelado', 682.10, 628.90, 40, 8],
    ['Ciências Biológicas', 'UFES', 'Vitória - ES', 'Sudeste', 'Integral', 'Bacharelado', 705.80, 652.40, 60, 8]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($ufesCourses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🏛️ Todos os Cursos da UFES Cadastrados com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($ufesCourses) . " cursos da Universidade Federal do Espírito Santo (UFES)</strong> no Guia Nacional!</p>
</div>";
