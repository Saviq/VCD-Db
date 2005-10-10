<? 
	if (!VCDUtils::isLoggedIn()) {
		VCDException::display("User must be logged in");
		exit();
	}	

	print "<h1>" . $language->show('MENU_WISHLISTPUBLIC') . "</h1>";
	
	// Get all available wishlists except for my own.
	$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");
	$USERClass = VCDClassFactory::getInstance('vcd_user');
	$propObj = $USERClass->getPropertyByKey(vcd_user::$PROPERTY_WISHLIST);
	if ($propObj instanceof userPropertiesObj ) {
		$usersArr = $USERClass->getAllUsersWithProperty($propObj->getpropertyID());
		if (sizeof($usersArr) > 0) {
			// Loop through the users wishlists
			foreach ($usersArr as $userObj) {
				if ($userObj->getUserID() != $_SESSION['user']->getUserID()) {
					$currList = $SETTINGSClass->getWishList($userObj->getUserID());
					
					if (sizeof($currList) > 0) {
					
						print "<br/><div class=\"bold\" style=\"padding-left:15px\">".$userObj->getUserName()." (".$userObj->getFullname().")</div>";
						print "<ol style=\"margin-top:0px\">";
						foreach ($currList as $item) {
							$css = "red";
							$title = $language->show('W_NOTOWN');
							if ($item['mine'] == 1) {
								$css = "green";
								$title = $language->show('W_OWN');
							}
							print "<li class=\"".$css."\" title=\"".$title."\"><a href=\"./?page=cd&amp;vcd_id=".$item['id']."\">".$item['title']."</a></li>";
						}
						print "</ol>";
					}
				}
			}
		}
	}
				
				
	
	
?>

