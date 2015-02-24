HOME             = {$config.home_dir}
RANDFILE         = {$config.random}
dir	             = {$config.ca_dir}
certs            = {$config.cert_dir}
crl_dir	         = {$config.crl_dir}
database         = {$config.index}
new_certs_dir    = {$config.new_certs_dir}
private_dir      = {$config.private_dir}
serial           = {$config.serial}
certificate      = {$config.cacert_pem}
crl              = {$config.cacrl_pem}
private_key      = {$config.cakey}
crl_extentions	 = crl_ext
default_days     = 365
default_crl_days = 30
preserve         = no
default_md       = sha512

[ req ]
default_bits        = {$keysize}
string_mask         = nombstr
prompt              = no
distinguished_name  = req_name
req_extensions      = req_ext

[ req_name]
{if $country}C={$country}{/if}
{if $province}ST={$province}{/if}
{if $locality}L={$locality}{/if}
{if $organization}O={$organization}{/if}
{if $unit}OU={$unit}{/if}
{if $common_name}CN={$common_name}{/if}
{if $email}emailAddress={$email}{/if}

[ ca ]
default_ca             = email_cert

[ root_cert ]
x509_extensions        = root_ext
default_days           = 3650
policy                 = policy_supplied

[ email_cert ]
x509_extensions        = email_ext
default_days           = 365
policy                 = policy_supplied

[ email_signing_cert ]
x509_extensions        = email_signing_ext
default_days           = 365
policy                 = policy_supplied

[ server_cert ]
x509_extensions        = server_ext
default_days           = 365
policy                 = policy_supplied

[ vpn_cert ]
x509_extensions        = vpn_client_server_ext
default_days           = 365
policy                 = policy_supplied

[ time_stamping_cert ]
x509_extensions        = time_stamping_ext
default_days           = 365
policy                 = policy_supplied


[ policy_supplied ]
{if $country}countryName            = supplied{/if}
{if $province}stateOrProvinceName    = supplied{/if}
{if $locality}localityName           = supplied{/if}
{if $organization}organizationName       = supplied{/if}
{if $unit}organizationalUnitName = supplied{/if}
{if $common_name}commonName             = supplied{/if}
{if $email}emailAddress           = supplied{/if}

[ req_ext]
basicConstraints = CA:false

[ crl_ext ]
issuerAltName=issuer:copy
authorityKeyIdentifier=keyid:always,issuer:always

[ root_ext ]
basicConstraints       = CA:true
keyUsage               = cRLSign, keyCertSign
nsCertType             = sslCA, emailCA, objCA
subjectKeyIdentifier   = hash
{if $email}subjectAltName         = email:copy{/if}
crlDistributionPoints  = URI:{$config.base_url}{$config.crl_distrib}
nsComment              = {$config.comment_root}
#nsCaRevocationUrl     =
nsCaPolicyUrl          = {$config.base_url}{$config.policy_url}

[ email_ext ]
basicConstraints       = critical, CA:false
keyUsage               = critical, nonRepudiation, digitalSignature, keyEncipherment
extendedKeyUsage       = critical, emailProtection, clientAuth
nsCertType             = critical, client, email
subjectKeyIdentifier   = hash
authorityKeyIdentifier = keyid:always, issuer:always
subjectAltName         = email:copy
issuerAltName          = issuer:copy
crlDistributionPoints  = URI:{$config.base_url}{$config.crl_distrib}
nsComment              = {$config.comment_email}
nsBaseUrl              = {$config.base_url}
nsRevocationUrl        = {$config.base_url}{$config.revoke_url}{$serial}
nsCaPolicyUrl          = {$config.base_url}{$config.policy_url}

[ email_signing_ext ]
basicConstraints       = critical, CA:false
keyUsage               = critical, nonRepudiation, digitalSignature, keyEncipherment
extendedKeyUsage       = critical, emailProtection, clientAuth, codeSigning
nsCertType             = critical, client, email
subjectKeyIdentifier   = hash
authorityKeyIdentifier = keyid:always, issuer:always
subjectAltName         = email:copy
issuerAltName          = issuer:copy
crlDistributionPoints  = URI:{$config.base_url}{$config.crl_distrib}
nsComment              = {$config.comment_sign}
nsBaseUrl              = {$config.base_url}
nsRevocationUrl        = {$config.base_url}{$config.revoke_url}{$serial}
nsCaPolicyUrl          = {$config.base_url}{$config.policy_url}

[ server_ext ]
basicConstraints        = critical, CA:false
keyUsage                = critical, digitalSignature, keyEncipherment
nsCertType              = server
extendedKeyUsage        = critical, serverAuth
subjectKeyIdentifier    = hash
authorityKeyIdentifier  = keyid:always, issuer:always
subjectAltName          = {$server_altnames}
issuerAltName           = issuer:copy
crlDistributionPoints   = URI:{$config.base_url}{$config.crl_distrib}
nsComment               = {$config.comment_srv}
nsBaseUrl               = {$config.base_url}
nsRevocationUrl         = {$config.base_url}{$config.revoke_url}{$serial}
nsCaPolicyUrl           = {$config.base_url}{$config.policy_url}

[ time_stamping_ext ]
basicConstraints       = CA:false
keyUsage               = critical, nonRepudiation, digitalSignature
extendedKeyUsage       = timeStamping
subjectKeyIdentifier   = hash
authorityKeyIdentifier = keyid:always, issuer:always
subjectAltName         = DNS:{$common_name}{if $email},email:copy{/if}
issuerAltName          = issuer:copy
crlDistributionPoints  = URI:{$config.base_url}{$config.crl_distrib}
nsComment              = {$config.comment_stamp}
nsBaseUrl              = {$config.base_url}
nsRevocationUrl        = {$config.base_url}{$config.revoke_url}{$serial}

[ vpn_client_ext ]
basicConstraints        = critical, CA:false
keyUsage                = critical, digitalSignature
extendedKeyUsage        = critical, clientAuth
nsCertType              = critical, client
subjectKeyIdentifier    = hash
authorityKeyIdentifier  = keyid:always, issuer:always
subjectAltName          = DNS:{$common_name}{if $email},email:copy{/if}

[ vpn_server_ext ]
basicConstraints        = critical, CA:false
keyUsage                = critical, digitalSignature, keyEncipherment
extendedKeyUsage        = critical, serverAuth
nsCertType              = critical, server
subjectKeyIdentifier    = hash
authorityKeyIdentifier  = keyid:always, issuer:always
subjectAltName          = DNS:{$common_name}{if $email},email:copy{/if}

[ vpn_client_server_ext ]
basicConstraints        = critical, CA:false
keyUsage                = critical, digitalSignature, keyEncipherment
extendedKeyUsage        = critical, serverAuth, clientAuth
nsCertType              = critical, server, client
subjectKeyIdentifier    = hash
authorityKeyIdentifier  = keyid:always, issuer:always
subjectAltName          = DNS:{$common_name}{if $email},email:copy{/if}

[alt_names]
{$alt_names}