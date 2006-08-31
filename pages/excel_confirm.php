<h1><?=language::translate('EXCEL_CONFIRM')?></h1>
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
		print "<p>".language::translate('excel_ERROR')."</p>";
	} else {
	?>
	
	<p><span class="bold"><? printf(language::translate('EXCEL_CONTAINS'), sizeof($exceltitles))?></span>
	<br/><?=language::translate('EXCEL_INFO1')?>
	<br/><br/>
	<form name="excelconfirm" method="post" action="exec_form.php?action=excelconfirm" enctype="multipart/form-data">
		<input type="submit" class="input" value="<?=language::translate('X_CONFIRM')?>" onclick="return checkEXCELConfirm(this.form)"/>&nbsp; <input type="button" onclick="clearEXCEL('<?=$excelfile?>')" value="<?=language::translate('X_CANCEL')?>" class="input"/>
		<input type="hidden" name="filename" value="<?=$excelfile?>"/>
	
	</p>
		
	<p><span class="bold"><?=language::translate('EXCEL_LIST')?></span></p>
	
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