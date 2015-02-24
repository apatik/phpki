<?php
require_once('./include/std_include.php');

printHeader('about');

$LICENSE = file_get_contents('./LICENSE.txt');
$S->assign('LICENSE', $LICENSE);
$S->display('about.tpl');

printFooter();