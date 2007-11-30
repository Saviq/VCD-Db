<?php
header('Content-type: application/xml');
include_once('../classes/includes.php');

$RSS = new VCDRss();
if (isset($_GET['users'])) {
	
	// Check if we are supposed to log this event ..
	if (VCDLog::isInLogList(VCDLog::EVENT_RSSCALL )) {
		VCDLog::addEntry(VCDLog::EVENT_RSSCALL , 'Call for RSS user feeds');
	}
	$xml = $RSS->getRSSUsers();
} elseif (isset($_GET['rss'])) {
	// Check if we are supposed to log this event ..
	if (VCDLog::isInLogList(VCDLog::EVENT_RSSCALL )) {
		VCDLog::addEntry(VCDLog::EVENT_RSSCALL , 'Call for RSS from user ' . $_GET['rss']);
	}
	$xml = $RSS->getRSSbyUser($_GET['rss']);
} else {
	// Check if we are supposed to log this event ..
	if (VCDLog::isInLogList(VCDLog::EVENT_RSSCALL )) {
		VCDLog::addEntry(VCDLog::EVENT_RSSCALL , 'Call site RSS list');
	}
	$xml = $RSS->getSiteRss();
}
print $xml;
?>