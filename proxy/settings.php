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
 * @subpackage WebService
 * @since v.0.90
 * @version $Id: settings.php 815 2006-11-05 20:56:51Z konni $
 */
?>
<?php
require_once(substr(dirname(__FILE__), 0, strrpos(dirname(__FILE__), DIRECTORY_SEPARATOR)).'/classes/includes.php');
require_once(substr(dirname(__FILE__), 0, strrpos(dirname(__FILE__), DIRECTORY_SEPARATOR)).'/classes/VCDSoapService.php');

$ws = new VCDSoapService(VCDSoapService::WSDLSettings);
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$ws->provideService($HTTP_RAW_POST_DATA);
?>