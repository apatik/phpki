<h4>You are about to create a certificate using the following information:</h4>
<table width=500>
    <tr>
        <td width=25% style='white-space: nowrap'>
            <p align=right>
                User's Name<br>
                E-mail Address<br>
                Organization<br>
                Department/Unit<br>
                Locality<br>
                State/Province<br>
                Country<br>
                Certificate Life<br>
                {if $encryptionType eq 'RSA'}
                Key Size<br>
                {else}
                Elliptic Curve<br>
                {/if}
                Certificate Use<br>
                {if $cert_type eq 'server'}
                DNS Alt Names<br>
                IP Addresses<br>
                {/if}
            </p>
        </td>

        <td>
            {$common_name}<br>
            {$email}<br>
            {$organization}<br>
            {$unit}<br>
            {$locality}<br>
            {$province}<br>
            {$country}<br>
            {$expiry} Year{if $expiry neq 1}s{/if}<br>
            {if $encryptionType eq 'RSA'}
            {$keysize} bits<br>
            {else}
            {$ecCurve}<br>
            {/if}

            {$cert_usage}<br>
            {if $cert_type eq 'server' || $cert_type eq 'client_server'}
            {$dns_names}<br>
            {$ip_addr}<br>
            {/if}
        </td>
    </tr>
</table>

<h4>Are you sure?</h4>
<p>
<form action='{$PHP_SELF}' method=post>
    <input type=hidden name="country" value="{$country}">
    <input type=hidden name="province" value="{$province}">
    <input type=hidden name="locality" value="{$locality}">
    <input type=hidden name="organization" value="{$organization}">
    <input type=hidden name="unit" value="{$unit}">
    <input type=hidden name="common_name" value="{$common_name}">
    <input type=hidden name="email" value="{$email}">
    <input type=hidden name="passwd" value="{$passwd}">
    <input type=hidden name="passwdv" value="{$passwdv}">
    <input type=hidden name="expiry" value="{$expiry}">
    <input type=hidden name="keysize" value="{$keysize}">
    <input type=hidden name="cert_type" value="{$cert_type}">
    <input type=hidden name="dns_names" value="{$dns_names}">
    <input type=hidden name="ip_addr" value="{$ip_addr}">
    <input type=hidden name="encryptionType" value="{$encryptionType}">
    <input type=hidden name="ecCurve" value="{$ecCurve}">
    <input type=hidden name="form_stage" value="final">
    <input type=submit name="submit" value="Yes!  Create and Download">&nbsp;
    <input type=submit name="submit" value="Go Back">
</form>