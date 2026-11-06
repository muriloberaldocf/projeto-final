<?php
/**
 * API DE CENTRAL DE NOTIFICAÇÕES - HIPOGABARITO
 * Retorna notificações de solicitações de amizade, lições concluídas e conquistas do usuário.
 */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

$userId = $_SESSION['user_id'];

try {
    $notifications = [];

    // 1. Buscando Solicitações de Amizade Pendentes
    $stmtFriends = $pdo->prepare("
        SELECT u.id as sender_id, u.name, u.avatar, u.avatar_icon, f.created_at
        FROM user_friends f
        JOIN users u ON u.id = f.user_id
        WHERE f.friend_id = ? AND f.status = 'pending'
        ORDER BY f.created_at DESC
    ");
    $stmtFriends->execute([$userId]);
    $pendingFriends = $stmtFriends->fetchAll(PDO::FETCH_ASSOC);

    foreach ($pendingFriends as $pf) {
        $notifications[] = [
            'id' => 'friend_req_' . $pf['sender_id'],
            'type' => 'friend_request',
            'sender_id' => $pf['sender_id'],
            'title' => 'Nova Solicitação de Amizade',
            'message' => "{$pf['name']} enviou uma solicitação de amizade para estudar com você!",
            'icon' => 'bi-person-plus-fill',
            'badge_color' => 'bg-amber-100 text-amber-600',
            'time' => date('d/m H:i', strtotime($pf['created_at'])),
            'avatar' => $pf['avatar'],
            'name' => $pf['name']
        ];
    }

    // 2. Buscando Últimas Lições/Tarefas Concluídas
    $stmtLessons = $pdo->prepare("
        SELECT l.title, up.completed_at, up.score_percent
        FROM user_progress up
        JOIN lessons l ON l.id = up.lesson_id
        WHERE up.user_id = ?
        ORDER BY up.completed_at DESC
        LIMIT 3
    ");
    $stmtLessons->execute([$userId]);
    $completedTasks = $stmtLessons->fetchAll(PDO::FETCH_ASSOC);

    foreach ($completedTasks as $ct) {
        $notifications[] = [
            'id' => 'task_' . md5($ct['title'] . $ct['completed_at']),
            'type' => 'task_completed',
            'title' => 'Tarefa Concluída com Sucesso!',
            'message' => "Você concluiu a lição \"{$ct['title']}\" com {$ct['score_percent']}% de aproveitamento.",
            'icon' => 'bi-check-circle-fill',
            'badge_color' => 'bg-emerald-100 text-emerald-600',
            'time' => date('d/m H:i', strtotime($ct['completed_at'] ?? 'now'))
        ];
    }

    // 3. Notificação de Ofensiva (Streak)
    $streakStatus = syncUserStreak($pdo, $userId);
    if ($streakStatus['is_active_today'] && $streakStatus['streak_days'] >= 1) {
        $notifications[] = [
            'id' => 'streak_active',
            'type' => 'streak',
            'title' => 'Ofensiva Diária Ativa! 🔥',
            'message' => "Você está há {$streakStatus['streak_days']} dia(s) consecutivos estudando sem parar! Continue assim.",
            'icon' => 'bi-fire',
            'badge_color' => 'bg-amber-100 text-amber-500',
            'time' => 'Hoje'
        ];
    }

    // 4. Notificações de "Boa! 👊" recebidos no Mural
    $stmtKudos = $pdo->prepare("
        SELECT u.id as sender_id, u.name, u.avatar, e.title as event_title, k.created_at
        FROM friend_mural_kudos k
        JOIN friend_mural_events e ON e.id = k.event_id
        JOIN users u ON u.id = k.from_user_id
        WHERE e.user_id = ? AND k.from_user_id != ?
        ORDER BY k.created_at DESC
        LIMIT 5
    ");
    $stmtKudos->execute([$userId, $userId]);
    $kudosReceived = $stmtKudos->fetchAll(PDO::FETCH_ASSOC);

    foreach ($kudosReceived as $kr) {
        $notifications[] = [
            'id' => 'kudo_' . md5($kr['name'] . $kr['event_title'] . $kr['created_at']),
            'type' => 'mural_kudo',
            'title' => 'Mandou um Boa! 👊',
            'message' => "{$kr['name']} parabenizou você pela sua conquista: \"{$kr['event_title']}\"!",
            'icon' => 'bi-hand-thumbs-up-fill',
            'badge_color' => 'bg-indigo-100 text-indigo-600',
            'time' => date('d/m H:i', strtotime($kr['created_at']))
        ];
    }

    echo json_encode([
        'success' => true,
        'notifications' => $notifications,
        'unread_count' => count($pendingFriends) + count($kudosReceived)
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao buscar notificações: ' . $e->getMessage()]);
}
