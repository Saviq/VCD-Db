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
 * @subpackage Settings
 * @version $Id$
 */
 
?>
<?php
class commentObj implements XMLable {

	private $id;
	private $vcd_id;
	private $owner_id;
	private $owner_name;
	private $date;
	private $comment;
	private $isPrivate = false;


	/**
	 * Object contructor
	 *
	 * @param array $dataArr
	 */
	public function __construct($dataArr) {
		
		if (is_array($dataArr)) {
			$this->id 		   = $dataArr[0];
			$this->vcd_id      = $dataArr[1];
			$this->owner_id	   = $dataArr[2];
			$this->date        = $dataArr[3];
			$this->comment 	   = $dataArr[4];
			$this->isPrivate   = (bool)$dataArr[5];
			
			if (isset($dataArr[6])) {
				$this->owner_name = $dataArr[6];
			}
		}
		
		if (!is_bool($this->isPrivate)) {
			$this->isPrivate = false;
		}
	}

	/**
	 * Get the ID of the comment
	 *
	 * @return int
	 */
	public function getID() {
		return $this->id;
	}
	
	/**
	 * Get the ID of the user who made this comment
	 *
	 * @return int
	 */
	public function getOwnerID() {
		return $this->owner_id;
	}
	
	/**
	 * Get the name of the user who wrote the comment
	 *
	 * @return string
	 */
	public function getOwnerName() {
		return $this->owner_name;
	}
	
	/**
	 * Set the username of the author of the comment
	 *
	 * @param string $name
	 */
	public function setOwnerName($name) {
		$this->owner_name = $name;
	}
	
	/**
	 * Get the CD ID that this comment belongs to
	 *
	 * @return int
	 */
	public function getVcdID() {
		return $this->vcd_id;
	}
	
	/**
	 * Set the CD id belonging to this comment
	 *
	 * @param int $id
	 */
	public function setVcdID($id) {
		$this->vcd_id = $id;
	}
	
	
	/**
	 * Get the date of comment creation
	 *
	 * @return date
	 */
	public function getDate() {
		return date("d-m-Y", $this->date);
		
	}
	
	/**
	 * Get the comment text.  $format = true replaces linebreaks with <br>
	 *
	 * @param bool $format
	 * @return string
	 */
	public function getComment($format = false) {
		if ($format) {
			return ereg_replace(13, "<br/>", $this->comment);
		} else {
			return $this->comment;
		}
		
	}
	
	/**
	 * Check if comment is private for author view-ing only
	 *
	 * @return bool
	 */
	public function isPrivate() {
		if (!is_bool($this->isPrivate)) {
			return false;
		} else {
			return $this->isPrivate;	
		}
	}
	

	/**
	 * Get the XML output of this object.
	 *
	 */
	public function toXML() {
		$xmlstr  = "<comment>\n";
		$xmlstr .= "<date>".$this->date."</date>\n";
		$xmlstr .= "<text><![CDATA[".$this->comment."]]></text>\n";
		$xmlstr .= "<isPrivate>".VCDUtils::booleanToInt($this->isPrivate)."</isPrivate>\n";
		$xmlstr .= "</comment>\n";
		
		return $xmlstr;
	}
	
	/**
	 * Get this object as SOAP encoded array
	 *
	 * @return array
	 */
	public function toSoapEncoding() {
		return array(
			'comment'	 => $this->comment,
			'date'		 => $this->date,
			'id'		 => $this->id,
			'isPrivate'  => (bool)$this->isPrivate,
			'owner_id'	 => $this->owner_id,
			'owner_name' => utf8_encode($this->owner_name),
			'vcd_id'	 => $this->vcd_id
		);
	}
	
}
		
?>