<h1>VCD-db maintainance tools</h1>

<?php

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
			
printTableClose();

?>