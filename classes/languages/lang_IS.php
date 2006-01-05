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
 * @author  H�kon Birgsson <konni@konni.com>
 * @package Language
 * @version $Id$
 */

?>
<?
	/**
		Icelandic language file

	*/



$_ = array(

/* Language Identifier */
'LANG_TYPE' 			=> 'IS',
'LANG_NAME' 			=> '�slenska',
'LANG_CHARSET'			=> 'iso-8859-1',

/* Menu system */
'MENU_MINE' 			=> 'M�n valmynd',
'MENU_SETTINGS' 		=> 'M�nar stillingar',
'MENU_MOVIES' 			=> 'M�nar myndir',
'MENU_ADDMOVIE' 		=> 'B�ta vi� mynd',
'MENU_LOANSYSTEM'		=> 'L�nakerfi�',
'MENU_WISHLIST' 		=> '�skalistinn',
'MENU_CATEGORIES' 		=> 'Flokkar mynda',
'MENU_RSS' 				=> 'RSS straumar',
'MENU_CONTROLPANEL' 	=> 'Stj�rnbor�',
'MENU_REGISTER' 		=> 'N�skr�ning',
'MENU_LOGOUT' 			=> '�tskr�ning',
'MENU_SUBMIT'			=> 'Sta�festa',
'MENU_TOPUSERS'			=> 'Top notendur',
'MENU_WISHLISTPUBLIC'	=> '�skalisti annara',
'MENU_STATISTICS'		=> 'T�lfr��i',

/* Login */
'LOGIN' 				=> 'Innskr�ning',
'LOGIN_USERNAME' 		=> 'Notandi',
'LOGIN_PASSWORD' 		=> 'Lykilor�',
'LOGIN_REMEMBER' 		=> 'Mundu mig',
'LOGIN_INFO' 			=> 'Haf�u �etta t�mt ef �� vilt <b>ekki</b> breyta lykilor�inu ��nu',

/* Register */
'REGISTER_TITLE'		=> 'N�skr�ning',
'REGISTER_FULLNAME' 	=> 'Fullt nafn',
'REGISTER_EMAIL' 		=> 'Netfang',
'REGISTER_AGAIN' 		=> 'Lykilor� aftur',
'REGISTER_DISABLED' 	=> '�vi mi�ur, kerfisstj�ri leyfir ekki n�skr�ningar eins og er',
'REGISTER_OK' 			=> 'Skr�ning heppna�ist, �� getur n�na skr�� �ig inn.',

/* User Properties */
'PRO_NOTIFY' 			=> 'Senda m�r p�st �egar a� n� mynd er skr��?',
'PRO_SHOW_ADULT' 		=> 'S�na fullor�ins efni?',
'PRO_RSS' 				=> 'Leyfa RSS straum fr� m�num lista?',
'PRO_WISHLIST' 			=> 'Leyfa ��rum a� sj� �skalistann minn?',
'PRO_USE_INDEX' 		=> 'Nota s�r au�kenni fyrir myndir?',
'PRO_SEEN_LIST' 		=> 'Halda utan um myndir sem �g hef s��?',
'PRO_PLAYOPTION' 		=> 'Nota afspilun fr� VCD-db?',
'PRO_NFO' 				=> 'Nota NFO skj�l?',

/* User Settings */
'SE_PLAYER' 			=> 'Stilla spilara',
'SE_OWNFEED' 			=> 'Sko�a minn XML straum',
'SE_CUSTOM' 			=> 'Stilla fors��u',
'SE_SHOWSTAT' 			=> 'S�na t�lfr��i',
'SE_SHOWSIDE' 			=> 'S�na n�jar myndir � h�gri valmynd',
'SE_SELECTRSS' 			=> 'Veldu RSS strauma',
'SE_PAGELOOK' 			=> '�tlit vefs',
'SE_PAGEMODE' 			=> 'Veldu sj�lfgefi� sni�m�t:',
'SE_UPDATED'			=> 'User information updated',
'SE_UPDATE_FAILED'		=> 'Failed to update',

/* Search */
'SEARCH' 				=> 'Leita',
'SEARCH_TITLE' 			=> 'Eftir titli',
'SEARCH_ACTOR' 			=> 'Eftir leikara',
'SEARCH_DIRECTOR' 		=> 'Eftir leikstj�ra',
'SEARCH_RESULTS' 		=> 'Leitarni�urst��ur',
'SEARCH_EXTENDED' 		=> '�tarleg leit',
'SEARCH_NORESULT' 		=> 'Leit skila�i engum ni�urst��um',

/* Movie categories*/
'CAT_ACTION' 			=> 'Spennumyndir',
'CAT_ADULT' 			=> 'Er�t�skar',
'CAT_ADVENTURE' 		=> '�vint�ramyndir',
'CAT_ANIMATION' 		=> 'Teiknimyndir',
'CAT_ANIME' 			=> 'Anime / Manga',
'CAT_COMEDY' 			=> 'Gamanmyndir',
'CAT_CRIME' 			=> 'Sakam�lamyndir',
'CAT_DOCUMENTARY' 		=> 'Heimildarmyndir',
'CAT_DRAMA' 			=> 'Drama',
'CAT_FAMILY' 			=> 'Fj�ldskyldumyndir',
'CAT_FANTASY' 			=> 'Fantas�a',
'CAT_FILMNOIR' 			=> 'Film Noir',
'CAT_HORROR' 			=> 'Hryllingsmyndir',
'CAT_JAMESBOND' 		=> 'James Bond',
'CAT_MUSICVIDEO' 		=> 'T�nlistarmyndb�nd',
'CAT_MUSICAL' 			=> 'S�ngvamyndir',
'CAT_MYSTERY' 			=> 'R��g�ta',
'CAT_ROMANCE' 			=> 'R�mantik',
'CAT_SCIFI' 			=> 'V�sindarsk�ldsaga',
'CAT_SHORT' 			=> 'Stuttmyndir',
'CAT_THRILLER' 			=> '�riller',
'CAT_TVSHOWS' 			=> 'Sj�nvarps��ttir',
'CAT_WAR' 				=> 'Str��smyndir',
'CAT_WESTERN' 			=> 'Vestrar',
'CAT_XRATED' 			=> 'Lj�sbl�ar',

/* Movie Listings */
'M_MOVIE' 				=> 'Um myndina',
'M_ACTORS' 				=> 'Leikarar',
'M_CATEGORY'		    => 'Flokkur',
'M_YEAR'				=> 'Framlei�slu�r',
'M_COPIES'				=> 'Eint�k til',
'M_FROM' 				=> 'Fr�',
'M_TITLE' 				=> 'Titill',
'M_ALTTITLE' 			=> 'Aukatitill',
'M_GRADE'				=> 'Einkunn',
'M_DIRECTOR' 			=> 'Leikstj�ri',
'M_COUNTRY'				=> 'Framlei�sluland',
'M_RUNTIME' 			=> 'Lengd',
'M_MINUTES'			 	=> 'min.',
'M_PLOT' 				=> 'S�gu�r��ur',
'M_NOPLOT' 				=> 'Enginn s�gu�r��ur til',
'M_COVERS' 				=> 'CD Covers',
'M_AVAILABLE' 			=> 'Eint�k til',
'M_MEDIA'			 	=> 'Myndg��i',
'M_NUM' 				=> 'Fj�ldi diska',
'M_DATE' 				=> 'Dags',
'M_OWNER'			 	=> 'Eigandi',
'M_NOACTORS'		    => 'Engir leikarar skr��ir',
'M_INFO'			    => 'N�nari uppl�singar',
'M_DETAILS'			    => 'Uppl�singar um mitt eintak',
'M_MEDIATYPE'		    => 'Myndg��i',
'M_COMMENT'			    => 'Athugasemd',
'M_PRIVATE'				=> 'Merkja pr�vat?',
'M_SCREENSHOTS'			=> 'Skj�myndir',
'M_NOSCREENS'			=> 'Engar skj�myndir eru til',
'M_SHOW'				=> 'S�na',
'M_HIDE'				=> 'Fela',
'M_CHANGE'				=> 'Breyta uppl�singum',
'M_NOCOVERS'			=> 'Enginn CD-Cover eru til',
'M_BYCAT'				=> 'Titlar eftir flokki',
'M_CURRCAT'				=> 'Valinn flokkur',
'M_TEXTVIEW'			=> 'Textas�n',
'M_IMAGEVIEW'			=> 'Myndas�n',
'M_MINEONLY'			=> 'S�na bara m�nar myndir',
'M_SIMILAR'				=> 'Svipa�ir titlar',
'M_MEDIAINDEX'			=> 'Au�kenni',

/* IMDB */
'I_DETAILS'				=> 'IMDB L�sing',
'I_PLOT'				=> 'S�gu�r��ur',
'I_GALLERY'				=> 'Myndasafn',
'I_TRAILERS'			=> 'Myndbands brot',
'I_LINKS'				=> 'IMDB Tenglar',
'I_NOT'					=> 'Engar IMDB uppl�singar tilt�kar',

/* DVD Specific */
'DVD_REGION'			=> 'Sv��i',
'DVD_FORMAT'			=> 'Tegund',
'DVD_ASPECT'			=> 'K��un',
'DVD_AUDIO'				=> 'Hlj��r�s',
'DVD_SUBTITLES'			=> 'Textar',

/* My Movies */
'MY_EXPORT' 			=> 'Flytja �t g�gn',
'MY_EXCEL' 				=> 'Flytja listann �t � Excel',
'MY_XML' 				=> 'Flytja listann �t sem XML',
'MY_XMLTHUMBS'			=> 'Flytja thumbnails �t sem XML',
'MY_ACTIONS'			=> 'M�nar a�ger�ir',
'MY_JOIN'				=> 'Samkeyrslur',
'MY_JOINMOVIES'			=> 'Samkeyrslur � myndum',
'MY_JOINSUSER'			=> 'Veldu notanda',
'MY_JOINSMEDIA'			=> 'Veldu myndg��i',
'MY_JOINSCAT'			=> 'Veldu yfirflokk',
'MY_JOINSTYPE'			=> 'Veldu tegund keyrslu',
'MY_JOINSHOW'			=> 'S�na ni�urst��ur',
'MY_NORESULTS'			=> 'Keyrsla skila�i engum ni�urst��um',
'MY_TEXTALL'			=> 'Prents�n (textas�n)',
'MY_PWALL'				=> 'Prents�n allra mynda',
'MY_PWMOVIES'			=> 'Prents�n kvikmynda',
'MY_PWTV'				=> 'Prents�n ��tta',
'MY_PWBLUE'				=> 'Prents�n er�t�skra mynda',
'MY_J1'					=> 'Myndir sem �g � en notandi ekki',
'MY_J2'					=> 'Myndir sem notandi � en �g ekki',
'MY_J3'					=> 'Myndir sem b��ir eiga',
'MY_OVERVIEW'			=> 'Yfirlit mynda',

'MY_INFO'				=> '� �essari si�u er allt a� finna um m�nar myndir
							Til h�gri eru m�gulegar a�ger�ir sem sn�a a� �inum myndum.
							Einnig geturu h�rna flutt �t g�gn � Excel formi til �tprentunar e�a nota� XML �tflutnings
							m�guleikana til a� f�ra allar �inar myndir � heilu lagi fr� einum VCD-db
							gagnagrunni til annars.',
'MY_KEYS'				=> 'Stilla lykla',
'MY_SEENLIST'			=> 'Stilla s��ar myndir',
'MY_HELPPICKER'			=> 'Finna mynd fyrir kv�ldi�',
'MY_HELPPICKERINFO'		=> 'Veistu ekki hva� �� �tt a� gl�pa �?<br/>Leyf�u VCD-db a� finna mynd fyrir �ig.<br/>
							�� getur stillt skor�ur ef �� vilt til a� �rengja �rtaki� fyrir VCD-db.',
'MY_FIND'				=> 'Finna mynd',
'MY_NOTSEEN'			=> 'Stinga bara upp� myndum sem �g hef ekki s��',
'MY_FRIENDS'			=> 'M�nir l�n�egar',

/* Manager window */
'MAN_BASIC' 			=> 'Grunnuppl�singar',
'MAN_IMDB' 				=> 'IMDB uppl�singar',
'MAN_EMPIRE' 			=> 'Empire uppl�singar',
'MAN_COPY' 				=> 'Mitt eintak',
'MAN_COPIES' 			=> 'M�n eint�k',
'MAN_NOCOPY' 			=> '�� �tt engin eint�k',
'MAN_1COPY' 			=> 'Eintak',
'MAN_ADDACT' 			=> 'B�ta vi� leikurum',
'MAN_ADDTODB' 			=> 'B�ta vi� n�jum leikara � grunninn',
'MAN_SAVETODB' 			=> 'Vista � db',
'MAN_SAVETODBNCD' 		=> 'Vista � db og mynd',
'MAN_INDB' 				=> 'Leikarar � gagnagrunni',
'MAN_SEL' 				=> 'Valdir leikarar',
'MAN_STARS' 			=> 'Stj�rnur',
'MAN_BROWSE'			=> 'Velja skjal',

/* Add movies */
'ADD_INFO' 				=> 'Veldu einn af eftirfarandi m�guleikum til a� skr� inn n�ja mynd',
'ADD_IMDB' 				=> 'S�kja beint fr� IMDB',
'ADD_IMDBTITLE' 		=> 'Sl��u inn titil til a� leita eftir',
'ADD_MANUAL' 			=> 'Sl� inn handvirkt',
'ADD_LISTED' 			=> 'B�ta vi� myndum sem er n� �egar skr��ar',
'ADD_XML' 				=> 'Hla�a inn �r XML skjali',
'ADD_XMLFILE' 			=> 'Skjal til a� hla�a inn',
'ADD_XMLNOTE' 			=> '(Athugi�, a�eins XML skj�l sem hafa veri� flutt �t me� VCD-DB geta veri� notu� til a�
							flytja inn myndir. �� getur flutt �t myndir � XML form undir "M�nar myndir".
							For�ist a� breyta XML skj�lum fr� VCD-DB handvirkt.) ',
