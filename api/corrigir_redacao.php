<?php
/**
 * API DE CORREÇÃO DE REDAÇÃO COM IA (GEMINI 2.5 PRO) — HIPOGABARITO
 * Avaliação oficial das 5 Competências do ENEM (0 a 1000 pontos)
 * OCR de redação manuscrita / foto, Detector de IA e atribuição de XP gamificado.
 */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/gemini.php';

// 1. Autenticação
$userId = $_SESSION['user_id'] ?? 0;
if (!$userId) {
    echo json_encode(['success' => false, 'message' => 'Sessão expirada. Faça login novamente para corrigir.']);
    exit;
}

// 2. Proteção CSRF
$csrfToken = $_POST['csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
if (!validateCsrfToken($csrfToken)) {
    echo json_encode(['success' => false, 'message' => 'Token de segurança inválido. Recarregue a página e tente novamente.']);
    exit;
}

// Permitir tempo para processamento do Gemini 2.5 Pro e OCR
set_time_limit(180);

// 3. Obter dados enviados
$temaId = filter_input(INPUT_POST, 'tema_id', FILTER_VALIDATE_INT);
$temaCustom = trim($_POST['tema_custom'] ?? '');
$tipoEnvio = trim($_POST['tipo_envio'] ?? 'texto');
$textoDigitado = trim($_POST['texto_redacao'] ?? '');
$instrucaoExtra = trim($_POST['instrucoes_extras'] ?? '');

// 4. Resolver Título, Banca e Informações do Tema
$tituloTema = 'Tema Livre';
$banca = trim($_POST['banca'] ?? 'ENEM');
$descricaoTema = '';
$textosMotivadores = '';
$orientacoesBanca = '';
$eixoTematico = 'Cidadania & Sociedade';

if ($temaId) {
    $stmtTema = $pdo->prepare("SELECT titulo, descricao, textos_motivadores, banca, eixo_tematico, orientacoes_especificas FROM redacao_temas WHERE id = ?");
    $stmtTema->execute([$temaId]);
    $temaInfo = $stmtTema->fetch();
    if ($temaInfo) {
        $tituloTema = $temaInfo['titulo'];
        $banca = $temaInfo['banca'] ?? $banca;
        $descricaoTema = $temaInfo['descricao'] ?? '';
        $textosMotivadores = $temaInfo['textos_motivadores'] ?? '';
        $orientacoesBanca = $temaInfo['orientacoes_especificas'] ?? '';
        $eixoTematico = $temaInfo['eixo_tematico'] ?? $eixoTematico;
    }
} elseif (!empty($temaCustom)) {
    $tituloTema = $temaCustom;
}

// 5. Validar conteúdo e arquivos
$arquivoSalvoRelativo = null;
$inlineFileData = null;
$temConteudo = false;
$textoOriginalBanco = '';

if ($tipoEnvio === 'arquivo' && isset($_FILES['arquivo_redacao']) && $_FILES['arquivo_redacao']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['arquivo_redacao'];
    
    // Limite de 15MB para fotos e scans
    if ($file['size'] > 15 * 1024 * 1024) {
        echo json_encode(['success' => false, 'message' => 'O arquivo enviado é muito pesado (máximo 15MB).']);
        exit;
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];
    if (!in_array($ext, $allowedExts)) {
        echo json_encode(['success' => false, 'message' => 'Formato não suportado. Envie uma foto JPG, PNG, WEBP ou arquivo PDF da sua redação.']);
        exit;
    }

    $mime = mime_content_type($file['tmp_name']);
    $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'application/pdf'];
    if (!in_array($mime, $allowedMimes)) {
        echo json_encode(['success' => false, 'message' => 'Tipo de arquivo não permitido por segurança.']);
        exit;
    }

    // Salvar arquivo físico
    $uploadDir = __DIR__ . '/../uploads/redacoes/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $filename = 'redacao_' . $userId . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $targetPath = $uploadDir . $filename;
    $arquivoSalvoRelativo = 'uploads/redacoes/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        echo json_encode(['success' => false, 'message' => 'Não foi possível salvar o arquivo da redação no servidor.']);
        exit;
    }

    $inlineFileData = [
        'mimeType' => $mime,
        'data'     => base64_encode(file_get_contents($targetPath))
    ];
    $temConteudo = true;
    $textoOriginalBanco = '[Redação enviada por Anexo: ' . $file['name'] . ']';

} elseif (!empty($textoDigitado)) {
    if (mb_strlen($textoDigitado) < 150) {
        echo json_encode(['success' => false, 'message' => 'Sua redação está muito curta (mínimo de 150 caracteres para uma avaliação pedagógica válida).']);
        exit;
    }
    $temConteudo = true;
    $textoOriginalBanco = $textoDigitado;
}

