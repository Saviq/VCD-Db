<?
$movies = MovieServices::search($search_string, $search_method);


if (sizeof($movies) > 0) {
	// Display the pager
	
	if (sizeof($movies) == 1) {
		print "<script>document.location.href='./?page=cd&vcd_id=".$movies[0]->getID()."'</script>";
		exit();
	}
	
	
	print "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\" class=\"displist\">";
	print "<tr><td class=\"header\">".VCDLanguage::translate('movie.title')."</td><td class=\"header\">".VCDLanguage::translate('movie.year')."</td><td class=\"header\">".VCDLanguage::translate('movie.media')."</td></tr>";
	foreach ($movies as $movie) {
		print "<tr>
				   <td><a href=\"./?page=cd&vcd_id=".$movie->getID()."\">".$movie->getTitle()."</a></td>
			       <td>".$movie->getYear()."</td>
		           <td>".$movie->showMediaTypes()."</td>
			   </tr>";
	}
	print "</table>";	
	
	
} else {
	print "<h1>".VCDLanguage::translate('search.search')."</h1>";
	print "<ul><br/>".VCDLanguage::translate('search.noresult')."</ul>";
}


?>