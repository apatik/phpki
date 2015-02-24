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
                Key Size<br>
                Certificate Use<br>
                {if $cert_type eq 'server'}
                DNS Alt Names<br>
                IP Addresses<br>
                {/if}
            </p>
        </td>

        <td>
            {$common_name|escape:'html'}<br>
            {$email|escape:'html'}<br>
            {$organization|escape:'html'}<br>
            {$unit|escape:'html'}<br>
            {$locality|escape:'html'}<br>
            {$province|escape:'html'}<br>
            {$country|escape:'html'}<br>
            {$expiry|escape:'html'} Year{if $expiry neq 1}s{/if}<br>
            {$keysize|escape:'html'} bits<br>

            {$cert_usage|escape:'html'}<br>
            {if $cert_type eq 'server'}
            {$dns_names|escape:'html'}<br>
            {$ip_addr|escape:'html'}<br>
            {/if}
        </td>
    </tr>
</table>

<h4>Are you sure?</h4>
<p>
<form action='{$PHP_SELF}' method=post>
    <input type=hidden name="country" value="{$country|escape:'html'}">
    <input type=hidden name="province" value="{$province|escape:'html'}">
    <input type=hidden name="locality" value="{$locality|escape:'html'}">
    <input type=hidden name="organization" value="{$organization|escape:'html'}">
    <input type=hidden name="unit" value="{$unit|escape:'html'}">
    <input type=hidden name="common_name" value="{$common_name|escape:'html'}">
    <input type=hidden name="email" value="{$email|escape:'html'}">
    <input type=hidden name="passwd" value="{$passwd|escape:'html'}">
    <input type=hidden name="passwdv" value="{$passwdv|escape:'html'}">
    <input type=hidden name="expiry" value="{$expiry|escape:'html'}">
    <input type=hidden name="keysize" value="{$keysize|escape:'html'}">
    <input type=hidden name="cert_type" value="{$cert_type|escape:'html'}">
    <input type=hidden name="dns_names" value="{$dns_names|escape:'html'}">
    <input type=hidden name="ip_addr" value="{$ip_addr|escape:'html'}">
    <input type=hidden name="form_stage" value="final">
    <input type=submit name="submit" value="Yes!  Create and Download">&nbsp;
    <input type=submit name="submit" value="Go Back">
</form>