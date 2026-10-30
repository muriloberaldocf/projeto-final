<?php
/**
 * COMPONENTE REUTILIZÁVEL: SIDEBAR DE NAVEGAÇÃO LATERAL — HIPOGABARITO
 * Destaca em roxo (bg-indigo-600) a página ativa atual.
 */
$currentPage = $currentPage ?? basename($_SERVER['PHP_SELF']);

$userAvatar = (!empty($user['avatar']) && trim($user['avatar']) !== '') ? $user['avatar'] : 'assets/img/default_avatar.jpg';
$userFrame = !empty($user['avatar_frame']) ? $user['avatar_frame'] : 'frame-indigo';

$badgeCatalog = getAvatarBadgesCatalog();
$userBadgeIcon = $user['avatar_icon'] ?? 'bi-person-circle';
$userBadgeName = $badgeCatalog[$userBadgeIcon]['name'] ?? 'Estudante Padrão';
?>
<aside id="mainSidebar" class="lg:col-span-3 sticky top-20 z-30">
    <div class="bg-white rounded-3xl border-2 border-slate-200 p-3 sm:p-4 shadow-[0_4px_0_0_#e2e8f0]">
        <!-- LISTA DE LINKS DE NAVEGAÇÃO COMPACTA & MODERNA -->
        <nav class="space-y-1.5">
            <!-- 1. TRILHA DE ESTUDOS -->
            <?php $isDashboard = ($currentPage === 'dashboard.php'); ?>
            <a href="dashboard.php" title="Trilha de Estudos" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl font-outfit font-extrabold text-sm transition-all <?= $isDashboard ? 'bg-indigo-600 text-white shadow-[0_4px_0_0_#312e81]' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' ?>">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 <?= $isDashboard ? 'bg-white/20 text-white' : 'bg-indigo-50 text-indigo-600' ?>">
                    <i class="bi bi-map-fill text-base"></i>
                </div>
                <span class="truncate">Trilha de Estudos</span>
            </a>

            <!-- 2. SIMULADOS -->
            <?php $isSimulado = ($currentPage === 'simulado.php'); ?>
            <a href="simulado.php" title="Simulados Cronometrados" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl font-outfit font-extrabold text-sm transition-all <?= $isSimulado ? 'bg-indigo-600 text-white shadow-[0_4px_0_0_#312e81]' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' ?>">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 <?= $isSimulado ? 'bg-white/20 text-white' : 'bg-amber-50 text-amber-600' ?>">
                    <i class="bi bi-stopwatch-fill text-base"></i>
                </div>
                <span class="truncate">Simulados</span>
            </a>

            <!-- 3. REDAÇÃO IA -->
            <?php $isRedacao = ($currentPage === 'redacao.php' || $currentPage === 'redacao_resultado.php'); ?>
            <a href="redacao.php" title="Redação com Correção por Inteligência Artificial" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl font-outfit font-extrabold text-sm transition-all <?= $isRedacao ? 'bg-indigo-600 text-white shadow-[0_4px_0_0_#312e81]' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' ?>">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 <?= $isRedacao ? 'bg-white/20 text-white' : 'bg-purple-50 text-purple-600' ?>">
                    <i class="bi bi-pen-fill text-base"></i>
                </div>
                <span class="truncate">Redação com IA</span>
            </a>

            <!-- 4. GUIA SISU & CURSOS (Centraliza Cursos, Notas de Corte, Concorrência e Provas) -->
            <?php $isCourseGuide = in_array($currentPage, ['course_guide.php', 'guia_concorrencia.php', 'provas_oficiais.php']); ?>
            <a href="course_guide.php" title="Guia de Cursos, Notas de Corte do SiSU e Provas Anteriores" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl font-outfit font-extrabold text-sm transition-all <?= $isCourseGuide ? 'bg-indigo-600 text-white shadow-[0_4px_0_0_#312e81]' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' ?>">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 <?= $isCourseGuide ? 'bg-white/20 text-white' : 'bg-blue-50 text-blue-600' ?>">
                    <i class="bi bi-mortarboard-fill text-base"></i>
                </div>
                <span class="truncate">Guia SiSU & Cursos</span>
            </a>

            <!-- 5. RAIO-X & DESEMPENHO -->
            <?php $isMetricas = ($currentPage === 'metricas.php'); ?>
            <a href="metricas.php" title="Métricas de Desempenho e Estatísticas Pedagógicas" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl font-outfit font-extrabold text-sm transition-all <?= $isMetricas ? 'bg-indigo-600 text-white shadow-[0_4px_0_0_#312e81]' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' ?>">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 <?= $isMetricas ? 'bg-white/20 text-white' : 'bg-emerald-50 text-emerald-600' ?>">
                    <i class="bi bi-graph-up-arrow text-base"></i>
                </div>
                <span class="truncate">Desempenho</span>
            </a>

            <!-- 6. RANKING DE AMIGOS -->
            <?php $isLeaderboard = ($currentPage === 'leaderboard.php'); ?>
            <a href="leaderboard.php" title="Ranking de Amigos" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl font-outfit font-extrabold text-sm transition-all <?= $isLeaderboard ? 'bg-indigo-600 text-white shadow-[0_4px_0_0_#312e81]' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' ?>">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 <?= $isLeaderboard ? 'bg-white/20 text-white' : 'bg-amber-50 text-amber-500' ?>">
                    <i class="bi bi-trophy-fill text-base"></i>
                </div>
                <span class="truncate">Ranking de Amigos</span>
            </a>

            <!-- DIVISÓRIA COM PERFIL E SAIR -->
            <div class="pt-2 border-t border-slate-200 mt-2 space-y-1">
                <?php $isProfile = ($currentPage === 'profile.php'); ?>
                <a href="profile.php" title="Meu Perfil" class="flex items-center gap-3 px-3.5 py-2 rounded-2xl font-outfit font-extrabold text-xs transition-all <?= $isProfile ? 'bg-indigo-600 text-white shadow-[0_3px_0_0_#312e81]' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-900' ?>">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center shrink-0 <?= $isProfile ? 'text-white' : 'text-indigo-600' ?>">
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

<script>
    // Limpar qualquer estado legado de sidebar retraída no navegador do usuário
    try {
        localStorage.removeItem('sidebar_collapsed');
    } catch(e) {}
</script>
