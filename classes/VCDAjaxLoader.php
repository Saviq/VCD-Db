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
<?

if (VCDUtils::isLoggedIn()) {

	// Include the Ajason & Ajax libraries
	require_once(dirname(__FILE__) . '/external/ajason/Ajax.php');

	// Create Ajax class instance
	$ajax = new Ajax();
	// Register VCD-db Ajax methods
	$ajax->registerMethod('VCDXMLImporter', 'addMovie' );
	$ajax->registerMethod('dvdObj', 'getCountryFlag');
	$ajax->registerMethod('VCDAjaxHelper', 'getDataForMediaType');
	$ajaxServer = $ajax->getServer();
	// Check for Ajax Request and handle it.
	if ( $ajaxServer->isRequest() )
	{
	  echo $ajaxServer->handleRequest();
	  exit();
	}
	$ajaxClient = $ajax->getClient();


}


?>