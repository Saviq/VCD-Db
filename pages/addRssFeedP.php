<?
	require_once("../classes/includes.php");
	if (!VCDUtils::isLoggedIn()) {
		VCDException::display("User must be logged in");
		print "<script>self.close();</script>";
		exit();
	}

	$language = new VCDLanguage();
	if (isset($_SESSION['vcdlang'])) {
		$language->load($_SESSION['vcdlang']);
	}
		
	VCDClassFactory::put($language, true);
	

	$user = $_SESSION['user'];
	$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/2000/REC-xhtml1-20000126/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?=VCDLanguage::translate('rss.add')?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?=VCDUtils::getCharSet()?>"/>
	<link rel="stylesheet" type="text/css" href="../<?=STYLE?>style.css"/>
	<script language="JavaScript" src="../includes/js/main.js" type="text/javascript"></script>

</head>
<body onload="window.focus()">
<h2><?=VCDLanguage::translate('rss.add')?></h2>

<ul>
	<form method="post" action="../exec_form.php?action=addprivatefeed">
	<table>
		<tr>
			<td><?=VCDLanguage::translate('metadata.name')?>:</td>
			<td><input type="text" size="40" name="rssname"/></td>
		</tr>
		<tr>
			<td>Url:</td>
			<td><input type="text" size="40" name="rssurl"/></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" value="<?=VCDLanguage::translate('menu.submit')?>"/></td>
		</tr>
	</table>
	</form>	
</ul>

</body>
</html>