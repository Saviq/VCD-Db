<?php
require_once("classes/includes.php");
global $ClassFactory;
$IMGclass = $ClassFactory->getInstance("VCDImage");
$image_id = $_GET['id'];
Header ('Content-type: image/pjpeg');
Header ('Content-Disposition: inline; filename=mynd.jpg');  
print $IMGclass->getImageStream($image_id);
?>

