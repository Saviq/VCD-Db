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
 * @package Vcd
 * @version $Id$
 */

?>
<?

require_once("cdObj.php");
require_once("vcdObj.php");
require_once("imdbObj.php");

class vcd_movie implements Vcd  {

	/**
	 *
	 * @var vcdSQL
	 */
	private $SQL;

   public function __construct() {
	 	$this->SQL = new vcdSQL();
   }



	/**
	 * Get vcd object by ID
	 *
	 * @param int $vcd_id
	 * @return vcdObj
	 */
	public function getVcdByID($vcd_id) {

		try {
			if (is_numeric($vcd_id)) {

				// Get the basic CD object with source site attached if any
				$obj = $this->SQL->getVcdByID($vcd_id);
				if ($obj instanceof vcdObj) {

					// Get the covers for the CD
					$CLASScovers = VCDClassFactory::getInstance("vcd_cdcover");
					$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");

					$coverArr = $CLASScovers->getAllCoversForVcd($vcd_id);

					if (sizeof($coverArr) > 0) {
						$obj->addCovers($coverArr);
					}

					// Set the movie categoryObj
					$obj->setMovieCategory($SETTINGSClass->getMovieCategoryByID($obj->getCategoryID()));

					// Get IMDB info for regular movies and TV shows
					// or adult information for blue movies

					if ($obj->getCategoryID() == $SETTINGSClass->getCategoryIDByName("adult")) {
						// Blue Movie
						$PORNClass = VCDClassFactory::getInstance("vcd_pornstar");
						$arrPornstars = $PORNClass->getPornstarsByMovieID($vcd_id);
						$obj->addPornstars($arrPornstars);
						$sObj = $PORNClass->getStudioByMovieID($vcd_id);

						if ($sObj instanceof studioObj ) {
							$obj->setStudioID($sObj->getID());
							unset($sObj);
						}



					} else {
						// Normal flick


						$imdb = $this->getIMDBinfo($vcd_id);
						if ($imdb instanceof imdbObj) {

							$obj->setIMDB($imdb);
						}
					}

					return $obj;


				} else {
					return false;
				}

			} else {
				throw new Exception("Numeric ID required");
			}

		} catch (Exception $e) {
			VCDException::display($e);
		}
	}


	/**
	 * Get all vcd objects as an array by user ID
	 *
	 * Param $simple set to true populates minimal data to the vcd objects
	 * otherwise all information for each vcd object is populated.
	 *
	 * @param int $user_id
	 * @param bool $simple
	 * @return array
	 */
	public function getAllVcdByUserId($user_id, $simple = true) {



		try {
			if (is_numeric($user_id)) {

				if ($simple) {
					$arr = $this->SQL->getAllVcdByUserIdSimple($user_id);
				} else {
					$arr = $this->SQL->getAllVcdByUserId($user_id);
				}


				$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");
				$PSClass = VCDClassFactory::getInstance("vcd_pornstar");

				$returnArr = array();

				foreach ($arr as $obj) {

					// Set the movie categoryObj
					$obj->setMovieCategory($SETTINGSClass->getMovieCategoryByID($obj->getCategoryID()));



					if (!$simple) {
						// Get IMDB info for regular movies and TV shows
						// or adult information for blue movies

						if ($obj->getCategoryID() == $SETTINGSClass->getCategoryIDByName("adult")) {
							// Blue Movie

							$arrPornstars = $PSClass->getPornstarsByMovieID($obj->getID());
							$obj->addPornstars($arrPornstars);

							$studioObj = $PSClass->getStudioByMovieID($obj->getID());
							if ($studioObj instanceof studioObj ) {
								$obj->setStudioID($studioObj->getID());
							}

							// Get the adult categories
							$arrPornCats = $PSClass->getSubCategoriesByMovieID($obj->getID());
							if (sizeof($arrPornCats) > 0) {
								foreach ($arrPornCats as $pornCat) {
									$obj->addAdultCategory($pornCat);
								}
							}
							unset($arrPornCats);


						} else {
							// Normal flick
							$imdb = $this->getIMDBinfo($obj->getID());
							if ($imdb instanceof imdbObj) {
								$obj->setIMDB($imdb);
							}
						}
					}


					array_push($returnArr, $obj);
				}

				unset($arr);
				return $returnArr;



			} else {
				throw new Exception("Parameter user_id invalid");
			}


		} catch (Exception $e) {
			VCDException::display($e);
		}

	}



