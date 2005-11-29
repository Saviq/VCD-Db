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
 * @author  Lari Majamäki <jeeger at clankuha.com>
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
'MENU_ADDMOVIE' 		=> 'Lisää uusi elokuva',
'MENU_LOANSYSTEM'		=> 'Lainausjärjestelmä',
'MENU_WISHLIST' 		=> 'Oma toivelista',
'MENU_CATEGORIES' 		=> 'Elokuvakategoriat',
'MENU_RSS' 				=> 'Omat RSS syötteet',
'MENU_CONTROLPANEL' 	=> 'Ohjauspaneeli',
'MENU_REGISTER' 		=> 'Rekisteröidy',
'MENU_LOGOUT' 			=> 'Kirjaudu ulos',
'MENU_SUBMIT'			=> 'Lähetä',
'MENU_TOPUSERS'			=> 'Käyttäjätilastot',
'MENU_WISHLISTPUBLIC'	=> 'Toisten toivelistat',
'MENU_STATISTICS'		=> 'Statistics',

/* Login */
'LOGIN' 				=> 'Kirjaudu',
'LOGIN_USERNAME' 		=> 'Käyttäjätunnus',
'LOGIN_PASSWORD' 		=> 'Salasana',
'LOGIN_REMEMBER' 		=> 'Muista minut',
'LOGIN_INFO' 			=> 'Jätä tämä tyhjäksi jos <b>et</b> halua vaihtaa salasanaa',

/* Register */
'REGISTER_TITLE'		=> 'Rekisteröityminen',
'REGISTER_FULLNAME' 	=> 'Koko nimi',
'REGISTER_EMAIL' 		=> 'Sähköposti',
'REGISTER_AGAIN' 		=> 'Salasana uudelleen',
'REGISTER_DISABLED' 	=> 'Tällä hetkellä rekisteröinti ei ole mahdollista. Ota yhteys järjestelmänvalvojaan.',
'REGISTER_OK' 			=> 'Rekisteröinti onnistui, voit nyt kirjautua sisään tietokantaan.',

/* User Properties */
'PRO_NOTIFY' 			=> 'Lähetä minulle sähköpostia kun tietokantaan on lisätty uusi elokuva?',
'PRO_SHOW_ADULT' 		=> 'Näytä aikuisviihdemateriaali sivustolla?',
'PRO_RSS' 				=> 'Salli RSS syöte omasta elokuvalistastani?',
'PRO_WISHLIST'    		=> 'Salli muiden näkevän toivelistani',
'PRO_USE_INDEX'   		=> 'Käytä indeksinumerokenttiä tallenteiden omalle numeroinnille',
'PRO_SEEN_LIST'   		=> 'Pidä kirjaa elokuvista jotka olen nähnyt',
'PRO_PLAYOPTION'   		=> 'Käytä soittimen toistoasetuksia',
'SE_PAGELOOK' 			=> 'Web layout',
'SE_PAGEMODE' 			=> 'Select default template:',

/* User Settings */
'SE_PLAYER' 			=> 'Soittimen asetukset',
'SE_OWNFEED' 			=> 'Tarkastele omaa syötettä',
'SE_CUSTOM' 			=> 'Muokkaa omaa aloitussivua',
'SE_SHOWSTAT' 			=> 'Näytä tilastot',
'SE_SHOWSIDE' 			=> 'Näytä uudet elokuvat sivuvalikossa',
'SE_SELECTRSS' 			=> 'Valitse RSS syötteet',


/* Search */
'SEARCH' 				=> 'Etsi',
'SEARCH_TITLE' 			=> 'Elokuvan nimi',
'SEARCH_ACTOR' 			=> 'Näyttelijä',
'SEARCH_DIRECTOR' 		=> 'Ohjaaja',
'SEARCH_RESULTS' 		=> 'Haun tulokset',
'SEARCH_EXTENDED' 		=> 'Tarkennettu haku',
'SEARCH_NORESULT' 		=> 'Haussa ei löytynyt yhtään tulosta',

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
'M_ACTORS' 				=> 'Näyttelijät',
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
'M_NUM' 				=> 'Lukumäärä',
'M_DATE' 				=> 'Lisäyspäivämäärä',
'M_OWNER'				=> 'Omistaja',
'M_NOACTORS'			=> 'Näyttelijälistaa ei saatavilla',
'M_INFO'				=> 'Elokuvan tietoja',
'M_DETAILS'				=> 'Lisätietoja kopiostani',
'M_MEDIATYPE'			=> 'Mediatyyppi',
'M_COMMENT'				=> 'Kommentti',
'M_PRIVATE'				=> 'Merkitse yksityiseksi',
'M_SCREENSHOTS'			=> 'Kuvakaappauksia',
'M_NOSCREENS'			=> 'Kuvakaappauksia ei saatavilla',
'M_SHOW'				=> 'Näytä',
'M_HIDE'				=> 'Piilota',
'M_CHANGE'				=> 'Muuta tietoja',
'M_NOCOVERS'			=> 'DVD kansia ei saatavilla',
'M_BYCAT'				=> 'Elokuvan nimet kategoriassa',
'M_CURRCAT'				=> 'Valittu kategoria',
'M_TEXTVIEW'			=> 'Tekstinäkymä',
'M_IMAGEVIEW'			=> 'Kuvanäkymä',
'M_MINEONLY'			=> 'Näytä vain omat elokuvat',
'M_SIMILAR'				=> 'Samankaltaiset elokuvat',
'M_MEDIAINDEX'			=> 'Media Index',

