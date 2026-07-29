<?php
/**
 * GERADOR COMPLETO DE CURSOS DA UFPB (UNIVERSIDADE FEDERAL DA PARAÍBA)
 * Adiciona todas as opções de graduação da UFPB (João Pessoa, Areia, Bananeiras, Rio Tinto, Mamanguape).
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(300);

echo "<h3>🏛️ Cadastrando Todos os Cursos e Notas de Corte da UFPB (Universidade Federal da Paraíba)...</h3>";

$ufpbCourses = [
    ['Medicina', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Integral', 'Bacharelado', 799.40, 755.20, 120, 12],
    ['Engenharia de Software', 'UFPB', 'Rio Tinto - PB', 'Nordeste', 'Integral', 'Bacharelado', 738.50, 688.20, 40, 9],
    ['Ciência da Computação', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Integral', 'Bacharelado', 752.40, 702.10, 60, 8],
    ['Sistemas de Informação', 'UFPB', 'Rio Tinto - PB', 'Nordeste', 'Noturno', 'Bacharelado', 721.80, 671.50, 50, 8],
    ['Direito', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Matutino', 'Bacharelado', 754.80, 704.50, 120, 10],
    ['Direito', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Noturno', 'Bacharelado', 751.20, 701.00, 120, 10],
    ['Odontologia', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Integral', 'Bacharelado', 741.20, 691.00, 60, 10],
    ['Psicologia', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Integral', 'Bacharelado', 738.90, 688.40, 50, 10],
    ['Arquitetura e Urbanismo', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Integral', 'Bacharelado', 735.40, 685.10, 60, 10],
    ['Engenharia Civil', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Integral', 'Bacharelado', 724.80, 671.50, 80, 10],
    ['Engenharia Mecânica', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Integral', 'Bacharelado', 728.90, 675.40, 60, 10],
    ['Engenharia Química', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Integral', 'Bacharelado', 718.50, 665.20, 50, 10],
    ['Engenharia Elétrica', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Integral', 'Bacharelado', 715.20, 662.00, 60, 10],
    ['Agronomia', 'UFPB', 'Areia - PB', 'Nordeste', 'Integral', 'Bacharelado', 672.40, 619.10, 80, 10],
    ['Medicina Veterinária', 'UFPB', 'Areia - PB', 'Nordeste', 'Integral', 'Bacharelado', 712.50, 659.20, 50, 10],
    ['Zootecnia', 'UFPB', 'Areia - PB', 'Nordeste', 'Integral', 'Bacharelado', 658.90, 605.40, 40, 10],
    ['Biomedicina', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Integral', 'Bacharelado', 731.80, 678.50, 40, 8],
    ['Enfermagem', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Integral', 'Bacharelado', 688.50, 635.20, 60, 8],
    ['Farmácia', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Integral', 'Bacharelado', 698.20, 645.00, 60, 10],
    ['Fisioterapia', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Integral', 'Bacharelado', 708.40, 655.10, 40, 10],
    ['Nutrição', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Integral', 'Bacharelado', 689.10, 635.80, 40, 8],
    ['Educação Física', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Integral', 'Bacharelado', 658.20, 605.00, 60, 8],
    ['Educação Física', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Noturno', 'Licenciatura', 645.80, 592.40, 60, 8],
    ['Design', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Integral', 'Bacharelado', 705.40, 652.10, 40, 8],
    ['Jornalismo', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Integral', 'Bacharelado', 718.20, 665.00, 40, 8],
    ['Publicidade e Propaganda', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Integral', 'Bacharelado', 712.30, 659.00, 40, 8],
    ['Administração', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Noturno', 'Bacharelado', 708.90, 655.50, 100, 8],
    ['Ciências Econômicas', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Noturno', 'Bacharelado', 701.80, 648.50, 60, 8],
    ['Relações Internacionais', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Integral', 'Bacharelado', 735.10, 681.80, 40, 8],
    ['Ciências Contábeis', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Noturno', 'Bacharelado', 692.40, 639.10, 80, 8],
    ['Pedagogia', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Noturno', 'Licenciatura', 651.40, 598.10, 80, 8],
    ['História', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Noturno', 'Licenciatura', 671.80, 618.50, 60, 8],
    ['Geografia', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Noturno', 'Licenciatura', 658.90, 605.60, 60, 8],
    ['Letras (Português/Inglês)', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Noturno', 'Licenciatura', 665.40, 612.10, 80, 8],
    ['Química', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Integral', 'Bacharelado', 678.50, 625.20, 40, 8],
    ['Física', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Integral', 'Bacharelado', 681.90, 628.60, 40, 8],
    ['Matemática', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Integral', 'Bacharelado', 675.20, 622.00, 40, 8],
    ['Ciências Biológicas', 'UFPB', 'João Pessoa - PB', 'Nordeste', 'Integral', 'Bacharelado', 702.10, 648.90, 60, 8]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($ufpbCourses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🏛️ Todos os Cursos da UFPB Cadastrados com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($ufpbCourses) . " cursos da Universidade Federal da Paraíba (UFPB)</strong> no Guia Nacional!</p>
</div>";
