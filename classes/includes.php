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
	// Define the current VCD-db version revision
	define("VCDDB_VERSION","0.985-CVS");
	define('VCDDB_BASE', substr(dirname(__FILE__), 0, strrpos(dirname(__FILE__), DIRECTORY_SEPARATOR)));
	if (file_exists(VCDDB_BASE.'/config.php')) {
		require_once(VCDDB_BASE.'/config.php');
	} else {
		header("Location: setup/index.php"); 
		exit();
	}

	
	
	checkEnvironment();
	
	require_once(dirname(__FILE__) . '/Connection.php');
	require_once(dirname(__FILE__) . '/XMLable.php');
	require_once(dirname(__FILE__) . '/VCDClassFactory.php');
	require_once(dirname(__FILE__) . '/VCDService.php');
	
	/* External Libraries */
	require_once(dirname(__FILE__) . '/external/cookie/SiteCookieClass.php');
	require_once(dirname(__FILE__) . '/external/mail/smtp.php');
	require_once(dirname(__FILE__) . '/external/excel/ExcelGen.php');
	require_once(dirname(__FILE__) . '/external/lastRSS.php');
	include_once(dirname(__FILE__) . '/external/Image_Toolbox.class.php');
	
	

	/* Common Functions*/
	require_once(VCDDB_BASE . '/functions/WebFunctions.php');
	require_once(VCDDB_BASE . '/functions/BackendFunctions.php');
	require_once(VCDDB_BASE . '/functions/XMLFunctions.php');

	/* Core Classes */
	require_once(dirname(__FILE__) . '/VCDUtils.php');
	require_once(dirname(__FILE__) . '/VCDException.php');
	require_once(dirname(__FILE__) . '/VCDLog.php');
	require_once(dirname(__FILE__) . '/VCDLanguage.php');
	require_once(dirname(__FILE__) . '/VCDAuthentication.php');
	require_once(dirname(__FILE__) . '/VCDImage.php');
	require_once(dirname(__FILE__) . '/VCDScreenshot.php');
	require_once(dirname(__FILE__) . '/VCDXMLImporter.php');
	require_once(dirname(__FILE__) . '/VCDFileUpload.php');
	require_once(dirname(__FILE__) . '/fetch/VCDFetch.php');
	
	
	/* RSS */
	require_once(dirname(__FILE__) . '/VCDRss.php');
	
	/* File system functions */
	if (strcmp(strtolower(VCDUtils::getOS()), "winnt") == 0) {
		require_once(dirname(__FILE__) . '/external/fs_win32.php');
	} else {
		require_once(dirname(__FILE__) . '/external/fs_unix.php');
	}
	
	
	
	/* VCD-db Bootstrappers - Ajax Loader */
	require_once(dirname(__FILE__) . '/VCDOnload.php');
	require_once(dirname(__FILE__) . '/VCDAjaxLoader.php');
	
	
	/**
	 * Check for current PHP Version and see if VCD-db can continue.
	 *
	 */
	function checkEnvironment() {
		if (PHP_VERSION < 5) {
			print "<br/><br/><div align=\"center\">PHP 5.0 or later must be installed for VCD-db to work.
					<br/>PHP version on webserver => ".PHP_VERSION."<br/>
					PHP 5 can be downloaded from <a href=\"http://www.php.net\" target=\"_new\">php.net</a></div>";
			die();
		}
	}
?>