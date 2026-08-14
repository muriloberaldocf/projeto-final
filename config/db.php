<?php
/**
 * Conexão com o Banco de Dados MySQL via PDO - HipoGabarito
 * Configurado para o ambiente padrão do XAMPP (localhost, root, sem senha)
 */

/**
 * Carregador simples de arquivo .env (sem dependências externas)
 */
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || str_starts_with($line, '#')) continue;
        if (str_contains($line, '=')) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value, " \t\n\r\0\x0B\"'");
            if (!array_key_exists($key, $_SERVER) && !array_key_exists($key, $_ENV)) {
                putenv("$key=$value");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }
}

// Leitura das configurações com fallback para o ambiente local XAMPP
$dbHost = getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? 'localhost');
$dbPort = getenv('DB_PORT') ?: ($_ENV['DB_PORT'] ?? '3306');
$dbName = getenv('DB_NAME') ?: ($_ENV['DB_NAME'] ?? 'vestilingo');
$dbUser = getenv('DB_USER') ?: ($_ENV['DB_USER'] ?? 'root');
$dbPass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : ($_ENV['DB_PASS'] ?? '');
$appName = getenv('APP_NAME') ?: ($_ENV['APP_NAME'] ?? 'HipoGabarito');

if (!defined('DB_NAME')) {
    define('DB_NAME', $dbName);
}
if (!defined('APP_NAME')) {
    define('APP_NAME', $appName);
}

$charset = 'utf8mb4';
$dsn = "mysql:host=$dbHost;port=$dbPort;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // 1. Conectar ao servidor MySQL
    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
    
    // 2. Garantir que o banco hipogabarito existe
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$dbName`");

    // 3. Verificar se as tabelas já foram criadas; se não, executa o schema.sql automaticamente
    $tablesCheck = $pdo->query("SHOW TABLES LIKE 'users'")->fetch();
    if (!$tablesCheck) {
        $sqlFile = __DIR__ . '/../database/schema.sql';
        if (file_exists($sqlFile)) {
            $sqlContent = file_get_contents($sqlFile);
            $pdo->exec($sqlContent);
        }
    }

    // Autoseed automático desativado para preservar o banco oficial de questões reais dos vestibulares.
    
} catch (\PDOException $e) {
    // Tela de Erro Amigável, Bonita e com Rostinho Triste quando o MySQL está desligado
    die("<!DOCTYPE html>
    <html lang='pt-BR'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Conexão Offline — HipoGabarito</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css' rel='stylesheet'>
        <link rel='stylesheet' href='assets/css/main.css'>
    </head>
    <body class='bg-mesh-gradient d-flex align-items-center justify-content-center min-vh-100 p-3'>
        <div class='card card-aprova text-center p-5 shadow-lg border-0' style='max-width: 480px; width: 100%; border-radius: 20px;'>
            <div class='mb-3'>
                <i class='bi bi-emoji-frown display-1 text-danger opacity-75'></i>
            </div>
            <h4 class='fw-bold text-dark mb-2'>Ops! Não consegui conectar ao MySQL</h4>
            <p class='text-muted small mb-4'>Parece que o serviço do Banco de Dados está desligado no seu computador.</p>
            
            <div class='bg-light p-3 rounded-3 text-start small mb-4 border'>
                <strong class='d-block text-dark mb-1'><i class='bi bi-wrench-adjust me-1 text-primary'></i> Como resolver em 2 passos:</strong>
                <ol class='mb-0 ps-3 text-secondary'>
                    <li>Abra o <strong>XAMPP Control Panel</strong>.</li>
                    <li>Clique no botão <strong>Start</strong> ao lado de <strong>MySQL</strong>.</li>
                </ol>
            </div>

            <button onclick='window.location.reload()' class='btn btn-aprova-primary w-100 py-2.5 fw-semibold'>
                <i class='bi bi-arrow-clockwise me-1'></i> Tentar Novamente
            </button>
        </div>
    </body>
    </html>");
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Função Auxiliar para verificar se o usuário está logado
 */
function checkAuth() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit;
    }
}

function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}
