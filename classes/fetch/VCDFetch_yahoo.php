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
class VCDFetch_yahoo extends VCDFetch {
	
	
	protected $regexArray = array(
		'title' 	  => '<td bgcolor=A6B9DC width=570><h1><strong>([^<]*)</strong></h1></td>',
		'year'  	  => '<td bgcolor=A6B9DC width=570><h1><strong>([^<]*)</strong></h1></td>',
		'genre'	 	  => 'site_media_id=([0-9])">([^<]*)</a></nobr>',
		'cast' 		  => null,
		'director'	  => 'Directed by:</font></td><td><font face=arial size=-1><a href="/shop?d=hc&id=1804476864&cf=gen">([^<]*)</a></font>',
		'thumbnail'	  => '<img src=([^<]*) width="101"',
		'frontcover'  => null,
		'backcover'   => null
		);
	
			
	protected $multiArray = array(
		'cast', 'genre', 'poster'
	);
		
		
		
	private $servername = 'movies.yahoo.com';
	private $searchpath = '/mv/search?p=[$]';
	private $itempath   = '/shop?d=hv&cf=info&id=[$]';
		
	
	public function __construct() {
		$this->useSnoopy();
		$this->setSiteName("yahoo");
		$this->setFetchUrls($this->servername, $this->searchpath, $this->itempath);
	}
	
	protected function processResults() {
		if (!is_array($this->workerArray) || sizeof($this->workerArray) == 0) {
			$this->setErrorMsg("No results to process.");
			return;
		}
		
		$obj = new imdbObj();
		$obj->setIMDB($this->getItemID());
		
		foreach ($this->workerArray as $key => $data) {
			
			$entry = $data[0];
			$arrData = $data[1];
			
			switch ($entry) {
				case 'title':
					$title = $arrData[1];
					$obj->setTitle($title);
					break;
				
				case 'year':
					$year = $arrData[2];
					$obj->setYear($year);		
					break;
					
				case 'poster':
					$poster = $arrData[4];
					$obj->setImage($poster);
					break;
					
				case 'director':
					$director = $arrData[1];
					$obj->setDirector($director);
					break;
					
				case 'genre':
					
					$arr = array();
					foreach ($arrData as $item) {
						array_push($arr, $item[1]);
					}
					$obj->setGenre($arr);
					break;
					
				case 'rating':
					$rating = $arrData[1].$arrData[2];
				    $rating = $rating/10;
				    $obj->setRating($rating);
					break;
					
				case 'cast':
					$arr = null;
					$arr = array();				
					foreach ($arrData as $itemArr) {
						$actor = $itemArr[1];
						$role = $itemArr[3];
						
						// Break when we hit the director ..
						if (strcmp($role,"Director")==0) {break;}
						
						$result = $actor." .... " .$role;
						array_push($arr, $result);
					}
					$obj->setCast($arr);
					break;
					
				case 'runtime':
					$runtime = $arrData[1];
					$obj->setRuntime($runtime);
					break;
					
				case 'akas':
					$akaTitles = implode(',', $arrData);
					$obj->setAltTitle($akaTitles);
					break;
					
				case 'plot':
					$plot = $arrData;
					$obj->setPlot($plot);
					break;
					
				case 'country':
					if (sizeof($arrData) > 0) {
						$arrCountries = array();
						foreach ($arrData as $itemArr) {
							array_push($arrCountries, $itemArr[2]);
						}
						$obj->setCountry($arrCountries);
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
			case 'cast':
			
				// Save the old buffer
				$itemBuffer = $this->getContents();	
			
				// Generate urls
				$actorurl = "/movie/".$this->getItemID()."/cast";
				$referer = "http://".$this->servername.str_replace('[$]', $this->getItemID(), $this->itempath);
				
				// Set the regx
				$regx = '&cf=gen">([^"]+)</a></font></td>([^\s])<td><font face=arial size=-1>([^"]+)</font>';
				
				$isActors =  $this->fetchPage($this->servername, $actorurl, $referer);
				if ($isActors) {
					if ($this->getItem($regx, true) == self::ITEM_OK) {
						$actors = $this->getFetchedItem();
						array_push($this->workerArray, array($entry, $actors));
						
					} 
				} 
				
				// Restore the old buffer
				$this->setContents($itemBuffer);
				
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
		$regx = 'd=hv&cf=info&id=([0-9]{10})">([^"]+)</a><br>';

		$results = parent::generateSimpleSearchResults($regx,1,2);
		
		
		

		parent::generateSearchSelection($results);
		
		/*
		print "<pre>";
		print_r($results);
		print "</pre>";
		*/
					
	}
	
	
	
}




?>