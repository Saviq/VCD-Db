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
 * @subpackage Vcd
 * @version $Id$
 */
 
?>
<? 
class vcdObj extends cdObj implements XMLable {

	/* local variables */
	private $category_id;
	private $source_id;	  // for example IMDB
	private $external_id; // for example IMDB ID
	
	
	private $arrDisc_count = array();
	private $arrDate_added = array();
	
	/* local objects or array of objects */
	private $moviecategoryobj;
	private $coversObjArr = array();
	private $ownersObjArr = array();
	private $mediaTypeObjArr = array();
	
	// IMDB Obj
	private $imdbObj;
	
	// Adult related
	private $arrPornstars = array();
	private $arrPorncategories = array();
	private $studio_id;
	private $screenshots = false;
	
	
	/**
	 * Object constructor
	 *
	 * @param array $dataArr
	 */
	public function __construct($dataArr) {
		$this->id 			 = $dataArr[0];
		$this->title 		 = trim($dataArr[1]);
		$this->category_id   = $dataArr[2];
		$this->year			 = $dataArr[3];
	}
	
			
	/**
	* @return void
	* @param userObj $userObj
	* @param mediaTypeObj $mediaTypeObj
	* @param int $disc_count
	* @param datetime $date_added
	* @desc Add an instance to the CD
	*/
	public function addInstance($userObj, $mediaTypeObj, $disc_count, $date_added) {
		array_push($this->ownersObjArr, $userObj);
		array_push($this->mediaTypeObjArr, $mediaTypeObj);
		array_push($this->arrDisc_count, $disc_count);
		array_push($this->arrDate_added, $date_added);
	}
	
	/**
	 * Get records for the instance related to specified user_id
	 *
	 * @param int $user_id
	 * @return array
	 */
	public function getInstancesByUserID($user_id) {
		$arrMediaType = array();
		$arrDiscount = array();
		for ($i = 0; $i < sizeof($this->ownersObjArr); $i++) {
			$userObj = $this->ownersObjArr[$i];
			
			if ($userObj->getUserID() == $user_id) { 
				array_push($arrMediaType, $this->mediaTypeObjArr[$i]);
				array_push($arrDiscount, $this->arrDisc_count[$i]);
			}
			
		}
		
		if (sizeof($arrMediaType) == 0) {
			return null;
		} else {
			return array("mediaTypes" => $arrMediaType, "discs" => $arrDiscount);					
		}
		
		
	}
		
		
	
	/**
	 * Get the number of media types available for this movie
	 *
	 * @return int
	 */
	public function getMediaTypeCount() {
		return sizeof($this->mediaTypeObjArr);
	}
	
	/**
	 * Get the category ID
	 *
	 * @return int
	 */
	public function getCategoryID() {
		return $this->category_id;
	}
	
	/**
	 * Get all cover objects associated with this movie
	 *
	 * @return array
	 */
	public function getCovers() {
		return $this->coversObjArr;
	}
	
	/**
	 * Get the number of covers associated with this movie
	 *
	 * @return int
	 */
	public function getCoverCount() {
		if (is_array($this->coversObjArr)) {
			return sizeof($this->coversObjArr);
		} else {
			return 0;
		}
	}
	
	/**
	 * Get a specific cover that is associated with this movie
	 *
	 * @param string $covername
	 * @return cdcoverObj
	 */
	public function getCover($covername) {
		foreach ($this->coversObjArr as $cdcoverObj) {
			
			if ($cdcoverObj instanceof cdcoverObj ) {
			
				if (strcmp(strtolower($cdcoverObj->getCoverTypeName()), strtolower($covername)) == 0) {
					return $cdcoverObj;
				}
			} else {
				VCDException::display('Object in CoversArray is not a cdcoverObj!');
				return false;
			}
		}
		
		return null;
	}
	
	/**
	 * Get the IMDBObj associated with this movie
	 *
	 * @return imdbObj
	 */
	public function getIMDB() {
		return $this->imdbObj;
	}
			
	/**
	 * Get the movieCategoryObj belonging to this movie
	 *
	 * @return movieCategoryObj
	 */
	public function getCategory() {
		if ($this->moviecategoryobj instanceof movieCategoryObj ) {
			return $this->moviecategoryobj;
		}
		return null;
	}
	
	
	/**
	 * Get the number of copies owned by users of the system
	 *
	 * @return int
	 */
	public function getNumCopies() {
		return sizeof($this->ownersObjArr);
	}
	
	/**
	 * Get the number of CD's this movie is on
	 *
	 * @return int
	 */
	public function getDiscCount() {
		if (sizeof($this->arrDisc_count) > 0) {
			return $this->arrDisc_count[0];
		} else {
			return null;
		}
	}
	
