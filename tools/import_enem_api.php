<?php
/**
 * IMPORTADOR DE QUESTÕES DO ENEM (ENEM-API / YUNGER7)
 * Lê os arquivos JSON estruturados do repositório enem-api e insere no banco vestilingo
 */

require_once __DIR__ . '/../config/db.php';

header('Content-Type: text/plain; charset=utf-8');

$enemApiDir = sys_get_temp_dir() . '/enem-api/public';

if (!is_dir($enemApiDir)) {
    die("Diretório da enem-api não encontrado em: $enemApiDir\n");
}

echo "=== INICIANDO IMPORTAÇÃO DE QUESTÕES DO ENEM ===\n\n";

// Mapeamento de disciplinas para subject_id e default_lesson_id
$subjectMap = [
    'matematica' => [
        'subject_id' => 1,
        'lesson_id'  => 1
    ],
    'ciencias-natureza' => [
        'subject_id' => 4, // Biologia/Natureza
        'lesson_id'  => 61
    ],
    'linguagens' => [
        'subject_id' => 5, // Português/Linguagens
        'lesson_id'  => 81
    ],
    'ciencias-humanas' => [
        'subject_id' => 6, // Humanas
        'lesson_id'  => 101
    ]
];

$stmtCheck = $pdo->prepare("SELECT id FROM questions WHERE exam_source = ? LIMIT 1");
$stmtInsert = $pdo->prepare("
    INSERT INTO questions (
        lesson_id, exam_source, question_text, 
        option_a, option_b, option_c, option_d, option_e, 
        correct_option, explanation_text, difficulty, is_boss, created_at
    ) VALUES (
        ?, ?, ?, 
        ?, ?, ?, ?, ?, 
        ?, ?, ?, 0, NOW()
    )
");

$years = glob("$enemApiDir/[1-2][0-9][0-9][0-9]", GLOB_ONLYDIR);
rsort($years); // Mais recentes primeiro

$totalImported = 0;
$totalSkipped = 0;

foreach ($years as $yearDir) {
    $year = basename($yearDir);
    echo "Processando ENEM $year...\n";

    $questionDirs = glob("$yearDir/questions/*", GLOB_ONLYDIR);
    $yearImported = 0;
    $yearSkipped = 0;

    foreach ($questionDirs as $qDir) {
        $detailsFile = "$qDir/details.json";
        if (!file_exists($detailsFile)) continue;

        $raw = file_get_contents($detailsFile);
        $data = json_decode($raw, true);
        if (!$data || empty($data['alternatives']) || empty($data['correctAlternative'])) {
            continue;
        }

        $index = $data['index'] ?? basename($qDir);
        $sourceTag = "ENEM $year - Questão $index";

        // Verificar duplicata
        $stmtCheck->execute([$sourceTag]);
        if ($stmtCheck->fetchColumn()) {
            $yearSkipped++;
            $totalSkipped++;
            continue;
        }

        // Mapear disciplina
        $disc = strtolower($data['discipline'] ?? 'linguagens');
        $mapping = $subjectMap[$disc] ?? $subjectMap['linguagens'];
        $lessonId = $mapping['lesson_id'];

        // Montar texto da questão preservando imagens e contexto
        $context = trim($data['context'] ?? '');
        $intro = trim($data['alternativesIntroduction'] ?? '');

        // Adicionar imagens do array 'files' caso não estejam no markdown do context
        if (!empty($data['files']) && is_array($data['files'])) {
            foreach ($data['files'] as $f) {
                if ($f && strpos($context, $f) === false) {
                    $context .= "\n\n![]($f)";
                }
            }
        }

        $fullText = $context;
        if ($intro) {
            $fullText .= ($fullText ? "\n\n" : "") . $intro;
        }

        if (empty($fullText)) {
            $fullText = "Questão $index do ENEM $year";
        }

        // Mapear alternativas A-E
        $options = ['a' => '', 'b' => '', 'c' => '', 'd' => '', 'e' => ''];
        foreach ($data['alternatives'] as $alt) {
            $letter = strtolower($alt['letter'] ?? '');
            if (isset($options[$letter])) {
                $altText = trim($alt['text'] ?? '');
                if (!empty($alt['file'])) {
                    $altText .= ($altText ? ' ' : '') . "![]({$alt['file']})";
                }
                $options[$letter] = $altText ?: "(Sem texto)";
            }
        }

        // Se faltar alguma alternativa essencial
        if (empty($options['a']) || empty($options['b']) || empty($options['c']) || empty($options['d'])) {
            $yearSkipped++;
            $totalSkipped++;
            continue;
        }
        if (empty($options['e'])) {
            $options['e'] = "N.D.A.";
        }

        $correctLetter = strtolower($data['correctAlternative']);
        if (!in_array($correctLetter, ['a', 'b', 'c', 'd', 'e'])) {
            $correctLetter = 'a';
        }

        // Dificuldade estimada balanceada
        $diffs = ['fácil', 'médio', 'médio', 'difícil'];
        $difficulty = $diffs[($index % 4)];

        $explanation = "Gabarito Oficial INEP: Alternativa " . strtoupper($correctLetter) . ".\nQuestão aplicada no ENEM $year ({$data['discipline']}).";

        try {
            $stmtInsert->execute([
                $lessonId,
                $sourceTag,
                $fullText,
                $options['a'],
                $options['b'],
                $options['c'],
                $options['d'],
                $options['e'],
                $correctLetter,
                $explanation,
                $difficulty
            ]);
            $yearImported++;
            $totalImported++;
        } catch (Exception $e) {
            // Ignorar erro pontual de charset ou constraint
            $yearSkipped++;
            $totalSkipped++;
        }
    }

    echo " -> ENEM $year finalizado: $yearImported novas importadas, $yearSkipped ignoradas/existentes.\n";
}

echo "\n============================================\n";
echo "IMPORTAÇÃO CONCLUÍDA!\n";
echo "Total de novas questões inseridas: $totalImported\n";
echo "Total de questões ignoradas/já existentes: $totalSkipped\n";
echo "============================================\n";
