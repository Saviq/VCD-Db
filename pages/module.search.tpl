<div class="topic">{$translate.search.search}</div>
<div class="forms">
<form action="{$smarty.server.SCRIPT_NAME}" method="get">
<input type="hidden" name="page" value="search"/>
<input type="text" name="searchstring" class="dashed" style="width:70px;"/>&nbsp;
<input type="submit" value="{$translate.search.search}" class="buttontext"/><br/>
{html_radios name='by' options=$searchOptions class='nof' selected=$lastSearchMethod separator='<br />'}
</form>
</div>