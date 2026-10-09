<?php
require_once __DIR__ . '/../config/db.php';

try {
    echo "Iniciando migração de tabelas de Redação..." . PHP_EOL;

    // 1. Tabela de Temas de Redação
    $pdo->exec("CREATE TABLE IF NOT EXISTS `redacao_temas` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `titulo` VARCHAR(255) NOT NULL,
        `ano` VARCHAR(20) DEFAULT NULL,
        `origem` VARCHAR(100) DEFAULT 'ENEM',
        `descricao` TEXT DEFAULT NULL,
        `textos_motivadores` LONGTEXT DEFAULT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "Tabela `redacao_temas` verificada/criada." . PHP_EOL;

    // 2. Tabela de Redações
    $pdo->exec("CREATE TABLE IF NOT EXISTS `redacoes` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT NOT NULL,
        `titulo` VARCHAR(255) NOT NULL,
        `tema_id` INT DEFAULT NULL,
        `texto_redacao` LONGTEXT NOT NULL,
        `arquivo_path` VARCHAR(255) DEFAULT NULL,
        `tipo_envio` ENUM('texto', 'imagem', 'pdf') DEFAULT 'texto',
        `relatorio_json` LONGTEXT NOT NULL,
        `nota_final` INT DEFAULT 0,
        `nota_c1` INT DEFAULT 0,
        `nota_c2` INT DEFAULT 0,
        `nota_c3` INT DEFAULT 0,
        `nota_c4` INT DEFAULT 0,
        `nota_c5` INT DEFAULT 0,
        `probabilidade_ia` INT DEFAULT 0,
        `justificativa_ia` TEXT DEFAULT NULL,
        `alerta_plagio` TINYINT(1) DEFAULT 0,
        `xp_ganho` INT DEFAULT 0,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX `idx_user_redacao` (`user_id`),
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "Tabela `redacoes` verificada/criada." . PHP_EOL;

    // 3. Inserir Temas Oficiais do ENEM e Temas Cotados
    $countTemas = $pdo->query("SELECT COUNT(*) FROM redacao_temas")->fetchColumn();
    if ($countTemas == 0) {
        $temas = [
            [
                'titulo' => 'Desafios para o enfrentamento da invisibilidade do trabalho de cuidado realizado pela mulher no Brasil',
                'ano' => '2023',
                'origem' => 'ENEM Oficial',
                'descricao' => 'A proposta aborda a histórica sobrecarga feminina em tarefas domésticas, cuidados com idosos e crianças, e a desvalorização social e econômica desse labor.',
                'textos_motivadores' => 'Texto I: Dados do IBGE apontam que mulheres dedicam quase o dobro de horas semanais a afazeres domésticos e cuidados de pessoas em relação aos homens.\nTexto II: O trabalho de cuidado sustenta a economia invisivelmente, sem a devida remuneração ou reconhecimento das políticas públicas.'
            ],
            [
                'titulo' => 'Desafios para a valorização de comunidades e povos tradicionais no Brasil',
                'ano' => '2022',
                'origem' => 'ENEM Oficial',
                'descricao' => 'Reflexão sobre os povos indígenas, quilombolas, ribeirinhos e ciganos, sua importância para a preservação ambiental e as ameaças aos seus territórios e culturas.',
                'textos_motivadores' => 'Texto I: O Brasil abriga dezenas de etnias e modos de vida tradicionais protegidos pela Constituição de 1988.\nTexto II: Conflitos fundiários, desmatamento e invisibilidade estatal colocam em risco o patrimônio material e imaterial desses povos.'
            ],
            [
                'titulo' => 'Invisibilidade e registro civil: garantia de acesso à cidadania no Brasil',
                'ano' => '2021',
                'origem' => 'ENEM Oficial',
                'descricao' => 'A falta de certidão de nascimento impede milhões de brasileiros de terem acesso a direitos básicos como saúde, educação, programas sociais e trabalho formal.',
                'textos_motivadores' => 'Texto I: A certidão de nascimento é o primeiro documento que formaliza a existência jurídica do cidadão perante o Estado.\nTexto II: Milhões de indivíduos sem registro vivem à margem da sociedade, impedidos de exercer seus direitos fundamentais.'
            ],
            [
                'titulo' => 'O estigma associado às doenças mentais na sociedade brasileira',
                'ano' => '2020',
                'origem' => 'ENEM Oficial',
                'descricao' => 'Aborda o preconceito, a falta de empatia e o déficit de atendimento de saúde mental para transtornos como depressão e ansiedade.',
                'textos_motivadores' => 'Texto I: Transtornos mentais afetam parcelas crescentes da população, mas ainda são vistos por muitos como fraqueza ou falta de esforço.\nTexto II: A necessidade de políticas públicas e da desmistificação da busca por apoio psicológico e psiquiátrico.'
            ],
            [
                'titulo' => 'Democratização do acesso ao cinema no Brasil',
                'ano' => '2019',
                'origem' => 'ENEM Oficial',
                'descricao' => 'Concentração de salas de cinema em centros urbanos e shoppings elitizados, excluindo periferias e pequenas cidades do consumo audiovisual.',
                'textos_motivadores' => 'Texto I: O cinema como instrumento cultural formador de pensamento crítico e fruição artística.\nTexto II: Dados revelam que a imensa maioria dos municípios brasileiros não possui sequer uma sala de exibição cinematográfica.'
            ],
            [
                'titulo' => 'Desafios éticos e educacionais no avanço da Inteligência Artificial no Brasil',
                'ano' => '2025',
                'origem' => 'Tema Inédito (Cotado)',
                'descricao' => 'Como a sociedade e a escola devem lidar com automação, plágio generativo, deepfakes e a necessidade de pensamento crítico na era dos algoritmos.',
                'textos_motivadores' => 'Texto I: O avanço rápido de IAs generativas transforma o mercado de trabalho e o aprendizado nas salas de aula.\nTexto II: A urgência de letramento digital, regulação ética e proteção contra desinformação em massa.'
            ],
            [
                'titulo' => 'Os impactos das mudanças climáticas na segurança alimentar e hídrica brasileira',
                'ano' => '2025',
                'origem' => 'Tema Inédito (Cotado)',
                'descricao' => 'Secas históricas na Amazônia, enchentes no Sul e o impacto direto na produção de alimentos, inflação e subsistência das populações vulneráveis.',
                'textos_motivadores' => 'Texto I: Eventos climáticos extremos afetam diretamente a agricultura familiar e os mananciais que abastecem as cidades.\nTexto II: O equilíbrio entre desenvolvimento sustentável, preservação de biomas e garantia de comida na mesa dos brasileiros.'
            ],
            [
                'titulo' => 'A inclusão de pessoas idosas na era da tecnologia e o combate ao etarismo',
                'ano' => '2025',
                'origem' => 'Tema Inédito (Cotado)',
                'descricao' => 'O envelhecimento acelerado da população brasileira, barreiras de usabilidade tecnológica, golpes digitais e preconceito contra a terceira idade.',
                'textos_motivadores' => 'Texto I: Projeções demográficas apontam que o Brasil será um país predominantemente idoso nas próximas décadas.\nTexto II: Serviços públicos e bancários migraram para aplicativos, gerando exclusão e vulnerabilidade para quem não tem letramento digital.'
            ]
        ];

        $ins = $pdo->prepare("INSERT INTO redacao_temas (titulo, ano, origem, descricao, textos_motivadores) VALUES (?, ?, ?, ?, ?)");
        foreach ($temas as $t) {
            $ins->execute([$t['titulo'], $t['ano'], $t['origem'], $t['descricao'], $t['textos_motivadores']]);
        }
        echo "Temas oficiais e cotados inseridos com sucesso (" . count($temas) . " temas)." . PHP_EOL;
    }

    echo "Migração concluída com 100% de sucesso!" . PHP_EOL;
} catch (Exception $e) {
    echo "ERRO NA MIGRAÇÃO: " . $e->getMessage() . PHP_EOL;
}
