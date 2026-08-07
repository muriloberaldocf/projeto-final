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
        margin = 54
        
        # Header (Pages 2+)
        if self._pageNumber > 1:
            self.setLineWidth(0.75)
            self.setStrokeColor(indigo_600)
            self.line(margin, height - 40, width - margin, height - 40)
            
            self.setFont("Helvetica-Bold", 8)
            self.setFillColor(indigo_600)
            self.drawString(margin, height - 34, "HIPOGABARITO")
            
            self.setFont("Helvetica", 8)
            self.setFillColor(slate_500)
            self.drawString(margin + 75, height - 34, "•  Especificação Exaustiva de Requisitos e Regras de Negócio")
            self.drawRightString(width - margin, height - 34, "Doc. RN/RF/RNF — v2.0")

        # Footer (All pages)
        self.setLineWidth(0.5)
        self.setStrokeColor(slate_300)
        self.line(margin, 45, width - margin, 45)
        
        self.setFont("Helvetica", 8)
        self.setFillColor(slate_500)
        self.drawString(margin, 32, "HipoGabarito — Catálogo Oficial de Requisitos Funcionais, Não-Funcionais e Regras de Negócio")
        self.drawRightString(width - margin, 32, f"Página {self._pageNumber} de {page_count}")
        
        self.restoreState()

