<?
$VCDClass = $ClassFactory->getInstance("vcd_movie");
$movies = $VCDClass->search($search_string, $search_method);


if (sizeof($movies) > 0) {
	// Display the pager
	
	if (sizeof($movies) == 1) {
		print "<script>document.location.href='./?page=cd&vcd_id=".$movies[0]->getID()."'</script>";
		exit();
	}
	
	
	print "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\" class=\"displist\">";
	print "<tr><td class=\"header\">Title</td><td class=\"header\">Year</td><td class=\"header\">Media</td></tr>";
	foreach ($movies as $movie) {
		print "<tr>
				   <td><a href=\"./?page=cd&vcd_id=".$movie->getID()."\">".$movie->getTitle()."</a></td>
			       <td>".$movie->getYear()."</td>
		           <td>".$movie->showMediaTypes()."</td>
			   </tr>";
	}
	print "</table>";	
	
	
} else {
	print "<h1>".$language->show('SEARCH')."</h1>";
	print "<ul><br/>Search returned no results </ul>";
}


?>