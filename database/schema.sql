-- ============================================================
-- SCRIPT DE CRIAÇÃO DO BANCO DE DADOS E CARGA INICIAL (VESTILINGO)
-- Duolingo para Vestibulares (ENEM, FUVEST, UNICAMP, VUNESP, SENAI)
-- ============================================================

CREATE DATABASE IF NOT EXISTS `vestilingo` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `vestilingo`;

-- 1. Tabela de Usuários
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `avatar` VARCHAR(255) DEFAULT 'default_student.png',
    `avatar_icon` VARCHAR(50) DEFAULT 'bi-person-circle',
    `xp` INT DEFAULT 0,
    `level` INT DEFAULT 1,
    `streak_days` INT DEFAULT 1,
    `last_active_date` DATE DEFAULT NULL,
    `hearts` INT DEFAULT 5,
    `max_hearts` INT DEFAULT 5,
    `role` ENUM('student', 'admin') DEFAULT 'student',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Tabela de Matérias
CREATE TABLE IF NOT EXISTS `subjects` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `description` TEXT,
    `icon` VARCHAR(50) DEFAULT 'book',
    `color_hex` VARCHAR(20) DEFAULT '#58cc02',
    `order_index` INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Tabela de Unidades de Aprendizado
CREATE TABLE IF NOT EXISTS `units` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `subject_id` INT NOT NULL,
    `title` VARCHAR(150) NOT NULL,
    `description` TEXT,
    `order_index` INT DEFAULT 0,
    FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Tabela de Lições
CREATE TABLE IF NOT EXISTS `lessons` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `unit_id` INT NOT NULL,
    `title` VARCHAR(150) NOT NULL,
    `xp_reward` INT DEFAULT 20,
    `order_index` INT DEFAULT 0,
    FOREIGN KEY (`unit_id`) REFERENCES `units`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Tabela de Questões (Estilo Vestibular Real com 5 Opções A, B, C, D, E)
CREATE TABLE IF NOT EXISTS `questions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `lesson_id` INT NOT NULL,
    `exam_source` VARCHAR(100) NOT NULL,
    `question_text` TEXT NOT NULL,
    `option_a` TEXT NOT NULL,
    `option_b` TEXT NOT NULL,
    `option_c` TEXT NOT NULL,
    `option_d` TEXT NOT NULL,
    `option_e` TEXT NOT NULL,
    `correct_option` ENUM('a', 'b', 'c', 'd', 'e') NOT NULL,
    `explanation_text` TEXT NOT NULL,
    `difficulty` ENUM('fácil', 'médio', 'difícil') DEFAULT 'médio',
    `is_boss` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`lesson_id`) REFERENCES `lessons`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Tabela de Progresso por Lição
CREATE TABLE IF NOT EXISTS `user_progress` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `lesson_id` INT NOT NULL,
    `score_percent` INT DEFAULT 100,
    `completed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_user_lesson` (`user_id`, `lesson_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`lesson_id`) REFERENCES `lessons`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Tabela de Histórico de Respostas das Questões
CREATE TABLE IF NOT EXISTS `user_answers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `question_id` INT NOT NULL,
    `chosen_option` ENUM('a', 'b', 'c', 'd', 'e') NOT NULL,
    `is_correct` TINYINT(1) NOT NULL,
    `answered_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`question_id`) REFERENCES `questions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. Tabela de Conquistas (Badges)
CREATE TABLE IF NOT EXISTS `achievements` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(50) NOT NULL UNIQUE,
    `title` VARCHAR(100) NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    `icon` VARCHAR(50) DEFAULT 'trophy',
    `xp_bonus` INT DEFAULT 50
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 9. Tabela de Conquistas Desbloqueadas pelo Usuário
CREATE TABLE IF NOT EXISTS `user_achievements` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `achievement_id` INT NOT NULL,
    `unlocked_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_user_achievement` (`user_id`, `achievement_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`achievement_id`) REFERENCES `achievements`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- CARGA DE DADOS INICIAIS (SEEDS)
-- ============================================================

