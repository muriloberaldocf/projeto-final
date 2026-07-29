<?php
require_once __DIR__ . '/config/db.php';
checkAuth();

$userId = $_SESSION['user_id'];

// Buscar dados atualizados do usuário
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trilha de Estudos — AprovaQuest</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom CSS AprovaQuest -->
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <style>
        .unit-header-toggle {
            cursor: pointer;
            user-select: none;
            transition: background-color 0.2s ease;
        }

        .unit-header-toggle:hover {
            background-color: rgba(79, 70, 229, 0.03);
        }

        .chevron-icon {
            transition: transform 0.25s ease;
        }

        .unit-header-toggle[aria-expanded="false"] .chevron-icon {
            transform: rotate(-90deg);
        }

        .unit-header-toggle[aria-expanded="true"] .chevron-icon {
            transform: rotate(0deg);
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
                <div class="stat-pill-aprova" title="Ofensiva diária">
                    <i class="bi bi-fire text-danger"></i>
                    <span><?= htmlspecialchars($user['streak_days'] ?? 1) ?> d</span>
                </div>
                <div class="stat-pill-aprova" title="Experiência acumulada">
                    <i class="bi bi-lightning-charge-fill text-warning"></i>
                    <span><?= htmlspecialchars($user['xp'] ?? 0) ?> XP</span>
                </div>
            </div>
        </div>
    </nav>

    <!-- LAYOUT PRINCIPAL -->
    <div class="container py-4">
        <div class="row g-4">
            <!-- SIDEBAR -->
            <div class="col-lg-3">
                <div class="card card-aprova p-3">
                    <div class="nav flex-column nav-pills gap-1">
                        <a href="dashboard.php" class="nav-link active fw-semibold d-flex align-items-center gap-2 py-2" style="background-color: var(--brand-primary); color: #fff;">
                            <i class="bi bi-map"></i> Trilha de Estudos
                        </a>
                        <a href="course_guide.php" class="nav-link text-secondary fw-semibold d-flex align-items-center gap-2 py-2">
                            <i class="bi bi-journal-bookmark-fill text-warning"></i> Guia de Cursos & Cortes
                        </a>
                        <a href="leaderboard.php" class="nav-link text-secondary fw-semibold d-flex align-items-center gap-2 py-2">
                            <i class="bi bi-trophy text-warning"></i> Classificação
                        </a>
                        <a href="profile.php" class="nav-link text-secondary fw-semibold d-flex align-items-center gap-2 py-2">
                            <i class="bi bi-person text-primary"></i> Perfil
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
                <!-- SELETOR DE MATÉRIAS -->
                <div class="d-flex gap-2 overflow-x-auto pb-2 mb-3" id="subjectSelector">
                    <!-- Carregado via JS -->
                </div>

                <!-- LISTA DE UNIDADES EXPANDÍVEIS (ACCORDION) -->
                <div id="roadmapTree" class="d-flex flex-column gap-3">
                    <!-- Carregado via JS -->
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/sound_effects.js"></script>
    <script>
        let currentSubject = 'matematica';

        function loadRoadmap(subjectSlug) {
            currentSubject = subjectSlug;
            
            fetch(`api/get_roadmap.php?subject=${subjectSlug}`)
                .then(res => res.json())
                .then(data => {
                    if (!data.success) return;

                    // Renderizar Abas de Matérias
                    const selector = document.getElementById('subjectSelector');
                    selector.innerHTML = '';
                    data.subjects.forEach(sub => {
                        const tab = document.createElement('a');
                        tab.href = '#';
                        tab.className = `subject-pill-aprova ${sub.slug === currentSubject ? 'active' : ''}`;
                        tab.innerHTML = `${sub.name}`;
                        tab.onclick = (e) => {
                            e.preventDefault();
                            sounds.playClick();
                            loadRoadmap(sub.slug);
                        };
                        selector.appendChild(tab);
                    });

                    // Renderizar Múltiplas Unidades Retráteis (Accordion)
                    const treeContainer = document.getElementById('roadmapTree');
                    treeContainer.innerHTML = '';

                    data.units.forEach((unit, unitIdx) => {
                        const unitCollapseId = `unitCollapse_${unit.id}`;
                        const isExpanded = (unitIdx === 0); // Primeira unidade aberta por padrão

                        const unitCard = document.createElement('div');
                        unitCard.className = 'card card-aprova p-0 overflow-hidden';
                        unitCard.style.borderLeft = '4px solid var(--brand-primary)';

                        // Cabeçalho Clicável da Unidade com Ícone de Chevron
                        const unitHeader = document.createElement('div');
                        unitHeader.className = 'card-body p-4 unit-header-toggle d-flex justify-content-between align-items-center';
                        unitHeader.setAttribute('data-bs-toggle', 'collapse');
                        unitHeader.setAttribute('data-bs-target', `#${unitCollapseId}`);
                        unitHeader.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
                        unitHeader.onclick = () => sounds.playClick();

                        unitHeader.innerHTML = `
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 p-2 text-primary" style="background-color: #eef2ff;">
                                    <i class="bi bi-journal-bookmark fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1 text-dark">${unit.title}</h5>
                                    <p class="text-muted mb-0 small">${unit.description}</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-light text-secondary border font-monospace me-2">${unit.lessons.length} Tópicos</span>
                                <i class="bi bi-chevron-down fs-5 text-secondary chevron-icon"></i>
                            </div>
                        `;
                        unitCard.appendChild(unitHeader);

                        // Conteúdo Retrátil da Unidade
                        const collapseContainer = document.createElement('div');
                        collapseContainer.id = unitCollapseId;
                        collapseContainer.className = `collapse ${isExpanded ? 'show' : ''}`;

                        const lessonListContainer = document.createElement('div');
                        lessonListContainer.className = 'card-body pt-0 pb-3 px-4 d-flex flex-column gap-2 border-top bg-light-subtle';

                        unit.lessons.forEach((lesson) => {
                            const item = document.createElement('div');
                            
                            let iconHtml = '<i class="bi bi-play-circle-fill text-primary fs-5"></i>';
                            let practiceBtn = `<button onclick="startLesson(${lesson.id})" class="btn btn-aprova-primary btn-sm fw-medium">Praticar (+${lesson.xp_reward} XP)</button>`;
                            let bossBtn = `<button disabled class="btn btn-outline-secondary btn-sm opacity-50" title="Conclua a lição para desbloquear o Desafio Boss!"><i class="bi bi-lock-fill me-1"></i> Boss (+50 XP)</button>`;

                            if (lesson.is_completed) {
                                iconHtml = '<i class="bi bi-check-circle-fill text-success fs-5"></i>';
                                bossBtn = `<button onclick="startLesson(${lesson.id}, 'boss')" class="btn btn-danger btn-sm fw-bold shadow-sm"><i class="bi bi-unlock-fill me-1"></i> Desafio Boss (+50 XP)</button>`;
                            }

                            item.className = `lesson-row-aprova ${lesson.is_completed ? 'completed' : 'unlocked'} bg-white mt-1`;
                            item.innerHTML = `
                                <div class="d-flex align-items-center gap-3">
                                    ${iconHtml}
                                    <div>
                                        <h6 class="mb-0 fw-semibold text-dark">${lesson.title}</h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    ${practiceBtn}
                                    ${bossBtn}
                                </div>
                            `;
                            lessonListContainer.appendChild(item);
                        });

                        collapseContainer.appendChild(lessonListContainer);
                        unitCard.appendChild(collapseContainer);
                        treeContainer.appendChild(unitCard);
                    });
                });
        }

        function startLesson(id, mode = '') {
            sounds.playClick();
            window.location.href = `lesson.php?id=${id}&mode=${mode}`;
        }

        document.addEventListener('DOMContentLoaded', () => loadRoadmap('matematica'));
    </script>
</body>
</html>
