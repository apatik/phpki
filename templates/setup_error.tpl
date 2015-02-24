<form action='{$PHP_SELF}' method=post>
    <input type=submit name=Submit value='Go Back'>
    <font color=#ff0000>
    <h2>ERROR(S) IN FORM:</h2>
        <h4>
            <blockquote>
                {foreach from=$er item=msg}
                    {$msg}<br/>
                {/foreach}
            </blockquote>
        </h4>
    </font>
    <font color=#0000ff>
        <h2>WARNING(S) IN FORM:</h2>
        <h4>
            <blockquote>
                {foreach from=$warn item=msg}
                    {$msg}<br/>
                {/foreach}
            </blockquote>
        </h4>
    </font>
    <br>
    <input type=submit name=Submit2 value='Go Back'>
    <input type=hidden name=country value="{$country}">
    <input type=hidden name=province value="{$province}">
    <input type=hidden name=locality value="{$locality}">
    <input type=hidden name=organization value="{$organization}">
    <input type=hidden name=unit value="{$unit}">
    <input type=hidden name=contact value="{$contact}">
    <input type=hidden name=common_name value="{$common_name}">
    <input type=hidden name=passwd value="{$passwd}">
    <input type=hidden name=passwdv value="{$passwdv}">
    <input type=hidden name=expiry value="{$expiry}">
    <input type=hidden name=keysize value="{$keysize}">
    <input type=hidden name=base_url value="{$base_url}">
    <input type=hidden name=crl_distrib value="{$crl_distrib}">
    <input type=hidden name=revoke_url value="{$revoke_url}">
    <input type=hidden name=policy_url value="{$policy_url}">
    <input type=hidden name=openssl_bin value="{$openssl_bin}">
    <input type=hidden name=getting_help value="{$getting_help}">
    <input type=hidden name=ca_prefix value="{$ca_prefix}">
    <input type=hidden name=header_title value="{$header_title}">
    <input type=hidden name=passwd_file value="{$passwd_file}">
    <input type=hidden name=store_dir value="{$store_dir}">
</form>