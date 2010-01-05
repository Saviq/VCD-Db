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
 * @author  Michał Sawicz <michal@sawicz.net>
 * @package Kernel
 * @subpackage WebFetch
 * @version $Id$
 */

?>
<?php
class VCDFetch_filmweb extends VCDFetch {

	protected $regexArray = array(
	'title'		=> '#<h1[^<]+<a[^>]+>([^<]+)</a></h1>#',
	'org_title'	=> '#</h1[^<]+<span class="aka">([^</]+)</span>#',
	'alt_title'	=> '#\(AKA ((?:[^(/]|\(?:I+\))+)\)#',
	'year'		=> '#\(([0-9]{4})\)#',
	'poster'	=> '#<img src="([^\?]+\.2\.jpg)\?#',
	'director' 	=> '#yseria(?:[^>]*>[^<]+</a>)+\s*scenariusz#',
	'genre' 	=> 'genreIds[^>]*>([^<]*)</a>',
	'rating' 	=> '#([0-9]{1,2})(?:,([0-9]{1,2}))?</strong>/10#',
	'cast'		=> '<td class="film-actor">[^>]+>[^>]+>([^<]+)</a>[^>]+>[^<]+<td class="film-protagonist">([^<]+)<span>',
	'runtime' 	=> '#trwania: ([0-9]+)#i',
	'country'	=> 'countryIds[^>]*>([^<]*)</a>',
	'plot'		=> '#justify">((?:.|\n)*?)</li>#m'
	);

	protected $multiArray = array('genre', 'cast', 'country');

	private $servername = 'www.filmweb.pl';
	private $itempath = '/f[$]/x,0';
	private $plotpath = '/f[$]/x,0/opisy';
	private $searchpath = '/szukaj?q=[$]&alias=film';

	public function __construct() {
		$this->setSiteName("filmweb");
		$this->useSnoopy();
		$this->setFetchUrls($this->servername, $this->searchpath, $this->itempath);
	}

	public function search($title) {
		return parent::search($title);
	}

	protected function fetchPage($host, $url, $referer, $useCache=true, $header=null) {
		$fetch = parent::fetchPage($host, $url, $referer, $useCache, $header);
		foreach(array_merge(array($this->getContents()), $this->snoopy->headers) as $v) {
			if(preg_match("#Set-Cookie: (welcomeScreen)=([^;]+);#", $v, $cookie)) {
				$this->headers['Cookie'] = "{$cookie[1]}={$cookie[2]};";
				$fetch = parent::fetchPage($host, $url, $referer, false, null);
			}
		}
		return $fetch;	
	}

	public function showSearchResults() {
		$regx = '#(?: none;">\((?P<info>[^\)]*)\)</span>[^<]*)?' // additional info
		.'<a class="searchResultTitle"\s*href=\"http://(www.filmweb.pl/f(?P<id>[0-9]+)/[^"]+|' // numeric id 
		.'(?P<lid>[a-z0-9.]+).filmweb.pl/)"[^>]*>' // alphanumeric id
		.'\s*(?P<title>.*?)\s+(?:/\s+(?P<org_title>.*?))?\s*' // title / original title
		.'</a>[^\(]*\((?P<year>[0-9]{4}(?:-[0-9]{4})?)\)' // year
		.'(?:[^<]*<span[^<]*<br/>aka:\s*(?P<aka>.*)[^<]*</span>)?#'; // AKA
		preg_match_all($regx, $this->getContents(), $searchArr, PREG_SET_ORDER);
		$results = array();
		foreach($searchArr as $searchItem) {
			array_push($results, array('id' => (empty($searchItem['id'])?$searchItem['lid']:$searchItem['id']), 'title' => VCDUtils::titleFormat($searchItem['title']), 'org_title' => VCDUtils::titleFormat($searchItem['org_title']), 'year' => $searchItem['year'], 'aka' => trim($searchItem['aka']), 'info' => trim($searchItem['info'])));
		}
		$partresults[$partname[1]] = $results;
		return $this->generateSearchSelection($partresults);
	}

