<?php
/**
 * VCD-db - a web based VCD/DVD Catalog system
 * Copyright (C) 2003-2004 Konni - konni.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * @author  H�kon Birgsson <konni@konni.com>
 * @package Functions
 * @subpackage Web
 * @version $Id$
 */
?>
<?

/**
 * Display the userlink in the topmenu section.
 *
 */
function display_topmenu() {

	global $language;

	if (VCDUtils::isLoggedIn()) {
		$user = $_SESSION['user'];
		echo "<a href=\"./?page=private&o=settings\">".$user->getFullname()."</a> ";

		if ($user->isAdmin()) {
			?>| <a href="#" onclick="openAdminConsole()"><?=$language->show('MENU_CONTROLPANEL')?></a><?
		}

		?> | <a href="./?do=logout"><?=$language->show('MENU_LOGOUT')?></a> <?

	} elseif (LDAP_AUTH == 0) {
		?><a href="./?page=register"><?=$language->show('MENU_REGISTER')?></a> <?
	}

	?>| <a href="./?page=detailed_search"><?=$language->show('SEARCH_EXTENDED')?></a> |<?

}

/**
 * Enter description here...
 *
 */
function display_userlinks() {
	global $language;

	$SETTINGSClass = VCDClassFactory::getInstance('vcd_settings');
	$CLASSVcd = VCDClassFactory::getInstance("vcd_movie");
	$rssLink = "";
	if (sizeof($SETTINGSClass->getRssFeedsByUserId(VCDUtils::getUserID()))>0) {
		$rssLink = "<span class=\"nav\"><a href=\"./?page=private&o=rss\" class=\"navx\">".$language->show('MENU_RSS')."</a></span>";
	}
	?>
	<div class="topic"><?=$language->show('MENU_MINE')?></div>
	<span class="nav"><a href="./?page=private&amp;o=settings" class="navx"><?=$language->show('MENU_SETTINGS')?></a></span>
	<? if (strcmp($_SESSION['user']->getRoleName(), 'Viewer') != 0) { ?>
	<span class="nav"><a href="./?page=private&amp;o=movies" class="navx"><?=$language->show('MENU_MOVIES')?></a></span>
	<span class="nav"><a href="./?page=private&amp;o=new" class="navx"><?=$language->show('MENU_ADDMOVIE')?></a></span>
	<span class="nav"><a href="./?page=private&amp;o=loans" class="navx"><?=$language->show('MENU_LOANSYSTEM')?></a></span>
	<? }
	// Check for shared wishlists and if so .. display the "others wishlist link"
		if ($SETTINGSClass->isPublicWishLists(VCDUtils::getUserID())) {
		?><span class="nav"><a href="./?page=private&amp;o=publicwishlist" class="navx"><?=$language->show('MENU_WISHLISTPUBLIC')?></a></span><?
	}
	?>
	<span class="nav"><a href="./?page=private&amp;o=wishlist" class="navx"><?=$language->show('MENU_WISHLIST')?></a></span>
	<? if ($CLASSVcd->getMovieCount(VCDUtils::getUserID()) > 0)  {?>
	<span class="nav"><a href="./?page=private&amp;o=stats" class="navx"><?=$language->show('MENU_STATISTICS')?></a></span>
	<? } ?>
	<?=$rssLink?>
	<?
}

/**
 * Enter description here...
 *
 */
function display_adultmenu() {
	global $language;
	$SETTINGSClass = VCDClassFactory::getInstance('vcd_settings');

	$show_adult = false;
	if (VCDUtils::isLoggedIn()) {
		$show_adult = $_SESSION['user']->getPropertyByKey('SHOW_ADULT');
	}
	if (VCDUtils::isLoggedIn() && $SETTINGSClass->getSettingsByKey('SITE_ADULT') && $show_adult) {
		?>
		<div class="topic">Pornstars</div>
		<ul>
		<li><a href="./?page=pornstars&amp;view=all">View all</a></li>
		<li><a href="./?page=pornstars&amp;view=active">View active</a></li>
		</ul>

		<?
	}

}

/**
 * Enter description here...
 *
 * @param unknown $imdb_id
 */
function display_imdbLinks($imdb_id) {
	global $language;

	print "<h2>".$language->show('I_LINKS')."</h2>";
	print "<ul>";
	print "<li><a href=\"http://www.imdb.com/Title?".$imdb_id."\" target=\"new\">".$language->show('I_DETAILS')."</a></li>";
	print "<li><a href=\"http://www.imdb.com/Plot?".$imdb_id."\" target=\"new\">".$language->show('I_PLOT')."</a></li>";
	print "<li><a href=\"http://www.imdb.com/Gallery?".$imdb_id."\" target=\"new\">".$language->show('I_GALLERY')."</a></li>";
	print "<li><a href=\"http://www.imdb.com/Trailers?".$imdb_id."\" target=\"new\">".$language->show('I_TRAILERS')."</a></li>";
	print "</ul>";
}


/**
 * Enter description here...
 *
 */
function display_toggle() {
	global $CURRENT_PAGE;
	global $language;
	if ($CURRENT_PAGE == "") {
	?>

	<div class="topic"><?=$language->show('X_TOGGLE')?></div>
	<div class="forms" align="center">
	<a href="javascript:show('r-col')">[<?=$language->show('X_TOGGLE_ON')?>]</a>-<a href="javascript:hide('r-col')">[<?=$language->show('X_TOGGLE_OFF')?>]</a>
	</div>


	<? }
}

/**
 * Enter description here...
 *
 */
function display_topusers() {
	global $language;
	$USERClass = VCDClassFactory::getInstance('vcd_user');
	$list = $USERClass->getUserTopList();
	if (sizeof($list) > 0) {
		$i = 0;
		print "<ul>";
		foreach ($list as $item) {
			if ($i > 5) break;
			print "<li>" . $item[0] . " (".$item[1]. ")</li>";
			$i++;
		}
		print "</ul>";
		unset($list);
	} else {
		print "<ul><li>".$language->show('X_NOUSERS')."</li></ul>";
	}
}

