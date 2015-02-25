<?php

//
// Creates a temporary openssl config file specific to given parameters.
// File name is placed in ./tmp with a random name. It lingers unless
// removed manually.
//
function CA_create_cnf($country='',$province='',$locality='',$organization='',$unit='',$common_name='',$email='',$keysize=2048,$dns_names='',$ip_addr='',$serial='') {
    global $config, $PHPki_user, $S;

    $issuer = $PHPki_user;
    $count_dns = 0;
    $count_ip = 0;
    $alt_names = "";

    if (! $dns_names == '') {

        $dns_n=explode("\n", $dns_names);
        $count_dns  = $count_dns + 1;
        $alt_names .= "DNS.$count_dns = $common_name\n";
        foreach ($dns_n as $value) {
            if (! $value == '') {
                $count_dns  = $count_dns + 1;
                $alt_names .= "DNS.$count_dns = ".trim($value)."\n";
            }
        }
    }

    if (! $ip_addr == '') {
        $ip_ar=explode("\n", $ip_addr);
        foreach ($ip_ar as $value) {
            if (! $value == '') {
                $count_dns  = $count_dns + 1;
                $count_ip   = $count_ip + 1;
                $alt_names .= "DNS.$count_dns = ".trim($value)."\n";
                $alt_names .= "IP.$count_ip = ".trim($value)."\n";
            }
        }
    }

    if (($count_dns > 0) || ($count_ip > 0)) {
        $server_altnames = "@alt_names";
    } else {
        $server_altnames = "DNS:$common_name";
    }

    $S->assign('country',$country);
    $S->assign('province',$province);
    $S->assign('locality',$locality);
    $S->assign('organization',$organization);
    $S->assign('unit',$unit);
    $S->assign('common_name',$common_name);
    $S->assign('email',$email);
    $S->assign('keysize',$keysize);
    $S->assign('alt_names',$alt_names);
    $S->assign('server_altnames',$server_altnames);
    $S->assign('serial',$serial);
    $S->assign('issuer',$issuer);
    $S->assign('config',$config);


    $cnf_contents = $S->fetch("requestCert/certSSLConfig.tpl");


    # Write out the config file.
    $cnf_file  = tempnam('./tmp','cnf-');
    $handle = fopen($cnf_file,"w");
    fwrite($handle, $cnf_contents);
    fclose($handle);

    return($cnf_file);
}

//
// Search the certificate index and return resulting
// records in array[cert_serial_number][field_name].
// Fields: serial, country, province, locality, organization,
//         issuer, unit, common_name, email
//
function CAdb_to_array($search = '.*') {
    global $config;

    # Prepend a default status to search string if missing.
    if (! ereg('^\^\[.*\]', $search)) $search = '^[VRE].*'.$search;

    # Include valid certs?
    if (ereg('^\^\[.*V.*\]',$search)) {
        $includeValid = true;
    }
    else {
        $includeValid = false;
    };
    # Include revoked certs?
    if (ereg('^\^\[.*R.*\]',$search)) {
        $includeRevoked = true;
    }
    else {
        $includeRevoked = false;
    };
    # Include expired certs?
    if (ereg('^\^\[.*E.*\]',$search)) {
        $includeExpired = true;
    }
    else {
        $includeExpired = false;
    };

    # There isn't really a status of 'E' in the openssl index.
    # Change (E)xpired to (V)alid within the search string.
    $search = ereg_replace('^(\^\[.*)E(.*\])','\\1V\\2',$search);

    $db = array();
    exec('egrep -i '.escshellarg($search).' '.$config['index'], $x);
    foreach($x as $y) {
        $i = CAdb_explode_entry($y);
        if (($i['status'] == "Valid" && $includeValid) || ($i['status'] == "Revoked" && $includeRevoked) || ($i['status'] == "Expired" && $includeExpired))
            $db[$i['serial']] = $i;
    }

    return($db);
}


//
// Returns an array containing the index record for
// certificate $serial.
// 
function CAdb_get_entry($serial) {
    global $config;
    $regexp = "^[VR]\t.*\t.*\t$serial\t.*\t.*$";
    $x = exec('egrep '.escshellarg($regexp).' '.$config['index']);
    if ($x)
        return CAdb_explode_entry($x);
    else {
        return false;
    }
}


