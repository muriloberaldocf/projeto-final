<?php
require_once __DIR__ . '/config/db.php';
checkAuth();

$userId = $_SESSION['user_id'];

// Estatísticas do Usuário
$stmtUser = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmtUser->execute([$userId]);
$user = $stmtUser->fetch();
$userAvatar = !empty($user['avatar']) ? $user['avatar'] : 'https://api.dicebear.com/7.x/bottts/svg?seed=' . urlencode($user['name'] ?? 'Player');

$currentAvatar = $user['avatar_icon'] ?? 'bi-person-circle';
$userXp = (int) ($user['xp'] ?? 0);

// Catálogo de Avatares / Insígnias e Títulos por XP
$avatars = [
    ['icon' => 'bi-person-circle', 'name' => 'Estudante Padrão', 'xp' => 0],
    ['icon' => 'bi-backpack', 'name' => 'Mochileiro Focado', 'xp' => 20],
    ['icon' => 'bi-mortarboard', 'name' => 'Formando Vestibulando', 'xp' => 50],
    ['icon' => 'bi-rocket-takeoff', 'name' => 'Foguete da Aprovação', 'xp' => 100],
    ['icon' => 'bi-lightning-charge', 'name' => 'Mago do Conhecimento', 'xp' => 200],
    ['icon' => 'bi-award', 'name' => 'Campeão de Simulados', 'xp' => 350],
    ['icon' => 'bi-gem', 'name' => 'Diamante Medicina', 'xp' => 500],
    ['icon' => 'bi-incognito', 'name' => 'Mestre Misterioso', 'xp' => 750],
    ['icon' => 'bi-crown', 'name' => 'Rei da Aprovação', 'xp' => 1000],
    ['icon' => 'bi-emoji-smile-fill', 'name' => '🦛 Hipopótamo Lendário', 'xp' => 1500, 'img' => 'assets/img/logo_mascot.png']
];

$equippedBadge = ['icon' => 'bi-person-circle', 'name' => 'Estudante Padrão'];
foreach ($avatars as $av) {
    if ($av['icon'] === $currentAvatar) {
        $equippedBadge = $av;
        break;
    }
}

// Buscar Conquistas
$stmtAch = $pdo->query("SELECT * FROM achievements");
$achievements = $stmtAch->fetchAll();

