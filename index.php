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

$stage = gpvar('stage');

$PHP_SELF     = basename(__FILE__);
$S->assign('PHP_SELF', $PHP_SELF);

switch($stage) {

    case 'dl_root':
        upload($config['cacert_pem'], $config['ca_prefix'] . "cacert.crt", 'application/x-x509-ca-cert');
        break;

    case 'dl_crl':
        upload($config['cacrl_der'], $config['ca_prefix'] . "cacrl.crl", 'application/pkix-crl');
        break;

    case 'gen_crl':
        list($returnValue,$errorText) = CA_generate_crl();
        $S->assign('returnValue', $returnValue);

        printHeader(false);

        if ($returnValue) {
            $CA_crl_text = CA_crl_text();
            $S->assign('CA_crl_text', $CA_crl_text);
        }
        else {
            $S->assign('errorText', $errorText);
        }
        $S->display('crlUpdate.tpl');
        printFooter();
        break;

    default:
        printHeader();
        $S->display('mainMenu.tpl');
        printFooter();
}