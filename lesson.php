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
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom CSS AprovaQuest -->
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/lesson.css">
    <script>
        window.LESSON_ID = <?= $lessonId ?>;
        window.LESSON_MODE = '<?= htmlspecialchars($mode) ?>';
        window.SUBJECT_SLUG = '<?= htmlspecialchars($subjectSlug) ?>';
    </script>
</head>
<body class="lesson-page bg-mesh-gradient">

    <!-- HEADER DA LIÇÃO -->
    <header class="lesson-header bg-white border-bottom shadow-sm d-flex align-items-center gap-3">
        <a href="dashboard.php?subject=<?= urlencode($subjectSlug) ?>" class="btn btn-aprova-light btn-sm rounded-circle p-1 px-2" title="Voltar à Trilha">
            <i class="bi bi-x-lg"></i>
        </a>
        <img src="assets/img/hipogabarito_logo.png" alt="HipoGabarito" style="height: 32px; width: auto; object-fit: contain;">
        
        <div class="progress-bar-container flex-grow-1">
            <div class="progress-bar-fill" id="lessonProgress" style="background-color: var(--brand-primary);"></div>
        </div>
    </header>

    <!-- CORPO DA QUESTÃO -->
    <main class="lesson-body" id="lessonBody">
        <div class="mb-3 d-flex align-items-center gap-2">
            <span class="badge bg-light text-secondary border font-monospace" id="examTag">
                EXERCÍCIO
            </span>
            <span id="modeBadge"></span>
        </div>

        <h5 class="question-title fw-semibold text-dark mb-4" id="questionText">
            Carregando enunciado da questão...
        </h5>

        <!-- LISTA DAS 5 ALTERNATIVAS (A, B, C, D, E) -->
        <div class="options-list" id="optionsContainer">
            <!-- Gerado via JS em lesson_engine.js -->
        </div>

        <!-- DICA DE ATALHO -->
        <div class="text-muted small mt-3">
            <i class="bi bi-keyboard me-1"></i> Atalho: use as teclas <strong>1-5</strong> ou <strong>A-E</strong> e pressione <strong>Enter</strong>.
        </div>
    </main>



    <!-- RODAPÉ FIXO -->
    <footer class="lesson-footer bg-white border-top py-3">
        <div class="container d-flex justify-content-between align-items-center max-w-800">
            <div></div>
            <button type="button" id="btnCheck" class="btn btn-aprova-primary px-4 py-2 fs-6 fw-semibold" disabled>
                Verificar Resposta
            </button>
        </div>
    </footer>

    <!-- POPUP DRAWER DE FEEDBACK -->
    <div class="feedback-drawer" id="feedbackDrawer">
        <div class="drawer-content">
            <div>
                <div class="feedback-header" id="feedbackTitle">
                    Resposta Correta
                </div>
                <div class="explanation-box" id="explanationBox">
                    Explicação pedagógica...
                </div>
            </div>

            <button type="button" id="btnContinue" class="btn btn-aprova-primary px-4 py-2 fw-semibold" style="flex-shrink: 0;">
                Continuar <i class="bi bi-arrow-right ms-1"></i>
            </button>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/sound_effects.js"></script>
    <script src="assets/js/lesson_engine.js"></script>
</body>
</html>
