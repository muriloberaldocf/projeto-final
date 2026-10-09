<?php
/**
 * CONFIGURAÇÃO & CLIENTE GEMINI API — HIPOGABARITO
 * Suporte a Gemini 2.5 Pro (melhor modelo para raciocínio profundo e correção ENEM)
 */

if (!function_exists('getGeminiConfig')) {
    function getGeminiConfig() {
        // 1. Tentar ler do .env ou ambiente
        $apiKey = getenv('GEMINI_API_KEY') ?: ($_ENV['GEMINI_API_KEY'] ?? '');
        $model = getenv('GEMINI_MODEL') ?: ($_ENV['GEMINI_MODEL'] ?? 'gemini-3.5-flash');

        // 2. Tentar chave salva na sessão ou chave padrão fallback
        if (empty($apiKey) && !empty($_SESSION['custom_gemini_api_key'])) {
            $apiKey = trim($_SESSION['custom_gemini_api_key']);
        }

        // Chave padrão do projeto descompactado (se nenhuma outra for fornecida)
        if (empty($apiKey)) {
            $apiKey = 'AIzaSyCKgW63-lLo5Ihx11a4tgRZduzxm5aMJdg';
        }

        return [
            'api_key' => trim($apiKey),
            'model'   => trim($model ?: 'gemini-3.5-flash'),
        ];
    }
}

if (!function_exists('callGeminiForEssay')) {
    /**
     * Executa a chamada à API do Gemini com o melhor modelo (gemini-2.5-pro),
     * suporte a multimodal (foto/PDF em base64) e fallback inteligente.
     */
    function callGeminiForEssay($prompt, $inlineFile = null, $overrideModel = null) {
        $config = getGeminiConfig();
        $apiKey = $config['api_key'];
        $model = $overrideModel ?: $config['model'];

        if (empty($apiKey)) {
            return [
                'success' => false,
                'error_type' => 'missing_key',
                'message' => 'Nenhuma chave de API do Gemini foi configurada no sistema.'
            ];
        }

        $parts = [];
        $parts[] = ['text' => $prompt];

        if (!empty($inlineFile) && !empty($inlineFile['data']) && !empty($inlineFile['mimeType'])) {
            $parts[] = [
                'inlineData' => [
                    'mimeType' => $inlineFile['mimeType'],
                    'data'     => $inlineFile['data']
                ]
            ];
        }

        $requestBody = [
            'contents' => [
                [
                    'role'  => 'user',
                    'parts' => $parts
                ]
            ],
            'generationConfig' => [
                'temperature'       => 0.4, // Menor temperatura para avaliação analítica rigorosa e consistente
                'responseMimeType'  => 'application/json'
            ]
        ];

        // Tentar primeiro com o modelo principal (ex: gemini-3.5-flash)
        $result = executeGeminiCurl($model, $apiKey, $requestBody);

        // Se falhar por sobrecarga transitória (503 / 500) ou modelo indisponível, tentar com fallback gemini-2.5-flash
        if (!$result['success'] && in_array($result['http_code'] ?? 0, [500, 503, 404]) && $model !== 'gemini-2.5-flash') {
            $result = executeGeminiCurl('gemini-2.5-flash', $apiKey, $requestBody);
        }

        return $result;
    }
}

if (!function_exists('executeGeminiCurl')) {
    function executeGeminiCurl($model, $apiKey, $requestBody) {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . urlencode($apiKey);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($requestBody),
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT        => 120
        ]);

        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($curlError) {
            return [
                'success' => false,
                'http_code' => 0,
                'message' => 'Falha de conexão com os servidores do Google Gemini (cURL): ' . $curlError
            ];
        }

        $data = json_decode($response, true);

        // Tratamento detalhado de códigos HTTP
        if ($httpCode === 402) {
            return [
                'success' => false,
                'http_code' => 402,
                'error_type' => 'prepayment_depleted',
                'message' => 'Os créditos da sua chave de API do Gemini foram esgotados (Erro 402 do Google). Para resolver de graça, gere uma nova chave em aistudio.google.com/app/apikey (em um projeto pessoal gratuito).'
            ];
        }

        if ($httpCode === 429) {
            return [
                'success' => false,
                'http_code' => 429,
                'error_type' => 'rate_limit',
                'message' => 'Limite de requisições por minuto da API do Gemini atingido (Erro 429). Aguarde 1 minuto e tente novamente.'
            ];
        }

        if ($httpCode === 400) {
            $errMsg = $data['error']['message'] ?? 'Erro nos parâmetros da requisição.';
            return [
                'success' => false,
                'http_code' => 400,
                'error_type' => 'bad_request',
                'message' => 'Erro na requisição da IA (Código 400): ' . $errMsg
            ];
        }

        if ($httpCode >= 400) {
            $errMsg = $data['error']['message'] ?? "Código HTTP {$httpCode}";
            return [
                'success' => false,
                'http_code' => $httpCode,
                'error_type' => 'api_error',
                'message' => "A API do Gemini retornou um erro ({$httpCode}): " . $errMsg
            ];
        }

        // Extrair texto da resposta
        $candidateText = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
        if (empty($candidateText)) {
            return [
                'success' => false,
                'http_code' => $httpCode,
                'message' => 'A IA não retornou conteúdo textual para a avaliação.'
            ];
        }

        // Limpar possíveis blocos markdown ```json ... ```
        $cleanJson = trim(preg_replace('/^```(?:json)?\s*|\s*```$/i', '', trim($candidateText)));
        $parsedJson = json_decode($cleanJson, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($parsedJson)) {
            return [
                'success' => false,
                'http_code' => $httpCode,
                'raw_text' => $cleanJson,
                'message' => 'A IA retornou os dados em um formato JSON inválido. Tente enviar novamente.'
            ];
        }

        return [
            'success'   => true,
            'http_code' => $httpCode,
            'model'     => $model,
            'data'      => $parsedJson
        ];
    }
}
