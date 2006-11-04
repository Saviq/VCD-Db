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
 * @subpackage Pornstars
 * @version $Id$
 */
 ?>
<? 
require_once(dirname(__FILE__).'/pornstar.php');
require_once(dirname(__FILE__).'/pornstarSQL.php');

interface IPornstar {
	
	/*  Functions for pornstars */
	public function getAllPornstars();
	public function getPornstarByID($pornstar_id);
	public function getPornstarByName($pornstar_name);
	public function getPornstarsByMovieID($movie_id);
	public function addPornstar(pornstarObj $pornstarObj);
	public function addPornstarToMovie($pornstar_id, $movie_id);
	public function deletePornstarFromMovie($pornstar_id, $movie_id);
	public function updatePornstar(pornstarObj $pornstar);
	public function getPornstarsAlphabet($active_only);
	public function getPornstarsByLetter($letter, $active_only);
	
	
	/* Functions for adult studios */
	public function getAllStudios();
	public function getStudioByID($studio_id);
	public function getStudioByName($studio_name);
	public function getStudioByMovieID($vcd_id);
	public function getStudiosInUse();
	public function addMovieToStudio($studio_id, $vcd_id);
	public function deleteMovieFromStudio($vcd_id);
	public function addStudio(studioObj $obj);
	public function deleteStudio($studio_id);
	
	/* Subcategories */
	public function getSubCategories();
	public function getSubCategoryByID($category_id);
	public function getSubCategoriesByMovieID($vcd_id);
	public function getSubCategoriesInUse();
	public function getValidCategories($arrCategoryNames);
	public function addCategoryToMovie($vcd_id, $category_id);
	public function deleteMovieFromCategories($vcd_id);
	public function addAdultCategory(porncategoryObj $obj);
	public function deleteAdultCategory($category_id);
	
}

?>