'ADD_MAXFILESIZE'		=> 'H�marks skr�rst�r�',
'ADD_DVDEMPIRE' 		=> 'S�kja beint fr� adultdvdempire.com (bl�ar myndir)',
'ADD_LISTEDSTEP1' 		=> 'Skref 1. <br/>Veldu myndir sem �� vilt b�ta vi� � listann �inn.<br/>
							� n�sta skrefi velur �� myndg��i. ',
'ADD_LISTEDSTEP2' 		=> 'Skref 2.<br/>Veldu vi�eigandi myndg��i.',
'ADD_INDB' 				=> 'Myndir � grunni',
'ADD_SELECTED' 			=> 'Valdir titlar',
'ADD_INFOLIST' 			=> 'Tv�smelltu � mynd til a� f�ra yfir, e�a nota�u �rina. <br/>
							H�gt er a� nota lyklabor�i� � valmyndinni vinstra megin <br/>
							til a� fl�ta fyrir a� finna �kve�na mynd.',
'ADD_NOTITLES' 			=> 'Enginn annar notandi hefur skr�� myndir i grunninn.',

/* Add from XML */
'XML_CONFIRM' 			=> 'Sta�festa XML innflutning',
'XML_CONTAINS' 			=> 'XML skjal inniheldur %d myndir.',
'XML_INFO1' 			=> '�ttu � sta�festa til a� lesa inn myndir og vista i grunninn.<br/>
							E�a �ttu � H�tta vi� til a� h�tta vi�. ',
