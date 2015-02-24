{if $error}
<center><h2><font color="red">Missing or invalid password or password and password verification do not match.</font></h2></center>


<p>
<center>
	<form action="{$PHP_SELF}" method="post">
		<input type="hidden" name="stage" value="add_user_form">
		<input type="hidden" name="login" value="{$login}">
		<input type="submit" name="submit" value="Back">
	</form>
</center>
{else}
Results of htpasswd command:<br>
{$htpasswdResults}
<p>
<form action="{$PHP_SELF}" method="post">
	<input type="submit" name="submit" value="Back to Menu">
</form>
{/if}