<?php
require_once __DIR__ . '/config/db.php';
checkAuth();

$userId = $_SESSION['user_id'];
$redacaoId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$redacaoId) {
    header("Location: redacao.php");
    exit;
}

// Buscar a redação no banco
$stmt = $pdo->prepare("SELECT * FROM redacoes WHERE id = ? AND user_id = ?");
$stmt->execute([$redacaoId, $userId]);
$redacao = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$redacao) {
    header("Location: redacao.php");
    exit;
}

$relatorio = json_decode($redacao['relatorio_json'], true) ?: [];

// Competências e notas
$c1 = (int)($redacao['nota_c1'] ?? ($relatorio['notas_competencias']['c1'] ?? 0));
$c2 = (int)($redacao['nota_c2'] ?? ($relatorio['notas_competencias']['c2'] ?? 0));
$c3 = (int)($redacao['nota_c3'] ?? ($relatorio['notas_competencias']['c3'] ?? 0));
$c4 = (int)($redacao['nota_c4'] ?? ($relatorio['notas_competencias']['c4'] ?? 0));
$c5 = (int)($redacao['nota_c5'] ?? ($relatorio['notas_competencias']['c5'] ?? 0));
$notaFinal = (int)($redacao['nota_final'] ?? ($relatorio['nota_final'] ?? ($c1 + $c2 + $c3 + $c4 + $c5)));

// Detector de IA
$probIa = (int)($redacao['probabilidade_ia'] ?? ($relatorio['autenticidade']['probabilidade_ia'] ?? 0));
$justIa = $redacao['justificativa_ia'] ?? ($relatorio['autenticidade']['justificativa_ia'] ?? 'Nenhum indício anômalo de inteligência artificial detectado.');

if ($probIa <= 25) {
    $iaBadge = ['label' => 'Autoria Humana Autêntica', 'cor' => 'emerald', 'icon' => 'bi-shield-check', 'desc' => 'Texto natural com voz autoral genuína do estudante.'];
} elseif ($probIa <= 65) {
    $iaBadge = ['label' => 'Indícios Moderados de IA', 'cor' => 'amber', 'icon' => 'bi-exclamation-triangle', 'desc' => 'Estrutura sintática com possíveis trechos gerados por IA ou modelos prontos.'];
} else {
    $iaBadge = ['label' => 'Alta Probabilidade de IA', 'cor' => 'rose', 'icon' => 'bi-robot', 'desc' => 'Padrões mecânicos e simetria artificial característicos de IA generativa.'];
}

$banca = strtoupper($redacao['banca'] ?? 'ENEM');

