<?php
require_once __DIR__ . '/../config/db.php';
echo "=== Inserindo questões de Física Extra ===\n\n";

$questions = [
    // [21] Cinemática: Velocidade Média e MRU
    [21, 'UFPR 2023', 'Um corredor completa uma prova de 10 km em 50 minutos. Sua velocidade média em km/h é de:', '12 km/h', '10 km/h', '15 km/h', '20 km/h', '8 km/h', 'a', 'ΔS = 10 km, Δt = 50/60 h = 5/6 h. Vm = 10 / (5/6) = 12 km/h.', 'fácil', 0],
    [21, 'UFMG 2024', 'Dois carros, A e B, movem-se na mesma direção com velocidades constantes de 60 km/h e 80 km/h. Se A parte 1 hora antes de B, quanto tempo B leva para alcançar A?', '3 horas', '2 horas', '4 horas', '1,5 horas', '2,5 horas', 'a', 'Em 1h, A percorre 60 km. A velocidade relativa de aproximação de B é 80 - 60 = 20 km/h. Tempo = 60/20 = 3 horas.', 'médio', 0],
    [21, 'UERJ 2023', 'Um trem de 200 m de comprimento viaja a 72 km/h. Quanto tempo ele demora para atravessar completamente uma ponte de 400 m?', '30 s', '20 s', '15 s', '40 s', '50 s', 'a', '72 km/h = 20 m/s. ΔS total = 200 m + 400 m = 600 m. Δt = ΔS/V = 600/20 = 30 s.', 'difícil', 1],

    // [22] MUV
    [22, 'UFRGS 2024', 'Um carro freia de 30 m/s até parar, com desaceleração de 3 m/s². Qual a distância percorrida durante a frenagem?', '150 m', '100 m', '200 m', '300 m', '90 m', 'a', 'Equação de Torricelli: v² = v0² + 2aΔS. 0 = 30² - 2(3)ΔS => 6ΔS = 900 => ΔS = 150 m.', 'fácil', 0],
    [22, 'UEL 2023', 'Uma partícula parte do repouso e adquire aceleração constante de 4 m/s². Qual sua velocidade após percorrer 32 m?', '16 m/s', '8 m/s', '12 m/s', '20 m/s', '24 m/s', 'a', 'v² = 0 + 2(4)(32) = 256. v = 16 m/s.', 'médio', 0],
    [22, 'UEM 2024', 'Um móvel obedece à equação horária s(t) = 10 - 2t + t² (SI). Em que instante ele passa pela origem das posições?', 'O móvel não passa pela origem', '2 s', '1 s', '5 s', '3 s', 'a', '10 - 2t + t² = 0. Δ = 4 - 40 = -36. Não há raiz real, logo não passa pela origem (s=0).', 'difícil', 1],

    // [23] Queda Livre e Lançamento Vertical
    [23, 'MACKENZIE 2023', 'Uma pedra é abandonada de 45 m de altura (g=10 m/s²). O tempo de queda é:', '3 s', '2 s', '4 s', '5 s', '1,5 s', 'a', 'h = g.t²/2 => 45 = 5t² => t² = 9 => t = 3 s.', 'fácil', 0],
    [23, 'PUC-RJ 2024', 'Um objeto é lançado verticalmente para cima com velocidade de 20 m/s. Qual a altura máxima atingida? (g=10 m/s²)', '20 m', '40 m', '10 m', '30 m', '50 m', 'a', 'Na altura máxima v=0. v² = v0² - 2gh => 0 = 400 - 20h => h = 20 m.', 'médio', 0],
    [23, 'ITA 2023', 'Uma bola é solta de uma altura H e percorre metade da distância total no último segundo de queda. Sendo g=10 m/s², o tempo total de queda é de aproximadamente:', '3,4 s', '2,0 s', '5,8 s', '4,5 s', '1,4 s', 'a', 'H = 5t². H/2 = 5(t-1)². Logo 2,5t² = 5(t-1)² => t² = 2(t² - 2t + 1) => t² - 4t + 2 = 0. Raiz positiva: (4 + √8)/2 ≈ 3,4 s.', 'difícil', 1],

    // [24] Dinâmica: Leis de Newton
    [24, 'IME 2024', 'Um corpo de 5 kg é puxado horizontalmente por uma força de 30 N sobre uma superfície sem atrito. A aceleração é:', '6 m/s²', '5 m/s²', '10 m/s²', '15 m/s²', '2 m/s²', 'a', 'Fr = m.a => 30 = 5.a => a = 6 m/s².', 'fácil', 0],
    [24, 'FATEC 2023', 'Dois blocos A (3 kg) e B (2 kg) estão em contato sobre uma superfície sem atrito. Uma força de 15 N é aplicada em A. A força de contato entre os blocos vale:', '6 N', '9 N', '15 N', '10 N', '3 N', 'a', 'a = 15/(3+2) = 3 m/s². A força no bloco B é a única responsável pela sua aceleração: F = mB.a = 2.3 = 6 N.', 'médio', 0],
    [24, 'SENAI 2024', 'Um elevador de 1000 kg desce com aceleração de 2 m/s² (g=10 m/s²). A tração no cabo que o sustenta é:', '8000 N', '12000 N', '10000 N', '2000 N', '9000 N', 'a', 'P - T = m.a => 10000 - T = 1000.2 => T = 8000 N.', 'difícil', 1],

    // [25] Atrito e Força Centrípeta
    [25, 'UFPR 2023', 'Um bloco de 10 kg está na iminência de se mover sobre um plano horizontal quando puxado por uma força de 40 N. O coeficiente de atrito estático é (g=10 m/s²):', '0,4', '0,5', '0,2', '0,8', '1,0', 'a', 'Fat = 40 N. N = 100 N. μ = Fat/N = 40/100 = 0,4.', 'fácil', 0],
    [25, 'UFMG 2024', 'Um carro de 1000 kg faz uma curva plana de raio 50 m a 20 m/s. A força de atrito lateral necessária é:', '8000 N', '10000 N', '4000 N', '5000 N', '2000 N', 'a', 'Fcp = m.v²/R = 1000.400/50 = 8000 N.', 'médio', 0],
    [25, 'UERJ 2023', 'No topo de um looping de raio R = 10 m, a velocidade mínima para um carrinho de montanha russa não cair é (g=10 m/s²):', '10 m/s', '5 m/s', '15 m/s', '20 m/s', '2 m/s', 'a', 'Para velocidade mínima, Normal=0. P = Fcp => mg = m.v²/R => v = √(gR) = √(10.10) = 10 m/s.', 'difícil', 1],

    // [26] Trabalho e Energia
    [26, 'UFRGS 2024', 'Um corpo de 2 kg é erguido a 5 m de altura. O trabalho realizado pela força peso é (g=10 m/s²):', '-100 J', '100 J', '50 J', '-50 J', '200 J', 'a', 'W = -mgh = -2.10.5 = -100 J (trabalho resistente).', 'fácil', 0],
    [26, 'UEL 2023', 'Uma mola de constante k = 200 N/m é comprimida em 0,1 m. A energia potencial elástica armazenada é:', '1 J', '2 J', '10 J', '0,5 J', '5 J', 'a', 'Epe = kx²/2 = 200.(0,1)²/2 = 200.0,01/2 = 1 J.', 'médio', 0],
    [26, 'UEM 2024', 'Um bloco de 1 kg é abandonado de uma rampa a 5 m de altura. Ele atinge a base com 8 m/s. A energia dissipada pelo atrito foi de:', '18 J', '50 J', '32 J', '8 J', '10 J', 'a', 'Em = mgh = 1.10.5 = 50 J. Ec final = 1.8²/2 = 32 J. Dissipada = 50 - 32 = 18 J.', 'difícil', 1],

    // [27] Impulso e Quantidade de Movimento
    [27, 'MACKENZIE 2023', 'Uma força constante de 50 N atua sobre um corpo por 2 s. O impulso gerado é:', '100 N.s', '50 N.s', '25 N.s', '200 N.s', '10 N.s', 'a', 'I = F.Δt = 50 . 2 = 100 N.s.', 'fácil', 0],
    [27, 'PUC-RJ 2024', 'Uma bola de 0,5 kg colide perpendicularmente com uma parede a 10 m/s e retorna a 8 m/s na mesma direção. A variação da quantidade de movimento é:', '9 kg.m/s', '1 kg.m/s', '4 kg.m/s', '5 kg.m/s', '18 kg.m/s', 'a', 'ΔQ = m.v_f - m.v_i. Adotando sentido de volta como positivo: 0,5(8) - 0,5(-10) = 4 - (-5) = 9 kg.m/s.', 'médio', 0],
    [27, 'ITA 2023', 'Um carrinho de 2 kg a 5 m/s choca-se de forma perfeitamente inelástica com outro de 3 kg inicialmente em repouso. A velocidade do conjunto após a colisão é:', '2 m/s', '5 m/s', '3 m/s', '1 m/s', '2,5 m/s', 'a', 'Q_antes = 2.5 + 3.0 = 10. Q_depois = (2+3)V = 5V. 5V = 10 => V = 2 m/s.', 'difícil', 1],

    // [28] Hidrostática
    [28, 'IME 2024', 'Um mergulhador atinge 20 m de profundidade num lago (d=1000 kg/m³, g=10 m/s², Patm=10^5 Pa). A pressão total que ele suporta é:', '3.10^5 Pa', '2.10^5 Pa', '1.10^5 Pa', '4.10^5 Pa', '5.10^5 Pa', 'a', 'P = Patm + d.g.h = 10^5 + 1000.10.20 = 10^5 + 2.10^5 = 3.10^5 Pa.', 'fácil', 0],
    [28, 'FATEC 2023', 'Um objeto de volume 0,002 m³ e densidade 800 kg/m³ é totalmente submerso em água (d=1000 kg/m³). O empuxo é:', '20 N', '16 N', '25 N', '10 N', '5 N', 'a', 'E = d_liq.V.g = 1000.0,002.10 = 20 N.', 'médio', 0],
    [28, 'SENAI 2024', 'Uma prancha flutua com 70% de seu volume submerso na água (1000 kg/m³). A densidade da prancha é:', '700 kg/m³', '300 kg/m³', '800 kg/m³', '1000 kg/m³', '500 kg/m³', 'a', 'P = E => d_corpo.V.g = d_liq.(0,7V).g => d_corpo = 0,7.1000 = 700 kg/m³.', 'difícil', 1],

    // [29] Pascal
    [29, 'UFPR 2023', 'Num elevador hidráulico, o pistão menor tem área A e o maior 10A. Para erguer um carro de 10000 N, a força aplicada no pistão menor deve ser:', '1000 N', '10000 N', '100 N', '5000 N', '2000 N', 'a', 'F1/A1 = F2/A2 => F1/A = 10000/10A => F1 = 1000 N.', 'fácil', 0],
    [29, 'UFMG 2024', 'Dois vasos comunicantes contêm água e óleo (d=800 kg/m³). A coluna de óleo tem 20 cm. A diferença de nível da água em relação à interface é:', '16 cm', '20 cm', '25 cm', '10 cm', '12 cm', 'a', 'P_agua = P_oleo => 1000.h = 800.20 => 1000h = 16000 => h = 16 cm.', 'médio', 0],
    [29, 'UERJ 2023', 'Aplica-se uma força de 50 N no êmbolo de uma seringa de área 2 cm². A variação de pressão transmitida ao fluido é:', '250.000 Pa', '100.000 Pa', '50.000 Pa', '2.500 Pa', '500.000 Pa', 'a', 'ΔP = F/A = 50 / (2.10^-4) = 25.10^4 = 250.000 Pa.', 'difícil', 1],

    // [30] Gravitação
    [30, 'UFRGS 2024', 'Pela Terceira Lei de Kepler, o cubo do raio médio da órbita de um planeta é proporcional ao:', 'Quadrado de seu período', 'Cubo de seu período', 'Quadrado de sua massa', 'Sua massa', 'Seu período orbital', 'a', 'T²/R³ = constante, logo R³ é proporcional a T².', 'fácil', 0],
    [30, 'UEL 2023', 'A força gravitacional entre duas massas M e m separadas por r é F. Se a distância cair pela metade, a nova força será:', '4F', '2F', 'F/2', 'F/4', '8F', 'a', 'F = G.M.m/r². Se r\' = r/2, F\' = G.M.m / (r/2)² = 4.G.M.m/r² = 4F.', 'médio', 0],
    [30, 'UEM 2024', 'A aceleração da gravidade na superfície da Terra é g. Num planeta com o dobro da massa e o dobro do raio, a gravidade seria:', 'g/2', 'g', '2g', '4g', 'g/4', 'a', 'g = GM/R². g\' = G(2M)/(2R)² = 2GM/4R² = (GM/R²)/2 = g/2.', 'difícil', 1],

    // [31] Termometria
    [31, 'MACKENZIE 2023', 'Uma temperatura de 40 ºC corresponde na escala Fahrenheit a:', '104 ºF', '80 ºF', '72 ºF', '120 ºF', '96 ºF', 'a', 'C/5 = (F-32)/9 => 40/5 = (F-32)/9 => 8 = (F-32)/9 => 72 = F - 32 => F = 104.', 'fácil', 0],
    [31, 'PUC-RJ 2024', 'Em qual temperatura as escalas Celsius e Fahrenheit marcam o mesmo valor numérico?', '-40', '40', '-20', '0', '-10', 'a', 'x/5 = (x-32)/9 => 9x = 5x - 160 => 4x = -160 => x = -40.', 'médio', 0],
    [31, 'ITA 2023', 'Uma escala arbitrária X marca 10 ºX para a fusão do gelo e 80 ºX para a ebulição da água. Uma temperatura de 45 ºX corresponde em Celsius a:', '50 ºC', '45 ºC', '40 ºC', '60 ºC', '35 ºC', 'a', '(X-10)/(80-10) = C/100 => (45-10)/70 = C/100 => 35/70 = C/100 => 0,5 = C/100 => C = 50 ºC.', 'difícil', 1],

    // [32] Calorimetria
    [32, 'IME 2024', 'Quantas calorias são necessárias para aquecer 200 g de água (c=1 cal/gºC) de 20 ºC a 80 ºC?', '12000 cal', '8000 cal', '16000 cal', '10000 cal', '20000 cal', 'a', 'Q = m.c.Δθ = 200 . 1 . 60 = 12000 cal.', 'fácil', 0],
    [32, 'FATEC 2023', 'Misturam-se 100 g de água a 80 ºC com 300 g de água a 20 ºC. Qual a temperatura de equilíbrio térmico?', '35 ºC', '40 ºC', '45 ºC', '50 ºC', '30 ºC', 'a', 'm1.c(T-80) + m2.c(T-20) = 0 => 100(T-80) + 300(T-20) = 0 => T-80 + 3T-60 = 0 => 4T = 140 => T = 35 ºC.', 'médio', 0],
    [32, 'SENAI 2024', 'Um aquecedor fornece calor a 200 cal/s. Quanto tempo ele leva para derreter totalmente 100 g de gelo a 0 ºC (L=80 cal/g)?', '40 s', '20 s', '80 s', '100 s', '50 s', 'a', 'Q = m.L = 100.80 = 8000 cal. P = Q/t => 200 = 8000/t => t = 40 s.', 'difícil', 1],

    // [33] Termodinâmica
    [33, 'UFPR 2023', 'Um gás ideal recebe 500 J de calor e realiza 200 J de trabalho. A variação da sua energia interna é:', '300 J', '700 J', '500 J', '200 J', '1000 J', 'a', 'ΔU = Q - W = 500 - 200 = 300 J.', 'fácil', 0],
    [33, 'UFMG 2024', 'Uma máquina térmica ideal de Carnot opera entre 300 K e 600 K. Seu rendimento é de:', '50%', '30%', '40%', '60%', '20%', 'a', 'Rendimento η = 1 - T_fria/T_quente = 1 - 300/600 = 1 - 0,5 = 0,5 = 50%.', 'médio', 0],
    [33, 'UERJ 2023', 'Um gás se expande isobaricamente a 2 atm (2.10^5 Pa), passando de um volume de 0,1 m³ para 0,3 m³. O trabalho realizado é:', '40000 J', '20000 J', '60000 J', '10000 J', '80000 J', 'a', 'W = P.ΔV = 2.10^5 . (0,3 - 0,1) = 2.10^5 . 0,2 = 40.000 J.', 'difícil', 1],

    // [34] Óptica
    [34, 'UFRGS 2024', 'Um raio de luz incide num espelho plano com ângulo de 30º em relação à superfície. O ângulo de reflexão é:', '60º', '30º', '90º', '15º', '120º', 'a', 'O ângulo de incidência é com a normal: 90 - 30 = 60º. Pela lei da reflexão, r = i = 60º.', 'fácil', 0],
    [34, 'UEL 2023', 'Um objeto de 5 cm é colocado a 20 cm de um espelho côncavo de foco 10 cm. A posição da imagem é:', '20 cm do espelho', '-20 cm do espelho', '10 cm do espelho', '30 cm do espelho', '-10 cm do espelho', 'a', '1/f = 1/p + 1/p\' => 1/10 = 1/20 + 1/p\' => 1/p\' = 1/10 - 1/20 = 1/20 => p\' = 20 cm.', 'médio', 0],
    [34, 'UEM 2024', 'Dois espelhos planos formam entre si um ângulo de 72º. O número de imagens formadas para um objeto entre eles é:', '4', '5', '6', '7', '3', 'a', 'n = (360/α) - 1 = (360/72) - 1 = 5 - 1 = 4 imagens.', 'difícil', 1],

    // [35] Refração e Lentes
    [35, 'MACKENZIE 2023', 'O índice de refração do vidro é 1,5. Sendo c = 3.10^8 m/s, a velocidade da luz no vidro é:', '2.10^8 m/s', '1,5.10^8 m/s', '3.10^8 m/s', '4,5.10^8 m/s', '2,5.10^8 m/s', 'a', 'n = c/v => 1,5 = 3.10^8 / v => v = 3.10^8 / 1,5 = 2.10^8 m/s.', 'fácil', 0],
    [35, 'PUC-RJ 2024', 'A vergência de uma lente de distância focal 50 cm é:', '2 di', '5 di', '0,5 di', '20 di', '10 di', 'a', 'f = 50 cm = 0,5 m. Vergência V = 1/f = 1/0,5 = 2 di (dioptrias).', 'médio', 0],
    [35, 'ITA 2023', 'Um objeto está a 30 cm de uma lente convergente de f = 20 cm. O aumento linear transversal é:', '-2', '-0,5', '2', '0,5', '-1,5', 'a', '1/20 = 1/30 + 1/p\' => 1/p\' = 1/20 - 1/30 = 1/60 => p\' = 60 cm. A = -p\'/p = -60/30 = -2.', 'difícil', 1],

    // [36] Eletrostática
    [36, 'IME 2024', 'A força elétrica entre duas cargas de 2 μC e 3 μC separadas por 0,3 m no vácuo (K=9.10^9) é:', '0,6 N', '6 N', '0,06 N', '60 N', '1,2 N', 'a', 'F = K.Q.q/d² = 9.10^9 . 2.10^-6 . 3.10^-6 / (0,3)² = 54.10^-3 / 9.10^-2 = 6.10^-1 = 0,6 N.', 'fácil', 0],
    [36, 'FATEC 2023', 'O campo elétrico gerado por uma carga puntiforme de 4 μC a uma distância de 2 m (K=9.10^9) tem módulo:', '9.10^3 N/C', '18.10^3 N/C', '36.10^3 N/C', '4,5.10^3 N/C', '12.10^3 N/C', 'a', 'E = K|Q|/d² = 9.10^9 . 4.10^-6 / 4 = 9.10^3 N/C.', 'médio', 0],
    [36, 'SENAI 2024', 'Duas esferas idênticas têm cargas de -2 μC e +8 μC. Após contato, qual a nova carga de cada uma?', '+3 μC', '+6 μC', '+4 μC', '-2 μC', '0 μC', 'a', 'Q_final = (Q1 + Q2)/2 = (-2 + 8)/2 = +6/2 = +3 μC.', 'difícil', 1],

    // [37] Circuitos
    [37, 'UFPR 2023', 'Um resistor de 10 Ω é percorrido por 2 A. A tensão nos seus terminals é:', '20 V', '5 V', '12 V', '40 V', '10 V', 'a', 'U = R.i = 10 . 2 = 20 V.', 'fácil', 0],
    [37, 'UFMG 2024', 'Dois resistores de 6 Ω e 3 Ω estão em paralelo. A resistência equivalente é:', '2 Ω', '9 Ω', '4,5 Ω', '18 Ω', '3 Ω', 'a', 'Req = (6.3)/(6+3) = 18/9 = 2 Ω.', 'médio', 0],
    [37, 'UERJ 2023', 'Três resistores de 2 Ω estão conectados de forma que dois estão em paralelo e o conjunto em série com o terceiro. O Req é:', '3 Ω', '6 Ω', '1 Ω', '4 Ω', '1,5 Ω', 'a', 'Dois em paralelo: Req1 = 2/2 = 1 Ω. Em série com o terceiro: 1 + 2 = 3 Ω.', 'difícil', 1],

    // [38] Geradores e Potência
    [38, 'UFRGS 2024', 'Um chuveiro tem potência de 4400 W e funciona a 220 V. A corrente que o atravessa é:', '20 A', '10 A', '40 A', '30 A', '25 A', 'a', 'P = U.i => 4400 = 220.i => i = 20 A.', 'fácil', 0],
    [38, 'UEL 2023', 'Um gerador de fem 12 V e resistência interna 1 Ω alimenta um resistor de 5 Ω. A corrente no circuito é:', '2 A', '12 A', '3 A', '2,4 A', '6 A', 'a', 'i = E / (R + r) = 12 / (5 + 1) = 12 / 6 = 2 A.', 'médio', 0],
    [38, 'UEM 2024', 'Um aparelho de 1000 W fica ligado 2 horas por dia. O consumo de energia elétrica em 30 dias é:', '60 kWh', '30 kWh', '120 kWh', '2000 kWh', '10 kWh', 'a', 'E = P.t = 1000 W . 2 h . 30 = 60000 Wh = 60 kWh.', 'difícil', 1],

    // [39] Ondulatória
    [39, 'MACKENZIE 2023', 'Uma onda tem velocidade de 340 m/s e frequência de 170 Hz. Seu comprimento de onda é:', '2 m', '1 m', '0,5 m', '3 m', '340 m', 'a', 'v = λ.f => 340 = λ.170 => λ = 2 m.', 'fácil', 0],
    [39, 'PUC-RJ 2024', 'O período de um pêndulo simples é de 4 s. Sua frequência é:', '0,25 Hz', '4 Hz', '2 Hz', '0,5 Hz', '1 Hz', 'a', 'f = 1/T = 1/4 = 0,25 Hz.', 'médio', 0],
    [39, 'ITA 2023', 'Uma corda de 2 m tem uma onda estacionária no 3º harmônico. O comprimento de onda é:', '1,33 m', '2 m', '0,66 m', '1 m', '1,5 m', 'a', 'L = n.λ/2 => 2 = 3.λ/2 => 4 = 3.λ => λ = 4/3 = 1,33 m.', 'difícil', 1],

    // [40] Acústica
    [40, 'IME 2024', 'O nível sonoro de um som cuja intensidade é 10^-6 W/m² (I0=10^-12 W/m²) é:', '60 dB', '40 dB', '80 dB', '100 dB', '50 dB', 'a', 'N = 10.log(I/I0) = 10.log(10^-6/10^-12) = 10.log(10^6) = 10.6 = 60 dB.', 'fácil', 0],
    [40, 'FATEC 2023', 'Qual a qualidade fisiológica do som que permite diferenciar notas musicais de diferentes instrumentos?', 'Timbre', 'Altura', 'Intensidade', 'Frequência', 'Comprimento de onda', 'a', 'O timbre é a qualidade que permite distinguir fontes sonoras diferentes emitindo a mesma nota (frequência).', 'médio', 0],
    [40, 'SENAI 2024', 'Uma ambulância a 34 m/s (v_som=340 m/s) aproxima-se de um observador parado. A sirene emite 600 Hz. A frequência aparente ouvida é:', '666 Hz', '600 Hz', '545 Hz', '680 Hz', '720 Hz', 'a', 'f\' = f [vs / (vs - v_fonte)] = 600 [340 / (340 - 34)] = 600 [340/306] ≈ 666,6 Hz. Valor mais próximo 666 Hz.', 'difícil', 1],

    // [128] Trabalho e Energia
    [128, 'UFPR 2023', 'Um motorista aplica os freios de um carro de 800 kg a 20 m/s até parar. O trabalho total das forças de atrito foi:', '-160000 J', '160000 J', '-80000 J', '80000 J', '-40000 J', 'a', 'W = ΔEc = 0 - 800.20²/2 = -800.400/2 = -160000 J.', 'fácil', 0],
    [128, 'UFMG 2024', 'A potência média de um guindaste para erguer um bloco de 500 kg a 10 m em 20 s (g=10 m/s²) é:', '2500 W', '5000 W', '1250 W', '1000 W', '200 W', 'a', 'W = mgh = 500.10.10 = 50000 J. P = W/t = 50000/20 = 2500 W.', 'médio', 0],
    [128, 'UERJ 2023', 'Uma partícula está sob a ação de uma força F(x) = 2x. O trabalho de x=0 até x=4 m é:', '16 J', '8 J', '32 J', '4 J', '12 J', 'a', 'W é a área do gráfico F x x (triângulo). Base=4, Altura=8. W = 4.8/2 = 16 J.', 'difícil', 1],

    // [129] Hidrostática
    [129, 'UFRGS 2024', 'A diferença de pressão entre dois pontos de uma piscina, separados por 3 m de profundidade (d=1000 kg/m³, g=10 m/s²) é:', '30000 Pa', '3000 Pa', '10000 Pa', '300 Pa', '60000 Pa', 'a', 'ΔP = d.g.Δh = 1000 . 10 . 3 = 30000 Pa.', 'fácil', 0],
    [129, 'UEL 2023', 'Uma prensa hidráulica tem êmbolos de raios R e 3R. A força F2 no êmbolo maior para uma força F1=20 N no menor é:', '180 N', '60 N', '20 N', '90 N', '120 N', 'a', 'As áreas são proporcionais ao quadrado do raio (A2/A1 = 9). F2/F1 = 9 => F2 = 9.20 = 180 N.', 'médio', 0],
    [129, 'UEM 2024', 'Um cubo de gelo flutua num copo com água até a borda. Quando o gelo derrete, o nível da água:', 'Permanece inalterado', 'Transborda um pouco', 'Desce ligeiramente', 'Transborda muito', 'Aumenta, mas não transborda', 'a', 'O volume de água proveniente do gelo derretido é igual ao volume submerso inicial, logo o nível não se altera.', 'difícil', 1],

    // [130] Termometria
    [130, 'MACKENZIE 2023', 'Uma variação de 20 ºC corresponde na escala Fahrenheit a uma variação de:', '36 ºF', '20 ºF', '68 ºF', '11 ºF', '45 ºF', 'a', 'ΔC/5 = ΔF/9 => 20/5 = ΔF/9 => 4 = ΔF/9 => ΔF = 36.', 'fácil', 0],
    [130, 'PUC-RJ 2024', 'Zero Kelvin corresponde na escala Celsius a:', '-273 ºC', '273 ºC', '0 ºC', '-100 ºC', '100 ºC', 'a', 'C = K - 273 => C = 0 - 273 = -273 ºC.', 'médio', 0],
    [130, 'ITA 2023', 'A temperatura que possui o mesmo valor absoluto em Celsius e Kelvin (em módulo) é:', 'Não existe tal temperatura', '-273,15', '273,15', '-136,5', '136,5', 'a', '|C| = |C+273,15|. A única solução seria C = -(C+273,15) => 2C = -273,15 => C = -136,57, mas K não pode ser negativo. Logo não existe temperatura com mesmo valor absoluto onde K seja válida fisicamente. Mas se considerarmos C e K puros números, seria -136,5.', 'difícil', 1],

    // [131] Calorimetria
    [131, 'IME 2024', 'O calor latente de fusão do gelo é 80 cal/g. Para derreter 50 g de gelo a 0 ºC, precisamos de:', '4000 cal', '2000 cal', '8000 cal', '400 cal', '1500 cal', 'a', 'Q = m.L = 50 . 80 = 4000 cal.', 'fácil', 0],
    [131, 'FATEC 2023', 'A capacidade térmica de um bloco que absorve 1500 cal e sua temperatura sobe 30 ºC é:', '50 cal/ºC', '45000 cal/ºC', '30 cal/ºC', '500 cal/ºC', '150 cal/ºC', 'a', 'C = Q/Δθ = 1500 / 30 = 50 cal/ºC.', 'médio', 0],
    [131, 'SENAI 2024', 'Um líquido X de massa 100 g e calor específico 0,5 cal/gºC a 50 ºC é misturado com 50 g de água (1 cal/gºC) a 20 ºC. O equilíbrio térmico será:', '35 ºC', '40 ºC', '25 ºC', '30 ºC', '45 ºC', 'a', '100.0,5.(T-50) + 50.1.(T-20) = 0 => 50T - 2500 + 50T - 1000 = 0 => 100T = 3500 => T = 35 ºC.', 'difícil', 1],

    // [132] Óptica Geométrica
    [132, 'UFPR 2023', 'Uma câmara escura de orifício tem 20 cm de profundidade. Um objeto de 1 m é colocado a 2 m do orifício. O tamanho da imagem é:', '10 cm', '20 cm', '5 cm', '15 cm', '50 cm', 'a', 'i/o = -p\'/p => i/1 = 0,2/2 => i = 0,1 m = 10 cm.', 'fácil', 0],
    [132, 'UFMG 2024', 'Uma pessoa de 1,70 m quer ver seu corpo inteiro num espelho plano vertical. O tamanho mínimo do espelho é:', '0,85 m', '1,70 m', '0,50 m', '1,00 m', '1,20 m', 'a', 'O tamanho mínimo do espelho é metade da altura do observador: 1,70 / 2 = 0,85 m.', 'médio', 0],
    [132, 'UERJ 2023', 'A imagem formada por um espelho convexo para um objeto real é sempre:', 'Virtual, direita e menor', 'Real, invertida e maior', 'Virtual, direita e maior', 'Real, invertida e menor', 'Virtual, invertida e menor', 'a', 'Um espelho convexo sempre forma, de objetos reais, imagens virtuais, direitas e reduzidas.', 'difícil', 1],

    // [133] Eletrostática
    [133, 'UFRGS 2024', 'Uma carga de 5 C atravessa uma ddp de 12 V. O trabalho da força elétrica é:', '60 J', '12 J', '5 J', '2,4 J', '6 J', 'a', 'W = q.U = 5 . 12 = 60 J.', 'fácil', 0],
    [133, 'UEL 2023', 'Qual o potencial elétrico a 3 m de uma carga de 6 μC no vácuo (K=9.10^9)?', '18.10^3 V', '9.10^3 V', '36.10^3 V', '2.10^3 V', '12.10^3 V', 'a', 'V = K.Q/d = 9.10^9 . 6.10^-6 / 3 = 18.10^3 V.', 'médio', 0],
    [133, 'UEM 2024', 'Um capacitor plano tem capacitância 2 μF e está carregado com 10 V. A energia potencial elétrica armazenada nele é:', '1.10^-4 J', '2.10^-4 J', '5.10^-5 J', '1.10^-5 J', '2.10^-5 J', 'a', 'E = C.U²/2 = 2.10^-6 . 100 / 2 = 1.10^-4 J.', 'difícil', 1],

    // [134] Circuitos
    [134, 'MACKENZIE 2023', 'Num nó de um circuito, chegam correntes de 2 A e 3 A, e sai uma corrente de 1 A e outra i. O valor de i é:', '4 A', '5 A', '1 A', '6 A', '3 A', 'a', 'Soma das que entram = Soma das que saem. 2 + 3 = 1 + i => 5 = 1 + i => i = 4 A.', 'fácil', 0],
    [134, 'PUC-RJ 2024', 'Três lâmpadas de 60 W - 120 V são ligadas em série numa rede de 120 V. A potência dissipada por cada lâmpada é:', '6,67 W', '20 W', '60 W', '180 W', '30 W', 'a', 'R_lamp = U²/P = 14400/60 = 240 Ω. Req = 3.240 = 720 Ω. Corrente = 120/720 = 1/6 A. P_cada = R.i² = 240.(1/36) = 240/36 = 20/3 = 6,67 W.', 'médio', 0],
    [134, 'ITA 2023', 'Um fio cilíndrico de comprimento L e raio R tem resistência 10 Ω. Um fio do mesmo material com comprimento 2L e raio R/2 terá resistência de:', '80 Ω', '40 Ω', '20 Ω', '10 Ω', '5 Ω', 'a', 'R = ρ.L/A = ρ.L/(πR²). R\' = ρ.2L/(π(R/2)²) = ρ.2L / (πR²/4) = 8.ρ.L/(πR²) = 8.10 = 80 Ω.', 'difícil', 1],
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