'XML_INFO2' 			=> 'Ef �� vilt a� thumbnail myndir fylgi me� innflutningi myndanna sem �� ert a� flytja inn �r XML skjalinu,
							<b>ver�ur</b> �� a� eiga XML thumbnails skjali� tilb�i� l�ka n�na.<br/>
							Thumbnails geta ekki veri� sj�lfkrafa fluttir inn nema n�na.
							Ef �� ert n� �egar me� XML thumbnails skjali�, �ttu �� � t�kkboxi� h�r fyrir ne�an og finndu XML skjali� sem
							� a� fylgja me� myndunum ��num.  �� munu myndir ver�a fluttar inn me� vi�komandi titlum.',
'XML_THUMBNAILS'		=> 'Flytja inn thumbnails fr� XML skjali',
'XML_LIST'				=> 'Eftirfarandi titlar fundust � XML skjalinu.',
'XML_ERROR'				=> 'Engar myndir fundust � XML skjalinu.<br/>Skjal g�ti veri� t�mt e�a skemmt.
			   				<br/>Gangtu �r skugga um a� �� s�rt a� nota XML skjal sem var flutt �r VCD-db ..',
'XML_RESULTS'			=> 'Ni�urst��ur XML innflutnings.',
'XML_RESULTS2'			=> 'H�r eru ni�ust��ur XML innflutnings.<br/>Samtals %d myndir voru vista�ar � grunninn.',



