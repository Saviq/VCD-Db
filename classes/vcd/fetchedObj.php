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
 * @package Kernel
 * @subpackage Vcd
 * @version $Id$
 */
?>
<?
require_once(dirname(__FILE__).'/imdbObj.php');
require_once(dirname(__FILE__).'/adultObj.php');

/**
 * Base class for all fetched objects with the Fetch Classes.
 * Classes needing more specific data than the generic ones defined in this class should
 * extend this class, and add include directive above.
 *
 */
class fetchedObj {

	protected $objectID;
	protected $title;
	protected $year;
	protected $runtime;
	protected $image;
	protected $covers = array();
	private $sourcesiteID;
	

	public function __construct() {}

	
	/**
	 * Set the Object ID.  The ID of the fetched item from the fetched site.
	 *
	 * @param string $strID
	 */
	public function setObjectID($strID) {
		$this->objectID = $strID;
	}
	

	/**
	 * Get the Object ID.  The ID of the item from the fetched site.
	 *
	 * @return string
	 */
	public function getObjectID() {
		return $this->objectID;
	}
	
	
	/**
	 * Get the movie title
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Set the movie title
	 *
	 * @param string $strTitle
	 */
	public function setTitle($strTitle) {
		$this->title = stripslashes($strTitle);
	}
	
	/**
	 * Get the production year
	 *
	 * @return int
	 */
	public function getYear() {
		if (is_numeric($this->year)) {
			return $this->year;
		} else {
			return 0;
		}

	}

	/**
	 * Set the production year
	 *
	 * @param int $iYear
	 */
	public function setYear($iYear) {
		$this->year = $iYear;
	}
	
	/**
	 * Get the movie runtime in minutes
	 *
	 * @return int
	 */
	public function getRuntime() {
		if (!is_numeric($this->runtime)) {
			return 0;
		} else {
			return $this->runtime;
		}

	}

	/**
	 * Set the movie in minutes
	 *
	 * @param int $iRuntime
	 */
	public function setRuntime($iRuntime) {
		$this->runtime = $iRuntime;
	}

	
		/**
	 * Set the image associated with this movie
	 *
	 * @param string $strImage
	 */
	public function setImage($strImage) {
		$this->image = trim($strImage);
	}

	/**
	 * Get the image associated with this IMDB object
	 *
	 * @return string
	 */
	public function getImage() {
		return $this->image;
	}
	
	
	/**
	 * Set the ID of the sourceSiteObj that created the object
	 *
	 * @param int $iSourceSiteID
	 */
	public function setSourceSite($iSourceSiteID) {
		if (is_numeric($iSourceSiteID)) {
			$this->sourcesiteID = $iSourceSiteID;
		} 
	}
	
	/**
	 * Get the ID of the Sourcesite that created the object
	 *
	 * @return int
	 */
	public function getSourceSiteID() {
		return $this->sourcesiteID;
	}
	
	/**
	 * Add CD-cover to the fetched object
	 *
	 * @param cdcoverObj $coverObj
	 */
	public function addCover(cdcoverObj $coverObj) {
		array_push($this->covers, $coverObj);
	}
	
	/**
	 * Get the CD-covers that have been added to the object.
	 * Returns array of cdcoverObjects.
	 *
	 * @return array
	 */
	public function getCovers() {
		return $this->covers;
	}
	

}



?>