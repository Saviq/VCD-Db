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
 * @version $Id$
 *
 */

/**
 * Class to handle file uploads in VCD-db.
 * $_FILES array is processed at initialization.
 *
 */
class VCDFileUpload {
	
	private $maxFileSize = 0;			// Max allowed filesize in bytes, 0 = no limit
	private $overwrite = true;			// Overwrite existing file with same name or not ?
	private $randomname	= true;			// Generate random file name or keep the original one.
	private $fileperm = 0777;			// Unix style file permission number
	private $arrRestrictions = array(); // Array of allowed mime types to upload.
	
	private $arrProcessesFiles = array();
	
	
	/**
	 * Object constructor
	 *
	 * @param array $arrExtensions | The Mime array to use for restrictions
	 */
	public function __construct($arrExtensions=null) {
		if (!is_null($arrExtensions)) { $this->setFileTypeRestrictions($arrExtensions); }
		
		if($_FILES) {
			foreach($_FILES as $fieldname => $fileObj) {
				$this->processFile($fileObj, $fieldname);
			}
		}
	}
	
	/**
	 * Process each uploaded file.
	 *
	 * @param $_FILE $fileObj | One item in PHP $_FILES array
	 * @param string $fieldname | The HTML upload field name
	 */
	private function processFile($fileObj, $fieldname) {
		$VCDUploadedFileObj = new VCDUploadedFile($fileObj, $fieldname);
		$VCDUploadedFileObj->setFileParams($this->maxFileSize, $this->overwrite, $this->randomname, $this->fileperm);
		$VCDUploadedFileObj->setFileRestrictions($this->arrRestrictions);
	
		
		if (!(strcmp($VCDUploadedFileObj->getFileName(), "") == 0)) {
			array_push($this->arrProcessesFiles, $VCDUploadedFileObj);	
		}
		
	}
	
	
	/**
	 * Set MIME type restrictions on the uploaded files.
	 * Uses the VCDUloadedFile::FILE_ Constants
	 *
	 * @param mixed $arrRestrictions | Can be array or string
	 */
	private function setFileTypeRestrictions($arrRestrictions) {
		if (is_array($arrRestrictions)) {
			$this->arrRestrictions = $arrRestrictions;
		} elseif (strcmp($arrRestrictions, "") != 0) {
			array_push($this->arrRestrictions, $arrRestrictions);
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
	private $arrRestrictions = array();
	
	
	/* List of file extensions used by VCD-db */
	CONST FILE_XML  = "xml";
	CONST FILE_TGZ  = "tgz";
	CONST FILE_ZIP  = "zip";
	CONST FILE_JPG  = "jpg";
	CONST FILE_JPEG = "jpeg";
	CONST FILE_GIF  = "gif";
	CONST FILE_NFO  = "nfo";
	CONST FILE_TXT  = "txt";
		
	private $arrExtension = array(
		self::FILE_XML  => 'text/xml',
		self::FILE_TGZ  => 'application/x-gzip',
		self::FILE_ZIP  => 'application/x-zip',
		self::FILE_JPEG => 'image/pjpeg',
		self::FILE_JPG  => 'image/jpg',
		self::FILE_GIF  => 'image/gif',
		self::FILE_NFO  => 'text/nfo',
		self::FILE_TXT  => 'text/plain'
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
	 * Set the MIME Type restrictions on the uploaded file.
	 *
	 * @param array $arrRestrictions
	 */
	public function setFileRestrictions($arrRestrictions) {
		if (is_array($arrRestrictions)) {
			$this->arrRestrictions = $arrRestrictions;
		}
	}
	

	/**
	 * Set the maximimum filesize in bytes
	 *
	 * @param int $iSize | The size in bytes
	 */
	public function setMaxFileSize($iSize) {
		if (is_numeric($iSize)) {
			$this->iMaxFileSize = $iSize;	
		}
	}
	
	/**
	 * Set the file name to be generated or not.
	 *
	 * @param bool $bUseRandom
	 */
	public function setRandomFileName($bUseRandom) {
		$this->bUseRandomFileName = $bUseRandom;
	}
	
	/**
	 * Overwrite existing file with same name if it exists?
	 *
	 * @param bool $bOverWrite
	 */
	public function setOverWrite($bOverWrite) {
		$this->bOverWrite = $bOverWrite;
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
	
	/**
	 * Get the current file extension
	 *
	 * @return string
	 */
	public function getFileExtenstion() {
		ereg( ".*\.(.*)$", $this->filename, $regs );
	    if (isset($regs[1])) {
	    	return $regs[1];	
	    } else {
	    	return "";
	    }
	}
	
	
	/**
	 * Move the uploaded file to a specific folder.
	 * If Move is successful functions returns true, otherwise false.
	 *
	 * @param string $strDestinationFolder
	 * 
	 * @return bool
	 */
	public function move($strDestinationFolder) {
		try {
			
			if ($this->checkUploadConditions($strDestinationFolder)) {
				
				$dst_file_name = ($this->bUseRandomFileName) ? $this->generateFileName() : $this->fixFileName($this->filename);
        		$full_destination_path = $strDestinationFolder."/".$dst_file_name;
        		
        		// Check if overwrite is disabled and if file already exists ..
        		if (!$this->bOverWrite && file_exists($full_destination_path)) {
        			// it already exists .. we have to use generated file name
        			$dst_file_name = $this->generateFileName();
        			$full_destination_path = $strDestinationFolder."/".$dst_file_name;
        			$this->filename = $dst_file_name;
        		}
        		
        		if(@move_uploaded_file($this->filetmpname,$full_destination_path)) {
            		$this->setFileLocation($strDestinationFolder."/".$dst_file_name);
        			@chmod ($this->filelocation, $this->strFilePermission);
        			if ($this->bUseRandomFileName) {
        				$this->filename = $dst_file_name;	
        			}
        			return true;
            		
        		} else {
        			throw new Exception("Unknown exception trying to move file {$this->filename}");
        		}
				
        		return false;
				
			}

		} catch (Exception $ex) {
			throw $ex;
		}
		
	}
	
	
	/**
	 * Delete the file from file system.
	 * Returns true if file could be deleted, otherwise false.
	 *
	 * @return bool
	 */
	public function delete() {
		if (strcmp($this->filelocation, "") != 0) {
			return @unlink($this->filelocation);
		}
		return true;
	}
	
	
	/** 
	 * 	 Private functions below ..
	 */
	
   
	/**
	 * Set the relative file location on the file system on the webserver.
	 *
	 * @param string $strLocation
	 */
	private function setFileLocation($strLocation) {
		$location = str_replace("//", "/", $strLocation);
		$this->filelocation = $location;
	}
	
	
	/**
	 * Check for upload conditions, such as file restrictions, size restrictions and folder location.
	 * Returns true if no restriction is broken, otherwise an Exception is thrown.
	 *
	 * @param string $strDestinationFolder
	 * @return bool
	 */
	private function checkUploadConditions($strDestinationFolder) {
		try {
			
			// Check if the requested directory to move to exits
			if (!@is_dir($strDestinationFolder)) {
				throw new Exception("Directory {$strDestinationFolder} does not exist.  Cannot move file.");
			}
			
			// Check if the file is of a legal extension
			if (is_array($this->arrExtension) && sizeof($this->arrExtension) > 0) {
				$isLegal = false;
				foreach ($this->arrRestrictions as $index => $extension) {
					
					if (strcmp(strtolower($this->getFileExtenstion()), strtolower($extension)) == 0) {
						$isLegal = true;
						break;
					}
				}
				if (!$isLegal) {
					throw new Exception("File type \"{$this->getFileType()}\" not allowed.");
				}
			}
			
			// Check for filesize Restrictions
			if (is_numeric($this->iMaxFileSize) && ($this->iMaxFileSize > 0) && ($this->filesize > $this->iMaxFileSize)) {
				throw new Exception("Filesize exceeds file size limit ({$this->toHumanFileSize($this->iMaxFileSize)}) ");
			}
			
			
			
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
	    // If the file name was changed (fixed) we need to update the filename
	    $this->filename = $string;
	    return $string;
  	}
  	
  	
  	/**
  	 * Format size in bytes to more readable format.
  	 *
  	 * @param int $size | The filesize in bytes
  	 * @return string
  	 */
  	private function toHumanFileSize($size) {
	   if (is_numeric($size) && $size > 0) {
	   		$filesizename = array(" Bytes", " kb", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
	   		return round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $filesizename[$i];
	   } else {
	   		return "0 Bytes";
	   }
	}
	
	
}



?>