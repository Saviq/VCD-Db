<?php 

error_reporting(0);

require_once("../classes/adodb/adodb-exceptions.inc.php"); 
require_once("../classes/adodb/adodb.inc.php");	 
	
$dbval = array(
	'host' => $_GET['h'],
	'username' => $_GET['u'],
	'password' => $_GET['p'],
	'dbname' => $_GET['db'],
	'dbtype' => $_GET['type']
	);


try { 
	$db = NewADOConnection($dbval['dbtype']); 
	
	if ($dbval['dbtype'] == 'db2') {
		$succ = $db->Connect($dbval['dbname'],$dbval['username'],$dbval['password'],$dbval['dbhost']);
	} else {
		$succ = $db->Connect($dbval['host'],$dbval['username'],$dbval['password'],$dbval['dbname']);
	}
	

} catch (exception $e) { 
	print $e->getMessage();
	//adodb_backtrace($e->gettrace());
} 


if ($succ) {
?>
<div align="center">
<strong style="color:green">Connection Successful</strong>
<br/><input type="button" value="Close" onclick="window.close()">
</div>
<?
} else {
	?>
<div align="center">
<strong style="color:red">Connection Failed</strong>
<br/><input type="button" value="Close" onclick="window.close()">
</div>
<?	
}
?>

