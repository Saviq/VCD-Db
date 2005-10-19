<div id="r-col">
<? 
	$VCDClass = VCDClassFactory::getInstance("vcd_movie");
	$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");
	
	$maxTitlelen = 17;

	
	
	/* Top Ten latest all movies */
	$movies = $VCDClass->getTopTenList();
	if (sizeof($movies) > 0) {
		print "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\" class=\"list\">";
		print "<tr><td class=\"header\">10 ".$language->show('X_LATESTMOVIES')."</td></tr>";
		
		foreach ($movies as $movie) {
			echo "<tr><td><a href=\"./?page=cd&amp;vcd_id=".$movie->getID()."\" title=\"".$movie->getTitle()."\">".  VCDUtils::shortenText($movie->getTitle(), $maxTitlelen) . "</a></td></tr>";
		}
		
		print "</table>";
	}
	
	
	/* Top Ten latest TV shows */
	$tv_category = $SETTINGSClass->getCategoryIDByName("tv shows");
	if ($tv_category != 0) {
		$movies = $VCDClass->getTopTenList($tv_category);
		if (sizeof($movies) > 0) {
			print "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\" class=\"list\">";
			print "<tr><td class=\"header\">10 ".$language->show('X_LATESTTV')."</td></tr>";
			
			foreach ($movies as $movie) {
				echo "<tr><td><a href=\"./?page=cd&amp;vcd_id=".$movie->getID()."\" title=\"".$movie->getTitle()."\">".  VCDUtils::shortenText($movie->getTitle(), $maxTitlelen) . "</a></td></tr>";
			}
			
			print "</table>";
		}
		
	}
	
	
	
	/* Top Ten latest Porn flix */
	$tv_category = $SETTINGSClass->getCategoryIDByName("adult");
	if ($tv_category != 0) {
		$movies = $VCDClass->getTopTenList($tv_category);
		if (sizeof($movies) > 0) {
			print "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\" class=\"list\">";
			print "<tr><td class=\"header\">10 ".$language->show('X_LATESTBLUE')."</td></tr>";
			
			foreach ($movies as $movie) {
				echo "<tr><td><a href=\"./?page=cd&amp;vcd_id=".$movie->getID()."\" title=\"".$movie->getTitle()."\">".  VCDUtils::shortenText($movie->getTitle(), $maxTitlelen) . "</a></td></tr>";
			}
			
			print "</table>";
		}
		
	}
	
	// Check for custom frontpage settings
	if (VCDUtils::isLoggedIn()) {
		$user_id = VCDUtils::getUserID();
		$arr = $SETTINGSClass->getMetadata(0, $user_id, 'frontstats');
		if (is_array($arr) && sizeof($arr) == 1 && $arr[0] instanceof metadataObj) {
			$SHOW_STATS = $arr[0]->getMetadataValue();
			if ($SHOW_STATS) {
				printStatistics(false, 175, 'list');
			}
		}
		
	}
	
	
	
?>
</div>