<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VCD-db</title>
	<meta http-equiv="Content-Type" content="text/html; charset={$pageCharset}"/>
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="{$pageStyle}" media="screen, projection"/>
	<link rel="stylesheet" type="text/css" href="includes/css/global.css" media="screen, projection"/>
	<link rel="stylesheet" type="text/css" href="includes/css/manager.css" media="screen, projection"/>
	{$pageScripts}
	<script type="text/javascript">
	{literal}
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
	    cellUpload.innerHTML = '<input type="file" name="'+fileName+'" id="'+fileName+'" class="input" onclick="addField('+(fNum+1)+')"/>';
	    row.appendChild(cellTitle);
	    row.appendChild(cellUpload);
	    tbody.appendChild(row);
	    fileCounts++;
	}
	{/literal}
	</script>
</head>
<body onload="window.focus()" class="nobg">

<div style="font-weight:bold;padding:2px 0px 5px 0px;text-align:center">
Add new screenshots for {$itemTitle}
<hr/>
<input type="button" value="Upload images" id="btnUpload" class="buttontext" onclick="hide('fetch');show('uploads')"/>
&nbsp;&nbsp;
<input type="button" value="Fetch images" id="btnFetch" class="buttontext" onclick="hide('uploads');show('fetch')"/>
<hr/>

</div>
<div id="uploads" style="padding:2px 5px 5px 5px;">
<form method="post" action="{$smarty.server.SCRIPT_NAME}?page=addscreens&amp;vcd_id={$itemId}&amp;action=upload" enctype="multipart/form-data">
<table cellpadding="1" cellspacing="1" border="0" width="100%" id="uploadtable">
<tr>
	<td class="tlbl">Image 1</td>
	<td><input type="file" class="input" id="image1" name="image1" onclick="addField(1)"/></td>
</tr>
</table>
<hr/>
<input type="submit" value="Upload images" name="upload" id="upload" class="buttontext" style="float:right"/>
</form>
</div>

<div id="fetch" style="visibility:hidden;display:none;padding:2px 5px 5px 5px;">
<form method="post" action="{$smarty.server.SCRIPT_NAME}?page=addscreens&amp;vcd_id={$itemId}&amp;action=fetch">
Fetch screenshots from remote webserver, add one image per line:

<textarea id="fetcher" name="fetcher" style="width:406px;height:140px"></textarea>
<hr/>
<input type="submit" value="Fetch images" name="doFetch" id="doFetch" class="buttontext" style="float:right"/>

</form>
</div>

</body>
</html>