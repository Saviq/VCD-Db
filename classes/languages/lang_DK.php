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
      Danish language file
      Initial version translated by Gundelach
   */



$_ = array(


/* Language Identifier */
'LANG_TYPE' 			=> 'DK',
'LANG_NAME'				=> 'Dansk',
'LANG_CHARSET'			=> 'iso-8859-1',

/* Menu system */
'MENU_MINE' 			=> 'Min menu',
'MENU_SETTINGS' 		=> 'Mine indstillinger',
'MENU_MOVIES' 			=> 'Mine film',
'MENU_ADDMOVIE' 		=> 'Tilføj ny film',
'MENU_LOANSYSTEM' 		=> 'Udlån',
'MENU_WISHLIST' 		=> 'Min ønskeliste',
'MENU_CATEGORIES' 		=> 'Filmkategorier',
'MENU_RSS' 				=> 'Mine Rss Feeds',
'MENU_CONTROLPANEL' 	=> 'Kontrolpanel',
'MENU_REGISTER' 		=> 'Registrer',
'MENU_LOGOUT' 			=> 'Log ud',
'MENU_SUBMIT' 			=> 'Send',
'MENU_TOPUSERS' 		=> 'Topbrugere',
'MENU_WISHLISTPUBLIC' 	=> 'Andres ønskelister',

/* Login */
'LOGIN' 				=> 'Login',
'LOGIN_USERNAME' 		=> 'Brugernavn',
'LOGIN_PASSWORD' 		=> 'Password',
'LOGIN_REMEMBER' 		=> 'Husk mine loginfo.',
'LOGIN_INFO' 			=> 'Lad dette felt være tomt hvis du <b>ikke</b> vil ændre dit password',

/* Register */
'REGISTER_TITLE' 		=> 'Oprettelse',
'REGISTER_FULLNAME' 	=> 'Fulde navn',
'REGISTER_EMAIL' 		=> 'E-mail',
'REGISTER_AGAIN' 		=> 'Password igen',
'REGISTER_DISABLED' 	=> 'Beklager, administratoren har lukket for tilmelding indtil videre',
'REGISTER_OK' 			=> 'Oprettelsen er gennemført, du kan nu logge ind i databasen.',

/* User Properties */
'PRO_NOTIFY' 			=> 'Send mig en e-mail når en ny film bliver tilføjet.',
'PRO_SHOW_ADULT' 		=> 'Vis pornofilm i oversigten',
'PRO_RSS' 				=> 'Tillad RSS tilføjelser fra min filmliste?',
'PRO_WISHLIST' 			=> 'Tillad at andre ser min ønskeliste ?',
'PRO_USE_INDEX' 		=> 'Brug index numre felter for peresonlige media ID\'er',
'PRO_SEEN_LIST' 		=> 'Hold styr på de film jeg har set',
'PRO_PLAYOPTION' 		=> 'Brug klient playback options',
'PRO_NFO' 				=> 'Bruge NFO arkiverer?',

/* User Settings */
'SE_PLAYER' 			=> 'Bruger Indstillinger',
'SE_OWNFEED' 			=> 'Vis mine tilføjelser',
'SE_CUSTOM' 			=> 'Forside indstillinger',
'SE_SHOWSTAT' 			=> 'Vis statistik',
'SE_SHOWSIDE' 			=> 'Vis nye film i sidebar',
'SE_SELECTRSS' 			=> 'Vælg RSS Side',
'SE_PAGELOOK' 			=> 'Web layout',
'SE_PAGEMODE' 			=> 'Vælg udsende:',
'SE_UPDATED'			=> 'User information updated',
'SE_UPDATE_FAILED'		=> 'Failed to update',

/* Search */
'SEARCH' 				=> 'Søg efter',
'SEARCH_TITLE' 			=> 'Titel',
'SEARCH_ACTOR' 			=> 'Skuespiller',
'SEARCH_DIRECTOR'		=> 'Instruktør',
'SEARCH_RESULTS' 		=> 'Søgeresultater',
'SEARCH_EXTENDED' 		=> 'Avanceret søgning',
'SEARCH_NORESULT' 		=> 'Din søgning var forgæves',

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
'M_YEAR' 				=> 'Produktionsår',
'M_COPIES' 				=> 'Film',
'M_FROM' 				=> 'Fra',
'M_TITLE' 				=> 'Titel',
'M_ALTTITLE' 			=> 'Alt titel',
'M_GRADE' 				=> 'Rating',
'M_DIRECTOR' 			=> 'Instruktør',
'M_COUNTRY'				=> 'Land',
'M_RUNTIME' 			=> 'Filmens længde',
'M_MINUTES' 			=> 'minutter',
'M_PLOT' 				=> 'Plot beskrivelse',
'M_NOPLOT'				=> 'Ingen plot-beskrivelse er tilgængelig',
'M_COVERS' 				=> 'CD Covers',
'M_AVAILABLE' 			=> 'Tilgængelige film',
'M_MEDIA' 				=> 'Medie',
'M_NUM' 				=> 'Antal DVDer',
'M_DATE' 				=> 'Lagt i databasen',
'M_OWNER' 				=> 'Ejer',
'M_NOACTORS' 			=> 'Ingen skuespilleroversigt tilgængelig',
'M_INFO' 				=> 'Information om filmen',
'M_DETAILS' 			=> 'Detaljer på min kopi',
'M_MEDIATYPE'			=> 'Medietype',
'M_COMMENT' 			=> 'Kommentar',
'M_PRIVATE' 			=> 'Markér som privat?',
'M_SCREENSHOTS' 		=> 'Screenshots',
'M_NOSCREENS' 			=> 'Der er ingen tilgængelige screenshots',
'M_SHOW' 				=> 'Vis',
'M_HIDE' 				=> 'Skjul',
'M_CHANGE' 				=> 'Rediger information',
'M_NOCOVERS' 			=> 'Der er ingen tilgængelige covers',
'M_BYCAT' 				=> 'Titler efter kategori',
'M_CURRCAT' 			=> 'Nuværende kategori',
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
'I_NOT' 				=> 'Der er ingen tilgængelig IMDB-information',

/* DVD Specific */
'DVD_REGION'			=> 'Region',
'DVD_FORMAT'			=> 'Format',
'DVD_ASPECT'			=> 'Aspect ratio',
'DVD_AUDIO'				=> 'Audio',
'DVD_SUBTITLES'			=> 'Unddertekster',

/* My Movies */
'MY_EXPORT' 			=> 'Eksporter data',
'MY_EXCEL' 				=> 'Eksporter som Excel',
'MY_XML' 				=> 'Eksporter som XML',
'MY_XMLTHUMBS' 			=> 'Eksporter thumbnails som XML',
'MY_ACTIONS' 			=> 'Mine handlinger',
'MY_JOIN' 				=> 'Disc join',
'MY_JOINMOVIES' 		=> 'Disc join movies',
'MY_JOINSUSER' 			=> 'Vælg bruger',
'MY_JOINSMEDIA' 		=> 'Vælg medietype',
'MY_JOINSCAT' 			=> 'Vælg kategori',
'MY_JOINSTYPE' 			=> 'Vælg handling',
'MY_JOINSHOW' 			=> 'Vis resultater',
'MY_NORESULTS' 			=> 'Forespørgslen gav ingen resultater',
'MY_TEXTALL'			=> 'Printervenlig version (Text)',
'MY_PWALL' 				=> 'Printervenlig version (Alt)',
'MY_PWMOVIES' 			=> 'Printervenlig version (Film)',
'MY_PWTV' 				=> 'Printervenlig version (Tv Shows)',
'MY_PWBLUE' 			=> 'Printervenlig version (Porno)',
'MY_J1' 				=> 'Film jeg har, som brugeren ikke har',
'MY_J2' 				=> 'Film brugeren har, som jeg ikke har',
'MY_J3' 				=> 'Film som både jeg og brugeren har',
'MY_OVERVIEW' 			=> 'Overblik over samling',
'MY_INFO' 				=> 'På denne side kan du finde ud af alt om mine film.
						Til højre finder du en oversigt over dine mulige handlinger.
						Du kan også eksportere dine data til Excel for udskrift, som backup eller for at flytte hele din database til en anden VCD-db.',
'MY_KEYS' 				=> 'Rediger personlige IDer',
'MY_SEENLIST' 			=> 'Rediger har set-liste',
'MY_HELPPICKER' 		=> 'Vælg en film',
'MY_HELPPICKERINFO' 	=> 'Ved du ikke hvad du vil se i aften?<br/>Lad databasen hjælpe dig med at finde en film.<br/>
						Du kan selv definere indenfor hvilke rammer databasen søger.',
'MY_FIND' 				=> 'Find en film',
'MY_NOTSEEN' 			=> 'Foreslå kun film jeg ikke har set',
'MY_FRIENDS' 			=> 'Mine venner som låner CDer',

/* Manager window */
'MAN_BASIC' 			=> 'Basic information',
'MAN_IMDB' 				=> 'IMDB info',
'MAN_EMPIRE' 			=> 'DVDEmpire info',
'MAN_COPY' 				=> 'Min film',
'MAN_COPIES' 			=> 'Mine film',
'MAN_NOCOPY' 			=> 'Du har ikke denne film',
'MAN_1COPY' 			=> 'Kopi',
'MAN_ADDACT' 			=> 'Tilføj skuespillere',
'MAN_ADDTODB' 			=> 'Tilføje nye skuespillere til DB',
'MAN_SAVETODB' 			=> 'Gem til DB',
'MAN_SAVETODBNCD' 		=> 'Gem til DB og film',
'MAN_INDB' 				=> 'Skuespillere i database',
'MAN_SEL' 				=> 'Vælg af skuespillere',
'MAN_STARS' 			=> 'Stjerner',
'MAN_BROWSE' 			=> 'Søg efter fil',
'MAN_ADDMEDIA'			=> 'Add...',

/* Add movies */
'ADD_INFO' 				=> 'Vælg måde du vil tilføje din film på',
'ADD_IMDB' 				=> 'Hent fra Internet Movie Database',
'ADD_IMDBTITLE' 		=> 'Skriv nøgleord du vil søge på',
'ADD_MANUAL' 			=> 'Skriv den ind manuelt',
'ADD_LISTED' 			=> 'Tilføje film der allerede i databasen',
'ADD_XML' 				=> 'Tilføje film fra eksporterede XML arkiverer',
'ADD_XMLFILE' 			=> 'Vælg den XML arkiverer der skal importeres',
'ADD_XMLNOTE' 			=> '(HUSK at det er kun XML arkiverer, der kan blive eksporteret fra ansøgning fra andre VCD-db
						så kan blive tilføje dem her. Du kan eksporteret dine film fra "Mine film" sektion. Du bør undgå, at redigere i de eksporterede XML filer. ) ',
