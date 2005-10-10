<?
	require_once("../classes/includes.php");
	if (!VCDUtils::isLoggedIn()) {
		VCDException::display("User must be logged in");
		print "<script>self.close();</script>";
		exit();
	}

	$onload = "";
	if (isset($_GET['do']) && $_GET['do'] == 'reload') {
		$onload = ";window.opener.location.reload();";
	}
	
	$language = new language(true);
	if (isset($_SESSION['vcdlang'])) {
		$language->load($_SESSION['vcdlang']);
	}
	
	

	$user = $_SESSION['user'];
	$VCDClass = VCDClassFactory::getInstance("vcd_movie");
	$PORNClass = VCDClassFactory::getInstance("vcd_pornstar");
	$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");
	
	
	$action = "";
	if (isset($_GET['action'])) {
		$action = $_GET['action'];
	}
	
	
	
	if ($action == "delactor") {
		$act_id = $_GET['act_id'];
		$mov_id = $_GET['mov_id'];
		$pornstar->delPornstarFromMovie($act_id, $mov_id);
		$cd_id = $mov_id;
	} else {
		$cd_id = $_GET['cd_id']; 
	}
	
	$bIMDB = false;
	$vcd = $VCDClass->getVcdByID($cd_id);
	if ($vcd->getIMDB() instanceof imdbObj ) {
		$imdb = $vcd->getIMDB();
		$bIMDB = true;
	}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/2000/REC-xhtml1-20000126/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<title>Manager | <?=$vcd->getTitle()?> | My copy</title>
	<link rel="stylesheet" type="text/css" href="../includes/css/style.css"/>
	<style type="text/css" media="screen">
		@import url(../includes/css/manager.css);
	</style>
	<script language="JavaScript" src="../includes/js/main.js" type="text/javascript"></script>
	<script language="JavaScript" src="../includes/js/js_tabs.js" type="text/javascript"></script>
</head>

<body onload="tabInit();window.focus()<?=$onload?>">
<form action="../exec_form.php?action=updatemovie" method="post" name="choiceForm" enctype="multipart/form-data">
<input type="hidden" name="cd_id" value="<?=$vcd->getId()?>"/>


<div class="tabs">
<table cellpadding=0 cellspacing=0 border=0 style="width:100%; height:100%">
	<tr>
		<td id=tab1 class="tab tabActive" height=18><?=$language->show('MAN_BASIC')?></td>
		<td id=tab2 class=tab>
		<? if ($vcd->isAdult()) { 
			echo $language->show('MAN_EMPIRE');
				} else {
			echo $language->show('MAN_IMDB');
		} ?>
		</td>
		<td id=tab3 class=tab><?=$language->show('M_ACTORS')?></td>
		<td id=tab4 class=tab>Covers</td>
	</tr>
	<tr>
		<td id=t1base style="height:2px; border-left:solid thin #E0E7EC"></td>
		<td id=t2base style="height:2px; background-color:#E0E7EC"></td>
		<td id=t3base style="height:2px; background-color:#E0E7EC; border-right:solid thin #E0E7EC"></td>	
		<td id=t4base style="height:2px; background-color:#E0E7EC"></td>
	</tr>
	</table>
</div>

	
<div id="content1" class="content">
<p>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td valign="top" width="80%">
	<table cellspacing="1" cellpadding="1" border="0">
<tr>
	<td class="tblb">Nr:</td>
	<td><?= $vcd->getId() ?></td>
</tr>
<tr>
	<td class="tblb"><?=$language->show('M_TITLE')?>:</td>
	<td><input type="text" name="title" class="input" value="<?= $vcd->getTitle() ?>" size="40"/></td>
</tr>
<tr>
	<td class="tblb"><?=$language->show('M_CATEGORY')?>:</td>
	<td>
		<select name="category" class="input">
		<? 	evalDropdown($SETTINGSClass->getAllMovieCategories(),$vcd->getCategoryID()); ?>
		</select>
	</td>
</tr>
<tr>
	<td class="tblb"><?=$language->show('M_YEAR')?>:</td>
	<td>
		<select name="year" class="input" size="1">
			<? 
				for ($i = date("Y"); $i >= 1900; $i--) {
					if ($i == $vcd->getYear()) {
						echo "<option value=\"$i\" selected>$i</option>";
					} else {
						echo "<option value=\"$i\">$i</option>";
					}
					
				}
			?>
		</select>
	</td>
