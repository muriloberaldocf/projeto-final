<?php
/**
 * MIGRAÇÃO: ADICIONAR CAMPO CONTEÚDO EXPLICATIVO (SUMMARY_TEXT / INTRO_TEXT) EM LESSONS
 */
require_once __DIR__ . '/../config/db.php';

try {
    // 1. Adicionar a coluna intro_text em lessons se não existir
    $stmtCheck = $pdo->query("SHOW COLUMNS FROM lessons LIKE 'intro_text'");
    if (!$stmtCheck->fetch()) {
        $pdo->exec("ALTER TABLE lessons ADD COLUMN intro_text TEXT NULL AFTER title");
        echo "Coluna 'intro_text' adicionada com sucesso na tabela 'lessons'.\n";
    }

    // 2. Popular resumos explicativos nas lições existentes
    $lessonExplanations = [
        // Matemática
        'Porcentagem & Regra de Três no ENEM' => "📌 **Resumo Prático:** Porcentagem representa uma fração de base 100. Em descontos sucessivos, lembre-se: 20% + 10% NÃO é 30%! Aplica-se o desconto sobre o novo saldo acumulado.\n\n💡 **Regra de Três:** Mantenha grandezas diretamente proporcionais alinhadas e inverta as grandezas inversamente proporcionais antes de multiplicar cruzado.",
        'Áreas de Figuras Planas (Triângulos e Círculos)' => "📌 **Resumo Prático:**\n- **Área do Triângulo:** Área = (Base × Altura) / 2\n- **Área do Círculo:** Área = π × Raio²\n- **Área do Retângulo:** Área = Base × Altura\n\n💡 Fique atento às unidades de medida (cm² para m²)!",
        'Gráficos e Raízes da Função Quadrática' => "📌 **Resumo Prático:** Uma Função do 2º Grau f(x) = ax² + bx + c forma uma **parábola**.\n- Se a > 0, concavidade para cima 😃 (tem ponto de Mínimo).\n- Se a < 0, concavidade para baixo 🙁 (tem ponto de Máximo).\n- O Vértice representa o ponto máximo ou mínimo da curva.",
        
        // Física
        'Velocidade Média e Leis de Newton' => "📌 **Resumo Prático:**\n- **Velocidade Média:** Vm = ΔS / Δt. Para converter de m/s para km/h, multiplique por 3,6.\n- **1ª Lei de Newton (Inércia):** Todo corpo mantém seu estado até que uma força externa atue sobre ele.\n- **2ª Lei de Newton (Fórmula Fundamental):** Força = Massa × Aceleração (F = m · a).",

        // Química
        'Ligações Químicas e Tabela Periódica' => "📌 **Resumo Prático:**\n- **Ligação Iônica:** Ocorre por transferência de elétrons entre Metais (doam) e Não-Metais (recebem).\n- **Ligação Covalente:** Ocorre por compartilhamento de pares de elétrons entre não-metais.\n- **Regra do Octeto:** Átomos buscam 8 elétrons na camada de valência para estabilidade.",

        // Português
        'Figuras de Linguagem nos Vestibulares' => "📌 **Resumo Prático:**\n- **Metáfora:** Comparação implícita sem o uso do termo 'como'.\n- **Metonímia:** Substituição da parte pelo todo (ex: 'Li Machado de Assis').\n- **Hipérbole:** Exagero intencional ('Chorei rios de dinheiro').\n- **Eufemismo:** Suavização de uma ideia desagradável."
    ];

    $stmtUpdate = $pdo->prepare("UPDATE lessons SET intro_text = ? WHERE title = ?");
    foreach ($lessonExplanations as $title => $intro) {
        $stmtUpdate->execute([$intro, $title]);
    }

    // 3. Gerar um resumo dinâmico genérico para qualquer outra lição sem explicação cadastrada
    $pdo->exec("UPDATE lessons SET intro_text = CONCAT(
        '📌 **Resumo do Conteúdo:** Neta lição de **', title, '**, abordaremos os conceitos essenciais cobrados nos principais vestibulares do Brasil (ENEM, FUVEST, UNICAMP).\n\n💡 **Dica de Estudo:** Leia o enunciado com atenção, sublinhe os dados fornecidos e elimine as alternativas evidentemente incorretas antes de responder!'
    ) WHERE intro_text IS NULL OR intro_text = ''");

    echo "Resumos explicativos populados com sucesso!\n";

} catch (PDOException $e) {
    echo "Erro na migração: " . $e->getMessage() . "\n";
}
