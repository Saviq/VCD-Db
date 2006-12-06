<? 
	if (!VCDUtils::isLoggedIn()) {
		VCDException::display("User must be logged in");
		print "<script>self.close();</script>";
		exit();
	}

		
	// Get all the users feeds
	$arrFeeds = SettingsServices::getRssFeedsByUserId(VCDUtils::getUserID());
	$ClassRss = new VCDRss();

	print "<h1>".VCDLanguage::translate('menu.rss')."</h1>";
	
	foreach ($arrFeeds as $rssObj) {
		if (!$rssObj->isVcddbFeed()) {continue;}
		$ClassRss->showRemoteVcddbFeed($rssObj->getName(), $rssObj->getFeedUrl());
	}

?>