/* IMDB */
'I_DETAILS'				=> 'IMDB lisätiedot',
'I_PLOT'				=> 'Juonen yhteenveto',
'I_GALLERY'				=> 'Kuvagalleria',
'I_TRAILERS'			=> 'Trailerit',
'I_LINKS'				=> 'IMDB Linkit',
'I_NOT'					=> 'IMDB tietoja ei saatavilla',

/* My Movies */
'MY_EXPORT' 			=> 'Vie tiedot',
'MY_EXCEL' 				=> 'Vie tiedot Excel muodossa',
'MY_XML' 				=> 'Vie tiedot XML muodossa',
'MY_XMLTHUMBS'			=> 'Vie thumbnailit XML muodossa',
'MY_ACTIONS'			=> 'Minun toiminnat',
'MY_JOIN'				=> 'Disc join',
'MY_JOINMOVIES'			=> 'Disc join movies',
'MY_JOINSUSER'			=> 'Valitse käyttäjä',
'MY_JOINSMEDIA'			=> 'Valitse mediatyyppi',
'MY_JOINSCAT'			=> 'Valitse kategoria',
'MY_JOINSTYPE'			=> 'Valitse toiminto',
'MY_JOINSHOW'			=> 'Näytä tulokset',
'MY_NORESULTS'			=> 'Kysely ei tuottanut yhtään tulosta',
'MY_PWALL'				=> 'Tulostusnäkymä (kaikki)',
'MY_PWMOVIES'			=> 'Tulostusnäkymä (elokuvat)',
'MY_PWTV'				=> 'Tulostusnäkymä (TV showt)',
'MY_PWBLUE'				=> 'Tulostusnäkymä (Blue movies)',
'MY_J1'					=> 'Elokuvat jotka minulla on mutta käyttäjällä ei',
'MY_J2'					=> 'Elokuvat jotka ovat käyttäjällä mutta ei minulla',
'MY_J3'					=> 'Elokuvat jotka ovat molemmilla',
'MY_OVERVIEW'			=> 'Kokoelman yhteenveto',
'MY_INFO'				=> 'Tältä sivulta löydät kaikki tiedot omista elokuvistasi.
				Oikealla on lueteltu toimet joita voit tehdä elokuvakokoelmallesi.
				Voit mm. viedä elokuvalistan tulostusta varten Exceliin.  XML viennillä voit varmuuskopioida tai siirtää dataa toiseen VCD-db tietokantaan.',
'MY_KEYS'			=> 'Muokkaa ID:tä',
'MY_SEENLIST'			=> 'Muokkaa nähtyjen elokuvien listaa',
'MY_HELPPICKER'			=> 'Valitse katseltava elokuva',
'MY_HELPPICKERINFO'		=> 'Mitä katsoisit tänään?<br/>Anna VCD-db:n auttaa sinua löytämään sopiva elokuva.<br/>
				Halutessasi voit tehdä rajauksia elokuvien hakuun.',
'MY_FIND'			=> 'Etsi elokuva',
'MY_NOTSEEN'			=> 'Ehdota vain elokuvia joita en ole nähnyt',
'MY_FRIENDS'			=> 'Ystäväni jotka lainaavat elokuvia',


