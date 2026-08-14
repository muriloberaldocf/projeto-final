<?php
require_once __DIR__ . '/config/db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR" class="h-full bg-[#F1EFF5]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HipoGabarito — Prática Inteligente para Vestibulares</title>

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
                            900: '#3d256b',
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
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/dark-mode.css">

    <script>
        window.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.documentElement.classList.remove('no-transition');
            }, 50);
        });
    </script>
</head>
<body class="min-h-full font-sans antialiased text-[#17151C] bg-[#F1EFF5] selection:bg-[#6842C2] selection:text-white flex flex-col">

    <!-- HEADER TOP -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b-2 border-[#d8cef2] py-2.5 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <a href="index.php" class="flex items-center gap-3 group text-decoration-none">
                <img src="assets/img/hipogabarito_logo.png" alt="HipoGabarito Logo" class="h-11 sm:h-12 w-auto object-contain group-hover:scale-105 transition-transform">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-[#f1eff5] text-[#6842C2] border border-[#d8cef2] uppercase">PRO</span>
            </a>

            <div class="flex items-center gap-3">
                <button id="themeToggleBtn" onclick="toggleTheme()" class="theme-toggle-btn" title="Alternar Modo Escuro">
                    <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
                </button>
                <div class="text-xs font-bold text-slate-500 hidden sm:block">
                    Prática Inteligente & Simulados Gamificados
                </div>
            </div>
        </div>
    </header>

    <!-- CONTEÚDO DA LANDING & AUTH -->
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex items-center">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center w-full">
            
            <!-- ESQUERDA: APRESENTAÇÃO -->
            <div class="lg:col-span-7 space-y-6">
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-[#f1eff5] text-[#6842C2] border border-[#d8cef2] uppercase tracking-wider">
                    <i class="bi bi-stars text-amber-500"></i> PREPARAÇÃO DE ALTO RENDIMENTO
                </span>

                <h1 class="font-outfit font-extrabold text-4xl sm:text-5xl lg:text-6xl text-[#17151C] leading-tight tracking-tight">
                    Conquiste sua Vaga Praticando <span class="text-[#6842C2]">Questões Reais</span>
                </h1>

                <p class="text-base text-slate-600 font-medium leading-relaxed max-w-xl">
                    Acelere sua aprovação no ENEM, FUVEST, UNICAMP e VUNESP com simulados interativos, resolução passo a passo e evolução em tempo real.
                </p>

                <!-- RECURSOS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div class="bg-white rounded-2xl border-2 border-[#d8cef2] p-4 shadow-[0_4px_0_0_#d8cef2] flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#f1eff5] text-[#6842C2] flex items-center justify-center border border-[#d8cef2] shrink-0">
                            <i class="bi bi-ui-checks-grid fs-5"></i>
                        </div>
                        <div>
                            <h4 class="font-outfit font-bold text-sm text-[#17151C]">5 Alternativas (A a E)</h4>
                            <span class="text-xs text-slate-500 font-medium">Estrutura oficial de vestibulares</span>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border-2 border-[#d8cef2] p-4 shadow-[0_4px_0_0_#d8cef2] flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-200 shrink-0">
                            <i class="bi bi-lightning-charge-fill fs-5"></i>
                        </div>
                        <div>
                            <h4 class="font-outfit font-bold text-sm text-[#17151C]">Evolução de XP & Fases</h4>
                            <span class="text-xs text-slate-500 font-medium">Suba de nível praticando</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DIREITA: FORMULÁRIO DE LOGIN / REGISTRO TAILWIND -->
            <div class="lg:col-span-5">
                <div class="bg-white rounded-3xl border-4 border-[#d8cef2] p-7 shadow-2xl space-y-5">
                    <div class="text-center pb-2 border-b border-slate-100 flex flex-col items-center">
                        <img src="assets/img/hipogabarito_logo.png?v=4" alt="HipoGabarito Logo" class="h-10 mx-auto w-auto object-contain mb-1">
                        <p class="text-xs font-bold text-slate-400 mb-0">Acesse sua conta para continuar praticando</p>
                    </div>

                    <!-- TABS ENTRAR / CRIAR CONTA -->
                    <div class="flex bg-[#f1eff5] p-1.5 rounded-2xl border border-[#d8cef2]" id="authTabs">
                        <button type="button" onclick="switchAuth('login')" id="login-tab" class="flex-1 py-2.5 rounded-xl font-outfit font-extrabold text-sm transition-all bg-[#6842C2] text-white shadow-sm">
                            Entrar
                        </button>
                        <button type="button" onclick="switchAuth('register')" id="register-tab" class="flex-1 py-2.5 rounded-xl font-outfit font-bold text-sm text-slate-600 hover:text-slate-900 transition-all">
                            Criar Conta
                        </button>
                    </div>

                    <form id="formAuth" class="space-y-4">
                        <div id="nameField" style="display: none;">
                            <label class="block text-xs font-bold text-slate-600 mb-1">Nome Completo</label>
                            <input type="text" id="inputName" class="w-full px-4 py-3 rounded-2xl border-2 border-slate-200 font-medium text-sm focus:border-[#6842C2] focus:outline-none" placeholder="Seu nome">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">E-mail</label>
                            <input type="email" id="inputEmail" class="w-full px-4 py-3 rounded-2xl border-2 border-slate-200 font-medium text-sm focus:border-[#6842C2] focus:outline-none" placeholder="estudante@aprovaquest.com" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Senha</label>
                            <input type="password" id="inputPassword" class="w-full px-4 py-3 rounded-2xl border-2 border-slate-200 font-medium text-sm focus:border-[#6842C2] focus:outline-none" placeholder="••••••••" required>
                        </div>

                        <button type="submit" id="btnSubmit" class="w-full py-3.5 px-6 rounded-2xl font-outfit font-extrabold text-base bg-[#6842C2] text-white shadow-[0_6px_0_0_#3D256B] hover:bg-[#5832b2] active:translate-y-1 active:shadow-[0_2px_0_0_#3D256B] transition-all">
                            Entrar na Plataforma
                        </button>
                    </form>

                    <div id="errorMessage" class="hidden p-3 rounded-2xl bg-rose-50 border border-rose-200 text-rose-600 text-xs font-bold text-center"></div>

                    <!-- ACESSO DEMO -->
                    <div class="pt-4 border-t border-slate-200 text-center">
                        <button type="button" onclick="demoLogin()" class="w-full py-3 px-4 rounded-2xl font-outfit font-extrabold text-xs bg-[#f1eff5] text-[#6842C2] hover:bg-[#e4ddf7] border border-[#d8cef2] transition-all flex items-center justify-center gap-2">
                            <i class="bi bi-lightning-fill text-amber-500 fs-6"></i> ACESSAR COMO ALUNO DEMO
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <footer class="py-6 border-t border-slate-200 text-center text-xs font-semibold text-slate-400">
        HipoGabarito &copy; 2026 — Plataforma Independente de Exercícios Gamificados
    </footer>

    <script src="assets/js/sound_effects.js"></script>
    <script>
        let currentMode = 'login';

        function switchAuth(mode) {
            currentMode = mode;
            const loginTab = document.getElementById('login-tab');
            const regTab = document.getElementById('register-tab');

            if (mode === 'login') {
                loginTab.className = 'flex-1 py-2.5 rounded-xl font-outfit font-extrabold text-sm transition-all bg-[#6842C2] text-white shadow-sm';
                regTab.className = 'flex-1 py-2.5 rounded-xl font-outfit font-bold text-sm text-slate-600 hover:text-slate-900 transition-all';
            } else {
                regTab.className = 'flex-1 py-2.5 rounded-xl font-outfit font-extrabold text-sm transition-all bg-[#6842C2] text-white shadow-sm';
                loginTab.className = 'flex-1 py-2.5 rounded-xl font-outfit font-bold text-sm text-slate-600 hover:text-slate-900 transition-all';
            }

            document.getElementById('nameField').style.display = (mode === 'register') ? 'block' : 'none';
            document.getElementById('btnSubmit').textContent = (mode === 'register') ? 'Criar Minha Conta' : 'Entrar na Plataforma';
        }

        document.getElementById('formAuth').addEventListener('submit', function(e) {
            e.preventDefault();
            if (typeof sounds !== 'undefined') sounds.playClick();

            const formData = new FormData();
            formData.append('action', currentMode);
            formData.append('email', document.getElementById('inputEmail').value);
            formData.append('password', document.getElementById('inputPassword').value);
            if (currentMode === 'register') {
                formData.append('name', document.getElementById('inputName').value);
            }

            fetch('api/auth.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    const err = document.getElementById('errorMessage');
                    err.textContent = data.message;
                    err.classList.remove('hidden');
                }
            });
        });

        function demoLogin() {
            if (typeof sounds !== 'undefined') sounds.playClick();
            fetch('api/auth.php?action=demo_login')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect;
                    }
                });
        }
    </script>

    <!-- DARK MODE TOGGLE -->
    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const isDark = html.classList.toggle('dark');
            localStorage.setItem('hipogabarito_theme', isDark ? 'dark' : 'light');
            updateThemeIcon();
        }
        function updateThemeIcon() {
            const icon = document.getElementById('themeIcon');
            if (!icon) return;
            const isDark = document.documentElement.classList.contains('dark');
            icon.className = isDark ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
        }
        document.addEventListener('DOMContentLoaded', updateThemeIcon);
    </script>
</body>
</html>
