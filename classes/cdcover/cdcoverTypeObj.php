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
 * @subpackage CDCover
 * @version $Id$
 */
 
?>
<?PHP

class cdcoverTypeObj implements XMLable {

	/**
	 * coverType id
	 *
	 * @var int
	 */
	protected $covertype_id;
	/**
	 * coverType name
	 *
	 * @var string
	 */
	protected $covertypeName;
	/**
	 * coverType description
	 *
	 * @var string
	 */
	protected $coverTypeDescription;
	
	
	/**
	 * Constructor, accepts array as parameter containing all the objects variables.
	 *
	 * @param array $dataArr
	 * @return cdcoverTypeObj
	 */
	public function __construct($dataArr) {
		$this->covertype_id = $dataArr[0];
		$this->covertypeName = $dataArr[1];
		$this->coverTypeDescription = $dataArr[2];
	}
	
	/**
	 * Get the coverType id
	 *
	 * @return int
	 */
	public function getCoverTypeID() {
		return $this->covertype_id;
	}
	
	/**
	 * Set the coverType id
	 *
	 * @param int $type_id
	 */
	public function setCoverTypeID($type_id) {
		$this->covertype_id = $type_id;
	}
	
	/**
	 * Get the coverType name
	 *
	 * @return string
	 */
	public function getCoverTypeName() {
		return $this->covertypeName;
	}
	
	/**
	 * Set the coverType name
	 *
	 * @param string $strName
	 */
	public function setCoverTypeName($strName) {
		$this->covertypeName = $strName;
	}
	
	
	/**
	 * Check if current coverTypeID has the thumbnail id.
	 * Used by cdcover objects to get information about themselves.
	 *
	 * @return boolean
	 */
	public function isThumbnail() {
		return (strcmp(strtolower($this->covertypeName), "thumbnail") == 0);
	}
	
	/**
	 * Get coverType description
	 *
	 * @return string
	 */
	public function getCoverTypeDescription() {
		return $this->coverTypeDescription;
	}
	
	/**
	 * Get objects id and name as array.
	 *
	 * Function used for example the dynamicly creating dropdownlists.
	 *
	 * @return array
	 */
	public function getList() {
    	return array("id"   => $this->covertype_id,
                 	"name" => $this->covertypeName);
	}
	
	/**
	 * Returns the XML reprentation of the cdcover object.
	 *
	 * @return string
	 */
	public function toXml() {
		$xmlstr  = "<cdcovertype>\n";
		$xmlstr .= "<covertype_id>".$this->covertype_id."</covertype_id>\n";
		$xmlstr .= "<typename>".$this->covertypeName."</typename>\n";
		$xmlstr .= "<description>".$this->coverTypeDescription."</description>\n";
		$xmlstr .= "</cdcovertype>\n";
		
		return $xmlstr;
	}
	
	public function toSoapEncoding() {
		return array(
			'covertype_id' 			=> $this->covertype_id,
			'coverTypeDescription' 	=> $this->coverTypeDescription,
			'covertypeName'			=> $this->covertypeName,
		);
	}
		
		
}
?>