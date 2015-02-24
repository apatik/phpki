<h4>You are about to <font color="red">REVOKE</font> the following certificate:</h4>
	<table width=500>
		<tr>
			<td width="25%" style='white-space: nowrap'>
				<p align="right">
					Serial Number<br>
					User's Name<br>
					Email Address<br>
					Organization<br>
					Department/Unit<br>
					Locality<br>
					State/Province<br>
					Country<br>
				</p>
			</td>
			<td>
				{$rec.serial|escape:'html'}<br>
				{$rec.common_name|escape:'html'}<br>
				{$rec.email|escape:'html'}<br>
				{$rec.organization|escape:'html'}<br>
				{$rec.unit|escape:'html'}<br>
				{$rec.locality|escape:'html'}<br>
				{$rec.province|escape:'html'}<br>
				{$rec.country|escape:'html'}<br>
				</td>
		</tr>
	</table>
	<h4>Are you sure?</h4>
	<p>
		<form action="{$PHP_SELF}?{$qstr_sort}&{$qstr_filter}" method="post">
			<input type="hidden" name="stage" value="revoke" >
			<input type="hidden" name="serial" value="{$serial|escape:'html'}" >
			<input type="submit" name="submit" value="Yes">&nbsp
			<input type="submit" name="submit" value="Cancel">
		</form>
	</p>