<?php
/**
 * COMPONENTE REUTILIZÁVEL: FOOTER / RODAPÉ DO SITE — HIPOGABARITO
 */
?>
    <!-- RODAPÉ REUTILIZÁVEL -->
    <footer class="mt-auto bg-white border-t-2 border-slate-200 py-6 text-slate-500 text-xs font-medium">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <img src="assets/img/hipogabarito_logo.png" alt="HipoGabarito Logo" class="h-6 w-auto object-contain">
                <span>&copy; <?= date('Y') ?> <strong>HipoGabarito</strong>. Todos os direitos reservados.</span>
            </div>
            <div class="flex items-center gap-4 text-slate-400">
                <a href="dashboard.php" class="hover:text-indigo-600 transition">Trilha</a>
                <a href="simulado.php" class="hover:text-indigo-600 transition">Simulados</a>
                <a href="leaderboard.php" class="hover:text-indigo-600 transition">Ranking</a>
                <a href="https://www.todamateria.com.br/" target="_blank" rel="noopener noreferrer" class="hover:text-emerald-600 transition flex items-center gap-1">
                    Toda Matéria <i class="bi bi-box-arrow-up-right text-[10px]"></i>
                </a>
            </div>
        </div>
    </footer>

    <!-- SCRIPTS DE NOTIFICAÇÃO E EFEITOS -->
    <script src="assets/js/notifications.js"></script>
</body>
</html>
