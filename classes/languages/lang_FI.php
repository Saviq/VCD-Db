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
 * Finnish language file:
 * @author  Lari Majam�ki <jeeger at clankuha.com>
 * @package Language
 * @version $Id: lang_FI.php,v 1.0 2005/03/19
 *
 */

?>
<?
	/**
		Finnish language file

	*/



$_ = array(

/* Language Identifier */
'LANG_TYPE' 			=> 'FI',
'LANG_NAME' 			=> 'Suomi',
'LANG_CHARSET'		 	=> 'iso-8859-1',

/* Menu system */
'MENU_MINE' 			=> 'Oma valikko',
'MENU_SETTINGS' 		=> 'Omat asetukset',
'MENU_MOVIES' 			=> 'Omat elokuvat',
'MENU_ADDMOVIE' 		=> 'Lis�� uusi elokuva',
'MENU_LOANSYSTEM'		=> 'Lainausj�rjestelm�',
'MENU_WISHLIST' 		=> 'Oma toivelista',
'MENU_CATEGORIES' 		=> 'Elokuvakategoriat',
'MENU_RSS' 				=> 'Omat RSS sy�tteet',
'MENU_CONTROLPANEL' 	=> 'Ohjauspaneeli',
'MENU_REGISTER' 		=> 'Rekister�idy',
'MENU_LOGOUT' 			=> 'Kirjaudu ulos',
'MENU_SUBMIT'			=> 'L�het�',
'MENU_TOPUSERS'			=> 'K�ytt�j�tilastot',
'MENU_WISHLISTPUBLIC'	=> 'Toisten toivelistat',
'MENU_STATISTICS'		=> 'Tilastot',

/* Login */
'LOGIN' 				=> 'Kirjaudu',
'LOGIN_USERNAME' 		=> 'K�ytt�j�tunnus',
'LOGIN_PASSWORD' 		=> 'Salasana',
'LOGIN_REMEMBER' 		=> 'Muista minut',
'LOGIN_INFO' 			=> 'J�t� t�m� tyhj�ksi jos <b>et</b> halua vaihtaa salasanaa',

/* Register */
'REGISTER_TITLE'		=> 'Rekister�ityminen',
'REGISTER_FULLNAME' 	=> 'Koko nimi',
'REGISTER_EMAIL' 		=> 'S�hk�posti',
'REGISTER_AGAIN' 		=> 'Salasana uudelleen',
'REGISTER_DISABLED' 	=> 'T�ll� hetkell� rekister�inti ei ole mahdollista. Ota yhteys j�rjestelm�nvalvojaan.',
'REGISTER_OK' 			=> 'Rekister�inti onnistui, voit nyt kirjautua sis��n tietokantaan.',

/* User Properties */
'PRO_NOTIFY' 			=> 'L�het� minulle s�hk�postia kun tietokantaan on lis�tty uusi elokuva?',
'PRO_SHOW_ADULT' 		=> 'N�yt� aikuisviihdemateriaali sivustolla?',
'PRO_RSS' 				=> 'Salli RSS sy�te omasta elokuvalistastani?',
'PRO_WISHLIST'    		=> 'Salli muiden n�kev�n toivelistani',
'PRO_USE_INDEX'   		=> 'K�yt� indeksinumerokentti� tallenteiden omalle numeroinnille',
'PRO_SEEN_LIST'   		=> 'Pid� kirjaa elokuvista jotka olen n�hnyt',
'PRO_PLAYOPTION'   		=> 'K�yt� soittimen toistoasetuksia',
'PRO_NFO' 				=> 'Salli NFO tiedosto?',

/* User Settings */
'SE_PLAYER' 			=> 'Soittimen asetukset',
'SE_OWNFEED' 			=> 'Tarkastele omaa sy�tett�',
'SE_CUSTOM' 			=> 'Muokkaa omaa aloitussivua',
'SE_SHOWSTAT' 			=> 'N�yt� tilastot',
'SE_SHOWSIDE' 			=> 'N�yt� uudet elokuvat sivuvalikossa',
'SE_SELECTRSS' 			=> 'Valitse RSS sy�tteet',
'SE_PAGELOOK' 			=> 'Sivun layout',
'SE_PAGEMODE' 			=> 'Valitse sivun oletuspohja:',
'SE_UPDATED'			=> 'User information updated',
'SE_UPDATE_FAILED'		=> 'Failed to update',

/* Search */
'SEARCH' 				=> 'Etsi',
'SEARCH_TITLE' 			=> 'Elokuvan nimi',
'SEARCH_ACTOR' 			=> 'N�yttelij�',
'SEARCH_DIRECTOR' 		=> 'Ohjaaja',
'SEARCH_RESULTS' 		=> 'Haun tulokset',
'SEARCH_EXTENDED' 		=> 'Tarkennettu haku',
'SEARCH_NORESULT' 		=> 'Haussa ei l�ytynyt yht��n tulosta',

/* Movie categories*/
'CAT_ACTION' 			=> 'Toiminta',
'CAT_ADULT' 			=> 'Aikuisviihde',
'CAT_ADVENTURE' 		=> 'Seikkailu',
'CAT_ANIMATION' 		=> 'Animaatio',
'CAT_ANIME' 			=> 'Anime / Manga',
'CAT_COMEDY' 			=> 'Komedia',
'CAT_CRIME' 			=> 'Rikos',
'CAT_DOCUMENTARY' 		=> 'Dokumentti',
'CAT_DRAMA' 			=> 'Draama',
'CAT_FAMILY' 			=> 'Perhe',
'CAT_FANTASY' 			=> 'Fantasia',
'CAT_FILMNOIR' 			=> 'Film Noir',
'CAT_HORROR' 			=> 'Kauhu',
'CAT_JAMESBOND' 		=> 'James Bond',
'CAT_MUSICVIDEO' 		=> 'Musiikkivideo',
'CAT_MUSICAL' 			=> 'Musikaali',
'CAT_MYSTERY' 			=> 'Mysteeri',
'CAT_ROMANCE' 			=> 'Romantiikka',
'CAT_SCIFI' 			=> 'Sci-fi',
'CAT_SHORT' 			=> 'Lyhytelokuva',
'CAT_THRILLER' 			=> 'Trilleri',
'CAT_TVSHOWS' 			=> 'TV Show',
'CAT_WAR' 				=> 'Sota',
'CAT_WESTERN' 			=> 'Western',
'CAT_XRATED' 			=> 'X-Rated',

/* Movie Listings */
'M_MOVIE' 				=> 'Elokuva',
'M_ACTORS' 				=> 'N�yttelij�t',
'M_CATEGORY'			=> 'Kategoria',
'M_YEAR'				=> 'Tuotantovuosi',
'M_COPIES'				=> 'Kopiot',
'M_FROM' 				=> 'Tietoja',
'M_TITLE' 				=> 'Elokuvan nimi',
'M_ALTTITLE' 			=> 'Alt. nimi',
'M_GRADE'				=> 'Arvosana',
'M_DIRECTOR' 			=> 'Ohjaaja',
'M_COUNTRY'				=> 'Tuotantomaa',
'M_RUNTIME' 			=> 'Kesto',
'M_MINUTES'				=> 'minuuttia',
'M_PLOT' 				=> 'Juoni',
'M_NOPLOT' 				=> 'Juonta ei saatavilla',
'M_COVERS' 				=> 'DVD kannet',
'M_AVAILABLE' 			=> 'Saatavissa olevat kopiot',
'M_MEDIA'				=> 'Media',
'M_NUM' 				=> 'Lukum��r�',
'M_DATE' 				=> 'Lis�ysp�iv�m��r�',
'M_OWNER'				=> 'Omistaja',
'M_NOACTORS'			=> 'N�yttelij�listaa ei saatavilla',
'M_INFO'				=> 'Elokuvan tietoja',
'M_DETAILS'				=> 'Lis�tietoja kopiostani',
'M_MEDIATYPE'			=> 'Mediatyyppi',
'M_COMMENT'				=> 'Kommentti',
'M_PRIVATE'				=> 'Merkitse yksityiseksi',
'M_SCREENSHOTS'			=> 'Kuvakaappauksia',
'M_NOSCREENS'			=> 'Kuvakaappauksia ei saatavilla',
'M_SHOW'				=> 'N�yt�',
'M_HIDE'				=> 'Piilota',
'M_CHANGE'				=> 'Muuta tietoja',
'M_NOCOVERS'			=> 'DVD kansia ei saatavilla',
'M_BYCAT'				=> 'Elokuvan nimet kategoriassa',
'M_CURRCAT'				=> 'Valittu kategoria',
'M_TEXTVIEW'			=> 'Tekstin�kym�',
'M_IMAGEVIEW'			=> 'Kuvan�kym�',
'M_MINEONLY'			=> 'N�yt� vain omat elokuvat',
'M_SIMILAR'				=> 'Samankaltaiset elokuvat',
'M_MEDIAINDEX'			=> 'Media Index',

/* IMDB */
'I_DETAILS'				=> 'IMDB lis�tiedot',
'I_PLOT'				=> 'Juonen yhteenveto',
'I_GALLERY'				=> 'Kuvagalleria',
'I_TRAILERS'			=> 'Trailerit',
'I_LINKS'				=> 'IMDB Linkit',
'I_NOT'					=> 'IMDB tietoja ei saatavilla',

/* DVD Specific */
'DVD_REGION'            => 'Aluekoodi',
'DVD_FORMAT'            => 'DVD-formaatti',
'DVD_ASPECT'            => 'Kuvasuhde',
'DVD_AUDIO'             => '��niraita',
'DVD_SUBTITLES'         => 'Tekstitys',

/* My Movies */
'MY_EXPORT' 			=> 'Vie tiedot',
'MY_EXCEL' 				=> 'Vie tiedot Excel muodossa',
'MY_XML' 				=> 'Vie tiedot XML muodossa',
'MY_XMLTHUMBS'			=> 'Vie thumbnailit XML muodossa',
'MY_ACTIONS'			=> 'Minun toiminnat',
'MY_JOIN'				=> 'Disc join',
'MY_JOINMOVIES'			=> 'Disc join movies',
'MY_JOINSUSER'			=> 'Valitse k�ytt�j�',
'MY_JOINSMEDIA'			=> 'Valitse mediatyyppi',
'MY_JOINSCAT'			=> 'Valitse kategoria',
'MY_JOINSTYPE'			=> 'Valitse toiminto',
'MY_JOINSHOW'			=> 'N�yt� tulokset',
'MY_NORESULTS'			=> 'Kysely ei tuottanut yht��n tulosta',
'MY_TEXTALL'			=> 'Tulostusn�kym� (Text)',
'MY_PWALL'				=> 'Tulostusn�kym� (kaikki)',
'MY_PWMOVIES'			=> 'Tulostusn�kym� (elokuvat)',
'MY_PWTV'				=> 'Tulostusn�kym� (TV showt)',
'MY_PWBLUE'				=> 'Tulostusn�kym� (Blue movies)',
'MY_J1'					=> 'Elokuvat jotka minulla on mutta k�ytt�j�ll� ei',
'MY_J2'					=> 'Elokuvat jotka ovat k�ytt�j�ll� mutta ei minulla',
'MY_J3'					=> 'Elokuvat jotka ovat molemmilla',
'MY_OVERVIEW'			=> 'Kokoelman yhteenveto',
'MY_INFO'				=> 'T�lt� sivulta l�yd�t kaikki tiedot omista elokuvistasi.
							Oikealla on lueteltu toimet joita voit tehd� elokuvakokoelmallesi.
							Voit mm. vied� elokuvalistan tulostusta varten Exceliin.  XML viennill� voit varmuuskopioida tai siirt�� dataa toiseen VCD-db tietokantaan.',
'MY_KEYS'				=> 'Muokkaa ID:t�',
'MY_SEENLIST'			=> 'Muokkaa n�htyjen elokuvien listaa',
'MY_HELPPICKER'			=> 'Valitse katseltava elokuva',
'MY_HELPPICKERINFO'		=> 'Mit� katsoisit t�n��n?<br/>Anna VCD-db:n auttaa sinua l�yt�m��n sopiva elokuva.<br/>
							Halutessasi voit tehd� rajauksia elokuvien hakuun.',
'MY_FIND'				=> 'Etsi elokuva',
'MY_NOTSEEN'			=> 'Ehdota vain elokuvia joita en ole n�hnyt',
'MY_FRIENDS'			=> 'Yst�v�ni jotka lainaavat elokuvia',


/* Manager window */
'MAN_BASIC' 			=> 'Perustiedot',
'MAN_IMDB' 				=> 'IMDB info',
'MAN_EMPIRE' 			=> 'DVDEmpire info',
'MAN_COPY' 				=> 'Kopioni',
'MAN_COPIES' 			=> 'Omat kopiot',
'MAN_NOCOPY' 			=> 'Sinulla ei ole kopioita',
'MAN_1COPY' 			=> 'Kopio',
'MAN_ADDACT' 			=> 'Lis�� n�yttelij�it�',
'MAN_ADDTODB' 			=> 'Lis�� uusia n�yttelij�it� tietokantaan',
'MAN_SAVETODB' 			=> 'Tallenna tietokantaan',
'MAN_SAVETODBNCD' 		=> 'Tallenna tietokantaan',
'MAN_INDB' 				=> 'Tietokannassa olevat n�yttelij�t',
'MAN_SEL' 				=> 'Valitut n�yttelij�t',
'MAN_STARS' 			=> 'T�hdet',
'MAN_BROWSE'			=> 'Selaa...',


/* Add movies */
'ADD_INFO' 				=> 'Valitse menetelm� jolla lis��t uuden elokuvan',
'ADD_IMDB' 				=> 'Nouda tiedot Internet Movie Databasesta',
'ADD_IMDBTITLE' 		=> 'Sy�t� hakusana',
'ADD_MANUAL' 			=> 'Lis�� tiedot k�sin',
'ADD_LISTED' 			=> 'Lis�� elokuvia jotka on jo listattu',
'ADD_XML' 				=> 'Lis�� elokuvia XML tiedostosta',
'ADD_XMLFILE' 			=> 'Valitse tuotava XML tiedosto',
'ADD_XMLNOTE' 			=> '(Huomaa, ett� XML-tiedostoja joita on viety toisesta VCD-db sovelluksesta voidaan k�ytt�� t�ss� uusien elokuvien viemiseen tietokantaan. 							Voit vied� elokuvasi XML-tiedostoon "Omat elokuvat" valikon kautta. V�lt� viedyn XML-tiedoston muokkausta k�sin.) ',
'ADD_MAXFILESIZE'		=> 'Maksimikoko',
'ADD_DVDEMPIRE' 		=> 'Hae tiedot Adult DVD Empire:st� (X-rated films)',
'ADD_LISTEDSTEP1' 		=> 'Vaihe 1<br/>Valitse elokuvat jotka haluat lis�t� omaan listaasi.<br/>Mediatyypin voit valita seuraavassa vaiheessa',
'ADD_LISTEDSTEP2' 		=> 'Vaihe 2.<br/>Valitse sopiva mediatyyppi.',
'ADD_INDB' 				=> 'Elokuvat jotka ovat jo tietokannassa',
'ADD_SELECTED' 			=> 'Valitut elokuvat',
'ADD_INFOLIST' 			=> 'Kaksoisklikkaa elokuvan nime� tai k�yt� nuolia.<br/>Voit k�ytt�� n�pp�imist�� elokuvien pikahakuun.',
'ADD_NOTITLES' 			=> 'Kukaan muu k�ytt�j� ei ole lis�nnyt elokuvia tietokantaan.',


/* Add from XML */
'XML_CONFIRM' 			=> 'Vahvista XML tiedoston lataaminen serverille',
'XML_CONTAINS' 			=> 'XML tiedosto sis�lt�� %d elokuvaa.',
'XML_INFO1' 			=> 'Paina vahvista-nappia k�sitell�ksesi elokuvat ja tallentaaksesi ne tietokantaan.<br/>',
'XML_INFO2' 			=> 'Jos haluat sis�llytt�� thumbnailit (julisteet) elokuviin joita olet tuomassa XML tiedostosta,
							niin sinulla <b>T�YTYY</b> olla thumbnail XML-tiedosto saatavilla t�ss� vaiheessa!<br/>
							Julisteita ei voi tuoda erikseen sen j�lkeen kun olet tuonut elokuvat t�st� XML tiedostosta.
							Jos sinulla on olemassa thumbnail XML-tiedosto, niin tarkista alla oleva kentt�.
							Seuraavassa vaiheessa elokuvien tuonnin j�lkeen, sinua pyydet��n antamaan thumbnail XML-tiedosto k�sittely� varten.',
'XML_THUMBNAILS'		=> 'Lis�� thumbnailit omasta thumbnail XML tiedostosta ',
'XML_LIST'				=> 'Lista elokuvista jotka l�ytyiv�t XML tiedostosta.',
'XML_ERROR'				=> 'XML tiedostosta ei l�ytynyt yht��n elokuvaa.<br/>Tiedosto voi olla vahingoittunut tai tyhj�.
			   				<br/>Varmista, ett� k�yt�t XML tiedostoa, joka on alunperin viety VCD-db:st�...',
'XML_RESULTS'			=> 'XML upload tulokset.',
'XML_RESULTS2'			=> 'T�ss� XML tuonnin tulokset.<br/>Yhteens� tuotiin %d elokuvaa.',


/* Add from DVD Empire */
'EM_INFO'				=> 'Tiedot AdultDVDEmpire.com:sta....',
'EM_DESC'				=> 'DVDEmpire kuvaus',
'EM_SUBCAT'				=> 'Adult kategoriat',
'EM_DETAILS'			=> 'Adultdvdempire.com lis�tiedot',
'EM_STARS'				=> 'Pornot�hdet',
'EM_NOTICE'				=> 'Punaisella merkattuja n�yttelij�it� ei ole t�ll� hetkell� VCD-db:ss�.
							Voit tarkistaa heid�n nimens� jolloin ne lis�t�� automaattisesti VCD-db:hen ja yhdistet��n t�h�n elokuvaan.',
'EM_FETCH'				=> 'Hae my�s',

/* Loan System */
'LOAN_MOVIES'			=> 'Lainattavat elokuvat',
'LOAN_TO'				=> 'Lainaa elokuvat henkil�lle',
'LOAN_ADDUSERS'			=> 'Lis�� k�ytt�ji� jatkaaksesi',
'LOAN_NEWUSER'			=> 'Uusi lainaaja',
'LOAN_REGISTERUSER'		=> 'Lis�� uusi lainaaja',
'LOAN_NAME'				=> 'Nimi',
'LOAN_SELECT'			=> 'Valitse lainaaja',
'LOAN_MOVIELOANS'		=> 'Lainatut elokuvat ...',
'LOAN_REMINDER'			=> 'L�het� muistutus',
'LOAN_HISTORY'			=> 'Lainahistoria',
'LOAN_HISTORY2'			=> 'Tarkastele lainahistoriaa',
'LOAN_SINCE'			=> 'Alkoi',
'LOAN_TIME'				=> 'Pvm l�htien',
'LOAN_RETURN'			=> 'Palauta elokuva',
'LOAN_SUCCESS'			=> 'Elokuvat onnistuneesti lainattu',
'LOAN_OUT'				=> 'Ei palautettu',
'LOAN_DATEIN'			=> 'Palautettu',
'LOAN_DATEOUT'			=> 'Lainassa',
'LOAN_PERIOD'			=> 'Laina-aika',
'LOAN_BACK'				=> 'Palaa lainahakuun',
'LOAN_DAY'				=> 'p�iv�',
'LOAN_DAYS'				=> 'p�iv��',
'LOAN_TODAY'			=> 't�st� p�iv�st� l�htien',


/* RSS */
'RSS'					=> 'RSS Sy�tteet',
'RSS_TITLE'				=> 'RSS sy�tteet kaverieni VCD-DB sivustoilta',
'RSS_SITE'				=> 'RSS Sivuston sy�te',
'RSS_USER'				=> 'RSS K�ytt�j�n sy�te',
'RSS_VIEW'				=> 'N�yt� RSS sy�te',
'RSS_ADD'				=> 'Lis�� uusi sy�te',
'RSS_NOTE'				=> 'Sy�t� VCD-db:n <strong>tarkka URL osoite</strong>.<br/>
							Jos RSS sy�tteet ovat sallittuja kohdesivulla, voit valita ne sy�tteet jotka haluat omalle sivullesi.',
'RSS_FETCH'				=> 'Hae RSS lista',
'RSS_NONE'				=> 'RSS sy�tteit� ei ole lis�tty.',
'RSS_FOUND'				=> 'Seuraavat RSS sy�tteet l�ydettiin, valitse ne sy�tteet jotka haluat lis�t�:',
'RSS_NOTFOUND'			=> 'Ei sy�tteit� kohteessa',


/* Wishlist */
'W_ADD'					=> 'Lis�� toivelistaani',
'W_ONLIST'				=> 'On your wishlist',
'W_EMPTY'				=> 'Toivelistasi on tyhj�',
'W_OWN'					=> 'Omistan kopion t�st� elokuvasta',
'W_NOTOWN'				=> 'En omista kopiota t�st� elokuvasta',


/* Comments */
'C_COMMENTS'			=> 'Kommentit',
'C_ADD'					=> 'L�het� uusi kommentti',
'C_NONE'				=> 'Yht��n kommenttia ei ole l�hetetty',
'C_TYPE'				=> 'Sy�t� uusi kommentti',
'C_YOUR'				=> 'Kommenttisi',
'C_POST'				=> 'L�het� kommentti',
'C_ERROR'				=> 'Sinun pit�� kirjautua sis��n l�hett��ksesi kommentin',


/* Pornstars */
'P_NAME'				=> 'Nimi',
'P_WEB'					=> 'Verkkosivu',
'P_MOVIECOUNT'			=> 'Elokuvalaskuri',


/* Seen List */
'S_SEENIT'				=> 'Olen n�hnyt elokuvan',
'S_NOTSEENIT'			=> 'En ole n�hnyt elokuvaa',
'S_SEENITCLICK'			=> 'Klikkaa t�t� merkataksesi n�hdyksi',
'S_NOTSEENITCLICK'		=> 'Klikkaa t�t� merkataksesi n�kem�tt�m�ksi',

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
'MAIL_RETURNTOPIC'      => 'Lainan muistutus ',
'MAIL_RETURNMOVIES1'	=> '%s, haluaisin, ett� palautat vuokraamasi elokuvat ;).\n
							Sinulla on edelleen seuraavat leffat vuokrassa:\n\n',
'MAIL_RETURNMOVIES2'	=> 'Palautatko pian\n Terveisin %s \n\n
							T�m� on automaattisesti generoitu s�hk�posti VCD-db systeemist� (http://vcddb.konni.com/)',
'MAIL_NOTIFY'  			=> '<strong>Uusi elokuva on lis�tty DVD tietokantaan!</strong><br/>
							Klikkaa <a href="%s/?page=cd&vcd_id=%s">t�t�</a> n�hd�ksesi lis��...
							<p>T�m� on automaattinen viesti, joka on l�hetetty osoitteesta http://vcddb.konni.com</p>',
'MAIL_REGISTER'		 	=> '%s, rekister�inti j�rjestelm��n onnistui.<br><br>K�ytt�j�nimesi on %s ja salasanasi on
							%s.<br><br>Voit vaihtaa salasanaasi kun olet kirjautunut sis��n.<br>
							Klikkaa <a href="%s" target="_new">t�st�</a> siirtyeks�si DVD tietokantaan.',


/* Player */
'PLAYER'				=> 'Soitin',
'PLAYER_PATH'			=> 'Polku',
'PLAYER_PARAM'			=> 'Parametrit',
'PLAYER_NOTE'			=> 'Sy�t� soittimen polku. Soittimesi pit�� tukea komentorivilt� annettuja parametrej�.
							Sellaisia soittimia ovat esimerkiksi BSPlayer Windowsille ja MPlayer Linuxille.<br/>
							BSPlayerin voit ladata ilmaiseksi <a href="http://www.bsplayer.org" target="_new">t��lt�</a>
							ja MPlayerin <a href="http://www.MPlayerHQ.hu" target="_new">t��lt�</a>.',


/* Metadata */
'META_MY'               => 'Oma metadata',
'META_NAME'             => 'Nimi',
'META_DESC'             => 'Kuvaus',
'META_TYPE'             => 'Metadatan tyyppi',
'META_VALUE'            => 'Metadatan arvo',
'META_NONE'             => 'Metadataa ei ole',

/* Ignore List */
'IGN_LIST'              => 'Lista k�ytt�jist� joita ei huomioida',
'IGN_DESC'              => '�l� n�yt� seuraavien k�ytt�jien elokuvia:',

/* Misc keywords */
'X_CONTAINS'			=> 'sis�lt��',
'X_GRADE'				=> 'IMDB arviointi enemm�n kuin',
'X_ANY'					=> 'Mik� tahansa',
'X_TRYAGAIN'			=> 'Yrit� uudelleen',
'X_PROCEED' 			=> 'Jatka',
'X_SELECT' 				=> 'Valitse',
'X_CONFIRM' 			=> 'Vahvista',
'X_CANCEL' 				=> 'Peruuta',
'X_ATTENTION' 			=> 'Huom!',
'X_STATUS' 				=> 'Tila',
'X_SUCCESS' 			=> 'Onnistui',
'X_FAILURE' 			=> 'Ep�onnistui',
'X_YES' 				=> 'Kyll�',
'X_NO' 					=> 'Ei',
'X_SHOWMORE' 			=> 'N�yt� lis��',
'X_SHOWLESS' 			=> 'N�yt� v�hemm�n',
'X_NEW' 				=> 'Uusi',
'X_CHANGE' 				=> 'muuta',
'X_DELETE' 				=> 'poista',
'X_UPDATE' 				=> 'P�ivit�',
'X_SAVEANDCLOSE' 		=> 'Tallenna ja sulje',
'X_CLOSE' 				=> 'Sulje',
'X_EDIT' 				=> 'Muokkaa',
'X_RESULTS' 			=> 'Results',
'X_LATESTMOVIES' 		=> 'viimeisint� elokuvaa',
'X_LATESTTV' 			=> 'viimeisimm�t TV showt',
'X_LATESTBLUE' 			=> 'viimeisimm�t X-rated',
'X_MOVIES' 				=> 'elokuvat',
'X_NOCATS' 				=> 'Elokuvia ei ole lis�tty.',
'X_NOUSERS' 			=> 'Ei aktiivisia k�ytt�ji�',
'X_KEY' 				=> 'Key',
'X_SAVENEXT' 			=> 'Talleta ja muokkaa seuraavaa',
'X_SAVE' 				=> 'Talleta',
'X_SEEN' 				=> 'N�hty',
'X_FOOTER'				=> 'Page Loaded in %s sec. (<i>%d Queries</i>) &nbsp; Copyright (c)',
'X_FOOTER_LINK'			=> 'Check out the official VCD-db website'



);


?>