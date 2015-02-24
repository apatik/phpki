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
    <input type=hidden name=country value="{$country|escape:'html'}">
    <input type=hidden name=province value="{$province|escape:'html'}">
    <input type=hidden name=locality value="{$locality|escape:'html'}">
    <input type=hidden name=organization value="{$organization|escape:'html'}">
    <input type=hidden name=unit value="{$unit|escape:'html'}">
    <input type=hidden name=contact value="{$contact|escape:'html'}">
    <input type=hidden name=common_name value="{$common_name|escape:'html'}">
    <input type=hidden name=passwd value="{$passwd|escape:'html'}">
    <input type=hidden name=passwdv value="{$passwdv|escape:'html'}">
    <input type=hidden name=expiry value="{$expiry|escape:'html'}">
    <input type=hidden name=keysize value="{$keysize|escape:'html'}">
    <input type=hidden name=base_url value="{$base_url|escape:'html'}">
    <input type=hidden name=crl_distrib value="{$crl_distrib|escape:'html'}">
    <input type=hidden name=revoke_url value="{$revoke_url|escape:'html'}">
    <input type=hidden name=policy_url value="{$policy_url|escape:'html'}">
    <input type=hidden name=comment_root value="{$comment_root|escape:'html'}">
    <input type=hidden name=comment_email value="{$comment_email|escape:'html'}">
    <input type=hidden name=comment_sign value="{$comment_sign|escape:'html'}">
    <input type=hidden name=comment_srv value="{$comment_srv|escape:'html'}">
    <input type=hidden name=comment_stamp value="{$comment_stamp|escape:'html'}">
    <input type=hidden name=openssl_bin value="{$openssl_bin|escape:'html'}">
    <input type=hidden name=getting_help value="{$getting_help|escape:'html'}">
    <input type=hidden name=ca_prefix value="{$ca_prefix|escape:'html'}">
    <input type=hidden name=header_title value="{$header_title|escape:'html'}">
    <input type=hidden name=passwd_file value="{$passwd_file|escape:'html'}">
    <input type=hidden name=store_dir value="{$store_dir|escape:'html'}">
</form>