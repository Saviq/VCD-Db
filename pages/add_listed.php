<?
	if (!VCDUtils::isLoggedIn()) {
		VCDException::display("User must be logged in");
		exit();
	}

	$VCDClass = VCDClassFactory::getInstance('vcd_movie');
	$movies = $VCDClass->getAllVcdForList(VCDUtils::getUserID());
?>

<h1><?=VCDLanguage::translate('addmovie.listed')?></h1>

<?
	if (sizeof($movies) == 0) {
		print "<p>".VCDLanguage::translate('addmovie.notitles')."<br/></p>";
	} else {
?>


<p class="bold"><?=VCDLanguage::translate('addmovie.listedstep1')?></p>

<div style="padding-left:10px;">
<form action="./exec_form.php?action=addlisted" method="post" name="choiceForm">
	<input type="hidden" name="id_list" id="id_list"/>
	<INPUT TYPE="hidden" NAME="keys" VALUE=""/>



	<table cellspacing="0" cellpadding="2" border="0">
	<tr>
	<td>
	<h2><?=VCDLanguage::translate('addmovie.indb')?></h2>

	<select name="available" id="available" size=20 style="width:300px;"onDblClick="moveOver(this.form, 'available', 'choiceBox');clr();" onKeyPress="selectKeyPress();" onKeyDown="onSelectKeyDown();" onBlur="clr();" onFocus="clr();">
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
	<td><h2><?=VCDLanguage::translate('addmovie.selected')?></h2>
		<select multiple name="choiceBox" id="choiceBox" style="width:300px;" size="8" class="input"></select>
		<br/>
		<input type="submit" onClick="return checkListed(this.form)" value="<?=VCDLanguage::translate('misc.proceed')?> &gt;&gt;"/>
	</td>
	</tr>
	</table>
	<br/>
	<p><?=VCDLanguage::translate('addmovie.infolist')?></p>

</form>
</div>

<? } ?>