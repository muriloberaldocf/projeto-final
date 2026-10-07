import os

texts = {}

texts[61] = """📚 **Citologia: Organelas e Funções**

**O que é?**
A citologia é o ramo da Biologia que estuda as células, suas estruturas, organelas e funções. A célula é a unidade básica, estrutural e funcional de todos os seres vivos. Células eucarióticas (com núcleo definido e organelas membranosas) são o foco principal desta área no Ensino Médio, mas é crucial lembrar que bactérias são procariontes e não possuem essas organelas complexas, exceto ribossomos.

**Conceitos Fundamentais e Organelas:**
• **Núcleo:** O "cérebro" da célula, abriga o material genético (DNA). É delimitado pela carioteca e controla as atividades celulares.
• **Ribossomos:** Estruturas não membranosas (presentes em procariontes e eucariontes) responsáveis pela síntese de proteínas.
• **Retículo Endoplasmático Rugoso (RER):** Possui ribossomos aderidos à sua membrana. Sintetiza proteínas que serão exportadas pela célula ou enviadas para outras organelas.
• **Retículo Endoplasmático Liso (REL):** Sem ribossomos, é responsável pela síntese de lipídios (como hormônios esteroides) e desintoxicação celular (muito desenvolvido em células do fígado).
• **Complexo de Golgi:** O "correio" celular. Modifica, armazena, empacota e secreta substâncias produzidas no RER e REL. Também forma o acrossomo do espermatozoide.
• **Lisossomos:** Bolsas com enzimas digestivas que realizam a digestão intracelular (heterofagia e autofagia). Atuam na apoptose e na destruição de organelas velhas.
• **Mitocôndrias:** A "usina de energia". Responsáveis pela respiração celular aeróbica e produção de ATP. Possuem DNA próprio e capacidade de autoduplicação (teoria endossimbiótica).
• **Cloroplastos:** Presentes em plantas e algas. Responsáveis pela fotossíntese (produção de glicose). Também têm DNA próprio.
• **Peroxissomos:** Quebram a água oxigenada (H2O2) tóxica através da enzima catalase e oxidam ácidos graxos.
• **Centríolos:** Participam da divisão celular (formação das fibras do fuso) e na formação de cílios e flagelos.

**Exemplo Prático:**
Uma célula do pâncreas tem a função de produzir e secretar muita insulina (uma proteína). Assim, essa célula terá o Retículo Endoplasmático Rugoso e o Complexo de Golgi extremamente desenvolvidos, já que precisa produzir (RER) e exportar (Golgi) grandes quantidades dessa substância.

⚠️ **Pegadinhas Comuns no Vestibular:**
• Confundir as funções do REL e do RER. O liso faz lipídio e desintoxica; o rugoso faz proteína para exportação.
• Achar que toda célula tem todas as organelas na mesma proporção. A proporção depende da função da célula.
• Esquecer que mitocôndrias têm herança exclusivamente materna (o espermatozoide não passa suas mitocôndrias para o embrião).

💡 **Dica de Ouro:** Lembre-se da "Teoria Endossimbiótica"! Mitocôndrias e cloroplastos já foram bactérias de vida livre que foram englobadas por uma célula maior. É por isso que eles têm DNA próprio, circular e ribossomos parecidos com os de bactérias (70S)."""

texts[62] = """📚 **Membrana Plasmática e Transporte Celular**

**O que é?**
A membrana plasmática é o envoltório celular que separa o meio intracelular do extracelular. Ela é responsável por manter a integridade da célula e, principalmente, por selecionar o que entra e o que sai (permeabilidade seletiva). É essencial para manter a homeostase (equilíbrio) celular.

**Conceitos Fundamentais:**
• **Modelo do Mosaico Fluido:** Proposto por Singer e Nicolson. A membrana é formada por uma bicamada de fosfolipídios com proteínas inseridas nela. Os fosfolipídios têm uma "cabeça" hidrofílica (afinidade por água) voltada para o exterior e uma "cauda" hidrofóbica voltada para o interior. A fluidez permite movimento das moléculas.
• **Glicocálix:** Camada de carboidratos ligada aos lipídios ou proteínas na face externa. Atua no reconhecimento celular e proteção (como uma "impressão digital" da célula).
• **Transporte Passivo (Sem gasto de ATP):** Ocorre a favor do gradiente de concentração (do mais concentrado para o menos).
  - *Difusão Simples:* Passagem de solutos pequenos e apolares (O2, CO2) diretamente pela bicamada lipídica.
  - *Difusão Facilitada:* Passagem de solutos (glicose, aminoácidos) através de proteínas "carreadoras" (permeases).
  - *Osmose:* É um caso especial! É a passagem do SOLVENTE (água), que vai do meio hipotônico (menos concentrado) para o meio hipertônico (mais concentrado), buscando igualar as concentrações.
• **Transporte Ativo (Com gasto de ATP):** Ocorre contra o gradiente de concentração.
  - *Bomba de Sódio e Potássio (Na+/K+):* Joga 3 Na+ para fora e 2 K+ para dentro, mantendo a diferença de cargas (essencial para o impulso nervoso).
• **Transporte em Quantidade (Vesicular):**
  - *Endocitose:* Entrada de partículas. (Fagocitose = sólidos; Pinocitose = líquidos).
  - *Exocitose:* Saída de substâncias (secreção ou excreção).

**Exemplo Prático:**
Ao temperar uma salada de alface com sal, a água dentro das células vegetais (meio hipotônico) sai em direção ao meio externo (hipertônico, por causa do sal) através de osmose. Como resultado, a alface murcha (plasmólise).

⚠️ **Pegadinhas Comuns no Vestibular:**
• Na osmose, quem se move é a ÁGUA, não o sal! A água sempre vai atrás do soluto para tentar diluí-lo.
• Célula animal em meio muito hipotônico sofre lise (estoura). Célula vegetal NÃO estoura por causa da parede celular rígida de celulose.
• A bomba de sódio e potássio joga SÓDIO PARA FORA e POTÁSSIO PARA DENTRO. Lembre-se: "Na+ rua" (sódio na rua, ou seja, fora).

💡 **Dica de Ouro:** A osmose cai muito atrelada ao estudo dos peixes. Peixes ósseos marinhos vivem em ambiente hipertônico e perdem muita água por osmose. Para não morrerem desidratados, eles bebem muita água do mar e eliminam o excesso de sal pelas brânquias, produzindo pouca urina e muito concentrada."""

texts[63] = """📚 **Respiração Celular e Fermentação**

**O que é?**
São processos de obtenção de energia (na forma de ATP) pelas células. A respiração celular é um processo aeróbio (exige oxigênio) que extrai o máximo de energia da molécula de glicose. A fermentação é um processo anaeróbio (sem oxigênio) que ocorre no citoplasma e gera muito menos energia, mas é rápido e vital para alguns organismos (ou para o músculo em emergência).

**Conceitos Fundamentais da Respiração Celular:**
A equação geral: C6H12O6 + 6 O2 -> 6 CO2 + 6 H2O + ATP (cerca de 30-32 ATPs).
Divide-se em 3 etapas principais:
1. **Glicólise (no citoplasma/hialoplasma):** Única etapa fora da mitocôndria e sem uso de O2. Uma molécula de glicose (6 carbonos) é quebrada em duas moléculas de ácido pirúvico/piruvato (3 carbonos). Saldo de 2 ATP e 2 NADH.
2. **Ciclo de Krebs (na matriz mitocondrial):** O piruvato é oxidado a Acetil-CoA e entra no ciclo. Ocorre a liberação total do carbono na forma de CO2. Produz muito NADH e FADH2 (carregadores de elétrons) e 2 ATP.
3. **Cadeia Respiratória / Fosforilação Oxidativa (nas cristas mitocondriais):** É onde a mágica acontece. Os elétrons trazidos pelo NADH e FADH2 saltam por proteínas (citocromos), liberando energia para bombear prótons (H+). O retorno dos prótons gira a ATP-sintase, gerando a maior parte do ATP (cerca de 26-28 ATP). O oxigênio atua como aceptor final de elétrons, formando água ao se ligar ao H+.

**Conceitos Fundamentais da Fermentação:**
Processo que ocorre apenas no citoplasma, rendendo apenas 2 ATPs por glicose (da glicólise). O piruvato é convertido em outro produto para reciclar o NAD+ necessário para manter a glicólise funcionando.
• **Fermentação Lática:** Realizada por bactérias (Lactobacillus) e pelas nossas fibras musculares brancas em exercício intenso (quando falta O2). Produz ácido lático (causa fadiga muscular).
• **Fermentação Alcoólica:** Realizada por leveduras (Saccharomyces cerevisiae, o fermento biológico). Produz etanol e CO2. Usada na produção de cerveja, vinho e pão (o CO2 faz a massa crescer).

**Exemplo Prático:**
Quando você corre uma maratona e o oxigênio não chega rápido o suficiente no músculo, suas células começam a realizar fermentação lática para não ficar sem energia, causando a famosa dor muscular (embora a dor também seja microlesões).

⚠️ **Pegadinhas Comuns no Vestibular:**
• Achar que glicólise ocorre na mitocôndria. NÃO! Ocorre no citoplasma.
• Esquecer o papel do oxigênio: ele é o "aceptor final de elétrons". Se faltar O2, a cadeia para, o ciclo de Krebs para, e a célula morre ou faz fermentação.
• Dizer que o CO2 que expiramos vem do O2 que inspiramos. FALSO! O oxigênio inspirado vira H2O. O CO2 expirado vem da quebra da glicose (carbono dos alimentos) no ciclo de Krebs.

💡 **Dica de Ouro:** O fermento de pão é biológico (fungos que fazem fermentação alcoólica e liberam CO2), enquanto o fermento de bolo é químico (bicarbonato que libera CO2 ao ser aquecido). É por isso que a massa do pão precisa "descansar" antes de assar, mas a do bolo não!"""

