<?php
require_once __DIR__ . '/config/db.php';
checkAuth();

$pageTitle = 'Ranking & Mural de Amigos — HipoGabarito';
require_once __DIR__ . '/includes/header.php';
?>

    <!-- LAYOUT PRINCIPAL DO RANKING & MURAL -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- SIDEBAR MODULAR COM DESTAQUE EM ROXO -->
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

            <!-- CONTEÚDO PRINCIPAL: RANKING E MURAL SOCIAL -->
            <section class="lg:col-span-9 space-y-6">
                
                <!-- BANNER DE RANKING E BOTÃO DE ADICIONAR AMIGOS -->
                <div class="bg-gradient-to-r from-amber-500 via-amber-600 to-[#6842C2] text-white rounded-3xl p-6 shadow-lg border-2 border-amber-400 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 relative overflow-hidden">
                    <div class="z-10">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold bg-white/20 text-amber-100 border border-white/30 uppercase tracking-wider mb-2">
                            <i class="bi bi-people-fill"></i> COMUNIDADE & REDE DE AMIGOS
                        </span>
                        <h2 class="font-outfit font-extrabold text-2xl sm:text-3xl mb-1">Amigos & Conquistas</h2>
                        <p class="text-xs sm:text-sm text-amber-100 font-medium mb-0">Comemore as conquistas dos seus amigos, mande um "Boa! 👊" e dispute o topo do ranking de XP.</p>
                    </div>
                    
                    <div class="flex items-center gap-3 z-10 shrink-0">
                        <button onclick="openAddFriendModal()" class="bg-white text-[#6842C2] hover:bg-amber-50 font-outfit font-black px-5 py-3 rounded-2xl shadow-md transition-all flex items-center gap-2 text-sm whitespace-nowrap group relative">
                            <i class="bi bi-person-plus-fill text-amber-500 text-lg group-hover:scale-110 transition-transform"></i>
                            <span>+ Adicionar Amigos</span>
                            <span id="pendingBadge" class="hidden bg-rose-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full shadow-sm animate-pulse">0</span>
                        </button>
                    </div>
                </div>

                <!-- ABAS PRINCIPAIS: MURAL DE CONQUISTAS / RANKING DE XP -->
                <div class="flex items-center gap-2 bg-[#f1eff5] dark:bg-slate-800/60 p-1.5 rounded-2xl border-2 border-slate-200 dark:border-slate-700 w-fit">
                    <button type="button" onclick="switchMainTab('mural')" id="tabBtnMural" class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-outfit font-extrabold text-sm transition-all bg-[#6842C2] text-white shadow-sm">
                        <i class="bi bi-fire text-amber-300"></i>
                        <span>Mural de Conquistas</span>
                    </button>
                    <button type="button" onclick="switchMainTab('ranking')" id="tabBtnRanking" class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-outfit font-extrabold text-sm transition-all text-slate-600 dark:text-slate-300 hover:text-slate-900">
                        <i class="bi bi-trophy-fill text-amber-500"></i>
                        <span>Liga de Amigos (XP)</span>
                    </button>
                </div>

                <!-- SEÇÃO 1: MURAL DE CONQUISTAS (FEED SOCIAL) -->
                <div id="viewMural" class="space-y-4">
                    <div id="muralFeedContainer" class="space-y-3.5">
                        <div class="text-center py-12 text-slate-400 text-xs">
                            <i class="bi bi-arrow-repeat animate-spin text-2xl mb-2 inline-block"></i>
                            <p class="font-outfit font-bold text-sm">Carregando conquistas dos seus amigos...</p>
                        </div>
                    </div>
                </div>

                <!-- SEÇÃO 2: TABELA DE RANKING (LIGA DE XP) -->
                <div id="viewRanking" class="hidden space-y-4">
                    <div class="bg-white rounded-3xl border-2 border-slate-200 shadow-[0_4px_0_0_#e2e8f0] overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 border-b-2 border-slate-200 font-outfit text-xs font-extrabold text-slate-500 uppercase tracking-wider">
                                        <th class="py-4 px-6">Posição</th>
                                        <th class="py-4 px-6">Estudante</th>
                                        <th class="py-4 px-6">Nível</th>
                                        <th class="py-4 px-6">Ofensiva</th>
                                        <th class="py-4 px-6 text-right">XP Total</th>
                                        <th class="py-4 px-6 text-center">Opções</th>
                                    </tr>
                                </thead>
                                <tbody id="rankTableBody" class="divide-y divide-slate-100">
                                    <!-- Preenchido via JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </section>
        </div>
    </main>

    <!-- MODAL DE ADICIONAR E GERENCIAR AMIGOS -->
    <div id="addFriendModal" onclick="if(event.target === this) closeAddFriendModal()" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl border-2 border-slate-200 max-w-lg w-full p-6 shadow-2xl relative animate-in fade-in zoom-in-95 duration-200">
            
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-100 text-[#6842C2] flex items-center justify-center text-xl">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>
                    <div>
                        <h3 class="font-outfit font-extrabold text-slate-900 text-lg leading-tight">Amigos & Solicitações</h3>
                        <p class="text-xs text-slate-500">Conecte-se com colegas para acompanhar conquistas e comparar XP</p>
                    </div>
                </div>
                <button type="button" onclick="closeAddFriendModal()" class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-all flex items-center justify-center">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>

            <!-- SEÇÃO DE SOLICITAÇÕES PENDENTES RECEBIDAS -->
            <div id="pendingRequestsSection" class="mb-4 hidden">
                <h4 class="font-outfit font-extrabold text-xs text-amber-600 uppercase tracking-wider mb-2 flex items-center gap-1">
                    <i class="bi bi-bell-fill"></i> Solicitações de Amizade Recebidas
                </h4>
                <div id="pendingRequestsList" class="space-y-2 max-h-40 overflow-y-auto pr-1">
                    <!-- Preenchido via JS -->
                </div>
                <div class="my-3 border-b border-slate-100"></div>
            </div>

            <!-- CAMPO DE BUSCA -->
            <div class="relative mb-3">
                <i class="bi bi-search absolute left-4 top-3.5 text-slate-400 text-base"></i>
                <input type="text" id="friendSearchInput" oninput="searchUsers()" placeholder="Digite o nome ou e-mail do seu colega..." class="w-full bg-slate-50 border-2 border-slate-200 rounded-2xl pl-11 pr-4 py-2.5 text-sm font-medium text-slate-800 focus:outline-none focus:border-[#6842C2] transition">
            </div>

            <!-- SUGESTÕES RÁPIDAS DE ESTUDANTES -->
            <div id="suggestionsSection" class="mb-3">
                <h5 class="font-outfit font-bold text-xs text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                    <i class="bi bi-stars text-amber-500"></i> Sugestões de Colegas para Estudar
                </h5>
                <div id="suggestionsList" class="space-y-2 max-h-48 overflow-y-auto pr-1">
                    <div class="text-center py-4 text-slate-400 text-xs">Carregando sugestões...</div>
                </div>
            </div>

            <!-- RESULTADOS DA BUSCA -->
            <div id="searchResultsContainer" class="hidden max-h-60 overflow-y-auto space-y-2 pr-1 mb-3"></div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex justify-end">
                <button onclick="closeAddFriendModal()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-5 py-2.5 rounded-xl text-xs transition">
                    Fechar
                </button>
            </div>

        </div>
    </div>

    <!-- MODAL DE MINI-PERFIL DO AMIGO (VER CONQUISTAS) -->
    <div id="friendProfileModal" onclick="if(event.target === this) closeFriendProfileModal()" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl border-2 border-slate-200 max-w-md w-full p-6 shadow-2xl relative animate-in fade-in zoom-in-95 duration-200">
            <button type="button" onclick="closeFriendProfileModal()" class="absolute top-4 right-4 p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-all flex items-center justify-center">
                <i class="bi bi-x-lg text-lg"></i>
            </button>
            
            <div class="text-center pb-4 border-b border-slate-100">
                <img id="fpAvatar" src="assets/img/default_avatar.jpg" class="w-20 h-20 rounded-full mx-auto object-cover mb-2 border-2 border-slate-200 shadow-md">
                <h3 id="fpName" class="font-outfit font-black text-xl text-slate-900 mb-0.5">Estudante</h3>
                <span id="fpTitle" class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-xl text-xs font-extrabold bg-[#f1eff5] text-[#6842C2] border border-indigo-200 mb-2">
                    Estudante Padrão
                </span>
                <div class="flex items-center justify-center gap-2">
                    <span id="fpLevel" class="px-2.5 py-0.5 rounded-xl text-xs font-extrabold bg-slate-100 text-slate-700 border border-slate-200">Nível 1</span>
                    <span id="fpXp" class="px-2.5 py-0.5 rounded-xl text-xs font-extrabold bg-indigo-50 text-[#6842C2] border border-indigo-200">0 XP</span>
                    <span id="fpStreak" class="px-2.5 py-0.5 rounded-xl text-xs font-extrabold bg-amber-50 text-amber-700 border border-amber-200 flex items-center gap-1">
                        <i class="bi bi-fire text-amber-500"></i> 0d
                    </span>
                </div>
            </div>

            <div class="mt-4">
                <h4 class="font-outfit font-extrabold text-xs text-slate-500 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                    <i class="bi bi-award-fill text-amber-500"></i> Conquistas Desbloqueadas (<span id="fpAchCount">0</span>)
                </h4>
                <div id="fpAchievementsList" class="space-y-2 max-h-56 overflow-y-auto pr-1">
                    <!-- Preenchido via JS -->
                </div>
            </div>

            <div class="mt-5 pt-3 border-t border-slate-100 flex justify-end">
                <button onclick="closeFriendProfileModal()" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 rounded-xl text-xs transition">
                    Fechar Perfil
                </button>
            </div>
        </div>
    </div>

    <script src="assets/js/sound_effects.js"></script>
    <script>
        const BADGE_MAP = {
            'bi-person-circle': 'Estudante Padrão',
            'bi-backpack': 'Mochileiro Focado',
            'bi-mortarboard': 'Formando Vestibulando',
            'bi-rocket-takeoff': 'Foguete da Aprovação',
            'bi-lightning-charge': 'Mago do Conhecimento',
            'bi-award': 'Campeão de Simulados',
            'bi-gem': 'Diamante Medicina',
            'bi-incognito': 'Mestre Misterioso',
            'bi-crown': 'Rei da Aprovação',
            'bi-emoji-smile-fill': 'Estudante Lendário'
        };

        let currentMainTab = 'mural';

        document.addEventListener('DOMContentLoaded', () => {
            loadMuralFeed();
            loadLeaderboard();
            loadPendingRequests();
        });

        // ALTERNAR ABAS: MURAL / RANKING
        function switchMainTab(tab) {
            currentMainTab = tab;
            const btnMural = document.getElementById('tabBtnMural');
            const btnRanking = document.getElementById('tabBtnRanking');
            const viewMural = document.getElementById('viewMural');
            const viewRanking = document.getElementById('viewRanking');

            if (tab === 'mural') {
                btnMural.className = 'flex items-center gap-2 px-5 py-2.5 rounded-xl font-outfit font-extrabold text-sm transition-all bg-[#6842C2] text-white shadow-sm';
                btnRanking.className = 'flex items-center gap-2 px-5 py-2.5 rounded-xl font-outfit font-extrabold text-sm transition-all text-slate-600 dark:text-slate-300 hover:text-slate-900';
                viewMural.classList.remove('hidden');
                viewRanking.classList.add('hidden');
                loadMuralFeed();
            } else {
                btnRanking.className = 'flex items-center gap-2 px-5 py-2.5 rounded-xl font-outfit font-extrabold text-sm transition-all bg-[#6842C2] text-white shadow-sm';
                btnMural.className = 'flex items-center gap-2 px-5 py-2.5 rounded-xl font-outfit font-extrabold text-sm transition-all text-slate-600 dark:text-slate-300 hover:text-slate-900';
                viewRanking.classList.remove('hidden');
                viewMural.classList.add('hidden');
                loadLeaderboard();
            }
        }

        // CARREGAR FEED DO MURAL DE CONQUISTAS
        async function loadMuralFeed() {
            try {
                const res = await fetch('api/friends.php?action=mural_feed');
                const data = await res.json();
                const container = document.getElementById('muralFeedContainer');

                if (!data.success || !data.events || data.events.length === 0) {
                    container.innerHTML = `
                        <div class="bg-white rounded-3xl border-2 border-slate-200 p-8 text-center text-slate-400">
                            <i class="bi bi-stars text-4xl text-amber-400 mb-2 inline-block"></i>
                            <h4 class="font-outfit font-black text-slate-800 text-base mb-1">O mural de conquistas está tranquilo!</h4>
                            <p class="text-xs text-slate-500 max-w-md mx-auto mb-4">Complete uma lição perfeita de Física, emplace uma sequência de 20 acertos ou adicione mais amigos para ver os feitos deles aqui!</p>
                            <button onclick="openAddFriendModal()" class="px-4 py-2 rounded-2xl font-outfit font-black text-xs bg-[#6842C2] text-white shadow-[0_3px_0_0_#3D256B] hover:bg-[#5832b2] transition">
                                + Convidar Amigos
                            </button>
                        </div>
                    `;
                    return;
                }

                container.innerHTML = '';
                data.events.forEach(ev => {
                    const card = document.createElement('div');
                    card.className = "bg-white rounded-3xl border-2 border-slate-200 p-4 sm:p-5 shadow-[0_3px_0_0_#e2e8f0] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 transition hover:border-indigo-300";

                    const userPhoto = (ev.avatar && ev.avatar.trim() !== '') ? ev.avatar : 'assets/img/default_avatar.jpg';
                    const userFrame = ev.avatar_frame || 'frame-indigo';
                    const hasSentBoa = !!ev.user_has_sent_boa;
                    const boaBtnClass = hasSentBoa 
                        ? 'bg-amber-100 text-amber-800 border-amber-300 shadow-[0_2px_0_0_#f59e0b]' 
                        : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-amber-50 hover:border-amber-300 shadow-[0_2px_0_0_#e2e8f0]';

                    // Cor do badge da conquista
                    const themeClass = ev.theme_color === 'cyan' ? 'bg-cyan-50 text-cyan-700 border-cyan-200'
                        : (ev.theme_color === 'amber' ? 'bg-amber-50 text-amber-700 border-amber-200'
                        : (ev.theme_color === 'rose' ? 'bg-rose-50 text-rose-700 border-rose-200'
                        : (ev.theme_color === 'emerald' ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                        : 'bg-indigo-50 text-[#6842C2] border-indigo-200')));

                    card.innerHTML = `
                        <div class="flex items-start gap-3.5 min-w-0">
                            <div class="cursor-pointer shrink-0" onclick="openFriendProfileModal(${ev.user_id})" title="Ver perfil de ${ev.user_name}">
                                <img src="${userPhoto}" onerror="this.onerror=null;this.src='assets/img/default_avatar.jpg'" class="w-12 h-12 rounded-full ${userFrame} object-cover shadow-sm hover:scale-105 transition-transform">
                            </div>
                            <div class="min-w-0 space-y-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-outfit font-extrabold text-slate-900 text-sm hover:text-[#6842C2] cursor-pointer" onclick="openFriendProfileModal(${ev.user_id})">
                                        ${ev.user_name}
                                    </span>
                                    ${ev.is_me ? '<span class="text-[9px] px-2 py-0.5 rounded-full bg-[#6842C2] text-white font-extrabold">VOCÊ</span>' : ''}
                                    <span class="text-[10px] text-slate-400 font-semibold">• ${ev.time_ago}</span>
                                </div>
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-xl text-xs font-black border ${themeClass}">
                                    <i class="bi ${ev.icon || 'bi-trophy-fill'} text-xs"></i>
                                    <span>${ev.title}</span>
                                </div>
                                <p class="text-xs text-slate-600 dark:text-slate-300 font-medium leading-relaxed mb-0">
                                    ${ev.description}
                                </p>
                            </div>
                        </div>

                        <div class="w-full sm:w-auto self-stretch sm:self-center shrink-0">
                            <button onclick="sendBoa(${ev.event_id}, this)" class="w-full sm:w-auto justify-center px-4 py-2.5 sm:py-2 rounded-2xl font-outfit font-black text-xs border-2 transition-all flex items-center gap-2 ${boaBtnClass}" title="Mandar um Boa!">
                                <span class="text-base">${hasSentBoa ? '🔥' : '👊'}</span>
                                <span>${hasSentBoa ? 'Boa! Enviado' : 'Mandar um Boa!'}</span>
                                <span class="px-1.5 py-0.2 rounded-lg bg-white/60 dark:bg-black/20 text-[11px] font-black">${ev.kudos_count || 0}</span>
                            </button>
                        </div>
                    `;
                    container.appendChild(card);
                });
            } catch (err) {
                console.error('Erro ao carregar mural:', err);
            }
        }

        // ENVIAR REAÇÃO "BOA! 👊"
        async function sendBoa(eventId, btn) {
            if (typeof sounds !== 'undefined') sounds.playClick();
            const formData = new FormData();
            formData.append('action', 'send_boa');
            formData.append('event_id', eventId);

            try {
                const res = await fetch('api/friends.php', { method: 'POST', body: formData });
                const data = await res.json();
                if (data.success) {
                    loadMuralFeed();
                }
            } catch (err) {
                console.error(err);
            }
        }

        // CARREGAR TABELA DE RANKING (LIGA DE AMIGOS)
        function loadLeaderboard() {
            fetch('api/get_leaderboard.php')
                .then(res => res.json())
                .then(data => {
                    if (!data.success) return;

                    const tbody = document.getElementById('rankTableBody');
                    tbody.innerHTML = '';

                    if (data.rankings.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    <i class="bi bi-people text-4xl mb-2 inline-block"></i>
                                    <p class="font-bold text-sm text-slate-600">Você ainda não possui amigos no ranking!</p>
                                    <p class="text-xs text-slate-400 mb-4">Clique no botão "+ Adicionar Amigos" acima para pesquisar e enviar solicitações.</p>
                                </td>
                            </tr>
                        `;
                        return;
                    }

                    data.rankings.forEach((r, idx) => {
                        const pos = idx + 1;
                        let posBadge = `<span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-extrabold bg-slate-100 text-slate-600 border border-slate-200">${pos}º</span>`;

                        if (pos === 1) {
                            posBadge = `<span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-extrabold bg-amber-100 text-amber-800 border border-amber-300 shadow-sm">🥇 1º</span>`;
                        } else if (pos === 2) {
                            posBadge = `<span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-extrabold bg-slate-200 text-slate-800 border border-slate-300 shadow-sm">🥈 2º</span>`;
                        } else if (pos === 3) {
                            posBadge = `<span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-extrabold bg-amber-700/10 text-amber-900 border border-amber-700/20 shadow-sm">🥉 3º</span>`;
                        }

                        const isMe = r.is_me;
                        const rowClass = isMe ? 'rank-row-me bg-indigo-50/70 hover:bg-indigo-50 font-bold' : 'rank-row hover:bg-slate-50/80';
                        const userPhoto = (r.avatar && r.avatar.trim() !== '') ? r.avatar : 'assets/img/default_avatar.jpg';
                        const userFrame = r.avatar_frame || 'frame-indigo';

                        const badgeIcon = r.avatar_icon || 'bi-person-circle';
                        const badgeName = BADGE_MAP[badgeIcon] || 'Estudante Padrão';
                        const isStreakActive = !!r.is_streak_active;

                        const actionBtn = isMe ? `
                            <span class="text-[11px] font-extrabold text-[#6842C2] bg-indigo-50 border border-indigo-200 px-3 py-1 rounded-xl">Você</span>
                        ` : `
                            <div class="flex items-center justify-center gap-1.5">
                                <button onclick="openFriendProfileModal(${r.id})" class="text-indigo-600 hover:text-indigo-800 text-xs px-2.5 py-1 rounded-xl border border-indigo-200 hover:bg-indigo-50 font-bold transition inline-flex items-center gap-1" title="Ver Perfil">
                                    <i class="bi bi-eye"></i> Perfil
                                </button>
                                <button onclick="removeFriend(${r.id})" class="text-slate-500 hover:text-rose-600 text-xs px-2 py-1 rounded-xl border border-slate-200 hover:border-rose-300 hover:bg-rose-50 font-bold transition inline-flex items-center" title="Remover Amizade">
                                    <i class="bi bi-trash text-rose-500"></i>
                                </button>
                            </div>
                        `;

                        const tr = document.createElement('tr');
                        tr.className = `transition ${rowClass}`;
                        tr.innerHTML = `
                            <td class="py-4 px-6 font-outfit fw-bold">${posBadge}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <img src="${userPhoto}" onerror="this.onerror=null;this.src='assets/img/default_avatar.jpg'" class="w-10 h-10 rounded-full ${userFrame} object-cover shadow-sm cursor-pointer" onclick="openFriendProfileModal(${r.id})">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="font-outfit font-bold text-slate-900 text-sm truncate hover:text-[#6842C2] cursor-pointer" onclick="openFriendProfileModal(${r.id})">${r.name}</span>
                                            ${isMe ? '<span class="text-[10px] px-2 py-0.5 rounded-full bg-[#6842C2] text-white font-extrabold">VOCÊ</span>' : ''}
                                        </div>
                                        <span class="text-[11px] font-bold text-[#6842C2] flex items-center gap-1">
                                            <i class="bi ${badgeIcon}"></i>
                                            <span>${badgeName}</span>
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-xl text-xs font-extrabold bg-slate-100 text-slate-700 border border-slate-200">NÍVEL ${r.level}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="${isStreakActive ? 'text-amber-500' : 'text-slate-400'} font-extrabold text-xs flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-1.048c-2.5 1.6-4.5 4.5-4.5 7.5 0 .285.021.564.062.836A4.99 4.99 0 014 6.5a1 1 0 00-1.92.4C2.5 9.5 4.5 12 7 12c.3 0 .59-.03.873-.087.6.93 1.556 1.6 2.685 1.776A5.002 5.002 0 0015 9c0-3.5-1.5-5.5-2.605-6.447z" clip-rule="evenodd"/></svg>
                                    ${r.streak_days}d
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right font-outfit font-extrabold text-[#6842C2] text-base">${r.xp} XP</td>
                            <td class="py-4 px-6 text-center">${actionBtn}</td>
                        `;
                        tbody.appendChild(tr);
                    });
                });
        }

        // CARREGAR SUGESTÕES DE AMIGOS
        async function loadFriendSuggestions() {
            try {
                const res = await fetch('api/friends.php?action=suggestions');
                const data = await res.json();
                const list = document.getElementById('suggestionsList');

                if (!data.success || !data.suggestions || data.suggestions.length === 0) {
                    list.innerHTML = `<div class="text-center py-3 text-slate-400 text-xs">Sem novas sugestões no momento. Use o campo acima para buscar!</div>`;
                    return;
                }

                list.innerHTML = '';
                data.suggestions.forEach(u => {
                    const userPhoto = (u.avatar && u.avatar.trim() !== '') ? u.avatar : 'assets/img/default_avatar.jpg';
                    const userFrame = u.avatar_frame || 'frame-indigo';
                    const item = document.createElement('div');
                    item.className = "flex items-center justify-between p-2.5 bg-slate-50 rounded-2xl border border-slate-200 hover:border-indigo-200 transition";
                    item.innerHTML = `
                        <div class="flex items-center gap-2.5 min-w-0">
                            <img src="${userPhoto}" onerror="this.onerror=null;this.src='assets/img/default_avatar.jpg'" class="w-8 h-8 rounded-full ${userFrame} object-cover">
                            <div class="min-w-0">
                                <span class="font-outfit font-bold text-slate-900 text-xs block truncate">${u.name}</span>
                                <span class="text-[10px] text-slate-500">Nível ${u.level} • ${u.xp} XP</span>
                            </div>
                        </div>
                        <button onclick="sendFriendRequest(${u.id})" class="bg-[#6842C2] hover:bg-[#5832b2] text-white font-extrabold text-[11px] px-3 py-1.5 rounded-xl shadow-sm transition flex items-center gap-1 shrink-0">
                            <i class="bi bi-person-plus"></i> Adicionar
                        </button>
                    `;
                    list.appendChild(item);
                });
            } catch (err) {
                console.error(err);
            }
        }

        // CARREGAR SOLICITAÇÕES PENDENTES
        async function loadPendingRequests() {
            try {
                const res = await fetch('api/friends.php?action=list_pending');
                const data = await res.json();
                
                const pendingBadge = document.getElementById('pendingBadge');
                const section = document.getElementById('pendingRequestsSection');
                const list = document.getElementById('pendingRequestsList');

                if (data.success && data.received && data.received.length > 0) {
                    if (pendingBadge) {
                        pendingBadge.textContent = `${data.received.length}`;
                        pendingBadge.classList.remove('hidden');
                    }
                    if (section && list) {
                        list.innerHTML = '';
                        data.received.forEach(u => {
                            const userPhoto = (u.avatar && u.avatar.trim() !== '') ? u.avatar : 'assets/img/default_avatar.jpg';
                            const userFrame = u.avatar_frame || 'frame-indigo';
                            const item = document.createElement('div');
                            item.className = "flex items-center justify-between p-3 bg-amber-50 rounded-2xl border border-amber-200";
                            item.innerHTML = `
                                <div class="flex items-center gap-2.5">
                                    <img src="${userPhoto}" onerror="this.onerror=null;this.src='assets/img/default_avatar.jpg'" class="w-9 h-9 rounded-full ${userFrame} object-cover">
                                    <div>
                                        <span class="font-outfit font-bold text-slate-900 text-xs block">${u.name}</span>
                                        <span class="text-[10px] text-slate-500">Nível ${u.level} • ${u.xp} XP</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <button onclick="acceptFriendRequest(${u.id})" class="bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-[11px] px-3 py-1.5 rounded-xl shadow-sm flex items-center gap-1 transition">
                                        <i class="bi bi-check-lg"></i> Aceitar
                                    </button>
                                    <button onclick="rejectFriendRequest(${u.id})" class="bg-slate-200 hover:bg-rose-100 text-slate-700 hover:text-rose-700 font-bold text-[11px] px-2.5 py-1.5 rounded-xl flex items-center gap-1 transition">
                                        <i class="bi bi-x-lg"></i> Recusar
                                    </button>
                                </div>
                            `;
                            list.appendChild(item);
                        });
                        section.classList.remove('hidden');
                    }
                } else {
                    if (pendingBadge) pendingBadge.classList.add('hidden');
                    if (section) section.classList.add('hidden');
                }
            } catch (err) {
                console.error('Erro ao carregar solicitações pendentes:', err);
            }
        }

        // FUNÇÕES DO MODAL DE AMIGOS
        function openAddFriendModal() {
            document.getElementById('addFriendModal').classList.remove('hidden');
            document.getElementById('friendSearchInput').value = '';
            document.getElementById('searchResultsContainer').classList.add('hidden');
            document.getElementById('suggestionsSection').classList.remove('hidden');
            loadPendingRequests();
            loadFriendSuggestions();
            document.getElementById('friendSearchInput').focus();
        }

        function closeAddFriendModal() {
            document.getElementById('addFriendModal').classList.add('hidden');
        }

        let searchTimeout = null;
        function searchUsers() {
            clearTimeout(searchTimeout);
            const query = document.getElementById('friendSearchInput').value.trim();
            const container = document.getElementById('searchResultsContainer');
            const suggestions = document.getElementById('suggestionsSection');

            if (query.length < 2) {
                container.classList.add('hidden');
                suggestions.classList.remove('hidden');
                return;
            }

            container.classList.remove('hidden');
            suggestions.classList.add('hidden');

            searchTimeout = setTimeout(async () => {
                container.innerHTML = `<div class="text-center py-6 text-slate-400 text-xs"><i class="bi bi-arrow-repeat animate-spin text-xl inline-block"></i> Buscando...</div>`;

                try {
                    const res = await fetch(`api/friends.php?action=search&q=${encodeURIComponent(query)}`);
                    const data = await res.json();

                    if (!data.success || data.users.length === 0) {
                        container.innerHTML = `
                            <div class="text-center py-6 text-slate-400 text-xs">
                                Nenhum estudante encontrado com o nome ou e-mail "${query}".
                            </div>
                        `;
                        return;
                    }

                    container.innerHTML = '';
                    data.users.forEach(u => {
                        const status = u.friend_status;
                        const userPhoto = (u.avatar && u.avatar.trim() !== '') ? u.avatar : 'assets/img/default_avatar.jpg';
                        const userFrame = u.avatar_frame || 'frame-indigo';

                        const card = document.createElement('div');
                        card.className = "flex items-center justify-between p-3 bg-slate-50 rounded-2xl border border-slate-200 hover:border-indigo-300 transition";
                        
                        let actionBtn = '';
                        if (status === 'accepted') {
                            actionBtn = `
                                <div class="flex items-center gap-1">
                                    <span class="bg-emerald-100 text-emerald-700 font-extrabold text-[11px] px-3 py-1.5 rounded-xl flex items-center gap-1">
                                        <i class="bi bi-check-lg"></i> Amigo
                                    </span>
                                </div>
                            `;
                        } else if (status === 'pending_sent') {
                            actionBtn = `
                                <div class="flex items-center gap-1">
                                    <span class="bg-amber-100 text-amber-800 font-extrabold text-[11px] px-3 py-1.5 rounded-xl flex items-center gap-1">
                                        <i class="bi bi-clock"></i> Enviada
                                    </span>
                                    <button onclick="cancelRequest(${u.id})" class="text-slate-400 hover:text-rose-600 text-xs p-1" title="Cancelar">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </div>
                            `;
                        } else if (status === 'pending_received') {
                            actionBtn = `
                                <div class="flex items-center gap-1.5">
                                    <button onclick="acceptFriendRequest(${u.id})" class="bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs px-3 py-1.5 rounded-xl shadow-sm flex items-center gap-1 transition">
                                        <i class="bi bi-check-lg"></i> Aceitar
                                    </button>
                                    <button onclick="rejectFriendRequest(${u.id})" class="bg-slate-200 hover:bg-rose-100 text-slate-700 hover:text-rose-700 font-bold text-xs px-2.5 py-1.5 rounded-xl flex items-center gap-1 transition">
                                        <i class="bi bi-x-lg"></i> Recusar
                                    </button>
                                </div>
                            `;
                        } else {
                            actionBtn = `
                                <button onclick="sendFriendRequest(${u.id})" class="bg-[#6842C2] hover:bg-[#5832b2] text-white font-extrabold text-xs px-3.5 py-1.5 rounded-xl shadow-sm transition flex items-center gap-1">
                                    <i class="bi bi-person-plus"></i> Adicionar
                                </button>
                            `;
                        }

                        card.innerHTML = `
                            <div class="flex items-center gap-3">
                                <img src="${userPhoto}" onerror="this.onerror=null;this.src='assets/img/default_avatar.jpg'" class="w-10 h-10 rounded-full ${userFrame} object-cover shadow-sm">
                                <div>
                                    <span class="font-outfit font-bold text-slate-900 text-sm block">${u.name}</span>
                                    <span class="text-[11px] text-slate-500">Nível ${u.level} • ${u.xp} XP</span>
                                </div>
                            </div>
                            ${actionBtn}
                        `;
                        container.appendChild(card);
                    });

                } catch (err) {
                    console.error(err);
                }
            }, 300);
        }

        async function sendFriendRequest(friendId) {
            const formData = new FormData();
            formData.append('action', 'send_request');
            formData.append('friend_id', friendId);

            const res = await fetch('api/friends.php', { method: 'POST', body: formData });
            const data = await res.json();

            if (data.success) {
                Swal.fire({
                    title: 'Solicitação Enviada!',
                    text: data.message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
                searchUsers();
                loadPendingRequests();
                loadFriendSuggestions();
            } else {
                Swal.fire('Ops!', data.message, 'warning');
            }
        }

        async function acceptFriendRequest(senderId) {
            const formData = new FormData();
            formData.append('action', 'accept_request');
            formData.append('sender_id', senderId);

            const res = await fetch('api/friends.php', { method: 'POST', body: formData });
            const data = await res.json();

            if (data.success) {
                Swal.fire({
                    title: 'Amizade Confirmada!',
                    text: data.message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
                loadPendingRequests();
                searchUsers();
                loadLeaderboard();
                loadMuralFeed();
            } else {
                Swal.fire('Ops!', data.message, 'warning');
            }
        }

        async function rejectFriendRequest(targetId) {
            const formData = new FormData();
            formData.append('action', 'reject_request');
            formData.append('target_id', targetId);

            const res = await fetch('api/friends.php', { method: 'POST', body: formData });
            const data = await res.json();

            if (data.success) {
                loadPendingRequests();
                searchUsers();
            }
        }

        async function cancelRequest(targetId) {
            const formData = new FormData();
            formData.append('action', 'cancel_request');
            formData.append('target_id', targetId);

            const res = await fetch('api/friends.php', { method: 'POST', body: formData });
            const data = await res.json();

            if (data.success) {
                searchUsers();
                loadPendingRequests();
            }
        }

        async function removeFriend(friendId) {
            const result = await Swal.fire({
                title: 'Remover Amigo?',
                text: "Deseja remover este estudante da sua lista de amigos?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6842C2',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sim, remover',
                cancelButtonText: 'Cancelar'
            });

            if (!result.isConfirmed) return;

            const formData = new FormData();
            formData.append('action', 'remove_friend');
            formData.append('friend_id', friendId);

            const res = await fetch('api/friends.php', { method: 'POST', body: formData });
            const data = await res.json();

            if (data.success) {
                searchUsers();
                loadLeaderboard();
                loadMuralFeed();
            }
        }

        // MODAL DE PERFIL DO AMIGO (VER CONQUISTAS)
        async function openFriendProfileModal(friendId) {
            if (typeof sounds !== 'undefined') sounds.playClick();
            try {
                const res = await fetch(`api/friends.php?action=friend_profile&friend_id=${friendId}`);
                const data = await res.json();

                if (!data.success || !data.friend) return;

                const f = data.friend;
                document.getElementById('fpName').textContent = f.name;
                document.getElementById('fpLevel').textContent = `Nível ${f.level}`;
                document.getElementById('fpXp').textContent = `${f.xp} XP`;
                document.getElementById('fpStreak').innerHTML = `<i class="bi bi-fire text-amber-500"></i> ${f.streak_days || 0}d`;

                const badgeName = BADGE_MAP[f.avatar_icon] || 'Estudante Padrão';
                document.getElementById('fpTitle').innerHTML = `<i class="bi ${f.avatar_icon || 'bi-person-circle'}"></i> ${badgeName}`;

                const photo = (f.avatar && f.avatar.trim() !== '') ? f.avatar : 'assets/img/default_avatar.jpg';
                const avatarImg = document.getElementById('fpAvatar');
                avatarImg.src = photo;
                avatarImg.className = `w-20 h-20 rounded-full mx-auto object-cover mb-2 border-2 ${f.avatar_frame || 'frame-indigo'} shadow-md`;

                document.getElementById('fpAchCount').textContent = data.total_achievements || 0;
                const list = document.getElementById('fpAchievementsList');

                if (!data.achievements || data.achievements.length === 0) {
                    list.innerHTML = `<div class="text-center py-4 text-slate-400 text-xs">Este colega ainda não desbloqueou conquistas.</div>`;
                } else {
                    list.innerHTML = '';
                    data.achievements.forEach(a => {
                        const item = document.createElement('div');
                        item.className = "flex items-center gap-2.5 p-2 bg-slate-50 rounded-xl border border-slate-200 text-left";
                        item.innerHTML = `
                            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-sm shrink-0">
                                <i class="bi ${a.icon || 'bi-award-fill'}"></i>
                            </div>
                            <div class="min-w-0">
                                <span class="font-outfit font-extrabold text-slate-900 text-xs block truncate">${a.title}</span>
                                <span class="text-[10px] text-slate-500 block leading-tight truncate">${a.description}</span>
                            </div>
                        `;
                        list.appendChild(item);
                    });
                }

                document.getElementById('friendProfileModal').classList.remove('hidden');
            } catch (err) {
                console.error(err);
            }
        }

        function closeFriendProfileModal() {
            document.getElementById('friendProfileModal').classList.add('hidden');
        }
    </script>
    <?php require_once __DIR__ . '/includes/footer.php'; ?>
