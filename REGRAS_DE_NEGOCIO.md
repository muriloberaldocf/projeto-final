# HIPOGABARITO — ESPECIFICAÇÃO EXAUSTIVA DE REGRAS DE NEGÓCIO (RN)

> **Documento Oficial de Engenharia de Software e Regras de Negócio**  
> **Sistema:** HipoGabarito (Plataforma Gamificada de Aprendizagem e Preparação para Vestibulares)  
> **Versão:** 3.0 Pro  
> **Data de Homologação:** Outubro / 2026  
> **Status:** Ativo e Homologado em Produção  

---

## 1. Visão Geral do Sistema

O **HipoGabarito** é uma plataforma educacional web gamificada desenvolvida para estudantes em preparação para o **ENEM**, **FUVEST (USP)**, **UNICAMP (COMVEST)**, **VUNESP (UNESP)**, **UERJ** e vestibulares técnicos (**SENAI**).

Inspirado no modelo de engajamento do Duolingo e no rigor avaliativo do ENEM/INEP e da Plataforma Redigir, o sistema combina:
1. **Trilha Pedagógica Não-Linear (Snake Path)** com progressão por unidades e matérias;
2. **Modo Chefão (Boss Challenge)** para testes de alta complexidade ao final de cada unidade;
3. **Motor Inteligente de Correção de Redações** alimentado por IA Multimodal (Google Gemini 2.5 Pro) com OCR de manuscritos e detecção de IA/plágio;
4. **Gerador Dinâmico de Simulados Cronometrados** balanceados por banca e curva de dificuldade;
5. **Economia de Gamificação Meritocrática**, desprovida de bloqueios punitivos (sem sistema de vidas/corações);
6. **Rede Social e Liga Privada de Amigos (Leaderboard)** para estímulo competitivo saudável.

---

## 2. Matriz Completa de Regras de Negócio (RN)

### DOMÍNIO 1: AUTENTICAÇÃO, CONTROLE DE ACESSO E SEGURANÇA

| Código | Nome da Regra | Especificação Técnica e Condições de Disparo | Tratamento de Violação / Exceção |
| :--- | :--- | :--- | :--- |
| **RN-01** | **Perfis de Acesso (RBAC)** | Os usuários possuem papel (`role`) classificado como `student` (estudante) ou `admin` (professor/administrador). Rotas administrativas requerem estritamente `role = 'admin'`. Rotas protegidas exigem sessão ativa (`$_SESSION['user_id']`). | Redirecionamento imediato para `index.php` com encerramento de fluxo em caso de usuário não autenticado ou erro HTTP 403 para rotas restritas. |
| **RN-02** | **Política de Senhas e Criptografia Bcrypt** | Todas as senhas cadastradas no sistema devem conter **no mínimo 6 caracteres**. O armazenamento no banco é obrigatoriamente criptografado com o algoritmo nativo Bcrypt (`PASSWORD_BCRYPT` via `password_hash()`). | Rejeição no registro com mensagem explicativa se `length < 6`. Proibição absoluta de armazenamento em texto plano. |
| **RN-03** | **Prevenção de Ataques de Força Bruta (Rate Limiting)** | O sistema monitora as tentativas de login com base no endereço IP do cliente. O limite máximo admitido é de **5 tentativas falhas dentro de uma janela de 10 minutos**. | Bloqueio temporário imediato (HTTP 429) por 10 minutos. Tentativas expiradas com mais de 24h são purgadas automaticamente do banco. |
| **RN-04** | **Proteção de Sessão e Anti-Fixação (Session Fixation)** | Ao autenticar com sucesso ou criar uma nova conta, o identificador de sessão deve ser obrigatoriamente regenerado via `session_regenerate_id(true)`. No logout, as variáveis de sessão são limpas e os cookies de sessão são invalidados no navegador do usuário. | Prevenção ativa de sequestro de sessão de usuários em redes públicas ou compartilhadas. |
| **RN-05** | **Integridade contra CSRF, SQL Injection e XSS** | Operações mutáveis de API (POST/PUT/DELETE) exigem token CSRF validado por `hash_equals()`. Todas as interações com banco de dados devem utilizar exclusivamente consultas preparadas PDO (`Prepared Statements`). Toda saída HTML renderizada deve ser tratada com `htmlspecialchars()`. | Rejeição imediata da requisição com mensagem de token inválido ou erro de validação. |

---