texts[64] = """📚 **Fotossíntese e Quimiossíntese**

**O que é?**
Processos autotróficos nos quais os organismos produzem seu próprio alimento (matéria orgânica). A fotossíntese utiliza a luz solar como fonte de energia e é realizada por plantas, algas e cianobactérias. A quimiossíntese utiliza a energia proveniente de reações químicas inorgânicas (oxidação de compostos) e é exclusiva de algumas bactérias (ex: bactérias nitrificantes do ciclo do nitrogênio e bactérias de fontes hidrotermais no fundo do mar).

**Conceitos Fundamentais da Fotossíntese:**
Equação geral: 6 CO2 + 12 H2O + Luz -> C6H12O6 + 6 O2 + 6 H2O
Ocorre dentro dos cloroplastos nas células vegetais e é dividida em duas grandes fases:
1. **Fase Clara (Fotoquímica):** Ocorre nos tilacoides (as "moedinhas" empilhadas no cloroplasto). Depende DIRETAMENTE da luz. A luz excita a clorofila, gerando a produção de ATP (fotofosforilação) e NADH (para levar hidrogênio). Ocorre também a fotólise da água (quebra da H2O pela luz), que é a origem do O2 que a planta libera!
2. **Fase Escura (Química / Ciclo de Calvin):** Ocorre no estroma (o fluido do cloroplasto). Não precisa de luz diretamente, mas precisa dos produtos da fase clara (ATP e NADPH). Nela, a planta fixa o CO2 atmosférico usando a enzima Rubisco para construir a molécula de glicose.

**Conceitos Fundamentais da Quimiossíntese:**
Sem luz, a energia vem da oxidação de compostos como amônia, ferro ou enxofre.
Exemplo no Ciclo do Nitrogênio: as bactérias Nitrosomonas convertem Amônia em Nitrito (liberando energia) e usam essa energia para fazer sua matéria orgânica.

**Fórmulas Essenciais:**
Para a fotossíntese, a percepção de que a água fornece o oxigênio liberado pode ser vista pela marcação de isótopos: 6 CO2 + 12 H2O* -> C6H12O6 + 6 O2* + 6 H2O. Se a água tiver oxigênio radioativo (O*), o gás liberado será radioativo.

**Exemplo Prático:**
Ao colocar uma planta no escuro (ou seja, sem fase clara), ela logo deixará de realizar a fase escura, mesmo havendo CO2 disponível, pois faltarão o ATP e o NADPH produzidos na luz.

⚠️ **Pegadinhas Comuns no Vestibular:**
• A "Fase Escura" não ocorre só à noite! Ela ocorre de dia junto com a fase clara, pois precisa do ATP produzido nela. Só recebeu esse nome por não usar luz diretamente.
• O oxigênio liberado pela fotossíntese vem da ÁGUA (fotólise), não do CO2.
• Cuidado com o Ponto de Compensação Fótico: é a intensidade luminosa na qual a taxa de fotossíntese (produção) iguala a de respiração (consumo). Abaixo do ponto, a planta morre pois consome mais do que produz.

💡 **Dica de Ouro:** A cor verde das plantas se deve ao pigmento clorofila, que REFLETE a luz verde. Por isso, a luz verde é a menos eficiente para a fotossíntese! A planta absorve melhor as cores vermelho e azul para fazer fotossíntese (famoso gráfico de espectro de absorção)."""

texts[65] = """📚 **Divisão Celular: Mitose e Meiose**

**O que é?**
A divisão celular é como as células se multiplicam. A Mitose (divisão equacional, E!) gera duas células-filhas geneticamente idênticas à mãe, mantendo o número de cromossomos. A Meiose (divisão reducional, R!) gera quatro células-filhas com a metade dos cromossomos, essencial para a formação de gametas e variabilidade genética.

**Conceitos Fundamentais do Ciclo Celular:**
Antes da divisão, ocorre a Interfase, o período de maior atividade metabólica (G1, S, G2). É na fase S que ocorre a duplicação do DNA. A divisão em si vem logo após.

**Mitose (PRO-META-ANA-TELO):**
• **Prófase:** Os cromossomos começam a se condensar, a carioteca desaparece e o fuso acromático se forma.
• **Metáfase:** Os cromossomos atingem a máxima condensação (melhor fase para ver cariótipo) e se alinham no equador da célula (placa equatorial).
• **Anáfase:** As fibras do fuso encurtam e ocorre a separação das CROMÁTIDES IRMÃS, que migram para os polos.
• **Telófase:** Os cromossomos descondensam, a carioteca reaparece, e ocorre a citocinese (divisão do citoplasma).

**Meiose (Meiose I e Meiose II):**
• **Meiose I (Reducional):** Ocorre a separação dos CROMOSSOMOS HOMÓLOGOS. 
  - *Prófase I:* Extremamente importante! Ocorre o Crossing-Over (Permutação) na subfase Paquíteno, que é a troca de pedaços entre cromossomos homólogos, garantindo a variabilidade genética.
  - *Anáfase I:* Os cromossomos homólogos se separam. Aqui a célula já passa de diploide (2n) para haploide (n).
• **Meiose II (Equacional):** Muito parecida com a mitose, mas em células haploides. Ocorre a separação das cromátides irmãs (Anáfase II).

**Exemplo Prático:**
Quando você corta a pele, as células da borda da ferida realizam mitose intensamente para regenerar o tecido (células novas iguais às velhas). Já nos testículos e ovários, a meiose está a todo vapor produzindo espermatozoides e óvulos (células haploides).

⚠️ **Pegadinhas Comuns no Vestibular:**
• Confundir o que se separa em cada Anáfase. MITOSE e MEIOSE II = separam cromátides irmãs. MEIOSE I = separa cromossomos homólogos.
• Síndrome de Down (Trissomia do 21) ocorre por um erro na não-disjunção dos cromossomos durante a Anáfase I ou II da meiose na formação do gameta.
• Na interfase, cromossomos estão descondensados (cromatina). Só é possível ver o formato de "X" clássico na divisão (Metáfase).

💡 **Dica de Ouro:** Guarde o macete das fases: "PROmeti a META a ANA TELefonar". O Crossing-over e a segregação independente dos homólogos são os grandes responsáveis por você não ser idêntico ao seu irmão, mesmo vindo dos mesmos pais!"""

texts[66] = """📚 **Ecologia: Cadeias e Teias Alimentares**

**O que é?**
A ecologia estuda as relações dos seres vivos entre si e com o ambiente. As cadeias e teias alimentares representam o fluxo de energia e de matéria nos ecossistemas, mostrando "quem come quem". A energia flui de forma unidirecional e decrescente, enquanto a matéria realiza ciclos (é reaproveitada).

**Conceitos Fundamentais:**
• **Produtores (1º Nível Trófico):** Autótrofos (plantas, algas, cianobactérias). Produzem a própria matéria orgânica através de fotossíntese ou quimiossíntese. São a base de toda cadeia alimentar.
• **Consumidores:** Heterótrofos que se alimentam de outros seres vivos.
  - *Consumidor Primário (C1):* Herbívoros, comem os produtores (2º nível trófico).
  - *Consumidor Secundário (C2):* Carnívoros que comem herbívoros (3º nível trófico).
  - *Consumidor Terciário (C3):* Carnívoros de topo.
• **Decompositores:** Fungos e bactérias. Transformam a matéria orgânica de cadáveres e fezes em matéria inorgânica, devolvendo nutrientes ao solo para as plantas. Eles fecham o ciclo da matéria! Atuam em todos os níveis tróficos simultaneamente.
• **Teia Alimentar:** Conjunto de várias cadeias alimentares interligadas. Um animal pode ocupar vários níveis tróficos ao mesmo tempo dependendo do que ele come (ex: um humano que come salada é C1, se come bife é C2).

**Fluxo de Energia e Pirâmides Ecológicas:**
• A energia flui unidirecionalmente, diminuindo a cada nível trófico (cerca de 10% passa, 90% é perdido como calor ou usado no metabolismo). Por isso as cadeias são curtas.
• **Pirâmide de Número:** Quantidade de indivíduos. Pode ser invertida (ex: 1 árvore enorme sustenta 1000 pulgões).
• **Pirâmide de Biomassa:** Peso seco. No ambiente terrestre, é "normal" (base larga). Mas ATENÇÃO: no ambiente aquático, a pirâmide de biomassa pode ser invertida (o peso do fitoplâncton pode ser menor que o do zooplâncton num dado momento, pois o fitoplâncton se reproduz e é comido muito rápido).
• **Pirâmide de Energia:** NUNCA é invertida! A energia sempre é maior nos produtores e menor no topo.

**Exemplo Prático:**
Grama (Produtor) -> Gafanhoto (C1) -> Sapo (C2) -> Cobra (C3) -> Gavião (C4). Se houver caça de cobras, a população de sapos vai explodir (sem predador), dizimando os gafanhotos e prejudicando a grama.

⚠️ **Pegadinhas Comuns no Vestibular:**
• Confundir Nível Trófico (NT) com a ordem do Consumidor. O Produtor é o 1º NT. Logo, o Consumidor Primário é o 2º NT!
• Magnificação Trófica (Bioacumulação): Metais pesados (mercúrio) e agrotóxicos (DDT) não são digeridos. Eles se ACUMULAM. Quanto mais alto o nível trófico, maior a concentração de toxinas! O predador de topo é sempre o mais contaminado.
• Achar que energia é reciclada. Matéria é ciclada, energia é perdida na forma de calor!

💡 **Dica de Ouro:** Em questões com teias complexas, se pedirem para descobrir o nível trófico de um animal, conte as setas desde a planta até o animal para encontrar a posição. E lembre-se: a ponta da seta indica PARA ONDE vai a energia (ou seja, aponta para quem come)."""