	/**
	 * Set the source site obj associated with this movie
	 *
	 * @param int $source_id
	 * @param string $external_id
	 */
	public function setSourceSite($source_id, $external_id) {
		$this->source_id = $source_id;
		$this->external_id = $external_id;
	}
	
	/**
	 * Get the ID of the sourceSiteObj associated with this movie
	 *
	 * @return int
	 */
	public function getSourceSiteID() {
		return $this->source_id;
	}
	
	/**
	 * Get the external ID of this movie, for example the IMDB ID or the DVDEmpire ID
	 *
	 * @return string
	 */
	public function getExternalID() {
		return $this->external_id;
	}
	
	/**
	 * Set the movieCategoryObj associated with this movie
	 *
	 * @param movieCategoryObj $obj
	 */
	public function setMovieCategory(movieCategoryObj $obj) {
		try {
			$this->moviecategoryobj = $obj;
		} catch (Exception $ex) {
			throw $ex;
		}
		
	}
	
	/**
	 * Set the IMDBObj associated with this movie
	 *
	 * @param imdbObj $obj
	 */
	public function setIMDB(imdbObj $obj) {
		$this->imdbObj = $obj;
	}
	
	/**
	 * Set the pornstars associated with this movie.
	   param $pornstars can either be a pornstarObj or and
	   array containing pornstars objects.
	 *
	 * @param mixed $pornstars
	 */
	public function addPornstars($pornstars) {
		if ($pornstars instanceof pornstarObj) {
			array_push($this->arrPornstars, $pornstars);
		} else if (is_array($pornstars)) {
			$this->arrPornstars = $pornstars;
		}
	}
	
	/**
	 * Get an array of all pornstars associated with this movie.
	 *
	 * @return array
	 */
	public function getPornstars() {
		return $this->arrPornstars;
	}
	
	/**
	 * Get the porncategoryObj associated with this movie
	 *
	 * @param porncategoryObj $obj
	 */
	public function addAdultCategory(porncategoryObj $obj) {
		array_push($this->arrPorncategories, $obj);
	}
	
	/**
	 * Get all adultcategories associated with this movie
	 *
	 * @return array
	 */
	public function getAdultCategories() {
		return $this->arrPorncategories;
	}
	
	/**
	 * Add cdcover objects to this movie.
	 *
	 * @param array $coverArr
	 */
	public function addCovers($coverArr) {
		if (sizeof($this->coversObjArr) > 0) {
			$this->coversObjArr = array_merge($this->coversObjArr, $coverArr);
		} else {
			$this->coversObjArr = $coverArr;
		}
		
	}
	
	/**
	 * Get the mediaTypeObj associated with this movie
	 *
	 * @return array
	 */
	public function getMediaType() {
		if (sizeof($this->mediaTypeObjArr) > 0) {
			return $this->mediaTypeObjArr;
		} else {
			return null;
		}
	}
	
	/**
	 * Add mediaTypeObj to this movie.  No room for duplicate entries.
	 *
	 * @param mediaTypeObj $obj
	 */
	public function addMediaType(mediaTypeObj $obj) {
		if (!in_array($obj, $this->mediaTypeObjArr)) {
			array_push($this->mediaTypeObjArr, $obj);	
		}
		
	}
	
	/**
	 * Check if movie is an adult feature or not.
	 *
	 * @return bool
	 */
	public function isAdult() {
		if ($this->moviecategoryobj instanceof movieCategoryObj ) {
			return strcmp(strtolower($this->moviecategoryobj->getName()), "adult") == 0;
		}
		return false;
	}
	
	/**
	 * Set the adult studio ID of the movie
	 *
	 * @param int $sid
	 */
	public function setStudioID($sid) {
		$this->studio_id = $sid;
	}
	
	/**
	 * Get the adult studio ID of the movie
	 *
	 * @return int
	 */
	public function getStudioID() {
		return $this->studio_id;
	}
	
	
	/**
	 * Flag that this movie has screenshots available.
	 *
	 */
	public function setScreenshots() {
		$this->screenshots = true;
	}
	
	/**
	 * Check if this movie has screenshots.
	 *
	 * @return bool
	 */
	public function hasScreenshots() {
		return $this->screenshots;
	}
	
	/**
	 * Set the number of discs to the current movie instance.
	 *
	 * @param int $disccount
	 */
	public function setDiscCount($disccount) {
		array_push($this->arrDisc_count, $disccount);
	}
	
	/**
	 * Set the date of the movie submission to the database.
	 *
	 * @param datetime $date
	 */
	public function setDateAdded($date) {
		array_push($this->arrDate_added, $date);
	}
	
