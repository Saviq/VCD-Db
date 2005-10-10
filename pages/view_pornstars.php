<h2>Pornstars</h2>
<? 
	global $language;
	$SETTINGSClass = VCDClassFactory::getInstance('vcd_settings');
	
	$show_adult = false;
	if (VCDUtils::isLoggedIn()) {
		$show_adult =& $_SESSION['user']->getPropertyByKey('SHOW_ADULT');
	}
	
	if (VCDUtils::isLoggedIn() && $SETTINGSClass->getSettingsByKey('SITE_ADULT') && $show_adult && isset($_GET['view'])) {
		$mode = $_GET['view'];
		$active = false;
		
		$PORNClass = new vcd_pornstar();
		if (strcmp($mode, 'active') == 0) {
			$arrAlpha = $PORNClass->getPornstarsAlphabet(true);
			$active = true;
		} else {
			$arrAlpha = $PORNClass->getPornstarsAlphabet(false);
		}
		asort($arrAlpha);
		
		
		print "<div align=\"center\">";
		foreach ($arrAlpha as $arr) {
			if (isset($_GET['viewmode']) && $_GET['viewmode'] == 'img') { 
				print "<a href=\"./?page=pornstars&amp;view=".$mode."&amp;l=$arr&amp;viewmode=img\">" . $arr . "</a><img src=\"images/dot.gif\" border=\"0\" align=\"absmiddle\" hspace=\"5\"/>";
			} else {
				print "<a href=\"./?page=pornstars&amp;view=".$mode."&amp;l=$arr\">" . $arr . "</a><img src=\"images/dot.gif\" border=\"0\" align=\"absmiddle\" hspace=\"5\"/>";
			}
		} 
		print "</div>";
				
		if (isset($_GET['l'])) {
			$pornstars = $PORNClass->getPornstarsByLetter($_GET['l'], $active);
			
			print "<hr/><div align=\"center\"><span class=\"bold\">".sizeof($pornstars)." pornstars</span> begin with letter \"".$_GET['l']."\" (<a href=\"./?page=pornstars&amp;view=".$_GET['view']."&amp;l=".$_GET['l']."\">Text view</a> / <a href=\"./?page=pornstars&amp;view=".$_GET['view']."&amp;l=".$_GET['l']."&amp;viewmode=img\">Image view</a>)</div>";
			
			
			
			if (isset($_GET['viewmode']) && $_GET['viewmode'] == 'img') {
				
			print "<div id=\"actorimages\">";
					foreach ($pornstars as $pornstar) {
						$pornstar->showImage() ;
					}
			print "</div>";
				
			} else {
			
				print "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\" class=\"displist\">";
				print "<tr><td class=\"header\" width=\"70%\">Title</td><td class=\"header\">Homepage</a><td class=\"header\">Movies</td></tr>";
				foreach ($pornstars as $obj) {
					
					$hp = $obj->getHomePage();
					$url = "&nbsp;";
					if (strlen($hp) > 1) {
						$url = "<a href=\"".$hp."\" target=\"_new\">Click here</a>";
					}
					
					print "<tr><td><a href=\"./?page=pornstar&amp;pornstar_id=".$obj->getID()."\">".$obj->getName()."</a></td><td nowrap>".$url."</td><td>".$obj->getMovieCount()."</td></tr>";
				}
				
				print "</table>";
				
			}
		}
		
	
	
		
	} else {
		redirect();
	}

?>