<?php
/**
 * API DE EQUIPAMENTO DE MOLDURA DE AVATAR — HIPOGABARITO
 */
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

$userId = $_SESSION['user_id'] ?? 0;
if (!$userId) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$frame = trim($data['frame'] ?? 'frame-indigo');

$frameCatalog = [
    'frame-indigo'  => ['name' => 'Índigo Padrão', 'min_xp' => 0],
    'frame-emerald' => ['name' => 'Esmeralda', 'min_xp' => 100],
    'frame-cyan'    => ['name' => 'Azul Ciano', 'min_xp' => 300],
    'frame-purple'  => ['name' => 'Roxo Místico', 'min_xp' => 500],
    'frame-rose'    => ['name' => 'Rosa Neon', 'min_xp' => 750],
    'frame-rainbow' => ['name' => 'Arco-Íris Lendária', 'min_xp' => 1000],
    'frame-gold'    => ['name' => 'Dourada Suprema', 'min_xp' => 1500],
];

if (!array_key_exists($frame, $frameCatalog)) {
    echo json_encode(['success' => false, 'message' => 'Moldura inválida.']);
    exit;
}

$stmtUser = $pdo->prepare("SELECT xp FROM users WHERE id = ?");
$stmtUser->execute([$userId]);
$userXp = (int) $stmtUser->fetchColumn();

$requiredXp = $frameCatalog[$frame]['min_xp'];

if ($userXp < $requiredXp) {
    echo json_encode([
        'success' => false,
        'message' => "Você precisa de {$requiredXp} XP para desbloquear a Moldura {$frameCatalog[$frame]['name']}! Seu XP atual: {$userXp} XP."
    ]);
    exit;
}

$stmtUpdate = $pdo->prepare("UPDATE users SET avatar_frame = ? WHERE id = ?");
$stmtUpdate->execute([$frame, $userId]);

echo json_encode([
    'success' => true,
    'message' => "Moldura {$frameCatalog[$frame]['name']} equipada com sucesso!",
    'frame' => $frame
]);
