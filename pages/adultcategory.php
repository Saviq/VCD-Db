<? 
	$cat_id = $_GET['category_id'];
	global $ClassFactory;
	$VCDClass = $ClassFactory->getInstance('vcd_movie');
	$SETTINGSclass = $ClassFactory->getInstance("vcd_settings");
	$PORNClass= $ClassFactory->getInstance("vcd_pornstar");
	
	$arrAdultCats = $PORNClass->getSubCategoriesInUse();
	$movies = $VCDClass->getVcdByAdultCategory($cat_id);
	$count = sizeof($movies);
	
	$batch  = 0;
	if (isset($_GET['batch']))
		$batch = $_GET['batch'];
	
	$imagemode = false;
	if (isset($_GET['viewmode'])) {
		$imagemode = true;
		$viewbar = "(<a href=\"./?page=".$CURRENT_PAGE."&amp;category_id=".$cat_id."&amp;batch=".$batch."\">".$language->show('M_TEXTVIEW')."</a> / ".$language->show('M_IMAGEVIEW').")";		
	} else {
		$viewbar = "(".$language->show('M_TEXTVIEW')." / <a href=\"./?page=".$CURRENT_PAGE."&amp;category_id=".$cat_id."&amp;viewmode=img&amp;batch=".$batch."\">".$language->show('M_IMAGEVIEW')."</a>)";
	}
		
?>

<h1>Adult films by category</h1>

<? 

	if ($imagemode) {
		$dropdownurl = './?page=adultcategory&amp;viewmode=img&amp;';
	} else {
		$dropdownurl = './?page=adultcategory&amp;';
	}

	print "<form>";
	print "&nbsp;<span class=\"bold\">Current category</span>&nbsp;";
	print "<select name=\"category\" onchange=\"location.href='".$dropdownurl."category_id='+this.value+''\">";
	evalDropdown($arrAdultCats, $cat_id, false);
	print "</select>&nbsp; (".$count." movies) ".$viewbar."</form>";
	
	
	
	$Recordcount = $SETTINGSclass->getSettingsByKey("PAGE_COUNT");
	$offset = $batch*$Recordcount;
	
	
	
	$movies = createObjFilter($movies, $batch*$Recordcount, $Recordcount);
	
	if (sizeof($movies) > 0) {
		
		
		// Display the pager
		if ($imagemode) {
			$suburl = "category_id=".$cat_id."&amp;viewmode=img";
		} else {
			$suburl = "category_id=".$cat_id;
		}
				
		pager($count, $batch, $suburl);
		
		
		if (!$imagemode) {
		
			print "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\" class=\"displist\">";
			print "<tr><td class=\"header\">".$language->show('M_TITLE')."</td><td class=\"header\" nowrap=\"nowrap\">".$language->show('M_YEAR')."</td><td class=\"header\">Screens</td></tr>";
			foreach ($movies as $movie) {
				
				$screens = "&nbsp;";
				if ($movie->hasScreenshots()) {
					$screens = "<a href=\"./?page=cd&vcd_id=".$movie->getID()."&amp;screens=on\"><img src=\"images/check.gif\" alt=\"Screenshots available\" border=\"0\"/></a>";
				}
				
				print "<tr>
						   <td width=\"80%\"><a href=\"./?page=cd&amp;vcd_id=".$movie->getID()."\">".$movie->getTitle()."</a></td>
					       <td nowrap>".$movie->getYear()."</td>
				           <td nowrap align=\"center\">".$screens."</td>
					   </tr>";
			}
			print "</table>";	
		} else {
			print "<hr/>";
			print "<div id=\"actorimages\">";
			foreach ($movies as $movie) {
								
				$coverObj = $movie->getCover('thumbnail');
				if ($coverObj instanceof cdcoverObj ) {
					print $coverObj->showImageAndLink("./?page=cd&amp;vcd_id=".$movie->getID()."",$movie->getTitle());
					
				}
			}
			
			print "</div>";
			
		}
		
	}
	
?>