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
 * @version $Id$
 *
 */


class VCDFileUpload {
	
	private $maxFileSize = 0;			// Max allowed filesize in bytes, 0 = no limit
	private $overwrite = true;			// Overwrite existing file with same name or not ?
	private $randomname	= false;		// Generate random file name or keep the original one.
	private $fileperm = 0777;			// Unix style file permission number
	
	private $arrProcessesFiles = array();
	
	
	public function __construct() {
		if($_FILES) {
			foreach($_FILES as $fieldname => $fileObj) {
				$this->processFile($fileObj, $fieldname);
			}
		}
	}
	
	private function processFile($fileObj, $fieldname) {
		$VCDUploadedFileObj = new VCDUploadedFile($fileObj, $fieldname);
		$VCDUploadedFileObj->setFileParams($this->maxFileSize, $this->overwrite, $this->randomname, $this->fileperm);
		if (!(strcmp($VCDUploadedFileObj->getFileName(), "") == 0)) {
			array_push($this->arrProcessesFiles, $VCDUploadedFileObj);	
		}
		
	}
	
	/**
	 * Get specific file by the HTML upload field name.
	 * Returns array of VCDUploadFiles, empty array if no file matches.
	 *
	 * @param string $strHTMLFieldname
	 * @return array
	 */
	public function getFileByHTMLFieldName($strHTMLFieldname) {
		$arrFiles = array();
		foreach ($this->arrProcessesFiles as $VCDUploadedFile) {
			if (strcmp(strtolower($VCDUploadedFile->getHTMLFieldName()), strtolower($strHTMLFieldname)) == 0) {
				array_push($arrFiles, $VCDUploadedFile);
			}
		}
		return $arrFiles;
	}
	
	/**
	 * Get the number of uploaded files
	 *
	 * @return int
	 */
	public function getFileCount() {
		return sizeof($this->arrProcessesFiles);
	}
	
	/**
	 * Get uploaded files by certain Mime Type.
	 * Returns array of VCDUploadFiles, empty array if no file matches.
	 *
	 * @param string $strMimeType
	 * @return array
	 */
	public function getFilesByType($strMimeType) {
		$arrFiles = array();
		if ($this->getFileCount() > 0) {
			foreach ($this->arrProcessesFiles as $VCDUploadedFile) {
				if (strcmp(strtolower($VCDUploadedFile->getFileType()), strtolower($strMimeType)) == 0) {
					array_push($arrFiles, $VCDUploadedFile);
				}
			}
		}
		return $arrFiles;
	}
	
	/**
	 * Get VCDUploadFile at specific index in the local file array.
	 *
	 * @param int $index
	 * @return VCDUploadedFile
	 */
	public function getFileAt($index) {
		if (is_numeric($index) && $index < sizeof($this->arrProcessesFiles)) {
			return $this->arrProcessesFiles[$index];
		} else {
			throw new Exception("File index out of bound.");
		}
		
	}
	
	
	
	
}


/**
 * Wrapper class for a uploaded file.
 *
 */
class VCDUploadedFile {
	
	private $filename;				// The name of the file
	private $filetmpname;			// The tmpfilename in /tmp folder
	private $filesize;				// Filesize in bytes
	private $filemimetype;			// The mimetype of the file | ex. ("xml" => "text/xml")
	private $fileerror;				// The error during upload if any
	private $fieldname;				// The HTML field name of the html upload field.
	
	private $filelocation = null;	// The full filepath on server filesystem.
	
	/* File variables set by VCDFileUpload class */
	private $iMaxFileSize;
	private $bOverWrite ;
	private $bUseRandomFileName;
	private $strFilePermission;
	
	
	/* List of file extensions used by VCD-db */
	CONST FILE_XML = "text/xml";
	CONST FILE_GZ  = "application/octet-stream";
	CONST FILE_JPG = "image/jpeg";
	CONST FILE_GIF = "image/gif";
	CONST FILE_NFO = "application/octet-stream";
	CONST FILE_TXT = "text/plain";
	
