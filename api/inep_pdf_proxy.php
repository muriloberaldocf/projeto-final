<?php
/**
 * PROXY DE STREAMING DE PDFS OFICIAIS DO INEP — HIPOGABARITO
 * Permite carregar os Cadernos de Prova e Gabaritos Oficiais do INEP (download.inep.gov.br)
 * diretamente dentro do iframe interativo da plataforma (com cache em disco para carregamento instantâneo).
 */
$url = trim($_GET['url'] ?? '');

if (empty($url)) {
    http_response_code(400);
    die('URL não informada.');
}

// Validar que a URL pertence exclusivamente ao domínio oficial do INEP
$parsed = parse_url($url);
$host = strtolower($parsed['host'] ?? '');
$scheme = strtolower($parsed['scheme'] ?? '');

if ($scheme !== 'https' || $host !== 'download.inep.gov.br' || !preg_match('/\.pdf$/i', $parsed['path'] ?? '')) {
    http_response_code(403);
    die('Apenas arquivos PDF oficiais do domínio download.inep.gov.br são permitidos.');
}

$cacheDir = __DIR__ . '/../uploads/inep_cache';
if (!is_dir($cacheDir)) {
    @mkdir($cacheDir, 0775, true);
}

$filename = basename($parsed['path']);
$safeFilename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $filename);
$cacheFile = $cacheDir . '/' . $safeFilename;

// Se já estiver em cache local e for um PDF válido, servir imediatamente
if (file_exists($cacheFile) && filesize($cacheFile) > 1000) {
    header('Content-Type: application/pdf');
    header('Content-Length: ' . filesize($cacheFile));
    header('Content-Disposition: inline; filename="' . $safeFilename . '"');
    header('Cache-Control: public, max-age=86400');
    readfile($cacheFile);
    exit;
}

// Caso contrário, baixar do servidor oficial do INEP via cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$binaryData = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200 && !empty($binaryData) && substr($binaryData, 0, 4) === '%PDF') {
    @file_put_contents($cacheFile, $binaryData);
    header('Content-Type: application/pdf');
    header('Content-Length: ' . strlen($binaryData));
    header('Content-Disposition: inline; filename="' . $safeFilename . '"');
    header('Cache-Control: public, max-age=86400');
    echo $binaryData;
    exit;
}

// Fallback: redirecionar para a URL original do INEP caso falhe o download via proxy
header('Location: ' . $url);
exit;
