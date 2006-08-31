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

?><?
class VCDFetch_filmweb extends VCDFetch {

	protected $regexArray = array(
	'title'		=> '#div class=\"tyt\">([^<]+)(?:<span|<br)#',
	'org_title' => '#class=\"styt\">([^(][^<]+)<\/span#',
	'year'		=> '#\(([0-9]{4})\)#',
	'poster'	=> '#solid Black;">[^"]+"([^"]+)" alt#',
	'director' 	=> '#yseria(?:[^>]*>[^<]+</a>)+\s*scenariusz#',
	'genre' 	=> 'genre.id=[0-9]+\">([^<]+)</a>',
	'rating' 	=> '#([0-9]{1,2}),([0-9]{1,2})<\/b>\/10#',
	'cast'		=> 'a class="n" title="[^>]+>([^<]+)</a>[^>]+>[^>]+>([^<]+)</td>',
	'runtime' 	=> '#trwania: ([0-9]+)#i',
	'country' 	=> 'country\.id=[0-9]+\">([^<]+)</a>',
	'plot'		=> '#"justify">(.*?)</li>#'
	);

	protected $multiArray = array('genre', 'cast', 'country');

	private $servername = 'www.filmweb.pl';
	private $itempath = '/Film?id=[$]';
	private $plotpath = '/FilmDescriptions?id=[$]';
	private $searchpath = '/Find?category=1&query=[$]';

	public function __construct() {
		$this->setSiteName("filmweb");
		$this->setFetchUrls($this->servername, $this->searchpath, $this->itempath);
	}

	public function search($title) {
		return parent::search($title);
	}

	public function showSearchResults() {
		$regx = "#<a title='([^\(]*?)(?: / ([^\(]*?))?(?: \(AKA (.*?)\))?[ ]*(?:\(I+\))?[ ]*\(([0-9]{4})\)(?:[ ]+\(([^\)]*)\))?' href=\"http://(?:(?:www\.filmweb\.pl/Film\?id=([0-9]+))|(?:([a-z0-9.]+)\.filmweb\.pl))\">#";
		preg_match_all($regx, $this->getContents(), $searchArr, PREG_SET_ORDER);
		$results = array();
		foreach($searchArr as $searchItem) {
			array_push($results, array('id' => ($searchItem[6]==""?$searchItem[7]:$searchItem[6]), 'title' => strip_tags($searchItem[1]), 'org_title' => $searchItem[2], 'year' => $searchItem[4], 'aka' => $searchItem[3]));
		}
		$this->generateSearchSelection($results);
	}

	protected function generateSearchSelection($arrSearchResults) {
		if (!is_array($arrSearchResults) || sizeof($arrSearchResults) == 0) {
			print "No search results to generate from.";
			return;
		}

		$testItem = $arrSearchResults[0];
		if (!isset($testItem['id']) || !isset($testItem['title']) || !isset($testItem['year']))	{
			throw new Exception('Results array must contain at least keys [id], [title] and [year]');
		}


		$extUrl = "http://".$this->servername.$this->itempath;
		print "<ul>";
		foreach ($arrSearchResults as $item) {
			$link = "?page=private&amp;o=add&amp;source=webfetch&site={$this->getSiteName()}&amp;fid={$item['id']}";
			if (is_numeric($item['id'])) $info = "http://filmweb.pl"."/Film?id=".$item['id'];
			else $info = "http://".$item['id'].".filmweb.pl";
			$str = "<li><a href=\"{$link}\">{$item['title']}</a> ({$item['year']})&nbsp;&nbsp;<a href=\"{$info}\" target=\"_new\">[info]</a>".($item['org_title']==""?"":"<br/>&nbsp;{$item['org_title']}").($item['aka']==""?"":"<i><br/>&nbsp;AKA ".str_replace(" / ", "<br/>&nbsp;&nbsp;&nbsp;", $item['aka'])."</i>")."</li>";
			print $str;
		}
		print "<ul>";

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

				case 'org_title':
					$title = $arrData[1];
					$obj->setAltTitle(trim($title));
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
							array_push($arrGenres, $itemArr[1]);
						}
					}
					$obj->setGenre($arrGenres);
					break;

				case 'rating':
					$rating = $arrData[1].$arrData[2];
					$rating = $rating/100;
					$obj->setRating($rating);
					break;

				case 'cast':
					$arr = null;
					$arr = array();
					foreach ($arrData as $itemArr) {
						$actor = $itemArr[1];
						$role = $itemArr[2];
						$result = $actor.($role==""?"":" .... ".$role);
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
				if (ereg("FilmUpdate,id=([0-9]+)", $this->getContents(), $id)) return $id[1];
				else return null;
			}
		} elseif(ereg("id=([0-9]+)", $this->getSearchRedirectUrl(), $id)) {
			return $id[1];
		} else {
			ereg("http://([^/]+)(/.*)?", $this->getSearchRedirectUrl(), $redirect);
			$isPage = $this->fetchPage($redirect[1], ($redirect[2]!=""?$redirect[2]:"/"), "www.filmweb.pl", false);
			if (ereg("FilmUpdate,id=([0-9]+)", $this->getContents(), $id)) return $id[1];
			else return null;
		}
	}
}
?>