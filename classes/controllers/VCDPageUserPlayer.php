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
 * @version $Id: VCDPageUserPlayer.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php

class VCDPageUserPlayer extends VCDBasePage {

	public function __construct(_VCDPageNode $node) {
		
		parent::__construct($node);
				
		$this->doPlayer();
		
	}
	
	public function handleRequest() {
		$obj = new metadataObj(array('',0,VCDUtils::getUserID(), metadataTypeObj::SYS_PLAYER, $this->getParam('player',true)));
		SettingsServices::addMetadata($obj);
		$obj = new metadataObj(array('',0,VCDUtils::getUserID(), metadataTypeObj::SYS_PLAYERPATH, $this->getParam('params',true)));
		SettingsServices::addMetadata($obj);
		redirect('?page=player');
	}
	
	/**
	 * Assign the current player settings
	 *
	 */
	private function doPlayer() {
		
		$playerObj = SettingsServices::getMetadata(0, VCDUtils::getUserID(), metadataTypeObj::SYS_PLAYER );
		$pathObj = SettingsServices::getMetadata(0, VCDUtils::getUserID(), metadataTypeObj::SYS_PLAYERPATH );
		if (is_array($playerObj) && sizeof($playerObj) == 1 && $playerObj[0] instanceof metadataObj ) {
			$player = $playerObj[0]->getMetaDataValue();
			$this->assign('playerPath', $player);
		}
		if (is_array($pathObj) && sizeof($pathObj) == 1 && $pathObj[0] instanceof metadataObj ) {
			$path = $pathObj[0]->getMetaDataValue();
			$this->assign('playerParams', $path);
		}
	}
	
	
}



?>