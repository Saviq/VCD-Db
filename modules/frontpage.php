<? 
	$SETTINGSClass = VCDClassFactory::getInstance('vcd_settings');
	$SHOW_STATS = true;
	$SHOW_RIGHT = true;
	$SHOW_FEEDS = false;
	$rsscount = 0;
	
	// Check for custom frontpage settings
	if (VCDUtils::isLoggedIn()) {
		$user_id = $_SESSION['user']->getUserID();
		$arr = $SETTINGSClass->getMetadata(0, $user_id, 'frontstats');
		if (is_array($arr) && sizeof($arr) == 1 && $arr[0] instanceof metadataObj) {
			$SHOW_STATS = $arr[0]->getMetadataValue();
		}
		$arr = $SETTINGSClass->getMetadata(0, $user_id, 'frontrss');
		if (is_array($arr) && sizeof($arr) == 1 && $arr[0] instanceof metadataObj) {
			$feedstring = $arr[0]->getMetadataValue();
			$feedarr = split("#", $feedstring);
			$rsscount = sizeof($feedarr);
			$SHOW_FEEDS = true;	
		}
		$arr = $SETTINGSClass->getMetadata(0, $user_id, 'frontbar');
			if (is_array($arr) && sizeof($arr) == 1 && $arr[0] instanceof metadataObj && $arr[0]->getMetadataValue() == 0) {
				$SHOW_RIGHT = false;
		}
		
		
	}
	

	if (!VCDUtils::isLoggedIn() || ($SHOW_STATS && !$SHOW_RIGHT)) {
		printStatistics();
	}
	
	
	
	if ($SHOW_FEEDS) {

		if ($rsscount > 3) {
			print "<table border=\"0\">";
			for ($i = 0; $i < $rsscount; $i++) {
				if ((bool)(($i % 2)==0) || ($i == 0)) {
					print "<tr>";
				}	
					
				if ((bool)(($i % 2)==0) && $i == ($rsscount-1)) {
					print "<td colspan=\"2\" valign=\"top\">";
				} else {
					print "<td valign=\"top\" width=\"50%\">";					
				}
				
				
				$curr_feed = $SETTINGSClass->getRssfeed($feedarr[$i]);
				ShowOneRSS($curr_feed['url']);
				print "</td>";
				
				if (((bool)(($i % 2)==1) && ($i != 0)) || ($i == ($rsscount-1))) {
					print "</tr>";
				}	
				
			}
			
			print "</table>";
		
		
		} else {
			foreach ($feedarr as $feed_id) {
			if (is_numeric($feed_id)) {
				$curr_feed = $SETTINGSClass->getRssfeed($feed_id);
				ShowOneRSS($curr_feed['url']);
				}
			}
		}
		
		
		
	}
	
		
	
?>


