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

class sourceSiteObj implements XMLable {
	private $site_id;
	private $site_name;
	private $site_alias;
	private $site_homepage;
	private $site_getCommand;
	private $isFetchable;
	private $site_classname;
	private $site_image;
	
	/**
	 * Object constructor
	 *
	 * @param array $dataArr
	 * @return sourceSiteObj
	 */
	public function __construct($dataArr) {
		$this->site_id     		= $dataArr[0];
		$this->site_name    	= $dataArr[1];
		$this->site_alias 		= $dataArr[2];
		$this->site_homepage 	= $dataArr[3];
		$this->site_getCommand  = $dataArr[4];
		$this->isFetchable 		= (bool)$dataArr[5];
		$this->site_classname   = $dataArr[6];
		$this->site_image		= $dataArr[7];
	}
	
	/**
	 * Populate object from XML Element
	 *
	 * @param SimpleXmlElement $xml
	 * @return sourceSiteObj
	 */
	public static function __loadFromXML(SimpleXmlElement $xml) {
		$id = (string)$xml->id;
		$name = (string)$xml->name;
		$alias = (string)$xml->alias;
		$homepage = (string)$xml->homepage;
		$command = (string)$xml->command;
		$fetchable = (string)$xml->fetchable;
		$classname = (string)$xml->classname;
		
		return new sourceSiteObj(array($id, $name, $alias, $homepage, $command, $fetchable, $classname, ""));
		
	}
	
	/**
	 * Get the object ID
	 *
	 * @return int
	 */
	public function getsiteID() {
		return $this->site_id;
	}
	
	/**
	 * Get sourceSiteObj name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->site_name;
	}
	
	/**
	 * Get the alias for the sourceSiteObj name
	 *
	 * For examle imdb.com has alias imdb
	 *
	 * @return string
	 */
	public function getAlias() {
		return $this->site_alias;
	}
	
	/**
	 * Get the url belonging to the sourceSite object
	 *
	 * @return string
	 */
	public function getHomepage() {
		return $this->site_homepage;
	}
	
	/**
	 * Get the sourceSite objects fetch command if site supports direct fetching
	 *
	 * @return string
	 */
	public function getCommand() {
		return $this->site_getCommand;
	}
	
	/**
	 * Tells is site is fetchable or not
	 *
	 * @return bool
	 */
	public function isFetchable() {
		return (bool)$this->isFetchable;
	}
	
	/**
	 * Get the name of the PHP fetch class for this site.
	 *
	 * @return string
	 */
	public function getClassName() {
		return $this->site_classname;
	}
		
	/**
	 * Set the image/logo for this sourcesite
	 *
	 * @param string $strImage
	 */
	public function setImage($strImage) {
		$this->site_image = $strImage;
	}
	
	/**
	 * Get the image/logo associated with this sourcesite.
	 *
	 * @return string
	 */
	public function getImage() {
		return $this->site_image;
	}
	
		
	/**
	 * Get the XML representation of the object
	 *
	 * @return string
	 */
	public function toXML() {
		$xmlstr  = "<sourcesite>\n";
		$xmlstr .= "<id>".$this->site_id."</id>\n";
		$xmlstr .= "<name>".$this->site_name."</name>\n";
		$xmlstr .= "<alias>".$this->site_alias."</alias>\n";
		$xmlstr .= "<homepage><![CDATA[".$this->site_homepage."]]></homepage>\n";
		$xmlstr .= "<command><![CDATA[".$this->site_getCommand."]]></command>\n";
		$xmlstr .= "<classname>".$this->site_classname."</classname>\n";
		$xmlstr .= "<fetchable>".VCDUtils::booleanToInt($this->isFetchable)."</fetchable>\n";
		$xmlstr .= "</sourcesite>\n";
		return $xmlstr;
	}
	
	
	/**
	 * Get this object as SOAP encoded array
	 *
	 * @return array
	 */
	public function toSoapEncoding() {
		return array(
			'site_id' => $this->site_id,
			'site_name' => $this->site_name,
			'site_alias' => $this->site_alias,
			'site_homepage' => $this->site_homepage,
			'site_getCommand' => $this->site_getCommand,
			'isFetchable' => $this->isFetchable,
			'site_classname' => $this->site_classname,
			'site_image' => $this->site_image
		);
	}
	



}

?>