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
 * @package Fetch
 * @version $Id$
 */
 
?>
<? 
	/**
		Container for data fetched from adultdvdempire		
	*/

class fetch_dvdempire {

	private $id;
	private $title;
	private $year;
	private $studio;
	private $screenshotcount = 0;
	private $cast = array();
	private $categories = array();
	
	private $searchKey;			   // Search frase
	private $contents;			   // The page contents that we will be searching
	private $current_page;  	   // Current Search Page (1 of ?)
	private $hasNextPage = false;  // Does the search have more results ?
	private $result_count = 0;	   // Number of results found
	private $arrResults = array(); // Array of all items found
	private $cache = false;		   // Are the current file contents cached or not
	

	/**
	 * Creates an instance of the fetch_dvdempire object
	 * Takes in filecontents from an alreadt fetched adultDVDempire DVD page
	 * and parses the results
	 *
	 * @param string $filecontents
	 * @param int $empire_id
	 * @param int $current_page
	 * @return fetch_dvdempire
	 */
	public function fetch_dvdempire(&$filecontents = null, $empire_id = -1, $current_page = 1) {

		// Even better way to remove the hex tokens
		$filecontents = preg_replace('[\x00]','',$filecontents); 
		
		// Find where <head> begin .. They put on purpose hexadecimal garbage for 
		// crippling fetch .. above the header ... how rude :)  lets bypass the fuckers
		$headpos = strpos($filecontents, '<head>');
		$this->contents = substr($filecontents, $headpos, strlen($filecontents));
				
		$this->id = $empire_id;
		$this->current_page = $current_page;
	}
	
	/**
	 * Returns the DVDEmpire movie id for the current movie.
	 *
	 * @return int
	 */
	public function getID() {
		return $this->id;
	}
	
	/**
	 * Sets the DVDempire movie ID.
	 *
	 * @param int $strID
	 */
	public function setID($strID) {
		$this->id = $strID;
	}
	
	/**
	 * Sets a flag to tell if search results are cached.
	 *
	 * @param bool $cache_status
	 */
	public function setCached($cache_status) {
		$this->cache = $cache_status;
	}
	
	/**
	 * Get the title for the currently fetched movie
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}
	
	/**
	 * Get the production year for the currently fetched movie
	 *
	 * @return int
	 */
	public function getYear() {
		return $this->year;
	}
	
	/**
	 * Get the studio name for the currently fetched movie
	 *
	 * @return string
	 */
	public function getStudio() {
		return $this->studio;
	}
	
	/**
	 * Get the number of screenshots available for the currently fetched movie
	 *
	 * @return int
	 */
	public function getScreenShotCount() {
		return $this->screenshotcount;
	}
	
	/**
	 * Set the number of screenshots available for this movie
	 *
	 * @param int $count
	 */
	public function setScreenShotsCount($count) {
		$this->screenshotcount = $count;
	}
	
	/**
	 * Get the cast for the currently fetched movie.
	 * Array returned contains array of strings with
	 * actor names.
	 *
	 * @return array
	 */
	public function getCast() {
		return $this->cast;
	}
	
	/**
	 * Get the adult movie categories for the currently fetched movie
	 * Array returned contains array of strings with
	 * adult caegory names.
	 *
	 * @return array
	 */
	public function getCategories() {
		asort($this->categories);
		return array_unique($this->categories);
	}
	
	/**
	 * Set the search key to be used in the search.
	 *
	 * @param string $key
	 */
	public function setSearchKey($key) {
		$this->searchKey = $key;
	}
	
	/**
	 * Get the filecontents for the current parsed page.
	 *
	 * @return string
	 */
	public function getContents()
	{
		return $this->contents;
	}
	
		
	/**
	 * Fetch all the data from the page and fill info
	 * for the currently fetched movie.
	 *
	 */
	public function fetch() {
			
		
		// Check if id has been set ... else try to find it
		if (!isset($this->id) || $this->id == -1) {
			if(!eregi("order_additem.asp[?]userid=([0-9]{14})&amp;item_id=([^<]*)\">", $this->contents, $x)) {
        	    print "Studio ID unset :(";
        	} else {
        		$this->id = $x[2];
        	}	
			
		
		}
		
		// Production Year
		if(!eregi("Production Year: ([0-9]{4})", $this->contents, $x)) {
            //print "Year not found";
        } else {
        	$this->year = $x[1];        
        }
        
        
        // Title 
        if(!eregi("<title>Adult DVD Empire - (.*) - Adult DVD", $this->contents, $x)) {
            //print "No title found!";
        } else {
        	$this->title = $x[1];
        }
              
        	
    	// Studio
    	if(!eregi("<font color=\"white\">i</font><a href=\"/Exec/studio.asp[?]userid=([0-9]{14})&amp;studio_id=([0-9]{3})\">([^<]*)</a><br>", $this->contents, $x)) {
            //print "Studio not found";
        } else {
        	$this->studio = $x[3];        
        }
        
        // Screenshot count
        if(!eregi("topoftabs\">([^<]*) Screen Shots</a>", $this->contents, $x)) {
            //print "No screenshot count found";
            $this->screenshotcount = 0;
        } else {
        	$this->screenshotcount = $x[1];
        }
        
        
        // Actors
        $site = $this->contents;       
        while(eregi('sort=2\'>([^<]*)</a>', $site, $x)) {
            $site = substr($site,strpos($site,$x[0])+strlen($x[0]));
	        array_push($this->cast, $x[1]);
        }
        
        // Categories
        while(eregi('v1_category.asp[?]cat_ref_id=([0-9]{1,5})&userid=([0-9]{14})\">([^<]*)</a><BR>', $site, $x)) {
            $site = substr($site,strpos($site,$x[0])+strlen($x[0]));
	        array_push($this->categories, $x[3]);
	    }
    	
		
		
	}	


