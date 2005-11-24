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
 * @package Functions
 * @version $Id$
 */
?>
<?

/**
 * Fetch RSS streams from another VCD-db sites and display them for selection.
 *
 * @param string $url
 */
function showAvailableFeeds($url) {

	global $language;
	
	// Flush errors ..
	error_reporting(0);
	
	$user_url = $url;
	
	$pos = strlen($url);
	$char = $url[($pos-1)];
	if ($char != '/') {	$url .= "/";}
	
				
	$sitefeed = $url .= "rss/";
	$feedusers = $sitefeed . "?users";
	
	$xml = simplexml_load_file($sitefeed);
	
	
	if ($xml && isset($xml->error)) {
		print $xml->error;
		print "<br/><a href=\"./addrssfeed.php\">".$language->show('X_TRYAGAIN')."</a>";
		return;
	}
	if (!$xml) {
		print "No feeds found at location " . $user_url;
		print "<br/><a href=\"javascript:history.back(-1)\">".$language->show('X_TRYAGAIN')."</a>";
		return;
	} 
	
	
	$xml_users = simplexml_load_file($feedusers);
	
	$title = $xml->channel->title;
	$link = $xml->channel->link;
	$description = $xml->channel->description;
	
	
	print "<form name=\"feeds\" method=\"post\" action=\"../exec_form.php?action=addfeed\">";
	print "<strong>".$language->show('RSS_FOUND')."</strong><br/>";
	
	
	print "<table cellspacing=\"1\" cellpadding=\"1\" border=\"0\">";
	print "<tr><td colspan=\"2\"><strong>".$language->show('RSS_SITE')."</strong></td></tr>";
	print "<tr><td><input type=\"checkbox\" class=\"nof\" value=\"".utf8_decode($title)."|".$sitefeed."\" name=\"feeds[]\"></td><td>" . utf8_decode($title) . "</td></tr>";		
	print "<tr><td colspan=\"2\"><strong>".$language->show('RSS_USER')."</strong></td></tr>";
	$usersfeeds = $xml_users->rssusers->user;
	foreach ($usersfeeds as $user_feed) {
		print "<tr><td><input name=\"feeds[]\" type=\"checkbox\" class=\"nof\" value=\"".utf8_decode($user_feed->fullname)."|".$user_feed->rsspath."\"></td><td>". utf8_decode($user_feed->fullname) . "</td></tr>";
	}
	
	print "<tr><td colspan=\"2\" align=\"right\"><input type=\"submit\" value=\"save\" onclick=\"return rssCheck(this.form)\"></td></tr>";
	print "</table>";
	    
	
	print "</form>";
	
	// Reset error reporting
	error_reporting(ini_get('error_reporting'));
	

}

/**
 * Display a VCD-db RSS feed from anither VCD-db site.
 *
 * @param string $name
 * @param string $url
 */
