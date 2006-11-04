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
 * @subpackage User
 * @version $Id$
 */
 
?>
<? 
require_once(dirname(__FILE__).'/userRoleObj.php');
require_once(dirname(__FILE__).'/userPropertiesObj.php');

class userObj extends userRoleObj implements XMLable { 
	
	private $user_id;
	private $username;
	private $password;
	private $fullname;
	private $email;
	private $isDeleted;
	private $dateCreated;
	private $userPropertiesArr = array();
	private $isDirectoryUser = false;
		
	
	/**
	 * Constuctor
	 *
	 * @param array $dataArr
	 * @return userObj
	 */
	public function __construct($dataArr) {
		$this->user_id     = $dataArr[0];
		$this->username    = $dataArr[1];
		$this->password    = $dataArr[2];
		$this->fullname    = $dataArr[3];
		$this->email       = $dataArr[4];
		$this->role_id     = $dataArr[5];
		$this->role_name   = $dataArr[6];
		$this->isDeleted   = $dataArr[7];
		$this->dateCreated = $dataArr[8];
	}
	
	/**
	 * Set the user's role
	 *
	 * @param userRoleObj $userRoleObj
	 */
	public function setRole(userRoleObj $userRoleObj) {
		$this->role_id = $userRoleObj->getRoleID();
		$this->role_name = $userRoleObj->getRoleName();
		$this->role_description = $userRoleObj->getRoleDescription();
	}
	
	/**
	 * Set the user's password
	 *
	 * @param string $new_password
	 */
	public function setPassword($new_password) {
		$this->password = $new_password;
	}
	
	/**
	 * Set the users's name
	 *
	 * @param string $name
	 */
	public function setName($name) {
		$this->fullname = $name;
	}
	
	/**
	 * Set the users's email
	 *
	 * @param string $email
	 */
	public function setEmail($email) {
		$this->email = $email;
	}
	
	/**
	 * Add a property object to user
	 *
	 * @param userPropertiesObj $prop
	 */
	public function addProperty(userPropertiesObj $prop) {
		array_push($this->userPropertiesArr, $prop);
	}
	
	/**
	 * Flush all property objects that are associated with user
	 *
	 */
	public function flushProperties() {
		unset($this->userPropertiesArr);
		$this->userPropertiesArr = array();
	}
	
	/**
	 * Get the user's user ID
	 *
	 * @return int
	 */
	public function getUserID() {
		return $this->user_id;
	}
	
	/**
	 * Get the user's name
	 *
	 * @return string
	 */
	public function getFullname() {
		return $this->fullname;
	}
	
	/**
	 * Get the user's username
	 *
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}
		
	/**
	 * Get the user's passord
	 *
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}
	
		
	/**
	 * Get the user's email
	 *
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}
	
	/**
	 * Check if used has been marked deleted in database.
	 *
	 * @return bool
	 */
	public function isDeleted() {
		return $this->isDeleted;
	}
	
	/**
	 * Get the date when this user was created.
	 *
	 * @return date
	 */
	public function getDateCreated() {
		return $this->dateCreated;
	}
		
	/**
	 * Get an array with all user's properties
	 *
	 * @return array
	 */
	public function getUserProperties() {
		if (isset($this->user_id)) {
			return $this->userPropertiesArr;	
		}
	}
	
	/**
	 * Check if user is assigned to specified property.
	 *
	 * Returns true if user is associated with the specified property key, 
	 * otherwise returns false.
	 *
	 * @param string $property_name
	 * @return bool
	 */
	public function getPropertyByKey($property_name) {
		if (isset($this->userPropertiesArr)) {
			foreach ($this->userPropertiesArr as $propObj) {
				if (strcmp($propObj->getpropertyName(), $property_name) == 0) {
					return true;
				}
			}
		}
		return false;
	}
	
	
	/**
	 * Mark the current user object as a LDAP Directory authenticated user.
	 *
	 * @param bool $value
	 */
	public function setDirectoryUser($value) {
		$this->isDirectoryUser = $value;
	}
	
	/**
	 * Check if user was authenticated via LDAP Directory or not.
	 *
	 * @return bool
	 */
	public function isDirectoryUser() {
		return $this->isDirectoryUser;
	}
	
	/**
	 * Get the XML representation of the user object
	 *
	 * @return string
	 */
	public function toXML() {
		$xmlstr  = "<user>\n";
		$xmlstr .= "<userid>".$this->user_id."</userid>\n";
		$xmlstr .= "<roleid>".$this->role_id."</roleid>\n";
		$xmlstr .= "<username>".$this->username."</username>\n";
		$xmlstr .= "<password>".$this->password."</password>\n";
		$xmlstr .= "<fullname>".$this->fullname."</fullname>\n";
		$xmlstr .= "<email>".$this->email."</email>\n";
		$xmlstr .= "<isdeleted>".$this->isDeleted."</isdeleted>\n";
		$xmlstr .= "<datecreated>".$this->dateCreated."</datecreated>\n";
		$xmlstr .= "</user>\n";
		
		return $xmlstr;
	}
	
	
}

?>