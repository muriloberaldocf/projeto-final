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

$badgesCatalog = getAvatarBadgesCatalog();

if (!array_key_exists($avatarIcon, $badgesCatalog)) {
    echo json_encode(['success' => false, 'message' => 'Avatar inválido']);
    exit;
}

// Verificar se o usuário possui XP suficiente
$stmtUser = $pdo->prepare("SELECT xp FROM users WHERE id = ?");
$stmtUser->execute([$userId]);
$userXp = (int) $stmtUser->fetchColumn();

$requiredXp = $badgesCatalog[$avatarIcon]['xp'];
if ($userXp < $requiredXp) {
    echo json_encode(['success' => false, 'message' => "Você precisa de no mínimo {$requiredXp} XP para desbloquear esta recompensa do Hipopótamo!"]);
    exit;
}

// Equipar Avatar / Título
$stmtUpdate = $pdo->prepare("UPDATE users SET avatar_icon = ? WHERE id = ?");
$stmtUpdate->execute([$avatarIcon, $userId]);

echo json_encode([
    'success' => true,
    'message' => 'Avatar equipado com sucesso!',
    'avatar_icon' => $avatarIcon
]);
