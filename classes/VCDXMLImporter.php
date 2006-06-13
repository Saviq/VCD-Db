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

class VCDXMLImporter {
		
	/**
	 * XSD to validate VCD-db XML Movie files.
	 *
	 */
	CONST XSD_VCDDB_MOVIES = "includes/schema/vcddb-export.xsd";
	/**
	 * XSD to validate VCD-db XML Thumbnails files.
	 *
	 */
	CONST XSD_VCDDB_THUMBS = "includes/schema/vcddb-thumbnails.xsd";
	
	
	public function __construct() {
		
		
	}
	
	
	/**
	 * Get the number of movie entries in the XML file.
	 *
	 * @param string $strXmlFile | The XML file to load and read from.
	 * @return int | The number of movie entries found in XML file.
	 */
	public static function getXmlMovieCount($strXmlFile) {
		try {
			$file = TEMP_FOLDER.$strXmlFile;
			if (!file_exists($file)) {
				throw new Exception("Could not load file " . $file);
			}
			
			$xml = simplexml_load_file($file);
			$movieCount = count($xml->xpath("//movie")); 
			if (is_numeric($movieCount)) {
				return $movieCount;
			} else {
				return 0;
			}
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get the number of entries in the XML file.
	 *
	 * @param string $strXmlFile | The XML file to load and read from.
	 * @return int | The number of cover entries found in the XML file.
	 */
	public static function getXmlThumbnailCount($strXmlFile) {
		try {
			
			$file = TEMP_FOLDER.$strXmlFile;
			if (!file_exists($file)) {
				throw new Exception("Could not load file " . $file);
			}
			
			$xml = simplexml_load_file($file);
			$coverCount = count($xml->xpath("//cdcover")); 
			if (is_numeric($coverCount)) {
				return $coverCount;
			} else {
				return 0;
			}
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Get the Movie Titles from the XML Movie file
	 *
	 * @param string $strXmlFile | The XML Movie file to read from.
	 * @return array | Array of movie titles.
	 */
	public static function getXmlTitles($strXmlFile) {
		try {
		
			$xml = simplexml_load_file(TEMP_FOLDER.$strXmlFile);
			$movies = $xml->movie;
			$arrMovies = array();
			foreach ($movies as $movie) {
				if ( strcmp((string)$movie->title, "") != 0 ) {
					array_push($arrMovies, utf8_decode((string)$movie->title));
				}
			}
			
			return $arrMovies;
			
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	

	/**
	 * Handle a XML Movie upload and import.
	 *
	 * @return string | The uploaded file name
	 */
	public static function validateXMLMovieImport() {
		
		// Set the allowed extensions for the upload
		$arrExt = array(VCDUploadedFile::FILE_XML , VCDUploadedFile::FILE_TGZ );
		$VCDUploader = new VCDFileUpload($arrExt);
		
		if ($VCDUploader->getFileCount() != 1) {
			throw new Exception("No File was uploaded.");
		}
		
		$fileObj = $VCDUploader->getFileAt(0);
		// Move the file to the TEMP Folder
		$fileObj->move(TEMP_FOLDER);
		// Get the full path including filename after it has been moved
		$fileLocation = $fileObj->getFileLocation();
		    
		 // Check if this is a compressed file ..
   		$filename = $fileObj->getFileName();
   		if (strpos($filename, ".tgz")) {
   			// The file is a tar archive .. lets untar it ...
   			require_once('classes/external/compression/tar.php');
   			$zipfile = new tar();
   			if ($zipfile->openTAR($fileLocation)) {
   				if ($zipfile->numFiles != 1) {
   					throw new Exception('Only one XML file is allowed per Tar archive');
   				}
   				
   				$tar_xmlfile = $zipfile->files[0]['file'];
   				$tar_xmlfilename = VCDUtils::generateUniqueId().".xml";
   				
   				// Write the contents to cache
   				VCDUtils::write(TEMP_FOLDER.$tar_xmlfilename, $tar_xmlfile);
   				$fileLocation = TEMP_FOLDER.$tar_xmlfilename;
   				
   				// delete the original Tar file
   				$fileObj->delete();
 				
   				
   			} else {
   				throw new Exception('The uploaded TAR file could not be opened.');
   			}
   		}
		      
		 
   		/* Process the XML Thumbnail file */
	    if (!fs_file_exists($fileLocation)) {
	   		throw new Exception('Failed to open the Xml file.');
	    }
	
	   		
		 // Validate the document before processing it ..
		 
		 $dom = new domdocument();
		 $dom->load($fileLocation);
		 $schema = self::XSD_VCDDB_MOVIES;
		 if (!@$dom->schemaValidate($schema)) {
		 	throw new Exception("XML Document does not validate to the VCD-db XSD import schema.<break>Please fix the document or export a new one.<break>The schema can be found under '/includes/schema/vcddb-export.xsd'");
		 }
		 unset($dom);
	   		
	 	return str_replace(TEMP_FOLDER, "", $fileLocation);
	}
	

	/**
	 * Handle a XML Thumbnail upload and import.
	 *
	 * @return string | The name of the uploaded XML file.
	 */
	public static function validateXMLThumbsImport() {
	
		// Set the allowed extensions for the upload
		$arrExt = array(VCDUploadedFile::FILE_XML , VCDUploadedFile::FILE_TGZ );
		$VCDUploader = new VCDFileUpload($arrExt);
		
		if ($VCDUploader->getFileCount() != 1) {
			throw new Exception("No File was uploaded.");
		}
		
		$fileObj = $VCDUploader->getFileAt(0);
		// Move the file to the TEMP Folder
		$fileObj->move(TEMP_FOLDER);
		// Get the full path including filename after it has been moved
		$fileLocation = $fileObj->getFileLocation();
		    
		 // Check if this is a compressed file ..
   		$filename = $fileObj->getFileName();
   		if (strpos($filename, ".tgz")) {
   			// The file is a tar archive .. lets untar it ...
   			require_once('classes/external/compression/tar.php');
   			$zipfile = new tar();
   			if ($zipfile->openTAR($fileLocation)) {
   				if ($zipfile->numFiles != 1) {
   					throw new Exception('Only one XML file is allowed per Tar archive');
   				}
   				
   				$tar_xmlfile = $zipfile->files[0]['file'];
   				$tar_xmlfilename = VCDUtils::generateUniqueId().".xml";
   				
   				// Write the contents to cache
   				VCDUtils::write(TEMP_FOLDER.$tar_xmlfilename, $tar_xmlfile);
   				$fileLocation = TEMP_FOLDER.$tar_xmlfilename;
   				
   				// delete the original Tar file
   				$fileObj->delete();
 				
   				
   			} else {
   				throw new Exception('The uploaded TAR file could not be opened.');
   			}
   		}
		      
		 
   		/* Process the XML Thumbnail file */
	    if (!fs_file_exists($fileLocation)) {
	   		throw new Exception('Failed to open the thumbnails file.');
	    }
	
	   		
		 // Validate the document before processing it ..
		 
		 $dom = new domdocument();
		 $dom->load($fileLocation);
		 $schema = self::XSD_VCDDB_THUMBS;
		 if (!@$dom->schemaValidate($schema)) {
			throw new Exception("XML Document does not validate to the VCD-db Thumbnails XSD import schema.<break>Please fix the document or export a new one.<break>The schema can be found under '/includes/schema/vcddb-thumbnails.xsd'");
		 }
		 unset($dom);
	   		
	 	return str_replace(TEMP_FOLDER, "", $fileLocation);
   		
	}


	/**
	 * Add a single movie from the $xmlfilename into database.
	 *
	 * @param string $xmlfilename | The XML Movie file to read from
	 * @param int $index | The item index to read in the XML Movie file.
	 * @param string $xmlthumbsfilename | The XML Thumbnail file to read from.
	 * @return array | Status array containing status information.
	 */
	public function addMovie($xmlfilename, $index, $xmlthumbsfilename = null) {
		try {
		
			$xml = simplexml_load_file(TEMP_FOLDER.$xmlfilename);
			
			$movie = $xml->movie[$index];
			$movie_id = (string)$movie->id;
			
			$cache = "NO";
			
			if (!is_null($xmlthumbsfilename)) {
				$xmlthumbnail = TEMP_FOLDER.$xmlthumbsfilename;
				$coverObj = $this->getThumbnail($movie_id, $xmlthumbnail);
				$cache = $coverObj->getFilename();
			}
			
			
			
			$arr = array(
				'name' => utf8_decode((string)$movie->title), 
				'status' => utf8_decode((string)$cache),
				'thumb' => 'YES',
				);
				
			
			return $arr;
			
			//return utf8_decode((string)$movie->title);
			
			//$doc = new DOMDocument();
			//$doc->loadXML($movie->asXML());
		
		} catch (Exception $ex) {
			throw $ex;
		}
		
	}
	
	/**
	 * Get the imported thumbnail as cdCoverObj is it exists, otherwise returns null.
	 *
	 * @param int $vcd_id | The MovieID to find the matching cover
	 * @param string $xmlfilename | The XML file name of the cover file.
	 * @return cdcoverObj
	 */
	private function getThumbnail($vcd_id, $xmlfilename) {
		try {
		
			if (file_exists($xmlfilename) && is_file($xmlfilename)) {

				$xml = simplexml_load_file($xmlfilename);
				
				$query = "//vcdthumbnails/cdcover/vcd_id[. = {$vcd_id}]";
				$nodeList = $xml->xpath($query);
				if (isset($nodeList)) {
					$node = $nodeList[0];
					$parent = $node->xpath('parent::node()');
					if (isset($parent[0])) {
						$cover = $parent[0];
						return $this->createThumbnailObject($cover);
					}
				}
			}
			return null;
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Create a CDcoverObj from the XMLElement, returns null if cover is not Ok.
	 *
	 * @param SimpleXMLElement $element
	 * @return cdcoverObj
	 */
	private function createThumbnailObject(SimpleXMLElement $element) {
		try {

			$filename = (string)$element->filename;
			$data = (string)$element->data;
			$ClassCovers = VCDClassFactory::getInstance('vcd_cdcover');
			$cdCoverObj = null;
			
			// Check if the data is not null and then write the image to temp folder
			if ((strlen($data) > 0) && VCDUtils::write(TEMP_FOLDER.$filename, base64_decode($data))) {
				$cdCoverObj = new cdcoverObj();
				$coverTypeObj = $ClassCovers->getCoverTypeByName("thumbnail");
				$cdCoverObj->setCoverTypeID($coverTypeObj->getCoverTypeID());
				$cdCoverObj->setCoverTypeName("Thumbnail");
				$cdCoverObj->setFilename($filename);
			}
			
			return $cdCoverObj;		
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	
}




?>