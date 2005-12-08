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
 * @package Install
 * @version $Id$
 */
?>
<?php

require_once("../classes/adodb/adodb.inc.php");	 	
require_once("../classes/adodb/adodb-xmlschema.inc.php");
require_once("../classes/VCDConstants.php");	 	

/* File system functions */
if (strcmp(strtolower(PHP_OS), "winnt") == 0) {
	require_once('../classes/external/fs_win32.php');
} else {
	require_once('../classes/external/fs_unix.php');
}

class installer {

	private $step;
	private $continue = true;
	private $error_msg = "";
	private $guidelines = array();
	
	private $dbsettings = array();
	private $proxysettings = array();
	private $adminsettings = array();
	
	private $SQLFILES = array(
		'mssql' => 'vcd_mssql.sql',
		'db2' => 'vcd_db2.sql',
		'postgres' => 'vcd_pgtables.xml',
		'other' => 'vcd_tables.xml',
		'other_data' => 'vcd_data.xml',
		'sqlite' => 'vcd_sqlite.sql',
	);
	
	private $dbtypes = array(
		'MS SQL' => 'mssql',
		'MySQL' => 'mysql',
		'PostgreSQL' => 'postgres',
		'IBM DB2' => 'db2',
		'SQLite' => 'sqlite',
		'Interbase' => 'ibase'
	);
	
	private $steps = array(
		0 => "Introduction",
		1 => "Checking System Settings",
		2 => "Collecting Database Settings",
		3 => "Beginning Initialization",
		4 => "Create Administrator Account",
		5 => "Configure Base Settings",
		6 => "Save and Start the Party"
	);
	
	private $steps_details = array(
		0 => "Introduction",
		1 => "",
		2 => "<ul><li>These values will be used by the VCD-DB application each time a query is made to the database.</li>
			  <li>If you are not sure if you have the right information you can test your settings by clicking on the 
		      <b>\"Test Connection\"</b> button. <br/>(You might need to disable popup blockers while testing.)</li>
		      <li>If the test returns no results it means that you do <b>NOT</b> have the required modules installed for the
		      selected database type.  You should check your PHP.ini for details.</li>
			  <li><b>Please be patient after pressing Continue, creating the neccassary tables and data can take some time. 
			  Specially on not so fast machines.</b></li></ul><br/>",
		3 => "Beginning Initialization",
		4 => "<ul><li>Type in the administrator credentials</li><li>Remember your password :)</li></ul><br/>",
		5 => "<ul><li>Here you have to adjust the settings according to your needs.</li>
			  <li>The Base url and the internal path of your website should be automatically discovered, so
			  most likely you do not have to change that settings.</li>
			  <li>All these settings can be changed later in the administrator <b>Control Panel</b> from the main web
			  when administrator logs in.</li>
			  <li>The Control Panel also has a <b>Mail Tester</b> where you can adjust your SMTP settings and try them while
			  adjusting the authentication and SMTP server.</li>
			  <li>Note about storing images in the database: the database can grow alot when storing binary data, just keep it
			  in mind.</li>
			  <li>If storing images in the database is not working correctly you can always change the settings and store
			  images on HD from in the Control Panel</li>
			  </ul><br/>",
		6 => "<ul><li><b>Everything wen't well!</b><li><b>Now delete the setup folder</b> to finish the installation.
		      You must do that in order to be able to use the web because the setup always looks for the setup folder in 
		      the beginning, besides it assures that no-one else tries to misconfigure your site.</li>
			  <li>
			  <b><a href=\"../?\">Then click here you view your web for the first time :)</a></b></li>
			  <li>Enjoy the VCD DB application.</li></ul><br/>"
	);
	
