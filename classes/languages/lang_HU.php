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
		Hungarian language file
		Thanks to MiszterX for the translation.

	*/



$_ = array(

/* Language Identifier */
'LANG_TYPE' 			=> 'HU',
'LANG_NAME' 			=> 'Magyar',
'LANG_CHARSET'		 	=> 'iso-8859-2',

/* Menu system */
'MENU_MINE' 			=> 'Saj�t men�',
'MENU_SETTINGS' 		=> 'Saj�t Be��l�t�saim',
'MENU_MOVIES' 			=> 'Saj�t filmjeim',
'MENU_ADDMOVIE' 		=> '�j film hozz�ad�sa',
'MENU_LOANSYSTEM'		=> 'K�lcs�nad� rendszer',
'MENU_WISHLIST' 		=> 'Saj�t K�v�ns�glist�m',
'MENU_CATEGORIES' 		=> 'Film Kateg�ri�k',
'MENU_RSS' 				=> 'Saj�t Rss Feeds',
'MENU_CONTROLPANEL' 	=> 'Vez�rl�pult',
'MENU_REGISTER' 		=> 'Regisztr�ci�',
'MENU_LOGOUT' 			=> 'Kil�p�s',
'MENU_SUBMIT'			=> 'Elk�ld',
'MENU_TOPUSERS'			=> 'Top felhaszn�l�k',
'MENU_WISHLISTPUBLIC'	=> 'M�sok k�v�ns�glist�ja',
'MENU_STATISTICS'		=> 'Statisztik�k',

/* Login */
'LOGIN' 				=> 'Bel�p�s',
'LOGIN_USERNAME' 		=> 'Felhaszn�l� n�v',
'LOGIN_PASSWORD' 		=> 'Password',
'LOGIN_REMEMBER' 		=> 'Eml�kezz r�m',
'LOGIN_INFO' 			=> 'Hagyd ezt a mez�t �resen ha <b>nem</b> akarod a password�det megv�ltoztatni.',

/* Register */
'REGISTER_TITLE'		=> 'Regisztr�ci�',
'REGISTER_FULLNAME' 	=> 'Teljes n�v',
'REGISTER_EMAIL' 		=> 'Email',
'REGISTER_AGAIN' 		=> 'Password �jra',
'REGISTER_DISABLED' 	=> 'Bocsi, Az administr�tor kikapcsolta a regisztr�ci�t most.',
'REGISTER_OK' 			=> 'Regisztr�ci� sikers volt, most be tudsz l�pni a VCD-dbbe.',

/* User Properties */
'PRO_NOTIFY' 			=> 'K�ldj�n nekem egy email, ha �j filme ker�lt hozz�ad�sra?',
'PRO_SHOW_ADULT' 		=> 'Mutassa a feln�tt tartalmat is az oldalon?',
'PRO_RSS' 				=> 'Engedj�jezem a RSS feed a saj�t filmjist�mr�l?',
'PRO_WISHLIST' 			=> 'Enged�jezem m�soknak, hogy l�thass�k az �n k�v�ns�glist�mat ?',
'PRO_USE_INDEX' 		=> 'Haszn�ljon index sz�m mez�t a m�di�k azonos�t�j�nak',
'PRO_SEEN_LIST' 		=> 'Tartsd meg ezt a filmet, hogy l�thassam',
'PRO_PLAYOPTION' 		=> 'Use client playback options',
'PRO_NFO' 				=> 'Enable NFO files?',

/* User Settings */
'SE_PLAYER' 			=> 'Lej�tsz� be�ll�t�sok',
'SE_OWNFEED' 			=> 'Saj�t feed megtekint�se',
'SE_CUSTOM' 			=> 'Customize my frontpage',
'SE_SHOWSTAT' 			=> 'Mutasd a statisztik�kat',
'SE_SHOWSIDE' 			=> 'Mutassa az �j filmeket a sidebar-ban',
'SE_SELECTRSS' 			=> 'Select RSS feeds',
'SE_PAGELOOK' 			=> 'Web layout',
'SE_PAGEMODE' 			=> 'Select default template:',
'SE_UPDATED'			=> 'User information updated',
'SE_UPDATE_FAILED'		=> 'Failed to update',


/* Search */
'SEARCH' 				=> 'Search',
'SEARCH_TITLE' 			=> 'C�m szerint',
'SEARCH_ACTOR' 			=> 'Sz�n�sz szerint',
'SEARCH_DIRECTOR' 		=> 'Rendez� szerint',
'SEARCH_RESULTS' 		=> '�rt�kel�ses keres�s',
'SEARCH_EXTENDED' 		=> 'R�szletes keres�s',
'SEARCH_NORESULT' 		=> 'A kereses�s eredm�nytelen volt',

/* Movie categories*/
'CAT_ACTION' 			=> 'Akci�',
'CAT_ADULT' 			=> 'Feln�tt',
'CAT_ADVENTURE' 		=> 'Kaland',
'CAT_ANIMATION' 		=> 'Anim�ci�s',
'CAT_ANIME' 			=> 'Anime / Manga',
'CAT_COMEDY' 			=> 'V�gj�t�k',
'CAT_CRIME' 			=> 'Krimi',
'CAT_DOCUMENTARY' 		=> 'Documentum',
'CAT_DRAMA' 			=> 'Dr�ma',
'CAT_FAMILY' 			=> 'Csal�di',
'CAT_FANTASY' 			=> 'Fantasy',
'CAT_FILMNOIR' 			=> 'Film Noir',
'CAT_HORROR' 			=> 'Horror',
'CAT_JAMESBOND' 		=> 'James Bond',
'CAT_MUSICVIDEO' 		=> 'Zenei Video',
'CAT_MUSICAL' 			=> 'Musical',
'CAT_MYSTERY' 			=> 'Mystery',
'CAT_ROMANCE' 			=> 'Romantikus',
'CAT_SCIFI' 			=> 'Sci-Fi',
'CAT_SHORT' 			=> 'R�vid',
'CAT_THRILLER' 			=> 'Thiller',
'CAT_TVSHOWS' 			=> 'TV Shows',
'CAT_WAR' 				=> 'H�bor�s',
'CAT_WESTERN' 			=> 'Western',
'CAT_XRATED' 			=> 'X-Rated',

/* Movie Listings */
'M_MOVIE' 				=> 'A Film',
'M_ACTORS' 				=> 'Szerepl�k',
'M_CATEGORY'		    => 'Kateg�ria',
'M_YEAR'				=> 'Kiad�s �ve',
'M_COPIES'				=> 'M�solatok',
'M_FROM' 				=> 'Sz�rmaz�s',
'M_TITLE' 				=> 'C�m',
'M_ALTTITLE' 			=> 'M�sik c�m',
'M_GRADE'				=> '�rt�kel�s',
'M_DIRECTOR' 			=> 'Rendez�',
'M_COUNTRY'				=> 'Megjelen�si Orsz�g',
'M_RUNTIME' 			=> 'Runtime',
'M_MINUTES'			 	=> 'perc',
'M_PLOT' 				=> 'Cselekm�ny',
'M_NOPLOT' 				=> 'Nem �ll rendelkez�sre �sszegz� cselekm�ny',
'M_COVERS' 				=> 'Bor�t�',
'M_AVAILABLE' 			=> 'Rendelkez�sre �ll� m�solatok',
'M_MEDIA'			 	=> 'M�dium',
'M_NUM' 				=> 'Darab CD\'k',
'M_DATE' 				=> 'Hozz�ad�s d�tumad',
'M_OWNER'			 	=> 'Birtokos',
'M_NOACTORS'		    => 'Nem tal�lhat� sz�n�sz',
'M_INFO'			    => 'Film inform�ci�',
'M_DETAILS'			    => 'R�szletes m�solatokDetails on my copy',
'M_MEDIATYPE'		    => 'Media t�pusa',
'M_COMMENT'			    => 'Hozz�sz�l�s',
'M_PRIVATE'				=> 'Priv�t film (m�s nem l�thatja)?',
'M_SCREENSHOTS'			=> 'K�perny�k�p',
'M_NOSCREENS'			=> 'Nincs k�perny�k�p',
'M_SHOW'				=> 'Mutasd',
'M_HIDE'				=> 'Rejtsd el',
'M_CHANGE'				=> 'Inform�ci� megv�ltoztat�sa',
'M_NOCOVERS'			=> 'Nincs CD-bor�t�',
'M_BYCAT'				=> 'Kateg�ria c�mek',
'M_CURRCAT'				=> 'Jelenlegi kateg�rai',
'M_TEXTVIEW'			=> 'Sz�veges n�zet',
'M_IMAGEVIEW'			=> 'K�pes n�zet',
'M_MINEONLY'			=> 'Csak az �n filmjeimet mutasd',
'M_SIMILAR'				=> 'Similar filmek',
'M_MEDIAINDEX'			=> 'M�di�k index',

/* IMDB */
'I_DETAILS'				=> 'IMDB r�szletek',
'I_PLOT'				=> 'Cselekm�ny �sszegz�s',
'I_GALLERY'				=> 'Fot� Gall�riar',
'I_TRAILERS'			=> 'El�zetesek',
'I_LINKS'				=> 'IMDB Linkek',
'I_NOT'					=> 'Nincs IMDB inform�ci� err�l',

/* DVD Specific */
'DVD_REGION'			=> 'Region',
'DVD_FORMAT'			=> 'Format',
'DVD_ASPECT'			=> 'Aspect ratio',
'DVD_AUDIO'				=> 'Audio',
'DVD_SUBTITLES'			=> 'Subtitles',

/* My Movies */
'MY_EXPORT' 			=> 'Adatok export�l�sa',
'MY_EXCEL' 				=> 'Export Excel dokumentumk�nt',
'MY_XML' 				=> 'Export XML dokumentumk�nt',
'MY_XMLTHUMBS'			=> 'Export thumbnails XMLk�nt',
'MY_ACTIONS'			=> 'Lehet�s�geim',
'MY_JOIN'				=> 'Disc join',
'MY_JOINMOVIES'			=> 'Disc join movies',
'MY_JOINSUSER'			=> 'Felhaszn�l� v�laszt�s',
'MY_JOINSMEDIA'			=> 'Media t�pus v�laszt�s',
'MY_JOINSCAT'			=> 'Kateg�ria v�laszt�s',
'MY_JOINSTYPE'			=> 'Lehet�s�g v�laszt�sa',
'MY_JOINSHOW'			=> 'Mutasd a tal�latokat',
'MY_NORESULTS'			=> 'Nincs tal�lat',
'MY_TEXTALL'			=> 'Mutasd (Text)',
'MY_PWALL'				=> 'Mutasd (Mind)',
'MY_PWMOVIES'			=> 'Mutasd (Filmeket)',
'MY_PWTV'				=> 'Mutasd (Tv Showkat)',
'MY_PWBLUE'				=> 'Mutasd ("K�k" filmeket)',
'MY_J1'					=> 'Movies i got but user not',
'MY_J2'					=> 'Movies that user owns but i dont',
'MY_J3'					=> 'Movies we both own',
'MY_OVERVIEW'			=> 'Kollekci� �ttekint�se',
'MY_INFO'				=> 'Ezen az oldalon megtal�lhatsz minden inform�ci�t a filmjeimr�l.
							To the right are actions you can run on your movie collection.
							You can also export your list as Excel for printing or use the XML
							export functions for backup or to move all your collection data from one
							VCD-db to another.',
'MY_KEYS'				=> 'Egyedi azonos�t� szerkeszt�se',
'MY_SEENLIST'			=> 'Megn�zett filmek kezel�se',
'MY_HELPPICKER'			=> 'Dobj egy filmet amit megn�zzek',
'MY_HELPPICKERINFO'		=> 'Nem tudod mit k�ne n�zni este?<br/>Haszn�ld a Filmt�rat, hogy seg�tsen a keres�sben.<br/>
							Be tudsz �ll�tani k�l�nb�z� sz�r�ket ami alapj�n javasol egy filmet a Filmt�r.',
'MY_FIND'				=> 'Megtal�lni egy filmet',
'MY_NOTSEEN'			=> 'Csak olyan filmeket javasolj amiket m�g nem l�ttam',
'MY_FRIENDS'			=> 'Bar�taim akik CD-ket k�rtek k�lcs�n t�llem',


/* Manager window */
'MAN_BASIC' 			=> 'Alap inform�ci�',
'MAN_IMDB' 				=> 'IMDB inf�',
'MAN_EMPIRE' 			=> 'DVDEmpire inf�',
'MAN_COPY' 				=> 'M�solatom',
'MAN_COPIES' 			=> 'M�solataim',
'MAN_NOCOPY' 			=> 'Nincs m�solatod',
'MAN_1COPY' 			=> 'M�sol',
'MAN_ADDACT' 			=> 'Sz�n�sz hozz�ad�sa',
'MAN_ADDTODB' 			=> '�j sz�n�sz hozz�ad�sa az adatb�zishoz',
'MAN_SAVETODB' 			=> 'Ment�s adatb�zisba',
'MAN_SAVETODBNCD' 		=> 'Ment�s adatb�zisba �s filmbe',
'MAN_INDB' 				=> 'A sz�n�sz az adatb�zisban',
'MAN_SEL' 				=> 'Sz�n�sz kiv�laszt�sa',
'MAN_STARS' 			=> 'Csillagok',
'MAN_BROWSE'			=> 'Tall�zd a file el�rhet�s�g�t',


/* Add movies */
'ADD_INFO' 				=> 'V�laszt ki a m�dot a film hozz�ad�s�hoz',
'ADD_IMDB' 				=> 'Lek�rni a Internet Movie Database-b�l',
'ADD_IMDBTITLE' 		=> 'Kulcssz� megad�sa keres�shez',
'ADD_MANUAL' 			=> 'Adat megad�sa k�zzel',
'ADD_LISTED' 			=> 'A film m�r benne van a list�ban',
'ADD_XML' 				=> 'FIlm hozz�ad�sa export�lt XML file-b�l',
'ADD_XMLFILE' 			=> 'V�laszd ki az XML file-t az import�l�shoz',
'ADD_XMLNOTE' 			=> '(Please note, only XML files that have been exported from another VCD-db application
							can be used to import your movies here. You can export your movies from the "My movies"
							section. You should avoid manual editing of the exported XML files. ) ',
