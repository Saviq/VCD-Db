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

<h2>{$translate.player.player}</h2>
<div id="play" style="padding-left:4px">
<form name="player" action="{$smarty.server.SCRIPT_NAME}?page=player&amp;action=update" method="post">
	{$translate.player.note}
	<br/><br/>
	<table cellpadding="1" cellspacing="1" border="0">
	<tr>
		<td>{$translate.player.path}:</td>
		<td><input type="text" size="45" name="player" value="{$playerPath}" class="input" style="margin-bottom:4px"/>
		<img src="images/icon_folder.gif" border="0" title="Browse for file" onclick="filebrowse('player');return false"/></td>
	</tr>
	<tr>
		<td>{$translate.player.param}:</td>
		<td><input type="text" size="50" name="params" value="{$playerParams}" class="input" style="margin-bottom:4px"/></td>
	</tr>
	<tr>
		<td colspan="2" align="right">
		<input type="submit" value="{$translate.misc.update}" class="inp"/>
		<input type="button" value="{$translate.misc.close}" class="inp" onclick="window.close()"/>
		</td>
	</tr>
	</table>
</form>
</div>
</body>
</html>