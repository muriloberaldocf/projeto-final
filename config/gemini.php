<?php
/**
 * CONFIGURAÇÃO & CLIENTE GEMINI API — HIPOGABARITO
 * Suporte a Gemini 2.5 Pro (melhor modelo para raciocínio profundo e correção ENEM)
 */

require_once __DIR__ . '/db.php';

if (!function_exists('getGeminiConfig')) {
    function getGeminiConfig() {
        // 1. Tentar ler da sessão (caso o usuário tenha inserido no modal)
        $apiKey = '';
        if (!empty($_SESSION['custom_gemini_api_key'])) {
            $apiKey = trim($_SESSION['custom_gemini_api_key']);
        }

        // 2. Tentar ler do .env ou ambiente
        if (empty($apiKey)) {
            $apiKey = getenv('GEMINI_API_KEY') ?: ($_ENV['GEMINI_API_KEY'] ?? '');
        }

        $model = getenv('GEMINI_MODEL') ?: ($_ENV['GEMINI_MODEL'] ?? 'gemini-3.5-flash-lite');

        return [
            'api_key' => trim($apiKey),
            'model'   => trim($model ?: 'gemini-3.5-flash-lite'),
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
                'temperature'       => 0.2, // Baixa temperatura para avaliação analítica, criteriosa e rigorosa
                'responseMimeType'  => 'application/json'
            ]
        ];

        // Tentar primeiro com o modelo principal configurado
        $result = executeGeminiCurl($model, $apiKey, $requestBody);

        // Se falhar por sobrecarga transitória (503 / 500) ou modelo indisponível, tentar automaticamente modelos alternativos
        if (!$result['success'] && in_array($result['http_code'] ?? 0, [500, 503, 404])) {
            $fallbackModels = ['gemini-3.5-flash-lite', 'gemini-3.5-flash', 'gemini-3.1-flash-lite', 'gemini-flash-lite-latest'];
            foreach ($fallbackModels as $fb) {
                if ($fb !== $model) {
                    $result = executeGeminiCurl($fb, $apiKey, $requestBody);
                    if ($result['success']) break;
                }
            }
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

        if ($httpCode === 403) {
            $errMsg = $data['error']['message'] ?? 'Acesso negado pela API do Google.';
            if (stripos($errMsg, 'leaked') !== false || stripos($errMsg, 'API key') !== false) {
                return [
                    'success' => false,
                    'http_code' => 403,
                    'error_type' => 'invalid_or_leaked_key',
                    'message' => 'A chave de API do Gemini configurada foi bloqueada pelo Google (reportada como vazada/inválida). Clique no botão "IA: ..." no topo da página ou configure uma nova chave gratuita em aistudio.google.com/app/apikey.'
                ];
            }
            return [
                'success' => false,
                'http_code' => 403,
                'error_type' => 'forbidden',
                'message' => 'Acesso negado pela API do Gemini (Erro 403): ' . $errMsg
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

        // Extrair texto da resposta iterando pelas partes (para lidar com partes de pensamento/texto)
        $candidateText = '';
        if (!empty($data['candidates'][0]['content']['parts'])) {
            foreach ($data['candidates'][0]['content']['parts'] as $part) {
                if (!empty($part['text'])) {
                    $candidateText .= $part['text'];
                }
            }
        }

        if (empty($candidateText)) {
            return [
                'success' => false,
                'http_code' => $httpCode,
                'message' => 'A IA não retornou conteúdo textual para a avaliação.'
            ];
        }

        // Limpar possíveis blocos markdown ```json ... ```
        $cleanJson = trim(preg_replace('/^```(?:json)?\s*|\s*```$/i', '', trim($candidateText)));
        
        // Se ainda contiver texto antes do primeiro { ou depois do último }, extrair o objeto JSON
        if (substr($cleanJson, 0, 1) !== '{') {
            $firstBrace = strpos($cleanJson, '{');
            $lastBrace = strrpos($cleanJson, '}');
            if ($firstBrace !== false && $lastBrace !== false && $lastBrace > $firstBrace) {
                $cleanJson = substr($cleanJson, $firstBrace, $lastBrace - $firstBrace + 1);
            }
        }

        $parsedJson = json_decode($cleanJson, true);

        // Se falhou por trailing commas geradas pela IA (vírgulas antes de } ou ]), sanitizar e tentar novamente
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($parsedJson)) {
            $sanitizedJson = preg_replace('/,\s*([}\]])/m', '$1', $cleanJson);
            $parsedJson = json_decode($sanitizedJson, true);
        }

        // Se ainda falhar, tentar remover possíveis caracteres de controle invisíveis
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($parsedJson)) {
            $sanitizedJson = preg_replace('/[\x00-\x1F\x7F]/u', ' ', $cleanJson);
            $sanitizedJson = preg_replace('/,\s*([}\]])/m', '$1', $sanitizedJson);
            $parsedJson = json_decode($sanitizedJson, true);
        }

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($parsedJson)) {
            @file_put_contents(__DIR__ . '/../storage_gemini_error.log', date('Y-m-d H:i:s') . "\nJSON ERROR: " . json_last_error_msg() . "\nRAW CLEAN TEXT:\n" . $cleanJson . "\n\n", FILE_APPEND);
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
