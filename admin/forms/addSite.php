<?php 
	
	$objArr = array ("id"=>"", "name"=>"", "alias"=>"", "homepage"=>"", "command"=>"", "isFetchable"=>"", "classname"=>"", "image"=>"");
	$readonly = "";	
	$button_value = "Save";
	$check = "";
	
	if (strcmp($WORKING_MODE, "edit") == 0) {
		if ((!isset($_GET['recordID'])) || (!is_numeric($_GET['recordID']))) {
			print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
			exit();
		}
		
		
		$sourceSiteObj = SettingsServices::getSourceSiteByID($_GET['recordID']);	
		$objArr['name']   = $sourceSiteObj->getName();
		$objArr['alias']   = $sourceSiteObj->getAlias();
		$objArr['homepage']   = $sourceSiteObj->getHomepage();
		$objArr['command']   = $sourceSiteObj->getCommand();
		$objArr['isFetchable']   = $sourceSiteObj->isFetchable();
		$objArr['classname']   = $sourceSiteObj->getClassName();
		$objArr['image']   = $sourceSiteObj->getImage();
		if ((bool)$objArr['isFetchable']) 
			$check = "checked";
		
		
		$button_value = "Update";
		
		
	}
	
	
?>
<div id="newObj" style="display: none;">
<form name="new" method="POST" action="<?php echo $_SERVER['REQUEST_URI']?>">
<?php
	if (strcmp($WORKING_MODE, "edit") == 0) { 
		print "<input type=\"hidden\" name=\"id\" value=\"".$sourceSiteObj->getsiteID()."\">";
	}
?>
<table class="add">
<tr>
	<td>Name:</td>
	<td><input name="name" type="text" value="<?php echo $objArr['name']?>"  onFocus="setBorder(this)" onBlur="clearBorder(this)" <?php if (strcmp($WORKING_MODE, "edit") == 0){ print "readonly"; }?>></td>
</tr>
<tr>
	<td>Alias:</td>
	<td><input name="alias" type="text" value="<?php echo $objArr['alias']?>"  onFocus="setBorder(this)" onBlur="clearBorder(this)"></td>
</tr>
<tr>
<tr>
	<td>Homepage:</td>
	<td><input name="homepage" type="text" size="40"  value="<?php echo $objArr['homepage']?>"  onFocus="setBorder(this)" onBlur="clearBorder(this)"></td>
</tr>
<tr>
	<td>Fetch Url:</td>
	<td><input name="command" type="text" size="60" value="<?php echo $objArr['command']?>"  onFocus="setBorder(this)" onBlur="clearBorder(this)"></td>
</tr>
<tr>
	<td>Class name:</td>
	<td><input name="classname" type="text" size="60" title="The PHP classname, Case Sensetive, Class must reside in folder 'classes/fetch'" value="<?php echo $objArr['classname']?>"  onFocus="setBorder(this)" onBlur="clearBorder(this)"></td>
</tr>
<tr>
	<td>Image path:</td>
	<td valign="absmiddle"><input name="imagename" type="text" size="44" value="<?php echo $objArr['image']?>" title="Image must reside in folder 'images/logos/'" onFocus="setBorder(this)" onBlur="clearBorder(this)"></td>
</tr>
<tr>
	<td>Is fetchable:</td>
	<td><input name="isFetchable" type="checkbox" value="<?php echo $objArr['isFetchable']?>" <?php echo $check?> onFocus="setBorder(this)" onBlur="clearBorder(this)"></td>
</tr>
<tr>
	<td colspan="2"><INPUT type="submit" value="<?php echo $button_value?>" name="<?php echo strtolower($button_value)?>" class="save"></td>
</tr>
</table>
				
</form>
<h1></h1>
</div>