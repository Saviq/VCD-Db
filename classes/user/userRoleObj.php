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
<?php

class userRoleObj implements XMLable {
	
	protected $role_id;
	protected $role_name;
	protected $role_description;

	/**
	 * Class constructor
	 *
	 * @param array $dataArr
	 */
	public function __construct($dataArr) {
		$this->role_id     		= $dataArr[0];
		$this->role_name    	= $dataArr[1];
		$this->role_description = $dataArr[2];
	}
	
	/**
	 * Get the users role ID
	 *
	 * @return int
	 */
	public function getRoleID() {
		return $this->role_id;
	}
	
	/**
	 * Set the users Role ID
	 *
	 * @param int $role_id
	 */
	public function setRoleID($role_id) {
		$this->role_id = $role_id;
	}
	
	/**
	 * Get the users role name
	 *
	 * @return string
	 */
	public function getRoleName() {
		return $this->role_name;
	}
	
	/**
	 * Get the users role description
	 *
	 * @return string
	 */
	public function getRoleDescription() {
		return $this->role_description;
	}
		
	/**
	 * Check if user has administrator priviledges.
	 *
	 * @return bool
	 */
	public function isAdmin() {
		if (strcmp($this->role_name,"Administrator") == 0) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Check if user is in a role that has permissions to view adult material.
	 *
	 * @return bool
	 */
	public function isAdult() {
		if ($this->isAdmin()) {
			return true;
		}
		if (strcmp($this->role_name,"Adult User") == 0) { 
			return true;
		}
		return false;
	}
			
	/**
	 * Get the XML representation of the object
	 *
	 * @return string
	 */
	public function toXML() {
		$xmlstr  = "<role>\n";
		$xmlstr .= "<roleid>".$this->role_id."</roleid>\n";
		$xmlstr .= "<rolename>".$this->role_name."</rolename>\n";
		$xmlstr .= "<roledescription>".$this->role_description."</roledescription>\n";
		$xmlstr .= "</role>\n";
		
		return $xmlstr;
	}
	
	/**
	 * Get the id and name as an associated array
	 *
	 * @return array
	 */
	public function getList() {
        return array("id"   => $this->role_id,
                     "name" => $this->role_name);
	}


	/**
	 * Get this object as SOAP encoded array
	 *
	 * @return array
	 */
	public function toSoapEncoding() {
		return array(
			'role_id'			=> $this->role_id,
			'role_name'			=> $this->role_name,
			'role_description' 	=> $this->role_description
		);
	}

}

?>