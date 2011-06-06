<h1>{$translate.register.title}</h1>

{if !$registrationOpen}
	<p>{$translate.register.disabled}</p>
	

{elseif $registrationSuccess}

<p class="bold">
{$registrationUsername}, {$translate.register.ok}
</p>

	
{else}

<!-- This is a comment  -->

<form name="register" method="post" action="{$smarty.server.SCRIPT_NAME}?page=register">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="displist">
<tr>
	<td width="45%">{$translate.register.fullname} :</td>
	<td><input type="text" name="name"/></td>
</tr>
<tr>
	<td>{$translate.login.username} :</td>
	<td><input type="text" name="username"/></td>
</tr>
<tr>
	<td>{$translate.register.email} :</td>
	<td><input type="text" name="email"/></td>
</tr>
<tr>
	<td>{$translate.login.password} :</td>
	<td><input type="password" name="password"/></td>
</tr>
<tr>
	<td>{$translate.register.again} :</td>
	<td><input type="password" name="password2"/></td>
</tr>
{foreach from=$userProperties item=i}
<tr>
	<td nowrap="nowrap">{$i.desc}</td>
	<td><input type="checkbox" class="nof" name="props[]" value="{$i.key}"/></td>
</tr>
{/foreach}
<tr>
	<td>&nbsp;</td>
	<td><input type="submit" value="{$translate.menu.submit}" onclick="return checkReg(this.form)"/></td>
</tr>
</table>
</form>

{/if}