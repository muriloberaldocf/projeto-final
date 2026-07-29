<?php
/**
 * MIGRATION PARA AVATARES CUSTOMIZÁVEIS - APROVAQUEST
 */
require_once __DIR__ . '/../config/db.php';

try {
    $pdo->exec("ALTER TABLE users ADD COLUMN avatar_icon VARCHAR(50) DEFAULT 'bi-person-circle'");
} catch (\PDOException $e) {
    // Coluna já existe
}

echo "Migração de Avatares concluída com sucesso!";
