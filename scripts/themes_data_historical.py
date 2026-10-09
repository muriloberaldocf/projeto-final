# -*- coding: utf-8 -*-
"""
Módulo de Temas Históricos Oficiais (ENEM, UNESP, FUVEST, UNICAMP, UERJ e Regionais)
Todos com textos motivadores completos, fontes e dados autênticos.
"""

def get_historical_themes():
    temas = []

    # =========================================================================
    # ENEM REGULAR (1998 a 2024)
    # =========================================================================
    enem_reg = [
        (
            "Desafios para a valorização da herança africana no Brasil", 2024, "ENEM 2024 Regular", "ENEM", "Cultura & Direitos Humanos", "Médio",
            "A proposta exige reflexão sobre o apagamento histórico e a necessidade de reconhecimento e proteção das manifestações culturais, religiosas, linguísticas e materiais de matriz africana que fundaram a identidade brasileira.",
            """TEXTO I
A cultura brasileira é profundamente enraizada nas contribuições dos povos escravizados trazidos da África durante mais de três séculos. Do vocabulário à gastronomia, da música à arquitetura colonial, a herança africana não é apenas parte da nossa história: ela é a própria base estruturante da civilização brasileira. No entanto, séculos de racismo estrutural tentaram inferiorizar e marginalizar essas contribuições.

TEXTO II
Lei nº 10.639/2003: Estabelece a obrigatoriedade do ensino da história e cultura afro-brasileira nas escolas de ensino fundamental e médio. Mais de vinte anos após a sua promulgação, relatórios educacionais apontam que grande parte das redes públicas e privadas ainda enfrenta resistência curricular e falta de capacitação docente para implementar plenamente a legislação.

TEXTO III
Manifestações religiosas de matriz africana, como o Candomblé e a Umbanda, continuam sendo os principais alvos de intolerância e violência no Brasil, respondendo por mais de 60% dos registros de crimes contra a liberdade de culto no país, evidenciando o estigma persistente contra a herança ancestral negra."""
        ),
        (
            "Desafios para o enfrentamento da invisibilidade do trabalho de cuidado realizado pela mulher no Brasil", 2023, "ENEM 2023 Regular", "ENEM", "Sociedade & Gênero", "Difícil",
            "Analisa a histórica sobrecarga feminina em tarefas domésticas, cuidados com crianças, idosos e pessoas com deficiência, desprovida de remuneração e de reconhecimento por políticas públicas.",
            """TEXTO I
O trabalho de cuidado não remunerado é o motor invisível da economia mundial. Sem ele, trabalhadores não teriam alimentação, lares organizados ou suporte emocional para produzir. No Brasil, essa responsabilidade recai historicamente quase de forma exclusiva sobre os ombros das mulheres, que dedicam, em média, 21,3 horas semanais a afazeres domésticos e cuidados de pessoas, contra apenas 10,6 horas dedicadas pelos homens (IBGE, 2022).

TEXTO II
A divisão sexual do trabalho impõe uma dupla ou tripla jornada feminina. Mulheres que ingressam no mercado de trabalho formal enfrentam remunerações inferiores e são forçadas a abrir mão de estudos, carreiras ou descanso para manter a sustentação da família sem apoio de creches públicas e centros comunitários de apoio a idosos.

TEXTO III
A economia do cuidado precisa ser reconhecida como um bem público essencial, exigindo a implementação de um Sistema Nacional de Cuidados com creches em tempo integral, lavanderias comunitárias e políticas de licença parental equitativas."""
        ),
        (
            "Desafios para a valorização de comunidades e povos tradicionais no Brasil", 2022, "ENEM 2022 Regular", "ENEM", "Cidadania & Meio Ambiente", "Médio",
            "Aborda povos indígenas, quilombolas, ribeirinhos, marisqueiras e ciganos que preservam os biomas brasileiros e sofrem com invasões territoriais e falta de políticas públicas de proteção.",
            """TEXTO I
O Brasil abriga dezenas de povos e comunidades tradicionais: indígenas, quilombolas, ribeirinhos, marisqueiras, seringueiros, caiçaras e ciganos. Esses grupos possuem formas peculiares de organização social, ocupação territorial e uso dos recursos naturais, transmitindo saberes ancestrais fundamentais para o equilíbrio ecológico do planeta.

TEXTO II
De acordo com o Censo Demográfico do IBGE, o Brasil possui mais de 1,7 milhão de indígenas e 1,3 milhão de quilombolas. Contudo, a imensa maioria dos territórios tradicionais ainda sofre com a morosidade nos processos de demarcação e titulação, ficando exposta ao avanço do garimpo ilegal, da grilagem e do desmatamento criminoso.

TEXTO III
Constituição Federal de 1988, Art. 215: 'O Estado garantirá a todos o pleno exercício dos direitos culturais e acesso às fontes da cultura nacional, e apoiará e incentivará a valorização e a difusão das manifestações culturais.'"""
        ),
        (
            "Invisibilidade e registro civil: garantia de acesso à cidadania no Brasil", 2021, "ENEM 2021 Regular", "ENEM", "Cidadania & Direitos Humanos", "Médio",
            "Discute como a falta de certidão de nascimento impede o indivíduo de acessar direitos básicos, tornando-o um cidadão invisível perante o Estado.",
            """TEXTO I
Toda pessoa tem direito ao reconhecimento de sua personalidade jurídica. No Brasil, a certidão de nascimento é o primeiro documento formal que comprova a existência do indivíduo perante o Estado. Sem ela, a pessoa é empurrada para a margem absoluta da sociedade, tornando-se um 'cidadão de papel' inexistente para as estatísticas e políticas públicas.

TEXTO II
Dados da Fundação Abrinq e do IBGE apontavam que cerca de 3 milhões de brasileiros não possuíam registro civil de nascimento, concentrando-se principalmente em famílias em extrema vulnerabilidade social, populações indígenas, quilombolas e moradores de áreas periféricas e rurais isoladas.

TEXTO III
A Lei nº 9.534/1997 estabelece que o primeiro registro civil de nascimento e a respectiva primeira certidão são gratuitos para todos os brasileiros, mas barreiras geográficas, burocráticas e a desinformação ainda impedem a erradicação do sub-registro no país."""
        ),
        (
            "O estigma associado às doenças mentais na sociedade brasileira", 2020, "ENEM 2020 Regular", "ENEM", "Saúde Pública & Comportamento", "Médio",
            "Trata do preconceito, da desinformação e do tabu que cercam transtornos psíquicos como depressão e ansiedade no cotidiano nacional.",
            """TEXTO I
A palavra estigma, de origem grega, designava marcas físicas feitas a ferro e fogo no corpo de criminosos e escravos. Na sociologia moderna, o estigma refere-se a atributos profundamente depreciativos atribuídos a certos grupos. Em relação à saúde mental, o estigma transforma o sofrimento psíquico em motivo de vergonha, fraqueza moral ou incapacidade social.

TEXTO II
Segundo a Organização Mundial da Saúde (OMS), o Brasil é o país com a maior prevalência de transtornos de ansiedade no mundo (9,3% da população) e lidera na América Latina em casos de depressão. Apesar disso, milhões de pessoas evitam buscar auxílio psiquiátrico ou psicológico pelo receio do julgamento social e da exclusão profissional.

TEXTO III
A Lei nº 10.216/2001 (Lei da Reforma Psiquiátrica) redirecionou a assistência em saúde mental no Brasil, priorizando o tratamento humanizado em serviços comunitários (CAPS) e garantindo os direitos das pessoas acometidas por transtornos mentais contra o abandono e o confinamento asilar."""
        ),
        (
            "Democratização do acesso ao cinema no Brasil", 2019, "ENEM 2019 Regular", "ENEM", "Cultura & Urbanismo", "Fácil",
            "Analisa a concentração de salas de exibição cinematográfica em grandes centros urbanos e shoppings, privando a maior parte dos municípios brasileiros do acesso à sétima arte.",
            """TEXTO I
O cinema é muito mais que entretenimento: é uma janela de representação simbólica, estímulo à imaginação coletiva e preservação da memória nacional. Contudo, no Brasil, o acesso à sétima arte foi progressivamente confinado aos centros de compras e às áreas mais ricas das capitais, transformando um direito cultural em mercadoria de luxo.

TEXTO II
Dados da Agência Nacional do Cinema (Ancine) mostravam que mais de 80% dos municípios brasileiros não possuíam nenhuma sala pública ou privada de cinema. A imensa maioria das salas está concentrada em shopping centers das regiões Sudeste e Sul, com ingressos e custos de consumo que inviabilizam o lazer das classes C, D e E.

TEXTO III
Artigo 215 da Constituição de 1988: O Estado deve garantir o pleno exercício dos direitos culturais e apoiar a difusão das manifestações culturais, promovendo a descentralização de recursos e democratizando o acesso físico e econômico às obras audiovisuais."""
        ),
        (
            "Manipulação do comportamento do usuário pelo controle de dados na internet", 2018, "ENEM 2018 Regular", "ENEM", "Tecnologia & Sociedade", "Difícil",
            "Reflete sobre os algoritmos de recomendação, o rastreamento invisível de preferências e como isso molda o consumo e o debate público nas redes sociais.",
            """TEXTO I
Ao navegar na internet, cada clique, busca, curtida e tempo de tela é registrado, processado e transformado em padrões comportamentais por empresas de tecnologia. Esses dados alimentam algoritmos preditivos capazes de antecipar desejos e manipular sutilmente escolhas políticas, padrões de consumo e crenças ideológicas sem que o usuário perceba.

TEXTO II
O caso Cambridge Analytica expôs internacionalmente como o cruzamento não consentido de dados de milhões de usuários foi utilizado para microdirecionar mensagens persuasivas e polarizar eleições, evidenciando que a arquitetura das redes sociais lucra com o engajamento baseado na raiva e na confirmação de preconceitos.

TEXTO III
O Marco Civil da Internet (Lei nº 12.965/2014) assegura no Brasil a privacidade, a proteção de dados pessoais e a liberdade de modelos de negócios, estabelecendo que o consentimento deve ser informado e expresso para a coleta e o tratamento de registros virtuais."""
        ),
        (
            "Desafios para a formação educacional de surdos no Brasil", 2017, "ENEM 2017 Regular", "ENEM", "Educação & Inclusão", "Médio",
            "Discute a importância do bilinguismo (Libras e Língua Portuguesa escrita), escassez de intérpretes nas escolas e os preconceitos enfrentados pela comunidade surda.",
            """TEXTO I
A Língua Brasileira de Sinais (Libras) foi reconhecida como meio legal de comunicação e expressão no país pela Lei nº 10.436/2002. Para a comunidade surda, a Libras é sua primeira língua e o pilar de sua identidade sociocultural, sendo o português escrito a sua segunda língua.

TEXTO II
Apesar do marco legal, a inclusão de alunos surdos nas escolas regulares frequentemente se resume à presença física em sala, sem intérpretes qualificados, materiais didáticos visuais adaptados ou professores capacitados, gerando isolamento linguístico e prejuízos irreparáveis ao aprendizado.

TEXTO III
A Lei Brasileira de Inclusão da Pessoa com Deficiência (Estatuto da Pessoa com Deficiência - Lei nº 13.146/2015) assegura um sistema educacional inclusivo em todos os níveis, garantindo oferta de educação bilíngue em Libras como primeira língua e na modalidade escrita da Língua Portuguesa como segunda língua."""
        ),
        (
            "Caminhos para combater a intolerância religiosa no Brasil", 2016, "ENEM 2016 Regular (1ª)", "ENEM", "Cidadania & Direitos Humanos", "Fácil",
            "Discute a escalada da intolerância, ataques a terreiros e a necessidade de respeito à diversidade de crenças garantida pela Constituição de 1988.",
            """TEXTO I
O Brasil se autodefine como uma nação miscigenada e acolhedora, mas a convivência pacífica entre diferentes crenças é constantemente desafiada por discursos de ódio, vandalismo contra templos sagrados e perseguições cotidianas motivadas pelo fanatismo.

TEXTO II
Dados do Disque 100 do Ministério dos Direitos Humanos revelam que as religiões de matriz afro-brasileira, embora professadas por uma fração menor da população quando comparadas ao cristianismo, concentram a maior parcela das denúncias de agressões verbais, físicas e destruição patrimonial no país.

TEXTO III
Constituição Federal de 1988, Art. 5º, VI: 'É inviolável a liberdade de consciência e de crença, sendo assegurado o livre exercício dos cultos religiosos e garantida, na forma da lei, a proteção aos locais de culto e a suas liturgias.'"""
        ),
        (
            "Caminhos para combater o racismo no Brasil", 2016, "ENEM 2016 2ª Aplicação", "ENEM", "Sociedade & Igualdade Racial", "Médio",
            "Trata do racismo estrutural, da desigualdade de oportunidades e da superação do mito da democracia racial.",
            """TEXTO I
O mito da democracia racial, difundido ao longo do século XX, alimentou a falsa impressão de que a miscigenação no Brasil teria gerado harmonia entre brancos e negros. Na realidade, a ausência de leis de segregação formal deu lugar a um racismo estrutural sofisticado e perverso.

TEXTO II
Segundo o Atlas da Violência, jovens negros têm probabilidade mais de duas vezes superior de serem assassinados em comparação a jovens brancos. Além disso, negros são minoria em cargos de liderança, na magistratura e na política, embora representem 56% da população brasileira segundo o IBGE.

TEXTO III
A Lei nº 7.716/1989 define os crimes resultantes de preconceito de raça ou de cor, e o Estatuto da Igualdade Racial (Lei nº 12.288/2010) estabelece a responsabilidade estatal em garantir oportunidades equivalentes no acesso à educação, saúde e mercado de trabalho."""
        ),
        (
            "A persistência da violência contra a mulher na sociedade brasileira", 2015, "ENEM 2015 Regular", "ENEM", "Sociedade & Gênero", "Médio",
            "Aborda o feminicídio, as raízes do machismo patriarcal e as lacunas na eficácia das medidas protetivas da Lei Maria da Penha.",
            """TEXTO I
A violência doméstica e de gênero não é um problema privado ou restrito a casais: é uma violação sistemática dos direitos humanos enraizada em uma cultura patriarcal que normaliza a posse, a submissão e o controle sobre o corpo e a vida das mulheres.

TEXTO II
O Brasil registra, em média, um caso de feminicídio a cada seis horas, além de dezenas de milhares de estupros notificados anualmente pelo Fórum Brasileiro de Segurança Pública. A imensa maioria das vítimas é assassinada por parceiros ou ex-parceiros dentro do próprio lar.

TEXTO III
A Lei nº 11.340/2006 (Lei Maria da Penha) criou mecanismos rigorosos para prevenir e punir a violência doméstica e familiar contra a mulher, mas a lentidão judicial e o descumprimento de medidas protetivas ainda custam milhares de vidas femininas."""
        ),
        (
            "Publicidade infantil em questão no Brasil", 2014, "ENEM 2014 Regular", "ENEM", "Infância & Consumo", "Médio",
            "Discute a vulnerabilidade da criança diante de apelos consumistas agressivos e a necessidade de regulação das propagandas dirigidas ao público infantil.",
            """TEXTO I
A criança está em fase de formação psicológica e cognitiva, sendo incapaz de distinguir claramente entre o conteúdo do programa e o apelo persuasivo do comercial. Direcionar publicidade a esse público explora sua hipervulnerabilidade para convertê-la em promotora de consumo dentro do lar.

TEXTO II
A Resolução nº 163/2014 do Conanda (Conselho Nacional dos Direitos da Criança e do Adolescente) pacificou que a publicidade dirigida diretamente à criança é abusiva e ilegal, coibindo práticas que utilizem linguagem infantil, personagens de animação ou brindes para incentivar o consumo precoce de produtos.

TEXTO III
O Código de Defesa do Consumidor (Art. 37, § 2º) considera abusiva a publicidade que se aproveite da deficiência de julgamento e experiência da criança, cabendo aos órgãos de fiscalização sancionar marcas infratoras."""
        ),
        (
            "Efeitos da implantação da Lei Seca no Brasil", 2013, "ENEM 2013 Regular", "ENEM", "Saúde Pública & Segurança", "Fácil",
            "Analisa a mudança cultural provocada pela fiscalização rígida do consumo de bebidas alcoólicas ao volante e a redução de acidentes de trânsito.",
            """TEXTO I
A combinação entre álcool e direção sempre foi uma das principais causas de mortes violentas e mutilações no trânsito brasileiro. A tolerância social histórica com o motorista embriagado custava anualmente bilhões de reais ao SUS e destruía famílias inteiras.

TEXTO II
Com a promulgação da Lei nº 11.705/2008 (Lei Seca) e seu posterior endurecimento com tolerância zero para qualquer concentração de álcool por litro de sangue, os índices de acidentes fatais registraram quedas expressivas nas capitais brasileiras.

TEXTO III
Pesquisas de opinião demonstram ampla aceitação da população em relação à fiscalização, embora o uso de aplicativos de trânsito e redes sociais para alertar motoristas sobre blitzes revele a persistência da cultura do desrespeito às normas de segurança coletiva."""
        ),
        (
            "O movimento imigratório para o Brasil no século XXI", 2012, "ENEM 2012 Regular", "ENEM", "Cidadania & Relações Internacionais", "Médio",
            "Reflete sobre o acolhimento de haitianos, venezuelanos e outros povos no Brasil, integração no mercado de trabalho e desafios contra a xenofobia.",
            """TEXTO I
O Brasil do século XXI passou a receber fluxos migratórios impulsionados por crises humanitárias, desastres naturais e conflitos armados no Sul Global. A chegada de haitianos após o terremoto de 2010 e o êxodo venezuelano redefiniram a pauta de migrações e refúgio nas fronteiras nacionais.

TEXTO II
Apesar da tradição de acolhimento diplomático, imigrantes e refugiados frequentemente enfrentam precarização no mercado de trabalho, moradia indigna, xenofobia velada e dificuldades na revalidação de diplomas profissionais para reconstruírem suas vidas com autonomia.

TEXTO III
A Nova Lei de Migração (Lei nº 13.445/2017) garantiu a não criminalização do migrante, o acolhimento humanitário e a igualdade de tratamento e acesso aos serviços públicos de saúde, educação e previdência."""
        ),
        (
            "Viver em rede no século XXI: os limites entre o público e o privado", 2011, "ENEM 2011 Regular", "ENEM", "Comportamento & Tecnologia", "Médio",
            "Trata da superexposição íntima na internet, vigilância corporativa e a transformação da privacidade em espetáculo.",
            """TEXTO I
As redes sociais revolucionaram a comunicação humana, mas também borraram as fronteiras que separavam o que é íntimo do que pertence à esfera pública. A exibição constante da própria vida, em busca de aprovação em forma de curtidas, transformou o indivíduo em seu próprio produto espetacularizado.

TEXTO II
A exposição voluntária de dados, rotinas, relações familiares e opiniões expõe os cidadãos a riscos de segurança física, chantagens, perseguições (stalking) e danos permanentes à reputação profissional causados pelo resgate de postagens antigas fora de contexto.

TEXTO III
O sociólogo Zygmunt Bauman advertiu que na sociedade confessional moderna, a privacidade não é mais um refúgio protegido, mas uma prisão da qual todos tentam escapar para serem vistos e notados no espaço público virtual."""
        ),
        (
            "O trabalho na construção da dignidade humana", 2010, "ENEM 2010 Regular", "ENEM", "Trabalho & Cidadania", "Fácil",
            "Discute a relação entre trabalho formal, direitos trabalhistas, emancipação social e a chaga do trabalho análogo à escravidão.",
            """TEXTO I
O trabalho é a atividade fundante pela qual o ser humano transforma a natureza e a si mesmo, adquirindo reconhecimento social, sustento material e dignidade cidadã. Quando o trabalho dignifica, ele permite a emancipação do sujeito.

TEXTO II
No entanto, no Brasil contemporâneo, milhões de pessoas sobrevivem na informalidade desprovidas de quaisquer garantias trabalhistas, enquanto casos de trabalho análogo à escravidão continuam sendo resgatados tanto em lavouras do interior quanto em oficinas têxteis de grandes capitais.

TEXTO III
Artigo 1º, IV, da Constituição de 1988: Estabelece os 'valores sociais do trabalho e da livre iniciativa' como um dos fundamentos essenciais da República Federativa do Brasil, ao lado da dignidade da pessoa humana."""
        ),
        (
            "O indivíduo frente à ética nacional", 2009, "ENEM 2009 Regular", "ENEM", "Ética & Sociedade", "Difícil",
            "Analisa a responsabilidade cidadã, o combate à corrupção cotidiana e a superação da cultura do 'jeitinho brasileiro'.",
            """TEXTO I
A ética coletiva de uma nação é moldada pelas ações diárias de seus cidadãos. Criticar os desvios da classe política sem questionar as pequenas infrações cotidianas — furar filas, subornar guardas, falsificar atestados — revela uma contradição moral que fragiliza o senso de bem comum.

TEXTO II
O historiador Sérgio Buarque de Holanda cunhou o conceito de 'homem cordial' para explicar a tendência brasileira de sobrepor os laços afetivos e de compadrio às leis impessoais do Estado, enfraquecendo o sentido republicano de igualdade perante a lei.

TEXTO III
A construção de um país ético requer a superação da hipocrisia social e a compreensão de que a honestidade pública começa na conduta ética individual de respeito irrestrito às normas coletivas."""
        ),
        (
            "Como preservar a floresta amazônica", 2008, "ENEM 2008 Regular", "ENEM", "Meio Ambiente & Sustentabilidade", "Médio",
            "Aborda o desmatamento, a grilagem de terras públicas e a importância de valorizar a floresta em pé por meio da bioeconomia.",
            """TEXTO I
A Amazônia é o maior patrimônio de biodiversidade do planeta e desempenha papel insubstituível na regulação climática mundial e no regime de chuvas do continente através dos chamados 'rios voadores'.

TEXTO II
O avanço desordenado da fronteira agrícola, a grilagem de terras públicas e a extração predatória de madeira ameaçam empurrar a floresta para o 'ponto de não retorno' (tipping point), a partir do qual ela perde a capacidade de se regenerar.

TEXTO III
A preservação sustentável exige fortalecer os órgãos de fiscalização ambiental (Ibama, ICMBio), demarcar terras indígenas e investir massivamente na bioeconomia de produtos florestais que mantenham a floresta em pé gerando renda local."""
        ),
        (
            "O desafio de se conviver com a diferença", 2007, "ENEM 2007 Regular", "ENEM", "Cidadania & Direitos Humanos", "Fácil",
            "Reflete sobre o preconceito, a intolerância contra minorias e a necessidade de respeito à diversidade cultural e identitária.",
            """TEXTO I
A humanidade é constituída pela pluralidade de modos de vida, crenças, orientações sexuais, etnias e visões de mundo. Conviver com a diferença não é apenas tolerar passivamente o outro, mas reconhecer sua legitimidade e valorizar sua contribuição ao mosaico coletivo.

TEXTO II
Casos de homofobia, xenofobia regional e discriminação racial mostram que o estranhamento em relação àquilo que diverge dos padrões hegemônicos frequentemente se converte em agressão e exclusão social no Brasil.

TEXTO III
A Declaração Universal dos Direitos Humanos (1948) proclama em seu Artigo 1º que 'todos os seres humanos nascem livres e iguais em dignidade e em direitos. Dotados de razão e de consciência, devem agir uns para com os outros em espírito de fraternidade.'"""
        ),
        (
            "O poder de transformação da leitura", 2006, "ENEM 2006 Regular", "ENEM", "Educação & Cultura", "Fácil",
            "Trata da formação de leitores críticos, superação do analfabetismo funcional e o papel emancipador da literatura.",
            """TEXTO I
Ler não é apenas decodificar letras e palavras, mas decifrar criticamente a realidade ao redor. Como ensinava Paulo Freire, 'a leitura do mundo precede a leitura da palavra', capacitando o indivíduo a compreender seu lugar na história e a intervir nela.

TEXTO II
Indicadores do Instituto Pró-Livro revelam que o Brasil ainda lê menos de 2,5 livros inteiros por habitante ao ano. O déficit de bibliotecas públicas e o preço elevado dos livros afastam as camadas populares da experiência literária contínua.

TEXTO III
A democratização da leitura exige políticas públicas integradas que promovam bibliotecas escolares atraentes, projetos comunitários de mediação de leitura e redução de tributos sobre a cadeia de produção editorial."""
        ),
        (
            "O trabalho infantil na sociedade brasileira", 2005, "ENEM 2005 Regular", "ENEM", "Infância & Direitos Humanos", "Médio",
            "Discute as raízes do trabalho precoce no campo e nas periferias, que rouba a infância e perpetua o ciclo geracional da pobreza.",
            """TEXTO I
O trabalho precoce rouba da criança o tempo sagrado do brincar, do estudar e do desenvolver-se com plenitude física e emocional. A crença popular de que 'o trabalho dignifica e evita o crime' oculta a perversidade de transferir para a infância a responsabilidade pelo sustento familiar.

TEXTO II
Milhões de crianças e adolescentes brasileiros continuam submetidos a piores formas de trabalho infantil, incluindo atividades perigosas na agricultura, feiras livres, construção civil e no tráfico de drogas, comprometendo sua frequência e rendimento escolar.

TEXTO III
O Estatuto da Criança e do Adolescente (ECA - Lei nº 8.069/1990) proíbe qualquer trabalho a menores de 14 anos, permitindo a atuação exclusivamente a partir dessa idade na condição de aprendiz, resguardando seu direito prioritário à escolarização."""
        ),
        (
            "Como garantir a liberdade de informação e evitar abusos nos meios de comunicação", 2004, "ENEM 2004 Regular", "ENEM", "Comunicação & Ética", "Médio",
            "Equilíbrio entre a liberdade de imprensa necessária à democracia e a responsabilidade civil contra linchamentos midiáticos.",
            """TEXTO I
A imprensa livre é um dos pilares mais sólidos de qualquer regime democrático republicano. Sem liberdade editorial para investigar, denunciar e informar, governos e corporações agem nas sombras sem controle dos cidadãos.

TEXTO II
Por outro lado, o sensacionalismo comercial e a publicação irresponsável de acusações infundadas sem direito de resposta podem destruir vidas, honras e reputações de maneira irreparável, exigindo rigor e compromisso ético dos veículos.

TEXTO III
A Constituição Federal veda expressamente toda e qualquer censura de natureza política, ideológica e artística, mas estabelece o direito de resposta proporcional ao agravo e a indenização por dano material, moral ou à imagem (Art. 5º, V)."""
        ),
        (
            "A violência na sociedade brasileira: como mudar as regras desse jogo", 2003, "ENEM 2003 Regular", "ENEM", "Segurança Pública & Cidadania", "Médio",
            "Analisa alternativas à militarização e ao encarceramento em massa: prevenção, inteligência, emprego para a juventude e políticas sociais.",
            """TEXTO I
A violência que assola as metrópoles brasileiras é alimentada pela combinação tóxica entre desigualdade socioeconômica extrema, tráfico ilegal de armas e drogas, desagregação comunitária e impunidade histórica.

TEXTO II
A resposta exclusivamente repressiva e bélica do Estado demonstrou-se incapaz de solucionar o problema nas últimas décadas, resultando apenas em taxas altíssimas de letalidade policial e vitimização de inocentes em confrontos armados diários.

TEXTO III
Especialistas em segurança pública defendem que mudar as regras desse jogo requer a integração de inteligência investigativa, controle estrito de armas, urbanismo social nas periferias e oferta massiva de oportunidades educacionais."""
        ),
        (
            "O direito de votar: como fazer dessa conquista um meio para promover as transformações sociais", 2002, "ENEM 2002 Regular", "ENEM", "Política & Cidadania", "Fácil",
            "Reflete sobre a conquista histórica do sufrágio universal e a necessidade de acompanhamento e cobrança contínua dos representantes eleitos.",
            """TEXTO I
O sufrágio universal e secreto foi uma conquista árdua do povo brasileiro, alcançada após décadas de regimes autoritários, voto de cabresto e exclusão de analfabetos, mulheres e trabalhadores pobres.

TEXTO II
No entanto, o voto perde sua potência transformadora quando é reduzido a um ritual meramente protocolar a cada dois anos, marcado pela troca de favores imediatistas e pela falta de acompanhamento das propostas dos eleitos após o término da apuração.

TEXTO III
A verdadeira cidadania política exige engajamento contínuo: fiscalização das contas públicas, participação em audiências de orçamento, conselhos de classe e mobilização comunitária para cobrar o cumprimento dos programas de governo."""
        ),
        (
            "Desenvolvimento e preservação ambiental: como conciliar os interesses em jogo", 2001, "ENEM 2001 Regular", "ENEM", "Meio Ambiente & Economia", "Médio",
            "Discute o desenvolvimento sustentável, economia verde e a superação da falsa dicotomia entre progresso e ecologia.",
            """TEXTO I
O modelo de crescimento econômico herdado da Revolução Industrial tratou os recursos naturais do planeta como fontes infinitas de matéria-prima e depósitos inesgotáveis de lixo e poluição.

TEXTO II
O Relatório Brundtland (1987) definiu desenvolvimento sustentável como aquele que atende às necessidades das gerações presentes sem comprometer a capacidade das gerações futuras de atenderem às suas próprias necessidades.

TEXTO III
O Brasil, detentor de rica matriz energética renovável e maior reserva de água doce do mundo, possui todas as condições para liderar uma economia de baixo carbono baseada na agregação de valor científico à sua biodiversidade."""
        ),
        (
            "Direitos da criança e do adolescente: como enfrentar esse desafio nacional", 2000, "ENEM 2000 Regular", "ENEM", "Infância & Direitos Humanos", "Médio",
            "Trata da aplicação prática do ECA, enfrentamento à exploração sexual e garantia de educação de qualidade para todas as crianças.",
            """TEXTO I
Antes da Constituição de 1988 e do ECA, menores de idade eram tratados pela doutrina da 'situação irregular', vistos como potenciais problemas de segurança pública e submetidos a internações arbitrárias em instituições correcionais.

TEXTO II
A doutrina da proteção integral consagrada no artigo 227 da Carta Magna estabeleceu a criança e o adolescente como sujeitos de direitos e deveres, impondo à família, à sociedade e ao Estado a obrigação de colocá-los a salvo de toda forma de negligência e violência.

TEXTO III
Apesar do arcabouço legislativo avançado, a evasão escolar, o trabalho infantil e os índices de homicídios de adolescentes mostram o abismo que ainda separa a letra da lei da realidade enfrentada por milhões de jovens no país."""
        ),
        (
            "Cidadania e participação social", 1999, "ENEM 1999 Regular", "ENEM", "Cidadania & Política", "Fácil",
            "A importância da organização comunitária, dos movimentos sociais e dos conselhos participativos para a consolidação democrática.",
            """TEXTO I
Ser cidadão não é apenas possuir carteira de identidade e o direito ao voto. A cidadania ativa exige a consciência de pertencimento a um corpo político e a disposição de agir coletivamente pelo bem comum.

TEXTO II
O sociólogo T. H. Marshall dividiu a cidadania em três dimensões históricas: direitos civis (liberdade individual e propriedade), direitos políticos (participação no poder) e direitos sociais (educação, saúde, trabalho e previdência).

TEXTO III
No Brasil, a consolidação dos direitos sociais sempre dependeu da mobilização dos movimentos sociais organizados: sindicais, estudantis, comunitários, de mulheres e do movimento negro ao longo de toda a história republicana."""
        ),
        (
            "Viver e aprender", 1998, "ENEM 1998 Regular", "ENEM", "Educação & Filosofia", "Fácil",
            "O aprendizado permanente ao longo da existência e a educação como processo constante de humanização e convivência fraterna.",
            """TEXTO I
Aprender é a atividade humana que nunca cessa. O conhecimento não é um estoque fechado adquirido nos anos escolares, mas uma dinâmica viva que se renova a cada experiência, erro, encontro e reflexão.

TEXTO II
A célebre música de Gonzaguinha canta: 'Viver e não ter a vergonha de ser feliz / Cantar e cantar e cantar a beleza de ser um eterno aprendiz.' A postura de eterno aprendiz é o antídoto contra a arrogância dogmática e o conformismo intelectual.

TEXTO III
A educação no século XXI é norteada pelos quatro pilares da Unesco: aprender a conhecer, aprender a fazer, aprender a conviver e aprender a ser, integrando técnica, afeto e ética para a cidadania planetária."""
        )
    ]

    for item in enem_reg:
        temas.append({
            'titulo': item[0],
            'ano': str(item[1]),
            'origem': item[2],
            'banca': item[3],
            'eixo_tematico': item[4],
            'genero_textual': 'Dissertativo-Argumentativo',
            'dificuldade': item[5],
            'orientacoes_especificas': 'Elabore proposta de intervenção com os 5 elementos oficiais (Agente, Ação, Meio/Modo, Efeito e Detalhamento). Respeite os direitos humanos. Mínimo 7 e máximo 30 linhas.',
            'descricao': item[6],
            'textos_motivadores': item[7]
        })

    # =========================================================================
    # ENEM PPL E REAPLICAÇÕES (2009 a 2024)
    # =========================================================================
    enem_ppl = [
        (
            "O trabalho informal como alternativa de subsistência no Brasil", 2024, "ENEM 2024 PPL", "ENEM", "Trabalho & Economia", "Médio",
            "Analisa a explosão do trabalho por conta própria e a falta de cobertura previdenciária e garantias trabalhistas entre milhões de brasileiros.",
            """TEXTO I
A informalidade no mercado de trabalho brasileiro atinge quase 40% da população ocupada. Para dezenas de milhões de cidadãos, a venda ambulante, os bicos e os serviços esporádicos são a única fonte diária de alimentação e moradia familiar.

TEXTO II
Trabalhadores informais não possuem férias remuneradas, décimo terceiro salário, descanso semanal ou auxílio-doença pelo INSS. Em situações de enfermidade ou acidentes, o sustento da família cessa imediatamente, empurrando-os para a extrema pobreza.

TEXTO III
Políticas públicas de incentivo ao microempreendedorismo, como o MEI, ampliaram a formalização, mas a baixa renda média e a complexidade tributária ainda mantêm milhões à margem de qualquer proteção do Estado."""
        ),
        (
            "Desafios para a promoção da doação de órgãos no Brasil", 2023, "ENEM 2023 PPL", "ENEM", "Saúde Pública & Cidadania", "Fácil",
            "Discute as recusas familiares, mitos sobre morte encefálica e a importância de manifestar a vontade de ser doador em vida.",
            """TEXTO I
O Brasil possui o maior sistema público de transplantes de órgãos do mundo, com o SUS financiando mais de 90% das cirurgias. No entanto, milhares de pacientes falecem anualmente nas filas de espera por rins, fígados e corações.

TEXTO II
A legislação brasileira exige a autorização da família para que a doação seja efetivada após o diagnóstico de morte encefálica. A taxa de recusa familiar ultrapassa 40% em diversos estados, motivada pelo desconhecimento, crenças religiosas e dor do luto.

TEXTO III
Especialistas em bioética enfatizam que a conscientização comunitária e o diálogo aberto dentro dos lares sobre o desejo individual de doar órgãos são a chave para salvar milhares de vidas anualmente."""
        ),
        (
            "Medidas para o enfrentamento da recorrência da insegurança alimentar no Brasil", 2022, "ENEM 2022 PPL", "ENEM", "Direitos Humanos & Sociedade", "Difícil",
            "Analisa o retorno do Brasil ao Mapa da Fome e a urgência de estoques reguladores, fortalecimento da agricultura familiar e programas de renda.",
            """TEXTO I
A fome não é um fenômeno natural ou inevitável em um país que figura entre os maiores exportadores globais de grãos e proteína animal. Ela é o resultado de desigualdades históricas na distribuição de renda e terra.

TEXTO II
O Inquérito Nacional sobre Insegurança Alimentar (Rede PENSSAN) revelou que mais de 33 milhões de brasileiros chegaram a passar fome severa no período pós-pandêmico, com crianças e lares chefiados por mulheres negras sendo os mais vitimados.

TEXTO III
O Artigo 6º da Constituição Cidadã estabelece a alimentação como um direito social fundamental, impondo ao poder público a obrigação de garantir a segurança alimentar e nutricional permanente de toda a população."""
        ),
        (
            "Reconhecimento da contribuição das mulheres nas ciências da saúde no Brasil", 2021, "ENEM 2021 PPL", "ENEM", "Ciência & Gênero", "Médio",
            "Trata do teto de vidro na academia, do efeito Matilda (apagamento de descobertas femininas) e da liderança de cientistas no combate a epidemias.",
            """TEXTO I
Historicamente, as mulheres foram pioneiras em descobertas vitais na medicina, na enfermagem e na biologia, mas com frequência tiveram suas contribuições silenciadas ou atribuídas a colegas do sexo masculino, fenômeno conhecido como 'Efeito Matilda'.

TEXTO II
No Brasil, cientistas como a biomédica Jaqueline Goes de Jesus ganharam notoriedade mundial ao sequenciarem o genoma do coronavírus em apenas 48 horas. Contudo, mulheres ainda enfrentam dificuldades para ocupar reitorias e cargos de liderança científica em decorrência da sobrecarga doméstica.

TEXTO III
Promover a equidade de gênero na ciência exige bolsas de pesquisa com licença-maternidade garantida, estímulo à presença de meninas nas áreas de STEM e premiações que valorizem o legado das pesquisadoras brasileiras."""
        ),
        (
            "A falta de empatia nas relações sociais no Brasil", 2020, "ENEM 2020 PPL", "ENEM", "Comportamento & Filosofia", "Médio",
            "Reflete sobre o individualismo extremado, a indiferença à dor alheia e a polarização que desumaniza o semelhante.",
            """TEXTO I
Empatia é a capacidade de se colocar no lugar do outro, compreendendo suas dores, perspectivas e sentimentos. Na modernidade líquida descrita por Zygmunt Bauman, os laços humanos tornaram-se frágeis e descartáveis, reduzindo a empatia a um sentimento supérfluo.

TEXTO II
Casos frequentes de agressões gratuitas, indiferença com a população em situação de rua e linchamentos morais na internet demonstram o endurecimento dos corações nas relações cotidianas brasileiras.

TEXTO III
A filósofa Hannah Arendt alertou para o perigo da 'banalidade do mal', caracterizada pela incapacidade de pensar a partir do ponto de vista do outro, tornando a desumanização uma rotina aceita silenciosamente pela coletividade."""
        ),
        (
            "Combate às doenças associadas ao consumo de tabaco", 2019, "ENEM 2019 PPL", "ENEM", "Saúde Pública & Juventude", "Médio",
            "Aborda as vitórias antitabagistas históricas e a nova ameaça dos cigarros eletrônicos (vapes) e narguilés entre os jovens.",
            """TEXTO I
O tabagismo é considerado pela OMS a principal causa de morte evitável no mundo. Nas últimas décadas, o Brasil foi referência internacional ao reduzir drasticamente o percentual de fumantes por meio de restrições de propaganda e impostos elevados.

TEXTO II
Entretanto, a ascensão dos dispositivos eletrônicos para fumar (vapes) e essências aromatizadas de narguilé reintroduziu o vício da nicotina entre adolescentes, sob a falsa ilusão de que seriam produtos inofensivos à saúde pulmonar e cardiovascular.

TEXTO III
A Anvisa proíbe a comercialização, importação e propaganda de cigarros eletrônicos no Brasil, mas o contrabando e a venda clandestina em comércios e na internet desafiam a proteção à saúde da juventude."""
        ),
        (
            "Formas de organização da sociedade para o enfrentamento de problemas econômicos", 2018, "ENEM 2018 PPL", "ENEM", "Economia & Cidadania", "Difícil",
            "Discute a economia solidária, cooperativas de trabalhadores, bancos comunitários e redes de apoio mútuo em momentos de crise financeira.",
            """TEXTO I
Diante da escassez de empregos formais e da retração estatal, comunidades brasileiras têm desenvolvido soluções autônomas baseadas no cooperativismo, na economia solidária e na ajuda mútua entre vizinhos.

TEXTO II
Experiências como o Banco Palmas, em Fortaleza, criaram moedas sociais locais que circulam dentro das periferias, incentivando o comércio de bairro, gerando postos de trabalho e mantendo a riqueza na própria comunidade.

TEXTO III
O sociólogo Boaventura de Sousa Santos ressalta que essas economias não capitalistas oferecem alternativas viáveis de subsistência e reconstrução dos laços comunitários em contraposição à frieza do mercado financeiro tradicional."""
        ),
        (
            "Consequências da busca por padrões de beleza inalcançáveis", 2017, "ENEM 2017 PPL", "ENEM", "Saúde Mental & Comportamento", "Fácil",
            "Trata da dismorfia corporal, banalização de cirurgias plásticas, uso abusivo de anabolizantes e transtornos alimentares impulsionados pelas redes sociais.",
            """TEXTO I
A indústria da beleza e os filtros das redes sociais construíram um ideal estético padronizado e artificial, no qual rugas, curvas e marcas do tempo são tratadas como imperfeições inaceitáveis a serem corrigidas cirurgicamente.

TEXTO II
O Brasil é um dos líderes globais na realização de cirurgias plásticas estéticas e no consumo de medicamentos inibidores de apetite. Transtornos como anorexia, bulimia e vigorexia afetam uma parcela crescente de jovens e mulheres.

TEXTO III
A filósofa Naomi Wolf, em 'O Mito da Beleza', demonstrou como a obsessão pelo corpo perfeito funciona como um mecanismo de controle social que drena energia, saúde mental e autonomia financeira das mulheres."""
        ),
        (
            "Alternativas para a escassez de água no Brasil", 2016, "ENEM 2016 PPL", "ENEM", "Meio Ambiente & Recursos Hídricos", "Médio",
            "Aborda crises hídricas nas regiões metropolitanas, secas no semiárido, transposição de rios e reuso de água industrial.",
            """TEXTO I
Embora o Brasil detenha cerca de 12% da água doce superficial do planeta, a distribuição desse recurso é profundamente desigual: mais de 70% está na bacia Amazônica, onde vive uma fração menor da população, enquanto os grandes centros urbanos sofrem com estresse hídrico crônico.

TEXTO II
O desmatamento das matas ciliares, a poluição por esgoto doméstico e industrial e a obsolescência das redes de distribuição provocam perdas superiores a 35% de toda a água tratada antes mesmo de ela chegar às torneiras dos cidadãos.

TEXTO III
Garantir a segurança hídrica requer a restauração de nascentes, o incentivo a tecnologias de reuso de água da chuva, dessalinização no semiárido e a cobrança justa pelo uso da água por grandes setores do agronegócio e da indústria."""
        ),
        (
            "O histórico desafio de valorizar o idoso no Brasil", 2015, "ENEM 2015 PPL", "ENEM", "Sociedade & Demografia", "Fácil",
            "Discute a transição demográfica acelerada, o abandono afetivo de idosos e os preconceitos etaristas no mercado de trabalho.",
            """TEXTO I
O Brasil está envelhecendo rapidamente: nas próximas décadas, a população com mais de 60 anos superará o número de crianças e jovens. Essa revolução demográfica exige uma profunda transformação nas políticas de saúde, previdência e acolhimento familiar.

TEXTO II
Casos de violência financeira, negligência médica e isolamento em instituições de longa permanência revelam o desprezo de uma sociedade voltada exclusivamente para o consumo e o culto à juventude produtivista.

TEXTO III
O Estatuto do Idoso (Lei nº 10.741/2003) assegura prioridade absoluta aos maiores de 60 anos, estabelecendo que a família, a sociedade e o poder público têm o dever compartilhado de resguardar sua dignidade e integridade física e moral."""
        )
    ]

    for item in enem_ppl:
        temas.append({
            'titulo': item[0],
            'ano': str(item[1]),
            'origem': item[2],
            'banca': item[3],
            'eixo_tematico': item[4],
            'genero_textual': 'Dissertativo-Argumentativo',
            'dificuldade': item[5],
            'orientacoes_especificas': 'Elabore proposta de intervenção com os 5 elementos oficiais (Agente, Ação, Meio/Modo, Efeito e Detalhamento). Respeite os direitos humanos. Mínimo 7 e máximo 30 linhas.',
            'descricao': item[6],
            'textos_motivadores': item[7]
        })

    # =========================================================================
    # UNESP / VUNESP (2005 a 2024 + meio de ano)
    # =========================================================================
    unesp_list = [
        (
            "É possível falar em cultura do cancelamento como forma legítima de justiça social?", 2024, "UNESP 2024", "UNESP", "Comportamento & Redes", "Difícil",
            "A proposta exige que o vestibulando se posicione sobre os linchamentos virtuais versus a denúncia de condutas criminosas e preconceituosas na internet.",
            """TEXTO I
A cultura do cancelamento emergiu nas redes como uma tentativa de grupos historicamente marginalizados de cobrar responsabilidade de figuras públicas e corporações por declarações racistas, machistas ou homofóbicas, operando onde a justiça tradicional é morosa ou leniente.

TEXTO II
Por outro lado, críticos apontam que o cancelamento frequentemente se degenera em linchamento público desenfreado, impedindo o contraditório, punindo desproporcionalmente desvios menores e inviabilizando qualquer processo pedagógico de reparação e diálogo.

TEXTO III
O filósofo Byung-Chul Han adverte que as tempestades de indignação digital (shitstorms) nas redes sociais não constroem um espaço público crítico, mas atuam como descargas emocionais passageiras que dissolvem o debate racional em nome da fúria algorítmica."""
        ),
        (
            "A 'ludificação' da vida contemporânea: tudo virou jogo?", 2023, "UNESP 2023", "UNESP", "Tecnologia & Sociedade", "Difícil",
            "Discute a gamificação de aplicativos de entrega, finanças e aprendizado, estimulando competição e produtividade compulsiva.",
            """TEXTO I
A 'gamificação' ou ludificação da sociedade consiste no emprego de mecânicas de jogos — pontos, rankings, medalhas e recompensas instantâneas — em áreas tradicionalmente não lúdicas, como trabalho, educação, exercícios físicos e relacionamentos amorosos.

TEXTO II
Entusiastas defendem que essa prática torna tarefas rotineiras mais engajantes e prazerosas. Contudo, sociólogos alertam que plataformas de entrega e transporte transformaram a sobrevivência econômica de trabalhadores em um jogo de metas perverso, mascarando a exploração sob o manto da diversão.

TEXTO III
O filósofo Johan Huizinga concebeu o conceito de 'Homo Ludens', defendendo que o jogo é anterior à cultura. Porém, quando o jogo deixa de ser livre e desinteressado para se tornar uma engrenagem de extração de valor, ele destrói a própria essência lúdica humana."""
        ),
        (
            "Tristeza em tempos de felicidade compulsória", 2022, "UNESP 2022", "UNESP", "Comportamento & Filosofia", "Médio",
            "Aborda a proibição tácita da vulnerabilidade, a positividade tóxica e a patologização do sofrimento humano nas redes sociais.",
            """TEXTO I
Vivemos sob a ditadura da felicidade compulsória. Nas vitrines digitais, todos parecem viver vidas plenas, produtivas e sorridentes, transformando o luto, a dúvida e a melancolia em falhas de caráter ou transtornos a serem imediatamente medicados.

TEXTO II
O ensaísta Byung-Chul Han, em 'Sociedade Paliativa', argumenta que a fobia à dor física e emocional impede a transformação pessoal e anestesia a consciência crítica: quem recusa todo sofrimento torna-se incapaz de empatia e de indignação moral.

TEXTO III
A tristeza é um afeto legítimo e necessário para o processamento de perdas, a reflexão sobre erros e a maturação psicológica, não podendo ser encarada como um desvio a ser erradicado da existência humana."""
        ),
        (
            "Tempo é dinheiro?", 2021, "UNESP 2021", "UNESP", "Economia & Filosofia", "Médio",
            "Contrapõe a lógica capitalista da pressa e da produtividade incessante com a fruição do tempo livre e a contemplação existencial.",
            """TEXTO I
A máxima 'tempo é dinheiro', atribuída a Benjamin Franklin no século XVIII, sintetiza a lógica do capitalismo moderno, que converteu cada minuto da existência humana em valor monetário a ser rentabilizado pelo trabalho contínuo.

TEXTO II
O sociólogo Domenico De Masi propôs a tese do 'ócio criativo', sustentando que os momentos livres de obrigações produtivas são o verdadeiro berço das grandes invenções artísticas, literárias e científicas da humanidade.

TEXTO III
O líder indígena Ailton Krenak convida a sociedade ocidental a 'adiar o fim do mundo' desacelerando o relógio corporativo para ouvir os rios, as montanhas e as histórias dos ancestrais, rompendo com a ditadura da mercadoria."""
        ),
        (
            "O carro será o novo cigarro?", 2020, "UNESP 2020", "UNESP", "Urbanismo & Meio Ambiente", "Fácil",
            "Analisa a transição da admiração social pelo automóvel individual para a percepção de poluição, congestionamentos e vilão da saúde urbana.",
            """TEXTO I
Durante quase todo o século XX, o automóvel particular foi o maior símbolo de liberdade, masculinidade e ascensão social, moldando as cidades modernas com viadutos e rodovias em detrimento de calçadas e trilhos.

TEXTO II
Hoje, assim como o cigarro — que passou de hábito charmoso a vilão da saúde pública —, o carro individual começa a ser visto como fonte de poluição atmosférica, ruído ensurdecedor, ilhas de calor e estresse coletivo em metrópoles paralisadas.

TEXTO III
Cidades como Paris, Amsterdã e Bogotá estão restringindo a circulação de automóveis nos centros históricos, investindo maciçamente em ciclovias e transportes elétricos sobre trilhos para devolver as ruas aos pedestres."""
        ),
        (
            "Compro, logo existo?", 2019, "UNESP 2019", "UNESP", "Sociedade & Consumo", "Médio",
            "Reflete sobre o consumismo como critério de valor social e pertencimento na sociedade contemporânea.",
            """TEXTO I
Parodiando o célebre 'Penso, logo existo' de René Descartes, o filósofo Zygmunt Bauman formulou que na modernidade tardia a identidade é construída pelo consumo: os indivíduos só se sentem reais e aceitos quando adquirem os produtos da moda.

TEXTO II
A publicidade moderna não vende apenas objetos úteis, mas promessas de status, afeto e segurança emocional, gerando uma insatisfação crônica em que cada novidade rapidamente se torna obsoleta para manter a roda da economia girando.

TEXTO III
Essa dinâmica condena os não consumidores — os excluídos do mercado — à invisibilidade social e ao sofrimento ético, privando-os do reconhecimento de sua dignidade cidadã."""
        ),
        (
            "O voto nulo é um ato político eficaz?", 2018, "UNESP 2018", "UNESP", "Política & Democracia", "Difícil",
            "Discute se a anulação do voto expressa um protesto legítimo contra o sistema político ou se apenas favorece a manutenção do status quo.",
            """TEXTO I
Defensores do voto nulo argumentam que ele é uma manifestação legítima de repúdio a um sistema político oligárquico e corrupto, demonstrando descontentamento cívico frente à falta de alternativas de qualidade nas urnas.

TEXTO II
Por outro lado, cientistas políticos alertam que, no sistema eleitoral brasileiro, votos nulos e brancos são desconsiderados no cômputo dos votos válidos, de modo que o voto nulo não anula a eleição e apenas delega a outros a escolha do futuro coletivo.

TEXTO III
A abstenção e o voto nulo em massa revelam o cansaço democrático, mas raramente resultam em reformas estruturais sem organização popular ativa fora dos períodos eleitorais."""
        ),
        (
            "A riqueza de poucos beneficia a sociedade inteira?", 2017, "UNESP 2017", "UNESP", "Economia & Desigualdade", "Médio",
            "Examina a teoria do gotejamento econômico (trickle-down) frente à concentração recorde de renda e patrimônio no século XXI.",
            """TEXTO I
A teoria econômica liberal clássica sustenta que a acumulação de capital por grandes empresários gera investimentos, empregos e inovação tecnológica, cujos benefícios eventualmente 'gotejam' para as camadas mais pobres da sociedade.

TEXTO II
Contudo, relatórios da Oxfam e estudos do economista Thomas Piketty demonstram que o retorno sobre o capital tem crescido a taxas superiores ao crescimento da economia real, aprofundando a concentração de patrimônio no 1% mais rico e aumentando a disparidade social.

TEXTO III
Sem tributação progressiva sobre heranças e grandes fortunas combinada com investimentos em serviços públicos universais, a riqueza de poucos tende a gerar privilégios dinásticos e estagnação social."""
        ),
        (
            "Publicação de imagens trágicas: entre o direito à informação e a exploração do sofrimento", 2016, "UNESP 2016", "UNESP", "Comunicação & Ética", "Médio",
            "Mede os limites éticos do fotojornalismo ao expor corpos de vítimas de desastres e guerras em busca de audiência.",
            """TEXTO I
Imagens emblemáticas de sofrimento — como a foto do menino sírio Alan Kurdi em uma praia europeia — comoveram governos e despertaram a solidariedade internacional para a tragédia dos refugiados de guerra.

TEXTO II
Entretanto, a circulação desenfreada de fotografias de cadáveres e vítimas de crimes violentos nas redes sociais e em programas televisivos sensacionalistas cruza a fronteira da notícia para se tornar exploração mórbida da dor alheia.

TEXTO III
A ensaísta Susan Sontag, em 'Diante da Dor dos Outros', questiona se a saturação visual de atrocidades nos torna mais compassivos ou apenas anestesia nossa capacidade moral de comoção genuína."""
        ),
        (
            "O legado da escravidão e o preconceito contra negros no Brasil", 2015, "UNESP 2015", "UNESP", "Sociedade & História", "Médio",
            "Trata da abolição inconclusa de 1888, que libertou os escravizados sem conceder terras, escolas ou cidadania básica.",
            """TEXTO I
A Lei Áurea de 1888 aboliu formalmente a escravidão mercantil, mas não foi acompanhada por nenhuma medida de reparação ou integração socioeconômica da população negra, que foi atirada às periferias e aos subempregos.

TEXTO II
Esse abandono deliberado sedimentou as bases do racismo estrutural brasileiro contemporâneo, no qual cor da pele e condições de vulnerabilidade continuam estatisticamente entrelaçadas em presídios, favelas e estatísticas de desemprego.

TEXTO III
Reconhecer que o racismo é um legado histórico vivo é o primeiro passo para a implementação e consolidação de políticas públicas afirmativas, como cotas em universidades e concursos públicos."""
        )
    ]

    for item in unesp_list:
        temas.append({
            'titulo': item[0],
            'ano': str(item[1]),
            'origem': item[2],
            'banca': item[3],
            'eixo_tematico': item[4],
            'genero_textual': 'Dissertativo-Argumentativo',
            'dificuldade': item[5],
            'orientacoes_especificas': 'Responda claramente à questão-tema proposta com tese consistente e reflexão dialética. A proposta de intervenção social NÃO é obrigatória na VUNESP. Título valorizado.',
            'descricao': item[6],
            'textos_motivadores': item[7]
        })

    # =========================================================================
    # FUVEST / USP (2000 a 2024)
    # =========================================================================
    fuvest_list = [
        (
            "Educação básica e formação física: além dos limites do utilitarismo", 2024, "FUVEST 2024", "FUVEST", "Educação & Filosofia", "Difícil",
            "Exige refletir sobre a educação que não visa apenas treinar mão de obra para o mercado, mas integrar o intelecto, a sensibilidade artística e a cultura corporal.",
            """TEXTO I
A civilização grega clássica formulava o ideal da 'paideia': a formação integral do cidadão que unia filosofia, matemática, música e ginástica, entendendo o corpo e a mente como dimensões inseparáveis da virtude cívica.

TEXTO II
Na sociedade hipercompetitiva atual, a educação básica foi colonizada por uma visão utilitarista de curto prazo, voltada quase exclusivamente para o treino de habilidades técnicas para o mercado de trabalho, marginalizando as artes e a educação física.

TEXTO III
Reduzir a escola a uma esteira de capacitação profissional mutila a experiência humana e empobrece a formação de cidadãos autônomos e críticos capazes de fruir a vida com plenitude."""
        ),
        (
            "Refugiados ambientais e a vulnerabilidade humana", 2023, "FUVEST 2023", "FUVEST", "Meio Ambiente & Direitos Humanos", "Difícil",
            "Aborda o deslocamento forçado de populações em decorrência de secas extremas, elevação do nível do mar e desastres climáticos, e o vácuo jurídico internacional sobre o tema.",
            """TEXTO I
As mudanças climáticas deixaram de ser uma projeção abstrata para o futuro e já expulsam milhões de pessoas de suas terras natais devido ao avanço dos desertos, inundações catastróficas e elevação do nível dos oceanos.

TEXTO II
A Convenção de Genebra de 1951 reconhece como refugiados apenas aqueles perseguidos por motivos de raça, religião ou opinião política, deixando os migrantes ambientais em um limbo jurídico sem proteção diplomática obrigatória.

TEXTO III
A crise climática escancara a vulnerabilidade universal da vida humana sobre a Terra, exigindo um novo pacto cosmopolita de solidariedade que supere as fronteiras territoriais egoístas dos Estados nacionais."""
        ),
        (
            "As diferentes faces do riso", 2022, "FUVEST 2022", "FUVEST", "Filosofia & Comportamento", "Difícil",
            "Reflete sobre o riso como instrumento de transgressão e resistência dos oprimidos versus o riso como deboche preconceituoso que humilha o fraco.",
            """TEXTO I
O riso é uma manifestação exclusivamente humana. O filósofo Henri Bergson apontou que o riso tem uma função social: corrigir a rigidez mecânica dos costumes e restabelecer a flexibilidade do convívio comunitário.

TEXTO II
Contudo, o riso possui duas faces antagônicas: pode ser a arma libertadora dos bufões e cronistas que ridicularizam os poderosos, ou a gargalhada cruel do tirano que humilha o vulnerável por meio do escárnio e do preconceito.

TEXTO III
Saber de que se ri e com quem se ri é um termômetro moral de uma sociedade, revelando suas cumplicidades inconscientes e seus pactos de civilidade."""
        ),
        (
            "O mundo contemporâneo está fora de ordem?", 2021, "FUVEST 2021", "FUVEST", "Filosofia & Geopolítica", "Muito Difícil",
            "Inspirado em Hamlet ('O tempo está fora dos eixos'), discute a instabilidade geopolítica, crises ecológicas e a perda de certezas do mundo moderno.",
            """TEXTO I
A frase de Shakespeare em Hamlet — 'O tempo está fora dos eixos; oh, maldito rancor / Que eu tenha nascido para pô-lo em ordem!' — sintetiza a angústia de épocas de transição em que o velho mundo já morreu e o novo ainda não pôde nascer.

TEXTO II
O século XXI testemunha a erosão dos consensos democráticos pós-Segunda Guerra, a emergência de autocracias digitais, a escalada de desastres climáticos e o retorno do fantasma da guerra nuclear.

TEXTO III
Perguntar se o mundo está fora de ordem pressupõe questionar qual era a 'ordem' anterior e se a desordem atual não é o sintoma necessário para a gestação de uma justiça social mais ampla."""
        ),
        (
            "O papel da ciência no mundo contemporâneo", 2020, "FUVEST 2020", "FUVEST", "Ciência & Sociedade", "Médio",
            "Discute a autoridade do método científico frente ao negacionismo, à pós-verdade e às teorias da conspiração.",
            """TEXTO I
A ciência moderna foi a maior força transformadora da humanidade, duplicando a expectativa de vida, erradicando pestes e desvendando os mistérios do cosmo através da observação rigorosa e da dúvida metódica.

TEXTO II
No entanto, na era dos algoritmos de polarização, a autoridade científica é desafiada pelo obscurantismo de movimentos antivacina, negacionistas do clima e propagadores de teorias conspiratórias que colocam opiniões leigas em pé de igualdade com séculos de pesquisa.

TEXTO III
Defender a ciência requer não transformá-la em um novo dogma inacessível, mas aproximar a pesquisa acadêmica da população através de uma comunicação pública clara e transparente."""
        )
    ]

    for item in fuvest_list:
        temas.append({
            'titulo': item[0],
            'ano': str(item[1]),
            'origem': item[2],
            'banca': item[3],
            'eixo_tematico': item[4],
            'genero_textual': 'Dissertativo-Argumentativo',
            'dificuldade': item[5],
            'orientacoes_especificas': 'Dissertação de alta densidade reflexiva e filosófica. Título OBRIGATÓRIO na FUVEST. Evite fórmulas prontas. Conclusão analítica consistente.',
            'descricao': item[6],
            'textos_motivadores': item[7]
        })

    # =========================================================================
    # UNICAMP (2015 a 2024)
    # =========================================================================
    unicamp_list = [
        (
            "Artigo de opinião sobre o impacto social e econômico das apostas esportivas online (bets)", 2024, "UNICAMP 2024", "UNICAMP", "Economia & Juventude", "Médio",
            "A proposta convida o candidato a escrever como um jovem articulista sobre o vício em apostas online, endividamento das famílias e publicidade abusiva.",
            """TEXTO I
A legalização e a proliferação das plataformas de apostas online de quota fixa (bets) transformaram o celular do brasileiro em um cassino de bolso em tempo integral, patrocinando quase todos os clubes de futebol e programas de mídia do país.

TEXTO II
Dados do Banco Central e de associações do varejo indicam que famílias de baixa renda transferem bilhões de reais mensalmente para empresas de apostas sediadas em paraísos fiscais, sacrificando recursos que seriam destinados à alimentação e ao vestuário.

TEXTO III
O ludopatia (vício patológico em jogos) é classificado pela OMS como transtorno mental grave, exigindo restrições severas à publicidade voltada a menores e suporte psicológico na rede pública de saúde."""
        ),
        (
            "Carta aberta contra a violência política de gênero nas universidades", 2024, "UNICAMP 2024 (P2)", "UNICAMP", "Gênero & Cidadania", "Médio",
            "Redigir uma carta aberta de uma liderança estudantil denunciando ataques e intimidações machistas contra estudantes candidatas em órgãos universitários.",
            """TEXTO I
A violência política de gênero consiste em todo ato de agressão física, verbal, psicológica ou virtual direcionado a mulheres com o objetivo de constrangê-las, silenciá-las ou afastá-las dos espaços de decisão pública e representação coletiva.

TEXTO II
Estudos em ambientes acadêmicos demonstram que jovens estudantes e professoras que assumem papel ativo em grêmios, centros acadêmicos e sindicatos são alvos frequentes de ameaças anônimas e desqualificação de suas capacidades intelectuais.

TEXTO III
A Lei nº 14.192/2021 tipificou o crime de violência política contra a mulher, garantindo direitos de participação política plena sem preconceito de sexo e exigindo medidas disciplinares rigorosas de instituições de ensino superior."""
        ),
        (
            "Artigo de opinião sobre o trabalho análogo à escravidão na indústria da moda", 2023, "UNICAMP 2023", "UNICAMP", "Trabalho & Direitos Humanos", "Médio",
            "Artigo de opinião debatendo a cumplicidade de grandes redes de fast fashion na terceirização predatória em oficinas clandestinas.",
            """TEXTO I
O modelo do fast fashion estimula o consumo vertiginoso de roupas baratas com dezenas de coleções lançadas por ano. Para sustentar margens astronômicas de lucro, grandes varejistas terceirizam a costura para cadeias opacas que exploram trabalhadores vulneráveis.

TEXTO II
Fiscalizações do Ministério do Trabalho continuam resgatando imigrantes e brasileiros em oficinas insalubres em capitais como São Paulo, trabalhando até 16 horas diárias trancados sob ameaça de dívidas forjadas e sem registro em carteira.

TEXTO III
A responsabilidade jurídica e reputacional não pode recair apenas sobre o subcontratado intermediário: as marcas finais devem ser responsabilizadas pela auditoria de toda a sua cadeia de fornecedores."""
        )
    ]

    for item in unicamp_list:
        temas.append({
            'titulo': item[0],
            'ano': str(item[1]),
            'origem': item[2],
            'banca': item[3],
            'eixo_tematico': item[4],
            'genero_textual': 'Artigo de Opinião / Carta',
            'dificuldade': item[5],
            'orientacoes_especificas': 'Atenda ao gênero textual solicitado (Artigo ou Carta), mantendo interlocução autoral e apropriação crítica da coletânea de textos sem cópia.',
            'descricao': item[6],
            'textos_motivadores': item[7]
        })

    # =========================================================================
    # UERJ (2015 a 2024 - Baseadas em Obras Literárias)
    # =========================================================================
    uerj_list = [
        (
            "A loucura, a razão e a legitimidade da autoridade sobre o indivíduo (O Alienista)", 2024, "UERJ 2024", "UERJ", "Filosofia & Poder", "Difícil",
            "A partir da novela machadiana 'O Alienista' e de Simão Bacamarte, discutir até que ponto o poder tem o direito de classificar e punir o que considera desvio da norma.",
            """TEXTO I
Em 'O Alienista', de Machado de Assis, o médico Simão Bacamarte cria a Casa Verde em Itaguaí para internar os desequilibrados mentais da vila. Movido pela vaidade científica, amplia sucessivamente seus critérios até confinar quase quatro quintos dos habitantes, revelando a arbitrariedade do poder que se veste de razão técnica.

TEXTO II
O filósofo Michel Foucault, em 'História da Loucura', demonstrou como o confinamento asilar e a psiquiatria clássica funcionaram historicamente como instrumentos de controle social para neutralizar dissidências e manter a ordem burguesa.

TEXTO III
A fronteira entre razão e desrazão, normalidade e patologia é fluida e politicamente construída, advertindo a sociedade contra governantes e especialistas que se outorgam o monopólio da verdade."""
        ),
        (
            "A mercantilização da vida humana e os limites éticos da ciência (Não Me Abandone Jamais)", 2023, "UERJ 2023", "UERJ", "Bioética & Sociedade", "Difícil",
            "Com base no romance de Kazuo Ishiguro, refletir sobre a exploração de vidas humanas tratadas como instrumentos de descarte em benefício de terceiros.",
            """TEXTO I
Na obra 'Não Me Abandone Jamais', clones humanos são criados em internatos aparentemente afetuosos com o único propósito de doar seus órgãos vitais na vida adulta até a 'conclusão' (morte), enquanto a sociedade normal desfruta de saúde perfeita ignorando a dor dos doadores.

TEXTO II
A distopia literária dialoga diretamente com as inquietações contemporâneas da bioética: o transplante de órgãos, o mercado de úteros de substituição e a edição genética criam o risco de novas castas humanas submetidas ao utilitarismo econômico.

TEXTO III
O imperativo categórico kantiano preconiza que o ser humano deve ser sempre tratado como um fim em si mesmo, e jamais como um meio ou instrumento para atender aos interesses de outrem."""
        )
    ]

    for item in uerj_list:
        temas.append({
            'titulo': item[0],
            'ano': str(item[1]),
            'origem': item[2],
            'banca': item[3],
            'eixo_tematico': item[4],
            'genero_textual': 'Dissertativo-Argumentativo',
            'dificuldade': item[5],
            'orientacoes_especificas': 'Desenvolva uma dissertação reflexiva dialogando com a obra literária indicada e com as grandes problemáticas éticas e políticas da condição humana contemporânea.',
            'descricao': item[6],
            'textos_motivadores': item[7]
        })

    return temas

print("Módulo histórico carregado com sucesso.")