texts[67] = """📚 **Relações Ecológicas Harmoniosas e Desarmoniosas**

**O que é?**
As interações entre os seres vivos num ecossistema. Podem ocorrer entre indivíduos da mesma espécie (Intraespecíficas) ou de espécies diferentes (Interespecíficas). São classificadas em harmônicas (quando ninguém sai prejudicado e pelo menos um se beneficia) e desarmônicas (quando pelo menos um sai no prejuízo).

**Conceitos Fundamentais - Harmônicas Intraespecíficas (Mesma Espécie):**
• **Colônia (+/+):** Indivíduos fisicamente unidos. Pode haver divisão de trabalho (ex: caravela-portuguesa, que é um cnidário, e os corais).
• **Sociedade (+/+):** Indivíduos independentes fisicamente, mas organizados com divisão de trabalho clara e castas (ex: abelhas, formigas, cupins).

**Conceitos Fundamentais - Harmônicas Interespecíficas (Espécies Diferentes):**
• **Mutualismo (+/+):** Ambos se beneficiam e a relação é OBRIGATÓRIA para a sobrevivência (ex: liquens = fungos + algas; cupins e protozoários digestores de celulose).
• **Protocooperação (+/+):** Ambos se beneficiam, mas podem viver separados; não é obrigatório (ex: boi e anu, pássaro-palito e crocodilo).
• **Comensalismo (+/0):** Um se beneficia e o outro não ganha nem perde. Muito focado na busca por ALIMENTO (ex: rêmora e tubarão).
• **Inquilinismo (+/0):** Um usa o outro como MORADIA, sem causar prejuízo. Epifitismo é um tipo de inquilinismo entre plantas (ex: orquídeas ou bromélias sobre o tronco de grandes árvores, buscando luz, sem sugar a seiva).

**Conceitos Fundamentais - Desarmônicas Intraespecíficas:**
• **Competição Intraespecífica (-/-):** A mais intensa! Indivíduos competem por comida, território ou fêmea. Ambos perdem energia no processo.
• **Canibalismo (+/-):** Um devora o outro da mesma espécie (ex: viúva-negra, louva-a-deus após o acasalamento).

**Conceitos Fundamentais - Desarmônicas Interespecíficas:**
• **Predação (+/-):** Um caça e mata o outro para se alimentar. Controla o tamanho das populações.
• **Parasitismo (+/-):** Um vive às custas do hospedeiro, roubando nutrientes. Geralmente não mata de imediato (ex: carrapatos - ectoparasita; lombrigas - endoparasita; erva-de-passarinho - hemiparasita vegetal; cipó-chumbo - holoparasita vegetal).
• **Amensalismo ou Antibiose (+/- ou 0/-):** Uma espécie libera substâncias químicas que inibem o crescimento da outra (ex: fungo *Penicillium* que produz penicilina e mata bactérias; maré vermelha).
• **Competição Interespecífica (-/-):** Ocorre quando duas espécies disputam os mesmos recursos (têm nichos ecológicos sobrepostos). Causa exclusão competitiva (uma pode ser extinta localmente).

**Exemplo Prático:**
As orquídeas que vivem nas árvores não são parasitas! Elas são epífitas (inquilinismo). Elas fazem fotossíntese sozinhas e não roubam seiva da árvore, só querem ficar mais no alto para pegar mais luz solar na floresta densa.

⚠️ **Pegadinhas Comuns no Vestibular:**
• Confundir mutualismo com protocooperação. Lembre do líquen: se separar o fungo da alga, eles morrem (mutualismo). O passarinho e o boi vivem muito bem separados (protocooperação).
• A Competição SEMPRE é (-/-), porque mesmo o "vencedor" gastou muita energia e correu risco, ou seja, estaria melhor se não houvesse o competidor lá.

💡 **Dica de Ouro:** Princípio de Gause (Exclusão Competitiva): Duas espécies com Nichos Ecológicos idênticos não podem coexistir no mesmo local por muito tempo. Uma vai acabar expulsando a outra. A saída é evoluir para ter dietas ou horários de caça diferentes (especialização do nicho)."""

texts[68] = """📚 **Ciclos Biogeoquímicos (Água, Carbono, Nitrogênio)**

**O que é?**
É o movimento dos elementos químicos essenciais entre a matéria viva (biota) e o ambiente físico (hidrosfera, litosfera, atmosfera). A matéria no planeta Terra é um sistema fechado: os átomos que compõem o seu corpo agora já fizeram parte de dinossauros, plantas antigas ou nuvens!

**Conceitos Fundamentais:**

**1. Ciclo da Água:**
Movido pela energia solar. Envolve processos de evaporação (rios, lagos e oceanos) e transpiração (plantas e animais). A soma dos dois é a *evapotranspiração*. O vapor condensa nas nuvens e retorna pela precipitação (chuva). É essencial para a manutenção do clima e agricultura.

**2. Ciclo do Carbono:**
Base da vida orgânica. O ciclo perfeito: fotossíntese retira o CO2 da atmosfera para produzir matéria orgânica (fixação), enquanto respiração celular e decomposição devolvem o CO2.
O problema: Os combustíveis fósseis (petróleo, carvão) são estoques de carbono de milhões de anos. Ao queimá-los rapidamente (combustão), despejamos na atmosfera muito mais CO2 do que as plantas conseguem absorver, intensificando o efeito estufa e causando as mudanças climáticas globais.

**3. Ciclo do Nitrogênio (O que mais cai!):**
O N2 compõe 78% do ar, mas nós e as plantas não conseguimos absorvê-lo na respiração! Precisamos do nitrogênio para fazer DNA, RNA e Proteínas. Dependemos inteiramente de bactérias para transformá-lo.
As fases do ciclo:
• **Fixação:** Bactérias do gênero *Rhizobium* (vivem em simbiose nas raízes de plantas leguminosas como feijão, soja, amendoim) e cianobactérias pegam o N2 atmosférico e transformam em Amônia (NH3).
• **Nitrificação:** Bactérias no solo convertem a Amônia tóxica em Nitrato (NO3-), a forma mais assimilável pelas plantas. Dividida em Nitrosação (*Nitrosomonas*, amônia -> nitrito) e Nitratação (*Nitrobacter*, nitrito -> nitrato).
• **Assimilação:** A planta absorve o nitrato, faz proteínas vegetais. Animais comem as plantas.
• **Decomposição (Amonificação):** Restos mortais e urina/fezes viram amônia novamente.
• **Desnitrificação:** Bactérias desnitrificantes (ex: *Pseudomonas*) pegam nitrato no solo e convertem de volta em gás N2 para a atmosfera, fechando o ciclo.

**Exemplo Prático:**
A "adubação verde" ou rotação de culturas envolve plantar feijão ou soja num terreno intercalando com milho. As bactérias *Rhizobium* na raiz do feijão fixam o nitrogênio no solo, deixando o terreno rico nesse nutriente para o milho no próximo plantio, poupando fertilizantes industriais.

⚠️ **Pegadinhas Comuns no Vestibular:**
• Leguminosas (feijão, soja) são essenciais na fixação não porque elas próprias fixam, mas porque abrigam as bactérias fixadoras em seus nódulos radiculares.
• O aquecimento global não é "a criação" do efeito estufa. O efeito estufa é um fenômeno natural e bom, sem o qual a Terra seria congelada. O problema é a INTENSIFICAÇÃO dele pela queima de combustíveis fósseis e desmatamento.

💡 **Dica de Ouro:** A amônia (NH3) é muito tóxica para as plantas, assim como o nitrito (NO2-). Por isso as reações das bactérias de nitrificação são vitais, pois produzem o Nitrato (NO3-), o grande adubo da natureza. Guarde os nomes das bactérias: Rhizobium (fixação) e Nitrossomonas/Nitrobacter (nitrificação)."""

