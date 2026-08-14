<?php
/**
 * COMPONENTE REUTILIZÁVEL: SIDEBAR DE NAVEGAÇÃO LATERAL — HIPOGABARITO
 * Destaca em roxo (bg-indigo-600) a página ativa atual.
 */
$currentPage = $currentPage ?? basename($_SERVER['PHP_SELF']);

$userAvatar = (!empty($user['avatar']) && trim($user['avatar']) !== '') ? $user['avatar'] : 'assets/img/default_avatar.jpg';
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
    'bi-emoji-smile-fill' => 'Estudante Lendário'
];
$userBadgeIcon = $user['avatar_icon'] ?? 'bi-person-circle';
$userBadgeName = $userBadgeMap[$userBadgeIcon] ?? 'Estudante Padrão';
?>
<aside id="mainSidebar" class="lg:col-span-3 sticky top-20 space-y-3.5 z-30">
    <div class="bg-white rounded-3xl border-2 border-slate-200 p-4 sm:p-5 shadow-[0_4px_0_0_#e2e8f0]">

        <!-- MINI CARD DO PERFIL DO ALUNO (Oculto em profile.php para não duplicar dados) -->
        <?php if ($currentPage !== 'profile.php'): ?>
        <div class="sidebar-user-card flex items-center gap-3.5 p-3 mb-4 bg-slate-50 rounded-2xl border border-slate-200 transition-all">
            <img src="<?= htmlspecialchars($userAvatar) ?>?v=<?= time() ?>" onerror="this.onerror=null;this.src='assets/img/default_avatar.jpg'" class="w-11 h-11 rounded-full <?= htmlspecialchars($userFrame) ?> object-cover shadow-sm shrink-0">
            <div class="sidebar-text min-w-0 flex-1">
                <h3 class="font-outfit font-bold text-slate-900 text-sm truncate mb-0"><?= htmlspecialchars($user['name'] ?? 'Estudante') ?></h3>
                <div class="flex items-center gap-1 text-[11px] font-bold text-indigo-600 truncate mt-0.5" title="Título Equipado">
                    <i class="bi <?= htmlspecialchars($userBadgeIcon) ?>"></i>
                    <span class="truncate"><?= htmlspecialchars($userBadgeName) ?></span>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- LISTA DE LINKS DE NAVEGAÇÃO -->
        <nav class="space-y-1.5">
            <!-- 1. TRILHA DE ESTUDOS -->
            <?php $isDashboard = ($currentPage === 'dashboard.php'); ?>
            <a href="dashboard.php" title="Trilha de Estudos" class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl font-outfit font-bold text-sm transition-all <?= $isDashboard ? 'bg-indigo-600 text-white shadow-[0_4px_0_0_#312e81] font-extrabold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' ?>">
                <svg class="w-5 h-5 shrink-0 <?= $isDashboard ? 'text-amber-300' : 'text-slate-500' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
                <span class="sidebar-text truncate">Trilha de Estudos</span>
            </a>

            <!-- 2. RANKING DE AMIGOS -->
            <?php $isLeaderboard = ($currentPage === 'leaderboard.php'); ?>
            <a href="leaderboard.php" title="Ranking de Amigos" class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl font-outfit font-bold text-sm transition-all <?= $isLeaderboard ? 'bg-indigo-600 text-white shadow-[0_4px_0_0_#312e81] font-extrabold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' ?>">
                <svg class="w-5 h-5 shrink-0 <?= $isLeaderboard ? 'text-amber-300' : 'text-amber-500' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4m6 17v-5m0 0a2 2 0 100-4 2 2 0 000 4zm0 5a2 2 0 100-4 2 2 0 000 4zM6 17v-3m0 0a2 2 0 100-4 2 2 0 000 4zm0 3a2 2 0 100-4 2 2 0 000 4zM18 17v-3m0 0a2 2 0 100-4 2 2 0 000 4zm0 3a2 2 0 100-4 2 2 0 000 4z"/>
                </svg>
                <span class="sidebar-text truncate">Ranking de Amigos</span>
            </a>

            <!-- 3. SIMULADOS CRONOMETRADOS -->
            <?php $isSimulado = ($currentPage === 'simulado.php'); ?>
            <a href="simulado.php" title="Simulados Cronometrados" class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl font-outfit font-bold text-sm transition-all <?= $isSimulado ? 'bg-indigo-600 text-white shadow-[0_4px_0_0_#312e81] font-extrabold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' ?>">
                <i class="bi bi-clock-history text-lg shrink-0 <?= $isSimulado ? 'text-amber-300' : 'text-indigo-600' ?>"></i>
                <span class="sidebar-text truncate">Simulados Cronometrados</span>
            </a>

            <!-- 4. GUIA DE CURSOS & NOTAS -->
            <?php $isCourseGuide = ($currentPage === 'course_guide.php'); ?>
            <a href="course_guide.php" title="Guia de Cursos & Notas" class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl font-outfit font-bold text-sm transition-all <?= $isCourseGuide ? 'bg-indigo-600 text-white shadow-[0_4px_0_0_#312e81] font-extrabold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' ?>">
                <i class="bi bi-mortarboard-fill text-lg shrink-0 <?= $isCourseGuide ? 'text-amber-300' : 'text-indigo-600' ?>"></i>
                <span class="sidebar-text truncate">Guia de Cursos & Notas</span>
            </a>

            <!-- 5. MEU PERFIL -->
            <?php $isProfile = ($currentPage === 'profile.php'); ?>
            <a href="profile.php" title="Meu Perfil" class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl font-outfit font-bold text-sm transition-all <?= $isProfile ? 'bg-indigo-600 text-white shadow-[0_4px_0_0_#312e81] font-extrabold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' ?>">
                <svg class="w-5 h-5 shrink-0 <?= $isProfile ? 'text-amber-300' : 'text-indigo-600' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="sidebar-text truncate">Meu Perfil</span>
            </a>

            <div class="pt-2 border-t border-slate-200 mt-2">
                <a href="api/auth.php?action=logout" title="Sair" class="flex items-center gap-3 px-3.5 py-2 rounded-2xl font-outfit font-bold text-xs text-slate-400 hover:text-rose-600 transition-all">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span class="sidebar-text truncate">Sair</span>
                </a>
            </div>
        </nav>
    </div>
</aside>

<script>
    // Limpar qualquer estado legado de sidebar retraída no navegador do usuário
    try {
        localStorage.removeItem('sidebar_collapsed');
    } catch(e) {}
</script>
