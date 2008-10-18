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
 * @author  Hákon Birgsson <konni@konni.com>
 * @package Kernel
 * @subpackage WebFetch
 * @version $Id$
 */

?>
<?php
class VCDFetch_imdb extends VCDFetch {


	protected $regexArray = array(
		'title'		=> '<h1>([^\<]*)<span>',
		'year'  	=> '(<a href="/Sections/Years/([0-9]{4})/">([0-9]{4})</a>)',
		'poster' 	=> '<a name="poster"[^<]*><img[^<]*src="([^<]*)" /></a>',
		'director' 	=> '#Director.*\n[^<]*<a href="/Name?[^"]*">([^<]*)</a>#i',
		'genre' 	=> '<A HREF=\"/Sections/Genres/[a-zA-Z\\-]*/\">([a-zA-Z\\-]*)</A>',
		'rating' 	=> '<b>([0-9]).([0-9])/10</b>',
		'cast' 		=> NULL,	// The cast is populated in the fetchDeeper() function
		'runtime' 	=> '([0-9]+) min',
		'akas' 		=> 'Also Known As</b>:</b><br>(.*)<b class="ch"><a href="/mpaa">MPAA</a>',
		'country' 	=> '<a href=\"/Sections/Countries/([^>]*)/">([^<]*)</a>',
		'plot'		=> '<h5>Plot Outline:</h5>([^\<]*)<'
		);

	protected $multiArray = array(
		'genre', 'cast', 'akas', 'country'
	);



	private $servername = 'akas.imdb.com';
	private $searchpath = '/find?s=tt&q=[$]';
	private $itempath   = '/title/tt[$]/';


	public function __construct() {
		$this->setSiteName("imdb");
		$this->setFetchUrls($this->servername, $this->searchpath, $this->itempath);
		$this->useSnoopy();
	}


	public function search($title) {
		return parent::search($title);
	}

	public function showSearchResults() {

		$this->setMaxSearchResults(50);
		$regx = '<a href=\"\/title\/tt([0-9]+)\/([^\<]*)\">([^\<]*)</a>[^(]*\(([0-9]{4}(/I+)?)\)';
		$results = parent::generateSimpleSearchResults($regx, 1, 3, 4);
		return parent::generateSearchSelection($results);
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
					$poster = $arrData[1];
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
					// The cast list has been populated in the fetchDeeper function
					if (is_array($arrData)) { 
						$obj->setCast($arrData);
					}
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
					if (is_array($arrData)) {
						$plot = trim($arrData[1]);
					} elseif (is_string($arrData)) {
						$plot = trim($arrData);
					}
					
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
				$regx = '/<a href="\/name\/nm([^"]+)\/">([^<]*)<\/a><\/td><td class="ddd"> ... <\/td><td class="char">(.*?)<\/td>/';
				preg_match_all($regx, $this->getContents(), $matches);
				if (is_array($matches) && sizeof($matches)>0) {
					$actors = $matches[2];
					$roles = $matches[3];
					
					$castList = array();
					for($i=0;$i<sizeof($actors);$i++) {
						$pair = $actors[$i].' .... ' . strip_tags($roles[$i]);
						$castList[] = $pair;
					}
					array_push($this->workerArray, array($entry, $castList));
				}				
				break;
			
			
			case 'poster':
				$regx = '<a name="poster" href="photogallery" title="([^<]*)"><img border="0" alt="([^<]*)" title="([^<]*)" src="([^<]*)" height="([0-9]{2,3})" width="([0-9]{2,3})"></a>';

				if ($this->getItem($regx) == self::ITEM_OK) {
					$res = $this->getFetchedItem();
				}

				break;


			case 'akas':

				$ret = array();
				$contents = $this->getContents();
		        if(eregi('Also Known As:</b><br>(.*)<b class="ch"><a href="/mpaa">MPAA</a>',$contents, $y)) {
		            $contents = $y[0];
		            while(eregi('<br>([^<]*)', $contents, $x)) {
		            	if (isset($x[1]) && strcmp(trim($x[1]),"") != 0) {
		            		$ret[] = trim($x[1]);
		            	}
		            	$contents = substr($contents,strpos($contents,$x[0])+strlen($x[0]));
		            }
		        }
		        array_push($this->workerArray, array($entry, $ret));

				break;


			case 'plot':

				
				// Save the old buffer
				$itemBuffer = $this->getContents();

				// Generate urls
				$plotUrl = str_replace('[$]', $this->getItemID(), $this->itempath).'plotsummary';
				$referer = "http://".$this->servername.str_replace('[$]', $this->getItemID(), $this->itempath);

				$isPlot =  $this->fetchPage($this->servername, $plotUrl, $referer);
				if ($isPlot) {
					
					$regxPlot = '<p class="plotpar">([^\<]*)<i>';
					if ($this->getItem($regxPlot) == self::ITEM_OK) {

						$plotArr = $this->getFetchedItem();
						$plotText = trim($plotArr[1]);
						array_push($this->workerArray, array($entry, $plotText));

					} else {
						// Plot not found, use the Tagline instead and use the old buffer again
						$regExTagline = '#Tagline:</b>([^<]*)#';
						$this->setContents($itemBuffer);
						if ($this->getItem($regExTagline) == self::ITEM_OK ) {
							$plotArr = $this->getFetchedItem();
							$plotText = $plotArr[1];
							array_push($this->workerArray, array($entry, $plotText));
						}
					}
				}


				break;

			default:
				break;
		}
	}







}









?>
