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
 * @author Marco Faltermeier <civaal@gmail.com>
 * @author Jochen Schales <Jochen.Schales_at_gmx.de>
 * @package Kernel
 * @subpackage WebFetch
 * @version $Id: VCDFetch_ofdb.php
 */
?>
<?php
class VCDFetch_ofdb extends VCDFetch {

	protected $regexArray = array(
		'title' 	=> '<title>OFDb - ([^\(]*)\(([0-9]{4})\)</title>',
		'year'  	=> '<title>OFDb - [^\(]*\(([0-9]{4})\)</title>',
		'poster' 	=> '<img src="(http://img.ofdb.de/film/[^"]*)"[^>]*>',
		'director' 	=> 'class="Normal">Regie:</font></td>[^<]*<td>&nbsp;&nbsp;</td>[^<]*<td><font [^>]*><b><a href="view.php.page=liste.Name[^>]*>([^<]*)</a>',
		'genre' 	=> '<a href="view.php.page=genre.Genre=[^"]*">([^<]*)</a>',
		'rating' 	=> '<br>Note: ([0-9].[0-9]{2}) .nbsp;',
		'cast' 		=> '<a href="view.php.page=liste.Name[^>]*>([^<]*)</a>',
		'country' 	=> '<a href="view.php.page=blaettern.Kat=Land&Text=[^>]*>([^<]*)</a>',
		'linkplot'	=> '<b>Inhalt:</b>[^<]*<a href="([^"]*)"[^>]*><b>\[mehr\]</b></a>',
		'orgtitle' => '#Originaltitel:</font></td>\s*<td>&nbsp;&nbsp;</td>\s*<td width="99%"><font face="Arial,Helvetica,sans-serif" size="2" class="Daten"><b>([^<]*)</b>#s',
		'akas' 		=> '#Alternativtitel:</font></td><td>&nbsp;&nbsp;&nbsp;</td><td width="99%"><font face="Arial, Helvetica, sans-serif" size="2" class="Normal"><b>(.+?)<br></b>#s',
		'plot'		=> NULL
	);

	protected $multiArray = array(
		'genre', 'cast', 'country'
	);



	private $servername = 'www.ofdb.de';
	private $searchpath = '/view.php?page=suchergebnis&Kat=Titel&SText=[$]';
	private $itempath   = '/film/[$],';


	public function __construct() {
		$this->setSiteName("ofdb");
		$this->setFetchUrls($this->servername, $this->searchpath, $this->itempath);
	}


	public function search($title) {

		return parent::search($title);
	}

	public function showSearchResults() {
		$this->setMaxSearchResults(50);
		$regx = '<a href=\"film/([0-9]+),[^)]*\)\">([^<]*)<font size=\"1\">[^<]*</font> \(([0-9]{4})\)</a>';
		$results = parent::generateSimpleSearchResults($regx, 1, 2, 3);
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
					$year = $arrData[1];
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
						array_push($arr, $this->getIMBDGenre($item[1]));
					}
					$obj->setGenre($arr);
					break;

				case 'rating':
					$rating = $arrData[1];
					$obj->setRating($rating);
					break;

				case 'cast':
					if (sizeof($arrData) > 0) {
					$arrCast = array();
		
					foreach ($arrData as $itemArr) {
						if($obj->getDirector()!= stripslashes($itemArr[1]))
								array_push($arrCast, $itemArr[1]);
					}
					
					$obj->setCast($arrCast);
					}
					break;

				case 'orgtitle':
					$altTitle = $arrData[1];
				
					if(stripslashes(trim($altTitle)) != $obj->getTitle())
						$obj->setAltTitle($arrData[1]);
					
					break;
					
				case 'akas':
					if($obj->getAltTitle() != "")
						$akaTitles = $obj->getAltTitle().", ";	
					
					$akaTitles = $akaTitles.str_replace('<br>', ', ', $arrData[1]);
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
							array_push($arrCountries, $itemArr[1]);
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
	
	
	private function getIMBDGenre($genre) {
		$mapping = array(
			'Action' 		=> 'Action',
			'Abenteuer' 	=> 'Adventure',
			'Animation' 	=> 'Animation',
			'Biographie' => 'Biography',
			'Manga/Anime' => 'Anime / Manga',
			'Komödie' 		=> 'Comedy',
			'Krimi' 		=> 'Crime',
			'Dokumentation' 	=> 'Documentary',
			'Drama' 		=> 'Drama',
			'Kinder-/Familienfilm' 		=> 'Family',
			'Fantasy' 		=> 'Fantasy',
			'Film-Noir' 	=> 'Film-Noir',
			'Historienfilm' => 'History',
			'Horror' 		=> 'Horror',
			'James Bond' 	=> 'James Bond',
			'Music Video' 	=> 'Music Video',
			'Musicfilm' 		=> 'Musical',
			'Mystery' 		=> 'Mystery',
			'Liebe/Romantik' 		=> 'Romance',
			'Science-Fiction' 		=> 'Sci-Fi',
			'Kurzfilm' 		=> 'Short',
			'Kampfsport'  => 'Sport',
			'Sportfilm'		=> 'Sport',
			'Thriller' 		=> 'Thriller',
			'TV-Serie' 		=> 'Tv Shows',
			'Krieg' 			=> 'War',
			'Western' 		=> 'Western',
			'Sex' 		=> 'Adult',
			'Hardcore' => 'X-Rated'
		);
		
			if (isset($mapping[$genre])){
				return $mapping[$genre];
			}else{
				return $genre;
			}
		
		
	}

	protected function fetchDeeper($entry) {
		switch ($entry) {

			case 'plot':
			
			
				$plotUrl = "/".(String) $this->workerArray[8][1][1];

				$referer = "http://".$this->servername.str_replace('[$]', $this->getItemID(), $this->itempath);
				$isPlot =  $this->fetchPage($this->servername, $plotUrl, $referer);
				if ($isPlot) {
					
					$plotRegex = '/Mal gelesen<\/b><\/b><br><br>(.+?)(?=<\/font><\/p>)/s';
					
					if ($this->getItem($plotRegex) == self::ITEM_OK) {
	

						$plotArr = $this->getFetchedItem();

						$plotBadStr = array("…", "", "<br />", "<br>");
						$plotReplaceStr = array("...", "...", "", "");
						$plotText = str_replace($plotBadStr, $plotReplaceStr, $plotArr[1]);
						array_push($this->workerArray, array($entry, $plotText));

					} 
			
				}

				break;

			default:
				break;
		}
	}
	



}
?>