/* Add from DVD Empire */
'EM_INFO'				=> 'Uppl�singar fr� adultdvdempire.com um myndina ....',
'EM_DESC'				=> 'DVDEmpire l�sing',
'EM_SUBCAT'				=> 'Undirflokkar',
'EM_DETAILS'			=> 'N�nari uppl�singar fr� Adultdvdempire.com',
'EM_STARS'				=> 'Leikarar',
'EM_NOTICE'				=> 'Leikarar merktir me� rau�u fundust ekki � grunninum.
							En �a� er h�gt a� haka vi� �� ef �� vilt b�ta �eim vi� � grunninn og
							tengja vi� �essa mynd.',
'EM_FETCH'				=> 'S�kja aukalega',

/* Loan System */
'LOAN_MOVIES'			=> 'Myndir til l�ns',
'LOAN_TO'				=> 'L�na myndir til',
'LOAN_ADDUSERS'			=> 'Byrja�u � a� b�a til l�n�ega',
'LOAN_NEWUSER'			=> 'N�r l�n�egi',
'LOAN_REGISTERUSER'		=> 'B�ta vi� l�n�ega',
'LOAN_NAME'				=> 'Nafn',
'LOAN_SELECT'			=> 'Veldu l�n�ega',
'LOAN_MOVIELOANS'		=> 'Myndir � l�ni ...',
'LOAN_REMINDER'			=> 'Senda �minningu',
'LOAN_HISTORY'			=> 'L�nasaga',
'LOAN_HISTORY2'			=> 'Sj� l�nas�gu',
'LOAN_SINCE'			=> 'S��an',
'LOAN_TIME'				=> 'T�mi si�an',
'LOAN_RETURN'			=> 'Skila eintaki',
'LOAN_SUCCESS'			=> 'Myndir hafa veri� settar � l�n',
'LOAN_OUT'				=> '�skila�',
'LOAN_DATEIN'			=> 'Dags inn',
'LOAN_DATEOUT'			=> 'Dags �t',
'LOAN_PERIOD'			=> 'L�nst�mi',
'LOAN_BACK'				=> 'Aftur � l�nayfirlit',
'LOAN_DAY'				=> 'dagur',
'LOAN_DAYS'				=> 'dagar',
'LOAN_TODAY'			=> 'fr� � dag',