</tr>
<? if (!$vcd->isAdult()) { ?>
<tr>
	<td class="tblb">IMDB nr.</td>
	<td><input type="text" value="<? if ($bIMDB) print $imdb->getIMDB(); ?>" size="8" name="imdb" class="input"/></td>
</tr>
<? } else { ?>
<tr>
	<td class="tblb"><?=$language->show('M_SCREENSHOTS')?>:</td>
	<td>
	<select name="screenshots" class="input" size="1">
		<option value="0"><?=$language->show('X_NO')?></option>
		<option value="1" <? if ($VCDClass->getScreenshots($vcd->getID())) {echo "selected";} ?>><?=$language->show('X_YES')?></option>
	</select>
	</td>
</tr>
<? } ?>

<? if ($_SESSION['user']->getPropertyByKey('USE_INDEX'))  { 
   $cusIndex = "";
   $arrMeta = $SETTINGSClass->getMetadata($vcd->getID(), $user->getUserID(), 'mediaindex');
   if (sizeof($arrMeta) == 1) {
   		$cusIndex = $arrMeta[0]->getMetadataValue();
   }
   
?>

<tr>	
	<td class="tblb">Custom Index:</td>
	<td><input type="text" name="custom_index" size="8" class="input" value="<?=$cusIndex?>"/></td>
</tr>

<? } ?>


<? if ($_SESSION['user']->getPropertyByKey('PLAYOPTION'))  { 
   $cusPath = "";
   $arrMeta = $SETTINGSClass->getMetadata($vcd->getID(), $user->getUserID(), 'filelocation');
   if (sizeof($arrMeta) == 1) {
   		$cusPath = $arrMeta[0]->getMetadataValue();
   }
   
?>

<tr>	
	<td class="tblb">File path:</td>
	<td><input type="text" name="filepath" size="36" class="input" value="<?=$cusPath?>"/>
		<img src="../images/icon_folder.gif" border="0" align="absmiddle" title="Browse for file" onclick="filebrowse('file')"/>
	</td>
</tr>

<? } ?>




<? 
	// Get my copies ..
	$arrCopies = $vcd->getInstancesByUserID($_SESSION['user']->getUserID());
	if (sizeof($arrCopies) > 0) {
		$arrMediaTypes = $arrCopies['mediaTypes'];
		$arrNumcds = $arrCopies['discs'];
	}
	
	if (sizeof($arrCopies) == 0) {
		print "<tr><td colspan=\"2\"><hr/>".$language->show('MAN_NOCOPY')."</td></tr>";
	} elseif (sizeof($arrMediaTypes) == 1) {
		print "<tr><td colspan=\"2\"><hr/><strong>".$language->show('MAN_COPY')."</strong></td></tr>";
	} else {
		print "<tr><td colspan=\"2\"><hr/><strong>".$language->show('MAN_COPIES')."</strong></td></tr>";
	}
	
	
	if (sizeof($arrCopies) > 0) {
?>
<tr>
	<td colspan="2" valign="top">
	<!-- Begin instance table -->
	<table cellspacing="1" cellpadding="1" border="0" width="100%">
	<tr><td><?=$language->show('MAN_1COPY')?></td><td><?=$language->show('M_MEDIATYPE')?></td><td><?=$language->show('M_NUM')?></td><td>&nbsp;</td></tr>
	<? 
		$allMediaTypes =  $SETTINGSClass->getAllMediatypes();
	
		for ($i = 0; $i < sizeof($arrMediaTypes); $i++) {
			print "<tr><td>".($i+1)."</td><td>";
			
			$media_id = $arrMediaTypes[$i]->getmediaTypeID();
			$cd_count = $arrNumcds[$i];
									
			print "<select name=\"userMediaType_".$i."\" size=\"1\" class=\"input\">";
			foreach ($allMediaTypes as $mediaTypeObj) {
				
				if ($media_id == $mediaTypeObj->getmediaTypeID()) {
					print "<option value=\"".$mediaTypeObj->getmediaTypeID()."\" selected>".$mediaTypeObj->getDetailedName()."</option>";
				} else {
					print "<option value=\"".$mediaTypeObj->getmediaTypeID()."\">".$mediaTypeObj->getDetailedName()."</option>";
				}
				
				
				if ($mediaTypeObj->getChildrenCount() > 0) {
					foreach ($mediaTypeObj->getChildren() as $childObj) { 
						if ($media_id == $childObj->getmediaTypeID()) { 
							print "<option value=\"".$childObj->getmediaTypeID()."\" selected>&nbsp;&nbsp;".$childObj->getDetailedName()."</option>";
						} else {
							print "<option value=\"".$childObj->getmediaTypeID()."\">&nbsp;&nbsp;".$childObj->getDetailedName()."</option>";						
						}
						
						
						}
					}
				}	
			print "</select>";
			
		print "</td><td>";
		print "<select name=\"usernumcds_".$i."\" class=\"input\" size=\"1\">";
				for ($j = 1; $j < 6; $j++) {
					if ($j == $cd_count) {
						echo "<option value=\"$j\" selected>$j</option>";
					} else {
						echo "<option value=\"$j\">$j</option>";
					}
					
				}
		print "</select>";
		
		
		print "</td><td><a href=\"#\" onclick=\"deleteCopy(".sizeof($arrMediaTypes).",".$vcd->getNumCopies().",".$vcd->getId().",".$media_id.")\"><img src=\"../images/thrashcan.gif\" alt=\"Delete this copy\" border=\"0\"/></a></td>";
		
		print "</tr>";
		}
		
	?>
	
	</table>
	<input type="hidden" name="usercdcount" value="<?=$i?>"/>
	<!-- End instance table -->
	</td>
</tr>

<?  } ?>

