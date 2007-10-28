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
 * @subpackage Pornstars
 * @version $Id$
 */
 ?>
<?php

require_once(dirname(__FILE__).'/pornstarObj.php');
require_once(dirname(__FILE__).'/studioObj.php');
require_once(dirname(__FILE__).'/porncategoryObj.php');

class vcd_pornstar implements IPornstar {
	
	/**
	 * Instance of pornstarSQL database class
	 *
	 * @var pornstarSQL
	 */
	private $SQL;
	
	 /**
	  * Constructor
	  *
	  */
	public function __construct() { 
		$this->SQL = new pornstarSQL();
	} 

   
	/**
	 * Get an array with all pornstar objects in database
	 *
	 * @return array
	 */
	public function getAllPornstars() {
		try {
   			
			return $this->SQL->getAllPornstars();
   			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
   
   
	/**
	 * Get a pornstar object by ID
	 *
	 * @param int $pornstar_id
	 * @return pornstarObj
	 */
	public function getPornstarByID($pornstar_id) {
		try {
			if (!is_numeric($pornstar_id)) {
				throw new VCDInvalidArgumentException('Pornstar Id must be numeric');
			}
			
			return $this->SQL->getPornstarByID($pornstar_id);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Update a pornstar object
	 *
	 * @param pornstarObj $pornstar
	 */
	public function updatePornstar(pornstarObj $pornstar) {
		try {
			
			$this->SQL->updatePornstar($pornstar);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get a pornstar object by pornstar name
	 *
	 * @param string $pornstar_name
	 * @return pornstarObj
	 */
	public function getPornstarByName($pornstar_name) {
		try 
		{
			return $this->SQL->getPornstarByName($pornstar_name);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Get an array of all pornstar objects that are linked to the specified movie.
	 *
	 * @param int $movie_id
	 * @return array
	 */
	public function getPornstarsByMovieID($movie_id) {
		try {
			
			return $this->SQL->getPornstarsByMovieID($movie_id);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Adds new pornstar object to database, returns the same object with the new id.
	 *
	 * @param pornstarObj $pornstarObj
	 * @return pornstarObj
	 */
	public function addPornstar(pornstarObj $pornstarObj) {
		try {
			
			if (is_null($pornstarObj->getName())) {
				throw new VCDInvalidArgumentException('Pornstar name cannot be empty');
			}
			
			// Check for duplicates ..
			$existingObj = $this->getPornstarByName($pornstarObj->getName());
			if ($existingObj instanceof pornstarObj ) {
				return $existingObj;
			}
			
			
			$new_id = $this->SQL->addPornstar($pornstarObj);
			$newPornstarsObj = $this->getPornstarByID($new_id);
			
			
			if ($newPornstarsObj instanceof pornstarObj) {
				return $newPornstarsObj;
			} else {
				// Bug in Postgres, sometimes entry not fount within same transaction ..
				$pornstarObj->setID($new_id);
				return $pornstarObj;
			}
						
		} catch (Exception $ex) {
			throw $ex;
		}
	}

	
	/**
	 * Link pornstar and movie together
	 *
	 * @param int $pornstar_id
	 * @param int $movie_id
	 */
	public function addPornstarToMovie($pornstar_id, $movie_id) {
		try {
			
			if (!(is_numeric($pornstar_id) && is_numeric($movie_id))) {
				throw new VCDInvalidArgumentException('Params must be numeric');
			}
				
			$this->SQL->addPornstarToMovie($pornstar_id, $movie_id);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Delete a pornstar from the movie cast list
	 *
	 * @param int $pornstar_id
	 * @param int $movie_id
	 */
	public function deletePornstarFromMovie($pornstar_id, $movie_id) {
		try {
			
			if (!(is_numeric($pornstar_id) && is_numeric($movie_id))) {
				throw new VCDInvalidArgumentException('Params must be numeric');
			}
				
			$this->SQL->deletePornstarFromMovie($pornstar_id, $movie_id);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	public function deletePornstar($pornstar_id) {
		try {
			
			if (!is_numeric($pornstar_id)) {
				throw new VCDInvalidArgumentException('Pornstar Id must be numeric');
			}
			
			$pornstarObj = $this->getPornstarByID($pornstar_id);
			
			if (!$pornstarObj instanceof pornstarObj ) {
				throw new VCDInvalidArgumentException('Invalid pornstar Id');
			}
			
			if ($pornstarObj->getMovieCount() > 0) {
				throw new VCDConstraintException("Cannot delete pornstar, pornstar is linked to {$pornstarObj->getMovieCount()} movies");
			} else {
				$this->SQL->deletePornstar($pornstar_id);
				return true;
			}
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/* Functions for adult studios */
	
	/**
	 * Get an array with all adult studio objects in database
	 *
	 * @return array
	 */
	public function getAllStudios() {
		try {
			
			return $this->SQL->getAllStudios();
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get all adult studio objects in database that have any movies associated. 
	 * Returns array of studio objects.
	 *
	 * @return array
	 */
	public function getStudiosInUse() {
		try {
			
			return $this->SQL->getStudiosInUse();
			
		} catch(Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get a studio object by ID
	 *
	 * @param int $studio_id
	 * @return studioObj
	 */
	public function getStudioByID($studio_id) {
		try {
			
			if (!is_numeric($studio_id)) {
				throw new VCDInvalidArgumentException('Studio Id must be numeric');
			}
			
			return $this->SQL->getStudioByID($studio_id);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Get studio object name studio name
	 *
	 * @param string $studio_name
	 * @return studioObj
	 */
	public function getStudioByName($studio_name) {
		try {
			
			return $this->SQL->getStudioByName($studio_name);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Get the adult studio that is associated with the movie with the following ID.
	 *
	 * @param int $vcd_id
	 * @return studioObj
	 */
	public function getStudioByMovieID($vcd_id) {
		try {

			if (!is_numeric($vcd_id)) {
				throw new VCDInvalidArgumentException('Vcd Id must be numeric');
			}
				
			return $this->SQL->getStudioByMovieID($vcd_id);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Link adult studio and movie together
	 *
	 * @param int $studio_id
	 * @param int $vcd_id
	 */
	public function addMovieToStudio($studio_id, $vcd_id) {
		try {
			
			if (is_numeric($studio_id) && is_numeric($vcd_id)) {
				$this->SQL->addMovieToStudio($studio_id, $vcd_id);
			} 
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Unlink movie from adult studio
	 *
	 * @param int $vcd_id
	 */
	public function deleteMovieFromStudio($vcd_id) {
		try {
		
			if (!is_numeric($vcd_id)) {
				throw new VCDInvalidArgumentException('Vcd Id must be numeric');
			}	
			
			$this->SQL->deleteMovieFromStudio($vcd_id);
		
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Add a new adult studio to database.
	 *
	 * @param studioObj $obj
	 */
	public function addStudio(studioObj $obj) {
		try {
			
			$this->SQL->addStudio($obj);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Delete studio from database.
	 *
	 * @param int $studio_id
	 */
	public function deleteStudio($studio_id) {
		try {
			if (!is_numeric($studio_id)) {
				throw new VCDInvalidArgumentException('Studio Id must be numeric');
			}	
			
			// Check if any movies are using this studio
			$CLASSVcd = VCDClassFactory::getInstance('vcd_movie');
			$arrMovies = $CLASSVcd->getVcdByAdultStudio($studio_id);
			if (is_array($arrMovies) && sizeof($arrMovies) > 0) {
				throw new VCDConstraintException('Cannot delete studios already linked to movies.');
			} else {
				$this->SQL->deleteStudio($studio_id);
			}
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/* Adult subcategories  */
	/**
	 * Get array of all porncategory objects in database
	 *
	 * @return array
	 */
	public function getSubCategories() {
		try {
		
			return $this->SQL->getSubCategories();
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Get an array of all porncategory objects that have any movies linked to it.
	 *
	 * @return array
	 */
	public function getSubCategoriesInUse() {
		try {
			
			return $this->SQL->getSubCategoriesInUse();
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get porncategory object by ID
	 *
	 * @param int $category_id
	 * @return porncategoryObj
	 */
	public function getSubCategoryByID($category_id) {
		try {
				
			if (!is_numeric($category_id)) {
				throw new VCDInvalidArgumentException('Category Id must be numeric');
			}
				
			return $this->SQL->getSubCategoryByID($category_id);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get array of all porncategory objects linked to this movie
	 *
	 * @param int $vcd_id
	 * @return array
	 */
	public function getSubCategoriesByMovieID($vcd_id) {
		try {
			
			if (!is_numeric($vcd_id)) {
				throw new VCDInvalidArgumentException('Vcd Id must be numeric');
			}
			
			return $this->SQL->getSubCategoriesByMovieID($vcd_id);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Link movie to porncategory
	 *
	 * @param int $vcd_id
	 * @param int $category_id
	 */
	public function addCategoryToMovie($vcd_id, $category_id) {
		try {
		
			if (!(is_numeric($vcd_id) && is_numeric($category_id))) {
				throw new VCDInvalidArgumentException('Params must be numeric');
			} 
			
			$this->SQL->addCategoryToMovie($vcd_id, $category_id);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Remove all linked porncategories from movie with specified ID
	 *
	 * @param int $vcd_id
	 */
	public function deleteMovieFromCategories($vcd_id) {
		try {
		
			if (!is_numeric($vcd_id)) {
				throw new VCDInvalidArgumentException('Vcd Id must be numeric');
			}
				
			$this->SQL->deleteMovieFromCategories($vcd_id);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get all valid porncategories.
	 * Checks all elements in the incoming array for valid porn category names in database,
	 * for each element found, porncategory object is added to the return array.
	 *
	 * @param array $arrCategoryNames
	 * @return array
	 */
	public function getValidCategories($arrCategoryNames) {
		try {
			
			if (is_array($arrCategoryNames) && sizeof($arrCategoryNames) > 0) {
				$returnArr = array();			
				$allCategories = $this->getSubCategories();
				foreach ($arrCategoryNames as $category_name) {
					foreach ($allCategories as $porncatObj) {
						if (strcmp(strtolower($category_name), strtolower($porncatObj->getName())) == 0) {
							array_push($returnArr, $porncatObj);
						}
					}
				}
				unset($arrCategoryNames);
				unset($allCategories);
				return $returnArr;
			}
		
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Get pornstars by Alphabet letter.
	 * Get an array of chars with each char representing a beginning of a letter of a known pornstar
	 * Parameter active only can limit the results to only those that are linked to a 
	 * movie in the database.
	 *
	 * @param bool $active_only
	 * @return array
	 */
	public function getPornstarsAlphabet($active_only) {
		try {
			
			return $this->SQL->getPornstarsAlphabet($active_only);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Get an array with all pornstar objects that begin with their names with the specified letter.
	 *
	 * @param char $letter
	 * @param bool $active_only
	 * @return array
	 */
	public function getPornstarsByLetter($letter, $active_only) {
		try {
			
			if (strlen($letter) != 1) {
				throw new VCDInvalidArgumentException('Letter should only contain 1 letter.');
			}
			
			if(!eregi("^[a-zA-Z ]+$", $letter))  {
				throw new VCDInvalidArgumentException('Only alphabetical characters can be used.');
			}

			return $this->SQL->getPornstarsByLetter($letter, $active_only);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Add a new adult category
	 *
	 * @param porncategoryObj $obj
	 */
	public function addAdultCategory(porncategoryObj $obj) {
		try {
			
			$this->SQL->addAdultCategory($obj);
			
		} catch (Exception $ex) {
			throw $ex;
		} 
	}
	
	
	
	/**
	 * Delete adult category
	 *
	 * @param int $category_id
	 */
	public function deleteAdultCategory($category_id) {
		try {
			if (!is_numeric($category_id)) {
				throw new VCDInvalidArgumentException('Category Id must be numeric');
			}
				
			// Check if category is in use ..
			$CLASSVcd = VCDClassFactory::getInstance('vcd_movie');
			$arrMovies = $CLASSVcd->getVcdByAdultCategory($category_id);
			if (is_array($arrMovies) && sizeof($arrMovies) > 0) {
				throw new VCDConstraintException('Cannot delete active category');
			} else {
				$this->SQL->deleteAdultCategory($category_id);
			}
				
				
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
}




?>