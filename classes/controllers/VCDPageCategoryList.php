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
 * @version $Id: VCDPageCategoryList.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php

class VCDPageCategoryList extends VCDBasePage {
	
	
	private $category_id;
	private $recordsPerPage;
	private $recordCount;
	private $offset = 0;
	private $mineonly = false;
	private $page = 0;
	private $imageMode = false;
		
	public function __construct(_VCDPageNode $node) {
		
		parent::__construct($node);
		$this->initPage();
			
		if ($this->imageMode) {
			$this->doImageList();
		} else {
			$this->doTextList();	
		}

		$this->doPager();
				
	
	}
		
	/**
	 * Populate the image category list
	 *
	 */
	private function doImageList() {
		$results = array();
		
		$movies = $this->getMovieList();
		foreach ($movies as $movie) {
			$coverObj = $movie->getCover('thumbnail');
			if ($coverObj instanceof cdcoverObj ) {
				$results[] = $coverObj->getCategoryImageAndLink("./?page=cd&amp;vcd_id=".$movie->getID()."",$movie->getTitle());
			}
		}
		
		$this->assign('movieCategoryList', $results);
	}
	
	/**
	 * Populate the text category list
	 *
	 */
	private function doTextList() {
		$movies = $this->getMovieList();
		$results = array();
		foreach ($movies as $movieObj) {
			$results[] = array('id' => $movieObj->getID(), 
				'title' => $movieObj->getTitle(), 
				'year' => $movieObj->getYear(),
				'mediatypes' => fixFormat($movieObj->showMediaTypes()));
		}
		
		$this->assign('movieCategoryList', $results);
	}
		
	
	/**
	 * Get the movie collection to work with based on current selection
	 *
	 * @return array | array of VCDobj
	 */
	private function getMovieList() {
		if ($this->mineonly && VCDUtils::isLoggedIn()) {
			$movies = MovieServices::getVcdByCategory($this->category_id, 
				$this->recordsPerPage, $this->offset, VCDUtils::getUserID());
		} elseif (VCDUtils::isLoggedIn() && VCDUtils::isUsingFilter(VCDUtils::getUserID())) {
			$movies = MovieServices::getVcdByCategoryFiltered($this->category_id, $this->recordsPerPage, 
				$this->offset, VCDUtils::getUserID());
		} else {
			$movies = MovieServices::getVcdByCategory($this->category_id, $this->recordsPerPage, $this->offset);
		}
		
		return $movies;
	}
	
	/**
	 * Initilize all the varibles the page needs to calculate which data to display
	 *
	 */
	protected function initPage() {
		
		$this->category_id = $this->getParam('category_id',false,-1);
		$this->recordsPerPage = SettingsServices::getSettingsByKey("PAGE_COUNT");
		$this->page = $this->getParam('batch',false,0);
		$this->offset = $this->page*$this->recordsPerPage;

		$this->imageMode = $this->setViewMode();
		$this->mineonly = (isset($_SESSION['mine']) && $_SESSION['mine'] == true);
				
		
		// Validate the data
		if (!is_numeric($this->category_id) || !is_numeric($this->page)) {
			redirect();
			exit();
		}
		
		// Assign global vars
		if ($this->imageMode) {
			$this->assign('imageMode',true);
		}
		
		if ($this->mineonly && VCDUtils::isLoggedIn()) {
			$categoryCount = MovieServices::getCategoryCount($this->category_id, VCDUtils::getUserID());
		} elseif (VCDUtils::isLoggedIn() && VCDUtils::isUsingFilter(VCDUtils::getUserID())) {
			$categoryCount = MovieServices::getCategoryCountFiltered($this->category_id, VCDUtils::getUserID());
		} else {
			$categoryCount = MovieServices::getCategoryCount($this->category_id);
		}
		
		$this->recordCount = $categoryCount;
		
		$this->assign('categoryId', $this->category_id);
		$this->assign('categoryPage', $this->page);
		$this->assign('movieCategoryCount', $categoryCount);
	}
	
	
	/**
	 * Figure out the current viewmode for the category display style
	 *
	 * @return bool
	 */
	protected function setViewMode() {
		$mode = $this->getParam('viewmode');
		if (is_null($mode)) {
			return (isset($_SESSION['viewmode']) && strcmp($_SESSION['viewmode'],'img')==0);
		}
		
		if (strcmp($mode,'img')==0) {
			$_SESSION['viewmode'] = 'img';
			return true;
		} elseif (strcmp($mode,'text')==0) {
			$_SESSION['viewmode'] = 'text';
			return false;
		}
	}
	
	
	/**
	 * Create the navigation pager
	 *
	 */
	private function doPager() {

		$totalPages = floor($this->recordCount / $this->recordsPerPage);
	
		if ($this->recordCount < $this->recordsPerPage) {
			return;
		}
	
		$current_pos = $this->page;
		
		$nextpos = $current_pos + 1;
		$backpos = $current_pos - 1;
	
		if ($current_pos > 0) {
			$first = "<a href=\"?page=category&amp;category_id={$this->category_id}&amp;batch=0\">&lt;</a>";
		} else {
			$first = "&lt;";
		}
	
		if ($current_pos >= $totalPages) {
			$last  = "&gt;";
		} else {
			$last  = "<a href=\"?page=category&amp;category_id={$this->category_id}&amp;batch=$totalPages\">&gt;</a>";
		}
	
		if ($current_pos > 0) {
			$back  = "<a href=\"?page=category&amp;category_id={$this->category_id}&amp;batch=$backpos\">&lt;</a>";
		} else {
			$back  = "&lt;";
		}
	
	
		if ($current_pos >= $totalPages) {
			$next  = "&gt;";
		} else {
			$next  = "<a href=\"?page=category&amp;category_id={$this->category_id}&amp;batch=$nextpos\">&gt;</a>";
		}
	
		$page = ($current_pos+1) . " of " . ($totalPages+1);
	
		$html = "<div id=\"pager\">" . $first . $back ." [$page] " . $next . $last . "</div>";
		
		$this->assign('categoryPager',$html);
	
	}
	
}

?>