//
// Returns the serial number of a VALID certificate matching 
// $email and/or $name. Returns FALSE if no match is found.
//
function CAdb_in($email="", $name="") {
    global $config;
    $regexp = "^[V].*CN=$name/(Email|emailAddress)=$email";
    $x =exec('egrep '.escshellarg($regexp).' '.$config['index']);

    if ($x) {
        list($j,$j,$j,$serial,$j,$j) = explode("\t", $x);
        return "$serial";
    }
    else
        return false;
}


//
// Alias for CAdb_in()
//
function CAdb_serial($email, $name='') {
    return CAdb_in($email, $name='');
}

//
// Alias for CAdb_in()
//
function CAdb_exists($email, $name='') {
    return CAdb_in($email, $name='');
}


//
// Returns the certificate 'issuer'
//
function CAdb_issuer($serial) {
    global $config;
    $rec = CAdb_get_entry($serial);
    return $rec['issuer'];
}

//
// Returns an array containing the respective fields given a
// a raw line ($dbentry) from the certificate index.
// Fields: serial, country, province locality, organization, 
//         issuer, unit, common_name, email
//
function CAdb_explode_entry($dbentry) {
    $a = explode("\t", $dbentry);
    $b = explode('/',$a[5]);

    $db['country']      = '';
    $db['province']     = '';
    $db['locality']     = '';
    $db['organization'] = '';
    $db['issuer']       = '';
    $db['unit']         = '';
    $db['common_name']  = '';
    $db['email']        = '';

    foreach ($b as $dnPart){
        $d = explode('=',$dnPart);
        if (!empty($d)){
            switch ($d[0]) {
                case "C":
                    $db['country'] = $d[1];
                    break;
                case "ST":
                    $db['province'] = $d[1];
                    break;
                case "L":
                    $db['locality'] = $d[1];
                    break;
                case "O":
                    # Both the "issuing user" and organization are saved
                    # under O tags, this way to separate them is a bit hacky, but should work...
                    if (preg_match('/^[a-f0-9]{32}$/', $d[1])){
                        $db['issuer'] = $d[1];
                    }
                    else {
                        $db['organization'] = $d[1];
                    }
                    break;
                case "OU":
                    $db['unit'] = $d[1];
                    break;
                case "CN":
                    $db['common_name'] = $d[1];
                    break;
                case "emailAddress":
                    $db['email'] = $d[1];
                    break;
                default:
                    break;
            }
        }
    }
    switch ($a[0]) {
        case "V":
            $db['status'] = "Valid";
            break;
        case "R":
            $db['status'] = "Revoked";
            break;
    }

    sscanf(CA_cert_startdate($a[3]),"%s %s %s %s", $mm,$dd,$tt,$yy);
    $db['issued'] = strftime("%y-%b-%d", strtotime("$dd $mm $yy"));

    sscanf($a[1], "%2s%2s%2s",$yy,$mm,$dd);
    $db['expires'] = strftime("%y-%b-%d", strtotime("$mm/$dd/$yy"));

    if (time() > strtotime("$mm/$dd/$yy"))
        $db['status'] = "Expired";

    $db['serial']       = $a[3];

    return $db;
}

//
// Returns the date & time a specified certificate is revoked,
// Returns FALSE if the certificate is not revoked.
//
function CAdb_is_revoked($serial) {
    global $config;
    $regexp = "^R\t.*\t.*\t$serial\t.*\t.*$";
    $x = exec('egrep '.escshellarg($regexp).' '.$config['index']);

    if  ($x) {
        list($j,$j,$revoke_date,$j,$j,$j) = explode("\t", $x);
        sscanf($revoke_date, "%2s%2s%2s",$yy,$mm,$dd);
        return strftime("%b %d, %Y", strtotime("$mm/$dd/$yy"));
    }
    else
        return false;
}

//
// Returns TRUE if a certificate is valid, otherwise FALSE.
//
function CAdb_is_valid($serial) {
    global $config;
    $regexp = "^V\t.*\t.*\t$serial\t.*\t.*$";

    if  (exec('egrep '.escshellarg($regexp).' '.$config['index']))
        return true;
    else
        return false;
}

