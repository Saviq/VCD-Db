<h1><?=$language->show('XML_CONFIRM')?></h1>


<script>

	function setXMLFileName() {
		var filename = document.getElementById('filename').value;
		framedoc = document.getElementById("xmlframe").contentWindow.document
		framedoc.getElementById('filename').value = filename;
		
	}

</script>

<? 
	if (!VCDUtils::isLoggedIn()) {
		redirect();
	}
	
	// Check if this is from the right source
	if (!isset($_SESSION['xmlfilename']) || !isset($_SESSION['xmldata'])) {
		redirect();
	} else {
		$xmlfile = $_SESSION['xmlfilename'];
		$xmltitles = $_SESSION['xmldata'];
		
		
		// Clean the titles from session and memory
		/*
		session_unregister('xmlfilename');
		unset($_SESSION['xmlfilename']);
		session_unregister('xmldata');
		unset($_SESSION['xmldata']);
		*/
	}

	if (!is_array($xmltitles) || sizeof($xmltitles) == 0) {
		print "<p>".$language->show('XML_ERROR')."</p>";
		
	} else {
	?>
	
	<p><span class="bold"><? printf($language->show('XML_CONTAINS'), sizeof($xmltitles))?></span>
	<br/><?=$language->show('XML_INFO1')?>
	<br/><br/>
	<form name="xmlconfirm" method="post" action="exec_form.php?action=xmlconfirm" enctype="multipart/form-data">
		<input type="submit" class="input" value="<?=$language->show('X_CONFIRM')?>" onclick="return checkXMLConfirm(this.form)"/>&nbsp; <input type="button" onclick="clearXML('<?=$xmlfile?>')" value="<?=$language->show('X_CANCEL')?>" class="input"/>
		<input type="hidden" id="filename" name="filename" value="<?=$xmlfile?>"/>
	
	</p>
		
	<p>
		<span class="bold" style="color:red"><?=$language->show('X_ATTENTION')?></span><br/>
		<?=$language->show('XML_INFO2')?>
	</p>
	<p>
		<input type="checkbox" name="xmlthumbs" onclick="showupload(this.form, 'thumbupload');" value="thumbnails" class="nof"/><?=$language->show('XML_THUMBNAILS')?>
		<div id="thumbupload" style="padding-left:20px;visibility:hidden;display:none">
		<iframe src="pages/xml_frame.php" id="xmlframe" name="xmlframe" frameborder="0" height="20"></iframe>
		</div>
	</p>
	
	
	<p><span class="bold"><?=$language->show('XML_LIST')?></span></p>
	
	<ul>
	<?
	if (is_array($xmltitles)) {
		foreach ($xmltitles as $title) {
			print "<li>".$title . "</li>";
		}
	}
	?>
	</ul>
	
	<br/><br/>
	<script>
		setXMLFileName();
	</script>
	</form>
	

<? } ?>