	private $env = array(
		'PHP_VERSION' => PHP_VERSION,
		'GD_OK' => '0',
		'WRITE_TO_CONFIG' => '0',
		'WRITE_TO_UPLOAD' => '0',
		'FILE_UPLOADS' => '0',
		'SIMPLE_XML' => '0',
		'SESSIONS' => '0'
	);
	
	
	private $vcd_settings = array(
		'ALLOW_REGISTRATION' => 'Allow new users to register or not',
		'DB_COVERS'          => 'Store Cover images in database (otherwise on file level)',
		'PAGE_COUNT' 	     => 'Number of Records to display in the category view',
		'SESSION_LIFETIME'   => 'How many hours should the session stay alive',
		'SITE_ADULT' 	     => 'Show/Allow adult content on the site or not',
		'SITE_HOME'  		 => 'Base url of your VCD web',
		'SITE_NAME' 		 => 'The name of your website', 
		'SITE_ROOT' 		 => 'Path within the wwwroot (set to / for root)',
		//'SMTP_DEBUG' 		 => 'Set to 1 to output the communication with the SMTP server',
		'SMTP_FROM' 		 => 'Emails address the application uses to send from',
		'SMTP_PASS' 		 => 'Set the SMTP authentication password',
		'SMTP_REALM' 		 => 'Set to the authentication realm, usually the authentication users e-mail domain',
		'SMTP_SERVER'		 => 'Default SMTP Server that the application uses',
		'SMTP_USER' 		 => 'Set the user name if the SMTP server requires authentication',
		/*  Proxy server option */
		'PROXY_USE' 		 => 'Use proxy server to fetch data?',
		'PROXY_SERVER' 		 => 'The address of your proxy server',
		'PROXY_PORT' 		 => 'Your proxy server port'
		
		);
		
	private $vcd_settings_defaults = array(
		'PAGE_COUNT' 	     => 25,
		'SESSION_LIFETIME'   => 6,
		'SITE_HOME'  		 => '',
		'SITE_NAME' 		 => 'VCD Database', 
		'SITE_ROOT' 		 => '',
		'SMTP_FROM' 		 => 'user',
		'SMTP_PASS' 		 => 'password',
		'SMTP_REALM' 		 => 'example.com',
		'SMTP_SERVER'		 => 'mail.example.com',
		'SMTP_USER' 		 => 'user',
		/*  Proxy server option */
		'PROXY_USE' 		 => 0,
		'PROXY_SERVER' 		 => '',
		'PROXY_PORT' 		 => '8080'
		);
		
	
	private $vcd_settings_boolean = array ('ALLOW_REGISTRATION', 'DB_COVERS', 'SITE_ADULT', 'PROXY_USE');
	
	
		
	public $settings = array();
	
	
	function installer() {
		$this->step = 0;
    }

	public function setStep($current_step) {
		$this->step = $current_step;
	}
    
	public function getStep() {
		return $this->step;
	}
	
    public function getSteps() {
    	return $this->steps;
    }


    public function gatherData($POST) {
    
    	// Get DB Data
    	if ($this->step == 3) {
    		$this->dbsettings['db_host'] = $POST['db_host'];
    		$this->dbsettings['db_username'] = $POST['db_username'];
    		$this->dbsettings['db_password'] = $POST['db_password'];
    		$this->dbsettings['db_name'] = $POST['db_name'];
    		$this->dbsettings['db_type'] = $POST['db_type'];
    		
    		$this->writeToConfig();
    	}
    	
    	
    	// Get Admin data
    	if ($this->step == 5) {
    		$this->adminsettings['fullname'] = $POST['vcd_fullname'];	
    		$this->adminsettings['username'] = $POST['vcd_username'];	
    		$this->adminsettings['password'] = $POST['vcd_password'];	
    		$this->adminsettings['email'] = $POST['vcd_email'];	
	   		$this->saveAdmin();
    	}
    	
    	// Get user defined settings
    	if ($this->step == 6) {
    		$this->saveSettings($POST);
    		$this->updateSourceSiteCommands();
    		$this->updateConfig();
    	}
    
    }
    
    
    
