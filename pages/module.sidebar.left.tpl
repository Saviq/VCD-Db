{if $isAuthenticated}
<div class="topic">{$translate.menu.mine}</div>
<span class="nav"><a href="?page=private&amp;o=settings" class="navx">{$translate.menu.settings}</a></span>
{if not $isViewer}
<span class="nav"><a href="?page=private&amp;o=movies" class="navx">{$translate.menu.movies}</a></span>
<span class="nav"><a href="?page=private&amp;o=new" class="navx">{$translate.menu.addmovie}</a></span>
<span class="nav"><a href="?page=private&amp;o=loans" class="navx">{$translate.menu.loansystem}</a></span>
{/if}
{if $showWishlists}
<span class="nav"><a href="?page=private&amp;o=publicwishlist" class="navx">{$translate.menu.wishlistpublic}</span>
{/if}
<span class="nav"><a href="?page=private&amp;o=wishlist" class="navx">{$translate.menu.wishlist}</a></span>
<span class="nav"><a href="?page=private&amp;o=stats" class="navx">{$translate.menu.statistics}</a></span>
<span class="nav"><a href="#" onclick="showAllMoviesDetailed()" class="navx">{$translate.menu.showallusersmovies}</a></span>
{if $showRssFeeds}
<span class=\"nav\"><a href="?page=private&amp;o=rss" class="navx">{$translate.menu.rss}</a></span>
{/if}
	

{else} {* Print the login box *}

<div class="topic">{$translate.login.login}</div>
<div class="forms">   
<form name="login" method="post" action="?page=authenticate">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td>{$translate.login.username}:<br/><input type="text" name="username" maxlength="50" class="dashed"/></td>
</tr>
<tr>
	<td>{$translate.login.password}:<br/><input type="password" name="password" maxlength="50" class="dashed"/></td>
</tr>
<tr>
	<td>{$translate.login.remember}: <input type ="checkbox" name="remember" value="1" class="nof"/></td>
</tr>
<tr>
	<td><input type="submit" value="{$translate.misc.confirm}"/></td>
</tr>
</table>
</form>
</div>

{/if}

{include file='module.categorylist.tpl'}

{include file='module.search.tpl'}

{include file='module.toggler.tpl'}

{include file='module.topusers.tpl'}

{include file='module.pornstars.tpl'}

