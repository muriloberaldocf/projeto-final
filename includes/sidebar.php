<?php
/**
 * COMPONENTE REUTILIZÁVEL: SIDEBAR LATERAL (DESKTOP) & BOTTOM NAVIGATION (MOBILE / TABLET) — HIPOGABARITO
 * - Desktop (>= 1024px): Sidebar lateral elegante sticky col-span-3.
 * - Mobile / Tablet (< 1024px): Bottom Navigation Bar fixa estilo App Duolingo na base da tela.
 */
$currentPage = $currentPage ?? basename($_SERVER['PHP_SELF']);

$userAvatar = (!empty($user['avatar']) && trim($user['avatar']) !== '') ? $user['avatar'] : 'assets/img/default_avatar.jpg';
$userFrame = !empty($user['avatar_frame']) ? $user['avatar_frame'] : 'frame-indigo';

$badgeCatalog = getAvatarBadgesCatalog();
$userBadgeIcon = $user['avatar_icon'] ?? 'bi-person-circle';
$userBadgeName = $badgeCatalog[$userBadgeIcon]['name'] ?? 'Estudante Padrão';

$isDashboard = ($currentPage === 'dashboard.php');
$isSimulado = ($currentPage === 'simulado.php');
$isRedacao = ($currentPage === 'redacao.php' || $currentPage === 'redacao_resultado.php');
$isCourseGuide = in_array($currentPage, ['course_guide.php', 'guia_concorrencia.php', 'provas_oficiais.php']);
$isMetricas = ($currentPage === 'metricas.php');
$isLeaderboard = ($currentPage === 'leaderboard.php');
$isProfile = ($currentPage === 'profile.php');
?>

