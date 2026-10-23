<?php
/**
 * PÁGINA: MÉTRICAS & RAIO-X PEDAGÓGICO — HIPOGABARITO
 * Exibe estatísticas de desempenho, gráficos interativos via Chart.js,
 * diagnóstico inteligente e histórico de evolução nos vestibulares.
 */
require_once __DIR__ . '/config/db.php';
checkAuth();

$userId = (int)$_SESSION['user_id'];
$pageTitle = 'Raio-X & Métricas de Desempenho — HipoGabarito';
$currentPage = 'metricas.php';

require_once __DIR__ . '/includes/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 w-full flex-1">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- SIDEBAR DE NAVEGAÇÃO -->
        <?php include __DIR__ . '/includes/sidebar.php'; ?>

        <!-- CONTEÚDO PRINCIPAL -->
        <main class="lg:col-span-9 space-y-6">

            <!-- HEADER DO PAINEL DE MÉTRICAS -->
            <div class="bg-gradient-to-r from-indigo-700 via-indigo-600 to-purple-600 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden border-2 border-indigo-500/30">
                <div class="absolute -right-8 -bottom-10 opacity-15 pointer-events-none">
                    <i class="bi bi-graph-up-arrow text-9xl"></i>
                </div>

                <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-xs font-bold uppercase tracking-wider text-amber-300 mb-2">
                            <i class="bi bi-lightning-charge-fill"></i> Analytics do Vestibulando
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-outfit font-black tracking-tight mb-1">
                            Raio-X de Desempenho
                        </h1>
                        <p class="text-indigo-100 text-sm max-w-xl">
                            Analise seus pontos fortes, monitore a evolução nas 5 competências da redação e descubra exatamente onde focar seus estudos para conquistar a aprovação.
                        </p>
                    </div>

                    <!-- SELETOR DE PERÍODO & BOTÃO DE IMPRESSÃO -->
                    <div class="flex items-center gap-2 self-start sm:self-center">
                        <div class="inline-flex p-1 bg-black/20 rounded-2xl backdrop-blur-sm border border-white/10">
                            <button onclick="changePeriod('all')" id="btnPeriodAll" class="period-btn px-3 py-1.5 text-xs font-bold rounded-xl transition-all bg-white text-indigo-900 shadow-sm">
                                Tudo
                            </button>
                            <button onclick="changePeriod('30d')" id="btnPeriod30d" class="period-btn px-3 py-1.5 text-xs font-bold rounded-xl text-white/80 hover:text-white transition-all">
                                30 Dias
                            </button>
                            <button onclick="changePeriod('7d')" id="btnPeriod7d" class="period-btn px-3 py-1.5 text-xs font-bold rounded-xl text-white/80 hover:text-white transition-all">
                                7 Dias
                            </button>
                        </div>

                        <button onclick="window.print()" title="Imprimir / Salvar Relatório em PDF" class="p-2.5 bg-white/15 hover:bg-white/25 rounded-2xl border border-white/20 text-white transition-all" aria-label="Imprimir">
                            <i class="bi bi-printer-fill text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- CARDS DE KPIS GLOBAIS -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">
                <!-- KPI 1: PRECISÃO GERAL -->
                <div class="bg-white rounded-3xl border-2 border-slate-200 p-4 sm:p-5 shadow-[0_4px_0_0_#e2e8f0] relative overflow-hidden">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 font-outfit">Precisão Geral</span>
                        <div class="w-9 h-9 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg">
                            <i class="bi bi-bullseye"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1.5">
                        <span id="kpiAccuracy" class="text-2xl sm:text-3xl font-black font-outfit text-slate-900">--%</span>
                    </div>
                    <p class="text-[11px] font-semibold text-slate-400 mt-1 truncate" id="kpiAccuracySub">
                        <span id="kpiTotalCorrect">0</span> de <span id="kpiTotalAnswers">0</span> questões
                    </p>
                </div>

                <!-- KPI 2: QUESTÕES PRATICADAS -->
                <div class="bg-white rounded-3xl border-2 border-slate-200 p-4 sm:p-5 shadow-[0_4px_0_0_#e2e8f0] relative overflow-hidden">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 font-outfit">Prática Ativa</span>
                        <div class="w-9 h-9 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-lg">
                            <i class="bi bi-check2-circle"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1.5">
                        <span id="kpiQuestionsPracticed" class="text-2xl sm:text-3xl font-black font-outfit text-slate-900">--</span>
                        <span class="text-xs font-bold text-slate-400">questões</span>
                    </div>
                    <p class="text-[11px] font-semibold text-slate-400 mt-1 truncate">
                        <span id="kpiLessonsCompleted">0</span> lições da trilha
                    </p>
                </div>

                <!-- KPI 3: MÉDIA DE REDAÇÃO -->
                <div class="bg-white rounded-3xl border-2 border-slate-200 p-4 sm:p-5 shadow-[0_4px_0_0_#e2e8f0] relative overflow-hidden">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 font-outfit">Média Redação</span>
                        <div class="w-9 h-9 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center text-lg">
                            <i class="bi bi-pen-fill"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1.5">
                        <span id="kpiAvgRedacao" class="text-2xl sm:text-3xl font-black font-outfit text-slate-900">--</span>
                        <span class="text-xs font-bold text-slate-400">/ 1000</span>
                    </div>
                    <p class="text-[11px] font-semibold text-purple-600 mt-1 truncate font-bold" id="kpiMaxRedacaoSub">
                        Recorde: -- pts
                    </p>
                </div>

                <!-- KPI 4: SIMULADOS CRONOMETRADOS -->
                <div class="bg-white rounded-3xl border-2 border-slate-200 p-4 sm:p-5 shadow-[0_4px_0_0_#e2e8f0] relative overflow-hidden">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 font-outfit">Simulados</span>
                        <div class="w-9 h-9 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-lg">
                            <i class="bi bi-clock-history"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1.5">
                        <span id="kpiTotalSimulados" class="text-2xl sm:text-3xl font-black font-outfit text-slate-900">--</span>
                        <span class="text-xs font-bold text-slate-400">provas</span>
                    </div>
                    <p class="text-[11px] font-semibold text-slate-400 mt-1 truncate" id="kpiAvgSimScoreSub">
                        Média: --% de acerto
                    </p>
                </div>
            </div>

            <!-- CARD DE DIAGNÓSTICO INTELIGENTE & AÇÃO RECOMENDADA -->
            <div id="diagnosticoContainer" class="bg-white rounded-3xl border-2 border-slate-200 p-5 sm:p-6 shadow-[0_4px_0_0_#e2e8f0] flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="flex items-start gap-3.5">
                    <div id="diagIconWrap" class="w-12 h-12 rounded-2xl bg-indigo-50 border-2 border-indigo-200 flex items-center justify-center text-indigo-600 text-2xl shrink-0 mt-0.5">
                        <i class="bi bi-cpu-fill"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-500 font-outfit">Diagnóstico do Aluno</span>
                            <span id="diagBadge" class="px-2.5 py-0.5 rounded-full text-[11px] font-black bg-indigo-100 text-indigo-700">Calculando...</span>
                        </div>
                        <p id="diagText" class="text-sm font-semibold text-slate-700 mb-0">
                            Carregando suas métricas para montar o plano de estudos personalizado...
                        </p>
                    </div>
                </div>

                <div id="diagActionWrap" class="hidden shrink-0 self-stretch md:self-auto flex items-center justify-end">
                    <a id="diagActionBtn" href="dashboard.php" class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-outfit font-black text-sm shadow-[0_3px_0_0_#312e81] transition-all">
                        <span>Praticar Agora</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- SEÇÃO DE GRÁFICOS PRINCIPAIS: RADAR DE MATÉRIAS & DIFICULDADE -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- GRÁFICO 1: RADAR DE DOMÍNIO POR MATÉRIA (8 COLUNAS) -->
                <div class="lg:col-span-7 bg-white rounded-3xl border-2 border-slate-200 p-5 sm:p-6 shadow-[0_4px_0_0_#e2e8f0]">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                <i class="bi bi-diagram-3-fill text-sm"></i>
                            </div>
                            <div>
                                <h3 class="font-outfit font-black text-slate-900 text-base mb-0">Domínio por Disciplina</h3>
                                <p class="text-[11px] font-semibold text-slate-400 mb-0">Taxa de acerto proporcional em cada área do vestibular</p>
                            </div>
                        </div>
                    </div>

                    <div class="relative h-72 sm:h-80 flex items-center justify-center">
                        <canvas id="chartRadarDisciplinas"></canvas>
                        <div id="radarEmptyState" class="hidden absolute inset-0 flex flex-col items-center justify-center text-center p-4 bg-white/90">
                            <i class="bi bi-journal-x text-4xl text-slate-300 mb-2"></i>
                            <p class="text-sm font-bold text-slate-600 mb-1">Ainda não há dados por matéria</p>
                            <p class="text-xs text-slate-400 max-w-xs mb-3">Resolva lições da trilha de estudos para desenhar seu mapa de competências.</p>
                            <a href="dashboard.php" class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold text-xs shadow">Ir para a Trilha</a>
                        </div>
                    </div>

                    <!-- MINI LEGENDA / LISTA DE MATÉRIAS -->
                    <div id="radarSubjectsList" class="grid grid-cols-2 sm:grid-cols-3 gap-2 pt-4 mt-2 border-t border-slate-100 text-xs">
                        <!-- Preenchido dinamicamente via JS -->
                    </div>
                </div>

                <!-- GRÁFICO 2: DESEMPENHO POR DIFICULDADE (5 COLUNAS) -->
                <div class="lg:col-span-5 bg-white rounded-3xl border-2 border-slate-200 p-5 sm:p-6 shadow-[0_4px_0_0_#e2e8f0] flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                                    <i class="bi bi-bar-chart-steps text-sm"></i>
                                </div>
                                <div>
                                    <h3 class="font-outfit font-black text-slate-900 text-base mb-0">Curva de Dificuldade</h3>
                                    <p class="text-[11px] font-semibold text-slate-400 mb-0">Desempenho por classificação da questão</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4 py-2" id="difficultyBarsContainer">
                            <!-- FÁCIL -->
                            <div>
                                <div class="flex items-center justify-between text-xs font-bold mb-1">
                                    <span class="flex items-center gap-1.5 text-emerald-600">
                                        <i class="bi bi-circle-fill text-[8px]"></i> Fácil
                                    </span>
                                    <span id="diffFacilLabel" class="text-slate-600 font-outfit">0% (0/0)</span>
                                </div>
                                <div class="h-3.5 bg-slate-100 rounded-full overflow-hidden p-0.5 border border-slate-200">
                                    <div id="diffFacilBar" class="h-full bg-emerald-500 rounded-full transition-all duration-700" style="width: 0%"></div>
                                </div>
                            </div>

                            <!-- MÉDIO -->
                            <div>
                                <div class="flex items-center justify-between text-xs font-bold mb-1">
                                    <span class="flex items-center gap-1.5 text-amber-600">
                                        <i class="bi bi-circle-fill text-[8px]"></i> Médio
                                    </span>
                                    <span id="diffMedioLabel" class="text-slate-600 font-outfit">0% (0/0)</span>
                                </div>
                                <div class="h-3.5 bg-slate-100 rounded-full overflow-hidden p-0.5 border border-slate-200">
                                    <div id="diffMedioBar" class="h-full bg-amber-500 rounded-full transition-all duration-700" style="width: 0%"></div>
                                </div>
                            </div>

                            <!-- DIFÍCIL / CHEFÃO -->
                            <div>
                                <div class="flex items-center justify-between text-xs font-bold mb-1">
                                    <span class="flex items-center gap-1.5 text-rose-600">
                                        <i class="bi bi-fire text-[11px]"></i> Difícil / Chefão
                                    </span>
                                    <span id="diffDificilLabel" class="text-slate-600 font-outfit">0% (0/0)</span>
                                </div>
                                <div class="h-3.5 bg-slate-100 rounded-full overflow-hidden p-0.5 border border-slate-200">
                                    <div id="diffDificilBar" class="h-full bg-rose-500 rounded-full transition-all duration-700" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DICA DE OURO PEDAGÓGICA -->
                    <div class="mt-4 p-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs">
                        <div class="flex items-center gap-1.5 font-bold text-slate-800 mb-1">
                            <i class="bi bi-lightbulb-fill text-amber-500"></i> Dica da Metodologia TRI
                        </div>
                        <p class="text-slate-500 mb-0 leading-relaxed text-[11px]">
                            No ENEM e vestibulares modernos, errar questões fáceis penaliza mais sua nota final do que errar questões difíceis. Mantenha seu acerto no nível Fácil acima de 85%!
                        </p>
                    </div>
                </div>
            </div>

            <!-- SEÇÃO DE REDAÇÃO: EVOLUÇÃO E AS 5 COMPETÊNCIAS -->
            <div class="bg-white rounded-3xl border-2 border-slate-200 p-5 sm:p-6 shadow-[0_4px_0_0_#e2e8f0]">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-slate-100 mb-4 gap-2">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">
                            <i class="bi bi-pen-fill text-sm"></i>
                        </div>
                        <div>
                            <h3 class="font-outfit font-black text-slate-900 text-base mb-0">Evolução & Competências da Redação</h3>
                            <p class="text-[11px] font-semibold text-slate-400 mb-0">Diagnóstico detalhado das 5 competências avaliadas pelo corretor IA</p>
                        </div>
                    </div>
                    <a href="redacao.php" class="text-xs font-bold text-purple-600 hover:text-purple-700 inline-flex items-center gap-1">
                        Escrever Redação <i class="bi bi-chevron-right"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                    <!-- GRÁFICO DE BARRAS: AS 5 COMPETÊNCIAS C1-C5 (7 COLUNAS) -->
                    <div class="lg:col-span-7">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 font-outfit mb-0">
                                Média por Competência vs Média Nacional (INEP)
                            </h4>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-black bg-indigo-50 text-indigo-700 border border-indigo-200" title="Dados consolidados dos microdados oficiais do INEP">
                                <i class="bi bi-patch-check-fill text-indigo-500"></i> Benchmark INEP
                            </span>
                        </div>
                        <div class="relative h-60">
                            <canvas id="chartCompetencias"></canvas>
                        </div>
                    </div>

                    <!-- HISTÓRICO DE NOTAS / EVOLUÇÃO (5 COLUNAS) -->
                    <div class="lg:col-span-5 bg-slate-50 rounded-2xl p-4 border border-slate-200">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 font-outfit mb-3">
                            Últimas Redações Submetidas
                        </h4>
                        <div id="recentRedacoesList" class="space-y-2 max-h-52 overflow-y-auto pr-1">
                            <!-- Preenchido dinamicamente -->
                            <div class="text-center py-6 text-slate-400 text-xs">
                                <i class="bi bi-pencil-square text-2xl block mb-1"></i>
                                Nenhuma redação enviada ainda.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEÇÃO DE CONSISTÊNCIA DE ESTUDOS: XP DOS ÚLTIMOS 14 DIAS -->
            <div class="bg-white rounded-3xl border-2 border-slate-200 p-5 sm:p-6 shadow-[0_4px_0_0_#e2e8f0]">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                            <i class="bi bi-calendar-check-fill text-sm"></i>
                        </div>
                        <div>
                            <h3 class="font-outfit font-black text-slate-900 text-base mb-0">Consistência Diária de Estudos</h3>
                            <p class="text-[11px] font-semibold text-slate-400 mb-0">XP acumulado nos últimos 14 dias (Linha tracejada: Meta diária de 50 XP)</p>
                        </div>
                    </div>
                </div>

                <div class="relative h-56 sm:h-64">
                    <canvas id="chartDailyXP"></canvas>
                </div>
            </div>

            <!-- TABELA DE SIMULADOS RECENTES -->
            <div class="bg-white rounded-3xl border-2 border-slate-200 p-5 sm:p-6 shadow-[0_4px_0_0_#e2e8f0]">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                            <i class="bi bi-clock-history text-sm"></i>
                        </div>
                        <div>
                            <h3 class="font-outfit font-black text-slate-900 text-base mb-0">Histórico de Simulados</h3>
                            <p class="text-[11px] font-semibold text-slate-400 mb-0">Últimos testes cronometrados realizados</p>
                        </div>
                    </div>
                    <a href="simulado.php" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 inline-flex items-center gap-1">
                        Novo Simulado <i class="bi bi-chevron-right"></i>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-400 uppercase font-outfit font-bold">
                                <th class="pb-2.5">Exame / Banca</th>
                                <th class="pb-2.5 text-center">Acertos</th>
                                <th class="pb-2.5 text-center">Aproveitamento</th>
                                <th class="pb-2.5 text-center">Tempo Gasto</th>
                                <th class="pb-2.5 text-center">XP Ganho</th>
                                <th class="pb-2.5 text-right">Data</th>
                            </tr>
                        </thead>
                        <tbody id="simuladosTableBody" class="divide-y divide-slate-100 font-semibold text-slate-700">
                            <tr>
                                <td colspan="6" class="text-center py-6 text-slate-400">
                                    Carregando histórico...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
</div>

<!-- SCRIPTS: CHART.JS & LÓGICA DE DADOS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
    let currentPeriod = 'all';
    let radarChartInstance = null;
    let competenciasChartInstance = null;
    let dailyXpChartInstance = null;

    // Detectar cores para Dark Mode / Light Mode
    function isDarkMode() {
        return document.documentElement.classList.contains('dark');
    }

    function getChartColors() {
        const dark = isDarkMode();
        return {
            textColor: dark ? '#cbd5e1' : '#475569',
            gridColor: dark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.06)',
            radarBg: dark ? 'rgba(99, 102, 241, 0.25)' : 'rgba(99, 102, 241, 0.2)',
            radarBorder: '#6366f1',
            radarPointBg: '#4f46e5'
        };
    }

    // Carregar Métricas via API
    async function loadMetrics(period = 'all') {
        currentPeriod = period;

        // Atualizar botões de período no UI
        document.querySelectorAll('.period-btn').forEach(btn => {
            btn.classList.remove('bg-white', 'text-indigo-900', 'shadow-sm');
            btn.classList.add('text-white/80');
        });
        const activeBtn = document.getElementById(`btnPeriod${period.charAt(0).toUpperCase() + period.slice(1)}`);
        if (activeBtn) {
            activeBtn.classList.add('bg-white', 'text-indigo-900', 'shadow-sm');
            activeBtn.classList.remove('text-white/80');
        }

        try {
            const response = await fetch(`api/get_metrics.php?period=${period}`);
            const data = await response.json();

            if (data.status !== 'success') {
                console.error('Erro ao buscar métricas:', data.message);
                return;
            }

            renderKPIs(data.kpis);
            renderDiagnostico(data.diagnostico, data.kpis);
            renderRadar(data.radar_chart);
            renderDifficulty(data.difficulty_stats);
            renderRedacoes(data.redacoes_chart, data.inep_benchmark);
            renderDailyXP(data.daily_chart);
            renderSimuladosTable(data.recent_simulados);

        } catch (error) {
            console.error('Erro de conexão com api/get_metrics.php:', error);
        }
    }

    // 1. Renderizar KPIs
    function renderKPIs(kpis) {
        document.getElementById('kpiAccuracy').innerText = `${kpis.accuracy_percent}%`;
        document.getElementById('kpiTotalCorrect').innerText = kpis.total_correct;
        document.getElementById('kpiTotalAnswers').innerText = kpis.total_answers;

        document.getElementById('kpiQuestionsPracticed').innerText = kpis.total_answers;
        document.getElementById('kpiLessonsCompleted').innerText = kpis.lessons_completed;

        document.getElementById('kpiAvgRedacao').innerText = kpis.total_redacoes > 0 ? kpis.avg_redacao_nota : '--';
        document.getElementById('kpiMaxRedacaoSub').innerText = kpis.total_redacoes > 0 ? `Recorde: ${kpis.max_redacao_nota} pts` : 'Nenhuma redação ainda';

        document.getElementById('kpiTotalSimulados').innerText = kpis.total_simulados;
        document.getElementById('kpiAvgSimScoreSub').innerText = kpis.total_simulados > 0 ? `Média: ${kpis.avg_simulado_score}% de acerto` : 'Nenhum simulado ainda';
    }

    // 2. Renderizar Diagnóstico
    function renderDiagnostico(diag, kpis) {
        const badge = document.getElementById('diagBadge');
        const text = document.getElementById('diagText');
        const actionWrap = document.getElementById('diagActionWrap');
        const actionBtn = document.getElementById('diagActionBtn');

        badge.innerText = diag.status_geral || 'Em Avaliação';
        text.innerText = diag.mensagem || '';

        if (diag.ponto_atencao) {
            actionWrap.classList.remove('hidden');
            actionBtn.href = diag.ponto_atencao.link;
            actionBtn.querySelector('span').innerText = (diag.ponto_atencao.tipo === 'materia') ? `Reforçar ${diag.ponto_atencao.nome}` : 'Treinar Redação';
        } else {
            actionWrap.classList.remove('hidden');
            actionBtn.href = 'dashboard.php';
            actionBtn.querySelector('span').innerText = 'Continuar Trilha';
        }
    }

    // 3. Renderizar Gráfico Radar de Disciplinas
    function renderRadar(radarData) {
        const ctx = document.getElementById('chartRadarDisciplinas').getContext('2d');
        const emptyState = document.getElementById('radarEmptyState');
        const colors = getChartColors();

        const hasData = radarData.scores.some(s => s > 0);
        if (!hasData) {
            emptyState.classList.remove('hidden');
        } else {
            emptyState.classList.add('hidden');
        }

        if (radarChartInstance) {
            radarChartInstance.destroy();
        }

        radarChartInstance = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: radarData.labels,
                datasets: [{
                    label: 'Aproveitamento (%)',
                    data: radarData.scores,
                    backgroundColor: colors.radarBg,
                    borderColor: colors.radarBorder,
                    borderWidth: 2.5,
                    pointBackgroundColor: colors.radarPointBg,
                    pointBorderColor: '#ffffff',
                    pointHoverBackgroundColor: '#ffffff',
                    pointHoverBorderColor: colors.radarBorder,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        angleLines: { color: colors.gridColor },
                        grid: { color: colors.gridColor },
                        suggestedMin: 0,
                        suggestedMax: 100,
                        pointLabels: {
                            color: colors.textColor,
                            font: { family: 'Outfit', size: 11, weight: '700' }
                        },
                        ticks: {
                            stepSize: 25,
                            color: colors.textColor,
                            backdropColor: 'transparent',
                            font: { size: 9 }
                        }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ` Domínio: ${context.raw}%`;
                            }
                        }
                    }
                }
            }
        });

        // Mini lista com percentuais
        const listContainer = document.getElementById('radarSubjectsList');
        listContainer.innerHTML = '';
        radarData.breakdown.forEach(sub => {
            const div = document.createElement('div');
            div.className = 'flex items-center justify-between p-2 rounded-xl bg-slate-50 border border-slate-100';
            div.innerHTML = `
                <span class="flex items-center gap-1.5 font-bold text-slate-700 truncate">
                    <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: ${sub.color}"></span>
                    <span class="truncate">${sub.name}</span>
                </span>
                <span class="font-outfit font-black ${sub.rate >= 70 ? 'text-emerald-600' : (sub.rate >= 50 ? 'text-amber-600' : 'text-slate-500')}">${sub.rate}%</span>
            `;
            listContainer.appendChild(div);
        });
    }

    // 4. Renderizar Curva de Dificuldade
    function renderDifficulty(diff) {
        // Fácil
        document.getElementById('diffFacilLabel').innerText = `${diff.facil.rate}% (${diff.facil.correct}/${diff.facil.total})`;
        document.getElementById('diffFacilBar').style.width = `${diff.facil.rate}%`;

        // Médio
        document.getElementById('diffMedioLabel').innerText = `${diff.medio.rate}% (${diff.medio.correct}/${diff.medio.total})`;
        document.getElementById('diffMedioBar').style.width = `${diff.medio.rate}%`;

        // Difícil
        document.getElementById('diffDificilLabel').innerText = `${diff.dificil.rate}% (${diff.dificil.correct}/${diff.dificil.total})`;
        document.getElementById('diffDificilBar').style.width = `${diff.dificil.rate}%`;
    }

    // 5. Renderizar Competências de Redação com Benchmark INEP / educabR
    function renderRedacoes(redData, inepBenchmark) {
        const ctx = document.getElementById('chartCompetencias').getContext('2d');
        const colors = getChartColors();
        const c = redData.competencias;
        const inepC = (inepBenchmark && inepBenchmark.competencias) ? inepBenchmark.competencias : { c1: 126, c2: 147, c3: 124, c4: 136, c5: 117 };

        if (competenciasChartInstance) {
            competenciasChartInstance.destroy();
        }

        competenciasChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['C1 (Gramática)', 'C2 (Tema)', 'C3 (Projeto)', 'C4 (Coesão)', 'C5 (Proposta)'],
                datasets: [
                    {
                        label: 'Sua Média (HipoGabarito)',
                        data: [c.c1, c.c2, c.c3, c.c4, c.c5],
                        backgroundColor: '#8b5cf6',
                        borderRadius: 8,
                        borderWidth: 0,
                        maxBarThickness: 26
                    },
                    {
                        label: 'Média Nacional Oficial (INEP)',
                        data: [inepC.c1, inepC.c2, inepC.c3, inepC.c4, inepC.c5],
                        backgroundColor: '#cbd5e1',
                        borderRadius: 8,
                        borderWidth: 0,
                        maxBarThickness: 26
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: colors.textColor, font: { family: 'Outfit', size: 10, weight: '700' } }
                    },
                    y: {
                        suggestedMin: 0,
                        suggestedMax: 200,
                        grid: { color: colors.gridColor },
                        ticks: { color: colors.textColor, stepSize: 40, font: { size: 10 } }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            color: colors.textColor,
                            font: { family: 'Outfit', size: 10, weight: '700' },
                            boxWidth: 12,
                            padding: 10
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ` ${context.dataset.label}: ${context.raw} / 200 pontos`;
                            }
                        }
                    }
                }
            }
        });

        // Lista recente de redações
        const recentList = document.getElementById('recentRedacoesList');
        if (!redData.recent || redData.recent.length === 0) {
            recentList.innerHTML = `
                <div class="text-center py-6 text-slate-400 text-xs">
                    <i class="bi bi-pencil-square text-2xl block mb-1"></i>
                    Nenhuma redação enviada ainda.
                </div>
            `;
        } else {
            recentList.innerHTML = '';
            redData.recent.forEach(r => {
                const dateStr = new Date(r.created_at).toLocaleDateString('pt-BR');
                const div = document.createElement('a');
                div.href = `redacao_resultado.php?id=${r.id}`;
                div.className = 'flex items-center justify-between p-2.5 rounded-xl bg-white hover:bg-purple-50/50 border border-slate-200 transition-all block text-decoration-none';
                div.innerHTML = `
                    <div class="min-w-0 pr-2">
                        <h5 class="text-xs font-bold text-slate-900 truncate mb-0">${r.titulo}</h5>
                        <div class="flex items-center gap-2 text-[10px] text-slate-400 mt-0.5">
                            <span class="font-bold text-purple-600">${r.banca}</span>
                            <span>•</span>
                            <span>${dateStr}</span>
                        </div>
                    </div>
                    <span class="font-outfit font-black text-sm text-purple-700 bg-purple-100 px-2.5 py-1 rounded-lg shrink-0">
                        ${r.nota_final} pts
                    </span>
                `;
                recentList.appendChild(div);
            });
        }
    }

    // 6. Renderizar Consistência Diária de XP (14 Dias)
    function renderDailyXP(dailyData) {
        const ctx = document.getElementById('chartDailyXP').getContext('2d');
        const colors = getChartColors();

        const labels = dailyData.map(d => d.label);
        const xpValues = dailyData.map(d => d.xp);

        if (dailyXpChartInstance) {
            dailyXpChartInstance.destroy();
        }

        dailyXpChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        type: 'line',
                        label: 'Meta Diária (50 XP)',
                        data: Array(labels.length).fill(50),
                        borderColor: '#f59e0b',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointRadius: 0,
                        fill: false
                    },
                    {
                        type: 'bar',
                        label: 'XP Conquistado',
                        data: xpValues,
                        backgroundColor: xpValues.map(val => val >= 50 ? '#10b981' : '#6366f1'),
                        borderRadius: 6,
                        maxBarThickness: 28
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: colors.textColor, font: { family: 'Outfit', size: 10, weight: '700' } }
                    },
                    y: {
                        suggestedMin: 0,
                        suggestedMax: 80,
                        grid: { color: colors.gridColor },
                        ticks: { color: colors.textColor, font: { size: 10 } }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            boxWidth: 12,
                            color: colors.textColor,
                            font: { family: 'Outfit', size: 11, weight: '700' }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ` ${context.dataset.label}: ${context.raw} XP`;
                            }
                        }
                    }
                }
            }
        });
    }

    // 7. Renderizar Tabela de Simulados
    function renderSimuladosTable(simulados) {
        const tbody = document.getElementById('simuladosTableBody');
        if (!simulados || simulados.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-6 text-slate-400">
                        Nenhum simulado registrado até o momento.
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = '';
        simulados.forEach(s => {
            const dateStr = new Date(s.started_at).toLocaleDateString('pt-BR');
            const minSpent = Math.round((s.time_spent_sec || 0) / 60);

            const tr = document.createElement('tr');
            tr.className = 'hover:bg-slate-50 transition-colors';
            tr.innerHTML = `
                <td class="py-3">
                    <span class="inline-flex items-center gap-1.5 uppercase font-black text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-md">
                        <i class="bi bi-patch-check-fill text-indigo-500"></i> ${s.exam_type}
                    </span>
                </td>
                <td class="py-3 text-center">
                    <span class="text-slate-800 font-bold">${s.total_correct}</span> / <span class="text-slate-400">${s.total_questions}</span>
                </td>
                <td class="py-3 text-center">
                    <span class="font-outfit font-black px-2 py-0.5 rounded-md ${s.score >= 70 ? 'bg-emerald-100 text-emerald-800' : (s.score >= 50 ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600')}">
                        ${s.score}%
                    </span>
                </td>
                <td class="py-3 text-center text-slate-500">
                    ${minSpent > 0 ? `${minSpent} min` : `${s.time_spent_sec || 0}s`}
                </td>
                <td class="py-3 text-center text-indigo-600 font-outfit font-black">
                    +${s.xp_earned || 0} XP
                </td>
                <td class="py-3 text-right text-slate-400 text-[11px]">
                    ${dateStr}
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    function changePeriod(p) {
        loadMetrics(p);
    }

    // Inicialização ao carregar a página
    document.addEventListener('DOMContentLoaded', () => {
        loadMetrics('all');
    });

    // Atualizar gráficos caso o usuário clique no botão de Dark Mode no header
    const originalToggleTheme = window.toggleTheme;
    if (typeof originalToggleTheme === 'function') {
        window.toggleTheme = function() {
            originalToggleTheme();
            setTimeout(() => {
                loadMetrics(currentPeriod);
            }, 100);
        };
    }
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
