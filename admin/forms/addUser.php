<div id="newObj" style="display: none;">
<form name="new" method="POST" action="<?= $_SERVER['REQUEST_URI']?>">

<table class="add">
<tr>
	<td>Full name:</td>
	<td><input name="name" size="40" type="text"  value="" onFocus="setBorder(this)" onBlur="clearBorder(this)"></td>
</tr>

<tr>
	<td>Username:</td>
	<td><input name="username" size="40" type="text" value="" onFocus="setBorder(this)" onBlur="clearBorder(this)"></td></td>
</tr>
<tr>
	<td>Email:</td>
	<td><input name="email" size="40" type="text" value="" onFocus="setBorder(this)" onBlur="clearBorder(this)"></td></td>
</tr>
<tr>
	<td>Password:</td>
	<td><input name="password" size="40" type="password" value="" onFocus="setBorder(this)" onBlur="clearBorder(this)"></td></td>
</tr>

<tr>
	<td colspan="2"><INPUT type="submit" value="Add user" name="save" class="save"></td>
</tr>
</table>
				
</form>
<h1></h1>
</div>