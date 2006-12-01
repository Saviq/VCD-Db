<?
$cd_id = $_GET['vcd_id'];

$movie = MovieServices::getVcdByID($cd_id);
if (!$movie instanceof vcdObj ) {
	redirect();
}

if ($movie->isAdult()) {
	require_once(dirname(__FILE__) . '/adultvcd.php');
} else {
	$imdb = $movie->getIMDB();

?>


<table width="100%" border="0" cellspacing="0" cellpadding="0" class="displist">
<tr>
	<td width="65%" class="header"><?= VCDLanguage::translate('movie.movie')?></td>
	<td width="35%" class="header"><?= VCDLanguage::translate('movie.actors')?></td>
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
				<td width="30%"><?= VCDLanguage::translate('movie.category')?>:</td>
				<td><?
				$mObj = $movie->getCategory();
				if (!is_null($mObj)) {
					print "<a href=\"./?page=category&amp;category_id=".$mObj->getID()."\">".$mObj->getName(true)."</a>";
				}
				?></td>
			</tr>
			<tr>
				<td nowrap="nowrap"><?= VCDLanguage::translate('movie.year')?>:</td>
				<td><?= $movie->getYear() ?></td>
			</tr>
			<tr>
				<td><?= VCDLanguage::translate('movie.copies')?>:</td>
				<td><?= $movie->getNumCopies() ?></td>
			</tr>
			<tr>
				<td><?= drawSourceSiteLogo($movie->getSourceSiteID(), $movie->getExternalID()); ?></td>
				<td>
				<?
					if (isset($imdb)) {$imdb->drawRating();}
				?>
				</td>
			</tr>
			<?
				if (VCDUtils::isLoggedIn()) {
					if (SettingsServices::isOnWishList($movie->getID())) {
						?><tr><td>&nbsp;</td><td><a href="./?page=private&amp;o=wishlist">(<?= VCDLanguage::translate('wishlist.onlist')?>)</a></td></tr><?
					} else {
						?><tr><td>&nbsp;</td><td><a href="#" onclick="addtowishlist(<?=$movie->getID()?>)"><?= VCDLanguage::translate('wishlist.add')?></a></td></tr><?
					}

				}
			?>


			<?
				if (VCDUtils::hasPermissionToChange($movie)) {
					?>
						<tr><td>&nbsp;</td><td><a href="#" onclick="loadManager(<?=$movie->getID()?>)"><?= VCDLanguage::translate('movie.change')?></a></td></tr>
					<?
				}

				// Display seen box if activated
				if (VCDUtils::isLoggedIn() && VCDUtils::isOwner($movie) && $_SESSION['user']->getPropertyByKey('SEEN_LIST')) {
   					$arrList = SettingsServices::getMetadata($movie->getID(), VCDUtils::getUserID(), 'seenlist');
   					print "<tr><td>&nbsp;</td><td>";
   					if (sizeof($arrList) == 1 && ($arrList[0]->getMetadataValue() == 1)) {
				   		print "<a href=\"#\"><img src=\"images/mark_seen.gif\" alt=\"".VCDLanguage::translate('seen.notseenitclick')."\" border=\"0\" style=\"padding-right:5px\" onclick=\"markSeen(".$movie->getID().", 0)\"/></a>";
				    	print VCDLanguage::translate('seen.seenit');
				    } else {
				    	print "<a href=\"#\"><img src=\"images/mark_unseen.gif\" alt=\"".VCDLanguage::translate('seen.seenitclick')."\" border=\"0\" style=\"padding-right:5px\" onclick=\"markSeen(".$movie->getID().", 1)\"/></a>";
				    	print VCDLanguage::translate('seen.notseenit');
				    }
				    print "</td></tr>";
				}
			?>
			</table>


		</td>
	</tr>
	</table>

	<div id="imdbinfo">
	<h2><?= VCDLanguage::translate('movie.from')?> IMDB:</h2>
	<? if (isset($imdb)) { ?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="normal">
	<tr>
		<td><?= VCDLanguage::translate('movie.title')?></td>
		<td><?=$imdb->getTitle() ?></td>
	</tr>
	<?
		if (strcmp($imdb->getAltTitle(), "") != 0) {
	?>
	<tr>
		<td valign="top"><?= VCDLanguage::translate('movie.alttitle')?></td>
		<td><?=$imdb->getAltTitle() ?></td>
	</tr>
	<? } ?>
	<tr>
		<td>IMDB <?= VCDLanguage::translate('movie.grade')?>:</td>
		<td><?=$imdb->getRating() ?></td>
	</tr>
	<tr>
		<td><?= VCDLanguage::translate('movie.director')?>:</td>
		<td><?=$imdb->getDirectorLink() ?></td>
	</tr>
	<tr>
		<td nowrap="nowrap"><?= VCDLanguage::translate('movie.country')?>:</td>
		<td><?=$imdb->getCountry() ?></td>
	</tr>
	<tr>
		<td valign="top" nowrap="nowrap">IMDB <?= VCDLanguage::translate('movie.category')?>:</td>
		<td><?=parseCategoryList($imdb->getGenre()) ?></td>
	</tr>
	<tr>
		<td><?= VCDLanguage::translate('movie.runtime')?>:</td>
		<td><?=$imdb->getRuntime() ?> <?= VCDLanguage::translate('movie.minutes')?></td>
	</tr>
	</table>


	<? } else { print "<ul><li>".VCDLanguage::translate('imdb.not')."</li></ul>"; } ?>
	</div>


	<div id="imdbplot">
	<a name="plot"></a>
	<h2><?= VCDLanguage::translate('movie.plot')?>:</h2>
	<? if (isset($imdb)) { showPlot($imdb->getPlot()); } else { print "<ul><li>".VCDLanguage::translate('movie.noplot')."</li></ul>"; }?>
	</div>

	<div id="covers">
	<h2><?= VCDLanguage::translate('movie.covers')?></h2>
	<?

		$covers = $movie->getCovers();
		$bNoCovers = true;
		$strCoverHTML = "";
		if (count($covers) > 0) {
			$strCoverHTML .= "<ul>";
			foreach ($covers as $cdcoverObj) {
				if (!$cdcoverObj->isThumbnail()) {
					$bNoCovers = false;
					$strCoverHTML .= "<li><a href=\"#\" onclick=\"showcover('".$cdcoverObj->getFilename()."',".(int)$cdcoverObj->isInDB().",".$cdcoverObj->getImageID().")\">".$cdcoverObj->getCoverTypeName() . "</a> <i>(".human_file_size($cdcoverObj->getFilesize()).")</i></li>";
				}
			}
			$strCoverHTML .= "</ul>";
		}

		if ($bNoCovers) {
			print "<ul><li>".VCDLanguage::translate('movie.nocovers')."</li></ul>";
		} else {
			print $strCoverHTML;
		}
	?>
	</div>

	<div id="copies">
	<h2><?= VCDLanguage::translate('movie.available')?>:</h2>

	<?
		$allMeta = SettingsServices::getMetadata($cd_id, null, null, null);
		drawDVDLayers($movie, $allMeta);
		$movie->displayCopies($allMeta);
	?>
	</div>


	<?
		if (VCDUtils::isLoggedIn() && VCDUtils::isOwner($movie)) {
			$userMetaArr = SettingsServices::getMetadata($movie->getID(), VCDUtils::getUserID(), null);
			// Filter out the SYSTEM Types that we don't want to display ..
			$userMetaArr = metadataTypeObj::filterOutSystemMeta($userMetaArr);

			if (is_array($userMetaArr) && sizeof($userMetaArr) > 0) {

				$arrCopies = $movie->getInstancesByUserID(VCDUtils::getUserID());
				$arrMyMediaTypes = $arrCopies['mediaTypes'];

				print "<div id=\"metadata\">";
				print "<h2>".VCDLanguage::translate('metadata.my')."</h2>";

				print "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\">";
				print "<tr><td width=\"20%\">".VCDLanguage::translate('movie.media')."</td><td>".VCDLanguage::translate('metadata.type')."</td><td>".VCDLanguage::translate('metadata.value')."</td></tr>";
				foreach ($userMetaArr as $metadataObj) {
					$mediaObj = SettingsServices::getMediaTypeByID($metadataObj->getMediaTypeID());
					if ($mediaObj instanceof mediaTypeObj && strcmp(trim($metadataObj->getMetadataValue()), "") != 0) {
						print "<tr><td>{$mediaObj->getName()}</td><td title=\"{$metadataObj->getMetadataDescription()}\">{$metadataObj->getMetadataName()}</td><td>{$metadataObj->prettyPrint($movie)}</td></tr>";
					}

				}
				print "</table>";




				print "</div>";

			}
		}

	?>




	<div id="comments">
	<h2><?= VCDLanguage::translate('comments.comments')?> (<a href="javascript:show('newcomment')"><?= VCDLanguage::translate('comments.add')?></a>)</h2>
	</div>

	<div id="newcomment" style="padding-left:15px;visibility:hidden;display:none">
		<?  if(!VCDUtils::isLoggedIn()) {
				print "<span class=\"bold\">". VCDLanguage::translate('comments.error')."</span>";
			} else { ?>

		<span class="bold"><?= VCDLanguage::translate('comments.type')?>:</span>
		<form name="newcomment" method="POST" action="exec_form.php?action=addcomment">
		<input type="hidden" name="vcd_id" value="<?=$movie->getID()?>"/>
		<table cellpadding="0" cellspacing="0" border="0" class="plain">
			<tr>
				<td valign="top"><?= VCDLanguage::translate('comments.your')?>:</td>
				<td><textarea name="comment" rows="4" cols="30"></textarea></td>
			</tr>
			<tr>
				<td><?= VCDLanguage::translate('movie.private')?>:</td>
				<td><input type="checkbox" name="private" class="nof" value="private"/>
				&nbsp;&nbsp;<input type="submit" value="<?=VCDLanguage::translate('comments.post')?>"/>
				</td>
			</tr>
		</table>
		</form>
		<? } ?>
		<br/>
	</div>

	<?
		$commArr = SettingsServices::getAllCommentsByVCD($movie->getID());
		if (empty($commArr)) {
			print "<ul><li>".VCDLanguage::translate('comments.none')."</li></ul>";
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
		print VCDLanguage::translate('movie.noactors');
	}
	?>
	</div>

	<div id="imdblinks">
	<?
		if (is_numeric($movie->getSourceSiteID())) {
			$SourceSiteObj = SettingsServices::getSourceSiteByID($movie->getSourceSiteID());
			if (isset($imdb) && $SourceSiteObj instanceof sourceSiteObj && strcmp($SourceSiteObj->getAlias(), "imdb") == 0) {
				display_imdbLinks($imdb->getIMDB());
			}
		}
		
	?>
	</div>

	<div id="similar">
	<?
		$simArr = MovieServices::getSimilarMovies($movie->getID());
		if (is_array($simArr) && sizeof($simArr) > 0) {

			print "<h2>".VCDLanguage::translate('movie.similar')."</h2>";
			print "<form name=\"sim\" action=\"get\"><select name=\"similar\" size=\"1\" onchange=\"goSimilar(this.form)\">";
			evalDropdown($simArr, 0, true, VCDLanguage::translate('misc.select'));
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