/* Manager window */
'MAN_BASIC' 			=> 'Perustiedot',
'MAN_IMDB' 			=> 'IMDB info',
'MAN_EMPIRE' 			=> 'DVDEmpire info',
'MAN_COPY' 			=> 'Kopioni',
'MAN_COPIES' 			=> 'Omat kopiot',
'MAN_NOCOPY' 			=> 'Sinulla ei ole kopioita',
'MAN_1COPY' 			=> 'Kopio',
'MAN_ADDACT' 			=> 'Lisää näyttelijöitä',
'MAN_ADDTODB' 			=> 'Lisää uusia näyttelijöitä tietokantaan',
'MAN_SAVETODB' 			=> 'Tallenna tietokantaan',
'MAN_SAVETODBNCD' 		=> 'Tallenna tietokantaan',
'MAN_INDB' 			=> 'Tietokannassa olevat näyttelijät',
'MAN_SEL' 			=> 'Valitut näyttelijät',
'MAN_STARS' 			=> 'Tähdet',
'MAN_BROWSE'			=> 'Selaa...',


/* Add movies */
'ADD_INFO' 			=> 'Valitse menetelmä jolla lisäät uuden elokuvan',
'ADD_IMDB' 			=> 'Nouda tiedot Internet Movie Databasesta',
'ADD_IMDBTITLE' 		=> 'Syötä hakusana',
'ADD_MANUAL' 			=> 'Lisää tiedot käsin',
'ADD_LISTED' 			=> 'Lisää elokuvia jotka on jo listattu',
'ADD_XML' 			=> 'Lisää elokuvia XML tiedostosta',
'ADD_XMLFILE' 			=> 'Valitse tuotava XML tiedosto',
'ADD_XMLNOTE' 			=> '(Huomaa, että XML-tiedostoja joita on viety toisesta VCD-db sovelluksesta voidaan käyttää tässä uusien elokuvien viemiseen tietokantaan. 							Voit viedä elokuvasi XML-tiedostoon "Omat elokuvat" valikon kautta. Vältä viedyn XML-tiedoston muokkausta käsin.) ',
'ADD_MAXFILESIZE'		=> 'Maksimikoko',
'ADD_DVDEMPIRE' 		=> 'Hae tiedot Adult DVD Empire:stä (X-rated films)',
'ADD_LISTEDSTEP1' 		=> 'Vaihe 1<br/>Valitse elokuvat jotka haluat lisätä omaan listaasi.<br/>Mediatyypin voit valita seuraavassa vaiheessa',
'ADD_LISTEDSTEP2' 		=> 'Vaihe 2.<br/>Valitse sopiva mediatyyppi.',
'ADD_INDB' 			=> 'Elokuvat jotka ovat jo tietokannassa',
'ADD_SELECTED' 			=> 'Valitut elokuvat',
'ADD_INFOLIST' 			=> 'Kaksoisklikkaa elokuvan nimeä tai käytä nuolia.<br/>Voit käyttää näppäimistöä elokuvien pikahakuun.',
'ADD_NOTITLES' 			=> 'Kukaan muu käyttäjä ei ole lisännyt elokuvia tietokantaan.',


/* Add from XML */
'XML_CONFIRM' 			=> 'Vahvista XML tiedoston lataaminen serverille',
'XML_CONTAINS' 			=> 'XML tiedosto sisältää %d elokuvaa.',
'XML_INFO1' 			=> 'Paina vahvista-nappia käsitelläksesi elokuvat ja tallentaaksesi ne tietokantaan.<br/>',
'XML_INFO2' 			=> 'Jos haluat sisällyttää thumbnailit (julisteet) elokuviin joita olet tuomassa XML tiedostosta,
				niin sinulla <b>TÄYTYY</b> olla thumbnail XML-tiedosto saatavilla tässä vaiheessa!<br/>
				Julisteita ei voi tuoda erikseen sen jälkeen kun olet tuonut elokuvat tästä XML tiedostosta.
				Jos sinulla on olemassa thumbnail XML-tiedosto, niin tarkista alla oleva kenttä. 
				Seuraavassa vaiheessa elokuvien tuonnin jälkeen, sinua pyydetään antamaan thumbnail XML-tiedosto käsittelyä varten.',
'XML_THUMBNAILS'		=> 'Lisää thumbnailit omasta thumbnail XML tiedostosta ',
'XML_LIST'			=> 'Lista elokuvista jotka löytyivät XML tiedostosta.',
'XML_ERROR'			=> 'XML tiedostosta ei löytynyt yhtään elokuvaa.<br/>Tiedosto voi olla vahingoittunut tai tyhjä.
			   	<br/>Varmista, että käytät XML tiedostoa, joka on alunperin viety VCD-db:stä...',
