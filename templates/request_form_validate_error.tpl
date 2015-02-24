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
    <input type=hidden name=country value="{$country}">
    <input type=hidden name=province value="{$province}">
    <input type=hidden name=locality value="{$locality}">
    <input type=hidden name=organization value="{$organization}">
    <input type=hidden name=unit value="{$unit}">
    <input type=hidden name=common_name value="{$common_name}">
    <input type=hidden name=email value="{$email}">
    <input type=hidden name=passwd value="{$passwd}">
    <input type=hidden name=passwdv value="{$passwdv}">
    <input type=hidden name=expiry value="{$expiry}">
    <input type=hidden name=keysize value="{$keysize}">
    <input type=hidden name=cert_type value="{$cert_type}">
    <input type=hidden name=dns_names value="{$dns_names}">
    <input type=hidden name=ip_addr value="{$ip_addr}">
</form>