<h3>You are about to download the <font color="red">PRIVATE</font> certificate key for {$rec.common_name} &lt;{$rec.email}&gt;</h3>
<h3><font color="red">DO NOT DISTRIBUTE THIS FILE TO THE PUBLIC!</font></h3>
<form action="{$PHP_SELF}?stage=download&serial={$serial}&{$qstr_sort}&{$qstr_filter}" method="post">
	<strong>File type: </strong>
		<select name="dl_type">
			<option value="PKCS#12">PKCS#12 Bundle</option>
			<option value="PEMCERT">PEM Certificate</option>
			<option value="PEMKEY">PEM Key</option>
            <option value="PEMBUNDLE">PEM Bundle</option>
            <option value="PKCS#1">RSA PEM Key</option>
			<option value="PEMCABUNDLE">PEM Bundle w/Root</option>
		</select>
		<input type="submit" name="submit" value="Download">
		&nbsp; or &nbsp;
		<input type="submit" name="submit" value="Go Back">
</form>