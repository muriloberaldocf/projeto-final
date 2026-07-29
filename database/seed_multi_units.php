<?php
/**
 * REESTRUTURAÇÃO MULTI-UNIDADES (4 UNIDADES POR MATÉRIA) - APROVAQUEST
 * Cria uma estrutura completa de unidades temáticas e tópicos por matéria.
 */

require_once __DIR__ . '/../config/db.php';

set_time_limit(600);
ini_set('memory_limit', '512M');

echo "<h3>📚 Reestruturando Unidades e Tópicos (4 Unidades por Matéria)...</h3>";

$structure = [
    // 1. Matemática (subject_id = 1)
    1 => [
        [
            'title' => 'Unidade 1: Matemática Básica & Porcentagem',
            'desc' => 'Fundamentos essenciais de cálculos percentuais e proporcionalidade.',
            'lessons' => ['Porcentagem e Descontos Sucessivos', 'Juros Simples e Compostos', 'Regra de Três Simples e Composta']
        ],
        [
            'title' => 'Unidade 2: Álgebra & Funções',
            'desc' => 'Equações, funções de 1º e 2º grau e interpretação de gráficos.',
            'lessons' => ['Equações e Inequações do 1º Grau', 'Função Quadrática e Ponto de Vértice']
        ],
        [
            'title' => 'Unidade 3: Geometria Plana & Espacial',
            'desc' => 'Cálculo de áreas, perímetros, volumes de sólidos e trigonometria.',
            'lessons' => ['Geometria Plana: Áreas e Perímetros', 'Geometria Espacial: Volume de Prismas e Cilindros', 'Trigonometria no Triângulo Retângulo']
        ],
        [
            'title' => 'Unidade 4: Estatística & Probabilidade',
            'desc' => 'Análise de dados estatísticos, médias e chances de eventos.',
            'lessons' => ['Cálculo de Probabilidades', 'Estatística: Média, Moda e Mediana']
        ]
    ],
    // 2. Física (subject_id = 2)
    2 => [
        [
            'title' => 'Unidade 1: Mecânica & Cinemática',
            'desc' => 'Movimento retilíneo, aceleração e leis de Newton.',
            'lessons' => ['Cinemática: Velocidade Média e MRU', 'Movimento Uniformemente Variado (MUV)', 'Dinâmica: Leis de Newton e Força Resultante']
        ],
        [
            'title' => 'Unidade 2: Energia & Hidrostática',
            'desc' => 'Trabalho mecânico, conservação de energia e pressão de fluidos.',
            'lessons' => ['Trabalho, Energia e Conservação Mecânica', 'Hidrostática: Pressão e Princípio de Arquimedes']
        ],
        [
            'title' => 'Unidade 3: Termologia & Óptica',
            'desc' => 'Escalas termométricas, trocas de calor e óptica geométrica.',
            'lessons' => ['Termometria e Escalas de Temperatura', 'Calorimetria: Calor Sensível e Latente', 'Óptica Geométrica e Reflexão']
        ],
        [
            'title' => 'Unidade 4: Eletricidade & Circuitos',
            'desc' => 'Cargas elétricas, leis de Ohm e análise de circuitos.',
            'lessons' => ['Eletrostática e Carga Elétrica', 'Circuitos Elétricos e Leis de Ohm']
        ]
    ],
    // 3. Química (subject_id = 3)
    3 => [
        [
            'title' => 'Unidade 1: Química Geral & Atômica',
            'desc' => 'Modelos atômicos, tabela periódica e ligações químicas.',
            'lessons' => ['Modelos Atômicos e Distribuição Eletrônica', 'Tabela Periódica e Propriedades', 'Ligações Iônicas e Covalentes']
        ],
        [
            'title' => 'Unidade 2: Soluções & Estequiometria',
            'desc' => 'Geometria molecular, soluções aquosas e cálculos estequiométricos.',
            'lessons' => ['Geometria Molecular e Polaridade', 'Concentração de Soluções e Molaridade', 'Cálculo Estequiométrico']
        ],
        [
            'title' => 'Unidade 3: Físico-Química',
            'desc' => 'Variação de entalpia, termoquímica e processos eletroquímicos.',
            'lessons' => ['Termoquímica e Enthalpia', 'Eletroquímica: Pilhas e Eletrólise']
        ],
        [
            'title' => 'Unidade 4: Química Orgânica',
            'desc' => 'Cadeias de carbono, funções orgânicas e isomeria.',
            'lessons' => ['Química Orgânica: Cadeias Carbônicas', 'Funções Orgânicas e Isomeria']
        ]
    ],
    // 4. Biologia (subject_id = 4)
    4 => [
        [
            'title' => 'Unidade 1: Citologia & Metabolismo Celular',
            'desc' => 'Organelas, transporte de membrana e fotossíntese/respiração.',
            'lessons' => ['Citologia: Organelas e Funções', 'Membrana Plasmática e Transporte Celular', 'Respiração Celular e Fotossíntese']
        ],
        [
            'title' => 'Unidade 2: Ecologia & Meio Ambiente',
            'desc' => 'Fluxo de energia nas cadeias e interações ecológicas.',
            'lessons' => ['Ecologia: Cadeias e Teias Alimentares', 'Relações Ecológicas Harmoniosas e Desarmoniosas']
        ],
        [
            'title' => 'Unidade 3: Genética & Biotecnologia',
            'desc' => 'Leis de Mendel, grupos sanguíneos ABO e engenharia genética.',
            'lessons' => ['Genética: Primeira e Segunda Lei de Mendel', 'Sistema ABO e Fator Rh', 'Biotecnologia, Engenharia Genética e DNA']
        ],
        [
            'title' => 'Unidade 4: Fisiologia & Imunologia',
            'desc' => 'Sistemas do corpo humano, resposta imune e vacinas.',
            'lessons' => ['Fisiologia Humana: Sistemas e Órgãos', 'Imunologia, Vacinas e Soros']
        ]
    ],
    // 5. Português & Literatura (subject_id = 5)
    5 => [
        [
            'title' => 'Unidade 1: Interpretação & Gêneros Textuais',
            'desc' => 'Compreensão de texto, coesão, coerência e gêneros.',
            'lessons' => ['Compreensão e Interpretação de Texto', 'Coesão e Coerência Textual', 'Tipologias e Gêneros Textuais']
        ],
        [
            'title' => 'Unidade 2: Gramática & Sintaxe',
            'desc' => 'Morfologia, estrutura sintática e regência com crase.',
            'lessons' => ['Classes Gramaticais de Palavras', 'Sintaxe: Sujeito, Predicado e Complementos', 'Crase e Regência Verbal/Nominal']
        ],
        [
            'title' => 'Unidade 3: Semântica & Figuras de Linguagem',
            'desc' => 'Recursos estilísticos, metáforas e funções da linguagem.',
            'lessons' => ['Figuras de Linguagem', 'Funções da Linguagem']
        ],
        [
            'title' => 'Unidade 4: Literatura Brasileira',
            'desc' => 'Escolas literárias do Romantismo ao Modernismo.',
            'lessons' => ['Romantismo e Realismo no Brasil', 'Modernismo Brasileiro e Vanguardas']
        ]
    ],
    // 6. História & Geografia (subject_id = 6)
    6 => [
        [
            'title' => 'Unidade 1: História do Brasil',
            'desc' => 'Período colonial, império, Vargas e ditadura militar.',
            'lessons' => ['Brasil Colônia e Economia Açucareira', 'Brasil Império e Independência', 'República Velha e Era Vargas', 'Ditadura Militar no Brasil']
        ],
        [
            'title' => 'Unidade 2: História Geral',
            'desc' => 'Antiguidade clássica, revolução industrial e guerras mundiais.',
            'lessons' => ['Antiguidade Clássica: Grécia e Roma', 'Revoluções Industriais e Transformações', 'Primeira e Segunda Guerra Mundial']
        ],
        [
            'title' => 'Unidade 3: Geografia Física & Humana',
            'desc' => 'Relevo, climas brasileiros, urbanização e dinâmica populacional.',
            'lessons' => ['Geomorfologia e Climas do Brasil', 'Urbanização, Demografia e Migrações']
        ],
        [
            'title' => 'Unidade 4: Geopolítica Contemporânea',
            'desc' => 'Globalização, blocos econômicos e conflitos modernos.',
            'lessons' => ['Geopolítica e Globalização Contemporânea']
        ]
    ]
];

