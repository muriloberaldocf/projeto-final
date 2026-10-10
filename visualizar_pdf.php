<?php
/**
 * VISUALIZADOR & DOWNLOAD DE PROPOSTA OFICIAL — HIPOGABARITO / VESTILINGO
 * - Se for PDF binário real (%PDF), faz o stream ou download nativo com Content-Type: application/pdf.
 * - Se for imagem de proposta (PNG/JPG da Redigir), exibe em alta definição no leitor ou baixa diretamente.
 * - Se for tema de vestibular/ENEM sem anexo gráfico, renderiza a Folha Oficial A4 diagramada no padrão oficial.
 */
require_once __DIR__ . '/config/db.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$forceDownload = isset($_GET['download']) && $_GET['download'] == 1;

if (!$id) {
    http_response_code(400);
    die('ID de proposta inválido.');
}

$stmt = $pdo->prepare("SELECT * FROM redacao_temas WHERE id = ?");
$stmt->execute([$id]);
$tema = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tema) {
    http_response_code(404);
    die('Proposta de redação não encontrada.');
}

$banca = strtoupper($tema['banca'] ?? 'ENEM');
$ano = $tema['ano'] ?? date('Y');
$titulo = $tema['titulo'] ?? 'Proposta de Redação';
$slugTitulo = preg_replace('/[^a-zA-Z0-9_\-]+/', '_', $titulo);
$nomeArquivoPdf = "Proposta_{$banca}_{$ano}_{$slugTitulo}.pdf";

$pdfUrl = trim($tema['pdf_url'] ?? '');
$isPdfBinary = false;
$isImageBinary = false;
$imageMime = '';
$binaryData = null;
$base64Image = null;

if (!empty($pdfUrl) && strpos($pdfUrl, 'http') === 0) {
    $ch = curl_init($pdfUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $binaryData = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && !empty($binaryData)) {
        // Validação estrita por Magic Bytes
        if (substr($binaryData, 0, 4) === '%PDF') {
            $isPdfBinary = true;
        } elseif (substr($binaryData, 0, 4) === "\x89PNG") {
            $isImageBinary = true;
            $imageMime = 'image/png';
        } elseif (substr($binaryData, 0, 3) === "\xFF\xD8\xFF") {
            $isImageBinary = true;
            $imageMime = 'image/jpeg';
        }
    }
}

// 1. SE FOR ARQUIVO PDF NATIVO REAL (%PDF):
if ($isPdfBinary) {
    header('Content-Type: application/pdf');
    header('Content-Length: ' . strlen($binaryData));
    if ($forceDownload) {
        header("Content-Disposition: attachment; filename=\"{$nomeArquivoPdf}\"");
    } else {
        header("Content-Disposition: inline; filename=\"{$nomeArquivoPdf}\"");
    }
    echo $binaryData;
    exit;
}

// 2. SE FOR UMA IMAGEM NATIVA DA FOLHA DA PROPOSTA (PNG / JPG):
if ($isImageBinary) {
    if ($forceDownload) {
        $ext = ($imageMime === 'image/png') ? 'png' : 'jpg';
        header("Content-Type: {$imageMime}");
        header('Content-Length: ' . strlen($binaryData));
        header("Content-Disposition: attachment; filename=\"Proposta_{$banca}_{$ano}_{$slugTitulo}.{$ext}\"");
        echo $binaryData;
        exit;
    }
    $base64Image = 'data:' . $imageMime . ';base64,' . base64_encode($binaryData);
}

