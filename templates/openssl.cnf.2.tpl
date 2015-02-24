[ req ]
default_bits		= 4096
default_keyfile		= privkey.pem
distinguished_name	= req_name
string_mask		= nombstr
req_extensions		= req_ext

[ req_name]
countryName			= Country Name (2 letter code)
countryName_default		= US
countryName_min			= 2
countryName_max			= 2

stateOrProvinceName		= State or Province Name (full name)
stateOrProvinceName_default	=

localityName			= Locality Name (eg, city)
localityName_default		=

0.organizationName		= Organization Name (eg, company)
0.organizationName_default	=

1.organizationName		= Second Organization Name (eg, company)
1.organizationName_default	=

organizationalUnitName		= Organizational Unit Name (eg, section)
organizationalUnitName_default	=

commonName			= Common Name (eg, YOUR name)

emailAddress			= Email Address or Web URL

[ req_ext ]
basicConstraints = critical, CA:false

