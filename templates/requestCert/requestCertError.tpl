<form action="{$PHP_SELF}" method="post">
    <font color=""#ff0000">
        <h2>There was an error creating your certificate.</h2>
    </font>
    <br>
    <blockquote>
        <h3>Debug Info:</h3>
        <pre>
            {$errorText}
        </pre>
    </blockquote>
    <p>
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
        <input type=hidden name=form_stage value="default">
        <input type=submit name=submit value=Back>
    <p>
</form>