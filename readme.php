<?php

require_once('./include/std_include.php');

printHeader();
$readme = file_get_contents('./README.md');
$S->assign('readme', $readme);
$S->display('no_config_exists.tpl');
printFooter();
exit;