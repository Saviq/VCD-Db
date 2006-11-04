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
 * @subpackage Settings
 * @version $Id$
 */
 
?>
<? 
class settingsObj implements XMLable {

	private $id;
	private $key;
	private $value;
	private $description;
	private $isProtected;
	private $type;
	
    /**
     * Object constructor.
     *
     * @param array $dataArr
     */
    public function __construct($dataArr) {
		$this->id 		   = $dataArr[0];
		$this->key 		   = $dataArr[1];
		$this->value 	   = $dataArr[2];
		$this->description = $dataArr[3];
		$this->isProtected = $dataArr[4];
		$this->type		   = $dataArr[5];
	}
	
			
	/**
	 * Get the settingsObj ID.
	 *
	 * @return int
	 */
	public function getID() {
		return $this->id;
	}
			
	/**
	 * Get the objects key.
	 *
	 * @return string
	 */
	public function getKey(){
		return $this->key;
	}
	
	/**
	 * Get the objects value.
	 *
	 * @return string
	 */
	public function getValue() {
		return $this->value;
	}
	
	/**
	 * Set the objects value.
	 *
	 * @param string $strValue
	 */
	public function setValue($strValue) {
		$this->value = $strValue;
	}
	
	/**
	 * Check if object is protected.
	 *
	 * If object is protected it can be updated but not deleted from database.
	 *
	 * @return bool
	 */
	public function isProtected() {
		return $this->isProtected;
	}
	
	/**
	 * Set object protection.
	 *
	 * @param bool $isProtected
	 */
	public function setProtected($isProtected) {
		$this->isProtected = $isProtected;
	}
	
	/**
	 * Get the objects description.
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}
	

	/**
	 * Set the objects description.
	 *
	 * @param string $strDescription
	 */
	public function setDescription($strDescription) {
		$this->description = $strDescription;
	}
	
	/**
	 * Get the objects type.
	 *
	 * Type can for example be bool or just empty.
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}
	
	/**
	 * Get the XML representation of the object.
	 *
	 * @return string
	 */
	public function toXML() {
		$xmlstr  = "<setting>\n";
		$xmlstr .= "<key>".$this->key."</key>\n";
		$xmlstr .= "<value>".$this->value."</value>\n";
		$xmlstr .= "<description>".$this->description."</description>\n";
		$xmlstr .= "<protected>".$this->isProtected."</protected>\n";
		$xmlstr .= "<type>".$this->type."</type>\n";
		$xmlstr .= "</setting>\n";
		
		return $xmlstr;
	
	}
}


?>