### DOMÍNIO 2: GAMIFICAÇÃO, ECONOMIA DE XP E PROGRESSÃO

| Código | Nome da Regra | Especificação Técnica e Condições de Disparo | Tratamento de Violação / Exceção |
| :--- | :--- | :--- | :--- |
| **RN-06** | **Prática Ilimitada (Extinção do Sistema de Vidas)** | O sistema de "corações" ou "vidas limitadas" é **100% inexistente**. O aluno possui tentativas ilimitadas para resolver qualquer exercício, lição ou simulado sem custos, cronômetros de recarga ou penalizações financeiras. | Erros cometidos pelo aluno não diminuem vidas nem impedem o prosseguimento dos estudos. |
| **RN-07** | **Fórmula de Nível (Leveling Up)** | O nível (`level`) do estudante é calculado matematicamente pela fórmula inteira:<br>`Nível = Math.floor(XP_Total / 100) + 1`<br>A cada 100 XP acumulados na conta, o nível é incrementado automaticamente. O nível inicial é sempre 1. | O nível nunca regride, mesmo se operações futuras concederem débitos em itens cosméticos. |
| **RN-08** | **Recompensa de XP em Lições da Trilha** | A conclusão de uma lição normal concede **+35 XP Base**. A conclusão de uma lição no Modo Chefão concede **+50 XP Base**.<br>Aprovações concedem **Bônus de Precisão** conforme a taxa de acerto:<br>• **100% de acerto:** +25 XP bônus<br>• **80% a 99% de acerto:** +15 XP bônus<br>• **60% a 79% de acerto:** +10 XP bônus | Submissões com taxa de acerto inferior a 60% são consideradas reprovadas: não liberam o avanço na trilha e concedem 0 XP. |
| **RN-09** | **Recompensa de XP em Simulados Cronometrados** | Em simulados completos, cada questão respondida corretamente concede **+3 XP**. Adicionalmente, são concedidos bônus cumulativos de alta performance:<br>• **Acertos ≥ 75%:** +30 XP bônus<br>• **Acertos ≥ 90%:** +40 XP bônus adicional (+70 XP total em bônus). | Simulados entregues com 0 respostas são rejeitados com erro HTTP 400. |
| **RN-10** | **Recompensa de XP em Redações Corrigidas por IA** | O envio e correção completa de uma proposta de redação concede **+100 XP Base**. Conforme a nota alcançada (escala de 0 a 1000), bônus meritocráticos são atribuídos:<br>• **Nota ≥ 900 pontos:** +100 XP bônus (Total: 200 XP)<br>• **Nota 800 a 899:** +60 XP bônus (Total: 160 XP)<br>• **Nota 700 a 799:** +40 XP bônus (Total: 140 XP)<br>• **Nota 600 a 699:** +20 XP bônus (Total: 120 XP)<br>• **Nota < 600:** +0 XP bônus (Total: 100 XP base) | Redações com menos de 150 caracteres ou arquivos inválidos são rejeitados antes de pontuar. |
| **RN-11** | **Regra da Ofensiva Diária (Daily Streak)** | A ofensiva contabiliza dias civis consecutivos em que o estudante concluiu pelo menos **1 atividade pedagógica válida** (lição com ≥ 60%, simulado ou redação):<br>1. Se a última atividade foi hoje (`last_active_date = today`): mantém a sequência (estado aceso).<br>2. Se foi ontem (`last_active_date = today - 1`): incrementa a sequência em **+1 dia**.<br>3. Se foi anterior a ontem ou nula: a ofensiva é **resetada para 0** e reiniciada em **1** após a nova atividade. | A sincronização da quebra da ofensiva é validada em tempo real no login e na consulta do perfil. |
| **RN-12** | **Meta Diária de XP e Desafios Diários** | Todo estudante possui uma **Meta Diária fixada em 50 XP**. A atividade diária é persistida na tabela `user_daily_activity`. O sistema monitora:<br>• Desafio 1: Concluir no mínimo 1 lição no dia civil.<br>• Desafio 2: Atingir ou ultrapassar 50 XP acumulados no dia civil. | Atingir 100% da meta diária ativa a animação comemorativa no HUD do jogador. |

---

### DOMÍNIO 3: TRILHA DE APRENDIZAGEM & MODO CHEFÃO (BOSS CHALLENGE)

