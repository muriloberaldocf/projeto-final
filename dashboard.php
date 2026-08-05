<?php
require_once __DIR__ . '/config/db.php';
checkAuth();

$userId = $_SESSION['user_id'];

// Buscar dados atualizados do usuário
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();
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
    <title>Trilha de Aprendizado — HipoGabarito</title>

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

    <style>
        /* SCROLLBAR MODERNO */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* ANIMAÇÃO DO BALÃO DE FALA FLUTUANTE */
        @keyframes floatBubble {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }
        .animate-float-bubble {
            animation: floatBubble 2s infinite ease-in-out;
        }

        /* ANIMAÇÃO DE PULSO DO BOTÃO ATIVO */
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 8px 0 0 #312e81, 0 0 0 0 rgba(79, 70, 229, 0.4); }
            50% { box-shadow: 0 8px 0 0 #312e81, 0 0 0 16px rgba(79, 70, 229, 0); }
        }
        .animate-pulse-node {
            animation: pulseGlow 2.2s infinite ease-in-out;
        }
    </style>
</head>
<body class="min-h-full font-sans antialiased text-slate-800 bg-slate-50 selection:bg-indigo-500 selection:text-white">

    <!-- TOP HEADER / HUD DE JOGADOR TAILWIND -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b-2 border-slate-200 py-2 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <!-- LOGO DA MARCA -->
            <a href="dashboard.php" class="flex items-center gap-2 group text-decoration-none">
                <img src="assets/img/hipogabarito_logo.png" alt="HipoGabarito Logo" class="h-9 sm:h-10 w-auto object-contain group-hover:scale-105 transition-transform">
            </a>

            <!-- HUD STATUS DO ALUNO -->
            <?php
            $streakCount = (int)($user['streak_days'] ?? 1);
            $isStreakActive = ($streakCount >= 2);
            ?>
            <div class="flex items-center gap-2.5">
                <div class="flex items-center gap-1.5 <?= $isStreakActive ? 'bg-amber-50 border-amber-300 shadow-[0_2px_0_0_#f59e0b]' : 'bg-white border-slate-200 shadow-[0_2px_0_0_#e2e8f0]' ?> px-3 py-1 rounded-2xl border-2 transition-all" title="<?= $isStreakActive ? 'Ofensiva Ativa! (2+ dias consecutivos)' : 'Fogo apagado: estude amanhã novamente para acender!' ?>">
                    <svg class="w-4 h-4 <?= $isStreakActive ? 'text-amber-500 animate-pulse' : 'text-slate-300' ?>" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-1.048c-2.5 1.6-4.5 4.5-4.5 7.5 0 .285.021.564.062.836A4.99 4.99 0 014 6.5a1 1 0 00-1.92.4C2.5 9.5 4.5 12 7 12c.3 0 .59-.03.873-.087.6.93 1.556 1.6 2.685 1.776A5.002 5.002 0 0015 9c0-3.5-1.5-5.5-2.605-6.447z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-outfit font-extrabold <?= $isStreakActive ? 'text-amber-700' : 'text-slate-400' ?> text-xs"><?= $streakCount ?>d</span>
                </div>

                <div class="flex items-center gap-1.5 bg-white px-3 py-1 rounded-2xl border-2 border-slate-200 shadow-[0_2px_0_0_#e2e8f0]" title="XP Acumulado">
                    <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-outfit font-extrabold text-indigo-600 text-xs"><?= htmlspecialchars($user['xp'] ?? 0) ?> XP</span>
                </div>

                <a href="profile.php" class="ml-0.5 group" title="Meu Perfil">
                    <img src="<?= htmlspecialchars($userAvatar) ?>" alt="Avatar" class="w-8 h-8 rounded-full border-2 border-indigo-600 object-cover group-hover:scale-105 transition-transform shadow-sm">
                </a>
            </div>
        </div>
    </header>

    <!-- LAYOUT PRINCIPAL (GRID 3 COLUNAS) -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">

            <!-- SIDEBAR ESQUERDA -->
            <aside class="lg:col-span-3">
                <div class="bg-white rounded-3xl border-2 border-slate-200 p-3.5 shadow-[0_3px_0_0_#e2e8f0]">
                    <div class="flex items-center gap-3 p-2.5 mb-2.5 bg-slate-50 rounded-2xl border border-slate-200">
                        <img src="<?= htmlspecialchars($userAvatar) ?>" class="w-10 h-10 rounded-full border-2 border-indigo-600 object-cover shadow-sm">
                        <div class="min-w-0 flex-1">
                            <h3 class="font-outfit font-bold text-slate-900 text-sm truncate"><?= htmlspecialchars($user['name'] ?? 'Estudante') ?></h3>
                            <div class="flex items-center gap-1 text-[11px] font-bold text-indigo-600 truncate mt-0.5" title="Título Equipado">
                                <i class="bi <?= htmlspecialchars($userBadgeIcon) ?>"></i>
                                <span class="truncate"><?= htmlspecialchars($userBadgeName) ?></span>
                            </div>
                        </div>
                    </div>

                    <nav class="space-y-1.5">
                        <a href="dashboard.php" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-2xl font-outfit font-extrabold text-xs bg-indigo-600 text-white shadow-[0_3px_0_0_#312e81] hover:bg-indigo-700 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                            Trilha de Estudos
                        </a>

                        <a href="leaderboard.php" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-2xl font-outfit font-bold text-xs text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-all">
                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4m6 17v-5m0 0a2 2 0 100-4 2 2 0 000 4zm0 5a2 2 0 100-4 2 2 0 000 4zM6 17v-3m0 0a2 2 0 100-4 2 2 0 000 4zm0 3a2 2 0 100-4 2 2 0 000 4zM18 17v-3m0 0a2 2 0 100-4 2 2 0 000 4zm0 3a2 2 0 100-4 2 2 0 000 4z"/>
                            </svg>
                            Ranking Arcade
                        </a>

                        <a href="profile.php" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-2xl font-outfit font-bold text-xs text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-all">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Meu Perfil
                        </a>

                        <?php if (($user['role'] ?? '') === 'admin'): ?>
                            <a href="admin.php" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-2xl font-outfit font-bold text-xs text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 transition-all mt-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Painel Professor
                            </a>
                        <?php endif; ?>

                        <div class="pt-2 border-t border-slate-200 mt-2">
                            <a href="api/auth.php?action=logout" class="flex items-center gap-2.5 px-3.5 py-2 rounded-2xl font-outfit font-bold text-xs text-slate-400 hover:text-rose-600 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Sair
                            </a>
                        </div>
                    </nav>
                </div>
            </aside>

            <!-- COLUNA CENTRAL: MAPA EM ONDA DA TRILHA -->
            <section class="lg:col-span-6 space-y-4">
                <div class="flex items-center gap-2.5 overflow-x-auto pb-1 scrollbar-none" id="subjectSelector"></div>

                <div id="roadmapMapTree" class="space-y-4">
                    <div class="flex flex-col items-center justify-center py-10 bg-white rounded-3xl border-2 border-slate-200 shadow-sm">
                        <div class="w-8 h-8 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
                        <span class="mt-2 font-outfit font-extrabold text-slate-500 text-xs">Carregando mapa de fases...</span>
                    </div>
                </div>
            </section>

            <!-- SIDEBAR DIREITA -->
            <aside class="lg:col-span-3 space-y-3.5">
                <!-- META DIÁRIA -->
                <div class="bg-white rounded-3xl border-2 border-slate-200 p-3.5 shadow-[0_3px_0_0_#e2e8f0]">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-outfit font-extrabold text-slate-900 text-sm">Meta Diária</h4>
                        <div class="w-7 h-7 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center border border-amber-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-500 mb-2 font-medium">Conquiste 50 XP hoje para manter sua sequência ativa!</p>

                    <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden mb-1.5 border border-slate-200">
                        <div class="h-full bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-full transition-all duration-500" style="width: <?= min(100, (($user['xp'] ?? 0) % 50) * 2) ?>%;"></div>
                    </div>
                    <div class="flex justify-between items-center text-[11px] font-extrabold font-outfit text-slate-600">
                        <span>Progresso</span>
                        <span class="text-indigo-600"><?= (($user['xp'] ?? 0) % 50) ?> / 50 XP</span>
                    </div>
                </div>

                <!-- DESAFIOS DO DIA -->
                <div class="bg-white rounded-3xl border-2 border-slate-200 p-3.5 shadow-[0_3px_0_0_#e2e8f0]">
                    <div class="flex items-center justify-between mb-2.5">
                        <h4 class="font-outfit font-extrabold text-slate-900 text-sm">Desafios Diários</h4>
                        <div class="w-7 h-7 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center gap-2.5 p-2 bg-slate-50 rounded-2xl border border-slate-200">
                            <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-outfit font-bold text-xs text-slate-900 mb-0">Conclua 1 Fase</h5>
                                <span class="text-[10px] font-extrabold text-indigo-600">+10 XP Bônus</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2.5 p-2 bg-slate-50 rounded-2xl border border-slate-200">
                            <div class="w-7 h-7 rounded-full bg-slate-200 text-slate-400 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                    <circle cx="12" cy="12" r="9"/>
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-outfit font-bold text-xs text-slate-900 mb-0">Pontuação Maior que 80%</h5>
                                <span class="text-[10px] font-extrabold text-amber-600">+20 XP Bônus</span>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </main>

    <!-- MODAL DE FASE TAILWIND -->
    <div id="stageModal" class="fixed inset-0 z-50 flex items-center justify-center p-3 bg-slate-900/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-200">
        <div class="bg-white rounded-3xl border-4 border-slate-200 shadow-2xl w-full max-w-sm p-4 text-center transform scale-95 transition-transform duration-200 relative" id="stageModalCard">
            <button onclick="closeStageModal()" class="absolute top-3 right-3 text-slate-400 hover:text-slate-600 p-1 rounded-full hover:bg-slate-100 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <img src="assets/img/logo_mascot.png" alt="Mascote Hipó" class="w-16 h-16 mx-auto mb-2 object-contain drop-shadow-md animate-bounce">

            <span id="modalStageBadge" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-indigo-50 text-indigo-600 border border-indigo-200 mb-2">
                FASE 1
            </span>

            <h3 id="modalStageTitle" class="font-outfit font-extrabold text-lg text-slate-900 mb-1">Título da Fase</h3>
            <p id="modalStageDesc" class="text-xs text-slate-500 mb-3 font-medium leading-relaxed">Conclua os exercícios desta fase para avançar na trilha!</p>

            <div class="mb-3">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block mb-1">Sua Pontuação</span>
                <div id="modalStageStars" class="flex items-center justify-center gap-1"></div>
            </div>

            <div class="space-y-2">
                <!-- BOTÃO DE TEORIA DA FASE -->
                <button type="button" id="btnTheoryStage" class="w-full py-2 px-4 rounded-2xl font-outfit font-bold text-xs bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border border-indigo-200 transition-all flex items-center justify-center gap-2">
                    <i class="bi bi-book-half text-indigo-600 text-sm"></i>
                    Ler Teoria & Conceitos
                </button>

                <!-- BOTÃO DE VÍDEO AULA -->
                <button type="button" id="btnVideoStage" class="w-full py-2 px-4 rounded-2xl font-outfit font-bold text-xs bg-amber-50 text-amber-800 hover:bg-amber-100 border border-amber-200 transition-all flex items-center justify-center gap-2">
                    <i class="bi bi-play-btn-fill text-amber-600 text-sm"></i>
                    Assistir Vídeo-Aula
                </button>

                <button type="button" id="btnPlayStage" class="w-full py-2.5 px-4 rounded-2xl font-outfit font-extrabold text-sm bg-emerald-500 text-white shadow-[0_4px_0_0_#047857] hover:bg-emerald-600 active:translate-y-0.5 active:shadow-[0_1px_0_0_#047857] transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                    </svg>
                    RESOLVER QUESTÕES (+35 XP)
                </button>

                <button type="button" id="btnBossStage" style="display: none;" class="w-full py-2.5 px-4 rounded-2xl font-outfit font-extrabold text-xs bg-rose-600 text-white shadow-[0_4px_0_0_#9f1239] hover:bg-rose-700 active:translate-y-0.5 active:shadow-[0_1px_0_0_#9f1239] transition-all items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-amber-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-1.048c-2.5 1.6-4.5 4.5-4.5 7.5 0 .285.021.564.062.836A4.99 4.99 0 014 6.5a1 1 0 00-1.92.4C2.5 9.5 4.5 12 7 12c.3 0 .59-.03.873-.087.6.93 1.556 1.6 2.685 1.776A5.002 5.002 0 0015 9c0-3.5-1.5-5.5-2.605-6.447z" clip-rule="evenodd"/>
                    </svg>
                    DESAFIO BOSS (+50 XP)
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL DE TEORIA / LEITURA DO CONTEXTO DA FASE -->
    <div id="theoryModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-200">
        <div class="bg-white rounded-3xl border-2 border-slate-200 shadow-2xl w-full max-w-lg p-6 relative transform scale-95 transition-transform duration-200" id="theoryModalCard">
            <button onclick="closeTheoryModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 text-2xl leading-none">&times;</button>
            <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-100">
                <div class="w-10 h-10 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl">
                    <i class="bi bi-book-half"></i>
                </div>
                <div>
                    <h3 id="theoryModalTitle" class="font-outfit font-extrabold text-slate-900 text-lg leading-tight">Teoria do Conteúdo</h3>
                    <span class="text-xs text-indigo-600 font-bold">Resumo Prático dos Conceitos</span>
                </div>
            </div>
            <div id="theoryModalBody" class="text-slate-700 text-xs sm:text-sm leading-relaxed max-h-72 overflow-y-auto pr-1"></div>
            <div class="mt-6 pt-3 border-t border-slate-100 flex justify-end">
                <button onclick="closeTheoryModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-5 py-2.5 rounded-xl text-xs transition">
                    Fechar Leitura
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL DE VÍDEO-AULA DA FASE -->
    <div id="videoModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-200">
        <div class="bg-white rounded-3xl border-2 border-slate-200 shadow-2xl w-full max-w-2xl p-6 relative transform scale-95 transition-transform duration-200" id="videoModalCard">
            <button onclick="closeVideoModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 text-2xl leading-none">&times;</button>
            <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-100">
                <div class="w-10 h-10 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl">
                    <i class="bi bi-play-btn-fill"></i>
                </div>
                <div>
                    <h3 id="videoModalTitle" class="font-outfit font-extrabold text-slate-900 text-base leading-tight">Vídeo-Aula Explicativa</h3>
                    <span class="text-xs text-amber-600 font-bold">Assista antes de resolver os exercícios</span>
                </div>
            </div>
            
            <div class="aspect-video w-full rounded-2xl overflow-hidden bg-black shadow-inner mb-4">
                <iframe id="videoIframe" class="w-full h-full" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>

            <div class="flex justify-end">
                <button onclick="closeVideoModal()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-5 py-2.5 rounded-xl text-xs transition">
                    Fechar Vídeo
                </button>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT DO MAPA E RASTRO CONTÍNUO S-CURVE BEZIER -->
    <script src="assets/js/sound_effects.js"></script>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        let currentSubject = urlParams.get('subject') || 'matematica';

        const SVG_ICONS = {
            CHECK: `<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>`,
            STAR: `<svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>`,
            GIFT: `<svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 5a3 3 0 015-2.236A3 3 0 0115 5h2a1 1 0 011 1v3a1 1 0 01-1 1h-1v7a2 2 0 01-2 2H6a2 2 0 01-2-2v-7H3a1 1 0 01-1-1V6a1 1 0 011-1h2zm2.5-1a1.5 1.5 0 100 3H10a1.5 1.5 0 00-2.5-3zM10 7H7.5A1.5 1.5 0 006 8.5 1.5 1.5 0 007.5 10H10V7zm2.5 0H10v3h2.5a1.5 1.5 0 001.5-1.5A1.5 1.5 0 0012.5 7zm0-3A1.5 1.5 0 0010 5.5h2.5a1.5 1.5 0 000-3z" clip-rule="evenodd"/></svg>`,
            CROWN: `<svg class="w-7 h-7 text-amber-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1.323l3.954-1.582a1 1 0 011.298 1.258l-1.5 4.5A1 1 0 0113.8 9H6.2a1 1 0 01-.952-.701l-1.5-4.5a1 1 0 011.298-1.258L9 4.323V3a1 1 0 011-1zm-5 11a1 1 0 011-1h8a1 1 0 011 1v3a1 1 0 01-1 1H6a1 1 0 01-1-1v-3z" clip-rule="evenodd"/></svg>`,
            STAR_GOLD: `<svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>`,
            STAR_GRAY: `<svg class="w-5 h-5 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>`
        };

        const PATH_OFFSETS_TAILWIND = [
            'ml-0',
            'ml-10 sm:ml-16',
            'ml-20 sm:ml-28',
            'ml-10 sm:ml-16',
            'ml-0',
            '-ml-10 sm:-ml-16',
            '-ml-20 sm:-ml-28',
            '-ml-10 sm:-ml-16'
        ];

        const PATH_OFFSETS_NUMERIC = [0, 50, 95, 50, 0, -50, -95, -50];

        function loadRoadmap(subjectSlug) {
            currentSubject = subjectSlug;

            fetch(`api/get_roadmap.php?subject=${subjectSlug}`)
                .then(res => res.json())
                .then(data => {
                    if (!data.success) return;

                    // RENDERIZAR ABAS DE MATÉRIAS
                    const selector = document.getElementById('subjectSelector');
                    selector.innerHTML = '';
                    data.subjects.forEach(sub => {
                        const isSelected = (sub.slug === currentSubject);
                        const tab = document.createElement('a');
                        tab.href = '#';
                        tab.className = `flex items-center gap-1.5 px-3 py-1.5 rounded-2xl font-outfit font-bold text-xs whitespace-nowrap transition-all border-2 ${
                            isSelected 
                            ? 'bg-indigo-600 text-white border-indigo-700 shadow-[0_3px_0_0_#312e81]' 
                            : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50 hover:text-slate-900 shadow-[0_2px_0_0_#e2e8f0]'
                        }`;
                        tab.innerHTML = `<span>${sub.name}</span>`;
                        tab.onclick = (e) => {
                            e.preventDefault();
                            if (typeof sounds !== 'undefined') sounds.playClick();
                            loadRoadmap(sub.slug);
                            if (window.history && window.history.replaceState) {
                                window.history.replaceState(null, '', `dashboard.php?subject=${sub.slug}`);
                            }
                        };
                        selector.appendChild(tab);
                    });

                    // RENDERIZAR TRILHA COM SVG OVERLAY ÚNICO E REAIS NÚMEROS DE COORDENADAS
                    const treeContainer = document.getElementById('roadmapMapTree');
                    treeContainer.innerHTML = '';

                    let globalStageCounter = 1;
                    let foundActiveNode = false;

                    data.units.forEach((unit, unitIdx) => {
                        // BANNER DE UNIDADE
                        const unitBanner = document.createElement('div');
                        unitBanner.className = 'relative bg-gradient-to-r from-indigo-600 to-indigo-800 text-white rounded-3xl p-4 shadow-[0_6px_0_0_#312e81] border-2 border-indigo-400 overflow-hidden mb-3';
                        unitBanner.innerHTML = `
                            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-white/20 text-indigo-100 border border-white/30 uppercase tracking-wider mb-1">
                                        UNIDADE ${unitIdx + 1}
                                    </span>
                                    <h2 class="font-outfit font-extrabold text-lg mb-0.5">${unit.title}</h2>
                                    <p class="text-xs text-indigo-100 max-w-md mb-0 font-medium">${unit.description}</p>
                                </div>
                            </div>
                        `;
                        treeContainer.appendChild(unitBanner);

                        // CONTAINER DO CAMINHO DE FASES
                        const pathContainer = document.createElement('div');
                        pathContainer.className = 'flex flex-col items-center py-2 relative';

                        const isMobile = window.innerWidth < 640;
                        const factor = isMobile ? 0.45 : 1;
                        const nodeSpacing = 72;
                        const nodeRadius = 30;
                        const totalHeight = unit.lessons.length * nodeSpacing;
                        const viewBoxWidth = 600;
                        const centerX = viewBoxWidth / 2;

                        // RENDERIZAR PATHS BEZIER VÁLIDOS EM SVG NATIVO
                        let greenPathD = '';
                        let grayPathD = '';

                        unit.lessons.forEach((lesson, lessonIdx) => {
                            if (lessonIdx < unit.lessons.length - 1) {
                                const isCompleted = lesson.is_completed;

                                const x1 = Math.round(centerX + (PATH_OFFSETS_NUMERIC[lessonIdx % PATH_OFFSETS_NUMERIC.length] * factor));
                                const y1 = nodeRadius + lessonIdx * nodeSpacing;

                                const nextIdx = lessonIdx + 1;
                                const x2 = Math.round(centerX + (PATH_OFFSETS_NUMERIC[nextIdx % PATH_OFFSETS_NUMERIC.length] * factor));
                                const y2 = nodeRadius + nextIdx * nodeSpacing;

                                const cy1 = Math.round(y1 + (y2 - y1) / 2);
                                const cy2 = Math.round(y1 + (y2 - y1) / 2);

                                const segmentD = `M ${x1} ${y1} C ${x1} ${cy1}, ${x2} ${cy2}, ${x2} ${y2} `;

                                if (isCompleted) {
                                    greenPathD += segmentD;
                                } else {
                                    grayPathD += segmentD;
                                }
                            }
                        });

                        // CAMADA SVG DE FUNDO COM VIEWBOX VÁLIDO E RESOLUÇÃO RETIFICADA
                        const svgOverlay = document.createElement('div');
                        svgOverlay.className = 'absolute inset-0 w-full h-full pointer-events-none z-0';
                        svgOverlay.innerHTML = `
                            <svg class="w-full h-full" viewBox="0 0 ${viewBoxWidth} ${totalHeight}" preserveAspectRatio="none">
                                ${greenPathD ? `<path d="${greenPathD}" fill="none" stroke="#10b981" stroke-width="12" stroke-linecap="round" stroke-linejoin="round" />` : ''}
                                ${grayPathD ? `<path d="${grayPathD}" fill="none" stroke="#cbd5e1" stroke-width="12" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="14 10" />` : ''}
                            </svg>
                        `;
                        pathContainer.appendChild(svgOverlay);

                        // BOTÕES DE FASE
                        unit.lessons.forEach((lesson, lessonIdx) => {
                            const isCompleted = lesson.is_completed;
                            const isBoss = (lessonIdx === unit.lessons.length - 1);
                            const isChest = (!isBoss && (lessonIdx + 1) % 3 === 0);
                            const offsetClass = PATH_OFFSETS_TAILWIND[lessonIdx % PATH_OFFSETS_TAILWIND.length];

                            const nodeWrapper = document.createElement('div');
                            nodeWrapper.className = `relative flex flex-col items-center z-10 transition-transform ${offsetClass} mb-[10px]`;

                            let speechBubbleHtml = '';
                            if (!isCompleted && !foundActiveNode) {
                                foundActiveNode = true;
                                speechBubbleHtml = `
                                    <div class="absolute left-full ml-3 top-1/2 -translate-y-1/2 z-20 flex items-center animate-pulse pointer-events-none">
                                        <div class="w-0 h-0 border-t-[5px] border-t-transparent border-b-[5px] border-b-transparent border-r-[6px] border-r-indigo-600 -mr-px"></div>
                                        <div class="bg-indigo-600 text-white font-outfit font-extrabold text-[10px] uppercase tracking-wider px-2.5 py-1 rounded-xl shadow-lg border border-indigo-400 flex items-center gap-1 whitespace-nowrap">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-ping"></span>
                                            PRÓXIMA ETAPA!
                                        </div>
                                    </div>
                                `;
                            }

                            let svgRingHtml = '';
                            if (!isCompleted && speechBubbleHtml !== '') {
                                svgRingHtml = `
                                    <svg class="absolute -inset-1.5 w-[76px] h-[76px] pointer-events-none">
                                        <circle cx="38" cy="38" r="33" fill="none" stroke="#e2e8f0" stroke-width="5"></circle>
                                        <circle cx="38" cy="38" r="33" fill="none" stroke="#4f46e5" stroke-width="5" stroke-linecap="round" stroke-dasharray="207" stroke-dashoffset="140"></circle>
                                    </svg>
                                `;
                            }

                            let btnClasses = 'w-16 h-16 rounded-full flex items-center justify-center border-4 border-white cursor-pointer transition-all outline-none relative ';
                            let iconSvg = SVG_ICONS.STAR;

                            if (isBoss) {
                                btnClasses += 'w-18 h-18 bg-rose-600 shadow-[0_6px_0_0_#9f1239] border-amber-300 hover:bg-rose-700 active:translate-y-1 active:shadow-[0_2px_0_0_#9f1239]';
                                iconSvg = SVG_ICONS.CROWN;
                            } else if (isChest) {
                                btnClasses += 'w-14 h-14 rounded-2xl bg-amber-500 shadow-[0_5px_0_0_#b45309] hover:bg-amber-600 active:translate-y-1 active:shadow-[0_2px_0_0_#b45309]';
                                iconSvg = SVG_ICONS.GIFT;
                            } else if (isCompleted) {
                                btnClasses += 'bg-emerald-500 shadow-[0_6px_0_0_#047857] hover:bg-emerald-600 active:translate-y-1 active:shadow-[0_2px_0_0_#047857]';
                                iconSvg = SVG_ICONS.CHECK;
                            } else {
                                btnClasses += 'bg-indigo-600 shadow-[0_6px_0_0_#312e81] hover:bg-indigo-700 active:translate-y-1 active:shadow-[0_2px_0_0_#312e81] animate-pulse-node';
                                iconSvg = SVG_ICONS.STAR;
                            }

                            nodeWrapper.innerHTML = `
                                ${speechBubbleHtml}
                                ${svgRingHtml}
                                <button type="button" class="${btnClasses}" onclick='openStageModal(${JSON.stringify(lesson)}, ${globalStageCounter}, ${isBoss})'>
                                    ${iconSvg}
                                </button>
                            `;

                            pathContainer.appendChild(nodeWrapper);
                            globalStageCounter++;
                        });

                        treeContainer.appendChild(pathContainer);
                    });
                });
        }

        function openStageModal(lesson, stageNumber, isBoss) {
            if (typeof sounds !== 'undefined') sounds.playClick();

            document.getElementById('modalStageBadge').textContent = isBoss ? 'DESAFIO BOSS 👑' : `FASE ${stageNumber}`;
            document.getElementById('modalStageTitle').textContent = lesson.title;
            document.getElementById('modalStageDesc').textContent = isBoss 
                ? 'Questões de alto nível com bônus máximo de XP!' 
                : 'Responda aos exercícios para conquistar estrelas e subir de nível.';

            const starsContainer = document.getElementById('modalStageStars');
            let starsCount = 0;
            if (lesson.is_completed) {
                const score = lesson.score || 0;
                if (score >= 90) starsCount = 3;
                else if (score >= 60) starsCount = 2;
                else starsCount = 1;
            }

            starsContainer.innerHTML = '';
            for (let s = 1; s <= 3; s++) {
                starsContainer.innerHTML += (s <= starsCount) ? SVG_ICONS.STAR_GOLD : SVG_ICONS.STAR_GRAY;
            }

            const btnPlay = document.getElementById('btnPlayStage');
            btnPlay.onclick = () => {
                if (typeof sounds !== 'undefined') sounds.playClick();
                window.location.href = `lesson.php?id=${lesson.id}`;
            };

            // Configurar Botão de Teoria & Conceitos da Fase
            const btnTheory = document.getElementById('btnTheoryStage');
            btnTheory.onclick = () => {
                if (typeof sounds !== 'undefined') sounds.playClick();
                openTheoryModal(lesson);
            };

            // Configurar Botão de Vídeo-Aula da Fase
            const btnVideo = document.getElementById('btnVideoStage');
            btnVideo.onclick = () => {
                if (typeof sounds !== 'undefined') sounds.playClick();
                openVideoModal(lesson);
            };

            const btnBoss = document.getElementById('btnBossStage');
            if (lesson.is_completed || isBoss) {
                btnBoss.style.display = 'flex';
                btnBoss.onclick = () => {
                    if (typeof sounds !== 'undefined') sounds.playClick();
                    window.location.href = `lesson.php?id=${lesson.id}&mode=boss`;
                };
            } else {
                btnBoss.style.display = 'none';
            }

            const modal = document.getElementById('stageModal');
            const card = document.getElementById('stageModalCard');
            modal.classList.remove('opacity-0', 'pointer-events-none');
            card.classList.remove('scale-95');
            card.classList.add('scale-100');
        }

        function closeStageModal() {
            if (typeof sounds !== 'undefined') sounds.playClick();
            const modal = document.getElementById('stageModal');
            const card = document.getElementById('stageModalCard');
            modal.classList.add('opacity-0', 'pointer-events-none');
            card.classList.remove('scale-100');
            card.classList.add('scale-95');
        }

        // CONTROLADORES DO MODAL DE TEORIA & CONCEITOS
        function openTheoryModal(lesson) {
            document.getElementById('theoryModalTitle').textContent = lesson.title || 'Teoria do Conteúdo';
            
            const rawText = lesson.intro_text || `📌 **Resumo Prático:** Neta fase de **${lesson.title}**, revise os conceitos essenciais para garantir um excelente desempenho nas questões!\n\n💡 **Dica:** Preste atenção aos detalhes das fórmulas e grandezas envolvidas.`;
            const formattedHtml = rawText
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\n\n/g, '<br><br>')
                .replace(/\n/g, '<br>');
            
            document.getElementById('theoryModalBody').innerHTML = formattedHtml;

            const modal = document.getElementById('theoryModal');
            const card = document.getElementById('theoryModalCard');
            modal.classList.remove('opacity-0', 'pointer-events-none');
            card.classList.remove('scale-95');
            card.classList.add('scale-100');
        }

        function closeTheoryModal() {
            if (typeof sounds !== 'undefined') sounds.playClick();
            const modal = document.getElementById('theoryModal');
            const card = document.getElementById('theoryModalCard');
            modal.classList.add('opacity-0', 'pointer-events-none');
            card.classList.remove('scale-100');
            card.classList.add('scale-95');
        }

        // CONTROLADORES DO MODAL DE VÍDEO-AULA
        function openVideoModal(lesson) {
            document.getElementById('videoModalTitle').textContent = lesson.video_title || `Vídeo-Aula: ${lesson.title}`;
            const videoUrl = lesson.video_url || 'https://www.youtube.com/embed/dQw4w9WgXcQ';
            document.getElementById('videoIframe').src = videoUrl;

            const modal = document.getElementById('videoModal');
            const card = document.getElementById('videoModalCard');
            modal.classList.remove('opacity-0', 'pointer-events-none');
            card.classList.remove('scale-95');
            card.classList.add('scale-100');
        }

        function closeVideoModal() {
            if (typeof sounds !== 'undefined') sounds.playClick();
            document.getElementById('videoIframe').src = ''; // Parar o vídeo ao fechar
            const modal = document.getElementById('videoModal');
            const card = document.getElementById('videoModalCard');
            modal.classList.add('opacity-0', 'pointer-events-none');
            card.classList.remove('scale-100');
            card.classList.add('scale-95');
        }

        document.getElementById('stageModal').addEventListener('click', function(e) {
            if (e.target === this) closeStageModal();
        });
        document.getElementById('theoryModal').addEventListener('click', function(e) {
            if (e.target === this) closeTheoryModal();
        });
        document.getElementById('videoModal').addEventListener('click', function(e) {
            if (e.target === this) closeVideoModal();
        });

        document.addEventListener('DOMContentLoaded', () => loadRoadmap(currentSubject));
    </script>
</body>
</html>
