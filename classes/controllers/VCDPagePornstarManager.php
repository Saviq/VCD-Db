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
 * @version $Id: VCDPagePornstarManager.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php
class VCDPagePornstarManager extends VCDBasePage {
	
	/**
	 * The pornstarObj being used
	 *
	 * @var pornstarObj
	 */
	private $pornstarObj = null;
	
	public function __construct(_VCDPageNode $node) {
		try {
			
			parent::__construct($node);
	
			$this->registerScript(self::$JS_MAIN);
			$this->registerScript(self::$JS_LANG);
			
			$pornstarObj = PornstarServices::getPornstarByID($this->getParam('pornstar_id'));
			if (!$pornstarObj instanceof pornstarObj ) {
				throw new VCDProgramException('Invalid pornstar Id.');
			}
			$this->pornstarObj = $pornstarObj;
			$this->doPornstar();
				
			// Check for _GET requests
			$this->doGet();
				
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
		
	
	/**
	 * Handle GET requests to the controller
	 *
	 */
	private function doGet() {
		$action = $this->getParam('action');
		switch ($action) {
			case 'deleteimage':
				unlink(VCDDB_BASE.DIRECTORY_SEPARATOR.PORNSTARIMAGE_PATH.$this->pornstarObj->getImageName());
				$this->pornstarObj->setImageName('');
				PornstarServices::updatePornstar($this->pornstarObj);
				redirect('?page=pornstarmanager&pornstar_id='.$this->pornstarObj->getID());
				break;
			
			case 'fetchimage':
				$this->fetchRemoteImage();
				redirect('?page=pornstarmanager&pornstar_id='.$this->pornstarObj->getID());
				break;
				
			default:
				break;
		}
	}
	
	
	/**
	 * Fetch image from remote website and use it as a pornstar thumbnail image
	 *
	 */
	private function fetchRemoteImage() {
		try {
			
			$fileLocation = $this->getParam('path');
			if (is_null($fileLocation)) {
				throw new VCDInvalidInputException('Remote file location is not set.');
			}
			
			$imageName = VCDUtils::grabImage($fileLocation);
			
			if (strlen($imageName) > 3) {
				$im = new Image_Toolbox(VCDDB_BASE.DIRECTORY_SEPARATOR.TEMP_FOLDER.$imageName);
				$im->newOutputSize(0,200);
				$im->save(TEMP_FOLDER.$imageName, 'jpg');
			}
					
			
			if (rename(VCDDB_BASE.DIRECTORY_SEPARATOR.TEMP_FOLDER.$imageName, VCDDB_BASE.DIRECTORY_SEPARATOR.PORNSTARIMAGE_PATH.$imageName)) {
				// Success ...
				$this->pornstarObj->setImageName($imageName);
				PornstarServices::updatePornstar($this->pornstarObj);
			} else {
				throw new VCDException('Could not write image to disk.');
			}
			
		} catch (Exception $ex) {
			VCDException::display($ex,true);
		}
	}
	
	
	/**
	 * Handle _POST request to the controller
	 *
	 */
	public function handleRequest() {
		try {
			
			$action = $this->getParam('action');
			
			if (is_null($this->pornstarObj)) {
				$pornstarObj = PornstarServices::getPornstarByID($this->getParam('pornstar_id'));
				if (!$pornstarObj instanceof pornstarObj ) {
					throw new VCDProgramException('Invalid pornstar Id.');
				}
				$this->pornstarObj = $pornstarObj;
			}
			
			if (strcmp($action, 'update')==0) {
				$this->updatePornstar();
			}
		
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	/**
	 * Update the pornstar instance
	 *
	 */
	private function updatePornstar() {
		
		// Set the basic data ..
		$this->pornstarObj->setName($this->getParam('name',true));
		$this->pornstarObj->setHomePage($this->getParam('www',true));
		$this->pornstarObj->setBiography($this->getParam('bio',true));
		
		// Check for uploaded file ..
		$this->handleImageUpload();
				
		// Update the pornstar
		PornstarServices::updatePornstar($this->pornstarObj);
		
		redirect('?page=pornstarmanager&pornstar_id='.$this->pornstarObj->getId());
	}
	
	/**
	 * Handle uploaded image
	 *
	 */
	private function handleImageUpload() {
		// Set the allowed extensions for the upload
		$arrExt = array(VCDUploadedFile::FILE_JPEG, VCDUploadedFile::FILE_JPG, VCDUploadedFile::FILE_GIF);
		$VCDUploader = new VCDFileUpload($arrExt);

		if ($VCDUploader->getFileCount() == 1) {
			try {

				$fileObj = $VCDUploader->getFileAt(0);

				// Move the file to the TEMP Folder
				$fileObj->move(VCDDB_BASE.DIRECTORY_SEPARATOR.TEMP_FOLDER);
				// Get the full path including filename after it has been moved
				$fileLocation = $fileObj->getFileLocation();
				$fileExtension = $fileObj->getFileExtenstion();

				// Check if image should be resized
		      	if (!is_null($this->getParam('resize',true))) {
		  	   		$im = new Image_Toolbox($fileLocation);
					$im->newOutputSize(0,200);
					$im->save(VCDDB_BASE.DIRECTORY_SEPARATOR.PORNSTARIMAGE_PATH.$fileObj->getFileName(), $fileExtension);
					$fileObj->delete();
		      	} else {
		    		fs_rename($fileObj->getFileLocation(), VCDDB_BASE.DIRECTORY_SEPARATOR.PORNSTARIMAGE_PATH.$fileObj->getFileName());
		      	}

		      	$this->pornstarObj->setImageName($fileObj->getFileName());

				// CleanUp
				unset($im);

			} catch (Exception $ex) {
				throw $ex;
			}
		}
	}
	
	
	/**
	 * Assign the pornstar data
	 *
	 */
	private function doPornstar() {
		
		$this->assign('pornstarId', $this->pornstarObj->getID());
		$this->assign('pornstarName', $this->pornstarObj->getName());
		$this->assign('pornstarHomepage', $this->pornstarObj->getHomepage());
		$this->assign('pornstarBiography', $this->pornstarObj->getBiography());
		if (strcmp($this->pornstarObj->getImageName(),'')!=0) {
			$this->assign('pornstarImage', $this->pornstarObj->getImageLink());
		}
		
	}
	
	
}
?>