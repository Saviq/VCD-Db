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
			
			
			// Check if we are authenticating to LDAP or DB
			if ((bool)LDAP_AUTH) {
				$LDAPAuthClass = new VCDLdapAuthentication();
				return $LDAPAuthClass->Authenticate($username, $password);
			}
			
			$password = md5($password);
			
		
			$USERclass = VCDClassFactory::getInstance("vcd_user");
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
				
				// Check if we are supposed to log this event ..
				if (VCDLog::isInLogList(VCDLog::EVENT_LOGIN )) {
					VCDLog::addEntry(VCDLog::EVENT_LOGIN, "Non existing account: " . $username);
				}
				
				return null;
			}
			
				// Check if we are supposed to log this event ..
				if (VCDLog::isInLogList(VCDLog::EVENT_LOGIN )) {
					VCDLog::addEntry(VCDLog::EVENT_LOGIN, "Invalid password for user: " . $username);
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
				
				$USERClass = VCDClassFactory::getInstance("vcd_user");
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
	
	
	
	
	class VCDLdapAuthentication {
	
		/**
		 * LDAP Server hostname.
		 *
		 * @var string
		 */
		private $ldap_host;
		/**
		 * The base DN of the LDAP server.
		 *
		 * @var string
		 */
		private $ldap_base;
		/**
		 * LDAP search filter when looking for users
		 *
		 * @var string
		 */
		private $ldap_filter;
		
		
		/**
		 * Is this LDAP Directory Server an Active Directory server ?
		 *
		 * @var bool
		 */
		private $isActiveDirectory = false;
		
		/**
		 * If LDAP server is Directory server, then Domain name must be set.  
		 * Then the domain name is added in front of the username when authentication towards the AD.
		 * 'username' automatically becomes 'DOMAIN\username'.
		 *
		 * @var string
		 */
		private $domainPrefix;
		
		
		/**
		 * Live Adodb LDAP Connection.
		 *
		 * @var ADOConnection
		 */
		private $ldap_connection = null;
		
		
		// Search array for fullname, searched in the following order.
		private $nameSearchArr = array('cn', 'displayName', 'name', 'givenName');
		
		// Search array for email, searched in the following order.
		private $mailSearchArr = array('mail', 'email');
		
		
		
		/**
		 * Class constructor
		 *
		 */
		public function __construct() {
			
			
			if (!function_exists( 'ldap_connect' ))  {
				throw new Exception('LDAP extension not available.<break>Cannot continue, use DB authentication instead or fix LDAP extension.');
			}
			
			
			$LDAP_CONNECT_OPTIONS = Array(
				Array ("OPTION_NAME"=>LDAP_OPT_DEREF, "OPTION_VALUE"=>2),
				Array ("OPTION_NAME"=>LDAP_OPT_SIZELIMIT,"OPTION_VALUE"=>100),
				Array ("OPTION_NAME"=>LDAP_OPT_TIMELIMIT,"OPTION_VALUE"=>30),
				Array ("OPTION_NAME"=>LDAP_OPT_PROTOCOL_VERSION,"OPTION_VALUE"=>3),
				Array ("OPTION_NAME"=>LDAP_OPT_ERROR_NUMBER,"OPTION_VALUE"=>13),
				Array ("OPTION_NAME"=>LDAP_OPT_REFERRALS,"OPTION_VALUE"=>FALSE),
				Array ("OPTION_NAME"=>LDAP_OPT_RESTART,"OPTION_VALUE"=>FALSE)
			);
			
			
			$this->ldap_host = LDAP_HOST;
			$this->ldap_base = LDAP_BASEDN;
			$this->isActiveDirectory = (bool)LDAP_AD;
			if ($this->isActiveDirectory) {
				// Set the AD Domain name
				$this->domainPrefix = AD_DOMAIN;
			}
			
			try {
				
				// Check if everything has been initilized correctly.
				if (!isset($this->ldap_host)  || !isset($this->ldap_base)) {
					throw new Exception("LDAP settings not defined.<break>Directory hostname and/or base DN misssing.<break>Cannot authenticate user until this has been fixed.");
				}	
			
				// Finally create the LDAP Connection
				$this->ldap_connection = NewADOConnection('ldap');
								
			
			} catch (Exception $ex) {
				VCDException::display($ex, true);
				exit();
			}
			
		}
		
		/**
		 * Authenticase user against the LDAP directory server
		 *
		 * @param string $username
		 * @param string $password
		 * @param bool $save_session
		 */
		final function Authenticate($username, $password) {
			try {
			
				
				if ($this->isActiveDirectory) {
					if (strcmp($this->domainPrefix, "") == 0) {
						throw new Exception("Domain name must be specified when using Active Directory in LDAP Authentication.");
						return;
					}
					
					// 	Add the domain prefix so AD will honor our request.
					// $ADUsername will now have become 'DOMAIN\username'
					$ADUsername = $this->domainPrefix . "\\" . $username;
				}
				
				
				try {
					
					if ($this->isActiveDirectory) {
						$this->ldap_connection->Connect($this->ldap_host, $ADUsername, $password, $this->ldap_base);
					} else {
						$this->ldap_connection->Connect($this->ldap_host, $username, $password, $this->ldap_base);
					}
					
					
				} catch (Exception $ex) {
					// Authentication failed, return null
					return null;									
				}
				
				
				
				// We have been authenticated !! Now map and populate user properties from the LDAP server.
				$this->ldap_connection->SetFetchMode(ADODB_FETCH_ASSOC);
				
				// Try to find user in any of the following field, CN, SN, GivenName, UID 
				// and sAMAccountName which is specially for Active Directory
				$filter="(|(CN=$username)(sn=$username)(givenname=$username)(uid=$username)(sAMAccountName=$username))";

							
				try {
					$rs = $this->ldap_connection->Execute( $filter );
				} catch (Exception $ex) {
					$errMsg = "User search failed although LDAP authentication was successful.";
					$errMsg .= "<break>This means that the LDAP Base DN needs to be corrected.";
					$errMsg .= "<break>Current Base DN is " . $this->ldap_base;
					throw new Exception($errMsg);
				}
				
								
				// Check for the record count of the search filter ...
				if ($rs->RecordCount() == 0) {
					$errMsg = "User search failed although LDAP authentication was successful.<break>";
					$errMsg .= "No user information found on LDAP Server " . $this->ldap_host  . " for user " . $username;
					$errMsg .= "<break>DN Search filter must be adjusted.";
					throw new Exception($errMsg);
				}
				
				
				// Ok, we have some results which allows us to continue.
				if ($rs->RecordCount() == 1) {
				
					$userArr = $this->ldap_connection->GetRow( $filter );
					
					$user_fullname = "";
					$user_email = "";
					
					// The only information we need from the LDAP server is the user's fullname and email address.
					
					// First make some attempts to find the full name of the user.
					$foundname = false;
					
					foreach ($this->nameSearchArr as $entry) {
						if (isset($userArr[$entry])) {
							$user_fullname = $userArr[$entry];
							$foundname = true;
							break;
						}
					}
					
					if (!$foundname) {
						// No fullname found, we will then just use the username.
						$user_fullname = $username;
					}
					
					
					
					foreach ($this->mailSearchArr as $entry) {
						if (isset($userArr[$entry])) {
							$user_email = $userArr[$entry];
							break;
						}
					}
					
					
					$userObj = new userObj(array('', $username, md5($password), $user_fullname, $user_email, '', '', false, ''));
					$userObj->setDirectoryUser(true);
					
										
				} else {
					// Deal with multiple results.
					throw new Exception('Cannot validate user.<break>LDAP server returned multiple results for this username.');
				} 
				
				
				
				// Now we have a userObj ready but we need a userObj with valid userid
				// Check if this user has logged in before and then use that userObj, otherwise
				// we need to save this user to DB, fetch it again and then we have a valid user.
				
				$CLASSUser = VCDClassFactory::getInstance('vcd_user');
				$dbUserObj = $CLASSUser->getUserByUsername($username);
				$dbUserObj->setDirectoryUser(true);
				
				if ($dbUserObj instanceof userObj ) {
					// User found, we will check if information needs updating.
					$needsUpdate = false;
					if (strcmp($dbUserObj->getFullname(), $userObj->getFullname()) != 0) {
						$dbUserObj->setName($userObj->getFullname());
						$needsUpdate = true;
					}
					
					if (strcmp($dbUserObj->getEmail(), $userObj->getEmail()) != 0) {
						$dbUserObj->setEmail($userObj->getEmail());
						$needsUpdate = true;
					}
					
					if ($needsUpdate) {
						$CLASSUser->updateUser($dbUserObj);
					}
					
					return $dbUserObj;
					
					
				} else {
					// Save this user to the database, since this is the first time he is logging in.
					$CLASSUser->addUser($userObj);
					return $CLASSUser->getUserByUsername($username);
				}
				
				
			
			
			} catch (Exception $ex) {
				VCDException::display($ex);
				exit();
			}
		}
	}

?>