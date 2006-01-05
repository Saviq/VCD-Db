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
      Danish language file
      Initial version translated by Gundelach
   */



$_ = array(


/* Language Identifier */
'LANG_TYPE' 			=> 'DK',
'LANG_NAME'				=> 'Danish',
'LANG_CHARSET'			=> 'iso-8859-1',

/* Menu system */
'MENU_MINE' 			=> 'Min menu',
'MENU_SETTINGS' 		=> 'Mine indstillinger',
'MENU_MOVIES' 			=> 'Mine film',
'MENU_ADDMOVIE' 		=> 'Tilf�j ny film',
'MENU_LOANSYSTEM' 		=> 'Udl�n',
'MENU_WISHLIST' 		=> 'Min �nskeliste',
'MENU_CATEGORIES' 		=> 'Filmkategorier',
'MENU_RSS' 				=> 'My Rss Feeds',
'MENU_CONTROLPANEL' 	=> 'Kontrolpanel',
'MENU_REGISTER' 		=> 'Registr�r',
'MENU_LOGOUT' 			=> 'Log ud',
'MENU_SUBMIT' 			=> 'Send',
'MENU_TOPUSERS' 		=> 'Topbrugere',
'MENU_WISHLISTPUBLIC' 	=> 'Andres �nskelister',

/* Login */
'LOGIN' 				=> 'Login',
'LOGIN_USERNAME' 		=> 'Brugernavn',
'LOGIN_PASSWORD' 		=> 'Password',
'LOGIN_REMEMBER' 		=> 'Husk mine loginfo.',
'LOGIN_INFO' 			=> 'Lad dette felt v�re tomt hvis du <b>ikke</b> vil �ndre dit password',

/* Register */
'REGISTER_TITLE' 		=> 'Oprettelse',
'REGISTER_FULLNAME' 	=> 'Fulde navn',
'REGISTER_EMAIL' 		=> 'Email',
'REGISTER_AGAIN' 		=> 'Password igen',
'REGISTER_DISABLED' 	=> 'Beklager, administratoren har lukket for tilmelding indtil videre',
'REGISTER_OK' 			=> 'Oprettelsen er gennemf�rt, du kan nu logge ind i databasen.',

/* User Properties */
'PRO_NOTIFY' 			=> 'Send mig en emial n�r en ny film bliver tilf�jet.',
'PRO_SHOW_ADULT' 		=> 'Vis pornofilm i oversigten',
'PRO_RSS' 				=> 'Tillad RSS tilf�jelser fra min filmliste?',
'PRO_WISHLIST' 			=> 'Allow others to see my wishlist ?',
'PRO_USE_INDEX' 		=> 'Use index number fields for custom media ID\'s',
'PRO_SEEN_LIST' 		=> 'Keep track of movies that I\'ve seen',
'PRO_PLAYOPTION' 		=> 'Use client playback options',
'PRO_NFO' 				=> 'Bruge NFO arkiverer?',

/* User Settings */
'SE_PLAYER' 			=> 'Bruger Indstillinger',
'SE_OWNFEED' 			=> 'Vis mine tilf�jelser',
'SE_CUSTOM' 			=> 'Forside indstillinger',
'SE_SHOWSTAT' 			=> 'Vis statistik',
'SE_SHOWSIDE' 			=> 'Vis nye film i sidebar',
'SE_SELECTRSS' 			=> 'V�lg RSS Side',
'SE_PAGELOOK' 			=> 'Web layout',
'SE_PAGEMODE' 			=> 'Select default template:',
'SE_UPDATED'			=> 'User information updated',
'SE_UPDATE_FAILED'		=> 'Failed to update',

/* Search */
'SEARCH' 				=> 'S�g efter',
'SEARCH_TITLE' 			=> 'Titel',
'SEARCH_ACTOR' 			=> 'Skuespiller',
'SEARCH_DIRECTOR'		=> 'Instrukt�r',
'SEARCH_RESULTS' 		=> 'S�geresultater',
'SEARCH_EXTENDED' 		=> 'Avanceret s�gning',
'SEARCH_NORESULT' 		=> 'Din s�gning var forg�ves',

/* Movie categories*/
'CAT_ACTION' 			=> 'Action',
'CAT_ADULT' 			=> 'Porno',
'CAT_ADVENTURE' 		=> 'Eventyr',
'CAT_ANIMATION' 		=> 'Animation',
'CAT_ANIME' 			=> 'Anime / Manga',
'CAT_COMEDY' 			=> 'Komedie',
'CAT_CRIME' 			=> 'Krimi',
'CAT_DOCUMENTARY' 		=> 'Dokumentar',
'CAT_DRAMA' 			=> 'Drama',
'CAT_FAMILY' 			=> 'Familie',
'CAT_FANTASY' 			=> 'Fantasy',
'CAT_FILMNOIR' 			=> 'Film Noir',
'CAT_HORROR' 			=> 'Gyser',
'CAT_JAMESBOND' 		=> 'James Bond',
'CAT_MUSICVIDEO' 		=> 'Musik-Video',
'CAT_MUSICAL' 			=> 'Musical',
'CAT_MYSTERY' 			=> 'Mysterie',
'CAT_ROMANCE' 			=> 'Romance',
'CAT_SCIFI' 			=> 'Sci-Fi',
'CAT_SHORT' 			=> 'Kortfilm',
'CAT_THRILLER' 			=> 'Thriller',
'CAT_TVSHOWS' 			=> 'TV Shows',
'CAT_WAR' 				=> 'Krigsfilm',
'CAT_WESTERN' 			=> 'Western',
'CAT_XRATED' 			=> 'X-Rated',

/* Movie Listings */
'M_MOVIE' 				=> 'Filmen',
'M_ACTORS' 				=> 'Skuespillere',
'M_CATEGORY' 			=> 'Kategori',
'M_YEAR' 				=> 'Produktions�r',
'M_COPIES' 				=> 'Film',
'M_FROM' 				=> 'Fra',
'M_TITLE' 				=> 'Titel',
'M_ALTTITLE' 			=> 'Alt titel',
'M_GRADE' 				=> 'Rating',
'M_DIRECTOR' 			=> 'Instrukt�r',
'M_COUNTRY'				=> 'Land',
'M_RUNTIME' 			=> 'Filmens l�ngde',
'M_MINUTES' 			=> 'minutter',
'M_PLOT' 				=> 'Plot beskrivelse',
'M_NOPLOT'				=> 'Ingen plot-beskrivelse er tilg�ngelig',
'M_COVERS' 				=> 'CD Covers',
'M_AVAILABLE' 			=> 'Tilg�ngelige film',
'M_MEDIA' 				=> 'Medie',
'M_NUM' 				=> 'Antal DVDer',
'M_DATE' 				=> 'Lagt i databasen',
'M_OWNER' 				=> 'Ejer',
'M_NOACTORS' 			=> 'Ingen skuespilleroversigt tilg�ngelig',
'M_INFO' 				=> 'Information om filmen',
'M_DETAILS' 			=> 'Detaljer p� min kopi',
'M_MEDIATYPE'			=> 'Medietype',
'M_COMMENT' 			=> 'Kommentar',
'M_PRIVATE' 			=> 'Mark�r som privat?',
'M_SCREENSHOTS' 		=> 'Screenshots',
'M_NOSCREENS' 			=> 'Der er ingen tilg�ngelige screenshots',
'M_SHOW' 				=> 'Vis',
'M_HIDE' 				=> 'Skjul',
'M_CHANGE' 				=> 'Rediger information',
'M_NOCOVERS' 			=> 'Der er ingen tilg�ngelige covers',
'M_BYCAT' 				=> 'Titler efter kategori',
'M_CURRCAT' 			=> 'Nuv�rende kategori',
'M_TEXTVIEW' 			=> 'Vis tekst',
'M_IMAGEVIEW' 			=> 'Vis billeder',
'M_MINEONLY' 			=> 'Vis kun mine film',
'M_SIMILAR' 			=> 'Lignende film',
'M_MEDIAINDEX'			=> 'Media Index',

/* IMDB */
'I_DETAILS' 			=> 'IMDBs oversigt',
'I_PLOT' 				=> 'Plotbeskrivelse',
'I_GALLERY' 			=> 'Fotogalleri',
'I_TRAILERS' 			=> 'Trailers',
'I_LINKS' 				=> 'IMDB Links',
'I_NOT' 				=> 'Der er ingen tilg�ngelig IMDB-information',

/* DVD Specific */
'DVD_REGION'			=> 'Region',
'DVD_FORMAT'			=> 'Format',
'DVD_ASPECT'			=> 'Aspect ratio',
'DVD_AUDIO'				=> 'Audio',
'DVD_SUBTITLES'			=> 'Subtitles',

/* My Movies */
'MY_EXPORT' 			=> 'Eksporter data',
'MY_EXCEL' 				=> 'Eksporter som Excel',
'MY_XML' 				=> 'Eksporter som XML',
'MY_XMLTHUMBS' 			=> 'Eksporter thumbnails som XML',
'MY_ACTIONS' 			=> 'Mine handlinger',
'MY_JOIN' 				=> 'Disc join',
'MY_JOINMOVIES' 		=> 'Disc join movies',
'MY_JOINSUSER' 			=> 'V�lg bruger',
'MY_JOINSMEDIA' 		=> 'V�lg medietype',
'MY_JOINSCAT' 			=> 'V�lg kategori',
'MY_JOINSTYPE' 			=> 'V�lg handling',
'MY_JOINSHOW' 			=> 'Vis resultater',
'MY_NORESULTS' 			=> 'Foresp�rgslen gav ingen resultater',
'MY_TEXTALL'			=> 'Printervenlig version (Text)',
'MY_PWALL' 				=> 'Printervenlig version (Alt)',
'MY_PWMOVIES' 			=> 'Printervenlig version (Film)',
'MY_PWTV' 				=> 'Printervenlig version (Tv Shows)',
'MY_PWBLUE' 			=> 'Printervenlig version (Porno)',
'MY_J1' 				=> 'Film jeg har som brugeren ikke har',
'MY_J2' 				=> 'Film brugeren har som jeg ikke har',
'MY_J3' 				=> 'Film som b�de jeg og brugeren har',
'MY_OVERVIEW' 			=> 'Overblik over samling',
'MY_INFO' 				=> 'P� denne side kan du finde ud af alt om mine film.
						Til h�jre finder du en oversigt over dine mulige handlinger.
						Du kan ogs� eksportere dine data som XML og udprinte den eller flytte hele din database til en anden lokation.',
'MY_KEYS' 				=> 'Edit Custom ID\'s',
'MY_SEENLIST' 			=> 'Rediger har set-liste',
'MY_HELPPICKER' 		=> 'V�lg en film',
'MY_HELPPICKERINFO' 	=> 'Ved du ikke hvad du vil se i aften?<br/>Lad databasen hj�lpe dig med at finde en film.<br/>
						Du kan selv definere indenfor hvilke rammer databasen s�ger.',
'MY_FIND' 				=> 'Find en film',
'MY_NOTSEEN' 			=> 'Foresl� kun film jeg ikke har set',
'MY_FRIENDS' 			=> 'Mine venner som l�ner CDer',

/* Manager window */
'MAN_BASIC' 			=> 'Basic information',
'MAN_IMDB' 				=> 'IMDB info',
'MAN_EMPIRE' 			=> 'DVDEmpire info',
'MAN_COPY' 				=> 'Min film',
'MAN_COPIES' 			=> 'Mine film',
'MAN_NOCOPY' 			=> 'Du har ikke denne film',
'MAN_1COPY' 			=> 'Kopi',
'MAN_ADDACT' 			=> 'Tilf�j skuespillere',
'MAN_ADDTODB' 			=> 'Tilf�je nye skuespillere til DB',
'MAN_SAVETODB' 			=> 'Gem til DB',
'MAN_SAVETODBNCD' 		=> 'Gem til DB og film',
'MAN_INDB' 				=> 'Skuespillere i database',
'MAN_SEL' 				=> 'V�lg af skuespillere',
'MAN_STARS' 			=> 'Stjerner',
'MAN_BROWSE' 			=> 'S�g efter fil',

/* Add movies */
'ADD_INFO' 				=> 'V�lg m�de du vil tilf�je din film p�',
'ADD_IMDB' 				=> 'Hent fra Internet Movie Database',
'ADD_IMDBTITLE' 		=> 'Skriv n�gleord du vil s�ge p�',
'ADD_MANUAL' 			=> 'Skriv den ind manuelt',
'ADD_LISTED' 			=> 'Tilf�je film der allerede i databasen',
'ADD_XML' 				=> 'Tilf�je film fra eksporterede XML arkiverer',
'ADD_XMLFILE' 			=> 'V�lg den XML arkiverer der skal importeres',
'ADD_XMLNOTE' 			=> '(HUSK at det er kun XML arkiverer der kan blive eksporteret fra ans�gning fra andre VCD-db
						s� kan blive tilf�je dem her. Du kan eksporteret dine film fra "Mine film"
						sektion. You should avoid manual editing of the exported XML files. ) ',
