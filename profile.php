<?php
require_once __DIR__ . '/config/db.php';
checkAuth();

$userId = $_SESSION['user_id'];

// Estatísticas do Usuário
$stmtUser = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmtUser->execute([$userId]);
$user = $stmtUser->fetch();
$userAvatar = !empty($user['avatar']) ? $user['avatar'] : 'assets/img/default_avatar.jpg';
$userFrame = !empty($user['avatar_frame']) ? $user['avatar_frame'] : 'frame-indigo';

$currentAvatar = $user['avatar_icon'] ?? 'bi-person-circle';
$userXp = (int) ($user['xp'] ?? 0);

$frames = [
    ['key' => 'frame-indigo',  'name' => 'Índigo Padrão',       'xp' => 0,    'desc' => 'Borda violeta clássica'],
    ['key' => 'frame-emerald', 'name' => 'Esmeralda',           'xp' => 100,  'desc' => 'Verde vivo da vitória'],
    ['key' => 'frame-cyan',    'name' => 'Azul Ciano',          'xp' => 300,  'desc' => 'Brilho ciano oceânico'],
    ['key' => 'frame-purple',  'name' => 'Roxo Místico',        'xp' => 500,  'desc' => 'Aura de sabedoria'],
    ['key' => 'frame-rose',    'name' => 'Rosa Neon',            'xp' => 750,  'desc' => 'Energia vibrante'],
    ['key' => 'frame-rainbow', 'name' => '🦛 Arco-Íris Lendária', 'xp' => 1000, 'desc' => 'Efeito multicor animado contínuo (1000 XP)'],
    ['key' => 'frame-gold',    'name' => '👑 Dourada Suprema',   'xp' => 1500, 'desc' => 'Brilho metálico ouro com pulso (1500 XP)'],
];

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
    ['icon' => 'bi-emoji-smile-fill', 'name' => 'Estudante Lendário', 'xp' => 1500]
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

