<?php
/**
 * VCD-db - a web based VCD/DVD Catalog system
 * Copyright (C) 2003-2006 Konni - konni.com
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * @author  Hákon Birgisson <konni@konni.com>
 * @package Functions
 * @subpackage Web
 * @version $Id$
 */
?>
<?

/**
 * Fetch RSS streams from another VCD-db sites and display them for selection.
 *
 * @param string $url
 */
function showAvailableFeeds($url) {

	// Flush errors .. 
	error_reporting(0);
	
	$user_url = $url;
	
	$pos = strlen($url);
	$char = $url[($pos-1)];
	if ($char != '/') {	$url .= "/";}
	
				
	$sitefeed = $url .= "rss/";
	$feedusers = $sitefeed . "?users";
	
	$xml = simplexml_load_file($sitefeed);
	
	
	if ($xml && isset($xml->error)) {
		print $xml->error;
		print "<br/><a href=\"./addrssfeed.php\">".VCDLanguage::translate('misc.tryagain')."</a>";
		return;
	}
	if (!$xml) {
		print "No feeds found at location " . $user_url;
		print "<br/><a href=\"javascript:history.back(-1)\">".VCDLanguage::translate('misc.tryagain')."</a>";
		return;
	} 
	
	
	$xml_users = simplexml_load_file($feedusers);
	
	$title = $xml->channel->title;
	$link = $xml->channel->link;
	$description = $xml->channel->description;
	
	
	print "<form name=\"feeds\" method=\"post\" action=\"../exec_form.php?action=addfeed\">";
	print "<strong>".VCDLanguage::translate('rss.found')."</strong><br/>";
	
	
	print "<table cellspacing=\"1\" cellpadding=\"1\" border=\"0\">";
	print "<tr><td colspan=\"2\"><strong>".VCDLanguage::translate('rss.site')."</strong></td></tr>";
	print "<tr><td><input type=\"checkbox\" class=\"nof\" value=\"".utf8_decode($title)."|".$sitefeed."\" name=\"feeds[]\"></td><td>" . utf8_decode($title) . "</td></tr>";		
	print "<tr><td colspan=\"2\"><strong>".VCDLanguage::translate('rss.user')."</strong></td></tr>";
	$usersfeeds = $xml_users->rssusers->user;
	foreach ($usersfeeds as $user_feed) {
		print "<tr><td><input name=\"feeds[]\" type=\"checkbox\" class=\"nof\" value=\"".$user_feed->fullname."|".$user_feed->rsspath."\"></td><td>". $user_feed->fullname . "</td></tr>";
	}
	
	print "<tr><td colspan=\"2\" align=\"right\"><input type=\"submit\" value=\"save\" onclick=\"return rssCheck(this.form)\"></td></tr>";
	print "</table>";
	    
	
	print "</form>";
	
	// Reset error reporting
	error_reporting(ini_get('error_reporting'));
	

}

/**
 * Display a VCD-db RSS feed from anither VCD-db site.
 *
 * @param string $name
 * @param string $url
 */
function showFeed($name, $url) {
	
	// Flush errors ..
	error_reporting(0);
	
	$xml = simplexml_load_file($url);
	
	if ($xml && isset($xml->error)) {
		print $xml->error;
		return;
	}
	if (!$xml) {
		print "<p>RSS Feed not found for ".$name.", site maybe down.</p>";
		return;
	} 
	
	$items = $xml->channel->item;
    $title = $xml->channel->title;
    $link = $xml->channel->link;
	
    $pos = strpos($title, "(");
			if ($pos === false) { 
			    $img = "<img src=\"images/rsssite.gif\" align=absmiddle title=\"VCD Site feed\" border=\"0\"/>&nbsp;";
			} else {
				$img = "<img src=\"images/rssuser.gif\" align=absmiddle title=\"VCD User feed\" border=\"0\"/>&nbsp;";
			}
    
    print "<p class=normal><strong>".$img."<a href=\"".$link."\" target=\"new\">".utf8_decode($title)."</a></strong>";
    print "<ul>";
	foreach ($items as $item) {
						
		print "<li><a href=\"$item->link\" target=\"new\">". utf8_decode($item->title)."</a>";
		if (isset($item->description)) {
			print " <a href=\"$item->description\" target=\"new\">[link]</a>";
		}
		
		print "</li>";	
		
	}
	
	print "</ul></p>";
		
	// Reset error reporting
	error_reporting(ini_get('error_reporting'));
}


?>