<? 

$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");
$borrowerObj = $SETTINGSClass->getBorrowerByID($_GET['history']);
if (!$borrowerObj instanceof borrowerObj ) {
	VCDException::display("Aborting query", true);
}
$loansArr = $SETTINGSClass->getLoansByBorrowerID($_SESSION['user']->getUserID(), $borrowerObj->getID(), true);



?>
<h2><?=$language->show('LOAN_HISTORY')?> - <?= $borrowerObj->getName() ?></h2>


<? 

	$bid = $_GET['history'];
	$arrBorrowers = $SETTINGSClass->getBorrowersByUserID($_SESSION['user']->getUserID());
	print "<form>&nbsp;<span class=\"bold\">".$language->show('LOAN_SELECT')." </span><select name=\"borrowers\" size=1\" onchange=\"Valmynd(this, false)\">";
			foreach ($arrBorrowers as $obj) {
				$arr = $obj->getList();
				
				$selected = "";
				if ($arr['id'] == $bid) 
					$selected = "selected";
				
				print "<option value=\"?page=private&o=loans&history=".$arr['id']."\" $selected>".$arr['name']."</option>";
			}
			unset($arr);
			print "</select>&nbsp;[<a href=\"./?page=private&amp;o=loans\">".$language->show('LOAN_BACK')."</a>]</form>";
	


		
		
	print "<br/><table width=\"100%\" cellspacing=0 cellpadding=0 border=0 class=list>";
	print "<tr><td class=header>".$language->show('M_TITLE').":</td><td class=header>".$language->show('LOAN_DATEOUT').":</td><td class=header>".$language->show('LOAN_DATEIN').":</td><td class=header>".$language->show('LOAN_PERIOD').":</td></tr>";

	foreach ($loansArr as $loanObj) {
		if ($loanObj->isReturned()) {
			print "<tr><td>".$loanObj->getCDTitle()."</td><td>".date("d/m/Y", $loanObj->getDateOut())."</td>
						<td>".date("d/m/Y", $loanObj->getDateIn())."</td><td>".VCDUtils::getDaydiff($loanObj->getDateOut(), $loanObj->getDateIn())."</td></tr>";	
		} else {
			print "<tr><td>".$loanObj->getCDTitle()."</td><td>".date("d/m/Y", $loanObj->getDateOut())."</td>
						<td style=\"color:red\">".$language->show('LOAN_OUT')."</td><td></td></tr>";	
		}
			
	}
		print "</table>";

?>