/**
 * Enter description here...
 *
 */
function display_moviecategories() {
	global $language;

	?>	<div class="topic"><?=$language->show('MENU_CATEGORIES')?></div> 	<?


	$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");
	$categories = $SETTINGSClass->getMovieCategoriesInUse();
	$adult_id = $SETTINGSClass->getCategoryIDByName('adult');
	$show_adult = (bool)$SETTINGSClass->getSettingsByKey('SITE_ADULT') && VCDUtils::isLoggedIn();
	if (VCDUtils::isLoggedIn()) {
		$show_adult = $_SESSION['user']->getPropertyByKey('SHOW_ADULT');
	}


	$curr_catid = -1;
	if (isset($_GET['category_id']) && is_numeric($_GET['category_id'])) {
		$curr_catid = $_GET['category_id'];
	}

	if (sizeof($categories) > 0) {
		foreach ($categories as $category) {
			$cssclass = "nav";
			if ($category->getID() == $curr_catid) {
				$cssclass = "navon";
			}
			if ($category->getID() == $adult_id) {
				if ($show_adult) {
					print "<span class=\"".$cssclass."\"><a href=\"./?page=category&amp;category_id=".$category->getID()."\" class=\"navx\">" . $category->getName(true) . "</a></span>";
				}
			} else {
				print "<span class=\"".$cssclass."\"><a href=\"./?page=category&amp;category_id=".$category->getID()."\" class=\"navx\">" . $category->getName(true) . "</a></span>";
			}
		}
	} else {
		print "<ul><li>".$language->show('X_NOCATS')."</li></ul>";
	}


	unset($categories);
}

/*  display pager for scrolling through recordsets on page */
/**
 * Enter description here...
 *
 * @param unknown $totalRecords
 * @param unknown $current_pos
 * @param unknown $url
 */
function pager($totalRecords, $current_pos, $url) {

	global $CURRENT_PAGE;

	$SetttingsClass = VCDClassFactory::getInstance("vcd_settings");
	$recordCount = $SetttingsClass->getSettingsByKey("PAGE_COUNT");
	$totalPages = floor($totalRecords / $recordCount);


	if ($totalRecords < $recordCount) {
		return;
	}


	$nextpos = $current_pos + 1;
	$backpos = $current_pos - 1;

	if ($current_pos > 0) {
		$first = "<a href=\"./?page=".$CURRENT_PAGE."&amp;".$url."&amp;batch=0\">&lt;</a>";
	} else {
		$first = "&lt;";
	}

	if ($current_pos >= $totalPages) {
		$last  = "&gt;";
	} else {
		$last  = "<a href=\"./?page=".$CURRENT_PAGE."&amp;".$url."&amp;batch=$totalPages\">&gt;</a>";
	}

	if ($current_pos > 0) {
		$back  = "<a href=\"./?page=".$CURRENT_PAGE."&amp;".$url."&amp;batch=$backpos\">&lt;</a>";
	} else {
		$back  = "&lt;";
	}


	if ($current_pos >= $totalPages) {
		$next  = "&gt;";
	} else {
		$next  = "<a href=\"./?page=".$CURRENT_PAGE."&amp;".$url."&amp;batch=$nextpos\">&gt;</a>";
	}

	$page = ($current_pos+1) . " of " . ($totalPages+1);

	print "<div id=\"pager\">" . $first . $back ." [$page] " . $next . $last . "</div>";

}

/**
 * Enter description here...
 *
 * @param unknown $layername
 */
function hidelayer($layername) {
	print "<script>hide('".$layername."')</script>";
}

/**
 * Enter description here...
 *
 */
function display_search() {

	global $language;

	// Check for last search method
	$lastkey = "";
	if (isset($_SESSION['searchkey'])) {
		$lastkey = $_SESSION['searchkey'];
	}

	?>
	<div class="topic"><?=$language->show('SEARCH')?></div>
	<div class="forms">
	<form action="search.php" method="get">
	<input type="text" name="searchstring" class="dashed" style="width:78px;"/>&nbsp;<input type="submit" value="<?=$language->show('SEARCH')?>" class="buttontext"/><br/>
	<input type="radio" name="by" value="title" <? if ($lastkey == '' || $lastkey == 'title') {print "checked=\"checked\"";} ?> class="nof"/><?=$language->show('SEARCH_TITLE')?><br/>
	<input type="radio" name="by" value="actor" <? if ($lastkey == 'actor') {print "checked=\"checked\"";} ?> class="nof"/><?=$language->show('SEARCH_ACTOR')?><br/>
	<input type="radio" name="by" value="director" <? if ($lastkey == 'director') {print "checked=\"checked\"";} ?> class="nof"/><?=$language->show('SEARCH_DIRECTOR')?><br/>
	</form>
	</div>
<?
}

/**
 * Enter description here...
 *
 */
function reloadandclose() {
	print "onload=\"window.opener.location.reload();window.close()\"";
}


/* Uses the getList from the Object to dynamicly create dropdown  */
/**
 * Enter description here...
 *
 * @param unknown $arrObjects
 * @param unknown $selected_index
 * @param unknown $showtitle
 * @param unknown $title
 */
function evalDropdown($arrObjects, $selected_index = -1, $showtitle = true, $title = "") {

	// Check for preliminaries ..
	if (sizeof($arrObjects) == 0) {
		return;
	}

	// Check if class exists and if he implements the getList function
	$objType = $arrObjects[0];

	if (class_exists(get_class($objType))) {
		if (!method_exists($objType, 'getList')) {
			VCDException::display(get_class($objType) . " must implement getList<break>before using function evalDropdown");
			return;
		}
	} else {
		VCDException::display("Class " . get_class($objType) . " does not exist");
		return;
	}

	// ok we are all set to display the dropdown
	if ($showtitle) {
		if ($title == "") {
			print "<option value=\"null\">Select</option>";
		} else {
			print "<option value=\"null\">".$title."</option>";
		}

	}


	foreach ($arrObjects as $obj) {
		$data = $obj->getList();
		if ($selected_index == $data['id']) {
				print "<option value=\"".$data['id']."\" selected>".$data['name']."</option>";
			} else {
				print "<option value=\"".$data['id']."\">".$data['name']."</option>";
			}
	}
	print "</select>";
}



