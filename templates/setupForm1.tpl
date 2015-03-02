<form action={$PHP_SELF} method=post>
    <center><h2>Certificate Authority Initial Setup</h2></center>

    <table width="99%">
        <tr>
            <th colspan=2><h3>Configuration Options</h3></th>
        </tr>


        <tr>
            <td width="35%">
                <strong>Location of OpenSSL Executable <font color=red>*</font></strong><br>
                Enter the location of your OpenSSL binary.  The default is usually ok.
            </td>
            <td>
                <input type=text name=openssl_bin value="{$openssl_bin}" size=35>
            </td>
        </tr>
    </table>
    <font color=red>* Required field</font>

    <p>
    <center><input type=submit name=submit value=Submit></center>
    <input type=hidden name=stage value='setup_stage2'>
    <input type=hidden name=overwrite value='yes'>
</form>