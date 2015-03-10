<?php
require_once('./include/std_include.php');

$overwrite = gpvar('overwrite');

if (file_exists(STORE_DIR) && $overwrite !== 'yes'){
    printHeader('setup');
    $readme = file_get_contents('./README.md');
    $S->assign('readme', $readme);
    $S->display('config_exists.tpl');
    printFooter();
    exit;
}

function flush_exec($command, $line_length=200) {
    $handle = popen("$command 2>&1",'r');

    $line = '';
    while (! feof($handle)) {
        $chr = fread($handle, 1);
        $line .= $chr;
        if ($chr == "\n") {
            print str_replace("\n", "<br>\n", $line);
            $line = '';
            flush();
        }
        elseif (strlen($line) > $line_length) {
            print $line."<br>\n";
            $line = '';
            flush();
        }
    }
    print $line."<br>\n";
    flush();
    return;
}

$version = PHPKI_VERSION;

# Who does the webserver run as (apache,www-data,nginx,etc)?
$uid          = posix_getuid();
$pwdinfo      = posix_getpwuid($uid);
$uname        = $pwdinfo['name'];
$S->assign('uname', $uname);

# Permissions on the file store.
$store_perms=0770;

# Where are we?
$here         = dirname($_SERVER['SCRIPT_FILENAME']);

$PHP_SELF     = basename(__FILE__);
$S->assign('PHP_SELF', $PHP_SELF);
$S->assign('DOCUMENT_ROOT',$_SERVER['DOCUMENT_ROOT']);

$submit       = gpvar('submit');
$stage        = gpvar('stage');

$organization  = gpvar('organization');
$unit          = gpvar('unit');
$contact       = gpvar('contact');
$locality      = gpvar('locality');
$province      = gpvar('province');
$country       = gpvar('country');
$common_name   = gpvar('common_name');
$passwd        = gpvar('passwd');
$passwdv       = gpvar('passwdv');
$expiry        = gpvar('expiry');
$keysize       = gpvar('keysize');
$base_url      = gpvar('base_url');
$crl_distrib   = gpvar('crl_distrib');
$revoke_url    = gpvar('revoke_url');
$policy_url    = gpvar('policy_url');
$comment_root  = gpvar('comment_root');
$comment_email = gpvar('comment_email');
$comment_sign  = gpvar('comment_sign');
$comment_srv   = gpvar('comment_srv');
$comment_stamp = gpvar('comment_stamp');
$openssl_bin   = gpvar('openssl_bin');
$passwd_file   = gpvar('passwd_file');
$getting_help  = gpvar('getting_help');
$ca_prefix     = gpvar('ca_prefix');
$header_title  = gpvar('header_title');
$store_dir     = gpvar('store_dir');
$ecCurve        = gpvar('ecCurve');
$encryptionType = gpvar('encryptionType');

# Unmodified copied for writing to the config files - htvar gets used directly
# before displaying the main entry form to provide the security against injection
$S->assign('country',$country);
$S->assign('province',$province);
$S->assign('locality',$locality);
$S->assign('organization',$organization);
$S->assign('unit',$unit);
$S->assign('contact',$contact);
$S->assign('common_name',$common_name);
$S->assign('passwd',$passwd);
$S->assign('passwdv',$passwdv);
$S->assign('expiry',$expiry);
$S->assign('keysize',$keysize);
$S->assign('base_url',$base_url);
$S->assign('crl_distrib',$crl_distrib);
$S->assign('revoke_url',$revoke_url);
$S->assign('policy_url',$policy_url);
$S->assign('comment_root',$comment_root);
$S->assign('comment_email',$comment_email);
$S->assign('comment_sign',$comment_sign);
$S->assign('comment_srv',$comment_srv);
$S->assign('comment_stamp',$comment_stamp);
$S->assign('openssl_bin',$openssl_bin);
$S->assign('getting_help',$getting_help);
$S->assign('ca_prefix',$ca_prefix);
$S->assign('header_title',$header_title);
$S->assign('passwd_file',$passwd_file);
$S->assign('store_dir',$store_dir);
$S->assign('ecCurve', $ecCurve);

if ($base_url && substr($base_url,-1) != '/') $base_url .= '/';

# Make ECParam operations available before config.php is created
if (isset($openssl_bin) && ($stage == 'setup_stage2' || $stage == 'validate')) {

    #Make the OpenSSL Binary available temporarily to retrieve the list of available Elliptic Curves
    $openssl_bin = htmlentities($_POST['openssl_bin']);

    if(!defined('OPENSSL')) {
        define('OPENSSL', $openssl_bin . ' ');
    }
    if(!defined('ECPARAM')) {
        define('ECPARAM', OPENSSL . ' ecparam ');
    }

}

