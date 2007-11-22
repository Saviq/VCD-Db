<?php
define('BASE', substr(dirname(__FILE__), 0, strrpos(dirname(__FILE__), DIRECTORY_SEPARATOR)));
if (!defined('VCDDB_BASE')) {
	define('VCDDB_BASE',BASE);
}
require_once(BASE .'/classes/includes.php');
if (!VCDAuthentication::isAdmin()) {
	VCDException::display("Only administrators have access here");
	print "<script>self.close();</script>";
	exit();
}

if (isset($_GET['t']) && $_GET['t']=='sql') {
	$exportTables = ($_GET['v']=='all');
	$sqlExport = new VCDSQLExporter();
	$sqlExport->export($exportTables);
}

?>