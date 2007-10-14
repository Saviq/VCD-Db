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
 * @version $Id: VCDPageUserAddItem.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php

class VCDPageUserAddItem extends VCDBasePage {
	
	/**
	 * The sourceSiteObj that is being used
	 *
	 * @var sourceSiteObj
	 */
	private $sourceSiteObj = null;
	
	/**
	 * The Fetchclass that is being used
	 *
	 * @var VCDFetch
	 */
	private $fetchClass = null;
	
	public function __construct(_VCDPageNode $node) {
		
		parent::__construct($node);
		
		
		if (sizeof($_POST) == 0) {
			$this->handleRequest();
		}
		

	}
	
	
	/**
	 * Handle requests to the controller
	 *
	 */
	public function handleRequest() {
		
		
		// Adding new item in process
		$action = $this->getParam('action');
		if (!is_null($action)) {
			switch ($action) {
				case 'addadultmovie':
					$this->doAddAdultMovie();
					break;
			
				default:
					break;
			}
		}
		
		
		
		
		// Fetch in process
		$source = $this->getParam('source');
		if (!is_null($source)) {
		
			switch ($source) {
				
				/**
				 * Handle request from the fetch classes
				 */
				case 'webfetch':
					$searchTitle = $this->getParam('searchTitle',true);
					$searchSite  = $this->getParam('fetchsite',true);
										
					if ((!is_null($searchTitle)) && (!is_null($searchSite))) {
						// Show the search results
						$this->doFetchSiteResults($searchSite, $searchTitle);
					} else {
						// Show the selected fetched item
						$site   = $this->getParam('site');
						$itemId = $this->getParam('fid');
						$this->doFetchItem($site, $itemId);
					}
					break;
					
					
				case 'xml':
					
					if (!is_null($this->getParam('xmlCancel',true))) {
						$this->doXmlCancel();
					}
					
					$this->doXmlImport();
					break;

				default:
					break;
			}
		}
		
			
	}
	
	
	/**
	 * Display selected fetched object
	 *
	 */
	private function doFetchItem($sourceSite, $sourceId) {
		
		$this->doInitFetch($sourceSite);
		
		if (!is_null($this->fetchClass)) {
			
			// Initilize the fetched object
			$this->fetchClass->fetchItemByID($sourceId);
		 	$this->fetchClass->fetchValues();
		 	$fetchedObj = $this->fetchClass->getFetchedObject();
		 	$fetchedObj->setSourceSite($this->sourceSiteObj->getsiteID());
		 	
		 	
		 	// Handle the thumbnail
		 	// Generic Fetched Object actions ..
			if (strcmp($fetchedObj->getImage(), "") != 0) {
				$filename = VCDUtils::grabImage($fetchedObj->getImage());
				// Check if we need to resize the thumbnail ..
				list($width, $height) = getimagesize(TEMP_FOLDER.$filename);
				if ((int)$width > 135) {
					// Image to big .. resize it
					$im = new Image_Toolbox(TEMP_FOLDER.$filename);
					if ($fetchedObj instanceof adultObj ) {
						$im->newOutputSize(135,0);
					} else {
						$im->newOutputSize(0,140);
					}
					$newFilename ="x".$filename;
					$im->save(TEMP_FOLDER.$newFilename, 'jpg');
					unset($im);
					fs_unlink($filename);
					$filename = $newFilename;
				}
				$fetchedObj->setImage($filename);
			}
				
		 	
		 	
		 	// Notify the UI that we have an object
		 	$this->assign('isFetched',true);
		 	
		 	
		 	// Tell the UI what kind of fetch item we have
		 	$this->assign('itemAdult', $this->fetchClass->isAdultSite());
		 	if ($this->fetchClass->isAdultSite()) {
		 		$this->doPopulateAdultMovie($fetchedObj);
		 	} else {
		 		$this->doPopulateMovie($fetchedObj);
		 	}
		 	
		 	// Store the fetchedObject in session for later usage
			$_SESSION['_fetchedObj'] = $fetchedObj;
		 	
		}
	}
	
	
	/**
	 * Populate the confirmation page by the fetched values.
	 *
	 * @param adultObj $obj
	 */
	private function doPopulateAdultMovie(adultObj $obj) {
		
		$this->assign('itemTitle', $obj->getTitle());
		$this->assign('itemYear', $obj->getYear());
		$this->assign('itemId', $obj->getObjectID());
		
		// Set the thumbnail
		if (is_null($obj->getImage())) {
			$img = '<img src="images/noimage.gif" border="0" class="imgx"/>';
			$this->assign('itemThumbnail', $img);
		} else {
			$src = TEMP_FOLDER.$obj->getImage();
			$img = '<img src="%s" border="0" class="imgx"/>';
			$this->assign('itemThumbnail',sprintf($img, $src));
			$this->assign('itemThumb',$obj->getImage());	
		}
		
		
		// Set the studios
		$results = array();
		$results[null] = VCDLanguage::translate('misc.select');
		$studios = PornstarServices::getAllStudios();
		foreach ($studios as $studioObj) {
			$results[$studioObj->getId()] = $studioObj->getName();
		}
		$this->assign('studioList',$results);
		$currStudio = PornstarServices::getStudioByName($obj->getStudio());
		if ($currStudio instanceof studioObj) {
			$this->assign('selectedStudio',$currStudio->getID());	
		}
		
		
		// Set the movie category
		$results = array();
		$categories = SettingsServices::getAllMovieCategories();
		foreach ($categories as $categoryObj) {
			$results[$categoryObj->getId()] = $categoryObj->getName(true);
		}
		asort($results);		
		$this->assign('itemCategoryList',$results);
		$this->assign('selectedCategory',SettingsServices::getCategoryIDByName('adult'));
		
		
		
		// Set the mediaType list
		$results = array();
		$results[null] = VCDLanguage::translate('misc.select');
		
		foreach (SettingsServices::getAllMediatypes() as $mediaTypeObj) {
			$results[$mediaTypeObj->getmediaTypeID()] = $mediaTypeObj->getDetailedName();
			if ($mediaTypeObj->getChildrenCount() > 0) {
				foreach ($mediaTypeObj->getChildren() as $childObj) { 
					$results[$childObj->getmediaTypeID()] = '&nbsp;&nbsp;'.$childObj->getDetailedName();
				}
			}
		}
		
		$this->assign('mediatypeList', $results);
		
		
		
		// Set the number of cd's list
		$results = array();
		$results[null] = VCDLanguage::translate('misc.select');
		for($i=1;$i<11;$i++) {
			$results[$i] = $i;
		}
		$this->assign('cdList',$results);
		
		
		// Set the available adult categories and adult categories from the fetch object
		$results = array();
		$fetchedCategories = PornstarServices::getValidCategories($obj->getCategories());
		if (is_array($fetchedCategories)) {
			foreach ($fetchedCategories as $adultCategoryObj) {
				$results[$adultCategoryObj->getId()] = $adultCategoryObj->getName();
			}
		}
		
		$results2 = array();
		$subCategories = PornstarServices::getSubCategories();
		foreach ($subCategories as $adultCategoryObj) {
			if (!in_array($adultCategoryObj->getName(),$results)) {
				$results2[$adultCategoryObj->getId()] = $adultCategoryObj->getName();
			}
		}
		
		$this->assign('subcatsSelectedList',$results);
		$this->assign('subcatsAvailableList',$results2);
		
		
		
		// Set the pornstars
		$results = array();
		if (is_array($obj->getActors())) {
			foreach ($obj->getActors() as $id => $name) {
				$pornstarObj = PornstarServices::getPornstarByName($name);
					if ($pornstarObj instanceof pornstarObj && $pornstarObj->getName() != '') {
					$results[] = array('id' => $pornstarObj->getID(), 'name' => $pornstarObj->getName(), 'exists' => true);
				} else {
					$results[] = array('name' => $name, 'exists' => false);
				}
			}
			$this->assign('itemActors',$results);
		}
	
		
		// Set the screenshot count
		$this->assign('itemScreenshotCount', $obj->getScreenShotCount());

	}
	
	
	private function doPopulateMovie(fetchedObj $obj) {
		
	}
	