	/**
	 * Get a string containing all available mediatypes for this movie.
	 *
	 * @return string
	 */
	public function showMediaTypes() {
		
		$strMediaTypes = "";
		$i = 1;
		
		foreach ($this->mediaTypeObjArr as $mediaObj) {
			$strMediaTypes .= $mediaObj->getDetailedName();
			if ($i < $this->getMediaTypeCount()) {
				$strMediaTypes .= ", ";
			}
			$i++;
		}
		
		return $strMediaTypes;
	}

	
	/**
	 * Get the date when movie was added to the database
	 * Returns empty string if date is not found.
	 *
	 * @return date
	 */
	public function getDateAdded() {
		if (is_array($this->arrDate_added) && sizeof($this->arrDate_added) > 0) {
			return date('d-m-Y', $this->arrDate_added[0]);
		} else {
			return "";
		}
	}

	
	
	/**
	 * Print out in a table all user copies of this movie.
	 *
	 */
	public function displayCopies(&$arrMetaData= null) {
				
		print "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\">";
		print "<tr><td>".VCDLanguage::translate('movie.media')."</td><td width=\"1%\">&nbsp;</td><td width=\"1%\">&nbsp;</td><td>".VCDLanguage::translate('movie.num')."</td><td>".VCDLanguage::translate('movie.date')."</td><td>".VCDLanguage::translate('movie.owner')."</td></tr>";
		for ($i = 0; $i < sizeof($this->ownersObjArr); $i++) {
			$owner = $this->ownersObjArr[$i];
			$media = $this->mediaTypeObjArr[$i];
			print "<tr>";
			print "<td>".$media->getDetailedName()."</td>";
			print "<td align=\"center\">".call_user_func('showDVDSpecs', $owner, $media, $arrMetaData)."</td>";
			print "<td align=\"center\">".call_user_func('showNFO', $owner, $media, $arrMetaData)."</td>";
			print "<td>".$this->arrDisc_count[$i]."</td>";
			print "<td>".date("d-m-Y", $this->arrDate_added[$i])."</td>";
			print "<td>".$owner->getUsername()."</td>";
			print "</tr>";

			
		}
		print "</table>";
	}
	
	
	/**
	 * Get both the array of Owners and the array of mediaTypes of this movie instance.
	 * Returns associative array with the data, 'owners' and 'mediatypes'
	 *
	 * @return array
	 */
	public function getInstanceArray() {
		return array('owners' => $this->ownersObjArr, 'mediatypes' => $this->mediaTypeObjArr);
	}
	
			
	/**
	 * Get the RSS data for this movie.
	 *
	 * @return string
	 */
	public function getRSSData() {
		
		$link = "";
		$user = "";
		$date = "";
		
		if (isset($this->external_id)) {
			$SettingsClass = VCDClassFactory::getInstance('vcd_settings');
			$sObj = $SettingsClass->getSourceSiteByID($this->source_id);
			
			$link = str_replace('#', $this->external_id, $sObj->getCommand());
		}
		
		$currOwner = $this->ownersObjArr[0];
		if ($currOwner instanceof userObj ) {
			$user = $currOwner->getUsername();
		}
		$date = $this->arrDate_added[0];
		
		return array('description' => $link, 'creator' => $user, 'date' => $date);
	}
	
