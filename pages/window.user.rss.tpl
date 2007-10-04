<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VCD-db</title>
	<meta http-equiv="Content-Type" content="text/html; charset={$pageCharset}"/>
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="{$pageStyle}" media="screen, projection"/>
	<link rel="stylesheet" type="text/css" href="includes/css/global.css" media="screen, projection"/>
	<script src="includes/js/main.js" type="text/javascript"></script>
</head>

{if $reload} 
<body onload="window.opener.location.reload();window.close()">
{else}
<body onload="window.focus()">
{/if}

{if $smarty.get.type eq 'vcddb'}
<h2>{$translate.rss.rss}</h2>

	<div id="container" style="padding-left:4px">

	
	{if !$smarty.post.feedurl == '' && $smarty.post.feedurl != 'http://'}
	
		{if is_array($rssList)}
		
			{** Show the RSS feeds that were found **}
			<form name="feeds" method="post" action="{$smarty.server.SCRIPT_NAME}?page=addrss&amp;type=vcddb&amp;action=addvcddbfeed">
			<strong>{$translate.rss.found}</strong>
			<br/>
			<table cellspacing="1" cellpadding="1" border="0">
			<tr>
				<td colspan="2"><strong>{$translate.rss.site}</strong></td>
			</tr>
			<tr>
				<td><input type="checkbox" class="nof" value="{$rssTitle}|{$rssLink}" name="feeds[]"/></td>
				<td>{$rssTitle}</td>
			</tr>
			<tr>
				<td colspan="2"><strong>{$translate.rss.user}</strong></td>
			</tr>
			{foreach from=$rssList item=i}
			<tr>
				<td><input name="feeds[]" type="checkbox" class="nof" value="{$i.name}|{$i.link}"/></td>
				<td>{$i.name}</td>
			</tr>
			{/foreach}
			<tr>
				<td colspan="2" align="right"><input type="submit" value="{$translate.misc.save}" onclick="return rssCheck(this.form)"/></td>
			</tr>
			</table>
		    </form>
	    
	    {elseif $rssError}
	    	
	    	{$rssError}
			<br/><a href="?page=addrss&amp;type=vcddb">{$translate.misc.tryagain}</a>
	    
	    {else}
	    	No feeds found. <br/>
	    	<a  href="?page=addrss&amp;type=vcddb">{$translate.misc.tryagain}</a>
	    
	    {/if}
	
		
	
	
	{else}
	
	<form name="rss" action="{$smarty.server.SCRIPT_NAME}?page=addrss&amp;type=vcddb&amp;action=vcddbfetch" method="post">
	{$translate.rss.note}
	<p>
		<input type="text" size="25" name="feedurl" value="http://" class="input"/>&nbsp;
		<input type="submit" value="{$translate.rss.fetch}" class="inp"/>
	</p>
	</form>
	
	{/if}

	</div>





{elseif $smarty.get.type eq 'site'}

	<h2>{$translate.rss.add}</h2>
	<div id="container" style="padding-left:4px">
	<form method="post" action="{$smarty.server.SCRIPT_NAME}?page=addrss&amp;type=site&amp;action=addsitefeed">
	<table cellpadding="1" cellspacing="1" border="0">
		<tr>
			<td>{$translate.metadata.name}:</td>
			<td><input type="text" size="36" name="rssname"/></td>
		</tr>
		<tr>
			<td>Url:</td>
			<td><input type="text" size="36" name="rssurl"/></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" value="{$translate.menu.submit}"/></td>
		</tr>
	</table>
	</form>
	</div>


{else}

<script>window.close();</script>

{/if}




</body>
</html>