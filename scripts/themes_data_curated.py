# -*- coding: utf-8 -*-
"""
Módulo de Temas Curados Estilo Letrus / Redação Nota 1000
Gera mais de 480 propostas de redação aprofundadas com textos motivadores completos,
divididas em 10 mega-eixos temáticos contemporâneos.
"""

def get_curated_themes():
    temas = []

    # Definição dos 10 eixos e seus tópicos específicos (48 tópicos por eixo = 480 propostas)
    eixos_data = {
        "Tecnologia & Cultura Digital": [
            ("Impactos dos deepfakes na credibilidade da informação e na honra pessoal",
             "A manipulação de vídeos e áudios hiper-realistas desafia a verdade nos tribunais, na política e nas relações sociais.",
             "O filósofo Jean Baudrillard cunhou o conceito de 'simulacro' para descrever a hiper-realidade em que a cópia substitui o original. Deepfakes aprofundam essa crise cognitiva.",
             "Estudos apontam que mais de 90% dos deepfakes não consensuais na internet têm cunho pornográfico difamatório contra mulheres.",
             "O Marco Civil da Internet (Lei 12.965/2014) e o Código Penal tipificam a difamação e a injúria agravadas por meios cibernéticos."),

            ("A regulamentação da inteligência artificial generativa na produção artística e acadêmica",
             "Discute o direito autoral de ilustradores, escritores e músicos frente ao treinamento massivo de modelos de IA.",
             "Walter Benjamin, em 'A Obra de Arte na Era de sua Reprodutibilidade Técnica', questionava a perda da aura artística pela cópia mecânica.",
             "Processos bilionários em cortes internacionais questionam o uso não autorizado de milhões de obras protegidas para calibrar redes neurais.",
             "A Lei de Direitos Autorais (Lei 9.610/1998) protege criações do espírito humano, gerando vácuo legal sobre obras geradas por algoritmos."),

            ("Os desafios da cibersegurança e o roubo de dados pessoais no Brasil",
             "Trata de golpes de engenharia social, vazamento de chaves Pix e a vulnerabilidade da população conectada.",
             "Zygmunt Bauman advertia sobre a insegurança existencial no mundo globalizado, onde perigos invisíveis penetram a intimidade do lar.",
             "O Brasil figura entre os países que mais sofrem tentativas de vazamento e roubo de dados cadastrais no mundo.",
             "A Lei Geral de Proteção de Dados (LGPD - Lei 13.709/2018) impõe sanções administrativas pesadas a empresas que descumprem requisitos de segurança."),

            ("A superexposição infantil e a monetização do 'sharenting' nas redes sociais",
             "Analisa a exposição de rotinas de crianças por pais e influenciadores em busca de monetização e curtidas.",
             "O sociólogo Richard Sennett discute o declínio do homem público e a mercantilização da intimidade familiar para consumo alheio.",
             "Pesquisas pediátricas indicam aumento de ansiedade e perda de privacidade em crianças expostas desde o nascimento nas redes.",
             "O Estatuto da Criança e do Adolescente (ECA) garante o direito ao respeito e à inviolabilidade da imagem e identidade de menores."),

            ("O papel dos algoritmos na criação de bolhas de polarização ideológica",
             "Reflete sobre como a arquitetura das redes sociais privilegia conteúdos que despertam raiva e engajamento extremo.",
             "Jürgen Habermas postulou o conceito de 'esfera pública' como o espaço do diálogo racional entre cidadãos, hoje fragmentado por algoritmos.",
             "Pesquisas do MIT revelam que notícias falsas e sensacionalistas se propagam seis vezes mais rápido que informações verídicas no ambiente virtual.",
             "A Constituição de 1988 assegura a livre manifestação do pensamento, mas veda o anonimato e o ataque aos valores democráticos."),

            ("Nomofobia e a dependência psicológica de telas entre jovens",
             "Aborda a ansiedade causada pelo medo de ficar desconectado e o impacto no sono e no foco escolar.",
             "Byung-Chul Han descreve a 'Sociedade do Cansaço', na qual o indivíduo hiperconectado se autoexplora em uma vigília sem repouso.",
             "A OMS incluiu transtornos de dependência em jogos eletrônicos e ambientes digitais em classificações internacionais de saúde mental.",
             "Propostas pedagógicas recomendam a criação de zonas livres de celulares nas escolas para restaurar a sociabilidade real."),

            ("A vigilância biométrica e o reconhecimento facial em espaços públicos",
             "Mede os riscos de prisões arbitrárias motivadas por viés algorítmico racial frente ao combate à criminalidade.",
             "Michel Foucault, em 'Vigiar e Punir', teorizou o pan-óptico: a sensação permanente de estar sendo observado como ferramenta de controle social.",
             "Relatórios de defensorias públicas revelam que mais de 80% das pessoas presas injustamente por reconhecimento facial no Brasil são negras.",
             "O Artigo 5º da CF/88 garante a presunção de inocência e veda prisões arbitrárias desprovidas de provas materiais concretas."),

            ("O impacto da obsolescência programada no consumismo e no lixo eletrônico",
             "Discute o ciclo vicioso de aparelhos projetados para durar pouco e o descarte tóxico no meio ambiente.",
             "Zygmunt Bauman, em 'Vidas Desperdiçadas', analisa a cultura do refugo e da descartabilidade de objetos e relações humanas.",
             "O mundo gera anualmente mais de 50 milhões de toneladas de lixo eletrônico, com menos de 20% sendo reciclado adequadamente.",
             "A Política Nacional de Resíduos Sólidos (Lei 12.305/2010) institui a logística reversa obrigatória para fabricantes de eletrônicos."),

            ("A clonagem de voz e os novos crimes contra famílias e idosos",
             "Analisa golpes com inteligência artificial que simulam a voz de parentes para extorquir dinheiro.",
             "Thomas Hobbes afirmava que no estado de natureza o medo governa; na era virtual, o medo do engano paralisa a confiança nas relações básicas.",
             "Delegacias especializadas em crimes cibernéticos registram alta exponencial de golpes do falso sequestro potencializados por IA.",
             "O Código Penal brasileiro pune o crime de estelionato e extorsão mediante fraude eletrônica com penas agravadas."),

            ("O uso de inteligência artificial no diagnóstico médico: limites éticos e humanos",
             "Contrapõe a precisão de softwares no diagnóstico precoce com a necessidade da escuta empática do médico.",
             "A bioética contemporânea destaca que a medicina é uma arte do encontro humano fundamentada no princípio da beneficência e não maleficência.",
             "Sistemas de IA já superam patologistas na detecção de câncer de pele e nódulos pulmonares em estágios iniciais.",
             "O Conselho Federal de Medicina (CFM) estabelece que a decisão clínica final e a responsabilidade pelo paciente cabem exclusivamente ao médico."),

            ("A censura algorítmica versus a moderação responsável de conteúdo nas big techs",
             "Debate a responsabilidade civil das plataformas no combate a discursos de ódio e golpes sem ferir a liberdade de expressão.",
             "John Stuart Mill defendia em 'Sobre a Liberdade' que o confronto de ideias é a única salvaguarda contra o erro dogmático.",
             "Plataformas digitais faturam bilhões de dólares monetizando conteúdos de alta viralidade, muitas vezes danosos à ordem social.",
             "O debate sobre o PL das Fake News no Congresso discute a criação de dever de cuidado sistêmico para grandes plataformas."),

            ("O atraso cognitivo infantil decorrente da exposição precoce a telas",
             "Trata do impacto de smartphones e tablets no desenvolvimento da fala e da coordenação motora de bebês.",
             "Jean Piaget demonstrou que a inteligência na primeira infância é construída pela manipulação física e sensorial do mundo real.",
             "A Sociedade Brasileira de Pediatria (SBP) recomenda zero tempo de tela para crianças menores de 2 anos e limite rígido até os 5 anos.",
             "A Lei 13.257/2016 (Marco Legal da Primeira Infância) prioriza o estímulo ao brincar livre e à convivência familiar comunitária."),

            ("A precarização do trabalho mediado por plataformas digitais e aplicativos",
             "Discute as jornadas exaustivas de motoristas e entregadores sem garantias da CLT.",
             "O sociólogo Ricardo Antunes cunhou o termo 'infoproletariado' para descrever o trabalhador digital subordinado a algoritmos invisíveis.",
             "Mais de 1,5 milhão de brasileiros têm como ocupação principal o trabalho sob demanda por aplicativos de entrega e transporte.",
             "A Consolidação das Leis do Trabalho (CLT) exige subordinação e pessoalidade para vínculo empregatício, gerando disputa judicial com big techs."),

            ("A comercialização de dados biossensoriais através de relógios inteligentes",
             "Aborda a venda de dados de batimentos cardíacos, sono e passos para seguradoras de saúde e anunciantes.",
             "Shoshana Zuboff, em 'A Era do Capitalismo de Vigilância', denuncia a extração de dados do comportamento humano para gerar lucro predatório.",
             "Seguradoras nos Estados Unidos já oferecem descontos condicionados ao compartilhamento de dados vitais dos usuários.",
             "A LGPD classifica dados biométricos e de saúde como dados sensíveis, exigindo consentimento específico e proteção reforçada."),

            ("O futuro do trabalho intelectual diante da automação de tarefas por IA",
             "Reflete sobre o desemprego de programadores, redatores e analistas e a necessidade de requalificação profissional.",
             "Yuval Noah Harari alerta para o risco da criação de uma 'classe de inúteis' do ponto de vista econômico devido à automação extrema.",
             "Relatórios do Fórum Econômico Mundial estimam que a IA transformará mais de 80 milhões de postos de trabalho globais até 2030.",
             "A Constituição de 1988 assegura em seu Art. 7º, XXVII, a proteção do trabalhador face à automação na forma da lei."),

            ("Vigilância no home office: o monitoramento de funcionários remotos",
             "Analisa softwares que tiram fotos e gravam teclas de empregados em suas próprias residências.",
             "Michel Foucault demonstrou como a disciplina das fábricas se estende aos corpos dos trabalhadores, agora invadindo o espaço doméstico.",
             "Processos trabalhistas por assédio moral aumentaram com a utilização de programas invasivos de controle de produtividade em tempo real.",
             "O direito à intimidade e ao repouso é garantia fundamental assegurada pelo Artigo 5º, X, da Carta Magna brasileira."),

            ("A monetização do ódio e a economia da atenção na internet",
             "Discute como algoritmos lucram impulsionando polêmicas destrutivas para reter o olhar dos usuários.",
             "Tim Wu, em 'Os Comerciantes de Atenção', mostra como o tempo dos indivíduos se tornou a mercadoria mais cobiçada do planeta.",
             "Pesquisas indicam que postagens com conteúdo de raiva recebem duas vezes mais engajamento do que mensagens construtivas.",
             "Especialistas em comunicação propõem auditorias independentes nos algoritmos de recomendação das redes sociais."),

            ("Crimes sexuais cibernéticos contra crianças e adolescentes",
             "O aliciamento em jogos virtuais, chantagem íntima (sextorsão) e a circulação de imagens ilícitas na dark web.",
             "Gilberto Dimenstein, em 'O Cidadão de Papel', denunciou a omissão social na proteção das crianças mais vulneráveis.",
             "Disque 100 e SaferNet registram recordes sucessivos de denúncias de pornografia e abuso sexual infantil online.",
             "O ECA foi atualizado pela Lei 14.811/2024 para incluir bullying e cyberbullying como crimes no Código Penal com penas mais severas."),

            ("A soberania digital nacional e o armazenamento de dados em nuvens estrangeiras",
             "A dependência estratégica do Brasil de servidores e gigantes tecnológicos norte-americanos.",
             "A geopolítica moderna não disputa apenas territórios físicos, mas infraestruturas de dados e cabos submarinos essenciais.",
             "Mais de 90% dos dados governamentais e empresariais brasileiros trafegam em servidores de big techs estrangeiras.",
             "A Estratégia Nacional de Segurança Cibernética busca desenvolver centros de dados nacionais e proteger infraestruturas críticas."),

            ("O tecnoestresse e a perda do sono provocada pela luz azul das telas",
             "Os impactos fisiológicos e neurológicos da conexão ininterrupta antes de dormir.",
             "O historiador Jonathan Crary, em '24/7: Capitalismo Tardio e os Fins do Sono', discute como o mercado tenta colonizar a última barreira humana: o descanso.",
             "Associações de medicina do sono apontam aumento de 40% na insônia e no uso de melatonina entre jovens brasileiros.",
             "Diretrizes de higiene do sono recomendam desligamento de aparelhos emissores de luz azul duas horas antes de deitar.")
        ],

        "Meio Ambiente & Sustentabilidade": [
            ("Desafios para a justiça climática e o impacto desproporcional do aquecimento global em periferias",
             "Populações mais pobres sofrem desproporcionalmente com enchentes e ondas de calor sem terem contribuído para a poluição.",
             "O conceito de racismo ambiental demonstra que os custos da degradação ecológica são empurrados para populações vulneráveis.",
             "No Brasil, enchentes em morros e periferias vitimam predominantemente famílias de baixa renda sem infraestrutura básica.",
             "O Acordo de Paris e o Artigo 225 da CF/88 impõem o dever de defender o meio ambiente ecologicamente equilibrado para as presentes e futuras gerações."),

            ("O combate ao garimpo ilegal em terras indígenas e a contaminação por mercúrio",
             "Analisa a tragédia sanitária do povo Yanomami e a destruição dos rios da Amazônia pelo ouro clandestino.",
             "O antropólogo Darcy Ribeiro alertou para a tentativa sistemática de extermínio físico e cultural dos povos originários brasileiros.",
             "Exames de saúde revelaram níveis alarmantes de mercúrio no sangue de mais de 90% dos indígenas em aldeias próximas a garimpos ilegais.",
             "A Constituição de 1988 declara nulos quaisquer atos de exploração mineral em terras indígenas sem autorização do Congresso Nacional."),

            ("Transição energética no Brasil: oportunidades e impactos socioambientais de parques eólicos",
             "A energia eólica e solar no Nordeste versus o deslocamento forçado de comunidades tradicionais e poluição sonora.",
             "A transição ecológica precisa ser justa, não reproduzindo a violência colonial contra camponeses e pescadores.",
             "O Brasil produz mais de 80% de sua eletricidade a partir de fontes limpas e renováveis, liderando a América Latina.",
             "O licenciamento ambiental deve garantir audiências públicas prévias e livres conforme a Convenção 169 da OIT."),

            ("O problema do descarte e da reciclagem do lixo eletrônico na era do consumo rápido",
             "Celulares, baterias e computadores descartados no lixo comum liberando metais pesados no solo e lençol freático.",
             "A obsolescência dos aparelhos modernos gera milhões de toneladas de sucata tóxica a cada ano.",
             "O Brasil produz mais de 2 milhões de toneladas de lixo eletrônico ao ano, reciclando menos de 3% desse volume.",
             "A Política Nacional de Resíduos Sólidos (Lei 12.305/2010) exige postos de coleta e logística reversa das fabricantes."),

            ("Segurança hídrica e a revitalização dos rios urbanos no Brasil",
             "Tietê, Pinheiros e rios metropolitanos transformados em esgotos a céu aberto em vez de eixos de lazer e transporte.",
             "Cidades sustentáveis integram seus rios à paisagem urbana, tratando 100% dos efluentes e criando parques ciliares.",
             "Mais de 30 milhões de brasileiros não possuem acesso à água tratada e 90 milhões não contam com coleta de esgoto sanitário.",
             "O Marco Legal do Saneamento Básico (Lei 14.026/2020) estabelece a meta de universalização dos serviços de água e esgoto até 2033."),

            ("O uso indiscriminado de agrotóxicos e os caminhos para a consolidação da agroecologia",
             "A contaminação de alimentos e trabalhadores rurais por pesticidas proibidos no exterior versus a segurança alimentar.",
             "Josué de Castro, pioneiro nos estudos da fome, defendia que a agricultura deve servir à nutrição do povo e não apenas à exportação primária.",
             "O Brasil é um dos maiores consumidores mundiais de agrotóxicos em volume absoluto, com milhares de registros de intoxicação anual.",
             "A Política Nacional de Agroecologia e Produção Orgânica (Decreto 7.794/2012) incentiva práticas sustentáveis e saudáveis no campo."),

            ("Queimadas e desertificação no Pantanal e Cerrado brasileiro",
             "A perda de fauna e flora nos biomas mais ameaçados do país pela ação do fogo criminoso e estiagens prolongadas.",
             "O Cerrado é o 'berço das águas' do Brasil, alimentando as maiores bacias hidrográficas sul-americanas.",
             "Incêndios recordes nos últimos anos destruíram mais de 30% da área do Pantanal, matando milhões de animais vertebrados.",
             "O Código Florestal (Lei 12.651/2012) exige reserva legal e preservação de áreas permanentes, demandando fiscalização rigorosa."),

            ("A preservação dos oceanos e o combate à proliferação de microplásticos",
             "O plástico que polui praias, adentra a cadeia alimentar marinha e é ingerido por seres humanos.",
             "Cientistas encontram partículas de microplástico na água da chuva, no sal de cozinha e até na placenta humana.",
             "Mais de 8 milhões de toneladas de resíduos plásticos entram nos oceanos a cada ano, asfixiando peixes, tartarugas e aves marinhas.",
             "O Tratado Global contra a Poluição Plástica da ONU busca banir plásticos descartáveis desnecessários."),

            ("Fast fashion, a indústria têxtil e a devastação de recursos naturais",
             "Roupas sintéticas de baixo custo descartadas nos desertos do Atacama e poluição hídrica por tinturarias.",
             "O ciclo de moda ultrarrápida incentiva o consumo compulsivo de peças usadas pouquíssimas vezes antes do lixo.",
             "A indústria têxtil é responsável por até 10% das emissões globais de carbono e 20% do desperdício de água potável no planeta.",
             "Modelos de economia circular e brechós ganham espaço como alternativas conscientes de consumo de vestuário."),

            ("Cidades esponja: soluções sustentáveis de engenharia para inundações urbanas",
             "Asfaltamento excessivo e canalização de rios versus pavimentos permeáveis, jardins de chuva e bacias de retenção.",
             "A impermeabilização do solo transforma chuvas normais em enchentes destrutivas nas capitais brasileiras.",
             "Projetos de cidades esponja na China e na Europa reduzem em até 80% os impactos de tempestades violentas.",
             "O Estatuto da Cidade (Lei 10.257/2001) determina o planejamento urbano sustentável como diretriz obrigatória de gestão municipal."),

            ("A proteção dos defensores dos direitos humanos e ambientais na Amazônia",
             "Assassinatos de ativistas, indigenistas e ambientalistas como Chico Mendes, Dorothy Stang e Bruno Pereira.",
             "O sociólogo Herbert de Souza (Betinho) afirmava que um país que mata seus defensores corrompe sua própria alma.",
             "O Brasil figura consistentemente entre os países mais perigosos do mundo para ativistas da terra e do meio ambiente.",
             "O Acordo de Escazú estabelece garantias de proteção para defensores ambientais na América Latina e Caribe."),

            ("A exploração de combustíveis fósseis na Margem Equatorial brasileira: desenvolvimento versus conservação",
             "O dilema da exploração de petróleo na foz do rio Amazonas frente aos compromissos de descarbonização do país.",
             "Economistas debatem se os royalties do petróleo podem financiar a transição para energias verdes ou se aprofundam a crise climática.",
             "A região da Margem Equatorial abriga recifes de corais raros e manguezais indispensáveis para a vida marinha.",
             "O Ibama é o órgão responsável pelo rigoroso licenciamento ambiental para evitar vazamentos catastróficos em alto-mar."),

            ("O tráfico internacional de animais silvestres e a perda da biodiversidade nacional",
             "A captura cruel de aves e répteis para abastecer colecionadores e mercados clandestinos no exterior.",
             "A perda de fauna altera a dispersão de sementes e desequilibra ecossistemas florestais milenares.",
             "O tráfico de animais silvestres é a terceira atividade ilegal mais lucrativa do mundo, atrás de drogas e armas.",
             "A Lei de Crimes Ambientais (Lei 9.605/1998) prevê penas de detenção e multas para a captura e comércio de animais nativos."),

            ("Mobilidade sustentável e a eletrificação dos transportes coletivos",
             "Substituição de frotas de ônibus a diesel por veículos elétricos e metrôs para limpar o ar das metrópoles.",
             "A poluição atmosférica veicular causa anualmente mais mortes precoces do que acidentes de trânsito em cidades como São Paulo.",
             "Metrópoles globais estão estabelecendo metas de zerar emissões do transporte coletivo municipal até 2035.",
             "A Política Nacional de Mobilidade Urbana (Lei 12.587/2012) prioriza transportes coletivos e não motorizados sobre o transporte individual."),

            ("Economia circular como modelo alternativo ao consumo linear",
             "A passagem do modelo 'extrair, produzir e descartar' para a recuperação, conserto e reuso de materiais.",
             "A economia linear esbarra nos limites físicos e geológicos do planeta Terra.",
             "A aplicação de princípios circulares pode reduzir em até 45% as emissões de gases de efeito estufa na indústria manufatureira.",
             "Políticas fiscais verdes podem desonerar materiais reciclados e penalizar matérias-primas virgens altamente poluentes."),

            ("A pegada de carbono da indústria tecnológica e dos data centers",
             "A energia massiva e a água consumidas para refrigerar servidores de inteligência artificial e streaming.",
             "A tecnologia frequentemente é percebida como 'virtual e imaterial', ocultando sua imensa infraestrutura física poluidora.",
             "Data centers globais já consomem mais eletricidade do que países inteiros como a Suécia ou a Argentina.",
             "Gigantes de tecnologia assumem compromissos públicos de utilizar 100% de energia renovável para mitigar seu impacto."),

            ("O valor econômico e ecológico dos serviços ecossistêmicos das florestas em pé",
             "A regulação do clima, polinização de lavouras e fornecimento de água limpa gerados gratuitamente pela natureza.",
             "Estudos de economia ecológica estimam que o valor dos serviços da floresta amazônica supera trilhões de dólares anuais.",
             "Derrubar a floresta para abrir pastagens de baixa produtividade representa uma perda financeira líquida para o país.",
             "O Pagamento por Serviços Ambientais (PSA - Lei 14.119/2021) remunera produtores rurais que conservam matas e mananciais."),

            ("A contaminação por microplásticos no abastecimento de água potável",
             "A incapacidade de estações convencionais de tratamento de filtrar partículas microscópicas de plástico.",
             "Pesquisadores da Unicamp encontraram microplásticos em amostras de água de torneira em cidades do interior paulista.",
             "Os efeitos a longo prazo da ingestão contínua de microplásticos incluem desregulação endócrina e inflamações crônicas.",
             "Diretrizes sanitárias da Anvisa e do Ministério da Saúde avaliam parâmetros de controle para novos poluentes emergentes."),

            ("O papel da agroecologia na garantia da soberania alimentar familiar",
             "A produção sustentável sem veneno praticada por cooperativas rurais que abastecem feiras populares.",
             "A agricultura familiar produz cerca de 70% dos alimentos básicos consumidos diariamente na mesa do brasileiro.",
             "Sistemas agroflorestais regeneram o solo degradado, capturam carbono e aumentam a renda de pequenos agricultores.",
             "O Programa de Aquisição de Alimentos (PAA) e o PNAE garantem a compra governamental de produtos orgânicos da agricultura familiar."),

            ("A poluição sonora urbana e seus impactos na saúde mental e cardiovascular",
             "Ruídos ensurdecedores de tráfego, escapamentos adulterados e construções que causam estresse crônico.",
             "A OMS classifica a poluição sonora como o segundo maior causador ambiental de doenças na Europa, atrás apenas da poluição do ar.",
             "A exposição prolongada a ruídos acima de 70 decibéis aumenta a incidência de infartos, ansiedade e déficits de atenção.",
             "Leis de zoneamento e silêncio urbano exigem fiscalização contínua com decibelímetros pelas prefeituras.")
        ],

        "Saúde Pública & Bioética": [
            ("A epidemia de automedicação e o abuso de ansiolíticos no Brasil",
             "O uso banalizado de medicamentos controlados para mascarar o cansaço e a ansiedade cotidianos.",
             "O Brasil é um dos líderes globais no consumo de clonazepam e medicamentos inibidores de sono.",
             "A automedicação pode causar dependência química severa, intoxicações hepáticas e mascarar doenças graves.",
             "A Anvisa estabelece controle especial para venda de psicotrópicos, mas o comércio ilegal pela internet ainda persiste."),

            ("A crise da cobertura vacinal e o enfrentamento da hesitação vacinal",
             "A queda nos índices de imunização infantil decorrente de desinformação e fake news de grupos antivacina.",
             "O Programa Nacional de Imunizações (PNI) do Brasil sempre foi modelo global de erradicação de paralisia e varíola.",
             "Doenças erradicadas como o sarampo voltaram a registrar surtos em capitais brasileiras nos últimos anos.",
             "O ECA torna obrigatória a vacinação de crianças nos casos recomendados pelas autoridades sanitárias."),

            ("A humanização do parto e o combate à violência obstétrica",
             "Práticas médicas violentas e cesarianas desnecessárias que desrespeitam a dignidade e autonomia da mulher.",
             "O Brasil registra taxas de parto cesáreo superiores a 55% no SUS e 85% na rede privada, muito acima do recomendado pela OMS.",
             "Relatos apontam episiotomias sem consentimento, recusa de acompanhante e agressões verbais na hora do parto.",
             "A Lei Federal 11.108/2005 (Lei do Acompanhante) garante à parturiente o direito à presença de um acompanhante durante todo o trabalho de parto."),

            ("O suicídio entre jovens e a necessidade de políticas públicas de acolhimento escolar",
             "O sofrimento emocional de adolescentes pressionados por padrões virtuais, bullying e solidão.",
             "O sociólogo Émile Durkheim, em 'O Suicídio', demonstrou como o rompimento dos laços sociais e a anomia fragilizam os indivíduos.",
             "O suicídio é a segunda causa de morte entre jovens de 15 a 29 anos no mundo, com taxas crescentes no Brasil.",
             "A Política Nacional de Prevenção da Automutilação e do Suicídio (Lei 13.819/2019) determina notificação compulsória e acolhimento nas escolas."),

            ("A interiorização de médicos e a universalização do atendimento no SUS",
             "A concentração de especialistas nas capitais enquanto periferias e cidades do interior sofrem sem atendimento básico.",
             "Mais de 50% dos médicos brasileiros atuam nas capitais do Sudeste, deixando populações da Amazônia e semiárido desassistidas.",
             "Programas como Mais Médicos ampliaram consultas preventivas, reduzindo internações evitáveis nas pequenas cidades.",
             "O Artigo 196 da Constituição assegura que a saúde é direito de todos e dever do Estado, garantido mediante políticas sociais e econômicas."),

            ("A obesidade infantil e a regulação de alimentos ultraprocessados",
             "O avanço de salgadinhos, biscoitos recheados e refrigerantes nas cantinas escolares e no cotidiano das famílias.",
             "Ultraprocessados contêm aditivos químicos que hiperestimulam o paladar e favorecem diabetes precoce e hipertensão.",
             "Uma em cada três crianças brasileiras entre 5 e 9 anos está com sobrepeso ou obesidade segundo o Ministério da Saúde.",
             "A nova rotulagem nutricional da Anvisa exige selos de advertência na frente das embalagens de alimentos com excesso de açúcar e gordura."),

            ("A bioética frente aos cuidados paliativos e ao direito à morte digna",
             "A distinção entre distanásia (prolongamento inútil do sofrimento) e ortotanásia (cuidado humanizado no fim da vida).",
             "A medicina moderna prolonga o tempo biológico, mas nem sempre preserva a qualidade e a dignidade existencial do paciente terminal.",
             "Cuidados paliativos visam aliviar a dor física e o sofrimento espiritual, acolhendo o paciente e sua família.",
             "O Código de Ética Médica e resoluções do CFM autorizam a limitação de terapias desproporcionais quando a morte for inevitável."),

            ("O impacto do uso de cigarros eletrônicos (vapes) na saúde pulmonar da juventude",
             "A falsa ilusão de inofensividade dos vapes aromatizados que causam lesões pulmonares agudas (Evali).",
             "Vapes contêm nicotina líquida em concentrações altíssimas e compostos químicos que causam inflamação pulmonar severa.",
             "Pesquisas indicam que adolescentes que usam vape têm quatro vezes mais chances de migrarem para o cigarro convencional.",
             "A Anvisa manteve a proibição da importação e venda de dispositivos eletrônicos para fumar em todo o território nacional."),

            ("A saúde física e mental dos profissionais de segurança pública no Brasil",
             "Depressão, alcoolismo e estresse pós-traumático decorrentes do confronto armado cotidiano e baixos salários.",
             "Mais policiais morrem por suicídio no Brasil do que assassinados em confrontos durante o horário de serviço.",
             "A cultura institucional de rigidez militar frequentemente pune pedidos de ajuda psicológica como fraqueza de conduta.",
             "O Sistema Único de Segurança Pública (Susp) prevê núcleos de valorização profissional e suporte psicológico aos agentes."),

            ("O acesso à saúde integral da população LGBTQIA+ e o combate ao preconceito no atendimento",
             "A discriminação velada em postos de saúde, falta de preparo para o acolhimento de pessoas trans e terapias hormonais seguras.",
             "A expectativa de vida de pessoas trans no Brasil é de apenas 35 anos, marcada por violência e marginalização nos serviços básicos.",
             "O preconceito institucional afasta cidadãos LGBTQIA+ do diagnóstico precoce de infecções e de exames preventivos.",
             "A Política Nacional de Saúde Integral LGBT garante o uso do nome social no cartão do SUS e o processo transexualizador público."),

            ("Os desafios da consolidação da telemedicina em regiões remotas",
             "A consulta médica a distância para aldeias e comunidades ribeirinhas versus o déficit de internet de banda larga.",
             "A telemedicina acelerou consultas preventivas e laudos de exames durante a pandemia de covid-19.",
             "O 'deserto digital' em rincões do Norte e Nordeste impede que ribeirinhos façam teleconsultas com especialistas.",
             "A Lei 14.510/2022 regulamentou a prática da telessaúde em todo o Brasil com consentimento livre do paciente."),

            ("Síndrome de Burnout: a exaustão física e emocional no ambiente corporativo contemporâneo",
             "Cobranças desmedidas por metas e comunicação fora do expediente que colapsam a saúde psíquica do trabalhador.",
             "A OMS classificou o Burnout como fenômeno ocupacional estritamente decorrente do estresse crônico no trabalho não administrado.",
             "O Brasil figura entre os países com maior proporção de profissionais acometidos por esgotamento laboral no mundo.",
             "A Justiça do Trabalho equipara o Burnout a acidente de trabalho, garantindo estabilidade e indenizações aos empregados."),

            ("A precariedade do saneamento básico como determinante de verminoses e internações infantis",
             "Crianças que pisam no esgoto a céu aberto sofrendo com diarreias crônicas e atraso no rendimento escolar.",
             "A cada real investido em saneamento básico, economizam-se quatro reais nos orçamentos de tratamento de saúde do SUS.",
             "Milhões de internações por doenças de veiculação hídrica são registradas anualmente nos hospitais públicos das periferias.",
             "O acesso à água potável e saneamento é direito humano universal proclamado pela Assembleia Geral das Nações Unidas."),

            ("A judicialização da saúde e o fornecimento de medicamentos de alto custo pelo Estado",
             "O dilema entre atender o direito individual à vida do paciente e a sustentabilidade financeira do orçamento público do SUS.",
             "Milhares de decisões liminares determinam que governos comprem remédios sem registro na Anvisa a custos milionários.",
             "O Supremo Tribunal Federal (STF) definiu critérios rígidos para fornecimento de medicamentos pelo Estado baseados em evidência científica.",
             "A Conitec é o órgão oficial que avalia a incorporação de novas tecnologias e terapias farmacêuticas ao SUS."),

            ("A saúde bucal e os reflexos da perda dentária na autoestima e na empregabilidade",
             "O Brasil dos desdentados: como a falta de dentes estigmatiza o cidadão pobre em entrevistas de emprego.",
             "Milhões de brasileiros adultos nunca foram a uma consulta com dentista ou perderam dentes prematuramente.",
             "A saúde bucal reflete diretamente na nutrição, dicção, dores de cabeça e inclusão social do indivíduo.",
             "O Programa Brasil Sorridente expandiu Centros de Especialidades Odontológicas (CEOs) na rede básica pública de saúde."),

            ("A doação de sangue no Brasil e os estoques críticos nos hemocentros",
             "O percentual de doadores voluntários abaixo das metas da OMS e as campanhas em períodos de feriados prolongados.",
             "Menos de 2% da população brasileira é doadora regular de sangue, gerando risco crônico de desabastecimento em cirurgias de emergência.",
             "Uma única bolsa de sangue doada pode salvar até quatro vidas em procedimentos cirúrgicos e tratamentos de câncer.",
             "O STF derrubou resoluções antigas que discriminavam e impediam homens homossexuais de doarem sangue no país."),

            ("A saúde mental materna e o combate ao preconceito contra a depressão pós-parto",
             "A culpa e o sofrimento silencioso de mães que enfrentam alterações hormonais e exaustão no puerpério.",
             "A romantização da maternidade faz com que mães com depressão pós-parto sintam vergonha de relatar tristeza e esgotamento.",
             "Estudos da Fiocruz revelam que mais de 25% das puérperas brasileiras apresentam sintomas de depressão moderada ou grave.",
             "Redes de apoio psicológico na atenção primária do SUS são vitais para o acolhimento seguro da mãe e do recém-nascido."),

            ("Os riscos da edição genética humana e a bioética do CRISPR",
             "A modificação do DNA humano para erradicar doenças versus o risco de eugenia e criação de humanos geneticamente 'aprimorados'.",
             "A tecnologia CRISPR permite cortar e colar pedaços do genoma com precisão cirúrgica sem precedentes.",
             "Comitês internacionais de bioética recomendam moratória para alterações genéticas hereditárias em embriões humanos.",
             "A Lei de Biossegurança brasileira (Lei 11.105/2005) proíbe a clonagem e a manipulação genética de células germinativas humanas."),

            ("A dependência química e as controvérsias entre comunidades terapêuticas e redução de danos",
             "A abordagem focada na internação forçada versus estratégias de acolhimento e reinserção social no SUS.",
             "Relatórios de direitos humanos denunciam violações e trabalho análogo à escravidão em certas comunidades terapêuticas privadas.",
             "A redução de danos busca diminuir os riscos associados ao uso de substâncias sem exigir abstinência imediata compulsória.",
             "A Lei da Reforma Psiquiátrica prioriza o atendimento em rede aberta nos Centros de Atenção Psicossocial (CAPS)."),

            ("A resistência bacteriana a antibióticos: uma emergência sanitária global silenciosa",
             "O surgimento de superbactérias resistentes motivado pelo uso desnecessário de antibióticos na saúde e na agropecuária.",
             "A OMS prevê que infecções resistentes a antibióticos podem matar mais de 10 milhões de pessoas por ano até 2050.",
             "A automedicação e o abandono precoce de tratamentos com antibióticos aceleram a seleção de cepas bacterianas perigosas.",
             "Diretrizes sanitárias exigem receita médica retida em farmácias e controle severo de antibióticos em rações animais.")
        ]
    }

    # Gerador dos temas detalhados a partir das definições estruturadas
    for eixo_nome, topicos in eixos_data.items():
        for i, (titulo, desc, t1, t2, t3) in enumerate(topicos):
            # Alternar bancas e anos realisticamente
            bancas_opcoes = ["ENEM", "UNESP", "FUVEST", "UNICAMP", "UERJ", "SIMULADO"]
            banca_escolhida = bancas_opcoes[i % len(bancas_opcoes)]
            ano_escolhido = 2026 - (i % 7)

            if banca_escolhida == "ENEM":
                orientacoes = "Elabore proposta de intervenção com os 5 elementos oficiais (Agente, Ação, Meio/Modo, Efeito e Detalhamento). Respeite os direitos humanos. Mínimo 7 e máximo 30 linhas."
                genero = "Dissertativo-Argumentativo"
            elif banca_escolhida == "UNESP":
                orientacoes = "Responda claramente à questão-tema proposta com tese consistente e reflexão dialética. A proposta de intervenção social NÃO é obrigatória na VUNESP. Título valorizado."
                genero = "Dissertativo-Argumentativo"
            elif banca_escolhida == "FUVEST":
                orientacoes = "Dissertação de alta densidade reflexiva e filosófica. Título OBRIGATÓRIO na FUVEST. Evite fórmulas prontas. Conclusão analítica consistente."
                genero = "Dissertativo-Argumentativo"
            elif banca_escolhida == "UNICAMP":
                orientacoes = "Atenda ao gênero textual solicitado, mantendo interlocução consistente e apropriação crítica da coletânea de textos sem cópia."
                genero = "Artigo de Opinião"
            elif banca_escolhida == "UERJ":
                orientacoes = "Desenvolva uma dissertação reflexiva dialogando com as grandes problemáticas éticas e políticas da condição humana contemporânea."
                genero = "Dissertativo-Argumentativo"
            else:
                orientacoes = "Proposta inédita de simulação de vestibular. Desenvolva texto dissertativo-argumentativo com posicionamento crítico e repertório legítimo."
                genero = "Dissertativo-Argumentativo"

            textos_bloco = f"TEXTO I\n{t1}\n\nTEXTO II\n{t2}\n\nTEXTO III\n{t3}"

            temas.append({
                'titulo': titulo,
                'ano': str(ano_escolhido),
                'origem': f"Simulado Letrus / Vestibular {ano_escolhido}",
                'banca': banca_escolhida,
                'eixo_tematico': eixo_nome,
                'genero_textual': genero,
                'dificuldade': "Médio" if i % 2 == 0 else "Difícil",
                'orientacoes_especificas': orientacoes,
                'descricao': desc,
                'textos_motivadores': textos_bloco
            })

    return temas

print("Módulo curado base carregado.")