/* RSS */
'RSS'					=> 'RSS Straumar',
'RSS_TITLE'				=> 'RSS straumar fr� ��rum VCD-DB f�l�gum',
'RSS_SITE'				=> 'RSS Vef straumur',
'RSS_USER'				=> 'RSS Notanda straumur',
'RSS_VIEW'				=> 'Sko�a RSS straum',
'RSS_ADD'				=> 'B�ta vi� RSS straum',
'RSS_NOTE'				=> 'Sl��u inn <strong>n�kv�ma sl��</strong> � VCD-DB til a� tengjast.<br/>
							Ef RSS straumur er virkja�ur � vi�komandi sl�� geturu vali� �r
							RSS straumum til a� birta � s��unni �inni.',
'RSS_FETCH'				=> 'S�kja RSS Lista',
'RSS_NONE'				=> 'Engir RSS straumar hafa veri� skilgreindir.',
'RSS_FOUND'				=> 'Eftirfarandi RSS straumar fundust, veldu �� sem �� vilt birta:',
'RSS_NOTFOUND'			=> 'Engar straumar fundust � sl��inni',


/* Wishlist */
'W_ADD'					=> 'Setja � �skalistann',
'W_ONLIST'				=> 'Er � �skalistanum',
'W_EMPTY'				=> '�skalistinn �inn er t�mur',
'W_OWN'					=> '�g � eintak af �essari mynd',
'W_NOTOWN'				=> '�g � ekki eintak af �essari mynd',


