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
 * @version $Id: VCDPageUserMoviePlayer.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php

/**
 * Controlls the page that launches the movie file with the OS associated player.
 * Works on Windows and Linux (KDE,GNOME & XFC).  Applet must be sign and trusted by the client user.
 *
 */
class VCDPageUserMoviePlayer extends VCDBasePage {

	public function __construct(_VCDPageNode $node) {
		
		parent::__construct($node);
		$this->doApplet();
	}
	
	
	/**
	 * Initialize the play applet properties and determine if everything is OK.
	 * If param "param" is sent to the applet, the applet <param file> is used,
	 * otherwise the param should be string poiting to a filelocation on the HD.
	 */
	private function doApplet() {
		try {
		
			$metadata_id = $this->getParam('id');
			if (is_numeric($metadata_id)) {
				
				$metaObj = SettingsServices::getMetadataById($metadata_id);
				if ($metaObj instanceof metadataObj ) {
					
					// Check if current user is actually the owner ...
					if ($metaObj->getUserID() != VCDUtils::getUserID()) {
						throw new VCDSecurityException('You have no rights viewing this item.');
					}
					
					
					// Verify that item is of correct type
					if ($metaObj->getMetadataTypeID() != metadataTypeObj::SYS_FILELOCATION) {
						throw new VCDProgramException('Invalid id.');
					}
					
					$file = $metaObj->getMetadataValue();
					if (empty($file)) {
						throw new VCDConstraintException('Path is empty.');
					}
					
					// All ok .. initialize the applet and parameters
					$this->assign('isPlayable',true);
					$this->assign('itemFilename',$file);
					
					// For now we just use the applet params, may need the other one later.
					$this->assign('itemLaunchCommand','param');
										
					
				} else {
					throw new VCDProgramException('Invalid id.');
				}
				
			} else {
				throw new VCDInvalidArgumentException('id must be numeric.');
			}
		
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
}



?>