'XML_RESULTS'			=> 'XML upload tulokset.',
'XML_RESULTS2'			=> 'Tässä XML tuonnin tulokset.<br/>Yhteensä tuotiin %d elokuvaa.',


/* Add from DVD Empire */
'EM_INFO'			=> 'Tiedot AdultDVDEmpire.com:sta....',
'EM_DESC'			=> 'DVDEmpire kuvaus',
'EM_SUBCAT'			=> 'Adult kategoriat',
'EM_DETAILS'			=> 'Adultdvdempire.com lisätiedot',
'EM_STARS'			=> 'Pornotähdet',
'EM_NOTICE'			=> 'Punaisella merkattuja näyttelijöitä ei ole tällä hetkellä VCD-db:ssä.
				Voit tarkistaa heidän nimensä jolloin ne lisätää automaattisesti VCD-db:hen ja yhdistetään tähän elokuvaan.',
'EM_FETCH'			=> 'Hae myös',

/* Loan System */
'LOAN_MOVIES'			=> 'Lainattavat elokuvat',
'LOAN_TO'			=> 'Lainaa elokuvat henkilölle',
'LOAN_ADDUSERS'			=> 'Lisää käyttäjiä jatkaaksesi',
'LOAN_NEWUSER'			=> 'Uusi lainaaja',
'LOAN_REGISTERUSER'		=> 'Lisää uusi lainaaja',
'LOAN_NAME'			=> 'Nimi',
'LOAN_SELECT'			=> 'Valitse lainaaja',
'LOAN_MOVIELOANS'		=> 'Lainatut elokuvat ...',
'LOAN_REMINDER'			=> 'Lähetä muistutus',
'LOAN_HISTORY'			=> 'Lainahistoria',
'LOAN_HISTORY2'			=> 'Tarkastele lainahistoriaa',
'LOAN_SINCE'			=> 'Alkoi',
'LOAN_TIME'			=> 'Pvm lähtien',
'LOAN_RETURN'			=> 'Palauta elokuva',
'LOAN_SUCCESS'			=> 'Elokuvat onnistuneesti lainattu',
'LOAN_OUT'			=> 'Ei palautettu',
'LOAN_DATEIN'			=> 'Palautettu',
'LOAN_DATEOUT'			=> 'Lainassa',
'LOAN_PERIOD'			=> 'Laina-aika',
'LOAN_BACK'			=> 'Palaa lainahakuun',
'LOAN_DAY'			=> 'päivä',
'LOAN_DAYS'			=> 'päivää',
'LOAN_TODAY'			=> 'tästä päivästä lähtien',


/* RSS */
'RSS'				=> 'RSS Syötteet',
'RSS_TITLE'			=> 'RSS syötteet kaverieni VCD-DB sivustoilta',
'RSS_SITE'			=> 'RSS Sivuston syöte',
'RSS_USER'			=> 'RSS Käyttäjän syöte',
'RSS_VIEW'			=> 'Näytä RSS syöte',
'RSS_ADD'			=> 'Lisää uusi syöte',
'RSS_NOTE'			=> 'Syötä VCD-db:n <strong>tarkka URL osoite</strong>.<br/>
				Jos RSS syötteet ovat sallittuja kohdesivulla, voit valita ne syötteet jotka haluat omalle sivullesi.',
'RSS_FETCH'			=> 'Hae RSS lista',
'RSS_NONE'			=> 'RSS syötteitä ei ole lisätty.',
'RSS_FOUND'			=> 'Seuraavat RSS syötteet löydettiin, valitse ne syötteet jotka haluat lisätä:',
'RSS_NOTFOUND'			=> 'Ei syötteitä kohteessa',


/* Wishlist */
'W_ADD'				=> 'Lisää toivelistaani',
'W_ONLIST'			=> 'On your wishlist',
'W_EMPTY'			=> 'Toivelistasi on tyhjä',
'W_OWN'				=> 'Omistan kopion tästä elokuvasta',
'W_NOTOWN'			=> 'En omista kopiota tästä elokuvasta',


/* Comments */
'C_COMMENTS'			=> 'Kommentit',
'C_ADD'				=> 'Lähetä uusi kommentti',
'C_NONE'			=> 'Yhtään kommenttia ei ole lähetetty',
'C_TYPE'			=> 'Syötä uusi kommentti',
'C_YOUR'			=> 'Kommenttisi',
'C_POST'			=> 'Lähetä kommentti',
'C_ERROR'			=> 'Sinun pitää kirjautua sisään lähettääksesi kommentin',


