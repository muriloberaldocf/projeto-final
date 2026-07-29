<?php
/**
 * GERADOR COMPLETO DE CURSOS DA USP (UNIVERSIDADE DE SÃO PAULO)
 * Adiciona todas as opções de graduação da USP (São Paulo, Ribeirão Preto, São Carlos, Piracicaba, Bauru, Lorena, Pirassununga).
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(300);

echo "<h3>🏛️ Cadastrando Todos os Cursos e Notas de Corte da USP (Universidade de São Paulo)...</h3>";

$uspCourses = [
    ['Medicina', 'USP', 'São Paulo (Pinheiros) - SP', 'Sudeste', 'Integral', 'Bacharelado', 815.40, 772.10, 80, 12],
    ['Medicina', 'USP', 'Ribeirão Preto - SP', 'Sudeste', 'Integral', 'Bacharelado', 812.80, 769.50, 100, 12],
    ['Medicina', 'USP', 'Bauru - SP', 'Sudeste', 'Integral', 'Bacharelado', 809.50, 766.20, 60, 12],
    ['Engenharia da Computação', 'USP / Poli', 'São Paulo - SP', 'Sudeste', 'Integral', 'Bacharelado', 792.10, 742.50, 60, 10],
    ['Ciência da Computação', 'USP / IME', 'São Paulo - SP', 'Sudeste', 'Integral', 'Bacharelado', 785.60, 735.20, 60, 8],
    ['Ciência da Computação', 'USP / ICMC', 'São Carlos - SP', 'Sudeste', 'Integral', 'Bacharelado', 778.90, 728.40, 60, 8],
    ['Sistemas de Informação', 'USP / EACH', 'São Paulo (Leste) - SP', 'Sudeste', 'Noturno', 'Bacharelado', 748.20, 696.10, 60, 8],
    ['Engenharia de Software & Dados', 'USP', 'São Carlos - SP', 'Sudeste', 'Integral', 'Bacharelado', 765.40, 715.10, 40, 8],
    ['Direito', 'USP / SanFran', 'São Paulo - SP', 'Sudeste', 'Matutino', 'Bacharelado', 778.20, 728.40, 150, 10],
    ['Direito', 'USP', 'Ribeirão Preto - SP', 'Sudeste', 'Noturno', 'Bacharelado', 762.40, 712.10, 100, 10],
    ['Odontologia', 'USP / FOUSP', 'São Paulo - SP', 'Sudeste', 'Integral', 'Bacharelado', 758.90, 708.20, 80, 10],
    ['Odontologia', 'USP', 'Ribeirão Preto - SP', 'Sudeste', 'Integral', 'Bacharelado', 745.20, 695.10, 60, 10],
    ['Odontologia', 'USP / FOB', 'Bauru - SP', 'Sudeste', 'Integral', 'Bacharelado', 742.80, 692.40, 50, 10],
    ['Psicologia', 'USP / IP', 'São Paulo - SP', 'Sudeste', 'Integral', 'Bacharelado', 762.30, 712.10, 60, 10],
    ['Psicologia', 'USP', 'Ribeirão Preto - SP', 'Sudeste', 'Integral', 'Bacharelado', 748.50, 698.20, 40, 10],
    ['Arquitetura e Urbanismo', 'USP / FAU', 'São Paulo - SP', 'Sudeste', 'Integral', 'Bacharelado', 762.50, 710.30, 90, 10],
    ['Arquitetura e Urbanismo', 'USP / IAU', 'São Carlos - SP', 'Sudeste', 'Integral', 'Bacharelado', 748.10, 695.80, 40, 10],
    ['Engenharia Aeronáutica', 'USP / EESC', 'São Carlos - SP', 'Sudeste', 'Integral', 'Bacharelado', 782.40, 732.10, 40, 10],
    ['Engenharia Mecatrônica', 'USP / Poli', 'São Paulo - SP', 'Sudeste', 'Integral', 'Bacharelado', 772.40, 720.10, 50, 10],
    ['Engenharia de Produção', 'USP / Poli', 'São Paulo - SP', 'Sudeste', 'Integral', 'Bacharelado', 758.30, 705.10, 90, 10],
    ['Engenharia Civil', 'USP / Poli', 'São Paulo - SP', 'Sudeste', 'Integral', 'Bacharelado', 752.10, 695.40, 120, 10],
    ['Engenharia Elétrica', 'USP / Poli', 'São Paulo - SP', 'Sudeste', 'Integral', 'Bacharelado', 748.50, 692.10, 100, 10],
    ['Engenharia Química', 'USP / EEL', 'Lorena - SP', 'Sudeste', 'Integral', 'Bacharelado', 728.40, 675.10, 80, 10],
    ['Agronomia', 'USP / ESALQ', 'Piracicaba - SP', 'Sudeste', 'Integral', 'Bacharelado', 712.50, 660.10, 200, 10],
    ['Medicina Veterinária', 'USP / FMVZ', 'São Paulo - SP', 'Sudeste', 'Integral', 'Bacharelado', 742.60, 690.20, 80, 10],
    ['Medicina Veterinária', 'USP', 'Pirassununga - SP', 'Sudeste', 'Integral', 'Bacharelado', 728.90, 675.40, 60, 10],
    ['Biomedicina', 'USP / ICB', 'São Paulo - SP', 'Sudeste', 'Integral', 'Bacharelado', 752.10, 701.80, 40, 8],
    ['Enfermagem', 'USP / EE', 'São Paulo - SP', 'Sudeste', 'Integral', 'Bacharelado', 712.40, 660.10, 80, 8],
    ['Farmácia', 'USP / FCF', 'São Paulo - SP', 'Sudeste', 'Integral', 'Bacharelado', 725.40, 672.10, 120, 10],
    ['Fisioterapia', 'USP / FM', 'São Paulo - SP', 'Sudeste', 'Integral', 'Bacharelado', 728.30, 675.10, 40, 10],
    ['Nutrição', 'USP / FSP', 'São Paulo - SP', 'Sudeste', 'Integral', 'Bacharelado', 718.50, 665.20, 40, 8],
    ['Fonoaudiologia', 'USP / FOB', 'Bauru - SP', 'Sudeste', 'Integral', 'Bacharelado', 695.20, 642.10, 40, 8],
    ['Educação Física', 'USP / EEFE', 'São Paulo - SP', 'Sudeste', 'Integral', 'Bacharelado', 685.30, 632.10, 90, 8],
    ['Administração', 'USP / FEA', 'São Paulo - SP', 'Sudeste', 'Noturno', 'Bacharelado', 738.90, 685.20, 200, 8],
    ['Ciências Econômicas', 'USP / FEA', 'São Paulo - SP', 'Sudeste', 'Integral', 'Bacharelado', 748.50, 695.20, 150, 8],
    ['Relações Internacionais', 'USP / IRI', 'São Paulo - SP', 'Sudeste', 'Integral', 'Bacharelado', 755.80, 702.40, 60, 8],
    ['Ciências Contábeis', 'USP / FEA', 'São Paulo - SP', 'Sudeste', 'Noturno', 'Bacharelado', 718.50, 665.20, 150, 8],
    ['Jornalismo', 'USP / ECA', 'São Paulo - SP', 'Sudeste', 'Matutino', 'Bacharelado', 742.30, 689.10, 60, 8],
    ['Publicidade e Propaganda', 'USP / ECA', 'São Paulo - SP', 'Sudeste', 'Matutino', 'Bacharelado', 738.10, 685.20, 50, 8],
    ['Audiovisual', 'USP / ECA', 'São Paulo - SP', 'Sudeste', 'Integral', 'Bacharelado', 745.90, 692.50, 35, 8],
    ['Design', 'USP / FAU', 'São Paulo - SP', 'Sudeste', 'Noturno', 'Bacharelado', 735.40, 682.10, 40, 8],
    ['Pedagogia', 'USP / FE', 'São Paulo - SP', 'Sudeste', 'Noturno', 'Licenciatura', 675.40, 622.10, 180, 8],
    ['História', 'USP / FFLCH', 'São Paulo - SP', 'Sudeste', 'Noturno', 'Licenciatura', 721.80, 668.50, 120, 8],
    ['Geografia', 'USP / FFLCH', 'São Paulo - SP', 'Sudeste', 'Noturno', 'Licenciatura', 682.40, 629.10, 90, 8],
    ['Letras', 'USP / FFLCH', 'São Paulo - SP', 'Sudeste', 'Noturno', 'Licenciatura', 685.90, 632.40, 300, 8],
    ['Química', 'USP / IQ', 'São Paulo - SP', 'Sudeste', 'Integral', 'Bacharelado', 708.90, 655.40, 60, 8],
    ['Física', 'USP / IF', 'São Paulo - SP', 'Sudeste', 'Integral', 'Bacharelado', 718.20, 665.10, 60, 8],
    ['Matemática', 'USP / IME', 'São Paulo - SP', 'Sudeste', 'Integral', 'Bacharelado', 715.40, 662.10, 50, 8],
    ['Estatística & Ciência de Dados', 'USP / ICMC', 'São Carlos - SP', 'Sudeste', 'Integral', 'Bacharelado', 758.10, 705.20, 40, 8],
    ['Turismo', 'USP / EACH', 'São Paulo (Leste) - SP', 'Sudeste', 'Noturno', 'Bacharelado', 665.40, 612.10, 60, 8]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($uspCourses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🏛️ Todos os Cursos da USP Cadastrados com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($uspCourses) . " cursos da Universidade de São Paulo (USP)</strong> em todos os câmpus (São Paulo, Ribeirão Preto, São Carlos, Piracicaba, Bauru, Lorena, Pirassununga)!</p>
</div>";
