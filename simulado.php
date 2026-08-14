<?php
require_once __DIR__ . '/config/db.php';
checkAuth();

$userId = $_SESSION['user_id'];

// Buscar histórico de simulados finalizados do usuário
$stmtHistory = $pdo->prepare("
    SELECT * FROM simulados 
    WHERE user_id = ? AND finished_at IS NOT NULL AND total_questions > 0
    ORDER BY finished_at DESC
    LIMIT 20
");
$stmtHistory->execute([$userId]);
$simuladosHistory = $stmtHistory->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = 'Simulado Cronometrado — HipoGabarito';
require_once __DIR__ . '/includes/header.php';
?>
    <link rel="stylesheet" href="assets/css/simulado.css?v=<?= time() ?>">

    <!-- LAYOUT PRINCIPAL DO SITE -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-all">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- SIDEBAR MODULAR COM DESTAQUE EM ROXO -->
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

            <!-- CONTEÚDO PRINCIPAL DOS SIMULADOS -->
            <section id="simuladoSection" class="lg:col-span-9 space-y-6 transition-all duration-300">

                <!-- STATE 1: Configuração do Simulado & Histórico -->
                <div id="screenSelect" class="space-y-6">
                    <div class="bg-white rounded-3xl border-2 border-slate-200 p-6 sm:p-8 shadow-[0_4px_0_0_#e2e8f0] space-y-8">
                        <!-- Banner de Título -->
                        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-6 rounded-2xl shadow-md flex items-center justify-between gap-4">
                            <div>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-white/20 text-amber-200 uppercase tracking-wider mb-2">
                                    <i class="bi bi-clock-history"></i> PROVA SIMULADA CONTEXTUAL
                                </span>
                                <h1 class="text-2xl sm:text-3xl font-extrabold font-outfit text-white mb-1">Simulado Cronometrado</h1>
                                <p class="text-xs sm:text-sm text-indigo-100 font-medium mb-0">Treine com tempo real de prova (média de 3 min por questão) e aprovação oficial (75%+).</p>
                            </div>
                            <div class="w-14 h-14 rounded-2xl bg-white/10 text-amber-300 flex items-center justify-center text-3xl shrink-0 hidden sm:flex">
                                <i class="bi bi-stopwatch"></i>
                            </div>
                        </div>

                        <!-- Escolha do Tipo de Prova -->
                        <div>
                            <h2 class="text-lg font-extrabold font-outfit mb-4 text-slate-800 flex items-center gap-2">
                                <i class="bi bi-1-circle-fill text-indigo-600"></i> Escolha a Banca ou Formato
                            </h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                <div class="exam-card selected cursor-pointer p-4 rounded-2xl border-2 border-indigo-600 bg-indigo-50/50 hover:border-indigo-600 transition-all" onclick="selectExamType('enem', this)">
                                    <div class="exam-icon w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-xl mb-3"><i class="bi bi-journal-text"></i></div>
                                    <h3 class="font-outfit font-extrabold text-base text-slate-900 mb-1">ENEM</h3>
                                    <p class="text-xs text-slate-500 mb-0">Questões contextualizadas no modelo oficial do ENEM</p>
                                </div>
                                <div class="exam-card cursor-pointer p-4 rounded-2xl border-2 border-slate-200 hover:border-indigo-400 transition-all" onclick="selectExamType('fuvest', this)">
                                    <div class="exam-icon w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-xl mb-3"><i class="bi bi-building"></i></div>
                                    <h3 class="font-outfit font-extrabold text-base text-slate-900 mb-1">FUVEST / USP</h3>
                                    <p class="text-xs text-slate-500 mb-0">Questões analíticas e conteudistas da USP</p>
                                </div>
                                <div class="exam-card cursor-pointer p-4 rounded-2xl border-2 border-slate-200 hover:border-indigo-400 transition-all" onclick="selectExamType('unicamp', this)">
                                    <div class="exam-icon w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-xl mb-3"><i class="bi bi-mortarboard"></i></div>
                                    <h3 class="font-outfit font-extrabold text-base text-slate-900 mb-1">UNICAMP</h3>
                                    <p class="text-xs text-slate-500 mb-0">Foco em interpretação de texto e interdisciplinaridade</p>
                                </div>
                                <div class="exam-card cursor-pointer p-4 rounded-2xl border-2 border-slate-200 hover:border-indigo-400 transition-all" onclick="selectExamType('unesp', this)">
                                    <div class="exam-icon w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-xl mb-3"><i class="bi bi-book"></i></div>
                                    <h3 class="font-outfit font-extrabold text-base text-slate-900 mb-1">UNESP</h3>
                                    <p class="text-xs text-slate-500 mb-0">Questões clássicas da Fundação Vunesp</p>
                                </div>
                                <div class="exam-card cursor-pointer p-4 rounded-2xl border-2 border-slate-200 hover:border-indigo-400 transition-all sm:col-span-2 md:col-span-2" onclick="selectExamType('misto', this)">
                                    <div class="exam-icon w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-xl mb-3"><i class="bi bi-shuffle"></i></div>
                                    <h3 class="font-outfit font-extrabold text-base text-slate-900 mb-1">Misto Nacional</h3>
                                    <p class="text-xs text-slate-500 mb-0">Mistura surpresa dos maiores vestibulares do Brasil (ENEM, USP, UNICAMP, UNESP, etc.)</p>
                                </div>
                            </div>
                        </div>

                        <!-- Quantidade e Tempo (3 minutos por questão) -->
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-lg font-extrabold font-outfit text-slate-800 flex items-center gap-2 mb-0">
                                    <i class="bi bi-2-circle-fill text-indigo-600"></i> Quantidade de Questões e Tempo
                                </h2>
                                <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-xl border border-indigo-200">
                                    ⏱️ Média de 3 minutos por questão
                                </span>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <button type="button" class="pill-selector p-3.5 rounded-2xl border-2 border-slate-200 font-outfit font-bold text-xs text-slate-700 hover:border-indigo-400 transition-all text-center" onclick="selectQuestionCount(15, 45, this)">
                                    <span class="block text-sm font-extrabold">15 Questões</span>
                                    <span class="text-[11px] opacity-80">45 minutos</span>
                                </button>
                                <button type="button" class="pill-selector selected p-3.5 rounded-2xl border-2 border-indigo-600 bg-indigo-600 text-white font-outfit font-extrabold text-xs shadow-md transition-all text-center" onclick="selectQuestionCount(30, 90, this)">
                                    <span class="block text-sm font-extrabold">30 Questões</span>
                                    <span class="text-[11px] opacity-80">1h 30min</span>
                                </button>
                                <button type="button" class="pill-selector p-3.5 rounded-2xl border-2 border-slate-200 font-outfit font-bold text-xs text-slate-700 hover:border-indigo-400 transition-all text-center" onclick="selectQuestionCount(45, 135, this)">
                                    <span class="block text-sm font-extrabold">45 Questões</span>
                                    <span class="text-[11px] opacity-80">2h 15min</span>
                                </button>
                                <button type="button" class="pill-selector p-3.5 rounded-2xl border-2 border-slate-200 font-outfit font-bold text-xs text-slate-700 hover:border-indigo-400 transition-all text-center" onclick="selectQuestionCount(90, 270, this)">
                                    <span class="block text-sm font-extrabold">90 Questões</span>
                                    <span class="text-[11px] opacity-80">4h 30min (Oficial)</span>
                                </button>
                            </div>
                        </div>

                        <!-- Botão de Ação -->
                        <div class="pt-4 border-t border-slate-100 flex justify-end">
                            <button onclick="startSimulado()" class="w-full sm:w-auto bg-emerald-500 hover:bg-emerald-600 text-white font-outfit font-black px-8 py-3.5 rounded-2xl shadow-[0_4px_0_0_#047857] active:translate-y-0.5 transition-all text-base flex items-center justify-center gap-2">
                                <i class="bi bi-play-fill text-xl"></i>
                                <span>INICIAR SIMULADO AGORA</span>
                            </button>
                        </div>
                    </div>

                    <!-- CARD DE HISTÓRICO DE SIMULADOS REALIZADOS -->
                    <div class="bg-white rounded-3xl border-2 border-slate-200 p-6 sm:p-8 shadow-[0_4px_0_0_#e2e8f0]">
                        <div class="flex items-center justify-between mb-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-200 text-indigo-600 flex items-center justify-center text-lg font-bold">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-extrabold font-outfit text-slate-900 mb-0.5">Histórico de Simulados Realizados</h2>
                                    <p class="text-xs text-slate-500 font-medium mb-0">Acompanhe seu histórico de notas, aproveitamento e ganho de XP nos vestibulares.</p>
                                </div>
                            </div>
                            <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-extrabold bg-slate-100 text-slate-700 border border-slate-200">
                                <i class="bi bi-list-check"></i> <?= count($simuladosHistory) ?> prova(s) registrada(s)
                            </span>
                        </div>

                        <?php if (empty($simuladosHistory)): ?>
                            <div class="p-8 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 text-center">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-2 text-xl font-bold">
                                    <i class="bi bi-journal-check"></i>
                                </div>
                                <h4 class="font-outfit font-extrabold text-sm text-slate-700 mb-1">Nenhum simulado realizado ainda</h4>
                                <p class="text-xs text-slate-500 font-medium mb-0 max-w-md mx-auto">Escolha o formato e a quantidade de questões acima para iniciar seu primeiro simulado e registrar seu histórico de evolução!</p>
                            </div>
                        <?php else: ?>
                            <div class="overflow-x-auto rounded-2xl border-2 border-slate-200 shadow-sm">
                                <table class="w-full text-left text-xs border-collapse">
                                    <thead class="bg-slate-100/90 text-slate-600 font-outfit font-black uppercase text-[11px] border-b-2 border-slate-200">
                                        <tr>
                                            <th class="py-3.5 px-4">Banca / Formato</th>
                                            <th class="py-3.5 px-4">Data & Horário</th>
                                            <th class="py-3.5 px-4">Desempenho</th>
                                            <th class="py-3.5 px-4">Tempo Gasto</th>
                                            <th class="py-3.5 px-4">XP Ganho</th>
                                            <th class="py-3.5 px-4 text-center">Resultado</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 bg-white font-medium text-slate-700">
                                        <?php 
                                        $examNames = [
                                            'enem' => ['name' => 'ENEM', 'color' => 'bg-indigo-50 text-indigo-700 border-indigo-200'],
                                            'fuvest' => ['name' => 'FUVEST / USP', 'color' => 'bg-blue-50 text-blue-700 border-blue-200'],
                                            'unicamp' => ['name' => 'UNICAMP', 'color' => 'bg-purple-50 text-purple-700 border-purple-200'],
                                            'unesp' => ['name' => 'UNESP', 'color' => 'bg-sky-50 text-sky-700 border-sky-200'],
                                            'misto' => ['name' => 'Misto Nacional', 'color' => 'bg-amber-50 text-amber-700 border-amber-200'],
                                        ];
                                        foreach ($simuladosHistory as $sim): 
                                            $examInfo = $examNames[$sim['exam_type']] ?? ['name' => strtoupper($sim['exam_type']), 'color' => 'bg-slate-100 text-slate-700 border-slate-200'];
                                            $dateStr = date('d/m/Y \à\s H:i', strtotime($sim['finished_at']));
                                            $scorePct = (int)$sim['score'];
                                            $isPassed = $scorePct >= 75;
                                            $minsSpent = floor(($sim['time_spent_sec'] ?? 0) / 60);
                                            $secsSpent = ($sim['time_spent_sec'] ?? 0) % 60;
                                        ?>
                                            <tr class="hover:bg-slate-50/80 transition-colors">
                                                <td class="py-3.5 px-4 font-bold">
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-[11px] font-extrabold border <?= $examInfo['color'] ?>">
                                                        <?= htmlspecialchars($examInfo['name']) ?>
                                                    </span>
                                                </td>
                                                <td class="py-3.5 px-4 text-slate-500 font-mono text-[11px]">
                                                    <?= $dateStr ?>
                                                </td>
                                                <td class="py-3.5 px-4 font-outfit">
                                                    <div class="font-extrabold text-slate-900 text-xs">
                                                        <?= (int)$sim['total_correct'] ?> / <?= (int)$sim['total_questions'] ?> acertos
                                                    </div>
                                                    <span class="text-[11px] font-bold <?= $isPassed ? 'text-emerald-600' : 'text-rose-500' ?>">
                                                        <?= $scorePct ?>% de aproveitamento
                                                    </span>
                                                </td>
                                                <td class="py-3.5 px-4 font-mono text-slate-600">
                                                    <?= $minsSpent ?>m <?= str_pad($secsSpent, 2, '0', STR_PAD_LEFT) ?>s
                                                </td>
                                                <td class="py-3.5 px-4">
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-xl font-outfit font-extrabold text-xs bg-amber-50 text-amber-700 border border-amber-200">
                                                        <i class="bi bi-star-fill text-amber-500 text-[10px]"></i> +<?= (int)$sim['xp_earned'] ?> XP
                                                    </span>
                                                </td>
                                                <td class="py-3.5 px-4 text-center">
                                                    <?php if ($isPassed): ?>
                                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                            <i class="bi bi-check-circle-fill text-emerald-600"></i> Aprovado
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-black bg-rose-100 text-rose-800 border border-rose-300">
                                                            <i class="bi bi-x-circle-fill text-rose-600"></i> Reprovado
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- STATE 2: Prova em Execução -->
                <div id="screenExam" class="hidden bg-white rounded-3xl border-2 border-slate-200 shadow-[0_4px_0_0_#e2e8f0] overflow-hidden">
                    <!-- Exam Header -->
                    <div class="bg-slate-900 text-white p-4 px-6 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <button onclick="confirmExit()" class="text-slate-400 hover:text-rose-400 transition-colors p-1.5 rounded-xl hover:bg-slate-800 flex items-center gap-1.5" title="Sair do Simulado">
                                <i class="bi bi-x-lg text-lg"></i>
                                <span class="text-xs font-bold hidden sm:inline text-slate-300 hover:text-rose-300">Sair da Prova</span>
                            </button>
                            <div class="h-6 w-px bg-slate-700"></div>
                            <div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Progresso da Prova</div>
                                <div id="progressText" class="font-outfit font-bold text-white text-sm">Questão 1 de 30</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div id="timerDisplay" class="bg-slate-800 px-3.5 py-1.5 rounded-xl border border-slate-700 font-mono text-amber-400 font-bold text-sm flex items-center gap-2">
                                <i class="bi bi-stopwatch-fill"></i>
                                <span id="timeRemaining">01:30:00</span>
                            </div>
                            <button onclick="finishSimulado(false)" class="bg-rose-600 hover:bg-rose-700 text-white font-outfit font-extrabold text-xs px-4 py-2 rounded-xl shadow-sm transition">
                                Entregar Prova
                            </button>
                        </div>
                    </div>

                    <!-- Exam Body -->
                    <div class="p-6 sm:p-8 grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                        <!-- Mapa da Prova (Grid de Questões) -->
                        <div class="lg:col-span-4 bg-slate-50 p-5 rounded-3xl border-2 border-slate-200 shadow-sm space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="font-outfit font-extrabold text-xs text-slate-700 uppercase tracking-wider mb-0 flex items-center gap-1.5">
                                    <i class="bi bi-grid-3x3-gap-fill text-indigo-600"></i> Mapa de Questões
                                </h3>
                                <span id="answeredCounterBadge" class="text-[11px] font-extrabold text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-lg border border-indigo-200">0/30</span>
                            </div>
                            <div id="questionGrid" class="question-grid grid grid-cols-5 gap-2.5 max-h-80 overflow-y-auto pr-1">
                                <!-- Populated by JS -->
                            </div>
                            <div class="pt-3 border-t border-slate-200 space-y-2 text-xs font-bold text-slate-500">
                                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-white border-2 border-slate-300"></span> Pendente</div>
                                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-indigo-100 border-2 border-indigo-400"></span> Respondida</div>
                                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-indigo-600"></span> Questão Atual</div>
                            </div>
                        </div>

                        <!-- Área da Questão -->
                        <div class="lg:col-span-8 space-y-5">
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <span id="qSource" class="px-3 py-1 bg-slate-100 text-slate-700 rounded-xl text-xs font-extrabold font-mono border border-slate-300 shadow-sm">ENEM 2023</span>
                                <span id="qSubject" class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-xl text-xs font-extrabold font-outfit border border-indigo-200 shadow-sm">Matemática</span>
                                <span id="qDifficulty" class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-extrabold border shadow-sm bg-amber-50 text-amber-700 border-amber-300"><i class="bi bi-dash-circle"></i> Médio</span>
                            </div>

                            <div id="qText" class="text-slate-900 text-base sm:text-lg leading-relaxed font-semibold bg-slate-50/80 p-6 rounded-3xl border-2 border-slate-200 shadow-sm">
                                Carregando questão...
                            </div>

                            <div id="qOptions" class="space-y-3.5 flex flex-col">
                                <!-- Populated by JS -->
                            </div>
                        </div>
                    </div>

                    <!-- Navegação do Exame -->
                    <div class="bg-slate-50 px-6 py-3 border-t border-slate-200 flex items-center justify-between">
                        <button id="btnPrev" onclick="prevQuestion()" class="bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold px-4 py-2 rounded-xl text-xs transition flex items-center gap-1">
                            <i class="bi bi-arrow-left"></i> Anterior
                        </button>
                        <button id="btnNext" onclick="nextQuestion()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-5 py-2 rounded-xl text-xs transition flex items-center gap-1">
                            Próxima <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- STATE 3: Boletim de Resultados -->
                <div id="screenResults" class="hidden bg-white rounded-3xl border-2 border-slate-200 p-6 sm:p-8 shadow-[0_4px_0_0_#e2e8f0] space-y-6">
                    <div class="text-center pb-4 border-b border-slate-100">
                        <div id="simuladoStatusBadge" class="mb-2"></div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold font-outfit text-slate-900 mb-1" id="simuladoResultTitle">Simulado Concluído!</h1>
                        <p id="resultMessage" class="text-xs sm:text-sm text-slate-500 mb-0">Calculando seu desempenho...</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Score Principal -->
                        <div class="bg-slate-50 rounded-2xl border border-slate-200 p-6 flex flex-col items-center justify-center text-center">
                            <h3 class="font-outfit font-extrabold text-xs text-slate-400 uppercase tracking-wider mb-4">Pontuação Geral</h3>
                            <div class="score-circle" id="scoreCircle" style="--percentage: 0%">
                                <div class="score-circle-inner">
                                    <span id="scoreText">0</span><span class="text-sm text-slate-400">/0</span>
                                </div>
                            </div>
                            <div class="mt-4 text-indigo-600 font-outfit font-black text-xl" id="percentageText">0%</div>
                        </div>

                        <!-- Métricas -->
                        <div class="md:col-span-2 grid grid-cols-2 gap-4">
                            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl shrink-0">
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-slate-500 font-semibold">XP Ganho</div>
                                    <div class="text-lg font-extrabold font-outfit text-amber-800" id="xpEarned">+0 XP</div>
                                </div>
                            </div>

                            <div class="p-4 rounded-2xl bg-indigo-50 border border-indigo-200 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl shrink-0">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-slate-500 font-semibold">Tempo Gasto</div>
                                    <div class="text-lg font-extrabold font-outfit text-indigo-800" id="timeSpent">00:00</div>
                                </div>
                            </div>

                            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-slate-500 font-semibold">Taxa de Acerto</div>
                                    <div class="text-lg font-extrabold font-outfit text-emerald-800" id="accuracyRate">0%</div>
                                </div>
                            </div>

                            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-xl shrink-0">
                                    <i class="bi bi-x-circle-fill"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-slate-500 font-semibold">Erros</div>
                                    <div class="text-lg font-extrabold font-outfit text-rose-800" id="mistakesCount">0</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Desempenho por Matéria -->
                    <div class="bg-slate-50 rounded-2xl border border-slate-200 p-5">
                        <h3 class="font-outfit font-extrabold text-sm text-slate-800 mb-4 flex items-center gap-2">
                            <i class="bi bi-bar-chart-fill text-indigo-600"></i> Desempenho por Matéria
                        </h3>
                        <div id="subjectBreakdown" class="space-y-3">
                            <!-- Populated by JS -->
                        </div>
                    </div>

                    <!-- Desempenho por Dificuldade -->
                    <div class="bg-slate-50 rounded-2xl border border-slate-200 p-5">
                        <h3 class="font-outfit font-extrabold text-sm text-slate-800 mb-4 flex items-center gap-2">
                            <i class="bi bi-speedometer text-amber-600"></i> Desempenho por Nível de Dificuldade
                        </h3>
                        <div id="difficultyBreakdown" class="space-y-3">
                            <!-- Populated by JS -->
                        </div>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="flex flex-col sm:flex-row gap-3 justify-end pt-3 border-t border-slate-100">
                        <a href="simulado.php" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-6 py-2.5 rounded-xl text-xs transition text-center">
                            Fazer Outro Simulado
                        </a>
                        <a href="dashboard.php" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-2.5 rounded-xl text-xs transition text-center">
                            Voltar à Trilha de Estudos
                        </a>
                    </div>
                </div>

            </section>
        </div>
    </main>

    <!-- JS ENGINE DO SIMULADO COM PERSISTÊNCIA TOTAL -->
    <script>
        const CURRENT_USER_ID = <?= (int)$userId ?>;
        const SIMULADO_STORAGE_KEY = `hipogabarito_simulado_state_${CURRENT_USER_ID}`;

        let selectedExam = 'enem';
        let selectedCount = 30;
        let selectedTimeMinutes = 90; // Média de 3 min por questão: 30 * 3 = 90 min
        
        let questionsData = [];
        let userAnswers = {};
        let currentQuestionIndex = 0;
        
        let timerInterval = null;
        let secondsRemaining = 0;
        let totalSeconds = 0;
        let examEndTimeStamp = null;

        let isExamActive = false;
        let focusWarningActive = false;

        // ============================================================
        // PERSISTÊNCIA TOTAL (SALVAR, RECUPERAR E LIMPAR ESTADO)
        // ============================================================
        function saveSimuladoState() {
            if (!isExamActive || !questionsData || questionsData.length === 0) return;
            
            const state = {
                userId: CURRENT_USER_ID,
                selectedExam: selectedExam,
                selectedCount: selectedCount,
                selectedTimeMinutes: selectedTimeMinutes,
                questionsData: questionsData,
                userAnswers: userAnswers,
                currentQuestionIndex: currentQuestionIndex,
                totalSeconds: totalSeconds,
                examEndTimeStamp: examEndTimeStamp,
                isExamActive: true,
                savedAt: Date.now()
            };
            
            try {
                localStorage.setItem(SIMULADO_STORAGE_KEY, JSON.stringify(state));
            } catch (e) {
                console.error('Erro ao salvar estado do simulado no localStorage:', e);
            }
        }

        function clearSimuladoState() {
            try {
                localStorage.removeItem(SIMULADO_STORAGE_KEY);
            } catch (e) {
                console.error('Erro ao limpar estado do simulado:', e);
            }
        }

        function checkAndRestoreSimulado() {
            try {
                const raw = localStorage.getItem(SIMULADO_STORAGE_KEY);
                if (!raw) return;

                const state = JSON.parse(raw);
                if (!state || !state.questionsData || state.questionsData.length === 0 || !state.examEndTimeStamp) {
                    clearSimuladoState();
                    return;
                }

                // Calcular tempo restante com base no timestamp absoluto de término
                const remaining = Math.floor((state.examEndTimeStamp - Date.now()) / 1000);
                if (remaining <= 0) {
                    clearSimuladoState();
                    Swal.fire({
                        title: 'Tempo Expirado',
                        text: 'O tempo do simulado anterior expirou enquanto você esteve fora da página.',
                        icon: 'info',
                        confirmButtonColor: '#4f46e5'
                    });
                    return;
                }

                // Restaurar todas as variáveis
                questionsData = state.questionsData;
                userAnswers = state.userAnswers || {};
                currentQuestionIndex = state.currentQuestionIndex || 0;
                selectedExam = state.selectedExam || 'enem';
                selectedCount = state.selectedCount || 30;
                selectedTimeMinutes = state.selectedTimeMinutes || 90;
                totalSeconds = state.totalSeconds || (selectedTimeMinutes * 60);
                examEndTimeStamp = state.examEndTimeStamp;
                secondsRemaining = remaining;
                isExamActive = true;

                // Ajustar UI para modo foco
                const sidebar = document.getElementById('mainSidebar');
                if (sidebar) sidebar.classList.add('hidden');

                const headerNav = document.querySelector('header');
                if (headerNav) headerNav.classList.add('hidden');

                const simuladoSec = document.getElementById('simuladoSection');
                if (simuladoSec) {
                    simuladoSec.classList.remove('lg:col-span-9');
                    simuladoSec.classList.add('lg:col-span-12');
                }

                document.getElementById('screenSelect').classList.add('hidden');
                document.getElementById('screenExam').classList.remove('hidden');

                startTimer();
                renderQuestionGrid();
                renderQuestion(currentQuestionIndex);

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true
                });
                Toast.fire({
                    icon: 'success',
                    title: 'Simulado Restaurado!',
                    text: 'Seu progresso, tempo e respostas foram mantidos com segurança.'
                });
            } catch (err) {
                console.error('Erro ao restaurar simulado salvo:', err);
                clearSimuladoState();
            }
        }

        // ============================================================
        // CONFIGURAÇÃO DO SIMULADO
        // ============================================================
        function selectExamType(type, el) {
            selectedExam = type;
            document.querySelectorAll('.exam-card').forEach(card => {
                card.classList.remove('selected', 'border-indigo-600', 'bg-indigo-50/50');
                card.classList.add('border-slate-200');
                const icon = card.querySelector('.exam-icon');
                if (icon) {
                    icon.classList.remove('bg-indigo-600', 'text-white');
                    icon.classList.add('bg-slate-100', 'text-slate-700');
                }
            });
            el.classList.add('selected', 'border-indigo-600', 'bg-indigo-50/50');
            el.classList.remove('border-slate-200');
            const activeIcon = el.querySelector('.exam-icon');
            if (activeIcon) {
                activeIcon.classList.add('bg-indigo-600', 'text-white');
                activeIcon.classList.remove('bg-slate-100', 'text-slate-700');
            }
        }

        function selectQuestionCount(count, timeMins, el) {
            selectedCount = count;
            selectedTimeMinutes = timeMins;
            document.querySelectorAll('.pill-selector').forEach(btn => {
                btn.classList.remove('selected', 'bg-indigo-600', 'text-white');
                btn.classList.add('border-slate-200', 'text-slate-700');
            });
            el.classList.add('selected', 'bg-indigo-600', 'text-white');
            el.classList.remove('border-slate-200', 'text-slate-700');
        }

        function startSimulado() {
            Swal.fire({
                title: 'Carregando Simulado...',
                text: 'Buscando questões do banco de dados',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch(`api/simulado.php?exam=${selectedExam}&count=${selectedCount}`)
                .then(res => res.json())
                .then(data => {
                    Swal.close();
                    if (data.status === 'success' && data.questions.length > 0) {
                        questionsData = data.questions;
                        userAnswers = {};
                        currentQuestionIndex = 0;

                        // 1. Ocultar Sidebar e Header Superior para Foco Total
                        const sidebar = document.getElementById('mainSidebar');
                        if (sidebar) sidebar.classList.add('hidden');

                        const headerNav = document.querySelector('header');
                        if (headerNav) headerNav.classList.add('hidden');

                        const simuladoSec = document.getElementById('simuladoSection');
                        if (simuladoSec) {
                            simuladoSec.classList.remove('lg:col-span-9');
                            simuladoSec.classList.add('lg:col-span-12');
                        }

                        // 2. Transicionar Telas
                        document.getElementById('screenSelect').classList.add('hidden');
                        document.getElementById('screenExam').classList.remove('hidden');

                        // 3. Iniciar Temporizador com Timestamp Absoluto
                        totalSeconds = selectedTimeMinutes * 60;
                        secondsRemaining = totalSeconds;
                        examEndTimeStamp = Date.now() + (totalSeconds * 1000);

                        // 4. Ativar Modo Foco e Salvar Estado
                        isExamActive = true;
                        saveSimuladoState();

                        startTimer();
                        renderQuestionGrid();
                        renderQuestion(0);
                    } else {
                        Swal.fire('Aviso', data.message || 'Não foi possível carregar as questões.', 'warning');
                    }
                })
                .catch(err => {
                    Swal.close();
                    console.error(err);
                    Swal.fire('Erro', 'Ocorreu uma falha ao conectar com o servidor.', 'error');
                });
        }

        function startTimer() {
            updateTimerDisplay();
            if (timerInterval) clearInterval(timerInterval);

            timerInterval = setInterval(() => {
                if (examEndTimeStamp) {
                    secondsRemaining = Math.max(0, Math.floor((examEndTimeStamp - Date.now()) / 1000));
                } else {
                    secondsRemaining--;
                }
                updateTimerDisplay();

                if (secondsRemaining <= 0) {
                    clearInterval(timerInterval);
                    isExamActive = false;
                    clearSimuladoState();
                    Swal.fire('Tempo Esgotado!', 'O tempo do simulado acabou. Suas respostas serão enviadas agora.', 'info').then(() => {
                        submitSimuladoData(true);
                    });
                }
            }, 1000);
        }

        function updateTimerDisplay() {
            const hours = Math.floor(secondsRemaining / 3600);
            const mins = Math.floor((secondsRemaining % 3600) / 60);
            const secs = secondsRemaining % 60;
            if (hours > 0) {
                document.getElementById('timeRemaining').textContent = 
                    `${hours.toString().padStart(2, '0')}:${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
            } else {
                document.getElementById('timeRemaining').textContent = 
                    `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
            }
        }

        // Helper para badge de dificuldade
        function getDiffBadge(diff) {
            const map = {
                'fácil':   { label: 'Fácil',   icon: 'bi-check-circle',   color: 'bg-emerald-50 text-emerald-700 border-emerald-300' },
                'médio':   { label: 'Médio',   icon: 'bi-dash-circle',    color: 'bg-amber-50 text-amber-700 border-amber-300' },
                'difícil': { label: 'Difícil', icon: 'bi-exclamation-circle', color: 'bg-rose-50 text-rose-700 border-rose-300' }
            };
            return map[diff] || map['médio'];
        }

        function renderQuestionGrid() {
            const grid = document.getElementById('questionGrid');
            grid.innerHTML = '';

            // Agrupar visualmente por dificuldade com separadores
            let lastDiff = null;
            questionsData.forEach((q, idx) => {
                const diff = q.difficulty || 'médio';
                if (diff !== lastDiff) {
                    lastDiff = diff;
                    const info = getDiffBadge(diff);
                    const separator = document.createElement('div');
                    separator.className = 'col-span-5 flex items-center gap-2 py-1.5 px-0.5';
                    separator.innerHTML = `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-extrabold border ${info.color}"><i class="bi ${info.icon}"></i> ${info.label}</span><div class="flex-1 h-px bg-slate-200"></div>`;
                    grid.appendChild(separator);
                }

                const btn = document.createElement('button');
                btn.className = 'q-grid-btn w-full py-1.5 rounded-lg font-outfit text-xs font-bold border transition-all text-center';
                btn.id = `qGridBtn_${idx}`;
                btn.textContent = idx + 1;
                btn.onclick = () => renderQuestion(idx);
                grid.appendChild(btn);
            });
            updateQuestionGridState();
        }

        function updateQuestionGridState() {
            const answeredCount = Object.keys(userAnswers).length;
            const badge = document.getElementById('answeredCounterBadge');
            if (badge) {
                badge.textContent = `${answeredCount}/${questionsData.length} respondidas`;
                if (answeredCount === questionsData.length && questionsData.length > 0) {
                    badge.className = 'text-[11px] font-extrabold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-lg border border-emerald-200';
                } else {
                    badge.className = 'text-[11px] font-extrabold text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-lg border border-indigo-200';
                }
            }

            questionsData.forEach((q, idx) => {
                const btn = document.getElementById(`qGridBtn_${idx}`);
                if (!btn) return;

                btn.classList.remove('bg-indigo-600', 'text-white', 'bg-indigo-100', 'text-indigo-800', 'border-indigo-400');
                if (idx === currentQuestionIndex) {
                    btn.classList.add('bg-indigo-600', 'text-white', 'border-indigo-600');
                } else if (userAnswers[q.id]) {
                    btn.classList.add('bg-indigo-100', 'text-indigo-800', 'border-indigo-400');
                } else {
                    btn.classList.add('bg-white', 'text-slate-700', 'border-slate-200');
                }
            });
        }

        function renderQuestion(index) {
            currentQuestionIndex = index;
            saveSimuladoState();

            const q = questionsData[index];

            document.getElementById('progressText').textContent = `Questão ${index + 1} de ${questionsData.length}`;
            document.getElementById('qSource').textContent = q.source || 'VESTIBULAR';
            document.getElementById('qSubject').textContent = q.subject || 'Geral';

            // Badge de Dificuldade
            const diffInfo = getDiffBadge(q.difficulty || 'médio');
            const diffBadgeEl = document.getElementById('qDifficulty');
            if (diffBadgeEl) {
                diffBadgeEl.className = `inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-extrabold border shadow-sm ${diffInfo.color}`;
                diffBadgeEl.innerHTML = `<i class="bi ${diffInfo.icon}"></i> ${diffInfo.label}`;
            }

            document.getElementById('qText').innerHTML = q.question_text;

            const optionsContainer = document.getElementById('qOptions');
            optionsContainer.innerHTML = '';

            const options = [
                { key: 'a', text: q.option_a },
                { key: 'b', text: q.option_b },
                { key: 'c', text: q.option_c },
                { key: 'd', text: q.option_d },
                { key: 'e', text: q.option_e }
            ];

            options.forEach(opt => {
                if (!opt.text) return;
                const isSelected = userAnswers[q.id] === opt.key;
                
                const optDiv = document.createElement('div');
                optDiv.className = `simulado-option-card ${isSelected ? 'selected' : ''}`;
                optDiv.onclick = () => selectOption(q.id, opt.key);

                optDiv.innerHTML = `
                    <div class="simulado-option-badge">
                        ${opt.key.toUpperCase()}
                    </div>
                    <div class="simulado-option-text">${opt.text}</div>
                    <div class="simulado-option-indicator">
                        <i class="bi ${isSelected ? 'bi-record-circle-fill text-indigo-600' : 'bi-circle text-slate-300'} text-base"></i>
                    </div>
                `;
                optionsContainer.appendChild(optDiv);
            });

            document.getElementById('btnPrev').disabled = index === 0;
            document.getElementById('btnNext').textContent = index === questionsData.length - 1 ? 'Concluir Prova' : 'Próxima >';
            
            updateQuestionGridState();
        }

        function selectOption(qId, optionKey) {
            userAnswers[qId] = optionKey;
            saveSimuladoState();
            renderQuestion(currentQuestionIndex);
        }

        function prevQuestion() {
            if (currentQuestionIndex > 0) renderQuestion(currentQuestionIndex - 1);
        }

        function nextQuestion() {
            if (currentQuestionIndex < questionsData.length - 1) {
                renderQuestion(currentQuestionIndex + 1);
            } else {
                finishSimulado(false);
            }
        }

        function confirmExit() {
            Swal.fire({
                title: 'Desistiu do Simulado?',
                text: 'Seu progresso atual será cancelado e o simulado não será salvo.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sim, sair',
                cancelButtonText: 'Continuar Prova'
            }).then((res) => {
                if (res.isConfirmed) {
                    isExamActive = false;
                    clearSimuladoState();
                    if (timerInterval) clearInterval(timerInterval);
                    window.location.href = 'simulado.php';
                }
            });
        }

        // BLOQUEIO SE NÃO HOUVER TODAS AS QUESTÕES MARCADAS
        function finishSimulado(isTimeOut = false) {
            if (!isTimeOut) {
                const totalQ = questionsData.length;
                const answeredCount = Object.keys(userAnswers).length;
                const unansweredCount = totalQ - answeredCount;

                if (unansweredCount > 0) {
                    // Encontrar o índice da primeira questão pendente
                    const firstUnansweredIndex = questionsData.findIndex(q => !userAnswers[q.id]);

                    Swal.fire({
                        title: 'Questões Pendentes!',
                        html: `Você ainda não marcou <b>${unansweredCount} questão(ões)</b> pendente(s).<br><br>É obrigatório responder todas as questões antes de entregar o simulado.`,
                        icon: 'warning',
                        confirmButtonText: 'Ir para questão pendente',
                        confirmButtonColor: '#4f46e5'
                    }).then(() => {
                        if (firstUnansweredIndex !== -1) {
                            renderQuestion(firstUnansweredIndex);
                        }
                    });
                    return; // Bloqueia a conclusão
                }

                // Se todas as questões foram respondidas, pedir confirmação antes de entregar
                Swal.fire({
                    title: 'Entregar o Simulado?',
                    text: `Você respondeu todas as ${totalQ} questões. Deseja finalizar e ver seu resultado?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sim, entregar agora',
                    cancelButtonText: 'Revisar respostas',
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#64748b'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitSimuladoData(false);
                    }
                });
            } else {
                // Caso de tempo esgotado: envia automaticamente com o que foi respondido
                submitSimuladoData(true);
            }
        }

        function submitSimuladoData(isTimeOut = false) {
            isExamActive = false;
            clearSimuladoState();
            if (timerInterval) clearInterval(timerInterval);

            Swal.fire({
                title: 'Calculando Resultados...',
                text: 'Processando respostas e contabilizando XP',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            const timeSpentSecs = Math.max(1, totalSeconds - secondsRemaining);

            fetch('api/simulado.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    answers: userAnswers,
                    time_spent: timeSpentSecs,
                    exam: selectedExam
                })
            })
            .then(res => res.json())
            .then(res => {
                Swal.close();
                if (res.status === 'success') {
                    // Restaurar Sidebar e Header na tela de resultados
                    const sidebar = document.getElementById('mainSidebar');
                    if (sidebar) sidebar.classList.remove('hidden');

                    const headerNav = document.querySelector('header');
                    if (headerNav) headerNav.classList.remove('hidden');

                    const simuladoSec = document.getElementById('simuladoSection');
                    if (simuladoSec) {
                        simuladoSec.classList.remove('lg:col-span-12');
                        simuladoSec.classList.add('lg:col-span-9');
                    }

                    showResultsScreen(res);
                } else {
                    Swal.fire('Erro', res.message || 'Falha ao salvar simulado.', 'error');
                }
            })
            .catch(err => {
                Swal.close();
                console.error(err);
                Swal.fire('Erro', 'Ocorreu um erro de comunicação com o servidor.', 'error');
            });
        }

        function showResultsScreen(res) {
            document.getElementById('screenExam').classList.add('hidden');
            document.getElementById('screenResults').classList.remove('hidden');

            const correct = res.correct_answers;
            const total = res.total_questions;
            const percentage = Math.round(res.score);
            const passed = percentage >= 75;

            const statusBadge = document.getElementById('simuladoStatusBadge');
            const resultTitle = document.getElementById('simuladoResultTitle');

            if (passed) {
                if (statusBadge) statusBadge.innerHTML = `<span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300"><i class="bi bi-check-circle-fill text-emerald-600"></i> APROVADO (${percentage}%)</span>`;
                if (resultTitle) resultTitle.textContent = "Parabéns! Simulado Aprovado!";
                document.getElementById('resultMessage').textContent = `Você acertou ${percentage}% das questões (mínimo de 75% exigido) e teve um ótimo resultado!`;
            } else {
                if (statusBadge) statusBadge.innerHTML = `<span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-extrabold bg-rose-100 text-rose-800 border border-rose-300"><i class="bi bi-x-circle-fill text-rose-600"></i> REPROVADO (${percentage}%)</span>`;
                if (resultTitle) resultTitle.textContent = "Não foi desta vez!";
                document.getElementById('resultMessage').textContent = `Você obteve ${percentage}% de aproveitamento. É necessário acertar no mínimo 75% das questões para ser aprovado no simulado.`;
            }

            document.getElementById('scoreText').textContent = correct;
            document.getElementById('scoreText').nextElementSibling.textContent = `/${total}`;
            document.getElementById('percentageText').textContent = `${percentage}% de acertos`;

            document.getElementById('scoreCircle').style.setProperty('--percentage', `${percentage}%`);
            document.getElementById('xpEarned').textContent = `+${res.xp_gained || 0} XP`;

            const mins = Math.floor(res.time_spent / 60);
            const secs = res.time_spent % 60;
            document.getElementById('timeSpent').textContent = `${mins}m ${secs}s`;
            document.getElementById('accuracyRate').textContent = `${percentage}%`;
            document.getElementById('mistakesCount').textContent = total - correct;

            // Breakdown por matéria
            const breakdownContainer = document.getElementById('subjectBreakdown');
            breakdownContainer.innerHTML = '';

            if (res.by_subject) {
                Object.keys(res.by_subject).forEach(subj => {
                    const item = res.by_subject[subj];
                    const pct = Math.round((item.correct / item.total) * 100);

                    const div = document.createElement('div');
                    div.className = 'space-y-1';
                    div.innerHTML = `
                        <div class="flex justify-between text-xs font-bold">
                            <span class="text-slate-700">${subj}</span>
                            <span class="text-indigo-600">${item.correct}/${item.total} (${pct}%)</span>
                        </div>
                        <div class="w-full h-2.5 bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-600 rounded-full transition-all duration-500" style="width: ${pct}%"></div>
                        </div>
                    `;
                    breakdownContainer.appendChild(div);
                });
            }

            // Breakdown por dificuldade
            const diffContainer = document.getElementById('difficultyBreakdown');
            if (diffContainer && res.by_difficulty) {
                diffContainer.innerHTML = '';
                const diffStyles = {
                    'fácil':   { label: 'Fácil',   bar: 'bg-emerald-500', badge: 'text-emerald-700' },
                    'médio':   { label: 'Médio',   bar: 'bg-amber-500',   badge: 'text-amber-700' },
                    'difícil': { label: 'Difícil', bar: 'bg-rose-500',    badge: 'text-rose-700' }
                };

                Object.keys(res.by_difficulty).forEach(diff => {
                    const item = res.by_difficulty[diff];
                    const pct = Math.round((item.correct / item.total) * 100);
                    const style = diffStyles[diff] || diffStyles['médio'];

                    const div = document.createElement('div');
                    div.className = 'space-y-1';
                    div.innerHTML = `
                        <div class="flex justify-between text-xs font-bold">
                            <span class="${style.badge} font-extrabold">${style.label}</span>
                            <span class="text-slate-600">${item.correct}/${item.total} (${pct}%)</span>
                        </div>
                        <div class="w-full h-2.5 bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full ${style.bar} rounded-full transition-all duration-500" style="width: ${pct}%"></div>
                        </div>
                    `;
                    diffContainer.appendChild(div);
                });
            }
        }

        // ============================================================
        // SISTEMA DE FOCO TOTAL & ANTI-DISTRAÇÃO ("MANTENHA O FOCO!!!")
        // ============================================================
        function handleFocusLost() {
            if (!isExamActive || focusWarningActive) return;
            focusWarningActive = true;

            Swal.fire({
                title: '⚠️ Mantenha o foco!!!',
                text: 'Você saiu da tela do simulado! Para garantir uma preparação autêntica para o vestibular, mantenha sua atenção total na prova.',
                icon: 'warning',
                confirmButtonText: 'Voltar ao Simulado',
                confirmButtonColor: '#4f46e5',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(() => {
                focusWarningActive = false;
            });
        }

        document.addEventListener('visibilitychange', () => {
            if (document.hidden && isExamActive) {
                handleFocusLost();
            }
        });

        window.addEventListener('blur', () => {
            if (isExamActive) {
                handleFocusLost();
            }
        });

        window.addEventListener('beforeunload', (e) => {
            if (isExamActive) {
                e.preventDefault();
                e.returnValue = 'O simulado está em andamento. Suas respostas atuais foram salvas localmente.';
                return e.returnValue;
            }
        });

        // Inicialização: Verificar e restaurar simulado se a página foi recarregada
        document.addEventListener('DOMContentLoaded', () => {
            checkAndRestoreSimulado();
        });
    </script>
    <?php require_once __DIR__ . '/includes/footer.php'; ?>
