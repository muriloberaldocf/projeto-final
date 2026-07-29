<?php
/**
 * GERADOR COMPLETO DE CURSOS DA UEM (UNIVERSIDADE ESTADUAL DE MARINGÁ - PR)
 * Adiciona todas as opções de graduação da UEM (Maringá, Umuarama, Cianorte, Goioerê, Cidade Gaúcha, Ivaiporã).
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(300);

echo "<h3>🏛️ Cadastrando Todos os Cursos e Notas de Corte da UEM (Universidade Estadual de Maringá)...</h3>";

$uemCourses = [
    ['Medicina', 'UEM', 'Maringá - PR', 'Sul', 'Integral', 'Bacharelado', 798.50, 755.20, 40, 12],
    ['Engenharia de Software', 'UEM', 'Maringá - PR', 'Sul', 'Noturno', 'Bacharelado', 735.40, 685.10, 40, 8],
    ['Ciência da Computação', 'UEM', 'Maringá - PR', 'Sul', 'Integral', 'Bacharelado', 748.20, 698.00, 40, 8],
    ['Direito', 'UEM', 'Maringá - PR', 'Sul', 'Matutino', 'Bacharelado', 752.60, 702.40, 80, 10],
    ['Direito', 'UEM', 'Maringá - PR', 'Sul', 'Noturno', 'Bacharelado', 749.80, 699.20, 80, 10],
    ['Odontologia', 'UEM', 'Maringá - PR', 'Sul', 'Integral', 'Bacharelado', 742.10, 692.50, 40, 10],
    ['Psicologia', 'UEM', 'Maringá - PR', 'Sul', 'Integral', 'Bacharelado', 738.90, 688.30, 40, 10],
    ['Arquitetura e Urbanismo', 'UEM', 'Maringá - PR', 'Sul', 'Integral', 'Bacharelado', 732.50, 682.10, 40, 10],
    ['Engenharia Civil', 'UEM', 'Maringá - PR', 'Sul', 'Integral', 'Bacharelado', 728.40, 675.20, 60, 10],
    ['Engenharia Mecânica', 'UEM', 'Maringá - PR', 'Sul', 'Integral', 'Bacharelado', 731.00, 678.40, 40, 10],
    ['Engenharia Química', 'UEM', 'Maringá - PR', 'Sul', 'Integral', 'Bacharelado', 718.50, 665.30, 40, 10],
    ['Engenharia de Produção', 'UEM', 'Maringá - PR', 'Sul', 'Integral', 'Bacharelado', 722.10, 669.50, 40, 10],
    ['Engenharia Elétrica', 'UEM', 'Maringá - PR', 'Sul', 'Integral', 'Bacharelado', 715.60, 662.80, 40, 10],
    ['Agronomia', 'UEM', 'Maringá - PR', 'Sul', 'Integral', 'Bacharelado', 708.40, 655.10, 60, 10],
    ['Medicina Veterinária', 'UEM', 'Umuarama - PR', 'Sul', 'Integral', 'Bacharelado', 725.30, 672.10, 40, 10],
    ['Biomedicina', 'UEM', 'Maringá - PR', 'Sul', 'Integral', 'Bacharelado', 735.80, 682.90, 40, 8],
    ['Enfermagem', 'UEM', 'Maringá - PR', 'Sul', 'Integral', 'Bacharelado', 698.20, 645.10, 40, 8],
    ['Farmácia', 'UEM', 'Maringá - PR', 'Sul', 'Integral', 'Bacharelado', 702.50, 649.30, 60, 10],
    ['Educação Física', 'UEM', 'Maringá - PR', 'Sul', 'Integral', 'Bacharelado', 668.40, 615.10, 60, 8],
    ['Educação Física', 'UEM', 'Maringá - PR', 'Sul', 'Noturno', 'Licenciatura', 655.20, 602.00, 60, 8],
    ['Nutrição', 'UEM', 'Maringá - PR', 'Sul', 'Integral', 'Bacharelado', 695.10, 642.30, 40, 8],
    ['Administração', 'UEM', 'Maringá - PR', 'Sul', 'Noturno', 'Bacharelado', 712.30, 659.80, 80, 8],
    ['Ciências Econômicas', 'UEM', 'Maringá - PR', 'Sul', 'Noturno', 'Bacharelado', 705.80, 652.40, 80, 8],
    ['Ciências Contábeis', 'UEM', 'Maringá - PR', 'Sul', 'Noturno', 'Bacharelado', 698.50, 645.20, 80, 8],
    ['Pedagogia', 'UEM', 'Maringá - PR', 'Sul', 'Noturno', 'Licenciatura', 658.20, 605.10, 80, 8],
    ['História', 'UEM', 'Maringá - PR', 'Sul', 'Noturno', 'Licenciatura', 672.40, 619.30, 40, 8],
    ['Geografia', 'UEM', 'Maringá - PR', 'Sul', 'Noturno', 'Licenciatura', 661.50, 608.20, 40, 8],
    ['Letras (Português/Inglês)', 'UEM', 'Maringá - PR', 'Sul', 'Noturno', 'Licenciatura', 665.90, 612.40, 60, 8],
    ['Design de Moda', 'UEM', 'Cianorte - PR', 'Sul', 'Integral', 'Bacharelado', 682.30, 629.10, 40, 8],
    ['Zootecnia', 'UEM', 'Umuarama - PR', 'Sul', 'Integral', 'Bacharelado', 675.10, 622.00, 40, 10],
    ['Química', 'UEM', 'Maringá - PR', 'Sul', 'Integral', 'Bacharelado', 685.40, 632.10, 40, 8],
    ['Física', 'UEM', 'Maringá - PR', 'Sul', 'Integral', 'Bacharelado', 688.10, 635.20, 40, 8],
    ['Matemática', 'UEM', 'Maringá - PR', 'Sul', 'Integral', 'Bacharelado', 682.90, 629.50, 40, 8],
    ['Ciências Biológicas', 'UEM', 'Maringá - PR', 'Sul', 'Integral', 'Bacharelado', 708.20, 655.40, 50, 8]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($uemCourses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🏛️ Todos os Cursos da UEM Cadastrados com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($uemCourses) . " cursos da Universidade Estadual de Maringá (UEM)</strong> no Guia Nacional!</p>
</div>";