// Nomes e descrições das Competências adaptados por Banca
if ($banca === 'UNESP') {
    $cTitles = [
        'c1' => ['titulo' => 'Critério 1', 'sub' => 'Norma Padrão & Precisão Vocabular (VUNESP)', 'icon' => 'bi-spellcheck'],
        'c2' => ['titulo' => 'Critério 2', 'sub' => 'Resposta à Questão-Tema & Leitura da Coletânea', 'icon' => 'bi-book-half'],
        'c3' => ['titulo' => 'Critério 3', 'sub' => 'Consistência Argumentativa & Projeto de Texto', 'icon' => 'bi-diagram-3-fill'],
        'c4' => ['titulo' => 'Critério 4', 'sub' => 'Coesão Textual & Recursos Articuladores', 'icon' => 'bi-link-45deg'],
        'c5' => ['titulo' => 'Critério 5', 'sub' => 'Fechamento da Tese & Conclusão Reflexiva', 'icon' => 'bi-lightbulb-fill']
    ];
} elseif ($banca === 'FUVEST') {
    $cTitles = [
        'c1' => ['titulo' => 'Critério 1', 'sub' => 'Expressão Linguística & Modalidade Culta (FUVEST)', 'icon' => 'bi-spellcheck'],
        'c2' => ['titulo' => 'Critério 2', 'sub' => 'Desenvolvimento Temático & Densidade Filosófica', 'icon' => 'bi-book-half'],
        'c3' => ['titulo' => 'Critério 3', 'sub' => 'Estrutura, Coerência dos Argumentos & Autonomia', 'icon' => 'bi-diagram-3-fill'],
        'c4' => ['titulo' => 'Critério 4', 'sub' => 'Coesão, Fluidez & Articulação Lógica', 'icon' => 'bi-link-45deg'],
        'c5' => ['titulo' => 'Critério 5', 'sub' => 'Título Autoral & Síntese Crítica da Tese', 'icon' => 'bi-lightbulb-fill']
    ];
} elseif ($banca === 'UNICAMP') {
    $cTitles = [
        'c1' => ['titulo' => 'Critério 1', 'sub' => 'Adequação da Linguagem & Clareza (UNICAMP)', 'icon' => 'bi-spellcheck'],
        'c2' => ['titulo' => 'Critério 2', 'sub' => 'Atendimento à Proposta & Leitura Crítica', 'icon' => 'bi-book-half'],
        'c3' => ['titulo' => 'Critério 3', 'sub' => 'Densidade Argumentativa & Interlocução Autoral', 'icon' => 'bi-diagram-3-fill'],
        'c4' => ['titulo' => 'Critério 4', 'sub' => 'Articulação Textual & Recursos Coesivos', 'icon' => 'bi-link-45deg'],
        'c5' => ['titulo' => 'Critério 5', 'sub' => 'Conclusão Consistente do Ponto de Vista', 'icon' => 'bi-lightbulb-fill']
    ];
} else {
    $cTitles = [
        'c1' => ['titulo' => 'Competência 1', 'sub' => 'Domínio da Norma Padrão da Língua Escrita (ENEM)', 'icon' => 'bi-spellcheck'],
        'c2' => ['titulo' => 'Competência 2', 'sub' => 'Compreensão da Proposta & Repertório Sociocultural', 'icon' => 'bi-book-half'],
        'c3' => ['titulo' => 'Competência 3', 'sub' => 'Seleção, Relação e Organização dos Argumentos', 'icon' => 'bi-diagram-3-fill'],
        'c4' => ['titulo' => 'Competência 4', 'sub' => 'Coesão & Recursos Coesivos Inter e Intraparágrafos', 'icon' => 'bi-link-45deg'],
        'c5' => ['titulo' => 'Competência 5', 'sub' => 'Proposta de Intervenção (Agente, Ação, Meio, Efeito e Detalhe)', 'icon' => 'bi-lightbulb-fill']
    ];
}

$competenciasInfo = [
    'c1' => [
        'titulo' => $cTitles['c1']['titulo'],
        'sub' => $cTitles['c1']['sub'],
        'nota' => $c1,
        'analise' => $relatorio['analise_por_competencia']['c1'] ?? 'Sem análise detalhada.',
        'icon' => $cTitles['c1']['icon']
    ],
    'c2' => [
        'titulo' => $cTitles['c2']['titulo'],
        'sub' => $cTitles['c2']['sub'],
        'nota' => $c2,
        'analise' => $relatorio['analise_por_competencia']['c2'] ?? 'Sem análise detalhada.',
        'icon' => $cTitles['c2']['icon']
    ],
    'c3' => [
        'titulo' => $cTitles['c3']['titulo'],
        'sub' => $cTitles['c3']['sub'],
        'nota' => $c3,
        'analise' => $relatorio['analise_por_competencia']['c3'] ?? 'Sem análise detalhada.',
        'icon' => $cTitles['c3']['icon']
    ],
    'c4' => [
        'titulo' => $cTitles['c4']['titulo'],
        'sub' => $cTitles['c4']['sub'],
        'nota' => $c4,
        'analise' => $relatorio['analise_por_competencia']['c4'] ?? 'Sem análise detalhada.',
        'icon' => $cTitles['c4']['icon']
    ],
    'c5' => [
        'titulo' => $cTitles['c5']['titulo'],
        'sub' => $cTitles['c5']['sub'],
        'nota' => $c5,
        'analise' => $relatorio['analise_por_competencia']['c5'] ?? 'Sem análise detalhada.',
        'icon' => $cTitles['c5']['icon']
    ],
];

