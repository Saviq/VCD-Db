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



	$user = $_SESSION['user'];
	$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");

	$player = "";
	$path = "";
	$playerObj = $SETTINGSClass->getMetadata(0, $user->getUserID(), 'player');
	$pathObj = $SETTINGSClass->getMetadata(0, $user->getUserID(), 'playerpath');
	if (is_array($playerObj) && sizeof($playerObj) == 1 && $playerObj[0] instanceof metadataObj ) {
		$player = $playerObj[0]->getMetaDataValue();
	}
	if (is_array($pathObj) && sizeof($pathObj) == 1 && $pathObj[0] instanceof metadataObj ) {
		$path = $pathObj[0]->getMetaDataValue();
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/2000/REC-xhtml1-20000126/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?=$language->show('PLAYER')?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?=VCDUtils::getCharSet()?>"/>
	<link rel="stylesheet" type="text/css" href="../<?=STYLE?>style.css"/>
	<script language="JavaScript" src="../includes/js/main.js" type="text/javascript"></script>

</head>
<body onload="window.focus()">
<h2><?=$language->show('PLAYER')?></h2>

<ul>

	<form name="player" action="../exec_form.php?action=player" method="POST">
	<?=$language->show('PLAYER_NOTE')?>
	<br/><br/>
	<table cellpadding="1" cellspacing="1">
	<tr>
		<td><?=$language->show('PLAYER_PATH')?>:</td>
		<td><input type="text" size="45" name="player" value="<?=$player?>" class="input" style="margin-bottom:4px"/>
		<img src="../images/icon_folder.gif" border="0" align="absmiddle" title="Browse for file" onclick="filebrowse('player')"/></td>
	</tr>
	<tr>
		<td><?=$language->show('PLAYER_PARAM')?>:</td>
		<td><input type="text" size="50" name="params" value="<?=$path?>" class="input" style="margin-bottom:4px"/></td>
	</tr>
	<tr>
		<td colspan="2" align="right">
		<input type="submit" value="<?=$language->show('X_UPDATE')?>" class="inp"/>
		<input type="button" value="<?=$language->show('X_CLOSE')?>" class="inp" onclick="window.close()"/>
		</td>
	</tr>

	</table>

	</form>


</ul>




</body>
</html>