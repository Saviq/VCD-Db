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
 * @package Settings
 * @version $Id$
 */
 
?>
<?php

class metadataObj {

	private $metadata_id;
	private $record_id;
	private $user_id;
	private $metadata_name;
	private $metadata_value;
	private $metadata_type_id;
	private $metadata_type_name;
	private $metadata_type_level;
	
	/**
	 * Object constructor
	 *
	 * @param array $dataArr
	 */
	public function __construct($dataArr) {
		$this->metadata_id	       = $dataArr[0];
		$this->record_id	       = $dataArr[1];
		$this->user_id 		       = $dataArr[2];
		$this->metadata_name       = $dataArr[3];
		$this->metadata_value      = $dataArr[4];
		$this->metadata_type_id    = $dataArr[5];
		$this->metadata_type_name  = $dataArr[6];
		$this->metadata_type_level = $dataArr[7];
	}


	/**
	 * Get the metadata ID
	 *
	 * @return int
	 */
	public function getMetadataID() {
		return $this->metadata_id;
	}
	
	/**
	 * Set the metadata ID
	 *
	 * @param int $id
	 */
	public function setMetadataID($id) {
		$this->metadata_id = $id;
	}
	
	/**
	 * Get the record ID associated with this metadata object
	 *
	 * @return int
	 */
	public function getRecordID() {
		return $this->record_id;
	}
	
	/**
	 * Get the user ID of the metadata object
	 *
	 * @return int
	 */
	public function getUserID() {
		return $this->user_id;
	}
	
	/**
	 * Get the metadata key
	 *
	 * @return string
	 */
	public function getMetadataName() {
		return $this->metadata_name;
	}
	
	/**
	 * Get the metadata value
	 *
	 * @return string
	 */
	public function getMetadataValue() {
		return $this->metadata_value;
	}

	/**
	 * Set the metadata value
	 *
	 * @param string $strValue
	 */
	public function setMetadataValue($strValue) {
		$this->metadata_value = $strValue;
	}

	/**
	 * Get the Type ID associated with this metadata object
	 *
	 * @return string
	 */
	public function getMetadataTypeID() {
		return $this->metadata_type_id;
	}

	/**
	 * Get the Type Name associated with this metadata object
	 *
	 * @return string
	 */
	public function getMetadataTypeName() {
		return $this->metadata_type_name;
	}

	/**
	 * Get the Type Level associated with this metadata object
	 *
	 * @return string
	 */
	public function getMetadataTypeLevel() {
		return $this->metadata_type_level;
	}
}


?>