<?
	/* File system functions */
	if (strcmp(strtolower(PHP_OS), "winnt") == 0) {
		require_once('../classes/external/fs_win32.php');
	} else {
		require_once('../classes/external/fs_unix.php');
	}
?>
<HTML>
<HEAD>
<TITLE>-&lt;::Cover::&gt;-</TITLE>
<style type="text/css" media="all">@import url("../includes/templates/default/css/style.css");</style>
<SCRIPT>
function resize() {
	try {
		intwidth = window.document.image.width;
		intheight = window.document.image.height;
		window.resizeTo(intwidth,intheight) 
	} catch (Exception) {}
	
}
</SCRIPT>
</HEAD>
<BODY onLoad="window.focus();resize();" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<? 
$picture = $_GET['pic'];
$isindb = $_GET['db'];
$image_id = $_GET['id'];
if(isset($picture)) {
	$picture = "../upload/covers/" . $picture;
	if ((bool)$isindb && $image_id > 0) {
		print("<a href=\"javascript:self.close()\"><img src=\"../vcd_image.php?id=".$image_id."\" name=\"image\" border=\"0\"></a>");
	} elseif (fs_file_exists($picture)) {
		print("<a href=\"javascript:self.close()\"><img src=\"".$picture."\" name=\"image\" border=\"0\"></a>");
	} else {
		print "<p></p><br><br><br><br><center><strong>File not found!</strong><br/><br/><input type=\"button\" value=\"Close Window\" onClick=\"window.close()\"></center>";
	}
	
	
	
} 
?>
</BODY>
</HTML>
 