| Código | Nome da Regra | Especificação Técnica e Condições de Disparo | Tratamento de Violação / Exceção |
| :--- | :--- | :--- | :--- |
| **RN-13** | **Hierarquia Curricular da Trilha** | O conteúdo didático é organizado rigorosamente em: **Matéria (`subjects`) → Unidades (`units`) → Lições (`lessons`) → Questões (`questions`)**.<br>A navegação visual utiliza curvas Bézier SVG (Snake Path 3D). O status visual é dividido em:<br>• Concluído (fase com `user_progress` registrado);<br>• Atual / Liberada (primeira fase pendente com pulso animado);<br>• Bloqueada (fases posteriores). | O acesso direto a lições não liberadas pode ser restrito ou guiado pelo fluxo de aprendizado. |
| **RN-14** | **Regras do Modo Chefão (Boss Challenge)** | Ao final de cada unidade pedagógica, o estudante enfrenta um desafio de Chefão:<br>• O teste é composto por exatamente **5 questões de alta dificuldade** (`is_boss = 1` ou `difficulty = 'difícil'`);<br>• Durante a realização da prova, a resolução comentada e a confirmação imediata de gabarito são **estritamente ocultadas**;<br>• O feedback pedagógico e o cálculo de bônus de XP só são revelados na tela de relatório final;<br>• A seleção de questões utiliza variantes dinâmicas baseadas no volume de acertos do aluno: `bossVariant = floor(totalAnswered / 50)`. | Acesso bloqueado caso o aluno não tenha completado as etapas pré-requisito da unidade. |
| **RN-15** | **Filtro Anti-Repetição (Janela de 5 Dias Civis)** | O algoritmo de sorteio de questões para lições e revisões exclui automaticamente do sorteio qualquer questão que o aluno tenha **respondido com acerto (`is_correct = 1`) nos últimos 5 dias civis** (`answered_at >= NOW() - 5 DAYS`). | **Mecanismo de Fallback:** Caso o estudante tenha acertado todas as questões disponíveis daquela lição nos últimos 5 dias, o banco recarrega as questões do tópico para evitar travamento da lição. |

---

### DOMÍNIO 4: CORREÇÃO DE REDAÇÃO COM IA (GEMINI 2.5 PRO) & OCR

| Código | Nome da Regra | Especificação Técnica e Condições de Disparo | Tratamento de Violação / Exceção |
| :--- | :--- | :--- | :--- |
| **RN-16** | **Condições de Submissão de Redação** | A submissão pode ocorrer por texto digitado ou por anexo de arquivo.<br>• Texto digitado: **mínimo obrigatório de 150 caracteres**;<br>• Anexo: aceita arquivos nos formatos `JPG`, `JPEG`, `PNG`, `WEBP` ou `PDF`, com **tamanho máximo de 15 MB**.<br>O sistema valida a extensão e o tipo MIME real via `mime_content_type`. | Rejeição imediata caso o texto tenha menos de 150 caracteres ou o arquivo ultrapasse 15 MB ou possua formato não suportado. |
| **RN-17** | **Transcrição Óptica (OCR) de Folhas Manuscritas** | Para envios por imagem ou PDF manuscrito, o motor multimodal Google Gemini realiza a leitura óptica da caligrafia, transcrevendo o texto integral no campo `texto_lido` antes de iniciar o processo de correção pedagógica.<br>A estética visual da letra não diminui a nota do candidato, exceto quando trechos forem 100% ilegíveis. | Se a imagem estiver corrompida ou ilegível, o corretor sinaliza a impossibilidade de leitura no relatório. |
| **RN-18** | **Matriz de Correção Oficial por Banca Examinadora** | A nota final da redação varia de **0 a 1000 pontos**, dividida em 5 competências (C1 a C5) com pontuação de 0 a 200 pontos cada.<br>As diretrizes variam conforme a banca selecionada:<br>• **ENEM (INEP):** C1 (Norma culta), C2 (Compreensão da proposta e repertório sociocultural), C3 (Projeto de texto e argumentação), C4 (Coesão textual e conectivos), C5 (Proposta de intervenção social com os **5 elementos obrigatórios: Agente, Ação, Meio/Modo, Efeito e Detalhamento**).<br>• **VUNESP (UNESP):** Pergunta provocativa do tema deve ser respondida com tese consistente. Proposta de intervenção social **NÃO é obrigatória** (privilegia-se conclusão reflexiva dialética). Título recomendado.<br>• **FUVEST (USP):** Ensaio dissertativo de **alta densidade filosófica e reflexiva**. Repúdio a clichês e fórmulas prontas. **Título OBRIGATÓRIO**. Proposta de intervenção NÃO é exigida.<br>• **UNICAMP:** Atendimento rigoroso ao propósito da proposta e interlocução autoral; leitura crítica da coletânea sem cópia.<br>• **UERJ:** Ensaio com reflexão sociopolítica e humanística articulada à obra literária indicada. | Se o aluno zerar alguma das competências essenciais (ex: fuga total ao tema), a nota final é rebaixada conforme as normas da banca. |
| **RN-19** | **Detecção de Inteligência Artificial e Autenticidade** | Toda redação submetida é submetida a uma análise heurística e linguística para verificação de autoria humana ou geração sintética por LLM:<br>• Avaliação de simetria mecânica entre parágrafos;<br>• Uso repetitivo de conectivos clichês de abertura ("Em primeiro plano...", "Ademais...", "Em suma...");<br>• Repertório genérico sem fundamentação concreta;<br>• Atribui uma métrica `probabilidade_ia` de 0% a 100% com justificativa técnica transparente e alerta de plágio (`alerta_plagio`). | Se `probabilidade_ia ≥ 80%`, a redação exibe badge de alerta de autenticidade no painel de resultados. |

