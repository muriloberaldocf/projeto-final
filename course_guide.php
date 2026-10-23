<?php
/**
 * GUIA DE CURSOS & NOTAS DE CORTE DO SISU — HIPOGABARITO
 * Consulta oficial de 1.350+ cursos cadastrados no banco de dados + Pesquisa Direta
 */
require_once __DIR__ . '/config/db.php';
checkAuth();

$pageTitle = 'Guia de Cursos & Notas de Corte — HipoGabarito';
require_once __DIR__ . '/includes/header.php';
?>

    <style>
    @media print {
        header, aside, .no-print, #sectionDatabase, #sectionGoogle, nav {
            display: none !important;
        }
        body {
            background: #ffffff !important;
            color: #0f172a !important;
        }
        main {
            max-width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .grid {
            display: block !important;
        }
        .lg\:col-span-9 {
            width: 100% !important;
        }
        #sectionVestibulares {
            display: block !important;
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
        }
        .exam-report-block {
            page-break-inside: avoid;
        }
    }
    </style>

    <!-- LAYOUT PRINCIPAL DA GUIA DE CURSOS -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-all">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- SIDEBAR MODULAR COM DESTAQUE EM ROXO -->
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

            <!-- CONTEÚDO PRINCIPAL: GUIA DE CURSOS -->
            <section class="lg:col-span-9 space-y-6">

                <!-- BANNER DE DESTAQUE: GUIA DE CONCORRÊNCIA E ACERTOS -->
                <div class="bg-gradient-to-r from-indigo-700 to-purple-700 rounded-3xl p-6 sm:p-7 text-white shadow-[0_4px_0_0_#312e81] flex flex-col sm:flex-row items-center justify-between gap-5">
                    <div class="space-y-1 text-center sm:text-left">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black bg-white/20 text-amber-200 uppercase tracking-wider">
                            <i class="bi bi-speedometer2"></i> TERMÔMETRO DE ACERTOS
                        </span>
                        <h2 class="text-xl sm:text-2xl font-black font-outfit text-white">
                            Descubra a faixa de acertos necessária para o seu curso!
                        </h2>
                        <p class="text-xs sm:text-sm text-indigo-100 font-medium max-w-xl mb-0">
                            A nota do ENEM varia via TRI. Use nosso Guia de Concorrência para saber quantas questões você precisa acertar nos simulados.
                        </p>
                    </div>

                    <a href="guia_concorrencia.php" class="shrink-0 px-6 py-3.5 rounded-2xl font-outfit font-black text-sm bg-amber-400 text-slate-900 shadow-[0_4px_0_0_#b45309] hover:bg-amber-300 active:translate-y-0.5 transition-all flex items-center gap-2">
                        <i class="bi bi-speedometer2 text-base"></i>
                        <span>GUIA DE ACERTOS</span>
                    </a>
                </div>

                <!-- TABS DE NAVEGAÇÃO -->
                <div class="flex flex-wrap items-center gap-2 p-1.5 bg-slate-200/80 rounded-2xl w-fit">
                    <button type="button" onclick="switchGuideTab('database', this)" id="tabBtnDatabase" class="px-4 py-2 rounded-xl font-outfit font-black text-xs bg-white text-indigo-900 shadow-sm transition-all flex items-center gap-2">
                        <i class="bi bi-table text-indigo-600"></i>
                        <span>Notas de Corte SiSU (+1.350 Cursos)</span>
                    </button>
                    <a href="provas_oficiais.php" class="px-4 py-2 rounded-xl font-outfit font-black text-xs bg-rose-50 hover:bg-rose-100 text-rose-800 transition-all flex items-center gap-2 border border-rose-200 shadow-2xs">
                        <i class="bi bi-file-earmark-pdf-fill text-rose-600"></i>
                        <span>Baixar Provas & Gabaritos (USP, UNESP, etc.)</span>
                        <span class="px-1.5 py-0.5 rounded text-[9px] font-black uppercase bg-rose-200 text-rose-900">PDFs</span>
                    </a>
                    <button type="button" onclick="switchGuideTab('vestibulares', this)" id="tabBtnVestibulares" class="px-4 py-2 rounded-xl font-outfit font-black text-xs text-slate-600 hover:text-slate-900 transition-all flex items-center gap-2">
                        <i class="bi bi-journal-bookmark-fill text-slate-400"></i>
                        <span>Manual das Bancas</span>
                    </button>
                    <button type="button" onclick="switchGuideTab('google', this)" id="tabBtnGoogle" class="px-4 py-2 rounded-xl font-outfit font-black text-xs text-slate-600 hover:text-slate-900 transition-all flex items-center gap-2">
                        <i class="bi bi-google text-slate-400"></i>
                        <span>Pesquisa Externa & Grade Curricular</span>
                    </button>
                </div>

                <!-- SEÇÃO 1: TABELA OFICIAL DE NOTAS DE CORTE DO SISU -->
                <div id="sectionDatabase" class="bg-white rounded-3xl border-2 border-slate-200 p-6 sm:p-8 shadow-[0_4px_0_0_#e2e8f0] space-y-6">
                    <div>
                        <h3 class="font-outfit font-extrabold text-xl text-slate-900 mb-1 flex items-center gap-2">
                            <i class="bi bi-mortarboard-fill text-indigo-600"></i>
                            Painel de Notas de Corte das Universidades Brasileiras
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-500 font-medium mb-0">
                            Explore as notas de corte de Ampla Concorrência e Cotas das maiores universidades públicas federais e estaduais do país.
                        </p>
                    </div>

                    <!-- BARRA DE PESQUISA & FILTROS -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                        <div class="sm:col-span-6 relative">
                            <i class="bi bi-search absolute left-3.5 top-3 text-slate-400"></i>
                            <input type="text" id="dbSearchInput" oninput="filterDatabaseTable()" placeholder="Buscar curso, universidade ou cidade (Ex: Medicina, USP, Direito UFMG...)" class="w-full pl-10 pr-4 py-2.5 rounded-xl border-2 border-slate-200 focus:border-indigo-600 text-xs font-bold text-slate-800 focus:outline-none shadow-inner">
                        </div>

                        <div class="sm:col-span-3">
                            <select id="dbRegionFilter" onchange="filterDatabaseTable()" class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 focus:border-indigo-600 text-xs font-bold text-slate-800 focus:outline-none cursor-pointer">
                                <option value="">Todas as Regiões</option>
                                <option value="Sudeste">Sudeste</option>
                                <option value="Sul">Sul</option>
                                <option value="Nordeste">Nordeste</option>
                                <option value="Centro-Oeste">Centro-Oeste</option>
                                <option value="Norte">Norte</option>
                            </select>
                        </div>

                        <div class="sm:col-span-3">
                            <select id="dbShiftFilter" onchange="filterDatabaseTable()" class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 focus:border-indigo-600 text-xs font-bold text-slate-800 focus:outline-none cursor-pointer">
                                <option value="">Todos os Turnos</option>
                                <option value="Integral">Integral</option>
                                <option value="Matutino">Matutino</option>
                                <option value="Noturno">Noturno</option>
                                <option value="Vespertino">Vespertino</option>
                            </select>
                        </div>
                    </div>

                    <!-- TABELA -->
                    <div class="overflow-x-auto rounded-2xl border-2 border-slate-200 shadow-xs">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead class="bg-slate-100 text-slate-700 font-outfit font-black uppercase text-[11px] border-b-2 border-slate-200">
                                <tr>
                                    <th class="py-3 px-4">Curso & Instituição</th>
                                    <th class="py-3 px-4">Cidade / Região</th>
                                    <th class="py-3 px-4 text-center">Turno / Grau</th>
                                    <th class="py-3 px-4 text-right">Corte Ampla</th>
                                    <th class="py-3 px-4 text-right">Corte Cotas</th>
                                    <th class="py-3 px-4 text-center">Simular</th>
                                </tr>
                            </thead>
                            <tbody id="dbTableBody" class="divide-y divide-slate-100 bg-white font-medium text-slate-700">
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-400">
                                        <div class="inline-block animate-spin w-6 h-6 border-2 border-indigo-600 border-t-transparent rounded-full mb-2"></div>
                                        <div class="font-outfit font-bold text-xs">Carregando catálogo de cursos...</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINAÇÃO -->
                    <div class="flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 font-medium pt-2 gap-2">
                        <span id="dbResultsCount">Carregando dados...</span>
                        <div class="flex items-center gap-1.5">
                            <button type="button" id="dbBtnPrev" onclick="changeDbPage(-1)" class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed font-bold text-slate-700">Anterior</button>
                            <span id="dbPageIndicator" class="px-2 font-bold text-slate-700">Pág. 1</span>
                            <button type="button" id="dbBtnNext" onclick="changeDbPage(1)" class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed font-bold text-slate-700">Próxima</button>
                        </div>
                    </div>
                </div>

                <!-- SEÇÃO 2: RAIO-X DOS GRANDES VESTIBULARES (USP, UNESP, UNICAMP, UERJ, ENEM) -->
                <div id="sectionVestibulares" class="bg-white rounded-3xl border-2 border-slate-200 p-6 sm:p-8 shadow-[0_4px_0_0_#e2e8f0] hidden space-y-6">
                    
                    <!-- CABEÇALHO DO RELATÓRIO -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                        <div>
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black bg-indigo-50 text-indigo-700 uppercase tracking-wider mb-2 border border-indigo-200">
                                <i class="bi bi-journal-check"></i> DOSSIÊ OFICIAL DAS BANCAS
                            </div>
                            <h3 class="font-outfit font-extrabold text-xl sm:text-2xl text-slate-900 mb-1 flex items-center gap-2">
                                <i class="bi bi-journal-bookmark-fill text-indigo-600"></i>
                                Raio-X dos Principais Vestibulares do Brasil
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-500 font-medium mb-0">
                                Estrutura de fases, perfil das bancas examinadoras, exigências das redações, leituras obrigatórias e temas mais recorrentes.
                            </p>
                        </div>

                        <div class="flex items-center gap-2 shrink-0 no-print">
                            <button type="button" onclick="window.print()" class="px-4 py-2.5 rounded-2xl font-outfit font-black text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 transition-all flex items-center gap-2 shadow-2xs">
                                <i class="bi bi-printer-fill text-indigo-600"></i>
                                <span>Imprimir / Salvar PDF</span>
                            </button>
                        </div>
                    </div>

                    <!-- NAVEGADOR DE BANCAS (SUB-TABS) -->
                    <div class="flex flex-wrap items-center gap-2 no-print" id="examNavButtons">
                        <button type="button" onclick="showExamReport('fuvest', this)" class="exam-btn active px-4 py-2.5 rounded-2xl font-outfit font-black text-xs bg-indigo-600 text-white shadow-[0_3px_0_0_#312e81] flex items-center gap-2 transition-all">
                            <span>🏛️ FUVEST (USP)</span>
                        </button>
                        <button type="button" onclick="showExamReport('unicamp', this)" class="exam-btn px-4 py-2.5 rounded-2xl font-outfit font-black text-xs bg-slate-100 text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 border border-slate-200 flex items-center gap-2 transition-all">
                            <span>🔬 UNICAMP</span>
                        </button>
                        <button type="button" onclick="showExamReport('unesp', this)" class="exam-btn px-4 py-2.5 rounded-2xl font-outfit font-black text-xs bg-slate-100 text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 border border-slate-200 flex items-center gap-2 transition-all">
                            <span>📚 UNESP (Vunesp)</span>
                        </button>
                        <button type="button" onclick="showExamReport('uerj', this)" class="exam-btn px-4 py-2.5 rounded-2xl font-outfit font-black text-xs bg-slate-100 text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 border border-slate-200 flex items-center gap-2 transition-all">
                            <span>⚓ UERJ</span>
                        </button>
                        <button type="button" onclick="showExamReport('enem', this)" class="exam-btn px-4 py-2.5 rounded-2xl font-outfit font-black text-xs bg-slate-100 text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 border border-slate-200 flex items-center gap-2 transition-all">
                            <span>🇧🇷 ENEM (INEP)</span>
                        </button>
                        <button type="button" onclick="showExamReport('comparativo', this)" class="exam-btn px-4 py-2.5 rounded-2xl font-outfit font-black text-xs bg-slate-100 text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 border border-slate-200 flex items-center gap-2 transition-all">
                            <span>⚖️ Quadro Comparativo</span>
                        </button>
                    </div>

                    <!-- BLOCO 1: FUVEST (USP) -->
                    <div id="report_fuvest" class="exam-report-block space-y-6">
                        
                        <!-- BANNER DA BANCA -->
                        <div class="p-6 rounded-3xl bg-gradient-to-r from-amber-500/10 via-indigo-50 to-purple-50 border-2 border-amber-200/80 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-2xl">🏛️</span>
                                    <h4 class="font-outfit font-black text-xl text-slate-900 mb-0">FUVEST — Universidade de São Paulo (USP)</h4>
                                </div>
                                <p class="text-xs text-slate-600 font-medium max-w-2xl leading-relaxed mb-0">
                                    O vestibular mais tradicional do país. Notabiliza-se pelo <strong>alto rigor formal e conceitual</strong>, enunciados densos e objetivos, forte valorização da teoria e redação filosófica reflexiva.
                                </p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="px-3 py-1.5 rounded-xl font-outfit font-extrabold text-xs bg-amber-100 text-amber-900 border border-amber-300">
                                    ⭐ IGC Nota 5 (MEC)
                                </span>
                                <span class="px-3 py-1.5 rounded-xl font-outfit font-extrabold text-xs bg-indigo-100 text-indigo-900 border border-indigo-200">
                                    ~8.200 Vagas
                                </span>
                            </div>
                        </div>

                        <!-- CARDS DE ESTRUTURA -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            
                            <!-- CARD 1: ESTRUTURA DE FASES -->
                            <div class="p-5 rounded-2xl border-2 border-slate-200 bg-slate-50/50 space-y-3">
                                <div class="flex items-center gap-2 text-indigo-700 font-outfit font-extrabold text-sm">
                                    <i class="bi bi-calendar3"></i> 1ª & 2ª Fase
                                </div>
                                <ul class="text-xs text-slate-600 space-y-2 font-medium">
                                    <li><strong>1ª Fase:</strong> 90 questões de múltipla escolha (A a E) em 5h00. Cobra todo o currículo do EM + obras literárias + interdisciplinares.</li>
                                    <li><strong>Corte 1ª Fase:</strong> Convoca 4 candidatos por vaga para a 2ª fase (Medicina costuma cortar entre 79 e 82 acertos).</li>
                                    <li><strong>2ª Fase (2 dias):</strong>
                                        <div class="text-[11px] text-slate-500 mt-1 pl-2 border-l-2 border-indigo-200">
                                            • <strong>Dia 1:</strong> 10 questões discursivas de Português/Literatura + Redação.<br>
                                            • <strong>Dia 2:</strong> 12 questões discursivas das matérias específicas da carreira.
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <!-- CARD 2: RAIO-X DA REDAÇÃO -->
                            <div class="p-5 rounded-2xl border-2 border-purple-200 bg-purple-50/30 space-y-3">
                                <div class="flex items-center gap-2 text-purple-700 font-outfit font-extrabold text-sm">
                                    <i class="bi bi-pen-fill"></i> Redação Fuvest
                                </div>
                                <ul class="text-xs text-slate-600 space-y-2 font-medium">
                                    <li><strong>Gênero:</strong> Dissertativo-argumentativo formal de natureza reflexiva/filosófica.</li>
                                    <li><strong>Diferencial Crítico:</strong> <span class="text-rose-600 font-bold">NÃO exige proposta de intervenção social!</span> O foco é a reflexão profunda e a defesa de uma tese consistente, sem clichês.</li>
                                    <li><strong>O que a banca premia:</strong> Autonomia de pensamento, capacidade crítica, uso legítimo de repertório filosófico e coesão textual apurada.</li>
                                    <li><strong>Peso:</strong> Vale 50 pontos (50% da nota do Dia 1 da 2ª fase).</li>
                                </ul>
                            </div>

                            <!-- CARD 3: PERFIL DA BANCA & DICAS -->
                            <div class="p-5 rounded-2xl border-2 border-amber-200 bg-amber-50/30 space-y-3">
                                <div class="flex items-center gap-2 text-amber-800 font-outfit font-extrabold text-sm">
                                    <i class="bi bi-shield-check"></i> Segredos da FUVEST
                                </div>
                                <ul class="text-xs text-slate-600 space-y-2 font-medium">
                                    <li><strong>Sem pegadinhas tolas:</strong> A dificuldade reside na profundidade conceitual e no rigor formal, e não em ambiguidades de enunciado.</li>
                                    <li><strong>Na 2ª fase:</strong> O corretor valoriza a resposta direta, etapas claras do cálculo e justificativa teórica precisa. Não "enrole" na folha de respostas.</li>
                                    <li><strong>Interdisciplinaridade real:</strong> Questões que exigem correlacionar física com química ou história com literatura contemporânea.</li>
                                </ul>
                            </div>

                        </div>

                        <!-- OBRAS LITERÁRIAS OBRIGATÓRIAS FUVEST -->
                        <div class="p-5 rounded-2xl border-2 border-indigo-100 bg-indigo-50/20 space-y-3">
                            <h5 class="font-outfit font-black text-sm text-indigo-950 flex items-center gap-2">
                                <i class="bi bi-book-half text-indigo-600"></i>
                                Obras Literárias Obrigatórias — Fuvest (Ciclo Oficial)
                            </h5>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5 text-xs">
                                <div class="p-3 bg-white rounded-xl border border-slate-200 shadow-2xs">
                                    <strong class="font-outfit text-slate-900 block font-bold">Alguma Poesia</strong>
                                    <span class="text-[11px] text-indigo-600 block">Carlos Drummond de Andrade</span>
                                    <span class="text-[10px] text-slate-400">Modernismo (Fase 1) • Poema de sete-faces</span>
                                </div>
                                <div class="p-3 bg-white rounded-xl border border-slate-200 shadow-2xs">
                                    <strong class="font-outfit text-slate-900 block font-bold">Mensagem</strong>
                                    <span class="text-[11px] text-indigo-600 block">Fernando Pessoa</span>
                                    <span class="text-[10px] text-slate-400">Modernismo Português • Epopeia e sebastianismo</span>
                                </div>
                                <div class="p-3 bg-white rounded-xl border border-slate-200 shadow-2xs">
                                    <strong class="font-outfit text-slate-900 block font-bold">Os Ratos</strong>
                                    <span class="text-[11px] text-indigo-600 block">Dyonélio Machado</span>
                                    <span class="text-[10px] text-slate-400">Romance urbano • Angústia existencial e dinheiro</span>
                                </div>
                                <div class="p-3 bg-white rounded-xl border border-slate-200 shadow-2xs">
                                    <strong class="font-outfit text-slate-900 block font-bold">Romanceiro da Inconfidência</strong>
                                    <span class="text-[11px] text-indigo-600 block">Cecília Meireles</span>
                                    <span class="text-[10px] text-slate-400">Lirismo épico-histórico • Liberdade e Tiradentes</span>
                                </div>
                                <div class="p-3 bg-white rounded-xl border border-slate-200 shadow-2xs">
                                    <strong class="font-outfit text-slate-900 block font-bold">Campo Geral</strong>
                                    <span class="text-[11px] text-indigo-600 block">Guimarães Rosa</span>
                                    <span class="text-[10px] text-slate-400">Sertão e infância • O despertar de Miguilim</span>
                                </div>
                                <div class="p-3 bg-white rounded-xl border border-slate-200 shadow-2xs">
                                    <strong class="font-outfit text-slate-900 block font-bold">Angústia</strong>
                                    <span class="text-[11px] text-indigo-600 block">Graciliano Ramos</span>
                                    <span class="text-[10px] text-slate-400">Monólogo interior • Degradação social e crime</span>
                                </div>
                                <div class="p-3 bg-white rounded-xl border border-slate-200 shadow-2xs">
                                    <strong class="font-outfit text-slate-900 block font-bold">Dois Irmãos</strong>
                                    <span class="text-[11px] text-indigo-600 block">Milton Hatoum</span>
                                    <span class="text-[10px] text-slate-400">Manaus contemporânea • Imigração e rivalidade</span>
                                </div>
                                <div class="p-3 bg-white rounded-xl border border-slate-200 shadow-2xs">
                                    <strong class="font-outfit text-slate-900 block font-bold">As Meninas</strong>
                                    <span class="text-[11px] text-indigo-600 block">Lygia Fagundes Telles</span>
                                    <span class="text-[10px] text-slate-400">Ditadura militar • Três jovens e seus destinos</span>
                                </div>
                                <div class="p-3 bg-white rounded-xl border border-slate-200 shadow-2xs">
                                    <strong class="font-outfit text-slate-900 block font-bold">Cânticos</strong>
                                    <span class="text-[11px] text-indigo-600 block">Cecília Meireles</span>
                                    <span class="text-[10px] text-slate-400">Poesia lírica e mística • Espiritualidade e tempo</span>
                                </div>
                            </div>
                        </div>

                        <!-- TOP RECORRÊNCIA POR DISCIPLINA -->
                        <div class="p-5 rounded-2xl border-2 border-slate-200 bg-white space-y-3">
                            <h5 class="font-outfit font-black text-sm text-slate-900 flex items-center gap-2">
                                <i class="bi bi-bar-chart-fill text-indigo-600"></i>
                                Assuntos de Maior Recorrência Histórica na FUVEST
                            </h5>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 text-xs">
                                <div class="space-y-1">
                                    <span class="font-bold text-indigo-700 block">📐 Matemática</span>
                                    <p class="text-slate-600 text-[11px] mb-0">Geometria Plana e Espacial, Funções (Exponencial/Logarítmica), Trigonometria, Probabilidade e Análise Combinatória.</p>
                                </div>
                                <div class="space-y-1">
                                    <span class="font-bold text-teal-700 block">⚡ Física</span>
                                    <p class="text-slate-600 text-[11px] mb-0">Eletrodinâmica (Circuitos, Resistores, Potência), Mecânica (Trabalho, Energia e Leis de Newton), Termologia e Óptica.</p>
                                </div>
                                <div class="space-y-1">
                                    <span class="font-bold text-emerald-700 block">🧪 Química</span>
                                    <p class="text-slate-600 text-[11px] mb-0">Equilíbrio Químico e pH, Reações Orgânicas e Isomeria, Estequiometria com Rendimento/Pureza e Termoquímica.</p>
                                </div>
                                <div class="space-y-1">
                                    <span class="font-bold text-amber-700 block">🧬 Biologia</span>
                                    <p class="text-slate-600 text-[11px] mb-0">Genética Molecular e Biotecnologia (CRISPR/Transgênicos), Ecologia (Cadeias e Impactos), Fisiologia Humana e Botânica.</p>
                                </div>
                                <div class="space-y-1">
                                    <span class="font-bold text-rose-700 block">📜 História</span>
                                    <p class="text-slate-600 text-[11px] mb-0">Brasil República (Era Vargas e Ditadura Civil-Militar), Brasil Colônia/Império, Idade Moderna e Revoluções Burguesas.</p>
                                </div>
                                <div class="space-y-1">
                                    <span class="font-bold text-sky-700 block">🌍 Geografia</span>
                                    <p class="text-slate-600 text-[11px] mb-0">Urbanização e Questão Agrária no Brasil, Geopolítica Contemporânea, Climatologia e Dinâmica de Bacias Hidrográficas.</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- BLOCO 2: UNICAMP (COMVEST) -->
                    <div id="report_unicamp" class="exam-report-block hidden space-y-6">
                        
                        <!-- BANNER DA BANCA -->
                        <div class="p-6 rounded-3xl bg-gradient-to-r from-red-500/10 via-rose-50 to-orange-50 border-2 border-red-200/80 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-2xl">🔬</span>
                                    <h4 class="font-outfit font-black text-xl text-slate-900 mb-0">COMVEST — Universidade Estadual de Campinas (UNICAMP)</h4>
                                </div>
                                <p class="text-xs text-slate-600 font-medium max-w-2xl leading-relaxed mb-0">
                                    Pioneira e inovadora. Famosa pelo seu <strong>formato único de redação com gêneros discursivos variados</strong>, 1ª fase com 4 alternativas e forte apego a debates sociais modernos e leitura de dados.
                                </p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="px-3 py-1.5 rounded-xl font-outfit font-extrabold text-xs bg-amber-100 text-amber-900 border border-amber-300">
                                    ⭐ IGC Nota 5 (MEC)
                                </span>
                                <span class="px-3 py-1.5 rounded-xl font-outfit font-extrabold text-xs bg-rose-100 text-rose-900 border border-rose-200">
                                    ~3.340 Vagas
                                </span>
                            </div>
                        </div>

                        <!-- CARDS DE ESTRUTURA -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            
                            <!-- CARD 1: ESTRUTURA DE FASES -->
                            <div class="p-5 rounded-2xl border-2 border-slate-200 bg-slate-50/50 space-y-3">
                                <div class="flex items-center gap-2 text-rose-700 font-outfit font-extrabold text-sm">
                                    <i class="bi bi-calendar3"></i> 1ª & 2ª Fase
                                </div>
                                <ul class="text-xs text-slate-600 space-y-2 font-medium">
                                    <li><strong>1ª Fase:</strong> 72 questões com <strong>4 alternativas (A, B, C, D)</strong> em 5h00. Muito equilibrada e com textos ricos e charges.</li>
                                    <li><strong>Distribuição 1ª Fase:</strong> 12 Português/Literatura, 12 Matemática, 8 Hist, 8 Geo, 8 Filo/Soc, 8 Fis, 8 Quim, 8 Bio, 8 Inglês.</li>
                                    <li><strong>2ª Fase (2 dias):</strong>
                                        <div class="text-[11px] text-slate-500 mt-1 pl-2 border-l-2 border-rose-200">
                                            • <strong>Dia 1:</strong> Redação (2 propostas de gêneros diferentes; escolhe 1) + 6 questões de Português + 2 interdisciplinares de Inglês.<br>
                                            • <strong>Dia 2:</strong> Provas interdisciplinares comuns + matérias específicas do curso.
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <!-- CARD 2: RAIO-X DA REDAÇÃO -->
                            <div class="p-5 rounded-2xl border-2 border-rose-200 bg-rose-50/30 space-y-3">
                                <div class="flex items-center gap-2 text-rose-800 font-outfit font-extrabold text-sm">
                                    <i class="bi bi-pen-fill"></i> Redação UNICAMP: Gêneros Variados
                                </div>
                                <ul class="text-xs text-slate-600 space-y-2 font-medium">
                                    <li><strong>A Grande Marca:</strong> Apresenta 2 propostas de <strong>gêneros textuais distintos</strong> (ex: carta aberta, crônica, manifesto, resenha crítica, roteiro de podcast, discurso). Você escolhe UMA.</li>
                                    <li><strong>A "Máscara do Gênero":</strong> É obrigatório incorporar quem é você (autor), para quem você escreve (interlocutor) e qual o veículo de publicação indicado.</li>
                                    <li><strong>Critérios:</strong> Cumprimento da tarefa, máscara do gênero, coesão, articulação dos textos motivadores e adequação do registro linguístico.</li>
                                </ul>
                            </div>

                            <!-- CARD 3: PERFIL DA BANCA -->
                            <div class="p-5 rounded-2xl border-2 border-amber-200 bg-amber-50/30 space-y-3">
                                <div class="flex items-center gap-2 text-amber-800 font-outfit font-extrabold text-sm">
                                    <i class="bi bi-lightbulb-fill"></i> O Estilo da COMVEST
                                </div>
                                <ul class="text-xs text-slate-600 space-y-2 font-medium">
                                    <li><strong>Leitura de Dados:</strong> Cobrança pesada de interpretação de gráficos, infográficos e tabelas em praticamente todas as disciplinas.</li>
                                    <li><strong>Atualidades e Sociedade:</strong> Debates sobre direitos humanos, racismo ambiental, avanços tecnológicos e cidadania permeiam toda a prova.</li>
                                    <li><strong>Menos Decoreba:</strong> Questões privilegiam a capacidade de associar teoria científica à vida prática.</li>
                                </ul>
                            </div>

                        </div>

                        <!-- OBRAS LITERÁRIAS OBRIGATÓRIAS UNICAMP -->
                        <div class="p-5 rounded-2xl border-2 border-rose-100 bg-rose-50/20 space-y-3">
                            <h5 class="font-outfit font-black text-sm text-rose-950 flex items-center gap-2">
                                <i class="bi bi-book-half text-rose-600"></i>
                                Obras Literárias Obrigatórias — UNICAMP
                            </h5>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2.5 text-xs">
                                <div class="p-3 bg-white rounded-xl border border-slate-200 shadow-2xs">
                                    <strong class="font-outfit text-slate-900 block font-bold">Prosas Seguidas de Odes Mínimas</strong>
                                    <span class="text-[11px] text-rose-600 block">Hilda Hilst</span>
                                    <span class="text-[10px] text-slate-400">Poesia e prosa • Existencialismo e sagrado/profano</span>
                                </div>
                                <div class="p-3 bg-white rounded-xl border border-slate-200 shadow-2xs">
                                    <strong class="font-outfit text-slate-900 block font-bold">Alice no País das Maravilhas</strong>
                                    <span class="text-[11px] text-rose-600 block">Lewis Carroll</span>
                                    <span class="text-[10px] text-slate-400">Nonsense, lógica e crítica social vitoriana</span>
                                </div>
                                <div class="p-3 bg-white rounded-xl border border-slate-200 shadow-2xs">
                                    <strong class="font-outfit text-slate-900 block font-bold">Canções Escolhidas</strong>
                                    <span class="text-[11px] text-rose-600 block">Cartola</span>
                                    <span class="text-[10px] text-slate-400">Samba e lirismo popular • Amor, memória e morro</span>
                                </div>
                                <div class="p-3 bg-white rounded-xl border border-slate-200 shadow-2xs">
                                    <strong class="font-outfit text-slate-900 block font-bold">Niketche: História de Poligamia</strong>
                                    <span class="text-[11px] text-rose-600 block">Paulina Chiziane</span>
                                    <span class="text-[10px] text-slate-400">Literatura moçambicana • Patriarcado e sororidade</span>
                                </div>
                                <div class="p-3 bg-white rounded-xl border border-slate-200 shadow-2xs">
                                    <strong class="font-outfit text-slate-900 block font-bold">Vida e Morte de M. J. Gonzaga de Sá</strong>
                                    <span class="text-[11px] text-rose-600 block">Lima Barreto</span>
                                    <span class="text-[10px] text-slate-400">Pré-Modernismo • Crítica ao Rio republicano</span>
                                </div>
                                <div class="p-3 bg-white rounded-xl border border-slate-200 shadow-2xs">
                                    <strong class="font-outfit text-slate-900 block font-bold">A Falecida</strong>
                                    <span class="text-[11px] text-rose-600 block">Nelson Rodrigues</span>
                                    <span class="text-[10px] text-slate-400">Dramaturgia moderna • Subúrbio carioca e hipocrisia</span>
                                </div>
                                <div class="p-3 bg-white rounded-xl border border-slate-200 shadow-2xs">
                                    <strong class="font-outfit text-slate-900 block font-bold">Casa de Pensão</strong>
                                    <span class="text-[11px] text-rose-600 block">Aluísio Azevedo</span>
                                    <span class="text-[10px] text-slate-400">Naturalismo brasileiro • Determinismo social</span>
                                </div>
                                <div class="p-3 bg-white rounded-xl border border-slate-200 shadow-2xs">
                                    <strong class="font-outfit text-slate-900 block font-bold">O Ateneu</strong>
                                    <span class="text-[11px] text-rose-600 block">Raul Pompeia</span>
                                    <span class="text-[10px] text-slate-400">Realismo/Impressionismo • Internato e microcosmo social</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- BLOCO 3: UNESP (VUNESP) -->
                    <div id="report_unesp" class="exam-report-block hidden space-y-6">
                        
                        <!-- BANNER DA BANCA -->
                        <div class="p-6 rounded-3xl bg-gradient-to-r from-blue-500/10 via-sky-50 to-indigo-50 border-2 border-blue-200/80 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-2xl">📚</span>
                                    <h4 class="font-outfit font-black text-xl text-slate-900 mb-0">VUNESP — Universidade Estadual Paulista (UNESP)</h4>
                                </div>
                                <p class="text-xs text-slate-600 font-medium max-w-2xl leading-relaxed mb-0">
                                    A banca mais equilibrada e transparente. Notabiliza-se por <strong>enunciados diretos sem ambiguidades</strong>, divisão exata em 3 blocos de 30 questões na 1ª fase e redação formulada com tema-pergunta.
                                </p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="px-3 py-1.5 rounded-xl font-outfit font-extrabold text-xs bg-amber-100 text-amber-900 border border-amber-300">
                                    ⭐ IGC Nota 5 (MEC)
                                </span>
                                <span class="px-3 py-1.5 rounded-xl font-outfit font-extrabold text-xs bg-blue-100 text-blue-900 border border-blue-200">
                                    ~7.690 Vagas
                                </span>
                            </div>
                        </div>

                        <!-- CARDS DE ESTRUTURA -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            
                            <!-- CARD 1: ESTRUTURA DE FASES -->
                            <div class="p-5 rounded-2xl border-2 border-slate-200 bg-slate-50/50 space-y-3">
                                <div class="flex items-center gap-2 text-blue-700 font-outfit font-extrabold text-sm">
                                    <i class="bi bi-calendar3"></i> 1ª & 2ª Fase
                                </div>
                                <ul class="text-xs text-slate-600 space-y-2 font-medium">
                                    <li><strong>1ª Fase:</strong> 90 questões de múltipla escolha (A a E) em 5h00. Estrutura rígida de 3 blocos de 30 questões:
                                        <div class="text-[11px] text-slate-500 mt-1 pl-2 border-l-2 border-blue-200">
                                            • 30 de Linguagens e Códigos<br>
                                            • 30 de Ciências Humanas<br>
                                            • 30 de Ciências da Natureza e Matemática
                                        </div>
                                    </li>
                                    <li><strong>2ª Fase (2 dias):</strong>
                                        <div class="text-[11px] text-slate-500 mt-1 pl-2 border-l-2 border-blue-200">
                                            • <strong>Dia 1:</strong> 24 questões discursivas (Humanas e Natureza/Mat).<br>
                                            • <strong>Dia 2:</strong> 12 questões discursivas de Linguagens + Redação.
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <!-- CARD 2: RAIO-X DA REDAÇÃO -->
                            <div class="p-5 rounded-2xl border-2 border-blue-200 bg-blue-50/30 space-y-3">
                                <div class="flex items-center gap-2 text-blue-800 font-outfit font-extrabold text-sm">
                                    <i class="bi bi-pen-fill"></i> Redação UNESP: Tema em Pergunta
                                </div>
                                <ul class="text-xs text-slate-600 space-y-2 font-medium">
                                    <li><strong>Gênero:</strong> Dissertativo-argumentativo formal.</li>
                                    <li><strong>Marca Registrada:</strong> O tema é formulado quase sempre como uma <strong>pergunta provocadora</strong> (ex: <em>"A tristeza virou doença?", "O voto nulo é ato de cidadania?"</em>).</li>
                                    <li><strong>O Grande Segredo:</strong> <strong>Tome partido com firmeza!</strong> A Vunesp desconta nota de candidatos que tentam ficar em cima do muro. Escolha um lado, justifique com argumentos lógicos e refute o contra-argumento.</li>
                                    <li><strong>Não exige intervenção social:</strong> Assim como a Fuvest, não há necessidade de criar propostas de intervenção detalhadas.</li>
                                </ul>
                            </div>

                            <!-- CARD 3: LIVROS & CONTEÚDOS -->
                            <div class="p-5 rounded-2xl border-2 border-teal-200 bg-teal-50/30 space-y-3">
                                <div class="flex items-center gap-2 text-teal-800 font-outfit font-extrabold text-sm">
                                    <i class="bi bi-bookmark-check-fill"></i> Sem Lista Fechada de Livros
                                </div>
                                <ul class="text-xs text-slate-600 space-y-2 font-medium">
                                    <li><strong>Leitura Ampla:</strong> A UNESP <strong>não possui lista fechada de livros obrigatórios</strong>. Suas questões de literatura trazem fragmentos poéticos e narrativos acompanhados de contexto histórico.</li>
                                    <li><strong>Previsibilidade Técnica:</strong> A prova cumpre a matriz curricular do Ensino Médio com excelência. Quem estudou o conteúdo base dificilmente é pego de surpresa.</li>
                                </ul>
                            </div>

                        </div>

                    </div>

                    <!-- BLOCO 4: UERJ -->
                    <div id="report_uerj" class="exam-report-block hidden space-y-6">
                        
                        <!-- BANNER DA BANCA -->
                        <div class="p-6 rounded-3xl bg-gradient-to-r from-emerald-500/10 via-teal-50 to-cyan-50 border-2 border-emerald-200/80 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-2xl">⚓</span>
                                    <h4 class="font-outfit font-black text-xl text-slate-900 mb-0">DSEA — Universidade do Estado do Rio de Janeiro (UERJ)</h4>
                                </div>
                                <p class="text-xs text-slate-600 font-medium max-w-2xl leading-relaxed mb-0">
                                    O vestibular público mais emblemático do Rio de Janeiro. Conhecido pelos seus <strong>dois Exames de Qualificação com conceitos (A a D)</strong> e exame discursivo com redação baseada em romance.
                                </p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="px-3 py-1.5 rounded-xl font-outfit font-extrabold text-xs bg-emerald-100 text-emerald-900 border border-emerald-300">
                                    ⭐ Vestibular Estadual RJ
                                </span>
                                <span class="px-3 py-1.5 rounded-xl font-outfit font-extrabold text-xs bg-teal-100 text-teal-900 border border-teal-200">
                                    ~5.800 Vagas
                                </span>
                            </div>
                        </div>

                        <!-- CARDS DE ESTRUTURA -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            
                            <div class="p-5 rounded-2xl border-2 border-slate-200 bg-slate-50/50 space-y-3">
                                <div class="flex items-center gap-2 text-teal-700 font-outfit font-extrabold text-sm">
                                    <i class="bi bi-trophy"></i> Exames de Qualificação (EQ)
                                </div>
                                <p class="text-xs text-slate-600 font-medium leading-relaxed mb-1">
                                    A UERJ aplica 2 Exames de Qualificação de 60 questões objetivas. O candidato pode fazer ambos e vale a melhor nota:
                                </p>
                                <div class="text-[11px] text-slate-600 space-y-1 bg-white p-3 rounded-xl border border-slate-200">
                                    <div>🏆 <strong>Conceito A (>70% acertos):</strong> 20 pontos de bônus</div>
                                    <div>⭐ <strong>Conceito B (60-70% acertos):</strong> 15 pontos de bônus</div>
                                    <div>📈 <strong>Conceito C (50-60% acertos):</strong> 10 pontos de bônus</div>
                                    <div>🎯 <strong>Conceito D (40-50% acertos):</strong> 5 pontos de bônus</div>
                                </div>
                            </div>

                            <div class="p-5 rounded-2xl border-2 border-emerald-200 bg-emerald-50/30 space-y-3">
                                <div class="flex items-center gap-2 text-emerald-800 font-outfit font-extrabold text-sm">
                                    <i class="bi bi-pen-fill"></i> Redação UERJ
                                </div>
                                <ul class="text-xs text-slate-600 space-y-2 font-medium">
                                    <li><strong>Inspirada em Romance:</strong> A redação é sempre formulada a partir dos dilemas morais e sociais de um livro clássico ou contemporâneo indicado no edital.</li>
                                    <li><strong>Foco em Autoria:</strong> A banca odeia repertórios decorados ou modelos prontos. Premia quem desenvolve uma reflexão genuína sobre a condição humana.</li>
                                </ul>
                            </div>

                            <div class="p-5 rounded-2xl border-2 border-teal-200 bg-teal-50/30 space-y-3">
                                <div class="flex items-center gap-2 text-teal-800 font-outfit font-extrabold text-sm">
                                    <i class="bi bi-people-fill"></i> Perfil Humanista
                                </div>
                                <ul class="text-xs text-slate-600 space-y-2 font-medium">
                                    <li><strong>Compromisso Social:</strong> A UERJ foi pioneira histórica no sistema de cotas sociais e raciais no Brasil.</li>
                                    <li><strong>Interpretação e Crítica:</strong> Textos de ciências humanas e linguagens debatem democracia, desigualdade e cultura brasileira.</li>
                                </ul>
                            </div>

                        </div>

                    </div>

                    <!-- BLOCO 5: ENEM (INEP) -->
                    <div id="report_enem" class="exam-report-block hidden space-y-6">
                        
                        <!-- BANNER DA BANCA -->
                        <div class="p-6 rounded-3xl bg-gradient-to-r from-emerald-500/10 via-amber-50 to-yellow-50 border-2 border-emerald-200/80 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-2xl">🇧🇷</span>
                                    <h4 class="font-outfit font-black text-xl text-slate-900 mb-0">ENEM — Exame Nacional do Ensino Médio (INEP)</h4>
                                </div>
                                <p class="text-xs text-slate-600 font-medium max-w-2xl leading-relaxed mb-0">
                                    O maior vestibular do país e porta de entrada universal para o <strong>SiSU, ProUni, FIES</strong> e universidades portuguesas. Avaliado pela Teoria de Resposta ao Item (TRI).
                                </p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="px-3 py-1.5 rounded-xl font-outfit font-extrabold text-xs bg-emerald-100 text-emerald-900 border border-emerald-300">
                                    Acesso Nacional
                                </span>
                                <span class="px-3 py-1.5 rounded-xl font-outfit font-extrabold text-xs bg-amber-100 text-amber-900 border border-amber-300">
                                    +3.9M Inscritos
                                </span>
                            </div>
                        </div>

                        <!-- CARDS DE ESTRUTURA -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            
                            <div class="p-5 rounded-2xl border-2 border-slate-200 bg-slate-50/50 space-y-3">
                                <div class="flex items-center gap-2 text-indigo-700 font-outfit font-extrabold text-sm">
                                    <i class="bi bi-calendar-event"></i> 2 Domingos de Prova
                                </div>
                                <ul class="text-xs text-slate-600 space-y-2 font-medium">
                                    <li><strong>Dia 1 (5h30):</strong> 45 questões de Linguagens + 45 de Humanas + Redação dissertativa-argumentativa (0 a 1000).</li>
                                    <li><strong>Dia 2 (5h00):</strong> 45 questões de Matemática + 45 de Ciências da Natureza. Prova cansativa com textos longos.</li>
                                    <li><strong>TRI:</strong> Coerência pedagógica é tudo! Acertar as fáceis e médias alavanca sua nota mais do que chutar difíceis.</li>
                                </ul>
                            </div>

                            <div class="p-5 rounded-2xl border-2 border-purple-200 bg-purple-50/30 space-y-3">
                                <div class="flex items-center gap-2 text-purple-800 font-outfit font-extrabold text-sm">
                                    <i class="bi bi-pen-fill"></i> 5 Competências da Redação
                                </div>
                                <ul class="text-xs text-slate-600 space-y-1.5 font-medium">
                                    <li><strong>C1:</strong> Norma culta formal e ortografia (200 pts).</li>
                                    <li><strong>C2:</strong> Compreensão do tema + repertório legitimado (200 pts).</li>
                                    <li><strong>C3:</strong> Projeto de texto e argumentação (200 pts).</li>
                                    <li><strong>C4:</strong> Mecanismos de coesão e conectivos (200 pts).</li>
                                    <li><strong>C5:</strong> <strong class="text-indigo-600">Proposta de Intervenção Completa</strong> (5 elementos: Agente, Ação, Meio, Efeito e Detalhe).</li>
                                </ul>
                            </div>

                            <div class="p-5 rounded-2xl border-2 border-emerald-200 bg-emerald-50/30 space-y-3">
                                <div class="flex items-center gap-2 text-emerald-800 font-outfit font-extrabold text-sm">
                                    <i class="bi bi-bullseye"></i> Estratégia no ENEM
                                </div>
                                <ul class="text-xs text-slate-600 space-y-2 font-medium">
                                    <li><strong>Alavancagem de Matemática:</strong> Por ser a área com a régua TRI mais alta (até 980+ pontos), é a disciplina que mais sobe sua média simples ou ponderada.</li>
                                    <li><strong>Redação 900+:</strong> É o único 1000 absoluto da prova, pois não usa TRI e tem pontuação determinística por critérios.</li>
                                </ul>
                            </div>

                        </div>

                    </div>

                    <!-- BLOCO 6: QUADRO COMPARATIVO GERAL -->
                    <div id="report_comparativo" class="exam-report-block hidden space-y-5">
                        
                        <div>
                            <h4 class="font-outfit font-black text-lg text-slate-900 mb-1 flex items-center gap-2">
                                <i class="bi bi-table text-indigo-600"></i>
                                Comparativo Sinóptico dos Grandes Vestibulares
                            </h4>
                            <p class="text-xs text-slate-500 font-medium mb-0">
                                Veja lado a lado as diferenças operacionais de cada vestibular para alinhar seu cronograma de estudos:
                            </p>
                        </div>

                        <div class="overflow-x-auto rounded-2xl border-2 border-slate-200 shadow-xs">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead class="bg-slate-100 text-slate-700 font-outfit font-black uppercase text-[11px] border-b-2 border-slate-200">
                                    <tr>
                                        <th class="py-3.5 px-4">Critério</th>
                                        <th class="py-3.5 px-4 text-indigo-700">🏛️ FUVEST (USP)</th>
                                        <th class="py-3.5 px-4 text-rose-700">🔬 UNICAMP</th>
                                        <th class="py-3.5 px-4 text-blue-700">📚 UNESP</th>
                                        <th class="py-3.5 px-4 text-teal-700">⚓ UERJ</th>
                                        <th class="py-3.5 px-4 text-emerald-700">🇧🇷 ENEM</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white font-medium text-slate-700">
                                    <tr class="hover:bg-slate-50/80">
                                        <td class="py-3 px-4 font-bold text-slate-900 bg-slate-50/50">Questões 1ª Fase</td>
                                        <td class="py-3 px-4 font-mono font-bold">90 questões (A-E)</td>
                                        <td class="py-3 px-4 font-mono font-bold text-rose-700">72 questões (A-D)</td>
                                        <td class="py-3 px-4 font-mono font-bold">90 questões (A-E)</td>
                                        <td class="py-3 px-4 font-mono font-bold">60 questões (A-D)</td>
                                        <td class="py-3 px-4 font-mono font-bold text-emerald-700">180 questões (A-E)</td>
                                    </tr>
                                    <tr class="hover:bg-slate-50/80">
                                        <td class="py-3 px-4 font-bold text-slate-900 bg-slate-50/50">Duração 1ª Fase</td>
                                        <td class="py-3 px-4">5h00</td>
                                        <td class="py-3 px-4">5h00</td>
                                        <td class="py-3 px-4">5h00</td>
                                        <td class="py-3 px-4">4h00</td>
                                        <td class="py-3 px-4">5h30 (Dia 1) / 5h00 (Dia 2)</td>
                                    </tr>
                                    <tr class="hover:bg-slate-50/80">
                                        <td class="py-3 px-4 font-bold text-slate-900 bg-slate-50/50">Formato da 2ª Fase</td>
                                        <td class="py-3 px-4">2 dias discursivos (10 Port + Redação / 12 específicas)</td>
                                        <td class="py-3 px-4">2 dias discursivos (Redação + Provas Comuns e Específicas)</td>
                                        <td class="py-3 px-4">2 dias discursivos (36 questões + Redação)</td>
                                        <td class="py-3 px-4">Exame Discursivo (Português + 2 específicas + Redação)</td>
                                        <td class="py-3 px-4 text-slate-400">Não possui 2ª fase (Fase única)</td>
                                    </tr>
                                    <tr class="hover:bg-slate-50/80">
                                        <td class="py-3 px-4 font-bold text-slate-900 bg-slate-50/50">Gênero da Redação</td>
                                        <td class="py-3 px-4">Dissertativa-argumentativa reflexiva</td>
                                        <td class="py-3 px-4 font-bold text-rose-700">Gêneros variados (2 opções para escolher 1)</td>
                                        <td class="py-3 px-4">Dissertação com tema-pergunta</td>
                                        <td class="py-3 px-4">Dissertação baseada em romance indicado</td>
                                        <td class="py-3 px-4">Dissertativa-argumentativa padrão</td>
                                    </tr>
                                    <tr class="hover:bg-slate-50/80">
                                        <td class="py-3 px-4 font-bold text-slate-900 bg-slate-50/50">Proposta de Intervenção?</td>
                                        <td class="py-3 px-4 text-rose-600 font-bold">❌ Não obrigatória</td>
                                        <td class="py-3 px-4 text-rose-600 font-bold">❌ Não obrigatória</td>
                                        <td class="py-3 px-4 text-rose-600 font-bold">❌ Não obrigatória</td>
                                        <td class="py-3 px-4 text-rose-600 font-bold">❌ Não obrigatória</td>
                                        <td class="py-3 px-4 text-emerald-700 font-bold">✅ Obrigatória com 5 elementos</td>
                                    </tr>
                                    <tr class="hover:bg-slate-50/80">
                                        <td class="py-3 px-4 font-bold text-slate-900 bg-slate-50/50">Lista de Livros?</td>
                                        <td class="py-3 px-4 text-indigo-700 font-bold">✅ Sim (9 obras oficiais)</td>
                                        <td class="py-3 px-4 text-rose-700 font-bold">✅ Sim (8 obras oficiais)</td>
                                        <td class="py-3 px-4 text-slate-500">❌ Sem lista fixa</td>
                                        <td class="py-3 px-4 text-teal-700 font-bold">✅ Sim (1 livro EQ + 1 Redação)</td>
                                        <td class="py-3 px-4 text-slate-500">❌ Sem lista fixa</td>
                                    </tr>
                                    <tr class="hover:bg-slate-50/80">
                                        <td class="py-3 px-4 font-bold text-slate-900 bg-slate-50/50">Método de Correção</td>
                                        <td class="py-3 px-4">Escore bruto padronizado</td>
                                        <td class="py-3 px-4">Padronização por média e desvio (NPE)</td>
                                        <td class="py-3 px-4">Escore bruto somatório</td>
                                        <td class="py-3 px-4">Conceitos (A-D) + Nota discursiva</td>
                                        <td class="py-3 px-4 font-bold text-emerald-700">TRI (Teoria de Resposta ao Item)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>

                <!-- SEÇÃO 2: PESQUISA DIRETA GOOGLE COM SELEÇÃO DE INTENÇÃO (MANTIDA & INTEGRADA) -->
                <div id="sectionGoogle" class="bg-white rounded-3xl border-2 border-slate-200 p-6 sm:p-8 shadow-[0_4px_0_0_#e2e8f0] hidden space-y-6">
                    
                    <div class="text-center max-w-2xl mx-auto mb-6">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-50 border-2 border-indigo-200 flex items-center justify-center text-indigo-600 mx-auto mb-3">
                            <i class="bi bi-search text-2xl"></i>
                        </div>
                        <h2 class="font-outfit font-extrabold text-2xl sm:text-3xl text-slate-900 mb-1.5">Pesquisa Direta de Cursos & Faculdades</h2>
                        <p class="text-xs sm:text-sm text-slate-500 font-medium">Selecione o tipo de informação desejada e digite a faculdade ou curso para buscar em tempo real no Google:</p>
                    </div>

                    <!-- PASSO 1: SELEÇÃO DE INTENÇÃO / TIPO DE PESQUISA -->
                    <div class="mb-6">
                        <label class="block font-outfit font-extrabold text-xs uppercase tracking-wider text-slate-400 mb-3 text-center sm:text-left">
                            1. Selecione o que você deseja consultar:
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5" id="intentContainer">
                            
                            <!-- OPÇÃO 1: NOTA DE CORTE (DEFAULT) -->
                            <button type="button" onclick="setSearchIntent('corte')" id="intent_corte" class="intent-btn flex items-center gap-3 p-3 rounded-2xl border-2 border-indigo-600 bg-indigo-50 text-indigo-900 font-extrabold text-xs shadow-sm transition-all">
                                <div class="w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center flex-shrink-0">
                                    <i class="bi bi-graph-up-arrow text-sm"></i>
                                </div>
                                <div class="text-left min-w-0">
                                    <span class="block truncate font-outfit">Nota de Corte</span>
                                    <span class="block text-[10px] font-normal text-indigo-600 truncate">SISU, ProUni & Vestibulares</span>
                                </div>
                            </button>

                            <!-- OPÇÃO 2: SE EXISTE O CURSO NA FACULDADE -->
                            <button type="button" onclick="setSearchIntent('existencia')" id="intent_existencia" class="intent-btn flex items-center gap-3 p-3 rounded-2xl border-2 border-slate-200 hover:border-indigo-300 bg-white text-slate-700 font-extrabold text-xs transition-all">
                                <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center flex-shrink-0">
                                    <i class="bi bi-building-check text-sm"></i>
                                </div>
                                <div class="text-left min-w-0">
                                    <span class="block truncate font-outfit">Existe o Curso?</span>
                                    <span class="block text-[10px] font-normal text-slate-400 truncate">Verificar se a faculdade oferece</span>
                                </div>
                            </button>

                            <!-- OPÇÃO 3: GRADE CURRICULAR & MATÉRIAS -->
                            <button type="button" onclick="setSearchIntent('grade')" id="intent_grade" class="intent-btn flex items-center gap-3 p-3 rounded-2xl border-2 border-slate-200 hover:border-indigo-300 bg-white text-slate-700 font-extrabold text-xs transition-all">
                                <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center flex-shrink-0">
                                    <i class="bi bi-journal-bookmark-fill text-sm"></i>
                                </div>
                                <div class="text-left min-w-0">
                                    <span class="block truncate font-outfit">Grade Curricular</span>
                                    <span class="block text-[10px] font-normal text-slate-400 truncate">Matérias e disciplinas do curso</span>
                                </div>
                            </button>

                            <!-- OPÇÃO 4: MERCADO DE TRABALHO & SALÁRIOS -->
                            <button type="button" onclick="setSearchIntent('mercado')" id="intent_mercado" class="intent-btn flex items-center gap-3 p-3 rounded-2xl border-2 border-slate-200 hover:border-indigo-300 bg-white text-slate-700 font-extrabold text-xs transition-all">
                                <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center flex-shrink-0">
                                    <i class="bi bi-briefcase-fill text-sm"></i>
                                </div>
                                <div class="text-left min-w-0">
                                    <span class="block truncate font-outfit">Mercado & Salários</span>
                                    <span class="block text-[10px] font-normal text-slate-400 truncate">Atuação e média salarial</span>
                                </div>
                            </button>

                            <!-- OPÇÃO 5: PESQUISA LIVRE -->
                            <button type="button" onclick="setSearchIntent('livre')" id="intent_livre" class="intent-btn flex items-center gap-3 p-3 rounded-2xl border-2 border-slate-200 hover:border-indigo-300 bg-white text-slate-700 font-extrabold text-xs transition-all sm:col-span-2 md:col-span-2">
                                <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center flex-shrink-0">
                                    <i class="bi bi-sliders text-sm"></i>
                                </div>
                                <div class="text-left min-w-0">
                                    <span class="block truncate font-outfit">Pesquisa Livre</span>
                                    <span class="block text-[10px] font-normal text-slate-400 truncate">Buscar exatamente o que eu digitar</span>
                                </div>
                            </button>

                        </div>
                    </div>

                    <!-- PASSO 2: CAMPO DE BUSCA COM PREVIEW -->
                    <form onsubmit="searchGoogle(event)" class="space-y-4 max-w-2xl mx-auto">
                        <label class="block font-outfit font-extrabold text-xs uppercase tracking-wider text-slate-400 mb-1 text-center sm:text-left">
                            2. Digite o curso e/ou faculdade:
                        </label>

                        <div class="flex flex-col sm:flex-row items-center gap-3">
                            <div class="relative w-full">
                                <input type="text" id="googleQuery" oninput="updatePreview()" placeholder="Ex: Medicina USP, Engenharia UNICAMP, Direito..." required class="w-full px-5 py-3.5 rounded-2xl border-2 border-slate-200 focus:border-indigo-600 focus:outline-none font-bold text-slate-900 shadow-inner">
                            </div>
                            <button type="submit" class="w-full sm:w-auto px-6 py-3.5 rounded-2xl font-outfit font-extrabold text-sm bg-indigo-600 text-white shadow-[0_4px_0_0_#312e81] hover:bg-indigo-700 active:translate-y-0.5 transition-all whitespace-nowrap flex items-center justify-center gap-2">
                                <i class="bi bi-google text-base"></i>
                                Pesquisar 🚀
                            </button>
                        </div>

                        <!-- PRÉ-VISUALIZAÇÃO DA BUSCA FORMATADA -->
                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 text-left">
                            <span class="text-[10px] font-extrabold uppercase text-slate-400 block mb-0.5">Sua busca no Google ficará assim:</span>
                            <div class="flex items-center gap-2 text-xs font-mono font-bold text-indigo-700 truncate" id="queryPreview">
                                <i class="bi bi-search text-slate-400"></i>
                                <span id="previewText">Digite um curso ou faculdade acima...</span>
                            </div>
                        </div>
                    </form>

                    <!-- EXEMPLOS DE BUSCA RÁPIDA -->
                    <div class="border-t border-slate-200 pt-6 mt-8">
                        <h4 class="font-outfit font-bold text-xs uppercase tracking-wider text-slate-400 mb-3">Exemplos Práticos em 1 Clique</h4>
                        <div class="flex flex-wrap items-center justify-center gap-2">
                            <button type="button" onclick="quickFill('Medicina USP', 'corte')" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 border border-slate-200 text-xs font-bold text-slate-700 transition-all">
                                📊 Medicina USP (Nota de Corte)
                            </button>
                            <button type="button" onclick="quickFill('Engenharia de Computação Unicamp', 'existencia')" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 border border-slate-200 text-xs font-bold text-slate-700 transition-all">
                                🏫 Eng. Computação Unicamp (Possui?)
                            </button>
                            <button type="button" onclick="quickFill('Direito UFRJ', 'grade')" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 border border-slate-200 text-xs font-bold text-slate-700 transition-all">
                                📚 Direito UFRJ (Grade Curricular)
                            </button>
                            <button type="button" onclick="quickFill('Psicologia Unesp', 'corte')" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 border border-slate-200 text-xs font-bold text-slate-700 transition-all">
                                📊 Psicologia Unesp (Nota de Corte)
                            </button>
                        </div>
                    </div>

                </div>
            </section>
        </div>
    </main>

    <!-- SCRIPTS -->
    <script>
        // GERENCIAMENTO DAS ABAS
        function switchGuideTab(tab, btn) {
            const secDb = document.getElementById('sectionDatabase');
            const secVestibulares = document.getElementById('sectionVestibulares');
            const secGoogle = document.getElementById('sectionGoogle');
            const btnDb = document.getElementById('tabBtnDatabase');
            const btnVestibulares = document.getElementById('tabBtnVestibulares');
            const btnGoogle = document.getElementById('tabBtnGoogle');

            const defaultBtnClass = 'px-4 py-2 rounded-xl font-outfit font-black text-xs text-slate-600 hover:text-slate-900 transition-all flex items-center gap-2';
            const activeBtnClass = 'px-4 py-2 rounded-xl font-outfit font-black text-xs bg-white text-indigo-900 shadow-sm transition-all flex items-center gap-2';

            if (secDb) secDb.classList.add('hidden');
            if (secVestibulares) secVestibulares.classList.add('hidden');
            if (secGoogle) secGoogle.classList.add('hidden');

            if (btnDb) btnDb.className = defaultBtnClass;
            if (btnVestibulares) btnVestibulares.className = defaultBtnClass;
            if (btnGoogle) btnGoogle.className = defaultBtnClass;

            if (tab === 'database') {
                if (secDb) secDb.classList.remove('hidden');
                if (btnDb) btnDb.className = activeBtnClass;
            } else if (tab === 'vestibulares') {
                if (secVestibulares) secVestibulares.classList.remove('hidden');
                if (btnVestibulares) btnVestibulares.className = activeBtnClass;
            } else if (tab === 'google') {
                if (secGoogle) secGoogle.classList.remove('hidden');
                if (btnGoogle) btnGoogle.className = activeBtnClass;
            }
        }

        // ALTERNAR RELATÓRIOS ENTRE AS BANCAS DE VESTIBULAR
        function showExamReport(examKey, btn) {
            document.querySelectorAll('.exam-report-block').forEach(el => el.classList.add('hidden'));
            const target = document.getElementById(`report_${examKey}`);
            if (target) target.classList.remove('hidden');

            document.querySelectorAll('.exam-btn').forEach(b => {
                b.className = 'exam-btn px-4 py-2.5 rounded-2xl font-outfit font-black text-xs bg-slate-100 text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 border border-slate-200 flex items-center gap-2 transition-all';
            });
            if (btn) {
                btn.className = 'exam-btn active px-4 py-2.5 rounded-2xl font-outfit font-black text-xs bg-indigo-600 text-white shadow-[0_3px_0_0_#312e81] flex items-center gap-2 transition-all';
            }
        }

        // LÓGICA DA TABELA DO BANCO DE DADOS
        let dbCourses = [];
        let filteredDbCourses = [];
        let dbCurrentPage = 1;
        const dbPageSize = 15;

        async function loadDbCourses() {
            try {
                const res = await fetch('api/get_course_guides.php');
                const data = await res.json();
                if (data.success && Array.isArray(data.courses)) {
                    dbCourses = data.courses;
                    filterDatabaseTable();
                } else {
                    document.getElementById('dbTableBody').innerHTML = '<tr><td colspan="6" class="text-center py-6 text-rose-500 font-bold">Erro ao carregar dados do catálogo.</td></tr>';
                }
            } catch (err) {
                console.error(err);
                document.getElementById('dbTableBody').innerHTML = '<tr><td colspan="6" class="text-center py-6 text-rose-500 font-bold">Erro de conexão ao catálogo.</td></tr>';
            }
        }

        function filterDatabaseTable() {
            if (!dbCourses || dbCourses.length === 0) return;

            const search = document.getElementById('dbSearchInput').value.toLowerCase().trim();
            const region = document.getElementById('dbRegionFilter').value;
            const shift = document.getElementById('dbShiftFilter').value;

            filteredDbCourses = dbCourses.filter(c => {
                if (search) {
                    const matchCourse = (c.course_name || '').toLowerCase().includes(search);
                    const matchUni = (c.university_name || '').toLowerCase().includes(search);
                    const matchCity = (c.campus_city || '').toLowerCase().includes(search);
                    if (!matchCourse && !matchUni && !matchCity) return false;
                }
                if (region && c.region !== region) return false;
                if (shift && c.shift !== shift) return false;
                return true;
            });

            dbCurrentPage = 1;
            renderDbTable();
        }

        function renderDbTable() {
            const tbody = document.getElementById('dbTableBody');
            const total = filteredDbCourses.length;

            if (total === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-500">
                            <i class="bi bi-info-circle text-2xl text-slate-400 block mb-1"></i>
                            <span class="font-outfit font-bold text-xs">Nenhum curso encontrado com esses filtros.</span>
                        </td>
                    </tr>
                `;
                document.getElementById('dbResultsCount').textContent = 'Nenhum curso encontrado';
                document.getElementById('dbPageIndicator').textContent = 'Pág. 0/0';
                document.getElementById('dbBtnPrev').disabled = true;
                document.getElementById('dbBtnNext').disabled = true;
                return;
            }

            const totalPages = Math.ceil(total / dbPageSize);
            const startIdx = (dbCurrentPage - 1) * dbPageSize;
            const endIdx = Math.min(startIdx + dbPageSize, total);
            const pageItems = filteredDbCourses.slice(startIdx, endIdx);

            let html = '';
            pageItems.forEach(c => {
                const cutoff = parseFloat(c.cutoff_score) || 0;
                const quotaCutoff = parseFloat(c.quota_cutoff_score || c.cutoff_score) || 0;

                html += `
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3 px-4">
                            <div class="font-outfit font-black text-slate-900 text-xs">${escapeHtml(c.course_name)}</div>
                            <div class="text-[11px] font-bold text-indigo-600 flex items-center gap-1.5 flex-wrap">
                                <span>${escapeHtml(c.university_name)}</span>
                                ${getUniversityBadge(c.university_name)}
                            </div>
                        </td>
                        <td class="py-3 px-4 text-slate-600 font-medium text-[11px]">
                            ${escapeHtml(c.campus_city)}
                            <span class="text-[10px] text-slate-400 block">${escapeHtml(c.region || '')}</span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-bold text-[10px] block">${escapeHtml(c.shift || 'Integral')}</span>
                            <span class="text-[9px] text-slate-400">${escapeHtml(c.degree || 'Bacharelado')}</span>
                        </td>
                        <td class="py-3 px-4 text-right font-mono font-black text-indigo-700 text-xs">
                            ${cutoff.toFixed(2)}
                        </td>
                        <td class="py-3 px-4 text-right font-mono font-black text-slate-600 text-xs">
                            ${quotaCutoff.toFixed(2)}
                        </td>
                        <td class="py-3 px-4 text-center">
                            <a href="guia_concorrencia.php" title="Ver Faixa de Acertos Necessária" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[10px] font-extrabold bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white border border-indigo-200 transition-all">
                                <i class="bi bi-speedometer2"></i> Acertos
                            </a>
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
            document.getElementById('dbResultsCount').textContent = `Mostrando ${startIdx + 1}–${endIdx} de ${total} cursos oficiais`;
            document.getElementById('dbPageIndicator').textContent = `Pág. ${dbCurrentPage} de ${totalPages}`;
            document.getElementById('dbBtnPrev').disabled = (dbCurrentPage === 1);
            document.getElementById('dbBtnNext').disabled = (dbCurrentPage === totalPages);
        }

        function changeDbPage(delta) {
            const totalPages = Math.ceil(filteredDbCourses.length / dbPageSize);
            dbCurrentPage += delta;
            if (dbCurrentPage < 1) dbCurrentPage = 1;
            if (dbCurrentPage > totalPages) dbCurrentPage = totalPages;
            renderDbTable();
        }

        // LÓGICA DO GOOGLE DIRECT SEARCH
        let currentIntent = 'corte';

        const INTENT_SUFFIXES = {
            'corte': 'nota de corte sisu vestibular',
            'existencia': 'tem o curso de graduacao existe faculdade',
            'grade': 'grade curricular materias disciplinas curso',
            'mercado': 'mercado de trabalho salario atuacao',
            'livre': ''
        };

        function setSearchIntent(intentKey) {
            currentIntent = intentKey;
            
            document.querySelectorAll('.intent-btn').forEach(btn => {
                btn.className = 'intent-btn flex items-center gap-3 p-3 rounded-2xl border-2 border-slate-200 hover:border-indigo-300 bg-white text-slate-700 font-extrabold text-xs transition-all';
                const iconBox = btn.querySelector('div');
                if (iconBox) iconBox.className = 'w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center flex-shrink-0';
            });

            const activeBtn = document.getElementById(`intent_${intentKey}`);
            if (activeBtn) {
                activeBtn.className = 'intent-btn flex items-center gap-3 p-3 rounded-2xl border-2 border-indigo-600 bg-indigo-50 text-indigo-900 font-extrabold text-xs shadow-sm transition-all';
                const iconBox = activeBtn.querySelector('div');
                if (iconBox) iconBox.className = 'w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center flex-shrink-0';
            }

            updatePreview();
        }

        function buildFinalQuery() {
            const rawQuery = document.getElementById('googleQuery').value.trim();
            const suffix = INTENT_SUFFIXES[currentIntent] || '';
            if (!rawQuery) return '';
            return suffix ? `${rawQuery} ${suffix}` : rawQuery;
        }

        function updatePreview() {
            const previewEl = document.getElementById('previewText');
            const finalQ = buildFinalQuery();
            if (finalQ) {
                previewEl.textContent = `"${finalQ}"`;
            } else {
                previewEl.textContent = 'Digite um curso ou faculdade acima...';
            }
        }

        function searchGoogle(e) {
            e.preventDefault();
            const finalQ = buildFinalQuery();
            if (finalQ) {
                window.open('https://www.google.com/search?q=' + encodeURIComponent(finalQ), '_blank');
            }
        }

        function quickFill(term, intentKey) {
            document.getElementById('googleQuery').value = term;
            setSearchIntent(intentKey);
            searchGoogle({ preventDefault: () => {} });
        }

        function getUniversityBadge(uniName) {
            if (!uniName) return '';
            const u = uniName.toUpperCase();
            const igc5List = ['USP', 'UNICAMP', 'UNESP', 'UFRGS', 'UFMG', 'UFRJ', 'UFSC', 'UFPR', 'UNB', 'UFPE', 'UFC', 'UFSCAR', 'UNIFESP', 'UFV', 'ITA', 'IME'];
            const igc4List = ['UFU', 'UFF', 'UFBA', 'UFRN', 'UFES', 'UFMS', 'UFG', 'UFJF', 'UFSM', 'UNIRIO', 'FURG', 'UFAL', 'UFPEL', 'UFOP', 'UFLA'];

            for (let item of igc5List) {
                if (u.includes(item)) {
                    return '<span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[9px] font-black bg-amber-100 text-amber-900 border border-amber-300" title="Índice Geral de Cursos (IGC) Nota Máxima do MEC (5)"><i class="bi bi-star-fill text-amber-500"></i> MEC 5</span>';
                }
            }
            for (let item of igc4List) {
                if (u.includes(item)) {
                    return '<span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[9px] font-black bg-blue-100 text-blue-900 border border-blue-200" title="Índice Geral de Cursos (IGC) Alta Excelência MEC (4)"><i class="bi bi-check-circle-fill text-blue-600"></i> MEC 4</span>';
                }
            }
            return '';
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadDbCourses();
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');
            if (tab === 'vestibulares') {
                switchGuideTab('vestibulares');
                const exam = urlParams.get('exam');
                if (exam) {
                    const btn = document.querySelector(`[onclick*="'${exam}'"]`);
                    showExamReport(exam, btn);
                }
            } else if (tab === 'google') {
                switchGuideTab('google');
            }
        });
    </script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
