<body onLoad="self.focus();document.form.passwd.focus()">

<form action="{$PHP_SELF}?{$qstr_sort}&{$qstr_filter}" method="post" name="form">
	<table width="99%">
		<th colspan=2><h3>Certificate Renewal Form</h3></th>

		<tr>
			<td width="25%">Common Name </td>
			<td><input type="text" name="common_name" value="{$rec.common_name}" size=50 maxlength=60 disabled></td>
		</tr>

		<tr>
			<td>E-mail Address </td>
			<td><input type="text" name="email" value="{$rec.email}" size=50 maxlength=60 disabled></td>
		</tr>

		<tr>
			<td>Organization </td>
			<td><input type="text" name="organization" value="{$rec.organization}" size=60 maxlength=60 disabled></td>
		</tr>

		<tr>
			<td>Department/Unit </td>
			<td><input type="text" name="unit" value="{$rec.unit}" size=40 maxlength=60 disabled></td>
		</tr>

		<tr>
			<td>Locality</td>
			<td><input type="text" name="locality" value="{$rec.locality}" size=30 maxlength=30 disabled></td>
		</tr>

		<tr>
			<td>State/Province</td>
			<td><input type="text" name="province" value="{$rec.province}" size=30 maxlength=30 disabled></td>
		</tr>

		<tr>
			<td>Country</td>
			<td><input type="text" name="country" value="{$rec.country}" size=2 maxlength=2 disabled></td>
		</tr>

		<tr>
			<td>Certificate Password </td>
			<td><input type="password" name="passwd" value="" size=30></td>
		</tr>

		<tr>
			<td>Certificate Life </td>
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
			<td>
				<center>
					<input type=submit name=submit value="Submit Request">&nbsp
					<input type=submit name=submit value="Back">
				</center>
			</td>
			<td>
				<input type="hidden" name="stage" value="renew">
				<input type="hidden" name="serial" value="{$serial}">
			</td>
		</tr>
	</table>
</form>