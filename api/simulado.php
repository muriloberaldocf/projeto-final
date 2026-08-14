<?php
/**
 * API DE SIMULADOS CRONOMETRADOS — HIPOGABARITO
 * Suporta geração e correção de simulados para ENEM, FUVEST, UNICAMP, UNESP e MISTO.
 * Questões organizadas por nível: Fácil → Médio → Difícil.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Usuário não autenticado.']);
    exit;
}

$userId = (int)$_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];

try {
    // -------------------------------------------------------------
    // 1. REQUISIÇÃO DE FINALIZAÇÃO / CORREÇÃO DO SIMULADO (POST)
    // -------------------------------------------------------------
    $inputRaw = file_get_contents('php://input');
    $inputJson = json_decode($inputRaw, true);

    // Se for POST com respostas (ou ?action=finish)
    if ($method === 'POST' && (isset($inputJson['answers']) || isset($_POST['answers']) || isset($_GET['action']) && $_GET['action'] === 'finish')) {
        $answers = $inputJson['answers'] ?? $_POST['answers'] ?? [];
        $timeSpent = (int)($inputJson['time_spent'] ?? $_POST['time_spent'] ?? 0);
        $examType = trim($inputJson['exam'] ?? $_POST['exam'] ?? 'misto');

        $totalQuestions = count($answers);
        if ($totalQuestions === 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Nenhuma resposta enviada.'
            ]);
            exit;
        }

        $correctCount = 0;
        $bySubject = [];
        $byDifficulty = ['fácil' => ['correct' => 0, 'total' => 0], 'médio' => ['correct' => 0, 'total' => 0], 'difícil' => ['correct' => 0, 'total' => 0]];

        // Buscar detalhes das questões respondidas
        $questionIds = array_keys($answers);
        $placeholders = implode(',', array_fill(0, count($questionIds), '?'));

        $stmt = $pdo->prepare("
            SELECT q.id, q.correct_option, q.difficulty, s.name as subject_name
            FROM questions q
            LEFT JOIN lessons l ON q.lesson_id = l.id
            LEFT JOIN units u ON l.unit_id = u.id
            LEFT JOIN subjects s ON u.subject_id = s.id
            WHERE q.id IN ($placeholders)
        ");
        $stmt->execute(array_map('intval', $questionIds));
        $questionsList = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $questionsMap = [];
        foreach ($questionsList as $q) {
            $questionsMap[$q['id']] = $q;
        }

        $pdo->beginTransaction();

        $stmtAnswer = $pdo->prepare("
            INSERT INTO user_answers (user_id, question_id, chosen_option, is_correct, answered_at)
            VALUES (?, ?, ?, ?, NOW())
        ");

        foreach ($answers as $qId => $chosen) {
            $qId = (int)$qId;
            $chosen = strtolower(trim($chosen));
            $qInfo = $questionsMap[$qId] ?? null;

            if ($qInfo) {
                $isCorrect = ($chosen === strtolower(trim($qInfo['correct_option']))) ? 1 : 0;
                $subjectName = $qInfo['subject_name'] ?: 'Geral';
                $diff = $qInfo['difficulty'] ?: 'médio';

                if ($isCorrect) {
                    $correctCount++;
                }

                if (!isset($bySubject[$subjectName])) {
                    $bySubject[$subjectName] = ['correct' => 0, 'total' => 0];
                }
                $bySubject[$subjectName]['total']++;
                if ($isCorrect) {
                    $bySubject[$subjectName]['correct']++;
                }

                // Acumular por dificuldade
                if (isset($byDifficulty[$diff])) {
                    $byDifficulty[$diff]['total']++;
                    if ($isCorrect) {
                        $byDifficulty[$diff]['correct']++;
                    }
                }

                $stmtAnswer->execute([$userId, $qId, $chosen, $isCorrect]);
            }
        }

        $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;

        // Cálculo de Recompensa de XP
        // 3 XP por acerto + Bônus de 30 XP para >= 75% + Bônus de 40 XP para >= 90%
        $xpGained = ($correctCount * 3);
        if ($score >= 75) {
            $xpGained += 30;
        }
        if ($score >= 90) {
            $xpGained += 40;
        }

        // Registrar simulado na tabela simulados se existir
        try {
            $stmtSim = $pdo->prepare("
                INSERT INTO simulados (user_id, exam_type, total_questions, time_limit_min, score, total_correct, time_spent_sec, xp_earned, started_at, finished_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
            ");
            $stmtSim->execute([$userId, $examType, $totalQuestions, round($timeSpent / 60), $score, $correctCount, $timeSpent, $xpGained]);
        } catch (Exception $e) {
            // Ignora caso a tabela tenha diferenças de schema
        }

        // Atualizar XP e Nível do Usuário
        $stmtUser = $pdo->prepare("UPDATE users SET xp = xp + ? WHERE id = ?");
        $stmtUser->execute([$xpGained, $userId]);

        $stmtGetXp = $pdo->prepare("SELECT xp FROM users WHERE id = ?");
        $stmtGetXp->execute([$userId]);
        $currentXp = (int)$stmtGetXp->fetchColumn();
        $newLevel = floor($currentXp / 100) + 1;

        $stmtLevel = $pdo->prepare("UPDATE users SET level = ? WHERE id = ?");
        $stmtLevel->execute([$newLevel, $userId]);

        $pdo->commit();

        // Remover dificuldades com 0 questões do breakdown
        $byDiffClean = [];
        foreach ($byDifficulty as $key => $val) {
            if ($val['total'] > 0) {
                $byDiffClean[$key] = $val;
            }
        }

        echo json_encode([
            'status' => 'success',
            'score' => $score,
            'correct_answers' => $correctCount,
            'total_questions' => $totalQuestions,
            'xp_gained' => $xpGained,
            'time_spent' => $timeSpent,
            'by_subject' => $bySubject,
            'by_difficulty' => $byDiffClean
        ]);
        exit;
    }

    // -------------------------------------------------------------
    // 2. REQUISIÇÃO DE CARREGAMENTO / GERAÇÃO DE QUESTÕES (GET/POST)
    // Questões ordenadas por dificuldade: fácil → médio → difícil
    // -------------------------------------------------------------
    $exam = trim($_GET['exam'] ?? $_POST['exam'] ?? $_GET['exam_type'] ?? $_POST['exam_type'] ?? 'enem');
    $count = (int)($_GET['count'] ?? $_POST['count'] ?? $_GET['total_questions'] ?? $_POST['total_questions'] ?? 30);

    if ($count <= 0) $count = 30;

    // Distribuição proporcional por dificuldade:
    // 30% fácil, 45% médio, 25% difícil
    $countFacil   = max(1, round($count * 0.30));
    $countDificil = max(1, round($count * 0.25));
    $countMedio   = $count - $countFacil - $countDificil;
    if ($countMedio < 1) $countMedio = 1;

    $baseSelect = "
        SELECT 
            q.id,
            q.question_text,
            q.option_a,
            q.option_b,
            q.option_c,
            q.option_d,
            q.option_e,
            q.exam_source as source,
            q.difficulty,
            COALESCE(s.name, 'Geral') as subject,
            COALESCE(s.id, 1) as subject_id
        FROM questions q
        LEFT JOIN lessons l ON q.lesson_id = l.id
        LEFT JOIN units u ON l.unit_id = u.id
        LEFT JOIN subjects s ON u.subject_id = s.id
    ";

    // Construir filtro de banca
    $examFilter = "";
    if ($exam === 'enem') {
        $examFilter = " AND q.exam_source LIKE '%ENEM%'";
    } elseif ($exam === 'fuvest') {
        $examFilter = " AND (q.exam_source LIKE '%FUVEST%' OR q.exam_source LIKE '%USP%')";
    } elseif ($exam === 'unicamp') {
        $examFilter = " AND q.exam_source LIKE '%UNICAMP%'";
    } elseif ($exam === 'unesp') {
        $examFilter = " AND q.exam_source LIKE '%UNESP%'";
    }
    // misto = sem filtro de banca

    // Buscar cada nível separadamente
    $selectedQuestions = [];
    $allCollectedIds = [];

    $diffLevels = [
        'fácil'   => $countFacil,
        'médio'   => $countMedio,
        'difícil' => $countDificil,
    ];

    foreach ($diffLevels as $diff => $needed) {
        $excludeClause = "";
        if (!empty($allCollectedIds)) {
            $excludeClause = " AND q.id NOT IN (" . implode(',', array_map('intval', $allCollectedIds)) . ")";
        }

        $sql = $baseSelect . " WHERE q.difficulty = ?" . $examFilter . $excludeClause . " ORDER BY RAND() LIMIT ?";
        $stmtDiff = $pdo->prepare($sql);
        $stmtDiff->bindValue(1, $diff, PDO::PARAM_STR);
        $stmtDiff->bindValue(2, $needed, PDO::PARAM_INT);
        $stmtDiff->execute();
        $fetched = $stmtDiff->fetchAll(PDO::FETCH_ASSOC);

        foreach ($fetched as $q) {
            $selectedQuestions[] = $q;
            $allCollectedIds[] = (int)$q['id'];
        }
    }

    // Se algum nível retornou menos do que o esperado, preencher o restante randomicamente
    if (count($selectedQuestions) < $count) {
        $needed = $count - count($selectedQuestions);
        $excludeClause = "";
        if (!empty($allCollectedIds)) {
            $excludeClause = " WHERE q.id NOT IN (" . implode(',', array_map('intval', $allCollectedIds)) . ")";
            if (!empty($examFilter)) {
                $excludeClause .= $examFilter;
            }
        } elseif (!empty($examFilter)) {
            $excludeClause = " WHERE 1=1" . $examFilter;
        }

        $stmtFill = $pdo->prepare($baseSelect . $excludeClause . " ORDER BY RAND() LIMIT ?");
        $stmtFill->bindValue(1, $needed, PDO::PARAM_INT);
        $stmtFill->execute();
        $fillQuestions = $stmtFill->fetchAll(PDO::FETCH_ASSOC);

        $selectedQuestions = array_merge($selectedQuestions, $fillQuestions);
    }

    // Se banca exclusiva tem poucas questões, preencher com outras bancas
    if (count($selectedQuestions) < $count) {
        $needed = $count - count($selectedQuestions);
        $existingIds = array_column($selectedQuestions, 'id');
        $excludeClause = "";
        if (!empty($existingIds)) {
            $excludeClause = " WHERE q.id NOT IN (" . implode(',', array_map('intval', $existingIds)) . ")";
        }
        $stmtFill2 = $pdo->prepare($baseSelect . $excludeClause . " ORDER BY RAND() LIMIT ?");
        $stmtFill2->bindValue(1, $needed, PDO::PARAM_INT);
        $stmtFill2->execute();
        $fillQuestions2 = $stmtFill2->fetchAll(PDO::FETCH_ASSOC);
        $selectedQuestions = array_merge($selectedQuestions, $fillQuestions2);
    }

    // Ordenar resultado final por dificuldade: fácil → médio → difícil
    $diffOrder = ['fácil' => 1, 'médio' => 2, 'difícil' => 3];
    usort($selectedQuestions, function($a, $b) use ($diffOrder) {
        $oa = $diffOrder[$a['difficulty'] ?? 'médio'] ?? 2;
        $ob = $diffOrder[$b['difficulty'] ?? 'médio'] ?? 2;
        return $oa - $ob;
    });

    if (empty($selectedQuestions)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Nenhuma questão encontrada para este simulado.'
        ]);
        exit;
    }

    // Calcular contagem por dificuldade para o frontend
    $diffCounts = ['fácil' => 0, 'médio' => 0, 'difícil' => 0];
    foreach ($selectedQuestions as $q) {
        $d = $q['difficulty'] ?? 'médio';
        if (isset($diffCounts[$d])) $diffCounts[$d]++;
    }

    echo json_encode([
        'status' => 'success',
        'exam' => $exam,
        'count' => count($selectedQuestions),
        'difficulty_counts' => $diffCounts,
        'questions' => $selectedQuestions
    ]);

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Erro no servidor: ' . $e->getMessage()
    ]);
}
