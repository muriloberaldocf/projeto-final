import os
import sys
from reportlab.lib.pagesizes import A4
from reportlab.lib import colors
from reportlab.platypus import (
    SimpleDocTemplate, Paragraph, Spacer, Table, TableStyle, HRFlowable
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
            self.drawString(margin + 75, height - 34, "•  Matriz de Análise SWOT (FOFA)")
            self.drawRightString(width - margin, height - 34, "Versão 2.0")

        # Footer (All pages)
        self.setLineWidth(0.5)
        self.setStrokeColor(slate_300)
        self.line(margin, 45, width - margin, 45)
        
        self.setFont("Helvetica", 8)
        self.setFillColor(slate_500)
        self.drawString(margin, 32, "HipoGabarito — Matriz Exclusiva de Análise SWOT (Forças, Fraquezas, Oportunidades e Ameaças)")
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
    c_border = colors.HexColor("#CBD5E1")      # Slate 300

    # SWOT Palette
    c_strength_bg = colors.HexColor("#ECFDF5")  # Emerald 50
    c_strength_hdr = colors.HexColor("#059669") # Emerald 600
    c_strength_bdr = colors.HexColor("#A7F3D0") # Emerald 200

    c_weakness_bg = colors.HexColor("#FFFBEB")  # Amber 50
    c_weakness_hdr = colors.HexColor("#D97706") # Amber 600
    c_weakness_bdr = colors.HexColor("#FDE68A") # Amber 200

    c_opportunity_bg = colors.HexColor("#EFF6FF")# Blue 50
    c_opportunity_hdr = colors.HexColor("#2563EB")# Blue 600
    c_opportunity_bdr = colors.HexColor("#BFDBFE")# Blue 200

    c_threat_bg = colors.HexColor("#FEF2F2")     # Red 50
    c_threat_hdr = colors.HexColor("#DC2626")    # Red 600
    c_threat_bdr = colors.HexColor("#FECACA")    # Red 200

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

    box_hdr_strength = ParagraphStyle(
        'HdrStrength',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=12,
        leading=15,
        textColor=colors.white,
        alignment=0
    )

    box_hdr_weakness = ParagraphStyle(
        'HdrWeakness',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=12,
        leading=15,
        textColor=colors.white,
        alignment=0
    )

    box_hdr_opportunity = ParagraphStyle(
        'HdrOpportunity',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=12,
        leading=15,
        textColor=colors.white,
        alignment=0
    )

    box_hdr_threat = ParagraphStyle(
        'HdrThreat',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=12,
        leading=15,
        textColor=colors.white,
        alignment=0
    )

    bullet_style = ParagraphStyle(
        'Bullet_SWOT',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=8.5,
        leading=12.5,
        textColor=c_body,
        leftIndent=10,
        firstLineIndent=-6,
        spaceAfter=4
    )

    story = []

    # --- HEADER ---
    story.append(Paragraph("HIPOGABARITO — ANÁLISE SWOT (FOFA)", title_style))
    story.append(Paragraph("Matriz de Forças, Fraquezas, Oportunidades e Ameaças do Projeto", subtitle_style))
    
    # Meta Box
    meta_data = [
        [
            Paragraph("<b>Projeto:</b> HipoGabarito (Vestibulares)", meta_style),
            Paragraph("<b>Data:</b> Agosto / 2026", meta_style),
            Paragraph("<b>Escopo:</b> Matriz SWOT Exclusiva", meta_style)
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
    story.append(Spacer(1, 14))

    # --- QUADRANTE 1: FORÇAS & FRAQUEZAS (INTERNO) ---
    story.append(Paragraph("<b>1. AMBIENTE INTERNO (FORÇAS E FRAQUEZAS)</b>", ParagraphStyle('SubSection', parent=styles['Normal'], fontName='Helvetica-Bold', fontSize=10, leading=13, textColor=c_dark, spaceAfter=6)))

    col_w = 237

    # Forças Text Content
    s_content = [
        Paragraph("• <b>Prática Ilimitada Sem Vidas:</b> Remoção total do sistema de corações; o aluno estuda sem bloqueio por erro ou punição regressiva.", bullet_style),
        Paragraph("• <b>Gamificação Meritocrática:</b> Distribuição de XP por dificuldade (+35/+50 XP base) e bônus por precisão (+25, +15, +5 XP).", bullet_style),
        Paragraph("• <b>Ofensiva Diária (Streak):</b> Mecânica de incentivo à constância diária com reset automático em caso de falta de estudo.", bullet_style),
        Paragraph("• <b>Acervo de Questões Reais (ENEM/Vestibulares):</b> 844+ questões autênticas com 5 alternativas (A a E) e resoluções pedagógicas.", bullet_style),
        Paragraph("• <b>Algoritmo Anti-Repetição & Modo Chefão:</b> Filtro de 5 dias civis e ocultação de gabarito para impedir memorização passiva.", bullet_style),
        Paragraph("• <b>Arquitetura Leve & Web Audio API:</b> Sonorização 100% sintetizada no navegador via áudio digital sem arquivos MP3 pesados.", bullet_style),
        Paragraph("• <b>Usabilidade com Atalhos de Teclado:</b> Navegação ultra-rápida via teclas <code>1-5</code>, <code>A-E</code> e <code>Enter</code>.", bullet_style)
    ]

    # Fraquezas Text Content
    w_content = [
        Paragraph("• <b>Dependência de Conexão à Internet:</b> Ausência de modo offline para resolver lições ou simulados em locais sem sinal.", bullet_style),
        Paragraph("• <b>Dependência de Navegador (Web App):</b> Falta de aplicativo nativo publicado diretamente na Google Play Store e Apple App Store.", bullet_style),
        Paragraph("• <b>Demanda por Curadoria Contínua:</b> Necessidade de manutenção e atualização constante de questões e gabaritos por professores.", bullet_style),
        Paragraph("• <b>Ausência de Simulado com Cronômetro Global:</b> Falta de modo simulado com cronômetro contínuo das 5h30min oficiais do ENEM.", bullet_style),
        Paragraph("• <b>Dependência de Infraestrutura Centralizada:</b> Servidor web e banco de dados únicos que exigem escalabilidade em picos.", bullet_style)
    ]

    t_internal = Table(
        [
            [Paragraph("💪 FORÇAS (STRENGTHS)", box_hdr_strength), Paragraph("⚠️ FRAQUEZAS (WEAKNESSES)", box_hdr_weakness)],
            [s_content, w_content]
        ],
        colWidths=[col_w, col_w]
    )
    t_internal.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (0,0), c_strength_hdr),
        ('BACKGROUND', (1,0), (1,0), c_weakness_hdr),
        ('BACKGROUND', (0,1), (0,1), c_strength_bg),
        ('BACKGROUND', (1,1), (1,1), c_weakness_bg),
        ('VALIGN', (0,0), (-1,-1), 'TOP'),
        ('GRID', (0,0), (-1,-1), 0.75, c_border),
        ('PADDING', (0,0), (-1,-1), 8),
    ]))
    story.append(t_internal)
    story.append(Spacer(1, 14))

    # --- QUADRANTE 2: OPORTUNIDADES & AMEAÇAS (EXTERNO) ---
    story.append(Paragraph("<b>2. AMBIENTE EXTERNO (OPORTUNIDADES E AMEAÇAS)</b>", ParagraphStyle('SubSection', parent=styles['Normal'], fontName='Helvetica-Bold', fontSize=10, leading=13, textColor=c_dark, spaceAfter=6)))

    # Oportunidades Text Content
    o_content = [
        Paragraph("• <b>Mercado de Vestibulandos em Expansão:</b> Milhões de candidatos anuais no ENEM, FUVEST, UNICAMP, VUNESP e SENAI buscando alternativas modernas.", bullet_style),
        Paragraph("• <b>Modelo B2B para Escolas e Cursinhos:</b> Licenciamento do painel administrativo para instituições acompanharem o progresso de turmas.", bullet_style),
        Paragraph("• <b>Integração com IA Generativa:</b> Explicações personalizadas adaptadas ao nível de dúvida do aluno e geração adaptativa de dicas.", bullet_style),
        Paragraph("• <b>Orientação de Carreira com Guia de Cursos:</b> Cruzamento automático da taxa de acerto do estudante com pesos SISU e notas de corte.", bullet_style),
        Paragraph("• <b>Transformação em PWA / Mobile App:</b> Empacotamento em PWA / Capacitor para distribuição nativa nas lojas móveis.", bullet_style)
    ]

    # Ameaças Text Content
    t_content = [
        Paragraph("• <b>Concorrência Consolidada:</b> Plataformas tradicionais de questões com grandes bancos e alto investimento em marketing.", bullet_style),
        Paragraph("• <b>Mudanças nos Editais dos Exames:</b> Alterações nas matrizes de referência do Novo ENEM e vestibulares exigindo reestruturação.", bullet_style),
        Paragraph("• <b>Distração com Entretenimento Digital:</b> Risco de dispersão do estudante para redes sociais e jogos não educacionais.", bullet_style),
        Paragraph("• <b>Sobrecarga em Períodos de Simulado:</b> Picos de acessos simultâneos em vésperas de provas exigindo alta capacidade de servidor.", bullet_style)
    ]

    t_external = Table(
        [
            [Paragraph("🚀 OPORTUNIDADES (OPPORTUNITIES)", box_hdr_opportunity), Paragraph("🛡️ AMEAÇAS (THREATS)", box_hdr_threat)],
            [o_content, t_content]
        ],
        colWidths=[col_w, col_w]
    )
    t_external.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (0,0), c_opportunity_hdr),
        ('BACKGROUND', (1,0), (1,0), c_threat_hdr),
        ('BACKGROUND', (0,1), (0,1), c_opportunity_bg),
        ('BACKGROUND', (1,1), (1,1), c_threat_bg),
        ('VALIGN', (0,0), (-1,-1), 'TOP'),
        ('GRID', (0,0), (-1,-1), 0.75, c_border),
        ('PADDING', (0,0), (-1,-1), 8),
    ]))
    story.append(t_external)

    story.append(Spacer(1, 14))
    story.append(Paragraph("<b>Matriz SWOT HipoGabarito Versão 2.0 Pro — Homologada para Análise Estratégica.</b>", ParagraphStyle('Approve', parent=styles['Normal'], fontName='Helvetica-Oblique', fontSize=8.5, alignment=1, textColor=colors.HexColor("#475569"))))

    # Build PDF
    doc.build(story, canvasmaker=NumberedCanvas)

if __name__ == "__main__":
    out_dir = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
    target_pdf = os.path.join(out_dir, "Analise_SWOT_HipoGabarito.pdf")
    build_pdf(target_pdf)
    print(f"PDF criado com sucesso em: {target_pdf}")
