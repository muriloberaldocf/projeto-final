<?php
require_once __DIR__ . '/_guard.php';
/**
 * MIGRAÇÃO: Tabelas do Modo Simulado Cronometrado — HipoGabarito
 * Cria as tabelas `simulados` e `simulado_answers`.
 */
require_once __DIR__ . '/../config/db.php';

echo "=== Migração: Modo Simulado Cronometrado ===" . PHP_EOL;

// 1. Tabela de simulados (cada tentativa do aluno)
$pdo->exec("
CREATE TABLE IF NOT EXISTS `simulados` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `exam_type` VARCHAR(50) NOT NULL DEFAULT 'misto',
    `total_questions` INT NOT NULL DEFAULT 30,
    `time_limit_min` INT NOT NULL DEFAULT 60,
    `score` INT DEFAULT 0,
    `total_correct` INT DEFAULT 0,
    `time_spent_sec` INT DEFAULT 0,
    `xp_earned` INT DEFAULT 0,
    `started_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `finished_at` TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");
echo "✅ Tabela 'simulados' criada/verificada." . PHP_EOL;

// 2. Tabela de respostas individuais do simulado
$pdo->exec("
CREATE TABLE IF NOT EXISTS `simulado_answers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `simulado_id` INT NOT NULL,
    `question_id` INT NOT NULL,
    `subject_id` INT NOT NULL,
    `chosen_option` ENUM('a', 'b', 'c', 'd', 'e') DEFAULT NULL,
    `is_correct` TINYINT(1) DEFAULT 0,
    FOREIGN KEY (`simulado_id`) REFERENCES `simulados`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`question_id`) REFERENCES `questions`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");
echo "✅ Tabela 'simulado_answers' criada/verificada." . PHP_EOL;

echo PHP_EOL . "✅ MIGRAÇÃO COMPLETA!" . PHP_EOL;