// Preparar roteiro de áudio para Text-to-Speech do navegador
$roteiroAudio = "Olá! Aqui é o Corretor de Redações do HipoGabarito. ";
$roteiroAudio .= "Sua redação para a banca " . $banca . " sobre o tema " . addslashes($redacao['titulo']) . " obteve a nota final de " . $notaFinal . " pontos. ";
$roteiroAudio .= "No critério 1 de Gramática e Norma Culta, você tirou $c1 pontos. ";
$roteiroAudio .= "No critério 2 de Abordagem do Tema e Repertório, você tirou $c2 pontos. ";
$roteiroAudio .= "No critério 3 de Argumentação e Projeto de Texto, você tirou $c3 pontos. ";
$roteiroAudio .= "No critério 4 de Coesão, você tirou $c4 pontos. ";
$roteiroAudio .= "E no critério 5 de Conclusão, você tirou $c5 pontos. ";
if (!empty($relatorio['relatorio_melhorias'])) {
    $roteiroAudio .= "Dica de ouro para evoluir: " . addslashes(implode('. ', array_slice($relatorio['relatorio_melhorias'], 0, 2))) . ". ";
}
$roteiroAudio .= "Parabéns pela dedicação e continue praticando rumo à nota mil!";
$roteiroAudio = preg_replace("/\r|\n/", " ", $roteiroAudio);

