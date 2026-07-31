import os
from fpdf import FPDF

class ProjectSummaryPDF(FPDF):
    def header(self):
        # Top banner decorativo
        self.set_fill_color(79, 70, 229) # Indigo #4f46e5
        self.rect(0, 0, 210, 24, 'F')
        
        self.set_font('Helvetica', 'B', 14)
        self.set_text_color(255, 255, 255)
        self.cell(0, 6, ' APROVAQUEST - RESUMO EXECUTIVO DO PROJETO', 0, 1, 'L')
        self.set_font('Helvetica', '', 8.5)
        self.cell(0, 4, ' Plataforma Gamificada de Pratica para Vestibulares (ENEM, FUVEST, UNICAMP, VUNESP, SENAI)', 0, 1, 'L')
        self.ln(10)

    def footer(self):
        self.set_y(-15)
        self.set_font('Helvetica', 'I', 8)
        self.set_text_color(148, 163, 184) # Slate-400
        self.cell(0, 10, f'AprovaQuest Pro (2026) - Pagina {self.page_no()}/{{nb}}', 0, 0, 'C')

    def chapter_title(self, title):
        self.set_font('Helvetica', 'B', 12)
        self.set_text_color(49, 46, 129) # Indigo 900
        self.set_fill_color(238, 242, 255) # Indigo 50
        self.cell(0, 7, f'  {title}', 0, 1, 'L', fill=True)
        self.ln(3)

    def bullet_point(self, label, text):
        self.set_font('Helvetica', 'B', 9)
        self.set_text_color(30, 41, 59)
        self.write(5, f"- {label}: ")
        self.set_font('Helvetica', '', 9)
        self.set_text_color(51, 65, 85)
        self.write(5, f"{text}\n")
        self.ln(1)

    def paragraph(self, text):
        self.set_font('Helvetica', '', 9)
        self.set_text_color(51, 65, 85)
        self.multi_cell(0, 4.5, text)
        self.ln(2)

