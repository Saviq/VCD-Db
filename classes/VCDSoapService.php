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
 * @version $Id: VCDSoapService.php 1062 2007-07-05 15:10:11Z konni $
 * @since  0.990
  */
?>
<?php

class VCDSoapService extends VCDServices {

	const WSDLPornstar = 'vcddb-pornstar.wsdl';
	const WSDLSettings = 'vcddb-settings.wsdl';
	const WSDLUser 	   = 'vcddb-user.wsdl';
	const WSDLAuth	   = 'vcddb-authentication.wsdl';
	const WSDLCover	   = 'vcddb-cover.wsdl';
	const WSDLMovie	   = 'vcddb-movie.wsdl';
	
	
	/**
	 * The nusoap server instance
	 *
	 * @var nusoap_server
	 */
	private static $server = null;
	
	/**
	 * The current user that is connected
	 *
	 * @var userObj
	 */
	private static $userObj = null;
	
	
	/**
	 * Force the use of NuSoap even if phpsoap is available
	 *
	 * @var bool
	 */
	private static $forceNuSoap = false;
	
	/**
	 * Class constructor
	 *
	 * @param string $wsdl | The wsdl file to load
	 */
	public function __construct($wsdl) {
		try {
			
			if (!self::$forceNuSoap && extension_loaded('soap')) {
				$this->initPhpSoap($wsdl);				
			} else {
				$this->initNuSoap($wsdl);
			}
		
			parent::$isWebserviceCall = true;
			
		} catch (Exception $ex) {
			return self::handleSoapError($ex);
		}
	}
	
	/**
	 * Provide service to the webservice caller
	 *
	 * @param string $request | The SOAP Request
	 */
	public function provideService($request) {
		try {
			
			if (!isset($_GET['wsdl'])) {
				$this->checkCredentials();
			}
			
			if (!self::$forceNuSoap && extension_loaded('soap')) {
				self::$server->handle($request);
			} else {
				self::$server->service($request);
			}
		} catch (Exception $ex) {
			return self::handleSoapError($ex);
		}
	}
	
