<?php

require_once('../include/std_include.php');

# User's preferences file
$user_cnf = "$config[home_dir]/config/user-".strtr($PHPki_user,'/\\','|#').'.php';

# Retrieve GET/POST values
$form_stage     = gpvar('form_stage');
$submit         = gpvar('submit');

$country        = gpvar('country');
$province       = gpvar('province');
$locality       = gpvar('locality');
$organization   = gpvar('organization');
$unit           = gpvar('unit');
$common_name    = gpvar('common_name');
$email          = gpvar('email');
$passwd         = gpvar('passwd');
$passwdv        = gpvar('passwdv');
$expiry         = gpvar('expiry');
$keysize        = gpvar('keysize');
$cert_type      = gpvar('cert_type');
$dns_names      = gpvar('dns_names');
$ip_addr        = gpvar('ip_addr');
$ecCurve        = gpvar('ecCurve');
$encryptionType = gpvar('encryptionType');
$curves         = CAdb_get_curves();

$PHP_SELF     = basename(__FILE__);
$S->assign('PHP_SELF', $PHP_SELF);

$S->assign('country',htvar($country));
$S->assign('province',htvar($province));
$S->assign('locality',htvar($locality));
$S->assign('organization',htvar($organization));
$S->assign('unit',htvar($unit));
$S->assign('common_name',htvar($common_name));
$S->assign('email',htvar($email));
$S->assign('passwd',htvar($passwd));
$S->assign('passwdv',htvar($passwdv));
$S->assign('expiry',htvar($expiry));
$S->assign('keysize',htvar($keysize));
$S->assign('cert_type',htvar($cert_type));
$S->assign('dns_names',htvar($dns_names));
$S->assign('ip_addr',htvar($ip_addr));
$S->assign('ecCurve',htvar($ecCurve));
$S->assign('encryptionType',htvar($encryptionType));
$S->assign('curves',$curves);


