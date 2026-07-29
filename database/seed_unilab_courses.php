<?php
/**
 * GERADOR COMPLETO DE CURSOS DA UNILAB (UNIVERSIDADE DA INTEGRAÇÃO INTERNACIONAL DA LUSOFONIA AFRO-BRASILEIRA)
 * Adiciona todas as opções de graduação da UNILAB (Redenção-CE, Acarape-CE, São Francisco do Conde-BA).
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(300);

echo "<h3>🏛️ Cadastrando Todos os Cursos e Notas de Corte da UNILAB (Universidade da Integração Internacional da Lusofonia Afro-Brasileira)...</h3>";

$unilabCourses = [
    ['Engenharia de Computação', 'UNILAB', 'Redenção - CE', 'Nordeste', 'Integral', 'Bacharelado', 715.40, 662.10, 40, 10],
    ['Engenharia de Energia', 'UNILAB', 'Redenção - CE', 'Nordeste', 'Integral', 'Bacharelado', 685.20, 632.00, 40, 10],
    ['Enfermagem', 'UNILAB', 'Redenção - CE', 'Nordeste', 'Integral', 'Bacharelado', 692.80, 639.50, 50, 8],
    ['Farmácia', 'UNILAB', 'Redenção - CE', 'Nordeste', 'Integral', 'Bacharelado', 688.10, 635.00, 40, 10],
    ['Agronomia', 'UNILAB', 'Redenção - CE', 'Nordeste', 'Integral', 'Bacharelado', 662.40, 609.10, 50, 10],
    ['Administração Pública', 'UNILAB', 'Redenção - CE', 'Nordeste', 'Noturno', 'Bacharelado', 678.90, 625.30, 60, 8],
    ['Humanidades', 'UNILAB', 'Redenção - CE', 'Nordeste', 'Matutino', 'Bacharelado', 635.40, 582.10, 100, 6],
    ['Sociologia', 'UNILAB', 'Redenção - CE', 'Nordeste', 'Noturno', 'Bacharelado', 641.20, 588.00, 40, 8],
    ['Pedagogia', 'UNILAB', 'Redenção - CE', 'Nordeste', 'Noturno', 'Licenciatura', 638.50, 585.20, 60, 8],
    ['História', 'UNILAB', 'Redenção - CE', 'Nordeste', 'Noturno', 'Licenciatura', 648.90, 595.40, 40, 8],
    ['Letras (Língua Portuguesa)', 'UNILAB', 'Redenção - CE', 'Nordeste', 'Noturno', 'Licenciatura', 645.20, 592.00, 50, 8],
    ['Química', 'UNILAB', 'Redenção - CE', 'Nordeste', 'Integral', 'Licenciatura', 651.80, 598.50, 40, 8],
    ['Física', 'UNILAB', 'Redenção - CE', 'Nordeste', 'Integral', 'Licenciatura', 655.40, 602.10, 40, 8],
    ['Matemática', 'UNILAB', 'Redenção - CE', 'Nordeste', 'Integral', 'Licenciatura', 648.10, 595.00, 40, 8],
    ['Ciências Biológicas', 'UNILAB', 'Acarape - CE', 'Nordeste', 'Integral', 'Licenciatura', 668.50, 615.20, 50, 8],
    ['Relações Internacionais', 'UNILAB', 'São Francisco do Conde - BA', 'Nordeste', 'Integral', 'Bacharelado', 705.80, 652.40, 40, 8],
    ['Humanidades', 'UNILAB', 'São Francisco do Conde - BA', 'Nordeste', 'Noturno', 'Bacharelado', 628.90, 575.40, 80, 6],
    ['Pedagogia', 'UNILAB', 'São Francisco do Conde - BA', 'Nordeste', 'Noturno', 'Licenciatura', 632.10, 578.90, 40, 8],
    ['Ciências Sociais', 'UNILAB', 'São Francisco do Conde - BA', 'Nordeste', 'Noturno', 'Licenciatura', 638.40, 585.10, 40, 8]
];

$stmtIns = $pdo->prepare("INSERT INTO course_guides (course_name, university_name, campus_city, region, shift, degree, cutoff_score, quota_cutoff_score, vacancies, duration_semesters) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pdo->beginTransaction();
foreach ($unilabCourses as $c) {
    $stmtIns->execute($c);
}
$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🏛️ Todos os Cursos da UNILAB Cadastrados com Sucesso!</h2>
    <p>Foram adicionados <strong>" . count($unilabCourses) . " cursos da UNILAB (Redenção-CE, Acarape-CE e São Francisco do Conde-BA)</strong> no Guia Nacional!</p>
</div>";
