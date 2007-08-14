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
<?php

class mediaTypeObj implements XMLable {
	private $media_type_id;
	private $media_type_name;
	private $parent_id;
	private $media_type_description;
	private $children = array();
		
	
	/**
	 * Object constructor
	 *
	 * @param array $dataArr
	 */
	public function __construct($dataArr) {
		$this->media_type_id    	  = $dataArr[0];
		$this->media_type_name   	  = $dataArr[1];
		$this->parent_id		      = $dataArr[2];
		$this->media_type_description = $dataArr[3];
	}
	
	/**
	 * Get mediatype ID
	 *
	 * @return int
	 */
	public function getmediaTypeID() {
		return $this->media_type_id;
	}
	
	/**
	 * Get mediatype name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->media_type_name;
	}
	
	/**
	 * Get full media type name
	 *
	 * @return string
	 */
	public function getDetailedName() {
		if (!isset($this->parent_id)) {
			return $this->getName();
		} else {
			$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");
			$parent_name = $SETTINGSClass->getMediaTypeByID($this->parent_id)->getName();
			return $parent_name . " " . $this->getName();
		}
		
	}
		
	/**
	 * Get parent ID
	 *
	 * @return int
	 */
	public function getParentID() {
		return $this->parent_id;
	}
	
	/**
	 * Get mediatype description
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->media_type_description;
	}
		
	/**
	 * Set media type description
	 *
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->media_type_description = $description;
	}
	
	
	/**
	 * Check if media type object is a parent object
	 *
	 * @return bool
	 */
	public function isParent() {
		if ($this->parent_id == 0) {
			return (bool)true;
		}
		return (bool)false;
	}
	
	/**
	 * Add media type as a child media type of current mediatype object.
	 *
	 * @param mediaTypeObj $obj
	 */
	public function addChild(mediaTypeObj $obj) {
		array_push($this->children, $obj);
	}
	
	/**
	 * Get number of children media type objects.
	 *
	 * @return int
	 */
	public function getChildrenCount() {
		return sizeof($this->children);
	}
	
	/**
	 * Get all children media type objects.
	 * Returns array of media type objects.
	 *
	 * @return array
	 */
	public function getChildren() {
		return $this->children;
	}
	
	/**
	 * Get array with the id and name of current object.
	 *
	 * @return array
	 */
	public function getList() {
        return array("id"   => $this->media_type_id,
                     "name" => $this->media_type_name);
	}

		
	/**
	 * Get the XML representation of current object.
	 *
	 * @return string
	 */
	public function toXML() {
		$xmlstr  = "<mediatype>\n";
		$xmlstr .= "<id>".$this->media_type_id."</id>\n";
		$xmlstr .= "<name>".$this->media_type_name."</name>\n";
		$xmlstr .= "<description>".$this->media_type_description."</description>\n";
		$xmlstr .= "<parentid>".$this->parent_id."</parentid>\n";
		$xmlstr .= "</mediatype>\n";
		if ($this->getChildrenCount() > 0) {
			foreach ($this->getChildren() as $obj) {
				$xmlstr .= $obj->toXML();
			}
		}
		return $xmlstr;
	}
	
	/**
	 * Get this object as SOAP encoded array
	 *
	 * @return array
	 */
	public function toSoapEncoding() {
		
		$children = array();
		foreach ($this->children as $obj) {
			array_push($children, $obj->toSoapEncoding());
		}
		
		return array(
			'media_type_id' => $this->media_type_id,
			'media_type_name' => $this->media_type_name,
			'media_type_description' => $this->media_type_description,
			'parent_id' => $this->parent_id,
			'children' => $children
		);
	}
	



}

?>