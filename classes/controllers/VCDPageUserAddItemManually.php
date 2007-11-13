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
 * @subpackage Controller
 * @version $Id: VCDPageUserAddItemManually.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php
class VCDPageUserAddItemManually extends VCDBasePage {
	
	public function __construct(_VCDPageNode $node) {

		parent::__construct($node);
		
		$this->doInitPage();
	
	}
	
	
	public function handleRequest() {
	
		if (strcmp($this->getParam('action'),"add")==0) {
			$this->addItem();		
		}
	
	}
	
	
	/**
	 * Initialize the item selection.
	 *
	 */
	private function doInitPage() {
	
		// Set the movie category
		$results = array();
		
		$categories = SettingsServices::getAllMovieCategories();
		foreach ($categories as $categoryObj) {
			$results[$categoryObj->getId()] = $categoryObj->getName(true);
		}
		$results[null] = VCDLanguage::translate('misc.select');
		asort($results);
		$results = array(null => VCDLanguage::translate('misc.select')) + $results;
		$this->assign('itemCategoryList',$results);
				
		
		
		// Set the mediaType list
		$results = array();
		$results[null] = VCDLanguage::translate('misc.select');
		
		foreach (SettingsServices::getAllMediatypes() as $mediaTypeObj) {
			$results[$mediaTypeObj->getmediaTypeID()] = $mediaTypeObj->getDetailedName();
			if ($mediaTypeObj->getChildrenCount() > 0) {
				foreach ($mediaTypeObj->getChildren() as $childObj) { 
					$results[$childObj->getmediaTypeID()] = '&nbsp;&nbsp;'.$childObj->getDetailedName();
				}
			}
		}
		
		$this->assign('mediatypeList', $results);
		
		
		
		// Set the number of cd's list
		$results = array();
		$results[null] = VCDLanguage::translate('misc.select');
		for($i=1;$i<11;$i++) {
			$results[$i] = $i;
		}
		$this->assign('cdList',$results);
		
		
		// Set the year list
		$results = array();
		for ($i = date("Y"); $i >= 1900; $i--) {
			$results[$i] = $i;
		}
		$this->assign('yearList',$results);
		
		
	}
	
	
	/**
	 * Add submitted entry to database
	 *
	 */
	private function addItem() {
		try {
			
			// Create the basic CD obj
			$basic = array('', $this->getParam('title',true), $this->getParam('category',true), $this->getParam('year',true));
			$vcd = new vcdObj($basic);
			
			// Add 1 instance
			$vcd->addInstance(VCDUtils::getCurrentUser(), SettingsServices::getMediaTypeByID($this->getParam('mediatype',true)), 
				$this->getParam('cds',true), mktime());
			
			// If file was uploaded .. lets process it ..
			// Set the allowed extensions for the upload
			$arrExt = array(VCDUploadedFile::FILE_JPEG, VCDUploadedFile::FILE_JPG, VCDUploadedFile::FILE_GIF);
			$VCDUploader = new VCDFileUpload($arrExt);
	
			if ($VCDUploader->getFileCount() == 1) {
				try {
	
					$fileObj = $VCDUploader->getFileAt(0);
	
					// Move the file to the TEMP Folder
					$fileObj->move(TEMP_FOLDER);
					// Get the full path including filename after it has been moved
					$fileLocation = $fileObj->getFileLocation();
					$fileExtension = $fileObj->getFileExtenstion();
					
		  	   		$im = new Image_Toolbox($fileLocation);
					$im->newOutputSize(0,140);
					$im->save(TEMP_FOLDER.$fileObj->getFileName(), $fileExtension);
	
					
				  	$cover = new cdcoverObj();
					// Get a Thumbnail CoverTypeObj
					$coverTypeObj = CoverServices::getCoverTypeByName("thumbnail");
					$cover->setCoverTypeID($coverTypeObj->getCoverTypeID());
					$cover->setCoverTypeName("thumbnail");
					$cover->setFilename($fileObj->getFileName());
					$vcd->addCovers(array($cover));
	
					// CleanUp
					unset($im);
	
	
				} catch (Exception $ex) {
					throw $ex;
				}
			}
	
	
			// Forward the movie to the Business layer
			$new_id = MovieServices::addVcd($vcd);
			
	
			// Insert the user comments if any ..
			$comment = $this->getParam('comment',true);
			if (!is_null($comment)) {
				$is_private = $this->getParam('private',true,0);
	
				$commObj = new commentObj(array('', $new_id, VCDUtils::getUserID(), '', VCDUtils::stripHTML($comment), $is_private));
				SettingsServices::addComment($commObj);
			}
	
			
			redirect('?page=cd&vcd_id='.$new_id);
			exit();
			
		} catch (Exception $ex) {
			VCDException::display($ex,true);
		}
	}
	
	
}

?>