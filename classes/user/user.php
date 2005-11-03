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
 * @package User
 * @version $Id$
 */
 
?>
<? 
require_once("userObj.php");

class vcd_user implements User {
	
	/**
	 * Internal user object cache
	 *
	 * @var array
	 */
	private $userObjArr = null;
	/**
	 * Internal user-roles objects cache
	 *
	 * @var array
	 */
	private $userRolesArr = null;
	private $propertiesArr = null;
	
	/**
	 * 
	 * @var userSQL
	 */
	private $SQL;
	
	// User property strings
	public static $PROPERTY_WISHLIST = "WISHLIST";
	public static $PROPERTY_SEEN	 = "SEEN_LIST";
	public static $PROPERTY_NOTIFY	 = "NOTIFY";
	public static $PROPERTY_RSS	 	 = "RSS";
	public static $PROPERTY_ADULT	 = "SHOW_ADULT";
	public static $PROPERTY_INDEX	 = "USE_INDEX";
	
	
	/**
	 * Constructor
	 *
	 */
	public function __construct() { 
	 	$this->SQL = new userSQL();
    } 
   	
    
    
	/**
	 * Get user by ID
	 *
	 * @param int $user_id
	 * @return userObj
	 */
	public function getUserByID($user_id) {
   		try {
   			$obj = $this->SQL->getUserByID($user_id);
   			
   			if (!$obj instanceof userObj ) {
   				return null;
   			} 
   			
   			/* Since we found our user, lets get his extended properties */
   				$propIDarr = $this->SQL->getPropertyIDsOnUser($obj->getUserID());
   				if (!is_null($propIDarr)) {
   					foreach ($propIDarr as $propID) {
   						$obj->addProperty($this->getPropertyById($propID));
   					}
   					unset($propIDarr);
   				}
   			return $obj;
   			
   			
   		} catch (Exception $e){
   			VCDException::display($e);
   		} 
   }
   
   /**
    * Get user by username.
    *
    * Returns null if no user is found with the specified username.
    *
    * @param string $user_name
    * @return userObj
    */
   public function getUserByUsername($user_name) {
   		try {
   			
   			if (empty($user_name)) {
   				return null;
   			}
   			
   			$obj = $this->SQL->getUserByUsername($user_name);
   			if ($obj instanceof userObj) {
   				
   				/* Since we found our user, lets get his extended properties */
   				$propIDarr = $this->SQL->getPropertyIDsOnUser($obj->getUserID());
   				if (!is_null($propIDarr)) {
   					foreach ($propIDarr as $propID) {
   						$obj->addProperty($this->getPropertyById($propID));
   					}
   					unset($propIDarr);
   				}
   				
   				return $obj;
   			} else {
   				return null;
   			}
   			
   			
   		} catch (Exception $e){
   			VCDException::display($e);
   		}	
   }
   
   /**
    * Add new user to database.
    *
    * Returns true if successful otherwise false
    *
    * @param userObj $userObj
    * @return bool
    */
   public function addUser(userObj $userObj) {
   		try {
   			if ($userObj instanceof userObj) {
   				
   				// Check if username is taken
   				foreach ($this->getAllUsers() as $user) {
   					if (strcmp(strtolower($user->getUserName()), strtolower($userObj->getUserName())) == 0) {
   						VCDException::display($userObj->getUsername() . " is already taken, choose another one");
   						return false;
   					}
   				}
   				
   				if (strlen(trim($userObj->getUsername())) == 0) {
   					VCDException::display("Username cannot be empty");
   					return false;
   				}
   				
   				if (strlen(trim($userObj->getFullname())) == 0) {
   					VCDException::display("Full name cannot be empty");
   					return false;
   				}
   				
   				// Add default role to user
   				$userObj->setRole($this->getDefaultRole());
   				// Add the user to DB and grab the new user_id
   				$user_id = $this->SQL->addUser($userObj);
   				
   				// Save the users selected properties to database
   				foreach ($userObj->getUserProperties() as $propObj) {
   					$this->addPropertyToUser($propObj->getpropertyID(), $user_id);
   				}
   				
   				// Create users default frontpage with default options
   				$SETTINGSClass = VCDClassFactory::getInstance('vcd_settings');
   				$statsObj = new metadataObj(array('',0, $user_id, 'frontstats', 1));
				$barObj   = new metadataObj(array('',0, $user_id, 'frontbar', 1));			
				$SETTINGSClass->addMetadata(array($barObj, $statsObj));
   				
   				return true;
   				   				
   			} else {
   				throw new Exception('userObj required');
   			}
   			
   			   			
   		} catch (Exception $e) {
   			VCDException::display($e);
   			return false;
   		}
   }
   
