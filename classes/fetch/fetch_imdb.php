<?php
/**
 * VCD-db - a web based VCD/DVD Catalog system
 * Copyright (C) 2003-2004 Konni - konni.com
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * @author  Hákon Birgsson <konni@konni.com>
 * @package Kernel
 * @subpackage WebFetch
 * @version $Id$
 */
 
?>
<? 
if (!class_exists('imdbObj')) {
	die('imdbObj must be available');
}

/** 
	The imdb Fetch class.
	Uses the imdbObj as data container
*/
if (!defined('CACHE_FOLDER')) {
	define("CACHE_FOLDER","upload/cache/");
}

define("SEARCH_ERROR",0);
define("SEARCH_DONE",1);
define("EXACT_MATCH",2);

define("ITEM_ERROR",0);
define("ITEM_OK",1);
define("ITEM_NOTFOUND",2);

class fetch_imdb {
	
	
	private $id;
	private $imdbObj;
	private $imdb_server  = "akas.imdb.com";
	private $internal_link = "./?page=private&o=add&source=imdb&fid=";		
	
	private $got_results = false;
	private $searchcontents = null;
	private $item_contents = null;
	private $curr_value;
	private $posterUrl;
	private $search_key;
	private $max_results = 100;
	
	
	/**
	 * Constructor
	 *
	 * @return fetch_imdb
	 */
	public function fetch_imdb() {
		$this->imdbObj = new imdbObj();
		
		if (defined("IMDB_MAXRESULT") && is_numeric(IMDB_MAXRESULT)) {
			$this->max_results = IMDB_MAXRESULT;
		} 
	}	
	
	
	/**
	 * Returns the IMDB id for the current movie.
	 *
	 * @return string
	 */
	public function getImdbID() {
		return $this->id;
	}
	
	/**
	 * Get the imdb data object containg information about the
	 * fetched movie.
	 *
	 * @return imdbObj
	 */
	public function getObj() {
		return $this->imdbObj;
	}
	
	
	/**
	 * Get the HTTP URL for the poster image of the current movie.
	 *
	 * @return string
	 */
	public function getPosterUrl() {
		if (isset($this->posterUrl) && strlen($this->posterUrl) > 4) {
			return $this->posterUrl;
		} else {
			return (ITEM_NOTFOUND);
		}
	}
	
	/**
	 * Check if search returned any results.
	 *
	 * @return bool
	 */
	public function gotResults() {
		return $this->got_results;
	}
	