/* Comments */
'C_COMMENTS'			=> 'Athugasemdir',
'C_ADD'					=> 'Skr� athugasemd',
'C_NONE'				=> 'Engar athugasemdir hafa veri� sendar inn',
'C_TYPE'				=> 'Sl��u inn athugasemd',
'C_YOUR'				=> '��n athugasemd',
'C_POST'				=> 'Senda athugasemd',
'C_ERROR'				=> 'Skr��u �ig inn ��ur en �� sendir inn athugasemd',


/* Pornstars */
'P_NAME'				=> 'Nafn',
'P_WEB'					=> 'Vefs��a',
'P_MOVIECOUNT'			=> 'Fj�ldi mynda',

/* Seen List */
'S_SEENIT'				=> 'B�inn a� sj� hana',
'S_NOTSEENIT'			=> 'Eftir a� sj� hana',
'S_SEENITCLICK'			=> 'Smelltu til a� merkja sem s��a',
'S_NOTSEENITCLICK'		=> 'Smelltu til a� merkja mynd �s��a',

/* Statistics */
'STAT_TOP_MOVIES'		=> 'Movies in database',
'STAT_TOP_CATS'			=> 'Top categories',
'STAT_TOP_ACT'			=> 'Most active categories',
'STAT_TOP_COVERS'		=> 'Covers in database',
'STAT_TOTAL'			=> 'Total',
'STAT_TODAY'			=> 'Added today',
'STAT_WEEK'				=> 'Added in last 7 days',
'STAT_MONTH'			=> 'Added in last 30 days',

/* Mail messages */
'MAIL_RETURNTOPIC'		=> '�minning um skil',
'MAIL_RETURNMOVIES1'	=> '%s, vill minna �ig � a� skila myndunum m�num.\n
							�� ert me� eftirtalda diska � l�ni :\n\n',
