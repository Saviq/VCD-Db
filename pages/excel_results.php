<h1>Excel upload results</h1>
<? 
	if (!VCDUtils::isLoggedIn()) {
		redirect();
	}
	
	// Check if this is from the right source
	if (!isset($_SESSION['excelresults'])) {
		redirect();
	} else {
		$excelResults = $_SESSION['excelresults'];
				
		
		// Clean the results from session and memory
		session_unregister('excelresults');
		unset($_SESSION['excelresults']);
	}

	if (!is_array($excelResults) || sizeof($excelResults) == 0) {
		print "<p>Upload failed, unknown errors. </p>";
		
	} else {
	?>
	
	<p><span class="bold"><? printf(VCDLanguage::translate('EXCEL_RESULTS2'), sizeof($excelResults)) ?></span></p>
	<br/><br/>
	<table cellpadding="1" cellspacing="1" border="0" width="100%" class="displist">
	<tr>
		<td class="bold"><?=VCDLanguage::translate('X_STATUS')?></td><td class="bold"><?=VCDLanguage::translate('M_TITLE')?></td><td class="bold">Media Index</td>
	</tr>
	<?
		foreach ($excelResults as $resultArr) {
			$status = $resultArr['status'];
			$title = $resultArr['title'];
			$mediaindex = $resultArr['mediaindex'];
			
			if ($status == 1) {
				$strStatus = VCDLanguage::translate('X_SUCCESS');
			} else {
				$strStatus = "<span style=\"color:red\">".VCDLanguage::translate('X_FAILURE')."</a>";
			}
			
			if ($mediaindex > 0) {
				$strIndex = $mediaindex;
			} else {
				$strIndex = VCDLanguage::translate('X_NO');
			}
			
			print "<tr><td>".$strStatus."</td><td>".$title."</td><td>".$strIndex."</td></tr>";
			
		}
	?>
	</table>
	
	
	<br/><br/>
	
	

<? } ?>