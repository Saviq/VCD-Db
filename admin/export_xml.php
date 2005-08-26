<?php
require_once("../classes/includes_admin.php");
header('Content-type: application/xml');
if (!isset($_GET['show'])) {
header('Content-Disposition: attachment; filename="vcd_export.xml"');
}
if (!VCDAuthentication::isAdmin()) {
	VCDException::display("Only administrators have access here");
	print "<script>self.close();</script>";
	exit();
}
global $ClassFactory;
$SETTINGSclass = $ClassFactory->getInstance("vcd_settings");
$USERclass = $ClassFactory->getInstance("vcd_user");

$allSettings = $SETTINGSclass->getAllSettings();
$allPropeties = $USERclass->getAllProperties();
$allRoles = $USERclass->getAllUserRoles();
$allUsers = $USERclass->getAllUsers();
$allMediaTypes = $SETTINGSclass->getAllMediatypes();
$allSourceSites = $SETTINGSclass->getSourceSites();

$xml = "<?xml version=\"1.0\" encoding=\"iso-8859-1\" ?>";
$xml .= "<sitedata>";

$xml .= "<vcdsettings>";
foreach ($allSettings as $obj) { $xml .= $obj->toXML(); }
$xml .= "</vcdsettings>";

$xml .= "<vcdroles>";
foreach ($allRoles as $obj) { $xml .= $obj->toXML(); }
$xml .= "</vcdroles>";

$xml .= "<userproperties>";
foreach ($allPropeties as $obj) { $xml .= $obj->toXML(); }
$xml .= "</userproperties>";

$xml .= "<vcdusers>";
foreach ($allUsers as $obj) { $xml .= $obj->toXML(); }
$xml .= "</vcdusers>";

$xml .= "<vcdmedia>";
foreach ($allMediaTypes as $obj) { $xml .= $obj->toXML(); }
$xml .= "</vcdmedia>";

$xml .= "<vcdsourcesites>";
foreach ($allSourceSites as $obj) { $xml .= $obj->toXML(); }
$xml .= "</vcdsourcesites>";


$xml .= "</sitedata>";
print $xml;
	
	
?>