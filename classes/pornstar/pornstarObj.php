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
if (!defined("PORNSTARIMAGE_PATH")) {
	define("PORNSTARIMAGE_PATH","upload/pornstars/");
}

class pornstarObj implements XMLable {

	/**
	 * pornstar id
	 *
	 * @var int
	 */
	private $id;
	/**
	 * pornstar name
	 *
	 * @var string
	 */
	private $name;
	/**
	 * pornstar homepage 
	 *
	 * @var string
	 */
	private $homepage;
	/**
	 * image name if any
	 *
	 * @var string
	 */
	private $image;
	/**
	 * pornstar biography
	 *
	 * @var string
	 */
	private $biography;
	/**
	 * array of movie with pornstar listed as cast,
	 * assoc array [id] - [title] {basic info}
	 *
	 * @var array
	 */
	private $movies = array();	
	/**
	 * number of movies with pornstar listed as actor
	 *
	 * @var int
	 */
	private $movie_count;
	
	/**
	 * Contructor
	 *
	 * @param array $dataArr
	 * @return pornstarObj
	 */
	public function __construct($dataArr) {
		$this->id 		   = $dataArr[0];
		$this->name 	   = $dataArr[1];
		$this->homepage    = $dataArr[2];
		$this->image 	   = $dataArr[3];
		if (isset($dataArr[4])) {
			$this->biography   = $dataArr[4];
		}
		
		if (isset($dataArr[5])) {
			$this->movie_count = $dataArr[5];
		}
		
	}
	
	/**
	 * Set the pornstar ID
	 *
	 * @param int $pornstar_id
	 */
	public function setID($pornstar_id) {
		$this->id = $pornstar_id;
	}
	
	/**
	 * Get the pornstar ID
	 *
	 * @return int
	 */
	public function getID() {
		return $this->id;
	}
	
	/**
	 * Get the pornstar name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Set the pornstar name
	 *
	 * @param string $strName
	 */
	public function setName($strName) {
		$this->name = $strName;
	}
			
	/**
	 * Get the weburl for pornstar homepage
	 *
	 * @return string
	 */
	public function getHomepage() {
		return $this->homepage;
	}
	
	/**
	 * Set the pornstars homepage
	 *
	 * @param string $strHomepage
	 */
	public function setHomePage($strHomepage) {
		$this->homepage = $strHomepage;
	}
	
	/**
	 * Get pornstars biography
	 *
	 * @return string
	 */
	public function getBiography() {
		return $this->biography;
	}
	
	/**
	 * Set the pornstars biography or details
	 *
	 * @param string $strBio
	 */
	public function setBiography($strBio)	{
		$this->biography = $strBio;
	}
	
	/**
	 * Set the pornstars movie list
	 *
	 * @param array $arrMovies
	 */
	public function setMovies($arrMovies) {
		$this->movies = $arrMovies;
	}
	
	/**
	 * Get the movielist where this pornstars is listed in.
	 *
	 * @return array
	 */
	public function getMovies() {
		return $this->movies;
	}
	
	/**
	 * Get number of movies this pornstar is listed in as an actor
	 *
	 * @return int
	 */
	public function getMovieCount() {
		if (is_numeric($this->movie_count)) {
			return $this->movie_count;
		} else {
			return sizeof($this->movies);
		}
		
	}
			
	
	/**
	 * Return the filename of the pornstar thumbnail
	 *
	 * @return string
	 */
	public function getImageName() {
		return $this->image;
	}
	
	/**
	 * Sets the pornstars thumbnail filename
	 *
	 * @param string $image_name
	 */
	public function setImageName($image_name) {
		$this->image = $image_name;
	}
	
	/**
	 * Get the link to this actor in the Internet Adult Film Database
	 *
	 * @return string
	 */
	public function getIAFD()	{
		$tname = str_replace(" ", "+", trim($this->name, ""));
		$iafd = "http://www.iafd.com/ramesearch.asp?searchtype=iafd-perf&amp;searchstring=".$tname;
		$link = "<a href=\"".$iafd."\" target=\"_blank\" title=\"Internet Adult Film Database\">IAFD</a>";
		return $link;
	}
	