texts[69] = """📚 **Biomas Brasileiros e Globais**

**O que é?**
Bioma é um grande conjunto de ecossistemas com vegetação característica, determinado principalmente pelo clima (temperatura e chuvas) e solo da região. O Brasil possui 6 biomas terrestres oficiais e uma imensa biodiversidade.

**Biomas Brasileiros Essenciais:**
• **Amazônia:** O maior do Brasil. Clima equatorial (quente e muito úmido o ano todo). Maior biodiversidade do planeta. Apresenta rios voadores (chuvas para o resto do país pela evapotranspiração). Solo pobre! A riqueza está na própria floresta (ciclo de decomposição rápida da serapilheira). Vegetação perenifólia (não perde folhas) e latifoliada (folhas grandes).
• **Cerrado:** A "caixa d'água do Brasil" e savana mais rica do mundo. Clima tropical (duas estações bem definidas: verão chuvoso e inverno seco). Solo ácido e rico em alumínio. Vegetação com caules retorcidos, cascas grossas e raízes muito profundas para buscar água lençol freático, resistência a fogo natural.
• **Mata Atlântica:** O mais devastado (sobra ~10%), situado no litoral onde concentra a população. Clima tropical úmido. Rica em espécies endêmicas (ex: mico-leão-dourado).
• **Caatinga:** Bioma exclusivamente brasileiro! Clima semiárido (quente e muito seco, chuvas irregulares). Vegetação xerófita (adaptada à seca): cactos com folhas modificadas em espinhos, caules que armazenam água, raízes superficiais extensas, perdem folhas na seca (caducifólias) para não perder água.
• **Pampa:** Situado no Sul (RS). Clima subtropical (estações bem marcadas, inverno frio). Vegetação herbácea (gramíneas). Sofre com a arenização pelo excesso de pastoreio (gado).
• **Pantanal:** A maior planície alagável do mundo. Considerado um complexo, pois sofre influência da Amazônia, Cerrado e Chaco. Clima tropical com verão chuvoso (cheias dos rios) e inverno seco. Muito utilizado por aves migratórias.

**Biomas Globais de Destaque:**
• **Tundra:** Polo norte. Frio extremo, solo congelado (permafrost). Só crescem musgos e líquens no rápido verão.
• **Taiga (Floresta Boreal/Coníferas):** Abaixo da Tundra. Formada por pinheiros.
• **Desertos:** Extrema amplitude térmica diária e falta de chuva. Plantas xerófitas e animais de hábitos noturnos.

**Exemplo Prático:**
As árvores do Cerrado parecem secas e mortas por fora (caules tortos com casca espessa de cortiça), mas isso é uma adaptação contra as queimadas naturais e deficiência nutricional do solo, enquanto suas raízes longas continuam absorvendo água em profundidade.

⚠️ **Pegadinhas Comuns no Vestibular:**
• Solo da Amazônia não é rico! É um solo arenoso e lavado pelas chuvas (lixiviação). A floresta sobrevive pela reciclagem de folhas que caem. Desmatar para plantar transforma em deserto de areia rápido.
• Diferença entre Desertificação e Arenização. Caatinga sofre desertificação (clima árido); Pampa sofre arenização (solo exposto e vento).
• Espinhos de cactos não são caules secos, são FOLHAS MODIFICADAS para evitar a perda de água pela transpiração estomática!

💡 **Dica de Ouro:** Lembre-se dos "Hotspots" de biodiversidade: áreas com muita biodiversidade endêmica, mas altamente ameaçadas de destruição. No Brasil, o Cerrado e a Mata Atlântica são os dois grandes hotspots mundiais."""

texts[70] = """📚 **Impactos Ambientais e Poluição**

**O que é?**
Alterações no meio ambiente causadas direta ou indiretamente pela atividade humana (antrópica), que afetam a saúde, a segurança e a biodiversidade. No vestibular, é o tema mais cobrado ao lado de Ecologia.

**Principais Impactos e Fenômenos:**
• **Intensificação do Efeito Estufa e Aquecimento Global:** Queima de combustíveis fósseis (petróleo, carvão) e desmatamento liberam excesso de CO2 e Metano (CH4, vindo de lixões e arrotos/flatos de gado - pecuária). Consequências: derretimento de calotas polares, elevação do nível do mar, eventos climáticos extremos, branqueamento de corais (morte das zooxantelas pelo aquecimento e acidificação da água).
• **Eutrofização:** Um "boom" de matéria orgânica na água. Despejo de esgoto doméstico ou fertilizantes agrícolas (ricos em N e P) em rios e lagos. Causa a proliferação excessiva de algas na superfície, bloqueando a luz. Plantas submersas morrem (sem fotossíntese). Bactérias aeróbicas decompõem os mortos gastando todo o oxigênio da água (demanda bioquímica de oxigênio DBO sobe), matando os peixes asfixiados. No fim, sobram bactérias anaeróbicas que liberam mau cheiro (gás sulfídrico).
• **Bioacumulação ou Magnificação Trófica:** Acúmulo de metais pesados (mercúrio dos garimpos) ou agrotóxicos (DDT) ao longo da cadeia alimentar. Essas substâncias não são excretadas. O predador do topo da cadeia (como gaviões, tubarões ou humanos) concentra as doses mais letais em sua gordura.
• **Chuva Ácida:** Queima de combustíveis de indústrias e veículos libera dióxido de enxofre (SO2) e óxidos de nitrogênio (NOx), que reagem com a água das nuvens formando ácido sulfúrico (H2SO4) e nítrico (HNO3). Destrói folhas de plantas, acidifica lagos (matando peixes) e corrói monumentos históricos (mármore/metal).
• **Destruição da Camada de Ozônio:** O gás CFC (presente antigamente em geladeiras e aerossóis) destrói o gás ozônio (O3) na estratosfera. O ozônio é nosso filtro natural contra a radiação Ultravioleta (UV). Sem ele, aumentam os casos de câncer de pele e mutações. (O Protocolo de Montreal quase resolveu esse problema, abolindo o CFC).
• **Inversão Térmica:** Fenômeno natural de inverno em grandes cidades poluidoras (como São Paulo). O ar frio (mais pesado) fica retido perto da superfície, preso por uma camada de ar quente acima dele. Isso impede que os gases poluentes subam e se dispersem, piorando doenças respiratórias.

**Exemplo Prático:**
No Pantanal e Amazônia, garimpeiros usam mercúrio para aglutinar ouro. Esse mercúrio cai no rio. O plâncton absorve 1mg; o peixe pequeno come 100 plânctons (acumula 100mg); o peixe grande come 10 peixes (acumula 1000mg); o ser humano ou a ariranha come o peixe grande e sofre intoxicação cerebral severa.

⚠️ **Pegadinhas Comuns no Vestibular:**
• Confundir Camada de Ozônio com Aquecimento Global. Buraco na camada de ozônio causa CÂNCER e mutação (deixa passar UV). Aquecimento global causa derretimento de gelo e calor (retém infravermelho). São coisas diferentes.
• Chuva ácida não derrete as pessoas na rua como em filmes de ficção! Ela afeta ecossistemas lentamente e o solo. Toda chuva é levemente ácida (pH 5,6 por causa do CO2), mas é a presença de enxofre e nitrogênio que a torna perigosa (pH < 4,5).

💡 **Dica de Ouro:** Memorize a sequence da eutrofização: Esgoto -> Muitas algas na superfície -> Falta de luz -> Morte de plantas submersas -> Proliferação de bactérias aeróbicas -> Queda brusca de Oxigênio (anóxia) -> Morte de peixes -> Mau cheiro. Essa história é questão carimbada todo ano!"""

texts[71] = """📚 **Genética: Primeira e Segunda Lei de Mendel**

**O que é?**
A Genética Clássica, fundamentada pelos experimentos do monge Gregor Mendel com ervilhas (Pisum sativum) no século XIX. Mendel descobriu como as características são passadas de pais para filhos, sem sequer saber o que era DNA ou cromossomo!

**Conceitos Fundamentais - Glossário Genético:**
• **Gene:** Pedaço de DNA que guarda a receita para uma proteína (e consequentemente, uma característica).
• **Alelos:** Versões diferentes de um mesmo gene. Ex: Gene da cor do olho tem o alelo para azul e alelo para castanho.
• **Genótipo:** A composição genética da pessoa (as letrinhas: AA, Aa, aa).
• **Fenótipo:** É a característica física visível, o resultado. (Fenótipo = Genótipo + Meio Ambiente). Ex: Albinismo.
• **Homozigoto (Puro):** Alelos iguais. AA (dominante) ou aa (recessivo).
• **Heterozigoto (Híbrido):** Alelos diferentes. Aa.

**A Primeira Lei de Mendel (Lei da Segregação dos Fatores):**
Cada característica é determinada por um par de fatores (alelos), que se separam (segregam) na formação dos gametas (meiose). Assim, cada gameta recebe apenas um fator de cada par. Trata de UMA característica por vez (Monoibridismo).
• Exemplo: Cruzando ervilhas amarelas heterozigotas (Aa x Aa), a proporção fenotípica na geração F1 será sempre 3:1 (3 amarelas para 1 verde) e proporção genotípica 1:2:1 (1 AA, 2 Aa, 1 aa).

**A Segunda Lei de Mendel (Lei da Segregação Independente):**
Quando duas ou mais características são estudadas (Diibridismo), os fatores de uma característica separam-se independentemente dos fatores da outra. (Isso vale desde que os genes estejam em cromossomos diferentes!).
• Exemplo clássico: Cruzar ervilha Amarela e Lisa (AaBb) com Amarela e Lisa (AaBb).
• A proporção fenotípica clássica de dois duplo-heterozigotos (AaBb x AaBb) é sempre **9:3:3:1** (9 duplo dominantes, 3 dominante para a primeira e recessivo pra segunda, 3 recessivo pra primeira e dominante pra segunda, 1 duplo recessivo).

**Exemplo Prático:**
Se um homem albino (aa) casa com uma mulher de pigmentação normal cujo pai era albino. O genótipo da mulher obrigatoriamente é "Aa" (ela tem pigmento, logo tem o A; mas herdou o 'a' do pai obrigatoriamente). O cruzamento Aa x aa gera 50% de chance de o filho nascer com albinismo.

⚠️ **Pegadinhas Comuns no Vestibular:**
• Esquecer a regra do "E" e do "OU" em probabilidade. Se o exercício pedir "qual a chance de nascer menino E albino", você multiplica as frações. Se pedir "menino OU menina", você soma. (Regra geral: menino sempre é 1/2).
• Herança Intermediária / Ausência de Dominância: As flores maravilhas. Vermelha (VV) x Branca (BB) geram filhas cor-de-rosa (VB). O heterozigoto tem um fenótipo intermediário, não há dominância!
• Codominância: Gado ruão. Branco x Vermelho gera bezerro malhado de branco e vermelho (ambos os fenótipos se expressam).

💡 **Dica de Ouro:** Para descobrir rapidamente quantos tipos de gametas um indivíduo produz, use a fórmula 2^n, onde n é o número de pares heterozigotos. Exemplo: AaBBCcddEe tem 3 heterozigotos (Aa, Cc, Ee). 2³ = 8 tipos diferentes de gametas!"""

