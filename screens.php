<?
require_once("classes/includes.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Screens</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<link rel="stylesheet" type="text/css" href="includes/templates/default/style.css"/>
</head>
<body>

<?
	$screen_id = $_GET['s_id'];
	$s = new VCDScreenshot($screen_id);
	if (isset($_GET['slide'])) {
		$s->setPage($_GET['slide']);
	} 
	
	if (isset($_GET['image_id'])) {
		$s->showImage($_GET['image_id']);
	} else {
		$s->showPage();
	}
	
?>

</body>
</html>