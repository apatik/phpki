<br>
<br>
<center>
	<table class=menu width=600><th class=menu colspan=2><big>CERTIFICATE MANAGEMENT MENU</big></th>

		<tr><td style="text-align: center; vertical-align: middle; font-weight: bold;" width="33%">
				<a href="ca/request_cert.php">Create a New Certificate</a></td>
			<td>Use the <strong><cite>Certificate Request Form</cite></strong> to create and download new digital certificates.
				You may create certificates in succession without re-entering the entire form
				by clicking the "<strong>Go Back</strong>" button after each certificate is created.</td></tr>

		<tr><td style="text-align: center; vertical-align: middle; font-weight: bold;">
				<a href="ca/manage_certs.php">Manage Certificates</a></td>
			<td>Conveniently view, download, revoke, and renew your existing certificates using the
				<strong><cite>Certificate Management Control Panel</cite></strong>.</td></tr>

		<tr><td style="text-align: center; vertical-align: middle; font-weight: bold;">
				<a href="{$PHP_SELF}?stage=gen_crl">Update & View the Certificate Revocation List</a></td>
			<td>Some applications automagically reference the Certificate Revocation List to determine
				certificate validity.  It is not necessary to perform this update function, as the CRL is
				updated when certificates are revoked.  However, doing so is harmless.
				<a href="./help.php" target="_help">Read the online help</a> to learn more about this.</td></tr>

		<tr><td style="text-align: center; vertical-align: middle; font-weight: bold;">
				<a href="{$PHP_SELF}?stage=dl_root">Download the Root Certificate</a></td>
			<td>The "Root" certificate must be installed before using any of the
				certificates issued here. <a href="./help.php" target="_help">Read the online help</a>
				to learn more about this.</td></tr>

		<tr><td style="text-align: center; vertical-align: middle; font-weight: bold;">
				<a href="crl.php">Download the Certificate Revocation List</a></td>
			<td>This is the official list of revoked certificates.  Using this list with your e-mail or
				browser application is optional.  Some applications will automagically reference this list. </td></tr>
	</table>
</center>
<br><br>
