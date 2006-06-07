<?php
/*
 * VCD-db - a web based VCD/DVD Catalog system
 * Copyright (C) 2003-2004 Konni - konni.com
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 */
 // $Id:
?>
<?

require_once("../classes/adodb/adodb.inc.php");	 	
require_once("../classes/adodb/adodb-xmlschema.inc.php");
require_once("../classes/VCDConstants.php");	 	

/* File system functions */
if (strcmp(strtolower(PHP_OS), "winnt") == 0) {
	require_once('../classes/external/fs_win32.php');
} else {
	require_once('../classes/external/fs_unix.php');
}

require_once('../functions/WebFunctions.php');
require_once('../classes/VCDUtils.php');


if (isset($_GET['a']) && strcmp($_GET['a'],"upgrade") == 0 ) {
	require_once('../classes/Connection.php');
	$db   = new Connection();
	$conn = &$db->getConnection();
	$error_msg = "";
	$goterror = false;
		
	
	// Check how many metadata types already exist
	$query = "SELECT COUNT(*) FROM vcd_MetaDataTypes";
	$iCount = $conn->GetOne($query);
	
	if ($iCount == 18) {
		// Metadatatypes are untouched .. no need for extra work
		
		// Add the metadata if no previous metadata exists.
		$arrQueries = array();
		$arrQueries[] = "INSERT INTO vcd_MetaDataTypes (type_name, type_description, owner_id) VALUES ('lastfetch', 'Last used fetch class', 0)";
		for ($i=0;$i<11;$i++) {
			$arrQueries[] = "INSERT INTO vcd_MetaDataTypes (type_name, type_description, owner_id) VALUES ('reserved', '', 0)";
		}	
		
		
		
		
		
	} else {
		// Metadata added by users ... we need to update all user defined metadata
		$metaQuery = "SELECT type_id, type_name, type_description, owner_id FROM vcd_MetaDataTypes WHERE type_id > 18 AND type_id < 31";
		$rs = $conn->Execute($metaQuery);
		
		if ($rs && $rs->RecordCount() > 0) {
			$arrExistingIDs = array();
			$arrExistingMeta = array();
			$arrExistingMeta = $rs->GetRows();
			
			foreach ($rs as $row) {
				array_push($arrExistingIDs, $row[0]);
			}
			
			// Update the medatatypes ...
			$arrQueries = array();
			for ($i=19;$i<=30;$i++) {
				if ($i==19) {
					if (in_array($i, $arrExistingIDs)) {
						$arrQueries[] = "UPDATE vcd_MetaDataTypes SET type_name = 'lastfetch', type_description = 'Last used fetch class', owner_id=0 WHERE type_id={$i}";
					} else {
						$arrQueries[] = "INSERT INTO vcd_MetaDataTypes (type_id, type_name, type_description, owner_id) VALUES ({$i}, 'lastfetch', 'Last used fetch class', 0)";
					}
					
				} else {
				
					if (in_array($i, $arrExistingIDs)) {
						$arrQueries[] = $arrQueries[] = "UPDATE vcd_MetaDataTypes SET type_name = 'reserved', type_description = '', owner_id=0 WHERE type_id={$i}";
					} else {
						$arrQueries[] = "INSERT INTO vcd_MetaDataTypes (type_id, type_name, type_description, owner_id) VALUES ({$i}, 'reserved', '', 0)";
					}
					
				} 
			}
			
			
			for ($i=0;$i<sizeof($arrQueries);$i++) {
				$conn->Execute($arrQueries[$i]);
			}
			
			
			// update the user defined metadata types ..
			for($i=0;$i<sizeof($arrExistingMeta);$i++) {
				if ($arrExistingMeta[$i]["owner_id"] > 0) {
					
					$query = "INSERT INTO vcd_MetaDataTypes (type_name, type_description, owner_id) VALUES ('".$arrExistingMeta[$i]["type_name"]."', '".$arrExistingMeta[$i]["type_description"]."', ".$arrExistingMeta[$i]["owner_id"].")";
					$conn->Execute($query);
					// Get the ID of the inserted item ..
					$xquery = "SELECT type_id FROM vcd_MetaDataTypes WHERE (type_name = '".$arrExistingMeta[$i]["type_name"]."' AND owner_id = ".$arrExistingMeta[$i]["owner_id"].")";
					$id = $conn->GetOne($xquery);
					
					$uquery = "UPDATE vcd_MetaData SET type_id = {$id} WHERE type_id = " . $arrExistingMeta[$i]["type_id"];
					$conn->Execute($uquery);
				}
			}
		}
	}
	

	// Then update / add new SourceSites
	$arrSourceQuerys = array();
	$arrSourceQuerys[] = "UPDATE vcd_SourceSites SET site_getCommand='http://www.imdb.com/title/tt#', site_isFetchable=1, site_classname='VCDFetch_imdb', site_image='imdb.gif' WHERE site_alias = 'imdb'";
	$arrSourceQuerys[] = "UPDATE vcd_SourceSites SET site_getCommand='http://www.adultdvdempire.com/Exec/v1_item.asp?item_id=#', site_isFetchable=1, site_classname='VCDFetch_dvdempire', site_image='dvdempire.gif' WHERE site_alias = 'DVDempire'";
	$arrSourceQuerys[] = "UPDATE vcd_SourceSites SET site_getCommand='http://jadedvideo.com/search_result.asp?product_id=#', site_isFetchable=1, site_classname='VCDFetch_jaded', site_image='jaded.gif' WHERE site_alias = 'jaded'";
	$arrSourceQuerys[] = "INSERT INTO vcd_SourceSites (site_name,site_alias,site_homepage,site_getCommand,site_isFetchable,site_classname,site_image) VALUES ('Yahoo Movies','yahoo','http://movies.yahoo.com/','http://movies.yahoo.com/movie/#/details',1,'VCDFetch_yahoo', 'yahoo.gif')";
	$arrSourceQuerys[] = "INSERT INTO vcd_SourceSites (site_name,site_alias,site_homepage,site_getCommand,site_isFetchable,site_classname,site_image) VALUES ('FilmWeb.pl','filmweb','http://filmweb.pl/','http://filmweb.pl/Film,id=#',1,'VCDFetch_filmweb', 'filmweb.gif')";
	
	foreach ($arrSourceQuerys as $sqlquery) {
		try {
			$conn->Execute($sqlquery);
		} catch (Exception $e) {
			$goterror = true;
		}
	}
	
	$error_msg = "Could not insert data.";
	$goterror = false;
		

}
function addQueryCount() {}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head><title>VCD-db Upgrade</title>
<style type="text/css" media="all">@import url("../includes/templates/default/style.css");</style>
<style type="text/css" media="all">@import url("../includes/css/setup.css");</style>
<script src="setup.js" type="text/javascript"></script>
</head>
<body>
<form name="setup" method="post" action="upgrade.php?a=upgrade">
<table cellspacing="0" cellpadding="0" width="830" border="0">
<tr>
	<td colspan="2"><div class="bar">Setup</div></td>