    private function saveSettings($arr) {
    	
    	
    	try {
	    	$db = $this->getConnection();
    	} catch (exception $e) { 
    		$this->error_msg = "Connection to database failed.  Please check your settings.<br/><p><blockquote><i style=\"font-size:10px\">ADODB Message : ". $e->getMessage() . "</i></blockquote></p>";
    		return false;
		} 
    	
    	foreach ($arr as $item => $key) {
    		// Skip the proxy settings
    		if (substr_count($item, "PROXY_") == 0) {
    			$query = "UPDATE vcd_Settings SET settings_value = ".$db->qstr($key)." WHERE settings_key = ".$db->qstr($item)."";
    			$db->Execute($query);
    		}
    	}
    	
    	$this->proxysettings['PROXY_USE']  = $arr['PROXY_USE'];
    	$this->proxysettings['PROXY_NAME'] = $arr['PROXY_SERVER'];
    	$this->proxysettings['PROXY_PORT'] = $arr['PROXY_PORT'];
    	
    	if ($this->proxysettings['PROXY_USE'] == "") {
    		$this->proxysettings['PROXY_USE'] = 0;
    	}
    	
    	if ($this->proxysettings['PROXY_PORT'] == "") {
    		$this->proxysettings['PROXY_PORT'] = 8080;
    	}
    }
    
    
    // Update the siteGetcommand, since they contain ? and & tokens, the xml does not
    // like them alot without CDATA ...  Grab the chance and update them now
    private function updateSourceSiteCommands() {
    	try {
	    	$db = $this->getConnection();
    	} catch (exception $e) { 
    		$this->error_msg = "Connection to database failed.  Please check your settings.<br/><p><blockquote><i style=\"font-size:10px\">ADODB Message : ". $e->getMessage() . "</i></blockquote></p>";
    		return false;
		} 
    	
		$query = "UPDATE vcd_SourceSites SET site_getCommand = ".$db->qstr('http://www.imdb.com/title/#')." WHERE site_alias = ".$db->qstr('imdb')."";
		$db->Execute($query);
		$query = "UPDATE vcd_SourceSites SET site_getCommand = ".$db->qstr('http://www.adultdvdempire.com/Exec/v1_item.asp?item_id=#')." WHERE site_alias = ".$db->qstr('DVDempire')."";
		$db->Execute($query);
		$query = "UPDATE vcd_SourceSites SET site_getCommand = ".$db->qstr('http://jadedvideo.com/search_result.asp?product_id=#')." WHERE site_alias = ".$db->qstr('jaded')."";
		$db->Execute($query);
		
    	
    }
    
    
    public function showStep() {
    	
    	    	   	
    	switch($this->step) {
    		case 0:
    			
    			
    			break;
    			
    			
    		case 1:
    			print "<h2>Checking system settings</h2><br/>";
    			print "<div class=\"information\">".$this->steps_details[$this->step]."</div>";
    			$this->checkSystem();
    			break;
    			
    		case 2:
    			print "<h2>Collect database settings</h2><br/>";
    			print "<div class=\"information\">".$this->steps_details[$this->step]."</div>";
    			
    			$this->collectDBSettings();
    		
    			break;
    			
    		case 3:
    			print "<h2>Creating SQL tables and necessary data</h2><br/>";				    		    		    		
   				print "<div class=\"information\">".$this->steps_details[$this->step]."</div>";
    			
    			$strOk = "<strong>SUCCESS: </strong>";
    			$strFail = "<strong class=\"bad\">FAILURE: </strong>";
    			
    			if ($this->installDB()) {
   					print $strOk . "Tables created successfully<br/><br/>";
    			} else {
    				$this->showError();
    				$this->continue = false;
    				
    				print "<input type=\"button\" value=\"&lt;&lt; Back\" onclick=\"window.location='./?step=".($this->getStep() - 2)."&retry=true'\">";
   				}
    				
   				set_time_limit(360);
   				$this->insertAdultData();
   				
    			break;
    	
    			
    		case 4:
    			print "<h2>Creating administrator account</h2><br/>";				    		    		    		
    			print "<div class=\"information\">".$this->steps_details[$this->step]."</div>";
    			$this->createAdmin();
    			break;
    			
    			
    		case 5:
    			print "<h2>Configure Base settings for your web</h2><br/>";
    			print "<div class=\"information\">".$this->steps_details[$this->step]."</div>";
    			$this->collectConfig();
    			break;
    			
    			
    		case 6:
    			print "<h2>All set</h2><br/>";
    			print "<div class=\"information\">".$this->steps_details[$this->step]."</div>";
    			$this->continue = false;
    			break;
    			
    	}
    
    }
    
    
    private function collectConfig() {
    	print "<table border=\"0\" cellspacing=\"1\" cellpadding=\"1\" class=\"conftable\" width=\"95%\">";
    	foreach ($this->vcd_settings as $setting => $description) {
    		
    		$form_field = $this->getFormType($setting);
    		
    		print "<tr>";
    		print "<td width=\"60%\" valign=\"top\">" .$description . "</td>";
    		print "<td>$form_field</td>";
    		print "</tr>";
    	}
    	print "</table>";
    }
    
