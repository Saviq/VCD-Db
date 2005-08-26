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
		print "<br/><a href=\"./addrssfeed.php\">".$language->show('X_TRYAGAIN')."</a>";
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
	Process user uploaded XML file containing exported movies from
	another vcd-db
*/

function checkMovieImport(&$out_movietitles) {

	global $ClassFactory;
	$upload =& new uploader();
	$SETTINGSClass = $ClassFactory->getInstance("vcd_settings");
	$path = $SETTINGSClass->getSettingsByKey('SITE_ROOT');
	
	if($_FILES){
	  foreach($_FILES as $key => $file){
	    $upload->set("name",$file["name"]); // Uploaded file name.
	    $upload->set("type",$file["type"]); // Uploaded file type.
	   	$upload->set("tmp_name",$file["tmp_name"]); // Uploaded tmp file name.
	    $upload->set("error",$file["error"]); // Uploaded file error.
	    $upload->set("size",$file["size"]); // Uploaded file size.
	    $upload->set("fld_name",$key); // Uploaded file field name.
		$upload->set("max_file_size",10192000); // Max size allowed for uploaded file in bytes =  ~10 MB.
	    $upload->set("supported_extensions",array("xml" => "text/xml")); // Allowed extensions and types for uploaded file.
	    $upload->set("randon_name",true); // Generate a unique name for uploaded file? bool(true/false).
		$upload->set("replace",true); // Replace existent files or not? bool(true/false).
		$upload->set("dst_dir",$_SERVER["DOCUMENT_ROOT"]."".$path."upload/"); // Destination directory for uploaded files.
		$result = $upload->moveFileToDestination(); // $result = bool (true/false). Succeed or not.
	  }
	}
	
	if($upload->succeed_files_track){
	      $file_arr = $upload->succeed_files_track; 
	      $upfile = $file_arr[0]['destination_directory'].$file_arr[0]['new_file_name'];
			      
	       /* 
	       		Process the XML file
	       */
	     	
		   if (fs_file_exists($upfile)) {
	    		$xml = simplexml_load_file($upfile);
		   } else {
	    		VCDException::display('Failed to open the uploaded file');
	    		return false;
		   }
		
		   		
			
		   // Generate Objects from the XML file ...
		   $movies = $xml->movie;
		   $imported_movies = array();
		   $adult_cat = $SETTINGSClass->getCategoryIDByName('adult');
		   
		   if (sizeof($movies) == 0) {
		   		print "No movies found in the XML file.<br/>Make sure that you are uploading
		   		       VCD-db generated XML file.";
		   } else {
		   		foreach ($movies as $item) {
		   			if (strcmp($item->title, "") != 0) {
		   				$title = (string)$item->title;
			    		array_push($out_movietitles, $title);
		   			}
				}
		   }
		   
		   unset($xml);
			
	
	      
	} else {
		VCDException::display('Error uploading file');
		return false;
	}
	
	return $file_arr[0]['new_file_name'];

}