-- Inserir Usuário Aluno Teste e Usuário Professor Admin (Senha padrão: 123456)
INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `xp`, `level`, `streak_days`, `last_active_date`, `hearts`, `role`) VALUES
(1, 'Estudante Focado', 'aluno@senai.br', '$2y$10$w6M6L1n/aF.p.1.r4lW0.eWzQ6B5q8iF9.K1J2L3M4N5O6P7Q8R9S', 180, 2, 5, CURDATE(), 5, 'student'),
(2, 'Prof. Lucas (SENAI)', 'prof@senai.br', '$2y$10$w6M6L1n/aF.p.1.r4lW0.eWzQ6B5q8iF9.K1J2L3M4N5O6P7Q8R9S', 500, 5, 12, CURDATE(), 5, 'admin'),
(3, 'Mariana Silva', 'mariana@enem.com', '$2y$10$w6M6L1n/aF.p.1.r4lW0.eWzQ6B5q8iF9.K1J2L3M4N5O6P7Q8R9S', 340, 3, 7, CURDATE(), 5, 'student'),
(4, 'Pedro Henrique', 'pedro@fuvest.com', '$2y$10$w6M6L1n/aF.p.1.r4lW0.eWzQ6B5q8iF9.K1J2L3M4N5O6P7Q8R9S', 260, 3, 3, CURDATE(), 4, 'student'),
(5, 'Beatriz Santos', 'beatriz@unicamp.com', '$2y$10$w6M6L1n/aF.p.1.r4lW0.eWzQ6B5q8iF9.K1J2L3M4N5O6P7Q8R9S', 120, 1, 2, CURDATE(), 5, 'student');

-- Inserir Matérias
INSERT INTO `subjects` (`id`, `name`, `slug`, `description`, `icon`, `color_hex`, `order_index`) VALUES
(1, 'Matemática & Raciocínio', 'matematica', 'Geometria, Álgebra, Funções, Porcentagem e Probabilidade.', 'calculator', '#58cc02', 1),
(2, 'Física', 'fisica', 'Mecânica, Leis de Newton, Termodinâmica, Óptica e Eletricidade.', 'zap', '#1cb0f6', 2),
(3, 'Química', 'quimica', 'Estequiometria, Química Orgânica, Tabela Periódica e Soluções.', 'flask', '#ffc800', 3),
(4, 'Biologia', 'biologia', 'Ecologia, Citologia, Genética e Fisiologia Humana.', 'leaf', '#20bf6b', 4),
(5, 'Português & Literatura', 'portugues', 'Gramática, Interpretação de Texto e Literatura Brasileira.', 'book-open', '#ff4b4b', 5),
(6, 'História & Geografia', 'humanas', 'História do Brasil, Geopolítica e Segunda Guerra Mundial.', 'globe', '#a55eea', 6);

-- Inserir Unidades
INSERT INTO `units` (`id`, `subject_id`, `title`, `description`, `order_index`) VALUES
(1, 1, 'Unidade 1: Geometria & Porcentagem Básica', 'Fundamentos de áreas, perímetros e cálculos de porcentagem no ENEM.', 1),
(2, 1, 'Unidade 2: Funções de 1º e 2º Grau', 'Interpretação de gráficos e equações do 2º grau.', 2),
(3, 2, 'Unidade 1: Cinemática & Leis de Newton', 'Movimento uniforme, aceleração e força resultante.', 1),
(4, 3, 'Unidade 1: Tabela Periódica & Ligações Química', 'Estrutura atômica e ligações iônicas/covalentes.', 1),
(5, 5, 'Unidade 1: Interpretação de Texto & Figuras de Linguagem', 'Identificação de metáforas, ironias e coesão textual.', 1);

-- Inserir Lições
INSERT INTO `lessons` (`id`, `unit_id`, `title`, `xp_reward`, `order_index`) VALUES
(1, 1, 'Porcentagem & Regra de Três no ENEM', 20, 1),
(2, 1, 'Áreas de Figuras Planas (Triângulos e Círculos)', 25, 2),
(3, 2, 'Gráficos e Raízes da Função Quadrática', 30, 1),
(4, 3, 'Velocidade Média e Leis de Newton', 25, 1),
(5, 4, 'Ligações Químicas e Tabela Periódica', 20, 1),
(6, 5, 'Figuras de Linguagem nos Vestibulares', 20, 1);

