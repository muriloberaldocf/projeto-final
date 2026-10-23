<?php
/**
 * API DE MÉTRICAS & RAIO-X PEDAGÓGICO — HIPOGABARITO
 * Retorna dados consolidados, KPIs, distribuições por matéria, dificuldade,
 * competências de redação e histórico temporal para o aluno logado.
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
$period = trim($_GET['period'] ?? 'all'); // '7d', '30d', 'all'

// Cláusula de período para respostas
$answersDateFilter = "";
$simuladosDateFilter = "";
$redacoesDateFilter = "";

if ($period === '7d') {
    $answersDateFilter = " AND ua.answered_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
    $simuladosDateFilter = " AND started_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
    $redacoesDateFilter = " AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
} elseif ($period === '30d') {
    $answersDateFilter = " AND ua.answered_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
    $simuladosDateFilter = " AND started_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
    $redacoesDateFilter = " AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
}

try {
    // -------------------------------------------------------------------------
    // 1. DADOS DO USUÁRIO
    // -------------------------------------------------------------------------
    $stmtUser = $pdo->prepare("SELECT id, name, xp, level, streak_days, last_active_date FROM users WHERE id = ?");
    $stmtUser->execute([$userId]);
    $userData = $stmtUser->fetch(PDO::FETCH_ASSOC) ?: [
        'id' => $userId, 'name' => 'Estudante', 'xp' => 0, 'level' => 1, 'streak_days' => 0
    ];

    // -------------------------------------------------------------------------
    // 2. QUESTÕES & PRECISÃO GERAL (user_answers)
    // -------------------------------------------------------------------------
    $stmtAnswers = $pdo->prepare("
        SELECT 
            COUNT(*) as total_answers,
            COALESCE(SUM(CASE WHEN ua.is_correct = 1 THEN 1 ELSE 0 END), 0) as total_correct
        FROM user_answers ua
        WHERE ua.user_id = ? $answersDateFilter
    ");
    $stmtAnswers->execute([$userId]);
    $answersStats = $stmtAnswers->fetch(PDO::FETCH_ASSOC);

    $totalAnswers = (int)($answersStats['total_answers'] ?? 0);
    $totalCorrect = (int)($answersStats['total_correct'] ?? 0);
    $accuracyPercent = $totalAnswers > 0 ? round(($totalCorrect / $totalAnswers) * 100, 1) : 0;

    // -------------------------------------------------------------------------
    // 3. LIÇÕES CONCLUÍDAS (user_progress)
    // -------------------------------------------------------------------------
    $stmtProg = $pdo->prepare("SELECT COUNT(*) FROM user_progress WHERE user_id = ?");
    $stmtProg->execute([$userId]);
    $lessonsCompleted = (int)$stmtProg->fetchColumn();

    // -------------------------------------------------------------------------
    // 4. SIMULADOS CRONOMETRADOS (simulados)
    // -------------------------------------------------------------------------
    $stmtSimulados = $pdo->prepare("
        SELECT 
            COUNT(*) as total_simulados,
            COALESCE(AVG(score), 0) as avg_score,
            COALESCE(MAX(score), 0) as max_score,
            COALESCE(SUM(time_spent_sec), 0) as total_time_sec,
            COALESCE(SUM(total_questions), 0) as total_sim_questions,
            COALESCE(SUM(total_correct), 0) as total_sim_correct
        FROM simulados
        WHERE user_id = ? $simuladosDateFilter
    ");
    $stmtSimulados->execute([$userId]);
    $simStats = $stmtSimulados->fetch(PDO::FETCH_ASSOC);

    $totalSimulados = (int)($simStats['total_simulados'] ?? 0);
    $avgSimuladoScore = round((float)($simStats['avg_score'] ?? 0), 1);
    $maxSimuladoScore = (int)($simStats['max_score'] ?? 0);
    $totalSimQuestions = (int)($simStats['total_sim_questions'] ?? 0);
    $totalTimeSec = (int)($simStats['total_time_sec'] ?? 0);
    
    // Tempo médio por questão (em segundos)
    $avgTimePerQuestion = ($totalSimQuestions > 0 && $totalTimeSec > 0) 
        ? round($totalTimeSec / $totalSimQuestions) 
        : 0;

    // Histórico recente de simulados (últimos 5)
    $stmtRecentSim = $pdo->prepare("
        SELECT id, exam_type, total_questions, total_correct, score, time_spent_sec, xp_earned, started_at
        FROM simulados
        WHERE user_id = ?
        ORDER BY started_at DESC
        LIMIT 5
    ");
    $stmtRecentSim->execute([$userId]);
    $recentSimulados = $stmtRecentSim->fetchAll(PDO::FETCH_ASSOC);

    // -------------------------------------------------------------------------
    // 5. REDAÇÕES (redacoes)
    // -------------------------------------------------------------------------
    $stmtRedacoes = $pdo->prepare("
        SELECT 
            COUNT(*) as total_redacoes,
            COALESCE(AVG(nota_final), 0) as avg_nota_final,
            COALESCE(MAX(nota_final), 0) as max_nota_final,
            COALESCE(AVG(nota_c1), 0) as avg_c1,
            COALESCE(AVG(nota_c2), 0) as avg_c2,
            COALESCE(AVG(nota_c3), 0) as avg_c3,
            COALESCE(AVG(nota_c4), 0) as avg_c4,
            COALESCE(AVG(nota_c5), 0) as avg_c5,
            COALESCE(AVG(probabilidade_ia), 0) as avg_prob_ia
        FROM redacoes
        WHERE user_id = ? $redacoesDateFilter
    ");
    $stmtRedacoes->execute([$userId]);
    $redStats = $stmtRedacoes->fetch(PDO::FETCH_ASSOC);

    $totalRedacoes = (int)($redStats['total_redacoes'] ?? 0);
    $avgRedacaoNota = round((float)($redStats['avg_nota_final'] ?? 0));
    $maxRedacaoNota = (int)($redStats['max_nota_final'] ?? 0);

    $competenciasAverages = [
        'c1' => round((float)($redStats['avg_c1'] ?? 0)),
        'c2' => round((float)($redStats['avg_c2'] ?? 0)),
        'c3' => round((float)($redStats['avg_c3'] ?? 0)),
        'c4' => round((float)($redStats['avg_c4'] ?? 0)),
        'c5' => round((float)($redStats['avg_c5'] ?? 0)),
    ];

    // Histórico recente de redações (últimas 6 para o gráfico de linha)
    $stmtRecentRed = $pdo->prepare("
        SELECT id, titulo, banca, nota_final, nota_c1, nota_c2, nota_c3, nota_c4, nota_c5, probabilidade_ia, created_at
        FROM redacoes
        WHERE user_id = ?
        ORDER BY created_at ASC
        LIMIT 10
    ");
    $stmtRecentRed->execute([$userId]);
    $recentRedacoes = $stmtRecentRed->fetchAll(PDO::FETCH_ASSOC);

    // -------------------------------------------------------------------------
    // 6. DOMÍNIO POR MATÉRIA (Radar / Breakdown)
    // -------------------------------------------------------------------------
    // Cruza user_answers com questions -> lessons -> units -> subjects
    $stmtSubjects = $pdo->prepare("
        SELECT 
            s.id as subject_id,
            s.name as subject_name,
            s.color_hex,
            COUNT(ua.id) as total_answered,
            COALESCE(SUM(CASE WHEN ua.is_correct = 1 THEN 1 ELSE 0 END), 0) as total_correct
        FROM subjects s
        LEFT JOIN units u ON u.subject_id = s.id
        LEFT JOIN lessons l ON l.unit_id = u.id
        LEFT JOIN questions q ON q.lesson_id = l.id
        LEFT JOIN user_answers ua ON ua.question_id = q.id AND ua.user_id = ? $answersDateFilter
        GROUP BY s.id, s.name, s.color_hex, s.order_index
        ORDER BY s.order_index ASC, s.name ASC
    ");
    $stmtSubjects->execute([$userId]);
    $subjectsRaw = $stmtSubjects->fetchAll(PDO::FETCH_ASSOC);

    $subjectLabels = [];
    $subjectScores = [];
    $subjectBreakdown = [];
    $bestSubject = null;
    $worstSubject = null;
    $maxRate = -1;
    $minRate = 999;

    foreach ($subjectsRaw as $sub) {
        $subTotal = (int)$sub['total_answered'];
        $subCorrect = (int)$sub['total_correct'];
        $rate = $subTotal > 0 ? round(($subCorrect / $subTotal) * 100, 1) : 0;

        $subjectLabels[] = $sub['subject_name'];
        $subjectScores[] = $rate;

        $item = [
            'id' => (int)$sub['subject_id'],
            'name' => $sub['subject_name'],
            'color' => $sub['color_hex'] ?: '#6366f1',
            'total' => $subTotal,
            'correct' => $subCorrect,
            'rate' => $rate
        ];
        $subjectBreakdown[] = $item;

        if ($subTotal > 0) {
            if ($rate > $maxRate) {
                $maxRate = $rate;
                $bestSubject = $item;
            }
            if ($rate < $minRate) {
                $minRate = $rate;
                $worstSubject = $item;
            }
        }
    }

    // -------------------------------------------------------------------------
    // 7. TAXA DE ACERTO POR DIFICULDADE (Fácil, Médio, Difícil)
    // -------------------------------------------------------------------------
    $stmtDifficulty = $pdo->prepare("
        SELECT 
            COALESCE(q.difficulty, 'médio') as diff,
            COUNT(ua.id) as total,
            COALESCE(SUM(CASE WHEN ua.is_correct = 1 THEN 1 ELSE 0 END), 0) as correct
        FROM user_answers ua
        JOIN questions q ON ua.question_id = q.id
        WHERE ua.user_id = ? $answersDateFilter
        GROUP BY diff
    ");
    $stmtDifficulty->execute([$userId]);
    $diffRows = $stmtDifficulty->fetchAll(PDO::FETCH_ASSOC);

    $difficultyStats = [
        'facil' => ['name' => 'Fácil', 'total' => 0, 'correct' => 0, 'rate' => 0],
        'medio' => ['name' => 'Médio', 'total' => 0, 'correct' => 0, 'rate' => 0],
        'dificil' => ['name' => 'Difícil', 'total' => 0, 'correct' => 0, 'rate' => 0],
    ];

    foreach ($diffRows as $d) {
        $dName = strtolower(trim($d['diff']));
        $key = 'medio';
        if (str_contains($dName, 'facil') || str_contains($dName, 'fácil')) {
            $key = 'facil';
        } elseif (str_contains($dName, 'dificil') || str_contains($dName, 'difícil')) {
            $key = 'dificil';
        }
        $tot = (int)$d['total'];
        $cor = (int)$d['correct'];
        $difficultyStats[$key]['total'] += $tot;
        $difficultyStats[$key]['correct'] += $cor;
    }

    foreach ($difficultyStats as $k => $v) {
        $difficultyStats[$k]['rate'] = $v['total'] > 0 ? round(($v['correct'] / $v['total']) * 100, 1) : 0;
    }

    // -------------------------------------------------------------------------
    // 8. ATIVIDADE DIÁRIA E CONSISTÊNCIA DE XP (Últimos 14 Dias)
    // -------------------------------------------------------------------------
    $stmtDaily = $pdo->prepare("
        SELECT activity_date, xp_earned, lessons_completed
        FROM user_daily_activity
        WHERE user_id = ? AND activity_date >= DATE_SUB(CURDATE(), INTERVAL 13 DAY)
        ORDER BY activity_date ASC
    ");
    $stmtDaily->execute([$userId]);
    $dailyActivityRows = $stmtDaily->fetchAll(PDO::FETCH_ASSOC);

    // Mapear últimos 14 dias completos mesmo que sem registro no banco
    $dailyHistory = [];
    $dailyMap = [];
    foreach ($dailyActivityRows as $row) {
        $dailyMap[$row['activity_date']] = [
            'xp' => (int)$row['xp_earned'],
            'lessons' => (int)$row['lessons_completed']
        ];
    }

    for ($i = 13; $i >= 0; $i--) {
        $d = date('Y-m-d', strtotime("-$i days"));
        $label = date('d/m', strtotime($d));
        $xp = $dailyMap[$d]['xp'] ?? 0;
        $lessons = $dailyMap[$d]['lessons'] ?? 0;

        $dailyHistory[] = [
            'date' => $d,
            'label' => $label,
            'xp' => $xp,
            'lessons' => $lessons,
            'goal_met' => ($xp >= 50)
        ];
    }

    // -------------------------------------------------------------------------
    // 9. DIAGNÓSTICO INTELIGENTE & RECOMENDAÇÕES PEDAGÓGICAS
    // -------------------------------------------------------------------------
    $diagnostico = [];

    // Nível de precisão
    if ($totalAnswers === 0) {
        $diagnostico['status_geral'] = 'Iniciante';
        $diagnostico['mensagem'] = 'Comece a responder lições e simulados para gerar seu Raio-X completo!';
    } elseif ($accuracyPercent >= 80) {
        $diagnostico['status_geral'] = 'Alto Rendimento (Nível Medicina/Poli)';
        $diagnostico['mensagem'] = 'Excelente precisão! Seu ritmo é altamente competitivo para os cursos mais concorridos.';
    } elseif ($accuracyPercent >= 60) {
        $diagnostico['status_geral'] = 'Firme no Caminho (Em Evolução)';
        $diagnostico['mensagem'] = 'Bom desempenho base! Foque na resolução das questões médias e difíceis para consolidar o salto de nota.';
    } else {
        $diagnostico['status_geral'] = 'Atenção ao Reforço';
        $diagnostico['mensagem'] = 'Sua taxa de acerto está abaixo de 60%. Vale a pena revisar a teoria das unidades e refazer lições concluídas.';
    }

    // Ponto mais forte e ponto de atenção
    $diagnostico['ponto_forte'] = $bestSubject ? [
        'nome' => $bestSubject['name'],
        'taxa' => $bestSubject['rate'],
        'descricao' => "Domínio consolidado com {$bestSubject['rate']}% de acertos."
    ] : null;

    // Menor competência da redação (se tiver redação)
    $piorCompetencia = null;
    if ($totalRedacoes > 0) {
        $minCVal = 999;
        $compNomes = [
            'c1' => 'Gramática & Norma Culta (C1)',
            'c2' => 'Compreensão & Repertório (C2)',
            'c3' => 'Projeto de Texto & Argumentação (C3)',
            'c4' => 'Coesão & Conectivos (C4)',
            'c5' => 'Proposta de Intervenção (C5)',
        ];
        foreach ($competenciasAverages as $cKey => $cVal) {
            if ($cVal < $minCVal) {
                $minCVal = $cVal;
                $piorCompetencia = [
                    'key' => $cKey,
                    'nome' => $compNomes[$cKey] ?? $cKey,
                    'nota' => $cVal
                ];
            }
        }
    }

    $diagnostico['ponto_atencao'] = null;
    if ($worstSubject && $worstSubject['rate'] < 70) {
        $diagnostico['ponto_atencao'] = [
            'tipo' => 'materia',
            'nome' => $worstSubject['name'],
            'taxa' => $worstSubject['rate'],
            'acao' => 'Revisar lições desta disciplina na Trilha de Estudos.',
            'link' => 'dashboard.php'
        ];
    } elseif ($piorCompetencia && $piorCompetencia['nota'] < 160) {
        $diagnostico['ponto_atencao'] = [
            'tipo' => 'redacao',
            'nome' => $piorCompetencia['nome'],
            'taxa' => $piorCompetencia['nota'] . '/200 pts',
            'acao' => 'Escrever uma nova redação focando em aprimorar este critério.',
            'link' => 'redacao.php'
        ];
    }

    // -------------------------------------------------------------------------
    // RETORNO JSON FINAL
    // -------------------------------------------------------------------------
    echo json_encode([
        'status' => 'success',
        'period' => $period,
        'user' => [
            'name' => $userData['name'],
            'xp' => (int)$userData['xp'],
            'level' => (int)$userData['level'],
            'streak' => (int)$userData['streak_days']
        ],
        'kpis' => [
            'total_answers' => $totalAnswers,
            'total_correct' => $totalCorrect,
            'accuracy_percent' => $accuracyPercent,
            'lessons_completed' => $lessonsCompleted,
            'total_simulados' => $totalSimulados,
            'avg_simulado_score' => $avgSimuladoScore,
            'max_simulado_score' => $maxSimuladoScore,
            'avg_time_per_question' => $avgTimePerQuestion,
            'total_redacoes' => $totalRedacoes,
            'avg_redacao_nota' => $avgRedacaoNota,
            'max_redacao_nota' => $maxRedacaoNota,
        ],
        'radar_chart' => [
            'labels' => $subjectLabels,
            'scores' => $subjectScores,
            'breakdown' => $subjectBreakdown
        ],
        'difficulty_stats' => $difficultyStats,
        'redacoes_chart' => [
            'competencias' => $competenciasAverages,
            'recent' => $recentRedacoes
        ],
        'inep_benchmark' => [
            'source' => 'Microdados INEP / educabR',
            'redacao_media' => 649,
            'competencias' => [
                'c1' => 126,
                'c2' => 147,
                'c3' => 124,
                'c4' => 136,
                'c5' => 117
            ],
            'areas' => [
                'natureza'   => ['media' => 492.0, 'desvio' => 79.8, 'q25' => 440.0, 'mediana' => 489.0, 'q75' => 541.0, 'max' => 817.0],
                'humanas'    => ['media' => 529.0, 'desvio' => 81.7, 'q25' => 480.0, 'mediana' => 535.0, 'q75' => 584.0, 'max' => 823.0],
                'linguagens' => ['media' => 520.0, 'desvio' => 70.4, 'q25' => 476.0, 'mediana' => 524.0, 'q75' => 568.0, 'max' => 731.0],
                'matematica' => ['media' => 520.0, 'desvio' => 121.0, 'q25' => 426.0, 'mediana' => 507.0, 'q75' => 608.0, 'max' => 945.0],
                'redacao'    => ['media' => 649.0, 'desvio' => 201.0, 'q25' => 520.0, 'mediana' => 640.0, 'q75' => 820.0, 'max' => 1000.0]
            ]
        ],
        'daily_chart' => $dailyHistory,
        'recent_simulados' => $recentSimulados,
        'diagnostico' => $diagnostico
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Erro interno ao processar métricas: ' . $e->getMessage()
    ]);
}
