/**
 * MOTOR DE LIÇÕES GAMIFICADAS - HIPOGABARITO
 * - Etapa 1: Tela de Guia Teórico & Resumo de Alto Rendimento com visual Modular 3D.
 * - Etapa 2: Quiz de Exercícios com feedback imediato e revisão teórica em modal.
 * - Suporte completo a Dark Mode, áudio e atalhos de teclado.
 */

// ==========================================================================
// 1. PARSER GLOBAL DE TEORIA (MARKDOWN -> CARDS MODULARES DE ALTO DESTAQUE)
// ==========================================================================
function parseTheoryCardsToHTML(rawText, lessonData = {}) {
    if (!rawText || !rawText.trim()) {
        return `
            <div class="theory-card card-definition">
                <div class="theory-card-header">
                    <div class="theory-card-icon"><i class="bi bi-book-half"></i></div>
                    <h4 class="theory-card-title">Resumo Teórico do Conteúdo</h4>
                </div>
                <div class="theory-card-body">
                    Revise os conceitos essenciais desta etapa antes de resolver os exercícios práticos.
                </div>
            </div>
        `;
    }

    // Normalizar quebras de linha
    let text = rawText.replace(/\r\n/g, '\n').replace(/\r/g, '\n').trim();

    // Remover título inicial caso comece com 📚 **Título** (já exibido no Hero)
    text = text.replace(/^📚\s*\*\*.*?\*\*\s*\n*/, '');

    // Dividir em seções lógicas baseadas em títulos destacados (**Título:** ou ⚠️ **...** ou 💡 **...**)
    const sections = [];
    const lines = text.split('\n');
    let currentSection = null;

    lines.forEach(line => {
        const trimmed = line.trim();
        if (!trimmed) {
            if (currentSection && currentSection.content.length > 0) {
                currentSection.content.push('');
            }
            return;
        }

        // Detectar se a linha é um cabeçalho de seção
        const isHeader = /^([📚💡⚠️🎯📐🧠📝🔍]?\s*\*\*[^*]+\*\*[:?]?)/i.test(trimmed);

        if (isHeader) {
            if (currentSection) {
                sections.push(currentSection);
            }
            currentSection = {
                rawHeader: trimmed,
                content: []
            };
        } else {
            if (!currentSection) {
                currentSection = {
                    rawHeader: '**O que é?**',
                    content: []
                };
            }
            currentSection.content.push(trimmed);
        }
    });

    if (currentSection) {
        sections.push(currentSection);
    }

    if (sections.length === 0) {
        return `
            <div class="theory-card card-definition">
                <div class="theory-card-body">
                    ${formatParagraphs(text)}
                </div>
            </div>
        `;
    }

    // Processar cada seção em um Card Específico com Iconografia e Cores Vibrantes
    let html = '';

    sections.forEach(sec => {
        const headerText = sec.rawHeader.replace(/[*📚💡⚠️🎯📐🧠📝🔍]/g, '').trim();
        const lowerHeader = headerText.toLowerCase();
        const bodyContent = sec.content.join('\n').trim();

        if (!bodyContent && !headerText) return;

        // 1. Tipo: O que é? / Definição
        if (lowerHeader.includes('o que é') || lowerHeader.includes('definição') || lowerHeader.includes('conceito inicial')) {
            html += `
                <div class="theory-card card-definition">
                    <div class="theory-card-header">
                        <div class="theory-card-icon"><i class="bi bi-book-half"></i></div>
                        <h4 class="theory-card-title">${escapeHtml(headerText || 'O que é?')}</h4>
                    </div>
                    <div class="theory-card-body">
                        ${formatParagraphs(bodyContent)}
                    </div>
                </div>
            `;
        }
        // 2. Tipo: Conceitos Fundamentais / Pilares
        else if (lowerHeader.includes('conceito') || lowerHeader.includes('pilares') || lowerHeader.includes('fundamento') || lowerHeader.includes('organela')) {
            html += `
                <div class="theory-card card-concepts">
                    <div class="theory-card-header">
                        <div class="theory-card-icon"><i class="bi bi-lightbulb-fill"></i></div>
                        <h4 class="theory-card-title">${escapeHtml(headerText || 'Conceitos Fundamentais')}</h4>
                    </div>
                    <div class="theory-card-body">
                        ${formatBulletList(bodyContent)}
                    </div>
                </div>
            `;
        }
        // 3. Tipo: Fórmulas / Operações / Passo a Passo / Tabela
        else if (lowerHeader.includes('fórmula') || lowerHeader.includes('passo a passo') || lowerHeader.includes('operaç') || lowerHeader.includes('tabela')) {
            html += `
                <div class="theory-card card-formulas">
                    <div class="theory-card-header">
                        <div class="theory-card-icon"><i class="bi bi-calculator-fill"></i></div>
                        <h4 class="theory-card-title">${escapeHtml(headerText || 'Fórmulas & Procedimentos')}</h4>
                    </div>
                    <div class="theory-card-body">
                        ${formatFormulaList(bodyContent)}
                    </div>
                </div>
            `;
        }
        // 4. Tipo: Exemplo Prático Resolvido
        else if (lowerHeader.includes('exemplo') || lowerHeader.includes('exercício resolvido') || lowerHeader.includes('na prática')) {
            html += `
                <div class="theory-card card-example">
                    <div class="theory-card-header">
                        <div class="theory-card-icon"><i class="bi bi-pencil-square"></i></div>
                        <h4 class="theory-card-title">${escapeHtml(headerText || 'Exemplo Prático Resolvido')}</h4>
                    </div>
                    <div class="theory-card-body">
                        <div class="theory-example-content">
                            ${formatParagraphs(bodyContent)}
                        </div>
                    </div>
                </div>
            `;
        }
        // 5. Tipo: Pegadinhas Comuns no Vestibular
        else if (lowerHeader.includes('pegadinha') || lowerHeader.includes('armadilha') || lowerHeader.includes('atenção') || lowerHeader.includes('cuidado')) {
            html += `
                <div class="theory-card card-traps">
                    <div class="theory-card-header">
                        <div class="theory-card-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
                        <h4 class="theory-card-title">${escapeHtml(headerText || 'Pegadinhas Comuns no Vestibular')}</h4>
                    </div>
                    <div class="theory-card-body">
                        ${formatTrapList(bodyContent)}
                    </div>
                </div>
            `;
        }
        // 6. Tipo: Dica de Ouro
        else if (lowerHeader.includes('dica de ouro') || lowerHeader.includes('dica') || lowerHeader.includes('macete')) {
            html += `
                <div class="theory-card card-golden">
                    <div class="theory-card-header">
                        <div class="theory-card-icon"><i class="bi bi-stars"></i></div>
                        <h4 class="theory-card-title">${escapeHtml(headerText || 'Dica de Ouro do Especialista')}</h4>
                    </div>
                    <div class="theory-card-body">
                        <div class="theory-golden-content">
                            ${formatInlineMarkdown(bodyContent)}
                        </div>
                    </div>
                </div>
            `;
        }
        // 7. Genérico / Outros Tópicos
        else {
            html += `
                <div class="theory-card">
                    <div class="theory-card-header">
                        <div class="theory-card-icon"><i class="bi bi-bookmark-star-fill"></i></div>
                        <h4 class="theory-card-title">${escapeHtml(headerText)}</h4>
                    </div>
                    <div class="theory-card-body">
                        ${formatParagraphs(bodyContent)}
                    </div>
                </div>
            `;
        }
    });

    return html;
}