'ADD_MAXFILESIZE' 		=> 'Max fil st�rrelse',
'ADD_DVDEMPIRE' 		=> 'Fetch from Adult DVD Empire (X-rated films)',
'ADD_LISTEDSTEP1' 		=> 'Step 1<br/>Select the titles that you want to add to your list.<br/>You can select media
					    type in next step.',
'ADD_LISTEDSTEP2' 		=> 'Step 2.<br/>Select the appropriate media type.',
'ADD_INDB' 				=> 'Movies in VCD-DB',
'ADD_SELECTED' 			=> 'Selected titles',
'ADD_INFOLIST' 			=> 'Double click on title to select title or use the arrows.<br/>You can use the keyboard to
						quickly find titles.',
'ADD_NOTITLES' 			=> 'No other user has added movies to the VCD-db',

/* Add from XML */
'XML_CONFIRM' 			=> 'Confirm XML upload',
'XML_CONTAINS' 			=> 'XML file contains %d movies.',
'XML_INFO1' 			=> 'Press confirm to process the movies and save to the database.<br/>
						Or press cancel to bail out. ',
'XML_INFO2' 			=> 'If you want to include the thumbnails (posters) with the movies that you are about to
						import in your XML file, you <b>MUST</b> have the thumbnails XML file availeble now!.<br/>
						Posters cannot be imported after you have finished importing you movies from the current XML file.
						If you already have the thumbnails XML file available check the field below and in next step
						after the import of your movies in the list below, you will be asked to submit you thumbnails XML
						file aswell for processing. ',
