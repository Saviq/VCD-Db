<h1>{$translate.xml.confirm}</h1>

<script type="text/javascript" src="includes/js/json.js"></script> 
<script type="text/javascript" src="includes/js/ajax.js"></script> 
<script type="text/javascript" src="includes/js/importer.js"></script> 
<script type="text/javascript"> 
{php}
global $ajaxClient;
echo $ajaxClient->getJavaScript();
{/php}

var counter = 1;
var numDocs = {$importMovieCount};
var bContinue = true;
{literal} 
function movie_cb( response )   { 
	obj = new Object(response);
  	tblAjaxUpdate(obj, (numDocs-counter));
  	
  	barvalue = roundNumber((counter/numDocs),2);
  	myProgBar.setBar(barvalue);
  	setBarColor(myProgBar, barvalue);
  	counter++; 	
  	
  	if (counter == numDocs) {
  		setTimeout("endCall()", 3000);	
  	}
} 
  
function _doCall() {
	document.getElementById('xmlClick').disabled=true;
	document.getElementById('xmlCancel').disabled=true;
	show('tblAjax');
	setTimeout("doCall()", 3000);
}

function endCall() {
	alert('Import completed.');
}
	 
function doCall() {
	var xmlfile = document.getElementById('xml_filename').value;
  	var xmlthumbsfile = document.getElementById('xml_thumbfilename').value;
  	for (i=0; i<numDocs;i++) {
		x_VCDXMLImporter.addMovie(xmlfile, i, xmlthumbsfile, movie_cb); 		
	}
}
{/literal}
</script>
   	
{if $importError}
<p>{$translate.xml.error}</p>
{else}


<p>
<span class="bold">{$importTranslateCount}</span>
<br/>
{$translate.xml.info1}
<br/><br/>
</p>

<form name="thumbupload" action="{$smarty.server.SCRIPT_NAME}?page=add&amp;source=xml" method="post" enctype="multipart/form-data">
&nbsp;&nbsp;&nbsp;
<input type="button" class="input" id="xmlClick" value="{$translate.misc.confirm}" onclick="_doCall()"/>
&nbsp; <input type="submit" id="xmlCancel" name="xmlCancel" value="{$translate.misc.cancel}" class="input"/>
<input type="hidden" name="xml_filename" id="xml_filename" value="{$importXmlFilename}"/>
<input type="hidden" name="xml_thumbfilename" id="xml_thumbfilename" value="{$importXmlThumbnailFilename}"/>


   
{if !$importThumbnails}
<p>
    <span class="bold" style="color:red">{$translate.misc.attention}</span><br/>
    {$translate.xml.info2}
	
    <br/><br/>
    
	&nbsp;&nbsp;&nbsp;{$translate.xml.thumbnails} &nbsp;  
	<input type="file" name="xmlthumbfile"/>
	<input type="submit" value="{$translate.misc.update}" name="thumbsupdate" id="thumbsupdate"/>
    
</p>
{/if}
</form>

<br/>
<br/>
<table cellspacing="1" cellpadding="1" id="tblAjax" border="0" class="displist" width="650" style="display:none;visibility:hidden">
<tr>
	<td nowrap="nowrap" width="60%">Title:</td>
	<td>Thumbnail:</td>
	<td>Status:</td>
	<td>Remaining:</td>
</tr>
<tr>
	<td id="ajax_tit"></td>
	<td id="ajax_thu"></td>
	<td id="ajax_sta"></td>
	<td id="ajax_rem"></td>
</tr>
<tr>
	<td colspan="4">
	<script type="text/javascript" language="javascript1.2">
		var myProgBar = new progressBar(1,'#000000','#ffffff','#043db2',642,20,1);
	</script>
	</td>
</tr>
</table>




<p>
	<span class="bold">{$translate.xml.list}</span>
</p>

{if is_array($importTitles) && count($importTitles)>0}
<ul>
	{foreach from=$importTitles item=title}
	<li>{$title|escape}</li>
	{/foreach}
</ul>
{/if}


<br/><br/>


{/if}
 