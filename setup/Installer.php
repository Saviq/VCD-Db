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
 * @package Installer
 * @version $Id$
 */

require_once('../classes/adodb/adodb.inc.php');
require_once('../classes/adodb/adodb-xmlschema03.inc.php');
require_once('../classes/adodb/adodb-exceptions.inc.php');
require_once('../classes/external/ajason/Ajax.php');


class Installer {
	
	private static $XMLSchema = 'data/schema.xml';
	private static $SchemaMSSQL = 'data/mssql.sql';
	private static $SchemaDB2 = 'data/db2.sql';
	private static $SchemaSQLite = 'data/sqlite.sql';
	private static $XMLData = 'data/data.xml';
	private static $XMLAdultData = 'data/adultdata.xml';
	
	private static $template = 'config.template';
	private static $totalRecordCount = 3348;
	
	
	/**
	 * The heart of the installer and the only public function, this function is the
	 * window into the class.  All Ajax calls are routed forward from this func.
	 *
	 * @param string $checkType | The name of the check to perform
	 * @param array $args | Optional argument array
	 * @param array $args2 | Optional argument array
	 * @return array
	 */
	public static function executeCheck($checkType, $args = null, $args2 = null) {
		try {
			
			$results = array('check' => $checkType, 'status' => 0, 'results' => '');
			
			switch ($checkType) {
				
				case 'recordcount':
					try {
						$count = self::getCurrentRecordCount($args);
						$results['status'] = 1;	
						$results['results'] = $count/self::$totalRecordCount;
					} catch (Exception $ex) {
						$results['status'] = 0;	
						$count = -1;
					}
					
					
					break;
				
				case 'createadmin':
						
					try {

						if (self::createAdmin($args, $args2)) {
							$results['status'] = 1;	
						}
											
					} catch (Exception $ex) {
						$results['results'] = $ex->getMessage();
					}
					
					
					break;
				
				
				case 'saveconfig':
					
					try {
						if (self::saveConfig($args, $args2)) {
							$results['status'] = 1;		
						}
					} catch (Exception $ex) {
						$results['results'] = $ex->getMessage();
					}
					
					
					
					
				
					break;
				
				case 'populatedata':
					try {
						
						if(self::populateData($args)) {
							$results['status'] = 1;
						}
						
					} catch (Exception $ex) {
						$results['results'] = $ex->getMessage();
					}
					
					
					
					
					break;
				
				case 'createtables':
					try {
						
						$schemaResults = self::createTables($args);
						
						switch ($schemaResults) {
							case 2:
								$results['status'] = 1;
								$results['results'] = "DB Schema successfully applied.";
								break;
								
							case 1:
								$results['status'] = 2;
								$results['results'] = "DB Schema created with errors, some tables may not have been created.";
								break;
						
							default:
								$results['status'] = 3;
								$results['results'] = "DB Schema Failed!";
								break;
						}

						
					} catch (Exception $ex) {
						$results['results'] = "Error: " . $ex->getMessage();
					}
					
					break;
				
				case 'testConnection':
					try {
						
						if (self::checkDatabaseConnecion($args)) {
							$results['status'] = 1;
						}
						
					} catch (Exception $adoex) {
						$results['results'] = $adoex->getMessage();
					}
					
					
					
					break;
				
				/* System check stuff .. */
				
				case 'phpversion':
					$results['results'] = "Installed v. " . phpversion();
					if (phpversion() > 5.0) {
						$results['status'] = 1;
					}
					break;
					
					
				case 'gd':
					$results['status'] = (int)function_exists('gd_info');
					if ($results['status'] == 1) {
						$gdinfo = gd_info();
						$gdversion = $gdinfo['GD Version'];
						$results['results'] = "GD Lib => {$gdversion} ";
    		
					}
					break;
					
				case 'simplexml':
					$results['status'] = (int)function_exists('simplexml_load_file');
					$results['results'] = "SimpleXML enabled";
					break;
					
				case 'session':
					$results['status'] = (int)function_exists('session_id');;
					$results['results'] = "Sessions enabled";
					break;
					
				case 'shorttags':
					$results['status'] = (int)ini_get('short_open_tag');
					$results['results'] = "Short open tags enabled";
					break;
					
				case 'urlfopen':
					$results['status'] = (int)ini_get('allow_url_fopen');
					$results['results'] = "Allow_url_fopen is set in php.ini";
					break;
					
				case 'fileupload':
					$results['status'] = (int)ini_get('file_uploads');
					$results['results'] = "File uploads enabled";
					break;
					
				case 'permissions':
					$arrFolders = array('upload/', 'upload/cache/', 'upload/covers/',
    						'upload/pornstars/', 'upload/thumbnails/', 'upload/nfo/',
    						'upload/screenshots/albums/', 'upload/screenshots/generated');
    				$arrBadFolders = array();
			    	$bUpload = true;
			    	foreach ($arrFolders as $folder) {
			    		$currStatus =  is_dir(self::getBaseDir().$folder) && is_writable(self::getBaseDir().$folder);
			    		$bUpload = $bUpload && $currStatus;
			    		if (!$currStatus) {
			    			array_push($arrBadFolders, $folder);
			    		}
			    	}
					
					$results['status'] = (int)$bUpload;
					if ($bUpload) {
						$strResults = "All upload folders OK.";
					} else {
						$j = 1;
						$strResults = "Wrong permissions on folders ..<br/>";
			    		$strResults .= "<ul>";
			    		foreach ($arrBadFolders as $folder) {
			    			$strResults .= "<li>". $j . " " . $folder . "</li>";
			    			$j++;	
			    		}
			    		$strResults .= "</ul>";
					}
					
					$results['results'] = $strResults;
					
					break;
					
				case 'config':
					clearstatcache();
					$config_file = self::getBaseDir().'config.php';
					$config_exist = file_exists($config_file);
					$config_writable = is_writable($config_file);
					if (!$config_exist) {
						$strResults = "Config file does not exists.";
					} else if ($config_exist && !$config_writable) {
						$strResults = "Config file is NOT writeable";
					} else {
						$strResults = "Config file is writeable";
						$results['status'] = 1;
					}
			
					
					$results['results'] = $strResults;
					break;
					
				case 'database':
					$arrConns = array('mysql_connect' => 'MySQL', 'pg_connect' => 'Postgres', 
						'mssql_connect' => 'Microsoft SQL', 'db2_connect' => 'IBM DB2', 'sqlite_open' => 'SQLite');
					$strResults = "<ul style='margin:0px;padding:0px'>";
					$bConnOk = false;
					
					$available = array();
					foreach ($arrConns as $func => $type) {
						if (function_exists($func)) {
							$bConnOk = true;
							array_push($available, 1);
							$strResults .= "<li style='color:green'>{$type} module loaded</li>";
						} else {
							array_push($available, 0);
							$strResults .= "<li style='color:red'>{$type} module NOT loaded</li>";
						}
					}
					
					$strResults .= "</ul>";
						
					$results['keys'] = $available;
					$results['status'] = (int)$bConnOk;
					$results['results'] = $strResults;
					break;
						
			
				default:
					throw new Exception('Unknown check request: ' . $checkType);
			}
			
			return $results;
			
			
		} catch (Exception $ex) {
			throw new AjaxException($ex->getMessage(), $ex->getCode());			
		}
	}
	
	
	/**
	 * Get the number of records in database
	 *
	 * @param array $arrSettings | The array containing the connection settings
	 * @return int
	 */
	private static function getCurrentRecordCount($arrSettings) {
		try {
			
			if (!is_array($arrSettings)) {
				throw new Exception('Missing connection arguments.');
			}
			
			$host = $arrSettings[0];
			$user = $arrSettings[1];
			$pass = $arrSettings[2];
			$name = $arrSettings[3];
			$type = $arrSettings[4];
		
			
			$db = ADONewConnection( $type );
			switch ($type) {
				case 'db2':
					$db->Connect($name, $user, $pass, $host);	
					break;
					
				case 'sqlite':
					$db->Connect(self::getBaseDir().'upload/cache/vcddb.db');
					break;
			
				default:
					$db->Connect($host, $user, $pass, $name);
					break;
			}
			
			
			$tables = $db->MetaTables('TABLES');
			$count = 0;
			foreach ($tables as $num => $table) {
				$count += $db->GetOne("SELECT COUNT(*) FROM " . $table);
			}
	
			return $count;   		
			
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Check the database connection with the given connection parameters in the 
	 * $arrSettings array
	 *
	 * @param array $arrSettings
	 * @return bool
	 */
	private static function checkDatabaseConnecion($arrSettings) {
		try {
			
			
			if (!is_array($arrSettings)) {
				throw new Exception('Missing connection arguments.');
			}
			
			$host = $arrSettings[0];
			$user = $arrSettings[1];
			$pass = $arrSettings[2];
			$name = $arrSettings[3];
			$type = $arrSettings[4];
			
		
			// Get the old error reporting level, and set current to E_ERROR
			$error_level = error_reporting(E_ERROR);
			
			$db = NewADOConnection($type);
			switch ($type) {
    			case 'db2':
    				$db->Connect($name, $user, $pass, $host);
    				break;
    				
    			case 'sqlite':
    				return true;		
    				break;
    		
    			default:
    				$db->Connect($host, $user, $pass, $name);
    				break;
    		}
		
    	
    		// No error has been thrown .. return true
    		return true;	
	    			
			
		} catch (Exception $ex) {
			throw $ex;
		} 
	}
	
	/**
	 * Create the tables in the selected database with the VCD-db Xml Schema.
	 * Return the status of the Execution, 0 = Failure, 1 = With Errors, 2 = Success.
	 *
	 * @param array $arrSettings | The array containing the database connection properties
	 * @return int 
	 */
	private static function createTables($arrSettings) {
		try {
			
			if (!is_array($arrSettings)) {
				throw new Exception('Missing connection arguments.');
			}
			
			// Get connection parameters
			$host = $arrSettings[0];
			$user = $arrSettings[1];
			$pass = $arrSettings[2];
			$name = $arrSettings[3];
			$type = $arrSettings[4];
						
			
			//Start by creating a normal ADODB connection.
			$db = ADONewConnection( $type );
			switch ($type) {
				case 'db2':
					$db->Connect($name, $user, $pass, $host);	
					break;
					
				case 'sqlite':
					$db->Connect(self::getBaseDir().'upload/cache/vcddb.db');
					break;
			
				default:
					$db->Connect($host, $user, $pass, $name);
					break;
			}
			
			
			// Use the database connection to create a new adoSchema object.
			$schema = new adoSchema( $db );
			
			// Parse the Schema - and supress errors to override the index not found errors
			@$schema->ParseSchema(dirname(__FILE__) . '/' . self::$XMLSchema );
			
			
			// Execute based on the database type
			switch ($type) {
				case 'mssql':
					$mssqlFile = dirname(__FILE__) . '/' . self::$SchemaMSSQL;
					if (!file_exists($mssqlFile)) {
						throw new Exception('MSSQL sql script missing!');
					}
					
					$fd = fopen($mssqlFile ,'rb');
					if (!$fd) {
						throw new Exception('Cannot open MSSQL script: ' . self::$SchemaMSSQL);
					}
					
					// Read the file 
					$sql = fread($fd, filesize($mssqlFile));
					fclose($fd);
					if(!$db->Execute($sql)) {
						throw new Exception("Error creating tables with SQL file.");
					} else {
						$result = 2;
					}
					
					break;
					
				case 'db2':
					$db2File = dirname(__FILE__) . '/' . self::$SchemaDB2;
					if (!file_exists($db2File)) {
						throw new Exception('IBM Db2 sql script missing!');
					}
					
					$fd = fopen($db2File,'rb');
					if (!$fd) {
						throw new Exception('Cannot open DB2 script: ' . self::$SchemaDB2);
					}
					
					// Read the file 
					$sql = fread($fd, filesize($db2File));
					fclose($fd);
					// We have to split each CREATE TABLE STATEMENT to single statements
					// Because the ODBC driver can't handle more than one Create Table at a time
					$arrTables = split("GO",$sql);
					foreach ($arrTables as $table) {
						$db->Execute(trim($table));
					}
					
					$result = 2;
					break;
					
					
				case 'sqlite':
					$sqliteFile = dirname(__FILE__) . '/' . self::$SchemaSQLite;
					if (!file_exists($sqliteFile)) {
						throw new Exception('SQLite sql script missing!');
					}
					
					$tables = file($sqliteFile);
					if (!is_array($tables)) {
						throw new Exception('Cannot open SQLite script: ' . self::$SchemaSQLite);
					}
					
					foreach ($tables as $tablenum => $table) {
			  			$db->Execute($table);
					}
    						
					$result = 2;
					
					break;
					
				default:	// mysql and postgres
					$result = $schema->ExecuteSchema();
					break;
			}
			
			
			
			
			return $result;
			
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Populate user submitted settings, both in database and in the config file.
	 *
	 * @param array $arrSettings | Assoc array containing the settings
	 * @return bool
	 */
	private static function populateData($arrSettings) {
		try {
			
			if (!is_array($arrSettings)) {
				throw new Exception('Missing connection arguments.');
			}
			
			
			// Give it some time ... 10 minutes for really bad hardware and/or slow connections
			@set_time_limit(60*10);
			
			
			// Get connection parameters
			$host = $arrSettings[0];
			$user = $arrSettings[1];
			$pass = $arrSettings[2];
			$name = $arrSettings[3];
			$type = $arrSettings[4];
			

			$db = ADONewConnection( $type );
			switch ($type) {
				case 'db2':
					$db->Connect($name, $user, $pass, $host);	
					break;
					
				case 'sqlite':
					$db->Connect(self::getBaseDir().'upload/cache/vcddb.db');
					break;
			
				default:
					$db->Connect($host, $user, $pass, $name);
					break;
			}
			
			// Begin with VCD-db core data ..
			$schema = new adoSchema( $db );
			$schema->ParseSchema(dirname(__FILE__) . '/' . self::$XMLData );
			$result = $schema->ExecuteSchema();
			
			// Then the adult stuff
			if (file_exists(dirname(__FILE__) . '/' . self::$XMLAdultData)) {
				$Xml = simplexml_load_file(dirname(__FILE__) . '/' . self::$XMLAdultData);
				
				// Insert studio data
				$studios = $Xml->studios->studio;
				if (sizeof($studios) > 0) {
					foreach ($studios as $item) {
						$query = "INSERT INTO vcd_PornStudios (studio_name) VALUES (".$db->qstr($item->name).")";
						$db->Execute($query);
					}
				}
				
				// Insert categories
				$categories = $Xml->categories->category;
				if (sizeof($categories) > 0) {
					foreach ($categories as $item) {
						$query = "INSERT INTO vcd_PornCategories (category_name) VALUES (".$db->qstr($item->name).")";
						$db->Execute($query);
					}
				}
				
				// Insert pornstars
				$pornstars = $Xml->pornstars->pornstar;
				if (sizeof($pornstars) > 0) {
					foreach ($pornstars as $item) {
						$query = "INSERT INTO vcd_Pornstars (name, homepage, image_name, biography) 
								  VALUES (".$db->qstr($item->name).", ".$db->qstr($item->homepage).",
						          ".$db->qstr($item->image).", ".$db->qstr($item->biography).")";
						$db->Execute($query);
					}
				}
			}
			
			return true;
			
			
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Get a config value from the stdClass that was typecasted from the javascript Object
	 *
	 * @param stdClass $objConfig | The stdClass containing the data
	 * @param string $varname | The variable name within the class
	 * @param string $default | The value to return if the $varname was not set
	 * @return string
	 */
	private static function getConfigValue($objConfig, $varname, $default = "") {
		if (isset($objConfig) && is_object($objConfig)) {
			if (isset($objConfig->$varname) && !empty($objConfig->$varname)) {
				return $objConfig->$varname;
			}
		}
		
		return $default;
		
	}
	
	/**
	 * Save the config file, with the given values.  Uses config.template as a template.
	 *
	 * @param object $arrSettings | The StdClass containing the database connection settings
	 * @param object $objConfig | The StdClass containing the config values
	 * @return bool
	 */
	private static function saveConfig($arrSettings, $objConfig) {
		try {
		
			if (!is_array($arrSettings)) {
				throw new Exception('Missing connection arguments.');
			}
			
			
			// Give it some time ... 2 minutes for really bad hardware and/or slow connections
			@set_time_limit(60*2);
			
			
			// Get connection parameters
			$host = $arrSettings[0];
			$user = $arrSettings[1];
			$pass = $arrSettings[2];
			$name = $arrSettings[3];
			$type = $arrSettings[4];
			
			
			$db = ADONewConnection( $type );
			switch ($type) {
				case 'db2':
					$db->Connect($name, $user, $pass, $host);	
					break;
					
				case 'sqlite':
					$db->Connect(self::getBaseDir().'upload/cache/vcddb.db');
					break;
			
				default:
					$db->Connect($host, $user, $pass, $name);
					break;
			}
			
			if (!is_object($objConfig)) {
				throw new Exception('Config params missing!');
			} 
			
			// Create the mappings to update the config.php file
			$cmap = array(
				'db.type'			=> $type,
				'db.user'			=> $user,
				'db.pass'			=> $pass,
				'db.host'			=> $host,
				'db.catalog'		=> $name,
				'ldap.auth'			=> self::getConfigValue($objConfig, 'useldap', 0),
				'ldap.host'			=> self::getConfigValue($objConfig, 's_ldaphost'),
				'ldap.base'			=> self::getConfigValue($objConfig, 's_ldapdn'),
				'ldap.isad'			=> self::getConfigValue($objConfig, 'ldapad', 0),
				'ldap.domain'		=> self::getConfigValue($objConfig, 's_ldapad'),
				'proxy.enable'		=> self::getConfigValue($objConfig, 'useproxy', 0),
				'proxy.hostname'	=> self::getConfigValue($objConfig, 's_proxyhost', 0),
				'proxy.port'		=> self::getConfigValue($objConfig, 's_proxyport', "8080")
			);
			
			
			// Create the db mappings to update ..
			$map = array(
				's_register' 		=> 'ALLOW_REGISTRATION', 
				's_covers'			=> 'DB_COVERS',
				's_category'		=> 'PAGE_COUNT',
				's_session'			=> 'SESSION_LIFETIME',
				's_adult'			=> 'SITE_ADULT',
				's_title'			=> 'SITE_NAME',
				's_email'			=> 'SMTP_FROM',
				's_smtphost'		=> 'SMTP_SERVER',
				's_smtpusername'	=> 'SMTP_USER',
				's_smtppassword'	=> 'SMTP_PASS',
				's_smtprealm'		=> 'SMTP_REALM'
			);
			
						
			// Update the settings in database and map the user submitted entries to the config file
			$class_vars = get_object_vars($objConfig);
			foreach ($class_vars as $key => $value) {
				if (strcmp($value, '' != 0) && key_exists($key, $map)) {
					$query = "UPDATE vcd_Settings SET settings_value = {$db->Quote($value)} WHERE settings_key = {$db->Quote($map[$key])}";
					$db->Execute($query);
				}
				
				if (strcmp($value, '' != 0) && key_exists($key, $cmap)) {
					$cmap[$key] = $value;
				}
				
			}
			
			// Add the SITE_ROOT and SITE_HOME values ..
			$query = "UPDATE vcd_Settings SET settings_value = {$db->Quote(self::getUrl())} WHERE settings_key = 'SITE_HOME'";
			$db->Execute($query);
			$query = "UPDATE vcd_Settings SET settings_value = {$db->Quote(self::getRelativeUrl())} WHERE settings_key = 'SITE_ROOT'";
			$db->Execute($query);
			
			
			// Then read the config file template and write with the used based values.
			$configtemplate = file_get_contents(self::$template);
			$configfile = str_replace(array_keys($cmap), array_values($cmap), $configtemplate);
			
			if (!self::write(self::getBaseDir().'config.php', $configfile)) {
				throw new Exception('Could not write to config file!');
			}
		
			
			
			
			return true;
			
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Get the full url of VCD-db
	 *
	 * @return string
	 */
	private static function getUrl() {
		$prefix = (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off" ? 'http' : 'https') . "://";
		$fullurl = $prefix.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		return substr($fullurl, 0, strpos($fullurl, "setup")); 
	}

	/**
	 * Get the relative url within the domain name
	 *
	 * @return string
	 */
	private static function getRelativeUrl() {
		$path = $_SERVER['PHP_SELF'];
		$pos = strpos($path, "setup");
		return substr($path, 0, $pos); 
	}
	
	/**
	 * Create the admin account in database.
	 *
	 * @param object $arrSettings | The StdClass containing the database connection settings
	 * @param object $objConfig | The StdClass containing the config values
	 * @return bool
	 */
	private static function createAdmin($arrSettings, $objConfig) {
		try {
		
			
			if (!is_array($arrSettings)) {
				throw new Exception('Missing connection arguments.');
			}
			
			// Give it some time ... 2 minutes for really bad hardware and/or slow connections
			@set_time_limit(60*2);
			
			// Get connection parameters
			$host = $arrSettings[0];
			$user = $arrSettings[1];
			$pass = $arrSettings[2];
			$name = $arrSettings[3];
			$type = $arrSettings[4];
			
			
			$db = ADONewConnection( $type );
			switch ($type) {
				case 'db2':
					$db->Connect($name, $user, $pass, $host);	
					break;
					
				case 'sqlite':
					$db->Connect(self::getBaseDir().'upload/cache/vcddb.db');
					break;
			
				default:
					$db->Connect($host, $user, $pass, $name);
					break;
			}
			
			if (!is_object($objConfig)) {
				throw new Exception('Config params missing!');
			} 
			
			$query = "INSERT INTO vcd_Users (user_name, user_password, user_fullname, 
				user_email, role_id, is_deleted, date_created) VALUES (
				{$db->qstr(self::getConfigValue($objConfig, 'vcd_username'))},
				{$db->qstr(md5(self::getConfigValue($objConfig, 'vcd_password')))},
				{$db->qstr(utf8_encode(self::getConfigValue($objConfig, 'vcd_fullname')))},
				{$db->qstr(self::getConfigValue($objConfig, 'vcd_email'))},
			    1, 0, {$db->DBDate(time())})";
			$db->Execute($query);
			
			return true;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	
	/**
	 * Get the basedir of the VCD-db installation.  I.O.W. the parent of this folder.
	 *
	 * @return string
	 */
	private static function getBaseDir() {
		return substr(dirname(__FILE__), 0, strrpos(dirname(__FILE__), DIRECTORY_SEPARATOR)).DIRECTORY_SEPARATOR;
	}
	
	/**
	 * Write file to HD.
	 *
	 * @param string $filename | The filename to use
	 * @param string $content | The file content
	 * @return bool
	 */
	private static function write($filename, $content){
			if(!empty($filename) && !empty($content)){
				$fp = fopen($filename,"w");
				$b = fwrite($fp,$content);
				fclose($fp);
				if($b != -1){
					return TRUE;
				} else {
					return FALSE;
				}
			} else {
				return FALSE;
			}
		}

	
	
	
}

?>