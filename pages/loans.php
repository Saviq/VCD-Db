<? 
	
	$CLASSvcd = VCDClassFactory::getInstance("vcd_movie");
	$SETTINGSclass = VCDClassFactory::getInstance("vcd_settings");
	$arrVcd = $CLASSvcd->getAllVcdByUserId(VCDUtils::getUserID());
	$arrBorrowers = $SETTINGSclass->getBorrowersByUserID(VCDUtils::getUserID());
	$arrLoans = $SETTINGSclass->getLoans(VCDUtils::getUserID(), false);
	
	if (is_array($arrLoans) && sizeof($arrLoans) > 0 && sizeof($arrVcd) > 0) {
		$arrVcd = filterLoanList($arrVcd, $arrLoans);
	}

?>
<h2><?=$language->show('MENU_LOANSYSTEM')?></h2>

<form method="post" name="loans" action="./exec_form.php?action=loan">
<input type="hidden" name="id_list"/>
<INPUT TYPE="hidden" NAME="keys" VALUE=""/>
<table cellspacing="0" cellpadding="2" border="0">
<tr>
	<td><h2><?=$language->show('MENU_MOVIES')?></h2>
	<select name="available" id="myitems" size=16 style="width:300px;" onDblClick="moveOver(this.form);clr();" onKeyPress="selectKeyPress();" onKeyDown="onSelectKeyDown();" onBlur="clr();" onFocus="clr();" class="inp">
		<?
		foreach ($arrVcd as $vcdObj) {
			print "<option value=\"".$vcdObj->getID()."\">" .  $vcdObj->getTitle() . " " . $vcdObj->showMediaTypes() . "</option>";		}	
		?>
	</select>
	</td>
	<td>
	<input type="button" value="&gt;&gt;" onclick="moveOver(this.form);clr();" class="input" style="margin-bottom:5px;"/><br/>
	<input type="button" value="<<" onclick="removeMe(this.form);clr();" class="input"/>
	</td>
	<td>
	<p><br/><br/><br/>
	<h2><?=$language->show('LOAN_MOVIES')?></h2>
	<select multiple name="choiceBox" style="width:300px;" size="8" class="inp" onDblClick="removeMe(this.form)"></select>
	
	<br/>
	<h2><?=$language->show('LOAN_TO')?></h2>
	
	<? 
		if (sizeof($arrBorrowers) == 0) {
			print "<ul><li>".$language->show('LOAN_ADDUSERS')."</li></ul><br/>";
			
		} else {
			print "<select name=\"borrowers\" size=1\">";
			print "<option value=\"null\">".$language->show('LOAN_SELECT')."</option>";
					foreach ($arrBorrowers as $obj) {
						$arr = $obj->getList();
						print "<option value=\"".$arr['id']."\">".$arr['name']."</option>";
					}
					unset($arr);
			print "</select>";
		}
	
	?>
	<input type="button" value="<?=$language->show('LOAN_NEWUSER')?>" onclick="createBorrower()"/>
	<? 
		if (sizeof($arrBorrowers) > 0) {
			print "<input type=\"submit\" value=\"".$language->show('X_CONFIRM')."\" onClick=\"return checkFields(this.form)\"/>";
		}
	?>
	</p>
	</td>			
</tr>
</table>
<? 
	print "<div align=\"right\" class=\"info\">".VCDUtils::getMessage()."</div>";

?>
<br/>

<? 
	if (sizeof($arrLoans) > 0) {
		print "<h2>".$language->show('LOAN_MOVIELOANS')."</h2>";
		print "<table cellspacing=0 cellpadding=0 border=0 width=\"100%\" class=\"list\">";
		
		$lastborrower_name = '';
		$loancounter = 1;
		foreach ($arrLoans as $loanObj) {
	
			if (strcmp($lastborrower_name, $loanObj->getBorrowerName()) != 0) {
				print "<tr><td colspan=6><strong>".$loanObj->getBorrowerName()."</strong> | <a href=\"exec_query.php?action=reminder&bid=".$loanObj->getBorrowerID()."\">".$language->show('LOAN_REMINDER')."</a> | <a href=\"./?page=private&o=loans&history=".$loanObj->getBorrowerID()."\">".$language->show('LOAN_HISTORY2')."</a></td></tr>";				
				print "<tr><td></td><td class=header>Nr.</td><td class=header>".$language->show('M_TITLE').":</td><td class=header>".$language->show('LOAN_SINCE').":</td><td class=header>".$language->show('LOAN_TIME').":</td><td class=header>&nbsp;</td></tr>";
				$loancounter = 1;
			}
			
			print "<tr><td>&nbsp;</td><td>".$loancounter."</td><td> <a href=\"./?page=cd&vcd_id=".$loanObj->getCDId()."\">".$loanObj->getCDTitle()."</a></td><td>".date("d/m/Y", $loanObj->getDateOut())."</td><td>".VCDUtils::getDaydiff($loanObj->getDateOut())."</td><td><a href=\"#\" onclick=\"returnloan(".$loanObj->getLoanID().")\">[".$language->show('LOAN_RETURN')."]</a></td></tr>";		
			
			$lastborrower_name = $loanObj->getBorrowerName();
			$loancounter++;
			
		}
		print "</table>";
	}
	
	
?>






</form>