/**
 * Enter description here...
 *
 * @param unknown $pornstar_id
 * @param unknown $pornstar_name
 * @param unknown $movie_id
 */
function make_pornstarlinks($pornstar_id, $pornstar_name, $movie_id) {
		global $language;
		?>
		<td>
			<a href="javascript:jumpTo('<?=$pornstar_name ?>','excalibur')"><img src="../images/excalibur.gif" border="0"/></a>
		</td>
		<td>
			<a href="javascript:jumpTo('<?=$pornstar_name ?>','goliath')"><img src="../images/gol.gif" border="0" alt="Search Goliath Films for <?=$pornstar_name ?>"/></a>
		</td>
		<td>
			<a href="javascript:jumpTo('<?=$pornstar_name ?>','searchextreme')"><img src="../images/extreme.gif" border="0" alt="Search searchextreme.com for <?=$pornstar_name ?>"/></a>
		</td>
		<td>
			<a href="javascript:jumpTo('<?=$pornstar_name ?>','eurobabe')"><img src="../images/eurobabe.gif" border="0" alt="Search eurobabeindex.com for <?=$pornstar_name ?>"/></a>
		</td>
		<td>
			<a href="javascript:changePornstar(<?=$pornstar_id ?>)">[<?=$language->show('X_CHANGE')?>]</a>
		</td>
		<td>
			&nbsp;&nbsp;<a href="#" onClick="del_actor(<?=$pornstar_id ?>,<?=$movie_id?>)">[<?=$language->show('X_DELETE')?>]</a>
		</td>
		<?

}

/**
 * Enter description here...
 *
 * @return unknown
 */
function 	getCategoryMapping() {
	$mapping = array(
		'Action' 		=> 'CAT_ACTION',
		'Adult' 		=> 'CAT_ADULT',
		'Adventure' 	=> 'CAT_ADVENTURE',
		'Animation' 	=> 'CAT_ANIMATION',
		'Anime / Manga' => 'CAT_ANIME',
		'Comedy' 		=> 'CAT_COMEDY',
		'Crime' 		=> 'CAT_CRIME',
		'Documentary' 	=> 'CAT_DOCUMENTARY',
		'Drama' 		=> 'CAT_DRAMA',
		'Family' 		=> 'CAT_FAMILY',
		'Fantasy' 		=> 'CAT_FANTASY',
		'Film-Noir' 	=> 'CAT_FILMNOIR',
		'Horror' 		=> 'CAT_HORROR',
		'James Bond' 	=> 'CAT_JAMESBOND',
		'Music Video' 	=> 'CAT_MUSICVIDEO',
		'Musical' 		=> 'CAT_MUSICAL',
		'Mystery' 		=> 'CAT_MYSTERY',
		'Romance' 		=> 'CAT_ROMANCE',
		'Sci-Fi' 		=> 'CAT_SCIFI',
		'Short' 		=> 'CAT_SHORT',
		'Thriller' 		=> 'CAT_THRILLER',
		'Tv Shows' 		=> 'CAT_TVSHOWS',
		'War' 			=> 'CAT_WAR',
		'Western' 		=> 'CAT_WESTERN',
		'X-Rated' 		=> 'CAT_XRATED'
	);
	return $mapping;
}


/**
 * Enter description here...
 *
 * @param unknown $strList
 * @return unknown
 */
function parseCategoryList($strList) {

	global $language;

	$SETTINGSClass = VCDClassFactory::getInstance('vcd_settings');
	$categories = $SETTINGSClass->getAllMovieCategories();
	$mapping = getCategoryMapping();
	$inArr = explode(", ", $strList);

	$strResult = "";
	foreach ($inArr as $cat) {

		$cat_id = $SETTINGSClass->getCategoryIDByName($cat, true);
		$cat_name = $cat;
		if (is_numeric($cat_id) && $cat_id != 0) {
			$catObj = $SETTINGSClass->getMovieCategoryByID($cat_id);
			$cat_name = $catObj->getName(true);
		}

		if ($cat_id != 0) {
			$strResult .= " <a href=\"./?page=category&amp;category_id=".$cat_id."\">".$cat_name."</a>,";
		} else {
			$strResult .= " ". $cat_name . ",";
		}

	}

	return substr($strResult, 0, (strlen($strResult)-1));


}


/**
 * Enter description here...
 *
 * @return unknown
 */
function server_url()
{
   $proto = "http" . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "s" : "") . "://";
   $server = isset($_SERVER['HTTP_HOST']) ?  $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];

   return $proto . $server;
}


// Default redirection to home
/**
 * Enter description here...
 *
 * @param unknown $relative_url
 */
function redirect($relative_url = '.?')
{
   $url = server_url() . dirname($_SERVER['PHP_SELF']);
	if (!strcmp(dirname($_SERVER['PHP_SELF']), "/") == 0) {
		$url .= "/";
	}
   $url .= $relative_url;
      
   if (!headers_sent())
   {
       header("Location: $url");
       exit();
   }
   else
   {
       print "<script>location.href='".$url."'</script>";
       exit();
   }
}



/**
 * Enter description here...
 *
 */
function inc_tooltipjs() {

	global $CURRENT_PAGE;
	if ($CURRENT_PAGE == 'pornstar' ) {
		?>
		<script src="includes/js/dw_event.js" type="text/javascript"></script>
		<script src="includes/js/dw_viewport.js" type="text/javascript"></script>
		<script src="includes/js/dw_tooltip.js" type="text/javascript"></script>
		<?
	}

	if ($CURRENT_PAGE == '' || $CURRENT_PAGE == 'cd') {
	// Frontpage ..
		?>
		<script language="JavaScript" type="text/javascript" src="includes/js/wz_tooltip.js"></script>
		<?

	}




}

