<?php
require_once __DIR__ . '/../config/db.php';

try {
    // 1. Tabela de Amizades
    $pdo->exec("CREATE TABLE IF NOT EXISTS `user_friends` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT NOT NULL,
        `friend_id` INT NOT NULL,
        `status` ENUM('pending', 'accepted', 'rejected') DEFAULT 'accepted',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY `unique_friendship` (`user_id`, `friend_id`),
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
        FOREIGN KEY (`friend_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // 2. Conectar os alunos de teste existentes como amigos iniciais
    $stmtUsers = $pdo->query("SELECT id FROM users LIMIT 5");
    $userIds = $stmtUsers->fetchAll(PDO::FETCH_COLUMN);

    if (count($userIds) >= 2) {
        $stmtAdd = $pdo->prepare("INSERT IGNORE INTO user_friends (user_id, friend_id, status) VALUES (?, ?, 'accepted'), (?, ?, 'accepted')");
        for ($i = 0; $i < count($userIds); $i++) {
            for ($j = $i + 1; $j < count($userIds); $j++) {
                $stmtAdd->execute([$userIds[$i], $userIds[$j], $userIds[$j], $userIds[$i]]);
            }
        }
    }

    echo "Tabela user_friends criada e amizades iniciais vinculadas com sucesso!\n";
} catch (PDOException $e) {
    echo "Erro na migração: " . $e->getMessage() . "\n";
}
