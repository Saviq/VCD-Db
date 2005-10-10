<? 

	
	$CLASSSettings = VCDClassFactory::getInstance("vcd_settings");
	$CLASSVcd = VCDClassFactory::getInstance("vcd_movie");
	
	$user_id = $_SESSION['user']->getUserID();
	$statsArr = $CLASSSettings->getUserStatistics($user_id);
	$moviecount = $CLASSVcd->getMovieCount($user_id);

?>

<h1><?=$language->show('MENU_STATISTICS')?></h1>


<? 
	
	
	// Translate category names
	$mapping = getCategoryMapping();			
	$altLang = $language->isUsingDefault();
	
	
		
	$arrCats = $statsArr['category'];
	// Get the highest percent
	$highest = round((($arrCats[0][1]/$moviecount)*100),1);
	$multiplier = round((97/$highest),1);
	
	print "<table cellspacing=\"1\" cellpadding=\"1\" border=\"0\" width=\"100%\" class=\"list\">";
	print "<tr><td class=\"statheader\">".$language->show('M_CATEGORY')."</td><td class=\"statheader\">".$moviecount."</td><td class=\"statheader\">&nbsp;</td></tr>";
	foreach ($arrCats as $subArr) {
		$category = $CLASSSettings->getMovieCategoryByID($subArr[0])->getName();
		if (!$altLang && key_exists($category, $mapping)) {
			$category = $language->show($mapping[$category]);
		}
		
		$num = $subArr[1];
		$percent = round((($num/$moviecount)*100),1);
		$imgpercent = $percent*$multiplier;
		
		$img = "<img src=\"images/bar_l.gif\" height=\"10\" border=\"0\" alt=\"".$percent."%\"/><img src=\"images/bar.gif\" height=\"10\" alt=\"".$percent."%\"  width=\"".$imgpercent."%\" border=\"0\"/><img src=\"images/bar_r.gif\" height=\"10\" alt=\"".$percent."%\" border=\"0\"/>";
		
		print "<tr><td width=\"130\"><a href=\"./?page=category&amp;category_id=".$subArr[0]."\">".$category."</a></td><td width=\"30\" align=\"right\">".$num."</td><td nowrap=\"nowrap\">$img</td></tr>";
		
	}
	print "</table>";
	
	print "<br>";
	
	
	$arrMedia = $statsArr['media'];
	// Get the highest percent
	$highest = round((($arrMedia[0][1]/$moviecount)*100),1);
	$multiplier = round((97/$highest),1);
	print "<table cellspacing=\"1\" cellpadding=\"1\" border=\"0\" width=\"100%\" class=\"list\">";
	print "<tr><td class=\"statheader\">".$language->show('M_MEDIA')."</td><td class=\"statheader\">".$moviecount."</td><td class=\"statheader\">&nbsp;</td></tr>";
	foreach ($arrMedia as $subArr) {
		
		$media = $CLASSSettings->getMediaTypeByID($subArr[0])->getDetailedName();
		$num = $subArr[1];
		$percent = round((($num/$moviecount)*100),1);
		$imgpercent = $percent*$multiplier;
		$img = "<img src=\"images/bar_l.gif\" height=\"10\" border=\"0\" alt=\"".$percent."%\"/><img src=\"images/bar.gif\" height=\"10\" alt=\"".$percent."%\"  width=\"".$imgpercent."%\" border=\"0\"/><img src=\"images/bar_r.gif\" alt=\"".$percent."%\" height=\"12\" border=\"0\"/>";
		print "<tr><td width=\"130\">".$media."</td><td width=\"30\" align=\"right\">".$num."</td><td nowrap=\"nowrap\">".$img."</td></tr>";
	}
	print "</table>";
	
	
	print "<br>";
	
	$arrYears = $statsArr['year'];
	// We have to brute force to find the highest entry
	$highest = 0;
	foreach ($arrYears as $tmp) {$tmp[1] > $highest ? $highest = $tmp[1] : 0;}
	$highest = round((($highest/$moviecount)*100),1);
	$multiplier = round((97/$highest),1);	
	
	print "<table cellspacing=\"1\" cellpadding=\"1\" border=\"0\" width=\"100%\" class=\"list\">";
	print "<tr><td class=\"statheader\" nowrap=\"nowrap\">".$language->show('M_YEAR')."</td><td class=\"statheader\">".$moviecount."</td><td class=\"statheader\">&nbsp;</td></tr>";
	foreach ($arrYears as $subArr) {
		$year= $subArr[0];
		$num = $subArr[1];
		$percent = round((($num/$moviecount)*100),1);
		$imgpercent = $percent*$multiplier;
		
		$img = "<img src=\"images/bar_l.gif\" height=\"10\" alt=\"".$percent."%\"  border=\"0\"/><img src=\"images/bar.gif\" height=\"10\" alt=\"".$percent."%\"  width=\"".$imgpercent."%\" border=\"0\"/><img src=\"images/bar_r.gif\" height=\"12\" alt=\"".$percent."%\"  border=\"0\"/>";
		
		print "<tr><td width=\"130\">".$year."</td><td width=\"30\" align=\"right\">".$num."</td><td nowrap=\"nowrap\">$img</td></tr>";
	}
	print "</table>";
	
		
	

?>