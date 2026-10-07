<?php
/**
 * API DE AUTENTICAÇÃO - APROVAQUEST (LOGIN / REGISTRO / LOGOUT)
 * Com cálculo de Ofensiva Diária (Daily Streak) na entrada.
 */
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

// Login
if ($action === 'login') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Preencha o e-mail e a senha!']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        
        // Apenas sincroniza (zera se ficou dias sem entrar; NÃO incrementa streak no login)
        syncUserStreak($pdo, $user['id']);

        echo json_encode(['success' => true, 'redirect' => 'dashboard.php']);
    } else {
        echo json_encode(['success' => false, 'message' => 'E-mail ou senha incorretos.']);
    }
    exit;
}

if ($action === 'register') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($name) || empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios!']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Este e-mail já está cadastrado.']);
        exit;
    }

    $today = date('Y-m-d');
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role, streak_days, last_active_date) VALUES (?, ?, ?, 'student', 0, NULL)");
    $stmt->execute([$name, $email, $hash]);

    $_SESSION['user_id'] = $pdo->lastInsertId();
    $_SESSION['user_name'] = $name;
    $_SESSION['user_role'] = 'student';

    echo json_encode(['success' => true, 'redirect' => 'dashboard.php']);
    exit;
}

if ($action === 'logout') {
    session_destroy();
    header("Location: ../index.php");
    exit;
}

if ($action === 'demo_login') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = 'aluno@senai.br'");
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user) {
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
