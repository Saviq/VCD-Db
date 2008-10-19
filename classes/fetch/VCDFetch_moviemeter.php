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
 * Search by Hákon Birgsson <konni@konni.com>
 * Regexp by Rolandow <rolandow@gmail.com>
 *
 * @author  Hákon Birgsson <konni@konni.com>
 * @author  Rolandow  <rolandow@gmail.com>
 * @package Kernel
 * @subpackage WebFetch
 * @version $Id: VCDFetch_moviemeter.php 1366 2007-12-12 10:03:15Z konni $
 */

?>
<?php
class VCDFetch_moviemeter extends VCDFetch {


	protected $regexArray = array(
		'titleyear'		=> '<head><title>([^\<]*)\(([0-9]*)\) - MovieMeter.nl<',
		'poster' 		=> '([^<]*)><img class="poster"([^<]*)([^<]*)src="([^<]*)" style="width:',
		'coungenrun'    => '<div id="film_info">([^<]*)<br \/>([^<]*)<br \/>([0-9]*) minuten',								// Country, genre, runtime
		'director'		=> 'geregisseerd door (.*)<br \/>met ',																// One or more directors
		'castplot' 		=> 'geregisseerd door <a href="(.*)</a><br \/>met (de stemmen van )*([^\<]*)<br \/><br \/>([^\<]*)',	// Director, cast, plot
		'rating' 		=> '<div id="film_votes"><b>([0-9]*)</b> stemmen(.*)gemiddelde <b>([0-9]*,[0-9]*)</b>',
		'akas' 			=> '\/h1><p>Alternatieve titel: ([^\<]*)&nbsp;'
		);

	protected $multiArray = array(
		'genre', 'cast', 'akas', 'country'
	);



	private $servername = 'www.moviemeter.nl';
	private $searchpath = '/';
	private $itempath   = '/film/[$]';


	public function __construct() {
		$this->setSiteName("moviemeter");
		$this->setFetchUrls($this->servername, $this->searchpath, $this->itempath);
		$this->setEncoding("ISO-8859-1");
		$this->useSnoopy();
	}


	public function search($title) {
		parent::search($title);
		// Grab the session ID
		$regx = "/hash=([0-9a-f]*)&/";
		$matchCount = preg_match($regx,$this->getContents(),$matches);
		if ($matchCount==0) {
			return self::SEARCH_ERROR;
		}
		$searchToken = $matches[1];
		
		
		// Now actually perform the search ..
		$searchUrl = '/calls/quicksearch.php?hash='.$searchToken.'&search='.rawurlencode($title);
		$this->fetchPage($this->servername,$searchUrl, $this->servername,false);
		
	}

	public function showSearchResults() {
		$this->setMaxSearchResults(50);
		
		$regx = '/film;;([0-9]{1,6});;(.*) %/';
		$searchCount = preg_match_all($regx, $this->getContents(), $matches);
		if ($searchCount > 0) {
			$ids = $matches[1];
			$titles = $matches[2];
			$results = array();
			for($i=0;$i<sizeof($ids);$i++) {
				$results[] = array('id' => $ids[$i], 'title' => urldecode($titles[$i]));
			}
			return parent::generateSearchSelection($results);
		} else {
			return null;
		}
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
				case 'titleyear':
					$title = $arrData[1];
					$obj->setTitle($title);
					
					$year = $arrData[2];
					$obj->setYear($year);
					break;

				case 'poster':
					$poster = $arrData[4];
					$obj->setImage($poster);
					break;
					
				case 'coungenrun':
					$country = $arrData[1];
					$obj->setCountry(Array($country));
					
					$genre = explode(" / ", $arrData[2]);
					$obj->setGenre($genre);
					
					$runtime = $arrData[3];
					$obj->setRuntime($runtime);
					break;
					
				case 'director':
					$tmp = $arrData[1];
					preg_match_all('|(<a href=(["\']))(.*?)(\2(.*?)>(.*?)<\/a>)|s', $tmp, $directors);
					$obj->setDirector(implode(",", $directors[6]));
					break;
					
				case 'castplot':
					$cast = explode(" en ", $arrData[3]);
					$cast2 = explode(", ", $cast[0]);
					$cast2[] = $cast[1];
					
					$obj->setCast($cast2);
					
					$plot = $arrData[4];
					$obj->setPlot($plot);
					break;
					
				case 'rating':
					$rating = $arrData[3];
					$rating = str_replace(',','.',$rating);
					$obj->setRating($rating);
					break;

				case 'akas':
					$akaTitle = $arrData[1];
					$obj->setAltTitle($akaTitle);
					break;

				default:
					break;
			}
		}

		$this->fetchedObj = $obj;


	}

	protected function fetchDeeper($entry) {

		switch ($entry) {

			default:
				break;
		}
	}







}









?>
