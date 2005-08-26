<? 
	if (!VCDUtils::isLoggedIn()) {
		VCDException::display("User must be logged in");
		print "<script>self.close();</script>";
		exit();
	}

	
	// Get all the users feeds
	global $ClassFactory;
	$SETTINGSClass = $ClassFactory->getInstance('vcd_settings');
	$arrFeeds = $SETTINGSClass->getRssFeedsByUserId($_SESSION['user']->getUserID());

	print "<h1>".$language->show('MENU_RSS')."</h1>";
	
	foreach ($arrFeeds as $rssfeed) {
		showFeed($rssfeed['name'], $rssfeed['url']);
	}

?>