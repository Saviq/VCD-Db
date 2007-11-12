<?php
/**
 * VCD-db - a web based VCD/DVD Catalog system
 * Copyright (C) 2003-2006 Konni - konni.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * @author  Hákon Birgisson <konni@konni.com>
 * @package Functions
 * @subpackage Web
 * @version $Id$
 */
?>
<?php

/**
 * Draw the IMDB links for the movie page
 *
 * @param string $imdb_id | The IMDB ID
 */
function display_imdbLinks($imdb_id) {

	print "<h2>".VCDLanguage::translate('imdb.links')."</h2>";
	print "<ul>";
	print "<li><a href=\"http://www.imdb.com/Title?".$imdb_id."\" target=\"new\">".VCDLanguage::translate('imdb.details')."</a></li>";
	print "<li><a href=\"http://www.imdb.com/Plot?".$imdb_id."\" target=\"new\">".VCDLanguage::translate('imdb.plot')."</a></li>";
	print "<li><a href=\"http://www.imdb.com/Gallery?".$imdb_id."\" target=\"new\">".VCDLanguage::translate('imdb.gallery')."</a></li>";
	print "<li><a href=\"http://www.imdb.com/Trailers?".$imdb_id."\" target=\"new\">".VCDLanguage::translate('imdb.trailers')."</a></li>";
	print "</ul>";
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
function redirect($relative_url = '?')
{
   
	$url = str_replace(basename($_SERVER['PHP_SELF']),'',
	    sprintf('http%s://%s%s',(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 's': ''),
    	$_SERVER['HTTP_HOST'], $_SERVER['PHP_SELF']));
	
   $url .= $relative_url;
      
   if (!headers_sent())
   {
       header("Location: $url");
       exit();
   } else {
       print "<script>location.href='".$url."'</script>";
       exit();
   }
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
 * @param unknown $categoryObjArr
 * @return unknown
 */
function getLocalizedCategories($categoryObjArr = null) {
	
	if ($categoryObjArr == null) {
		$categoryObjArr = SettingsServices::getAllMovieCategories();
	}


	// Translate category names
	$mapping = VCDUtils::getCategoryMapping();
	// Create translated category array
	$arrCategories = array();
	foreach ($categoryObjArr as $categoryObj) {
		$arr = array("id" => $categoryObj->getID(), "name" => $categoryObj->getName(true));
		array_push($arrCategories, $arr);
	}
	$arrCategories = aSortBySecondIndex($arrCategories, 'name');
	return $arrCategories;
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

 
function getLocalizedCategoryName($category_name) {

	$baseMap = getCategoryMapping();
	if (key_exists($category_name, $baseMap)) {

		$baseKey = $baseMap[$category_name];
		$translatedKey = VCDLanguage::translate($baseKey);
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



?>
