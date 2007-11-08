<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VCD-db</title>
	<meta http-equiv="Content-Type" content="text/html; charset={$pageCharset}"/>
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="{$pageStyle}" media="screen, projection"/>
	<link rel="stylesheet" type="text/css" href="includes/css/global.css" media="screen, projection"/>
	<link rel="stylesheet" type="text/css" href="includes/css/manager.css" media="screen, projection"/>
	<script src="includes/js/main.js" type="text/javascript"></script>
</head>

<body onload="window.focus()">
<form name="pornstar" action="{$smarty.server.SCRIPT_NAME}?page=pornstarmanager&amp;pornstar_id={$smarty.get.pornstar_id}&amp;action=update" method="post" enctype="multipart/form-data">

<table cellspacing="1" cellpadding="1" border="0" width="100%">
<tr>
	<td class="tblb">{$translate.pornstar.name}:</td>
	<td><input type="text" name="name" class="input" size="25" value="{$pornstarName}"/></td>
	<td rowspan="4" valign="top">{$pornstarImage}</td>
</tr>
<tr>
	<td class="tblb">{$translate.pornstar.web}:</td>
	<td><input type="text" name="www" class="input" size="25" value="{$pornstarHomepage}"/></td>
</tr>
<tr>
	<td class="tblb">Image:</td>
	<td>
	{if !$pornstarImage}
	
	<input type="file" name="userfile" value="userfile" size="10" class="input"/>
	<input type="button" value="Fetch" class="input" onclick="fetchstarimage({$pornstarId});return false;" title="Click here for paste-ing direct image url on the web to fetch.  Then the image will be automatically downloaded."/>
	<strong>Resize</strong> <input type="checkbox" name="resize" value="true" title="Check to automatically resize image" checked="checked"/>
			
	{else}
	<input type="text" name="image" size="25" class="input" value="{$pornstarImageName}"/>
	{/if}
	
	</td>
</tr>
<tr>
	<td colspan="2">
	<strong>Biography</strong><br/>
	<textarea cols="24" rows="9" name="bio" class="input">{$pornstarBiography}</textarea>
	</td>
</tr>
<tr>
	<td colspan="3" align="center">
	<hr/>
	<input type="submit" name="update" value="{$translate.misc.update}" class="buttontext"/>
	&nbsp;
	<input type="button" name="close" onclick="window.close()" value="{$translate.misc.close}" class="buttontext"/>
	&nbsp;
	<input type="submit" name="save" value="{$translate.misc.saveandclose}" class="buttontext"/>
	{if $pornstarImage}
	&nbsp;
	<input type="button" name="delimage" value="Delete image" class="buttontext" onclick="deletePornstarImage({$pornstarId});return false;"/>
	{/if}
	</td>
</tr>
</table>
</form>


</body>
</html>