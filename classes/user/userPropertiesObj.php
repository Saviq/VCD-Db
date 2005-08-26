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
<?php

class userPropertiesObj implements XMLable {
		protected $property_id;
		protected $property_name;
		protected $property_description;
	
	/**
	 * Constructor
	 *
	 * @param array $dataArr
	 * @return userPropertiesObj
	 */
	public function userPropertiesObj($dataArr) {
		$this->property_id     		= $dataArr[0];
		$this->property_name    	= $dataArr[1];
		$this->property_description = $dataArr[2];
	}
	
	/**
	 * Get the property ID
	 *
	 * @return int
	 */
	public function getpropertyID() {
		return $this->property_id;
	}
	
	
	/**
	 * Get the property name
	 *
	 * @return string
	 */
	public function getpropertyName() {
		return $this->property_name;
	}
	
	/**
	 * Get the property description
	 *
	 * @return string
	 */
	public function getpropertyDescription() {
		return $this->property_description;
	}
	
	/**
	 * Set the property description
	 *
	 * @param string $description
	 */
	public function setPropertyDescription($description) {
		$this->property_description = $description;
	}
		
		
	/**
	 * Get the XML representation of the object
	 *
	 * @return string
	 */
	public function toXML() {
		$xmlstr  = "<userproperty>\n";
		$xmlstr .= "<id>".$this->property_id."</id>\n";
		$xmlstr .= "<name>".$this->property_name."</name>\n";
		$xmlstr .= "<description>".$this->property_description."</description>\n";
		$xmlstr .= "</userproperty>\n";
		
		return $xmlstr;
	}
	



}

?>