    private function getFormType($inpName) {
    	if (in_array($inpName,$this->vcd_settings_boolean)) {
    		if (strcmp($inpName, "PROXY_USE") == 0) {
    			$str = "<select name=\"$inpName\"><option value=\"1\">True</option><option value=\"0\" selected=\"selected\">False</option></select>";
    		} else {
				$str = "<select name=\"$inpName\"><option value=\"1\">True</option><option value=\"0\">False</option></select>";    		
    		}
    		
    		
    		
    		return $str;    		
    	} else {
    		return "<input type=\"text\" size=\"30\" name=\"$inpName\" value=\"".$this->getDefaultValue($inpName)."\"/>";
    	}
    }
    
    
    private function getDefaultValue($name) {
    	
    	if ($name == 'SITE_HOME') {
    		$url = $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
    		$pos = strpos($url, "setup");
    		return "http://" . substr($url, 0, $pos); 
    		
    	} else if ($name == 'SITE_ROOT') {
    		$path = $_SERVER['PHP_SELF'];
    		$pos = strpos($path, "setup");
    		return substr($path, 0, $pos); 
    		
    	}
    	
    	else if (isset($this->vcd_settings_defaults[$name])) {
    		return $this->vcd_settings_defaults[$name];
    	}
    	
    }
    
    
    private function collectDBSettings() {
    	?> 
		<table cellspacing="1" cellpadding="1" border="0" width="95%" class="conftable">
		<tr>
			<td width="200">Host:</td>
			<td><input type="text" name="db_host" size="40"></td>
		</tr>
		<tr>
			<td>Username:</td>
			<td><input type="text" name="db_username" size="40"></td>
		</tr>
		<tr>
			<td>Password:</td>
			<td><input type="text" name="db_password" size="40"></td>
		</tr>
		<tr>
			<td>Database Name:</td>
			<td><input type="text" name="db_name" size="40"></td>
		</tr>
		<tr>
			<td>Database Type</td>
			<td><select name="db_type" onchange="checkSQLite(this.form)">
				<?
					foreach ($this->dbtypes as $type => $key) {
						print "<option value=\"".$key."\">".$type."</option>";
					}
				?>
				</select>&nbsp;&nbsp;&nbsp;<input type="button" name="conntester" value="Test Connection" onClick="testConnection(this.form)" style="font-size:12px;"/>
				</td>
		</tr>
		</table>
    	
    	
    	<?
    	
    }
    
    
    private function checkSystem() {
    	$this->env['GD_OK'] = function_exists('gd_info');
    	$this->env['FILE_UPLOADS'] = ini_get('file_uploads');
    	$this->env['SESSIONS'] = function_exists('session_id');
    	    	
    	$arrFolders = array('../upload/', '../upload/cache/', '../upload/covers/',
    						'../upload/pornstars/', '../upload/thumbnails/');
    	
    	$arrBadFolders = array();
    	$bUpload = true;
    	foreach ($arrFolders as $folder) {
    		$currStatus =  is_dir($folder) && is_writable($folder);
    		$bUpload = $bUpload && $currStatus;
    		if (!$currStatus) {
    			array_push($arrBadFolders, $folder);
    		}
    	}
    	    	
    	if ($bUpload)
    		$this->env['WRITE_TO_UPLOAD'] = $bUpload;
    	
    	$bWriteConf = is_writable('../classes/VCDConstants.php');
    	if ($bWriteConf)    	
    		$this->env['WRITE_TO_CONFIG']  = $bWriteConf;
    	
    		
    	// Write out the results
    	$strOk = "<strong>SUCCESS: </strong>";
    	$strFail = "<strong class=\"bad\">FAILURE: </strong>";
    	
    	if ($this->env['PHP_VERSION'] >= 5) {
    		print $strOk . "PHP Version OK => " . $this->env['PHP_VERSION'] . "<br/><br/>";
    	} else {
    		print $strFail . "PHP Version to old => " . $this->env['PHP_VERSION'] . "<br/><br/>";
    		$this->continue = false;
    	}
    	
    	if ($this->env['GD_OK'] == true) {
    		$gdinfo = gd_info();
    		$gdversion = $gdinfo['GD Version'];
    		print $strOk . "GD Library available => {$gdversion}<br/><br/>";
    	} else {
    		print $strFail . "GD Library NOT available <br/><br/>";
    		$this->continue = false;
    	}
    	
    	if ($this->env['WRITE_TO_CONFIG'] == true) {
    		print $strOk . "Config file is writeable<br/><br/>";
    	} else {
    		print $strFail . "VCDConstants.php in not writeable<br/><br/>";
    		$this->continue = false;
    	}
    	
    	if ($this->env['FILE_UPLOADS'] == true) {
    		print $strOk . "Uploads enabled<br/><br/>";
    	} else {
    		print $strFail . "No covers/files can be uploaded, change the PHP.INI and allow uploads<br>";
    		$this->continue = false;
    	}
    	
    	if ($this->env['WRITE_TO_UPLOAD'] == true) {
    		print $strOk . "Upload folder is writeble <br/><br/>";
    	} else {
    		print $strFail . "No files can be written in the upload folder<br/><blockquote style=padding-left:58px>Check permissions on these folders .. <br/>";
    		$j = 1;
    		print "<ul>";
    		foreach ($arrBadFolders as $folder) {
    			print "<li>". $j . " " . $folder . "</li>";
    			$j++;	
    		}
    		print "</ul></blockquote><br/><br/>";
    		$this->continue = false;
    		
    	}
    	
    	$this->env['SIMPLE_XML'] = function_exists('simplexml_load_file');
    	if ($this->env['SIMPLE_XML'] == true) {
    		print $strOk . "Simple XML module enabled<br/><br/>";
    	} else {
    		print $strFail . "Simple XML module not enabled, which is strange becase the SimpleXML extension is enabled by default<br>";
    		$this->continue = false;
    	}
    	
    	
    	if ($this->env['SESSIONS'] == true) {
    		print $strOk . "Session support enabled<br/><br/>";
    	} else {
    		print $strFail . "No session support available<br>";
    		$this->continue = false;
    	}
    	   	
    	
    
    	
    }
    
    
    