//
// Returns the long-form certificate description as output by
// openssl x509 -in certificatefile -text -purpose
//
function CA_cert_text($serial) {
    global $config;
    $certfile = $config['new_certs_dir'] . '/' . $serial . '.pem';
    return(shell_exec(X509.' -in '.escshellarg($certfile).' -text -purpose 2>&1'));
}

//
// Returns the long-form text of the Certificate Revocation List
// openssl crl -in crlfile -text 
//
function CA_crl_text() {
    global $config;
    $crlfile = $config['cacrl_pem'];
    return(shell_exec(CRL.' -in '.escshellarg($crlfile).' -text 2>&1'));
}

//
// Returns the subject of a certificate.
//
function CA_cert_subject($serial) {
    global $config;
    $certfile = $config['new_certs_dir'] . '/' . $serial . '.pem';
    $x = exec(X509.' -in '.escshellarg($certfile).' -noout -subject 2>&1');
    return(str_replace('subject=', '', $x));
}

//
// Returns the common name of a certificate.
//
function CA_cert_cname($serial) {
    global $config;
    return(ereg_replace('^.*/CN=(.*)/.*','\\1',CA_cert_subject($serial)));
}

//
// Returns the email address of a certificate.
//
function CA_cert_email($serial) {
    global $config;
    $certfile = $config['new_certs_dir'] . '/' . $serial . '.pem';
    $x = exec(X509.' -in '.escshellarg($certfile).' -noout -email 2>&1');
    return($x);
}

//
// Returns the effective date of a certificate.
//
function CA_cert_startdate($serial) {
    global $config;
    $certfile = $config['new_certs_dir'] . '/' . $serial . '.pem';
    $x = exec(X509.' -in '.escshellarg($certfile).' -noout -startdate 2>&1');
    return(str_replace('notBefore=','',$x));
}

//
// Returns the expiration date of a certificate.
//
function CA_cert_enddate($serial) {
    global $config;
    $certfile = $config['new_certs_dir'] . '/' . $serial . '.pem';
    $x = exec(X509.' -in '.escshellarg($certfile).' -noout -enddate  2>&1');
    return(str_replace('notAfter=','',$x));
}

//
// Revokes a specified certificate.
//
function CA_revoke_cert($serial) {
    global $config;

    $fd = fopen($config['index'],'a');
    flock($fd, LOCK_EX);

    $certfile     = "$config[new_certs_dir]/$serial.pem";

    $cmd_output[] = 'Revoking the certificate.';
    exec(CA." -config '$config[openssl_cnf]' -revoke ".escshellarg($certfile)." -passin pass:'$config[ca_pwd]' 2>&1", $cmd_output, $ret);

    if ($ret == 0) {
        unset($cmd_output);
        list($ret, $cmd_output[]) = CA_generate_crl();
    }

    fclose($fd);

    return array(($ret == true || $ret == 0 ? true : false), implode('<br>',$cmd_output));
}

