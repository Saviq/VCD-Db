<?
header('Content-type: application/xml');
include_once("../classes/includes.php");

$RSS = new VCDRss();
if (isset($_GET['users'])) {
	$xml = $RSS->getRSSUsers();
} elseif (isset($_GET['rss'])) {
	$xml = $RSS->getRSSbyUser($_GET['rss']);
} else {
	$xml = $RSS->getSiteRss();
}
print $xml;
?>