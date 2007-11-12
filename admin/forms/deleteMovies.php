<?php 
	$title = "";
	$owner = "";	

	if (sizeof($_POST) > 0) { 
		$title = $_POST['title'];
		$owner = $_POST['owner'];
	}
	?>


<h1>Delete movies from database</h1>

<div id="newObj">
<form name="new" method="POST" action="index.php?page=deletemovies" onsubmit="return checkDeletion(this)">
<table class="add">
<tr>
	<td>Title to search for:</td>
	<td><input name="title" value="<?php echo $title?>" type="text" size="30" onFocus="setBorder(this)" onBlur="clearBorder(this)"></td>
</tr>
<tr>
	<td>Select owner:</td>
	<td><?php createDropDown(UserServices::getActiveUsers(),'owner','Select','',$owner); ?>
	
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input type="submit" value="Search" class="save"></td>
</tr>
</table>
</form>
<br>

<?php 

if (isset($_GET['deleted'])) {
	print "<b>Selected movie has been deleted.</b>";
}

if (sizeof($_POST) > 0) {
		
	$results = MovieServices::advancedSearch($title, null, null, null, $owner, null);

	if (sizeof($results) > 0) {
				
		$header = array("Title","Category", "Media", "");
		printTableOpen();
		printRowHeader($header);
		foreach ($results as $item) {
			printTr();
			printRow($item['title']);
			printRow($item['category']);
			printRow($item['media_type']);
						
			$idRow = "'".$item['id']."|".$item['media_id']."|".$owner."'";
			printDeleteRow($idRow, $CURRENT_PAGE, "Delete movie from owner?");
			
			printTr(false);
		}
		printTableClose();
		
		
		
	} else {
		print "<b>Search returned no results.</b>";
	}
		
	
}


?>


</div>