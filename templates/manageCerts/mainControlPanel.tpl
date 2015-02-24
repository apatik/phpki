<body onLoad="self.focus();document.filter.search.focus()">
<table>
	<tr><th colspan=8><big>CERTIFICATE MANAGEMENT CONTROL PANEL</big></th></tr>
	<tr>
		<td colspan=8><center>
				<form action="{$PHP_SELF}?{$qstr_sort}" method="get" name="filter">
					Search: <input type="text" name="search" value="{$search|escape:'html'}" style="font-size: 11px;" maxlength=60 size=30>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="show_valid" value="V" {if $show_valid}checked{/if}>Valid
					&nbsp;&nbsp;<input type="checkbox" name="show_revoked" value="R" {if $show_revoked}checked{/if}>Revoked
					&nbsp;&nbsp;<input type="checkbox" name="show_expired" value="E" {if $show_expired}checked{/if}>Expired
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Apply Filter" style="font-size: 11px;">
				</form>
			</center>
		</td>
	</tr>
	<tr>
		{foreach from=$headings key=field item=head}
			<th><a href="{$PHP_SELF}?sortfield={$field}&ascdec=A&{$qstr_filter}" title="Click to sort on this column."><u>{$head}</u></a>
				{if $sortfield eq $field}
				&nbsp;<a href="{$PHP_SELF}?sortfield={$field}&ascdec={$ht_ascdec}&{$qstr_filter}" ><img src="{$arrow_gif}" height=12 alt='Change sort order.' title='Click to reverse sort order.'></a>
				{/if}
			</th>
		{/foreach}
	</tr>

	{foreach from=$db item=rec}
		<tr style="font-size: 11px;">
			<td><font color='{$stcolor.{$rec.status}}'><b>{$rec.status|escape:'html'}</b></font></td>
			<td style="white-space: nowrap">{$rec.issued|date_format}</td>
			<td style="white-space: nowrap">{$rec.expires|date_format}</td>
			<td>{$rec.common_name}</td>
			<td style="white-space: nowrap"><a href="mailto:{$rec.common_name|escape:'html'} <{$rec.email|escape:'html'}>">{$rec.email|escape:'html'}</a></td>
			<td>{$rec.organization|escape:'html'}</td>
			<td>{$rec.unit|escape:'html'}</td>
			<td>{$rec.locality|escape:'html'}</td>
			<td>
				<a href="{$PHP_SELF}?stage=display&serial={$rec.serial|escape:'html'}" target="_certdisp"><img src="../images/display.png" alt="Display" title="Display complete certificate details."></a>

				{if ($rec.status eq 'Valid')}
					<a href="{$PHP_SELF}?stage=dl-confirm&serial={$rec.serial|escape:'html'}&{$qstr_sort}&{$qstr_filter}"><img src="../images/download.png" alt="Download" title="Download the PRIVATE certificate. DO NOT DISTRIBUTE THIS TO THE PUBLIC!"></a>
					<a href="{$PHP_SELF}?stage=revoke-form&serial={$rec.serial|escape:'html'}&{$qstr_sort}&{$qstr_filter}"><img src="../images/revoke.png" alt="Revoke" title="Revoke the certificate when the e-mail address is no longer valid or the certificate password or private key has been compromised."></a>
				{/if}
				<a href="{$PHP_SELF}?stage=renew-form&serial={$rec.serial|escape:'html'}&{$qstr_sort}&{$qstr_filter}"><img src="../images/renew.png" alt="Renew" title="Renew the certificate by revoking it, if necessary, and creating a replacement with a new expiration date."></a>
			</td>
		</tr>
	{/foreach}
</table>