'XML_THUMBNAILS' 		=> 'Insert thumbnails from my thumbnails XML file ',
'XML_LIST' 				=> 'Full list of movies found in XML file.',
'XML_ERROR' 			=> 'No titles found in XML file.<br/>File could be damaged or just plain empty.
		   				<br/>Make sure that you are using XML file that was exported from the VCD-db..',
'XML_RESULTS' 			=> 'XML upload results.',
'XML_RESULTS2' 			=> 'Here are the results on your XML import.<br/>Total %d movies were imported.',

/* Add from DVD Empire */
'EM_INFO' 				=> 'Information from AdultDVDEmpire.com ....',
'EM_DESC' 				=> 'DVDEmpire description',
'EM_SUBCAT' 			=> 'Adult categories',
'EM_DETAILS' 			=> 'Adultdvdempire.com details',
'EM_STARS' 				=> 'Pornstars',
'EM_NOTICE' 			=> 'Actors marked red are currently not in the VCD-DB.
						But you can check their names and they will be automatically added to the VCD-db
					    and associated with this movie.',
'EM_FETCH' 				=> 'Fetch Also',

/* Loan System */
'LOAN_MOVIES' 			=> 'Movies to borrow',
'LOAN_TO' 				=> 'Borrow movies to',
'LOAN_ADDUSERS' 		=> 'Add some users to borrow to continue',
'LOAN_NEWUSER' 			=> 'New borrower',
'LOAN_REGISTERUSER' 	=> 'Add new borrower',
'LOAN_NAME' 			=> 'Name',
'LOAN_SELECT' 			=> 'Select borrower',
'LOAN_MOVIELOANS' 		=> 'Borrowed movies ...',
'LOAN_REMINDER' 		=> 'Send reminder',
'LOAN_HISTORY' 			=> 'Loan history',
'LOAN_HISTORY2' 		=> 'See loan history',
'LOAN_SINCE' 			=> 'Since',
'LOAN_TIME' 			=> 'Time since',
'LOAN_RETURN' 			=> 'Return copy',
'LOAN_SUCCESS' 			=> 'Movies successfully loaned',
'LOAN_OUT' 				=> 'Not returned',
'LOAN_DATEIN' 			=> 'Date in',
'LOAN_DATEOUT' 			=> 'Date out',
'LOAN_PERIOD' 			=> 'Loan period',
'LOAN_BACK' 			=> 'Back to loan index',
'LOAN_DAY' 				=> 'day',
'LOAN_DAYS' 			=> 'days',
'LOAN_TODAY' 			=> 'from today',

