<?php  
	require_once("../classes/includes.php");
	if (!VCDUtils::isLoggedIn()) {
		VCDException::display("User must be logged in");
		print "<script>self.close();</script>";
		exit();
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>VCD-db</title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo VCDUtils::getCharSet() ?>"/>
	<link rel="stylesheet" type="text/css" href="../<?php echo VCDUtils::getStyle() ?>"/>
	<script src="../includes/js/main.js" type="text/javascript"></script>
</head>
<body class="nobg">



<?php 
   $categories = SettingsServices::getMovieCategoriesInUse();
   if (sizeof($categories) == 0)
   {
      VCDException::display('You have not added any movies yet<break>Try again after you have inserted some movies');
      print "<script>window.close()</script>";
      exit();
   }
   
   //Get categories names
   $newCatName = array();
   foreach ($categories as $categoryObj)
   {
      $mapping = getCategoryMapping();
      if(isset($mapping[$categoryObj->getName()]))
      {
         $translated = $mapping[$categoryObj->getName()];
         $newKey = VCDLanguage::translate($translated);
      }
      else
         $newKey = $categoryObj->getName();
      array_push($newCatName, $newKey);
   }
   //Alphabetical sort categories names
   sort($newCatName);
   //Reorder categories objects according to categories names
   $newCatObj = array();
   foreach ($newCatName as $name)
   {
      foreach ($categories as $categoryObj)
      {
         $mapping = getCategoryMapping();
         if(isset($mapping[$categoryObj->getName()]))
         {
            $translated = $mapping[$categoryObj->getName()];
            $newKey = VCDLanguage::translate($translated);
         }
         else
            $newKey = $categoryObj->getName();
         if($newKey == $name)
         {
            array_push($newCatObj, $categoryObj);
            break;
         }
      }
   }
   $categories = $newCatObj;
   
   $arr = SettingsServices::getMediaTypesInUse();
   $arrMediaTypes = array();
   $arrMediaTypeNames = array();
   $mediaTypesCount = 0;
   
   print "<table cellspacing=\"1\" cellpadding=\"1\" border=\"0\" width=\"100%\">";
   print "<tr><td>&nbsp;</td>";
   foreach ($arr as $item)
   {
      print "<td nowrap=\"noswrap\" class=\"statc\">".$item[1] . "</td>";
      array_push($arrMediaTypes, $item[0]);
      array_push($arrMediaTypeNames, $item[1]);
      $mediaTypesCount = $mediaTypesCount + 1;
   }
   print "<td class=\"statc\">&nbsp;</td>";
   print "</tr>";
   
   $arrMediaTypes = array_flip($arrMediaTypes);
   $mod = 0;
   foreach ($categories as $categoryObj)
   {
      $css_class_cat = "statc";
      
      //Translate category name to be displayed
      $mapping = getCategoryMapping();
      if(isset($mapping[$categoryObj->getName()]))
      {
         $translated = $mapping[$categoryObj->getName()];
         $newKey = VCDLanguage::translate($translated);
      }
      else
         $newKey = $$categoryObj->getName();
      
      print "<tr>";
      print "<td nowrap=\"nowrap\" class=\"".$css_class_cat."\">".$newKey."</td>";
      $arr2 = SettingsServices::getMediaCountByCategory($categoryObj->getID());
      $resultArr = getCategoryResults($arrMediaTypes,  $arr2);
      $category_sum = 0;
      foreach ($resultArr as $count)
      {
         print "<td align=\"right\" class=\"".$css_class_cat."\">$count</td>";
         $category_sum += $count;
      }
      print "<td align=\"right\" class=\"".$css_class_cat."\"><b>".$category_sum."</b></td>";
      print "</tr>";
      
      //To print movies name inside current category
      $batch  = 0;
      if (isset($_GET['batch']))
         $batch = $_GET['batch'];
      $Recordcount = SettingsServices::getSettingsByKey("PAGE_COUNT");
      $offset = $batch*$Recordcount;
      $movies = MovieServices::getVcdByCategory($categoryObj->getID(), $Recordcount, $offset);
      $lig = 0;
      foreach ($movies as $movie)
      {
         if(($lig % 2) == 0)
            $css_class_movie = "statb";
         else
            $css_class_movie = "stata";
         
         print "<tr>";
         print "<td nowrap=\"nowrap\" class=\"".$css_class_movie."\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$movie->getTitle()."</td>";
         $lastFound = "";
         foreach ($resultArr as $count)
         {
            $stringToWrite = "&nbsp;";
            $mediaType = $movie->getMediaType();
            foreach($mediaType as $mediaTypeObj)
            {
               if($mediaTypeObj->getName() == $lastFound)
                  continue ;
               if(in_array($mediaTypeObj->getName(), $arrMediaTypeNames) and $mediaTypesCount > 1)
               {
                  $lastFound = $mediaTypeObj->getName();
                  $stringToWrite = " X ";
                  break;
               }
            }
            print "<td align=\"center\" class=\"".$css_class_movie."\">$stringToWrite</td>";
         }
         print "</tr>";
         
         $lig++;
      }
      $mod++;
   }
   
   print "<tr>";
   print "<td class=\"statc\">&nbsp;</td>";
   $total_sum = 0;
   foreach ($arr as $results_count)
   {
      $total_sum += $results_count[2];
      print "<td class=\"statc\" align=\"right\"><b>".$results_count[2]."</b></td>";
   }
   print "<td class=\"statc\" align=\"right\"><b>".$total_sum."</b></td>";
   print "</tr>";
   print "</table>";
?>

   <br/><hr/>
   <p align="center"><input onclick="window.close()" type="button" value="<?php echo VCDLanguage::translate('misc.close')?>"/></p>

</body>
</html>