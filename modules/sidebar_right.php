<? 

	if (isset($_COOKIE['rbar']) && $_COOKIE['rbar'] == 0) {
		print "<div id=\"r-col\" style=\"display:none\">";
	} else {
		print "<div id=\"r-col\">";
	}

	$maxTitlelen = 24;
	$tv_category = SettingsServices::getCategoryIDByName("tv shows");
	$xx_category = SettingsServices::getCategoryIDByName("adult");
	
		
	/* Top Ten latest all movies , except for TV Shows and Adult movies*/
	$arrExclude = array($tv_category, $xx_category);
	$movies = MovieServices::getTopTenList(0, $arrExclude);
	if (sizeof($movies) > 0) {
		print "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\" class=\"list\">";
		print "<tr><td class=\"header\">10 ".VCDLanguage::translate('misc.latestmovies')."</td></tr>";
		
		foreach ($movies as $movie) {
			echo "<tr><td><a href=\"./?page=cd&amp;vcd_id=".$movie->getID()."\" title=\"".$movie->getTitle()."\">".  VCDUtils::shortenText($movie->getTitle(), $maxTitlelen) . "</a></td></tr>";
		}
		
		print "</table>";
	}
	
	
	/* Top Ten latest TV shows */
	if ($tv_category != 0) {
		$movies = MovieServices::getTopTenList($tv_category);
		if (sizeof($movies) > 0) {
			print "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\" class=\"list\">";
			print "<tr><td class=\"header\">10 ".VCDLanguage::translate('misc.latesttv')."</td></tr>";
			
			foreach ($movies as $movie) {
				echo "<tr><td><a href=\"./?page=cd&amp;vcd_id=".$movie->getID()."\" title=\"".$movie->getTitle()."\">".  VCDUtils::shortenText($movie->getTitle(), $maxTitlelen) . "</a></td></tr>";
			}
			
			print "</table>";
		}
		
	}
	
	
	
	/* Top Ten latest Porn flix IF adult content is allowed and user has requested to see it .. */
	if (VCDUtils::showAdultContent()) {
		if ($xx_category != 0) {
			$movies = MovieServices::getTopTenList($xx_category);
			if (sizeof($movies) > 0) {
				print "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\" class=\"list\">";
				print "<tr><td class=\"header\">10 ".VCDLanguage::translate('misc.latestblue')."</td></tr>";
				
				foreach ($movies as $movie) {
					echo "<tr><td><a href=\"./?page=cd&amp;vcd_id=".$movie->getID()."\" title=\"".$movie->getTitle()."\">".  VCDUtils::shortenText($movie->getTitle(), $maxTitlelen) . "</a></td></tr>";
				}
				
				print "</table>";
			}
			
		}
	
	}
	
	// Check for custom frontpage settings
	if (VCDUtils::isLoggedIn()) {
		$user_id = VCDUtils::getUserID();
		$arr = SettingsServices::getMetadata(0, $user_id, metadataTypeObj::SYS_FRONTSTATS );
		if (is_array($arr) && sizeof($arr) == 1 && $arr[0] instanceof metadataObj) {
			$SHOW_STATS = $arr[0]->getMetadataValue();
			if ($SHOW_STATS) {
				printStatistics(false, 175, 'list');
			}
		}
		
	}
	
	
	
?>
</div>