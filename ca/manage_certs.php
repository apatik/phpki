<?php

require_once('../include/std_include.php');

$stage     = gpvar('stage');
$serial    = gpvar('serial');
$sortfield = gpvar('sortfield');
$ascdec    = gpvar('ascdec');
$passwd    = gpvar('passwd');
$expiry    = gpvar('expiry');
$submit    = gpvar('submit');
$dl_type   = gpvar('dl_type');

$S->assign('serial', $serial);
$S->assign('expiry', $expiry);

$search       = gpvar('search');
$show_valid   = gpvar('show_valid');
$show_revoked = gpvar('show_revoked');
$show_expired = gpvar('show_expired');

$PHP_SELF     = basename(__FILE__);
$S->assign('PHP_SELF', $PHP_SELF);

# Prevent handling certs that don't belong to user
if ($serial && CAdb_issuer($serial) != $PHPki_user && ! in_array($PHPki_user, $PHPki_admins)) {
    $stage = 'goaway';
}

if ( !($show_valid.$show_revoked.$show_expired) ) {
    $show_valid   = 'V';
    $show_revoked = 'R';
    $show_expired = 'E';
}

$qstr_filter =	'search='.htvar($search).'&'.
    "show_valid=$show_valid&".
    "show_revoked=$show_revoked&".
    "show_expired=$show_expired&";

$qstr_sort   = "sortfield=$sortfield&ascdec=$ascdec";

$S->assign('search', htvar($search));
$S->assign('show_valid', $show_valid);
$S->assign('show_revoked', $show_revoked);
$S->assign('show_expired', $show_expired);
$S->assign('qstr_filter',$qstr_filter);
$S->assign('qstr_sort',$qstr_sort);

