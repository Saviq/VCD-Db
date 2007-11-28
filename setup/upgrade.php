<?php
	define('VCDDB_BASE', substr(dirname(__FILE__), 0, strrpos(dirname(__FILE__), DIRECTORY_SEPARATOR)));
	define("CURR_VERSION","0.990");
	require_once(VCDDB_BASE.DIRECTORY_SEPARATOR.'classes/includes.php');
	
	class upgrader extends VCDConnection {
		public function __construct() { parent::__construct();}
		public function Execute($query) {$this->db->Execute($query);}
		public function Conn($query) {return $this->db;}
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>VCD-db Installer</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="installer.css">
	<style>
		form {margin:0;padding:0}
	</style>
</head>

<body>
<form method="POST" action="upgrade.php?upgrade">
<table cellspacing="3" cellpadding="3" border="0" width="100%" height="100%" id="maintbl">
<tr>
	<td colspan="2" id="topcell" valign="middle">
	<!-- Top bar -->
	<table cellspacing="0" cellpadding="0" width="100%" border="0">
		<tr>
			<td id="message" nowrap="nowrap">Welcome to VCD-db Upgrade (v.<?php print CURR_VERSION?>)</td>
			<td width="100" align="right"><img src="img/vcddb.gif" border="0" hspace="20" alt="VCD-db"></td>
		</tr>
	</table>
	<!-- / Top bar -->
	
</tr>
<tr>
	<td valign="top" id="maincell" width="75%">
	<!-- Main window -->
	
	<!-- Page 0 -->
	<div id="page0">
	
	<blockquote>
	
		<p>
		<?php
		if (isset($_POST['Upgrade'])) {

			try {
				doUpgrade();
				print "<b>Upgrade complete.<br/>You can now delete the setup folder.</b><hr/>";
				
			} catch (Exception $ex) {
				print "<b>Upgrade Failed!</b><br/>Message: {$ex->getMessage()}";
			}
		}
		?>
		</p>
	
		Press the continue button to upgrade from VCD-db 0.984, 0.985 or 0.986.
		<br/>
		Older versions are not supported by the web based upgrader.
	
		<?php if (apache_get_version() !== false) { ?>
		<br/><br/>
		<p>
		The installer has detected that you are running Apache webserver.<br/>
		VCD-db 0.990 has support for friendly urls using mod_rewrite.<br/>
		If you wish to activate this option in the "admin panel" after upgrade, you will
		have to add these lines to your apache configuration file for this host.
		<pre>
&lt;Directory "<?php echo str_replace('setup/upgrade.php','',$_SERVER['SCRIPT_FILENAME'])?>"&gt;
        AllowOverride Options FileInfo AuthConfig
&lt;/Directory&gt;
</pre>
		If you wish to enable "friendly urls"  after the upgrade you must follow these steps:
		<ol>
			<li>Make sure that you have the mod_rewrite apache module loaded.</li>
			<li>Insert the above lines to your apache config file and restart/reload apache.</li>
			<li>In the admin panel, select Tools/Create .htaccess file.</li>
			<li>In the admin panel, select Web settings and set the "friendly url" option to true.</li>
		</ol>
</p>

<p>
	VCD-db 0.990 has new webservice interface for other programs and/or other instances of VCD-db
	to interop.  For the webservice interface to work you will need to add the following lines
	at the end of your config.php file.  <br/>You can also change the password below if you do not like the generated one.
	<pre style="font-size:10px">
/**
   These constants are used if you are using the VCD-db SOAP interface, meaning that
   you can either provide another VCD-db instance with data or consume data from another
   VCD-db website via webservices.
  
  These constants consist of the following keys ...
  -----------------------------------------------------------------
  VCDDB_USEPROXY	Use remote VCD-db website as a data provider
  VCDDB_SOAPPROXY	The Url to the remote VCD-db website (must end with /)
  VCDDB_SOAPSECRET	The shared secret key used to verify permission to use the SOAP interface
  CACHE_MANAGER		Cache data if cache modules are installed, can be XCACHE or MEMCACHE
 
**/
define("VCDDB_USEPROXY", "0");
define("VCDDB_SOAPPROXY", "http://domain.com/vcddb/");
define("VCDDB_SOAPSECRET", "<?php print substr(md5(uniqid(mt_rand(),true)),0, 6)?>");
define("CACHE_MANAGER", "");
	</pre>
</p>

		<?php }	?>
		
		
		
	</blockquote>
	
	
	</div>
	
		
	<!-- Main window -->
	</td>
	<td valign="top" id="menucell" width="25%">
	<!-- Menu -->

	<br>
	<div style="margin-left:50px">
	<span id="process"><img src="img/processing.gif" border="0" align="absmiddle" hspace="5" alt="Processing, be patient!" title="Processing, be patient!"></span>
	<input type="submit" id="BtnContinue" name="Upgrade" value="Continue &gt;&gt;">
	</div>
	<!-- / Menu -->
	</td>
</tr>
</table>

</form>
</body>
</html>

<?php 
function doUpgrade() {
	try {
		
		//throw new Exception('Database invalid');

		$version = CURR_VERSION;
		$upgrader = new upgrader();
		
		$Queries = array();
		// Check the sourcesites
		$query = "SELECT count(*) FROM vcd_SourceSites WHERE site_alias = 'filmtv'";
		if ($upgrader->Conn()->getOne($query) == 0) {
			$Queries[] = "INSERT INTO vcd_SourceSites (site_name,site_alias,site_homepage,site_getCommand,site_isFetchable,site_classname,site_image) VALUES ('Film.Tv.It','filmtv','http://www.film.tv.it','http://www.film.tv.it/scheda.php?film=#','1','VCDFetch_filmtv', 'filmtvit.gif')";
		}
		
		$query = "SELECT count(*) FROM vcd_SourceSites WHERE site_alias = 'ofdb'";
		if ($upgrader->Conn()->getOne($query) == 0) {
			$Queries[] = "INSERT INTO vcd_SourceSites (site_name,site_alias,site_homepage,site_getCommand,site_isFetchable,site_classname,site_image) VALUES ('Online Filmdatenbank','ofdb','http://www.ofdb.de','http://www.ofdb.de/view.php?page=film&amp;fid=#&amp;full=1','1','VCDFetch_ofdb', 'ofdb.jpg')";
		}
		
		$query = "SELECT count(*) FROM vcd_SourceSites WHERE site_alias = 'cdon'";
		if ($upgrader->Conn()->getOne($query) == 0) {
			$Queries[] = "INSERT INTO vcd_SourceSites (site_name,site_alias,site_homepage,site_getCommand,site_isFetchable,site_classname,site_image) VALUES ('CDOn.com','cdon','http://www.cdon.com','http://www.hyrfilm.cdon.com/movie/detail.asp?MovieId=#','1','VCDFetch_cdon', 'cdon.png')";
		}
		
		$query = "SELECT count(*) FROM vcd_SourceSites WHERE site_alias = 'amazon'";
		if ($upgrader->Conn()->getOne($query) == 0) {
			$Queries[] = "INSERT INTO vcd_SourceSites (site_name,site_alias,site_homepage,site_getCommand,site_isFetchable,site_classname,site_image) VALUES ('Amazon.com','amazon','http://www.amazon.com','http://www.amazon.com/dp/#','1','VCDFetch_amazon', 'amazon.gif')";
		}
		
		
		// Check user properties
		$query = "SELECT count(*) FROM vcd_UserProperties WHERE property_name = 'NFO_IMAGE'";
		if ($upgrader->Conn()->getOne($query) == 0) {
			$Queries[] = "INSERT INTO vcd_UserProperties (property_name,property_description) VALUES ('NFO_IMAGE','View NFO files as images?')";
		}
		
		
		// Add the rewrite
		$Queries[] = "INSERT INTO vcd_Settings (settings_key, settings_value, settings_description, isProtected, settings_type) VALUES ('MOD_REWRITE','0','Use friendly urls? This requires mod_rewrite installed.','1','bool')";
						
			
		// Update the metadata types
		$Queries[] = 'DELETE FROM vcd_MetaData WHERE type_id = 6 or type_id = 7';
		$Queries[] = "UPDATE vcd_MetaDataTypes SET type_name = 'dvdlang', type_description='DVD Spoken languages' WHERE type_id = 6";
		$Queries[] = "UPDATE vcd_MetaDataTypes SET type_name = 'version', type_description='The current VCD-db version' WHERE type_id = 7";
		$Queries[] = "INSERT INTO vcd_MetaData (record_id,mediatype_id, user_id, type_id, metadata_value) VALUES (0,0,0,7,'{$version}')";
		
		

		foreach ($Queries as $query) {
			$upgrader->Execute($query);
			//print $query . "<br>\n";
		}
		
		
	} catch (Exception $ex) {
		throw $ex;
	}	
	
}
?>