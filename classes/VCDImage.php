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
 * @package Core
 * @version $Id$
 *
	Class to store and fetch binary data trough the ADODB Layer.
	There are several things that have to be taken care of first.

	File upload must be set on in the php.ini and maxfile size should
	be set to at least 2mb for non rejected image uploads.
	Be sure that magic_quotes are off, they can fuck up binary streams 
	to the DB.
	
	Limits in the php.ini should be changed from 4096 (which is default)
	to at least 1mb, can be more ... otherwise the read from the db will fail.

	mssql.textlimit = 2147483647
	mssql.textsize = 2147483647
	mssql column for storing binary data must be of value 'image'
	
	vcdimage.php is used to display the image ..
*/

require_once('cdcover/imageSQL.php');

class VCDImage {
	
	private $image_id;
	private $name;
	private $content_type;
	private $image_size;
	private $image = null;
	
	private $default_filepath;
	/**
	 * @var imageSQL
	 */
	private $SQL;

	
	/**
	 * Object constructor.
	 * If $image_id is supplied the current Obj is populated, except for the binary data iteself.
	 * getImageStream() must be called for that.
	 *
	 * @param int $image_id
	 */
	public function __construct($image_id = null) { 
    	// Try to change php.ini file values ..
   		ini_set('mssql.textlimit',2147483647);
   		ini_set('mssql.textsize' ,2147483647);
   		
   		// Get the default temp filepath from php.ini
   		$this->default_filepath = ini_get('upload_tmp_dir');
   		
   		// Initilize imageSQL 
   		$this->SQL = new imageSQL();
   		
   		
   		if (!is_null($image_id) && is_numeric($image_id)) {
   			$imagedata = $this->SQL->getImageDetails($image_id);
   			if (is_array($imagedata)) {
   				
   				if (isset($imagedata['NAME'])) {
   					$this->name = $imagedata['NAME'];
   				}
   				if (isset($imagedata['IMAGE_TYPE'])) {
   					$this->content_type = $imagedata['IMAGE_TYPE'];
   				}
   				if (isset($imagedata['IMAGE_SIZE'])) {
   					$this->image_size = $imagedata['IMAGE_SIZE'];
   				}
   				unset($imagedata);
   				
   			}
   			
   		}
   		
	} 
	
		
	/**
	 * Save image from given path on hard drive
	 *
	 * Function returns the assigned image_id on success,
	 * otherwise it returns false.
	 *
	 * @param string $file
	 * @param array $imageInfoArr
	 * @param bool $delete
	 * @return int
	 */
	public function addImageFromPath($file, $imageInfoArr = null, $delete=false) {
		// Open file descriptor in binary mode (essential for windows machines)
		if(!fs_file_exists($file)) {
			
			
			// try to look in the default upload folder if image name
			// was only submitted
			$path_added = $this->default_filepath . "/" . $file;
			if(!fs_file_exists($path_added)) { 
				VCDException::display("File ".$file." does not exist");
				return;
			} else {
				$file = $path_added;
			}
			
			
		}
		
		$fd = fopen($file,'rb');
		if (!$fd) {
			return false;
		}
		
		// Read the file 
		$contents = fread($fd,filesize($file));
		
		// Encodes the stream with MIME base64
		$encoded = base64_encode($contents); 
		
		// Close the file descriptor
		fclose($fd);
		

		// Look for image information
		if (is_array($imageInfoArr)) {
			
			$this->name = $imageInfoArr['name'];
			$this->content_type = $imageInfoArr['type'];
					
		} else {	
			$this->getAttributes($file);
		}
		
		
			
		try {
				
				$this->image_size = filesize($file);
			
				$image_id = $this->SQL->addImage($this->name, $this->content_type, filesize($file), $encoded);
				if (!(bool)$image_id) {
					VCDException::display("Could not insert image to DB");
				}
				
				
			} catch (Exception $e) {
				VCDException::display($e);
			}
		
		
		// Delete the file if it was requested
		if ($delete) {
			fs_unlink($file);
		}
			
			
		// Return image_id
		return $image_id;
	}
	
	
	/**
	 * Get the binary image stream.
	 *
	 * @param int $image_id
	 * @return string
	 */
	public function getImageStream($image_id) {
		try {
			if (!is_numeric($image_id)) {
				VCDException::display("Parameter image_id missing");
			} else {
				if (is_null($this->image)) {
					$this->image = $this->SQL->getImageStream($image_id);
				}
				return $this->image;
			}
			
			
		} catch (Exception $e)	{
			VCDException::display($e);
		}
	}
	
	
	/**
	 * Get the filesize of current image.
	 *
	 * @return int
	 */
	public function getFilesize() {
		if (is_numeric($this->image_size)) {
			return $this->image_size;
		} else if (!is_null($this->image)) {
			return strlen($this->image);
		} else {
			return 0;
		}
	}
	
	/**
	 * Get the name of current image.
	 *
	 * @return string
	 */
	public function getImageName() {
		return $this->name;
	}
	
	/**
	 * Get the mime type of the imageObj.  For example for jpg image "image/pjpeg" would be returned.
	 *
	 * @return string
	 */
	public function getImageType() {
		return $this->content_type;
	}
	
	
	/**
	 * Get file attributes.
	 *
	 * @param string $filename
	 */
	private function getAttributes($filename) {
		$file = fs_import_filename($filename);
		$file = str_replace('\\',"#",$file);
		$arr = split ('#', $file);
		
		if (is_array($arr)) {
			$info = $arr[sizeof($arr)-1];
			$info = split ('.', $info);
			$this->name = $info[0];
			$this->content_type = $info[1];
			
			unset($info);
		}
		unset($arr);
	}

}
?>