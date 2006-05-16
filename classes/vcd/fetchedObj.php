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
	

	public function __construct() {}

	
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


}



?>