'ADD_MAXFILESIZE' 		=> 'Max fil størrelse',
'ADD_DVDEMPIRE' 		=> 'Hent fra Adult DVD Empire (X-rated films)',
'ADD_LISTEDSTEP1' 		=> 'Trin 1<br/>Vælg de titler du vil tilføje din liste.<br/>Du kan vælge media typen i næste trin.',
'ADD_LISTEDSTEP2' 		=> 'Trin 2.<br/>Vælg den rette media type.',
'ADD_INDB' 				=> 'Film i VCD-DB',
'ADD_SELECTED' 			=> 'Valgte titler',
'ADD_INFOLIST' 			=> 'Dobbelt klik på en titel for at vælge den eller brug piletasterne.<br/>Du kan bruge tastaturet til hutrigt at finde en titel.',
'ADD_NOTITLES' 			=> 'Ingen andre brugere har tilføjet film til VCD-db',

/* Add from XML */
'XML_CONFIRM' 			=> 'Bekræft XML upload',
'XML_CONTAINS' 			=> 'XML fil indeholder %d film.',
'XML_INFO1' 			=> 'Tryk bekræft for at behandle filmen og gemme den i databasen.<br/>Eller tryk fortryd for at stoppe. ',
'XML_INFO2' 			=> 'Hvis du ønsker at inkludere thumbnails (posters) med filmene du er ved at	importere i din XML fil, <b>SKALT</b> du have thumbnails XML fil tilgængelig nu!.<br/>
						Posters kan ikke importeres efter du har gennemført importen af film fra denne XML fil.
						Hvis du allerede har thumbnails XML filen klar afmærk feltet herunder, og i efter importen af dine film vil du blive spurgt efter din thumbnails XML fil. ',