if (!$temConteudo) {
    echo json_encode(['success' => false, 'message' => 'Você precisa digitar o texto da redação ou anexar uma foto da sua folha de resposta.']);
    exit;
}

// 6. Construir Diretrizes Específicas por Banca (ENEM, UNESP, FUVEST, UNICAMP, UERJ)
$blocoBanca = '';
if ($banca === 'UNESP') {
    $blocoBanca = "BANCA EXAMINADORA: VUNESP (UNESP)\n"
        . "DIRETRIZES DA VUNESP:\n"
        . "- A proposta da VUNESP geralmente apresenta uma pergunta reflexiva/provocativa. O candidato DEVE responder à questão com tese firme e coerente.\n"
        . "- A proposta de intervenção social NÃO É OBRIGATÓRIA na VUNESP. Valorize a conclusão reflexiva, a síntese dialética e o fechamento crítico da tese.\n"
        . "- Título: Recomendado/valorizado.\n"
        . "- Critérios de Nota (escala 0 a 200 cada):\n"
        . "  * C1: Domínio da norma culta formal, clareza sintática e precisão vocabular;\n"
        . "  * C2: Abordagem completa do tema e diálogo autoral com a coletânea (sem cópia);\n"
        . "  * C3: Consistência argumentativa, projeto de texto e raciocínio lógico (sem contradição);\n"
        . "  * C4: Coesão textual e variedade de conectivos articuladores;\n"
        . "  * C5: Fechamento da tese e maturidade conclusiva.\n";
} elseif ($banca === 'FUVEST') {
    $blocoBanca = "BANCA EXAMINADORA: FUVEST (USP)\n"
        . "DIRETRIZES DA FUVEST:\n"
        . "- A FUVEST exige texto de ALTA DENSIDADE reflexiva, filosófica e abstrata. Repudie clichês, slogans e fórmulas prontas de cursinho.\n"
        . "- Título: OBRIGATÓRIO na FUVEST (avalie a criatividade e a relação com o texto).\n"
        . "- Proposta de intervenção: NÃO É EXIGIDA. A conclusão deve ser analítica, filosófica e amarrar a reflexão.\n"
        . "- Critérios de Nota (escala 0 a 200 cada):\n"
        . "  * C1: Expressão linguística, precisão vocabular de alto nível e correção gramatical;\n"
        . "  * C2: Desenvolvimento temático profundo, reflexão crítica e densidade de ideias;\n"
        . "  * C3: Estrutura do projeto de texto, coerência e progressão temática sólida;\n"
        . "  * C4: Coesão, variedade de recursos articuladores e fluidez;\n"
        . "  * C5: Título autoral e conclusão analítica/filosófica consistente.\n";
} elseif ($banca === 'UNICAMP') {
    $blocoBanca = "BANCA EXAMINADORA: COMVEST (UNICAMP)\n"
        . "DIRETRIZES DA UNICAMP:\n"
        . "- Cumprimento rigoroso do propósito da proposta e interlocução autoral;\n"
        . "- Leitura crítica da coletânea com apropriação inteligente (sem cópia e sem tangenciamento);\n"
        . "- Critérios de Nota (escala 0 a 200 cada):\n"
        . "  * C1: Adequação à norma culta e clareza de registro;\n"
        . "  * C2: Atendimento à proposta e diálogo crítico com os textos motivadores;\n"
        . "  * C3: Densidade argumentativa, justificativas consistentes e voz autoral;\n"
        . "  * C4: Articulação lógica e recursos de coesão;\n"
        . "  * C5: Conclusão consistente e síntese do ponto de vista.\n";
} elseif ($banca === 'UERJ') {
    $blocoBanca = "BANCA EXAMINADORA: UERJ\n"
        . "DIRETRIZES DA UERJ:\n"
        . "- Reflexão sociopolítica e filosófica madura;\n"
        . "- Diálogo conceitual com as problemáticas humanas ou com a obra indicada;\n"
        . "- Critérios de Nota (escala 0 a 200 cada):\n"
        . "  * C1: Recursos expressivos, norma culta e variedade vocabular;\n"
        . "  * C2: Abordagem temática consistente e reflexão humanística;\n"
        . "  * C3: Argumentação crítica e projeto de texto maduro;\n"
        . "  * C4: Coesão e fluidez discursiva;\n"
        . "  * C5: Fechamento reflexivo e impacto conclusivo.\n";
} elseif ($banca === 'REDIGIR') {
    $blocoBanca = "BANCA EXAMINADORA: PLATAFORMA REDIGIR (PADRÃO ENEM & VESTIBULARES)\n"
        . "DIRETRIZES DA PLATAFORMA REDIGIR:\n"
        . "- Avaliação rigorosa inspirada nos critérios de excelência e trilhas adaptativas da Plataforma Redigir;\n"
        . "- Leitura crítica da coletânea com apropriação autoral produtiva (sem cópia literal);\n"
        . "- Critérios de Nota (escala 0 a 200 cada, somando 1000 pontos):\n"
        . "  * C1: Domínio da norma culta formal e correção gramatical;\n"
        . "  * C2: Compreensão da proposta, repertório sociocultural e atendimento ao gênero textual;\n"
        . "  * C3: Projeto de texto estratégico, consistência argumentativa e autoria;\n"
        . "  * C4: Coesão referencial e sequencial, variedade de conectivos;\n"
        . "  * C5: Proposta de intervenção social detalhada (ou síntese conclusiva coerente com a banca).\n";
} else {
    $blocoBanca = "BANCA EXAMINADORA: ENEM (INEP)\n"
        . "MATRIZ OFICIAL ENEM (5 COMPETÊNCIAS OFICIAIS):\n"
        . "- C1: Domínio da norma culta da língua escrita formal (desvios gramaticais, pontuação, crase, concordância e regência);\n"
        . "- C2: Compreensão da proposta, aplicação de repertório sociocultural legitimado e tipologia dissertativa;\n"
        . "- C3: Projeto de texto estratégico, coerência e solidez dos argumentos;\n"
        . "- C4: Coesão inter e intraparágrafos e repertório diversificado de conectivos;\n"
        . "- C5: Proposta de intervenção completa com os 5 elementos (Agente, Ação, Meio/Modo, Efeito e Detalhamento).\n";
}

