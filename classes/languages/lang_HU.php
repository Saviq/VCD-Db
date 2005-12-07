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
'MENU_MINE' 			=> 'Saját menü',
'MENU_SETTINGS' 		=> 'Saját Beáálításaim',
'MENU_MOVIES' 			=> 'Saját filmjeim',
'MENU_ADDMOVIE' 		=> 'Új film hozzáadása',
'MENU_LOANSYSTEM'		=> 'Kölcsönadó rendszer',
'MENU_WISHLIST' 		=> 'Saját Kívánságlistám',
'MENU_CATEGORIES' 		=> 'Film Kategóriák',
'MENU_RSS' 				=> 'Saját Rss Feeds',
'MENU_CONTROLPANEL' 	=> 'Vezérlõpult',
'MENU_REGISTER' 		=> 'Regisztráció',
'MENU_LOGOUT' 			=> 'Kilépés',
'MENU_SUBMIT'			=> 'Elküld',
'MENU_TOPUSERS'			=> 'Top felhasználók',
'MENU_WISHLISTPUBLIC'	=> 'Mások kívánságlistája',
'MENU_STATISTICS'		=> 'Statisztikák',

/* Login */
'LOGIN' 				=> 'Belépés',
'LOGIN_USERNAME' 		=> 'Felhasználó név',
'LOGIN_PASSWORD' 		=> 'Password',
'LOGIN_REMEMBER' 		=> 'Emlékezz rám',
'LOGIN_INFO' 			=> 'Hagyd ezt a mezõt üresen ha <b>nem</b> akarod a passwordödet megváltoztatni.',

/* Register */
'REGISTER_TITLE'		=> 'Regisztráció',
'REGISTER_FULLNAME' 	=> 'Teljes név',
'REGISTER_EMAIL' 		=> 'Email',
'REGISTER_AGAIN' 		=> 'Password újra',
'REGISTER_DISABLED' 	=> 'Bocsi, Az administrátor kikapcsolta a regisztrációt most.',
'REGISTER_OK' 			=> 'Regisztráció sikers volt, most be tudsz lépni a VCD-dbbe.',

/* User Properties */
'PRO_NOTIFY' 			=> 'Küldjön nekem egy email, ha új filme került hozzáadásra?',
'PRO_SHOW_ADULT' 		=> 'Mutassa a felnõtt tartalmat is az oldalon?',
'PRO_RSS' 				=> 'Engedjéjezem a RSS feed a saját filmjistámról?',
'PRO_WISHLIST' 			=> 'Engedéjezem másoknak, hogy láthassák az én kívánságlistámat ?',
'PRO_USE_INDEX' 		=> 'Használjon index szám mezõt a médiák azonosítójának',
'PRO_SEEN_LIST' 		=> 'Tartsd meg ezt a filmet, hogy láthassam',
'PRO_PLAYOPTION' 		=> 'Use client playback options',

/* User Settings */
'SE_PLAYER' 			=> 'Lejátszó beállítások',
'SE_OWNFEED' 			=> 'Saját feed megtekintése',
'SE_CUSTOM' 			=> 'Customize my frontpage',
'SE_SHOWSTAT' 			=> 'Mutasd a statisztikákat',
'SE_SHOWSIDE' 			=> 'Mutassa az új filmeket a sidebar-ban',
'SE_SELECTRSS' 			=> 'Select RSS feeds',
'SE_PAGELOOK' 			=> 'Web layout',
'SE_PAGEMODE' 			=> 'Select default template:',


/* Search */
'SEARCH' 				=> 'Search',
'SEARCH_TITLE' 			=> 'Cím szerint',
'SEARCH_ACTOR' 			=> 'Színész szerint',
'SEARCH_DIRECTOR' 		=> 'Rendezõ szerint',
'SEARCH_RESULTS' 		=> 'Értékeléses keresés',
'SEARCH_EXTENDED' 		=> 'Részletes keresés',
'SEARCH_NORESULT' 		=> 'A keresesés eredménytelen volt',

