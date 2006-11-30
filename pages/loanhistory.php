<? 

$borrowerObj = SettingsServices::getBorrowerByID($_GET['history']);
if (!$borrowerObj instanceof borrowerObj ) {
	VCDException::display("Aborting query", true);
}
$loansArr = SettingsServices::getLoansByBorrowerID(VCDUtils::getUserID(), $borrowerObj->getID(), true);



?>
<h2><?=VCDLanguage::translate('loan.history')?> - <?= $borrowerObj->getName() ?></h2>


<? 

	$bid = $_GET['history'];
	$arrBorrowers = SettingsServices::getBorrowersByUserID(VCDUtils::getUserID());
	print "<form>&nbsp;<span class=\"bold\">".VCDLanguage::translate('loan.select')." </span><select name=\"borrowers\" size=1\" onchange=\"Valmynd(this, false)\">";
			foreach ($arrBorrowers as $obj) {
				$arr = $obj->getList();
				
				$selected = "";
				if ($arr['id'] == $bid) 
					$selected = "selected";
				
				print "<option value=\"?page=private&o=loans&history=".$arr['id']."\" $selected>".$arr['name']."</option>";
			}
			unset($arr);
			print "</select>&nbsp;[<a href=\"./?page=private&amp;o=loans\">".VCDLanguage::translate('loan.back')."</a>]</form>";
	


		
		
	print "<br/><table width=\"100%\" cellspacing=0 cellpadding=0 border=0 class=list>";
	print "<tr><td class=\"header\">".VCDLanguage::translate('movie.title').":</td><td class=\"header\">".VCDLanguage::translate('loan.dateout').":</td><td class=\"header\">".VCDLanguage::translate('loan.datein').":</td><td class=\"header\">".VCDLanguage::translate('loan.period').":</td></tr>";

	foreach ($loansArr as $loanObj) {
		if ($loanObj->isReturned()) {
			print "<tr><td>".$loanObj->getCDTitle()."</td><td>".date("d/m/Y", $loanObj->getDateOut())."</td>
						<td>".date("d/m/Y", $loanObj->getDateIn())."</td><td>".VCDUtils::getDaydiff($loanObj->getDateOut(), $loanObj->getDateIn())."</td></tr>";	
		} else {
			print "<tr><td>".$loanObj->getCDTitle()."</td><td>".date("d/m/Y", $loanObj->getDateOut())."</td>
						<td style=\"color:red\">".VCDLanguage::translate('loan.out')."</td><td></td></tr>";	
		}
			
	}
		print "</table>";

?>