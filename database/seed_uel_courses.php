<?php
/**
 * GERADOR COMPLETO DE CURSOS DA UEL (UNIVERSIDADE ESTADUAL DE LONDRINA - PR)
 * Adiciona todas as opções de graduação da UEL e suas notas de corte.
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(300);

echo "<h3>🏛️ Cadastrando Todos os Cursos e Notas de Corte da UEL (Universidade Estadual de Londrina)...</h3>";

$uelCourses = [
    ['Medicina', 'UEL', 'Londrina - PR', 'Sul', 'Integral', 'Bacharelado', 802.40, 759.10, 80, 12],
    ['Engenharia de Software', 'UEL', 'Londrina - PR', 'Sul', 'Noturno', 'Bacharelado', 738.50, 688.20, 40, 8],
    ['Ciência da Computação', 'UEL', 'Londrina - PR', 'Sul', 'Integral', 'Bacharelado', 745.10, 695.40, 40, 8],
    ['Direito', 'UEL', 'Londrina - PR', 'Sul', 'Matutino', 'Bacharelado', 754.20, 704.00, 100, 10],
    ['Direito', 'UEL', 'Londrina - PR', 'Sul', 'Noturno', 'Bacharelado', 751.80, 701.50, 100, 10],
    ['Odontologia', 'UEL', 'Londrina - PR', 'Sul', 'Integral', 'Bacharelado', 745.80, 695.20, 50, 10],
    ['Psicologia', 'UEL', 'Londrina - PR', 'Sul', 'Integral', 'Bacharelado', 741.30, 691.00, 40, 10],
    ['Arquitetura e Urbanismo', 'UEL', 'Londrina - PR', 'Sul', 'Integral', 'Bacharelado', 735.60, 685.20, 40, 10],
    ['Medicina Veterinária', 'UEL', 'Londrina - PR', 'Sul', 'Integral', 'Bacharelado', 732.10, 679.50, 60, 10],
    ['Agronomia', 'UEL', 'Londrina - PR', 'Sul', 'Integral', 'Bacharelado', 712.40, 659.80, 80, 10],
    ['Engenharia Civil', 'UEL', 'Londrina - PR', 'Sul', 'Integral', 'Bacharelado', 731.20, 678.90, 60, 10],
    ['Engenharia Elétrica', 'UEL', 'Londrina - PR', 'Sul', 'Integral', 'Bacharelado', 718.40, 665.10, 40, 10],
    ['Biomedicina', 'UEL', 'Londrina - PR', 'Sul', 'Integral', 'Bacharelado', 738.20, 685.30, 40, 8],
    ['Enfermagem', 'UEL', 'Londrina - PR', 'Sul', 'Integral', 'Bacharelado', 701.50, 648.20, 40, 8],
    ['Farmácia', 'UEL', 'Londrina - PR', 'Sul', 'Integral', 'Bacharelado', 705.80, 652.40, 50, 10],
    ['Fisioterapia', 'UEL', 'Londrina - PR', 'Sul', 'Integral', 'Bacharelado', 715.40, 662.10, 40, 10],
    ['Nutrição', 'UEL', 'Londrina - PR', 'Sul', 'Integral', 'Bacharelado', 698.30, 645.10, 40, 8],
    ['Educação Física', 'UEL', 'Londrina - PR', 'Sul', 'Integral', 'Bacharelado', 671.20, 618.50, 60, 8],
    ['Educação Física', 'UEL', 'Londrina - PR', 'Sul', 'Noturno', 'Licenciatura', 658.40, 605.20, 60, 8],
    ['Design Gráfico', 'UEL', 'Londrina - PR', 'Sul', 'Integral', 'Bacharelado', 712.50, 659.20, 40, 8],
    ['Design de Moda', 'UEL', 'Londrina - PR', 'Sul', 'Integral', 'Bacharelado', 689.40, 636.10, 40, 8],
    ['Jornalismo', 'UEL', 'Londrina - PR', 'Sul', 'Integral', 'Bacharelado', 724.30, 671.00, 40, 8],
    ['Relações Públicas', 'UEL', 'Londrina - PR', 'Sul', 'Noturno', 'Bacharelado', 695.80, 642.50, 40, 8],
    ['Administração', 'UEL', 'Londrina - PR', 'Sul', 'Noturno', 'Bacharelado', 715.20, 662.30, 100, 8],
    ['Ciências Econômicas', 'UEL', 'Londrina - PR', 'Sul', 'Noturno', 'Bacharelado', 708.90, 655.40, 80, 8],
    ['Ciências Contábeis', 'UEL', 'Londrina - PR', 'Sul', 'Noturno', 'Bacharelado', 701.20, 648.10, 80, 8],
    ['Pedagogia', 'UEL', 'Londrina - PR', 'Sul', 'Noturno', 'Licenciatura', 662.50, 609.20, 80, 8],
    ['História', 'UEL', 'Londrina - PR', 'Sul', 'Noturno', 'Licenciatura', 675.80, 622.40, 40, 8],
    ['Geografia', 'UEL', 'Londrina - PR', 'Sul', 'Noturno', 'Licenciatura', 664.20, 611.10, 40, 8],
    ['Letras (Português/Inglês)', 'UEL', 'Londrina - PR', 'Sul', 'Noturno', 'Licenciatura', 668.50, 615.20, 60, 8],
    ['Artes Cênicas', 'UEL', 'Londrina - PR', 'Sul', 'Integral', 'Bacharelado', 672.10, 618.90, 30, 8],
    ['Música', 'UEL', 'Londrina - PR', 'Sul', 'Integral', 'Licenciatura', 681.40, 628.30, 30, 8],
    ['Química', 'UEL', 'Londrina - PR', 'Sul', 'Integral', 'Bacharelado', 688.20, 635.10, 40, 8],
    ['Física', 'UEL', 'Londrina - PR', 'Sul', 'Integral', 'Bacharelado', 691.50, 638.20, 40, 8],
    ['Matemática', 'UEL', 'Londrina - PR', 'Sul', 'Integral', 'Bacharelado', 685.40, 632.10, 40, 8],
    ['Ciências Biológicas', 'UEL', 'Londrina - PR', 'Sul', 'Integral', 'Bacharelado', 711.30, 658.40, 50, 8]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($uelCourses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🏛️ Todos os Cursos da UEL Cadastrados com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($uelCourses) . " cursos da Universidade Estadual de Londrina (UEL)</strong> no Guia Nacional!</p>
</div>";
