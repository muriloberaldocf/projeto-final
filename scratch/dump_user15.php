<?php
require_once __DIR__ . '/../config/db.php';

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = 15 OR email = 'admin@sgm.com'");
$stmt->execute();
$user15 = $stmt->fetch(PDO::FETCH_ASSOC);

print_r($user15);
