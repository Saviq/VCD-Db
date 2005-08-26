<?php
/**
 * VCD-db - a web based VCD/DVD Catalog system
 * Copyright (C) 2003-2004 Konni - konni.com
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * @author  Hákon Birgsson <konni@konni.com>
 * @package Core
 * @version $Id$
 */
?>
<?
	require_once("VCDConstants.php");
	checkEnvironment();
	require_once("Connection.php");
	require_once("XMLable.php");
	require_once("VCDClassFactory.php");

	/* Language Files */
	require_once("languages/languages.php");

	/* External Libraries */
	require_once('external/cookie/SiteCookieClass.php');
	require_once("external/mail/smtp.php");
	require_once("external/excel/ExcelGen.php");
	require_once('external/uploader.php');
	require_once("external/lastRSS.php");

	/* Settings */
	require_once("settings/settingsFacade.php");

	/* User */
	require_once("user/userFacade.php");

	/* Pornstars */
	require_once("pornstar/pornstarFacade.php");

	/* CDCovers && Image inserts*/
	require_once("cdcover/cdcoverFacade.php");

	/* VCD movies */
	require_once("vcd/vcdFacade.php");

	require_once("VCDUtils.php");
	require_once("VCDException.php");
	require_once("VCDAuthentication.php");
	require_once("VCDOnload.php");
	require_once("VCDImage.php");
	require_once("VCDScreenshot.php");


	/* Common Functions*/
	require_once(dirname(__FILE__).'/../functions/WebFunctions.php');
	require_once(dirname(__FILE__).'/../functions/BackendFunctions.php');
	require_once(dirname(__FILE__).'/../functions/XMLFunctions.php');
	
	/* RSS */
	require_once("VCDRss.php");


	/* File system functions */
	if (strcmp(strtolower(VCDUtils::getOS()), "winnt") == 0) {
		require_once('external/fs_win32.php');
	} else {
		require_once('external/fs_unix.php');
	}
	
	function checkEnvironment() {
		if (PHP_VERSION < 5) {
			print "<br/><br/><div align=\"center\">PHP 5.0 or later must be installed for VCD-db to work.
					<br/>PHP version on webserver => ".PHP_VERSION."<br/>
					PHP 5 can be downloaded from <a href=\"http://www.php.net\" target=\"_new\">php.net</a></div>";
			die();
		}
	}
?>