
<table cellpadding="0" cellspacing="0" width="100%" border="0">
<tr>
	<td width="70%"><h1><?= language::translate('MENU_MOVIES') ?></h1></td>
	<td><h1><?= language::translate('MY_ACTIONS') ?></h1></td>
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
				require_once('user_diff.php');
				break;
			case 'customkeys':
				require_once('user_keys.php');
				break;
			case 'seenlist':
				require_once('user_seenlist.php');
				break;
			case 'picker':
				require_once('user_picker.php');
				break;
		
			default:
				?>
			<p>
				<?= language::translate('MY_INFO') ?>
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
		<li><a href="./?page=private&o=movies&do=diff"><?= language::translate('MY_JOIN') ?></a></li>
		<li><a href="#" onclick="showUserStatus()"><?= language::translate('MY_OVERVIEW') ?></a></li>
		<? if ($_SESSION['user']->getPropertyByKey('USE_INDEX'))  { ?>
		<li><a href="./?page=private&o=movies&do=customkeys"><?= language::translate('MY_KEYS') ?></a></li>
		<? } ?>
		<? if ($_SESSION['user']->getPropertyByKey('SEEN_LIST'))  { ?>
		<li><a href="./?page=private&o=movies&do=seenlist"><?= language::translate('MY_SEENLIST') ?></a></li>
		<? } ?>
		<li><a href="./?page=private&o=movies&do=picker"><?= language::translate('MY_HELPPICKER') ?></a></li>
		<br/><br/>
		<li><a href="#" onclick="printView('text')"><?= language::translate('MY_TEXTALL') ?></a></li>
		<li><a href="#" onclick="printView('all')"><?= language::translate('MY_PWALL') ?></a></li>
		<li><a href="#" onclick="printView('movies')"><?= language::translate('MY_PWMOVIES') ?></a></li>
		<li><a href="#" onclick="printView('tv')"><?= language::translate('MY_PWTV') ?></a></li>
		<li><a href="#" onclick="printView('blue')"><?= language::translate('MY_PWBLUE') ?></a></li>

	</ul>
	
	<!-- / User menu -->
	</td>
</tr>
</table>


<? if (!isset($_GET['do'])) { ?>

<h2><?= language::translate('MY_EXPORT') ?></h2>
<table cellspacing="0" cellpadding="1" border="0" width="100%" class="displist">
<tr>
	<td width="4%"><img src="images/icon_xls.gif" border="0" alt="Excel" hspace="2"/></td>
	<td width="4%">&nbsp;</td>
	<td width="4%">&nbsp;</td>
	<td><a href="./exec_query.php?action=export&amp;type=excel"><?= language::translate('MY_EXCEL') ?></a> <br/></td>
</tr>
<tr>
	<td><a href="./exec_query.php?action=export&amp;type=xml"><img src="images/icon_xml.gif" border="0" alt="XML" hspace="2"/></a></td>
	<td><a href="./exec_query.php?action=export&amp;type=xml&c=tar"><img src="images/icon_tar.gif" border="0" alt="Tar" hspace="2"/></a></td>
	<td><a href="./exec_query.php?action=export&amp;type=xml&c=zip"><img src="images/icon_zip.gif" border="0" alt="Zip" hspace="2"/></a></td>
	<td><a href="./exec_query.php?action=export&amp;type=xml"><?= language::translate('MY_XML') ?></a></td>
</tr>
<tr>
	<td><a href="./exec_query.php?action=export&amp;filter=thumbs&amp;type=xml"><img src="images/icon_xml.gif" border="0" alt="XML" hspace="2"/></a></td>
	<td><a href="./exec_query.php?action=export&amp;filter=thumbs&amp;type=xml&c=tar"><img src="images/icon_tar.gif" border="0" alt="Tar" hspace="2"/></a></td>
	<td><a href="./exec_query.php?action=export&amp;filter=thumbs&amp;type=xml&c=zip"><img src="images/icon_zip.gif" border="0" alt="Zip" hspace="2"/></a></td>
	<td><a href="./exec_query.php?action=export&amp;filter=thumbs&amp;type=xml"><?= language::translate('MY_XMLTHUMBS') ?></a></td>
</tr>
<? 
if (substr_count($_SERVER['HTTP_USER_AGENT'], "Windows") > 0) {
	?> 
<tr>
	<td><img src="images/icon_cd.gif" border="0" alt="VCD-db Client" hspace="2"/></td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td><a href="images/VCDdb-Client-0.1.zip">VCD-db Client</a></td>
</tr>
	<?
}
print "</table>";

} ?>


<? 
	
	if (isset($_GET['show']) && $_GET['show'] == 'results') {
		print "<h2>".language::translate('X_RESULTS')."</h2>";
		$VCDClass = VCDClassFactory::getInstance('vcd_movie');
		$results = $VCDClass->crossJoin($s_owner, $s_mediatype, $s_category, $s_meth);	
		
		if (sizeof($results) == 0) {
			print "<p class=\"bold\">".language::translate('MY_NORESULTS')."</p>";
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