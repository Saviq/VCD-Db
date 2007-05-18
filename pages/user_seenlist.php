<? 
	
	$numRecords = 25;	
	$batch = 1;
	$start = 0;
		
	$allPostedFields = array();
	$allEditedFields = array();
	if (isset($_POST['allIds'])) {
		$allPostedFields = explode("|", $_POST['allIds']);
	}
	
	
	$user_id = VCDUtils::getUserID();
	if (isset($_POST['save'])) {
		// Loop through the posted values
		foreach ($_POST['seenlist'] as $key => $value) {
			if (is_numeric($key) && (strcmp($value, "") != 0)) {
				// check for existing data
				$arr = SettingsServices::getMetadata($value, $user_id, metadataTypeObj::SYS_SEENLIST );
				if (is_array($arr) && sizeof($arr) == 1) {
					// update the Obj
					$obj = $arr[0];
					$obj->setMetadataValue('1');
					SettingsServices::updateMetadata($obj);
				} else {
					// create new Obj
					$obj = new metadataObj(array('',$value, $user_id, metadataTypeObj::SYS_SEENLIST , '1'));
					SettingsServices::addMetadata($obj);
				}
				
				array_push($allEditedFields, $value);
			}
		}
		
		
		// Then update the ones who were not selected.
		foreach ($allPostedFields as $itemId) {
			if (!in_array($itemId, $allEditedFields)) {
				$obj = new metadataObj(array('',$itemId, $user_id, metadataTypeObj::SYS_SEENLIST , '0'));
				SettingsServices::addMetadata($obj);
			}
		}
	}
	
	
	if (isset($_GET['batch'])) {
		$batch = $_GET['batch'];
		$end = $numRecords*$batch;
		$start = $end - $numRecords;
	} else {
		$end = $numRecords;
	}
		
	$batch++;
	
	
?>

<form name="customkeys" method="post" action="./?page=private&o=movies&do=seenlist&batch=<?=$batch?>">
<table cellpadding="1" cellspacing="1" border="0" width="100%" class="tblsmall">
<tr>
	<td class="bold" width="65%"><?=VCDLanguage::translate('movie.title')?></td>
	<td class="bold"><?=VCDLanguage::translate('movie.media')?></td>
	<td class="bold" width="5%"><?=VCDLanguage::translate('misc.seen')?></td>
</tr>
<? 


$arrMovies = MovieServices::getAllVcdByUserId($user_id, true);
$currRecordCount = 0;
if ($end > sizeof($arrMovies)) {
	$end = sizeof($arrMovies);
}

if ($start > $end) {
	redirect('?page=private&o=movies&do=seenlist');
}

$strAllIds = "";

for ($j = $start; $j < $end; $j++) {
	$obj = $arrMovies[$j];
	$currRecordCount++;
	$arr = SettingsServices::getMetadata($obj->getID(), $user_id, 'seenlist');
	$checked = "";
	if (is_array($arr) && sizeof($arr) == 1 && (strcmp($arr[0]->getMetadataValue(), "1")==0)) {
		$checked = "checked=\"checked\"";
	}
	
	print "<tr>";
	print "<td>".$obj->getTitle()."</td>";
	print "<td nowrap=\"nowrap\">".$obj->showMediaTypes()."</td>";
	print "<td align=\"right\"><input type=\"checkbox\" ".$checked." size=\"3\" class=\"nof\" value=\"".$obj->getID()."\" class=\"inp\" name=\"seenlist[]\"/></td>";
	print "</tr>";
	
	if ($j == ($end-1)) {
		$strAllIds .= $obj->getID();
	} else {
		$strAllIds .= $obj->getID()."|";
	}
	
	
}

	print "<tr><td>";
	print "<select name=\"sellist\" onchange=\"showPage(this, false)\">";
	
	$from = 1;
	$to = $numRecords;
	do {
		
		$key = ((($from-1)/$numRecords)+1);
		if ($batch == 1) {
			print "<option value=\"./?page=private&amp;o=movies&amp;do=seenlist&amp;batch=".$key."\" selected=\"selected\">".$from. " - " . $to."</option>\n";
		} else {
			if ($key == ($batch-1) && ($batch-1) != 1) {
				print "<option value=\"./?page=private&amp;o=movies&amp;do=seenlist&amp;batch=".$key."\" selected=\"selected\">".$from. " - " . $to."</option>\n";
			} else {
				print "<option value=\"./?page=private&amp;o=movies&amp;do=seenlist&amp;batch=".$key."\">".$from. " - " . $to."</option>\n";
			}
		}
		$from += $numRecords;
		$to += $numRecords;
		
	} while ($to < sizeof($arrMovies));
	
	print "</select>";
	
	if ($currRecordCount < $numRecords) {
		$savetext = VCDLanguage::translate('misc.save');
	} else {
		$savetext = VCDLanguage::translate('misc.savenext'). " " . $numRecords;
	}
	
	print "</td><td colspan=\"2\" align=\"right\"><input type=\"submit\" class=\"inp\" name=\"save\" value=\"".$savetext."\"></td></tr>";



?>


</table>
<input type="hidden" name="allIds" value="<?php echo $strAllIds?>"/>
</form>