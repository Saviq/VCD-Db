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
 * @subpackage User
 * @version $Id$
 */
 
?>
<?php

class userSQL extends VCDConnection  {
	
	private $TABLE_users = "vcd_Users";
	private $TABLE_roles = "vcd_UserRoles";
	private $TABLE_props = "vcd_UserProperties";
	private $TABLE_propsToUser = "vcd_PropertiesToUser";
	private $TABLE_sessions = "vcd_Sessions";
	private $TABLE_vcdtousers = "vcd_VcdToUsers";
	
	private $TABLE_loans 	 = "vcd_UserLoans";
	private $TABLE_wishlist  = "vcd_UserWishList";
	private $TABLE_rss 		 = "vcd_RssFeeds";
	private $TABLE_borrowers = "vcd_Borrowers";
	private $TABLE_metadata  = "vcd_MetaData";
	
		
	public function __construct() {
		parent::__construct();
	}
	
	
	public function getAllUsers() {
		try {
		
		$query = "SELECT U.user_id, U.user_name, U.user_password, U.user_fullname, U.user_email,
				  U.role_id, R.role_name, U.is_deleted, U.date_created
				  FROM $this->TABLE_users U, $this->TABLE_roles R
				  WHERE R.role_id = U.role_id AND
		 		  U.is_deleted = 0
				  ORDER BY U.user_fullname";
		
		$rs = $this->db->Execute($query);
		
		$userObjArr = array();
		foreach ($rs as $row) {
    		$obj = new userObj($row);
    		array_push($userObjArr, $obj);
		}
		
		$rs->Close();
		return $userObjArr;
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	
	public function getActiveUsers() { 
		try {
			
		$query = "SELECT DISTINCT u.user_id,  u.user_name,  u.user_password,  u.user_fullname,  u.user_email, 
				  u.role_id,  r.role_name,  u.is_deleted,  u.date_created
				  FROM $this->TABLE_users u
				  LEFT OUTER JOIN $this->TABLE_roles r ON u.role_id = r.role_id
				  INNER JOIN $this->TABLE_vcdtousers us ON us.user_id = u.user_id
				  WHERE 
				  u.is_deleted = 0 
				  ORDER BY u.user_fullname";
		
						  
		$rs = $this->db->Execute($query);
		
		$userObjArr = array();
		foreach ($rs as $row) {
    		$obj = new userObj($row);
    		array_push($userObjArr, $obj);
		}
		
		$rs->Close();
		return $userObjArr;
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	public function getUserByID($user_id) {
		try {
			
		$query = "SELECT U.user_id, U.user_name, U.user_password, U.user_fullname, U.user_email,
				  U.role_id, R.role_name, U.is_deleted, U.date_created
				  FROM $this->TABLE_users U, $this->TABLE_roles R
				  WHERE U.user_id = ".$user_id." AND R.role_id = U.role_id 
				  AND U.is_deleted = 0";
		
		$rs = $this->db->Execute($query);
		if ($rs && $rs->RecordCount() > 0) {
			return new userObj($rs->FetchRow());
		} else {
			$rs->Close();
			return null;
		}
		
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	
	public function updateUser(userObj $userObj) {
		try {
			
		$query = "UPDATE $this->TABLE_users SET user_password = ".$this->db->qstr($userObj->getPassword()).",
				  user_fullname = ".$this->db->qstr($userObj->getFullname()).",
				  user_email = ".$this->db->qstr($userObj->getEmail()).",
			      role_id = ".$userObj->getRoleID().",
				  is_deleted = ".(int)$userObj->isDeleted()."
				  WHERE user_id = ".$userObj->getUserID()."";
		
		$this->db->Execute($query);
		return true;
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	
	public function addUser(userObj $userObj) {
		try {
			
		$query = "INSERT INTO $this->TABLE_users (user_name, user_password, user_fullname, user_email, 
				  role_id, is_deleted, date_created) VALUES (
				  ".$this->db->qstr($userObj->getUsername()).",
				  ".$this->db->qstr($userObj->getPassword()).",
			      ".$this->db->qstr($userObj->getFullname()).",	
				  ".$this->db->qstr($userObj->getEmail()).",
				  ".$userObj->getRoleID().", 0 , ".$this->db->DBDate(time()).")";
		$this->db->Execute($query);
		
		
		
		/* 	Returns the last autonumbering ID inserted. Returns false if function not supported. 
			Only supported by databases that support auto-increment or object id's,
			such as PostgreSQL, MySQL and MS SQL Server currently. PostgreSQL returns the OID, 
			which can change on a database reload.	*/
			
		$inserted_id = -1;
		
		try {			
			$inserted_id = $this->db->Insert_ID($this->TABLE_users, 'user_id');
		} catch (Exception $ex) {
			// Check if this is a Postgre not using OID columns
			if ($this->isPostgres()) {
				// Yeap, postgres not using OID ..
				$inserted_id = $this->oToID($this->TABLE_users, 'user_id');
			} else {
				throw $ex;
			}
		}
			
			
				
		if (is_numeric($inserted_id) && $inserted_id > 0) {

			return $inserted_id;
			
		} else {
			// InsertedID not supported, we have to dig the latest entry out manually
			$query = "SELECT user_id FROM $this->TABLE_users ORDER BY user_id DESC";
			$rs = $this->db->SelectLimit($query, 1);
			
			// Should only be 1 recordset
			foreach ($rs as $row) {
				$inserted_id = $row[0];
			}
			
			return $inserted_id;
						
		}
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	public function deleteUser($user_id) {
		try {
			
		$query = "UPDATE $this->TABLE_users SET is_deleted = 1 WHERE user_id = " . $user_id;
		$this->db->Execute($query);
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	
	public function eraseUser($user_id) {
		
		try {
		
		$query = "DELETE FROM $this->TABLE_loans WHERE owner_id = " . $user_id;
		$this->db->Execute($query);
		
		$query = "DELETE FROM $this->TABLE_borrowers WHERE owner_id = " . $user_id;
		$this->db->Execute($query);
		
		$query = "DELETE FROM $this->TABLE_propsToUser WHERE user_id = " . $user_id;
		$this->db->Execute($query);
		
		$query = "DELETE FROM $this->TABLE_rss WHERE user_id = " . $user_id;
		$this->db->Execute($query);
		
		$query = "DELETE FROM $this->TABLE_wishlist WHERE user_id = " . $user_id;
		$this->db->Execute($query);
		
		$query = "DELETE FROM $this->TABLE_metadata WHERE user_id = " . $user_id;
		$this->db->Execute($query);
		
		$query = "DELETE FROM $this->TABLE_users WHERE user_id = " . $user_id;
		$this->db->Execute($query);
	
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	public function getAllUserRoles() {
		try {
			
		$query = "SELECT role_id, role_name, role_description FROM $this->TABLE_roles ORDER BY role_name";
		
		$rs = $this->db->Execute($query);
		$userRoleObjArr = array();
		foreach ($rs as $row) {
    		$obj = new userRoleObj($row);
    		array_push($userRoleObjArr, $obj);
		}
		
		$rs->Close();
		return $userRoleObjArr;
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	
	public function getAllUsersInRole($role_id) {
		try {
			
		$query = "SELECT U.user_id, U.user_name, U.user_password, U.user_fullname, U.user_email,
				  U.role_id, R.role_name, U.is_deleted, U.date_created
				  FROM $this->TABLE_users U, $this->TABLE_roles R
				  WHERE R.role_id = U.role_id AND R.role_id = ".$role_id." 
				  ORDER BY U.user_fullname";
		
		$rs = $this->db->Execute($query);
		
		$userObjArr = array();
		foreach ($rs as $row) {
    		$obj = new userObj($row);
    		array_push($userObjArr, $obj);
		}
		
		$rs->Close();
		return $userObjArr;
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	public function deleteUserRole($role_id) {
		try {
			
		$query = "DELETE FROM $this->TABLE_roles WHERE role_id = ". $role_id;
		$this->db->Execute($query);
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	public function getUserByUsername($user_name) {
		try {
			
		$query = "SELECT U.user_id, U.user_name, U.user_password, U.user_fullname, U.user_email,
				  U.role_id, R.role_name, U.is_deleted, U.date_created
				  FROM $this->TABLE_users U, $this->TABLE_roles R
				  WHERE U.user_name = ".$this->db->qstr($user_name)." AND R.role_id = U.role_id ORDER BY U.user_fullname";
		$rs = $this->db->Execute($query);
		if ($rs && $rs->RecordCount() > 0) {
			return new userObj($rs->FetchRow());
		} else {
			return null;
		}
		$rs->Close();	
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
		
	
	/* 
		Sessions
	*/


   	public function addSession($session_id, $user_id, $session_time, $user_ip) {
   		try {
   			
   		$query = "INSERT INTO $this->TABLE_sessions 
   				  (session_id, session_user_id, session_start, session_ip) 
   				  VALUES 
   				  (".$this->db->qstr($session_id).",".$user_id.",".$session_time.",".$this->db->qstr($user_ip).")";
   		$this->db->Execute($query);
   		
   		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
   	}
   			
   	
   	public function getSessionUserID($session_id, $session_start) {
   		try {
   			
   		$query = "SELECT session_user_id FROM $this->TABLE_sessions WHERE 
   				  session_id = ".$this->db->qstr($session_id)." AND
   				  session_start = ".$session_start."";
   		
   		return $this->db->GetOne($query);
   		
   		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
   	}
   	
   	
   	public function updateSession($session_id, $new_sessiontime) {
   		try {
   			
			$query = "UPDATE $this->TABLE_sessions SET session_start = ".$new_sessiontime." 
					  WHERE session_id = ".$this->db->qstr($session_id)."";
			
			$this->db->Execute($query);
			
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
   	}
		
	
	/* 
		User Properties
	*/
	
	public function getAllProperties() {
		try {
			
		$query = "SELECT property_id, property_name, property_description 
				  FROM $this->TABLE_props ORDER BY property_name";
		
		$rs = $this->db->Execute($query);
		$objArr = array();
		foreach ($rs as $row) {
    		$obj = new userPropertiesObj($row);
    		array_push($objArr, $obj);
		}
		
		$rs->Close();
		return $objArr;
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	
	public function deletePropertiesOnUser($user_id) {
		try {
			
		$query = "DELETE FROM $this->TABLE_propsToUser WHERE user_id = " . $user_id;
		
		$this->db->Execute($query);
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	public function addProperty(userPropertiesObj $obj) {
		try {
			
		$query = "INSERT INTO $this->TABLE_props (property_name, property_description) 
				  VALUES (".$this->db->qstr($obj->getpropertyName()).",
				  ".$this->db->qstr($obj->getpropertyDescription()).")";
		$this->db->Execute($query);
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	

	public function deleteProperty($property_id) {
		try {
			
		$query = "DELETE FROM $this->TABLE_props WHERE property_id = " . $property_id;
		$this->db->Execute($query);
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	
	public function updateProperty(userPropertiesObj $obj) {
		try {
			
		$query = "UPDATE $this->TABLE_props SET property_name = ".$this->db->qstr($obj->getpropertyName()).",
				  property_description = ".$this->db->qstr($obj->getpropertyDescription())."
				  WHERE property_id = " . $obj->getpropertyID();
		$this->db->Execute($query);
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	
	public function addPropertyToUser($property_id, $user_id) {
		try {
			
		$query = "INSERT INTO $this->TABLE_propsToUser (user_id, property_id) 
				  VALUES (".$user_id.", ".$property_id.") ";
		
		$this->db->Execute($query);
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	
	public function deletePropertyOnUser($property_id, $user_id) {
		try {
			
		$query = "DELETE FROM $this->TABLE_propsToUser WHERE 
				  user_id = ".$user_id." AND property_id = ".$property_id."";
		$this->db->Execute($query);
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	public function getUsersByPropertyID($property_id) {
		try {
			
		$query = "SELECT U.user_id, U.user_name, U.user_password, U.user_fullname, U.user_email,
				  U.role_id, R.role_name, U.is_deleted, U.date_created
				  FROM $this->TABLE_users U, $this->TABLE_roles R, $this->TABLE_propsToUser P
				  WHERE P.user_id = U.user_id AND
		 		  P.property_id = ".$property_id." AND
				  R.role_id = U.role_id AND
		 		  U.is_deleted = 0
				  ORDER BY U.user_fullname";
		
		$rs = $this->db->Execute($query);
		
		$userObjArr = array();
		foreach ($rs as $row) {
    		$obj = new userObj($row);
    		array_push($userObjArr, $obj);
		}
		
		$rs->Close();
		return $userObjArr;
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	public function getPropertyIDsOnUser($user_id) {
		try {
			
		$query = "SELECT property_id FROM $this->TABLE_propsToUser WHERE user_id = " . $user_id;
		$rs = $this->db->Execute($query);
		if ($rs) {
			$arrPropsIDs = array();
			foreach ($rs as $row) {
				array_push($arrPropsIDs, $row[0]);
			}
			$rs->Close();
			return $arrPropsIDs;
		} else {
			return null;
		}
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	
	public function getUserTopList() {
		try {
			
		$query = "SELECT u.user_fullname, COUNT(t.vcd_id) AS count FROM
				  $this->TABLE_users u, $this->TABLE_vcdtousers t
				  WHERE u.user_id = t.user_id
				  GROUP BY u.user_name
				  ORDER BY count DESC";
		$rs = $this->db->Execute($query);
		
		$results = array();
		
		if ($rs && $rs->RecordCount() > 0) {
			foreach ($rs as $row) {
				array_push($results,array('username'=>$row[0],'count'=>$row[1]));
			}
		}
		
		return $results;
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	
	
	
}


?>
