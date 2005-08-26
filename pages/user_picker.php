<p>
<?= $language->show('MY_HELPPICKERINFO'); ?>

</p>
<br/><br/>
<div style="padding-left:10px">
<form name="picker" method="POST" action="">
<table cellpadding="1" cellspacing="1" width="100%" class="tblsmall">
<tr>
	<td><?= $language->show('MY_JOINSCAT') ?></td>
	<td><? 
		print "<select name=\"category\" size=\"1\">";
		print "<option value=\"null\">".$language->show('X_ANY')."</option>";
		foreach ($SETTINGSClass->getMovieCategoriesInUse() as $categoryObj) {
			print "<option value=\"".$categoryObj->getID()."\">".$categoryObj->getName()."</option>";
		}
		print "</select>"; ?></td>
</tr>
<? if ($_SESSION['user']->getPropertyByKey(vcd_user::$PROPERTY_SEEN))  { ?>
<tr>
	<td><?=$language->show('MY_NOTSEEN')?></td>
	<td><input type="checkbox" name="onlynotseen" value="1" class="nof"/></td>
</tr>
<? } ?>
<tr>
	<td>&nbsp;</td>
	<td><input type="button" name="search" value="<?=$language->show('MY_FIND')?>" class="buttontext" onclick="showSuggestion(this.form)"/></td>
</tr>
</table>
</form>

<br/><br/>

<iframe src="pages/user_suggestion.php" name="suggestion" id="suggestion" width="100%" height="400" marginwidth="0" marginheight="0" scrolling="no" frameborder="0"></iframe>

</div>