'ADD_MAXFILESIZE'		=> 'Max filem�ret',
'ADD_DVDEMPIRE' 		=> 'Lek�r�s az Adult DVD Empire-t�l (X-rated filmek)',
'ADD_LISTEDSTEP1' 		=> 'Step 1<br/>Select the titles that you want to add to your list.<br/>You can select media
						    type in next step.',
'ADD_LISTEDSTEP2' 		=> 'Step 2.<br/>Select the appropriate media type.',
'ADD_INDB' 				=> 'Movies in VCD-DB',
'ADD_SELECTED' 			=> 'Kiv�lasztott c�mek',
'ADD_INFOLIST' 			=> 'Double click on title to select title or use the arrows.<br/>You can use the keyboard to
							quickly find titles.',
'ADD_NOTITLES' 			=> 'Nincs m�sik felhaszn�l�nak ez a film hozz�adva a Filmt�rba',


/* Add from XML */
'XML_CONFIRM' 			=> 'Az XML felt�tl�s�t igazol�sa',
'XML_CONTAINS' 			=> 'XML file  %d filmeket tartalmazza.',
'XML_INFO1' 			=> 'Press confirm to process the movies and save to the database.<br/>
							Or press cancel to bail out. ',
'XML_INFO2' 			=> 'If you want to include the thumbnails (posters) with the movies that you are about to
							import in your XML file, you <b>MUST</b> have the thumbnails XML file availeble now!.<br/>
							Posters cannot be imported after you have finished importing you movies from the current XML file.
							If you already have the thumbnails XML file available check the field below and in next step
							after the import of your movies in the list below, you will be asked to submit you thumbnails XML
							file aswell for processing. ',
