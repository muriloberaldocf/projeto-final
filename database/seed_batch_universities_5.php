<?php
/**
 * GERADOR EM LOTE 5 DE CURSOS DAS UNIVERSIDADES FEDERAIS SOLICITADAS:
 * UFPel, UFSM, UNIPAMPA, UFPR, FURG, UFAPE, UFDPar, UFNT.
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(600);

echo "<h3>🏛️ Cadastrando Cursos e Notas de Corte para UFPel, UFSM, UNIPAMPA, UFPR, FURG, UFAPE, UFDPar e UFNT...</h3>";

$batch5Courses = [
    // --- UFPel (Pelotas - RS) ---
    ['Medicina', 'UFPel', 'Pelotas - RS', 'Sul', 'Integral', 'Bacharelado', 801.20, 758.00, 90, 12],
    ['Engenharia de Software', 'UFPel', 'Pelotas - RS', 'Sul', 'Noturno', 'Bacharelado', 735.40, 685.10, 40, 9],
    ['Ciência da Computação', 'UFPel', 'Pelotas - RS', 'Sul', 'Integral', 'Bacharelado', 742.80, 692.50, 50, 8],
    ['Direito', 'UFPel', 'Pelotas - RS', 'Sul', 'Noturno', 'Bacharelado', 748.90, 698.20, 80, 10],
    ['Odontologia', 'UFPel', 'Pelotas - RS', 'Sul', 'Integral', 'Bacharelado', 745.20, 695.00, 60, 10],
    ['Medicina Veterinária', 'UFPel', 'Pelotas - RS', 'Sul', 'Integral', 'Bacharelado', 732.10, 678.90, 80, 10],
    ['Agronomia', 'UFPel', 'Pelotas - RS', 'Sul', 'Integral', 'Bacharelado', 685.40, 632.10, 100, 10],
    ['Arquitetura e Urbanismo', 'UFPel', 'Pelotas - RS', 'Sul', 'Integral', 'Bacharelado', 735.80, 682.40, 50, 10],
    ['Psicologia', 'UFPel', 'Pelotas - RS', 'Sul', 'Integral', 'Bacharelado', 738.90, 688.50, 40, 10],

    // --- UFSM (Santa Maria - RS) ---
    ['Medicina', 'UFSM', 'Santa Maria - RS', 'Sul', 'Integral', 'Bacharelado', 805.80, 762.40, 100, 12],
    ['Engenharia de Software', 'UFSM', 'Santa Maria - RS', 'Sul', 'Integral', 'Bacharelado', 748.50, 698.10, 40, 9],
    ['Ciência da Computação', 'UFSM', 'Santa Maria - RS', 'Sul', 'Integral', 'Bacharelado', 758.20, 708.00, 60, 8],
    ['Direito', 'UFSM', 'Santa Maria - RS', 'Sul', 'Matutino', 'Bacharelado', 755.40, 705.10, 80, 10],
    ['Odontologia', 'UFSM', 'Santa Maria - RS', 'Sul', 'Integral', 'Bacharelado', 748.10, 698.00, 50, 10],
    ['Medicina Veterinária', 'UFSM', 'Santa Maria - RS', 'Sul', 'Integral', 'Bacharelado', 738.90, 685.40, 80, 10],
    ['Agronomia', 'UFSM', 'Santa Maria - RS', 'Sul', 'Integral', 'Bacharelado', 698.20, 645.00, 120, 10],
    ['Engenharia Mecânica', 'UFSM', 'Santa Maria - RS', 'Sul', 'Integral', 'Bacharelado', 745.20, 692.00, 80, 10],
    ['Engenharia Civil', 'UFSM', 'Santa Maria - RS', 'Sul', 'Integral', 'Bacharelado', 732.50, 679.10, 80, 10],

    // --- UNIPAMPA (Pampa - RS) ---
    ['Medicina', 'UNIPAMPA', 'Uruguaiana - RS', 'Sul', 'Integral', 'Bacharelado', 791.50, 747.20, 60, 12],
    ['Engenharia de Software', 'UNIPAMPA', 'Alegrete - RS', 'Sul', 'Integral', 'Bacharelado', 715.40, 665.10, 40, 9],
    ['Ciência da Computação', 'UNIPAMPA', 'Alegrete - RS', 'Sul', 'Integral', 'Bacharelado', 721.80, 671.50, 40, 8],
    ['Medicina Veterinária', 'UNIPAMPA', 'Uruguaiana - RS', 'Sul', 'Integral', 'Bacharelado', 712.30, 659.00, 50, 10],
    ['Agronomia', 'UNIPAMPA', 'São Gabriel - RS', 'Sul', 'Integral', 'Bacharelado', 668.50, 615.20, 60, 10],
    ['Relações Internacionais', 'UNIPAMPA', 'Santana do Livramento - RS', 'Sul', 'Integral', 'Bacharelado', 708.90, 655.40, 40, 8],
    ['Engenharia Civil', 'UNIPAMPA', 'Alegrete - RS', 'Sul', 'Integral', 'Bacharelado', 695.20, 642.00, 50, 10],

    // --- UFPR (Paraná) ---
    ['Medicina', 'UFPR', 'Curitiba - PR', 'Sul', 'Integral', 'Bacharelado', 812.90, 769.50, 150, 12],
    ['Medicina', 'UFPR', 'Toledo - PR', 'Sul', 'Integral', 'Bacharelado', 798.20, 754.00, 60, 12],
    ['Engenharia de Software', 'UFPR', 'Curitiba - PR', 'Sul', 'Noturno', 'Bacharelado', 762.50, 712.10, 40, 9],
    ['Ciência da Computação', 'UFPR', 'Curitiba - PR', 'Sul', 'Integral', 'Bacharelado', 772.40, 722.00, 80, 8],
    ['Direito', 'UFPR', 'Curitiba - PR', 'Sul', 'Matutino', 'Bacharelado', 769.80, 719.50, 200, 10],
    ['Odontologia', 'UFPR', 'Curitiba - PR', 'Sul', 'Integral', 'Bacharelado', 752.10, 702.00, 80, 10],
    ['Psicologia', 'UFPR', 'Curitiba - PR', 'Sul', 'Integral', 'Bacharelado', 755.80, 705.20, 60, 10],
    ['Arquitetura e Urbanismo', 'UFPR', 'Curitiba - PR', 'Sul', 'Integral', 'Bacharelado', 758.90, 708.50, 60, 10],
    ['Medicina Veterinária', 'UFPR', 'Curitiba - PR', 'Sul', 'Integral', 'Bacharelado', 742.50, 689.20, 80, 10],
    ['Medicina Veterinária', 'UFPR', 'Palotina - PR', 'Sul', 'Integral', 'Bacharelado', 728.90, 675.40, 60, 10],

    // --- FURG (Rio Grande - RS) ---
    ['Medicina', 'FURG', 'Rio Grande - RS', 'Sul', 'Integral', 'Bacharelado', 798.40, 754.10, 70, 12],
    ['Engenharia de Computação', 'FURG', 'Rio Grande - RS', 'Sul', 'Integral', 'Bacharelado', 738.50, 688.20, 40, 10],
    ['Sistemas de Informação', 'FURG', 'Rio Grande - RS', 'Sul', 'Noturno', 'Bacharelado', 721.40, 671.00, 40, 8],
    ['Direito', 'FURG', 'Rio Grande - RS', 'Sul', 'Noturno', 'Bacharelado', 742.10, 692.00, 80, 10],
    ['Oceanografia', 'FURG', 'Rio Grande - RS', 'Sul', 'Integral', 'Bacharelado', 689.50, 636.20, 40, 10],
    ['Engenharia Mecânica', 'FURG', 'Rio Grande - RS', 'Sul', 'Integral', 'Bacharelado', 725.80, 672.50, 60, 10],
    ['Engenharia Civil', 'FURG', 'Rio Grande - RS', 'Sul', 'Integral', 'Bacharelado', 718.20, 665.00, 60, 10],

    // --- UFAPE (Agreste de Pernambuco) ---
    ['Medicina Veterinária', 'UFAPE', 'Garanhuns - PE', 'Nordeste', 'Integral', 'Bacharelado', 715.40, 662.10, 60, 10],
    ['Agronomia', 'UFAPE', 'Garanhuns - PE', 'Nordeste', 'Integral', 'Bacharelado', 668.90, 615.60, 80, 10],
    ['Engenharia de Alimentos', 'UFAPE', 'Garanhuns - PE', 'Nordeste', 'Integral', 'Bacharelado', 655.20, 602.00, 40, 10],
    ['Zootecnia', 'UFAPE', 'Garanhuns - PE', 'Nordeste', 'Integral', 'Bacharelado', 648.50, 595.20, 50, 10],
    ['Ciência da Computação', 'UFAPE', 'Garanhuns - PE', 'Nordeste', 'Integral', 'Bacharelado', 728.90, 678.50, 40, 8],

    // --- UFDPar (Delta do Parnaíba - PI) ---
    ['Medicina', 'UFDPar', 'Parnaíba - PI', 'Nordeste', 'Integral', 'Bacharelado', 791.20, 746.90, 60, 12],
    ['Psicologia', 'UFDPar', 'Parnaíba - PI', 'Nordeste', 'Integral', 'Bacharelado', 725.40, 675.10, 40, 10],
    ['Biomedicina', 'UFDPar', 'Parnaíba - PI', 'Nordeste', 'Integral', 'Bacharelado', 718.90, 665.50, 40, 8],
    ['Enfermagem', 'UFDPar', 'Parnaíba - PI', 'Nordeste', 'Integral', 'Bacharelado', 682.40, 629.10, 50, 8],
    ['Fisioterapia', 'UFDPar', 'Parnaíba - PI', 'Nordeste', 'Integral', 'Bacharelado', 698.50, 645.20, 40, 10],

    // --- UFNT (Norte do Tocantins) ---
    ['Medicina', 'UFNT', 'Araguaína - TO', 'Norte', 'Integral', 'Bacharelado', 788.50, 744.20, 60, 12],
    ['Medicina Veterinária', 'UFNT', 'Araguaína - TO', 'Norte', 'Integral', 'Bacharelado', 705.80, 652.40, 50, 10],
    ['Zootecnia', 'UFNT', 'Araguaína - TO', 'Norte', 'Integral', 'Bacharelado', 648.90, 595.60, 40, 10],
    ['Direito', 'UFNT', 'Tocantinópolis - TO', 'Norte', 'Noturno', 'Bacharelado', 728.40, 678.10, 60, 10]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($batch5Courses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🎉 Carga das 8 Universidades do 5º Lote Concluída com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($batch5Courses) . " novos cursos</strong> abrangendo UFPel, UFSM, UNIPAMPA, UFPR, FURG, UFAPE, UFDPar e UFNT!</p>
</div>";