</table>
</td>
	<td valign="top" align="right" width="20%">
	<? $coverObj = $vcd->getCover("thumbnail");
		if (!is_null($coverObj))
			$coverObj->showImage('../'); ?>
	</td>
</tr>
</table>
</p>
</div>
	
<div id="content2" class="content">
<p>
<? 
	if ($vcd->isAdult()) { ?>
<table cellspacing="1" cellpadding="1" border="0">
<tr>
	<td class="tblb" valign="top">Studio:</td>
	<td><select name="studio" class="input">
		<? 	
			$studioObj = $PORNClass->getStudioByMovieID($vcd->getID());
			if ($studioObj instanceof studioObj) {
				$studio_id = $studioObj->getID();
			} else {
				$studio_id = "";
			}
			evalDropdown($PORNClass->getAllStudios(),$studio_id); ?>
	</select>
	
	</td>
</tr>
<tr>
	<td class="tblb" valign="top" colspan="2"><?=$language->show('EM_SUBCAT')?>:<br/>
	<input type="hidden" name="id_list">
			<table cellspacing="0" cellpadding="2" border="0">
			<tr>
				<td>
					<select name="available" size=8 style="width:200px;" onDblClick="moveOver(this.form)" class="input">
					<? 
					$result = $PORNClass->getSubCategories();
					foreach ($result as $porncategoryObj) {
						print "<option value=\"".$porncategoryObj->getID()."\">".$porncategoryObj->getName()."</option>";
					}
					unset($result);
					?>	
					</select>
				</td>
				<td>
					<input type="button" value="&gt;&gt;" onclick="moveOver(this.form);" class="input" style="margin-bottom:5px;"/><br/>
					<input type="button" value="<<" onclick="removeMe(this.form);" class="input"/>
				</td>
				<td>
					<select multiple name="choiceBox" style="width:200px;" size="8" onDblClick="removeMe(this.form)" class="input">
					<?
					$result = $PORNClass->getSubCategoriesByMovieID($vcd->getID());
					foreach ($result as $porncategoryObj) {
						print "<option value=\"".$porncategoryObj->getID()."\">".$porncategoryObj->getName()."</option>";
					}
					unset($result);
					?>	
					
					</select>
				</td>			
			</tr>
			</table>
	</td>
</tr>
</table>
	
	
	<? } else {  ?>
<table cellspacing="1" cellpadding="1" border="0">
<tr>
	<td class="tblb" valign="top"><?=$language->show('M_TITLE')?>:</td>
	<td><input type="text" name="imdbtitle" class="input" value="<? if ($bIMDB) echo $imdb->getTitle() ?>" size="45"/></td>
</tr>
<tr>
	<td class="tblb" valign="top"><?=$language->show('M_ALTTITLE')?>:</td>
	<td><input type="text" name="imdbalttitle" class="input" value="<? if ($bIMDB) echo $imdb->getAltTitle() ?>" size="45"/></td>
</tr>
<tr>
	<td class="tblb"><?=$language->show('M_GRADE')?>:</td>
	<td><input type="text" name="imdbgrade" class="input" value="<? if ($bIMDB) echo $imdb->getRating() ?>" size="3"/> <?=$language->show('MAN_STARS')?></td>
</tr>
<tr>
	<td class="tblb"><?=$language->show('M_RUNTIME')?>:</td>
	<td><input type="text" name="imdbruntime" class="input" value="<? if ($bIMDB) echo $imdb->getRunTime() ?>" size="3"/> min.</td>
</tr>
<tr>
	<td class="tblb"><?=$language->show('M_DIRECTOR')?>:</td>
	<td><input type="text" name="imdbdirector" class="input" value="<? if ($bIMDB) echo $imdb->getDirector() ?>" size="45"/></td>
</tr>
<tr>
	<td class="tblb"><?=$language->show('M_COUNTRY')?>:</td>
	<td><input type="text" name="imdbcountries" class="input" value="<? if ($bIMDB) echo $imdb->getCountry() ?>" size="45"/></td>
</tr>
<tr>
	<td class="tblb" valign="top">IMDB <?=$language->show('M_CATEGORY')?>:</td>
	<td><input type="text" name="imdbcategories" class="input" value="<? if ($bIMDB) echo $imdb->getGenre() ?>" size="45"/></td>
</tr>
<tr>
	<td class="tblb" valign="top"><?=$language->show('M_PLOT')?>:</td>
	<td><textarea cols="40" rows="5" name="plot" class="input"><? if ($bIMDB) echo $imdb->getPlot() ?></textarea></td>
</tr>
</table>
<? } ?>
</p>
</div>
	