	/**
	 * Get array of latest vcd objects that have been added to database by user ID.
	 *
	 * Param $count represents how many records should be fetched, $simple is
	 * used for populating the vcd object to minimal or full data.
	 *
	 * @param int $user_id
	 * @param int $count
	 * @param bool $simple
	 * @return array
	 */
	public function getLatestVcdsByUserID($user_id, $count, $simple = true) {


		try {

			if (is_numeric($user_id) && is_numeric($count)) {

				if ($simple) {
					$arr = $this->SQL->getAllVcdByUserIdSimple($user_id, $count);
				} else {
					$arr = $this->SQL->getAllVcdByUserId($user_id, $count);
				}



				$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");
				$PSClass = VCDClassFactory::getInstance("vcd_pornstar");

				$returnArr = array();
				foreach ($arr as $obj) {
					// Set the movie categoryObj
					$obj->setMovieCategory($SETTINGSClass->getMovieCategoryByID($obj->getCategoryID()));


					if (!$simple) {
						// Get IMDB info for regular movies and TV shows
						// or adult information for blue movies

						if ($obj->getCategoryID() == $SETTINGSClass->getCategoryIDByName("adult")) {
							// Blue Movie

							$arrPornstars = $PSClass->getPornstarsByMovieID($obj->getID());
							$obj->addPornstars($arrPornstars);


						} else {
							// Normal flick
							$imdb = $this->getIMDBinfo($obj->getID());
							if ($imdb instanceof imdbObj) {
								$obj->setIMDB($imdb);
							}
						}
					}


					array_push($returnArr, $obj);
				}

				unset($arr);
				return $returnArr;

			} else {
				throw new Exception("Wrong parameters");
			}

		} catch (Exception $e) {
			VCDException::display($e);
		}
	}


	/**
	 * Add a vcd object to database.
	 *
	 * On success the newly created vcd objects id is returned, otherwise -1
	 * Or an Exception will be thrown
	 *
	 * @param vcdObj $vcdObj
	 * @return int
	 */
	public function addVcd(vcdObj $vcdObj) {
		$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");

		try {

			$cd_id = -1;

			// First of all .. check if movie exists in DB (exactly same title & year)
			$exisisting_id = $this->SQL->checkEntry($vcdObj->getTitle(), $vcdObj->getYear());
			if (!$exisisting_id) {
				// All ok .. CD does not exist .., insert the new CD to DB
				$cd_id = $this->SQL->addVCD($vcdObj);

				// Ooops we have a db underneath, that does not return last inserted ID, will try to use adodb->genID
				if ($cd_id == -1) {
					throw new Exception('You seem to using ancient DB<break>Please post a message to the VCD DB homepage for further info');
				}


				// Associate the new ID to the vcdObj
				$vcdObj->setID($cd_id);

				// Add an owner instance to the newly created CD
				if (!$this->SQL->addVcdInstance($vcdObj)) {
					throw new Exception('Could not add instance to user');
				}


				// Create temporary unique ID for the image
				$image_name = VCDUtils::generateUniqueId();

				// Add the IMDB information to the IMDB table
				if ($vcdObj->getIMDB() instanceof imdbObj) {
					$image_name = $vcdObj->getIMDB()->getIMDB();

					// Just to be sure .. check for existing IMDB entry
					if ($this->SQL->checkIMDBDuplicate($vcdObj->getIMDB()->getIMDB()) == 0) {
						if (!$this->SQL->addIMDBInfo($vcdObj->getIMDB())) {
							throw new Exception('Failed to add to IMDB table');
						}
					}
				}


				// Add to movie to the source site linked table
				if (is_numeric($vcdObj->getSourceSiteID()) && strlen($vcdObj->getExternalID()) > 0) {
					if (!$this->SQL->addVcdToSourceSite($vcdObj)) {
						throw new Exception('Failed to link movie to source site table');
					}
				}


				/*
					Process The thumbnail
					Check if images should be stored in DB or on HD.
					First, check if any thumbnail was added
				*/

				$thumbnail = $vcdObj->getCover("thumbnail");
				if ($thumbnail instanceof cdcoverObj ) {
					// Ok thumbnail was added .. lets save the image


					$imgToDB = (bool)$SETTINGSClass->getSettingsByKey('DB_COVERS');

					$thumbnail->setOwnerId($vcdObj->getInsertValueUserID());
					$thumbnail->setVcdId($cd_id);

					$filename = TEMP_FOLDER.$thumbnail->getFilename();
					$newname = $image_name . "." . VCDUtils::getFileExtension($thumbnail->getFilename());

					if ($imgToDB) {	// Insert the image as a binary file to DB, it's still in the temp folder

						$vcd_image = new VCDImage();
						if (VCDUtils::getFileExtension($thumbnail->getFilename()) == 'gif') {
							$image_type = "gif";
						} else {
							$image_type = "pjpeg";
						}

						// Use File info
						$arrFileInfo = array("name" => "".$newname."", "type" => "image/".$image_type."");

						$image_id = $vcd_image->addImageFromPath(TEMP_FOLDER.$thumbnail->getFilename(), $arrFileInfo, true);
						$thumbnail->setFilesize($vcd_image->getFilesize());
						// Set the DB imageID to the cover
						$thumbnail->setImageID($image_id);
						$thumbnail->setFilename($newname);


					} else {


						// rename the image and move it to the thumbnail upload folder
						if (fs_file_exists($filename)) {

							$thumbnail->setFilesize(fs_filesize($filename));
							fs_rename($filename, THUMBNAIL_PATH . $newname);
							$thumbnail->setFilename($newname);

						} else {
							throw new Exception("Trying to move an image that does not exist!");
						}

					}


					// Finally add the CDCover Obj to the DB
					$vcdCover = VCDClassFactory::getInstance("vcd_cdcover");
					$vcdCover->addCover($thumbnail);


				}



				/* If we this is an Adult film, call for special treatment */
				if ($vcdObj->isAdult()) {
					$this->handleAdultVcd($vcdObj);
				}





			} else {
				// CD exist, we only have to add our instance in the db

				// Set the existing ID as the VCD ID
				$vcdObj->setID($exisisting_id);

				// Has the user submitted a duplicate entry ?
				// in other words .. does this exact copy already belong to him ?
				if ($this->checkDuplicateEntry($vcdObj->getInsertValueUserID(),$exisisting_id, $vcdObj->getInsertValueMediaTypeID())) {
					// Return from function, nothing more to do ..
					return;
				}

				if (!$this->SQL->addVcdInstance($vcdObj)) {
					throw new Exception("Could not add instance to user.");
				}

				$cd_id = $exisisting_id;

			}


			// Check if people wan't to be notified of the new entry
			$SETTINGSClass->notifyOfNewEntry($vcdObj);


			return $cd_id;



		} catch (Exception $e) {
			VCDException::display($e);
		}
	}


