[ req ]
default_bits		= 4096
default_keyfile		= privkey.pem
distinguished_name	= req_name
string_mask		    = nombstr
req_extensions		= req_ext
prompt			    = no

[ req_name ]
{if $config.country}
C={$config.country}
{/if}

{if $config.province}
ST={$config.province}
{/if}

{if $config.locality}
L={config.$locality}
{/if}

{if $config.organization}
O={config.$organization}
{/if}

{if $config.unit}
OU={$config.unit}
{/if}

{if $config.common_name}
CN={$config.common_name}
{/if}

{if $config.email}
emailAddress={$config.email}
{/if}

[ req_ext ]
basicConstraints = critical, CA:true

