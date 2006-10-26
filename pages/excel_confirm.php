<h1><?=VCDLanguage::translate('EXCEL_CONFIRM')?></h1>
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
		print "<p>".VCDLanguage::translate('excel_ERROR')."</p>";
	} else {
	?>
	
	<p><span class="bold"><? printf(VCDLanguage::translate('EXCEL_CONTAINS'), sizeof($exceltitles))?></span>
	<br/><?=VCDLanguage::translate('EXCEL_INFO1')?>
	<br/><br/>
	<form name="excelconfirm" method="post" action="exec_form.php?action=excelconfirm" enctype="multipart/form-data">
		<input type="submit" class="input" value="<?=VCDLanguage::translate('X_CONFIRM')?>" onclick="return checkEXCELConfirm(this.form)"/>&nbsp; <input type="button" onclick="clearEXCEL('<?=$excelfile?>')" value="<?=VCDLanguage::translate('X_CANCEL')?>" class="input"/>
		<input type="hidden" name="filename" value="<?=$excelfile?>"/>
	
	</p>
		
	<p><span class="bold"><?=VCDLanguage::translate('EXCEL_LIST')?></span></p>
	
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