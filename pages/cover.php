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
<style type="text/css" media="all">@import url("../includes/templates/default/style.css");</style>
<SCRIPT>
var ns4 = (document.layers);
var ie4 = (document.all && !document.getElementById);
var ie5 = (document.all && document.getElementById);
var ns6 = (!document.all && document.getElementById);
function resize() {
	try {
    	// screen size substracted to try and fit on screen
		var x = screen.availWidth - 5;
		var y = screen.availHeight - 50;
		// image bigger than screen
		if ((window.document.image.width > x) ||
			(window.document.image.height > y)) {
			window.moveTo(0,0);
			// image aspect ratio
			var ratio = window.document.image.width / window.document.image.height;
			// if "wider" aspect ratio
			if (ratio > (screen.availWidth / screen.availHeight)) {
				window.document.image.width = x;
			// else "higher' aspect ratio
			} else {
				window.document.image.height = y;
			}
		}
		if (ie4 || ie5) {
			intwidth = window.document.image.width - window.document.body.clientWidth;
			intheight = window.document.image.height - window.document.body.clientHeight;
		} else {
			intwidth = window.document.image.width - window.innerWidth;
			intheight = window.document.image.height - window.innerHeight;
		}
		window.resizeBy(intwidth,intheight);
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
