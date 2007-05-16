<h1>VCD-db maintainance tools</h1>

<?php

$header = array("Action", "Last run","");
printTableOpen();
printRowHeader($header);

printTr();
printRow("Clean up orphan movies");
printRow("Never");
printRow("<a href=\"javascript:runTask(1);\"><img src=\"../images/admin/cog.png\" title=\"Execute task\" border=\"0\"></a>");
printTr(false);

printTr();
printRow("Move all covers from hard-drive to database");
printRow("Never");
printRow("<a href=\"javascript:runTask(2);\"><img src=\"../images/admin/cog.png\" title=\"Execute task\" border=\"0\"></a>");
printTr(false);

printTr();
printRow("Move all covers from database to hard-drive");
printRow("Never");
printRow("<a href=\"javascript:runTask(3);\"><img src=\"../images/admin/cog.png\" title=\"Execute task\" border=\"0\"></a>");
printTr(false);

printTr();
printRow("Clean up the cache folder");
printRow("Never");
printRow("<a href=\"javascript:runTask(4);\"><img src=\"../images/admin/cog.png\" title=\"Execute task\" border=\"0\"></a>");
printTr(false);
			
printTableClose();

?>