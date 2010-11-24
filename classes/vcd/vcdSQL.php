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
 * @subpackage Vcd
 * @version $Id$
 */

?>
<?php

class vcdSQL extends VCDConnection {

	private $TABLE_vcd   	    = "vcd";
	private $TABLE_categories   = "vcd_MovieCategories";
	private $TABLE_vcdtouser    = "vcd_VcdToUsers";
	private $TABLE_mediatypes   = "vcd_MediaTypes";
	private $TABLE_vcdtosources = "vcd_VcdToSources";
	private $TABLE_imdb 		= "vcd_IMDB";
	private $TABLE_vcdtopornst  = "vcd_VcdToPornstars";
	private $TABLE_pornstars	= "vcd_Pornstars";
	private $TABLE_vcdtoporncat = "vcd_VcdToPornCategories";
	private $TABLE_studios		= "vcd_PornStudios";
	private $TABLE_vcdtostudios	= "vcd_VcdToPornStudios";
	private $TABLE_screens		= "vcd_Screenshots";
	private $TABLE_covers		= "vcd_Covers";
	private $TABLE_wishlist 	= "vcd_UserWishList";
	private $TABLE_userloans 	= "vcd_UserLoans";
	private $TABLE_comments		= "vcd_Comments";
	private $TABLE_metadata		= "vcd_MetaData";
	private $TABLE_metatypes	= "vcd_MetaDataTypes";


 	private $magic_quotes;

	public function __construct() {
		parent::__construct();
 		$this->magic_quotes = magic_quotes_runtime();
	}


