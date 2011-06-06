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
 * @subpackage Controller
 * @version $Id: VCDPagePornstars.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php
class VCDPagePornstars extends VCDBasePage {
	
	private $viewMode = 'text';
	private $letter = 'a';
	
	/**
	 * Class constructor
	 *
	 * @param _VCDPageNode $node
	 */
	public function __construct(_VCDPageNode $node) {
		try {
		
			parent::__construct($node);
		
			$this->doAlphabetList();
	
			$this->letter = $this->getParam('l',false,'a');
			$this->viewMode = $this->getParam('viewmode',false,'text');
			
			$this->assign('view',$this->getParam('view'));
			$this->assign('mode',$this->viewMode);
			
			$letter = $this->getParam('l');
			if (!is_null($letter)) {
				$this->assign('selectedLetter',$letter);
				if (strcmp($this->viewMode,'img') == 0) {
					$this->doImageList($this->letter);
				} else {
					$this->doTextList($this->letter);	
				}
			}
				
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	/**
	 * Populate the image list
	 *
	 * @param string $letter | The letter that the pornstar names begin with
	 */
	private function doImageList($letter) {

		$active = ($this->getParam('view') == 'active');
		$pornstars = PornstarServices::getPornstarsByLetter($letter, $active);
		$this->assign('viewmode', 'images');
		$this->assign('pornstarCount', sizeof($pornstars));
		
		$results = array();
		foreach ($pornstars as $pornstarObj) {
			$results[$pornstarObj->getID()] = array(
				'name' => $pornstarObj->getName(),
				'image' => $pornstarObj->getImageLink()
			);
		}
		
		$this->assign('pornstars', $results);
		
	}
	
	/**
	 * Populate the text list
	 *
	 * @param string $letter | The letter that the pornstar names begin with
	 */
	private function doTextList($letter) {
	
		$active = ($this->getParam('view') == 'active');
		$pornstars = PornstarServices::getPornstarsByLetter($letter, $active);
		$this->assign('viewmode', 'list');
		$this->assign('pornstarCount', sizeof($pornstars));
		
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
	
	/**
	 * Populate the alphabet navigator list
	 *
	 */
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