// Helpers de Formatação
function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

function formatInlineMarkdown(text) {
    if (!text) return '';
    return text
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
        .replace(/\*(.*?)\*/g, '<em>$1</em>')
        .replace(/`(.*?)`/g, '<code class="px-1.5 py-0.5 rounded bg-slate-100 text-indigo-700 font-monospace small">$1</code>');
}

function formatParagraphs(text) {
    if (!text) return '';
    const paragraphs = text.split(/\n{2,}/);
    return paragraphs.map(p => {
        const lines = p.split('\n');
        const formattedLines = lines.map(line => formatInlineMarkdown(line));
        return `<p class="mb-3">${formattedLines.join('<br>')}</p>`;
    }).join('');
}

function formatBulletList(text) {
    if (!text) return '';
    const lines = text.split('\n');
    const items = [];
    let currentItem = [];

    lines.forEach(line => {
        const trimmed = line.trim();
        if (/^[•\-\*]\s+/.test(trimmed) || /^\d+\.\s+/.test(trimmed)) {
            if (currentItem.length > 0) {
                items.push(currentItem.join('<br>'));
                currentItem = [];
            }
            currentItem.push(trimmed.replace(/^[•\-\*]\s+/, '').replace(/^\d+\.\s+/, ''));
        } else if (trimmed) {
            currentItem.push(trimmed);
        }
    });
    if (currentItem.length > 0) {
        items.push(currentItem.join('<br>'));
    }

    if (items.length === 0) {
        return formatParagraphs(text);
    }

    return `
        <div class="theory-bullet-list">
            ${items.map((item) => `
                <div class="theory-bullet-item">
                    <div class="theory-bullet-dot"><i class="bi bi-check-lg"></i></div>
                    <div class="flex-grow-1">${formatInlineMarkdown(item)}</div>
                </div>
            `).join('')}
        </div>
    `;
}

