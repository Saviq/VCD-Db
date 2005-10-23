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
	require_once("user.php");
	require_once('userSQL.php');
	

	interface User {
		
		/* Users */
		public function getUserByID($user_id);
		public function updateUser($userObj);
		public function deleteUser($user_id, $erase_data = false);
		public function addUser(userObj $userObj);
		public function getAllUsers();
		
		/* Specific users */
		public function getActiveUsers();
		
		/* Login and session routines */
		public function getUserByUsername($user_name);
		public function addSession($session_id, $user_id);
		public function isValidSession($session_id, $session_time, $user_id);
				
		/* Roles / Groups */
		public function getAllUserRoles();
		public function getAllUsersInRole($role_id);
		public function addUserRole($userRoleObj);
		public function deleteUserRole($role_id);
		public function getDefaultRole();
		public function setDefaultRole($role_id);

		/* User Properties */
		public function getAllProperties();
		public function getPropertyById($property_id);
		public function getPropertyByKey($property_key);
		public function addProperty($userPropertiesObj);
		public function deleteProperty($property_id);
		public function updateProperty($userPropertiesObj);
				
		/* Properties on user */
		public function addPropertyToUser($property_id, $user_id);
		public function deletePropertyOnUser($property_id, $user_id);
		public function getAllUsersWithProperty($property_id);
		
		/* Misc user functions */
		public function getUserTopList();
		
	
	}




?>