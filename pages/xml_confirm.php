<h1><?=$language->show('XML_CONFIRM')?></h1>
<? 
    if (!VCDUtils::isLoggedIn()) {
        redirect();
    }
    
    
    require_once('classes/external/ajason/Ajax.php');
    $ajax = new Ajax(); 
	$ajax->registerMethod( 'VCDXMLImporter', 'addMovie' ); 
	$ajaxServer = $ajax->getServer(); 
	if ( $ajaxServer->isRequest() ) 
	{ 
	  echo $ajaxServer->handleRequest(); 
	  exit(); 
	} 
	$ajaxClient = $ajax->getClient(); 
	
	// Print out the Javascripts needed
	?>
	
	<script type="text/javascript" src="includes/js/json.js"></script> 
    <script type="text/javascript" src="includes/js/ajax.js"></script> 
    <script type="text/javascript" src="includes/js/importer.js"></script> 
    <script type="text/javascript"> 
      <?php echo $ajaxClient->getJavaScript(); ?> 

      var counter = 0;
      var numDocs = 50;
      function example_cb( response ) 
      { 
               
      	barvalue = roundNumber((counter/numDocs),2);
      	
      	obj = new Object(response);
      	title = counter + " " + obj.name + "("+barvalue+")";
      	
      	
      	document.getElementById( "panel" ).innerHTML = title; 
     
      	
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
        	x_VCDXMLImporter.addMovie(xmlfile, i, xmlthumbsfile, example_cb); 
      	
        }
        
      }
      
      function roundNumber(float,dec){
		return Math.round(float*Math.pow(10,dec))/Math.pow(10,dec)
	}
      
   </script>
	
	
	
	<?
	
	
	
	
	
	
    
    
    $hasThumbs = false;
    $xmlthumbnailfile = "";
    
    /** Check for uploaded thumbnails .. **/
    if (isset($_POST['thumbsupdate'])) {
    	try {
    
    		$filename = VCDXMLImporter::validateXMLThumbsImport();	
    		if (strcmp($filename, "") != 0) {
    			$xmlthumbnailfile = $filename;
    			$hasThumbs = true;
    		}
    		
    	} catch (Exception $ex) {
    		VCDException::display($ex->getMessage());
    	}
    	
    }
    
    
    // Check if this is from the right source
    if (!isset($_SESSION['xmlfilename']) || !isset($_SESSION['xmldata'])) {
        redirect();
    } else {
        $xmlfile = $_SESSION['xmlfilename'];
        $xmltitles = $_SESSION['xmldata'];
        
        /*
        // Clean the titles from session and memory
        session_unregister('xmlfilename');
        unset($_SESSION['xmlfilename']);
        session_unregister('xmldata');
        unset($_SESSION['xmldata']);
        */
    }

    if (!is_array($xmltitles) || sizeof($xmltitles) == 0) {
        print "<p>".$language->show('XML_ERROR')."</p>";
        
    } else {
    ?>
    
    <p><span class="bold"><? printf($language->show('XML_CONTAINS'), sizeof($xmltitles))?></span>
    <br/><?=$language->show('XML_INFO1')?>
    <br/><br/>
    
    <form name="xmlconfirm" method="post" action="">
    &nbsp;&nbsp;&nbsp;<input type="button" class="input" value="<?=$language->show('X_CONFIRM')?>" onclick="doCall()"/>
    &nbsp; <input type="button" onclick="clearXML('<?=$xmlfile?>')" value="<?=$language->show('X_CANCEL')?>" class="input"/>
    <input type="hidden" name="filename" id="xml_filename" value="<?=$xmlfile?>"/>
    <input type="hidden" name="thumbfilename" id="xml_thumbfilename" value="<?=$xmlthumbnailfile?>"/>
    </form>
    
       
    <? if (!$hasThumbs) { ?> 
    <p>
        <span class="bold" style="color:red"><?=$language->show('X_ATTENTION')?></span><br/>
        <?=$language->show('XML_INFO2')?>
    	
        <br/><br/>
        
        <form name="thumbupload" action="./?page=private&o=add&source=xml" method="POST" enctype="multipart/form-data">
    	&nbsp;&nbsp;&nbsp;<?=$language->show('XML_THUMBNAILS')?> &nbsp;  <input type="file" name="xmlthumbfile"/>
    	<input type="submit" value="<?=$language->show('X_UPDATE')?>" name="thumbsupdate" id="thumbsupdate"/>
        </form>
    </p>
    <? } ?>
        
    <br>
    <div id="panel"></div><br>
    <br><br>
     <script type="text/javascript" language="javascript1.2"><!--
		var myProgBar = new progressBar(
			1,         //border thickness
			'#000000', //border colour
			'#a5f3b1', //background colour
			'#043db2', //bar colour
			400,       //width of bar (excluding border)
			20,        //height of bar (excluding border)
			1          //direction of progress: 1 = right, 2 = down, 3 = left, 4 = up
		);
	//--></script>
    
    
    
    
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