   /**
    * Add the user's session details to database.
    *
    * @param string $session_id
    * @param int $user_id
    */
   public function addSession($session_id, $user_id) {
   	try {
   			$user_ip = $_SERVER['REMOTE_ADDR'];
			$session_time = VCDUtils::getmicrotime();
   			$this->SQL->addSession($session_id, $user_id, $session_time, $user_ip);
   			
   		} catch (Exception $e){
   			VCDException::display($e);
   		}
   }
   
   
   /**
   * Check if session is valid.
   *
   * @param string $session_id
   * @param string $session_time
   * @param int $user_id
   * @return bool
   */
   public function isValidSession($session_id, $session_time, $user_id) {
   		try {
   		   			  			
   			
   			if (!is_numeric($user_id)) {
   				return false;
   			} else {
   				$user_id = intval($user_id);
   			}
   			
   			// Expire time in hours
   			$SETTINGSclass = VCDClassFactory::getInstance("vcd_settings");
   			$session_lifetime = (int)$SETTINGSclass->getSettingsByKey("SESSION_LIFETIME");
   			$session_expires = 24*24*24*$session_lifetime;
  			unset($SETTINGSclass);
  			
  			if ((VCDUtils::getmicrotime() - (int)$session_expires)  < (int)$session_time) {
  				// Time-frame seems to be valid .. proceed with check
  				
  				try {

  					$user_id = $this->SQL->getSessionUserID($session_id, $session_time);
  					if (!empty($user_id)) {
						
  						// Our user seems valid... update the session info
  						$new_sessiontime = VCDUtils::getmicrotime();
  						$this->SQL->updateSession($session_id, $new_sessiontime);
  						return true;	
  						
  						 						
  						
  					} else {
  						return false;
  					}	
  					
  					 					
  				} catch (Exception $e) {
  					VCDException::display($e);
  				}
  					
  			}
   			
   			return false;
   			
   		} catch (Exception $e) {
   			VCDException::display($e);
   		}
   	
   }
   
   
   /**
    * Update user object in database.
    *
    * Returns true if user is successfully updates otherwise false.
    *
    * @param userObj $userObj
    * @return bool
    */
   public function updateUser($userObj) {
   		try {
   			
   			// update the user
   			if ($this->SQL->updateUser($userObj)) {
   				// update the userproperties
   				$this->SQL->deletePropertiesOnUser($userObj->getUserID());
	   			
	   			foreach ($userObj->getUserProperties() as $property) {
	   				$this->SQL->addPropertyToUser($property->getpropertyID(), $userObj->getUserID());
	   			}
	   			
	   			return true;
   			}
   			
			return false;
   			
   		} catch (Exception $e){
   			VCDException::display($e);
   		}
   }
   
   
   /**
    * Delete user from database.
    *
    * Param $erase_data tells if all user related data in VCD-db should be deleted as
    * well, such as users movies, comments etc.
    *
    * @param int $user_id
    * @param bool $erase_data
    */
   public function deleteUser($user_id, $erase_data = false) {
   		try {
   		
   			if (is_numeric($user_id)) {
   				
   				  				
   				
   				if ($erase_data) {
   					// delete all user related data aswell
   					// (borrowers, loan records, rss feeds, user properties)
   					   					  					
   					
   					// Find all user movies
   					$VCDClass = VCDClassFactory::getInstance('vcd_movie');
   					$vcdArr = $VCDClass->getAllVcdByUserId($user_id, true);
   					
   					
   					
   					// loop through his movies and delete them
   					foreach ($vcdArr as $vcdObj) {
   						// Get the mediaType
   						$mtArr = $vcdObj->getMediaType();
   						if (is_array($mtArr) && sizeof($mtArr) == 1) {
   							$mediaTypeObj = $mtArr[0];
   							
   							// delete the copy
   							$VCDClass->deleteVcdFromUser($vcdObj->getID(), $mediaTypeObj->getmediaTypeID(), 'full', $user_id);
   						}
   					}
   					
   					unset($vcdArr);
   					
   					// delete rest of user data
   					$this->SQL->eraseUser($user_id);
   					
   				
   				} else {
   					$this->SQL->deleteUser($user_id);
   				}
   				
   				
   				$this->updateUserCache();
   				
   			}
   			
   		} catch (Exception $e) {
   			VCDException::display($e);
   		}
   }
   
   
   /**
   * Returns array with all the users in the system.
   *
   * @return array
   */
   public function getAllUsers() {
   		try {
   			$this->updateUserCache();	
   			return $this->userObjArr;
   			
   		}	catch (Exception $e) {
   			VCDException::display($e);
   		}
   }
   
   
   /** 
   * Get all users that have inserted movies
   *
   * @return array
   */
   public function getActiveUsers() {
   		try {
   			return $this->SQL->getActiveUsers();
   		} catch (Exception $e) {
   			VCDException::display($e);
   		}
   }
   
   
   /**
    * Get all user roles.
    *
    * Returns an array with all user role objects.
    *
    * @return array
    */
   public function getAllUserRoles() {
   		try {
   			if (is_null($this->userRolesArr)) {
   				$this->updateRolesCache();
   				return $this->userRolesArr;
   			} else {
   				return $this->userRolesArr;
   			}
   			
   			
   		} catch (Exception $e) {
   			VCDException::display($e);
   		}
   }
   
