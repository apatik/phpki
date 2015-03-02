<form action={$PHP_SELF} method=post>
    <center><h2>Certificate Authority Initial Setup</h2></center>
    <table width="99%">
        <tr>
            <th colspan=2><h3>Root Certificate Data</h3></th>
        </tr>

        <tr>
            <td width="35%"><strong>Organization</strong> <font color=red>*</font></td>
            <td><input type=text name=organization value="{$organization}" maxlength=60 size=50></td>
        </tr>

        <tr>
            <td><strong>Department/Unit</strong> <font color=red>*</font></td>
            <td><input type=text name=unit value="{$unit}" maxlength=60 size=30></td>
        </tr>

        <tr>
            <td>
                <strong>Common Name</strong> <font color=red>*</font>
                This is embeded in certificates, and is most often displayed in
                e-mail clients as the <cite>Issued By:</cite> text.  This is usually
                the full name of your certificate authority (i.e. ACME Certificate Authority).
            </td>
            <td><input type=text name=common_name value="{$common_name}" maxlength=60 size=60></td>
        </tr>

        <tr>
            <td>
                <strong>Technical Contact E-mail Address</strong> <font color=red>*</font><br>
                Enter an e-mail address where users should send correspondence
                regarding your certificate authority and the certificates you issue.
            </td>

            <td><input type=text name=contact value="{$contact}" maxlength=60 size=30></td>
        </tr>

        <tr>
            <td><strong>Locality</strong> <font color=red>*</font></td>
            <td><input type=text name=locality value="{$locality}" maxlength=60 size=30></td>
        </tr>

        <tr>
            <td><strong>State/Province</strong> <font color=red>*</font></td>
            <td><input type=text name=province value="{$province}" maxlength=60 size=20></td>
        </tr>

        <tr>
            <td><strong>Country</strong> <font color=red>*</font></td>
            <td><input type=text name=country value="{$country}" maxlength=2 size=2></td>
        </tr>

        <tr>
            <td>
                <strong>Password</strong> <font color=red>*</font><br>
                This password will be used to protect your root certificate private
                key.<br/>Can't contain single quote ! <strong><font color=red>Do not lose or forget this password.</font></strong>
            </td>
            <td><input type=password name=passwd value="{$passwd}" size=30>&nbsp;&nbsp; Again <input type=password name=passwdv value="{$passwdv}" size=30></td>
        </tr>

        <tr>
            <td>
                <strong>Certificate Life</strong> <font color=red>*</font><br>
                Enter the number of years you wish your root certificate to be valid.
            </td>
            <td>
                <select name=expiry>
                {for $i=5 to 20 step 5}
                    <option value={$i} {if $expiry eq $i}selected='selected'{/if}">{$i} years</option>
                {/for}
                </select>
            </td>
        </tr>

        <tr>
            <td>
                Certificate Key Type
            </td>
            <td>
                <select name="encryptionType" onchange={literal}"if (this.value=='EC'){setVisibility('curve',true); setVisibility('RSAKeySize',false);} else {setVisibility('curve',false); setVisibility('RSAKeySize',true);}"{/literal}>
                    <option value="RSA">RSA</option>
                    <option value="EC">Elliptic Curve</option>
                </select>
            </td>
        </tr>

        <tr id="curve" style="visibility: hidden; display: none;">
            <td>
                Elliptic Curve to Use
            </td>
            <td>
                <select name="ecCurve">
                    {foreach $curves as $curve}
                        <option value="{$curve}" {if $ecCurve eq $curve}selected='selected'{/if}">{$curve}</option>
                    {/foreach}
                </select>
            </td>
        </tr>

        <tr id="RSAKeySize">
            <td>
                <strong>Key Size</strong> <font color=red>*</font><br>
                Enter the size of your certificate key.
            </td>
            <td>
                <select name=keysize>
                    {for $i=512 to 4096 step 512}
                        <option value={$i} {if $keysize eq $i}selected='selected'{/if}">{$i} bits</option>
                    {/for}
                </select>
            </td>
        </tr>

        <tr>
            <td>
                <strong>Certificate Authority Base URL</strong><br>
                Enter the public Web address where your Certificate Authority will
                reside.  The address should end with a trailing slash (/) character.
                This address will be embeded in all certficates issued
                by your CA, for informational purposes.
            </td>
            <td>
                <input type=text name=base_url value="{$base_url}" size=50>
            </td>
        </tr>

        <tr>
            <td>
                <strong>Certificate Authority CRL Distribution Points</strong><br>
                Provide the public URL where Certificate Revocation List (CRL) of
                your CA will reside. This path is relative to Base URL above.
                You may leave it by default if your clients have direct access to PHPki.
            </td>
            <td>
                <input type=text name=crl_distrib value="{$crl_distrib}" size=50>
            </td>
        </tr>

        <tr>
            <td>
                <strong>Certificate Authority Revocation Check URL</strong><br>
                Provide the public URL where clients of your CA can check if the requested
                certificate has been revoked. This path is relative to Base URL above.
                You may leave it by default if your clients have direct access to PHPki.
            </td>
            <td>
                <input type=text name=revoke_url value="{$revoke_url}" size=50>
            </td>
        </tr>

        <tr>
            <td>
                <strong>Certificate Authority Policy URL</strong><br>
                Provide the public URL where your CA policy will reside.
                This path is relative to Base URL above.
                You may leave it by default or adjust to your environment.
            </td>
            <td>
                <input type=text name=policy_url value="{$policy_url}" size=50>
            </td>
        </tr>

        <tr>
            <td>
                <strong>Root Certificate Comment</strong><br>
                Root certificate Comment attribute. You may change it to something
                or use the default value set by PHPki.
            </td>
            <td>
                <input type=text name=comment_root value="{$comment_root}" size=50>
            </td>
        </tr>

        <tr>
            <td>
                <strong>Email Certificate Comment</strong><br>
                Email certificate Comment attribute. You may change it to something
                or use the default value set by PHPki.
            </td>
            <td>
                <input type=text name=comment_email value="{$comment_email}" size=50>
            </td>
        </tr>

        <tr>
            <td>
                <strong>Email/Signing Certificate Comment</strong><br>
                Email and signing certificate Comment attribute. You may change it
                to something or use the default value set by PHPki.
            </td>
            <td>
                <input type=text name=comment_sign value="{$comment_sign}" size=50>
            </td>
        </tr>

        <tr>
            <td>
                <strong>SSL Server Certificate Comment</strong><br>
                SSL server certificate Comment attribute. You may change it to something
                or use the default value set by PHPki.
            </td>
            <td>
                <input type=text name=comment_srv value="{$comment_srv}" size=50>
            </td>
        </tr>

        <tr>
            <td>
                <strong>Time Stamping Certificate Comment</strong><br>
                Time stamping certificate Comment attribute. You may change it
                to something or use the default value set by PHPki.
            </td>
            <td>
                <input type=text name=comment_stamp value="{$comment_stamp}" size=50>
            </td>
        </tr>

    </table>

    <p>
    <table width="99%">
        <tr>
            <th colspan=2><h3>Configuration Options</h3></th>
        </tr>

        <tr>
            <td width="35%">
                <strong>Storage Directory <font color=red>*</font></strong><br>
                Enter the location where PHPki will store its files.  This should be a directory where
                the web server has full read/write access (chown {$uname} ; chmod 700), and is preferably
                outside of DOCUMENT_ROOT ({$DOCUMENT_ROOT}).  You may have to manually create the directory before completing this form.
            </td>
            <td>
                <input type=text name=store_dir value="{$store_dir}" size=35>
            </td>
        </tr>

        <tr>
            <td width="35%">
                <strong>Location of HTTP password file <font color=red>*</font></strong><br>
                Enter the location of your PHPki user password file.  The default is usually ok.
            </td>
            <td>
                <input type=text name=passwd_file value="{$passwd_file}" size=35>
            </td>
        </tr>

        <tr>
            <td>
                <strong>File Upload Prefix</strong><br>
                This is an optional prefix which will be added to root certificate
                and certificate revocation list file uploads.  Normally the root
                certificate is uploaded as caroot.crt.  With a prefix like
                <cite style="white-space: nowrap">"acme_"</cite>, the root certificate would be uploaded as
                <cite>"acme_caroot.crt"</cite>.
            </td>
            <td>
                <input type=text name=ca_prefix value="{$ca_prefix}" maxlength=10 size=10>
            </td>
        </tr>

        <tr>
            <td>
                <strong>Page Header Title</strong><br>
                This title will be displayed superimposed over the PHPki logo at the
                top of every page.
            </td>
            <td>
                <input type=text name=header_title value="{$header_title}" maxlength=40 size=40>
            </td>
        </tr>

        <tr>
            <td>
                <strong>Help Document Contact Info</strong><br>
                This text will be inserted into the online help document
                under the "Getting Additional Help" section.  Include full
                contact info for the convenience of your users.  Use HTML
                tags to improve presentation.
            </td>
            <td>
                <textarea name=getting_help cols=50 rows=15>{$getting_help}</textarea>
            </td>
        </tr>
    </table>
    <font color=red>* Required field</font>

    <p>
    <center><input type=submit name=submit value=Submit></center></td>
    <input type=hidden name=stage value='validate'>
    <input type=hidden name=openssl_bin value="{$openssl_bin}">
    <input type=hidden name=overwrite value='yes'>
</form>