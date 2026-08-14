<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'start':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Method not allowed");
            }
            
            $exam_type = $_POST['exam_type'] ?? 'enem';
            $total_questions = (int)($_POST['total_questions'] ?? 15);
            
            $time_limits = [
                15 => 30,
                30 => 60,
                45 => 90,
                90 => 180
            ];
            $time_limit_min = $time_limits[$total_questions] ?? 30;
            
            $selected_questions = [];
            
            $base_query = "
                SELECT q.id, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.option_e, q.exam_source, s.name as subject_name, s.id as subject_id
                FROM questions q
                JOIN lessons l ON q.lesson_id = l.id
                JOIN units u ON l.unit_id = u.id
                JOIN subjects s ON u.subject_id = s.id
                WHERE q.id NOT IN (
                    SELECT question_id FROM user_answers 
                    WHERE user_id = ? AND is_correct = 1 AND answered_at >= DATE_SUB(NOW(), INTERVAL 3 DAY)
                )
            ";
            
            if (in_array($exam_type, ['enem', 'misto'])) {
                // Select proportionally from all 6 subjects
                $per_subject = max(1, round($total_questions / 6));
                $stmt = $pdo->prepare($base_query . " AND s.id = ? ORDER BY RAND() LIMIT ?");
                
                for ($i = 1; $i <= 6; $i++) {
                    $stmt->execute([$user_id, $i, $per_subject]);
                    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $selected_questions = array_merge($selected_questions, $res);
                }
                
                // Trim if we have too many, or we can just leave it if it's slightly off due to rounding, 
                // but let's strictly limit to total_questions
                shuffle($selected_questions);
                $selected_questions = array_slice($selected_questions, 0, $total_questions);
            } else {
                // fuvest, unicamp, unesp
                $source = strtoupper($exam_type);
                $stmt = $pdo->prepare($base_query . " AND q.exam_source LIKE ? ORDER BY RAND() LIMIT ?");
                $stmt->execute([$user_id, "%$source%", $total_questions]);
                $selected_questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Fill remainder if not enough
                if (count($selected_questions) < $total_questions) {
                    $needed = $total_questions - count($selected_questions);
                    $ids_to_exclude = array_column($selected_questions, 'id');
                    $exclude_sql = count($ids_to_exclude) > 0 ? " AND q.id NOT IN (" . implode(',', array_map('intval', $ids_to_exclude)) . ") " : "";
                    
                    $stmt_fill = $pdo->prepare($base_query . $exclude_sql . " ORDER BY RAND() LIMIT ?");
                    $stmt_fill->execute([$user_id, $needed]);
                    $fill_res = $stmt_fill->fetchAll(PDO::FETCH_ASSOC);
                    $selected_questions = array_merge($selected_questions, $fill_res);
                }
            }
            
            if (count($selected_questions) == 0) {
                throw new Exception("No questions available to generate simulado.");
            }
            
            $actual_total = count($selected_questions);
            
            $pdo->beginTransaction();
            
            $stmt = $pdo->prepare("INSERT INTO simulados (user_id, exam_type, total_questions, time_limit_min, started_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$user_id, $exam_type, $actual_total, $time_limit_min]);
            $simulado_id = $pdo->lastInsertId();
            
            $stmt_ans = $pdo->prepare("INSERT INTO simulado_answers (simulado_id, question_id, subject_id) VALUES (?, ?, ?)");
            $return_questions = [];
            foreach ($selected_questions as $q) {
                $stmt_ans->execute([$simulado_id, $q['id'], $q['subject_id']]);
                
                unset($q['subject_id']); // Not needed in output
                $return_questions[] = $q;
            }
            
            $pdo->commit();
            
            echo json_encode([
                'simulado_id' => $simulado_id,
                'questions' => $return_questions
            ]);
            break;
            
        case 'answer':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Method not allowed");
            }
            
            $simulado_id = $_POST['simulado_id'] ?? null;
            $question_id = $_POST['question_id'] ?? null;
            $chosen_option = $_POST['chosen_option'] ?? null;
            
            if (!$simulado_id || !$question_id || !$chosen_option) {
                throw new Exception("Missing parameters");
            }
            
            // Check if simulado belongs to user
            $stmt = $pdo->prepare("SELECT id FROM simulados WHERE id = ? AND user_id = ?");
            $stmt->execute([$simulado_id, $user_id]);
            if (!$stmt->fetch()) {
                throw new Exception("Simulado not found or unauthorized");
            }
            
            // Get correct option
            $stmt = $pdo->prepare("SELECT correct_option FROM questions WHERE id = ?");
            $stmt->execute([$question_id]);
            $q = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$q) {
                throw new Exception("Question not found");
            }
            
            $is_correct = ($chosen_option === $q['correct_option']) ? 1 : 0;
            
            $stmt = $pdo->prepare("UPDATE simulado_answers SET chosen_option = ?, is_correct = ? WHERE simulado_id = ? AND question_id = ?");
            $stmt->execute([$chosen_option, $is_correct, $simulado_id, $question_id]);
            
            echo json_encode(['success' => true]);
            break;
            
        case 'finish':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Method not allowed");
            }
            
            $simulado_id = $_POST['simulado_id'] ?? null;
            $time_spent_sec = (int)($_POST['time_spent_sec'] ?? 0);
            
            if (!$simulado_id) {
                throw new Exception("Missing simulado_id");
            }
            
            $stmt = $pdo->prepare("SELECT * FROM simulados WHERE id = ? AND user_id = ? AND finished_at IS NULL");
            $stmt->execute([$simulado_id, $user_id]);
            $simulado = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$simulado) {
                throw new Exception("Simulado not found, unauthorized, or already finished");
            }
            
            $pdo->beginTransaction();
            
            // Calculate total correct and breakdown
            $stmt = $pdo->prepare("
                SELECT sa.subject_id, s.name as subject_name, SUM(sa.is_correct) as correct, COUNT(sa.id) as total
                FROM simulado_answers sa
                JOIN subjects s ON sa.subject_id = s.id
                WHERE sa.simulado_id = ?
                GROUP BY sa.subject_id, s.name
            ");
            $stmt->execute([$simulado_id]);
            $breakdown_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $total_correct = 0;
            $breakdown = [];
            foreach ($breakdown_data as $row) {
                $total_correct += $row['correct'];
                $breakdown[] = [
                    'subject_name' => $row['subject_name'],
                    'correct' => (int)$row['correct'],
                    'total' => (int)$row['total'],
                    'percentage' => $row['total'] > 0 ? round(($row['correct'] / $row['total']) * 100, 2) : 0
                ];
            }
            
            $total_questions = $simulado['total_questions'];
            $score = $total_questions > 0 ? round(($total_correct / $total_questions) * 100, 2) : 0;
            
            // Calculate XP
            $xp_earned = $total_correct * 2;
            if ($score >= 90) {
                $xp_earned += 50;
            } elseif ($score >= 70) {
                $xp_earned += 20;
            }
            
            // Update simulados
            $stmt = $pdo->prepare("UPDATE simulados SET score = ?, total_correct = ?, time_spent_sec = ?, xp_earned = ?, finished_at = NOW() WHERE id = ?");
            $stmt->execute([$score, $total_correct, $time_spent_sec, $xp_earned, $simulado_id]);
            
            // Update user XP and Level
            $stmt = $pdo->prepare("UPDATE users SET xp = xp + ? WHERE id = ?");
            $stmt->execute([$xp_earned, $user_id]);
            
            $stmt = $pdo->prepare("SELECT xp FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
            $new_level = floor($user_data['xp'] / 100) + 1;
            
            $stmt = $pdo->prepare("UPDATE users SET level = ? WHERE id = ?");
            $stmt->execute([$new_level, $user_id]);
            
            $pdo->commit();
            
            echo json_encode([
                'score' => $score,
                'total_correct' => $total_correct,
                'total_questions' => $total_questions,
                'xp_earned' => $xp_earned,
                'time_spent_sec' => $time_spent_sec,
                'breakdown' => $breakdown
            ]);
            break;
            
        case 'history':
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                throw new Exception("Method not allowed");
            }
            
            $stmt = $pdo->prepare("
                SELECT id, exam_type, total_questions, score, total_correct, time_spent_sec, xp_earned, finished_at
                FROM simulados
                WHERE user_id = ? AND finished_at IS NOT NULL
                ORDER BY finished_at DESC
                LIMIT 10
            ");
            $stmt->execute([$user_id]);
            $history = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode($history);
            break;
            
        default:
            throw new Exception("Invalid action");
    }
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
