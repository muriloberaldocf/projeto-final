<?php
/**
 * API DE SELEÇÃO E UPLOAD DE FOTO DE PERFIL — APROVAQUEST
 */
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

$userId = $_SESSION['user_id'] ?? 0;
if (!$userId) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

// 1. UPLOAD DE ARQUIVO
if (isset($_FILES['avatar_file']) && $_FILES['avatar_file']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['avatar_file'];
    
    // Verificar tamanho (máx 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        echo json_encode(['success' => false, 'message' => 'A imagem deve ter no máximo 5MB.']);
        exit;
    }
    
    // Extensões permitidas
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    if (!in_array($ext, $allowedExts)) {
        echo json_encode(['success' => false, 'message' => 'Formato inválido. Use JPG, PNG, WEBP ou GIF.']);
        exit;
    }
    
    // Validar tipo de imagem real
    $imageInfo = @getimagesize($file['tmp_name']);
    if (!$imageInfo) {
        echo json_encode(['success' => false, 'message' => 'O arquivo enviado não é uma imagem válida.']);
        exit;
    }
    
    // Criar diretório se não existir
    $uploadDir = __DIR__ . '/../uploads/avatars/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Nome único para a foto
    $filename = 'user_' . $userId . '_' . time() . '.' . $ext;
    $targetPath = $uploadDir . $filename;
    $relativePath = 'uploads/avatars/' . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        // Remover foto antiga se for um upload local
        $stmtOld = $pdo->prepare("SELECT avatar FROM users WHERE id = ?");
        $stmtOld->execute([$userId]);
        $oldAvatar = $stmtOld->fetchColumn();
        if ($oldAvatar && strpos($oldAvatar, 'uploads/avatars/') === 0 && file_exists(__DIR__ . '/../' . $oldAvatar)) {
            @unlink(__DIR__ . '/../' . $oldAvatar);
        }
        
        // Atualizar no banco
        $stmt = $pdo->prepare("UPDATE users SET avatar = ? WHERE id = ?");
        $stmt->execute([$relativePath, $userId]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Foto de perfil atualizada com sucesso!',
            'avatar_url' => $relativePath
        ]);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Falha ao salvar a imagem no servidor.']);
        exit;
    }
}

// 2. SELEÇÃO DE URL OU PRESET
$inputData = json_decode(file_get_contents('php://input'), true);
if (!empty($inputData['avatar_url'])) {
    $avatarUrl = trim($inputData['avatar_url']);
    
    $isValidUrl = filter_var($avatarUrl, FILTER_VALIDATE_URL);
    $isLocalAsset = (strpos($avatarUrl, 'assets/') === 0 || strpos($avatarUrl, 'uploads/') === 0 || file_exists(__DIR__ . '/../' . $avatarUrl));
    
    if (!$isValidUrl && !$isLocalAsset) {
        echo json_encode(['success' => false, 'message' => 'URL de imagem inválida.']);
        exit;
    }
    
    $stmt = $pdo->prepare("UPDATE users SET avatar = ? WHERE id = ?");
    $stmt->execute([$avatarUrl, $userId]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Foto de perfil alterada com sucesso!',
        'avatar_url' => $avatarUrl
    ]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Nenhuma foto enviada ou selecionada.']);
