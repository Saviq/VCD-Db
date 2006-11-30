<h1><?=VCDLanguage::translate('addmovie.listed')?></h1>
<?
	if (!isset($_SESSION['listed'])) {
		redirect();
		exit();
	} else {
		$id_list = $_SESSION['listed'];
		// Clean the id's from session and memory
		session_unregister('listed');
		unset($_SESSION['listed']);
	}

	$movies = MovieServices::getVcdForListByIds($id_list);
?>


<p class="bold"><?=VCDLanguage::translate('addmovie.listedstep2')?></p>

<br/>

<form method="POST" action="./exec_form.php?action=listedconfirm">
<table cellpadding="1" cellspacing="1" border="0" width="100%" class="displist">
<tr>
	<td class="header" width="80%"><?=VCDLanguage::translate('movie.title')?></td>
	<td class="header" nowrap="nowrap"><?=VCDLanguage::translate('movie.mediatype')?></td>
	<td class="header" nowrap="nowrap"><?=VCDLanguage::translate('movie.num')?></td>
</tr>
	<? 
		$i = 0;
	
		foreach ($movies as $movie) {
			print "<tr>";
			print "<td>".$movie->getTitle()."</td>";
			
			print "<td>";
			print "<select name=\"item_".$i."\" size=\"1\">";
			print "<option value=\"null\">".VCDLanguage::translate('misc.select')."</option>";
			foreach (SettingsServices::getAllMediatypes() as $mediaTypeObj) {
				
				print "<option value=\"".$movie->getId()."|".$mediaTypeObj->getmediaTypeID()."\">".$mediaTypeObj->getDetailedName()."</option>";
				if ($mediaTypeObj->getChildrenCount() > 0) {
					foreach ($mediaTypeObj->getChildren() as $childObj) { 
						print "<option value=\"".$movie->getID()."|".$childObj->getmediaTypeID()."\">&nbsp;&nbsp;".$childObj->getDetailedName()."</option>";
					}
				}
				
			}
			
			print "</select></td>";
			
			print "<td><select name=\"cds_".$i."\"><option value=\"1\">1</option><option value=\"2\">2</option><option value=\"3\">3</option><option value=\"4\">4</option></select></td></tr>";
			$i++;
		}
	?>
</table>
<p align="right" style="padding-right:85px;">
	<input type="hidden" name="disccount" value="<?=$i?>"/>
	<input type="submit" value="<?=VCDLanguage::translate('misc.confirm')?>" onclick="return confirmListed(this.form)"/>
</p>

</form>

<? 
	unset($movies);
?>