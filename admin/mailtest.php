<?php
	require_once("../classes/includes.php");
	require_once("functions/adminPageFunctions.php");
	
	if (!VCDAuthentication::isAdmin()) {
		VCDException::display("Only administrators have access here");
		print "<script>self.close();</script>";
		exit();
	}
	
	// Shut down errors ..
	error_reporting(0);
	
?> 

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/strict.dtd">		 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>...........................</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="../includes/css/admin.css" type="text/css" media="all" />
<script src="../includes/js/admin.js" type="text/javascript"></script>

</head>
<body onload="window.focus()">

<?php

	$arrsettings = SettingsServices::getAllSettings();
	$sObj = null;
	foreach ($arrsettings as $settingsObj) {
		if (strcmp($settingsObj->getKey(), 'SMTP_DEBUG') == 0) {
			$sObj = $settingsObj;
			break;
		}
	}
	
	if (!$sObj instanceof settingsObj ) {
		VCDException::display('No SMTP_DEBUG settings found, cannot continue');
		exit();
	} else {
		// Update the DEBUG OBJ
		$sObj->setValue("1");
		SettingsServices::updateSettings($sObj);
		$email = VCDUtils::getCurrentUser()->getEmail();
		$body = "This is a test message from the admin console.";
		if (VCDUtils::sendMail($email, "Test email from the VCDDB", $body, false)) {
			print "<h1>Mail successfullly sent</h1>";
		} else {
			print "<h1>Mail failed to send, review the above log for further information.</h1>";
		}
		
		// Change back the settings KEY ...
		$sObj->setValue("0");
		SettingsServices::updateSettings($sObj);
	}
	
	// Set error reporting to normal
	error_reporting(1);
	
?>

</body>
</html>