/* Movie categories*/
'CAT_ACTION' 			=> 'Akció',
'CAT_ADULT' 			=> 'Felnõtt',
'CAT_ADVENTURE' 		=> 'Kaland',
'CAT_ANIMATION' 		=> 'Animációs',
'CAT_ANIME' 			=> 'Anime / Manga',
'CAT_COMEDY' 			=> 'Vígjáték',
'CAT_CRIME' 			=> 'Krimi',
'CAT_DOCUMENTARY' 		=> 'Documentum',
'CAT_DRAMA' 			=> 'Dráma',
'CAT_FAMILY' 			=> 'Családi',
'CAT_FANTASY' 			=> 'Fantasy',
'CAT_FILMNOIR' 			=> 'Film Noir',
'CAT_HORROR' 			=> 'Horror',
'CAT_JAMESBOND' 		=> 'James Bond',
'CAT_MUSICVIDEO' 		=> 'Zenei Video',
'CAT_MUSICAL' 			=> 'Musical',
'CAT_MYSTERY' 			=> 'Mystery',
'CAT_ROMANCE' 			=> 'Romantikus',
'CAT_SCIFI' 			=> 'Sci-Fi',
'CAT_SHORT' 			=> 'Rövid',
'CAT_THRILLER' 			=> 'Thiller',
'CAT_TVSHOWS' 			=> 'TV Shows',
'CAT_WAR' 				=> 'Háborús',
'CAT_WESTERN' 			=> 'Western',
'CAT_XRATED' 			=> 'X-Rated',

/* Movie Listings */
'M_MOVIE' 				=> 'A Film',
'M_ACTORS' 				=> 'Szereplõk',
'M_CATEGORY'		    => 'Kategória',
'M_YEAR'				=> 'Kiadás éve',
'M_COPIES'				=> 'Másolatok',
'M_FROM' 				=> 'Származás',
'M_TITLE' 				=> 'Cím',
'M_ALTTITLE' 			=> 'Másik cím',
'M_GRADE'				=> 'Értékelés',
'M_DIRECTOR' 			=> 'Rendezõ',
'M_COUNTRY'				=> 'Megjelenési Ország',
'M_RUNTIME' 			=> 'Runtime',
'M_MINUTES'			 	=> 'perc',
'M_PLOT' 				=> 'Cselekmény',
'M_NOPLOT' 				=> 'Nem áll rendelkezésre összegzõ cselekmény',
'M_COVERS' 				=> 'Borító',
'M_AVAILABLE' 			=> 'Rendelkezésre álló másolatok',
'M_MEDIA'			 	=> 'Médium',
'M_NUM' 				=> 'Darab CD\'k',
'M_DATE' 				=> 'Hozzáadás dátumad',
'M_OWNER'			 	=> 'Birtokos',
'M_NOACTORS'		    => 'Nem található színész',
'M_INFO'			    => 'Film információ',
'M_DETAILS'			    => 'Részletes másolatokDetails on my copy',
'M_MEDIATYPE'		    => 'Media típusa',
'M_COMMENT'			    => 'Hozzászólás',
'M_PRIVATE'				=> 'Privát film (más nem láthatja)?',
'M_SCREENSHOTS'			=> 'Képernyõkép',
'M_NOSCREENS'			=> 'Nincs képernyõkép',
'M_SHOW'				=> 'Mutasd',
'M_HIDE'				=> 'Rejtsd el',
'M_CHANGE'				=> 'Információ megváltoztatása',
'M_NOCOVERS'			=> 'Nincs CD-borító',
'M_BYCAT'				=> 'Kategória címek',
'M_CURRCAT'				=> 'Jelenlegi kategórai',
'M_TEXTVIEW'			=> 'Szöveges nézet',
'M_IMAGEVIEW'			=> 'Képes nézet',
'M_MINEONLY'			=> 'Csak az én filmjeimet mutasd',
'M_SIMILAR'				=> 'Similar filmek',
'M_MEDIAINDEX'			=> 'Médiák index',

/* IMDB */
'I_DETAILS'				=> 'IMDB részletek',
'I_PLOT'				=> 'Cselekmény összegzés',
'I_GALLERY'				=> 'Fotó Gallériar',
'I_TRAILERS'			=> 'Elõzetesek',
'I_LINKS'				=> 'IMDB Linkek',
'I_NOT'					=> 'Nincs IMDB információ errõl',

/* DVD Specific */
'DVD_REGION'			=> 'Region',
'DVD_FORMAT'			=> 'Format',
'DVD_ASPECT'			=> 'Aspect ratio',
'DVD_AUDIO'				=> 'Audio',
'DVD_SUBTITLES'			=> 'Subtitles',

