<?php
/**
 * VCD-db - a web based VCD/DVD Catalog system
 * Copyright (C) 2003-2007 Konni - konni.com
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * @author  Hákon Birgisson <konni@konni.com>
 * @package Kernel
 * @version $Id: VCDPageUserStatistics.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php

class VCDPageUserStatistics extends VCDBasePage  {
	
	public function __construct(_VCDPageNode $node) {
		
		parent::__construct($node);
		
		if ($this->getParam('view') == 'gfx') {
			$this->doGraphs();
		} else {
			$this->doTables();
		}
		
		
	}
	
	private function doGraphs() {
		$this->assign('showgraphs',true);
		
		require_once(VCDDB_BASE.DIRECTORY_SEPARATOR.'classes/external/powergraph.php');
		 
		$skin = 1;
		$type = 5;
		
		$PG = new PowerGraphic();
		$PG->title = VCDLanguage::translate('movie.category');
		$PG->axis_x = VCDLanguage::translate('movie.category');
		$PG->axis_y = "Nr";
		$PG->skin = $skin;
		$PG->type  = $type;
				
		
		$arrStats = SettingsServices::getUserStatistics(VCDUtils::getUserID());
		$arrCats = $arrStats['category'];

		$i = 0;
		foreach ($arrCats as $subArr) {
				$category = SettingsServices::getMovieCategoryByID($subArr[0])->getName(true);
				$num = $subArr[1];
				$PG->x[$i] = $category;
				$PG->y[$i] = $num;		
				$i++;
		}
		$qs = base64_encode($PG->create_query_string());
		$this->assign('graph1', $qs);
		
				
		$PG->reset_values();
		$PG->title = VCDLanguage::translate('movie.media');
		$PG->axis_x = VCDLanguage::translate('movie.media');
		$PG->axis_y = "Nr";
		$PG->skin = $skin;
		$PG->type  = $type;
		
		$arrCats = $arrStats['media'];

		$i = 0;
		foreach ($arrCats as $subArr) {
				$media = SettingsServices::getMediaTypeByID($subArr[0])->getDetailedName();
				$num = $subArr[1];
				$PG->x[$i] = $media;
				$PG->y[$i] = $num;		
				$i++;
		}
		$qs = base64_encode($PG->create_query_string());
		$this->assign('graph2', $qs);
		
		
		$PG->reset_values();
		$PG->title = VCDLanguage::translate('movie.year');
		$PG->axis_x = VCDLanguage::translate('movie.year');
		$PG->axis_y = "Nr";
		$PG->skin = $skin;
		$PG->type  = $type;
		
		$arrCats = $arrStats['year'];

		$i = 0;
		foreach ($arrCats as $subArr) {
				$year = $subArr[0];
				$num = $subArr[1];
				$PG->x[$i] = $year;
				$PG->y[$i] = $num;		
				$i++;
		}
		$qs = base64_encode($PG->create_query_string());
		$this->assign('graph3', $qs);
		
	}
	
	private function doTables() {
	
		$arrStats = SettingsServices::getUserStatistics(VCDUtils::getUserID());
		$movieCount = MovieServices::getMovieCount(VCDUtils::getUserID());
		$mapping = getCategoryMapping();
		$altLang = VCDClassFactory::getInstance('VCDLanguage')->isEnglish();
				
		$arrCats = $arrStats['category'];
		// Get the highest percent
		$highest = round((($arrCats[0][1]/$movieCount)*100),1);
		$multiplier = 96/$highest;
	
				
		$results = array();
		foreach ($arrCats as $subArr) {
			$categoryName = SettingsServices::getMovieCategoryByID($subArr[0])->getName(true);
			if (!$altLang && key_exists($categoryName, $mapping)) {
				$categoryName = VCDLanguage::translate($mapping[$categoryName]);
			}
			$num = $subArr[1];
			$percent = round((($num/$movieCount)*100),1);
			$imgpercent = $percent*$multiplier;
			$img = "<img src=\"images/bar.gif\" height=\"10\" title=\"{$percent}%\" alt=\"{$percent}%\"  width=\"{$imgpercent}%\" border=\"0\"/>";
			
			
			$results[$subArr[0]] = array(
				'id' => $subArr[0],
				'name' => $categoryName,
				'count' => $num,
				'image' => $img
			);
		}
		
		$this->assign('categoryList', $results);
		
		
		
		$results = array();
		$arrMedia = $arrStats['media'];
		$highest = round((($arrMedia[0][1]/$movieCount)*100),1);
		$multiplier = 96/$highest;
		
		foreach ($arrMedia as $subArr) {
			$media = SettingsServices::getMediaTypeByID($subArr[0])->getDetailedName();
			$num = $subArr[1];
			$percent = round((($num/$movieCount)*100),1);
			$imgpercent = $percent*$multiplier;
			$img = "<img src=\"images/bar.gif\" height=\"10\" title=\"{$percent}%\" alt=\"{$percent}%\"  width=\"{$imgpercent}%\" border=\"0\"/>";
			
			$results[$subArr[0]] = array(
				'id' => $subArr[0],
				'name' => $media,
				'count' => $num,
				'image' => $img
			);
		}
		
		$this->assign('mediaList', $results);
		
		
		
		$results = array();
		$arrYears = $arrStats['year'];
		// We have to brute force to find the highest entry
		$highest = 0;
		foreach ($arrYears as $tmp) {$tmp[1] > $highest ? $highest = $tmp[1] : 0;}
		$highest = round((($highest/$movieCount)*100),1);
		$multiplier = 96/$highest;
		
		foreach ($arrYears as $subArr) {
			$year= $subArr[0];
			$num = $subArr[1];
			$percent = round((($num/$movieCount)*100),1);
			$imgpercent = $percent*$multiplier;
			$img = "<img src=\"images/bar.gif\" height=\"10\" alt=\"{$percent}%\" title=\"{$percent}%\" width=\"{$imgpercent}%\" border=\"0\"/>";
			
			$results[] = array(
				'id' => 0,
				'name' => $year,
				'count' => $num,
				'image' => $img
			);
		}
				
		$this->assign('yearList', $results);
		$this->assign('movieCount', $movieCount);
		
				
		
	}
	
	
}


?>