texts[72] = """📚 **Sistema ABO, Fator Rh e Alelos Múltiplos**

**O que é?**
Os tipos sanguíneos são o clássico exemplo de "Polialelia" ou Alelos Múltiplos. Em vez de haver apenas duas opções (como na ervilha verde/amarela), num mesmo locus gênico existem 3 ou mais alelos na população para determinar uma mesma característica.

**Conceitos Fundamentais - Sistema ABO:**
Existem 3 alelos: IA, IB e i. 
• O alelo i é recessivo e não produz antígenos.
• Os alelos IA e IB são CODOMINANTES (quando estão juntos, ambos se expressam).
• **Tipo A (Genótipos: IAIA ou IAi):** Possui antígeno A na hemácia. Produz anticorpo anti-B no plasma.
• **Tipo B (Genótipos: IBIB ou IBi):** Possui antígeno B na hemácia. Produz anticorpo anti-A.
• **Tipo AB (Genótipo: IAIB):** Possui os antígenos A e B. NÃO produz anticorpos (é o Receptor Universal, pois aceita sangue de todos sem atacar).
• **Tipo O (Genótipo: ii):** NÃO possui antígenos na hemácia. Produz anticorpos anti-A e anti-B. (É o Doador Universal, pois suas hemácias "lisas" não são atacadas por ninguém).

**Conceitos Fundamentais - Fator Rh:**
Descoberto no macaco *Rhesus*. É um caso clássico de Primeira Lei de Mendel com dominância completa.
• **Rh Positivo (+):** Genótipos RR ou Rr. Tem a proteína na hemácia. Pode receber de + ou -.
• **Rh Negativo (-):** Genótipo rr. Não tem a proteína. Só pode receber sangue negativo. Se receber positivo, ele é sensibilizado e produz anticorpos anti-Rh.

**Doador e Receptor Universal:**
Levando tudo em conta, o O- (O Negativo) é o Doador Universal absoluto (pode dar pra qualquer um). O AB+ (AB Positivo) é o Receptor Universal (recebe de qualquer um).

**Eritroblastose Fetal (Doença Hemolítica do Recém-Nascido - DHRN):**
Condição em que os anticorpos da mãe atacam e destroem o sangue do bebê.
• *Regra OBRIGATÓRIA para ocorrer:* Mãe precisa ser Rh negativo (rr), pai precisa ser Rh positivo, e o bebê precisa ser Rh positivo.
• A primeira gravidez de um bebê positivo geralmente não tem problema. Mas no parto, o sangue do bebê vaza pra mãe, que fabrica os anticorpos anti-Rh (fica "vacinada/sensibilizada").
• Na SEGUNDA gravidez de um bebê positivo, os anticorpos da mãe cruzam a placenta e atacam as hemácias do feto.
• *Prevenção:* A mãe toma uma injeção de soro com anticorpos logo após o primeiro parto para destruir as hemácias do bebê antes que o sistema dela memorize, protegendo a próxima gestação.

**Exemplo Prático:**
Em testes de paternidade, se uma mãe O (ii) e um pai AB (IAIB) têm um filho. O filho SÓ PODE SER tipo A (IAi) ou tipo B (IBi). Nunca será AB, nem será O. Isso elimina suspeitos!

⚠️ **Pegadinhas Comuns no Vestibular:**
• A Eritroblastose fetal NUNCA afeta mães Rh positivo, não importa o sangue do bebê. Só ocorre em MÃE NEGATIVA com BEBÊ POSITIVO!
• Pessoas do tipo AB não têm anticorpos contra o sistema ABO, mas podem ter anti-Rh caso sejam AB negativo.
• Outro clássico de alelos múltiplos é a cor da pelagem de coelhos (Selvagem C > Chinchila c^ch > Himalaia c^h > Albino c).

💡 **Dica de Ouro:** O tipo "O" é extremamente comum, mesmo sendo o genótipo duplo recessivo (ii). Ser dominante na genética não significa ser abundante na população; significa apenas que inibe o outro alelo. O gene 'i' na população brasileira tem alta frequência."""

texts[73] = """📚 **Heredogramas e Genética Ligada ao Sexo**

**O que é?**
A análise de heredogramas (árvores genealógicas) permite descobrir como uma doença ou característica flui pelas gerações e se é dominante, recessiva, autossômica ou ligada aos cromossomos sexuais. O ser humano tem 46 cromossomos (44 autossomos que definem o corpo, e 2 sexuais que definem o sexo: XX mulher, XY homem).

**Conceitos Fundamentais - Analisando Heredogramas:**
• **Símbolos:** Quadrado = homem; Círculo = mulher; Pintado de escuro = Afetado pela característica em estudo; Losango = sexo não informado.
• **Regra de Ouro da Recessividade:** Procure um casal igual (mesmo fenótipo) que tem um filho diferente deles. *Os pais são obrigatoriamente HETEROZIGOTOS e o filho diferente é o RECESSIVO.* Ex: Pais normais têm filho albino. Pais são Aa, filho é aa. A doença é recessiva.

**Herança Ligada ao Sexo (Ligada ao cromossomo X):**
Os homens só têm um cromossomo X (herdam sempre da mãe), e o Y (do pai). As mulheres têm dois X. Doenças recessivas ligadas ao X afetam MUITO MAIS homens do que mulheres.
• **Daltonismo e Hemofilia:** São recessivos ligados ao X (Xd e Xh).
• Homem daltônico é (Xd Y). Só precisa de 1 gene mutante para adoecer, porque não tem outro X pra cobrir.
• Mulher daltônica é (Xd Xd). Precisa de 2 genes mutantes, por isso é mais raro.
• Regra: Um homem doente SEMPRE passa o gene mutante pra todas as suas filhas (que serão portadoras), mas NUNCA pros seus filhos homens (pra homem ele só manda o Y).
• Regra: Uma mulher doente passa a doença para 100% de seus filhos homens.

**Herança Restrita ao Sexo (Ligada ao cromossomo Y):**
Também chamada de herança holândrica. Só passa de pai para filho (homem para homem), nunca afetará mulheres. Ex: Hipertricose auricular (pelos nas orelhas).

**Exemplo Prático:**
No daltonismo: Um homem normal (XD Y) casa com uma mulher portadora (XD Xd). A chance de o filho homem nascer daltônico é de 50% (porque ele receberá o Y do pai obrigatoriamente, e tem 50% de chance de receber o Xd da mãe). A chance de a filha nascer daltônica é ZERO (porque ela receberá o XD normal do pai obrigatoriamente).

⚠️ **Pegadinhas Comuns no Vestibular:**
• Em questões com heredograma, preste atenção se pedem a probabilidade de nascer "uma menina doente" vs "dentre as meninas, qual a chance de ser doente". No primeiro caso você precisa multiplicar por 1/2. No segundo caso você foca só nas filhas e esquece a chance de sexo.
• Mulher "Portadora" não é doente. Ela tem o gene escondido (heterozigota, Aa ou XD Xd), fenótipo normal!

💡 **Dica de Ouro:** Dicas rápidas para matar heredograma:
- Se homens e mulheres são afetados em proporção igual, é autossômica.
- Se pula gerações (pais normais têm filho doente), é recessiva.
- Se todo homem doente tem a mãe obrigatoriamente doente, é forte indício de ligada ao X recessiva."""