	/**
	 * Add vcd to user from CD vcd that already exists in the database.
	 *
	 * @param int $user_id
	 * @param int $vcd_id
	 * @param int $mediatype
	 * @param int $cds
	 */
	public function addVcdToUser($user_id, $vcd_id, $mediatype, $cds) {
		try {

			if (is_numeric($user_id) && is_numeric($vcd_id) && is_numeric($mediatype) && is_numeric($cds)) {


				// Has the user submitted a duplicate entry ?
				// in other words .. does this exact copy already belong to him ?
				if ($this->checkDuplicateEntry($user_id, $vcd_id, $mediatype)) {
					// Return from function, nothing more to do ..
					return;
				}


				$this->SQL->addVcdToUser($user_id, $vcd_id, $mediatype, $cds);

			} else {
				throw new Exception('Invalid parameters');
			}

		} catch (Exception $e) {
			VCDException::display($e);
		}
	}

	/**
	 * Internal function to process adult vcd objects
	 *
	 * @param vcdObj $vcdObj
	 */
	private function handleAdultVcd(vcdObj $vcdObj) {

		try {

			$PORNClass = VCDClassFactory::getInstance("vcd_pornstar");

			// Link the pornstars to the movie
			if ($vcdObj->getID() > 0) {
				foreach ($vcdObj->getPornstars() as $pornstarObj) {
					$PORNClass->addPornstarToMovie($pornstarObj->getID(), $vcdObj->getID());
				}
			} else {
				VCDException::display("VCD ID must be set before linking stars to Movie");
			}

			// Link movie to adult categories
			foreach ($vcdObj->getAdultCategories() as $adultCatObj) {
				$PORNClass->addCategoryToMovie($vcdObj->getID(), $adultCatObj->getID());
			}

			// Link to movie to the assigned adult studio
			if ($vcdObj->getStudioID() > 0) {
				$PORNClass->addMovieToStudio($vcdObj->getStudioID(),$vcdObj->getID());
			}


			$cd_id = $vcdObj->getID();

			// Add all extra covers to the movie
			$vcdCover = VCDClassFactory::getInstance("vcd_cdcover");
			$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");
			foreach ($vcdObj->getCovers() as $cdcoverObj) {
				if ($cdcoverObj instanceof cdcoverObj && !$cdcoverObj->isThumbnail()) {


					// Create temporary unique ID for the image
					$image_name = VCDUtils::generateUniqueId();

					$imgToDB = (bool)$SETTINGSClass->getSettingsByKey('DB_COVERS');
					$cdcoverObj->setOwnerId($vcdObj->getInsertValueUserID());
					$cdcoverObj->setVcdId($cd_id);

					$filename = TEMP_FOLDER.$cdcoverObj->getFilename();
					$newname = $image_name . "." . VCDUtils::getFileExtension($cdcoverObj->getFilename());

					if ($imgToDB) {	// Insert the image as a binary file to DB, it's still in the temp folder

						$vcd_image = new VCDImage();
						if (VCDUtils::getFileExtension($cdcoverObj->getFilename()) == 'gif') {
							$image_type = "gif";
						} else {
							$image_type = "pjpeg";
						}

						// Use File info
						$arrFileInfo = array("name" => "".$newname."", "type" => "image/".$image_type."");

						$image_id = $vcd_image->addImageFromPath(TEMP_FOLDER.$cdcoverObj->getFilename(), $arrFileInfo, true);
						$cdcoverObj->setFilesize($vcd_image->getFilesize());
						// Set the DB imageID to the cover
						$cdcoverObj->setImageID($image_id);
						$cdcoverObj->setFilename($newname);


					} else {


						// rename the image and move it to the thumbnail upload folder
						if (fs_file_exists($filename)) {

							$cdcoverObj->setFilesize(fs_filesize($filename));
							fs_rename($filename, COVER_PATH . $newname);
							$cdcoverObj->setFilename($newname);

						} else {
							VCDException::display('Trying to move an image that does not exist!');
						}

					}


						// Finally add the CDCover Obj to the DB
						$vcdCover->addCover($cdcoverObj);
					}
			}

		} catch (Exception $e) {
			VCDException::display($e);
		}

	}