'XML_THUMBNAILS'		=> 'Insert thumbnails from my thumbnails XML file ',
'XML_LIST'				=> 'Full list of movies found in XML file.',
'XML_ERROR'				=> 'No titles found in XML file.<br/>File could be damaged or just plain empty.
			   				<br/>Make sure that you are using XML file that was exported from the VCD-db..',
'XML_RESULTS'			=> 'XML upload results.',
'XML_RESULTS2'			=> 'Here are the results on your XML import.<br/>Total %d movies were imported.',


/* Add from DVD Empire */
'EM_INFO'				=> 'Inform�ci�k AdultDVDEmpire.com-t�l ....',
'EM_DESC'				=> 'DVDEmpire le�r�sa',
'EM_SUBCAT'				=> 'Adult kateg�ri�k',
'EM_DETAILS'			=> 'Adultdvdempire.com details',
'EM_STARS'				=> 'Porn�csillagok',
'EM_NOTICE'				=> 'Actors marked red are currently not in the VCD-DB.
							But you can check their names and they will be automatically added to the VCD-db
						    and associated with this movie.',
'EM_FETCH'				=> 'Fetch Also',

/* Loan System */
'LOAN_MOVIES'			=> 'Filmek k�lcs�nz�sre',
'LOAN_TO'				=> 'K�lcs�nadom a filmet neki',
'LOAN_ADDUSERS'			=> 'Adj n�h�ny k�lcs�nz� felhaszn�l�t hogy folytathasd',
'LOAN_NEWUSER'			=> '�j k�lcs�nz�',
'LOAN_REGISTERUSER'		=> '�jk�lcs�nz� hozz�ad�sa',
'LOAN_NAME'				=> 'Neve',
'LOAN_SELECT'			=> 'K�lcs�nz� kiv�laszt�sa',
'LOAN_MOVIELOANS'		=> 'K�lcs�nzend� filmek ...',
'LOAN_REMINDER'			=> 'K�ldj eml�keztet�st',
'LOAN_HISTORY'			=> 'K�lcs�nz�si napl�',
'LOAN_HISTORY2'			=> 'K�lcs�nz�si napl� megtekint�se',
'LOAN_SINCE'			=> 'T�l',
'LOAN_TIME'				=> 'Id�pont�l',
'LOAN_RETURN'			=> 'Visszaadva a m�solat',
'LOAN_SUCCESS'			=> 'A film k�lcs�nz�se j�v�hagyva',
'LOAN_OUT'				=> 'Nincs visszahozva',
'LOAN_DATEIN'			=> 'Visszahozva',
'LOAN_DATEOUT'			=> 'Kiadva',
'LOAN_PERIOD'			=> 'Loan period',
'LOAN_BACK'				=> 'Back to loan index',
'LOAN_DAY'				=> 'nap',
'LOAN_DAYS'				=> 'napok',
'LOAN_TODAY'			=> 'm�t�l',