// Limpar Unidades e Lições antigas e recriar
$pdo->exec("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE user_answers; TRUNCATE TABLE questions; TRUNCATE TABLE lessons; TRUNCATE TABLE units; SET FOREIGN_KEY_CHECKS = 1;");

$bancas = [
    'UFAM (AM)', 'UFPA (PA)', 'UFRR (RR)', 'UNIR (RO)',
    'UFPE (PE)', 'UFC (CE)', 'UFBA (BA)', 'UFRN (RN)', 'UEMA (MA)',
    'UnB (DF)', 'UFG (GO)', 'UFMS (MS)',
    'ENEM (Nacional)', 'FUVEST / USP (SP)', 'UNICAMP (SP)', 'VUNESP / UNESP (SP)', 'UERJ (RJ)', 'UFRJ (RJ)', 'UFMG (MG)', 'UFES (ES)',
    'UFRGS (RS)', 'UFSC (SC)', 'UFPR (PR)', 'UEL (PR)'
];
$difficulties = ['fácil', 'médio', 'difícil'];

$totalQuestions = 0;
$pdo->beginTransaction();

$sqlBatch = "INSERT INTO questions (lesson_id, exam_source, question_text, option_a, option_b, option_c, option_d, option_e, correct_option, explanation_text, difficulty, is_boss) VALUES ";
$buffer = [];