	/**
	 * Update an instance of vcd object in database.
	 *
	 * @param vcdObj $vcdObj
	 */
	public function updateVcd(vcdObj $vcdObj) {

		try {

			// Update the basics ..
			$this->SQL->updateBasicVcdInfo($vcdObj);
			$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");

			if ((bool)$vcdObj->isAdult()) {
				// Blue movie
				$PORNClass = VCDClassFactory::getInstance('vcd_pornstar');

				// update the adult categories
				$PORNClass->deleteMovieFromCategories($vcdObj->getID());
				foreach ($vcdObj->getAdultCategories() as $adultCatObj) {
					$PORNClass->addCategoryToMovie($vcdObj->getID(), $adultCatObj->getID());
				}

				$PORNClass->deleteMovieFromStudio($vcdObj->getID());
				$PORNClass->addMovieToStudio($vcdObj->getStudioID(), $vcdObj->getID());



			} else {
				// Normal flick


				// Check for the IMDB obj if any
				if ($vcdObj->getIMDB() instanceof imdbObj ) {

					if (is_numeric($vcdObj->getSourceSiteID()) && $vcdObj->getSourceSiteID() > 0) {
						$this->SQL->updateIMDBInfo($vcdObj->getIMDB());

					} else {

						// New IMDB Obj - Lets write it to DB
						// Set the source site


						$sourceSiteObj = $SETTINGSClass->getSourceSiteByAlias('imdb');
						$imdbObj = $vcdObj->getIMDB();
						$imdbObj->setYear($vcdObj->getYear());
						if ($sourceSiteObj instanceof sourceSiteObj ) {
							$vcdObj->setSourceSite($sourceSiteObj->getsiteID(), $imdbObj->getIMDB());
							if (!$this->SQL->addIMDBInfo($imdbObj)) {
								throw new Exception('Failed to add to IMDB table');
							}
							// Add to movie to the source site linked table
							if (is_numeric($vcdObj->getSourceSiteID()) && strlen($vcdObj->getExternalID()) > 0) {
								if (!$this->SQL->addVcdToSourceSite($vcdObj)) {
									throw new Exception('Failed to link movie to source site table');
								}
							}
						}
					}
				}
			}


			// Check where covers should be stored ..
			$COVERClass    = VCDClassFactory::getInstance("vcd_cdcover");
			$coversInDB    = (bool)$SETTINGSClass->getSettingsByKey('DB_COVERS');

			// Finally process new cdcovers ..
			foreach ($vcdObj->getCovers() as $coverObj) {

				if (!is_numeric($coverObj->getId())) {
					// Cover has not been added to DB

					if ($coversInDB) {
						// Store covers in DB
						$vcd_image = new VCDImage();
						if (VCDUtils::getFileExtension($coverObj->getFilename()) == 'gif') {
							$image_type = "gif";
						} else {
							$image_type = "pjpeg";
						}

						$arrFileInfo = array("name" => "".$coverObj->getFilename()."", "type" => "image/".$image_type."");
						$image_id = $vcd_image->addImageFromPath(TEMP_FOLDER.$coverObj->getFilename(), $arrFileInfo, true);

						// Set the DB imageID to the cover
						$coverObj->setImageID($image_id);


					} else {
						// Store covers on file level
						// rename the image and move it to the thumbnail upload folder
						if (fs_file_exists(TEMP_FOLDER.$coverObj->getFilename())) {

							if ($coverObj->isThumbnail()) {
								fs_rename(TEMP_FOLDER.$coverObj->getFilename(), THUMBNAIL_PATH . $coverObj->getFilename());
							} else {
								fs_rename(TEMP_FOLDER.$coverObj->getFilename(), COVER_PATH . $coverObj->getFilename());
							}



						} else {
							VCDException::display('Trying to move an image that does not exist!');
						}


					}


					// Finally add the CDCover Obj to the DB
					$COVERClass->addCover($coverObj);


				}
			}



		} catch (Exception $e) {
			VCDException::display($e);
		}

	}


