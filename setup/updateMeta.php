<?php

require_once("../classes/adodb/adodb.inc.php");	 	
require_once("../classes/adodb/adodb-xmlschema.inc.php");
require_once("../classes/VCDConstants.php");	 	
require_once('../functions/WebFunctions.php');
require_once('../classes/VCDUtils.php');
require_once('../classes/Connection.php');


function addQueryCount() {}

function upgradeMetaData() {

	try {
	
	$db = new Connection();
	$conn = $db->getConnection();
	
	$query = "SELECT metadata_id, metadata_name from vcd_MetaData";
	$rs = $conn->Execute($query);
	$arr = $rs->getAssoc();
	
	$iRecordCounter = 0;
	
	
	
	foreach ($arr as $key => $value) {
		$updatequery = "UPDATE vcd_MetaData SET metadata_name = " . getNewMetaId($value) . " WHERE metadata_id = " . $key;
		$conn->Execute($updatequery);
		$iRecordCounter++;
	}
	
	
	return "Success .. updated {$iRecordCounter} metadata Records.";
	
	
	} catch (Exception $ex) {
		return "Error updating metadata<br>".$ex->getMessage();
	}
	
}

function getNewMetaId($metaname) {
	
	$SYS_LANGUAGES    = 1;
	$SYS_LOGTYPES     = 2;
	$SYS_FRONTSTATS   = 3;
	$SYS_FRONTBAR     = 4;
	$SYS_DEFAULTROLE  = 5;
	$SYS_PLAYER	  	  = 6;
	$SYS_PLAYERPATH   = 7;
	$SYS_FRONTRSS 	  = 8;
	$SYS_IGNORELIST   = 9;
	$SYS_MEDIAINDEX   = 10;
	$SYS_FILELOCATION = 11;
	$SYS_SEENLIST 	  = 12;
	
	switch ($metaname) {
		case 'languages': 	 return $SYS_LANGUAGES;		break;
		case 'logtypes': 	 return $SYS_LOGTYPES;		break;
		case 'frontstats': 	 return $SYS_FRONTSTATS;	break;
		case 'frontbar': 	 return $SYS_FRONTBAR;		break;
		case 'default_role': return $SYS_DEFAULTROLE;	break;
		case 'player': 		 return $SYS_PLAYER;		break;
		case 'playerpath':   return $SYS_PLAYERPATH;	break;
		case 'frontrss': 	 return $SYS_FRONTRSS;		break;
		case 'ignorelist':	 return $SYS_IGNORELIST;	break;
		case 'mediaindex': 	 return $SYS_MEDIAINDEX;	break;
		case 'filelocation': return $SYS_FILELOCATION;	break;
		case 'seenlist': 	 return $SYS_SEENLIST;		break;
		default: return -1; break;
	
		
	}
}


?>


<html>
<body>
<title>Metadata update</title>
</body>

<? if (isset($_GET['do']) && $_GET['do'] == 'update') {
	$msg = upgradeMetaData();
	print $msg;
	
	print "<br><p>If action was successful you can close this window.</p>";
	
} else {


?> 

Click button below to upgrade metadata in database.<br>
This action should only be ran once.<br>
<br>
<input type="button" onclick="location.href='./updateMeta.php?do=update'" value="Run Metadata Update">

<? 

}

?>

</html>