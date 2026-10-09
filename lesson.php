<?php
require_once __DIR__ . '/config/db.php';
checkAuth();

$lessonId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$mode = $_GET['mode'] ?? '';

if (!$lessonId) {
    header("Location: dashboard.php");
    exit;
}

// Buscar o slug e nome da matéria desta lição para manter a navegação ao sair
$stmtLesson = $pdo->prepare("
    SELECT l.*, s.name AS subject_name, s.slug AS subject_slug 
    FROM lessons l 
    JOIN units u ON l.unit_id = u.id 
    JOIN subjects s ON u.subject_id = s.id 
    WHERE l.id = ?
");
$stmtLesson->execute([$lessonId]);
$lessonInfo = $stmtLesson->fetch();

$subjectSlug = $lessonInfo['subject_slug'] ?? 'matematica';
$subjectName = $lessonInfo['subject_name'] ?? 'Matemática';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($lessonInfo['title'] ?? 'Lição') ?> — HipoGabarito</title>

    <!-- ANTI-FLICKER -->
    <script>
        (function() {
            var t = localStorage.getItem('hipogabarito_theme');
            if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark', 'no-transition');
            }
        })();
    </script>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS HipoGabarito -->
    <link rel="stylesheet" href="assets/css/main.css?v=<?= time() ?>">
    <link rel="stylesheet" href="assets/css/lesson.css?v=<?= time() ?>">
    <link rel="stylesheet" href="assets/css/dark-mode.css?v=<?= time() ?>">
    <script>
        window.LESSON_ID = <?= $lessonId ?>;
        window.LESSON_MODE = '<?= htmlspecialchars($mode) ?>';
        window.SUBJECT_SLUG = '<?= htmlspecialchars($subjectSlug) ?>';
    </script>