// 3. SE NÃO FOR PDF NEM IMAGEM: FOLHA OFICIAL A4 DIAGRAMADA
$instrucoesPadrao = [
    'ENEM' => [
        "O rascunho da redação deve ser feito no espaço apropriado.",
        "O texto definitivo deve ser escrito a tinta preta na folha própria, em até 30 linhas.",
        "A redação que apresentar cópia dos textos da Proposta de Redação ou do Caderno de Questões terá o número de linhas copiadas desconsiderado para a contagem de linhas.",
        "Receberá nota zero, em qualquer das situações expressas a seguir, a redação que: tiver até 7 (sete) linhas escritas; fugir ao tema ou que não atender ao tipo dissertativo-argumentativo; apresentar parte deliberadamente desconectada do tema proposto; desrespeitar os princípios dos direitos humanos."
    ],
    'UNESP' => [
        "Em sua redação, procure atender às seguintes recomendações:",
        "1. Posicione-se criticamente em relação à questão formulada na proposta.",
        "2. Desenvolva argumentos consistentes e estruturados em defesa de seu ponto de vista.",
        "3. Empregue a norma-padrão da língua portuguesa, com coesão e coerência textual.",
        "4. Dê um título adequado à sua redação e não copie trechos da coletânea."
    ],
    'FUVEST' => [
        "Instruções da Prova da FUVEST (USP):",
        "1. Redija uma dissertação em prosa, na qual você exponha seu ponto de vista sobre o tema proposto.",
        "2. A redação deve ser obrigatoriamente encabeçada por um título criativo e consistente.",
        "3. Empregue a norma-padrão da língua portuguesa e evite clichês e fórmulas prontas.",
        "4. Articule reflexão filosófica e autônoma, sem cópia literal da coletânea."
    ],
    'UNICAMP' => [
        "Instruções da COMVEST (UNICAMP):",
        "1. Atenda rigorosamente ao gênero textual solicitado e à situação de interlocução proposta.",
        "2. Faça uma leitura crítica e produtiva dos textos da coletânea (sem transcrição literal).",
        "3. Mantenha a clareza, a consistência argumentativa e a autoria reflexiva."
    ]
][$banca] ?? [
    "1. Leia atentamente os textos motivadores para compreender a delimitação temática.",
    "2. Redija um texto dissertativo-argumentativo em língua culta formal.",
    "3. Defenda sua tese com repertório legitimado e argumentos sólidos.",
    "4. Não faça cópia literal da coletânea."
];

// Processar textos motivadores em blocos
$linhas = explode("\n", $tema['textos_motivadores'] ?? '');
$blocosTextos = [];
$blocoAtual = ['titulo' => 'TEXTO I', 'linhas' => [], 'fonte' => ''];

