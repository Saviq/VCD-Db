<h1>VCD-db maintainance tools</h1>


<?php

if (isset($_POST) && sizeof($_POST)>0) {
	
		
	$task_id = $_GET['task_id'];
	
	if (isset($task_id) && $task_id == 5) {
		// Check selected duplicate entries
		$list = $_SESSION['duplicatelist'];
		foreach ($_POST as $index => $dominant) {
			
			$currItems = $list[$index];
			$arrToBeMerged = array();
			foreach ($currItems as $vcdObj) {
				if ($vcdObj->getID() != $dominant) {
					array_push($arrToBeMerged, $vcdObj->getID());
				} 
			}
			
			
			// Process the movies to be merged in the businessLayer
			$status = MovieServices::mergeMovies($dominant, $arrToBeMerged);
			if ($status) {
				$message = $task_id."|"."Successfully merged " . sizeof($arrToBeMerged) . " items.";
			} else {
				$message = $task_id."|"."Failed to fix the duplicate entries.";
			}
						
			VCDLog::addEntry(VCDLog::EVENT_TASKS, $message);
		
		}
		$_SESSION['duplicatelist'] = null;
		header("Location: ./?page=tools&task_id={$task_id}"); /* Redirect browser */
	
	}

}

$showTools = true;

if (isset($_GET['do']) && $_GET['do'] == 'process') {
	
	$showTools = false;
	switch ($_GET['task_id']) {
		case 5:
			
			$list = $_SESSION['duplicatelist'];
			
			print "<form method=\"post\" action=\"index.php?page=tools&task_id={$_GET['task_id']}\">";
			
			$header = array("Id", "Title", "Copies", "Dominant");
			printTableOpen();
			printRowHeader($header);
			
			$iCounter = 0;
			foreach ($list as $listArray) {
				
				$radio = "<input type=\"radio\" name=\"{$iCounter}\" value=\"{#}\">";
				
				foreach ($listArray as $movieObj) {
					printTr();
					printRow($movieObj->getID());
					printRow("<a href=\"../?page=cd&amp;vcd_id={$movieObj->getID()}\" target=\"_new\">{$movieObj->getTitle()}</a>");
					printRow($movieObj->getNumCopies());
					printRow(str_replace('{#}', $movieObj->getID(), $radio));
					printTr(false);
				}
				
				printTr();
				printRow("<hr/>");
				printRow("<hr/>");
				printRow("<hr/>");
				printRow("<hr/>");
				printTr(false);
				$iCounter++;
				
			}
			
								
			printTableClose();
			
			print "<div align=\"right\"><input type=\"button\" class=\"button\" value=\"Cancel\" onclick=\"location.href='./?page=tools'\">";
			print "&nbsp;<input type=\"submit\" value=\"Execute\" class=\"button\"></div>";
			
			print "</form>";
			
			break;
	
		default:
			header("Location: ./?page=tools"); /* Redirect browser */
	}
	
}


?>



<?php
if ($showTools) {

$header = array("Action", "Last run","");
printTableOpen();
printRowHeader($header);

printTr();
printRow("Clean up orphan movies");
printRow(getTaskStatus(1));
printRow("<a href=\"javascript:runTask(1);\"><img src=\"../images/admin/cog.png\" title=\"Execute task\" border=\"0\"></a>");
printTr(false);

printTr();
printRow("Move all covers from hard-drive to database");
printRow(getTaskStatus(2));
printRow("<a href=\"javascript:runTask(2);\"><img src=\"../images/admin/cog.png\" title=\"Execute task\" border=\"0\"></a>");
printTr(false);

printTr();
printRow("Move all covers from database to hard-drive");
printRow(getTaskStatus(3));
printRow("<a href=\"javascript:runTask(3);\"><img src=\"../images/admin/cog.png\" title=\"Execute task\" border=\"0\"></a>");
printTr(false);

printTr();
printRow("Clean up the cache folder");
printRow(getTaskStatus(4));
printRow("<a href=\"javascript:runTask(4);\"><img src=\"../images/admin/cog.png\" title=\"Execute task\" border=\"0\"></a>");
printTr(false);

printTr();
printRow("Fix duplicate entries");
printRow(getTaskStatus(5));
printRow("<a href=\"javascript:runTask(5);\"><img src=\"../images/admin/cog.png\" title=\"Execute task\" border=\"0\"></a>");
printTr(false);
			

printTr();
printRow("Fix broken pornstar images");
printRow(getTaskStatus(6));
printRow("<a href=\"javascript:runTask(6);\"><img src=\"../images/admin/cog.png\" title=\"Execute task\" border=\"0\"></a>");
printTr(false);

printTr();
printRow("Create .htaccess for mod_rewrite");
printRow(getTaskStatus(7));
printRow("<a href=\"javascript:runTask(7);\"><img src=\"../images/admin/cog.png\" title=\"Execute task\" border=\"0\"></a>");
printTr(false);

printTableClose();
}
?>