<?
	if (isset($_GET['recordID'])) {
		global $langCLASS;
		$contents = $langCLASS->getFileContents($_GET['recordID']);
	}
?>

<style>.txt { width:550px;height:300px;}</style>
<div id="newObj" style="display: none;">
<form name="new" method="POST">
<table class="add">
<tr>
	<td>Edit language file</td>
</tr>
<tr>
	<td><textarea cols="5" rows="5" class="txt"><? print_r($contents)?></textarea></td>
</tr>
</table>
				
</form>
<h1></h1>
</div>