import os
import sys
from reportlab.lib.pagesizes import A4
from reportlab.lib import colors
from reportlab.platypus import (
    SimpleDocTemplate, Paragraph, Spacer, Table, TableStyle, PageBreak, KeepTogether, HRFlowable
)
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.pdfgen import canvas

class NumberedCanvas(canvas.Canvas):
    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self._saved_page_states = []

    def showPage(self):
        self._saved_page_states.append(dict(self.__dict__))
        self._startPage()

    def save(self):
        num_pages = len(self._saved_page_states)
        for state in self._saved_page_states:
            self.__dict__.update(state)
            self.draw_page_decorations(num_pages)
            super().showPage()
        super().save()

    def draw_page_decorations(self, page_count):
        self.saveState()
        
        slate_500 = colors.HexColor("#64748B")
        slate_300 = colors.HexColor("#CBD5E1")
        indigo_600 = colors.HexColor("#4F46E5")
        
        width, height = A4
        margin = 40
        
        # Header (Pages 2+)
        if self._pageNumber > 1:
            self.setLineWidth(0.75)
            self.setStrokeColor(indigo_600)
            self.line(margin, height - 35, width - margin, height - 35)
            
            self.setFont("Helvetica-Bold", 8)
            self.setFillColor(indigo_600)
            self.drawString(margin, height - 30, "HIPOGABARITO")
            
            self.setFont("Helvetica", 8)
            self.setFillColor(slate_500)
            self.drawString(margin + 75, height - 30, "•  Especificação Exaustiva de Requisitos e Regras de Negócio")
            self.drawRightString(width - margin, height - 30, "Doc. RN/RF/RNF — v3.0 Pro")

        # Footer (All pages)
        self.setLineWidth(0.5)
        self.setStrokeColor(slate_300)
        self.line(margin, 35, width - margin, 35)
        
        self.setFont("Helvetica", 8)
        self.setFillColor(slate_500)
        self.drawString(margin, 24, "HipoGabarito — Catálogo Oficial de Requisitos Funcionais, Não-Funcionais e Regras de Negócio")
        self.drawRightString(width - margin, 24, f"Página {self._pageNumber} de {page_count}")
        
        self.restoreState()

