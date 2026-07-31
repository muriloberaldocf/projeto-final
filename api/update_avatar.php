<?php
/**
 * API DE EQUIPAR AVATAR COM UTILIDADE DE XP - APROVAQUEST
 */
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

$userId = $_SESSION['user_id'] ?? 0;
if (!$userId) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$avatarIcon = trim($data['avatar_icon'] ?? 'bi-person-circle');

// Catálogo de Avatares e XP necessário
$avatarsCatalog = [
    'bi-person-circle' => 0,
    'bi-backpack' => 20,
    'bi-mortarboard' => 50,
    'bi-rocket-takeoff' => 100,
    'bi-lightning-charge' => 200,
    'bi-award' => 350,
    'bi-gem' => 500,
    'bi-incognito' => 750,
    'bi-crown' => 1000,
    'bi-emoji-smile-fill' => 1500
];

if (!array_key_exists($avatarIcon, $avatarsCatalog)) {
    echo json_encode(['success' => false, 'message' => 'Avatar inválido']);
    exit;
}

// Verificar se o usuário possui XP suficiente
$stmtUser = $pdo->prepare("SELECT xp FROM users WHERE id = ?");
$stmtUser->execute([$userId]);
$userXp = (int) $stmtUser->fetchColumn();

$requiredXp = $avatarsCatalog[$avatarIcon];
if ($userXp < $requiredXp) {
    echo json_encode(['success' => false, 'message' => "Você precisa de no mínimo {$requiredXp} XP para desbloquear esta recompensa do Hipopótamo!"]);
    exit;
}

// Equipar Avatar (Se for o Hipopótamo Lendário 1500 XP, define também a foto de perfil do mascote!)
if ($avatarIcon === 'bi-emoji-smile-fill') {
    $stmtUpdate = $pdo->prepare("UPDATE users SET avatar_icon = ?, avatar = 'assets/img/logo_mascot.png' WHERE id = ?");
    $stmtUpdate->execute([$avatarIcon, $userId]);
} else {
    $stmtUpdate = $pdo->prepare("UPDATE users SET avatar_icon = ? WHERE id = ?");
    $stmtUpdate->execute([$avatarIcon, $userId]);
}

echo json_encode([
    'success' => true,
    'message' => 'Avatar equipado com sucesso!',
    'avatar_icon' => $avatarIcon
]);
