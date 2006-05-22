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
 * @author  Hákon Birgsson <konni@konni.com>
 * @package Kernel
 * @subpackage WebFetch
 * @version $Id$
 */
 
?>
<? 
class VCDFetch_jaded extends VCDFetch {
	
	
	protected $regexArray = array(
		'title' 	  => '<big><strong><font color="#000FF">([^<]*)</big></strong><br>',
		'year'  	  => 'REL: ([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})<br>',
		'studio'	  => 'Manufacturer=([^<]*)>([^<]*)</a><BR>',
		'screens'	  => 'topoftabs\">([^<]*) Screen Shots</a>',
		'genre'	 	  => 'CAT: <a href=search_result.asp?([^<]*)>([^<]*)</a><BR>',
		'cast' 		  => '</font><font color=#00000>([^<]*)<br>',
		'thumbnail'	  => null,
		'frontcover'  => null,
		'backcover'   => null
		);
	
			
	protected $multiArray = array(
		'cast', 'genre', 'poster'
	);
		
		
		
	private $servername = 'www.jadedvideo.com';
	private $searchpath = '/Search_result.asp?V=true&CATEGORY=ALL&SexPref=1&MANUFACTURER=ALL&IMAGE_PATH=DVD&DESCRIPTION=[$]&MESSAGE=ALL&SaleStatus=1&ScrollAction=Page1&SO=T&OB=Description%20ASC';
	private $itempath   = '/search_result.asp?PRODUCT_ID=[$]';
		
	
	public function __construct() {
		$this->useSnoopy();
		$this->setSiteName("jaded");
		$this->setFetchUrls($this->servername, $this->searchpath, $this->itempath);
		$this->setAdult();
	}
	
	protected function processResults() {
			if (!is_array($this->workerArray) || sizeof($this->workerArray) == 0) {
				$this->setErrorMsg("No results to process.");
				return;
			}
					
		$obj = new adultObj();
		$obj->setObjectID($this->getItemID());
				
		foreach ($this->workerArray as $key => $data) {
			
			$entry = $data[0];
			$arrData = $data[1];
			
			switch ($entry) {
				case 'title':
					$title = $arrData[1];
					$regex = "\(([A-z]{3})\)";
					$title = ereg_replace($regex, "", $title);
					$obj->setTitle($title);
					break;
				
				case 'year':
					$year = $arrData[3];
					$obj->setYear($year);		
					break;
					
				case 'studio':
					$studio = $arrData[2];
					$obj->setStudio($studio);
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
					if (isset($arrData[0][1])) {
						$pornstars = explode(",", $arrData[0][1]);
						foreach ($pornstars as $pornstar) {
							$obj->addActor(ltrim($pornstar, "\."));
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
		$regx = 'PRODUCT_ID=([0-9]{1,6})">([^<]*)</a></td>';
		//$regx = 'PRODUCT_ID=69750">([^<]*)</a>';

		$results = parent::generateSimpleSearchResults($regx,1,2);
		
		
		

		parent::generateSearchSelection($results);
		
		/*
		print "<pre>";
		print_r($results);
		print "</pre>";
		*/
					
	}
	
	
	/**
	 * Get the Full HTTP image path for the asked for image on the DVDEmpire server.
	 * Valid image types are thumbnail, frontcover, backcover.
	 * All except screenshots return strings, screenshots returns an array of all screenshot images for that movie.
	 *
	 * @param string $image_type
	 * @return mixed.
	 */
	private function getImagePath($image_type) {
	
		$folder = substr($this->getItemID(),0,3);
				
		switch ($image_type) {
			case 'thumbnail':
				$imagebase = "http://www.jadedvideo.com/images/".$folder."/thumbs/".$this->getItemID();
				return $imagebase.".jpg";
				break;
				
			case 'frontcover':
				$imagebase = "http://www.jadedvideo.com/images/".$folder."/front/".$this->getItemID();
				return $imagebase.".jpg";
				break;
				
			case 'backcover':
				$imagebase = "http://www.jadedvideo.com/images/".$folder."/back/".$this->getItemID();
				return $imagebase.".jpg";
				break;
						
			default:
				return false;
				break;
		}
	
	}
	
}




?>