/* RSS */
'RSS'					=> 'RSS Feeds',
'RSS_TITLE'				=> 'RSS feeds from my friends VCD-DB sites',
'RSS_SITE'				=> 'RSS Site feed',
'RSS_USER'				=> 'RSS User feed',
'RSS_VIEW'				=> 'View RSS feed',
'RSS_ADD'				=> 'Add new feed',
'RSS_NOTE'				=> 'Enter the <strong>excact url</strong> of your friends VCD database.<br/>
							If RSS is enabled on your friends site you can pick out the
							feeds that you are interested in and display it on your page.',
'RSS_FETCH'				=> 'Fetch RSS List',
'RSS_NONE'				=> 'No RSS feeds have been added.',
'RSS_FOUND'				=> 'The following RSS feeds were found, select the feeds you want to add:',
'RSS_NOTFOUND'			=> 'No feeds found at location',


/* Wishlist */
'W_ADD'					=> 'Hozz�ad�s a K�v�ns�glist�mhoz',
'W_ONLIST'				=> 'A saj�t k�v�ns�glist�don',
'W_EMPTY'				=> 'A k�v�ns�glist�d �res',
'W_OWN'					=> 'M�r van m�solatom err�l a filmr�l',
'W_NOTOWN'				=> 'Nem rendelkezem m�solattal err�l a filmr�l',