	protected function generateSearchSelection($arrPartSearchResults) {
		if (!is_array($arrPartSearchResults) || sizeof($arrPartSearchResults) == 0) {
			return "No search results to generate from.";
		}

		$result = "";
		foreach ($arrPartSearchResults as $partName => $arrSearchResults) {
			if (!is_array($arrSearchResults) || sizeof($arrSearchResults) == 0) continue;
			$testItem = $arrSearchResults[0];
			if (!isset($testItem['id']) || !isset($testItem['title']) || !isset($testItem['year']))	{
				throw new Exception('Results array must contain at least keys [id], [title] and [year]');
			}
			$result .= "<h3>".$partName."</h3>\n";

			$extUrl = "http://".$this->servername.$this->itempath;
			$result .= "<ul>";
			foreach ($arrSearchResults as $item) {
				$link = "?page=add&amp;source=webfetch&site={$this->getSiteName()}&amp;fid={$item['id']}";
				if (is_numeric($item['id'])) $info = "http://filmweb.pl"."/Film?id=".$item['id'];
				else $info = "http://".$item['id'].".filmweb.pl";
				$result .= "<li><a href=\"{$link}\">{$item['title']}</a> ".(empty($item['info'])?"":"[".strtolower($item['info'])."] ")."({$item['year']})&nbsp;&nbsp;<a href=\"{$info}\" target=\"_new\">[info]</a>".(empty($item['org_title'])?"":"<br/>&nbsp;{$item['org_title']}").($item['aka']==""?"":"<i><br/>&nbsp;AKA ".str_replace(" / ", "<br/>&nbsp;&nbsp;&nbsp;", $item['aka'])."</i>")."</li>";
				
			}
			$result .= "</ul>";
		}
		return $result;
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
					$title = VCDUtils::titleFormat($arrData[1]);
					$obj->setTitle($title);
					break;

				case 'org_title':
					if(!ereg("^\([0-9]{4}\)$", trim($arrData[1])) && !ereg("^\(AKA", trim($arrData[1]))) {
						$org_title = VCDUtils::titleFormat($arrData[1]);
						$obj->setAltTitle($org_title);
					}
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
					$obj->setImage($poster);
					break;

				case 'director':
					preg_match_all("#>([^<]+)</a>#", $arrData[0], $arrDirectors);
					$director = implode(", ", $arrDirectors[1]);
					$obj->setDirector($director);
					break;

				case 'genre':
					if (sizeof($arrData) > 0) {
						$arrGenres = array();
						foreach ($arrData as $itemArr) {
							array_push($arrGenres, trim($itemArr[1]));
						}
					}
					$obj->setGenre($arrGenres);
					break;

				case 'rating':
					if(isset($arrData[2])) {
						$rating = $arrData[1].".".$arrData[2];
					} else {
						$rating = $arrData[1];
					}
					$obj->setRating($rating);
					break;

				case 'cast':
					$arr = array();
					foreach ($arrData as $itemArr) {
						$actor = trim($itemArr[1]);
						$role = trim(str_replace("&nbsp;", " ", $itemArr[2]));
						$result = $actor.($role==""?"":" .... ".$role);
						array_push($arr, $result);
					}
					$obj->setCast($arr);
					break;

				case 'runtime':
					$runtime = $arrData[1];
					$obj->setRuntime($runtime);
					break;

				case 'plot':
					$plot = trim(strip_tags(str_replace("<br/>", "\n", $arrData)));
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
			else {
				$isPage = $this->fetchPage($this->getID().".filmweb.pl", "/", "www.filmweb.pl", false);
				if (ereg("filmId = ([0-9]+);", $this->getContents(), $id)) return $id[1];
				else return null;
			}
		} elseif(ereg("/f([0-9]+)/", $this->getSearchRedirectUrl(), $id)) {
			return $id[1];
		} else {
			ereg("http://([^/]+)(/.*)?", $this->getSearchRedirectUrl(), $redirect);
			$isPage = $this->fetchPage($redirect[1], ($redirect[2]!=""?$redirect[2]:"/"), "www.filmweb.pl", false);
			if (ereg("filmId = ([0-9]+);", $this->getContents(), $id)) return $id[1];
			else return null;
		}
	}
}
?>
