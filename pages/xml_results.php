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
	
	<p><span class="bold"><? printf($language->show('XML_RESULTS2'), sizeof($xmlResults)) ?></span></p>
	<br/><br/>
	<table cellpadding="1" cellspacing="1" border="0" width="100%" class="displist">
	<tr>
		<td class="bold"><?=$language->show('X_STATUS')?></td><td class="bold"><?=$language->show('M_TITLE')?></td><td class="bold">Thumbnail</td>
	</tr>
	<?
		foreach ($xmlResults as $resultArr) {
			$status = $resultArr['status'];
			$title = $resultArr['title'];
			$thumbs = $resultArr['thumb'];
			
			if ($status == 1) {
				$strStatus = $language->show('X_SUCCESS');
			} else {
				$strStatus = "<span style=\"color:red\">".$language->show('X_FAILURE')."</a>";
			}
			
			if ($thumbs == 1) {
				$strThumbs = $language->show('X_YES');
			} else {
				$strThumbs = $language->show('X_NO');
			}
			
			print "<tr><td>".$strStatus."</td><td>".$title."</td><td>".$strThumbs."</td></tr>";
			
		}
	?>
	</table>
	
	
	<br/><br/>
	
	

<? } ?>