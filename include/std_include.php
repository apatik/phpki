<?php

umask(0007);

require_once(__DIR__ . '/../define.php');

if (isset($_SERVER['PHP_AUTH_USER']))
    $PHPki_user = md5($_SERVER['PHP_AUTH_USER']);
else
    $PHPki_user = md5('default');

require_once(PROJECT_DIR . '/vendor/smarty/smarty/libs/Smarty.class.php');
$S = new Smarty();
$S->setTemplateDir(PROJECT_DIR  . '/templates');
$S->setCompileDir(PROJECT_DIR  . '/templates_c');
$S->assign('DEMO', DEMO);
$S->assign('PHPKI_VERSION',PHPKI_VERSION);

require_once(PROJECT_DIR . '/include/std_functions.php');
require_once(PROJECT_DIR . '/include/openssl_functions.php');

if (STORE_DIR != 'fakeDirNameLOL'){
    require_once(STORE_DIR . "/config/config.php");
}