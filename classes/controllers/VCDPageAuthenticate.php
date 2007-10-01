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
 * @version $Id: VCDPageAuthenticate.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php

class VCDPageAuthenticate extends VCDBasePage {
	
	
	public function __construct(_VCDPageNode $node) {
		parent::__construct($node);

		// Only for users not authenticated
		if (VCDUtils::isLoggedIn()) {
			redirect();
			exit();
		}
		
		// Only accept post ..
		if (sizeof($_POST)==0) {
			redirect();
			exit();
		}	
		
	}
	
	
	/**
	 * Handle authentication requests
	 *
	 */
	public function handleRequest() {
	
		if (is_null($this->getParam('username',true)) || is_null($this->getParam('password',true))) {
			redirect(); /* Redirect browser - Bad request */ 
			exit();
		}
		
		if ((strcmp($this->getParam('username',true), "") == 0) || (strcmp($this->getParam('password',true),"") == 0)) {
			redirect(); /* Redirect browser - empty request */ 
			exit();
		}
					
		$this->doAuthenticate();

	}
	
	
	private function doAuthenticate() {
	
		$username = str_replace("'", "", $this->getParam('username',true));
		$password = str_replace("'", "", $this->getParam('password',true));
		
		$remember = false;
		if (!is_null($this->getParam('remember',true))) {
			$remember = true;
		}
		
		
		
		$userObj = VCDAuthentication::authenticate($username, $password, (bool)$remember);
		if ($userObj instanceof userObj ) {
			
			// user has been authenticated
			
			// But .. has this account been deleted ?
			if ($userObj->isDeleted()) {
				$this->assign('loginAccountdisabled',true);
				return;
			}
					
			// Store info in users cookie if want's to be remembered
			if (isset($_POST['remember']) && (bool)$_POST['remember']) {
				$Cookie = new SiteCookie("vcd_cookie");
		   		$Cookie->clear();
				$Cookie->put("session_id", session_id());	
				$Cookie->put("session_time", VCDUtils::getmicrotime());
				$Cookie->put("session_uid", $userObj->getUserId());
				if (isset($_SESSION['vcdlang'])) {
					$Cookie->put("language", $_SESSION['vcdlang']);	
				}
				$Cookie->set();
			}
				
			// Add userObj to session
			$_SESSION['user'] = $userObj;
			
			
			// Check if we are supposed to log this event ..
			if (VCDLog::isInLogList(VCDLog::EVENT_LOGIN )) {
				VCDLog::addEntry(VCDLog::EVENT_LOGIN, "User login");
			}
			
			// Redirect to referee page - N.B. HTTP_REFERER cannot always be trusted
			header("Location: ".$_SERVER['HTTP_REFERER'].""); /* Redirect browser */ 
			exit();
			
			
		} else {
			// authentication failed
			$this->assign('loginInvalid',true);
			return;
		}
		
		
		
		
	}
	
	
}


?>