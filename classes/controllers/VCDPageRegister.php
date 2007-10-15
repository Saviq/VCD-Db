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
		
		// Already logged in user has no business using this page ..
		if (VCDUtils::isLoggedIn()) {
			redirect();
			exit();
		}
		
		
		// Check if registration is enabled
		$canRegister = SettingsServices::getSettingsByKey("ALLOW_REGISTRATION");
		if ($canRegister) {
			$this->append('registrationOpen', true);
			$this->doUserProperties();
		}
		
	}
	
	
	public function handleRequest() {
		
		// The only request that is made by the new user registration process ..
		$this->doRegisterUser();
			
	}
	
	
	private function doRegisterUser() {
		try {
			
			// double check that the registration is open ..
			$canRegister = SettingsServices::getSettingsByKey("ALLOW_REGISTRATION");
			if ($canRegister) {
				
				$name = $this->getParam('name',true);
				$username = $this->getParam('username',true);
				$email = $this->getParam('email',true);
				$password = md5($this->getParam('password',true));
				
				
				// do some checks ..
				if (is_null($name)) {
					throw new VCDInvalidInputException('Name cannot be empty');
				}
				
				if (is_null($username)) {
					throw new VCDInvalidInputException('Username cannot be empty');
				}
				
				if ((is_null($this->getParam('password',true))) || (strlen($this->getParam('password',true))<5)) {
					throw new VCDInvalidInputException('Password must be at least 5 characters');
				}
				
								
				$data = array('', $username, $password, $name, $email, '', '', '', '');
				$userObj = new userObj($data);
				
				// find selected properties
				$userProperties = $this->getParam('props',true);
				if (is_array($userProperties)) {
					foreach ($userProperties as $propKey) {
						$propObj = UserServices::getPropertyByKey($propKey);
						$userObj->addProperty($propObj);	
					}
				}
				
				if (UserServices::addUser($userObj)) {
					// Try to send mail to user with registration details.
					$body = sprintf(VCDLanguage::translate('mail.register'), $name, $username, $this->getParam('password',true), SettingsServices::getSettingsByKey('SITE_HOME'));
					sendMail($email, "VCD-db " . VCDLanguage::translate('register.title'), $body, true);
				
					
					// Inform the UI of the success
					$this->assign('registrationSuccess',true);
					$this->assign('registrationUsername', $userObj->getFullname());

					
				} else {
					throw new VCDProgramException('Failed to add user, something went wrong!');
				}
				
				
				
			} else {
				throw new VCDException(VCDLanguage::translate('register.disabled'));
			}
			
			
		} catch (Exception $ex) {
			VCDException::display($ex,true);
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