	private $arrExtension = array(
		self::FILE_XML => 'xml',
		self::FILE_GZ  => 'gz',
		self::FILE_JPG => 'jpeg',
		self::FILE_GIF => 'gif',
		self::FILE_NFO => 'nfo',
		self::FILE_TXT => 'txt'
	);
	
	
	/**
	 * Object constructer
	 *
	 * @param $_FILE $fileObj
	 * @param string $HTMLfieldName
	 */
	public function __construct($fileObj, $HTMLfieldName) {
		if (is_array($fileObj)) {
			$this->filename = $fileObj['name'];
			$this->filemimetype = $fileObj['type'];
			$this->filesize = $fileObj['size'];
			$this->fileerror = $fileObj['error'];
			$this->filetmpname = $fileObj['tmp_name'];
			$this->fieldname = $HTMLfieldName;
		} else {
			throw new Exception("Parameter must be item of array $_FILES");
		}
		
	}
	
	/**
	 * Set the uploaded file parameters.
	 *
	 * @param int $maxFileSize | Max file size in bytes
	 * @param bool $replace | Replace exising file with same name if it exists
	 * @param bool $useRandomFileName | Generate random file name or keep the original
	 * @param string $filePermissions | Unix style file permissions
	 */
	public function setFileParams($maxFileSize, $replace, $useRandomFileName, $filePermissions) {
		$this->iMaxFileSize = $maxFileSize;
		$this->bOverWrite = $replace;
		$this->bUseRandomFileName = $useRandomFileName;
		$this->strFilePermission = $filePermissions;
	}
	
	/**
	 * Get the HTML upload field name of the uploaded file
	 *
	 * @return string
	 */
	public function getHTMLFieldName() {
		return $this->fieldname;
	}
	
	/**
	 * Get the file name of the uploaded file
	 *
	 * @return string
	 */
	public function getFileName() {
		return $this->filename;
	}
	
	/**
	 * Get the size of the file in bytes
	 *
	 * @return int
	 */
	public function getFileSize() {
		return $this->filesize;
	}
	
	/**
	 * Get the file MIME type
	 *
	 * @return string
	 */
	public function getFileType() {
		return $this->filemimetype;
	}	
	
	/**
	 * Get the Error code of the file if any, no error = 0
	 *
	 * @return int
	 */
	public function getFileError() {
		return $this->fileerror;
	}
	
	/**
	 * Get the current file location of the file, empty string if file has not been moved from tmp folder
	 *
	 * @return string
	 */
	public function getFileLocation() {
		return $this->filelocation;
	}
	
	
	public function move($strDestinationFolder) {
		try {
			
			if ($this->checkUploadConditions($strDestinationFolder)) {
				
				$dst_file_name = ($this->bUseRandomFileName) ? $this->generateFileName() : $this->fixFileName($this->filename);
        		$full_destination_path = $strDestinationFolder."/".$dst_file_name;
        		if(move_uploaded_file($this->filetmpname,$full_destination_path)) {
            		$this->filelocation = $strDestinationFolder."/".$dst_file_name;
        			@chmod ($this->filelocation, $this->strFilePermission);
            		
        		} else {
        			throw new Exception("Unknown exception trying to move file {$this->filename}");
        		}
				
				
			}

		} catch (Exception $ex) {
			throw $ex;
		}
		
	}
	
	
	
	/** 
	 * 	 Private functions below ..
	 */
	
   
	private function checkUploadConditions($strDestinationFolder) {
		try {
			
			// Check if the requested directory to move to exits
			if (!@is_dir($strDestinationFolder)) {
				throw new Exception("Directory {$strDestinationFolder} does not exist.  Cannot move file.");
			}
			
			// Check if the file is of a legal extension
			
			
		
			
			return true;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	  
	/**
	 * Generate a unique name to the uploaded file.
	 *
	 * @return string
	 */
  	private function generateUniqueId() {
	    return md5(uniqid(mt_rand(),TRUE));
  	}

  
	/**
	 * Generate the file unique name with extension.
	 *
	 * @return string
	 */
    private function generateFileName() {
	    $dst_file_name = $this->generateUniqueId();
	    $arr = split("\.",$this->filename);
	    $dst_file_name .= ".".$arr[count($arr)-1];
	    return $dst_file_name;
  	}

  	
  	/**
  	 * Replace accents and special chars from file name.
  	 *
  	 * @param string $string
  	 * @return string
  	 */
    private function fixFileName($string){
	    $string = strtr ( $string, "�����������������������������������������������������", "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn");
	    for($i=0 ; $i < strlen($string); $i++){
	       if(!ereg("([0-9A-Za-z_\.])",$string[$i]))
	         $string[$i] = "_";
	    }
	    return $string;
  	}
	
	
}



?>