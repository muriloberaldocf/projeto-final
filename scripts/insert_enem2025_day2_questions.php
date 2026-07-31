<?php
require_once __DIR__ . '/../config/db.php';

echo "Iniciando a inserção das questões do ENEM 2025 (2º Dia - Caderno 8 Verde)...\n";

$lessonsMap = $pdo->query("SELECT l.id, l.title, s.name as subject_name FROM lessons l JOIN units u ON l.unit_id = u.id JOIN subjects s ON u.subject_id = s.id")->fetchAll(PDO::FETCH_ASSOC);

function findLessonIdDay2($lessonsMap, $subjectKeyword, $titleKeyword) {
    foreach ($lessonsMap as $l) {
        if ((mb_stripos($l['subject_name'], $subjectKeyword) !== false || mb_stripos($subjectKeyword, $l['subject_name']) !== false) && 
            (mb_stripos($l['title'], $titleKeyword) !== false || mb_stripos($titleKeyword, $l['title']) !== false)) {
            return $l['id'];
        }
    }
    // Fallback por matéria
    foreach ($lessonsMap as $l) {
        if (mb_stripos($l['subject_name'], $subjectKeyword) !== false) {
            return $l['id'];
        }
    }
    return 121; // Default para matemática/geometria
}

$questionsEnem2025Day2 = [
    // QUESTÃO 91 (Química Orgânica / Reações)
    [
        'lesson_id' => findLessonIdDay2($lessonsMap, 'Química', 'Orgânica') ?: 141,
        'exam_source' => 'ENEM 2025 - 2º DIA (VERDE)',
        'question_text' => "O líquido da casca da castanha de caju (LCC) representa aproximadamente 25% da massa da castanha. Quando submetido a altas temperaturas, o ácido anacárdico presente no LCC é convertido a cardanol com liberação de CO2, conforme o esquema:\nÁcido Anacárdico --(180°C a 200°C)--> Cardanol + CO2.\n\nO LCC técnico é produzido por meio de uma reação orgânica do tipo:",
        'option_a' => 'hidrólise.',
        'option_b' => 'fenilação.',
        'option_c' => 'esterificação.',
        'option_d' => 'hidrogenação.',
        'option_e' => 'descarboxilação.',
        'correct_option' => 'e',
        'difficulty' => 'fácil',
        'explanation_text' => "A conversão do ácido anacárdico em cardanol ocorre pela eliminação do grupo carboxila (-COOH) na forma de dióxido de carbono (CO2), caracterizando uma reação de descarboxilação térmica."
    ],

    // QUESTÃO 92 (Físico-Química / Química Nuclear)
    [
        'lesson_id' => findLessonIdDay2($lessonsMap, 'Química', 'Termoquímica') ?: 139,
        'exam_source' => 'ENEM 2025 - 2º DIA (VERDE)',
        'question_text' => "A radiação emitida pelo cobalto-60 é utilizada na medicina no tratamento do câncer. Esse radioisótopo tem um tempo de meia-vida de 5,3 anos. Considere um frasco com uma amostra contendo 2,00 mg de cobalto-60, armazenado durante um período de 26,5 anos.\n\nA massa de cobalto-60, em miligrama, que restará ao final desse tempo é mais próxima de:",
        'option_a' => '2,00 mg.',
        'option_b' => '1,00 mg.',
        'option_c' => '0,40 mg.',
        'option_d' => '0,13 mg.',
        'option_e' => '0,06 mg.',
        'correct_option' => 'e',
        'difficulty' => 'médio',
        'explanation_text' => "O número de meias-vidas decorridas é n = 26,5 / 5,3 = 5 meias-vidas. A cada meia-vida, a massa cai pela metade: 2,00 -> 1,00 -> 0,50 -> 0,25 -> 0,125 -> 0,0625 mg (~ 0,06 mg)."
    ],

    // QUESTÃO 94 (Biologia / Genética e Biotecnologia)
    [
        'lesson_id' => findLessonIdDay2($lessonsMap, 'Biologia', 'Biotecnologia') ?: 148,
        'exam_source' => 'ENEM 2025 - 2º DIA (VERDE)',
        'question_text' => "Golden Rice, ou arroz dourado, é uma variedade de arroz enriquecida em betacaroteno, precursor da vitamina A. Foi desenvolvida por engenharia genética para ajudar a combater a doença decorrente da deficiência dessa vitamina.\n\nEsse alimento contribui para diminuir a carência associada a qual doença?",
        'option_a' => 'Hemofilia.',
        'option_b' => 'Escorbuto.',
        'option_c' => 'Raquitismo.',
        'option_d' => 'Cegueira noturna.',
        'option_e' => 'Anemia perniciosa.',
        'correct_option' => 'd',
        'difficulty' => 'fácil',
        'explanation_text' => "A vitamina A (retinol) é essencial para a síntese de pigmentos visuais na retina. A sua deficiência causa a nictalopia ou cegueira noturna (e xeroftalmia)."
    ],

    // QUESTÃO 100 (Física / Eletrodinâmica)
    [
        'lesson_id' => findLessonIdDay2($lessonsMap, 'Física', 'Circuitos') ?: 134,
        'exam_source' => 'ENEM 2025 - 2º DIA (VERDE)',
        'question_text' => "Uma régua elétrica ligada a 110 V suporta no máximo 20 A (potência máxima = 2200 W) antes que seu fusível se rompa. Um computador (250 W) e um ar-condicionado portátil (1100 W) já estão ligados permanentemente (soma = 1350 W). A estudante tenta ligar, um de cada vez, na ordem:\n1º Impressora laser (660 W -> soma 2010 W)\n2º Cafeteira (900 W -> soma 2250 W)\n3º Luminária LED (5 W)\n4º Secador de cabelos (750 W).\n\nQuantas atividades a estudante conseguiu realizar antes de queimar o fusível?",
        'option_a' => '4',
        'option_b' => '3',
        'option_c' => '2',
        'option_d' => '1',
        'option_e' => '0',
        'correct_option' => 'd',
        'difficulty' => 'médio',
        'explanation_text' => "Potência máxima = 110 V x 20 A = 2200 W. Carga inicial = 1350 W. Ao tentar a 1ª atividade (impressora +660 W), a potência vai a 2010 W <= 2200 W (Sucesso!). Ao tentar a 2ª (cafeteira +900 W), a potência iria a 2250 W > 2200 W, queimando o fusível. Portanto, realizou apenas 1 atividade com sucesso."
    ],

    // QUESTÃO 105 (Física / Termodinâmica & Meio Ambiente)
    [
        'lesson_id' => findLessonIdDay2($lessonsMap, 'Física', 'Calorimetria') ?: 131,
        'exam_source' => 'ENEM 2025 - 2º DIA (VERDE)',
        'question_text' => "As usinas termonucleares utilizam água de refrigeração que, se descartada ineficientemente no meio ambiente sem o devido resfriamento, causa poluição térmica em rios e lagos.\n\nPara o ecossistema aquático, a ineficiência do sistema de água de refrigeração tem como consequência a:",
        'option_a' => 'diminuição do pH.',
        'option_b' => 'liberação de gases poluentes.',
        'option_c' => 'contaminação por combustíveis.',
        'option_d' => 'liberação de elementos radioativos.',
        'option_e' => 'diminuição da solubilidade do gás oxigênio.',
        'correct_option' => 'e',
        'difficulty' => 'fácil',
        'explanation_text' => "A elevação da temperatura da água (poluição térmica) reduz a solubilidade dos gases nela dissolvidos, incluindo o oxigênio (O2), prejudicando a respiração dos organismos aquáticos."
    ],

    // QUESTÃO 115 (Física / Ondulatória & Acústica)
    [
        'lesson_id' => findLessonIdDay2($lessonsMap, 'Física', 'Óptica') ?: 132,
        'exam_source' => 'ENEM 2025 - 2º DIA (VERDE)',
        'question_text' => "Uma tirinha ilustra uma onda sonora produzida pela Mônica que causa a quebra de taças de cristal. O fenômeno ondulatório que provoca a quebra das taças só é possível em razão de uma característica da voz da Mônica naquele momento.\n\nEsse fenômeno e a característica associada à voz da Mônica são, respectivamente:",
        'option_a' => 'reflexão e comprimento de onda.',
        'option_b' => 'ressonância e frequência.',
        'option_c' => 'interferência e velocidade.',
        'option_d' => 'ressonância e timbre.',
        'option_e' => 'reflexão e amplitude.',
        'correct_option' => 'b',
        'difficulty' => 'fácil',
        'explanation_text' => "A quebra de objetos de cristal por emissão sonora ocorre pelo fenômeno da RESSONÂNCIA, que exige que a FREQUÊNCIA da onda sonora emitida coincida com a frequência natural de vibração do objeto."
    ],

    // QUESTÃO 119 (Física / Hidrostática)
    [
        'lesson_id' => findLessonIdDay2($lessonsMap, 'Física', 'Hidrostática') ?: 129,
        'exam_source' => 'ENEM 2025 - 2º DIA (VERDE)',
        'question_text' => "A laje de um depósito de bebidas tem 50 m2 de área útil e foi projetada para suportar pressões de até 10^4 Pa (10.000 N/m2). O gerente pretende armazenar um produto cuja densidade é d = 1 250 kg/m3. Considere g = 10 m/s2.\n\nA altura máxima (h), em metro, de empilhamento do produto que essa laje é capaz de suportar é:",
        'option_a' => '0,16 m.',
        'option_b' => '0,50 m.',
        'option_c' => '0,80 m.',
        'option_d' => '1,60 m.',
        'option_e' => '8,00 m.',
        'correct_option' => 'c',
        'difficulty' => 'médio',
        'explanation_text' => "A pressão hidrostática/efetiva é dada por P = d x g x h. Substituindo os valores: 10.000 = 1250 x 10 x h => 10.000 = 12.500 x h => h = 10.000 / 12.500 = 0,80 m."
    ],

    // QUESTÃO 131 (Física / Eletromagnetismo)
    [
        'lesson_id' => findLessonIdDay2($lessonsMap, 'Física', 'Circuitos') ?: 134,
        'exam_source' => 'ENEM 2025 - 2º DIA (VERDE)',
        'question_text' => "O aquecimento em fogões por indução utiliza bobinas para produzir um campo magnético variável abaixo do vidro cerâmico. O mecanismo aquece apenas a panela de metal que se encontra na zona de cozimento.\n\nO uso do campo magnético variável tem a finalidade de:",
        'option_a' => 'imantar o material da panela por indução.',
        'option_b' => 'movimentar os átomos de ferro concentrados no fundo da panela.',
        'option_c' => 'emitir radiação eletromagnética, aquecendo a panela através do vidro.',
        'option_d' => 'induzir corrente elétrica na parte inferior da panela, aquecendo-a por efeito Joule.',
        'option_e' => 'gerar um fluxo de corrente de convecção no ar contido entre a bobina e o vidro.',
        'correct_option' => 'd',
        'difficulty' => 'médio',
        'explanation_text' => "Pela Lei da Indução de Faraday, um campo magnético variável induz correntes elétricas parasitas (correntes de Foucault) na base metálica da panela, gerando calor por Efeito Joule."
    ],

    // QUESTÃO 137 (Matemática / Regra de Três Composta)
    [
        'lesson_id' => findLessonIdDay2($lessonsMap, 'Matemática', 'Equações') ?: 123,
        'exam_source' => 'ENEM 2025 - 2º DIA (VERDE)',
        'question_text' => "Uma fábrica de tijolos ecológicos com 3 funcionários, cada um trabalhando 6 horas diárias, produz 720 unidades por dia. A fábrica passou a ter 5 funcionários trabalhando 9 horas por dia.\n\nO número de tijolos fabricados diariamente após o aumento da capacidade de produção é:",
        'option_a' => '800.',
        'option_b' => '1 080.',
        'option_c' => '1 200.',
        'option_d' => '1 800.',
        'option_e' => '2 520.',
        'correct_option' => 'd',
        'difficulty' => 'fácil',
        'explanation_text' => "Produção por homem-hora = 720 / (3 x 6) = 720 / 18 = 40 tijolos/hora. Nova capacidade = 5 funcionais x 9 horas x 40 = 45 x 40 = 1 800 tijolos."
    ],

    // QUESTÃO 147 (Matemática / Geometria Plana)
    [
        'lesson_id' => findLessonIdDay2($lessonsMap, 'Matemática', 'Geometria Plana') ?: 121,
        'exam_source' => 'ENEM 2025 - 2º DIA (VERDE)',
        'question_text' => "No entorno de uma lagoa circular de raio R = 1 km (1.000 m), há uma ciclovia. A prefeitura alocará policiais na ciclovia de forma que qualquer ponto esteja a no máximo 200 m de um policial (cada policial cobre 200 m para cada lado, totalizando 400 m de alcance por policial). Use pi = 3.\n\nA quantidade mínima de policiais necessários para patrulhar toda a ciclovia é:",
        'option_a' => '4.',
        'option_b' => '8.',
        'option_c' => '15.',
        'option_d' => '30.',
        'option_e' => '60.',
        'correct_option' => 'c',
        'difficulty' => 'médio',
        'explanation_text' => "Comprimento da ciclovia C = 2 x pi x R = 2 x 3 x 1.000 = 6.000 m. Cada policial cobre 2 x 200 = 400 m de extensão da ciclovia. Número mínimo de policiais = 6.000 / 400 = 15 policiais."
    ],

    // QUESTÃO 150 (Matemática / Estatística - Mediana)
    [
        'lesson_id' => findLessonIdDay2($lessonsMap, 'Matemática', 'Estatística') ?: 126,
        'exam_source' => 'ENEM 2025 - 2º DIA (VERDE)',
        'question_text' => "Uma empresa de internet adotará como novo padrão a MEDIANA das velocidades de conexão de 10 cidades (em MB/s):\nC1: 390, C2: 380, C3: 320, C4: 390, C5: 340, C6: 380, C7: 390, C8: 400, C9: 350, C10: 360.\n\nA velocidade de referência em MB/s adotada será:",
        'option_a' => '360.',
        'option_b' => '370.',
        'option_c' => '380.',
        'option_d' => '390.',
        'option_e' => '400.',
        'correct_option' => 'c',
        'difficulty' => 'fácil',
        'explanation_text' => "Ordenando os 10 valores em rol crescente: 320, 340, 350, 360, 380, 380, 390, 390, 390, 400. Como temos 10 elementos (par), a mediana é a média aritmética dos termos centrais (5º e 6º elementos): (380 + 380) / 2 = 380 MB/s."
    ],

    // QUESTÃO 161 (Matemática / Conversão de Unidades)
    [
        'lesson_id' => findLessonIdDay2($lessonsMap, 'Matemática', 'Geometria Espacial') ?: 122,
        'exam_source' => 'ENEM 2025 - 2º DIA (VERDE)',
        'question_text' => "O dono de uma sorveteria armazena sorvete em potes de 20 000 cm3. Ele serve o sorvete em taças com porções de 250 mL.\n\nA quantidade de taças que ele consegue servir a partir de um pote cheio é:",
        'option_a' => '5.',
        'option_b' => '8.',
        'option_c' => '50.',
        'option_d' => '80.',
        'option_e' => '800.',
        'correct_option' => 'd',
        'difficulty' => 'fácil',
        'explanation_text' => "Sabendo que 1 cm3 = 1 mL, o volume total do pote é 20.000 mL. Dividindo pelo volume de cada taça: 20.000 mL / 250 mL = 80 taças."
    ]
];

$stmt = $pdo->prepare("INSERT INTO questions (lesson_id, exam_source, question_text, option_a, option_b, option_c, option_d, option_e, correct_option, difficulty, explanation_text) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$inserted = 0;
foreach ($questionsEnem2025Day2 as $q) {
    $stmt->execute([
        $q['lesson_id'],
        $q['exam_source'],
        $q['question_text'],
        $q['option_a'],
        $q['option_b'],
        $q['option_c'],
        $q['option_d'],
        $q['option_e'],
        $q['correct_option'],
        $q['difficulty'],
        $q['explanation_text']
    ]);
    $inserted++;
}

echo "Sucesso! Inseridas {$inserted} questões reais do ENEM 2025 - 2º DIA no banco de dados!\n";
