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
	
	private $doc;
	private $filename;
	
	
	public function __construct() {
		
		
	}
	
	
	/**
 	* Process user uploaded XML file containing exported movies from another vcd-db.
 	* Validates the uploaded data, if XML file is in a TGZ file, the file is unzipped and
 	* examined.  The uploaded XML file is then validated using the VCD-db XSD schema document.
	* If XML document does not validate and error is thrown.
 	*
	* @param array $out_movietitles
 	* @return string Returns the uploaded file name.
 	*/

	public static function validateXMLImport(&$out_movietitles) {

	
		$upload = new uploader();
		$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");
		$path = $SETTINGSClass->getSettingsByKey('SITE_ROOT');
		
		if($_FILES){
		  foreach($_FILES as $key => $file){
		  	
		  	$savePath = $_SERVER["DOCUMENT_ROOT"]."".$path."upload/";
			$arrFileExt = array("xml" => "text/xml", "tgz" => "application/zip");
			prepareUploader($upload, $file, $key, VSIZE_XML, $arrFileExt, $savePath);
			$result = $upload->moveFileToDestination(); 
		  }
		}
		
		if($upload->succeed_files_track){
		      $file_arr = $upload->succeed_files_track; 
		      $upfile = $file_arr[0]['destination_directory'].$file_arr[0]['new_file_name'];
		      $returnFilename = $file_arr[0]['new_file_name'];
				      
		       /* 
		       		Process the XML file
		       */
			   if (fs_file_exists($upfile)) {
		    		
			   			   	
			   	
			   		// Check if this is a compressed file ..
			   		$filename = $file_arr[0]['file_name'];
			   		if (strpos($filename, ".tgz")) {
			   			// The file is a tar archive .. lets untar it ...
			   			require_once('classes/external/compression/tar.php');
			   			$zipfile = new tar();
			   			if ($zipfile->openTAR($upfile)) {
			   				if ($zipfile->numFiles != 1) {
			   					throw new Exception('Only one XML file is allowed per Tar archive');
			   				}
			   				
			   				
			   				$tar_xmlfile = $zipfile->files[0]['file'];
			   				$tar_xmlfilename = "movie_import.xml";
			   				$returnFilename = $tar_xmlfilename;
			   				
			   				
			   				// Write the contents to cache
			   				VCDUtils::write(TEMP_FOLDER.$tar_xmlfilename, $tar_xmlfile);
			   				$upfile = TEMP_FOLDER.$tar_xmlfilename;
			   				
			   				
			   				
			   			} else {
			   				throw new Exception('The uploaded TAR file could not be opened.');
			   			}
			   		}
			   		
			   		
			   				   	
			   	
			   		// First of all Validate the XML document so we can begin with avoiding
			   		// errors when processing the file later with the VCDdb objects
			   		
			   		$xml = simplexml_load_file($upfile);
			   		$dom = new domdocument();
			   		$dom->load($upfile);
			   		
			   		$schema = 'includes/schema/vcddb-export.xsd';
			   		
			   		if (!@$dom->schemaValidate($schema)) {
			   			throw new Exception("XML Document does not validate to the VCD-db XSD import schema.<break>Please fix the document or export a new one.<break>The schema can be found under '/includes/schema/vcddb-export.xsd'");
			   		}
			   		
			   		
			   } else {
		    		throw new Exception("Failed to open the uploaded file.<break>Check file permissions on the upload folder.");
			   }
			
			   		
				
			   // Generate Objects from the XML file ...
			   $movies = $xml->movie;
			   $imported_movies = array();
			   $adult_cat = $SETTINGSClass->getCategoryIDByName('adult');
			   
			   if (sizeof($movies) == 0) {
			   		throw new Exception("No movies found in the XML file.<br/>Make sure that you are uploading VCD-db generated XML file.");
			   } else {
			   		foreach ($movies as $item) {
			   			if (strcmp($item->title, "") != 0) {
			   				$title = utf8_decode((string)$item->title);
				    		array_push($out_movietitles, $title);
			   			}
					}
			   }
			   
			   unset($xml);
				
		
		      
		} else {
			throw new Exception($upload->fail_files_track[0]['msg']);
		}
		
		return $returnFilename;

}


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
	 $schema = 'includes/schema/vcddb-thumbnails.xsd';
	 if (!@$dom->schemaValidate($schema)) {
		throw new Exception("XML Document does not validate to the VCD-db Thumbnails XSD import schema.<break>Please fix the document or export a new one.<break>The schema can be found under '/includes/schema/vcddb-thumbnails.xsd'");
	 }
	 unset($dom);
	   
	   
		
	 return str_replace(TEMP_FOLDER, "", $fileLocation);
   		
	}

	
	public function addMovie($xmlfilename, $index, $xmlthumbsfilename = null) {
		
		
		$xml = simplexml_load_file(TEMP_FOLDER.$xmlfilename);

						
		$movie = $xml->movie[$index];

		$movie_id = (string)$movie->id;
		
		
		$cache = "OK";
		if (is_null($xmlthumbsfilename)) {
			$xmlthumbnail = TEMP_FOLDER.$xmlthumbsfilename;
			$cache = $this->getThumbnail($movie_id, $xmlthumbnail);
		}
		
		
		
		$arr = array('name' => utf8_decode((string)$movie->title), 'status' => utf8_decode((string)$cache));
			
		
		return $arr;
		
		//return utf8_decode((string)$movie->title);
		
		//$doc = new DOMDocument();
		//$doc->loadXML($movie->asXML());
		
	}
	
	private function getThumbnail($vcd_id, $xmlfilename) {
		
		if (file_exists($xmlfilename) && is_file($xmlfilename)) {
		
			$xml = simplexml_load_file($xmlfilename);
			
			$query = "//vcdthumbnails/cdcover/vcd_id[. = {$vcd_id}]";
			$nodeList = $xml->xpath($query);
			if (isset($nodeList)) {
				$node = $nodeList[0];
				$parent = $node->xpath('parent::node()');
				if (isset($parent[0])) {
					$cover = $parent[0];
					return (string)$cover->filename;
				}
				
			}
	
		}
		
		return "NO";
		
	}
	
}




?>