def build_pdf(filename):
    doc = SimpleDocTemplate(
        filename,
        pagesize=A4,
        leftMargin=54,
        rightMargin=54,
        topMargin=54,
        bottomMargin=54
    )
    
    # Palette
    c_primary = colors.HexColor("#4F46E5")     # Indigo 600
    c_dark = colors.HexColor("#0F172A")        # Slate 900
    c_body = colors.HexColor("#334155")        # Slate 700
    c_light_bg = colors.HexColor("#F8FAFC")    # Slate 50
    c_card_bg = colors.HexColor("#F1F5F9")     # Slate 100
    c_border = colors.HexColor("#E2E8F0")      # Slate 200
    c_red = colors.HexColor("#DC2626")         # Red 600

    styles = getSampleStyleSheet()
    
    # Custom styles
    title_style = ParagraphStyle(
        'DocTitle',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=20,
        leading=24,
        textColor=c_dark,
        spaceAfter=4
    )
    
    subtitle_style = ParagraphStyle(
        'DocSubtitle',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=11,
        leading=15,
        textColor=c_primary,
        spaceAfter=12
    )
    
    meta_style = ParagraphStyle(
        'DocMeta',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=8.5,
        leading=12,
        textColor=colors.HexColor("#64748B")
    )
    
    h1_style = ParagraphStyle(
        'Heading1_Custom',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=13,
        leading=17,
        textColor=c_dark,
        spaceBefore=14,
        spaceAfter=6,
        keepWithNext=True
    )

    h2_style = ParagraphStyle(
        'Heading2_Custom',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=10,
        leading=14,
        textColor=c_primary,
        spaceBefore=8,
        spaceAfter=3,
        keepWithNext=True
    )
    
    body_style = ParagraphStyle(
        'Body_Custom',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=9,
        leading=13.5,
        textColor=c_body,
        spaceAfter=5
    )

    bullet_style = ParagraphStyle(
        'Bullet_Custom',
        parent=body_style,
        leftIndent=12,
        firstLineIndent=-8,
        spaceAfter=3.5
    )

    table_header_style = ParagraphStyle(
        'TableHeader',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=8.5,
        leading=11.5,
        textColor=colors.white
    )

    table_cell_style = ParagraphStyle(
        'TableCell',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=8,
        leading=11,
        textColor=c_body
    )

    table_cell_bold = ParagraphStyle(
        'TableCellBold',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=8,
        leading=11,
        textColor=c_dark
    )

    story = []

    # --- TITLE HEADER ---
    story.append(Paragraph("HIPOGABARITO — ESPECIFICAÇÃO DE REQUISITOS E REGRAS", title_style))
    story.append(Paragraph("Catálogo Completo e Exaustivo de Regras de Negócio (RN), Requisitos Funcionais (RF) e Não-Funcionais (RNF)", subtitle_style))
    
    # Meta Box Table
    meta_data = [
        [
            Paragraph("<b>Documento:</b> Matriz de Regras e Requisitos", meta_style),
            Paragraph("<b>Sistema:</b> HipoGabarito v2.0 Pro", meta_style),
            Paragraph("<b>Data:</b> Agosto / 2026", meta_style)
        ],
        [
            Paragraph("<b>Escopo:</b> Regras de Negócio (RN), RF e RNF", meta_style),
            Paragraph("<b>Classificação:</b> Especificação Técnica", meta_style),
            Paragraph("<b>Status:</b> Homologado", meta_style)
        ]
    ]
    t_meta = Table(meta_data, colWidths=[170, 160, 157])
    t_meta.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), c_card_bg),
        ('PADDING', (0,0), (-1,-1), 6),
        ('VALIGN', (0,0), (-1,-1), 'MIDDLE'),
        ('BOX', (0,0), (-1,-1), 0.5, c_border),
    ]))
    story.append(t_meta)
    story.append(Spacer(1, 10))

    # --- SEÇÃO 1: REGRAS DE NEGÓCIO (RN) ---
    story.append(Paragraph("1. Regras de Negócio (RN)", h1_style))
    story.append(HRFlowable(width="100%", thickness=1.5, color=c_primary, spaceBefore=2, spaceAfter=6))
    
    story.append(Paragraph("As Regras de Negócio definem as condições de funcionamento, restrições e fórmulas de cálculo que regem a plataforma HipoGabarito:", body_style))

    rn_data = [
        [Paragraph("Código", table_header_style), Paragraph("Nome da Regra", table_header_style), Paragraph("Descrição Detalhada e Especificação Técnica", table_header_style), Paragraph("Ação de Violação / Exceção", table_header_style)],
        [
            Paragraph("<b>RN-01</b>", table_cell_bold),
            Paragraph("Prática Ilimitada (Sem Vidas)", table_cell_bold),
            Paragraph("O sistema de vidas/corações é 100% inexistente. O estudante possui tentativas ilimitadas em qualquer lição ou simulado sem bloqueio temporal ou custo financeiro.", table_cell_style),
            Paragraph("Nenhuma punição regressiva é aplicada ao errar.", table_cell_style)
        ],
        [
            Paragraph("<b>RN-02</b>", table_cell_bold),
            Paragraph("Cálculo de XP Base", table_cell_bold),
            Paragraph("Cada conclusão de Lição Normal concede <b>+35 XP Base</b>. Conclusões no Modo Chefão (Boss Challenge) concedem <b>+50 XP Base</b>.", table_cell_style),
            Paragraph("Não concede XP em submissões vazias ou inválidas.", table_cell_style)
        ],
        [
            Paragraph("<b>RN-03</b>", table_cell_bold),
            Paragraph("Bônus por Precisão de Acertos", table_cell_bold),
            Paragraph("Adicional de XP conforme a taxa de acerto na lição:<br/>"
                      "• <b>100% de acerto:</b> +25 XP bônus<br/>"
                      "• <b>80% a 99% de acerto:</b> +15 XP bônus<br/>"
                      "• <b>60% a 79% de acerto:</b> +5 XP bônus<br/>"
                      "• <b>< 60% de acerto:</b> +0 XP bônus", table_cell_style),
            Paragraph("Taxas abaixo de 60% recebem apenas o XP base da fase.", table_cell_style)
        ],
        [
            Paragraph("<b>RN-04</b>", table_cell_bold),
            Paragraph("Fórmula de Nível (Leveling Up)", table_cell_bold),
            Paragraph("O nível do estudante é calculado pela fórmula inteira:<br/><code>Nível = Math.floor(XP_Total / 100) + 1</code>.<br/>A cada 100 XP acumulados, o nível é incrementado automaticamente.", table_cell_style),
            Paragraph("O nível mínimo da conta é sempre 1.", table_cell_style)
        ],
        [
            Paragraph("<b>RN-05</b>", table_cell_bold),
            Paragraph("Ofensiva Diária (Daily Streak)", table_cell_bold),
            Paragraph("A ofensiva incrementa +1 dia a cada dia civil consecutivo com pelo menos 1 lição concluída. O 1º dia de atividade inicia em 1.", table_cell_style),
            Paragraph("Requer conclusão em dias civis consecutivos.", table_cell_style)
        ],
        [
            Paragraph("<b>RN-06</b>", table_cell_bold),
            Paragraph("Reset da Ofensiva por Inatividade", table_cell_bold),
            Paragraph("Se a última data de atividade (`last_active_date`) for anterior ao dia civil de ontem (`date < today - 1 day`), o contador de ofensiva é resetado para 1.", table_cell_style),
            Paragraph("A ofensiva volta para 1 no próximo login/exercício.", table_cell_style)
        ],
        [
            Paragraph("<b>RN-07</b>", table_cell_bold),
            Paragraph("Modo Chefão - Ocultação de Resolução", table_cell_bold),
            Paragraph("No Modo Chefão (Boss Challenge), a resolução passo a passo e o gabarito instantâneo são totalmente ocultados durante o teste, sendo exibidos somente ao final.", table_cell_style),
            Paragraph("Feedback intermediário bloqueado durante a prova.", table_cell_style)
        ],
        [
            Paragraph("<b>RN-08</b>", table_cell_bold),
            Paragraph("Filtro Anti-Repetição (5 Dias Civis)", table_cell_bold),
            Paragraph("O motor de busca de questões exclui automaticamente do sorteio qualquer questão que o usuário tenha respondido corretamente (`is_correct = 1`) nos últimos 5 dias civis.", table_cell_style),
            Paragraph("Fallback: Se respondeu a todas no período, recarrega o banco.", table_cell_style)
        ],
        [
            Paragraph("<b>RN-09</b>", table_cell_bold),
            Paragraph("Desbloqueio de Avatares por XP", table_cell_bold),
            Paragraph("Requisito mínimo de XP para equipar avatares:<br/>"
                      "• `bi-person-circle`: 0 XP | `bi-backpack`: 20 XP<br/>"
                      "• `bi-mortarboard`: 50 XP | `bi-rocket-takeoff`: 100 XP<br/>"
                      "• `bi-lightning-charge`: 200 XP | `bi-award`: 350 XP<br/>"
                      "• `bi-gem`: 500 XP | `bi-incognito`: 750 XP<br/>"
                      "• `bi-crown`: 1000 XP | `bi-emoji-smile-fill` (Mascote): 1500 XP", table_cell_style),
            Paragraph("Requisição com XP insuficiente é rejeitada pela API.", table_cell_style)
        ],
        [
            Paragraph("<b>RN-10</b>", table_cell_bold),
            Paragraph("Perfis de Acesso (Student / Admin)", table_cell_bold),
            Paragraph("Usuários possuem papel `student` ou `admin`. Apenas o papel `admin` tem autorização para criar, editar ou excluir questões e gerenciar o acervo da plataforma.", table_cell_style),
            Paragraph("Acesso negado para `student` em rotas administrativas.", table_cell_style)
        ],
        [
            Paragraph("<b>RN-11</b>", table_cell_bold),
            Paragraph("Estrutura Obrigatória de Questões", table_cell_bold),
            Paragraph("Toda questão deve conter obrigatoriamente 5 alternativas (A, B, C, D, E), texto de enunciado não vazio e indicação do gabarito correto (`a`, `b`, `c`, `d` ou `e`).", table_cell_style),
            Paragraph("Rejeição imediata no cadastro se faltar 1 opção.", table_cell_style)
        ],
    ]
    t_rn = Table(rn_data, colWidths=[45, 115, 232, 95])
    t_rn.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), c_primary),
        ('ALIGN', (0,0), (-1,-1), 'LEFT'),
        ('VALIGN', (0,0), (-1,-1), 'TOP'),
        ('GRID', (0,0), (-1,-1), 0.5, c_border),
        ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, c_light_bg]),
        ('PADDING', (0,0), (-1,-1), 5),
    ]))
    story.append(t_rn)

    story.append(Spacer(1, 10))

    # --- SEÇÃO 2: REQUISITOS FUNCIONAIS (RF) ---
    story.append(Paragraph("2. Requisitos Funcionais (RF)", h1_style))
    story.append(HRFlowable(width="100%", thickness=1.5, color=c_primary, spaceBefore=2, spaceAfter=6))
    
    story.append(Paragraph("Os Requisitos Funcionais descrevem as ações, recursos e comportamentos que o sistema deve fornecer aos usuários:", body_style))

    rf_data = [
        [Paragraph("Código", table_header_style), Paragraph("Módulo / Recurso", table_header_style), Paragraph("Especificação do Requisito Funcional", table_header_style), Paragraph("Prioridade", table_header_style)],
        [
            Paragraph("<b>RF-01</b>", table_cell_bold),
            Paragraph("Autenticação de Usuários", table_cell_bold),
            Paragraph("Permitir login com e-mail/senha, cadastro de novos alunos, encerramento de sessão (logout) e botão de acesso direto como Aluno Demo.", table_cell_style),
            Paragraph("<font color='#10B981'><b>ALTA</b></font>", table_cell_style)
        ],
        [
            Paragraph("<b>RF-02</b>", table_cell_bold),
            Paragraph("Trilha Snake Path (`dashboard.php`)", table_cell_bold),
            Paragraph("Exibir mapa visual com nós 3D conectados por curvas Bézier SVG em tempo real, indicando graficamente fases concluídas, atual e bloqueadas.", table_cell_style),
            Paragraph("<font color='#10B981'><b>ALTA</b></font>", table_cell_style)
        ],
        [
            Paragraph("<b>RF-03</b>", table_cell_bold),
            Paragraph("HUD do Jogador", table_cell_bold),
            Paragraph("Exibir barra superior fixa no dashboard contendo Avatar, Nível atual, Barra de XP com %, Streak de dias consecutivos e total de XP acumulado.", table_cell_style),
            Paragraph("<font color='#10B981'><b>ALTA</b></font>", table_cell_style)
        ],
        [
            Paragraph("<b>RF-04</b>", table_cell_bold),
            Paragraph("Motor de Resolução (`lesson.php`)", table_cell_bold),
            Paragraph("Apresentar questões de vestibular com 5 alternativas (A a E), feedback pedagógico imediato e explicação comentada passo a passo.", table_cell_style),
            Paragraph("<font color='#10B981'><b>ALTA</b></font>", table_cell_style)
        ],
        [
            Paragraph("<b>RF-05</b>", table_cell_bold),
            Paragraph("Atalhos de Teclado", table_cell_bold),
            Paragraph("Permitir seleção de alternativas via teclas `1-5` ou `A-E` e submissão/avanço via tecla `Enter` para agilizar a resolução de exercícios.", table_cell_style),
            Paragraph("<font color='#0EA5E9'><b>MÉDIA</b></font>", table_cell_style)
        ],
        [
            Paragraph("<b>RF-06</b>", table_cell_bold),
            Paragraph("Modo Chefão (Boss Challenge)", table_cell_bold),
            Paragraph("Executar simulado de final de unidade com 5 questões inéditas/difíceis, tempo/desafio ampliado e relatório final com cálculo de XP.", table_cell_style),
            Paragraph("<font color='#10B981'><b>ALTA</b></font>", table_cell_style)
        ],
        [
            Paragraph("<b>RF-07</b>", table_cell_bold),
            Paragraph("Painel do Professor (`admin.php`)", table_cell_bold),
            Paragraph("Oferecer interface administrativa para cadastro, edição, exclusão e listagem de questões com suporte a rich text e formulador de opções.", table_cell_style),
            Paragraph("<font color='#10B981'><b>ALTA</b></font>", table_cell_style)
        ],
        [
            Paragraph("<b>RF-08</b>", table_cell_bold),
            Paragraph("Importação de Questões em Lote", table_cell_bold),
            Paragraph("Permitir a ingestão automatizada de simulados oficiais (ENEM 2025/2026, FUVEST, UNICAMP, VUNESP e SENAI) vinculados às lições.", table_cell_style),
            Paragraph("<font color='#0EA5E9'><b>MÉDIA</b></font>", table_cell_style)
        ],
        [
            Paragraph("<b>RF-09</b>", table_cell_bold),
            Paragraph("Perfil & Loja (`profile.php`)", table_cell_bold),
            Paragraph("Permitir equipar avatares desbloqueados conforme o XP acumulado, exibir conquistas (Badges) conquistadas e resumo estatístico de acertos.", table_cell_style),
            Paragraph("<font color='#0EA5E9'><b>MÉDIA</b></font>", table_cell_style)
        ],
        [
            Paragraph("<b>RF-10</b>", table_cell_bold),
            Paragraph("Leaderboard (`leaderboard.php`)", table_cell_bold),
            Paragraph("Exibir ranking geral ordenado por XP total acumulado com pódio estilizado estilo arcade para os 1º, 2º e 3º colocados da semana.", table_cell_style),
            Paragraph("<font color='#0EA5E9'><b>MÉDIA</b></font>", table_cell_style)
        ],
        [
            Paragraph("<b>RF-11</b>", table_cell_bold),
            Paragraph("Guia de Cursos (`course_guide.php`)", table_cell_bold),
            Paragraph("Disponibilizar catálogo pesquisável de cursos universitários com filtros por instituição, notas de corte SISU/ENEM e pesos por matéria.", table_cell_style),
            Paragraph("<font color='#0EA5E9'><b>MÉDIA</b></font>", table_cell_style)
        ],
    ]
    t_rf = Table(rf_data, colWidths=[45, 120, 263, 59])
    t_rf.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), c_primary),
        ('ALIGN', (0,0), (-1,-1), 'LEFT'),
        ('VALIGN', (0,0), (-1,-1), 'MIDDLE'),
        ('GRID', (0,0), (-1,-1), 0.5, c_border),
        ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, c_light_bg]),
        ('PADDING', (0,0), (-1,-1), 5),
    ]))
    story.append(t_rf)

    story.append(Spacer(1, 10))

    # --- SEÇÃO 3: REQUISITOS NÃO-FUNCIONAIS (RNF) ---
    story.append(Paragraph("3. Requisitos Não-Funcionais (RNF)", h1_style))
    story.append(HRFlowable(width="100%", thickness=1.5, color=c_primary, spaceBefore=2, spaceAfter=6))
    
    story.append(Paragraph("Os Requisitos Não-Funcionais estabelecem os critérios de qualidade, desempenho, segurança e arquitetura do sistema:", body_style))

    rnf_data = [
        [Paragraph("Código", table_header_style), Paragraph("Categoria", table_header_style), Paragraph("Especificação do Requisito Não-Funcional", table_header_style), Paragraph("Métrica / Padrão", table_header_style)],
        [
            Paragraph("<b>RNF-01</b>", table_cell_bold),
            Paragraph("Segurança de Senhas", table_cell_bold),
            Paragraph("Todas as senhas de usuários devem ser obrigatoriamente armazenadas com hash criptográfico seguro via `password_hash()` com algoritmo Bcrypt.", table_cell_style),
            Paragraph("PASSWORD_BCRYPT nativo PHP 8+", table_cell_style)
        ],
        [
            Paragraph("<b>RNF-02</b>", table_cell_bold),
            Paragraph("Proteção contra SQLi & XSS", table_cell_bold),
            Paragraph("Todas as interações com o banco de dados devem utilizar consultas preparadas PDO (Prepared Statements). Saídas HTML devem ser tratadas contra XSS.", table_cell_style),
            Paragraph("PDO Prepared Statements & `htmlspecialchars`", table_cell_style)
        ],
        [
            Paragraph("<b>RNF-03</b>", table_cell_bold),
            Paragraph("Desempenho & Carregamento", table_cell_bold),
            Paragraph("O tempo de resposta e carregamento inicial de qualquer página deve ser inferior a 1,5 segundos em conexões 4G convencionais.", table_cell_style),
            Paragraph("Tempo total < 1,5 segundos", table_cell_style)
        ],
        [
            Paragraph("<b>RNF-04</b>", table_cell_bold),
            Paragraph("Sonorização Sintética", table_cell_bold),
            Paragraph("Os efeitos sonoros (clique, acerto, erro e level up) devem ser gerados via Web Audio API nativa do navegador, sem dependência de download de arquivos MP3 externos.", table_cell_style),
            Paragraph("Web Audio API sem requisições HTTP adicionais", table_cell_style)
        ],
        [
            Paragraph("<b>RNF-05</b>", table_cell_bold),
            Paragraph("Responsividade Mobile-First", table_cell_bold),
            Paragraph("A interface gráfica deve ser 100% adaptável e funcional em telas de smartphones (320px+), tablets e monitores desktop.", table_cell_style),
            Paragraph("Tailwind CSS v3 com breakpoints responsivos", table_cell_style)
        ],
        [
            Paragraph("<b>RNF-06</b>", table_cell_bold),
            Paragraph("Integridade Referencial do Banco", table_cell_bold),
            Paragraph("O banco de dados deve utilizar o motor InnoDB com suporte a transações, chaves estrangeiras com ação `ON DELETE CASCADE` e restrições de unicidade (`UNIQUE KEY`).", table_cell_style),
            Paragraph("MySQL 8.x / MariaDB InnoDB Engine", table_cell_style)
        ],
    ]
    t_rnf = Table(rnf_data, colWidths=[48, 95, 234, 110])
    t_rnf.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), c_dark),
        ('ALIGN', (0,0), (-1,-1), 'LEFT'),
        ('VALIGN', (0,0), (-1,-1), 'TOP'),
        ('GRID', (0,0), (-1,-1), 0.5, c_border),
        ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, c_light_bg]),
        ('PADDING', (0,0), (-1,-1), 5),
    ]))
    story.append(t_rnf)

    story.append(Spacer(1, 14))
    story.append(Paragraph("<b>Termo de Homologação:</b> Este catálogo contém a totalidade dos Requisitos Funcionais, Não-Funcionais e Regras de Negócio do aplicativo HipoGabarito.", ParagraphStyle('Approve', parent=body_style, fontName='Helvetica-Oblique', alignment=1, textColor=colors.HexColor("#475569"))))

    # Build PDF
    doc.build(story, canvasmaker=NumberedCanvas)

if __name__ == "__main__":
    out_dir = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
    target_pdf = os.path.join(out_dir, "Novas_Regras_e_Requisitos_HipoGabarito.pdf")
    build_pdf(target_pdf)
    print(f"PDF criado com sucesso em: {target_pdf}")
