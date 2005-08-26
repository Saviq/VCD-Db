<?php 
	
	$objArr = array ("id"=>"", "name"=>"", "url"=>"");
	$readonly = "";	
	$button_value = "Save";
	$parent_id = "";

	
	if (strcmp($WORKING_MODE, "edit") == 0) {
		if ((!isset($_GET['recordID'])) || (!is_numeric($_GET['recordID']))) {
			print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
			exit();
		}
		
		
		$feed = $SETTINGSclass->getRssfeed($_GET['recordID']);	
		
		
		$objArr['id']	= $feed['id'];
		$objArr['name'] = $feed['name'];
		$objArr['url'] 	= $feed['url'];
		
		
		$readonly = "readonly";	
		$button_value = "Update";
		
		
	}
	
	
?>
<div id="newObj" style="display: none;">
<form name="new" method="POST">
<? 
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
	<td colspan="2"><INPUT type="submit" value="<?=$button_value?>" name="<?=strtolower($button_value)?>" class="save"></td>
</tr>
</table>
				
</form>
<h1></h1>
</div>