<div id="content3" class="content">
<div class="flow" align="left">
<p>
<? if($vcd->isAdult()) { ?>
<div align="right"><input type="button" value="<?=$language->show('MAN_ADDACT')?>" class="buttontext" title="<?=$language->show('MAN_ADDACT')?>" onClick="addActors(<?=$vcd->getID()?>)"/></div>
<? } ?>
<? 
	if ($vcd->isAdult()) {
			$ArrayPornstars = $PORNClass->getPornstarsByMovieID($vcd->getID());
			if(is_array($ArrayPornstars)) {
			echo "<table cellspacing=1 cellpadding=1 border=0>";
				foreach($ArrayPornstars as $pornstar)   {
					$p_id   = $pornstar->getId();
					$p_name	= $pornstar->getName();
					
					echo "<tr><td><li><a href=\"../?page=pornstar&amp;pornstar_id=$p_id\" target=\"_new\">$p_name</a></li></td>";
					make_pornstarlinks($p_id, $p_name, $vcd->getId());
					echo "</tr>";
					
				}
			echo "</table>";
		} else {
			$language->show('M_NOACTORS');
		}
		unset($ArrayPornstars);
			
			
	} else {
		?> 
<div align="center">
<textarea cols="60" rows="15" name="actors" class="input"><? if ($bIMDB) echo $imdb->getCast(false) ?></textarea>
</div>
<?
}
?>
<!-- Leikarar enda -->
</p>
</div>
</div>
	
<div id="content4" class="content">
<? 
	// first get all cover types that are allowed on this media type
	$COVERClass = VCDClassFactory::getInstance("vcd_cdcover");
	$arrCoverTypes = $COVERClass->getAllowedCoversForVcd($vcd->getMediaType());
?>
<table cellspacing="1" cellpadding="1" border="0">
<?
	foreach ($arrCoverTypes as $cdcoverTypeObj) {
		
		// do we have that cover ?
		$coverpath = "";
		$deletecover = "";
		$coverObj = $vcd->getCover($cdcoverTypeObj->getCoverTypeName());
		if ($coverObj instanceof cdcoverObj ) {
			$coverpath = $coverObj->getFilename();
			$deletecover = "&nbsp;&nbsp;<img src=\"../images/thrashcan.gif\" align=\"absmiddle\" onclick=\"deleteCover(".$coverObj->getId().",".$vcd->getId().")\" alt=\"Delete cover\" border=\"0\"/>";
		}
		
		
		print "<tr><td class=\"tblb\" valign=\"top\">".$cdcoverTypeObj->getCoverTypeName()."</td>";
		print "<td><input type=\"text\" name=\"".$cdcoverTypeObj->getCoverTypeName()."\" size=\"20\" class=\"input\" value=\"".$coverpath."\"/>";
		print "&nbsp; <input type=\"file\" name=\"".$cdcoverTypeObj->getCoverTypeID()."\" value=\"".$cdcoverTypeObj->getCoverTypeName()."\" size=\"10\" class=\"input\"/>$deletecover</td></tr>";
	}

?>
</table>

</div>

<div id="submitters">
		
		<? if ($vcd->isAdult()) { ?>
			<input type="submit" name="update" value="<?=$language->show('X_UPDATE')?>" class="buttontext" onClick="checkFieldsRaw(this.form);"/>
			<input type="submit" name="submit" value="<?=$language->show('X_SAVEANDCLOSE')?>" class="buttontext" onClick="checkFieldsRaw(this.form);"/>
		<? } else { ?>
			<input type="submit" name="update" value="<?=$language->show('X_UPDATE')?>" class="buttontext"/>
			<input type="submit" name="submit" value="<?=$language->show('X_SAVEANDCLOSE')?>" class="buttontext"/>
		<? } ?>
		<input type="button" name="close" value="<?=$language->show('X_CLOSE')?>" class="buttontext" onClick="window.close()"/>
</div>
</form>
</body>
</html>
