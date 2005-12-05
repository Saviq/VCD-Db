<? 
	if (!VCDUtils::isLoggedIn()) {
		VCDException::display("User must be logged in");
		exit();
	}

	global $language;
	$VCDClass = VCDClassFactory::getInstance('vcd_movie');
	$movies = $VCDClass->getAllVcdForList(VCDUtils::getUserID());
?>

<h1><?=$language->show('ADD_LISTED')?></h1>

<? 
	if (sizeof($movies) == 0) {
		print "<p>".$language->show('ADD_NOTITLES')."<br/></p>";
	} else {
?>


<p class="bold"><?=$language->show('ADD_LISTEDSTEP1')?></p>

<div style="padding-left:10px;">
<form action="./exec_form.php?action=addlisted" method="post" name="choiceForm">
	<input type="hidden" name="id_list"/>
	<INPUT TYPE="hidden" NAME="keys" VALUE=""/>
	
	

	<table cellspacing="0" cellpadding="2" border="0">
	<tr>
	<td>
	<h2><?=$language->show('ADD_INDB')?></h2>
	
	<select name="available" size=20 style="width:300px;"onDblClick="moveOver(this.form, 'available', 'choiceBox');clr();" onKeyPress="selectKeyPress();" onKeyDown="onSelectKeyDown();" onBlur="clr();" onFocus="clr();">
	<?
	 
		foreach ($movies as $movie) {
			print "<option value=\"".$movie->getID()."\">".$movie->getTitle()."</option>";
		}
	
		unset($movies);
		
	?> 
	</select>
	</td>
	<td>
		<input type="button" value="&gt;&gt;" onclick="moveOver(this.form, 'available', 'choiceBox');clr();" class="input" style="margin-bottom:5px;"/><br/>
		<input type="button" value="&lt;&lt;" onclick="removeMe(this.form, 'available', 'choiceBox');" class="input"/>
	</td>
	<td><h2><?=$language->show('ADD_SELECTED')?></h2>
		<select multiple name="choiceBox" style="width:300px;" size="8" class="input"></select>
		<br/>
		<input type="submit" onClick="return checkListed(this.form)" value="<?=$language->show('X_PROCEED')?> &gt;&gt;"/>
	</td>			
	</tr>
	</table>
	<br/>
	<p><?=$language->show('ADD_INFOLIST')?></p>
	
</form>
</div>

<? } ?>