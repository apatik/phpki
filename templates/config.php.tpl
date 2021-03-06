<?php
# PHPki CONFIGURATION FILE
# Automatically generated by PHPki.  Edit at your own peril.
#
$config['organization'] = '{$organization}';
$config['unit']         = '{$unit}';
$config['contact']      = '{$contact}';
$config['locality']     = '{$locality}';
$config['province']     = '{$province}';
$config['country']      = '{$country}';
$config['common_name']  = '{$common_name}';

# Store Directory
$config['store_dir'] = '{$store_dir}';

# Location HTTP Password File
$config['passwd_file'] = '{$passwd_file}';

# Password for CA root certificate.
$config['ca_pwd'] = '{$passwd}';

# Number of years the root certificate is good.
$config['expiry'] = '{$expiry}';

# CA certificate key size
$config['keysize'] = '{$keysize}';

# This is superimposed over the PHPki logo on each page.
$config['header_title'] = '{$header_title}';

# String to prefix cer and crl uploads
$config['ca_prefix'] = '{$ca_prefix}';

# Location of your OpenSSL binary.
$config['openssl_bin'] = '{$openssl_bin}';

# Base URL
$config['base_url']  = '{$base_url}';

# CRL Distribution points path
$config['crl_distrib']  = '{$crl_distrib}';

# Certificate Revocation URL
$config['revoke_url']  = '{$revoke_url}';

# Certificate Authority Policy URL
$config['policy_url']  = '{$policy_url}';

# Preferred Elliptic Curve to use for EC certificates
$config['ecCurve'] = '{$ecCurve}';

# Who users should contact if they have technical difficulty with
# your certificate authority site.
$config['getting_help'] = '{$getting_help}';

#
# You shouldn't change anything below this line.  If you do, don't
# ask for help!
#
$config['home_dir']      = $config['store_dir'];
$config['ca_dir']        = $config['home_dir'] . '/CA';
$config['private_dir']   = $config['ca_dir']   . '/private';
$config['new_certs_dir'] = $config['ca_dir']   . '/newcerts';
$config['cert_dir']      = $config['ca_dir']   . '/certs';
$config['req_dir']       = $config['ca_dir']   . '/requests';
$config['crl_dir']       = $config['ca_dir']   . '/crl';
$config['pfx_dir']       = $config['ca_dir']   . '/pfx';
$config['index']         = $config['ca_dir']   . '/index.txt';
$config['serial']        = $config['ca_dir']   . '/serial';
$config['random']        = $config['ca_dir'] . '/.rnd';
$config['cacert_pem']    = $config['cert_dir'] . '/cacert.pem';
$config['cacrl_pem']     = $config['crl_dir'] . '/cacrl.pem';
$config['cacrl_der']     = $config['crl_dir'] . '/cacrl.crl';
$config['cakey']         = $config['private_dir'] . '/cakey.pem';
{foreach from=$curves item=curve}
$config['ca_ecparam_{$curve}']      = $config['private_dir'] . '/ecparam_{$curve}.pem';
{/foreach}

# Default OpenSSL Config File.
$config['openssl_cnf']   = $config['home_dir'] . '/config/openssl.cnf';

$PHPki_admins = Array(md5('pkiadmin'));

if(!defined('OPENSSL')){
define('OPENSSL',$config['openssl_bin'].' ');
}
define('X509', OPENSSL . ' x509 ');
define('PKCS12', "RANDFILE='$config[random]' " . OPENSSL . ' pkcs12 ');
define('CA', OPENSSL . ' ca ');
define('REQ', OPENSSL . ' req ');
define('CRL', OPENSSL . ' crl ');
if(!defined('ECPARAM')){
define('ECPARAM', OPENSSL . ' ecparam ');
}
define('EC', OPENSSL . ' ec ');