	/**
	 * Update an instance of CD item in the database.
	 *
	 * Updates only the media_typeid and the number of CD's count.
	 *
	 * @param int $vcd_id
	 * @param int $new_mediaid
	 * @param int $old_mediaid
	 * @param int $new_numcds
	 * @param int $oldnumcds
	 */
	public function updateVcdInstance($vcd_id, $new_mediaid, $old_mediaid, $new_numcds, $oldnumcds) {
		try {
				if (is_numeric($vcd_id) && is_numeric($new_mediaid) && is_numeric($old_mediaid) &&
				    is_numeric($new_numcds) && is_numeric($oldnumcds)) {

				    	$user_id = VCDUtils::getUserID();
				    	$this->SQL->updateVcdInstance($user_id, $vcd_id, $new_mediaid, $old_mediaid, $new_numcds, $oldnumcds);

				} else {
					throw new Exception("Invalid parameters");
				}

		} catch (Exception $e) {
			VCDException::display($e);
		}

	}



	/**
	 * Delete a movie entry from user in database.
	 *
	 * If $mode is set to 'full' all records about that movie is deleted.
	 * Should not be called unless the specified user_id is the only owner of
	 * the movie.  If $mode is set to 'single', the record linking to the user is the
	 * only thing that will be deleted.  Returns true on success otherwise false.
	 *
	 * @param int $vcd_id
	 * @param int $media_id
	 * @param string $mode
	 * @param int $user_id
	 * @return bool
	 */
	public function deleteVcdFromUser($vcd_id, $media_id, $mode, $user_id = -1) {
		try {

			if (!is_numeric($vcd_id) || !is_numeric($media_id)) {
				throw new Exception('Parameters must be numeric');
			}

			if (!$_SESSION['user'] instanceof userObj ) {
				return false;
			}


			if ($user_id == -1) {
				$user_id = VCDUtils::getUserID();
			}



			if ($mode == 'full') {


				$delObj = $this->getVcdByID($vcd_id);
				if ($delObj instanceof vcdObj ) {


					$external_id = $delObj->getExternalID();

					// Delete the user Copy
					$this->SQL->deleteVcdFromUser($user_id, $vcd_id, $media_id);


					// verify that no user is linked to this movie
					if ($this->SQL->getVcdOwnerCount($vcd_id) == 0) {

						// Delete all movie data
						$this->SQL->deleteVcdFromDB($vcd_id, $external_id);
						return true;


					} else {
						// Someone is still linked to the movie ... abort
						return false;
					}



				} else {
					throw new Exception('Trying to delete a none existing Obj');
				}


			} elseif ($mode == 'single') {
				// Just delete the user copy .. all movie data stays
				$this->SQL->deleteVcdFromUser($user_id, $vcd_id, $media_id);
				return true;
			}

		} catch (Exception $e) {
			VCDException::display($e);
			return false;
		}

	}


	/**
	 * Get all vcd objects.
	 *
	 * Param $excluded_userid can be used for filtering.
	 * Returns array of vcd objects.
	 *
	 * @param int $excluded_userid
	 * @return array
	 */
	public function getAllVcdForList($excluded_userid) {

		try {
			return $this->SQL->getAllVcdForList($excluded_userid);
		} catch (Exception $e) {
			VCDException::display($e);
		}

	}


	/**
	 * Enter description here...
	 *
	 * @param array $arrIDs
	 * @return array
	 */
	public function getVcdForListByIds($arrIDs) {
		try {
			if (is_array($arrIDs) && !empty($arrIDs)) {
				$arrMovies = array();
				foreach ($arrIDs as $id) {
					array_push($arrMovies, $this->getVcdByID($id));
				}
				return $arrMovies;

			} else {
				throw new Exception('Parameter not valid');
			}

		} catch (Exception $e) {
			VCDException::display($e);
		}
	}


	/**
	 * Get the number of movies in the selected category.
	 *
	 * @param int $category_id
	 * @param bool $isAdult
	 * @param int $user_id
	 * @return int
	 */
	public function getCategoryCount($category_id, $isAdult = false, $user_id = -1) {
		if ($isAdult) {
			//TODO úrfæra adult category count
		} else {
			return $this->SQL->getCategoryCount($category_id, $user_id);
		}
	}

	/**
	 * Get the number of movies for selected category after user filter has been applied.
	 *
	 * @param int $category_id
	 * @param int $user_id
	 * @return int
	 */
	public function getCategoryCountFiltered($category_id, $user_id) {
		try {
		// Get the ignore list.
			$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");
			$metaArr = $SETTINGSClass->getMetadata(0, $user_id, 'ignorelist');
			$ignorelist = split("#", $metaArr[0]->getMetadataValue());

			return $this->SQL->getCategoryCountFiltered($category_id, $user_id, $ignorelist);

		} catch (Exception $e) {
			VCDException::display($e);
		}
	}