//
// Creates a new certificate request, and certificate in various formats
// according to specified parameters.   PKCS12 bundle files contain the 
// private key, certificate, and CA certificate.
//
// Returns an array containing the output of failed openssl commands.
//
function CA_create_cert($cert_type='email',$country,$province,$locality,$organization,$unit,$common_name,$email,$expiry,$passwd,$keysize=2048,$dns_names,$ip_addr) {
    global $config;

    # Wait here if another user has the database locked.
    $fd = fopen($config['index'],"a");
    flock($fd, LOCK_EX);

    # Get the next available serial number
    $serial = trim(implode('',file($config['serial'])));

    $userkey   = $config['private_dir'].'/'.$serial.'-key.pem';
    $userreq   = $config['req_dir'].'/'.$serial.'-req.pem';
    $usercert  = $config['new_certs_dir'].'/'.$serial.'.pem';
    $userder   = $config['cert_dir'].'/'.$serial.'.der';
    $userpfx   = $config['pfx_dir'].'/'.$serial.'.pfx';

    $expiry_days = round($expiry * 365.25, 0);

    $cnf_file = CA_create_cnf($country,$province,$locality,$organization,$unit,$common_name,$email,$keysize,$dns_names,$ip_addr,$serial);

    # Escape certain dangerous characters in user input
    $email         = escshellcmd($email);
    $_passwd       = escshellarg($passwd);
    $friendly_name = escshellarg($common_name);
    $extensions    = escshellarg($cert_type.'_ext');

    # Create the certificate request
    unset($cmd_output);
    $cmd_output[] = 'Creating certificate request.';

    if ($passwd) {
        exec(REQ." -new -newkey rsa:$keysize -keyout '$userkey' -out '$userreq' -config '$cnf_file' -days '$expiry_days' -passout pass:$_passwd  2>&1", $cmd_output, $ret);
    }
    else {
        exec(REQ." -new -nodes -newkey rsa:$keysize -keyout '$userkey' -out '$userreq' -config '$cnf_file' -days '$expiry_days' 2>&1", $cmd_output, $ret);
    }

    # Sign the certificate request and create the certificate
    if ($ret == 0) {
        unset($cmd_output);
        $cmd_output[] = "Signing $cert_type certifcate request.";
        exec(CA." -config '$cnf_file' -in '$userreq' -out /dev/null -notext -days '$expiry_days' -passin pass:'$config[ca_pwd]' -batch -extensions $extensions 2>&1", $cmd_output, $ret);
    };

    # Create DER format certificate
    if ($ret == 0) {
        unset($cmd_output);
        $cmd_output[] = "Creating DER format certifcate.";
        exec(X509." -in '$usercert' -out '$userder' -inform PEM -outform DER 2>&1", $cmd_output, $ret);
    };

    # Create a PKCS12 certificate file for download to Windows
    if ($ret == 0) {
        unset($cmd_output);
        $cmd_output[] = "Creating PKCS12 format certifcate.";
        if ($passwd) {
            $cmd_output[] = "infile: $usercert   keyfile: $userkey   outfile: $userpfx  pass: $_passwd";
            exec(PKCS12." -export -in '$usercert' -inkey '$userkey' -certfile '$config[cacert_pem]' -caname '$config[organization]' -out '$userpfx' -name $friendly_name -rand '$config[random]' -passin pass:$_passwd -passout pass:$_passwd  2>&1", $cmd_output, $ret);
        }
        else {
            $cmd_output[] = "infile: $usercert   keyfile: $userkey   outfile: $userpfx";
            exec(PKCS12." -export -in '$usercert' -inkey '$userkey' -certfile '$config[cacert_pem]' -caname '$config[organization]' -out '$userpfx' -name $friendly_name -nodes -passout pass: 2>&1", $cmd_output, $ret);
        }
    };

    #Unlock the CA database
    fclose($fd);

    #Remove temporary openssl config file.
    if (file_exists($cnf_file)) unlink($cnf_file);

    if ($ret == 0) {
        # Successful!
        # Return status=true and serial number of issued certificate.
        return array(true, $serial);

    }
    else {
        # Not successful. :-(
        # Clean up our loose ends.
        # Return status=false and openssl output/errors for debug.
        CA_remove_cert($serial);
        $cmd_output[] = 'Click on the "Help" link above for information on how to report this problem.';
        return array(false, implode("<br>",$cmd_output));
    }
}

