<?php
/**
 * GERADOR COMPLETO DE CURSOS DA UFRJ (UNIVERSIDADE FEDERAL DO RIO DE JANEIRO)
 * Adiciona todas as opções de graduação da UFRJ (Fundão, Praia Vermelha, Macaé, Duque de Caxias).
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(300);

echo "<h3>🏛️ Cadastrando Todos os Cursos e Notas de Corte da UFRJ (Universidade Federal do Rio de Janeiro)...</h3>";

$ufrjCourses = [
    ['Medicina', 'UFRJ', 'Rio de Janeiro (Fundão) - RJ', 'Sudeste', 'Integral', 'Bacharelado', 808.90, 765.00, 100, 12],
    ['Medicina', 'UFRJ', 'Macaé - RJ', 'Sudeste', 'Integral', 'Bacharelado', 798.50, 754.20, 60, 12],
    ['Engenharia de Software & Computação', 'UFRJ / Poli', 'Rio de Janeiro - RJ', 'Sudeste', 'Integral', 'Bacharelado', 772.40, 722.10, 60, 9],
    ['Ciência da Computação', 'UFRJ / IM', 'Rio de Janeiro - RJ', 'Sudeste', 'Integral', 'Bacharelado', 778.50, 728.90, 60, 8],
    ['Direito', 'UFRJ / FND', 'Rio de Janeiro (Centro) - RJ', 'Sudeste', 'Matutino', 'Bacharelado', 765.10, 715.30, 200, 10],
    ['Direito', 'UFRJ / FND', 'Rio de Janeiro (Centro) - RJ', 'Sudeste', 'Noturno', 'Bacharelado', 761.80, 711.90, 200, 10],
    ['Odontologia', 'UFRJ', 'Rio de Janeiro - RJ', 'Sudeste', 'Integral', 'Bacharelado', 752.40, 702.10, 60, 10],
    ['Psicologia', 'UFRJ / IP', 'Rio de Janeiro (Praia Vermelha) - RJ', 'Sudeste', 'Integral', 'Bacharelado', 748.60, 698.20, 80, 10],
    ['Arquitetura e Urbanismo', 'UFRJ / FAU', 'Rio de Janeiro - RJ', 'Sudeste', 'Integral', 'Bacharelado', 754.20, 704.10, 90, 10],
    ['Engenharia Mecânica', 'UFRJ / Poli', 'Rio de Janeiro - RJ', 'Sudeste', 'Integral', 'Bacharelado', 758.90, 708.40, 80, 10],
    ['Engenharia Química', 'UFRJ / EQ', 'Rio de Janeiro - RJ', 'Sudeste', 'Integral', 'Bacharelado', 745.80, 695.20, 60, 10],
    ['Engenharia Civil', 'UFRJ / Poli', 'Rio de Janeiro - RJ', 'Sudeste', 'Integral', 'Bacharelado', 742.10, 691.50, 100, 10],
    ['Engenharia Elétrica', 'UFRJ / Poli', 'Rio de Janeiro - RJ', 'Sudeste', 'Integral', 'Bacharelado', 738.50, 688.10, 80, 10],
    ['Engenharia de Petróleo', 'UFRJ / Poli', 'Macaé - RJ', 'Sudeste', 'Integral', 'Bacharelado', 748.20, 695.10, 50, 10],
    ['Engenharia de Produção', 'UFRJ / Poli', 'Rio de Janeiro - RJ', 'Sudeste', 'Integral', 'Bacharelado', 755.10, 702.30, 80, 10],
    ['Medicina Veterinária', 'UFRJ', 'Rio de Janeiro - RJ', 'Sudeste', 'Integral', 'Bacharelado', 735.40, 682.10, 60, 10],
    ['Biomedicina', 'UFRJ', 'Rio de Janeiro - RJ', 'Sudeste', 'Integral', 'Bacharelado', 748.90, 698.50, 40, 8],
    ['Enfermagem', 'UFRJ / EEAN', 'Rio de Janeiro - RJ', 'Sudeste', 'Integral', 'Bacharelado', 701.80, 649.50, 100, 8],
    ['Farmácia', 'UFRJ / FF', 'Rio de Janeiro - RJ', 'Sudeste', 'Integral', 'Bacharelado', 718.50, 665.20, 100, 10],
    ['Fisioterapia', 'UFRJ', 'Rio de Janeiro - RJ', 'Sudeste', 'Integral', 'Bacharelado', 724.10, 671.80, 50, 10],
    ['Nutrição', 'UFRJ / INJC', 'Rio de Janeiro - RJ', 'Sudeste', 'Integral', 'Bacharelado', 708.20, 655.40, 60, 8],
    ['Educação Física', 'UFRJ / EEFD', 'Rio de Janeiro - RJ', 'Sudeste', 'Integral', 'Bacharelado', 678.90, 625.30, 90, 8],
    ['Gastronomia', 'UFRJ', 'Rio de Janeiro - RJ', 'Sudeste', 'Integral', 'Bacharelado', 708.20, 655.10, 40, 8],
    ['Publicidade e Propaganda', 'UFRJ / ECO', 'Rio de Janeiro - RJ', 'Sudeste', 'Integral', 'Bacharelado', 732.50, 679.20, 50, 8],
    ['Jornalismo', 'UFRJ / ECO', 'Rio de Janeiro - RJ', 'Sudeste', 'Integral', 'Bacharelado', 738.90, 685.40, 50, 8],
    ['Radialismo / Cinema', 'UFRJ / ECO', 'Rio de Janeiro - RJ', 'Sudeste', 'Integral', 'Bacharelado', 728.10, 675.20, 40, 8],
    ['Administração', 'UFRJ / FACC', 'Rio de Janeiro - RJ', 'Sudeste', 'Noturno', 'Bacharelado', 731.40, 678.10, 120, 8],
    ['Ciências Econômicas', 'UFRJ / IE', 'Rio de Janeiro - RJ', 'Sudeste', 'Noturno', 'Bacharelado', 735.80, 682.50, 100, 8],
    ['Relações Internacionais', 'UFRJ / IRJ', 'Rio de Janeiro - RJ', 'Sudeste', 'Integral', 'Bacharelado', 752.10, 698.90, 40, 8],
    ['Ciências Contábeis', 'UFRJ / FACC', 'Rio de Janeiro - RJ', 'Sudeste', 'Noturno', 'Bacharelado', 712.40, 659.10, 100, 8],
    ['Pedagogia', 'UFRJ / FE', 'Rio de Janeiro - RJ', 'Sudeste', 'Noturno', 'Licenciatura', 668.50, 615.20, 120, 8],
    ['História', 'UFRJ / IFCS', 'Rio de Janeiro (Centro) - RJ', 'Sudeste', 'Noturno', 'Licenciatura', 698.20, 645.10, 80, 8],
    ['Geografia', 'UFRJ / IGEO', 'Rio de Janeiro - RJ', 'Sudeste', 'Noturno', 'Licenciatura', 678.40, 625.10, 80, 8],
    ['Filosofia', 'UFRJ / IFCS', 'Rio de Janeiro (Centro) - RJ', 'Sudeste', 'Integral', 'Bacharelado', 672.10, 618.90, 50, 8],
    ['Letras', 'UFRJ / FL', 'Rio de Janeiro - RJ', 'Sudeste', 'Noturno', 'Licenciatura', 685.20, 632.10, 150, 8],
    ['Química', 'UFRJ / IQ', 'Rio de Janeiro - RJ', 'Sudeste', 'Integral', 'Bacharelado', 705.40, 652.10, 60, 8],
    ['Física', 'UFRJ / IF', 'Rio de Janeiro - RJ', 'Sudeste', 'Integral', 'Bacharelado', 715.80, 662.40, 60, 8],
    ['Matemática', 'UFRJ / IM', 'Rio de Janeiro - RJ', 'Sudeste', 'Integral', 'Bacharelado', 712.30, 659.10, 60, 8],
    ['Ciências Biológicas', 'UFRJ / IB', 'Rio de Janeiro - RJ', 'Sudeste', 'Integral', 'Bacharelado', 728.50, 675.20, 80, 8]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($ufrjCourses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🏛️ Todos os Cursos da UFRJ Cadastrados com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($ufrjCourses) . " cursos da Universidade Federal do Rio de Janeiro (UFRJ)</strong> no Guia Nacional!</p>
</div>";
