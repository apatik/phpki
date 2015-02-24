<?php

require_once('../include/std_include.php');
$PHP_SELF     = basename(__FILE__);
$S->assign('PHP_SELF', $PHP_SELF);

$stage   = gpvar('stage');
$login   = gpvar('login');
$passwd  = gpvar('passwd');
$passwdv = gpvar('passwdv');

$S->assign('login', $login);

switch($stage) {
case 'list_users':
    $S->assign('config',$config);
    $passwd_file = file_get_contents($config['passwd_file']);
    $S->assign('passwd_file',$passwd_file);

    printHeader('admin');
    $S->display('admin/listUsers.tpl');
    printFooter(false);
    break;

case 'add_user_form';
    printHeader('admin');
    $S->display('admin/addUser.tpl');
    printFooter();
break;

case 'add_user':
    printHeader('admin');
    if (! $passwd || ! $passwdv || $passwd != $passwdv || strlen($passwd) < 8) {
        $error=true;
    }
    else {
        $error=false;
        $pwdfile = escapeshellarg($config['passwd_file']);
        $login = escapeshellarg($login);
        $passwd = escapeshellarg($passwd);
        $htpasswdResults=system("htpasswd -bm $pwdfile $login $passwd 2>&1");
        $S->assign('htpasswdResults',$htpasswdResults);
    }
    $S->display('admin/addUserResult.tpl');
    printFooter();
    break;

case 'del_user_form';
    printHeader('admin');
    $S->assign('form', true);
    $S->display('admin/delUser.tpl');
    printFooter();
break;

case 'del_user':
    printHeader('admin');
    $S->assign('form', false);
    $pwdfile = escapeshellarg($config['passwd_file']);
    $login = escapeshellarg($login);
    $htpasswdResults=system("htpasswd -D $pwdfile $login 2>&1");
    $S->assign('htpasswdResults',$htpasswdResults);
    printFooter();
    break;

default:
    printHeader('admin');
    $S->display('admin/mainMenu.tpl');
    printFooter();
}