//
// Renews a specified certificate, revoking any existing valid versions.
// Uses old certificate request to Creates a new request, and certificate 
// in various formats.
//
// Returns an array containing the output of failed openssl commands.
//
// FIXME: Yes, I know... This functions contains much duplicative code 
//        from CA_create_cert().  Bleh!
//        
function CA_renew_cert($old_serial,$expiry,$passwd) {
    global $config;

    # Don't renew a revoked certificate if a valid one exists for this
    # URL.  Find and renew the valid certificate instead.
    if (CAdb_is_revoked($old_serial)) {
        $ret = CAdb_in(CA_cert_email($old_serial),CA_cert_cname($old_serial));
        if ($ret && $old_serial != $ret) $old_serial = $ret;
    }

    # Valid certificates must be revoked prior to renewal.
    if (CAdb_is_valid($old_serial)) {
        $ret = CA_revoke_cert($old_serial);
        if (! $ret[0]) return $ret;
    }

    $cert_type  = CA_cert_type($old_serial);
    $extensions = $cert_type.'_ext';

    # Get common_name from old certificate for use as the
    # "friendly name" of PKCS12 certificate.
    $rec = CAdb_get_entry($old_serial);
    $country      = $rec['country'];
    $province     = $rec['province'];
    $locality     = $rec['locality'];
    $organization = $rec['organization'];
    $unit         = $rec['unit'];
    $common_name  = $rec['common_name'];
    $email        = $rec['email'];

    # Wait here if another user has the database locked.
    $fd = fopen($config['index'],"a");
    flock($fd, LOCK_EX);

    # Get the next available serial number
    $serial = trim(implode('',file($config['serial'])));

    $old_userkey = $config['private_dir'].'/'.$old_serial.'-key.pem';
    $old_userreq = $config['req_dir'].'/'.$old_serial.'-req.pem';
    $userkey     = $config['private_dir'].'/'.$serial.'-key.pem';
    $userreq     = $config['req_dir'].'/'.$serial.'-req.pem';
    $usercert    = $config['new_certs_dir'].'/'.$serial.'.pem';
    $userder     = $config['cert_dir'].'/'.$serial.'.der';
    $userpfx     = $config['pfx_dir'].'/'.$serial.'.pfx';

    $expiry_days = round($expiry * 365.25, 0);

    $cmd_output = array();
    $ret = 0;

    # Create a new certificate request by copying the old request.
    if (! file_exists($old_userreq) || ! copy($old_userreq,$userreq)) {
        $cmd_output[] = 'Could not create new certificate request file.';
        $ret = 1;
    }

    # Copy private key to new file.
    if ($ret == 0 && (! file_exists($old_userkey) || ! copy($old_userkey,$userkey))) {
        $cmd_output[] = "Could not update private key file.";
        $ret = 1;
    }

    $cnf_file = CA_create_cnf($country,$province,$locality,$organization,$unit,$common_name,$email);

    # "friendly name" of PKCS12 certificate.
    $friendly_name = escshellarg($rec['common_name']);

    # Escape dangerous characters in user input.
    $_passwd    = escshellarg($passwd);

    # Sign the certificate request and create the certificate.
    if ($ret == 0) {
        unset($cmd_output);
        $cmd_output[] = "Signing the $cert_type certificate request.";
        exec(CA." -config '$cnf_file' -in '$userreq' -out /dev/null -notext -days '$expiry_days' -passin pass:'$config[ca_pwd]' -batch -extensions $extensions 2>&1", $cmd_output, $ret);
    };

    # Create DER format certificate
    if ($ret == 0) {
        unset($cmd_output);
        $cmd_output[] = "Creating DER format certificate.";
        exec(X509." -in '$usercert' -out '$userder' -inform PEM -outform DER 2>&1", $cmd_output, $ret);
    };

    # Create a PKCS12 certificate file for download to Windows
    if ($ret == 0) {
        unset($cmd_output);
        $cmd_output[] = "Creating PKCS12 format certificate.";
        if ($passwd) {
            $cmd_output[] = "infile: $usercert   keyfile: $userkey   outfile: $userpfx  pass: $_passwd";
            exec(PKCS12." -export -in '$usercert' -inkey '$userkey' -certfile '$config[cacert_pem]' -caname '$config[organization]' -out '$userpfx' -name $friendly_name -rand '$config[random]' -passin pass:$_passwd -passout pass:$_passwd  2>&1", $cmd_output, $ret);
        }
        else {
            $cmd_output[] = "infile: $usercert   keyfile: $userkey   outfile: $userpfx";
            #exec(PKCS12." -export -in '$usercert' -inkey '$userkey' -certfile '$config[cacert_pem]' -caname '$config[organization]' -out '$userpfx' -name $friendly_name  -passout pass: 2>&1", $cmd_output, $ret);
            exec(PKCS12." -export -in '$usercert' -inkey '$userkey' -certfile '$config[cacert_pem]' -caname '$config[organization]' -out '$userpfx' -name $friendly_name  -nodes 2>&1", $cmd_output, $ret);
        }
    };

    #Unlock the CA database
    fclose($fd);

    #Remove temporary openssl config file.
    if (file_exists($cnf_file)) unlink($cnf_file);

    if ($ret == 0) {
        return array(true, $serial);
    }
    else {
        # Not successful, so clean up before exiting.
        CA_remove_cert($serial);

        if (eregi_array('.*private key.*',$cmd_output))
            $cmd_output[] = '<strong>This was likely caused by entering the wrong certificate password.</strong>';
        else
            $cmd_output[] = '<strong>Click on the "Help" link above for information on how to report this problem.</strong>';

        return array(false, implode('<br>',$cmd_output));
    }
}

