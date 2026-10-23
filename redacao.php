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
$temasAgrupadosRaw = [];
foreach ($temas as $t) {
    $b = strtoupper($t['banca'] ?? 'ENEM');
    $temasPorBanca[$b] = ($temasPorBanca[$b] ?? 0) + 1;
    $temasAgrupadosRaw[$b][] = $t;
}

// Ordem prioritária de bancas: Provas Reais primeiro, depois Redigir e Possíveis Temas
$ordemBancas = ['ENEM', 'UNESP', 'FUVEST', 'UNICAMP', 'UERJ', 'REDIGIR', 'POSSIVEIS'];
$temasAgrupados = [];
foreach ($ordemBancas as $bKey) {
    if (!empty($temasAgrupadosRaw[$bKey])) {
        $temasAgrupados[$bKey] = $temasAgrupadosRaw[$bKey];
    }
}
foreach ($temasAgrupadosRaw as $bKey => $items) {
    if (!isset($temasAgrupados[$bKey])) {
        $temasAgrupados[$bKey] = $items;
    }
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
        /* Folha Pautada Oficial e Gutter de Linhas (Letrus Style) */
        .folha-pautada-box {
            border-radius: 20px;
            border: 2px solid #e2e8f0;
            background: #ffffff;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
            position: relative;
        }
        .folha-pautada-box:focus-within {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15), 0 10px 25px -5px rgba(0, 0, 0, 0.08);
        }
        .redacao-gutter {
            width: 46px;
            background: #f8fafc;
            border-right: 2px solid #e2e8f0;
            padding: 16px 0;
            font-family: 'JetBrains Mono', ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: 13px;
            font-weight: 700;
            color: #94a3b8;
            text-align: center;
            overflow-y: hidden;
            user-select: none;
        }
        .redacao-gutter span {
            display: block;
            height: 32px;
            line-height: 32px;
            transition: all 0.15s ease;
        }
        .redacao-gutter span.linha-ativa {
            color: #4f46e5 !important;
            background-color: rgba(99, 102, 241, 0.12) !important;
            font-weight: 900 !important;
            border-radius: 6px;
        }
        .redacao-folha-sync {
            background-color: #ffffff;
            /* Folha oficial pautada com guia sutil de margem vermelha à esquerda */
            background-image: 
                repeating-linear-gradient(to bottom, transparent, transparent 31px, #e2e8f0 31px, #e2e8f0 32px),
                linear-gradient(to right, transparent 20px, rgba(239, 68, 68, 0.22) 20px, rgba(239, 68, 68, 0.22) 22px, transparent 22px);
            line-height: 32px;
            padding: 16px 20px 16px 36px;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: 15px;
            color: #1e293b;
            border: none;
            outline: none;
            resize: none;
            min-height: 992px; /* 30 linhas * 32px + 32px padding */
            overflow-y: auto;
            letter-spacing: 0.01em;
        }
        html.dark .folha-pautada-box {
            border-color: #334155;
            background: #18202e;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.25);
        }
        html.dark .redacao-gutter {
            background: #0f172a;
            border-right-color: #1e293b;
            color: #64748b;
        }
        html.dark .redacao-gutter span.linha-ativa {
            color: #818cf8 !important;
            background-color: rgba(99, 102, 241, 0.25) !important;
        }
        html.dark .redacao-folha-sync {
            background-color: #18202e !important;
            background-image: 
                repeating-linear-gradient(to bottom, transparent, transparent 31px, #273449 31px, #273449 32px),
                linear-gradient(to right, transparent 20px, rgba(244, 63, 94, 0.25) 20px, rgba(244, 63, 94, 0.25) 22px, transparent 22px) !important;
            color: #f8fafc !important;
        }

        /* Marcador de Texto (Letrus Highlighter) */
        .letrus-mark {
            border-radius: 4px;
            padding: 2px 4px;
            cursor: pointer;
            transition: all 0.15s ease;
            display: inline;
        }
        .letrus-mark:hover {
            filter: brightness(0.92);
            outline: 2px dashed rgba(0, 0, 0, 0.25);
        }
        .letrus-mark.mark-amarelo {
            background-color: #fef08a !important;
            color: #713f12 !important;
            border-bottom: 2px solid #facc15;
        }
        .letrus-mark.mark-verde {
            background-color: #bbf7d0 !important;
            color: #14532d !important;
            border-bottom: 2px solid #4ade80;
        }
        .letrus-mark.mark-azul {
            background-color: #bae6fd !important;
            color: #0c4a6e !important;
            border-bottom: 2px solid #38bdf8;
        }
        .letrus-mark.mark-rosa {
            background-color: #fbcfe8 !important;
            color: #831843 !important;
            border-bottom: 2px solid #f472b6;
        }

        html.dark .letrus-mark.mark-amarelo {
            background-color: rgba(234, 179, 8, 0.4) !important;
            color: #fef08a !important;
            border-bottom-color: #facc15;
        }
        html.dark .letrus-mark.mark-verde {
            background-color: rgba(34, 197, 94, 0.4) !important;
            color: #bbf7d0 !important;
            border-bottom-color: #4ade80;
        }
        html.dark .letrus-mark.mark-azul {
            background-color: rgba(14, 165, 233, 0.4) !important;
            color: #bae6fd !important;
            border-bottom-color: #38bdf8;
        }
        html.dark .letrus-mark.mark-rosa {
            background-color: rgba(236, 72, 153, 0.4) !important;
            color: #fbcfe8 !important;
            border-bottom-color: #f472b6;
        }

        /* Cursores Realistas de Marca-Texto para os Textos Motivadores */
        .cursor-grifo-amarelo, .cursor-grifo-amarelo *:not(button):not(a):not(.fonte-nao-grifavel):not(.letrus-mark) {
            cursor: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMiIgaGVpZ2h0PSIzMiIgdmlld0JveD0iMCAwIDMyIDMyIj48cG9seWdvbiBwb2ludHM9IjEsNCA0LDEgMTEsOCA2LDEwIiBmaWxsPSIjZmFjYzE1IiBzdHJva2U9IiMwZjE3MmEiIHN0cm9rZS13aWR0aD0iMS4yIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+PHBvbHlnb24gcG9pbnRzPSI2LDEwIDExLDggMTQsMTIgOCwxNCIgZmlsbD0iIzMzNDE1NSIgc3Ryb2tlPSIjMGYxNzJhIiBzdHJva2Utd2lkdGg9IjEuMiIvPjxwb2x5Z29uIHBvaW50cz0iOCwxNCAxNCwxMiAyNSwyMyAxOSwyNyIgZmlsbD0iI2ZlZjA4YSIgc3Ryb2tlPSIjMGYxNzJhIiBzdHJva2Utd2lkdGg9IjEuNSIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPjxsaW5lIHgxPSIxMiIgeTE9IjE2IiB4Mj0iMjEiIHkyPSIyNSIgc3Ryb2tlPSIjZmZmZmZmIiBzdHJva2Utd2lkdGg9IjEuNSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBvcGFjaXR5PSIwLjgiLz48cG9seWdvbiBwb2ludHM9IjE5LDI3IDI1LDIzIDI4LDI2IDIyLDMwIiBmaWxsPSIjMGYxNzJhIi8+PC9zdmc=") 2 2, text !important;
        }
        .cursor-grifo-verde, .cursor-grifo-verde *:not(button):not(a):not(.fonte-nao-grifavel):not(.letrus-mark) {
            cursor: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMiIgaGVpZ2h0PSIzMiIgdmlld0JveD0iMCAwIDMyIDMyIj48cG9seWdvbiBwb2ludHM9IjEsNCA0LDEgMTEsOCA2LDEwIiBmaWxsPSIjMjJjNTVlIiBzdHJva2U9IiMwZjE3MmEiIHN0cm9rZS13aWR0aD0iMS4yIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+PHBvbHlnb24gcG9pbnRzPSI2LDEwIDExLDggMTQsMTIgOCwxNCIgZmlsbD0iIzMzNDE1NSIgc3Ryb2tlPSIjMGYxNzJhIiBzdHJva2Utd2lkdGg9IjEuMiIvPjxwb2x5Z29uIHBvaW50cz0iOCwxNCAxNCwxMiAyNSwyMyAxOSwyNyIgZmlsbD0iI2JiZjdkMCIgc3Ryb2tlPSIjMGYxNzJhIiBzdHJva2Utd2lkdGg9IjEuNSIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPjxsaW5lIHgxPSIxMiIgeTE9IjE2IiB4Mj0iMjEiIHkyPSIyNSIgc3Ryb2tlPSIjZmZmZmZmIiBzdHJva2Utd2lkdGg9IjEuNSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBvcGFjaXR5PSIwLjgiLz48cG9seWdvbiBwb2ludHM9IjE5LDI3IDI1LDIzIDI4LDI2IDIyLDMwIiBmaWxsPSIjMGYxNzJhIi8+PC9zdmc=") 2 2, text !important;
        }
        .cursor-grifo-azul, .cursor-grifo-azul *:not(button):not(a):not(.fonte-nao-grifavel):not(.letrus-mark) {
            cursor: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMiIgaGVpZ2h0PSIzMiIgdmlld0JveD0iMCAwIDMyIDMyIj48cG9seWdvbiBwb2ludHM9IjEsNCA0LDEgMTEsOCA2LDEwIiBmaWxsPSIjMGVhNWU5IiBzdHJva2U9IiMwZjE3MmEiIHN0cm9rZS13aWR0aD0iMS4yIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+PHBvbHlnb24gcG9pbnRzPSI2LDEwIDExLDggMTQsMTIgOCwxNCIgZmlsbD0iIzMzNDE1NSIgc3Ryb2tlPSIjMGYxNzJhIiBzdHJva2Utd2lkdGg9IjEuMiIvPjxwb2x5Z29uIHBvaW50cz0iOCwxNCAxNCwxMiAyNSwyMyAxOSwyNyIgZmlsbD0iI2JhZTZmZCIgc3Ryb2tlPSIjMGYxNzJhIiBzdHJva2Utd2lkdGg9IjEuNSIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPjxsaW5lIHgxPSIxMiIgeTE9IjE2IiB4Mj0iMjEiIHkyPSIyNSIgc3Ryb2tlPSIjZmZmZmZmIiBzdHJva2Utd2lkdGg9IjEuNSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBvcGFjaXR5PSIwLjgiLz48cG9seWdvbiBwb2ludHM9IjE5LDI3IDI1LDIzIDI4LDI2IDIyLDMwIiBmaWxsPSIjMGYxNzJhIi8+PC9zdmc=") 2 2, text !important;
        }
        .cursor-grifo-rosa, .cursor-grifo-rosa *:not(button):not(a):not(.fonte-nao-grifavel):not(.letrus-mark) {
            cursor: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMiIgaGVpZ2h0PSIzMiIgdmlld0JveD0iMCAwIDMyIDMyIj48cG9seWdvbiBwb2ludHM9IjEsNCA0LDEgMTEsOCA2LDEwIiBmaWxsPSIjZWM0ODk5IiBzdHJva2U9IiMwZjE3MmEiIHN0cm9rZS13aWR0aD0iMS4yIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+PHBvbHlnb24gcG9pbnRzPSI2LDEwIDExLDggMTQsMTIgOCwxNCIgZmlsbD0iIzMzNDE1NSIgc3Ryb2tlPSIjMGYxNzJhIiBzdHJva2Utd2lkdGg9IjEuMiIvPjxwb2x5Z29uIHBvaW50cz0iOCwxNCAxNCwxMiAyNSwyMyAxOSwyNyIgZmlsbD0iI2ZiY2ZlOCIgc3Ryb2tlPSIjMGYxNzJhIiBzdHJva2Utd2lkdGg9IjEuNSIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPjxsaW5lIHgxPSIxMiIgeTE9IjE2IiB4Mj0iMjEiIHkyPSIyNSIgc3Ryb2tlPSIjZmZmZmZmIiBzdHJva2Utd2lkdGg9IjEuNSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBvcGFjaXR5PSIwLjgiLz48cG9seWdvbiBwb2ludHM9IjE5LDI3IDI1LDIzIDI4LDI2IDIyLDMwIiBmaWxsPSIjMGYxNzJhIi8+PC9zdmc=") 2 2, text !important;
        }

        /* Proteção Absoluta para Fontes e Créditos: NUNCA GRIFÁVEIS */
        .fonte-nao-grifavel, .fonte-nao-grifavel * {
            user-select: none !important;
            -webkit-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
            cursor: not-allowed !important;
        }

        /* Dropzone para Envio de Foto/PDF */
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

        /* Modo Foco Zen / Fullscreen */
        #tabContentEscrever.modo-foco-zen {
            position: fixed !important;
            inset: 0 !important;
            z-index: 9999 !important;
            background-color: #f8fafc !important;
            overflow-y: auto !important;
            padding: 1.5rem 2rem !important;
            margin: 0 !important;
            max-width: 100vw !important;
            width: 100vw !important;
            height: 100vh !important;
        }
        html.dark #tabContentEscrever.modo-foco-zen {
            background-color: #0b1120 !important;
        }
    </style>

    <!-- LAYOUT PRINCIPAL -->
    <main id="mainContainer" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-all duration-300">
        <div id="mainGrid" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start transition-all duration-300">

            <!-- SIDEBAR MODULAR -->
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

            <!-- CONTEÚDO PRINCIPAL DO PORTAL DA REDAÇÃO -->
            <section id="portalRedacaoSection" class="lg:col-span-9 space-y-6 transition-all duration-300">

                <!-- HERO BANNER EXCLUSIVO: PORTAL DA REDAÇÃO -->
                <div id="heroBannerRedacao" class="bg-gradient-to-r from-slate-900 via-indigo-950 to-purple-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden border-2 border-indigo-500/30 transition-all duration-300">
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
                <div id="portalTabsBar" class="flex items-center gap-2 p-1.5 bg-slate-100 dark:bg-slate-800 rounded-2xl border-2 border-slate-200 dark:border-slate-700 overflow-x-auto transition-all duration-300">
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
                                <button type="button" onclick="filtrarBanca('ENEM')" class="banca-pill px-3.5 py-1.5 rounded-xl text-xs font-outfit font-black transition-all bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-200" title="Apenas propostas reais que caíram nas provas oficiais do ENEM">
                                    🟡 ENEM (<?= $temasPorBanca['ENEM'] ?? 0 ?> Reais)
                                </button>
                                <button type="button" onclick="filtrarBanca('UNESP')" class="banca-pill px-3.5 py-1.5 rounded-xl text-xs font-outfit font-black transition-all bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-200" title="Provas oficiais reais da UNESP">
                                    🔵 UNESP (<?= $temasPorBanca['UNESP'] ?? 0 ?> Reais)
                                </button>
                                <button type="button" onclick="filtrarBanca('FUVEST')" class="banca-pill px-3.5 py-1.5 rounded-xl text-xs font-outfit font-black transition-all bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-200" title="Provas oficiais reais da FUVEST">
                                    🔴 FUVEST (<?= $temasPorBanca['FUVEST'] ?? 0 ?> Reais)
                                </button>
                                <button type="button" onclick="filtrarBanca('UNICAMP')" class="banca-pill px-3.5 py-1.5 rounded-xl text-xs font-outfit font-black transition-all bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-200" title="Provas oficiais reais da UNICAMP">
                                    🟠 UNICAMP (<?= $temasPorBanca['UNICAMP'] ?? 0 ?> Reais)
                                </button>
                                <button type="button" onclick="filtrarBanca('UERJ')" class="banca-pill px-3.5 py-1.5 rounded-xl text-xs font-outfit font-black transition-all bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-200" title="Provas oficiais reais da UERJ">
                                    🟣 UERJ (<?= $temasPorBanca['UERJ'] ?? 0 ?> Reais)
                                </button>
                                <button type="button" onclick="filtrarBanca('REDIGIR')" class="banca-pill px-3.5 py-1.5 rounded-xl text-xs font-outfit font-black transition-all bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-200">
                                    📚 Plataforma Redigir (<?= $temasPorBanca['REDIGIR'] ?? 0 ?>)
                                </button>
                                <button type="button" onclick="filtrarBanca('POSSIVEIS')" class="banca-pill px-3.5 py-1.5 rounded-xl text-xs font-outfit font-black transition-all bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-200" title="Temas inéditos de simulação e possíveis temas para futuros exames">
                                    💡 Possíveis Temas (<?= $temasPorBanca['POSSIVEIS'] ?? 0 ?>)
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
                                <input type="text" id="inputBuscaTema" autocomplete="off" onkeyup="aplicarFiltrosTemas()" onkeydown="if(event.key==='Enter'){event.preventDefault(); abrirPrimeiroTemaFiltrado();}" placeholder="Buscar por tema, filósofo (Bauman, Foucault...), palavra-chave ou ano..." class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs font-medium text-slate-900 dark:text-white focus:outline-none pr-8">
                                <button type="button" onclick="limparBuscaTema()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xs transition" title="Limpar busca">
                                    <i class="bi bi-search" id="iconBuscaStatus"></i>
                                </button>
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
                                'POSSIVEIS' => 'bg-emerald-100 text-emerald-900 border-emerald-300 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-700',
                                'REDIGIR' => 'bg-red-100 text-red-900 border-red-300 dark:bg-red-950/40 dark:text-red-300 dark:border-red-700'
                            ][$bancaUpper] ?? 'bg-indigo-100 text-indigo-900 border-indigo-300';
                            ?>
                            <div class="tema-card bg-white dark:bg-slate-800 rounded-2xl border-2 border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md hover:border-indigo-400 dark:hover:border-indigo-500 transition-all flex flex-col justify-between gap-4 cursor-pointer group" onclick="iniciarEscritaComTema(<?= $t['id'] ?>, 'escrita')" data-id="<?= $t['id'] ?>" data-banca="<?= $bancaUpper ?>" data-titulo="<?= htmlspecialchars($t['titulo']) ?>" data-eixo="<?= htmlspecialchars($t['eixo_tematico'] ?? '') ?>" title="Clique para abrir e começar a escrever esta redação">
                                <div class="space-y-2.5">
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="px-2.5 py-0.5 rounded-lg border text-[11px] font-outfit font-black uppercase <?= $corBanca ?>">
                                                <?php if ($bancaUpper === 'POSSIVEIS'): ?>
                                                    💡 Possível Tema <?= htmlspecialchars($t['ano'] ?? '') ?>
                                                <?php elseif ($bancaUpper === 'REDIGIR'): ?>
                                                    📚 Redigir <?= htmlspecialchars($t['ano'] ?? '') ?>
                                                <?php else: ?>
                                                    <?php 
                                                    $origemUpper = strtoupper($t['origem'] ?? '');
                                                    if (strpos($origemUpper, 'PPL') !== false): ?>
                                                        🔒 <?= $bancaUpper ?> <?= htmlspecialchars($t['ano'] ?? '') ?> • PPL / Reaplicação
                                                    <?php elseif (strpos($origemUpper, '2ª') !== false || strpos($origemUpper, '2A') !== false): ?>
                                                        📝 <?= $bancaUpper ?> <?= htmlspecialchars($t['ano'] ?? '') ?> • 2ª Aplicação
                                                    <?php else: ?>
                                                        🏛️ <?= $bancaUpper ?> <?= htmlspecialchars($t['ano'] ?? '') ?> • Regular (1ª)
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </span>
                                            <span class="px-2 py-0.5 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-[10px] font-bold">
                                                <?= htmlspecialchars($t['eixo_tematico'] ?? 'Geral') ?>
                                            </span>
                                        </div>
                                        <span class="text-[11px] text-slate-400 font-bold shrink-0">
                                            <?= htmlspecialchars($t['genero_textual'] ?? 'Dissertativo') ?>
                                        </span>
                                    </div>

                                    <h3 class="font-outfit font-extrabold text-base text-slate-900 dark:text-white leading-snug group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                        <?= htmlspecialchars($t['titulo']) ?>
                                    </h3>

                                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-2">
                                        <?= htmlspecialchars($t['descricao']) ?>
                                    </p>
                                </div>

                                <div class="pt-3 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between gap-2" onclick="event.stopPropagation()">
                                    <div class="flex items-center gap-1.5">
                                        <a href="visualizar_pdf.php?id=<?= $t['id'] ?>&download=1" target="_blank" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-outfit font-bold text-xs transition-all flex items-center gap-1.5 shadow-sm" title="Baixar arquivo da proposta">
                                            <i class="bi bi-file-earmark-pdf-fill text-rose-500"></i>
                                            <span class="hidden sm:inline">PDF</span>
                                        </a>
                                        <button type="button" onclick="iniciarEscritaComTema(<?= $t['id'] ?>, 'leitura')" class="px-3 py-2 rounded-xl bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/60 dark:hover:bg-indigo-900/80 text-indigo-700 dark:text-indigo-300 font-outfit font-bold text-xs transition-all flex items-center gap-1.5" title="Ver coletânea em tela de leitura">
                                            <i class="bi bi-book-half"></i>
                                            <span>Textos</span>
                                        </button>
                                    </div>

                                    <button type="button" onclick="iniciarEscritaComTema(<?= $t['id'] ?>, 'escrita')" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-outfit font-black text-xs shadow-sm transition-all flex items-center gap-1.5" title="Abrir folha oficial e começar a redação">
                                        <span>Iniciar Redação</span>
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- ========================================================================= -->
                <!-- ABA 2: BANCADA DE ESCRITA & FLUXO DE REDAÇÃO (ESTILO LETRUS)               -->
                <!-- ========================================================================= -->
                <div id="tabContentEscrever" class="hidden space-y-6 transition-all duration-300">
                    <input type="hidden" name="csrf_token" id="letrusCsrfToken" value="<?= htmlspecialchars(getCsrfToken()) ?>">
                    <input type="hidden" id="inputBancaAtiva" name="banca" value="ENEM">

                    <!-- BARRA PRINCIPAL DO WORKSPACE (LIMPA, ELEGANTE E INTEGRADA) -->
                    <div class="bg-white dark:bg-slate-800 rounded-3xl border-2 border-slate-200 dark:border-slate-700 p-4 sm:p-5 shadow-sm space-y-4">
                        <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3 sm:gap-4">
                            <!-- LADO ESQUERDO: VOLTAR AOS TEMAS + SELETOR DE PROPOSTA -->
                            <div class="flex items-center gap-3 flex-1 min-w-0">
                                <button type="button" onclick="sairDoModoSimulado()" class="px-3.5 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-outfit font-bold text-xs transition flex items-center gap-1.5 shrink-0 border border-slate-200 dark:border-slate-600 shadow-xs" title="Voltar ao catálogo de propostas">
                                    <i class="bi bi-arrow-left text-sm"></i>
                                    <span class="hidden sm:inline">Temas</span>
                                </button>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2 mb-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-[10px] font-outfit font-black uppercase tracking-wider text-indigo-600 dark:text-indigo-400 flex items-center gap-1">
                                                <i class="bi bi-pencil-fill"></i> Tema Selecionado para Produção
                                            </span>
                                            <span id="badgeBancaHeader" class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                                                ENEM
                                            </span>
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <select id="temaSelect" name="tema_id" onchange="aoMudarTema()" class="w-full bg-slate-50 dark:bg-slate-900 border-2 border-indigo-300 dark:border-indigo-700 rounded-xl px-3.5 py-2 text-slate-900 dark:text-slate-100 font-bold text-xs sm:text-sm focus:border-indigo-500 focus:outline-none transition-all pr-8 cursor-pointer truncate shadow-xs" title="Tema ativo para escrita (clique se desejar alternar)">
                                            <?php foreach ($temasAgrupados as $bancaNome => $listaTemas): ?>
                                                <?php
                                                $optLabel = match($bancaNome) {
                                                    'ENEM' => '🟡 ENEM (Provas Reais 1998–2024)',
                                                    'UNESP' => '🔵 UNESP (Provas Reais)',
                                                    'FUVEST' => '🔴 FUVEST (Provas Reais)',
                                                    'UNICAMP' => '🟠 UNICAMP (Provas Reais)',
                                                    'UERJ' => '🟣 UERJ (Provas Reais)',
                                                    'REDIGIR' => '📚 Plataforma Redigir',
                                                    'POSSIVEIS' => '💡 Possíveis Temas / Inéditos (Padrão ENEM)',
                                                    default => "🏛️ $bancaNome"
                                                };
                                                ?>
                                                <optgroup label="<?= $optLabel ?> (<?= count($listaTemas) ?>)">
                                                    <?php foreach ($listaTemas as $t): ?>
                                                        <option value="<?= $t['id'] ?>">
                                                            [<?= htmlspecialchars($t['origem'] ?: ($t['banca'] . ' ' . $t['ano'])) ?>] <?= htmlspecialchars($t['titulo']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </optgroup>
                                            <?php endforeach; ?>
                                            <option value="custom">✍️ Digitar Outro Tema Personalizado...</option>
                                        </select>
                                        <i class="bi bi-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                                    </div>

                                    <div id="divTemaCustom" class="mt-2 hidden">
                                        <input type="text" id="temaCustom" name="tema_custom" placeholder="Digite o título ou pergunta do tema personalizado..." class="w-full bg-slate-50 dark:bg-slate-900 border-2 border-indigo-400 rounded-xl px-3 py-1.5 text-slate-900 dark:text-slate-100 font-medium text-xs focus:outline-none">
                                    </div>
                                </div>
                            </div>

                            <!-- LADO DIREITO: CRONÔMETRO + MODO FOCO + ENTREGAR -->
                            <div class="flex items-center gap-2 sm:gap-2.5 shrink-0 justify-end">
                                <!-- CRONÔMETRO DE PROVA -->
                                <div class="bg-slate-50 dark:bg-slate-900 px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center gap-2 shadow-xs">
                                    <div class="text-right">
                                        <span class="block text-[8px] font-black uppercase text-slate-400 leading-none mb-0.5">Tempo</span>
                                        <span id="displayTimer" class="font-outfit font-black text-sm text-indigo-600 dark:text-indigo-400 leading-none">00:00:00</span>
                                    </div>
                                    <button type="button" id="btnTimerToggle" onclick="toggleTimer()" class="w-7 h-7 rounded-lg bg-indigo-600 text-white flex items-center justify-center hover:bg-indigo-700 transition shadow-xs" title="Iniciar/Pausar Cronômetro">
                                        <i class="bi bi-play-fill text-sm" id="iconTimer"></i>
                                    </button>
                                </div>

                                <!-- MODO FOCO -->
                                <button type="button" onclick="toggleModoFocoTotal()" id="btnModoFoco" class="px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-indigo-400 bg-slate-50 dark:bg-slate-900 text-slate-700 dark:text-slate-200 font-outfit font-bold text-xs transition flex items-center gap-1.5 shadow-xs" title="Modo Foco sem distrações">
                                    <i class="bi bi-arrows-fullscreen text-indigo-500" id="iconModoFoco"></i>
                                    <span id="txtModoFoco" class="hidden md:inline">Foco</span>
                                </button>

                                <!-- BOTÃO ENTREGAR REDAÇÃO -->
                                <button type="button" onclick="dispararEnvioRedacao()" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-outfit font-black text-xs sm:text-sm shadow-sm transition-all flex items-center gap-1.5">
                                    <span>Entregar</span>
                                    <i class="bi bi-send-fill text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <!-- LINHA INFERIOR: NAVEGAÇÃO ENTRE ETAPAS (STEPPER LETRUS) + STATUS -->
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-3 border-t border-slate-100 dark:border-slate-700/60">
                            <div class="inline-flex p-1 bg-slate-100 dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-700 w-full sm:w-auto">
                                <button type="button" id="btnStepLeitura" onclick="irParaEtapa('leitura')" class="flex-1 sm:flex-initial px-4 py-2 rounded-lg text-xs font-outfit font-extrabold transition-all flex items-center justify-center gap-2 bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm">
                                    <span class="w-4 h-4 rounded-full bg-indigo-600 text-white flex items-center justify-center text-[9px] font-black">1</span>
                                    <span>1. Proposta & Textos</span>
                                </button>
                                <button type="button" id="btnStepEscrita" onclick="irParaEtapa('escrita')" class="flex-1 sm:flex-initial px-4 py-2 rounded-lg text-xs font-outfit font-extrabold transition-all flex items-center justify-center gap-2 text-slate-500 hover:text-slate-800 dark:hover:text-slate-200">
                                    <span class="w-4 h-4 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center text-[9px] font-black">2</span>
                                    <span>2. Folha de Redação</span>
                                </button>
                            </div>

                            <div class="flex items-center gap-2.5 w-full sm:w-auto justify-between sm:justify-end">
                                <span id="badgeGrifosGeral" class="px-2.5 py-1 rounded-lg bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900 text-amber-800 dark:text-amber-300 font-outfit font-bold text-xs flex items-center gap-1.5">
                                    <i class="bi bi-highlighter text-amber-500"></i>
                                    <span id="txtCountGrifosGeral">0 trechos destacados</span>
                                </span>
                                <span id="badgeDraftGeral" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-900 text-slate-500 text-xs font-medium flex items-center gap-1.5">
                                    <i class="bi bi-cloud-check-fill text-indigo-500"></i>
                                    <span id="txtDraftStatus">Rascunho salvo</span>
                                </span>
                            </div>
                        </div>

                        <!-- Elementos de compatibilidade invisíveis para JS legado -->
                        <div class="hidden">
                            <span id="tituloSimuladoTopo"></span>
                            <span id="badgeSimuladoBanca"></span>
                            <span id="txtSimuladoDraft"></span>
                            <span id="displayTimerSimulado"></span>
                            <span id="iconTimerSimulado"></span>
                        </div>
                    </div>

                    <!-- ========================================================================= -->
                    <!-- ETAPA 1: TELA EXCLUSIVA COM OS TEXTOS MOTIVADORES E AVANÇO                -->
                    <!-- ========================================================================= -->
                    <div id="etapaLeitura" class="space-y-4">
                        <!-- BARRA SUPERIOR LIMPA: TÍTULO DA PROPOSTA + PALETA MARCA-TEXTO + BOTÃO AVANÇAR -->
                        <div class="bg-white dark:bg-slate-800 rounded-2xl border-2 border-slate-200 dark:border-slate-700 p-4 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800" id="badgeEtapa1Banca">
                                        ENEM
                                    </span>
                                    <span class="text-xs text-slate-400 font-bold" id="badgeEtapa1Eixo">
                                        Geral
                                    </span>
                                    <span id="badgeContadorGrifosEtapa1" class="px-2 py-0.5 rounded-full bg-amber-50 dark:bg-amber-950/40 text-[10px] font-bold text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                        0 grifos
                                    </span>
                                </div>
                                <h2 id="tituloTemaEtapa1" class="font-outfit font-black text-base sm:text-lg text-slate-900 dark:text-white truncate" title="Tema">
                                    Carregando tema...
                                </h2>
                                <p id="descTemaEtapa1" class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 hidden"></p>
                                <div id="boxOrientacoesEtapa1" class="hidden"></div>
                            </div>

                            <div class="flex items-center gap-2 sm:gap-3 flex-wrap shrink-0">
                                <!-- Paleta de Marca-Texto Interativa -->
                                <div class="flex items-center gap-1.5 px-2.5 py-1.5 bg-slate-100 dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-700">
                                    <span class="text-[11px] font-outfit font-bold text-slate-400 hidden sm:inline-flex items-center gap-1 me-1">
                                        <i class="bi bi-pen-fill text-indigo-500"></i> Marca-texto:
                                    </span>
                                    <button type="button" data-cor="amarelo" onclick="setCorGrifoAtiva('amarelo')" class="btn-paleta-cor w-5 h-5 rounded-full bg-yellow-400 hover:scale-110 active:scale-95 transition shadow-sm border border-yellow-500/40 flex items-center justify-center" title="Marca-texto Amarelo"></button>
                                    <button type="button" data-cor="verde" onclick="setCorGrifoAtiva('verde')" class="btn-paleta-cor w-5 h-5 rounded-full bg-emerald-400 hover:scale-110 active:scale-95 transition shadow-sm border border-emerald-500/40 flex items-center justify-center" title="Marca-texto Verde"></button>
                                    <button type="button" data-cor="azul" onclick="setCorGrifoAtiva('azul')" class="btn-paleta-cor w-5 h-5 rounded-full bg-sky-400 hover:scale-110 active:scale-95 transition shadow-sm border border-sky-500/40 flex items-center justify-center" title="Marca-texto Azul"></button>
                                    <button type="button" data-cor="rosa" onclick="setCorGrifoAtiva('rosa')" class="btn-paleta-cor w-5 h-5 rounded-full bg-pink-400 hover:scale-110 active:scale-95 transition shadow-sm border border-pink-500/40 flex items-center justify-center" title="Marca-texto Rosa"></button>
                                    <span class="w-[1px] h-3.5 bg-slate-300 dark:bg-slate-700 mx-0.5"></span>
                                    <button type="button" onclick="limparTodosGrifos()" class="text-slate-400 hover:text-rose-500 text-xs transition p-0.5" title="Limpar todos os grifos deste tema">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>

                                <!-- Alternador: Texto vs PDF -->
                                <div class="inline-flex p-1 bg-slate-100 dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-700">
                                    <button type="button" id="btnModoTextoEtapa1" onclick="setModoVisualizacaoEtapa1('texto')" class="px-2.5 py-1 rounded-lg text-xs font-outfit font-black transition bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm flex items-center gap-1">
                                        <i class="bi bi-fonts"></i>
                                        <span>Texto</span>
                                    </button>
                                    <button type="button" id="btnModoPdfEtapa1" onclick="setModoVisualizacaoEtapa1('pdf')" class="px-2.5 py-1 rounded-lg text-xs font-outfit font-bold transition text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-300 flex items-center gap-1">
                                        <i class="bi bi-file-earmark-pdf-fill text-rose-500"></i>
                                        <span>PDF</span>
                                    </button>
                                </div>

                                <!-- Botão de Avanço -->
                                <button type="button" onclick="irParaEtapa('escrita')" class="px-4 sm:px-5 py-2 rounded-xl bg-amber-400 hover:bg-amber-300 active:scale-95 text-slate-950 font-outfit font-black text-xs sm:text-sm shadow-sm transition-all flex items-center gap-2">
                                    <span>Avançar para a Redação</span>
                                    <i class="bi bi-arrow-right font-black"></i>
                                </button>
                            </div>
                        </div>

                        <!-- CONTAINER DOS TEXTOS MOTIVADORES -->
                        <div class="bg-white dark:bg-slate-800 rounded-3xl border-2 border-slate-200 dark:border-slate-700 p-6 sm:p-8 shadow-sm space-y-6">
                            <!-- VISUALIZAÇÃO EM TEXTO TRANSCRITO -->
                            <div id="viewTextoEtapa1" class="space-y-4">
                                <div id="letrusTextosEtapa1" class="textos-motivadores-render space-y-4 leading-relaxed text-slate-800 dark:text-slate-200 select-text">
                                    <!-- Conteúdo injetado via JS -->
                                </div>
                            </div>

                            <!-- VISUALIZAÇÃO EM PDF OFICIAL -->
                            <div id="viewPdfEtapa1" class="hidden w-full h-[700px] rounded-2xl overflow-hidden border-2 border-slate-200 dark:border-slate-700 shadow-inner bg-slate-100 dark:bg-slate-900 relative">
                                <iframe id="iframePdfEtapa1" src="" class="w-full h-full border-0 bg-transparent"></iframe>
                            </div>
                        </div>

                        <!-- BOTÃO DE AVANÇAR PARA A REDAÇÃO NO FINAL DOS TEXTOS -->
                        <div class="flex items-center justify-center pt-2 pb-6">
                            <button type="button" onclick="irParaEtapa('escrita')" class="w-full sm:w-auto px-10 py-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-outfit font-black text-base shadow-lg shadow-indigo-500/25 transition-all flex items-center justify-center gap-2.5">
                                <span>Avançar para a Redação</span>
                                <i class="bi bi-arrow-right text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <!-- ========================================================================= -->
                    <!-- ETAPA 2: BANCADA DE PRODUÇÃO TEXTUAL COM SPLIT-SCREEN (LETRUS WORKSPACE) -->
                    <!-- ========================================================================= -->
                    <div id="etapaEscrita" class="hidden space-y-6">
                        <!-- BARRA DE CONTROLE DA BANCADA: AÇÕES E LAYOUT -->
                        <div class="bg-white dark:bg-slate-800 rounded-2xl border-2 border-slate-200 dark:border-slate-700 p-3.5 sm:p-4 shadow-sm flex flex-wrap items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <!-- BOTÃO ÚNICO DE ALTERNÂNCIA DE LARGURA (EXPANDIR FOLHA / DIVIDIR TELA) -->
                                <button type="button" id="btnToggleSideTextos" onclick="toggleTextosAoLado()" class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-outfit font-bold text-xs flex items-center gap-2 border border-slate-200 dark:border-slate-600 transition-all" title="Alternar entre tela dividida e folha inteira">
                                    <i class="bi bi-arrows-angle-expand text-xs" id="iconSideTextos"></i>
                                    <span id="txtSideTextos">Expandir Folha</span>
                                </button>
                            </div>

                            <div class="flex items-center gap-2">
                                <button type="button" onclick="limparRascunho()" class="px-3 py-1.5 rounded-xl text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 text-xs font-bold transition flex items-center gap-1" title="Limpar folha e começar rascunho do zero">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                    <span class="hidden sm:inline">Limpar Folha</span>
                                </button>
                                <button type="button" onclick="salvarRascunhoManual()" class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-xs font-outfit font-bold transition flex items-center gap-1.5 shadow-sm" title="Salvar Rascunho">
                                    <i class="bi bi-save-fill text-indigo-500"></i>
                                    <span>Salvar</span>
                                </button>
                            </div>
                        </div>

                        <!-- GRID PRINCIPAL SPLIT-SCREEN (TEXTOS DE UM LADO E REDAÇÃO DO OUTRO) -->
                        <div id="gridSplitBancada" class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                            
                            <!-- ================================================================= -->
                            <!-- COLUNA DA ESQUERDA: COLETÂNEA DE TEXTOS MOTIVADORES COM GRIFOS   -->
                            <!-- ================================================================= -->
                            <div id="colunaTextos" class="coluna-split lg:col-span-6 xl:col-span-6 space-y-4 transition-all">
                                <div class="bg-indigo-50/50 dark:bg-indigo-950/20 border-2 border-indigo-200 dark:border-indigo-900/50 rounded-3xl p-5 shadow-sm space-y-4">
                                    <!-- TOPO DO PAINEL DE TEXTOS -->
                                    <div class="flex items-center justify-between pb-3 border-b border-indigo-100 dark:border-indigo-900/50">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <span class="w-7 h-7 rounded-lg bg-indigo-600 text-white flex items-center justify-center text-xs shrink-0">
                                                <i class="bi bi-book-half"></i>
                                            </span>
                                            <div class="min-w-0">
                                                <h3 class="font-outfit font-black text-sm text-slate-900 dark:text-white truncate" id="splitTemaTituloMini">
                                                    Textos Motivadores
                                                </h3>
                                                <span class="text-[10px] text-slate-500 font-bold block" id="splitCountGrifosMini">
                                                    0 grifos ativos
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-1.5 shrink-0">
                                            <!-- Mini-paleta de marca-texto -->
                                            <div class="flex items-center gap-1 p-1 bg-white dark:bg-slate-800 rounded-xl border border-indigo-100 dark:border-indigo-900 shadow-sm me-1">
                                                <button type="button" data-cor="amarelo" onclick="setCorGrifoAtiva('amarelo')" class="btn-paleta-cor w-5 h-5 rounded-full bg-yellow-400 hover:scale-110 active:scale-95 transition shadow-sm border border-yellow-500/40 flex items-center justify-center" title="Marca-texto Amarelo"></button>
                                                <button type="button" data-cor="verde" onclick="setCorGrifoAtiva('verde')" class="btn-paleta-cor w-5 h-5 rounded-full bg-emerald-400 hover:scale-110 active:scale-95 transition shadow-sm border border-emerald-500/40 flex items-center justify-center" title="Marca-texto Verde"></button>
                                                <button type="button" data-cor="azul" onclick="setCorGrifoAtiva('azul')" class="btn-paleta-cor w-5 h-5 rounded-full bg-sky-400 hover:scale-110 active:scale-95 transition shadow-sm border border-sky-500/40 flex items-center justify-center" title="Marca-texto Azul"></button>
                                                <button type="button" data-cor="rosa" onclick="setCorGrifoAtiva('rosa')" class="btn-paleta-cor w-5 h-5 rounded-full bg-pink-400 hover:scale-110 active:scale-95 transition shadow-sm border border-pink-500/40 flex items-center justify-center" title="Marca-texto Rosa"></button>
                                                <span class="w-[1px] h-3.5 bg-slate-200 dark:bg-slate-700 mx-0.5"></span>
                                                <button type="button" onclick="limparTodosGrifos()" class="text-slate-400 hover:text-rose-500 text-[11px] transition px-0.5" title="Limpar todos os grifos">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </div>

                                            <!-- Alternar Texto vs PDF no split -->
                                            <div class="inline-flex p-0.5 bg-white dark:bg-slate-800 rounded-xl border border-indigo-200 dark:border-indigo-900">
                                                <button type="button" id="btnModoTextoSplit" onclick="setModoVisualizacaoSplit('texto')" class="px-2 py-1 rounded-lg text-[11px] font-outfit font-black transition bg-indigo-600 text-white shadow-sm" title="Modo Texto">
                                                    Texto
                                                </button>
                                                <button type="button" id="btnModoPdfSplit" onclick="setModoVisualizacaoSplit('pdf')" class="px-2 py-1 rounded-lg text-[11px] font-outfit font-bold transition text-slate-600 dark:text-slate-300 hover:text-indigo-600" title="Modo PDF">
                                                    PDF
                                                </button>
                                            </div>

                                            <!-- Botão Ocultar Textos com setinha -->
                                            <button type="button" onclick="setVisualizacaoBancada('redacao-only')" class="px-2.5 py-1 rounded-xl text-slate-500 hover:text-indigo-600 hover:bg-white dark:hover:bg-slate-800 text-xs font-bold transition flex items-center gap-1 border border-indigo-100 dark:border-indigo-900" title="Ocultar Textos (focar na folha de redação)">
                                                <i class="bi bi-chevron-left text-sm"></i>
                                                <span class="hidden sm:inline">Ocultar</span>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- CORPO COM SCROLL INDEPENDENTE DOS TEXTOS MOTIVADORES -->
                                    <div class="max-h-[820px] overflow-y-auto pr-1 space-y-4">
                                        <!-- Modo Texto Transcrito (com Grifos) -->
                                        <div id="viewTextoSplit" class="space-y-4">
                                            <div id="boxOrientacoesSplit" class="p-3 rounded-2xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900/40 text-xs text-amber-900 dark:text-amber-200 font-medium"></div>
                                            <div id="descTemaSplit" class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed italic"></div>
                                            <div id="letrusTextosSplit" class="textos-motivadores-render space-y-4 text-xs text-slate-800 dark:text-slate-200 leading-relaxed bg-white dark:bg-slate-900 p-4 sm:p-5 rounded-2xl border border-indigo-100 dark:border-slate-700 shadow-sm select-text">
                                                <!-- Textos sincronizados -->
                                            </div>
                                        </div>

                                        <!-- Modo PDF Oficial no Split -->
                                        <div id="viewPdfSplit" class="hidden w-full h-[780px] rounded-2xl overflow-hidden border-2 border-indigo-200 dark:border-indigo-900/60 shadow-inner bg-slate-100 dark:bg-slate-900 relative">
                                            <iframe id="iframePdfSplit" src="" class="w-full h-full border-0 bg-transparent"></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ================================================================= -->
                            <!-- COLUNA DA DIREITA: FOLHA OFICIAL DE REDAÇÃO PAUTADA E ENVIOS      -->
                            <!-- ================================================================= -->
                            <div id="colunaRedacao" class="coluna-split lg:col-span-6 xl:col-span-6 space-y-4 transition-all">
                                <form id="formRedacao" onsubmit="enviarRedacao(event)" class="bg-white dark:bg-slate-800 rounded-3xl border-2 border-slate-200 dark:border-slate-700 p-6 sm:p-8 shadow-sm space-y-5">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken()) ?>">
                                    <input type="hidden" id="formInputBanca" name="banca" value="ENEM">
                                    <input type="hidden" id="formInputTemaId" name="tema_id" value="">
                                    <input type="hidden" id="formInputTemaCustom" name="tema_custom" value="">


                                    <!-- TÍTULO DA REDAÇÃO (CAMPO PROPRIO) -->
                                    <div>
                                        <div class="flex items-center justify-between mb-1">
                                            <label class="block font-outfit font-extrabold text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                                Título da Redação <span class="font-normal lowercase text-slate-400" id="rotuloTituloBanca">(opcional)</span>
                                            </label>
                                        </div>
                                        <input type="text" id="tituloRedacao" name="titulo_redacao" oninput="agendarAutoSave()" placeholder="Digite o título da sua redação..." class="w-full bg-slate-50 dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-2.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:border-indigo-500 focus:outline-none transition-all">
                                    </div>

                                    <!-- MODO DE ENVIO: DIGITAR FOLHA vs FOTO/PDF -->
                                    <div class="flex items-center justify-between gap-4 pb-2 border-b border-slate-100 dark:border-slate-700">
                                        <span class="font-outfit font-extrabold text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                            Formato da Folha:
                                        </span>
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

                                    <!-- ÁREA 1: FOLHA PAUTADA COM LINHAS NUMERADAS (1 A 30) -->
                                    <div id="containerTexto" class="space-y-3">
                                        <!-- RÉGUA DE CONTADORES LIMPA -->
                                        <div class="flex flex-wrap items-center justify-between text-xs font-bold gap-2">
                                            <div class="flex items-center gap-2">
                                                <span id="badgeLinhas" class="px-3 py-1 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 font-mono font-black transition-all">
                                                    0 / 30 linhas
                                                </span>
                                                <span id="badgePalavras" class="px-2.5 py-1 rounded-xl bg-slate-50 dark:bg-slate-900 text-slate-500 dark:text-slate-400">
                                                    0 palavras
                                                </span>
                                                <span id="badgeParagrafos" class="px-2.5 py-1 rounded-xl bg-slate-50 dark:bg-slate-900 text-slate-500 dark:text-slate-400 hidden sm:inline">
                                                    0 parágrafos
                                                </span>
                                            </div>

                                            <span id="badgeAlertaLinhas" class="hidden text-xs font-bold text-rose-500"></span>
                                        </div>

                                        <!-- FOLHA OFICIAL COM GUTTER DE NÚMEROS E MARGEM -->
                                        <div class="folha-pautada-box flex overflow-hidden relative">
                                            <!-- COLUNA DE LINHAS 1 A 30 -->
                                            <div id="gutterLinhas" class="redacao-gutter select-none shrink-0" aria-hidden="true">
                                                <?php for ($i = 1; $i <= 30; $i++): ?>
                                                    <span id="gutter-line-<?= $i ?>"><?= sprintf('%02d', $i) ?></span>
                                                <?php endfor; ?>
                                            </div>

                                            <!-- TEXTAREA DA REDAÇÃO LIMPA -->
                                            <textarea id="textoRedacao" name="texto_redacao" rows="30" oninput="atualizarContadoresRedacao(); agendarAutoSave(); sincronizarLinhaAtiva();" onkeyup="sincronizarLinhaAtiva();" onclick="sincronizarLinhaAtiva();" placeholder="Comece a escrever sua redação aqui..." class="redacao-folha-sync w-full"></textarea>
                                        </div>
                                    </div>

                                    <!-- ÁREA 2: UPLOAD DE FOTO OU PDF DA FOLHA MANUSCRITA -->
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

                        </div>
                    </div>
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
                                <div class="bg-white dark:bg-slate-800 rounded-2xl border-2 border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md hover:border-indigo-400 dark:hover:border-indigo-500 transition-all flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 cursor-pointer group" onclick="window.location.href='redacao_resultado.php?id=<?= $red['id'] ?>'" title="Clique para abrir e ver o relatório detalhado desta redação">
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
                                        <h4 class="font-outfit font-extrabold text-base text-slate-900 dark:text-white truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
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

                                    <div class="flex items-center gap-3 shrink-0 w-full sm:w-auto justify-between sm:justify-end" onclick="event.stopPropagation()">
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

    <!-- MODAL: VISUALIZADOR DA PROPOSTA OFICIAL EM PDF (LOGO DE CARA) -->
    <div id="modalProposta" onclick="if(event.target === this) fecharModalProposta()" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md hidden items-center justify-center p-2 sm:p-4 transition-all" style="z-index: 9999;">
        <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-5xl w-full p-4 sm:p-6 border-2 border-slate-200 dark:border-slate-700 shadow-2xl relative max-h-[95vh] flex flex-col gap-3">
            <button type="button" onclick="fecharModalProposta()" class="absolute right-4 top-4 p-2 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-700 transition" title="Fechar (ESC)">
                <i class="bi bi-x-lg text-lg"></i>
            </button>

            <!-- CABEÇALHO DO MODAL -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100 dark:border-slate-700 pr-10">
                <div class="space-y-1">
                    <span id="modalPropostaBanca" class="px-2.5 py-0.5 rounded-md text-[11px] font-black uppercase bg-indigo-100 text-indigo-800 dark:bg-indigo-900/60 dark:text-indigo-300"></span>
                    <h3 id="modalPropostaTitulo" class="font-outfit font-black text-base sm:text-lg text-slate-900 dark:text-white leading-snug line-clamp-1"></h3>
                </div>

                <div class="flex items-center gap-2">
                    <a id="btnModalDownloadPdf" href="#" target="_blank" class="px-3.5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-outfit font-bold text-xs transition flex items-center gap-1.5 shadow-sm" title="Baixar arquivo da proposta">
                        <i class="bi bi-download"></i>
                        <span>Baixar</span>
                    </a>
                    <a id="btnModalNovaAba" href="#" target="_blank" class="p-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-xs transition flex items-center justify-center" title="Abrir em Nova Aba">
                        <i class="bi bi-box-arrow-up-right"></i>
                    </a>
                    <button type="button" id="btnModalPraticar" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-outfit font-black text-xs sm:text-sm shadow-md hover:shadow-lg transition-all flex items-center gap-2 transform active:scale-95" title="Ver textos motivadores e iniciar redação">
                        <i class="bi bi-pencil-square"></i>
                        <span>Iniciar Redação</span>
                    </button>
                </div>
            </div>

            <!-- CORPO DO MODAL: IFRAME COM ALTURA FIXA E FUNDO NEUTRO -->
            <div class="w-full h-[75vh] rounded-2xl overflow-hidden border-2 border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-900 relative">
                <iframe id="modalPdfFrame" src="" class="w-full h-full border-0 bg-transparent"></iframe>
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
        // Dados de todos os temas injetados para consulta instantânea sem delay (com escape seguro de tags HTML)
        const TODOS_TEMAS = <?= json_encode($temas, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

        // Alternância do Modo Simulado (Oculta Sidebar, Banner e Abas para Foco Total em Tela Cheia)
        function aplicarModoSimulado(ativo) {
            const sidebar = document.getElementById('mainSidebar');
            const sec = document.getElementById('portalRedacaoSection');
            const grid = document.getElementById('mainGrid');
            const container = document.getElementById('mainContainer');
            const heroBanner = document.getElementById('heroBannerRedacao');
            const tabsBar = document.getElementById('portalTabsBar');

            if (ativo) {
                // Ativar modo simulado: esconder sidebar, expandir tela ao máximo, esconder hero e tabs
                if (sidebar) sidebar.classList.add('hidden');
                if (heroBanner) heroBanner.classList.add('hidden');
                if (tabsBar) tabsBar.classList.add('hidden');

                if (sec) {
                    sec.classList.remove('lg:col-span-9');
                    sec.classList.add('lg:col-span-12', 'w-full');
                }
                if (grid) {
                    grid.classList.remove('grid', 'grid-cols-1', 'lg:grid-cols-12', 'gap-8');
                    grid.classList.add('w-full', 'block');
                }
                if (container) {
                    container.classList.remove('max-w-7xl', 'px-4');
                    container.classList.add('max-w-[1720px]', 'w-full', 'px-2', 'sm:px-6');
                }
            } else {
                // Desativar modo simulado: restaurar sidebar, layout padrão e abas
                if (sidebar) sidebar.classList.remove('hidden');
                if (heroBanner) heroBanner.classList.remove('hidden');
                if (tabsBar) tabsBar.classList.remove('hidden');

                if (sec) {
                    sec.classList.remove('lg:col-span-12', 'w-full');
                    sec.classList.add('lg:col-span-9');
                }
                if (grid) {
                    grid.classList.remove('w-full', 'block');
                    grid.classList.add('grid', 'grid-cols-1', 'lg:grid-cols-12', 'gap-8');
                }
                if (container) {
                    container.classList.remove('max-w-[1720px]', 'w-full', 'px-2');
                    container.classList.add('max-w-7xl', 'px-4');
                }
            }
        }

        // Função de Saída rápida do Simulado de volta ao Banco de Temas com confirmação se houver prova em andamento
        async function sairDoModoSimulado() {
            if (isRedacaoAtiva && timerSeconds > 10) {
                const result = await Swal.fire({
                    title: 'Sair da Redação?',
                    text: 'O seu tempo de produção está correndo. Deseja pausar o cronômetro e voltar ao catálogo de temas?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sim, sair e pausar',
                    cancelButtonText: 'Continuar escrevendo',
                    confirmButtonColor: '#4f46e5',
                    cancelButtonColor: '#64748b',
                    reverseButtons: true
                });
                if (!result.isConfirmed) {
                    return;
                }
            }
            if (typeof pausarCronometro === 'function') {
                pausarCronometro();
            }
            isRedacaoAtiva = false;
            switchPortalTab('banco');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Disparo de Envio pelo cabeçalho do Simulado
        function dispararEnvioRedacao() {
            const btn = document.getElementById('btnSubmitRedacao');
            if (btn) {
                btn.click();
            } else {
                const form = document.getElementById('formRedacao');
                if (form) form.requestSubmit();
            }
        }

        // Controle de Abas
        function switchPortalTab(tab) {
            const tabs = ['banco', 'escrever', 'repertorio', 'historico'];
            tabs.forEach(t => {
                const btn = document.getElementById('tabBtn' + t.charAt(0).toUpperCase() + t.slice(1));
                const content = document.getElementById('tabContent' + t.charAt(0).toUpperCase() + t.slice(1));
                if (btn && content) {
                    if (t === tab) {
                        btn.className = "flex-1 min-w-[130px] py-3 rounded-xl font-outfit font-extrabold text-xs sm:text-sm transition-all flex items-center justify-center gap-2 bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm border border-slate-200 dark:border-slate-600";
                        content.classList.remove('hidden');
                    } else {
                        btn.className = "flex-1 min-w-[130px] py-3 rounded-xl font-outfit font-extrabold text-xs sm:text-sm transition-all flex items-center justify-center gap-2 text-slate-500 hover:text-slate-800 dark:hover:text-slate-200";
                        content.classList.add('hidden');
                    }
                }
            });

            if (tab === 'escrever') {
                aplicarModoSimulado(true);
                aoMudarTema();
                if (etapaAtual !== 'leitura') {
                    irParaEtapa('escrita');
                }
            } else {
                if (typeof pausarCronometro === 'function') {
                    pausarCronometro();
                }
                isRedacaoAtiva = false;
                aplicarModoSimulado(false);
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        let bancaAtivaFiltro = 'TODOS';

        // Helper de normalização para busca sem acentos e case-insensitive
        function normalizarTexto(txt) {
            return (txt || '').toString().normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase().trim();
        }

        // Escape seguro para injeção HTML
        function escapeHtml(str) {
            if (str === null || str === undefined) return '';
            return String(str)
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        let globalInfograficoCounter = 0;

        function alternarVisualizacaoCard(id, modo) {
            const vInfo = document.getElementById('view_infografico_' + id);
            const vFoto = document.getElementById('view_foto_' + id);
            const bInfo = document.getElementById('btn_toggle_info_' + id);
            const bFoto = document.getElementById('btn_toggle_foto_' + id);

            if (modo === 'foto') {
                if (vInfo) vInfo.classList.add('hidden');
                if (vFoto) vFoto.classList.remove('hidden');
                if (bFoto) bFoto.className = "px-2 py-0.5 rounded-md bg-indigo-600 text-white transition flex items-center gap-1 shadow-xs";
                if (bInfo) bInfo.className = "px-2 py-0.5 rounded-md text-slate-600 dark:text-slate-300 hover:text-indigo-600 transition flex items-center gap-1";
            } else {
                if (vFoto) vFoto.classList.add('hidden');
                if (vInfo) vInfo.classList.remove('hidden');
                if (bInfo) bInfo.className = "px-2 py-0.5 rounded-md bg-indigo-600 text-white transition flex items-center gap-1 shadow-xs";
                if (bFoto) bFoto.className = "px-2 py-0.5 rounded-md text-slate-600 dark:text-slate-300 hover:text-indigo-600 transition flex items-center gap-1";
            }
        }

        function gerarInfograficoHtml(url, legenda, contextoGeral) {
            globalInfograficoCounter++;
            const uId = 'info_' + globalInfograficoCounter;
            const legNorm = normalizarTexto(legenda);
            const ctxNorm = normalizarTexto(contextoGeral);
            const combinado = legNorm + ' ' + ctxNorm;

            // Identificar se a proposta exige um infográfico/gráfico oficial de dados
            const ehInfografico = /infograf|grafic|censo|indicador|taxa|estatist|pesquisa|pnad|ibge|cnj|ancine|inep|oms|ipcc|datasus|dados/i.test(legenda);

            if (!ehInfografico) {
                // Foto comum (sem prometer infográfico)
                return `
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
            }

            // Definição dos Dados do Infográfico
            let orgao = 'INSTITUTO OFICIAL DE PESQUISA • BRASIL';
            let titulo = legenda.replace(/^infogr[aá]fico\s*(oficial)?\s*:\s*/i, '').replace(/^gr[aá]fico\s*:\s*/i, '');
            let fonte = 'Relatórios Oficiais e Bases Públicas de Dados Governamentais';
            let kpi1 = { valor: '40,4%', rotulo: 'Índice de Destaque', desc: 'Dados apurados pelo órgão' };
            let kpi2 = { valor: '68%', rotulo: 'Impacto Geral', desc: 'Abrangência do fenômeno' };
            let kpi3 = { valor: '24 Estados', rotulo: 'Extensão Nacional', desc: 'Incidência no território' };
            let barras = [
                { rotulo: 'Segmento Principal', pct: 60, valor: '60%', cor: 'bg-indigo-600' },
                { rotulo: 'Segmento Secundário', pct: 40, valor: '40%', cor: 'bg-amber-500' }
            ];

            // 1. SISTEMA PRISIONAL & CNJ
            if (/prision|carcer|cnj|custodia|pena/i.test(combinado)) {
                orgao = 'CONSELHO NACIONAL DE JUSTIÇA (CNJ) • BNMP 3.0';
                titulo = 'População Carcerária e Presos Provisórios no Brasil';
                fonte = 'Banco Nacional de Monitoramento de Prisões (BNMP/CNJ) e SENAPPEN (2024)';
                kpi1 = { valor: '40,4%', rotulo: 'Presos Provisórios', desc: 'Mais de 336 mil detentos sem condenação definitiva' };
                kpi2 = { valor: '832.295', rotulo: 'População Carcerária Total', desc: '3ª maior população carcerária do mundo' };
                kpi3 = { valor: '172%', rotulo: 'Taxa de Ocupação Média', desc: 'Superlotação crítica com déficit de ~350 mil vagas' };
                barras = [
                    { rotulo: 'Presos Provisórios (Aguardando julgamento)', pct: 40.4, valor: '40.4% (~336 mil)', cor: 'bg-amber-500' },
                    { rotulo: 'Presos com Condenação Definitiva', pct: 59.6, valor: '59.6% (~496 mil)', cor: 'bg-indigo-600' }
                ];
            }
            // 2. TRABALHO DE CUIDADO / MULHERES
            else if (/cuidado|afazeres|domestico|genero|mulher/i.test(combinado)) {
                orgao = 'IBGE • PESQUISA NACIONAL POR AMOSTRA DE DOMICÍLIOS (PNAD)';
                titulo = 'Média Semanal de Horas em Afazeres Domésticos e Cuidado de Pessoas';
                fonte = 'IBGE / Diretoria de Pesquisas — Estatísticas de Gênero';
                kpi1 = { valor: '21,3 h/sem', rotulo: 'Mulheres', desc: 'Tempo semanal dedicado ao trabalho de cuidado invisível' };
                kpi2 = { valor: '10,9 h/sem', rotulo: 'Homens', desc: 'Quase metade do tempo dispensado pelas mulheres' };
                kpi3 = { valor: '92,1%', rotulo: 'Taxa de Realização Feminina', desc: 'Proporção de mulheres que cuidam da casa/filhos' };
                barras = [
                    { rotulo: 'Mulheres (21,3 horas/semana)', pct: 66.1, valor: '66.1%', cor: 'bg-rose-500' },
                    { rotulo: 'Homens (10,9 horas/semana)', pct: 33.9, valor: '33.9%', cor: 'bg-sky-500' }
                ];
            }
            // 3. REGISTRO CIVIL & CIDADANIA
            else if (/certidao|registro civil|nascimento|indocumentado/i.test(combinado)) {
                orgao = 'ARPEN-BRASIL & CENSO DEMOGRÁFICO / IBGE';
                titulo = 'Estimativa de População sem Certidão de Nascimento no Brasil';
                fonte = 'Arpen-Brasil e Censo Demográfico do IBGE';
                kpi1 = { valor: '~3 milhões', rotulo: 'Brasileiros sem Certidão', desc: 'Cidadãos invisíveis sem documentação básica' };
                kpi2 = { valor: '46%', rotulo: 'Crianças de 0 a 5 anos', desc: 'Maior parcela vulnerável sem acesso a creches e SUS' };
                kpi3 = { valor: '64%', rotulo: 'Concentração Norte/Nordeste', desc: 'Regiões com maior índice de sub-registro civil' };
                barras = [
                    { rotulo: 'Norte e Nordeste', pct: 64, valor: '64%', cor: 'bg-amber-500' },
                    { rotulo: 'Sul, Sudeste e Centro-Oeste', pct: 36, valor: '36%', cor: 'bg-indigo-600' }
                ];
            }
            // 4. DEMOCRATIZAÇÃO DO CINEMA
            else if (/cinema|salas de exibicao|ancine|filme/i.test(combinado)) {
                orgao = 'AGÊNCIA NACIONAL DO CINEMA (ANCINE) • OBSERVATÓRIO DO CINEMA';
                titulo = 'Distribuição Territorial e Concentração das Salas de Cinema';
                fonte = 'Ancine / Anuário Estatístico do Cinema Brasileiro';
                kpi1 = { valor: '84%', rotulo: 'Municípios sem Cinema', desc: 'Mais de 4.600 cidades sem nenhuma sala comercial' };
                kpi2 = { valor: '62%', rotulo: 'Concentração no Sudeste', desc: 'Predomínio de salas em capitais e shoppings do Sudeste' };
                kpi3 = { valor: '16%', rotulo: 'Municípios Atendidos', desc: 'Apenas uma minoria da população tem acesso contínuo' };
                barras = [
                    { rotulo: 'Municípios Sem Nenhuma Sala de Cinema', pct: 84, valor: '84%', cor: 'bg-rose-500' },
                    { rotulo: 'Municípios Com Salas de Cinema', pct: 16, valor: '16%', cor: 'bg-emerald-500' }
                ];
            }
            // 5. SAÚDE MENTAL & ANSIEDADE
            else if (/mental|ansiedade|depressao|oms|estigma|psiquic/i.test(combinado)) {
                orgao = 'ORGANIZAÇÃO MUNDIAL DA SAÚDE (OMS) & MINISTÉRIO DA SAÚDE';
                titulo = 'Prevalência de Transtornos de Ansiedade e Depressão no Brasil';
                fonte = 'Relatório Mundial de Saúde Mental da OMS e DataSUS';
                kpi1 = { valor: '9,3%', rotulo: 'Transtorno de Ansiedade', desc: 'Brasil é líder mundial na prevalência de ansiedade' };
                kpi2 = { valor: '5,8%', rotulo: 'Depressão Clínica', desc: 'Maior taxa registrada entre os países da América Latina' };
                kpi3 = { valor: '78%', rotulo: 'Estigma e Silenciamento', desc: 'Pessoas que deixam de buscar tratamento por preconceito' };
                barras = [
                    { rotulo: 'Taxa de Ansiedade no Brasil', pct: 72, valor: '9.3%', cor: 'bg-rose-500' },
                    { rotulo: 'Média Global de Ansiedade', pct: 28, valor: '3.6%', cor: 'bg-slate-400' }
                ];
            }
            // 6. REDES SOCIAIS, ALGORITMOS E TEMPO DE TELA
            else if (/algoritmo|redes sociais|atencao|tela|digital|ciber/i.test(combinado)) {
                orgao = 'CETIC.BR • COMITÊ GESTOR DA INTERNET NO BRASIL (CGI.BR)';
                titulo = 'Tempo de Conexão, Rastreamento Algorítmico e Economia da Atenção';
                fonte = 'Pesquisa TIC Domicílios e Global Web Index';
                kpi1 = { valor: '3h 42m', rotulo: 'Média Diária em Redes', desc: 'Tempo diário gasto exclusivamente em aplicativos sociais' };
                kpi2 = { valor: '86%', rotulo: 'Consumo de Notícias', desc: 'Usuários que recebem informações mediadas por feed' };
                kpi3 = { valor: '71%', rotulo: 'Sobrecarga Informacional', desc: 'Relatam perda de concentração e ansiedade digital' };
                barras = [
                    { rotulo: 'Tempo em Redes e Apps Sociais (3h42m)', pct: 76, valor: '76%', cor: 'bg-indigo-600' },
                    { rotulo: 'Tempo com Leitura Profunda e Estudos (1h10m)', pct: 24, valor: '24%', cor: 'bg-slate-400' }
                ];
            }
            // 7. DESMATAMENTO, CLIMA E ESTIAGEM
            else if (/clima|estiagem|ipcc|seca|aquecimento|amazonia|desmat/i.test(combinado)) {
                orgao = 'INPE (TERRABRASILIS) & RELATÓRIO DO IPCC / ONU';
                titulo = 'Emergência Climática, Frequência de Extremos e Cobertura Vegetal';
                fonte = 'Painel Intergovernamental de Mudança Climática (IPCC) e INPE';
                kpi1 = { valor: '+400%', rotulo: 'Eventos Extremos', desc: 'Aumento na frequência de secas e enchentes severas' };
                kpi2 = { valor: '1,5 °C', rotulo: 'Meta Limite do Clima', desc: 'Acordo de Paris para conter impactos irreversíveis' };
                kpi3 = { valor: '-22%', rotulo: 'Vazão Hídrica Média', desc: 'Queda na disponibilidade de água doce em bacias chave' };
                barras = [
                    { rotulo: 'Queimadas e Ações Antrópicas', pct: 88, valor: '88%', cor: 'bg-rose-500' },
                    { rotulo: 'Causas Naturais de Ignição', pct: 12, valor: '12%', cor: 'bg-emerald-500' }
                ];
            }
            // 8. RESÍDUOS SÓLIDOS E RECICLAGEM
            else if (/residuo|lixo|recicl|plastico|abrelpe/i.test(combinado)) {
                orgao = 'ABRELPE & SISTEMA NACIONAL DE INFORMAÇÕES SOBRE SANEAMENTO (SNIS)';
                titulo = 'Panorama da Geração e Destinação de Resíduos Sólidos no Brasil';
                fonte = 'Panorama dos Resíduos Sólidos no Brasil (Abrelpe / MMA)';
                kpi1 = { valor: '4%', rotulo: 'Taxa de Reciclagem', desc: 'Apenas 4% dos resíduos recicláveis são reaproveitados' };
                kpi2 = { valor: '81,8 mi', rotulo: 'Toneladas de Lixo/Ano', desc: 'Produção massiva de resíduos sólidos urbanos no país' };
                kpi3 = { valor: '39%', rotulo: 'Lixões e Aterros', desc: 'Municípios que ainda descartam lixo sem tratamento' };
                barras = [
                    { rotulo: 'Lixo Descartado em Aterros / Lixões', pct: 96, valor: '96%', cor: 'bg-slate-500' },
                    { rotulo: 'Lixo Efetivamente Reciclado', pct: 4, valor: '4%', cor: 'bg-emerald-500' }
                ];
            }
            // 9. LEITURA & ALFABETIZAÇÃO FUNCIONAL
            else if (/leitura|alfabetiz|inaf|inep|escolar|evasao/i.test(combinado)) {
                orgao = 'INSTITUTO PAULO MONTENEGRO & INEP / MINISTÉRIO DA EDUCAÇÃO';
                titulo = 'Indicadores de Alfabetismo Funcional e Competência Leitora';
                fonte = 'Indicador de Alfabetismo Funcional (INAF) e Censo Escolar';
                kpi1 = { valor: '29%', rotulo: 'Analfabetos Funcionais', desc: 'Sabem ler palavras, mas não interpretam textos longos' };
                kpi2 = { valor: '34%', rotulo: 'Nível Elementar', desc: 'Leem textos curtos e encontram informações explícitas' };
                kpi3 = { valor: '12%', rotulo: 'Nível Proficiente', desc: 'Apenas 1 em cada 8 cidadãos domina leitura crítica plena' };
                barras = [
                    { rotulo: 'Nível Rudimentar ou Elementar', pct: 63, valor: '63%', cor: 'bg-amber-500' },
                    { rotulo: 'Nível Intermediário ou Proficiente', pct: 37, valor: '37%', cor: 'bg-indigo-600' }
                ];
            }
            // 10. DESIGUALDADE DE RENDA
            else if (/desigualdade|renda|riqueza|pobreza|concentracao/i.test(combinado)) {
                orgao = 'WORLD INEQUALITY LAB & IBGE (PNAD CONTÍNUA)';
                titulo = 'Distribuição da Renda Nacional e Concentração Patrimonial';
                fonte = 'World Inequality Database e Instituto de Pesquisa Econômica Aplicada';
                kpi1 = { valor: '58,6%', rotulo: 'Topo 10% Mais Rico', desc: 'Fatia de toda a renda nacional concentrada no topo' };
                kpi2 = { valor: '10,1%', rotulo: 'Base 50% Mais Pobre', desc: 'Metade da população brasileira sobrevive com um décimo' };
                kpi3 = { valor: '29x', rotulo: 'Disparidade Extrema', desc: 'Renda média do 1% mais rico é 29 vezes a da base' };
                barras = [
                    { rotulo: '10% Mais Ricos (58,6% da renda)', pct: 58.6, valor: '58.6%', cor: 'bg-indigo-600' },
                    { rotulo: '40% Estrato Médio (31,3% da renda)', pct: 31.3, valor: '31.3%', cor: 'bg-sky-500' },
                    { rotulo: '50% Mais Pobres (10,1% da renda)', pct: 10.1, valor: '10.1%', cor: 'bg-amber-500' }
                ];
            }
            // 11. UBERIZAÇÃO & TRABALHO EM APLICATIVOS
            else if (/entregador|aplicativo|uber|plataforma|informal/i.test(combinado)) {
                orgao = 'IPEA • INSTITUTO DE PESQUISA ECONÔMICA APLICADA & IBGE';
                titulo = 'Jornada Média e Condições de Trabalho em Plataformas Digitais';
                fonte = 'IPEA / Nota Técnica sobre Plataformização do Trabalho Urbano';
                kpi1 = { valor: '62 h/sem', rotulo: 'Jornada Média Semanal', desc: 'Trabalho contínuo nas ruas sem limite de jornada diária' };
                kpi2 = { valor: '39,2%', rotulo: 'Taxa de Informalidade Geral', desc: 'Mais de 38 milhões de trabalhadores sem carteira no país' };
                kpi3 = { valor: '76%', rotulo: 'Sem Previdência Social', desc: 'Trabalhadores desamparados em caso de acidente ou doença' };
                barras = [
                    { rotulo: 'Jornada em Aplicativos de Entrega (62h)', pct: 62, valor: '62h', cor: 'bg-amber-500' },
                    { rotulo: 'Jornada Máxima Padrão CLT (44h)', pct: 38, valor: '44h', cor: 'bg-indigo-600' }
                ];
            }
            // 12. MOBILIDADE URBANA & TRANSPORTE SOBRE TRILHOS
            else if (/mobilidade|trilhos|transporte|metro|trem|transito/i.test(combinado)) {
                orgao = 'ANPTRILHOS & INSTITUTO DE POLÍTICAS DE TRANSPORTE (ITDP)';
                titulo = 'Divisão Modal dos Deslocamentos Metropolitanos no Brasil';
                fonte = 'Relatório Anual do Setor Metroferroviário e Pesquisas de Origem-Destino';
                kpi1 = { valor: '14,2%', rotulo: 'Deslocamentos sobre Trilhos', desc: 'Subutilização de trens e metrô nas grandes capitais' };
                kpi2 = { valor: '74,5%', rotulo: 'Dependência Rodoviária', desc: 'Predomínio de automóveis particulares e ônibus poluentes' };
                kpi3 = { valor: '2h 15m', rotulo: 'Tempo em Congestionamentos', desc: 'Média de horas perdidas no trânsito diariamente' };
                barras = [
                    { rotulo: 'Modal Rodoviário (Carros e Ônibus)', pct: 74.5, valor: '74.5%', cor: 'bg-rose-500' },
                    { rotulo: 'Modal Metroferroviário (Trilhos)', pct: 14.2, valor: '14.2%', cor: 'bg-emerald-500' },
                    { rotulo: 'Modais Ativos (Bicicleta e a Pé)', pct: 11.3, valor: '11.3%', cor: 'bg-slate-400' }
                ];
            }
            // 13. SURDOS & EDUCAÇÃO BILÍNGUE
            else if (/surdo|libras|auditiva|bilingue|deficiencia/i.test(combinado)) {
                orgao = 'INEP & DIRETORIA DE EDUCAÇÃO ESPECIAL (MEC)';
                titulo = 'Acesso à Educação Bilíngue de Libras e Inclusão Escolar';
                fonte = 'Censo Escolar da Educação Básica e Relatórios do Inep';
                kpi1 = { valor: '23%', rotulo: 'Escolas com Libras', desc: 'Apenas uma minoria conta com intérprete habilitado' };
                kpi2 = { valor: '9,7 mi', rotulo: 'Deficientes Auditivos no País', desc: 'População surda com barreiras severas de comunicação' };
                kpi3 = { valor: '68%', rotulo: 'Evasão Antes do Superior', desc: 'Barreiras linguísticas que impedem progressão acadêmica' };
                barras = [
                    { rotulo: 'Escolas Sem Intérprete de Libras', pct: 77, valor: '77%', cor: 'bg-rose-500' },
                    { rotulo: 'Escolas Com Acessibilidade Bilíngue', pct: 23, valor: '23%', cor: 'bg-emerald-500' }
                ];
            }
            // 14. INTOLERÂNCIA RELIGIOSA
            else if (/religio|intolerancia|crenca|disque 100/i.test(combinado)) {
                orgao = 'OUVIDORIA NACIONAL DE DIREITOS HUMANOS • DISQUE 100';
                titulo = 'Denúncias de Violações de Liberdade Religiosa por Matriz';
                fonte = 'Painel Oficial de Dados da Ouvidoria Nacional dos Direitos Humanos';
                kpi1 = { valor: '+106%', rotulo: 'Aumento nas Denúncias', desc: 'Crescimento de registros de intolerância e agressões' };
                kpi2 = { valor: '58%', rotulo: 'Matriz Afro-Brasileira', desc: 'Maior parcela dos ataques mira terreiros e sacerdotes' };
                kpi3 = { valor: '72%', rotulo: 'Violência Verbal e Danos', desc: 'Invasões a locais sagrados e discursos de ódio online' };
                barras = [
                    { rotulo: 'Vítimas de Matriz Afro-Brasileira', pct: 58, valor: '58%', cor: 'bg-purple-600' },
                    { rotulo: 'Outras Matrizes e Grupos sem Religião', pct: 42, valor: '42%', cor: 'bg-slate-500' }
                ];
            }
            // 15. LEI SECA & TRÂNSITO
            else if (/lei seca|transito|mortalidade|acidente|bafometro/i.test(combinado)) {
                orgao = 'OBSERVATÓRIO NACIONAL DE SEGURANÇA VIÁRIA & DATASUS';
                titulo = 'Indicadores de Mortalidade e Efeitos da Fiscalização de Trânsito';
                fonte = 'Sistema de Informações de Mortalidade (SIM/DataSUS) e ONSV';
                kpi1 = { valor: '33.000', rotulo: 'Mortes Anuais no Trânsito', desc: 'Uma das principais causas de óbito de jovens no Brasil' };
                kpi2 = { valor: '-21%', rotulo: 'Impacto da Lei Seca', desc: 'Queda de óbitos comprovada após tolerância zero ao álcool' };
                kpi3 = { valor: '54%', rotulo: 'Motociclistas e Pedestres', desc: 'Grupos mais vulneráveis nas vias urbanas e rodovias' };
                barras = [
                    { rotulo: 'Índice de Óbitos Antes da Lei Seca', pct: 100, valor: '100% (Base)', cor: 'bg-rose-500' },
                    { rotulo: 'Índice com Fiscalização Rigorosa (-21%)', pct: 79, valor: '79%', cor: 'bg-emerald-500' }
                ];
            }
            // 16. VIOLÊNCIA RACIAL & HOMICÍDIOS
            else if (/racismo|racial|afro|negra|violencia/i.test(combinado)) {
                orgao = 'ATLAS DA VIOLÊNCIA • IPEA & FÓRUM BRASILEIRO DE SEGURANÇA PÚBLICA';
                titulo = 'Indicadores de Desigualdade e Vitimização Racial no Brasil';
                fonte = 'Atlas da Violência / IPEA e Fórum Brasileiro de Segurança Pública';
                kpi1 = { valor: '77%', rotulo: 'Vítimas Negras', desc: 'Parcela das vítimas de homicídio no Brasil que são negras' };
                kpi2 = { valor: '2,6x', rotulo: 'Risco de Morte Violenta', desc: 'Jovem negro tem risco 2,6 vezes maior que jovem branco' };
                kpi3 = { valor: '44%', rotulo: 'Desigualdade Salarial', desc: 'Diferença de remuneração média entre negros e não negros' };
                barras = [
                    { rotulo: 'Homicídios de Pessoas Negras', pct: 77, valor: '77%', cor: 'bg-amber-600' },
                    { rotulo: 'Homicídios de Pessoas Não Negras', pct: 23, valor: '23%', cor: 'bg-sky-500' }
                ];
            }
            // 17. FEMINICÍDIO & VIOLÊNCIA CONTRA A MULHER
            else if (/feminicidio|violencia contra a mulher|maria da penha/i.test(combinado)) {
                orgao = 'FÓRUM BRASILEIRO DE SEGURANÇA PÚBLICA (FBSP)';
                titulo = 'Notificações de Feminicídio e Violência Doméstica no Brasil';
                fonte = 'Anuário Brasileiro de Segurança Pública e Ligue 180';
                kpi1 = { valor: '1 a cada 6h', rotulo: 'Vítima de Feminicídio', desc: 'Média de 4 mulheres assassinadas por dia no país' };
                kpi2 = { valor: '71,6%', rotulo: 'Dentro da Própria Casa', desc: 'A maior parte dos crimes ocorre no ambiente doméstico' };
                kpi3 = { valor: '78%', rotulo: 'Autores Conhecidos', desc: 'Cometidos pelo atual parceiro ou ex-companheiro' };
                barras = [
                    { rotulo: 'Crime Cometido por Parceiro ou Ex-Parceiro', pct: 78, valor: '78%', cor: 'bg-rose-600' },
                    { rotulo: 'Crime Cometido por Outros Autores', pct: 22, valor: '22%', cor: 'bg-slate-400' }
                ];
            }
            // 18. FAKE NEWS & DESINFORMAÇÃO
            else if (/fake news|desinformacao|noticias falsas|boatos/i.test(combinado)) {
                orgao = 'PESQUISA DATAFOLHA & INSTITUTO TECNOLOGIA E SOCIEDADE (ITS RIO)';
                titulo = 'Disseminação de Conteúdos Falsos e Confiança nas Instituições';
                fonte = 'Pesquisas Eleitorais e Sociais Datafolha e ITS Rio';
                kpi1 = { valor: '68%', rotulo: 'Compartilharam Fake News', desc: 'Já acreditaram ou compartilharam conteúdo comprovadamente falso' };
                kpi2 = { valor: '79%', rotulo: 'Ameaça à Democracia', desc: 'Concordam que desinformação prejudica decisões cívicas' };
                kpi3 = { valor: '84%', rotulo: 'Regulação de Plataformas', desc: 'Defendem transparência obrigatória nos algoritmos' };
                barras = [
                    { rotulo: 'Compartilharam Conteúdos Não Checados', pct: 68, valor: '68%', cor: 'bg-amber-500' },
                    { rotulo: 'Checam a Fonte Antes de Compartilhar', pct: 32, valor: '32%', cor: 'bg-indigo-600' }
                ];
            }

            // Construir o HTML das barras comparativas
            let barrasHtml = `
                <div class="p-3.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 space-y-2.5">
                    <div class="flex items-center justify-between text-xs font-bold text-slate-700 dark:text-slate-300">
                        <span>Comparativo Percentual • Coletânea do Exame</span>
                        <span class="text-[10px] text-slate-400 font-semibold">Total: 100%</span>
                    </div>
                    <div class="w-full h-5 rounded-lg overflow-hidden flex bg-slate-100 dark:bg-slate-700 shadow-inner">
            `;
            barras.forEach(b => {
                barrasHtml += `<div style="width: ${b.pct}%;" class="${b.cor} text-white text-[10px] font-black flex items-center justify-center transition-all truncate px-1" title="${escapeHtml(b.rotulo)}: ${b.valor}">${b.valor}</div>`;
            });
            barrasHtml += `</div><div class="flex items-center justify-between flex-wrap gap-2 text-[11px] font-semibold text-slate-600 dark:text-slate-300 pt-1">`;
            barras.forEach(b => {
                barrasHtml += `
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-sm ${b.cor} inline-block shrink-0"></span>
                        <span>${escapeHtml(b.rotulo)}: <strong>${b.valor}</strong></span>
                    </span>
                `;
            });
            barrasHtml += `</div></div>`;

            // Construir HTML dos KPIs
            const kpisHtml = `
                <div class="p-3 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-xs">
                    <span class="block text-[10px] font-bold text-slate-500 uppercase">${escapeHtml(kpi1.rotulo)}</span>
                    <div class="flex items-baseline gap-1 mt-0.5">
                        <span class="text-xl sm:text-2xl font-black text-amber-500 font-outfit">${escapeHtml(kpi1.valor)}</span>
                    </div>
                    <span class="block text-[10px] text-slate-400 mt-1 leading-snug">${escapeHtml(kpi1.desc)}</span>
                </div>
                <div class="p-3 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-xs">
                    <span class="block text-[10px] font-bold text-slate-500 uppercase">${escapeHtml(kpi2.rotulo)}</span>
                    <div class="flex items-baseline gap-1 mt-0.5">
                        <span class="text-xl sm:text-2xl font-black text-indigo-600 dark:text-indigo-400 font-outfit">${escapeHtml(kpi2.valor)}</span>
                    </div>
                    <span class="block text-[10px] text-slate-400 mt-1 leading-snug">${escapeHtml(kpi2.desc)}</span>
                </div>
                <div class="col-span-2 sm:col-span-1 p-3 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-xs">
                    <span class="block text-[10px] font-bold text-slate-500 uppercase">${escapeHtml(kpi3.rotulo)}</span>
                    <div class="flex items-baseline gap-1 mt-0.5">
                        <span class="text-xl sm:text-2xl font-black text-rose-500 font-outfit">${escapeHtml(kpi3.valor)}</span>
                    </div>
                    <span class="block text-[10px] text-slate-400 mt-1 leading-snug">${escapeHtml(kpi3.desc)}</span>
                </div>
            `;

            return `
                <div class="infografico-wrapper my-3 rounded-2xl border-2 border-indigo-200 dark:border-indigo-800/60 bg-gradient-to-br from-indigo-50/60 via-white to-slate-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 shadow-sm overflow-hidden" id="infografico_box_${uId}">
                    <div class="px-4 py-2.5 bg-indigo-600/10 dark:bg-indigo-950/40 border-b border-indigo-100 dark:border-indigo-900/40 flex items-center justify-between gap-2 flex-wrap">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-indigo-600 text-white flex items-center justify-center text-xs shadow-sm">
                                <i class="bi bi-bar-chart-fill"></i>
                            </span>
                            <span class="text-[11px] font-outfit font-black uppercase tracking-wider text-indigo-700 dark:text-indigo-300">
                                ${escapeHtml(orgao)}
                            </span>
                        </div>
                        
                        <div class="flex items-center gap-1 bg-white dark:bg-slate-800 p-0.5 rounded-lg border border-slate-200 dark:border-slate-700 text-[10px] font-bold">
                            <button type="button" onclick="alternarVisualizacaoCard('${uId}', 'infografico')" id="btn_toggle_info_${uId}" class="px-2 py-0.5 rounded-md bg-indigo-600 text-white transition flex items-center gap-1 shadow-xs">
                                <i class="bi bi-pie-chart-fill"></i>
                                <span>Infográfico Oficial</span>
                            </button>
                            <button type="button" onclick="alternarVisualizacaoCard('${uId}', 'foto')" id="btn_toggle_foto_${uId}" class="px-2 py-0.5 rounded-md text-slate-600 dark:text-slate-300 hover:text-indigo-600 transition flex items-center gap-1">
                                <i class="bi bi-image"></i>
                                <span>Foto</span>
                            </button>
                        </div>
                    </div>

                    <!-- VISÃO 1: INFOGRÁFICO DE DADOS -->
                    <div id="view_infografico_${uId}" class="p-4 sm:p-5 space-y-4">
                        <div>
                            <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400 block mb-0.5">PESQUISA & INDICADORES DO EXAME</span>
                            <h4 class="font-outfit font-black text-sm sm:text-base text-slate-900 dark:text-white leading-snug">
                                ${escapeHtml(titulo)}
                            </h4>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                            ${kpisHtml}
                        </div>

                        ${barrasHtml}

                        <div class="fonte-nao-grifavel pt-2 border-t border-slate-200 dark:border-slate-700/60 flex items-center justify-between text-[10px] text-slate-500 dark:text-slate-400 flex-wrap gap-2 select-none">
                            <span class="flex items-center gap-1">
                                <i class="bi bi-shield-check text-emerald-500"></i>
                                <strong>Fonte:</strong> ${escapeHtml(fonte)}
                            </span>
                            <span class="font-bold text-indigo-500">Dados Oficiais para Repertório (C2 / C3)</span>
                        </div>
                    </div>

                    <!-- VISÃO 2: FOTO CONCEITUAL -->
                    <div id="view_foto_${uId}" class="hidden p-3 bg-slate-100 dark:bg-slate-900 rounded-b-xl border-t border-slate-200 dark:border-slate-700">
                        <img src="${escapeHtml(url)}" alt="${escapeHtml(legenda)}" class="w-full h-auto max-h-72 object-cover rounded-xl" loading="lazy">
                        <p class="fonte-nao-grifavel text-center text-[10px] text-slate-500 dark:text-slate-400 mt-2 italic select-none">${escapeHtml(legenda)}</p>
                    </div>
                </div>
            `;
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
                            <div class="fonte-nao-grifavel flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-700/60 select-none">
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
                    html += gerarInfograficoHtml(url, legenda, rawText);
                    return;
                }

                // 3. Fonte Oficial: [FONTE: ...]
                const fonteMatch = l.match(/\[FONTE:\s*([^\]]+)\]/i);
                if (fonteMatch) {
                    const fonte = fonteMatch[1].trim();
                    html += `
                        <div class="fonte-nao-grifavel mt-2.5 pt-2 border-t border-dashed border-slate-200 dark:border-slate-700 flex items-center gap-1.5 text-[11px] text-slate-500 dark:text-slate-400 font-bold italic select-none">
                            <i class="bi bi-quote text-indigo-500 text-sm shrink-0"></i>
                            <span>Fonte: ${escapeHtml(fonte)}</span>
                        </div>
                    `;
                    return;
                }

                // 3.1 Fonte Genérica / Citação de origem bibliográfica
                const genericFonteMatch = l.match(/^(\(?Fonte:?|\(?Disponível em:?|\(?Acesso em:?|\(?Adaptado de:?)\s*(.+)$/i);
                if (genericFonteMatch) {
                    html += `
                        <div class="fonte-nao-grifavel mt-2.5 pt-2 border-t border-dashed border-slate-200 dark:border-slate-700 flex items-center gap-1.5 text-[11px] text-slate-500 dark:text-slate-400 font-bold italic select-none">
                            <i class="bi bi-quote text-indigo-500 text-sm shrink-0"></i>
                            <span>${escapeHtml(l)}</span>
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

            const iconBusca = document.getElementById('iconBuscaStatus');
            if (iconBusca) {
                if (termoNorm) {
                    iconBusca.className = 'bi bi-x-circle-fill text-slate-400 hover:text-rose-500';
                } else {
                    iconBusca.className = 'bi bi-search text-slate-400';
                }
            }
        }

        // Abre o primeiro tema visível nos filtros (útil para teclar ENTER no buscador)
        function abrirPrimeiroTemaFiltrado() {
            const cards = document.querySelectorAll('.tema-card');
            for (let c of cards) {
                if (c.style.display !== 'none' && !c.classList.contains('hidden')) {
                    const id = c.getAttribute('data-id');
                    if (id) {
                        iniciarEscritaComTema(id, 'escrita');
                        break;
                    }
                }
            }
        }

        // Limpa o buscador em 1 clique
        function limparBuscaTema() {
            const input = document.getElementById('inputBuscaTema');
            if (input) {
                input.value = '';
                aplicarFiltrosTemas();
                input.focus();
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

        // Ações de Proposta em Tela Cheia (Modal com PDF Direto)
        function verPropostaCompleta(id) {
            const tema = TODOS_TEMAS.find(t => parseInt(t.id) === parseInt(id));
            if (!tema) return;

            const bancaUpper = (tema.banca || 'ENEM').toUpperCase();
            const isPossivel = (bancaUpper === 'POSSIVEIS');
            const isRedigir = (bancaUpper === 'REDIGIR');
            const isReal = !isPossivel && !isRedigir && bancaUpper !== 'SIMULADO';
            const rotuloModal = isPossivel ? `💡 Possível Tema ${tema.ano || ''}` : (isReal ? `🏛️ ${bancaUpper} ${tema.ano || ''} • Prova Real` : `📚 Redigir ${tema.ano || ''}`);

            document.getElementById('modalPropostaBanca').textContent = `${rotuloModal} • ${tema.eixo_tematico || ''}`;
            document.getElementById('modalPropostaTitulo').textContent = tema.titulo;

            // Carregar PDF diretamente no iframe do modal (LOGO DE CARA)
            const modalFrame = document.getElementById('modalPdfFrame');
            if (modalFrame) {
                modalFrame.src = 'visualizar_pdf.php?id=' + id;
            }

            const btnDownloadPdf = document.getElementById('btnModalDownloadPdf');
            if (btnDownloadPdf) {
                btnDownloadPdf.href = 'visualizar_pdf.php?id=' + id + '&download=1';
            }

            const btnNovaAba = document.getElementById('btnModalNovaAba');
            if (btnNovaAba) {
                btnNovaAba.href = 'visualizar_pdf.php?id=' + id;
            }

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
            const modalFrame = document.getElementById('modalPdfFrame');
            if (modalFrame) {
                modalFrame.src = ''; // Limpar para poupar memória
            }
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

        // =========================================================================
        // ESTADO GLOBAL DO WORKSPACE LETRUS
        // =========================================================================
        let etapaAtual = 'leitura'; // 'leitura' | 'escrita'
        let modoVisualizacaoBancada = 'split'; // 'split' | 'redacao-only' | 'textos-only'
        let corGrifoAtiva = 'amarelo'; // 'amarelo' | 'verde' | 'azul' | 'rosa'
        let modoFocoAtivo = false;
        let autoSaveTimer = null;
        let timerSeconds = 0;
        let timerInterval = null;
        let isRedacaoAtiva = false;
        let focusWarningActive = false;

        // Controle do Cronômetro Oficial de Produção
        function iniciarCronometro() {
            isRedacaoAtiva = true;
            const icon = document.getElementById('iconTimer');
            const iconSimulado = document.getElementById('iconTimerSimulado');
            if (icon) icon.className = 'bi bi-pause-fill text-sm';
            if (iconSimulado) iconSimulado.className = 'bi bi-pause-fill text-base';

            if (timerInterval) return;

            timerInterval = setInterval(() => {
                timerSeconds++;
                const h = String(Math.floor(timerSeconds / 3600)).padStart(2, '0');
                const m = String(Math.floor((timerSeconds % 3600) / 60)).padStart(2, '0');
                const s = String(timerSeconds % 60).padStart(2, '0');
                const timeStr = `${h}:${m}:${s}`;
                const el = document.getElementById('displayTimer');
                const elSimulado = document.getElementById('displayTimerSimulado');
                if (el) el.textContent = timeStr;
                if (elSimulado) elSimulado.textContent = timeStr;
            }, 1000);
        }

        function pausarCronometro() {
            isRedacaoAtiva = false;
            if (timerInterval) {
                clearInterval(timerInterval);
                timerInterval = null;
            }
            const icon = document.getElementById('iconTimer');
            const iconSimulado = document.getElementById('iconTimerSimulado');
            if (icon) icon.className = 'bi bi-play-fill text-sm';
            if (iconSimulado) iconSimulado.className = 'bi bi-play-fill text-base';
        }

        function toggleTimer() {
            if (timerInterval) {
                pausarCronometro();
            } else {
                iniciarCronometro();
            }
        }

        // ============================================================
        // SISTEMA DE FOCO TOTAL & ANTI-DISTRAÇÃO ("MANTENHA O FOCO!!!")
        // ============================================================
        function handleFocusLostRedacao() {
            if (!isRedacaoAtiva || focusWarningActive) return;
            // Se houver modal do SweetAlert aberto (ex: envio, confirmação) ou modal de chave API
            if (typeof Swal !== 'undefined' && Swal.isVisible && Swal.isVisible()) return;
            const modalApiKey = document.getElementById('modalApiKey');
            if (modalApiKey && !modalApiKey.classList.contains('hidden')) return;

            focusWarningActive = true;

            Swal.fire({
                title: '⚠️ Mantenha o foco!!!',
                text: 'Você saiu da tela da redação! Para garantir uma preparação autêntica para o vestibular, mantenha sua atenção total na prova.',
                icon: 'warning',
                confirmButtonText: 'Voltar à Redação',
                confirmButtonColor: '#4f46e5',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(() => {
                focusWarningActive = false;
            });
        }

        document.addEventListener('visibilitychange', () => {
            if (document.hidden && isRedacaoAtiva) {
                handleFocusLostRedacao();
            }
        });

        window.addEventListener('blur', () => {
            if (isRedacaoAtiva) {
                // Pequeno debounce para evitar falso positivo com modais internos ou input file
                setTimeout(() => {
                    const fileInput = document.getElementById('arquivoInput');
                    if (fileInput && document.activeElement === fileInput) return;
                    if (isRedacaoAtiva && !document.hasFocus() && (typeof Swal === 'undefined' || !Swal.isVisible || !Swal.isVisible())) {
                        handleFocusLostRedacao();
                    }
                }, 200);
            }
        });

        window.addEventListener('beforeunload', (e) => {
            if (isRedacaoAtiva && timerSeconds > 10) {
                e.preventDefault();
                e.returnValue = 'A sua redação está em andamento. O tempo continua correndo e seu texto está salvo.';
                return e.returnValue;
            }
        });

        // Iniciar Escrita com Proposta selecionada (Abre DIRETO a folha oficial de redação pronta para escrever)
        function iniciarEscritaComTema(id, etapaInicial = 'escrita') {
            fecharModalProposta();
            const select = document.getElementById('temaSelect');
            if (id && select) {
                select.value = id;
            }
            aoMudarTema();
            switchPortalTab('escrever');

            // Abrir diretamente a etapa de escrita por padrão (folha pautada, linhas 1-30 e split-screen com os textos)
            irParaEtapa(etapaInicial);

            // Iniciar o cronômetro imediatamente e marcar a redação como ativa
            iniciarCronometro();

            // Alerta Toast estilo simulado oficial
            if (typeof Swal !== 'undefined') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                Toast.fire({
                    icon: 'info',
                    title: '⏱️ Redação Pronta para Escrita!',
                    text: 'O cronômetro oficial está ativo. Mantenha o foco e boa prova!'
                });
            }

            window.scrollTo({ top: 0, behavior: 'instant' });
            setTimeout(() => {
                if (etapaInicial === 'escrita') {
                    const etapaEscritaEl = document.getElementById('etapaEscrita');
                    if (etapaEscritaEl) etapaEscritaEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    const folha = document.getElementById('textoRedacao');
                    if (folha) folha.focus();
                } else {
                    const etapaLeituraEl = document.getElementById('etapaLeitura');
                    if (etapaLeituraEl) etapaLeituraEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 80);
        }

        // Iniciar com Leitura em Tela Cheia & Grifos (Etapa 1)
        function iniciarLeituraComTema(id) {
            iniciarEscritaComTema(id, 'leitura');
        }

        // Navegação entre Etapa 1 (Leitura) e Etapa 2 (Split-Screen)
        function irParaEtapa(etapa) {
            etapaAtual = etapa;
            const etapaLeitura = document.getElementById('etapaLeitura');
            const etapaEscrita = document.getElementById('etapaEscrita');
            const btnStepLeitura = document.getElementById('btnStepLeitura');
            const btnStepEscrita = document.getElementById('btnStepEscrita');

            const btnAtivo = "flex-1 sm:flex-initial px-4 sm:px-5 py-2.5 rounded-xl text-xs sm:text-sm font-outfit font-extrabold transition-all flex items-center justify-center gap-2 bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm";
            const btnInativo = "flex-1 sm:flex-initial px-4 sm:px-5 py-2.5 rounded-xl text-xs sm:text-sm font-outfit font-extrabold transition-all flex items-center justify-center gap-2 text-slate-500 hover:text-slate-800 dark:hover:text-slate-200";

            if (etapa === 'leitura') {
                salvarGrifosTema();
                sincronizarTextosEntreContainers();

                if (etapaLeitura) etapaLeitura.classList.remove('hidden');
                if (etapaEscrita) etapaEscrita.classList.add('hidden');
                if (btnStepLeitura) btnStepLeitura.className = btnAtivo;
                if (btnStepEscrita) btnStepEscrita.className = btnInativo;
                if (etapaLeitura) etapaLeitura.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                // Sincronizar grifos da Etapa 1 para a Etapa 2
                salvarGrifosTema();
                sincronizarTextosEntreContainers();

                // Garantir que o cronômetro esteja rodando na etapa de escrita
                if (!timerInterval) {
                    iniciarCronometro();
                }

                if (etapaLeitura) etapaLeitura.classList.add('hidden');
                if (etapaEscrita) etapaEscrita.classList.remove('hidden');
                if (btnStepEscrita) btnStepEscrita.className = btnAtivo;
                if (btnStepLeitura) btnStepLeitura.className = btnInativo;

                // Focar suavemente na folha de redação
                setTimeout(() => {
                    const folha = document.getElementById('textoRedacao');
                    const cTexto = document.getElementById('containerTexto');
                    if (folha && cTexto && !cTexto.classList.contains('hidden')) {
                        folha.focus();
                    }
                    if (etapaEscrita) etapaEscrita.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);
            }
        }

        // Alternância de Visualização da Bancada Split (Dividido, Minimizar Textos)
        function setVisualizacaoBancada(modo) {
            modoVisualizacaoBancada = modo;
            const colTextos = document.getElementById('colunaTextos');
            const colRedacao = document.getElementById('colunaRedacao');
            const btnToggleSide = document.getElementById('btnToggleSideTextos');
            const iconSide = document.getElementById('iconSideTextos');
            const txtSide = document.getElementById('txtSideTextos');

            if (modo === 'split') {
                if (colTextos) colTextos.className = "coluna-split lg:col-span-6 xl:col-span-6 space-y-4 transition-all block";
                if (colRedacao) colRedacao.className = "coluna-split lg:col-span-6 xl:col-span-6 space-y-4 transition-all block";
                if (btnToggleSide) {
                    if (iconSide) iconSide.className = 'bi bi-arrows-angle-expand text-xs';
                    if (txtSide) txtSide.textContent = 'Expandir Folha';
                    btnToggleSide.className = "px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-outfit font-bold text-xs flex items-center gap-2 border border-slate-200 dark:border-slate-600 transition-all";
                    btnToggleSide.title = "Expandir folha de redação para largura total";
                }
            } else if (modo === 'redacao-only') {
                // Textos ocultos: Folha de Redação ganha 100% da tela!
                if (colTextos) colTextos.className = "coluna-split hidden";
                if (colRedacao) colRedacao.className = "coluna-split lg:col-span-12 xl:col-span-12 space-y-4 transition-all block";
                if (btnToggleSide) {
                    if (iconSide) iconSide.className = 'bi bi-columns text-xs';
                    if (txtSide) txtSide.textContent = 'Dividir com Textos';
                    btnToggleSide.className = "px-3.5 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 dark:bg-slate-700 dark:hover:bg-slate-600 text-indigo-700 dark:text-indigo-300 font-outfit font-bold text-xs flex items-center gap-2 border border-indigo-200 dark:border-slate-600 shadow-sm transition-all";
                    btnToggleSide.title = "Exibir textos motivadores ao lado da folha";
                }
            }
        }

        // Alternador rápido da setinha
        function toggleTextosAoLado() {
            if (modoVisualizacaoBancada === 'split') {
                setVisualizacaoBancada('redacao-only');
            } else {
                setVisualizacaoBancada('split');
            }
        }

        // Modo Foco Zen / Fullscreen sem distrações
        function toggleModoFocoTotal() {
            modoFocoAtivo = !modoFocoAtivo;
            const container = document.getElementById('tabContentEscrever');
            const icon = document.getElementById('iconModoFoco');
            const txt = document.getElementById('txtModoFoco');

            if (modoFocoAtivo) {
                container.classList.add('modo-foco-zen');
                document.body.style.overflow = 'hidden';
                if (icon) icon.className = 'bi bi-fullscreen-exit text-rose-500';
                if (txt) txt.textContent = 'Sair do Foco';
            } else {
                container.classList.remove('modo-foco-zen');
                document.body.style.overflow = '';
                if (icon) icon.className = 'bi bi-arrows-fullscreen text-indigo-600 dark:text-indigo-400';
                if (txt) txt.textContent = 'Modo Foco';
            }
        }

        // =========================================================================
        // MARCADOR DE TEXTO AUTOMÁTICO (LETRUS STYLE COM CURSOR REALISTA)
        // =========================================================================
        function setCorGrifoAtiva(cor) {
            if (!cor) cor = 'amarelo';
            corGrifoAtiva = cor;
            try {
                localStorage.setItem('letrus_cor_grifo_ativa', cor);
            } catch(e) {}

            // 1. Atualizar classe de cursor nos containers de textos motivadores
            const c1 = document.getElementById('letrusTextosEtapa1');
            const c2 = document.getElementById('letrusTextosSplit');
            const classesCursor = ['cursor-grifo-amarelo', 'cursor-grifo-verde', 'cursor-grifo-azul', 'cursor-grifo-rosa'];
            [c1, c2].forEach(el => {
                if (el) {
                    classesCursor.forEach(cls => el.classList.remove(cls));
                    el.classList.add(`cursor-grifo-${cor}`);
                }
            });

            // 2. Atualizar estado visual de TODOS os botões da paleta (.btn-paleta-cor)
            document.querySelectorAll('.btn-paleta-cor').forEach(btn => {
                const btnCor = btn.dataset.cor;
                if (btnCor === cor) {
                    btn.classList.add('ring-2', 'ring-offset-2', 'ring-indigo-600', 'dark:ring-offset-slate-900', 'scale-110');
                    btn.innerHTML = '<i class="bi bi-check text-[11px] text-slate-950 font-black"></i>';
                } else {
                    btn.classList.remove('ring-2', 'ring-offset-2', 'ring-indigo-600', 'dark:ring-offset-slate-900', 'scale-110');
                    btn.innerHTML = '';
                }
            });
        }

        function aplicarGrifoNaSelecao(cor) {
            const sel = window.getSelection();
            if (!sel || sel.isCollapsed || sel.rangeCount === 0) return;

            const range = sel.getRangeAt(0);
            const containerEtapa1 = document.getElementById('letrusTextosEtapa1');
            const containerSplit = document.getElementById('letrusTextosSplit');

            const inEtapa1 = containerEtapa1 && containerEtapa1.contains(range.commonAncestorContainer);
            const inSplit = containerSplit && containerSplit.contains(range.commonAncestorContainer);

            if (!inEtapa1 && !inSplit) return;

            const selectedText = range.toString().trim();
            if (selectedText.length < 2) return;

            // Bloqueio Absoluto: Proibido grifar fontes ou créditos!
            const common = range.commonAncestorContainer;
            const start = range.startContainer;
            const end = range.endContainer;
            const elCommon = (common.nodeType === 1) ? common : common.parentElement;
            const elStart = (start.nodeType === 1) ? start : start.parentElement;
            const elEnd = (end.nodeType === 1) ? end : end.parentElement;

            if (elCommon?.closest('.fonte-nao-grifavel') || elStart?.closest('.fonte-nao-grifavel') || elEnd?.closest('.fonte-nao-grifavel')) {
                sel.removeAllRanges();
                return;
            }

            try {
                const clone = range.cloneContents();
                if (clone.querySelector && clone.querySelector('.fonte-nao-grifavel')) {
                    sel.removeAllRanges();
                    return;
                }
            } catch(e) {}

            const corFinal = cor || corGrifoAtiva || 'amarelo';
            const mark = document.createElement('mark');
            mark.className = `letrus-mark mark-${corFinal}`;
            mark.dataset.cor = corFinal;
            mark.title = 'Clique para remover ou trocar a cor deste grifo';
            mark.onclick = function(ev) {
                ev.stopPropagation();
                gerenciarGrifoClicado(this);
            };

            try {
                range.surroundContents(mark);
            } catch (err) {
                try {
                    const frag = range.extractContents();
                    mark.appendChild(frag);
                    range.insertNode(mark);
                } catch(e2) {
                    console.warn('Erro ao inserir grifo:', e2);
                }
            }

            sel.removeAllRanges();
            salvarGrifosTema();
            atualizarContadorGrifos();
        }

        function removerGrifoDaSelecao() {
            const sel = window.getSelection();
            if (!sel || sel.isCollapsed || sel.rangeCount === 0) return;

            const range = sel.getRangeAt(0);
            const container = range.commonAncestorContainer;
            const markEl = container.nodeType === 1 ? container.closest('.letrus-mark') : container.parentElement?.closest('.letrus-mark');

            if (markEl) {
                desembrulharMark(markEl);
                salvarGrifosTema();
                atualizarContadorGrifos();
            }
            sel.removeAllRanges();
        }

        function desembrulharMark(markEl) {
            const parent = markEl.parentNode;
            if (!parent) return;
            while (markEl.firstChild) {
                parent.insertBefore(markEl.firstChild, markEl);
            }
            parent.removeChild(markEl);
        }

        function gerenciarGrifoClicado(markEl) {
            Swal.fire({
                title: 'Destaque de Texto',
                text: 'O que deseja fazer com este trecho grifado?',
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-trash3-fill me-1"></i> Remover Grifo',
                confirmButtonColor: '#e11d48',
                cancelButtonText: 'Cancelar',
                cancelButtonColor: '#64748b',
                showDenyButton: true,
                denyButtonText: '<i class="bi bi-palette-fill me-1"></i> Trocar Cor',
                denyButtonColor: '#4f46e5'
            }).then((res) => {
                if (res.isConfirmed) {
                    desembrulharMark(markEl);
                    salvarGrifosTema();
                    atualizarContadorGrifos();
                } else if (res.isDenied) {
                    const cores = ['amarelo', 'verde', 'azul', 'rosa'];
                    const cur = markEl.dataset.cor || 'amarelo';
                    const nextCor = cores[(cores.indexOf(cur) + 1) % cores.length];
                    markEl.className = `letrus-mark mark-${nextCor}`;
                    markEl.dataset.cor = nextCor;
                    salvarGrifosTema();
                    atualizarContadorGrifos();
                }
            });
        }

        function limparTodosGrifos() {
            const temaId = document.getElementById('temaSelect')?.value;
            if (!temaId || temaId === 'custom') return;
            const tema = TODOS_TEMAS.find(t => parseInt(t.id) === parseInt(temaId));
            if (!tema) return;

            Swal.fire({
                title: 'Limpar todos os grifos?',
                text: 'Você tem certeza que deseja remover todos os destaques salvos deste tema?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sim, limpar todos',
                cancelButtonText: 'Cancelar'
            }).then((res) => {
                if (res.isConfirmed) {
                    try {
                        localStorage.removeItem('letrus_grifos_html_' + temaId);
                    } catch (e) {
                        console.warn('LocalStorage error:', e);
                    }
                    const freshHtml = renderizarTextosMotivadores(tema.textos_motivadores);
                    const c1 = document.getElementById('letrusTextosEtapa1');
                    const c2 = document.getElementById('letrusTextosSplit');
                    if (c1) c1.innerHTML = freshHtml;
                    if (c2) c2.innerHTML = freshHtml;
                    setCorGrifoAtiva(corGrifoAtiva);
                    atualizarContadorGrifos();
                }
            });
        }

        function salvarGrifosTema() {
            const temaId = document.getElementById('temaSelect')?.value;
            if (!temaId || temaId === 'custom') return;

            // Container ativo de referência
            const refContainer = (etapaAtual === 'leitura') ? document.getElementById('letrusTextosEtapa1') : document.getElementById('letrusTextosSplit');
            if (refContainer) {
                try {
                    localStorage.setItem('letrus_grifos_html_' + temaId, refContainer.innerHTML);
                } catch (e) {
                    console.warn('LocalStorage error:', e);
                }
            }
        }

        function sincronizarTextosEntreContainers() {
            const c1 = document.getElementById('letrusTextosEtapa1');
            const c2 = document.getElementById('letrusTextosSplit');
            if (!c1 || !c2) return;

            if (etapaAtual === 'leitura') {
                c2.innerHTML = c1.innerHTML;
                reatacharEventosMarks(c2);
            } else {
                c1.innerHTML = c2.innerHTML;
                reatacharEventosMarks(c1);
            }
        }

        function reatacharEventosMarks(container) {
            if (!container) return;
            container.querySelectorAll('.letrus-mark').forEach(m => {
                m.onclick = function(e) {
                    e.stopPropagation();
                    gerenciarGrifoClicado(this);
                };
            });
        }

        function atualizarContadorGrifos() {
            const containerAtivo = (etapaAtual === 'leitura') ? document.getElementById('letrusTextosEtapa1') : document.getElementById('letrusTextosSplit');
            const total = containerAtivo ? containerAtivo.querySelectorAll('.letrus-mark').length : 0;

            const txtGeral = document.getElementById('txtCountGrifosGeral');
            const badgeEtapa1 = document.getElementById('badgeContadorGrifosEtapa1');
            const splitMini = document.getElementById('splitCountGrifosMini');

            const label = `${total} trecho${total === 1 ? '' : 's'} destacado${total === 1 ? '' : 's'}`;
            if (txtGeral) txtGeral.textContent = label;
            if (badgeEtapa1) badgeEtapa1.textContent = `${total} grifo${total === 1 ? '' : 's'}`;
            if (splitMini) splitMini.textContent = `${total} grifo${total === 1 ? '' : 's'} ativo${total === 1 ? '' : 's'}`;
        }

        // Grifo Automático ao Selecionar Texto (Mouseup / Touchend)
        let isInteragindoComMark = false;

        document.addEventListener('mousedown', (e) => {
            isInteragindoComMark = !!e.target.closest('.letrus-mark');
        });

        function monitorarSelecaoAutomatica(e) {
            if (isInteragindoComMark) {
                isInteragindoComMark = false;
                return;
            }

            const sel = window.getSelection();
            if (!sel || sel.isCollapsed || sel.rangeCount === 0) return;

            const range = sel.getRangeAt(0);
            const c1 = document.getElementById('letrusTextosEtapa1');
            const c2 = document.getElementById('letrusTextosSplit');

            const inC1 = c1 && c1.contains(range.commonAncestorContainer);
            const inC2 = c2 && c2.contains(range.commonAncestorContainer);

            // Só atua se a seleção ocorreu dentro de um dos containers de textos motivadores
            if (!inC1 && !inC2) return;

            const selectedText = sel.toString().trim();
            if (selectedText.length < 2) return;

            // Bloquear se tocar ou estiver dentro de fonte!
            const common = range.commonAncestorContainer;
            const start = range.startContainer;
            const end = range.endContainer;
            const elCommon = (common.nodeType === 1) ? common : common.parentElement;
            const elStart = (start.nodeType === 1) ? start : start.parentElement;
            const elEnd = (end.nodeType === 1) ? end : end.parentElement;

            if (elCommon?.closest('.fonte-nao-grifavel') || elStart?.closest('.fonte-nao-grifavel') || elEnd?.closest('.fonte-nao-grifavel')) {
                sel.removeAllRanges();
                return;
            }

            try {
                const clone = range.cloneContents();
                if (clone.querySelector && clone.querySelector('.fonte-nao-grifavel')) {
                    sel.removeAllRanges();
                    return;
                }
            } catch(err) {}

            // Aplica o grifo automaticamente sem precisar clicar em nenhum botão extra!
            aplicarGrifoNaSelecao(corGrifoAtiva || 'amarelo');
        }

        document.addEventListener('mouseup', monitorarSelecaoAutomatica);
        document.addEventListener('touchend', monitorarSelecaoAutomatica);

        // =========================================================================
        // CARREGAMENTO DO TEMA & SINCRONIZAÇÃO GERAL
        // =========================================================================
        function aoMudarTema() {
            const select = document.getElementById('temaSelect');
            if (!select) return;

            const val = select.value;
            const divCustom = document.getElementById('divTemaCustom');
            const formTemaId = document.getElementById('formInputTemaId');
            const formTemaCustom = document.getElementById('formInputTemaCustom');
            const formBanca = document.getElementById('formInputBanca');
            const badgeBancaHeader = document.getElementById('badgeBancaHeader');

            // Elementos Etapa 1
            const badgeBanca1 = document.getElementById('badgeEtapa1Banca');
            const badgeEixo1 = document.getElementById('badgeEtapa1Eixo');
            const titTema1 = document.getElementById('tituloTemaEtapa1');
            const descTema1 = document.getElementById('descTemaEtapa1');
            const orientacoes1 = document.getElementById('boxOrientacoesEtapa1');
            const containerTextos1 = document.getElementById('letrusTextosEtapa1');
            const iframePdf1 = document.getElementById('iframePdfEtapa1');

            // Elementos Etapa 2 (Split)
            const splitTitulo = document.getElementById('splitTemaTituloMini');
            const splitOrientacoes = document.getElementById('boxOrientacoesSplit');
            const splitDesc = document.getElementById('descTemaSplit');
            const splitTextos = document.getElementById('letrusTextosSplit');
            const iframePdfSplit = document.getElementById('iframePdfSplit');
            const rotuloTituloBanca = document.getElementById('rotuloTituloBanca');

            // Elementos Barra Simulado Topo
            const titSimuladoTopo = document.getElementById('tituloSimuladoTopo');
            const badgeSimuladoBanca = document.getElementById('badgeSimuladoBanca');

            if (val === 'custom') {
                if (divCustom) divCustom.classList.remove('hidden');
                if (formTemaId) formTemaId.value = '';
                if (formBanca) formBanca.value = 'ENEM';
                if (badgeBancaHeader) badgeBancaHeader.textContent = 'PERSONALIZADO';
                if (badgeBanca1) badgeBanca1.textContent = 'PERSONALIZADO';
                if (badgeSimuladoBanca) badgeSimuladoBanca.textContent = 'TEMA PERSONALIZADO';
                if (titTema1) titTema1.textContent = 'Tema Personalizado';
                if (titSimuladoTopo) titSimuladoTopo.textContent = 'Tema Livre / Personalizado';
                if (containerTextos1) containerTextos1.innerHTML = '<p class="text-xs text-slate-400 italic">Digite sua redação sobre o tema livre informado.</p>';
                if (splitTextos) splitTextos.innerHTML = '<p class="text-xs text-slate-400 italic">Tema livre sem coletânea motivadora oficial.</p>';
            } else {
                if (divCustom) divCustom.classList.add('hidden');
                let tema = TODOS_TEMAS.find(t => parseInt(t.id) === parseInt(val));
                if (!tema && TODOS_TEMAS.length > 0) {
                    tema = TODOS_TEMAS[0];
                    if (select) select.value = tema.id;
                }

                if (tema) {
                    const bancaUpper = (tema.banca || 'ENEM').toUpperCase();
                    const isPossivel = (bancaUpper === 'POSSIVEIS');
                    const isRedigir = (bancaUpper === 'REDIGIR');
                    const isReal = !isPossivel && !isRedigir && bancaUpper !== 'SIMULADO';

                    const origemUpper = (tema.origem || '').toUpperCase();
                    let tagAplicacao = 'Prova Real';
                    if (origemUpper.includes('PPL')) {
                        tagAplicacao = 'PPL / Reaplicação';
                    } else if (origemUpper.includes('2ª') || origemUpper.includes('2A')) {
                        tagAplicacao = '2ª Aplicação';
                    } else if (origemUpper.includes('REGULAR')) {
                        tagAplicacao = 'Regular (1ª)';
                    }

                    const rotuloExibicao = isPossivel ? 'Possível Tema' : (isRedigir ? 'Redigir' : `${bancaUpper} ${tema.ano || ''} (${tagAplicacao})`);
                    const badgeTexto = isPossivel ? '💡 Possível Tema' : (isReal ? `${bancaUpper} ${tema.ano || ''} • ${tagAplicacao}` : `📚 Redigir ${tema.ano || ''}`);

                    if (formTemaId) formTemaId.value = tema.id;
                    if (formBanca) formBanca.value = isPossivel ? 'ENEM' : bancaUpper;
                    if (badgeBancaHeader) badgeBancaHeader.textContent = isPossivel ? 'POSSÍVEL TEMA' : bancaUpper;
                    if (badgeBanca1) badgeBanca1.textContent = badgeTexto;
                    if (badgeEixo1) badgeEixo1.textContent = tema.eixo_tematico || 'Geral';
                    if (badgeSimuladoBanca) badgeSimuladoBanca.textContent = badgeTexto;

                    if (titTema1) titTema1.textContent = tema.titulo;
                    if (titSimuladoTopo) titSimuladoTopo.textContent = `[${rotuloExibicao}] ${tema.titulo}`;
                    if (descTema1) descTema1.textContent = tema.descricao || '';
                    if (splitTitulo) splitTitulo.textContent = `[${rotuloExibicao}] ${tema.titulo}`;
                    if (splitDesc) splitDesc.textContent = tema.descricao || '';

                    // Orientações Oficiais da Banca
                    let orientacoesHtml = `<strong>Orientações ${isPossivel ? 'do Tema Inédito (Padrão ENEM)' : escapeHtml(bancaUpper)}:</strong> `;
                    if (tema.orientacoes_especificas) {
                        orientacoesHtml += escapeHtml(tema.orientacoes_especificas);
                    } else if (bancaUpper === 'ENEM' || isPossivel) {
                        orientacoesHtml += 'Desenvolva um texto dissertativo-argumentativo em norma padrão. Apresente proposta de intervenção social com os 5 elementos (Agente, Ação, Meio, Efeito e Detalhamento). Respeite os direitos humanos. Limite: 7 a 30 linhas.';
                    } else if (bancaUpper === 'FUVEST') {
                        orientacoesHtml += 'Texto dissertativo-argumentativo de reflexão crítica, filosófica e conceitual. Título obrigatório. Conclusão dialética e autoral. Limite: 20 a 30 linhas.';
                    } else if (bancaUpper === 'UNESP') {
                        orientacoesHtml += 'Responda claramente à questão-tema proposta com tese consistente. Título recomendado. Fechamento reflexivo sem obrigatoriedade de proposta de intervenção.';
                    } else {
                        orientacoesHtml += 'Desenvolva texto dissertativo-argumentativo fundamentado na coletânea, com tese clara e progressão lógica.';
                    }

                    if (orientacoes1) orientacoes1.innerHTML = orientacoesHtml;
                    if (splitOrientacoes) splitOrientacoes.innerHTML = orientacoesHtml;

                    // Rótulo dinâmico do campo de título
                    if (rotuloTituloBanca) {
                        if (bancaUpper === 'FUVEST') {
                            rotuloTituloBanca.textContent = '(obrigatório na FUVEST)';
                            rotuloTituloBanca.className = 'font-bold text-amber-500 lowercase';
                        } else if (bancaUpper === 'UNESP') {
                            rotuloTituloBanca.textContent = '(recomendado na UNESP)';
                            rotuloTituloBanca.className = 'font-bold text-indigo-500 lowercase';
                        } else {
                            rotuloTituloBanca.textContent = '(opcional)';
                            rotuloTituloBanca.className = 'font-normal text-slate-400 lowercase';
                        }
                    }

                    // Carregar PDF no iframe
                    const pdfUrl = 'visualizar_pdf.php?id=' + tema.id;
                    if (iframePdf1) iframePdf1.src = pdfUrl;
                    if (iframePdfSplit) iframePdfSplit.src = pdfUrl;

                    // Carregar textos motivadores com grifos salvos no LocalStorage
                    let savedHtml = null;
                    try {
                        savedHtml = localStorage.getItem('letrus_grifos_html_' + tema.id);
                    } catch (e) {
                        console.warn('LocalStorage inacessível:', e);
                    }
                    const rendered = savedHtml || renderizarTextosMotivadores(tema.textos_motivadores);

                    if (containerTextos1) {
                        containerTextos1.innerHTML = rendered;
                        reatacharEventosMarks(containerTextos1);
                    }
                    if (splitTextos) {
                        splitTextos.innerHTML = rendered;
                        reatacharEventosMarks(splitTextos);
                    }

                    atualizarContadorGrifos();
                    carregarRascunho(tema.id);
                    setCorGrifoAtiva(corGrifoAtiva || 'amarelo');
                }
            }
        }

        // Alternadores de Modo Texto vs PDF
        function setModoVisualizacaoEtapa1(modo) {
            const vTexto = document.getElementById('viewTextoEtapa1');
            const vPdf = document.getElementById('viewPdfEtapa1');
            const bTexto = document.getElementById('btnModoTextoEtapa1');
            const bPdf = document.getElementById('btnModoPdfEtapa1');

            if (modo === 'pdf') {
                vTexto.classList.add('hidden');
                vPdf.classList.remove('hidden');
                bPdf.className = "px-3 py-1 rounded-lg text-xs font-outfit font-black transition bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm flex items-center gap-1";
                bTexto.className = "px-3 py-1 rounded-lg text-xs font-outfit font-bold transition text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-300 flex items-center gap-1";
            } else {
                vPdf.classList.add('hidden');
                vTexto.classList.remove('hidden');
                bTexto.className = "px-3 py-1 rounded-lg text-xs font-outfit font-black transition bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm flex items-center gap-1";
                bPdf.className = "px-3 py-1 rounded-lg text-xs font-outfit font-bold transition text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-300 flex items-center gap-1";
            }
        }

        function setModoVisualizacaoSplit(modo) {
            const vTexto = document.getElementById('viewTextoSplit');
            const vPdf = document.getElementById('viewPdfSplit');
            const bTexto = document.getElementById('btnModoTextoSplit');
            const bPdf = document.getElementById('btnModoPdfSplit');

            if (modo === 'pdf') {
                vTexto.classList.add('hidden');
                vPdf.classList.remove('hidden');
                bPdf.className = "px-2 py-1 rounded-lg text-[11px] font-outfit font-black transition bg-indigo-600 text-white shadow-sm";
                bTexto.className = "px-2 py-1 rounded-lg text-[11px] font-outfit font-bold transition text-slate-600 dark:text-slate-300 hover:text-indigo-600";
            } else {
                vPdf.classList.add('hidden');
                vTexto.classList.remove('hidden');
                bTexto.className = "px-2 py-1 rounded-lg text-[11px] font-outfit font-black transition bg-indigo-600 text-white shadow-sm";
                bPdf.className = "px-2 py-1 rounded-lg text-[11px] font-outfit font-bold transition text-slate-600 dark:text-slate-300 hover:text-indigo-600";
            }
        }

        // =========================================================================
        // FOLHA PAUTADA OFICIAL: CONTADORES, GUTTER E SINCRONIZAÇÃO
        // =========================================================================
        function sincronizarLinhaAtiva() {
            const textarea = document.getElementById('textoRedacao');
            if (!textarea) return;

            const pos = textarea.selectionStart;
            const textUpToCursor = textarea.value.substring(0, pos);
            const lineIndex = textUpToCursor.split('\n').length;

            for (let i = 1; i <= 30; i++) {
                const sp = document.getElementById('gutter-line-' + i);
                if (sp) {
                    if (i === lineIndex) {
                        sp.classList.add('linha-ativa');
                    } else {
                        sp.classList.remove('linha-ativa');
                    }
                }
            }
        }

        function atualizarContadoresRedacao() {
            const txt = (document.getElementById('textoRedacao')?.value || '');
            const badgeLinhas = document.getElementById('badgeLinhas');
            const badgePalavras = document.getElementById('badgePalavras');
            const badgeParagrafos = document.getElementById('badgeParagrafos');
            const alerta = document.getElementById('badgeAlertaLinhas');

            const linhasQuebra = txt.split('\n');
            let totalLinhas = 0;
            linhasQuebra.forEach(l => {
                totalLinhas += Math.max(1, Math.ceil(l.length / 75));
            });
            if (txt.trim() === '') totalLinhas = 0;

            const palavras = txt.trim() === '' ? 0 : txt.trim().split(/\s+/).length;
            const paragrafos = txt.trim() === '' ? 0 : txt.split(/\n\s*\n/).filter(p => p.trim().length > 0).length;

            if (badgeLinhas) badgeLinhas.textContent = `${totalLinhas} / 30 linhas`;
            if (badgePalavras) badgePalavras.textContent = `${palavras} palavra${palavras === 1 ? '' : 's'}`;
            if (badgeParagrafos) badgeParagrafos.textContent = `${paragrafos} parágrafo${paragrafos === 1 ? '' : 's'}`;

            if (totalLinhas > 30) {
                if (alerta) {
                    alerta.textContent = '🚨 Excedeu o limite de 30 linhas';
                    alerta.classList.remove('hidden');
                }
                if (badgeLinhas) badgeLinhas.className = 'px-3 py-1 rounded-xl bg-rose-100 dark:bg-rose-950 text-rose-700 dark:text-rose-300 font-mono font-black border border-rose-300 dark:border-rose-800';
            } else if (totalLinhas > 0 && totalLinhas < 7) {
                if (alerta) {
                    alerta.textContent = '⚠️ Mínimo de 7 linhas';
                    alerta.classList.remove('hidden');
                }
                if (badgeLinhas) badgeLinhas.className = 'px-3 py-1 rounded-xl bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 font-mono font-black border border-amber-300 dark:border-amber-800';
            } else if (totalLinhas >= 7) {
                if (alerta) alerta.classList.add('hidden');
                if (badgeLinhas) badgeLinhas.className = 'px-3 py-1 rounded-xl bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 font-mono font-black border border-emerald-300 dark:border-emerald-800';
            } else {
                if (alerta) alerta.classList.add('hidden');
                if (badgeLinhas) badgeLinhas.className = 'px-3 py-1 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 font-mono font-black border border-slate-200 dark:border-slate-600';
            }
        }

        // =========================================================================
        // SALVAMENTO AUTOMÁTICO DE RASCUNHO (LOCALSTORAGE)
        // =========================================================================
        function agendarAutoSave() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(() => {
                salvarRascunho();
            }, 600);
        }

        function salvarRascunho() {
            const temaId = document.getElementById('temaSelect')?.value;
            if (!temaId) return;

            const titulo = document.getElementById('tituloRedacao')?.value || '';
            const texto = document.getElementById('textoRedacao')?.value || '';

            if (!texto && !titulo) return;

            const now = new Date();
            const hora = now.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });

            const draft = {
                titulo: titulo,
                texto: texto,
                tempoSegundos: timerSeconds,
                hora: hora
            };

            try {
                localStorage.setItem('letrus_draft_' + temaId, JSON.stringify(draft));
            } catch (e) {
                console.warn('LocalStorage error:', e);
            }
            const txtStatus = document.getElementById('txtDraftStatus');
            const txtSimulado = document.getElementById('txtSimuladoDraft');
            const statusMsg = `Salvo às ${hora}`;
            if (txtStatus) txtStatus.textContent = statusMsg;
            if (txtSimulado) txtSimulado.textContent = statusMsg;
        }

        function salvarRascunhoManual() {
            salvarRascunho();
            Swal.fire({
                icon: 'success',
                title: 'Rascunho Salvo!',
                text: 'Seu texto e tempo de prova foram salvos no navegador.',
                timer: 1500,
                showConfirmButton: false
            });
        }

        function carregarRascunho(temaId) {
            if (!temaId) return;
            let raw = null;
            try {
                raw = localStorage.getItem('letrus_draft_' + temaId);
            } catch (e) {
                console.warn('LocalStorage error:', e);
            }
            const titEl = document.getElementById('tituloRedacao');
            const txtEl = document.getElementById('textoRedacao');
            const txtStatus = document.getElementById('txtDraftStatus');
            const txtSimulado = document.getElementById('txtSimuladoDraft');

            if (!raw) {
                if (titEl) titEl.value = '';
                if (txtEl) txtEl.value = '';
                if (txtStatus) txtStatus.textContent = 'Sem rascunho salvo';
                if (txtSimulado) txtSimulado.textContent = 'Sem rascunho salvo';
                atualizarContadoresRedacao();
                sincronizarLinhaAtiva();
                return;
            }

            try {
                const draft = JSON.parse(raw);
                if (titEl && draft.titulo) titEl.value = draft.titulo;
                if (txtEl && draft.texto) txtEl.value = draft.texto;
                if (draft.tempoSegundos) {
                    timerSeconds = draft.tempoSegundos;
                    const h = String(Math.floor(timerSeconds / 3600)).padStart(2, '0');
                    const m = String(Math.floor((timerSeconds % 3600) / 60)).padStart(2, '0');
                    const s = String(timerSeconds % 60).padStart(2, '0');
                    const timeStr = `${h}:${m}:${s}`;
                    const disp = document.getElementById('displayTimer');
                    const dispSimulado = document.getElementById('displayTimerSimulado');
                    if (disp) disp.textContent = timeStr;
                    if (dispSimulado) dispSimulado.textContent = timeStr;
                }
                if (draft.hora) {
                    const statusMsg = `Salvo às ${draft.hora}`;
                    if (txtStatus) txtStatus.textContent = statusMsg;
                    if (txtSimulado) txtSimulado.textContent = statusMsg;
                }
                atualizarContadoresRedacao();
                sincronizarLinhaAtiva();
            } catch (err) {
                console.error('Erro ao ler rascunho:', err);
            }
        }

        function limparRascunho() {
            const temaId = document.getElementById('temaSelect')?.value;
            Swal.fire({
                title: 'Limpar Folha de Redação?',
                text: 'Seu rascunho digitado será apagado. Esta ação não pode ser desfeita.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sim, limpar folha',
                cancelButtonText: 'Cancelar'
            }).then((res) => {
                if (res.isConfirmed) {
                    if (temaId) {
                        try {
                            localStorage.removeItem('letrus_draft_' + temaId);
                        } catch (e) {
                            console.warn('LocalStorage error:', e);
                        }
                    }
                    const titEl = document.getElementById('tituloRedacao');
                    const txtEl = document.getElementById('textoRedacao');
                    if (titEl) titEl.value = '';
                    if (txtEl) txtEl.value = '';
                    atualizarContadoresRedacao();
                    sincronizarLinhaAtiva();
                    const txtStatus = document.getElementById('txtDraftStatus');
                    const txtSimulado = document.getElementById('txtSimuladoDraft');
                    if (txtStatus) txtStatus.textContent = 'Folha limpa';
                    if (txtSimulado) txtSimulado.textContent = 'Folha limpa';
                }
            });
        }

        // =========================================================================
        // MODO DE ENVIO: DIGITADO vs FOTO/PDF & CRONÔMETRO
        // =========================================================================
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

        // =========================================================================
        // ENVIO DO FORMULÁRIO COM IA (GEMINI)
        // =========================================================================
        function enviarRedacao(e) {
            e.preventDefault();

            const form = document.getElementById('formRedacao');
            const formData = new FormData(form);
            const btn = document.getElementById('btnSubmitRedacao');
            const tipoEnvio = formData.get('tipo_envio');
            const banca = document.getElementById('formInputBanca')?.value || 'ENEM';
            const titulo = (document.getElementById('tituloRedacao')?.value || '').trim();

            if (tipoEnvio === 'texto') {
                let txt = (formData.get('texto_redacao') || '').trim();
                if (txt.length < 150) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Redação muito curta',
                        text: 'Digite pelo menos 150 caracteres para uma avaliação pedagógica válida.',
                        confirmButtonColor: '#4f46e5'
                    });
                    return;
                }

                // Se houver título digitado e não estiver no corpo, prepende formalmente
                if (titulo && !txt.startsWith(titulo)) {
                    txt = `[TÍTULO: ${titulo}]\n\n` + txt;
                    formData.set('texto_redacao', txt);
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

            // Desativar alerta anti-distração e pausar cronômetro durante a avaliação
            isRedacaoAtiva = false;
            pausarCronometro();

            // Atrelar valores do tema
            formData.set('tema_id', document.getElementById('temaSelect')?.value || '');
            formData.set('tema_custom', document.getElementById('temaCustom')?.value || '');
            formData.set('banca', banca);

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
                    // Limpar rascunho após sucesso
                    const temaId = document.getElementById('temaSelect')?.value;
                    if (temaId) localStorage.removeItem('letrus_draft_' + temaId);

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

        // =========================================================================
        // CONFIGURAÇÃO DA CHAVE GEMINI & MODAIS
        // =========================================================================
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

        // =========================================================================
        // INICIALIZAÇÃO
        // =========================================================================
        document.addEventListener('DOMContentLoaded', () => {
            // Sincronizar scroll do gutter com o textarea
            const textarea = document.getElementById('textoRedacao');
            const gutter = document.getElementById('gutterLinhas');
            if (textarea && gutter) {
                textarea.addEventListener('scroll', () => {
                    gutter.scrollTop = textarea.scrollTop;
                });
            }

            const urlParams = new URLSearchParams(window.location.search);
            const temaParam = urlParams.get('tema_id');
            if (temaParam) {
                iniciarEscritaComTema(temaParam);
            } else {
                aoMudarTema();
            }
            try {
                const savedCor = localStorage.getItem('letrus_cor_grifo_ativa');
                if (savedCor) corGrifoAtiva = savedCor;
            } catch(e) {}
            setCorGrifoAtiva(corGrifoAtiva || 'amarelo');
            atualizarContadoresRedacao();
            aplicarFiltrosTemas();
        });
    </script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