def build_pdf(filename):
    doc = SimpleDocTemplate(
        filename,
        pagesize=A4,
        leftMargin=40,
        rightMargin=40,
        topMargin=45,
        bottomMargin=45
    )
    
    c_primary = colors.HexColor("#4F46E5")     # Indigo 600
    c_dark = colors.HexColor("#0F172A")        # Slate 900
    c_body = colors.HexColor("#334155")        # Slate 700
    c_light_bg = colors.HexColor("#F8FAFC")    # Slate 50
    c_card_bg = colors.HexColor("#F1F5F9")     # Slate 100
    c_border = colors.HexColor("#E2E8F0")      # Slate 200

    styles = getSampleStyleSheet()
    
    title_style = ParagraphStyle(
        'DocTitle',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=18,
        leading=22,
        textColor=c_dark,
        spaceAfter=3
    )
    
    subtitle_style = ParagraphStyle(
        'DocSubtitle',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=10,
        leading=14,
        textColor=c_primary,
        spaceAfter=8
    )
    
    meta_style = ParagraphStyle(
        'DocMeta',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=8,
        leading=11,
        textColor=colors.HexColor("#64748B")
    )
    
    h1_style = ParagraphStyle(
        'Heading1_Custom',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=12,
        leading=16,
        textColor=c_dark,
        spaceBefore=10,
        spaceAfter=4,
        keepWithNext=True
    )

    body_style = ParagraphStyle(
        'Body_Custom',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=8.5,
        leading=12,
        textColor=c_body,
        spaceAfter=4
    )

    table_header_style = ParagraphStyle(
        'TableHeader',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=8,
        leading=10.5,
        textColor=colors.white
    )

    table_cell_style = ParagraphStyle(
        'TableCell',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=7.5,
        leading=10,
        textColor=c_body
    )

    table_cell_bold = ParagraphStyle(
        'TableCellBold',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=7.5,
        leading=10,
        textColor=c_dark
    )

    story = []

    # --- TITLE HEADER ---
    story.append(Paragraph("HIPOGABARITO — ESPECIFICAÇÃO DE REQUISITOS E REGRAS", title_style))
    story.append(Paragraph("Catálogo Completo e Exaustivo de Regras de Negócio (RN), Requisitos Funcionais (RF) e Não-Funcionais (RNF)", subtitle_style))
    
    meta_data = [
        [
            Paragraph("<b>Documento:</b> Matriz Oficial de Regras de Negócio", meta_style),
            Paragraph("<b>Sistema:</b> HipoGabarito v3.0 Pro", meta_style),
            Paragraph("<b>Data:</b> Outubro / 2026", meta_style)
        ],
        [
            Paragraph("<b>Escopo:</b> Engenharia de Software e Gamificação", meta_style),
            Paragraph("<b>Classificação:</b> Especificação Técnica", meta_style),
            Paragraph("<b>Status:</b> Homologado em Produção", meta_style)
        ]
    ]
    t_meta = Table(meta_data, colWidths=[175, 175, 165])
    t_meta.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), c_card_bg),
        ('PADDING', (0,0), (-1,-1), 5),
        ('VALIGN', (0,0), (-1,-1), 'MIDDLE'),
        ('BOX', (0,0), (-1,-1), 0.5, c_border),
    ]))
    story.append(t_meta)
    story.append(Spacer(1, 8))

    # --- SEÇÃO 1: REGRAS DE NEGÓCIO (RN) ---
    story.append(Paragraph("1. Regras de Negócio (RN)", h1_style))
    story.append(HRFlowable(width="100%", thickness=1.5, color=c_primary, spaceBefore=2, spaceAfter=4))
    story.append(Paragraph("As Regras de Negócio definem as condições de funcionamento, restrições e fórmulas que regem a plataforma:", body_style))

    rn_data = [
        [Paragraph("Código", table_header_style), Paragraph("Nome da Regra", table_header_style), Paragraph("Descrição Detalhada e Especificação Técnica", table_header_style), Paragraph("Ação de Violação / Exceção", table_header_style)],
        [
            Paragraph("<b>RN-01</b>", table_cell_bold),
            Paragraph("Perfis de Acesso (RBAC)", table_cell_bold),
            Paragraph("Usuários possuem papel <code>student</code> ou <code>admin</code>. Rotas de resolução, perfil e simulados exigem sessão ativa; rotas de gestão de questões exigem <code>admin</code>.", table_cell_style),
            Paragraph("Acesso não autenticado redireciona para login; não-admin em rotas restritas gera HTTP 403.", table_cell_style)
        ],
        [
            Paragraph("<b>RN-02</b>", table_cell_bold),
            Paragraph("Política de Senhas e Criptografia", table_cell_bold),
            Paragraph("Senhas devem ter no mínimo 6 caracteres e são criptografadas com Bcrypt nativo (<code>PASSWORD_BCRYPT</code>). Proibido texto plano.", table_cell_style),
            Paragraph("Rejeição imediata no cadastro se senha for menor que 6 caracteres.", table_cell_style)
        ],
        [
            Paragraph("<b>RN-03</b>", table_cell_bold),
            Paragraph("Prevenção Brute Force (Rate Limit)", table_cell_bold),
            Paragraph("Máximo de 5 tentativas de login incorretas por endereço IP em janela de 10 minutos.", table_cell_style),
            Paragraph("Bloqueio temporário imediato (HTTP 429) por 10 minutos após 5 erros.", table_cell_style)
        ],
        [
            Paragraph("<b>RN-04</b>", table_cell_bold),
            Paragraph("Prática Ilimitada (Sem Vidas)", table_cell_bold),
            Paragraph("O sistema de vidas/corações é 100% inexistente. O estudante possui tentativas ilimitadas sem bloqueio temporal ou custo.", table_cell_style),
            Paragraph("Nenhuma penalização regressiva de vidas ao errar questões.", table_cell_style)
        ],
        [
            Paragraph("<b>RN-05</b>", table_cell_bold),
            Paragraph("Fórmula de Nível (Leveling Up)", table_cell_bold),
            Paragraph("O nível é calculado matematicamente por: <code>Nível = Math.floor(XP_Total / 100) + 1</code>. A cada 100 XP, o nível é incrementado automaticamente. Nível mínimo é 1.", table_cell_style),
            Paragraph("O nível nunca regride na conta do estudante.", table_cell_style)
        ],
        [
            Paragraph("<b>RN-06</b>", table_cell_bold),
            Paragraph("Recompensa de XP em Lições", table_cell_bold),
            Paragraph("Aprovação requer ≥ 60% de acertos.<br/>• Lição Normal: +35 XP Base<br/>• Modo Chefão: +50 XP Base<br/>• Bônus de Precisão: 100% (+25 XP), 80-99% (+15 XP), 60-79% (+10 XP).", table_cell_style),
            Paragraph("Submissão com taxa < 60% é reprovada, não avança trilha e concede 0 XP.", table_cell_style)
        ],
        [
            Paragraph("<b>RN-07</b>", table_cell_bold),
            Paragraph("Recompensa de XP em Simulados", table_cell_bold),
            Paragraph("Concede +3 XP por questão correta. Bônus por aproveitamento: ≥ 75% (+30 XP bônus) e ≥ 90% (+40 XP adicionais, totalizando +70 XP em bônus).", table_cell_style),
            Paragraph("Simulado sem respostas enviadas é rejeitado pela API.", table_cell_style)
        ],
        [
            Paragraph("<b>RN-08</b>", table_cell_bold),
            Paragraph("Recompensa de XP em Redações", table_cell_bold),
            Paragraph("Submissão válida concede +100 XP Base. Bônus por nota oficial:<br/>• Nota ≥ 900: +100 XP (Total 200)<br/>• Nota 800-899: +60 XP (Total 160)<br/>• Nota 700-799: +40 XP (Total 140)<br/>• Nota 600-699: +20 XP (Total 120)", table_cell_style),
            Paragraph("Redações < 150 caracteres ou inválidas são rejeitadas.", table_cell_style)
        ],
        [
            Paragraph("<b>RN-09</b>", table_cell_bold),
            Paragraph("Ofensiva Diária (Daily Streak)", table_cell_bold),
            Paragraph("Incrementa +1 dia a cada dia civil consecutivo com lição, simulado ou redação concluída. Se o dia anterior for pulado, reseta para 0 no login e volta para 1 na nova atividade.", table_cell_style),
            Paragraph("Requer conclusão em dias civis consecutivos.", table_cell_style)
        ],
        [
            Paragraph("<b>RN-10</b>", table_cell_bold),
            Paragraph("Meta Diária de XP", table_cell_bold),
            Paragraph("Meta fixada em 50 XP/dia. Registrada em <code>user_daily_activity</code> monitorando: Conclusão de Lição e Atingimento de 50 XP.", table_cell_style),
            Paragraph("Atingir a meta desbloqueia estado visual comemorativo no HUD.", table_cell_style)
        ],
        [
            Paragraph("<b>RN-11</b>", table_cell_bold),
            Paragraph("Modo Chefão (Boss Challenge)", table_cell_bold),
            Paragraph("Simulado de final de unidade com 5 questões difíceis. Gabarito instantâneo e resolução são 100% ocultados durante a realização, exibidos apenas no relatório final.", table_cell_style),
            Paragraph("Feedback intermediário bloqueado durante a prova.", table_cell_style)
        ],
        [
            Paragraph("<b>RN-12</b>", table_cell_bold),
            Paragraph("Filtro Anti-Repetição (5 Dias)", table_cell_bold),
            Paragraph("O motor de questões exclui questões respondidas corretamente (<code>is_correct = 1</code>) nos últimos 5 dias civis.", table_cell_style),
            Paragraph("Fallback: Se acertou todas recentemente, recarrega o banco.", table_cell_style)
        ],
        [
            Paragraph("<b>RN-13</b>", table_cell_bold),
            Paragraph("Submissão de Redação & Arquivos", table_cell_bold),
            Paragraph("Texto digitado: mínimo de 150 caracteres. Anexo: fotos (JPG, PNG, WEBP) ou PDF até 15 MB com validação de tipo MIME real.", table_cell_style),
            Paragraph("Rejeição imediata se arquivo > 15 MB ou formato proibido.", table_cell_style)
        ],
        [
            Paragraph("<b>RN-14</b>", table_cell_bold),
            Paragraph("OCR de Manuscrito & Gemini 2.5 Pro", table_cell_bold),
            Paragraph("Envios manuscritos passam por OCR via Google Gemini 2.5 Pro, transcrevendo o texto lido integralmente antes da correção das 5 competências.", table_cell_style),
            Paragraph("Caligrafia ilegível compromete apenas os trechos incompreensíveis.", table_cell_style)
        ],
        [
            Paragraph("<b>RN-15</b>", table_cell_bold),
            Paragraph("Critérios Oficiais por Banca", table_cell_bold),
            Paragraph("ENEM: 5 competências de 0 a 200 (C5 exige proposta com 5 elementos). UNESP/FUVEST: Proposta de intervenção social NÃO é obrigatória; FUVEST exige título; UNESP valoriza tese dialética.", table_cell_style),
            Paragraph("Desvios de estrutura e fuga ao tema penalizam a nota final (0-1000).", table_cell_style)
        ],
        [
            Paragraph("<b>RN-16</b>", table_cell_bold),
            Paragraph("Detecção de IA e Plágio", table_cell_bold),
            Paragraph("Gera índice <code>probabilidade_ia</code> (0-100%) analisando simetria mecânica, conectivos clichês engessados e falta de voz autoral humana.", table_cell_style),
            Paragraph("Alertas visuais de autenticidade são exibidos ao aluno.", table_cell_style)
        ],
        [
            Paragraph("<b>RN-17</b>", table_cell_bold),
            Paragraph("Simulado: Curva de Dificuldade", table_cell_bold),
            Paragraph("Todo simulado cronometrado de 30 questões distribui estritamente: 30% Fácil, 45% Médio e 25% Difícil, ordenados de forma crescente.", table_cell_style),
            Paragraph("Fallback de preenchimento randômico caso falte questões no nível.", table_cell_style)
        ],
        [
            Paragraph("<b>RN-18</b>", table_cell_bold),
            Paragraph("Desbloqueio de Avatares por XP", table_cell_bold),
            Paragraph("Avatares liberados por XP acumulado: Estudante (0), Mochileiro (20), Formando (50), Foguete (100), Mago (200), Campeão (350), Diamante (500), Incógnito (750), Rei (1000) e Mascote Hipopótamo Lendário (1500 XP).", table_cell_style),
            Paragraph("Requisição com XP insuficiente é rejeitada com HTTP 400.", table_cell_style)
        ],
        [
            Paragraph("<b>RN-19</b>", table_cell_bold),
            Paragraph("Desbloqueio de Molduras Animadas", table_cell_bold),
            Paragraph("Molduras por XP: Índigo (0), Esmeralda (100), Ciano (300), Roxo (500), Rosa (750), Arco-Íris Lendária (1000) e Dourada Suprema (1500 XP).", table_cell_style),
            Paragraph("Tentativa sem XP mínimo exigido é bloqueada.", table_cell_style)
        ],
        [
            Paragraph("<b>RN-20</b>", table_cell_bold),
            Paragraph("Upload de Foto de Perfil", table_cell_bold),
            Paragraph("Aceita imagens até 5 MB (JPG, PNG, WEBP, GIF) com validação de cabeçalho binário anti-spoofing via finfo. Exclui a foto antiga do disco ao atualizar.", table_cell_style),
            Paragraph("Arquivos maliciosos ou maiores que 5 MB são descartados.", table_cell_style)
        ],
        [
            Paragraph("<b>RN-21</b>", table_cell_bold),
            Paragraph("Rede de Amigos e Ranking Privado", table_cell_bold),
            Paragraph("Busca por nome/e-mail (mínimo 2 caracteres). Relações: pendente, aceita e recusada. Ranking ordenado por XP Total e desempate por Ofensiva.", table_cell_style),
            Paragraph("Bloqueio de auto-amizade e solicitações duplicadas.", table_cell_style)
        ],
        [
            Paragraph("<b>RN-22</b>", table_cell_bold),
            Paragraph("Estrutura Obrigatória de Questões", table_cell_bold),
            Paragraph("Toda questão deve conter obrigatoriamente 5 alternativas (A a E), enunciado contextualizado, gabarito correto ('a' a 'e') e resolução comentada.", table_cell_style),
            Paragraph("Rejeição imediata no cadastro se faltar qualquer opção.", table_cell_style)
        ],
    ]
    t_rn = Table(rn_data, colWidths=[42, 108, 255, 110])
    t_rn.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), c_primary),
        ('ALIGN', (0,0), (-1,-1), 'LEFT'),
        ('VALIGN', (0,0), (-1,-1), 'TOP'),
        ('GRID', (0,0), (-1,-1), 0.5, c_border),
        ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, c_light_bg]),
        ('PADDING', (0,0), (-1,-1), 4),
    ]))
    story.append(t_rn)

    story.append(Spacer(1, 10))

    # --- SEÇÃO 2: REQUISITOS FUNCIONAIS (RF) ---
    story.append(Paragraph("2. Requisitos Funcionais (RF)", h1_style))
    story.append(HRFlowable(width="100%", thickness=1.5, color=c_primary, spaceBefore=2, spaceAfter=4))
    story.append(Paragraph("Ações, recursos e comportamentos que o sistema fornece aos usuários:", body_style))

    rf_data = [
        [Paragraph("Código", table_header_style), Paragraph("Módulo / Recurso", table_header_style), Paragraph("Especificação do Requisito Funcional", table_header_style), Paragraph("Prioridade", table_header_style)],
        [
            Paragraph("<b>RF-01</b>", table_cell_bold),
            Paragraph("Autenticação e Sessão (`index.php`)", table_cell_bold),
            Paragraph("Login com e-mail/senha, cadastro com hash Bcrypt, logout seguro e botão de acesso direto para Aluno Demo.", table_cell_style),
            Paragraph("<font color='#DC2626'><b>ALTA</b></font>", table_cell_style)
        ],
        [
            Paragraph("<b>RF-02</b>", table_cell_bold),
            Paragraph("Trilha Snake Path (`dashboard.php`)", table_cell_bold),
            Paragraph("Mapa visual com nós 3D conectados por curvas Bézier SVG indicando fases concluídas, fase atual e fases bloqueadas.", table_cell_style),
            Paragraph("<font color='#DC2626'><b>ALTA</b></font>", table_cell_style)
        ],
        [
            Paragraph("<b>RF-03</b>", table_cell_bold),
            Paragraph("HUD do Jogador (`dashboard.php`)", table_cell_bold),
            Paragraph("Barra fixa no topo contendo Avatar com moldura, Nível atual, Barra de XP %, Streak e Desafios Diários.", table_cell_style),
            Paragraph("<font color='#DC2626'><b>ALTA</b></font>", table_cell_style)
        ],
        [
            Paragraph("<b>RF-04</b>", table_cell_bold),
            Paragraph("Motor de Resolução (`lesson.php`)", table_cell_bold),
            Paragraph("Apresentar questões de vestibular (5 opções), feedback imediato, atalhos de teclado (1-5, Enter) e resolução comentada.", table_cell_style),
            Paragraph("<font color='#DC2626'><b>ALTA</b></font>", table_cell_style)
        ],
        [
            Paragraph("<b>RF-05</b>", table_cell_bold),
            Paragraph("Modo Chefão (`lesson.php?mode=boss`)", table_cell_bold),
            Paragraph("Simulado de final de unidade com 5 questões difíceis, gabarito ocultado durante a prova e bônus ampliado de XP.", table_cell_style),
            Paragraph("<font color='#DC2626'><b>ALTA</b></font>", table_cell_style)
        ],
        [
            Paragraph("<b>RF-06</b>", table_cell_bold),
            Paragraph("Portal de Redações (`redacao.php`)", table_cell_bold),
            Paragraph("Catálogo com mais de 500 temas de redação (ENEM, FUVEST, VUNESP, UNICAMP, UERJ e Redigir), folha pautada e modal motivador.", table_cell_style),
            Paragraph("<font color='#DC2626'><b>ALTA</b></font>", table_cell_style)
        ],
        [
            Paragraph("<b>RF-07</b>", table_cell_bold),
            Paragraph("Correção com IA & OCR (`corrigir_redacao.php`)", table_cell_bold),
            Paragraph("Correção com Gemini 2.5 Pro nas 5 competências (0 a 1000 pontos), transcrição de manuscritos e detector de IA/plágio.", table_cell_style),
            Paragraph("<font color='#DC2626'><b>ALTA</b></font>", table_cell_style)
        ],
        [
            Paragraph("<b>RF-08</b>", table_cell_bold),
            Paragraph("Relatório de Redação (`redacao_resultado.php`)", table_cell_bold),
            Paragraph("Exibição visual da nota final, radar de competências, pontos fortes, oportunidades de melhoria e texto lido via OCR.", table_cell_style),
            Paragraph("<font color='#DC2626'><b>ALTA</b></font>", table_cell_style)
        ],
        [
            Paragraph("<b>RF-09</b>", table_cell_bold),
            Paragraph("Simulados Cronometrados (`simulado.php`)", table_cell_bold),
            Paragraph("Execução de simulados de 30 questões balanceadas por dificuldade, cronômetro regressivo e estatísticas por matéria.", table_cell_style),
            Paragraph("<font color='#DC2626'><b>ALTA</b></font>", table_cell_style)
        ],
        [
            Paragraph("<b>RF-10</b>", table_cell_bold),
            Paragraph("Perfil & Cosméticos (`profile.php`)", table_cell_bold),
            Paragraph("Equipar avatares desbloqueados por XP, molduras animadas, upload de foto própria e histórico de conquistas (Badges).", table_cell_style),
            Paragraph("<font color='#0EA5E9'><b>MÉDIA</b></font>", table_cell_style)
        ],
        [
            Paragraph("<b>RF-11</b>", table_cell_bold),
            Paragraph("Ranking de Amigos (`leaderboard.php`)", table_cell_bold),
            Paragraph("Ranking geral e liga privada de amigos ordenados por XP com pódio arcade (1º, 2º e 3º) e gerenciamento de solicitações.", table_cell_style),
            Paragraph("<font color='#0EA5E9'><b>MÉDIA</b></font>", table_cell_style)
        ],
        [
            Paragraph("<b>RF-12</b>", table_cell_bold),
            Paragraph("Guia de Cursos (`course_guide.php`)", table_cell_bold),
            Paragraph("Pesquisa de cursos e universidades brasileiras com filtros por notas de corte SISU/ENEM, existência de curso e grades.", table_cell_style),
            Paragraph("<font color='#0EA5E9'><b>MÉDIA</b></font>", table_cell_style)
        ],
    ]
    t_rf = Table(rf_data, colWidths=[42, 118, 295, 60])
    t_rf.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), c_primary),
        ('ALIGN', (0,0), (-1,-1), 'LEFT'),
        ('VALIGN', (0,0), (-1,-1), 'MIDDLE'),
        ('GRID', (0,0), (-1,-1), 0.5, c_border),
        ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, c_light_bg]),
        ('PADDING', (0,0), (-1,-1), 4),
    ]))
    story.append(t_rf)

    story.append(Spacer(1, 10))

    # --- SEÇÃO 3: REQUISITOS NÃO-FUNCIONAIS (RNF) ---
    story.append(Paragraph("3. Requisitos Não-Funcionais (RNF)", h1_style))
    story.append(HRFlowable(width="100%", thickness=1.5, color=c_primary, spaceBefore=2, spaceAfter=4))
    story.append(Paragraph("Critérios de qualidade, desempenho, segurança e arquitetura do sistema:", body_style))

    rnf_data = [
        [Paragraph("Código", table_header_style), Paragraph("Categoria", table_header_style), Paragraph("Especificação do Requisito Não-Funcional", table_header_style), Paragraph("Métrica / Padrão", table_header_style)],
        [
            Paragraph("<b>RNF-01</b>", table_cell_bold),
            Paragraph("Segurança de Senhas", table_cell_bold),
            Paragraph("Armazenamento seguro de senhas com algoritmo Bcrypt via `password_hash()` nativo do PHP.", table_cell_style),
            Paragraph("PASSWORD_BCRYPT nativo PHP 8+", table_cell_style)
        ],
        [
            Paragraph("<b>RNF-02</b>", table_cell_bold),
            Paragraph("Proteção contra SQLi & XSS", table_cell_bold),
            Paragraph("Consultas preparadas obrigatórias (PDO Prepared Statements) e sanitização de dados com `htmlspecialchars()`.", table_cell_style),
            Paragraph("PDO Prepared Statements & htmlspecialchars", table_cell_style)
        ],
        [
            Paragraph("<b>RNF-03</b>", table_cell_bold),
            Paragraph("Prevenção Brute Force", table_cell_bold),
            Paragraph("Controle de tentativas de login por IP com bloqueio temporário e purga automática de logs antigos.", table_cell_style),
            Paragraph("Máximo 5 falhas em 10 minutos (HTTP 429)", table_cell_style)
        ],
        [
            Paragraph("<b>RNF-04</b>", table_cell_bold),
            Paragraph("Desempenho & Carregamento", table_cell_bold),
            Paragraph("Tempo de resposta de APIs e carregamento inicial de telas inferior a 1,5s em conexões convencionais.", table_cell_style),
            Paragraph("Tempo total < 1,5 segundos", table_cell_style)
        ],
        [
            Paragraph("<b>RNF-05</b>", table_cell_bold),
            Paragraph("Sonorização Sintética", table_cell_bold),
            Paragraph("Efeitos sonoros (clique, acerto, erro, level up) gerados via Web Audio API do navegador sem downloads HTTP extras.", table_cell_style),
            Paragraph("Web Audio API nativa com zero latência", table_cell_style)
        ],
        [
            Paragraph("<b>RNF-06</b>", table_cell_bold),
            Paragraph("Responsividade Mobile-First", table_cell_bold),
            Paragraph("Interface gráfica 100% responsiva para smartphones (320px+), tablets e monitores desktop.", table_cell_style),
            Paragraph("Tailwind CSS v3 com breakpoints responsivos", table_cell_style)
        ],
        [
            Paragraph("<b>RNF-07</b>", table_cell_bold),
            Paragraph("Integridade Referencial do BD", table_cell_bold),
            Paragraph("Banco de dados relacional com motor InnoDB, transações ACID, chaves estrangeiras com CASCADE e índices únicos.", table_cell_style),
            Paragraph("MySQL 8.x / MariaDB InnoDB Engine", table_cell_style)
        ],
    ]
    t_rnf = Table(rnf_data, colWidths=[45, 105, 245, 120])
    t_rnf.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), c_dark),
        ('ALIGN', (0,0), (-1,-1), 'LEFT'),
        ('VALIGN', (0,0), (-1,-1), 'TOP'),
        ('GRID', (0,0), (-1,-1), 0.5, c_border),
        ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, c_light_bg]),
        ('PADDING', (0,0), (-1,-1), 4),
    ]))
    story.append(t_rnf)

    story.append(Spacer(1, 10))
    story.append(Paragraph("<b>Termo de Homologação:</b> Este catálogo contém a totalidade oficial dos Requisitos Funcionais, Não-Funcionais e Regras de Negócio do sistema HipoGabarito v3.0 Pro.", ParagraphStyle('Approve', parent=body_style, fontName='Helvetica-Oblique', alignment=1, textColor=colors.HexColor("#475569"))))

    # Build PDF
    doc.build(story, canvasmaker=NumberedCanvas)

if __name__ == "__main__":
    out_dir = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
    target_pdf = os.path.join(out_dir, "Novas_Regras_e_Requisitos_HipoGabarito.pdf")
    build_pdf(target_pdf)
    print(f"PDF criado com sucesso em: {target_pdf}")