/**
 * Enter description here...
 *
 * @return unknown
 */
function rightbar() {
	global $CURRENT_PAGE;
	$subaction = "";
	if (isset($_GET['o'])) {
		$subaction = $_GET['o'];
	}

	if ($CURRENT_PAGE == '') {
		// Check if user is logged in and wished to disable sidebar
		if (VCDUtils::isLoggedIn()) {

			$SETTINGSClass = VCDClassFactory::getInstance('vcd_settings');
			$arr = $SETTINGSClass->getMetadata(0, VCDUtils::getUserID(), 'frontbar');
			if (is_array($arr) && sizeof($arr) == 1 && $arr[0] instanceof metadataObj && $arr[0]->getMetadataValue() == 0) {
				return false;
			}
		}
		return true;

	} else {
		return false;
	}
}


/**
 * Enter description here...
 *
 * @param unknown $arr
 * @param unknown $start
 * @param unknown $pageCount
 * @return unknown
 */
function createObjFilter(&$arr, $start, $pageCount) {
	$newarr = array();
	for ($i = 0; $i < sizeof($arr); $i++) {
		if ($i >= $start && $i < ($start+$pageCount)) {
			array_push($newarr, $arr[$i]);
		}

		if ($i > ($start+$pageCount)) {
			break;
		}
	}

	return $newarr;
}

/**
 * Enter description here...
 *
 * @param unknown $multiArray
 * @param unknown $secondIndex
 * @return unknown
 */
function aSortBySecondIndex($multiArray, $secondIndex) {
		   while (list($firstIndex, ) = each($multiArray))
		       $indexMap[$firstIndex] = $multiArray[$firstIndex][$secondIndex];
		   asort($indexMap);
		   while (list($firstIndex, ) = each($indexMap))
		       if (is_numeric($firstIndex))
		           $sortedArray[] = $multiArray[$firstIndex];
		       else $sortedArray[$firstIndex] = $multiArray[$firstIndex];
		   return $sortedArray;
}

/**
 * Enter description here...
 *
 * @param unknown $strPlot
 */
function showPlot($strPlot) {

	global $language;

	$showLen = 280;
	$plot = ereg_replace(13,"<br/>",$strPlot);
	$len = strlen($plot);
	if ($len > $showLen) {
		$first = substr($plot, 0, $showLen);
		print "<div style=\"padding-right:20px\" id=\"first\">".$first." ...<br/>&nbsp;&nbsp;<a href=\"#plot\" onclick=\"hide('first');show('rest')\">".$language->show('X_SHOWMORE')." &gt;&gt;</a></div>";
		print "<div id=\"rest\" style=\"visibility:hidden;display:none;\">".$plot."
				<br/>&nbsp;&nbsp;<a href=\"#plot\" onclick=\"hide('rest');show('first')\">&lt;&lt; ".$language->show('X_SHOWLESS')."</a>";

	} else {
		print $plot;
	}



}

/**
 * Enter description here...
 *
 * @param unknown $str
 * @return unknown
 */
function fixFormat($str) {
	$len = 10;
	$cats = explode(",",$str);
	asort($cats);
	if (sizeof($cats) == 1) {
		return $cats[0];
	}
	$catstr = $str;
	if (strlen($catstr) > $len) {
		return "<span title=\"".$str."\">".$cats[0].", ...</span>";
	} else {
		return implode(",", $cats);
	}
}


/**
 * Enter description here...
 *
 * @return unknown
 */
function checkInstall() {
	return (is_dir('setup'));
}


/**
 * Enter description here...
 *
 * @param unknown $arrMovies
 * @param unknown $arrLoans
 * @return unknown
 */
function filterLoanList($arrMovies, $arrLoans) {

	// create array with movie id's that are in loan ..
	$loanIds = array();
	foreach ($arrLoans as $loanObj) {
		array_push($loanIds, $loanObj->getCDID());
	}

	$arrAvailable = array();
	foreach ($arrMovies as $vcdObj) {
		if (!in_array($vcdObj->getId(), $loanIds)) {
			array_push($arrAvailable, $vcdObj);
		}
	}

	unset($loanIds);
	return $arrAvailable;


}

/**
 * Enter description here...
 *
 * @param unknown $url
 * @param unknown $showdescription
 */
function ShowOneRSS($url, $showdescription = false) {


	$maxtitlelen = 44;
	$rss = VCDClassFactory::getInstance('lastRSS');
	$rss->cache_dir = CACHE_FOLDER;
	$rss->cache_time = RSS_CACHE_TIME;
	$rss->cp = VCDUtils::getCharSet();

    if ($rs = $rss->get($url)) {
  	    	
    	if ($rs['items_count'] <= 0) {  return; }
    	
    	$title = $rs['title'];
    	if (strlen($title) > $maxtitlelen) {
    		$title = VCDUtils::shortenText($title, $maxtitlelen);
    	}


        echo "<h1><em><a href=\" ".$rs['link']."\" title=\"".$rs['title']."\">".$title."</a></em></h1>\n";
        if ($showdescription)
        	echo $rs['description']."<br/>\n";

            echo "<ul>\n";
            foreach ($rs['items'] as $item) {

            	  $onmouseover= "";
            	  if (isset($item['description'])) {


            	  	$hovertext = str_replace("&#039;", "", $item['description']);
            	  	$hovertext = str_replace("'", "", $hovertext);
            	  	$hovertext = str_replace("\"", "", $hovertext);

            	  	$hovertext = str_replace("&apos;", "", $hovertext);
            	  	$hovertext = str_replace(chr(13), "", $hovertext);
            	  	$hovertext = str_replace(chr(10), "", $hovertext);

            	  	$onmouseover = "onmouseover=\"this.T_SHADOWWIDTH=1;this.T_STICKY=1;this.T_OFFSETX=-70;this.T_WIDTH=250;return escape('{$hovertext}')\"";
            	  }

					// Fix so the long titles will not fuck up the layout
					$item['title'] = str_replace("."," ", $item['title']);

	              echo "\t<li><a href=\"".str_replace('<![CDATA[&]]>', '&amp;', $item['link'])."\" target=\"_new\" {$onmouseover}>".unhtmlentities(str_replace("&apos;", "", $item['title']))."</a></li>\n";
            }
            echo "</ul>\n";
    }
}


