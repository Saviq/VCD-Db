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
	
	/**
	 * The ID of the RSS feed
	 *
	 * @var int
	 */
	private $id;
	/**
	 * The owner ID of the RSS feed, 0 = system owner visible
	 * to all VCD-db users.
	 *
	 * @var int
	 */
	private $owner_id = 0;
	/**
	 * The name of the RSS feed
	 *
	 * @var string
	 */
	private $name;
	/**
	 * The actual RSS Feed URL
	 *
	 * @var string
	 */
	private $url;
	/**
	 * Is this RSS feed pointing to adult content or not
	 *
	 * @var bool
	 */
	private $isXrated;
	/**
	 * Is this feed a VCD-db RSS feed from remote VCD-db site
	 *
	 * @var bool
	 */
	private $isSitefeed;


	/**
	 * Object contstructor
	 *
	 * @param array $dataArr | Array populated from database
	 */
	public function __construct($dataArr) {
		$this->isXrated = 0;
		$this->isSitefeed = 0;
		
		if (is_array($dataArr))	{
			$this->id = $dataArr[0];
			$this->owner_id = $dataArr[1];
			$this->name = $dataArr[2];
			$this->url = $dataArr[3];
			if (isset($dataArr[4])) {
				$this->isXrated = $dataArr[4];
			}
			if (isset($dataArr[5])) {
				$this->isSitefeed = $dataArr[5];
			}
		}
	}

	/**
	 * Get the feed ID
	 *
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Get the owner ID of the RSS feed
	 *
	 * @return int
	 */
	public function getOwnerId() {
		return $this->owner_id;
	}
	
	/**
	 * Set the owner Id of the RSS feed, 0 = user global feed
	 *
	 * @param int $id
	 */
	public function setOwnerId($id) {
		$this->owner_id = $id;
	}
	
	/**
	 * Get the name of the RSS feed
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Set the name of the RSS feed
	 *
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}
	
	/**
	 * Get the RSS feed url
	 *
	 * @return string
	 */
	public function getFeedUrl() {
		return $this->url;
	}
	
	/**
	 * Set the RSS feed url
	 *
	 * @param string $feedurl
	 */
	public function setFeedUrl($feedurl) {
		$this->url = $feedurl;
	}
	
	/**
	 * Check if the RSS feed points to adult content
	 *
	 * @return bool
	 */
	public function isAdultFeed() {
		if (is_bool($this->isXrated)) {
			return false;
		} else {
			return $this->isXrated;	
		}
	}
	
	/**
	 * Set the RSS Feed as adult feed or not
	 *
	 * @param bool $bool
	 */
	public function setAdult($bool) {
		$this->isXrated = (int)$bool;
	}
	
	/**
	 * Check if the feed belongs to another VCD-db web
	 *
	 * @return bool
	 */
	public function isVcddbFeed() {
		return $this->isSitefeed;
	}
	
	/**
	 * Set the RSS feed as a VCD-db site feed
	 *
	 * @param bool $bSitefeed
	 */
	public function setAsSiteFeed($bSitefeed) {
		$this->isSitefeed = (int)$bSitefeed;
	}
	
	/**
	 * Get this object as SOAP encoded array
	 *
	 * @return array
	 */
	public function toSoapEncoding() {
		return array(
			'id' => $this->id,
			'isSitefeed' => $this->isVcddbFeed(),
			'isXrated'	=> $this->isAdultFeed(),
			'name' => $this->name,
			'owner_id' => $this->owner_id,
			'url' => $this->url
		);
	}

}
?>