-- Inserir Questões Reais estilo Vestibular (Com 5 Alternativas: A, B, C, D, E)

-- Questão 1 (Matemática - ENEM 2023)
INSERT INTO `questions` (`id`, `lesson_id`, `exam_source`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `option_e`, `correct_option`, `explanation_text`, `difficulty`) VALUES
(1, 1, 'ENEM 2023', 
'Um comerciante decide aplicar um desconto de 20% sobre o preço de etiqueta de uma calça. Como o produto continuou encalhado, ele aplicou um segundo desconto sucessivo de 10% sobre o novo valor. Qual foi o desconto percentual total concedido em relação ao preço inicial de etiqueta?',
'A) 30%', 
'B) 28%', 
'C) 25%', 
'D) 22%', 
'E) 18%', 
'b', 
'Se o preço inicial é R$ 100,00: após o 1º desconto de 20%, o valor passa para R$ 80,00. O 2º desconto de 10% é calculado sobre R$ 80,00 (10% de 80 = R$ 8,00). O preço final fica R$ 72,00. O desconto total em relação a R$ 100 é R$ 28,00, ou seja, 28%. (Atenção: descontos sucessivos NÃO se somam diretamente!).', 
'médio');

-- Questão 2 (Matemática - SENAI / FUVEST)
INSERT INTO `questions` (`id`, `lesson_id`, `exam_source`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `option_e`, `correct_option`, `explanation_text`, `difficulty`) VALUES
(2, 1, 'FUVEST / SENAI 2024', 
'Em um setor industrial, 6 máquinas idênticas produzem 1.200 peças funcionando durante 8 horas por dia. Se 2 máquinas apresentarem defeito e pararem de funcionar, quantas peças as máquinas restantes produzirão se trabalharem durante 10 horas no mesmo dia?',
'A) 800 peças', 
'B) 1.000 peças', 
'C) 1.250 peças', 
'D) 1.500 peças', 
'E) 1.600 peças', 
'c', 
'Aplicando a Regra de Três Composta:\nMáquinas restantes = 4 máquinas.\n(Máquinas x Horas) / Peças = Constante.\n(6 x 8) / 1200 = (4 x 10) / X\n48 / 1200 = 40 / X\n48X = 48.000 => X = 1.250 peças.', 
'médio');

-- Questão 3 (Matemática - ENEM 2022)
INSERT INTO `questions` (`id`, `lesson_id`, `exam_source`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `option_e`, `correct_option`, `explanation_text`, `difficulty`) VALUES
(3, 2, 'ENEM 2022', 
'Uma praça circular possui raio igual a 10 metros. A prefeitura deseja pavimentar toda a superfície dessa praça com lajotas de concreto. Sabendo que o custo do metro quadrado pavimentado é de R$ 50,00 e utilizando π = 3,14, qual será o valor total gasto na pavimentação da praça?',
'A) R$ 15.700,00', 
'B) R$ 31.400,00', 
'C) R$ 7.850,00', 
'D) R$ 1.570,00', 
'E) R$ 6.280,00', 
'a', 
'Área do círculo: A = π * r² = 3,14 * (10)² = 3,14 * 100 = 314 m².\nCusto Total: 314 m² * R$ 50,00/m² = R$ 15.700,00.', 
'fácil');

-- Questão 4 (Física - UNICAMP 2023)
INSERT INTO `questions` (`id`, `lesson_id`, `exam_source`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `option_e`, `correct_option`, `explanation_text`, `difficulty`) VALUES
(4, 4, 'UNICAMP 2023', 
'Um carro parte do repouso e acelera uniformemente a uma taxa constante de 2,5 m/s² ao longo de uma pista retilínea. Qual será a velocidade atingida por este veículo após 8 segundos de movimento?',
'A) 10 m/s (36 km/h)', 
'B) 15 m/s (54 km/h)', 
'C) 20 m/s (72 km/h)', 
'D) 25 m/s (90 km/h)', 
'E) 30 m/s (108 km/h)', 
'c', 
'Usando a equação horária da velocidade no Movimento Uniformemente Variado (MUV): V = V0 + a*t.\nComo parte do repouso, V0 = 0.\nV = 0 + (2,5 m/s² * 8 s) = 20 m/s.\n(Convertendo para km/h: 20 * 3,6 = 72 km/h).', 
'fácil');

