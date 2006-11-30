<? 
	$cat_id = $_GET['studio_id'];
	
	$arrAdultStudios = PornstarServices::getStudiosInUse();
	$movies = MovieServices::getVcdByAdultStudio($cat_id);
	$count = sizeof($movies);
	
	$imagemode = false;
	if (isset($_GET['viewmode'])) {
		$imagemode = true;
		$viewbar = "(<a href=\"./?page=".$CURRENT_PAGE."&amp;studio_id=".$cat_id."\">".VCDLanguage::translate('movie.textview')."</a> / ".VCDLanguage::translate('movie.imageview').")";		
	} else {
		$viewbar = "(".VCDLanguage::translate('movie.textview')." / <a href=\"./?page=".$CURRENT_PAGE."&amp;studio_id=".$cat_id."&amp;viewmode=img\">".VCDLanguage::translate('movie.imageview')."</a>)";
	}
	
	
	
?>

<h1>Adult films by studio</h1>

<? 

	if ($imagemode) {
		$dropdownurl = './?page=adultcategory&amp;viewmode=img&amp;';
	} else {
		$dropdownurl = './?page=adultcategory&amp;';
	}
	

	print "<form>";
	print "&nbsp;<span class=\"bold\">Current category</span>&nbsp;";
	print "<select name=\"category\" onchange=\"location.href='".$dropdownurl."studio_id='+this.value+''\">";
	evalDropdown($arrAdultStudios, $cat_id, false);
	print "</select>&nbsp; (".$count." movies) ".$viewbar."</form>";
	
	
	$batch  = 0;
	if (isset($_GET['batch']))
		$batch = $_GET['batch'];
	
	$Recordcount = SettingsServices::getSettingsByKey("PAGE_COUNT");
	$offset = $batch*$Recordcount;
	
	
	
	$movies = createObjFilter($movies, $batch*$Recordcount, $Recordcount);
	
	if (sizeof($movies) > 0) {
		
		// Display the pager
		if ($imagemode) {
			$suburl = "studio_id=".$cat_id."&amp;viewmode=img";
		} else {
			$suburl = "studio_id=".$cat_id;
		}
		
		
		pager($count, $batch, $suburl);
		
		if (!$imagemode) {
			
			print "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\" class=\"displist\">";
			print "<tr><td class=\"header\">".VCDLanguage::translate('movie.title')."</td><td class=\"header\" nowrap=\"nowrap\">".VCDLanguage::translate('movie.year')."</td><td class=\"header\">Screens</td></tr>";
				
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