/* Pornstars */
'P_NAME'			=> 'Nimi',
'P_WEB'				=> 'Verkkosivu',
'P_MOVIECOUNT'			=> 'Elokuvalaskuri',


/* Seen List */
'S_SEENIT'			=> 'Olen nähnyt elokuvan',
'S_NOTSEENIT'			=> 'En ole nähnyt elokuvaa',
'S_SEENITCLICK'			=> 'Klikkaa tätä merkataksesi nähdyksi',
'S_NOTSEENITCLICK'		=> 'Klikkaa tätä merkataksesi näkemättömäksi',

/* Mail messages */
'MAIL_RETURNTOPIC'		=> 'Loan reminder',
'MAIL_RETURNMOVIES1'		=> '%s, haluaisin, että palautat vuokraamasi elokuvat ;).\n
				Sinulla on edelleen seuraavat leffat vuokrassa:\n\n',
'MAIL_RETURNMOVIES2'		=> 'Palautatko pian\n Terveisin %s \n\n
				Tämä on automaattisesti generoitu sähköposti VCD-db systeemistä (http://URL/)',
'MAIL_NOTIFY'  			=> '<strong>Uusi elokuva on lisätty DVD tietokantaan!</strong><br/>
				Klikkaa <a href="%s/?page=cd&vcd_id=%s">tätä</a> nähdäksesi lisää...
				<p>Tämä on automaattinen viesti, joka on lähetetty osoitteesta http://URL/</p>',
'MAIL_REGISTER'		 	=> '%s, rekisteröinti järjestelmään onnistui.\n\nKäyttäjänimesi on %s ja salasanasi on  
				%s.\n\nVoit vaihtaa salasanaasi kun olet kirjautunut sisään.\n
				Klikkaa <a href="%s" target="_new">tästä</a> siirtyeksäsi DVD tietokantaan.',


/* Player */
'PLAYER'			=> 'Soitin',
'PLAYER_PATH'			=> 'Polku',
'PLAYER_PARAM'			=> 'Parametrit',
'PLAYER_NOTE'			=> 'Syötä soittimen polku. Soittimesi pitää tukea komentoriviltä annettuja parametrejä. 
				Sellaisia soittimia ovat esimerkiksi BSPlayer Windowsille ja MPlayer Linuxille.<br/>
				BSPlayerin voit ladata ilmaiseksi <a href="http://www.bsplayer.org" target="_new">täältä</a> 
				ja MPlayerin <a href="http://www.MPlayerHQ.hu" target="_new">täältä</a>.',


/* Misc keywords */
'X_CONTAINS'			=> 'sisältää',
'X_GRADE'			=> 'IMDB arviointi enemmän kuin',
'X_ANY'				=> 'Mikä tahansa',
'X_TRYAGAIN'			=> 'Yritä uudelleen',
'X_PROCEED' 			=> 'Jatka',
'X_SELECT' 			=> 'Valitse',
'X_CONFIRM' 			=> 'Vahvista',
'X_CANCEL' 			=> 'Peruuta',
'X_ATTENTION' 			=> 'Huom!',
'X_STATUS' 			=> 'Tila',
'X_SUCCESS' 			=> 'Onnistui',
'X_FAILURE' 			=> 'Epäonnistui',
'X_YES' 			=> 'Kyllä',
'X_NO' 				=> 'Ei',
'X_SHOWMORE' 			=> 'Näytä lisää',
'X_SHOWLESS' 			=> 'Näytä vähemmän',
'X_NEW' 			=> 'Uusi',
'X_CHANGE' 			=> 'muuta',
'X_DELETE' 			=> 'poista',
'X_UPDATE' 			=> 'Päivitä',
'X_SAVEANDCLOSE' 		=> 'Tallenna ja sulje',
'X_CLOSE' 			=> 'Sulje',
'X_EDIT' 			=> 'Muokkaa',
'X_RESULTS' 			=> 'Results',
'X_LATESTMOVIES' 		=> 'viimeisintä elokuvaa',
'X_LATESTTV' 			=> 'viimeisimmät TV showt',
'X_LATESTBLUE' 			=> 'viimeisimmät X-rated',
'X_MOVIES' 			=> 'elokuvat',
'X_NOCATS' 			=> 'Elokuvia ei ole lisätty.',
'X_NOUSERS' 			=> 'Ei aktiivisia käyttäjiä',
'X_KEY' 			=> 'Key',
'X_SAVENEXT' 			=> 'Talleta ja muokkaa seuraavaa',
'X_SAVE' 			=> 'Talleta',
'X_SEEN' 			=> 'Nähty'


);


?>