$prompt = <<<PROMPT
Você é o Corretor de Redações Oficial de Elite da plataforma HipoGabarito, especialista absoluto nas bancas de vestibulares do Brasil (ENEM, FUVEST, UNESP, UNICAMP e UERJ).

SUA MISSÃO:
Avaliar minuciosamente a redação do estudante com extremo rigor pedagógico, clareza, empatia e assertividade, fornecendo notas e justificativas específicas para a banca: {$banca}.

📝 BANCA: {$banca}
📝 PROPOSTA / TEMA: "{$tituloTema}"
{$descricaoTema}{$blocoMotivadores}

{$orientacoesBanca}
{$instrucaoExtra}

----------------------------------------------------------------------
{$blocoBanca}

DETECÇÃO DE IA E AUTENTICIDADE:
Examine a escrita para identificar se o texto aparenta ter sido gerado por ferramentas de Inteligência Artificial:
- Simetria artificial excessiva nos parágrafos e frases;
- Conectivos mecânicos e repetitivos de introdução de parágrafo ("Em primeiro plano...", "Ademais...", "Em suma...");
- Repertório vago e genérico sem fundamentação real;
- Falta de voz autoral e imperfeições naturais da escrita humana de estudantes.
Atribua probabilidade_ia de 0 a 100 e explique a justificativa de forma técnica e transparente.

