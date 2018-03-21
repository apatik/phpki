<?php

require_once('./include/std_include.php');

if (!file_exists(STORE_DIR)){
    printHeader('setup');
    $readme = file_get_contents('./README.md');
    $S->assign('readme', $readme);
    $S->display('no_config_exists.tpl');
    printFooter();
    exit;
}

upload($config['cacrl_der'], $config['ca_prefix'] . "cacrl.crl", 'application/pkix-crl');
