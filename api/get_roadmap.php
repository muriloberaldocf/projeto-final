<?php
/**
 * API DE TRILHA DE APRENDIZADO (ROADMAP) - SENAI PREP
 * Todos os temas 100% desbloqueados por padrão.
 * Tópicos concluídos desbloqueiam o Modo Desafio Avançado (Questões mais difíceis).
 */
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

$userId = $_SESSION['user_id'] ?? 1;
$subjectSlug = $_GET['subject'] ?? 'matematica';

// Busca dados da matéria selecionada
$stmtSub = $pdo->prepare("SELECT * FROM subjects WHERE slug = ?");
$stmtSub->execute([$subjectSlug]);
$subject = $stmtSub->fetch();

if (!$subject) {
    $stmtSub->execute(['matematica']);
    $subject = $stmtSub->fetch();
}

// Buscar todas as matérias para as abas
$allSubjects = $pdo->query("SELECT id, name, slug, color_hex, icon FROM subjects ORDER BY order_index ASC")->fetchAll();

// Buscar unidades e lições da matéria atual
$stmtUnits = $pdo->prepare("SELECT * FROM units WHERE subject_id = ? ORDER BY order_index ASC");
$stmtUnits->execute([$subject['id']]);
$units = $stmtUnits->fetchAll();

// Buscar progresso do usuário
$stmtProg = $pdo->prepare("SELECT lesson_id, score_percent FROM user_progress WHERE user_id = ?");
$stmtProg->execute([$userId]);
$completedLessonsRaw = $stmtProg->fetchAll();
$completedLessonsMap = [];
foreach ($completedLessonsRaw as $row) {
    $completedLessonsMap[$row['lesson_id']] = $row['score_percent'];
}

$tree = [];

foreach ($units as $unit) {
    $stmtLessons = $pdo->prepare("SELECT * FROM lessons WHERE unit_id = ? ORDER BY order_index ASC");
    $stmtLessons->execute([$unit['id']]);
    $lessons = $stmtLessons->fetchAll();

    $unitLessons = [];
    foreach ($lessons as $lesson) {
        $isCompleted = isset($completedLessonsMap[$lesson['id']]);

        // TODOS OS TEMAS 100% DESBLOQUEADOS!
        $unitLessons[] = [
            'id' => $lesson['id'],
            'title' => $lesson['title'],
            'intro_text' => $lesson['intro_text'] ?? null,
            'video_url' => $lesson['video_url'] ?? null,
            'video_title' => $lesson['video_title'] ?? null,
            'xp_reward' => $isCompleted ? 35 : $lesson['xp_reward'],
            'is_completed' => $isCompleted,
            'is_unlocked' => true, // Livre acesso para qualquer tópico!
            'advanced_unlocked' => $isCompleted, // Modo Desafio ativado se já concluiu
            'score' => $completedLessonsMap[$lesson['id']] ?? 0
        ];
    }

    $tree[] = [
        'id' => $unit['id'],
        'title' => $unit['title'],
        'description' => $unit['description'],
        'lessons' => $unitLessons
    ];
}

// Buscar estatísticas do usuário
$stmtUser = $pdo->prepare("SELECT name, avatar, xp, level, streak_days, hearts FROM users WHERE id = ?");
$stmtUser->execute([$userId]);
$userData = $stmtUser->fetch();

echo json_encode([
    'success' => true,
    'user' => $userData,
    'subjects' => $allSubjects,
    'current_subject' => $subject,
    'units' => $tree
]);