/* My Movies */
'MY_EXPORT' 			=> 'Adatok exportálása',
'MY_EXCEL' 				=> 'Export Excel dokumentumként',
'MY_XML' 				=> 'Export XML dokumentumként',
'MY_XMLTHUMBS'			=> 'Export thumbnails XMLként',
'MY_ACTIONS'			=> 'Lehetõségeim',
'MY_JOIN'				=> 'Disc join',
'MY_JOINMOVIES'			=> 'Disc join movies',
'MY_JOINSUSER'			=> 'Felhasználó választás',
'MY_JOINSMEDIA'			=> 'Media típus választás',
'MY_JOINSCAT'			=> 'Kategória választás',
'MY_JOINSTYPE'			=> 'Lehetõség választása',
'MY_JOINSHOW'			=> 'Mutasd a találatokat',
'MY_NORESULTS'			=> 'Nincs találat',
'MY_PWALL'				=> 'Mutasd (Mind)',
'MY_PWMOVIES'			=> 'Mutasd (Filmeket)',
'MY_PWTV'				=> 'Mutasd (Tv Showkat)',
'MY_PWBLUE'				=> 'Mutasd ("Kék" filmeket)',
'MY_J1'					=> 'Movies i got but user not',
'MY_J2'					=> 'Movies that user owns but i dont',
'MY_J3'					=> 'Movies we both own',
'MY_OVERVIEW'			=> 'Kollekció áttekintése',
'MY_INFO'				=> 'Ezen az oldalon megtalálhatsz minden információt a filmjeimrõl.
							To the right are actions you can run on your movie collection.
							You can also export your list as Excel for printing or use the XML
							export functions for backup or to move all your collection data from one
							VCD-db to another.',
'MY_KEYS'				=> 'Egyedi azonosító szerkesztése',
'MY_SEENLIST'			=> 'Megnézett filmek kezelése',
'MY_HELPPICKER'			=> 'Dobj egy filmet amit megnézzek',
'MY_HELPPICKERINFO'		=> 'Nem tudod mit kéne nézni este?<br/>Használd a Filmtárat, hogy segítsen a keresésben.<br/>
							Be tudsz állítani különbözõ szõrõket ami alapján javasol egy filmet a Filmtár.',
'MY_FIND'				=> 'Megtalálni egy filmet',
'MY_NOTSEEN'			=> 'Csak olyan filmeket javasolj amiket még nem láttam',
'MY_FRIENDS'			=> 'Barátaim akik CD-ket kértek kölcsön tõllem',


/* Manager window */
'MAN_BASIC' 			=> 'Alap információ',
'MAN_IMDB' 				=> 'IMDB infó',
'MAN_EMPIRE' 			=> 'DVDEmpire infó',
'MAN_COPY' 				=> 'Másolatom',
'MAN_COPIES' 			=> 'Másolataim',
'MAN_NOCOPY' 			=> 'Nincs másolatod',
'MAN_1COPY' 			=> 'Másol',
'MAN_ADDACT' 			=> 'Színész hozzáadása',
'MAN_ADDTODB' 			=> 'Új színész hozzáadása az adatbázishoz',
'MAN_SAVETODB' 			=> 'Mentés adatbázisba',
'MAN_SAVETODBNCD' 		=> 'Mentés adatbázisba és filmbe',
'MAN_INDB' 				=> 'A színész az adatbázisban',
'MAN_SEL' 				=> 'Színész kiválasztása',
'MAN_STARS' 			=> 'Csillagok',
'MAN_BROWSE'			=> 'Tallózd a file elérhetõségét',


/* Add movies */
'ADD_INFO' 				=> 'Választ ki a módot a film hozzáadásához',
'ADD_IMDB' 				=> 'Lekérni a Internet Movie Database-ból',
'ADD_IMDBTITLE' 		=> 'Kulcsszó megadása kereséshez',
'ADD_MANUAL' 			=> 'Adat megadása kézzel',
'ADD_LISTED' 			=> 'A film már benne van a listában',
'ADD_XML' 				=> 'FIlm hozzáadása exportált XML file-bõl',
'ADD_XMLFILE' 			=> 'Válaszd ki az XML file-t az importáláshoz',
'ADD_XMLNOTE' 			=> '(Please note, only XML files that have been exported from another VCD-db application 
							can be used to import your movies here. You can export your movies from the "My movies" 
							section. You should avoid manual editing of the exported XML files. ) ',
