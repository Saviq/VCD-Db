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

require_once(dirname(__FILE__).'/settingsObj.php');
require_once(dirname(__FILE__).'/sourceSiteObj.php');
require_once(dirname(__FILE__).'/mediaTypeObj.php');
require_once(dirname(__FILE__).'/movieCategoryObj.php');
require_once(dirname(__FILE__).'/borrowerObj.php');
require_once(dirname(__FILE__).'/loanObj.php');
require_once(dirname(__FILE__).'/commentObj.php');
require_once(dirname(__FILE__).'/statisticsObj.php');
require_once(dirname(__FILE__).'/metadataObj.php');
require_once(dirname(__FILE__).'/dvdObj.php');
require_once(dirname(__FILE__).'/rssObj.php');

class vcd_settings implements ISettings {

	private $settingsArray = null;
	private $mediatypeArray = null;
	private $mediatypeFullArray = null;
	private $moviecategoryArray = null;
	private $sourcesiteArray = null;
	private $borrowersArray = null;
	/**
	 *
	 * @var settingsSQL
	 */
	private $SQL;

	public function __construct() {
	 	$this->SQL = new settingsSQL();
   }

   /**
    * Gets all SettingsObj in database
    *
    * @return array of settingsObj
    */
	public function getAllSettings() {
		try {
		
			if (is_null($this->settingsArray)) {
				$this->updateCache();
			}
			
	   		return $this->settingsArray;

		} catch (Exception $ex) {
			throw $ex;
		}
   }

   /**
    * Save settingsObj to database.
    * Parameter can either be settingsObj or
    * an array of settingsObj
    *
    * @param mixed $settingsObj
    */
	public function addSettings($settingsObj) {
		try {
	   		
			if ($settingsObj instanceof settingsObj) {
				if ($this->checkDuplicates($settingsObj)) {
					throw new VCDConstraintException('Key already exists');
			    }

			    if (strcmp($settingsObj->getKey(),"") == 0) {
			    	throw new VCDInvalidArgumentException('Key can not be empty');
			    }

			    if (strcmp($settingsObj->getValue(),"") == 0) {
			    	throw new VCDInvalidArgumentException('Value can not be empty');
			    }

	   			$this->SQL->saveSettings($settingsObj);

			} elseif (is_array($settingsObj)) {
			   $this->SQL->saveSettings($settingsObj);
			}
			
			$this->updateCache();
			
		} catch (Exception $ex) {
			throw $ex;
		}
   }

   /**
    * Update an settingsObj
    *
    * @param settingsObj $settingsObj
    */
   public function updateSettings(settingsObj $obj) {
		try {

			$this->SQL->updateSettings($obj);
			$this->updateCache();
   		
		} catch (Exception $ex) {
			throw $ex;
		}
   }