/**
 * Enter description here...
 *
 * @param unknown $string
 * @return unknown
 */
function unhtmlentities ($string)
{
	$trans_tbl = get_html_translation_table (HTML_ENTITIES);
	$trans_tbl = array_flip ($trans_tbl);
	return strtr ($string, $trans_tbl);
}



/**
 * Enter description here...
 *
 * @param unknown $show_logo
 * @param unknown $width
 * @param unknown $style
 */
function printStatistics($show_logo = true, $width = "230", $style = "statsTable") {

	$SETTINGSClass = VCDClassFactory::getInstance('vcd_settings');
	$statObj = $SETTINGSClass->getStatsObj();

	global $language;

	if (strcmp($style, "statsTable") == 0) {
		$header = "stata";
	?>
	<h3 align="center">
	<? if ($show_logo) { ?>
	<img src="images/logotest.gif" width="187" align="middle" height="118" alt="" border="0"/>
	<br/>
	<? } ?>
	<?=$language->show('STAT_TITLE');?>
	</h3>
	<? }  else {$header = "header";} ?>
	<div align="center">
	<table cellspacing="1" cellpadding="1" border="0" class="<?=$style?>" style="width:<?=$width?>px">
	<tr>
		<td class="<?=$header?>" colspan="2"><?=$language->show('STAT_TOP_MOVIES')?></td>
	</tr>
	<tr>
		<td align="left"><?=$language->show('STAT_TOTAL')?></td>
		<td align="right"><?=$statObj->getMovieCount()?></td>
	</tr>
	<tr>
		<td align="left"><?=$language->show('STAT_TODAY')?></td>
		<td align="right"><?=$statObj->getMovieTodayCount()?></td>
	</tr>
	<tr>
		<td align="left"><?=$language->show('STAT_WEEK')?></td>
		<td align="right"><?=$statObj->getMovieWeeklyCount()?></td>
	</tr>
	<tr>
		<td align="left"><?=$language->show('STAT_MONTH')?></td>
		<td align="right"><?=$statObj->getMovieMonthlyCount()?></td>
	</tr>

	<tr>
		<td class="<?=$header?>" colspan="2"><?=$language->show('STAT_TOP_CATS')?></td>
	</tr>
	<?
		foreach ($statObj->getBiggestCats() as $catObj) {
			print "<tr>";
				print "<td align=\"left\"><a href=\"./?page=category&amp;category_id=".$catObj->getID()."\">".$catObj->getName(true)."</a></td>";
				print "<td align=\"right\">".$catObj->getCategoryCount()."</td>";
			print "</tr>";
		}
	?>

	<tr>
		<td class="<?=$header?>" colspan="2"><?=$language->show('STAT_TOP_ACT')?></td>
	</tr>
	<?
		foreach ($statObj->getBiggestMonhtlyCats() as $catObj) {
			print "<tr>";
				print "<td align=\"left\"><a href=\"./?page=category&amp;category_id=".$catObj->getID()."\">".$catObj->getName(true)."</a></td>";
				print "<td align=\"right\">".$catObj->getCategoryCount()."</td>";
			print "</tr>";
		}
	?>

	<tr>
		<td colspan="2" class="<?=$header?>"><?=$language->show('STAT_TOP_COVERS')?></td>
	</tr>
	<tr>
		<td align="left"><?=$language->show('STAT_TOTAL')?></td>
		<td align="right"><?=$statObj->getTotalCoverCount()?></td>
	</tr>
	<tr>
		<td align="left"><?=$language->show('STAT_WEEK')?></td>
		<td align="right"><?=$statObj->getWeeklyCoverCount()?></td>
	</tr>
	<tr>
		<td align="left"><?=$language->show('STAT_MONTH')?></td>
		<td align="right"><?=$statObj->getMonthlyCoverCount()?></td>
	</tr>
	</table>
	</div>

	<?

}


/**
 * Get the play command for specified movie.
 *
 * Returns true if all presetiquites as met for playing the movie
 * and movie file location has been saved.  Otherwise returns false.
 * Param $playcommand will then contain the command for playing the movie.
 *
 * @param vcdObj $vcd_id
 * @param int $user_id
 * @param string $playcommand
 * @param metadataObj $metaObj | The metadata Object
 */
function getPlayCommand($vcdObj, $user_id, &$playcommand, $metaObj = null) {
	if (VCDUtils::isLoggedIn() && VCDUtils::isOwner($vcdObj) && $_SESSION['user']->getPropertyByKey('PLAYOPTION')) {

		$SETTINGSClass = VCDClassFactory::getInstance('vcd_settings');

		$player = "";
		$playerparams = "";
		$filename = "";

		// check for filename
		if ($metaObj instanceof metadataObj ) {
			$filename = $metaObj->getMetaDataValue();
		} else {
			$fileArr = $SETTINGSClass->getMetadata($vcdObj->getID(), $user_id, metadataTypeObj::SYS_FILELOCATION );
			if (is_array($fileArr) && sizeof($fileArr) == 1 && $fileArr[0] instanceof metadataObj) {
				$filename = $fileArr[0]->getMetaDataValue();
			}
		}



		// check for player settings
		$arr = $SETTINGSClass->getMetadata(0, $user_id, 'player');
		if (is_array($arr) && sizeof($arr) == 1 && $arr[0] instanceof metadataObj) {
			$player = $arr[0]->getMetaDataValue();
		}
		$arr = $SETTINGSClass->getMetadata(0, $user_id, 'playerpath');
		if (is_array($arr) && sizeof($arr) == 1 && $arr[0] instanceof metadataObj) {
			$playerparams = $arr[0]->getMetaDataValue();
		}

		if (strcmp($player, "") !=0 && strcmp($filename, "") != 0) {
			$playcommand = "|".$player . "| |" . $filename . "| " . $playerparams;
			$playcommand = str_replace('\\','#', $playcommand);
			return true;
		} else {
			return false;
		}


	}

	return false;
}

