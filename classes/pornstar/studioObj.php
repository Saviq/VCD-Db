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
 * @subpackage Pornstars
 * @version $Id$
 */
 ?>
<? 
/* 
	Container for the Adult studios ..
*/

class studioObj implements XMLable {

	/**
	 * studio id
	 *
	 * @var int
	 */
	private $id;
	/**
	 * studio name
	 *
	 * @var string
	 */
	private $name;
	
	/**
	 * Constructor
	 *
	 * @param array $dataArr
	 * @return studioObj
	 */
	public function __construct($dataArr) {
		$this->id   = $dataArr[0];
		$this->name = $dataArr[1];
	}
	
	/**
	 * Get studio ID
	 *
	 * @return int
	 */
	public function getID() {
		return $this->id;
	}
	
	/**
	 * Get studio name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Get objects id and name as array.
	 *
	 * Function used for example for dynamicly creating dropdownlists.
	 *
	 * @return array
	 */
	public function getList() {
    	return array("id"   => $this->id,
        	         "name" => $this->name);
	}
	
	
	/**
	 * Get XML for this object
	 *
	 * @return string
	 */
	public function toXML() {
		$xmlstr  = "<studio>\n";
		$xmlstr .= "<id>".$this->id."</id>\n";
		$xmlstr .= "<name><![CDATA[".$this->name."]]></name>\n";
		$xmlstr .= "</studio>\n";
		
		return $xmlstr;
	}

}


?>