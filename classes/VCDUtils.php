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
 */
?>
<?PHP


class VCDUtils {

	/**
	* @return Array
	* @param $location String
	* @param $fileregex String
	* @desc Search folder for files with certain extensions defined in the $fileregex parameter.
 */
	static function findfile($location='',$fileregex='') {
   		if (!$location or !is_dir($location) or !$fileregex) {
       		return false;
   		}

		$matchedfiles = array();

	   	$all = opendir($location);
	   	while ($file = readdir($all)) {
	       	if (is_dir($location.'/'.$file) and $file <> ".." and $file <> ".") {
	         	$subdir_matches = findfile($location.'/'.$file,$fileregex);
	         	$matchedfiles = array_merge($matchedfiles,$subdir_matches);
	         	unset($file);
	       	}
	       	elseif (!is_dir($location.'/'.$file)) {
	         	if (preg_match($fileregex,$file)) {
	             	array_push($matchedfiles,$location.'/'.$file);
	         	}
		       }
	   		}
	   	   closedir($all);
		   unset($all);
	       return $matchedfiles;
 	}






 	/**
	* @return Boolean
	* @param $recipent Mixed
	* @param $subject String
	* @param $body String
	* @param $use_html Boolean
	* @desc Send email, $recipent can either be an email address or an array of email addresses.
 */
	static function sendMail($recipent, $subject, $body, $use_html=false) {
		if (is_array($recipent)) {

			$b_ok = true;
			foreach ($recipent as $email) {
				$b_ok = sendMail($email, $subject, $body, $use_html);
				if (!$b_ok)	{
					break;
					return false;
				}
			}

			return true;

		} else {
			return sendMail($recipent, $subject, $body, $use_html);
		}
	}



	/**
	 * Get the current time in microseconds
	 *
	 * @param bool $float | Use float precision or not
	 * @return float
	 */
	static function getmicrotime($float = false) {
		   list($usec, $sec) = explode(" ", microtime());
		   if ($float)
		   		return ((float)$usec + (float)$sec);
		   else
		   		return ((int)(float)$usec + (float)$sec);

	}

	/**
	 * Get the total time of the page load in microseconds
	 *
	 * @return string
	 */
	static function getPageLoadTime() {
		global $start_time;
		$end = VCDUtils::getmicrotime(true);
		$run = $end - $start_time;
		return substr($run, 0, 5);
	}

	/**
	 * Get the PHP Version
	 *
	 * @return string
	 */
	static function getOS() {
		return PHP_OS;
	}


