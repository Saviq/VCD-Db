<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VCD-db</title>
	<meta http-equiv="Content-Type" content="text/html; charset={$pageCharset}"/>
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="{$pageStyle}" media="screen, projection"/>
	<link rel="stylesheet" type="text/css" href="includes/css/global.css" media="screen, projection"/>
	<script src="includes/js/main.js" type="text/javascript"></script>
</head>
<body onload="window.focus()">

{if !$smarty.get.field eq '' }
	{assign var='jsAction' value='return getFileName(this.form, '{$fieldname}')'}
{elseif $smarty.get.from eq 'player'}
	{assign var='jsAction' value='return getPlayerFileName(this.form)'}
{else}
	{assign var='jsAction' value='return getFileName(this.form)'}
{/if}

<h2>{$translate.manager.browse}</h2>
<form name="browse" action="{$smarty.server.SCRIPT_NAME}" method="post" onsubmit="return false">
<table cellspacing="1" cellpadding="1" border="0" class="plain">
<tr>
	<td>{$translate.player.path}:</td>
	<td><input size="40" type="file" name="filename"/></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td align="right"><input type="button" value="{$translate.misc.save}" onclick="{$jsAction}"/></td>
</tr>
</table>
</form>
</body>
</html>
