<? 

$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");
$borrowerObj = $SETTINGSClass->getBorrowerByID($_GET['history']);
if (!$borrowerObj instanceof borrowerObj ) {
	VCDException::display("Aborting query", true);
}
$loansArr = $SETTINGSClass->getLoansByBorrowerID(VCDUtils::getUserID(), $borrowerObj->getID(), true);



?>
<h2><?=language::translate('LOAN_HISTORY')?> - <?= $borrowerObj->getName() ?></h2>


<? 

	$bid = $_GET['history'];
	$arrBorrowers = $SETTINGSClass->getBorrowersByUserID(VCDUtils::getUserID());
	print "<form>&nbsp;<span class=\"bold\">".language::translate('LOAN_SELECT')." </span><select name=\"borrowers\" size=1\" onchange=\"Valmynd(this, false)\">";
			foreach ($arrBorrowers as $obj) {
				$arr = $obj->getList();
				
				$selected = "";
				if ($arr['id'] == $bid) 
					$selected = "selected";
				
				print "<option value=\"?page=private&o=loans&history=".$arr['id']."\" $selected>".$arr['name']."</option>";
			}
			unset($arr);
			print "</select>&nbsp;[<a href=\"./?page=private&amp;o=loans\">".language::translate('LOAN_BACK')."</a>]</form>";
	


		
		
	print "<br/><table width=\"100%\" cellspacing=0 cellpadding=0 border=0 class=list>";
	print "<tr><td class=header>".language::translate('M_TITLE').":</td><td class=header>".language::translate('LOAN_DATEOUT').":</td><td class=header>".language::translate('LOAN_DATEIN').":</td><td class=header>".language::translate('LOAN_PERIOD').":</td></tr>";

	foreach ($loansArr as $loanObj) {
		if ($loanObj->isReturned()) {
			print "<tr><td>".$loanObj->getCDTitle()."</td><td>".date("d/m/Y", $loanObj->getDateOut())."</td>
						<td>".date("d/m/Y", $loanObj->getDateIn())."</td><td>".VCDUtils::getDaydiff($loanObj->getDateOut(), $loanObj->getDateIn())."</td></tr>";	
		} else {
			print "<tr><td>".$loanObj->getCDTitle()."</td><td>".date("d/m/Y", $loanObj->getDateOut())."</td>
						<td style=\"color:red\">".language::translate('LOAN_OUT')."</td><td></td></tr>";	
		}
			
	}
		print "</table>";

?>