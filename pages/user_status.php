<?
	include_once("../classes/includes.php");
	if (!VCDUtils::isLoggedIn()) {
		die('Login first to use this page');
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>VCD Gallery</title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?=VCDUtils::getCharSet()?>"/>
	<link rel="stylesheet" type="text/css" href="../<?=STYLE?>style.css"/>
	<script src="../includes/js/main.js" type="text/javascript"></script>
</head>
<body class="nobg">



<?


		$s = new vcd_settings();
		$userCategories = $s->getCategoriesInUseByUserID(VCDUtils::getUserID());
		if (sizeof($userCategories) == 0) {
			VCDException::display('You have not added any movies yet<break>Try again after you have inserted some movies');
			print "<script>window.close()</script>";
			exit();
		}

		$arr = $s->getMediaTypesInUseByUserID(VCDUtils::getUserID());

		$arrMediaTypes = array();

		print "<table cellspacing=\"1\" cellpadding=\"1\" border=\"0\" width=\"100%\">";
		print "<tr><td>&nbsp;</td>";


		foreach ($arr as $item) {
			print "<td nowrap=\"noswrap\" class=\"stata\">".$item[1] . "</td>";
			array_push($arrMediaTypes, $item[0]);
		}

		print "<td class=\"stata\">&nbsp;</td>";
		print "</tr>";


		$arrMediaTypes = array_flip($arrMediaTypes);


		$mod = 0;
		foreach ($userCategories as $categoryObj) {

			if (($mod % 2) == 0) {
				$css_class = "statb";
			} else {
				$css_class = "stata";
			}

			print "<tr>";
			print "<td nowrap=\"nowrap\" class=\"".$css_class."\">".$categoryObj->getName(true)."</td>";

			$arr2 = $s->getMediaCountByCategoryAndUserID(VCDUtils::getUserID(),$categoryObj->getID());
			$resultArr = getCategoryResults($arrMediaTypes,  $arr2);

			$category_sum = 0;
			foreach ($resultArr as $count) {
				print "<td class=\"".$css_class."\" align=\"right\">$count</td>";
				$category_sum += $count;
			}

			print "<td align=\"right\" class=\"".$css_class."\"><b>".$category_sum."</b></td>";
			print "</tr>";
			$mod++;

		}



		print "<tr>";
		print "<td>&nbsp;</td>";
		$total_sum = 0;
		foreach ($arr as $results_count) {
			$total_sum += $results_count[2];
			print "<td align=\"right\"><b>".$results_count[2]."</b></td>";
		}

		print "<td align=\"right\"><b>".$total_sum."</b></td>";

		print "</tr>";

		print "</table>";
		$language = new language(true);
		if (isset($_SESSION['vcdlang'])) {
			$language->load($_SESSION['vcdlang']);
		}


	?>

		<br/><hr/>
		<p align="center"><input onclick="window.close()" type="button" value="<?=$language->show('X_CLOSE')?>"/></p>


</body>
</html>
