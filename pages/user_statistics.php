<?
	$user_id = VCDUtils::getUserID();
	$statsArr = SettingsServices::getUserStatistics($user_id);
	$moviecount = MovieServices::getMovieCount($user_id);
	
	$useGD = isset($_GET['view']) && strcmp($_GET['view'], "gfx")==0;

?>

<h1><?=VCDLanguage::translate('menu.statistics')?></h1>

<?

	// Translate category names
	$mapping = getCategoryMapping();
	$altLang = VCDClassFactory::getInstance('VCDLanguage')->isEnglish();
	$statimg = "<a href=\"./?page=private&amp;o=stats&amp;view=gfx\"><img src=\"images/graph.gif\" border=\"0\" vspace=\"0\"/></a>";

	
	
	if ($useGD) {
	
		require_once(VCDDB_BASE.DIRECTORY_SEPARATOR.'classes/external/powergraph.php');
		 
		$skin = 1;
		$type = 5;
		
		$PG = new PowerGraphic();
		$PG->title = VCDLanguage::translate('movie.category');
		$PG->axis_x = VCDLanguage::translate('movie.category');
		$PG->axis_y = "Nr";
		$PG->skin = $skin;
		$PG->type  = $type;
		
		$arrCats = $statsArr['category'];

		$i = 0;
		foreach ($arrCats as $subArr) {
				$category = SettingsServices::getMovieCategoryByID($subArr[0])->getName(true);
				$num = $subArr[1];
				$PG->x[$i] = $category;
				$PG->y[$i] = $num;		
				$i++;
		}
		$qs = base64_encode($PG->create_query_string());
		echo '<img src="vcd_image.php?o=' . $qs . '" />';
		
		print "<br>";
		
		$PG = new PowerGraphic();
		$PG->title = VCDLanguage::translate('movie.media');
		$PG->axis_x = VCDLanguage::translate('movie.media');
		$PG->axis_y = "Nr";
		$PG->skin = $skin;
		$PG->type  = $type;
		
		$arrCats = $statsArr['media'];

		$i = 0;
		foreach ($arrCats as $subArr) {
				$media = SettingsServices::getMediaTypeByID($subArr[0])->getDetailedName();
				$num = $subArr[1];
				$PG->x[$i] = $media;
				$PG->y[$i] = $num;		
				$i++;
		}
		$qs = base64_encode($PG->create_query_string());
		echo '<img src="vcd_image.php?o=' . $qs . '" />';
		
		print "<br>";
		
		$PG = new PowerGraphic();
		$PG->title = VCDLanguage::translate('movie.year');
		$PG->axis_x = VCDLanguage::translate('movie.year');
		$PG->axis_y = "Nr";
		$PG->skin = $skin;
		$PG->type  = $type;
		
		$arrCats = $statsArr['year'];

		$i = 0;
		foreach ($arrCats as $subArr) {
				$year = $subArr[0];
				$num = $subArr[1];
				$PG->x[$i] = $year;
				$PG->y[$i] = $num;		
				$i++;
		}
		$qs = base64_encode($PG->create_query_string());
		echo '<img src="vcd_image.php?o=' . $qs . '" />';
		
	
	
	
	
	} else {
	


		$arrCats = $statsArr['category'];
		// Get the highest percent
		$highest = round((($arrCats[0][1]/$moviecount)*100),1);
		$multiplier = 96/$highest;
	
		print "<table cellspacing=\"1\" cellpadding=\"1\" border=\"0\" width=\"100%\" class=\"list\">";
		print "<tr><td width=\"1\">{$statimg}</td><td class=\"statheader\">".VCDLanguage::translate('movie.category')."</td><td class=\"statheader\">".$moviecount."</td><td class=\"statheader\">&nbsp;</td></tr>";
		foreach ($arrCats as $subArr) {
			$category = SettingsServices::getMovieCategoryByID($subArr[0])->getName(true);
			if (!$altLang && key_exists($category, $mapping)) {
				$category = VCDLanguage::translate($mapping[$category]);
			}
	
			$num = $subArr[1];
			$percent = round((($num/$moviecount)*100),1);
			$imgpercent = $percent*$multiplier;
	
			$img = "<img src=\"images/bar_l.gif\" height=\"10\" border=\"0\" alt=\"".$percent."%\"/><img src=\"images/bar.gif\" height=\"10\" alt=\"".$percent."%\"  width=\"".$imgpercent."%\" border=\"0\"/><img src=\"images/bar_r.gif\" height=\"10\" alt=\"".$percent."%\" border=\"0\"/>";
	
			print "<tr><td colspan=\"2\" width=\"130\"><a href=\"./?page=category&amp;category_id=".$subArr[0]."\">".$category."</a></td><td width=\"30\" align=\"right\">".$num."</td><td width=\"72%\" nowrap=\"nowrap\">$img</td></tr>";
	
		}
		print "</table>";
	
		print "<br>";
	
	
		$arrMedia = $statsArr['media'];
		// Get the highest percent
		$highest = round((($arrMedia[0][1]/$moviecount)*100),1);
		$multiplier = 96/$highest;
		print "<table cellspacing=\"1\" cellpadding=\"1\" border=\"0\" width=\"100%\" class=\"list\">";
		print "<tr><td width=\"1\">{$statimg}</td><td class=\"statheader\">".VCDLanguage::translate('movie.media')."</td><td class=\"statheader\">".$moviecount."</td><td class=\"statheader\">&nbsp;</td></tr>";
		foreach ($arrMedia as $subArr) {
	
			$media = SettingsServices::getMediaTypeByID($subArr[0])->getDetailedName();
			$num = $subArr[1];
			$percent = round((($num/$moviecount)*100),1);
			$imgpercent = $percent*$multiplier;
			$img = "<img src=\"images/bar_l.gif\" height=\"10\" border=\"0\" alt=\"".$percent."%\"/><img src=\"images/bar.gif\" height=\"10\" alt=\"".$percent."%\"  width=\"".$imgpercent."%\" border=\"0\"/><img src=\"images/bar_r.gif\" alt=\"".$percent."%\" height=\"10\" border=\"0\"/>";
			print "<tr><td colspan=\"2\" width=\"130\">".$media."</td><td width=\"30\" align=\"right\">".$num."</td><td width=\"72%\" nowrap=\"nowrap\">".$img."</td></tr>";
		}
		print "</table>";
	
	
		print "<br>";
	
		$arrYears = $statsArr['year'];
		// We have to brute force to find the highest entry
		$highest = 0;
		foreach ($arrYears as $tmp) {$tmp[1] > $highest ? $highest = $tmp[1] : 0;}
		$highest = round((($highest/$moviecount)*100),1);
		$multiplier = 96/$highest;
	
		print "<table cellspacing=\"1\" cellpadding=\"1\" border=\"0\" width=\"100%\" class=\"list\">";
		print "<tr><td width=\"1\">{$statimg}</td><td class=\"statheader\" nowrap=\"nowrap\">".VCDLanguage::translate('movie.year')."</td><td class=\"statheader\">".$moviecount."</td><td class=\"statheader\">&nbsp;</td></tr>";
		foreach ($arrYears as $subArr) {
			$year= $subArr[0];
			$num = $subArr[1];
			$percent = round((($num/$moviecount)*100),1);
			$imgpercent = $percent*$multiplier;
	
			$img = "<img src=\"images/bar_l.gif\" height=\"10\" alt=\"".$percent."%\"  border=\"0\"/><img src=\"images/bar.gif\" height=\"10\" alt=\"".$percent."%\"  width=\"".$imgpercent."%\" border=\"0\"/><img src=\"images/bar_r.gif\" height=\"10\" alt=\"".$percent."%\"  border=\"0\"/>";
	
			print "<tr><td width=\"130\" colspan=\"2\">".$year."</td><td width=\"30\" align=\"right\">".$num."</td><td width=\"72%\" nowrap=\"nowrap\">$img</td></tr>";
		}
		print "</table>";

	}


?>