<?php

function printHeader($menu="default") {
    global $config, $S;
    $title = ($config['header_title']?$config['header_title']:'PHPki Certificate Authority');

    switch ($menu) {
        case 'ca':
        case 'admin':
            $style_css = './../css/style.css';
            break;
        case 'public':
        case 'about':
        case 'setup':
        default:
            $style_css = './css/style.css';
            break;

    }

    if (isKonq()) {
        $logo_class  = '"logo-konq"';
        $title_class = '"title-konq"';
        $menu_class  = '"headermenu-konq"';
    }
    else {
        $logo_class  = '"logo-ie"';
        $title_class = '"title-ie"';
        $menu_class  = '"headermenu-ie"';
    }

    $S->assign('title', $title);
    $S->assign('menu', $menu);
    $S->assign('style_css', $style_css);
    $S->assign('logo_class',$logo_class);
    $S->assign('title_class',$title_class);
    $S->assign('menu_class',$menu_class);

    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Expires: -1");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    $S->display('header.tpl');

}

function printFooter() {
    global $S;
    $S->display('footer.tpl');
}

/*
 Checks for browser version
*/
function isIE() {
    global $_SERVER;
    return strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE');
}

function isKonq() {
    global $_SERVER;
    return strstr($_SERVER['HTTP_USER_AGENT'], 'Konqueror');
}

function isMoz() {
    global $_SERVER;
    return strstr($_SERVER['HTTP_USER_AGENT'], 'Gecko');
}

#
# Returns TRUE if argument contains only alphabetic characters.
#
function is_alpha($v) {
    return (eregi('[^A-Z]',$v) ? false : true) ;
}

#
# Returns TRUE if argument contains only numeric characters.
#
function is_num($v) {
    return (eregi('[^0-9]',$v) ? false : true) ;
}

#
# Returns TRUE if argument contains only alphanumeric characters.
#
function is_alnum($v) {
    return (eregi('[^A-Z0-9]',$v) ? false : true) ;
}

#
# Returns TRUE if argument is in proper e-mail address format.
#
function is_email($v) {
    return (eregi('^[^@ ]+\@[^@ ]+\.[A-Z]{2,4}$',$v) ? true : false);
}

#
# Returns True if the given string is a IP address
#
function is_ip( $ip = null ) {
    if( !$ip or strlen(trim($ip)) == 0){
        return false;
    }
    $ip=trim($ip);
    if(preg_match("/^[0-9]{1,3}(.[0-9]{1,3}){3}$/",$ip)) {
        foreach(explode(".", $ip) as $block)
            if($block<0 || $block>255 )
                return false;
        return true;
    }
    return false;
}

#
# Returns True if the given string is a valid FQDN
#
function is_fqdn($FQDN) {
    // remove leading wildcard characters if exist
    $FQDN = preg_replace('/^\*\./','', $FQDN, 1);
    return (!empty($FQDN) && preg_match('/^(?=.{1,254}$)((?=[a-z0-9-]{1,63}\.)(xn--+)?[a-z0-9]+(-[a-z0-9]+)*\.)+(xn--+)?[a-z0-9]{2,63}$/i', $FQDN) > 0);
}

#
# Returns a value suitable for use as a shell argument.
# Strips slashes if magic quotes is on, surrounds
# provided strings with single-quotes and quotes any
# other dangerous characters.
#
function escshellarg($v, $strip=false) {
    if ($strip)
        return escapeshellarg(stripslashes($v));
    else
        return escapeshellarg($v);
}

#
# Similar to escshellarg(), but doesn't surround provided
# string with single-quotes.
#
function escshellcmd($v, $strip=false) {
    if ($strip)
        return escapeshellcmd(stripslashes($v));
    else
        return escapeshellarg($v);
}

#
# Returns a value from the GET/POST global array referenced
# by field name.  POST fields have precedence over GET fields.
# Quoting/Slashes are stripped if magic quotes gpc is on.
#
function gpvar($v) {
    global $_GET, $_POST;
    $x = "";
    if (isset($_GET[$v]))  $x = $_GET[$v];
    if (isset($_POST[$v])) $x = $_POST[$v];
    if (get_magic_quotes_gpc()) $x = stripslashes($x);
    return $x;
}

#
# Returns a value suitable for display in the browser.
# Strips slashes if second argument is true.
#
function htvar($v, $strip=false) {
    if ($strip)
        return  htmlentities(stripslashes($v), 0, "UTF-8");
    else
        return  htmlentities($v, 0, "UTF-8");
}

#
# Sort a two multidimensional array by one of it's columns
#
function csort($array, $column, $ascdec=SORT_ASC){
    if (sizeof($array) == 0) return $array;

    foreach($array as $x) $sortarr[]=$x[$column];
    array_multisort($sortarr, $ascdec, $array);

    return $array;
}

#
# Force upload of specified file to browser.
#
function upload($source, $destination, $content_type="application/octet-stream") {
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Expires: -1");
#	header("Cache-Control: no-store, no-cache, must-revalidate");
#	header("Cache-Control: post-check=0, pre-check=0", false);
#	header("Pragma: no-cache");
    header("Content-Type: $content_type");

    if (is_array($source)) {
        $fsize = 0;
        foreach($source as $f) $fsize += filesize($f);
    }
    else {
        $fsize = filesize($source);
    }

    header("Content-length: " . $fsize);
#        header("Content-Disposition: attachment; filename=\"" . $destination ."\"");
    header("Content-Disposition: filename=\"" . $destination ."\"");

    if (is_array($source))
        foreach($source as $f) $ret = readfile($f);
    else
        $ret=readfile($source);

#        $fd=fopen($source,'r');
#        fpassthru($fd);
#        fclose($fd);
}