'XML_THUMBNAILS' 		=> 'Indsæt thumbnails fra min thumbnails XML fil ',
'XML_LIST' 				=> 'Komplet liste over film fundet i XML fil.',
'XML_ERROR' 			=> 'Ingen titler fundet i XML fil.<br/>Filen kan være tom eller ødelagt. <br/>Check at du bruger en XML fil der er eksporteret fra VCD-db..',
'XML_RESULTS' 			=> 'XML upload resultat.',
'XML_RESULTS2' 			=> 'Her er resultatet af din XML import.<br/>Totalt %d film blev importeret.',

/* Add from DVD Empire */
'EM_INFO' 				=> 'Information fra AdultDVDEmpire.com ....',
'EM_DESC' 				=> 'DVDEmpire beskrivelse',
'EM_SUBCAT' 			=> 'Voksen kategorier',
'EM_DETAILS' 			=> 'Adultdvdempire.com detaljer',
'EM_STARS' 				=> 'Pornstjerner',
'EM_NOTICE' 			=> 'Skuespillere markeret med rødt findes p.t. ikke i VCD-DB. Du kan markere deres navn og de tilføjes automatisk til VCD-db og kædes til denne film.',
'EM_FETCH' 				=> 'Hent også',

/* Loan System */
'LOAN_MOVIES' 			=> 'Film til udlån',
'LOAN_TO' 				=> 'Lån fil til',
'LOAN_ADDUSERS' 		=> 'Tilføj nogle lånere for at kunne fortsætte',
'LOAN_NEWUSER' 			=> 'Ny låner',
'LOAN_REGISTERUSER' 	=> 'Tilføj ny låner',
'LOAN_NAME' 			=> 'Navn',
'LOAN_SELECT' 			=> 'Vælg låner',
'LOAN_MOVIELOANS' 		=> 'Udlånte film ...',
'LOAN_REMINDER' 		=> 'Send påmindelse',
'LOAN_HISTORY' 			=> 'Udlåns historik',
'LOAN_HISTORY2' 		=> 'Se udlåns historik',
'LOAN_SINCE' 			=> 'Siden',
'LOAN_TIME' 			=> 'Tid siden',
'LOAN_RETURN' 			=> 'Retur kopi',
'LOAN_SUCCESS' 			=> 'Film udlån genneført',
'LOAN_OUT' 				=> 'Ikke returneret',
'LOAN_DATEIN' 			=> 'Dato ind',
'LOAN_DATEOUT' 			=> 'Dato ud',
'LOAN_PERIOD' 			=> 'Låne periode',
'LOAN_BACK' 			=> 'Tilbage til udlåns index',
'LOAN_DAY' 				=> 'dag',
'LOAN_DAYS' 			=> 'dage',
'LOAN_TODAY' 			=> 'fra i dag',

