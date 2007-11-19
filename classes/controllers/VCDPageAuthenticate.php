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
		}
		
		// Only accept post ..
		if (sizeof($_POST)==0 && (strcmp($this->getParam('action'),'retry')!=0)) {
			redirect();
		}	
	}
	
	
	/**
	 * Handle authentication requests
	 *
	 */
	public function handleRequest() {
	
		
		// Check for the special case where user is requesting new password
		if (strcmp($this->getParam('action'),'reset')==0) {
			$this->doResetPassword();
			exit();
		}
		
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
	
	/**
	 * Reset password if username and email combination is correct.
	 *
	 */
	private function doResetPassword() {
		try {
			
			$username = $this->getParam('username',true);
			$email = $this->getParam('email',true);
			
			if (!(is_null($username) || is_null($email))) {
				
				$obj = UserServices::getUserByUsername($username);
				if ($obj instanceof userObj && (strcmp($obj->getEmail(),$email) == 0)) {
					
					$newpass = substr(VCDUtils::generateUniqueId(),0, 6);
					$md5newpass = md5($newpass);
					
					$body  = "Request for new password was made for your account from computer: " . $_SERVER['REMOTE_ADDR'] . "\n\n";
					$body .= $obj->getFullname() . ", your new password as requested is ".$newpass . "\n";
					$body .= "\nGood luck, (The VCD-db)";
					
					if ((VCDUtils::sendMail($email, "New password as requested",$body))) {
						$message  = "New password has been mailed to " . $email . "<break>";
						$message .= "You can change the password next time you log in.";	
						
						// actually update the password since we now know that the email was successfully sent
						$obj->setPassword($md5newpass);
						UserServices::updateUser($obj);
						
					} else {
						$message = "The site owner has wrong mail settings defined, cannot sent password";
					}
					
					VCDException::display($message);
					redirect();
					
				} else {
					throw new VCDProgramException('Invalid username and email combination');
				}
							
			} else {
				throw new VCDInvalidInputException('You must provide both username and email.');
			}
			
		} catch (Exception $ex) {
			VCDException::display($ex);
			redirect('?page=authenticate&action=retry');
		}
	}
	
	/**
	 * Authenticate the user.
	 *
	 */
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
			// Redirect to the frontpage is authentication comes from the register page ..
			if (!strpos($_SERVER['HTTP_REFERER'],'register')) {
				header("Location: ".$_SERVER['HTTP_REFERER'].""); /* Redirect browser */ 	
			} else {
				redirect();
			}
			exit();
			
			
		} else {
			// authentication failed
			$this->assign('loginInvalid',true);
			return;
		}
	}
	
	
}
?>