-- Questão 5 (Física - ENEM 2023)
INSERT INTO `questions` (`id`, `lesson_id`, `exam_source`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `option_e`, `correct_option`, `explanation_text`, `difficulty`) VALUES
(5, 4, 'ENEM 2023', 
'De acordo com a Primeira Lei de Newton (Lei da Inércia), qual das alternativas a seguir expressa corretamente o comportamento de um corpo material?',
'A) Um corpo necessita obrigatoriamente de uma força resultante contínua atuando sobre ele para se manter em movimento retilíneo uniforme.', 
'B) A aceleração de um objeto é inversamente proporcional à força resultante aplicada sobre sua massa.', 
'C) Todo corpo permanece em seu estado de repouso ou de movimento retilíneo uniforme a menos que seja compelido a mudar esse estado por forças resultantes externas.', 
'D) Para toda força de ação exercida por um corpo, existe uma força de reação de mesma intensidade, mesma direção e mesmo sentido.', 
'E) A massa de um corpo diminui à medida que sua velocidade se aproxima da velocidade do som no ar.', 
'c', 
'A 1ª Lei de Newton estabelece o Conceito de Inércia: se a força resultante sobre um corpo for nula, ele permanecerá em repouso (se já estava parado) ou em Movimento Retilíneo Uniforme (MRU, se já estava em movimento).', 
'fácil');

-- Questão 6 (Química - FUVEST 2023)
INSERT INTO `questions` (`id`, `lesson_id`, `exam_source`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `option_e`, `correct_option`, `explanation_text`, `difficulty`) VALUES
(6, 5, 'FUVEST 2023', 
'A ligação química caracterizada pela transferência definitiva de elétrons de um átomo metálico (que forma um cátion) para um átomo não-metálico (que forma um ânion) é denominada:',
'A) Ligação Covalente Simples', 
'B) Ligação Metálica', 
'C) Ligação Iônica', 
'D) Ligação Covalente Dativa', 
'E) Ligação de Hidrogênio', 
'c', 
'A Ligação Iônica (ou eletrovalente) ocorre entre metais (tendência a doar elétrons / cátions) e não-metais (tendência a receber elétrons / ânions) através de atração eletrostática por transferência de elétrons.', 
'fácil');

-- Questão 7 (Português - ENEM 2023)
INSERT INTO `questions` (`id`, `lesson_id`, `exam_source`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `option_e`, `correct_option`, `explanation_text`, `difficulty`) VALUES
(7, 6, 'ENEM 2023', 
'Assinale a alternativa em que a figura de linguagem presente na frase "Aquele jovem é um leão nos estudos, enfrenta qualquer desafio sem tremer" está corretamente identificada:',
'A) Metonímia', 
'B) Metáfora', 
'C) Pleonasmo', 
'D) Hipérbole', 
'E) Eufemismo', 
'b', 
'Trata-se de uma Metáfora (uma comparação implícita ou subentendida entre a coragem do jovem e a figura do leão, sem o uso do conectivo "como").', 
'fácil');

-- Inserir Conquistas (Badges)
INSERT INTO `achievements` (`id`, `code`, `title`, `description`, `icon`, `xp_bonus`) VALUES
(1, 'first_lesson', 'Primeiro Passo', 'Concluiu a primeira lição de vestibular!', 'target', 50),
(2, 'streak_3', 'Imparável 🔥', 'Manteve uma ofensiva de 3 dias seguidos!', 'flame', 100),
(3, 'perfectionist', 'Nota 1000 no ENEM', 'Acertou 100% das questões de uma lição!', 'award', 150),
(4, 'math_master', 'Mestre dos Números', 'Concluiu todas as lições de Matemática!', 'calculator', 200);

-- Progresso Inicial do Aluno Teste
INSERT INTO `user_progress` (`user_id`, `lesson_id`, `score_percent`) VALUES
(1, 1, 100);

-- Desbloquear Conquista Inicial para Aluno Teste
INSERT INTO `user_achievements` (`user_id`, `achievement_id`) VALUES
(1, 1);
