<?
	require_once("../classes/includes.php");
	if (!VCDUtils::isLoggedIn()) {
		VCDException::display("User must be logged in");
		print "<script>self.close();</script>";
		exit();
	}

	$language = new VCDLanguage();
	if (isset($_SESSION['vcdlang'])) {
		$language->load($_SESSION['vcdlang']);
	}
	VCDClassFactory::put($language, true);
	

	$user = $_SESSION['user'];
	$jsaction = "";
		
	if(isset($_POST['updatename'])) {
	
		$newactor = $_POST['newstar'];
		if (!empty($newactor)) {
			$obj = new pornstarObj(array('',$newactor,'',''));
			PornstarServices::addPornstar($obj);
		} 
		$cd_id = $_POST['movie_id'];
		$vcd = MovieServices::getVcdById($cd_id);
	
	} elseif(isset($_POST['updateboth'])) {
		
		$newactor = $_POST['newstar'];
		$movie_id = $_POST['movie_id'];
		
		if (!empty($newactor)) {
			$obj = new pornstarObj(array('',$newactor,'',''));
			$pObj = PornstarServices::addPornstar($obj);
			$new_id = $pObj->getID();
			PornstarServices::addPornstarToMovie($new_id, $movie_id);
		}
		
		$vcd = MovieServices::getVcdById($movie_id);
		$jsaction = ";window.opener.location.reload()";
	
	} elseif(isset($_POST['update'])) {
		$movie_id = $_POST['movie_id'];
		$actors = explode ("#", $_POST['id_list']);
		foreach ($actors as $actor_id) {
			PornstarServices::addPornstarToMovie($actor_id, $movie_id);
		}
		$jsaction = ";window.opener.location.reload();window.close()";
		$vcd = MovieServices::getVcdById($movie_id);
		
	
	} else {
		$cd_id = $_GET['id'];
		$vcd = MovieServices::getVcdById($cd_id);
	
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/2000/REC-xhtml1-20000126/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<title>Star manager | <?=$vcd->getTitle()?></title>
	<link rel="stylesheet" type="text/css" href="../includes/css/style.css"/>
	<meta http-equiv="Content-Type" content="text/html; charset=<?=VCDUtils::getCharSet()?>"/>
	<style type="text/css" media="screen">
		@import url(../includes/css/manager.css);
	</style>
	<script language="JavaScript" src="../includes/js/main.js" type="text/javascript"></script>
</head>

<body onload="window.focus()<?=$jsaction?>">
<form method="post" name="choiceForm" action="editactors.php?update">
<input type="hidden" value="<?=$vcd->getID()?>" name="movie_id"/>
<input type="hidden" value="addthenew" value="0"/>
&nbsp;<strong><?=VCDLanguage::translate('manager.addtodb')?></strong><br/>
&nbsp;<input type="text" class="input" name="newstar"/>&nbsp;
<input type="submit" name="updatename" value="<?=VCDLanguage::translate('manager.savetodb')?>" class="buttontext" title="<?=VCDLanguage::translate('manager.savetodb')?>"/>&nbsp;
<input type="submit" name="updateboth" value="<?=VCDLanguage::translate('manager.savetodbncd')?>" class="buttontext" title="<?=VCDLanguage::translate('manager.savetodbncd')?>"/><br/>
<hr/>
<? 
	$pornstarArr = PornstarServices::getAllPornstars();
?>
	<input type="hidden" name="id_list"/>
		<table cellspacing="0" cellpadding="2" border="0" width="95%">
		<tr>
		<td>
		&nbsp;<strong><?=VCDLanguage::translate('manager.indb')?></strong>
		<input type="hidden" name="keys" value=""/>
		<select name="available" size=15 style="width:180px;" onDblClick="moveOver(this.form, 'available', 'choiceBox');clr();" onKeyPress="selectKeyPress();" onKeyDown="onSelectKeyDown();" onBlur="clr();" onFocus="clr();" class="input">
		<?
		 	foreach ($pornstarArr as $pornstar) {
		 		echo "<option value=\"".$pornstar->getID()."\">".$pornstar->getName()."</option>";
		 	}
		?> 
		</select>
		</td>
		<td>
			<input type="button" value="&gt;&gt;" onclick="moveOver(this.form, 'available', 'choiceBox');clr();" class="input" style="margin-bottom:5px;"/><br/>
			<input type="button" value="<<" onclick="removeMe(this.form, 'available', 'choiceBox');" class="input"/>
		</td>
		<td><strong><?=VCDLanguage::translate('manager.sel')?></strong>
			<select multiple name="choiceBox" onclick="removeMe(this.form, 'available', 'choiceBox')" style="width:180px;" size="8" class="input"></select>
			<br/><br/>
			<input type="submit" onClick="checkFieldsRaw(this.form,'choiceBox','id_list')" value="<?=VCDLanguage::translate('misc.saveandclose')?>" name="update" class="buttontext"/>
		</td>			
		</tr>
		</table>
		<br/>

</form>

</body>
</html>