/* Comments */
'C_COMMENTS'			=> 'Hozz�sz�l�sok',
'C_ADD'					=> '�j hozz�sz�l�s hozz�ad�asa',
'C_NONE'				=> 'Nincs hozz�sz�l�s',
'C_TYPE'				=> '�rj egy �j hozz�sz�l�st',
'C_YOUR'				=> 'A te hozz�sz�l�sod',
'C_POST'				=> 'Post comment',
'C_ERROR'				=> 'Be kell jelentkezned hogy komment�lhass',


/* Pornstars */
'P_NAME'				=> 'N�v',
'P_WEB'					=> 'Website',
'P_MOVIECOUNT'			=> 'Film sz�ml�l�',


/* Seen List */
'S_SEENIT'				=> 'M�r l�ttam',
'S_NOTSEENIT'			=> 'M�g nem l�ttam ',
'S_SEENITCLICK'			=> 'M�r l�ttam-ra �ll�t�shoz klikk',
'S_NOTSEENITCLICK'		=> 'M�g neml�ttam-ra �ll�t�shoz klikk',

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
'MAIL_RETURNTOPIC'		=> 'Loan reminder',
'MAIL_RETURNMOVIES1'	=> '%s, csak eml�keztetni szeretn�lek hogy vissza kell hoznod a filmem.\n
							You still have the following movies :\n\n',