</head>
<body class="lesson-page">

    <!-- HEADER DA LIÇÃO -->
    <header class="lesson-header">
        <div class="lesson-header-inner">
            <a href="dashboard.php?subject=<?= urlencode($subjectSlug) ?>" class="btn btn-aprova-light btn-sm rounded-circle p-1 px-2" title="Voltar à Trilha">
                <i class="bi bi-x-lg"></i>
            </a>
            
            <a href="dashboard.php" class="d-flex align-items-center text-decoration-none">
                <img src="assets/img/hipogabarito_logo.png?v=<?= time() ?>" alt="HipoGabarito" class="hipo-brand-logo" style="height: 32px; width: auto; object-fit: contain;">
            </a>
            
            <!-- Modo Teoria: Badge da Etapa 1 e Botão Pular -->
            <div id="theoryHeaderControls" class="d-flex align-items-center justify-content-end gap-2 flex-grow-1">
                <span class="badge bg-indigo-subtle text-indigo-700 font-monospace px-3 py-1.5 rounded-pill border border-indigo-200 d-none d-sm-inline-flex align-items-center gap-1">
                    <i class="bi bi-journal-text"></i> ETAPA 1: GUIA TEÓRICO
                </span>
                <button type="button" id="btnSkipToQuiz" class="btn btn-sm btn-light border fw-bold text-indigo-700 px-3 py-1.5 rounded-pill font-outfit">
                    Pular para Exercícios <i class="bi bi-arrow-right ms-1"></i>
                </button>
            </div>

            <!-- Modo Quiz: Barra de Progresso e Botão Rever Teoria (Oculto inicialmente) -->
            <div id="quizHeaderControls" class="d-flex align-items-center gap-3 flex-grow-1" style="display: none !important;">
                <div class="progress-bar-container flex-grow-1">
                    <div class="progress-bar-fill" id="lessonProgress"></div>
                </div>
                <button type="button" id="btnOpenReviewModal" class="btn btn-sm btn-light border text-indigo-700 fw-bold px-3 py-1.5 rounded-pill font-outfit text-nowrap" data-bs-toggle="modal" data-bs-target="#theoryReviewModal" title="Consultar Teoria">
                    <i class="bi bi-book-half me-1"></i> <span class="d-none d-md-inline">Rever</span> Teoria
                </button>
            </div>
        </div>
    </header>

    <!-- CONTAINER 1: TELA DEDICADA DE TEORIA & EXPLICAÇÕES (ETAPA 1) -->
    <section id="theoryScreen" class="theory-screen">
        <!-- Hero Header -->
        <div class="theory-hero-card" id="theoryHero">
            <div class="theory-subject-pill" id="theorySubjectBadge">
                <i class="bi bi-bookmark-fill"></i> <?= htmlspecialchars($subjectName) ?>
            </div>
            <h1 class="theory-lesson-title" id="theoryLessonTitle">
                <?= htmlspecialchars($lessonInfo['title'] ?? 'Carregando Lição...') ?>
            </h1>
            <div class="theory-unit-subtitle" id="theoryUnitSubtitle">
                Guia Teórico Completo & Resumo de Alto Rendimento para Vestibulares
            </div>

            <div class="theory-meta-row" id="theoryMetaRow">
                <span class="theory-meta-badge time-badge">
                    <i class="bi bi-clock-history"></i> ~3 min de leitura recomendada
                </span>
                <span class="theory-meta-badge xp-badge">
                    <i class="bi bi-lightning-charge-fill text-warning"></i> +35 XP ao concluir
                </span>
                <span class="theory-meta-badge">
                    <i class="bi bi-award-fill text-indigo-600"></i> Foco: ENEM, FUVEST & UNICAMP
                </span>
                <div id="theoryTodaMateriaBtn" style="display: inline-block;"></div>
            </div>
        </div>

        <!-- Grade de Cards Dinâmicos da Teoria -->
        <div id="theoryCardsContainer" class="theory-cards-grid">
            <div class="text-center py-5">
                <div class="spinner-border text-primary mb-3" role="status"></div>
                <h5 class="fw-bold text-muted font-outfit">Carregando guia teórico detalhado...</h5>
            </div>
        </div>

        <!-- Barra Inferior Fixa da Teoria -->
        <div class="theory-bottom-bar" id="theoryBottomBar">
            <div class="theory-bottom-bar-inner">
                <div class="d-none d-md-flex align-items-center gap-2">
                    <i class="bi bi-check2-circle text-success fs-4"></i>
                    <div>
                        <div class="fw-bold font-outfit text-dark fs-6">Leu o resumo com atenção?</div>
                        <div class="text-muted small">Avance para testar seu aprendizado com as questões reais.</div>
                    </div>
                </div>
                <button type="button" id="btnStartQuiz" class="btn-start-exercises ms-auto w-100 w-md-auto">
                    <span>Começar Exercícios</span>
                    <i class="bi bi-arrow-right-circle-fill fs-5"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- CONTAINER 2: CORPO DA QUESTÃO / QUIZ (ETAPA 2) -->
    <main class="lesson-body" id="quizScreen" style="display: none;">
        <!-- CARD DO ENUNCIADO DA QUESTÃO -->
        <div class="question-container-card mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="exam-badge-aprova" id="examTag">
                        <i class="bi bi-patch-question-fill text-indigo-600"></i> EXERCÍCIO
                    </span>
                    <span id="modeBadge"></span>
                </div>
                <div class="text-xs text-muted fw-bold font-monospace" id="questionCounter">
                    <!-- Atualizado via JS -->
                </div>
            </div>

            <h5 class="question-title-text" id="questionText">
                Carregando enunciado da questão...
            </h5>
        </div>

        <!-- LISTA DAS 5 ALTERNATIVAS (A, B, C, D, E) -->
        <div class="options-list mb-3" id="optionsContainer">
            <!-- Gerado via JS em lesson_engine.js -->
        </div>

        <!-- DICA DE ATALHO -->
        <div class="quiz-shortcut-bar small mt-2 d-flex align-items-center gap-2">
            <i class="bi bi-keyboard fs-6"></i>
            <span>Atalho: use as teclas <kbd class="quiz-kbd px-1.5 py-0.5 border rounded fw-bold shadow-sm">1-5</kbd> ou <kbd class="quiz-kbd px-1.5 py-0.5 border rounded fw-bold shadow-sm">A-E</kbd> e pressione <kbd class="quiz-kbd px-2 py-0.5 border rounded fw-bold shadow-sm">Enter ↵</kbd></span>
        </div>
    </main>

    <!-- RODAPÉ FIXO DO QUIZ -->
    <footer class="lesson-footer" id="quizFooter" style="display: none;">
        <div class="lesson-header-inner d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 font-outfit fw-bold" onclick="showTheoryScreen()">
                <i class="bi bi-journal-text me-1"></i> Voltar à Teoria
            </button>
            <button type="button" id="btnCheck" class="btn btn-aprova-primary px-4 py-2.5 fs-6 fw-bold" disabled>
                Verificar Resposta
            </button>
        </div>
    </footer>

    <!-- POPUP DRAWER DE FEEDBACK -->
    <div class="feedback-drawer" id="feedbackDrawer">
        <div class="drawer-content">
            <div class="drawer-header-row">
                <div class="feedback-header" id="feedbackTitle">
                    Resposta Correta
                </div>
                <button type="button" id="btnContinue" class="btn btn-aprova-primary px-4 py-2.5 fw-bold text-nowrap">
                    Continuar <i class="bi bi-arrow-right ms-1"></i>
                </button>
            </div>
            <div class="explanation-box" id="explanationBox">
                Explicação pedagógica...
            </div>
        </div>
    </div>

    <!-- MODAL DE REVISÃO DA TEORIA DURANTE O QUIZ -->
    <div class="modal fade" id="theoryReviewModal" tabindex="-1" aria-labelledby="theoryReviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable theory-modal-dialog">
            <div class="modal-content theory-modal-content">
                <div class="modal-header theory-modal-header">
                    <div class="d-flex align-items-center gap-2">
                        <div class="p-2 rounded-3 bg-indigo-50 text-indigo-600 fs-4">
                            <i class="bi bi-book-half"></i>
                        </div>
                        <div>
                            <h5 class="modal-title font-outfit fw-bold text-dark mb-0" id="theoryReviewModalLabel">
                                Consulta Teórica — <span id="reviewModalTitle"><?= htmlspecialchars($lessonInfo['title'] ?? 'Lição') ?></span>
                            </h5>
                            <span class="text-muted small">Revise os conceitos e fórmulas para responder à questão</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body theory-modal-body" id="reviewModalCardsBody">
                    <!-- Cloned / Populated by JS -->
                </div>
                <div class="modal-footer bg-white border-top">
                    <button type="button" class="btn btn-aprova-primary px-4 font-outfit fw-bold" data-bs-dismiss="modal">
                        Voltar à Questão <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/sound_effects.js"></script>
    <script src="assets/js/theory_parser.js?v=<?= time() ?>"></script>
    <script src="assets/js/lesson_engine.js?v=<?= time() ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (document.documentElement.classList.contains('dark')) {
                document.querySelectorAll('.hipo-brand-logo, img[src*="hipogabarito_logo"]').forEach(function(img) {
                    img.src = 'assets/img/hipogabarito_logo_dark.png?v=5';
                });
            }
        });
    </script>
</body>
</html>
