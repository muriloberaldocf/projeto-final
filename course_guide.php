<?php
require_once __DIR__ . '/config/db.php';
checkAuth();

$pageTitle = 'Guia de Cursos & Notas de Corte — HipoGabarito';
require_once __DIR__ . '/includes/header.php';
?>

    <!-- LAYOUT PRINCIPAL DA GUIA DE CURSOS -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- SIDEBAR MODULAR COM DESTAQUE EM ROXO -->
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

            <!-- CONTEÚDO PRINCIPAL: PESQUISA DIRETA GOOGLE COM SELEÇÃO DE INTENÇÃO -->
            <section class="lg:col-span-9 space-y-6">
                <div class="bg-white rounded-3xl border-2 border-slate-200 p-6 sm:p-8 shadow-[0_4px_0_0_#e2e8f0]">
                    
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

    <script>
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
            
            // Atualizar classes visuais dos botões
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
    </script>
    <?php require_once __DIR__ . '/includes/footer.php'; ?>
