<?php
/**
 * VCD-db - a web based VCD/DVD Catalog system
 * Copyright (C) 2003-2006 Konni - konni.com
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * @author  Hákon Birgisson <konni@konni.com>
 * @package Kernel
 * @version $Id$
 * @since  0.985
  */
?>
<?php

class UserServices extends VCDServices {
	
	/* Users */
	/**
	 * Get user By ID
	 *
	 * @param int $user_id
	 * @return userObj
	 */
	public static function getUserByID($user_id) {
		try {
			
			return self::User()->getUserByID($user_id);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function updateUser(userObj $userObj) {
		try {
			
			return self::User()->updateUser($userObj);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function deleteUser($user_id, $erase_data = false) {
		try {
			
			self::User()->deleteUser($user_id, $erase_data);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function addUser(userObj $userObj) {
		try {
			
			return self::User()->addUser($userObj);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getAllUsers() {
		try {
			
			return self::User()->getAllUsers();
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	/* Specific users */
	public static function getActiveUsers() {
		try {
			
			return self::User()->getActiveUsers();
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	/* Login and session routines */
	public static function getUserByUsername($user_name) {
		try {
			
			return self::User()->getUserByUsername($user_name);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function addSession($session_id, $user_id) {
		try {
			
			self::User()->addSession($session_id, $user_id);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function isValidSession($session_id, $session_time, $user_id) {
		try {
			
			return self::User()->isValidSession($session_id, $session_time, $user_id);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
			
	/* Roles / Groups */
	public static function getAllUserRoles() {
		try {
			
			return self::User()->getAllUserRoles();
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getAllUsersInRole($role_id) {
		try {
			
			return self::User()->getAllUsersInRole($role_id);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function addUserRole($userRoleObj) {
		try {
			
			self::User()->addUserRole($userRoleObj);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function deleteUserRole($role_id) {
		try {
			
			return self::User()->deleteUserRole($role_id);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getDefaultRole() {
		try {
			
			return self::User()->getDefaultRole();
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function setDefaultRole($role_id) {
		try {
			
			self::User()->setDefaultRole($role_id);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}

	/* User Properties */
	public static function getAllProperties() {
		try {
			
			return self::User()->getAllProperties();
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getPropertyById($property_id) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getPropertyByKey($property_key) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function addProperty($userPropertiesObj) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function deleteProperty($property_id) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function updateProperty($userPropertiesObj) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
			
	/* Properties on user */
	public static function addPropertyToUser($property_id, $user_id) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function deletePropertyOnUser($property_id, $user_id) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getAllUsersWithProperty($property_id) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	/* Misc user functions */
	public static function getUserTopList() {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	
}

class SettingsServices extends VCDServices {
	
}

class CoverServices extends VCDServices {
	
}

class PornstarServices extends VCDServices {
	
}

class MovieServices extends VCDServices {
	
	/**
	 * Get movie by ID
	 *
	 * @param int $movie_id
	 * @return vcdObj
	 */
	public static function GetVCDById($movie_id) {
		try {
			
			return self::Movie()->getVcdByID($movie_id);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
}


class VCDServices {
	
	/**
	 * Get an instantce of the vcd_movie class
	 *
	 * @return vcd_movie
	 */
	protected static function Movie() {
		return VCDClassFactory::getInstance('vcd_movie');
	}

	/**
	 * Get an instance of the vcd_user class
	 *
	 * @return vcd_user
	 */
	protected static function User() {
		return VCDClassFactory::getInstance('vcd_user');
	}
	
	/**
	 * Get an instance of the vcd_pornstar class
	 *
	 * @return vcd_pornstar
	 */
	protected static function Pornstar() {
		return VCDClassFactory::getInstance('vcd_pornstar');
	}
	
	/**
	 * Get an instance of the CDcover class
	 *
	 * @return vcd_cdcover
	 */
	protected static function CDcover() {
		return VCDClassFactory::getInstance('vcd_cdcover');
	}
	
	/**
	 * Get an instance of the vcd_settings class
	 *
	 * @return vcd_settings
	 */
	protected static function Settings() {
		return VCDClassFactory::getInstance('vcd_settings');
	}
}




?>