	/**
	 * Get all vcd objects by category.
	 *
	 * Param $start and $end can be used as a pager.
	 * Returns array of vcd objects.
	 *
	 * @param int $category_id
	 * @param int $start
	 * @param int $end
	 * @param int $user_id
	 * @return array
	 */
	public function getVcdByCategory($category_id, $start=0, $end=0, $user_id = -1) {
		try {
			if ($start == 0 && $end == 0) {
				if ($user_id == -1) {
					return $this->SQL->getAllVcdByCategory($category_id);
				} else {
					return $this->SQL->getAllVcdByUserAndCategory($user_id, $category_id, true);
				}

			} else {

				// Get the id of the thumbnail coverObj in DB
				$COVERSClass = VCDClassFactory::getInstance('vcd_cdcover');
				$coverTypeObj = $COVERSClass->getCoverTypeByName('thumbnail');

				return $this->SQL->getVcdByCategory($category_id, $start, $end, $coverTypeObj->getCoverTypeID(), $user_id);
			}

		} catch (Exception $e) {
			VCDException::display($e);
		}
	}


	/**
	 * Get movies for specified category, filtering out movies from users who user does not wish to see.
	 *
	 * Returns array of vcd Objects
	 *
	 * @param int $category_id
	 * @param int $start
	 * @param int $end
	 * @param int $user_id
	 * @return array
	 */
	public function getVcdByCategoryFiltered($category_id, $start=0, $end=0, $user_id) {
		try {

			if (is_numeric($category_id) && is_numeric($user_id)) {

				// Get the ignore list.
				$COVERSClass = VCDClassFactory::getInstance('vcd_cdcover');
				$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");
				$metaArr = $SETTINGSClass->getMetadata(0, $user_id, 'ignorelist');
				$ignorelist = split("#", $metaArr[0]->getMetadataValue());
				$coverTypeObj = $COVERSClass->getCoverTypeByName('thumbnail');
				$thumb_id = $coverTypeObj->getCoverTypeID();

				return $this->SQL->getVcdByCategoryFiltered($category_id, $start, $end, $thumb_id, $ignorelist);


			} else {
				throw new Exception("Param category_id and user_id must be numeric.");
			}



		} catch (Exception $e) {
			VCDException::display($e);
		}
	}


	/**
	 * Get all adult vcd objects linked to certain adult subcategories.
	 *
	 * Returns array of vcd objects.
	 *
	 * @param int $category_id
	 * @return array
	 */
	public function getVcdByAdultCategory($category_id) {
		try {
			if (is_numeric($category_id)) {

				// Get the id of the thumbnail coverObj in DB
				$COVERSClass = VCDClassFactory::getInstance('vcd_cdcover');
				$coverTypeObj = $COVERSClass->getCoverTypeByName('thumbnail');
				return $this->SQL->getVcdByAdultCategory($category_id, $coverTypeObj->getCoverTypeID());

			} else {
				throw new Exception("Parameter must be numeric");
			}

		} catch (Exception $e) {
			VCDException::display($e);
		}
	}


	/**
	 * Get all adult vcd objects linked to the given adult studio ID.
	 *
	 * Returns an array of vcd objects.
	 *
	 * @param int $studio_id
	 * @return array
	 */
	public function getVcdByAdultStudio($studio_id) {
		try {
			if (is_numeric($studio_id)) {

				// Get the id of the thumbnail coverObj in DB
				$COVERSClass = VCDClassFactory::getInstance('vcd_cdcover');
				$coverTypeObj = $COVERSClass->getCoverTypeByName('thumbnail');

				return $this->SQL->getVcdByAdultStudio($studio_id, $coverTypeObj->getCoverTypeID());


			} else {
				VCDException::display('Param must be numeric');
			}

		} catch (Exception $e) {
			VCDException::display($e);
		}
	}

	/**
	 * Mark a movie with screenshots available.
	 *
	 * @param int $vcd_id
	 */
	public function markVcdWithScreenshots($vcd_id) {
		try {
			if (is_numeric($vcd_id)) {
				$this->SQL->markVcdWithScreenshots($vcd_id);
			} else {
				throw new Exception("vcd_id must be numeric");
			}
		} catch (Exception $e) {
			VCDException::display($e);
		}
	}


	/**
	 * Check if movie has screenshots.
	 *
	 * @param int $vcd_id
	 * @return bool
	 */
	public function getScreenshots($vcd_id) {
		try {
			if (is_numeric($vcd_id)) {
				return $this->SQL->getScreenshots($vcd_id);
			} else {
				throw new Exception("Parameter must be numeric");
			}
		} catch (Exception $e) {
			VCDException::display($e);
		}
	}


