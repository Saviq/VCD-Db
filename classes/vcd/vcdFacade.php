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
<?PHP
	
	require_once(dirname(__FILE__).'/vcd.php');
	require_once(dirname(__FILE__).'/vcdSQL.php');
	
	interface IVcd {
		
		public function getVcdByID($vcd_id);
		public function addVcd(vcdObj $vcdObj);
		public function updateVcd(vcdObj $vcdObj);
		public function updateVcdInstance($vcd_id, $new_mediaid, $old_mediaid, $new_numcds, $oldnumcds);
		public function deleteVcdFromUser($vcd_id, $media_id, $mode, $user_id = -1);
		public function getVcdByCategory($category_id, $start=0, $end=0, $user_id = -1);
		public function getVcdByCategoryFiltered($category_id, $start=0, $end=0, $user_id);
		public function getAllVcdByUserId($user_id, $simple = true);
		public function getLatestVcdsByUserID($user_id, $count, $simple = true);
		public function getAllVcdForList($excluded_userid);
		public function getVcdForListByIds($arrIDs);
		public function addVcdToUser($user_id, $vcd_id, $mediatype, $cds);
		
		public function getCategoryCount($category_id, $isAdult = false, $user_id = -1);
		public function getCategoryCountFiltered($category_id, $user_id);
		public function getTopTenList($category_id = 0);
		public function search($keyword, $method);
		public function advancedSearch($title = null, $category = null, $year = null, $mediatype = null,
									   $owner = null, $imdbgrade = null);
		public function crossJoin($user_id, $media_id, $category_id, $method);							
		public function getPrintViewList($user_id, $list_type);
		public function getRandomMovie($category, $use_seenlist = false);
		public function getSimilarMovies($vcd_id);
		public function getMovieCount($user_id);
		
		/* Adult VCD functions */
		public function getVcdByAdultCategory($category_id);
		public function getVcdByAdultStudio($studio_id);
		public function markVcdWithScreenshots($vcd_id);
		public function getScreenshots($vcd_id);
		
	
	}



?>