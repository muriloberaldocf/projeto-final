<?php
/**
 * SEED DE USUÁRIOS INICIAIS DA PLATAFORMA HIPOGABARITO
 * Popula 10 estudantes com avatares, XP, níveis, ofensivas e conquistas.
 * Senha padrão para todos os usuários de teste: 123456
 */
require_once __DIR__ . '/../config/db.php';

try {
    // 1. Limpar tabela de usuários preservando integridade
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
    $pdo->exec("DELETE FROM user_answers;");
    $pdo->exec("DELETE FROM user_achievements;");
    $pdo->exec("DELETE FROM user_progress;");
    $pdo->exec("DELETE FROM users;");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

    $defaultHash = password_hash('123456', PASSWORD_BCRYPT);
    $today = date('Y-m-d');

    $initialUsers = [
        [
            'id' => 1,
            'name' => 'Sofia Ferreira',
            'email' => 'sofia.ferreira@enem.com',
            'avatar_icon' => 'bi-emoji-smile-fill',
            'avatar' => 'assets/img/logo_mascot.png',
            'avatar_frame' => 'frame-gold',
            'xp' => 1850,
            'level' => 19,
            'streak_days' => 24,
        ],
        [
            'id' => 2,
            'name' => 'Estudante Focado (Aluno Demo)',
            'email' => 'aluno@senai.br',
            'avatar_icon' => 'bi-crown',
            'avatar' => 'assets/img/avatars/avatar_student.jpg',
            'avatar_frame' => 'frame-rainbow',
            'xp' => 1450,
            'level' => 15,
            'streak_days' => 12,
        ],
        [
            'id' => 3,
            'name' => 'Lucas Mendes',
            'email' => 'lucas.mendes@fuvest.com',
            'avatar_icon' => 'bi-gem',
            'avatar' => 'assets/img/avatars/avatar_wizard.jpg',
            'avatar_frame' => 'frame-rainbow',
            'xp' => 1280,
            'level' => 13,
            'streak_days' => 15,
        ],
        [
            'id' => 4,
            'name' => 'Camila Rocha',
            'email' => 'camila.rocha@unicamp.br',
            'avatar_icon' => 'bi-incognito',
            'avatar' => 'assets/img/avatars/avatar_ninja.jpg',
            'avatar_frame' => 'frame-rose',
            'xp' => 980,
            'level' => 10,
            'streak_days' => 9,
        ],
        [
            'id' => 5,
            'name' => 'Gabriel Oliveira',
            'email' => 'gabriel.oliveira@vunesp.br',
            'avatar_icon' => 'bi-rocket-takeoff',
            'avatar' => 'assets/img/avatars/avatar_rocket.jpg',
            'avatar_frame' => 'frame-purple',
            'xp' => 760,
            'level' => 8,
            'streak_days' => 6,
        ],
        [
            'id' => 6,
            'name' => 'Ana Clara Lima',
            'email' => 'ana.clara@enem.com',
            'avatar_icon' => 'bi-award',
            'avatar' => 'assets/img/avatars/avatar_owl.jpg',
            'avatar_frame' => 'frame-cyan',
            'xp' => 540,
            'level' => 6,
            'streak_days' => 5,
        ],
        [
            'id' => 7,
            'name' => 'Rodrigo Alves',
            'email' => 'rodrigo.alves@senai.br',
            'avatar_icon' => 'bi-lightning-charge',
            'avatar' => 'assets/img/avatars/avatar_robot.jpg',
            'avatar_frame' => 'frame-emerald',
            'xp' => 390,
            'level' => 4,
            'streak_days' => 4,
        ],
        [
            'id' => 8,
            'name' => 'Mariana Silva',
            'email' => 'mariana.silva@enem.com',
            'avatar_icon' => 'bi-mortarboard',
            'avatar' => 'assets/img/avatars/avatar_doctor.jpg',
            'avatar_frame' => 'frame-emerald',
            'xp' => 280,
            'level' => 3,
            'streak_days' => 7,
        ],
        [
            'id' => 9,
            'name' => 'Pedro Henrique',
            'email' => 'pedro.henrique@fuvest.com',
            'avatar_icon' => 'bi-backpack',
            'avatar' => 'assets/img/avatars/avatar_engineer.jpg',
            'avatar_frame' => 'frame-indigo',
            'xp' => 160,
            'level' => 2,
            'streak_days' => 3,
        ],
        [
            'id' => 10,
            'name' => 'Beatriz Santos',
            'email' => 'beatriz.santos@unicamp.com',
            'avatar_icon' => 'bi-person-circle',
            'avatar' => 'assets/img/default_avatar.jpg',
            'avatar_frame' => 'frame-indigo',
            'xp' => 80,
            'level' => 1,
            'streak_days' => 2,
        ],
    ];

    $stmtUser = $pdo->prepare("INSERT INTO users (id, name, email, password_hash, avatar, avatar_icon, avatar_frame, xp, level, streak_days, last_active_date, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'student')");

    foreach ($initialUsers as $u) {
        $stmtUser->execute([
            $u['id'],
            $u['name'],
            $u['email'],
            $defaultHash,
            $u['avatar'],
            $u['avatar_icon'],
            $u['avatar_frame'],
            $u['xp'],
            $u['level'],
            $u['streak_days'],
            $today
        ]);
    }

    // 2. Dar progresso inicial e conquistas para os usuários
    $stmtProg = $pdo->prepare("INSERT INTO user_progress (user_id, lesson_id, score_percent) VALUES (?, ?, ?)");
    $stmtAchiv = $pdo->prepare("INSERT INTO user_achievements (user_id, achievement_id) VALUES (?, ?)");

    $lessonIds = $pdo->query("SELECT id FROM lessons ORDER BY id LIMIT 10")->fetchAll(PDO::FETCH_COLUMN);
    $achievementIds = $pdo->query("SELECT id FROM achievements")->fetchAll(PDO::FETCH_COLUMN);

    foreach ($initialUsers as $u) {
        $uid = $u['id'];
        
        $completedCount = min(count($lessonIds), max(1, (int)($u['level'] / 2)));
        for ($i = 0; $i < $completedCount; $i++) {
            $lid = $lessonIds[$i];
            $stmtProg->execute([$uid, $lid, rand(85, 100)]);
        }

        if (!empty($achievementIds)) {
            $stmtAchiv->execute([$uid, $achievementIds[0]]);
            if ($u['streak_days'] >= 3 && isset($achievementIds[1])) {
                $stmtAchiv->execute([$uid, $achievementIds[1]]);
            }
        }
    }

    echo "Sucesso! " . count($initialUsers) . " usuários estudantes criados e configurados com sucesso.\n";

} catch (PDOException $e) {
    echo "Erro ao semear usuários: " . $e->getMessage() . "\n";
}
