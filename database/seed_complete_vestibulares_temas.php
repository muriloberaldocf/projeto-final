<?php
require_once __DIR__ . '/../config/db.php';

try {
    echo "=== ATUALIZANDO TABELA DE TEMAS DE REDAÇÃO ===" . PHP_EOL;

    // 1. Adicionar colunas se não existirem
    $cols = $pdo->query("SHOW COLUMNS FROM redacao_temas")->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array('banca', $cols)) {
        $pdo->exec("ALTER TABLE redacao_temas ADD COLUMN `banca` VARCHAR(50) DEFAULT 'ENEM' AFTER `origem`");
        echo "Coluna `banca` adicionada." . PHP_EOL;
    }
    if (!in_array('eixo_tematico', $cols)) {
        $pdo->exec("ALTER TABLE redacao_temas ADD COLUMN `eixo_tematico` VARCHAR(100) DEFAULT 'Cidadania & Sociedade' AFTER `banca`");
        echo "Coluna `eixo_tematico` adicionada." . PHP_EOL;
    }
    if (!in_array('genero_textual', $cols)) {
        $pdo->exec("ALTER TABLE redacao_temas ADD COLUMN `genero_textual` VARCHAR(50) DEFAULT 'Dissertativo-Argumentativo' AFTER `eixo_tematico`");
        echo "Coluna `genero_textual` adicionada." . PHP_EOL;
    }
    if (!in_array('dificuldade', $cols)) {
        $pdo->exec("ALTER TABLE redacao_temas ADD COLUMN `dificuldade` VARCHAR(20) DEFAULT 'Médio' AFTER `genero_textual`");
        echo "Coluna `dificuldade` adicionada." . PHP_EOL;
    }
    if (!in_array('orientacoes_especificas', $cols)) {
        $pdo->exec("ALTER TABLE redacao_temas ADD COLUMN `orientacoes_especificas` TEXT DEFAULT NULL AFTER `dificuldade`");
        echo "Coluna `orientacoes_especificas` adicionada." . PHP_EOL;
    }

    // 2. Limpar temas antigos para inserir a coleção completa e autêntica com textos motivadores detalhados
    $pdo->exec("TRUNCATE TABLE redacao_temas");

    $temas = [
        // ==================== ENEM ====================
        [
            'titulo' => 'Desafios para a valorização da herança africana no Brasil',
            'ano' => '2024',
            'origem' => 'ENEM',
            'banca' => 'ENEM',
            'eixo_tematico' => 'Cultura & Direitos Humanos',
            'genero_textual' => 'Dissertativo-Argumentativo',
            'dificuldade' => 'Médio',
            'orientacoes_especificas' => 'Elabore uma proposta de intervenção social que respeite os direitos humanos com os 5 elementos (Agente, Ação, Meio/Modo, Efeito e Detalhamento). Proibido título obrigatório.',
            'descricao' => 'A proposta exige reflexão sobre o apagamento histórico e a necessidade de reconhecimento e proteção das manifestações culturais, religiosas, linguísticas e materiais de matriz africana que fundaram a identidade brasileira.',
            'textos_motivadores' => "TEXTO I\nA cultura brasileira é profundamente enraizada nas contribuições dos povos escravizados trazidos da África durante mais de três séculos. Do vocabulário à gastronomia, da música à arquitetura colonial, a herança africana não é apenas parte da nossa história: ela é a própria base estruturante da civilização brasileira. No entanto, séculos de racismo estrutural tentaram inferiorizar e marginalizar essas contribuições.\n\nTEXTO II\nLei nº 10.639/2003: Estabelece a obrigatoriedade do ensino da história e cultura afro-brasileira nas escolas de ensino fundamental e médio. Mais de vinte anos após a sua promulgação, relatórios educacionais apontam que grande parte das redes públicas e privadas ainda enfrenta resistência curricular e falta de capacitação docente para implementar plenamente a legislação.\n\nTEXTO III\nManifestações religiosas de matriz africana, como o Candomblé e a Umbanda, continuam sendo os principais alvos de intolerância e violência no Brasil, respondendo por mais de 60% dos registros de crimes contra a liberdade de culto no país, evidenciando o estigma persistente contra a herança ancestral negra."
        ],
        [
            'titulo' => 'Desafios para o enfrentamento da invisibilidade do trabalho de cuidado realizado pela mulher no Brasil',
            'ano' => '2023',
            'origem' => 'ENEM',
            'banca' => 'ENEM',
            'eixo_tematico' => 'Sociedade & Gênero',
            'genero_textual' => 'Dissertativo-Argumentativo',
            'dificuldade' => 'Difícil',
            'orientacoes_especificas' => 'Aborde a dimensão econômica e social do cuidado, propondo intervenção concreta com os 5 elementos do ENEM.',
            'descricao' => 'Analisa a histórica sobrecarga feminina em tarefas domésticas, cuidados com crianças, idosos e pessoas com deficiência, desprovida de remuneração e de reconhecimento por políticas públicas.',
            'textos_motivadores' => "TEXTO I\nO trabalho de cuidado não remunerado é o motor invisível da economia mundial. Sem ele, trabalhadores não teriam alimentação, lares organizados ou suporte emocional para produzir. No Brasil, essa responsabilidade recai historicamente quase de forma exclusiva sobre os ombros das mulheres, que dedicam, em média, 21,3 horas semanais a afazeres domésticos e cuidados de pessoas, contra apenas 10,6 horas dedicadas pelos homens (IBGE, 2022).\n\nTEXTO II\nA divisão sexual do trabalho impõe uma dupla ou tripla jornada feminina. Mulheres que ingressam no mercado de trabalho formal enfrentam remunerações inferiores e são forçadas a abrir mão de estudos, carreiras ou descanso para manter a sustentação da família sem apoio de creches públicas e centros comunitários de apoio a idosos.\n\nTEXTO III\nA economia do cuidado precisa ser reconhecida como um bem público essencial, exigindo a implementação de um Sistema Nacional de Cuidados com creches em tempo integral, lavanderias comunitárias e políticas de licença parental equitativas."
        ],
        [
            'titulo' => 'Desafios para a valorização de comunidades e povos tradicionais no Brasil',
            'ano' => '2022',
            'origem' => 'ENEM',
            'banca' => 'ENEM',
            'eixo_tematico' => 'Meio Ambiente & Cidadania',
            'genero_textual' => 'Dissertativo-Argumentativo',
            'dificuldade' => 'Médio',
            'orientacoes_especificas' => 'Contemple a diversidade de povos tradicionais (indígenas, quilombolas, ribeirinhos) e a proteção ambiental constitucional.',
            'descricao' => 'Reflexão sobre os povos indígenas, quilombolas, ribeirinhos e ciganos, sua importância vital para a conservação da biodiversidade e as ameaças fundiárias sofridas.',
            'textos_motivadores' => "TEXTO I\nO Decreto Federal nº 6.040/2007 define povos e comunidades tradicionais como grupos culturalmente diferenciados e que se reconhecem como tais, possuindo formas próprias de organização social e ocupando territórios necessários à sua reprodução cultural, social e econômica.\n\nTEXTO II\nDados do MapBiomas comprovam que as Terras Indígenas e Territórios Quilombolas são as áreas mais preservadas de todo o território brasileiro, com taxas de desmatamento até dez vezes menores que áreas privadas adjacentes. Proteger os modos de vida tradicionais é preservar o equilíbrio climático do planeta.\n\nTEXTO III\nApesar de sua relevância ecológica e cultural, essas populações enfrentam conflitos com grileiros, garimpo ilegal, desmatamento clandestino e invisibilidade nos censos e serviços de saúde pública."
        ],
        [
            'titulo' => 'Invisibilidade e registro civil: garantia de acesso à cidadania no Brasil',
            'ano' => '2021',
            'origem' => 'ENEM',
            'banca' => 'ENEM',
            'eixo_tematico' => 'Cidadania & Direitos',
            'genero_textual' => 'Dissertativo-Argumentativo',
            'dificuldade' => 'Médio',
            'orientacoes_especificas' => 'Relacione o registro civil como pressuposto inegociável para a fruição de todos os direitos sociais.',
            'descricao' => 'A ausência de certidão de nascimento exclui cidadãos de serviços básicos (SUS, escolas, auxílios) gerando indivíduos juridicamente inexistentes.',
            'textos_motivadores' => "TEXTO I\nA certidão de nascimento é o primeiro documento que comprova a existência formal de uma pessoa perante a lei e o Estado. Sem ela, o indivíduo não existe para as estatísticas públicas, não pode ser matriculado regularmente em escolas, não obtém carteira de trabalho e fica impedido de acessar programas de transferência de renda.\n\nTEXTO II\nSegundo dados do IBGE e do Ministério da Justiça, cerca de 3 milhões de brasileiros não possuem certidão de nascimento. O sub-registro atinge principalmente populações ribeirinhas, indígenas, pessoas em situação de rua e comunidades isoladas pelo déficit de cartórios e altos custos de locomoção.\n\nTEXTO III\nLei nº 9.534/1997: Declara a gratuidade da primeira via da certidão de nascimento para todos os brasileiros, mas o desconhecimento da população e a burocracia administrativa mantêm barreiras invisíveis para a cidadania plena."
        ],
        [
            'titulo' => 'O estigma associado às doenças mentais na sociedade brasileira',
            'ano' => '2020',
            'origem' => 'ENEM',
            'banca' => 'ENEM',
            'eixo_tematico' => 'Saúde & Comportamento',
            'genero_textual' => 'Dissertativo-Argumentativo',
            'dificuldade' => 'Médio',
            'orientacoes_especificas' => 'Discuta o tabu social e a carência de políticas públicas como a Rede de Atenção Psicossocial (RAPS).',
            'descricao' => 'Aborda o preconceito social que rotula o sofrimento psíquico como fraqueza de caráter, dificultando a busca por acolhimento médico.',
            'textos_motivadores' => "TEXTO I\nA Organização Mundial da Saúde (OMS) aponta que o Brasil lidera os índices de ansiedade nas Américas e possui índices alarmantes de depressão. No entanto, o termo 'doença mental' ainda carrega forte preconceito, associado equivocadamente à loucura, incapacidade ou falta de força de vontade.\n\nTEXTO II\nO estigma impede que milhões de jovens e adultos busquem tratamento precoce nos Centros de Atenção Psicossocial (CAPS) do SUS por receio de julgamento no ambiente de trabalho e familiar, agravando quadros clínicos que poderiam ser controlados com acompanhamento multidisciplinar."
        ],
        [
            'titulo' => 'Democratização do acesso ao cinema no Brasil',
            'ano' => '2019',
            'origem' => 'ENEM',
            'banca' => 'ENEM',
            'eixo_tematico' => 'Cultura & Cidade',
            'genero_textual' => 'Dissertativo-Argumentativo',
            'dificuldade' => 'Médio',
            'orientacoes_especificas' => 'Explore o papel formador do cinema e a segregação espacial das salas de exibição.',
            'descricao' => 'Concentração de salas de cinema em shopping centers de áreas elitizadas, excluindo o interior e as periferias do consumo cultural audiovisual.',
            'textos_motivadores' => "TEXTO I\nO cinema não é apenas entretenimento; é reflexão estética, preservação de memória e formação crítica de cidadãos. Ele permite enxergar outras realidades e questionar a própria existência.\n\nTEXTO II\nDados da Ancine demonstram que mais de 80% dos municípios brasileiros não possuem nenhuma sala de cinema comercial. As salas existentes concentram-se predominantemente em shopping centers de capitais e centros metropolitanos, onde os ingressos e a alimentação têm preços proibitivos para famílias de baixa renda."
        ],

        // ==================== UNESP ====================
        [
            'titulo' => "A 'cultura do cancelamento': punição legítima ou linchamento virtual?",
            'ano' => '2024',
            'origem' => 'UNESP',
            'banca' => 'UNESP',
            'eixo_tematico' => 'Tecnologia & Ética',
            'genero_textual' => 'Dissertativo-Argumentativo',
            'dificuldade' => 'Difícil',
            'orientacoes_especificas' => 'Critérios VUNESP: Responda à pergunta provocativa com tese definida. Exige título. Não é obrigatória proposta de intervenção no modelo ENEM.',
            'descricao' => 'Avalia se o boicote virtual massivo funciona como ferramenta democrática de cobrança ética ou se converteu em intolerância desproporcional e tribunal inquisitório sem direito à ampla defesa.',
            'textos_motivadores' => "TEXTO I\nNas redes sociais contemporâneas, o termo 'cancelamento' surgiu como forma de retirar o poder de audiência e lucro de figuras públicas que cometeram atos racistas, misóginos ou homofóbicos. Defensores argumentam que, diante da lentidão da justiça tradicional, a reação coletiva nas redes é uma ferramenta democratizadora que dá voz aos oprimidos para cobrar ética e responsabilidade social de poderosos e corporações.\n\nTEXTO II\nCríticos da cultura do cancelamento apontam que a prática se transformou em linchamento virtual sumário e impiedoso. Não há espaço para o diálogo, a retratação ou o aprendizado com o erro. O algoritmo das plataformas lucra com o engajamento do ódio e da humilhação pública, destruindo reputações e vidas profissionais em questão de horas sem qualquer garantia de devido processo legal ou proporcionalidade."
        ],
        [
            'titulo' => 'A lógica do jogo na sociedade atual: a ludificação da vida cotidiana é um avanço ou uma armadilha?',
            'ano' => '2023',
            'origem' => 'UNESP',
            'banca' => 'UNESP',
            'eixo_tematico' => 'Sociedade & Comportamento',
            'genero_textual' => 'Dissertativo-Argumentativo',
            'dificuldade' => 'Difícil',
            'orientacoes_especificas' => 'Critérios VUNESP: Posicione-se criticamente sobre a gamificação das relações humanas e do trabalho. Título obrigatório.',
            'descricao' => 'Reflexão sobre como a gamificação (pontos, recompensas, rankings, metas instantâneas) foi aplicada ao trabalho, aos estudos e aos aplicativos, gerando engajamento ou dependência psicológica.',
            'textos_motivadores' => "TEXTO I\nA gamificação (ou ludificação) consiste em utilizar elementos de jogos digitais — como barras de progresso, emblemas, rankings e recompensas imediatas — em contextos não lúdicos, como educação, aplicativos de condicionamento físico e rotinas de produtividade no trabalho. Defensores destacam o aumento da motivação, do engajamento e a sensação de conquista do indivíduo.\n\nTEXTO II\nFilósofos e sociólogos contemporâneos alertam que a lógica do jogo transformou a vida em uma competição incessante por validação. O trabalho em aplicativos de entrega usa a gamificação para ocultar a precarização das jornadas, e redes sociais utilizam sistemas de recompensa dopaminérgica para criar vício psicológico nos usuários, tornando o lazer e a convivência reféns de pontuações artificiais."
        ],
        [
            'titulo' => 'Tristeza em tempos de felicidade compulsória',
            'ano' => '2022',
            'origem' => 'UNESP',
            'banca' => 'UNESP',
            'eixo_tematico' => 'Filosofia & Comportamento',
            'genero_textual' => 'Dissertativo-Argumentativo',
            'dificuldade' => 'Difícil',
            'orientacoes_especificas' => 'Critérios VUNESP: Explore a ditadura da positividade tóxica e o direito ao luto e à tristeza. Título criativo obrigatório.',
            'descricao' => 'Reflexão profunda sobre a proibição tácita da tristeza e da vulnerabilidade na sociedade hiperconectada e a cobrança contínua por sucesso e alegria nas vitrines sociais.',
            'textos_motivadores' => "TEXTO I\nO filósofo Byung-Chul Han, em 'Sociedade do Cansaço', observa que vivemos em uma era onde a dor e a tristeza foram banidas do espaço público. O sujeito contemporâneo é obrigado a performar positividade e vitória constante em suas redes sociais, transformando qualquer sinal de luto, angústia ou desânimo em um fracasso pessoal inaceitável.\n\nTEXTO II\nA psicanálise nos ensina que a tristeza, a frustração e a melancolia são sentimentos humanos naturais e fundamentais para a elaboração de perdas e o autoconhecimento. Negar a tristeza em nome de uma felicidade de consumo compulsória gera adoecimento mental em massa e solidão profunda."
        ],
        [
            'titulo' => 'Tempo é dinheiro? Uma reflexão sobre a aceleração do cotidiano moderno',
            'ano' => '2021',
            'origem' => 'UNESP',
            'banca' => 'UNESP',
            'eixo_tematico' => 'Filosofia & Economia',
            'genero_textual' => 'Dissertativo-Argumentativo',
            'dificuldade' => 'Médio',
            'orientacoes_especificas' => 'Critérios VUNESP: Discuta a máxima de Benjamin Franklin confrontando a produtividade capitalista com a qualidade do viver.',
            'descricao' => 'Questiona a mercantilização de cada minuto da existência humana e a perda da contemplação, do ócio criativo e do tempo compartilhado.',
            'textos_motivadores' => "TEXTO I\n'Lembra-te de que tempo é dinheiro', escreveu Benjamin Franklin em 1748. No coração da modernidade industrial e capitalista, a medição do tempo pelo relógio transformou cada segundo em mercadoria. Perder tempo passou a ser o maior dos pecados corporativos.\n\nTEXTO II\nNa sociedade digital hiperconectada, o tempo livre foi colonizado por notificações, e-mails corporativos fora do horário de expediente e consumo ininterrupto. O ser humano não consegue mais desacelerar sem sentir culpa, perdendo a capacidade de contemplação, reflexão filosófica e desfrute genuíno da existência."
        ],

        // ==================== FUVEST ====================
        [
            'titulo' => 'Educação básica: para além da utilidade econômica',
            'ano' => '2024',
            'origem' => 'FUVEST',
            'banca' => 'FUVEST',
            'eixo_tematico' => 'Educação & Filosofia',
            'genero_textual' => 'Dissertativo-Argumentativo',
            'dificuldade' => 'Difícil',
            'orientacoes_especificas' => 'Critérios FUVEST: Texto de alta densidade argumentativa e filosófica. Título obrigatório e autoral. Não use clichês ou propostas rasas.',
            'descricao' => 'A prova exige discutir a redução do ensino ao treinamento técnico para o mercado de trabalho versus a formação de cidadãos críticos, sensíveis e livres.',
            'textos_motivadores' => "TEXTO I\nCada vez mais, reformas educacionais em todo o planeta enfatizam matérias utilitárias voltadas à empregabilidade imediata, competências de gestão e tecnologias aplicadas, enquanto disciplinas humanísticas como Filosofia, Sociologia, Artes e Literatura são relegadas ao segundo plano por serem consideradas 'improdutivas' pelas métricas de mercado.\n\nTEXTO II\nPara pensadores como Martha Nussbaum, a educação para o lucro desumaniza o indivíduo. A verdadeira missão da educação básica é cultivar a imaginação narrativa, a empatia moral com o diferente e o pensamento crítico capaz de desafiar tradições injustas, formando cidadãos conscientes e não apenas engrenagens para a máquina produtiva."
        ],
        [
            'titulo' => 'Refugiados ambientais e a vulnerabilidade humana frente às catástrofes climáticas',
            'ano' => '2023',
            'origem' => 'FUVEST',
            'banca' => 'FUVEST',
            'eixo_tematico' => 'Meio Ambiente & Geopolítica',
            'genero_textual' => 'Dissertativo-Argumentativo',
            'dificuldade' => 'Difícil',
            'orientacoes_especificas' => 'Critérios FUVEST: Discuta a injustiça climática global e o vazio jurídico internacional sobre os migrantes do clima.',
            'descricao' => 'Avalia como secas, enchentes e o aumento do nível dos oceanos expulsam populações de suas terras sem que o direito internacional ofereça o status de refugiados.',
            'textos_motivadores' => "TEXTO I\nMilhões de pessoas já são forçadas a abandonar suas casas anualmente em razão de secas prolongadas, tufões devastadores e desertificação. A ONU estima que até 2050 cerca de 200 milhões de indivíduos se tornem migrantes forçados pelo colapso ecológico.\n\nTEXTO II\nA Convenção de Genebra de 1951 reconhece como refugiados apenas aqueles que sofrem perseguição política, étnica ou religiosa. Quem perde sua terra e subsistência para o clima não tem amparo legal para solicitar asilo em outros países, gerando uma crise humanitária de proporções inéditas."
        ],
        [
            'titulo' => 'As diferentes faces do riso na sociedade contemporânea',
            'ano' => '2022',
            'origem' => 'FUVEST',
            'banca' => 'FUVEST',
            'eixo_tematico' => 'Filosofia & Cultura',
            'genero_textual' => 'Dissertativo-Argumentativo',
            'dificuldade' => 'Difícil',
            'orientacoes_especificas' => 'Critérios FUVEST: Explore o riso como resistência, como opressão ou como fuga da realidade. Título obrigatório.',
            'descricao' => 'O riso pode ser arma subversiva contra o autoritarismo, mas também pode ser ferramenta de escárnio, humilhação e alienação do sofrimento.',
            'textos_motivadores' => "TEXTO I\nO riso é uma das manifestações humanas mais complexas. Ele pode ser libertador e revolucionário — como o riso carnavalesco e a sátira política que desnudam a prepotência dos governantes — mas também pode ser opressor, quando reforça estereótipos, humilha os vulneráveis e normaliza preconceitos através do humor depreciativo.\n\nTEXTO II\nEm tempos de crise e desesperança, o humor nas redes sociais em formato de memes serve tanto como mecanismo de defesa psicológica coletiva quanto como fuga cínica da ação política transformadora."
        ],

        // ==================== UNICAMP ====================
        [
            'titulo' => 'O impacto das apostas online (bets) e cassinos virtuais entre jovens brasileiros',
            'ano' => '2024',
            'origem' => 'UNICAMP',
            'banca' => 'UNICAMP',
            'eixo_tematico' => 'Tecnologia & Economia',
            'genero_textual' => 'Dissertativo-Argumentativo',
            'dificuldade' => 'Médio',
            'orientacoes_especificas' => 'Banca UNICAMP: Analise a ilusão de enriquecimento fácil, a publicidade de influenciadores e o endividamento de famílias.',
            'descricao' => 'Reflexão crítica sobre a proliferação desregulada de plataformas de apostas esportivas e jogos de azar online e o vício precoce entre adolescentes e jovens adultos.',
            'textos_motivadores' => "TEXTO I\nA legalização e explosão das apostas esportivas de quota fixa (as 'bets') no Brasil atraíram dezenas de milhões de apostadores em poucos anos. Promovidas massivamente por influenciadores digitais, as plataformas vendem a ilusão de renda rápida e fácil por meio de celulares.\n\nTEXTO II\nDados de saúde pública e do Banco Central revelam que famílias de baixa renda e beneficiários de programas sociais transferiram bilhões de reais para sites de apostas, comprometendo o orçamento de alimentação e gerando dependência patológica (ludopatia) especialmente em jovens de 16 a 24 anos."
        ],
        [
            'titulo' => 'A persistência do trabalho análogo à escravidão no século XXI: causas estruturais e caminhos de combate',
            'ano' => '2023',
            'origem' => 'UNICAMP',
            'banca' => 'UNICAMP',
            'eixo_tematico' => 'Direitos Humanos & Trabalho',
            'genero_textual' => 'Dissertativo-Argumentativo',
            'dificuldade' => 'Médio',
            'orientacoes_especificas' => 'Banca UNICAMP: Discuta a exploração no agronegócio e na indústria têxtil urbana, apontando a omissão fiscalizatória.',
            'descricao' => 'Como mais de 130 anos após a abolição formal, trabalhadores brasileiros e imigrantes continuam sendo resgatados de condições degradantes, servidão por dívida e jornadas exaustivas.',
            'textos_motivadores' => "TEXTO I\nO artigo 149 do Código Penal brasileiro define trabalho análogo à escravidão por quatro elementos: trabalhos forçados, jornada exaustiva, condições degradantes de alojamento/trabalho e servidão por dívidas. Em 2023, o número de trabalhadores resgatados bateu recordes históricos em vinícolas, fazendas de cana e confecções urbanas.\n\nTEXTO II\nA vulnerabilidade econômica e a falta de oportunidades empurram trabalhadores migrantes para intermediários fraudulentos ('gatos'), demonstrando que o problema não é arcaico, mas uma engrenagem moderna de redução ilegal de custos em cadeias produtivas globais."
        ],

        // ==================== UERJ ====================
        [
            'titulo' => 'O papel da desobediência civil diante de leis injustas e tiranias',
            'ano' => '2024',
            'origem' => 'UERJ',
            'banca' => 'UERJ',
            'eixo_tematico' => 'Filosofia & Política',
            'genero_textual' => 'Dissertativo-Argumentativo',
            'dificuldade' => 'Difícil',
            'orientacoes_especificas' => 'Critérios UERJ: Desenvolva uma reflexão consistente ancorada no livro indicado (O Alienista, de Machado de Assis) ou em autores da filosofia política.',
            'descricao' => 'Quando a autoridade legal extrapola os limites da ética e do bem comum, a desobediência pacífica é um dever moral do cidadão?',
            'textos_motivadores' => "TEXTO I\nEm 'O Alienista', de Machado de Assis, Simão Bacamarte interna na Casa Verde quatro quintos da população de Itaguaí sob o pretexto da pureza científica da psiquiatria. A população inicial que aplaudiu as leis aos poucos percebe a tirania do poder absoluto quando a lei se descola da moral e da razoabilidade humana.\n\nTEXTO II\nHenry David Thoreau, Mahatma Gandhi e Martin Luther King Jr. sustentavam que nenhuma lei injusta carrega legitimidade moral. Quando o Estado institucionaliza a opressão, a desobediência civil torna-se a manifestação mais elevada de compromisso com a justiça e com a dignidade da humanidade."
        ],
        [
            'titulo' => 'A busca pela perfeição e o medo da imperfeição no ser humano',
            'ano' => '2023',
            'origem' => 'UERJ',
            'banca' => 'UERJ',
            'eixo_tematico' => 'Filosofia & Existência',
            'genero_textual' => 'Dissertativo-Argumentativo',
            'dificuldade' => 'Médio',
            'orientacoes_especificas' => 'Critérios UERJ: Confronte os ideais estéticos e tecnológicos de perfeição com a condição frágil e finita da natureza humana.',
            'descricao' => 'Reflexão inspirada na distopia *Não Me Abandone Jamais*, questionando os limites bioéticos da engenharia genética e da uniformização de corpos e mentes.',
            'textos_motivadores' => "TEXTO I\nA cultura contemporânea exalta corpos esculpidos por procedimentos estéticos, rotinas meticulosamente controladas e algoritmos projetados para eliminar erros. O erro e a falha tornaram-se sinônimos de inadequação social.\n\nTEXTO II\nNa literatura de Kazuo Ishiguro, a clonagem humana serve como espelho trágico da busca por perfeição às custas da desumanização dos indivíduos. Reconhecer a imperfeição, a fragilidade e a finitude é a única forma de garantir a empatia e a beleza genuína da existência."
        ],

        // ==================== TEMAS INÉDITOS / COTADOS 2025 ====================
        [
            'titulo' => 'Desafios éticos e educacionais no avanço da Inteligência Artificial no Brasil',
            'ano' => '2025',
            'origem' => 'Tema Inédito (Cotado)',
            'banca' => 'ENEM',
            'eixo_tematico' => 'Tecnologia & Educação',
            'genero_textual' => 'Dissertativo-Argumentativo',
            'dificuldade' => 'Médio',
            'orientacoes_especificas' => 'Equilibre os ganhos pedagógicos da IA com os riscos de desinformação, automação precarizada e plágio.',
            'descricao' => 'Como a escola e a sociedade brasileira devem se posicionar diante da automação cognitiva, do plágio generativo e da necessidade de letramento digital crítico.',
            'textos_motivadores' => "TEXTO I\nA popularização vertiginosa de ferramentas generativas como o ChatGPT e Gemini transformou a produção textual, o desenvolvimento de códigos e as salas de aula em todo o Brasil. Professores debatem entre a proibição punitiva e a incorporação crítica dessas tecnologias no aprendizado.\n\nTEXTO II\nA ausência de um marco regulatório claro e de letramento de dados pode aprofundar abismos educacionais entre escolas públicas sem conectividade e colégios privados de elite com laboratórios de inteligência artificial de ponta."
        ],
        [
            'titulo' => 'Os impactos das mudanças climáticas na segurança alimentar e hídrica brasileira',
            'ano' => '2025',
            'origem' => 'Tema Inédito (Cotado)',
            'banca' => 'ENEM',
            'eixo_tematico' => 'Meio Ambiente & Economia',
            'genero_textual' => 'Dissertativo-Argumentativo',
            'dificuldade' => 'Médio',
            'orientacoes_especificas' => 'Discuta a inflação de alimentos e a vulnerabilidade hídrica em grandes cidades e áreas agrícolas.',
            'descricao' => 'Secas extremas na bacia amazônica e tempestades severas no Centro-Sul comprometem a produção agrícola familiar e o abastecimento de água potável.',
            'textos_motivadores' => "TEXTO I\nSecas históricas nos rios da Amazônia e tempestades catastróficas no Rio Grande do Sul em 2024 demonstraram que os efeitos do aquecimento global não pertencem a um futuro distante, mas já alteram o calendário de plantio e colheita do agronegócio e da agricultura familiar.\n\nTEXTO II\nA perda de safras eleva diretamente os preços dos alimentos básicos (como arroz, feijão e hortaliças), castigando as populações de menor renda que já enfrentam insegurança alimentar."
        ]
    ];

    $ins = $pdo->prepare("
        INSERT INTO redacao_temas (
            titulo, ano, origem, banca, eixo_tematico, genero_textual,
            dificuldade, orientacoes_especificas, descricao, textos_motivadores, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");

    foreach ($temas as $t) {
        $ins->execute([
            $t['titulo'],
            $t['ano'],
            $t['origem'],
            $t['banca'],
            $t['eixo_tematico'],
            $t['genero_textual'],
            $t['dificuldade'],
            $t['orientacoes_especificas'],
            $t['descricao'],
            $t['textos_motivadores']
        ]);
    }

    echo "Sucesso! Total de temas inseridos: " . count($temas) . PHP_EOL;

} catch (Exception $e) {
    echo "ERRO: " . $e->getMessage() . PHP_EOL;
}
