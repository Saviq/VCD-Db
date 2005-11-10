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
require_once("adodb/adodb-exceptions.inc.php");
require_once("adodb/adodb.inc.php");	


class Connection {
	
	private $db_type 		= DB_TYPE;
   	private $db_username 	= DB_USER;
	private $db_password 	= DB_PASS;
	private $db_host 		= DB_HOST;
	private $db_catalog 	= DB_CATALOG;
	private $sqlitedb 		= "vcddb.db";
	
	private $debug = false;
	/**
	 * adoDB connection
	 *
	 * @var ADOConnection
	 */
	private $connection = null;
		
	public function __construct() {
		
		$count = substr_count(strtoupper($this->db_type), 'SETUP_');
		
		if ($count == 0) {
				
			try {
				$this->connection = &NewADOConnection($this->db_type);  
				
				// IBM DB2 wants catalog as the first parameter
				if ($this->db_type == 'db2') {
					$this->connection->Connect($this->db_catalog, $this->db_username, $this->db_password, $this->db_host);				

				
				} elseif ($this->db_type == 'sqlite') {
					// only the database name is needed
					$sqlite_dbname = $this->getSQLitePath();
					$this->connection->Connect($sqlite_dbname);
					
				} else {
					$this->connection->Connect($this->db_host, $this->db_username, $this->db_password, $this->db_catalog);
				}
				
				
				$this->connection->debug = $this->debug;
				
			} catch (Exception $e) {
				
				$this->redirect('./error.php?type=db');
				exit();
			}
		
		} else {
			$this->redirect('./error.php?type=db');
			exit();
		}
		
	}
	
	 
	/**
	 * Get live database connection
	 *
	 * @return ADOConnection
	 */
	public function &getConnection() {
		return $this->connection;
	}
	
	/**
	 * Get the SQL server type
	 *
	 * @return string
	 */
	public function getSQLType() {
		return $this->db_type;
	}
	
	/**
	 * Get the SQL server hostname
	 *
	 * @return string
	 */
	public function getSQLHost() {
		return $this->db_host;
	}
	
	/**
	 * Get the SQL server environment details
	 *
	 * @return array
	 */
	public function getServerInfo() {
		return $this->connection->ServerInfo();
	}
	
	/**
	 * Get the SQL server error message
	 *
	 * @return string
	 */
	public function getError() {
		return $this->connection->ErrorMsg();
	}
	
	
	/**
	 * Return actual ID from Postgres OID	
	 *
	 * @param string $table_name
	 * @param string $column_name
	 * @return int
	 */
	public function oToID($table_name, $column_name) {
		try {
			$query = "SELECT ".$column_name." FROM ".$table_name." ORDER BY oid DESC LIMIT 1";
			return $this->connection->getOne($query);
		} catch (Exception $e) {
			VCDException::display($e);
		}
	}
	
	/**
	 * Redirect browser the desired location.
	 *
	 * @param string $relative_url
	 */
	private function redirect($relative_url = '.?') 	{
	   $url = $this->server_url() . dirname($_SERVER['PHP_SELF']) . "/" . $relative_url;
	   if (!headers_sent())
	   {
	       header("Location: $url");
	   }
	   else
	   {
	       print "<script>location.href='".$url."'</script>";
	   }
	}
	
	
	/**
	 * Get the full server url
	 *
	 * @return string
	 */
	private function server_url()	{  
	   $proto = "http" .
	       ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "s" : "") . "://";
	   $server = isset($_SERVER['HTTP_HOST']) ?
	       $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
	   return $proto . $server;
	}
	

	/**
	 * Get the right path to the sqlite database.
	 *
	 * @return string
	 */
	private function getSQLitePath() {
		try {
			if (file_exists(dirname(__FILE__) . '/../'.CACHE_FOLDER.$this->sqlitedb)) {
				return CACHE_FOLDER.$this->sqlitedb;
			} else {
				throw new Exception('Could not find path to SQLite DB');
			}
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
		
	}
	
	
}


?>