   /**
    * Get all user objects with the specified role ID.
    *
    * @param int $role_id
    * @return array
    */
   public function getAllUsersInRole($role_id) {
   		try {
   			return $this->SQL->getAllUsersInRole($role_id);
   		} catch (Exception $e) {
   			VCDException::display($e);
   		}
   }
   
   /**
    * Enter description here...
    *
    * @param unknown $userRoleObj
    */
   public function addUserRole($userRoleObj) {
   		//TODO .. implement
   }
   
   
   /**
    * Delete user role from database.
    *
    * Throws Exception if any users are assigned to the role that 
    * is marked for deletion.  Returns true if operation succeds otherwise false.
    *
    * @param int $role_id
    * @return bool
    */
   public function deleteUserRole($role_id) {
		if (is_numeric($role_id)) {
			$arrUsersinWithRole = $this->getAllUsersInRole($role_id);
			if (is_array($arrUsersinWithRole) && sizeof($arrUsersinWithRole) == 0) {
				try {
					$this->SQL->deleteUserRole($role_id);
					return true;
				} catch (Exception $e) {
					VCDException::display($e);
				}
				
			} else {
				VCDException::display("Cannot delete roles with ".sizeof($arrUsersinWithRole)." active users, change user roles first.");
			}
		}
		return false;
   }
   


   
   /**
    * Enter description here...
    *
    */
   private function updateUserCache() {
   		if (is_null($this->userObjArr))
   			$this->userObjArr = $this->SQL->getAllUsers();
   }
	
   
   
   
   /* User Properties */
	/**
	 * Get an array with all user property objects.
	 *
	 * @return array
	 */
	public function getAllProperties() {
		try {
			if (is_null($this->propertiesArr)) {
				$this->updatePropsCache();
				return $this->propertiesArr;
			} else {
				return $this->propertiesArr;
			}
			
		} catch (Exception $e) {
			VCDException::display($e);
		}
	}
	
	
	/**
	 * Add a new user property object to database.
	 *
	 * @param userPropertiesObj $userPropertiesObj
	 */
	public function addProperty($userPropertiesObj) {
		try {
			
			if ($userPropertiesObj instanceof userPropertiesObj ) {
				$this->SQL->addProperty($userPropertiesObj);
				$this->updatePropsCache();
			}
			
		} catch (Exception $e) {
			VCDException::display($e);
		}
	}
	
	
	/**
	 * Delete property from database.
	 *
	 * Throws Exception if any users are using this property.
	 *
	 * @param int $property_id
	 */
	public function deleteProperty($property_id) {
		try {
			if (is_numeric($property_id)) {
				
				if (sizeof($this->getAllUsersWithProperty($property_id)) > 0) {
					VCDException::display('Cannot delete property. Property is in use by users.');
					return;
				}
				
				$this->SQL->deleteProperty($property_id);
				$this->updatePropsCache();
			}
		} catch (Exception $e) {
			VCDException::display($e);
		}
	}
	
	
	/**
	 * Update userProperty object in database.
	 *
	 * @param userPropertiesObj $userPropertiesObj
	 */
	public function updateProperty($userPropertiesObj) {
		try {
			if ($userPropertiesObj instanceof userPropertiesObj ) {
				$this->SQL->updateProperty($userPropertiesObj);
				$this->updatePropsCache();
			}
		} catch (Exception $e) {
			VCDException::display($e);
		}
	}
	
		
	/* Properties on user */
	/**
	 * Save a propertyObj on user to database.
	 *
	 * @param int $property_id
	 * @param int $user_id
	 */
	public function addPropertyToUser($property_id, $user_id) {
		try {
			if (is_numeric($property_id) && is_numeric($user_id)) {
				$this->SQL->addPropertyToUser($property_id, $user_id);
			} else {
				throw new Exception("Parameters must be numeric");
			}
			
			
		} catch (Exception $e) {
			VCDException::display($e);
		}
	}
	
