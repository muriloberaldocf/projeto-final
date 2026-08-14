/**
 * MOTOR DE LIÇÕES GAMIFICADAS - APROVAQUEST
 * - Sistema de Vidas 100% Removido.
 * - Registra cada resposta correta em tempo real para não repeti-la nos próximos 5 dias.
 * - Dinâmica Avançada de XP (Base + Bônus de Precisão) com Notificação de Level Up.
 */

document.addEventListener('DOMContentLoaded', () => {
    const lessonId = window.LESSON_ID;
    const lessonMode = window.LESSON_MODE || '';
    if (!lessonId) return;

    // Estado da Lição
    let questions = [];
    let currentIndex = 0;
    let selectedOption = null;
    let correctAnswersCount = 0;
    let isAnswerChecked = false;
    let isBossChallenge = (lessonMode === 'boss');

    // Elementos do DOM
    const progressBar = document.getElementById('lessonProgress');
    const optionsContainer = document.getElementById('optionsContainer');
    const examTag = document.getElementById('examTag');
    const modeBadge = document.getElementById('modeBadge');
    const questionText = document.getElementById('questionText');
    const btnCheck = document.getElementById('btnCheck');
    const feedbackDrawer = document.getElementById('feedbackDrawer');
    const feedbackTitle = document.getElementById('feedbackTitle');
    const explanationBox = document.getElementById('explanationBox');
    const btnContinue = document.getElementById('btnContinue');

    // Carregar Questões da Lição via API
    fetch(`api/get_lesson.php?id=${lessonId}&mode=${lessonMode}`)
        .then(res => res.json())
        .then(data => {
            if (!data.success || !data.questions || data.questions.length === 0) {
                alert('Não foram encontradas questões para este tópico.');
                window.location.href = 'dashboard.php';
                return;
            }
            questions = data.questions;

            if (data.is_boss_mode && modeBadge) {
                isBossChallenge = true;
                modeBadge.innerHTML = `<span class="badge bg-danger text-white font-monospace"><i class="bi bi-shield-lock-fill me-1"></i> DESAFIO BOSS (VARIAÇÃO #${data.boss_variant})</span>`;
            }

            // EXIBIR EXPLICAÇÃO TEÓRICA E LEITURA RECOMENDADA NO CABEÇALHO DA LIÇÃO
            const introBox = document.getElementById('lessonIntroBox');
            const introContentText = document.getElementById('introContentText');
            const videoContainer = document.getElementById('videoContainer');
            const introContentContainer = document.getElementById('introContentContainer');

            let hasHeaderContent = false;

            // 1. Renderizar Card do Toda Matéria / Leitura Recomendada
            if (data.video_url && videoContainer) {
                videoContainer.innerHTML = `
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between p-3.5 rounded-3 bg-emerald-50 border border-emerald-200 gap-3 mb-2 shadow-sm">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-2.5 rounded-3 bg-white text-emerald-600 shadow-sm border border-emerald-200 fs-3 flex-shrink-0">
                                <i class="bi bi-journal-bookmark-fill"></i>
                            </div>
                            <div>
                                <span class="badge bg-emerald-600 text-white font-monospace text-xs mb-1 px-2 py-0.5">TODA MATÉRIA</span>
                                <h6 class="fw-bold font-outfit text-dark mb-0 fs-6">${data.video_title || 'Artigo Teórico Completo'}</h6>
                                <p class="text-muted small mb-0">Consulte o conteúdo completo no Toda Matéria para aprofundar seus estudos.</p>
                            </div>
                        </div>
                        <a href="${data.video_url}" target="_blank" rel="noopener noreferrer" class="btn btn-sm font-outfit fw-bold px-3.5 py-2 text-xs rounded-pill flex-shrink-0 text-white shadow-sm" style="background-color: #059669;">
                            Ler no Toda Matéria <i class="bi bi-box-arrow-up-right ms-1"></i>
                        </a>
                    </div>
                `;
                videoContainer.style.display = 'block';
                hasHeaderContent = true;
            }

            // 2. Renderizar Resumo Teórico Aprofundado
            if (data.intro_text && introContentText) {
                const formattedIntro = data.intro_text
                    .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                    .replace(/\n\n/g, '<br><br>')
                    .replace(/\n/g, '<br>');
                introContentText.innerHTML = formattedIntro;
                introContentText.style.display = 'block';
                hasHeaderContent = true;
            }

            if (hasHeaderContent && introBox) {
                introBox.style.display = 'block';

                const btnToggleIntro = document.getElementById('btnToggleIntro');
                if (btnToggleIntro && introContentContainer) {
                    btnToggleIntro.onclick = () => {
                        if (introContentContainer.style.display === 'none') {
                            introContentContainer.style.display = 'block';
                            btnToggleIntro.innerHTML = '<i class="bi bi-chevron-up me-1"></i> Ocultar';
                        } else {
                            introContentContainer.style.display = 'none';
                            btnToggleIntro.innerHTML = '<i class="bi bi-chevron-down me-1"></i> Mostrar Conteúdo';
                        }
                    };
                }
            }

            loadQuestion(0);
        })
        .catch(err => {
            console.error('Erro ao carregar lição:', err);
        });

    // Carregar Questão
    function loadQuestion(index) {
        if (index >= questions.length) {
            finishLesson();
            return;
        }

        const q = questions[index];
        selectedOption = null;
        isAnswerChecked = false;

        const progressPercent = Math.round((index / questions.length) * 100);
        if (progressBar) progressBar.style.width = `${progressPercent}%`;

        const questionCounter = document.getElementById('questionCounter');
        if (questionCounter) {
            questionCounter.textContent = `QUESTÃO ${index + 1} DE ${questions.length}`;
        }

        if (feedbackDrawer) {
            feedbackDrawer.classList.remove('show', 'success', 'error');
        }
        btnCheck.disabled = true;

        examTag.textContent = isBossChallenge ? 'CHEFÃO BOSS' : (q.exam_source || 'VESTIBULAR');
        questionText.textContent = q.question_text;

        const options = [
            { letter: 'a', text: q.option_a, key: '1' },
            { letter: 'b', text: q.option_b, key: '2' },
            { letter: 'c', text: q.option_c, key: '3' },
            { letter: 'd', text: q.option_d, key: '4' },
            { letter: 'e', text: q.option_e, key: '5' }
        ];

        optionsContainer.innerHTML = '';
        options.forEach(opt => {
            if (!opt.text) return;
            const card = document.createElement('div');
            card.className = 'quiz-card-aprova';
            card.dataset.option = opt.letter;
            card.setAttribute('role', 'button');
            card.setAttribute('tabindex', '0');
            card.innerHTML = `
                <div class="quiz-badge-aprova">${opt.letter.toUpperCase()}</div>
                <div class="quiz-text-aprova">${opt.text}</div>
                <div class="quiz-indicator-aprova">
                    <span class="quiz-key-hint d-none d-sm-inline-block">${opt.key}</span>
                    <i class="bi bi-circle quiz-circle-icon"></i>
                    <i class="bi bi-check-circle-fill quiz-check-icon"></i>
                    <i class="bi bi-x-circle-fill quiz-wrong-icon"></i>
                </div>
            `;

            card.addEventListener('click', () => selectOption(opt.letter));
            optionsContainer.appendChild(card);
        });
    }

    // Selecionar Alternativa
    function selectOption(letter) {
        if (isAnswerChecked) return;

        if (typeof sounds !== 'undefined') sounds.playClick();
        selectedOption = letter;

        document.querySelectorAll('.quiz-card-aprova').forEach(card => {
            const circleIcon = card.querySelector('.quiz-circle-icon');
            if (card.dataset.option === letter) {
                card.classList.add('selected');
                if (circleIcon) {
                    circleIcon.className = 'bi bi-record-circle-fill quiz-circle-icon';
                }
            } else {
                card.classList.remove('selected');
                if (circleIcon) {
                    circleIcon.className = 'bi bi-circle quiz-circle-icon';
                }
            }
        });

        btnCheck.disabled = false;
    }

    // Atalhos de Teclado
    document.addEventListener('keydown', (e) => {
        if (isAnswerChecked) {
            if (e.key === 'Enter') {
                btnContinue.click();
            }
            return;
        }

        const keyMap = {
            '1': 'a', 'a': 'a', 'A': 'a',
            '2': 'b', 'b': 'b', 'B': 'b',
            '3': 'c', 'c': 'c', 'C': 'c',
            '4': 'd', 'd': 'd', 'D': 'd',
            '5': 'e', 'e': 'e', 'E': 'e'
        };

        if (keyMap[e.key]) {
            selectOption(keyMap[e.key]);
        } else if (e.key === 'Enter' && !btnCheck.disabled) {
            checkAnswer();
        }
    });

    // Verificação de Resposta
    function checkAnswer() {
        if (!selectedOption || isAnswerChecked) return;
        isAnswerChecked = true;

        const currentQ = questions[currentIndex];
        const isCorrect = (selectedOption === currentQ.correct_option.toLowerCase());

        fetch('api/save_answer.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                question_id: currentQ.id,
                chosen_option: selectedOption,
                is_correct: isCorrect
            })
        }).catch(err => console.error(err));

        const hideResolution = isBossChallenge || currentQ.hide_resolution || currentQ.is_boss;

        // Atualizar estado visual de cada card de alternativa
        document.querySelectorAll('.quiz-card-aprova').forEach(card => {
            const cardOpt = card.dataset.option;
            card.classList.remove('selected');

            if (cardOpt === currentQ.correct_option.toLowerCase()) {
                card.classList.add('is-correct');
            } else if (cardOpt === selectedOption && !isCorrect) {
                card.classList.add('is-wrong');
            } else {
                card.classList.add('is-disabled');
            }
        });

        if (isCorrect) {
            if (typeof sounds !== 'undefined') sounds.playCorrect();
            correctAnswersCount++;
            feedbackDrawer.className = 'feedback-drawer show success';
            feedbackTitle.innerHTML = `<div class="d-flex align-items-center gap-2"><i class="bi bi-check-circle-fill fs-2 text-success"></i><span class="text-success fw-bold font-outfit fs-4">Excelente! Resposta Correta!</span></div>`;

            if (hideResolution) {
                explanationBox.innerHTML = `<em class="text-muted"><i class="bi bi-shield-lock-fill text-danger me-1"></i> Desafio Boss: A resolução detalhada fica oculta para manter o desafio!</em>`;
            } else {
                explanationBox.innerHTML = `<strong>Explicação Resolvida:</strong><br>${currentQ.explanation_text}`;
            }
        } else {
            if (typeof sounds !== 'undefined') sounds.playError();
            feedbackDrawer.className = 'feedback-drawer show error';
            feedbackTitle.innerHTML = `<div class="d-flex align-items-center gap-2"><i class="bi bi-x-circle-fill fs-2 text-danger"></i><span class="text-danger fw-bold font-outfit fs-4">Resposta Incorreta (Gabarito: Opção ${currentQ.correct_option.toUpperCase()})</span></div>`;

            if (hideResolution) {
                explanationBox.innerHTML = `<em class="text-muted"><i class="bi bi-shield-lock-fill text-danger me-1"></i> Desafio Boss: A resolução detalhada fica oculta para manter o desafio!</em>`;
            } else {
                explanationBox.innerHTML = `<strong>Dica & Explicação:</strong><br>${currentQ.explanation_text}`;
            }
        }
    }

    btnCheck.addEventListener('click', checkAnswer);

    btnContinue.addEventListener('click', () => {
        if (feedbackDrawer) {
            feedbackDrawer.classList.remove('show', 'success', 'error');
        }
        currentIndex++;
        loadQuestion(currentIndex);
    });

    // Conclusão da Lição com Validação de Aprovação (>= 75%), Mascote Hipo (Feliz/Triste) e Recompensas
    function finishLesson() {
        const scorePercent = Math.round((correctAnswersCount / questions.length) * 100);

        if (feedbackDrawer) {
            feedbackDrawer.classList.remove('show', 'success', 'error');
            feedbackDrawer.style.display = 'none';
        }
        const footer = document.querySelector('.lesson-footer');
        if (footer) {
            footer.style.display = 'none';
        }

        fetch('api/submit_lesson.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                lesson_id: lessonId,
                score_percent: scorePercent,
                mode: lessonMode
            })
        })
        .then(res => res.json())
        .then(res => {
            const body = document.getElementById('lessonBody');
            const passed = (res.passed !== undefined) ? res.passed : (scorePercent >= 75);
            const redirectUrl = window.SUBJECT_SLUG ? ('dashboard.php?subject=' + encodeURIComponent(window.SUBJECT_SLUG)) : 'dashboard.php';

            if (passed) {
                if (typeof sounds !== 'undefined') sounds.playComplete();
            } else {
                if (typeof sounds !== 'undefined') sounds.playError();
            }

            let levelUpHtml = '';
            if (passed && res.leveled_up) {
                levelUpHtml = `
                    <div class="alert alert-warning border-warning shadow-sm rounded-3 py-3 mb-4 text-center">
                        <i class="bi bi-stars text-warning fs-3 d-block mb-1"></i>
                        <h4 class="fw-bold text-dark mb-0">SUBIU DE NÍVEL!</h4>
                        <span class="badge bg-warning text-dark font-monospace mt-1 px-3 py-1 fs-6">NÍVEL ${res.level} CONQUISTADO</span>
                    </div>
                `;
            }

            if (passed) {
                // TELA DE VITÓRIA / APROVADO (>= 75%)
                body.innerHTML = `
                    ${levelUpHtml}
                    <div class="card card-aprova text-center p-4 p-md-5 my-3 shadow-lg border-2 border-emerald-200">
                        <div class="mb-3">
                            <i class="bi bi-emoji-laughing fs-1 text-success"></i>
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-success-subtle text-success border border-success fw-bold px-3 py-1.5 fs-6 rounded-pill">
                                <i class="bi bi-check-circle-fill me-1"></i> APROVADO (${scorePercent}%)
                            </span>
                        </div>
                        <h3 class="fw-bold text-dark mb-1">${isBossChallenge ? 'Desafio Boss Superado!' : 'Fase Concluída com Sucesso!'}</h3>
                        <p class="text-secondary small mb-4">Parabéns! Você alcançou a nota de corte mínima de 75% e dominou esta etapa!</p>

                        <div class="row g-3 mb-4">
                            <div class="col-4">
                                <div class="p-3 bg-light border rounded-3">
                                    <span class="d-block small text-muted font-monospace">XP BASE</span>
                                    <span class="fs-5 fw-bold text-primary">+${res.base_xp || 35}</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3 bg-light border rounded-3">
                                    <span class="d-block small text-muted font-monospace">BÔNUS</span>
                                    <span class="fs-5 fw-bold text-success">+${res.accuracy_bonus || 0}</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3 bg-indigo-subtle border border-indigo-subtle rounded-3">
                                    <span class="d-block small text-primary font-monospace fw-bold">TOTAL XP</span>
                                    <span class="fs-4 fw-extrabold text-primary">+${res.xp_gained}</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 border mb-4">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-fire text-warning fs-4"></i>
                                <span class="fw-bold text-dark">Ofensiva Diária</span>
                            </div>
                            <span class="badge bg-warning text-dark font-monospace px-3 py-1.5 fs-6">${res.streak_days} DIAS</span>
                        </div>

                        <button type="button" onclick="window.location.href='${redirectUrl}'" class="btn btn-aprova-primary py-3 fw-bold w-100 fs-6 shadow">
                            VOLTAR À TRILHA DE ESTUDOS
                        </button>
                    </div>
                `;
            } else {
                // TELA DE DERROTA / REPROVADO (< 75%)
                body.innerHTML = `
                    <div class="card card-aprova text-center p-4 p-md-5 my-3 shadow-lg border-2 border-rose-200">
                        <div class="mb-3">
                            <i class="bi bi-emoji-frown fs-1 text-danger"></i>
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-danger-subtle text-danger border border-danger fw-bold px-3 py-1.5 fs-6 rounded-pill">
                                <i class="bi bi-x-circle-fill me-1"></i> REPROVADO (${scorePercent}%)
                            </span>
                        </div>
                        <h3 class="fw-bold text-dark mb-1">Não foi desta vez!</h3>
                        <p class="text-secondary small mb-4">Você teve <strong>${scorePercent}%</strong> de acertos. É necessário acertar no mínimo <strong>75%</strong> das questões para ser aprovado nesta atividade.</p>

                        <div class="p-3 bg-rose-50 border border-rose-200 rounded-3 mb-4 text-start">
                            <div class="d-flex align-items-center gap-2 text-rose-700 fw-bold small mb-1">
                                <i class="bi bi-lightbulb-fill fs-5"></i> Dica de Estudo:
                            </div>
                            <p class="text-rose-900 small mb-0">Revise o material teórico e os vídeos explicativos no topo da lição antes de tentar novamente!</p>
                        </div>

                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <button type="button" onclick="window.location.reload()" class="btn btn-aprova-primary py-3 fw-bold flex-fill fs-6 shadow">
                                <i class="bi bi-arrow-clockwise me-1"></i> Tentar Novamente
                            </button>
                            <button type="button" onclick="window.location.href='${redirectUrl}'" class="btn btn-light border py-3 fw-bold flex-fill fs-6 text-secondary">
                                Voltar à Trilha
                            </button>
                        </div>
                    </div>
                `;
            }
        })
        .catch(err => {
            console.error('Erro ao enviar progresso:', err);
            const redirectUrl = window.SUBJECT_SLUG ? ('dashboard.php?subject=' + encodeURIComponent(window.SUBJECT_SLUG)) : 'dashboard.php';
            window.location.href = redirectUrl;
        });
    }
        });