PROCEDIMENTO OBRIGATÓRIO PARA ANEXOS (IMAGEM / PDF / MANUSCRITO):
Se um arquivo anexo foi enviado:
1. Faça um escaneamento óptico (OCR) detalhado da caligrafia e transcreva TODO o texto lido no campo "texto_lido", preservando os parágrafos originais.
2. Realize a avaliação pedagógica com base no texto transcrito, sem penalizar desvios causados apenas pela estética da letra.

SAÍDA ESTRITAMENTE EM JSON VÁLIDO (SEM BLOCOS MARKDOWN, SEM TEXTO ADICIONAL):
{
  "texto_lido": "Transcrição integral do texto do aluno (ou o texto digitado)",
  "notas_competencias": {
    "c1": 160,
    "c2": 200,
    "c3": 160,
    "c4": 160,
    "c5": 200
  },
  "nota_final": 880,
  "analise_por_competencia": {
    "c1": "Análise detalhada apontando desvios gramaticais pontuais e como melhorar...",
    "c2": "Análise sobre adequação ao tema, tipologia textual e qualidade do repertório sociocultural...",
    "c3": "Análise sobre o projeto de texto, coerência e solidez dos argumentos...",
    "c4": "Análise sobre conectivos, coesão inter e intraparágrafos...",
    "c5": "Análise da proposta de intervenção, listando quais dos 5 elementos (Agente, Ação, Meio, Efeito, Detalhamento) foram cumpridos e quais faltaram..."
  },
  "pontos_fortes": [
    "Destaque positivo 1 da redação",
    "Destaque positivo 2 da redação"
  ],
  "pontos_fracos": [
    "Ponto que precisa de atenção prioritária 1",
    "Ponto que precisa de atenção prioritária 2"
  ],
  "relatorio_melhorias": [
    "Passo prático 1 para subir a pontuação",
    "Passo prático 2 para subir a pontuação"
  ],
  "materiais_estudo": [
    "Dica de estudo específica",
    "Tópico gramatical ou filosófico recomendado para revisão"
  ],
  "autenticidade": {
    "probabilidade_ia": 10,
    "alerta_plagio": false,
    "justificativa_ia": "Explicação técnica sobre a naturalidade da autoria humana ou indícios de IA."
  }
}
PROMPT;

// 7. Chamada à Inteligência Artificial (Gemini 2.5 Pro)
$geminiResponse = callGeminiForEssay($prompt, $inlineFileData);

if (!$geminiResponse['success']) {
    $errMessage = $geminiResponse['message'] ?? 'Não foi possível concluir a correção com a IA.';
    $errType = $geminiResponse['error_type'] ?? 'unknown';
    
    echo json_encode([
        'success'    => false,
        'error_type' => $errType,
        'http_code'  => $geminiResponse['http_code'] ?? 0,
        'message'    => $errMessage
    ]);
    exit;
}

$relatorio = $geminiResponse['data'];

// 8. Normalização e Validação dos Valores Retornados
$c1 = max(0, min(200, (int)($relatorio['notas_competencias']['c1'] ?? 0)));
$c2 = max(0, min(200, (int)($relatorio['notas_competencias']['c2'] ?? 0)));
$c3 = max(0, min(200, (int)($relatorio['notas_competencias']['c3'] ?? 0)));
$c4 = max(0, min(200, (int)($relatorio['notas_competencias']['c4'] ?? 0)));
$c5 = max(0, min(200, (int)($relatorio['notas_competencias']['c5'] ?? 0)));

$notaFinal = $c1 + $c2 + $c3 + $c4 + $c5;
$relatorio['nota_final'] = $notaFinal;