/**
 * Enter description here...
 *
 * @param unknown $vcdObj
 * @param unknown $user_id
 * @param unknown $playcommand
 * @return unknown
 */
function getPublicPlayCommand($vcdObj, $user_id, &$playcommand) {

		$SETTINGSClass = VCDClassFactory::getInstance('vcd_settings');

		$player = "";
		$playerparams = "";
		$filename = "";

		// check for filename
		$fileArr = $SETTINGSClass->getMetadata($vcdObj->getID(), $user_id, 'filelocation');
		if (is_array($fileArr) && sizeof($fileArr) == 1 && $fileArr[0] instanceof metadataObj) {
			$filename = $fileArr[0]->getMetaDataValue();
		}


		// check for player settings
		$arr = $SETTINGSClass->getMetadata(0, $user_id, 'player');
		if (is_array($arr) && sizeof($arr) == 1 && $arr[0] instanceof metadataObj) {
			$player = $arr[0]->getMetaDataValue();
		}
		$arr = $SETTINGSClass->getMetadata(0, $user_id, 'playerpath');
		if (is_array($arr) && sizeof($arr) == 1 && $arr[0] instanceof metadataObj) {
			$playerparams = $arr[0]->getMetaDataValue();
		}

		if (strcmp($player, "") !=0 && strcmp($filename, "") != 0) {
			$playcommand = $player . " " . $filename . " " . $playerparams;
			$playcommand = str_replace('\\','#', $playcommand);
			return true;
		} else {
			return false;
		}


	return false;
}




/**
 * Enter description here...
 *
 * @param unknown $categoryObjArr
 * @return unknown
 */
function getLocalizedCategories($categoryObjArr = null) {
	global $language;

	$SETTINGSClass = VCDClassFactory::getInstance('vcd_settings');


	if ($categoryObjArr == null) {
		$categoryObjArr = $SETTINGSClass->getAllMovieCategories();
	}


	// Translate category names
	$mapping = getCategoryMapping();
	$altLang = $language->isUsingDefault();
	// Create translated category array
	$arrCategories = array();
	foreach ($categoryObjArr as $categoryObj) {
		$arr = array("id" => $categoryObj->getID(), "name" => $categoryObj->getName(true));
		array_push($arrCategories, $arr);
	}
	$arrCategories = aSortBySecondIndex($arrCategories, 'name');
	return $arrCategories;

}


// Check if mod_rewrite is enabled and call for page parsing on contents if it is.
/**
 * Enter description here...
 *
 */
function start_mrw() {
	if (defined("MOD_REWRITE") && (strcmp(MOD_REWRITE, "1") == 0)) {
		ob_start("doRewrite");
	}
}

// Flush buffer if mod_rewrite is enabled
/**
 * Enter description here...
 *
 */
function end_mrw() {
	if (defined("MOD_REWRITE") && (strcmp(MOD_REWRITE, "1") == 0)) {
		ob_end_flush();
	}
}

/**
 * Enter description here...
 *
 * @param unknown $buffer
 * @return unknown
 */
function doRewrite($buffer) {

  $root = substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], "index.php"));

  $arrDefault = array(
  		//STYLE."style.css",
    	"./?page=category&amp;category_id=",
    	"./?page=adultcategory&amp;category_id=",
    	"./?page=adultcategory&amp;studio_id=",
    	"./?page=cd&amp;vcd_id=",
    	"./?page=pornstar&amp;pornstar_id=",
    	"images/",
    	"upload",
    	"includes",
    	"./?"


  );

  $arrRewrite = array(
  		//$root.STYLE."style.css",
  		$root."cat/",
  		$root."xcat/",
  		$root."studio/",
  		$root."cd/",
  		$root."pornstar/",
  		$root."images/",
  		$root."upload",
  		$root."includes",
  		$root."?",
  );

  return str_replace($arrDefault, $arrRewrite, $buffer);
}


