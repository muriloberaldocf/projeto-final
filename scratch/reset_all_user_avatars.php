<?php
require_once __DIR__ . '/../config/db.php';

$stmt = $pdo->prepare("UPDATE users SET avatar = 'assets/img/default_avatar.jpg', avatar_icon = 'bi-person-circle' WHERE avatar_icon = 'bi-emoji-smile-fill' OR avatar LIKE '%hipo%' OR avatar LIKE '%mascot%'");
$stmt->execute();

$stmtAll = $pdo->prepare("UPDATE users SET avatar = 'assets/img/default_avatar.jpg'");
$stmtAll->execute();

echo "Todas as contas de usuário no banco de dados foram redefinidas com sucesso para a logo oficial do HipoGabarito!\n";