/* RSS */
'RSS' 					=> 'RSS tilf�jelser',
'RSS_TITLE' 			=> 'RSS tilf�jelser fra mine venners VCD-DB hjemme-sider',
'RSS_SITE' 				=> 'RSS Side tilf�jelser',
'RSS_USER' 				=> 'RSS Bruger tilf�jelser',
'RSS_VIEW' 				=> 'Vis RSS tilf�jelser',
'RSS_ADD' 				=> 'Tilf�j ny brugerliste',
'RSS_NOTE' 				=> 'Skriv den <strong>n�jagtige url</strong> p� din vens VCD database.<br/>
						Hvis RSS er sl�et til p� din vens side s� kan du udv�lge
						de film du er intresseret i og vise dem p� din side.',
'RSS_FETCH' 			=> 'Fetch RSS List',
'RSS_NONE' 				=> 'Ingen RSS er blevet tilf�jet.',
'RSS_FOUND' 			=> 'The following RSS feeds were found, select the feeds you want to add:',
'RSS_NOTFOUND' 			=> 'No feeds found at location',

/* Wishlist */
'W_ADD' 				=> 'Tilf�j til min �nskeliste',
'W_ONLIST' 				=> 'Er p� din �nskeliste',
'W_EMPTY' 				=> 'Din �nskeliste er tom',
'W_OWN' 				=> 'Du har denne film',
'W_NOTOWN' 				=> 'Du har ikke denne film',

