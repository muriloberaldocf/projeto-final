<?php
require_once __DIR__ . '/config/db.php';
checkAuth();

// Verificar se é Admin
if (($_SESSION['user_role'] ?? '') !== 'admin') {
    echo "<script>alert('Acesso restrito a professores/administradores.'); window.location.href='dashboard.php';</script>";
    exit;
}

// Buscar lições para o select
$lessons = $pdo->query("SELECT l.id, l.title, s.name as subject_name FROM lessons l JOIN units u ON l.unit_id = u.id JOIN subjects s ON u.subject_id = s.id ORDER BY s.id, l.id")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Professor — AprovaQuest</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom CSS AprovaQuest -->
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body class="bg-mesh-gradient">

    <!-- NAVBAR INDEPENDENTE -->
    <nav class="navbar navbar-expand-lg navbar-aprova sticky-top">
        <div class="container">
            <a class="brand-logo-aprova" href="dashboard.php">
                <div class="brand-icon-box">
                    <i class="bi bi-check-all"></i>
                </div>
                <span>AprovaQuest</span>
            </a>
            <a href="dashboard.php" class="btn btn-aprova-light btn-sm font-monospace">
                Voltar à Trilha
            </a>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row g-4">
            <!-- SIDEBAR -->
            <div class="col-lg-3">
                <div class="card card-aprova p-3">
                    <div class="nav flex-column nav-pills gap-1">
                        <a href="dashboard.php" class="nav-link text-secondary fw-semibold d-flex align-items-center gap-2 py-2">
                            <i class="bi bi-map"></i> Trilha de Estudos
                        </a>
                        <a href="leaderboard.php" class="nav-link text-secondary fw-semibold d-flex align-items-center gap-2 py-2">
                            <i class="bi bi-trophy text-warning"></i> Classificação
                        </a>
                        <a href="profile.php" class="nav-link text-secondary fw-semibold d-flex align-items-center gap-2 py-2">
                            <i class="bi bi-person text-primary"></i> Perfil
                        </a>
                        <a href="admin.php" class="nav-link active fw-semibold d-flex align-items-center gap-2 py-2" style="background-color: var(--brand-primary); color:#fff;">
                            <i class="bi bi-gear"></i> Painel Professor
                        </a>

                        <hr class="my-2 text-muted">

                        <a href="api/auth.php?action=logout" class="nav-link text-muted small d-flex align-items-center gap-2 py-1.5">
                            <i class="bi bi-box-arrow-right"></i> Sair
                        </a>
                    </div>
                </div>
            </div>

            <!-- CONTEÚDO PRINCIPAL -->
            <div class="col-lg-9">
                <div class="card card-aprova p-4 mb-4">
                    <h5 class="fw-bold mb-3 text-dark">Cadastro de Questões (5 Alternativas A-E)</h5>

                    <form id="formQuestion" class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-medium">Lição Destino</label>
                            <select id="lesson_id" class="form-select form-control-aprova" required>
                                <?php foreach ($lessons as $l): ?>
                                    <option value="<?= $l['id'] ?>">[<?= htmlspecialchars($l['subject_name']) ?>] <?= htmlspecialchars($l['title']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-medium">Origem / Identificador</label>
                            <input type="text" id="exam_source" class="form-control form-control-aprova" placeholder="Ex: VESTIBULAR 2024" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label text-muted small fw-medium">Enunciado da Questão</label>
                            <textarea id="question_text" class="form-control form-control-aprova" rows="3" placeholder="Digite o enunciado..." required></textarea>
                        </div>

                        <!-- 5 ALTERNATIVAS -->
                        <div class="col-12 mt-3">
                            <span class="text-muted small fw-bold">As 5 Opções de Resposta:</span>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small">Opção A</label>
                            <input type="text" id="option_a" class="form-control form-control-aprova" placeholder="Texto da opção A" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small">Opção B</label>
                            <input type="text" id="option_b" class="form-control form-control-aprova" placeholder="Texto da opção B" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small">Opção C</label>
                            <input type="text" id="option_c" class="form-control form-control-aprova" placeholder="Texto da opção C" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small">Opção D</label>
                            <input type="text" id="option_d" class="form-control form-control-aprova" placeholder="Texto da opção D" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label text-muted small">Opção E</label>
                            <input type="text" id="option_e" class="form-control form-control-aprova" placeholder="Texto da opção E" required>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label text-muted small fw-medium">Gabarito Correto</label>
                            <select id="correct_option" class="form-select form-control-aprova fw-semibold text-success" required>
                                <option value="a">Opção A</option>
                                <option value="b">Opção B</option>
                                <option value="c">Opção C</option>
                                <option value="d">Opção D</option>
                                <option value="e">Opção E</option>
                            </select>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label text-muted small fw-medium">Dificuldade</label>
                            <select id="difficulty" class="form-select form-control-aprova">
                                <option value="fácil">Fácil</option>
                                <option value="médio" selected>Médio</option>
                                <option value="difícil">Difícil</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label text-muted small fw-medium">Explicação Pedagógica</label>
                            <textarea id="explanation_text" class="form-control form-control-aprova" rows="2" placeholder="Explique por que esta opção é a correta..." required></textarea>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-aprova-primary w-100 py-2 fs-6">
                                Cadastrar Questão
                            </button>
                        </div>
                    </form>
                </div>

                <!-- LISTA DE QUESTÕES CADASTRADAS -->
                <div class="card card-aprova p-4">
                    <h6 class="fw-bold mb-3 text-dark">Questões no Banco</h6>
                    <div id="questionsTableContainer" class="table-responsive">
                        <!-- Carregado via JS -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/sound_effects.js"></script>
    <script>
        function loadQuestionsList() {
            fetch('api/admin_questions.php?action=list')
                .then(res => res.json())
                .then(data => {
                    if (!data.success) return;
                    const container = document.getElementById('questionsTableContainer');
                    let html = `<table class="table table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Matéria / Lição</th>
                                <th>Enunciado</th>
                                <th>Gabarito</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>`;
                    
                    data.questions.forEach(q => {
                        html += `<tr>
                            <td>#${q.id}</td>
                            <td><strong>${q.subject_name}</strong><br><span class="text-muted small">${q.lesson_title}</span></td>
                            <td>${q.question_text.substring(0, 60)}...</td>
                            <td><span class="badge bg-light text-success border fw-bold">Opção ${q.correct_option.toUpperCase()}</span></td>
                            <td>
                                <button onclick="deleteQuestion(${q.id})" class="btn btn-outline-danger btn-sm py-0 px-2">Excluir</button>
                            </td>
                        </tr>`;
                    });

                    html += `</tbody></table>`;
                    container.innerHTML = html;
                });
        }

        document.getElementById('formQuestion').addEventListener('submit', function(e) {
            e.preventDefault();
            sounds.playClick();

            const formData = new FormData();
            formData.append('action', 'create');
            formData.append('lesson_id', document.getElementById('lesson_id').value);
            formData.append('exam_source', document.getElementById('exam_source').value);
            formData.append('question_text', document.getElementById('question_text').value);
            formData.append('option_a', document.getElementById('option_a').value);
            formData.append('option_b', document.getElementById('option_b').value);
            formData.append('option_c', document.getElementById('option_c').value);
            formData.append('option_d', document.getElementById('option_d').value);
            formData.append('option_e', document.getElementById('option_e').value);
            formData.append('correct_option', document.getElementById('correct_option').value);
            formData.append('difficulty', document.getElementById('difficulty').value);
            formData.append('explanation_text', document.getElementById('explanation_text').value);

            fetch('api/admin_questions.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    document.getElementById('formQuestion').reset();
                    loadQuestionsList();
                }
            });
        });

        function deleteQuestion(id) {
            if (!confirm('Deseja realmente excluir esta questão?')) return;
            sounds.playClick();
            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('id', id);

            fetch('api/admin_questions.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                loadQuestionsList();
            });
        }

        document.addEventListener('DOMContentLoaded', loadQuestionsList);
    </script>
</body>
</html>
