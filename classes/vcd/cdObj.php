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
 * @subpackage Vcd
 * @version $Id$
 */
 
?>
<? 
/**
	Class cdObj
	Root object for the application.
	For future expansion, all new cd object must derive from this base class
	This class cannot be instance-iated.	
*/

abstract class cdObj {

	/**
	 * The ID of the media item
	 *
	 * @var int
	 */
	protected $id;
	/**
	 * The title of the media item
	 *
	 * @var string
	 */
	protected $title;
	/**
	 * The publishing year of the media
	 *
	 * @var int
	 */
	protected $year;
	
	/**
	 * Array collection of metadataObjects associated with media item
	 *
	 * @var array
	 */
	protected $arrMetadata = array();
	/**
	 * Array collection of commentObjects associated with media item
	 *
	 * @var array
	 */
	protected $arrComments = array();
	
				
	/**
	 * Get the cd objects ID
	 *
	 * @return int
	 */
	public function getID() {
		return $this->id;
	}
					
	/**
	 * Set the cd object ID
	 *
	 * @param int $id
	 */
	public function setID($id) {
		$this->id = $id;
	}		
	
	/**
	 * Get title
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}
	
	/**
	 * Set title
	 *
	 * @param string $strTitle
	 */
	public function setTitle($strTitle) {
		$this->title = $strTitle;
	}
	
	/**
	 * Get year
	 *
	 * @return int
	 */
	public function getYear() {
		return $this->year;
	}
	
	/**
	 * Set year
	 *
	 * @param int $iYear
	 */
	public function setYear($iYear) {
		$this->year = $iYear;
	}
		
	/**
	 * Add metadata Object to media
	 *
	 * @param metadataObj $obj
	 */
	public function addMetaData(metadataObj $obj) {
		array_push($this->arrMetadata, $obj);
	}
	
	/**
	 * Add commentObject to media
	 *
	 * @param commentObj $obj
	 */
	public function addComment(commentObj $obj) {
		array_push($this->arrComments, $obj);
	}
	
	/**
	 * Get all comments associated with media.
	 * Returns array of commentObjects
	 *
	 * @return array
	 */
	public function getComments() {
		return $this->arrComments;
	}
	
	/**
	 * Return all metadataObjects associated with media.
	 * Returns array of metadataObjects.
	 *
	 * @return array
	 */
	public function getMetaData() {
		return $this->arrMetadata;
	}
	
	
	/**
	 * Get the id and name of this object as an array
	 *
	 * @return array
	 */
	public function getList() {
	    return array("id"   => $this->id,
     	             "name" => $this->title);
	}

	
	
}


?>