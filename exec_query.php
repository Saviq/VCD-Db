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
include_once('classes/includes.php');


if (!VCDUtils::isLoggedIn()) {
	die("Unauthorized Access");
}

if (sizeof($_POST) > 0) {
	die("I only accept get!");
}

if (isset($_GET['action'])) {
	$form = $_GET['action'];
} else {
	die("Unspecified form handler!");
}

$reload_and_close = true;
$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");
global $language;

switch ($form) {

	case 'returnloan':
		$loan_id = $_GET['loan_id'];
		$SETTINGSClass->loanReturn($loan_id);
		VCDUtils::setMessage("(Removed CD from loan)");
		redirect('?page=private&o=loans');
		break;
	
	case 'reminder':
		$borrower_id = $_GET['bid'];
		$obj = $SETTINGSClass->getBorrowerByID($borrower_id);
		
		if ($obj instanceof borrowerObj ) {
			$loanArr = $SETTINGSClass->getLoansByBorrowerID(VCDUtils::getUserID(), $borrower_id);
			if (VCDUtils::sendMail($obj->getEmail(), $language->show('MAIL_RETURNTOPIC'), createReminderEmailBody($obj->getName(), $loanArr), false)) {
				VCDUtils::setMessage("(Mail successfully sent to ".$obj->getName().")");
			} else {
				VCDUtils::setMessage("(Failed to send mail)");
			}
		}
		redirect('?page=private&o=loans');
		break;
		
		
	case 'deleteNFO':
		if (isset($_GET['meta_id']) && is_numeric($_GET['meta_id'])) {
			$SETTINGSClass->deleteNFO($_GET['meta_id']);
			$vcd_id = $_GET['rid'];
			redirect("pages/manager.php?cd_id=".$vcd_id."&do=reload");
		}
	
		break;
		
	case 'delete_borrower':
		$borrowerObj = $SETTINGSClass->getBorrowerByID($_GET['bid']);
		$SETTINGSClass->deleteBorrower($borrowerObj);
		VCDUtils::setMessage("(".$borrowerObj->getName()." has been deleted)");
		redirect('?page=private&o=settings');
		break;
		
	case 'export':
		$method = $_GET['type'];
		if (strcmp($method, "excel") == 0) {
			generateExcel();
		}	
	
		if (strcmp($method, "xml") == 0) {
			
			
			if (isset($_GET['filter']) && $_GET['filter'] == 'thumbs') {
			
				$COVERClass = VCDClassFactory::getInstance('vcd_cdcover');
				$arrCovers = $COVERClass->getAllThumbnailsForXMLExport(VCDUtils::getUserID());
				
				// This can take alot of time for many entries .. Give it 1000 secs
				set_time_limit(1000);
				
				$xml = "<?xml version=\"1.0\" encoding=\"iso-8859-1\" ?>";
				$xml .= "<vcdthumbnails>";
				foreach ($arrCovers as $cdcover) {
					$xml .= $cdcover->toXML();
				}
				$xml .= "</vcdthumbnails>";
				
												
				$xmlFilename = CACHE_FOLDER.'thumbnails_export.xml';
				$tarFilename = CACHE_FOLDER.'thumbnails_export.tgz';
				$zipFilename = CACHE_FOLDER.'thumbnails_export.zip';
				
				if (isset($_GET['c']) && strcmp($_GET['c'], "tar") == 0) { 
					require_once('classes/external/compression/tar.php');
	
					VCDUtils::write($xmlFilename, $xml);
					$zipfile = new tar();
					$zipfile->addFile($xmlFilename);
					fs_unlink($xmlFilename);
					
					// Write the TAR file to disk
					VCDUtils::write($tarFilename, $zipfile->toTarOutput("xmlthumbnails", true));
					// Stream the file to browser
					send_file($tarFilename);
					// Delete the tar file from cache
					fs_unlink($tarFilename);
					exit();
				
				} else if (isset($_GET['c']) && strcmp($_GET['c'], "zip") == 0) {
					require_once('classes/external/compression/zip.php');
					
					$zipfile = new zipfile();
					$zipfile->addFile($xml, "thumbnails_export.xml");
					
					// Write the zip file to cache folder
					VCDUtils::write($zipFilename, $zipfile->file());
					// Stream the file to browser
					send_file($zipFilename);
					// Delete the Zip file from cache
					fs_unlink($zipFilename);
					exit();
						
				
				} else {
					// Write the XML file to cache folder
					VCDUtils::write($xmlFilename, $xml);
					// Stream the file to browser
					send_file($xmlFilename);
					// Delete the XML file from cache
					fs_unlink($xmlFilename);
					exit();
					
				}
				
				exit();
				
				
			} else {
			
				
				
				$xml = "<?xml version=\"1.0\" encoding=\"iso-8859-1\" ?>";
				$xml .= "<vcdmovies>";
				$CLASSVcd = VCDClassFactory::getInstance("vcd_movie");
				$arrMovies = $CLASSVcd->getAllVcdByUserId(VCDUtils::getUserID(), false);
				foreach ($arrMovies as $vcdObj) {
					$xml .= $vcdObj->toXML();
				}
				$xml .= "</vcdmovies>";
				unset($arrMovies);
				
				
				
				$xmlFilename = CACHE_FOLDER.'movie_export.xml';
				$tarFilename = CACHE_FOLDER.'movie_export.tgz';
				$zipFilename = CACHE_FOLDER.'movie_export.zip';
				
				
				if (isset($_GET['c']) && strcmp($_GET['c'], "tar") == 0) { 
					require_once('classes/external/compression/tar.php');
	
					VCDUtils::write($xmlFilename, $xml);
					$zipfile = new tar();
					$zipfile->addFile($xmlFilename);
					fs_unlink($xmlFilename);
					
					// Write the TAR file to disk
					VCDUtils::write($tarFilename, $zipfile->toTarOutput("movie_export", true));
					// Stream the file to browser
					send_file($tarFilename);
					// Delete the tar file from cache
					fs_unlink($tarFilename);
					exit();
				
				} else if (isset($_GET['c']) && strcmp($_GET['c'], "zip") == 0) {
					require_once('classes/external/compression/zip.php');
					
					$zipfile = new zipfile();
					$zipfile->addFile($xml, "movie_export.xml");
					
					// Write the zip file to cache folder
					VCDUtils::write($zipFilename, $zipfile->file());
					// Stream the file to browser
					send_file($zipFilename);
					// Delete the Zip file from cache
					fs_unlink($zipFilename);
					exit();
					
				
				} else {
					// Write the XML file to cache folder
					VCDUtils::write($xmlFilename, $xml);
					// Stream the file to browser
					send_file($xmlFilename);
					// Delete the XML file from cache
					fs_unlink($xmlFilename);
					exit();
					
				}
				
				exit();	
			
			}
		}
		
		break;
		
		
	case 'onlymine':
		if (isset($_SESSION['mine'])) {
			if ($_SESSION['mine'] == true) {
				$_SESSION['mine'] = false;
			} else {
				$_SESSION['mine'] = true;
			}
		} else {
			$_SESSION['mine'] = true;
		}
		$url = "?page=category&category_id=" . $_GET['cat_id'];
		redirect($url);
		break;
		
		
	case 'fetchimage':
				
		
		$path = $_GET['path'];	
		$pornstar_id = $_GET['star_id'];
		
		$image_name = VCDUtils::grabImage($path);
		
		if (strlen($image_name) > 3) {
			try {
				$im = new Image_Toolbox(TEMP_FOLDER.$image_name);
				$im->newOutputSize(0,200);
				$im->save(TEMP_FOLDER.$image_name, 'jpg');
			} catch (Exception $e) {
				VCDException::display($e);
			}
			
		}
				
		
		if (fs_rename(TEMP_FOLDER.$image_name, PORNSTARIMAGE_PATH.$image_name)) {
			// Success ...
			$PORNClass = VCDClassFactory::getInstance("vcd_pornstar");
			$pornstar = $PORNClass->getPornstarByID($pornstar_id);
			$pornstar->setImageName($image_name);
			$PORNClass->updatePornstar($pornstar);
			redirect("pages/pmanager.php?pornstar_id=".$pornstar_id."");
		} else {
			// Error notification
			redirect("pages/pmanager.php?pornstar_id=".$pornstar_id."&error=true");
		}
	
		
		break;
		
		
	case 'deletecopy':	
	
		$media_id = $_GET['media_id'];
		$cd_id = $_GET['cd_id'];
		$mode = $_GET['mode'];
		$VCDClass = VCDClassFactory::getInstance('vcd_movie');
		$VCDClass->deleteVcdFromUser($cd_id, $media_id, $mode);
	
		break;
	
		
	case 'deletecover':
		$cid = $_GET['cover_id'];
		$vcd_id = $_GET['vcd_id'];
		$COVERClass = VCDClassFactory::getInstance('vcd_cdcover');
		if (is_numeric($cid)) {
			$COVERClass->deleteCover($cid);
		} 
		redirect("pages/manager.php?cd_id=".$vcd_id."&do=reload");
		
		break;
		
	case 'delComment':
		
		$comment_id = $_GET['cid'];
		$commObj = $SETTINGSClass->getCommentByID($comment_id);
		if ($commObj instanceof commentObj && $commObj->getOwnerID() == VCDUtils::getUserID()) {
			$vcd_id = $commObj->getVcdID();
			$SETTINGSClass->deleteComment($comment_id);	
			redirect("?page=cd&vcd_id=".$vcd_id."");
		} else {
			redirect();
		}
		
		break;
		
		
	case 'delimage':
		$pornstar_id = $_GET['star_id'];
		
		$PORNClass = VCDClassFactory::getInstance("vcd_pornstar");
		$pornstar = $PORNClass->getPornstarByID($pornstar_id);
		
		fs_unlink(PORNSTARIMAGE_PATH.$pornstar->getImageName());
		$pornstar->setImageName('');
		$PORNClass->updatePornstar($pornstar);
		redirect("pages/pmanager.php?pornstar_id=".$pornstar_id."");

		break;
		
	case 'delrss':
		$rssid = $_GET['rss_id'];
		$SETTINGSClass->delFeed($rssid);
		redirect('?page=private&o=settings');

		break;
		
	case 'delmetatype':
		$metadatatype_id = $_GET['meta_id'];
		if (is_numeric($metadatatype_id)) {
			$SETTINGSClass->deleteMetaDataType($metadatatype_id);
		}
		redirect('?page=private&o=settings');
		break;
		
	case 'delactor':
		$actor_id = $_GET['actor_id'];
		$movie_id = $_GET['movie_id'];
		$PORNClass = VCDClassFactory::getInstance("vcd_pornstar");
		$PORNClass->deletePornstarFromMovie($actor_id, $movie_id);
		redirect("pages/manager.php?cd_id=".$movie_id."");
		
		break;
		
		
		
	case 'templates':
		$template_name = $_GET['name'];
		
		// Set the new template in cookie
		// but we must keep existing data in the cookie
		SiteCookie::extract('vcd_cookie');
		$Cookie = new SiteCookie("vcd_cookie");
		$Cookie->clear();
		
		if (isset($_COOKIE['session_id']) && isset($_COOKIE['session_uid'])) { 
			$session_id    = $_COOKIE['session_id'];			
			$user_id 	   = $_COOKIE['session_uid'];
			$session_time  = $_COOKIE['session_time'];	
			
			$Cookie->put("session_id", $session_id);	
			$Cookie->put("session_time", $session_time);
			$Cookie->put("session_uid", $user_id);
		}
		
		if (isset($_COOKIE['language'])) {
			$langname = $_COOKIE['language'];
			$Cookie->put("language",$langname);
		}
				
		$Cookie->put("template",$template_name);	
		$Cookie->set();
		
		
	
		redirect('?page=private&o=settings');
		break;
		
		
	case 'seenlist':
		$movie_id = $_GET['vcd_id'];	
		$mark = $_GET['flag'];
		if (is_numeric($movie_id) && is_numeric($mark) && VCDUtils::isLoggedIn()) {
			
			// Check for existing data
			$arr = $SETTINGSClass->getMetadata($movie_id, VCDUtils::getUserID(), 'seenlist');
			if (is_array($arr) && sizeof($arr) == 1) {
				// update the Obj
				$obj = $arr[0];
				$obj->setMetadataValue($mark);
				$SETTINGSClass->updateMetadata($obj);
			} else {
				// create new Obj
				$obj = new metadataObj(array('',$movie_id, VCDUtils::getUserID(), metadataTypeObj::SYS_SEENLIST , $mark));
				$SETTINGSClass->addMetadata($obj);
			}
			
			redirect("?page=cd&vcd_id=".$movie_id."");
		} else {
			redirect();
		}
		
		break;
		
	case 'addtowishlist':
		$id = $_GET['vcd_id'];		
		$SETTINGSClass->addToWishList($id, VCDUtils::getUserID());
		redirect("./?page=cd&vcd_id=".$id);
		break;
		
	case 'deletefromwishlist':
		$id = $_GET['vcd_id'];		
		$SETTINGSClass->removeFromWishList($id, VCDUtils::getUserID());
		redirect("?page=private&o=wishlist");
		break;
		
	/* User has cancelled uploading though XML file upload
	   Try to delete the uploaded file.
	*/
	case 'cleanxml':
		$filename = "";
		if (isset($_GET['filename'])) {
			$filename = $_GET['filename'];
			$fullpath = TEMP_FOLDER.$filename;
			if (!fs_is_dir($fullpath) && fs_file_exists($fullpath)) {
				fs_unlink($fullpath);
				redirect('?page=private&o=new');
			} else {
				redirect();
			}
		} else {
			redirect();
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