$pageTitle = 'Meu Perfil & Avatares — HipoGabarito';
require_once __DIR__ . '/includes/header.php';
?>
    <style>
        img.frame-indigo, .frame-indigo { outline: 4px solid #6842C2 !important; outline-offset: -2px !important; box-shadow: 0 0 14px rgba(104,66,194,0.7) !important; border-radius: 9999px !important; }
        img.frame-emerald, .frame-emerald { outline: 4px solid #10b981 !important; outline-offset: -2px !important; box-shadow: 0 0 14px rgba(16,185,129,0.7) !important; border-radius: 9999px !important; }
        img.frame-cyan, .frame-cyan { outline: 4px solid #06b6d4 !important; outline-offset: -2px !important; box-shadow: 0 0 16px rgba(6,182,212,0.75) !important; border-radius: 9999px !important; }
        img.frame-purple, .frame-purple { outline: 4px solid #a855f7 !important; outline-offset: -2px !important; box-shadow: 0 0 16px rgba(168,85,247,0.75) !important; border-radius: 9999px !important; }
        img.frame-rose, .frame-rose { outline: 4px solid #f43f5e !important; outline-offset: -2px !important; box-shadow: 0 0 18px rgba(244,63,94,0.8) !important; border-radius: 9999px !important; }
        img.frame-rainbow, .frame-rainbow { outline: 4.5px solid #ff0055 !important; outline-offset: -2px !important; border-radius: 9999px !important; animation: rainbowRgbFast 1.8s linear infinite !important; }
        img.frame-gold, .frame-gold { outline: 4px solid #f59e0b !important; outline-offset: -2px !important; border-radius: 9999px !important; animation: goldOutlineCycle 2.2s ease-in-out infinite alternate !important; }
        @keyframes rainbowRgbFast {
            0%   { outline-color: #ff0055 !important; box-shadow: 0 0 18px #ff0055, 0 0 32px rgba(255,0,85,0.85) !important; }
            14%  { outline-color: #ff6600 !important; box-shadow: 0 0 18px #ff6600, 0 0 32px rgba(255,102,0,0.85) !important; }
            28%  { outline-color: #ffee00 !important; box-shadow: 0 0 18px #ffee00, 0 0 32px rgba(255,238,0,0.85) !important; }
            42%  { outline-color: #00ff66 !important; box-shadow: 0 0 18px #00ff66, 0 0 32px rgba(0,255,102,0.85) !important; }
            57%  { outline-color: #00d5ff !important; box-shadow: 0 0 18px #00d5ff, 0 0 32px rgba(0,213,255,0.85) !important; }
            71%  { outline-color: #9900ff !important; box-shadow: 0 0 18px #9900ff, 0 0 32px rgba(153,0,255,0.85) !important; }
            85%  { outline-color: #ff00cc !important; box-shadow: 0 0 18px #ff00cc, 0 0 32px rgba(255,0,204,0.85) !important; }
            100% { outline-color: #ff0055 !important; box-shadow: 0 0 18px #ff0055, 0 0 32px rgba(255,0,85,0.85) !important; }
        }
        @keyframes goldOutlineCycle {
            0% { outline-color: #fbbf24 !important; box-shadow: 0 0 14px #f59e0b, 0 0 22px rgba(251,191,36,0.6) !important; }
            50% { outline-color: #fef08a !important; box-shadow: 0 0 26px #fbbf24, 0 0 38px rgba(245,158,11,0.9) !important; }
            100% { outline-color: #d97706 !important; box-shadow: 0 0 16px #d97706, 0 0 25px rgba(217,119,6,0.7) !important; }
        }
    </style>

    <!-- LAYOUT PRINCIPAL DO PERFIL -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- SIDEBAR MODULAR COM DESTAQUE EM ROXO -->
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

            <!-- CONTEÚDO PRINCIPAL: PERFIL & AVATARES -->
            <section class="lg:col-span-9 space-y-6">
                <!-- CARD RESUMO DO PERFIL COM OPÇÃO DE FOTO -->
                <div class="bg-white rounded-3xl border-2 border-slate-200 p-5 shadow-[0_3px_0_0_#e2e8f0] flex flex-col sm:flex-row items-center gap-5">
                    <!-- FOTO COM BADGE DE CÂMERA CLICÁVEL -->
                    <div class="relative group cursor-pointer" onclick="openPhotoModal()" title="Clique para alterar sua foto de perfil">
                        <img src="<?= htmlspecialchars($userAvatar) ?>?v=<?= time() ?>" id="profileAvatarImg" onerror="this.onerror=null;this.src='assets/img/default_avatar.jpg'" class="w-20 h-20 rounded-full <?= htmlspecialchars($userFrame) ?> object-cover group-hover:opacity-90 transition-opacity">
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

                <!-- MOLDURAS DO AVATAR POR XP -->
                <div class="bg-white rounded-3xl border-2 border-slate-200 p-5 shadow-[0_3px_0_0_#e2e8f0]">
                    <div class="mb-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200 mb-1">
                            <i class="bi bi-bounding-box-circles text-indigo-600"></i> NOVO RECURSO
                        </span>
                        <h3 class="font-outfit font-extrabold text-lg text-slate-900 mb-0.5">Molduras de Avatar por XP</h3>
                        <p class="text-xs text-slate-500 font-medium mb-0">Desbloqueie molduras incríveis para sua foto de perfil conforme ganha XP! Atingir <strong>1000 XP</strong> libera a <strong>Arco-Íris Animada</strong> e <strong>1500 XP</strong> libera a <strong>Dourada Suprema</strong>!</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        <?php foreach ($frames as $fr): ?>
                            <?php 
                                $isUnlockedFrame = ($userXp >= $fr['xp']);
                                $isEquippedFrame = ($userFrame === $fr['key']);
                            ?>
                            <div class="bg-slate-50 rounded-2xl border-2 p-3.5 text-center transition-all <?= 
                                $isEquippedFrame ? 'border-indigo-600 bg-indigo-50/50 shadow-sm' : ($isUnlockedFrame ? 'border-slate-200 hover:border-indigo-400 hover:shadow-md cursor-pointer' : 'border-slate-200 opacity-60')
                            ?>" <?= $isUnlockedFrame ? "onclick=\"equipAvatarFrame('{$fr['key']}')\"" : '' ?>>
                                
                                <div class="relative w-16 h-16 mx-auto mb-2 flex items-center justify-center">
                                    <img src="<?= htmlspecialchars($userAvatar) ?>?v=<?= time() ?>" onerror="this.onerror=null;this.src='assets/img/default_avatar.jpg'" class="w-14 h-14 rounded-full <?= $fr['key'] ?> object-cover">
                                </div>

                                <h4 class="font-outfit font-bold text-xs text-slate-900 mb-0.5"><?= htmlspecialchars($fr['name']) ?></h4>
                                <p class="text-[10px] text-slate-500 font-medium mb-2 leading-tight"><?= htmlspecialchars($fr['desc']) ?></p>

                                <?php if ($isEquippedFrame): ?>
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-[11px] font-extrabold bg-indigo-600 text-white shadow-sm">Equipado</span>
                                <?php elseif ($isUnlockedFrame): ?>         <?php elseif ($isUnlockedFrame): ?>
                                    <button class="w-full py-1 px-2.5 rounded-xl font-outfit font-extrabold text-xs bg-indigo-50 text-indigo-700 hover:bg-indigo-600 hover:text-white border border-indigo-200 transition-all">Equipar Moldura</button>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[11px] font-bold bg-slate-200 text-slate-600"><i class="bi bi-lock-fill"></i> <?= $fr['xp'] ?> XP</span>
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
                        ['name' => 'Logo HipoGabarito', 'url' => 'assets/img/hipogabarito_logo.png'],
                        ['name' => 'Robô Gamer', 'url' => 'assets/img/avatars/avatar_robot.jpg'],
                        ['name' => 'Estudante Focado', 'url' => 'assets/img/avatars/avatar_student.jpg'],
                        ['name' => 'Astronauta', 'url' => 'assets/img/avatars/avatar_astronaut.jpg'],
                        ['name' => 'Cientista', 'url' => 'assets/img/avatars/avatar_scientist.jpg'],
                        ['name' => 'Mago do Saber', 'url' => 'assets/img/avatars/avatar_wizard.jpg'],
                        ['name' => 'Ninja', 'url' => 'assets/img/avatars/avatar_ninja.jpg'],
                        ['name' => 'Coruja da Sabedoria', 'url' => 'assets/img/avatars/avatar_owl.jpg'],
                        ['name' => 'Leão da Aprovação', 'url' => 'assets/img/avatars/avatar_lion.jpg'],
                        ['name' => 'Medicina', 'url' => 'assets/img/avatars/avatar_doctor.jpg'],
                        ['name' => 'Engenharia', 'url' => 'assets/img/avatars/avatar_engineer.jpg'],
                        ['name' => 'Direito', 'url' => 'assets/img/avatars/avatar_lawyer.jpg'],
                        ['name' => 'Foguete', 'url' => 'assets/img/avatars/avatar_rocket.jpg']
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

        // EQUIPAR MOLDURA DO AVATAR POR XP
        function equipAvatarFrame(frameKey) {
            if (typeof sounds !== 'undefined') sounds.playClick();
            fetch('api/update_frame.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ frame: frameKey })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Moldura Equipada!',
                            text: data.message,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => window.location.reload());
                    } else {
                        window.location.reload();
                    }
                } else {
                    alert(data.message);
                }
            })
            .catch(err => {
                alert('Erro ao conectar com o servidor.');
            });
        }
    </script>
    <?php require_once __DIR__ . '/includes/footer.php'; ?>

