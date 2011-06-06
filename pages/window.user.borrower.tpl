<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VCD-db</title>
	<meta http-equiv="Content-Type" content="text/html; charset={$pageCharset}"/>
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="{$pageStyle}" media="screen, projection"/>
	<link rel="stylesheet" type="text/css" href="includes/css/global.css" media="screen, projection"/>
	{$pageScripts}
</head>
<body onload="window.focus()" class="nobg">
<h2>{$translate.loan.registeruser}</h2>
<form name="borrower" action="{$smarty.server.SCRIPT_NAME}?page=borrower&amp;action=add" method="post">

<table cellspacing="1" cellpadding="1" border="0" class="plain">
<tr>
	<td>{$translate.loan.name}:</td>
	<td><input type="text" name="borrower_name"/></td>
</tr>
<tr>
	<td>{$translate.register.email}:</td>
	<td><input type="text" name="borrower_email"/></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input type="submit" value="{$translate.misc.confirm}" id="saveBorrower" name="saveBorrower" onclick="return checkBorrower(this.form)"/></td>
</tr>
</table>

</form>
</body>
</html>