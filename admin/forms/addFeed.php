<?php 
	
	$objArr = array ("id"=>"", "name"=>"", "url"=>"", "adult"=>"");
	$readonly = "";	
	$button_value = "Save";
	$parent_id = "";
	$check = "";

	
	if (strcmp($WORKING_MODE, "edit") == 0) {
		if ((!isset($_GET['recordID'])) || (!is_numeric($_GET['recordID']))) {
			print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
			exit();
		}
		
		
		$rssObj = SettingsServices::getRssfeed($_GET['recordID']);
		
		$objArr['id']	= $rssObj->getId();
		$objArr['name'] = $rssObj->getName();
		$objArr['url'] 	= $rssObj->getFeedUrl();
		$objArr['adult'] = $rssObj->isAdultFeed();
		
		
		$readonly = "readonly";	
		$button_value = "Update";
		if ((bool)$objArr['adult']) 
			$check = "checked";
		
	}
	
	
?>
<div id="newObj" style="display: none;">
<form name="new" method="POST" action="<?= $_SERVER['REQUEST_URI']?>">
<?php
	if (strcmp($WORKING_MODE, "edit") == 0) { 
		print "<input type=\"hidden\" name=\"id\" value=\"".$objArr['id']."\">";
	}
?>
<table class="add">
<tr>
	<td>Name:</td>
	<td><input name="name" size="40" type="text"  value="<?=$objArr['name']?>" onFocus="setBorder(this)" onBlur="clearBorder(this)"></td>
</tr>

<tr>
	<td>Url:</td>
	<td><input name="url" size="90" value="<?=$objArr['url']?>" type="text" onFocus="setBorder(this)" onBlur="clearBorder(this)"></td>
</tr>
<tr>
	<td>Is xRated:</td>
	<td><input name="isxrated" type="checkbox" value="<?=$objArr['adult']?>" <?=$check?> onFocus="setBorder(this)" onBlur="clearBorder(this)"></td>
</tr>
<tr>
	<td colspan="2"><INPUT type="submit" value="<?=$button_value?>" name="<?=strtolower($button_value)?>" class="save"></td>
</tr>
</table>
				
</form>
<h1></h1>
</div>