	/**
	 * Show the searchresults of the fetched object
	 *
	 * @param string $sourceSite | The alias of the sourcesite
	 * @param string $searchTitle | The search title used
	 */
	private function doFetchSiteResults($sourceSite, $searchTitle) {
				
		
		
		$this->doInitFetch($sourceSite);
		// Fetch class data seems all ok .. lets continue
		
		
		// Save the current fetch class in use for next time user fetches movie
		$metaDefaultClass = SettingsServices::getMetadata(0, VCDUtils::getUserID(), metadataTypeObj::SYS_LASTFETCH);
		if (!($metaDefaultClass instanceof metadataObj && strcmp($metaDefaultClass->getMetadataValue(), $sourceSite)==0)) {
			// Default class changed or not found .. add "last used class" to database
			$metaLastUsedClass = new metadataObj(array('',0,VCDUtils::getUserID(),metadataTypeObj::SYS_LASTFETCH,$sourceSite));
			SettingsServices::addMetadata(array($metaLastUsedClass));
		}
		
		

		// Make the fetchClass search it's site ..
		$fetchResults =	$this->fetchClass->Search($searchTitle);
		if ($fetchResults == VCDFetch::SEARCH_EXACT) {
			
			$this->doFetchItem($sourceSite,null);
			
		 	
		} else {
	 		$results = $this->fetchClass->showSearchResults();
	 		$this->assign('fetchList', $results);
	 		$this->assign('sourceSiteName', $this->sourceSiteObj->getName());
	 		
		}
	}

	
	
	
	/**
	 * Initialize the fetch
	 *
	 * @param string $sourceSite | The alias of the sourceSite being used
	 */
	private function doInitFetch($sourceSite) {
	
		// Load the correct fetch class
		$sourceObj = SettingsServices::getSourceSiteByAlias($sourceSite);
		if (!($sourceObj instanceof sourceSiteObj)) {
			throw new VCDProgramException('Invalid source site: ' . $sourceSite);
		}
		$this->sourceSiteObj = $sourceObj;
		
		$className = $sourceObj->getClassName();
		$fetchClass = VCDClassFactory::loadClass($className);
		if (!($fetchClass instanceof VCDFetch)) {
			throw new VCDProgramException("Class {$className} could not be loaded.");
		}
		$this->fetchClass = $fetchClass;
		
	}
	
	
	