function human_file_size($size)
{
   if (is_numeric($size) && $size > 0) {
   		$filesizename = array(" Bytes", " kb", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
   		return round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $filesizename[$i];
   } else {
   		return "0 Bytes";
   }
}

function send_file($path) {
   session_write_close();
   @ob_end_clean();
   if (!is_file($path) || connection_status()!=0)
       return(FALSE);

   //to prevent long file from getting cut off from    //max_execution_time

   set_time_limit(0);

   $name=basename($path);

   //filenames in IE containing dots will screw up the
   //filename unless we add this

   if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE"))
       $name = preg_replace('/\./', '%2e', $name, substr_count($name, '.') - 1);

   //required, or it might try to send the serving    //document instead of the file

   header("Cache-Control: ");
   header("Pragma: ");
   header("Content-Type: application/octet-stream");
   header("Content-Length: " .(string)(filesize($path)) );
   header('Content-Disposition: attachment; filename="'.$name.'"');
   header("Content-Transfer-Encoding: binary\n");

   if($file = fopen($path, 'rb')){
       while( (!feof($file)) && (connection_status()==0) ){
           print(fread($file, 1024*8));
           flush();
       }
       fclose($file);
   }
   return((connection_status()==0) and !connection_aborted());
}


function createDVDDropdown($arrMediaTypes, $selectedIndex = null) {
	try {

		if (sizeof($arrMediaTypes) == 1 && $arrMediaTypes[0] instanceof mediaTypeObj ) {
			print $arrMediaTypes[0]->getDetailedName();
		}

		else if (is_array($arrMediaTypes)) {
			$arrDVDTypes = array();
			foreach ($arrMediaTypes as $mediatypeObj) {
				if (VCDUtils::isDVDType(array($mediatypeObj))) {
					array_push($arrDVDTypes, $mediatypeObj);
				}
			}

			if (sizeof($arrDVDTypes) == 1) {
				print $arrDVDTypes[0]->getDetailedName();
			} else {
				print "<select name=\"dvdtype\" class=\"input\" onchange=\"doManagerSubmit(this)\">";
				foreach ($arrDVDTypes as $mediatypeObj) {
					if ($selectedIndex === $mediatypeObj->getmediaTypeID()) {
						print "<option value=\"{$mediatypeObj->getmediaTypeID()}\" selected=\"selected\">{$mediatypeObj->getDetailedName()}</option>";
					} else {
						print "<option value=\"{$mediatypeObj->getmediaTypeID()}\">{$mediatypeObj->getDetailedName()}</option>";
					}

				}
				print "</select>";
			}

		}


	} catch (Exception $ex) {
		VCDException::display($ex);
	}
}

function drawDVDLayers(vcdObj &$vcdObj, &$metadataArr) {

	global $language;

	// First get all available owners and mediatypes
	$arrData = $vcdObj->getInstanceArray();
	if (isset($arrData['owners']) && isset($arrData['mediatypes'])) {

		$arrOwners = $arrData['owners'];
		$arrMediatypes = $arrData['mediatypes'];
		$i = 0;


		foreach ($arrMediatypes as $mediaTypeObj) {

			if ($mediaTypeObj instanceof mediaTypeObj && VCDUtils::isDVDType(array($mediaTypeObj))) {

				$arrDVDMeta = metadataTypeObj::filterByMediaTypeID($metadataArr, $mediaTypeObj->getmediaTypeID(), $arrOwners[$i]->getUserId());
				$arrDVDMeta = metadataTypeObj::getDVDMeta($arrDVDMeta);

				if (is_array($arrDVDMeta) && sizeof($arrDVDMeta) > 0) {

					$dvdObj = new dvdObj();

					$dvd_region = VCDUtils::getDVDMetaObjValue($arrDVDMeta, metadataTypeObj::SYS_DVDREGION);
					$dvd_format = VCDUtils::getDVDMetaObjValue($arrDVDMeta, metadataTypeObj::SYS_DVDFORMAT);
					$dvd_aspect = VCDUtils::getDVDMetaObjValue($arrDVDMeta, metadataTypeObj::SYS_DVDASPECT);
					$dvd_audio = VCDUtils::getDVDMetaObjValue($arrDVDMeta, metadataTypeObj::SYS_DVDAUDIO);
					$dvd_subs = VCDUtils::getDVDMetaObjValue($arrDVDMeta, metadataTypeObj::SYS_DVDSUBS);

					if (strcmp($dvd_region, "") != 0) {
						//$dvd_region = $dvd_region.". (". $dvdObj->getRegion($dvd_region) . ")";
						$dvd_region = $dvd_region. ".";
					}

					if (strcmp($dvd_aspect, "") != 0) {
						$dvd_aspect = $dvdObj->getAspectRatio($dvd_aspect);
					}

					if (strcmp($dvd_audio, "") != 0) {
						$arrAudio = explode("#", $dvd_audio);
						$dvd_audio = "<ul class=\"ulnorm\">";
						foreach ($arrAudio as $audioType) {
							$dvd_audio .= "<li class=\"linorm\">" . $dvdObj->getAudio($audioType) . "</li>";
						}
						$dvd_audio .= "</ul>";
					}

					if (strcmp($dvd_subs, "") != 0) {
						$arrSubs = explode("#", $dvd_subs);
						$dvd_subs = "<ul class=\"ulnorm\">";
						foreach ($arrSubs as $subTitle) {
							$imgsource = $dvdObj->getCountryFlag($subTitle);
							$langName = $dvdObj->getLanguage($subTitle);
							$img = "<img src=\"{$imgsource}\" alt=\"{$langName}\" hspace=\"1\"/>";
							$dvd_subs .= "<li class=\"linorm\">".$img . " " . $langName . "</li>";
						}
						$dvd_subs .= "</ul>";
					}

					$divid = "x". $mediaTypeObj->getmediaTypeID()."x".$arrOwners[$i]->getUserId();
					print "<div id=\"{$divid}\" class=\"dvdetails\">";
					?>
					<table width="280" cellpadding="1" cellspacing="1" border="0" class="dvdspecs">
					<tr>
						<td nowrap="nowrap" width="15%"><?= $language->show('DVD_REGION')?>:</td>
						<td><?= $dvd_region ?></td>
					</tr>
					<tr>
						<td nowrap="nowrap"><?= $language->show('DVD_FORMAT')?>:</td>
						<td><?= $dvd_format ?></td>
					</tr>
					<tr>
						<td nowrap="nowrap"><?= $language->show('DVD_ASPECT')?>:</td>
						<td><?= $dvd_aspect?></td>
					</tr>
					<tr>
						<td nowrap="nowrap" valign="top"><?= $language->show('DVD_AUDIO')?>:</td>
						<td valign="top"><?= $dvd_audio ?></td>
					</tr>
					<tr>
						<td nowrap="nowrap" valign="top"><?= $language->show('DVD_SUBTITLES')?>:</td>
						<td valign="top"><?= $dvd_subs ?></td>
					</tr>
					</table>
					<?
					print "</div>";

				}
			}
			$i++;
		}


	} else {
		return;
	}

}

function showDVDSpecs(userObj $userObj, mediaTypeObj $mediaTypeObj, &$metaDataArr = null) {

	$divid = "";
	$arrDVDMeta = null;
	if (!is_null($metaDataArr)) {
		$arrDVDMeta = metadataTypeObj::filterByMediaTypeID($metaDataArr, $mediaTypeObj->getmediaTypeID(), $userObj->getUserID());
		$arrDVDMeta = metadataTypeObj::getDVDMeta($arrDVDMeta);
		$divid = "x".$mediaTypeObj->getmediaTypeID() ."x". $userObj->getUserId();
	}
	$dhtml = "this.T_SHADOWWIDTH=1;this.T_STICKY=1;this.T_ABOVE=true;this.T_LEFT=false; this.T_WIDTH=284;";
	$img = "<img src=\"images/icon_item.gif\" onmouseover=\"{$dhtml}return escape(showDVD('{$divid}'))\" border=\"0\" hspace=\"1\" alt=\"\" align=\"middle\"/>";

	if (VCDUtils::isDVDType(array($mediaTypeObj)) && !is_null($arrDVDMeta) && sizeof($arrDVDMeta) > 0) {
		return $img;
	} else {
		return "&nbsp;";
	}
}


function showNFO(userObj $userObj, mediaTypeObj $mediaTypeObj, &$metaDataArr = null) {

	$hasNFO = false;
	if (!is_null($metaDataArr)) {
		$currMeta = metadataTypeObj::filterByMediaTypeID($metaDataArr, $mediaTypeObj->getmediaTypeID(), $userObj->getUserID());
		// Search for NFO metadata ..
		if (is_array($currMeta) && sizeof($currMeta) > 0) {
			foreach ($currMeta as $metadataObj) {
				if ($metadataObj->getMetadataTypeID() == metadataTypeObj::SYS_NFO) {
					$nfofile = NFO_PATH . $metadataObj->getMetaDataValue();
					$js = "window.open('{$nfofile}');";
					$img = "<a href=\"#\" onclick=\"{$js};\"><img src=\"images/icon_nfo.gif\" border=\"0\" hspace=\"1\" alt=\"NFO\" align=\"middle\"/></a>";
					$hasNFO = true;
					break;
				}
			}
		}
	}


	if ($hasNFO) {
		return $img;
	} else {
		return "&nbsp;";
	}
}


function getLocalizedCategoryName($category_name) {

	$baseMap = getCategoryMapping();
	if (key_exists($category_name, $baseMap)) {

		$baseKey = $baseMap[$category_name];
		global $language;
		$translatedKey = $language->show($baseKey);
		$notfound = "undefined";
		if (strcmp($translatedKey, strtolower($notfound)) == 0) {
			return $category_name;
		} else {
			return $translatedKey;
		}

	} else {
		return $category_name;
	}
}

function drawGraph($instructions) {
	$qs = base64_decode($instructions);
	$qs = utf8_decode(urldecode($qs));
	$PG = new PowerGraphic($qs);
	$PG->drawimg = false;
	$PG->start();

	$obj = $PG->create_graphic();
	header('Content-type: image/png');
	imagepng($obj);
	imagedestroy($obj);
	exit();
}

function display_fetchsites() {
	
	$arrFetchableSites = getFetchClasses(VCDUtils::showAdultContent());
	
	if (sizeof($arrFetchableSites) == 0) {
		VCDException::display("No Fetchclasses available.<break>Enable some fetch classes from the Control Panel.");
		return;
	}
	
	// Check for the last used fetch class and make it default if we find one ..
	$SettingsClass = VCDClassFactory::getInstance('vcd_settings');
	$metaDefaultArr = $SettingsClass->getMetadata(0,VCDUtils::getUserID(), metadataTypeObj::SYS_LASTFETCH);
	$defaultClassName = "";
	if (is_array($metaDefaultArr) && sizeof($metaDefaultArr) > 0 && $metaDefaultArr[0] instanceof metadataObj ) {
		$defaultClassName = $metaDefaultArr[0]->getMetadataValue();
	}
	
	
	$html = "<select name=\"fetchsite\">";
	foreach ($arrFetchableSites as $sourceSiteObj) {
		$selected = "";
		if (strcmp(strtolower($sourceSiteObj->getAlias()), strtolower($defaultClassName)) == 0) {
			$selected = " selected=\"selected\"";
		}
		$html .= "<option value=\"".$sourceSiteObj->getAlias()."\"{$selected}>".$sourceSiteObj->getName()."</option>";
	}
	$html .= "</select>";
	
	print $html;
	
}


function getFetchClasses($bShowAdult = false) {
	
	$SettingsClass = VCDClassFactory::getInstance("vcd_settings");
	$arrSourceSites = $SettingsClass->getSourceSites();
	$arrSourceList = array();
	foreach ($arrSourceSites as $siteObj) {

	   if ($siteObj->isFetchable() && strcmp($siteObj->getClassName(), "") != 0) {
	           
            // Try to instanceate the class ..
            $className = $siteObj->getClassName();
            if (!class_exists($className)) {
                   
                    // Check if the $VCDClassfactory can load the class.                    
                    if (!is_null(VCDClassFactory::loadClass($className))) {
                            // Create instance and check class status
                            $fetchClass = new $className;
                            $adultStatus = $fetchClass->isAdultSite();
                            if ((!$adultStatus) || ($adultStatus && $bShowAdult)) {
                                    array_push($arrSourceList, $siteObj);
                            }
                    }
            }
	    }
	}
	return $arrSourceList;
       
} 


function drawSourceSiteLogo($sourceSiteID, $external_id) {
	if (is_numeric($sourceSiteID) && strcmp($external_id,"") != 0) {
		$SettingsClass = VCDClassFactory::getInstance('vcd_settings');
		$SourceSiteObj = $SettingsClass->getSourceSiteByID($sourceSiteID);	
		if ($SourceSiteObj instanceof sourceSiteObj ) {
			$image = "images/logos/".$SourceSiteObj->getImage();
			$link = str_replace("#", $external_id, $SourceSiteObj->getCommand());
			$html = "<a href=\"%s\" target=\"_new\"><img src=\"%s\" border=\"0\"/></a>";
			$imgstring = sprintf($html, $link, $image);
			return $imgstring;
		}
	}
	
}


?>
