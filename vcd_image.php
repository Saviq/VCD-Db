<?php
require_once(dirname(__FILE__).'/config.php');
if (isset($_GET['o'])) {
	require_once('classes/external/powergraph.php');
	drawGraph($_GET['o']);
	exit();
}
$image_id = $_GET['id'];
$CLASSImage = new VCDImage($image_id);

@session_write_close();
@ob_end_clean();
header("Cache-Control: ");
header("Pragma: ");
header("Content-Type: application/octet-stream");
header("Content-Length: " .(string)($CLASSImage->getFilesize()) );
header('Content-Disposition: attachment; filename="'.$CLASSImage->getImageName().'"');
header("Content-Transfer-Encoding: binary\n");
echo $CLASSImage->getImageStream($image_id);
?>