texts[74] = """📚 **Biotecnologia, Engenharia Genética e DNA**

**O que é?**
A manipulação do material genético (DNA ou RNA) de organismos para produzir bens, tecnologias ou resolver problemas. Hoje, grande parte das questões de biologia atual aborda testes de DNA, vacinas de mRNA, clonagem, transgênicos e a técnica de CRISPR.

**Conceitos Fundamentais e Técnicas Essenciais:**

• **Enzimas de Restrição ("Tesouras biológicas"):** Enzimas que reconhecem sequências específicas de bases nitrogenadas no DNA e cortam exatamente naquele local. Extraídas de bactérias. São o primeiro passo para criar qualquer DNA recombinante.
• **DNA Recombinante:** A união do DNA de espécies diferentes. Um gene de interesse humano (ex: gene da insulina) é cortado por uma enzima de restrição e "colado" no DNA de uma bactéria (plasmídio). A bactéria, ao se multiplicar, passará a produzir a proteína humana!
• **Transgênicos (Organismos Geneticamente Modificados - OGM):** Todo transgênico é um OGM que recebeu um gene de uma ESPÉCIE DIFERENTE. Ex: Milho *Bt* (recebeu um gene da bactéria Bacillus thuringiensis que produz uma toxina fatal para a lagarta-do-cartucho, tornando a planta resistente a essa praga sem uso de agrotóxico externo). O benefício é aumento de produtividade, mas o risco ambiental inclui a contaminação de campos de milho orgânico e morte de insetos não-alvo.
• **PCR (Reação em Cadeia da Polimerase):** É a "máquina copiadora de DNA". Se você achou uma gotinha de sangue na cena do crime, usa a PCR em laboratório com a enzima Taq-Polimerase e variações de temperatura para multiplicar esse DNA milhões de vezes para facilitar a análise.
• **Teste de DNA (Eletroforese em gel / DNA Fingerprint):** O DNA fragmentado é colocado num gel sob corrente elétrica. O DNA tem carga NEGATIVA e corre para o polo positivo. Os pedaços menores correm mais rápido e mais longe. O padrão de "barras" formado é a identidade da pessoa. No teste de paternidade, TODA BARRINHA que o filho tem PRECISA estar no pai ou na mãe. Se o filho tem uma banda que a mãe não tem, obrigatoriamente tem que vir do pai.
• **Clonagem:** Criação de cópias genéticas exatas. A ovelha Dolly foi feita pegando o núcleo de uma célula da mama de uma ovelha adulta (doadora do DNA) e inserindo num óvulo de outra ovelha do qual o núcleo original foi removido.
• **Células-Tronco:** Células indiferenciadas com potencial para formar qualquer tecido. As *Totipotentes* (fase mórula, até 3º dia) formam o corpo e os anexos embrionários (placenta). As *Pluripotentes* (blastocisto) formam todo o feto. As *Multipotentes* (adultas, ex: medula óssea) só formam os tecidos de sua linhagem (sangue).

**Exemplo Prático:**
A insulina que os diabéticos injetam hoje não vem mais do pâncreas de porcos ou bois como há décadas atrás. Ela é idêntica à humana, produzida em escala industrial por bactérias transgênicas que possuem o gene humano inserido em seu DNA plasmidial.

⚠️ **Pegadinhas Comuns no Vestibular:**
• Todo transgênico é um OGM, mas nem todo OGM é transgênico (um organismo pode ter seu próprio gene modificado ou silenciado sem receber DNA de fora, como num tipo de maçã que não escurece).
• Na clonagem, o clone é geneticamente idêntico a quem doou o NÚCLEO celular (DNA), e não à mãe de aluguel ou quem doou o óvulo vazio. (Entretanto, cuidado: o DNA mitocondrial do clone vem de quem doou o óvulo!).

💡 **Dica de Ouro:** Lembre do Dogma Central da Biologia Molecular! DNA sofre replicação, é transcrito em RNA (no núcleo), e o RNA é traduzido em Proteína (no citoplasma/ribossomo). Mas atenção à exceção: Retrovírus (como o HIV) fazem transcrição reversa (RNA volta para DNA) usando a enzima Transcriptase Reversa!"""

texts[75] = """📚 **Teorias Evolutivas e Neodarwinismo**

**O que é?**
A evolução estuda como as espécies sofrem modificações ao longo das gerações. É o pilar central da Biologia contemporânea. Foca principalmente no contraste histórico entre as ideias de Lamarck, a Revolução de Darwin e a síntese moderna (Neodarwinismo).

**Conceitos Fundamentais - Lamarckismo (Início do Séc. XIX):**
Lamarck foi o pioneiro em afirmar que o ambiente induz mudanças, mas errou no "como".
• **Lei do Uso e Desuso:** Se um órgão é muito usado, ele hipertrofia (cresce/fortalece); se não for usado, atrofia (some).
• **Lei da Transmissão dos Caracteres Adquiridos:** Aquilo que um ser adquiriu em vida passa para seus descendentes. (O erro letal: alterações corporais que não estão nos gametas/DNA não são herdáveis!).
• Exemplo clássico lamarckista: A girafa esticou tanto o pescoço para comer folhas altas que seu pescoço cresceu, e ela passou isso para o filho.

**Conceitos Fundamentais - Darwinismo (Meados do Séc. XIX):**
Charles Darwin (junto com Wallace) publicou "A Origem das Espécies".
• **A grande ideia:** Na natureza existe *Variabilidade pré-existente* (já existem girafas de pescoço longo, médio e curto na mesma população).
• **Seleção Natural:** O ambiente não causa a mudança, ele "peneira" ou SELECIONA quem já está mais bem adaptado a ele. As girafas de pescoço longo conseguem mais comida e, por isso, sobrevivem e deixam mais descendentes. Com o tempo, a população inteira se torna de pescoço longo.
• O "tendão de Aquiles" de Darwin: Ele não sabia como surgia a variabilidade (genes e mutações eram desconhecidos na época!).

**Conceitos Fundamentais - Neodarwinismo (Teoria Sintética da Evolução - Séc. XX):**
A união perfeita: Seleção Natural de Darwin + Genética Mendeliana.
• Responde à pergunta que Darwin não pôde: A variabilidade nas populações é gerada por duas coisas: **Mutação** (alterações aleatórias no DNA, única fonte de novos genes) e **Recombinação Genética** (crossing-over na meiose e a fecundação, que misturam genes velhos em novas combinações).
• A seleção natural atua sobre esses fenótipos selecionando os genes mais vantajosos.

**Evidências da Evolução:**
• Fósseis.
• **Órgãos Homólogos:** Mesma origem embrionária, estruturas internas parecidas, mas podem ter funções diferentes. Indica ancestralidade comum (Irradiação adaptativa). Ex: Braço humano, asa de morcego e nadadeira de baleia.
• **Órgãos Análogos:** Origem diferente, estruturas diferentes, mas têm a mesma função porque vivem no mesmo ambiente. Não indica grau de parentesco próximo! (Convergência evolutiva). Ex: Asa de inseto e asa de pássaro.
• Órgãos vestigiais (apêndice).

**Exemplo Prático:**
A resistência a antibióticos em bactérias. O remédio não "ensina" a bactéria a ficar resistente ou sofre mutação para sobreviver (Lamarckismo). A verdade é que numa população de milhões, por acaso e por mutação natural, já existem 2 bactérias resistentes. O remédio mata as fracas, as resistentes sobrevivem, se reproduzem sem competição e dominam tudo (Darwinismo/Neodarwinismo).

⚠️ **Pegadinhas Comuns no Vestibular:**
• Falar que a espécie "evoluiu PARA não morrer" está errado! É teleologia (finalidade). A evolução não tem propósito ou intenção. A mutação ocorre ao acaso. A seleção natural "peneira" depois.
• Dizer que o "homem veio do macaco" é incorreto na evolução. Homens e chimpanzés modernos evoluíram de um mesmo ancestral comum que existiu no passado.

💡 **Dica de Ouro:** No vestibular, atente ao texto. Se o texto focar no uso/esforço do animal e herança disso, gabarito: Lamarck. Se focar que a natureza escolheu os mais aptos a deixar filhotes, gabarito: Darwin. Se o texto incluir "DNA", "alelos" ou "mutação", gabarito: Neodarwinismo!"""

texts[76] = """📚 **Fisiologia Humana: Digestório e Circulatório**

**O que é?**
O estudo de como o corpo humano quebra os alimentos para obter nutrientes básicos (sistema digestório) e como distribui esses nutrientes, gases respiratórios e defesas por todo o corpo através do sangue (sistema circulatório).

**Conceitos Fundamentais - Sistema Digestório:**
A digestão transforma polímeros (amido, proteínas) em monômeros (glicose, aminoácidos) absorvíveis.
• **Boca:** Mastigação (mecânica) e insalivação (química). Na saliva atua a enzima **ptialina (amilase salivar)**, que inicia a quebra de AMIDO num pH neutro (~7,0).
• **Estômago:** Foco total na digestão de PROTEÍNAS. Lá o pH é extremamente ácido (~2,0) devido ao ácido clorídrico (HCl). A enzima **Pepsina** quebra as cadeias longas de proteínas. O bolo alimentar vira um líquido chamado *quimo*.
• **Intestino Delgado (Duodeno):** É a etapa principal. Recebe o quimo ácido. O fígado/vesícula lança a **Bile** (ATENÇÃO: bile não é enzima, é um detergente que emulsifica gorduras/lipídios). O pâncreas lança o **Suco Pancreático** (tem enzimas pra tudo: lipases, amilases e proteases como a tripsina, além do bicarbonato de sódio para neutralizar o ácido e deixar o pH ~8,0).
• **Intestino Delgado (Jejuno-íleo):** Onde ocorre a absorção dos nutrientes (aminoácidos, açúcares, gorduras) pelas **microvilosidades** intestinais (dobras que aumentam a área de contato).
• **Intestino Grosso:** Absorção de água, sais minerais e formação das fezes.

**Conceitos Fundamentais - Sistema Circulatório (Cardiovascular):**
Nos humanos é um sistema fechado (o sangue corre sempre dentro de vasos) e duplo (passa 2x pelo coração por ciclo).
• **Lado Direito do Coração:** Só corre sangue VENOSO (rico em CO2).
• **Lado Esquerdo do Coração:** Só corre sangue ARTERIAL (rico em O2). Os lados não se misturam (circulação completa).
• **Artérias:** Vasos musculosos e elásticos que SAEM do coração sob alta pressão (a maioria leva sangue arterial, exceto a artéria pulmonar).
• **Veias:** Vasos finos que CHEGAM ao coração. Têm *válvulas* para impedir o refluxo do sangue e contam com a contração dos músculos esqueléticos para bombear o sangue contra a gravidade. (A maioria leva sangue venoso, exceto a veia pulmonar).
• **Capilares:** Extremamente finos (uma célula de espessura) onde ocorre a troca gasosa com os tecidos.
• **Pequena Circulação:** Coração (Vent. Direito) -> Pulmão (oxigena) -> Coração (Átrio Esquerdo).
• **Grande Circulação:** Coração (Vent. Esquerdo) -> Corpo inteiro -> Coração (Átrio Direito).

**Exemplo Prático:**
Uma pessoa que retirou a vesícula biliar continuará digerindo gorduras, mas com mais dificuldade. A vesícula apenas armazena a bile produzida pelo fígado; sem ela, o gotejamento de bile é constante e menos eficiente após uma grande refeição gordurosa, como comer uma feijoada.

⚠️ **Pegadinhas Comuns no Vestibular:**
• A BILE NÃO É ENZIMA. E a bile é produzida no FÍGADO, a vesícula só armazena!
• Não confunda: Artéria sai do coração, Veia entra no coração. A definição não é pelo tipo de sangue que carrega! A artéria pulmonar leva sangue VENOSO pro pulmão.
• O lado mais espesso/musculoso do coração é o Ventrículo ESQUERDO, porque ele precisa fazer uma força absurda para jogar sangue pro corpo inteiro, enquanto o direito só joga pro vizinho (pulmão).

💡 **Dica de Ouro:** Decore a trinca do pH digestivo: Boca = 7 (Amido); Estômago = 2 (Proteínas); Intestino = 8 (Lipídios e o resto). Se a enzima do estômago for pro intestino, ela inativa pelo excesso de base. Se a da boca for pro estômago, frita no ácido."""

