<?php
/**
 * VCD-db - a web based VCD/DVD Catalog system
 * Copyright (C) 2003-2007 Konni - konni.com
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * @author  Hákon Birgisson <konni@konni.com>
 * @package Kernel
 * @version $Id: VCDPageSearch.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php

class VCDPageSearch extends VCDBasePage {
	
	public function __construct(_VCDPageNode $node) {

		parent::__construct($node);
		
		$this->handleSearchResults();
		
	}
	
	
	private function handleSearchResults() {
		
		$searchString = $this->getParam('searchstring');
		$method = $this->getParam('by', false,'title');
		
		
		if (is_null($searchString)) {
			return;
		}
		
		// remember last search method
		$_SESSION['searchkey'] = $method;
		
		$movies = MovieServices::search($searchString, $method);
		
		if (is_array($movies) && sizeof($movies) > 0) {
			
			// Redirect to item if results contain only 1 item
			if (sizeof($movies) == 1) {
				
				$movie = $movies[0];
				redirect('?page=cd&vcd_id='.$movie->getID());
				exit();
				
			} else {
				$results = array();
				foreach ($movies as $vcdObj) {
					$results[] = array('id' => $vcdObj->getID() , 
						'title' => $vcdObj->getTitle(), 
						'year' => $vcdObj->getYear(),
						'mediatypes' => $vcdObj->showMediaTypes());
				}
				
				$this->assign('searchResults', $results);
				
			}
			
		}
		
		
	}
	
}
?>