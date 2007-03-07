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
require_once(dirname(__FILE__).'/cdObj.php');
require_once(dirname(__FILE__).'/vcdObj.php');
require_once(dirname(__FILE__).'/fetchedObj.php');

class vcd_movie implements IVcd  {

	/**
	 *
	 * @var vcdSQL
	 */
	private $SQL;


	/**
	 * Legal search methods.
	 *
	 * @var array
	 */
	private $searchMethods = array('title', 'actor', 'director');


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
			if (!is_numeric($vcd_id)) {
				throw new VCDInvalidArgumentException('Movie Id must be numeric');
			}

			// Get the basic CD object with source site attached if any
			$obj = $this->SQL->getVcdByID($vcd_id);
			
			if (!$obj instanceof vcdObj) {
				throw new VCDProgramException('Invalid movie Id');
			}

			// Get the covers for the CD
			$coverArr = $this->Cover()->getAllCoversForVcd($vcd_id);

			if (sizeof($coverArr) > 0) {
				$obj->addCovers($coverArr);
			}

			// Set the movie categoryObj
			$obj->setMovieCategory($this->Settings()->getMovieCategoryByID($obj->getCategoryID()));

			// Get IMDB info for regular movies and TV shows
			// or adult information for blue movies

			if ($obj->getCategoryID() == $this->Settings()->getCategoryIDByName("adult")) {
				
				// Blue Movie
				$arrPornstars = $this->Pornstar()->getPornstarsByMovieID($vcd_id);
				$obj->addPornstars($arrPornstars);
				$sObj = $this->Pornstar()->getStudioByMovieID($vcd_id);
				if ($sObj instanceof studioObj ) {
					$obj->setStudioID($sObj->getID());
				}
				
			} else {
				
				// Normal flick
				$imdb = $this->getIMDBinfo($vcd_id);
				if ($imdb instanceof imdbObj) {
					$obj->setIMDB($imdb);
				}
			}

