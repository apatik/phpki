{if $returnValue}
<center><h2>Certificate Revocation List Updated</h2></center>
<p>
<form action="{$PHP_SELF}" method="post">
	<input type="submit" name="submit" value="Back to Menu">
</form>
<pre>
	{$CA_crl_text}
</pre>
{else}
<font color="#ff0000">
	<h2>There was an error updating the Certificate Revocation List.</h2></font><br>
<blockquote>
	<h3>Debug Info:</h3>
	<pre>
		{$errorText}
	</pre>
</blockquote>
<form action="{$PHP_SELF}" method="post">
	<p>
		<input type="submit" name="submit" value="Back to Menu">
	<p>
</form>
{/if}