function processXMLMovies($upfile, $use_covers) {
	
			global $ClassFactory;
			$SETTINGSClass = $ClassFactory->getInstance("vcd_settings");
	
	       if (fs_file_exists($upfile)) {
	    		$xml = simplexml_load_file($upfile);
		   } else {
	    		VCDException::display('Failed to open the uploaded file<break> for the movies');
		   }
		
		   
		   
		   // User has thumbnail XML file uploaded as well
		   if ($use_covers) {
		   		// Begin thumbnail upload

				$upload =& new uploader();
				$path = $SETTINGSClass->getSettingsByKey('SITE_ROOT');
				
				if($_FILES){
				  foreach($_FILES as $key => $file){
				    $upload->set("name",$file["name"]); // Uploaded file name.
				    $upload->set("type",$file["type"]); // Uploaded file type.
				   	$upload->set("tmp_name",$file["tmp_name"]); // Uploaded tmp file name.
				    $upload->set("error",$file["error"]); // Uploaded file error.
				    $upload->set("size",$file["size"]); // Uploaded file size.
				    $upload->set("fld_name",$key); // Uploaded file field name.
					$upload->set("max_file_size",10192000); // Max size allowed for uploaded file in bytes =  ~10 MB.
				    $upload->set("supported_extensions",array("xml" => "text/xml")); // Allowed extensions and types for uploaded file.
				    $upload->set("randon_name",true); // Generate a unique name for uploaded file? bool(true/false).
					$upload->set("replace",true); // Replace existent files or not? bool(true/false).
					$upload->set("dst_dir",$_SERVER["DOCUMENT_ROOT"]."".$path."upload/"); // Destination directory for uploaded files.
					$result = $upload->moveFileToDestination(); // $result = bool (true/false). Succeed or not.
				  }
				}
				
				if($upload->succeed_files_track){
				      $file_arr = $upload->succeed_files_track; 
				      $upthumbfile = $file_arr[0]['destination_directory'].$file_arr[0]['new_file_name'];
						      
				       /* 
				       		Process the XML Thumbnail file
				       */
				     	
					   if (fs_file_exists($upthumbfile)) {
				    		$xmlthumbnails = simplexml_load_file($upthumbfile);
					   } else {
				    		VCDException::display('Failed to open the thumbnails file');
				    		return false;
					   }
					
					   		
						
					   // GenerateObjects from the XML file ...
					   $thumbnails = $xmlthumbnails->cdcover;
					   $imported_thumbnails = array();
					   
					   
					   if (sizeof($thumbnails) == 0) {
					   		print "No thumbnails found in the XML file.<br/>Make sure that you are uploading
					   		       VCD-db generated XML file.";
					   } else {
					   	
					   	
					   		// Get a Thumbnail CoverTypeObj
							$COVERClass = $ClassFactory->getInstance("vcd_cdcover");
					   	
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
					VCDException::display('Error uploading thumbnails file');
					return false;
				}
		   		
		   		   	
		   		
		   
		   		// End thumbnail upload
		   }
		   
		   
		   
		   
		   
		   		
			
		   // GenerateObjects from the XML file ...
		   $movies = $xml->movie;
		   $imported_movies = array();
		   $adult_cat = $SETTINGSClass->getCategoryIDByName('adult');
		   $PORNClass = new vcd_pornstar();
		   
		   if (sizeof($movies) == 0) {
		   		print "No movies found in the XML file.<br/>Make sure that you are uploading
		   		       VCD-db generated XML file.";
		   } else {
		   		foreach ($movies as $item) {
			    	
		   			
		   			// Create the basic CD obj
					$basic = array('', (string)$item->title, (string)$item->category_id, (string)$item->year);
					$vcd = new vcdObj($basic);
					
					// Add 1 instance
					$mediaTypeObj = $SETTINGSClass->getMediaTypeByID((string)$item->mediatype_id);
					
					$vcd->addInstance($_SESSION['user'], $mediaTypeObj, (string)$item->cds, (string)$item->dateadded);
					$vcd->setMovieCategory($SETTINGSClass->getMovieCategoryByID((string)$item->category_id));
		   			
		   			
		   			
		   			$source_id = '';
		   			
		   			if ($item->category_id == $adult_cat) {
		   				// Adult flick
		   				
		   				// Check if any pornstars are associated in the movie
		   				$pornstars = $item->pornstars->pornstar;
		   				if (isset($pornstars)) {
		   					
		   					
		   					foreach ($pornstars as $pornstar) {
		   						$starObj = $PORNClass->getPornstarByName($pornstar);
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
		   						// Maybe later create the new studio entry ...
		   						
		   					}
		   				}
		   				
		   				$sourceSiteObj = $SETTINGSClass->getSourceSiteByAlias('DVDempire');
						if ($sourceSiteObj instanceof sourceSiteObj ) {
							$source_id = $sourceSiteObj->getsiteID();		
						}
						
						// Add the adult categories if any
						$adult_categories = $item->adult_category->category;
						if (sizeof($adult_categories > 0)) {
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
							$obj->setTitle((string)$imdb->title);
							$obj->setYear((string)$imdb->year);
							$obj->setDirector((string)$imdb->director);
							$obj->setGenre((string)$imdb->genre);
							$obj->setRating((string)$imdb->rating);
							$obj->setCast(ereg_replace("\|",13,(string)$imdb->cast));
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
		   							return false;
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
		   
		   
		   $VCDClass = new vcd_movie();
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

}






?>