	/**
	 * Write out the search results to browser.
	 *
	 */
	public function showSearchResults() {
		if ($this->got_results) {
			print $this->searchcontents;
		}
	}
	
	
	/** 
		Returns true if exact math was found,
		else false, and the stores the search buffer for display
	*/
	public function search($title) {
		
		  $this->search_key = $title;		
		
        //$header = "GET /find?tt=on;mx=20;q=". rawurlencode($title) ." HTTP/1.0\r\n";
          $header = "GET /find?more=tt;q=". rawurlencode($title) ." HTTP/1.0\r\n";
          $header .= "Accept: text/html, image/png, image/x-xbitmap, image/gif, image/jpeg, */*\r\n";
          $header .= "Referer: http://".$this->imdb_server."/Find\r\n";
          $header .= "Content-type: application/x-www-form-urlencoded\r\n";
          $header .= "Accept-Encoding: *;q=0\r\n";
          $header .= "User-Agent: Mozilla/4.0 (compatible; MSIE 5.5; Windows 98; Win 9x 4.90)\r\n";
          $header .= "Host: akas.imdb.com\r\n";
          $header .= "Connection: Keep-Alive\r\n";
          $header .= "Cache-Control: no-cache\r\n";
          $header .= "\r\n";

        // Grab search results
        $site = $this->fetchPage($header, $this->imdb_server);
        
       
        //when you use the search-form on imdb.com and you search for a title that was exactly found
        //imdb uses a 302-found-page to redirect to the Title-page of this movie.
        //if this happens, we can use this imdb-id too
        if(strstr($site, "HTTP/1.0 302") || strstr($site, "HTTP/1.1 302")) { 
            ereg('\/title\/tt([0-9]+)\/', $site, $x);
            $this->id = $x[1];          
            return (EXACT_MATCH);
        }
        
        $y = preg_split('/<b>Titles /', $site);
                
        $z = 0;
        $searchData = array();

        for($i=1; $i < sizeof($y); $i++) {
        	        	
            $site = $y[$i];
            $cat=" -$i- ";
            while(ereg('<a href=\"\/title\/tt([0-9]+)\/([^\<]*)\">([^\<]*)</a>', $site, $x)) {

            	
            	// Check if search results are limited
            	if ($z > $this->max_results) {
            		break;
            	} 
            	
            	$z++;
                $site = substr($site,strpos($site,$x[0])+strlen($x[0]));
                $rest = substr($site,0,strpos($site,"</li>"));

                $help = str_replace("&#160;aka","<br>&nbsp;&nbsp;&nbsp;&nbsp;",strip_tags($rest)); 
                
                $searchData[] = array("id"   => $x[1],
                                      "name" => $x[3],
                                      "help" => $help,
                                      "cat"  => $cat);
            }
        }

        if($z == 0) {
            return (SEARCH_DONE);
        }

        // Notify of results and store them
        $this->got_results = true;
        $this->searchcontents .= "<table class=\"plain\" width=\"100%\">";
        
        $Page = basename(__FILE__);
        $Page = substr($Page, 6);
        $Page = substr($Page, 0, -4);

        $lastEntry = "";
		$j=0;
        
		
        foreach($searchData as $entry) {
        	
        	
            if($entry['cat'] != $lastEntry) {
                $this->searchcontents .= "<tr class=\"top\"><td colspan=\"2\" class=\"bold\" align=\"left\">".$entry['cat'].": ";
             
                if ($j == 0) {
            		$this->searchcontents .= "Exact Matches"; 
            	} else if($j == 1) {
            		$this->searchcontents .= "Partial Matches"; 
            	} else {
            		$this->searchcontents .= "Approx Matches";
            	}
                $j++;
                $this->searchcontents .= "</td></tr>";
                
            }
            
            $lastEntry = $entry['cat'];
            $this->searchcontents .= "<tr class=\"row\"";
            
            
            
            $this->searchcontents .= "\"><td width=\"20\">&nbsp;</td><td>";
    	    $this->searchcontents .= "<a href=\"".$this->internal_link.urlencode($entry['id'])."\">".$entry['name']."</a>";
            $this->searchcontents .= " - [<a href=\"http://".$this->imdb_server."/Title?$entry[id]\" target=\"_blank\">Info</a>]";
            
            if (strlen($entry['help']) > 2) { 
            	$this->searchcontents .= "<i>". $entry['help'] ."</i>";
            }
            
            $this->searchcontents .= "</td></tr>";
            
        }
        
        $this->searchcontents .= "</table>";

        return(SEARCH_DONE);
	
	
	}
	
	
	/**
	 * Enter description here...
	 *
	 * @param string $imdb_id
	 */
	public function populateObj($imdb_id) {
		
		$this->id = $imdb_id;
		$this->imdbObj->setIMDB($imdb_id);

		if ($this->fetch('title') == ITEM_OK) {
			$this->imdbObj->setTitle($this->getValue());
		}
		
		if ($this->fetch('year') == ITEM_OK) {
			$this->imdbObj->setYear($this->getValue());
		}
		
		
		if ($this->fetch('poster') == ITEM_OK) {
			$this->imdbObj->setImage($this->posterUrl);
		}
		
		if ($this->fetch('director') == ITEM_OK) {
			$this->imdbObj->setDirector($this->getValue());
		}
		
		if ($this->fetch('genre') == ITEM_OK) {
			$this->imdbObj->setGenre($this->getValue());
		}
		
		
		if ($this->fetch('rating') == ITEM_OK) {
			$this->imdbObj->setRating($this->getValue());
		}
		
		if ($this->fetch('cast') == ITEM_OK) {
			$this->imdbObj->setCast($this->getValue());
		}
		
		if ($this->fetch('plot') == ITEM_OK) {
			$this->imdbObj->setPlot($this->getValue());
		}
		
		if ($this->fetch('runtime') == ITEM_OK) {
			$this->imdbObj->setRuntime($this->getValue());
		}
		
		
		if ($this->fetch('country') == ITEM_OK) {
			$this->imdbObj->setCountry($this->getValue());
		}
		
		
						
	}
	
			
	
	
	 /**
	  * Get a value from the current value variable
	  *
	  *  @return mixed
	  */
	