/* RSS */
'RSS' 					=> 'RSS tilføjelser',
'RSS_TITLE' 			=> 'RSS tilføjelser fra mine venners VCD-DB hjemme-sider',
'RSS_SITE' 				=> 'RSS Side tilføjelser',
'RSS_USER' 				=> 'RSS Bruger tilføjelser',
'RSS_VIEW' 				=> 'Vis RSS tilføjelser',
'RSS_ADD' 				=> 'Tilføj ny brugerliste',
'RSS_NOTE' 				=> 'Skriv den <strong>nøjagtige url</strong> på din vens VCD database.<br/>Hvis RSS er slået til på din vens side så kan du udvælge
						de film du er intresseret i og vise dem på din side.',
'RSS_FETCH' 			=> 'Hent RSS liste',
'RSS_NONE' 				=> 'Ingen RSS er blevet tilføjet.',
'RSS_FOUND' 			=> 'Følgende RSS feeds blev fundet. Vælg de feeds du vil tilføje:',
'RSS_NOTFOUND' 			=> 'Ingen feeds blev fundet på denne lokation',

/* Wishlist */
'W_ADD' 				=> 'Tilføj til min ønskeliste',
'W_ONLIST' 				=> 'Er på din ønskeliste',
'W_EMPTY' 				=> 'Din ønskeliste er tom',
'W_OWN' 				=> 'Du har denne film',
'W_NOTOWN' 				=> 'Du har ikke denne film',

/* Comments */
'C_COMMENTS' 			=> 'Kommentarer',
'C_ADD' 				=> 'Post ny kommentar',
'C_NONE' 				=> 'Ingen kommentarer er postet',
'C_TYPE' 				=> 'Skriv din nye kommentar',
'C_YOUR' 				=> 'Din kommentar',
'C_POST' 				=> 'Post kommentar',
'C_ERROR' 				=> 'Du skal være logget ind for at kunne poste en kommentar',

/* Pornstars */
'P_NAME' 				=> 'Navn',
'P_WEB' 				=> 'Hjemmeside',
'P_MOVIECOUNT' 			=> 'Film antal',

/* Seen List */
'S_SEENIT' 				=> 'Jeg har set den',
'S_NOTSEENIT' 			=> 'Jeg har ikke set den',
'S_SEENITCLICK' 		=> 'Klik her for at markere som set',
'S_NOTSEENITCLICK' 		=> 'Klik her for at markere som uset',

