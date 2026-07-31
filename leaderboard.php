<?php
require_once __DIR__ . '/config/db.php';
checkAuth();

$userId = $_SESSION['user_id'];
$stmtUser = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmtUser->execute([$userId]);
$user = $stmtUser->fetch();
$userAvatar = !empty($user['avatar']) ? $user['avatar'] : 'https://api.dicebear.com/7.x/bottts/svg?seed=' . urlencode($user['name'] ?? 'Player');

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
    <title>Ranking Arcade — AprovaQuest</title>

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
</head>
<body class="min-h-full font-sans antialiased text-slate-800 bg-slate-50 selection:bg-indigo-500 selection:text-white">

    <!-- TOP HEADER / HUD DE JOGADOR TAILWIND -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b-2 border-slate-200 py-3 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <a href="dashboard.php" class="flex items-center gap-2 group text-decoration-none">
                <img src="assets/img/logo_mascot.png" alt="AprovaQuest Logo" class="h-9 sm:h-10 w-auto object-contain group-hover:scale-105 transition-transform">
            </a>

            <!-- HUD STATUS DO ALUNO (SEM VIDAS!) -->
            <div class="flex items-center gap-3">
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
                    <img src="<?= htmlspecialchars($userAvatar) ?>" alt="Avatar" class="w-10 h-10 rounded-full border-2 border-indigo-600 object-cover group-hover:scale-105 transition-transform shadow-sm">
                </a>
            </div>
        </div>
    </header>

    <!-- LAYOUT PRINCIPAL -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- SIDEBAR ESQUERDA -->
            <aside class="lg:col-span-3">
                <div class="bg-white rounded-3xl border-2 border-slate-200 p-5 shadow-[0_4px_0_0_#e2e8f0]">
                    <div class="flex items-center gap-3.5 p-3 mb-4 bg-slate-50 rounded-2xl border border-slate-200">
                        <img src="<?= htmlspecialchars($userAvatar) ?>" class="w-12 h-12 rounded-full border-2 border-indigo-600 object-cover shadow-sm">
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
                            Ranking Arcade
                        </a>

                        <a href="profile.php" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-outfit font-bold text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-all">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Meu Perfil
                        </a>

                        <?php if (($user['role'] ?? '') === 'admin'): ?>
                            <a href="admin.php" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-outfit font-bold text-sm text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 transition-all mt-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Painel Professor
                            </a>
                        <?php endif; ?>

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

            <!-- CONTEÚDO PRINCIPAL: TABELA DE RANKING ARCADE -->
            <section class="lg:col-span-9 space-y-6">
                <!-- BANNER DE RANKING -->
                <div class="bg-gradient-to-r from-amber-500 via-amber-600 to-amber-700 text-white rounded-3xl p-6 shadow-[0_8px_0_0_#b45309] border-2 border-amber-400 flex items-center justify-between">
                    <div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-extrabold bg-white/20 text-amber-100 border border-white/30 uppercase tracking-wider mb-2">
                            LIGA DE ALTO RENDIMENTO
                        </span>
                        <h2 class="font-outfit font-extrabold text-2xl mb-1">Ranking Geral Arcade</h2>
                        <p class="text-xs text-amber-100 font-medium mb-0">Estudantes com maior pontuação acumulada nos exercícios e simulados.</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center text-amber-200">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 3v4M3 5h4m6 17v-5m0 0a2 2 0 100-4 2 2 0 000 4zm0 5a2 2 0 100-4 2 2 0 000 4zM6 17v-3m0 0a2 2 0 100-4 2 2 0 000 4zm0 3a2 2 0 100-4 2 2 0 000 4zM18 17v-3m0 0a2 2 0 100-4 2 2 0 000 4zm0 3a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
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
                                </tr>
                            </thead>
                            <tbody id="rankTableBody" class="divide-y divide-slate-100">
                                <!-- JS Populates -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </main>

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
            fetch('api/get_leaderboard.php')
                .then(res => res.json())
                .then(data => {
                    if (!data.success) return;

                    const tbody = document.getElementById('rankTableBody');
                    tbody.innerHTML = '';

                    data.rankings.forEach((r, idx) => {
                        const pos = idx + 1;
                        let posBadge = `<span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-extrabold bg-slate-100 text-slate-600 border border-slate-200">${pos}º</span>`;

                        if (pos === 1) {
                            posBadge = `<span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-extrabold bg-amber-100 text-amber-800 border border-amber-300 shadow-sm">🥇 1º LUGAR</span>`;
                        } else if (pos === 2) {
                            posBadge = `<span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-extrabold bg-slate-200 text-slate-800 border border-slate-300">🥈 2º LUGAR</span>`;
                        } else if (pos === 3) {
                            posBadge = `<span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-extrabold bg-amber-50 text-amber-900 border border-amber-200">🥉 3º LUGAR</span>`;
                        }

                        const isMe = (r.id == data.current_user_id);
                        const tr = document.createElement('tr');
                        tr.className = `transition-colors ${isMe ? 'bg-indigo-50/80 font-semibold' : 'hover:bg-slate-50'}`;

                        // USAR A FOTO REAL DO USUÁRIO (R.AVATAR) SE EXISTIR, OU FALLBACK PARA DICEBEAR
                        const userPhoto = (r.avatar && r.avatar.trim() !== '') ? r.avatar : `https://api.dicebear.com/7.x/bottts/svg?seed=${encodeURIComponent(r.name)}`;
                        const badgeIcon = r.avatar_icon || 'bi-person-circle';
                        const badgeName = BADGE_MAP[badgeIcon] || 'Estudante Padrão';

                        tr.innerHTML = `
                            <td class="py-4 px-6">${posBadge}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <img src="${userPhoto}" class="w-10 h-10 rounded-full border-2 border-indigo-500 bg-indigo-50 object-cover shadow-sm">
                                    <div class="min-w-0">
                                        <span class="font-outfit font-bold text-slate-900 text-sm block truncate">${r.name} ${isMe ? '<span class="ml-1 text-[10px] px-2 py-0.5 rounded-full bg-indigo-600 text-white font-extrabold">VOCÊ</span>' : ''}</span>
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
                                <span class="text-amber-500 font-extrabold text-xs flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-1.048c-2.5 1.6-4.5 4.5-4.5 7.5 0 .285.021.564.062.836A4.99 4.99 0 014 6.5a1 1 0 00-1.92.4C2.5 9.5 4.5 12 7 12c.3 0 .59-.03.873-.087.6.93 1.556 1.6 2.685 1.776A5.002 5.002 0 0015 9c0-3.5-1.5-5.5-2.605-6.447z" clip-rule="evenodd"/></svg>
                                    ${r.streak_days}d
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right font-outfit font-extrabold text-indigo-600 text-base">${r.xp} XP</td>
                        `;
                        tbody.appendChild(tr);
                    });
                });
        });
    </script>
</body>
</html>

