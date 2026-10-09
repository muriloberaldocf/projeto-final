<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/gemini.php';
checkAuth();

$userId = $_SESSION['user_id'];

// Estatísticas de Redação do Usuário
$stmtStats = $pdo->prepare("
    SELECT 
        COUNT(*) AS total,
        COALESCE(MAX(nota_final), 0) AS melhor_nota,
        COALESCE(ROUND(AVG(nota_final)), 0) AS media_nota
    FROM redacoes 
    WHERE user_id = ?
");
$stmtStats->execute([$userId]);
$stats = $stmtStats->fetch(PDO::FETCH_ASSOC);

// Buscar todos os temas ordenados por ano desc
$stmtTemas = $pdo->query("SELECT * FROM redacao_temas ORDER BY ano DESC, id ASC");
$temas = $stmtTemas->fetchAll(PDO::FETCH_ASSOC);

// Contagem e agrupamento de temas por banca
$temasPorBanca = [];
$temasAgrupados = [];
foreach ($temas as $t) {
    $b = strtoupper($t['banca'] ?? 'ENEM');
    $temasPorBanca[$b] = ($temasPorBanca[$b] ?? 0) + 1;
    $temasAgrupados[$b][] = $t;
}

// Buscar Histórico de Redações
$stmtHistorico = $pdo->prepare("
    SELECT r.*, t.ano as tema_ano, t.eixo_tematico as tema_eixo
    FROM redacoes r
    LEFT JOIN redacao_temas t ON r.tema_id = t.id
    WHERE r.user_id = ? 
    ORDER BY r.created_at DESC
");
$stmtHistorico->execute([$userId]);
$historico = $stmtHistorico->fetchAll(PDO::FETCH_ASSOC);

$geminiConfig = getGeminiConfig();
$pageTitle = 'Portal de Redações & Propostas de Vestibulares — HipoGabarito';
require_once __DIR__ . '/includes/header.php';
?>

    <style>
        .redacao-folha {
            background-color: #ffffff;
            background-image: repeating-linear-gradient(white, white 31px, #e2e8f0 31px, #e2e8f0 32px);
            line-height: 32px;
            padding: 16px 20px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 15px;
            border-radius: 16px;
            border: 2px solid #cbd5e1;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.03);
            resize: vertical;
            transition: all 0.2s ease;
        }
        .redacao-folha:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }
        html.dark .redacao-folha {
            background-color: #18202e !important;
            background-image: repeating-linear-gradient(#18202e, #18202e 31px, #273449 31px, #273449 32px) !important;
            color: #f8fafc !important;
            border-color: #384660 !important;
        }
        .dropzone-redacao {
            border: 2px dashed #a5b4fc;
            background: rgba(99, 102, 241, 0.03);
            border-radius: 20px;
            transition: all 0.2s;
        }
        .dropzone-redacao:hover, .dropzone-redacao.dragover {
            border-color: #6366f1;
            background: rgba(99, 102, 241, 0.08);
        }
        html.dark .dropzone-redacao {
            border-color: #475569 !important;
            background-color: rgba(99, 102, 241, 0.08) !important;
        }
        .banca-pill.active {
            background-color: #4f46e5 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
        }
    </style>

    <!-- LAYOUT PRINCIPAL -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-all">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- SIDEBAR MODULAR -->
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

            <!-- CONTEÚDO PRINCIPAL DO PORTAL DA REDAÇÃO -->
            <section class="lg:col-span-9 space-y-6">

                <!-- HERO BANNER EXCLUSIVO: PORTAL DA REDAÇÃO -->
                <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-purple-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden border-2 border-indigo-500/30">
                    <div class="absolute -right-10 -top-10 w-52 h-52 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute right-8 bottom-0 opacity-10 pointer-events-none hidden sm:block">
                        <i class="bi bi-feather text-9xl"></i>
                    </div>

                    <div class="relative z-10 space-y-4">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-amber-400 text-slate-950 uppercase tracking-wider shadow-sm">
                                    <i class="bi bi-award-fill"></i> Área Exclusiva
                                </span>
                                <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-indigo-500/20 text-indigo-300 border border-indigo-400/30">
                                    ENEM • UNESP • FUVEST • UNICAMP • UERJ
                                </span>
                            </div>

                            <button type="button" onclick="openApiKeyModal()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/15 text-xs font-bold transition-all text-white" title="Configurar IA">
                                <i class="bi bi-cpu-fill text-amber-300"></i>
                                <span>IA: <?= htmlspecialchars($geminiConfig['model']) ?></span>
                            </button>
                        </div>

                        <div>
                            <h1 class="font-outfit font-black text-2xl sm:text-4xl tracking-tight text-white mb-2">
                                Centro de Redação <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-yellow-200 to-amber-400">Nota 1000</span>
                            </h1>
                            <p class="text-slate-300 text-xs sm:text-sm max-w-2xl leading-relaxed">
                                A redação responde por uma das maiores fatias da sua nota de corte. Treine com as propostas e <strong>textos motivadores oficiais</strong> dos vestibulares mais disputados do país e receba uma correção cirúrgica por Inteligência Artificial adaptada aos critérios da banca examinadora!
                            </p>
                        </div>

                        <!-- STATS RÁPIDOS DO ALUNO -->
                        <div class="grid grid-cols-3 sm:grid-cols-4 gap-3 max-w-xl pt-2">
                            <div class="bg-white/5 backdrop-blur-md rounded-2xl p-3 border border-white/10 text-center">
                                <span class="block text-xl sm:text-2xl font-black font-outfit text-white"><?= $stats['total'] ?></span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Redações</span>
                            </div>
                            <div class="bg-white/5 backdrop-blur-md rounded-2xl p-3 border border-white/10 text-center">
                                <span class="block text-xl sm:text-2xl font-black font-outfit text-amber-300"><?= $stats['melhor_nota'] ?></span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Melhor Nota</span>
                            </div>
                            <div class="bg-white/5 backdrop-blur-md rounded-2xl p-3 border border-white/10 text-center">
                                <span class="block text-xl sm:text-2xl font-black font-outfit text-emerald-400"><?= $stats['media_nota'] ?></span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Média Geral</span>
                            </div>
                            <div class="bg-white/5 backdrop-blur-md rounded-2xl p-3 border border-white/10 text-center hidden sm:block">
                                <span class="block text-xl sm:text-2xl font-black font-outfit text-indigo-300"><?= count($temas) ?></span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Propostas</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ABAS PRINCIPAIS DO PORTAL -->
                <div class="flex items-center gap-2 p-1.5 bg-slate-100 dark:bg-slate-800 rounded-2xl border-2 border-slate-200 dark:border-slate-700 overflow-x-auto">
                    <button type="button" id="tabBtnBanco" onclick="switchPortalTab('banco')" class="flex-1 min-w-[130px] py-3 rounded-xl font-outfit font-extrabold text-xs sm:text-sm transition-all flex items-center justify-center gap-2 bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm border border-slate-200 dark:border-slate-600">
                        <i class="bi bi-collection-fill"></i>
                        <span>Banco de Temas</span>
                    </button>
                    <button type="button" id="tabBtnEscrever" onclick="switchPortalTab('escrever')" class="flex-1 min-w-[130px] py-3 rounded-xl font-outfit font-extrabold text-xs sm:text-sm transition-all flex items-center justify-center gap-2 text-slate-500 hover:text-slate-800 dark:hover:text-slate-200">
                        <i class="bi bi-pencil-square"></i>
                        <span>Bancada de Escrita</span>
                    </button>
                    <button type="button" id="tabBtnRepertorio" onclick="switchPortalTab('repertorio')" class="flex-1 min-w-[130px] py-3 rounded-xl font-outfit font-extrabold text-xs sm:text-sm transition-all flex items-center justify-center gap-2 text-slate-500 hover:text-slate-800 dark:hover:text-slate-200">
                        <i class="bi bi-quote"></i>
                        <span>Guia de Repertórios</span>
                    </button>
                    <button type="button" id="tabBtnHistorico" onclick="switchPortalTab('historico')" class="flex-1 min-w-[130px] py-3 rounded-xl font-outfit font-extrabold text-xs sm:text-sm transition-all flex items-center justify-center gap-2 text-slate-500 hover:text-slate-800 dark:hover:text-slate-200">
                        <i class="bi bi-clock-history"></i>
                        <span>Minhas Redações (<?= count($historico) ?>)</span>
                    </button>
                </div>

                <!-- ========================================================================= -->
                <!-- ABA 1: BANCO DE PROPOSTAS & TEXTOS MOTIVADORES DAS UNIVERSIDADES -->
                <!-- ========================================================================= -->
                <div id="tabContentBanco" class="space-y-6">
                    
                    <!-- FILTRO POR BANCA / VESTIBULAR -->
                    <div class="flex flex-col gap-3.5 bg-white dark:bg-slate-800 p-4 sm:p-5 rounded-3xl border-2 border-slate-200 dark:border-slate-700 shadow-sm">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                            <div class="flex flex-wrap items-center gap-1.5" id="bancaFilters">
                                <button type="button" onclick="filtrarBanca('TODOS')" class="banca-pill active px-3.5 py-1.5 rounded-xl text-xs font-outfit font-black transition-all bg-indigo-600 text-white shadow-sm">
                                    Todos (<?= count($temas) ?>)
                                </button>
                                <button type="button" onclick="filtrarBanca('ENEM')" class="banca-pill px-3.5 py-1.5 rounded-xl text-xs font-outfit font-black transition-all bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-200">
                                    🟡 ENEM (<?= $temasPorBanca['ENEM'] ?? 0 ?>)
                                </button>
                                <button type="button" onclick="filtrarBanca('UNESP')" class="banca-pill px-3.5 py-1.5 rounded-xl text-xs font-outfit font-black transition-all bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-200">
                                    🔵 UNESP (<?= $temasPorBanca['UNESP'] ?? 0 ?>)
                                </button>
                                <button type="button" onclick="filtrarBanca('FUVEST')" class="banca-pill px-3.5 py-1.5 rounded-xl text-xs font-outfit font-black transition-all bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-200">
                                    🔴 FUVEST (<?= $temasPorBanca['FUVEST'] ?? 0 ?>)
                                </button>
                                <button type="button" onclick="filtrarBanca('UNICAMP')" class="banca-pill px-3.5 py-1.5 rounded-xl text-xs font-outfit font-black transition-all bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-200">
                                    🟠 UNICAMP (<?= $temasPorBanca['UNICAMP'] ?? 0 ?>)
                                </button>
                                <button type="button" onclick="filtrarBanca('UERJ')" class="banca-pill px-3.5 py-1.5 rounded-xl text-xs font-outfit font-black transition-all bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-200">
                                    🟣 UERJ (<?= $temasPorBanca['UERJ'] ?? 0 ?>)
                                </button>
                                <button type="button" onclick="filtrarBanca('SIMULADO')" class="banca-pill px-3.5 py-1.5 rounded-xl text-xs font-outfit font-black transition-all bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-200">
                                    ⚡ SIMULADOS (<?= $temasPorBanca['SIMULADO'] ?? 0 ?>)
                                </button>
                                <button type="button" onclick="filtrarBanca('REDIGIR')" class="banca-pill px-3.5 py-1.5 rounded-xl text-xs font-outfit font-black transition-all bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-200">
                                    📚 REDIGIR (<?= $temasPorBanca['REDIGIR'] ?? 0 ?>)
                                </button>
                            </div>

                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400">
                                Mostrando <strong id="countVisiveis" class="text-indigo-600 dark:text-indigo-400"><?= count($temas) ?></strong> de <?= count($temas) ?> propostas com coletânea
                            </span>
                        </div>

                        <!-- BARRA DE PESQUISA & EIXO TEMÁTICO -->
                        <div class="flex flex-col sm:flex-row items-center gap-3 pt-2.5 border-t border-slate-100 dark:border-slate-700/60">
                            <!-- EIXO TEMÁTICO DROPDOWN -->
                            <div class="relative w-full sm:w-80">
                                <select id="selectEixoFiltro" onchange="aplicarFiltrosTemas()" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs font-bold text-slate-700 dark:text-slate-200 focus:border-indigo-500 focus:outline-none cursor-pointer">
                                    <option value="">🎯 Todos os Eixos Temáticos (580+ temas)</option>
                                    <option value="tecnologia">💻 Tecnologia & Cultura Digital</option>
                                    <option value="meio ambiente">🌱 Meio Ambiente & Sustentabilidade</option>
                                    <option value="saúde">🏥 Saúde Pública & Bioética</option>
                                    <option value="educação">🎓 Educação & Sociedade</option>
                                    <option value="cidadania">⚖️ Cidadania & Direitos Humanos</option>
                                    <option value="trabalho">💼 Trabalho & Economia</option>
                                    <option value="cultura">🎭 Cultura, Mídia & Comportamento</option>
                                    <option value="segurança">🛡️ Segurança Pública & Justiça</option>
                                    <option value="política">🏛️ Política, Democracia & Ética</option>
                                    <option value="urbanismo">🏙️ Urbanismo & Moradia</option>
                                </select>
                            </div>

                            <!-- BUSCADOR EM TEMPO REAL -->
                            <div class="relative flex-1 w-full">
                                <input type="text" id="inputBuscaTema" onkeyup="aplicarFiltrosTemas()" placeholder="Buscar por tema, filósofo (Bauman, Foucault...), palavra-chave ou ano..." class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs font-medium text-slate-900 dark:text-white focus:outline-none pr-8">
                                <i class="bi bi-search absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <!-- GRID DE CARDS DOS TEMAS -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="gridTemas">
                        <?php foreach ($temas as $t): ?>
                            <?php
                            $bancaUpper = strtoupper($t['banca'] ?? 'ENEM');
                            $corBanca = [
                                'ENEM' => 'bg-amber-100 text-amber-900 border-amber-300 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-700',
                                'UNESP' => 'bg-cyan-100 text-cyan-900 border-cyan-300 dark:bg-cyan-950/40 dark:text-cyan-300 dark:border-cyan-700',
                                'FUVEST' => 'bg-rose-100 text-rose-900 border-rose-300 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-700',
                                'UNICAMP' => 'bg-orange-100 text-orange-900 border-orange-300 dark:bg-orange-950/40 dark:text-orange-300 dark:border-orange-700',
                                'UERJ' => 'bg-purple-100 text-purple-900 border-purple-300 dark:bg-purple-950/40 dark:text-purple-300 dark:border-purple-700',
                                'SIMULADO' => 'bg-emerald-100 text-emerald-900 border-emerald-300 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-700',
                                'REDIGIR' => 'bg-red-100 text-red-900 border-red-300 dark:bg-red-950/40 dark:text-red-300 dark:border-red-700'
                            ][$bancaUpper] ?? 'bg-indigo-100 text-indigo-900 border-indigo-300';
                            ?>
                            <div class="tema-card bg-white dark:bg-slate-800 rounded-2xl border-2 border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between gap-4" data-banca="<?= $bancaUpper ?>" data-titulo="<?= htmlspecialchars($t['titulo']) ?>" data-eixo="<?= htmlspecialchars($t['eixo_tematico'] ?? '') ?>">
                                <div class="space-y-2.5">
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-1.5">
                                            <span class="px-2.5 py-0.5 rounded-lg border text-[11px] font-outfit font-black uppercase <?= $corBanca ?>">
                                                <?= $bancaUpper ?> <?= htmlspecialchars($t['ano'] ?? '') ?>
                                            </span>
                                            <span class="px-2 py-0.5 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-[10px] font-bold">
                                                <?= htmlspecialchars($t['eixo_tematico'] ?? 'Geral') ?>
                                            </span>
                                        </div>
                                        <span class="text-[11px] text-slate-400 font-bold">
                                            <?= htmlspecialchars($t['genero_textual'] ?? 'Dissertativo') ?>
                                        </span>
                                    </div>

                                    <h3 class="font-outfit font-extrabold text-base text-slate-900 dark:text-white leading-snug">
                                        <?= htmlspecialchars($t['titulo']) ?>
                                    </h3>

                                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-2">
                                        <?= htmlspecialchars($t['descricao']) ?>
                                    </p>
                                </div>

                                <div class="pt-3 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between gap-2">
                                    <button type="button" id="btnToggleColetanea-<?= $t['id'] ?>" onclick="toggleColetaneaInline(<?= $t['id'] ?>)" class="btn-coletanea-toggle px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-outfit font-bold text-xs transition-all flex items-center gap-1.5" title="Ver textos motivadores desta proposta">
                                        <i class="bi bi-file-text" id="iconColetanea-<?= $t['id'] ?>"></i>
                                        <span id="txtColetanea-<?= $t['id'] ?>">Ler Coletânea</span>
                                    </button>

                                    <button type="button" onclick="iniciarEscritaComTema(<?= $t['id'] ?>)" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-outfit font-black text-xs shadow-sm transition-all flex items-center gap-1.5">
                                        <span>Escrever Agora</span>
                                        <i class="bi bi-arrow-right"></i>
                                    </button>
                                </div>

                                <!-- GAVETA INLINE DA COLETÂNEA (EXPANDE DIRETO NO CARD SEM NADA SUMIR DA TELA!) -->
                                <div id="drawerColetanea-<?= $t['id'] ?>" class="hidden mt-3 pt-3 border-t-2 border-indigo-100 dark:border-indigo-900/40 space-y-3">
                                    <!-- Cabeçalho da gaveta -->
                                    <div class="flex items-center justify-between bg-indigo-50 dark:bg-indigo-950/40 p-2.5 rounded-xl border border-indigo-200 dark:border-indigo-900/50">
                                        <div class="flex items-center gap-2 text-indigo-900 dark:text-indigo-300 font-outfit font-black text-xs">
                                            <i class="bi bi-book-half text-sm text-indigo-600 dark:text-indigo-400"></i>
                                            <span>Coletânea Oficial & Orientações</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <button type="button" onclick="verPropostaCompleta(<?= $t['id'] ?>)" class="px-2.5 py-1 rounded-lg bg-white dark:bg-slate-800 border border-indigo-200 dark:border-slate-700 text-indigo-700 dark:text-indigo-300 hover:bg-indigo-50 text-[11px] font-bold transition flex items-center gap-1" title="Abrir em Janela Cheia">
                                                <i class="bi bi-arrows-fullscreen text-[10px]"></i>
                                                <span class="hidden sm:inline">Tela Cheia</span>
                                            </button>
                                            <button type="button" onclick="toggleColetaneaInline(<?= $t['id'] ?>)" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-white text-xs" title="Recolher">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Conteúdo dos textos motivadores com scroll controlado -->
                                    <div id="conteudoDrawer-<?= $t['id'] ?>" class="max-h-96 overflow-y-auto pr-1 space-y-3 text-xs">
                                        <!-- Renderizado dinamicamente via JS ao expandir -->
                                    </div>

                                    <!-- Rodapé da gaveta -->
                                    <div class="pt-2 flex items-center justify-between gap-2 border-t border-slate-100 dark:border-slate-700/60">
                                        <button type="button" onclick="toggleColetaneaInline(<?= $t['id'] ?>)" class="px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 text-slate-600 dark:text-slate-300 font-outfit font-bold text-xs flex items-center gap-1">
                                            <i class="bi bi-chevron-up"></i>
                                            <span>Recolher</span>
                                        </button>
                                        <button type="button" onclick="iniciarEscritaComTema(<?= $t['id'] ?>)" class="px-3.5 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-outfit font-black text-xs shadow-sm flex items-center gap-1.5">
                                            <span>Redigir Este Tema</span>
                                            <i class="bi bi-arrow-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- ========================================================================= -->
                <!-- ABA 2: BANCADA DE ESCRITA (SPLIT-SCREEN INTERATIVO COM COLETÂNEA AO LADO) -->
                <!-- ========================================================================= -->
                <div id="tabContentEscrever" class="hidden space-y-6">
                    <form id="formRedacao" onsubmit="enviarRedacao(event)" class="bg-white dark:bg-slate-800 rounded-3xl border-2 border-slate-200 dark:border-slate-700 p-6 sm:p-8 shadow-[0_4px_0_0_#e2e8f0] dark:shadow-none space-y-6">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken()) ?>">
                        <input type="hidden" id="inputBancaAtiva" name="banca" value="ENEM">

                        <!-- TOPO DA BANCADA: SELETOR DE TEMA + CRONÔMETRO -->
                        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 pb-4 border-b border-slate-100 dark:border-slate-700">
                            <div class="flex-1 w-full">
                                <label class="block font-outfit font-extrabold text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                                    Proposta / Tema Selecionado
                                </label>
                                <div class="relative">
                                    <select id="temaSelect" name="tema_id" onchange="aoMudarTema()" class="w-full bg-slate-50 dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-slate-900 dark:text-slate-100 font-bold text-sm focus:border-indigo-500 focus:outline-none transition-all pr-10 cursor-pointer">
                                        <?php foreach ($temasAgrupados as $bancaNome => $listaTemas): ?>
                                            <optgroup label="🏛️ <?= htmlspecialchars($bancaNome) ?> (<?= count($listaTemas) ?> propostas)">
                                                <?php foreach ($listaTemas as $t): ?>
                                                    <option value="<?= $t['id'] ?>">
                                                        [<?= htmlspecialchars($t['banca']) ?> <?= htmlspecialchars($t['ano']) ?>] <?= htmlspecialchars($t['titulo']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                        <?php endforeach; ?>
                                        <option value="custom">✍️ Digitar Outro Tema Personalizado...</option>
                                    </select>
                                    <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                </div>

                                <div id="divTemaCustom" class="mt-3 hidden">
                                    <input type="text" id="temaCustom" name="tema_custom" placeholder="Digite o título ou pergunta do tema personalizado..." class="w-full bg-slate-50 dark:bg-slate-900 border-2 border-indigo-400 rounded-2xl px-4 py-3 text-slate-900 dark:text-slate-100 font-medium text-sm focus:outline-none">
                                </div>
                            </div>

                            <!-- CRONÔMETRO DE PROVA -->
                            <div class="bg-slate-50 dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-700 flex items-center gap-3 shrink-0">
                                <div class="text-right">
                                    <span class="block text-[10px] font-black uppercase text-slate-400">Tempo de Prova</span>
                                    <span id="displayTimer" class="font-outfit font-black text-xl text-indigo-600 dark:text-indigo-400">00:00:00</span>
                                </div>
                                <button type="button" id="btnTimerToggle" onclick="toggleTimer()" class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center hover:bg-indigo-700 transition-all shadow-sm" title="Iniciar/Pausar Cronômetro">
                                    <i class="bi bi-play-fill text-xl" id="iconTimer"></i>
                                </button>
                            </div>
                        </div>

                        <!-- PAINEL SPLIT: COLETÂNEA DE TEXTOS MOTIVADORES (COLAPSÁVEL OU FIXO) -->
                        <div id="cardMotivadores" class="bg-indigo-50/50 dark:bg-indigo-950/20 border-2 border-indigo-200 dark:border-indigo-900/50 rounded-2xl p-4 sm:p-5 transition-all">
                            <div class="flex items-center justify-between gap-3 mb-3 cursor-pointer select-none" onclick="toggleMotivadores()">
                                <div class="flex items-center gap-2 text-indigo-800 dark:text-indigo-300 font-outfit font-extrabold text-xs uppercase tracking-wide">
                                    <i class="bi bi-book-half text-base"></i>
                                    <span>Textos Motivadores Oficiais & Orientações da Prova</span>
                                </div>
                                <button type="button" onclick="event.stopPropagation(); toggleMotivadores();" class="text-xs text-indigo-600 dark:text-indigo-400 font-bold hover:underline flex items-center gap-1.5">
                                    <i class="bi bi-eye-slash-fill" id="iconBtnMotivadores"></i>
                                    <span id="txtBtnMotivadores">Ocultar Coletânea</span>
                                </button>
                            </div>

                            <div id="conteudoMotivadores" class="space-y-3">
                                <div id="boxOrientacoesBanca" class="p-3 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900/40 text-xs text-amber-900 dark:text-amber-200 font-medium"></div>
                                <div id="descTemaText" class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed italic"></div>
                                <div id="textosMotivadoresText" class="text-xs text-slate-800 dark:text-slate-200 leading-relaxed bg-white dark:bg-slate-900 p-4 rounded-xl border border-indigo-100 dark:border-slate-700 max-h-[36rem] overflow-y-auto"></div>
                            </div>
                        </div>

                        <!-- MODO DE ENVIO: DIGITAR vs FOTO / PDF -->
                        <div class="flex items-center justify-between gap-4">
                            <label class="font-outfit font-extrabold text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Formato do Envio:
                            </label>
                            <div class="inline-flex p-1 bg-slate-100 dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-700">
                                <button type="button" id="btnModoTexto" onclick="toggleTipoEnvio('texto')" class="px-4 py-1.5 rounded-lg text-xs font-outfit font-black transition-all bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm">
                                    <i class="bi bi-keyboard-fill me-1"></i> Digitar Folha
                                </button>
                                <button type="button" id="btnModoArquivo" onclick="toggleTipoEnvio('arquivo')" class="px-4 py-1.5 rounded-lg text-xs font-outfit font-black transition-all text-slate-500 hover:text-slate-800 dark:hover:text-white">
                                    <i class="bi bi-camera-fill me-1"></i> Enviar Foto/PDF
                                </button>
                            </div>
                        </div>
                        <input type="hidden" id="tipoEnvioInput" name="tipo_envio" value="texto">

                        <!-- ÁREA DE TEXTO: FOLHA PAUTADA -->
                        <div id="containerTexto" class="space-y-2">
                            <div class="flex items-center justify-between text-xs font-bold text-slate-500 dark:text-slate-400">
                                <span>Folha Pautada Oficial (Dissertativo-Argumentativo)</span>
                                <div class="flex items-center gap-3">
                                    <span id="badgeLinhas" class="px-2.5 py-0.5 rounded-md bg-slate-100 dark:bg-slate-700">0 / 30 linhas</span>
                                    <span id="badgePalavras">0 palavras</span>
                                </div>
                            </div>

                            <textarea id="textoRedacao" name="texto_redacao" rows="18" oninput="atualizarContadoresRedacao()" placeholder="Comece aqui a introdução com sua tese central...&#10;&#10;Em seguida, estruture o desenvolvimento com argumentos consistentes e repertório legítimo...&#10;&#10;Finalize com uma conclusão articulada (proposta de intervenção completa no ENEM ou reflexão crítica na UNESP/FUVEST)." class="redacao-folha w-full"></textarea>

                            <div class="flex items-center justify-between text-[11px] text-slate-400 px-1">
                                <span>* ENEM: máximo 30 linhas. FUVEST e UNESP: inclua um título expressivo.</span>
                                <span id="badgeAlertaLinhas" class="hidden font-bold text-rose-500"></span>
                            </div>
                        </div>

                        <!-- UPLOAD DE FOTO OU PDF DA FOLHA MANUSCRITA -->
                        <div id="containerArquivo" class="hidden space-y-3">
                            <div class="dropzone-redacao p-8 text-center cursor-pointer relative" onclick="document.getElementById('arquivoInput').click()" id="dropzoneBox">
                                <input type="file" id="arquivoInput" name="arquivo_redacao" accept="image/jpeg,image/png,image/webp,application/pdf" onchange="aoSelecionarArquivo(event)" class="hidden">
                                
                                <div id="dropzonePrompt" class="space-y-3">
                                    <div class="w-14 h-14 rounded-2xl bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mx-auto">
                                        <i class="bi bi-cloud-arrow-up-fill text-2xl"></i>
                                    </div>
                                    <div>
                                        <p class="font-outfit font-bold text-slate-800 dark:text-slate-200 text-sm mb-1">
                                            Clique para selecionar ou arraste a foto da folha manuscrita
                                        </p>
                                        <span class="text-xs text-slate-500 dark:text-slate-400">Formatos aceitos: JPG, PNG, WEBP ou PDF (até 15MB)</span>
                                    </div>
                                </div>

                                <div id="dropzonePreview" class="hidden space-y-2">
                                    <i class="bi bi-check-circle-fill text-3xl text-emerald-500"></i>
                                    <p id="nomeArquivoSelecionado" class="font-outfit font-bold text-sm text-slate-800 dark:text-slate-200 mb-0"></p>
                                    <span class="text-xs text-indigo-600 dark:text-indigo-400 font-bold hover:underline">Clique para trocar de arquivo</span>
                                </div>
                            </div>
                        </div>

                        <!-- BOTÃO DE ENVIO COM EFEITO 3D -->
                        <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                            <button type="submit" id="btnSubmitRedacao" class="w-full py-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-outfit font-black text-base shadow-[0_5px_0_0_#3730a3] active:translate-y-1 active:shadow-none transition-all flex items-center justify-center gap-2">
                                <i class="bi bi-magic text-xl"></i>
                                <span>Corrigir com IA (Gemini 3.5) 🚀</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- ========================================================================= -->
                <!-- ABA 3: GUIA DE REPERTÓRIOS SOCIOCULTURAIS & CITAÇÕES LEGÍTIMAS -->
                <!-- ========================================================================= -->
                <div id="tabContentRepertorio" class="hidden space-y-6">
                    <div class="bg-white dark:bg-slate-800 rounded-3xl border-2 border-slate-200 dark:border-slate-700 p-6 sm:p-8 shadow-sm space-y-6">
                        
                        <div>
                            <span class="px-3 py-1 rounded-full text-xs font-black bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 uppercase">
                                Banco de Filosofia & Legislação
                            </span>
                            <h2 class="font-outfit font-black text-2xl text-slate-900 dark:text-white mt-2 mb-1">
                                Repertórios Socioculturais para Vestibulares
                            </h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                                Citações legítimas, alusões históricas e artigos constitucionais categorizados por eixo temático para fundamentar sua argumentação.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- EIXO 1: CIDADANIA E DIREITOS -->
                            <div class="p-5 rounded-2xl border-2 border-slate-200 dark:border-slate-700 space-y-3 bg-slate-50 dark:bg-slate-900/50">
                                <div class="flex items-center gap-2 text-indigo-600 dark:text-indigo-400 font-outfit font-black text-sm">
                                    <i class="bi bi-book-fill"></i>
                                    <span>Constituição Federal de 1988</span>
                                </div>
                                <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed mb-0">
                                    <strong>Art. 5º e Art. 6º:</strong> Assegura a dignidade da pessoa humana e direitos sociais inalienáveis (educação, saúde, trabalho, moradia e segurança). Pode ser confrontado com a <em>"Cidadania de Papel"</em> de Gilberto Dimenstein, quando o direito existe na lei mas não na realidade do cidadão.
                                </p>
                            </div>

                            <!-- EIXO 2: FILOSOFIA & SOCIEDADE -->
                            <div class="p-5 rounded-2xl border-2 border-slate-200 dark:border-slate-700 space-y-3 bg-slate-50 dark:bg-slate-900/50">
                                <div class="flex items-center gap-2 text-purple-600 dark:text-purple-400 font-outfit font-black text-sm">
                                    <i class="bi bi-person-workspace"></i>
                                    <span>Zygmunt Bauman (Modernidade Líquida)</span>
                                </div>
                                <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed mb-0">
                                    Os laços humanos, valores e instituições perderam a solidez, tornando-se fluidos e efêmeros. Ideal para temas sobre relações digitais, individualismo, consumismo desenfreado e fragilidade das redes comunitárias.
                                </p>
                            </div>

                            <!-- EIXO 3: POLÍTICA & ESTADO -->
                            <div class="p-5 rounded-2xl border-2 border-slate-200 dark:border-slate-700 space-y-3 bg-slate-50 dark:bg-slate-900/50">
                                <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400 font-outfit font-black text-sm">
                                    <i class="bi bi-shield-fill"></i>
                                    <span>Thomas Hobbes & O Contrato Social</span>
                                </div>
                                <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed mb-0">
                                    O Estado foi constituído para superar o estado de natureza e garantir a ordem e a segurança dos indivíduos. Quando o Estado é omisso, rompe-se o contrato social implícito com a população.
                                </p>
                            </div>

                            <!-- EIXO 4: MEIO AMBIENTE & CLIMA -->
                            <div class="p-5 rounded-2xl border-2 border-slate-200 dark:border-slate-700 space-y-3 bg-slate-50 dark:bg-slate-900/50">
                                <div class="flex items-center gap-2 text-amber-600 dark:text-amber-400 font-outfit font-black text-sm">
                                    <i class="bi bi-tree-fill"></i>
                                    <span>Hans Jonas & O Princípio Responsabilidade</span>
                                </div>
                                <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed mb-0">
                                    Defende que a humanidade tem a obrigação ética de agir hoje preservando as condições de vida para as gerações futuras. Excelente para propostas sobre sustentabilidade, racismo ambiental e transição ecológica.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ========================================================================= -->
                <!-- ABA 4: HISTÓRICO DE REDAÇÕES DO ALUNO -->
                <!-- ========================================================================= -->
                <div id="tabContentHistorico" class="hidden space-y-4">
                    <?php if (empty($historico)): ?>
                        <div class="bg-white dark:bg-slate-800 rounded-3xl border-2 border-slate-200 dark:border-slate-700 p-12 text-center shadow-sm">
                            <div class="w-16 h-16 rounded-2xl bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mx-auto mb-4">
                                <i class="bi bi-journal-text text-3xl"></i>
                            </div>
                            <h3 class="font-outfit font-black text-lg text-slate-900 dark:text-white mb-1">Nenhuma redação corrigida ainda</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto mb-6">
                                Escolha uma das propostas acima e escreva sua primeira redação para receber sua nota detalhada e ganhar XP!
                            </p>
                            <button onclick="switchPortalTab('banco')" class="px-6 py-2.5 rounded-xl bg-indigo-600 text-white font-outfit font-extrabold text-sm shadow-sm hover:bg-indigo-700 transition-all">
                                Explorar Banco de Temas
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="grid grid-cols-1 gap-4">
                            <?php foreach ($historico as $red): ?>
                                <?php
                                $nota = (int)$red['nota_final'];
                                if ($nota >= 900) {
                                    $badgeCor = 'bg-amber-100 text-amber-800 border-amber-300 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-700';
                                    $tagIcon = '🥇';
                                } elseif ($nota >= 700) {
                                    $badgeCor = 'bg-emerald-100 text-emerald-800 border-emerald-300 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-700';
                                    $tagIcon = '🎯';
                                } elseif ($nota >= 500) {
                                    $badgeCor = 'bg-indigo-100 text-indigo-800 border-indigo-300 dark:bg-indigo-950/40 dark:text-indigo-300 dark:border-indigo-700';
                                    $tagIcon = '📝';
                                } else {
                                    $badgeCor = 'bg-rose-100 text-rose-800 border-rose-300 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-700';
                                    $tagIcon = '⚠️';
                                }
                                $bancaTag = strtoupper($red['banca'] ?? 'ENEM');
                                ?>
                                <div class="bg-white dark:bg-slate-800 rounded-2xl border-2 border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition-all flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                    <div class="space-y-1 min-w-0 flex-1">
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-black bg-indigo-100 dark:bg-indigo-900/60 text-indigo-800 dark:text-indigo-200 uppercase">
                                                <?= $bancaTag ?>
                                            </span>
                                            <span class="text-xs text-slate-400 font-bold">
                                                <i class="bi bi-calendar3 me-1"></i><?= date('d/m/Y \à\s H:i', strtotime($red['created_at'])) ?>
                                            </span>
                                            <span class="text-xs px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-semibold uppercase">
                                                <?= $red['tipo_envio'] === 'arquivo' ? '📷 Foto/OCR' : '⌨️ Digitada' ?>
                                            </span>
                                            <?php if ($red['xp_ganho'] > 0): ?>
                                                <span class="text-xs px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 dark:bg-indigo-950 dark:text-indigo-300 font-extrabold">
                                                    +<?= $red['xp_ganho'] ?> XP
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <h4 class="font-outfit font-extrabold text-base text-slate-900 dark:text-white truncate">
                                            <?= htmlspecialchars($red['titulo']) ?>
                                        </h4>
                                        <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500 dark:text-slate-400">
                                            <span>C1: <strong><?= $red['nota_c1'] ?></strong></span>
                                            <span>C2: <strong><?= $red['nota_c2'] ?></strong></span>
                                            <span>C3: <strong><?= $red['nota_c3'] ?></strong></span>
                                            <span>C4: <strong><?= $red['nota_c4'] ?></strong></span>
                                            <span>C5: <strong><?= $red['nota_c5'] ?></strong></span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3 shrink-0 w-full sm:w-auto justify-between sm:justify-end">
                                        <div class="text-right">
                                            <div class="px-3.5 py-1.5 rounded-xl border text-sm font-outfit font-black <?= $badgeCor ?>">
                                                <?= $tagIcon ?> <?= $nota ?> <span class="text-[11px] font-bold opacity-75">/ 1000</span>
                                            </div>
                                        </div>

                                        <a href="redacao_resultado.php?id=<?= $red['id'] ?>" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-outfit font-extrabold text-xs shadow-sm transition-all flex items-center gap-1.5">
                                            <span>Ver Relatório</span>
                                            <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

            </section>
        </div>
    </main>

    <!-- MODAL: VISUALIZADOR DA PROPOSTA COMPLETA & TEXTOS MOTIVADORES -->
    <div id="modalProposta" onclick="if(event.target === this) fecharModalProposta()" class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm hidden items-center justify-center p-4 transition-all" style="z-index: 9999;">
        <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-2xl w-full p-6 sm:p-7 border-2 border-slate-200 dark:border-slate-700 shadow-2xl relative max-h-[85vh] flex flex-col">
            <button type="button" onclick="fecharModalProposta()" class="absolute right-5 top-5 p-2 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-700 transition" title="Fechar (ESC)">
                <i class="bi bi-x-lg text-base"></i>
            </button>

            <div class="space-y-1 pb-4 border-b border-slate-100 dark:border-slate-700 pr-10">
                <span id="modalPropostaBanca" class="px-2.5 py-0.5 rounded-md text-[11px] font-black uppercase bg-indigo-100 text-indigo-800 dark:bg-indigo-900/60 dark:text-indigo-300"></span>
                <h3 id="modalPropostaTitulo" class="font-outfit font-black text-lg text-slate-900 dark:text-white leading-snug"></h3>
            </div>

            <div class="overflow-y-auto py-4 space-y-4 flex-1 pr-1">
                <div id="modalPropostaOrientacoes" class="p-3.5 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900/40 text-xs text-amber-900 dark:text-amber-200 font-medium"></div>
                <div id="modalPropostaTextos" class="text-xs text-slate-800 dark:text-slate-200 leading-relaxed bg-slate-50 dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-700 font-sans"></div>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between gap-3">
                <button type="button" onclick="fecharModalProposta()" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-outfit font-bold text-xs hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    Fechar
                </button>
                <button type="button" id="btnModalPraticar" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-outfit font-black text-xs shadow-sm transition-all flex items-center gap-2">
                    <span>Praticar Esta Redação</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL: CONFIGURAÇÃO DE CHAVE DO GOOGLE GEMINI -->
    <div id="modalApiKey" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-md w-full p-6 sm:p-7 border-2 border-slate-200 dark:border-slate-700 shadow-2xl relative">
            <button onclick="closeApiKeyModal()" class="absolute right-5 top-5 text-slate-400 hover:text-slate-600 dark:hover:text-white">
                <i class="bi bi-x-lg text-lg"></i>
            </button>

            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                    <i class="bi bi-key-fill fs-5"></i>
                </div>
                <div>
                    <h3 class="font-outfit font-black text-base text-slate-900 dark:text-white mb-0">Configurar IA do Gemini</h3>
                    <p class="text-xs text-slate-400 mb-0">Gerencie sua chave e modelo da IA</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block font-outfit font-extrabold text-xs text-slate-500 dark:text-slate-400 mb-1">
                        Modelo de Inteligência Artificial:
                    </label>
                    <select id="modalModelSelect" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-sm font-semibold text-slate-900 dark:text-white focus:outline-none">
                        <option value="gemini-3.5-flash" selected>Gemini 3.5 Flash (Geração 3.5 - Ultrarrápido, Moderno & Mais Inteligente)</option>
                        <option value="gemini-3-flash-preview">Gemini 3 Flash Preview</option>
                        <option value="gemini-2.5-flash">Gemini 2.5 Flash</option>
                    </select>
                </div>

                <div>
                    <label class="block font-outfit font-extrabold text-xs text-slate-500 dark:text-slate-400 mb-1">
                        Chave de API do Gemini:
                    </label>
                    <input type="password" id="modalApiKeyInput" placeholder="Cole sua chave AIzaSy..." class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white focus:outline-none font-mono">
                    <p class="text-[11px] text-slate-400 mt-1.5">
                        Obtenha sua chave grátis em <a href="https://aistudio.google.com/app/apikey" target="_blank" class="text-indigo-600 dark:text-indigo-400 font-bold underline">Google AI Studio</a>.
                    </p>
                </div>

                <div id="modalStatusMsg" class="hidden text-xs p-3 rounded-xl font-medium"></div>

                <div class="flex items-center gap-2 pt-2">
                    <button type="button" onclick="salvarChaveGemini()" id="btnSalvarChave" class="flex-1 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-outfit font-extrabold text-sm shadow-sm transition-all">
                        Testar & Salvar Chave
                    </button>
                    <button type="button" onclick="closeApiKeyModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-outfit font-bold text-sm">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPTS JS DO PORTAL DA REDAÇÃO -->
    <script>
        // Dados de todos os temas injetados para consulta instantânea sem delay
        const TODOS_TEMAS = <?= json_encode($temas, JSON_UNESCAPED_UNICODE) ?>;

        // Controle de Abas
        function switchPortalTab(tab) {
            const tabs = ['banco', 'escrever', 'repertorio', 'historico'];
            tabs.forEach(t => {
                const btn = document.getElementById('tabBtn' + t.charAt(0).toUpperCase() + t.slice(1));
                const content = document.getElementById('tabContent' + t.charAt(0).toUpperCase() + t.slice(1));
                if (t === tab) {
                    btn.className = "flex-1 min-w-[130px] py-3 rounded-xl font-outfit font-extrabold text-xs sm:text-sm transition-all flex items-center justify-center gap-2 bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm border border-slate-200 dark:border-slate-600";
                    content.classList.remove('hidden');
                } else {
                    btn.className = "flex-1 min-w-[130px] py-3 rounded-xl font-outfit font-extrabold text-xs sm:text-sm transition-all flex items-center justify-center gap-2 text-slate-500 hover:text-slate-800 dark:hover:text-slate-200";
                    content.classList.add('hidden');
                }
            });
        }

        let bancaAtivaFiltro = 'TODOS';

        // Helper de normalização para busca sem acentos e case-insensitive
        function normalizarTexto(txt) {
            return (txt || '').toString().normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase().trim();
        }

        // Escape seguro para injeção HTML
        function escapeHtml(str) {
            if (!str) return '';
            return str
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        // Renderizador profissional de textos motivadores (separa em cards, renderiza fotos/infográficos e fontes)
        function renderizarTextosMotivadores(rawText) {
            if (!rawText || !rawText.trim()) {
                return '<p class="text-xs text-slate-400 italic">Textos motivadores não disponíveis para este tema.</p>';
            }

            const linhas = rawText.split('\n');
            let html = '<div class="space-y-4 font-sans">';
            let dentroDeTextoCard = false;

            linhas.forEach(linha => {
                let l = linha.trim();
                if (!l) return;

                // 1. Cabeçalho de Texto Motivador (ex: TEXTO I, TEXTO II — DADOS...)
                if (/^TEXTO\s+[IVXLCDM]+/i.test(l)) {
                    if (dentroDeTextoCard) {
                        html += '</div></div>';
                    }
                    dentroDeTextoCard = true;
                    html += `
                        <div class="rounded-2xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow-sm space-y-3 transition-all">
                            <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-700/60">
                                <span class="px-2.5 py-1 rounded-lg bg-indigo-600 text-white font-outfit font-black text-[11px] uppercase tracking-wide shadow-sm flex items-center gap-1.5">
                                    <i class="bi bi-file-earmark-text-fill"></i>
                                    ${escapeHtml(l)}
                                </span>
                            </div>
                            <div class="space-y-2.5 text-xs text-slate-700 dark:text-slate-300 leading-relaxed font-normal">
                    `;
                    return;
                }

                // Se houver texto antes de qualquer cabeçalho "TEXTO I"
                if (!dentroDeTextoCard) {
                    dentroDeTextoCard = true;
                    html += `
                        <div class="rounded-2xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow-sm space-y-3 transition-all">
                            <div class="space-y-2.5 text-xs text-slate-700 dark:text-slate-300 leading-relaxed font-normal">
                    `;
                }

                // 2. Imagem / Infográfico / Foto: [IMAGEM: url | LEGENDA: legenda]
                const imgMatch = l.match(/\[IMAGEM:\s*([^|]+)\s*\|\s*LEGENDA:\s*([^\]]+)\]/i);
                if (imgMatch) {
                    const url = imgMatch[1].trim();
                    const legenda = imgMatch[2].trim();
                    html += `
                        <figure class="my-3 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 shadow-sm">
                            <div class="relative bg-slate-950 flex items-center justify-center max-h-72 overflow-hidden">
                                <img src="${escapeHtml(url)}" alt="${escapeHtml(legenda)}" class="w-full h-auto max-h-72 object-cover transition-transform duration-300 hover:scale-105" loading="lazy" onerror="this.closest('figure').style.display='none'">
                            </div>
                            <figcaption class="p-2.5 bg-slate-100/90 dark:bg-slate-800/90 border-t border-slate-200 dark:border-slate-700 text-[11px] text-slate-600 dark:text-slate-400 font-medium flex items-center gap-2">
                                <i class="bi bi-image-fill text-indigo-500 shrink-0"></i>
                                <span>${escapeHtml(legenda)}</span>
                            </figcaption>
                        </figure>
                    `;
                    return;
                }

                // 3. Fonte Oficial: [FONTE: ...]
                const fonteMatch = l.match(/\[FONTE:\s*([^\]]+)\]/i);
                if (fonteMatch) {
                    const fonte = fonteMatch[1].trim();
                    html += `
                        <div class="mt-2.5 pt-2 border-t border-dashed border-slate-200 dark:border-slate-700 flex items-center gap-1.5 text-[11px] text-slate-500 dark:text-slate-400 font-bold italic">
                            <i class="bi bi-quote text-indigo-500 text-sm shrink-0"></i>
                            <span>${escapeHtml(fonte)}</span>
                        </div>
                    `;
                    return;
                }

                // 4. Parágrafo de texto normal
                html += `<p class="m-0">${escapeHtml(l)}</p>`;
            });

            if (dentroDeTextoCard) {
                html += '</div></div>';
            }

            html += '</div>';
            return html;
        }

        // Filtro por Banca
        function filtrarBanca(banca) {
            bancaAtivaFiltro = banca;
            const pills = document.querySelectorAll('#bancaFilters .banca-pill');
            pills.forEach(p => {
                const clickAttr = p.getAttribute('onclick') || '';
                if (clickAttr.includes(`'${banca}'`)) {
                    p.className = 'banca-pill active px-3.5 py-1.5 rounded-xl text-xs font-outfit font-black transition-all bg-indigo-600 text-white shadow-sm';
                } else {
                    p.className = 'banca-pill px-3.5 py-1.5 rounded-xl text-xs font-outfit font-black transition-all bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-200';
                }
            });
            aplicarFiltrosTemas();
        }

        // Filtros combinados: Banca + Eixo Temático + Busca textual
        function aplicarFiltrosTemas() {
            const termoNorm = normalizarTexto(document.getElementById('inputBuscaTema')?.value);
            const eixoFiltroNorm = normalizarTexto(document.getElementById('selectEixoFiltro')?.value);
            const cards = document.querySelectorAll('.tema-card');
            let visiveis = 0;

            cards.forEach(card => {
                const cardBanca = card.getAttribute('data-banca') || '';
                const cardBancaNorm = normalizarTexto(cardBanca);
                const tituloNorm = normalizarTexto(card.getAttribute('data-titulo'));
                const eixoNorm = normalizarTexto(card.getAttribute('data-eixo'));

                const matchBanca = (bancaAtivaFiltro === 'TODOS' || cardBanca === bancaAtivaFiltro || cardBancaNorm === normalizarTexto(bancaAtivaFiltro));
                const matchEixo = (eixoFiltroNorm === '' || eixoNorm.includes(eixoFiltroNorm));
                const matchTermo = (termoNorm === '' || tituloNorm.includes(termoNorm) || eixoNorm.includes(termoNorm) || cardBancaNorm.includes(termoNorm));

                if (matchBanca && matchEixo && matchTermo) {
                    card.style.display = 'flex';
                    visiveis++;
                } else {
                    card.style.display = 'none';
                }
            });

            const countEl = document.getElementById('countVisiveis');
            if (countEl) {
                countEl.textContent = visiveis;
            }
        }

        // Alternar gaveta inline da coletânea diretamente no card (NADA SOME DA TELA!)
        function toggleColetaneaInline(id) {
            const drawer = document.getElementById('drawerColetanea-' + id);
            const conteudo = document.getElementById('conteudoDrawer-' + id);
            const btn = document.getElementById('btnToggleColetanea-' + id);
            const icon = document.getElementById('iconColetanea-' + id);
            const txt = document.getElementById('txtColetanea-' + id);

            if (!drawer) return;

            const estaAberto = !drawer.classList.contains('hidden');

            if (estaAberto) {
                // Fechar gaveta inline
                drawer.classList.add('hidden');
                if (btn) {
                    btn.classList.remove('bg-indigo-600', 'text-white', 'hover:bg-indigo-700');
                    btn.classList.add('bg-slate-100', 'text-slate-700', 'dark:bg-slate-700', 'dark:text-slate-200');
                }
                if (icon) icon.className = 'bi bi-file-text';
                if (txt) txt.textContent = 'Ler Coletânea';
            } else {
                // Se ainda não foi renderizado o conteúdo desta gaveta, renderizar agora
                if (conteudo && !conteudo.dataset.rendered) {
                    const tema = TODOS_TEMAS.find(t => parseInt(t.id) === parseInt(id));
                    if (tema) {
                        let htmlOri = '';
                        if (tema.orientacoes_especificas || tema.descricao) {
                            htmlOri = `
                                <div class="p-3 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900/40 text-xs text-amber-900 dark:text-amber-200 font-medium">
                                    <strong>Orientações ${escapeHtml(tema.banca || 'Oficiais')}:</strong> ${escapeHtml(tema.orientacoes_especificas || tema.descricao || '')}
                                </div>
                            `;
                        }
                        conteudo.innerHTML = htmlOri + renderizarTextosMotivadores(tema.textos_motivadores);
                        conteudo.dataset.rendered = "true";
                    }
                }

                // Abrir gaveta inline
                drawer.classList.remove('hidden');
                if (btn) {
                    btn.classList.remove('bg-slate-100', 'text-slate-700', 'dark:bg-slate-700', 'dark:text-slate-200');
                    btn.classList.add('bg-indigo-600', 'text-white', 'hover:bg-indigo-700');
                }
                if (icon) icon.className = 'bi bi-chevron-up';
                if (txt) txt.textContent = 'Recolher Coletânea';
            }
        }

        // Ações de Proposta em Tela Cheia (Modal)
        function verPropostaCompleta(id) {
            const tema = TODOS_TEMAS.find(t => parseInt(t.id) === parseInt(id));
            if (!tema) return;

            document.getElementById('modalPropostaBanca').textContent = `${tema.banca} ${tema.ano || ''} • ${tema.eixo_tematico || ''}`;
            document.getElementById('modalPropostaTitulo').textContent = tema.titulo;
            document.getElementById('modalPropostaOrientacoes').innerHTML = `<strong>Orientações da Prova:</strong> ${escapeHtml(tema.orientacoes_especificas || tema.descricao || '')}`;
            document.getElementById('modalPropostaTextos').innerHTML = renderizarTextosMotivadores(tema.textos_motivadores);

            const btnPraticar = document.getElementById('btnModalPraticar');
            btnPraticar.onclick = () => {
                fecharModalProposta();
                iniciarEscritaComTema(id);
            };

            const m = document.getElementById('modalProposta');
            m.classList.remove('hidden');
            m.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function fecharModalProposta() {
            const m = document.getElementById('modalProposta');
            m.classList.add('hidden');
            m.classList.remove('flex');
            document.body.style.overflow = '';
        }

        // Fechar modais ao pressionar tecla ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                fecharModalProposta();
                closeApiKeyModal();
            }
        });

        function iniciarEscritaComTema(id) {
            const select = document.getElementById('temaSelect');
            select.value = id;
            aoMudarTema();
            switchPortalTab('escrever');
            window.scrollTo({ top: 350, behavior: 'smooth' });
        }

        function aoMudarTema() {
            const select = document.getElementById('temaSelect');
            const val = select.value;
            const divCustom = document.getElementById('divTemaCustom');
            const cardMotivadores = document.getElementById('cardMotivadores');
            const descTemaText = document.getElementById('descTemaText');
            const textosMotivadoresText = document.getElementById('textosMotivadoresText');
            const boxOrientacoes = document.getElementById('boxOrientacoesBanca');
            const inputBanca = document.getElementById('inputBancaAtiva');

            if (val === 'custom') {
                divCustom.classList.remove('hidden');
                cardMotivadores.classList.add('hidden');
                inputBanca.value = 'ENEM';
            } else {
                divCustom.classList.add('hidden');
                const tema = TODOS_TEMAS.find(t => parseInt(t.id) === parseInt(val));
                if (tema) {
                    inputBanca.value = tema.banca || 'ENEM';

                    if (tema.orientacoes_especificas) {
                        boxOrientacoes.innerHTML = `<strong>Orientações ${escapeHtml(tema.banca)}:</strong> ${escapeHtml(tema.orientacoes_especificas)}`;
                        boxOrientacoes.classList.remove('hidden');
                    } else {
                        boxOrientacoes.classList.add('hidden');
                    }

                    if (tema.descricao || tema.textos_motivadores) {
                        descTemaText.textContent = tema.descricao || '';
                        textosMotivadoresText.innerHTML = renderizarTextosMotivadores(tema.textos_motivadores);
                        cardMotivadores.classList.remove('hidden');
                    } else {
                        cardMotivadores.classList.add('hidden');
                    }
                }
            }
        }

        function toggleMotivadores() {
            const c = document.getElementById('conteudoMotivadores');
            const btn = document.getElementById('txtBtnMotivadores');
            const icon = document.getElementById('iconBtnMotivadores');
            if (c.classList.contains('hidden')) {
                c.classList.remove('hidden');
                if (btn) btn.textContent = 'Ocultar Coletânea';
                if (icon) icon.className = 'bi bi-eye-slash-fill';
            } else {
                c.classList.add('hidden');
                if (btn) btn.textContent = 'Ver Coletânea';
                if (icon) icon.className = 'bi bi-eye-fill';
            }
        }

        function toggleTipoEnvio(tipo) {
            const cTexto = document.getElementById('containerTexto');
            const cArquivo = document.getElementById('containerArquivo');
            const btnT = document.getElementById('btnModoTexto');
            const btnA = document.getElementById('btnModoArquivo');
            const inputTipo = document.getElementById('tipoEnvioInput');

            inputTipo.value = tipo;

            if (tipo === 'arquivo') {
                cTexto.classList.add('hidden');
                cArquivo.classList.remove('hidden');
                btnA.className = "px-4 py-1.5 rounded-lg text-xs font-outfit font-black transition-all bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm";
                btnT.className = "px-4 py-1.5 rounded-lg text-xs font-outfit font-black transition-all text-slate-500 hover:text-slate-800 dark:hover:text-white";
            } else {
                cTexto.classList.remove('hidden');
                cArquivo.classList.add('hidden');
                btnT.className = "px-4 py-1.5 rounded-lg text-xs font-outfit font-black transition-all bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm";
                btnA.className = "px-4 py-1.5 rounded-lg text-xs font-outfit font-black transition-all text-slate-500 hover:text-slate-800 dark:hover:text-white";
            }
        }

        function atualizarContadoresRedacao() {
            const txt = document.getElementById('textoRedacao').value;
            const badgeLinhas = document.getElementById('badgeLinhas');
            const badgePalavras = document.getElementById('badgePalavras');
            const alerta = document.getElementById('badgeAlertaLinhas');

            const linhasQuebra = txt.split('\n');
            let totalLinhas = 0;
            linhasQuebra.forEach(l => {
                totalLinhas += Math.max(1, Math.ceil(l.length / 75));
            });
            if (txt.trim() === '') totalLinhas = 0;

            const palavras = txt.trim() === '' ? 0 : txt.trim().split(/\s+/).length;

            badgeLinhas.textContent = `${totalLinhas} / 30 linhas`;
            badgePalavras.textContent = `${palavras} palavras`;

            if (totalLinhas > 30) {
                alerta.textContent = '⚠️ Excedeu 30 linhas (limite oficial do ENEM/Vestibulares)!';
                alerta.classList.remove('hidden');
                badgeLinhas.className = 'px-2.5 py-0.5 rounded-md bg-rose-100 text-rose-700 font-black';
            } else if (totalLinhas > 0 && totalLinhas < 7) {
                alerta.textContent = '⚠️ Menos de 7 linhas é desclassificada!';
                alerta.classList.remove('hidden');
                badgeLinhas.className = 'px-2.5 py-0.5 rounded-md bg-amber-100 text-amber-700 font-bold';
            } else {
                alerta.classList.add('hidden');
                badgeLinhas.className = 'px-2.5 py-0.5 rounded-md bg-slate-100 dark:bg-slate-700';
            }
        }

        function aoSelecionarArquivo(event) {
            const input = event.target;
            const prompt = document.getElementById('dropzonePrompt');
            const preview = document.getElementById('dropzonePreview');
            const nome = document.getElementById('nomeArquivoSelecionado');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                nome.textContent = `${file.name} (${(file.size / (1024 * 1024)).toFixed(2)} MB)`;
                prompt.classList.add('hidden');
                preview.classList.remove('hidden');
            }
        }

        // Cronômetro
        let timerSeconds = 0;
        let timerInterval = null;
        function toggleTimer() {
            const icon = document.getElementById('iconTimer');
            if (timerInterval) {
                clearInterval(timerInterval);
                timerInterval = null;
                icon.className = 'bi bi-play-fill text-xl';
            } else {
                icon.className = 'bi bi-pause-fill text-xl';
                timerInterval = setInterval(() => {
                    timerSeconds++;
                    const h = String(Math.floor(timerSeconds / 3600)).padStart(2, '0');
                    const m = String(Math.floor((timerSeconds % 3600) / 60)).padStart(2, '0');
                    const s = String(timerSeconds % 60).padStart(2, '0');
                    document.getElementById('displayTimer').textContent = `${h}:${m}:${s}`;
                }, 1000);
            }
        }

        // Envio do formulário com animação
        function enviarRedacao(e) {
            e.preventDefault();

            const form = document.getElementById('formRedacao');
            const formData = new FormData(form);
            const btn = document.getElementById('btnSubmitRedacao');
            const tipoEnvio = formData.get('tipo_envio');
            const banca = formData.get('banca') || 'ENEM';

            if (tipoEnvio === 'texto') {
                const txt = (formData.get('texto_redacao') || '').trim();
                if (txt.length < 150) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Redação muito curta',
                        text: 'Digite pelo menos 150 caracteres para uma avaliação pedagógica válida.',
                        confirmButtonColor: '#4f46e5'
                    });
                    return;
                }
            } else {
                const arquivoInput = document.getElementById('arquivoInput');
                if (!arquivoInput.files || !arquivoInput.files[0]) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Arquivo não selecionado',
                        text: 'Por favor, selecione uma foto ou PDF da folha da sua redação.',
                        confirmButtonColor: '#4f46e5'
                    });
                    return;
                }
            }

            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Avaliando com Gemini 3.5...`;

            Swal.fire({
                title: `Avaliando Redação (${banca})...`,
                html: `
                    <div class="py-3 text-center space-y-3">
                        <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto animate-pulse">
                            <i class="bi bi-robot text-3xl"></i>
                        </div>
                        <p class="text-xs text-slate-500 font-medium">
                            Aplicando critérios da banca examinadora ${banca}, coerência textual, repertório sociocultural e detector de IA...
                        </p>
                    </div>
                `,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false
            });

            fetch('api/corrigir_redacao.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Redação Corrigida!',
                        text: `Nota Final: ${data.nota_final} pontos! Você conquistou +${data.xp_ganho} XP!`,
                        confirmButtonText: 'Ver Relatório Completo 🚀',
                        confirmButtonColor: '#4f46e5'
                    }).then(() => {
                        window.location.href = data.redirect_url;
                    });
                } else {
                    btn.disabled = false;
                    btn.innerHTML = `<i class="bi bi-magic text-xl"></i><span>Corrigir com IA (Gemini 3.5) 🚀</span>`;

                    Swal.fire({
                        icon: 'error',
                        title: 'Não foi possível corrigir',
                        text: data.message || 'Erro inesperado ao processar a redação.',
                        confirmButtonColor: '#4f46e5'
                    });
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = `<i class="bi bi-magic text-xl"></i><span>Corrigir com IA (Gemini 3.5) 🚀</span>`;
                Swal.fire({
                    icon: 'error',
                    title: 'Falha na Conexão',
                    text: 'Tempo limite ou erro de rede. Tente novamente.',
                    confirmButtonColor: '#4f46e5'
                });
            });
        }

        // Funções do Modal de Chave Gemini
        function openApiKeyModal() {
            document.getElementById('modalApiKey').classList.remove('hidden');
            document.getElementById('modalApiKey').classList.add('flex');
        }

        function closeApiKeyModal() {
            document.getElementById('modalApiKey').classList.add('hidden');
            document.getElementById('modalApiKey').classList.remove('flex');
        }

        function salvarChaveGemini() {
            const key = document.getElementById('modalApiKeyInput').value.trim();
            const model = document.getElementById('modalModelSelect').value;
            const btn = document.getElementById('btnSalvarChave');
            const msg = document.getElementById('modalStatusMsg');

            if (!key) {
                msg.className = 'text-xs p-3 rounded-xl font-medium bg-rose-50 text-rose-700 border border-rose-200 block';
                msg.textContent = 'Por favor, informe uma chave de API válida do Google AI Studio.';
                return;
            }

            btn.disabled = true;
            btn.textContent = 'Testando Chave...';
            msg.className = 'text-xs p-3 rounded-xl font-medium bg-slate-100 text-slate-700 block';
            msg.textContent = 'Verificando conexão com o Google Gemini...';

            const fd = new FormData();
            fd.append('api_key', key);
            fd.append('model', model);

            fetch('api/save_gemini_key.php', {
                method: 'POST',
                body: fd
            })
            .then(r => r.json())
            .then(res => {
                btn.disabled = false;
                btn.textContent = 'Testar & Salvar Chave';
                if (res.success) {
                    msg.className = 'text-xs p-3 rounded-xl font-medium bg-emerald-50 text-emerald-800 border border-emerald-200 block';
                    msg.textContent = res.message;
                    setTimeout(() => {
                        closeApiKeyModal();
                        window.location.reload();
                    }, 1200);
                } else {
                    msg.className = 'text-xs p-3 rounded-xl font-medium bg-rose-50 text-rose-700 border border-rose-200 block';
                    msg.textContent = res.message;
                }
            })
            .catch(e => {
                btn.disabled = false;
                btn.textContent = 'Testar & Salvar Chave';
                msg.className = 'text-xs p-3 rounded-xl font-medium bg-rose-50 text-rose-700 border border-rose-200 block';
                msg.textContent = 'Falha na comunicação com o servidor local.';
            });
        }

        // Inicialização
        document.addEventListener('DOMContentLoaded', () => {
            aoMudarTema();
            atualizarContadoresRedacao();
            aplicarFiltrosTemas();
        });
    </script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
