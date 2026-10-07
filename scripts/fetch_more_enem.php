<?php
$years = [2023, 2019, 2018];
$cacheDir = __DIR__ . '/../database/enem_cache';

foreach ($years as $yr) {
    $filePath = "$cacheDir/enem_{$yr}.json";
    echo "Baixando ENEM $yr...\n";
    $allQuestions = [];
    $offset = 0;
    $limit = 45;

    while (true) {
        $url = "https://api.enem.dev/v1/exams/{$yr}/questions?limit={$limit}&offset={$offset}";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0");
        curl_setopt($ch, CURLOPT_TIMEOUT, 25);
        $res = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode !== 200 || !$res) {
            echo "  Erro no offset $offset (HTTP $httpCode)\n";
            break;
        }

        $data = json_decode($res, true);
        $questions = $data['questions'] ?? [];
        if (empty($questions)) {
            break;
        }

        $allQuestions = array_merge($allQuestions, $questions);
        $total = $data['metadata']['total'] ?? 0;
        echo "  Offset $offset: +" . count($questions) . " questões (Total baixado: " . count($allQuestions) . " / Esperado: $total)\n";

        if (count($allQuestions) >= $total || empty($questions)) {
            break;
        }
        $offset += count($questions);
        usleep(100000);
    }

    file_put_contents($filePath, json_encode($allQuestions, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    echo "Ano $yr finalizado com " . count($allQuestions) . " questões salvas em $filePath\n\n";
}