//
// Creates a new Certificate Revocation List and copies it the the approriate 
// locations. Returns error messages from failed commands.
//
function CA_generate_crl() {
    global $config;

    $ret = 0;

    $cmd_output[] = "Generating Certificate Revocation List.";
    exec(CA. " -gencrl -config '$config[openssl_cnf]' -out '$config[cacrl_pem]' -passin pass:'$config[ca_pwd]' 2>&1", $cmd_output, $ret);

    if ($ret == 0) {
        unset($cmd_output);
        $cmd_output[] = "Creating DER format Certificate Revocation List.";
        exec(CRL." -in '$config[cacrl_pem]' -out '$config[cacrl_der]' -inform PEM -outform DER 2>&1", $cmd_output, $ret);
    }

    return array(($ret == 0 ? true : false), implode('<br>',$cmd_output));
}

//
// Removes a specified certificate from the certificate index,
// and all traces of it from the file system.
//
function CA_remove_cert($serial) {
    global $config;

    $userreq  = $config['req_dir'].'/'.$serial.'-req.pem';
    $userkey  = $config['private_dir'].'/'.$serial.'-key.pem';
    $usercert = $config['new_certs_dir'].'/'.$serial.'.pem';
    $userder  = $config['cert_dir'].'/'.$serial.'.der';
    $userpfx  = $config['pfx_dir'].'/'.$serial.'.pfx';


    # Wait here if another user has the database locked.
    $fd = fopen($config['index'],'a');
    flock($fd, LOCK_EX);

    if( file_exists($userreq))  unlink($userreq);
    if( file_exists($userkey))  unlink($userkey);
    if( file_exists($usercert)) unlink($usercert);
    if( file_exists($userder))  unlink($userder);
    if( file_exists($userpfx))  unlink($userpfx);

    $tmpfile = $config['index'].'.tmp';
    copy($config['index'], $tmpfile);

    $regexp = "^[VR]\t.*\t.*\t".$serial."\t.*\t.*$";
    exec('egrep -v '.escshellarg($regexp)." $tmpfile > $config[index] 2>/dev/null");

    unlink($tmpfile);
    fclose($fd);

}

//
// Returns the likely intended use for a specified certificate 
// (email, server, vpn).
//
function CA_cert_type($serial) {

    $certtext = CA_cert_text($serial);

    if (ereg('OpenSSL.* (E.?mail|Personal) .*Certificate', $certtext) && ereg('Code Signing', $certtest)) {
        $cert_type = 'email_signing';
    }
    if (ereg('OpenSSL.* (E.?mail|Personal) .*Certificate', $certtext)) {
        $cert_type = 'email';
    }
    elseif (ereg('OpenSSL.* Server .*Certificate', $certtext)) {
        $cert_type = 'server';
    }
    elseif (ereg('timeStamping|Time Stamping', $certtext)) {
        $cert_type = 'time_stamping';
    }
    elseif (ereg('TLS Web Client Authentication', $certtext) && ereg('TLS Web Server Authentication', $certtext)) {
        $cert_type = 'vpn_client_server';
    }
    elseif (ereg('TLS Web Client Authentication', $certtext)) {
        $cert_type = 'vpn_client';
    }
    elseif (ereg('TLS Web Server Authentication', $certtext)) {
        $cert_type = 'vpn_server';
    }
    else {
        $cert_type = 'vpn_client_server';
    }

    return $cert_type;
}

function CA_get_root_pem() {
    global $config;
    return(file_get_contents($config['cacert_pem']));
}
