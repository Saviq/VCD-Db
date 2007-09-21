<? 
	$goto = $_GET['web'];
	$pornstar = $_GET['pornstar'];
	
	$formname = "";
	if ($goto == "excalibur") {
		$formname = "jsDVDform";
	} elseif($goto == "goliath") {
		$formname = "littlesearch";
	} elseif($goto == "searchextreme") {
		$formname = "quickie";
	} elseif($goto == "google") {
		$formname = "qs";
	} elseif($goto == "eurobabe") {
		$formname = "eurobabe";
	}


?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Jumper</title>
</head>

<body onload="document.<?=$formname?>.submit()">


<div align="center" style="color:red;font-weight:bolder">Processing request .......</div>

<div id="forms" style="display:none">

<? 
	if ($goto == "excalibur") {
		?>
		<form name="jsDVDform" action="http://www.excaliburfilms.com/excals.htm" target="_self">
		<input type="radio" name="SearchFor" value="Title.x">
		<input type="radio" name="SearchFor" value="Star.x" checked="checked">
		<input name="searchString" type="Text" value="<?=$pornstar?>">
		<input type="hidden" name="Case" value="ExcalMovies">
		<input type="hidden" name="Search" value="AdultDVDMovies">
		</form>
		<?	
	
	} elseif($goto == "goliath") {
		?>
		<form name="littlesearch" method="post" action="http://www.goliathfilms.com/index.php" target="_self">
		<input name="search[text]" value="<?=$pornstar?>" type="text" />
		<input type="radio" name="search[in]" value="artists" checked="checked" />
		</form>
		<?
				
	} elseif($goto == "searchextreme") {
		?>
		<form action="http://www.searchextreme.com/quickie.aspx" method="post" name="quickie" target="_self">
		<input type="hidden" name="searchType" value="actor">
		<input type="hidden" name="searchstring" value="<?=$pornstar?>">
		</form>
		<?
	} elseif($goto == "google") {
		?>
		<form action="http://images.google.com/images" method="get" name="qs" target="_self">
		<input type=text name=q size=41 maxlength=2048 value="<?=$pornstar?>" title="">
		</form>
		<?
	} elseif($goto == "eurobabe") {
		?>
		<form method="post" action="http://www.eurobabeindex.com/modules/search.py" name="eurobabe" target="_self">
		<input type="hidden" name="text" value="<?=$pornstar?>">
		<input type="hidden" name="what" value="Search Babe">
		</form>

		<?
	}

?>
</div>

</body>
</html>
