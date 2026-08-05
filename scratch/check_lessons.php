<?php
require_once __DIR__ . '/../config/db.php';
$stmt = $pdo->query('SHOW COLUMNS FROM lessons');
print_r($stmt->fetchAll());
