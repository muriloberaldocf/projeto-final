<?php
/**
 * GERADOR EM LOTE 3 DE CURSOS DAS UNIVERSIDADES FEDERAIS SOLICITADAS:
 * UFOP, UFSCar, UFSJ, UNIFESP, UFU, UFV, UFABC, UNIRIO.
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(600);

echo "<h3>🏛️ Cadastrando Cursos e Notas de Corte para UFOP, UFSCar, UFSJ, UNIFESP, UFU, UFV, UFABC e UNIRIO...</h3>";

$batch3Courses = [
    // --- UFOP (Ouro Preto - MG) ---
    ['Medicina', 'UFOP', 'Ouro Preto - MG', 'Sudeste', 'Integral', 'Bacharelado', 803.40, 759.80, 80, 12],
    ['Engenharia de Software', 'UFOP', 'João Monlevade - MG', 'Sudeste', 'Integral', 'Bacharelado', 742.10, 692.00, 40, 9],
    ['Ciência da Computação', 'UFOP', 'Ouro Preto - MG', 'Sudeste', 'Integral', 'Bacharelado', 748.50, 698.20, 50, 8],
    ['Direito', 'UFOP', 'Ouro Preto - MG', 'Sudeste', 'Matutino', 'Bacharelado', 758.90, 708.50, 80, 10],
    ['Engenharia de Minas', 'UFOP', 'Ouro Preto - MG', 'Sudeste', 'Integral', 'Bacharelado', 712.50, 659.20, 60, 10],
    ['Engenharia Civil', 'UFOP', 'Ouro Preto - MG', 'Sudeste', 'Integral', 'Bacharelado', 725.40, 672.10, 60, 10],
    ['Farmácia', 'UFOP', 'Ouro Preto - MG', 'Sudeste', 'Integral', 'Bacharelado', 715.80, 662.40, 60, 10],
    ['Arquitetura e Urbanismo', 'UFOP', 'Ouro Preto - MG', 'Sudeste', 'Integral', 'Bacharelado', 738.20, 688.00, 40, 10],

    // --- UFSCar (São Carlos - SP) ---
    ['Medicina', 'UFSCar', 'São Carlos - SP', 'Sudeste', 'Integral', 'Bacharelado', 811.20, 768.40, 60, 12],
    ['Engenharia de Computação', 'UFSCar', 'São Carlos - SP', 'Sudeste', 'Integral', 'Bacharelado', 785.40, 735.10, 60, 10],
    ['Ciência da Computação', 'UFSCar', 'São Carlos - SP', 'Sudeste', 'Integral', 'Bacharelado', 778.90, 728.50, 60, 8],
    ['Engenharia de Software', 'UFSCar', 'São Carlos - SP', 'Sudeste', 'Integral', 'Bacharelado', 768.20, 718.00, 40, 9],
    ['Ciência da Computação', 'UFSCar', 'Sorocaba - SP', 'Sudeste', 'Integral', 'Bacharelado', 755.40, 705.10, 50, 8],
    ['Psicologia', 'UFSCar', 'São Carlos - SP', 'Sudeste', 'Integral', 'Bacharelado', 758.90, 708.20, 40, 10],
    ['Engenharia Mecânica', 'UFSCar', 'São Carlos - SP', 'Sudeste', 'Integral', 'Bacharelado', 762.50, 712.10, 60, 10],
    ['Engenharia de Produção', 'UFSCar', 'São Carlos - SP', 'Sudeste', 'Integral', 'Bacharelado', 758.20, 705.00, 60, 10],
    ['Engenharia Civil', 'UFSCar', 'São Carlos - SP', 'Sudeste', 'Integral', 'Bacharelado', 748.50, 695.20, 60, 10],
    ['Biotecnologia', 'UFSCar', 'São Carlos - SP', 'Sudeste', 'Integral', 'Bacharelado', 732.10, 678.90, 40, 8],

    // --- UFSJ (São João del-Rei - MG) ---
    ['Medicina', 'UFSJ', 'Divinópolis - MG', 'Sudeste', 'Integral', 'Bacharelado', 801.50, 758.20, 60, 12],
    ['Medicina', 'UFSJ', 'São João del-Rei - MG', 'Sudeste', 'Integral', 'Bacharelado', 798.90, 755.00, 60, 12],
    ['Ciência da Computação', 'UFSJ', 'São João del-Rei - MG', 'Sudeste', 'Integral', 'Bacharelado', 738.50, 688.10, 40, 8],
    ['Engenharia de Software', 'UFSJ', 'Ouro Branco - MG', 'Sudeste', 'Integral', 'Bacharelado', 728.90, 678.40, 40, 9],
    ['Direito', 'UFSJ', 'São João del-Rei - MG', 'Sudeste', 'Noturno', 'Bacharelado', 748.20, 698.00, 60, 10],
    ['Psicologia', 'UFSJ', 'São João del-Rei - MG', 'Sudeste', 'Integral', 'Bacharelado', 735.40, 685.10, 40, 10],
    ['Engenharia Mecânica', 'UFSJ', 'São João del-Rei - MG', 'Sudeste', 'Integral', 'Bacharelado', 725.10, 672.00, 50, 10],

    // --- UNIFESP (São Paulo - SP) ---
    ['Medicina', 'UNIFESP', 'São Paulo (EPM) - SP', 'Sudeste', 'Integral', 'Bacharelado', 814.80, 771.50, 120, 12],
    ['Biomedicina', 'UNIFESP', 'São Paulo - SP', 'Sudeste', 'Integral', 'Bacharelado', 758.40, 708.10, 40, 8],
    ['Ciência da Computação', 'UNIFESP', 'São José dos Campos - SP', 'Sudeste', 'Integral', 'Bacharelado', 772.50, 722.10, 50, 8],
    ['Engenharia da Computação', 'UNIFESP', 'São José dos Campos - SP', 'Sudeste', 'Integral', 'Bacharelado', 768.90, 718.50, 50, 10],
    ['Psicologia', 'UNIFESP', 'Santos - SP', 'Sudeste', 'Integral', 'Bacharelado', 755.20, 705.00, 50, 10],
    ['Relações Internacionais', 'UNIFESP', 'Osasco - SP', 'Sudeste', 'Integral', 'Bacharelado', 752.40, 698.90, 50, 8],
    ['Enfermagem', 'UNIFESP', 'São Paulo - SP', 'Sudeste', 'Integral', 'Bacharelado', 718.50, 665.20, 80, 8],
    ['Farmácia', 'UNIFESP', 'Diadema - SP', 'Sudeste', 'Integral', 'Bacharelado', 728.10, 675.00, 80, 10],

    // --- UFU (Uberlândia - MG) ---
    ['Medicina', 'UFU', 'Uberlândia - MG', 'Sudeste', 'Integral', 'Bacharelado', 808.50, 765.20, 80, 12],
    ['Engenharia de Software', 'UFU', 'Uberlândia - MG', 'Sudeste', 'Integral', 'Bacharelado', 752.40, 702.10, 40, 9],
    ['Ciência da Computação', 'UFU', 'Uberlândia - MG', 'Sudeste', 'Integral', 'Bacharelado', 761.80, 711.50, 60, 8],
    ['Direito', 'UFU', 'Uberlândia - MG', 'Sudeste', 'Matutino', 'Bacharelado', 765.20, 715.00, 100, 10],
    ['Odontologia', 'UFU', 'Uberlândia - MG', 'Sudeste', 'Integral', 'Bacharelado', 748.90, 698.50, 60, 10],
    ['Psicologia', 'UFU', 'Uberlândia - MG', 'Sudeste', 'Integral', 'Bacharelado', 745.10, 695.00, 50, 10],
    ['Arquitetura e Urbanismo', 'UFU', 'Uberlândia - MG', 'Sudeste', 'Integral', 'Bacharelado', 742.50, 692.10, 50, 10],
    ['Engenharia Mecânica', 'UFU', 'Uberlândia - MG', 'Sudeste', 'Integral', 'Bacharelado', 748.20, 695.10, 80, 10],
    ['Engenharia Civil', 'UFU', 'Uberlândia - MG', 'Sudeste', 'Integral', 'Bacharelado', 735.40, 682.10, 80, 10],
    ['Medicina Veterinária', 'UFU', 'Uberlândia - MG', 'Sudeste', 'Integral', 'Bacharelado', 732.10, 678.90, 60, 10],

    // --- UFV (Viçosa - MG) ---
    ['Medicina', 'UFV', 'Viçosa - MG', 'Sudeste', 'Integral', 'Bacharelado', 806.90, 763.50, 50, 12],
    ['Engenharia de Software', 'UFV', 'Florestal - MG', 'Sudeste', 'Integral', 'Bacharelado', 745.80, 695.20, 40, 9],
    ['Ciência da Computação', 'UFV', 'Viçosa - MG', 'Sudeste', 'Integral', 'Bacharelado', 758.40, 708.10, 50, 8],
    ['Agronomia', 'UFV', 'Viçosa - MG', 'Sudeste', 'Integral', 'Bacharelado', 725.40, 672.10, 150, 10],
    ['Medicina Veterinária', 'UFV', 'Viçosa - MG', 'Sudeste', 'Integral', 'Bacharelado', 748.20, 695.00, 60, 10],
    ['Engenharia Florestal', 'UFV', 'Viçosa - MG', 'Sudeste', 'Integral', 'Bacharelado', 698.50, 645.20, 80, 10],
    ['Zootecnia', 'UFV', 'Viçosa - MG', 'Sudeste', 'Integral', 'Bacharelado', 685.20, 632.00, 60, 10],
    ['Engenharia Mecânica', 'UFV', 'Viçosa - MG', 'Sudeste', 'Integral', 'Bacharelado', 742.10, 689.00, 50, 10],
    ['Engenharia Civil', 'UFV', 'Viçosa - MG', 'Sudeste', 'Integral', 'Bacharelado', 735.80, 682.50, 60, 10],

    // --- UFABC (Santo André - SP) ---
    ['Bacharelado em Ciência e Tecnologia (BC&T)', 'UFABC', 'Santo André - SP', 'Sudeste', 'Noturno', 'Bacharelado', 758.90, 708.50, 300, 6],
    ['Bacharelado em Ciência e Tecnologia (BC&T)', 'UFABC', 'São Bernardo do Campo - SP', 'Sudeste', 'Matutino', 'Bacharelado', 762.40, 712.10, 300, 6],
    ['Engenharia de Informação / Computação', 'UFABC', 'Santo André - SP', 'Sudeste', 'Noturno', 'Bacharelado', 768.50, 718.20, 60, 10],
    ['Engenharia Aeroespacial', 'UFABC', 'São Bernardo do Campo - SP', 'Sudeste', 'Integral', 'Bacharelado', 781.20, 731.00, 60, 10],
    ['Neurociência', 'UFABC', 'São Bernardo do Campo - SP', 'Sudeste', 'Integral', 'Bacharelado', 755.40, 705.10, 40, 8],
    ['Relações Internacionais', 'UFABC', 'São Bernardo do Campo - SP', 'Sudeste', 'Noturno', 'Bacharelado', 748.90, 695.80, 50, 8],

    // --- UNIRIO (Rio de Janeiro - RJ) ---
    ['Medicina', 'UNIRIO', 'Rio de Janeiro (Urca) - RJ', 'Sudeste', 'Integral', 'Bacharelado', 807.50, 764.10, 80, 12],
    ['Sistemas de Informação', 'UNIRIO', 'Rio de Janeiro (Urca) - RJ', 'Sudeste', 'Noturno', 'Bacharelado', 748.20, 698.00, 50, 8],
    ['Direito', 'UNIRIO', 'Rio de Janeiro (Urca) - RJ', 'Sudeste', 'Matutino', 'Bacharelado', 762.90, 712.50, 100, 10],
    ['Psicologia', 'UNIRIO', 'Rio de Janeiro (Urca) - RJ', 'Sudeste', 'Integral', 'Bacharelado', 748.50, 698.10, 40, 10],
    ['Enfermagem', 'UNIRIO', 'Rio de Janeiro (Urca) - RJ', 'Sudeste', 'Integral', 'Bacharelado', 705.40, 652.10, 60, 8],
    ['Biomedicina', 'UNIRIO', 'Rio de Janeiro (Urca) - RJ', 'Sudeste', 'Integral', 'Bacharelado', 742.10, 689.00, 40, 8],
    ['Teatro / Artes Cênicas', 'UNIRIO', 'Rio de Janeiro (Urca) - RJ', 'Sudeste', 'Integral', 'Bacharelado', 712.50, 659.20, 30, 8],
    ['Música', 'UNIRIO', 'Rio de Janeiro (Urca) - RJ', 'Sudeste', 'Integral', 'Bacharelado', 708.90, 655.60, 30, 8]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($batch3Courses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🎉 Carga das 8 Universidades do 3º Lote Concluída com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($batch3Courses) . " novos cursos</strong> abrangendo UFOP, UFSCar, UFSJ, UNIFESP, UFU, UFV, UFABC e UNIRIO!</p>
</div>";
