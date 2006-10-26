<h1>XML upload results</h1>
<? 
	if (!VCDUtils::isLoggedIn()) {
		redirect();
	}
	
	// Check if this is from the right source
	if (!isset($_SESSION['xmlresults'])) {
		redirect();
	} else {
		$xmlResults = $_SESSION['xmlresults'];
				
		
		// Clean the results from session and memory
		session_unregister('xmlresults');
		unset($_SESSION['xmlresults']);
	}

	if (!is_array($xmlResults) || sizeof($xmlResults) == 0) {
		print "<p>Upload failed, unknown errors. </p>";
		
	} else {
	?>
	
	<p><span class="bold"><? printf(VCDLanguage::translate('xml.results2'), sizeof($xmlResults)) ?></span></p>
	<br/><br/>
	<table cellpadding="1" cellspacing="1" border="0" width="100%" class="displist">
	<tr>
		<td class="bold"><?=VCDLanguage::translate('misc.status')?></td><td class="bold"><?=VCDLanguage::translate('movie.title')?></td><td class="bold">Thumbnail</td>
	</tr>
	<?
		foreach ($xmlResults as $resultArr) {
			$status = $resultArr['status'];
			$title = $resultArr['title'];
			$thumbs = $resultArr['thumb'];
			
			if ($status == 1) {
				$strStatus = VCDLanguage::translate('misc.success');
			} else {
				$strStatus = "<span style=\"color:red\">".VCDLanguage::translate('misc.failure')."</a>";
			}
			
			if ($thumbs == 1) {
				$strThumbs = VCDLanguage::translate('misc.yes');
			} else {
				$strThumbs = VCDLanguage::translate('misc.no');
			}
			
			print "<tr><td>".$strStatus."</td><td>".$title."</td><td>".$strThumbs."</td></tr>";
			
		}
	?>
	</table>
	
	
	<br/><br/>
	
	

<? } ?>