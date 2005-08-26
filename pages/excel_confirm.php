<h1><?=$language->show('EXCEL_CONFIRM')?></h1>
<? 
	if (!VCDUtils::isLoggedIn()) {
		redirect();
	}
	
	// Check if this is from the right source
	if (!isset($_SESSION['excelfilename']) || !isset($_SESSION['exceldata'])) {
		redirect();
	} else {
		$excelfile = $_SESSION['excelfilename'];
		$exceltitles = $_SESSION['exceldata'];
		
		
		// Clean the titles from session and memory
		session_unregister('excelfilename');
		unset($_SESSION['excelfilename']);
		session_unregister('exceldata');
		unset($_SESSION['exceldata']);
	}

	if (!is_array($exceltitles) || sizeof($exceltitles) == 0) {
		print "<p>".$language->show('excel_ERROR')."</p>";
	} else {
	?>
	
	<p><span class="bold"><? printf($language->show('EXCEL_CONTAINS'), sizeof($exceltitles))?></span>
	<br/><?=$language->show('EXCEL_INFO1')?>
	<br/><br/>
	<form name="excelconfirm" method="post" action="exec_form.php?action=excelconfirm" enctype="multipart/form-data">
		<input type="submit" class="input" value="<?=$language->show('X_CONFIRM')?>" onclick="return checkEXCELConfirm(this.form)"/>&nbsp; <input type="button" onclick="clearEXCEL('<?=$excelfile?>')" value="<?=$language->show('X_CANCEL')?>" class="input"/>
		<input type="hidden" name="filename" value="<?=$excelfile?>"/>
	
	</p>
		
	<p><span class="bold"><?=$language->show('EXCEL_LIST')?></span></p>
	
	<ul>
	<?
	if (is_array($exceltitles)) {
		foreach ($exceltitles as $title) {
			print "<li>".$title . "</li>";
		}
	}
	?>
	</ul>
	
	<br/><br/>
	</form>
	

<? } ?>