
<table cellpadding="0" cellspacing="0" width="100%" border="0">
<tr>
	<td width="70%"><h1><?= VCDLanguage::translate('menu.movies') ?></h1></td>
	<td><h1><?= VCDLanguage::translate('mymovies.actions') ?></h1></td>
</tr>
<tr>
	<td valign="top">
	<!-- User content -->
	<? 
		$user_action = "";
		if (isset($_GET['do'])) {
			$user_action = $_GET['do'];
		}
	
		
		switch ($user_action) {
			case 'diff':
				require_once(VCDDB_BASE.'/pages/user_diff.php');
				break;
			case 'customkeys':
				require_once(VCDDB_BASE.'/pages/user_keys.php');
				break;
			case 'seenlist':
				require_once(VCDDB_BASE.'/pages/user_seenlist.php');
				break;
			case 'picker':
				require_once(VCDDB_BASE.'/pages/user_picker.php');
				break;
		
			default:
				?>
			<p>
				<?= VCDLanguage::translate('mymovies.info') ?>
			</p>
				<?
			
				break;
		}
	
	?>
		
	<!-- / User content -->
	</td>
	<td valign="top">
	<!-- User menu -->
	<ul>
		<li><a href="./?page=private&o=movies&do=diff"><?= VCDLanguage::translate('mymovies.join') ?></a></li>
		<li><a href="#" onclick="showUserStatus()"><?= VCDLanguage::translate('mymovies.overview') ?></a></li>
		<li><a href="#" onclick="showUserStatusDetailed()"><?= VCDLanguage::translate('mymovies.overviewdetail') ?></a></li>
		<? if ($_SESSION['user']->getPropertyByKey('USE_INDEX'))  { ?>
		<li><a href="./?page=private&o=movies&do=customkeys"><?= VCDLanguage::translate('mymovies.keys') ?></a></li>
		<? } ?>
		<? if ($_SESSION['user']->getPropertyByKey('SEEN_LIST'))  { ?>
		<li><a href="./?page=private&o=movies&do=seenlist"><?= VCDLanguage::translate('mymovies.seenlist') ?></a></li>
		<? } ?>
		<li><a href="./?page=private&o=movies&do=picker"><?= VCDLanguage::translate('mymovies.helppicker') ?></a></li>
		<br/><br/>
		<li><a href="#" onclick="printView('text')"><?= VCDLanguage::translate('mymovies.textall') ?></a></li>
		<li><a href="#" onclick="printView('all')"><?= VCDLanguage::translate('mymovies.pwall') ?></a></li>
		<li><a href="#" onclick="printView('movies')"><?= VCDLanguage::translate('mymovies.pwmovies') ?></a></li>
		<li><a href="#" onclick="printView('tv')"><?= VCDLanguage::translate('mymovies.pwtv') ?></a></li>
		<li><a href="#" onclick="printView('blue')"><?= VCDLanguage::translate('mymovies.pwblue') ?></a></li>

	</ul>
	
	<!-- / User menu -->
	</td>
</tr>
</table>


<? if (!isset($_GET['do'])) { ?>

<fieldset id="pagelook" title="<?= VCDLanguage::translate('mymovies.export'); ?>">
<legend class="bold"><?= VCDLanguage::translate('mymovies.export'); ?></legend>

<table cellspacing="1" cellpadding="1" border="0" width="100%" class="displist">
<tr>
	<td width="4%"><img src="images/icon_xls.gif" border="0" alt="Excel" hspace="2"/></td>
	<td width="4%">&nbsp;</td>
	<td width="4%">&nbsp;</td>
	<td><a href="./exec_query.php?action=export&amp;type=excel"><?= VCDLanguage::translate('mymovies.excel') ?></a> <br/></td>
</tr>
<tr>
	<td><a href="./exec_query.php?action=export&amp;type=xml"><img src="images/icon_xml.gif" border="0" alt="XML" hspace="2"/></a></td>
	<td><a href="./exec_query.php?action=export&amp;type=xml&c=tar"><img src="images/icon_tar.gif" border="0" alt="Tar" hspace="2"/></a></td>
	<td><a href="./exec_query.php?action=export&amp;type=xml&c=zip"><img src="images/icon_zip.gif" border="0" alt="Zip" hspace="2"/></a></td>
	<td><a href="./exec_query.php?action=export&amp;type=xml"><?= VCDLanguage::translate('mymovies.xml') ?></a></td>
</tr>
<tr>
	<td><a href="./exec_query.php?action=export&amp;filter=thumbs&amp;type=xml"><img src="images/icon_xml.gif" border="0" alt="XML" hspace="2"/></a></td>
	<td><a href="./exec_query.php?action=export&amp;filter=thumbs&amp;type=xml&c=tar"><img src="images/icon_tar.gif" border="0" alt="Tar" hspace="2"/></a></td>
	<td><a href="./exec_query.php?action=export&amp;filter=thumbs&amp;type=xml&c=zip"><img src="images/icon_zip.gif" border="0" alt="Zip" hspace="2"/></a></td>
	<td><a href="./exec_query.php?action=export&amp;filter=thumbs&amp;type=xml"><?= VCDLanguage::translate('mymovies.xmlthumbs') ?></a></td>
</tr>
<? if (substr_count($_SERVER['HTTP_USER_AGENT'], "Windows") > 0) { 	?> 
<tr>
	<td><img src="images/icon_cd.gif" border="0" alt="VCD-db Client" hspace="2"/></td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td><a href="images/VCDdb-Client-0.1.zip">VCD-db Client</a></td>
</tr>
<? } ?>
</table>
</fieldset>
<? } ?>

<? 
	
	if (isset($_GET['show']) && $_GET['show'] == 'results') {
		print "<h2>".VCDLanguage::translate('misc.results')."</h2>";
		$results = MovieServices::crossJoin($s_owner, $s_mediatype, $s_category, $s_meth);	
		
		if (sizeof($results) == 0) {
			print "<p class=\"bold\">".VCDLanguage::translate('mymovies.noresults')."</p>";
		} else {
			if ($s_meth == 2) {
				$movCounter = 1;
				print "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\" >";
				foreach ($results as $vcdObj) {
					print "<tr>";
						print "<td width=\"40\">&nbsp;<input type=\"checkbox\" name=\"wanted[]\" class=\"nof\" value=\"".$vcdObj->getID()."\"/></td>";
						print "<td align=\"right\" width=\"15\">{$movCounter}</td>";
						print "<td>&nbsp;<a href=\"./?page=cd&amp;vcd_id=".$vcdObj->getID()."\">".$vcdObj->getTitle()."</a></td>";
					print "</tr>";
					$movCounter++;
				}
				print "</table>";
			} else {
				print "<ol>";
				foreach ($results as $vcdObj) {
						print "<li><a href=\"./?page=cd&amp;vcd_id=".$vcdObj->getID()."\">".$vcdObj->getTitle()."</a></li>";
				}
				print "</ol>";
			}
			
			
			unset($results);
		}
		
	}
?>