	/**
	 * Print the HTML IMG string for this pornstars thumbnail image.
	 *
	 * Param prefix can point to a folder down in the tree if desireable.
	 *
	 * @param string $prefix
	 */
	public function showImage($prefix = "") {
		
		$filenotfoundImage = "notfoundimagestar.gif";
		
		if (VCDDB_USEPROXY==1) {
			
			if (isset($this->image) && strlen($this->image) > 3) {
				print "<a href=\"./?page=pornstar&amp;pornstar_id=".$this->id."\"><img src=\"".VCDDB_SOAPPROXY.$prefix.PORNSTARIMAGE_PATH . $this->image."\" class=\"imgx\" alt=\"\" title=\"".$this->name."\" width=\"145\" height=\"200\" border=\"0\"/></a>";
			} else {
				print "<a href=\"./?page=pornstar&amp;pornstar_id=".$this->id."\"><img src=\"".$prefix."images/noimagestar.gif\" border=\"0\" alt=\"\" title=\"".$this->name."\" class=\"imgx\"/></a>";
			}	
			
		} else {
			if (!file_exists($prefix.PORNSTARIMAGE_PATH.$this->image) && isset($this->image)) { 
				print "<a href=\"./?page=pornstar&amp;pornstar_id=".$this->id."\"><img src=\"".$prefix."images/{$filenotfoundImage}\" border=\"0\" alt=\"\" title=\"".$this->name."\" class=\"imgx\"/></a>";
			} else if (isset($this->image) && strlen($this->image) > 3) {
				print "<a href=\"./?page=pornstar&amp;pornstar_id=".$this->id."\"><img src=\"".$prefix.PORNSTARIMAGE_PATH . $this->image."\" class=\"imgx\" alt=\"\" title=\"".$this->name."\" width=\"145\" height=\"200\" border=\"0\"/></a>";
			} else {
				print "<a href=\"./?page=pornstar&amp;pornstar_id=".$this->id."\"><img src=\"".$prefix."images/noimagestar.gif\" border=\"0\" alt=\"\" title=\"".$this->name."\" class=\"imgx\"/></a>";
			}	
		}
	}
	
	
	
	/**
	 * Print the HTML IMG string for this pornstars thumbnail image.
	 */
	public function getImageLink() {
		
		$html = "";
		if (isset($this->image) && strlen($this->image) > 3) {
			
			$img = '<img src="%s" alt="%s" title ="%s" class="imgx" border="0" width="145" height="200"/>';
			if (VCDDB_USEPROXY==1) {
				$html = sprintf($img, VCDDB_SOAPPROXY.'?page=file&amp;pornstar_id='.$this->id, $this->name, $this->name);
			} else {
				$html = sprintf($img, '?page=file&amp;pornstar_id='.$this->id, $this->name, $this->name);
			}
			
		} else {
			$img = '<img src="images/noimagestar.gif" title="%s" class="imgx" alt="%s" border="0"/>';
			$html = sprintf($img, $this->name, $this->name);
		}
		
		return $html;
			
	}
	
	
	/**
	 * Get this object as XML
	 *
	 * @return string
	 */
	public function toXML() {
		$xmlstr  = "<pornstar>\n";
		$xmlstr .= "<id>".$this->id."</id>\n";
		$xmlstr .= "<name>".$this->name."</name>\n";
		$xmlstr .= "<homepage>".$this->homepage."</homepage>\n";
		$xmlstr .= "<image>".$this->image."</image>\n";
		$xmlstr .= "<biography><![CDATA[".$this->biography."]]></biography>\n";
		$xmlstr .= "</pornstar>\n";
		
		return $xmlstr;
	}
	
	
	/**
	 * Get this object as SOAP encoded array
	 *
	 * @return array
	 */
	public function toSoapEncoding() {
		
		$moviedata = array();
		if (is_array($this->movies)) {
			foreach ($this->movies as $id => $title) {
				array_push($moviedata, $id.'|'.$title);
			}
		}
		
		return array(
			'biography' => $this->getBiography(),
			'homepage' => $this->getHomepage(),
			'id' => $this->getID(),
			'image' => $this->getImageName(),
			'movie_count' => $this->getMovieCount(),
			'movies' => $moviedata,
			'name' => $this->getName()
		);
	}
	
}


?>