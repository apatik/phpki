[ req ]
default_bits		= 4096
default_keyfile		= privkey.pem
distinguished_name	= req_name
string_mask		    = nombstr
req_extensions		= req_ext
prompt			    = no

[ req_name ]
C		     = {$config.country}
ST		     = {$config.province}
L		     = {$config.locality}
O		     = {$config.organization}
OU		     = {$config.unit}
CN		     = {$config.common_name}
emailAddress = {$config.contact}

[ req_ext ]
basicConstraints = critical, CA:true

