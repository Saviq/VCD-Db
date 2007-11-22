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
 * @version $Id: VCDConfig.php 1062 2007-07-05 15:10:11Z konni $
 * @since  0.990
  */
?>
<?php
/**
 * A wrapper class for the config.php configuration file.
 *
 */
final class VCDConfig {

	/**
	 * Get the database type VCD-db is using
	 *
	 * @return string
	 */
	public static final function getDatabaseType() {
		if (!defined('DB_TYPE') || DB_TYPE=='') {
			return null;
		}
		return DB_TYPE;
	}
	
	/**
	 * Get the username to connect to the VCD-db database
	 *
	 * @return string
	 */
	public static final function getDatabaseUser() {
		if (!defined('DB_USER') || DB_USER=='') {
			return null;
		}
		return DB_USER;
	}
	
	/**
	 * Get the password to connect to the VCD-db database
	 *
	 * @return string
	 */
	public static final function getDatabasePassword() {
		if (!defined('DB_PASS')) {
			return null;
		}
		return DB_PASS;
	}
	
	/**
	 * Get the database host
	 *
	 * @return string
	 */
	public static final function getDatabaseHost() {
		if (!defined('DB_HOST') || DB_HOST=='') {
			return null;
		}
		return DB_HOST;
	}
	
	/**
	 * Get the name of the database VCD-db connects to
	 *
	 * @return string
	 */
	public static final function getDatabaseName() {
		if (!defined('DB_CATALOG') || DB_CATALOG=='') {
			return null;
		}
		return DB_CATALOG;
	}
	
	
	/**
	 * Check if VCD-db is connect to webservice proxy or database
	 *
	 * @return bool
	 */
	public static final function isUsingWebservice() {
		if (defined('VCDDB_USEPROXY') && (int)VCDDB_USEPROXY == 1) {
			return true;
		}
		return false;
	}
	
	
	/**
	 * Get the web base directory where VCD-db lies.  Possible output could be '/' for root directory
	 * or '/webs/vcddb/' if VCD-db resides in webfolder webs/vcddb
	 *
	 * @return string
	 */
	public static function getWebBaseDir() {
		$base = dirname($_SERVER['PHP_SELF']);
		if (self::endsWith('/',$base)) {
			return $base;
		} else {
			return $base.'/';
		}
	}
	
	
	/**
	 * Check if specified string ends with certain character
	 *
	 * @param string $str | The needle
	 * @param string $sub | The haystack
	 * @return bool
	 */
	private static function endsWith($str, $sub) {
   		return (substr($str, strlen($str) - strlen($sub)) === $sub );
	}
	
	/**
	 * Check if specified string begins with certain character
	 *
	 * @param string $str | The needle
	 * @param string $sub | The haustack
	 * @return bool
	 */
	private static function beginsWith($str, $sub) {
   		return (substr($str, 0, strlen($sub)) === $sub);
	}
	
}
?>