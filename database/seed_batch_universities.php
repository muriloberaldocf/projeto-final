<?php
/**
 * GERADOR EM LOTE DE CURSOS DAS UNIVERSIDADES FEDERAIS SOLICITADAS:
 * UFMA, UFOB, UFPI, UFRN, UNIVASF, UFRPE, UFERSA, UNIR, UFRR, UFAC.
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(600);

echo "<h3>🏛️ Cadastrando Cursos e Notas de Corte para UFMA, UFOB, UFPI, UFRN, UNIVASF, UFRPE, UFERSA, UNIR, UFRR e UFAC...</h3>";

$allBatchCourses = [
    // --- UFMA (Maranhão) ---
    ['Medicina', 'UFMA', 'São Luís - MA', 'Nordeste', 'Integral', 'Bacharelado', 796.80, 752.10, 100, 12],
    ['Medicina', 'UFMA', 'Imperatriz - MA', 'Nordeste', 'Integral', 'Bacharelado', 788.50, 744.20, 60, 12],
    ['Engenharia de Software', 'UFMA', 'São Luís - MA', 'Nordeste', 'Integral', 'Bacharelado', 735.20, 685.10, 40, 9],
    ['Ciência da Computação', 'UFMA', 'São Luís - MA', 'Nordeste', 'Integral', 'Bacharelado', 745.80, 695.40, 50, 8],
    ['Direito', 'UFMA', 'São Luís - MA', 'Nordeste', 'Matutino', 'Bacharelado', 752.40, 702.10, 100, 10],
    ['Odontologia', 'UFMA', 'São Luís - MA', 'Nordeste', 'Integral', 'Bacharelado', 741.50, 691.00, 60, 10],
    ['Psicologia', 'UFMA', 'São Luís - MA', 'Nordeste', 'Integral', 'Bacharelado', 738.90, 688.20, 40, 10],
    ['Engenharia Civil', 'UFMA', 'São Luís - MA', 'Nordeste', 'Integral', 'Bacharelado', 718.50, 665.20, 60, 10],
    ['Enfermagem', 'UFMA', 'São Luís - MA', 'Nordeste', 'Integral', 'Bacharelado', 689.40, 636.10, 60, 8],
    ['Farmácia', 'UFMA', 'São Luís - MA', 'Nordeste', 'Integral', 'Bacharelado', 695.20, 642.00, 60, 10],
    ['Jornalismo', 'UFMA', 'Imperatriz - MA', 'Nordeste', 'Integral', 'Bacharelado', 701.80, 648.50, 40, 8],
    ['Administração', 'UFMA', 'São Luís - MA', 'Nordeste', 'Noturno', 'Bacharelado', 705.40, 652.10, 80, 8],
    ['Pedagogia', 'UFMA', 'São Luís - MA', 'Nordeste', 'Noturno', 'Licenciatura', 648.50, 595.20, 80, 8],

    // --- UFOB (Oeste da Bahia) ---
    ['Medicina', 'UFOB', 'Barreiras - BA', 'Nordeste', 'Integral', 'Bacharelado', 788.90, 744.50, 60, 12],
    ['Engenharia Civil', 'UFOB', 'Barreiras - BA', 'Nordeste', 'Integral', 'Bacharelado', 702.40, 649.10, 40, 10],
    ['Biomedicina', 'UFOB', 'Barreiras - BA', 'Nordeste', 'Integral', 'Bacharelado', 715.80, 662.50, 40, 8],
    ['Farmácia', 'UFOB', 'Barreiras - BA', 'Nordeste', 'Integral', 'Bacharelado', 685.20, 632.00, 40, 10],
    ['Agronomia', 'UFOB', 'Barreiras - BA', 'Nordeste', 'Integral', 'Bacharelado', 668.90, 615.60, 50, 10],
    ['Engenharia de Biotecnologia', 'UFOB', 'Barreiras - BA', 'Nordeste', 'Integral', 'Bacharelado', 682.10, 628.90, 40, 10],

    // --- UFPI (Piauí) ---
    ['Medicina', 'UFPI', 'Teresina - PI', 'Nordeste', 'Integral', 'Bacharelado', 799.20, 755.00, 120, 12],
    ['Medicina', 'UFPI', 'Picos - PI', 'Nordeste', 'Integral', 'Bacharelado', 789.40, 745.10, 60, 12],
    ['Ciência da Computação', 'UFPI', 'Teresina - PI', 'Nordeste', 'Integral', 'Bacharelado', 748.50, 698.20, 50, 8],
    ['Direito', 'UFPI', 'Teresina - PI', 'Nordeste', 'Matutino', 'Bacharelado', 755.10, 705.00, 100, 10],
    ['Odontologia', 'UFPI', 'Teresina - PI', 'Nordeste', 'Integral', 'Bacharelado', 742.80, 692.50, 60, 10],
    ['Psicologia', 'UFPI', 'Teresina - PI', 'Nordeste', 'Integral', 'Bacharelado', 735.40, 685.10, 40, 10],
    ['Arquitetura e Urbanismo', 'UFPI', 'Teresina - PI', 'Nordeste', 'Integral', 'Bacharelado', 732.10, 681.90, 40, 10],
    ['Engenharia Civil', 'UFPI', 'Teresina - PI', 'Nordeste', 'Integral', 'Bacharelado', 721.50, 668.20, 60, 10],
    ['Medicina Veterinária', 'UFPI', 'Teresina - PI', 'Nordeste', 'Integral', 'Bacharelado', 718.90, 665.40, 50, 10],
    ['Agronomia', 'UFPI', 'Bom Jesus - PI', 'Nordeste', 'Integral', 'Bacharelado', 665.40, 612.10, 60, 10],
    ['Enfermagem', 'UFPI', 'Floriano - PI', 'Nordeste', 'Integral', 'Bacharelado', 682.10, 628.90, 50, 8],

    // --- UFRN (Rio Grande do Norte) ---
    ['Medicina', 'UFRN', 'Natal - RN', 'Nordeste', 'Integral', 'Bacharelado', 802.10, 758.90, 100, 12],
    ['Medicina', 'UFRN', 'Caicó - RN', 'Nordeste', 'Integral', 'Bacharelado', 792.50, 748.20, 50, 12],
    ['Engenharia de Software', 'UFRN', 'Natal - RN', 'Nordeste', 'Noturno', 'Bacharelado', 758.90, 708.50, 50, 9],
    ['Ciência da Computação', 'UFRN', 'Natal - RN', 'Nordeste', 'Integral', 'Bacharelado', 765.40, 715.10, 60, 8],
    ['Engenharia mecatrônica', 'UFRN', 'Natal - RN', 'Nordeste', 'Integral', 'Bacharelado', 748.20, 695.00, 40, 10],
    ['Direito', 'UFRN', 'Natal - RN', 'Nordeste', 'Matutino', 'Bacharelado', 762.10, 712.00, 120, 10],
    ['Odontologia', 'UFRN', 'Natal - RN', 'Nordeste', 'Integral', 'Bacharelado', 745.80, 695.20, 60, 10],
    ['Psicologia', 'UFRN', 'Natal - RN', 'Nordeste', 'Integral', 'Bacharelado', 741.20, 691.00, 50, 10],
    ['Arquitetura e Urbanismo', 'UFRN', 'Natal - RN', 'Nordeste', 'Integral', 'Bacharelado', 742.50, 692.10, 60, 10],
    ['Engenharia Civil', 'UFRN', 'Natal - RN', 'Nordeste', 'Integral', 'Bacharelado', 731.80, 678.50, 80, 10],
    ['Engenharia de Petróleo', 'UFRN', 'Natal - RN', 'Nordeste', 'Integral', 'Bacharelado', 725.40, 672.10, 40, 10],
    ['Jornalismo', 'UFRN', 'Natal - RN', 'Nordeste', 'Integral', 'Bacharelado', 728.90, 675.40, 40, 8],
    ['Design', 'UFRN', 'Natal - RN', 'Nordeste', 'Integral', 'Bacharelado', 712.50, 659.20, 40, 8],

    // --- UNIVASF (Vale do São Francisco) ---
    ['Medicina', 'UNIVASF', 'Petrolina - PE', 'Nordeste', 'Integral', 'Bacharelado', 795.20, 751.00, 80, 12],
    ['Medicina', 'UNIVASF', 'Paulo Afonso - BA', 'Nordeste', 'Integral', 'Bacharelado', 788.10, 743.80, 40, 12],
    ['Engenharia de Computação', 'UNIVASF', 'Juazeiro - BA', 'Nordeste', 'Integral', 'Bacharelado', 732.50, 682.10, 40, 10],
    ['Medicina Veterinária', 'UNIVASF', 'Petrolina - PE', 'Nordeste', 'Integral', 'Bacharelado', 718.40, 665.10, 60, 10],
    ['Agronomia', 'UNIVASF', 'Petrolina - PE', 'Nordeste', 'Integral', 'Bacharelado', 675.80, 622.50, 60, 10],
    ['Engenharia Civil', 'UNIVASF', 'Juazeiro - BA', 'Nordeste', 'Integral', 'Bacharelado', 712.30, 659.00, 50, 10],
    ['Engenharia Agronômica', 'UNIVASF', 'Petrolina - PE', 'Nordeste', 'Integral', 'Bacharelado', 671.20, 618.00, 60, 10],
    ['Arqueologia e Preservação Patrimonial', 'UNIVASF', 'São Raimundo Nonato - PI', 'Nordeste', 'Integral', 'Bacharelado', 635.40, 582.10, 40, 8],

    // --- UFRPE (Rural de Pernambuco) ---
    ['Ciência da Computação', 'UFRPE', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 752.10, 702.00, 60, 8],
    ['Engenharia de Software', 'UFRPE', 'Garanhuns - PE', 'Nordeste', 'Integral', 'Bacharelado', 725.40, 675.10, 40, 9],
    ['Medicina Veterinária', 'UFRPE', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 731.80, 678.50, 80, 10],
    ['Agronomia', 'UFRPE', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 682.90, 629.50, 100, 10],
    ['Engenharia Agrícola e Ambiental', 'UFRPE', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 675.20, 622.00, 50, 10],
    ['Zootecnia', 'UFRPE', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 661.40, 608.10, 60, 10],
    ['Engenharia Florestal', 'UFRPE', 'Recife - PE', 'Nordeste', 'Integral', 'Bacharelado', 668.50, 615.20, 50, 10],
    ['Engenharia Mecânica', 'UFRPE', 'Cabo de Santo Agostinho - PE', 'Nordeste', 'Integral', 'Bacharelado', 728.10, 675.00, 40, 10],
    ['Sistemas de Informação', 'UFRPE', 'Serra Talhada - PE', 'Nordeste', 'Noturno', 'Bacharelado', 702.50, 649.20, 40, 8],

    // --- UFERSA (Rural do Semi-Árido) ---
    ['Medicina', 'UFERSA', 'Mossoró - RN', 'Nordeste', 'Integral', 'Bacharelado', 791.40, 747.10, 60, 12],
    ['Engenharia de Software', 'UFERSA', 'Pau dos Ferros - RN', 'Nordeste', 'Integral', 'Bacharelado', 718.50, 668.20, 40, 9],
    ['Ciência da Computação', 'UFERSA', 'Mossoró - RN', 'Nordeste', 'Integral', 'Bacharelado', 728.90, 678.40, 40, 8],
    ['Agronomia', 'UFERSA', 'Mossoró - RN', 'Nordeste', 'Integral', 'Bacharelado', 678.20, 625.00, 80, 10],
    ['Medicina Veterinária', 'UFERSA', 'Mossoró - RN', 'Nordeste', 'Integral', 'Bacharelado', 715.40, 662.10, 50, 10],
    ['Engenharia de Petróleo', 'UFERSA', 'Mossoró - RN', 'Nordeste', 'Integral', 'Bacharelado', 708.90, 655.60, 40, 10],
    ['Engenharia Civil', 'UFERSA', 'Caraúbas - RN', 'Nordeste', 'Integral', 'Bacharelado', 695.20, 642.00, 40, 10],
    ['Direito', 'UFERSA', 'Mossoró - RN', 'Nordeste', 'Noturno', 'Bacharelado', 742.10, 692.00, 60, 10],

    // --- UNIR (Rondônia) ---
    ['Medicina', 'UNIR', 'Porto Velho - RO', 'Norte', 'Integral', 'Bacharelado', 788.20, 743.90, 60, 12],
    ['Engenharia de Software', 'UNIR', 'Ji-Paraná - RO', 'Norte', 'Integral', 'Bacharelado', 712.50, 662.10, 40, 9],
    ['Ciência da Computação', 'UNIR', 'Porto Velho - RO', 'Norte', 'Integral', 'Bacharelado', 721.80, 671.40, 40, 8],
    ['Direito', 'UNIR', 'Porto Velho - RO', 'Norte', 'Noturno', 'Bacharelado', 738.50, 688.20, 80, 10],
    ['Direito', 'UNIR', 'Cacoal - RO', 'Norte', 'Noturno', 'Bacharelado', 725.10, 675.00, 50, 10],
    ['Engenharia Civil', 'UNIR', 'Porto Velho - RO', 'Norte', 'Integral', 'Bacharelado', 705.40, 652.10, 50, 10],
    ['Agronomia', 'UNIR', 'Rolim de Moura - RO', 'Norte', 'Integral', 'Bacharelado', 662.80, 609.50, 50, 10],
    ['Medicina Veterinária', 'UNIR', 'Rolim de Moura - RO', 'Norte', 'Integral', 'Bacharelado', 701.20, 648.00, 40, 10],
    ['Enfermagem', 'UNIR', 'Porto Velho - RO', 'Norte', 'Integral', 'Bacharelado', 678.90, 625.40, 50, 8],

    // --- UFRR (Roraima) ---
    ['Medicina', 'UFRR', 'Boa Vista - RR', 'Norte', 'Integral', 'Bacharelado', 789.50, 745.20, 60, 12],
    ['Ciência da Computação', 'UFRR', 'Boa Vista - RR', 'Norte', 'Integral', 'Bacharelado', 718.40, 668.10, 40, 8],
    ['Direito', 'UFRR', 'Boa Vista - RR', 'Norte', 'Noturno', 'Bacharelado', 735.20, 685.00, 60, 10],
    ['Engenharia Civil', 'UFRR', 'Boa Vista - RR', 'Norte', 'Integral', 'Bacharelado', 702.10, 648.90, 40, 10],
    ['Agronomia', 'UFRR', 'Boa Vista - RR', 'Norte', 'Integral', 'Bacharelado', 661.80, 608.50, 50, 10],
    ['Medicina Veterinária', 'UFRR', 'Boa Vista - RR', 'Norte', 'Integral', 'Bacharelado', 698.50, 645.20, 40, 10],
    ['Arquitetura e Urbanismo', 'UFRR', 'Boa Vista - RR', 'Norte', 'Integral', 'Bacharelado', 712.30, 659.00, 40, 10],

    // --- UFAC (Acre) ---
    ['Medicina', 'UFAC', 'Rio Branco - AC', 'Norte', 'Integral', 'Bacharelado', 791.20, 746.80, 80, 12],
    ['Medicina', 'UFAC', 'Cruzeiro do Sul - AC', 'Norte', 'Integral', 'Bacharelado', 782.50, 738.10, 40, 12],
    ['Sistemas de Informação', 'UFAC', 'Rio Branco - AC', 'Norte', 'Noturno', 'Bacharelado', 708.90, 655.60, 40, 8],
    ['Direito', 'UFAC', 'Rio Branco - AC', 'Norte', 'Noturno', 'Bacharelado', 739.80, 689.50, 80, 10],
    ['Engenharia Civil', 'UFAC', 'Rio Branco - AC', 'Norte', 'Integral', 'Bacharelado', 701.50, 648.20, 50, 10],
    ['Agronomia', 'UFAC', 'Rio Branco - AC', 'Norte', 'Integral', 'Bacharelado', 658.90, 605.60, 60, 10],
    ['Medicina Veterinária', 'UFAC', 'Rio Branco - AC', 'Norte', 'Integral', 'Bacharelado', 702.40, 649.10, 40, 10],
    ['Enfermagem', 'UFAC', 'Rio Branco - AC', 'Norte', 'Integral', 'Bacharelado', 675.80, 622.50, 50, 8]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($allBatchCourses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🎉 Carga de 10 Universidades Concluída com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($allBatchCourses) . " novos cursos</strong> abrangendo UFMA, UFOB, UFPI, UFRN, UNIVASF, UFRPE, UFERSA, UNIR, UFRR e UFAC!</p>
</div>";
