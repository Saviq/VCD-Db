<?
	define('VCDDB_BASE', substr(dirname(__FILE__), 0, strrpos(dirname(__FILE__), DIRECTORY_SEPARATOR)));
	require_once(VCDDB_BASE.DIRECTORY_SEPARATOR.'classes/includes.php');
	
	class upgrader extends VCDConnection {
		public function __construct() { parent::__construct();}
		public function Execute($query) {$this->db->Execute($query);}
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
			<td id="message" nowrap="nowrap">Welcome to VCD-db Upgrade</td>
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
	
		Press the continue button to upgrade from VCD-db 0.984 or VCD-db 0.985.
		<br/>
		Older versions are not supported by the web based upgrader.
	
		
		<br/><br/>
		<p>
		<?php
		if (isset($_POST['Upgrade'])) {

			$queries = array(
				"INSERT INTO vcd_SourceSites (site_name,site_alias,site_homepage,site_getCommand,site_isFetchable,site_classname,site_image) VALUES ('Film.Tv.It','filmtv','http://www.film.tv.it','http://www.film.tv.it/scheda.php?film=#','1','VCDFetch_filmtv', 'filmtvit.gif')",
				"INSERT INTO vcd_SourceSites (site_name,site_alias,site_homepage,site_getCommand,site_isFetchable,site_classname,site_image) VALUES ('Online Filmdatenbank','ofdb','http://www.ofdb.de','http://www.ofdb.de/view.php?page=film&amp;fid=#&amp;full=1','1','VCDFetch_ofdb', 'ofdb.jpg')",
				"INSERT INTO vcd_UserProperties (property_name,property_description) VALUES ('NFO_IMAGE','View NFO files as images?')"
			);
			
			
			$upgrader = new upgrader();
			try {
				foreach ($queries as $query) {
					$upgrader->Execute($query);
				}
			} catch (Exception $ex) {
				VCDException::display($ex);
			}
			
			
			print "<b>Upgrade complete.<br/>You can now delete the setup folder.</b>";
			
		
		}
		?>
		</p>
		
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