$stmtMyAch = $pdo->prepare("SELECT achievement_id FROM user_achievements WHERE user_id = ?");
$stmtMyAch->execute([$userId]);
$myAchIds = $stmtMyAch->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="pt-BR" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil & Avatares — AprovaQuest</title>

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

    <!-- GOOGLE FONTS -->
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
                    <div class="flex items-center gap-3 p-2.5 mb-2.5 bg-slate-50 rounded-2xl border border-slate-200">
                        <img src="<?= htmlspecialchars($userAvatar) ?>" class="w-10 h-10 rounded-full border-2 border-indigo-600 object-cover shadow-sm">
                        <div class="min-w-0 flex-1">
                            <h3 class="font-outfit font-bold text-slate-900 text-sm truncate"><?= htmlspecialchars($user['name'] ?? 'Estudante') ?></h3>
                            <div class="flex items-center gap-1 text-[11px] font-bold text-indigo-600 truncate mt-0.5" title="Título Equipado">
                                <i class="bi <?= htmlspecialchars($equippedBadge['icon']) ?>"></i>
                                <span class="truncate"><?= htmlspecialchars($equippedBadge['name']) ?></span>
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

                        <a href="profile.php" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-outfit font-extrabold text-sm bg-indigo-600 text-white shadow-[0_4px_0_0_#312e81] hover:bg-indigo-700 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
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

            <!-- CONTEÚDO PRINCIPAL: PERFIL & AVATARES -->
            <section class="lg:col-span-9 space-y-6">
                <!-- CARD RESUMO DO PERFIL COM OPÇÃO DE FOTO -->
                <div class="bg-white rounded-3xl border-2 border-slate-200 p-5 shadow-[0_3px_0_0_#e2e8f0] flex flex-col sm:flex-row items-center gap-5">
                    <!-- FOTO COM BADGE DE CÂMERA CLICÁVEL -->
                    <div class="relative group cursor-pointer" onclick="openPhotoModal()" title="Clique para alterar sua foto de perfil">
                        <img src="<?= htmlspecialchars($userAvatar) ?>" id="profileAvatarImg" class="w-20 h-20 rounded-full border-4 border-indigo-600 object-cover shadow-md bg-indigo-50 group-hover:opacity-90 transition-opacity">
                        <div class="absolute bottom-0 right-0 w-7 h-7 bg-indigo-600 text-white rounded-full flex items-center justify-center shadow-md border-2 border-white group-hover:scale-110 transition-transform">
                            <i class="bi bi-camera-fill text-xs"></i>
                        </div>
                    </div>

                    <div class="text-center sm:text-left flex-1 min-w-0">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <h2 class="font-outfit font-extrabold text-2xl text-slate-900 mb-0.5 truncate"><?= htmlspecialchars($user['name']) ?></h2>
                                
                                <!-- INSÍGNIA / TÍTULO DE JOGADOR EQUIPADO ABAIXO DO NOME -->
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200 mb-2">
                                    <i class="bi <?= htmlspecialchars($equippedBadge['icon']) ?> text-sm text-indigo-600"></i>
                                    <span><?= htmlspecialchars($equippedBadge['name']) ?></span>
                                </div>

                                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                                    <span class="px-2.5 py-0.5 rounded-xl text-xs font-extrabold bg-slate-100 text-slate-700 border border-slate-200">
                                        NÍVEL <?= $user['level'] ?>
                                    </span>
                                    <span class="px-2.5 py-0.5 rounded-xl text-xs font-extrabold bg-amber-50 text-amber-700 border border-amber-200 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/></svg>
                                        <?= $userXp ?> XP Acumulados
                                    </span>
                                </div>
                            </div>

                            <button type="button" onclick="openPhotoModal()" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl font-outfit font-extrabold text-xs bg-indigo-600 text-white shadow-[0_3px_0_0_#312e81] hover:bg-indigo-700 active:translate-y-0.5 transition-all self-center sm:self-auto">
                                <i class="bi bi-camera-fill text-sm"></i>
                                Alterar Foto de Perfil
                            </button>
                        </div>
                    </div>
                </div>

                <!-- LOJA DE TÍTULOS & INSÍGNIAS POR XP -->
                <div class="bg-white rounded-3xl border-2 border-slate-200 p-5 shadow-[0_3px_0_0_#e2e8f0]">
                    <div class="mb-4">
                        <h3 class="font-outfit font-extrabold text-lg text-slate-900 mb-0.5">Títulos & Insígnias por XP</h3>
                        <p class="text-xs text-slate-500 font-medium mb-0">Acumule XP na sua trilha para desbloquear e equipar títulos exclusivos que aparecem logo abaixo do seu nome!</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        <?php foreach ($avatars as $av): ?>
                            <?php 
                                $isUnlocked = ($userXp >= $av['xp']);
                                $isEquipped = ($currentAvatar === $av['icon']);
                            ?>
                            <div class="bg-slate-50 rounded-2xl border-2 p-3 text-center transition-all <?= 
                                $isEquipped ? 'border-indigo-600 bg-indigo-50/50 shadow-sm' : ($isUnlocked ? 'border-slate-200 hover:border-indigo-400 hover:shadow-md cursor-pointer' : 'border-slate-200 opacity-60')
                            ?>" <?= $isUnlocked ? "onclick=\"equipAvatarIcon('{$av['icon']}')\"" : '' ?>>
                                <div class="h-10 text-3xl text-indigo-600 mb-1.5 flex items-center justify-center">
                                    <?php if (!empty($av['img'])): ?>
                                        <img src="<?= $av['img'] ?>" class="h-10 w-auto object-contain">
                                    <?php else: ?>
                                        <i class="bi <?= $av['icon'] ?>"></i>
                                    <?php endif; ?>
                                </div>
                                <h4 class="font-outfit font-bold text-xs text-slate-900 mb-1.5"><?= htmlspecialchars($av['name']) ?></h4>

                                <?php if ($isEquipped): ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-xl text-[11px] font-extrabold bg-indigo-600 text-white">Equipado</span>
                                <?php elseif ($isUnlocked): ?>
                                    <button class="w-full py-1 px-2.5 rounded-xl font-outfit font-bold text-xs bg-indigo-50 text-indigo-700 hover:bg-indigo-600 hover:text-white border border-indigo-200 transition-all">Equipar</button>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-xl text-[11px] font-bold bg-slate-200 text-slate-600"><i class="bi bi-lock-fill"></i> <?= $av['xp'] ?> XP</span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- CONQUISTAS -->
                <div class="bg-white rounded-3xl border-2 border-slate-200 p-5 shadow-[0_3px_0_0_#e2e8f0]">
                    <h3 class="font-outfit font-extrabold text-lg text-slate-900 mb-3">Conquistas & Selos</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <?php foreach ($achievements as $ach): ?>
                            <?php $unlocked = in_array($ach['id'], $myAchIds); ?>
                            <div class="p-3 rounded-2xl border-2 flex items-center gap-3 <?= $unlocked ? 'border-amber-300 bg-amber-50/60' : 'border-slate-200 bg-slate-50 opacity-50' ?>">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xl shrink-0 <?= $unlocked ? 'text-amber-500' : 'text-slate-400' ?>">
                                    <i class="bi <?= $unlocked ? 'bi-award-fill' : 'bi-lock-fill' ?>"></i>
                                </div>
                                <div>
                                    <h4 class="font-outfit font-bold text-xs text-slate-900 mb-0.5"><?= htmlspecialchars($ach['title']) ?></h4>
                                    <p class="text-[11px] text-slate-500 font-medium mb-0"><?= htmlspecialchars($ach['description']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- MODAL SELETOR E UPLOADER DE FOTO DE PERFIL -->
    <div id="photoModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-200">
        <div class="bg-white rounded-3xl border-4 border-slate-200 shadow-2xl w-full max-w-lg p-6 transform scale-95 transition-transform duration-200 relative" id="photoModalCard">
            <!-- BOTÃO FECHAR -->
            <button onclick="closePhotoModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 p-1.5 rounded-full hover:bg-slate-100 transition-all">
                <i class="bi bi-x-lg text-lg"></i>
            </button>

            <div class="mb-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-indigo-50 text-indigo-600 border border-indigo-200 mb-2">
                    <i class="bi bi-person-bounding-box me-1"></i> FOTO DE PERFIL
                </span>
                <h3 class="font-outfit font-extrabold text-xl text-slate-900">Escolha sua Foto de Perfil</h3>
                <p class="text-xs text-slate-500 font-medium">Envie uma foto do seu dispositivo ou escolha um avatar ilustrado!</p>
            </div>

            <!-- ABAS DE NAVEGAÇÃO DO MODAL -->
            <div class="flex border-b border-slate-200 mb-5 gap-2">
                <button type="button" onclick="switchPhotoTab('upload')" id="tabBtnUpload" class="px-3.5 py-2 font-outfit font-extrabold text-xs border-b-2 border-indigo-600 text-indigo-600 transition-all">
                    <i class="bi bi-upload me-1"></i> Enviar Arquivo
                </button>
                <button type="button" onclick="switchPhotoTab('presets')" id="tabBtnPresets" class="px-3.5 py-2 font-outfit font-extrabold text-xs border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-all">
                    <i class="bi bi-grid-fill me-1"></i> Avatares Ilustrados
                </button>
                <button type="button" onclick="switchPhotoTab('url')" id="tabBtnUrl" class="px-3.5 py-2 font-outfit font-extrabold text-xs border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-all">
                    <i class="bi bi-link-45deg me-1"></i> Link URL
                </button>
            </div>

            <!-- TAB 1: UPLOAD DE FOTO DO DISPOSITIVO -->
            <div id="tabUpload" class="space-y-4">
                <form id="avatarUploadForm" onsubmit="handleFileUpload(event)">
                    <div class="border-2 border-dashed border-indigo-200 bg-indigo-50/50 hover:bg-indigo-50 rounded-2xl p-6 text-center cursor-pointer transition-all relative" onclick="document.getElementById('avatarFileInput').click()">
                        <input type="file" id="avatarFileInput" accept="image/png, image/jpeg, image/webp, image/gif" class="hidden" onchange="previewSelectedFile(this)">
                        
                        <div id="uploadPlaceholder" class="space-y-2">
                            <div class="w-12 h-12 rounded-full bg-indigo-600 text-white flex items-center justify-center mx-auto shadow-md">
                                <i class="bi bi-cloud-arrow-up-fill text-xl"></i>
                            </div>
                            <div>
                                <p class="font-outfit font-extrabold text-sm text-slate-800 mb-0.5">Clique para escolher uma foto</p>
                                <p class="text-[11px] text-slate-500 font-medium">Suporta JPG, PNG, WEBP ou GIF (máx 5MB)</p>
                            </div>
                        </div>

                        <!-- PREVIEW DA FOTO SELECIONADA -->
                        <div id="uploadPreviewContainer" class="hidden space-y-3">
                            <img id="uploadPreviewImg" src="" class="w-24 h-24 rounded-full border-4 border-indigo-600 object-cover mx-auto shadow-md">
                            <p class="font-outfit font-bold text-xs text-indigo-600 truncate" id="uploadFileName"></p>
                        </div>
                    </div>

                    <button type="submit" id="btnSaveFilePhoto" disabled class="w-full mt-4 py-3 px-4 rounded-2xl font-outfit font-extrabold text-sm bg-indigo-600 text-white shadow-[0_4px_0_0_#312e81] hover:bg-indigo-700 disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed transition-all flex items-center justify-center gap-2">
                        <i class="bi bi-check-circle-fill"></i> Salvar Foto Escolhida
                    </button>
                </form>
            </div>

            <!-- TAB 2: GALERIA DE AVATARES ILUSTRADOS -->
            <div id="tabPresets" class="hidden space-y-3">
                <p class="text-xs text-slate-500 font-medium mb-3">Clique em qualquer ilustração para definir como sua foto de perfil imediata:</p>
                <div class="grid grid-cols-4 gap-3 max-h-64 overflow-y-auto p-1 scrollbar-thin">
                    <?php
                    $presetAvatars = [
                        ['name' => 'Mascote Hipopótamo', 'url' => 'assets/img/logo_mascot.png'],
                        ['name' => 'Robô Gamer', 'url' => 'https://api.dicebear.com/7.x/bottts/svg?seed=GamerAprova'],
                        ['name' => 'Estudante Focado', 'url' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=Vestibular2025'],
                        ['name' => 'Astronauta', 'url' => 'https://api.dicebear.com/7.x/bottts/svg?seed=AstroQuest'],
                        ['name' => 'Cientista', 'url' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=GenioQuimico'],
                        ['name' => 'Mago do Saber', 'url' => 'https://api.dicebear.com/7.x/lorelei/svg?seed=MagoAprova'],
                        ['name' => 'Ninja', 'url' => 'https://api.dicebear.com/7.x/bottts/svg?seed=NinjaMath'],
                        ['name' => 'Coruja da Sabedoria', 'url' => 'https://api.dicebear.com/7.x/identicon/svg?seed=CorujaWise'],
                        ['name' => 'Leão da Aprovação', 'url' => 'https://api.dicebear.com/7.x/bottts/svg?seed=KingLion'],
                        ['name' => 'Medicina', 'url' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=DoctorPro'],
                        ['name' => 'Engenharia', 'url' => 'https://api.dicebear.com/7.x/bottts/svg?seed=EngineerTech'],
                        ['name' => 'Direito', 'url' => 'https://api.dicebear.com/7.x/lorelei/svg?seed=JusticeHero'],
                        ['name' => 'Foguete', 'url' => 'https://api.dicebear.com/7.x/identicon/svg?seed=RocketXP']
                    ];
                    foreach ($presetAvatars as $preset):
                    ?>
                        <div onclick="selectPresetAvatar('<?= $preset['url'] ?>')" class="bg-slate-50 hover:bg-indigo-50 border-2 border-slate-200 hover:border-indigo-500 rounded-2xl p-2 text-center cursor-pointer transition-all group">
                            <img src="<?= $preset['url'] ?>" class="w-12 h-12 rounded-full mx-auto mb-1 group-hover:scale-110 transition-transform">
                            <span class="text-[10px] font-outfit font-bold text-slate-700 block truncate"><?= $preset['name'] ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- TAB 3: LINK URL DE IMAGEM -->
            <div id="tabUrl" class="hidden space-y-4">
                <div>
                    <label class="block font-outfit font-bold text-xs text-slate-700 mb-1">Cole a URL da sua foto (ex: HTTPS):</label>
                    <input type="url" id="avatarUrlInput" placeholder="https://exemplo.com/minha-foto.jpg" class="w-full px-3.5 py-2.5 rounded-xl border-2 border-slate-200 font-medium text-xs focus:border-indigo-600 focus:outline-none transition-all" oninput="previewUrlImage(this.value)">
                </div>

                <div id="urlPreviewBox" class="hidden text-center p-3 bg-slate-50 border border-slate-200 rounded-2xl">
                    <img id="urlPreviewImg" src="" class="w-20 h-20 rounded-full border-2 border-indigo-600 object-cover mx-auto mb-1" onerror="handleUrlError()">
                    <span id="urlPreviewStatus" class="text-[11px] font-bold text-emerald-600">Pré-visualização bem-sucedida!</span>
                </div>

                <button type="button" onclick="saveUrlAvatar()" id="btnSaveUrlPhoto" disabled class="w-full py-3 px-4 rounded-2xl font-outfit font-extrabold text-sm bg-indigo-600 text-white shadow-[0_4px_0_0_#312e81] hover:bg-indigo-700 disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed transition-all">
                    Salvar Foto via Link
                </button>
            </div>
        </div>
    </div>

    <script src="assets/js/sound_effects.js"></script>
    <script>
        // MODAL HANDLERS
        function openPhotoModal() {
            if (typeof sounds !== 'undefined') sounds.playClick();
            const modal = document.getElementById('photoModal');
            const card = document.getElementById('photoModalCard');
            modal.classList.remove('opacity-0', 'pointer-events-none');
            card.classList.remove('scale-95');
            card.classList.add('scale-100');
        }

        function closePhotoModal() {
            const modal = document.getElementById('photoModal');
            const card = document.getElementById('photoModalCard');
            modal.classList.add('opacity-0', 'pointer-events-none');
            card.classList.remove('scale-100');
            card.classList.add('scale-95');
        }

        function switchPhotoTab(tabName) {
            ['upload', 'presets', 'url'].forEach(tab => {
                document.getElementById('tab' + tab.charAt(0).toUpperCase() + tab.slice(1)).classList.add('hidden');
                const btn = document.getElementById('tabBtn' + tab.charAt(0).toUpperCase() + tab.slice(1));
                btn.classList.remove('border-indigo-600', 'text-indigo-600');
                btn.classList.add('border-transparent', 'text-slate-500');
            });

            document.getElementById('tab' + tabName.charAt(0).toUpperCase() + tabName.slice(1)).classList.remove('hidden');
            const activeBtn = document.getElementById('tabBtn' + tabName.charAt(0).toUpperCase() + tabName.slice(1));
            activeBtn.classList.remove('border-transparent', 'text-slate-500');
            activeBtn.classList.add('border-indigo-600', 'text-indigo-600');
        }

        // PREVIEW DE ARQUIVO LOCAL
        function previewSelectedFile(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('uploadPlaceholder').classList.add('hidden');
                    document.getElementById('uploadPreviewContainer').classList.remove('hidden');
                    document.getElementById('uploadPreviewImg').src = e.target.result;
                    document.getElementById('uploadFileName').textContent = file.name;
                    document.getElementById('btnSaveFilePhoto').disabled = false;
                };
                reader.readAsDataURL(file);
            }
        }

        // SUBMIT DO UPLOAD DE ARQUIVO
        function handleFileUpload(e) {
            e.preventDefault();
            const fileInput = document.getElementById('avatarFileInput');
            if (!fileInput.files || !fileInput.files[0]) return;

            const btn = document.getElementById('btnSaveFilePhoto');
            btn.disabled = true;
            btn.innerHTML = '<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div> Enviando...';

            const formData = new FormData();
            formData.append('avatar_file', fileInput.files[0]);

            fetch('api/upload_avatar.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Erro ao enviar foto.');
                    btn.disabled = false;
                    btn.textContent = 'Salvar Foto Escolhida';
                }
            })
            .catch(err => {
                alert('Erro na conexão com o servidor.');
                btn.disabled = false;
                btn.textContent = 'Salvar Foto Escolhida';
            });
        }

        // SELEÇÃO DE PRESET ILUSTRADO
        function selectPresetAvatar(url) {
            if (typeof sounds !== 'undefined') sounds.playClick();
            fetch('api/upload_avatar.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ avatar_url: url })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message);
                }
            });
        }

        // PREVIEW E SALVAMENTO DE URL EXTERNA
        function previewUrlImage(url) {
            const box = document.getElementById('urlPreviewBox');
            const img = document.getElementById('urlPreviewImg');
            const status = document.getElementById('urlPreviewStatus');
            const btn = document.getElementById('btnSaveUrlPhoto');

            if (url && (url.startsWith('http://') || url.startsWith('https://'))) {
                box.classList.remove('hidden');
                img.src = url;
                status.textContent = 'Carregando pré-visualização...';
                status.className = 'text-[11px] font-bold text-slate-500';
                img.onload = function() {
                    status.textContent = 'Imagem carregada com sucesso!';
                    status.className = 'text-[11px] font-bold text-emerald-600';
                    btn.disabled = false;
                };
            } else {
                box.classList.add('hidden');
                btn.disabled = true;
            }
        }

        function handleUrlError() {
            const status = document.getElementById('urlPreviewStatus');
            const btn = document.getElementById('btnSaveUrlPhoto');
            status.textContent = 'Não foi possível carregar a imagem desta URL.';
            status.className = 'text-[11px] font-bold text-rose-600';
            btn.disabled = true;
        }

        function saveUrlAvatar() {
            const url = document.getElementById('avatarUrlInput').value.trim();
            if (!url) return;
            selectPresetAvatar(url);
        }

        // EQUIPAR INSÍGNIA POR XP
        function equipAvatarIcon(icon) {
            if (typeof sounds !== 'undefined') sounds.playClick();
            fetch('api/update_avatar.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ avatar_icon: icon })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message);
                }
            });
        }
    </script>
</body>
</html>

