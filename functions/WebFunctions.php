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
 * Redirect the browser to a specific url.
 *
 * @param string $relative_url | The interlan url to redirect to
 */
function redirect($relative_url = '?') {
   
	$url = str_replace(basename($_SERVER['PHP_SELF']),'',
	    sprintf('http%s://%s%s',(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 's': ''),
    	$_SERVER['HTTP_HOST'], $_SERVER['PHP_SELF']));
	
   $url .= $relative_url;
      
   if (!headers_sent()) {
       header("Location: $url");
       exit();
   } else {
       print "<script>location.href='".$url."'</script>";
       exit();
   }
}


/**
 * Sort an array by the a specific index
 *
 * @param array $multiArray | The array to sort
 * @param mixed $secondIndex | The sort index
 * @return array | The sorted array
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
 * Get the category list localized and sorted
 *
 * @param array $categoryObjArr | Array of moviecategoryObj
 * @return array | The localized and sorted array
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


/**
 * Get filesize in human readable form
 *
 * @param float $size
 * @return string | The formatted filesize string
 */
function human_file_size($size) {
   if (is_numeric($size) && $size > 0) {
   		$filesizename = array(" Bytes", " kb", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
   		return round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $filesizename[$i];
   } else {
   		return '0 Bytes';
   }
}

 
/**
 * Translate a category name to the currently used language.
 *
 * @param string $category_name | The category name in english
 * @return string | The category name in the currently used language
 */
function getLocalizedCategoryName($category_name) {
	$baseMap = getCategoryMapping();
	if (key_exists($category_name, $baseMap)) {
		$baseKey = $baseMap[$category_name];
		$translatedKey = VCDLanguage::translate($baseKey);
		$notfound = 'undefined';
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