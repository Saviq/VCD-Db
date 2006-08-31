<?
	require_once("../classes/includes.php");
	if (!VCDUtils::isLoggedIn()) {
		VCDException::display("User must be logged in");
		print "<script>self.close();</script>";
		exit();
	}
	
	
	$user = $_SESSION['user'];
	$VCDClass = VCDClassFactory::getInstance("vcd_movie");
	$PORNClass = VCDClassFactory::getInstance("vcd_pornstar");
	$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");
	
	$language = new language(true);
	if (isset($_SESSION['vcdlang'])) {
		$language->load($_SESSION['vcdlang']);
	}
	VCDClassFactory::put($language, true);
	
	
	$pornstar_id = $_GET['pornstar_id'];
	$pornstar = $PORNClass->getPornStarByID($pornstar_id);
	
	if (isset($_GET['error'])) {
		VCDException::display('Error d/l image<break>Probably bad url');
	}
	
		

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/2000/REC-xhtml1-20000126/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<title>Manager | <?=$pornstar->getName()?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?=VCDUtils::getCharSet()?>"/>
	<link rel="stylesheet" type="text/css" href="../<?=STYLE?>style.css"/>
	<style type="text/css" media="screen">
		@import url(../includes/css/manager.css);
	</style>
	<script language="JavaScript" src="../includes/js/main.js" type="text/javascript"></script>
</head>

<body onload="window.focus()">
<form name="pornstar" action="../exec_form.php?action=updatepornstar" method="post" enctype="multipart/form-data">
<input type="hidden" name="star_id" value="<?=$pornstar->getId()?>"/>
<table cellspacing="1" cellpadding="1" border="0" width="100%">
<tr>
	<td class="tblb"><?=language::translate('P_NAME')?>:</td>
	<td><input type="text" name="name" class="input" size="25" value="<?=$pornstar->getName() ?>"/></td>
	<td rowspan="4" valign="top">
	<? if (strlen($pornstar->getImageName()) > 3) {
		$pornstar->showImage('../');
	} 
	?>
	
	</td></td>
</tr>
<tr>
	<td class="tblb"><?=language::translate('P_WEB')?>:</td>
	<td><input type="text" name="www" class="input" size="25" value="<?=$pornstar->getHomepage()?>"/></td>
</tr>
<tr>
	<td class="tblb">Image:</td>
	<td>
	<? if (strlen($pornstar->getImageName()) < 3)	{ ?>
	
	<input type="file" name="userfile" value="userfile" size="10" class="input"/>
	<input type="button" value="Fetch" class="input" onclick="fetchstarimage(<?=$pornstar_id?>)" title="Click here for paste-ing direct image url on the web to fetch.  Then the image will be automatically downloaded."/>
	<strong>Resize</strong> <input type="checkbox" name="resize" value="true" title="Check to automatically resize image" checked>
			
   <? } else { ?>
	<input type="text" name="image" size="25" class="input" value="<?=$pornstar->getImageName()?>"/>
	<? } ?>
	
	</td>
</tr>
<tr>
	<td colspan="2">
	<? 
		$bio = ereg_replace("<br>","\n",$pornstar->getBiography());
	?>
	<strong>Biography</strong><br/>
	<textarea cols="24" rows="9" name="bio" class="input"><?=$bio?></textarea></td>
</tr>
<tr>
	<td colspan="3" align="center">
	<hr/>
	<input type="submit" name="update" value="<?= language::translate('X_UPDATE')?>" class="buttontext">
	&nbsp;
	<input type="button" name="close" onclick="window.close()" value="<?=language::translate('X_CLOSE')?>" class="buttontext">
	&nbsp;
	<input type="submit" name="save" value="<?=language::translate('X_SAVEANDCLOSE')?>" class="buttontext">
	<? if (strlen($pornstar->getImageName()) > 3)	{ ?>
	&nbsp;
	<input type="button" name="delimage" value="Delete image" class="buttontext" onClick="delpornstarImage(<?=$pornstar_id?>)">
	<? } ?>
	</td>
</tr>
</table>
</form>


</body>
</html>
