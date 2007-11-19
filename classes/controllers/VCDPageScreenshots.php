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
 * @version $Id: VCDPageScreenshots.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php
class VCDPageScreenshots extends VCDBasePage  {
	
	/**
	 * The movie object being worked with
	 *
	 * @var cdObj
	 */
	private $itemObj = null;
	
	
	public function __construct(_VCDPageNode $node) {
		try {
			
			parent::__construct($node);
			$this->registerScript(self::$JS_MAIN);
	
			$this->itemObj = MovieServices::getVcdByID($this->getParam('vcd_id'));
			$this->assign('itemTitle', $this->itemObj->getTitle());
			$this->assign('itemId',$this->itemObj->getID());	
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	
	/**
	 * Handle $_POST actions to the controller
	 *
	 */
	public function handleRequest() {
		
		if (is_null($this->itemObj)) {
			$this->itemObj = MovieServices::getVcdByID($this->getParam('vcd_id'));
		}
		
		$action = $this->getParam('action');
		
		switch ($action) {
			case 'upload':
				$this->uploadImages();
				// reload and close upon success
				redirect('?page=addscreens&vcd_id='.$this->itemObj->getID().'&close=true');
				break;
				
			case 'fetch':
				$this->fetchImages();
				// reload and close upon success
				redirect('?page=addscreens&vcd_id='.$this->itemObj->getID().'&close=true');
				break;
		
			default:
				redirect();
				break;
		}
	}
	
	
	/**
	 * Fetch the requested remote images
	 *
	 */
	private function fetchImages() {
		try {
			
			$images = $this->getParam('fetcher',true);
			$images = explode(chr(13), $images);
			if (sizeof($images > 0)) {

				if (sizeof($images)==1 && $images[0]=='') {
					throw new VCDInvalidInputException('Invalid input!');
				}
				
				// Check if the screenshots folder already exist
				$destFolder = VCDDB_BASE.DIRECTORY_SEPARATOR.ALBUMS.$this->itemObj->getID().DIRECTORY_SEPARATOR;
				if (!$this->itemObj->hasScreenshots()) {
					if (!fs_is_dir($destFolder)) {
						fs_mkdir($destFolder, 0755);
					}
				}
				
				foreach ($images as $image) {
					VCDUtils::grabImage(trim($image), false, $destFolder);
				}
				
				
				if (!MovieServices::getScreenshots($this->itemObj->getID())) {
					MovieServices::markVcdWithScreenshots($this->itemObj->getID());
				}
				
				
			}
			
		} catch (Exception $ex) {
			VCDException::display($ex,true);
		}
	}
	
	/**
	 * Handle uploaded images
	 *
	 */
	private function uploadImages() {
		try {
			
			$upload = new VCDFileUpload(array(VCDUploadedFile::FILE_GIF , VCDUploadedFile::FILE_JPG , VCDUploadedFile::FILE_JPEG ));
					
			// Check if the screenshots folder already exist
			$destFolder = VCDDB_BASE.DIRECTORY_SEPARATOR.ALBUMS.$this->itemObj->getID();
			if (($upload->getFileCount() > 0) && !$this->itemObj->hasScreenshots()) {
				if (!fs_is_dir($destFolder)) {
					fs_mkdir($destFolder, 0755);
				}
			}
			
			
			for ($i=0; $i<$upload->getFileCount();$i++) {
				$file = $upload->getFileAt($i);
				$file->move($destFolder);
			}
			
			if ($upload->getFileCount() > 0 && !MovieServices::getScreenshots($this->itemObj->getID())) {
				MovieServices::markVcdWithScreenshots($this->itemObj->getID());
			}
			
		} catch (Exception $ex) {
			VCDException::display($ex,true);
		}
	}
	
	
}

?>