function showFeed($name, $url) {
	
	// Flush errors ..
	error_reporting(0);
	
	$xml = simplexml_load_file($url);
	
	if ($xml && isset($xml->error)) {
		print $xml->error;
		return;
	}
	if (!$xml) {
		print "<p>RSS Feed not found for ".$name.", site maybe down.</p>";
		return;
	} 
	
	$items = $xml->channel->item;
    $title = $xml->channel->title;
    $link = $xml->channel->link;
	
    $pos = strpos($title, "(");
			if ($pos === false) { 
			    $img = "<img src=\"images/rsssite.gif\" align=absmiddle title=\"VCD Site feed\" border=\"0\"/>&nbsp;";
			} else {
				$img = "<img src=\"images/rssuser.gif\" align=absmiddle title=\"VCD User feed\" border=\"0\"/>&nbsp;";
			}
    
    print "<p class=normal><strong>".$img."<a href=\"".$link."\" target=\"new\">".utf8_decode($title)."</a></strong>";
    print "<ul>";
	foreach ($items as $item) {
						
		print "<li><a href=\"$item->link\" target=\"new\">". utf8_decode($item->title)."</a>";
		if (isset($item->description)) {
			print " <a href=\"$item->description\" target=\"new\">[link]</a>";
		}
		
		print "</li>";	
		
	}
	
	print "</ul></p>";
		
	// Reset error reporting
	error_reporting(ini_get('error_reporting'));
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

function checkMovieImport(&$out_movietitles) {

	
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



/**
 * Here all the actual work is performed after a XML file has been validated with checkMovieImport()
 * Movie enties are added from the XML file to the database, if user has selected uploading covers
 * the covers are also processed and linked to the correct imported movie.
 *
 * @param string $upfile
 * @param bool $use_covers
 * @return unknown
 */
function processXMLMovies($upfile, $use_covers) {
	
	
		// Since imported 1000 + movies can take alot time and memory
		// lets increase the function timeout to 5 minutes
		
		set_time_limit(300);
	
		
		 try {
	
		 $SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");
	
	       if (fs_file_exists($upfile)) {
	    		$xml = simplexml_load_file($upfile);
		   } else {
		   		throw new Exception('Failed to open the uploaded file for the movies.');
		   }
		
		   
		   
		   // User has thumbnail XML file uploaded as well
		   if ($use_covers) {
		   		// Begin thumbnail upload

				$upload = new uploader();
				$path = $SETTINGSClass->getSettingsByKey('SITE_ROOT');
				
				if($_FILES){
				  foreach($_FILES as $key => $file){
				  	
				  	$savePath = $_SERVER["DOCUMENT_ROOT"]."".$path."upload/";
		  			$arrFileExt = array("xml" => "text/xml", "tgz" => "application/zip");
		  			prepareUploader($upload, $file, $key, VSIZE_XMLTHUMBS, $arrFileExt, $savePath);
					$result = $upload->moveFileToDestination(); // $result = bool (true/false). Succeed or not.
					
				  }
				}
				
				if($upload->succeed_files_track){
				      $file_arr = $upload->succeed_files_track; 
				      $upthumbfile = $file_arr[0]['destination_directory'].$file_arr[0]['new_file_name'];
				      
				      
				      
				      
				      
						 // Check if this is a compressed file ..
				   		$filename = $file_arr[0]['file_name'];
				   		if (strpos($filename, ".tgz")) {
				   			// The file is a tar archive .. lets untar it ...
				   			require_once('classes/external/compression/tar.php');
				   			$zipfile = new tar();
				   			if ($zipfile->openTAR($upthumbfile)) {
				   				if ($zipfile->numFiles != 1) {
				   					throw new Exception('Only one XML file is allowed per Tar archive');
				   				}
				   				
				   				
				   				$tar_xmlfile = $zipfile->files[0]['file'];
				   				$tar_xmlfilename = "movie_import.xml";
				   				$returnFilename = $tar_xmlfilename;
				   				
				   				
				   				// Write the contents to cache
				   				VCDUtils::write(TEMP_FOLDER.$tar_xmlfilename, $tar_xmlfile);
				   				$upthumbfile = TEMP_FOLDER.$tar_xmlfilename;
				   				
				   				
				   				
				   			} else {
				   				throw new Exception('The uploaded TAR file could not be opened.');
				   			}
				   		}
				      
				      
				      
				      
				      
						      
				       /* 
				       		Process the XML Thumbnail file
				       */
				     	
					   if (fs_file_exists($upthumbfile)) {
				    		$xmlthumbnails = simplexml_load_file($upthumbfile);
					   } else {
					   		throw new Exception('Failed to open the thumbnails file.');
					   }
					
					   		
					 // Validate the document before processing it ..
					 $dom = new domdocument();
		   			 $dom->load($upthumbfile);
		   			 $schema = 'includes/schema/vcddb-thumbnails.xsd';
		   			 if (!@$dom->schemaValidate($schema)) {
		   				throw new Exception("XML Document does not validate to the VCD-db Thumbnails XSD import schema.<break>Please fix the document or export a new one.<break>The schema can be found under '/includes/schema/vcddb-thumbnails.xsd'");
		   			 }
		   			 unset($dom);
					   
					   
						
					   // GenerateObjects from the XML file ...
					   $thumbnails = $xmlthumbnails->cdcover;
					   $imported_thumbnails = array();
					   
					   
					   if (sizeof($thumbnails) == 0) {
					   		$strErr =  "No thumbnails found in the XML file.<break>Make sure that you are uploading VCD-db generated XML file.";
					   		throw new Exception($strErr);
					   } else {
					   	
					   	
					   		// Get a Thumbnail CoverTypeObj
							$COVERClass = VCDClassFactory::getInstance("vcd_cdcover");
					   	
					   		foreach ($thumbnails as $item) {
						    					   			
					   			// Write the image to the temp folder
					   			if ((strlen($item->data) > 0) && VCDUtils::write(TEMP_FOLDER.(string)$item->filename, base64_decode((string)$item->data))) {
					   			
					   				$cover = new cdcoverObj();
									$coverTypeObj = $COVERClass->getCoverTypeByName("thumbnail");
									$cover->setCoverTypeID($coverTypeObj->getCoverTypeID());	
									$cover->setCoverTypeName("Thumbnail");
									$cover->setFilename((string)$item->filename);
						   			
							    	array_push($imported_thumbnails, array('vcd_id' => (string)$item->vcd_id, 'obj' => $cover));
					   									   			
					   			}
							}
					   }
					   
					   unset($xmlthumbnails);
						
				
				      
				} else {
					$errMsg = $upload->fail_files_track[0]['msg'];
					throw new Exception('Error uploading thumbnails file.<break>'.$errMsg);
				}
		   		
		   		   	
			
		   		
		   
		   		// End thumbnail upload
		   }
		   
		   
		   
		   // GenerateObjects from the XML file ...
		   $movies = $xml->movie;
		   $imported_movies = array(); 
		   $adult_cat = $SETTINGSClass->getCategoryIDByName('adult');
		   $PORNClass = VCDClassFactory::getInstance("vcd_pornstar");
		   
		   		   
		   
		   if (sizeof($movies) == 0) {
		   		$strErr = "No movies found in the XML file.<break>Make sure that you are uploading VCD-db generated XML file.";
		   		throw new Exception($strErr);
		   		
		   } else {
		   		foreach ($movies as $item) {
			    	
		   			
		   			
		   			// Create the basic CD obj
					$basic = array('', utf8_decode((string)$item->title), (string)$item->category_id, (string)$item->year);
					$vcd = new vcdObj($basic);
					
					// Add 1 instance
					$mediaTypeObj = $SETTINGSClass->getMediaTypeByID((string)$item->mediatype_id);
					if (is_null($mediaTypeObj)) {
						
						// Non existing media type .. at least not found by ID
						// try a lookup by name
						
						$mediaTypeObj = $SETTINGSClass->getMediaTypeByName((string)$item->mediatype);
						if (is_null($mediaTypeObj)) {
							// Still no luck .. then lets create mediatype
							$newMediaTypeObj = new mediaTypeObj(array('',(string)$item->mediatype,'','Created by XML importer.'));
							$SETTINGSClass->addMediaType($newMediaTypeObj);
						}
					}
					
					$vcd->addInstance($_SESSION['user'], $mediaTypeObj, (string)$item->cds, (string)$item->dateadded);
					
					
					$movieCatObj = $SETTINGSClass->getMovieCategoryByID((string)$item->category_id);
					if ($movieCatObj instanceof movieCategoryObj ) {
						$vcd->setMovieCategory($movieCatObj);
					} 		   			
		   			
		   			
		   			$source_id = '';
		   			
		   			if ($item->category_id == $adult_cat) {
		   				// Adult flick
		   				
		   				// Check if any pornstars are associated in the movie
		   				$pornstars = $item->pornstars->pornstar;
		   				
		   					   				
		   				if (isset($pornstars)) {
		   					foreach ($pornstars as $pornstar) {
		   						$starObj = null;
		   						$starObj = $PORNClass->getPornstarByName((string)$pornstar->name);
		   						
		   						if ($starObj instanceof pornstarObj ) {
		   							$vcd->addPornstars($starObj);
		   						} else {
		   							// Star was not found in DB | create the entry
		   							$s = new pornstarObj(array('',(string)$pornstar->name, (string)$pornstar->homepage, ''));
		   							$vcd->addPornstars($PORNClass->addPornstar($s));
		   						}
		   					}
		   				}
		   				
		   				
		   				
		   				// Set the studio if any
		   				$studio = $item->studio;
		   				if (sizeof($studio) > 0) {
		   					$studioObj = $PORNClass->getStudioByName((string)$studio->name);
		   					if ($studioObj instanceof studioObj ) {
		   						$vcd->setStudioID($studioObj->getID());
		   					} else {
		   						$studioObj = new studioObj(array('', (string)$studio->name));
		   						$PORNClass->addStudio($studioObj);
		   						
		   						// Find the just added studioObj
		   						$studioObj = $PORNClass->getStudioByName((string)$studio->name);
		   						// And add it to the movie
		   						if ($studioObj instanceof studioObj ) {
		   							$vcd->setStudioID($studioObj->getID());
		   						}
		   						
		   					}
		   				}
		   				
		   				$sourceSiteObj = $SETTINGSClass->getSourceSiteByAlias('DVDempire');
						if ($sourceSiteObj instanceof sourceSiteObj ) {
							$source_id = $sourceSiteObj->getsiteID();		
						}
						
						// Add the adult categories if any
						$adult_categories = $item->adult_category->category;
						if (!is_null($adult_categories) && sizeof($adult_categories > 0)) {
							foreach ($adult_categories as $xmlcat) {
								$catObj = new porncategoryObj(array((string)$xmlcat->id, (string)$xmlcat->name));
								$vcd->addAdultCategory($catObj);
							}
						}
		   				
		   				
		   						   			
		   			} else {
		   				// Normal flick
		   				
		   				if (isset($item->imdb)) {
		   				
		   					$imdb = $item->imdb;
		   					
			   				// Create the IMDB obj
							$obj = new imdbObj();
							$obj->setIMDB((string)$imdb->imdb_id);
							$obj->setTitle(utf8_decode((string)$imdb->title));
							$obj->setYear((string)$imdb->year);
							$obj->setDirector((string)$imdb->director);
							$obj->setGenre((string)$imdb->genre);
							$obj->setRating((string)$imdb->rating);
							$obj->setCast(utf8_decode(ereg_replace("\|",13,(string)$imdb->cast)));
							$obj->setPlot((string)$imdb->plot);
							$obj->setRuntime((string)$imdb->runtime);
							$obj->setCountry((string)$imdb->country);
							
							// Add the imdbObj to the VCD
							$vcd->setIMDB($obj);
			   				
			   				}
			   				
			   			$sourceSiteObj = $SETTINGSClass->getSourceSiteByAlias('imdb');
						if ($sourceSiteObj instanceof sourceSiteObj ) {
							$source_id = $sourceSiteObj->getsiteID();		
						}
						
		   			}

		   			$external_id = (string)$item->external_id;
		   			
		   			// Set the source site
		   			if ($source_id != '' && $external_id != '') {
						$vcd->setSourceSite($source_id, $external_id);
		   			}
					
		   			
		   			// If thumbnails XML were exported, find the image in the imported image array ...
		   			if ($use_covers) {
		   				$old_vcdid = (string)$item->id;
		   				foreach ($imported_thumbnails as $arr) {
		   					
		   					$vcd_id = $arr['vcd_id'];
		   					
		   					if ($vcd_id == $old_vcdid) {
		   								   						
		   						$coverObj = $arr['obj'];
		   								   						
		   						if ($coverObj instanceof cdcoverObj ) {
		   							$vcd->addCovers(array($coverObj));
		   						} else {
		   							VCDException::display('Obj is not a CDcover!');
		   						}
		   						
		   						
		   						
		   						break;
		   					}
		   				}
		   			}
		   			
		   			array_push($imported_movies, $vcd);
				}
		   }
		   
		   
		   // Create the results display array
		   $results_array = array();
		   
		   		   
		   $VCDClass = VCDClassFactory::getInstance('vcd_movie');
		   foreach ($imported_movies as $cdobj) {
		   		
		   		$new_vcdid = $VCDClass->addVcd($cdobj);
		   		if (is_numeric($new_vcdid) && $new_vcdid > 0) {
		   			$itemresult = array('status' => 1, 'title' => $cdobj->getTitle(), 'thumb' => $cdobj->getCoverCount());
		   		} else {
		   			$itemresult = array('status' => 0, 'title' => $cdobj->getTitle(), 'thumb' => 0);
		   		}
				array_push($results_array, $itemresult);
		   		
		   }
		   
	      
		   unset($xml);
		   fs_unlink($upfile);
		   if ($use_covers) {
		   		fs_unlink($upthumbfile);
		   }
		   
		   $_SESSION['xmlresults'] = $results_array;
		   redirect('?page=private&o=add&source=xmlresults');
		   
		   
		   
		   } catch (Exception $ex) {
		   		throw $ex;
		   }
		   

}






?>