switch ($stage) {
case 'goaway':
    printHeader(false);
    print '<p><center><h1><font color="red">ACCESS DENIED!</font></h2></center>';
    printFooter();
    break;

case 'display':
    printHeader('ca');

    $certCommonName = CA_cert_cname($serial);
    $certEmail = CA_cert_email($serial);
    $certRevokeDate = CAdb_is_revoked($serial);
    $certText = CA_cert_text($serial);
    $S->assign('certCommonName',htvar($certCommonName));
    $S->assign('certEmail',$certEmail);
    $S->assign('certRevokeDate',$certRevokeDate);
    $S->assign('certText',$certText);

    $S->display('manageCerts/displayCert.tpl');

    printFooter();
    break;

case 'dl-confirm':
    printHeader('ca');

    $rec = CAdb_get_entry($serial);
    if($rec !== false){
        foreach($rec as $k=>$v){
            $rec[$k] = htvar($v);
        }
    }
    $S->assign('rec',$rec);

    $S->display('manageCerts/downloadCert.tpl');
    printFooter();

    break;

case 'download':
    if (strstr($submit, "Back"))  $dl_type = '';

    $rec = CAdb_get_entry($serial);

    switch ($dl_type)
    {
        case 'PKCS#12':
            $fileName = (empty($rec['email'])) ? $rec['common_name'] . ".p12" : $rec['common_name'] . ' (' . $rec['email'] . ').p12';
            upload("$config[pfx_dir]/$serial.pfx", $fileName, 'application/x-pkcs12');
            break;
        case 'PKCS#1':
            if (file_exists("$config[private_dir]/$serial-RSAkey.pem"))
            {
                $fileName = (empty($rec['email'])) ? $rec['common_name'] . "-RSAkey.pem" : $rec['common_name'] . ' (' . $rec['email'] . ')-RSAkey.pem';
                upload("$config[private_dir]/$serial-RSAkey.pem", $fileName, 'application/octet-stream');
            }
            else
            {
                print "<script language='javascript'>alert('RSA key not found! Elliptic Curve certificates do not have RSA keys.'); location.reload();</script>";
            }
            break;
        case 'PEMCERT':
            $fileName = (empty($rec['email'])) ? $rec['common_name'] . ".pem" : $rec['common_name'] . ' (' . $rec['email'] . ').pem';
            upload("$config[new_certs_dir]/$serial.pem", $fileName, 'application/pkix-cert');
            break;
        case 'PEMKEY':
            $fileName = (empty($rec['email'])) ? $rec['common_name'] . "-key.pem" : $rec['common_name'] . ' (' . $rec['email'] . ')-key.pem';
            upload("$config[private_dir]/$serial-key.pem", $fileName, 'application/octet-stream');
            break;
        case 'PEMBUNDLE':
            $fileName = (empty($rec['email'])) ? $rec['common_name'] . ".pem" : $rec['common_name'] . ' (' . $rec['email'] . ').pem';
            upload(array("$config[private_dir]/$serial-key.pem","$config[new_certs_dir]/$serial.pem"), $fileName, 'application/octet-stream');
            break;
        case 'PEMCABUNDLE':
            $fileName = (empty($rec['email'])) ? $rec['common_name'] . ".pem" : $rec['common_name'] . ' (' . $rec['email'] . ').pem';
            upload(array("$config[private_dir]/$serial-key.pem","$config[new_certs_dir]/$serial.pem",$config['cacert_pem']), $fileName, 'application/octet-stream');
            break;
        default:
            header("Location: ${PHP_SELF}?$qstr_sort&$qstr_filter");
    }
    break;

case 'revoke-form':

    $rec = CAdb_get_entry($serial);
    if($rec !== false){
        foreach($rec as $k=>$v){
            $rec[$k] = htvar($v);
        }
    }
    $S->assign('rec',$rec);

    printHeader('ca');
    $S->display('manageCerts/revokeCert.tpl');
    printFooter();

    break;

case 'revoke':
    $returnValue = true;
    if ($submit == 'Yes')
        list($returnValue, $errorText) = CA_revoke_cert($serial);

    if (! $returnValue) {
        $S->assign('errorText',$errorText);
        printHeader('ca');
        $S->display('manageCerts/revokeCertError.tpl');
        printFooter();
    }
    else
        header("Location: ${PHP_SELF}?$qstr_sort&$qstr_filter");
    break;

case 'renew-form':
    #
    # Get last known values submitted by this user.  We only really
    # need the expiry value, but the old cert values will override
    # the rest.
    #
    if (! $submit and file_exists("config/user-${PHPki_user}.php"))
        include("config/user-${PHPki_user}.php");

    #
    # Get values from the old certificate.
    #
    $rec = CAdb_get_entry($serial);
    if ($rec !== false) {
        foreach ($rec as $k => $v) {
            $rec[$k] = htvar($v);
        }
    }

    $S->assign('rec',$rec);

    printHeader('ca');
    $S->display('manageCerts/renewCert.tpl');
    printFooter();

    break;

case 'renew':
    $returnValue = true;
    if ($submit == "Submit Request")
        list($returnValue, $errorText) = CA_renew_cert($serial, $expiry, $passwd);

    if (! $returnValue) {
        $S->assign('errorText',$errorText);
        printHeader('ca');
        $S->display('manageCerts/renewCertError.tpl');
        printFooter();
    }
    else {
        header("Location: $PHP_SELF?$qstr_sort&$qstr_filter");
    }

    break;

default:

    if (! $sortfield) {
        $sortfield = 'email' ;
        $ascdec = 'A';
    }

    if ($ascdec == 'A') {
        $arrow_gif = '../images/uparrow-blue.gif';
        $ht_ascdec = 'D';
    }
    else {
        $arrow_gif = '../images/downarrow-blue.gif';
        $ht_ascdec = 'A';
    }

    $headings = array(
        'status'=>"Status",
        'issued'=>"Issued",
        'expires'=>"Expires",
        'common_name'=>"User's Name",
        'email'=>"E-mail",
        'organization'=>"Organization",
        'unit'=>"Department",
        'locality'=>"Locality"
    );

    $x = "^[$show_valid$show_revoked$show_expired]";

    if (in_array($PHPki_user, $PHPki_admins)) {
        $x = "$x.*$search";
    }
    else {
        $x = "$x.*$search.*$PHPki_user|$x.*$PHPki_user.*$search";
    }

    $db = csort(CAdb_to_array($x), $sortfield, ($ascdec=='A'?SORT_ASC:SORT_DESC));

    # Convert dates to unix timestamp format, because the default format trips up smarty
    foreach($db as $key=>$rec){
        $db[$key]['issued'] = DateTime::createFromFormat('y-M-j', $rec['issued'])->getTimestamp();
        $db[$key]['expires'] = DateTime::createFromFormat('y-M-j', $rec['expires'])->getTimestamp();
        $db[$key]['common_name'] = htvar($rec['common_name']);
        $db[$key]['email'] = htvar($rec['email']);
        $db[$key]['organization'] = htvar($rec['organization']);
        $db[$key]['unit'] = htvar($rec['unit']);
        $db[$key]['locality'] = htvar($rec['locality']);

    }

    $stcolor = array(
        'Valid'=>'green',
        'Revoked'=>'red',
        'Expired'=>'orange'
    );

    $S->assign('sortfield',$sortfield);
    $S->assign('arrow_gif',$arrow_gif);
    $S->assign('ht_ascdec',$ht_ascdec);
    $S->assign('headings', $headings);
    $S->assign('db',$db);
    $S->assign('stcolor', $stcolor);

    printHeader('ca');
    $S->display('manageCerts/mainControlPanel.tpl');
    printFooter();
}