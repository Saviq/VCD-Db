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
 * @author  Jochen Schales <Jochen.Schales_at_gmx.de>
 * @package Kernel
 * @subpackage WebFetch
 * @version $Id: VCDFetch_ofdb.php
 */
?>
<?php
class VCDFetch_ofdb extends VCDFetch {

	protected $regexArray = array(
		'title' 	=> '<title>OFDb - ([^\(]*)\(([0-9]{4})\)</title>',
		'year'  	=> '<title>OFDb - ([^\(]*)\(([0-9]{4})\)</title>',
		'poster' 	=> '<img src="(images/film/[^"]*)"[^>]*>',
		'director' 	=> '#class="Normal">Regie:.*\n.*\n.*\n[^<]*<td><font [^>]*><b><a href="view.php.page=liste.Name[^>]*>([^<]*)</a>#i',
		'genre' 	=> '<a href="view.php.page=genre.Genre=[^"]*">([^<]*)</a>',
		'rating' 	=> '<br>Note: ([0-9].[0-9]{2}).nbsp;',
		'cast' 		=> '<a href="view.php.page=liste.Name[^>]*>([^<]*)</a>',
		'runtime' 	=> '#<b class="ch">Runtime:</b>\n([0-9]+) min#i',
		'akas' 		=> 'Also Known As</b>:</b><br>(.*)<b class="ch"><a href="/mpaa">MPAA</a>',
		'country' 	=> '<a href="view.php.page=blaettern.Kat=Land&Text=[^>]*>([^<]*)</a>',
		'plotshort'	=> '<b>Inhalt:</b> *([^<]*)<a href="view.php.page=inhalt.fid=[^>]*><b>\[mehr\]</b></a>',
		'linkplot'	=> '<b>Inhalt:</b>[^<]*<a href="([^"]*)"[^>]*><b>\[mehr\]</b></a>',
		'plot'		=> '/Eine Inhaltsangabe von <a href=\'usercenter\/info.php[^<]*<\/a><\/b><br><br>(.+?)(?=<\/font><\/p>)/is'
		);

	protected $multiArray = array(
		'genre', 'cast', 'akas', 'country'
	);



	private $servername = 'www.ofdb.de';
	private $searchpath = '/view.php?page=erwblaettern&Kat=Film&Titel=[$]&Darsteller=&Regie=&Land=-&Alter=-&Genre=-&Inhalt=&Submit=Suche+ausf%FChren';
	private $itempath   = '/view.php?page=film&fid=[$]&full=1';

	private $serverCharset = 'iso-8859-1';

	public function __construct() {
		$this->setSiteName("ofdb");
		$this->setFetchUrls($this->servername, $this->searchpath, $this->itempath);
	}


	public function search($title) {
	
		if ((VCDUtils::getCharSet() != $this->serverCharset)) $title = iconv(VCDUtils::getCharSet()."//TRANSLIT", $this->serverCharset, $title);
#		if (strcasecmp(VCDUtils::getCharSet(), $this->serverCharset)!= 0)
#			$title = mb_convert_encoding($title, $this->serverCharset, VCDUtils::getCharSet());
		return parent::search($title);
	}

	public function showSearchResults() {
		$this->setMaxSearchResults(50);
		$regx = '<a href=\"view\.php\?page=film.fid=([0-9]+)([^\<]*)\">([^\<\(]*)[^(]*\(([0-9]{4})\)</a>';
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
					$poster = "http://$this->servername/".$arrData[1];
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
					$rating = $arrData[1];
					$obj->setRating($rating);
					break;

				case 'cast':
					$arr = null;
					$arr = array();
					# remove director
					if (sizeof( $arrData ) > 0)
						array_shift( $arrData );
						
					foreach ($arrData as $itemArr) {
						$actor = $itemArr[1];
#						$role = $itemArr[3];
						$result = $actor;
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

#				case 'plotshort':
#					$plot = $arrData[1];
#					$obj->setPlot($plot);
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
				$linkPlot = $this->workerArray[10][1][1];
				
				$plotUrl = "http://$this->servername/$linkPlot";
				
				
				$referer = "http://".$this->servername.str_replace('[$]', $this->getItemID(), $this->itempath);
				$isPlot =  $this->fetchPage($this->servername, $plotUrl, $referer);
				if ($isPlot) {
					if ($this->getItem($this->regexArray['plot']) == self::ITEM_OK) {
						
						$plotArr = $this->getFetchedItem();
#						$plotText = $plotArr[1];
						$plotBadStr = array("…", "", "<br />", "<br>");
						$plotReplaceStr = array("...", "...", "", "");
						$plotText = str_replace($plotBadStr, $plotReplaceStr, $plotArr[1]);
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