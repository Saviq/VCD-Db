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
 * @package Kernel
 * @version $Id$
 */
?>
<?php

define("ALBUMS"   ,"upload/screenshots/albums/");
define("GENERATED","upload/screenshots/generated/");

class VCDScreenshot {

	private $folder_id;
	
	private $image_count;
	private $images = array();
	private $thumbs = array();

	private $current_page = 0;
	private $current_image;
	
	private $display_rows	= 3;
	private $display_cols	= 3;
	private $thumb_size		= 120;
	private $thumb_border	= 0;


	/**
	 * Object constructor
	 *
	 * @param int $id
	 */
	public function __construct($id) {
		if (!is_numeric($id)) {
			return;
		}
		
		$this->folder_id = $id;
		$this->image_count = 0;
		$this->initialize();	
		
	}

	
	/**
	 * Set the page index of current screenshot collection
	 *
	 * @param int $pagenum
	 */
	public function setPage($pagenum) {
		if ((is_numeric($pagenum)) && ($pagenum <= ($this->image_count) / ($this->display_rows*$this->display_cols))) {
			$this->current_page = $pagenum;
		}
		
	}
	
	
	/**
	 * Show specified image
	 *
	 * @param int $imageID
	 */
	public function showImage($imageID) {
		if (!is_numeric($imageID) || $imageID >= $this->image_count) {
			return;
		}
		
		$this->current_image = $imageID;
		print "<div align=\"center\"><img src=\"".$this->images[$imageID]."\" border=\"0\" alt=\"\" class=\"imgx\"/></div>";
		$this->drawNavigation(true);
	}
	
	/**
	 * Print out the current screenshot page.
	 *
	 */
	public function showPage() {
		if ($this->image_count == 0) {
			return;
		}
		
		print "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\" class=\"list\">";
		$i = 1;
		$rowcounter=1;
		
		
		if ($this->current_page == 0) {
		
		foreach ($this->thumbs as $index => $thumbnail) {
			if ($rowcounter > $this->display_rows) { break;}
			
			if ($index == 0 || ($index % $this->display_cols) == 0) {
				print "<tr>";
			}
			
			print "<td align=\"center\"><a href=\"screens.php?s_id=".$this->folder_id."&amp;image_id=".$index."\" onmouseover=\"self.status='Screenshot ".($index+1)."'; return true\" onmouseout=\"self.status=''\"><img src=\"".$thumbnail."\" border=\"0\" class=\"imgx\"/></a></td>";
			
			if ($index != 0 && ($i % $this->display_cols) == 0) {
				$i = 0;
				$rowcounter++;
				print "</tr>";
			}
			$i++;
		}
			
		} else {
			
			$index = $this->display_cols*$this->display_rows*$this->current_page;
			
			for($j = $index; $j < $this->image_count; $j++) {
				if ($rowcounter > $this->display_rows) { break;}
			
				if ($j == $index || ($j % $this->display_cols) == 0) {
					print "<tr>";
				}
				
				print "<td align=\"center\"><a href=\"screens.php?s_id=".$this->folder_id."&amp;image_id=".$j."\" onmouseover=\"self.status='Screenshot ".($j+1)."'; return true\" onmouseout=\"self.status=''\"><img src=\"".$this->thumbs[$j]."\" border=\"0\" class=\"imgx\"/></a></td>";
				
				if ($j != 0 && ($i % $this->display_cols) == 0) {
					$i = 0;
					$rowcounter++;
					print "</tr>";
				}
				$i++;
			}				
		}
		
		
		print "</table>";
		
		$this->drawNavigation();
		
	}
	
	
	/**
	 * Print the navigation links for current screenshot collection.
	 *
	 * @param bool $imagemode
	 */
	private function drawNavigation($imagemode = false) {
		
		if ($imagemode) {
						
			$previmg = "";
			$nextimg = "";
			if (($this->current_image+1) < $this->image_count) {
				$nextimg  = "<a href=\"screens.php?s_id=".$this->folder_id."&amp;image_id=".($this->current_image+1)."\">Next image &gt;&gt;</a><br/>";
				$nextimg .= "<a href=\"screens.php?s_id=".$this->folder_id."&amp;image_id=".($this->current_image+1)."\"><img src=\"".$this->thumbs[($this->current_image+1)]."\" border=\"0\" class=\"imgx\"/></a>";
			}
			
			if (!$this->current_image == 0) {
				$previmg  = "<a href=\"screens.php?s_id=".$this->folder_id."&amp;image_id=".($this->current_image-1)."\">&lt;&lt;Previous image</a><br/>";
				$previmg .= "<a href=\"screens.php?s_id=".$this->folder_id."&amp;image_id=".($this->current_image-1)."\"><img src=\"".$this->thumbs[($this->current_image-1)]."\" border=\"0\" class=\"imgx\"/></a>";
			} 

							
			print "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\"><tr>";
			print "<td align=\"left\" width=\"25%\">".$previmg."</td>";
			print "<td align=\"center\" width=\"50%\"><a href=\"screens.php?s_id=".$this->folder_id."&amp;slide=".$this->getPageLink()."\">Page view</a></td>";
			print "<td align=\"right\" width=\"25%\">".$nextimg."</td>";
			print "</tr></table>";
			
		
		} else {
						
			if (($this->current_page+1)*$this->display_cols*$this->display_rows < $this->image_count)
				$next = "<a href=\"screens.php?s_id=".$this->folder_id."&amp;slide=".($this->current_page+1)."\">Next &gt;&gt;</a>";
			else {
				$next = "";
			}
				
				
			if ($this->current_page != 0) {
				$prev = "<a href=\"screens.php?s_id=".$this->folder_id."&amp;slide=".($this->current_page-1)."\">&lt;&lt; Back</a> || ";			
			} else {
				$prev = "";
			}
			
			
			print "<br/>";
			print "<div align=\"center\" class=\"displist\">$prev $next</div>";
		}
		
		
	}
	
