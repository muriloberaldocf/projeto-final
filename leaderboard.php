<?php
require_once __DIR__ . '/config/db.php';
checkAuth();

$userId = $_SESSION['user_id'];
$stmtUser = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmtUser->execute([$userId]);
$user = $stmtUser->fetch();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classificação — AprovaQuest</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom CSS AprovaQuest -->
    <link rel="stylesheet" href="assets/css/main.css">
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
                        <a href="leaderboard.php" class="nav-link active fw-semibold d-flex align-items-center gap-2 py-2" style="background-color: var(--brand-primary); color:#fff;">
                            <i class="bi bi-trophy text-warning"></i> Classificação
                        </a>
                        <a href="profile.php" class="nav-link text-secondary fw-semibold d-flex align-items-center gap-2 py-2">
                            <i class="bi bi-person text-primary"></i> Perfil & Avatares
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
                <div class="card card-aprova p-4 mb-4" style="border-left: 4px solid var(--brand-primary) !important;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold mb-1 text-dark">Classificação dos Estudantes</h4>
                            <p class="mb-0 text-muted small">Estudantes com maior pontuação de XP acumulado nos simulados.</p>
                        </div>
                        <i class="bi bi-trophy text-warning fs-2"></i>
                    </div>
                </div>

                <div class="card card-aprova p-0 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light border-bottom">
                                <tr>
                                    <th class="ps-4" style="width: 80px;">Posição</th>
                                    <th>Estudante & Avatar</th>
                                    <th>Nível</th>
                                    <th>Ofensiva</th>
                                    <th class="text-end pe-4">XP Total</th>
                                </tr>
                            </thead>
                            <tbody id="rankTableBody">
                                <!-- Carregado via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/sound_effects.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            fetch('api/get_leaderboard.php')
                .then(res => res.json())
                .then(data => {
                    if (!data.success) return;

                    const tbody = document.getElementById('rankTableBody');
                    tbody.innerHTML = '';

                    data.rankings.forEach((r, idx) => {
                        const pos = idx + 1;
                        let posBadge = `<span class="badge bg-light text-secondary border font-monospace">${pos}º</span>`;

                        if (pos === 1) { posBadge = `<span class="badge bg-warning text-dark fw-bold">1º</span>`; }
                        else if (pos === 2) { posBadge = `<span class="badge bg-secondary text-white fw-bold">2º</span>`; }
                        else if (pos === 3) { posBadge = `<span class="badge bg-danger-subtle text-danger border border-danger-subtle fw-bold">3º</span>`; }

                        const isMe = (r.id == data.current_user_id);
                        const avatarIcon = r.avatar_icon || 'bi-person-circle';

                        const tr = document.createElement('tr');
                        if (isMe) tr.className = 'table-primary-subtle fw-medium';

                        tr.innerHTML = `
                            <td class="ps-4">${posBadge}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-indigo-subtle border text-primary d-flex align-items-center justify-content-center fs-5" style="width:38px; height:38px; background-color:#eef2ff; color:#4f46e5;">
                                        <i class="bi ${avatarIcon}"></i>
                                    </div>
                                    <div>
                                        <span class="d-block fw-semibold text-dark">${r.name} ${isMe ? '<span class="badge bg-primary ms-1">Você</span>' : ''}</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-light text-secondary border font-monospace">Nível ${r.level}</span></td>
                            <td><span class="text-danger fw-medium"><i class="bi bi-fire"></i> ${r.streak_days}d</span></td>
                            <td class="text-end pe-4"><span class="fw-bold text-dark fs-6">${r.xp} XP</span></td>
                        `;
                        tbody.appendChild(tr);
                    });
                });
        });
    </script>
</body>
</html>
