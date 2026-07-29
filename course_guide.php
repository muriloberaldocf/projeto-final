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
    <title>Guia de Cursos & Notas de Corte — AprovaQuest</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom CSS AprovaQuest -->
    <link rel="stylesheet" href="assets/css/main.css">
    <style>
        .course-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(229, 231, 235, 0.8);
            border-radius: 16px;
            padding: 20px;
            transition: all 0.2s ease;
        }

        .course-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(79, 70, 229, 0.12);
            border-color: var(--brand-primary);
        }

        .score-pill-ampla {
            background-color: #eef2ff;
            color: #4f46e5;
            font-weight: 700;
            padding: 8px 14px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .score-pill-cotas {
            background-color: #fef3c7;
            color: #92400e;
            font-weight: 700;
            padding: 8px 14px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
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
                        <a href="course_guide.php" class="nav-link active fw-semibold d-flex align-items-center gap-2 py-2" style="background-color: var(--brand-primary); color:#fff;">
                            <i class="bi bi-journal-bookmark-fill text-warning"></i> Guia de Cursos & Cortes
                        </a>
                        <a href="leaderboard.php" class="nav-link text-secondary fw-semibold d-flex align-items-center gap-2 py-2">
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
                            <h4 class="fw-bold mb-1 text-dark">Guia de Cursos & Notas de Corte (SISU/Vestibulares)</h4>
                            <p class="mb-0 text-muted small">Consulte a pontuação de corte das maiores universidades de Norte a Sul do Brasil.</p>
                        </div>
                        <i class="bi bi-mortarboard text-primary fs-1 opacity-75"></i>
                    </div>
                </div>

                <!-- BARRA DE BUSCA E FILTROS DE REGIÃO -->
                <div class="card card-aprova p-3 mb-4">
                    <div class="row g-3">
                        <div class="col-md-7">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                                <input type="text" id="inputSearch" class="form-control form-control-aprova border-start-0 ps-0" placeholder="Buscar por Curso (ex: Medicina) ou Universidade (ex: USP)..." oninput="filterCourses()">
                            </div>
                        </div>

                        <div class="col-md-5">
                            <select id="selectRegion" class="form-select form-control-aprova" onchange="filterCourses()">
                                <option value="todas">Todas as Regiões do Brasil</option>
                                <option value="Norte">Região Norte (UFAM, UFPA...)</option>
                                <option value="Nordeste">Região Nordeste (UFPE, UFC, UFBA...)</option>
                                <option value="Centro-Oeste">Região Centro-Oeste (UnB, UFG...)</option>
                                <option value="Sudeste">Região Sudeste (USP, UNICAMP, UFRJ...)</option>
                                <option value="Sul">Região Sul (UFRGS, UFSC, UFPR...)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- RESULTADOS DOS CURSOS -->
                <div id="coursesList" class="row g-3">
                    <!-- Carregado via JS -->
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/sound_effects.js"></script>
    <script>
        function filterCourses() {
            const search = document.getElementById('inputSearch').value;
            const region = document.getElementById('selectRegion').value;

            fetch(`api/get_course_guides.php?search=${encodeURIComponent(search)}&region=${encodeURIComponent(region)}`)
                .then(res => res.json())
                .then(data => {
                    if (!data.success) return;

                    const container = document.getElementById('coursesList');
                    container.innerHTML = '';

                    if (data.courses.length === 0) {
                        container.innerHTML = `
                            <div class="col-12 text-center py-5">
                                <i class="bi bi-journal-x display-4 text-muted mb-2"></i>
                                <h5 class="fw-semibold text-muted">Nenhum curso encontrado para este filtro</h5>
                            </div>
                        `;
                        return;
                    }

                    data.courses.forEach(c => {
                        const cardCol = document.createElement('div');
                        cardCol.className = 'col-md-6';
                        cardCol.innerHTML = `
                            <div class="course-card h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge bg-indigo-subtle text-primary border border-indigo-subtle fw-semibold px-2.5 py-1" style="background-color:#eef2ff; color:#4f46e5; border-color:#c7d2fe;">
                                            ${c.degree} • ${c.shift}
                                        </span>
                                        <span class="badge bg-light text-secondary border font-monospace">${c.region}</span>
                                    </div>

                                    <h5 class="fw-bold text-dark mb-1">${c.course_name}</h5>
                                    <p class="text-secondary small mb-3">
                                        <i class="bi bi-building text-primary me-1"></i> <strong>${c.university_name}</strong> — ${c.campus_city}
                                    </p>
                                </div>

                                <div>
                                    <div class="d-flex gap-2 mb-3">
                                        <div class="score-pill-ampla flex-fill">
                                            <i class="bi bi-star-fill"></i>
                                            <div>
                                                <span class="d-block text-uppercase small" style="font-size:10px;">Ampla Concorrência</span>
                                                <span class="fs-6">${c.cutoff_score} pts</span>
                                            </div>
                                        </div>

                                        <div class="score-pill-cotas flex-fill">
                                            <i class="bi bi-person-hearts"></i>
                                            <div>
                                                <span class="d-block text-uppercase small" style="font-size:10px;">Cotas (EP/L5)</span>
                                                <span class="fs-6">${c.quota_cutoff_score} pts</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between text-muted small pt-2 border-top">
                                        <span><i class="bi bi-people me-1"></i> ${c.vacancies} Vagas</span>
                                        <span><i class="bi bi-clock-history me-1"></i> ${c.duration_semesters} Semestres</span>
                                    </div>
                                </div>
                            </div>
                        `;
                        container.appendChild(cardCol);
                    });
                });
        }

        document.addEventListener('DOMContentLoaded', filterCourses);
    </script>
</body>
</html>
