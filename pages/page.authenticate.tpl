<h1>{$translate.login.failed}</h1>

{if $loginAccountdisabled}
<p>
	<span class="bold">{$translate.login.failed}.</span><br/><br/>
	{$translate.login.deleted}	
</p>


{/if}
{if $loginInvalid || $smarty.get.action eq 'retry'}

<p>
	<span class="bold">{$translate.login.failed}. {$translate.login.tryagain}. </span><br/><br/>
	{$translate.login.lost}<br/>
	<a href="#" onclick="show('claim');return false">{$translate.login.claim}</a>
</p>

<p>
	{if $smarty.get.action eq 'retry'} 
	<div id="claim" style="padding-left:15px;">
	{else}
	<div id="claim" style="padding-left:15px;visibility:hidden">
	{/if}
		<span class="bold">{$translate.login.recover}</span>
		<form name="claim" method="post" action="{$smarty.server.SCRIPT_NAME}?page=authenticate&amp;action=reset">
		<table cellpadding="1" cellspacing="1" border="0">
			<tr>
				<td>{$translate.login.username}:</td>
				<td><input type="text" name="username"/></td>
			</tr>
			<tr>
				<td>{$translate.register.email}:</td>
				<td><input type="text" name="email"/></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" value="{$translate.misc.confirm}"/></td>
			</tr>
		</table>
		</form>
	</div>
</p>


{/if}