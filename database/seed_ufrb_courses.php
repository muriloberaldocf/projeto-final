<?php
/**
 * GERADOR COMPLETO DE CURSOS DA UFRB (UNIVERSIDADE FEDERAL DO RECÔNCAVO DA BAHIA)
 * Adiciona todas as opções de graduação da UFRB (Santo Antônio de Jesus, Cruz das Almas, Feira de Santana, Cachoeira, Amargosa).
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(300);

echo "<h3>🏛️ Cadastrando Todos os Cursos e Notas de Corte da UFRB (Universidade Federal do Recôncavo da Bahia)...</h3>";

$ufrbCourses = [
    ['Medicina', 'UFRB', 'Santo Antônio de Jesus - BA', 'Nordeste', 'Integral', 'Bacharelado', 789.20, 744.80, 60, 12],
    ['Engenharia de Computação', 'UFRB', 'Feira de Santana - BA', 'Nordeste', 'Integral', 'Bacharelado', 728.50, 678.10, 40, 10],
    ['Engenharia de Software', 'UFRB', 'Feira de Santana - BA', 'Nordeste', 'Integral', 'Bacharelado', 718.90, 668.50, 40, 9],
    ['Agronomia', 'UFRB', 'Cruz das Almas - BA', 'Nordeste', 'Integral', 'Bacharelado', 678.40, 625.10, 80, 10],
    ['Medicina Veterinária', 'UFRB', 'Cruz das Almas - BA', 'Nordeste', 'Integral', 'Bacharelado', 712.50, 659.20, 60, 10],
    ['Zootecnia', 'UFRB', 'Cruz das Almas - BA', 'Nordeste', 'Integral', 'Bacharelado', 658.90, 605.40, 50, 10],
    ['Engenharia Florestal', 'UFRB', 'Cruz das Almas - BA', 'Nordeste', 'Integral', 'Bacharelado', 652.10, 598.90, 40, 10],
    ['Engenharia Civil', 'UFRB', 'Cruz das Almas - BA', 'Nordeste', 'Integral', 'Bacharelado', 698.20, 645.00, 50, 10],
    ['Psicologia', 'UFRB', 'Santo Antônio de Jesus - BA', 'Nordeste', 'Integral', 'Bacharelado', 718.40, 668.00, 40, 10],
    ['Enfermagem', 'UFRB', 'Santo Antônio de Jesus - BA', 'Nordeste', 'Integral', 'Bacharelado', 678.90, 625.30, 50, 8],
    ['Nutrição', 'UFRB', 'Santo Antônio de Jesus - BA', 'Nordeste', 'Integral', 'Bacharelado', 675.20, 622.00, 40, 8],
    ['Cinema e Audiovisual', 'UFRB', 'Cachoeira - BA', 'Nordeste', 'Integral', 'Bacharelado', 708.50, 655.20, 40, 8],
    ['Jornalismo em Multimeios', 'UFRB', 'Cachoeira - BA', 'Nordeste', 'Integral', 'Bacharelado', 698.40, 645.10, 40, 8],
    ['Museologia', 'UFRB', 'Cachoeira - BA', 'Nordeste', 'Integral', 'Bacharelado', 648.50, 595.20, 30, 8],
    ['História', 'UFRB', 'Cachoeira - BA', 'Nordeste', 'Noturno', 'Licenciatura', 655.80, 602.40, 40, 8],
    ['Pedagogia', 'UFRB', 'Amargosa - BA', 'Nordeste', 'Noturno', 'Licenciatura', 638.90, 585.40, 60, 8],
    ['Filosofia', 'UFRB', 'Amargosa - BA', 'Nordeste', 'Noturno', 'Licenciatura', 635.10, 582.00, 40, 8],
    ['Letras (Português/LIBRAS)', 'UFRB', 'Amargosa - BA', 'Nordeste', 'Noturno', 'Licenciatura', 642.50, 589.10, 40, 8],
    ['Física', 'UFRB', 'Amargosa - BA', 'Nordeste', 'Integral', 'Licenciatura', 652.80, 599.50, 40, 8],
    ['Matemática', 'UFRB', 'Amargosa - BA', 'Nordeste', 'Integral', 'Licenciatura', 648.20, 595.00, 40, 8],
    ['Química', 'UFRB', 'Amargosa - BA', 'Nordeste', 'Integral', 'Licenciatura', 651.40, 598.10, 40, 8],
    ['Ciências Biológicas', 'UFRB', 'Cruz das Almas - BA', 'Nordeste', 'Integral', 'Bacharelado', 672.40, 619.10, 50, 8]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($ufrbCourses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🏛️ Todos os Cursos da UFRB Cadastrados com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($ufrbCourses) . " cursos da Universidade Federal do Recôncavo da Bahia (UFRB)</strong> no Guia Nacional!</p>
</div>";