/* Statistics */
'STAT_TITLE'			=> 'Today\'s Report',
'STAT_TOP_MOVIES'		=> 'Movies in database',
'STAT_TOP_CATS'			=> 'Top categories',
'STAT_TOP_ACT'			=> 'Most active categories',
'STAT_TOP_COVERS'		=> 'Covers in database',
'STAT_TOTAL'			=> 'Total',
'STAT_TODAY'			=> 'Added today',
'STAT_WEEK'				=> 'Added in last 7 days',
'STAT_MONTH'			=> 'Added in last 30 days',

/* Mail messages */
'MAIL_RETURNTOPIC'		=> 'Udlån påmindelse',
'MAIL_RETURNMOVIES1' 	=> '%s, vil bare minde dig om, at du skal aflevere mine film.\n	Du har stadig følgende film:\n\n',
'MAIL_RETURNMOVIES2'	=> 'Venligst returnere skiverne så snart som muligt\n Hilsen %s \n\n NB: Dette er en e-mail der sendes automatisk fra VCD-db system (http://klixbuell.dk)',
'MAIL_NOTIFY' 			=> '<strong>Nye film er tilføjet VCD-db</strong><br/> Klik <a href="%s/?page=cd&vcd_id=%s">her</a> for at se mere ..
						 <p>NB: Dette er en e-mail der sendes automatisk fra VCD-db system (www.klixbuell.dk)</p>',
'MAIL_REGISTER' 		=> '%s, registreringen på VCD-db er gennemført.\n\nDit brugernavn er %s og dit password er
						%s.\n\nDu kan altid ændre dit password når du er logget på.\n
						Klik <a href="%s" target="_new">her</a> for at komme til VCD-db website.',

/* Player */
'PLAYER' 				=> 'Afspiller',
'PLAYER_PATH' 			=> 'Sti',
'PLAYER_PARAM' 			=> 'Parametere',
'PLAYER_NOTE' 			=> 'Skriv den fulde sti til din film-afspiller på din harddisk.
						Din afspiller skal være kompatible til tage parameters og kommando linjer som fx. BSPlayer til Win32 og MPlayer til Linux.<br/>
						Du kan downloade BSPlayer <a href="http://www.bsplayer.org" target="_new"><b>HER</b></a>
						og MPlayer til linux <a href="http://www.MPlayerHQ.hu" target="_new"><b>HER</b></a>.',


/* Metadata */
'META_MY'				=> 'Mine Metadata',
'META_NAME'				=> 'Navn',
'META_DESC'				=> 'Beskrivelse',
'META_TYPE'				=> 'Meta type',
'META_VALUE'			=> 'Meta værdi',
'META_NONE'				=> 'ingen Metadata findes.',

/* Ignore List */
'IGN_LIST'				=> 'Ignorer Liste',
'IGN_DESC'				=> 'Ignorer alle film fra følgende brugere:',

/* Misc keywords */
'X_CONTAINS' 			=> 'indhold',
'X_GRADE' 				=> 'IMDB karakter højere end',
'X_ANY' 				=> 'Alle',
'X_TRYAGAIN' 			=> 'Prøv igen',
'X_PROCEED' 			=> 'Kør',
'X_SELECT' 				=> 'Vælge',
'X_CONFIRM' 			=> 'Bekræft',
'X_CANCEL' 				=> 'Fortryd',
'X_ATTENTION' 			=> 'Opmærksomhed!',
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
'X_UPDATE' 				=> 'Opdater',
'X_SAVEANDCLOSE' 		=> 'Gem og luk',
'X_CLOSE' 				=> 'Luk',
'X_EDIT' 				=> 'Ret',
'X_RESULTS' 			=> 'Resulter',
'X_LATESTMOVIES' 		=> 'sidste film',
'X_LATESTTV' 			=> 'sidste TV show',
'X_LATESTBLUE' 			=> 'sidste X-rated',
'X_MOVIES' 				=> 'filmene',
'X_NOCATS' 				=> 'Ingen film er blivet tilføjet.',
'X_NOUSERS' 			=> 'Ingen aktive bruger',
'X_KEY' 				=> 'Nøgle',
'X_SAVENEXT' 			=> 'Gem og ret den næste',
'X_SAVE' 				=> 'Gem',
'X_SEEN' 				=> 'Set',
'X_TOGGLE'				=> 'Toggle preview',
'X_TOGGLE_ON'			=> 'on',
'X_TOGGLE_OFF'			=> 'off'

);


?>