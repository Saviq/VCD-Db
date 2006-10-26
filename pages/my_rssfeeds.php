<? 
	if (!VCDUtils::isLoggedIn()) {
		VCDException::display("User must be logged in");
		print "<script>self.close();</script>";
		exit();
	}

	
	// Get all the users feeds
	
	$SETTINGSClass = VCDClassFactory::getInstance('vcd_settings');
	$arrFeeds = $SETTINGSClass->getRssFeedsByUserId(VCDUtils::getUserID());

	print "<h1>".VCDLanguage::translate('menu.rss')."</h1>";
	
	foreach ($arrFeeds as $rssfeed) {
		showFeed($rssfeed['name'], $rssfeed['url']);
	}

?>