	/**
	 * Get the Top Ten list of latest movies.
	 *
	 * $category_id can be used to filter results to specified category.
	 * Returns array of vcd objects
	 *
	 * @param int $category_id
	 * @param array $arrFilter | array of category id's to exclude
	 * @return array
	 */
	public function getTopTenList($category_id = 0, $arrFilter = null) {
		if (!is_numeric($category_id)) {
			throw new Exception("Category ID must be numeric");
		} else {
			try {
				if ($category_id == 0) {
					return $this->SQL->getCompleteTopTenList($arrFilter);
				} else {
					return $this->SQL->getTopTenList($category_id);
				}

			} catch (Exception $e) {
				VCDException::display($e);
			}
		}
	}

	/**
	 * Get a random movie from database.
	 *
	 * $category can be used to narrow results to specified category
	 * $use_seenlist set to false rules out movies that user has seen.
	 *
	 * @param int $category
	 * @param bool $use_seenlist
	 * @return vcdObj
	 */
	public function getRandomMovie($category, $use_seenlist = false) {
		try {

			// Get the current user's ID
			$user_id = VCDUtils::getUserID();
			if ($category == 0) {
				$movies = $this->getAllVcdByUserId($user_id);
			} else {
				$movies = $this->SQL->getAllVcdByUserAndCategory($user_id, $category);
			}

			// TODO - implement the use_seenlist stuff ..
			if ($use_seenlist) {
				// Get the seenlist
				$SETTINGSClass = VCDClassFactory::getInstance('vcd_settings');
				$ArrSeen = $SETTINGSClass->getRecordIDsByMetadata($user_id, metadataTypeObj::SYS_SEENLIST );
				if (is_array($ArrSeen) && sizeof($ArrSeen) > 0) {
					// we got data . lets compare and filter out the unwanted ones ..
					$arrNewlist = array();
					foreach ($movies as $obj) {
						if (!in_array($obj->getID(), $ArrSeen)) {
							array_push($arrNewlist, $obj);
						}
					}
					$movies = &$arrNewlist;
				}

			}


			if (sizeof($movies) > 0) {
				$randIndex = rand(0, (sizeof($movies)-1));
				$movie = $movies[$randIndex];
				$vcd = $this->getVcdByID($movie->getID());
				unset($movies);
				return $vcd;
			} else {
				return null;
			}






		} catch (Exception $e) {
			VCDException::display($e);
		}
	}


	/**
	 * Search the database.
	 *
	 * Returns array of vcd Objects.
	 * param $method defines the search type.
	 * Search type can be 'title', 'actor' or 'director'
	 *
	 * @param string $keyword
	 * @param string $method
	 * @return array
	 */
	public function search($keyword, $method) {
		try {
			// are adult categories in use ? and if so does user want to see them ?
			$showadult = false;

			if (isset($_SESSION['user'])) {
				$curruser = &$_SESSION['user'];
				if ($curruser->getPropertyByKey('SHOW_ADULT')) {
					$showadult = true;
				}
			}

			$resultArr =  $this->SQL->search($keyword, $method, $showadult);


			// Check if user is logged in and is using custom index
			if (VCDUtils::isLoggedIn()) {
				$user = $_SESSION['user'];
				if ($user->getPropertyByKey('USE_INDEX')) {
					// Check for movie marked with custom key
					$cusKeyArr = $this->SQL->getMovieByCustomKey($user->getUserID(), $keyword);
					if (is_array($cusKeyArr) && sizeof($cusKeyArr) > 0) {
						// push the results to the existing vcdObj array
						foreach ($cusKeyArr as $item) {
							array_push($resultArr, $this->getVcdByID($item['id']));
						}
						unset($cusKeyArr);
					}
				}
			}


			return $resultArr;

		} catch (Exception $e) {
			VCDException::display($e);
		}

	}


	/**
	 * Perform advanced search.
	 *
	 * Returns array of vcd objects.
	 *
	 * @param string $title
	 * @param int $category
	 * @param int $year
	 * @param int $mediatype
	 * @param int $owner
	 * @param float $imdbgrade
	 * @return array
	 */
	public function advancedSearch($title = null, $category = null, $year = null, $mediatype = null,
									   $owner = null, $imdbgrade = null) {
		try {


			$results = $this->SQL->advancedSearch($title, $category, $year, $mediatype, $owner, $imdbgrade);

			// Check if user is logged in and is using custom index
			if (VCDUtils::isLoggedIn() && !is_null($title)) {
				$user = $_SESSION['user'];
				if ($user->getPropertyByKey('USE_INDEX')) {
					// Check for movie marked with custom key
					$cusKeyArr = $this->SQL->getMovieByCustomKey($user->getUserID(), $title);
					if (is_array($cusKeyArr) && sizeof($cusKeyArr) > 0) {
						$results = array_merge($results, $cusKeyArr);
					}
				}
			}


			$SETTINGSClass = VCDClassFactory::getInstance('vcd_settings');

			foreach ($results as &$item) {
				$catObj = $SETTINGSClass->getMovieCategoryByID($item['cat_id']);
				$item['category'] = $catObj->getName();

				$mObj = $SETTINGSClass->getMediaTypeByID($item['media_id']);
				$item['media_type'] = $mObj->getDetailedName();

			}


			return $results;

		} catch (Exception $e) {
			VCDException::display($e);
		}

	}


