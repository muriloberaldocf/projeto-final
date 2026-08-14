<?php
require_once __DIR__ . '/config/db.php';
checkAuth();

$pageTitle = 'Simulado Cronometrado — HipoGabarito';
require_once __DIR__ . '/includes/header.php';
?>
    <link rel="stylesheet" href="assets/css/simulado.css?v=<?= time() ?>">

    <!-- LAYOUT PRINCIPAL DO SITE -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- SIDEBAR MODULAR COM DESTAQUE EM ROXO -->
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

            <!-- CONTEÚDO PRINCIPAL DOS SIMULADOS -->
            <section class="lg:col-span-9 space-y-6">

                <!-- STATE 1: Configuração do Simulado -->
                <div id="screenSelect" class="bg-white rounded-3xl border-2 border-slate-200 p-6 sm:p-8 shadow-[0_4px_0_0_#e2e8f0] space-y-8">
                    <!-- Banner de Título -->
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-6 rounded-2xl shadow-md flex items-center justify-between gap-4">
                        <div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-white/20 text-amber-200 uppercase tracking-wider mb-2">
                                <i class="bi bi-clock-history"></i> PROVA SIMULADA CONTEXTUAL
                            </span>
                            <h1 class="text-2xl sm:text-3xl font-extrabold font-outfit text-white mb-1">Simulado Cronometrado</h1>
                            <p class="text-xs sm:text-sm text-indigo-100 font-medium mb-0">Treine com tempo limite e pontuação real de aprovação (75%+).</p>
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
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="exam-card selected cursor-pointer p-4 rounded-2xl border-2 border-indigo-600 bg-indigo-50/50 hover:border-indigo-600 transition-all" onclick="selectExamType('enem', this)">
                                <div class="exam-icon w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-xl mb-3"><i class="bi bi-journal-text"></i></div>
                                <h3 class="font-outfit font-extrabold text-base text-slate-900 mb-1">ENEM</h3>
                                <p class="text-xs text-slate-500 mb-0">Questões contextualizadas no modelo oficial do ENEM</p>
                            </div>
                            <div class="exam-card cursor-pointer p-4 rounded-2xl border-2 border-slate-200 hover:border-indigo-400 transition-all" onclick="selectExamType('fuvest', this)">
                                <div class="exam-icon w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-xl mb-3"><i class="bi bi-building"></i></div>
                                <h3 class="font-outfit font-extrabold text-base text-slate-900 mb-1">FUVEST</h3>
                                <p class="text-xs text-slate-500 mb-0">Questões analíticas e conteudistas da USP</p>
                            </div>
                            <div class="exam-card cursor-pointer p-4 rounded-2xl border-2 border-slate-200 hover:border-indigo-400 transition-all" onclick="selectExamType('unicamp', this)">
                                <div class="exam-icon w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-xl mb-3"><i class="bi bi-mortarboard"></i></div>
                                <h3 class="font-outfit font-extrabold text-base text-slate-900 mb-1">UNICAMP</h3>
                                <p class="text-xs text-slate-500 mb-0">Foco em interpretação de texto e interdisciplinaridade</p>
                            </div>
                            <div class="exam-card cursor-pointer p-4 rounded-2xl border-2 border-slate-200 hover:border-indigo-400 transition-all" onclick="selectExamType('misto', this)">
                                <div class="exam-icon w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-xl mb-3"><i class="bi bi-shuffle"></i></div>
                                <h3 class="font-outfit font-extrabold text-base text-slate-900 mb-1">Misto Aleatório</h3>
                                <p class="text-xs text-slate-500 mb-0">Mistura surpresa de grandes vestibulares do Brasil</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quantidade e Tempo -->
                    <div>
                        <h2 class="text-lg font-extrabold font-outfit mb-4 text-slate-800 flex items-center gap-2">
                            <i class="bi bi-2-circle-fill text-indigo-600"></i> Quantidade de Questões e Tempo
                        </h2>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <button type="button" class="pill-selector p-3 rounded-2xl border-2 border-slate-200 font-outfit font-bold text-xs text-slate-700 hover:border-indigo-400 transition-all text-center" onclick="selectQuestionCount(15, 30, this)">15 Q • 30min</button>
                            <button type="button" class="pill-selector selected p-3 rounded-2xl border-2 border-indigo-600 bg-indigo-600 text-white font-outfit font-extrabold text-xs shadow-md transition-all text-center" onclick="selectQuestionCount(30, 60, this)">30 Q • 1h</button>
                            <button type="button" class="pill-selector p-3 rounded-2xl border-2 border-slate-200 font-outfit font-bold text-xs text-slate-700 hover:border-indigo-400 transition-all text-center" onclick="selectQuestionCount(45, 90, this)">45 Q • 1h30</button>
                            <button type="button" class="pill-selector p-3 rounded-2xl border-2 border-slate-200 font-outfit font-bold text-xs text-slate-700 hover:border-indigo-400 transition-all text-center" onclick="selectQuestionCount(90, 180, this)">90 Q • 3h</button>
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

                <!-- STATE 2: Prova em Execução -->
                <div id="screenExam" class="hidden bg-white rounded-3xl border-2 border-slate-200 shadow-[0_4px_0_0_#e2e8f0] overflow-hidden">
                    <!-- Exam Header -->
                    <div class="bg-slate-900 text-white p-4 px-6 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <button onclick="confirmExit()" class="text-slate-400 hover:text-rose-400 transition-colors p-1" title="Sair do Simulado">
                                <i class="bi bi-x-lg text-lg"></i>
                            </button>
                            <div class="h-6 w-px bg-slate-700"></div>
                            <div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Progresso da Prova</div>
                                <div id="progressText" class="font-outfit font-bold text-white text-sm">Questão 1 de 30</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div id="timerDisplay" class="bg-slate-800 px-3.5 py-1.5 rounded-xl border border-slate-700 font-monospace text-amber-400 font-bold text-sm flex items-center gap-2">
                                <i class="bi bi-stopwatch-fill"></i>
                                <span id="timeRemaining">60:00</span>
                            </div>
                            <button onclick="finishSimulado()" class="bg-rose-600 hover:bg-rose-700 text-white font-outfit font-extrabold text-xs px-4 py-2 rounded-xl shadow-sm transition">
                                Entregar Prova
                            </button>
                        </div>
                    </div>

                    <!-- Exam Body -->
                    <div class="p-6 grid grid-cols-1 md:grid-cols-12 gap-6">
                        <!-- Mapa da Prova (Grid de Questões) -->
                        <div class="md:col-span-4 bg-slate-50 p-4 rounded-2xl border border-slate-200">
                            <h3 class="font-outfit font-extrabold text-xs text-slate-500 uppercase tracking-wider mb-3">Mapa de Respostas</h3>
                            <div id="questionGrid" class="question-grid grid grid-cols-5 gap-2 max-h-72 overflow-y-auto pr-1">
                                <!-- Populated by JS -->
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-200 space-y-1.5 text-[11px] font-bold text-slate-500">
                                <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-white border border-slate-300"></span> Pendente</div>
                                <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-indigo-100 border border-indigo-400"></span> Respondida</div>
                                <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span> Questão Atual</div>
                            </div>
                        </div>

                        <!-- Área da Questão -->
                        <div class="md:col-span-8 space-y-4">
                            <div class="flex items-center gap-2">
                                <span id="qSource" class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg text-xs font-bold font-monospace border border-slate-200">ENEM 2023</span>
                                <span id="qSubject" class="px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-bold font-outfit border border-indigo-200">Matemática</span>
                            </div>

                            <div id="qText" class="text-slate-800 text-sm leading-relaxed font-medium bg-slate-50 p-4 rounded-2xl border border-slate-200">
                                Carregando questão...
                            </div>

                            <div id="qOptions" class="space-y-2.5">
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

    <!-- JS ENGINE DO SIMULADO -->
    <script>
        let selectedExam = 'enem';
        let selectedCount = 30;
        let selectedTimeMinutes = 60;
        
        let questionsData = [];
        let userAnswers = {};
        let currentQuestionIndex = 0;
        
        let timerInterval = null;
        let secondsRemaining = 0;
        let totalSeconds = 0;

        function selectExamType(type, el) {
            selectedExam = type;
            document.querySelectorAll('.exam-card').forEach(card => {
                card.classList.remove('selected', 'border-indigo-600', 'bg-indigo-50/50');
                card.classList.add('border-slate-200');
            });
            el.classList.add('selected', 'border-indigo-600', 'bg-indigo-50/50');
            el.classList.remove('border-slate-200');
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

                        document.getElementById('screenSelect').classList.add('hidden');
                        document.getElementById('screenExam').classList.remove('hidden');

                        totalSeconds = selectedTimeMinutes * 60;
                        secondsRemaining = totalSeconds;
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
            timerInterval = setInterval(() => {
                secondsRemaining--;
                updateTimerDisplay();
                if (secondsRemaining <= 0) {
                    clearInterval(timerInterval);
                    Swal.fire('Tempo Esgotado!', 'O tempo do simulado acabou. Suas respostas serão enviadas.', 'info').then(() => {
                        finishSimulado();
                    });
                }
            }, 1000);
        }

        function updateTimerDisplay() {
            const mins = Math.floor(secondsRemaining / 60);
            const secs = secondsRemaining % 60;
            document.getElementById('timeRemaining').textContent = 
                `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }

        function renderQuestionGrid() {
            const grid = document.getElementById('questionGrid');
            grid.innerHTML = '';
            questionsData.forEach((q, idx) => {
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
            const q = questionsData[index];

            document.getElementById('progressText').textContent = `Questão ${index + 1} de ${questionsData.length}`;
            document.getElementById('qSource').textContent = q.source || 'VESTIBULAR';
            document.getElementById('qSubject').textContent = q.subject || 'Geral';
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
                optDiv.className = `p-3.5 rounded-xl border-2 cursor-pointer transition-all flex items-center gap-3 text-xs sm:text-sm font-medium ${isSelected ? 'border-indigo-600 bg-indigo-50/50 text-indigo-900 fw-bold' : 'border-slate-200 hover:border-indigo-300 bg-white text-slate-700'}`;
                optDiv.onclick = () => selectOption(q.id, opt.key);

                optDiv.innerHTML = `
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center font-bold text-xs uppercase ${isSelected ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-500'}">
                        ${opt.key}
                    </div>
                    <div class="flex-1">${opt.text}</div>
                `;
                optionsContainer.appendChild(optDiv);
            });

            document.getElementById('btnPrev').disabled = index === 0;
            document.getElementById('btnNext').textContent = index === questionsData.length - 1 ? 'Concluir' : 'Próxima >';
            
            updateQuestionGridState();
        }

        function selectOption(qId, optionKey) {
            userAnswers[qId] = optionKey;
            renderQuestion(currentQuestionIndex);
        }

        function prevQuestion() {
            if (currentQuestionIndex > 0) renderQuestion(currentQuestionIndex - 1);
        }

        function nextQuestion() {
            if (currentQuestionIndex < questionsData.length - 1) {
                renderQuestion(currentQuestionIndex + 1);
            } else {
                finishSimulado();
            }
        }

        function confirmExit() {
            Swal.fire({
                title: 'Desistiu do Simulado?',
                text: 'Seu progresso atual não será salvo.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sim, sair',
                cancelButtonText: 'Continuar Prova'
            }).then((res) => {
                if (res.isConfirmed) {
                    clearInterval(timerInterval);
                    window.location.href = 'simulado.php';
                }
            });
        }

        function finishSimulado() {
            clearInterval(timerInterval);

            Swal.fire({
                title: 'Calculando Resultados...',
                text: 'Processando respostas e contabilizando XP',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            const timeSpentSecs = totalSeconds - secondsRemaining;

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
        }
    </script>
    <?php require_once __DIR__ . '/includes/footer.php'; ?>
