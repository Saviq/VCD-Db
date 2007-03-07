<?php 
	include_once("../classes/includes.php");
	if (!VCDUtils::isLoggedIn()) {
		die('Login first to use this page');
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>VCD-db</title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo VCDUtils::getCharSet()?>"/>
	<link rel="stylesheet" type="text/css" href="../<?php echo STYLE?>style.css"/>
	<script src="../includes/js/main.js" type="text/javascript"></script>
</head>
<body class="nobg">


<?php 
   $userCategories = SettingsServices::getCategoriesInUseByUserID(VCDUtils::getUserID());
   if (sizeof($userCategories) == 0)
   {
      VCDException::display('You have not added any movies yet<break>Try again after you have inserted some movies');
      print "<script>window.close()</script>";
      exit();
   }
   
   //Get categories names
   $newCatName = array();
   foreach ($userCategories as $categoryObj)
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
   //Alphabetical sort cat�gories names
   sort($newCatName);
   //Reorder categories objects according to categories names
   $newCatObj = array();
   foreach ($newCatName as $name)
   {
      foreach ($userCategories as $categoryObj)
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
   $userCategories = $newCatObj;
   
   $arr = SettingsServices::getMediaTypesInUseByUserID(VCDUtils::getUserID());
   $arrMediaTypes = array();
   
   print "<table cellspacing=\"1\" cellpadding=\"1\" border=\"0\" width=\"100%\">";
   print "<tr><td>&nbsp;</td>";
   foreach ($arr as $item)
   {
      print "<td nowrap=\"noswrap\" class=\"stata\">".$item[1] . "</td>";
      array_push($arrMediaTypes, $item[0]);
   }
   print "<td class=\"stata\">&nbsp;</td>";
   print "</tr>";
   
   $arrMediaTypes = array_flip($arrMediaTypes);
   $mod = 0;
   foreach ($userCategories as $categoryObj)
   {
      $css_class = "stata";
      
      // Translate category name
      $mapping = getCategoryMapping();
      if(isset($mapping[$categoryObj->getName()]))
      {
         $translated = $mapping[$categoryObj->getName()];
         $newKey = VCDLanguage::translate($translated);
      }
      else
         $newKey = $$categoryObj->getName();
      
      print "<tr>";
      print "<td nowrap=\"nowrap\" class=\"".$css_class."\">".$newKey."</td>";
      $arr2 = SettingsServices::getMediaCountByCategoryAndUserID(VCDUtils::getUserID(),$categoryObj->getID());
      $resultArr = getCategoryResults($arrMediaTypes,  $arr2);
      $category_sum = 0;
      foreach ($resultArr as $count)
      {
         print "<td class=\"".$css_class."\" align=\"right\">$count</td>";
         $category_sum += $count;
      }
      print "<td align=\"right\" class=\"".$css_class."\"><b>".$category_sum."</b></td>";
      print "</tr>";
      
      // Print movies name inside current category
      $batch  = 0;
      if (isset($_GET['batch']))
         $batch = $_GET['batch'];
      $Recordcount = SettingsServices::getSettingsByKey("PAGE_COUNT");
      $offset = $batch*$Recordcount;
      $movies = MovieServices::getVcdByCategory($categoryObj->getID(), $Recordcount, $offset, VCDUtils::getUserID());
      foreach ($movies as $movie)
      {
         print "<tr>";
         print "<td nowrap=\"nowrap\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$movie->getTitle()."</td>";
         print "</tr>";
      }
      $mod++;
   }
   
   print "<tr>";
   print "<td class=\"stata\">&nbsp;</td>";
   $total_sum = 0;
   foreach ($arr as $results_count)
   {
      $total_sum += $results_count[2];
      print "<td class=\"stata\" align=\"right\"><b>".$results_count[2]."</b></td>";
   }
   print "<td class=\"stata\" align=\"right\"><b>".$total_sum."</b></td>";
   print "</tr>";
   print "</table>";
?>

   <br/><hr/>
   <p align="center"><input onclick="window.close()" type="button" value="<?php echo VCDLanguage::translate('misc.close')?>"/></p>

</body>
</html>