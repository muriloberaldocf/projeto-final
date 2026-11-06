<?php
/**
 * COMPONENTE REUTILIZÁVEL: HEADER & HUD SUPERIOR — HIPOGABARITO
 */
require_once __DIR__ . '/../config/db.php';
checkAuth();

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $streakStatus = syncUserStreak($pdo, $userId);

    $stmtUser = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmtUser->execute([$userId]);
    $user = $stmtUser->fetch();

    $userStreakDays = $streakStatus['streak_days'];
    $isStreakActive = $streakStatus['is_active_today'];
} else {
    $userStreakDays = 0;
    $isStreakActive = false;
}

$userAvatar = (!empty($user['avatar']) && trim($user['avatar']) !== '') ? $user['avatar'] : 'assets/img/default_avatar.jpg';
$userFrame = !empty($user['avatar_frame']) ? $user['avatar_frame'] : 'frame-indigo';

$badgeCatalog = getAvatarBadgesCatalog();
$userBadgeIcon = $user['avatar_icon'] ?? 'bi-person-circle';
$userBadgeName = $badgeCatalog[$userBadgeIcon]['name'] ?? 'Estudante Padrão';

$pageTitle = $pageTitle ?? 'HipoGabarito — Plataforma Gamificada de Estudos';
?>
<!DOCTYPE html>
<html lang="pt-BR" class="h-full bg-[#F1EFF5]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= htmlspecialchars(getCsrfToken()) ?>">
    <title><?= htmlspecialchars($pageTitle) ?></title>

    <!-- ANTI-FLICKER: Aplicar tema salvo ANTES de qualquer renderização -->
    <script>
        (function() {
            var t = localStorage.getItem('hipogabarito_theme');
            if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark', 'no-transition');
            }
        })();
    </script>

    <!-- TAILWIND CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f1eff5',
                            100: '#e4ddf7',
                            500: '#a98be0',
                            600: '#6842c2',
                            700: '#5832b2',
                            800: '#482792',
                            900: '#3d256b'
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
    <link rel="stylesheet" href="assets/css/dark-mode.css?v=<?= time() ?>">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Remover classe no-transition após renderizar -->
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.documentElement.classList.remove('no-transition');
            }, 50);
        });
    </script>