---

### DOMÍNIO 5: SISTEMA DE SIMULADOS CRONOMETRADOS

| Código | Nome da Regra | Especificação Técnica e Condições de Disparo | Tratamento de Violação / Exceção |
| :--- | :--- | :--- | :--- |
| **RN-20** | **Bancas e Modalidades de Simulado** | O sistema oferece simulados segmentados por exame oficial: **ENEM**, **FUVEST**, **UNICAMP**, **UNESP** e **MISTO** (questões sorteadas de todas as bancas). Quantidade padrão: 30 questões por simulado. | O sistema filtra a coluna `exam_source` para garantir correspondência temática. |
| **RN-21** | **Distribuição Proporcional por Nível de Dificuldade** | Todo simulado gerado deve obedecer estritamente à curva de dificuldade pedagógica:<br>• **30% Fácil**<br>• **45% Médio**<br>• **25% Difícil**<br>A ordenação das questões entregue ao estudante segue o fluxo gradativo: **Fácil → Médio → Difícil**. | Caso uma banca específica não contenha questões suficientes em uma das faixas, o motor preenche as vagas remanescentes com questões de bancas equivalentes via fallback aleatório. |
| **RN-22** | **Cálculo de Desempenho e Histórico do Simulado** | A nota do simulado é calculada por: `Score = round((Acertos / Total) * 100)`. Cada resposta enviada é gravada em `user_answers`. O resultado consolidado é gravado em `simulados` com registro de tempo gasto (`time_spent_sec`), XP recebido e separação estatística de acertos por matéria e por nível de dificuldade. | Tentativa de submissão sem respostas gera código de erro e impede o cômputo de XP. |

---

### DOMÍNIO 6: LOJA DE COSMÉTICOS, AVATARES E MOLDURAS

| Código | Nome da Regra | Especificação Técnica e Condições de Disparo | Tratamento de Violação / Exceção |
| :--- | :--- | :--- | :--- |
| **RN-23** | **Desbloqueio de Avatares por XP Acumulado** | Os avatares de perfil são desbloqueados conforme o total de XP acumulado na conta do estudante (o XP não é gasto/debitado, funciona como limiar de prestígio):<br>• `bi-person-circle` (Estudante Padrão): **0 XP**<br>• `bi-backpack` (Mochileiro Focado): **20 XP**<br>• `bi-mortarboard` (Formando Vestibulando): **50 XP**<br>• `bi-rocket-takeoff` (Foguete da Aprovação): **100 XP**<br>• `bi-lightning-charge` (Mago do Conhecimento): **200 XP**<br>• `bi-award` (Campeão de Simulados): **350 XP**<br>• `bi-gem` (Diamante Medicina): **500 XP**<br>• `bi-incognito` (Mestre Misterioso): **750 XP**<br>• `bi-crown` (Rei da Aprovação): **1000 XP**<br>• `bi-emoji-smile-fill` (Mascote Hipopótamo Lendário): **1500 XP** (equipa também a imagem oficial do mascote). | Requisições via API para equipar um avatar sem o XP mínimo exigido são bloqueadas com erro HTTP 400. |
| **RN-24** | **Desbloqueio de Molduras de Avatar (Frames)** | As molduras iluminadas e animadas do avatar seguem tabela de XP acumulado:<br>• `frame-indigo` (Índigo Padrão): **0 XP**<br>• `frame-emerald` (Esmeralda): **100 XP**<br>• `frame-cyan` (Azul Ciano): **300 XP**<br>• `frame-purple` (Roxo Místico): **500 XP**<br>• `frame-rose` (Rosa Neon): **750 XP**<br>• `frame-rainbow` (Arco-Íris Lendária Animada): **1000 XP**<br>• `frame-gold` (Dourada Suprema com Pulso): **1500 XP** | Tentativa de equipar moldura bloqueada é rejeitada informando o montante de XP faltante. |
| **RN-25** | **Upload de Foto de Perfil Customizada** | O usuário pode realizar upload de foto própria. Formatos permitidos: `JPG`, `JPEG`, `PNG`, `WEBP`, `GIF`. Limite máximo de **5 MB**.<br>A validação checa `getimagesize()` e o cabeçalho binário anti-spoofing via `finfo`. Ao salvar um novo arquivo, a imagem anterior do usuário é excluída do disco. | Arquivos inválidos, corrompidos ou executáveis mascarados são rejeitados imediatamente. |