'MAIL_RETURNMOVIES2'    => 'K�rlek hozd vissza amilyen gyorsan csak tudod a lemezeim \n �dv�zlettel %s \n\n
							ui. Ez egy aut�matikus e-mail k�ld� rendszer (http://filmtar.xorp.hu)',
'MAIL_NOTIFY'  		    => '<strong>�j film lett hozz�adva a Filmt�rhoz</strong><br/>
							 Klikk <a href="%s/?page=cd&vcd_id=%s">ide</a>, hogy megn�zhesd ..
							 <p>ui. Ez egy aut�matikus e-mail k�ldo rendszer (http://filmtar.xorp.hu)</p>',
'MAIL_REGISTER'		 	=> '%s, regisztr�ci�d a filmt�rhoz megt�rt�nt.\n\nA felhaszn�l� neved:%s �s a password�d:
							%s.\n\nB�rmikor megv�ltoztathatod a jelszavad, hogy bejelentkezt�l.\n
							Klikk <a href="%s" target="_new">ide</a> a Filmt�rhoz.',


/* Player */
'PLAYER'				=> 'Lej�tsz�',
'PLAYER_PATH'			=> 'El�r�si �tvonal',
'PLAYER_PARAM'			=> 'Param�terek',
'PLAYER_NOTE'			=> 'Add meg a teljes el�r�si �tvonal�t a filmenk a merevlemezeden.
							A lej�tsz�dnak k�pesnek kell lennie az param�terek �tad�s�ra, ilyen p�ld�ul
							BSPlayer Win32-re vagy  MPlayer Linux-ra.<br/>YLet�ltheted a BSPlayer-t ingyen
							<a href="http://www.bsplayer.org" target="_new">innen</a>
							�s a MPlayer-t <a href="http://www.MPlayerHQ.hu" target="_new">innen</a>.',

/* Metadata */
'META_MY'				=> 'My Metadata',
'META_NAME'				=> 'Name',
'META_DESC'				=> 'Description',
'META_TYPE'				=> 'Meta type',
'META_VALUE'			=> 'Meta value',
'META_NONE'				=> 'No Metadata exists.',

/* Ignore List */
'IGN_LIST'				=> 'Ignore List',
'IGN_DESC'				=> 'Ignore all movies from the following users:',

/* Misc keywords */
'X_CONTAINS'			=> 'tartalmak',
'X_GRADE'				=> 'IMDB �rt�kel�s alapj�n vagy jobb',
'X_ANY'					=> 'M�s',
'X_TRYAGAIN'			=> 'Pr�b�ld �jra',
'X_PROCEED' 			=> 'V�grehajtva',
'X_SELECT' 				=> 'kiv�laszt',
'X_CONFIRM' 			=> 'J�v�hagy',
'X_CANCEL' 				=> 'M�gse',
'X_ATTENTION' 			=> 'FIGYELEM!',
'X_STATUS' 				=> 'St�tusz',
'X_SUCCESS' 			=> 'K�sz',
'X_FAILURE' 			=> 'Hiba',
'X_YES' 				=> 'Igen',
'X_NO' 					=> 'Nem',
'X_SHOWMORE' 			=> 'Mutass t�bbet',
'X_SHOWLESS' 			=> 'Mutass kevesebbet',
'X_NEW' 				=> '�j',
'X_CHANGE' 				=> 'megv�ltoztat',
'X_DELETE' 				=> 't�r�l',
'X_UPDATE' 				=> 'Friss�t',
'X_SAVEANDCLOSE' 		=> 'Ment �s bez�r',
'X_CLOSE' 				=> 'Bez�r',
'X_EDIT' 				=> 'Szerkeszt',
'X_RESULTS' 			=> 'Tal�latok',
'X_LATESTMOVIES' 		=> 'Utols� filmek',
'X_LATESTTV' 			=> 'Utols� TV m�sorok',
'X_LATESTBLUE' 			=> 'utols� X-rated',
'X_MOVIES' 				=> 'filmek',
'X_NOCATS' 				=> 'Nincs film hozz�adva.',
'X_NOUSERS' 			=> 'Nincs akt�v felhaszn�l�',
'X_KEY' 				=> 'kulcs',
'X_SAVENEXT' 			=> 'Ment�s �s szerkeszt a k�vetkez�t',
'X_SAVE' 				=> 'Ment�s',
'X_SEEN' 				=> 'Megn�zve',
'X_FOOTER'				=> 'Page Loaded in %s sec. (<i>%d Queries</i>) &nbsp; Copyright (c)',
'X_FOOTER_LINK'			=> 'Check out the official VCD-db website'



);


?>
