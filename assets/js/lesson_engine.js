/**
 * MOTOR DE LIÇÕES GAMIFICADAS - APROVAQUEST
 * - Sistema de vidas totalmente removido.
 * - Registra cada resposta correta em tempo real para não repeti-la nos próximos 5 dias.
 * - Ocultação de resolução para o Modo Chefão Boss.
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

            // Se for Modo Chefão Boss
            if (data.is_boss_mode && modeBadge) {
                isBossChallenge = true;
                modeBadge.innerHTML = `<span class="badge bg-danger text-white font-monospace"><i class="bi bi-shield-lock-fill me-1"></i> DESAFIO BOSS (VARIAÇÃO #${data.boss_variant})</span>`;
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

        // Barra de Progresso
        const progressPercent = Math.round((index / questions.length) * 100);
        if (progressBar) progressBar.style.width = `${progressPercent}%`;

        // Ocultar Feedback Drawer
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

        sounds.playClick();
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

        // Salvar resposta no banco em segundo plano para o filtro de 5 dias
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
            sounds.playCorrect();
            correctAnswersCount++;
            feedbackDrawer.className = 'feedback-drawer show success';
            feedbackTitle.innerHTML = `<i class="bi bi-check-circle-fill text-success me-2"></i> Resposta Correta!`;

            if (hideResolution) {
                explanationBox.innerHTML = `<em class="text-muted"><i class="bi bi-shield-lock-fill text-danger me-1"></i> Desafio Boss: A resolução detalhada fica oculta para manter o desafio!</em>`;
            } else {
                explanationBox.innerHTML = `<strong>Explicação:</strong><br>${currentQ.explanation_text}`;
            }
        } else {
            sounds.playError();
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

    // Conclusão da Lição
    function finishLesson() {
        sounds.playComplete();
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
                score_percent: scorePercent
            })
        })
        .then(res => res.json())
        .then(res => {
            const body = document.getElementById('lessonBody');
            body.innerHTML = `
                <div class="card card-aprova text-center p-5 my-4 shadow-sm">
                    <i class="bi ${isBossChallenge ? 'bi-shield-lock-fill text-danger' : 'bi-trophy text-warning'} display-4 mb-3"></i>
                    <h3 class="fw-bold text-dark mb-2">${isBossChallenge ? 'Desafio Boss Concluído!' : 'Tópico Concluído!'}</h3>
                    <p class="text-secondary mb-4">${isBossChallenge ? 'As questões que você acertou ficarão gravadas e não serão repetidas pelos próximos 5 dias!' : 'As questões que você acertou não se repetirão pelos próximos 5 dias.'}</p>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="p-3 bg-light border rounded-3">
                                <span class="d-block small text-muted text-uppercase fw-semibold">XP Ganho</span>
                                <span class="fs-4 fw-bold text-warning">+${res.xp_gained || (isBossChallenge ? 50 : 20)} XP</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light border rounded-3">
                                <span class="d-block small text-muted text-uppercase fw-semibold">Precisão</span>
                                <span class="fs-4 fw-bold text-success">${scorePercent}%</span>
                            </div>
                        </div>
                    </div>

                    <button type="button" onclick="window.location.href='dashboard.php'" class="btn btn-aprova-primary py-2.5 fw-semibold w-100">
                        Voltar à Trilha de Estudos
                    </button>
                </div>
            `;
        })
        .catch(err => {
            console.error('Erro ao enviar progresso:', err);
            window.location.href = 'dashboard.php';
        });
    }
});
