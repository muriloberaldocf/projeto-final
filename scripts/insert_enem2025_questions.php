<?php
require_once __DIR__ . '/../config/db.php';

echo "Iniciando a inserção das questões do ENEM 2025 (Caderno 1 - Azul)...\n";

// Mapeamento de matérias para encontrar lições apropriadas
$lessonsMap = $pdo->query("SELECT l.id, l.title, s.name as subject_name FROM lessons l JOIN units u ON l.unit_id = u.id JOIN subjects s ON u.subject_id = s.id")->fetchAll(PDO::FETCH_ASSOC);

function findLessonId($lessonsMap, $subjectKeyword, $titleKeyword) {
    foreach ($lessonsMap as $l) {
        if (mb_stripos($l['subject_name'], $subjectKeyword) !== false || mb_stripos($l['title'], $titleKeyword) !== false) {
            return $l['id'];
        }
    }
    return 1; // Default
}

$questionsEnem2025 = [
    // QUESTÃO 04 (Inglês)
    [
        'lesson_id' => findLessonId($lessonsMap, 'Língua', 'Gramática') ?: 151,
        'exam_source' => 'ENEM 2025 - 1º DIA (AZUL)',
        'question_text' => "It is true that all children are special, simply because they are children. But most adults are not special, and children end up as adults pretty quickly... The shock of this may account for the emergence of the \"snowflake generation\" of university students, who are so delicate they can’t handle controversial ideas... Resilience is not about feeding ego — telling your children how wonderful they are — but strengthening it.\n\nNesse texto, a expressão \"snowflake generation\" é usada para:",
        'option_a' => 'abordar obstáculos impostos a universitários.',
        'option_b' => 'destacar mensagens de incentivo a estudantes.',
        'option_c' => 'estimular ações proativas em situações de emergência.',
        'option_d' => 'retratar relações conflituosas em ambiente universitário.',
        'option_e' => 'apontar posturas de uma juventude avessa a contrariedades.',
        'correct_option' => 'e',
        'difficulty' => 'médio',
        'explanation_text' => "A expressão 'snowflake generation' (geração floco de neve) é utilizada no texto para criticar a hipersensibilidade da juventude moderna, que tem dificuldade de lidar com ideias controversas e frustrações."
    ],

    // QUESTÃO 07 (Linguagens / Crônica)
    [
        'lesson_id' => findLessonId($lessonsMap, 'Língua', 'Funções') ?: 155,
        'exam_source' => 'ENEM 2025 - 1º DIA (AZUL)',
        'question_text' => "No texto \"De próprio punho\", de Ana Elisa Ribeiro, a autora relata o hábito cotidiano de escrever bilhetes em post-its na geladeira e reflete sobre a transição do manuscrito para o mundo digital ('do punho ao pixel').\n\nO elemento que caracteriza esse texto como uma crônica é a:",
        'option_a' => 'defesa das opiniões da autora sobre um tema de interesse coletivo.',
        'option_b' => 'exposição sobre o uso de tecnologias nas práticas de escrita atuais.',
        'option_c' => 'abordagem de fatos do contexto pessoal em uma perspectiva reflexiva.',
        'option_d' => 'utilização de recursos linguísticos para a interlocução direta com o leitor.',
        'option_e' => 'apresentação de acontecimentos segundo a ordem de sucessão no tempo.',
        'correct_option' => 'c',
        'difficulty' => 'médio',
        'explanation_text' => "A crônica se caracteriza pelo olhar atento a fatos cotidianos e pessoais, transformando episódios simples do dia a dia em reflexões profundas sobre a sociedade e o comportamento humano."
    ],

    // QUESTÃO 11 (Literatura / Romantismo)
    [
        'lesson_id' => findLessonId($lessonsMap, 'Língua', 'Romantismo') ?: 156,
        'exam_source' => 'ENEM 2025 - 1º DIA (AZUL)',
        'question_text' => "— Vejo, disse ele com algum acanhamento, que o doutor não é nenhum pé-rapado, mas nunca é bom facilitar... Minha filha Inocência fez 18 anos pelo Natal... tratei logo de casá-la. (TAUNAY, A. d’E. Inocência).\n\nNesse trecho, ao se referir à sua filha, o pai de Inocência reproduz os ideais românticos, presentes na:",
        'option_a' => 'valorização do ambiente rural na formação moral da mulher.',
        'option_b' => 'figura decorativa da mulher ante o protagonismo masculino.',
        'option_c' => 'equivalência de origem social para a harmonia do casal.',
        'option_d' => 'importância do dote como condição para o casamento.',
        'option_e' => 'aura de mistério sobre a identidade da jovem.',
        'correct_option' => 'b',
        'difficulty' => 'médio',
        'explanation_text' => "No romance regionalista romântico 'Inocência', a figura feminina é retratada em posição passiva e subordinada às decisões e arranjos patriarcais dos homens da família."
    ],

    // QUESTÃO 16 (Literatura / Simbolismo)
    [
        'lesson_id' => findLessonId($lessonsMap, 'Língua', 'Modernismo') ?: 157,
        'exam_source' => 'ENEM 2025 - 1º DIA (AZUL)',
        'question_text' => "Eu e tu, ante a noite e o amplo desdobramento / do mar, fero, a estourar de encontro à rocha nua... / Um símbolo descubro aqui, neste momento / esta rocha, este mar... a minha vida e a tua. (MACHADO, G. Símbolos).\n\nNesse soneto, os traços da estética simbolista são resgatados pelo eu lírico ao:",
        'option_a' => 'rejeitar as emoções de “amor” e “mágoa”.',
        'option_b' => 'expressar a dubiedade do olhar sobre o outro.',
        'option_c' => 'representar o “eu” e o “tu” como sujeitos volúveis.',
        'option_d' => 'associar a sua inconsciência a elementos da natureza.',
        'option_e' => 'metaforizar o conflito amoroso nas imagens de “mar” e “rocha”.',
        'correct_option' => 'e',
        'difficulty' => 'difícil',
        'explanation_text' => "O Simbolismo utiliza elementos da natureza (como o mar dinâmico e a rocha estática) como metáforas sugestivas dos estados de alma e conflitos sentimentais."
    ],

    // QUESTÃO 46 (Geografia / Geopolítica)
    [
        'lesson_id' => findLessonId($lessonsMap, 'Geografia', 'Geopolítica') ?: 116,
        'exam_source' => 'ENEM 2025 - 1º DIA (AZUL)',
        'question_text' => "Entrou em vigor, em 2019, o acordo que determina a mudança de nome da Macedônia para Macedônia do Norte. A troca põe fim ao impasse entre essa antiga república da Iugoslávia e a vizinha Grécia, que bloqueava sua entrada na União Europeia.\n\nPara o país originado da antiga Iugoslávia, a mudança de nome é uma estratégia política para:",
        'option_a' => 'criar a moeda própria.',
        'option_b' => 'proteger a cultura local.',
        'option_c' => 'subjugar a minoria étnica.',
        'option_d' => 'expandir o território nacional.',
        'option_e' => 'intensificar a integração regional.',
        'correct_option' => 'e',
        'difficulty' => 'fácil',
        'explanation_text' => "Ao resolver a disputa diplomática sobre o nome do país com a Grécia, a Macedônia do Norte destravou o processo de adesão aos blocos europeus (União Europeia e OTAN), intensificando a integração regional."
    ],

    // QUESTÃO 48 (Filosofia / Teoria do Conhecimento)
    [
        'lesson_id' => findLessonId($lessonsMap, 'História', 'Antiguidade') ?: 159,
        'exam_source' => 'ENEM 2025 - 1º DIA (AZUL)',
        'question_text' => "A credulidade dos ouvintes aumenta o descaramento do narrador... A eloquência, quando levada a seu patamar mais alto, deixa pouco lugar à razão ou à reflexão, mas, dirigindo-se inteiramente à imaginação e aos afetos, cativa os ouvintes. (HUME, D. Uma investigação sobre o entendimento humano).\n\nNo contexto do século XVIII, o autor propõe uma reflexão radical acerca da arte da eloquência, restringindo-a ao:",
        'option_a' => 'sistema de crenças, conforme a proposta kantiana de objetividade do conhecimento.',
        'option_b' => 'campo dos absolutos, semelhante ao entendimento medieval dos Universais.',
        'option_c' => 'domínio da lógica, consoante a compreensão aristotélica nos Analíticos.',
        'option_d' => 'paradigma da racionalidade, alinhado ao modelo cartesiano de método.',
        'option_e' => 'âmbito da persuasão, análogo às críticas platônicas aos sofistas.',
        'correct_option' => 'e',
        'difficulty' => 'difícil',
        'explanation_text' => "David Hume critica a eloquência apelativa e emocional, aproximando-se da crítica de Platão aos sofistas, que utilizavam a oratória não para buscar a verdade racional, mas para persuadir e manipular os afetos do público."
    ],

    // QUESTÃO 54 (Geografia / Demografia & Espaço Urbano)
    [
        'lesson_id' => findLessonId($lessonsMap, 'Geografia', 'Urbanização') ?: 114,
        'exam_source' => 'ENEM 2025 - 1º DIA (AZUL)',
        'question_text' => "A ideia de êxodo urbano assume ares caricaturais... Se o êxodo urbano for compreendido como aquisição de uma segunda residência ou retorno aos municípios menos povoados por uma elite com alta renda, trata-se da migração de pessoas cuja estabilidade permite simular a vida urbana metropolitana no interior.\n\nA crítica apresentada no texto evidencia uma dinâmica socioespacial marcada pela:",
        'option_a' => 'valorização de tradições rurais.',
        'option_b' => 'redução de plantações agrícolas.',
        'option_c' => 'estagnação de atividades comerciais.',
        'option_d' => 'precariedade de infraestruturas rodoviárias.',
        'option_e' => 'seletividade de deslocamentos populacionais.',
        'correct_option' => 'e',
        'difficulty' => 'médio',
        'explanation_text' => "O texto mostra que a migração das grandes metrópoles para áreas do interior não é um fenômeno homogêneo de massas, mas sim uma decisão seletiva restrita a grupos socioeconômicos de alta renda."
    ],

    // QUESTÃO 55 (Sociologia / Foucault)
    [
        'lesson_id' => findLessonId($lessonsMap, 'História', 'Revoluções') ?: 160,
        'exam_source' => 'ENEM 2025 - 1º DIA (AZUL)',
        'question_text' => "A 'invenção' dessa nova anatomia política encontra-se em funcionamento nos colégios, muito cedo; mais tarde, nas escolas primárias, no espaço hospitalar e na organização militar. (FOUCAULT, M. Vigiar e punir).\n\nO texto indica o seguinte aspecto da disciplina como ferramenta política:",
        'option_a' => 'Expansão das técnicas de suplício.',
        'option_b' => 'Judicialização das relações de poder.',
        'option_c' => 'Dissolução das distinções de nobreza.',
        'option_d' => 'Capilarização das práticas de controle.',
        'option_e' => 'Espetacularização das medidas de penitência.',
        'correct_option' => 'd',
        'difficulty' => 'médio',
        'explanation_text' => "Michel Foucault demonstra em 'Vigiar e Punir' como o poder disciplinar não atua apenas via Estado, mas se espalha e se capilariza através de instituições como escolas, quartéis e hospitais."
    ],

    // QUESTÃO 58 (História do Brasil / Império & Cidadania)
    [
        'lesson_id' => findLessonId($lessonsMap, 'História', 'Ditadura') ?: 158,
        'exam_source' => 'ENEM 2025 - 1º DIA (AZUL)',
        'question_text' => "Em 1872, havia mais de 1 milhão de votantes (13% da população livre). Em 1886, votaram nas eleições parlamentares pouco mais de 100 mil eleitores (0,8% da população total). Houve um corte de quase 90% do eleitorado após a reforma eleitoral de 1881 (Lei Saraiva).\n\nDe acordo com o texto, a participação no processo eleitoral brasileiro após a Reforma de 1881 sofreu uma variação que se explica pela:",
        'option_a' => 'restrição de gênero.',
        'option_b' => 'exclusão de imigrantes.',
        'option_c' => 'comprovação de domicílio.',
        'option_d' => 'exigência da alfabetização.',
        'option_e' => 'obrigatoriedade do sufrágio.',
        'correct_option' => 'd',
        'difficulty' => 'médio',
        'explanation_text' => "A Lei Saraiva (1881) introduziu a exigência da alfabetização para o exercício do voto no Brasil Império, o que excluiu a imensa maioria da população analfabeta e reduziu drasticamente o corpo eleitoral."
    ],

    // QUESTÃO 61 (Filosofia Política / Aristóteles)
    [
        'lesson_id' => findLessonId($lessonsMap, 'História', 'Antiguidade') ?: 159,
        'exam_source' => 'ENEM 2025 - 1º DIA (AZUL)',
        'question_text' => "O corpo de cidadãos é o poder supremo dos Estados... Sempre que o Um, a Minoria ou Todos governam tendo em vista o bem-estar comum, essas constituições são justas; mas se procuram apenas o benefício de uma das partes, estabelece-se um desvio. (ARISTÓTELES. Política).\n\nNo excerto encontra-se a base da teoria clássica das três formas puras de governo representadas pela:",
        'option_a' => 'tirania, oligarquia e república.',
        'option_b' => 'burocracia, autarquia e império.',
        'option_c' => 'ditadura, autocracia e anarquia.',
        'option_d' => 'plutocracia, tecnocracia e demagogia.',
        'option_e' => 'monarquia, aristocracia e democracia.',
        'correct_option' => 'e',
        'difficulty' => 'fácil',
        'explanation_text' => "Para Aristóteles, as três formas legítimas/puras de governo focadas no bem comum são: Monarquia (governo de um), Aristocracia (governo de poucos) e Democracia/Politéia (governo de todos)."
    ],

    // QUESTÃO 64 (História do Brasil / Era Vargas)
    [
        'lesson_id' => findLessonId($lessonsMap, 'História', 'Ditadura') ?: 158,
        'exam_source' => 'ENEM 2025 - 1º DIA (AZUL)',
        'question_text' => "A principal figura da oposição era o belicoso jornalista Carlos Lacerda. Se ao menos pudessem 'removê-lo' do cenário político, talvez Vargas se salvasse da situação. Esses seguidores decidiram tomar o assunto em suas próprias mãos com o atentado da Rua Tonelero. (SKIDMORE, T. Brasil: de Getúlio a Castelo).\n\nNesse contexto, a ação dos aliados de Getúlio Vargas teve como consequência imediata o(a):",
        'option_a' => 'intensificação dos ataques do grupo udenista.',
        'option_b' => 'intervenção dos sindicatos no conflito partidário.',
        'option_c' => 'mobilização da sociedade na defesa do governo.',
        'option_d' => 'abandono da censura aos meios de comunicação.',
        'option_e' => 'apoio dos parlamentares aos candidatos oposicionistas.',
        'correct_option' => 'a',
        'difficulty' => 'médio',
        'explanation_text' => "O atentado da Rua Tonelero (agosto de 1954), que vitimou o major Rubens Vaz e feriu Carlos Lacerda, acirrou violentamente a crise política do governo Vargas, provocando uma onda incontrolável de ataques da oposição udenista (UDN) e das Forças Armadas, culminando no suicídio de Getúlio."
    ],

    // QUESTÃO 86 (Geografia / Fontes de Energia & Sustentabilidade)
    [
        'lesson_id' => findLessonId($lessonsMap, 'Geografia', 'Energia') ?: 118,
        'exam_source' => 'ENEM 2025 - 1º DIA (AZUL)',
        'question_text' => "Pela primeira vez na história, o Brasil terá um prédio que usa energia do solo para climatizar seus ambientes. A energia geotérmica é aquela encontrada dentro da crosta terrestre, sendo transferida por processos de troca térmica a partir das fundações da edificação.\n\nPara o sistema elétrico brasileiro, a expansão de tecnologias como a descrita no texto representa uma tendência de:",
        'option_a' => 'homogeneização da oferta da matriz produtiva.',
        'option_b' => 'ampliação do consumo em unidades residenciais.',
        'option_c' => 'redução da pressão sobre as usinas hidrelétricas.',
        'option_d' => 'estagnação do consumo em unidades industriais.',
        'option_e' => 'superação da necessidade de recursos renováveis.',
        'correct_option' => 'c',
        'difficulty' => 'médio',
        'explanation_text' => "O uso de energia geotérmica para climatização reduz a demanda por energia elétrica da rede convencional, aliviando a sobrecarga sobre a matriz hidroelétrica brasileira, especialmente durante períodos de estiagem."
    ]
];

$stmt = $pdo->prepare("INSERT INTO questions (lesson_id, exam_source, question_text, option_a, option_b, option_c, option_d, option_e, correct_option, difficulty, explanation_text) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$inserted = 0;
foreach ($questionsEnem2025 as $q) {
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

echo "Sucesso! Inseridas {$inserted} questões reais do ENEM 2025 no banco de dados!\n";
