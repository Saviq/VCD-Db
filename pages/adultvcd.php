<table width="100%" border="0" cellspacing="0" cellpadding="0" class="displist">
<tr>
	<td width="75%" class="header"><?=VCDLanguage::translate('movie.movie')?></td>
	<td width="25%" class="header"><?=VCDLanguage::translate('movie.info')?></td>
</tr>
<tr>
	<td valign="top">
	<!-- Info table -->
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" width="10%">
		<?
		$coverObj = $movie->getCover("thumbnail");
		if (!is_null($coverObj))
			$coverObj->showImage();
		?>
		</td>
		<td valign="top">
			<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td colspan="2"><strong><?= $movie->getTitle() ?></strong></td>
			</tr>
			<tr>
				<td width="30%"><?=VCDLanguage::translate('movie.category')?>:</td>
				<td><?
				$mObj = $movie->getCategory();
				if (!is_null($mObj)) {
					print "<a href=\"./?page=category&amp;category_id=".$mObj->getID()."\">".$mObj->getName()."</a>";
				}
				?></td>
			</tr>
			<tr>
				<td nowrap="nowrap"><?=VCDLanguage::translate('movie.year')?>:</td>
				<td><?= $movie->getYear() ?></td>
			</tr>
			<tr>
				<td><?=VCDLanguage::translate('movie.copies')?>:</td>
				<td><?= $movie->getNumCopies() ?></td>
			</tr>
			<tr>
				<td><?=VCDLanguage::translate('movie.screenshots')?></td>
				<td>
				<?
					if (isset($_GET['screens'])) {
						print "<a href=\"./?page=cd&amp;vcd_id=".$movie->getID()."\">".VCDLanguage::translate('movie.hide')."</a>";
					}

					elseif (MovieServices::getScreenshots($movie->getID())) {
						print "<a href=\"./?page=cd&amp;vcd_id=".$movie->getID()."&amp;screens=on\">".VCDLanguage::translate('movie.show')."</a>";
					} else {
						print VCDLanguage::translate('movie.noscreens');
					}
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
						<tr><td>&nbsp;</td><td><a href="#" onclick="loadManager(<?=$movie->getID()?>)"><?=VCDLanguage::translate('movie.change')?></a></td></tr>
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



				// Display Play button
				if (VCDUtils::isLoggedIn() && VCDUtils::isOwner($movie)) {
					$command = "";
					if (getPlayCommand($movie, VCDUtils::getUserID(), $command)) {
						print "<tr><td>&nbsp;</td><td>";
					    print "<a href=\"javascript:void(0)\"><img src=\"images/play.gif\" border=\"0\" onclick=\"playFile('".$command."')\"/></a>";
				    	print "</td></tr>";
					}

				}


			?>
			<tr>
				<td colspan="2"><?= drawSourceSiteLogo($movie->getSourceSiteID(), $movie->getExternalID()); ?></td>
			</tr>
			</table>


		</td>
	</tr>
	</table>

	<? if (isset($_GET['screens']) && MovieServices::getScreenshots($movie->getID())) {
		?>
		<h2>Screenshots</h2>
		<iframe id="screenshots" width="100%" height="470" src="screens.php?s_id=<?=$movie->getID()?>"
					frameborder="0" scrolling="no"></iframe>
		<?
	}
	?>



	<h2><?=VCDLanguage::translate('movie.actors')?></h2>
	<div id="actorimages" style="padding-left:10px;">
	<?
		$arr = PornstarServices::getPornstarsByMovieID($movie->getID());

		foreach ($arr as $pornstar) {
			$pornstar->showImage() ;
		}
	?></div>
	<p></p>


	<div id="copies">
	<h2><?= VCDLanguage::translate('movie.available')?>:</h2>
	<? 
		$allMeta = SettingsServices::getMetadata($cd_id, null, null, null);
		drawDVDLayers($movie, $allMeta);
		$movie->displayCopies($allMeta) 
	?>
	</div>

	<p></p>

	<h2><?=VCDLanguage::translate('movie.covers')?></h2>
	<?
		foreach ($movie->getCovers() as $cover) {
			if (!$cover->isThumbnail()) {

				// Only show the front and back covers
				 if ((strpos($cover->getCoverTypeName(), 'Front') > 0) || (strpos($cover->getCoverTypeName(), 'Back') > 0)) {
				 	$cover->showImage();
				 }
			}
		}
	?>



	</td>
	<td valign="top" style="background-color:white">
	<h2>Studio</h2>
	<ul>
		<?
			if (is_numeric($movie->getStudioID())) {
				$studio = PornstarServices::getStudioByID($movie->getStudioID());
			} else {
				$studio = PornstarServices::getStudioByMovieID($movie->getID());
			}


			if ($studio instanceof studioObj) {
				print "<li><a href=\"./?page=adultcategory&amp;studio_id=".$studio->getID()."\">".htmlspecialchars($studio->getName())."</a></li>";
			} else {
				print "<li>No Studio information</li>";
			}

		?>
	</ul>
	<br/><br/>

	<h2><?=VCDLanguage::translate('dvdempire.subcat')?></h2>
	<ul>
	<?
		$subcats = PornstarServices::getSubCategoriesByMovieID($movie->getID());
		foreach ($subcats as $categoryObj) {
			print "<li><a href=\"./?page=adultcategory&amp;category_id=".$categoryObj->getId()."\">".$categoryObj->getName(true) . "</a></li>";
		}

	?>
	</ul>
	<br/><br/>

	<h2><?=VCDLanguage::translate('movie.covers')?></h2>
	<?
		print "<ul>";
		foreach ($movie->getCovers() as $cover) {

			if (!$cover->isThumbnail()) {
				 if ((strpos($cover->getCoverTypeName(), 'Front') > 0) || (strpos($cover->getCoverTypeName(), 'Back') > 0)) {
				 	print "<li><a title=\"".human_file_size($cover->getFilesize())."\" href=\"#".$cover->getCoverTypeName()."\">" . $cover->getCoverTypeName() . "</a></li>";
				 } else {
					print "<li><a href=\"#\" title=\"".human_file_size($cover->getFilesize())."\" onclick=\"showcover('".$cover->getFilename()."',".(int)$cover->isInDB().",".$cover->getImageID().")\">".$cover->getCoverTypeName() . "</a></li>";
				 }

			}
		}
		print "</ul>";
	?>


	<br/><br/>
	<h2><?=VCDLanguage::translate('movie.actors')?></h2>
	<div id="actorlist"><ul>
	<?

		foreach ($arr as $pornstar) {

			print "<li><a href=\"./?page=pornstar&amp;pornstar_id=".$pornstar->getID()."\">".$pornstar->getName() . "</a></li>";
		}
		unset($arr)
	?>
	</ul></div>
	<br/>
	<div id="similar">
	<?
		$simArr = MovieServices::getSimilarMovies($movie->getID());
		if (is_array($simArr) && sizeof($simArr) > 0) {

			print "<h2>".VCDLanguage::translate('movie.similar')."</h2>";
			print "<form name=\"sim\" action=\"get\"><select name=\"similar\" size=\"1\" onchange=\"goSimilar(this.form)\">";
			evalDropdown($simArr, 0, true, VCDLanguage::translate('misc.select'));
			print "</select></form><br/>";
		}

	?>
	</div>
	<div id="comments">
	<h2><?= VCDLanguage::translate('comments.comments')?> (<a href="javascript:show('newcomment')"><?= VCDLanguage::translate('misc.new')?></a>)</h2>
	</div>
	<div id="newcomment" style="left:665px;visibility:hidden;display:none;position:absolute">
		<?  if(!VCDUtils::isLoggedIn()) {
				print "<span class=\"bold\">". VCDLanguage::translate('comments.error')."</span>";
			} else { ?>

		<form name="newcomment" method="POST" action="exec_form.php?action=addcomment">
		<input type="hidden" name="vcd_id" value="<?=$movie->getID()?>"/>
		<table cellpadding="0" cellspacing="0" border="0" class="plain">
			<tr>
				<td colspan="2"><textarea name="comment" rows="4" cols="20"></textarea></td>
			</tr>
			<tr>
				<td><?= VCDLanguage::translate('movie.private')?>:</td>
				<td><input type="checkbox" name="private" class="nof" value="private"/></td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" value="<?=VCDLanguage::translate('comments.post')?>"/></td>
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

				if ($commObj->isPrivate()) {
					if (VCDUtils::isLoggedIn() && $commObj->getOwnerID() == VCDUtils::getUserID())	{
						print "<li>".$commObj->getOwnerName()." (<i>Private</i>) <a href=\"#\" onclick=\"location.href='exec_query.php?action=delComment&amp;cid=".$commObj->getID()."'\"><img src=\"images/icon_del.gif\" alt=\"Delete comment\" align=\"absmiddle\" border=\"0\"/></a>
					   <br/><i style=\"display:block\">".$commObj->getComment(true)."</i></li>";
					}
				} else {
					if (VCDUtils::isLoggedIn() && $commObj->getOwnerID() == VCDUtils::getUserID())	{
						print "<li>".$commObj->getOwnerName()."  <a href=\"#\" onclick=\"location.href='exec_query.php?action=delComment&amp;cid=".$commObj->getID()."'\"><img src=\"images/icon_del.gif\" alt=\"Delete comment\" align=\"absmiddle\" border=\"0\"/></a>
					   <br/><i style=\"display:block\">".$commObj->getComment(true)."</i></li>";
					} else  {
						print "<li>".$commObj->getOwnerName()." <br/><i style=\"display:block\">".$commObj->getComment(true)."</i></li>";
					}
					
				}


			}
			print "</ul>";
		}

	?>

	</td>
</tr>
</table>