function formatFormulaList(text) {
    if (!text) return '';
    const lines = text.split('\n');
    const items = [];

    lines.forEach(line => {
        const trimmed = line.trim();
        if (trimmed) {
            const cleanLine = trimmed.replace(/^[•\-\*]\s+/, '');
            items.push(cleanLine);
        }
    });

    if (items.length === 0) {
        return formatParagraphs(text);
    }

    return `
        <div class="theory-formulas-wrapper">
            ${items.map(item => `
                <div class="theory-formula-pill">
                    <i class="bi bi-braces text-primary me-2"></i> ${formatInlineMarkdown(item)}
                </div>
            `).join('')}
        </div>
    `;
}

function formatTrapList(text) {
    if (!text) return '';
    const lines = text.split('\n');
    const items = [];

    lines.forEach(line => {
        const trimmed = line.trim();
        if (trimmed) {
            const cleanLine = trimmed.replace(/^[•\-\*]\s+/, '');
            items.push(cleanLine);
        }
    });

    if (items.length === 0) {
        return formatParagraphs(text);
    }

    return items.map(item => `
        <div class="theory-trap-item">
            <i class="bi bi-x-circle-fill text-danger me-2"></i> ${formatInlineMarkdown(item)}
        </div>
    `).join('');
}

window.parseTheoryCardsToHTML = parseTheoryCardsToHTML;


