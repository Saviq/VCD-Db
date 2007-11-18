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
 * @subpackage Controller
 * @version $Id: VCDPageCategoryListAdult.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php
class VCDPageCategoryListAdult extends VCDBasePage {
	
	private $isCategoryList = false;
	private $isStudioList = false;
	private $list_id = null;
	
	private $recordsPerPage;
	private $recordCount;
	private $offset = 0;
	private $page = 0;
	private $imageMode = false;
	
	public function __construct(_VCDPageNode $node) {
		try {
		
			parent::__construct($node);
		
			$this->initPage();
			
			// Register javascripts
			$this->registerScript(self::$JS_JSON);
			$this->registerScript(self::$JS_AJAX);
			$this->registerScript(self::$JS_LYTE);
			
			// Load the correct data in the dropdown list
			$this->doSelectionList();
			
			// Load the selected list view
			if ($this->imageMode) {
				$this->doImageList();
			} else {
				$this->doTextList();	
			}
					
			// Do the pager
			$this->doPager();
				
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	
	/**
	 * Populate the image category list
	 *
	 */
	private function doImageList() {
				
		$results = array();
		if ($this->isCategoryList) {
			$movies = MovieServices::getVcdByAdultCategory($this->list_id);
		} elseif ($this->isStudioList) {
			$movies = MovieServices::getVcdByAdultStudio($this->list_id);
		}
		
		
		$this->recordCount = sizeof($movies);
		$this->assign('movieCategoryCount', $this->recordCount);
		$movies = $this->doFilter($movies);
				
		foreach ($movies as $movie) {
			$coverObj = $movie->getCover('thumbnail');
			if ($coverObj instanceof cdcoverObj ) {
				$results[] = 
					$coverObj->getCategoryImageAndLink("?page=cd&amp;vcd_id=".$movie->getID(),$movie->getTitle(), 125,185);
			}
		}
		
		$this->assign('movieList', $results);
	}
	
	
	/**
	 * Populate the text based list
	 *
	 */
	private function doTextList() {
		
		$results = array();
		if ($this->isCategoryList) {
			$movies = MovieServices::getVcdByAdultCategory($this->list_id);
		} elseif ($this->isStudioList) {
			$movies = MovieServices::getVcdByAdultStudio($this->list_id);
		}
		
		
		$this->recordCount = sizeof($movies);
		$this->assign('movieCategoryCount', $this->recordCount);
		$movies = $this->doFilter($movies);
		
		foreach ($movies as $movieObj) {
			$results[] = array('id' => $movieObj->getId(), 'title' => $movieObj->getTitle(), 
				'year' => $movieObj->getYear(), 'screens' => (bool)$movieObj->hasScreenshots());
		}
		
		
		$this->assign('movieList',$results);
	}
	
	
	/**
	 * Filter out correct items to display
	 *
	 * @param array $items | Array of vcd Objects
	 * @return array | The filtered array
	 */
	private function doFilter(&$items) {
		$newarr = array();
		$start = $this->page*$this->recordsPerPage;
		$pageCount = $this->recordsPerPage;
				
		for ($i = 0; $i < sizeof($items); $i++) {
			if ($i >= $start && $i < ($start+$pageCount)) {
				array_push($newarr, &$items[$i]);
			}
			if ($i > ($start+$pageCount)) {
				break;
			}
		}
		return $newarr;
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
		
		
		if ($this->isCategoryList) {
			$listType = 'category_id';
		} elseif ($this->isStudioList) {
			$listType = 'studio_id';
		}
		
	
		if ($current_pos > 0) {
			$first = "<a href=\"?page=adultcategory&amp;{$listType}={$this->list_id}&amp;batch=0\">&lt;</a>";
		} else {
			$first = "&lt;";
		}
	
		if ($current_pos >= $totalPages) {
			$last  = "&gt;";
		} else {
			$last  = "<a href=\"?page=adultcategory&amp;{$listType}={$this->list_id}&amp;batch=$totalPages\">&gt;</a>";
		}
	
		if ($current_pos > 0) {
			$back  = "<a href=\"?page=adultcategory&amp;{$listType}={$this->list_id}&amp;batch=$backpos\">&lt;</a>";
		} else {
			$back  = "&lt;";
		}
	
	
		if ($current_pos >= $totalPages) {
			$next  = "&gt;";
		} else {
			$next  = "<a href=\"?page=adultcategory&amp;{$listType}={$this->list_id}&amp;batch=$nextpos\">&gt;</a>";
		}
	
		$page = ($current_pos+1) . " of " . ($totalPages+1);
	
		$html = "<div id=\"pager\">" . $first . $back ." [$page] " . $next . $last . "</div>";
		
		$this->assign('categoryPager',$html);
	
	}
	
	
	/**
	 * Populate the correct list selection based on what view is in use.
	 *
	 */
	private function doSelectionList() {
		
		$results = array();
		
		if ($this->isCategoryList) {
			
			$list = PornstarServices::getSubCategoriesInUse();
			foreach ($list as $categoryObj) {
				$results[$categoryObj->getId()] = $categoryObj->getName();
			}
			
		} elseif ($this->isStudioList) {
		
			$list = PornstarServices::getStudiosInUse();
			foreach ($list as $studioObj) {
				$results[$studioObj->getId()] = $studioObj->getName();
			}
			
		} else {
			throw new VCDInvalidArgumentException('Invalid list view');
		}
		
		
		$this->assign('currentList', $results);
		$this->assign('selectedListItem', $this->list_id);
	
	}
	
	
	/**
	 * Initialize the Controller based on which data to display
	 *
	 */
	private function initPage() {
		
		$category_id = $this->getParam('category_id');
		$studio_id = $this->getParam('studio_id');
		if (!is_null($category_id) && is_numeric($category_id)) {
			$this->isCategoryList = true;
			$this->list_id = $category_id;
			$this->assign('byCategory',true);
		} elseif (!is_null($studio_id) && is_numeric($studio_id)) {
			$this->isStudioList = true;
			$this->list_id = $studio_id;
		}
		
		
		
		$this->recordsPerPage = SettingsServices::getSettingsByKey("PAGE_COUNT");
		$this->page = $this->getParam('batch',false,0);
		$this->offset = $this->page*$this->recordsPerPage;

		$this->imageMode = $this->setViewMode();
				
		
		// Validate the data
		if (!is_numeric($this->list_id) || !is_numeric($this->page)) {
			redirect();
			exit();
		}
		
		// Assign global vars
		if ($this->imageMode) {
			$this->assign('imageMode',true);
		} 		
				
		if ($this->isStudioList) {
			$this->assign('viewTitle', 'Current studio');
			$this->assign('viewType', 'studio_id');
		} elseif ($this->isCategoryList) {
			$this->assign('viewTitle', 'Current category');
			$this->assign('viewType', 'category_id');
		}
		
		
		$this->assign('categoryId', $this->list_id);
		$this->assign('categoryPage', $this->page);
		
		
		
		
	}
	
	
	/**
	 * Figure out the current viewmode for the category display style
	 *
	 * @return bool
	 */
	private function setViewMode() {
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
	
	
}

?>