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
    'bi-crown' => 'Rei da Aprovação',
    'bi-emoji-smile-fill' => '🦛 Hipopótamo Lendário'
];
$userBadgeIcon = $user['avatar_icon'] ?? 'bi-person-circle';
$userBadgeName = $userBadgeMap[$userBadgeIcon] ?? 'Estudante Padrão';
?>
<!DOCTYPE html>
<html lang="pt-BR" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking de Amigos — HipoGabarito</title>

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

    <!-- GOOGLE FONTS & BOOTSTRAP ICONS & SWEETALERT2 -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/main.css?v=<?= time() ?>">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="min-h-full font-sans antialiased text-slate-800 bg-slate-50 selection:bg-indigo-500 selection:text-white">

    <!-- TOP HEADER / HUD DE JOGADOR TAILWIND -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b-2 border-slate-200 py-3 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <a href="dashboard.php" class="flex items-center gap-2 group text-decoration-none">
                <img src="assets/img/hipogabarito_logo.png" alt="HipoGabarito Logo" class="h-9 sm:h-10 w-auto object-contain group-hover:scale-105 transition-transform">
            </a>

            <!-- HUD STATUS DO ALUNO -->
            <?php
            $streakCountLeaderboard = (int)($user['streak_days'] ?? 1);
            $isStreakActiveLeaderboard = ($streakCountLeaderboard >= 2);
            ?>
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

                <div class="flex items-center gap-2 bg-amber-50 px-4 py-1.5 rounded-2xl border-2 border-amber-300 shadow-[0_2px_0_0_#f59e0b]">
                    <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-1.048c-2.5 1.6-4.5 4.5-4.5 7.5 0 .285.021.564.062.836A4.99 4.99 0 014 6.5a1 1 0 00-1.92.4C2.5 9.5 4.5 12 7 12c.3 0 .59-.03.873-.087.6.93 1.556 1.6 2.685 1.776A5.002 5.002 0 0015 9c0-3.5-1.5-5.5-2.605-6.447z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-outfit font-extrabold text-amber-700 text-sm"><?= (int)($user['streak_days'] ?? 1) ?> dias</span>
                </div>

                <div class="flex items-center gap-2 bg-white px-4 py-1.5 rounded-2xl border-2 border-slate-200 shadow-[0_2px_0_0_#e2e8f0]">
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

                        <a href="leaderboard.php" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-outfit font-extrabold text-sm bg-indigo-600 text-white shadow-[0_4px_0_0_#312e81] hover:bg-indigo-700 transition-all">
                            <svg class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4m6 17v-5m0 0a2 2 0 100-4 2 2 0 000 4zm0 5a2 2 0 100-4 2 2 0 000 4zM6 17v-3m0 0a2 2 0 100-4 2 2 0 000 4zm0 3a2 2 0 100-4 2 2 0 000 4zM18 17v-3m0 0a2 2 0 100-4 2 2 0 000 4zm0 3a2 2 0 100-4 2 2 0 000 4z"/>
                            </svg>
                            Ranking de Amigos
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

            <!-- CONTEÚDO PRINCIPAL: TABELA DE RANKING DE AMIGOS -->
            <section class="lg:col-span-9 space-y-6">
                
                <!-- BANNER DE RANKING E BOTÃO DE ADICIONAR AMIGOS -->
                <div class="bg-gradient-to-r from-amber-500 via-amber-600 to-indigo-700 text-white rounded-3xl p-6 shadow-lg border-2 border-amber-400 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-extrabold bg-white/20 text-amber-100 border border-white/30 uppercase tracking-wider mb-2">
                            <i class="bi bi-people-fill"></i> LIGA PRIVADA ENTRE AMIGOS
                        </span>
                        <h2 class="font-outfit font-extrabold text-2xl sm:text-3xl mb-1">Ranking de Amigos</h2>
                        <p class="text-xs sm:text-sm text-amber-100 font-medium mb-0">Dispute a liderança do XP com seus colegas de classe e amigos adicionados.</p>
                    </div>
                    
                    <button onclick="openAddFriendModal()" class="bg-white text-indigo-700 hover:bg-amber-50 font-outfit font-black px-5 py-3 rounded-2xl shadow-md transition-all flex items-center gap-2 text-sm whitespace-nowrap group relative">
                        <i class="bi bi-person-plus-fill text-amber-500 text-lg group-hover:scale-110 transition-transform"></i>
                        <span>+ Adicionar Amigos</span>
                        <span id="pendingBadge" class="hidden bg-rose-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full shadow-sm animate-pulse">0</span>
                    </button>
                </div>

                <!-- TABELA DE RANKING -->
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

            </section>
        </div>
    </main>

    <!-- MODAL DE ADICIONAR E GERENCIAR AMIGOS -->
    <div id="addFriendModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl border-2 border-slate-200 max-w-lg w-full p-6 shadow-2xl relative animate-in fade-in zoom-in-95 duration-200">
            
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl">
                        <i class="bi bi-person-add"></i>
                    </div>
                    <div>
                        <h3 class="font-outfit font-extrabold text-slate-900 text-lg leading-tight">Amigos & Solicitações</h3>
                        <p class="text-xs text-slate-500">Envie e responda a solicitações de amizade de estudos</p>
                    </div>
                </div>
                <button onclick="closeAddFriendModal()" class="text-slate-400 hover:text-slate-600 text-2xl leading-none">&times;</button>
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
            <div class="relative mb-4">
                <i class="bi bi-search absolute left-4 top-3.5 text-slate-400 text-base"></i>
                <input type="text" id="friendSearchInput" oninput="searchUsers()" placeholder="Digite o nome ou e-mail do seu colega..." class="w-full bg-slate-50 border-2 border-slate-200 rounded-2xl pl-11 pr-4 py-2.5 text-sm font-medium text-slate-800 focus:outline-none focus:border-indigo-600 transition">
            </div>

            <!-- RESULTADOS DA BUSCA -->
            <div id="searchResultsContainer" class="max-h-64 overflow-y-auto space-y-2 pr-1">
                <div class="text-center py-8 text-slate-400 text-xs">
                    <i class="bi bi-search text-2xl mb-1 block"></i>
                    Digite pelo menos 2 letras para pesquisar colegas
                </div>
            </div>

            <div class="mt-6 pt-3 border-t border-slate-100 flex justify-end">
                <button onclick="closeAddFriendModal()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-5 py-2.5 rounded-xl text-xs transition">
                    Fechar
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
            'bi-emoji-smile-fill': '🦛 Hipopótamo Lendário'
        };

        document.addEventListener('DOMContentLoaded', () => {
            loadLeaderboard();
            loadPendingRequests();
        });

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
                        const rowClass = isMe ? 'bg-indigo-50/70 hover:bg-indigo-50 font-bold' : 'hover:bg-slate-50/80';
                        const userPhoto = (r.avatar && r.avatar.trim() !== '') ? r.avatar : 'assets/img/default_avatar.jpg';
                        const userFrame = r.avatar_frame || 'frame-indigo';

                        const badgeIcon = r.avatar_icon || 'bi-person-circle';
                        const badgeName = BADGE_MAP[badgeIcon] || 'Estudante Padrão';
                        const isStreakActive = (r.streak_days >= 2);

                        const actionBtn = isMe ? `
                            <span class="text-[11px] font-extrabold text-indigo-600 bg-indigo-50 border border-indigo-200 px-3 py-1 rounded-xl">Você</span>
                        ` : `
                            <button onclick="removeFriend(${r.id})" class="text-slate-500 hover:text-rose-600 text-xs px-2.5 py-1 rounded-xl border border-slate-200 hover:border-rose-300 hover:bg-rose-50 font-bold transition inline-flex items-center gap-1" title="Remover Amizade">
                                <i class="bi bi-person-x text-rose-500"></i> Remover
                            </button>
                        `;

                        const tr = document.createElement('tr');
                        tr.className = `transition ${rowClass}`;
                        tr.innerHTML = `
                            <td class="py-4 px-6 font-outfit fw-bold">${posBadge}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <img src="${userPhoto}" onerror="this.onerror=null;this.src='assets/img/default_avatar.jpg'" class="w-10 h-10 rounded-full ${userFrame} object-cover shadow-sm">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="font-outfit font-bold text-slate-900 text-sm truncate">${r.name}</span>
                                            ${isMe ? '<span class="text-[10px] px-2 py-0.5 rounded-full bg-indigo-600 text-white font-extrabold">VOCÊ</span>' : ''}
                                        </div>
                                        <span class="text-[11px] font-bold text-indigo-600 flex items-center gap-1">
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
                            <td class="py-4 px-6 text-right font-outfit font-extrabold text-indigo-600 text-base">${r.xp} XP</td>
                            <td class="py-4 px-6 text-center">${actionBtn}</td>
                        `;
                        tbody.appendChild(tr);
                    });
                });
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
            loadPendingRequests();
            document.getElementById('friendSearchInput').focus();
            document.getElementById('searchResultsContainer').innerHTML = `
                <div class="text-center py-8 text-slate-400 text-xs">
                    <i class="bi bi-search text-2xl mb-1 block"></i>
                    Digite pelo menos 2 letras para pesquisar colegas
                </div>
            `;
        }

        function closeAddFriendModal() {
            document.getElementById('addFriendModal').classList.add('hidden');
        }

        let searchTimeout = null;
        function searchUsers() {
            clearTimeout(searchTimeout);
            const query = document.getElementById('friendSearchInput').value.trim();
            const container = document.getElementById('searchResultsContainer');

            if (query.length < 2) {
                container.innerHTML = `
                    <div class="text-center py-8 text-slate-400 text-xs">
                        <i class="bi bi-search text-2xl mb-1 block"></i>
                        Digite pelo menos 2 letras para pesquisar colegas
                    </div>
                `;
                return;
            }

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
                                    <button onclick="removeFriend(${u.id})" class="text-slate-400 hover:text-rose-600 text-xs p-1" title="Desfazer Amizade">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            `;
                        } else if (status === 'pending_sent') {
                            actionBtn = `
                                <div class="flex items-center gap-1">
                                    <span class="bg-amber-100 text-amber-800 font-extrabold text-[11px] px-3 py-1.5 rounded-xl flex items-center gap-1">
                                        <i class="bi bi-clock"></i> Solicitação Enviada
                                    </span>
                                    <button onclick="cancelRequest(${u.id})" class="text-slate-400 hover:text-rose-600 text-xs p-1" title="Cancelar Solicitação">
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
                                <button onclick="sendFriendRequest(${u.id})" class="bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs px-3.5 py-1.5 rounded-xl shadow-sm transition flex items-center gap-1">
                                    <i class="bi bi-person-plus"></i> Enviar Solicitação
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
                confirmButtonColor: '#4f46e5',
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
            }
        }
    </script>
    <script src="assets/js/notifications.js"></script>
</body>
</html>