	/**
	 * Get userProperty object by ID.
	 *
	 * @param int $property_id
	 * @return  userPropertyObj
	 */
	public function getPropertyById($property_id) {
		try {
		
			if (is_numeric($property_id)) {
				
				foreach ($this->getAllProperties() as $obj) {
					if ($obj->getpropertyID() == $property_id) {
						return $obj;
					}
				}
				
				return null;
				
			} else {
				throw new Exception("Invalid property ID");
			}
			
		} catch (Exception $e) {
			VCDException::display($e);
		}
	}
	
	
	
	/**
	 * Get a userProperty object by property key.
	 *
	 * @param string $property_key
	 * @return userPropertiesObj
	 */
	public function getPropertyByKey($property_key) {
		try {
		
			if (is_string($property_key)) {
				foreach ($this->getAllProperties() as $obj) {
					
					if (strcmp(strtolower($obj->getpropertyName()), strtolower($property_key)) == 0) {
						return $obj;
					}
					
				}
				
				return null;
				
			} else {
				throw new Exception("Invalid property key");
			}
			
		} catch (Exception $e) {
			VCDException::display($e);
		}
	}
	
	
	
	/**
	 * Unlink property object from user
	 *
	 * @param int $property_id
	 * @param int $user_id
	 */
	public function deletePropertyOnUser($property_id, $user_id) {
		try {
			if (is_numeric($property_id) && is_numeric($user_id)) {
				$this->SQL->deletePropertyOnUser($property_id, $user_id);
			} else {
				throw new Exception("Parameters must be numeric");
			}
		} catch (Exception $e) {
			VCDException::display($e);
		}
	}
	
	
	/**
	 * Get all users with the specified property ID assigned.
	 *
	 * Returns array of user objects.
	 *
	 * @param int $property_id
	 * @return array
	 */
	public function getAllUsersWithProperty($property_id) {
		try {
			if (!is_numeric($property_id)) {
				throw new Exception("Property ID must be an Identifier");
			}			
			
			return $this->SQL->getUsersByPropertyID($property_id);
		
			
		} catch (Exception $e) {
			VCDException::display($e);
		}
	}
	
	
	/* Misc functions */
	
	/**
	 * Get the users with most movie count in database.
	 *
	 * Returns array of user objects sorted by highest movie count.
	 *
	 * @return array
	 */
	public function getUserTopList() {
		try {
			return $this->SQL->getUserTopList();
		} catch (Exception $e) {
			VCDException::display($e);
		}
		
		
	}
	
	
	/**
	 * Update the internal properties cache.
	 *
	 */
	private function updatePropsCache() {
		if (is_null($this->propertiesArr)) {
			$this->propertiesArr = $this->SQL->getAllProperties();
		}
			
	}
	
	/**
	 * Update the internal user roles cache.
	 *
	 */
	private function updateRolesCache() {
		if (is_null($this->userRolesArr))
			$this->userRolesArr = $this->SQL->getAllUserRoles();
	}
	
	/**
	 * Get the default user role for new users.
	 *
	 * @return userRoleObj
	 */
	public function getDefaultRole() {
		try {
			
			
			// Check if default role has been defined as a metadata
			$CLASSSettings = VCDClassFactory::getInstance('vcd_settings');
			$arrMeta = $CLASSSettings->getMetadata(0,0,'default_role');
			if (is_array($arrMeta) && sizeof($arrMeta) == 1) {
				$metaObj = $arrMeta[0];
				if ($metaObj instanceof metadataObj ) {
					$defaultRoleID = (int)$metaObj->getMetadataValue();
					foreach ($this->getAllUserRoles() as $role) {
						if ($role->getRoleID() == $defaultRoleID) {
							return $role;
						}
					}
				}
			}
			
			
			// Else set the "user" a default role and return that roleObj.
			foreach ($this->getAllUserRoles() as $role) {
				if (strcmp(strtolower($role->getRoleName()), strtolower("user")) == 0) {
					return $role;
				}
			}
			
			throw new Exception("Default userrole not found!");
			
		} catch (Exception $e) {
			VCDException::display($e);
		}

	}
	
	/**
	 * Set default role for new users who sign up.
	 *
	 * @param int $role_id
	 */
	public function setDefaultRole($role_id) {
		try {
		
			foreach ($this->getAllUserRoles() as $role) {
				if ((strcmp(strtolower($role->getRoleName()), strtolower("administrator")) == 0) && ($role->getRoleID() == $role_id)) {
					VCDException::display('For security reasons<break>administrator cannot be set as a default role.', true);
					return;
				}
			}
			
			$CLASSSettings = VCDClassFactory::getInstance('vcd_settings');
			$metadata = new metadataObj(array('', 0, 0, 'default_role', $role_id));
			$CLASSSettings->addMetadata($metadata);
			
			
		} catch (Exception $ex) {
			VCDException::display($ex, true);
		}
	}


}




?>