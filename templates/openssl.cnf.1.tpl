HOME			 = {$config.home_dir}
RANDFILE		 = {$config.random}
dir			     = {$config.ca_dir}
certs			 = {$config.cert_dir}
crl_dir			 = {$config.crl_dir}
database		 = {$config.index}
new_certs_dir	 = {$config.new_certs_dir}
private_dir		 = {$config.private_dir}
serial			 = {$config.serial}
certificate		 = {$config.cacert_pem}
crl			     = {$config.cacrl_pem}
private_key		 = {$config.cakey}
crl_extensions	 = crl_ext
default_days	 = 365
default_crl_days = 30
preserve	 	 = no
default_md	 	 = sha512

[ ca ]
default_ca		= server_cert

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
countryName            = supplied
stateOrProvinceName    = supplied
localityName           = supplied
organizationName       = supplied
organizationalUnitName = supplied
commonName             = supplied
emailAddress           = supplied

[ root_ext ]
basicConstraints       = CA:true
keyUsage               = cRLSign, keyCertSign
subjectKeyIdentifier   = hash
subjectAltName         = email:copy
crlDistributionPoints  = URI:{$config.base_url}{$config.crl_distrib}
#nsCaRevocationUrl     =
nsCaPolicyUrl          = {$config.base_url}{$config.policy_url}

[ email_ext ]
basicConstraints       = critical, CA:false
keyUsage               = critical, nonRepudiation, digitalSignature, keyEncipherment
extendedKeyUsage       = critical, emailProtection, clientAuth
subjectKeyIdentifier   = hash
authorityKeyIdentifier = keyid:always, issuer:always
subjectAltName         = email:copy
issuerAltName          = issuer:copy
crlDistributionPoints  = URI:{$config.base_url}{$config.crl_distrib}
nsBaseUrl              = {$config.base_url}
nsRevocationUrl        = {$config.revoke_url}
#nsRenewalUrl          =
nsCaPolicyUrl          = {$config.base_url}{$config.policy_url}
#nsSslServerName       =

[ email_signing_ext ]
basicConstraints       = critical, CA:false
keyUsage               = critical, nonRepudiation, digitalSignature, keyEncipherment
extendedKeyUsage       = critical, emailProtection, clientAuth, codeSigning
subjectKeyIdentifier   = hash
authorityKeyIdentifier = keyid:always, issuer:always
subjectAltName         = email:copy
issuerAltName          = issuer:copy
crlDistributionPoints  = URI:{$config.base_url}{$config.crl_distrib}
nsBaseUrl              = {$config.base_url}
nsRevocationUrl	       = {$config.revoke_url}
#nsRenewalUrl          =
nsCaPolicyUrl          = {$config.base_url}{$config.policy_url}
#nsSslServerName       =

[ server_ext ]
basicConstraints        = CA:false
keyUsage                = critical, digitalSignature, keyEncipherment
extendedKeyUsage        = critical, serverAuth, 1.3.6.1.5.5.7.3.1
subjectKeyIdentifier    = hash
authorityKeyIdentifier  = keyid:always, issuer:always
subjectAltName          = DNS:{$common_name},email:copy
issuerAltName           = issuer:copy
crlDistributionPoints   = URI:{$config.base_url}{$config.crl_distrib}
nsBaseUrl               = {$config.base_url}
nsRevocationUrl         = {$config.revoke_url}
nsCaPolicyUrl           = {$config.base_url}{$config.policy_url}

[ time_stamping_ext ]
basicConstraints       = CA:false
keyUsage               = critical, nonRepudiation, digitalSignature
extendedKeyUsage       = timeStamping
subjectKeyIdentifier   = hash
authorityKeyIdentifier = keyid:always, issuer:always
subjectAltName         = DNS:{$common_name},email:copy
issuerAltName          = issuer:copy
crlDistributionPoints  = URI:{$config.base_url}{$config.crl_distrib}
nsBaseUrl              = {$config.base_url}
nsRevocationUrl        = {$config.revoke_url}
nsCaPolicyUrl          = {$config.base_url}{$config.policy_url}

[ vpn_client_ext ]
basicConstraints        = critical, CA:false
keyUsage                = critical, digitalSignature
extendedKeyUsage        = critical, clientAuth
subjectKeyIdentifier    = hash
authorityKeyIdentifier  = keyid:always, issuer:always
subjectAltName          = DNS:{$common_name},email:copy

[ vpn_server_ext ]
basicConstraints        = critical, CA:false
keyUsage                = critical, digitalSignature, keyEncipherment
extendedKeyUsage        = critical, serverAuth
subjectKeyIdentifier    = hash
authorityKeyIdentifier  = keyid:always, issuer:always
subjectAltName          = DNS:{$common_name},email:copy

[ vpn_client_server_ext ]
basicConstraints        = critical, CA:false
keyUsage                = critical, digitalSignature, keyEncipherment
extendedKeyUsage        = critical, serverAuth, clientAuth
subjectKeyIdentifier    = hash
authorityKeyIdentifier  = keyid:always, issuer:always
subjectAltName          = DNS:{$common_name},email:copy

[ crl_ext ]
issuerAltName=issuer:copy
authorityKeyIdentifier=keyid:always,issuer:always