	/**
	* @return array
	* @param int $user_id
	* @param int $media_id
	* @param int $category_id
	* @param string $method
	* @desc Compare movie lists between users
	**/
	public function crossJoin($user_id, $media_id, $category_id, $method) {
		try {

			if (!is_numeric($user_id) || $method == 'null') {
				return null;
			}

			$request_userid = VCDUtils::getUserID();
			return $this->SQL->crossJoin($request_userid, $user_id, $media_id, $category_id, $method);

		} catch (Exception $e) {
			VCDException::display($e);
		}

	}


	/**
	 * Get all vcd objects by userid for printview.
	 *
	 * $list_type can be 'all', 'movies', 'tv' or 'blue'
	 * Return array of vcd objects.
	 *
	 * @param int $user_id
	 * @param string $list_type
	 * @return array
	 */
	public function getPrintViewList($user_id, $list_type) {
		try {
			if (!is_numeric($user_id)) {
				throw new Exception('User ID missing');
			}

			$SETTINGSClass = VCDClassFactory::getInstance('vcd_settings');
			$cat_tv = $SETTINGSClass->getCategoryIDByName('Tv Shows');
			$cat_adult = $SETTINGSClass->getCategoryIDByName('Adult');

			// Get the id of the thumbnail coverObj in DB
			$COVERSClass = VCDClassFactory::getInstance('vcd_cdcover');
			$coverTypeObj = $COVERSClass->getCoverTypeByName('thumbnail');
			$thumbnail_id = $coverTypeObj->getCoverTypeID();

			if (strcmp($list_type, 'all') == 0) {
				return $this->SQL->getPrintViewList($user_id, null, null, $thumbnail_id);
			} elseif (strcmp($list_type, 'movies') == 0) {
				return $this->SQL->getPrintViewList($user_id, null, array($cat_tv, $cat_adult), $thumbnail_id);
			} elseif (strcmp($list_type, 'tv') == 0) {
				return $this->SQL->getPrintViewList($user_id, array($cat_tv), null, $thumbnail_id);
			} elseif (strcmp($list_type, 'blue') == 0) {
				return $this->SQL->getPrintViewList($user_id, array($cat_adult), null, $thumbnail_id);
			}

			return null;

		} catch (Exception $e) {
			VCDException::display($e);
		}

	}


	/**
	 * Get similiar movies as an array.
	 *
	 * Movies in same category as the one specified in the $vcd_id param
	 * and with similar names will be returned as an array.
	 * Returns array of vcd objects.
	 *
	 * @param int $vcd_id
	 * @return array
	 */
	public function getSimilarMovies($vcd_id) {
		try {
			if (is_numeric($vcd_id)) {

				// Find out this movie's details
				$obj = $this->getVcdByID($vcd_id);
				if ($obj instanceof vcdObj) {
					$title  = $obj->getTitle();
					$cat_id = $obj->getCategoryID();
					$arr = $this->SQL->getSimilarMovies($title, $cat_id);

					// Shorten the titles and delete the parent entry
					$arrList = array();
					$len = 30;
					foreach ($arr as $vcdObj) {
						if ($vcdObj->getID() != $vcd_id) {
							$vcdObj->setTitle(VCDUtils::shortenText($vcdObj->getTitle(), $len));
							array_push($arrList, $vcdObj);
						}
					}
					unset($arr);
					return $arrList;
				}

				return null;

			}

			return null;

		} catch (Exception $e) {
			VCDException::display($e);
		}
	}



	/**
	 * Get the number of movies user has added to VCD-db.
	 *
	 * @param int $user_id
	 * @return int
	 */
	public function getMovieCount($user_id) {
		try {

			if (is_numeric($user_id)) {
				return $this->SQL->getMovieCount($user_id);
			} else {
				throw new Exception("Invalid userid");
			}

		}catch (Exception $e) {
			VCDException::display($e);
		}
	}


	/*  Private functions */
	/**
	 * Get imdb object by movie id
	 *
	 * @param int $movie_id
	 * @return imdbObj
	 */
	private function getIMDBinfo($movie_id) {
		try {
			return $this->SQL->getIMDB($movie_id);
		} catch (Exception $e) {
			VCDException::display($e);
		}
	}


	// VCD-db only allows one entry per user with same vcd_id and media type
	/**
	 * Check the database for duplicate entries.
	 *
	 * @param int $user_id
	 * @param int $vcd_id
	 * @param int $media_id
	 * @return bool
	 */
	private function checkDuplicateEntry($user_id, $vcd_id, $media_id) {
		try {

			if ($this->SQL->checkDuplicateEntry($user_id, $vcd_id, $media_id) == 0) {
				return false;
			} else {
				return true;
			}

		} catch (Exception $e) {
			throw new VCDException($e);
		}
	}




}