'ADD_MAXFILESIZE'		=> 'Max fileméret',
'ADD_DVDEMPIRE' 		=> 'Lekérés az Adult DVD Empire-tõl (X-rated filmek)',
'ADD_LISTEDSTEP1' 		=> 'Step 1<br/>Select the titles that you want to add to your list.<br/>You can select media 
						    type in next step.',
'ADD_LISTEDSTEP2' 		=> 'Step 2.<br/>Select the appropriate media type.',
'ADD_INDB' 				=> 'Movies in VCD-DB',
'ADD_SELECTED' 			=> 'Kiválasztott címek',
'ADD_INFOLIST' 			=> 'Double click on title to select title or use the arrows.<br/>You can use the keyboard to
							quickly find titles.',
'ADD_NOTITLES' 			=> 'Nincs másik felhasználónak ez a film hozzáadva a Filmtárba',


/* Add from XML */
'XML_CONFIRM' 			=> 'Az XML feltötlését igazolása',
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
'EM_INFO'				=> 'Információk AdultDVDEmpire.com-tól ....',
'EM_DESC'				=> 'DVDEmpire leírása',
'EM_SUBCAT'				=> 'Adult kategóriák',
'EM_DETAILS'			=> 'Adultdvdempire.com details',
'EM_STARS'				=> 'Pornócsillagok',
'EM_NOTICE'				=> 'Actors marked red are currently not in the VCD-DB.
							But you can check their names and they will be automatically added to the VCD-db
						    and associated with this movie.',
'EM_FETCH'				=> 'Fetch Also',

/* Loan System */
'LOAN_MOVIES'			=> 'Filmek kölcsönzésre',
'LOAN_TO'				=> 'Kölcsönadom a filmet neki',
'LOAN_ADDUSERS'			=> 'Adj néhány kölcsönzõ felhasználót hogy folytathasd',
'LOAN_NEWUSER'			=> 'Új kölcsönzõ',
'LOAN_REGISTERUSER'		=> 'Újkölcsönzõ hozzáadása',
'LOAN_NAME'				=> 'Neve',
'LOAN_SELECT'			=> 'Kölcsönzõ kiválasztása',
'LOAN_MOVIELOANS'		=> 'Kölcsönzendõ filmek ...',
'LOAN_REMINDER'			=> 'Küldj emlékeztetést',
'LOAN_HISTORY'			=> 'Kölcsönzési napló',
'LOAN_HISTORY2'			=> 'Kölcsönzési napló megtekintése',
'LOAN_SINCE'			=> 'Tõl',
'LOAN_TIME'				=> 'Idõpontól',
'LOAN_RETURN'			=> 'Visszaadva a másolat',
'LOAN_SUCCESS'			=> 'A film kölcsönzése jóváhagyva',
'LOAN_OUT'				=> 'Nincs visszahozva',
'LOAN_DATEIN'			=> 'Visszahozva',
'LOAN_DATEOUT'			=> 'Kiadva',
'LOAN_PERIOD'			=> 'Loan period',
'LOAN_BACK'				=> 'Back to loan index',
'LOAN_DAY'				=> 'nap',
'LOAN_DAYS'				=> 'napok',
'LOAN_TODAY'			=> 'mától',


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
'W_ADD'					=> 'Hozzáadás a Kívánságlistámhoz',
'W_ONLIST'				=> 'A saját kívánságlistádon',
'W_EMPTY'				=> 'A kívánságlistád üres',
'W_OWN'					=> 'Már van másolatom errõl a filmrõl',
'W_NOTOWN'				=> 'Nem rendelkezem másolattal errõl a filmrõl',


/* Comments */
'C_COMMENTS'			=> 'Hozzászólások',
'C_ADD'					=> 'Új hozzászólás hozzáadáasa',
'C_NONE'				=> 'Nincs hozzászólás',
'C_TYPE'				=> 'Írj egy új hozzászólást',
'C_YOUR'				=> 'A te hozzászólásod',
'C_POST'				=> 'Post comment',
'C_ERROR'				=> 'Be kell jelentkezned hogy kommentálhass',


/* Pornstars */
'P_NAME'				=> 'Név',
'P_WEB'					=> 'Website',
'P_MOVIECOUNT'			=> 'Film számláló',


