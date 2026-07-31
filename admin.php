<?php
require_once __DIR__ . '/config/db.php';
checkAuth();

// Verificar se é Admin
if (($_SESSION['user_role'] ?? '') !== 'admin') {
    echo "<script>alert('Acesso restrito a professores/administradores.'); window.location.href='dashboard.php';</script>";
    exit;
}

$userId = $_SESSION['user_id'];
$stmtUser = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmtUser->execute([$userId]);
$user = $stmtUser->fetch();
$userAvatar = !empty($user['avatar']) ? $user['avatar'] : 'https://api.dicebear.com/7.x/bottts/svg?seed=' . urlencode($user['name'] ?? 'Player');

// Buscar lições para o select
$lessons = $pdo->query("SELECT l.id, l.title, s.name as subject_name FROM lessons l JOIN units u ON l.unit_id = u.id JOIN subjects s ON u.subject_id = s.id ORDER BY s.id, l.id")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Professor — AprovaQuest</title>

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
                    <div class="flex items-center gap-3.5 p-3 mb-4 bg-slate-50 rounded-2xl border border-slate-200">
                        <img src="<?= htmlspecialchars($userAvatar) ?>" class="w-12 h-12 rounded-full border-2 border-indigo-600 object-cover shadow-sm">
                        <div class="min-w-0 flex-1">
                            <h3 class="font-outfit font-bold text-slate-900 text-base truncate"><?= htmlspecialchars($user['name'] ?? 'Professor') ?></h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                PAINEL ADMIN
                            </span>
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

                        <a href="profile.php" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-outfit font-bold text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-all">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Meu Perfil
                        </a>

                        <a href="admin.php" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-outfit font-extrabold text-sm bg-indigo-600 text-white shadow-[0_4px_0_0_#312e81] hover:bg-indigo-700 transition-all mt-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Painel Professor
                        </a>

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

            <!-- CONTEÚDO PRINCIPAL: PAINEL PROFESSOR -->
            <section class="lg:col-span-9 space-y-6">
                <!-- BANNER PAINEL -->
                <div class="bg-gradient-to-r from-indigo-600 via-indigo-700 to-indigo-800 text-white rounded-3xl p-6 shadow-[0_8px_0_0_#312e81] border-2 border-indigo-400 flex items-center justify-between">
                    <div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-extrabold bg-white/20 text-indigo-100 border border-white/30 uppercase tracking-wider mb-2">
                            GESTÃO DE CONTEÚDO PEDAGÓGICO
                        </span>
                        <h2 class="font-outfit font-extrabold text-2xl mb-1">Painel do Professor</h2>
                        <p class="text-xs text-indigo-100 font-medium mb-0">Cadastre e gerencie questões dos simulados e vestibulares.</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center text-indigo-200 shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                </div>

                <!-- FORMULÁRIO DE CADASTRO -->
                <div class="bg-white rounded-3xl border-2 border-slate-200 p-6 shadow-[0_4px_0_0_#e2e8f0]">
                    <h3 class="font-outfit font-extrabold text-lg text-slate-900 mb-4">Cadastro de Nova Questão (5 Alternativas A a E)</h3>

                    <form id="formQuestion" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 mb-1">Lição Destino</label>
                                <select id="lesson_id" class="w-full px-4 py-2.5 rounded-2xl border-2 border-slate-200 font-medium text-sm focus:border-indigo-600 focus:outline-none" required>
                                    <?php foreach ($lessons as $l): ?>
                                        <option value="<?= $l['id'] ?>">[<?= htmlspecialchars($l['subject_name']) ?>] <?= htmlspecialchars($l['title']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 mb-1">Origem / Exame</label>
                                <input type="text" id="exam_source" class="w-full px-4 py-2.5 rounded-2xl border-2 border-slate-200 font-medium text-sm focus:border-indigo-600 focus:outline-none" placeholder="Ex: FUVEST 2024" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Enunciado da Questão</label>
                            <textarea id="question_text" rows="3" class="w-full px-4 py-2.5 rounded-2xl border-2 border-slate-200 font-medium text-sm focus:border-indigo-600 focus:outline-none" placeholder="Digite o enunciado completo..." required></textarea>
                        </div>

                        <!-- 5 OPÇÕES -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 mb-1">Opção A</label>
                                <input type="text" id="option_a" class="w-full px-4 py-2 rounded-xl border-2 border-slate-200 text-sm" placeholder="Texto da alternativa A" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 mb-1">Opção B</label>
                                <input type="text" id="option_b" class="w-full px-4 py-2 rounded-xl border-2 border-slate-200 text-sm" placeholder="Texto da alternativa B" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 mb-1">Opção C</label>
                                <input type="text" id="option_c" class="w-full px-4 py-2 rounded-xl border-2 border-slate-200 text-sm" placeholder="Texto da alternativa C" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 mb-1">Opção D</label>
                                <input type="text" id="option_d" class="w-full px-4 py-2 rounded-xl border-2 border-slate-200 text-sm" placeholder="Texto da alternativa D" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-600 mb-1">Opção E</label>
                                <input type="text" id="option_e" class="w-full px-4 py-2 rounded-xl border-2 border-slate-200 text-sm" placeholder="Texto da alternativa E" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 mb-1">Gabarito Correto</label>
                                <select id="correct_option" class="w-full px-4 py-2.5 rounded-2xl border-2 border-slate-200 font-extrabold text-emerald-600 text-sm focus:border-indigo-600 focus:outline-none" required>
                                    <option value="a">Opção A</option>
                                    <option value="b">Opção B</option>
                                    <option value="c">Opção C</option>
                                    <option value="d">Opção D</option>
                                    <option value="e">Opção E</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 mb-1">Dificuldade</label>
                                <select id="difficulty" class="w-full px-4 py-2.5 rounded-2xl border-2 border-slate-200 font-bold text-slate-700 text-sm focus:border-indigo-600 focus:outline-none">
                                    <option value="fácil">Fácil</option>
                                    <option value="médio" selected>Médio</option>
                                    <option value="difícil">Difícil</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Explicação Pedagógica</label>
                            <textarea id="explanation_text" rows="2" class="w-full px-4 py-2.5 rounded-2xl border-2 border-slate-200 font-medium text-sm focus:border-indigo-600 focus:outline-none" placeholder="Explicação que aparecerá após o aluno responder..." required></textarea>
                        </div>

                        <button type="submit" class="w-full py-3.5 px-6 rounded-2xl font-outfit font-extrabold text-base bg-indigo-600 text-white shadow-[0_6px_0_0_#312e81] hover:bg-indigo-700 active:translate-y-1 active:shadow-[0_2px_0_0_#312e81] transition-all">
                            Cadastrar Questão
                        </button>
                    </form>
                </div>

                <!-- LISTA DE QUESTÕES CADASTRADAS -->
                <div class="bg-white rounded-3xl border-2 border-slate-200 p-6 shadow-[0_4px_0_0_#e2e8f0]">
                    <h3 class="font-outfit font-extrabold text-lg text-slate-900 mb-4">Questões Cadastradas</h3>
                    <div id="questionsTableContainer" class="overflow-x-auto"></div>
                </div>
            </section>
        </div>
    </main>

    <script src="assets/js/sound_effects.js"></script>
    <script>
        function loadQuestionsList() {
            fetch('api/admin_questions.php?action=list')
                .then(res => res.json())
                .then(data => {
                    if (!data.success) return;
                    const container = document.getElementById('questionsTableContainer');
                    let html = `<table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 border-b-2 border-slate-200 font-outfit font-extrabold text-slate-500 uppercase">
                                <th class="py-3 px-4">ID</th>
                                <th class="py-3 px-4">Matéria / Lição</th>
                                <th class="py-3 px-4">Enunciado</th>
                                <th class="py-3 px-4">Gabarito</th>
                                <th class="py-3 px-4 text-right">Ação</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">`;
                    
                    data.questions.forEach(q => {
                        html += `<tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 font-bold text-slate-400">#${q.id}</td>
                            <td class="py-3 px-4"><strong class="text-indigo-600 block">${q.subject_name}</strong><span class="text-slate-500 text-[11px]">${q.lesson_title}</span></td>
                            <td class="py-3 px-4 max-w-xs truncate">${q.question_text}</td>
                            <td class="py-3 px-4"><span class="px-2 py-1 rounded-lg text-xs font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">Opção ${q.correct_option.toUpperCase()}</span></td>
                            <td class="py-3 px-4 text-right">
                                <button onclick="deleteQuestion(${q.id})" class="px-3 py-1 rounded-xl text-xs font-bold bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-600 hover:text-white transition-all">Excluir</button>
                            </td>
                        </tr>`;
                    });

                    html += `</tbody></table>`;
                    container.innerHTML = html;
                });
        }

        document.getElementById('formQuestion').addEventListener('submit', function(e) {
            e.preventDefault();
            if (typeof sounds !== 'undefined') sounds.playClick();

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
            if (typeof sounds !== 'undefined') sounds.playClick();
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