texts[77] = """📚 **Fisiologia Humana: Nervoso e Endócrino**

**O que é?**
Os sistemas de controle e integração do corpo. O Sistema Nervoso atua de forma rápida, elétrica e pontual (através de impulsos nervosos). O Sistema Endócrino atua de forma mais lenta, duradoura e sistêmica (lançando hormônios no sangue). Juntos, mantêm a homeostase (equilíbrio interno).

**Conceitos Fundamentais - Sistema Nervoso:**
• **Neurônio:** A célula funcional. Possui Dendritos (recebem o sinal), Corpo Celular e Axônio (transmite o sinal para frente).
• **Impulso Nervoso:** Natureza elétrica. A despolarização da membrana celular ocorre pela abertura dos canais de Na+ (Sódio entra, deixando o interior positivo). A repolarização ocorre pela saída de K+. (Bomba de Sódio e Potássio ajuda a restaurar depois). O impulso é sempre no sentido Dendrito -> Corpo -> Axônio.
• **Bainha de Mielina:** Capa de lipídio que isola o axônio, fazendo o impulso elétrico saltar, tornando-o muito MAIS RÁPIDO.
• **Sinapse:** O espaço entre dois neurônios. A comunicação ali não é elétrica, mas química! Os **neurotransmissores** (ex: dopamina, serotonina, acetilcolina) são lançados na fenda e encaixam nos receptores do neurônio seguinte.
• **Divisões:**
  - *SNC (Central):* Encéfalo e Medula Espinhal. Analisa e decide. O cerebelo coordena equilíbrio e movimento motor. O bulbo comanda o ritmo cardiorrespiratório.
  - *SNP (Periférico):* Nervos (motores e sensitivos). Destaca-se o Autônomo, dividido em **Simpático** (Prepara pro estresse, luta ou fuga, libera adrenalina, taquicardia, dilata pupila) e **Parassimpático** (Descanso e digestão, libera acetilcolina, bradicardia).

**Conceitos Fundamentais - Sistema Endócrino (Glândulas principais):**
Glândulas endócrinas jogam hormônios direto no sangue para atuar em células-alvo.
• **Hipófise e Hipotálamo:** O "mestre" do corpo. O hipotálamo liga o nervoso ao endócrino e comanda a hipófise. A Neuro-hipófise (posterior) não produz hormônios, só armazena o ADH (antidiurético, retém água no rim) e a Ocitocina (contração do parto e ejeção do leite) produzidos no hipotálamo. A Adeno-hipófise (anterior) produz o hormônio do crescimento (GH) e tróficos que estimulam outras glândulas (TSH, FSH, LH).
• **Tireoide:** Produz T3 e T4 (que regulam o metabolismo, contêm Iodo) e Calcitonina (tira cálcio do sangue e põe no osso). O hipotireoidismo deixa a pessoa lenta, ganhando peso.
• **Paratireoide:** Produz Paratormônio (tira cálcio do osso e joga no sangue). É o antagônico da Calcitonina.
• **Pâncreas (Glândula Mista):** Produz suco pancreático (exócrino) e hormônios nas Ilhotas de Langerhans. Insulina (células beta): guarda a glicose do sangue nas células, baixando a glicemia. Glucagon (células alfa): tira glicose do fígado (quebrando glicogênio) pra jogar no sangue quando você está de jejum.

**Exemplo Prático:**
Uma pessoa após um grave acidente na rua ativa seu Sistema Nervoso Simpático e sua medula adrenal: pupila dilata para ver melhor, coração dispara pra mandar sangue pro músculo, brônquios abrem e digestão para. É a reação de "Luta ou Fuga".

⚠️ **Pegadinhas Comuns no Vestibular:**
• O impulso nervoso no axônio é ELÉTRICO, mas na fenda sináptica é QUÍMICO (neurotransmissores).
• Diabetes Tipo 1: Doença autoimune que destrói as células do pâncreas; o corpo não produz insulina (precisa tomar injeção). Diabetes Tipo 2: Adquirida ao longo da vida (resistência periférica); o pâncreas produz insulina, mas as células obesas não conseguem encaixá-la no receptor.

💡 **Dica de Ouro:** O consumo de álcool inibe intensamente a liberação do hormônio ADH. Sem ADH, o seu rim não reabsorve água, então você urina litros e fica desidratado, causando a famosa ressaca e dor de cabeça."""

texts[78] = """📚 **Imunologia, Vacinas e Soros**

**O que é?**
É o ramo da biologia que estuda o sistema de defesa do organismo contra patógenos (vírus, bactérias, vermes) ou moléculas estranhas. O sistema imunológico reconhece o "não-próprio" e monta estratégias para destruí-lo. As vacinas e soros são os carros-chefes desse tema no vestibular!

**Conceitos Fundamentais - Elementos de Defesa:**
• **Antígeno:** É qualquer corpo, partícula ou proteína ESTRANHA que invade o seu organismo e estimula uma resposta imune. Ex: a espícula de um coronavírus, ou o veneno de uma cobra.
• **Anticorpo (Imunoglobulina):** Proteínas de defesa no formato de "Y" produzidas pelo nosso corpo (pelos Linfócitos B maduros, chamados plasmócitos) EXCLUSIVAMENTE para se ligar a um antígeno específico e inativá-lo. "Encaixe chave-fechadura".
• **Fagócitos (Macrófagos e Neutrófilos):** A "infantaria". Englobam o invasor (fagocitose) e o destroem nos lisossomos.
• **Linfócitos:** A "tropa de elite". 
  - *Linfócito T Auxiliar (CD4):* O comandante. Ele reconhece o antígeno e "chama/avisa" todo o exército. (É a célula que o vírus do HIV ataca e destrói, causando a AIDS pela falta de comando).
  - *Linfócito B:* Transforma-se em plasmócito e fábrica os anticorpos na linha de frente.

**Imunização Ativa vs. Imunização Passiva:**
A chave para acertar qualquer questão desse assunto.
• **Imunização Ativa (VACINA):** O corpo trabalha!
  - Você injeta o antígeno morto, atenuado (enfraquecido), ou apenas um pedaço dele (como o mRNA) na pessoa saudável.
  - Como o patógeno é fraco, não causa a doença, mas o corpo o reconhece e monta uma resposta primária: fabrica anticorpos devagar, MAS produz as preciosas **CÉLULAS DE MEMÓRIA**.
  - Finalidade: PREVENTIVA. Proteção de longa duração. Se o patógeno de verdade invadir meses depois, a resposta secundária de memória será imediata, massiva e você não adoecerá.
• **Imunização Passiva (SORO):** O corpo é preguiçoso.
  - Você injeta ANTICORPOS prontos (já formados por outro animal, ex: num cavalo que foi injetado com veneno de cobra antes).
  - Como os anticorpos não foram feitos por você, seu corpo os destrói em poucos dias e NÃO gera células de memória imunológica.
  - Finalidade: CURATIVA. Uso emergencial. Serve para toxinas ou venenos fulminantes de picada de aranha/cobra onde você não tem 15 dias pra esperar o corpo fazer anticorpo (senão você morre hoje!).

**Exemplo Prático:**
Se uma pessoa pisa num prego enferrujado, ela precisa tomar o SORO antitetânico no pronto-socorro para ter anticorpos imediatos neutralizando as toxinas da bactéria antes de atingir o sistema nervoso. Mas no calendário infantil, a criança toma a VACINA antitetânica preventivamente.

⚠️ **Pegadinhas Comuns no Vestibular:**
• Antibióticos não matam vírus, matam BACTÉRIAS. Não curam gripe! 
• Soro não é vacina. Soro = Cura, Ação Imediata, Anticorpo pronto, Sem memória. Vacina = Prevenção, Ação lenta, Antígeno atenuado, Gera memória.
• O leite materno (via colostro) e a transferência via placenta são exemplos naturais de Imunização Passiva, onde a mãe manda os anticorpos prontos para proteger o bebê nos primeiros meses de vida.

💡 **Dica de Ouro:** Fique ligado nos gráficos de imunização em eixos! Se aplicar uma dose e a curva de anticorpos subir lenta, baixar, e depois de uma segunda dose a curva subir como um foguete bem mais alta que a primeira, é gráfico clássico de VACINA provando a Ação de Memória Secundária."""

