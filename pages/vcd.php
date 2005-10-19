<? 
$cd_id = $_GET['vcd_id'];

global $language;
$VCDClass = VCDClassFactory::getInstance("vcd_movie");
$movie = $VCDClass->getVcdByID($cd_id);
if (!$movie instanceof vcdObj ) {
	redirect();
}

if ($movie->isAdult()) {
	include_once('adultvcd.php');
	
} else {
	$imdb = $movie->getIMDB();
	
?>


<table width="100%" border="0" cellspacing="0" cellpadding="0" class="displist">
<tr>
	<td width="65%" class="header"><?= $language->show('M_MOVIE')?></td>
	<td width="35%" class="header"><?= $language->show('M_ACTORS')?></td>
</tr>
<tr>
	<td valign="top">
	<!-- Info table -->
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" width="10%">
		<? 
		$coverObj = $movie->getCover("thumbnail");
		if (!is_null($coverObj)) {
			$coverObj->showImage();
		} else {
			print "<div class=\"poster\">No poster available</div>";
		}
		?>
		</td>
		<td valign="top">
			<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td colspan="2"><strong><?= $movie->getTitle() ?></strong></td>
			</tr>
			<tr>
				<td width="30%"><?= $language->show('M_CATEGORY')?>:</td>
				<td><? 
				$mObj = $movie->getCategory();
				if (!is_null($mObj)) {
					print "<a href=\"./?page=category&amp;category_id=".$mObj->getID()."\">".$mObj->getName()."</a>";
				} 
				?></td>
			</tr>
			<tr>
				<td nowrap="nowrap"><?= $language->show('M_YEAR')?>:</td>
				<td><?= $movie->getYear() ?></td>
			</tr>
			<tr>
				<td><?= $language->show('M_COPIES')?>:</td>
				<td><?= $movie->getNumCopies() ?></td>
			</tr>
		<?
		$SETTINGSClass = VCDClassFactory::getInstance('vcd_settings');
		$arr = $SETTINGSClass->getMetadata($movie->getID(), VCDUtils::getUserID(), 'mediaindex');
		
		if (is_array($arr) && sizeof($arr) == 1) {
		?>
			<tr>
				<td><?= $language->show('M_MEDIAINDEX')?>:</td>
				<td><?= $arr[0]->getMetadataValue() ?></td>
			</tr>
		<?
		}
		?>
			<tr>
				<td colspan="2">
				<? 
					if (isset($imdb)) { 
						$imdb->printImageLink(); $imdb->drawRating();
					}
				?>
				</td>
			</tr>
			<? 
				if (VCDUtils::isLoggedIn()) {
					if ($SETTINGSClass->isOnWishList($movie->getID())) {
						?><tr><td>&nbsp;</td><td><a href="./?page=private&amp;o=wishlist">(<?= $language->show('W_ONLIST')?>)</a></td></tr><?
					} else {
						?><tr><td>&nbsp;</td><td><a href="#" onclick="addtowishlist(<?=$movie->getID()?>)"><?= $language->show('W_ADD')?></a></td></tr><?					
					}
					
				}
			?>
			
			
			<? 
				if (VCDUtils::hasPermissionToChange($movie)) {
					?>
						<tr><td>&nbsp;</td><td><a href="#" onclick="loadManager(<?=$movie->getID()?>)"><?= $language->show('M_CHANGE')?></a></td></tr>
					<?
				}
				
				// Display seen box if activated
				if (VCDUtils::isLoggedIn() && VCDUtils::isOwner($movie) && $_SESSION['user']->getPropertyByKey('SEEN_LIST')) {
   					$arrList = $SETTINGSClass->getMetadata($movie->getID(), VCDUtils::getUserID(), 'seenlist');
   					print "<tr><td>&nbsp;</td><td>";
   					if (sizeof($arrList) == 1 && ($arrList[0]->getMetadataValue() == 1)) {
				   		print "<a href=\"#\"><img src=\"images/mark_seen.gif\" alt=\"".$language->show('S_NOTSEENITCLICK')."\" border=\"0\" style=\"padding-right:5px\" onclick=\"markSeen(".$movie->getID().", 0)\"/></a>";
				    	print $language->show('S_SEENIT');
				    } else {
				    	print "<a href=\"#\"><img src=\"images/mark_unseen.gif\" alt=\"".$language->show('S_SEENITCLICK')."\" border=\"0\" style=\"padding-right:5px\" onclick=\"markSeen(".$movie->getID().", 1)\"/></a>";
				    	print $language->show('S_NOTSEENIT');
				    }
				    print "</td></tr>";
				}
				
				// Display Play button
				
				if (VCDUtils::isLoggedIn() && VCDUtils::isOwner($movie)) {
					$command = "";
					if (getPlayCommand($movie, VCDUtils::getUserID(), $command)) {
						print "<tr><td>&nbsp;</td><td>";
					    print "<a href=\"javascript:void(0)\"><img src=\"images/play.gif\" alt=\"\" border=\"0\" onclick=\"playFile('".$command."')\"/></a>";
				    	print "</td></tr>";	
					}
					
				}
				
			?>
			</table>
		
		
		</td>
	</tr>
	</table>

	<div id="imdbinfo">
	<h2><?= $language->show('M_FROM')?> IMDB:</h2>
	<? if (isset($imdb)) { ?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="normal">
	<tr>
		<td><?= $language->show('M_TITLE')?></td>
		<td><?=$imdb->getTitle() ?></td>
	</tr>
	<tr>
		<td valign="top"><?= $language->show('M_ALTTITLE')?></td>
		<td><?=$imdb->getAltTitle() ?></td>
	</tr>
	<tr>
		<td>IMDB <?= $language->show('M_GRADE')?>:</td>
		<td><?=$imdb->getRating() ?></td>
	</tr>
	<tr>
		<td><?= $language->show('M_DIRECTOR')?>:</td>
		<td><?=$imdb->getDirector() ?></td>
	</tr>
	<tr>
		<td nowrap="nowrap"><?= $language->show('M_COUNTRY')?>:</td>
		<td><?=$imdb->getCountry() ?></td>
	</tr>
	<tr>
		<td valign="top">IMDB <?= $language->show('M_CATEGORY')?>:</td>
		<td><?=parseCategoryList($imdb->getGenre()) ?></td>
	</tr>
	<tr>
		<td><?= $language->show('M_RUNTIME')?>:</td>
		<td><?=$imdb->getRuntime() ?> <?= $language->show('M_MINUTES')?></td>
	</tr>
	</table>
	
	
	<? } else { print "<ul><li>".$language->show('I_NOT')."</li></ul>"; } ?>
	</div>
	
	
	<div id="imdbplot">
	<a name="plot"></a>
	<h2><?= $language->show('M_PLOT')?>:</h2>
	<? if (isset($imdb)) { showPlot($imdb->getPlot()); } else { print "<ul><li>".$language->show('M_NOPLOT')."</li></ul>"; }?>
	</div>
	
	<div id="covers">
	<h2><?= $language->show('M_COVERS')?></h2>
	<?
			
		$covers = $movie->getCovers();
		$bNoCovers = true;
		if (count($covers) > 0) {
			print "<ul>";
			foreach ($covers as $cdcoverObj) {
				if (!$cdcoverObj->isThumbnail()) {
					$bNoCovers = false;
					print "<li><a href=\"#\" onclick=\"showcover('".$cdcoverObj->getFilename()."',".(int)$cdcoverObj->isInDB().",".$cdcoverObj->getImageID().")\">".$cdcoverObj->getCoverTypeName() . "</a></li>";
				}
			}
			print "</ul>";
		} 
		
		if ($bNoCovers) {
			print "<ul><li>".$language->show('M_NOCOVERS')."</li></ul>";
		}
	?>
	</div>
		
	
	<div id="copies">
	<h2><?= $language->show('M_AVAILABLE')?>:</h2>
	<? $movie->displayCopies() ?>
	</div>
	
	<div id="comments">
	<h2><?= $language->show('C_COMMENTS')?> (<a href="javascript:show('newcomment')"><?= $language->show('C_ADD')?></a>)</h2>
	</div>
	
	<div id="newcomment" style="padding-left:15px;visibility:hidden;display:none">
		<?  if(!VCDUtils::isLoggedIn()) {
				print "<span class=\"bold\">". $language->show('C_ERROR')."</span>";
			} else { ?>
	
		<span class="bold"><?= $language->show('C_TYPE')?>:</span>
		<form name="newcomment" method="POST" action="exec_form.php?action=addcomment">
		<input type="hidden" name="vcd_id" value="<?=$movie->getID()?>"/>
		<table cellpadding="0" cellspacing="0" border="0" class="plain">
			<tr>
				<td valign="top"><?= $language->show('C_YOUR')?>:</td>
				<td><textarea name="comment" rows="4" cols="30"></textarea></td>
			</tr>
			<tr>
				<td><?= $language->show('M_PRIVATE')?>:</td>
				<td><input type="checkbox" name="private" class="nof" value="private"/>
				&nbsp;&nbsp;<input type="submit" value="<?=$language->show('C_POST')?>"/>
				</td>
			</tr>
		</table>
		</form>
		<? } ?>
		<br/>
	</div>
	
	<?
		$commArr = $SETTINGSClass->getAllCommentsByVCD($movie->getID());
		if (empty($commArr)) {
			print "<ul><li>".$language->show('C_NONE')."</li></ul>";
		} else {
			print "<ul>";
			foreach ($commArr as $commObj) {
				
				$delcomment = "";
				if (VCDUtils::isLoggedIn() && $commObj->getOwnerID() == VCDUtils::getUserID()) {
					$delcomment = "<a href=\"#\" onclick=\"location.href='exec_query.php?action=delComment&amp;cid=".$commObj->getID()."'\"><img src=\"images/icon_del.gif\" alt=\"Delete comment\" align=\"absmiddle\" border=\"0\"/></a>";
				}
				
				if ($commObj->isPrivate()) {
					if (VCDUtils::isLoggedIn() && $commObj->getOwnerID() == VCDUtils::getUserID())	{
						print "<li>".$commObj->getOwnerName()." (".$commObj->getDate().") (<i>Private comment</i>) $delcomment
					   <br/><i style=\"padding-left:3px;display:block\">".$commObj->getComment(true)."</i></li>";
					}
				} else {
					print "<li>".$commObj->getOwnerName()." (".$commObj->getDate().") $delcomment
					   <br/><i style=\"padding-left:3px;display:block\">".$commObj->getComment(true)."</i></li>";
				}
				
				
			}
			print "</ul>";
		}
		
	?>
	
	</div>
	
	
	</td>
	<td valign="top">
	<div id="cast">
	<?  if (isset($imdb)) {
		print $imdb->getCast();
	} else {
		print $language->show('M_NOACTORS');
	}
	?>
	</div>
	
	<div id="imdblinks">
	<? 
		if (isset($imdb)) {
			display_imdbLinks($imdb->getIMDB());
		}
	?>
	</div>
	
	<div id="similar">
	<? 
		$simArr = $VCDClass->getSimilarMovies($movie->getID());
		if (is_array($simArr) && sizeof($simArr) > 0) {
			
			print "<h2>".$language->show('M_SIMILAR')."</h2>";
			print "<form name=\"sim\" action=\"get\"><select name=\"similar\" size=\"1\" onchange=\"goSimilar(this.form)\">";
			evalDropdown($simArr, 0, true, $language->show('X_SELECT'));
			print "</form>";
		}
		
	?>
	</div>
	</td>
</tr>
</table>



<? 
}
?>