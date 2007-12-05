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
 * @version $Id: VCDCache.php 1062 2007-07-05 15:10:11Z konni $
 * @since  0.990
  */
?>
<?php


class VCDCache implements ICache  {

	protected static $ttl = 60;
	private static $storage = 'vcdcache';
	private static $engines = array('xcache');
			
	/**
	 * Get an item from the Cache
	 *
	 * @param string $name | The ID that identifies the data being but in cache
	 * @return mixed | The data thar was stored in the cache
	 */
	public static function get($name) {
		try {
			switch (self::$storage) {
				case 'xcache' :
					return VCDCache_xcache::get($name);
					break;
			
				case 'vcdcache' :
					return VCDCache_filecache::get($name);
					break;
					
					
				default:
					throw new VCDException('Selected storage engine is not implemented.');
			}
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Add an item to the Cache pool
	 *
	 * @param string $name | The ID to identidy the data for later retrival
	 * @param mixed $value | The data to store in the cache
	 * @param int $ttl | The lifetime of the data to store in seconds
	 * @return bool | Returns true if item could be stored, otherwise false
	 */
	public static function set($name, $value, $ttl = 60) {
		try {
			switch (self::$storage) {
				case 'xcache' :
					return VCDCache_xcache::set($name, $value, $ttl);
					break;
					
				case 'vcdcache' :
					return VCDCache_filecache::set($name, $value, $ttl);
					break;
			
				default:
					throw new VCDException('Selected storage engine is not implemented.');
			}
		} catch (Exception $ex) {
			throw $ex;
		}
	}
		
	/**
	 * Check if an item exists in the cache.
	 *
	 * @param string $name | The Id of the item to look for
	 * @return bool | Returns true if the item exists otherwise false.
	 */
	public static function exists($name) {
		try {
			switch (self::$storage) {
				case 'xcache' :
					return VCDCache_xcache::exists($name);
					break;
					
				case 'vcdcache' :
					return VCDCache_filecache::exists($name);
					break;
			
				default:
					throw new VCDException('Selected storage engine is not implemented.');
			}
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Remove item from the cache.
	 *
	 * @param string $name | The Id of the entry to remove
	 * @return bool | Returns true if the item was found and removed, otherwise false.
	 */
	public static function remove($name) {
		try {
			switch (self::$storage) {
				case 'xcache' :
					return VCDCache_xcache::remove($name);
					break;
			
				case 'vcdcache':
					return VCDCache_filecache::remove($name);
					break;
					
				default:
					throw new VCDException('Selected storage engine is not implemented.');
			}
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
}


class VCDCache_xcache extends VCDCache implements ICache  {

			
	/**
	 * Get an item from the Cache
	 *
	 * @param string $name | The ID that identifies the data being but in cache
	 * @return mixed | The data thar was stored in the cache
	 */
	public static function get($name) {
		return unserialize(xcache_get($name));
	}
	
	/**
	 * Add an item to the Cache pool
	 *
	 * @param string $name | The ID to identidy the data for later retrival
	 * @param mixed $value | The data to store in the cache
	 * @param int $ttl | The lifetime of the data to store in seconds
	 * @return bool | Returns true if item could be stored, otherwise false
	 */
	public static function set($name, $value, $ttl = 60) {
		return xcache_set($name, serialize($value), $ttl);
	}
		
	/**
	 * Check if an item exists in the cache.
	 *
	 * @param string $name | The Id of the item to look for
	 * @return bool | Returns true if the item exists otherwise false.
	 */
	public static function exists($name) {
		return xcache_isset($name);
	}
	
	
	/**
	 * Remove item from the cache.
	 *
	 * @param string $name | The Id of the entry to remove
	 * @return bool | Returns true if the item was found and removed, otherwise false.
	 */
	public static function remove($name) {
		return xcache_unset($name);
	}
}

class VCDCache_filecache extends VCDCache implements ICache {
	
		
	/**
	 * Get an item from the Cache
	 *
	 * @param string $name | The ID that identifies the data being but in cache
	 * @return mixed | The data thar was stored in the cache
	 */
	public static function get($name) {
		$filename = VCDDB_BASE.DIRECTORY_SEPARATOR.CACHE_FOLDER.$name;
		return unserialize(file_get_contents($filename));
	}
	
	/**
	 * Add an item to the Cache pool
	 *
	 * @param string $name | The ID to identidy the data for later retrival
	 * @param mixed $value | The data to store in the cache
	 * @param int $ttl | The lifetime of the data to store in seconds
	 * @return bool | Returns true if item could be stored, otherwise false
	 */
	public static function set($name, $value, $ttl = 60) {
		
		if(file_put_contents(VCDDB_BASE.DIRECTORY_SEPARATOR.CACHE_FOLDER.$name, serialize($value)) > 0) {
			self::addToList($name, $ttl);
			return true;
		}
		return false;
	}
		
	/**
	 * Check if an item exists in the cache.
	 *
	 * @param string $name | The Id of the item to look for
	 * @return bool | Returns true if the item exists otherwise false.
	 */
	public static function exists($name) {
		return (self::isValid($name) && file_exists(VCDDB_BASE.DIRECTORY_SEPARATOR.CACHE_FOLDER.$name));
	}
	
	
	/**
	 * Remove item from the cache.
	 *
	 * @param string $name | The Id of the entry to remove
	 * @return bool | Returns true if the item was found and removed, otherwise false.
	 */
	public static function remove($name) {
		if (self::exists($name)) {
			self::removeFromList($name);
			return unlink(VCDDB_BASE.DIRECTORY_SEPARATOR.CACHE_FOLDER.$name);
		} else {
			return false;
		}
	}
	
	private static function removeFromList($name) {
		if (isset($_SESSION['cachemap'][$name])) {
			unset($_SESSION['cachemap'][$name]);
		}
	}
	
	private static function addToList($name, $ttl) {
		$list = array();
		if (isset($_SESSION['cachemap'])) {
			$list = $_SESSION['cachemap'];
		}
		$list[$name] = time()+$ttl;
		$_SESSION['cachemap'] = $list;
	}
	
	private static function isValid($name) {
		return (isset($_SESSION['cachemap']) 
			&& isset($_SESSION['cachemap'][$name]) 
			&& $_SESSION['cachemap'][$name] > time()); 
	}	
}

interface ICache {

		
	/**
	 * Get an item from the Cache
	 *
	 * @param string $name | The ID that identifies the data being but in cache
	 * @return mixed | The data thar was stored in the cache
	 */
	public static function get($name);
	
	/**
	 * Add an item to the Cache pool
	 *
	 * @param string $name | The ID to identidy the data for later retrival
	 * @param mixed $value | The data to store in the cache
	 * @param int $ttl | The lifetime of the data to store in seconds
	 * @return bool | Returns true if item could be stored, otherwise false
	 */
	public static function set($name, $value, $ttl = 60);
		
	/**
	 * Check if an item exists in the cache.
	 *
	 * @param string $name | The Id of the item to look for
	 * @return bool | Returns true if the item exists otherwise false.
	 */
	public static function exists($name);
	
	
	/**
	 * Remove item from the cache.
	 *
	 * @param string $name | The Id of the entry to remove
	 * @return bool | Returns true if the item was found and removed, otherwise false.
	 */
	public static function remove($name);
	
		
}


/**
 * Holds data on what data to cache and for how long.
 *
 */
class VCDCacheMap {
	
	CONST ONE_MIN = 60;
	CONST FIVE_MIN = 300;
	CONST TEN_MIN = 600;
	CONST TWENTY_MIN = 1200;
	CONST THIRTY_MIN = 1800;
	CONST HOUR = 3200;
	
	private static $cacheMap = null;
	
	/**
	 * Get the cache map rules
	 *
	 * @return array
	 */
	public static function getMap() {
		if (is_null(self::$cacheMap)) {
			self::createMap();
		} 
		return self::$cacheMap;
	}
	
	/**
	 * Create the cache map rules
	 *
	 */
	private static function createMap() {
		// Create the rules of caching, timeout, invalidations & more.
        $data = array();
       
        $data['getSettingsByKey'] 			= self::HOUR;
        $data['getCategoryIDByName'] 		= self::HOUR;
        $data['getActiveUsers']				= self::HOUR;
        $data['getMediaTypeByID']			= self::HOUR;
        
        $data['getTopTenList'] 				= self::THIRTY_MIN;
        $data['getStatsObj'] 				= self::THIRTY_MIN;
        $data['getMovieCategoriesInUse'] 	= self::THIRTY_MIN;
        $data['getUserTopList'] 			= self::THIRTY_MIN;
        $data['getAllMediatypes']			= self::THIRTY_MIN;

       
        self::$cacheMap = &$data;
	}
	
	
	
	
}


?>