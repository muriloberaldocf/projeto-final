<?php
require_once __DIR__ . '/config/db.php';
checkAuth();

$userId = $_SESSION['user_id'];

// Estatísticas do Usuário
$stmtUser = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmtUser->execute([$userId]);
$user = $stmtUser->fetch();

$currentAvatar = $user['avatar_icon'] ?? 'bi-person-circle';
$userXp = (int) ($user['xp'] ?? 0);

// Catálogo de Avatares
$avatars = [
    ['icon' => 'bi-person-circle', 'name' => 'Estudante Padrão', 'xp' => 0],
    ['icon' => 'bi-backpack', 'name' => 'Mochileiro Focado', 'xp' => 20],
    ['icon' => 'bi-mortarboard', 'name' => 'Formando Vestibulando', 'xp' => 50],
    ['icon' => 'bi-rocket-takeoff', 'name' => 'Foguete da Aprovação', 'xp' => 100],
    ['icon' => 'bi-lightning-charge', 'name' => 'Mago do Conhecimento', 'xp' => 200],
    ['icon' => 'bi-award', 'name' => 'Campeão de Simulados', 'xp' => 350],
    ['icon' => 'bi-gem', 'name' => 'Diamante Medicina', 'xp' => 500],
    ['icon' => 'bi-incognito', 'name' => 'Mestre Misterioso', 'xp' => 750],
    ['icon' => 'bi-crown', 'name' => 'Rei da Aprovação', 'xp' => 1000]
];

// Buscar Conquistas
$stmtAch = $pdo->query("SELECT * FROM achievements");
$achievements = $stmtAch->fetchAll();

$stmtMyAch = $pdo->prepare("SELECT achievement_id FROM user_achievements WHERE user_id = ?");
$stmtMyAch->execute([$userId]);
$myAchIds = $stmtMyAch->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil & Avatares — AprovaQuest</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom CSS AprovaQuest -->
    <link rel="stylesheet" href="assets/css/main.css">
    <style>
        .avatar-card {
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            padding: 16px;
            text-align: center;
            transition: all 0.2s ease;
            background: #ffffff;
        }

        .avatar-card.unlocked {
            cursor: pointer;
        }

        .avatar-card.unlocked:hover {
            transform: translateY(-4px);
            border-color: var(--brand-primary);
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.12);
        }

        .avatar-card.equipped {
            border-color: #4f46e5;
            background: #eef2ff;
        }

        .avatar-card.locked {
            opacity: 0.6;
            background: #f9fafb;
        }
    </style>
