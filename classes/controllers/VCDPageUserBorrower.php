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
 * @version $Id: VCDPageUserBorrower.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php
class VCDPageUserBorrower extends VCDBasePage {

	public function __construct(_VCDPageNode $node) {
		try {
			
			parent::__construct($node);
		
			// Register javascripts
			$this->registerScript(self::$JS_MAIN);
			$this->registerScript(self::$JS_LANG);
				
		} catch (Exception $ex) {
			VCDException::display($ex);	
		}	
	}
	
	/**
	 * Handle post requests to the controller
	 *
	 */
	public function handleRequest() {
		
		$action = $this->getParam('action');
		if (strcmp($action,'add')==0) {
			$this->addBorrower();
		}
		
	}
	
	/**
	 * Add the new borrower to database
	 *
	 */
	private function addBorrower() {
		try {
			
			$name = $this->getParam('borrower_name',true);
			$email = $this->getParam('borrower_email',true);
			
			if (is_null($name) || is_null($email)) {
				throw new VCDInvalidInputException('Please fill in user and email information.');
			}
			
			$obj = new borrowerObj(array('',VCDUtils::getUserID(),$name, $email));
			SettingsServices::addBorrower($obj);
			
			// Tell window to reload parent and close itself
			$this->registerScriptBlock('window.opener.location.reload();window.close()');
			
		} catch (Exception $ex) {
			VCDException::display($ex,true);
		}
	}
	
	
	
}

?>