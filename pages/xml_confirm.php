<h1><?=$language->show('XML_CONFIRM')?></h1>
<? 
    if (!VCDUtils::isLoggedIn()) {
        redirect();
    }
    
    $xmlImportedFileName = "";
    $xmlImportedThumbsFileName = "";
    
    // Check for the XML movie file name.
    if (isset($_POST['xml_filename'])) {
    	$xmlImportedFileName = $_POST['xml_filename'];
    } else {
    	$xmlImportedFileName = VCDXMLImporter::validateXMLMovieImport();
    }
    
    if (strcmp($xmlImportedFileName, "") == 0) {
    	redirect();
    }
    
    
    
    $xmlMovieCount = VCDXMLImporter::getXmlMovieCount($xmlImportedFileName);
    $hasThumbs = false;
        
    /** Check for uploaded thumbnails .. **/
    if (isset($_POST['thumbsupdate'])) {
    	try {
    
    		$filename = VCDXMLImporter::validateXMLThumbsImport();	
    		if (strcmp($filename, "") != 0) {
    			$xmlImportedThumbsFileName = $filename;
    			$hasThumbs = true;
    		}
    		
    	} catch (Exception $ex) {
    		VCDException::display($ex->getMessage());
    	}
    	
    }
    
    
    
	// Print out the Javascripts needed
	?>
	<script type="text/javascript" src="includes/js/json.js"></script> 
    <script type="text/javascript" src="includes/js/ajax.js"></script> 
    <script type="text/javascript" src="includes/js/importer.js"></script> 
    <script type="text/javascript"> 
      <?php echo $ajaxClient->getJavaScript(); ?> 

      var counter = 0;
      var numDocs = <?=$xmlMovieCount?>;
      var bContinue = true;
     
     function movie_cb( response )   { 
      	obj = new Object(response);
	  	tblAjaxUpdate(obj, (numDocs-counter));
	  	
	  	barvalue = roundNumber((counter/numDocs),2);
	  	myProgBar.setBar(barvalue);
	  	//myProgBar.setCol('#ff0000'); //change the colour of the progress bar
	  	if (counter == (numDocs/2) ) {
	  		myProgBar.setCol('orange'); //change the colour of the progress bar
	  	}
	  	counter++; 	
  	 } 
      
     function doCall() {
     	var xmlfile = document.getElementById('xml_filename').value;
      	var xmlthumbsfile = document.getElementById('xml_thumbfilename').value;
      	for (i=0; i<=numDocs;i++) {
      			x_VCDXMLImporter.addMovie(xmlfile, i, xmlthumbsfile, movie_cb); 	
        }
      }
   
   </script>
   	
	<?
	
	$xmltitles = VCDXMLImporter::getXmlTitles($xmlImportedFileName);
    if (!is_array($xmltitles) || sizeof($xmltitles) == 0) {
        print "<p>".$language->show('XML_ERROR')."</p>";
        
    } else {
    ?>
    
    <p><span class="bold"><? printf($language->show('XML_CONTAINS'), sizeof($xmltitles))?></span>
    <br/><?=$language->show('XML_INFO1')?>
    <br/><br/>
    
    
    <form name="thumbupload" action="./?page=private&o=add&source=xml" method="POST" enctype="multipart/form-data">
    &nbsp;&nbsp;&nbsp;<input type="button" class="input" value="<?=$language->show('X_CONFIRM')?>" onclick="doCall()"/>
    &nbsp; <input type="button" onclick="clearXML('<?=$xmlImportedFileName?>')" value="<?=$language->show('X_CANCEL')?>" class="input"/>
    <input type="hidden" name="xml_filename" id="xml_filename" value="<?=$xmlImportedFileName?>"/>
    <input type="hidden" name="xml_thumbfilename" id="xml_thumbfilename" value="<?=$xmlImportedThumbsFileName?>"/>
    
    
       
    <? if (!$hasThumbs) { ?> 
    <p>
        <span class="bold" style="color:red"><?=$language->show('X_ATTENTION')?></span><br/>
        <?=$language->show('XML_INFO2')?>
    	
        <br/><br/>
        
        
    	&nbsp;&nbsp;&nbsp;<?=$language->show('XML_THUMBNAILS')?> &nbsp;  <input type="file" name="xmlthumbfile"/>
    	<input type="submit" value="<?=$language->show('X_UPDATE')?>" name="thumbsupdate" id="thumbsupdate"/>
        
    </p>
    <? } ?>
        
    </form>
    
    <br>
    <br>
    <table cellspacing=1" cellpadding="1" id="tbjAjax" border="0" class="displist" width="650">
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
			var myProgBar = new progressBar(
			1,         //border thickness
			'#000000', //border colour
			'#a5f3b1', //background colour
			'#043db2', //bar colour
			642,       //width of bar (excluding border)
			20,        //height of bar (excluding border)
			1          //direction of progress: 1 = right, 2 = down, 3 = left, 4 = up
		);
		</script>
		</td>
	</tr>
	</table>
	

    
    
    <p><span class="bold"><?=$language->show('XML_LIST')?></span></p>
    
    <ul>
    <?
    if (is_array($xmltitles)) {
        foreach ($xmltitles as $title) {
            print "<li>".$title . "</li>";
        }
    }
    ?>
    </ul>
    
    <br/><br/>
    
    

<? } ?>