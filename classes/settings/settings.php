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
 * @package Settings
 * @version $Id$
 */

?>
<?

require_once("settingsObj.php");
require_once("sourceSiteObj.php");
require_once("mediaTypeObj.php");
require_once("movieCategoryObj.php");
require_once("borrowerObj.php");
require_once("loanObj.php");
require_once("commentObj.php");
require_once("statisticsObj.php");
require_once("metadataObj.php");
require_once("dvdObj.php");

class vcd_settings implements Settings {

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

   	} catch (Exception $e) {
   		VCDException::display($e);
   	}

   }

   /**
    * Save settingsObj to database.
    *
    * Parameter can either be settingsObj or
    * an array of settingsObj
    *
    * @param mixed $settingsObj
    */
   public function addSettings($settingsObj) {

   		try {
	   		if ($settingsObj instanceof settingsObj) {
			    if ($this->checkDuplicates($settingsObj)) {
			    	VCDException::display("Key already exist");
			    	return;
			    }

			    if (strcmp($settingsObj->getKey(),"") == 0) {
			    	VCDException::display("Key cannot be empty");
			    	return;
			    }

			    if (strcmp($settingsObj->getValue(),"") == 0) {
			    	VCDException::display("Value cannot be empty");
			    	return;
			    }

	   			$this->SQL->saveSettings($settingsObj);

			}
			elseif (is_array($settingsObj)) {
			   $this->SQL->saveSettings($settingsObj);
			}
		} catch (Exception $e) {
			VCDException::display($e);
		}
		$this->updateCache();
   }

   /**
    * Update an settingsObj
    *
    * @param settingsObj $settingsObj
    */
   public function updateSettings($settingsObj) {
   		if ($settingsObj instanceof settingsObj) {
   			try {
   				$this->SQL->updateSettings($settingsObj);
   			} catch (Exception $e) {
   				VCDException::display($e);
   			}

		}
		$this->updateCache();
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

			throw new Exception("Key ".$key." not found");

		} catch (Exception $e) {
			VCDException::display($e);
			return false;
		}


	}

	/**
	 * Delete a settingsObj.
	 *
	 * Returns true if settingsObj with that key was deleted, otherwise false.
	 *
	 * @param int $settings_id
	 * @return boolean
	 */
	public function deleteSettings($settings_id) {
		if (is_numeric($settings_id)) {

			$obj = $this->getSettingsByID($settings_id);
			if (!$obj instanceof settingsObj) {
				return false;
			}

			if ($obj->isProtected()) {
				return false;
			}

			$this->SQL->deleteSettings($settings_id);
			$this->updateCache();
			return true;
		}
		return false;
	}


	/**
	 * Get a settingsObj by id
	 *
	 * @param int $settings_id
	 * @return settingsObj
	 */
	public function getSettingsByID($settings_id) {
		try {
			if (is_numeric($settings_id)) {
				return $this->SQL->getSettingsByID($settings_id);
			}
		} catch (Exception $e) {
			VCDException::display($e);
			return null;
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

		} catch (Exception $e) {
			VCDException::display($e);
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

			if (is_numeric($source_id)) {
				foreach ($this->getSourceSites() as $obj) {
					if ($obj->getsiteID() == $source_id) {
						return $obj;
					}
				}
				return null;
			}

		} catch (Exception $e) {
			VCDException::display($e);
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

		} catch (Exception $e) {
			VCDException::display($e);
		}

	}

	/**
	 * Save a new SourceSiteObj to database
	 *
	 * @param sourceSiteObj $sourceSiteObj
	 */
	public function addSourceSite($sourceSiteObj) {
		try {
			if ($sourceSiteObj instanceof sourceSiteObj ) {
				$this->SQL->addSourceSite($sourceSiteObj);
				$this->updateSiteCache();
			} else {
				throw new Exception('Wrong object type');
			}


		} catch (Exception $e) {
			VCDException::display($e);
		}
	}


	/**
	 * Delete a sourcesiteObj by id
	 *
	 * @param int $source_id
	 */
	public function deleteSourceSite($source_id) {
		try {
			if (is_numeric($source_id)) {
				$this->SQL->deleteSourceSite($source_id);
				$this->updateSiteCache();
			}
		} catch (Exception $e) {
			VCDException::display($e);
		}
	}

	/**
	 * Update an existing sourceSiteObj
	 *
	 * @param sourceSiteObj $sourceSiteObj
	 */
	public function updateSourceSite($sourceSiteObj) {
		try {
			if ($sourceSiteObj instanceof sourceSiteObj ) {
				$this->SQL->updateSourceSite($sourceSiteObj);
				$this->updateSiteCache();
			} else {
				throw new Exception('Wrong object type');
			}


		} catch (Exception $e) {
			VCDException::display($e);
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


		} catch (Exeption $e) {
			VCDException::display($e);
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


		} catch (Exeption $e) {
			VCDException::display($e);
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
			if (is_numeric($media_id)) {

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
			}

		} catch (Exception $e) {
			VCDException::display($e);
		}
	}


	/**
	 * Save a new mediaTypeObj to database
	 *
	 * @param mediaTypeObj $mediaTypeObj
	 */
	public function addMediaType($mediaTypeObj) {
		try {
			if ($mediaTypeObj instanceof mediaTypeObj) {
				$this->SQL->addMediaType($mediaTypeObj);
				$this->updateMediaCache();
			}
		} catch (Exception $e) {
			VCDException::display($e);
		}
	}


	/**
	 * Delete mediaTypeObj from database.
	 *
	 * Return true if deletion is successful otherwise
	 * returns false.
	 *
	 * @param int $mediatype_id
	 * @return boolean
	 */
	public function deleteMediaType($mediatype_id) {
		try {
			if (is_numeric($mediatype_id)) {
				$tempObj = $this->getMediaTypeByID($mediatype_id);
				if (!$tempObj instanceof mediaTypeObj) {
					return false;
				}

				if ($tempObj->getChildrenCount() > 0) {
					throw new Exception('Cannot delete media type with active subcategories,');
				}

				if ($this->SQL->getCountByMediaType($mediatype_id) > 0) {
					throw new Exception('Media type in use.  Cannot delete,');
				}

				$this->SQL->deleteMediaType($mediatype_id);
				$this->updateMediaCache();
				return true;
			}

			return false;
		} catch (Exception $e) {
			VCDException::display($e);
			return false;
		}
	}


	/**
	 * Update a mediaTypeObj
	 *
	 * @param mediaTypeObj $mediaTypeObj
	 */
	public function updateMediaType($mediaTypeObj) {
		try {
			if ($mediaTypeObj instanceof mediaTypeObj) {
				$this->SQL->updateMediaType($mediaTypeObj);
			}
		} catch (Exception $e) {
			VCDException::display($e);
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
				VCDException::display("CD with id " . $vcd_id ." has no assigned media types");
			}



		} catch (Exception $e) {
			VCDException::display($e);
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
			if (is_numeric($user_id)) {

				$media_array = $this->SQL->getMediaTypesInUseByUserID($user_id);
				$i = 0;
				foreach ($media_array as $itemArray) {
					$catObj = $this->getMediaTypeByID($itemArray[0]);
					$media_array[$i++][1] = $catObj->getDetailedName();
				}

				asort($media_array);
				return aSortBySecondIndex($media_array,1);


			} else {
				throw new Exception('Parameter must be numeric');
			}

		} catch (Exception $e) {
			VCDException::display($e);
			return null;
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

			if (is_numeric($user_id) && is_numeric($category_id)) {
				return $this->SQL->getMediaCountByCategoryAndUserID($user_id, $category_id);
			} else {
				throw new Exception('Parameters must be numeric');
			}

		} catch (Exception $e) {
			VCDException::display($e);
		}
	}

	/**
	 * Get a mediatype by name
	 *
	 * @param string $media_name
	 * @return media type object
	 */
	public function getMediaTypeByName($media_name) {
		try {
			foreach ($this->getAllMediaTypesFull() as $movieMediaTypeObj) {
				$thisname = $movieMediaTypeObj->getName();
				if (strcmp(strtolower(substr($media_name, -strlen($thisname))), strtolower($thisname)) == 0) {
					return $movieMediaTypeObj;
				}
			}
			return null;

		} catch (Exception $e) {
			VCDException::display($e);
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
			if (is_null($this->moviecategoryArray))
				$this->updateCategoryCache();

			return $this->moviecategoryArray;

		} catch (Exception $e) {
			VCDException::display($e);
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
		} catch (Exception $e) {
			VCDException::display($e);
			return null;
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

			if (is_numeric($user_id)) {
				return $this->SQL->getCategoriesInUseByUserID($user_id);
			} else {
				throw new Exception('Parameter must be numeric');
			}

		} catch (Exception $e) {
			VCDException::display($e);
			return null;
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
		} catch (Exception $e) {
			VCDException::display($e);
		}
	}

	/**
	 * Save a new movieCategoyObj to database
	 *
	 * @param movieCategoyObj $movieCategoryObj
	 */
	public function addMovieCategory($movieCategoryObj) {
		try {
			if ($movieCategoryObj instanceof movieCategoryObj) {
				$this->SQL->addMovieCategory($movieCategoryObj);
			} else {
				throw new Exception('movieCategoryObj expected');
			}

		} catch (Exception $e) {
			VCDException::display($e);
		}
	}


	/**
	 * Delete a movieCategoryObj from database
	 *
	 * @param int $category_id
	 */
	public function deleteMovieCategory($category_id) {
		try {
			if (is_numeric($category_id)) {

				// check if category is in use ..
				foreach ($this->getMovieCategoriesInUse() as $obj) {
					if ($obj->getID() == $category_id) {
						throw new Exception("Cannot delete category that is in use.");
					}
				}

				$this->SQL->deleteMovieCategory($category_id);
				$this->updateCategoryCache();
			}

		} catch (Exception $e) {
			VCDException::display($e);
		}
	}

	/**
	 * Update an instance of movieCategoryObj in database
	 *
	 * @param movieCategoryObj $movieCategoryObj
	 */
	public function updateMovieCategory($movieCategoryObj) {
		try {
			if ($movieCategoryObj instanceof movieCategoryObj) {
				$this->SQL->updateMovieCategory($movieCategoryObj);
			}

		} catch (Exception $e) {
			VCDException::display($e);
		}
	}


	/**
	 * Get a moviecategory_id by name
	 *
	 * @param string $category_name
	 * @return int
	 */
	public function getCategoryIDByName($category_name) {
		try {
			foreach ($this->getAllMovieCategories() as $movieCategoryObj) {
				if (strcmp(strtolower($category_name), strtolower($movieCategoryObj->getName())) == 0) {
					return $movieCategoryObj->getID();
				}
			}
			return 0;

		} catch (Exception $e) {
			VCDException::display($e);
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

			if (!is_numeric($borrower_id)) {
				throw new Exception("Invalid borrower_id");
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
			throw new Exception("Borrower nr. " . $borrower_id . " does not exist.");


		} catch (Exception $e) {
			VCDException::display($e);
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

		} catch (Exception $e) {
			VCDException::display($e);
		}
	}

	/**
	 * Add a new borrower object to database
	 *
	 * @param borrowerObj $borrowerObj
	 */
	public function addBorrower($borrowerObj) {
		try {
			if ($borrowerObj instanceof borrowerObj) {
				$this->SQL->addBorrower($borrowerObj);
			} else {
				throw new Exception("object must be a instance of borrowerObj");
			}

		} catch (Exception $e) {
			VCDException::display($e);
		}
	}


	/**
	 * Update borrowe object in database
	 *
	 * @param borrowerObj $borrowerObj
	 */
	public function updateBorrower($borrowerObj) {
		try {
			if ($borrowerObj instanceof borrowerObj) {
				$this->SQL->updateBorrower($borrowerObj);
			} else {
				throw new Exception("object must be a instance of borrowerObj");
			}

		} catch (Exception $e) {
			VCDException::display($e);
		}
	}


	/**
	 * Delete a borrower from database and all related records to him.
	 *
	 * @param borrowerObj $borrowerObj
	 */
	public function deleteBorrower($borrowerObj) {
		try {

			if ($borrowerObj instanceof borrowerObj) {

				// Check if user is allowd to delete this borrowerObj
				$user_id = VCDUtils::getUserID();
				if ($borrowerObj->getOwnerID() != $user_id) {
					throw new Exception("You have no permission to delete borrower " . $borrowerObj->getName());
				}

				// Delete the borrower loan history records
				$this->deleteLoanRecords($borrowerObj->getID());
				$this->SQL->deleteBorrower($borrowerObj->getID());


			} else {
				throw new Exception("object must be a instance of borrowerObj");
			}


		} catch (Exception $e) {
			VCDException::display($e);
		}
	}


	/*
		Loan system functions
	*/

	/**
	 * Loan movies to borrower.
	 *
	 * Param $arrMovieIDs must contain  array of movie ID's.
	 * Returns true on success otherwise false.
	 *
	 * @param int $borrower_id
	 * @param array $arrMovieIDs
	 * @return bool
	 */
	public function loanCDs($borrower_id, $arrMovieIDs) {
		try {
			if (!is_array($arrMovieIDs)) {
				VCDException::display("Movie IDs must be entered as an array");
				return false;
			} else {
				foreach ($arrMovieIDs as $cd_id) {
					$this->SQL->loanCDs(VCDUtils::getUserID(), $borrower_id, $cd_id);
				}
				return true;
			}

		} catch (Exception $e) {
			VCDException::display($e);
		}
	}

	/**
	 * Return a movie from loan
	 *
	 * Returns true on success otherwise false.
	 *
	 * @param int $loan_id
	 * @return bool
	 */
	public function loanReturn($loan_id) {
		try {
			if (is_numeric($loan_id)) {
				$this->SQL->loanReturn($loan_id);
				return true;
			} else {
				VCDException::display("Loan id must be numeric");
				return false;
			}
		} catch (Exception $e) {
			VCDException::display($e);
		}
	}

	/**
	 * Get all loans by specified user.
	 *
	 * Param $show_returned specifies if only movies currently in loan
	 * should be returned or all movies ever to be loaned.
	 * Returns an array of loan objects.
	 *
	 * @param int $user_id
	 * @param bool $show_returned
	 * @return array
	 */
	public function getLoans($user_id, $show_returned) {
		try {
			if (is_numeric($user_id)) {

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


			} else {
				throw new Exception("UserID must be valid");
			}

		} catch (Exception $e) {
			VCDException::display($e);
		}

	}


	/**
	 * Delete all loan records by borrower id
	 *
	 * @param int $borrower_id
	 */
	private function deleteLoanRecords($borrower_id) {
		try {

			if (is_numeric($borrower_id)) {
				$this->SQL->deleteLoanRecords($borrower_id);
			}

		} catch (Exception $e) {
			VCDException::display($e);
		}
	}


	/**
	 * Get all loans by specified borrower id.
	 *
	 * $show_returned can specify weither to show all movies ever loaned
	 * to that borrower or only the movies that he currently has in loan.
	 * Returns an array of loan objects.
	 *
	 * @param int $user_id
	 * @param int $borrower_id
	 * @param bool $show_returned
	 * @return array
	 */
	public function getLoansByBorrowerID($user_id, $borrower_id, $show_returned = false) {
		try {

			if (!is_numeric($user_id) || !is_numeric($borrower_id)) {
				throw new Exception("Wrong parameters");
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


		} catch (Exception $e) {
			VCDException::display($e);
		}

	}


	/* Notification */

	/**
	 * Send email notifycation.
	 *
	 * Send out email notification to all users that are wathing for new movies to be inserted in the database.
	 * Returns true on successful email delivery otherwise false.
	 *
	 * @param vcdObj $vcdObj
	 * @return bool
	 */
	public function notifyOfNewEntry($vcdObj) {
		try {

			// First, find all the users that want to be notified
			$USERClass = VCDClassFactory::getInstance("vcd_user");

			$notifyPropObj = $USERClass->getPropertyByKey('NOTIFY');
			if (!$notifyPropObj instanceof userPropertiesObj) {
				VCDException::display("Property NOTIFY was not found.<break>Cant send notifications");
				return false;
			}

			$notifyUsers = $USERClass->getAllUsersWithProperty($notifyPropObj->getpropertyID());

			if (is_array($notifyUsers) && sizeof($notifyUsers) > 0) {
				$arrEmails = array();
				foreach ($notifyUsers as $userObj) {
					array_push($arrEmails, $userObj->getEmail());
				}
				unset($notifyUsers);

				$body = createNotifyEmailBody($vcdObj);

				VCDUtils::sendMail($arrEmails, 'New entry in the VCD DB', $body, true);

			}



		} catch (Exception $e) {
			VCDException::display($e);
		}
	}


	/*  Rss Feeds */
	/**
	 * Add a new feed to database.
	 *
	 * user_id should be 0 for Global Feeds.
	 *
	 * @param int $user_id
	 * @param string $feed_name
	 * @param string $feed_url
	 */
	public function addRssfeed($user_id, $feed_name, $feed_url) {
		try {
			if (is_numeric($user_id)) {
				$this->SQL->addRssfeed($user_id, $feed_name, $feed_url);
			} else {
				throw new Exception('Invalid user id');
			}
		} catch (Exception $e) {
			VCDException::display($e);
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
				throw new Exception("Parameter must be numeric");
			}

			return $this->SQL->getRSSfeedByID($feed_id);


		} catch (Exception $e) {
			VCDException::display($e);
			return null;
		}
	}

	/**
	 * Update RSS feed entry in the database.
	 *
	 * @param int $feed_id
	 * @param string $feed_name
	 * @param string $feed_url
	 */
	public function updateRssfeed($feed_id, $feed_name, $feed_url) {
		try {
			if (is_numeric($feed_id)) {
				$this->SQL->updateRssfeed($feed_id, $feed_name, $feed_url);
			} else {
				throw new Exception("Parameter feed_id must be numeric");
			}

		} catch (Exception $e) {
			VCDException::display($e);
		}
	}

	/**
	 * Get all RSS feeds by specified user ID.
	 *
	 * Returns an array containing all RSS feeds
	 *
	 * @param int $user_id
	 * @return array
	 */
	public function getRssFeedsByUserId($user_id) {
		try {
			return $this->SQL->getRssFeedsByUserId($user_id);
		} catch (Exception $e) {
			VCDException::display($e);
		}
	}

	/**
	 * Delete specified RSS feed from database
	 *
	 * @param int $feed_id
	 */
	public function delFeed($feed_id) {
		try {
			if (is_numeric($feed_id)) {
				$this->SQL->delFeed($feed_id);
			} else {
				VCDException::display('Invalid Feed ID');
			}

		} catch (Exception $e) {
			VCDException::display($e);
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
			if (is_numeric($user_id) && is_numeric($vcd_id))	 {
				$this->SQL->addToWishList($vcd_id, $user_id);
			} else {
				VCDException::display("Parameters must be numeric");
			}

		} catch (Exception $e) {
			VCDException::display($e);
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
			if (is_numeric($user_id))	 {
				$wishlistArr = $this->SQL->getWishList($user_id);
				if (isset($_SESSION['user']) && VCDUtils::getUserID() != $user_id) {
					// User is view-ing others wishlist, lets check if user owns movies from this wishlist
					$ArrVCDids = $this->SQL->getVCDIDsByUser(VCDUtils::getUserID());
					if (is_array($ArrVCDids) && sizeof($ArrVCDids) > 0) {
						// Loop through the list
						$comparedArr = array();
						if (sizeof($wishlistArr) > 0) {
							foreach ($wishlistArr as $item) {
								$iown = 0;
								if (in_array($item[0], $ArrVCDids)) {
									$iown = 1;
								}
								array_push($comparedArr, array('id' => $item[0], 'title' => $item[1], 'mine' => $iown));
							}
						}
						unset($wishlistArr);
						unset($ArrVCDids);
						return $comparedArr;
					}

				} else {
					return $wishlistArr;
				}

			} else {
				throw new Exception("Parameters must be numeric");
			}

		} catch (Exception $e) {
			VCDException::display($e);
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
				throw new Exception("ID for wishlist must be numeric");
			}

			$user_id = VCDUtils::getUserID();
			return $this->SQL->isOnWishList($vcd_id, $user_id);

		} catch (Exception $e) {
			VCDException::display($e);
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
			if (is_numeric($user_id) && is_numeric($vcd_id))	 {
				$this->SQL->removeFromWishList($vcd_id, $user_id);
			} else {
				throw new Exception("Parameters must be numeric");
			}

		} catch (Exception $e) {
			VCDException::display($e);
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

				$USERClass = VCDClassFactory::getInstance('vcd_user');
				$propObj = $USERClass->getPropertyByKey(vcd_user::$PROPERTY_WISHLIST);
				if ($propObj instanceof userPropertiesObj ) {
					$propCount = (int)sizeof($USERClass->getAllUsersWithProperty($propObj->getpropertyID()));
					if ($propCount == 0) {
						return false;
					} elseif ($_SESSION['user']->getPropertyByKey(vcd_user::$PROPERTY_WISHLIST) && ($propCount == 1)) {
						return false;
					} elseif (!$_SESSION['user']->getPropertyByKey(vcd_user::$PROPERTY_WISHLIST) && ($propCount >= 1)) {
						return true;
					} else {
						return true;
					}
				}


			}
			return false;

		} catch (Exception $e) {
			VCDException::display($e);
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

		} catch (Exception $e) {
			VCDException::display($e);
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
				throw new Exception('Comment ID must be numeric');
			}

			$this->SQL->deleteComment($comment_id);

		} catch (Exception $e) {
			VCDException::display($e);
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
				throw new Exception('Comment ID must be numeric');
			}

			return $this->SQL->getCommentByID($comment_id);

		} catch (Exception $e) {
			VCDException::display($e);
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
				throw new Exception('User ID must be numeric');
			}

			return $this->SQL->getAllCommentsByUserID($user_id);

		} catch (Exception $e) {
			VCDException::display($e);
		}
	}

	/**
	 * Get all comments for specified movie.
	 *
	 * Returns array of commentObj.
	 *
	 * @param int $vcd_id
	 * @return array
	 */
	public function getAllCommentsByVCD($vcd_id) {
		try {

			if (!is_numeric($vcd_id)) {
				throw new Exception('VCD ID must be numeric');
			}

			return $this->SQL->getAllCommentsByVCD($vcd_id);

		} catch (Exception $e) {
			VCDException::display($e);
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
			$maxRecords = 5;
			$arrAllCats = $obj->getBiggestCats();
			$arrMonCats = $obj->getBiggestMonhtlyCats();
			$obj->resetCategories();

			$counter = 0;
			$arrMonCatObjs = array();
			foreach ($arrMonCats as $item) {
				if ($counter >= $maxRecords) { break; }
				$cObj = $this->getMovieCategoryByID($item[0]);
				$currObj = new movieCategoryObj(array($cObj->getID(), $cObj->getName()));
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



		} catch (Exception $e) {
			VCDException::display($e);
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

			if (is_numeric($user_id)) {
				return $this->SQL->getUserStatistics($user_id);
			} else {
				throw new Exception("Missing parameter user_id");
			}


		} catch (Exception $e) {
			VCDException::display($e);
		}

	}



	/* Metadata objects */

	/**
	 * Add metadata to database.
	 *
	 * param $arrObj can either be metadataObj or an
	 * array of metadata objects.  If metadata object with same
	 * record_id, user_id and metadata name exists already, that
	 * entry is updated instead of inserting duplicate record.
	 *
	 * @param mixed $arrObj
	 * @param bool $forceCheck | Force to check the mediatypeID field also.
	 */
	public function addMetadata($arrObj, $forceCheck = false) {
	 	try {

	 		
	 		if ($forceCheck) {
	 			if (is_array($arrObj)) {
	 				foreach ($arrObj as $metaObj) {
	 					$this->addMetadata($metaObj, true);
	 				}
	 			} else {
	 				
	 				if (!$arrObj instanceof metadataObj ) {
	 					throw new Exception('Excepted metadata object.');
	 				}
	 				
	 				$oldArr = $this->getMetadata($arrObj->getRecordID(), $arrObj->getUserID(), $arrObj->getMetadataName(), $arrObj->getmediaTypeID());
	 				$oldObj = null;
	 				if (is_array($oldArr) && sizeof($oldArr) == 1) {
	 					$oldObj = $oldArr[0];
	 					$arrObj->setMetadataID($oldObj->getMetadataID());
	 					$this->updateMetadata($arrObj);
	 				} else {
	 					$this->SQL->addMetadata($arrObj);
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

	 				$this->SQL->addMetadata($arrObj);
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
			 				$this->SQL->addMetadata($metaObj);
			 			}
	 				}
	 			}
	 		}

	 	} catch (Exception $e) {
	 		VCDException::display($e);
	 	}
	}


	/**
	 * Update metadata object
	 *
	 * @param metadataObj $obj
	 */
	public function updateMetadata($obj) {
		try {

			if ($obj instanceof metadataObj ) {
				$this->SQL->updateMetadata($obj);
			}

	 	} catch (Exception $e) {
	 		VCDException::display($e);
	 	}
	}

	/**
	 * Delete metadata object
	 *
	 * @param int $metadata_id
	 */
	public function deleteMetadata($metadata_id) {
		try {
	 		if (is_numeric($metadata_id)) {
	 			$this->SQL->deleteMetadata($metadata_id);
	 		} else {
	 			throw new Exception('metadata_id must be numeric');
	 		}

	 	} catch (Exception $e) {
	 		VCDException::display($e);
	 	}
	}


	/**
	 * Get specific metadata.
	 *
	 * If an entry with specific parameters does not exists,
	 * null is returned.  Otherwise array of metadata is returned
	 *
	 * @param int $record_id
	 * @param int $user_id
	 * @param int $metadata_name
	 * @param int $mediatype_id | MediaType ID of movieObj.  This forces deeper check.
	 * @return array
	 */
	public function getMetadata($record_id, $user_id, $metadata_name, $mediatype_id = null) {
		try {
	 		if (is_numeric($record_id) && is_numeric($user_id)) {

	 			// reverse metadataObj SYS constant to correct string
	 			if (is_numeric($metadata_name)) {
	 				$mappingName = metadataTypeObj::getSystemTypeMapping($metadata_name);
	 				if ($mappingName) {
	 					return $this->SQL->getMetadata($record_id, $user_id, $mappingName);
	 				} else {
	 					throw new Exception('System mapping for metadataType not found.');
	 				}

	 			} else {
	 				return $this->SQL->getMetadata($record_id, $user_id, $metadata_name, $mediatype_id);
	 			}



	 		} else {
	 			return null;
	 		}

	 	} catch (Exception $e) {
	 		VCDException::display($e);
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

		} catch (Exception $e) {
			VCDException::display($e);
		}
	}



	/**
	 * Add a new metadataTypeObj to the database.
	 * The updated metadataTypeObj is then returned.
	 *
	 * @param metadataTypeObj $obj
	 * @return metadataTypeObj
	 */
	public function addMetaDataType(metadataTypeObj $obj) {
		try {

			return $this->SQL->addMetaDataType($obj);

		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}

	/**
	 * Get all known metadatatypes from database.
	 * Id $user_id is provided, only metadatatypes created by that
	 * user_id will be returned.
	 * Function returns array of metadataTypeObjects.
	 *
	 * @param int_type $user_id | The user_id to filter metadatatypes to, null = no filter
	 * @return array
	 */
	public function getMetadataTypes($user_id = null) {
		try {

			return $this->SQL->getMetadataTypes($user_id);

		} catch (Exception $ex) {
			VCDException::display($ex);
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
				// Check if the user trying to delete the object is actuallt the owner of the metadataType.
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
					throw new Exception('You cannot delete metadataType that you did not create.');
				}
				
			}
			
 		} catch (Exception $ex) {
 			VCDException::display($ex);
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
		} catch (Exception $e) {
			VCDException::display($e);
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
		} catch (Exception $e) {
			VCDException::display($e);
		}

	}

	/**
	 * Update the internal sourceSite obj cache.
	 *
	 */
	private function updateSiteCache() {
		if (is_null($this->sourcesiteArray))
			$this->sourcesiteArray = $this->SQL->getSourceSites();

	}

	/**
	 * Update the internal Borrowers object cache.
	 *
	 * @param int $user_id
	 */
	private function updateBorrowersCache($user_id) {
		if (is_null($this->borrowersArray))
			$this->borrowersArray = $this->SQL->getBorrowersByUserID($user_id);
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



}




?>