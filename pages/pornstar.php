<?
	global $ClassFactory;
	global $language;
	$PORNClass = $ClassFactory->getInstance('vcd_pornstar');
	$pid = $_GET['pornstar_id'];
	$pornstarObj = $PORNClass->getPornstarByID($pid);
?>
<h1>Pornstar | <?=$pornstarObj->getName()?></h1>

<style type="text/css">
div#tipDiv {
  position:absolute; visibility:hidden; left:0; top:0; z-index:10000;
  background-color:#fff; border:1px solid #000; 
  width:200px; padding:6px;
  color:#000; font-size:11px; line-height:1.3;
}

/* These are used in the wrapTipContent function */
div#tipDiv div.img { text-align:center }
div#tipDiv div.msg { text-align:left; margin-top:4px }
</style>

<script type="text/javascript">
var messages = new Array();

<? 
	$i = 0;
	$CLASSCovers = $ClassFactory->getInstance('vcd_cdcover');
	foreach ($pornstarObj->getMovies() as $id => $title) {
		$arrCovers = $CLASSCovers->getAllCoversForVcd($id);
		foreach ($arrCovers as $obj) {
			if ($obj->isThumbnail()) {
				print "messages[".$i++."] = [\"".$obj->getImagePath()."\", 145, 205];\n ";
			}	
		}
		
	}
?>



</script>

<table cellspacing="1" cellpadding="0" border="0" class="displist" width="100%">
		<tr>
			<td valign="top" width="170"><? $pornstarObj->showImage(); ?><br/>
			<div align="center"><strong><?= $pornstarObj->getIAFD() ?></strong></div></td>
			<td valign="top" style="padding-left:3px;text-indent:0px">
				<strong><?=$language->show('P_NAME')?>:</strong> <?= $pornstarObj->getName() ?><br/>
				<strong><?=$language->show('P_WEB')?>:</strong> <?= $pornstarObj->getHomepage() ?><br/>
				<strong><?=$language->show('P_MOVIECOUNT')?>:</strong> <? echo $pornstarObj->getMovieCount() ?><br/><br/>
				<?
					if ($pornstarObj->getMovieCount() > 0) {
						$i = 0;
						print "<ul>";
						foreach ($pornstarObj->getMovies() as $id => $title) {
							print "<li onmouseover=\"doTooltip(event,".$i++.")\" onmouseout=\"hideTip()\"><a href=\"./?page=cd&amp;vcd_id=".$id."\">".$title . "</a></li>";
						}
						print "</ul>";
					}
				?>				

			</td>
		</tr>
	</table>
<p>

<?
	$bio = ereg_replace("\n","<br/>",$pornstarObj->getBiography()) ;
	print $bio;	
?>
</p>