// ==========================================================================
// 2. CONTROLADOR PRINCIPAL DA LIÇÃO & DO QUIZ
// ==========================================================================
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
    let lessonDataCache = null;

    // Elementos do DOM
    const theoryScreen = document.getElementById('theoryScreen');
    const quizScreen = document.getElementById('quizScreen');
    const theoryHeaderControls = document.getElementById('theoryHeaderControls');
    const quizHeaderControls = document.getElementById('quizHeaderControls');
    const quizFooter = document.getElementById('quizFooter');
    const theoryCardsContainer = document.getElementById('theoryCardsContainer');
    const reviewModalCardsBody = document.getElementById('reviewModalCardsBody');
    const btnStartQuiz = document.getElementById('btnStartQuiz');
    const btnSkipToQuiz = document.getElementById('btnSkipToQuiz');
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

    // Carregar Dados da Lição e Questões via API
    fetch(`api/get_lesson.php?id=${lessonId}&mode=${lessonMode}`)
        .then(res => res.json())
        .then(data => {
            if (!data.success || !data.questions || data.questions.length === 0) {
                alert('Não foram encontradas questões para este tópico.');
                window.location.href = 'dashboard.php';
                return;
            }

            lessonDataCache = data;
            questions = data.questions;

            if (data.is_boss_mode && modeBadge) {
                isBossChallenge = true;
                modeBadge.innerHTML = `<span class="badge bg-danger text-white font-monospace"><i class="bi bi-shield-lock-fill me-1"></i> DESAFIO BOSS (VARIAÇÃO #${data.boss_variant})</span>`;
            }

            // 1. Preencher Detalhes do Hero Header da Teoria
            const theoryLessonTitle = document.getElementById('theoryLessonTitle');
            const theorySubjectBadge = document.getElementById('theorySubjectBadge');
            const theoryUnitSubtitle = document.getElementById('theoryUnitSubtitle');
            const theoryTodaMateriaBtn = document.getElementById('theoryTodaMateriaBtn');
            const reviewModalTitle = document.getElementById('reviewModalTitle');

            if (theoryLessonTitle) theoryLessonTitle.textContent = data.lesson_title || 'Lição';
            if (reviewModalTitle) reviewModalTitle.textContent = data.lesson_title || 'Lição';
            if (theorySubjectBadge && data.subject_name) {
                theorySubjectBadge.innerHTML = `<i class="bi bi-bookmark-fill me-1"></i> ${data.subject_name}`;
            }
            if (theoryUnitSubtitle && data.unit_title) {
                theoryUnitSubtitle.textContent = `${data.unit_title} • Guia Teórico Completo & Resumo de Alto Rendimento`;
            }

            // Link do Toda Matéria
            if (data.video_url && theoryTodaMateriaBtn) {
                theoryTodaMateriaBtn.innerHTML = `
                    <a href="${data.video_url}" target="_blank" rel="noopener noreferrer" class="theory-meta-badge todamateria-badge font-outfit fw-bold">
                        <i class="bi bi-journal-bookmark-fill"></i> Ler Artigo Completo no Toda Matéria <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                    </a>
                `;
                theoryTodaMateriaBtn.style.display = 'inline-block';
            }

            // 2. Renderizar Cards Modulares de Teoria
            const cardsHtml = parseTheoryCardsToHTML(data.intro_text, data);
            if (theoryCardsContainer) {
                theoryCardsContainer.innerHTML = cardsHtml;
            }
            if (reviewModalCardsBody) {
                reviewModalCardsBody.innerHTML = cardsHtml;
            }

            // Atualizar botão com contagem de questões e XP
            if (btnStartQuiz) {
                btnStartQuiz.innerHTML = `
                    <span>Começar Exercícios (${questions.length} Questões)</span>
                    <i class="bi bi-arrow-right-circle-fill fs-5"></i>
                `;
            }

            // Se for Boss ou tiver parâmetro skip_theory=1 na URL, vai direto ao quiz
            const urlParams = new URLSearchParams(window.location.search);
            if (isBossChallenge || urlParams.get('skip_theory') === '1') {
                startQuiz();
            }
        })
        .catch(err => {
            console.error('Erro ao carregar lição:', err);
        });

    // ==========================================================================
    // 3. TRANSIÇÃO: TEORIA -> QUIZ DE EXERCÍCIOS
    // ==========================================================================
    function startQuiz() {
        if (typeof sounds !== 'undefined') sounds.playClick();

        if (theoryScreen) theoryScreen.style.display = 'none';
        if (theoryHeaderControls) theoryHeaderControls.style.display = 'none';

        if (quizScreen) quizScreen.style.display = 'flex';
        if (quizHeaderControls) {
            quizHeaderControls.style.display = 'flex';
            quizHeaderControls.style.setProperty('display', 'flex', 'important');
        }
        if (quizFooter) quizFooter.style.display = 'block';

        window.scrollTo({ top: 0, behavior: 'smooth' });
        loadQuestion(currentIndex);
    }

    function showTheoryScreen() {
        if (typeof sounds !== 'undefined') sounds.playClick();

        if (quizScreen) quizScreen.style.display = 'none';
        if (quizHeaderControls) {
            quizHeaderControls.style.display = 'none';
            quizHeaderControls.style.setProperty('display', 'none', 'important');
        }
        if (quizFooter) quizFooter.style.display = 'none';

        if (theoryScreen) theoryScreen.style.display = 'block';
        if (theoryHeaderControls) theoryHeaderControls.style.display = 'flex';

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    window.showTheoryScreen = showTheoryScreen;
    window.startQuiz = startQuiz;

    if (btnStartQuiz) btnStartQuiz.addEventListener('click', startQuiz);
    if (btnSkipToQuiz) btnSkipToQuiz.addEventListener('click', startQuiz);

    // ==========================================================================
    // 4. LÓGICA DO QUIZ & QUESTÕES
    // ==========================================================================
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
        
        // Sanitização de segurança: remover qualquer link, URL ou imagem markdown
        let cleanText = (q.question_text || '')
            .replace(/!\[.*?\]\(.*?\)/gi, '')
            .replace(/\[(.*?)\]\((?:https?:\/\/.*?)\)/gi, '$1')
            .replace(/https?:\/\/[^\s\)\"]+/gi, '')
            .replace(/www\.[^\s\)\"]+/gi, '')
            .replace(/(?:Disponível em|Acesso em)[\:\.\,\s]*/gi, '')
            .replace(/\n{3,}/g, '\n\n')
            .trim();

        questionText.textContent = cleanText;
        questionText.style.whiteSpace = 'pre-line';

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
        if (quizScreen && quizScreen.style.display === 'none') {
            if (e.key === 'Enter') {
                startQuiz();
            }
            return;
        }

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
        } else if (e.key === 'Enter' && btnCheck && !btnCheck.disabled) {
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

        function diagnoseUserMistake(question, chosenLetter, correctLetter) {
            if (!chosenLetter || chosenLetter.toLowerCase() === correctLetter.toLowerCase()) return null;

            const chosenKey = 'option_' + chosenLetter.toLowerCase();
            const correctKey = 'option_' + correctLetter.toLowerCase();
            const chosenText = (question[chosenKey] || '').trim();
            const correctText = (question[correctKey] || '').trim();
            const explanation = question.explanation_text || '';
            const qText = question.question_text || '';

            let mistakeReason = '';
            let mistakeType = 'distractor';

            // 1. Procurar se a própria explicação cita a opção do aluno ou distratores específicos
            const optRegex = new RegExp(`(?:alternativa|opção|letra|item)\\s*${chosenLetter}\\b([^\\.\\n]*[\\.\\n])`, 'i');
            const optMatch = explanation.match(optRegex);
            if (optMatch && optMatch[0]) {
                const sentenceMatch = explanation.match(new RegExp(`[^.\\n]*\\b(?:alternativa|opção|letra|item)\\s*${chosenLetter}\\b[^.\\n]*[\\.]?`, 'i'));
                if (sentenceMatch) {
                    mistakeReason = sentenceMatch[0].trim();
                    mistakeType = 'direct_distractor';
                }
            }

            // 2. Análise matemática/quantitativa se ambas as alternativas envolverem números
            if (!mistakeReason) {
                const chosenNumMatch = chosenText.replace(',', '.').match(/[-+]?\d*\.?\d+/);
                const correctNumMatch = correctText.replace(',', '.').match(/[-+]?\d*\.?\d+/);

                if (chosenNumMatch && correctNumMatch) {
                    const chosenVal = parseFloat(chosenNumMatch[0]);
                    const correctVal = parseFloat(correctNumMatch[0]);

                    // Caso A: Soma Linear de Porcentagens
                    const percentMatches = [...qText.matchAll(/(\d+(?:,\d+)?)\s*%/g)];
                    if (percentMatches.length >= 2) {
                        const nums = percentMatches.map(m => {
                            const idx = m.index;
                            const prefix = qText.substring(Math.max(0, idx - 40), idx).toLowerCase();
                            const isNeg = /(desvaloriz|perda|queda|desconto|preju|redu)/.test(prefix);
                            const val = parseFloat(m[1].replace(',', '.'));
                            return isNeg ? -val : val;
                        });
                        const linearSum = nums.reduce((a, b) => a + b, 0);

                        if (Math.abs(chosenVal - linearSum) < 0.1 || (Math.abs(chosenVal - 15.0) < 0.1 && linearSum === 0)) {
                            const expr = nums.map((n, i) => (i > 0 && n >= 0 ? '+ ' : '') + n + '%').join(' ');
                            mistakeReason = `Você provavelmente somou e subtraiu as porcentagens de forma direta (<code>${expr} = ${linearSum.toFixed(1).replace('.', ',')}%</code>). Em aumentos e descontos sucessivos, cada taxa incide sobre o saldo já corrigido (efeito multiplicativo por fatores <code>(1 + i)</code>), e não por adição linear.`;
                            mistakeType = 'linear_sum';
                        }
                    }

                    // Caso B: Esqueceu de subtrair o principal (1,00 ou 100%)
                    if (!mistakeReason) {
                        if (Math.abs(chosenVal - (correctVal + 100)) < 1 || Math.abs(chosenVal - (correctVal / 100 + 1)) < 0.05) {
                            mistakeReason = `Você calculou o fator acumulado total, mas esqueceu de subtrair a base inicial (100% ou 1,00) para encontrar a taxa líquida final de rendimento.`;
                            mistakeType = 'calculation_slip';
                        }
                    }

                    // Caso C: Inversão de sinal ou operação oposta
                    if (!mistakeReason) {
                        if (Math.abs(chosenVal + correctVal) < 0.01 && chosenVal !== correctVal) {
                            mistakeReason = `Houve uma inversão de sinal (positivo/negativo). Atenção ao sentido das grandezas no enunciado (por exemplo, desaceleração vs aceleração ou perda vs ganho).`;
                            mistakeType = 'sign_error';
                        }
                    }

                    // Caso D: Deslize de cálculo próximo ao valor correto
                    if (!mistakeReason && Math.abs(chosenVal - correctVal) / Math.max(1, Math.abs(correctVal)) < 0.35) {
                        mistakeReason = `Você seguiu a linha de raciocínio correta da fórmula, mas cometeu uma pequena imprecisão aritmética nas multiplicações ou simplificações intermediárias.`;
                        mistakeType = 'calculation_slip';
                    }

                    // Caso E: Fator de 10 ou conversão de unidades
                    if (!mistakeReason) {
                        const ratio = chosenVal / (correctVal || 1);
                        if (Math.abs(ratio - 10) < 0.1 || Math.abs(ratio - 0.1) < 0.01 || Math.abs(ratio - 1000) < 1 || Math.abs(ratio - 0.001) < 0.0001) {
                            mistakeReason = `Atenção à conversão de unidades de medida (como metros para centímetros ou gramas para quilogramas), o que alterou a ordem de grandeza do resultado.`;
                            mistakeType = 'unit_conversion';
                        }
                    }
                }
            }

            // 3. Fallback inteligente para questões conceituais, humanas, biologia e ENEM
            if (!mistakeReason) {
                const isExamItem = explanation.includes('Gabarito Oficial:') || explanation.includes('Resolução Pedagógica:') || explanation.includes('distrator');
                if (isExamItem) {
                    mistakeReason = `A alternativa <strong>(${chosenLetter.toUpperCase()})</strong> é um distrator comum: ela aborda um elemento secundário ou uma interpretação parcial do texto-base, enquanto o comando da questão requer com precisão o que está sintetizado na alternativa correta <strong>(${correctLetter.toUpperCase()})</strong>.`;
                } else {
                    mistakeReason = `Ao optar pela alternativa <strong>(${chosenLetter.toUpperCase()})</strong>, você considerou uma afirmação que não atende às condições estabelecidas no problema. A alternativa correta <strong>(${correctLetter.toUpperCase()})</strong> é a única que satisfaz todos os critérios.`;
                }
            }

            return {
                chosenLetter: chosenLetter.toUpperCase(),
                chosenText: chosenText,
                correctLetter: correctLetter.toUpperCase(),
                correctText: correctText,
                reason: mistakeReason,
                type: mistakeType
            };
        }

        function formatExplanationDetailed(rawText, isSuccess, correctLetter, questionObj, chosenLetter) {
            if (!rawText) return '<span class="text-muted">Resolução não disponível para esta questão.</span>';

            // 1. Limpeza de quebras e resíduos
            let text = rawText
                .replace(/\\n/g, '\n')
                .replace(/\\\\n/g, '\n')
                .replace(/\r/g, '')
                .replace(/!\[.*?\]\(.*?\)/gi, '')
                .replace(/https?:\/\/[^\s\)\"]+/gi, '')
                .replace(/www\.[^\s\)\"]+/gi, '')
                .trim();

            // 2. Padronização de operadores matemáticos para símbolos amigáveis
            text = text
                .replace(/=>/g, ' ➔ ')
                .replace(/->/g, ' ➔ ')
                .replace(/\s\*\s/g, ' × ')
                .replace(/(\d+)\s*\*\s*(\d+)/g, '$1 × $2')
                .replace(/(\d+)\s*\/\s*(\d+)/g, '$1 ÷ $2')
                .replace(/sqrt\((.*?)\)/gi, '√($1)')
                .replace(/\bdelta\b/gi, 'Δ')
                .replace(/\bpi\b/gi, 'π');

            const pillClass = isSuccess ? 'success' : 'error';
            const pillIcon = isSuccess ? 'bi-check-circle-fill' : 'bi-lightbulb-fill';
            const pillText = isSuccess ? 'Resolução Passo a Passo' : `Como Chegar ao Resultado (Gabarito: ${correctLetter.toUpperCase()})`;

            let html = `
                <div class="exp-header-pill ${pillClass}">
                    <i class="bi ${pillIcon}"></i> ${pillText}
                </div>
            `;

            html += `<div class="exp-step-list">`;

            // SE FOI ERRO: Diagnóstico específico de onde o aluno errou com base na resposta dele
            if (!isSuccess && questionObj && chosenLetter) {
                const diag = diagnoseUserMistake(questionObj, chosenLetter, correctLetter);
                if (diag) {
                    html += `
                        <div class="exp-step-card exp-step-mistake">
                            <div class="exp-step-card-header">
                                <span class="exp-step-badge">
                                    <i class="bi bi-search"></i> Diagnóstico: Onde você errou
                                </span>
                                <span class="exp-step-badge-sub text-danger">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Sua escolha: Opção ${diag.chosenLetter}
                                </span>
                            </div>
                            <div class="exp-step-content">
                                <div class="exp-mistake-comparison">
                                    <span class="exp-mistake-tag user-pick">
                                        <i class="bi bi-x-circle-fill"></i> Você marcou: <strong>(${diag.chosenLetter}) ${diag.chosenText}</strong>
                                    </span>
                                    <span class="exp-mistake-tag correct-pick">
                                        <i class="bi bi-check-circle-fill"></i> Gabarito correto: <strong>(${diag.correctLetter}) ${diag.correctText}</strong>
                                    </span>
                                </div>
                                <div class="exp-mistake-explanation mt-2">
                                    ${diag.reason}
                                </div>
                            </div>
                        </div>
                    `;
                }
            }

            // 3. Estruturação especial para itens do ENEM (com Gabarito Oficial e Resolução Pedagógica)
            if (text.includes('Gabarito Oficial:') && text.includes('Resolução Pedagógica:')) {
                const parts = text.split('Resolução Pedagógica:');
                const gabHeader = parts[0].replace('Gabarito Oficial:', '').trim();
                const resBody = parts[1] ? parts[1].trim() : '';

                html += `
                    <div class="exp-step-card exp-step-official">
                        <div class="exp-step-card-header">
                            <span class="exp-step-badge"><i class="bi bi-award-fill"></i> Gabarito Oficial</span>
                        </div>
                        <div class="exp-step-content">${gabHeader}</div>
                    </div>
                `;

                const subSentences = resBody
                    .split(/(?<=[0-9a-zA-Z\.\)])\.\s+(?=[A-Z0-9А-Я]|Logo|Portanto|As demais)/)
                    .map(s => s.trim())
                    .filter(Boolean);

                let sNum = 1;
                subSentences.forEach((sentence, sIdx) => {
                    const isLast = (sIdx === subSentences.length - 1 && subSentences.length > 1);
                    const isConc = isLast || /(portanto|logo|conclusão|as demais opções)/i.test(sentence);
                    const cardClass = isConc ? 'exp-step-conclusion' : 'exp-step-concept';
                    const badgeIcon = isConc ? '🎯' : '💡';
                    const badgeTitle = isConc ? 'Conclusão & Gabarito' : `Etapa ${sNum}: Análise Pedagógica`;
                    if (!isConc) sNum++;

                    const cleanSentence = sentence.endsWith('.') ? sentence : sentence + '.';
                    html += `
                        <div class="exp-step-card ${cardClass}">
                            <div class="exp-step-card-header">
                                <span class="exp-step-badge">${badgeIcon} ${badgeTitle}</span>
                            </div>
                            <div class="exp-step-content">${cleanSentence}</div>
                        </div>
                    `;
                });

                html += `</div>`;
                return html;
            }

            // 4. Estruturação modular com quebra inteligente de etapas
            let lines = [];
            const rawLines = text.split('\n').map(l => l.trim()).filter(Boolean);
            for (const rl of rawLines) {
                const sub = rl.split(/(?<=[0-9a-zA-Z\.\)])\.\s+(?=[A-Z0-9А-Я]|Logo|Portanto|Subtraindo|Assim|Aplicando|Substituindo|Multiplicando|Temos que)/);
                for (const s of sub) {
                    const clean = s.trim();
                    if (clean) lines.push(clean.endsWith('.') ? clean : clean + '.');
                }
            }

            let stepNum = 1;
            lines.forEach((line, idx) => {
                const isLast = (idx === lines.length - 1 && lines.length > 1);
                const isConclusion = isLast || /(portanto|logo|ou seja|resultado|resposta|conclusão|ganho líquido|valor final)/i.test(line);

                if (isConclusion) {
                    html += `
                        <div class="exp-step-card exp-step-conclusion">
                            <div class="exp-step-card-header">
                                <span class="exp-step-badge">🎯 Conclusão & Resultado Final</span>
                            </div>
                            <div class="exp-step-content">${line}</div>
                        </div>
                    `;
                } else {
                    const hasMath = /([=+\/×÷%><➔]|\d+\s*-\s*\d+)/.test(line);
                    const cardClass = hasMath ? 'exp-step-calc' : 'exp-step-concept';
                    const icon = hasMath ? '🧮' : '💡';
                    const typeTitle = hasMath ? 'Cálculo & Desenvolvimento' : 'Fundamentação Teórica';

                    html += `
                        <div class="exp-step-card ${cardClass}">
                            <div class="exp-step-card-header">
                                <span class="exp-step-badge">${icon} Etapa ${stepNum}: ${typeTitle}</span>
                            </div>
                            <div class="exp-step-content">${line}</div>
                        </div>
                    `;
                    stepNum++;
                }
            });
            html += `</div>`;

            return html;
        }

        if (isCorrect) {
            if (typeof sounds !== 'undefined') sounds.playCorrect();
            correctAnswersCount++;
            feedbackDrawer.className = 'feedback-drawer show success';
            feedbackTitle.innerHTML = `<div class="d-flex align-items-center gap-2"><i class="bi bi-check-circle-fill fs-2 text-success"></i><span class="text-success fw-bold font-outfit fs-4">Excelente! Resposta Correta!</span></div>`;

            if (hideResolution) {
                explanationBox.innerHTML = `<em class="text-muted"><i class="bi bi-shield-lock-fill text-danger me-1"></i> Desafio Boss: A resolução detalhada fica oculta para manter o desafio!</em>`;
            } else {
                explanationBox.innerHTML = formatExplanationDetailed(currentQ.explanation_text, true, currentQ.correct_option, currentQ, selectedOption);
            }
        } else {
            if (typeof sounds !== 'undefined') sounds.playError();
            feedbackDrawer.className = 'feedback-drawer show error';
            feedbackTitle.innerHTML = `<div class="d-flex align-items-center gap-2"><i class="bi bi-x-circle-fill fs-2 text-danger"></i><span class="text-danger fw-bold font-outfit fs-4">Resposta Incorreta (Gabarito: Opção ${currentQ.correct_option.toUpperCase()})</span></div>`;

            if (hideResolution) {
                explanationBox.innerHTML = `<em class="text-muted"><i class="bi bi-shield-lock-fill text-danger me-1"></i> Desafio Boss: A resolução detalhada fica oculta para manter o desafio!</em>`;
            } else {
                explanationBox.innerHTML = formatExplanationDetailed(currentQ.explanation_text, false, currentQ.correct_option, currentQ, selectedOption);
            }
        }
    }

    if (btnCheck) btnCheck.addEventListener('click', checkAnswer);

    if (btnContinue) {
        btnContinue.addEventListener('click', () => {
            if (feedbackDrawer) {
                feedbackDrawer.classList.remove('show', 'success', 'error');
            }
            currentIndex++;
            loadQuestion(currentIndex);
        });
    }

    // ==========================================================================
    // 5. CONCLUSÃO DA LIÇÃO COM RECOMPENSAS & LEVEL UP
    // ==========================================================================
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
            const body = document.getElementById('quizScreen');
            const passed = (res.passed !== undefined) ? res.passed : (scorePercent >= 60);
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
                        <h4 class="fw-bold text-dark mb-0 font-outfit">SUBIU DE NÍVEL!</h4>
                        <span class="badge bg-warning text-dark font-monospace mt-1 px-3 py-1 fs-6">NÍVEL ${res.level} CONQUISTADO</span>
                    </div>
                `;
            }

            if (passed) {
                // TELA DE VITÓRIA / APROVADO (>= 60%)
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
                        <h3 class="fw-bold font-outfit text-dark mb-1">${isBossChallenge ? 'Desafio Boss Superado!' : 'Fase Concluída com Sucesso!'}</h3>
                        <p class="text-secondary small mb-4">Parabéns! Você alcançou a nota de corte mínima de 60% e dominou esta etapa!</p>

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
                                <span class="fw-bold font-outfit text-dark">Ofensiva Diária</span>
                            </div>
                            <span class="badge bg-warning text-dark font-monospace px-3 py-1.5 fs-6">${res.streak_days} DIAS</span>
                        </div>

                        <button type="button" onclick="window.location.href='${redirectUrl}'" class="btn btn-aprova-primary py-3 fw-bold w-100 fs-6 shadow">
                            VOLTAR À TRILHA DE ESTUDOS
                        </button>
                    </div>
                `;
            } else {
                // TELA DE DERROTA / REPROVADO (< 60%)
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
                        <h3 class="fw-bold font-outfit text-dark mb-1">Não foi desta vez!</h3>
                        <p class="text-secondary small mb-4">Você teve <strong>${scorePercent}%</strong> de acertos. É necessário acertar no mínimo <strong>60%</strong> das questões para ser aprovado nesta atividade.</p>

                        <div class="p-3 bg-rose-50 border border-rose-200 rounded-3 mb-4 text-start">
                            <div class="d-flex align-items-center gap-2 text-rose-700 fw-bold small mb-1 font-outfit">
                                <i class="bi bi-lightbulb-fill fs-5"></i> Dica de Estudo:
                            </div>
                            <p class="text-rose-900 small mb-0">Revise os conceitos no Guia Teórico antes de tentar novamente!</p>
                        </div>

                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <button type="button" onclick="window.location.reload()" class="btn btn-aprova-primary py-3 fw-bold flex-fill fs-6 shadow">
                                <i class="bi bi-arrow-clockwise me-1"></i> Tentar Novamente
                            </button>
                            <button type="button" onclick="showTheoryScreen()" class="btn btn-light border py-3 fw-bold flex-fill fs-6 text-secondary">
                                <i class="bi bi-book-half me-1"></i> Rever Teoria
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
