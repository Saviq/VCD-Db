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
<h2><?=VCDLanguage::translate('rss.rss')?></h2>

<ul>

	<?
		if (isset($_POST['feedurl'])) {
		$url = $_POST['feedurl'];
		if ($url != 'http://' && $url != "") {

			showAvailableFeeds($url);

		} else {
			print "<script>history.back(-1);</script>";
		}
	} else {
	?>
	<form name="rss" action="addRssFeed.php?action=fetch" method="POST">
	<?=VCDLanguage::translate('rss.note')?>
	<p>

	<input type="text" size="30" name="feedurl" value="http://" class="input"/>&nbsp;
	<input type="submit" value="<?=VCDLanguage::translate('rss.fetch')?>" class="inp"/>

	</p>
	</form>
	<? } ?>

</ul>




</body>
</html>