switch ($stage) {
case 'validate':
    $er   = array();
    $warn = array();

    if (! $country)      $warn[] = 'Missing Country';
    if (! $province)     $warn[] = 'Missing State/Province';
    if (! $locality)     $warn[] = 'Missing Locality';
    if (! $organization) $er[]   = 'Missing Organization';
    if (! $unit)         $warn[] = 'Missing Unit/Department';
    if (! $contact)      $warn[] = 'Missing Contact E-mail Address';
    if (! $common_name)  $er[]   = 'Missing Common Name';
    if (! $passwd)       $er[]   = 'Missing Certificate Password';
    if (! $passwdv)      $er[]   = 'Missing Certificate Password Verification "Again"';
    if (! $header_title) $er[]   = 'Missing Header Title';
    if (! $passwd_file)  $er[]   = 'Missing User Password File Location';
    if (! $store_dir)    $er[]   = 'Missing Storage Directory';


    if ( $passwd && strlen($passwd) < 8 )
        $er[] = 'Certificate password is too short';

    if ( $passwd and $passwd != $passwdv )
        $er[] = 'Password and password verification do not match';

    if ( $contact && ! is_email($contact) )
        $er[] = 'E-mail address ('. htvar($contact) . ') may be invalid.<br>';

    if (strpos($store_dir,$_SERVER['DOCUMENT_ROOT']) === 0)
        $er[] = 'Store directory must exist somewhere outside of DOCUMENT_ROOT ('.$_SERVER['DOCUMENT_ROOT'].')';

    if (strpos($store_dir,dirname($_SERVER['SCRIPT_FILENAME'])) === 0)
        $er[] = 'Store directory cannot exist within the PHPki installation directory ('.dirname($_SERVER['SCRIPT_FILENAME']).')';

    if (! $er) {
        if (! file_exists($store_dir) ) {
            if ( ! mkdir("$store_dir",$store_perms)) $er[] = "Could not create the store directory \"$store_dir\"";
        }

        if (file_exists($store_dir)) {
            if (! chmod($store_dir, $store_perms)) $er[] = "Could not change permissions on the store directory \"$store_dir\"";
            if (! is_readable($store_dir))  $er[] = "The store directory \"$store_dir\" is not readable by the web server user \"$uname\"";
            if (! is_writeable($store_dir)) $er[] = "The store directory \"$store_dir\" is not writeable by the web server user \"$uname\"";
        }
        else {
            $er[] = "Store directory \"$store_dir\" does not exist.  You will have to manually create it as desribed in the setup form.";
        }
    }

    if ($er)  {
        $S->assign('er', $er);
        $S->assign('warn', $warn);

        printHeader('setup');
        $S->display('setup_error.tpl');
        printFooter();
        break;
    }

case 'write':

    printHeader('about');

    #
    #Create the file store directory structure.
    #

    print '<strong>Creating PHPki file store...</strong><br>';
    flush();

    if (! file_exists("$store_dir/config")) mkdir("$store_dir/config",$store_perms);
    if (! file_exists("$store_dir/tmp")) mkdir("$store_dir/tmp",$store_perms);
    if (! file_exists("$store_dir/CA")) mkdir("$store_dir/CA",$store_perms);
    if (! file_exists("$store_dir/CA/certs")) mkdir("$store_dir/CA/certs",$store_perms);
    if (! file_exists("$store_dir/CA/private")) mkdir("$store_dir/CA/private",$store_perms);
    if (! file_exists("$store_dir/CA/newcerts")) mkdir("$store_dir/CA/newcerts",$store_perms);
    if (! file_exists("$store_dir/CA/requests")) mkdir("$store_dir/CA/requests",$store_perms);
    if (! file_exists("$store_dir/CA/crl")) mkdir("$store_dir/CA/crl",$store_perms);
    if (! file_exists("$store_dir/CA/pfx")) mkdir("$store_dir/CA/pfx",$store_perms);

    #
    # Create the PHPki CA configuration.
    #
    print '<strong>Writing configuration files...</strong><br>';
    flush();

    $curves = CAdb_get_curves();
    $S->assign('curves',$curves);
    $config_txt = $S->fetch('config.php.tpl');

    #
    # Write out the CA configuration file.
    #
    $fd = fopen("$store_dir/config/config.php",'w');
    fwrite($fd, $config_txt);
    fclose($fd);


    #
    # Create the bootstrap configuration
    #
    $config_txt = <<<EOS
<?php
define('PHPKI_VERSION','$version');
define('DEMO',FALSE);
define('PROJECT_DIR', __DIR__);
define('STORE_DIR','$store_dir');
define('BASE_URL','$base_url');

EOS;

    #
    # Write out the bootstrap config
    #
    $fd = fopen('./define.php','w');
    fwrite($fd, $config_txt);
    fclose($fd);


    # Re-read the CA config file so the openssl_functions
    # can be used to create a CA root certificate.
    include("$store_dir/config/config.php");

    $S->assign('config',$config);

    #
    # Now create a temporary openssl.cnf for creating a self-signed
    # CA root certificate, and create a generic openssl.cnf file
    # in the CA home
    #
    $config_txt1 = $S->fetch('openssl.cnf.1.tpl');

    $config_txt2 = $S->fetch('openssl.cnf.2.tpl');

    $config_txt3 = $S->fetch('openssl.cnf.3.tpl');

    #
    # Write the permanent OpenSSL config
    #
    $fd = fopen($config['openssl_cnf'],'w');
    fwrite($fd, $config_txt1 . $config_txt2);
    fclose($fd);

    #
    # Write the temporary OpenSSL config
    #
    $tmp_cnf = "$config[home_dir]/tmp/openssl.cnf";
    $fd = fopen($tmp_cnf,'w');
    fwrite($fd, "$config_txt1 $config_txt3");
    fclose($fd);

    #
    # Intialize index.txt and serial files
    #
    $fd = fopen($config['index'],'w');
    fwrite($fd, "");
    fclose($fd);
    #
    $fd = fopen($config['serial'],'w');
    fwrite($fd, "100001");
    fclose($fd);

    #
    # Convert expiry years to approximate days.
    #
    $days = $config['expiry'] * 365.25;

    #
    # Create a new self-signed CA certificate in PEM format.
    #
    print '<strong>Creating root certificate...</strong><br>';
    flush();

    # Setup EC Parameter files for all curves right away to simplify things up later
    if($curves) {
        foreach ($curves as $curve) {
            exec(ECPARAM . " -out " . $config["ca_ecparam_$curve"] . " -name $curve");
        }
    }

    if ($encryptionType == 'RSA') {
        exec(REQ . " -x509 -config $tmp_cnf -extensions root_ext -newkey rsa:$keysize -keyout $config[cakey] -out $config[cacert_pem] -passout pass:'$config[ca_pwd]' -days $days 2>&1");
    } elseif ($encryptionType == 'EC') {
        exec(OPENSSL . " genpkey -paramfile " . $config["ca_ecparam_$ecCurve"] . " -out $config[cakey] 2>&1");
        exec(REQ . " -x509 -config $tmp_cnf -extensions root_ext -new -key $config[cakey] -out $config[cacert_pem] -passout pass:'$config[ca_pwd]' -days $days 2>&1");
    }

    #
    # Generate the initial CRL.
    #
    print '<strong>Generating certificate revocation list...</strong><br>';
    flush();
    exec(CA . " -gencrl -config $config[openssl_cnf] -out $config[cacrl_pem] -passin pass:'$config[ca_pwd]'");

    # Make a copy of the CRL in DER format.
    #
    exec(CRL . " -in $config[cacrl_pem] -out $config[cacrl_der] -inform PEM -outform DER");

    ?>
    <center>
        <h2>Setup is complete. Your CA root certificate as been created.</h2>
        <h3><font color=red>SECURITY WARNING!&nbsp;&nbsp; Be sure to run the <cite>secure.sh</cite> shell script as the <strong>root</strong> user.</font></h3>
        <p><br><br>
        <form action=index.php>
            <input type=submit name=submit>
        </form>
    </center>
    <?php
    printFooter();
    break;

case "setup_stage2":
    $curves         = CAdb_get_curves();
    if (! $country) $country = isset($config['country']) ? $config['country'] : null;
    if (! $province) $province = isset($config['province']) ? $config['province'] : null;
    if (! $locality) $locality = isset($config['locality']) ? $config['locality'] : null;
    if (! $organization) $organization = isset($config['organization']) ? $config['organization'] : null;
    if (! $contact) $contact = isset($config['contact']) ? $config['contact'] : null;
    if (! $expiry) $expiry = isset($config['expiry']) ? $config['expiry'] : 10;
    if (! $keysize) $keysize = isset($config['keysize']) ? $config['keysize'] : 4096;
    if (! $passwd) $passwd = isset($config['ca_pwd']) ? $config['ca_pwd'] : null;
    if (! $passwdv) $passwdv = isset($passwd) ? $passwd : null;

    if (! $unit) $unit = isset($config['unit']) ? $config['unit'] : "Certificate Authority";

    if (! $common_name) $common_name =  isset($config['common_name']) ? $config['common_name'] : "PHPki Certificate Authority";

    if (! $getting_help) $getting_help = isset($config['getting_help']) ? $config['getting_help'] : '
<b>Contact:</b><br>
First-Name Last-Name<br>
Company/Organization Name<br>
Address Line #1<br>
Address Line #2<br>
City, State, ZipCode<br>
<br>
Phone: (000) 000-0000<br>
E-mail: <a href=mailto:someone@somewhere.com>someone@somewhere.com</a>&nbsp;&nbsp;&nbsp;<i><b>E-mail is preferred.</b></i><br>';

    if (! $store_dir) $store_dir = dirname($_SERVER['DOCUMENT_ROOT']).'/phpki-store';

    if (! $base_url) $base_url = isset($config['base_url']) ? $config['base_url'] : 'http://www.somewhere.com/phpki/';

    if (! $crl_distrib) $crl_distrib = 'index.php?stage=dl_crl';
    if (! $revoke_url) $revoke_url = 'ns_revoke_query.php?';
    if (! $policy_url) $policy_url = 'policy.html';

    if (! $comment_root) $comment_root = 'PHPki/OpenSSL Generated Root Certificate Authority';
    if (! $comment_email) $comment_email = 'PHPki/OpenSSL Generated Personal Certificate';
    if (! $comment_sign) $comment_sign = 'PHPki/OpenSSL Generated Personal Certificate';
    if (! $comment_srv) $comment_srv = 'PHPki/OpenSSL Generated Secure Server Certificate';
    if (! $comment_stamp) $comment_stamp = 'PHPki/OpenSSL Generated Time Stamping Certificate';

    if (! $ca_prefix) $ca_prefix = isset($config['ca_prefix']) ? $config['ca_prefix'] : null;

    if (! $openssl_bin) $openssl_bin = isset($config['openssl_bin']) ? $config['openssl_bin'] : '/usr/bin/openssl';

    if (! $passwd_file) $passwd_file = isset($config['passwd_file']) ? $config['passwd_file'] : dirname($_SERVER['DOCUMENT_ROOT']).'/phpkipasswd';

    if (! $header_title) $header_title = isset($config['header_title']) ? $config['header_title'] : 'Certificate Authority';

    printHeader('setup');
    $S->assign('country',htvar($country));
    $S->assign('province',htvar($province));
    $S->assign('locality',htvar($locality));
    $S->assign('organization',htvar($organization));
    $S->assign('unit',htvar($unit));
    $S->assign('contact',htvar($contact));
    $S->assign('common_name',htvar($common_name));
    $S->assign('passwd',htvar($passwd));
    $S->assign('passwdv',htvar($passwdv));
    $S->assign('expiry',htvar($expiry));
    $S->assign('keysize',htvar($keysize));
    $S->assign('base_url',htvar($base_url));
    $S->assign('crl_distrib',htvar($crl_distrib));
    $S->assign('revoke_url',htvar($revoke_url));
    $S->assign('policy_url',htvar($policy_url));
    $S->assign('comment_root',htvar($comment_root));
    $S->assign('comment_email',htvar($comment_email));
    $S->assign('comment_sign',htvar($comment_sign));
    $S->assign('comment_srv',htvar($comment_srv));
    $S->assign('comment_stamp',htvar($comment_stamp));
    $S->assign('openssl_bin',htvar($openssl_bin));
    $S->assign('getting_help',htvar($getting_help));
    $S->assign('ca_prefix',htvar($ca_prefix));
    $S->assign('header_title',htvar($header_title));
    $S->assign('passwd_file',htvar($passwd_file));
    $S->assign('store_dir',htvar($store_dir));
    $S->assign('ecCurve',htvar($ecCurve));
    $S->assign('encryptionType',htvar($encryptionType));
    $S->assign('curves',$curves);
    $S->display('setupForm2.tpl');
    printFooter();
    break;
        break;
default:
    if (! $openssl_bin) $openssl_bin = isset($config['openssl_bin']) ? $config['openssl_bin'] : '/usr/bin/openssl';
    $S->assign('openssl_bin',htvar($openssl_bin));
    printHeader();
    $S->display('setupForm1.tpl');
    printFooter();

    break;
}
