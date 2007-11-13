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
 * @version $Id: VCDPageItemMovie.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php
require_once(dirname(__FILE__).'/VCDPageBaseItem.php');
class VCDPageItemMovie extends VCDPageBaseItem  {
	
	public function __construct(_VCDPageNode $node) {
				
		parent::__construct($node);

		if (!is_null($this->sourceObj))	{
			$this->doSourceSiteElements();
			$this->doCast();
			$this->doImdbLinks();
		}
	}
		
	private function doCast() {
		if (!is_null($this->sourceObj))	{
			$this->assign('sourceActors', $this->sourceObj->getCast(true));
		}
	}
	
	
	private function doImdbLinks() {
		
		if (!is_numeric($this->itemObj->getSourceSiteID())) {
			return;
		}
		
		if (is_null($this->sourceSiteObj)) {
			$this->sourceSiteObj = SettingsServices::getSourceSiteByID($this->itemObj->getSourceSiteID());
		}
		
		if (strcmp(strtolower($this->sourceSiteObj->getAlias()),'imdb')==0) {
			$this->assign('showImdbLinks',true);	
		}
	}
	
	
}
?>