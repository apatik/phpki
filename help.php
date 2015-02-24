<?php

require_once('include/std_include.php');
$S->assign('BASE_URL',BASE_URL);
printHeader('about');
$S->display('userHelp.tpl');
printFooter();
