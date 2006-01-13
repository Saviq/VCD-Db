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
	
	
	private $regexArray = array(
		'title' 	=> '<STRONG CLASS=\"title\">([^\<]*) <SMALL>\(<A HREF=\"/Sections/Years/([0-9]{4})',
		'year'  	=> '<STRONG CLASS=\"title\">([^\<]*) <SMALL>\(<A HREF=\"/Sections/Years/([0-9]{4})',
		'poster' 	=> '/<img border="0" alt="cover" src="([^"]+)"/is',
		'director' 	=> '#Directed by.*\n[^<]*<a href="/Name?[^"]*">([^<]*)</a>#i',
		'genre' 	=> '<A HREF=\"/Sections/Genres/[a-zA-Z\\-]*/\">([a-zA-Z\\-]*)</A>',
		'rating' 	=> '<B>([0-9]).([0-9])/10</B> \([0-9,]+ votes\)',
		'cast' 		=> '<td valign="top"><a href="/name/nm([^"]+)">([^<]*)</a></td><td valign="top" nowrap="1"> .... </td><td valign="top">([^<]*)</td>',
		'runtime' 	=> '#<b class="ch">Runtime:</b>\n([0-9]+) min#i',
		'akas' 		=> 'Also Known As</b>:</b><br>(.*)<b class="ch"><a href="/mpaa">MPAA</a>',
		'plot'		=> '<p class="plotpar">([^<]*)</p>',
		'country' 	=> '<a href="/Sections/Countries/([^>]*)>([^<]*)</a>'
		);
		
	private $multiArray = array(
		'genre', 'cast', 'akas', 'country'
	);
	
	private $workerArray = array();
	private $resultArray = array();
	
		
	
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
		
		$this->setMaxSearchResults(40);
		$regx = '<a href=\"\/title\/tt([0-9]+)\/([^\<]*)\">([^\<]*)</a>';
		$results = parent::generateSimpleSearchResults($regx, 1, 3);
		parent::generateSearchSelection($results);
	}
	
	public function toString() {
		print "<pre>";
		print_r($this->workerArray);
		print "</pre>";
	}
	
	
	protected function processResults() {
		if (!is_array($this->workerArray) || sizeof($this->workerArray) == 0) {
			print "No results to process";
			return;
		}
		
		foreach ($this->workerArray as $key => $data) {
			print $key . "<br>";			
			
		}
		
		
		
	}
	
	protected function fetchDeeper($entry) {

		switch ($entry) {
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
						$this->setContents($buffer);
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
	
	
	/**
	 * Try to featch each item in $regexArray the simple way, call the fetchDeeper() on failure
	 * for deeper processing.  Each success item is pushed into array $resultArray
	 *
	 */
	public function fetchValues() {
		foreach ($this->regexArray as $entry => $regex) {
			$multi = (bool)in_array($entry, $this->multiArray);
			if ($this->getItem($regex, $multi) == self::ITEM_OK ) {
				array_push($this->workerArray, $this->getFetchedItem());
			} else {
				$this->fetchDeeper($entry);
			}
		}
		
		// and finally process the results
		$this->processResults();
		
	}
	
	
	
	
	
}









?>