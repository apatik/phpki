<body onLoad="self.focus();document.form.login.focus()">
<form action="{$PHP_SELF}" method="post" name="form">
	<table>
		<th colspan=2><h3>Add User or Change Password</h3></th>
		<tr><td>User ID</td><td><input type="text" name="login" value="{$login|escape:'html'}" maxlength=15 size=15></td></tr>
		<tr><td>Password </td><td><input type="password" name="passwd" value=''  size=20></td></tr>
		<tr><td>Verify Password </td><td><input type="password" name="passwdv" value='' size=20></td></tr>
	</table>
	<input type="hidden" name="stage" value="add_user">
	<input type="submit" name="submit" value='Submit'>
</form>