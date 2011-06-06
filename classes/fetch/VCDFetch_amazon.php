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
 * @package Kernel
 * @subpackage WebFetch
 * @version $Id$
 */
 
?>
<?php
class VCDFetch_amazon extends VCDFetch {
	
	
	protected $regexArray = array(
		'title'		=> '/<h1 class="parseasinTitle"><span id="btAsinTitle">([^<]*)<\/span><\/h1/',
		'year'		=> '/\(([0-9]{2,4})\)<\/span>/',
		'director'	=> '/<li> <b>Directors:<\/b>(.*)<\/li>/',
		'cast'		=> '/<li> <b>Actors:<\/b>(.*)<\/li>/',
		'runtime'	=> '/<li> <b>Run Time:<\/b> ([^<]*) minutes<\/li>/',
		'rating'	=> '/<span>([0-9.]+) out of 5 stars<\/span>/',
		'plot'		=> '/<b>Product Description<\/b><br \/>(.*?)<\/div>/s',
		'image'		=> '/registerImage\("original_image", "([^"]*)",/'
	);
	
			
	protected $multiArray = array();
		
		
		
	private $servername = 'www.amazon.com';
	private $searchpath = '/s/102-1930070-8444921?url=search-alias%3Ddvd&field-keywords=[$]';
	private $itempath   = '/[$]';
		
	
	public function __construct() {
		$this->setSiteName("amazon");
		$this->setFetchUrls($this->servername, $this->searchpath, $this->itempath);
		$this->setEncoding("ISO-8859-1");
	}
	
	protected function processResults() {
			if (!is_array($this->workerArray) || sizeof($this->workerArray) == 0) {
				$this->setErrorMsg("No results to process.");
				return;
			}
		
		$obj = new imdbObj();
		
		
		// Get the amazon true item ID
		$itemRegx = '/[a-zA-Z0-9-]{10}/';
		$amazonId = $this->getItemID();
		if (@ereg($itemRegx, $this->getItemID(), $amazonId)) {
			$amazonId = str_replace('/', '', $amazonId[0]);
			$this->setID($amazonId);
		}
		
		$obj->setObjectID($amazonId);
		
				
		foreach ($this->workerArray as $key => $data) {
			
			$entry = $data[0];
			$arrData = $data[1];

			switch ($entry) {
				case 'title':
					$title = $arrData[1];
					$regex = "\(([0-9]*)\)";
					$title = ereg_replace($regex, "", $title);
					$obj->setTitle($title);
					break;
				
				case 'year':
					$year = $arrData[1];
					$obj->setYear($year);		
					break;
					
				#case 'studio':
				#	$studio = $arrData[2];
				#	$obj->setStudio($studio);
				#	break;

				case 'director':
					preg_match_all("#>([^<]+)</a>#", $arrData[0], $arrDirectors);
				        $director = implode(", ", $arrDirectors[1]);
					$obj->setDirector($director);		
					break;

				case 'runtime':
					$runtime = trim($arrData[1]);
					$obj->setRuntime($runtime);
					break;

				case 'image':
					$poster = $arrData[1];
					$obj->setImage($poster);
					break;
					
				case 'genre':
					foreach ($arrData as $item) {
						$genre = $item[2];
						$obj->addCategory($genre);
					}
					break;

				case 'plot':
					$plot = $arrData[1];
					$plot = ereg_replace($regex, "", $plot);
					$regex = "<[^>]*>";
					$plot = ereg_replace($regex, "", $plot);
					$obj->setPlot($plot);
					break;

				case 'rating':
					$rating = $arrData[1];
					$obj->setRating($rating);;
					break;
					
				case 'cast':
					preg_match_all("#>([^<]+)</a>#", $arrData[0], $arrActors);
				        $actor = implode("\n", $arrActors[1]);
					$obj->setCast($arrActors[1]);
					break;

				case 'castold':
					if (isset($arrData[0][1])) {
						$actors = explode(",", $arrData[0][1]);
						foreach ($actors as $actor) {
							#$obj->addActor($actor);
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
			case 'poster':
				$regx = 'registerImage("original_image", "([^<]*)",';

				if ($this->getItem($regx) == self::ITEM_OK) {
					$res = $this->getFetchedItem();
				}

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
		$regx = '<a href="http://www.amazon.com/([^<]*)"><span class="srTitle">([^<]*)</span></a>';

		$results = parent::generateSimpleSearchResults($regx,1,2);
		
		return parent::generateSearchSelection($results);
					
	}
	
}

?>
