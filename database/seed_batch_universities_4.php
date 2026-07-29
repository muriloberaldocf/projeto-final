<?php
/**
 * GERADOR EM LOTE 4 DE CURSOS DAS UNIVERSIDADES FEDERAIS SOLICITADAS:
 * UFTM, UFVJM, UFF, UFRRJ, UTFPR, UFFS, UNILA, UFCSPA.
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(600);

echo "<h3>🏛️ Cadastrando Cursos e Notas de Corte para UFTM, UFVJM, UFF, UFRRJ, UTFPR, UFFS, UNILA e UFCSPA...</h3>";

$batch4Courses = [
    // --- UFTM (Uberaba - MG) ---
    ['Medicina', 'UFTM', 'Uberaba - MG', 'Sudeste', 'Integral', 'Bacharelado', 809.50, 766.20, 80, 12],
    ['Engenharia de Software', 'UFTM', 'Uberaba - MG', 'Sudeste', 'Noturno', 'Bacharelado', 738.90, 688.50, 40, 9],
    ['Biomedicina', 'UFTM', 'Uberaba - MG', 'Sudeste', 'Integral', 'Bacharelado', 735.20, 682.00, 40, 8],
    ['Enfermagem', 'UFTM', 'Uberaba - MG', 'Sudeste', 'Integral', 'Bacharelado', 695.40, 642.10, 60, 8],
    ['Fisioterapia', 'UFTM', 'Uberaba - MG', 'Sudeste', 'Integral', 'Bacharelado', 708.90, 655.60, 40, 10],
    ['Engenharia Civil', 'UFTM', 'Uberaba - MG', 'Sudeste', 'Integral', 'Bacharelado', 721.40, 668.10, 50, 10],
    ['Engenharia Química', 'UFTM', 'Uberaba - MG', 'Sudeste', 'Integral', 'Bacharelado', 712.50, 659.20, 40, 10],

    // --- UFVJM (Diamantina - MG) ---
    ['Medicina', 'UFVJM', 'Diamantina - MG', 'Sudeste', 'Integral', 'Bacharelado', 799.80, 756.20, 60, 12],
    ['Medicina', 'UFVJM', 'Teófilo Otoni - MG', 'Sudeste', 'Integral', 'Bacharelado', 792.40, 748.10, 60, 12],
    ['Odontologia', 'UFVJM', 'Diamantina - MG', 'Sudeste', 'Integral', 'Bacharelado', 738.50, 688.20, 50, 10],
    ['Engenharia Florestal', 'UFVJM', 'Diamantina - MG', 'Sudeste', 'Integral', 'Bacharelado', 668.90, 615.40, 40, 10],
    ['Agronomia', 'UFVJM', 'Unaí - MG', 'Sudeste', 'Integral', 'Bacharelado', 675.20, 622.00, 50, 10],
    ['Sistemas de Informação', 'UFVJM', 'Diamantina - MG', 'Sudeste', 'Noturno', 'Bacharelado', 712.30, 659.00, 40, 8],

    // --- UFF (Niterói - RJ) ---
    ['Medicina', 'UFF', 'Niterói - RJ', 'Sudeste', 'Integral', 'Bacharelado', 812.40, 769.10, 90, 12],
    ['Engenharia de Software', 'UFF', 'Niterói - RJ', 'Sudeste', 'Integral', 'Bacharelado', 768.90, 718.50, 50, 9],
    ['Ciência da Computação', 'UFF', 'Niterói - RJ', 'Sudeste', 'Integral', 'Bacharelado', 775.20, 725.00, 60, 8],
    ['Sistemas de Informação', 'UFF', 'Niterói - RJ', 'Sudeste', 'Noturno', 'Bacharelado', 752.10, 702.00, 60, 8],
    ['Direito', 'UFF', 'Niterói - RJ', 'Sudeste', 'Matutino', 'Bacharelado', 768.50, 718.20, 120, 10],
    ['Odontologia', 'UFF', 'Niterói - RJ', 'Sudeste', 'Integral', 'Bacharelado', 752.40, 702.10, 60, 10],
    ['Psicologia', 'UFF', 'Niterói - RJ', 'Sudeste', 'Integral', 'Bacharelado', 755.80, 705.40, 60, 10],
    ['Arquitetura e Urbanismo', 'UFF', 'Niterói - RJ', 'Sudeste', 'Integral', 'Bacharelado', 758.20, 708.00, 60, 10],
    ['Engenharia Mecânica', 'UFF', 'Niterói - RJ', 'Sudeste', 'Integral', 'Bacharelado', 754.10, 701.80, 80, 10],
    ['Engenharia Civil', 'UFF', 'Niterói - RJ', 'Sudeste', 'Integral', 'Bacharelado', 742.80, 689.50, 80, 10],
    ['Engenharia de Petróleo', 'UFF', 'Macaé - RJ', 'Sudeste', 'Integral', 'Bacharelado', 738.90, 685.40, 40, 10],
    ['Jornalismo', 'UFF', 'Niterói - RJ', 'Sudeste', 'Integral', 'Bacharelado', 735.40, 682.10, 40, 8],

    // --- UFRRJ (Seropédica - RJ) ---
    ['Medicina Veterinária', 'UFRRJ', 'Seropédica - RJ', 'Sudeste', 'Integral', 'Bacharelado', 742.50, 689.20, 80, 10],
    ['Agronomia', 'UFRRJ', 'Seropédica - RJ', 'Sudeste', 'Integral', 'Bacharelado', 692.10, 638.90, 120, 10],
    ['Ciência da Computação', 'UFRRJ', 'Seropédica - RJ', 'Sudeste', 'Integral', 'Bacharelado', 745.80, 695.40, 50, 8],
    ['Sistemas de Informação', 'UFRRJ', 'Nova Iguaçu - RJ', 'Sudeste', 'Noturno', 'Bacharelado', 728.90, 678.50, 50, 8],
    ['Direito', 'UFRRJ', 'Seropédica - RJ', 'Sudeste', 'Noturno', 'Bacharelado', 748.20, 698.00, 80, 10],
    ['Engenharia Florestal', 'UFRRJ', 'Seropédica - RJ', 'Sudeste', 'Integral', 'Bacharelado', 675.40, 622.10, 60, 10],
    ['Zootecnia', 'UFRRJ', 'Seropédica - RJ', 'Sudeste', 'Integral', 'Bacharelado', 668.20, 615.00, 60, 10],

    // --- UTFPR (Paraná) ---
    ['Engenharia de Software', 'UTFPR', 'Cornélio Procópio - PR', 'Sul', 'Integral', 'Bacharelado', 735.40, 685.10, 40, 9],
    ['Engenharia de Software', 'UTFPR', 'Dois Vizinhos - PR', 'Sul', 'Integral', 'Bacharelado', 721.80, 671.50, 40, 9],
    ['Ciência da Computação', 'UTFPR', 'Curitiba - PR', 'Sul', 'Integral', 'Bacharelado', 772.50, 722.10, 60, 8],
    ['Engenharia da Computação', 'UTFPR', 'Curitiba - PR', 'Sul', 'Integral', 'Bacharelado', 768.90, 718.50, 60, 10],
    ['Sistemas de Informação', 'UTFPR', 'Curitiba - PR', 'Sul', 'Noturno', 'Bacharelado', 748.20, 698.00, 60, 8],
    ['Engenharia Mecânica', 'UTFPR', 'Curitiba - PR', 'Sul', 'Integral', 'Bacharelado', 755.40, 702.10, 80, 10],
    ['Engenharia Mecatrônica', 'UTFPR', 'Curitiba - PR', 'Sul', 'Integral', 'Bacharelado', 761.20, 708.00, 60, 10],
    ['Engenharia Civil', 'UTFPR', 'Curitiba - PR', 'Sul', 'Integral', 'Bacharelado', 738.90, 685.40, 80, 10],
    ['Arquitetura e Urbanismo', 'UTFPR', 'Curitiba - PR', 'Sul', 'Integral', 'Bacharelado', 745.10, 692.00, 60, 10],

    // --- UFFS (Fronteira Sul) ---
    ['Medicina', 'UFFS', 'Chapecó - SC', 'Sul', 'Integral', 'Bacharelado', 795.80, 751.20, 80, 12],
    ['Medicina', 'UFFS', 'Passo Fundo - RS', 'Sul', 'Integral', 'Bacharelado', 792.10, 747.80, 40, 12],
    ['Ciência da Computação', 'UFFS', 'Chapecó - SC', 'Sul', 'Integral', 'Bacharelado', 728.50, 678.10, 40, 8],
    ['Agronomia', 'UFFS', 'Chapecó - SC', 'Sul', 'Integral', 'Bacharelado', 671.20, 618.00, 60, 10],
    ['Medicina Veterinária', 'UFFS', 'Realeza - PR', 'Sul', 'Integral', 'Bacharelado', 708.40, 655.10, 50, 10],
    ['Enfermagem', 'UFFS', 'Chapecó - SC', 'Sul', 'Integral', 'Bacharelado', 681.90, 628.50, 50, 8],

    // --- UNILA (Foz do Iguaçu - PR) ---
    ['Medicina', 'UNILA', 'Foz do Iguaçu - PR', 'Sul', 'Integral', 'Bacharelado', 794.20, 749.80, 60, 12],
    ['Engenharia de Software', 'UNILA', 'Foz do Iguaçu - PR', 'Sul', 'Integral', 'Bacharelado', 725.10, 675.00, 40, 9],
    ['Ciência da Computação', 'UNILA', 'Foz do Iguaçu - PR', 'Sul', 'Integral', 'Bacharelado', 732.40, 682.10, 40, 8],
    ['Relações Internacionais e Integração', 'UNILA', 'Foz do Iguaçu - PR', 'Sul', 'Integral', 'Bacharelado', 728.90, 675.40, 40, 8],
    ['Arquitetura e Urbanismo', 'UNILA', 'Foz do Iguaçu - PR', 'Sul', 'Integral', 'Bacharelado', 721.50, 668.20, 40, 10],
    ['Engenharia Civil de Infraestrutura', 'UNILA', 'Foz do Iguaçu - PR', 'Sul', 'Integral', 'Bacharelado', 712.30, 659.00, 50, 10],

    // --- UFCSPA (Porto Alegre - RS) ---
    ['Medicina', 'UFCSPA', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 808.90, 765.40, 90, 12],
    ['Biomedicina', 'UFCSPA', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 748.20, 698.00, 40, 8],
    ['Psicologia', 'UFCSPA', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 742.50, 692.10, 40, 10],
    ['Fisioterapia', 'UFCSPA', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 718.90, 665.40, 40, 10],
    ['Farmácia', 'UFCSPA', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 712.40, 659.10, 50, 10],
    ['Enfermagem', 'UFCSPA', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 698.50, 645.20, 50, 8],
    ['Nutrição', 'UFCSPA', 'Porto Alegre - RS', 'Sul', 'Integral', 'Bacharelado', 695.10, 641.80, 40, 8]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($batch4Courses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🎉 Carga das 8 Universidades do 4º Lote Concluída com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($batch4Courses) . " novos cursos</strong> abrangendo UFTM, UFVJM, UFF, UFRRJ, UTFPR, UFFS, UNILA e UFCSPA!</p>
</div>";