	/**
	 * Show contents of the imported XML file.
	 *
	 */
	private function doXmlImport() {
		try {
			
			$xmlImportedFileName = "";
    		$xmlImportedThumbsFileName = "";

    		
			// Check for the XML movie file name.
			if (!is_null($this->getParam('xml_filename',true))) {
				$xmlImportedFileName = $this->getParam('xml_filename',true);
			} else {
				$xmlImportedFileName = VCDXMLImporter::validateXMLMovieImport();
			}
			   
			if (strcmp($xmlImportedFileName, "") == 0) {
				redirect('?page=new');
				exit();
    		}
    		
    		$xmlMovieCount = VCDXMLImporter::getXmlMovieCount($xmlImportedFileName);
    		$hasThumbs = false;
    	
    		/** Check for uploaded thumbnails  **/	
    		if (!is_null($this->getParam('thumbsupdate',true))) {
    			try {
    				
    				$xmlImportedThumbsFileName = VCDXMLImporter::validateXMLThumbsImport();
    				$hasThumbs = true;
    				
    			} catch (Exception $ex) {
    				VCDException::display($ex->getMessage());
    			}
    		}
        
	    
    		// Get the titles from the imported XML document
    		$xmltitles = VCDXMLImporter::getXmlTitles($xmlImportedFileName);
    		if (!is_array($xmltitles) || sizeof($xmltitles) == 0) {
    			$this->assign('importError',true);
    			return;
    		}
    		
    		
    		// Assign import status to the UI
    		$this->assign('importTranslateCount', sprintf(VCDLanguage::translate('xml.contains'),sizeof($xmltitles)));
    		$this->assign('importXmlFilename', $xmlImportedFileName);    		
    		$this->assign('importXmlThumbnailFilename',$xmlImportedThumbsFileName);
   			$this->assign('importThumbnails',$hasThumbs);
   			$this->assign('importTitles', $xmltitles);
   			$this->assign('importMovieCount',sizeof($xmltitles));
    		
			
			
			
		} catch (Exception $ex) {
			VCDException::display($ex,true);
		}
	}
	
	
	
	/**
	 * Cancel XML Import and delete Xml files in upload folder
	 *
	 */
	private function doXmlCancel() {
		
		$xmlFile = $this->getParam('xml_filename',true);
		$xmlThumbFile = $this->getParam('xml_thumbfilename',true);
		if (!is_null($xmlFile)) {
			unlink(VCDDB_BASE.DIRECTORY_SEPARATOR.TEMP_FOLDER.$xmlFile);
		}
		if (!is_null($xmlThumbFile)) {
			unlink(VCDDB_BASE.DIRECTORY_SEPARATOR.TEMP_FOLDER.$xmlThumbFile);
		}
		redirect('?page=new');
		exit();
		
	}
	
	
	