			return $obj;


		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Get all vcd objects as an array by user ID. Param $simple set to true populates minimal data 
	 * to the vcd objects otherwise all information for each vcd object is populated.
	 *
	 * @param int $user_id
	 * @param bool $simple
	 * @return array
	 */
	public function getAllVcdByUserId($user_id, $simple = true) {
		try {
			
			if (!is_numeric($user_id)) { 
				throw new VCDInvalidArgumentException('User Id must be numeric');
			}

			if ($simple) {
				$arr = $this->SQL->getAllVcdByUserIdSimple($user_id);
			} else {
				$arr = $this->SQL->getAllVcdByUserId($user_id);
			}


			$returnArr = array();

			foreach ($arr as $obj) {

				// Set the movie categoryObj
				$obj->setMovieCategory($this->Settings()->getMovieCategoryByID($obj->getCategoryID()));

				if (!$simple) {
					// Get IMDB info for regular movies and TV shows
					// or adult information for blue movies

					if ($obj->getCategoryID() == $this->Settings()->getCategoryIDByName("adult")) {
						// Blue Movie

						$arrPornstars = $this->Pornstar()->getPornstarsByMovieID($obj->getID());
						$obj->addPornstars($arrPornstars);

						$studioObj = $this->Pornstar()->getStudioByMovieID($obj->getID());
						if ($studioObj instanceof studioObj ) {
							$obj->setStudioID($studioObj->getID());
						}

						// Get the adult categories
						$arrPornCats = $this->Pornstar()->getSubCategoriesByMovieID($obj->getID());
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

		} catch (Exception $ex) {
			throw $ex;
		}
	}



	/**
	 * Get array of latest vcd objects that have been added to database by user ID.
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

			if (!(is_numeric($user_id) && is_numeric($count))) {
				throw new VCDInvalidArgumentException('User Id and Count must be numeric');
			}

			if ($simple) {
				$arr = $this->SQL->getAllVcdByUserIdSimple($user_id, $count);
			} else {
				$arr = $this->SQL->getAllVcdByUserId($user_id, $count);
			}

			$returnArr = array();
			
			foreach ($arr as $obj) {
				// Set the movie categoryObj
				$obj->setMovieCategory($this->Settings()->getMovieCategoryByID($obj->getCategoryID()));


				if (!$simple) {
					// Get IMDB info for regular movies and TV shows
					// or adult information for blue movies

					if ($obj->getCategoryID() == $this->Settings()->getCategoryIDByName("adult")) {
						// Blue Movie

						$arrPornstars = $this->Pornstar()->getPornstarsByMovieID($obj->getID());
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

		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Add a vcd object to database. On success the newly created vcd objects id is returned, 
	 * otherwise -1 or an Exception will be thrown.
	 *
	 * @param vcdObj $vcdObj | The vcdObj to add
	 * @param  bool $notify | Send notification emails or not
	 * @return int
	 */
	public function addVcd(vcdObj $vcdObj, $notify = true) {
		try {

			$cd_id = -1;

			// First of all .. check if movie exists in DB (exactly same title & year)
			$exisisting_id = $this->SQL->checkEntry($vcdObj->getTitle(), $vcdObj->getYear());
			
			if (!$exisisting_id) {
				// All ok .. CD does not exist .., insert the new CD to DB
				$cd_id = $this->SQL->addVCD($vcdObj);

				// Ooops we have a db underneath, that does not return last inserted ID, will try to use adodb->genID
				if ($cd_id == -1) {
					throw new VCDProgramException('Your database does not support identity inserts.<break>Please post a message to the VCD DB homepage for further info.');
				}

				// Associate the new ID to the vcdObj
				$vcdObj->setID($cd_id);

				// Add an owner instance to the newly created CD
				if (!$this->SQL->addVcdInstance($vcdObj)) {
					throw new VCDProgramException('Could not add instance to user');
				}

				// Add the IMDB information to the IMDB table
				if ($vcdObj->getIMDB() instanceof imdbObj) {

					// Just to be sure .. check for existing IMDB entry
					if ($this->SQL->checkIMDBDuplicate($vcdObj->getIMDB()->getIMDB()) == 0) {
						if (!$this->SQL->addIMDBInfo($vcdObj->getIMDB())) {
							throw new VCDProgramException('Failed to add entry in the IMDB table.');
						}
					}
				}

				// Add to movie to the source site linked table
				if (is_numeric($vcdObj->getSourceSiteID()) && strlen($vcdObj->getExternalID()) > 0) {
					if (!$this->SQL->addVcdToSourceSite($vcdObj)) {
						throw new VCDProgramException('Failed to link movie to source site table');
					}
				}

				/*
				Process The thumbnail
				Check if images should be stored in DB or on HD.
				First, check if any thumbnail was added
				*/

				foreach ($vcdObj->getCovers() as $coverObj) {
                    if($coverObj instanceof cdcoverObj) {
                        $imgToDB = (bool)$this->Settings()->getSettingsByKey('DB_COVERS');

                        // Create temporary unique ID for the image
                        $image_name = VCDUtils::generateUniqueId();

                        $coverObj->setOwnerId($vcdObj->getInsertValueUserID());
                        $coverObj->setVcdId($cd_id);

                        $filename = TEMP_FOLDER.$coverObj->getFilename();
                        $newname = $image_name . "." . VCDUtils::getFileExtension($coverObj->getFilename());

                        if ($imgToDB) { // Insert the image as a binary file to DB, it's still in the temp folder
                            $vcd_image = new VCDImage();

                            if (VCDUtils::getFileExtension($coverObj->getFilename()) == 'gif') {
                                $image_type = "gif";
                            } else {
                                $image_type = "pjpeg";
                            }

                            // Use File info
                            $arrFileInfo = array("name" => "".$newname."", "type" => "image/".$image_type."");

                            $image_id = $vcd_image->addImageFromPath(TEMP_FOLDER.$coverObj->getFilename(), $arrFileInfo, true);
                            $coverObj->setFilesize($vcd_image->getFilesize());
                            // Set the DB imageID to the cover
                            $coverObj->setImageID($image_id);
                            $coverObj->setFilename($newname);
                        } else {
                            // rename the image and move it to the thumbnail upload folder
                            if (fs_file_exists($filename)) {

                                $coverObj->setFilesize(fs_filesize($filename));
                                if (strcmp($coverObj->getCoverTypeName(), "thumbnail") == 0) {
                                	fs_rename($filename, THUMBNAIL_PATH . $newname);
                                } else {
                                	fs_rename($filename, COVER_PATH . $newname);
                                }
                                
                                $coverObj->setFilename($newname);

                            } else {
                                throw new VCDProgramException('Trying to move an image that does not exist!');
                            }

                        }
                    }

                    // Finally add the CDCover Obj to the DB
                    $this->Cover()->addCover($coverObj);
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
					throw new VCDConstraintException('You have already added this movie, in same format.');
				}

				if (!$this->SQL->addVcdInstance($vcdObj)) {
					throw new VCDProgramException('Could not add instance to user.');
				}

				$cd_id = $exisisting_id;

			}


			// Add comments to the movie if any
			if (is_array($vcdObj->getComments()) && sizeof($vcdObj->getComments()) > 0) {
				foreach ($vcdObj->getComments() as $commentObj) {
					$commentObj->setVcdID($cd_id);
					$this->Settings()->addComment($commentObj);
				}
			}

			// Add metadata to the movie if any
			if (is_array($vcdObj->getMetaData()) && sizeof($vcdObj->getMetaData()) > 0) {
				// Get metadataObjects created by user if any ..
				$arrUserMeta = $this->Settings()->getMetadataTypes(VCDUtils::getUserID());
				foreach ($vcdObj->getMetaData() as $metadataObj) {
					$metadataObj->setRecordID($cd_id);
					// Check if metadata is a System type ..
					if (!$metadataObj->isSystemObj()) {
						// Check if this metadataType exists ..
						$mFound = false;
						if (is_array($arrUserMeta) && sizeof($arrUserMeta) > 0) {
							foreach ($arrUserMeta as $existingMetaObj) {
								if (strcmp($existingMetaObj->getMetadataTypeName(),$metadataObj->getMetadataTypeName()) == 0) {
									$metadataObj->setMetaDataTypeID($existingMetaObj->getMetadataTypeID());
									$mFound = true;
									break;
								}
							}
						}
						if (!$mFound) {
							// Metadata Type was not found .. lets create it ..
							$mObj = new metadataTypeObj('', $metadataObj->getMetadataTypeName(), $metadataObj->getMetadataDescription(), VCDUtils::getUserID());
							$mObj = $this->Settings()->addMetaDataType($mObj);
							$metadataObj->setMetaDataTypeID($mObj->getMetadataTypeID());
							// Update the internal $arrUserMeta stack
							$arrUserMeta = $this->Settings()->getMetadataTypes(VCDUtils::getUserID());
						}

					} 
					
					$this->Settings()->addMetadata($metadataObj, true);
				}
			}


			// Check if people wan't to be notified of the new entry
			if ($notify) {
				$this->Settings()->notifyOfNewEntry($vcdObj);
			}


			return $cd_id;



		} catch (Exception $ex) {
			throw $ex;
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

			if (!(is_numeric($user_id) && is_numeric($vcd_id) && is_numeric($mediatype) && is_numeric($cds))) {
				throw new VCDInvalidArgumentException('User Id, Movie Id, Mediatype Id and Cd count must be numeric');
			}

			// Has the user submitted a duplicate entry ?
			// in other words .. does this exact copy already belong to him ?
			if ($this->checkDuplicateEntry($user_id, $vcd_id, $mediatype)) {
				// Return from function, nothing more to do ..
				return;
			}

			$this->SQL->addVcdToUser($user_id, $vcd_id, $mediatype, $cds);

		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Internal function to process adult vcd objects
	 *
	 * @param vcdObj $vcdObj
	 */
	private function handleAdultVcd(vcdObj $vcdObj) {
		try {

			// Link the pornstars to the movie
			if ($vcdObj->getID() > 0) {
				foreach ($vcdObj->getPornstars() as $pornstarObj) {
					$this->Pornstar()->addPornstarToMovie($pornstarObj->getID(), $vcdObj->getID());
				}
			} else {
				throw new VCDProgramException('Movie Id must be set before linking stars to Movie');
			}

			// Link movie to adult categories
			foreach ($vcdObj->getAdultCategories() as $adultCatObj) {
				$this->Pornstar()->addCategoryToMovie($vcdObj->getID(), $adultCatObj->getID());
			}

			// Link to movie to the assigned adult studio
			if ($vcdObj->getStudioID() > 0) {
				$this->Pornstar()->addMovieToStudio($vcdObj->getStudioID(),$vcdObj->getID());
			}

			$cd_id = $vcdObj->getID();


		} catch (Exception $ex) {
			throw $ex;
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

			if ((bool)$vcdObj->isAdult()) {
				// Blue movie

				// update the adult categories
				$this->Pornstar()->deleteMovieFromCategories($vcdObj->getID());
				foreach ($vcdObj->getAdultCategories() as $adultCatObj) {
					$this->Pornstar()->addCategoryToMovie($vcdObj->getID(), $adultCatObj->getID());
				}

				$this->Pornstar()->deleteMovieFromStudio($vcdObj->getID());
				$this->Pornstar()->addMovieToStudio($vcdObj->getStudioID(), $vcdObj->getID());



			} else {
				// Normal flick
				// Check for the IMDB obj if any
				if ($vcdObj->getIMDB() instanceof imdbObj ) {

					if (is_numeric($vcdObj->getSourceSiteID()) && $vcdObj->getSourceSiteID() > 0) {
						$this->SQL->updateIMDBInfo($vcdObj->getIMDB());

					} else {

						// New IMDB Obj - Lets write it to DB
						// Set the source site

						$sourceSiteObj = $this->Settings()->getSourceSiteByAlias('imdb');
						$imdbObj = $vcdObj->getIMDB();
						$imdbObj->setYear($vcdObj->getYear());
						if ($sourceSiteObj instanceof sourceSiteObj ) {
							$vcdObj->setSourceSite($sourceSiteObj->getsiteID(), $imdbObj->getIMDB());
							if (!$this->SQL->addIMDBInfo($imdbObj)) {
								throw new VCDProgramException('Failed to add to IMDB table');
							}
							// Add to movie to the source site linked table
							if (is_numeric($vcdObj->getSourceSiteID()) && strlen($vcdObj->getExternalID()) > 0) {
								if (!$this->SQL->addVcdToSourceSite($vcdObj)) {
									throw new VCDProgramException('Failed to link movie to source site table');
								}
							}
						}
					}
				}
			}


			// Check where covers should be stored ..
			$coversInDB = (bool)$this->Settings()->getSettingsByKey('DB_COVERS');

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
							throw new VCDProgramException('Trying to move an image that does not exist!');
						}
					}


					// Finally add the CDCover Obj to the DB
					$this->Cover()->addCover($coverObj);

				}
			}


		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Update an instance of CD item in the database. Updates only the Mediatype Id and the number of CD's count.
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
				throw new VCDInvalidArgumentException('Invalid parameters');
			}

		} catch (Exception $ex) {
			throw $ex;
		}
	}



