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
		'genre' 	=> '#Genre:</b>(.*?)<br>#msi',
		'rating' 	=> '<B>([0-9]).([0-9])/10</B> \([0-9,]+ votes\)',
		'cast' 		=> '<td valign="top"><a href="/name/nm([^"]+)">([^<]*)</a></td><td valign="top" nowrap="1"> .... </td><td valign="top">([^<]*)</td>',
		'plot'		=> '<p class="plotpar">([^<]*)</p>',
		'runtime' 	=> '#<b class="ch">Runtime:</b>\n([0-9]+) min#i',
		'akas' 		=> 'Also Known As</b>:</b><br>(.*)<b class="ch"><a href="/mpaa">MPAA</a>',
		'country' 	=> '<a href="/Sections/Countries/([^>]*)>([^<]*)</a>'
		
		
		
		
		
		);
	
	
	
	public function __construct() {
		$this->setSiteName("imdb");
	}
	
	
	public function showSearchResults() {
				
		
	}
	
	
	public function search() {
		
		$this->fetchPage('www.imdb.com', '/title/tt0360717/', 'http://akas.imdb.com');
		
		
		foreach ($this->regexArray as $item => $value) {
			if ($this->getItem($value) == parent::ITEM_OK ) {
				print "<br>br>";
				print_r($this->getFetchedItem());
			} 
		}
		
		
	}
	
	
	
	
	
	
	
	
	
}









?>