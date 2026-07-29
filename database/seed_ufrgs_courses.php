<?php
/**
 * GERADOR COMPLETO DE CURSOS DA UFRGS (UNIVERSIDADE FEDERAL DO RIO GRANDE DO SUL)
 * Adiciona todas as opções de graduação da UFRGS e suas notas de corte.
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(300);

echo "<h3>🏛️ Cadastrando Todos os Cursos e Notas de Corte da UFRGS (Universidade Federal do Rio Grande do Sul)...</h3>";

$ufrgsCourses = [
    ['Medicina', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 802.70, 759.80, 90, 12],
    ['Engenharia de Software', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Noturno', 'Bacharelado', 758.90, 708.20, 40, 9],
    ['Ciência da Computação', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 762.30, 712.10, 50, 8],
    ['Engenharia da Computação', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 755.40, 705.10, 50, 10],
    ['Direito', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Matutino', 'Bacharelado', 752.40, 702.10, 120, 10],
    ['Direito', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Noturno', 'Bacharelado', 749.80, 699.50, 120, 10],
    ['Odontologia', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 742.80, 692.50, 50, 10],
    ['Psicologia', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 739.20, 689.00, 40, 10],
    ['Arquitetura e Urbanismo', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 738.60, 688.20, 60, 10],
    ['Medicina Veterinária', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 735.10, 682.40, 60, 10],
    ['Agronomia', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 708.40, 655.10, 80, 10],
    ['Engenharia Química', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 729.80, 673.50, 60, 10],
    ['Engenharia Mecânica', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 748.20, 695.10, 80, 10],
    ['Engenharia Civil', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 732.50, 679.80, 80, 10],
    ['Engenharia Elétrica', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 725.40, 672.10, 60, 10],
    ['Biomedicina', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 735.90, 682.70, 40, 8],
    ['Enfermagem', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 698.40, 645.10, 50, 8],
    ['Farmácia', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 705.20, 652.00, 60, 10],
    ['Fisioterapia', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 712.80, 659.40, 40, 10],
    ['Nutrição', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 695.30, 642.10, 40, 8],
    ['Educação Física', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 675.40, 622.10, 60, 8],
    ['Educação Física', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Noturno', 'Licenciatura', 662.80, 609.50, 60, 8],
    ['Design de Produto', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 715.80, 662.50, 40, 8],
    ['Jornalismo', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 728.40, 675.10, 40, 8],
    ['Publicidade e Propaganda', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 722.10, 668.90, 40, 8],
    ['Administração', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Noturno', 'Bacharelado', 721.50, 668.20, 100, 8],
    ['Ciências Econômicas', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Noturno', 'Bacharelado', 718.90, 665.40, 80, 8],
    ['Relações Internacionais', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 742.50, 689.20, 40, 8],
    ['Ciências Contábeis', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Noturno', 'Bacharelado', 702.40, 649.10, 80, 8],
    ['Pedagogia', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Noturno', 'Licenciatura', 665.20, 612.00, 80, 8],
    ['História', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Noturno', 'Licenciatura', 681.40, 628.20, 40, 8],
    ['Geografia', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Noturno', 'Licenciatura', 668.90, 615.40, 40, 8],
    ['Letras (Português/Inglês)', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Noturno', 'Licenciatura', 672.30, 619.00, 60, 8],
    ['Química', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 695.80, 642.50, 40, 8],
    ['Física', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 698.20, 645.00, 40, 8],
    ['Matemática', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 691.40, 638.10, 40, 8],
    ['Ciências Biológicas', 'UFRGS', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 718.50, 665.20, 50, 8]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($ufrgsCourses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🏛️ Todos os Cursos da UFRGS Cadastrados com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($ufrgsCourses) . " cursos da Universidade Federal do Rio Grande do Sul (UFRGS)</strong> no Guia Nacional!</p>
</div>";
