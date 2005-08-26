<? 
	require_once("../classes/includes.php");
	if (!VCDUtils::isLoggedIn()) {
		VCDException::display("User must be logged in");
		print "<script>self.close();</script>";
		exit();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<link rel="stylesheet" type="text/css" href="../<?=STYLE?>style.css"/>
	<script src="../includes/js/main.js" type="text/javascript"></script>
</head>
<body class="nobg">


<?
	global $ClassFactory;
	$CLASSVcd = $ClassFactory->getInstance('vcd_movie');
	$language = new language(true);
	if (isset($_SESSION['vcdlang'])) {
		$language->load($_SESSION['vcdlang']);
	}
	


	if (VCDUtils::isLoggedIn() && isset($_GET['do']) && strcmp($_GET['do'], "suggest") == 0) {
			
		$category = 0;
		$seenfilter = 0;
		if (isset($_GET['cat']) && is_numeric($_GET['cat'])) {
			$category = $_GET['cat'];
		}	
		if (isset($_GET['seen']) && $_GET['seen'] == 1) {
			$seenfilter = 1;
		}
		
		$movie = $CLASSVcd->getRandomMovie($category, $seenfilter);
		if ($movie instanceof vcdObj ) {
			?>
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td valign="top" width="10%">
				<? 
				$coverObj = $movie->getCover("thumbnail");
				if (!is_null($coverObj)) {
					$coverObj->showImage('../');
				} else {
					print "<div class=\"poster\">No poster available</div>";
				}
				?>
				</td>
				<td valign="top" nowrap="nowrap">
				
				<table cellpadding="1" cellspacing="1" border="0" width="100%">
				<tr>
					<td colspan="2"><h1><?=$movie->getTitle()?></h1></td>
				</tr>
				<tr>
					<td width="20%" class="bold" nowrap="nowrap">&nbsp;<?= $language->show('M_CATEGORY')?>:</td>
					<td><? 
					$mObj = $movie->getCategory();
					if (!is_null($mObj)) {
						print "<a href=\"../?page=category&category_id=".$mObj->getID()."\" target=\"_top\">".$mObj->getName()."</a>";
					} 
					?></td>
				</tr>
				<tr>
					<td class="bold" nowrap="nowrap">&nbsp;<?= $language->show('M_YEAR')?>:</td>
					<td><?= $movie->getYear() ?></td>
				</tr>
				<tr>
					<td colspan="2" class="bold"><br/>&nbsp;<a href="../?page=cd&amp;vcd_id=<?=$movie->getID()?>" target="_top"><?=$language->show('X_SHOWMORE')?> &gt;&gt;</a></td>
				</tr>
				</table>
				
				</td>
			</tr>
			</table>
			
			
			<?
			
		} else {
			print "No movie matched the criteria";
		}
		
			
			
	
	}

?>

</body>
</html>