/* Comments */
'C_COMMENTS' 			=> 'Comments',
'C_ADD' 				=> 'Post new comment',
'C_NONE' 				=> 'No comments have been posted',
'C_TYPE' 				=> 'Type in your new comment',
'C_YOUR' 				=> 'Your comment',
'C_POST' 				=> 'Post comment',
'C_ERROR' 				=> 'You have be logged in to post a comment',

/* Pornstars */
'P_NAME' 				=> 'Navn',
'P_WEB' 				=> 'Hjemmeside',
'P_MOVIECOUNT' 			=> 'Movie count',

/* Seen List */
'S_SEENIT' 				=> 'I\'ve seen it',
'S_NOTSEENIT' 			=> 'I\'ve not seen it',
'S_SEENITCLICK' 		=> 'Click to mark seen',
'S_NOTSEENITCLICK' 		=> 'Click to mark unseen',

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
'MAIL_RETURNMOVIES1' 	=> '%s, just wanted to remind you to return my movies.\n
						You still have the following movies :\n\n',
'MAIL_RETURNMOVIES2'	=> 'Please return the discs as soon as possible\n Cheers %s \n\n
						nb. this is an automated e-mail from the VCD-db system (http://vcddb.konni.com)',
'MAIL_NOTIFY' 			=> '<strong>New movie has been added to VCD-db</strong><br/>
						 Click <a href="%s/?page=cd&vcd_id=%s">here</a> to see more ..
						 <p>nb. this is an automated e-mail from the VCD-db (vcddb.konni.com)</p>',
'MAIL_REGISTER' 		=> '%s, registration to VCD-db was successful.\n\nYour username is %s and your password is
						%s.\n\nYou can always change your password after you have logged in.\n
						Click <a href="%s" target="_new">here</a> to goto the VCD-db website.',

/* Player */
'PLAYER' 				=> 'Afspiller',
'PLAYER_PATH' 			=> 'Path',
'PLAYER_PARAM' 			=> 'Parameters',
'PLAYER_NOTE' 			=> 'Skriv den fulde path til din film-afspiller p� din harddisk.
						Din afspiller skal v�re kompatible til tage parameters og kommando linjer ligesom til
						BSPlayer til Win32 og MPlayer til Linux.<br/>du kan downloade BSPlayer gratis
						<a href="http://www.bsplayer.org" target="_new"><b>HER</b></a>
						og MPlayer til linux <a href="http://www.MPlayerHQ.hu" target="_new"><b>HER</b></a>.',


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
'X_CONTAINS' 			=> 'indhold',
'X_GRADE' 				=> 'IMDB karakter h�jere end',
'X_ANY' 				=> 'Alle',
'X_TRYAGAIN' 			=> 'Pr�v igen',
'X_PROCEED' 			=> 'Proceed',
'X_SELECT' 				=> 'V�lge',
'X_CONFIRM' 			=> 'Bekr�ft',
'X_CANCEL' 				=> 'Annuller',
'X_ATTENTION' 			=> 'Opm�rksomhed!',
'X_STATUS' 				=> 'Status',
'X_SUCCESS' 			=> 'Succes',
'X_FAILURE' 			=> 'Fejl',
'X_YES' 				=> 'Ja',
'X_NO' 					=> 'Nej',
'X_SHOWMORE' 			=> 'Vis mere',
'X_SHOWLESS' 			=> 'Vis kort',
'X_NEW' 				=> 'Ny',
'X_CHANGE' 				=> 'skift',
'X_DELETE' 				=> 'slet',
'X_UPDATE' 				=> 'Update',
'X_SAVEANDCLOSE' 		=> 'Gem og luk',
'X_CLOSE' 				=> 'Luk',
'X_EDIT' 				=> 'Ret',
'X_RESULTS' 			=> 'Results',
'X_LATESTMOVIES' 		=> 'sidste film',
'X_LATESTTV' 			=> 'sidste TV show',
'X_LATESTBLUE' 			=> 'sidste X-rated',
'X_MOVIES' 				=> 'filmene',
'X_NOCATS' 				=> 'Ingen film er blivet tilf�jet.',
'X_NOUSERS' 			=> 'Ingen aktive bruger',
'X_KEY' 				=> 'N�gle',
'X_SAVENEXT' 			=> 'Gem og ret den n�ste',
'X_SAVE' 				=> 'Gem',
'X_SEEN' 				=> 'Set',
'X_FOOTER'				=> 'Page Loaded in %s sec. (<i>%d Queries</i>) &nbsp; Copyright (c)',
'X_FOOTER_LINK'			=> 'Check out the official VCD-db website'


);


?>