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
 * @author  Gian <mcaghi@tin.it>
 * @package Kernel
 * @subpackage WebFetch
 * @version $Id: VCDFetch_filmtv.php,v 1.24 2007/01/26 00:08:07 Saviq Exp $
 */

?>
<?php
class VCDFetch_filmtv extends VCDFetch {

	protected $regexArray = array(
		'title'		=> 'fTitolo[^>]*>([^<]*)<\/',
		'org_title'	=> 'Titolo originale([^>]*>){2}([^<]*)<\/td>',
	#	'alt_title'	=> '#<span class=\"otherTitle\">[^(]+\(AKA (([^(/]|\(I+\))+)\)#',
		'year'		=> 'Anno<\/td>[^>]*>([0-9]{4})',
		'poster'	=> 'src="(imgbank[^"]*)" width',
		'director'=> 'Regia<\/td>([^>]*>){5}::([^>]*>){4}([^<]*)<\/a>',
		'genre' 	=> 'Genere<\/td>[^>]*>([^<]*)<\/td>',
		'rating' 	=> 'il voto di FilmTV([^"]*"){5}img\/pollici_testata\/([0-9])\.gif',
		'cast'		=> 'persona=[0-9]+[^>]*>([^<]*)<\/a><\/td>',
		'runtime' => "Durata<\/td>[^>]*>([^']*)'<\/td>",
		'country'	=> 'Produzione<\/td>[^>]*>([^<]*)<\/td>',
		'plot'		=> 'La Trama([^>]*>){3}([^<]*)<'
	);

	protected $multiArray = array('genre', 'cast', 'country');

	private $servername = 'www.film.tv.it';
	private $itempath = '/scheda.php?film=[$]';
	private $plotpath = '/FilmDescriptions?id=[$]';
	private $searchpath = '/cerca.php?q=[$]';

	public function __construct() {
		$this->setSiteName("filmtv");
		$this->setFetchUrls($this->servername, $this->searchpath, $this->itempath);
	}

	public function search($title) {
		return parent::search($title);
	}

	public function showSearchResults() {
		$this->setMaxSearchResults(50);
		$regx = 'scheda\.php\?film=([0-9]+)[^<]*\">[^\>]*>([^<]*)</a>[^(]*\(([^)]*))';
		$results = parent::generateSimpleSearchResults($regx, 1,2,3);
		return parent::generateSearchSelection($results);
	}

	protected function processResults() {
		if (!is_array($this->workerArray) || sizeof($this->workerArray) == 0) {
			$this->setErrorMsg("No results to process.");
			return;
		}

		$obj = new imdbObj();
#		$obj->setIMDB($this->getItemID());
		$obj->setObjectID($this->getItemID());
		foreach ($this->workerArray as $key => $data) {
			$entry = $data[0];
			$arrData = $data[1];

			switch ($entry) {
				case 'title':
					$title = VCDUtils::titleFormat($arrData[1]);
					$obj->setTitle($title);
					break;

				case 'org_title':
					$org_title = VCDUtils::titleFormat($arrData[2]);
					$obj->setAltTitle($org_title);
					break;

				case 'alt_title':
					if($obj->getAltTitle() == "") {
						$alt_title = VCDUtils::titleFormat($arrData[1]);
						$obj->setAltTitle($alt_title);
					}
					break;

				case 'year':
					$year = $arrData[1];
					$obj->setYear($year);
					break;

				case 'poster':
					$poster = $arrData[1];
					$obj->setImage("http://".$this->servername.'/'.$poster);
					break;

				case 'director':
					$director = $arrData[3];
					$obj->setDirector($director);
					break;

				case 'genre':
					if (sizeof($arrData) > 0) {
						$arrGenres = array();
						foreach ($arrData as $itemArr) {
							array_push($arrGenres, $itemArr[1]);
						}
					}
					$obj->setGenre($arrGenres);
					break;

				case 'rating':
					$rating = $arrData[2] * 2.5;
					$obj->setRating($rating);
					break;

				case 'cast':
					$arr = null;
					$arr = array();
					foreach ($arrData as $itemArr) {
						#$actor = trim($itemArr[1]);
						#$role = trim(str_replace("&nbsp;", " ", $itemArr[3]));
						#$result = $actor.($role==""?"":" .... ".$role);
						#array_push($arr, $result);
						# quii da mettere a posta: mostra anche il regista.
						array_push($arr, $itemArr[1]);
					}
					unset ($arr[0]);
					$arr = array_values($arr);
					$obj->setCast($arr);
					break;

				case 'runtime':
					$runtime = $arrData[1];
					$obj->setRuntime($runtime);
					break;

				case 'plot':
					#$plot = trim(strip_tags(str_replace("<br/>", "\n", $arrData)));
					$plot = $arrData[2];
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

	protected function fetchDeeper($entry) {

		switch ($entry) {
			case 'plot':

				// Save the old buffer
				$itemBuffer = $this->getContents();
				// Generate urls
				$plotUrl = str_replace('[$]', $this->getItemID(), $this->plotpath);
				$referer = "http://".$this->servername.str_replace('[$]', $this->getItemID(), $this->plotpath);
				$isPlot = $this->fetchPage($this->servername, $plotUrl, $referer);
				if ($isPlot) {
					if ($this->getItem($this->regexArray['plot']) == self::ITEM_OK) {
						$plotArr = $this->getFetchedItem();
						$plotText = $plotArr[1];
						array_push($this->workerArray, array($entry, $plotText));
					}
				}
				break;
						
			default:
				break;
		}
	}




	protected function getItemID() {
		if (is_null($this->getSearchRedirectUrl())) {
			if(is_numeric($this->getID())) return $this->getID();
			return null;
		} elseif(ereg("film=([0-9]+)", $this->getSearchRedirectUrl(), $id)) {
			return $id[1];
		} else {
			return null;
		}
	}
}
?>