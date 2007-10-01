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
 * @version $Id: VCDPagePornstars.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php

class VCDPagePornstars extends VCDBasePage {
	
	
	public function __construct(_VCDPageNode $node) {
		
		parent::__construct($node);
		
		$this->doAlphabetList();

		$this->assign('view',$this->getParam('view'));
		$this->assign('mode',$this->getParam('viewmode'));
		
		$letter = $this->getParam('l');
		if (!is_null($letter)) {
			$this->assign('selectedLetter',$letter);
			$this->doTextList($letter);
		}
		

	}
	
	
	private function doTextList($letter) {
	
		$active = ($this->getParam('view') == 'active');
		$pornstars = PornstarServices::getPornstarsByLetter($letter, $active);
		$this->assign('viewmode', 'list');
		
		$results = array();
		
		foreach ($pornstars as $pornstarObj) {
			$results[$pornstarObj->getID()] = array(
				'name' => $pornstarObj->getName(),
				'homepage' => $pornstarObj->getHomepage(),
				'count' => $pornstarObj->getMovieCount()
			);
		}
		
		$this->assign('pornstars', $results);
		
	}
	
	private function doAlphabetList() {
		
		$active = ($this->getParam('view') == 'active');
		$alpabet = PornstarServices::getPornstarsAlphabet($active);
		if ($active) {
			asort($alpabet);
		}
		
		$this->assign('alphabet', $alpabet);
		
	}
	
	
}


?>