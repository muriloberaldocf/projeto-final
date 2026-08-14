<?php
require_once __DIR__ . '/config/db.php';
checkAuth();

$lessonId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$mode = $_GET['mode'] ?? '';

if (!$lessonId) {
    header("Location: dashboard.php");
    exit;
}

// Buscar o slug da matéria desta lição para manter a navegação ao sair
$stmtLesson = $pdo->prepare("
    SELECT l.*, s.slug AS subject_slug 
    FROM lessons l 
    JOIN units u ON l.unit_id = u.id 
    JOIN subjects s ON u.subject_id = s.id 
    WHERE l.id = ?
");
$stmtLesson->execute([$lessonId]);
$lessonInfo = $stmtLesson->fetch();

$subjectSlug = $lessonInfo['subject_slug'] ?? 'matematica';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercício — HipoGabarito</title>

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
            <img src="assets/img/hipogabarito_logo.png" alt="HipoGabarito" style="height: 32px; width: auto; object-fit: contain;">
            
            <div class="progress-bar-container">
                <div class="progress-bar-fill" id="lessonProgress"></div>
            </div>
        </div>
    </header>

    <!-- CORPO DA QUESTÃO -->
    <main class="lesson-body" id="lessonBody">
        <!-- CAIXA DE EXPLICAÇÃO TEÓRICA E VÍDEO-AULA NO CABEÇALHO DA LIÇÃO -->
        <div id="lessonIntroBox" class="bg-white rounded-4 border-2 border-indigo-200 p-3 p-md-4 mb-4 shadow-sm" style="display: none;">
            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-indigo-100">
                <div class="d-flex align-items-center gap-2 text-indigo-900 fw-bold">
                    <i class="bi bi-journal-bookmark-fill text-indigo-600 fs-5"></i>
                    <span>Explicação Teórica & Leitura Recomendada</span>
                </div>
                <button type="button" id="btnToggleIntro" class="btn btn-sm btn-light border text-indigo-700 fw-semibold py-1 px-3.5 text-xs rounded-pill">
                    <i class="bi bi-chevron-up me-1"></i> Ocultar
                </button>
            </div>

            <!-- CONTEÚDO EXPANDÍVEL -->
            <div id="introContentContainer">
                <!-- LEITURA RECOMENDADA / ARTIGO -->
                <div id="videoContainer" class="mb-3" style="display: none;"></div>

                <!-- RESUMO TEÓRICO APROFUNDADO -->
                <div id="introContentText" class="text-secondary small leading-relaxed font-medium pt-2"></div>
            </div>
        </div>

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
        <div class="text-muted small mt-2 d-flex align-items-center gap-2">
            <i class="bi bi-keyboard text-secondary fs-6"></i>
            <span>Atalho: use as teclas <kbd class="px-1.5 py-0.5 bg-white text-dark border rounded fw-bold shadow-sm">1-5</kbd> ou <kbd class="px-1.5 py-0.5 bg-white text-dark border rounded fw-bold shadow-sm">A-E</kbd> e pressione <kbd class="px-2 py-0.5 bg-white text-dark border rounded fw-bold shadow-sm">Enter ↵</kbd></span>
        </div>
    </main>

    <!-- RODAPÉ FIXO -->
    <footer class="lesson-footer">
        <div class="lesson-header-inner d-flex justify-content-between align-items-center">
            <div></div>
            <button type="button" id="btnCheck" class="btn btn-aprova-primary px-4 py-2.5 fs-6 fw-bold" disabled>
                Verificar Resposta
            </button>
        </div>
    </footer>

    <!-- POPUP DRAWER DE FEEDBACK -->
    <div class="feedback-drawer" id="feedbackDrawer">
        <div class="drawer-content">
            <div class="flex-grow-1">
                <div class="feedback-header" id="feedbackTitle">
                    Resposta Correta
                </div>
                <div class="explanation-box mt-2" id="explanationBox">
                    Explicação pedagógica...
                </div>
            </div>

            <button type="button" id="btnContinue" class="btn btn-aprova-primary px-4 py-2.5 fw-bold text-nowrap" style="flex-shrink: 0;">
                Continuar <i class="bi bi-arrow-right ms-1"></i>
            </button>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/sound_effects.js"></script>
    <script src="assets/js/lesson_engine.js?v=<?= time() ?>"></script>
</body>
</html>
