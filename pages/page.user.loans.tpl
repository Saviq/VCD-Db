<h2>{$translate.menu.loansystem}</h2>

<form method="post" name="loans" action="{$smarty.server.SCRIPT_NAME}?page=loans&amp;action=addloan">
<input type="hidden" name="id_list"/>
<input type="hidden" name="keys" value=""/>

<table cellspacing="0" cellpadding="2" border="0">
<tr>
	<td><h2>{$translate.menu.movies}</h2>
	{html_options size="16" style="width:300px;" id=available name=available options=$myMovieList ondblclick="moveOver(this.form, 'available', 'choiceBox');clr();" onKeyPress="selectKeyPress();" onKeyDown="onSelectKeyDown();" onBlur="clr();" onFocus="clr();" class="inp"}
	</td>
	<td>
	<input type="button" value="&gt;&gt;" onclick="moveOver(this.form, 'available', 'choiceBox');clr();" class="input" style="margin-bottom:5px;"/>
	<br/>
	<input type="button" value="<<" onclick="removeMe(this.form, 'available', 'choiceBox');clr();" class="input"/>
	</td>
	<td>
	
	<p><br/><br/><br/>
	
	<h2>{$translate.loan.movies}</h2>
	<select multiple name="choiceBox" id="choiceBox" style="width:300px;" size="8" class="inp" onDblClick="removeMe(this.form, 'available', 'choiceBox')"></select>
	
	<br/>
	<h2>{$translate.loan.to}</h2>
	
	{if is_array($borrowersList) && count($borrowersList)>0}
		{html_options id=borrowers name=borrowers options=$borrowersList selected=$selectedBorrower}
	{else}
	<ul>
		<li>{$translate.loan.addusers}</li>
	</ul>
	{/if}
	<br/>
	
	<input type="button" value="{$translate.loan.newuser}" onclick="createBorrower()"/>
	{if is_array($borrowersList) && count($borrowersList)>0}
	<input type="submit" value="{$translate.misc.confirm}" onclick="return checkFields(this.form)"/>
	{/if}
	</p>
	
	</td>			
</tr>
</table>
 
<div align="right" class="info">Message</div>


<br/>

Ef lán ...

<h2>{$translate.loan.movieloans}</h2>
		
<table cellspacing="0" cellpadding="0" border="0" width="100%" class="list">
{foreach from=$loanList item=i name=loan}
<tr>
	<td colspan="6"><strong>{$i.name}</strong> 
	| <a href="exec_query.php?action=reminder&bid=id">{$translate.loan.reminder}</a> 
	| <a href=\"./?page=private&o=loans&history=id">{$translate.loan.history2}</a>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td class="header">Nr.</td>
	<td class="header">{$translate.movie.title}:</td>
	<td class="header">{$translate.loan.since}:</td>
	<td class="header">{$translate.loan.time}:</td>
	<td class="header">&nbsp;</td>
</tr>
{foreach from=$i.items name=movies item=j}
<tr>
	<td>&nbsp;</td>
	<td>teljari</td>
	<td><a href="?page=cd&vcd_id=id">{$j.title}</a></td>
	<td>dagsút</td>
	<td>daydiff</td>
	<td><a href="#" onclick="returnloan(number)\">[{$translate.loan.return}]</a></td>
</tr>
{/foreach}
{/foreach}
</table>


</form>