	/**
	 * Get a value from settingsObj by certain key
	 *
	 * @param string $key
	 * @return string
	 */
	public function getSettingsByKey($key) {
		try {

			$obj = $this->getAllSettings();
			foreach ($obj as $settingsObj) {
				if (strcmp($settingsObj->getKey(),$key) == 0) {
					return $settingsObj->getValue();
				}
			}

			throw new VCDProgramException("Key {$key} was not found");

		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Delete a settingsObj, returns true if settingsObj with that key was deleted, otherwise false.
	 *
	 * @param int $settings_id
	 * @return boolean
	 */
	public function deleteSettings($settings_id) {
		try {
			
			if (!is_numeric($settings_id)) {
				throw new VCDInvalidArgumentException('Settings Id must be numeric');
			}
			
			$obj = $this->getSettingsByID($settings_id);
			if (!$obj instanceof settingsObj) {
				throw new VCDInvalidArgumentException('Invalid settings Id value');
			}

			if ($obj->isProtected()) {
				throw new VCDConstraintException('Key is protected');
			}

			$this->SQL->deleteSettings($settings_id);
			$this->updateCache();
			return true;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Get a settingsObj by id
	 *
	 * @param int $settings_id
	 * @return settingsObj
	 */
	public function getSettingsByID($settings_id) {
		try {
			
			if (!is_numeric($settings_id)) {
				throw new VCDInvalidArgumentException('Settings Id must be numeric');
			}
		
			return $this->SQL->getSettingsByID($settings_id);
				
		} catch (Exception $ex) {
			throw $ex;
		}
	}



	/* Source Site public functions  */

	/**
	 * Get all sourcesiteObjects from database
	 *
	 * @return array of sourceSiteObj
	 */
	public function getSourceSites() {
		try {
			
			$this->updateSiteCache();
			return $this->sourcesiteArray;

		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Get sourcesiteObj by id
	 *
	 * @param int $source_id
	 * @return sourceSiteObj
	 */
	public function getSourceSiteByID($source_id) {
		try {
			
			if (!is_numeric($source_id)) {
				throw new VCDInvalidArgumentException('Source Id must be numeric');
			}
		
			foreach ($this->getSourceSites() as $obj) {
				if ($obj->getsiteID() == $source_id) {
					return $obj;
				}
			}
			
			return null;

		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Get a sourceSiteObj by alias
	 *
	 * @param string $strAlias
	 * @return sourceSiteObj
	 */
	public function getSourceSiteByAlias($strAlias) {
		try {

			foreach ($this->getSourceSites() as $obj) {
				if (strcmp(strtolower($strAlias), strtolower($obj->getAlias())) == 0) {
					return $obj;
				}
			}

			return null;

		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Save a new SourceSiteObj to database
	 *
	 * @param sourceSiteObj $obj
	 */
	public function addSourceSite(sourceSiteObj $obj) {
		try {
			
			if ($obj->isFetchable() && strcmp($obj->getClassName(),"") == 0) {
				throw new VCDConstraintException('When sourcesite is marked fetchable, classname must be defined.');
			}
			
			$this->SQL->addSourceSite($obj);
			$this->updateSiteCache();
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Delete a sourcesiteObj by id
	 *
	 * @param int $source_id
	 */
	public function deleteSourceSite($source_id) {
		try {
			
			if (!is_numeric($source_id)) {
				throw new VCDInvalidArgumentException('Source Id must be numeric');
			}
		
			$this->SQL->deleteSourceSite($source_id);
			$this->updateSiteCache();
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Update an existing sourceSiteObj
	 *
	 * @param sourceSiteObj $obj
	 */
	public function updateSourceSite(sourceSiteObj $obj) {
		try {
						
			if ($obj->isFetchable() && strcmp($obj->getClassName(),"") == 0) {
				throw new VCDConstraintException('When sourcesite is marked fetchable, classname must be defined.');
			}
			
			if (strcmp($obj->getImage(),"") != 0) {
				$filename = '../images/logos/'.$obj->getImage();
				if (!fs_file_exists($filename)) {
					throw new VCDProgramException("File {$filename} was not found.");
				}
			}
		
		
			$this->SQL->updateSourceSite($obj);
			$this->updateSiteCache();

		} catch (Exception $ex) {
			throw $ex;
		}
	}




	/*

		Media Type Functions

	*/


	/**
	 * Get an array parent media types in database
	 *
	 * @return array of medaTypeObj
	 */
	public function getAllMediatypes() {
		try {
			
			if (is_null($this->mediatypeArray)) {
				$this->updateMediaCache();
			}

			return $this->mediatypeArray;

		} catch (Exeption $ex) {
			throw $ex;
		}
	}

	/**
	 * Get an array of all media types in database
	 *
	 * @return array of medaTypeObj
	 */
	public function getAllMediatypesFull() {
		try {
			if (is_null($this->mediatypeFullArray)) {
				$this->updateMediaCache();
			}

			return $this->mediatypeFullArray;

		} catch (Exeption $ex) {
			throw $ex;
		}
	}


	/**
	 * Get a mediaTypeObj by id
	 *
	 * @param int $media_id
	 * @return mediaTypeObj
	 */
	public function getMediaTypeByID($media_id) {
		try {
			
			if (!is_numeric($media_id)) {
				throw new VCDInvalidArgumentException('Media Id must be numeric');
			}

			foreach ($this->getAllMediatypes() as $obj) {
				if ($media_id == $obj->getmediaTypeID()) {
					return $obj;
				}
				// Also check the children
				foreach ($obj->getChildren() as $childObj) {
					if ($media_id == $childObj->getmediaTypeID()) {
						return $childObj;
					}
				}
			}
			
			return null;

		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Save a new mediaTypeObj to database
	 *
	 * @param mediaTypeObj $obj
	 */
	public function addMediaType(mediaTypeObj $obj) {
		try {
			
			$this->SQL->addMediaType($obj);
			$this->updateMediaCache();
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Delete mediaTypeObj from database, returns true if deletion is successful otherwise false.
	 *
	 * @param int $mediatype_id
	 * @return boolean
	 */
	public function deleteMediaType($mediatype_id) {
		try {
			
				if (!is_numeric($mediatype_id)) {
					throw new VCDInvalidArgumentException('Mediatype Id must be numeric');
				}
			
				$tempObj = $this->getMediaTypeByID($mediatype_id);
				if (!$tempObj instanceof mediaTypeObj) {
					return false;
				}

				if ($tempObj->getChildrenCount() > 0) {
					throw new VCDConstraintException('Cannot delete mediatype with active subcategories');
				}

				if ($this->SQL->getCountByMediaType($mediatype_id) > 0) {
					throw new VCDConstraintException('Media type is in use. Cannot delete.');
				}

				$this->SQL->deleteMediaType($mediatype_id);
				$this->updateMediaCache();
				return true;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Update a mediaTypeObj
	 *
	 * @param mediaTypeObj $obj
	 */
	public function updateMediaType(mediaTypeObj $obj) {
		try {
			
				$this->SQL->updateMediaType($obj);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Get all mediaTypes that are assigned to this vcdID
	 *
	 * @param int $vcd_id
	 * @return array of mediaTypeObj
	 */
	public function getMediaTypesOnCD($vcd_id) {
		try {

			$arrMediaTypesIDs = $this->SQL->getMediaTypesOnCD($vcd_id);
			if (is_array($arrMediaTypesIDs) && sizeof($arrMediaTypesIDs) > 0) {
				$arrMediaTypes = array();
				foreach ($arrMediaTypesIDs as $mediatypeid) {
					array_push($arrMediaTypes, $this->getMediaTypeByID($mediatypeid));
				}
				unset($arrMediaTypesIDs);
				return $arrMediaTypes;

			} else {
				throw new VCDProgramException("CD with id {$vcd_id} has no assigned media types");
			}

		} catch (Exception $ex) {
			throw $ex;
		}
	}





	/**
	 * Get all mediatype in use by user
	 *
	 * @param int $user_id
	 * @return array of mediaTypeObj
	 */
	public function getMediaTypesInUseByUserID($user_id) {
		try {
			if (!is_numeric($user_id)) { 
				throw new VCDInvalidArgumentException('User Id must be numeric');
			}

			$media_array = $this->SQL->getMediaTypesInUseByUserID($user_id);
			$i = 0;
			foreach ($media_array as $itemArray) {
				$catObj = $this->getMediaTypeByID($itemArray[0]);
				$media_array[$i++][1] = $catObj->getDetailedName();
			}

			asort($media_array);
			return aSortBySecondIndex($media_array,1);

		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Get all mediatype in use
	 *
	 * @return array of mediaTypeObj
	 */
	public function getMediaTypesInUse() 
	{
		try
		{
			$media_array = $this->SQL->getMediaTypesInUse();
			$i = 0;
			foreach ($media_array as $itemArray)
			{
				$catObj = $this->getMediaTypeByID($itemArray[0]);
				$media_array[$i++][1] = $catObj->getDetailedName();
			}
			asort($media_array);
			return aSortBySecondIndex($media_array,1);
		}
		catch (Exception $ex)
		{
			throw $ex;
		}
	}

	/**
	 * Get the count of mediaTypes by certain categoryID and userID
	 *
	 * @param int $user_id
	 * @param int $category_id
	 * @return int
	 */
	public function getMediaCountByCategoryAndUserID($user_id, $category_id) {
		try {

			if (!(is_numeric($user_id) && is_numeric($category_id))) {
				throw new VCDInvalidArgumentException('User Id and Category Id must be numeric');
			}
			
			return $this->SQL->getMediaCountByCategoryAndUserID($user_id, $category_id);			

		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Get the count of mediaTypes by certain categoryID
	 *
	 * @param int $category_id
	 * @return int
	 */
	public function getMediaCountByCategory($category_id)
	{
		try
		{
			if (is_numeric($category_id))
			{
				return $this->SQL->getMediaCountByCategory($category_id);
			}
			else
			{
				throw new Exception('Parameters must be numeric');
			}
		}
		catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Get a mediatypeObj by mediatype name. If media type is not found, null is returned.
	 *
	 * @param string $media_name | The name of the media type
	 * @return mediaTypeObj | The object found.
	 */
	public function getMediaTypeByName($media_name) {
		try {
			
			foreach ($this->getAllMediaTypesFull() as $movieMediaTypeObj) {
				$typename = $movieMediaTypeObj->getName();
				if (strcmp(strtolower(substr($media_name, -strlen($typename))), strtolower($typename)) == 0) {
					return $movieMediaTypeObj;
				}
			}
			
			return null;

		} catch (Exception $ex) {
			throw $ex;
		}
	}




	/*
		Movie Category Functions
	*/

	/**
	 * Get an array of all movieCategoryObj in database
	 *
	 * @return array of movieCategoryObj
	 */
	public function getAllMovieCategories() {
		try {
			
			if (is_null($this->moviecategoryArray)) {
				$this->updateCategoryCache();
			}
		
			return $this->moviecategoryArray;

		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Get an array of all movie categories that are in use.
	 *
	 * @return array of movieCategoryObj
	 */
	public function getMovieCategoriesInUse() {
		try {
			
			return $this->SQL->getMovieCategoriesInUse();
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Get all movie categories in use by user_id
	 *
	 * @param int $user_id
	 * @return array of movieCategoryObj
	 */
	public function getCategoriesInUseByUserID($user_id) {
		try {

			if (!is_numeric($user_id)) {
				throw new VCDInvalidArgumentException('User Id must be numeric');
			}
				
			return $this->SQL->getCategoriesInUseByUserID($user_id);

		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Get one instance of movieCategoryObj by ID
	 *
	 * @param int $category_id
	 * @return movieCategoryObj
	 */
	public function getMovieCategoryByID($category_id) {
		try {
			
			foreach ($this->getAllMovieCategories() as $movieCategoryObj) {
				if ($movieCategoryObj->getID() == $category_id) {
					return $movieCategoryObj;
				}
			}
			
			return null;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Save a new movieCategoyObj to database
	 *
	 * @param movieCategoyObj $obj
	 */
	public function addMovieCategory(movieCategoryObj $obj) {
		try {
			
			$this->SQL->addMovieCategory($obj);

		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Delete a movieCategoryObj from database
	 *
	 * @param int $category_id
	 */
	public function deleteMovieCategory($category_id) {
		try {
			
			if (!is_numeric($category_id)) {
				throw new VCDInvalidArgumentException('Category Id must be numeric');
			}

			// check if category is in use ..
			foreach ($this->getMovieCategoriesInUse() as $obj) {
				if ($obj->getID() == $category_id) {
					throw new VCDConstraintException('Cannot delete category that is in use.');
				}
			}

			$this->SQL->deleteMovieCategory($category_id);
			$this->updateCategoryCache();
			

		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Update an instance of movieCategoryObj in database
	 *
	 * @param movieCategoryObj $obj
	 */
	public function updateMovieCategory(movieCategoryObj $obj) {
		try {
			
			$this->SQL->updateMovieCategory($obj);

		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Get a moviecategory_id by name
	 *
	 * @param string $category_name | The category name
	 * @param bool $localized | Is the category name in English or not
	 * @return int
	 */
	public function getCategoryIDByName($category_name, $localized=false) {
		try {
			
			foreach ($this->getAllMovieCategories() as $movieCategoryObj) {
				if (strcmp(strtolower($category_name), strtolower($movieCategoryObj->getName($localized))) == 0) {
					return $movieCategoryObj->getID();
				}
			}
			
			return 0;

		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Get the categoryId of a movie by the movie ID
	 *
	 * @param int $itemId | The ID of the item to lookup
	 * @return int
	 */
	public function getCategoryIDByItemId($itemId) {
		try {	
		
			if (!is_numeric($itemId)) {
				throw new VCDInvalidArgumentException('Item ID must be numeric.');
			}
			
			return $this->SQL->getCategoryIDByItemId($itemId);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	
	/*
		Borrowers functions
	*/

	/**
	 * Get borrower object by ID
	 *
	 * @param int $borrower_id
	 * @return borrowerObj
	 */
	public function getBorrowerByID($borrower_id) {
		try {

			if (!VCDUtils::isLoggedIn()) {
				throw new VCDSecurityException('Unauthorized function call.');
			}
			
			if (!is_numeric($borrower_id)) {
				throw new VCDInvalidArgumentException('Borrower Id must be numeric');
			}

			if (is_null($this->borrowersArray)) {
				// should not need this, just a precaution
				$this->getBorrowersByUserID(VCDUtils::getUserID());
			}

			foreach ($this->borrowersArray as $obj) {
				if (strcmp($borrower_id, $obj->getID()) == 0) {
					return $obj;
				}
			}

			// No borrower found, should never happen
			throw new VCDProgramException("Borrower with Id {$borrower_id} does not exist.");

		} catch (Exception $ex) {
			throw $ex;
		}

	}

	/**
	 * Get an array of all borrower objects belonging to the specified user ID.
	 *
	 * @param int $user_id
	 * @return array
	 */
	public function getBorrowersByUserID($user_id) {
		try {
			
			if (is_null($this->borrowersArray)) {
				$this->updateBorrowersCache($user_id);
			}
			
			return $this->borrowersArray;

		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Add a new borrower object to database
	 *
	 * @param borrowerObj $obj
	 */
	public function addBorrower(borrowerObj $obj) {
		try {
			
			$this->SQL->addBorrower($obj);

		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Update borrowe object in database
	 *
	 * @param borrowerObj $obj
	 */
	public function updateBorrower(borrowerObj $obj) {
		try {
			
			$this->SQL->updateBorrower($obj);

		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Delete a borrower from database and all related records to him.
	 *
	 * @param borrowerObj $obj
	 */
	public function deleteBorrower(borrowerObj $obj) {
		try {

			// Check if user is allowd to delete this borrowerObj
			$user_id = VCDUtils::getUserID();
			if ($obj->getOwnerID() != $user_id) {
				throw new VCDConstraintException("You have no permission to delete borrower " . $obj->getName());
			}

			// Delete the borrower loan history records
			$this->deleteLoanRecords($obj->getID());
			$this->SQL->deleteBorrower($obj->getID());

		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/*
		Loan system functions
	*/

	/**
	 * Loan movies to borrower. Param $arrMovieIDs must contain  array of movie ID's.
	 * Returns true on success otherwise false.
	 *
	 * @param int $borrower_id
	 * @param array $arrMovieIDs
	 * @return bool
	 */
	public function loanCDs($borrower_id, $arrMovieIDs) {
		try {
			
			if (!is_array($arrMovieIDs)) {
				throw new VCDInvalidArgumentException('Expected Ids as an array');
			}
			
			if (!is_numeric($borrower_id)) {
				throw new VCDInvalidArgumentException('Borrower Id must be numeric');
			}
		
			foreach ($arrMovieIDs as $cd_id) {
				$this->SQL->loanCDs(VCDUtils::getUserID(), $borrower_id, $cd_id);
			}
			
			return true;

		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Return a movie from loan, returns true on success otherwise false.
	 *
	 * @param int $loan_id
	 * @return bool
	 */
	public function loanReturn($loan_id) {
		try {
			
			if (!is_numeric($loan_id)) { 
				throw new VCDInvalidArgumentException('Loan Id must be numeric');
			}
				
			$this->SQL->loanReturn($loan_id);
			return true;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Get all loans by specified user, param $show_returned specifies if only movies currently in loan
	 * should be returned or all movies ever to be loaned. Returns an array of loan objects.
	 *
	 * @param int $user_id
	 * @param bool $show_returned
	 * @return array
	 */
	public function getLoans($user_id, $show_returned) {
		try {
			if (!is_numeric($user_id)) { 
				throw new VCDInvalidArgumentException('User Id must be numeric');
			}

			$loanArr =  $this->SQL->getLoans($user_id, $show_returned);
			$outArr = array();
			foreach ($loanArr as $data) {
				$inArr = array($data[0], $data[1], $data[2], $this->getBorrowerByID($data[3]), $data[4], $data[5]);
				$obj = new loanObj($inArr);
				array_push($outArr, $obj);
			}
			unset($loanArr);
			unset($inArr);
			
			return $outArr;

		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Delete all loan records by borrower id
	 *
	 * @param int $borrower_id
	 */
	private function deleteLoanRecords($borrower_id) {
		try {

			if (!is_numeric($borrower_id)) {
				throw new VCDInvalidArgumentException('Borrower Id must be numeric');
			}
		
			$this->SQL->deleteLoanRecords($borrower_id);

		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Get all loans by specified borrower id. $show_returned can specify weither to show all movies ever loaned
	 * to that borrower or only the movies that he currently has in loan. Returns an array of loan objects.
	 *
	 * @param int $user_id
	 * @param int $borrower_id
	 * @param bool $show_returned
	 * @return array
	 */
	public function getLoansByBorrowerID($user_id, $borrower_id, $show_returned = false) {
		try {

			if (!is_numeric($user_id) || !is_numeric($borrower_id)) {
				throw new VCDInvalidArgumentException('User Id and Borrower Id must be numeric');
			}

			$arr = $this->getLoans($user_id, $show_returned);
			// Filter out the ones for the current borrower
			$userloans = array();
			foreach ($arr as $loanObj) {
				if ($loanObj->getBorrowerID() == $borrower_id) {
					array_push($userloans, $loanObj);
				}
			}

			unset($arr);
			return $userloans;

		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/* Notification */

	/**
	 * Send email notifycation. Send out email notification to all users that are wathing for 
	 * new movies to be inserted in the database. Returns true on successful email delivery otherwise false.
	 *
	 * @param vcdObj $obj
	 * @return bool
	 */
	public function notifyOfNewEntry(vcdObj $obj) {
		try {

			// First, find all the users that want to be notified
			$notifyPropObj = $this->User()->getPropertyByKey('NOTIFY');
			if (!$notifyPropObj instanceof userPropertiesObj) {
				throw new VCDProgramException('Property key Notify was not found, aborting.');
			}

			$notifyUsers = $this->User()->getAllUsersWithProperty($notifyPropObj->getpropertyID());

			if (is_array($notifyUsers) && sizeof($notifyUsers) > 0) {
				$arrEmails = array();
				foreach ($notifyUsers as $userObj) {
					// If the movie is an adult film .. only notify those with adult enabled ..
					if ($obj->isAdult() && !(bool)$userObj->getPropertyByKey('SHOW_ADULT')) {continue;}
					array_push($arrEmails, $userObj->getEmail());
				}
				
				unset($notifyUsers);
				$body = createNotifyEmailBody($obj);
				VCDUtils::sendMail($arrEmails, 'New entry in VCD-db', $body, true);
			}

		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/*  Rss Feeds */
	
	 /**
	 * Add a new feed to database.
	 *
	 * @param rssObj $obj
	 */
	public function addRssfeed(rssObj $obj) {
		try {
			
			$this->SQL->addRssfeed($obj);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Get a single RSS feed by ID
	 *
	 * Returns array containing the RSS feed information.
	 *
	 * @param int $feed_id
	 * @return array
	 */
	public function getRssfeed($feed_id) {
		try {
			
			if (!is_numeric($feed_id)) {
				throw new VCDInvalidArgumentException('Feed Id must be numeric');
			}

			return $this->SQL->getRSSfeedByID($feed_id);

		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Update RSS feed entry in the database.
	 *
	 * @param rssObj $obj
	 */
	public function updateRssfeed(rssObj $obj) {
		try {
			
			$this->SQL->updateRssfeed($obj);

		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Get all RSS feeds by specified user ID. Returns an array containing all RSS feeds
	 *
	 * @param int $user_id
	 * @return array
	 */
	public function getRssFeedsByUserId($user_id) {
		try {
			
			return $this->SQL->getRssFeedsByUserId($user_id);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Delete specified RSS feed from database
	 *
	 * @param int $feed_id
	 */
	public function delFeed($feed_id) {
		try {
			
			if (!is_numeric($feed_id)) {
				throw new VCDInvalidArgumentException('Feed Id must be numeric');
			}
				
			$this->SQL->delFeed($feed_id);

		} catch (Exception $ex) {
			throw $ex;
		}
	}



	/* Wishlist */
	/**
	 * Add a new movie to user's wishlist
	 *
	 * @param int $vcd_id
	 * @param int $user_id
	 */
	public function addToWishList($vcd_id, $user_id) {
		try {
			
			if (!(is_numeric($user_id) && is_numeric($vcd_id)))	 {
				throw new VCDInvalidArgumentException('Movie Id and User Id must be numeric');
			}
				
			$this->SQL->addToWishList($vcd_id, $user_id);

		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Get user's wishlist
	 *
	 * @param int $user_id
	 * @return array
	 */
	public function getWishList($user_id) {
		try {
			
			if (!is_numeric($user_id)) {
				throw new VCDInvalidArgumentException('User Id must be numeric');
			}
				
			$wishlistArr = $this->SQL->getWishList($user_id);
			
			if (VCDUtils::isLoggedIn() && VCDUtils::getUserID() != $user_id) {
				// User is view-ing others wishlist, lets check if user owns movies from this wishlist
				$ArrVCDids = $this->SQL->getVCDIDsByUser(VCDUtils::getUserID());
				if (is_array($ArrVCDids) && sizeof($ArrVCDids) > 0) {
					// Loop through the list
					$comparedArr = array();
					if (sizeof($wishlistArr) > 0) {
						foreach ($wishlistArr as $item) {
							$iown = 0;
							if (in_array($item['id'], $ArrVCDids)) {
								$iown = 1;
							}
							array_push($comparedArr, array('id' => $item['id'], 'title' => $item['title'], 'mine' => $iown));
						}
					}
					unset($wishlistArr);
					unset($ArrVCDids);
					return $comparedArr;
				}
			} else {
				return $wishlistArr;
			}

		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Check if the specified movie is on user's wishlist
	 *
	 * @param int $vcd_id
	 * @return bool
	 */
	public function isOnWishList($vcd_id) {
		try {

			if (!is_numeric($vcd_id)) {
				throw new VCDInvalidArgumentException('Movie Id must be numeric');
			}

			$user_id = VCDUtils::getUserID();
			if (is_null($user_id)) {
				throw new VCDException('User is not logged in.');
			}
			
			return $this->SQL->isOnWishList($vcd_id, $user_id);

		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Remove movie from user's wishlist
	 *
	 * @param int $vcd_id
	 * @param int $user_id
	 */
	public function removeFromWishList($vcd_id, $user_id) {
		try {
			if (!(is_numeric($user_id) && is_numeric($vcd_id)))	{
				throw new VCDInvalidArgumentException('Movie Id and User Id must be numeric');
			}
			
			$this->SQL->removeFromWishList($vcd_id, $user_id);

		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * See if any public wishlists are available
	 *
	 * @param int $user_id
	 * @return bool
	 */
	public function isPublicWishLists($user_id) {
		try {
			
			if (is_numeric($user_id)) {
				
				$propertyObj = $this->User()->getPropertyByKey(vcd_user::$PROPERTY_WISHLIST);
				if ($propertyObj instanceof userPropertiesObj ) {
					return $this->SQL->isPublicWishLists($user_id, $propertyObj->getpropertyID());	
				}
			}
			
			return false;

		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/* Comments */
	/**
	 * Add a comment to specified movie
	 *
	 * @param commentObj $obj
	 */
	public function addComment(commentObj $obj) {
		try {

			$this->SQL->addComment($obj);

		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Delete commnent from database.
	 *
	 * @param int $comment_id
	 */
	public function deleteComment($comment_id) {
		try {

			if (!is_numeric($comment_id)) {
				throw new VCDInvalidArgumentException('Comment Id must be numeric');
			}

			$this->SQL->deleteComment($comment_id);

		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Get a comment by ID
	 *
	 * @param int $comment_id
	 * @return commentObj
	 */
	public function getCommentByID($comment_id) {
		try {

			if (!is_numeric($comment_id)) {
				throw new VCDInvalidArgumentException('Comment Id must be numeric');
			}

			return $this->SQL->getCommentByID($comment_id);

		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Get all comments by user ID, returns array of comment objects
	 *
	 * @param int $user_id
	 * @return array
	 */
	public function getAllCommentsByUserID($user_id) {
		try {

			if (!is_numeric($user_id)) {
				throw new VCDInvalidArgumentException('User Id must be numeric');
			}

			return $this->SQL->getAllCommentsByUserID($user_id);

		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Get all comments for specified movie. Returns array of comment objects.
	 *
	 * @param int $vcd_id
	 * @return array
	 */
	public function getAllCommentsByVCD($vcd_id) {
		try {

			if (!is_numeric($vcd_id)) {
				throw new VCDInvalidArgumentException('Movie Id must be numeric');
			}

			return $this->SQL->getAllCommentsByVCD($vcd_id);

		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Get site statistics.
	 *
	 * @return statisticsObj
	 */
	public function getStatsObj() {
		try {

			$obj = $this->SQL->getStatsObj();
			$maxRecords = 6;
			$arrAllCats = $obj->getBiggestCats();
			$arrMonCats = $obj->getBiggestMonhtlyCats();
			$obj->resetCategories();

			$counter = 0;
			$arrMonCatObjs = array();
			foreach ($arrMonCats as $item) {
				if ($counter >= $maxRecords) { break; }
				$currObj = $this->getMovieCategoryByID($item[0]);
				$currObj->setCategoryCount($item[1]);
				array_push($arrMonCatObjs, $currObj);
				$counter++;
			}
			$obj->setBiggestMonhtlyCats($arrMonCatObjs);


			$counter = 0;
			$arrCatObjs = array();
			foreach ($arrAllCats as $itemArr) {
				if ($counter >= $maxRecords) { break; }
				$catObj = $this->getMovieCategoryByID($itemArr[0]);
				$catObj->setCategoryCount($itemArr[1]);
				array_push($arrCatObjs, $catObj);
				$counter++;
			}
			$obj->setBiggestCats($arrCatObjs);

			return $obj;

		} catch (Exception $ex) {
			throw $ex;
		}
	}



	/**
	 * Return a array containing 3 arrays with statistics about users movies.
	 *
	 * @param int $user_id
	 * @return array
	 */
	public function getUserStatistics($user_id) {
		try {

			if (!is_numeric($user_id)) {
				throw new VCDInvalidArgumentException('User Id must be numeric');
			}
				
			return $this->SQL->getUserStatistics($user_id);

		} catch (Exception $ex) {
			throw $ex;
		}
	}



	/* Metadata objects */

	/**
	 * Add metadata to database. Param $arrObj can either be metadataObj or an array of metadata objects.  
	 * If metadata object with same record_id, user_id and metadata name exists already, that
	 * entry is updated instead of inserting duplicate record.
	 *
	 * @param mixed $arrObj
	 * @param bool $forceCheck | Force to check the mediatypeID field also.
	 */
	public function addMetadata($arrObj, $forceCheck = true) {
	 	try {
	 		
	 		if ($forceCheck) {
	 			if (is_array($arrObj)) {
	 				foreach ($arrObj as $metaObj) {
	 					$this->addMetadata($metaObj, true);
	 				}
	 			} else {

	 				if (!$arrObj instanceof metadataObj ) {
	 					throw new VCDProgramException('Expected metadata object.');
	 				}

	 				// Check for new metadataObj that is allowed duplicate
	 				if (is_null($arrObj->getMetadataID()) && $arrObj->isDuplicatesAllowed()) {
	 					if (strcmp(trim($arrObj->getMetadataValue()), "") != 0)  {
	 						$this->SQL->addMetadata($arrObj);
	 						return;
	 					}
	 				}
	 				
	 				// If metadataObj already has metadata_id update is called
	 				if (!is_null($arrObj->getMetadataID()) && is_numeric($arrObj->getMetadataID())) {
	 					$this->updateMetadata($arrObj);
	 					return;
	 				}
	 				
	 				$oldArr = $this->getMetadata($arrObj->getRecordID(), $arrObj->getUserID(), $arrObj->getMetadataName(), $arrObj->getmediaTypeID());
	 				$oldObj = null;
	 				if (is_array($oldArr) && sizeof($oldArr) == 1) {
	 					$oldObj = $oldArr[0];
	 					$arrObj->setMetadataID($oldObj->getMetadataID());
	 					$this->updateMetadata($arrObj);
	 				} else {
	 					// if the data is empty .. we return without throwing an error.
	 					if (strcmp(trim($arrObj->getMetadataValue()), "") != 0)  {
	 						$this->SQL->addMetadata($arrObj);
	 					}

	 				}
	 			}
	 			return;
	 		}



	 		if ($arrObj instanceof metadataObj ) {
	 			$oldObj = $this->getMetadata($arrObj->getRecordID(), $arrObj->getUserID(), $arrObj->getMetadataName());
	 			if (is_array($oldObj) && sizeof($oldObj) == 1) {
	 				$arrObj->setMetadataID($oldObj[0]->getMetadataID());
	 				$this->updateMetadata($arrObj);
	 			} else {

	 				// do we have a valid metadataTypeObj parent ?
	 				if ($arrObj->getMetadataTypeID() == -1) {
	 					// not a valid parent, lets construct it
	 					$metaTypeObj = $arrObj->getMetaDataTypeInstance();
	 					$metaTypeObj = $this->addMetaDataType($metaTypeObj);
	 					$arrObj->setMetaDataTypeID($metaTypeObj->getMetadataTypeID());
	 				}
					if (strcmp(trim($arrObj->getMetadataValue()), "") != 0)  {
	 					$this->SQL->addMetadata($arrObj);
					}
	 			}


	 		} elseif (is_array($arrObj)) {

	 			foreach ($arrObj as $metaObj) {
	 				$oldObj = null;
	 				$arr = $this->getMetadata($metaObj->getRecordID(), $metaObj->getUserID(), $metaObj->getMetadataName());

	 				if (is_array($arr) && sizeof($arr) == 1) {
	 					$oldObj = $arr[0];
	 				}
	 				if ($metaObj instanceof metadataObj ) {
	 					if ($oldObj	instanceof metadataObj) {
			 				$metaObj->setMetadataID($oldObj->getMetadataID());
			 				$this->updateMetadata($metaObj);
			 			} else {
			 				// do we have a valid metadataTypeObj parent ?
				 			if ($metaObj->getMetadataTypeID() == -1) {
				 				// not a valid parent, lets construct it
				 				$metaTypeObj = $arrObj->getMetaDataTypeInstance();
				 				$metaTypeObj = $this->addMetaDataType($metaTypeObj);
				 				$arrObj->setMetaDataTypeID($metaTypeObj->getMetadataTypeID());
				 			}
				 			if (strcmp(trim($metaObj->getMetadataValue()), "") != 0)  {
			 					$this->SQL->addMetadata($metaObj);
				 			}
			 			}
	 				}
	 			}
	 		}

	 	} catch (Exception $ex) {
	 		throw $ex;
	 	}
	}


	/**
	 * Update metadata object
	 *
	 * @param metadataObj $obj
	 */
	public function updateMetadata(metadataObj $obj) {
		try {

			$this->SQL->updateMetadata($obj);

	 	} catch (Exception $ex) {
	 		throw $ex;
	 	}
	}

	/**
	 * Delete metadata object
	 *
	 * @param int $metadata_id
	 */
	public function deleteMetadata($metadata_id) {
		try {
	 		if (!is_numeric($metadata_id)) {
	 			throw new VCDInvalidArgumentException('Metadata Id must be numeric');
	 		}
	 			
 			// Check if user has rights to delete this metadata ..
 			$metaDataObj = $this->SQL->getMetadataById($metadata_id);
 			if ($metaDataObj instanceof metadataObj ) {
 				if ($metaDataObj->getUserID() == VCDUtils::getUserID() ) {
 					$this->SQL->deleteMetadata($metadata_id);
 				} else {
 					throw new VCDConstraintException('You do not have rights to delete this metadata entry.');
 				}
 			}

	 	} catch (Exception $ex) {
	 		throw $ex;
	 	}
	}


	/**
	 * Get specific metadata. If an entry with specific parameters does not exists,
	 * null is returned.  Otherwise array of metadata is returned
	 *
	 * @param int $record_id
	 * @param int $user_id
	 * @param int $metadata_name
	 * @param int $mediatype_id | MediaType ID of movieObj.  This forces deeper check.
	 * @return array
	 */
	public function getMetadata($record_id, $user_id = null, $metadata_name, $mediatype_id = null) {
		try {
			
	 		if (is_numeric($record_id) && is_numeric($user_id)) {

	 			// reverse metadataObj SYS constant to correct string
	 			if (is_numeric($metadata_name)) {
	 				$mappingName = metadataTypeObj::getSystemTypeMapping($metadata_name);
	 				if ($mappingName) {
	 					return $this->SQL->getMetadata($record_id, $user_id, $mappingName, $mediatype_id);
	 				} else {
	 					throw new VCDProgramException('System mapping for metadataType not found.');
	 				}

	 			} else {
	 				return $this->SQL->getMetadata($record_id, $user_id, $metadata_name, $mediatype_id);
	 			}


	 		} else {
	 			if (is_numeric($record_id)) {
	 				// Get metadata only based on the Record ID
	 				return $this->SQL->getMetadata($record_id, $user_id, $metadata_name, $mediatype_id);
	 			} else {
	 				return null;
	 			}
	 		}

	 	} catch (Exception $ex) {
	 		throw $ex;
	 	}
	}

	/**
	 * Get single MetadataObject by metadata ID
	 *
	 * @param int $metadata_id | The MetaData ID
	 * @return metadataObj
	 */
	public function getMetadataById($metadata_id) {
		try {

			if (!is_numeric($metadata_id)) { 
				throw new VCDInvalidArgumentException('Metadata Id must be numeric');
			}
			
			return $this->SQL->getMetadataById($metadata_id);

 		} catch (Exception $ex) {
 			throw $ex;
 		}
	}


	/**
	 * Get an array of all records id's in metadata objects belonging to specified user_id and metadata_name.
	 *
	 * @param int $user_id
	 * @param string $metadata_name
	 * @return array
	 */
	public function getRecordIDsByMetadata($user_id, $metadata_name) {
		try {

			if (is_numeric($user_id) && strcmp($metadata_name, "") != 0) {

				if (is_numeric($metadata_name)) {
					$sysName = metadataTypeObj::getSystemTypeMapping($metadata_name);
					if ($sysName) {
						return $this->SQL->getRecordIDsByMetadata($user_id, $sysName);
					} else {
						return null;
					}
				} else {
					return $this->SQL->getRecordIDsByMetadata($user_id, $metadata_name);
				}


			}
			
			return null;

		} catch (Exception $ex) {
			throw $ex;
		}
	}



	/**
	 * Add a new metadataTypeObj to the database. The updated metadataTypeObj is then returned.
	 *
	 * @param metadataTypeObj $obj
	 * @return metadataTypeObj
	 */
	public function addMetaDataType(metadataTypeObj $obj) {
		try {

			return $this->SQL->addMetaDataType($obj);

		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Get all known metadatatypes from database. If $user_id is provided, only metadatatypes created by that
	 * user_id will be returned. Function returns array of metadataTypeObjects.
	 *
	 * @param int $user_id | The user_id to filter metadatatypes to, null = no filter
	 * @return array
	 */
	public function getMetadataTypes($user_id = null) {
		try {

			return $this->SQL->getMetadataTypes($user_id);

		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Delete metadataType object.  Only user defined metadata can be deleted by it's creator.
	 *
	 * @param int $metatype_id | The metadataType Id to delete
	 */
	public function deleteMetaDataType($metatype_id) {
		try {

			if (is_numeric($metatype_id)) {
				// Check if the user trying to delete the object is actually the owner of the metadataType.
				$canDelete = false;

				$metaArr = $this->getMetadataTypes(VCDUtils::getUserID());
				foreach ($metaArr as $metatypeObj) {
					if ($metatypeObj->getMetadataTypeID() === $metatype_id) {
						$canDelete = true;
						break;
					}
				}

				if ($canDelete) {
					$this->SQL->deleteMetaDataType($metatype_id);
				} else {
					throw new VCDConstraintException('You are not the owner of this entry, aborting.');
				}
			}

 		} catch (Exception $ex) {
 			throw $ex;
 		}
	}


	/**
	 * Delete NFO Metadata from DB and delete the file aswell from filelevel.
	 *
	 * @param int $metadata_id | The metadata ID to delete
	 */
	public function deleteNFO($metadata_id) {
		try {
			if (VCDUtils::isLoggedIn() && is_numeric($metadata_id)) {
				// Get the metadata Object
				$metaObj = $this->getMetadataById($metadata_id);

				// Check if the logged in user is actually the owner of the file
				if ($metaObj instanceof metadataObj && $metaObj->getUserID() == VCDUtils::getUserID()) {

					// But before we delete the NFO file, make sure no one else is linking to it ..
					$useCount = $this->SQL->getMetadataValueCount($metaObj);
					if (is_numeric($useCount) && $useCount == 1) {
						// No one else is using this NFO, safe to delete
						// Delete the file from filelevel
						$filename = NFO_PATH . $metaObj->getMetadataValue();
						fs_unlink($filename);
					}

					// Delete the metadataObj from DB
					$this->deleteMetadata($metadata_id);

				} else {
					throw new VCDConstraintException('You do not have access to delete this file.');
				}

			}
		} catch (Exception $ex) {
			throw $ex;
		}
	}



	/*
		Private functions below

	*/

	/**
	 * Update the internal settings cache.
	 *
	 */
	private function updateCache() {
		try {
			
			$this->settingsArray = $this->SQL->getAllSettings();
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Update the internal media type cache
	 *
	 */
	private function updateMediaCache() {

		$this->mediatypeArray = array();
		$this->mediatypeFullArray = array();
		$arrAllMedia = $this->SQL->getAllMediaTypes();

		// filter out the parent
		foreach ($arrAllMedia as $mediaTypeObj) {
			array_push($this->mediatypeFullArray, $mediaTypeObj);
			if ($mediaTypeObj->isParent()) {

				// Get it's children
				foreach ($arrAllMedia as $childObj) {
					if ($mediaTypeObj->getmediaTypeID() == $childObj->getParentID()) {
						$mediaTypeObj->addChild($childObj);
					}
				}

				array_push($this->mediatypeArray, $mediaTypeObj);
			}

		}

		unset($arrAllMedia);

	}

	/**
	 * Update the internal category cache.
	 *
	 */
	private function updateCategoryCache() {
		try {
			
			$this->moviecategoryArray = $this->SQL->getAllMovieCategories();
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Update the internal sourceSite obj cache.
	 *
	 */
	private function updateSiteCache() {
		if (is_null($this->sourcesiteArray)) {
			$this->sourcesiteArray = $this->SQL->getSourceSites();
		}
	}

	/**
	 * Update the internal Borrowers object cache.
	 *
	 * @param int $user_id
	 */
	private function updateBorrowersCache($user_id) {
		if (is_null($this->borrowersArray)) {
			$this->borrowersArray = $this->SQL->getBorrowersByUserID($user_id);
		}
	}


	/**
	 * Check for duplicate keys in the settings objects.
	 *
	 * @param settingsObj $settingsObj
	 * @return bool
	 */
	private function checkDuplicates($settingsObj) {
		if (is_null($this->settingsArray))
   			$this->updateCache();

		foreach ($this->settingsArray as $cacheObj) {
			if (strcmp(strtoupper($settingsObj->getKey()),strtoupper($cacheObj->getKey())) == 0) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Get an instance of the vcd_user class
	 *
	 * @return vcd_user
	 */
	private function User() {
		return VCDClassFactory::getInstance('vcd_user');
	}
}


?>