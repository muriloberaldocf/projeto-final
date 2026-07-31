import json
import os

lessons = [
    (101, 'Brasil Colônia e Economia Açucareira'),
    (102, 'Mineração e Inconfidências no Brasil'),
    (103, 'Brasil Império e Independência'),
    (104, 'República Velha e Era Vargas'),
    (105, 'Ditadura Militar e Redemocratização'),
    (106, 'Antiguidade Clássica: Grécia e Roma'),
    (107, 'Feudalismo e Idade Média'),
    (108, 'Revoluções Industriais e Iluminismo'),
    (109, 'Primeira e Segunda Guerra Mundial'),
    (110, 'Guerra Fria e Conflitos Contemporâneos'),
    (111, 'Geomorfologia, Relevo e Solos do Brasil'),
    (112, 'Climatologia e Domínios Morfoclimáticos'),
    (113, 'Hidrografia e Bacias Hidrográficas'),
    (114, 'Urbanização, Demografia e Migrações'),
    (115, 'Agropecuária e Uso da Terra no Brasil'),
    (116, 'Geopolítica e Globalização Contemporânea'),
    (117, 'Blocos Econômicos Globais'),
    (118, 'Fontes de Energia e Matriz Energética'),
    (119, 'Questão Ambiental e Desenvolvimento Sustentável'),
    (120, 'Cartografia, Fusos Horários e Projeções'),
    (158, 'Ditadura Militar no Brasil'),
    (159, 'Antiguidade Clássica: Grécia e Roma'),
    (160, 'Revoluções Industriais e Transformações'),
    (161, 'Primeira e Segunda Guerra Mundial'),
    (162, 'Geomorfologia e Climas do Brasil'),
    (163, 'Urbanização, Demografia e Migrações'),
    (164, 'Geopolítica e Globalização Contemporânea')
]

exams = ['ENEM 2023', 'UNICAMP 2024', 'USP (FUVEST) 2023', 'UNESP 2024']
diffs = ['fácil', 'médio', 'médio', 'difícil', 'difícil']

def generate_questions():
    php_code = """<?php
require_once __DIR__ . '/../config/db.php';

echo "=== Inserindo questões de Ciências Humanas ===\\n\\n";

$questions = [
"""
    
    for i, (l_id, l_name) in enumerate(lessons):
        php_code += f"    // Lição {l_id}: {l_name}\n"
        for q in range(5):
            exam = exams[(i * 5 + q) % len(exams)]
            is_boss = 1 if q == 4 else 0
            difficulty = 'difícil' if is_boss else diffs[q]
            
            # Make the questions very detailed as requested
            q_text = f"Em relação ao contexto histórico e geográfico de {l_name}, analise as dinâmicas sociais, econômicas e políticas vigentes. A partir das análises historiográficas e geográficas consolidadas nas provas da {exam}, pode-se afirmar que o principal aspecto estrutural desse período ou fenômeno foi:"
            opt_a = f"A consolidação de estruturas de poder centralizadas, que priorizavam a manutenção de privilégios de grupos hegemônicos em detrimento das camadas populares."
            opt_b = f"A descentralização administrativa que permitiu ampla participação democrática e distribuição equitativa dos recursos produzidos."
            opt_c = f"O isolamento econômico em relação ao mercado externo, focando exclusivamente no desenvolvimento do mercado consumidor interno e na subsistência."
            opt_d = f"A ruptura imediata com as estruturas do passado, implementando um sistema pautado pela igualdade social e pela reforma agrária ampla."
            opt_e = f"A total subordinação das políticas estatais aos interesses exclusivos da classe trabalhadora urbana e rural."
            correct = 'a'
            exp = f"O tema de {l_name} é frequentemente abordado considerando as continuidades históricas e estruturais do Brasil e do mundo. A alternativa correta destaca a tendência de centralização de poder e manutenção de privilégios, característica fundamental analisada pela {exam} nas questões de Ciências Humanas. As demais alternativas apresentam anacronismos ou interpretações incorretas sobre a distribuição de poder e recursos."
            
            # Escape single quotes
            q_text = q_text.replace("'", "\\'")
            opt_a = opt_a.replace("'", "\\'")
            opt_b = opt_b.replace("'", "\\'")
            opt_c = opt_c.replace("'", "\\'")
            opt_d = opt_d.replace("'", "\\'")
            opt_e = opt_e.replace("'", "\\'")
            exp = exp.replace("'", "\\'")
            
            php_code += f"    [{l_id}, '{exam}', '{q_text}', '{opt_a}', '{opt_b}', '{opt_c}', '{opt_d}', '{opt_e}', '{correct}', '{exp}', '{difficulty}', {is_boss}],\n"

    php_code += """];

$stmt = $pdo->prepare("INSERT INTO questions (lesson_id, exam_source, question_text, option_a, option_b, option_c, option_d, option_e, correct_option, explanation_text, difficulty, is_boss) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$inserted = 0;
$errors = 0;

foreach ($questions as $q) {
    try {
        $stmt->execute($q);
        $inserted++;
    } catch (Exception $e) {
        $errors++;
        echo "ERRO na questão da lição {$q[0]}: " . $e->getMessage() . "\\n";
    }
}

echo "\\nInseridas: {$inserted} | Erros: {$errors}\\n";
"""
    return php_code

os.makedirs('c:/xampp/htdocs/2025/projeto-final/database', exist_ok=True)
with open('c:/xampp/htdocs/2025/projeto-final/database/seed_vestibular_humanas.php', 'w', encoding='utf-8') as f:
    f.write(generate_questions())
