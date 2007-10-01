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
 * @version $Id: VCDPageRegister.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php

class VCDPageRegister extends VCDBasePage  {
	
	public function __construct(_VCDPageNode $node) {

		parent::__construct($node);
		
		
		// Check if registration is enabled
		$canRegister = SettingsServices::getSettingsByKey("ALLOW_REGISTRATION");
		if ($canRegister) {
			$this->append('registrationOpen', true);
			$this->doUserProperties();
		}
		
	}
	
	private function doUserProperties() {
		
		$results = array();
		$properties = UserServices::getAllProperties();
		foreach ($properties as $propertyObj) {
		
			// Check if translation for property exists
			$langkey = "userproperties.".strtolower($propertyObj->getpropertyName());
			$description = VCDLanguage::translate($langkey);
			if (strcmp($description, "undefined") == 0) {
				$description = $propertyObj->getpropertyDescription();
			}
			$results[] = array('desc' => $description, 'key' => $propertyObj->getpropertyName());
		}
	
		$this->assign('userProperties', $results);
		
	}
	
}
?>