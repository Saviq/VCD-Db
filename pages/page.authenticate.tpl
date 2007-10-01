<h1>Login failed</h1>

{if $loginAccountdisabled}
<p>
	<span class="bold">Login failed.</span><br/><br/>
	Your account has been deleted.<br/>
	Contact admin for further details.
	
</p>


{/if}
{if $loginInvalid}

<p>
	<span class="bold">Login failed. Try again. </span><br/><br/>
	Or maybe you have lost your password?<br/>
	<a href="#" onclick="show('claim')">Click here to get your password</a>
</p>

<p>
	<div id="claim" style="padding-left:15px;visibility:hidden">
		<span class="bold">Enter username and email</span>
		<form name="claim" method="post" action="./?page=reset">
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