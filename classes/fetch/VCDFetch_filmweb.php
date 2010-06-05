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
	'title'		=> '#<h1 class="pageTitle"><a[^>]+><span[^>]+></span>\s*([^<]+)\s*</a>#',
	'org_title'	=> '#<h2 class="original-title">\s*[^<]+?\s*<#',
	'alt_title'	=> '#<dt>inne tytuły:</dt>\s*<dd>(.*?)</dd>#',
	'year'		=> '#filmYear">(\d{4})</span>#',
	'poster'	=> '#<img src="([^\?]+\.2\.jpg)\?#',
	'director' 	=> '#reżyseria:</th>\s*<td>\s*<a[^>]+>([^<]+)</a>#',
	'genre' 	=> '#genreIds[^>]*>([^<]*)</a>#',
	'rating' 	=> '#<strong>\s*([0-9]{1,2})(?:,([0-9]{1,2}))?\s*</strong>#',
	'cast'		=> '#<span>\s*([^<]*)\s*</span>\s*</a>\s*</h3>\s*<div>\s*([^<]*)(?:\s*<span[^>]+>\(([^\)]+)\)</span>\s*)?</div>#',
	'runtime' 	=> '#class="time">\s*(\d+)\s*<span>#',
	'country'	=> '#countryIds[^>]*>([^<]*)</a>#',
	'plot'		=> '#span class="filmDescrBg">\s*(.*?)\s*</span#s'
	);

	protected $multiArray = array('genre', 'cast', 'country');

	private $servername = 'www.filmweb.pl';
	private $itempath = '/film/a-0-[$]';
	private $searchpath = '/search?q=[$]&alias=film';

	public function __construct() {
		$this->setSiteName("filmweb");
		$this->useSnoopy();
		$this->setFetchUrls($this->servername, $this->searchpath, $this->itempath);
	}

	public function search($title) {
		return parent::search($title);
	}

	protected function fetchPage($host, $url, $referer, $useCache=true, $header=null) {
		$this->cookies['welcomeScreen'] = 'welcomeScreen';
		$this->cookies['welcomeScreenNew'] = 'welcomeScreen';
		$fetch = parent::fetchPage($host, $url, $referer, $useCache, null);
		foreach(array_merge(array($this->getContents()), $this->snoopy->headers) as $v) {
			if(preg_match("#Set-Cookie: (welcomeScreen)=([^;]+);#", $v, $cookie)) {
				$this->cookie['welcomeScreen'] = $cookie[2];
				$fetch = parent::fetchPage($host, $url, $referer, false, null);
				break;
			}
		}
		return $fetch;	
	}

	public function showSearchResults() {
		$regx = '#(?:<span class="searchResultTypeAlias">\[(?P<info>[^\]]+)\]</span><br>\s*)?' // additional info
		.'<h3><a class="searchResultTitle" href="(?:/[^/]+/[\w%+-]+-\d{4}-(?P<id>\d+)|/(?P<lid>[\w%+\.-]+))">\s*' // numeric / textual id 
		.'(?P<title>.+?)(?: / (?P<org_title>.+?))?\s*</a></h3>\s*' // title / original title
		.'(<span class="searchResultOtherTitle">\s*aka: (?P<aka>.*?)\s*</span>\s*<br>\s*)?' // AKA
		.'<span class="searchResultDetails">\s*(?P<year>\d{4})\s*\|(\s*<a href="/search/film\?countryIds=\d+">([^<]+)</a>(?:,\s|))+\s*\|#'; // year
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

			$result .= "<ul>";
			foreach ($arrSearchResults as $item) {
				$link = "?page=add&amp;source=webfetch&site={$this->getSiteName()}&amp;fid={$item['id']}";
				if (is_numeric($item['id'])) $info = str_replace('[$]', $item['id'], sprintf('http://%s%s', $this->servername, $this->itempath));
				else $info = sprintf('http://%s/%s', $this->servername, $item['id']);
				$result .= "<li><a href=\"{$link}\">{$item['title']}</a> ".(empty($item['info'])?"":"[".strtolower($item['info'])."] ")."({$item['year']})&nbsp;&nbsp;<a href=\"{$info}\" target=\"_new\">[info]</a>".(empty($item['org_title'])?"":"<br/>&nbsp;{$item['org_title']}").($item['aka']==""?"":"<i><br/>&nbsp;AKA ".str_replace(" / ", "<br/>&nbsp;&nbsp;&nbsp;", $item['aka'])."</i>")."</li>";
				
			}
			$result .= "</ul>";
		}
		return $result;
	}

	protected function processResults() {
		if (!is_array($this->workerArray) || sizeof($this->workerArray) == 0) {
			$this->setErrorMsg("No results to process.");
			return false;
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
					$org_title = VCDUtils::titleFormat($arrData[1]);
					$obj->setAltTitle($org_title);
					break;

				case 'alt_title':
					$alt_title = preg_split('#\<br\>#', $arrData[1], null, PREG_SPLIT_NO_EMPTY);
					if(($org_title = $obj->getAltTitle()) != "") {
						array_unshift($alt_title, $org_title);
					}
					$obj->setAltTitle(implode(' / ', $alt_title));
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
						if(!empty($itemArr[3])) $role .= sprintf(" (%s)", $itemArr[3]);
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
					$plot = strip_tags(str_replace("<br/>", "\n", $arrData[1]));
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
	}

	protected function getItemID() {
		if(is_numeric($this->getID())) return $this->getID();
		else {
			$isPage = $this->fetchPage($this->servername, "/".$this->getID(), $this->servername);
			if (preg_match('#<div id="filmId" style="display:none;">(?P<id>\d+)</div>#', $this->getContents(), $id)) return $id['id'];
			else return null;
		}
	}
}
?>