	/**
	 * Add adult movie to the database
	 *
	 */
	private function doAddAdultMovie() {
		try {
		
			// Get the fetchedObj from session and unset it from session
			$fetchedObj = $_SESSION['_fetchedObj'];
			unset($_SESSION['_fetchedObj']);
	
	
			// Create the basic CD obj
			$basic = array("", $_POST['title'], $_POST['category'], $_POST['year']);
			$vcd = new vcdObj($basic);
	
			// Add 1 instance
			$vcd->addInstance(VCDUtils::getCurrentUser(), SettingsServices::getMediaTypeByID($_POST['mediatype']), $_POST['cds'], mktime());
	
			// Set the categoryObj
			$vcd->setMovieCategory(SettingsServices::getMovieCategoryByID($_POST['category']));
	
	
			// Add the thumbnail as a cover if any was found on IMDB
			if (isset($_POST['thumbnail'])) {
				$cover = new cdcoverObj();
	
				// Get a Thumbnail CoverTypeObj
				$coverTypeObj = CoverServices::getCoverTypeByName("thumbnail");
				$cover->setCoverTypeID($coverTypeObj->getCoverTypeID());
				$cover->setCoverTypeName("thumbnail");
	
	
				$cover->setFilename($_POST['thumbnail']);
				$vcd->addCovers(array($cover));
			}
	
	
			// Set the source site
			$sourceSiteObj = SettingsServices::getSourceSiteByID($fetchedObj->getSourceSiteID());
			if ($sourceSiteObj instanceof sourceSiteObj ) {
				$vcd->setSourceSite($sourceSiteObj->getsiteID(), $_POST['id']);
			}
	
			// Set the adult studio if any
			if (isset($_POST['studio']) && is_numeric($_POST['studio'])) {
				$vcd->setStudioID($_POST['studio']);
			}
	
			// Associate the existing pornstars to the CD
	
			// Set the adult categories
			if (isset($_POST['id_list'])) {
	     		$adult_categories = split('#',$_POST['id_list']);
	
	     		if (sizeof($adult_categories) > 0) {
					foreach ($adult_categories as $adult_catid) {
						$catObj = PornstarServices::getSubCategoryByID($adult_catid);
						if ($catObj instanceof porncategoryObj ) {
							$vcd->addAdultCategory($catObj);
						}
	
					}
				}
	     	}
	
	
	
			if (isset($_POST['pornstars'])) {
				$pornstars = array_unique($_POST['pornstars']);
				foreach ($pornstars as $pornstar_id) {
					$vcd->addPornstars(PornstarServices::getPornstarByID($pornstar_id));
				}
			}
	
	
	
			// and the new ones after we create them
			if (isset($_POST['pornstars_new'])) {
				$pornstars_new = array_unique($_POST['pornstars_new']);
				foreach ($pornstars_new as $new_names) {
					$vcd->addPornstars(PornstarServices::addPornstar(new pornstarObj(array("",$new_names, "","",""))));
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
						$coverTypeObj = CoverServices::getCoverTypeByName($image_type);
						$cover->setCoverTypeID($coverTypeObj->getCoverTypeID());
						$cover->setCoverTypeName($image_type);
	
						$cover->setFilename($image_name);
	
						$vcd->addCovers(array($cover));
	
					}
				}
	
			}
	
	
	
			// Forward the movie to the Business layer
			try {
				$new_id = MovieServices::addVcd($vcd);
			} catch (Exception $ex) {
				VCDException::display($ex, true);
			}
	
			// Was I supposed to grab some screenshots ?
			if (sizeof($screenFiles) > 0) {
	
				// Does the destination folder exist?
				if (!fs_is_dir(ALBUMS.$new_id)) {
					if (fs_mkdir(ALBUMS.$new_id, 0755)) {
	
						foreach ($screenFiles as $screenshotImage) {
							VCDUtils::grabImage($screenshotImage, false, ALBUMS.$new_id."/");
						}
	
						// Mark thumbnails to movie in DB
						MovieServices::markVcdWithScreenshots($new_id);
	
					} else {
						throw new VCDProgramException("Could not create directory ".ALBUMS.$new_id."<break>Check permissions");
					}
				}
	
	
			}
	
			// Insert the user comments if any ..
			if (isset($_POST['comment']) && (strlen($_POST['comment']) > 1)) {
				$is_private = 0;
				if (isset($_POST['private'])) {
					$is_private = 1;
				}
	
				$commObj = new commentObj(array('', $new_id, VCDUtils::getUserID(), '', VCDUtils::stripHTML($_POST['comment']), $is_private));
				SettingsServices::addComment($commObj);
			}
	
	
			if (is_numeric($new_id) && $new_id != -1) {
				redirect("?page=cd&vcd_id=".$new_id);
				exit();
			}
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
		
	}
	
	
}

?>