	/**
	 * Get the XML representation of the object.
	 *
	 * @return string
	 */
	public function toXML() {
		
		$xmlstr  = "<movie>\n";
		$xmlstr .= "<id>".$this->id."</id>\n";
		$xmlstr .= "<title><![CDATA[".utf8_encode($this->title)."]]></title>\n";
		$xmlstr .= "<category>".$this->moviecategoryobj->getName()."</category>\n";
		$xmlstr .= "<category_id>".$this->moviecategoryobj->getID()."</category_id>\n";
		$xmlstr .= "<year>".$this->year."</year>\n";
		$xmlstr .= "<cds>".$this->arrDisc_count[0]."</cds>\n";
		
		$mediaTypeObj = $this->mediaTypeObjArr[0];
		$xmlstr .= "<mediatype>".$mediaTypeObj->getDetailedName()."</mediatype>\n";
		$xmlstr .= "<mediatype_id>".$mediaTypeObj->getmediaTypeID()."</mediatype_id>\n";
		$xmlstr .= "<dateadded>".$this->arrDate_added[0]."</dateadded>\n";
		$xmlstr .= "<sourcesite_id>".$this->source_id."</sourcesite_id>\n";
		$xmlstr .= "<external_id>".$this->external_id."</external_id>\n";
		if ($this->imdbObj instanceof imdbObj) {
			$xmlstr .= $this->imdbObj->toXML();
		}
		
		if ($this->isAdult() && is_numeric($this->studio_id)) {
			$PORNClass = VCDClassFactory::getInstance('vcd_pornstar');
			$studioObj = $PORNClass->getStudioByID($this->studio_id);
			if ($studioObj instanceof studioObj ) {
				$xmlstr .= $studioObj->toXML();
			}
		}
		
		if ($this->isAdult() && sizeof($this->arrPorncategories) > 0) {
			$xmlstr .= "<adult_category>\n";
			foreach ($this->arrPorncategories as $pornCatObj) {
				$xmlstr .= $pornCatObj->toXML();
			}
			$xmlstr .= "</adult_category>\n";
		}
		
		if (sizeof($this->arrPornstars) > 0) {
			$xmlstr .= "<pornstars>\n";
			foreach ($this->arrPornstars as $starObj) {
				$xmlstr .= $starObj->toXML();
			}
			$xmlstr .= "</pornstars>\n";
		}
		
		// Check for metadata
		$CLASSsettings = VCDClassFactory::getInstance('vcd_settings');
		$arrMeta = $CLASSsettings->getMetadata($this->id, VCDUtils::getUserID(), "", $mediaTypeObj->getmediaTypeID());
		if (is_array($arrMeta) && sizeof($arrMeta) > 0) {
			$xmlstr .= "<meta>\n";
			foreach ($arrMeta as $metaObj) {
				$xmlstr .= $metaObj->toXML();
			}
			$xmlstr .= "</meta>\n";
		} 
		
		// Check for comments
		$arrComments = VCDUtils::filterCommentsByUserID($CLASSsettings->getAllCommentsByVCD($this->id), VCDUtils::getUserID());
		if (is_array($arrComments) && sizeof($arrComments) > 0) {
			$xmlstr .= "<comments>";
			foreach ($arrComments as $commentObj) {
				$xmlstr .= $commentObj->toXML();
			}
			$xmlstr .= "</comments>";
		}

		
		$xmlstr .= "</movie>\n";
		
		return $xmlstr;
	
	}
	
	
	/**
	 * Get this object as SOAP encoded array
	 *
	 * @return array
	 */
	public function toSoapEncoding() {
				
		$imdbObj = null;
		if (!is_null($this->imdbObj)) {
			$imdbObj = $this->imdbObj->toSoapEncoding();
		}
		
		$movieCatData = null;
		if ($this->moviecategoryobj instanceof movieCategoryObj ) {
			$movieCatData = $this->moviecategoryobj->toSoapEncoding();
		}
			
		return array(
			'arrComments'		=> VCDSoapTools::EncodeArray($this->arrComments),
			'arrDate_added'		=> $this->arrDate_added,
			'arrDisc_count'		=> $this->arrDisc_count,
			'arrMetadata'		=> VCDSoapTools::EncodeArray($this->arrMetadata),
			'arrPorncategories'	=> VCDSoapTools::EncodeArray($this->arrPorncategories),
			'arrPornstars'		=> VCDSoapTools::EncodeArray($this->arrPornstars),
			'category_id'		=> $this->category_id,
			'coversObjArr'		=> VCDSoapTools::EncodeArray($this->coversObjArr),
			'external_id'		=> $this->external_id,
			'id'				=> $this->id,
			'imdbObj'			=> $imdbObj,
			'mediaTypeObjArr'	=> VCDSoapTools::EncodeArray($this->mediaTypeObjArr),
			'moviecategoryobj'	=> $movieCatData,
			'ownersObjArr'		=> VCDSoapTools::EncodeArray($this->ownersObjArr),
			'screenshots'		=> $this->screenshots,
			'source_id'			=> $this->source_id,
			'studio_id'			=> $this->studio_id,
			'title'				=> $this->title,
			'year'				=> $this->year
		);
	}
	
	
	/* One time only needed function for insertion of a new vcdObj */
	/**
	 * Get the userid of the owner of this movie's instance
	 *
	 * @return int
	 */
	public function getInsertValueUserID() {
		if (sizeof($this->ownersObjArr) == 1) {
			return $this->ownersObjArr[0]->getUserID();
		} else {
			VCDException::display('No user has been added to CD<break>Cannot continue');
			return false;
		}
	}
	
	/**
	 * Get the mediatypeID for this intance.
	 *
	 * @return int
	 */
	public function getInsertValueMediaTypeID() {
		if (sizeof($this->mediaTypeObjArr) == 1) {
			return $this->mediaTypeObjArr[0]->getmediaTypeID();
		} else {
			VCDException::display('No media type has been added to CD<break>Cannot continue');
			return false;
		}
	}
	
	/**
	 * Get the disccount for this instance.
	 *
	 * @return int
	 */
	public function getInsertValueDiscCount() {
		if (sizeof($this->arrDisc_count) == 1) {
			return $this->arrDisc_count[0];
		} else {
			VCDException::display('No disc count has been added to CD<break>Cannot continue');
			return false;
		}
	}
	
			
	
}


?>