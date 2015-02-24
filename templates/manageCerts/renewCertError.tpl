<form action="{$PHP_SELF}?stage=renew-form&serial={$serial}&{$qstr_sort}&{$qstr_filter}" method="post">
	<font color="#ff0000">
		<h2>There was an error creating your certificate.</h2>
	</font>
	<br>
	<blockquote>
		<h3>Debug Info:</h3>
		<pre>
			{$errorText}
		</pre>
	</blockquote>
	<p>
		<input type="submit" name="submit" value="Back">
	<p>
</form>