texts[79] = """📚 **Botânica: Grupos Vegetais**

**O que é?**
A botânica no Ensino Médio exige que você conheça a história evolutiva das plantas (Reino Plantae) na conquista do ambiente terrestre e as características e inovações que separam os 4 grandes grupos: Briófitas, Pteridófitas, Gimnospermas e Angiospermas. O critério principal é sempre focar no ciclo de vida (alternância de gerações) e tecidos de transporte.

**Conceitos Fundamentais - Evolução Vegetal:**
Toda planta veio de uma alga verde ancestral, desenvolveu tecidos e passou a abrigar o embrião (embriófitas).
• **Fase predominante:** Nas Briófitas é o gametófito (haploide n, produz gametas). Em TODOS os outros grupos, a fase que você vê (a árvore/planta verde grande) é o esporófito (diploide 2n, que produz esporos).

**Os 4 Grandes Grupos:**
1. **Briófitas (Musgos e Hepáticas):**
   - As "anãs" do reino vegetal.
   - São Avasculares (NÃO têm vasos condutores de seiva - xilema e floema). Como os nutrientes passam de célula a célula por difusão (lento), elas são sempre rasteiras, de pequeno porte (não conseguem crescer alto).
   - Dependem EXTREMAMENTE da água ambiental para a fecundação (o gameta masculino, anterozoide, precisa nadar até a oosfera nas poças de chuva). Vivem em locais sombreados e úmidos.
2. **Pteridófitas (Samambaias e Avencas):**
   - Primeira grande inovação: Adquirem Vasos Condutores (Xilema: sobe água e sais / Floema: desce glicose). Isso permitiu que atingissem grandes alturas (como o xaxim).
   - Ainda não têm sementes nem flores!
   - Ainda são dependentes de água para a fecundação (espermatozoide ainda nada). Sob a folha da samambaia ficam uns pontinhos marrons (os soros), que são os depósitos de esporos espalhados pelo vento.
3. **Gimnospermas (Pinheiros, Araucárias, Ciprestes):**
   - Duas grandes inovações revolucionárias que permitiram o domínio da Terra: 
     1) **Semente:** O pinhão! Protege e nutre o embrião contra ressecamento.
     2) **Tubo Polínico (Polinização via vento - anemofilia):** O grão de pólen é levado pelo vento (livres da água!) e ele próprio cria um tubo até o óvulo. A independência da água para reprodução é total!
   - Seu nome significa "semente nua": Têm semente (pinhão), mas NÃO têm FLORES coloridas nem FRUTOS ao redor da semente. O órgão reprodutor são os cones/estróbilos (pinhas).
4. **Angiospermas (A grande maioria das plantas: Mangueiras, Rosas, Milho, Grama):**
   - Últimas e maiores inovações: **Flores completas e Frutos!**
   - Flores atraem animais (insetos, aves, morcegos) garantindo uma polinização super eficiente em troca de néctar, sem depender apenas do vento aleatório.
   - O Fruto é o ovário da flor desenvolvido! Ele protege a semente e facilita absurdamente a dispersão dela através dos animais que a comem e defecam longe da mãe.

**Exemplo Prático:**
Você saboreia muito as angiospermas: a maçã (pseudofruto), a laranja, o tomate, o arroz, o feijão. A única semente de Gimnosperma famosa no prato do brasileiro é o Pinhão da araucária (Mata de Araucária, Sul do Brasil).

⚠️ **Pegadinhas Comuns no Vestibular:**
• Briófitas não têm raiz, caule ou folha verdadeiros; são chamados de rizoides, cauloides e filoides por não terem vasos condutores.
• Pinhão é SEMENTE de pinheiro, não é fruto. Pinha é a "inflorescência" estróbilo, não é fruto.
• Semente NÃO VEM da fecundação do grão de pólen com o ovário. O ovário vira FRUTO. O óvulo vira a SEMENTE!

💡 **Dica de Ouro:** Lembre da escadinha evolutiva: Vasos aparecem nas Pteridófitas; Semente e pólen nas Gimnospermas; Flor e Fruto nas Angiospermas."""

texts[80] = """📚 **Zoologia: Invertebrados e Vertebrados**

**O que é?**
A zoologia no nível médio concentra-se na evolução dos filos do Reino Animal, desde os mais primitivos (sem tecidos) até os mais complexos vertebrados, focando em suas aquisições morfológicas adaptativas para alimentação, respiração, excreção e conquista do meio terrestre.

**Conceitos Fundamentais - Os Invertebrados (Cerca de 95% das espécies animais):**
Nove filos principais que caem em prova:
• **Poríferos (Esponjas):** Os mais primitivos. NÃO possuem tecidos verdadeiros, nem sistema nervoso ou digestivo. Filtram a água por células chamadas coanócitos. Vivem fixos (sésseis).
• **Cnidários (Águas-vivas, Corais):** Primeiros a ter tecidos e um sistema nervoso (difuso em rede). Têm simetria radial e cavidade digestória incompleta (só boca, sem ânus). Capturam presas com células urticantes (cnidócitos).
• **Platelmintos (Planárias, Tênias):** Vermes chatos. Primeiros a ter simetria bilateral e sistema nervoso cefalizado. Maioria parasita (Causam Teníase e Esquistossomose). Excretam por células-flama.
• **Nematelmintos (Lombrigas):** Vermes cilíndricos. Primeiros a ter tubo digestivo completo (boca e ânus, o fluxo da comida fica unidirecional). Parasitas que causam ascaridíase e ancilostomose.
• **Moluscos (Caracóis, Lulas, Polvos):** Corpo mole, geralmente com concha calcária. Têm manto. Excreção por nefrídios. Respiração branquial ou "pulmonar".
• **Anelídeos (Minhocas, Sanguessugas):** Corpo segmentado (metameria). Respiração cutânea. Fecham o ciclo da terra (humus). Sistema circulatório fechado!
• **Artrópodes (Insetos, Aranhas, Crustáceos):** O grupo de maior sucesso! Presença de exoesqueleto de quitina (proteção contra desidratação, mas exige "mudas/ecdise" para crescerem) e patas articuladas. Insetos (3 pares de patas, voam), Aracnídeos (4 pares).
• **Equinodermos (Estrela-do-mar, Ouriço-do-mar):** Curiosidade suprema: são invertebrados, mas são o grupo mais próximo de nós na evolução porque, assim como nós cordados, são deuterostômios (o ânus forma-se antes da boca no embrião).

**Conceitos Fundamentais - Os Cordados (Vertebrados):**
No embrião têm notocorda, tubo nervoso dorsal e fendas faringianas.
• **Peixes:** Ósseos ou cartilaginosos (tubarão). Coração com 2 cavidades. Ectotérmicos (sangue frio).
• **Anfíbios (Sapos, Salamandras):** Transição água/terra. Sofrem metamorfose. Respiração pulmonar precária e MUITA respiração pela pele (cutânea úmida). Ainda dependem d'água para botar seus ovos (que não têm casca). Coração com 3 cavidades. Ectotérmicos.
• **Répteis (Cobras, Tartarugas, Crocodilos):** A CONQUISTA definitiva do ambiente terrestre (assim como as Gimnospermas nas plantas). Ganharam um ovo com casca resistente (ovo amniótico) e escamas impermeáveis de queratina. Não precisam d'água para procriar. Ectotérmicos.
• **Aves:** Penas (voo e calor), voo, ossos pneumáticos. Endotérmicas (sangue quente, mantêm temperatura constante, exigem muito alimento). Coração com 4 cavidades separadas.
• **Mamíferos:** Têm pelos, glândulas mamárias (produzem leite). Diafragma para respiração. Endotérmicos. Coração 4 cavidades. Cérebro muito desenvolvido. (Maioria são placentários).

**Exemplo Prático:**
A estrela-do-mar (Equinodermo) não tem nada a ver com o polvo ou inseto. Evolutivamente, está na mesma "árvore de primos" que nós, pois durante o desenvolvimento embrionário inicial o blastóporo formou primeiro o ânus e depois a boca. Invertebrados inferiores (como insetos) são protostômios (boca primeiro).

⚠️ **Pegadinhas Comuns no Vestibular:**
• Inseto não tem sangue igual ao nosso. Eles têm hemolinfa e respiram por traqueias (tubinhos que ligam a rua direto às células). O "sangue" do inseto NÃO transporta oxigênio, por isso você matar uma barata e esmagá-la não sai vermelho.
• Aves e mamíferos conquistaram a endotermia de forma INDEPENDENTE por convergência evolutiva. Eles não vieram do mesmo ancestral endotérmico.

💡 **Dica de Ouro:** A conquista do ambiente terrestre pelos vertebrados teve 3 grandes aliados: pulmões eficientes, pele grossa e queratinizada (não perde água) e O OVO AMNIÓTICO COM CASCA (um tanque de água portátil para o feto crescer sem ressecar no deserto). Isso define os Répteis!"""

with open(r'c:\xampp\htdocs\2025\projeto-final\database\theory_biologia.php', 'w', encoding='utf-8') as f:
    f.write('<?php\n')
    f.write('// Resumos teóricos completos — Biologia\n')
    f.write('return [\n')
    for key in sorted(texts.keys()):
        # Escape backslashes, double quotes, dollar signs
        safe_text = texts[key].replace('\\', '\\\\').replace('"', '\\"').replace('$', '\\$')
        # Replace newlines with literal \\n (so it prints as \n in the PHP file)
        safe_text = safe_text.replace('\n', '\\n')
        f.write(f'    {key} => "{safe_text}",\n')
    f.write('];\n')
