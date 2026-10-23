<?php
/**
 * GUIA DE CONCORRÊNCIA & FAIXA DE ACERTOS DO ENEM — HIPOGABARITO
 * 
 * Substitui o modelo de "calculadora de notas fixas" por uma abordagem
 * estatística e pedagógica realista:
 * - A nota do ENEM varia via TRI (Teoria de Resposta ao Item) e coerência pedagógica
 * - As notas de corte do SiSU mudam a cada edição conforme concorrência e vagas
 * - Foco 100% no indicador que o estudante realmente controla: ACERTOS NOS SIMULADOS (0 a 45 por prova)
 * - Faixas históricas de concorrência (Altíssima, Alta, Média, Acessível)
 * - Integração com selos MEC/INEP (IGC 5 e 4) e base de 1.350+ cursos oficiais do SiSU
 */
require_once __DIR__ . '/config/db.php';
checkAuth();

$pageTitle = 'Guia de Concorrência & Faixa de Acertos ENEM — HipoGabarito';
require_once __DIR__ . '/includes/header.php';
?>

    <!-- LAYOUT PRINCIPAL -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-all">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- SIDEBAR LATERAL NAVEGAÇÃO -->
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

            <!-- CONTEÚDO PRINCIPAL DO GUIA DE CONCORRÊNCIA -->
            <section class="lg:col-span-9 space-y-6">

                <!-- HERO BANNER -->
                <div class="bg-gradient-to-r from-indigo-700 via-purple-700 to-indigo-800 text-white rounded-3xl p-6 sm:p-8 shadow-[0_4px_0_0_#312e81] relative overflow-hidden">
                    <div class="relative z-10 max-w-2xl">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-white/20 text-amber-200 uppercase tracking-wider mb-3">
                            <i class="bi bi-speedometer2"></i> TERMÔMETRO DE CONCORRÊNCIA & ACERTOS
                        </div>
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold font-outfit text-white mb-2 leading-tight">
                            Guia de Concorrência & Faixas de Acertos
                        </h1>
                        <p class="text-xs sm:text-sm text-indigo-100 font-medium leading-relaxed">
                            A nota do ENEM varia a cada ano pela <strong>TRI</strong> e a concorrência do SiSU oscila. Por isso, <strong>o indicador real de treino são os seus acertos nos simulados</strong>. Ajuste seus acertos médios e descubra para quais níveis de cursos você já tem margem competitiva.
                        </p>
                    </div>

                    <div class="absolute -right-6 -bottom-8 opacity-15 sm:opacity-20 pointer-events-none text-9xl">
                        <i class="bi bi-bullseye"></i>
                    </div>
                </div>

                <!-- AVISO PEDAGÓGICO: POR QUE ACERTOS E NÃO NOTAS FIXAS -->
                <div class="bg-amber-50/70 border-2 border-amber-200/90 rounded-3xl p-5 sm:p-6 text-slate-800 space-y-2">
                    <div class="flex items-center gap-2.5 text-amber-900 font-outfit font-black text-sm">
                        <i class="bi bi-lightbulb-fill text-amber-500 text-lg"></i>
                        Por que avaliar ACERTOS e não promessas de nota fixa?
                    </div>
                    <p class="text-xs text-slate-700 leading-relaxed font-medium mb-0">
                        No ENEM, <strong>duas pessoas com 35 acertos na mesma prova podem ter notas muito diferentes</strong> devido à coerência pedagógica da TRI (acertar as fáceis é mais valorizado que chutar difíceis). Além disso, as notas de corte do SiSU sobem ou descem anualmente conforme vagas e concorrência. Trabalhar com <strong>faixas de acertos históricas</strong> protege você de falsas expectativas e direciona seu treino para o que você realmente controla: <strong>a consistência nos simulados!</strong>
                    </p>
                </div>

                <!-- PRESETS DE NÍVEL DE CONCORRÊNCIA -->
                <div class="bg-white rounded-3xl border-2 border-slate-200 p-6 sm:p-7 shadow-[0_4px_0_0_#e2e8f0] space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
                        <div>
                            <h2 class="text-base sm:text-lg font-extrabold font-outfit text-slate-900 flex items-center gap-2">
                                <i class="bi bi-sliders text-indigo-600"></i>
                                1. Metas Rápidas por Nível de Carreira
                            </h2>
                            <p class="text-xs text-slate-500 font-medium mb-0">Clique para carregar o padrão histórico de acertos exigido para cada patamar:</p>
                        </div>
                        <span id="activePresetBadge" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200">
                            Meta: Medicina / Top Brasil
                        </span>
                    </div>

                    <!-- BOTÕES DE PRESET -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-2.5" id="presetButtons">
                        <button type="button" onclick="applyHitPreset('medicina', this)" class="preset-btn selected flex flex-col items-center justify-center p-3 rounded-2xl border-2 border-indigo-600 bg-indigo-50/80 text-indigo-900 font-extrabold text-xs shadow-sm transition-all hover:border-indigo-600">
                            <span class="text-xl mb-1">🏆</span>
                            <span class="font-outfit text-center">Altíssima Concorrência</span>
                            <span class="text-[10px] font-normal text-indigo-600">Medicina, Direito USP, ITA</span>
                        </button>

                        <button type="button" onclick="applyHitPreset('alta', this)" class="preset-btn flex flex-col items-center justify-center p-3 rounded-2xl border-2 border-slate-200 bg-white text-slate-700 font-extrabold text-xs transition-all hover:border-indigo-300">
                            <span class="text-xl mb-1">⭐</span>
                            <span class="font-outfit text-center">Alta Concorrência</span>
                            <span class="text-[10px] font-normal text-slate-400">Computação, Odonto, Eng.</span>
                        </button>

                        <button type="button" onclick="applyHitPreset('media', this)" class="preset-btn flex flex-col items-center justify-center p-3 rounded-2xl border-2 border-slate-200 bg-white text-slate-700 font-extrabold text-xs transition-all hover:border-indigo-300">
                            <span class="text-xl mb-1">📈</span>
                            <span class="font-outfit text-center">Média Concorrência</span>
                            <span class="text-[10px] font-normal text-slate-400">Adm, Enfermagem, Psico</span>
                        </button>

                        <button type="button" onclick="applyHitPreset('acessivel', this)" class="preset-btn flex flex-col items-center justify-center p-3 rounded-2xl border-2 border-slate-200 bg-white text-slate-700 font-extrabold text-xs transition-all hover:border-indigo-300">
                            <span class="text-xl mb-1">🎯</span>
                            <span class="font-outfit text-center">Acesso Amplo</span>
                            <span class="text-[10px] font-normal text-slate-400">Licenciaturas, Pedagogia</span>
                        </button>

                        <button type="button" onclick="applyHitPreset('custom', this)" class="preset-btn flex flex-col items-center justify-center p-3 rounded-2xl border-2 border-slate-200 bg-white text-slate-700 font-extrabold text-xs transition-all hover:border-indigo-300">
                            <span class="text-xl mb-1">✏️</span>
                            <span class="font-outfit text-center">Meus Acertos</span>
                            <span class="text-[10px] font-normal text-slate-400">Ajuste Livre</span>
                        </button>
                    </div>
                </div>

                <!-- ENTRADA DE ACERTOS NOS SIMULADOS (0 A 45 POR ÁREA) -->
                <div class="bg-white rounded-3xl border-2 border-slate-200 p-6 sm:p-7 shadow-[0_4px_0_0_#e2e8f0] space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <h2 class="text-base sm:text-lg font-extrabold font-outfit text-slate-900 flex items-center gap-2">
                                <i class="bi bi-bullseye text-indigo-600"></i>
                                2. Seus Acertos Médios nos Simulados
                            </h2>
                            <p class="text-xs text-slate-500 font-medium mb-0">Quantas questões você costuma acertar em cada prova (0 a 45 questões)?</p>
                        </div>
                        <button type="button" onclick="resetToCustom()" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                            <i class="bi bi-arrow-counterclockwise"></i> Redefinir
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- ÁREA 1: MATEMÁTICA -->
                        <div class="p-4 rounded-2xl border-2 border-emerald-100 bg-emerald-50/30 hover:border-emerald-200 transition-all space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-lg font-bold shadow-xs">
                                        <i class="bi bi-calculator"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-outfit font-extrabold text-sm text-slate-900 mb-0">Matemática</h3>
                                        <span class="text-[11px] text-emerald-700 font-bold">45 questões • Régua mais elástica</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 bg-white px-3 py-1.5 rounded-xl border border-emerald-200 shadow-2xs">
                                    <input type="number" id="hits_matematica" min="0" max="45" value="36" oninput="syncHits('matematica', this.value)" class="w-12 text-center font-outfit font-black text-base text-slate-900 focus:outline-none">
                                    <span class="text-[11px] font-bold text-slate-400">/ 45</span>
                                </div>
                            </div>
                            <input type="range" id="slider_matematica" min="0" max="45" value="36" oninput="syncHits('matematica', this.value)" class="w-full accent-emerald-600 cursor-pointer">
                            <div class="flex justify-between items-center text-[10px] font-bold text-slate-500">
                                <span>Rendimento: <strong id="pct_matematica" class="text-emerald-700">80%</strong></span>
                                <span id="tri_matematica" class="text-slate-600 font-mono">TRI Estimada: ~780 - 860 pts</span>
                            </div>
                        </div>

                        <!-- ÁREA 2: CIÊNCIAS DA NATUREZA -->
                        <div class="p-4 rounded-2xl border-2 border-teal-100 bg-teal-50/30 hover:border-teal-200 transition-all space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-teal-600 text-white flex items-center justify-center text-lg font-bold shadow-xs">
                                        <i class="bi bi-flower1"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-outfit font-extrabold text-sm text-slate-900 mb-0">Ciências da Natureza</h3>
                                        <span class="text-[11px] text-teal-700 font-bold">45 questões • Física, Química, Biologia</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 bg-white px-3 py-1.5 rounded-xl border border-teal-200 shadow-2xs">
                                    <input type="number" id="hits_natureza" min="0" max="45" value="33" oninput="syncHits('natureza', this.value)" class="w-12 text-center font-outfit font-black text-base text-slate-900 focus:outline-none">
                                    <span class="text-[11px] font-bold text-slate-400">/ 45</span>
                                </div>
                            </div>
                            <input type="range" id="slider_natureza" min="0" max="45" value="33" oninput="syncHits('natureza', this.value)" class="w-full accent-teal-600 cursor-pointer">
                            <div class="flex justify-between items-center text-[10px] font-bold text-slate-500">
                                <span>Rendimento: <strong id="pct_natureza" class="text-teal-700">73%</strong></span>
                                <span id="tri_natureza" class="text-slate-600 font-mono">TRI Estimada: ~670 - 740 pts</span>
                            </div>
                        </div>

                        <!-- ÁREA 3: CIÊNCIAS HUMANAS -->
                        <div class="p-4 rounded-2xl border-2 border-amber-100 bg-amber-50/30 hover:border-amber-200 transition-all space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center text-lg font-bold shadow-xs">
                                        <i class="bi bi-bank"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-outfit font-extrabold text-sm text-slate-900 mb-0">Ciências Humanas</h3>
                                        <span class="text-[11px] text-amber-700 font-bold">45 questões • História, Geo, Filo, Soc</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 bg-white px-3 py-1.5 rounded-xl border border-amber-200 shadow-2xs">
                                    <input type="number" id="hits_humanas" min="0" max="45" value="35" oninput="syncHits('humanas', this.value)" class="w-12 text-center font-outfit font-black text-base text-slate-900 focus:outline-none">
                                    <span class="text-[11px] font-bold text-slate-400">/ 45</span>
                                </div>
                            </div>
                            <input type="range" id="slider_humanas" min="0" max="45" value="35" oninput="syncHits('humanas', this.value)" class="w-full accent-amber-500 cursor-pointer">
                            <div class="flex justify-between items-center text-[10px] font-bold text-slate-500">
                                <span>Rendimento: <strong id="pct_humanas" class="text-amber-700">78%</strong></span>
                                <span id="tri_humanas" class="text-slate-600 font-mono">TRI Estimada: ~690 - 750 pts</span>
                            </div>
                        </div>

                        <!-- ÁREA 4: LINGUAGENS -->
                        <div class="p-4 rounded-2xl border-2 border-sky-100 bg-sky-50/30 hover:border-sky-200 transition-all space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-sky-600 text-white flex items-center justify-center text-lg font-bold shadow-xs">
                                        <i class="bi bi-chat-quote-fill"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-outfit font-extrabold text-sm text-slate-900 mb-0">Linguagens & Códigos</h3>
                                        <span class="text-[11px] text-sky-700 font-bold">45 questões • Português, Literatura, Língua</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 bg-white px-3 py-1.5 rounded-xl border border-sky-200 shadow-2xs">
                                    <input type="number" id="hits_linguagens" min="0" max="45" value="34" oninput="syncHits('linguagens', this.value)" class="w-12 text-center font-outfit font-black text-base text-slate-900 focus:outline-none">
                                    <span class="text-[11px] font-bold text-slate-400">/ 45</span>
                                </div>
                            </div>
                            <input type="range" id="slider_linguagens" min="0" max="45" value="34" oninput="syncHits('linguagens', this.value)" class="w-full accent-sky-600 cursor-pointer">
                            <div class="flex justify-between items-center text-[10px] font-bold text-slate-500">
                                <span>Rendimento: <strong id="pct_linguagens" class="text-sky-700">76%</strong></span>
                                <span id="tri_linguagens" class="text-slate-600 font-mono">TRI Estimada: ~650 - 710 pts</span>
                            </div>
                        </div>

                    </div>

                    <!-- ÁREA 5: REDAÇÃO (FAIXA DE NOTA DE TREINO) -->
                    <div class="p-4 rounded-2xl border-2 border-purple-100 bg-purple-50/30 hover:border-purple-200 transition-all space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center text-lg font-bold shadow-xs">
                                    <i class="bi bi-pen-fill"></i>
                                </div>
                                <div>
                                    <h3 class="font-outfit font-extrabold text-sm text-slate-900 mb-0">Redação (Média nos Treinos)</h3>
                                    <span class="text-[11px] text-purple-700 font-bold">Avaliação por 5 competências (0 a 1000) • Não usa TRI</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <select id="score_redacao" onchange="syncRedacao(this.value)" class="px-3 py-1.5 rounded-xl border-2 border-purple-200 focus:border-purple-600 font-outfit font-black text-xs text-purple-900 bg-white cursor-pointer">
                                    <option value="600">600 pts (Base Média Nacional)</option>
                                    <option value="720">720 pts (Intermediário Sólido)</option>
                                    <option value="800">800 pts (Bom Rendimento)</option>
                                    <option value="880">880 pts (Alta Performance)</option>
                                    <option value="920" selected>920 pts (Nível Concorrência Alta)</option>
                                    <option value="960">960 pts (Elite Nacional / Medicina)</option>
                                    <option value="1000">1000 pts (Nota Máxima Rara)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- PAINEL DO TERMÔMETRO: DIAGNÓSTICO DE ACERTOS E COMPETITIVIDADE -->
                <div class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-[0_4px_0_0_#1e1b4b] border-2 border-indigo-800">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">

                        <!-- COLUNA DA ESQUERDA: TOTAL DE ACERTOS -->
                        <div class="md:col-span-5 text-center md:text-left border-b md:border-b-0 md:border-r border-indigo-800/80 pb-6 md:pb-0 md:pr-6">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 uppercase tracking-wider mb-2">
                                <i class="bi bi-speedometer2 text-amber-400"></i> Seu Volume de Acertos
                            </span>
                            
                            <div class="flex items-baseline justify-center md:justify-start gap-2 mb-1">
                                <span class="text-4xl sm:text-5xl lg:text-6xl font-black font-outfit text-white tracking-tight" id="totalHitsDisplay">
                                    138
                                </span>
                                <span class="text-base text-indigo-300 font-bold">/ 180 questões</span>
                            </div>

                            <p class="text-xs text-indigo-200 font-medium mb-3">
                                Taxa Global: <strong id="globalHitRate" class="text-amber-300 font-black">76.7% da prova</strong>
                            </p>

                            <div class="p-3 rounded-2xl bg-white/10 text-xs text-indigo-100 space-y-1">
                                <div class="flex justify-between items-center text-[11px]">
                                    <span>Faixa Histórica de TRI:</span>
                                    <strong id="triRangeEstimate" class="text-amber-300 font-mono font-black">~720 a 785 pts</strong>
                                </div>
                                <span class="block text-[10px] text-indigo-300">Margem real considerando oscilação de coerência pedagógica.</span>
                            </div>
                        </div>

                        <!-- COLUNA DA DIREITA: NÍVEL DE CONCORRÊNCIA ATINGIDO -->
                        <div class="md:col-span-7 space-y-3">
                            <h3 class="font-outfit font-extrabold text-base text-white flex items-center gap-2">
                                <i class="bi bi-fire text-amber-400"></i>
                                Termômetro de Competitividade
                            </h3>

                            <div class="p-4 rounded-2xl bg-white/5 border border-white/10 space-y-3">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                    <span class="text-xs text-indigo-200">Faixa de Concorrência:</span>
                                    <span id="tierBadge" class="font-black px-3 py-1 rounded-xl text-xs bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                                        ⭐ Alta Concorrência (Top 5%)
                                    </span>
                                </div>

                                <!-- BARRA DE TERMÔMETRO -->
                                <div class="space-y-1">
                                    <div class="h-3 bg-slate-800 rounded-full overflow-hidden p-0.5 border border-white/10">
                                        <div id="thermometerBar" class="h-full bg-gradient-to-r from-teal-400 via-indigo-500 to-purple-500 rounded-full transition-all duration-700" style="width: 78%"></div>
                                    </div>
                                    <div class="flex justify-between text-[9px] text-slate-400 font-bold uppercase tracking-wider">
                                        <span>Acessível (40%)</span>
                                        <span>Média (55%)</span>
                                        <span>Alta (75%)</span>
                                        <span>Top Elite (85%+)</span>
                                    </div>
                                </div>

                                <p id="tierInsightText" class="text-xs text-slate-300 font-medium leading-relaxed mb-0">
                                    Seus acertos médios já cobrem confortavelmente cursos de <strong>Computação, Odontologia, Engenharias e Psicologia</strong> na ampla concorrência da maioria das universidades públicas.
                                </p>
                            </div>

                            <div class="flex items-start gap-2.5 p-3 rounded-xl bg-amber-500/10 border border-amber-500/20 text-[11px] text-amber-200">
                                <i class="bi bi-shield-check text-amber-400 shrink-0 text-sm mt-0.5"></i>
                                <span><strong>Princípio da Segurança:</strong> Para cursos ultra-disputados (Medicina/Direito em federais de ponta), a meta ideal nos simulados é manter <strong>145+ acertos</strong> e <strong>Redação 920+</strong> de forma consistente.</span>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- GUIA DE CURSOS OFICIAIS COM FAIXAS DE CONCORRÊNCIA -->
                <div class="bg-white rounded-3xl border-2 border-slate-200 p-6 sm:p-7 shadow-[0_4px_0_0_#e2e8f0] space-y-5">
                    
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                        <div>
                            <h2 class="text-base sm:text-lg font-extrabold font-outfit text-slate-900 flex items-center gap-2">
                                <i class="bi bi-mortarboard-fill text-indigo-600"></i>
                                3. Radar de Cursos & Metas de Acertos
                            </h2>
                            <p class="text-xs text-slate-500 font-medium mb-0">
                                Veja como seus acertos atuais se posicionam frente às faixas de concorrência de <strong>1.350+ cursos oficiais do SiSU</strong>.
                            </p>
                        </div>

                        <!-- MODALIDADE: AMPLA CONCORRÊNCIA VS COTAS -->
                        <div class="flex items-center gap-1.5 p-1 bg-slate-100 rounded-2xl border border-slate-200 shrink-0">
                            <button type="button" onclick="setQuotaMode('ampla', this)" id="btnModeAmpla" class="px-3 py-1.5 rounded-xl font-outfit font-extrabold text-xs bg-white text-indigo-900 shadow-sm transition-all">
                                Ampla Concorrência
                            </button>
                            <button type="button" onclick="setQuotaMode('cota', this)" id="btnModeCota" class="px-3 py-1.5 rounded-xl font-outfit font-extrabold text-xs text-slate-600 hover:text-slate-900 transition-all">
                                Cotas / Ações Afirmativas
                            </button>
                        </div>
                    </div>

                    <!-- FILTROS DE PESQUISA NA BASE -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                        <div class="sm:col-span-6 relative">
                            <i class="bi bi-search absolute left-3.5 top-3 text-slate-400"></i>
                            <input type="text" id="courseSearchInput" oninput="filterCoursesTable()" placeholder="Buscar curso ou universidade (Ex: Medicina, USP, Direito, UFRJ...)" class="w-full pl-10 pr-4 py-2.5 rounded-xl border-2 border-slate-200 focus:border-indigo-600 text-xs font-bold text-slate-800 focus:outline-none">
                        </div>

                        <div class="sm:col-span-3">
                            <select id="regionFilter" onchange="filterCoursesTable()" class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 focus:border-indigo-600 text-xs font-bold text-slate-800 focus:outline-none cursor-pointer">
                                <option value="">Todas as Regiões</option>
                                <option value="Sudeste">Sudeste</option>
                                <option value="Sul">Sul</option>
                                <option value="Nordeste">Nordeste</option>
                                <option value="Centro-Oeste">Centro-Oeste</option>
                                <option value="Norte">Norte</option>
                            </select>
                        </div>

                        <div class="sm:col-span-3">
                            <select id="statusFilter" onchange="filterCoursesTable()" class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 focus:border-indigo-600 text-xs font-bold text-slate-800 focus:outline-none cursor-pointer">
                                <option value="all">Todas as Faixas</option>
                                <option value="seguranca" selected>🟢 Faixa de Segurança</option>
                                <option value="disputa">🟡 Zona de Disputa</option>
                                <option value="meta">🔴 Meta de Avanço</option>
                            </select>
                        </div>
                    </div>

                    <!-- TABELA DE RESULTADOS DO SISU -->
                    <div class="overflow-x-auto rounded-2xl border-2 border-slate-200 shadow-xs">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead class="bg-slate-100 text-slate-700 font-outfit font-black uppercase text-[11px] border-b-2 border-slate-200">
                                <tr>
                                    <th class="py-3 px-4">Curso & Instituição</th>
                                    <th class="py-3 px-4">Cidade / UF</th>
                                    <th class="py-3 px-4 text-center">Nível de Disputa</th>
                                    <th class="py-3 px-4 text-right">Corte Histórico</th>
                                    <th class="py-3 px-4 text-center">Acertos Recomendados</th>
                                    <th class="py-3 px-4 text-center">Seu Momento</th>
                                </tr>
                            </thead>
                            <tbody id="courseTableBody" class="divide-y divide-slate-100 bg-white font-medium text-slate-700">
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-400">
                                        <div class="inline-block animate-spin w-6 h-6 border-2 border-indigo-600 border-t-transparent rounded-full mb-2"></div>
                                        <div class="font-outfit font-bold text-xs">Carregando dados oficiais de cursos...</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 font-medium pt-2 gap-2">
                        <span id="resultsCount">Mostrando resultados...</span>
                        <div class="flex items-center gap-1.5">
                            <button type="button" id="btnPrevPage" onclick="changePage(-1)" class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed font-bold text-slate-700">Anterior</button>
                            <span id="pageIndicator" class="px-2 font-bold text-slate-700">Pág. 1</span>
                            <button type="button" id="btnNextPage" onclick="changePage(1)" class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed font-bold text-slate-700">Próxima</button>
                        </div>
                    </div>

                </div>

            </section>
        </div>
    </main>

    <!-- LOGICA JS DO GUIA DE CONCORRÊNCIA E ACERTOS -->
    <script>
        // ESTIMATIVA DE FAIXAS TRI BASEADA EM MICRODADOS DO INEP (MÉDIA DE OSCILAÇÃO)
        const TRI_CALIBRATION = {
            matematica: (hits) => {
                if (hits <= 5)  return { min: 340, max: 420 };
                if (hits <= 10) return { min: 420, max: 510 };
                if (hits <= 15) return { min: 500, max: 590 };
                if (hits <= 20) return { min: 570, max: 660 };
                if (hits <= 25) return { min: 630, max: 730 };
                if (hits <= 30) return { min: 700, max: 800 };
                if (hits <= 35) return { min: 770, max: 870 };
                if (hits <= 40) return { min: 840, max: 940 };
                return { min: 910, max: 985 };
            },
            natureza: (hits) => {
                if (hits <= 5)  return { min: 330, max: 400 };
                if (hits <= 10) return { min: 400, max: 470 };
                if (hits <= 15) return { min: 460, max: 530 };
                if (hits <= 20) return { min: 520, max: 590 };
                if (hits <= 25) return { min: 580, max: 650 };
                if (hits <= 30) return { min: 640, max: 710 };
                if (hits <= 35) return { min: 700, max: 770 };
                if (hits <= 40) return { min: 760, max: 820 };
                return { min: 810, max: 865 };
            },
            humanas: (hits) => {
                if (hits <= 5)  return { min: 350, max: 420 };
                if (hits <= 10) return { min: 430, max: 490 };
                if (hits <= 15) return { min: 490, max: 550 };
                if (hits <= 20) return { min: 540, max: 610 };
                if (hits <= 25) return { min: 600, max: 660 };
                if (hits <= 30) return { min: 640, max: 710 };
                if (hits <= 35) return { min: 690, max: 750 };
                if (hits <= 40) return { min: 740, max: 800 };
                return { min: 790, max: 840 };
            },
            linguagens: (hits) => {
                if (hits <= 5)  return { min: 340, max: 410 };
                if (hits <= 10) return { min: 420, max: 480 };
                if (hits <= 15) return { min: 480, max: 540 };
                if (hits <= 20) return { min: 530, max: 590 };
                if (hits <= 25) return { min: 580, max: 640 };
                if (hits <= 30) return { min: 620, max: 680 };
                if (hits <= 35) return { min: 660, max: 720 };
                if (hits <= 40) return { min: 710, max: 770 };
                return { min: 750, max: 810 };
            }
        };

        // PRESETS DE METAS DE ACERTOS
        const HIT_PRESETS = {
            'medicina':  { mat: 40, nat: 38, hum: 39, ling: 37, redacao: 960, label: 'Altíssima Concorrência (Medicina, Direito USP, ITA)' },
            'alta':      { mat: 36, nat: 33, hum: 35, ling: 34, redacao: 920, label: 'Alta Concorrência (Computação, Odonto, Engenharias)' },
            'media':     { mat: 28, nat: 26, hum: 29, ling: 28, redacao: 800, label: 'Média Concorrência (Adm, Enfermagem, Psicologia)' },
            'acessivel': { mat: 20, nat: 19, hum: 22, ling: 21, redacao: 720, label: 'Acesso Amplo (Licenciaturas, Pedagogia)' },
            'custom':    null
        };

        let userHits = {
            matematica: 36,
            natureza: 33,
            humanas: 35,
            linguagens: 34
        };
        let userRedacao = 920;

        let currentQuotaMode = 'ampla';
        let allCourses = [];
        let filteredCourses = [];
        let currentPage = 1;
        const pageSize = 12;

        function syncHits(area, val) {
            val = Math.max(0, Math.min(45, Number(val) || 0));
            userHits[area] = val;

            const inputEl = document.getElementById(`hits_${area}`);
            const sliderEl = document.getElementById(`slider_${area}`);
            if (inputEl && inputEl.value != val) inputEl.value = val;
            if (sliderEl && sliderEl.value != val) sliderEl.value = val;

            const pctEl = document.getElementById(`pct_${area}`);
            if (pctEl) pctEl.textContent = `${Math.round((val / 45) * 100)}%`;

            const triRange = TRI_CALIBRATION[area](val);
            const triEl = document.getElementById(`tri_${area}`);
            if (triEl) triEl.textContent = `TRI Estimada: ~${triRange.min} - ${triRange.max} pts`;

            recalculateThermometer();
        }

        function syncRedacao(val) {
            userRedacao = Number(val) || 800;
            recalculateThermometer();
        }

        function applyHitPreset(presetKey, btn) {
            document.querySelectorAll('.preset-btn').forEach(b => {
                b.className = 'preset-btn flex flex-col items-center justify-center p-3 rounded-2xl border-2 border-slate-200 bg-white text-slate-700 font-extrabold text-xs transition-all hover:border-indigo-300';
            });
            if (btn) {
                btn.className = 'preset-btn selected flex flex-col items-center justify-center p-3 rounded-2xl border-2 border-indigo-600 bg-indigo-50/80 text-indigo-900 font-extrabold text-xs shadow-sm transition-all hover:border-indigo-600';
            }

            const badge = document.getElementById('activePresetBadge');
            if (presetKey !== 'custom' && HIT_PRESETS[presetKey]) {
                const p = HIT_PRESETS[presetKey];
                syncHits('matematica', p.mat);
                syncHits('natureza', p.nat);
                syncHits('humanas', p.hum);
                syncHits('linguagens', p.ling);
                
                const selRed = document.getElementById('score_redacao');
                if (selRed) selRed.value = p.redacao;
                syncRedacao(p.redacao);

                if (badge) badge.textContent = `Meta: ${p.label}`;
            } else {
                if (badge) badge.textContent = 'Modo Meus Acertos (Edição Livre)';
            }
        }

        function resetToCustom() {
            applyHitPreset('alta', document.querySelectorAll('.preset-btn')[1]);
        }

        // ESTIMAR NOTA PONDERADA MÉDIA COM BASE NOS ACERTOS
        function estimateScoreRange() {
            const triMat = TRI_CALIBRATION.matematica(userHits.matematica);
            const triNat = TRI_CALIBRATION.natureza(userHits.natureza);
            const triHum = TRI_CALIBRATION.humanas(userHits.humanas);
            const triLing = TRI_CALIBRATION.linguagens(userHits.linguagens);

            const minSum = triMat.min + triNat.min + triHum.min + triLing.min + userRedacao;
            const maxSum = triMat.max + triNat.max + triHum.max + triLing.max + userRedacao;

            return {
                min: minSum / 5,
                max: maxSum / 5,
                mid: (minSum + maxSum) / 10
            };
        }

        function recalculateThermometer() {
            const totalHits = userHits.matematica + userHits.natureza + userHits.humanas + userHits.linguagens;
            const hitRate = (totalHits / 180) * 100;
            const scoreRange = estimateScoreRange();

            document.getElementById('totalHitsDisplay').textContent = totalHits;
            document.getElementById('globalHitRate').textContent = `${hitRate.toFixed(1)}% da prova`;
            document.getElementById('triRangeEstimate').textContent = `~${Math.round(scoreRange.min)} a ${Math.round(scoreRange.max)} pts`;

            // Atualizar barra de termômetro e diagnóstico
            const bar = document.getElementById('thermometerBar');
            const tierEl = document.getElementById('tierBadge');
            const insightEl = document.getElementById('tierInsightText');

            bar.style.width = `${Math.min(100, Math.max(10, hitRate))}%`;

            if (totalHits >= 148 && userRedacao >= 920) {
                tierEl.textContent = '🏆 Altíssima Concorrência (Top 1% - Medicina / Direito SanFran)';
                tierEl.className = 'font-black px-3 py-1 rounded-xl text-xs bg-emerald-500/20 text-emerald-300 border border-emerald-500/30';
                insightEl.innerHTML = 'Volume extraordinário! Seus simulados sustentam margem competitiva para <strong>Medicina, Engenharia Aeronáutica e Direito nas federais mais concorridas do Brasil</strong>.';
            } else if (totalHits >= 126 && userRedacao >= 840) {
                tierEl.textContent = '⭐ Alta Concorrência (Top 5% - Computação, Odonto, Engenharias)';
                tierEl.className = 'font-black px-3 py-1 rounded-xl text-xs bg-indigo-500/20 text-indigo-300 border border-indigo-500/30';
                insightEl.innerHTML = 'Consistência excelente! Seus acertos atuais dão segurança para <strong>Ciência da Computação, Odontologia, Engenharias e Psicologia</strong> na ampla concorrência da imensa maioria das universidades públicas.';
            } else if (totalHits >= 98 && userRedacao >= 760) {
                tierEl.textContent = '📈 Média Concorrência (Top 20% - Adm, Enfermagem, Arquitetura)';
                tierEl.className = 'font-black px-3 py-1 rounded-xl text-xs bg-sky-500/20 text-sky-300 border border-sky-500/30';
                insightEl.innerHTML = 'Base sólida! Você atinge margem competitiva para <strong>Administração, Economia, Enfermagem, Farmácia e Arquitetura</strong> na ampla concorrência.';
            } else if (totalHits >= 72) {
                tierEl.textContent = '🎯 Acesso Amplo / Base (Licenciaturas, Pedagogia, Tecnologia)';
                tierEl.className = 'font-black px-3 py-1 rounded-xl text-xs bg-amber-500/20 text-amber-300 border border-amber-500/30';
                insightEl.innerHTML = 'Bom ponto de partida! Permite disputa em <strong>Licenciaturas, Pedagogia, Gestão Pública</strong> e cursos com vagas reservadas de cota.';
            } else {
                tierEl.textContent = '🌱 Construção de Base';
                tierEl.className = 'font-black px-3 py-1 rounded-xl text-xs bg-rose-500/20 text-rose-300 border border-rose-500/30';
                insightEl.innerHTML = 'Fase de fortalecimento! Concentre seu treino na resolução de questões nível fácil/médio de Matemática e temas semanais de redação.';
            }

            filterCoursesTable();
        }

        // SELOS OFICIAIS MEC (IGC 5 E 4)
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

        // ESTIMAR ACERTOS RECOMENDADOS PARA UM CURSO COM BASE NO CORTE
        function getRecommendedHitsForCutoff(cutoff) {
            if (cutoff >= 780) return { hitsText: '145 a 160+ acertos', dispute: 'Altíssima', badgeClass: 'bg-purple-100 text-purple-800 border-purple-200' };
            if (cutoff >= 720) return { hitsText: '125 a 144 acertos', dispute: 'Alta', badgeClass: 'bg-indigo-100 text-indigo-800 border-indigo-200' };
            if (cutoff >= 640) return { hitsText: '95 a 124 acertos', dispute: 'Média', badgeClass: 'bg-sky-100 text-sky-800 border-sky-200' };
            return { hitsText: '70 a 94 acertos', dispute: 'Acessível', badgeClass: 'bg-amber-100 text-amber-800 border-amber-200' };
        }

        function setQuotaMode(mode, btn) {
            currentQuotaMode = mode;
            const btnAmpla = document.getElementById('btnModeAmpla');
            const btnCota = document.getElementById('btnModeCota');

            if (mode === 'ampla') {
                btnAmpla.className = 'px-3 py-1.5 rounded-xl font-outfit font-extrabold text-xs bg-white text-indigo-900 shadow-sm transition-all';
                btnCota.className = 'px-3 py-1.5 rounded-xl font-outfit font-extrabold text-xs text-slate-600 hover:text-slate-900 transition-all';
            } else {
                btnCota.className = 'px-3 py-1.5 rounded-xl font-outfit font-extrabold text-xs bg-white text-indigo-900 shadow-sm transition-all';
                btnAmpla.className = 'px-3 py-1.5 rounded-xl font-outfit font-extrabold text-xs text-slate-600 hover:text-slate-900 transition-all';
            }

            filterCoursesTable();
        }

        async function loadCourses() {
            try {
                const res = await fetch('api/get_course_guides.php');
                const data = await res.json();
                if (data.success && Array.isArray(data.courses)) {
                    allCourses = data.courses;
                    filterCoursesTable();
                } else {
                    document.getElementById('courseTableBody').innerHTML = '<tr><td colspan="6" class="text-center py-6 text-rose-500 font-bold">Erro ao carregar catálogo.</td></tr>';
                }
            } catch (err) {
                console.error(err);
                document.getElementById('courseTableBody').innerHTML = '<tr><td colspan="6" class="text-center py-6 text-rose-500 font-bold">Erro de conexão ao banco de cursos.</td></tr>';
            }
        }

        function filterCoursesTable() {
            if (!allCourses || allCourses.length === 0) return;

            const scoreRange = estimateScoreRange();
            const search = document.getElementById('courseSearchInput').value.toLowerCase().trim();
            const region = document.getElementById('regionFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;

            filteredCourses = allCourses.filter(c => {
                if (search) {
                    const matchCourse = (c.course_name || '').toLowerCase().includes(search);
                    const matchUni = (c.university_name || '').toLowerCase().includes(search);
                    const matchCity = (c.campus_city || '').toLowerCase().includes(search);
                    if (!matchCourse && !matchUni && !matchCity) return false;
                }

                if (region && c.region !== region) return false;

                const cutoff = currentQuotaMode === 'ampla' ? parseFloat(c.cutoff_score) : parseFloat(c.quota_cutoff_score || c.cutoff_score);

                // Determinar momento frente ao corte
                let status = 'meta';
                if (scoreRange.min >= cutoff) {
                    status = 'seguranca';
                } else if (scoreRange.max >= cutoff) {
                    status = 'disputa';
                }

                if (statusFilter !== 'all' && status !== statusFilter) return false;

                return true;
            });

            currentPage = 1;
            renderTablePage();
        }

        function renderTablePage() {
            const tbody = document.getElementById('courseTableBody');
            const total = filteredCourses.length;
            const scoreRange = estimateScoreRange();

            if (total === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-500">
                            <i class="bi bi-info-circle text-2xl text-slate-400 block mb-1"></i>
                            <span class="font-outfit font-bold text-xs">Nenhum curso encontrado com esses filtros e padrão de acertos.</span>
                        </td>
                    </tr>
                `;
                document.getElementById('resultsCount').textContent = 'Nenhum curso encontrado';
                document.getElementById('pageIndicator').textContent = 'Pág. 0/0';
                document.getElementById('btnPrevPage').disabled = true;
                document.getElementById('btnNextPage').disabled = true;
                return;
            }

            const totalPages = Math.ceil(total / pageSize);
            const startIdx = (currentPage - 1) * pageSize;
            const endIdx = Math.min(startIdx + pageSize, total);
            const pageItems = filteredCourses.slice(startIdx, endIdx);

            let html = '';
            pageItems.forEach(c => {
                const cutoff = currentQuotaMode === 'ampla' ? parseFloat(c.cutoff_score) : parseFloat(c.quota_cutoff_score || c.cutoff_score);
                const rec = getRecommendedHitsForCutoff(cutoff);

                let statusBadge = '';
                if (scoreRange.min >= cutoff) {
                    statusBadge = '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">🟢 Faixa de Segurança</span>';
                } else if (scoreRange.max >= cutoff) {
                    statusBadge = '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[10px] font-extrabold bg-amber-100 text-amber-800 border border-amber-200">🟡 Zona de Disputa</span>';
                } else {
                    statusBadge = '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[10px] font-extrabold bg-slate-100 text-slate-700 border border-slate-200">🔴 Meta de Avanço</span>';
                }

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
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-black border ${rec.badgeClass}">
                                ${rec.dispute}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right font-mono font-black text-slate-900 text-xs">
                            ${cutoff.toFixed(2)}
                        </td>
                        <td class="py-3 px-4 text-center text-xs font-bold text-slate-700 font-mono">
                            ${rec.hitsText}
                        </td>
                        <td class="py-3 px-4 text-center">
                            ${statusBadge}
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
            document.getElementById('resultsCount').textContent = `Mostrando ${startIdx + 1}–${endIdx} de ${total} cursos`;
            document.getElementById('pageIndicator').textContent = `Pág. ${currentPage} de ${totalPages}`;
            document.getElementById('btnPrevPage').disabled = (currentPage === 1);
            document.getElementById('btnNextPage').disabled = (currentPage === totalPages);
        }

        function changePage(delta) {
            const totalPages = Math.ceil(filteredCourses.length / pageSize);
            currentPage += delta;
            if (currentPage < 1) currentPage = 1;
            if (currentPage > totalPages) currentPage = totalPages;
            renderTablePage();
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
            syncHits('matematica', 36);
            syncHits('natureza', 33);
            syncHits('humanas', 35);
            syncHits('linguagens', 34);
            loadCourses();
        });
    </script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