foreach ($linhas as $linha) {
    $l = trim($linha);
    if (!$l) continue;

    if (preg_match('/^TEXTO\s+([IVXLCDM]+)/i', $l)) {
        if (!empty($blocoAtual['linhas'])) {
            $blocosTextos[] = $blocoAtual;
        }
        $blocoAtual = ['titulo' => strtoupper($l), 'linhas' => [], 'fonte' => ''];
        continue;
    }

    if (preg_match('/\[FONTE:\s*([^\]]+)\]/i', $l, $mf)) {
        $blocoAtual['fonte'] = trim($mf[1]);
        continue;
    }

    $blocoAtual['linhas'][] = $l;
}
if (!empty($blocoAtual['linhas'])) {
    $blocosTextos[] = $blocoAtual;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo) ?> — Folha da Proposta Oficial</title>
    <!-- Tailwind CSS para layout -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=Merriweather:ital,wght@0,300;0,400;0,700;1,300&family=Outfit:wght@400;600;800;900&display=swap" rel="stylesheet">

    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        body {
            background-color: #f1f5f9;
            font-family: 'Merriweather', 'Georgia', serif;
            color: #111827;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .folha-a4 {
            width: 100%;
            max-width: 850px;
            margin: 20px auto 40px auto;
            background: #ffffff;
            box-shadow: 0 4px 25px -4px rgba(0, 0, 0, 0.1), 0 0 0 1px rgba(0, 0, 0, 0.05);
            padding: 24px sm:padding: 32px;
            box-sizing: border-box;
            position: relative;
            border-radius: 16px;
        }

        /* Se estiver incorporado em iframe, esconder toolbar e limpar margens */
        body.is-embedded .toolbar-flutuante {
            display: none !important;
        }
        body.is-embedded {
            padding: 0 !important;
            margin: 0 !important;
            background-color: #ffffff !important;
        }
        body.is-embedded .folha-a4 {
            margin: 0 auto !important;
            box-shadow: none !important;
            border-radius: 0 !important;
            max-width: 100% !important;
            padding: 16px sm:padding: 24px !important;
        }

        @media print {
            body {
                background: transparent !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .no-print, .toolbar-flutuante {
                display: none !important;
            }
            .folha-a4 {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                box-shadow: none !important;
                padding: 0 !important;
            }
        }

        .border-prova {
            border: 2px solid #000000;
        }
    </style>
</head>
<body class="min-h-screen">

    <script>
        // Detectar se está rodando dentro de um iframe e ocultar a barra redundante
        if (window.self !== window.top) {
            document.body.classList.add('is-embedded');
        }
    </script>

    <!-- FLOATING TOP ACTION BAR (SOMENTE EM TELA CHEIA FORA DE IFRAME) -->
    <div class="toolbar-flutuante no-print fixed top-4 left-1/2 -translate-x-1/2 z-50 bg-slate-900/95 backdrop-blur-md border border-slate-700 text-white px-5 py-2.5 rounded-2xl shadow-2xl flex items-center gap-4 text-xs font-sans">
        <div class="flex items-center gap-2 pr-3 border-r border-slate-700">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="font-outfit font-black tracking-wider uppercase text-slate-200">
                <?= htmlspecialchars($banca) ?> <?= htmlspecialchars($ano) ?>
            </span>
        </div>

        <button type="button" onclick="window.print()" class="px-3.5 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 font-outfit font-black transition flex items-center gap-1.5 text-white shadow-sm" title="Baixar / Salvar em PDF oficial pelo navegador">
            <i class="bi bi-download"></i>
            <span>Baixar / Salvar PDF</span>
        </button>

        <button type="button" onclick="window.print()" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 font-outfit font-bold transition flex items-center gap-1.5 text-slate-200">
            <i class="bi bi-printer-fill"></i>
            <span>Imprimir</span>
        </button>

        <a href="redacao.php?tema_id=<?= $id ?>" target="_top" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 font-outfit font-bold transition flex items-center gap-1.5 text-white">
            <i class="bi bi-pencil-fill"></i>
            <span>Escrever Redação</span>
        </a>
    </div>

    <?php if ($isImageBinary && !empty($base64Image)): ?>
        <!-- ========================================================================= -->
        <!-- CASO 1: FOLHA OFICIAL EM IMAGEM NATIVA DE ALTA DEFINIÇÃO (REDIGIR)       -->
        <!-- ========================================================================= -->
        <div class="folha-a4 text-black flex flex-col bg-white">
            <div class="w-full flex items-center justify-between pb-3 border-b-2 border-slate-200 mb-4">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-wider rounded-lg font-outfit">
                        <?= htmlspecialchars($banca) ?> <?= htmlspecialchars($ano) ?>
                    </span>
                    <h1 class="font-outfit font-black text-xs sm:text-sm text-slate-900 uppercase m-0 line-clamp-1">
                        <?= htmlspecialchars($titulo) ?>
                    </h1>
                </div>
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider shrink-0 hidden sm:inline">Folha Oficial de Proposta</span>
            </div>

            <!-- IMAGEM DA PROPOSTA EM ALTA RESOLUÇÃO -->
            <div class="w-full flex items-center justify-center bg-white rounded-xl overflow-hidden border border-slate-200 shadow-sm p-1">
                <img src="<?= $base64Image ?>" alt="<?= htmlspecialchars($titulo) ?>" class="w-full h-auto max-w-full object-contain block select-none" />
            </div>

            <div class="w-full flex items-center justify-between pt-3 mt-4 border-t border-slate-200 text-[10px] text-slate-500 font-semibold">
                <span>Plataforma Oficial • Vestilingo</span>
                <span>Documento Original Autenticado • Eixo: <?= htmlspecialchars($tema['eixo_tematico'] ?? 'Geral') ?></span>
            </div>
        </div>

    <?php else: ?>
        <!-- ========================================================================= -->
        <!-- CASO 2: FOLHA A4 OFICIAL DE EXAME DIAGRAMADA (ENEM, VESTIBULARES & TEXTO) -->
        <!-- ========================================================================= -->
        <div class="folha-a4 text-black flex flex-col justify-between bg-white">

            <!-- CABEÇALHO OFICIAL DE PROVA DE EXAME -->
            <header class="border-b-2 border-black pb-3 mb-4">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 flex items-center justify-center border-2 border-black rounded-lg p-1 shrink-0">
                            <svg viewBox="0 0 100 100" class="w-full h-full fill-black">
                                <polygon points="50,5 61,38 95,38 67,58 78,91 50,71 22,91 33,58 5,38 39,38"/>
                                <circle cx="50" cy="50" r="16" fill="white"/>
                                <circle cx="50" cy="50" r="12" fill="black"/>
                            </svg>
                        </div>
                        <div>
                            <span class="block text-[11px] font-bold tracking-widest uppercase text-slate-800">
                                MINISTÉRIO DA EDUCAÇÃO • EXAME NACIONAL
                            </span>
                            <h1 class="text-base font-black tracking-wider uppercase font-outfit m-0 leading-tight">
                                <?= htmlspecialchars($banca) ?> <?= htmlspecialchars($ano) ?> — PROVA DE REDAÇÃO
                            </h1>
                            <span class="block text-[10px] uppercase tracking-wide text-slate-600">
                                Caderno de Prova Oficial • Coletânea de Textos Motivadores
                            </span>
                        </div>
                    </div>

                    <div class="text-right border-2 border-black px-3 py-1 bg-slate-50 shrink-0">
                        <span class="block text-[9px] font-black uppercase text-slate-700">CADERNO</span>
                        <span class="font-outfit font-black text-sm uppercase">1º DIA</span>
                    </div>
                </div>
            </header>

            <!-- CAIXA DE INSTRUÇÕES OFICIAIS -->
            <section class="border border-black p-3 bg-slate-50/80 mb-4 text-[10px] leading-relaxed">
                <div class="font-black uppercase tracking-wider mb-1 text-[11px] border-b border-black/30 pb-0.5">
                    INSTRUÇÕES PARA A REDAÇÃO
                </div>
                <ul class="list-disc ps-4 space-y-0.5 m-0 text-slate-800">
                    <?php foreach ($instrucoesPadrao as $inst): ?>
                        <li><?= htmlspecialchars($inst) ?></li>
                    <?php endforeach; ?>
                </ul>
            </section>

            <!-- SEÇÃO DA PROPOSTA DE REDAÇÃO (TEMA CENTRAL EM DESTAQUE) -->
            <section class="border-prova p-3 bg-black text-white text-center mb-4">
                <span class="block text-[10px] font-bold uppercase tracking-widest text-slate-300 mb-0.5">
                    PROPOSTA DE REDAÇÃO
                </span>
                <p class="text-[11px] leading-snug text-slate-200 mb-1">
                    A partir da leitura dos textos motivadores seguintes e com base nos conhecimentos construídos ao longo de sua formação, redija um texto dissertativo-argumentativo em modalidade escrita formal da língua portuguesa sobre o tema:
                </p>
                <h2 class="text-sm sm:text-base font-black uppercase tracking-wide font-outfit text-white m-0 py-1">
                    "<?= htmlspecialchars($titulo) ?>"
                </h2>
            </section>

            <!-- COLETÂNEA DE TEXTOS MOTIVADORES EM COLUNAS EDITORIAL -->
            <section class="space-y-3 flex-1 mb-4">
                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-600 border-b border-black pb-0.5 mb-2">
                    TEXTOS MOTIVADORES OFICIAIS
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <?php foreach ($blocosTextos as $bl): ?>
                        <div class="border border-black p-3 bg-white flex flex-col justify-between text-[10.5px] leading-relaxed">
                            <div>
                                <span class="inline-block px-1.5 py-0.5 bg-black text-white font-black text-[9px] uppercase tracking-wider mb-1.5">
                                    <?= htmlspecialchars($bl['titulo']) ?>
                                </span>
                                <div class="space-y-1 text-slate-900 text-justify">
                                    <?php foreach ($bl['linhas'] as $pl): ?>
                                        <p class="m-0 indent-3"><?= htmlspecialchars($pl) ?></p>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <?php if (!empty($bl['fonte'])): ?>
                                <div class="mt-2 pt-1 border-t border-dotted border-black/40 text-[9px] text-slate-600 italic">
                                    Disponível em: <?= htmlspecialchars($bl['fonte']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- RODAPÉ DA FOLHA OFICIAL COM CÓDIGO DE BARRAS DE PROVA -->
            <footer class="border-t-2 border-black pt-2 mt-auto text-[9px] text-slate-700 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="font-black uppercase tracking-wider"><?= htmlspecialchars($banca) ?></span>
                    <span>• Eixo: <?= htmlspecialchars($tema['eixo_tematico'] ?? 'Geral') ?></span>
                    <span>• Proposta ID #<?= $tema['id'] ?></span>
                </div>

                <div class="flex items-center gap-0.5 h-4 opacity-80" title="Autenticação Oficial">
                    <?php for ($i = 0; $i < 36; $i++): ?>
                        <span class="bg-black inline-block h-full" style="width: <?= ($i % 3 === 0) ? '3px' : (($i % 2 === 0) ? '1px' : '2px') ?>;"></span>
                    <?php endfor; ?>
                </div>

                <div class="font-bold">
                    PÁGINA 1 / 1
                </div>
            </footer>

        </div>
    <?php endif; ?>

</body>
</html>
