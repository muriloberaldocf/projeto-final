<?php
/**
 * REDIRECIONAMENTO TRANSPARENTE:
 * A antiga calculadora foi substituída pelo "Guia de Concorrência & Faixas de Acertos"
 * para evitar falsas projeções de notas fixas decorrentes da variação da TRI e cortes do SiSU.
 */
header('Location: guia_concorrencia.php', true, 301);
exit;