<!-- 1. SIDEBAR DESKTOP (VISÍVEL APENAS EM TELAS GRANDES >= 1024px) -->
<aside id="mainSidebar" class="hidden lg:block lg:col-span-3 sticky top-20 z-30">
    <div class="bg-white rounded-3xl border-2 border-slate-200 p-3 sm:p-4 shadow-[0_4px_0_0_#e2e8f0]">
        <!-- LISTA DE LINKS DE NAVEGAÇÃO COMPACTA & MODERNA -->
        <nav class="space-y-1.5">
            <!-- 1. TRILHA DE ESTUDOS -->
            <a href="dashboard.php" title="Trilha de Estudos" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl font-outfit font-extrabold text-sm transition-all <?= $isDashboard ? 'bg-[#6842C2] text-white shadow-[0_4px_0_0_#3D256B]' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' ?>">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 <?= $isDashboard ? 'bg-white/20 text-white' : 'bg-[#f1eff5] text-[#6842C2]' ?>">
                    <i class="bi bi-map-fill text-base"></i>
                </div>
                <span class="truncate">Trilha de Estudos</span>
            </a>

            <!-- 2. SIMULADOS -->
            <a href="simulado.php" title="Simulados Cronometrados" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl font-outfit font-extrabold text-sm transition-all <?= $isSimulado ? 'bg-[#6842C2] text-white shadow-[0_4px_0_0_#3D256B]' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' ?>">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 <?= $isSimulado ? 'bg-white/20 text-white' : 'bg-amber-50 text-amber-600' ?>">
                    <i class="bi bi-stopwatch-fill text-base"></i>
                </div>
                <span class="truncate">Simulados</span>
            </a>

            <!-- 3. REDAÇÃO IA -->
            <a href="redacao.php" title="Redação com Correção por Inteligência Artificial" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl font-outfit font-extrabold text-sm transition-all <?= $isRedacao ? 'bg-[#6842C2] text-white shadow-[0_4px_0_0_#3D256B]' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' ?>">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 <?= $isRedacao ? 'bg-white/20 text-white' : 'bg-purple-50 text-purple-600' ?>">
                    <i class="bi bi-pen-fill text-base"></i>
                </div>
                <span class="truncate">Redação com IA</span>
            </a>

            <!-- 4. GUIA SISU & CURSOS -->
            <a href="course_guide.php" title="Guia de Cursos, Notas de Corte do SiSU e Provas Anteriores" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl font-outfit font-extrabold text-sm transition-all <?= $isCourseGuide ? 'bg-[#6842C2] text-white shadow-[0_4px_0_0_#3D256B]' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' ?>">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 <?= $isCourseGuide ? 'bg-white/20 text-white' : 'bg-blue-50 text-blue-600' ?>">
                    <i class="bi bi-mortarboard-fill text-base"></i>
                </div>
                <span class="truncate">Guia SiSU & Cursos</span>
            </a>

            <!-- 5. RAIO-X & DESEMPENHO -->
            <a href="metricas.php" title="Métricas de Desempenho e Estatísticas Pedagógicas" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl font-outfit font-extrabold text-sm transition-all <?= $isMetricas ? 'bg-[#6842C2] text-white shadow-[0_4px_0_0_#3D256B]' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' ?>">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 <?= $isMetricas ? 'bg-white/20 text-white' : 'bg-emerald-50 text-emerald-600' ?>">
                    <i class="bi bi-graph-up-arrow text-base"></i>
                </div>
                <span class="truncate">Desempenho</span>
            </a>

            <!-- 6. RANKING DE AMIGOS -->
            <a href="leaderboard.php" title="Ranking de Amigos & Mural de Conquistas" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl font-outfit font-extrabold text-sm transition-all <?= $isLeaderboard ? 'bg-[#6842C2] text-white shadow-[0_4px_0_0_#3D256B]' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' ?>">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 <?= $isLeaderboard ? 'bg-white/20 text-white' : 'bg-amber-50 text-amber-500' ?>">
                    <i class="bi bi-trophy-fill text-base"></i>
                </div>
                <span class="truncate">Ranking de Amigos</span>
            </a>

            <!-- DIVISÓRIA COM PERFIL E SAIR -->
            <div class="pt-2 border-t border-slate-200 mt-2 space-y-1">
                <a href="profile.php" title="Meu Perfil" class="flex items-center gap-3 px-3.5 py-2 rounded-2xl font-outfit font-extrabold text-xs transition-all <?= $isProfile ? 'bg-[#6842C2] text-white shadow-[0_3px_0_0_#3D256B]' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-900' ?>">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center shrink-0 <?= $isProfile ? 'text-white' : 'text-[#6842C2]' ?>">
                        <i class="bi bi-person-fill text-sm"></i>
                    </div>
                    <span class="truncate">Meu Perfil</span>
                </a>

                <a href="api/auth.php?action=logout" title="Sair da Conta" class="flex items-center gap-3 px-3.5 py-2 rounded-2xl font-outfit font-bold text-xs text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center shrink-0">
                        <i class="bi bi-box-arrow-right text-sm"></i>
                    </div>
                    <span class="truncate">Sair</span>
                </a>
            </div>
        </nav>
    </div>
</aside>

<!-- 2. BOTTOM NAVIGATION BAR (APP MOBILE & TABLET: < 1024px) -->
<nav id="bottomNavBar" class="lg:hidden fixed bottom-0 inset-x-0 z-50 bg-white/95 dark:bg-[#181f2c]/95 backdrop-blur-md border-t-2 border-slate-200 dark:border-[#384660] shadow-[0_-4px_20px_rgba(0,0,0,0.08)] px-2 py-1.5 safe-area-bottom nav-visible">
    <div class="max-w-md mx-auto grid grid-cols-6 gap-1 items-center">
        <!-- TRILHA -->
        <a href="dashboard.php" class="flex flex-col items-center justify-center py-1 rounded-xl transition-all <?= $isDashboard ? 'text-[#6842C2] dark:text-[#a5b4fc] font-black' : 'text-slate-400 dark:text-slate-400 hover:text-slate-600' ?>">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center <?= $isDashboard ? 'bg-[#6842C2]/10 dark:bg-[#6842C2]/30 shadow-xs' : '' ?>">
                <i class="bi <?= $isDashboard ? 'bi-map-fill text-lg' : 'bi-map text-base' ?>"></i>
            </div>
            <span class="text-[9px] font-outfit uppercase tracking-tight mt-0.5 truncate">Trilha</span>
        </a>

        <!-- SIMULADOS -->
        <a href="simulado.php" class="flex flex-col items-center justify-center py-1 rounded-xl transition-all <?= $isSimulado ? 'text-[#6842C2] dark:text-[#a5b4fc] font-black' : 'text-slate-400 dark:text-slate-400 hover:text-slate-600' ?>">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center <?= $isSimulado ? 'bg-[#6842C2]/10 dark:bg-[#6842C2]/30 shadow-xs' : '' ?>">
                <i class="bi <?= $isSimulado ? 'bi-stopwatch-fill text-lg' : 'bi-stopwatch text-base' ?>"></i>
            </div>
            <span class="text-[9px] font-outfit uppercase tracking-tight mt-0.5 truncate">Simulado</span>
        </a>

        <!-- REDAÇÃO -->
        <a href="redacao.php" class="flex flex-col items-center justify-center py-1 rounded-xl transition-all <?= $isRedacao ? 'text-[#6842C2] dark:text-[#a5b4fc] font-black' : 'text-slate-400 dark:text-slate-400 hover:text-slate-600' ?>">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center <?= $isRedacao ? 'bg-[#6842C2]/10 dark:bg-[#6842C2]/30 shadow-xs' : '' ?>">
                <i class="bi <?= $isRedacao ? 'bi-pen-fill text-lg' : 'bi-pen text-base' ?>"></i>
            </div>
            <span class="text-[9px] font-outfit uppercase tracking-tight mt-0.5 truncate">Redação</span>
        </a>

        <!-- SISU & CURSOS -->
        <a href="course_guide.php" class="flex flex-col items-center justify-center py-1 rounded-xl transition-all <?= $isCourseGuide ? 'text-[#6842C2] dark:text-[#a5b4fc] font-black' : 'text-slate-400 dark:text-slate-400 hover:text-slate-600' ?>">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center <?= $isCourseGuide ? 'bg-[#6842C2]/10 dark:bg-[#6842C2]/30 shadow-xs' : '' ?>">
                <i class="bi <?= $isCourseGuide ? 'bi-mortarboard-fill text-lg' : 'bi-mortarboard text-base' ?>"></i>
            </div>
            <span class="text-[9px] font-outfit uppercase tracking-tight mt-0.5 truncate">SiSU</span>
        </a>

        <!-- AMIGOS -->
        <a href="leaderboard.php" class="flex flex-col items-center justify-center py-1 rounded-xl transition-all <?= $isLeaderboard ? 'text-[#6842C2] dark:text-[#a5b4fc] font-black' : 'text-slate-400 dark:text-slate-400 hover:text-slate-600' ?>">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center <?= $isLeaderboard ? 'bg-[#6842C2]/10 dark:bg-[#6842C2]/30 shadow-xs' : '' ?>">
                <i class="bi <?= $isLeaderboard ? 'bi-trophy-fill text-lg' : 'bi-trophy text-base' ?>"></i>
            </div>
            <span class="text-[9px] font-outfit uppercase tracking-tight mt-0.5 truncate">Amigos</span>
        </a>

        <!-- PERFIL & MAIS -->
        <a href="profile.php" class="flex flex-col items-center justify-center py-1 rounded-xl transition-all <?= ($isProfile || $isMetricas) ? 'text-[#6842C2] dark:text-[#a5b4fc] font-black' : 'text-slate-400 dark:text-slate-400 hover:text-slate-600' ?>">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center <?= ($isProfile || $isMetricas) ? 'bg-[#6842C2]/10 dark:bg-[#6842C2]/30 shadow-xs' : '' ?>">
                <i class="bi <?= ($isProfile || $isMetricas) ? 'bi-person-circle text-lg' : 'bi-person text-base' ?>"></i>
            </div>
            <span class="text-[9px] font-outfit uppercase tracking-tight mt-0.5 truncate">Perfil</span>
        </a>
    </div>
</nav>

<script>
    // Limpar qualquer estado legado de sidebar retraída no navegador do usuário
    try {
        localStorage.removeItem('sidebar_collapsed');
    } catch(e) {}

    // AUTO-HIDE BOTTOM NAVBAR NO SCROLL (Estilo Instagram / Duolingo / Twitter Mobile)
    (function() {
        const bottomNav = document.getElementById('bottomNavBar');
        if (!bottomNav) return;

        let lastScrollY = window.pageYOffset || document.documentElement.scrollTop;
        let ticking = false;
        const threshold = 8; // Sensibilidade do toque / scroll em pixels

        function updateBottomBarVisibility() {
            const currentScrollY = window.pageYOffset || document.documentElement.scrollTop;
            const diff = currentScrollY - lastScrollY;

            // Se o usuário estiver no topo absoluto da tela, sempre mostra a barra
            if (currentScrollY <= 20) {
                bottomNav.classList.remove('nav-hidden');
                bottomNav.classList.add('nav-visible');
            } else if (Math.abs(diff) > threshold) {
                if (diff > 0) {
                    // Rolando para BAIXO -> Efeito visual elegante: desliza para fora com fade out
                    bottomNav.classList.remove('nav-visible');
                    bottomNav.classList.add('nav-hidden');
                } else {
                    // Rolando para CIMA -> Efeito de retorno suave com fade in e scale 1
                    bottomNav.classList.remove('nav-hidden');
                    bottomNav.classList.add('nav-visible');
                }
                lastScrollY = currentScrollY;
            }

            ticking = false;
        }

        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(updateBottomBarVisibility);
                ticking = true;
            }
        }, { passive: true });
    })();
</script>
