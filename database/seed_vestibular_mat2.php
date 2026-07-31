<?php
require_once __DIR__ . '/../config/db.php';
echo "=== Inserindo questões de MATEMÁTICA ===\n\n";

$questions = [
    // [15] Geometria Analítica: Distância e Reta
    [15, 'ENEM 2023', 'Qual é a distância entre os pontos A(2, 3) e B(5, 7) no plano cartesiano?', '4', '5', '6', '7', '8', 'b', 'd = sqrt((5-2)^2 + (7-3)^2) = sqrt(3^2 + 4^2) = sqrt(9 + 16) = sqrt(25) = 5.', 'fácil', 0],
    [15, 'UNICAMP 2024', 'Determine a equação da reta que passa pelos pontos C(1, 2) e D(3, 6).', 'y = 2x + 1', 'y = 2x', 'y = -2x + 4', 'y = 3x - 1', 'y = x + 1', 'b', 'm = (6-2)/(3-1) = 4/2 = 2. y - 2 = 2(x - 1) => y = 2x - 2 + 2 => y = 2x.', 'médio', 0],
    [15, 'USP (FUVEST) 2023', 'Se a reta r de equação 3x + 4y - 12 = 0 intercepta os eixos X e Y nos pontos P e Q, qual o ponto médio do segmento PQ?', '(2, 3/2)', '(4/3, 3/2)', '(2, 3)', '(4, 0)', '(0, 3)', 'a', 'Intercepta x (y=0): 3x=12 => x=4, P(4,0). Intercepta y (x=0): 4y=12 => y=3, Q(0,3). Ponto médio: ((4+0)/2, (0+3)/2) = (2, 3/2).', 'médio', 0],
    [15, 'UNESP 2024', 'Qual a distância do ponto P(3, 4) à reta de equação 4x - 3y + 10 = 0?', '1', '2', '3', '4', '5', 'b', 'd = |4(3) - 3(4) + 10| / sqrt(4^2 + (-3)^2) = |12 - 12 + 10| / sqrt(25) = 10 / 5 = 2.', 'difícil', 1],
    [15, 'ENEM 2022', 'As retas y = ax + 3 e y = -2x + b são perpendiculares. Qual o valor de a?', '-2', '-1/2', '1/2', '2', '3', 'c', 'Para retas serem perpendiculares, o produto dos coeficientes angulares é -1. a * (-2) = -1 => a = 1/2.', 'fácil', 0],

    // [16] Cálculo de Probabilidades
    [16, 'ENEM 2023', 'Um dado honesto é lançado duas vezes. Qual a probabilidade de a soma dos resultados ser igual a 7?', '1/6', '1/12', '5/36', '1/9', '7/36', 'a', 'Pares que somam 7: (1,6), (2,5), (3,4), (4,3), (5,2), (6,1). São 6 casos favoráveis em 36 possíveis: 6/36 = 1/6.', 'fácil', 0],
    [16, 'UNICAMP 2024', 'Em uma urna há 4 bolas vermelhas e 6 azuis. Retirando-se duas bolas sucessivamente e sem reposição, qual a probabilidade de ambas serem vermelhas?', '2/15', '4/25', '1/5', '1/3', '4/15', 'a', 'Primeira vermelha: 4/10. Segunda vermelha: 3/9. Probabilidade: (4/10) * (3/9) = 12/90 = 2/15.', 'médio', 0],
    [16, 'USP (FUVEST) 2023', 'Lançam-se três moedas honestas simultaneamente. Qual a probabilidade de se obter pelo menos duas caras?', '1/2', '3/8', '1/4', '5/8', '1/8', 'a', 'Casos possíveis: 2^3=8. Casos favoráveis (2 ou 3 caras): (K,K,C), (K,C,K), (C,K,K), (K,K,K). Total de 4. P = 4/8 = 1/2.', 'médio', 0],
    [16, 'UNESP 2024', 'Uma senha é formada por 2 vogais distintas seguidas de 2 algarismos distintos. Se uma pessoa digitar uma senha aleatoriamente, qual a probabilidade de acertar na primeira tentativa?', '1/1800', '1/3600', '1/900', '1/720', '1/1440', 'a', 'Vogais distintas: 5*4 = 20. Algarismos distintos: 10*9 = 90. Total de senhas: 20*90 = 1800. Probabilidade: 1/1800.', 'difícil', 1],
    [16, 'ENEM 2022', 'Qual a probabilidade de escolhermos um número primo ao sortearmos um número de 1 a 20?', '2/5', '1/4', '7/20', '9/20', '1/2', 'a', 'Primos de 1 a 20: 2, 3, 5, 7, 11, 13, 17, 19. São 8 números. P = 8/20 = 2/5.', 'fácil', 0],

    // [17] Estatística: Média, Moda e Mediana
    [17, 'ENEM 2023', 'As notas de 5 alunos foram: 7, 8, 6, 9, 5. A média aritmética e a mediana são, respectivamente:', '7,0 e 7,0', '7,0 e 8,0', '6,5 e 7,0', '7,5 e 7,0', '7,0 e 6,0', 'a', 'Rol: 5, 6, 7, 8, 9. Mediana (terceiro termo) = 7. Média = (5+6+7+8+9)/5 = 35/5 = 7,0.', 'fácil', 0],
    [17, 'UNICAMP 2024', 'Em uma sapataria, as vendas de um modelo de sapato por numeração foram: 38, 39, 40, 40, 41, 41, 41, 42. Qual é a moda dessa distribuição?', '40', '41', '39', '42', '38', 'b', 'A numeração 41 aparece com maior frequência (3 vezes).', 'fácil', 0],
    [17, 'USP (FUVEST) 2023', 'Em uma turma, 10 alunos tiraram nota 6 e 20 alunos tiraram nota 9. Qual é a média de notas dessa turma?', '7,5', '7,0', '8,0', '8,5', '6,5', 'c', 'Média ponderada: (10*6 + 20*9)/30 = (60 + 180)/30 = 240/30 = 8,0.', 'médio', 0],
    [17, 'UNESP 2024', 'Se a média aritmética de 5 números é 12 e retirarmos um deles, a nova média passa a ser 10. Qual foi o número retirado?', '16', '18', '20', '22', '24', 'c', 'Soma original = 5*12 = 60. Nova soma = 4*10 = 40. Número retirado = 60 - 40 = 20.', 'difícil', 1],
    [17, 'ENEM 2022', 'Dada a sequência de dados: 2, 4, x, 8, 10. Sabendo que a média é 6, qual é a mediana?', '6', '4', '8', '5', '7', 'a', 'Média = (2+4+x+8+10)/5 = 6 => 24+x = 30 => x = 6. O rol fica: 2, 4, 6, 8, 10. Mediana é 6.', 'médio', 0],

    // [18] Desvio Padrão e Variância
    [18, 'ENEM 2023', 'A variância de um conjunto de dados é 16. Qual é o seu desvio padrão?', '2', '4', '8', '32', '256', 'b', 'O desvio padrão é a raiz quadrada da variância. sqrt(16) = 4.', 'fácil', 0],
    [18, 'UNICAMP 2024', 'Um conjunto tem valores 4, 6 e 8. A sua variância populacional é:', '8/3', '2', '4/3', '8', '4', 'a', 'Média = (4+6+8)/3 = 6. Variância = ((4-6)^2 + (6-6)^2 + (8-6)^2)/3 = (4 + 0 + 4)/3 = 8/3.', 'médio', 0],
    [18, 'USP (FUVEST) 2023', 'Se todos os dados de uma amostra forem multiplicados por 3, o que acontece com o desvio padrão?', 'Permanece igual', 'Soma-se 3', 'Multiplica-se por 3', 'Multiplica-se por 9', 'Divide-se por 3', 'c', 'O desvio padrão sofre a mesma multiplicação (em módulo) que os dados.', 'fácil', 0],
    [18, 'UNESP 2024', 'Dois grupos A e B têm mesma média. O grupo A tem dados {10, 10, 10} e o grupo B {5, 10, 15}. O desvio padrão de B é:', '0', '5', 'sqrt(50/3)', 'sqrt(100/3)', '10', 'c', 'Média de B = 10. DP = sqrt(((5-10)^2 + (10-10)^2 + (15-10)^2)/3) = sqrt((25 + 0 + 25)/3) = sqrt(50/3).', 'difícil', 1],
    [18, 'ENEM 2022', 'Qual dos conjuntos a seguir possui variância zero?', '{1, 2, 3}', '{0, 0, 0}', '{1, -1, 1}', '{2, 4, 6}', '{-2, 0, 2}', 'b', 'Variância é zero apenas quando não há dispersão, ou seja, todos os elementos são iguais.', 'fácil', 0],

    // [19] Análise Combinatória: Arranjo e Combinação
    [19, 'ENEM 2023', 'De quantas maneiras podemos escolher 3 alunos entre 10 para representar a classe?', '120', '720', '210', '90', '504', 'a', 'C(10,3) = 10! / (3!*7!) = (10*9*8)/6 = 120.', 'fácil', 0],
    [19, 'UNICAMP 2024', 'Uma prova tem 8 questões, o aluno deve escolher 5 para resolver. Quantas opções de escolha ele tem?', '336', '56', '40', '120', '24', 'b', 'C(8,5) = 8! / (5!*3!) = (8*7*6)/6 = 56.', 'fácil', 0],
    [19, 'USP (FUVEST) 2023', 'Quantos números de 3 algarismos distintos podemos formar usando os dígitos 1, 2, 3, 4, 5?', '120', '60', '125', '243', '15', 'b', 'Arranjo de 5 tomados 3 a 3. 5*4*3 = 60.', 'médio', 0],
    [19, 'UNESP 2024', 'Em uma reunião de 12 pessoas, todos trocam um aperto de mão entre si exatamente uma vez. Quantos apertos de mão ocorrem?', '144', '132', '66', '24', '12', 'c', 'Combinação de 12 dois a dois: C(12,2) = (12*11)/2 = 66.', 'difícil', 1],
    [19, 'ENEM 2022', 'De um grupo de 6 homens e 4 mulheres, deseja-se formar uma comissão de 3 pessoas, contendo exatamente 2 homens e 1 mulher. Quantas comissões são possíveis?', '60', '120', '30', '90', '24', 'a', 'C(6,2) * C(4,1) = 15 * 4 = 60.', 'médio', 0],

    // [20] Matrizes e Determinantes
    [20, 'ENEM 2023', 'O determinante da matriz A = [[2, 4], [3, x]] é zero. Qual é o valor de x?', '4', '5', '6', '7', '8', 'c', 'Det = (2*x) - (4*3) = 2x - 12 = 0 => 2x = 12 => x = 6.', 'fácil', 0],
    [20, 'UNICAMP 2024', 'Seja A e B matrizes 2x2. Sabe-se que det(A)=3 e det(B)=4. Qual é o det(A*B)?', '7', '12', '3/4', '1', '144', 'b', 'Pelo Teorema de Binet, det(AB) = det(A) * det(B) = 3 * 4 = 12.', 'fácil', 0],
    [20, 'USP (FUVEST) 2023', 'Se a matriz M tem det(M) = 5, quanto vale det(2*M), sabendo que M é de ordem 3x3?', '10', '15', '20', '30', '40', 'e', 'det(k*M) = k^n * det(M). n=3, então det(2M) = 2^3 * 5 = 8 * 5 = 40.', 'médio', 0],
    [20, 'UNESP 2024', 'A matriz inversa de [[1, 2], [3, 4]] tem soma dos elementos igual a:', '1', '-1', '1/2', '0', '-1/2', 'b', 'Det = 4-6 = -2. Inversa = (-1/2)*[[4, -2], [-3, 1]] = [[-2, 1], [3/2, -1/2]]. Soma = -2 + 1 + 1.5 - 0.5 = 0.', 'difícil', 1],
    [20, 'ENEM 2022', 'Qual o elemento c21 da matriz C = A + B, sabendo que A21 = 5 e B21 = -3?', '2', '8', '-15', '1', '-2', 'a', 'c21 = A21 + B21 = 5 + (-3) = 2.', 'fácil', 0],

    // [121] Geometria Plana: Áreas e Perímetros
    [121, 'ENEM 2023', 'Um terreno retangular tem 20m de comprimento e 15m de largura. Qual é o seu perímetro?', '35m', '70m', '300m', '150m', '40m', 'b', 'Perímetro = 2*(20+15) = 2*35 = 70m.', 'fácil', 0],
    [121, 'UNICAMP 2024', 'A área de um triângulo retângulo cujos catetos medem 6 cm e 8 cm é:', '24 cm²', '48 cm²', '14 cm²', '10 cm²', '12 cm²', 'a', 'Área = (base * altura)/2 = (6*8)/2 = 48/2 = 24 cm².', 'fácil', 0],
    [121, 'USP (FUVEST) 2023', 'A diagonal de um quadrado mede 5*sqrt(2) cm. Qual é a sua área?', '10 cm²', '20 cm²', '25 cm²', '50 cm²', '12,5 cm²', 'c', 'Diagonal d = l*sqrt(2). Então l = 5. Área = l² = 5² = 25 cm².', 'médio', 0],
    [121, 'UNESP 2024', 'Um hexágono regular tem lado medindo 4 cm. Sua área é:', '12*sqrt(3) cm²', '24*sqrt(3) cm²', '16*sqrt(3) cm²', '48*sqrt(3) cm²', '6*sqrt(3) cm²', 'b', 'Área = 6 * (l²*sqrt(3)/4) = 6 * (16*sqrt(3)/4) = 6 * 4*sqrt(3) = 24*sqrt(3) cm².', 'difícil', 1],
    [121, 'ENEM 2022', 'A área de um círculo é 36*pi cm². Qual é o comprimento de sua circunferência?', '12*pi cm', '6*pi cm', '18*pi cm', '36*pi cm', '24*pi cm', 'a', 'pi*R² = 36*pi => R = 6. Comprimento = 2*pi*R = 12*pi cm.', 'médio', 0],

    // [122] Geometria Espacial: Volume de Prismas e Cilindros
    [122, 'ENEM 2023', 'Um cilindro circular reto tem altura de 10 cm e raio da base de 3 cm. Qual o seu volume?', '30*pi cm³', '60*pi cm³', '90*pi cm³', '180*pi cm³', '270*pi cm³', 'c', 'V = pi*R²*h = pi * 3² * 10 = 90*pi cm³.', 'fácil', 0],
    [122, 'UNICAMP 2024', 'O volume de um cubo é 64 cm³. Quanto mede sua aresta?', '2 cm', '4 cm', '6 cm', '8 cm', '16 cm', 'b', 'V = a³ = 64 => a = raiz_cúbica(64) = 4 cm.', 'fácil', 0],
    [122, 'USP (FUVEST) 2023', 'Um prisma de base retangular tem dimensões 2 cm, 3 cm e 5 cm. Qual seu volume?', '10 cm³', '15 cm³', '20 cm³', '30 cm³', '60 cm³', 'd', 'V = a*b*c = 2 * 3 * 5 = 30 cm³.', 'fácil', 0],
    [122, 'UNESP 2024', 'A área lateral de um cilindro equilátero é 100*pi cm². Seu volume é:', '125*pi cm³', '250*pi cm³', '500*pi cm³', '1000*pi cm³', '750*pi cm³', 'b', 'Cilindro equilátero: h = 2R. A_lateral = 2*pi*R*h = 2*pi*R*2R = 4*pi*R² = 100*pi => R² = 25 => R = 5, h = 10. V = pi*R²*h = pi*25*10 = 250*pi cm³.', 'difícil', 1],
    [122, 'ENEM 2022', 'Qual o volume da água em um aquário de base 40cm x 50cm que está preenchido até 30cm de altura?', '60.000 cm³', '6.000 cm³', '1.200 cm³', '20.000 cm³', '120.000 cm³', 'a', 'V = base * altura * prof = 40 * 50 * 30 = 60.000 cm³.', 'médio', 0],

    // [123] Equações e Inequações do 1º Grau
    [123, 'ENEM 2023', 'Qual a raiz da equação 3x - 12 = 0?', '2', '3', '4', '5', '6', 'c', '3x = 12 => x = 4.', 'fácil', 0],
    [123, 'UNICAMP 2024', 'Resolva a inequação 2x + 5 < x + 9:', 'x < 4', 'x > 4', 'x < -4', 'x > -4', 'x = 4', 'a', '2x - x < 9 - 5 => x < 4.', 'fácil', 0],
    [123, 'USP (FUVEST) 2023', 'O dobro de um número menos 7 é igual a 15. Que número é esse?', '8', '9', '10', '11', '12', 'd', '2x - 7 = 15 => 2x = 22 => x = 11.', 'médio', 0],
    [123, 'UNESP 2024', 'A soma de três números consecutivos é igual a 51. Qual o maior deles?', '16', '17', '18', '19', '20', 'c', 'x + (x+1) + (x+2) = 51 => 3x + 3 = 51 => 3x = 48 => x = 16. O maior é x+2 = 18.', 'difícil', 1],
    [123, 'ENEM 2022', 'Se 3(x - 2) = 2x + 1, qual o valor de x?', '5', '6', '7', '8', '9', 'c', '3x - 6 = 2x + 1 => x = 7.', 'médio', 0],

    // [124] Função Quadrática e Ponto de Vértice
    [124, 'ENEM 2023', 'Qual a coordenada do X do vértice da parábola y = x² - 6x + 5?', '-3', '3', '6', '-6', '5', 'b', 'Xv = -b / 2a = -(-6) / (2*1) = 6/2 = 3.', 'fácil', 0],
    [124, 'UNICAMP 2024', 'Qual o valor máximo assumido pela função y = -x² + 4x + 12?', '12', '14', '16', '18', '20', 'c', 'Xv = -4 / -2 = 2. Yv = -(2)² + 4(2) + 12 = -4 + 8 + 12 = 16.', 'médio', 0],
    [124, 'USP (FUVEST) 2023', 'As raízes da função f(x) = x² - 5x + 6 são:', '1 e 6', '2 e 3', '-2 e -3', '1 e 5', '-1 e -6', 'b', 'Por soma e produto: S=5, P=6. As raízes são 2 e 3.', 'fácil', 0],
    [124, 'UNESP 2024', 'O gráfico de f(x) = ax² + bx + c intercepta o eixo Y em (0,3), seu vértice é (2, -1). Qual o valor de a?', '1', '2', '3', '4', '5', 'a', 'c = 3. Yv = -1, Xv = 2. f(x) = a(x-2)² - 1. f(0) = a(-2)² - 1 = 3 => 4a = 4 => a = 1.', 'difícil', 1],
    [124, 'ENEM 2022', 'Em qual ponto a parábola y = 2x² - 8x + 6 cruza o eixo y?', '(0, 2)', '(0, -8)', '(0, 6)', '(6, 0)', '(2, 0)', 'c', 'O cruzamento no eixo y ocorre quando x=0. y = 2(0)² - 8(0) + 6 = 6. Ponto (0, 6).', 'fácil', 0],

    // [125] Cálculo de Probabilidades (Duplicated lesson ID but different questions requested)
    [125, 'ENEM 2023', 'Ao jogar uma moeda 4 vezes, qual a probabilidade de dar apenas caras?', '1/2', '1/4', '1/8', '1/16', '1/32', 'd', 'P = (1/2)^4 = 1/16.', 'fácil', 0],
    [125, 'UNICAMP 2024', 'De um baralho de 52 cartas, tira-se uma. Qual a probabilidade de ser um Ás?', '1/13', '1/52', '4/13', '1/4', '3/52', 'a', 'Existem 4 Ases em 52 cartas. P = 4/52 = 1/13.', 'fácil', 0],
    [125, 'USP (FUVEST) 2023', 'Tirando duas cartas sucessivamente (sem reposição) de um baralho, qual a chance da 1ª ser copas e a 2ª espadas?', '13/204', '1/16', '1/4', '169/2704', '13/102', 'a', '(13/52) * (13/51) = (1/4) * (13/51) = 13/204.', 'médio', 0],
    [125, 'UNESP 2024', 'Uma prova tem 5 alternativas por questão. Se um aluno chutar 3 questões, qual a probabilidade de acertar todas?', '1/15', '1/125', '3/125', '1/5', '3/5', 'b', 'P = (1/5) * (1/5) * (1/5) = 1/125.', 'difícil', 1],
    [125, 'ENEM 2022', 'Em um sorteio de 1 a 100, qual a probabilidade de ser um múltiplo de 10?', '1/5', '1/10', '1/20', '1/2', '1/100', 'b', 'Os múltiplos de 10 até 100 são 10,20,...,100 (10 números). P = 10/100 = 1/10.', 'fácil', 0],

    // [126] Estatística: Média, Moda e Mediana (Duplicated lesson ID but different questions requested)
    [126, 'ENEM 2023', 'A média das idades de 3 amigos é 15 anos. Se mais um amigo de 19 anos se juntar, qual a nova média?', '15', '16', '17', '18', '19', 'b', 'Soma = 3*15 = 45. Nova soma = 45+19 = 64. Nova média = 64/4 = 16.', 'médio', 0],
    [126, 'UNICAMP 2024', 'Dados os números {3, 7, 7, 9, 14}, a mediana é:', '7', '9', '8', '6', '14', 'a', 'A mediana é o termo central do rol de 5 termos: 7.', 'fácil', 0],
    [126, 'USP (FUVEST) 2023', 'As vendas diárias de um vendedor foram: R$100, R$200, R$100, R$300, R$100. A moda é:', 'R$100', 'R$200', 'R$300', 'R$150', 'R$250', 'a', 'R$100 é o valor mais frequente (3 vezes).', 'fácil', 0],
    [126, 'UNESP 2024', 'A média de dois números é 10. Se um deles for o dobro do outro, o menor número é:', '20/3', '10/3', '5', '10', '2', 'a', '(x + 2x)/2 = 10 => 3x = 20 => x = 20/3.', 'difícil', 1],
    [126, 'ENEM 2022', 'Em uma avaliação, os pesos de duas provas são 2 e 3. Se um aluno tirou 5 e 10, sua média final é:', '7,0', '7,5', '8,0', '8,5', '9,0', 'c', '(5*2 + 10*3)/5 = (10 + 30)/5 = 40/5 = 8,0.', 'médio', 0],

    // [127] Trigonometria no Triângulo Retângulo
    [127, 'ENEM 2023', 'Em um triângulo retângulo, o cateto oposto a um ângulo mede 3 cm e a hipotenusa 5 cm. O seno desse ângulo é:', '3/5', '4/5', '3/4', '4/3', '5/3', 'a', 'Seno = cateto oposto / hipotenusa = 3/5.', 'fácil', 0],
    [127, 'UNICAMP 2024', 'Se tg(θ) = 1 e θ está no primeiro quadrante, qual o valor de θ?', '30º', '45º', '60º', '90º', '0º', 'b', 'A tangente vale 1 quando o seno e o cosseno são iguais, ocorrendo em 45º.', 'fácil', 0],
    [127, 'USP (FUVEST) 2023', 'Uma escada de 10m está encostada numa parede, fazendo 60º com o chão. A que altura a escada toca a parede?', '5m', '5*sqrt(3)m', '10m', '10*sqrt(3)m', '8,6m', 'b', 'Seno(60º) = h / 10 => sqrt(3)/2 = h / 10 => h = 10*sqrt(3)/2 = 5*sqrt(3)m.', 'médio', 0],
    [127, 'UNESP 2024', 'Em um triângulo retângulo, um cateto é metade da hipotenusa. Qual o menor ângulo deste triângulo?', '15º', '30º', '45º', '60º', '75º', 'b', 'Seno(a) = c/h = (h/2)/h = 1/2. Logo, a = 30º.', 'difícil', 1],
    [127, 'ENEM 2022', 'Qual o cosseno de um ângulo cujo seno é 0,6 no primeiro quadrante?', '0,4', '0,5', '0,6', '0,8', '1,0', 'd', 'sen² + cos² = 1 => 0,36 + cos² = 1 => cos² = 0,64 => cos = 0,8.', 'médio', 0],

];

$stmt = $pdo->prepare("INSERT INTO questions (lesson_id, exam_source, question_text, option_a, option_b, option_c, option_d, option_e, correct_option, explanation_text, difficulty, is_boss) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$inserted = 0;
$errors = 0;
foreach ($questions as $q) {
    try { 
        $stmt->execute($q); 
        $inserted++; 
    } catch (Exception $e) { 
        $errors++; 
        echo "ERRO: " . $e->getMessage() . "\n"; 
    }
}
echo "\nInseridas: {$inserted} | Erros: {$errors}\n";
