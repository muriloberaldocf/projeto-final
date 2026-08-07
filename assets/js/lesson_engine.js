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

            // EXIBIR EXPLICAÇÃO TEÓRICA E VÍDEO-AULA NO CABEÇALHO SUPERIOR DA LIÇÃO
            const introBox = document.getElementById('lessonIntroBox');
            const introContentText = document.getElementById('introContentText');
            const videoContainer = document.getElementById('videoContainer');
            const videoIframe = document.getElementById('videoIframe');
            const videoTitleText = document.getElementById('videoTitleText');
            const introContentContainer = document.getElementById('introContentContainer');

            let hasHeaderContent = false;

            // 1. Renderizar Vídeo-Aula (Local MP4 ou YouTube Embed)
            if (data.video_url && videoContainer) {
                const videoWrapper = document.getElementById('videoWrapper') || videoContainer.querySelector('.ratio');
                if (data.video_title && videoTitleText) {
                    videoTitleText.textContent = data.video_title;
                }

                if (videoWrapper) {
                    if (data.video_url.endsWith('.mp4') || data.video_url.endsWith('.webm') || data.video_url.includes('/vids/')) {
                        videoWrapper.innerHTML = `
                            <video controls controlsList="nodownload" class="w-100 h-100 object-fit-contain bg-black">
                                <source src="${data.video_url}" type="video/mp4">
                                Seu navegador não suporta a reprodução de vídeos HTML5.
                            </video>
                        `;
                    } else {
                        videoWrapper.innerHTML = `
                            <iframe class="w-100 h-100 border-0" src="${data.video_url}" title="Vídeo Aula" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                        `;
                    }
                }
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

        if (feedbackDrawer) {
            feedbackDrawer.classList.remove('show', 'success', 'error');
        }
        btnCheck.disabled = true;

        examTag.textContent = isBossChallenge ? 'CHEFÃO BOSS' : (q.exam_source || 'QUESTÃO');
        questionText.textContent = q.question_text;

        const options = [
            { letter: 'a', text: q.option_a },
            { letter: 'b', text: q.option_b },
            { letter: 'c', text: q.option_c },
            { letter: 'd', text: q.option_d },
            { letter: 'e', text: q.option_e }
        ];

        optionsContainer.innerHTML = '';
        options.forEach(opt => {
            const card = document.createElement('div');
            card.className = 'quiz-card-aprova';
            card.dataset.option = opt.letter;
            card.innerHTML = `
                <div class="quiz-badge-aprova">${opt.letter.toUpperCase()}</div>
                <div>${opt.text}</div>
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
            if (card.dataset.option === letter) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
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

        if (isCorrect) {
            if (typeof sounds !== 'undefined') sounds.playCorrect();
            correctAnswersCount++;
            feedbackDrawer.className = 'feedback-drawer show success';
            feedbackTitle.innerHTML = `<i class="bi bi-check-circle-fill text-success me-2"></i> Resposta Correta!`;

            if (hideResolution) {
                explanationBox.innerHTML = `<em class="text-muted"><i class="bi bi-shield-lock-fill text-danger me-1"></i> Desafio Boss: A resolução detalhada fica oculta para manter o desafio!</em>`;
            } else {
                explanationBox.innerHTML = `<strong>Explicação:</strong><br>${currentQ.explanation_text}`;
            }
        } else {
            if (typeof sounds !== 'undefined') sounds.playError();
            feedbackDrawer.className = 'feedback-drawer show error';
            feedbackTitle.innerHTML = `<i class="bi bi-x-circle-fill text-danger me-2"></i> Resposta Incorreta (Gabarito: Opção ${currentQ.correct_option.toUpperCase()})`;

            if (hideResolution) {
                explanationBox.innerHTML = `<em class="text-muted"><i class="bi bi-shield-lock-fill text-danger me-1"></i> Desafio Boss: A resolução detalhada fica oculta para manter o desafio!</em>`;
            } else {
                explanationBox.innerHTML = `<strong>Explicação:</strong><br>${currentQ.explanation_text}`;
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

    // Conclusão da Lição com Card de Recompensa de XP e Notificação de Level Up
    function finishLesson() {
        if (typeof sounds !== 'undefined') sounds.playComplete();
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
            
            let levelUpHtml = '';
            if (res.leveled_up) {
                levelUpHtml = `
                    <div class="alert alert-warning border-warning shadow-sm rounded-3 py-3 mb-4 text-center">
                        <i class="bi bi-stars text-warning fs-3 d-block mb-1"></i>
                        <h4 class="fw-bold text-dark mb-0">SUBIU DE NÍVEL!</h4>
                        <span class="badge bg-warning text-dark font-monospace mt-1 px-3 py-1 fs-6">NÍVEL ${res.level} CONQUISTADO</span>
                    </div>
                `;
            }

                    const redirectUrl = window.SUBJECT_SLUG ? ('dashboard.php?subject=' + encodeURIComponent(window.SUBJECT_SLUG)) : 'dashboard.php';

                    body.innerHTML = `
                        ${levelUpHtml}
                        <div class="card card-aprova text-center p-5 my-3 shadow-lg border-2">
                            <div class="mb-3">
                                <i class="bi ${isBossChallenge ? 'bi-fire text-danger' : 'bi-trophy-fill text-warning'} display-3"></i>
                            </div>
                            <h3 class="fw-bold text-dark mb-1">${isBossChallenge ? 'Desafio Boss Superado!' : 'Fase Concluída!'}</h3>
                            <p class="text-secondary small mb-4">Você completou os exercícios desta etapa!</p>
                            
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
                })
                .catch(err => {
                    console.error('Erro ao enviar progresso:', err);
                    const redirectUrl = window.SUBJECT_SLUG ? ('dashboard.php?subject=' + encodeURIComponent(window.SUBJECT_SLUG)) : 'dashboard.php';
                    window.location.href = redirectUrl;
                });
            }
        });