</head>
<body class="bg-mesh-gradient">

    <!-- NAVBAR INDEPENDENTE -->
    <nav class="navbar navbar-expand-lg navbar-aprova sticky-top">
        <div class="container">
            <a class="brand-logo-aprova" href="dashboard.php">
                <div class="brand-icon-box">
                    <i class="bi bi-check-all"></i>
                </div>
                <span>AprovaQuest</span>
            </a>
            <div class="d-flex align-items-center gap-2 ms-auto">
                <div class="stat-pill-aprova" title="Ofensiva">
                    <i class="bi bi-fire text-danger"></i>
                    <span><?= htmlspecialchars($user['streak_days'] ?? 1) ?> d</span>
                </div>
                <div class="stat-pill-aprova" title="XP">
                    <i class="bi bi-lightning-charge-fill text-warning"></i>
                    <span><?= htmlspecialchars($user['xp'] ?? 0) ?> XP</span>
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row g-4">
            <!-- SIDEBAR -->
            <div class="col-lg-3">
                <div class="card card-aprova p-3">
                    <div class="nav flex-column nav-pills gap-1">
                        <a href="dashboard.php" class="nav-link text-secondary fw-semibold d-flex align-items-center gap-2 py-2">
                            <i class="bi bi-map"></i> Trilha de Estudos
                        </a>
                        <a href="leaderboard.php" class="nav-link text-secondary fw-semibold d-flex align-items-center gap-2 py-2">
                            <i class="bi bi-trophy text-warning"></i> Classificação
                        </a>
                        <a href="profile.php" class="nav-link active fw-semibold d-flex align-items-center gap-2 py-2" style="background-color: var(--brand-primary); color:#fff;">
                            <i class="bi bi-person"></i> Perfil & Avatares
                        </a>
                        <?php if (($user['role'] ?? '') === 'admin'): ?>
                            <a href="admin.php" class="nav-link text-primary fw-semibold d-flex align-items-center gap-2 py-2 bg-indigo-subtle rounded mt-2" style="background-color: #eef2ff;">
                                <i class="bi bi-gear"></i> Painel Professor
                            </a>
                        <?php endif; ?>

                        <hr class="my-2 text-muted">

                        <a href="api/auth.php?action=logout" class="nav-link text-muted small d-flex align-items-center gap-2 py-1.5">
                            <i class="bi bi-box-arrow-right"></i> Sair
                        </a>
                    </div>
                </div>
            </div>

            <!-- CONTEÚDO PRINCIPAL -->
            <div class="col-lg-9">
                <!-- CARD PERFIL COM AVATAR EQUIPADO -->
                <div class="card card-aprova p-4 mb-4">
                    <div class="d-flex align-items-center gap-4">
                        <div class="rounded-circle bg-indigo-subtle border text-primary d-flex align-items-center justify-content-center display-5 shadow-sm" style="width: 80px; height: 80px; background-color: #eef2ff; color: #4f46e5;">
                            <i class="bi <?= htmlspecialchars($currentAvatar) ?>"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-1 text-dark"><?= htmlspecialchars($user['name']) ?></h4>
                            <span class="badge bg-light text-secondary border me-2 font-monospace">
                                Nível <?= $user['level'] ?>
                            </span>
                            <span class="badge bg-warning-subtle text-dark border border-warning-subtle font-monospace me-2">
                                <i class="bi bi-lightning-charge-fill text-warning me-1"></i> <?= $userXp ?> XP Acumulados
                            </span>
                        </div>
                    </div>
                </div>

                <!-- LOJA DE AVATARES POR XP -->
                <div class="card card-aprova p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-shop text-primary me-2"></i> Loja de Avatares por XP</h5>
                            <p class="text-muted small mb-0">Use seu XP acumulado nos simulados para desbloquear e equipar novos avatares exclusivos!</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <?php foreach ($avatars as $av): ?>
                            <?php 
                                $isUnlocked = ($userXp >= $av['xp']);
                                $isEquipped = ($currentAvatar === $av['icon']);
                            ?>
                            <div class="col-md-4 col-sm-6">
                                <div class="avatar-card <?= $isEquipped ? 'equipped' : ($isUnlocked ? 'unlocked' : 'locked') ?>" 
                                     <?= $isUnlocked ? "onclick=\"equipAvatar('{$av['icon']}')\"" : '' ?>>
                                    <div class="display-6 mb-2 text-primary">
                                        <i class="bi <?= $av['icon'] ?>"></i>
                                    </div>
                                    <h6 class="fw-semibold text-dark mb-1"><?= htmlspecialchars($av['name']) ?></h6>
                                    
                                    <?php if ($isEquipped): ?>
                                        <span class="badge bg-primary text-white py-1 px-2 small"><i class="bi bi-check-lg me-1"></i> Equipado</span>
                                    <?php elseif ($isUnlocked): ?>
                                        <button class="btn btn-outline-primary btn-sm w-100 mt-2 py-1 fw-semibold">Equipar</button>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted border py-1 px-2 small"><i class="bi bi-lock-fill me-1"></i> <?= $av['xp'] ?> XP</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- CONQUISTAS -->
                <h6 class="fw-bold mb-3 text-dark">Conquistas & Selos</h6>
                <div class="row g-3">
                    <?php foreach ($achievements as $ach): ?>
                        <?php $unlocked = in_array($ach['id'], $myAchIds); ?>
                        <div class="col-md-6">
                            <div class="card card-aprova p-3 <?= $unlocked ? 'border-warning-subtle bg-warning-subtle' : 'opacity-50' ?>">
                                <div class="d-flex align-items-center gap-3">
                                    <i class="bi <?= $unlocked ? 'bi-award text-warning fs-3' : 'bi-lock text-muted fs-3' ?>"></i>
                                    <div>
                                        <h6 class="mb-0 fw-semibold text-dark"><?= htmlspecialchars($ach['title']) ?></h6>
                                        <p class="mb-0 text-muted small"><?= htmlspecialchars($ach['description']) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/sound_effects.js"></script>
    <script>
        function equipAvatar(icon) {
            sounds.playClick();
            fetch('api/update_avatar.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ avatar_icon: icon })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message);
                }
            });
        }
    </script>
</body>
</html>
