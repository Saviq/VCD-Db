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
 * @author  Hákon Birgsson <konni@konni.com>
 * @package Kernel
 * @subpackage CDCover
 * @version $Id$
 */
 
?>
<?PHP

class cdcoverSQL {
	
	private $TABLE_covers 		 = "vcd_Covers";
	private $TABLE_types  		 = "vcd_CoverTypes";
	private $TABLE_mediatypes 	 = "vcd_MediaTypes";
	private $TABLE_allowedcovers = "vcd_CoversAllowedOnMediatypes";
	private $TABLE_vcdtousers    = "vcd_VcdToUsers";
	private $db;
 			
	
	public function __construct() {
		$conn = VCDClassFactory::getInstance('Connection');
 		$this->db = &$conn->getConnection();
	}
	
	
	public function getAllCoverTypes() {
		
		try {
		
			$query = "SELECT cover_type_id, cover_type_name, cover_type_description
					  FROM $this->TABLE_types ORDER BY cover_type_name";
			
			$rs = $this->db->Execute($query);
			$coverTypeObjArr = array();
			foreach ($rs as $row) {
	    		$obj = new cdcoverTypeObj($row);
	    		array_push($coverTypeObjArr, $obj);
			}
			
			$rs->Close();
			return $coverTypeObjArr;
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}

	
	public function addCoverType(cdcoverTypeObj $cdcoverTypeObj) {
		try 
		{
			$query = "INSERT INTO $this->TABLE_types (cover_type_name, cover_type_description)
					  VALUES (".$this->db->qstr($cdcoverTypeObj->getCoverTypeName()).",
					  ".$this->db->qstr($cdcoverTypeObj->getCoverTypeDescription()).")";
			
			$this->db->Execute($query);
			
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	public function deleteCoverType($type_id) {
		try {
			$query = "DELETE FROM $this->TABLE_types WHERE cover_type_id = " .$type_id;
			$this->db->Execute($query);

		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	
	public function updateCoverType(cdcoverTypeObj $cdcoverTypeObj) {
		try {
		
			$query = "UPDATE $this->TABLE_types SET cover_type_name = 
					  ".$this->db->qstr($cdcoverTypeObj->getCoverTypeName()).",
					  cover_type_description = ".$this->db->qstr($cdcoverTypeObj->getCoverTypeDescription())."
					  WHERE cover_type_id = " . $cdcoverTypeObj->getCoverTypeID();
			$this->db->Execute($query);
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	public function getCoverById($cover_id) {
		try {
			$query = "SELECT C.cover_id, C.vcd_id, C.cover_filename, C.cover_filesize, 	
	 				  C.user_id, C.date_added, C.cover_type_id, T.cover_type_name, C.image_id
	 				  FROM $this->TABLE_covers C, $this->TABLE_types T
	 				  WHERE C.cover_type_id = T.cover_type_id AND
					  C.cover_id = " . $cover_id;
			
			$rs = $this->db->Execute($query);
			if ($rs) {
				return new cdcoverObj($rs->FetchRow());
			}
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	
	public function getAllCoversForVcd($vcd_id) {
		try {
			
			$query = "SELECT C.cover_id, C.vcd_id, C.cover_filename, C.cover_filesize, 	
	 				  C.user_id, C.date_added, C.cover_type_id, T.cover_type_name, C.image_id
	 				  FROM $this->TABLE_covers C, $this->TABLE_types T
	 				  WHERE C.cover_type_id = T.cover_type_id AND
					  C.vcd_id = " . $vcd_id;
			
						
			$rs = $this->db->Execute($query);
			$objArr = array();
			foreach ($rs as $row) {
	    		$obj = new cdcoverObj($row);
	    		array_push($objArr, $obj);
			}
			
			$rs->Close();
			return $objArr;
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}
	}
	
	public function addCover(cdcoverObj $cdcoverObj) {
		try {
					
			$query = "INSERT INTO $this->TABLE_covers 
					  (vcd_id, cover_type_id, cover_filename, cover_filesize, user_id, date_added) 
			 		  VALUES (
					  ".$cdcoverObj->getVcdId().", 
					  ".$cdcoverObj->getCoverTypeID().", 
				   	  ".$this->db->qstr($cdcoverObj->getFilename()).",
					  ".$cdcoverObj->getFilesize().",
					  ".$cdcoverObj->getOwnerId().",
					  ".$this->db->DBDate(time()).")";
			$this->db->Execute($query);
			
			
			if ($cdcoverObj->isInDB()) {
				$image_id = $cdcoverObj->getImageID();
				$query = "UPDATE $this->TABLE_covers SET image_id = ".$image_id." 
						  WHERE vcd_id = ".$cdcoverObj->getVcdId()." AND cover_type_id = ".$cdcoverObj->getCoverTypeID()."";
				$this->db->Execute($query);
				
			}
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}			
	}
	
	public function deleteCover($cover_id) {
		try {
			
			$query = "DELETE FROM $this->TABLE_covers WHERE cover_id = " . $cover_id;
			$this->db->Execute($query);
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}			
	}
		
	public function updateCover($cdcoverObj) {
		
		try {
		
			$query = "UPDATE $this->TABLE_covers 
					  SET cover_type_id = ".$cdcoverObj->getCoverTypeID().",
					  cover_filename = ".$this->db->qstr($cdcoverObj->getFilename()).",
					  cover_filesize = ".$cdcoverObj->getFilesize().",
					  user_id = ".$cdcoverObj->getOwnerId().",
					  date_added = ".$this->db->DBDate(time())."
					  WHERE cover_id = ".$cdcoverObj->getId()."";
			
			$this->db->Execute($query);
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}			
	}
	
	public function getAllCoverTypesForVcd($mediatype_id) {
		try {
			
			$query = "SELECT C.cover_type_id, C.cover_type_name, C.cover_type_description
					  FROM $this->TABLE_types C, $this->TABLE_mediatypes M, $this->TABLE_allowedcovers A
					  WHERE C.cover_type_id = A.cover_type_id AND
					  A.media_type_id = M.media_type_id AND
					  M.media_type_id = $mediatype_id
					  ORDER BY C.cover_type_name";
			
			$rs = $this->db->Execute($query);
			$coverTypeObjArr = array();
			foreach ($rs as $row) {
	    		$obj = new cdcoverTypeObj($row);
	    		array_push($coverTypeObjArr, $obj);
			}
			
			$rs->Close();
			return $coverTypeObjArr;
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}			
	}
	
	
	public function getAllowedCoversForVcd($arrMediaTypeIDs) { 
		try {
		
			for ($i = 0; $i < sizeof($arrMediaTypeIDs); $i++) {
				if ($i == 0) {
					$sel = "m.media_type_id = " . $arrMediaTypeIDs[$i];
				} else {
					$sel .= " OR m.media_type_id = " . $arrMediaTypeIDs[$i];
				}
			}
			
			$query = "SELECT c.cover_type_id, c.cover_type_name, c.cover_type_description 
					 FROM $this->TABLE_types c, $this->TABLE_allowedcovers a, $this->TABLE_mediatypes m
					 WHERE 
					 c.cover_type_id = a.cover_type_id 
					 AND a.media_type_id = m.media_type_id
					 AND (".$sel.")
					 GROUP BY c.cover_type_id, c.cover_type_name, c.cover_type_description 
					 ORDER BY cover_type_name";
						
			$rs = $this->db->Execute($query);
			$coverTypeObjArr = array();
			foreach ($rs as $row) {
	    		$obj = new cdcoverTypeObj($row);
	    		array_push($coverTypeObjArr, $obj);
			}
			
			$rs->Close();
			return $coverTypeObjArr;
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}			
	}
	
	
	public function addCoverTypesToMedia($mediaTypeID, $coverTypeID) {
		try {
		
			$query = "INSERT INTO $this->TABLE_allowedcovers (cover_type_id, media_type_id) 
					  VALUES (".$coverTypeID.", ".$mediaTypeID.")";
			$this->db->Execute($query);
			
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}			
	}
	
	public function deleteAllCoversFromMedia($mediaTypeID) {
		try {
			
			$query = "DELETE FROM $this->TABLE_allowedcovers WHERE media_type_id = " . $mediaTypeID;
			$this->db->Execute($query);
			
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}			
	}
	
	
	public function getCDcoverTypesOnMediaType($mediaType_id) {
		try {
			
			$query = "SELECT C.cover_type_id, C.cover_type_name, C.cover_type_description
					  FROM $this->TABLE_types C, $this->TABLE_allowedcovers A 
					  WHERE C.cover_type_id = A.cover_type_id
					  AND A.media_type_id = ".$mediaType_id."
					  ORDER BY C.cover_type_name";
			
			$rs = $this->db->Execute($query);
			$coverTypeObjArr = array();
			foreach ($rs as $row) {
	    		$obj = new cdcoverTypeObj($row);
	    		array_push($coverTypeObjArr, $obj);
			}
			
			$rs->Close();
		
		return $coverTypeObjArr;
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}			
	}
	
	public function getAllThumbnailsForXMLExport($user_id, $thumbnail_id) {
		try {
			
			$query = "SELECT c.cover_id, c.vcd_id, c.cover_filename, c.cover_filesize, 	
					  c.user_id, c.date_added, c.cover_type_id, t.cover_type_name, c.image_id
					  FROM $this->TABLE_covers c, $this->TABLE_types t, $this->TABLE_vcdtousers u
					  WHERE c.cover_type_id = t.cover_type_id
					  AND c.vcd_id = u.vcd_id AND
					  u.user_id = ".$user_id." AND
					  c.cover_type_id = ".$thumbnail_id."";
			
			$rs = $this->db->Execute($query);
			$coverObjArr = array();
			foreach ($rs as $row) {
	    		$obj = new cdcoverObj($row);
	    		array_push($coverObjArr, $obj);
			}
			
			$rs->Close();
			return $coverObjArr;
		
		} catch (Exception $ex) {
			throw new VCDSqlException($ex->getMessage(), $ex->getCode());
		}			
	}
	
}
?>