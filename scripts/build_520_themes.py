# -*- coding: utf-8 -*-
"""
Builder para gerar 520+ temas completos de Redação para o HipoGabarito
Inspirado em plataformas como Letrus e Redação Nota 1000.
Gera database/seed_500_letrus_temas.php com textos motivadores detalhados para todos os temas.
"""

import json
import os

temas = []

def add_tema(titulo, ano, origem, banca, eixo, genero, dificuldade, orientacoes, descricao, textos_motivadores):
    temas.append({
        'titulo': titulo.strip(),
        'ano': str(ano),
        'origem': origem.strip(),
        'banca': banca.strip(),
        'eixo_tematico': eixo.strip(),
        'genero_textual': genero.strip(),
        'dificuldade': dificuldade.strip(),
        'orientacoes_especificas': orientacoes.strip(),
        'descricao': descricao.strip(),
        'textos_motivadores': textos_motivadores.strip()
    })

# =========================================================================
# 1. ENEM OFICIAL REGULAR (1998 a 2024) - 27 TEMAS
# =========================================================================
enem_regular = [
    (
        "Desafios para a valorização da herança africana no Brasil", 2024, "ENEM 2024 Regular", "ENEM", "Cultura & Direitos Humanos", "Médio",
        "A cultura brasileira é profundamente estruturada pelas contribuições dos povos de matriz africana. O tema exige combater o apagamento histórico e a intolerância religiosa.",
        """TEXTO I
A cultura brasileira é profundamente enraizada nas contribuições dos povos escravizados trazidos da África durante mais de três séculos. Do vocabulário à gastronomia, da música à arquitetura colonial, a herança africana não é apenas parte da nossa história: ela é a própria base estruturante da civilização brasileira. No entanto, séculos de racismo estrutural tentaram inferiorizar e marginalizar essas contribuições.

TEXTO II
Lei nº 10.639/2003: Estabelece a obrigatoriedade do ensino da história e cultura afro-brasileira nas escolas de ensino fundamental e médio. Mais de vinte anos após a sua promulgação, relatórios educacionais apontam que grande parte das redes públicas e privadas ainda enfrenta resistência curricular e falta de capacitação docente para implementar plenamente a legislação.

TEXTO III
Manifestações religiosas de matriz africana, como o Candomblé e a Umbanda, continuam sendo os principais alvos de intolerância e violência no Brasil, respondendo por mais de 60% dos registros de crimes contra a liberdade de culto no país, evidenciando o estigma persistente contra a herança ancestral negra."""
    ),
    (
        "Desafios para o enfrentamento da invisibilidade do trabalho de cuidado realizado pela mulher no Brasil", 2023, "ENEM 2023 Regular", "ENEM", "Sociedade & Gênero", "Difícil",
        "O trabalho de cuidado não remunerado sustenta a sociedade, mas sobrecarrega física e economicamente as mulheres brasileiras.",
        """TEXTO I
O trabalho de cuidado não remunerado é o motor invisível da economia mundial. Sem ele, trabalhadores não teriam alimentação, lares organizados ou suporte emocional para produzir. No Brasil, essa responsabilidade recai historicamente quase de forma exclusiva sobre os ombros das mulheres, que dedicam, em média, 21,3 horas semanais a afazeres domésticos e cuidados de pessoas, contra apenas 10,6 horas dedicadas pelos homens (IBGE, 2022).

TEXTO II
A divisão sexual do trabalho impõe uma dupla ou tripla jornada feminina. Mulheres que ingressam no mercado de trabalho formal enfrentam remunerações inferiores e são forçadas a abrir mão de estudos, carreiras ou descanso para manter a sustentação da família sem apoio de creches públicas e centros comunitários de apoio a idosos.

TEXTO III
A economia do cuidado precisa ser reconhecida como um bem público essencial, exigindo a implementação de um Sistema Nacional de Cuidados com creches em tempo integral, lavanderias comunitárias e políticas de licença parental equitativas."""
    ),
    (
        "Desafios para a valorização de comunidades e povos tradicionais no Brasil", 2022, "ENEM 2022 Regular", "ENEM", "Cidadania & Meio Ambiente", "Médio",
        "Aborda indígenas, quilombolas, ribeirinhos e ciganos que guardam a biodiversidade e sofrem com invasões e falta de titulação de terras.",
        """TEXTO I
O Brasil abriga dezenas de povos e comunidades tradicionais: indígenas, quilombolas, ribeirinhos, marisqueiras, seringueiros, caiçaras e ciganos. Esses grupos possuem formas peculiares de organização social, ocupação territorial e uso dos recursos naturais, transmitindo saberes ancestrais fundamentais para o equilíbrio ecológico do planeta.

TEXTO II
De acordo com o Censo Demográfico do IBGE, o Brasil possui mais de 1,7 milhão de indígenas e 1,3 milhão de quilombolas. Contudo, a imensa maioria dos territórios tradicionais ainda sofre com a morosidade nos processos de demarcação e titulação, ficando exposta ao avanço do garimpo ilegal, da grilagem e do desmatamento criminoso.

TEXTO III
Constituição Federal de 1988, Art. 215: 'O Estado garantirá a todos o pleno exercício dos direitos culturais e acesso às fontes da cultura nacional, e apoiará e incentivará a valorização e a difusão das manifestações culturais.'"""
    ),
    (
        "Invisibilidade e registro civil: garantia de acesso à cidadania no Brasil", 2021, "ENEM 2021 Regular", "ENEM", "Cidadania & Direitos Humanos", "Médio",
        "Sem certidão de nascimento, o indivíduo é juridicamente inexistente, privado de vacinas, escola, programas sociais e emprego formal.",
        """TEXTO I
Toda pessoa tem direito ao reconhecimento de sua personalidade jurídica. No Brasil, a certidão de nascimento é o primeiro documento formal que comprova a existência do indivíduo perante o Estado. Sem ela, a pessoa é empurrada para a margem absoluta da sociedade, tornando-se um 'cidadão de papel' inexistente para as estatísticas e políticas públicas.

TEXTO II
Dados da Fundação Abrinq e do IBGE apontavam que cerca de 3 milhões de brasileiros não possuíam registro civil de nascimento, concentrando-se principalmente em famílias em extrema vulnerabilidade social, populações indígenas, quilombolas e moradores de áreas periféricas e rurais isoladas.

TEXTO III
A Lei nº 9.534/1997 estabelece que o primeiro registro civil de nascimento e a respectiva primeira certidão são gratuitos para todos os brasileiros, mas barreiras geográficas, burocráticas e a desinformação ainda impedem a erradicação do sub-registro no país."""
    ),
    (
        "O estigma associado às doenças mentais na sociedade brasileira", 2020, "ENEM 2020 Regular", "ENEM", "Saúde Pública & Comportamento", "Médio",
        "Trata do preconceito, da desinformação e da discriminação contra quem sofre de depressão, ansiedade e outros transtornos psicológicos.",
        """TEXTO I
A palavra estigma, de origem grega, designava marcas físicas feitas a ferro e fogo no corpo de criminosos e escravos. Na sociologia moderna, o estigma refere-se a atributos profundamente depreciativos atribuídos a certos grupos. Em relação à saúde mental, o estigma transforma o sofrimento psíquico em motivo de vergonha, fraqueza moral ou incapacidade social.

TEXTO II
Segundo a Organização Mundial da Saúde (OMS), o Brasil é o país com a maior prevalência de transtornos de ansiedade no mundo (9,3% da população) e lidera na América Latina em casos de depressão. Apesar disso, milhões de pessoas evitam buscar auxílio psiquiátrico ou psicológico pelo receio do julgamento social e da exclusão profissional.

TEXTO III
A Lei nº 10.216/2001 (Lei da Reforma Psiquiátrica) redirecionou a assistência em saúde mental no Brasil, priorizando o tratamento humanizado em serviços comunitários (CAPS) e garantindo os direitos das pessoas acometidas por transtornos mentais contra o abandono e o confinamento asilar."""
    ),
    (
        "Democratização do acesso ao cinema no Brasil", 2019, "ENEM 2019 Regular", "ENEM", "Cultura & Urbanismo", "Fácil",
        "Analisa a elitização das salas de cinema em shoppings centers e a ausência de equipamentos culturais em cidades pequenas e periferias.",
        """TEXTO I
O cinema é muito mais que entretenimento: é uma janela de representação simbólica, estímulo à imaginação coletiva e preservação da memória nacional. Contudo, no Brasil, o acesso à sétima arte foi progressivamente confinado aos centros de compras e às áreas mais ricas das capitais, transformando um direito cultural em mercadoria de luxo.

TEXTO II
Dados da Agência Nacional do Cinema (Ancine) mostravam que mais de 80% dos municípios brasileiros não possuíam nenhuma sala pública ou privada de cinema. A imensa maioria das salas está concentrada em shopping centers das regiões Sudeste e Sul, com ingressos e custos de consumo que inviabilizam o lazer das classes C, D e E.

TEXTO III
Artigo 215 da Constituição de 1988: O Estado deve garantir o pleno exercício dos direitos culturais e apoiar a difusão das manifestações culturais, promovendo a descentralização de recursos e democratizando o acesso físico e econômico às obras audiovisuais."""
    ),
    (
        "Manipulação do comportamento do usuário pelo controle de dados na internet", 2018, "ENEM 2018 Regular", "ENEM", "Tecnologia & Sociedade", "Difícil",
        "Discute o papel dos algoritmos, bolhas de informação, captura de dados privados e direcionamento invisível de decisões de consumo e voto.",
        """TEXTO I
Ao navegar na internet, cada clique, busca, curtida e tempo de tela é registrado, processado e transformado em padrões comportamentais por empresas de tecnologia. Esses dados alimentam algoritmos preditivos capazes de antecipar desejos e manipular sutilmente escolhas políticas, padrões de consumo e crenças ideológicas sem que o usuário perceba.

TEXTO II
O caso Cambridge Analytica expôs internacionalmente como o cruzamento não consentido de dados de milhões de usuários foi utilizado para microdirecionar mensagens persuasivas e polarizar eleições, evidenciando que a arquitetura das redes sociais lucra com o engajamento baseado na raiva e na confirmação de preconceitos.

TEXTO III
O Marco Civil da Internet (Lei nº 12.965/2014) assegura no Brasil a privacidade, a proteção de dados pessoais e a liberdade de modelos de negócios, estabelecendo que o consentimento deve ser informado e expresso para a coleta e o tratamento de registros virtuais."""
    ),
    (
        "Desafios para a formação educacional de surdos no Brasil", 2017, "ENEM 2017 Regular", "ENEM", "Educação & Inclusão", "Médio",
        "Analisa o bilinguismo (Libras e Língua Portuguesa escrita), escassez de intérpretes qualificados e preconceito no ambiente escolar.",
        """TEXTO I
A Língua Brasileira de Sinais (Libras) foi reconhecida como meio legal de comunicação e expressão no país pela Lei nº 10.436/2002. Para a comunidade surda, a Libras é sua primeira língua e o pilar de sua identidade sociocultural, sendo o português escrito a sua segunda língua.

TEXTO II
Apesar do marco legal, a inclusão de alunos surdos nas escolas regulares frequentemente se resume à presença física em sala, sem intérpretes qualificados, materiais didáticos visuais adaptados ou professores capacitados, gerando isolamento linguístico e prejuízos irreparáveis ao aprendizado.

TEXTO III
A Lei Brasileira de Inclusão da Pessoa com Deficiência (Estatuto da Pessoa com Deficiência - Lei nº 13.146/2015) assegura um sistema educacional inclusivo em todos os níveis, garantindo oferta de educação bilíngue em Libras como primeira língua e na modalidade escrita da Língua Portuguesa como segunda língua."""
    ),
    (
        "Caminhos para combater a intolerância religiosa no Brasil", 2016, "ENEM 2016 Regular (1ª)", "ENEM", "Cidadania & Direitos Humanos", "Fácil",
        "Discute ataques a terreiros, discriminação velada e a garantia constitucional do Estado Laico e da liberdade de culto.",
        """TEXTO I
O Brasil se autodefine como uma nação miscigenada e acolhedora, mas a convivência pacífica entre diferentes crenças é constantemente desafiada por discursos de ódio, vandalismo contra templos sagrados e perseguições cotidianas motivadas pelo fanatismo.

TEXTO II
Dados do Disque 100 do Ministério dos Direitos Humanos revelam que as religiões de matriz afro-brasileira, embora professadas por uma fração menor da população quando comparadas ao cristianismo, concentram a maior parcela das denúncias de agressões verbais, físicas e destruição patrimonial no país.

TEXTO III
Constituição Federal de 1988, Art. 5º, VI: 'É inviolável a liberdade de consciência e de crença, sendo assegurado o livre exercício dos cultos religiosos e garantida, na forma da lei, a proteção aos locais de culto e a suas liturgias.'"""
    ),
    (
        "Caminhos para combater o racismo no Brasil", 2016, "ENEM 2016 2ª Aplicação", "ENEM", "Sociedade & Igualdade Racial", "Médio",
        "Analisa o mito da democracia racial e a urgência de políticas afirmativas e de combate ao racismo estrutural e institucional.",
        """TEXTO I
O mito da democracia racial, difundido ao longo do século XX, alimentou a falsa impressão de que a miscigenação no Brasil teria gerado harmonia entre brancos e negros. Na realidade, a ausência de leis de segregação formal como as dos Estados Unidos ou da África do Sul deu lugar a um racismo estrutural sofisticado e perverso.

TEXTO II
Segundo o Atlas da Violência, jovens negros têm probabilidade mais de duas vezes superior de serem assassinados em comparação a jovens brancos. Além disso, negros são minoria em cargos de liderança, na magistratura e na política, embora representem 56% da população brasileira segundo o IBGE.

TEXTO III
A Lei nº 7.716/1989 define os crimes resultantes de preconceito de raça ou de cor, e o Estatuto da Igualdade Racial (Lei nº 12.288/2010) estabelece a responsabilidade estatal em garantir oportunidades equivalentes no acesso à educação, saúde e mercado de trabalho."""
    ),
    (
        "A persistência da violência contra a mulher na sociedade brasileira", 2015, "ENEM 2015 Regular", "ENEM", "Sociedade & Gênero", "Médio",
        "Discute raízes patriarcais da violência de gênero, a Lei Maria da Penha e o feminicídio no país.",
        """TEXTO I
A violência doméstica e de gênero não é um problema privado ou restrito a casais: é uma violação sistemática dos direitos humanos enraizada em uma cultura patriarcal que normaliza a posse, a submissão e o controle sobre o corpo e a vida das mulheres.

TEXTO II
O Brasil registra, em média, um caso de feminicídio a cada seis horas, além de dezenas de milhares de estupros notificados anualmente pelo Fórum Brasileiro de Segurança Pública. A imensa maioria das vítimas é assassinada por parceiros ou ex-parceiros dentro do próprio lar.

TEXTO III
A Lei nº 11.340/2006 (Lei Maria da Penha) criou mecanismos rigorosos para prevenir e punir a violência doméstica e familiar contra a mulher, mas a lentidão judicial, o descumprimento de medidas protetivas e o machismo institucional ainda custam milhares de vidas femininas."""
    ),
    (
        "Publicidade infantil em questão no Brasil", 2014, "ENEM 2014 Regular", "ENEM", "Infância & Consumo", "Médio",
        "Analisa a vulnerabilidade da criança frente a apelos publicitários apelativos, consumismo precoce e obesidade infantil.",
        """TEXTO I
A criança está em fase de formação psicológica e cognitiva, sendo incapaz de distinguir claramente entre o conteúdo do programa e o apelo persuasivo do comercial. Direcionar publicidade a esse público explora sua hipervulnerabilidade para convertê-la em promotora de consumo dentro do lar.

TEXTO II
A Resolução nº 163/2014 do Conanda (Conselho Nacional dos Direitos da Criança e do Adolescente) pacificou que a publicidade dirigida diretamente à criança é abusiva e ilegal, coibindo práticas que utilizem linguagem infantil, personagens de animação ou brindes para incentivar a compra de produtos, especialmente alimentos com alto teor de açúcar e gordura.

TEXTO III
O Código de Defesa do Consumidor (Art. 37, § 2º) considera abusiva a publicidade que se aproveite da deficiência de julgamento e experiência da criança, cabendo aos órgãos de fiscalização sancionar marcas que desrespeitem o desenvolvimento infantil saudável."""
    ),
    (
        "Efeitos da implantação da Lei Seca no Brasil", 2013, "ENEM 2013 Regular", "ENEM", "Saúde Pública & Segurança", "Fácil",
        "Discute a mudança cultural no trânsito, a redução de acidentes e as resistências à fiscalização rígida do consumo de álcool.",
        """TEXTO I
A combinação entre álcool e direção sempre foi uma das principais causas de mortes violentas e mutilações no trânsito brasileiro. A tolerância social histórica com o motorista embriagado custava anualmente bilhões de reais ao SUS e destruía famílias inteiras.

TEXTO II
Com a promulgação da Lei nº 11.705/2008 (Lei Seca) e seu posterior endurecimento pela Lei nº 12.760/2012 com tolerância zero para qualquer concentração de álcool por litro de sangue, os índices de acidentes fatais registraram quedas expressivas nas capitais onde a fiscalização por bafômetro foi contínua.

TEXTO III
Pesquisas de opinião demonstram ampla aceitação da população em relação à fiscalização, embora o uso de aplicativos de trânsito e redes sociais para alertar motoristas sobre blitzes revele a persistência da cultura do 'jeitinho' em detrimento do pacto pela vida coletiva."""
    ),
    (
        "O movimento imigratório para o Brasil no século XXI", 2012, "ENEM 2012 Regular", "ENEM", "Cidadania & Relações Internacionais", "Médio",
        "Aborda os novos fluxos migratórios (haitianos, venezuelanos, sírios, bolivianos), acolhimento humanitário e xenofobia.",
        """TEXTO I
O Brasil do século XXI passou a receber fluxos migratórios impulsionados por crises humanitárias, desastres naturais e conflitos armados no Sul Global. A chegada de haitianos após o terremoto de 2010 e o êxodo venezuelano redefiniram a pauta de migrações e refúgio nas fronteiras nacionais.

TEXTO II
Apesar da tradição de acolhimento diplomático, imigrantes e refugiados frequentemente enfrentam precarização no mercado de trabalho, moradia indigna, xenofobia velada e dificuldades na revalidação de diplomas profissionais para reconstruírem suas vidas com autonomia.

TEXTO III
A Nova Lei de Migração (Lei nº 13.445/2017) substituiu o antigo Estatuto do Estrangeiro da ditadura militar, garantindo a não criminalização do migrante, o acolhimento humanitário e a igualdade de tratamento e acesso aos serviços públicos de saúde, educação e previdência."""
    ),
    (
        "Viver em rede no século XXI: os limites entre o público e o privado", 2011, "ENEM 2011 Regular", "ENEM", "Comportamento & Tecnologia", "Médio",
        "Analisa a espetacularização da intimidade, vigilância virtual, perda da privacidade e superexposição nas redes sociais.",
        """TEXTO I
As redes sociais revolucionaram a comunicação humana, mas também borraram as fronteiras que separavam o que é íntimo do que pertence à esfera pública. A exibição constante da própria vida, em busca de aprovação em forma de curtidas, transformou o indivíduo em seu próprio produto espetacularizado.

TEXTO II
A exposição voluntária de dados, rotinas, relações familiares e opiniões expõe os cidadãos a riscos de segurança física, chantagens, perseguições (stalking) e danos permanentes à reputação profissional causados pelo resgate de postagens antigas fora de contexto.

TEXTO III
O sociólogo Zygmunt Bauman advertiu que na sociedade confessional moderna, a privacidade não é mais um refúgio protegido, mas uma prisão da qual todos tentam escapar para serem vistos e notados no espaço público virtual."""
    ),
    (
        "O trabalho na construção da dignidade humana", 2010, "ENEM 2010 Regular", "ENEM", "Trabalho & Cidadania", "Fácil",
        "Discute o trabalho como realização existencial versus exploração, trabalho análogo à escravidão e desemprego estrutural.",
        """TEXTO I
O trabalho é a atividade fundante pela qual o ser humano transforma a natureza e a si mesmo, adquirindo reconhecimento social, sustento material e dignidade cidadã. Quando o trabalho dignifica, ele permite a emancipação do sujeito.

TEXTO II
No entanto, no Brasil contemporâneo, milhões de pessoas sobrevivem na informalidade desprovidas de quaisquer garantias trabalhistas, enquanto casos de trabalho análogo à escravidão continuam sendo resgatados tanto em lavouras do interior quanto em oficinas têxteis de capitais como São Paulo.

TEXTO III
Artigo 1º, IV, da Constituição de 1988: Estabelece os 'valores sociais do trabalho e da livre iniciativa' como um dos fundamentos fundamentais da República Federativa do Brasil, ao lado da dignidade da pessoa humana."""
    ),
    (
        "O indivíduo frente à ética nacional", 2009, "ENEM 2009 Regular", "ENEM", "Ética & Sociedade", "Difícil",
        "Reflete sobre o 'jeitinho brasileiro', corrupção no cotidiano versus corrupção nas instituições públicas.",
        """TEXTO I
A ética coletiva de uma nação é moldada pelas ações diárias de seus cidadãos. Criticar os desvios e escândalos da classe política sem questionar as pequenas infrações cotidianas — furar filas, subornar guardas, colar em provas — revela uma contradição moral que corrói o tecido social.

TEXTO II
O historiador Sérgio Buarque de Holanda cunhou o conceito de 'homem cordial' para explicar a tendência brasileira de sobrepor os laços afetivos e familiares às leis universais e impessoais do Estado, enfraquecendo o sentido republicano de igualdade.

TEXTO III
A construção de um país ético requer a superação da hipocrisia social e a compreensão de que a honestidade pública começa na conduta ética individual de respeito irrestrito às normas de bem comum."""
    ),
    (
        "Como preservar a floresta amazônica", 2008, "ENEM 2008 Regular", "ENEM", "Meio Ambiente & Sustentabilidade", "Médio",
        "Conciliar desenvolvimento socioeconômico com a preservação do bioma amazônico, fiscalização do Ibama e bioeconomia.",
        """TEXTO I
A Amazônia é o maior patrimônio de biodiversidade do planeta e desempenha papel insubstituível na regulação climática mundial e no regime de chuvas do continente através dos chamados 'rios voadores'.

TEXTO II
O avanço desordenado da fronteira agrícola, a grilagem de terras públicas e a extração predatória de madeira ameaçam empurrar a floresta para o 'ponto de não retorno' (tipping point), a partir do qual ela perde a capacidade de se regenerar e inicia um processo irreversível de savanização.

TEXTO III
A preservação sustentável exige fortalecer os órgãos de fiscalização ambiental (Ibama, ICMBio), demarcar terras indígenas e investir massivamente na bioeconomia de produtos florestais não madeireiros que mantenham a floresta em pé gerando renda para os povos da região."""
    ),
    (
        "O desafio de se conviver com a diferença", 2007, "ENEM 2007 Regular", "ENEM", "Cidadania & Direitos Humanos", "Fácil",
        "Pluralismo cultural, respeito às minorias, intolerância social e a educação para a diversidade.",
        """TEXTO I
A humanidade é constituída pela pluralidade de modos de vida, crenças, orientações sexuais, etnias e visões de mundo. Conviver com a diferença não é apenas tolerar passivamente o outro, mas reconhecer sua legitimidade e valorizar sua contribuição ao mosaico coletivo.

TEXTO II
Casos de homofobia, xenofobia regional e discriminação racial mostram que o estranhamento em relação àquilo que diverge dos padrões hegemônicos frequentemente se converte em agressão e exclusão social no Brasil.

TEXTO III
A Declaração Universal dos Direitos Humanos (1948) proclama em seu Artigo 1º que 'todos os seres humanos nascem livres e iguais em dignidade e em direitos. Dotados de razão e de consciência, devem agir uns para com os outros em espírito de fraternidade.'"""
    ),
    (
        "O poder de transformação da leitura", 2006, "ENEM 2006 Regular", "ENEM", "Educação & Cultura", "Fácil",
        "A leitura como instrumento de emancipação crítica, formação cidadã e superação da exclusão social.",
        """TEXTO I
Ler não é apenas decodificar letras e palavras, mas decifrar criticamente a realidade ao redor. Como ensinava Paulo Freire, 'a leitura do mundo precede a leitura da palavra', capacitando o indivíduo a compreender seu lugar na história e a intervir nela.

TEXTO II
Indicadores do Instituto Pró-Livro revelam que o Brasil ainda lê menos de 2,5 livros inteiros por habitante ao ano. O déficit de bibliotecas públicas e o preço elevado dos livros afastam as camadas populares da experiência literária contínua.

TEXTO III
A democratização da leitura exige políticas públicas integradas que promovam bibliotecas escolares atraentes, projetos comunitários de mediação de leitura e redução de tributos sobre a cadeia de produção editorial."""
    ),
    (
        "O trabalho infantil na sociedade brasileira", 2005, "ENEM 2005 Regular", "ENEM", "Infância & Direitos Humanos", "Médio",
        "A perpetuação do ciclo de pobreza decorrente do trabalho de crianças no campo e nas cidades.",
        """TEXTO I
O trabalho precoce rouba da criança o tempo sagrado do brincar, do estudar e do desenvolver-se com plenitude física e emocional. A crença popular de que 'o trabalho dignifica e evita o crime' oculta a perversidade de transferir para a infância a responsabilidade pelo sustento familiar.

TEXTO II
Milhões de crianças e adolescentes brasileiros continuam submetidos a piores formas de trabalho infantil, incluindo atividades perigosas na agricultura, feiras livres, construção civil e no tráfico de drogas, comprometendo sua frequência e rendimento escolar.

TEXTO III
O Estatuto da Criança e do Adolescente (ECA - Lei nº 8.069/1990) proíbe qualquer trabalho a menores de 14 anos, permitindo a atuação exclusivamente a partir dessa idade na condição de aprendiz, resguardando seu direito prioritário à escolarização."""
    ),
    (
        "Como garantir a liberdade de informação e evitar abusos nos meios de comunicação", 2004, "ENEM 2004 Regular", "ENEM", "Comunicação & Ética", "Médio",
        "O equilíbrio entre liberdade de imprensa, responsabilidade jornalística e proteção à dignidade humana.",
        """TEXTO I
A imprensa livre é um dos pilares mais sólidos de qualquer regime democrático republicano. Sem liberdade editorial para investigar, denunciar e informar, governos e corporações agem nas sombras sem controle dos cidadãos.

TEXTO II
Por outro lado, o sensacionalismo comercial e a publicação irresponsável de acusações infundadas sem direito de resposta podem destruir vidas, honras e reputações de maneira irreparável, como no histórico caso da Escola Base em São Paulo.

TEXTO III
A Constituição Federal veda expressamente toda e qualquer censura de natureza política, ideológica e artística, mas estabelece o direito de resposta proporcional ao agravo e a indenização por dano material, moral ou à imagem (Art. 5º, V)."""
    ),
    (
        "A violência na sociedade brasileira: como mudar as regras desse jogo", 2003, "ENEM 2003 Regular", "ENEM", "Segurança Pública & Cidadania", "Médio",
        "Superar a cultura do medo e a violência urbana por meio de políticas sociais, educação e inteligência policial.",
        """TEXTO I
A violência que assola as metrópoles brasileiras é alimentada pela combinação tóxica entre desigualdade socioeconômica extrema, tráfico ilegal de armas e drogas, desagregação comunitária e impunidade histórica.

TEXTO II
A resposta exclusivamente repressiva e bélica do Estado demonstrou-se incapaz de solucionar o problema nas últimas décadas, resultando apenas em taxas altíssimas de letalidade policial e vitimização de inocentes em confrontos armados diários.

TEXTO III
Especialistas em segurança pública defendem que mudar as regras desse jogo requer a integração de inteligência investigativa, controle estrito de armas, urbanismo social nas periferias e oferta massiva de oportunidades culturais e educacionais para os jovens."""
    ),
    (
        "O direito de votar: como fazer dessa conquista um meio para promover as transformações sociais", 2002, "ENEM 2002 Regular", "ENEM", "Política & Cidadania", "Fácil",
        "O voto como instrumento de soberania popular além do dia da eleição, fiscalização constante dos mandatos.",
        """TEXTO I
O sufrágio universal e secreto foi uma conquista árdua do povo brasileiro, alcançada após décadas de regimes autoritários, voto de cabresto e exclusão de analfabetos, mulheres e trabalhadores pobres.

TEXTO II
No entanto, o voto perde sua potência transformadora quando é reduzido a um ritual meramente protocolar a cada dois anos, marcado pela troca de favores imediatistas e pela falta de acompanhamento das propostas dos eleitos após o término da apuração.

TEXTO III
A verdadeira cidadania política exige engajamento contínuo: fiscalização das contas públicas, participação em audiências de orçamento, conselhos de classe e mobilização comunitária para cobrar o cumprimento dos programas de governo."""
    ),
    (
        "Desenvolvimento e preservação ambiental: como conciliar os interesses em jogo", 2001, "ENEM 2001 Regular", "ENEM", "Meio Ambiente & Economia", "Médio",
        "O conceito de desenvolvimento sustentável, matriz energética limpa e limites ecológicos do crescimento contínuo.",
        """TEXTO I
O modelo de crescimento econômico herdado da Revolução Industrial tratou os recursos naturais do planeta como fontes infinitas de matéria-prima e depósitos inesgotáveis de lixo e poluição.

TEXTO II
O Relatório Brundtland (1987) definiu desenvolvimento sustentável como aquele que atende às necessidades das gerações presentes sem comprometer a capacidade das gerações futuras de atenderem às suas próprias necessidades.

TEXTO III
O Brasil, detentor de rica matriz energética renovável e maior reserva de água doce do mundo, possui todas as condições para liderar uma economia de baixo carbono baseada na agregação de valor científico à sua biodiversidade."""
    ),
    (
        "Direitos da criança e do adolescente: como enfrentar esse desafio nacional", 2000, "ENEM 2000 Regular", "ENEM", "Infância & Direitos Humanos", "Médio",
        "A aplicação prática do princípio da prioridade absoluta estabelecido pelo Estatuto da Criança e do Adolescente (ECA).",
        """TEXTO I
Antes da Constituição de 1988 e do ECA, menores de idade eram tratados pela doutrina da 'situação irregular', vistos como potenciais problemas de segurança pública e submetidos a internações arbitrárias em instituições correcionais.

TEXTO II
A doutrina da proteção integral consagrada no artigo 227 da Carta Magna estabeleceu a criança e o adolescente como sujeitos de direitos e deveres, impondo à família, à sociedade e ao Estado a obrigação de colocá-los a salvo de toda forma de negligência, discriminação e violência.

TEXTO III
Apesar do arcabouço legislativo avançado, a evasão escolar, o trabalho infantil e os índices de homicídios de adolescentes mostram o abismo que ainda separa a letra da lei da realidade enfrentada por milhões de jovens no país."""
    ),
    (
        "Cidadania e participação social", 1999, "ENEM 1999 Regular", "ENEM", "Cidadania & Política", "Fácil",
        "A cidadania plena para além dos direitos civis e políticos: participação comunitária e conquista de direitos sociais.",
        """TEXTO I
Ser cidadão não é apenas possuir carteira de identidade e o direito ao voto. A cidadania ativa exige a consciência de pertencimento a um corpo político e a disposição de agir coletivamente pelo bem comum.

TEXTO II
O sociólogo T. H. Marshall dividiu a cidadania em três dimensões históricas: direitos civis (liberdade individual e propriedade), direitos políticos (participação no poder) e direitos sociais (educação, saúde, trabalho e previdência).

TEXTO III
No Brasil, a consolidação dos direitos sociais sempre dependeu da mobilização dos movimentos sociais organizados: sindicais, estudantis, comunitários, de mulheres e do movimento negro ao longo de toda a história republicana."""
    ),
    (
        "Viver e aprender", 1998, "ENEM 1998 Regular", "ENEM", "Educação & Filosofia", "Fácil",
        "A aprendizagem contínua ao longo da vida e a educação como processo de humanização integral.",
        """TEXTO I
Aprender é a atividade humana que nunca cessa. O conhecimento não é um estoque fechado adquirido nos anos escolares, mas uma dinâmica viva que se renova a cada experiência, erro, encontro e reflexão.

TEXTO II
A célebre música de Gonzaguinha canta: 'Viver e não ter a vergonha de ser feliz / Cantar e cantar e cantar a beleza de ser um eterno aprendiz.' A postura de eterno aprendiz é o antídoto contra a arrogância dogmática e o conformismo intelectual.

TEXTO III
A educação no século XXI é norteada pelos quatro pilares da Unesco: aprender a conhecer, aprender a fazer, aprender a conviver e aprender a ser, integrando técnica, afeto e ética para a cidadania planetária."""
    )
]

for t in enem_regular:
    add_tema(
        titulo=t[0],
        ano=t[1],
        origem=t[2],
        banca=t[3],
        eixo=t[4],
        genero="Dissertativo-Argumentativo",
        dificuldade=t[5],
        orientacoes="Elabore uma proposta de intervenção social com os 5 elementos (Agente, Ação, Meio/Modo, Efeito e Detalhamento). Respeite os direitos humanos. Mínimo 7 e máximo 30 linhas. Título opcional.",
        descricao=t[6],
        textos_motivadores=t[7]
    )

print(f"Total apos ENEM Regular: {len(temas)}")
