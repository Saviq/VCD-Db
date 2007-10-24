<table cellspacing="1" cellpadding="1" border="0">
<tr>
	<td class="tblb" valign="top">{$translate.movie.title}:</td>
	<td><input type="text" name="imdbtitle" class="input" value="{$sourceTitle}" size="45"/></td>
</tr>
<tr>
	<td class="tblb" valign="top">{$translate.movie.alttitle}:</td>
	<td><input type="text" name="imdbalttitle" class="input" value="{$sourceAlttitle}" size="45"/></td>
</tr>
<tr>
	<td class="tblb">{$translate.movie.grade}:</td>
	<td><input type="text" name="imdbgrade" class="input" value="{$sourceGrade}" size="3"/> {$translate.manager.stars}</td>
</tr>
<tr>
	<td class="tblb">{$translate.movie.runtime}:</td>
	<td><input type="text" name="imdbruntime" class="input" value="{$sourceRuntime}" size="3"/> min.</td>
</tr>
<tr>
	<td class="tblb">{$translate.movie.director}:</td>
	<td><input type="text" name="imdbdirector" class="input" value="{$sourceDirector}" size="45"/></td>
</tr>
<tr>
	<td class="tblb">{$translate.movie.country}:</td>
	<td><input type="text" name="imdbcountries" class="input" value="{$sourceCountries}" size="45"/></td>
</tr>
<tr>
	<td class="tblb" valign="top">IMDB {$translate.movie.category}:</td>
	<td><input type="text" name="imdbcategories" class="input" value="{$sourceCategoryList}" size="45"/></td>
</tr>
<tr>
	<td class="tblb" valign="top">{$translate.movie.plot}:</td>
	<td><textarea cols="40" rows="5" name="plot" class="input">{$sourcePlot}</textarea></td>
</tr>
</table>