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
class settingsSQL extends VCDConnection  {

	private $TABLE_settings   = "vcd_Settings";
	private $TABLE_sites      = "vcd_SourceSites";
	private $TABLE_mediatypes = "vcd_MediaTypes";
	private $TABLE_categories = "vcd_MovieCategories";
	private $TABLE_vcd		  = "vcd";
	private $TABLE_vcdtousers = "vcd_VcdToUsers";
	private $TABLE_borrowers  = "vcd_Borrowers";
	private $TABLE_loans 	  = "vcd_UserLoans";
	private $TABLE_rss		  = "vcd_RssFeeds";
	private $TABLE_wishlist	  = "vcd_UserWishList";
	private $TABLE_comments	  = "vcd_Comments";
	private $TABLE_users	  = "vcd_Users";
	private $TABLE_covers 	  = "vcd_Covers";
	private $TABLE_metadata   = "vcd_MetaData";
	private $TABLE_metatypes  = "vcd_MetaDataTypes";
	private $TABLE_propstousr = "vcd_PropertiesToUser";

	public function __construct() {
		parent::__construct();
	}


	public function getAllSettings() {
		try {

		$query = "SELECT settings_id, settings_key, settings_value, settings_description, isProtected, settings_type FROM
				  $this->TABLE_settings ORDER BY settings_key";

		$rs = $this->db->Execute($query);

		$arrSettingsObj = array();
		foreach ($rs as $row) {
    		$obj = new settingsObj($row);
    		array_push($arrSettingsObj, $obj);
		}

		$rs->Close();
		return $arrSettingsObj;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function updateSettings($settingsObj) {
		try {

		$query = "UPDATE $this->TABLE_settings SET
				  settings_key = ".$this->db->qstr($settingsObj->getKey()).",
				  settings_value = ".$this->db->qstr($settingsObj->getValue()).",
				  settings_description = ".$this->db->qstr($settingsObj->getDescription()).",
				  isProtected = ".(int)$settingsObj->isProtected()."
				  WHERE settings_id = ".$settingsObj->getID()."";
		$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function saveSettings($settingsObj) {
		try {

		if (is_array($settingsObj)) {

			foreach ($settingsObj as $obj) {
				$query = "INSERT INTO $this->TABLE_settings (settings_key, settings_value, settings_description, isProtected)
						  VALUES (".$this->db->qstr($settingsObj->getKey()).",  ".$this->db->qstr($settingsObj->getValue()).",
						  ".$this->db->qstr($settingsObj->getDescription()).", ".(int)$settingsObj->isProtected().")";
				
				$this->db->Execute($query);
			}

		} else {
			$query = "INSERT INTO $this->TABLE_settings (settings_key, settings_value, settings_description, isProtected)
					  VALUES (".$this->db->qstr($settingsObj->getKey()).",".$this->db->qstr($settingsObj->getValue()).",
					  ".$this->db->qstr($settingsObj->getDescription()).", ".(int)$settingsObj->isProtected().")";
			$this->db->Execute($query);
		}

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function deleteSettings($settings_id) {
		try {

		$query = "DELETE FROM $this->TABLE_settings WHERE settings_id = " .$settings_id;
		$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function getSettingsByID($settings_id) {
		try {

		$query = "SELECT settings_id, settings_key, settings_value, settings_description, isProtected, settings_type FROM
				  $this->TABLE_settings WHERE settings_id = ". $settings_id;
		$rs = $this->db->Execute($query);
		if ($rs) {
			return new settingsObj($rs->FetchRow());
		}

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}



	/*
		SOURCE SITE FUNCTIONS

	*/

	public function getSourceSites() {
		try {

		$query = "SELECT site_id, site_name, site_alias, site_homepage, site_getCommand, 
				  site_isFetchable, site_classname, site_image
				  FROM $this->TABLE_sites ORDER BY site_name";

		$rs = $this->db->Execute($query);

		$arrObj = array();
		foreach ($rs as $row) {
    		$obj = new sourceSiteObj($row);
    		array_push($arrObj, $obj);
		}

		$rs->Close();
		return $arrObj;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function addSourceSite(sourceSiteObj $obj) {
		try {

		$query = "INSERT INTO $this->TABLE_sites
				  (site_name, site_alias, site_homepage, site_getCommand, site_isFetchable, site_classname, site_image) VALUES
				  (".$this->db->qstr($obj->getName()).",
				   ".$this->db->qstr($obj->getAlias()).",
				   ".$this->db->qstr($obj->getHomepage()).",
				   ".$this->db->qstr($obj->getCommand()).",".(int)$obj->isFetchable().",
				   ".$this->db->qstr($obj->getClassName()).",
				   ".$this->db->qstr($obj->getImage())."
				   )";

		$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function updateSourceSite(sourceSiteObj $obj) {
		try {

		$query = "UPDATE $this->TABLE_sites SET
				  site_name = ".$this->db->qstr($obj->getName()).",
				  site_alias = ".$this->db->qstr($obj->getAlias()).",
				  site_homepage = ".$this->db->qstr($obj->getHomepage()).",
				  site_getCommand = ".$this->db->qstr($obj->getCommand()).",
				  site_isFetchable = ".(int)$obj->isFetchable().",
				  site_classname = ".$this->db->qstr($obj->getClassName()).",
				  site_image = ".$this->db->qstr($obj->getImage())."
				  WHERE site_id = " . $obj->getsiteID();
		$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function deleteSourceSite($source_id) {
		try {

		$query = "DELETE FROM $this->TABLE_sites WHERE site_id = " . $source_id;
		$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	/*
		MEDIA TYPE Functions

	*/

	public function getAllMediaTypes() {
		try {

		$query = "SELECT media_type_id, media_type_name, parent_id, media_type_description
			      FROM $this->TABLE_mediatypes
				  ORDER BY parent_id, media_type_name";

				
		$rs = $this->db->Execute($query);

		$arrObj = array();
		foreach ($rs as $row) {
    		$obj = new mediaTypeObj($row);
    		array_push($arrObj, $obj);
		}

		$rs->Close();


		return $arrObj;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function addMediaType(mediaTypeObj $mediaTypeObj) {
		try {

		$query = "INSERT INTO $this->TABLE_mediatypes
				  (media_type_name, parent_id, media_type_description)
				  VALUES (".$this->db->qstr($mediaTypeObj->getName()).",
				  ".$mediaTypeObj->getParentID()." ,".$this->db->qstr($mediaTypeObj->getDescription()).")";

		$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function deleteMediaType($mediatype_id) {
		try {

		$query = "DELETE FROM $this->TABLE_mediatypes WHERE media_type_id = " . $mediatype_id;
		$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function updateMediaType(mediaTypeObj $mediaTypeObj) {
		try {

		$parent_id = 'null';
		if (is_numeric($mediaTypeObj->getParentID())) {
			$parent_id = $mediaTypeObj->getParentID();
		}
			
		
		$query = "UPDATE $this->TABLE_mediatypes SET media_type_name = ".$this->db->qstr($mediaTypeObj->getName())." ,
			  	  parent_id = ".$parent_id.",
			  	  media_type_description = ".$this->db->qstr($mediaTypeObj->getDescription())."
			  	  WHERE media_type_id = ".$mediaTypeObj->getmediaTypeID()."";	
		
			
		
		$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function getMediaTypesOnCD($vcd_id) {
		try {

		$query = "SELECT DISTINCT media_type_id FROM $this->TABLE_vcdtousers WHERE vcd_id = ".$vcd_id."";
		$resultArr = array();
		$rs = $this->db->Execute($query);
		foreach ($rs as $row) {
			array_push($resultArr, $row[0]);
		}
		$rs->Close();
		return $resultArr;


		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function getMediaTypesInUseByUserID($user_id) {
		try {

			$query = "SELECT m.media_type_id, m.media_type_name, COUNT(u.media_type_id) AS media_count
					  FROM $this->TABLE_mediatypes m
					  INNER JOIN $this->TABLE_vcdtousers u ON m.media_type_id = u.media_type_id
						AND u.user_id = ".$user_id."
					  GROUP BY m.media_type_id, m.media_type_name
					  ORDER BY m.media_type_id";
	
			$rs = $this->db->Execute($query);
			$results = array();
			if ($rs) {
				foreach ($rs as $row) {
					$results[] = array($row[0], $row[1], $row[2]);
				}
			}
			
			return $results;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function getMediaTypesInUse()
	{
		try
		{
			$query = "SELECT m.media_type_id, m.media_type_name, COUNT(u.media_type_id) AS media_count
					  FROM $this->TABLE_mediatypes m
					  INNER JOIN $this->TABLE_vcdtousers u ON m.media_type_id = u.media_type_id 
					  GROUP BY m.media_type_id, m.media_type_name
					  ORDER BY m.media_type_id";
			$rs = $this->db->Execute($query);
			$arr = $rs->GetRows();
			$rs->Close( );
			return $arr;
		}
		catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function getMediaCountByCategoryAndUserID($user_id, $category_id) {
		try {

			$query = "SELECT u.media_type_id, COUNT(v.vcd_id) AS media_count
					  FROM $this->TABLE_vcd v
					  INNER JOIN $this->TABLE_vcdtousers u ON v.vcd_id = u.vcd_id
						AND u.user_id = ".$user_id."
					  LEFT OUTER JOIN $this->TABLE_mediatypes m ON u.media_type_id = m.media_type_id
					  WHERE v.category_id = ".$category_id."
					  GROUP BY u.media_type_id";
	
			$rs = $this->db->Execute($query);
			$results = array();
			if ($rs) {
				foreach ($rs as $row) {
					$results[] = array($row[0], $row[1]);
				}
			}
			
			return $results;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function getMediaCountByCategory($category_id) {
		try {
			
		$query = "SELECT u.media_type_id, COUNT(v.vcd_id) AS media_count 
				  FROM $this->TABLE_vcd v
				  INNER JOIN $this->TABLE_vcdtousers u ON v.vcd_id = u.vcd_id 
				  LEFT OUTER JOIN $this->TABLE_mediatypes m ON u.media_type_id = m.media_type_id
				  WHERE v.category_id = ".$category_id."
				  GROUP BY u.media_type_id";
		
		return $this->db->Execute($query)->getArray();
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function getCountByMediaType($mediatype_id) {
		try {

		$query = "SELECT COUNT(vcd_id) FROM $this->TABLE_vcdtousers WHERE media_type_id = " . $mediatype_id;
		return $this->db->getOne($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function getMediaTypeByName($media_name) {
		try {

		$query = "SELECT * FROM $this->TABLE_mediatypes WHERE media_type_name = " . $media_name;
		$this->db->Execute($query);

		$arrObj = array();
		foreach ($rs as $row) {
    		$obj = new mediaTypeObj($row);
		}

		$rs->Close();
		return $obj;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	/*
		Movie Categories functions
	*/

	public function getAllMovieCategories() {
		try {

		$query = "SELECT category_id, category_name
				  FROM $this->TABLE_categories ORDER BY category_name";

		$rs = $this->db->Execute($query);

		$arrObj = array();
		foreach ($rs as $row) {
    		$obj = new movieCategoryObj($row);
    		array_push($arrObj, $obj);
		}

		$rs->Close();
		return $arrObj;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function getMovieCategoriesInUse() {
		try {

		$query = "SELECT DISTINCT c.category_id, c.category_name FROM $this->TABLE_categories c, $this->TABLE_vcd v
				  WHERE c.category_id = v.category_id
				  ORDER BY c.category_name";
		$rs = $this->db->Execute($query);

		$arrObj = array();
		foreach ($rs as $row) {
    		$obj = new movieCategoryObj($row);
    		array_push($arrObj, $obj);
		}

		$rs->Close();
		return $arrObj;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function getCategoryIDByItemId($itemId) {
		try {
			
			$query = "SELECT category_id FROM $this->TABLE_vcd WHERE vcd_id = " . $itemId;
			return $this->db->GetOne($query);
			
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(),$ex->getCode());
		}
	}
	
	public function getCategoriesInUseByUserID($user_id) {
		try {

		$query = "SELECT v.category_id, m.category_name FROM $this->TABLE_vcd v,
				  $this->TABLE_vcdtousers u, $this->TABLE_categories m
				  WHERE v.vcd_id = u.vcd_id AND
				  u.user_id = ".$user_id." AND
				  v.category_id = m.category_id
				  GROUP BY v.category_id, m.category_name
				  ORDER BY m.category_name";

		$rs = $this->db->Execute($query);

		$arrObj = array();
		foreach ($rs as $row) {
    		$obj = new movieCategoryObj($row);
    		array_push($arrObj, $obj);
		}

		$rs->Close();
		return $arrObj;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function addMovieCategory(movieCategoryObj $movieCategoryObj) {
		try {

		$query = "INSERT INTO $this->TABLE_categories (category_name) 
				  VALUES (".$this->db->qstr($movieCategoryObj->getName()).")";
		$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function deleteMovieCategory($category_id) {
		try {

		$query = "DELETE FROM $this->TABLE_categories WHERE category_id = " . $category_id;
		$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function updateMovieCategory(movieCategoryObj $movieCategoryObj) {
		try {

		$query = "UPDATE $this->TABLE_categories SET category_name = ".$this->db->qstr($movieCategoryObj->getName())."";
		$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function getMovieCategoriesByName($category_name) {
		try {

		$query = "SELECT * FROM $this->TABLE_categories WHERE category_name = " . $category_name;
		$this->db->Execute($query);

		$arrObj = array();
		foreach ($rs as $row) {
    		$obj = new movieCategoryObj($row);
		}

		$rs->Close();
		return $obj;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	/*
		Borrowers functions
	*/
	public function getBorrowerByID($borrower_id) {
		try {

		$query = "SELECT borrower_id, owner_id, name, email FROM $this->TABLE_borrowers
				  WHERE borrower_id = " . $borrower_id;
		$rs = $this->db->Execute($query);
		$arrObj = array();
		foreach ($rs as $row) {
    		$obj = new borrowerObj($row);
    		array_push($arrObj, $obj);
		}
		$rs->Close();
		return $arrObj;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function getBorrowersByUserID($user_id) {
		try {

		$query = "SELECT borrower_id, owner_id, name, email FROM $this->TABLE_borrowers
				  WHERE owner_id = ".$user_id." ORDER BY name";
		$rs = $this->db->Execute($query);
		$arrObj = array();
		foreach ($rs as $row) {
    		$obj = new borrowerObj($row);
    		array_push($arrObj, $obj);
		}
		$rs->Close();
		return $arrObj;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function addBorrower(borrowerObj $borrowerObj) {
		try {

		$query = "INSERT INTO $this->TABLE_borrowers (owner_id, name, email)
				  VALUES (".$borrowerObj->getOwnerID().", ".$this->db->qstr($borrowerObj->getName()).",
				  ".$this->db->qstr($borrowerObj->getEmail()).")";
		$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function updateBorrower(borrowerObj $borrowerObj) {
		try {

		$query = "UPDATE $this->TABLE_borrowers SET
				  name = ".$this->db->qstr($borrowerObj->getName()).",
				  email = ".$this->db->qstr($borrowerObj->getEmail())."
				  WHERE borrower_id = ".$borrowerObj->getID()."";
		$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function deleteBorrower($borrower_id) {
		try {

			$query = "DELETE FROM $this->TABLE_borrowers WHERE borrower_id = " . $borrower_id;
			$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	/*
		Loan system functions
	*/

	public function loanCDs($user_id, $borrower_id, $cd_id) {
		try {

		$query = "INSERT INTO $this->TABLE_loans (vcd_id, owner_id, borrower_id, date_out)
				  VALUES (".$cd_id.", ".$user_id.", ".$borrower_id.", ".$this->db->DBDate(time()).")";
		$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function loanReturn($loan_id) {
		try {

		$query = "UPDATE $this->TABLE_loans SET date_in = ".$this->db->DBDate(time())." WHERE loan_id = " . $loan_id;
		$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function getLoans($user_id, $show_returned) {
		try {

		if ($show_returned) {
			$query = "SELECT DISTINCT l.loan_id, l.vcd_id, v.title, l.borrower_id, l.date_out, l.date_in
					  FROM $this->TABLE_loans l, $this->TABLE_vcdtousers u, $this->TABLE_vcd v
					  WHERE l.owner_id = ".$user_id." AND l.vcd_id = u.vcd_id AND
					  u.user_id = ".$user_id." AND u.vcd_id = v.vcd_id
					  ORDER BY l.borrower_id, l.date_out DESC";
		} else {
			$query = "SELECT DISTINCT l.loan_id, l.vcd_id, v.title, l.borrower_id, l.date_out, l.date_in
					  FROM $this->TABLE_loans l, $this->TABLE_vcdtousers u, $this->TABLE_vcd v
					  WHERE l.date_in IS NULL AND
					  l.owner_id = ".$user_id." AND l.vcd_id = u.vcd_id AND
					  u.user_id = ".$user_id." AND u.vcd_id = v.vcd_id
					  ORDER BY l.borrower_id, l.date_out DESC";
		}

		$rs = $this->db->Execute($query);
		$arrObj = array();
		foreach ($rs as $row) {

			$data = array($row[0], $row[1], $row[2], $row[3], $this->db->UnixDate($row[4]), $this->db->UnixDate($row[5]));
    		array_push($arrObj, $data);
		}
		$rs->Close();
		return $arrObj;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function deleteLoanRecords($borrower_id) {
		try {

			$query = "DELETE FROM $this->TABLE_loans WHERE borrower_id = " . $borrower_id;
			$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}




	/* RSS Feed functions */

	public function addRssfeed(rssObj $obj) {
		try {

			$query = "INSERT INTO $this->TABLE_rss (user_id, feed_name, feed_url, isAdult, isSite) VALUES (
				{$obj->getOwnerId()}, 
				{$this->db->qstr($obj->getName())},
				{$this->db->qstr($obj->getFeedUrl())},
				".(int)$obj->isAdultFeed().", 
				{$obj->isVcddbFeed()})";
			
			
			$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function updateRssfeed(rssObj $obj) {
		try {

			$query = "UPDATE $this->TABLE_rss SET feed_name = ".$this->db->qstr($obj->getName()).",
					  feed_url = ".$this->db->qstr($obj->getFeedUrl()).", isAdult = ".$obj->isAdultFeed()." 
					  WHERE feed_id =  " . $obj->getId();
			$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function getRSSfeedByID($feed_id) {
		try {

			$query = "SELECT feed_id, user_id, feed_name, feed_url, isAdult, isSite 
						FROM $this->TABLE_rss WHERE feed_id = " . $feed_id;
			
			$rs = $this->db->Execute($query);
			if ($rs && $rs->RecordCount() > 0) {
				return new rssObj($rs->FetchRow());
			}
			
			return null;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function getRssFeedsByUserId($user_id) {
		try {

			$query = "SELECT feed_id, user_id, feed_name, feed_url, isAdult, isSite 
				FROM $this->TABLE_rss WHERE user_id = ". $user_id;
			
			if (VCDUtils::isLoggedIn()) {
				$query .= " OR (user_id = " . VCDUtils::getUserID() . " AND isSite = 0)";
			}
			
			$query .= " ORDER BY feed_name";
			$rs = $this->db->Execute($query);
			
			$arrObj = array();
			foreach ($rs as $row) {
	    		$obj = new rssObj($row);
	    		array_push($arrObj, $obj);
			}
			$rs->Close();
			return $arrObj;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function delFeed($feed_id) {
		try {

			$query = "DELETE FROM $this->TABLE_rss WHERE feed_id = " . $feed_id;
			$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function getVCDIDsByUser($user_id) {
		try {

		$query = "SELECT vcd_id FROM $this->TABLE_vcdtousers WHERE user_id = " . $user_id;
		$rs = $this->db->Execute($query);
		if ($rs && $rs->RecordCount() > 0) {
			$arrIDS = array();
			foreach ($rs as $row) {
				array_push($arrIDS, $row[0]);
			}
			$rs->Close();
			return $arrIDS;
		} else {
			return null;
		}

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function addToWishList($vcd_id, $user_id) {
		try {

		$query = "INSERT INTO $this->TABLE_wishlist (user_id, vcd_id) VALUES (".$user_id.", ".$vcd_id.")";
		$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function getWishList($user_id) {
		try {

		$query = "SELECT w.vcd_id, v.title, COUNT(u.user_id) AS count FROM $this->TABLE_wishlist w
				  INNER JOIN $this->TABLE_vcd v ON v.vcd_id = w.vcd_id
				  LEFT OUTER JOIN $this->TABLE_vcdtousers u ON u.vcd_id = v.vcd_id AND u.user_id = ".$user_id."
				  WHERE w.user_id = ".$user_id." GROUP BY w.vcd_id, v.title ORDER BY v.title";
		
		
		$rs = $this->db->Execute($query);
		$results = array();
		if ($rs && $rs->RecordCount() > 0) {
			foreach ($rs as $row) {
				$results[] = array('id' => $row[0], 'title' => $row[1], 'mine' => $row[2]);
			}
			return $results;
		} else {
			return null;
		}

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function isOnWishList($vcd_id, $user_id) {
		try {

		$query = "SELECT vcd_id FROM $this->TABLE_wishlist WHERE user_id = ".$user_id." AND
				  vcd_id = ".$vcd_id."";
		$rs = $this->db->Execute($query);
		if ($rs && $rs->RecordCount() > 0) {
			$rs->Close();
			return true;
		} else {
			$rs->Close();
			return false;
		}

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function isPublicWishLists($user_id, $property_id) {
		try {
			
			$query = "SELECT Count(l.vcd_id) AS Result FROM $this->TABLE_wishlist l INNER JOIN $this->TABLE_propstousr 
			p on p.user_id = l.user_id WHERE p.property_id = {$property_id} AND l.user_id <> " . $user_id;

			return ($this->db->GetOne($query) > 0);
			
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	
	public function removeFromWishList($vcd_id, $user_id) {
		try {

		$query = "DELETE FROM $this->TABLE_wishlist WHERE vcd_id = ".$vcd_id." AND user_id = ".$user_id;
		$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	/* Comments */

	public function addComment(commentObj $obj) {
		try {

		$commentColumn = 'comment';
		if ($this->isOracle()) {
			$commentColumn = 'comments';
		}
		
			
		$query = "INSERT INTO $this->TABLE_comments (vcd_id, user_id, comment_date, $commentColumn, isPrivate)
				  VALUES (".$obj->getVcdID().", ".$obj->getOwnerID().", ".$this->db->DBDate(time()).",
				  ".$this->db->qstr($obj->getComment()).", ".(int)$obj->isPrivate().")";
		
		$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function deleteComment($comment_id) {
		try {
			
		$query = "DELETE FROM $this->TABLE_comments WHERE comment_id = " . $comment_id;
		$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function getCommentByID($comment_id)  {
		try {

		$commentColumn = 'comment';
		if ($this->isOracle()) {
			$commentColumn = 'comments';
		}
			
		$query = "SELECT comment_id, vcd_id, user_id, comment_date, $commentColumn, isPrivate FROM
				  $this->TABLE_comments WHERE comment_id = ".$comment_id." ORDER BY comment_date DESC";
		$rs = $this->db->Execute($query);
		if ($rs) {
			return new commentObj($rs->FetchRow());
		}

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function getAllCommentsByUserID($user_id) {
		try {

		$commentColumn = 'comment';
		if ($this->isOracle()) {
			$commentColumn = 'comments';
		}
			
		$query = "SELECT comment_id, vcd_id, user_id, comment_date, $commentColumn, isPrivate FROM
				  $this->TABLE_comments WHERE user_id = ".$user_id." ORDER BY comment_id DESC";

		$rs = $this->db->Execute($query);
		$arrObj = array();
		foreach ($rs as $row) {
    		$obj = new commentObj($row);
    		array_push($arrObj, $obj);
		}
		$rs->Close();
		return $arrObj;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function getAllCommentsByVCD($vcd_id) {
		try {

		$commentColumn = 'c.comment';
		if ($this->isOracle()) {
			$commentColumn = 'c.comments';
		}
			
		$query = "SELECT c.comment_id, c.vcd_id, c.user_id, c.comment_date, $commentColumn,
				  c.isPrivate, u.user_fullname FROM
				  $this->TABLE_comments c
				  LEFT OUTER JOIN $this->TABLE_users u ON c.user_id = u.user_id
				  WHERE c.vcd_id = ".$vcd_id." ORDER BY c.comment_id DESC";

		
		$rs = $this->db->Execute($query);
		$arrObj = array();
		foreach ($rs as $row) {
			$data = array($row[0], $row[1], $row[2], $this->db->UnixDate($row[3]), $row[4], $row[5], $row[6]);
    		$obj = new commentObj($data);
    		array_push($arrObj, $obj);
		}
		$rs->Close();
		
		return $arrObj;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	/* Metadata objects */

	public function addMetadata(metadataObj $obj) {
		try {

		$query = "INSERT INTO $this->TABLE_metadata (record_id, mediatype_id, user_id, type_id, metadata_value) VALUES
				 (".$obj->getRecordID().", ".$obj->getMediaTypeID().", ".$obj->getUserID().", ".$obj->getMetadataTypeID().",
				 ".$this->db->qstr($obj->getMetadataValue()).")";

		$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function updateMetadata(metadataObj $obj) {
		try {

		$query = "UPDATE $this->TABLE_metadata SET metadata_value = ".$this->db->qstr($obj->getMetadataValue());
		
		if (is_numeric($obj->getRecordID()) && $obj->getRecordID() > 0) {
			$query .= ", record_id = " . $obj->getRecordID();
		}
		if (is_numeric($obj->getMediaTypeID()) && $obj->getMediaTypeID() > 0) {
			$query .= ", mediatype_id = " . $obj->getMediaTypeID();
		}
		$query .= " WHERE metadata_id = " . $obj->getMetadataID();
			
		$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function updateMetadataType(metadataTypeObj $obj) {
		try {

		$query = "UPDATE $this->TABLE_metatypes SET type_name = ".$this->db->qstr($obj->getMetadataTypeName());
		
		$query .= ", type_description = ".$this->db->qstr($obj->getMetadataDescription());
		
		if (is_numeric($obj->getMetadataTypeLevel())) {
			$query .= ", owner_id = " . $obj->getMetadataTypeLevel();
		}
		
		$query .= ", public = ".(int)$obj->getMetadataTypePublic();
		
		$query .= " WHERE type_id = " . $obj->getMetadataTypeID();
			
		$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	public function deleteMetadata($metadata_id) {
		try {

		$query = "DELETE FROM $this->TABLE_metadata WHERE metadata_id = " . $metadata_id;
		$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function getMetadata($record_id, $user_id, $metadata_name, $mediatype_id = null) {
		try {
					
		if (is_numeric($mediatype_id) && is_numeric($record_id) && is_numeric($user_id) && strlen($metadata_name) == 0) {
			$query = "SELECT m.metadata_id, m.record_id, m.user_id, n.type_name, m.metadata_value,
					  m.mediatype_id, n.type_id, n.owner_id, n.type_description FROM $this->TABLE_metadata m
					  LEFT OUTER JOIN $this->TABLE_metatypes n on m.type_id = n.type_id
					  WHERE m.record_id = ".$record_id." AND m.user_id = " . $user_id . "
					  AND m.mediatype_id = ".$mediatype_id." ORDER BY n.type_name";
			
		} else if (!is_null($mediatype_id)) {
			$query = "SELECT m.metadata_id, m.record_id, m.user_id, n.type_name, m.metadata_value,
					  m.mediatype_id, n.type_id, n.owner_id FROM $this->TABLE_metadata m
					  LEFT OUTER JOIN $this->TABLE_metatypes n on m.type_id = n.type_id
					  WHERE m.record_id = ".$record_id." AND m.user_id = " . $user_id . "
					  AND m.mediatype_id = ".$mediatype_id." 
			 		  AND n.type_name = " . $this->db->qstr($metadata_name) . " ORDER BY n.type_name";
			
		} else if (is_numeric($record_id) && !is_numeric($user_id)) {
			$query = "SELECT m.metadata_id, m.record_id, m.user_id, n.type_name, m.metadata_value,
					  m.mediatype_id, n.type_id, n.owner_id, n.type_description FROM $this->TABLE_metadata m
					  LEFT OUTER JOIN $this->TABLE_metatypes n on m.type_id = n.type_id
					  WHERE m.record_id = ".$record_id." ORDER BY m.mediatype_id, n.type_id, n.type_name";
			
		} else if (strlen($metadata_name) == 0) {
			$query = "SELECT m.metadata_id, m.record_id, m.user_id, n.type_name, m.metadata_value,
					  m.mediatype_id, n.type_id, n.owner_id, n.type_description FROM $this->TABLE_metadata m
					  LEFT OUTER JOIN $this->TABLE_metatypes n on m.type_id = n.type_id
					  WHERE m.record_id = ".$record_id." AND (m.user_id = " . $user_id ." OR n.public = 1)
					  ORDER BY m.mediatype_id, n.type_id, n.type_name, m.metadata_value";
			
		} else {
			$query = "SELECT m.metadata_id, m.record_id, m.user_id, n.type_name, m.metadata_value,
					  m.mediatype_id, n.type_id, n.owner_id FROM $this->TABLE_metadata m
					  LEFT OUTER JOIN $this->TABLE_metatypes n on m.type_id = n.type_id
					  WHERE m.record_id = ".$record_id." AND m.user_id = " . $user_id . "
			 		  AND n.type_name = " . $this->db->qstr($metadata_name) . " ORDER BY n.type_name";
		}

		
		$metaArr = array();
		$rs = $this->db->Execute($query);
		if ($rs && $rs->RecordCount() > 0) {
			foreach ($rs as $row) {
				$obj = new metadataObj($row);
				array_push($metaArr, $obj);
			}
		}
		
		return $metaArr;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	public function getMetadataById($metadata_id) {
		try {
			
			$query = "SELECT m.metadata_id, m.record_id, m.user_id, n.type_name, m.metadata_value,
					  m.mediatype_id, n.type_id, n.owner_id, n.type_description FROM $this->TABLE_metadata m
					  LEFT OUTER JOIN $this->TABLE_metatypes n on m.type_id = n.type_id
					  WHERE m.metadata_id = " . $metadata_id;
			$rs = $this->db->Execute($query);
			
			if ($rs && $rs->RecordCount() > 0) {
				foreach ($rs as $row) {
					$obj = new metadataObj($row);
					return $obj;
				}
			} else {
				return null;
			}
			
			
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	

	public function getRecordIDsByMetadata($user_id, $metadata_name) {
		try {

		$query = "SELECT m.record_id FROM $this->TABLE_metadata m LEFT OUTER JOIN
				  $this->TABLE_metatypes t on t.type_id = m.type_id WHERE
				  m.user_id = {$user_id} AND t.type_name = ".$this->db->qstr($metadata_name)."
				  AND (m.metadata_value <> '0' AND m.metadata_value <> '')";

		$rs = $this->db->Execute($query);
		if ($rs && $rs->RecordCount() > 0) {
			$arr = array();
			foreach ($rs as $row) {
				array_push($arr, $row[0]);
			}
			$rs->Close();
			return $arr;
		} else {
			return null;
		}

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function addMetaDataType(metadataTypeObj $obj) {
		try {

			// First we check if metadataObj with same name exist, if so
			// We simply return that metadataTypeObj since we don't allow duplicate names
			$inObj = $this->getMetadataType($obj->getMetadataTypeName());
			if ($inObj instanceof metadataTypeObj) {
				return $inObj;
			} else {
				// Check for legal typename
				if (strcmp($obj->getMetadataTypeName(), "") == 0) {
					throw new Exception("MetadataTypeName cannot be empty");
				}

				// Object not found .. lets create it ..
				$query = "INSERT INTO $this->TABLE_metatypes (type_name, type_description, owner_id, public) VALUES
						  (".$this->db->qstr($obj->getMetadataTypeName()).", ".$this->db->qstr($obj->getMetadataDescription()).",
						  ".$obj->getMetadataTypeLevel().", ".(int)$obj->getMetadataTypePublic().")";
				$this->db->Execute($query);


				/* 	Returns the last autonumbering ID inserted. Returns false if function not supported.
					Only supported by databases that support auto-increment or object id's,
					such as PostgreSQL, MySQL and MS SQL Server currently. PostgreSQL returns the OID,
					which can change on a database reload.
				*/

				$inserted_id = -1;
				try {
					$inserted_id = $this->db->Insert_ID($this->TABLE_metatypes, 'type_id');
				} catch (Exception $e) {
					// Check if this is a Postgre not using OID columns
					if ($this->isPostgres()) {
						// Yeap, postgres not using OID ..
						$inserted_id = $this->oToID($this->TABLE_metatypes, 'type_id');
					} else {
						throw $ex;
					}
				}
				
				if (!is_numeric($inserted_id) || $inserted_id < 0 ) {
					// InsertedID not supported, we have to dig the latest entry out manually
					$query = "SELECT type_id FROM $this->TABLE_metatypes ORDER BY type_id DESC";
					$rs = $this->db->SelectLimit($query, 1);
					// Should only be 1 recordset
					foreach ($rs as $row) {
						$inserted_id = $row[0];
					}
				}

				$obj->setMetaDataTypeID($inserted_id);
				return $obj;

			}


		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function getMetadataType($name=null, $id=null) {
		try {
			if(!is_null($name)) {
				$query = "SELECT type_id, type_name, type_description, owner_id, public FROM
						  $this->TABLE_metatypes WHERE type_name = " . $this->db->qstr($name);
			} else {
				$query = "SELECT type_id, type_name, type_description, owner_id, public FROM
						  $this->TABLE_metatypes WHERE type_id = " . $this->db->qstr($id);
			}
			$rs = $this->db->Execute($query);

			if ($rs && $rs->RecordCount() > 0) {
				foreach ($rs as $row) {
					$obj = new metadataTypeObj($row[0], $row[1], $row[2], $row[3], $row[4]);
					return $obj;
				}

			} else {
				return null;
			}


		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function getMetadataTypes($user_id = null) {
		try {

			if (!is_null($user_id) && is_numeric($user_id)) {

				$query = "SELECT type_id, type_name, type_description, owner_id, public FROM
						  $this->TABLE_metatypes WHERE owner_id = " . $user_id . " ORDER BY type_name";

			} else {
				$query = "SELECT type_id, type_name, type_description, owner_id, public FROM
						  $this->TABLE_metatypes ORDER BY type_name";
			}

			$rs = $this->db->Execute($query);
			if ($rs && $rs->RecordCount() > 0) {
				$metaArr = array();
				foreach ($rs as $row) {
					$obj = new metadataTypeObj($row[0], $row[1], $row[2], $row[3], $row[4]);
					array_push($metaArr, $obj);
				}
				return $metaArr;
			} else {
				return null;
			}


		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function deleteMetaDataType($metatype_id) { 
		try {
			
			$query = "DELETE FROM $this->TABLE_metadata WHERE type_id = " . $metatype_id;
			$this->db->Execute($query);
			$query = "DELETE FROM $this->TABLE_metatypes WHERE type_id = " . $metatype_id;
			$this->db->Execute($query);
			
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	
	public function getMetadataValueCount(metadataObj $obj) {
		try {
			
			$query = "SELECT COUNT(metadata_id) FROM $this->TABLE_metadata 
					  WHERE metadata_value = " . $this->db->qstr($obj->getMetadataValue());
			return $this->db->GetOne($query);
			
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	public function getStatsObj() {
		try {

		$query = "SELECT COUNT(vcd_id) FROM $this->TABLE_vcd";
		$totalmoviecount = $this->db->getOne($query);

		$today = date('Y-m-d',time() - 3600*24);
		$week = date('Y-m-d',time() - 3600*24*7);
		$month = date('Y-m-d',time() - 3600*24*30);

		$zdate = $this->db->DBDate($today);
		$query = "SELECT COUNT(vcd_id) FROM $this->TABLE_vcdtousers WHERE date_added > " . $zdate;
		$todaycount = $this->db->getOne($query);

		$zdate = $this->db->DBDate($week);
		$query = "SELECT COUNT(vcd_id) FROM $this->TABLE_vcdtousers WHERE date_added > " . $zdate;
		$weekcount = $this->db->getOne($query);

		$zdate = $this->db->DBDate($month);
		$query = "SELECT COUNT(vcd_id) FROM $this->TABLE_vcdtousers WHERE date_added > " . $zdate;
		$monthcount = $this->db->getOne($query);


		$monthArrCats = array();
		$ArrCats = array();

		// Most movies added to categories this month
		$query = "SELECT v.category_id, COUNT(v.category_id) AS Num FROM $this->TABLE_vcd v,
				  $this->TABLE_vcdtousers u
				  WHERE u.vcd_id = v.vcd_id AND u.date_added > ".$zdate."
				  GROUP BY v.category_id ORDER BY Num DESC";
		$rs = $this->db->Execute($query);
		if ($rs && $rs->RecordCount() > 0) {
			$monthArrCats = $rs->GetArray();
			$rs->Close();
		}

		// Biggest categories total
		$query = "SELECT v.category_id, COUNT(v.category_id) AS Num FROM $this->TABLE_vcd v,
				  $this->TABLE_vcdtousers u
				  WHERE u.vcd_id = v.vcd_id GROUP BY v.category_id ORDER BY Num DESC";
		$rs = $this->db->Execute($query);
		if ($rs && $rs->RecordCount() > 0) {
			$ArrCats = $rs->GetArray();
			$rs->Close();
		}


		$query = "SELECT COUNT(cover_id) FROM $this->TABLE_covers";
		$coverCount = $this->db->getOne($query);

		$zdate = $this->db->DBDate($week);
		$query = "SELECT COUNT(cover_id) FROM $this->TABLE_covers WHERE date_added > " . $zdate;
		$coverCountWeek = $this->db->getOne($query);

		$zdate = $this->db->DBDate($month);
		$query = "SELECT COUNT(cover_id) FROM $this->TABLE_covers WHERE date_added > " . $zdate;
		$coverCountMonth = $this->db->getOne($query);

		$obj = new statisticsObj();
		$obj->setMovieCount($totalmoviecount);
		$obj->setMovieTodayCount($todaycount);
		$obj->setMovieWeeklyCount($weekcount);
		$obj->setMovieMonthlyCount($monthcount);
		$obj->setCoverCount($coverCount, $coverCountWeek, $coverCountMonth);
		$obj->setBiggestCats($ArrCats);
		$obj->setBiggestMonhtlyCats($monthArrCats);

		return $obj;


		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}



	public function getUserStatistics($user_id) {
		try {

			$stat_array = array();
			
			// Year Data
			$query = "SELECT v.year as year, COUNT(v.vcd_id) as num FROM $this->TABLE_vcd v,
					  $this->TABLE_vcdtousers u WHERE v.vcd_id = u.vcd_id AND
					  u.user_id = ".$user_id." GROUP BY v.year
					  ORDER BY v.year DESC";

			$rs = $this->db->Execute($query);
			$data = array();
			if ($rs) {
				foreach ($rs as $row) {
					$data[] = array($row[0],$row[1]);
				}
			}
			$stat_array['year'] = $data;
			


			// Category data
			$query = "SELECT v.category_id, COUNT(v.vcd_id) as num FROM $this->TABLE_vcd v,
					  $this->TABLE_vcdtousers u WHERE v.vcd_id = u.vcd_id AND
					  u.user_id = ".$user_id." GROUP BY v.category_id
					  ORDER BY num DESC";
			
			$rs = $this->db->Execute($query);
			$data = array();
			if ($rs) {
				foreach ($rs as $row) {
					$data[] = array($row[0], $row[1]);
				}
			}
			$stat_array['category'] = $data;


			// Media data			
			$query = "SELECT u.media_type_id, COUNT(v.vcd_id) as num FROM $this->TABLE_vcd v,
					  $this->TABLE_vcdtousers u WHERE v.vcd_id = u.vcd_id AND
					  u.user_id = ".$user_id." GROUP BY u.media_type_id
					  ORDER BY num DESC";

			$rs = $this->db->Execute($query);
			$data = array();
			if ($rs) {
				foreach ($rs as $row) {
					$data[] = array($row[0], $row[1]);
				}
			}
			
			$stat_array['media'] = $data;
			return $stat_array;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


}


?>
