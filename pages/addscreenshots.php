<?
	require_once("../classes/includes.php");

	if (!(VCDUtils::isLoggedIn() && isset($_GET['id']) && is_numeric($_GET['id']))) {
		print "<script>window.close()</script>";
		exit();
	}
	
	
	$vcdObj = MovieServices::getVcdByID($_GET['id']);
	
	
	if (isset($_POST['upload'])) {
		
		try {
		
			$upload = new VCDFileUpload(array(VCDUploadedFile::FILE_GIF , VCDUploadedFile::FILE_JPG , VCDUploadedFile::FILE_JPEG ));
					
			// Check if the screenshots folder already exist
			$destFolder = VCDDB_BASE.DIRECTORY_SEPARATOR.ALBUMS.$vcdObj->getID();
			if (($upload->getFileCount() > 0) && !$vcdObj->hasScreenshots()) {
				if (!fs_is_dir($destFolder)) {
					fs_mkdir($destFolder, 0755);
				}
			}
			
			
			for ($i=0; $i<$upload->getFileCount();$i++) {
				$file = $upload->getFileAt($i);
				$file->move($destFolder);
			}
			
			if ($upload->getFileCount() > 0 && !MovieServices::getScreenshots($vcdObj->getID())) {
				MovieServices::markVcdWithScreenshots($vcdObj->getID());
			}
		
		} catch (Exception $ex) {
			VCDException::display($ex->getMessage());
		}
		
	} elseif (isset($_POST['doFetch'])) {
		
		
		try {
		
			$images = $_POST['fetcher'];
			$images = explode(chr(13), $images);
			if (sizeof($images > 0)) {
				
				// Check if the screenshots folder already exist
				$destFolder = VCDDB_BASE.DIRECTORY_SEPARATOR.ALBUMS.$vcdObj->getID().DIRECTORY_SEPARATOR;
				if (!$vcdObj->hasScreenshots()) {
					if (!fs_is_dir($destFolder)) {
						fs_mkdir($destFolder, 0755);
					}
				}
				
				foreach ($images as $image) {
					VCDUtils::grabImage(trim($image), false, $destFolder);
				}
				
				
				if (!MovieServices::getScreenshots($vcdObj->getID())) {
					MovieServices::markVcdWithScreenshots($vcdObj->getID());
				}
				
				
			}
		
		} catch (Exception $ex) {
			VCDException::display($ex->getMessage());
		}
		
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/2000/REC-xhtml1-20000126/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<title>Add Screenshots</title>
	<link rel="stylesheet" type="text/css" href="../includes/css/style.css"/>
	<meta http-equiv="Content-Type" content="text/html; charset=<?=VCDUtils::getCharSet()?>"/>
	<style type="text/css" media="screen">
		@import url(../includes/css/manager.css);
	</style>
	<script language="JavaScript" src="../includes/js/main.js" type="text/javascript"></script>
	<script language="JavaScript">
		var fileCounts  = 1;
		function addField(fNum) {
			
			if (fileCounts > fNum) {return;}
			
			var sender = 'image'+(fNum);
			if (document.getElementById(sender).value == '') {return;}
			
			var table = document.getElementById('uploadtable'); 
		    var rows = table.rows; 
		    var tbody = table.getElementsByTagName("tbody")[0];
		    var row = document.createElement("TR");
		    var cellTitle  = document.createElement("TD");
		    var cellUpload = document.createElement("TD");
		    cellTitle.innerHTML = 'Image ' + (fNum+1);
		    var fileName = 'image'+(fNum+1);
		    cellUpload.innerHTML = '<input type="file" name="'+fileName+'" id="'+fileName+'" class="input" onclick="addField('+(fNum+1)+')">';
		    row.appendChild(cellTitle);
		    row.appendChild(cellUpload);
		    tbody.appendChild(row);
		    fileCounts++;
		}
	</script>
</head>
<body>

<div style="font-weight:bold;padding:2px 0px 5px 0px;text-align:center">
Add new screenshots for <?= $vcdObj->getTitle()?>
<hr/>
<input type="button" value="Upload images" id="btnUpload" class="buttontext" onclick="hide('fetch');show('uploads')"/>
&nbsp;&nbsp;
<input type="button" value="Fetch images" id="btnFetch" class="buttontext" onclick="hide('uploads');show('fetch')"/>
<hr/>

</div>
<div id="uploads" style="padding:2px 5px 5px 5px;">
<form method="POST" action="addscreenshots.php?upload&id=<?=$vcdObj->getID()?>" enctype="multipart/form-data">
<table cellpadding="1" cellspacing="1" border="0" width="100%" id="uploadtable">
<tr>
	<td class="tlbl">Image 1</td>
	<td><input type="file" class="input" id="image1" name="image1" onclick="addField(1)"></td>
</tr>
</table>
<hr/>
<input type="submit" value="Upload images" name="upload" id="upload" class="buttontext" style="float:right">
</form>
</div>

<div id="fetch" style="visibility:hidden;display:none;padding:2px 5px 5px 5px;">
<form method="POST" action="addscreenshots.php?fetch&id=<?=$vcdObj->getID()?>">
Fetch screenshots from remote webserver, add one image per line:
<p>
<textarea id="fetcher" name="fetcher" style="width:390px;height:140px"></textarea>
<hr/>
<input type="submit" value="Fetch images" name="doFetch" id="doFetch" class="buttontext" style="float:right">
</p>
</form>
</div>


</body>
</html>