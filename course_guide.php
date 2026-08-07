<?php
require_once __DIR__ . '/config/db.php';
checkAuth();

$userId = $_SESSION['user_id'];
$stmtUser = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmtUser->execute([$userId]);
$user = $stmtUser->fetch();
$userAvatar = !empty($user['avatar']) ? $user['avatar'] : 'assets/img/default_avatar.jpg';
$userFrame = !empty($user['avatar_frame']) ? $user['avatar_frame'] : 'frame-indigo';

$userBadgeMap = [
    'bi-person-circle' => 'Estudante Padrão',
    'bi-backpack' => 'Mochileiro Focado',
    'bi-mortarboard' => 'Formando Vestibulando',
    'bi-rocket-takeoff' => 'Foguete da Aprovação',
    'bi-lightning-charge' => 'Mago do Conhecimento',
    'bi-award' => 'Campeão de Simulados',
    'bi-gem' => 'Diamante Medicina',
    'bi-incognito' => 'Mestre Misterioso',
    'bi-crown' => 'Rei da Aprovação'
];
$userBadgeIcon = $user['avatar_icon'] ?? 'bi-person-circle';
$userBadgeName = $userBadgeMap[$userBadgeIcon] ?? 'Estudante Padrão';
?>
<!DOCTYPE html>
<html lang="pt-BR" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesquisa Direta de Cursos & Vestibulares — HipoGabarito</title>

    <!-- TAILWIND CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- GOOGLE FONTS & BOOTSTRAP ICONS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/main.css?v=<?= time() ?>">
