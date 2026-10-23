<?php
/**
 * CENTRAL DE PROVAS & GABARITOS OFICIAIS — HIPOGABARITO
 * Repositório oficial para acessar e baixar cadernos de prova reais
 * e gabaritos oficiais de USP (FUVEST), UNESP (VUNESP), UNICAMP (COMVEST),
 * ENEM (INEP) e UERJ dos anos anteriores.
 */
require_once __DIR__ . '/config/db.php';
checkAuth();

$pageTitle = 'Provas Anteriores & Gabaritos Oficiais — HipoGabarito';
$currentPage = 'provas_oficiais.php';

// LISTA ESTRUTURADA DE PROVAS OFICIAIS E GABARITOS HOMOLOGADOS
$examRepository = [
    // --- FUVEST (USP) ---
    [
        'id' => 'fuvest_2024_f1',
        'banca' => 'fuvest',
        'banca_label' => 'FUVEST / USP',
        'banca_theme' => 'amber',
        'ano' => 2024,
        'fase' => '1ª Fase',
        'titulo' => 'FUVEST 2024 — 1ª Fase (Caderno de Prova V)',
        'descricao' => '90 questões de múltipla escolha abordando todas as disciplinas do Ensino Médio e obras literárias obrigatórias.',
        'questoes_count' => 90,
        'tempo_min' => 300,
        'tipo' => 'Conhecimentos Gerais',
        'prova_url' => 'https://acervo.fuvest.br/vest2024/fuvest_2024_primeira_fase_prova_v.pdf',
        'gabarito_url' => 'https://acervo.fuvest.br/vest2024/fuvest_2024_primeira_fase_gabarito.pdf',
        'simulado_exam' => 'fuvest'
    ],
    [
        'id' => 'fuvest_2024_f2',
        'banca' => 'fuvest',
        'banca_label' => 'FUVEST / USP',
        'banca_theme' => 'amber',
        'ano' => 2024,
        'fase' => '2ª Fase',
        'titulo' => 'FUVEST 2024 — 2ª Fase (Dia 1 & Dia 2)',
        'descricao' => 'Provas discursivas oficiais: Dia 1 (10 questões de Português + Redação) e Dia 2 (12 questões de matérias específicas).',
        'questoes_count' => 22,
        'tempo_min' => 480,
        'tipo' => 'Discursiva + Redação',
        'prova_url' => 'https://acervo.fuvest.br/vest2024/segunda_fase/',
        'gabarito_url' => 'https://acervo.fuvest.br/vest2024/',
        'simulado_exam' => 'fuvest'
    ],
    [
        'id' => 'fuvest_2023_f1',
        'banca' => 'fuvest',
        'banca_label' => 'FUVEST / USP',
        'banca_theme' => 'amber',
        'ano' => 2023,
        'fase' => '1ª Fase',
        'titulo' => 'FUVEST 2023 — 1ª Fase (Caderno de Prova V)',
        'descricao' => 'Caderno oficial da 1ª fase do vestibular da Universidade de São Paulo com gabarito retificado.',
        'questoes_count' => 90,
        'tempo_min' => 300,
        'tipo' => 'Conhecimentos Gerais',
        'prova_url' => 'https://acervo.fuvest.br/vest2023/fuvest_2023_primeira_fase_prova_v.pdf',
        'gabarito_url' => 'https://acervo.fuvest.br/vest2023/fuvest_2023_primeira_fase_gabarito.pdf',
        'simulado_exam' => 'fuvest'
    ],
    [
        'id' => 'fuvest_2022_f1',
        'banca' => 'fuvest',
        'banca_label' => 'FUVEST / USP',
        'banca_theme' => 'amber',
        'ano' => 2022,
        'fase' => '1ª Fase',
        'titulo' => 'FUVEST 2022 — 1ª Fase (Caderno de Prova V)',
        'descricao' => 'Prova de Conhecimentos Gerais aplicada em novembro de 2021 para ingresso na USP em 2022.',
        'questoes_count' => 90,
        'tempo_min' => 300,
        'tipo' => 'Conhecimentos Gerais',
        'prova_url' => 'https://acervo.fuvest.br/vest2022/fuvest_2022_primeira_fase_prova_v.pdf',
        'gabarito_url' => 'https://acervo.fuvest.br/vest2022/fuvest_2022_primeira_fase_gabarito.pdf',
        'simulado_exam' => 'fuvest'
    ],
    [
        'id' => 'fuvest_2021_f1',
        'banca' => 'fuvest',
        'banca_label' => 'FUVEST / USP',
        'banca_theme' => 'amber',
        'ano' => 2021,
        'fase' => '1ª Fase',
        'titulo' => 'FUVEST 2021 — 1ª Fase (Caderno Oficial)',
        'descricao' => 'Edição do vestibular com forte teor analítico e questões de literatura e ciências exatas.',
        'questoes_count' => 90,
        'tempo_min' => 300,
        'tipo' => 'Conhecimentos Gerais',
        'prova_url' => 'https://acervo.fuvest.br/vest2021/fuvest_2021_primeira_fase_prova_v.pdf',
        'gabarito_url' => 'https://acervo.fuvest.br/vest2021/fuvest_2021_primeira_fase_gabarito.pdf',
        'simulado_exam' => 'fuvest'
    ],

    // --- UNICAMP (COMVEST) ---
    [
        'id' => 'unicamp_2024_f1',
        'banca' => 'unicamp',
        'banca_label' => 'UNICAMP',
        'banca_theme' => 'rose',
        'ano' => 2024,
        'fase' => '1ª Fase',
        'titulo' => 'UNICAMP 2024 — 1ª Fase (Caderno Q/X)',
        'descricao' => '72 questões de 4 alternativas (A a D) com foco em letramento visual, charges e atualidades.',
        'questoes_count' => 72,
        'tempo_min' => 300,
        'tipo' => 'Conhecimentos Gerais (4 Alt)',
        'prova_url' => 'https://www.comvest.unicamp.br/vest2024/F1/provas/f12024q_x.pdf',
        'gabarito_url' => 'https://www.comvest.unicamp.br/vest2024/F1/gabaritos/gabf12024.pdf',
        'simulado_exam' => 'unicamp'
    ],
    [
        'id' => 'unicamp_2024_f2',
        'banca' => 'unicamp',
        'banca_label' => 'UNICAMP',
        'banca_theme' => 'rose',
        'ano' => 2024,
        'fase' => '2ª Fase',
        'titulo' => 'UNICAMP 2024 — 2ª Fase (Cadernos & Propostas)',
        'descricao' => 'Cadernos completos do Dia 1 (Redação com gêneros variados + Português + Inglês) e Dia 2 (Específicas).',
        'questoes_count' => 20,
        'tempo_min' => 600,
        'tipo' => 'Discursiva + Gêneros Textuais',
        'prova_url' => 'https://www.comvest.unicamp.br/vestibulares-anteriores/vestibular-2024/',
        'gabarito_url' => 'https://www.comvest.unicamp.br/vestibulares-anteriores/vestibular-2024/',
        'simulado_exam' => 'unicamp'
    ],
    [
        'id' => 'unicamp_2023_f1',
        'banca' => 'unicamp',
        'banca_label' => 'UNICAMP',
        'banca_theme' => 'rose',
        'ano' => 2023,
        'fase' => '1ª Fase',
        'titulo' => 'UNICAMP 2023 — 1ª Fase (Caderno Q/T)',
        'descricao' => 'Caderno oficial da 1ª fase com resolução de gráficos, textos de apoio e interpretação interdisciplinar.',
        'questoes_count' => 72,
        'tempo_min' => 300,
        'tipo' => 'Conhecimentos Gerais (4 Alt)',
        'prova_url' => 'https://www.comvest.unicamp.br/vest2023/F1/provas/f12023q_t.pdf',
        'gabarito_url' => 'https://www.comvest.unicamp.br/vest2023/F1/gabaritos/gabf12023.pdf',
        'simulado_exam' => 'unicamp'
    ],
    [
        'id' => 'unicamp_2022_f1',
        'banca' => 'unicamp',
        'banca_label' => 'UNICAMP',
        'banca_theme' => 'rose',
        'ano' => 2022,
        'fase' => '1ª Fase',
        'titulo' => 'UNICAMP 2022 — 1ª Fase (Caderno Q/Q)',
        'descricao' => 'Exame da Unicamp com abordagem moderna de ciências humanas, literatura lusófona e ciências biológicas.',
        'questoes_count' => 72,
        'tempo_min' => 300,
        'tipo' => 'Conhecimentos Gerais (4 Alt)',
        'prova_url' => 'https://www.comvest.unicamp.br/vest2022/F1/provas/f12022q_q.pdf',
        'gabarito_url' => 'https://www.comvest.unicamp.br/vest2022/F1/gabaritos/gabf12022.pdf',
        'simulado_exam' => 'unicamp'
    ],

    // --- UNESP (VUNESP) ---
    [
        'id' => 'unesp_2024_f1',
        'banca' => 'unesp',
        'banca_label' => 'UNESP / VUNESP',
        'banca_theme' => 'blue',
        'ano' => 2024,
        'fase' => '1ª Fase',
        'titulo' => 'UNESP 2024 — 1ª Fase (Conhecimentos Gerais Versão 1)',
        'descricao' => '90 questões divididas rigorosamente em 3 blocos de 30 (Linguagens, Humanas e Natureza/Matemática).',
        'questoes_count' => 90,
        'tempo_min' => 300,
        'tipo' => 'Conhecimentos Gerais',
        'prova_url' => 'https://vestibular.unesp.br/portal/vestibular-2024/',
        'gabarito_url' => 'https://vestibular.unesp.br/portal/vestibular-2024/',
        'simulado_exam' => 'unesp'
    ],
    [
        'id' => 'unesp_2024_f2',
        'banca' => 'unesp',
        'banca_label' => 'UNESP / VUNESP',
        'banca_theme' => 'blue',
        'ano' => 2024,
        'fase' => '2ª Fase',
        'titulo' => 'UNESP 2024 — 2ª Fase (Dia 1 & Dia 2)',
        'descricao' => '36 questões discursivas oficiais + Proposta de Redação com tema provocador em formato de pergunta.',
        'questoes_count' => 36,
        'tempo_min' => 540,
        'tipo' => 'Discursiva + Redação',
        'prova_url' => 'https://vestibular.unesp.br/portal/vestibular-2024/',
        'gabarito_url' => 'https://vestibular.unesp.br/portal/vestibular-2024/',
        'simulado_exam' => 'unesp'
    ],
    [
        'id' => 'unesp_2023_f1',
        'banca' => 'unesp',
        'banca_label' => 'UNESP / VUNESP',
        'banca_theme' => 'blue',
        'ano' => 2023,
        'fase' => '1ª Fase',
        'titulo' => 'UNESP 2023 — 1ª Fase (Conhecimentos Gerais)',
        'descricao' => 'Caderno oficial da Vunesp com enunciados diretos, objetivos e sem pegadinhas conceituais.',
        'questoes_count' => 90,
        'tempo_min' => 300,
        'tipo' => 'Conhecimentos Gerais',
        'prova_url' => 'https://vestibular.unesp.br/portal/vestibular-2023/',
        'gabarito_url' => 'https://vestibular.unesp.br/portal/vestibular-2023/',
        'simulado_exam' => 'unesp'
    ],
    [
        'id' => 'unesp_2022_f1',
        'banca' => 'unesp',
        'banca_label' => 'UNESP / VUNESP',
        'banca_theme' => 'blue',
        'ano' => 2022,
        'fase' => '1ª Fase',
        'titulo' => 'UNESP 2022 — 1ª Fase (Caderno Oficial)',
        'descricao' => 'Prova da Unesp aplicada pela Fundação Vunesp com gabarito oficial divulgado.',
        'questoes_count' => 90,
        'tempo_min' => 300,
        'tipo' => 'Conhecimentos Gerais',
        'prova_url' => 'https://vestibular.unesp.br/portal/vestibular-2022/',
        'gabarito_url' => 'https://vestibular.unesp.br/portal/vestibular-2022/',
        'simulado_exam' => 'unesp'
    ],

    // --- ENEM (INEP) ---
    [
        'id' => 'enem_2023_d1',
        'banca' => 'enem',
        'banca_label' => 'ENEM / INEP',
        'banca_theme' => 'emerald',
        'ano' => 2023,
        'fase' => 'Dia 1',
        'titulo' => 'ENEM 2023 — Dia 1 (Caderno 1 Azul)',
        'descricao' => '45 questões de Linguagens e Códigos + 45 de Ciências Humanas + Proposta Oficial de Redação.',
        'questoes_count' => 90,
        'tempo_min' => 330,
        'tipo' => 'Linguagens, Humanas & Redação',
        'prova_url' => 'https://download.inep.gov.br/enem/provas_e_gabaritos/2023_PV_impresso_D1_CD1.pdf',
        'gabarito_url' => 'https://download.inep.gov.br/enem/provas_e_gabaritos/2023_GB_impresso_D1_CD1.pdf',
        'simulado_exam' => 'enem'
    ],
    [
        'id' => 'enem_2023_d2',
        'banca' => 'enem',
        'banca_label' => 'ENEM / INEP',
        'banca_theme' => 'emerald',
        'ano' => 2023,
        'fase' => 'Dia 2',
        'titulo' => 'ENEM 2023 — Dia 2 (Caderno 7 Amarelo)',
        'descricao' => '45 questões de Matemática e suas Tecnologias + 45 questões de Ciências da Natureza.',
        'questoes_count' => 90,
        'tempo_min' => 300,
        'tipo' => 'Matemática & Ciências da Natureza',
        'prova_url' => 'https://download.inep.gov.br/enem/provas_e_gabaritos/2023_PV_impresso_D2_CD7.pdf',
        'gabarito_url' => 'https://download.inep.gov.br/enem/provas_e_gabaritos/2023_GB_impresso_D2_CD7.pdf',
        'simulado_exam' => 'enem'
    ],
    [
        'id' => 'enem_2022_d1',
        'banca' => 'enem',
        'banca_label' => 'ENEM / INEP',
        'banca_theme' => 'emerald',
        'ano' => 2022,
        'fase' => 'Dia 1',
        'titulo' => 'ENEM 2022 — Dia 1 (Caderno 1 Azul)',
        'descricao' => 'Caderno impresso oficial do 1º domingo do ENEM 2022 com a redação dos povos tradicionais.',
        'questoes_count' => 90,
        'tempo_min' => 330,
        'tipo' => 'Linguagens, Humanas & Redação',
        'prova_url' => 'https://download.inep.gov.br/enem/provas_e_gabaritos/2022_PV_impresso_D1_CD1.pdf',
        'gabarito_url' => 'https://download.inep.gov.br/enem/provas_e_gabaritos/2022_GB_impresso_D1_CD1.pdf',
        'simulado_exam' => 'enem'
    ],
    [
        'id' => 'enem_2022_d2',
        'banca' => 'enem',
        'banca_label' => 'ENEM / INEP',
        'banca_theme' => 'emerald',
        'ano' => 2022,
        'fase' => 'Dia 2',
        'titulo' => 'ENEM 2022 — Dia 2 (Caderno 7 Amarelo)',
        'descricao' => 'Prova oficial do 2º domingo do ENEM 2022: Matemática e Ciências da Natureza.',
        'questoes_count' => 90,
        'tempo_min' => 300,
        'tipo' => 'Matemática & Ciências da Natureza',
        'prova_url' => 'https://download.inep.gov.br/enem/provas_e_gabaritos/2022_PV_impresso_D2_CD7.pdf',
        'gabarito_url' => 'https://download.inep.gov.br/enem/provas_e_gabaritos/2022_GB_impresso_D2_CD7.pdf',
        'simulado_exam' => 'enem'
    ],
    [
        'id' => 'enem_2021_d1',
        'banca' => 'enem',
        'banca_label' => 'ENEM / INEP',
        'banca_theme' => 'emerald',
        'ano' => 2021,
        'fase' => 'Dia 1',
        'titulo' => 'ENEM 2021 — Dia 1 (Caderno 1 Azul)',
        'descricao' => 'Caderno oficial com Linguagens, Ciências Humanas e tema da invisibilidade do registro civil.',
        'questoes_count' => 90,
        'tempo_min' => 330,
        'tipo' => 'Linguagens, Humanas & Redação',
        'prova_url' => 'https://download.inep.gov.br/enem/provas_e_gabaritos/2021_PV_impresso_D1_CD1.pdf',
        'gabarito_url' => 'https://download.inep.gov.br/enem/provas_e_gabaritos/2021_GB_impresso_D1_CD1.pdf',
        'simulado_exam' => 'enem'
    ],
    [
        'id' => 'enem_2021_d2',
        'banca' => 'enem',
        'banca_label' => 'ENEM / INEP',
        'banca_theme' => 'emerald',
        'ano' => 2021,
        'fase' => 'Dia 2',
        'titulo' => 'ENEM 2021 — Dia 2 (Caderno 7 Amarelo)',
        'descricao' => 'Caderno impresso oficial de Matemática e Ciências da Natureza do ENEM 2021.',
        'questoes_count' => 90,
        'tempo_min' => 300,
        'tipo' => 'Matemática & Ciências da Natureza',
        'prova_url' => 'https://download.inep.gov.br/enem/provas_e_gabaritos/2021_PV_impresso_D2_CD7.pdf',
        'gabarito_url' => 'https://download.inep.gov.br/enem/provas_e_gabaritos/2021_GB_impresso_D2_CD7.pdf',
        'simulado_exam' => 'enem'
    ],

    // --- UERJ ---
    [
        'id' => 'uerj_2024_eq1',
        'banca' => 'uerj',
        'banca_label' => 'UERJ',
        'banca_theme' => 'teal',
        'ano' => 2024,
        'fase' => '1º EQ',
        'titulo' => 'UERJ 2024 — 1º Exame de Qualificação',
        'descricao' => '60 questões de múltipla escolha com conceitos A, B, C e D valendo bônus de até 20 pontos.',
        'questoes_count' => 60,
        'tempo_min' => 240,
        'tipo' => 'Exame de Qualificação (EQ)',
        'prova_url' => 'https://www.vestibular.uerj.br/',
        'gabarito_url' => 'https://www.vestibular.uerj.br/',
        'simulado_exam' => 'uerj'
    ],
    [
        'id' => 'uerj_2024_ed',
        'banca' => 'uerj',
        'banca_label' => 'UERJ',
        'banca_theme' => 'teal',
        'ano' => 2024,
        'fase' => 'Discursiva',
        'titulo' => 'UERJ 2024 — Exame Discursivo & Redação',
        'descricao' => 'Prova discursiva de Língua Portuguesa Instrumental com Redação reflexiva inspirada no livro indicado.',
        'questoes_count' => 20,
        'tempo_min' => 300,
        'tipo' => 'Exame Discursivo (ED)',
        'prova_url' => 'https://www.vestibular.uerj.br/',
        'gabarito_url' => 'https://www.vestibular.uerj.br/',
        'simulado_exam' => 'uerj'
    ]
];

