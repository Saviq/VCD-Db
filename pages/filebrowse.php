<? 
	require_once("../classes/includes.php");
	if (!VCDUtils::isLoggedIn()) {
		VCDException::display("User must be logged in");
		print "<script>self.close();</script>";
		exit();
	}
	$language = new language(true);
	if (isset($_SESSION['vcdlang'])) {
		$language->load($_SESSION['vcdlang']);
	}
	global $ClassFactory;
	
	$jsaction = "return getFileName(this.form)";
	if (isset($_GET['from']) && strcmp($_GET['from'], "player") == 0) {
		$jsaction = "return getPlayerFileName(this.form)";
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head><title>VCD-db</title>
<link rel="stylesheet" type="text/css" href="../<?=STYLE?>style.css"/>
<script src="../includes/js/main.js" type="text/javascript"></script>
</head>
<body onload="window.focus()">
<h2><?=$language->show('MAN_BROWSE')?></h2>
<form name="browse" action="" method="POST" onsubmit="return false">
<table cellspacing="1" cellpadding="1" border="0" class="plain">
<tr>
	<td><?=$language->show('PLAYER_PATH')?>:</td>
	<td><input size="40" type="file" name="filename"/></td>
</tr>
<tr>
	<td></td>
	<td align="right"><input type="submit" value="<?=$language->show('X_SAVE')?>" onclick="<?=$jsaction?>"/></td>
</tr>
</table>
</form>
</body>
</html>