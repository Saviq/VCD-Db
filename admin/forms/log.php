<h1>VCD-db log options</h1>


<form name="log" method="post">

<table cellpadding="0" cellspacing="1" border="0" class="datatable" width="100%">
<tr>
	<td class="header">Event type:</td>
	<td class="header">&nbsp;</td>
</tr>
<tr onMouseOver="trOn(this)" onMouseOut="trOff(this)">
	<td>Logins</td>
	<td align="right"><input type="checkbox" name="logoptions[]" value="<?=VCDLog::EVENT_LOGIN ?>" <?if (VCDLog::isInLogList(VCDLog::EVENT_LOGIN )) print "checked=\"checked\"" ?>></td>
</tr>
<tr onMouseOver="trOn(this)" onMouseOut="trOff(this)">
	<td>Errors</td>
	<td align="right"><input type="checkbox" name="logoptions[]" value="<?=VCDLog::EVENT_ERROR ?>" <?if (VCDLog::isInLogList(VCDLog::EVENT_ERROR )) print "checked=\"checked\"" ?>></td>
</tr>
<tr onMouseOver="trOn(this)" onMouseOut="trOff(this)">
	<td>Webservice calls</td>
	<td align="right"><input type="checkbox" name="logoptions[]" value="<?=VCDLog::EVENT_SOAPCALL ?>" <?if (VCDLog::isInLogList(VCDLog::EVENT_SOAPCALL )) print "checked=\"checked\"" ?>></td>
</tr>
<tr onMouseOver="trOn(this)" onMouseOut="trOff(this)">
	<td>XML Rss calls</td>
	<td align="right"><input type="checkbox" name="logoptions[]" value="<?=VCDLog::EVENT_RSSCALL ?>" <?if (VCDLog::isInLogList(VCDLog::EVENT_RSSCALL )) print "checked=\"checked\"" ?>></td>
</tr>

</table>

<p align="right">
<input type="submit" value="Update" name="update" class="button">
</p>
</form>