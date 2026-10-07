<?php
/**
 * API DE AUTENTICAÇÃO BLINDADA — HIPOGABARITO
 * - Prevenção contra força bruta (Rate Limiting por IP)
 * - Proteção contra Session Fixation (session_regenerate_id)
 * - Validação estrita de e-mail e comprimento de senha
 * - Logout seguro com invalidação de cookies de sessão
 */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$clientIp = getClientIp();

// 1. LOGIN DE USUÁRIO
if ($action === 'login') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
        exit;
    }

    // Checagem de Rate Limit (máx 5 tentativas por 10 minutos)
    $rateCheck = checkLoginRateLimit($pdo, $clientIp, 5, 10);
    if ($rateCheck['blocked']) {
        http_response_code(429);
        echo json_encode([
            'success' => false,
            'message' => 'Muitas tentativas incorretas de login. Por segurança, tente novamente em 10 minutos.'
        ]);
        exit;
    }

    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Preencha o e-mail e a senha!']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Formato de e-mail inválido.']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        // Sucesso: limpar histórico de tentativas do IP
        clearLoginAttempts($pdo, $clientIp);

        // Previne Session Fixation
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        
        // Sincronizar ofensiva
        syncUserStreak($pdo, $user['id']);

        echo json_encode(['success' => true, 'redirect' => 'dashboard.php']);
    } else {
        // Falha: registrar tentativa para o rate limiting
        recordFailedLogin($pdo, $clientIp);
        $remaining = max(0, $rateCheck['remaining'] - 1);

        $warnMsg = 'E-mail ou senha incorretos.';
        if ($remaining <= 2 && $remaining > 0) {
            $warnMsg .= " Atenção: mais {$remaining} tentativa(s) antes do bloqueio temporário.";
        }

        echo json_encode(['success' => false, 'message' => $warnMsg]);
    }
    exit;
}

// 2. CADASTRO / REGISTRO
if ($action === 'register') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
        exit;
    }

    $name = trim(strip_tags($_POST['name'] ?? ''));
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($name) || empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios!']);
        exit;
    }

    if (mb_strlen($name) < 2 || mb_strlen($name) > 80) {
        echo json_encode(['success' => false, 'message' => 'O nome deve ter entre 2 e 80 caracteres.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Informe um endereço de e-mail válido.']);
        exit;
    }

    if (strlen($password) < 6) {
        echo json_encode(['success' => false, 'message' => 'A senha deve conter no mínimo 6 caracteres por segurança.']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Este e-mail já está cadastrado no sistema.']);
        exit;
    }

    $hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role, streak_days, last_active_date) VALUES (?, ?, ?, 'student', 0, NULL)");
    $stmt->execute([$name, $email, $hash]);

    session_regenerate_id(true);

    $_SESSION['user_id'] = $pdo->lastInsertId();
    $_SESSION['user_name'] = $name;
    $_SESSION['user_role'] = 'student';

    echo json_encode(['success' => true, 'redirect' => 'dashboard.php']);
    exit;
}

// 3. LOGOUT SEGURO
if ($action === 'logout') {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    session_destroy();
    header("Location: ../index.php");
    exit;
}

// 4. LOGIN DEMO (PARA AVALIAÇÃO RÁPIDA)
if ($action === 'demo_login') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = 'aluno@senai.br'");
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user) {
        clearLoginAttempts($pdo, $clientIp);
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];

        syncUserStreak($pdo, $user['id']);

        echo json_encode(['success' => true, 'redirect' => 'dashboard.php']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuário demo não encontrado.']);
    }
    exit;
}