---

### DOMÍNIO 7: SOCIAL, REDE DE AMIGOS E LEADERBOARD

| Código | Nome da Regra | Especificação Técnica e Condições de Disparo | Tratamento de Violação / Exceção |
| :--- | :--- | :--- | :--- |
| **RN-26** | **Mecânica de Solicitação e Vínculo de Amizade** | Estudantes podem se conectar pesquisando por nome ou e-mail (mínimo de 2 caracteres na busca). As solicitações possuem status: `pending` (pendente), `accepted` (aceita) e `rejected` (rejeitada). É estritamente proibido enviar solicitação para si mesmo ou duplicar solicitações existentes. | Bloqueio imediato com aviso amigável na interface em caso de convite duplicado ou auto-solicitação. |
| **RN-27** | **Ranking da Liga de Amigos (Leaderboard)** | A ordenação da tabela de classificação é feita estritamente de forma decrescente por **XP Total Acumulado** (`users.xp DESC`). Em caso de empate, o desempate segue a maior sequência de ofensiva (`streak_days DESC`). Os três primeiros colocados recebem insígnias arcade de pódio (Ouro, Prata e Bronze). | Usuários recém-cadastrados com 0 XP aparecem nas posições de base da tabela. |

---

### DOMÍNIO 8: INTEGRIDADE DO ACERVO E QUALIDADE PEDAGÓGICA

| Código | Nome da Regra | Especificação Técnica e Condições de Disparo | Tratamento de Violação / Exceção |
| :--- | :--- | :--- | :--- |
| **RN-28** | **Estrutura Obrigatória de Questões de Vestibular** | Toda questão cadastrada no banco de dados deve conter obrigatoriamente:<br>• Enunciado contextualizado não vazio;<br>• Exatamente 5 alternativas de resposta (`option_a`, `option_b`, `option_c`, `option_d`, `option_e`);<br>• Gabarito oficial restrito a uma letra minúscula (`'a'`, `'b'`, `'c'`, `'d'`, `'e'`);<br>• Explicação comentada passo a passo (`explanation_text`);<br>• Classificação de dificuldade (`fácil`, `médio`, `difícil`);<br>• Fonte da banca e ano (`exam_source`). | A falta de qualquer uma das 5 opções ou gabarito inválido inviabiliza o cadastro no banco. |

---

## 3. Matriz de Requisitos Funcionais (RF)

