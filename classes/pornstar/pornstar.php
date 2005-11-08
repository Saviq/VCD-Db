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
 * @package Porndata
 * @version $Id$
 */
 ?>
<? 

require_once("pornstarObj.php");
require_once('studioObj.php');
require_once('porncategoryObj.php');

class vcd_pornstar implements Pornstar {
	
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
   		} catch (Exception $e) {
   			VCDException::display($e);
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
				throw new Exception("ID must be numeric");
				return false;
			}
			
			return $this->SQL->getPornstarByID($pornstar_id);
			
		} catch (Exception $e) {
			VCDException::display($e);
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
		} catch (Exception $e) {
			VCDException::display($e);
		}
	}
	
	/**
	 * Get a pornstar object by pornstar name
	 *
	 * @param string $pornstar_name
	 * @return pornstarObj
	 */
	public function getPornstarByName($pornstar_name) {
		try {
			return $this->SQL->getPornstarByName($pornstar_name);
		} catch (Exception $e) {
			VCDException::display($e);
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
		} catch (Exception $e) {
			VCDException::display($e);
		}
	}
	
	/**
	 * Adds new pornstar object to database.
	 *
	 * Returns the same object with the new id
	 *
	 * @param pornstarObj $pornstarObj
	 * @return pornstarObj
	 */
	public function addPornstar(pornstarObj $pornstarObj) {
		try {
			$new_id =  $this->SQL->addPornstar($pornstarObj);
			return $this->getPornstarByID($new_id);
			
			
		} catch (Exception $e) {
			VCDException::display($e);
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
			if (is_numeric($pornstar_id) && is_numeric($movie_id)) {
				$this->SQL->addPornstarToMovie($pornstar_id, $movie_id);
			} else {
				throw new Exception("Parameters must be numeric");
			}
		} catch (Exception $e) {
			VCDException::display($e);
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
			if (is_numeric($pornstar_id) && is_numeric($movie_id)) {
				$this->SQL->deletePornstarFromMovie($pornstar_id, $movie_id);
			} else {
				throw new Exception("Parameters must be numeric");
			}
		} catch (Exception $e) {
			VCDException::display($e);
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
			
		} catch (Exception $e) {
			VCDException::display($e);
		}
	}
	
	/**
	 * Get all adult studio objects in database that have any movies associated. 
	 *
	 * Returns array of studio objects.
	 *
	 * @return array
	 */
	public function getStudiosInUse() {
		try {
			
			return $this->SQL->getStudiosInUse();
			
		} catch(Exception $e) {
			VCDException::display($e);
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
				throw new Exception("Studio ID must be numeric");
			}
			return $this->SQL->getStudioByID($studio_id);
			
		} catch (Exception $e) {
			VCDException::display($e);
			return null;
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
		} catch (Exception $e) {
			VCDException::display($e);
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
			if (is_numeric($vcd_id)) {
				return $this->SQL->getStudioByMovieID($vcd_id);
			} else {
				throw new Exception("Parameter must be numeric");
			}
		} catch (Exception $e) {
			VCDException::display($e);
			return null;
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
		} catch (Exception $e) {
			VCDException::display($e);
		}
	}
	
	/**
	 * Unlink movie from adult studio
	 *
	 * @param int $vcd_id
	 */
	public function deleteMovieFromStudio($vcd_id) {
		try {
		
			if (is_numeric($vcd_id)) {
				$this->SQL->deleteMovieFromStudio($vcd_id);
			} else {
				throw new Exception("Parameter must be numeric");
			}
		
		} catch (Exception $e) {
			VCDException::display($e);
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
		} catch (Exception $e) {
			VCDException::display($e);
		}
	}
	
	
	/**
	 * Delete studio from database.
	 *
	 * @param int $studio_id
	 */
	public function deleteStudio($studio_id) {
		try {
			if (is_numeric($studio_id)) {
				
				// Check if any movies are using this studio
				$CLASSVcd = new vcd_movie();
				$arrMovies = $CLASSVcd->getVcdByAdultStudio($studio_id);
				if (is_array($arrMovies) && sizeof($arrMovies) > 0) {
					throw new Exception("Cannot delete active studio in use.");
				} else {
					$this->SQL->deleteStudio($studio_id);
				}
				
				
			} else {
				throw new Exception("studio id must be numeric");
			}
		} catch (Exception $e) {
			VCDException::display($e);
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
			
		} catch (Exception $e) {
			VCDException::display($e);
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
		} catch (Exception $e) {
			VCDException::display($e);
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
				
			if (is_numeric($category_id)) {
				return $this->SQL->getSubCategoryByID($category_id);
			} else {
				throw new Exception("Parameter must be numeric");
			}
			
		} catch (Exception $e) {
			VCDException::display($e);
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
				throw new Exception("Parameter must be numeric");
			}
			
			return $this->SQL->getSubCategoriesByMovieID($vcd_id);
			
		} catch (Exception $e) {
			VCDException::display($e);
			return null;
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
		
			if (is_numeric($vcd_id) && is_numeric($category_id)) {
				$this->SQL->addCategoryToMovie($vcd_id, $category_id);
			} else {
				throw new Exception("Parameters must be numeric");
			}
			
		} catch (Exception $e) {
			VCDException::display($e);
		}
	}
	
	
	/**
	 * Remove all linked porncategories from movie with specified ID
	 *
	 * @param int $vcd_id
	 */
	public function deleteMovieFromCategories($vcd_id) {
		try {
		
			if (is_numeric($vcd_id)) {
				$this->SQL->deleteMovieFromCategories($vcd_id);
			} else {
				throw new Exception("Parameter must be numeric");
			}
			
		} catch (Exception $e) {
			VCDException::display($e);
		}
	}
	
	/**
	 * Get all valid porncategories.
	 *
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
		
		} catch (Exception $e) {
			VCDException::display($e);
		}
	}
	
	
	/**
	 * Get pornstars by Alphabet letter.
	 *
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
		} catch (Exception $e) {
			VCDException::display($e);
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
				throw new Exception('Letter should only contain 1 letter.');
			}
			
			if(!eregi("^[a-zA-Z ]+$", $letter))  {
				throw new Exception('Only alphabetical characters will do.');
			}
			
			
			return $this->SQL->getPornstarsByLetter($letter, $active_only);
			
		} catch (Exception $e) {
			VCDException::display($e);
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
			
		} catch (Exception $e) {
			VCDException::display($e);
		} 
	}
	
	
	
	/**
	 * Delete adult category
	 *
	 * @param int $category_id
	 */
	public function deleteAdultCategory($category_id) {
		try {
			if (is_numeric($category_id)) {
				
				// Check if category is in use ..
				$CLASSVcd = new vcd_movie();
				$arrMovies = $CLASSVcd->getVcdByAdultCategory($category_id);
				if (is_array($arrMovies) && sizeof($arrMovies) > 0) {
					throw new Exception("Cannot delete active category");	
				} else {
					$this->SQL->deleteAdultCategory($category_id);
				}
				
				
			} else {
				throw new Exception("category_id must be numeric");
			}
		} catch (Exception $e) {
			VCDException::display($e);
		}
	}
	
}




?>