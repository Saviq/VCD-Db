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

class rssObj {
	
	private $id;
	private $owner_id;
	private $name;
	private $url;
	private $isAdult = false;
	private $isSitefeed = false;


	public function __construct($dataArr) {
		if (is_array($dataArr))	{
			$this->id = $dataArr[0];
			$this->owner_id = $dataArr[1];
			$this->name = $dataArr[2];
			$this->url = $dataArr[3];
			if (isset($dataArr[4])) {
				$this->isAdult = $dataArr[4];
			}
			if (isset($dataArr[5])) {
				$this->isSitefeed = $dataArr[5];
			}
		}
	}


	public function getId() {
		return $this->id;
	}
	
	public function getOwnerId() {
		return $this->owner_id;
	}
	
	public function setOwnerId($id) {
		$this->owner_id = $id;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getFeedUrl() {
		return $this->url;
	}
	
	public function setFeedUrl($feedurl) {
		$this->url = $feedurl;
	}
	
	public function isAdult() {
		return $this->isAdult;
	}
	
	public function isVcddbFeed() {
		return $this->isSitefeed;
	}

}
?>