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
 * @author  H�kon Birgisson <konni@konni.com>
 * @package Kernel
 * @subpackage WebFetch
 * @version $Id$
 */
 
?>
<?php
class VCDFetch_dvdempire extends VCDFetch {
	
	
	protected $regexArray = array(
		'title' 	  => '<div class=\"Item_Title\">([^<]*)</div>',
		'year'  	  => 'Production Year: ([0-9]{4})',
		'studio'	  => 'class=\"Item_StudioProductionRating\">([^<]*)</a>',
		'screens'	  => 'Screen Shots',
		'genre'		  => 'CategoryPage.aspx?([^<]*)>([^<]*)</a>',
		'cast'		  => 'PerformersItems.aspx?([^<]*)type=1">([^<]*)</a>',
		'thumbnail'	  => null,
		'frontcover'  => null,
		'backcover'   => null,
		'screenshots' => null
		);
			
	protected $multiArray = array(
		'cast', 'genre', 'poster'
	);
		
		
		
	private $servername = 'adult.dvdempire.com';
	private $searchpath = '/SearchTitlesPage.aspx?listview=2&media_id=2&searchstring=[$]&pp=50&sort=5';
	private $itempath   = '/ItemPage.aspx?item_id=[$]';
		
	
	public function __construct() {
		$this->useSnoopy();
		$this->setSiteName("dvdempire");
		$this->setAdult();
		$this->setFetchUrls($this->servername, $this->searchpath, $this->itempath);
	}
	
	protected function processResults() {
			if (!is_array($this->workerArray) || sizeof($this->workerArray) == 0) {
				$this->setErrorMsg("No results to process.");
				return;
			}
					
		$obj = new adultObj();
		$obj->setObjectID($this->getItemID());
		$obj->addImage('VCD Front Cover', $this->getImagePath('frontcover'));
		$obj->addImage('VCD Back Cover', $this->getImagePath('backcover'));
				
		foreach ($this->workerArray as $key => $data) {
			
			$entry = $data[0];
			$arrData = $data[1];
			
			switch ($entry) {
				case 'title':
					$title = $arrData[1];
					$obj->setTitle($title);
					break;
				
				case 'year':
					$year = $arrData[1];
					$obj->setYear($year);		
					break;
					
				case 'studio':
					$studio = $arrData[1];
					$obj->setStudio($studio);
					break;
					
				case 'screens':
					// Since we don't know the screenshot count any more .. we say 50 :)
					$screencount = 50;
					if (is_numeric($screencount) && ($screencount>0)) {
						$arrScreens = $this->getImagePath('screenshots');
						for($i=0;$i<$screencount;$i++) {
							$obj->addScreenShotFile($arrScreens[$i]);
						}
					}
					break;
					
				case 'thumbnail':
					$obj->setImage($arrData);
					break;
					
				case 'genre':
					foreach ($arrData as $item) {
						$genre = $item[2];
						$obj->addCategory($genre);
					}
					break;
					
				case 'cast':
					$cast = array();
					foreach ($arrData as $item) {
						$cast[] = trim($item[2]);
					}
					$cast = array_unique($cast);
					foreach ($cast as $actor) {
						if (!empty($actor)) {
							$obj->addActor($actor);		
						}
					}
					break;
					
					
				default:
					break;
			}
			
		}
		
		$this->fetchedObj = $obj;
	}
	
	
	protected function fetchDeeper($entry) {
		
		switch ($entry) {
			case 'thumbnail':
				$value = $this->getImagePath($entry);
				array_push($this->workerArray, array($entry, $value));
				break;
				
			case 'frontcover':
				$value = $this->getImagePath($entry);
				array_push($this->workerArray, array($entry, $value));
				break;
				
			case 'backcover':
				$value = $this->getImagePath($entry);
				array_push($this->workerArray, array($entry, $value));
				break;
				
			case 'screenshots':
				$value = $this->getImagePath($entry);
				array_push($this->workerArray, array($entry, $value));
				break;
		
			default:
				break;
		}
		
	}
	
	public function search($title) { 
		return parent::search($title);
	}
	
	public function showSearchResults() {
		
		$this->setMaxSearchResults(50);
		$regx = 'item_id=([^"]+)&amp;view=0&amp;subview=0">([^<]*)</a>';
		$results = parent::generateSimpleSearchResults($regx, 1, 2);
						
		return parent::generateSearchSelection($results);
					
	}
	
	
	/**
	 * Get the Full HTTP image path for the asked for image on the DVDEmpire server.
	 * Valid image types are thumbnail, frontcover, backcover and screenshots.
	 * All except screenshots return strings, screenshots returns an array of all screenshot images for that movie.
	 *
	 * @param string $image_type
	 * @return mixed.
	 */
	private function getImagePath($image_type) {
	
		$folder = substr($this->getItemID(),0,1);
		$imagebase = "http://images2.dvdempire.com/res/movies/".$folder."/".$this->getItemID();
		
		switch ($image_type) {
			case 'thumbnail':
				return $imagebase.".jpg";
				break;
				
			case 'frontcover':
				$imagebase = "http://images2.dvdempire.com/res/movies/".$folder."/".$this->getItemID();
				return $imagebase."h.jpg";
				break;
				
			case 'backcover':
				$imagebase = "http://images2.dvdempire.com/res/movies/".$folder."/".$this->getItemID();
				return $imagebase."bh.jpg";
				break;
		
			case 'screenshots':
				// Return array of all screenshots
				$screenbase = "http://images2.dvdempire.com/res/movies/screenshots/".$this->getItemID();
				$screens = array();
				for($i = 1; $i <= 50 ; $i++) {
					$path = $screenbase."_".$i."l.jpg";
					array_push($screens, $path);
				}
				return $screens;
				break;
				
			default:
				return false;
				break;
		}
	
	}
	
}




?>