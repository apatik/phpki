<body onLoad="self.focus();document.request.common_name.focus();document.request.cert_type.onchange()">
<form action="{$PHP_SELF}" method=post name=request>
    <table width='99%'>
        <th colspan=2><h3>Certificate Request Form</h3></th>

        <tr>
            <td width='30%'>Common Name<font color=red size=3>*</font><br>(i.e. User real name or computer hostname) </td>
            <td><input type=text name=common_name value="{$common_name}" size=50 maxlength=60></td>
        </tr>

        <tr>
            <td>E-mail Address <font color=red size=3>*</font></td>
            <td><input type=text name=email value="{$email}" size=50 maxlength=60></td>
        </tr>

        <tr>
            <td>Organization (Company/Agency)<font color=red size=3>*</font></td>
            <td><input type=text name=organization value="{$organization}" size=60 maxlength=60></td>
        </tr>

        <tr>
            <td>Department/Unit<font color=red size=3>*</font> </td><td><input type=text name=unit value="{$unit}" size=40 maxlength=60></td>
        </tr>

        <tr>
            <td>Locality (City/County)<font color=red size=3>*</font></td><td><input type=text name=locality value="{$locality}" size=30 maxlength=30></td>
        </tr>

        <tr>
            <td>State/Province<font color=red size=3>*</font></td><td><input type=text name=province value="{$province}" size=30 maxlength=30></td>
        </tr>

        <tr>
            <td>Country<font color=red size=3>*</font></td>
            <td><input type=text name=country value="{$country}" size=2 maxlength=2></td>
        </tr>

        <tr>
            <td>Certificate Password<font color=red size=3>*</font> </td>
            <td><input type=password name=passwd value="{$passwd}" size=30>&nbsp;&nbsp; Again <input type=password name=passwdv  value="{$passwdv}" size=30></td>
        </tr>

        <tr>
            <td>
                Certificate Life<font color=red size=3>*</font>
            </td>
            <td>
                <select name=expiry>
                    <option value=0.083 {if $expiry eq 0.083}selected='selected'{/if}>1 Month</option>
                    <option value=0.25 {if $expiry eq 0.25}selected='selected'{/if}>3 Months</option>
                    <option value=0.5 {if $expiry eq 0.5}selected='selected'{/if}>6 Months</option>
                    <option value=1 {if $expiry eq 1}selected='selected'{/if}>1 Year</option>
                    {for $i=5 to 10}
                    <option value={$i} {if $expiry eq $i}selected='selected'{/if}">{$i} years</option>
                    {/for}
                </select>
            </td>
        </tr>

        <tr>
            <td>Key Size<font color=red size=3>*</font> </td>
            <td>
                <select name=keysize>
                {for $i=512 to 4096 step 512}
                    <option value={$i} {if $keysize eq $i}selected='selected'{/if}">{$i} bits</option>
                {/for}
                </select>
            </td>
        </tr>

        <tr>
            <td>Certificate Use:<font color=red size=3>*</font> </td>
            <td><select name=cert_type onchange={literal}"if (this.value=='server'){setVisibility('testrow1',true); setVisibility('testrow2',true);} else {setVisibility('testrow1',false); setVisibility('testrow2',false);}"{/literal}>
                    <option value="email" {if $cert_type eq 'email'}selected='selected'{/if}>E-mail, SSL Client</option>
                    <option value="email_signing" {if $cert_type eq 'email_signing'}selected='selected'{/if}>E-mail, SSL Client, Code Signing</option>
                    <option value="server" {if $cert_type eq 'server'}selected='selected'{/if}>SSL Server</option>
                    <option value="vpn_client" {if $cert_type eq 'vpn_client'}selected='selected'{/if}).'>VPN Client Only</option>
                    <option value="vpn_server" {if $cert_type eq 'vpn_server'}selected='selected'{/if}>VPN Server Only</option>
                    <option value="vpn_client_server" {if $cert_type eq 'vpn_client_server'}selected='selected'{/if}>VPN Client, VPN Server</option>
                    <option value="time_stamping" {if $cert_type eq 'time_stamping'}selected='selected'{/if}>Time Stamping</option>
                </select></td>
        </tr>

        <tr id="testrow2" name="testrow2" style="visibility:hidden;display:none;">
            <td>Alternative DNS Names<br>(only one per Line)</td><td><textarea name="dns_names" cols=30 rows=5>{$dns_names}</textarea></td>
        </tr>

        <tr id="testrow1" name="testrow1" style="visibility:hidden;display:none;">
            <td>IP's<br>(only one per Line)</td><td><textarea name="ip_addr" cols=30 rows=5>{$ip_addr}</textarea></td>
        </tr>

        <tr>
            <td><center><input type=submit name=submit value='Submit Request'></center><input type=hidden name=form_stage value='validate'></td><td><font color=red size=3>* Fields are required</td>
        </tr>
    </table>
</form>