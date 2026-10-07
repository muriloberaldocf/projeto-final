<?php
require_once __DIR__ . '/../config/db.php';

$cacheDir = __DIR__ . '/../database/enem_cache';
$files = glob("$cacheDir/enem_*.json");

$allEnem = [];
foreach ($files as $file) {
    $data = json_decode(file_get_contents($file), true);
    foreach ($data as $q) {
        $hasBroken = (strpos($q['context'] ?? '', 'broken-image.svg') !== false || strpos($q['alternativesIntroduction'] ?? '', 'broken-image.svg') !== false);
        $allAltsHaveText = true;
        if (!empty($q['alternatives'])) {
            foreach ($q['alternatives'] as $alt) {
                if (empty(trim($alt['text'] ?? ''))) $allAltsHaveText = false;
            }
        } else {
            $allAltsHaveText = false;
        }

        if (!$hasBroken && $allAltsHaveText && (!empty($q['context']) || !empty($q['alternativesIntroduction']))) {
            $allEnem[] = $q;
        }
    }
}

// Lições por matéria
$lessons = $pdo->query("
    SELECT l.id, l.title, u.title as unit_title, s.slug as subject_slug, s.name as subject_name
    FROM lessons l
    JOIN units u ON l.unit_id = u.id
    JOIN subjects s ON u.subject_id = s.id
")->fetchAll();

$lessonsBySubject = [];
foreach ($lessons as $l) {
    $lessonsBySubject[$l['subject_slug']][] = $l;
}

// Termos para discriminar ciências da natureza
$fisKeywords = ['velocidade', 'aceleração', 'atrito', 'força', 'newton', 'cinética', 'potencial', 'trabalho', 'joule', 'gravidade', 'pressão', 'empuxo', 'arquimedes', 'temperatura', 'calor', 'calorimetria', 'condução', 'convecção', 'radiação', 'termodinâmica', 'onda', 'frequência', 'hertz', 'doppler', 'refração', 'reflexão', 'luz', 'espelho', 'lente', 'óptica', 'carga elétrica', 'coulomb', 'corrente elétrica', 'ampere', 'circuito', 'resistor', 'ohm', 'ddp', 'volt', 'potência', 'watt', 'kwh', 'campo magnético', 'indução'];
$quimKeywords = ['átomo', 'elétron', 'próton', 'distribuição eletrônica', 'tabela periódica', 'eletronegatividade', 'ligação iônica', 'ligação covalente', 'geometria molecular', 'polaridade', 'estequiometria', 'mol', 'massa molar', 'solução', 'soluto', 'solvente', 'concentração', 'molaridade', 'entalpia', 'combustão', 'endotérmica', 'exotérmica', 'cinética química', 'catalisador', 'equilíbrio químico', 'le chatelier', 'ph', 'poh', 'ácido', 'base', 'hidrólise', 'oxirredução', 'pilha', 'cátodo', 'ânodo', 'eletrólise', 'carbono', 'cadeia carbônica', 'hidrocarboneto', 'álcool', 'aldeído', 'cetona', 'ácido carboxílico', 'éster', 'amina', 'amida', 'isomeria', 'polímero'];
$bioKeywords = ['célula', 'membrana plasmática', 'organela', 'mitocôndria', 'dna', 'rna', 'proteína', 'mitose', 'meiose', 'respiração celular', 'fotossíntese', 'clorofila', 'ecologia', 'cadeia alimentar', 'teia alimentar', 'trófico', 'bioma', 'ecossistema', 'mutualismo', 'parasitismo', 'predação', 'poluição', 'biodiversidade', 'bioacumulação', 'eutrofização', 'genética', 'mendel', 'heredograma', 'sistema abo', 'fator rh', 'biotecnologia', 'transgênico', 'evolução', 'darwin', 'seleção natural', 'vírus', 'bactéria', 'vacina', 'soro', 'anticorpo', 'antígeno', 'sistema imune', 'digestório', 'circulatório', 'respiratório', 'excretor', 'nervoso', 'endócrino', 'hormônio', 'planta', 'vegetal', 'inseto', 'peixe', 'ave', 'mamífero', 'espécie'];

function getNatureSubject($fullText, $fisKw, $quimKw, $bioKw) {
    $sF = 0; foreach ($fisKw as $k) if (strpos($fullText, $k) !== false) $sF += 2;
    $sQ = 0; foreach ($quimKw as $k) if (strpos($fullText, $k) !== false) $sQ += 2;
    $sB = 0; foreach ($bioKw as $k) if (strpos($fullText, $k) !== false) $sB += 2;
    $m = max($sF, $sQ, $sB);
    if ($m === 0) return 'biologia'; // fallback
    if ($sB === $m) return 'biologia';
    if ($sQ === $m) return 'quimica';
    return 'fisica';
}

function matchLesson($fullText, $candidateLessons) {
    $bestLesson = $candidateLessons[0];
    $bestScore = -1;

    foreach ($candidateLessons as $l) {
        $score = 0;
        $titleLower = mb_strtolower($l['title'], 'UTF-8');
        $unitLower = mb_strtolower($l['unit_title'], 'UTF-8');
        
        $tokens = preg_split('/[\s,\:\;\(\)\-]+/u', $titleLower . ' ' . $unitLower);
        foreach ($tokens as $t) {
            $t = trim($t);
            if (mb_strlen($t) >= 4 && !in_array($t, ['para', 'com', 'das', 'dos', 'uma', 'como', 'sobre', 'lição', 'unidade'])) {
                if (strpos($fullText, $t) !== false) {
                    $score += 3;
                }
            }
        }

        if ($score > $bestScore) {
            $bestScore = $score;
            $bestLesson = $l;
        }
    }

    return [$bestLesson, $bestScore];
}

$mappedCounts = [];
$totalClassified = 0;

foreach ($allEnem as $q) {
    $disc = $q['discipline'] ?? '';
    $fullText = mb_strtolower(($q['context'] ?? '') . ' ' . ($q['alternativesIntroduction'] ?? ''), 'UTF-8');
    foreach ($q['alternatives'] as $a) $fullText .= ' ' . mb_strtolower($a['text'] ?? '', 'UTF-8');

    $targetSubject = null;
    if ($disc === 'matematica') $targetSubject = 'matematica';
    elseif ($disc === 'linguagens') $targetSubject = 'portugues';
    elseif ($disc === 'ciencias-humanas') $targetSubject = 'humanas';
    elseif ($disc === 'ciencias-natureza') $targetSubject = getNatureSubject($fullText, $fisKeywords, $quimKeywords, $bioKeywords);

    if (!$targetSubject || !isset($lessonsBySubject[$targetSubject])) continue;

    list($chosenLesson, $score) = matchLesson($fullText, $lessonsBySubject[$targetSubject]);
    $mappedCounts[$chosenLesson['id']] = ($mappedCounts[$chosenLesson['id']] ?? 0) + 1;
    $totalClassified++;
}

echo "Total classificadas com sucesso: $totalClassified\n";
echo "Total de lições que receberam pelo menos 1 questão do ENEM: " . count($mappedCounts) . " / " . count($lessons) . "\n";
echo "Média de questões ENEM por lição contemplada: " . round($totalClassified / count($mappedCounts), 1) . "\n";