	public function getVcdByID($vcd_id) {
		try {

		$query = "SELECT v.vcd_id, v.title, v.category_id, v.year, s.site_id, s.external_id
				  FROM $this->TABLE_vcd v
				  LEFT OUTER join $this->TABLE_vcdtosources s ON v.vcd_id = s.vcd_id
				  WHERE v.vcd_id = $vcd_id";

		$rs = $this->db->GetRow($query);

		if ($rs) {

			$obj = new vcdObj($rs);

			// Any source site attached ?
			if (isset($rs[4])) {
				$obj->setSourceSite($rs[4], $rs[5]);
			}


			// Get the users, mediaTypes, disc count and dates added on the CD
			$query = "SELECT u.user_id, u.media_type_id, u.disc_count, u.date_added
					  FROM $this->TABLE_vcdtouser u WHERE
					  u.vcd_id = $vcd_id ORDER BY u.date_added DESC";

			$rs2 = $this->db->Execute($query);
			if ($rs2) {

				$USERClass = VCDClassFactory::getInstance("vcd_user");
				$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");

				foreach ($rs2 as $row) {
					$user_id  = $row[0];
					$media_id = $row[1];
					$discs    = $row[2];
					$date     = $this->db->UnixTimeStamp($row[3]);

					$obj->addInstance($USERClass->getUserByID($user_id), $SETTINGSClass->getMediaTypeByID($media_id), $discs, $date);


				}
				$rs2->Close();
			}

			return $obj;

		} else {
			return null;
		}

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function getAllVcdByCategory($category_id, $sort = null) {
		try {

		if($category_id != 0) $category_cond = "v.category_id = $category_id";
		else $category_cond = "TRUE";

		$query = "SELECT v.vcd_id, v.title, v.category_id, v.year FROM $this->TABLE_vcd v
				  WHERE $category_cond ORDER BY v.title";

		// Get all CD's in this category
		$rs = $this->db->Execute($query);
		$arrVcdObj = array();

		$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");

		foreach ($rs as $row) {

				$obj = new vcdObj($row);
				// Get the mediaTypes
				foreach ($SETTINGSClass->getMediaTypesOnCD($obj->getID()) as $mediaTypeObj) {
					$obj->addMediaType($mediaTypeObj);
				}
				array_push($arrVcdObj, $obj);
		}

		$rs->Close();
		return $arrVcdObj;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function getAllVcdByUserAndCategory($user_id, $category_id, $simple = true, $sort = null) {
		try {
		
		if($category_id != 0) $category_cond = "v.category_id = $category_id";
		else $category_cond = "TRUE";		
			
		$query = "SELECT v.vcd_id, v.title, v.category_id, v.year FROM $this->TABLE_vcd v
				  INNER JOIN $this->TABLE_vcdtouser u ON v.vcd_id = u.vcd_id
				  WHERE $category_cond AND u.user_id = ".$user_id." ORDER BY v.title";

		// Get all CD's in this category
		$rs = $this->db->Execute($query);
		$arrVcdObj = array();

		$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");

		foreach ($rs as $row) {

				$obj = new vcdObj($row);
				if (!$simple) {
					// Get the mediaTypes
					foreach ($SETTINGSClass->getMediaTypesOnCD($obj->getID()) as $mediaTypeObj) {
						$obj->addMediaType($mediaTypeObj);
					}
				}
				array_push($arrVcdObj, $obj);
		}

		$rs->Close();
		return $arrVcdObj;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}



	public function getVcdByAdultCategory($category_id, $thumbnail_id) {
		try {


		$query = "SELECT v.vcd_id, v.title, v.category_id, v.year, s.vcd_id AS screenshots,
				  z.cover_filename, z.cover_id FROM $this->TABLE_vcd v
				  INNER JOIN $this->TABLE_vcdtoporncat c ON v.vcd_id = c.vcd_id
				  LEFT OUTER JOIN $this->TABLE_screens s ON s.vcd_id = v.vcd_id
				  LEFT OUTER JOIN $this->TABLE_covers z ON v.vcd_id = z.vcd_id AND z.cover_type_id = ".$thumbnail_id."
				  WHERE c.category_id = ".$category_id."
				  ORDER BY v.title";

		$rs = $this->db->Execute($query);
		$arrVcdObj = array();

		foreach ($rs as $row) {
				$obj = new vcdObj(array($row[0], $row[1], $row[2], $row[3]));
				if (isset($row[4]) && is_numeric($row[4])) {
					$obj->setScreenshots();
				}

				// check for thumnail cover
				if (isset($row[5])) {
					$cobj = new cdcoverObj();
					$cobj->setVcdId($row[0]);
					$cobj->setCoverTypeID($thumbnail_id);
					$cobj->setCoverTypeName('Thumbnail');
					$cobj->setFilename($row[5]);

					if (isset($row[6])) {
						$cobj->setCoverID($row[6]);
					}

					$obj->addCovers(array($cobj));
				}


				array_push($arrVcdObj, $obj);
		}

		$rs->Close();
		return $arrVcdObj;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function getVcdByAdultStudio($studio_id, $thumbnail_id) {
		try {

		$query = "SELECT v.vcd_id, v.title, v.category_id, v.year, s.vcd_id AS screenshots,
				  z.cover_filename, z.cover_id FROM $this->TABLE_vcd v
				  INNER JOIN $this->TABLE_vcdtostudios c ON v.vcd_id = c.vcd_id
				  LEFT OUTER JOIN $this->TABLE_screens s ON s.vcd_id = v.vcd_id
				  LEFT OUTER JOIN $this->TABLE_covers z ON v.vcd_id = z.vcd_id AND z.cover_type_id = ".$thumbnail_id."
				  WHERE c.studio_id = ".$studio_id."
				  ORDER BY v.title";


		$rs = $this->db->Execute($query);
		$arrVcdObj = array();

		foreach ($rs as $row) {
				$obj = new vcdObj(array($row[0], $row[1], $row[2], $row[3]));
				if (isset($row[4]) && is_numeric($row[4])) {
					$obj->setScreenshots();
				}

				// check for thumnail cover
				if (isset($row[5])) {
					$cobj = new cdcoverObj();
					$cobj->setVcdId($row[0]);
					$cobj->setCoverTypeID($thumbnail_id);
					$cobj->setCoverTypeName('Thumbnail');
					$cobj->setFilename($row[5]);

					if (isset($row[6])) {
						$cobj->setCoverID($row[6]);
					}

					$obj->addCovers(array($cobj));
				}


				array_push($arrVcdObj, $obj);
		}

		$rs->Close();
		return $arrVcdObj;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function getAllVcdByUserId($user_id, $limit = -1) {
		try {


		if ($limit > 0) {
			$query = "SELECT v.vcd_id, v.title, v.category_id, v.year, u.media_type_id,
				  u.disc_count, u.date_added, s.site_id, s.external_id FROM
				  $this->TABLE_vcdtouser u, $this->TABLE_vcd v
				  LEFT OUTER JOIN $this->TABLE_vcdtosources s ON v.vcd_id = s.vcd_id
		    	  WHERE u.user_id = ".$user_id." AND u.vcd_id = v.vcd_id
				  ORDER BY v.vcd_id DESC";

			// Get all CD's in this category
			$rs =  $this->db->SelectLimit($query, $limit);

		} else {

			$query = "SELECT v.vcd_id, v.title, v.category_id, v.year, u.media_type_id,
				  u.disc_count, u.date_added, s.site_id, s.external_id FROM
				  $this->TABLE_vcdtouser u, $this->TABLE_vcd v
				  LEFT OUTER JOIN $this->TABLE_vcdtosources s ON v.vcd_id = s.vcd_id
		    	  WHERE u.user_id = ".$user_id." AND u.vcd_id = v.vcd_id
				  ORDER BY v.title";



			// Get all CD's in this category
			$rs = $this->db->Execute($query);

		}


		$arrVcdObj = array();


		$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");

		foreach ($rs as $row) {
				$obj = new vcdObj($row);
				// Get the mediaType
				$obj->addMediaType($SETTINGSClass->getMediaTypeByID($row[4]));
				$obj->setDiscCount($row[5]);
				$obj->setDateAdded($this->db->UnixTimeStamp($row[6]));

				$obj->setSourceSite($row[7], $row[8]);

				array_push($arrVcdObj, $obj);
		}

		$rs->Close();
		return $arrVcdObj;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function getAllVcdByUserIdSimple($user_id, $limit = -1) {
		try {


		if ($limit > 0) {

			$query = "SELECT v.vcd_id, v.title, v.category_id, v.year, u.media_type_id FROM
				  $this->TABLE_vcd v, $this->TABLE_vcdtouser u WHERE
				  u.user_id = ".$user_id." AND u.vcd_id = v.vcd_id
				  ORDER BY v.vcd_id DESC";

			// Get all CD's in this category
			$rs = $this->db->SelectLimit($query, $limit);

		} else {

			$query = "SELECT v.vcd_id, v.title, v.category_id, v.year, u.media_type_id FROM
				  $this->TABLE_vcd v, $this->TABLE_vcdtouser u WHERE
				  u.user_id = ".$user_id." AND u.vcd_id = v.vcd_id
				  ORDER BY v.title";

			// Get all CD's in this category
			$rs = $this->db->Execute($query);

		}


		$arrVcdObj = array();

		$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");

		foreach ($rs as $row) {
				$obj = new vcdObj($row);
				// Get the mediaType
				$obj->addMediaType($SETTINGSClass->getMediaTypeByID($row[4]));
				array_push($arrVcdObj, $obj);
		}

		$rs->Close();
		return $arrVcdObj;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}




	/**
		Since the SQL function LIMIT is terribly slow on MySQL without indexes
		I have to fetch all the records and manually pick out the requested interval

	*/
	public function getVcdByCategory($category_id, $numrecords, $offset, $thumbnail_id, $user_id = -1, $sort = null) {
		try {

		$order = "v.title";
			
		if(!is_null($sort)) {
			$dir = "";
			if(strcmp(substr($sort, -1), "d") == 0) {
				$dir .= " DESC";
			}
			switch(substr($sort, 0, -2)) {
				case "id": $order = "v.vcd_id$dir, v.title"; break;
				case "year": $order = "v.year$dir, v.title"; break;
				case "title":
				default: $order = "v.title$dir"; break;	
			}
		}
		if($category_id != 0) $category_cond = "v.category_id = $category_id";
		else $category_cond = "TRUE";
			
		if ($user_id == -1) {
			$query = "SELECT v.vcd_id, v.title, v.category_id, v.year, m.media_type_id, z.cover_filename, z.cover_id FROM $this->TABLE_vcd v
				  LEFT OUTER JOIN $this->TABLE_covers z ON v.vcd_id = z.vcd_id AND z.cover_type_id = ".$thumbnail_id."
				  LEFT OUTER JOIN $this->TABLE_vcdtouser m ON m.vcd_id = v.vcd_id
				  WHERE $category_cond ORDER BY $order";
		} else {
			$query = "SELECT v.vcd_id, v.title, v.category_id, v.year, m.media_type_id, z.cover_filename, z.cover_id FROM $this->TABLE_vcd v
				  LEFT OUTER JOIN $this->TABLE_covers z ON v.vcd_id = z.vcd_id AND z.cover_type_id = ".$thumbnail_id."
				  LEFT OUTER JOIN $this->TABLE_vcdtouser m ON m.vcd_id = v.vcd_id
				  INNER JOIN $this->TABLE_vcdtouser u ON v.vcd_id = u.vcd_id AND u.user_id = ".$user_id."
				  WHERE $category_cond ORDER BY $order";
		}


		// Get all CD's in this category
		$rs = $this->db->Execute($query);
		$arrVcdObj = array();

		$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");
		

		$i = 0;
		$lastMovieObj = null;
		foreach ($rs as $row) {

			if ($i >= $offset && $i < ($offset+$numrecords)) {
				$obj = new vcdObj($row);

				// Add the mediaType
				$obj->addMediaType($SETTINGSClass->getMediaTypeByID($row[4]));
				
				if (!is_null($lastMovieObj) && $obj->getID() === $lastMovieObj->getID()) {
					$lastMovieObj->addMediaType($SETTINGSClass->getMediaTypeByID($row[4]));
					continue;
				}
			
								
				// check for thumnail cover
				if (isset($row[5])) {
					$cobj = new cdcoverObj();
					$cobj->setVcdId($row[0]);
					$cobj->setCoverTypeID($thumbnail_id);
					$cobj->setCoverTypeName('Thumbnail');
					$cobj->setFilename($row[5]);
					$cobj->setCoverID($row[6]);

					$obj->addCovers(array($cobj));
				}

				array_push($arrVcdObj, $obj);
				$lastMovieObj = $obj;
			}

			$i++;
		}



		$rs->Close();
		return $arrVcdObj;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}




	public function getVcdByCategoryFiltered($category_id, $numrecords, $offset, $thumbnail_id, $arrIgnore) {
		try {

		if($category_id != 0) $category_cond = "v.category_id = $category_id";
		else $category_cond = "TRUE";

		$order = "v.title";
			
		if(!is_null($sort)) {
			$dir = "";
			if(strcmp(substr($sort, -1), "d") == 0) {
				$dir .= " DESC";
			}
			switch(substr($sort, 0, -2)) {
				case "id": $order = "v.vcd_id$dir"; break;
				case "year": $order = "v.year$dir, v.title, v.vcd_id"; break;
				case "title":
				default: $order = "v.title$dir, v.vcd_id"; break;	
			}
		}
		
		// Create sql from the ignore array
		$sql_ignore = "";
		for ($i = 0; $i < sizeof($arrIgnore); $i++) {

			if ($i == (sizeof($arrIgnore)-1)) {
				$sql_ignore .= "u.user_id <> ".$arrIgnore[$i]." ";
			} else {
				$sql_ignore .= "u.user_id <> ".$arrIgnore[$i]." OR ";
			}

		}


		$query = "SELECT DISTINCT v.vcd_id, v.title, v.category_id, v.year, z.cover_filename, z.cover_id
				  FROM $this->TABLE_vcd v
				  LEFT OUTER JOIN $this->TABLE_covers z ON v.vcd_id = z.vcd_id AND z.cover_type_id = ".$thumbnail_id."
				  WHERE $category_cond AND v.vcd_id IN
				  (SELECT v.vcd_id FROM $this->TABLE_vcd v, $this->TABLE_vcdtouser u
				  WHERE v.vcd_id = u.vcd_id AND (".$sql_ignore.")) ORDER BY $order";


		// Get all CD's in this category
		$rs = $this->db->Execute($query);
		$arrVcdObj = array();

		$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");

		$i = 0;
		foreach ($rs as $row) {

			if ($i >= $offset && $i < ($offset+$numrecords)) {
				$obj = new vcdObj($row);

				// Get the mediaTypes
				foreach ($SETTINGSClass->getMediaTypesOnCD($obj->getID()) as $mediaTypeObj) {
					$obj->addMediaType($mediaTypeObj);
				}

				// check for thumnail cover
				if (isset($row[4])) {
					$cobj = new cdcoverObj();
					$cobj->setVcdId($row[0]);
					$cobj->setCoverTypeID($thumbnail_id);
					$cobj->setCoverTypeName('Thumbnail');
					$cobj->setFilename($row[4]);
					$cobj->setCoverID($row[5]);
	
					$obj->addCovers(array($cobj));
				}

				array_push($arrVcdObj, $obj);
			}

			$i++;
		}



		$rs->Close();
		return $arrVcdObj;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}



	public function getAllVcdForList($excluded_userid) {
		try {

		if (is_numeric($excluded_userid) && ($excluded_userid > 0)) {
			$query = "SELECT v.vcd_id, v.title, v.category_id, v.year FROM
					  $this->TABLE_vcd v, $this->TABLE_vcdtouser u WHERE
					  v.vcd_id = u.vcd_id AND
					  u.user_id <> ".$excluded_userid."
					  GROUP BY v.vcd_id, v.title, v.category_id, v.year
					  ORDER BY v.title";
		} else {
			$query = "SELECT v.vcd_id, v.title, v.category_id, v.year FROM
					  $this->TABLE_vcd v, $this->TABLE_vcdtouser u WHERE
					  v.vcd_id = u.vcd_id
					  GROUP BY v.vcd_id, v.title, v.category_id, v.year
					  ORDER BY v.title";
		}

		$rs = $this->db->Execute($query);
		$arrVcdObj = array();

		foreach ($rs as $row) {
				$obj = new vcdObj($row);
				array_push($arrVcdObj, $obj);
		}

		$rs->Close();
		return $arrVcdObj;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function checkEntry($title, $year) {
		try {

		$query = "SELECT vcd_id FROM $this->TABLE_vcd WHERE title = ".$this->db->qstr($title)." AND year = " . $year;
		$cd_id = $this->db->GetOne($query);
		if (!$cd_id) {
			return false;
		} else {
			return $cd_id;
		}

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function deleteVcdFromUser($user_id, $vcd_id, $media_id) {
		try {

		$query = "DELETE FROM $this->TABLE_vcdtouser WHERE
				  vcd_id = ".$vcd_id." AND user_id = ".$user_id." AND media_type_id = " . $media_id;
		$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function getVcdOwnerCount($vcd_id) {
		try {

		$query = "SELECT COUNT(user_id) FROM $this->TABLE_vcdtouser WHERE vcd_id = " . $vcd_id;
		return $this->db->GetOne($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	/**
		Delete all evidence of this VCD in the database, including in categories, covers and etc.
	*/
	public function deleteVcdFromDB($vcd_id, $external_id) {
		try {


		if (!is_numeric($vcd_id)) {
			return false;
		}

		if (isset($external_id) && strlen($external_id) > 0) {
			$query = "DELETE FROM $this->TABLE_imdb WHERE imdb = ".$this->db->quote($external_id)."";
			$this->db->Execute($query);
		}

		$query = "DELETE FROM $this->TABLE_vcdtosources WHERE vcd_id = ". $vcd_id;
		$this->db->Execute($query);
		$query = "DELETE FROM $this->TABLE_vcdtostudios WHERE vcd_id = ". $vcd_id;
		$this->db->Execute($query);
		$query = "DELETE FROM $this->TABLE_vcdtopornst WHERE vcd_id = ". $vcd_id;
		$this->db->Execute($query);
		$query = "DELETE FROM $this->TABLE_vcdtoporncat WHERE vcd_id = ". $vcd_id;
		$this->db->Execute($query);
		$query = "DELETE FROM $this->TABLE_wishlist WHERE vcd_id = ". $vcd_id;
		$this->db->Execute($query);
		$query = "DELETE FROM $this->TABLE_userloans WHERE vcd_id = ". $vcd_id;
		$this->db->Execute($query);
		$query = "DELETE FROM $this->TABLE_screens WHERE vcd_id = ". $vcd_id;
		$this->db->Execute($query);
		$query = "DELETE FROM $this->TABLE_covers WHERE vcd_id = ". $vcd_id;
		$this->db->Execute($query);
		$query = "DELETE FROM $this->TABLE_comments WHERE vcd_id = ". $vcd_id;
		$this->db->Execute($query);
		$query = "DELETE FROM $this->TABLE_metadata WHERE record_id = ". $vcd_id;
		$this->db->Execute($query);
		$query = "DELETE FROM $this->TABLE_vcd WHERE vcd_id = " . $vcd_id;
		$this->db->Execute($query);
		

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function addVCD(vcdObj $obj) {
		try {

		$query = "INSERT INTO $this->TABLE_vcd (title, category_id, year) VALUES
				  (".$this->db->qstr($obj->getTitle(), $this->magic_quotes).", ".$obj->getCategoryID().", ".$obj->getYear().")";

		// Execute the statement
		$this->db->Execute($query);

		/* 	Returns the last autonumbering ID inserted. Returns false if function not supported.
			Only supported by databases that support auto-increment or object id's,
			such as PostgreSQL, MySQL and MS SQL Server currently. PostgreSQL returns the OID,
			which can change on a database reload.	*/

		$inserted_id = -1;


		try {
			$inserted_id = $this->db->Insert_ID($this->TABLE_vcd, 'vcd_id');
		} catch (Exception $ex) {
			// Check if this is a Postgre not using OID columns
			if ($this->isPostgres()) {
				// Yeap, postgres not using OID ..
				$inserted_id = $this->oToID($this->TABLE_vcd, 'vcd_id');
			} else {
				throw $ex;
			}
		}


		if (is_numeric($inserted_id) && $inserted_id > 0) {

			return $inserted_id;

		} else {
			// InsertedID not supported, we have to dig the latest entry out manually
			$query = "SELECT vcd_id FROM $this->TABLE_vcd ORDER BY vcd_id DESC";
			$rs = $this->db->SelectLimit($query, 1);

			// S$hould only be 1 recordset
			foreach ($rs as $row) {
				$inserted_id = $row[0];
			}

			return $inserted_id;

		}

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function addVcdInstance(vcdObj $obj) {
		try {


		$query = "INSERT INTO $this->TABLE_vcdtouser (vcd_id, user_id, media_type_id, disc_count, date_added)
				  VALUES (
				  ".$obj->getID().",
				  ".$obj->getInsertValueUserID().",
				  ".$obj->getInsertValueMediaTypeID().",
				  ".$obj->getInsertValueDiscCount().",
				  ".$this->db->DBTimeStamp(time()).")";

		return $this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function addVcdToUser($user_id, $vcd_id, $mediatype_id, $cds) {
		try {

		$query = "INSERT INTO $this->TABLE_vcdtouser (vcd_id, user_id, media_type_id, disc_count, date_added)
				  VALUES (".$vcd_id.", ".$user_id.", ".$mediatype_id.", ".$cds.",  ".$this->db->DBTimeStamp(time()).")";
		return $this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function addVcdToSourceSite(vcdObj $obj) {
		try {

		$query = "INSERT INTO $this->TABLE_vcdtosources (vcd_id, site_id, external_id)
				  VALUES (".$obj->getID().", ".$obj->getSourceSiteID().", ".$this->db->qstr($obj->getExternalID()).")";
		return $this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function checkIMDBDuplicate($imdb_id) {
		try {

		$query = "SELECT COUNT(i.imdb) FROM $this->TABLE_imdb i WHERE
				  i.imdb = ".$this->db->qstr($imdb_id);

		return $this->db->GetOne($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function addIMDBInfo(imdbObj $obj) {

		try {

		$query = "INSERT INTO $this->TABLE_imdb (imdb, title, alt_title1, image, year, plot,
				  director, fcast, rating, runtime, country, genre) VALUES (
				  ".$this->db->qstr($obj->getIMDB(), $this->magic_quotes).",
				  ".$this->db->qstr($obj->getTitle(), $this->magic_quotes).",
				  ".$this->db->qstr($obj->getAltTitle(), $this->magic_quotes).",
				  ".$this->db->qstr($obj->getImage(), $this->magic_quotes).",
				  ".$obj->getYear().",
		          ".$this->db->qstr($obj->getPlot(), $this->magic_quotes).",
		          ".$this->db->qstr($obj->getDirector(), $this->magic_quotes).",
		          ".$this->db->qstr($obj->getCast(false), $this->magic_quotes).",
				  ".$this->db->qstr($obj->getRating(), $this->magic_quotes).",
			      ".$obj->getRuntime().",
				  ".$this->db->qstr($obj->getCountry(), $this->magic_quotes).",
		          ".$this->db->qstr($obj->getGenre(), $this->magic_quotes).")";


		return $this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function updateIMDBInfo(imdbObj $obj) {
		try {

		$query = "UPDATE $this->TABLE_imdb
				  SET title = ".$this->db->qstr($obj->getTitle(), $this->magic_quotes).",
				  alt_title1 = ".$this->db->qstr($obj->getAltTitle(), $this->magic_quotes).",
				  image = ".$this->db->qstr($obj->getImage(), $this->magic_quotes).",
				  year = ".$obj->getYear().",
			      plot = ".$this->db->qstr($obj->getPlot(), $this->magic_quotes).",
				  director = ".$this->db->qstr($obj->getDirector(), $this->magic_quotes).",
		          fcast = ".$this->db->qstr($obj->getCast(false), $this->magic_quotes).",
				  rating = ".$this->db->qstr($obj->getRating(), $this->magic_quotes).",
		          runtime = ".$obj->getRuntime().",
			      country = ".$this->db->qstr($obj->getCountry(), $this->magic_quotes).",
			      genre = ".$this->db->qstr($obj->getGenre(), $this->magic_quotes)."
				  WHERE imdb = ".$this->db->qstr($obj->getIMDB(), $this->magic_quotes)."";


		return $this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function updateBasicVcdInfo(vcdObj $obj) {
		try {

		if ($obj->getCategory() instanceof movieCategoryObj ) {
			$cat_id = $obj->getCategory()->getID();
		} else {
			$cat_id = $obj->getCategoryID();
		}

		$query = "UPDATE $this->TABLE_vcd SET title = ".$this->db->qstr($obj->getTitle(), $this->magic_quotes).",
				  category_id = ".$cat_id.", year = ".$obj->getYear()."
				  WHERE vcd_id = ".$obj->getID()."";
		return $this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function updateVcdInstance($user_id, $vcd_id, $new_mediaid, $old_mediaid, $new_numcds, $oldnumcds) {
		try {

			$query = "UPDATE $this->TABLE_vcdtouser SET media_type_id = ".$new_mediaid.", disc_count = ".$new_numcds."
					  WHERE vcd_id = ".$vcd_id." AND user_id = ".$user_id." AND media_type_id = ".$old_mediaid." AND
					  disc_count = ".$oldnumcds."";
			$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function markVcdWithScreenshots($vcd_id) {
		try {

		$query = "INSERT INTO $this->TABLE_screens (vcd_id) VALUES (".$vcd_id.")";
		$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function getScreenshots($vcd_id) {
		try {

		$query = "SELECT vcd_id FROM $this->TABLE_screens WHERE vcd_id = ". $vcd_id;
		$rs = $this->db->Execute($query);
		$screens = false;
		if ($rs && $rs->RecordCount() > 0) {
			$screens = true;
			$rs->Close();
		}

		return $screens;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function getCategoryCount($category_id, $user_id = -1) {
		try {

		if($category_id != 0) $category_cond = "v.category_id = $category_id";
		else $category_cond = "TRUE";
			
		if ($user_id == -1) {
			$query = "SELECT COUNT(v.vcd_id) FROM $this->TABLE_vcd v WHERE $category_cond";
		} else {
			$query = "SELECT COUNT(v.vcd_id) FROM $this->TABLE_vcd v, $this->TABLE_vcdtouser u
			WHERE v.vcd_id = u.vcd_id AND $category_cond AND u.user_id = " . $user_id;
		}

		return $this->db->GetOne($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function getCategoryCountFiltered($category_id, $user_id, $arrIgnore) {
		try {

			if($category_id != 0) $category_cond = "v.category_id = $category_id";
			else $category_cond = "TRUE";
				
			// Create sql from the ignore array
			$sql_ignore = "";
			for ($i = 0; $i < sizeof($arrIgnore); $i++) {
			if ($i == (sizeof($arrIgnore)-1)) {
					$sql_ignore .= "u.user_id <> ".$arrIgnore[$i]." ";
				} else {
					$sql_ignore .= "u.user_id <> ".$arrIgnore[$i]." OR ";
				}
			}


			$query = "SELECT COUNT(v.vcd_id) FROM $this->TABLE_vcd v
				  WHERE $category_cond AND v.vcd_id IN
				  (SELECT v.vcd_id FROM $this->TABLE_vcd v, $this->TABLE_vcdtouser u
				  WHERE v.vcd_id = u.vcd_id AND (".$sql_ignore."))";


			return $this->db->GetOne($query);


		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function getTopTenList($category_id) {
		try {

		$query = "SELECT v.vcd_id, v.title, v.category_id, v.year FROM $this->TABLE_vcd v
				  WHERE v.category_id = $category_id ORDER by v.vcd_id DESC";


		$rs =  $this->db->SelectLimit($query, 10);

		$arrVcdObj = array();

		foreach ($rs as $row) {
			$obj = new vcdObj($row);
			array_push($arrVcdObj, $obj);
		}

		$rs->Close();
		return $arrVcdObj;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function getCompleteTopTenList($arrFilter = null) {
		try {


		if (!is_null($arrFilter) && is_array($arrFilter)) {
			$strWhere = "WHERE v.category_id <> ";
			$i = 0;
			foreach ($arrFilter as $index => $category_id) {
				if ($i == 0) {
					$strWhere .= $category_id;
				} else {
					$strWhere .= " AND v.category_id <> " . $category_id;
				}
				$i++;
			}

			$query = "SELECT v.vcd_id, v.title, v.category_id, v.year FROM $this->TABLE_vcd v
					  {$strWhere} ORDER by v.vcd_id DESC";


		} else {
			$query = "SELECT v.vcd_id, v.title, v.category_id, v.year FROM $this->TABLE_vcd v
				  	  ORDER by v.vcd_id DESC";
		}


		$rs =  $this->db->SelectLimit($query, 10);

		$arrVcdObj = array();

		foreach ($rs as $row) {
			$obj = new vcdObj($row);
			array_push($arrVcdObj, $obj);
		}

		$rs->Close();
		return $arrVcdObj;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function getSimilarMovies($movie_name, $category_id, $vcd_id) {
		try {

			$num = 5;
			$searchString = substr($movie_name, 0, $num) . "%";

			$query = "SELECT v.vcd_id, v.title, v.category_id, v.year FROM $this->TABLE_vcd v
					  WHERE v.title LIKE ".$this->db->qstr($searchString)."
					  AND v.category_id = ".$category_id." AND v.vcd_id <> {$vcd_id} ORDER BY v.title";

			$rs = $this->db->Execute($query);
			$arrVcdObj = array();


			foreach ($rs as $row) {
				$obj = new vcdObj($row);
				array_push($arrVcdObj, $obj);
			}

			$rs->Close();
			return $arrVcdObj;


		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function getIMDB($movie_id) {
		try {

		$query = "SELECT i.imdb,  i.title,  i.alt_title1,  i.alt_title2,  i.image,
				 i.year,  i.plot,  i.director,  i.fcast,  i.rating,  i.runtime,
				 i.country,  i.genre FROM $this->TABLE_imdb i
				 INNER JOIN $this->TABLE_vcdtosources s ON i.imdb = s.external_id
				 WHERE s.vcd_id = ".$movie_id." AND s.external_id = i.imdb";

		$rs = $this->db->GetRow($query);
		if ($rs) {
			return new imdbObj($rs);
		}

		return null;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function checkDuplicateEntry($user_id, $vcd_id, $media_id) {
		try {

		$query = "SELECT COUNT(u.vcd_id) FROM $this->TABLE_vcdtouser u WHERE
				  u.vcd_id = ".$vcd_id." AND u.user_id = ".$user_id." AND u.media_type_id = " . $media_id;

		$itemCount = $this->db->GetOne($query);
		return $itemCount;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	
	
	public function getDuplicationList($ignore_id) {
		try {
			
						
			if (is_numeric($ignore_id)) {
				$query = "SELECT external_id, COUNT(external_id) AS numocc FROM ".$this->TABLE_vcdtosources." s 
					  INNER JOIN ".$this->TABLE_vcd." v ON v.vcd_id = s.vcd_id and v.category_id <> ".$ignore_id."
					  GROUP BY external_id HAVING ( COUNT(external_id) > 1 )";	
			} else {
				$query = "SELECT external_id, COUNT(external_id) AS numocc FROM ".$this->TABLE_vcdtosources." s 
					  INNER JOIN ".$this->TABLE_vcd." v ON v.vcd_id = s.vcd_id 
					  GROUP BY external_id HAVING ( COUNT(external_id) > 1 )";
			}
		
			
			$entryQuery = "SELECT v.vcd_id, s.site_id from ".$this->TABLE_vcd." v 
						  INNER JOIN ".$this->TABLE_vcdtosources." s ON s.vcd_id = v.vcd_id
						  WHERE s.external_id = '%s' ORDER BY vcd_id ASC";
			
			$resultArr = array();
			$rs = $this->db->Execute($query);
			if ($rs) {
				foreach ($rs as $row) {
					$external_id = $row[0];
					$query = sprintf($entryQuery, $external_id);
					$listResults = $this->db->Execute($query);
					if ($listResults) {
						$list = array();
						foreach ($listResults as $rows) {
							array_push($list, $this->getVcdByID($rows[0]));
						}
						if (sizeof($list) > 0) {
							array_push($resultArr, $list);
						}
					}
				}
			}
			
			return $resultArr;
			
			
		} catch (Exception $ex) {
			throw new VCDException($ex->getMessage(), $ex->getCode());
		}
	}

	
	public function getPrintViewList($user_id, $arr_use = null, $arr_exclude = null, $thumbnail_id) {
		try {

		if (!is_array($arr_use)) {

			if (!is_array($arr_exclude)) {
				// Fetch all movies
				$query = "SELECT DISTINCT v.vcd_id, v.title, v.category_id, v.year, z.cover_filename, z.cover_id
						  FROM $this->TABLE_vcd v
						  INNER JOIN $this->TABLE_vcdtouser u ON v.vcd_id = u.vcd_id AND u.user_id = ".$user_id."
					      LEFT OUTER JOIN $this->TABLE_covers z ON v.vcd_id = z.vcd_id
						  AND z.cover_type_id = ".$thumbnail_id."
						  ORDER BY v.title";
			} else {
				// Fetch all movies except those in the exclusion array
				$query = "SELECT DISTINCT v.vcd_id, v.title, v.category_id, v.year, z.cover_filename, z.cover_id
						  FROM $this->TABLE_vcd v
						  INNER JOIN $this->TABLE_vcdtouser u ON v.vcd_id = u.vcd_id AND u.user_id = ".$user_id."
					      LEFT OUTER JOIN $this->TABLE_covers z ON v.vcd_id = z.vcd_id AND z.cover_type_id = ".$thumbnail_id." ";

				$query .= "WHERE";
				for ($i = 0; $i < sizeof($arr_exclude); $i++) {
					if ($i == (sizeof($arr_exclude)-1)) {
						$query .= " v.category_id <> ".$arr_exclude[$i] ;
					} else {
						$query .= " v.category_id <> ".$arr_exclude[$i]." AND ";
					}

				}

				$query .= " ORDER BY v.title";
			}


		} elseif (is_array($arr_use)) {
			// Get movies with category ID's defined in the array
			$query = "SELECT v.vcd_id, v.title, v.category_id, v.year, z.cover_filename, z.cover_id
					  FROM $this->TABLE_vcd v
					  INNER JOIN $this->TABLE_vcdtouser u ON v.vcd_id = u.vcd_id AND u.user_id = ".$user_id."
				      LEFT OUTER JOIN $this->TABLE_covers z ON v.vcd_id = z.vcd_id AND z.cover_type_id = ".$thumbnail_id."
					  WHERE ";

			for ($i = 0; $i < sizeof($arr_use); $i++) {
				if ($i == (sizeof($arr_use)-1)) {
					$query .= " v.category_id = ".$arr_use[$i] ;
				} else {
					$query .= " v.category_id = ".$arr_use[$i]." AND ";
				}

			}
			$query .= " ORDER BY v.title";
		}


		// Get all CD's in this category
		$rs = $this->db->Execute($query);
		$arrVcdObj = array();


		foreach ($rs as $row) {

				$obj = new vcdObj($row);

				// check for thumnail cover
				if (isset($row[4])) {
					$cobj = new cdcoverObj();
					$cobj->setVcdId($row[0]);
					$cobj->setCoverTypeID($thumbnail_id);
					$cobj->setCoverTypeName('Thumbnail');
					$cobj->setFilename($row[4]);

					if (isset($row[5])) {
						//$cobj->setImageID($row[5]);
						$cobj->setCoverID($row[5]);
					}

					$obj->addCovers(array($cobj));
				}

				array_push($arrVcdObj, $obj);
		}


		$rs->Close();
		return $arrVcdObj;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}



	public function getMovieCount($user_id) {
		try {

			$query = "SELECT COUNT(*) FROM $this->TABLE_vcdtouser WHERE user_id = " . $user_id;
			return $this->db->getOne($query);


		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}


	public function search($keyword, $method, $showadult = false) {
		try {

		$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");
		$adult_cat = $SETTINGSClass->getCategoryIDByName('Adult');
		$keyword = "%".$keyword."%";


		switch ($method) {

			case 'title':
				if ($showadult) {

					$query = "SELECT v.vcd_id, v.title, v.category_id, v.year
							  FROM $this->TABLE_vcd v
							  LEFT OUTER JOIN $this->TABLE_vcdtosources so ON v.vcd_id = so.vcd_id
							  LEFT OUTER JOIN $this->TABLE_imdb i ON so.external_id = i.imdb
							  WHERE v.title LIKE ".$this->db->quote($keyword)." OR i.title LIKE ".$this->db->quote($keyword)."
							  OR i.alt_title1 LIKE ".$this->db->quote($keyword)." OR i.alt_title2 LIKE ".$this->db->quote($keyword)."
							  ORDER BY v.title";
				} else {
					$query = "SELECT v.vcd_id, v.title, v.category_id, v.year
							  FROM $this->TABLE_vcd v
							  LEFT OUTER JOIN $this->TABLE_vcdtosources so ON v.vcd_id = so.vcd_id
							  LEFT OUTER JOIN $this->TABLE_imdb i ON so.external_id = i.imdb
							  WHERE (v.title LIKE ".$this->db->quote($keyword)." OR i.title LIKE ".$this->db->quote($keyword)."
							  OR i.alt_title1 LIKE ".$this->db->quote($keyword)." OR i.alt_title2 LIKE ".$this->db->quote($keyword).")
							  AND v.category_id <> ".$adult_cat."
							  ORDER BY v.title";
				}
				break;

			case 'actor':
				if ($showadult) {
					$query = "SELECT v.vcd_id, v.title, v.category_id, v.year
							  FROM $this->TABLE_vcd v
							  LEFT OUTER JOIN $this->TABLE_vcdtosources so ON v.vcd_id = so.vcd_id
							  LEFT OUTER JOIN $this->TABLE_imdb i ON so.external_id = i.imdb
							  LEFT OUTER JOIN $this->TABLE_vcdtopornst tp ON v.vcd_id = tp.vcd_id
							  LEFT OUTER JOIN $this->TABLE_pornstars p ON tp.pornstar_id = p.pornstar_id
							  WHERE i.fcast LIKE ".$this->db->quote($keyword)." OR p.name LIKE ".$this->db->quote($keyword)."
							  ORDER BY v.title";

				} else {
					$query = "SELECT v.vcd_id, v.title, v.category_id, v.year
							  FROM $this->TABLE_vcd v
							  LEFT OUTER JOIN $this->TABLE_vcdtosources so ON v.vcd_id = so.vcd_id
							  LEFT OUTER JOIN $this->TABLE_imdb i ON so.external_id = i.imdb
							  WHERE i.fcast LIKE ".$this->db->quote($keyword)."
							  AND v.category_id <> ".$adult_cat."
							  ORDER BY v.title";
				}

				break;

			case 'director':
					$query = "SELECT v.vcd_id, v.title, v.category_id, v.year
							  FROM $this->TABLE_vcd v
							  LEFT OUTER JOIN $this->TABLE_vcdtosources so ON v.vcd_id = so.vcd_id
							  LEFT OUTER JOIN $this->TABLE_imdb i ON so.external_id = i.imdb
							  WHERE i.director LIKE ".$this->db->quote($keyword)."
							  ORDER BY v.title";

			break;

		}
		
		
		// Transform the queries with LOWER() if postgres ..
		if ($this->isPostgres()) { 
			$arrFields = array('v.title', 'i.title', 'i.alt_title1', 'i.alt_title2', 'i.fcast','p.name', 'i.director', $this->db->Quote($keyword));
			//  create the replacement array ..
			$arrLower = array();
			for ($i=0;$i<sizeof($arrFields);$i++) { $arrLower[$i] = "lower(".$arrFields[$i].")"; }
			$query = str_replace($arrFields, $arrLower, $query);
		}
		

		$rs = $this->db->Execute($query);
		$arrVcdObj = array();



		foreach ($rs as $row) {

				$obj = new vcdObj($row);
				// Get the mediaTypes
				foreach ($SETTINGSClass->getMediaTypesOnCD($obj->getID()) as $mediaTypeObj) {
					$obj->addMediaType($mediaTypeObj);
				}
				
				// Update the titles from lowercase to UcFirst if Postgres
				if ($this->isPostgres()) {
					$obj->setTitle(ucwords($obj->getTitle()));
				}
				array_push($arrVcdObj, $obj);
		}

		$rs->Close();
		return $arrVcdObj;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function getMovieByCustomKey($user_id, $metadataKey) {
		try {

		$query = "SELECT DISTINCT v.vcd_id, v.title, v.category_id, v.year, u.media_type_id, i.rating
				  FROM $this->TABLE_vcd v
				  INNER JOIN $this->TABLE_vcdtouser u ON v.vcd_id = u.vcd_id
				  INNER JOIN $this->TABLE_metadata  m ON u.vcd_id = m.record_id
				  LEFT OUTER JOIN $this->TABLE_vcdtosources s ON s.vcd_id = v.vcd_id
					  LEFT OUTER JOIN $this->TABLE_imdb i ON i.imdb = s.external_id
				  WHERE m.user_id = ".$user_id." AND m.type_id = ".metadataTypeObj::SYS_MEDIAINDEX ." AND
				  m.metadata_value = " . $this->db->qstr($metadataKey);

		$results = array();
		$rs = $this->db->Execute($query);
		if ($rs) {
			foreach ($rs as $row) {

				$item = array('id' => $row[0], 'title' => $row[1], 'cat_id' => $row[2],
								  'year' => $row[3], 'media_id' => $row[4], 'rating' => $row[5]);
					array_push($results, $item);
				}
			$rs->Close();
		}
		return $results;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function advancedSearch($title, $category, $year, $mediatype, $owner, $imdbgrade, $meta, $unseen = false, $showadult = false) {
		try {

		//If the user is not logged in, the unseen search is disabled!
		if (VCDUtils::isLoggedIn()){
			$userID =  VCDUtils::getCurrentUser()->getUserID();
		} else {
			$unseen = false;
		}
		
		$query = "SELECT DISTINCT v.vcd_id, v.title, v.category_id, v.year, u.media_type_id, i.rating
				  FROM $this->TABLE_vcd v ";


		$query .= "LEFT OUTER JOIN $this->TABLE_vcdtouser u ON v.vcd_id = u.vcd_id ";

		$query .= "LEFT OUTER JOIN $this->TABLE_vcdtosources s ON s.vcd_id = v.vcd_id ";

		$query .= "LEFT OUTER JOIN $this->TABLE_imdb i ON i.imdb = s.external_id ";
		
		$query .= "LEFT OUTER JOIN $this->TABLE_comments c ON c.vcd_id = v.vcd_id ";
		
		if(!is_null($meta)) {
			$query .= "LEFT OUTER JOIN $this->TABLE_metadata m ON m.record_id = v.vcd_id ";
		
			$query .= "LEFT JOIN $this->TABLE_metatypes t ON m.type_id = t.type_id ";
		}


		$bCon = false;
		if (!is_null($title)) {
			$title = "%".$title."%";
			$query .= "WHERE v.title LIKE ".$this->db->qstr($title);
			$bCon = true;
		}
		
		if ($unseen) {
			if ($bCon) {
				$query .= " AND (v.vcd_id NOT IN (SELECT DISTINCT m.record_id FROM $this->TABLE_metadata m WHERE m.user_id =".$userID." AND m.type_id = 12)";
				$query .= " OR v.vcd_id IN (SELECT DISTINCT m.record_id FROM $this->TABLE_metadata m WHERE m.user_id =".$userID." AND m.type_id = 12 AND m.metadata_value = 0))";
			} else {
				$query .= " WHERE (v.vcd_id NOT IN (SELECT DISTINCT m.record_id FROM $this->TABLE_metadata m WHERE m.user_id =".$userID." AND m.type_id = 12)";
				$query .= " OR v.vcd_id IN (SELECT DISTINCT m.record_id FROM $this->TABLE_metadata m WHERE m.user_id =".$userID." AND m.type_id = 12 AND m.metadata_value = 0))";
				$bCon = true;
			}
		}

		if (is_numeric($owner)) {
			if ($bCon) {
				$query .= " AND u.user_id = ".$owner." ";
			} else {
				$query .= " WHERE u.user_id = ".$owner." ";
				$bCon = true;
			}
		}


		if (is_numeric($mediatype)) {
			if ($bCon) {
				$query .= " AND u.media_type_id = ".$mediatype." ";
			} else {
				$query .= " WHERE u.media_type_id = ".$mediatype." ";
				$bCon = true;
			}
		}


		if (is_numeric($category)) {
			if ($bCon) {
				$query .= " AND v.category_id = ".$category."";
			} else {
				$query .= " WHERE v.category_id = ".$category."";
				$bCon = true;
			}
		}

		if (is_numeric($year)) {
			if ($bCon) {
				$query .= " AND v.year = ".$year."";
			} else {
				$query .= " WHERE v.year = ".$year."";
				$bCon = true;
			}
		}

		if(!is_null($meta)) {
			foreach($meta as $k => $v) {
				if(!empty($v)) {
					$meta = $this->db->qstr("%".$v."%");
					if($this->isPostgres()) {
						$meta = "LOWER(".$meta.")";
					}
					
					if($bCon) {
						$query .= " AND (m.type_id = $k".
									   " AND m.metadata_value LIKE ".$meta.
									   " AND (t.public ".(VCDUtils::isLoggedIn()?"OR m.user_id = ".VCDUtils::getCurrentUser()->getUserID():"")."))";
					} else {
						$query .= " WHERE (m.type_id = $k".
									     " AND m.metadata_value LIKE ".$meta.
									     " AND (t.public ".(VCDUtils::isLoggedIn()?"OR m.user_id = ".VCDUtils::getCurrentUser()->getUserID():"")."))";
						$bCon = true;
					}
				}
			}
		}

		// Skip adult titles if requested
		if (!$showadult) {
			$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");
			$adult_cat = $SETTINGSClass->getCategoryIDByName('Adult');	
			if ($bCon) {
				$query .= " AND v.category_id <> ".$adult_cat."";
			} else {
				$query .= " WHERE v.category_id <> ".$adult_cat."";
			}
		}
		
		$commentColumn = "c.comment";
		if ($this->isOracle()) {
			$commentColumn = "c.comments";
		}
		
		// Check for all public comments and users private if user is logged in
		if (!is_null($title) && (!is_numeric($owner) && !is_numeric($mediatype) && !is_numeric($category) && !is_numeric($year))) {
			if (VCDUtils::isLoggedIn()) {
				$user_id = VCDUtils::getUserID();
				$query .= " OR (($commentColumn LIKE {$this->db->qstr($title)} AND isPrivate = 0) OR ($commentColumn LIKE {$this->db->qstr($title)} AND c.user_id = {$user_id}))";
			} else {
				$query .= " OR ($commentColumn LIKE {$this->db->qstr($title)} AND isPrivate = 0) ";
			}
		}
	
		$query .= " ORDER BY v.title";

		
		// Transform the queries with LOWER() if postgres ..
		if ($this->isPostgres()) { 
			$arrFields = array('v.title', 'c.comment', 'm.metadata_value', $this->db->qstr($title));
			//  create the replacement array ..
			$arrLower = array();
			for ($i=0;$i<sizeof($arrFields);$i++) { $arrLower[$i] = "lower(".$arrFields[$i].")"; }
			$query = str_replace($arrFields, $arrLower, $query);
		}
		
		
		$results = array();
		
		$rs = $this->db->Execute($query);
		if ($rs) {


			foreach ($rs as $row) {

				if (is_numeric($imdbgrade)) {
					
					if ($row[5] >= $imdbgrade) {
						$item = array('id' => $row[0], 'title' => $row[1], 'cat_id' => $row[2],
									  'year' => $row[3], 'media_id' => $row[4], 'rating' => $row[5]);
									  
					// Update the titles from lowercase to UcFirst if Postgres
					if ($this->isPostgres()) {
							$item['title'] = ucwords($item['title']);
					}
					array_push($results, $item);
									  
					}
					
				} else {
					$item = array('id' => $row[0], 'title' => $row[1], 'cat_id' => $row[2],
								  'year' => $row[3], 'media_id' => $row[4], 'rating' => $row[5]);
								  
					// Update the titles from lowercase to UcFirst if Postgres
					if ($this->isPostgres()) {
							$item['title'] = ucwords($item['title']);
					}
					array_push($results, $item);
				}
				
			}

			$rs->Close();
		}
		return $results;

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	public function crossJoin($request_userid, $user_id, $media_id, $category_id, $method) {
		try {

		$sqlMed = "";
		$sqlCat = "";
		$bCat = false;
		$bMed = false;

		if (is_numeric($media_id)) {
			$sqlMed = " AND u.media_type_id = ".$media_id." ";
			$bMed = true;
		}

		if (is_numeric($category_id)) {
			$sqlCat = " AND v.category_id = ".$category_id." ";
			$bCat = true;
		}

		switch ($method) {
			case 1:
				// Movies I got but user not
				$query = "SELECT DISTINCT v.vcd_id, v.title, v.category_id, v.year FROM
						  $this->TABLE_vcd v
						  INNER JOIN $this->TABLE_vcdtouser u ON v.vcd_id = u.vcd_id
						  WHERE u.user_id = ".$request_userid."";

				if ($bMed) {
					$query .= $sqlMed;
				}

				if ($bCat) {
					$query .= $sqlCat;
				}

				$query .=  " AND v.vcd_id NOT IN
						  	(SELECT v.vcd_id FROM
							$this->TABLE_vcd v
							INNER JOIN $this->TABLE_vcdtouser u ON v.vcd_id = u.vcd_id
							WHERE u.user_id = ".$user_id."";

				if ($bMed) {
					$query .= $sqlMed;
				}

				if ($bCat) {
					$query .= $sqlCat;
				}


				$query .= " ) ORDER BY v.title";


				break;

			case 2:
				// Movies user has but i do not
				$query = "SELECT DISTINCT v.vcd_id, v.title, v.category_id, v.year FROM
						  $this->TABLE_vcd v
						  INNER JOIN $this->TABLE_vcdtouser u ON v.vcd_id = u.vcd_id
						  WHERE u.user_id = ".$user_id."";

				if ($bMed) {
					$query .= $sqlMed;
				}

				if ($bCat) {
					$query .= $sqlCat;
				}

				$query .=  " AND v.vcd_id NOT IN
						  	(SELECT v.vcd_id FROM
							$this->TABLE_vcd v
							INNER JOIN $this->TABLE_vcdtouser u ON v.vcd_id = u.vcd_id
							WHERE u.user_id = ".$request_userid."";

				if ($bMed) {
					$query .= $sqlMed;
				}

				if ($bCat) {
					$query .= $sqlCat;
				}


				$query .= " ) ORDER BY v.title";

				break;

			case 3:
				// Movies we both got
				$query = "SELECT DISTINCT v.vcd_id, v.title, v.category_id, v.year FROM
						  $this->TABLE_vcd v
						  INNER JOIN $this->TABLE_vcdtouser u ON v.vcd_id = u.vcd_id
						  WHERE u.user_id = ".$request_userid."";

				if ($bMed) {
					$query .= $sqlMed;
				}

				if ($bCat) {
					$query .= $sqlCat;
				}

				$query .=  " AND v.vcd_id IN
						  	(SELECT v.vcd_id FROM
							$this->TABLE_vcd v
							INNER JOIN $this->TABLE_vcdtouser u ON v.vcd_id = u.vcd_id
							WHERE u.user_id = ".$user_id."";

				if ($bMed) {
					$query .= $sqlMed;
				}

				if ($bCat) {
					$query .= $sqlCat;
				}


				$query .= " ) ORDER BY v.title";
				break;

			default:
				break;
		}


		$rs = $this->db->Execute($query);
		$arrVcdObj = array();
		foreach ($rs as $row) {
				$obj = new vcdObj($row);
				array_push($arrVcdObj, $obj);
		}


		$rs->Close();
		return $arrVcdObj;


		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}

	}



}

?>
