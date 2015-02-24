{if $form}
<body onLoad="self.focus();document.form.login.focus()">
<form action="{$PHP_SELF}" method="post" name="form">
	<table>
		<th colspan=2><h3>Remove User</h3></th>
		<tr><td>User ID</td><td><input type=text name=login value="{$login|escape:'html'}" maxlength=15 size=15></td></tr>
	</table>
	<input type="hidden" name="stage" value="del_user">
	<input type="submit" name="submit" value='Submit'>
</form>
{else}
Results of htpasswd command:<br>
{$htpasswdResults}
<p>
<form action="{$PHP_SELF}" method="post">
	<input type="submit" name="submit" value="Back to Menu">
</form>
{/if}