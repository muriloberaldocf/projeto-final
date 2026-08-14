<?php
require_once __DIR__ . '/_guard.php';
/**
 * MIGRAÇÃO: LINKS DO SITE TODA MATÉRIA PARA TODAS AS LIÇÕES — HIPOGABARITO
 * Substitui vídeos locais/embeds por links de leitura aprofundada do Toda Matéria (todamateria.com.br).
 */
require_once __DIR__ . '/../config/db.php';

try {
    // Mapeamento de links reais do Toda Matéria por assunto
    $todaMateriaMapping = [
        'Porcentagem & Regra de Três no ENEM' => [
            'url' => 'https://www.todamateria.com.br/porcentagem/',
            'title' => 'Toda Matéria: Guia Completo de Porcentagem & Regra de Três'
        ],
        'Áreas de Figuras Planas (Triângulos e Círculos)' => [
            'url' => 'https://www.todamateria.com.br/areas-de-figuras-planas/',
            'title' => 'Toda Matéria: Áreas de Figuras Planas e Fórmulas'
        ],
        'Gráficos e Raízes da Função Quadrática' => [
            'url' => 'https://www.todamateria.com.br/funcao-do-segundo-grau/',
            'title' => 'Toda Matéria: Função do 2º Grau, Gráficos e Vértices'
        ],
        'Velocidade Média e Leis de Newton' => [
            'url' => 'https://www.todamateria.com.br/leis-de-newton/',
            'title' => 'Toda Matéria: Leis de Newton & Cinemática'
        ],
        'Ligações Químicas e Tabela Periódica' => [
            'url' => 'https://www.todamateria.com.br/ligacoes-quimicas/',
            'title' => 'Toda Matéria: Ligações Iônicas, Covalentes e Metálicas'
        ],
        'Figuras de Linguagem nos Vestibulares' => [
            'url' => 'https://www.todamateria.com.br/figuras-de-linguagem/',
            'title' => 'Toda Matéria: Todas as Figuras de Linguagem com Exemplos'
        ]
    ];

    $stmtUpd = $pdo->prepare("UPDATE lessons SET video_url = ?, video_title = ? WHERE title = ?");
    foreach ($todaMateriaMapping as $title => $data) {
        $stmtUpd->execute([$data['url'], $data['title'], $title]);
    }

    // Para lições gerais, gerar link de busca temática no Toda Matéria
    $lessons = $pdo->query("SELECT id, title FROM lessons WHERE video_url IS NULL OR video_url NOT LIKE '%todamateria.com.br%'")->fetchAll();
    
    $stmtFallback = $pdo->prepare("UPDATE lessons SET video_url = ?, video_title = ? WHERE id = ?");
    foreach ($lessons as $l) {
        $cleanTitle = rawurlencode($l['title']);
        $url = "https://www.todamateria.com.br/?s=" . $cleanTitle;
        $title = "Toda Matéria: Artigos & Teoria sobre " . $l['title'];
        $stmtFallback->execute([$url, $title, $l['id']]);
    }

    echo "Migração de links do Toda Matéria concluída com sucesso!\n";

} catch (\PDOException $e) {
    echo "Erro ao atualizar links do Toda Matéria: " . $e->getMessage() . "\n";
}