/* Seen List */
'S_SEENIT'				=> 'Már láttam',
'S_NOTSEENIT'			=> 'Még nem láttam ',
'S_SEENITCLICK'			=> 'Már láttam-ra állításhoz klikk',
'S_NOTSEENITCLICK'		=> 'Még nemláttam-ra állításhoz klikk',

/* Mail messages */
'MAIL_RETURNTOPIC'		=> 'Loan reminder',
'MAIL_RETURNMOVIES1'	=> '%s, csak emlékeztetni szeretnélek hogy vissza kell hoznod a filmem.\n
							You still have the following movies :\n\n',
'MAIL_RETURNMOVIES2'    => 'Kérlek hozd vissza amilyen gyorsan csak tudod a lemezeim \n Üdvözlettel %s \n\n
							ui. Ez egy autómatikus e-mail küldõ rendszer (http://filmtar.xorp.hu)',
'MAIL_NOTIFY'  		    => '<strong>Új film lett hozzáadva a Filmtárhoz</strong><br/>
							 Klikk <a href="%s/?page=cd&vcd_id=%s">ide</a>, hogy megnézhesd ..
							 <p>ui. Ez egy autómatikus e-mail küldo rendszer (http://filmtar.xorp.hu)</p>',
'MAIL_REGISTER'		 	=> '%s, regisztrációd a filmtárhoz megtörtént.\n\nA felhasználó neved:%s és a passwordöd: 
							%s.\n\nBármikor megváltoztathatod a jelszavad, hogy bejelentkeztél.\n
							Klikk <a href="%s" target="_new">ide</a> a Filmtárhoz.',


/* Player */
'PLAYER'				=> 'Lejátszó',
'PLAYER_PATH'			=> 'Elérési útvonal',
'PLAYER_PARAM'			=> 'Paraméterek',
'PLAYER_NOTE'			=> 'Add meg a teljes elérési útvonalát a filmenk a merevlemezeden.
							A lejátszódnak képesnek kell lennie az paraméterek átadására, ilyen például
							BSPlayer Win32-re vagy  MPlayer Linux-ra.<br/>YLetöltheted a BSPlayer-t ingyen 
							<a href="http://www.bsplayer.org" target="_new">innen</a> 
							és a MPlayer-t <a href="http://www.MPlayerHQ.hu" target="_new">innen</a>.',


/* Misc keywords */
'X_CONTAINS'			=> 'tartalmak',
'X_GRADE'				=> 'IMDB értékelés alapján vagy jobb',
'X_ANY'					=> 'Más',
'X_TRYAGAIN'			=> 'Próbáld újra',
'X_PROCEED' 			=> 'Végrehajtva',
'X_SELECT' 				=> 'kiválaszt',
'X_CONFIRM' 			=> 'Jóváhagy',
'X_CANCEL' 				=> 'Mégse',
'X_ATTENTION' 			=> 'FIGYELEM!',
'X_STATUS' 				=> 'Státusz',
'X_SUCCESS' 			=> 'Kész',
'X_FAILURE' 			=> 'Hiba',
'X_YES' 				=> 'Igen',
'X_NO' 					=> 'Nem',
'X_SHOWMORE' 			=> 'Mutass többet',
'X_SHOWLESS' 			=> 'Mutass kevesebbet',
'X_NEW' 				=> 'Új',
'X_CHANGE' 				=> 'megváltoztat',
'X_DELETE' 				=> 'töröl',
'X_UPDATE' 				=> 'Frissít',
'X_SAVEANDCLOSE' 		=> 'Ment és bezár',
'X_CLOSE' 				=> 'Bezár',
'X_EDIT' 				=> 'Szerkeszt',
'X_RESULTS' 			=> 'Találatok',
'X_LATESTMOVIES' 		=> 'Utolsó filmek',
'X_LATESTTV' 			=> 'Utolsó TV mûsorok',
'X_LATESTBLUE' 			=> 'utolsó X-rated',
'X_MOVIES' 				=> 'filmek',
'X_NOCATS' 				=> 'Nincs film hozzáadva.',
'X_NOUSERS' 			=> 'Nincs aktív felhasználó',
'X_KEY' 				=> 'kulcs',
'X_SAVENEXT' 			=> 'Mentés és szerkeszt a következõt',
'X_SAVE' 				=> 'Mentés',
'X_SEEN' 				=> 'Megnézve'


);


?>
