<h2>{$translate.menu.loansystem}</h2>

<form method="post" name="loans" action="{$smarty.server.SCRIPT_NAME}?page=loans&ampaction=addloan">
<input type="hidden" name="id_list"/>
<input type="hidden" name="keys" value=""/>

<table cellspacing="0" cellpadding="2" border="0">
<tr>
	<td><h2>{$translate.menu.movies}</h2>
	movie to loan
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
	
	list of borrowers
	<ul>
		<li>{$translate.loan.addusers}</li>
	</ul>
	
	<br/>
	else ..
			
	velja lánþega
	
	<input type="button" value="{$translate.loan.newuser}" onclick="createBorrower()"/>
	if lángþegar ..
	<input type="submit" value="{$translate.misc.confirm}" onClick="return checkFields(this.form)"/>

	</p>
	</td>			
</tr>
</table>
 
<div align="right" class="info">Message</div>


<br/>

Ef lán ...

<h2>{$translate.loan.movieloans}</h2>
		
<table cellspacing="0" cellpadding="0" border="0" width="100%" class="list">
{** Setja foreach **}
<tr>
	<td colspan="6"><strong>borrname</strong> 
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
<tr>
	<td>&nbsp;</td>
	<td>teljari</td>
	<td><a href="?page=cd&vcd_id=id">Title</a></td>
	<td>dagsút</td>
	<td>daydiff</td>
	<td><a href="#" onclick="returnloan(number)\">[{$translate.loan.return}]</a></td>
</tr>
</table>


</form>