def build_pdf():
    pdf = ProjectSummaryPDF()
    pdf.alias_nb_pages()
    pdf.set_auto_page_break(auto=True, margin=15)
    pdf.add_page()
    
    # TITULO DO DOCUMENTO
    pdf.set_font('Helvetica', 'B', 16)
    pdf.set_text_color(30, 41, 59)
    pdf.cell(0, 8, 'Resumo Executivo do Projeto: AprovaQuest', 0, 1, 'L')
    
    pdf.set_font('Helvetica', 'I', 9.5)
    pdf.set_text_color(100, 116, 139)
    pdf.cell(0, 5, 'Documento de Apresentacao Tecnica, Gamificacao e Banco de Questoes', 0, 1, 'L')
    pdf.ln(4)

    # 1. VISÃO GERAL
    pdf.chapter_title('1. Visao Geral & Proposta de Valor')
    pdf.paragraph(
        "O AprovaQuest e uma plataforma web de aprendizagem ativa e simulados gamificados desenvolvida para "
        "estudantes vestibulandos (ENEM, FUVEST, UNICAMP, VUNESP e SENAI). Inspirado no modelo de sucesso do Duolingo, "
        "o sistema substitui listas estaticas de questoes por uma trilha interativa em fases estilo jogo de aventura."
    )
    pdf.paragraph(
        "O principal objetivo e aumentar a frequencia de estudo e o engajamento dos alunos atraves de ciclos curtos de "
        "pratica diaria, feedback pedagogico imediato e recompensas continuas de experiencia (XP)."
    )

    # 2. ARQUITETURA TECNOLÓGICA
    pdf.chapter_title('2. Arquitetura Tecnologica & Stack')
    pdf.bullet_point('Backend', 'PHP 8+ estruturado em padrao modular e rotas API JSON seguras.')
    pdf.bullet_point('Banco de Dados', 'MySQL / MariaDB com suporte a transacoes e historico de respostas.')
    pdf.bullet_point('Interface & Design System', 'Tailwind CSS CDN + Google Fonts (Outfit & Plus Jakarta Sans).')
    pdf.bullet_point('Iconografia & Renderizacao', 'Vetores SVG inline nativos (sem falhas de carregamento em redes lentas).')
    pdf.bullet_point('Canvas Interativo de Trilha', 'SVG Overlay dinamico com curvas Bezier S-Curve calculadas em tempo real.')
    pdf.bullet_point('Efeitos de Audio', 'Web Audio API para sons de clique, acerto, erro e conclusao de fase.')
    pdf.ln(2)

    # 3. MECÂNICAS DE GAMEPLAY & REGRAS
    pdf.chapter_title('3. Mecanicas de Gameplay & Sistema de Gamificacao')
    pdf.bullet_point('Trilha em Fases (Snake Path)', 'Nos 3D navegaveis com relevo tatil, indicador flutuante na fase atual e rastro de trilha continuo em curvas S (verde para concluidas, cinza para futuras).')
    pdf.bullet_point('Remocao Total do Sistema de Vidas', 'Coracoes/vidas foram 100% removidos da plataforma. O estudante pode praticar ilimitadamente sem ser bloqueado ou desmotivado.')
    pdf.bullet_point('Ofensiva Diaria (Daily Streak)', 'Verificacao automatica no login e submissao. Incrementa +1 dia a cada dia consecutivo de estudo e reseta caso o aluno falte um dia.')
    pdf.bullet_point('Dinamica Avancada de XP', 'Recompensa base por fase (+35 XP normal, +50 XP no Modo Chefao) somada a Bonus de Precisao (+25 XP para 100% de acertos, +15 XP para 80%+ e +5 XP para 60%+).')
    pdf.bullet_point('Progresso por Niveis (Level Up)', 'Calculo dinamico onde a cada 100 XP acumulados o jogador avanca de nivel (Nivel = floor(XP / 100) + 1) com alerta de nivel conquistado.')
    pdf.bullet_point('Modo Chefao (Boss Challenge)', 'Desafios ao final de cada unidade com questoes ineditas, resolucao oculta para maior desafio e filtro que impede repeticao de questoes acertadas por 5 dias.')
    pdf.ln(2)

    # 4. MÓDULOS E PÁGINAS DO SISTEMA
    pdf.chapter_title('4. Estrutura de Paginas & Padronizacao Visual')
    pdf.bullet_point('dashboard.php', 'Trilha central de estudos com mapa 3D, HUD do jogador e barra lateral de conquistas.')
    pdf.bullet_point('leaderboard.php', 'Ranking Arcade dos estudantes com podio destacado (1o, 2o e 3o lugares) e badges de nivel.')
    pdf.bullet_point('profile.php', 'Perfil do estudante com Loja de Avatares desbloqueaveis por XP e selos de conquistas.')
    pdf.bullet_point('course_guide.php', 'Guia de Cursos Universitarios e Notas de Corte oficiais (SISU / ENEM) por modalidade.')
    pdf.bullet_point('admin.php', 'Painel do Professor para cadastro de questoes com 5 alternativas (A a E), gabarito e explicacao.')
    pdf.bullet_point('index.php', 'Landing page e formulario de autenticacao (Login / Registro / Acesso Demo).')
    pdf.bullet_point('lesson.php', 'Motor de resolucao de exercicios com atalhos de teclado (teclas 1-5 / A-E) e feedback sonoro.')
    pdf.ln(2)

    # 5. BANCO DE QUESTÕES & ENEM 2025
    pdf.chapter_title('5. Acervo do Banco de Questoes & ENEM 2025')
    pdf.paragraph(
        "O AprovaQuest conta com um acervo total de 844 questoes cadastradas. Foi realizada a "
        "ingestao completa e integracao das questoes oficiais do ENEM 2025 (1o e 2o Dias), divididas entre:"
    )
    pdf.bullet_point('1o Dia (Caderno 1 Azul)', 'Linguagens, Codigos, Literatura (Romantismo, Simbolismo), Artes, Ingles, Espanhol, Historia e Filosofia (Hume, Foucault, Aristoteles, Saffioti).')
    pdf.bullet_point('2o Dia (Caderno 8 Verde)', 'Ciencias da Natureza (Quimica Organica, Eletroquimica, Biotecnologia, Ondulatoria, Termodinamica, Hidrostatica) e Matematica (Geometria, Estatistica, Probabilidade e Funcoes).')
    pdf.ln(2)

    # 6. CONCLUSÃO
    pdf.chapter_title('6. Conclusao & Impacto Educacional')
    pdf.paragraph(
        "Com a padronizacao em Tailwind CSS, a remocao do sistema de vidas, a introducao de uma dinamica de XP "
        "meritocratica e o cadastramento das questoes atualizadas do ENEM 2025, o AprovaQuest se consolida como uma "
        "solucao moderna, robusta e atraente para a preparacao de estudantes rumo a universidade."
    )

    output_dir = r"c:\xampp\htdocs\2025\projeto-final"
    pdf_path = os.path.join(output_dir, "Resumo_Projeto_AprovaQuest.pdf")
    pdf.output(pdf_path)
    print(f"PDF gerado com sucesso em: {pdf_path}")

if __name__ == '__main__':
    build_pdf()
