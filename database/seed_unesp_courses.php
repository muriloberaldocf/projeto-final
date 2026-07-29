<?php
/**
 * GERADOR COMPLETO DE CURSOS DA UNESP (UNIVERSIDADE ESTADUAL PAULISTA "JÚLIO DE MESQUITA FILHO")
 * Adiciona todas as opções de graduação da UNESP nos seus diversos câmpus.
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(300);

echo "<h3>🏛️ Cadastrando Todos os Cursos e Notas de Corte da UNESP (Universidade Estadual Paulista)...</h3>";

$unespCourses = [
    ['Medicina', 'UNESP', 'Botucatu - SP', 'Sudeste', 'Integral', 'Bacharelado', 812.50, 768.90, 90, 12],
    ['Engenharia de Software / Ciência da Computação', 'UNESP', 'Bauru - SP', 'Sudeste', 'Noturno', 'Bacharelado', 772.40, 722.10, 60, 8],
    ['Engenharia da Computação', 'UNESP', 'São José do Rio Preto - SP', 'Sudeste', 'Integral', 'Bacharelado', 765.80, 715.40, 40, 10],
    ['Ciência da Computação', 'UNESP', 'Rio Claro - SP', 'Sudeste', 'Integral', 'Bacharelado', 758.90, 708.50, 50, 8],
    ['Ciência da Computação', 'UNESP', 'Presidente Prudente - SP', 'Sudeste', 'Noturno', 'Bacharelado', 748.20, 698.00, 40, 8],
    ['Direito', 'UNESP', 'Franca - SP', 'Sudeste', 'Matutino', 'Bacharelado', 768.50, 718.20, 100, 10],
    ['Direito', 'UNESP', 'Franca - SP', 'Sudeste', 'Noturno', 'Bacharelado', 765.10, 715.00, 100, 10],
    ['Odontologia', 'UNESP', 'Araraquara - SP', 'Sudeste', 'Integral', 'Bacharelado', 752.40, 702.10, 80, 10],
    ['Odontologia', 'UNESP', 'Araçatuba - SP', 'Sudeste', 'Integral', 'Bacharelado', 748.90, 698.50, 80, 10],
    ['Odontologia', 'UNESP', 'São José dos Campos - SP', 'Sudeste', 'Integral', 'Bacharelado', 745.20, 695.00, 60, 10],
    ['Medicina Veterinária', 'UNESP', 'Jaboticabal - SP', 'Sudeste', 'Integral', 'Bacharelado', 755.80, 705.20, 60, 10],
    ['Medicina Veterinária', 'UNESP', 'Botucatu - SP', 'Sudeste', 'Integral', 'Bacharelado', 752.10, 702.00, 60, 10],
    ['Medicina Veterinária', 'UNESP', 'Araçatuba - SP', 'Sudeste', 'Integral', 'Bacharelado', 742.50, 689.20, 40, 10],
    ['Psicologia', 'UNESP', 'Bauru - SP', 'Sudeste', 'Integral', 'Bacharelado', 758.20, 708.00, 40, 10],
    ['Arquitetura e Urbanismo', 'UNESP', 'Bauru - SP', 'Sudeste', 'Integral', 'Bacharelado', 754.10, 701.80, 40, 10],
    ['Engenharia Mecânica', 'UNESP', 'Guaratinguetá - SP', 'Sudeste', 'Integral', 'Bacharelado', 758.90, 708.50, 60, 10],
    ['Engenharia Civil', 'UNESP', 'Bauru - SP', 'Sudeste', 'Integral', 'Bacharelado', 742.80, 689.50, 60, 10],
    ['Engenharia Elétrica', 'UNESP', 'Ilha Solteira - SP', 'Sudeste', 'Integral', 'Bacharelado', 735.40, 682.10, 60, 10],
    ['Agronomia', 'UNESP', 'Jaboticabal - SP', 'Sudeste', 'Integral', 'Bacharelado', 718.50, 665.20, 100, 10],
    ['Agronomia', 'UNESP', 'Botucatu - SP', 'Sudeste', 'Integral', 'Bacharelado', 712.40, 659.10, 80, 10],
    ['Biomedicina', 'UNESP', 'Botucatu - SP', 'Sudeste', 'Integral', 'Bacharelado', 758.90, 708.40, 30, 8],
    ['Farmácia', 'UNESP', 'Araraquara - SP', 'Sudeste', 'Integral', 'Bacharelado', 728.90, 675.40, 80, 10],
    ['Fisioterapia', 'UNESP', 'Presidente Prudente - SP', 'Sudeste', 'Integral', 'Bacharelado', 718.90, 665.40, 40, 10],
    ['Nutrição', 'UNESP', 'Botucatu - SP', 'Sudeste', 'Integral', 'Bacharelado', 708.50, 655.20, 30, 8],
    ['Design', 'UNESP', 'Bauru - SP', 'Sudeste', 'Noturno', 'Bacharelado', 732.10, 678.90, 40, 8],
    ['Jornalismo', 'UNESP', 'Bauru - SP', 'Sudeste', 'Noturno', 'Bacharelado', 735.40, 682.10, 40, 8],
    ['Relações Internacionais', 'UNESP', 'Franca - SP', 'Sudeste', 'Matutino', 'Bacharelado', 748.20, 698.00, 60, 8],
    ['Administração', 'UNESP', 'Tupã - SP', 'Sudeste', 'Noturno', 'Bacharelado', 698.50, 645.20, 40, 8],
    ['Engenharia Ambiental', 'UNESP', 'Sorocaba - SP', 'Sudeste', 'Integral', 'Bacharelado', 725.10, 672.00, 40, 10],
    ['Engenharia de Alimentos', 'UNESP', 'São José do Rio Preto - SP', 'Sudeste', 'Integral', 'Bacharelado', 702.40, 649.10, 40, 10],
    ['Ciências Biológicas', 'UNESP', 'Botucatu - SP', 'Sudeste', 'Integral', 'Bacharelado', 715.80, 662.50, 60, 8]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($unespCourses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🏛️ Todos os Cursos da UNESP Cadastrados com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($unespCourses) . " cursos da Universidade Estadual Paulista (UNESP)</strong> no Guia Nacional!</p>
</div>";
