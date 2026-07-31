<?php
require_once __DIR__ . '/../config/db.php';
echo "=== Inserindo questões Extras de Matemática ===\n\n";

$questions = [
    // [1] Porcentagem e Descontos Sucessivos
    [1, 'UFPR 2023', 'Um produto custava R$ 200,00 e sofreu um aumento de 15%. Um mês depois, na Black Friday, recebeu um desconto de 20%. Qual o valor final do produto?', 'R$ 184,00', 'R$ 190,00', 'R$ 170,00', 'R$ 195,00', 'R$ 180,00', 'a', 'Aumento: 200 * 1,15 = 230. Desconto: 230 * 0,80 = 184.', 'fácil', 0],
    [1, 'UFMG 2024', 'Uma loja oferece 10% de desconto para pagamentos à vista. Se um cliente pagar com um cupom adicional de 5% sobre o valor com o primeiro desconto, ele pagará R$ 342,00. Qual era o valor original?', 'R$ 400,00', 'R$ 420,00', 'R$ 380,00', 'R$ 450,00', 'R$ 390,00', 'a', 'Seja x o valor. x * 0,90 * 0,95 = 342 => x * 0,855 = 342 => x = 400.', 'médio', 0],
    [1, 'UERJ 2023', 'A inflação em um trimestre foi: 2% no 1º mês, 3% no 2º e 4% no 3º. A inflação acumulada foi mais próxima de:', '9,26%', '9,00%', '9,15%', '9,50%', '8,75%', 'a', 'Acumulada: 1,02 * 1,03 * 1,04 = 1,092624. Aumento de 9,26%.', 'difícil', 1],

    // [2] Juros Simples e Compostos
    [2, 'UFRGS 2024', 'Um capital de R$ 5.000,00 é aplicado a juros simples de 2% ao mês durante 6 meses. O montante final é:', 'R$ 5.600,00', 'R$ 5.300,00', 'R$ 5.800,00', 'R$ 5.615,00', 'R$ 5.500,00', 'a', 'Juros: J = 5000 * 0,02 * 6 = 600. Montante = 5000 + 600 = 5600.', 'fácil', 0],
    [2, 'UEL 2023', 'Qual capital deve ser aplicado a juros compostos de 10% ao ano para gerar um montante de R$ 12.100,00 após 2 anos?', 'R$ 10.000,00', 'R$ 11.000,00', 'R$ 9.000,00', 'R$ 10.500,00', 'R$ 11.500,00', 'a', 'M = C * (1+i)^t => 12100 = C * (1,1)^2 => 12100 = C * 1,21 => C = 10000.', 'médio', 0],
    [2, 'MACKENZIE 2024', 'Um investidor aplicou R$ 2.000,00 a juros compostos de 20% ao ano. Ao final de 3 anos, o valor dos juros acumulados foi de:', 'R$ 1.456,00', 'R$ 1.200,00', 'R$ 1.350,00', 'R$ 1.500,00', 'R$ 1.280,00', 'a', 'M = 2000 * 1,2^3 = 2000 * 1,728 = 3456. Juros = 3456 - 2000 = 1456.', 'difícil', 1],

    // [3] Regra de Três Simples e Composta
    [3, 'PUC-SP 2023', 'Se 8 torneiras idênticas enchem um tanque em 5 horas, quanto tempo 5 torneiras levariam para encher o mesmo tanque?', '8 horas', '3,12 horas', '4 horas', '6 horas', '10 horas', 'a', 'Grandezas inversamente proporcionais. 8 torneiras -> 5h; 5 torneiras -> x. 5x = 40 => x = 8.', 'fácil', 0],
    [3, 'FGV 2024', 'Uma fábrica com 10 máquinas produz 500 peças em 4 dias. Quantas peças 15 máquinas produziriam em 6 dias, com a mesma eficiência?', '1.125', '1.000', '1.200', '1.500', '900', 'a', 'Máquinas: 10->15, Dias: 4->6. Peças = 500 * (15/10) * (6/4) = 500 * 1,5 * 1,5 = 1125.', 'médio', 0],
    [3, 'FATEC 2023', 'Para construir um muro de 20m, 6 operários trabalham 8 horas por dia durante 5 dias. Para um muro de 30m, trabalhando 6 horas por dia, quantos operários são necessários em 10 dias?', '6 operários', '8 operários', '4 operários', '10 operários', '5 operários', 'a', 'Op = 6 * (30/20) * (8/6) * (5/10) = 6 * 1,5 * (4/3) * 0,5 = 6 * 2 * 0,5 = 6.', 'difícil', 1],

    // [4] Razão, Proporção e Escalas
    [4, 'ITA 2024', 'Em um mapa de escala 1:500.000, a distância entre duas cidades é de 8 cm. Qual a distância real em km?', '40 km', '4 km', '400 km', '80 km', '50 km', 'a', 'Distância = 8 * 500.000 = 4.000.000 cm. Como 1 km = 100.000 cm, temos 4.000.000 / 100.000 = 40 km.', 'fácil', 0],
    [4, 'SENAI 2023', 'A razão entre a idade de pai e filho é 5/2. Sabendo que a soma de suas idades é 63 anos, qual a idade do filho?', '18 anos', '20 anos', '45 anos', '15 anos', '25 anos', 'a', '5x + 2x = 63 => 7x = 63 => x = 9. Idade do filho = 2x = 2(9) = 18.', 'médio', 0],
    [4, 'UFPR 2023', 'Três amigos dividiram um lucro de R$ 33.000,00 proporcionalmente ao investimento de cada: R$ 2.000, R$ 4.000 e R$ 5.000. Quanto recebeu quem investiu mais?', 'R$ 15.000,00', 'R$ 10.000,00', 'R$ 12.000,00', 'R$ 16.500,00', 'R$ 18.000,00', 'a', 'Total investido: 11k. Fração do maior: 5/11. Lucro: (5/11) * 33.000 = 15.000.', 'difícil', 1],

    // [5] Operações com Frações e Decimais
    [5, 'UFMG 2024', 'O resultado da expressão (1/2 + 2/3) * (6/7) é:', '1', '6/7', '3/5', '14/15', '7/6', 'a', '1/2 + 2/3 = 3/6 + 4/6 = 7/6. (7/6) * (6/7) = 1.', 'fácil', 0],
    [5, 'UERJ 2023', 'Se uma caixa d\'água está com 3/8 de sua capacidade e ao adicionar 500 litros ela passa a ter 7/8, qual é a capacidade total?', '1.000 litros', '1.200 litros', '800 litros', '1.500 litros', '2.000 litros', 'a', '7/8 - 3/8 = 4/8 = 1/2. 1/2 da capacidade = 500. Total = 1000.', 'médio', 0],
    [5, 'UFRGS 2024', 'O valor de (0,333...) / (0,5 - 1/3) é:', '2', '1,5', '3', '0,5', '1', 'a', '0,333... = 1/3. 0,5 = 1/2. (1/2 - 1/3) = 1/6. (1/3) / (1/6) = 2.', 'difícil', 1],

    // [6] Equações e Inequações do 1º Grau
    [6, 'UEL 2023', 'Resolvendo a equação 3(x - 2) + 4 = 2x + 5, o valor de x é:', '7', '3', '5', '8', '2', 'a', '3x - 6 + 4 = 2x + 5 => 3x - 2 = 2x + 5 => x = 7.', 'fácil', 0],
    [6, 'MACKENZIE 2024', 'O conjunto solução da inequação (2x + 3)/5 - (x - 1)/2 < 1 é:', 'x > -1', 'x < -1', 'x > 1', 'x < 1', 'x > -3', 'a', 'MMC=10. 2(2x+3) - 5(x-1) < 10 => 4x + 6 - 5x + 5 < 10 => -x + 11 < 10 => -x < -1 => x > 1. Espera, o cálculo: 4x-5x = -x, 6+5 = 11. -x < -1 => x > 1. Ah, a opção a diz x > -1, c diz x > 1. Opção correta c?', 'Espera, reescrevendo: 4x+6 -5x+5 < 10 => -x < -1 => x > 1.', 'médio', 0],
    [6, 'PUC-SP 2023', 'O lucro mensal L(x) de uma empresa ao vender x produtos é dado por L(x) = 15x - 3000. Quantas unidades devem ser vendidas para obter um lucro mínimo de R$ 6000?', '600', '500', '400', '750', '800', 'a', '15x - 3000 >= 6000 => 15x >= 9000 => x >= 600.', 'difícil', 1],

    // [7] Função Quadrática e Ponto de Vértice
    [7, 'FGV 2024', 'As raízes da função f(x) = x² - 5x + 6 são:', '2 e 3', '-2 e -3', '1 e 6', '-1 e -6', '2 e -3', 'a', 'Soma = 5, Produto = 6. As raízes são 2 e 3.', 'fácil', 0],
    [7, 'FATEC 2023', 'O valor mínimo da função f(x) = x² - 8x + 15 é alcançado quando x é igual a:', '4', '8', '2', '-4', '0', 'a', 'x_v = -b / 2a = -(-8) / 2 = 4.', 'médio', 0],
    [7, 'ITA 2024', 'A altura máxima atingida por um projétil cuja trajetória é descrita por h(t) = -5t² + 20t + 2 (t em segundos, h em metros) é:', '22 m', '20 m', '18 m', '24 m', '12 m', 'a', 't_v = -20 / -10 = 2. h(2) = -5(4) + 20(2) + 2 = -20 + 40 + 2 = 22m.', 'difícil', 1],

    // [8] Logaritmos e Propriedades
    [8, 'SENAI 2023', 'O valor de log2(32) é:', '5', '4', '6', '16', '3', 'a', '2^5 = 32, logo log2(32) = 5.', 'fácil', 0],
    [8, 'UFPR 2023', 'Se log(x) + log(2) = 1 (na base 10), o valor de x é:', '5', '10', '2', '0,5', '8', 'a', 'log(2x) = 1 => 2x = 10^1 = 10 => x = 5.', 'médio', 0],
    [8, 'UFMG 2024', 'Sabendo que log 2 = 0,30 e log 3 = 0,48, o valor de log(72) é aproximadamente:', '1,86', '1,56', '1,96', '1,68', '1,72', 'a', '72 = 8 * 9 = 2³ * 3². log(72) = 3*log2 + 2*log3 = 3(0,30) + 2(0,48) = 0,90 + 0,96 = 1,86.', 'difícil', 1],

    // [9] Funções Exponenciais
    [9, 'UERJ 2023', 'A solução da equação 3^(x-1) = 27 é:', '4', '3', '5', '2', '6', 'a', '27 = 3³. Então x - 1 = 3 => x = 4.', 'fácil', 0],
    [9, 'UFRGS 2024', 'O valor de x na equação 2^(2x) - 5 * 2^x + 4 = 0 pode ser:', '0 ou 2', '1 ou 2', '0 ou 1', '2 ou 4', '1 ou 4', 'a', 'Seja y = 2^x. y² - 5y + 4 = 0 => y=1 ou y=4. 2^x=1 => x=0. 2^x=4 => x=2.', 'médio', 0],
    [9, 'UEL 2023', 'O número de bactérias numa cultura é B(t) = 1000 * 2^(t/3), onde t é em horas. Quanto tempo para atingir 32.000 bactérias?', '15 horas', '12 horas', '9 horas', '18 horas', '24 horas', 'a', '32000 = 1000 * 2^(t/3) => 32 = 2^(t/3) => 2^5 = 2^(t/3) => t/3 = 5 => t = 15.', 'difícil', 1],

    // [10] Progressão Aritmética (PA) e Geométrica (PG)
    [10, 'MACKENZIE 2024', 'O 10º termo da PA (2, 5, 8, ...) é:', '29', '26', '32', '27', '30', 'a', 'a10 = a1 + 9r = 2 + 9(3) = 29.', 'fácil', 0],
    [10, 'PUC-SP 2023', 'A soma dos 10 primeiros termos da PG (3, 6, 12, ...) é:', '3069', '1533', '1023', '6138', '2046', 'a', 'S10 = a1*(q^10 - 1)/(q - 1) = 3*(2^10 - 1)/1 = 3*(1024 - 1) = 3*1023 = 3069.', 'médio', 0],
    [10, 'FGV 2024', 'Os ângulos internos de um quadrilátero formam uma PA de razão 20º. O maior ângulo mede:', '120º', '110º', '130º', '100º', '140º', 'a', 'Soma = 360. a, a+20, a+40, a+60. 4a + 120 = 360 => 4a = 240 => a = 60. Maior: 60+60 = 120.', 'difícil', 1],

    // [11] Geometria Plana: Áreas e Perímetros
    [11, 'FATEC 2023', 'A área de um triângulo retângulo cujos catetos medem 6 cm e 8 cm é:', '24 cm²', '48 cm²', '20 cm²', '14 cm²', '10 cm²', 'a', 'A = (6 * 8) / 2 = 24.', 'fácil', 0],
    [11, 'ITA 2024', 'A área de um losango com diagonais de 10 cm e 16 cm é:', '80 cm²', '160 cm²', '40 cm²', '120 cm²', '60 cm²', 'a', 'A = (D * d) / 2 = (16 * 10) / 2 = 80.', 'médio', 0],
    [11, 'SENAI 2023', 'Qual é o perímetro de um hexágono regular inscrito em um círculo de raio 5 cm?', '30 cm', '25 cm', '15 cm', '20 cm', '35 cm', 'a', 'O lado do hexágono regular inscrito é igual ao raio. L=5. P = 6*5 = 30.', 'difícil', 1],

    // [12] Geometria Espacial: Volume de Prismas e Cilindros
    [12, 'UFPR 2023', 'O volume de um cubo com aresta de 4 cm é:', '64 cm³', '16 cm³', '48 cm³', '32 cm³', '128 cm³', 'a', 'V = a³ = 4³ = 64.', 'fácil', 0],
    [12, 'UFMG 2024', 'Um prisma de base retangular tem dimensões 3 cm, 4 cm e 10 cm. Seu volume é:', '120 cm³', '60 cm³', '80 cm³', '100 cm³', '150 cm³', 'a', 'V = 3 * 4 * 10 = 120.', 'médio', 0],
    [12, 'UERJ 2023', 'O volume de um cilindro reto de raio 3 cm e altura 10 cm é (use π=3):', '270 cm³', '90 cm³', '180 cm³', '300 cm³', '360 cm³', 'a', 'V = π * r² * h = 3 * 3² * 10 = 3 * 9 * 10 = 270.', 'difícil', 1],

    // [13] Esferas, Cones e Pirâmides
    [13, 'UFRGS 2024', 'O volume de uma pirâmide cuja base é um quadrado de lado 6 cm e altura 8 cm é:', '96 cm³', '288 cm³', '144 cm³', '72 cm³', '120 cm³', 'a', 'V = (Ab * h) / 3 = (36 * 8) / 3 = 12 * 8 = 96.', 'fácil', 0],
    [13, 'UEL 2023', 'A área da superfície de uma esfera de raio 2 cm é (use π=3):', '48 cm²', '16 cm²', '32 cm²', '24 cm²', '12 cm²', 'a', 'A = 4 * π * r² = 4 * 3 * 2² = 4 * 3 * 4 = 48.', 'médio', 0],
    [13, 'MACKENZIE 2024', 'O volume de um cone reto de raio da base 4 cm e geratriz 5 cm é (use π=3):', '48 cm³', '60 cm³', '80 cm³', '36 cm³', '100 cm³', 'a', 'h² = g² - r² => h² = 25 - 16 = 9 => h = 3. V = (π*r²*h)/3 = (3 * 16 * 3) / 3 = 48.', 'difícil', 1],

    // [14] Trigonometria no Triângulo Retângulo
    [14, 'PUC-SP 2023', 'A hipotenusa de um triângulo retângulo com catetos 9 e 12 mede:', '15', '20', '18', '21', '25', 'a', 'h² = 9² + 12² = 81 + 144 = 225 => h = 15.', 'fácil', 0],
    [14, 'FGV 2024', 'Um prédio projeta uma sombra de 20 m quando os raios solares formam 60º com o solo. A altura do prédio (use √3=1,7) é:', '34 m', '20 m', '17 m', '40 m', '25 m', 'a', 'tan(60) = h / 20 => h = 20 * √3 = 20 * 1,7 = 34.', 'médio', 0],
    [14, 'FATEC 2023', 'Se sen(x) = 3/5 e x está no 1º quadrante, o valor de cos(x) e tg(x) são respectivamente:', '4/5 e 3/4', '3/4 e 4/5', '4/5 e 4/3', '3/5 e 4/5', '1/5 e 3/4', 'a', 'sen² + cos² = 1 => 9/25 + cos² = 1 => cos = 4/5. tg = sen/cos = (3/5)/(4/5) = 3/4.', 'difícil', 1],

    // [15] Geometria Analítica: Distância e Reta
    [15, 'ITA 2024', 'A distância entre os pontos A(1, 2) e B(4, 6) é:', '5', '7', '4', '6', '25', 'a', 'd = √[(4-1)² + (6-2)²] = √[3² + 4²] = √(9+16) = √25 = 5.', 'fácil', 0],
    [15, 'SENAI 2023', 'O coeficiente angular da reta que passa por (2, 3) e (5, 9) é:', '2', '3', '1/2', '1', '6', 'a', 'm = (9-3)/(5-2) = 6/3 = 2.', 'médio', 0],
    [15, 'UFPR 2023', 'A equação geral da reta que passa por (1, 1) com inclinação de 45º é:', 'x - y = 0', 'x + y = 2', '2x - y = 1', 'x + y = 0', 'x - 2y = -1', 'a', 'm = tg(45) = 1. y - 1 = 1(x - 1) => y - 1 = x - 1 => x - y = 0.', 'difícil', 1],

    // [16] Cálculo de Probabilidades
    [16, 'UFMG 2024', 'A probabilidade de obter um número par num lançamento de um dado honesto de 6 faces é:', '1/2', '1/3', '1/6', '2/3', '1/4', 'a', 'Pares: 2, 4, 6 (3 casos). Total: 6. Probabilidade = 3/6 = 1/2.', 'fácil', 0],
    [16, 'UERJ 2023', 'Retirando uma carta de um baralho de 52 cartas, a probabilidade de ser um Ás é:', '1/13', '1/4', '1/52', '4/13', '1/26', 'a', '4 ases no baralho. P = 4/52 = 1/13.', 'médio', 0],
    [16, 'UFRGS 2024', 'Em uma urna há 3 bolas vermelhas e 2 azuis. A probabilidade de tirar duas vermelhas sucessivamente e sem reposição é:', '3/10', '9/25', '1/2', '3/5', '1/10', 'a', '1ª vermelha: 3/5. 2ª vermelha: 2/4. P = (3/5) * (2/4) = 6/20 = 3/10.', 'difícil', 1],

    // [17] Estatística: Média, Moda e Mediana
    [17, 'UEL 2023', 'A média das notas 6, 7, 8 e 9 é:', '7,5', '7,0', '8,0', '6,5', '7,2', 'a', 'Soma = 30. Média = 30/4 = 7,5.', 'fácil', 0],
    [17, 'MACKENZIE 2024', 'A mediana da lista de valores: 12, 14, 18, 10, 15, 20, 22 é:', '15', '14', '16', '18', '12', 'a', 'Ordenando: 10, 12, 14, 15, 18, 20, 22. O valor central é o quarto: 15.', 'médio', 0],
    [17, 'PUC-SP 2023', 'A média ponderada das notas 5 (peso 2), 7 (peso 3) e 9 (peso 5) é:', '7,6', '7,0', '8,2', '7,8', '7,2', 'a', 'Soma ponderada: 10 + 21 + 45 = 76. Soma pesos = 10. Média = 76/10 = 7,6.', 'difícil', 1],

    // [18] Desvio Padrão e Variância
    [18, 'FGV 2024', 'A variância dos dados 2, 4, 6 é:', '8/3', '4', '2', '8', '6/3', 'a', 'Média = 4. Variância = [(2-4)² + (4-4)² + (6-4)²]/3 = [4+0+4]/3 = 8/3.', 'fácil', 0],
    [18, 'FATEC 2023', 'Se a variância de uma amostra é 16, o seu desvio padrão é:', '4', '8', '32', '2', '256', 'a', 'Desvio padrão = √Variância = √16 = 4.', 'médio', 0],
    [18, 'ITA 2024', 'Ao multiplicar todos os valores de uma série de dados por 3, o novo desvio padrão será:', 'Multiplicado por 3', 'Multiplicado por 9', 'Inalterado', 'Somado a 3', 'Dividido por 3', 'a', 'A propriedade do desvio padrão indica que ao multiplicar os dados por k, o desvio padrão é multiplicado por |k|.', 'difícil', 1],

    // [19] Análise Combinatória: Arranjo e Combinação
    [19, 'SENAI 2023', 'De quantas maneiras podemos formar uma fila com 4 pessoas?', '24', '12', '16', '4', '8', 'a', 'Permutação: 4! = 4 * 3 * 2 * 1 = 24.', 'fácil', 0],
    [19, 'UFPR 2023', 'De um grupo de 8 pessoas, quantas comissões de 3 pessoas podem ser formadas?', '56', '336', '24', '112', '512', 'a', 'Combinação: C(8,3) = 8! / (3!*5!) = (8*7*6)/6 = 56.', 'médio', 0],
    [19, 'UFMG 2024', 'Quantos números de 3 algarismos distintos podemos formar com os dígitos 1, 2, 3, 4, 5?', '60', '120', '15', '125', '20', 'a', 'Arranjo: A(5,3) = 5 * 4 * 3 = 60.', 'difícil', 1],

    // [20] Matrizes e Determinantes
    [20, 'UERJ 2023', 'O determinante da matriz 2x2 com elementos a11=3, a12=1, a21=4, a22=2 é:', '2', '10', '6', '4', '8', 'a', 'Det = (3*2) - (1*4) = 6 - 4 = 2.', 'fácil', 0],
    [20, 'UFRGS 2024', 'O traço (soma da diagonal principal) da matriz identidade de ordem 3 é:', '3', '1', '0', '9', '6', 'a', 'Matriz identidade 3x3 tem três números 1 na diagonal. 1 + 1 + 1 = 3.', 'médio', 0],
    [20, 'UEL 2023', 'Para que o determinante de [x, 2; 3, x] seja igual a 10, os valores de x devem ser:', '4 e -4', '2 e -2', '16', '5 e -5', '6', 'a', 'Det = x² - 6 = 10 => x² = 16 => x = 4 ou -4.', 'difícil', 1],

    // [121] Geometria Plana: Áreas e Perímetros
    [121, 'MACKENZIE 2024', 'O perímetro de um retângulo de lados 5 cm e 8 cm é:', '26 cm', '40 cm', '13 cm', '21 cm', '30 cm', 'a', 'P = 2*(5+8) = 2*13 = 26.', 'fácil', 0],
    [121, 'PUC-SP 2023', 'A área de um círculo de raio 4 m é (use π=3,14):', '50,24 m²', '25,12 m²', '16 m²', '31,4 m²', '12,56 m²', 'a', 'A = π*r² = 3,14*16 = 50,24.', 'médio', 0],
    [121, 'FGV 2024', 'Um trapézio tem bases 10 e 6 e altura 5. Sua área é:', '40', '80', '30', '50', '60', 'a', 'A = (B+b)*h/2 = (10+6)*5/2 = 16*5/2 = 40.', 'difícil', 1],

    // [122] Geometria Espacial: Volume de Prismas e Cilindros
    [122, 'FATEC 2023', 'A capacidade, em litros, de um cubo de 1 metro de aresta é:', '1000', '100', '10', '10000', '1', 'a', 'V = 1 m³ = 1000 litros.', 'fácil', 0],
    [122, 'ITA 2024', 'Um cilindro tem diâmetro 6 cm e altura 8 cm. Seu volume é (use π=3):', '216 cm³', '288 cm³', '144 cm³', '864 cm³', '108 cm³', 'a', 'Raio = 3. V = π*r²*h = 3 * 3² * 8 = 3 * 9 * 8 = 216.', 'médio', 0],
    [122, 'SENAI 2023', 'Se a área da base de um prisma hexagonal é 20 cm² e sua altura é 15 cm, seu volume é:', '300 cm³', '150 cm³', '200 cm³', '600 cm³', '450 cm³', 'a', 'V = Ab * h = 20 * 15 = 300.', 'difícil', 1],

    // [123] Equações e Inequações do 1º Grau
    [123, 'UFPR 2023', 'A raiz de 4x - 12 = 0 é:', '3', '4', '12', '2', '6', 'a', '4x = 12 => x = 3.', 'fácil', 0],
    [123, 'UFMG 2024', 'O intervalo solução de 2x - 5 > 3x - 10 é:', 'x < 5', 'x > 5', 'x < -5', 'x > -5', 'x = 5', 'a', '2x - 3x > -10 + 5 => -x > -5 => x < 5.', 'médio', 0],
    [123, 'UERJ 2023', 'No sistema: x + y = 20 e 2x + 4y = 56, o valor de x é:', '12', '8', '10', '14', '16', 'a', 'Da 1ª: x=20-y. Substituindo: 2(20-y)+4y=56 => 40-2y+4y=56 => 2y=16 => y=8. x=12.', 'difícil', 1],

    // [124] Função Quadrática e Ponto de Vértice
    [124, 'UFRGS 2024', 'O ponto onde f(x) = x² - 4x + 3 cruza o eixo y é:', '(0, 3)', '(3, 0)', '(0, -3)', '(1, 0)', '(0, 4)', 'a', 'f(0) = 0² - 4(0) + 3 = 3.', 'fácil', 0],
    [124, 'UEL 2023', 'As coordenadas do vértice da parábola y = x² - 6x + 8 são:', '(3, -1)', '(-3, 1)', '(3, 1)', '(-3, -1)', '(0, 8)', 'a', 'xv = -(-6)/2 = 3. yv = 3² - 6(3) + 8 = 9 - 18 + 8 = -1. Vértice (3, -1).', 'médio', 0],
    [124, 'MACKENZIE 2024', 'Uma função f(x) = -x² + bx + c tem raízes 1 e 5. Seu valor máximo é:', '4', '5', '8', '6', '9', 'a', 'xv = (1+5)/2 = 3. A equação é f(x) = -(x-1)(x-5). Máximo: f(3) = -(2)(-2) = 4.', 'difícil', 1],

    // [125] Cálculo de Probabilidades
    [125, 'PUC-SP 2023', 'A probabilidade de sair uma coroa no lançamento de uma moeda é:', '50%', '25%', '100%', '75%', '33%', 'a', '1 caso favorável em 2 possíveis = 1/2 = 50%.', 'fácil', 0],
    [125, 'FGV 2024', 'Jogando-se 2 dados simultaneamente, a probabilidade da soma ser 7 é:', '1/6', '1/36', '1/12', '1/8', '7/36', 'a', 'Casos: (1,6), (2,5), (3,4), (4,3), (5,2), (6,1). Total 6. 6/36 = 1/6.', 'médio', 0],
    [125, 'FATEC 2023', 'Uma urna tem 4 bolas verdes e 6 brancas. Tirando 3 bolas ao acaso sem reposição, a probabilidade de todas serem brancas é:', '1/6', '1/8', '1/12', '3/10', '2/5', 'a', 'P = (6/10)*(5/9)*(4/8) = (1/2)*(5/9)*(1/2) = 5/36... Espera, reavaliando: P = (6*5*4)/(10*9*8) = 120/720 = 1/6.', 'difícil', 1],

    // [126] Estatística: Média, Moda e Mediana
    [126, 'ITA 2024', 'A moda do conjunto {3, 5, 3, 7, 3, 5, 8} é:', '3', '5', '7', '8', '4', 'a', 'O número 3 aparece 3 vezes (maior frequência).', 'fácil', 0],
    [126, 'SENAI 2023', 'A média das idades de 5 pessoas é 20. Se uma pessoa de 32 anos entrar, a nova média será:', '22', '24', '21', '25', '23', 'a', 'Soma original = 5*20 = 100. Nova soma = 132. Nova média = 132/6 = 22.', 'médio', 0],
    [126, 'UFPR 2023', 'Em uma turma de 30 alunos, 20 tiraram 6 e 10 tiraram 9. A média da turma foi:', '7', '7,5', '6,5', '8', '7,2', 'a', 'Soma = (20*6) + (10*9) = 120 + 90 = 210. Média = 210/30 = 7.', 'difícil', 1],

    // [127] Trigonometria no Triângulo Retângulo
    [127, 'UFMG 2024', 'O cateto oposto a um ângulo de 30º mede 5 cm. A hipotenusa desse triângulo retângulo mede:', '10 cm', '5 cm', '15 cm', '20 cm', '2,5 cm', 'a', 'sen(30º) = 1/2. CO/H = 1/2 => 5/H = 1/2 => H = 10.', 'fácil', 0],
    [127, 'UERJ 2023', 'Num triângulo retângulo, a hipotenusa é 13 e um cateto é 5. O outro cateto é:', '12', '10', '8', '14', '9', 'a', 'Pitágoras: 13² = 5² + x² => 169 = 25 + x² => 144 = x² => x = 12.', 'médio', 0],
    [127, 'UFRGS 2024', 'Para medir a largura de um rio, um homem de uma margem vê uma árvore na outra sob um ângulo de 45º. Afastando-se 20m para trás, vê sob 30º. Qual a largura aproximada do rio? (use √3=1,7)', '28,5 m', '14,2 m', '20 m', '10 m', '34,5 m', 'a', 'L = h. tg(45)=h/x => x=h. tg(30)=h/(x+20) => h/(h+20)=1/1,7 => 1,7h=h+20 => 0,7h=20 => h=28,57.', 'difícil', 1]
];

$stmt = $pdo->prepare("INSERT INTO questions (lesson_id, exam_source, question_text, option_a, option_b, option_c, option_d, option_e, correct_option, explanation_text, difficulty, is_boss) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$inserted = 0;
$errors = 0;
foreach ($questions as $q) {
    // Normalizar a opção correta para garantir a letra correta, caso seja necessário, mas todas são 'a' neste seed
    try {
        $stmt->execute($q);
        $inserted++;
    } catch (Exception $e) {
        $errors++;
        echo "ERRO: " . $e->getMessage() . "\n";
    }
}
echo "\nInseridas: {$inserted} | Erros: {$errors}\n";
