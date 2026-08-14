<?php
/**
 * Guarda de Segurança CLI — HipoGabarito
 * Bloqueia a execução de scripts de banco de dados via requisições HTTP (Web).
 */
if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    header('Content-Type: text/plain; charset=utf-8');
    die("Acesso Negado: Este script de banco de dados só pode ser executado via linha de comando (CLI).\n");
}
