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
class VCDFetch_imdb extends VCDFetch {


	protected $regexArray = array(
		'title' 	=> '<STRONG CLASS=\"title\">([^\<]*) <SMALL>\(<A HREF=\"/Sections/Years/([0-9]{4})',
		'year'  	=> '<STRONG CLASS=\"title\">([^\<]*) <SMALL>\(<A HREF=\"/Sections/Years/([0-9]{4})',
		'poster' 	=> '<a name="poster"([^<]*)><img([^<]*)([^<]*)src="([^<]*)" height="([0-9]{2,3})" width="([0-9]{2,3})"></a>',
		'director' 	=> '#Directed by.*\n[^<]*<a href="/Name?[^"]*">([^<]*)</a>#i',
		'genre' 	=> '<A HREF=\"/Sections/Genres/[a-zA-Z\\-]*/\">([a-zA-Z\\-]*)</A>',
		'rating' 	=> '<B>([0-9]).([0-9])/10</B> \([0-9,]+ votes\)',
		'cast' 		=> '<td valign="middle"><a href="/name/nm([^"]+)">([^<]*)</a></td><td valign="middle" nowrap="1"> .... </td><td valign="middle">([^<]*)</td>',
		'runtime' 	=> '#<b class="ch">Runtime:</b>\n([0-9]+) min#i',
		'akas' 		=> 'Also Known As</b>:</b><br>(.*)<b class="ch"><a href="/mpaa">MPAA</a>',
		'country' 	=> '<a href=\"/Sections/Countries/([^>]*)>([^<]*)</a>',
		'plot'		=> '<p class="plotpar">([^<]*)</p>'
		);

	protected $multiArray = array(
		'genre', 'cast', 'akas', 'country'
	);



	private $servername = 'akas.imdb.com';
	private $searchpath = '/find?q=[$]';
	private $itempath   = '/title/tt[$]/';


	public function __construct() {
		$this->setSiteName("imdb");
		$this->setFetchUrls($this->servername, $this->searchpath, $this->itempath);
	}


	public function search($title) {
		return parent::search($title);
	}

	public function showSearchResults() {

		$this->setMaxSearchResults(50);
		$regx = '<a href=\"\/title\/tt([0-9]+)\/([^\<]*)\">([^\<]*)</a>[^(]*\(([0-9]{4})\)';
		$results = parent::generateSimpleSearchResults($regx, 1, 3, 4);
		parent::generateSearchSelection($results);
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
						$actor = $itemArr[2];
						$role = $itemArr[3];
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
					if ($this->getItem($this->regexArray['plot']) == self::ITEM_OK) {

						$plotArr = $this->getFetchedItem();
						$plotText = $plotArr[1];
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