require_once __DIR__ . '/includes/header.php';
?>

    <!-- LAYOUT PRINCIPAL -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-all">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- SIDEBAR LATERAL -->
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

            <!-- CONTEÚDO PRINCIPAL: CENTRAL DE PROVAS -->
            <section class="lg:col-span-9 space-y-6">

                <!-- HERO BANNER -->
                <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-purple-950 text-white rounded-3xl p-6 sm:p-8 shadow-[0_4px_0_0_#1e1b4b] border-2 border-indigo-800/60 relative overflow-hidden">
                    <div class="relative z-10 max-w-2xl">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-white/10 text-amber-300 uppercase tracking-wider mb-3 border border-white/10">
                            <i class="bi bi-file-earmark-pdf-fill text-amber-400"></i> ACERVO OFICIAL DE PROVAS
                        </div>
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold font-outfit text-white mb-2 leading-tight">
                            Cadernos de Prova & Gabaritos Oficiais
                        </h1>
                        <p class="text-xs sm:text-sm text-indigo-200 font-medium leading-relaxed mb-4">
                            Baixe os cadernos reais de prova (PDFs) e gabaritos homologados da <strong>USP (FUVEST), UNICAMP, UNESP, ENEM e UERJ</strong>. Treine com o material impresso original ou pratique online nos nossos simulados.
                        </p>

                        <div class="flex flex-wrap items-center gap-3 text-xs font-bold text-slate-300">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-white/10 text-white border border-white/10">
                                <i class="bi bi-check2-circle text-emerald-400"></i> <?= count($examRepository) ?> Cadernos Catalogados
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-white/10 text-white border border-white/10">
                                <i class="bi bi-clock-history text-amber-300"></i> Edições 2021 a 2024
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-white/10 text-white border border-white/10">
                                <i class="bi bi-patch-check-fill text-sky-400"></i> Gabaritos Oficiais
                            </span>
                        </div>
                    </div>

                    <div class="absolute -right-6 -bottom-8 opacity-15 sm:opacity-20 pointer-events-none text-9xl text-indigo-400">
                        <i class="bi bi-journal-arrow-down"></i>
                    </div>
                </div>

                <!-- PAINEL DE FILTROS INTERATIVOS -->
                <div class="bg-white rounded-3xl border-2 border-slate-200 p-5 sm:p-6 shadow-[0_4px_0_0_#e2e8f0] space-y-4">
                    
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                        <div>
                            <h2 class="text-base font-extrabold font-outfit text-slate-900 flex items-center gap-2">
                                <i class="bi bi-funnel-fill text-indigo-600"></i>
                                Filtrar Provas por Vestibular e Ano
                            </h2>
                            <p class="text-xs text-slate-500 font-medium mb-0">Selecione a instituição desejada para encontrar o caderno de prova em 1 clique:</p>
                        </div>

                        <span id="filteredCountBadge" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-black bg-indigo-50 text-indigo-700 border border-indigo-200">
                            Mostrando <?= count($examRepository) ?> provas
                        </span>
                    </div>

                    <!-- BANCAS (PILLS RÁPIDAS) -->
                    <div class="flex flex-wrap items-center gap-2" id="bancaPills">
                        <button type="button" onclick="setBancaFilter('all', this)" class="banca-btn active px-3.5 py-2 rounded-xl font-outfit font-black text-xs bg-indigo-600 text-white shadow-sm transition-all">
                            Todas as Bancas
                        </button>
                        <button type="button" onclick="setBancaFilter('fuvest', this)" class="banca-btn px-3.5 py-2 rounded-xl font-outfit font-black text-xs bg-slate-100 text-slate-700 hover:bg-amber-50 hover:text-amber-800 border border-slate-200 transition-all flex items-center gap-1.5">
                            <span>🏛️ FUVEST (USP)</span>
                        </button>
                        <button type="button" onclick="setBancaFilter('unicamp', this)" class="banca-btn px-3.5 py-2 rounded-xl font-outfit font-black text-xs bg-slate-100 text-slate-700 hover:bg-rose-50 hover:text-rose-800 border border-slate-200 transition-all flex items-center gap-1.5">
                            <span>🔬 UNICAMP</span>
                        </button>
                        <button type="button" onclick="setBancaFilter('unesp', this)" class="banca-btn px-3.5 py-2 rounded-xl font-outfit font-black text-xs bg-slate-100 text-slate-700 hover:bg-blue-50 hover:text-blue-800 border border-slate-200 transition-all flex items-center gap-1.5">
                            <span>📚 UNESP</span>
                        </button>
                        <button type="button" onclick="setBancaFilter('enem', this)" class="banca-btn px-3.5 py-2 rounded-xl font-outfit font-black text-xs bg-slate-100 text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 border border-slate-200 transition-all flex items-center gap-1.5">
                            <span>🇧🇷 ENEM</span>
                        </button>
                        <button type="button" onclick="setBancaFilter('uerj', this)" class="banca-btn px-3.5 py-2 rounded-xl font-outfit font-black text-xs bg-slate-100 text-slate-700 hover:bg-teal-50 hover:text-teal-800 border border-slate-200 transition-all flex items-center gap-1.5">
                            <span>⚓ UERJ</span>
                        </button>
                    </div>

                    <!-- BUSCA POR TEXTO & SELETOR DE ANO/FASE -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 pt-2">
                        <div class="sm:col-span-6 relative">
                            <i class="bi bi-search absolute left-3.5 top-3 text-slate-400"></i>
                            <input type="text" id="searchInput" autocomplete="off" oninput="applyFilters()" placeholder="Buscar por ano, fase ou caderno (Ex: 2024, 1ª fase, Caderno V...)" class="w-full pl-10 pr-4 py-2.5 rounded-xl border-2 border-slate-200 focus:border-indigo-600 text-xs font-bold text-slate-800 focus:outline-none">
                        </div>

                        <div class="sm:col-span-3">
                            <select id="anoFilter" onchange="applyFilters()" class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 focus:border-indigo-600 text-xs font-bold text-slate-800 focus:outline-none cursor-pointer">
                                <option value="">Todos os Anos</option>
                                <option value="2024">2024</option>
                                <option value="2023">2023</option>
                                <option value="2022">2022</option>
                                <option value="2021">2021</option>
                            </select>
                        </div>

                        <div class="sm:col-span-3">
                            <select id="faseFilter" onchange="applyFilters()" class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 focus:border-indigo-600 text-xs font-bold text-slate-800 focus:outline-none cursor-pointer">
                                <option value="">Todas as Fases</option>
                                <option value="1ª Fase">1ª Fase / Objetiva</option>
                                <option value="2ª Fase">2ª Fase / Discursiva</option>
                                <option value="Dia 1">Dia 1</option>
                                <option value="Dia 2">Dia 2</option>
                            </select>
                        </div>
                    </div>

                </div>

                <!-- GRID DE CARDS DAS PROVAS -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="examsGrid">
                    <?php foreach ($examRepository as $exam): ?>
                        <div class="exam-card-item bg-white rounded-3xl border-2 border-slate-200 p-5 sm:p-6 shadow-[0_4px_0_0_#e2e8f0] hover:border-indigo-400 transition-all flex flex-col justify-between space-y-4"
                             data-banca="<?= htmlspecialchars($exam['banca']) ?>"
                             data-ano="<?= htmlspecialchars($exam['ano']) ?>"
                             data-fase="<?= htmlspecialchars($exam['fase']) ?>"
                             data-search="<?= strtolower(htmlspecialchars($exam['titulo'] . ' ' . $exam['banca_label'] . ' ' . $exam['descricao'])) ?>">

                            <!-- CABEÇALHO DO CARD -->
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-2">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-wider
                                        <?php if ($exam['banca_theme'] === 'amber'): ?> bg-amber-100 text-amber-900 border border-amber-300
                                        <?php elseif ($exam['banca_theme'] === 'rose'): ?> bg-rose-100 text-rose-900 border border-rose-300
                                        <?php elseif ($exam['banca_theme'] === 'blue'): ?> bg-blue-100 text-blue-900 border border-blue-300
                                        <?php elseif ($exam['banca_theme'] === 'emerald'): ?> bg-emerald-100 text-emerald-900 border border-emerald-300
                                        <?php else: ?> bg-teal-100 text-teal-900 border border-teal-300 <?php endif; ?>">
                                        <?= htmlspecialchars($exam['banca_label']) ?>
                                    </span>

                                    <span class="font-outfit font-black text-xs text-slate-500">
                                        <?= htmlspecialchars($exam['ano']) ?> • <?= htmlspecialchars($exam['fase']) ?>
                                    </span>
                                </div>

                                <h3 class="font-outfit font-black text-base text-slate-900 mb-1 leading-snug">
                                    <?= htmlspecialchars($exam['titulo']) ?>
                                </h3>

                                <p class="text-xs text-slate-500 font-medium leading-relaxed mb-3">
                                    <?= htmlspecialchars($exam['descricao']) ?>
                                </p>

                                <!-- METADADOS: QUESTÕES E TEMPO -->
                                <div class="flex flex-wrap items-center gap-2 text-[11px] font-bold text-slate-600 mb-2">
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200">
                                        <i class="bi bi-card-checklist text-indigo-600"></i> <?= $exam['questoes_count'] ?> questões
                                    </span>
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200">
                                        <i class="bi bi-stopwatch text-indigo-600"></i> <?= round($exam['tempo_min'] / 60, 1) ?>h de prova
                                    </span>
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200 text-slate-700">
                                        <?= htmlspecialchars($exam['tipo']) ?>
                                    </span>
                                </div>
                            </div>

                            <!-- BOTÕES DE AÇÃO: BAIXAR PROVA, GABARITO & SIMULADO -->
                            <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center gap-2">
                                <!-- BOTÃO PROVA -->
                                <a href="<?= htmlspecialchars($exam['prova_url']) ?>" target="_blank" rel="noopener noreferrer" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl font-outfit font-black text-xs bg-indigo-600 hover:bg-indigo-700 text-white shadow-xs transition-all">
                                    <i class="bi bi-file-earmark-pdf-fill"></i>
                                    <span>Baixar Prova</span>
                                </a>

                                <!-- BOTÃO GABARITO -->
                                <a href="<?= htmlspecialchars($exam['gabarito_url']) ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl font-outfit font-black text-xs bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-300 text-slate-700 border border-slate-200 transition-all" title="Ver Gabarito Oficial">
                                    <i class="bi bi-check2-circle text-emerald-600"></i>
                                    <span>Gabarito</span>
                                </a>

                                <!-- BOTÃO SIMULAR ONLINE -->
                                <a href="simulado.php?exam=<?= urlencode($exam['simulado_exam']) ?>" class="inline-flex items-center justify-center gap-1 px-3 py-2 rounded-xl font-outfit font-black text-xs bg-amber-400 hover:bg-amber-300 text-slate-900 shadow-xs transition-all" title="Resolver online no Simulado">
                                    <i class="bi bi-play-circle-fill"></i>
                                    <span>Simular</span>
                                </a>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- EMPTY STATE QUANDO NENHUMA PROVA CORRESPONDE AOS FILTROS -->
                <div id="noResultsState" class="hidden bg-white rounded-3xl border-2 border-slate-200 p-8 text-center space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-2xl">
                        <i class="bi bi-search"></i>
                    </div>
                    <h3 class="font-outfit font-black text-base text-slate-800 mb-1">Nenhum caderno encontrado com esses filtros</h3>
                    <p class="text-xs text-slate-500 font-medium max-w-sm mx-auto mb-2">Tente selecionar outra banca examinadora ou limpar os termos de busca.</p>
                    <button type="button" onclick="resetFilters()" class="px-4 py-2 rounded-xl bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-outfit font-black text-xs border border-indigo-200 transition-all">
                        Limpar Filtros
                    </button>
                </div>

            </section>
        </div>
    </main>

    <!-- SCRIPTS DE FILTRAGEM REATIVA -->
    <script>
        let currentBanca = 'all';

        function setBancaFilter(bancaKey, btn) {
            currentBanca = bancaKey;
            
            document.querySelectorAll('.banca-btn').forEach(b => {
                b.className = 'banca-btn px-3.5 py-2 rounded-xl font-outfit font-black text-xs bg-slate-100 text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 border border-slate-200 transition-all';
            });
            if (btn) {
                btn.className = 'banca-btn active px-3.5 py-2 rounded-xl font-outfit font-black text-xs bg-indigo-600 text-white shadow-sm transition-all';
            }

            applyFilters();
        }

        function applyFilters() {
            const query = (document.getElementById('searchInput').value || '').toLowerCase().trim();
            const ano = document.getElementById('anoFilter').value;
            const fase = document.getElementById('faseFilter').value;

            const cards = document.querySelectorAll('.exam-card-item');
            let visibleCount = 0;

            cards.forEach(card => {
                const cardBanca = card.getAttribute('data-banca');
                const cardAno = card.getAttribute('data-ano');
                const cardFase = card.getAttribute('data-fase');
                const cardSearch = card.getAttribute('data-search');

                // Filtro Banca
                if (currentBanca !== 'all' && cardBanca !== currentBanca) {
                    card.classList.add('hidden');
                    return;
                }

                // Filtro Ano
                if (ano && cardAno !== ano) {
                    card.classList.add('hidden');
                    return;
                }

                // Filtro Fase
                if (fase && !cardFase.includes(fase)) {
                    card.classList.add('hidden');
                    return;
                }

                // Filtro Busca Texto
                if (query && !cardSearch.includes(query)) {
                    card.classList.add('hidden');
                    return;
                }

                card.classList.remove('hidden');
                visibleCount++;
            });

            // Atualizar badge de contagem
            const badge = document.getElementById('filteredCountBadge');
            if (badge) {
                badge.textContent = `Mostrando ${visibleCount} de ${cards.length} provas`;
            }

            // Exibir ou ocultar empty state
            const emptyState = document.getElementById('noResultsState');
            if (emptyState) {
                if (visibleCount === 0) {
                    emptyState.classList.remove('hidden');
                } else {
                    emptyState.classList.add('hidden');
                }
            }
        }

        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('anoFilter').value = '';
            document.getElementById('faseFilter').value = '';
            setBancaFilter('all', document.querySelector('.banca-btn'));
        }
    </script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
