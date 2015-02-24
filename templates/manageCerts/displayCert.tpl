<center><h2>Certificate Details</h2></center>
<center><font color="#0000AA"><h3>(#{$serial})<br>{$certCommonName} <{$certEmail}></h3></font></center>
{if $certRevokeDate}
<center><font color="red"><h2>REVOKED {$certRevokeDate}</h2></font></center>
{/if}
<pre>
	{$certText}
</pre>