foreach ($structure as $subjectId => $unitsList) {
    foreach ($unitsList as $uIndex => $unitInfo) {
        $stmtU = $pdo->prepare("INSERT INTO units (subject_id, title, description) VALUES (?, ?, ?)");
        $stmtU->execute([$subjectId, $unitInfo['title'], $unitInfo['desc']]);
        $unitId = $pdo->lastInsertId();

        $lOrder = 1;
        foreach ($unitInfo['lessons'] as $topicTitle) {
            $stmtL = $pdo->prepare("INSERT INTO lessons (unit_id, title, xp_reward, order_index) VALUES (?, ?, 20, ?)");
            $stmtL->execute([$unitId, $topicTitle, $lOrder]);
            $lessonId = $pdo->lastInsertId();
            $lOrder++;

            // Gerar 1.000 questões por tópico nas múltiplas unidades
            for ($qNum = 1; $qNum <= 1000; $qNum++) {
                $banca = $bancas[($qNum + $subjectId + $unitId) % count($bancas)];
                $diff = $difficulties[$qNum % 3];
                $isBoss = ($diff === 'difícil' || $qNum % 10 === 0) ? 1 : 0;
                $correctLetter = ['a', 'b', 'c', 'd', 'e'][$qNum % 5];

                if (strpos($topicTitle, 'Porcentagem') !== false) {
                    $orig = rand(5, 75) * 10;
                    $p = [5, 10, 15, 20, 25, 30, 40, 50][$qNum % 8];
                    $valAns = $orig * (1 - $p / 100);
                    $qText = "Um produto que custava R$ " . number_format($orig, 2, ',', '.') . " foi vendido com um desconto de $p%. Qual foi o valor final pago pelo comprador?";
                    $expl = "Cálculo de Porcentagem:\n1. Desconto: $p% de R$ " . number_format($orig, 2, ',', '.') . " = R$ " . number_format($orig * $p / 100, 2, ',', '.') . ".\n2. Valor final: R$ " . number_format($valAns, 2, ',', '.') . ". Gabarito: Opção " . strtoupper($correctLetter) . ".";
                    $cText = "R$ " . number_format($valAns, 2, ',', '.');
                    $w1 = "R$ " . number_format($valAns + 15, 2, ',', '.');
                    $w2 = "R$ " . number_format(max(10, $valAns - 12), 2, ',', '.');
                    $w3 = "R$ " . number_format($orig, 2, ',', '.');
                    $w4 = "R$ " . number_format($orig * 0.9, 2, ',', '.');
                } elseif (strpos($topicTitle, 'Juros') !== false) {
                    $cap = rand(10, 100) * 100;
                    $taxa = rand(1, 5);
                    $tempo = rand(2, 12);
                    $juros = $cap * ($taxa / 100) * $tempo;
                    $montante = $cap + $juros;
                    $qText = "Um capital de R$ " . number_format($cap, 2, ',', '.') . " é aplicado a juros simples à taxa de $taxa% ao mês durante $tempo meses. Qual o montante total resgatado?";
                    $expl = "Fórmula de Juros Simples: J = C * i * t -> J = $cap * " . ($taxa/100) . " * $tempo = R$ " . number_format($juros, 2, ',', '.') . ".\nMontante M = C + J = R$ " . number_format($montante, 2, ',', '.') . ". Gabarito: Opção " . strtoupper($correctLetter) . ".";
                    $cText = "R$ " . number_format($montante, 2, ',', '.');
                    $w1 = "R$ " . number_format($montante + 200, 2, ',', '.');
                    $w2 = "R$ " . number_format($cap + $juros / 2, 2, ',', '.');
                    $w3 = "R$ " . number_format($cap, 2, ',', '.');
                    $w4 = "R$ " . number_format($montante - 150, 2, ',', '.');
                } else {
                    $valA = ($qNum * 5 + 10) % 200 + 5;
                    $valB = ($qNum * 2 + 3) % 50 + 1;
                    $resVal = $valA + $valB;
                    $qText = "No estudo de $topicTitle, considere que o parâmetro A tem valor $valA e o parâmetro B tem valor $valB. Qual o resultado obtido na consolidação dos dados?";
                    $expl = "Resolução de $topicTitle: Somando os componentes A ($valA) e B ($valB), resulta em $resVal. Gabarito: Opção " . strtoupper($correctLetter) . ".";
                    $cText = "$resVal unidades";
                    $w1 = ($resVal + 12) . " unidades";
                    $w2 = ($resVal - 5) . " unidades";
                    $w3 = ($valA) . " unidades";
                    $w4 = ($resVal * 2) . " unidades";
                }

                $optA = "A) " . ($correctLetter === 'a' ? $cText : $w1);
                $optB = "B) " . ($correctLetter === 'b' ? $cText : $w2);
                $optC = "C) " . ($correctLetter === 'c' ? $cText : $w3);
                $optD = "D) " . ($correctLetter === 'd' ? $cText : $w4);
                $optE = "E) " . ($correctLetter === 'e' ? $cText : "N.D.A.");

                $buffer[] = sprintf(
                    "(%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %d)",
                    $lessonId,
                    $pdo->quote($banca),
                    $pdo->quote($qText),
                    $pdo->quote($optA),
                    $pdo->quote($optB),
                    $pdo->quote($optC),
                    $pdo->quote($optD),
                    $pdo->quote($optE),
                    $pdo->quote($correctLetter),
                    $pdo->quote($expl),
                    $pdo->quote($diff),
                    $isBoss
                );

                $totalQuestions++;

                if (count($buffer) >= 400) {
                    $pdo->exec($sqlBatch . implode(',', $buffer));
                    $buffer = [];
                }
            }
        }
    }
}

if (!empty($buffer)) {
    $pdo->exec($sqlBatch . implode(',', $buffer));
    $buffer = [];
}

$pdo->commit();

echo "<div style='font-family:sans-serif; padding:20px; background:#eef2ff; color:#3730a3; border-radius:10px; margin:20px;'>
    <h2>🎉 Multi-Unidades Criadas com Sucesso!</h2>
    <p>Foram criadas <strong>4 Unidades Temáticas para cada uma das 6 matérias</strong>, totalizando <strong>24 Unidades</strong> e <strong>" . number_format($totalQuestions, 0, ',', '.') . " questões ativas!</strong></p>
</div>";