</tr>
<tr>
	<td valign="top" style="padding:18px 0px 0px 16px" class="content">
	<!-- Setup content -->
	<h2>VCD-DB (v. <?= VCDDB_VERSION?>) Upgrade</h2>
	<br/>
	<? if (isset($_GET['a']) && strcmp($_GET['a'],"upgrade") == 0) { 
	
		
		if ($goterror) {
			print "The upgrade process encountered errors .. <br>The message is " . $error_msg;
		} else {
			print "<b>Upgrade Successfull !<br>Delete the setup folder and start using VCD-db again :)</b>";
		}
		
		} elseif (isset($_GET['a']) && strcmp($_GET['a'],"upgrade97") == 0) { 
	
		
		if ($goterror) {
			print "The upgrade process encountered errors .. <br>The message is " . $error_msg;
		} else {
			print "<b>Upgrade Successfull !<br>Delete the setup folder and start using VCD-db again :)</b>";
		}
	
	
	} else {
		
		if (!defined('DB_TYPE') || strcmp(DB_TYPE, 'SETUP_TYPE') == 0) { ?>
		
		<span class="bold">
		ERROR.<br/>
		You cannot upgrade VCD-db until you edit your database settings in the file 'classes/VCDConstants.php' as
		said in the readme file.
		<br/><br/>
		Paste in your database settings from previous installation and reload this page.
		
		</span>
		
	<?	} else {
	?>
	
	<ul>
		 <li>Click the "Upgrade VCD-db" button below to upgrade your <b><?=DB_CATALOG?></b> database at
		 	 <b><?=DB_TYPE?></b> server <b><?=DB_HOST?></b>
		 	 
		 </li>
		 <li><span style="color:red">Attention</span>: Only upgrade from VCD-db v.098 and v.0981 is supported!</li>
		 <li><span style="color:red">Attention</span>: Be sure to have completed the required <b>STEPS 1 to 4</b> described in the <b><a title="File opens in new Window!" target="_new" href="../README">README</a></b></li>
		
	</ul>
	<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" onclick="return upgradeCheck(this.form)" value="Upgrade VCD-db" id="upgrade"/>
	</form>
		
	
	<? } } ?>
	
	<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>

	
	
	<!-- / Setup content  -->
	</td>
	<td valign="top" width="300" style="padding-right:14px;">
	<!-- Sidebar table -->
	<br/>
	<table cellspacing="0" cellpadding="6" border="0" width="300" id="menu">
	<tr>
		<td class="active">&gt; Upgrade VCD-db</td>
	</tr>
	</table>
	
	
	
	<!-- / Sidebar table -->
	</td>
</tr>
<tr>
	<td colspan="2" align="right" valign="top"><img src="../images/logotest.gif" alt="" hspace="80" vspace="2"/><br/><br/></td>
</tr>
<tr>
	<td colspan="2"><div class="bar">(c) VCD-db</div></td>
</tr>
</table>

</body>
</html>