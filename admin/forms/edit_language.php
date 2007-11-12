<?
	if (isset($_GET['recordID'])) {
		global $langCLASS;
		$Arrcontents = $langCLASS->getFileContents($_GET['recordID']);
		$contents = $langCLASS->getRawFileContents($_GET['recordID']);
	} else {
		return;
	}
?>

<style>.txt { width:100%;height:300px;font-size:1em;font-family:arial}</style>
<div id="newObj" style="display: none;">
<form name="new" method="POST" action="<?php echo $_SERVER['REQUEST_URI']?>">
<table class="add" width="100%">
<tr>
	<td>Edit language file</td>
</tr>
<tr>
	<td>
	<?php if (isset($_GET['type']) && strcmp($_GET['type'], 'safe') == 0) {
		
		$header = array("Expression","Value");
		printTableOpen('100%',0,0);
		printRowHeader($header);
		foreach ($Arrcontents as $key => $value) {
			printTr();
			print "<td width=\"10%\" nowrap=\"nowrap\"><i>{$key}</i>" . "</td>";
			printRow("<input type=\"text\" name=\"values[{$key}]\" value=\"". str_replace("\t", "", htmlentities($value, ENT_QUOTES)) ."\" style=\"width:98%\">");
			printTr(false);
		}
		
		printTableClose();
		
		
	} else {
		?><textarea cols="5" rows="5" class="txt" name="langfile"><?php print $contents?></textarea></td><?
	}
	?>
	
</tr>
</table>
<?php
if ($langCLASS->isWriteable($_GET['recordID'])) {
	print "&nbsp;<input type=\"button\" value=\"Edit in safe mode\" onclick=SaveModeEdit({$_GET['recordID']})> &nbsp; <input type=\"submit\" value=\"Update file\" name=\"Update\">";
} else {
	print "<i style=\"padding-left:10px;color:red\">If this file ({$langCLASS->getFileName($_GET['recordID'])}) were writeable, you could edit and then save it.</i>";
}
?>		
</form>
<h1></h1>
</div>