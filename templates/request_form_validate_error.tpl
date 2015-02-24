<form action='{$PHP_SELF}' method=post>
    <input type=submit name=submit value='Go Back'>
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
    <input type=submit name=submit value='Go Back'>
    <input type=hidden name=country value="{$country|escape:'html'}">
    <input type=hidden name=province value="{$province|escape:'html'}">
    <input type=hidden name=locality value="{$locality|escape:'html'}">
    <input type=hidden name=organization value="{$organization|escape:'html'}">
    <input type=hidden name=unit value="{$unit|escape:'html'}">
    <input type=hidden name=common_name value="{$common_name|escape:'html'}">
    <input type=hidden name=email value="{$email|escape:'html'}">
    <input type=hidden name=passwd value="{$passwd|escape:'html'}">
    <input type=hidden name=passwdv value="{$passwdv|escape:'html'}">
    <input type=hidden name=expiry value="{$expiry|escape:'html'}">
    <input type=hidden name=keysize value="{$keysize|escape:'html'}">
    <input type=hidden name=cert_type value="{$cert_type|escape:'html'}">
    <input type=hidden name=dns_names value="{$dns_names|escape:'html'}">
    <input type=hidden name=ip_addr value="{$ip_addr|escape:'html'}">
</form>