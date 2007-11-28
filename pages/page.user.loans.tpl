{assign var='base' value=$smarty.server.SCRIPT_NAME}
{if $smarty.get.history}
<h1>{$translate.loan.history}</h1>

&nbsp;<span class="bold">{$translate.loan.select}</span>
{html_options id=borrowers name=borrowers options=$borrowersList selected=$smarty.get.history onchange="location.href='$base?page=loans&history='+this.options[this.selectedIndex].value"}
<br/><br/>
<fieldset>
	<legend>{$loanHistoryTitle}</legend>
	
	<table width="100%" cellspacing="0" cellpadding="0" border="0" class="displist">
	<tr>
		<td class="bold">{$translate.movie.title}:</td>
		<td class="bold">{$translate.loan.dateout}:</td>
		<td class="bold">{$translate.loan.datein}:</td>
		<td class="bold">{$translate.loan.period}:</td>
	</tr>
	{foreach from=$loanList item=i name=history}
	<tr>
		<td>{$i.title}</td>
		<td>{$i.out|date_format:$config.date}</td>
		{if $i.returned}
		<td>{$i.in|date_format:$config.date}</td>
		{else}
		<td style="color:red;background-color:#f2f2f2">{$i.in}</td>
		{/if}
		<td>{$i.duration}</td>
	</tr>
	{/foreach}
	</table>
</fieldset>

{else}

<h1>{$translate.menu.loansystem}</h1>

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
	
	<br/><br/><br/>
	
	<h2>{$translate.loan.movies}</h2>
	<select multiple name="choiceBox" id="choiceBox" style="width:300px;" size="8" class="inp" onDblClick="removeMe(this.form, 'available', 'choiceBox')"></select>
	<br/>
	<h2 style="margin-bottom:5px">{$translate.loan.to}</h2>
	
	{if is_array($borrowersList) && count($borrowersList)>0}
		{html_options id=borrowers name=borrowers options=$borrowersList selected=$selectedBorrower}
	{else}
	<ul>
		<li>{$translate.loan.addusers}</li>
	</ul>
	{/if}
		
	<input type="button" value="{$translate.loan.newuser}" onclick="createBorrower()"/>
	{if is_array($borrowersList) && count($borrowersList)>0}
	<input type="submit" value="{$translate.misc.confirm}" onclick="return checkFields(this.form)"/>
	{/if}
	
	
	</td>			
</tr>
</table>

<br/>

{if is_array($loanList) && count($loanList)>0}
<h2>{$translate.loan.movieloans}</h2>
{foreach from=$loanList item=i name=loan key=key}
<fieldset>
	<legend><strong>{$i.name}</strong> / <a href="?page=loans&amp;action=reminder&amp;bid={$key}">{$translate.loan.reminder}</a> 
	/ <a href="?page=loans&amp;history={$key}">{$translate.loan.history2}</a></legend>

<table cellpadding="1" cellspacing="1" border="0" class="displist" width="100%">
<tr>
	<td>Nr.</td>
	<td width="55%">{$translate.movie.title}:</td>
	<td>{$translate.loan.since}:</td>
	<td>{$translate.loan.time}:</td>
	<td>&nbsp;</td>
</tr>
{foreach from=$i.items name=movies item=j key=jkey}
<tr>
	<td>{$smarty.foreach.movies.iteration}</td>
	<td><a href="?page=cd&amp;vcd_id={$j.id}">{$j.title}</a></td>
	<td>{$j.out|date_format:$config.date}</td>
	<td>{$j.since}</td>
	<td><a href="?page=loans&amp;action=return&amp;lid={$jkey}">[{$translate.loan.return}]</a></td>
</tr>
{/foreach}
</table>
</fieldset>
{/foreach}

{else}

&nbsp;<span class="bold">{$translate.loan.history2} &gt;&gt;</span>
{html_options id=borrowerDropdown name=borrowerDropdown options=$borrowersList selected=$smarty.get.history onchange="location.href='$base?page=loans&history='+this.options[this.selectedIndex].value"}
{/if}
</form>

{/if}