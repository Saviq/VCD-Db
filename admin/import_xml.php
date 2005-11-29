<?php
require_once("../classes/includes.php");
if (!VCDAuthentication::isAdmin()) {
		VCDException::display("Only administrators have access here");
		print "<script>self.close();</script>";
		exit();
	}

$upload =& new uploader();
$SETTINGSclass = VCDClassFactory::getInstance("vcd_settings");
$path = $SETTINGSclass->getSettingsByKey('SITE_ROOT');

if($_FILES){
  foreach($_FILES as $key => $file){
    $upload->set("name",$file["name"]); // Uploaded file name.
    $upload->set("type",$file["type"]); // Uploaded file type.
   	$upload->set("tmp_name",$file["tmp_name"]); // Uploaded tmp file name.
    $upload->set("error",$file["error"]); // Uploaded file error.
    $upload->set("size",$file["size"]); // Uploaded file size.
    $upload->set("fld_name",$key); // Uploaded file field name.
	$upload->set("max_file_size",509600); // Max size allowed for uploaded file in bytes =  ~500 KB.
    $upload->set("supported_extensions",array("xml" => "text/xml")); // Allowed extensions and types for uploaded file.
    $upload->set("randon_name",true); // Generate a unique name for uploaded file? bool(true/false).
	$upload->set("replace",true); // Replace existent files or not? bool(true/false).
	$upload->set("dst_dir",$_SERVER["DOCUMENT_ROOT"]."".$path."upload/"); // Destination directory for uploaded files.
	$result = $upload->moveFileToDestination(); // $result = bool (true/false). Succeed or not.
  }
}

if($upload->succeed_files_track){
      $file_arr = $upload->succeed_files_track; 
      $upfile = $file_arr[0]['destination_directory'].$file_arr[0]['new_file_name'];
		
      
      
       /* 
       		Process the XML file
       */
     	
	   print "Loading file $upfile<br>";
       if (fs_file_exists($upfile)) {
    		$xml = simplexml_load_file($upfile);
			//var_dump($xml);
	   } else {
    		exit('Failed to open $upfile.');
	   }
	
	   		
		
	   // Load the users ...
	   $users = $xml->vcdusers->user;
	   $imported_users = array();
	   
	   if (sizeof($users) == 0) {
	   		print "No users found in XML file.<br>";
	   } else {
	   		foreach ($users as $item) {
		    	$u = new userObj(array($item->userid, $item->username, $item->password, 
		    					$item->fullname, $item->email, $item->roleid, "",
		    					$item->isdeleted, $item->datecreated));
		    	array_push($imported_users, $u);
			}
			
			print "Found " . sizeof($imported_users) . " users in XML doc.<br>";
			
	   }
	   
	   
	   // Load all Media Types
	   $settings = $xml->vcdsettings->setting;
	   if (is_array($settings) && sizeof($settings) > 0) {
	   		print "Found " . sizeof($settings) . " settings to import.<br>";
	   } else {
	   		print "No settigns found in XML file.<br>";
	   }
	   
	   /*
		foreach ($s->vcdsettings->setting as $item) {
		    print $item->key. " - ";
		    print $item->value . "<br>";
		}
*/
      
		unset($xml);
		fs_unlink($upfile);

      
} else {
	print "Error uploading file.";
	print "<pre>";
	print_r($upload->fail_files_track); 
	print "</pre>";
}




?>