	/**
	 * Check if the current user browsing VCD-db is logged in or not.
	 *
	 * @return bool
	 */
	static function isLoggedIn() {
		if (isset($_SESSION['user']) && $_SESSION['user'] instanceof userObj) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Check weither adult content should be displayed or not.
	 * To return true all of the following requirements must be met ..
	 * 1) User must be logged in
	 * 2) SITE_ADULT must be enabled in the control panel
	 * 3) User must belong to the Administrator role or the Adult User role
	 * 4) User must have enabled adult content in the "My settings page"
	 *
	 * @return bool
	 */
	static function showAdultContent($skipUserPrefs=false) {
		if (!VCDUtils::isLoggedIn()) {
			return false;
		}

		$user = $_SESSION['user'];	
		$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");
		$siteEnabled = $SETTINGSClass->getSettingsByKey('SITE_ADULT');
		$userEnabled = $user->getPropertyByKey('SHOW_ADULT');
		$roleEnabled = $user->isAdult();
		if ($skipUserPrefs) {
			return ($siteEnabled && $roleEnabled);		
		} else {
			return ($siteEnabled && $userEnabled && $roleEnabled);			
		}
		
	}
	
	
	/**
	 * Get the current users ID, if user is not logged in null is returned.
	 *
	 * @return int
	 */
	static function getUserID() {
		if (VCDUtils::isLoggedIn()) {
			return $_SESSION['user']->getUserID();
		} else {
			return null;
		}
	}


	/**
	 * Check if the user is using a filter to filter out movies by specific user.
	 *
	 * @param int $user_id
	 * @return bool
	 */
	static function isUsingFilter($user_id) {

		$SETTINGSClass = VCDClassFactory::getInstance('vcd_settings');
		$metaArr = $SETTINGSClass->getMetadata(0, $user_id, 'ignorelist');
		if (is_array($metaArr) && sizeof($metaArr) > 0) {

			if ($metaArr[0] instanceof metadataObj && strcmp(trim($metaArr[0]->getMetaDataValue()), "") != 0) {
				return true;
			}

			return false;

		} else {
			return false;
		}
	}

	/**
	 * Shorten text string to specific length.
	 * Cuts of the end of string and appends "..."
	 *
	 * @param string $text
	 * @param int $length
	 * @return string
	 */
	static function shortenText($text, $length) {
		if (strlen($text) > $length) {
				$text_spl = explode(' ', $text);
				$i = 1;
				$text = $text_spl[0];
				while(strlen($text.$text_spl[$i]) < $length) {
					$text .= " ".$text_spl[$i++];
				}
				$text = $text."...";
			}
		return $text;
	}


	/**
	 * Get the difference between 2 dates
	 *
	 * @param date $date1
	 * @param date $date2
	 * @return string
	 */
	static function getDaydiff($date1, $date2 = null) {
		if (is_null($date2)) {
			$date2 = time();
			$datediff = $date2 - $date1;

		} else {
			$datediff = $date2 - $date1;
		}


		if (floor($datediff/60/60/24) > 0) {
			if (floor($datediff/60/60/24) == 1) {
				return floor($datediff/60/60/24) . " ". VCDLanguage::translate('loan.day');
			} else {
				return floor($datediff/60/60/24) . " ". VCDLanguage::translate('loan.days');
			}

		} elseif (floor($datediff/60/60) > 0) {
			return VCDLanguage::translate('loan.day');

		} else {
			return "1 " . VCDLanguage::translate('loan.days');

		}


	}


	/**
	 * Get the character set for the current selected language.
	 * The character set is then used in the HTML charset directive.
	 *
	 * @return string
	 */
	static public function getCharSet() {
		return "UTF-8";
		$charset = VCDLanguage::translate('language.charset');
		if (strcmp($charset, "undefined") == 0) {
			return "iso-8859-1";
		} else {
			return $charset;
		}
	}


	/**
	 * Set a message to the Session
	 *
	 * @param string $strMessage
	 */
	static function setMessage($strMessage) {
		$_SESSION['message'] = $strMessage;
	}

	/**
	 * Get the current message from Session and delete it.
	 *
	 * @return string
	 */
	static function getMessage() {
		$message = "";
		if (isset($_SESSION['message'])) {
			$message = $_SESSION['message'];
			unset($_SESSION['message']);
		}
		return $message;
	}


	/**
	 * Download image resource from a specified url.
	 * Returns the new image file name.
	 *
	 * @param string $image_url | The http url to grab the image from
	 * @param bool $uniqueID | Generate uniqueID or not
	 * @param string $destination | The folder to save the image to
	 * @return string
	 */
	static function grabImage($image_url, $uniqueID = true, $destination = TEMP_FOLDER) {

	  // Cut some slack for slow connections, 15 secs per file.
	  @set_time_limit(30);

      $source = urldecode($image_url);

      if (defined('USE_PROXY') && USE_PROXY == 1) {

      	$contents = VCDUtils::proxy_url($source);

      } else {

      	$fd = fopen($source, "rb");
      	if (!$fd )	{
      		VCDException::display("Cant open file at: ".$image_url."");
      		return false;
	  	}
        $contents = '';
	  	while (!feof($fd)) {
		  	$contents .= fgets ($fd, 1024);
	  	}
		fclose($fd);


      }



      // get the extension of this image
      ereg( ".*\.(.*)$", $source, $regs );
      $ext = $regs[1];

      if ($uniqueID) {
      	$filename = VCDUtils::generateUniqueId() . "." .$ext;
      } else {
      	ereg( ".*\/(.*)$", $source, $regs );
      	$filename = $regs[1];
      }

      $dFolder = $destination;

      $dest = $destination . $filename;
      $fd = fopen($dest, "wb");

      if ( !$fd ) {
	  	VCDException::display("Cant write file, check permissions for folder " . $destination);
	  	return false;
	  }

	  fwrite($fd, $contents);
      fclose($fd);
	  return $filename;
	}


 /**
  * Generate Unique ID
  *
  * @return string
  */
 static function generateUniqueId(){
    return md5(uniqid(mt_rand(),TRUE));
  }


  /**
   * Split array to string by given seperator token
   *
   * @param array $arrItems | The array to split
   * @param char $sepator | The seperator token
   * @return string
   */
  static function split($arrItems, $sepator) {
  	if (is_array($arrItems)) {
  		$string = implode($sepator, $arrItems);
  		return $string;
  	} else {
  		return $arrItems;
  	}
  }


   /**
    * If title contains The at the end .. move it forward.
    *
    * @param string $strTitle
    * @return string
    */
   static function titleFormat($strTitle) {
  		$strTitle = trim($strTitle);

  		$rest = substr($strTitle, -5);
  		if (!$rest) {
  			return $strTitle;
  		}

  		// Title ends with ', The'
  		if (strcmp($rest, ", The") == 0) {
  			$strTitle = "The " . substr($strTitle, 0 , (strlen($strTitle)-5));
  			return $strTitle;
  		} else {
  			return $strTitle;
  		}
  }



	 /**
	  * Get a file extension from specified filename
	  *
	  * @param string $strFilename
	  * @return string
	  */
	 static function getFileExtension($strFilename) {
	  	  ereg( ".*\.(.*)$", $strFilename, $regs );
	      return $regs[1];
	  }



	/**
	 * Write contents of a stream to disk.
	 * Returns true if operation succeded otherwise false.
	 *
	 * @param string $filename | The filename to create
	 * @param string $content | The stream to write to disk
	 * @param  bool $append | Append to the file or not
	 * @return bool
	 */
	static function write($filename, $content, $append=false){
			if(!empty($filename) && !empty($content)){
				if ($append) {
					$fp = fopen($filename, "a");
				} else {
					$fp = fopen($filename,"w");
				}
				$b = fwrite($fp,$content);
				fclose($fp);
				if($b != -1){
					return TRUE;
				} else {
					VCDException::display("Can't write File [no fwrite]");
					return FALSE;
				}
			} else {
				VCDException::display("Cant write File [no filename | no content]");
				return FALSE;
			}
		}

	/**
	 * Check if the current logged in user is the owner of this vcdObject.
	 * Returns true if user has got a copy of this vcdObj
	 *
	 * @param vcdObj $obj
	 * @return bool
	 */
	static function isOwner(vcdObj $obj) {
		if (isset($_SESSION['user']) && $_SESSION['user'] instanceof userObj ) {
			$user = $_SESSION['user'];
			if ($obj->getInstancesByUserID($user->getUserID()) != null &&
				is_array($obj->getInstancesByUserID($user->getUserID()))) {
				return true;
			}
			return false;

		} else {
			return false;
		}
	}


	/**
	 * Check if user has access to the "change movie" console
	 *
	 * @param vcdObj $obj
	 * @return bool
	 */
	static function hasPermissionToChange(vcdObj $obj) {
		if (isset($_SESSION['user']) && $_SESSION['user'] instanceof userObj ) {
			$user = $_SESSION['user'];
			if ($user->isAdmin()) {
				return true;
			}

			if ($obj->getInstancesByUserID($user->getUserID()) != null &&
				is_array($obj->getInstancesByUserID($user->getUserID()))) {
				return true;
			}

			return false;

		} else {
			return false;
		}
	}


	/**
	 * Get file contents through proxy server.
	 * Returns the contents of the downloaded file.
	 *
	 * @param string $proxy_url | The url to the file to download
	 * @return string
	 */
	static function proxy_url($proxy_url) {
	   if (!defined('PROXY_URL') || !defined('PROXY_PORT') || PROXY_URL == '' || PROXY_PORT == '' ) {
	   		VCDException::display('You must define Proxy server and port in VCDConstants.php', true);
	   		return false;
	   }

	   $proxy_name = PROXY_URL;
	   $proxy_port = PROXY_PORT;
	   $proxy_cont = '';

	   $proxy_fp = fsockopen($proxy_name, $proxy_port);
	   if (!$proxy_fp)  {
	   		VCDException::display('No response from proxy server', true);
	   		return false;
	   }

	   $urlArr = parse_url($proxy_url);
	   $domain = $urlArr['host'];

	   //fputs($proxy_fp, "GET $proxy_url HTTP/1.0\r\nHost: $proxy_name\r\n\r\n");
	   fputs($proxy_fp, "GET $proxy_url HTTP/1.0\r\nHost: $domain\r\n\r\n");

	   while(!feof($proxy_fp)) {$proxy_cont .= fread($proxy_fp,4096);}
	   fclose($proxy_fp);
	   $proxy_cont = substr($proxy_cont, strpos($proxy_cont,"\r\n\r\n")+4);
	   return $proxy_cont;
	}


	/**
	 * Get list of available CSS templates for VCD-db.
	 * Returns array of strings, containing the unique template names.
	 *
	 * @return array
	 */
	static function getStyleTemplates() {

		$templateDirectory = 'includes/templates';
		$it = new DirectoryIterator( $templateDirectory );

		$styles = array();

		while($it->valid()) {
			$directory = $it->current();
			if ($directory->isDir() && !$directory->isDot() && (strcmp($directory->getFilename(), "CVS") != 0)) {
				array_push($styles, $directory->getFilename());
			}
			$it->next();
		}

		return $styles;
	}


	/**
	 * Get the path to the selected stylesheet
	 *
	 * @return string
	 */
	static function getStyle() {
		$defaultStyle = STYLE;
		$stylepath = "includes/templates/";

		// Check if style is set in Cookie
		SiteCookie::extract('vcd_cookie');
		if (isset($_COOKIE['template'])) {
			return $stylepath.$_COOKIE['template']."/style.css";
		} else {
			return $defaultStyle."style.css";
		}


	}

	/**
	 * Write out the url to the XML feed of VCD-db if XML feeds from VCD-db are allowed.
	 *
	 * @return string
	 */
	static function getAlternateLink() {
		$SETTINGSClass = VCDClassFactory::getInstance('vcd_settings');
		$showRSS = $SETTINGSClass->getSettingsByKey('RSS_SITE');
		if ($showRSS) {
			return "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"VCD-db RSS\" href=rss/>";
		}

	}


	/**
	 * Check if any of the mediaTypes in the incoming array matches a DVD based mediaType.
	 *
	 * @param array $arrMediaTypes | Array of mediaTypeObjects
	 * @return bool
	 */
	static function isDVDType($arrMediaTypes) {
		if (is_array($arrMediaTypes) && sizeof($arrMediaTypes) > 0) {

			$CLASSsettings = VCDClassFactory::getInstance('vcd_settings');
			// Get the standars DVD and DVD-R mediaTypeObj
			$objDVD =  $CLASSsettings->getMediaTypeByName('DVD');
			$objDVDR = $CLASSsettings->getMediaTypeByName('DVD-R');

			$isDVDType = false;
			if ($objDVD instanceof mediaTypeObj && $objDVDR instanceof mediaTypeObj ) {
				foreach ($arrMediaTypes as $mediaTypeObj) {
					$curr_id = $mediaTypeObj->getmediaTypeID();
					$dvd_id = $objDVD->getmediaTypeID();
					$dvdr_id = $objDVDR->getmediaTypeID();
					if ($curr_id == $dvd_id || $curr_id == $dvdr_id) {
						$isDVDType = true;
						break;
					}

					if (is_numeric($mediaTypeObj->getParentID()) && ($mediaTypeObj->getParentID() > 0)) {
						if ($mediaTypeObj->getParentID() == $dvd_id || $mediaTypeObj->getParentID() == $dvdr_id) {
							$isDVDType = true;
							break;
					}
				}
			}
		}

			return $isDVDType;

		} else {
			return false;
		}
	}



	/**
	 * Get a value from specfic metaData type in the DVD Section
	 *
	 * @param arrray $arrMetaObj
	 * @param string $dvdTypeToFind
	 * @return string
	 */
	static function getDVDMetaObjValue($arrMetaObj, $dvdTypeToFind) {
		$metaValue = "";
		if (is_array($arrMetaObj)) {
			foreach ($arrMetaObj as $metaDataObj) {
				if ($metaDataObj->getMetadataTypeID() == $dvdTypeToFind) {
					$metaValue = $metaDataObj->getMetadataValue();
					break;
				}
			}
		}


		return $metaValue;
	}


	/**
	 * Filter comments in the commentsObj Array for by specified userID
	 *
	 * @param array $arrCommentsObj | Array of commentObjects
	 * @param int $user_id | The userID to filter by
	 * @return array | The filtered array
	 */
	static function filterCommentsByUserID($arrCommentsObj, $user_id) {
		if (!is_numeric($user_id)) {
			return $arrCommentsObj;
		}

		$arrFilteredComments = array();
		foreach ($arrCommentsObj as &$commentObj) {
			if ((int)$commentObj->getOwnerID() === (int)$user_id) {
				array_push($arrFilteredComments, $commentObj);
			}
		}

		return $arrFilteredComments;

	}
	
	
	/**
	 * Clean up Magic_Quotes_GPC() stupidity
	 *
	 */
	static function cleanMagicQuotes() {
		if (!(bool)get_magic_quotes_gpc()) {return;}
		
		foreach($_GET as $k=>$v) {
			$_GET[$k]=stripslashes($v);
		}
		
		foreach($_POST as $k=>$v) {
			if ( (!is_array($v)) )
				$_POST[$k]=stripslashes($v);
		}
	}
	
	
	/**
	 * Strip html entities from text
	 *
	 * @param string $document | The polluted text
	 * @return string | The sanitized text
	 */
	static function stripHTML($document){
		$search = array('@<script[^>]*?>.*?</script>@si',   // Strip out javascript
        	'@<[\/\!]*?[^<>]*?>@si',            			// Strip out HTML tags
            '@<style[^>]*?>.*?</style>@siU',    			// Strip style tags properly
            '@<![\s\S]*?--[ \t\n\r]*>@'        				// Strip multi-line comments including CDATA
        );
		return preg_replace($search, '', $document);
	}
	


}


?>