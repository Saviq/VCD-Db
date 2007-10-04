<script type="text/javascript" src="includes/js/json.js"></script> 
<script type="text/javascript" src="includes/js/ajax.js"></script> 
<script type="text/javascript"> 
{php}
// include the Ajax javascript
global $ajaxClient;
echo $ajaxClient->getJavaScript();
{/php}
</script>

<form name="imdbfetcher" action="exec_form.php?action=moviefetch" onsubmit="copyFiles(this);" enctype="multipart/form-data" method="post">
<input type="hidden" name="imdb" value="{$remoteId}"/>
<input type="hidden" name="image" value="{$imageName}"/>
<table cellspacing="0" cellpadding="0" width="100%" border="0" class="displist">
<tr>
	<td class="header">{$translate.movie.info}</td>
	<td class="header">{$translate.movie.details}</td>
</tr>
<tr>
	<td valign="top" width="65%">
	<!-- Begin IMDB info-->
	<table cellpadding="0" cellspacing="0" border="0" class="plain" width="100%">
	<tr>
		<td rowspan="6" width="105"><img src="{$imagePath}" border="0" class="imgx"/></td>
		<td width="30%">{$translate.movie.title}:</td>
		<td width="50%"><input type="text" name="title" value="{$titleFormatted}" size="28"/></td>
	</tr>
	<tr>
		<td>IMDB {$translate.movie.title}:</td>
		<td><input type="text" name="imdbtitle" value="{$title}" size="28"/></td>
	</tr>
	<tr>
		<td>{$translate.movie.alttitle}:</td>
		<td><input type="text" name="alttitle" value="{$altTitle}" size="28"/></td>
	</tr>
	<tr>
		<td>{$translate.movie.year}:</td>
		<td><input type="text" name="year" value="{$year}" size="4"/></td>
	</tr>
	<tr>
		<td>{$translate.movie.grade}:</td>
		<td><input type="text" name="rating" value="{$rating}" size="2"/> stars</td>
	</tr>
	<tr>
		<td>{$translate.movie.runtime}:</td>
		<td><input type="text" name="Runtime" value="{$runtime}" size="4"/> minutes</td>
	</tr>
	<tr>
		<td>{$translate.movie.director}: </td>
		<td colspan="2"><input type="text" name="director" value="{$director}" size="25"/></td>
	</tr>
	<tr>
		<td>{$translate.movie.country}:</td>
		<td colspan="2"><input type="text" name="Country" value="{$countryList}" size="40"/></td>
	</tr>
	<tr>
		<td nowrap>IMDB {$translate.movie.category}: </td>
		<td colspan="2"><input type="text" name="categories" value="{$catList}" size="40"/></td>
	</tr>
	<tr>
		<td colspan="3">{$translate.movie.plot}:<br/>
		<textarea cols="55" rows="5" name="plot">{$plot}</textarea></td>
	</tr>
	<tr>
		<td colspan="3">{$translate.movie.actors}:<br/>
		<!-- IMDB Cast -->
		<textarea cols="55" rows="16" name="cast">{$cast}</textarea>
		<!-- End IMDB Cast -->
		</td>
	</tr>
	</table>


	<!-- End IMDB info -->

	</td>
	<td valign="top" width="35%">
	<!-- My copy -->


	<table cellspacing="1" cellpadding="1" width="100%" class="plain">
	<tr>
		<td class="strong" nowrap="nowrap">{$translate.movie.mediatype}:</td>
		<td>{html_options id=mediatype name=mediatype options=$mediatypeList selected=$selectedMediatype onchange="processing(true);x_VCDAjaxHelper.getDataForMediaType('meta|cover|dvd', this.value, showForms)"}</td>
	</tr>
	<tr>
		<td>{$translate.movie.category}:</td>
		<td>{html_options id=category name=category options=$categoryList selected=$selectedCategory}</td>
	</tr>
	<tr>
		<td>CD's:</td>
		<td>{html_options id=cds name=cds options=$countList selected=$selectedCount}</td>
	</tr>
	<tr>
		<td valign="top">{$translate.movie.comment}:</td>
		<td><textarea cols="15" rows="5" name="comment"></textarea></td>
	</tr>
	<tr>
		<td valign="top" colspan="2">{$translate.movie.private}: <input type="checkbox" class="nof" value="private" name="private"/></td>
	</tr>
	</table>
	<div id="dvdFields"></div>
	<div id="metaFields"></div>
	<div id="coverFields"></div>
	<br/>
	<img id="processIcon" style="visibility:hidden;" src="images/processing.gif"/>
	<input type="submit" value="{$translate.misc.confirm}" class="buttontext" id="confirmButton" onclick="return val_IMDB(this.form);return false"/>
	<!-- End My copy -->
	</td>
</tr>
</table>


</form>