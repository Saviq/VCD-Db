<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VCD-db</title>
	<meta http-equiv="Content-Type" content="text/html; charset={$pageCharset}"/>
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="{$pageStyle}" media="screen, projection"/>
	<link rel="stylesheet" type="text/css" href="includes/css/global.css" media="screen, projection"/>
	<link rel="stylesheet" type="text/css" href="includes/css/manager.css" media="screen, projection"/>
	{$pageScripts}
</head>
<body onload="window.focus()" class="nobg">

<form method="post" name="addPornstars" action="{$smarty.server.SCRIPT_NAME}?page=addpornstars&amp;vcd_id={$smarty.get.vcd_id}">
<input type="hidden" value="id_list" value=""/>
&nbsp;<strong>{$translate.manager.addtodb}</strong>
<br/>
&nbsp;<input type="text" class="input" name="newstar"/>&nbsp;
<input type="submit" name="updatename" value="{$translate.manager.savetodb}" class="buttontext" title="{$translate.manager.savetodb}"/>&nbsp;
<input type="submit" name="updateboth" value="{$translate.manager.savetodbncd}" class="buttontext" title="{$translate.manager.savetodbncd}"/>
<br/>
<hr/>

<input type="hidden" id="id_list" name="id_list"/>
<table cellspacing="0" cellpadding="2" border="0" width="95%">
<tr>
	<td>
		&nbsp;<strong>{$translate.manager.indb}</strong>
		<input type="hidden" name="keys" id="keys" value=""/>
		{html_options id=available name=available options=$itemPornstarList size="15" style="width:180px;" class="input" ondblclick="moveOver(this.form, 'available', 'choiceBox');clr();" onKeyPress="selectkeypress();" onKeyDown="onselectKeyDown();" onblur="clr();" onfocus="clr();"}
	</td>
	<td>
		<input type="button" value="&gt;&gt;" onclick="moveOver(this.form, 'available', 'choiceBox');clr();" class="input" style="margin-bottom:5px;"/><br/>
		<input type="button" value="<<" onclick="removeMe(this.form, 'available', 'choiceBox');" class="input"/>
	</td>
	<td><strong>{$translate.manager.sel}</strong>
		{html_options id=choiceBox name=choiceBox size="8" options=null style="width:180px;" class="input" onDblClick="removeMe(this.form, 'available', 'choiceBox')"}
		<br/><br/>
		<input type="submit" onClick="checkFieldsRaw(this.form,'choiceBox','id_list')" value="{$translate.misc.saveandclose}" name="update" class="buttontext"/>
	</td>
</tr>
</table>

<br/>
</form>

</body>
</html>