$probabilidadeIa = max(0, min(100, (int)($relatorio['autenticidade']['probabilidade_ia'] ?? 0)));
$justificativaIa = $relatorio['autenticidade']['justificativa_ia'] ?? '';
$alertaPlagio    = !empty($relatorio['autenticidade']['alerta_plagio']) ? 1 : 0;

// Atualizar o texto salvo caso tenha sido lido via OCR
$textoFinalSalvar = $textoOriginalBanco;
if (!empty($relatorio['texto_lido']) && $tipoEnvio === 'arquivo') {
    $textoFinalSalvar = trim($relatorio['texto_lido']);
}

// 9. Cálculo Gamificado de Recompensa de XP (HipoGabarito)
// Base: 100 XP por redação completa
// Bônus proporcional à qualidade:
// 900+ -> +100 XP (Total 200 XP)
// 800+ -> +60 XP  (Total 160 XP)
// 700+ -> +40 XP  (Total 140 XP)
// 600+ -> +20 XP  (Total 120 XP)
$xpGanho = 100;
if ($notaFinal >= 900) {
    $xpGanho += 100;
} elseif ($notaFinal >= 800) {
    $xpGanho += 60;
} elseif ($notaFinal >= 700) {
    $xpGanho += 40;
} elseif ($notaFinal >= 600) {
    $xpGanho += 20;
}

try {
    $pdo->beginTransaction();

    // Inserir registro da redação
    $stmtIns = $pdo->prepare("
        INSERT INTO redacoes (
            user_id, titulo, tema_id, banca, texto_redacao, arquivo_path, tipo_envio,
            relatorio_json, nota_final, nota_c1, nota_c2, nota_c3, nota_c4, nota_c5,
            probabilidade_ia, justificativa_ia, alerta_plagio, xp_ganho, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");

    $stmtIns->execute([
        $userId,
        $tituloTema,
        $temaId ?: null,
        $banca,
        $textoFinalSalvar,
        $arquivoSalvoRelativo,
        $tipoEnvio,
        json_encode($relatorio, JSON_UNESCAPED_UNICODE),
        $notaFinal,
        $c1,
        $c2,
        $c3,
        $c4,
        $c5,
        $probabilidadeIa,
        $justificativaIa,
        $alertaPlagio,
        $xpGanho
    ]);

    $redacaoId = $pdo->lastInsertId();

    // 10. Atualizar XP do Aluno e verificar se subiu de nível
    $stmtUser = $pdo->prepare("SELECT xp, level FROM users WHERE id = ?");
    $stmtUser->execute([$userId]);
    $userData = $stmtUser->fetch();
    $novoXp = ($userData['xp'] ?? 0) + $xpGanho;
    $novoNivel = max(1, floor($novoXp / 100) + 1);

    $stmtUpUser = $pdo->prepare("UPDATE users SET xp = ?, level = ? WHERE id = ?");
    $stmtUpUser->execute([$novoXp, $novoNivel, $userId]);

    // 11. Incrementar / Manter Ofensiva Diária (Streak)
    recordUserActivityStreak($pdo, $userId);

    // 12. Atualizar tabela de atividade diária
    $hoje = date('Y-m-d');
    $stmtActivity = $pdo->prepare("
        INSERT INTO user_daily_activity (user_id, activity_date, xp_earned, lessons_completed)
        VALUES (?, ?, ?, 1)
        ON DUPLICATE KEY UPDATE 
            xp_earned = xp_earned + VALUES(xp_earned),
            lessons_completed = lessons_completed + 1
    ");
    $stmtActivity->execute([$userId, $hoje, $xpGanho]);

    $pdo->commit();

    echo json_encode([
        'success'      => true,
        'redacao_id'   => $redacaoId,
        'nota_final'   => $notaFinal,
        'xp_ganho'     => $xpGanho,
        'redirect_url' => 'redacao_resultado.php?id=' . $redacaoId,
        'message'      => "Redação corrigida com sucesso! Você conquistou +{$xpGanho} XP!"
    ]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao registrar os dados da correção no banco de dados: ' . $e->getMessage()
    ]);
}
