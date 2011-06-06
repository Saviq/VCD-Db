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
 * @subpackage Pornstars
 * @version $Id$
 */
 ?>
<?php
/* 
	Container for the Adult categories ..
*/

class porncategoryObj implements XMLable {

	/**
	 * Porncategory ID
	 *
	 * @var int
	 */
	private $id;
	/**
	 * Porncategory name
	 *
	 * @var string
	 */
	private $name;
	
	/**
	 * Constructor, accepts array as an parameter containing all the objects variables.
	 *
	 * @param array $dataArr
	 * @return porncategoryObj
	 */
	public function __construct($dataArr) {
		$this->id   = $dataArr[0];
		$this->name = $dataArr[1];
	}
	
	/**
	 * Get the object category ID
	 *
	 * @return int
	 */
	public function getID() {
		return $this->id;
	}
	
	/**
	 * Get the object category name
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
	  * Returns the XML reprentation of the cdcover object.
	 *
	 * @return string
	 */
	public function toXML() {
		$xmlstr  = "<category>\n";
		$xmlstr .= "<id>".$this->id."</id>\n";
		$xmlstr .= "<name>".$this->name."</name>\n";
		$xmlstr .= "</category>\n";
		
		return $xmlstr;
	}
	
	/**
	 * Get this object as SOAP encoded array
	 *
	 * @return array
	 */
	public function toSoapEncoding() {
		return array('id' => $this->getID(),'name' => $this->getName());
	}


}


?>