	/**
	 * Get the id of current page to use in the pagelink
	 *
	 * @return int
	 */
	private function getPageLink() {
		if (isset($this->current_image) && is_numeric($this->current_image)) {
			return 0;
		} else {
			return $this->current_page;
		}
	}
	
	/**
	 * Initialize the screenshots that belong to the movie
	 *
	 */
	private function initialize() {
		// check for the screenshots folder
		$screens = $this->findfile(ALBUMS.$this->folder_id,'/\.(jpg)$/');
		if (is_array($screens) && sizeof($screens) > 0) {
			$this->images = $screens;
			$this->image_count = sizeof($screens);
			
			// check for generated thumbnails
			$thumbs = $this->findfile(GENERATED.$this->folder_id,'/\.(jpg)$/');
			if (is_array($thumbs) && sizeof($thumbs) > 0) {
				
				$this->thumbs = $thumbs;
				
				// is the image count equal ?
				if ($this->image_count != sizeof($thumbs)) {
					$this->generateThumbs(false);
				}
				
				
			} else {
				$this->generateThumbs();
			}
			
							
		}
	
	}
	
	
	
	/**
	 * Create thumbnails from the screenshots if they do not already exist.
	 *
	 * @param bool $all | Regenerate all thumbnails or just those that are missing
	 */
	private function generateThumbs($all = true) {
		
		// Create the list of thumbsnails that should be available
		$newscreens = array();
		foreach ($this->images as $file) {
			preg_match("/(.*)\.jpg/i",$file,$parts);
			$thumbpic = "$parts[1]__scaled_$this->thumb_size.jpg";
			array_push($newscreens, str_replace(ALBUMS, GENERATED, $thumbpic));
		}
		
		
		if ($all) {
			// create all the thumbnails.
			
			// Check for the directory
			if (!fs_is_dir(GENERATED.$this->folder_id)) {
				fs_mkdir(GENERATED.$this->folder_id, 0755);
			}
			
			for ($i=0; $i < $this->image_count; $i++) {
				$this->CreateImage($this->thumb_size, $this->images[$i], $newscreens[$i], $this->thumb_border);
			}
			
			
		} else {
			// create the missing ones.
			for ($i=0; $i < $this->image_count; $i++) {
				if (!fs_file_exists($newscreens[$i])) {
					$this->CreateImage($this->thumb_size, $this->images[$i], $newscreens[$i], $this->thumb_border);
				}
				
			}
			
		}
		
		// INITilize again
		$this->initialize();
	
	}
	
	/**
	 * Create a thumbnail image from the source image
	 *
	 * @param int $size | The size of the thumbnail
	 * @param string $source | The filepath of the original image
	 * @param string $dest | The Savepath of the thumbnail
	 * @param int $border | The size of the border on the thumbnail
	 */
	private function CreateImage($size, $source, $dest, $border=0) {
		$sourcedate = 0;
		$destdate = 0;
		if (file_exists($dest)) {
			clearstatcache();
			$sourceinfo = stat($source);
			$destinfo = stat($dest);
			$sourcedate = $sourceinfo[10];
			$destdate = $destinfo[10];
		}
		if (!file_exists("$dest") or ($sourcedate > $destdate)) {

			$imgsize = getimagesize($source);
			$width = $imgsize[0];
			$height = $imgsize[1];
	
			$new_width = $size;
			$new_height = ceil($size * $height / $width);

			$im = ImageCreateFromJPEG($source); 
			$new_im = ImageCreateTrueColor($new_width,$new_height);
			ImageCopyResized($new_im,$im,0,0,0,0,$new_width,$new_height,ImageSX($im),ImageSY($im));
			ImageJPEG($new_im,$dest,75);
							
		}
	}
	
	

	/**
	* @return Array
	* @param $location String
	* @param $fileregex String
	* @desc Search folder for files with certain extensions defined in the $fileregex parameter.
	* @access Private
 */
	private function findfile($location='',$fileregex='') {
   		if (!$location or !is_dir($location) or !$fileregex) {
       		return false;
   		}
 
		$matchedfiles = array();
	 
	   	$all = opendir($location);
	   	while ($file = readdir($all)) {
	       	if (is_dir($location.'/'.$file) and $file <> ".." and $file <> ".") {
	         	$subdir_matches = $this->findfile($location.'/'.$file,$fileregex);
	         	$matchedfiles = array_merge($matchedfiles,$subdir_matches);
	         	unset($file);
	       	}
	       	elseif (!is_dir($location.'/'.$file)) {
	         	if (preg_match($fileregex,$file)) {
	             	array_push($matchedfiles,$location.'/'.$file);
	         	}
		       }
	   		}	
	   	   closedir($all);
		   unset($all);
	       return $matchedfiles;
 	}
	

	 	
	 	
	
}


?>