<form action="{$PHP_SELF}?stage=revoke-form&serial={$serial}&{$qstr_sort}&{$qstr_filter}" method="post">
	<font color="#ff0000">
		<h2>There was an error revoking your certificate.</h2>
	</font>
	<br>
	<blockquote>
		<h3>Debug Info:</h3>
		<pre>
			{$errtxt}
		</pre>
	</blockquote>
	<p>
		<input type="submit" name="submit" value="Back">
	<p>
</form>