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
 * @version $Id: VCDPagePornstarSelector.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php
class VCDPagePornstarSelector extends VCDBasePage {
	
	/**
	 * The movie ID that called the window
	 *
	 * @var int
	 */
	private $itemId = null;
	
	public function __construct(_VCDPageNode $node) {
		parent::__construct($node);
		$this->itemId = $this->getParam('vcd_id');
		if (is_null($this->itemId)) {
			throw new VCDInvalidArgumentException('Invalid caller Id.');
		}
		
		// Register javascripts ..
		$this->registerScript(self::$JS_MAIN);
		
		$this->initPage();
				
	}
	
	
	/**
	 * Handle _POST requests
	 *
	 */
	public function handleRequest() {
		try {
			
			$this->itemId = $this->getParam('vcd_id');
			if (is_null($this->itemId)) {
				throw new VCDInvalidArgumentException('Invalid caller Id.');
			}
			
			if (!is_null($this->getParam('updatename',true))) {
				
				$starname = $this->getParam('newstar',true);
				if (!is_null($starname)) {
					PornstarServices::addPornstar(new pornstarObj(array('',$starname,'','')));
				}
				
			} elseif (!is_null($this->getParam('updateboth',true))) {
				
				$starname = $this->getParam('newstar',true);
				if (!is_null($starname)) {
					$pornstarObj = PornstarServices::addPornstar(new pornstarObj(array('',$starname,'','')));
					PornstarServices::addPornstarToMovie($pornstarObj->getID(), $this->itemId);
				}
				
			} elseif (!is_null($this->getParam('update',true))) {
				
				$list = $this->getParam('id_list',true);
				if (!is_null($list)) {
					$stars = explode('#',$list);
					foreach ($stars as $id) {
						PornstarServices::addPornstarToMovie($id,$this->itemId);
					}
				}
				
				redirect('?page=addpornstars&vcd_id='.$this->itemId.'&close=true');
				
			}
			
		} catch (Exception $ex) {
			VCDException::display($ex,true);	
		}
	}
	
	/**
	 * Initialize the page
	 *
	 */
	private function initPage() {
		
		$stars = PornstarServices::getAllPornstars();
		
		$added = array();
		foreach (PornstarServices::getPornstarsByMovieID($this->itemId) as $pornstarObj) {
			$added[] = $pornstarObj->getID();
		}
		
		$results = array();
		foreach ($stars as $pornstarObj) {
			if (!in_array($pornstarObj->getID(), $added)) {
				$results[$pornstarObj->getID()] = $pornstarObj->getName();
			}
		}
		
		$this->assign('itemPornstarList',$results);
		
		
	}
	
}
?>