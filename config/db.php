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

    // Garantir tabela de atividade diária
    $pdo->exec("CREATE TABLE IF NOT EXISTS `user_daily_activity` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT NOT NULL,
        `activity_date` DATE NOT NULL,
        `xp_earned` INT DEFAULT 0,
        `lessons_completed` INT DEFAULT 0,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY `unique_user_date` (`user_id`, `activity_date`),
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Garantir tabela para rate limiting contra ataques de força bruta
    $pdo->exec("CREATE TABLE IF NOT EXISTS `login_attempts` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `ip_address` VARCHAR(45) NOT NULL,
        `attempted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX `idx_ip_time` (`ip_address`, `attempted_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

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

// Configurações avançadas de segurança de sessão
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_samesite', 'Lax');
    ini_set('session.use_strict_mode', '1');
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        ini_set('session.cookie_secure', '1');
    }
    session_start();
}

/**
 * Funções de Proteção CSRF (Cross-Site Request Forgery)
 */
function getCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCsrfToken($token) {
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], (string)$token);
}

/**
 * Rate Limiting e Prevenção de Ataques de Força Bruta
 */
function getClientIp() {
    $headers = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
    foreach ($headers as $h) {
        if (!empty($_SERVER[$h])) {
            $ip = trim(explode(',', $_SERVER[$h])[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }
    return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
}

function checkLoginRateLimit($pdo, $ipAddress, $maxAttempts = 5, $decayMinutes = 10) {
    try {
        // Limpar tentativas antigas com mais de 24 horas
        $pdo->exec("DELETE FROM login_attempts WHERE attempted_at < DATE_SUB(NOW(), INTERVAL 1 DAY)");

        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM login_attempts 
            WHERE ip_address = ? 
              AND attempted_at >= DATE_SUB(NOW(), INTERVAL ? MINUTE)
        ");
        $stmt->execute([$ipAddress, $decayMinutes]);
        $attempts = (int) $stmt->fetchColumn();

        return [
            'blocked' => ($attempts >= $maxAttempts),
            'attempts' => $attempts,
            'remaining' => max(0, $maxAttempts - $attempts)
        ];
    } catch (Exception $e) {
        return ['blocked' => false, 'attempts' => 0, 'remaining' => $maxAttempts];
    }
}

function recordFailedLogin($pdo, $ipAddress) {
    try {
        $stmt = $pdo->prepare("INSERT INTO login_attempts (ip_address, attempted_at) VALUES (?, NOW())");
        $stmt->execute([$ipAddress]);
    } catch (Exception $e) {}
}

function clearLoginAttempts($pdo, $ipAddress) {
    try {
        $stmt = $pdo->prepare("DELETE FROM login_attempts WHERE ip_address = ?");
        $stmt->execute([$ipAddress]);
    } catch (Exception $e) {}
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

/**
 * Sincroniza a Ofensiva Diária (Streak):
 * - Se o usuário ficou sem concluir tarefas (last_active_date anterior a ontem), a sequência é zerada (0).
 * - Retorna array com streak_days atualizado e se a sequência está ativa/acesa hoje (last_active_date === hoje).
 */
function syncUserStreak($pdo, $userId) {
    if (!$userId) {
        return ['streak_days' => 0, 'is_active_today' => false];
    }

    $stmt = $pdo->prepare("SELECT streak_days, last_active_date FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    if (!$user) {
        return ['streak_days' => 0, 'is_active_today' => false];
    }

    $today = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    $lastActive = $user['last_active_date'];
    $streak = (int)($user['streak_days'] ?? 0);

    // Se nunca fez tarefa ou a última tarefa foi antes de ontem, a sequência quebrou e vai para 0
    if (empty($lastActive) || ($lastActive !== $today && $lastActive !== $yesterday)) {
        if ($streak !== 0) {
            $streak = 0;
            $up = $pdo->prepare("UPDATE users SET streak_days = 0 WHERE id = ?");
            $up->execute([$userId]);
        }
    }

    $isActiveToday = (!empty($lastActive) && $lastActive === $today && $streak > 0);

    return [
        'streak_days' => $streak,
        'is_active_today' => $isActiveToday
    ];
}

/**
 * Incrementa a Ofensiva Diária ao concluir uma atividade/tarefa válida (lição ou simulado):
 * - Se já fez atividade hoje, mantém a sequência atual (já acesa).
 * - Se a última atividade foi ontem, incrementa a sequência (+1).
 * - Se a última atividade foi antes de ontem ou nunca, inicia em 1.
 */
function recordUserActivityStreak($pdo, $userId) {
    if (!$userId) return 0;

    $stmt = $pdo->prepare("SELECT streak_days, last_active_date FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    if (!$user) return 0;

    $today = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    $lastActive = $user['last_active_date'];
    $currentStreak = (int)($user['streak_days'] ?? 0);

    if ($lastActive === $today && $currentStreak > 0) {
        $newStreak = $currentStreak;
    } else if ($lastActive === $yesterday && $currentStreak > 0) {
        $newStreak = $currentStreak + 1;
    } else {
        $newStreak = 1;
    }

    $up = $pdo->prepare("UPDATE users SET streak_days = ?, last_active_date = ? WHERE id = ?");
    $up->execute([$newStreak, $today, $userId]);

    return $newStreak;
}
