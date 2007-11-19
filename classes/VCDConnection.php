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
 * @version $Id$
 */
?>
<?php
require_once(dirname(__FILE__) . '/adodb/adodb-exceptions.inc.php');
require_once(dirname(__FILE__) . '/adodb/adodb.inc.php');


class VCDConnection {

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
	protected $db = null;
	protected static $queryCounter = 0;

	/**
	 * Initialize the database connection, keep the constructor private to keep
	 * unauthorized classes from using the connection.
	 *
	 */
	protected function __construct() {

		if (!is_null($this->db)) {
			return;
		}
		
		if (defined('DB_USER')) {

			try {
				$this->db = NewADOConnection($this->db_type);

				// IBM DB2 wants catalog as the first parameter
				if ($this->db_type == 'db2') {
					// Try the old approach
					try {
						$this->db->Connect($this->db_catalog, $this->db_username, $this->db_password, $this->db_host);
					} catch (Exception $ex) {
						// If it failes .. try the new approach
						$this->db->Connect($this->db_host, $this->db_username, $this->db_password, $this->db_catalog);
					}


				} elseif ($this->db_type == 'sqlite') {
					// only the database name is needed
					$this->db->Connect($this->getSQLitePath());
					
					
				} else {
					
					if ($this->db_type == 'oci8') {
						$this->db->charSet = 'AL32UTF8';
					}
					
					$this->db->Connect($this->db_host, $this->db_username, $this->db_password, $this->db_catalog);
				}


				$this->db->debug = $this->debug;
				$this->db->fnExecute = 'addQueryCount';

			} catch (Exception $e) {

				$this->redirect('?page=error&type=db');
				exit();
			}

		} else {
			$this->redirect('?page=error&type=db');
			exit();
		}

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
	public static function getServerInfo() {
		$obj = new VCDConnection();
		return $obj->db->ServerInfo();
	}
	
	
	/**
	 * Check if the database being used is Oracle
	 *
	 * @return bool
	 */
	protected function isOracle() {
		return (strcmp($this->db_type, 'oci8') == 0);
	}
	
	/**
	 * Check if the database being used is Postgres
	 *
	 * @return bool
	 */
	protected function isPostgres() {
		return ((substr_count(strtolower($this->getSQLType()), 'postgre') > 0));
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
	protected function oToID($table_name, $column_name) {
		try {
			$query = "SELECT {$column_name} FROM {$table_name} ORDER BY {$column_name} DESC LIMIT 1";
			return $this->db->getOne($query);
		} catch (Exception $ex) {
			throw $ex;
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
			
			$fullpath = VCDDB_BASE.DIRECTORY_SEPARATOR.CACHE_FOLDER.$this->sqlitedb;
			if (file_exists($fullpath)) {
				return $fullpath;
			} else {
				throw new Exception('Could not locate the SQLite database');
			}
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Increment the internal query counter by 1
	 *
	 */
	public static function addQueryCount() {
		self::$queryCounter++;
	}

	/**
	 * Get the total number of queries executed during page load.
	 *
	 * @return int
	 */
	public static function getQueryCount() {
		return self::$queryCounter;
	}

}


?>