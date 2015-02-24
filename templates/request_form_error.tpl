<form action="{$PHP_SELF}" method="post">
    <font color=""#ff0000">
        <h2>There was an error creating your certificate.</h2>
    </font>
    <br>
    <blockquote>
        <h3>Debug Info:</h3>
        <pre>
            {$errtxt|escape:'html'}
        </pre>
    </blockquote>
    <p>
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
        <input type=hidden name=form_stage value="default">
        <input type=submit name=submit value=Back>
    <p>
</form>