</head>
<body class="min-h-full font-sans antialiased text-[#17151C] bg-[#F1EFF5] selection:bg-[#6842C2] selection:text-white flex flex-col min-h-screen">

    <!-- TOP HEADER / HUD DE JOGADOR TAILWIND -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b-2 border-slate-200 py-2.5 sm:py-3 shadow-sm">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 flex items-center justify-between">
            <a href="dashboard.php" class="flex items-center gap-2 group text-decoration-none shrink-0">
                <img src="assets/img/hipogabarito_logo.png?v=<?= time() ?>" alt="HipoGabarito Logo" class="hipo-brand-logo h-8 sm:h-10 w-auto object-contain group-hover:scale-105 transition-transform">
            </a>

            <!-- HUD STATUS DO ALUNO -->
            <div class="flex items-center gap-1.5 sm:gap-3">
                <!-- TOGGLE DARK MODE -->
                <button id="themeToggleBtn" onclick="toggleTheme()" class="theme-toggle-btn shrink-0" title="Alternar Modo Escuro">
                    <i class="bi bi-moon-stars-fill text-sm sm:text-base" id="themeIcon"></i>
                </button>

                <!-- SININHO DE NOTIFICAÇÕES -->
                <div class="relative">
                    <button id="notifBellBtn" onclick="toggleNotifDropdown()" class="relative p-1.5 sm:p-2 rounded-xl sm:rounded-2xl bg-white border-2 border-slate-200 shadow-[0_2px_0_0_#e2e8f0] hover:bg-slate-50 text-slate-600 hover:text-indigo-600 transition-all flex items-center justify-center shrink-0" title="Notificações">
                        <i class="bi bi-bell-fill text-sm sm:text-base"></i>
                        <span id="notifBadge" class="hidden absolute -top-1.5 -right-1.5 bg-rose-500 text-white text-[9px] sm:text-[10px] font-black w-4 h-4 sm:w-4.5 sm:h-4.5 px-1 rounded-full flex items-center justify-center shadow-sm animate-pulse">0</span>
                    </button>

                    <!-- DROPDOWN DE NOTIFICAÇÕES (RESPONSIVO PARA NÃO CORTAR NO MOBILE) -->
                    <div id="notifDropdown" class="hidden fixed inset-x-3 top-14 sm:inset-x-auto sm:right-0 sm:absolute sm:top-auto sm:mt-2 w-auto sm:w-96 bg-white rounded-3xl border-2 border-slate-200 shadow-2xl z-50 p-4 animate-in fade-in zoom-in-95 duration-150">
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

                <?php if ($isStreakActive): ?>
                <!-- Ofensiva Acesa (tarefa realizada hoje) -->
                <div class="flex items-center gap-1 sm:gap-2 bg-amber-50 px-2 sm:px-4 py-1 sm:py-1.5 rounded-xl sm:rounded-2xl border-2 border-amber-300 shadow-[0_2px_0_0_#f59e0b] shrink-0" title="Ofensiva ativa hoje! <?= $userStreakDays ?> dia(s) consecutivo(s)">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-amber-500 animate-bounce" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-1.048c-2.5 1.6-4.5 4.5-4.5 7.5 0 .285.021.564.062.836A4.99 4.99 0 014 6.5a1 1 0 00-1.92.4C2.5 9.5 4.5 12 7 12c.3 0 .59-.03.873-.087.6.93 1.556 1.6 2.685 1.776A5.002 5.002 0 0015 9c0-3.5-1.5-5.5-2.605-6.447z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-outfit font-extrabold text-amber-700 text-xs sm:text-sm whitespace-nowrap"><?= $userStreakDays ?><span class="hidden sm:inline"> dias</span></span>
                </div>
                <?php else: ?>
                <!-- Ofensiva Apagada (nenhuma tarefa realizada hoje ainda) -->
                <div class="flex items-center gap-1 sm:gap-2 bg-slate-100/90 px-2 sm:px-4 py-1 sm:py-1.5 rounded-xl sm:rounded-2xl border-2 border-slate-200 shadow-[0_2px_0_0_#cbd5e1] opacity-75 hover:opacity-100 transition shrink-0" title="Complete uma lição ou simulado hoje para acender a sua ofensiva!">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-1.048c-2.5 1.6-4.5 4.5-4.5 7.5 0 .285.021.564.062.836A4.99 4.99 0 014 6.5a1 1 0 00-1.92.4C2.5 9.5 4.5 12 7 12c.3 0 .59-.03.873-.087.6.93 1.556 1.6 2.685 1.776A5.002 5.002 0 0015 9c0-3.5-1.5-5.5-2.605-6.447z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-outfit font-extrabold text-slate-500 text-xs sm:text-sm whitespace-nowrap"><?= $userStreakDays ?><span class="hidden sm:inline"> dias</span></span>
                </div>
                <?php endif; ?>

                <!-- Pílula de XP -->
                <div class="flex items-center gap-1 sm:gap-2 bg-white px-2 sm:px-4 py-1 sm:py-1.5 rounded-xl sm:rounded-2xl border-2 border-slate-200 shadow-[0_2px_0_0_#e2e8f0] shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-outfit font-extrabold text-indigo-600 text-xs sm:text-sm whitespace-nowrap"><?= htmlspecialchars($user['xp'] ?? 0) ?><span class="hidden sm:inline"> XP</span></span>
                </div>

                <!-- Avatar do Perfil (Visível apenas em Desktop lg+, pois no Mobile/Tablet o perfil é acessado diretamente na Bottom Bar) -->
                <a href="profile.php" class="hidden lg:inline-flex ml-1 group shrink-0" title="Meu Perfil">
                    <img src="<?= htmlspecialchars($userAvatar) ?>?v=<?= time() ?>" alt="Avatar" onerror="this.onerror=null;this.src='assets/img/default_avatar.jpg'" class="w-10 h-10 rounded-full <?= htmlspecialchars($userFrame) ?> object-cover group-hover:scale-105 transition-transform shadow-sm">
                </a>
            </div>
        </div>
    </header>
