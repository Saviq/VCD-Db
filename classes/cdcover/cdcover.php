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
 * @subpackage CDCover
 * @version $Id$
 */
 
?>
<?php
require_once(dirname(__FILE__).'/cdcoverObj.php');

class vcd_cdcover implements ICdcover {
	
	/**
	 * Instance of cdcoverSQL
	 *
	 * @var cdcoverSQL
	 */
	private $SQL;
	private $coverTypesArr = null;
	
	public function __construct() { 
	 	$this->SQL = new cdcoverSQL();
   } 

   
	/**
	* Get all available coverType objects in an array.
	*
	* @return array
	*/
	public function getAllCoverTypes() {
		try {
			
			$this->updateCoverTypeCache();
			return $this->coverTypesArr;	
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
   
	/**
	 * Save a new coverType object to database.
	 * 
	 * @param cdcoverTypeObj $cdcoverTypeObj
	 */
	public function addCoverType(cdcoverTypeObj $cdcoverTypeObj) {
		try {

			$this->SQL->addCoverType($cdcoverTypeObj);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Delete a coverType object from database.
	 *
	 * @param int $type_id
	 */
	public function deleteCoverType($type_id) {
		try {
			
			if (!is_numeric($type_id)) {
				throw new VCDInvalidArgumentException('CoverType Id must be numeric');
			}
			
			$this->SQL->deleteCoverType($type_id);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Get all allowed coverType objects that have been associated with the selected mediaTypeId.
	 * Returns an array of coverType objects.
	 *
	 * @param int $mediatype_id
	 * @return array
	 */
	public function getAllCoverTypesForVcd($mediatype_id) {
		try {
			
			if (!is_numeric($mediatype_id)) {
				throw new VCDInvalidArgumentException('MediaType Id must be numeric');
			}
			
			return $this->SQL->getAllCoverTypesForVcd($mediatype_id);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get a coverType object by id.
	 *
	 * @param int $covertype_id
	 * @return cdcoverTypeObj
	 */
	public function getCoverTypeById($covertype_id) {
		try {
			
			if (!is_numeric($covertype_id)) {
				throw new VCDInvalidArgumentException('CoverType Id must be numeric');
			}
			
			$this->updateCoverTypeCache();
			foreach ($this->coverTypesArr as $obj) {
				if ($obj->getCoverTypeID() == $covertype_id) {
					return $obj;
				}
			}
			
			return null;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Get a cdcover object by name.
	 *
	 * @param string $covertype_name
	 * @return cdcoverTypeObj
	 */
	public function getCoverTypeByName($covertype_name) {
		try {
			
			$this->updateCoverTypeCache();
			foreach ($this->coverTypesArr as $obj) {
				if (strcmp(strtolower($obj->getCoverTypeName()), strtolower($covertype_name)) == 0) {
					return $obj;
				}
			}
			
			throw new VCDProgramException($covertype_name . ' not found');
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Update a cdcoverTypeObj in database.
	 *
	 * @param cdcoverTypeObj $cdcoverTypeObj
	 */
	public function updateCoverType(cdcoverTypeObj $cdcoverTypeObj) {
		try {
			
			$this->SQL->updateCoverType($cdcoverTypeObj);
			$this->updateCoverTypeCache();
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	
	/**
	 * Get a cover object by id.
	 *
	 * @param int $cover_id
	 * @return cdcoverObj
	 */
	public function getCoverById($cover_id) {
		try {
			
			if (!is_numeric($cover_id)) {
				throw new VCDInvalidArgumentException('Cover Id must be numeric');
			}
			
			return $this->SQL->getCoverById($cover_id);
		
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Get all cdcoverType objects associated with incoming mediaType objects.
	 * Parameter should contain array of mediaType objects.
	 * Returns an array of cdcoverType objects, if none are
	 * found - function returns null.
	 *
	 * @param array $mediaTypeObjArr
	 * @return array
	 */
	public function getAllowedCoversForVcd($mediaTypeObjArr) {
		try {
			
			if (sizeof($mediaTypeObjArr) > 0) {
				$arrMediaTypeIDs = array();
				foreach ($mediaTypeObjArr as $mediaTypeObj) {
					if ($mediaTypeObj->getParentID() > 0)  {
						$id = $mediaTypeObj->getParentID();
					} else {
						$id = $mediaTypeObj->getmediaTypeID();
					}
					array_push($arrMediaTypeIDs, $id);
				}			
				
				return $this->SQL->getAllowedCoversForVcd($arrMediaTypeIDs);	
				
			} else {
				return null;
			}
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Get all cdCover objects that are associated with the following cd id.
	 * 
	 * Returns an array of cdCover objects.
	 *
	 * @param int $vcd_id
	 * @return array
	 */
	public function getAllCoversForVcd($vcd_id) {
		try {
			
			if (!is_numeric($vcd_id)) {
				throw new VCDInvalidArgumentException('Id must be numeric');
			}
			
			$arrCovers = $this->SQL->getAllCoversForVcd($vcd_id);
			
			// Filter out the duplicates
			$arrUnique = array();
			$arrTypes = array();
			foreach ($arrCovers as $obj) {
				if (!in_array($obj->getCoverTypeID(),$arrTypes)) { 
					array_push($arrUnique, $obj);
					array_push($arrTypes, $obj->getCoverTypeID());
				}
			}

			return $arrUnique;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Save a new cdcover object to database.
	 * If same cover object exists in database, the cover object is updated 
	 * instead of inserting a duplicate entry.
	 *
	 * @param cdcoverObj $cdcoverObj
	 */
	public function addCover(cdcoverObj $cdcoverObj) {
		try {
		
			// Check cover with same coverTypeID and movie exist, and then call update 
			// instead of inserting ..
			
			$coverArr = $this->getAllCoversForVcd($cdcoverObj->getVcdId());
			if (is_array($coverArr) && sizeof($coverArr) > 0) {
				foreach ($coverArr as $coverObj) {
					if ($coverObj->getCoverTypeID() == $cdcoverObj->getCoverTypeID()) {
						$cdcoverObj->setCoverID($coverObj->getId());
						$this->updateCover($cdcoverObj);
						return;
					}
				}
			}
			
			// Nopes .. cover is brand new .. lets insert it.
			$this->SQL->addCover($cdcoverObj);
	
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Delete a cdCover object from database.
	 *
	 * @param int $cover_id
	 */
	public function deleteCover($cover_id) {
		try {
			
			if (!is_numeric($cover_id)) {
				throw new VCDInvalidArgumentException('Cover Id must be numeric');
			}
			
			$this->SQL->deleteCover($cover_id);
		
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Update a cdCover object in database.
	 *
	 * @param cdcoverObj $cdcoverObj
	 */
	public function updateCover(cdcoverObj $cdcoverObj) {
		try {

			$this->SQL->updateCover($cdcoverObj);
		
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	
	/**
	 * Link coverType objects to mediaType objects.
	 * If the param coverTypeIDArr is an empty array all linking to mediaTypeId in param 1 is deleted.
	 * Otherwise coverTypeIDArr should be an array containing cdcoverType id's.
	 *
	 * @param int $mediaTypeID
	 * @param array $coverTypeIDArr
	 */
	public function addCoverTypesToMedia($mediaTypeID, $coverTypeIDArr) {
		try {

			if (!is_numeric($mediaTypeID)) {
				throw new VCDInvalidArgumentException('Mediatype Id must be numeric');
			}
			
			if (is_array($coverTypeIDArr) && sizeof($coverTypeIDArr) == 0) {
				$this->SQL->deleteAllCoversFromMedia($mediaTypeID);
				return;
			}
			
			if (is_array($coverTypeIDArr)) {
				
				// Clean values before insert
				$this->SQL->deleteAllCoversFromMedia($mediaTypeID);

				foreach ($coverTypeIDArr as $coverTypeID) {
					$this->SQL->addCoverTypesToMedia($mediaTypeID, $coverTypeID);
				}
							
			} else {
				throw new VCDInvalidArgumentException('Param $coverTypeIDArr must be an array on covertype ids');
			}
					
		} catch (Exception $ex) {
			throw $ex;
		}
	}

	
	
	/**
	 * Get all cdcoverType objects associated with selected mediaType id.
	 * Returns an array of cdcoverType objects.
	 *
	 * @param int $mediaType_id
	 * @return array
	 */
	public function getCDcoverTypesOnMediaType($mediaType_id) {
		try {
			
			if (!is_numeric($mediaType_id)) {
				throw new VCDInvalidArgumentException('Mediatype Id must be numeric');
			}
				
			return $this->SQL->getCDcoverTypesOnMediaType($mediaType_id);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Get an array of all cdcover objects to prepare for a XML export of user's thumbnail export.
	 * 
	 * @param int $user_id
	 * @return array
	 */
	public function getAllThumbnailsForXMLExport($user_id) {
		try {
			if (!is_numeric($user_id)) {
				throw new VCDInvalidArgumentException('User Id must be numeric');
			}
				
			$coverObj = $this->getCoverTypeByName('thumbnail');
			return $this->SQL->getAllThumbnailsForXMLExport($user_id, $coverObj->getCoverTypeID());
						
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Update the internal cdcoverType cache.
	 *
	 */
	private function updateCoverTypeCache() {
		if (is_null($this->coverTypesArr))
			$this->coverTypesArr = $this->SQL->getAllCoverTypes();
	}

}
?>