<?php
require_once __DIR__ . '/../config/db.php';

$stmt = $pdo->query("SELECT id, name, avatar FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Usuários no banco:\n";
foreach ($users as $u) {
    echo "ID: {$u['id']} | Nome: {$u['name']} | Avatar: {$u['avatar']}\n";
    if (strpos($u['avatar'], 'hipo') !== false || strpos($u['avatar'], 'mascot') !== false) {
        $update = $pdo->prepare("UPDATE users SET avatar = 'assets/img/default_avatar.jpg' WHERE id = ?");
        $update->execute([$u['id']]);
        echo " -> Avatar atualizado para logo padrão!\n";
    }
}
