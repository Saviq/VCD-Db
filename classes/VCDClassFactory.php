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
<?php
/**
	Class factory to provide the application with worker 
	classes needed on demand within the application.
*/

final class VCDClassFactory {

	/**
	 * Internal cache of loaded classes.
	 *
	 * @var array
	 */
	private static $classArray = array();
	

	/**
	 * Class constructor.
	 *
	 */
	public function __construct() {}
	
	/**
	 * Class destructor.  Unsets all instances in cache.
	 *
	 */
	public function __destruct() {
		foreach (VCDClassFactory::$classArray as $obj) {
			unset($obj);
		}
	}
	
	
	/**
	 * Get an instance of the specified class name.
	 *
	 * If the requested class name is already in cache the instance from the
	 * cache is returned, otherwise new instance is created and saved in local cache
	 * and then returned.
	 * If class is not known to the system, an Exception is thrown.
	 *
	 * @param string $instance_name
	 * @return mixed
	 */
	public static function getInstance($instance_name) {
		try {
			if (class_exists($instance_name)) {

				// Check if class is cached in the factory
				if (array_key_exists($instance_name, VCDClassFactory::$classArray)) {
					return VCDClassFactory::$classArray[$instance_name];
				}
				
				
				$obj = new $instance_name;
				VCDClassFactory::$classArray[$instance_name] = $obj;
				return $obj;
				
			} else {
				throw new Exception($instance_name . " is an unknown class");
			}
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Put an object into the ClassFactory Cache
	 *
	 * @param object $obj | The object/class to store in the cache 
	 * @param bool $replace | Replace object with existing key or not
	 */
	public static function put($obj, $replace = false) {
		if (is_object($obj)) {
			if ($replace) {
				VCDClassFactory::$classArray[get_class($obj)] = $obj;
			} else if (!array_key_exists(get_class($obj), VCDClassFactory::$classArray)) {
				VCDClassFactory::$classArray[get_class($obj)] = $obj;
			}
		}
	}
	
	
	/**
	 * Ask the framework to load specific class.
	 * If the class is not known, then the /classes/fetch directory will be searched for
	 * the specific class.  The fetch classes are the only onces that need dynamic loading.
	 * If function failes to load the class, null is returned.
	 *
	 * @param string $className
	 * @return $class
	 */
	public static function loadClass($className) {
		try {
			
			// Check if class is cached in the factory
			if (array_key_exists($className, VCDClassFactory::$classArray)) {
					return VCDClassFactory::$classArray[$className];
			}
			
			// Check if this is a known class ..
			if (class_exists($className)) {
				return new $className;
			}
						
			// Class not found .. check the fetch classes
			
			// Try to include the file ..
			$classPath = "/fetch/{$className}.php";
            @include_once(dirname(__FILE__) . $classPath);
            if (class_exists($className)) {
            	return new $className;
            } else {
            	return null;
            }
			
			
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Get the size of the cache.
	 *
	 * @return int
	 */
	public static function getCacheSize() {
		return sizeof(VCDClassFactory::$classArray);
	}
	
	/**
	 * Flush the internal class cache.
	 *
	 */
	public static function flushCache() {
		VCDClassFactory::$classArray = null;
		VCDClassFactory::$classArray = array();
	}
	
}

?>