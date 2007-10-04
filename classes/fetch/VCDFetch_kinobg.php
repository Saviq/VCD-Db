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
 * @author  Branislav Kirilov and Bai Velichko <mastermind@networx-bg.com>
 * @package Kernel
 * @subpackage WebFetch
 * @version $Id: VCDFetch_kinobg.php,v 1.0 15/10/2006
 */

?>
<?php
class VCDFetch_kinobg extends VCDFetch {


	protected $regexArray = array(
		'title' 	=> '#<td class=\"txtH\">\n<p><b>([^\<]*)</b><br>#i',
        'year'  	=> '#<b>Година</b>: \n([0-9]+)<br>#i',
		'poster' 	=> '#(http://i.dir.bg/kino/posters/pos[0-9]+.gif)#',
		'director' 	=> '<td colspan="2" class="txtH"><br><b>Режисьор</b>: ([^<]*)<br><b>',
		'genre' 	=> '<a href=\'/search.php\?advanced=1&janr=([0-9]+)\'>([^<]*)</a>',
		'rating' 	=> '<br><b>Рейтинг:</b> ([^!]+)%<p>',
		'cast' 		=> '#<b>В ролите</b>: (.*?)<br>#',
		'runtime' 	=> '</a><br><b>Времетраене:</b> ([0-9]+) минути',
		'akas' 		=> '<i>([^>]*)</i></p>',
		'country' 	=> '<p><b>([^<]*)</b>: ([^<]*)<br>',
		'plot'		=> '#<b>Разпространител DVD:</b>([^^]*?)</table>|<b>Разпространител кино:</b>([^^]*?)</table>|<b>Разпространител кино:</b>([^^]*?)</table>#'
		);

	protected $multiArray = array(
		'genre', 'country'
	);



	private $servername = 'kino.dir.bg';
	private $searchpath = '/search.php?mmstring=All&keywords=[$]&type=0';
	private $itempath   = '/film.php?id=[$]';


	public function __construct() {
		$this->setSiteName("kinobg");
		$this->setFetchUrls($this->servername, $this->searchpath, $this->itempath);
	}


	public function search($title) {
		return parent::search($title);
	}

	public function showSearchResults() {

		$this->setMaxSearchResults(50);
	    $regx = '<a href=\"film.php\?id=([0-9]+)([^\<]*)\"><strong>([^\<]*)</strong></a><br>([^>]*)>([^>]*)>([^\<]*)';
		$results = parent::generateSimpleSearchResults($regx, 1,3,6);
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
					$obj->setImage($arrData[0]);
					break;

				case 'director':
					$director = $arrData[1];
					$obj->setDirector($director);
					break;

				case 'genre':

					$arr = array();
					foreach ($arrData as $item) {
						array_push($arr, $item[2]);
					}
					$obj->setGenre($arr);
					break;

				case 'rating':
					$rating = round($arrData[1]/10);
				    $obj->setRating($rating);
					break;

				case 'cast':
					$result = explode(", ", strip_tags($arrData[1]));
					$obj->setCast($result);
					break;

				case 'runtime':
					$runtime = $arrData[1];
					$obj->setRuntime($runtime);
					break;

				case 'akas':
				    $akaTitles = $arrData[1];
				    $obj->setAltTitle($akaTitles);
					break;

				case 'plot':
					$plot  = $arrData[1];
					$plot .= $arrData[2];
					$plot = str_replace("<br/ >", "\n", $plot);
					$plot = str_replace("<br>", "\n", $plot);
					$plot = strip_tags(trim($plot));
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
			default:
				break;
		}
	}







}









?>
