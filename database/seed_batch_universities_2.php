<?php
/**
 * GERADOR EM LOTE 2 DE CURSOS DAS UNIVERSIDADES FEDERAIS SOLICITADAS:
 * UNIFAP, UFAM, UFOPA, UFPA, UFT, UFRA, UNIFESSPA, UNIFAL-MG, UNIFEI, UFJF, UFLA.
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(600);

echo "<h3>🏛️ Cadastrando Cursos e Notas de Corte para UNIFAP, UFAM, UFOPA, UFPA, UFT, UFRA, UNIFESSPA, UNIFAL-MG, UNIFEI, UFJF e UFLA...</h3>";

$batch2Courses = [
    // --- UNIFAP (Amapá) ---
    ['Medicina', 'UNIFAP', 'Macapá - AP', 'Norte', 'Integral', 'Bacharelado', 789.50, 745.20, 60, 12],
    ['Engenharia de Software', 'UNIFAP', 'Macapá - AP', 'Norte', 'Integral', 'Bacharelado', 712.40, 662.10, 40, 9],
    ['Ciência da Computação', 'UNIFAP', 'Macapá - AP', 'Norte', 'Integral', 'Bacharelado', 721.80, 671.50, 40, 8],
    ['Direito', 'UNIFAP', 'Macapá - AP', 'Norte', 'Noturno', 'Bacharelado', 738.90, 688.40, 60, 10],
    ['Arquitetura e Urbanismo', 'UNIFAP', 'Macapá - AP', 'Norte', 'Integral', 'Bacharelado', 715.20, 665.00, 40, 10],
    ['Engenharia Civil', 'UNIFAP', 'Macapá - AP', 'Norte', 'Integral', 'Bacharelado', 701.80, 648.50, 50, 10],
    ['Enfermagem', 'UNIFAP', 'Macapá - AP', 'Norte', 'Integral', 'Bacharelado', 678.90, 625.40, 50, 8],
    ['Farmácia', 'UNIFAP', 'Macapá - AP', 'Norte', 'Integral', 'Bacharelado', 685.20, 632.00, 40, 10],

    // --- UFAM (Amazonas) ---
    ['Medicina', 'UFAM', 'Manaus - AM', 'Norte', 'Integral', 'Bacharelado', 795.40, 751.20, 100, 12],
    ['Engenharia de Software', 'UFAM', 'Manaus - AM', 'Norte', 'Integral', 'Bacharelado', 742.10, 692.00, 50, 9],
    ['Ciência da Computação', 'UFAM', 'Manaus - AM', 'Norte', 'Integral', 'Bacharelado', 755.80, 705.20, 60, 8],
    ['Engenharia da Computação', 'UFAM', 'Manaus - AM', 'Norte', 'Integral', 'Bacharelado', 748.50, 698.10, 40, 10],
    ['Direito', 'UFAM', 'Manaus - AM', 'Norte', 'Matutino', 'Bacharelado', 752.10, 702.00, 100, 10],
    ['Odontologia', 'UFAM', 'Manaus - AM', 'Norte', 'Integral', 'Bacharelado', 738.90, 688.40, 60, 10],
    ['Psicologia', 'UFAM', 'Manaus - AM', 'Norte', 'Integral', 'Bacharelado', 732.50, 682.10, 40, 10],
    ['Arquitetura e Urbanismo', 'UFAM', 'Manaus - AM', 'Norte', 'Integral', 'Bacharelado', 728.90, 675.40, 40, 10],
    ['Engenharia Mecânica', 'UFAM', 'Manaus - AM', 'Norte', 'Integral', 'Bacharelado', 735.40, 682.00, 60, 10],
    ['Engenharia Elétrica', 'UFAM', 'Manaus - AM', 'Norte', 'Integral', 'Bacharelado', 725.10, 672.00, 60, 10],
    ['Agronomia', 'UFAM', 'Humaitá - AM', 'Norte', 'Integral', 'Bacharelado', 655.80, 602.40, 50, 10],

    // --- UFOPA (Oeste do Pará) ---
    ['Medicina', 'UFOPA', 'Santarém - PA', 'Norte', 'Integral', 'Bacharelado', 788.10, 743.80, 60, 12],
    ['Ciência da Computação', 'UFOPA', 'Santarém - PA', 'Norte', 'Integral', 'Bacharelado', 718.50, 668.20, 40, 8],
    ['Engenharia Florestal', 'UFOPA', 'Santarém - PA', 'Norte', 'Integral', 'Bacharelado', 668.20, 615.00, 40, 10],
    ['Agronomia', 'UFOPA', 'Santarém - PA', 'Norte', 'Integral', 'Bacharelado', 662.50, 609.20, 50, 10],
    ['Arqueologia', 'UFOPA', 'Santarém - PA', 'Norte', 'Integral', 'Bacharelado', 641.80, 588.50, 30, 8],
    ['Direito', 'UFOPA', 'Santarém - PA', 'Norte', 'Noturno', 'Bacharelado', 735.40, 685.10, 60, 10],

    // --- UFPA (Pará) ---
    ['Medicina', 'UFPA', 'Belém - PA', 'Norte', 'Integral', 'Bacharelado', 801.20, 758.00, 150, 12],
    ['Medicina', 'UFPA', 'Altamira - PA', 'Norte', 'Integral', 'Bacharelado', 785.40, 741.00, 60, 12],
    ['Engenharia de Computação', 'UFPA', 'Belém - PA', 'Norte', 'Integral', 'Bacharelado', 752.40, 702.10, 60, 10],
    ['Ciência da Computação', 'UFPA', 'Belém - PA', 'Norte', 'Integral', 'Bacharelado', 758.90, 708.50, 60, 8],
    ['Sistemas de Informação', 'UFPA', 'Castanhal - PA', 'Norte', 'Noturno', 'Bacharelado', 712.50, 662.10, 40, 8],
    ['Direito', 'UFPA', 'Belém - PA', 'Norte', 'Matutino', 'Bacharelado', 761.50, 711.20, 160, 10],
    ['Odontologia', 'UFPA', 'Belém - PA', 'Norte', 'Integral', 'Bacharelado', 745.20, 695.00, 80, 10],
    ['Psicologia', 'UFPA', 'Belém - PA', 'Norte', 'Integral', 'Bacharelado', 739.80, 689.50, 60, 10],
    ['Arquitetura e Urbanismo', 'UFPA', 'Belém - PA', 'Norte', 'Integral', 'Bacharelado', 738.50, 688.10, 60, 10],
    ['Engenharia Civil', 'UFPA', 'Belém - PA', 'Norte', 'Integral', 'Bacharelado', 731.20, 678.00, 80, 10],

    // --- UFT (Tocantins) ---
    ['Medicina', 'UFT', 'Palmas - TO', 'Norte', 'Integral', 'Bacharelado', 792.80, 748.50, 80, 12],
    ['Medicina', 'UFT', 'Araguaína - TO', 'Norte', 'Integral', 'Bacharelado', 786.10, 741.80, 60, 12],
    ['Engenharia de Software', 'UFT', 'Palmas - TO', 'Norte', 'Integral', 'Bacharelado', 731.40, 681.20, 40, 9],
    ['Ciência da Computação', 'UFT', 'Palmas - TO', 'Norte', 'Integral', 'Bacharelado', 738.50, 688.10, 40, 8],
    ['Direito', 'UFT', 'Palmas - TO', 'Norte', 'Matutino', 'Bacharelado', 748.20, 698.00, 80, 10],
    ['Arquitetura e Urbanismo', 'UFT', 'Palmas - TO', 'Norte', 'Integral', 'Bacharelado', 725.40, 675.10, 40, 10],
    ['Engenharia Civil', 'UFT', 'Palmas - TO', 'Norte', 'Integral', 'Bacharelado', 715.80, 662.50, 50, 10],
    ['Medicina Veterinária', 'UFT', 'Araguaína - TO', 'Norte', 'Integral', 'Bacharelado', 712.30, 659.00, 50, 10],

    // --- UFRA (Rural da Amazônia) ---
    ['Medicina Veterinária', 'UFRA', 'Belém - PA', 'Norte', 'Integral', 'Bacharelado', 721.50, 668.20, 80, 10],
    ['Agronomia', 'UFRA', 'Belém - PA', 'Norte', 'Integral', 'Bacharelado', 675.40, 622.10, 100, 10],
    ['Engenharia Florestal', 'UFRA', 'Belém - PA', 'Norte', 'Integral', 'Bacharelado', 668.90, 615.50, 60, 10],
    ['Zootecnia', 'UFRA', 'Belém - PA', 'Norte', 'Integral', 'Bacharelado', 655.20, 602.00, 60, 10],
    ['Engenharia Ambiental', 'UFRA', 'Belém - PA', 'Norte', 'Integral', 'Bacharelado', 682.40, 629.10, 50, 10],
    ['Engenharia de Pesca', 'UFRA', 'Belém - PA', 'Norte', 'Integral', 'Bacharelado', 648.50, 595.20, 40, 10],

    // --- UNIFESSPA (Sul e Sudeste do Pará) ---
    ['Medicina', 'UNIFESSPA', 'Marabá - PA', 'Norte', 'Integral', 'Bacharelado', 786.40, 742.10, 40, 12],
    ['Engenharia de Computação', 'UNIFESSPA', 'Marabá - PA', 'Norte', 'Integral', 'Bacharelado', 724.50, 674.10, 40, 10],
    ['Direito', 'UNIFESSPA', 'Marabá - PA', 'Norte', 'Noturno', 'Bacharelado', 739.20, 689.00, 60, 10],
    ['Agronomia', 'UNIFESSPA', 'Marabá - PA', 'Norte', 'Integral', 'Bacharelado', 661.40, 608.10, 50, 10],
    ['Zootecnia', 'UNIFESSPA', 'Xinguara - PA', 'Norte', 'Integral', 'Bacharelado', 645.80, 592.50, 40, 10],
    ['Engenharia de Minas', 'UNIFESSPA', 'Marabá - PA', 'Norte', 'Integral', 'Bacharelado', 688.90, 635.60, 40, 10],

    // --- UNIFAL-MG (Alfenas - MG) ---
    ['Medicina', 'UNIFAL-MG', 'Alfenas - MG', 'Sudeste', 'Integral', 'Bacharelado', 802.50, 759.10, 80, 12],
    ['Odontologia', 'UNIFAL-MG', 'Alfenas - MG', 'Sudeste', 'Integral', 'Bacharelado', 742.10, 692.00, 60, 10],
    ['Farmácia', 'UNIFAL-MG', 'Alfenas - MG', 'Sudeste', 'Integral', 'Bacharelado', 712.40, 659.10, 80, 10],
    ['Biotecnologia', 'UNIFAL-MG', 'Alfenas - MG', 'Sudeste', 'Integral', 'Bacharelado', 705.80, 652.40, 40, 8],
    ['Ciência da Computação', 'UNIFAL-MG', 'Alfenas - MG', 'Sudeste', 'Integral', 'Bacharelado', 738.90, 688.50, 40, 8],
    ['Engenharia Química', 'UNIFAL-MG', 'Poços de Caldas - MG', 'Sudeste', 'Integral', 'Bacharelado', 718.20, 665.00, 50, 10],

    // --- UNIFEI (Itajubá - MG) ---
    ['Engenharia de Computação', 'UNIFEI', 'Itajubá - MG', 'Sudeste', 'Integral', 'Bacharelado', 772.40, 722.10, 60, 10],
    ['Ciência da Computação', 'UNIFEI', 'Itajubá - MG', 'Sudeste', 'Integral', 'Bacharelado', 765.80, 715.40, 50, 8],
    ['Engenharia Mecânica', 'UNIFEI', 'Itajubá - MG', 'Sudeste', 'Integral', 'Bacharelado', 758.20, 705.10, 80, 10],
    ['Engenharia Elétrica', 'UNIFEI', 'Itajubá - MG', 'Sudeste', 'Integral', 'Bacharelado', 748.90, 695.80, 80, 10],
    ['Engenharia Aeronáutica', 'UNIFEI', 'Itajubá - MG', 'Sudeste', 'Integral', 'Bacharelado', 778.50, 728.20, 40, 10],
    ['Engenharia de Energia', 'UNIFEI', 'Itajubá - MG', 'Sudeste', 'Integral', 'Bacharelado', 725.40, 672.10, 40, 10],

    // --- UFJF (Juiz de Fora - MG) ---
    ['Medicina', 'UFJF', 'Juiz de Fora - MG', 'Sudeste', 'Integral', 'Bacharelado', 806.20, 763.10, 90, 12],
    ['Medicina', 'UFJF', 'Governador Valadares - MG', 'Sudeste', 'Integral', 'Bacharelado', 798.40, 754.00, 60, 12],
    ['Engenharia de Software', 'UFJF', 'Juiz de Fora - MG', 'Sudeste', 'Noturno', 'Bacharelado', 755.80, 705.20, 40, 9],
    ['Ciência da Computação', 'UFJF', 'Juiz de Fora - MG', 'Sudeste', 'Integral', 'Bacharelado', 762.10, 712.00, 50, 8],
    ['Direito', 'UFJF', 'Juiz de Fora - MG', 'Sudeste', 'Matutino', 'Bacharelado', 764.50, 714.20, 120, 10],
    ['Odontologia', 'UFJF', 'Juiz de Fora - MG', 'Sudeste', 'Integral', 'Bacharelado', 748.90, 698.50, 60, 10],
    ['Psicologia', 'UFJF', 'Juiz de Fora - MG', 'Sudeste', 'Integral', 'Bacharelado', 745.20, 695.00, 50, 10],
    ['Arquitetura e Urbanismo', 'UFJF', 'Juiz de Fora - MG', 'Sudeste', 'Integral', 'Bacharelado', 748.10, 698.00, 60, 10],

    // --- UFLA (Lavras - MG) ---
    ['Medicina', 'UFLA', 'Lavras - MG', 'Sudeste', 'Integral', 'Bacharelado', 801.40, 758.00, 60, 12],
    ['Engenharia de Software', 'UFLA', 'Lavras - MG', 'Sudeste', 'Integral', 'Bacharelado', 752.10, 702.00, 40, 9],
    ['Ciência da Computação', 'UFLA', 'Lavras - MG', 'Sudeste', 'Integral', 'Bacharelado', 758.90, 708.40, 50, 8],
    ['Medicina Veterinária', 'UFLA', 'Lavras - MG', 'Sudeste', 'Integral', 'Bacharelado', 745.80, 692.50, 60, 10],
    ['Agronomia', 'UFLA', 'Lavras - MG', 'Sudeste', 'Integral', 'Bacharelado', 718.50, 665.20, 120, 10],
    ['Zootecnia', 'UFLA', 'Lavras - MG', 'Sudeste', 'Integral', 'Bacharelado', 682.40, 629.10, 60, 10],
    ['Engenharia Florestal', 'UFLA', 'Lavras - MG', 'Sudeste', 'Integral', 'Bacharelado', 689.10, 635.80, 60, 10],
    ['Engenharia de Alimentos', 'UFLA', 'Lavras - MG', 'Sudeste', 'Integral', 'Bacharelado', 695.20, 642.00, 40, 10]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($batch2Courses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🎉 Carga das 11 Universidades do 2º Lote Concluída com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($batch2Courses) . " novos cursos</strong> abrangendo UNIFAP, UFAM, UFOPA, UFPA, UFT, UFRA, UNIFESSPA, UNIFAL-MG, UNIFEI, UFJF e UFLA!</p>
</div>";
