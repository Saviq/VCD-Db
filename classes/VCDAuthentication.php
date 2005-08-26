<?php
/**
 * VCD-db - a web based VCD/DVD Catalog system
 * Copyright (C) 2003-2004 Konni - konni.com
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * @author  Hákon Birgsson <konni@konni.com>
 * @package Core
 * @version $Id$
 */
?>
<?php
	/*
		Authentication class for the framework
	*/

	class VCDAuthentication {
	
		/**
		 * Authenticate user.
		 *
		 * If user is authenticated an userObj is returned, otherwise null.
		 *
		 * @param string $username
		 * @param string $password
		 * @param bool $save_session
		 * @return userObj
		 */
		static final function authenticate($username, $password, $save_session = false) {
		
			global $ClassFactory;
			$USERclass = $ClassFactory->getInstance("vcd_user");
			$userObj = $USERclass->getUserByUsername($username);
			
			if ($userObj instanceof userObj) {
				
				if (strcmp($userObj->getPassword(),$password) == 0) {
					// We have a valid user ...
					
					// Lets add his session to the DB if user want's to be remembered
					if ($save_session)
						$USERclass->addSession(session_id(),$userObj->getUserID());
					
					// return the user 
					return $userObj;
					
				}
				
			} else {
				return null;
			}
					
			return null;
			
		}
		
		/**
		 * Check if user has a cookie in browser, with valid information 
		 * so we can log him in.
		 *
		 */
		static final function checkCookie() {
			SiteCookie::extract('vcd_cookie');

			// Check if we find the desired values in the cookie
			if (isset($_COOKIE['session_id']) && isset($_COOKIE['session_uid'])) {
				$old_sessionid = $_COOKIE['session_id'];			
				$user_id 	   = $_COOKIE['session_uid'];
				$session_time  = $_COOKIE['session_time'];
				
				global $ClassFactory;
				$USERClass = $ClassFactory->getInstance("vcd_user");
				if ($USERClass->isValidSession($old_sessionid, $session_time, $user_id)) {
					
					//Update users cookie
					SiteCookie::extract("vcd_cookie");
					$sess_lang = $_COOKIE['language'];
					
					$Cookie = new SiteCookie("vcd_cookie");
					$Cookie->clear();
					$Cookie->put("session_id", $old_sessionid);	
					$Cookie->put("session_time", VCDUtils::getmicrotime());
					$Cookie->put("session_uid", $user_id);
											
					$Cookie->put("language", $sess_lang);
					$Cookie->set();
					
					
					// And finally log the user in and add userObj to session
					$user = $USERClass->getUserByID($user_id);
					$_SESSION['user'] = $user;
										
					
				}
				
				unset($USERClass);
				
			}
			
			
			
			
		}
		
		
		/**
		 * Check if user belongs to the administrator group.
		 *
		 * @return bool
		 */
		static final function isAdmin() {
			if (isset($_SESSION['user']))  {
			
				$u = $_SESSION['user'];
				if ($u instanceof userObj) {
					if (strcmp($u->getRoleName(),"Administrator") == 0) {
						return true;
					}
				}
				
				
				return false;
				
			} else {
				return false;
			} 
				
				
		}
		
		
		
		/**
		 * Print the HTML for the login box.
		 *
		 */
		static final function printLoginBox() {
			
			if (!VCDUtils::isLoggedIn()) {
				global $language;
			?>
			<div class="topic"><?=$language->show('LOGIN')?></div>
   			<div class="forms">   
			<form name="login" method="post" action="./authenticate.php">
			<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td><?=$language->show('LOGIN_USERNAME')?>:<br/>
				<input type="text" name="username" maxlength="50" class="dashed"/></td>
			</tr>
			<tr>
				<td><?=$language->show('LOGIN_PASSWORD')?>:<br/>
				<input type="password" name="password" maxlength="50" class="dashed"/></td>
			</tr>
			<tr>
				<td><?=$language->show('LOGIN_REMEMBER')?>: <input type ="checkbox" name="remember" value="1" class="nof"/></td>
			</tr>
			<tr>
				<td><input type="submit" value="<?=$language->show('X_CONFIRM')?>"/></td>
			</tr>
			</table>
			</form>
			</div>
			<?
			
			}
			
		}
		
		
		

	
	}


?>