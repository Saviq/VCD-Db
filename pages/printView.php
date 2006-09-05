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
	VCDClassFactory::put($language, true);

?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>VCD-db</title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?= VCDUtils::getCharSet()?>"/>
	<link rel="stylesheet" type="text/css" href="../<?=STYLE?>style.css"/>
	<script src="../includes/js/main.js" type="text/javascript"></script>
</head>
<body class="nobg" onload="window.focus()">

<?
	
	// Get all the users movies based on the selection
	$MOVIEClass = new vcd_movie();
	$arr = $MOVIEClass->getPrintViewList(VCDUtils::getUserID(), $_GET['mode']);
	
	
	if (strcmp($_GET['mode'], "text") == 0) {
		print "<table cellspacing=\"1\" cellpadding=\"1\" border=\"0\" width=\"100%\" class=\"displist\">";
		print "<tr><td class=\"bold\" width=\"50%\">".language::translate('M_TITLE')."</td><td class=\"bold\">".language::translate('M_CATEGORY')."</td><td class=\"bold\">".language::translate('M_YEAR')."</td><td class=\"bold\">".language::translate('M_MEDIA')."</td><td class=\"bold\">".language::translate('M_DATE')."</td></tr>";
		foreach ($arr as $vcdObj) {
			print "<tr><td>{$vcdObj->getTitle()}</td><td nowrap=\"nowrap\">".$vcdObj->getCategory()->getName(true)."</td><td>{$vcdObj->getYear()}</td><td nowrap=\"nowrap\">{$vcdObj->showMediaTypes()}</td><td nowrap=\"nowrap\">{$vcdObj->getDateAdded()}</td></tr>";
		}
		print "</table>";
	}
	
	else if (is_array($arr)) {

		
		$i = 0;
		$cols = 6;
		$size = sizeof($arr);
		$w = (int)(100/$cols);
		
		print "<table width=\"100%\" cellpadding=\"1\" cellspacing=\"1\" border=\"0\">";
		foreach ($arr as $vcdObj) {
			if (!($i % $cols)) { print "<tr>";	}
			
			$title = $vcdObj->getTitle();
			if (strlen($vcdObj->getTitle()) > 12) {
				$title = substr($vcdObj->getTitle(), 0, 12) . "..";
			}
			
			$coverObj = $vcdObj->getCover('thumbnail');
				if ($coverObj instanceof cdcoverObj ) {
					print "<td valign=\"top\" width=\"".$w."%\" align=\"center\"><span class=\"ptil\">".$title."</span><br/>".$coverObj->getIMGSRC('../', 140, 100)."</td>";									
				} else {
					print "<td align=\"center\">".$vcdObj->getTitle()."<br/></td>";	
				}
			
			$i++;
			if (!($i % $cols) || $i === $size) { print "</tr>";	}
			
			
		
		}
			
			
		print "</table>";
		unset($arr);
				
	}
	
?>

</body></html>
