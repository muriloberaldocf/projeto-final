<?php
require_once __DIR__ . '/config/db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AprovaQuest — Prática Inteligente para Vestibulares</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom CSS AprovaQuest -->
    <link rel="stylesheet" href="assets/css/main.css">
    <style>
        /* Estilos adicionais para harmonização de abas e botões */
        .auth-nav-link {
            border: none;
            border-radius: 8px !important;
            font-weight: 600;
            color: var(--text-muted);
            padding: 10px 16px;
            transition: all 0.15s ease;
        }

        .auth-nav-link.active {
            background-color: var(--brand-primary) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        }
    </style>
</head>
<body class="bg-mesh-gradient bg-grid-pattern">

    <!-- NAVBAR INDEPENDENTE -->
    <nav class="navbar navbar-expand-lg navbar-aprova sticky-top">
        <div class="container">
            <a class="brand-logo-aprova" href="index.php">
                <div class="brand-icon-box">
                    <i class="bi bi-check-all"></i>
                </div>
                <span>AprovaQuest</span>
                <span class="brand-badge-aprova ms-1">PRO</span>
            </a>
            <div class="ms-auto d-none d-md-block text-muted small fw-medium">
                Prática Inteligente & Simulados
            </div>
        </div>
    </nav>

    <!-- CONTEÚDO PRINCIPAL COM FUNDO DECORATIVO E RICO -->
    <main class="container py-5">
        <div class="row align-items-center g-5 py-3">
            <!-- COLUNA ESQUERDA -->
            <div class="col-lg-6">
                <span class="badge bg-indigo-subtle text-primary border border-indigo-subtle px-3 py-2 rounded-pill fw-semibold mb-3 d-inline-flex align-items-center gap-1" style="background-color: #eef2ff; color: #4f46e5; border-color: #c7d2fe;">
                    <i class="bi bi-stars"></i> PREPARAÇÃO DE ALTO RENDIMENTO
                </span>

                <h1 class="display-5 fw-bold text-dark mb-3" style="letter-spacing: -0.8px; line-height: 1.15;">
                    Sua Jornada de Exercícios para os <span style="color: #4f46e5;">Vestibulares</span>
                </h1>

                <p class="text-secondary mb-4 fs-6" style="line-height: 1.6;">
                    Pratique com milhares de questões organizadas por disciplinas, acompanhe sua evolução acadêmica em tempo real e consolide seu aprendizado.
                </p>

                <!-- CARDS DE RECURSOS FLUTUANTES -->
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="card card-aprova p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 p-2 text-primary" style="background-color: #eef2ff;">
                                    <i class="bi bi-ui-checks-grid fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">5 Alternativas (A a E)</h6>
                                    <span class="text-muted small">Estrutura oficial</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="card card-aprova p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 p-2 text-info" style="background-color: #ecfeff;">
                                    <i class="bi bi-graph-up-arrow fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Evolução de XP</h6>
                                    <span class="text-muted small">Desempenho diário</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CARD DE AUTENTICAÇÃO COM GLASSMORPHISM HARMONIZADO -->
            <div class="col-lg-5 offset-lg-1">
                <div class="card card-aprova p-4 shadow-lg border-0" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(16px);">
                    <ul class="nav nav-pills nav-fill mb-4 bg-light p-1 rounded-3" id="authTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link auth-nav-link active" id="login-tab" onclick="switchAuth('login')">
                                Entrar
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link auth-nav-link" id="register-tab" onclick="switchAuth('register')">
                                Criar Conta
                            </button>
                        </li>
                    </ul>

                    <form id="formAuth">
                        <div id="nameField" class="mb-3" style="display: none;">
                            <label class="form-label text-muted small fw-semibold">Nome Completo</label>
                            <input type="text" id="inputName" class="form-control form-control-aprova" placeholder="Seu nome">
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-semibold">E-mail</label>
                            <input type="email" id="inputEmail" class="form-control form-control-aprova" placeholder="estudante@aprovaquest.com" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-semibold">Senha</label>
                            <input type="password" id="inputPassword" class="form-control form-control-aprova" placeholder="••••••••" required>
                        </div>

                        <button type="submit" id="btnSubmit" class="btn btn-aprova-primary w-100 py-2.5 fs-6">
                            Entrar na Plataforma
                        </button>
                    </form>

                    <div id="errorMessage" class="alert alert-danger mt-3 mb-0 small" style="display: none;"></div>

                    <!-- ACESSO RÁPIDO HARMONIZADO -->
                    <div class="mt-4 pt-3 border-top text-center">
                        <button type="button" onclick="demoLogin()" class="btn btn-aprova-soft btn-sm w-100 fw-semibold py-2">
                            <i class="bi bi-lightning-fill me-1"></i> Acessar como Aluno Demo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="container py-4 border-top text-center text-muted small">
        <p class="mb-0">AprovaQuest &copy; 2026 — Plataforma Independente de Exercícios</p>
    </footer>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/sound_effects.js"></script>
    <script>
        let currentMode = 'login';

        function switchAuth(mode) {
            currentMode = mode;
            document.getElementById('login-tab').classList.toggle('active', mode === 'login');
            document.getElementById('register-tab').classList.toggle('active', mode === 'register');
            
            document.getElementById('nameField').style.display = (mode === 'register') ? 'block' : 'none';
            document.getElementById('btnSubmit').textContent = (mode === 'register') ? 'Criar Minha Conta' : 'Entrar na Plataforma';
        }

        document.getElementById('formAuth').addEventListener('submit', function(e) {
            e.preventDefault();
            sounds.playClick();

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
                    err.style.display = 'block';
                }
            });
        });

        function demoLogin() {
            sounds.playClick();
            fetch('api/auth.php?action=demo_login')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect;
                    }
                });
        }
    </script>
</body>
</html>
