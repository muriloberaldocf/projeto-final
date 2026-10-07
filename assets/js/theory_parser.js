/**
 * HIPOGABARITO — PARSER DE TEORIA EDUCACIONAL
 * Converte resumos em Markdown para Cards Modulares de Alto Destaque 3D.
 */

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
