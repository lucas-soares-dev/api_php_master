<?php

// ATIVANDO DISPLAYS DE ERROS (OBS: NÃO SE DEVE ATIVAR ESSES DISPLAYS EM PRODUÇÃO PARA O USUÁRIO)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

// DEFININDO CONSTANTES GLOBAIS NO PROJETO
define('HOST', 'localhost');
define('USER', 'root');
define('PASSWORD', 'Admin@123*');
define('DBNAME', 'api-php');

define('DIR_SEPARATOR', DIRECTORY_SEPARATOR);
define('DIR_APP', __DIR__);
define('DIR_PROJ', 'api_php_master');

// INCLUINDO AUTOLOAD
if(file_exists('./vendor/autoload.php')) {
    include 'vendor/autoload.php';
} else {
    die("ERRO: Não foi possível incluir o autoload");
}