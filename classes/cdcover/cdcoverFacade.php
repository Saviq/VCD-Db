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
 * @package Kernel
 * @subpackage CDCover
 * @version $Id$
 */
 
?>
<?PHP
	require_once("cdcover.php");
	require_once("cdcoverSQL.php");
	

	interface ICdcover {
		
		public function getAllCoverTypes();
		public function addCoverType($cdcoverTypeObj);
		public function deleteCoverType($type_id);
		public function getAllCoverTypesForVcd($mediatype_id);
		public function getCoverTypeById($covertype_id);
		public function getCoverTypeByName($covertype_name);
		public function updateCoverType($cdcoverTypeObj);
		
		public function getCoverById($cover_id);
		public function getAllCoversForVcd($vcd_id);
		public function addCover($cdcoverObj);
		public function deleteCover($cover_id);
		public function updateCover($cdcoverObj);
		public function getAllowedCoversForVcd($mediaTypeObjArr);
		
		public function addCoverTypesToMedia($mediaTypeID, $coverTypeIDArr);
		public function getCDcoverTypesOnMediaType($mediaType_id);
		
		public function getAllThumbnailsForXMLExport($user_id);
			
	
	}



?>