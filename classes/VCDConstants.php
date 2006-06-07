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
 * @author  Hákon Birgisson <konni@konni.com>
 * @package Core
 * @version $Id$
 */
?>
<? 
// All constants used by VCD-db will be placed here
define("VCDDB_VERSION","0.982");                   		 // VCD-db version
define("STYLE","includes/templates/default/");           // Path to current template
define("TEMP_FOLDER","upload/");                         // Temp folder used by VCD-db
define("CACHE_FOLDER","upload/cache/");                  // Fetch cache folder
define("THUMBNAIL_PATH","upload/thumbnails/");           // Thumbnail path
define("COVER_PATH","upload/covers/");                   // Covers path
define("PORNSTARIMAGE_PATH","upload/pornstars/");        // Pornstar images
define("NFO_PATH","upload/nfo/");                        // NFO files

// Proxy settings | if using proxy server, define it here below
define("USE_PROXY",  0);                                 // Change to "1" if using proxy server
define("PROXY_URL",  "");                                // Url of your proxy server
define("PROXY_PORT", 8080);                              // Proxy port

// RSS Settings
define("RSS_CACHE_TIME",7200);                           // 2 hours

// Database settings
define("DB_TYPE",    "SETUP_TYPE");
define("DB_USER",    "SETUP_USER");
define("DB_PASS",    "SETUP_PASSWORD");
define("DB_HOST",    "SETUP_HOST");
define("DB_CATALOG","SETUP_CATALOG");

// Authentication Method
define("LDAP_AUTH", 0);                                   // Are you using LDAP for authentication ?
define("LDAP_HOST", "");                                  // LDAP host name (and port if not using default)
define("LDAP_BASEDN", "");                                // LDAP Base DN for Binding to LDAP server
define("LDAP_AD", 0);                                     // Is this LDAP server an Active Directory Server ?
define("AD_DOMAIN", "");                                  // If server is an AD server, domain name must be specified.

// Maximum filesizes for uploading and importing images and data (in MB)
// If entry exceeds "upload_max_filesize" in php.ini,  "upload_max_filesize" overrides.
define("VSIZE_THUMBS", 0.2);                              // Max Thumbnail filesize
define("VSIZE_COVERS", 5);                                // Max Cover filesize
define("VSIZE_XML", 20);                                  // Max Imported XML filesize
define("VSIZE_XMLTHUMBS", 30);                            // Max imported XML Thumbnail filesize
?>