'MAIL_RETURNMOVIES2'    => 'Vinsamlegast skili� diskunum sem fyrst\n Kve�jur %s \n\n
							ps. �etta er sj�lfvirkur p�stur fr� VCD-db kerfinu (http://vcddb.konni.com)',

'MAIL_NOTIFY'  			=> '<strong>N� mynd hefur veri� skr�� � grunninn</strong><br/>
							 Smelltu <a href="%s/?page=cd&vcd_id=%s">h�r</a> til a� k�kja � m�li�
							 <p>ps. �etta er sj�lfvirkur p�stur fr� VCD kerfinu.</p>',
'MAIL_REGISTER'		 	=> '%s, skr�ning ��n t�kst � VCD-db kerfi�.<br><br>Notandanafni� �itt er %s og lykilor�i�
							�itt er %s.<br><br>�� getur s��an skipt um lykilor� eftir a� �� hefur skr�� �ig inn.<br>
							VCD-db vefurinn er s��an <a href="%s" target="_new">h�r</a>.',

/* Player */
'PLAYER'				=> 'Spilari',
'PLAYER_PATH'			=> 'Sl��i',
'PLAYER_PARAM'			=> 'F�ribreytur',
'PLAYER_NOTE'			=> 'Sl��u inn fullan sl��a � spilara forriti� �itt.
							Spilarinn ver�ur a� geta teki� inn f�ribreytur � skj�l eins og td.
							BSPlayer fyrir Windows e�a MPlayer fyrir Linux.<br/>�� getur n�� � BS spilarann fr�tt
							<a href="http://www.bsplayer.org" target="_new">h�rna</a>
							og MPlayer <a href="http://www.MPlayerHQ.hu" target="_new">h�rna</a>.',


/* Metadata */
'META_MY'				=> 'M�n Aukagildi',
'META_NAME'				=> 'Lykill',
'META_DESC'				=> 'L�sing',
'META_TYPE'				=> 'Tegund',
'META_VALUE'			=> 'Gildi',
'META_NONE'				=> 'Engin aukagildi skr��.',

/* Ignore List */
'IGN_LIST'				=> '�tilokunar listi',
'IGN_DESC'				=> 'Ekki birta myndir fr� eftirfarandi notendum:',

/* Misc keywords */
'X_CONTAINS'			=> 'inniheldur',
'X_GRADE'				=> 'IMDB einkunn meira en',
'X_ANY'					=> 'Allt',
'X_TRYAGAIN'			=> 'Reyndu aftur',
'X_PROCEED' 			=> '�fram',
'X_SELECT' 				=> 'Veldu',
'X_CONFIRM' 			=> 'Sta�festa',
'X_CANCEL' 				=> 'H�tta vi�',
'X_ATTENTION' 			=> 'Athugi�!',
'X_STATUS' 				=> 'Sta�a',
'X_SUCCESS' 			=> 'T�kst',
'X_FAILURE' 			=> 'Mist�kst',
'X_YES' 				=> 'J�',
'X_NO' 					=> 'Nei',
'X_SHOWMORE' 			=> 'S�na meira',
'X_SHOWLESS' 			=> 'S�na minna',
'X_NEW' 				=> 'N�tt',
'X_CHANGE' 				=> 'breyta',
'X_DELETE' 				=> 'ey�a',
'X_UPDATE' 				=> 'Uppf�ra',
'X_SAVEANDCLOSE' 		=> 'Vista og loka',
'X_CLOSE' 				=> 'Loka',
'X_EDIT' 				=> 'Breyta',
'X_RESULTS' 			=> 'Ni�urst��ur',
'X_LATESTMOVIES' 		=> 'n�justu myndirnar',
'X_LATESTTV' 			=> 'n�justu ��ttirnir',
'X_LATESTBLUE' 			=> 'n�justu XXX',
'X_MOVIES' 				=> 'myndir',
'X_NOCATS' 				=> 'Engar myndir til � grunni.',
'X_NOUSERS' 			=> 'Engir virkir notendur',
'X_KEY' 				=> 'Lykill',
'X_SAVENEXT' 			=> 'Vista og breyta n�stu',
'X_SAVE' 				=> 'Vista',
'X_SEEN' 				=> 'S��',
'X_FOOTER'				=> 'Page Loaded in %s sec. (<i>%d Queries</i>) &nbsp; Copyright (c)',
'X_FOOTER_LINK'			=> 'Check out the official VCD-db website'




);

?>