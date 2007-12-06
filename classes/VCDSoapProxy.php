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
 * @version $Id: VCDSoapProxy.php 1062 2007-07-05 15:10:11Z konni $
 * @since  0.990
  */
?>
<?php
require_once(dirname(__FILE__).'/VCDCache.php');

abstract class VCDProxy {
	
	/**
	 * The Remote VCD-db proxy instance
	 *
	 * @var nusoapclient
	 */
	private $proxy;
	private $namespace = '';
	private $soapaction = '';
	private $timeout = 0;
	private $responseTimeout = 60;
	private $cacheWSDL = true;
	protected $proxyUri;
	protected $wsdl;
	
	private $cachedData = null;
	
	/**
	 * Force the use of NuSoap even if phpsoap is available
	 *
	 * @var bool
	 */
	private $forceNuSoap = false;
	
	
	/**
	 * Class constructor
	 *
	 */
	protected function __construct() {
		try { 
			
			if ($this->cacheWSDL) {
                $this->checkWSDLCache($this->wsdl);
            }
			
            if (!$this->forceNuSoap && extension_loaded('soap')) {
                       	
            	if (VCDUtils::isLoggedIn()) {
					$userObj = $_SESSION['user'];
					$this->proxy = new SoapClient($this->wsdl, 
						array('login' => $userObj->getUsername(), 
							'password' => $userObj->getPassword(),
							'encoding' => 'UTF-8',
							'user_agent' => 'VCD-db '.VCDDB_VERSION,
							'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP));
				} else {
					$this->proxy = new SoapClient($this->wsdl, 
						array('login' => 'vcddb',
							'password' => VCDConfig::getWebservicePassword(),
							'encoding' => 'UTF-8',
							'user_agent' => 'VCD-db '.VCDDB_VERSION,
							'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP));
					
				}
            	
            } else {
            	
            	$this->proxy = new nusoap_client($this->wsdl, true, false,false,false,false, $this->timeout, $this->responseTimeout);
				$this->proxy->soap_defencoding = 'UTF-8';
				if (VCDUtils::isLoggedIn()) {
					$userObj = $_SESSION['user'];
					$this->proxy->setCredentials($userObj->getUsername(), $userObj->getPassword(), 'basic');
				} else {
					$this->proxy->setCredentials('vcddb', VCDDB_SOAPSECRET, 'basic');
				}
				$error = $this->proxy->getError();
				if ($error) {
					throw new VCDProgramException($error);
				}
            }

			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Override non existing functions and throws error.
	 *
	 * @param string $func
	 * @param array $params
	 */
	protected function __call($func, $params) {
		throw new VCDProgramException('Function ' . $func . ' is not implemented');
	}
	
	/**
	 * Call the remote webservice
	 *
	 * @param string $action | The remtote method to call
	 * @param array $params | The parameters to send
	 * @return mixed | The webservice call results
	 */
	protected function call($action, $params) {
		try {
			
			
			if ($this->checkCache($action, $params)) {
				return $this->cachedData;	
			}
			
			
			if ($this->proxy instanceof SoapClient ) {

				try {
					$result = $this->proxy->__soapCall($action, $params);
				} catch (Exception $ex) {
					if (strcmp($ex->getMessage(),'Invalid Credentials.')==0) {
						// Kill the session
						session_destroy();
						// Redirect
						redirect('?page=error&type=wscredentials');
					} 
					throw new SoapFault($ex->getMessage(), $ex->getMessage(), 'Server');
				}
				
				if (is_soap_fault($result)) {
					throw new VCDProgramException("Action: ".$action . "<break>Error: ".$result->faultstring, $result->faultcode);
				}
					
			} else {

				
				
				$result = $this->proxy->call($action, $params, $this->namespace, $this->soapaction);
				
				if ($this->proxy->fault) {
					throw new VCDSoapException($this->proxy->faultstring, $this->proxy->fault);
				}
				
				$error = $this->proxy->getError();
				if ($error) {
					throw new VCDProgramException("Action: ".$action . "<break>Error: ".$error);
				}
				
			}
			
			$this->addToCache(&$result, $action, $params);
			
			VCDConnection::addQueryCount();
			
			return $result;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}

	
	
	/**
	 * Check for cached data if the cache manager is being used.
	 *
	 * @param string $func | The function name mapped to cache ID
	 * @param array $params | The parameters used to invoke the functions
	 * @return mixed
	 */
	private function checkCache($func, $params) {
		try {

			if (is_array($params) && sizeof($params) > 0) {
				$cachedName = $func.implode('#',array_values($params));
			} else {
				$cachedName = $func;
			}
			
			$cachedName = md5($cachedName);
			
			if (VCDCache::exists($cachedName)) {
				$this->cachedData = VCDCache::get($cachedName);
				return true;
			} 

			return false;
			
		} catch (Exception $ex) {
			throw new VCDProgramException('Error in cache manager: ' . $ex->getMessage(), $ex->getCode());
		}
	}
	
	/**
	 * Add data to cache
	 *
	 * @param mixed $data | The data to store in the cache
	 * @param string $func | The id of the cached item
	 * @param array $params | The parameters that were used to invoke the function
	 */
	private function addToCache($data, $func, $params) {
        try {
            
            $cacheMap = VCDCacheMap::getMap();
            if (key_exists($func,$cacheMap)) {
                
                if (is_array($params) && sizeof($params) > 0) {
                    $cachedName = $func.implode('#',array_values($params));
                } else {
                    $cachedName = $func;
                }
                
                $cachedName = md5($cachedName);
                VCDCache::set($cachedName, $data, $cacheMap[$func]);
                
            }
                
        } catch (Exception $ex) {
            throw new VCDProgramException('Error in cache manager: ' . $ex->getMessage(), $ex->getCode());
        }
    }
	
	/**
	 * Check the wsdl cache before fetching the wsdl remotely
	 *
	 * @param string $wsdlLocation
	 */
	private function checkWSDLCache($wsdlLocation) {
        $filename = VCDDB_BASE.DIRECTORY_SEPARATOR.CACHE_FOLDER.md5($wsdlLocation).'.wsdl';
        if (file_exists($filename)) {
            $this->wsdl = $filename;
        } else {
            $contents = file_get_contents($wsdlLocation);
            VCDUtils::write($filename, $contents);
            $this->wsdl = $filename;
        }
    }
	
}

class SoapCoverProxy extends VCDProxy {
	private $classPrefix = 'SoapCoverServices';
	
	public function __construct() {
		
		$this->wsdl = VCDDB_SOAPPROXY.'proxy/cover.php?wsdl';
		$this->proxyUri = VCDDB_SOAPPROXY.'proxy/cover.php';
		$this->namespace = 'urn:http://vcddb.konni.com';
		$this->soapaction = 'urn:CoverServicesAction';
		parent::__construct();
	}
	
	protected function invoke($action, $params) {
		return $this->call($action, $params);
	}
	
	
	/**
	 * Get all cover types in VCD-db, returns array of coverType objects
	 *
	 * @return array
	 */
	public function getAllCoverTypes() {
		try {
			
			$data = $this->invoke('getAllCoverTypes', array());
			$results = array();
			foreach ($data as $item) {
				array_push($results, VCDSoapTools::GetCoverTypeObj($item));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Add a new coverType object
	 *
	 * @param cdcoverTypeObj $obj
	 */
	public function addCoverType(cdcoverTypeObj $obj) {
		try {
			
			$this->invoke('addCoverType', array('obj' => $obj->toSoapEncoding()));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Delete a specific cover type object
	 *
	 * @param int $type_id | The ID of the cover type object to delete
	 */
	public function deleteCoverType($type_id) {
		try {
			
			$this->invoke('deleteCoverType', array('type_id' => $type_id));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get all coverTypes used by this specific movie, returns array of coverType objects
	 *
	 * @param int $mediatype_id | The mediaType ID of the movie
	 * @return array
	 */
	public function getAllCoverTypesForVcd($mediatype_id) {
		try {
			
			$data = $this->invoke('getAllCoverTypesForVcd', array('mediatype_id' => $mediatype_id));
			$results = array();
			foreach ($data as $item) {
				array_push($results, VCDSoapTools::GetCoverTypeObj($item));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get coverType object by ID
	 *
	 * @param int $covertype_id | The ID of the coverType object
	 * @return cdcoverTypeObj
	 */
	public function getCoverTypeById($covertype_id) {
		try {
			
			return VCDSoapTools::GetCoverTypeObj($this->invoke('getCoverTypeById', array('covertype_id' => $covertype_id)));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get coverType object by Name
	 *
	 * @param string $name | The name of the coverType object
	 * @return cdcoverTypeObj
	 */
	public function getCoverTypeByName($name) {
		try {
			
			return VCDSoapTools::GetCoverTypeObj($this->invoke('getCoverTypeByName', array('name' => $name)));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Update specific coverType object
	 *
	 * @param cdcoverTypeObj $obj
	 */
	public function updateCoverType(cdcoverTypeObj $obj) {
		try {
			
			$this->invoke('updateCoverType', array('obj' => $obj->toSoapEncoding()));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * The a specific cover by ID
	 *
	 * @param int $cover_id | 
	 * @return cdcoverObj
	 */
	public function getCoverById($cover_id) {
		try {
			
			return VCDSoapTools::GetCoverObj($this->invoke('getCoverById', array('cover_id' => $cover_id)));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Get all Covers stored in VCD-db.  Returns array of cdcoverObjects
	 *
	 * @return array | Array of cdcoverObjects
	 */
	public function getAllCovers() {
		try {
			
			$data = $this->invoke('getAllCovers', array());
			$results = array();
			foreach ($data as $item) {
				array_push($results, VCDSoapTools::GetCoverObj($item));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Get all cover objects that belong to a specified movie, returns array of cdcover objects
	 *
	 * @param int $vcd_id | The ID of the movie that owns the covers
	 * @return array
	 */
	public function getAllCoversForVcd($vcd_id) {
		try {
			
			$data = $this->invoke('getAllCoversForVcd', array('vcd_id' => $vcd_id));
			$results = array();
			foreach ($data as $item) {
				array_push($results, VCDSoapTools::GetCoverObj($item));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Add a new cover to VCD-db
	 *
	 * @param cdcoverObj $obj
	 */
	public function addCover(cdcoverObj $obj) {
		try {
			
			$this->invoke('addCover',array('obj' => $obj->toSoapEncoding()));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Delete a specific cover from VCD-db
	 *
	 * @param int $cover_id
	 */
	public function deleteCover($cover_id) {
		try {
			
			$this->invoke('deleteCover', array('cover_id' => $cover_id));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Update cover object
	 *
	 * @param cdcoverObj $obj
	 */
	public function updateCover(cdcoverObj $obj) {
		try {
			
			$this->invoke('updateCover', array('obj' => $obj->toSoapEncoding()));
			
		} catch (Exception $ex) {
			throw $ex;
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
	public function getAllowedCoversForVcd($mediaTypeObjArr) {
		try {
			
			$data = $this->invoke('getAllowedCoversForVcd', 
				array('mediaTypeObjArr' => VCDSoapTools::EncodeArray($mediaTypeObjArr)));
			$results = array();
			
			foreach ($data as $item) {
				array_push($results, VCDSoapTools::GetCoverTypeObj($item));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Assign a new coverType object to a specified mediaType.
	 *
	 * @param int $mediaTypeID | The media type ID to use for assignment
	 * @param array $coverTypeIDArr | Array of integers representing coverType ID's
	 */
	public function addCoverTypesToMedia($mediaTypeID, $coverTypeIDArr) {
		try {
			
			$this->invoke('addCoverTypesToMedia', array('mediaTypeId' => $mediaTypeID, 'coverTypeIDArr' => $coverTypeIDArr));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get All coverType associated with the specified mediaType ID, returns array of cdcoverType objects.
	 *
	 * @param int $mediaType_id | The ID of the mediaType object
	 * @return array
	 */
	public function getCDcoverTypesOnMediaType($mediaType_id) {
		try {
			
			$data = $this->invoke('getCDcoverTypesOnMediaType', array('mediaType_id' => $mediaType_id));
			$results = array();
			foreach ($data as $item) {
				array_push($results, VCDSoapTools::GetCoverTypeObj($item));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get all thumbnails that the specified user has created in VCD-db, returns array of cdcover objects.
	 * And the cdcover objects are all of type 'Thumbnail'
	 *
	 * @param int $user_id | The Owner ID of the thumbnails
	 * @return array
	 */
	public function getAllThumbnailsForXMLExport($user_id) {
		try {
			
			$data = $this->invoke('getAllThumbnailsForXMLExport', array('user_id' => $user_id));
			$results = array();
			foreach ($data as $item) {
				array_push($results, VCDSoapTools::GetCoverObj($item));
			}
			return $results;
			
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Move all CD-covers in VCD-db from database to harddrive
	 *
	 * @return int | The number of affected covers
	 */
	public function moveCoversToDisk() {
		try {
			
			return $this->invoke('moveCoversToDisk', array());
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Move all CD-covers in VCD-db from harddrive to database
	 *
	 * @return int | The number of affected covers
	 */
	public function moveCoversToDatabase() {
		try {
			
			return $this->invoke('moveCoversToDatabase', array());
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
}

class SoapMovieProxy extends VCDProxy {
	private $classPrefix = 'SoapMovieServices';
	
	public function __construct() {
		
		$this->wsdl = VCDDB_SOAPPROXY.'proxy/movie.php?wsdl';
		$this->proxyUri = VCDDB_SOAPPROXY.'proxy/movie.php';
		$this->namespace = 'urn:http://vcddb.konni.com';
		$this->soapaction = 'urn:MovieServicesAction';
		parent::__construct();
	}
	
	protected function invoke($action, $params) {
		return $this->call($action, $params);
	}
	
	
	
	/**
	 * Get movie by ID
	 *
	 * @param int $movie_id
	 * @return vcdObj
	 */
	public function getVcdByID($movie_id) {
		try {
			
			return VCDSoapTools::GetVcdObj($this->invoke('getVcdByID', array('movie_id' => $movie_id)));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Create a new movie object, returns the ID of the newly created movie object.
	 *
	 * @param vcdObj $obj
	 * @return int
	 */
	public function addVcd(vcdObj $obj) {
		try {
			
			return $this->invoke('addVcd', array('obj' => $obj->toSoapEncoding()));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Update movie
	 *
	 * @param vcdObj $obj
	 */
	public function updateVcd(vcdObj $obj) {
		try {
			
			$this->invoke('updateVcd', array('obj' => $obj->toSoapEncoding()));
			
		} catch (Exception $ex) {
			throw $ex;
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
	public function updateVcdInstance($vcd_id, $new_mediaid, $old_mediaid, $new_numcds, $oldnumcds) {
		try {
			
			$this->invoke('updateVcdInstance', array('vcd_id' => $vcd_id, 'new_mediaid' => $new_mediaid,
				'old_mediaid' => $old_mediaid, 'new_numcds' => $new_numcds, 'oldnumcds' => $oldnumcds));
			
		} catch (Exception $ex) {
			throw $ex;
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
	public function deleteVcdFromUser($vcd_id, $media_id, $mode, $user_id = -1) {
		try {
			
			return $this->invoke('deleteVcdFromUser',array('vcd_id' => $vcd_id, 'media_id' => $media_id,
				'mode' => $mode, 'user_id' => $user_id));
			
		} catch (Exception $ex) {
			throw $ex;
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
	public function getVcdByCategory($category_id, $start=0, $end=0, $user_id = -1) {
		try {
			
			$data = $this->invoke('getVcdByCategory',array('category_id' => $category_id, 'start' => $start,
				'end' => $end, 'user_id' => $user_id));
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetVcdObj($obj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
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
	public function getVcdByCategoryFiltered($category_id, $start=0, $end=0, $user_id) {
		try {
			
			$data = $this->invoke('getVcdByCategoryFiltered',array('category_id' => $category_id, 'start' => $start,
				'end' => $end, 'user_id' => $user_id));
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetVcdObj($obj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
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
	public function getAllVcdByUserId($user_id, $simple = true) {
		try {
			
			$data = $this->invoke('getAllVcdByUserId',array('user_id' => $user_id, 'simple' => $simple));
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetVcdObj($obj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
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
	public function getLatestVcdsByUserID($user_id, $count, $simple = true) {
		try {
			
			$data = $this->invoke('getLatestVcdsByUserID',array('user_id' => $user_id, 
				'count' => $count, 'simple' => $simple));
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetVcdObj($obj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get all vcd objects for lists creations.  Returns array of vcd objects.
	 *
	 * @param int $excluded_userid | The ID of the owner to exclude
	 * @return array
	 */
	public function getAllVcdForList($excluded_userid) {
		try {
			
			$data = $this->invoke('getAllVcdForList',array('excluded_userid' => $excluded_userid));
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetVcdObj($obj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get specific movies by ID's.  Returns array of vcd objects.
	 *
	 * @param array $arrIDs | array of integers representing movie ID's
	 * @return array
	 */
	public function getVcdForListByIds($arrIDs) {
		try {
			
			$data = $this->invoke('getVcdForListByIds',array('arrIDs' => $arrIDs));
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetVcdObj($obj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
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
	public function addVcdToUser($user_id, $vcd_id, $mediatype, $cds) {
		try {
			
			$this->invoke('addVcdToUser', array('user_id' => $user_id, 'vcd_id' => $vcd_id,
				'mediatype' => $mediatype, 'cds' => $cds));
			
		} catch (Exception $ex) {
			throw $ex;
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
	public function getCategoryCount($category_id, $user_id = -1) {
		try {
			
			if (!is_numeric($user_id)) {
				$user_id = -1;
			}
			
			return $this->invoke('getCategoryCount',array('category_id' => $category_id, 'user_id' => $user_id));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get the number of movies for selected category after user filter has been applied.
	 *
	 * @param int $category_id | The ID of the mediatype to filter by
	 * @param int $user_id | The Owner ID
	 * @return int
	 */
	public function getCategoryCountFiltered($category_id, $user_id) {
		try {
			
			return $this->invoke('getCategoryCountFiltered',array('category_id' => $category_id, 'user_id' => $user_id));
			
		} catch (Exception $ex) {
			throw $ex;
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
	public function getTopTenList($category_id = 0, $arrFilter = null) {
		try {
			
			$data = $this->invoke('getTopTenList',array('category_id' => $category_id, 'arrFilter' => $arrFilter));
						
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetVcdObj($obj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
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
	public function search($keyword, $method) {
		try {
			
			$data = $this->invoke('search',array('keyword' => $keyword, 'method' => $method));
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetVcdObj($obj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
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
	public function advancedSearch($title = null, $category = null, $year = null, $mediatype = null,
								   $owner = null, $imdbgrade = null) {
		try {
			
			
			$data = $this->invoke('advancedSearch',array('title' => $title, 'category' => $category,
				'year' => $year, 'mediatype' => $mediatype, 'owner' => $owner, 'imdbgrade' => $imdbgrade));
			
			foreach ($data as &$item) {
				$item = (array)$item;
			}
			return $data;
			
		} catch (Exception $ex) {
			throw $ex;
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
	public function crossJoin($user_id, $media_id, $category_id, $method) {
		try {
			
			$data = $this->invoke('crossJoin',array('user_id' => $user_id, 'media_id' => $media_id,
				'category_id' => $category_id, 'method' => $method));
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetVcdObj($obj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
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
	public function getPrintViewList($user_id, $list_type) {
		try {
			
			$data = $this->invoke('getPrintViewList',array('user_id' => $user_id, 'list_type' => $list_type));
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetVcdObj($obj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Find a random vcd object, params $category_id and $use_seenlist are optional.
	 *
	 * @param int $category_id | The movie category ID to limit results by
	 * @param bool $use_seenlist | Filter search result only to unseen movies.
	 * @return vcdObj
	 */
	public function getRandomMovie($category_id, $use_seenlist = false) {
		try {
			
			return VCDSoapTools::GetVcdObj($this->invoke('getRandomMovie',array('category_id' => $category_id,
				'use_seenlist' => $use_seenlist)));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get similiar movies as an array of vcd objects.
	 * Movies in same category as the one specified in the $vcd_id param will be returned.
	 *
	 * @param int $vcd_id | The ID of the vcd object to search by
	 * @return array
	 */
	public function getSimilarMovies($vcd_id) {
		try {
			
			$data = $this->invoke('getSimilarMovies',array('vcd_id' => $vcd_id));
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetVcdObj($obj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get the number of movies that user owns
	 *
	 * @param itn $user_id | The Owner ID to seek by
	 * @return int
	 */
	public function getMovieCount($user_id) {
		try {
			
			return $this->invoke('getMovieCount',array('user_id' => $user_id));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get vcd objects that are marked adult, and that are in a specific adult category.
	 * Returns array of vcd objects.
	 *
	 * @param int $category_id | The ID of the porncategory object to filter by
	 * @return array
	 */
	public function getVcdByAdultCategory($category_id) {
		try {
			
			$data = $this->invoke('getVcdByAdultCategory',array('category_id' => $category_id));
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetVcdObj($obj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get all vcd objects that are linked to a specific adult studio.
	 * Returns array of vcd objects.
	 *
	 * @param int $studio_id | The ID of the studio object to filter by
	 * @return array
	 */
	public function getVcdByAdultStudio($studio_id) {
		try {
			
			$data = $this->invoke('getVcdByAdultStudio',array('studio_id' => $studio_id));
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetVcdObj($obj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Set the screenshot flag on a specified movie object.
	 *
	 * @param int $vcd_id | The ID of the vcd object
	 */
	public function markVcdWithScreenshots($vcd_id) {
		try {
			
			$this->invoke('markVcdWithScreenshots',array('vcd_id' => $vcd_id));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Check if the movie has some screenshots.  Returns true if screenshots exist, otherwise false.
	 *
	 * @param int $vcd_id | The ID of the movie object
	 * @return bool
	 */
	public function getScreenshots($vcd_id) {
		try {
			
			return $this->invoke('getScreenshots', array('vcd_id' => $vcd_id));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Get list of duplicate movies in the database
	 *
	 * @return array | Returns array of duplicate movie data
	 */
	public function getDuplicationList() {
		try {
			
			return $this->invoke('getDuplicationList', array());
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
}

class SoapUserProxy extends VCDProxy {
	private $classPrefix = 'SoapUserServices';
	
	public function __construct() {
		
		$this->wsdl = VCDDB_SOAPPROXY.'proxy/user.php?wsdl';
		$this->proxyUri = VCDDB_SOAPPROXY.'proxy/user.php';
		$this->namespace = 'urn:http://vcddb.konni.com';
		$this->soapaction = 'urn:UserServicesAction';
		parent::__construct();
	}
	
	protected function invoke($action, $params) {
		return $this->call($action, $params);
	}
	
	
	
	/**
	 * Get user By ID
	 *
	 * @param int $user_id
	 * @return userObj
	 */
	public function getUserByID($user_id) {
		try {
			
			return VCDSoapTools::GetUserObj($this->invoke('getUserByID', array('user_id' => $user_id)));
					
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Update User, return true on success otherwise false
	 *
	 * @param userObj $userObj
	 * @return bool
	 */
	public function updateUser(userObj $userObj) {
		try {
			
			return $this->invoke('updateUser', array('userObj' => $userObj->toSoapEncoding()));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Delete User
	 *
	 * @param int $user_id | The userID
	 * @param bool $erase_data | Delete all user data including movie list
	 */
	public function deleteUser($user_id, $erase_data = false) {
		try {
			
			$this->invoke('deleteUser', array('user_id' => $user_id, 'erase_data' => $erase_data));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Add new User to VCD-db, returns true on success otherwise false
	 *
	 * @param userObj $userObj | The userObj to create
	 * @return bool
	 */
	
	public function addUser(userObj $userObj) {
		try {
			
			return $this->invoke('addUser', array('userObj' => $userObj->toSoapEncoding()));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get all Users in VCD-db, returns array of User objects
	 *
	 * @return array
	 */
	public function getAllUsers() {
		try {
			
			$data = $this->invoke('getAllUsers', array());
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetUserObj($obj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get all users that have added a movie to VCD-db, return array of User objects
	 *
	 * @return array
	 */
	public function getActiveUsers() {
		try {
			
			$data = $this->invoke('getActiveUsers', array());
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetUserObj($obj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get User by specific username
	 *
	 * @param string $user_name | The username
	 * @return userObj
	 */
	public function getUserByUsername($user_name) {
		try {
			
			return VCDSoapTools::GetUserObj($this->invoke('getUserByUsername', array('user_name' => $user_name)));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Store the users session after user has requested to be remembered during login.
	 *
	 * @param string $session_id | The hashed session id
	 * @param int $user_id | The User ID
	 */
	public function addSession($session_id, $user_id) {
		try {
			
			$this->invoke('addSession', array('session_id' => $session_id, 'user_id' => $user_id));
			
		} catch (Exception $ex) {
			throw $ex;
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
	public function isValidSession($session_id, $session_time, $user_id) {
		try {
			
			return $this->invoke('isValidSession', array('session_id' => $session_id, 
				'session_time' => $session_time, 'user_id' => $user_id));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
			
	/**
	 * Get all user roles in VCD-db.  Returns array of UserRole objects.
	 *
	 * @return array
	 */
	public function getAllUserRoles() {
		try {
			
			$data = $this->invoke('getAllUserRoles', array());
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetUserRole($obj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get all users in specific role.  Returns array of Users objects.
	 *
	 * @param int $role_id | The ID of the userrole to seek by
	 * @return array
	 */
	public function getAllUsersInRole($role_id) {
		try {
			
			
			$data = $this->invoke('getAllUsersInRole', array('role_id' => $role_id));
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetUserObj($obj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	

	/**
	 * Delete a specific user role, error is thrown is some user/s is still using this role.
	 * Returns true if actions secceds otherwise false. 
	 *
	 * @param int $role_id | The userrole ID to delete
	 * @return bool
	 */
	public function deleteUserRole($role_id) {
		try {
			
			return $this->invoke('deleteUserRole', array('role_id' => $role_id));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get the default role assigned to new users that are created/registered in VCD-db.
	 *
	 * @return userRoleObj
	 */
	public function getDefaultRole() {
		try {
			
			return VCDSoapTools::GetUserRole($this->invoke('getDefaultRole', array()));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Assign a specific role as a default role to new users.
	 *
	 * @param int $role_id | The ID of the UserRole to use.
	 */
	public function setDefaultRole($role_id) {
		try {
			
			$this->invoke('setDefaultRole', array('role_id' => $role_id));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Get all user properties in VCD-db.  Returns array of userProperties objects.
	 *
	 * @return array
	 */
	public function getAllProperties() {
		try {
			
			$data = $this->invoke('getAllProperties', array());
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetUserPropertyObj($obj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get specific userProperties object by ID.
	 *
	 * @param int $property_id | The ID of the userProperty
	 * @return userPropertiesObj
	 */
	public function getPropertyById($property_id) {
		try {
			
			return VCDSoapTools::GetUserPropertyObj($this->invoke('getPropertyById', array('property_id' => $property_id)));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get specific userProperty object by the property key.
	 *
	 * @param string $property_key | The property key
	 * @return userPropertiesObj
	 */
	public function getPropertyByKey($property_key) {
		try {
			
			return VCDSoapTools::GetUserPropertyObj($this->invoke('getPropertyByKey', array('property_key' => $property_key)));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Add new userProperty object to VCD-db.
	 *
	 * @param userPropertiesObj $obj
	 */
	public function addProperty(userPropertiesObj $obj) {
		try {
			
			$this->invoke('addProperty',array('obj' => $obj->toSoapEncoding()));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Delete a specific user Property object.
	 *
	 * @param int $property_id | The ID of the userProperty object to delete.
	 */
	public function deleteProperty($property_id) {
		try {
			
			$this->invoke('deleteProperty',array('property_id' => $property_id));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Update specific userProperties object.
	 *
	 * @param userPropertiesObj $obj
	 */
	public function updateProperty(userPropertiesObj $obj) {
		try {
			
			$this->invoke('updateProperty',array('obj' => $obj->toSoapEncoding()));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
			
	/**
	 * Add new userProperty to a specific user.
	 *
	 * @param int $property_id | The ID of the userProperties object
	 * @param int $user_id | The ID of the user to add the property to
	 */
	public function addPropertyToUser($property_id, $user_id) {
		try {
			
			$this->invoke('addPropertyToUser',array('property_id' => $property_id,
				'user_id' => $user_id));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Remove specific property from the selected User.
	 *
	 * @param int $property_id | The ID of the userProperties object
	 * @param int $user_id | The ID of the user to remove the property from
	 */
	public function deletePropertyOnUser($property_id, $user_id) {
		try {
			
			$this->invoke('', array('property_id' => $property_id, 'user_id' => $user_id));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get all users that are associated to a a specific userProperty, returns array of User objects.
	 *
	 * @param int $property_id | The ID of the userProperties object
	 * @return array
	 */
	public function getAllUsersWithProperty($property_id) {
		try {
			
			$data = $this->invoke('getAllUsersWithProperty', array('property_id' => $property_id));
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetUserObj($obj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get the users with most movie count in database.
	 * Returns array of User objects sorted by highest movie count.
	 *
	 * @return array
	 */
	public function getUserTopList() {
		try {
			
			$data = $this->invoke('getUserTopList', array());
			$results = array();
			foreach ($data as $item) {
				list($username, $count) = explode('|', $item);
				$results[] = array('username' => $username, 'count' => $count);
			}
			return $results;
						
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
}

class SoapAuthenticationProxy extends VCDProxy  {
	
	private $classPrefix = 'SoapAuthenticationServices';
	
	public function __construct() {
		
		$this->wsdl = VCDDB_SOAPPROXY.'proxy/authentication.php?wsdl';
		$this->proxyUri = VCDDB_SOAPPROXY.'proxy/authentication.php';
		$this->namespace = 'urn:http://vcddb.konni.com';
		$this->soapaction = 'urn:AuthenticationServicesAction';
		parent::__construct();
	}
	
	protected function invoke($action, $params) {
		return $this->call($action, $params);
	}
	
	public function authenticate($username, $password) {
		try {
			
			return $this->invoke('authenticate', array('username' => $username, 'password' => $password));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
}

class SoapFilesProxy extends VCDProxy {
	
	private $classPrefix = 'SoapFilesServices';
	
	public function __construct() {
		$this->wsdl = VCDDB_SOAPPROXY.'proxy/files.php?wsdl';
		$this->proxyUri = VCDDB_SOAPPROXY.'proxy/files.php';
		$this->namespace = 'urn:http://vcddb.konni.com';
		$this->soapaction = 'urn:FilesServicesAction';
		parent::__construct();
	}
	
	protected function invoke($action, $params) {
		return $this->call($action, $params);
	}
	
	public function getCover($cover_id) {
		try {
			
			return $this->invoke('getCover',array('cover_id' => $cover_id));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	public function getScreenshot($movie_id, $index) {
		try {
			
			return $this->invoke('getScreenshot',array('movie_id' => $movie_id, 'index' => $index));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
}

class SoapPornstarProxy extends VCDProxy  {
	
	private $classPrefix = 'SoapPornstarServices';
	
	public function __construct() {
		
		$this->wsdl = VCDDB_SOAPPROXY.'proxy/pornstar.php?wsdl';
		$this->proxyUri = VCDDB_SOAPPROXY.'proxy/pornstar.php';
		$this->namespace = 'urn:http://vcddb.konni.com';
		$this->soapaction = 'urn:PornstarServicesAction';
		parent::__construct();
	}
	
	protected function invoke($action, $params) {
		return $this->call($action, $params);
	}
	
	public function getPornstarByID($pornstar_id) {
		try {
			
			$data = $this->invoke('getPornstarByID', array('pornstar_id' => $pornstar_id));
			$obj = VCDSoapTools::GetPornstarObj($data);
			return $obj;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	public function getPornstarByName($name) {
		try {
			
			$data = $this->invoke('getPornstarByName', array('name' => $name));
			$obj = VCDSoapTools::GetPornstarObj($data);
			return $obj;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	public function getAllPornstars() {
		try {
			
			// Since the payload for all the pornstars is most likely to timeout 
			// we have to divide the requests ..
			$list = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			$results = array();
			foreach ($list as $char) {
				$stars =  $this->getPornstarsByLetter($char, false);
				$results = array_merge($stars, $results);
			}
			
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	public function getPornstarsByLetter($letter, $active_only) {
		try {

			$data = $this->invoke('getPornstarsByLetter', array('letter' => $letter, 'active_only' => $active_only));
			$results = array();
			foreach ($data as $pornstar) {
				array_push($results, VCDSoapTools::GetPornstarObj($pornstar));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	public function getPornstarsAlphabet($active_only) {
		try {
						
			return $this->invoke('getPornstarsAlphabet', array('active_only' => $active_only));
						
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	
	
	public function getStudioByID($studio_id) {
		try {
			
			$data = $this->invoke('getStudioByID', array('studio_id' => $studio_id));
			return VCDSoapTools::GetStudioObj($data);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	public function getStudioByMovieID($vcd_id) {
		try {
			
			$data = $this->invoke('getStudioByMovieID', array('vcd_id' => $vcd_id));
			return VCDSoapTools::GetStudioObj($data);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	public function getStudioByName($studio_name) {
		try {
			
			$data = $this->invoke('getStudioByName', array('studio_name' => $studio_name));
			return VCDSoapTools::GetStudioObj($data);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	public function addMovieToStudio($studio_id, $vcd_id) {	
		try {
			$this->invoke('addMovieToStudio', array('studio_id' => $studio_id, 'vcd_id' => $vcd_id));
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	public function addStudio(studioObj $obj) {
		try {
			$this->invoke('addStudio', array('obj' => $obj->toSoapEncoding()));
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	public function deleteStudio($studio_id) {
		try {
			$this->invoke('deleteStudio', array('studio_id' => $studio_id));
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	public function deletePornstar($pornstar_id) {
		try {
			return $this->invoke('deletePornstar', array('pornstar_id' => $pornstar_id));
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	public function getAllStudios() {
		try {
			
			$data = $this->invoke('getAllStudios', array());
			$results = array();
			foreach ($data as $studioObj) {
				array_push($results, VCDSoapTools::GetStudioObj($studioObj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	public function getStudiosInUse() {
		try {
			
			$data = $this->invoke('getStudiosInUse', array());
			$results = array();
			foreach ($data as $studioObj) {
				array_push($results, VCDSoapTools::GetStudioObj($studioObj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	
	public function getPornstarsByMovieID($movie_id) {
		try {
		
			$data = $this->invoke('getPornstarsByMovieID', array('movie_id' => $movie_id));
			$results = array();
			foreach ($data as $pornstar) {
				array_push($results, VCDSoapTools::GetPornstarObj($pornstar));
			}
			
			return $results;
				
		} catch (Exception $ex) {
			throw $ex;
		}
		
	}
	
	
	public function getSubCategoriesByMovieID($vcd_id)  {
		try {
			
			$data = $this->invoke('getSubCategoriesByMovieID', array('vcd_id' => $vcd_id));
			$results = array();
			
			foreach ($data as $catObj) {
				array_push($results, VCDSoapTools::GetPornCategoryObj($catObj));
			}
			
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	public function getSubCategoryByID($category_id)  {
		try {
			
			$data = $this->invoke('getSubCategoryByID', array('category_id' => $category_id));
			return VCDSoapTools::GetPornCategoryObj($data);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	public function getValidCategories($arrCategoryNames)  {
		try {
			
			$data = $this->invoke('getValidCategories', array('arrCategoryNames' => $arrCategoryNames));
			
			$arr = array();
			foreach ($data as $catObj) {
				array_push($arr,VCDSoapTools::GetPornCategoryObj($catObj));
			}
			return $arr;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	
	public function getSubCategoriesInUse() {
		try {
			
			$data = $this->invoke('getSubCategoriesInUse', array());
			$results = array();
			
			foreach ($data as $catObj) {
				array_push($results, VCDSoapTools::GetPornCategoryObj($catObj));
			}
			
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	public function addPornstar(pornstarObj $obj) {
		try {
			return VCDSoapTools::GetPornstarObj(
				$this->invoke('addPornstar', array('obj' => $obj->toSoapEncoding())));
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	public function addAdultCategory(porncategoryObj $obj) {
		try {
			$this->invoke('addAdultCategory', array('obj' => $obj->toSoapEncoding()));
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	public function deleteAdultCategory($category_id) {
		try {
			$this->invoke('deleteAdultCategory', array('category_id' => $category_id));
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	public function getSubCategories() {
		try {
			
			$data = $this->invoke('getSubCategories', array());
			$results = array();
			
			foreach ($data as $catObj) {
				array_push($results, VCDSoapTools::GetPornCategoryObj($catObj));
			}
			
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	public function updatePornstar(pornstarObj $obj) {
		try {
			$this->invoke('updatePornstar', array('obj' => $obj->toSoapEncoding()));
		} catch (Exception $ex) {
			throw $ex;
		}
	}
}

class SoapSettingsProxy extends VCDProxy {
	
	private $classPrefix = 'SoapSettingsServices';
	
	public function __construct() {
		
		$this->wsdl = VCDDB_SOAPPROXY.'proxy/settings.php?wsdl';
		$this->proxyUri = VCDDB_SOAPPROXY.'proxy/settings.php';
		$this->namespace = 'urn:http://vcddb.konni.com';
		$this->soapaction = 'urn:SettingsServicesAction';
		parent::__construct();
	}
	
	protected function invoke($action, $params) {
		return $this->call($action, $params);
	}
	
	
	/**
	 * Get all Settings objects in VCD-db.  Returns array of Settings objects.
	 *
	 * @return array
	 */
	public function getAllSettings() {
		try {
			
			$data = $this->invoke('getAllSettings', array());
			$results = array();
			foreach ($data as $settingsObj) {
				array_push($results, VCDSoapTools::GetSettingsObj($settingsObj));
			}
						
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get specific settings string by key.
	 *
	 * @param string $key | The settings key that identifies the object
	 * @return string
	 */
	public function getSettingsByKey($key) {
		try {

			return $this->invoke('getSettingsByKey', array('key' => $key));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get specific settings object by ID.
	 *
	 * @param int $settings_id | The ID of the settings object
	 * @return settingsObj
	 */
	public function getSettingsByID($settings_id) {
		try {
			
			$data = $this->invoke('getSettingsByID', array('settings_id' => $settings_id));
			return VCDSoapTools::GetSettingsObj($data);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Add new settings object to VCD-db.  Param settings can either be settingsObj or an
	 * array of settings objects.
	 *
	 * @param mixed $settings
	 */
	public function addSettings($settings) {
		try {
			
			if ($settings instanceof settingsObj ) {
				$settings = array($settings);
			}
			$this->invoke('addSettings', array('settings' => VCDSoapTools::EncodeArray($settings)));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Update specific settings object.
	 *
	 * @param settingsObj $obj
	 */
	public function updateSettings(settingsObj $obj) {
		try {
			
			$this->invoke('updateSettings', array('obj' => $obj->toSoapEncoding()));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Delete specific settings object.  Returns true on success otherwise false.
	 *
	 * @param int $settings_id | The ID of the settings object to delete
	 * @return bool
	 */
	public function deleteSettings($settings_id) {
		try {

			return $this->invoke('deleteSettings', array('settings_id' => $settings_id));		
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Get all sourcesite objects in VCD-db.  Returns array of sourceSite objects.
	 *
	 * @return array
	 */
	public function getSourceSites() {
		try {

			$data = $this->invoke('getSourceSites', array());
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetSourceSiteObj($obj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get specific sourceSite object by ID.
	 *
	 * @param int $source_id | The ID of the sourceSite object to get
	 * @return sourceSiteObj
	 */
	public function getSourceSiteByID($source_id) {
		try {
			
			return VCDSoapTools::GetSourceSiteObj(
				$this->invoke('getSourceSiteByID',array('source_id' => $source_id)));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get specific sourceSite object by the sourcesite Alias.
	 *
	 * @param string $alias | The alias of the sourcesite.  EG "imdb"
	 * @return sourceSiteObj
	 */
	public function getSourceSiteByAlias($alias) {
		try {
			
			return VCDSoapTools::GetSourceSiteObj(
				$this->invoke('getSourceSiteByAlias',array('alias' => $alias)));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Add new sourceSite object to VCD-db.
	 *
	 * @param sourceSiteObj $obj
	 */
	public function addSourceSite(sourceSiteObj $obj) {
		try {

			$this->invoke('addSourceSite', array('obj' => $obj->toSoapEncoding()));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Delete specific sourceSite object from VCD-db.
	 *
	 * @param int $source_id | The ID of the sourceSite object to delete.
	 */
	public function deleteSourceSite($source_id) {
		try {
			
			$this->invoke('deleteSourceSite',array('source_id' => $source_id));
					
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Delete NFO file
	 *
	 * @param int $metadata_id | The Id of the metadata containing the NFO entry
	 */
	public function deleteNFO($metadata_id) {
		try {
			
			$this->invoke('deleteNFO', array('metadata_id' => $metadata_id));
			
		} catch (Exception $ex) {
			throw $ex;	
		}
	}
	
	
	/**
	 * Update a specific sourceSite object.
	 *
	 * @param sourceSiteObj $sourceSiteObj
	 */
	public function updateSourceSite(sourceSiteObj $sourceSiteObj) {
		try {
			
			$this->invoke('updateSourceSite', array('sourceSiteObj' => $sourceSiteObj->toSoapEncoding()));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Get all mediaType objects in VCD-db.  Returns array of mediaType objects.
	 *
	 * @return array
	 */
	public function getAllMediatypes() {
		try {
			
			$data = $this->invoke('getAllMediatypes', array());
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetMediaTypeObj($obj));
			}
			
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get specific mediaType object by ID.
	 *
	 * @param int $media_id | The ID of the specific mediaType object
	 * @return mediaTypeObj
	 */
	public function getMediaTypeByID($media_id) {
		try {
			
			$data = $this->invoke('getMediaTypeByID',array('media_id' => $media_id));
			return VCDSoapTools::GetMediaTypeObj($data);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Add new mediaType object to VCD-db.
	 *
	 * @param mediaTypeObj $mediaTypeObj
	 */
	public function addMediaType(mediaTypeObj $mediaTypeObj) {
		try {
			
			$this->invoke('addMediaType', array('mediaTypeObj' => $mediaTypeObj->toSoapEncoding()));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Delete a mediaType object from VCD-db.  Returns true on success, otherwise false.
	 *
	 * @param int $mediatype_id | The ID of the mediaType object to delete.
	 * @return bool
	 */
	public function deleteMediaType($mediatype_id) {
		try {
			
			return $this->invoke('deleteMediaType', array('mediatype_id' => $mediatype_id));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Update a specific mediaType object in VCD-db.
	 *
	 * @param mediaTypeObj $mediaTypeObj
	 */
	public function updateMediaType(mediaTypeObj $mediaTypeObj) {
		try {

			$this->invoke('updateMediaType', array('mediaTypeObj' => $mediaTypeObj->toSoapEncoding()));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get all mediatype objects that are available on the specified movie object.
	 * Returns array of mediaType objects.
	 *
	 * @param int $vcd_id | The ID of the movie to get mediatype objects by
	 * @return array
	 */
	public function getMediaTypesOnCD($vcd_id) {
		try {
			
			$data = $this->invoke('getMediaTypesOnCD', array('vcd_id' => $vcd_id));
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetMediaTypeObj($obj));
			}
			
			return $results;
			
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get collection of mediatype objects that user uses in all his movies.
	 * Return array of stats data.
	 *
	 * @param int $user_id | The User ID to seek mediaType objects by.
	 * @return array
	 */
	public function getMediaTypesInUseByUserID($user_id) {
		try {
			
			$data = $this->invoke('getMediaTypesInUseByUserID', array('user_id' => $user_id));
			$results = array();
			foreach ($data as $item) {
				array_push($results, explode("|", $item));
			}
			
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Get all media types that are being used by the system
	 *
	 * @return array | Array of mediatype objects
	 */
	public function getMediaTypesInUse() {
		try {
			
			$data = $this->invoke('getMediaTypesInUse', array());
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetMediaTypeObj($obj));
			}
			
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get the count of media's in the specified movie category
	 *
	 * @param int $category_id | The category ID to use
	 * @return array
	 */
	public function getMediaCountByCategory($category_id) {
		try {
			
			return $this->invoke('getMediaCountByCategory', array('category_id' => $category_id));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Get the movie count of all movies belonging to a specific category.
	 *
	 * @param int $user_id | The User ID of the user to get results from
	 * @param int $category_id | The ID of the category to filter by
	 * @return int
	 */
	public function getMediaCountByCategoryAndUserID($user_id, $category_id) {
		try {
			
			$data = $this->invoke('getMediaCountByCategoryAndUserID', array('user_id' => $user_id, 'category_id' => $category_id));
			$results = array();
			foreach ($data as $item) {
				array_push($results, explode("|", $item));
			}
			
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get a specific mediaType object by it's name.
	 *
	 * @param string $name | The name of the mediaType object
	 * @return mediaTypeObj
	 */
	public function getMediaTypeByName($name) {
		try {
			
			$data = $this->invoke('getMediaTypeByName', array('name' => $name));
			return VCDSoapTools::GetMediaTypeObj($data);
			
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Get all movieCategory objects available in VCD-db.
	 * Returns array of movieCategory objects.
	 *
	 * @return array
	 */
	public function getAllMovieCategories() {
		try {

			$data = $this->invoke('getAllMovieCategories', array());
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetMovieCategoryObj($obj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get collection of all movieCategory objects that are in use in VCD-db.
	 * Returns array of movieCategory
	 *
	 * @return array
	 */
	public function getMovieCategoriesInUse() {
		try {
			
			$data = $this->invoke('getMovieCategoriesInUse', array());
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetMovieCategoryObj($obj));
			}
			return $results;
			
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get a specific movieCategory object by category ID.
	 *
	 * @param int $category_id | The ID of the movieCategory object
	 * @return movieCategoryObj
	 */
	public function getMovieCategoryByID($category_id) {
		try {
			
			$data = $this->invoke('getMovieCategoryByID', array('category_id' => $category_id));
			return VCDSoapTools::GetMovieCategoryObj($data);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Add new movieCategory object to VCD-db.
	 *
	 * @param movieCategoryObj $obj
	 */
	public function addMovieCategory(movieCategoryObj $obj) {
		try {
			
			$this->invoke('addMovieCategory', array('obj' => $obj->toSoapEncoding()));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Delete a movieCategory object from VCD-db.
	 *
	 * @param int $category_id | The ID of the movieCategory object to delete
	 */
	public function deleteMovieCategory($category_id) {
		try {

			$this->invoke('deleteMovieCategory', array('category_id' => $category_id));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Update a movieCategory object in VCD-db.
	 *
	 * @param movieCategoryObj $obj
	 */
	public function updateMovieCategory(movieCategoryObj $obj) {
		try {

			$this->invoke('updateMovieCategory', array('obj' => $obj->toSoapEncoding()));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get the ID of a specific movieCategory object by using its name as identifier.
	 *
	 * @param string $name | The name of the moviecategory object to seek by
	 * @param bool $localized | Is the category name translated ?
	 * @return int | Returns the ID of the movieCategory object
	 */
	public function getCategoryIDByName($name, $localized=false) {
		try {
			
			return $this->invoke('getCategoryIDByName',array('name' => $name, 'localized' => $localized));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Get categoryID on the item by the item ID
	 *
	 * @param int $item_id | The item ID
	 * @return int | The category ID of the item
	 */
	public function getCategoryIDByItemId($item_id) {
		try {
			
			return $this->invoke('getCategoryIDByItemId', array('item_id' => $item_id));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get all movieCategory objects in use by specific User ID.
	 * Returns array of movieCategory objects.
	 *
	 * @param int $user_id | The User ID of the user to filter by
	 * @return array
	 */
	public function getCategoriesInUseByUserID($user_id) {
		try {
			
			$data = $this->invoke('getCategoriesInUseByUserID', array('user_id' => $user_id));
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetMovieCategoryObj($obj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get a specific borrower object by the object ID.
	 *
	 * @param int $borrower_id | The ID of the borrower object to seek by.
	 * @return borrowerObj
	 */
	public function getBorrowerByID($borrower_id) {
		try {
			
			$data = $this->invoke('getBorrowerByID',array('borrower_id' => $borrower_id));
			return VCDSoapTools::GetBorrowerObj($data);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get all the borrower objects created by a specific User.
	 * Returns array of borrower objects.
	 *
	 * @param int $user_id | The User ID of the user that is the owner of the borrower objects
	 * @return array
	 */
	public function getBorrowersByUserID($user_id) {
		try {
			
			$data = $this->invoke('getBorrowersByUserID', array('user_id' => $user_id));
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetBorrowerObj($obj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Add new borrower to VCD-db.
	 *
	 * @param borrowerObj $obj
	 */
	public function addBorrower(borrowerObj $obj) {
		try {
			
			$this->invoke('addBorrower', array('obj' => $obj->toSoapEncoding()));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Update a borrower object.
	 *
	 * @param borrowerObj $obj
	 */
	public function updateBorrower(borrowerObj $obj) {
		try {
			
			$this->invoke('updateBorrower', array('obj' => $obj->toSoapEncoding()));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Delete a specific borrower object
	 *
	 * @param borrowerObj $obj
	 */
	public function deleteBorrower(borrowerObj $obj) {
		try {
			
			$this->invoke('deleteBorrower', array('obj' => $obj->toSoapEncoding()));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Flag specific movies as loaned in the loan system.
	 *
	 * @param int $borrower_id | The ID of the borrower that is getting the CD's
	 * @param array $arrMovieIDs | Array of movie ID's
	 */
	public function loanCDs($borrower_id, $arrMovieIDs) {
		try {

			$this->invoke('loanCDs', array('borrower_id' => $borrower_id, 'arrMovieIDs' => $arrMovieIDs));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Returns a specific movie from a loan, using the ID of the loan entry.
	 *
	 * @param int $loan_id | The ID of the loan entry.
	 */
	public function loanReturn($loan_id) {
		try {
			
			$this->invoke('loanReturn', array('load_id' => $loan_id));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get all the loan objects belonging to a specific VCD-db User.
	 * Returns array of loan objects.
	 *
	 * @param int $user_id | The User ID of the loaner
	 * @param bool $show_returned | Include loans that have been returned
	 * @return array
	 */
	public function getLoans($user_id, $show_returned) {
		try {
			
			$data = $this->invoke('getLoans', array('user_id' => $user_id, 'show_returned' => $show_returned));
			$results = array();
			foreach ($data as $loanObj) {
				array_push($results, VCDSoapTools::GetLoanObj($loanObj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get all loan entries by a specific borrower. Returns array of loan objects.
	 *
	 * @param int $user_id | The owner of the loan
	 * @param int $borrower_id | The borrower ID to seek by
	 * @param bool $show_returned | Show loans that have been returned or not
	 * @return array
	 */
	public function getLoansByBorrowerID($user_id, $borrower_id, $show_returned = false) {
		try {
			
			$data = $this->invoke('getLoansByBorrowerID', array('user_id' => $user_id,
				'borrower_id' => $borrower_id, 'show_returned' => $show_returned));
			$results = array();
			foreach ($data as $loanObj) {
				array_push($results, VCDSoapTools::GetLoanObj($loanObj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
		
	/**
	 * Add new Rss Feed to VCD-db.
	 *
	 * @param rssObj $obj
	 */
	public function addRssfeed(rssObj $obj) {
		try {

			$this->invoke('addRssfeed', array('obj' => $obj->toSoapEncoding()));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get specific Rss feed by the feed ID.
	 *
	 * @param int $feed_id | The ID of the Rss feed.
	 * @return rssObj
	 */
	public function getRssfeed($feed_id) {
		try {
			
			return VCDSoapTools::GetRssObj($this->invoke('getRssfeed', array('feed_id' => $feed_id)));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get All Rss Feeds that belong to specific User.
	 * Returns array of rss objects.
	 *
	 * @param int $user_id | The User ID of the Rss feed owner
	 * @return array
	 */
	public function getRssFeedsByUserId($user_id) {
		try {
			
			$data = $this->invoke('getRssFeedsByUserId', array('user_id', $user_id));
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetRssObj($obj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Delete a specific Rss feed
	 *
	 * @param int $feed_id | The ID of the feed to delete
	 */
	public function delFeed($feed_id) {
		try {
			
			$this->invoke('delFeed', array('feed_id' => $feed_id));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Update a specific Rss feed.
	 *
	 * @param rssObj $obj
	 */
	public function updateRssfeed(rssObj $obj) {
		try {
			
			$this->invoke('updateRssfeed', array('obj' => $obj->toSoapEncoding()));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Add a movie to users wishlist.
	 *
	 * @param int $vcd_id | The ID of the movie to add to the wishlist
	 * @param int $user_id | The User ID of the wishlist owner
	 */
	public function addToWishList($vcd_id, $user_id) {
		try {

			$this->invoke('addToWishList', array('vcd_id' => $vcd_id, 'user_id' => $user_id));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get a wishlist by User ID.  Returns assoc array containg keys [id, title, mine] 
	 *
	 * @param int $user_id | The Owner of the wishlist
	 * @return array
	 */
	public function getWishList($user_id) {
		try {
			
			$data = $this->invoke('getWishList',array('user_id' => $user_id));
			$results = array();
					
			foreach ($data as $item) {
				list($id,$title,$mine) = explode('|',$item);
				$results[] = array('id' => $id, 'title' => $title, 'mine' => $mine);
			}
			
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Check is a specified movie is on users wishlist.
	 *
	 * @param int $vcd_id | The ID of the movie to check
	 * @return bool
	 */
	public function isOnWishList($vcd_id) {
		try {
			
			return $this->invoke('isOnWishList', array('vcd_id' => $vcd_id));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Remove a movie from users wishlist.
	 *
	 * @param int $vcd_id | The ID of the movie to remove
	 * @param int $user_id | The Owner ID of the wishlist
	 */
	public function removeFromWishList($vcd_id, $user_id) {
		try {
			
			$this->invoke('removeFromWishList', array('vcd_id' => $vcd_id, 'user_id' => $user_id));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Check if the users wishlist is public
	 *
	 * @param int $user_id | The owner ID of the wishlist
	 * @return bool
	 */
	public function isPublicWishLists($user_id) {
		try {
			
			return $this->invoke('isPublicWishLists', array('user_id' => $user_id));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Add a new comment.
	 *
	 * @param commentObj $obj
	 */
	public function addComment(commentObj $obj) {
		try {
						
			$this->invoke('addComment', array('obj' => $obj->toSoapEncoding()));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Delete comment.
	 *
	 * @param int $comment_id | The ID of the comment to delete
	 */
	public function deleteComment($comment_id) {
		try {
			
			$this->invoke('deleteComment', array('comment_id' => $comment_id));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get a specific comment by ID.
	 *
	 * @param int $comment_id | The ID of the comment
	 * @return commentObj
	 */
	public function getCommentByID($comment_id) {
		try {
			
			return VCDSoapTools::GetCommentObj($this->invoke('getCommentByID', array('comment_id' => $comment_id)));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get all comments by specific user ID, returns array of comment objects.
	 *
	 * @param int $user_id | The Owner ID of the comments.
	 * @return array
	 */
	public function getAllCommentsByUserID($user_id) {
		try {
			
			$data = $this->invoke('getAllCommentsByUserID', array('user_id' => $user_id));
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetCommentObj($obj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get all comments that have been made on a specific movie.
	 * Returns array of comments objects.
	 *
	 * @param int $vcd_id | The ID of the movie to get comments
	 * @return array
	 */
	public function getAllCommentsByVCD($vcd_id) {
		try {
			
			$data = $this->invoke('getAllCommentsByVCD', array('vcd_id' => $vcd_id));
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetCommentObj($obj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get the statistics object.
	 *
	 * @return statisticsObj
	 */
	public function getStatsObj() {
		try {
			
			$data = $this->invoke('getStatsObj',array());
			return VCDSoapTools::GetStatsObj($data);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
		
	/**
	 * Get statistics by a specified user ID.  Returns array of 3 statistics objects.
	 *
	 * @param int $user_id | The User ID that the statistics belong to
	 * @return array
	 */
	public function getUserStatistics($user_id) {
		try {
			
			$arrValidTypes = array('year','category','media');
			$results = array();
			foreach ($arrValidTypes as $type) {
				$data = $this->invoke('getUserStatistics', array('user_id' => $user_id, 'type' => $type));	
				$itemResults = array();
				foreach ($data as $item) {
					$itemResults[] = explode("|", $item);
				}
				$results[$type] = $itemResults;
			}
			
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Add a new metadatatype object to the database, returns the same object with populated ID.
	 *
	 * @param metadataTypeObj $obj
	 * @return metadataTypeObj
	 */
	public function addMetadataType(metadataTypeObj $obj) { 
		try {
		
			return VCDSoapTools::GetMetadataTypeObj(
				$this->invoke('addMetadataType', array('obj' => $obj->toSoapEncoding())));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
		
	
	/**
	 * Add new medata object to VCD-db.  Param can either be metadata object or an array of metadata objects.
	 *
	 * @param mixed $arrObj
	 */
	public function addMetadata($arrObj) {
		try {
			
			if ($arrObj instanceof metadataObj) {
				$arrObj = array($arrObj);			
			}
			$this->invoke('addMetadata', array('arrObj' => VCDSoapTools::EncodeArray($arrObj)));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Update a specific metadata.
	 *
	 * @param metadataObj $obj
	 */
	public function updateMetadata(metadataObj $obj) {
		try {
			
			$this->invoke('updateMetadata', array('obj' => $obj->toSoapEncoding()));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Delete a user defined metadata type
	 *
	 * @param int $type_id | The Id of the metadata type
	 */
	public function deleteMetaDataType($type_id) {
		try {
			
			$this->invoke('deleteMetaDataType', array('type_id' => $type_id));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Delete a specific metadata.
	 *
	 * @param int $metadata_id | The ID of the metadata to delete
	 */
	public function deleteMetadata($metadata_id) {
		try {
			
			$this->invoke('deleteMetadata',array('metadata_id' => $metadata_id));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get metadata based on param conditions.  Returns array of metadata objects.
	 * The $record_id param is required but $user_id is optional and the $metadata_name param.
	 *
	 * @param int $record_id | The ID of the movie that the metadata belongs to
	 * @param int $user_id | The Owner ID of the metadata entries.
	 * @param string $metadata_name | The name of the metadata to filter by
	 * @param int $mediatype_id | MediaType ID of movieObj.  This forces deeper check.
	 * @return array
	 */
	public function getMetadata($record_id, $user_id, $metadata_name, $mediatype_id = null) {
		try {
			
			$data = $this->invoke('getMetadata', array('record_id' => $record_id, 'user_id' => $user_id,
				 'metadata_name' => $metadata_name, 'mediatype_id' => $mediatype_id));
				 
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetMetadataObj($obj));
			}
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Get metadataObj by ID
	 *
	 * @param int $metadata_id | The ID of the metadata to get
	 * @return metadataObj | The metadata object that is returned
	 */
	public function getMetadataById($metadata_id) {
		try {
			
			$data = $this->invoke('getMetadataById', array('metadata_id' => $metadata_id));
			return VCDSoapTools::GetMetadataObj($data);
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get all record ID's (movie ID's) that exist with association to a specific metadata name and User ID.
	 * Returns array of integers (Record ID's)
	 *
	 * @param int $user_id | The Owner ID of the metadata
	 * @param string $metadata_name | The metadata type name
	 * @return array
	 */
	public function getRecordIDsByMetadata($user_id, $metadata_name) {
		try {
			
			return $this->invoke('getRecordIDsByMetadata', array('user_id' => $user_id, 'metadata_name' => $metadata_name));
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get all known metadatatypes from database. If $user_id is provided, only metadatatypes created by that
	 * user_id will be returned. Function returns array of metadataTypeObjects.
	 *
	 * @param int $user_id | The user_id to filter metadatatypes to, null = no filter
	 * @return array
	 */
	public function getMetadataTypes($user_id) {
		try {
			
			$data = $this->invoke('getMetadataTypes', array('user_id' => $user_id));
			
			
			
			$results = array();
			foreach ($data as $obj) {
				array_push($results, VCDSoapTools::GetMetadataTypeObj($obj));
			}
			
			
			
			return $results;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	
	
}

class VCDSoapTools {
	
	/**
	 * Transform Soap encoded data to object
	 *
	 * @param array $data | The soapencoded pornstar data
	 * @return pornstarObj
	 */
	public static final function GetPornstarObj($data) {
		
		if ($data instanceof stdClass ) {
			$data = (array)$data;
		}
		
		if (!(isset($data['id']) && isset($data['name']))) {
			return null;
		}
		
		$obj = new pornstarObj(
			array($data['id'], 
				$data['name'], 
				$data['homepage'], 
				$data['image'], 
				$data['biography'], 
				$data['movie_count']));
				
		if (is_array($data['movies'])) {
			$movies = array();
			foreach ($data['movies'] as $item) {
				$entry = explode('|', $item);
				$movies[$entry[0]] = $entry[1];
			}
			$obj->setMovies($movies);
		}
		
		return $obj;
	}
		
	/**
	 * Get porncategoryObj from soap data
	 *
	 * @param array $data
	 * @return pornCategoryObj
	 */
	public static final function GetPornCategoryObj($data) {
		if ($data instanceof stdClass ) {
			$data = (array)$data;
		}
		return new porncategoryObj(array($data['id'], $data['name']));
	}
	
	/**
	 * Get studioObj from soap data
	 *
	 * @param array $data
	 * @return studioObj
	 */
	public static final function GetStudioObj($data) {
		if ($data instanceof stdClass ) {
			$data = (array)$data;
		}
		if (isset($data['id']) && isset($data['name'])) {
			return new studioObj(array($data['id'], $data['name']));	
		} else {
			return null;
		}
	}
	
	public static final function GetSettingsObj($data) {
		if ($data instanceof stdClass ) {
			$data = (array)$data;
		}
		return new settingsObj(array($data['id'],$data['key'],
			$data['value'],$data['description'],$data['isProtected'], $data['type']));
	}
	
	public static final function GetMetadataObj($data) {
		if ($data instanceof stdClass ) {
			$data = (array)$data;
		}
		
		$level = 0;
		if (isset($data['metatype_level']) && is_numeric($data['metatype_level'])) {
			$level = (int)$data['metatype_level'];
		}
		
		return new metadataObj(array($data['metadata_id'], $data['record_id'], $data['user_id'],
			$data['metatype_name'], $data['metadata_value'], $data['mediatype_id'], $data['metatype_id'],
			$level, $data['metatype_description']));
	}

	public static final function GetSourceSiteObj($data) {
		if ($data instanceof stdClass ) {
			$data = (array)$data;
		}
		return new sourceSiteObj(array($data['site_id'], $data['site_name'], $data['site_alias'], 
			$data['site_homepage'], $data['site_getCommand'],$data['isFetchable'], $data['site_classname'],
			$data['site_image']));
	}
	
	public static final function GetMovieCategoryObj($data) {
		
		if ($data instanceof stdClass ) {
			$data = (array)$data;
		}
				
		if (!isset($data['category_name'])) {
			return null;
		}
		
		$obj = new movieCategoryObj(array($data['category_id'], $data['category_name']));
		if (isset($data['category_count']) && is_numeric($data['category_count'])) {
			$obj->setCategoryCount($data['category_count']);
		}
		return $obj;
		
	}
	
	public static final function GetUserObj($data) {
		if ($data instanceof stdClass ) {
			$data = (array)$data;
		}
		$obj = new userObj(array($data['user_id'], $data['username'], $data['password'],
			$data['fullname'], $data['email'], $data['role_id'], $data['role_name'],
			$data['isDeleted'], $data['dateCreated']));
		
		$arrUserProps = $data['userPropertiesArr'];
		foreach ($arrUserProps as $userPropObj) {
			$obj->addProperty(self::GetUserPropertyObj($userPropObj));
		}
		
		return $obj;
	}
	
	
	public static final function GetImdbObj($data) {
		if ($data instanceof stdClass ) {
			$data = (array)$data;
		}
		if (!is_array($data) || sizeof($data)==0) {
			return null;
		}
		
		//$cast = ereg_replace(10,13,$data['cast']);
		$cast = $data['cast'];
		
		$obj = new imdbObj(array($data['id'],$data['title'],$data['altTitle'],$data['altTitle'],$data['image'],
			$data['year'],$data['plot'],$data['director'],$cast,$data['rating'],$data['runtime'],
			$data['country'],$data['genre'],));
		$obj->setIMDB($data['id']);

		return $obj;
	}
	
	public static final function GetVcdObj($data) {
		if ($data instanceof stdClass ) {
			$data = (array)$data;
		}
		$obj = new vcdObj(array($data['id'], $data['title'], $data['category_id'], $data['year']));
		
		$imdbObj = self::GetImdbObj($data['imdbObj']);
		if ($imdbObj instanceof imdbObj ) {
			$obj->setIMDB($imdbObj);
		}
		
		
		$movieCatObj = self::GetMovieCategoryObj($data['moviecategoryobj']);
		if ($movieCatObj instanceof movieCategoryObj ) {
			$obj->setMovieCategory($movieCatObj);
		}
		
		$obj->setSourceSite($data['source_id'], $data['external_id']);
		
		
		if (is_array($data['arrMetadata'])) {
			foreach ($data['arrMetadata'] as $metaObj) {
				$obj->addMetaData(self::GetMetadataObj($metaObj));
			}
		}
		
		
		if (is_array($data['ownersObjArr'])) {
			for ($i=0;$i<sizeof($data['ownersObjArr']);$i++) {
				$obj->addInstance(
					self::GetUserObj($data['ownersObjArr'][$i]),
					self::GetMediaTypeObj($data['mediaTypeObjArr'][$i]),
					$data['arrDisc_count'][$i],
					self::EvalDate($data['arrDate_added'][$i])
				);
			}
		} 
		
		if (is_null($obj->getDateAdded()) && is_array($data['arrDate_added']) && sizeof($data['arrDate_added'])==1) {
			$obj->setDateAdded(self::EvalDate($data['arrDate_added'][0]));
		}
		
		
		if (is_array($data['coversObjArr'])) {
			$covers = array();
			foreach ($data['coversObjArr'] as $coverObj) {
				array_push($covers, self::GetCoverObj($coverObj));
			}
			$obj->addCovers($covers);
		}
		
		
		if (is_array($data['mediaTypeObjArr'])) {
			foreach ($data['mediaTypeObjArr'] as $mediaTypeObj) {
				$obj->addMediaType(self::GetMediaTypeObj($mediaTypeObj));
			}
		}
		
		$comments = $data['arrComments'];
		foreach ($comments as $commentObj) {
			$obj->addComment(self::GetCommentObj($commentObj));
		}
		
		if ($obj->isAdult()) {
			$adultcats = $data['arrPorncategories'];
			$pornstars = $data['arrPornstars'];
			foreach ($pornstars as $pobj) {
				$obj->addPornstars(self::GetPornstarObj($pobj));
			}
			foreach ($adultcats as $catObj) {
				$obj->addAdultCategory(self::GetPornCategoryObj($catObj));
			}
		}
		
		
		
		return $obj;
	}
	
	public static final function GetUserRole($data) {
		if ($data instanceof stdClass ) {
			$data = (array)$data;
		}
		return new userRoleObj(array($data['role_id'], $data['role_name'], $data['role_description']));
	}
	
	
	public static final function GetUserPropertyObj($data) {
		if ($data instanceof stdClass ) {
			$data = (array)$data;
		}
		return new userPropertiesObj(array($data['property_id'], $data['property_name'], $data['property_description']));
	}
	
	public static final function GetBorrowerObj($data) {
		if ($data instanceof stdClass ) {
			$data = (array)$data;
		}
		return new borrowerObj(array($data['id'], $data['owner_id'], $data['name'], $data['email']));
	}
	
	public static final function GetRssObj($data) {
		if ($data instanceof stdClass ) {
			$data = (array)$data;
		}
		return new rssObj(array($data['id'], $data['owner_id'], $data['name'],
			$data['url'], $data['isXrated'], $data['isSitefeed']));
	}
	
	public static final function GetMetadataTypeObj($data) {
		if ($data instanceof stdClass ) {
			$data = (array)$data;
		}
		
		return new metadataTypeObj($data['id'], $data['name'], $data['desc'], $data['level']);
	}
	
	public static final function GetCommentObj($data) {
		if ($data instanceof stdClass ) {
			$data = (array)$data;
		}
		return new commentObj(array($data['id'], $data['vcd_id'], $data['owner_id'], self::EvalDate($data['date']),
			$data['comment'], $data['isPrivate'], $data['owner_name']));
	}
	
	public static final function GetCoverObj($data) {
		if ($data instanceof stdClass ) {
			$data = (array)$data;
		}
		return new cdcoverObj(array($data['cover_id'],$data['vcd_id'],$data['filename'],
			$data['filesize'],$data['owner_id'],$data['date_added'],$data['covertype_id'],
			$data['covertypeName'],$data['image_id']));
	}
		
	public static final function GetCoverTypeObj($data) {
		if ($data instanceof stdClass ) {
			$data = (array)$data;
		}
		return new cdcoverTypeObj(array($data['covertype_id'],$data['covertypeName'],
			$data['coverTypeDescription']));
	}
	
	public static final function GetLoanObj($data) {
		if ($data instanceof stdClass ) {
			$data = (array)$data;
		}
		return new loanObj(array($data['loan_id'],$data['cd_id'],
			$data['cd_title'],self::GetBorrowerObj($data['borrowerObj']),
			$data['date_out'],$data['date_in'],));
	}
	
	public static final function GetStatsObj($data) {
		if ($data instanceof stdClass ) {
			$data = (array)$data;
		}
		$obj = new statisticsObj();
		$allCats = array();
		foreach ($data['ArrAllCats'] as $typeObj) {
			array_push($allCats, self::GetMovieCategoryObj($typeObj));
		}
		$obj->setBiggestCats($allCats);
		
		$cats = array();
		foreach ($data['ArrMonthlyCats'] as $typeObj) {
			array_push($cats, self::GetMovieCategoryObj($typeObj));
		}
		$obj->setBiggestMonhtlyCats($cats);
		
		$obj->setMovieCount($data['total_movies']);
		$obj->setMovieMonthlyCount($data['movies_addedmonth']);
		$obj->setMovieWeeklyCount($data['movies_addedweek']);
		$obj->setMovieTodayCount($data['movies_addedtoday']);
		$obj->setCoverCount($data['total_covers'], $data['total_coversthisweek'], $data['total_coversthismonth']);
		
		return $obj;
		
	}
	
	public static final function GetMediaTypeObj($data) {
		if ($data instanceof stdClass ) {
			$data = (array)$data;
		}
		$obj = new mediaTypeObj(array($data['media_type_id'],$data['media_type_name'],
			$data['parent_id'], $data['media_type_description']));
		
			
		$children = $data['children'];
		foreach ($children as $childObj) {
			$obj->addChild(self::GetMediaTypeObj($childObj));
		}
			
		return $obj;
	}
	
	/**
	 * Encode array collection of objects that support the toSoapEncoding function
	 *
	 * @param array $arr | Array of objects
	 * @return array | Array of Soap Encoded objects
	 */
	public static final function EncodeArray($arr) {
		try {
			$data = array();
			foreach ($arr as $obj) { array_push($data, $obj->toSoapEncoding());	}
			return $data;
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	private static final function EvalDate($dateTime) {
		if (strlen($dateTime)==10) {
			return @ADOConnection::UnixDate($dateTime);
		} else {
			return @ADOConnection::UnixTimeStamp($dateTime);
		}
	}
	
}




?>