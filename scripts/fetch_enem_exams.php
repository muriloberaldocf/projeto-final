<?php
$years = [2023, 2022, 2021, 2020];
$cacheDir = __DIR__ . '/../database/enem_cache';
if (!is_dir($cacheDir)) {
    mkdir($cacheDir, 0777, true);
}

foreach ($years as $yr) {
    $filePath = "$cacheDir/enem_{$yr}.json";
    if (file_exists($filePath)) {
        $existing = json_decode(file_get_contents($filePath), true);
        echo "Ano $yr já em cache com " . count($existing) . " questões.\n";
        continue;
    }

    echo "Baixando ENEM $yr...\n";
    $allQuestions = [];
    $offset = 0;
    $limit = 50;

    while (true) {
        $url = "https://api.enem.dev/v1/exams/{$yr}/questions?limit={$limit}&offset={$offset}";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, "HipoGabarito-Seeder/1.0");
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
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
        $hasMore = $data['metadata']['hasMore'] ?? false;
        echo "  Offset $offset: +" . count($questions) . " questões (Total: " . count($allQuestions) . ")\n";

        if (!$hasMore || count($questions) < $limit) {
            break;
        }
        $offset += $limit;
        usleep(100000); // 100ms
    }

    file_put_contents($filePath, json_encode($allQuestions, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    echo "Ano $yr finalizado com " . count($allQuestions) . " questões salvas em $filePath\n\n";
}