switch ($form_stage) {

    case 'validate':
        $er   = array();
        $warn = array();

        if (! $country)      $warn[] = 'Missing Country';
        if (! $province)     $warn[] = 'Missing State/Province';
        if (! $locality)     $warn[] = 'Missing Locality (City/County)';
        if (! $organization) $er[]   = 'Missing Organization (Company/Agency)';
        if (! $unit)         $warn[] = 'Missing Unit/Department';
        if (! $common_name)  $er[]   = 'Missing Common Name';
        if (! $email)        $warn[] = 'Missing E-mail Address';

        if (($cert_type == 'email' || $cert_type == 'email_signing') && ! $passwd)       $er[] = 'Missing Certificate Password';
        if (($cert_type == 'email' || $cert_type == 'email_signing') && ! $passwdv)      $er[] = 'Missing Certificate Password Verification "Again"';

        if ( $passwd && strlen($passwd) < 8 )
            $er[] = 'Certificate password is too short.';

        if ( $passwd and $passwd != $passwdv )
            $er[] = 'Password and password verification do not match.';

        if ( $email && ! is_email($email) )
            $er[] = 'E-mail address ('. htvar($email) . ') may be invalid.';

        $ip_ar=explode("\n", $ip_addr);
        foreach ($ip_ar as $value){
            if ( $value && ! is_ip($value) )
                $er[] = 'IP address ('. htvar($value) . ') may be invalid.';
        }

        $dns_n=explode("\n", $dns_names);
        foreach ($dns_n as $value){
            if (trim($value) == trim($common_name)){
                $er[] = 'DNS Name ('. htvar($value) . ') is invalid - the common name is already added to the subjectAltName automatically, do not add it again manually';
                continue;
            }
            if (trim($value) == 'localhost'){continue;}
            if ( $value && ! is_fqdn(trim($value)) )
                $er[] = 'DNS Name ('. htvar($value) . ') may be invalid.';
        }


        if ($email && ($serial = CAdb_in($email,$common_name))) {
            $certtext = CA_cert_text($serial);
            $er[] = 'A valid certificate already exists for ' . htvar("$common_name  <$email>"). ": <blockquote><pre>". htvar($certtext) . "</pre></blockquote>";
        }

        if ($er)  {
            $S->assign('er',$er);
            $S->assign('warn',$warn);
            printHeader('ca');
            $S->display('requestCert/requestCertValidationError.tpl');
            printFooter();
            break;
        }

    case 'confirm':

        switch  ($cert_type) {
            case 'email': $cert_usage = 'E-mail, SSL Client';
                break;
            case 'email_signing': $cert_usage = 'E-mail, SSL Client, Code Signing';
                break;
            case 'server': $cert_usage = 'SSL Server';
                break;
            case 'vSphere': $cert_usage = 'vSphere 5 Server';
                break;
            case 'vpn_client': $cert_usage = 'VPN Client Only';
                break;
            case 'vpn_server': $cert_usage = 'VPN Server Only';
                break;
            case 'vpn_client_server': $cert_usage = 'VPN Client, VPN Server';
                break;
            case 'time_stamping': $cert_usage = 'Time Stamping';
        }
        $S->assign('cert_usage', $cert_usage);

        printHeader('ca');
        $S->display('requestCert/requestCertConfirm.tpl');
        printFooter();

        # Save user's defaults
        $fp = fopen($user_cnf,'w');
        $x = $S->fetch('requestCert/userDefaults.tpl');
        fwrite($fp,$x);
        fclose($fp);

        break;

    case 'final':
        if ($submit == "Yes!  Create and Download") {
            if (! $serial = CAdb_in($email,$common_name)) {
                list($returnValue,$errorText) = CA_create_cert($cert_type,$country, $province, $locality, $organization, $unit, $common_name, $email, $expiry, $passwd, $keysize,$dns_names,$ip_addr,$encryptionType,$ecCurve);

                if (! $returnValue) {
                    $S->assign('errorText',$errorText);
                    printHeader('ca');
                    $S->display('requestCert/requestCertError.tpl');
                    printFooter();
                    break;
                }
                else {
                    $serial = $errorText;
                }
            }

            switch($cert_type) {
                case 'vSphere':
                case 'server':
                    upload(array("$config[private_dir]/$serial-key.pem","$config[new_certs_dir]/$serial.pem",$config['cacert_pem']), "$common_name ($email).pem",'application/pkix-cert');
                    break;
                case 'email':
                case 'email_signing':
                case 'time_stamping':
                case 'vpn_client_server':
                case 'vpn_client':
                case 'vpn_server':
                    upload("$config[pfx_dir]/$serial.pfx", "$common_name ($email).p12", 'application/x-pkcs12');
                    break;
            }

            break;
        }
    default:
        #
        # Default fields to reasonable values if necessary.
        #
        if (! $submit and file_exists($user_cnf)) include($user_cnf);

        if (! $country)       $country = $config['country'];
        if (! $province)      $province = $config['province'];
        if (! $locality)      $locality = "";
        if (! $organization)  $organization = "";
        if (! $unit)          $unit = "";
        if (! $email)         $email = "";
        if (! $expiry)        $expiry = 10;
        if (! $keysize)       $keysize = 4096;
        if (! $cert_type)     $cert_type = 'server';
        if (! $dns_names)     $dns_names = "";
        if (! $ip_addr)       $ip_addr = "";

        $S->assign('country',htvar($country));
        $S->assign('province',htvar($province));
        $S->assign('locality',htvar($locality));
        $S->assign('organization',htvar($organization));
        $S->assign('unit',htvar($unit));
        $S->assign('email',htvar($email));
        $S->assign('expiry',htvar($expiry));
        $S->assign('keysize',htvar($keysize));
        $S->assign('cert_type',htvar($cert_type));
        $S->assign('dns_names',htvar($dns_names));
        $S->assign('ip_addr',htvar($ip_addr));

        printHeader('ca');
        $S->display('requestCert/requestCert.tpl');
        printFooter();
}
