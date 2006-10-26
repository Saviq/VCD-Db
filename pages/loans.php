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
<h2><?=VCDLanguage::translate('menu.loansystem')?></h2>

<form method="post" name="loans" action="./exec_form.php?action=loan">
<input type="hidden" name="id_list"/>
<INPUT TYPE="hidden" NAME="keys" VALUE=""/>
<table cellspacing="0" cellpadding="2" border="0">
<tr>
	<td><h2><?=VCDLanguage::translate('menu.movies')?></h2>
	<select name="available" id="available" size=16 style="width:300px;" onDblClick="moveOver(this.form, 'available', 'choiceBox');clr();" onKeyPress="selectKeyPress();" onKeyDown="onSelectKeyDown();" onBlur="clr();" onFocus="clr();" class="inp">
		<?
		foreach ($arrVcd as $vcdObj) {
			print "<option value=\"".$vcdObj->getID()."\">" .  $vcdObj->getTitle() . " " . $vcdObj->showMediaTypes() . "</option>";		}	
		?>
	</select>
	</td>
	<td>
	<input type="button" value="&gt;&gt;" onclick="moveOver(this.form, 'available', 'choiceBox');clr();" class="input" style="margin-bottom:5px;"/><br/>
	<input type="button" value="<<" onclick="removeMe(this.form, 'available', 'choiceBox');clr();" class="input"/>
	</td>
	<td>
	<p><br/><br/><br/>
	<h2><?=VCDLanguage::translate('loan.movies')?></h2>
	<select multiple name="choiceBox" id="choiceBox" style="width:300px;" size="8" class="inp" onDblClick="removeMe(this.form, 'available', 'choiceBox')"></select>
	
	<br/>
	<h2><?=VCDLanguage::translate('loan.to')?></h2>
	
	<? 
		if (sizeof($arrBorrowers) == 0) {
			print "<ul><li>".VCDLanguage::translate('loan.addusers')."</li></ul><br/>";
			
		} else {
			print "<select name=\"borrowers\" size=1\">";
			print "<option value=\"null\">".VCDLanguage::translate('loan.select')."</option>";
					foreach ($arrBorrowers as $obj) {
						$arr = $obj->getList();
						print "<option value=\"".$arr['id']."\">".$arr['name']."</option>";
					}
					unset($arr);
			print "</select>";
		}
	
	?>
	<input type="button" value="<?=VCDLanguage::translate('loan.newuser')?>" onclick="createBorrower()"/>
	<? 
		if (sizeof($arrBorrowers) > 0) {
			print "<input type=\"submit\" value=\"".VCDLanguage::translate('misc.confirm')."\" onClick=\"return checkFields(this.form)\"/>";
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
		print "<h2>".VCDLanguage::translate('loan.movieloans')."</h2>";
		print "<table cellspacing=0 cellpadding=0 border=0 width=\"100%\" class=\"list\">";
		
		$lastborrower_name = '';
		$loancounter = 1;
		foreach ($arrLoans as $loanObj) {
	
			if (strcmp($lastborrower_name, $loanObj->getBorrowerName()) != 0) {
				print "<tr><td colspan=6><strong>".$loanObj->getBorrowerName()."</strong> | <a href=\"exec_query.php?action=reminder&bid=".$loanObj->getBorrowerID()."\">".VCDLanguage::translate('loan.reminder')."</a> | <a href=\"./?page=private&o=loans&history=".$loanObj->getBorrowerID()."\">".VCDLanguage::translate('loan.history2')."</a></td></tr>";
				print "<tr><td></td><td class=\"header\">Nr.</td><td class=\"header\">".VCDLanguage::translate('movie.title').":</td><td class=\"header\">".VCDLanguage::translate('loan.since').":</td><td class=\"header\">".VCDLanguage::translate('loan.time').":</td><td class=\"header\">&nbsp;</td></tr>";
				$loancounter = 1;
			}
			
			print "<tr><td>&nbsp;</td><td>".$loancounter."</td><td> <a href=\"./?page=cd&vcd_id=".$loanObj->getCDId()."\">".$loanObj->getCDTitle()."</a></td><td>".date("d/m/Y", $loanObj->getDateOut())."</td><td>".VCDUtils::getDaydiff($loanObj->getDateOut())."</td><td><a href=\"#\" onclick=\"returnloan(".$loanObj->getLoanID().")\">[".VCDLanguage::translate('loan.return')."]</a></td></tr>";		
			
			$lastborrower_name = $loanObj->getBorrowerName();
			$loancounter++;
			
		}
		print "</table>";
	}
	
	
?>






</form>