    private function createAdmin() {
    	
    	?>
    	<table cellspacing="1" cellpadding="1" border="0" width="95%" class="conftable">
		<tr>
			<td width="200">Fullname:</td>
			<td><input type="text" name="vcd_fullname" size="40"></td>
		</tr>
		<tr>
			<td>Username:</td>
			<td><input type="text" name="vcd_username" size="40"></td>
		</tr>
		<tr>
			<td>Password:</td>
			<td><input type="text" name="vcd_password" size="40"></td>
		</tr>
		<tr>
			<td>Password again:</td>
			<td><input type="text" name="vcd_password2" size="40"></td>
		</tr>
		<tr>
			<td>Email:</td>
			<td><input type="text" name="vcd_email" size="40"></td>
		</tr>
		</table>

		<?	
    }
    
    // Write the proxy server settings also to config file
    private function updateConfig() {
    	// First .. read the current constants File ..
    	$file = "../classes/VCDConstants.php";
    	if (fs_file_exists($file)) {

    		$fd = fopen($file,'rb');
			if (!$fd) {
				$this->error_msg = "VCDConstants.php CLASS is unreadable !!";
    			$this->showError();
				return false;
			}
			
			
			// Read the file 
			$conn_file = fread($fd, filesize($file));   		
			
			// Replace the proxy settings
			
			$arrReplace = array('"SETUP_PROXY"', 'SETUP_PROXYNAME', '"SETUP_PROXYPORT"');
			$conn_file = str_replace($arrReplace, $this->proxysettings, $conn_file);
						
			fclose($fd);
			
			return $this->write($file, $conn_file);
			    		
    	} else {
    		$this->error_msg = "VCDConstants.php CLASS is missing, you need to d/l your copy again or install VCDDB again";
    		$this->showError();
    		return false;
    	}
    }
    