	/**
	 * Delete a movie entry from user in database. If $mode is set to 'full' all records about that movie is deleted.
	 * Should not be called unless the specified user_id is the only owner of the movie.  
	 * If $mode is set to 'single', the record linking to the user is the only thing that will be deleted.  
	 * Returns true on success otherwise false.
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
				throw new VCDInvalidArgumentException('Movie Id and Media Id must be numeric.');
			}

			if (!VCDUtils::isLoggedIn()) {
				throw new VCDProgramException('Action not authorized.');
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
					throw new VCDProgramException('Target object not found.');
				}


			} elseif ($mode == 'single') {
				
				// Just delete the user copy .. all movie data stays
				$this->SQL->deleteVcdFromUser($user_id, $vcd_id, $media_id);
				return true;
				
			}

		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Get all vcd objects. Param $excluded_userid can be used for filtering.
	 * Returns array of vcd objects.
	 *
	 * @param int $excluded_userid
	 * @return array
	 */
	public function getAllVcdForList($excluded_userid) {
		try {
			
			return $this->SQL->getAllVcdForList($excluded_userid);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Get specific movies by ID's.  Returns array of vcd objects.
	 *
	 * @param array $arrIDs | Array of numeric values representing the movie ID's
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
				throw new VCDInvalidArgumentException('Invalid parameter.');
			}

		} catch (Exception $ex) {
			throw $ex;
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
		try {
		
			if (!is_numeric($user_id)) {
				throw new VCDInvalidArgumentException('User Id must be numeric');
			}
			
			return $this->SQL->getCategoryCount($category_id, $user_id);
				
		} catch (Exception $ex) {
			throw $ex;
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
			$metaArr = $this->Settings()->getMetadata(0, $user_id, 'ignorelist');

			$ignorelist = split("#", $metaArr[0]->getMetadataValue());
			return $this->SQL->getCategoryCountFiltered($category_id, $user_id, $ignorelist);

		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Get all vcd objects by category. Param $start and $end can be used as a pager.
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
				$coverTypeObj = $this->Cover()->getCoverTypeByName('thumbnail');
				return $this->SQL->getVcdByCategory($category_id, $start, $end, $coverTypeObj->getCoverTypeID(), $user_id);
			}

		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Get movies for specified category, filtering out movies from users who user does not wish to see.
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
			if (!(is_numeric($category_id) && is_numeric($user_id))) {
				throw new VCDInvalidArgumentException('Category Id and User Id must be numeric');
			}

			// Get the ignore list.
			$metaArr = $this->Settings()->getMetadata(0, $user_id, 'ignorelist');
			$ignorelist = split("#", $metaArr[0]->getMetadataValue());
			$coverTypeObj = $this->Cover()->getCoverTypeByName('thumbnail');
			$thumb_id = $coverTypeObj->getCoverTypeID();
			return $this->SQL->getVcdByCategoryFiltered($category_id, $start, $end, $thumb_id, $ignorelist);

		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Get all adult vcd objects linked to certain adult subcategories. Returns array of vcd objects.
	 *
	 * @param int $category_id
	 * @return array
	 */
	public function getVcdByAdultCategory($category_id) {
		try {
			if (!is_numeric($category_id)) {
				throw new VCDInvalidArgumentException('Category Id must be numeric');
			}

			// Get the id of the thumbnail coverObj in DB
			$coverTypeObj = $this->Cover()->getCoverTypeByName('thumbnail');
			return $this->SQL->getVcdByAdultCategory($category_id, $coverTypeObj->getCoverTypeID());

		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Get all adult vcd objects linked to the given adult studio ID. Returns an array of vcd objects.
	 *
	 * @param int $studio_id
	 * @return array
	 */
	public function getVcdByAdultStudio($studio_id) {
		try {
			if (!is_numeric($studio_id)) {
				throw new VCDInvalidArgumentException('Studio Id must be numeric');
			}

			// Get the id of the thumbnail coverObj in DB
			$coverTypeObj = $this->Cover()->getCoverTypeByName('thumbnail');
			return $this->SQL->getVcdByAdultStudio($studio_id, $coverTypeObj->getCoverTypeID());

		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Mark a movie with screenshots available.
	 *
	 * @param int $vcd_id
	 */
	public function markVcdWithScreenshots($vcd_id) {
		try {
			
			if (!is_numeric($vcd_id)) {
				throw new VCDInvalidArgumentException('Movie Id must be numeric');
			}
			$this->SQL->markVcdWithScreenshots($vcd_id);
			
		} catch (Exception $ex) {
			throw $ex;
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
			
			if (!is_numeric($vcd_id)) {
				throw new VCDInvalidArgumentException('Movie Id must be numeric');
			}
			
			return $this->SQL->getScreenshots($vcd_id);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Get the Top Ten list of latest movies. $category_id can be used to filter results to specified category.
	 * Returns array of vcd objects
	 *
	 * @param int $category_id
	 * @param array $arrFilter | array of category id's to exclude
	 * @return array
	 */
	public function getTopTenList($category_id = 0, $arrFilter = null) {
		try {
		
			if (!is_numeric($category_id)) {
				throw new VCDInvalidArgumentException('Category Id must be numeric');
			}	
			
			if ($category_id == 0) {
				return $this->SQL->getCompleteTopTenList($arrFilter);
			} else {
				return $this->SQL->getTopTenList($category_id);
			}
			
		} catch (Exception $ex) {
			throw $ex;		
		}
	}
	

	/**
	 * Get a random movie from database. $category can be used to narrow results to specified category
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
				$ArrSeen = $this->Settings()->getRecordIDsByMetadata($user_id, metadataTypeObj::SYS_SEENLIST );
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

		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Search the database. Returns array of vcd Objects. Param $method defines the search type.
	 * Search type can be 'title', 'actor' or 'director'
	 *
	 * @param string $keyword
	 * @param string $method
	 * @return array
	 */
	public function search($keyword, $method) {
		try {

			// Check that the search method is legal
			if (!in_array($method, $this->searchMethods)) {
				$method = $this->searchMethods[0];
			}

			// are adult categories in use ? and if so does user want to see them ?
			$showadult = VCDUtils::showAdultContent();

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

		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Perform advanced search. Returns array of vcd objects.
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


			foreach ($results as &$item) {
				$catObj = $this->Settings()->getMovieCategoryByID($item['cat_id']);
				$item['category'] = $catObj->getName();

				$mObj = $this->Settings()->getMediaTypeByID($item['media_id']);
				$item['media_type'] = $mObj->getDetailedName();
			}

			return $results;

		} catch (Exception $ex) {
			throw $ex;
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

			return $this->SQL->crossJoin(VCDUtils::getUserID(), $user_id, $media_id, $category_id, $method);

		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Get all vcd objects by userid for printview. $list_type can be 'all', 'movies', 'tv', text or 'blue'
	 * Returns array of vcd objects.
	 *
	 * @param int $user_id
	 * @param string $list_type
	 * @return array
	 */
	public function getPrintViewList($user_id, $list_type) {
		try {
			
			if (!is_numeric($user_id)) {
				throw new VCDInvalidArgumentException('User Id must be numeric');
			}

			$cat_tv = $this->Settings()->getCategoryIDByName('Tv Shows');
			$cat_adult = $this->Settings()->getCategoryIDByName('Adult');

			// Get the id of the thumbnail coverObj in DB
			$coverTypeObj = $this->Cover()->getCoverTypeByName('thumbnail');
			$thumbnail_id = $coverTypeObj->getCoverTypeID();

			if (strcmp($list_type, 'all') == 0) {
				return $this->SQL->getPrintViewList($user_id, null, null, $thumbnail_id);
			} elseif (strcmp($list_type, 'movies') == 0) {
				return $this->SQL->getPrintViewList($user_id, null, array($cat_tv, $cat_adult), $thumbnail_id);
			} elseif (strcmp($list_type, 'tv') == 0) {
				return $this->SQL->getPrintViewList($user_id, array($cat_tv), null, $thumbnail_id);
			} elseif (strcmp($list_type, 'blue') == 0) {
				return $this->SQL->getPrintViewList($user_id, array($cat_adult), null, $thumbnail_id);
			} elseif (strcmp($list_type, 'text') == 0) {
				return $this->getAllVcdByUserId($user_id, false);
			}

			return null;

		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Get similiar movies as an array. Movies in same category as the one specified in the $vcd_id param
	 * and with similar names will be returned as an array. Returns array of vcd objects.
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

		} catch (Exception $ex) {
			throw $ex;
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
			
			if (!is_numeric($user_id)) {
				throw new VCDInvalidArgumentException('User Id must be numeric');
			}
		
			return $this->SQL->getMovieCount($user_id);

		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Add Default DVD Settings if they are defined and if the selected mediaType is a DVD or a child of the DVD mediatype object.
	 *
	 * @param vcdObj $obj
	 */
	public function addDefaultDVDSettings(vcdObj $obj) {
		try {

			$mediaTypeID = $obj->getInsertValueMediaTypeID();
			
			$dvdTypeObj = $this->Settings()->getMediaTypeByName('DVD');
			$dvdrTypeObj = $this->Settings()->getMediaTypeByName('DVD-R');
			
			if (is_numeric($mediaTypeID) && $dvdTypeObj instanceof mediaTypeObj) {
				
				if ($mediaTypeID == $dvdTypeObj->getmediaTypeID() || $mediaTypeID == $dvdTypeObj->getParentID() 
						|| $mediaTypeID == $dvdrTypeObj->getmediaTypeID() || $mediaTypeID == $dvdrTypeObj->getParentID()) {
							
					// Yeap .... DVD based type	
					$dmetaObj = $this->Settings()->getMetadata(0, VCDUtils::getUserID(), metadataTypeObj::SYS_DEFAULTDVD);

					if (is_array($dmetaObj) && sizeof($dmetaObj) == 1) {
						$dvdSettings = unserialize($dmetaObj[0]->getMetadataValue());
						if (is_array($dvdSettings)) {
							$metaObj = new metadataObj(array('', '', VCDUtils::getUserID(), metadataTypeObj::SYS_DVDREGION, $dvdSettings['region']));
							$metaObj->setMediaTypeID($mediaTypeID);
							$obj->addMetaData($metaObj);
							$metaObj = new metadataObj(array('', '', VCDUtils::getUserID(), metadataTypeObj::SYS_DVDFORMAT , $dvdSettings['format']));
							$metaObj->setMediaTypeID($mediaTypeID);
							$obj->addMetaData($metaObj);
							$metaObj = new metadataObj(array('', '', VCDUtils::getUserID(), metadataTypeObj::SYS_DVDASPECT , $dvdSettings['aspect']));
							$metaObj->setMediaTypeID($mediaTypeID);
							$obj->addMetaData($metaObj);
							$metaObj = new metadataObj(array('', '', VCDUtils::getUserID(), metadataTypeObj::SYS_DVDAUDIO , $dvdSettings['audio']));
							$metaObj->setMediaTypeID($mediaTypeID);
							$obj->addMetaData($metaObj);
							$metaObj = new metadataObj(array('', '', VCDUtils::getUserID(), metadataTypeObj::SYS_DVDSUBS , $dvdSettings['subs']));
							$metaObj->setMediaTypeID($mediaTypeID);
							$obj->addMetaData($metaObj);
						}
					}
				}
			}


		} catch (Exception $ex) {
			throw $ex;
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
			
		} catch (Exception $ex) {
			throw $ex;
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

		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Get an instance of the vcd_settings class
	 *
	 * @return vcd_settings
	 */
	private function Settings() {
		return VCDClassFactory::getInstance('vcd_settings');
	}
	
	/**
	 * Get an instance of the vcd_pornstars class
	 *
	 * @return vcd_pornstar
	 */
	private function Pornstar() {
		return VCDClassFactory::getInstance('vcd_pornstar');
	}
	
	/**
	 * Get an instance of the vcd_cover class
	 *
	 * @return vcd_cdcover
	 */
	private function Cover() {
		return VCDClassFactory::getInstance('vcd_cdcover');
	}
	
}

?>