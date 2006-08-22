<?php
/*
 * VCD-db - a web based VCD/DVD Catalog system
 * Copyright (C) 2003-2004 Konni - konni.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 */
 // $Id:
?>
<?
include_once("classes/includes.php");

if (!VCDUtils::isLoggedIn()) {
	die("Unauthorized Access");
}


if (isset($_GET['action']) && sizeof($_POST) > 0) {
	$form = $_GET['action'];
} else {
	die("I only accept post!");
}

$reload_and_close = true;


$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");
$VCDClass = VCDClassFactory::getInstance("vcd_movie");

switch ($form) {

	case 'borrower':
		$obj = new borrowerObj(array("",VCDUtils::getUserID(),$_POST['borrower_name'], $_POST['borrower_email']));
		$SETTINGSClass->addBorrower($obj);
		VCDUtils::setMessage("(Added ".$_POST['borrower_name']." to your list)");
		break;

	case 'edit_borrower':
		$borrowerObj = $SETTINGSClass->getBorrowerByID($_POST['borrower_id']);
		$borrowerObj->setEmail($_POST['borrower_email']);
		if (isset($_POST['borrower_name']) && strlen($_POST['borrower_name']) > 0) {
			$borrowerObj->setName($_POST['borrower_name']);
		}
		$SETTINGSClass->updateBorrower($borrowerObj);
		VCDUtils::setMessage("(".$borrowerObj->getName()." has been updated)");
		redirect('?page=private&o=settings');
		break;


	case 'loan':
		$arrMovies = split("#",$_POST['id_list']);
		$borrower_id = $_POST['borrowers'];
		$SETTINGSClass->loanCDs($borrower_id, $arrMovies);
		VCDUtils::setMessage("(Movies successfully loaned)");
		header("Location: ".$_SERVER['HTTP_REFERER']."");
		break;

	case 'addmetadata':
		if (isset($_POST['metadataname']) && isset($_POST['metadatadescription'])) {
			$metaname = trim($_POST['metadataname']);
			// Strip all spaces in between
			$metaname = preg_replace('/\s/', '', $metaname);
			$metadescription = trim($_POST['metadatadescription']);

			if (strcmp($metaname, "") != 0 && strcmp($metadescription,"") != 0) {

				$obj = new metadataTypeObj('', $metaname, $metadescription, VCDUtils::getUserID());
				$SETTINGSClass->addMetaDataType($obj);
			}


			header("Location: ".$_SERVER['HTTP_REFERER']."");

		}

		break;

	case 'addfeed':

		$feeds = $_POST['feeds'];
		foreach ($feeds as $feed) {
			$currFeed = explode("|",$feed);
			$SETTINGSClass->addRssfeed(VCDUtils::getUserID(), $currFeed[0], $currFeed[1]);
		}

		break;

	case 'addcomment':


		if (isset($_POST['comment']) && isset($_POST['vcd_id'])) {
			$is_private = 0;
			if (isset($_POST['private'])) {
				$is_private = 1;
			}
			$commObj = new commentObj(array('', $_POST['vcd_id'], VCDUtils::getUserID(), '', $_POST['comment'], $is_private));

			if (strlen($_POST['comment']) > 0) {
				$SETTINGSClass->addComment($commObj);
			}

		}

		redirect("?page=cd&vcd_id=".$_POST['vcd_id']."#comments");

		break;

	case 'addlisted':
		$arrMovies = split("#",$_POST['id_list']);
		if (is_array($arrMovies) && sizeof($arrMovies) > 0) {
			// Push the ID list to session
			$_SESSION['listed'] = $arrMovies;
			redirect('.?page=private&o=add&source=listed');
		} else {
			redirect();
		}

		break;


	/* Update media player settings */
	case 'player':
		$obj = new metadataObj(array('',0,VCDUtils::getUserID(), metadataTypeObj::SYS_PLAYER, $_POST['player']));
		$SETTINGSClass->addMetadata($obj);
		$obj = new metadataObj(array('',0,VCDUtils::getUserID(), metadataTypeObj::SYS_PLAYERPATH, $_POST['params']));
		$SETTINGSClass->addMetadata($obj);
		redirect('pages/player.php');
		break;


	case 'listedconfirm':

		$VCDClass = new vcd_movie();

		if (isset($_POST['disccount'])) {
			$j = $_POST['disccount'];
			for ($i=0; $i < $j; $i++) {

				$key = "item_".$i;
				$cds = "cds_".$i;
				$valkey = $_POST[$key];
				$valcds = $_POST[$cds];
				$arr = split("\\|",$valkey);
				$VCDClass->addVcdToUser(VCDUtils::getUserID(), $arr[0], $arr[1], $valcds);
			}
		}
		redirect();
		break;

	case 'edit_frontpage':

		if (isset($_POST['stats']) && strcmp($_POST['stats'], "yes") == 0) {
			// User wants to see statistics
			$frontstatsObj = new metadataObj(array('',0, VCDUtils::getUserID(), metadataTypeObj::SYS_FRONTSTATS , 1));
		} else {
			$frontstatsObj = new metadataObj(array('',0, VCDUtils::getUserID(),  metadataTypeObj::SYS_FRONTSTATS, 0));
		}

		if (isset($_POST['sidebar']) && strcmp($_POST['sidebar'], "yes") == 0) {
			// User wants to see sidebar
			$frontbarObj = new metadataObj(array('',0, VCDUtils::getUserID(),  metadataTypeObj::SYS_FRONTBAR , 1));
		} else {
			$frontbarObj = new metadataObj(array('',0, VCDUtils::getUserID(), metadataTypeObj::SYS_FRONTBAR , 0));
		}

		if (isset($_POST['rss_list']) && strlen($_POST['rss_list']) > 1) {
			$frontRssObj = new metadataObj(array('',0, VCDUtils::getUserID(), metadataTypeObj::SYS_FRONTRSS , $_POST['rss_list']));
		} else {
			$frontRssObj = new metadataObj(array('',0, VCDUtils::getUserID(), metadataTypeObj::SYS_FRONTRSS , $_POST['rss_list']));
		}

		$SETTINGSClass->addMetadata(array($frontbarObj, $frontRssObj, $frontstatsObj));


		redirect('?page=private&o=settings');

		break;


	case 'update_ignorelist':
		if (isset($_POST['id_list'])) {
			// Save the ignore list to database
			$obj = new metadataObj(array('',0, VCDUtils::getUserID(), metadataTypeObj::SYS_IGNORELIST , $_POST['id_list']));
			$SETTINGSClass->addMetadata($obj);
		}

		redirect('?page=private&o=settings');
		break;

	case 'addfromxml':
		// call to XMLFunctions
		$movie_titles = array();

		try {
			$file_name = checkMovieImport($movie_titles);
		} catch (Exception $ex) {
			VCDException::display($ex, true);
		}


		if ($file_name) {
			$_SESSION['xmldata'] = $movie_titles;
			$_SESSION['xmlfilename'] = $file_name;
			redirect('?page=private&o=add&source=xml');
		} else {
			$reload_and_close = false;
		}

		break;

	/* Actually insert the XML data */
	case 'xmlconfirm':
		$filename = "";
		if (isset($_POST['filename'])) {
			$filename = $_POST['filename'];
			$use_covers = false;

			$fullpath = TEMP_FOLDER.$filename;
			if (!fs_is_dir($fullpath) && fs_file_exists($fullpath)) {

				if (isset($_POST['xmlthumbs'])) {
					$use_covers = true;
				}

				// Process the file
				try {
					processXMLMovies($fullpath, $use_covers);
				} catch (Exception $ex) {
					VCDException::display($ex, true);
				}

			} else {
				VCDException::display('Could not open file ' . $fullpath, true);
			}
		}
		break;

	case 'addfromexcel':
		$movie_titles = array();
		$file_name = checkExcelImport($movie_titles);

		if ($file_name) {
			$_SESSION['exceldata'] = $movie_titles;
			$_SESSION['excelfilename'] = $file_name;
			redirect('?page=private&o=add&source=excel');
		} else {
			redirect('?page=private&o=new');
		}
		break;

	/* Actually insert the Excel data */
	case 'excelconfirm':
		$filename = "";
		if (isset($_POST['filename'])) {
			$filename = $_POST['filename'];

			$fullpath = TEMP_FOLDER.$filename;
			if (!fs_is_dir($fullpath) && fs_file_exists($fullpath)) {
				// Process the file
				processExcelMovies($fullpath);
			}
		}
		break;

	case 'add_manually':
		// Create the basic CD obj
		$basic = array("", $_POST['title'], $_POST['category'], $_POST['year']);
		$vcd = new vcdObj($basic);
		// Add 1 instance
		$vcd->addInstance($_SESSION['user'], $SETTINGSClass->getMediaTypeByID($_POST['mediatype']), $_POST['cds'], mktime());
		// if file was uploaded .. lets process it ..

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
				$COVERClass = VCDClassFactory::getInstance("vcd_cdcover");
				$coverTypeObj = $COVERClass->getCoverTypeByName("thumbnail");
				$cover->setCoverTypeID($coverTypeObj->getCoverTypeID());
				$cover->setCoverTypeName("thumbnail");
				$cover->setFilename($fileObj->getFileName());
				$vcd->addCovers(array($cover));
				
				
				// CleanUp
				unset($im);
				$fileObj->delete();
				
			
			} catch (Exception $ex) {
				VCDException::display($ex, true);
				exit();
			}
		}


		// Forward the movie to the Business layer
		$new_id = $VCDClass->addVcd($vcd);


		// Insert the user comments if any ..
		if (isset($_POST['comment']) && (strlen($_POST['comment']) > 1)) {
			$is_private = 0;
			if (isset($_POST['private'])) {
				$is_private = 1;
			}

			$commObj = new commentObj(array('', $new_id, VCDUtils::getUserID(), '', $_POST['comment'], $is_private));
			$SETTINGSClass->addComment($commObj);
		}

		if (is_numeric($new_id) && $new_id != -1) {
			redirect("?page=cd&vcd_id=".$new_id."");
		}

		break;



	/* Add a movie to the database from the web-fetch form */
	case 'moviefetch':

		// Get the fetchedObj from session and unset it from session
		$fetchedObj = $_SESSION['_fetchedObj'];
		unset($_SESSION['_fetchedObj']);
		
		// Create the basic CD obj
		$basic = array("", $_POST['title'], $_POST['category'], $_POST['year']);
		$vcd = new vcdObj($basic);

		// Add 1 instance
		$vcd->addInstance($_SESSION['user'], $SETTINGSClass->getMediaTypeByID($_POST['mediatype']), $_POST['cds'], mktime());

		// Create the IMDB obj
		$obj = new imdbObj();
		$obj->setIMDB($_POST['imdb']);
		$obj->setTitle($_POST['imdbtitle']);
		$obj->setAltTitle($_POST['alttitle']);
		$obj->setYear($_POST['year']);
		$obj->setImage($_POST['image']);
		$obj->setDirector($_POST['director']);
		$obj->setGenre($_POST['categories']);
		$obj->setRating($_POST['rating']);
		$obj->setCast($_POST['cast']);
		$obj->setPlot($_POST['plot']);
		$obj->setRuntime($_POST['Runtime']);
		$obj->setCountry($_POST['Country']);

		// Add the imdbObj to the VCD
		$vcd->setIMDB($obj);


		// Add the thumbnail as a cover if any was found on IMDB
		if (isset($_POST['image']) && strcmp($_POST['image'], "") != 0) {
			$cover = new cdcoverObj();

			// Get a Thumbnail CoverTypeObj
			$COVERClass = VCDClassFactory::getInstance("vcd_cdcover");
			$coverTypeObj = $COVERClass->getCoverTypeByName("thumbnail");
			$cover->setCoverTypeID($coverTypeObj->getCoverTypeID());
			$cover->setCoverTypeName("thumbnail");


			$cover->setFilename($_POST['image']);
			$vcd->addCovers(array($cover));
		}


		// Set the source site
		$sourceSiteObj = $SETTINGSClass->getSourceSiteByID($fetchedObj->getSourceSiteID());
		if ($sourceSiteObj instanceof sourceSiteObj ) {
			$vcd->setSourceSite($sourceSiteObj->getsiteID(), $_POST['imdb']);
		}

		// Forward the movie to the Business layer
		$new_id = $VCDClass->addVcd($vcd);


		// Insert the user comments if any ..
		if (isset($_POST['comment']) && (strlen($_POST['comment']) > 1)) {
			$is_private = 0;
			if (isset($_POST['private'])) {
				$is_private = 1;
			}

			$commObj = new commentObj(array('', $new_id, VCDUtils::getUserID(), '', $_POST['comment'], $is_private));
			$SETTINGSClass->addComment($commObj);
		}


		if (is_numeric($new_id) && $new_id != -1) {
			redirect("?page=cd&vcd_id=".$new_id."");
		}

		break;


		/* Add adult movie from Web-fetch */
		case 'adultmoviefetch':
			// Get the fetchedObj from session and unset it from session
			$fetchedObj = $_SESSION['_fetchedObj'];
			unset($_SESSION['_fetchedObj']);
			
			
			// Create the basic CD obj
			$basic = array("", $_POST['title'], $_POST['category'], $_POST['year']);
			$vcd = new vcdObj($basic);

			// Add 1 instance
			$vcd->addInstance($_SESSION['user'], $SETTINGSClass->getMediaTypeByID($_POST['mediatype']), $_POST['cds'], mktime());

			// Set the categoryObj
			$vcd->setMovieCategory($SETTINGSClass->getMovieCategoryByID($_POST['category']));


			// Add the thumbnail as a cover if any was found on IMDB
			if (isset($_POST['thumbnail'])) {
				$cover = new cdcoverObj();

				// Get a Thumbnail CoverTypeObj
				$COVERClass = VCDClassFactory::getInstance("vcd_cdcover");
				$coverTypeObj = $COVERClass->getCoverTypeByName("thumbnail");
				$cover->setCoverTypeID($coverTypeObj->getCoverTypeID());
				$cover->setCoverTypeName("thumbnail");


				$cover->setFilename($_POST['thumbnail']);
				$vcd->addCovers(array($cover));
			}


			// Set the source site
			$sourceSiteObj = $SETTINGSClass->getSourceSiteByID($fetchedObj->getSourceSiteID());
			if ($sourceSiteObj instanceof sourceSiteObj ) {
				$vcd->setSourceSite($sourceSiteObj->getsiteID(), $_POST['id']);
			}

			// Set the adult studio if any
			if (isset($_POST['studio']) && is_numeric($_POST['studio'])) {
				$vcd->setStudioID($_POST['studio']);
			}

			// Associate the existing pornstars to the CD
			$PORNClass = new vcd_pornstar();


			// Set the adult categories
			if (isset($_POST['id_list'])) {
	     		$adult_categories = split('#',$_POST['id_list']);

	     		if (sizeof($adult_categories) > 0) {
					foreach ($adult_categories as $adult_catid) {
						$catObj = $PORNClass->getSubCategoryByID($adult_catid);
						if ($catObj instanceof porncategoryObj ) {
							$vcd->addAdultCategory($catObj);
						}

					}
				}
	     	}



			if (isset($_POST['pornstars'])) {
				$pornstars = array_unique($_POST['pornstars']);
				foreach ($pornstars as $pornstar_id) {
					$vcd->addPornstars($PORNClass->getPornstarByID($pornstar_id));
				}
			}



			// and the new ones after we create them
			if (isset($_POST['pornstars_new'])) {
				$pornstars_new = array_unique($_POST['pornstars_new']);
				foreach ($pornstars_new as $new_names) {
					$vcd->addPornstars($PORNClass->addPornstar(new pornstarObj(array("",$new_names, "","",""))));
				}
			}


			// Check what images to fetch
			$screenFiles = array();
			if (isset($_POST['imagefetch'])) {
				$imagefetchArr = $_POST['imagefetch'];

				foreach ($imagefetchArr as $image_type) {
					if (strcmp($image_type, "screenshots") == 0) {

						if (isset($_POST['screenshotcount'])) {
							$screencount = $_POST['screenshotcount'];
							$screenFiles = $fetchedObj->getScreenShotImages();
						}


					} else {

						// Fetch the image from the sourceSite
						$path = $fetchedObj->getImageLocation($image_type);
						$image_name = VCDUtils::grabImage($path);

						$cover = new cdcoverObj();
						$COVERClass = VCDClassFactory::getInstance("vcd_cdcover");
						$coverTypeObj = $COVERClass->getCoverTypeByName($image_type);
						$cover->setCoverTypeID($coverTypeObj->getCoverTypeID());
						$cover->setCoverTypeName($image_type);

						$cover->setFilename($image_name);

						$vcd->addCovers(array($cover));

					}
				}

			}



			// Forward the movie to the Business layer
			$new_id = $VCDClass->addVcd($vcd);

			// Was I supposed to grab some screenshots ?
			if (sizeof($screenFiles) > 0) {

				// Does the destination folder exist?
				if (!fs_is_dir(ALBUMS.$new_id)) {
					if (fs_mkdir(ALBUMS.$new_id, 0755)) {

						foreach ($screenFiles as $screenshotImage) {
							VCDUtils::grabImage($screenshotImage, false, ALBUMS.$new_id."/");
						}

						// Mark thumbnails to movie in DB
						$VCDClass->markVcdWithScreenshots($new_id);

					} else {
						VCDException::display("Could not create directory ".ALBUMS.$new_id."<break>Check permissions");
					}
				}


			}

			// Insert the user comments if any ..
			if (isset($_POST['comment']) && (strlen($_POST['comment']) > 1)) {
				$is_private = 0;
				if (isset($_POST['private'])) {
					$is_private = 1;
				}

				$commObj = new commentObj(array('', $new_id, VCDUtils::getUserID(), '', $_POST['comment'], $is_private));
				$SETTINGSClass->addComment($commObj);
			}

			
			if (is_numeric($new_id) && $new_id != -1) {
				redirect("?page=cd&vcd_id=".$new_id."");
			}


		break;

		case 'updatepornstar':

			$pornstar_id = $_POST['star_id'];
			$pornstar_name = $_POST['name'];
			$pornstar_bio = $_POST['bio'];
			$pornstar_url = $_POST['www'];


			$PORNClass = VCDClassFactory::getInstance("vcd_pornstar");
			$pornstar = $PORNClass->getPornstarByID($pornstar_id);
			$pornstar->setName($pornstar_name);
			$pornstar->setHomePage($pornstar_url);
			$pornstar->setBiography($pornstar_bio);


			// if file was uploaded .. lets process it ..

				$upload =& new uploader();
				$path = $SETTINGSClass->getSettingsByKey('SITE_ROOT');


				if($_FILES){

				  foreach($_FILES as $key => $file){

				  	$savePath = $_SERVER["DOCUMENT_ROOT"]."".$path."upload/";
		  			$arrFileExt = array("jpg" => "image/pjpeg", "jpg" => "image/jpeg" ,"gif" => "image/gif");
		  			prepareUploader($upload, $file, $key, VSIZE_THUMBS, $arrFileExt, $savePath, false, false);
					$result = $upload->moveFileToDestination(); // $result = bool (true/false). Succeed or not.

				  }


				  if($upload->succeed_files_track){
			       	   $file_arr = $upload->succeed_files_track;
			      	   $upfile = $file_arr[0]['destination_directory'].$file_arr[0]['new_file_name'];
			      	   $f_name = $upload->succeed_files_track[0]['file_name'];

			      	   // Check if image should be resized
			      	   if (isset($_POST['resize']) && $_POST['resize']) {

			      	   		// Release the file hook
			      	   		unset($upload);

			      	   		$im = new Image_Toolbox(TEMP_FOLDER.$f_name);
							$im->newOutputSize(0,200);
							$im->save(PORNSTARIMAGE_PATH.$f_name, 'jpg');
							unset($im);
			      	   		fs_unlink(TEMP_FOLDER.$f_name);

					  	} else {
					  		fs_rename(TEMP_FOLDER.$f_name, PORNSTARIMAGE_PATH.$f_name);
					  	}


					  	$pornstar->setImageName($f_name);

					} else {
						if ($upload->fail_files_track[0]['error_type'] != 6) {
				  		 	VCDException::display($upload->fail_files_track[0]['msg'],true);
					  	}
					}
				}

			$PORNClass->updatePornstar($pornstar);

			if (isset($_POST['update'])) {
				redirect("pages/pmanager.php?pornstar_id=".$pornstar_id.""); /* Redirect back to form */
			}


			break;


	case 'updatemovie':

		 $errors = false;

		 // Basic data
		 $cd_id = $_POST['cd_id'];
    	 $title = $_POST['title'];
	     $category = $_POST['category'];
	     $year = $_POST['year'];
	     if (isset($_POST['imdb'])) {
	     	$imdb = $_POST['imdb'];
	     }




	     // Fetch the current data from DB
	     $VCDClass = VCDClassFactory::getInstance('vcd_movie');
	     $vcd = $VCDClass->getVcdByID($cd_id);



	     $vcd->setYear($year);
	     $vcd->setTitle($title);


	     $movieCategoryObj = $SETTINGSClass->getMovieCategoryByID($category);
	     if ($movieCategoryObj instanceof movieCategoryObj ) {
	     	$vcd->setMovieCategory($movieCategoryObj);
	     }

	     // External ID already exists
	     if ($vcd->getSourceSiteID() == $SETTINGSClass->getSourceSiteByAlias('imdb')->getsiteID()) {
	     	$vcd->setSourceSite($vcd->getSourceSiteID(), $imdb);
	     }



	     // is this by any means blue movie ?
	     if ($category == $SETTINGSClass->getCategoryIDByName('adult')) {
	     	// Blue movie data
	     	if (isset($_POST['id_list'])) {
	     		$subCatArr = split('#',$_POST['id_list']);
	     		$PORNClass = VCDClassFactory::getInstance('vcd_pornstar');
	     		foreach ($subCatArr as $adult_catid) {
	     			$adultCatObj = null;
	     			if (is_numeric($adult_catid)) {
	     				$adultCatObj = $PORNClass->getSubCategoryByID($adult_catid);
	     			}
	     			if ($adultCatObj instanceof porncategoryObj ) {
	     				$vcd->addAdultCategory($adultCatObj);
	     			}
	     		}
	     	}


	     	if (isset($_POST['studio']) && is_numeric($_POST['studio']))  {
	     		$vcd->setStudioID($_POST['studio']);
	     	}



	     } else {
	     	// Imdb data
	     	 $imdb_title = $_POST['imdbtitle'];
		     $imdb_alttitle =  $_POST['imdbalttitle'];
		     $imdb_grade = $_POST['imdbgrade'];
		     $imdb_runtime = $_POST['imdbruntime'];
		     $imdb_director =  $_POST['imdbdirector'];
		     $imdb_countries = $_POST['imdbcountries'];
		     $imdb_categories = $_POST['imdbcategories'];
		     $imdb_plot = $_POST['plot'];
		     $imdb_actors =  $_POST['actors'];

	     	// Get current data
	     	 $imdbObj = $vcd->getIMDB();
	     	 if (!$imdbObj instanceof imdbObj ) {
	     	 	$imdbObj = new imdbObj();
	     	 	$imdbObj->setIMDB($imdb);
	     	 }
     	 	 $imdbObj->setTitle($imdb_title);
     	 	 $imdbObj->setAltTitle($imdb_alttitle);
     	 	 $imdbObj->setRating($imdb_grade);
     	 	 $imdbObj->setDirector($imdb_director);
      	 	 $imdbObj->setCountry($imdb_countries);
     	 	 $imdbObj->setGenre($imdb_categories);
     	 	 $imdbObj->setPlot($imdb_plot);
     	 	 $imdbObj->setCast($imdb_actors);
     	 	 $imdbObj->setRuntime($imdb_runtime);

     	 	 if (strcmp($imdbObj->getIMDB(), "" != 0)) {
     	 	 	$vcd->setIMDB($imdbObj);
     	 	 }

	     }

	     // Check if user has updated his cd item
	     $arrCopies = $vcd->getInstancesByUserID(VCDUtils::getUserID());
	     if (sizeof($arrCopies) > 0) {
			$arrMediaTypes = $arrCopies['mediaTypes'];
			$arrNumcds = $arrCopies['discs'];
			// Loop through the instances and compare
			for ($i = 0; $i < sizeof($arrMediaTypes); $i++) {
				$postedMediaType = $_POST["userMediaType_".$i];
				$media_id = $arrMediaTypes[$i]->getmediaTypeID();
				$postedCDCount   = $_POST["usernumcds_".$i];
				if ($media_id == $postedMediaType && $arrNumcds[$i] == $postedCDCount) {}
					else {
						for($j = 0; $j < sizeof($arrMediaTypes); $j++) {
							$MediaType = $arrMediaTypes[$j];
							if (($MediaType->getmediaTypeID() == $postedMediaType) && ($i != $j)) {
		      					VCDException::display("You can not double the media type.");
								$double = true;
		      					$errors = true;
							} else $double = false;
						}
						if (!$double) {
							// Either media type or numCD's have been updated .. update entry to DB
							$VCDClass->updateVcdInstance($cd_id, $postedMediaType, $media_id, $postedCDCount, $arrNumcds[$i]);
						}
					}
			}

	     }

	     // Check if user has added a cd item
	     if ($_POST["userMediaType_".$i] != "null") {
			$postedMediaType = $_POST["userMediaType_".$i];
			$postedCDCount = $_POST["usernumcds_".$i];
	     	foreach($arrMediaTypes as $MediaType) {
	     		if ($MediaType->getmediaTypeID() == $postedMediaType) {
	     			VCDException::display("You can not double the media type.");
	     			$double = true;
	     			$errors = true;
	     		} else $double = false;
	     		if (!$double) {
	     			// Added media...
					$VCDClass->addVcdToUser(VCDUtils::getUserID(), $cd_id, $postedMediaType, $postedCDCount);
	     		}
	     	}
	     }


	    // Update metadata
	    if (isset($_POST['custom_index'])) {
	    	// add or update ?
	    	$metaArr = $SETTINGSClass->getMetadata($vcd->getID(), VCDUtils::getUserID(), metadataTypeObj::SYS_MEDIAINDEX );
	    	if (sizeof($metaArr) == 1) {
	    		$obj = $metaArr[0];
	    		$obj->setMetadataValue($_POST['custom_index']);
	    		$SETTINGSClass->updateMetadata($obj);
	    	} else {
	    		$obj = new metadataObj(array('',$cd_id, VCDUtils::getUserID(), metadataTypeObj::SYS_MEDIAINDEX , $_POST['custom_index']));
	    		$SETTINGSClass->addMetadata($obj);
	    	}
	    }

	    if (isset($_POST['filepath'])) {
	    	$obj = new metadataObj(array('',$cd_id, VCDUtils::getUserID(), metadataTypeObj::SYS_FILELOCATION , $_POST['filepath']));
	    	$SETTINGSClass->addMetadata($obj);
	    }


	    // Check for DVD Specific metadata
	    $is_dvd = false;
	    if (isset($_POST['current_dvd']) && is_numeric($_POST['current_dvd'])) {
	    	// This is DVD typed media type
	    	$is_dvd = true;

	    	$curr_dvd = $_POST['current_dvd'];
	    	$next_dvd = null;
	    	if (isset($_POST['selected_dvd']) && is_numeric($_POST['selected_dvd'])) {
	    		$next_dvd = $_POST['selected_dvd'];
	    	}

	    	$dvd_region = $_POST['dvdregion'];
	    	$dvd_format = $_POST['dvdformat'];
	    	$dvd_aspect = $_POST['dvdaspect'];
	    	$audio_list = $_POST['audio_list'];
	    	$sub_list = $_POST['sub_list'];

	    	$arrDVDMeta = array();
	    	$obj = new metadataObj(array('', $cd_id, VCDUtils::getUserID(), metadataTypeObj::SYS_DVDREGION, $dvd_region));
	    	array_push($arrDVDMeta, $obj);
	    	$obj = new metadataObj(array('', $cd_id, VCDUtils::getUserID(), metadataTypeObj::SYS_DVDFORMAT, $dvd_format));
	    	array_push($arrDVDMeta, $obj);
	    	$obj = new metadataObj(array('', $cd_id, VCDUtils::getUserID(), metadataTypeObj::SYS_DVDASPECT, $dvd_aspect));
	    	array_push($arrDVDMeta, $obj);
	    	$obj = new metadataObj(array('', $cd_id, VCDUtils::getUserID(), metadataTypeObj::SYS_DVDAUDIO, $audio_list));
	    	array_push($arrDVDMeta, $obj);
	    	$obj = new metadataObj(array('', $cd_id, VCDUtils::getUserID(), metadataTypeObj::SYS_DVDSUBS, $sub_list));
	    	array_push($arrDVDMeta, $obj);
	    	foreach ($arrDVDMeta as $metadataObj) {
	    		$metadataObj->setMediaTypeID($curr_dvd);
	    		$metadataObj->setMetadataTypeName(metadataTypeObj::getSystemTypeMapping($metadataObj->getMetadataTypeID()));
	    	}

	    	// Add / Update the DVD metadata
	    	//print_r($arrDVDMeta);
	    	//exit();
	    	$SETTINGSClass->addMetadata($arrDVDMeta, true);


	    }



	    // Handle metadata
	    $arrMetaData = array();
		foreach ($_POST as $key => $value) {
			if ((int)substr_count($key, 'meta') == 1) {
		 		array_push($arrMetaData, array('key' => $key, 'value' => $value));
		 	}
		}

		if (sizeof($arrMetaData) > 0) {
			$metadataCommit = array();
			foreach ($arrMetaData as $itemArr) {
				$key   = $itemArr['key'];
				$value = $itemArr['value'];
				$entry = explode("|", $key);
				$metadataName = $entry[1];
				$metadatatype_id = $entry[2];
				$mediatype_id = $entry[3];


				// Skip empty metadata
				if (strcmp($value, "") != 0) {
					$obj = new metadataObj(array('',$cd_id, VCDUtils::getUserID(), $metadataName, $value));
					$obj->setMetaDataTypeID($metadatatype_id);
					$obj->setMediaTypeID($mediatype_id);
					array_push($metadataCommit, $obj);
				}

			}


			$SETTINGSClass->addMetadata($metadataCommit, true);
			unset($metadataCommit);
			unset($arrMetaData);

		}




	    /*
	    	Process uploaded images
	    */
	    $upload = new uploader();
	    $path = $SETTINGSClass->getSettingsByKey('SITE_ROOT');

		if($_FILES){
		  foreach($_FILES as $key => $file){

		  	$savePath = $_SERVER["DOCUMENT_ROOT"]."".$path."upload/";
		  	$arrFileExt = array("jpg" => "image/pjpeg", "jpg" => "image/jpeg", "nfo" => "text/nfo", "txt" => "text/txt");
		  	prepareUploader($upload, $file, $key, VSIZE_COVERS, $arrFileExt, $savePath, false, true);
			$result = $upload->moveFileToDestination(); // $result = bool (true/false). Succeed or not.
		  }
		}

		if($upload->succeed_files_track){
		      $file_arr = $upload->succeed_files_track;


		      // Check which covertypes were uploaded and update them
		      $COVERClass = VCDClassFactory::getInstance('vcd_cdcover');
		      foreach ($upload->succeed_files_track as $cfile) {

		      		$cover_typeid = $cfile['field_name'];

		      		// Check if this uploaded file is a NFO file ..
		      		$nfostart = "meta|nfo";
		      		if (substr_count($cover_typeid, $nfostart) > 0)  {

		      			// Yeap it's a NFO file
		      			// Begin with moving the file to the NFO folder
		      			if (fs_file_exists(TEMP_FOLDER.$cfile['new_file_name'])) {

		      				if (!fs_rename(TEMP_FOLDER.$cfile['new_file_name'], NFO_PATH . $cfile['new_file_name'])) {
		      					VCDException::display("Could not move NFO file " . $$cfile['new_file_name'] . " to NFO folder!");
		      					$errors = true;
		      				} else {
		      					// Everything is OK ... add the metadata
								$entry = explode("|", $cover_typeid);
								$metadataName = $entry[1];
								$metadatatype_id = $entry[2];
								$mediatype_id = $entry[3];

								// Create the MetadataObject
								$obj = new metadataObj(array('',$cd_id, VCDUtils::getUserID(), $metadataName, $cfile['new_file_name']));
								$obj->setMetaDataTypeID($metadatatype_id);
								$obj->setMediaTypeID($mediatype_id);

								// And save to DB
								$SETTINGSClass->addMetadata($obj, true);

		      				}

		      			} else {
		      				VCDException::display("Could not find uploaded NFO file " . $$cfile['new_file_name']);
		      				$errors = true;
		      			}




		      		} else {
		      			$coverType = $COVERClass->getCoverTypeById($cover_typeid);

			      		$imginfo = array('', $cd_id, $cfile['new_file_name'], $cfile['file_size'],
			      							 VCDUtils::getUserID(), date(time()), $cover_typeid,
			      							 $coverType->getCoverTypeName(), '');
			      		$cdcover = new cdcoverObj($imginfo);
			      		$vcd->addCovers(array($cdcover));
		      		}
		      }
		 }

		if ($upload->fail_files_track) {
			foreach ($upload->fail_files_track as $msg) {
				if ($msg['error_type'] != 6) {
					VCDException::display("Error with file ".$msg['file_name']."<break>". $msg['msg']);
					$errors = true;
				}
			}
		}

		unset($upload);

		$VCDClass->updateVcd($vcd);


	    if (isset($_POST['update'])) {
	    	if ($errors) {
	    		print "<script>alert('Errors occurred');history.back(-1)</script>";
	    	} else {
	    		if ($is_dvd && is_numeric($next_dvd)) {
	    			redirect("pages/manager.php?cd_id=".$cd_id."&do=reload&curr_dvd=".$next_dvd.""); /* Redirect back to form */
	    		} else {
	    			redirect("pages/manager.php?cd_id=".$cd_id."&do=reload"); /* Redirect back to form */
	    		}
	    	}
	    }



		break;





	default:
		die("Unspecified form handler!");
}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head><title>VCD Gallery</title>
	<link rel="stylesheet" type="text/css" href="<?=STYLE?>style.css"/>
	<script src="includes/js/main.js" type="text/javascript"></script>
</head>
<body <?if ($reload_and_close) { reloadandclose(); } ?>>



</body>
</html>