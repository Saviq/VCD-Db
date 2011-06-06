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
 * @subpackage Settings
 * @version $Id$
 */
 
?>
<?php
class borrowerObj {

	private $id;
	private $owner_id;
	private $name;
	private $email;


	/**
	 * Object constructor
	 *
	 * @param array $dataArr
	 */
	public function __construct($dataArr) {
		$this->id 		   = $dataArr[0];
		$this->owner_id    = $dataArr[1];
		$this->name 	   = $dataArr[2];
		$this->email       = $dataArr[3];
	}

	/**
	 * Get the borrower ID
	 *
	 * @return int
	 */
	public function getID() {
		return $this->id;
	}
	
	/**
	 * Get the owners user ID
	 *
	 * @return int
	 */
	public function getOwnerID() {
		return $this->owner_id;
	}
	
	/**
	 * Get the borrower name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Get the borrower email
	 *
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Set the borrower email
	 *
	 * @param string $strEmail
	 */
	public function setEmail($strEmail) {
		$this->email = $strEmail;
	}
	
	/**
	 * Set the borrowers fullname
	 *
	 * @param string $strName | The borrowers name
	 */
	public function setName($strName)
	{
		$this->name = $strName;
	}
	
	
	/**
	 * Get the object id and name as an array for dropdown lists
	 *
	 * @return array
	 */
	public function getList() {
    	return array("id"   => $this->id,
                 "name" => $this->name);
	}
	
	/**
	 * Get this object as SOAP encoded array
	 *
	 * @return array
	 */
	public function toSoapEncoding() {
		return array(
			'email' => $this->email,
			'id' => $this->id,
			'name' => utf8_encode($this->name),
			'owner_id' => $this->owner_id
		);
	}
	
	
}
?>