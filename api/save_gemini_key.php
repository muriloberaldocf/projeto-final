<?php
/**
 * API PARA CONFIGURAR & TESTAR CHAVE DO GEMINI — HIPOGABARITO
 */
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', '0');
ob_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/gemini.php';

$userId = $_SESSION['user_id'] ?? 0;
if (!$userId) {
    if (ob_get_length()) ob_clean();
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

$apiKey = trim($_POST['api_key'] ?? '');
$model = trim($_POST['model'] ?? 'gemini-2.5-flash');
if ($model === 'gemini-3.5-flash') {
    $model = 'gemini-2.5-flash';
}

if (empty($apiKey)) {
    if (ob_get_length()) ob_clean();
    echo json_encode(['success' => false, 'message' => 'Por favor, informe uma chave de API válida do Google AI Studio.']);
    exit;
}

// Testar a chave com chamada ultrarrápida
$testUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . urlencode($apiKey);
$payload = [
    "contents" => [
        ["role" => "user", "parts" => [["text" => "Responda apenas: OK"]]]
    ]
];

$ch = curl_init($testUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => json_encode($payload),
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_TIMEOUT        => 15
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError) {
    if (ob_get_length()) ob_clean();
    echo json_encode(['success' => false, 'message' => 'Falha de conexão com a API do Google: ' . $curlError]);
    exit;
}

if ($httpCode !== 200) {
    $errData = json_decode($response, true);
    $errMsg = $errData['error']['message'] ?? "Código HTTP {$httpCode}";
    
    if ($httpCode === 402) {
        $errMsg = "Créditos pré-pagos esgotados neste projeto do Google (Erro 402). Por favor, crie uma chave gratuita no Google AI Studio (aistudio.google.com/app/apikey) em um projeto sem faturamento pré-pago.";
    } elseif ($httpCode === 400) {
        $errMsg = "Chave de API inválida ou formato incorreto (Erro 400).";
    } elseif ($httpCode === 403) {
        $errMsg = "Esta chave de API foi bloqueada pelo Google (ex: reportada como vazada). Por favor, gere uma nova chave em aistudio.google.com/app/apikey.";
    }
    
    if (ob_get_length()) ob_clean();
    echo json_encode(['success' => false, 'http_code' => $httpCode, 'message' => $errMsg]);
    exit;
}

// Salvar na sessão
$_SESSION['custom_gemini_api_key'] = $apiKey;

// Tentar gravar no .env do projeto
$envPath = __DIR__ . '/../.env';
if (file_exists($envPath) && is_writable($envPath)) {
    $envContent = file_get_contents($envPath);
    if (strpos($envContent, 'GEMINI_API_KEY=') !== false) {
        $envContent = preg_replace('/GEMINI_API_KEY=.*$/m', "GEMINI_API_KEY={$apiKey}", $envContent);
    } else {
        $envContent .= "\nGEMINI_API_KEY={$apiKey}";
    }

    if (strpos($envContent, 'GEMINI_MODEL=') !== false) {
        $envContent = preg_replace('/GEMINI_MODEL=.*$/m', "GEMINI_MODEL={$model}", $envContent);
    } else {
        $envContent .= "\nGEMINI_MODEL={$model}";
    }

    file_put_contents($envPath, $envContent);
}

if (ob_get_length()) ob_clean();
echo json_encode([
    'success' => true,
    'message' => "Chave de API do Gemini testada e salva com sucesso! Modelo ativo: {$model}"
]);
