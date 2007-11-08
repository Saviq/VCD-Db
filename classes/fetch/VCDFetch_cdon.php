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
 * @author Hákon Birgisson <konni@konni.com>
 * @package Kernel
 * @subpackage WebFetch
 * @version $Id: VCDFetch_cdon.php,v 1.24 2007/01/26 00:08:07 Saviq Exp $
 */

?>
<?php
class VCDFetch_cdon extends VCDFetch {

	protected $regexArray = array(
		'title'		=> 'Titel:</td>([^<]*)<td class=\"moviedetailtext\">([^<]*)</td>',
		'alt_title'	=> 'Originaltitel:</td>([^<]*)<td class=\"moviedetailtext\">([^<]*)</td>',
		'year'		=> 'Inspelnings.r:</td>([^<]*)<td class=\"moviedetailtext\">([0-9]{4})</td>',
		'poster'	=> 'moviedetail\" border=\"0\" src=\"([^"]*)" class=\"moviedetail\"',
		'director'=> 'Regia<\/td>([^>]*>){5}::([^>]*>){4}([^<]*)<\/a>',
		'genre' 	=> 'Genere<\/td>[^>]*>([^<]*)<\/td>',
		'rating' 	=> 'il voto di FilmTV([^"]*"){5}img\/pollici_testata\/([0-9])\.gif',
		'cast'		=> 'persona=[0-9]+[^>]*>([^<]*)<\/a><\/td>',
		'runtime' => "Durata<\/td>[^>]*>([^']*)'<\/td>",
		'country'	=> 'Produzione<\/td>[^>]*>([^<]*)<\/td>',
		'plot'		=> 'La Trama([^>]*>){3}([^<]*)<'
	);

	protected $multiArray = array('genre', 'cast', 'country');

	private $servername = 'www.hyrfilm.cdon.com';
	private $itempath = '/movie/detail.asp?MovieId=[$]';
	private $searchpath = '/movie/?Sort=1&Mode=4&SValue=[$]';

	public function __construct() {
		$this->useSnoopy();
		$this->setSiteName("cdon");
		$this->setFetchUrls($this->servername, $this->searchpath, $this->itempath);
	}

	public function search($title) {
		return parent::search($title);
	}

	public function showSearchResults() {
		$this->setMaxSearchResults(50);
		$regx = 'detail.asp\?MovieId=([0-9]{1,6})">([^<]*)</a>';
		$results = parent::generateSimpleSearchResults($regx, 1,2);
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
					$title = VCDUtils::titleFormat($arrData[2]);
					$obj->setTitle($title);
					break;

				case 'alt_title':
					$altTitle = VCDUtils::titleFormat($arrData[2]);
					$obj->setAltTitle($altTitle);
					break;
					
				case 'year':
					$obj->setYear($arrData[2]);
					break;
					
				case 'poster':
					$poster = $arrData[1];
					$obj->setImage($poster);
					break;


				default:
					break;
			}

		}
		$this->fetchedObj = $obj;
	}

	protected function fetchDeeper($entry) {

	}

}
?>