	/**
	 * Get search results for the currently parsed contents.
	 *
	 */
	public function search() {
		
		$this->checkNextPage();
		$i = 0;
		while(eregi('item_id=([^"]+)">([^<]*)</a></b>', $this->contents, $x)) {    	
        	$this->contents = substr($this->contents, strpos($this->contents, $x[0])+strlen($x[0]));
        	$this->arrResults[$i]['id'] = $x[1];
        	$this->arrResults[$i]['name'] = $x[2];
        	$i++;
    	}

    	$this->result_count = $i;   	
	}
	
	
	/**
	 * Write out the search results.
	 * Returns false if now results were found,
	 * otherwise true.
	 *
	 * @return bool
	 */
	public function displayResults() {
		if ($this->result_count > 0) {
		
			if ($this->hasNextPage) {
				$next = "<a href=\"./?page=private&o=add&source=dvdempire&offset=".$this->current_page."&key=".urlencode($this->searchKey)."\">View next results &gt;&gt;</a>";
			} else {
				$next = "No more results";
			}
			
			$cachemessage = "";
			if ($this->cache) {
				$cachemessage = " <a href=\"./?page=private&o=add&source=dvdempire&offset=".$this->current_page."&key=".urlencode($this->searchKey)."&cache=off\" title=\"Click for using nocache\">[cached]</a>";
			}
			
			print "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"list\">";
			print "<tr><td colspan=\"2\" class=\"header\" align=\"center\">Search result for ".$this->searchKey." ".$cachemessage."</td>
				   <td colspan=\"2\" class=\"header\" align=\"center\">".$next."</td></tr>";
			
			$i = 0;
			
			foreach ($this->arrResults as $arr) {

				if (($i % 4) == 0) { print "<tr>"; }
				
				print "<td align=\"center\" valign=\"top\" style=\"padding-top:5px;\">
					   <a href=\"./?page=private&o=add&source=dvdempire&id=".$arr['id']."&key=".$arr['name']."\" title=\"Fetch this movie\">
					   ".$this->generateEmpireMiniThumb($arr['id'])."</a><br/><strong>".$arr['name']."</strong><br/>
					   <a href=\"".$this->getUrl($arr['id'])."\" target=\"new\">[Info]</td>";
				
				if (($i % 4) == 0 && $i = 0) { print "</tr>"; }
				
				$i++;

			} 
			
						
			print "</table>";
			return true;
			
		} else {
			return false;
		}
		
	}
	
	
	/**
	 * Get the Full HTTP image path for the asked for image
	 * on the DVDEmpire server.
	 * Valid image types are
	 * thumbnail, VCD Front Cover, VCD Back cover and screenshots.
	 * All except screenshots return strings, screenshots returns an
	 * array of all screenshot images for that movie.
	 *
	 * @param string $image_type
	 * @return mixed.
	 */
	public function getImagePath($image_type) {
	
		$folder = substr($this->id,0,1);
		$imagebase = "http://images.dvdempire.com/res/movies/".$folder."/".$this->id;
		
		switch ($image_type) {
			case 'thumbnail':
				return $imagebase.".jpg";
				break;
				
			case 'VCD Front Cover':
				return $imagebase."h.jpg";
				break;
				
			case 'VCD Back Cover':
				return $imagebase."bh.jpg";
				break;
		
			case 'screenshots':
				// Return array of all screenshots
				$screenbase = "http://images.dvdempire.com/res/movies/screenshots/".$folder."/".$this->id;
				$screens = array();
				for($i = 1; $i <= $this->screenshotcount; $i++) {
					$path = $screenbase."_".$i."l.jpg";
					array_push($screens, $path);
				}
				
				return $screens;
				
				break;
				
				
			default:
				return false;
				break;
		}
	
	}
	
	/**
	 * Check if search results have another page.
	 *
	 */
	private function checkNextPage() {
		if(!eregi("272744>Next Page &gt;</font>", $this->contents, $x)) {
            $this->hasNextPage = false;
        } else {
        	$this->current_page++;
        	$this->hasNextPage = true;
        }
		
	}
	
	/**
	 * Get the full DVDEmpire URL for the movie with the 
	 * given id.
	 *
	 * @param int $empire_id
	 * @return string
	 */
	private function getUrl($empire_id) {
		return "http://www.adultdvdempire.com/Exec/v1_item.asp?item_id=".$empire_id;
	}
	
	
  	/**
  	 * Get the thumbnails for the movies to display in the
  	 * search results.
  	 *
  	 * @param int $empire_id
  	 * @return string
  	 */
  	private function generateEmpireMiniThumb($empire_id) {
  		$folder = substr($empire_id,0,1);	
  		$uri = "http://images.dvdempire.com/res/movies/".$folder."/".$empire_id."t.jpg";
  		return "<img src=\"".$uri."\" border=\"0\" width=\"70\" height=\"100\" class=\"imgx\">";
 	}
 	
 	
 	
	

}


?>