    private function writeToConfig() {
    	// First .. read the current constants File ..
    	$file = "../classes/VCDConstants.php";
    	if (fs_file_exists($file)) {

    		$fd = fopen($file,'rb');
			if (!$fd) {
				$this->error_msg = "VCDConstants.php CLASS is unreadable !!";
    			$this->showError();
				return false;
			}
			
			
			// Read the file 
			$conn_file = fread($fd, filesize($file));   		
			
			$arrReplace = array('SETUP_HOST', 'SETUP_USER', 'SETUP_PASSWORD', 'SETUP_CATALOG','SETUP_TYPE');
			$conn_file = str_replace($arrReplace, $this->dbsettings, $conn_file);
			
			
			fclose($fd);
			
			return $this->write($file, $conn_file);
			    		
    	} else {
    		$this->error_msg = "VCDConstants.php CLASS is missing, you need to d/l your copy again or install VCDDB again";
    		$this->showError();
    		return false;
    	}
    	
    }
    
    
    private function saveAdmin() {
    	try {
	    	$db = $this->getConnection();
			
    	} catch (exception $e) { 
    		$this->error_msg = "Connection to database failed.  Please check your settings.<br/><p><blockquote><i style=\"font-size:10px\">ADODB Message : ". $e->getMessage() . "</i></blockquote></p>";
    		return false;
		} 
		
		
		$query = "INSERT INTO vcd_Users (user_name, user_password, user_fullname, user_email,
							   role_id, is_deleted, date_created) 
				  VALUES (".$db->qstr($this->adminsettings['username']).",
				  ".$db->qstr(md5($this->adminsettings['password'])).",
				  ".$db->qstr($this->adminsettings['fullname']).",
				  ".$db->qstr($this->adminsettings['email']).",
			      1, 0, ".$db->DBDate(time()).")";
			
		
		$db->Execute($query);
    }
    
    private function installDB() {
   		
    	
    	try {
	    	$db = $this->getConnection();
    	} catch (exception $e) { 
    		$this->error_msg = "Connection to database failed.  Please check your settings.<br/><p><blockquote><i style=\"font-size:10px\">ADODB Message : ". $e->getMessage() . "</i></blockquote></p>";
    		return false;
		} 
				
		
		if ($this->dbsettings['db_type'] == 'db2') {
			
					
			if (fs_file_exists($this->SQLFILES['db2'])) {
    			$fd = fopen($this->SQLFILES['db2'],'rb');
				if (!$fd) {
					return false;
				}
				
				// Read the file 
				$sql = fread($fd, filesize($this->SQLFILES['db2']));
				fclose($fd);
				
				// We have to split each CREATE TABLE STATEMENT to single statements
				// Because the ODBC driver can't handle more than one Create Table at a time
				$arrTables = split("GO",$sql);
				
				foreach ($arrTables as $table) {
					$table = trim($table);
					$result = $db->Execute($table);
				} 
				
				/* 
					Execute the data Schema				
				*/
				
				$dbxml = $this->getConnection(true);
									 
	    		$schema = new adoSchema( $dbxml );
				$sql = $schema->ParseSchema( $this->SQLFILES['other_data'] );
				$result = $schema->ExecuteSchema(null, false); 
				
				if ($result == 0) {
					$this->error_msg = "ADOSchema failed while executing data schema";
					return false;
				} elseif ($result == 1) {
					$this->error_msg = "ADOSchema encountered errors while executing data schema";
					return false;
				}
				
				
				
				return true;
			}
			
			
		
		}
		elseif ($this->dbsettings['db_type'] == 'mssql') {
    	    
    		// Those who use MSSQL get the luxury of total data integrity
    		if (fs_file_exists($this->SQLFILES['mssql'])) {
    			$fd = fopen($this->SQLFILES['mssql'],'rb');
				if (!$fd) {
					return false;
				}
				
				// Read the file 
				$sql = fread($fd, filesize($this->SQLFILES['mssql']));
				fclose($fd);
				
				if($db->Execute($sql)) {
					
				} else {
					$this->error_msg = "Error inserting via sql script.";
				}
				
				
				/* 
					Execute the data Schema				
				*/
				
				$dbxml = $this->getConnection();
							 
	    		$schema = new adoSchema( $dbxml );
				$sql = $schema->ParseSchema( $this->SQLFILES['other_data'] );
				$result = $schema->ExecuteSchema(null, false); 
				
				if ($result == 0) {
					$this->error_msg = "ADOSchema failed while executing data schema";
					return false;
				} elseif ($result == 1) {
					$this->error_msg = "ADOSchema encountered errors while executing data schema";
					return false;
				}
				
				return true;
				
				
    		} else {
    			$this->error_msg = "vcd_mssql.sql is missing, d/l the VCD-DB package again.";
    			return false;
    		}
	    	
    		
    		
    	} 
    	
    	
    	elseif ($this->dbsettings['db_type'] == 'postgres') {
    	
    		$dbxml = $this->getConnection();
    								 
    		$schema = new adoSchema( $dbxml );
			$sql = $schema->ParseSchema( $this->SQLFILES['postgres'] );
			$result = $schema->ExecuteSchema(null, false); 
						
			
			/* 
					Execute the data Schema				
				*/
				
			$dbxml = $this->getConnection();
						 
    		$schema = new adoSchema( $dbxml );
			$sql = $schema->ParseSchema( $this->SQLFILES['other_data'] );
			$result = $schema->ExecuteSchema(null, false); 
			
			
			if ($result == 0) {
				$this->error_msg = "ADOSchema failed while executing Postgres schema";
				return false;
			} elseif ($result == 1) {
				$this->error_msg = "ADOSchema encountered errors while executing Postgres schema";
				return false;
			}
			
			
    		
    	} elseif ($this->dbsettings['db_type'] == 'sqlite') {
			
    		$fn = $this->SQLFILES['sqlite'];
			$tables = file($fn);
			
			foreach ($tables as $query_num => $query) {
			  $db->Execute($query);
			}
    		
    		
			
				/* 
					Execute the data Schema				
				*/
				
				$dbxml = $this->getConnection();
							 
	    		$schema = new adoSchema( $dbxml );
				$sql = $schema->ParseSchema( $this->SQLFILES['other_data'] );
				$result = $schema->ExecuteSchema(null, false); 
				
				if ($result == 0) {
					$this->error_msg = "ADOSchema failed while executing data schema";
					return false;
				} elseif ($result == 1) {
					$this->error_msg = "ADOSchema encountered errors while executing data schema";
					return false;
				}
				
				return true;
    		
    		
			
    	} else {
    		
    		
    		$dbxml = $this->getConnection();
    								 
    		$schema = new adoSchema( $dbxml );
			$sql = $schema->ParseSchema( $this->SQLFILES['other'] );
			$result = $schema->ExecuteSchema(null, false); 
			
			if ($result == 0) {
				$this->error_msg = "ADOSchema failed while executing schema";
				return false;
			} elseif ($result == 1) {
				$this->error_msg = "ADOSchema encountered errors while executing schema";
				return false;
			}
			

    	}
    	
    	
    	
    	return true;
    }
    
    
    
    private function insertAdultData() {
    	try {
    		
    		$db = $this->getConnection();
    	} catch (exception $e) { 
    		$this->error_msg = "Connection to database failed.  Please check your settings.<br/><p><blockquote><i style=\"font-size:10px\">ADODB Message : ". $e->getMessage() . "</i></blockquote></p>";
    		return false;
		} 
				
    	
		
	   $xmlfile = "vcd_pornstars.xml";
	   if (fs_file_exists($xmlfile)) {
    		$xml = simplexml_load_file($xmlfile);
	   } else {
    		exit('Failed to open $xmlfile.');
	   }
	
	     
	   $studios = $xml->studios->studio;
	   if (sizeof($studios) == 0) {
	   		print "No studios found in XML file.<br>";
	   } else {
	   		foreach ($studios as $item) {
	   			$query = "INSERT INTO vcd_PornStudios (studio_name) VALUES (".$db->qstr($item->name).")";
	   			$db->Execute($query);
			}
	   }
	   
	   
	   $categories = $xml->categories->category;
	   if (sizeof($categories) == 0) {
	   		print "No studios found in XML file.<br>";
	   } else {
	   		foreach ($categories as $item) {
	   			$query = "INSERT INTO vcd_PornCategories (category_name) VALUES (".$db->qstr($item->name).")";
	   			$db->Execute($query);
			}
	   }
	   
	   
	   
	    // Load the pornstars ...
	   $pornstars = $xml->pornstars->pornstar;
	   	   
	   if (sizeof($pornstars) == 0) {
	   		print "No pornstars found in XML file.<br>";
	   } else {
	   		foreach ($pornstars as $item) {
		    	
	   			$query = "INSERT INTO vcd_Pornstars (name, homepage, image_name, biography) 
	   					  VALUES (".$db->qstr($item->name).",
	   					  ".$db->qstr($item->homepage).",
	   			          ".$db->qstr($item->image).",
	   			          ".$db->qstr($item->biography).")";
	   			$db->Execute($query);
			}
	   }
	
    }
    

    private function getConnection($forxml = false) {
    	try {
    		
    		
    		if ($forxml) {
    			 $db = ADONewConnection($this->dbsettings['db_type']); 
    		} else {
    			$db = NewADOConnection($this->dbsettings['db_type']); 
    		}
    		
	    	
			
	    	
	    	if ($this->dbsettings['db_type'] == 'db2') {
	    		$db->Connect($this->dbsettings['db_name'],
			             $this->dbsettings['db_username'],
						 $this->dbsettings['db_password'],
						 $this->dbsettings['db_host']);
	    	
	    	} elseif ($this->dbsettings['db_type'] == 'sqlite')	{
				
	    		$db->Connect("../upload/cache/vcddb.db");
	    		
	    	} else {
	    		$db->Connect($this->dbsettings['db_host'],
			             $this->dbsettings['db_username'],
						 $this->dbsettings['db_password'],
						 $this->dbsettings['db_name']);
	    	}
	    	
	    						
				
						 	 
			return $db;			 
						 
						 
    	} catch (exception $e) { 
    		$this->error_msg = "Connection to database failed.  Please check your settings.<br/><p><blockquote><i style=\"font-size:10px\">ADODB Message : ". $e->getMessage() . "</i></blockquote></p>";
    		return false;
		}
    }
    
    
    
    public function showNextStep() {
    	return $this->continue;
    }
    
    
    public function retry() {
    	$this->continue = true;
    }
    
    private function showError() {
    	print "<div class=\"bad\">".$this->error_msg."</div>";
    }
      
	static function write($filename, $content){
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