	private function getValue() {
		$value = $this->curr_value;
		if(is_array($value)) {
            foreach($value as $i => $j) {
                $value[$i] = $this->clean($j);
            }
        } else {
            $value = $this->clean($value);
        }
        
        return $value;
	}
	
	
	/**
	 * Fetch all movie data based on the
	 * input string.
	 *
	 * @param string $entry
	 * @return string
	 */
	private function fetch($entry) {
	
		// fill up the contents
		if (strcmp($entry, 'plot') == 0) {
			$this->item_contents = $this->getCachedPage("/title/tt".$this->id."/plotsummary", "http://".$this->imdb_server."/title/tt".$this->id."/");
		} else {
			$this->item_contents = $this->getCachedPage("/title/tt".$this->id."/", "http://".$this->imdb_server."/Find");
		}
		
		 
		switch ($entry) {
			case 'title':
				
				/** 
					Fetch the Title
				*/
		        if(!eregi("<STRONG CLASS=\"title\">([^\<]*) <SMALL>\(<A HREF=\"/Sections/Years/([0-9]{4})", $this->item_contents, $x)) {
		               return (ITEM_ERROR);
		        } else {
		            $ret = $x[1];
		            $ret = addslashes($ret);                
		            $this->curr_value = $ret;
		        }
			
				break;
				
			case 'year':
				
				 /** 
		        	Fetch the year
		         */
		        if(!eregi("<STRONG CLASS=\"title\">([^\<]*) <SMALL>\(<A HREF=\"/Sections/Years/([0-9]{4})", $this->item_contents, $x)) {
		             return (ITEM_ERROR);
		        } else {
		        	$ret = $x[2];
		        	if($ret == '') {
		        		$ret=0;
		        	}
		        	$this->curr_value = $ret;
		        }
			
				break;
				
				
			case 'poster':
				
				/** 
	    	    	Fetch the poster url
		        */
				$regxposter = '<a name="poster" href="photogallery" title="([^<]*)"><img border="0" alt="([^<]*)" title="([^<]*)" src="([^<]*)" height="([0-9]{2,3})" width="([0-9]{2,3})"></a>';
		        if(!eregi($regxposter, $this->item_contents, $x)) {
		            return (ITEM_ERROR);
		        } else {
		        	$ret = $x[4];
		        	$ret = addslashes($ret);
		        	$this->posterUrl = $ret;
		        }
			
				break;
				
				
			case 'director':
				
				/** 
		         	Fetch the director
		        */
		        if(!preg_match('#Directed by.*\n[^<]*<a href="/Name?[^"]*">([^<]*)</a>#i', $this->item_contents, $x)) {
		            return (ITEM_ERROR);
		        } else {
		        	$ret = $x[1];
		        	$ret = addslashes($ret);
		        	$this->curr_value = $ret;
		        }
			
				break;
				
				
			case 'genre':
			
				 /** 
		        	Fetch the movie genre
		        */
		        if(!preg_match('#Genre:</b>(.*?)<br>#msi', $this->item_contents, $gen)) {                    
		            return (ITEM_ERROR);
		        } else {
		        	$gen = $gen[1];
		            $ret = array();
		            while(eregi("<A HREF=\"/Sections/Genres/[a-zA-Z\\-]*/\">([a-zA-Z\\-]*)</A>", $gen, $x)) {
		                $gen = substr($gen,strpos($gen,$x[0])+strlen($x[0]));
		                $ret[] = addslashes($x[1]);
		            }
		
		            if (sizeof($ret) == 0) {
		                 return (ITEM_ERROR);
		            } else {
		            	$this->curr_value = $ret;
		            }
		        	
		        }
				
				break;
				
				
			case 'rating':
				
				 /** 
		        	Fetch the rating    	
		        */      
				 if(!eregi("<B>([0-9]).([0-9])/10</B> \([0-9,]+ votes\)", $this->item_contents, $x)) {
				 	return (ITEM_ERROR);
				 } else {
				 	$ret = $x[1].$x[2];
				    $ret = $ret/10;
				    $this->curr_value = $ret;
				 }
			
				break;
				
				
			case 'cast':
				
				/** 
					Fetch the cast and roles
				*/
		        $ret = array();
		        $i=0;
		        while(eregi('<td valign="top"><a href="/name/nm([^"]+)">([^<]*)</a></td><td valign="top" nowrap="1"> .... </td><td valign="top">([^<]*)</td>', $this->item_contents, $x)) {
		            $i++;
		            $this->item_contents = substr($this->item_contents,strpos($this->item_contents,$x[0])+strlen($x[0]));
		            $ret[] = addslashes($x[2] ." .... " .$x[3]);
		        }
		        if (sizeof($ret) == 0) {
		            return (ITEM_ERROR);
		        } else {
		        	$this->curr_value = $ret;
		        }
			
				break;
				
				
			case 'plot':
			
				/** 
		        	Plot summary
		        */
		        if(eregi('<p class="plotpar">([^<]*)</p>', $this->item_contents, $x)) {
		            $ret = addslashes($x[1]); //plot exists:
		        } else {
		            //plot doesn't exist, use plot-outline from title-page:
		            $this->item_contents = $this->getCachedPage("/title/tt".$this->id."/", "http://".$this->imdb_server."/Find");
		            preg_match("#Plot Outline:</b>([^<]*)#", $this->item_contents, $x);
		            $ret = @addslashes($x[1]);
		            // if there's no plot outline fetch tagline.
		            if(!$ret) {
		                $x = array();
		                if(!preg_match("#Tagline:</b>([^<]*)#", $this->item_contents, $x)) {
							return (ITEM_ERROR);
		                }
		                $ret = addslashes($x[1]);
		            }
		        }
		        
		        $this->curr_value = $ret;
			
				
				break;
				
				
			case 'runtime':
				
				/** 
		        	Runtime
		        */
			    if(!preg_match('#<b class="ch">Runtime:</b>\n([0-9]+) min#i', $this->item_contents,$x)) {                    
			       return (ITEM_ERROR);
			    } else {
			    	$ret = $x[1];
			    	$ret = addslashes($ret);
			    	$this->curr_value = $ret;
			    }
			
				break;
				
				
			case 'akas':
				
				/** 
			    	Alterative title
			    */
			    $ret = array();
		        if(eregi('Also Known As</b>:</b><br>(.*)<b class="ch"><a href="/mpaa">MPAA</a>',$this->item_contents, $y)) {
		            $this->item_contents = $y[0];
		            while(eregi('<i([^>]*)>([^<]*)</i>', $this->item_contents, $x)) {
		                if(eregi('USA', $x[2]))
		                    $ret[] = addslashes(str_replace("&#32;", " ", $x[2]));
		                	$this->item_contents = substr($this->item_contents,strpos($this->item_contents,$x[0])+strlen($x[0]));
		            	}
		        }
		        if(sizeof($ret)==0) {
		        	return (ITEM_ERROR);
		        } else {
		        	$this->curr_value = $ret;
		        }
			
				break;
				
				
			case 'country':
				
				/** 
		        	Production country list
		        */
		        $ret = array();
		        while(eregi('<a href="/Sections/Countries/([^>]*)>([^<]*)</a>', $this->item_contents, $x)) {
		            $this->item_contents = substr($this->item_contents,strpos($this->item_contents,$x[0])+strlen($x[0]));
		            $ret[] = addslashes($x[2]);
		        }
		        if (sizeof($ret)==0) {
		            return (ITEM_ERROR);
		        } else {
		        	$this->curr_value = $ret;
		        }
			
			
				break;
		
				
			default:
				return (ITEM_NOTFOUND);
			
		}
		
		
		return (ITEM_OK);
			
	}
	
	
	
	
	/**
	* send a request-header to a server and return the returned data
	*
	* @param string the request-header
	* @param string Host where to download the site (host:port)
	* @return string the fetched HTML-code
	*/
	private function fetchPage($requestHeader, $server, $url = "")	{
		$psplit = split(":",$server);
		$pserver = $psplit[0];
		
		if(isset($psplit[1])) {
			$pport = $psplit[1];
		} else {
			$pport = 80;
		}
		
		if (defined('USE_PROXY') && USE_PROXY == 1) {

			if (strcmp($url, "") == 0) {
				$surl = "http://".$this->imdb_server."/find?more=tt;q=" . rawurlencode($this->search_key);
				return VCDUtils::proxy_url($surl);
			} else {
				return VCDUtils::proxy_url($url);
			}
			
			
			
			
			
		} else {
			
			$fp = @fsockopen($pserver, $pport);
			if (!$fp) {
			print "<p><b>Error connecting to $pserver:$pport</b></p>";
				return false;
			}
	
			fputs($fp, $requestHeader);
			$site = "";
			
			while (!feof($fp)) {
				$site .= fgets ($fp, 1024);
			}
			
			fclose($fp);
			return ($site);
			
			
			
		}

		
	}
	
	
	/**
	 * Get cached moviepage from HD
	 *
	 * @param string $url
	 * @param string $referer
	 * @return string
	 */
	function getCachedPage($url, $referer="http://akas.imdb.com/") {
        return($this->fetchCachedUrl($url, "akas.imdb.com", $referer));
    }
	
	
	/**
	 * Get cached search results page from HD
	 *
	 * @param string $url
	 * @param string $host
	 * @param string $referer
	 * @return string
	 */
	private function fetchCachedUrl($url, $host, $referer)	{

		$cacheFileName = preg_replace("#([^a-z0-9]*)#", "", $url);
		$cacheFileName = CACHE_FOLDER."imdb-".$cacheFileName;

		if(file_exists($cacheFileName)) {
			return (implode("", file($cacheFileName)));
		}
		
		$header = "GET ".$url." HTTP/1.0\r\n";
		$header .= "User-Agent: Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0)\r\n";
		$header .= "Accept: image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, */*\r\n";
		$header .= "Accept-Language: de\r\n";
		$header .= "Referer: ".$referer."\r\n";  //with given referer
		$header .= "Host: ".$host."\r\n";
		$header .= "Connection: Keep-Alive\r\n";
		$header .= "Cache-Control: no-cache\r\n";
		$header .= "\r\n";

		$proxy_url = "http://" . $this->imdb_server . $url;
		
		$contents = $this->fetchPage($header, $host, $proxy_url);
		
		//save the fethed data in cache
		$fp = fopen($cacheFileName, "w");
		fwrite($fp, $contents);
		fclose($fp);

		return($contents);
	}
	
	
	
	
	/**
	 * Clean unwanted data from the string item
	 *
	 * @param string $strData
	 * @return string
	 */
	private function clean($strData) {
		while(ereg("&#([0-9]{3});", $strData, $x)) {
			$strData = str_replace("&#".$x[1].";", chr($x[1]), $strData);
		}
		return $strData;
	}
	
	
	
	
	
}


?>