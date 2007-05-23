<div id="newObj" style="display: none;">
<form name="new" method="POST" action="<?= $_SERVER['REQUEST_URI']?>">
<table class="add">
<tr>
	<td>Studio name:</td>
	<td><input name="name" type="text" onFocus="setBorder(this)" onBlur="clearBorder(this)"></td>
</tr>
<tr>
	<td colspan="2"><INPUT type="submit" value="Save" name="save" class="save"></td>
</tr>
</table>
				
</form>
<h1></h1>
</div>