| Código | Módulo / Tela | Especificação do Requisito Funcional | Prioridade |
| :--- | :--- | :--- | :--- |
| **RF-01** | Autenticação (`index.php`) | Login com e-mail/senha, registro de novos alunos com senha criptografada, encerramento de sessão e botão de acesso direto para Aluno Demo. | **ALTA** |
| **RF-02** | Trilha Snake Path (`dashboard.php`) | Mapa interativo com nós estilizados 3D conectados por curvas Bézier SVG indicando fases concluídas, fase ativa e fases futuras. | **ALTA** |
| **RF-03** | HUD do Jogador (`dashboard.php`) | Barra superior fixa exibindo Avatar, Moldura, Nível, Barra de Progresso de XP, Ofensiva (Streak) e Desafios Diários. | **ALTA** |
| **RF-04** | Motor de Resolução (`lesson.php`) | Apresentação de questões com 5 alternativas, atalhos de teclado (teclas `1-5` ou `A-E`, `Enter`), som sintético e feedback pedagógico imediato. | **ALTA** |
| **RF-05** | Modo Chefão (`lesson.php?mode=boss`) | Simulado de conclusão de unidade com 5 questões difíceis, gabarito e resolução ocultados até a tela de resultado final. | **ALTA** |
| **RF-06** | Portal de Redações (`redacao.php`) | Catálogo de mais de 500 temas oficiais, divisão por bancas (ENEM, VUNESP, FUVEST, UNICAMP, UERJ, Redigir), folha pautada oficial Letrus com gutter de linhas e modal de textos motivadores. | **ALTA** |
| **RF-07** | Correção com IA & OCR (`api/corrigir_redacao.php`) | Avaliação pedagógica via Gemini 2.5 Pro com análise das 5 competências, transcrição OCR de redações manuscritas (fotos/PDFs) e detector de IA/plágio. | **ALTA** |
| **RF-08** | Painel de Resultados de Redação (`redacao_resultado.php`) | Exibição visual da nota final (0 a 1000), notas e diagnósticos por competência, pontos fortes, oportunidades de melhoria e transcrição lida. | **ALTA** |
| **RF-09** | Simulados Cronometrados (`simulado.php`) | Geração de provas de 30 questões balanceadas por dificuldade, cronômetro regressivo/progressivo e análise de acertos por matéria. | **ALTA** |
| **RF-10** | Loja & Perfil (`profile.php`) | Gerenciamento de foto de perfil, seleção de avatares desbloqueáveis por XP e seleção de molduras com animações CSS/glow. | **MÉDIA** |
| **RF-11** | Ranking & Amigos (`leaderboard.php`) | Tabela de classificação ordenada por XP com busca de colegas, envio de solicitações de amizade e pódio arcade. | **MÉDIA** |
| **RF-12** | Guia de Cursos (`course_guide.php`) | Pesquisa integrada de cursos superiores com filtros de intenção (Notas de corte SISU/ENEM, Existência do curso e Grade Curricular). | **MÉDIA** |

---

## 4. Matriz de Requisitos Não-Funcionais (RNF)

| Código | Categoria | Especificação do Requisito Não-Funcional | Métrica / Padrão |
| :--- | :--- | :--- | :--- |
| **RNF-01** | **Segurança de Senhas** | Todas as senhas de usuários devem ser obrigatoriamente armazenadas com hash criptográfico seguro via `password_hash()` com algoritmo Bcrypt. | `PASSWORD_BCRYPT` nativo PHP 8+ |
| **RNF-02** | **Proteção contra SQLi & XSS** | Todas as interações com o banco de dados devem utilizar consultas preparadas PDO (Prepared Statements). Saídas HTML devem ser tratadas contra XSS. | PDO Prepared Statements & `htmlspecialchars()` |
| **RNF-03** | **Prevenção contra Brute Force** | Controle de requisições de login por endereço IP com armazenamento temporal e bloqueio progressivo. | Máximo 5 tentativas falhas em 10 minutos |
| **RNF-04** | **Desempenho e Carregamento** | Tempo de resposta de APIs e carregamento inicial de telas abaixo de 1,5 segundos em conexões 4G padrão. | Tempo total < 1,5s |
| **RNF-05** | **Sonorização Sintética Nativa** | Os efeitos sonoros (clique, acerto, erro e level up) devem ser gerados via Web Audio API do navegador, sem downloads de arquivos MP3 pesados. | Web Audio API com zero latência |
| **RNF-06** | **Responsividade Mobile-First** | Interface gráfica 100% responsiva para smartphones (320px+), tablets e telas desktop ultrawide. | Tailwind CSS v3 com breakpoints responsivos |
| **RNF-07** | **Integridade Referencial do Banco** | O banco de dados utiliza motor InnoDB com integridade referencial, chaves estrangeiras com `ON DELETE CASCADE` e restrições de unicidade (`UNIQUE KEY`). | MySQL 8.x / MariaDB InnoDB Engine |

---

## 5. Termo de Homologação

Este catálogo reflete com precisão todas as regras de negócio, requisitos funcionais e não-funcionais implementados e validados na arquitetura técnica do **HipoGabarito v3.0 Pro**.