</head>
<body class="min-h-full font-sans antialiased text-slate-800 bg-slate-50 selection:bg-indigo-500 selection:text-white">

    <!-- TOP HEADER / HUD DE JOGADOR TAILWIND -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b-2 border-slate-200 py-3 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <a href="dashboard.php" class="flex items-center gap-2 group text-decoration-none">
                <img src="assets/img/hipogabarito_logo.png" alt="HipoGabarito Logo" class="h-9 sm:h-10 w-auto object-contain group-hover:scale-105 transition-transform">
            </a>

            <!-- HUD STATUS DO ALUNO -->
            <div class="flex items-center gap-3">
                <!-- SININHO DE NOTIFICAÇÕES NA NAVBAR -->
                <div class="relative">
                    <button id="notifBellBtn" onclick="toggleNotifDropdown()" class="relative p-2 rounded-2xl bg-white border-2 border-slate-200 shadow-[0_2px_0_0_#e2e8f0] hover:bg-slate-50 text-slate-600 hover:text-indigo-600 transition-all flex items-center justify-center" title="Notificações">
                        <i class="bi bi-bell-fill text-base"></i>
                        <span id="notifBadge" class="hidden absolute -top-1.5 -right-1.5 bg-rose-500 text-white text-[10px] font-black w-4.5 h-4.5 px-1 rounded-full flex items-center justify-center shadow-sm animate-pulse">0</span>
                    </button>

                    <!-- DROPDOWN DE NOTIFICAÇÕES -->
                    <div id="notifDropdown" class="hidden absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-3xl border-2 border-slate-200 shadow-2xl z-50 p-4 animate-in fade-in zoom-in-95 duration-150">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                            <div class="flex items-center gap-2">
                                <i class="bi bi-bell-fill text-indigo-600"></i>
                                <h4 class="font-outfit font-extrabold text-slate-900 text-sm mb-0">Central de Notificações</h4>
                            </div>
                            <span id="notifCountText" class="text-[11px] font-bold text-slate-400">0 notificações</span>
                        </div>

                        <div id="notifListContainer" class="space-y-2.5 max-h-80 overflow-y-auto pr-1">
                            <div class="text-center py-6 text-slate-400 text-xs">
                                <i class="bi bi-arrow-repeat animate-spin text-xl block mb-1"></i> Carregando...
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 bg-white px-3.5 py-1.5 rounded-2xl border-2 border-slate-200 shadow-[0_3px_0_0_#e2e8f0]" title="Ofensiva Diária">
                    <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-1.048c-2.5 1.6-4.5 4.5-4.5 7.5 0 .285.021.564.062.836A4.99 4.99 0 014 6.5a1 1 0 00-1.92.4C2.5 9.5 4.5 12 7 12c.3 0 .59-.03.873-.087.6.93 1.556 1.6 2.685 1.776A5.002 5.002 0 0015 9c0-3.5-1.5-5.5-2.605-6.447z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-outfit font-extrabold text-slate-700 text-sm"><?= htmlspecialchars($user['streak_days'] ?? 1) ?>d</span>
                </div>

                <div class="flex items-center gap-2 bg-white px-3.5 py-1.5 rounded-2xl border-2 border-slate-200 shadow-[0_3px_0_0_#e2e8f0]" title="XP Acumulado">
                    <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-outfit font-extrabold text-indigo-600 text-sm"><?= htmlspecialchars($user['xp'] ?? 0) ?> XP</span>
                </div>

                <a href="profile.php" class="ml-1 group" title="Meu Perfil">
                    <img src="<?= htmlspecialchars($userAvatar) ?>" alt="Avatar" onerror="this.onerror=null;this.src='assets/img/default_avatar.jpg'" class="w-10 h-10 rounded-full <?= htmlspecialchars($userFrame) ?> object-cover group-hover:scale-105 transition-transform shadow-sm">
                </a>
            </div>
        </div>
    </header>

    <!-- LAYOUT PRINCIPAL -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- SIDEBAR ESQUERDA -->
            <aside class="lg:col-span-3 sticky top-20 space-y-3.5">
                <div class="bg-white rounded-3xl border-2 border-slate-200 p-5 shadow-[0_4px_0_0_#e2e8f0]">
                    <div class="flex items-center gap-3.5 p-3 mb-4 bg-slate-50 rounded-2xl border border-slate-200">
                        <img src="<?= htmlspecialchars($userAvatar) ?>" onerror="this.onerror=null;this.src='assets/img/default_avatar.jpg'" class="w-12 h-12 rounded-full <?= htmlspecialchars($userFrame) ?> object-cover shadow-sm">
                        <div class="min-w-0 flex-1">
                            <h3 class="font-outfit font-bold text-slate-900 text-base truncate"><?= htmlspecialchars($user['name'] ?? 'Estudante') ?></h3>
                            <div class="flex items-center gap-1 text-xs font-bold text-indigo-600 truncate mt-0.5" title="Título Equipado">
                                <i class="bi <?= htmlspecialchars($userBadgeIcon) ?>"></i>
                                <span class="truncate"><?= htmlspecialchars($userBadgeName) ?></span>
                            </div>
                        </div>
                    </div>

                    <nav class="space-y-2">
                        <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-outfit font-bold text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                            Trilha de Estudos
                        </a>

                        <a href="leaderboard.php" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-outfit font-bold text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-all">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4m6 17v-5m0 0a2 2 0 100-4 2 2 0 000 4zm0 5a2 2 0 100-4 2 2 0 000 4zM6 17v-3m0 0a2 2 0 100-4 2 2 0 000 4zm0 3a2 2 0 100-4 2 2 0 000 4zM18 17v-3m0 0a2 2 0 100-4 2 2 0 000 4zm0 3a2 2 0 100-4 2 2 0 000 4z"/>
                            </svg>
                            Ranking Arcade
                        </a>

                        <a href="profile.php" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-outfit font-bold text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-all">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Meu Perfil
                        </a>

                        <div class="pt-3 border-t border-slate-200 mt-3">
                            <a href="api/auth.php?action=logout" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl font-outfit font-bold text-xs text-slate-400 hover:text-rose-600 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Sair
                            </a>
                        </div>
                    </nav>
                </div>
            </aside>

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
    <script src="assets/js/notifications.js"></script>
</body>
</html>