	/**
	 * Initiate the NuSoap Soap server
	 *
	 * @param string $wsdl | The service descriptor to load
	 */
	private function initNuSoap($wsdl) {
		try {
		
			if ($this->checkWSDLCache($wsdl)) {
				$_wsdl = new wsdl(VCDDB_BASE.DIRECTORY_SEPARATOR.CACHE_FOLDER.$wsdl);
			} else {
				$_wsdl = new wsdl(VCDDB_BASE.'/includes/wsdl/'.$wsdl);	
			}
			self::$server = new nusoap_server($_wsdl);
			self::$server->soap_defencoding = 'UTF-8';
			self::$server->setClass4Operation($this->getHandler($wsdl), true);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Initiate the PHP Soap server
	 *
	 * @param string $wsdl | The service descriptor to load
	 */
	private function initPhpSoap($wsdl) {
		try {
		
			$_wsdl = VCDDB_BASE.DIRECTORY_SEPARATOR.CACHE_FOLDER.$wsdl;
			if ($this->checkWSDLCache($wsdl)) {
				$_wsdl = VCDDB_BASE.DIRECTORY_SEPARATOR.CACHE_FOLDER.$wsdl;
			} else {
				$_wsdl = VCDDB_BASE.'/includes/wsdl/'.$wsdl;	
			}
				
			self::$server = new SoapServer($_wsdl, array('(encoding'=>'UTF-8'));
			self::$server->setClass($this->getHandler($wsdl));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Get the correct class handler for the current webservice
	 *
	 * @param string $wsdl | The wsdl file to map service to
	 * @return string | The classname for the corrisponding wsdl file
	 */
	private function getHandler($wsdl) {
		try {

			switch ($wsdl) {
				case self::WSDLAuth:
					return 'SoapAuthenticationServices';
					break;
					
				case self::WSDLCover:
					return 'SoapCoverServices';
					break;
					
				case self::WSDLMovie:
					return 'SoapMovieServices';
					break;
					
				case self::WSDLPornstar:
					return 'SoapPornstarServices';
					break;
					
				case self::WSDLSettings:
					return 'SoapSettingsServices';
					break;
					
				case self::WSDLUser:
					return 'SoapUserServices';
					break;
				
				default:
					throw new VCDProgramException('No handler available for wsdl: ' . $wsdl);
			}		
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Check the cache folder for wsdl file, if not generate new one
	 * with the correct endpoint url.
	 *
	 * @param string $wsdl | The wsdl file to use
	 * @return bool | Returns true if file exist in the cache folder
	 */
	private function checkWSDLCache($wsdl) {
		$wsdlFile = VCDDB_BASE.'/includes/wsdl/'.$wsdl;
		if (file_exists(VCDDB_BASE.DIRECTORY_SEPARATOR.CACHE_FOLDER.$wsdl)) {
			return true;
		}
		$tempUri = '<soap:address location="http://tempuri"/>';
		$proto = "http" . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "s" : "") . "://";
		$uri = $proto.$_SERVER['HTTP_HOST'].$_SERVER[PHP_SELF];
		$newUri = "<soap:address location=\"{$uri}\"/>";
		$wsdlData = str_replace($tempUri,$newUri, file_get_contents($wsdlFile));
		return VCDUtils::write(VCDDB_BASE.DIRECTORY_SEPARATOR.CACHE_FOLDER.$wsdl, $wsdlData);
		
	}
	
	/**
	 * Check the HTTP header for credentials
	 *
	 */
	private function checkCredentials() {
		try {
		
			$username = null;
			$password = null;
			
			if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
				
				$username = $_SERVER['PHP_AUTH_USER'];
				$password = $_SERVER['PHP_AUTH_PW']; 	
								
				
				if ((strcmp($username,'vcddb') == 0) && (strcmp($password,VCDDB_SOAPSECRET)==0)) {
					return;
				} else {
					
					$userObj = UserServices::getUserByUsername($username);
					if ($userObj instanceof userObj && !$userObj->isDeleted() 
						&& strcmp($userObj->getPassword(),$password) == 0) {
						// We have a valid user ...
						// Add userObj to session
						$_SESSION['user'] = $userObj;
						self::$userObj = $userObj;
						return;
					} else {
						throw new VCDSecurityException('Invalid Credentials.');
					}
				}
			} else {
				$this->sendAuthHeader();
			}
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Send 401 Auth header if HTTP Auth request did not follow
	 *
	 */
	private function sendAuthHeader() {
		header('HTTP/1.0 401 Unauthorized');
    	header('WWW-Authenticate: Basic realm="VCD-db WebServices"');
    	session_write_close();
   		@ob_end_clean();
    	header('Content-type: application/xml');
	   	die($this->getInvalidAuthResponse());
    }
	
	
    /**
     * Generate the error message as XML-SOAP Exception when no auth header is supplied.
     *
     * @return string
     */
	private function getInvalidAuthResponse() {
		$err = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><SOAP-ENV:Envelope xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\"><SOAP-ENV:Body><SOAP-ENV:Fault><faultcode>1</faultcode><faultstring>No credentials supplied.</faultstring><faultactor>Client</faultactor><detail>Error</detail></SOAP-ENV:Fault></SOAP-ENV:Body></SOAP-ENV:Envelope>";
		return $err;
	}
	
	/**
	 * Handle Exception and transform them to SOAP exception.
	 *
	 * @param Exception $ex | The Exception to handle
	 * @return string | The XML-SOAP exception that is returned
	 */
	public static function handleSoapError(Exception $ex) {
	
		if (!self::$forceNuSoap && extension_loaded('soap')) {
			return self::$server->fault((string)$ex->getCode(), $ex->getMessage(), 'Server', $ex->getTraceAsString());
		} else {
			return new soap_fault('1', 'Server', $ex->getMessage());
		}
	}
	
	
	/**
	 * Check if the current has permission to perform a restricted operation.
	 *
	 */
	public static function isAdmin() {
		try {

			if (!(VCDUtils::isLoggedIn() && (self::$$userObj->isAdmin()))) {
				throw new VCDSecurityException('Unauthorized to use this method.');
			}
		
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
}

class SoapMovieServices extends MovieServices {
	
	
	/**
	 * Get movie by ID
	 *
	 * @param int $movie_id
	 * @return vcdObj
	 */
	public static function getVcdByID($movie_id) {
		try {
			
			return parent::getVcdByID($movie_id)->toSoapEncoding();
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Create a new movie object, returns the ID of the newly created movie object.
	 *
	 * @param vcdObj $obj
	 * @return int
	 */
	public static function addVcd($obj) {
		try {
			
			return parent::addVcd(VCDSoapTools::GetVcdObj($obj));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Update movie
	 *
	 * @param vcdObj $obj
	 */
	public static function updateVcd($obj) {
		try {
			
			parent::updateVcd(VCDSoapTools::GetVcdObj($obj));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Update instance of a movie object, updated the media type and the cd count.
	 *
	 * @param int $vcd_id | The ID of the vcd object
	 * @param int $new_mediaid | The old media type object ID
	 * @param int $old_mediaid | The new media type object ID
	 * @param int $new_numcds | The new number of cd count
	 * @param int $oldnumcds | The old number of cd count
	 */
	public static function updateVcdInstance($vcd_id, $new_mediaid, $old_mediaid, $new_numcds, $oldnumcds) {
		try {
			
			parent::updateVcdInstance($vcd_id, $new_mediaid, $old_mediaid, $new_numcds, $oldnumcds);
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Remove user from the owner list of a specific movie.
	 * If $mode is set to 'full' all records about that movie is deleted.
	 * Should not be called unless the specified user_id is the only owner of the movie.  
	 * If $mode is set to 'single', the record linking to the user is the only thing that will be deleted.  
	 * Returns true on success otherwise false.
	 *
	 * @param int $vcd_id | The vcd object ID
	 * @param int $media_id | The media type ID to remove
	 * @param string $mode | can be 'full' or 'single'
	 * @param int $user_id | The Owner ID of the vcd object
	 * @return bool
	 */
	public static function deleteVcdFromUser($vcd_id, $media_id, $mode, $user_id = -1) {
		try {
			
			return parent::deleteVcdFromUser($vcd_id, $media_id, $mode, $user_id);
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get all vcd objects that belong to a certain category ID.  The $start and $end are used as pager variables.
	 * The $user_id param is optional. Returns array of vcd objects.
	 *
	 * @param int $category_id | The category ID to filter by
	 * @param int $start | The start row of the recordset to get
	 * @param int $end | The end row of the the recordset to get
	 * @param int $user_id | Filter by specific owner ID
	 * @return array
	 */
	public static function getVcdByCategory($category_id, $start=0, $end=0, $user_id = -1) {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getVcdByCategory($category_id, $start, $end, $user_id));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get all vcd objects that belong to a certain category ID.  The $start and $end are used as pager variables.
	 * Returns array of vcd objects.
	 *
	 * @param int $category_id | The category ID to filter by
	 * @param int $start | The start row of the recordset to get
	 * @param int $end | The end row of the the recordset to get
	 * @param int $user_id | Filter by specific owner ID
	 * @return array
	 */
	public static function getVcdByCategoryFiltered($category_id, $start=0, $end=0, $user_id) {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getVcdByCategoryFiltered($category_id, $start, $end, $user_id));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get all vcd objects that belong to a specific owner ID.  Param $simple tells if the vcd object shall be fully populated.
	 * Returns array of vcd objects.
	 *
	 * @param int $user_id | The Owner ID to get movies by
	 * @param bool $simple | Get partially populated vcd objects or fully populated
	 * @return array
	 */
	public static function getAllVcdByUserId($user_id, $simple = true) {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getAllVcdByUserId($user_id, $simple));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get list of users vcd objects, ordered by creation date DESC.
	 * Returns array of vcd objects.
	 *
	 * @param int $user_id | The Owner ID of the vcd objects
	 * @param int $count | Number of results to get
	 * @param bool $simple | Get partially populated vcd objects or fully populated
	 * @return array
	 */
	public static function getLatestVcdsByUserID($user_id, $count, $simple = true) {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getLatestVcdsByUserID($user_id, $count, $simple));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get all vcd objects for lists creations.  Returns array of vcd objects.
	 *
	 * @param int $excluded_userid | The ID of the owner to exclude
	 * @return array
	 */
	public static function getAllVcdForList($excluded_userid) {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getAllVcdForList($excluded_userid));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get specific movies by ID's.  Returns array of vcd objects.
	 *
	 * @param array $arrIDs | array of integers representing movie ID's
	 * @return array
	 */
	public static function getVcdForListByIds($arrIDs) {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getVcdForListByIds($arrIDs));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Add a new instance of a vcd object to user.
	 *
	 * @param int $user_id | The User ID to link the movie to
	 * @param int $vcd_id | The vcd object ID to link to user
	 * @param int $mediatype | The ID of the mediaType object
	 * @param int $cds | The number of CD's this copy uses
	 */
	public static function addVcdToUser($user_id, $vcd_id, $mediatype, $cds) {
		try {
			
			parent::addVcdToUser($user_id, $vcd_id, $mediatype, $cds);
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get count of all vcd objects that belong to a specified category.
	 * Returns the number of vcd objects that match the given criteria.
	 *
	 * @param int $category_id | The ID of the mediatype to filter by
	 * @param int $user_id | The Owner ID
	 * @return int
	 */
	public static function getCategoryCount($category_id, $user_id = -1) {
		try {
			
			return parent::getCategoryCount($category_id, false, $user_id);
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get the number of movies for selected category after user filter has been applied.
	 *
	 * @param int $category_id | The ID of the mediatype to filter by
	 * @param int $user_id | The Owner ID
	 * @return int
	 */
	public static function getCategoryCountFiltered($category_id, $user_id) {
		try {
			
			return parent::getCategoryCountFiltered($category_id, $user_id);
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get the Top Ten list of latest movies.
	 * $category_id can be used to filter results to specified category.
	 * Returns array of vcd objects.
	 *
	 * @param int $category_id | The ID of the mediatype to filter by
	 * @param array $arrFilter | array of category id's to exclude
	 * @return array
	 */
	public static function getTopTenList($category_id = 0, $arrFilter = null) {
		try {

			return VCDSoapTools::EncodeArray(parent::getTopTenList($category_id, $arrFilter));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Search the database. Returns array of vcd Objects.
	 * Param $method defines the search type. Search type can be 'title', 'actor' or 'director'
	 *
	 * @param string $keyword | The keyword to search by
	 * @param string $method | The search method to use can be one of the following [title, actor, director]
	 * @return array
	 */
	public static function search($keyword, $method) {
		try {
			
			return VCDSoapTools::EncodeArray(parent::search($keyword, $method));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Perform advanced search, where filters can be applied.  All params except $title are optional.
	 * Returns array of vcd objects.
	 *
	 * @param string $title | The search keyword
	 * @param int $category | The ID of the movieCategory object to filter by
	 * @param int $year | The Year of production to filter by
	 * @param int $mediatype | The ID of the mediaType object to filter by
	 * @param int $owner | The Owner ID to filter by
	 * @param float $imdbgrade | The minimum IMDB grade to use a filter
	 * @return array
	 */
	public static function advancedSearch($title = null, $category = null, $year = null, $mediatype = null,
								   $owner = null, $imdbgrade = null) {
		try {
						
			if ($category==0) {$category=null;}
			if ($year==0) {$year=null;}
			if ($mediatype==0) {$mediatype=null;}
			if ($owner==0) {$owner=null;}
			if ($imdbgrade==0) {$imdbgrade=null;}
			
			return parent::advancedSearch($title, $category, $year, $mediatype, $owner, $imdbgrade);
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Perform a cross join of movies by the logged-in user and some other Owner ID.
	 * Param $method represents the join action, returns array of vcd objects.
	 * 1 = Movies I own but user not
	 * 2 = Movies user owns but i dont
	 * 3 = Movies we both own
	 *
	 * @param int $user_id | The Other Owner ID to perform cross join with
	 * @param int $media_id | The mediatype ID to limit results by
	 * @param int $category_id | The category type ID to limit results
	 * @param int $method | The Join method to use, 1,2 or 3
	 * @return array
	 */
	public static function crossJoin($user_id, $media_id, $category_id, $method) {
		try {
			
			return VCDSoapTools::EncodeArray(parent::crossJoin($user_id, $media_id, $category_id, $method));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
 	 * Get all vcd objects by User ID for printview.
	 * $list_type can be 'all', 'movies', 'tv', text or 'blue'
	 * Returns array of vcd objects.
	 *
	 * @param int $user_id | The Owner ID of the movie objects
	 * @param string $list_type | The list type [all,movies,tv,text,blue]
	 * @return array
	 */
	public static function getPrintViewList($user_id, $list_type) {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getPrintViewList($user_id, $list_type));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Find a random vcd object, params $category_id and $use_seenlist are optional.
	 *
	 * @param int $category_id | The movie category ID to limit results by
	 * @param bool $use_seenlist | Filter search result only to unseen movies.
	 * @return vcdObj
	 */
	public static function getRandomMovie($category_id, $use_seenlist = false) {
		try {
			
			return parent::getRandomMovie($category_id, $use_seenlist)->toSoapEncoding();
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get similiar movies as an array of vcd objects.
	 * Movies in same category as the one specified in the $vcd_id param will be returned.
	 *
	 * @param int $vcd_id | The ID of the vcd object to search by
	 * @return array
	 */
	public static function getSimilarMovies($vcd_id) {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getSimilarMovies($vcd_id));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get the number of movies that user owns
	 *
	 * @param itn $user_id | The Owner ID to seek by
	 * @return int
	 */
	public static function getMovieCount($user_id) {
		try {
			
			return parent::getMovieCount($user_id);
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get vcd objects that are marked adult, and that are in a specific adult category.
	 * Returns array of vcd objects.
	 *
	 * @param int $category_id | The ID of the porncategory object to filter by
	 * @return array
	 */
	public static function getVcdByAdultCategory($category_id) {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getVcdByAdultCategory($category_id));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get all vcd objects that are linked to a specific adult studio.
	 * Returns array of vcd objects.
	 *
	 * @param int $studio_id | The ID of the studio object to filter by
	 * @return array
	 */
	public static function getVcdByAdultStudio($studio_id) {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getVcdByAdultStudio($studio_id));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Set the screenshot flag on a specified movie object.
	 *
	 * @param int $vcd_id | The ID of the vcd object
	 */
	public static function markVcdWithScreenshots($vcd_id) {
		try {
			
			parent::markVcdWithScreenshots($vcd_id);;
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Check if the movie has some screenshots.  Returns true if screenshots exist, otherwise false.
	 *
	 * @param int $vcd_id | The ID of the movie object
	 * @return bool
	 */
	public static function getScreenshots($vcd_id) {
		try {
			
			return parent::getScreenshots($vcd_id);
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	
	/**
	 * Get list of duplicate movies in the database
	 *
	 * @return array | Returns array of duplicate movie data
	 */
	public static function getDuplicationList() {
		try {
			
			return parent::getDuplicationList();
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
}

class SoapAuthenticationServices {
	
	public static function authenticate($username, $password) {
		try {
						
			$password = md5($password);
			$userObj = UserServices::getUserByUsername($username);
			if ($userObj instanceof userObj && !$userObj->isDeleted() 
				&& strcmp($userObj->getPassword(),$password) == 0) {
				// We have a valid user ...
				// Add userObj to session
				$_SESSION['user'] = $userObj;
				return true;
			} else {
				return false;			
			}
	
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
}

class SoapCoverServices extends CoverServices {
	
	
		/**
	 * Get all cover types in VCD-db, returns array of coverType objects
	 *
	 * @return array
	 */
	public static function getAllCoverTypes() {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getAllCoverTypes());
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	
	/**
	 * Add a new coverType object
	 *
	 * @param cdcoverTypeObj $obj
	 */
	public static function addCoverType($obj) {
		try {
			
			parent::addCoverType(VCDSoapTools::GetCoverTypeObj($obj));;
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Delete a specific cover type object
	 *
	 * @param int $type_id | The ID of the cover type object to delete
	 */
	public static function deleteCoverType($type_id) {
		try {
			
			VCDSoapService::isAdmin();
			parent::deleteCoverType($type_id);
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get all coverTypes used by this specific movie, returns array of coverType objects
	 *
	 * @param int $mediatype_id | The mediaType ID of the movie
	 * @return array
	 */
	public static function getAllCoverTypesForVcd($mediatype_id) {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getAllCoverTypesForVcd($mediatype_id));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get coverType object by ID
	 *
	 * @param int $covertype_id | The ID of the coverType object
	 * @return cdcoverTypeObj
	 */
	public static function getCoverTypeById($covertype_id) {
		try {
			
			return parent::getCoverTypeById($covertype_id)->toSoapEncoding();
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get coverType object by Name
	 *
	 * @param string $name | The name of the coverType object
	 * @return cdcoverTypeObj
	 */
	public static function getCoverTypeByName($name) {
		try {
			
			return parent::getCoverTypeByName($name)->toSoapEncoding();
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Update specific coverType object
	 *
	 * @param cdcoverTypeObj $obj
	 */
	public static function updateCoverType($obj) {
		try {
			
			VCDSoapService::isAdmin();
			parent::updateCoverType(VCDSoapTools::GetCoverTypeObj($obj));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * The a specific cover by ID
	 *
	 * @param int $cover_id | 
	 * @return cdcoverObj
	 */
	public static function getCoverById($cover_id) {
		try {
			
			return parent::getCoverById($cover_id)->toSoapEncoding();
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	
	/**
	 * Get all Covers stored in VCD-db.  Returns array of cdcoverObjects
	 *
	 * @return array | Array of cdcoverObjects
	 */
	public static function getAllCovers() {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getAllCovers());
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	
	/**
	 * Get all cover objects that belong to a specified movie, returns array of cdcover objects
	 *
	 * @param int $vcd_id | The ID of the movie that owns the covers
	 * @return array
	 */
	public static function getAllCoversForVcd($vcd_id) {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getAllCoversForVcd($vcd_id));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Add a new cover to VCD-db
	 *
	 * @param cdcoverObj $obj
	 */
	public static function addCover($obj) {
		try {
			
			parent::addCover(VCDSoapTools::GetCoverObj($obj));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Delete a specific cover from VCD-db
	 *
	 * @param int $cover_id
	 */
	public static function deleteCover($cover_id) {
		try {
			
			parent::deleteCover($cover_id);
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Update cover object
	 *
	 * @param cdcoverObj $obj
	 */
	public static function updateCover($obj) {
		try {
			
			parent::updateCover(VCDSoapTools::GetCoverObj($obj));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get all cdcoverType objects associated with incoming mediaType objects.
	 * Parameter should contain array of mediaType objects, 
	 * returns an array of cdcoverType objects if none are found
	 *
	 * @param array $mediaTypeObjArr | Array of mediaType objects
	 * @return array
	 */
	public static function getAllowedCoversForVcd($mediaTypeObjArr) {
		try {
			
			$arrMediaTypes = array();
			foreach ($mediaTypeObjArr as $obj) {
				array_push($arrMediaTypes, VCDSoapTools::GetMediaTypeObj($obj));
			}
			
			return VCDSoapTools::EncodeArray(parent::getAllowedCoversForVcd($arrMediaTypes));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	
	/**
	 * Assign a new coverType object to a specified mediaType.
	 *
	 * @param int $mediaTypeID | The media type ID to use for assignment
	 * @param array $coverTypeIDArr | Array of integers representing coverType ID's
	 */
	public static function addCoverTypesToMedia($mediaTypeID, $coverTypeIDArr) {
		try {
			
			parent::addCoverTypesToMedia($mediaTypeID, $coverTypeIDArr);
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get All coverType associated with the specified mediaType ID, returns array of cdcoverType objects.
	 *
	 * @param int $mediaType_id | The ID of the mediaType object
	 * @return array
	 */
	public static function getCDcoverTypesOnMediaType($mediaType_id) {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getCDcoverTypesOnMediaType($mediaType_id));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get all thumbnails that the specified user has created in VCD-db, returns array of cdcover objects.
	 * And the cdcover objects are all of type 'Thumbnail'
	 *
	 * @param int $user_id | The Owner ID of the thumbnails
	 * @return array
	 */
	public static function getAllThumbnailsForXMLExport($user_id) {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getAllThumbnailsForXMLExport($user_id));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	
	/**
	 * Move all CD-covers in VCD-db from database to harddrive
	 *
	 * @return int | The number of affected covers
	 */
	public static function moveCoversToDisk() {
		try {
			
			VCDSoapService::isAdmin();
			return parent::moveCoversToDisk();
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Move all CD-covers in VCD-db from harddrive to database
	 *
	 * @return int | The number of affected covers
	 */
	public static function moveCoversToDatabase() {
		try {
			
			VCDSoapService::isAdmin();
			return parent::moveCoversToDatabase();
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
}

class SoapSettingsServices extends SettingsServices {
	
	
	
	public static function getSettingsByKey($key) {
		try {
			return parent::getSettingsByKey($key);
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function addMetadata($arrObj) {
		try {
			
			$inArr = array();
			foreach ($arrObj as $obj) {
				array_push($inArr, VCDSoapTools::GetMetadataObj($obj));
			}
			parent::addMetadata($inArr);
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function addMetaDataType($obj) {
		try {
			
			parent::addMetadataType(VCDSoapTools::GetMetadataTypeObj($obj));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	
	public static function getSettingsByID($settings_id) {
		try {
			return parent::getSettingsByID($settings_id)->toSoapEncoding();
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function updateSettings($obj) {
		try {
			
			VCDSoapService::isAdmin();
			parent::updateSettings(VCDSoapTools::GetSettingsObj($obj));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function getAllSettings()	{
		try {
			
			return VCDSoapTools::EncodeArray(parent::getAllSettings());
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function getMetadata($record_id, $user_id, $metadata_name, $mediatype_id = null) {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getMetadata($record_id, $user_id, $metadata_name, $mediatype_id));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function deleteMetaDataType($type_id) {
		try {
			
			parent::deleteMetaDataType($type_id);
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function getMetadataTypes($user_id) {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getMetadataTypes($user_id));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function getSourceSites()  {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getSourceSites());
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function getSourceSiteByID($source_id) {
		try {
			return parent::getSourceSiteByID($source_id)->toSoapEncoding();
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function getSourceSiteByAlias($alias) {
		try {
			
			return parent::getSourceSiteByAlias($alias)->toSoapEncoding();
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function getAllMediatypes() {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getAllMediatypes());
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function getMediaTypeByName($name) {
		try {
			
			return parent::getMediaTypeByName($name)->toSoapEncoding();
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function getMediaTypeByID($media_id) {
		try {
			
			return parent::getMediaTypeByID($media_id)->toSoapEncoding();
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function getMediaTypesInUseByUserID($user_id)	{
		try {
			
			$data = parent::getMediaTypesInUseByUserID($user_id);
			$stringArray = array();
			foreach ($data as $item) {
				$stringArray[] = implode("|", $item);
			}
			return $stringArray;
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function getMediaCountByCategoryAndUserID($user_id, $category_id) {
		try {
			
			$data = parent::getMediaCountByCategoryAndUserID($user_id, $category_id);
			$stringArray = array();
			foreach ($data as $item) {
				$stringArray[] = implode("|", $item);
			}
			return $stringArray;
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function getCategoriesInUseByUserID($user_id) {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getCategoriesInUseByUserID($user_id));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	
	/**
	 * Get the count of media's in the specified movie category
	 *
	 * @param int $category_id | The category ID to use
	 * @return array
	 */
	public static function getMediaCountByCategory($category_id) {
		try {
			
			return parent::getMediaCountByCategory($category_id);
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function addMediaType($mediaTypeObj) {
		try {
			
			parent::addMediaType(VCDSoapTools::GetMediaTypeObj($mediaTypeObj));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	

	public static function getAllMovieCategories() {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getAllMovieCategories());
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function getMovieCategoriesInUse()  {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getMovieCategoriesInUse());
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function getMovieCategoryByID($category_id) {
		try {
		
			return parent::getMovieCategoryByID($category_id)->toSoapEncoding();
				
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function addMovieCategory($obj)  {
		try {
			
			parent::addMovieCategory(VCDSoapTools::GetMovieCategoryObj($obj));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function getCategoryIDByName($name, $localized = false) {
		try {
			
			return parent::getCategoryIDByName($name, $localized);
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function getStatsObj() {
		try {
			
			return parent::getStatsObj()->toSoapEncoding();
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	

	public static function getUserStatistics($user_id, $type) {
		try {
			
			$arrValidTypes = array('year','category','media');
			if (!in_array($type, $arrValidTypes)) {
				throw new VCDConstraintException($type . " is not a valid option.");
			}
			
			$data = parent::getUserStatistics($user_id);
			$results = array();
			foreach ($data[$type] as $item) {
				$results[] = implode("|", $item);
			}
			return $results;
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function getRssFeedsByUserId($user_id) {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getRssFeedsByUserId($user_id));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function addRssfeed($data) {
		try {
			
			parent::addRssfeed(VCDSoapTools::GetRssObj($data));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function getRssfeed($feed_id) {
		try {
			
			return parent::getRssfeed($feed_id)->toSoapEncoding();
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function isPublicWishLists($user_id) {
		try {
			
			return parent::isPublicWishLists($user_id);
					
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	
	public static function getBorrowerByID($borrower_id) {
		try {

			return parent::getBorrowerByID($borrower_id)->toSoapEncoding();
				
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function addBorrower($obj) {
		try {

			parent::addBorrower(VCDSoapTools::GetBorrowerObj($obj));
				
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function updateBorrower($obj) {
		try {

			parent::updateBorrower(VCDSoapTools::GetBorrowerObj($obj));
					
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
		
	public static function loanCDs($borrower_id, $arrMovieIDs) {
		try {
			
			parent::loanCDs($borrower_id, $arrMovieIDs);
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function getLoans($user_id, $show_returned) {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getLoans($user_id, $show_returned));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	
	/**
	 * Get all the borrower objects created by a specific User.
	 * Returns array of borrower objects.
	 *
	 * @param int $user_id | The User ID of the user that is the owner of the borrower objects
	 * @return array
	 */
	public static function getBorrowersByUserID($user_id) {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getBorrowersByUserID($user_id));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function getLoansByBorrowerID($user_id, $borrower_id, $show_returned = false) {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getLoansByBorrowerID($user_id, $borrower_id, $show_returned));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Add a new comment.
	 *
	 * @param commentObj $obj
	 */
	public static function addComment($obj) {
		try {
				
			parent::addComment(VCDSoapTools::GetCommentObj($obj));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Delete comment.
	 *
	 * @param int $comment_id | The ID of the comment to delete
	 */
	public static function deleteComment($comment_id) {
		try {
			
			parent::deleteComment($comment_id);
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get a specific comment by ID.
	 *
	 * @param int $comment_id | The ID of the comment
	 * @return commentObj
	 */
	public static function getCommentByID($comment_id) {
		try {
			
			return parent::getCommentByID($comment_id)->toSoapEncoding();
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get all comments by specific user ID, returns array of comment objects.
	 *
	 * @param int $user_id | The Owner ID of the comments.
	 * @return array
	 */
	public static function getAllCommentsByUserID($user_id) {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getAllCommentsByUserID($user_id));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get all comments that have been made on a specific movie.
	 * Returns array of comments objects.
	 *
	 * @param int $vcd_id | The ID of the movie to get comments
	 * @return array
	 */
	public static function getAllCommentsByVCD($vcd_id) {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getAllCommentsByVCD($vcd_id));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	
	/**
	 * Check is a specified movie is on users wishlist.
	 *
	 * @param int $vcd_id | The ID of the movie to check
	 * @return bool
	 */
	public static function isOnWishList($vcd_id) {
		try {
			
			return parent::isOnWishList($vcd_id);
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	
	/**
	 * Get a wishlist by User ID.  Returns assoc array containg keys [id, title, mine] 
	 *
	 * @param int $user_id | The Owner of the wishlist
	 * @return array
	 */
	public static function getWishList($user_id) {
		try {
			$results = array();
			$list = parent::getWishList($user_id);
			foreach ($list as $item) {
				array_push($results, $item[0] .'|'.$item[1].'|'.$item[2]);
			}
			return $results;
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	
}

class SoapPornstarServices extends PornstarServices {
	
	
	public static function getPornstarByID($pornstar_id) {
		try {
			
			return parent::getPornstarByID($pornstar_id)->toSoapEncoding();
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function getPornstarByName($name) {
		try {
			
			$obj = parent::getPornstarByName($name);
			if ($obj instanceof pornstarObj ) {
				return $obj->toSoapEncoding();	
			} else {
				return null;
			}
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function addAdultCategory($obj) {
		try {
			
			$porncategoryObj = VCDSoapTools::GetPornCategoryObj($obj);
			parent::addAdultCategory($porncategoryObj);
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function addPornstar($obj) {
		try {
			
			$pornstarObj = VCDSoapTools::GetPornstarObj($obj);
			parent::addPornstar($pornstarObj)->toSoapEncoding();
						
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function addMovieToStudio($studio_id, $vcd_id) {	
		try {
			parent::addMovieToStudio($studio_id, $vcd_id);
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function addStudio($obj) {
		try {

			parent::addStudio(VCDSoapTools::GetStudioObj($obj));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function updatePornstar($obj) {
		try {
			parent::updatePornstar(VCDSoapTools::GetPornstarObj($obj));
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	
	
	public static function deleteStudio($studio_id) {
		try {
			
			VCDSoapService::isAdmin();
			parent::deleteStudio($studio_id);
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	
	
	public static function deleteAdultCategory($category_id) {
		try {
			
			VCDSoapService::isAdmin();
			parent::deleteAdultCategory($category_id);
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	
	public static function getStudioByID($studio_id) {
		try { 
			
			return parent::getStudioByID($studio_id)->toSoapEncoding();
		
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function getStudioByName($studio_name) {
		try { 
			
			$obj = parent::getStudioByName($studio_name);
			if ($obj instanceof studioObj ) {
				return $obj->toSoapEncoding();
			} else {
				return null;
			}
		
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	
	
	
	public static function getStudioByMovieID($vcd_id) {
		try { 
			
			$obj = parent::getStudioByMovieID($vcd_id);
			if ($obj instanceof studioObj ) {
				return $obj->toSoapEncoding();
			} else {
				return null;
			}
		
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	
	public static function getStudiosInUse() {
		try { 
			
			$data = array();
			foreach (parent::getStudiosInUse() as $studioObj) {
				array_push($data, $studioObj->toSoapEncoding());
			}
			return $data;
		
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function getAllStudios() {
		try { 
			
			$data = array();
			foreach (parent::getAllStudios() as $studioObj) {
				array_push($data, $studioObj->toSoapEncoding());
			}
			return $data;
		
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	
	public static function getPornstarsByMovieID($movie_id) {
		try {
			
			$pornstars = parent::getPornstarsByMovieID($movie_id);
			$data = array();
			
			foreach ($pornstars as $pornstar) {
				array_push($data, $pornstar->toSoapEncoding());
			}
		
			return $data;
				
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	
	public static function getPornstarsByLetter($letter, $active_only) {
		try {
			
			$pornstars = parent::getPornstarsByLetter($letter, $active_only);
			$data = array();
			foreach ($pornstars as $pornstar) {
				array_push($data, $pornstar->toSoapEncoding());
			}
			return $data;
				
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	
	public static function getPornstarsAlphabet($active_only) {
		try {
					
			return parent::getPornstarsAlphabet($active_only);
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function getAllPornstars() {
		try {
			
			$data = array();
			foreach (parent::getAllPornstars() as $pornstar) {
				array_push($data, $pornstar->toSoapEncoding());
			}
			return $data;
				
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	
	public static function getSubCategoriesByMovieID($vcd_id) {
		try {
			
			$data = array();
			foreach (parent::getSubCategoriesByMovieID($vcd_id) as $catObj) {
				array_push($data, $catObj->toSoapEncoding());
			}
			return $data;
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function getSubCategoryByID($category_id) {
		try {
			
			return parent::getSubCategoryByID($category_id)->toSoapEncoding();
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function getSubCategoriesInUse() {
		try {
			
			$data = array();
			foreach (parent::getSubCategoriesInUse() as $catObj) {
				array_push($data, $catObj->toSoapEncoding());
			}
			return $data;
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	public static function getValidCategories($arrCategoryNames) {
		try {
			
			$data = array();
			foreach (parent::getValidCategories($arrCategoryNames) as $catObj) {
				array_push($data, $catObj->toSoapEncoding());
			}
			return $data;
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	
	
	public static function getSubCategories() {
		try {
			
			$data = array();
			foreach (parent::getSubCategories() as $catObj) {
				array_push($data, $catObj->toSoapEncoding());
			}
			return $data;
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
}

class SoapUserServices extends UserServices  {
	
	/**
	 * Get user By ID
	 *
	 * @param int $user_id
	 * @return userObj
	 */
	public static function getUserByID($user_id) {
		try {
			
			return parent::getUserByID($user_id)->toSoapEncoding();
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Update User, return true on success otherwise false
	 *
	 * @param userObj $userObj
	 * @return bool
	 */
	public static function updateUser($userObj) {
		try {
			
			return parent::updateUser(VCDSoapTools::GetUserObj($userObj));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Delete User
	 *
	 * @param int $user_id | The userID
	 * @param bool $erase_data | Delete all user data including movie list
	 */
	public static function deleteUser($user_id, $erase_data = false) {
		try {
			
			VCDSoapService::isAdmin();
			return parent::deleteUser($user_id, $erase_data);
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Add new User to VCD-db, returns true on success otherwise false
	 *
	 * @param userObj $userObj | The userObj to create
	 * @return bool
	 */
	
	public static function addUser($userObj) {
		try {
			
			VCDSoapService::isAdmin();
			return parent::addUser(VCDSoapTools::GetUserObj($userObj));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get all Users in VCD-db, returns array of User objects
	 *
	 * @return array
	 */
	public static function getAllUsers() {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getAllUsers());
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get all users that have added a movie to VCD-db, return array of User objects
	 *
	 * @return array
	 */
	public static function getActiveUsers() {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getActiveUsers());
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get User by specific username
	 *
	 * @param string $user_name | The username
	 * @return userObj
	 */
	public static function getUserByUsername($user_name) {
		try {
			
			return parent::getUserByUsername($user_name)->toSoapEncoding();
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Store the users session after user has requested to be remembered during login.
	 *
	 * @param string $session_id | The hashed session id
	 * @param int $user_id | The User ID
	 */
	public static function addSession($session_id, $user_id) {
		try {
			
			parent::addSession($session_id, $user_id);
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Check if the specified session in valid for authentication.
	 * Returns true if the session is still valid, otherwise false.
	 *
	 * @param string $session_id | The session ID from cookie
	 * @param string $session_time | The original session save time
	 * @param int $user_id | The User ID
	 * @return bool
	 */
	public static function isValidSession($session_id, $session_time, $user_id) {
		try {
			
			return parent::isValidSession($session_id, $session_time, $user_id);
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
			
	/**
	 * Get all user roles in VCD-db.  Returns array of UserRole objects.
	 *
	 * @return array
	 */
	public static function getAllUserRoles() {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getAllUserRoles());
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get all users in specific role.  Returns array of Users objects.
	 *
	 * @param int $role_id | The ID of the userrole to seek by
	 * @return array
	 */
	public static function getAllUsersInRole($role_id) {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getAllUsersInRole($role_id));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	

	/**
	 * Delete a specific user role, error is thrown is some user/s is still using this role.
	 * Returns true if actions secceds otherwise false. 
	 *
	 * @param int $role_id | The userrole ID to delete
	 * @return bool
	 */
	public static function deleteUserRole($role_id) {
		try {
			
			VCDSoapService::isAdmin();
			return parent::deleteUserRole($role_id);
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get the default role assigned to new users that are created/registered in VCD-db.
	 *
	 * @return userRoleObj
	 */
	public static function getDefaultRole() {
		try {
			
			return parent::getDefaultRole()->toSoapEncoding();
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Assign a specific role as a default role to new users.
	 *
	 * @param int $role_id | The ID of the UserRole to use.
	 */
	public static function setDefaultRole($role_id) {
		try {
			
			VCDSoapService::isAdmin();
			parent::setDefaultRole($role_id);
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}

	/**
	 * Get all user properties in VCD-db.  Returns array of userProperties objects.
	 *
	 * @return array
	 */
	public static function getAllProperties() {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getAllProperties());
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get specific userProperties object by ID.
	 *
	 * @param int $property_id | The ID of the userProperty
	 * @return userPropertiesObj
	 */
	public static function getPropertyById($property_id) {
		try {
			
			return parent::getPropertyById($property_id)->toSoapEncoding();
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get specific userProperty object by the property key.
	 *
	 * @param string $property_key | The property key
	 * @return userPropertiesObj
	 */
	public static function getPropertyByKey($property_key) {
		try {
			
			return parent::getPropertyByKey($property_key)->toSoapEncoding();
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Add new userProperty object to VCD-db.
	 *
	 * @param userPropertiesObj $obj
	 */
	public static function addProperty($obj) {
		try {
			
			parent::addProperty(VCDSoapTools::GetUserPropertyObj($obj));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Delete a specific user Property object.
	 *
	 * @param int $property_id | The ID of the userProperty object to delete.
	 */
	public static function deleteProperty($property_id) {
		try {
			
			VCDSoapService::isAdmin();
			parent::deleteProperty($property_id);
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Update specific userProperties object.
	 *
	 * @param userPropertiesObj $obj
	 */
	public static function updateProperty($obj) {
		try {
			
			parent::updateProperty(VCDSoapTools::GetUserPropertyObj($obj));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
			
	/**
	 * Add new userProperty to a specific user.
	 *
	 * @param int $property_id | The ID of the userProperties object
	 * @param int $user_id | The ID of the user to add the property to
	 */
	public static function addPropertyToUser($property_id, $user_id) {
		try {
			
			parent::addPropertyToUser($property_id, $user_id);
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Remove specific property from the selected User.
	 *
	 * @param int $property_id | The ID of the userProperties object
	 * @param int $user_id | The ID of the user to remove the property from
	 */
	public static function deletePropertyOnUser($property_id, $user_id) {
		try {
			
			parent::deletePropertyOnUser($property_id, $user_id);
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get all users that are associated to a a specific userProperty, returns array of User objects.
	 *
	 * @param int $property_id | The ID of the userProperties object
	 * @return array
	 */
	public static function getAllUsersWithProperty($property_id) {
		try {
			
			return VCDSoapTools::EncodeArray(parent::getAllUsersWithProperty($property_id));
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
	/**
	 * Get the users with most movie count in database.
	 * Returns array of strings sorted by highest movie count.
	 *
	 * @return array
	 */
	public static function getUserTopList() {
		try {
			
			$data = parent::getUserTopList();
			$fixedArr = array();
			foreach ($data as $item) {
				array_push($fixedArr, $item['user_name'].'|'.$item['count']);
			}
			return $fixedArr;
			
		} catch (Exception $ex) {
			return VCDSoapService::handleSoapError($ex);
		}
	}
	
}


?>