$pageTitle = "Resultado da Redação ({$notaFinal} pts) — HipoGabarito";
require_once __DIR__ . '/includes/header.php';
?>

    <style>
        @media print {
            header, aside, footer, .no-print { display: none !important; }
            main { padding: 0 !important; margin: 0 !important; }
            .lg\:col-span-9 { width: 100% !important; }
            body { background: white !important; color: black !important; }
        }
        .pulso-audio {
            animation: pulse-ring 1.5s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.05); opacity: 1; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }
    </style>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-all">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- SIDEBAR MODULAR -->
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

            <!-- CONTEÚDO PRINCIPAL DO RELATÓRIO -->
            <section class="lg:col-span-9 space-y-6">

                <!-- BARRA DE AÇÕES SUPERIOR -->
                <div class="flex flex-wrap items-center justify-between gap-3 no-print">
                    <a href="redacao.php" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 font-outfit font-bold text-xs hover:bg-slate-300 dark:hover:bg-slate-600 transition-all">
                        <i class="bi bi-arrow-left"></i>
                        <span>Voltar ao Corretor</span>
                    </a>

                    <div class="flex items-center gap-2">
                        <!-- BOTÃO OUVIR FEEDBACK POR ÁUDIO -->
                        <button type="button" id="btnPlayAudio" onclick="toggleAudioFeedback()" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-400 hover:bg-amber-500 text-slate-900 font-outfit font-black text-xs shadow-sm transition-all">
                            <i class="bi bi-volume-up-fill text-sm" id="iconAudio"></i>
                            <span id="txtAudio">Ouvir Correção por Áudio</span>
                        </button>

                        <!-- BOTÃO IMPRIMIR / PDF -->
                        <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-outfit font-bold text-xs shadow-sm transition-all">
                            <i class="bi bi-printer-fill text-sm"></i>
                            <span>Salvar em PDF</span>
                        </button>
                    </div>
                </div>

                <!-- CARD PRINCIPAL: NOTA GIGANTE E TEMA -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl border-2 border-slate-200 dark:border-slate-700 p-6 sm:p-8 shadow-[0_4px_0_0_#e2e8f0] dark:shadow-none space-y-6">
                    
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 pb-6 border-b border-slate-100 dark:border-slate-700">
                        <div class="space-y-1.5 flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 uppercase tracking-wide">
                                    Correção Oficial <?= htmlspecialchars($banca) ?>
                                </span>
                                <span class="text-xs text-slate-400 font-bold">
                                    <i class="bi bi-calendar3 me-1"></i><?= date('d/m/Y \à\s H:i', strtotime($redacao['created_at'])) ?>
                                </span>
                                <?php if ($redacao['xp_ganho'] > 0): ?>
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300">
                                        +<?= $redacao['xp_ganho'] ?> XP Conquistados!
                                    </span>
                                <?php endif; ?>
                            </div>

                            <h1 class="font-outfit font-black text-xl sm:text-2xl text-slate-900 dark:text-white leading-tight">
                                <?= htmlspecialchars($redacao['titulo']) ?>
                            </h1>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-0">
                                Tipo de envio: <strong><?= $redacao['tipo_envio'] === 'arquivo' ? 'Foto da Folha / Manuscrito com OCR' : 'Digitada diretamente na plataforma' ?></strong>
                            </p>
                        </div>

                        <!-- PLACAR GIGANTE DA NOTA FINAL -->
                        <div class="bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 text-white rounded-3xl p-5 sm:p-6 text-center shadow-lg border-2 border-indigo-400/30 shrink-0 w-full md:w-56">
                            <span class="block text-xs font-black uppercase tracking-widest text-indigo-200 mb-1">Nota Final</span>
                            <div class="font-outfit font-black text-5xl sm:text-6xl tracking-tight leading-none text-amber-300 mb-1">
                                <?= $notaFinal ?>
                            </div>
                            <span class="text-xs font-bold text-indigo-200">de 1000 pontos</span>
                        </div>
                    </div>

                    <!-- CARD DE DETECÇÃO DE IA & AUTENTICIDADE -->
                    <div class="rounded-2xl p-4 sm:p-5 border-2 flex flex-col sm:flex-row items-start sm:items-center gap-4 <?= $iaBadge['cor'] === 'emerald' ? 'bg-emerald-50/70 border-emerald-300 dark:bg-emerald-950/30 dark:border-emerald-800' : ($iaBadge['cor'] === 'amber' ? 'bg-amber-50/70 border-amber-300 dark:bg-amber-950/30 dark:border-amber-800' : 'bg-rose-50/70 border-rose-300 dark:bg-rose-950/30 dark:border-rose-800') ?>">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 <?= $iaBadge['cor'] === 'emerald' ? 'bg-emerald-200 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200' : ($iaBadge['cor'] === 'amber' ? 'bg-amber-200 text-amber-800 dark:bg-amber-900 dark:text-amber-200' : 'bg-rose-200 text-rose-800 dark:bg-rose-900 dark:text-rose-200') ?>">
                            <i class="bi <?= $iaBadge['icon'] ?> text-2xl"></i>
                        </div>
                        <div class="space-y-0.5 flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <h4 class="font-outfit font-black text-sm text-slate-900 dark:text-white mb-0">
                                    Detector de Autenticidade: <?= $iaBadge['label'] ?> (<?= $probIa ?>% probabilidade de IA)
                                </h4>
                            </div>
                            <p class="text-xs text-slate-600 dark:text-slate-300 mb-0 leading-relaxed">
                                <?= htmlspecialchars($justIa) ?>
                            </p>
                        </div>
                    </div>

                    <!-- GRID DAS 5 COMPETÊNCIAS DO ENEM -->
                    <div>
                        <h3 class="font-outfit font-black text-lg text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                            <i class="bi bi-bar-chart-fill text-indigo-600 dark:text-indigo-400"></i>
                            <span>Desempenho por Competência ENEM (0 a 200 pontos cada)</span>
                        </h3>

                        <div class="space-y-4">
                            <?php foreach ($competenciasInfo as $chave => $comp): ?>
                                <?php
                                $pct = ($comp['nota'] / 200) * 100;
                                $corBarra = $comp['nota'] >= 160 ? 'bg-emerald-500' : ($comp['nota'] >= 120 ? 'bg-indigo-500' : ($comp['nota'] >= 80 ? 'bg-amber-500' : 'bg-rose-500'));
                                ?>
                                <div class="bg-slate-50 dark:bg-slate-900/60 rounded-2xl border border-slate-200 dark:border-slate-700/80 p-5 space-y-3">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 flex items-center justify-center font-black text-xs">
                                                <?= strtoupper($chave) ?>
                                            </div>
                                            <div>
                                                <h4 class="font-outfit font-extrabold text-sm text-slate-900 dark:text-white mb-0">
                                                    <?= $comp['titulo'] ?> — <?= $comp['sub'] ?>
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="px-3 py-1 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 font-outfit font-black text-sm text-indigo-600 dark:text-indigo-400">
                                            <?= $comp['nota'] ?> <span class="text-[11px] font-bold text-slate-400">/ 200</span>
                                        </div>
                                    </div>

                                    <!-- BARRA DE PROGRESSO -->
                                    <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2.5 overflow-hidden">
                                        <div class="<?= $corBarra ?> h-2.5 rounded-full transition-all duration-700" style="width: <?= $pct ?>%"></div>
                                    </div>

                                    <!-- PARECER DA BANCA -->
                                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed mb-0 bg-white/70 dark:bg-slate-800/60 p-3.5 rounded-xl border border-slate-100 dark:border-slate-700/50">
                                        <?= nl2br(htmlspecialchars($comp['analise'])) ?>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- PONTOS FORTES E OPORTUNIDADES DE MELHORIA -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- PONTOS FORTES -->
                        <div class="bg-emerald-50/50 dark:bg-emerald-950/20 border-2 border-emerald-200 dark:border-emerald-900/40 rounded-2xl p-5 space-y-3">
                            <h4 class="font-outfit font-extrabold text-sm text-emerald-800 dark:text-emerald-300 flex items-center gap-2 mb-0">
                                <i class="bi bi-star-fill text-amber-500"></i>
                                <span>Pontos Fortes da Sua Redação</span>
                            </h4>
                            <ul class="text-xs text-slate-700 dark:text-slate-300 space-y-2 mb-0 ps-4 list-disc">
                                <?php if (!empty($relatorio['pontos_fortes'])): ?>
                                    <?php foreach ($relatorio['pontos_fortes'] as $pf): ?>
                                        <li><?= htmlspecialchars($pf) ?></li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li>Boa estruturação dissertativa e respeito à proposta do tema.</li>
                                    <li>Proposta de intervenção direcionada ao problema central.</li>
                                <?php endif; ?>
                            </ul>
                        </div>

                        <!-- MELHORIAS PRÁTICAS -->
                        <div class="bg-indigo-50/50 dark:bg-indigo-950/20 border-2 border-indigo-200 dark:border-indigo-900/40 rounded-2xl p-5 space-y-3">
                            <h4 class="font-outfit font-extrabold text-sm text-indigo-800 dark:text-indigo-300 flex items-center gap-2 mb-0">
                                <i class="bi bi-arrow-up-circle-fill text-indigo-600 dark:text-indigo-400"></i>
                                <span>Ações Práticas para Subir a Nota</span>
                            </h4>
                            <ul class="text-xs text-slate-700 dark:text-slate-300 space-y-2 mb-0 ps-4 list-disc">
                                <?php if (!empty($relatorio['relatorio_melhorias'])): ?>
                                    <?php foreach ($relatorio['relatorio_melhorias'] as $melh): ?>
                                        <li><?= htmlspecialchars($melh) ?></li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li>Inclua repertório sociocultural legitimado e produtivo de filósofos ou sociólogos.</li>
                                    <li>Garanta os 5 elementos na proposta de intervenção da C5.</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- DICAS DE ESTUDO RECOMENDADAS PELA IA -->
                    <?php if (!empty($relatorio['materiais_estudo'])): ?>
                    <div class="bg-amber-50/60 dark:bg-amber-950/20 border-2 border-amber-200 dark:border-amber-900/40 rounded-2xl p-5 space-y-2">
                        <h4 class="font-outfit font-extrabold text-sm text-amber-900 dark:text-amber-300 flex items-center gap-2 mb-0">
                            <i class="bi bi-lightbulb-fill text-amber-600"></i>
                            <span>Recomendações de Estudo Personalizadas:</span>
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1">
                            <?php foreach ($relatorio['materiais_estudo'] as $mat): ?>
                                <div class="bg-white/80 dark:bg-slate-800/80 p-2.5 rounded-xl border border-amber-200/60 dark:border-amber-900/30 text-xs text-slate-700 dark:text-slate-300 flex items-center gap-2">
                                    <i class="bi bi-bookmark-check-fill text-indigo-600 dark:text-indigo-400 shrink-0"></i>
                                    <span><?= htmlspecialchars($mat) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- TEXTO DA REDAÇÃO LIDO / TRANSCRIÇÃO OCR -->
                    <div class="space-y-3 pt-2">
                        <div class="flex items-center justify-between">
                            <h4 class="font-outfit font-extrabold text-sm text-slate-900 dark:text-white mb-0 flex items-center gap-2">
                                <i class="bi bi-file-earmark-text-fill text-indigo-600"></i>
                                <span>Texto da Redação <?= $redacao['tipo_envio'] === 'arquivo' ? '(Transcrição por OCR)' : '(Original)' ?></span>
                            </h4>
                            <button type="button" onclick="copiarTextoRedacao()" class="text-xs text-indigo-600 dark:text-indigo-400 font-bold hover:underline flex items-center gap-1">
                                <i class="bi bi-clipboard"></i> Copiar Texto
                            </button>
                        </div>

                        <div id="boxTextoRedacao" class="bg-slate-50 dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-700 rounded-2xl p-5 text-sm text-slate-800 dark:text-slate-200 leading-relaxed font-sans whitespace-pre-line max-h-96 overflow-y-auto">
                            <?= htmlspecialchars($redacao['texto_redacao']) ?>
                        </div>
                    </div>

                    <!-- BOTÃO PARA NOVA REDAÇÃO -->
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-700 flex flex-col sm:flex-row items-center justify-between gap-4 no-print">
                        <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                            Pratique redações semanalmente para garantir sua aprovação no vestibular!
                        </span>
                        <a href="redacao.php" class="px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-outfit font-extrabold text-sm shadow-[0_4px_0_0_#3730a3] active:translate-y-1 active:shadow-none transition-all flex items-center gap-2">
                            <i class="bi bi-pencil-fill"></i>
                            <span>Treinar Outra Redação</span>
                        </a>
                    </div>

                </div>

            </section>
        </div>
    </main>

    <!-- SCRIPT DE SÍNTESE DE VOZ & UTILITÁRIOS -->
    <script>
        const textoAudioFeedback = "<?= $roteiroAudio ?>";
        let isAudioTocando = false;
        let audioUtterance = null;

        function toggleAudioFeedback() {
            if (!('speechSynthesis' in window)) {
                Swal.fire({
                    icon: 'info',
                    title: 'Recurso de Áudio',
                    text: 'Seu navegador não suporta a síntese de voz nativa.',
                    confirmButtonColor: '#4f46e5'
                });
                return;
            }

            const btn = document.getElementById('btnPlayAudio');
            const icon = document.getElementById('iconAudio');
            const txt = document.getElementById('txtAudio');

            if (isAudioTocando) {
                window.speechSynthesis.cancel();
                isAudioTocando = false;
                btn.classList.remove('pulso-audio');
                icon.className = 'bi bi-volume-up-fill text-sm';
                txt.textContent = 'Ouvir Correção por Áudio';
            } else {
                window.speechSynthesis.cancel(); // Para qualquer fala anterior
                audioUtterance = new SpeechSynthesisUtterance(textoAudioFeedback);
                audioUtterance.lang = 'pt-BR';
                audioUtterance.rate = 1.05; // Velocidade natural e dinâmica

                audioUtterance.onend = function() {
                    isAudioTocando = false;
                    btn.classList.remove('pulso-audio');
                    icon.className = 'bi bi-volume-up-fill text-sm';
                    txt.textContent = 'Ouvir Correção por Áudio';
                };

                audioUtterance.onerror = function() {
                    isAudioTocando = false;
                    btn.classList.remove('pulso-audio');
                    icon.className = 'bi bi-volume-up-fill text-sm';
                    txt.textContent = 'Ouvir Correção por Áudio';
                };

                window.speechSynthesis.speak(audioUtterance);
                isAudioTocando = true;
                btn.classList.add('pulso-audio');
                icon.className = 'bi bi-stop-circle-fill text-sm';
                txt.textContent = 'Pausar Áudio';
            }
        }

        function copiarTextoRedacao() {
            const texto = document.getElementById('boxTextoRedacao').innerText;
            navigator.clipboard.writeText(texto).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Copiado!',
                    text: 'O texto da redação foi copiado para sua área de transferência.',
                    timer: 1500,
                    showConfirmButton: false
                });
            });
        }
    </script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
