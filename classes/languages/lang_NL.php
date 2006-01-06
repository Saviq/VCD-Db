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
 * @author  Hï¿½kon Birgsson <konni@konni.com>
 * @package Language
 * @version $Id$
 */

?>
<?
   /**
      Dutch language file
      Big Thanks to ikerstges for the translation !
   */



$_ = array(

/* Language Identifier */
'LANG_TYPE'         	=> 'NL',
'LANG_NAME'         	=> 'Nederlands',
'LANG_CHARSET'			=> 'iso-8859-1',

/* Menu system */
'MENU_MINE'         	=> 'Mijn menu',
'MENU_SETTINGS'     	=> 'Mijn instellingen',
'MENU_MOVIES'       	=> 'Mijn films',
'MENU_ADDMOVIE'       	=> 'Film toevoegen',
'MENU_LOANSYSTEM'      	=> 'Uitleen balie',
'MENU_WISHLIST'       	=> 'Mijn verlanglijstje',
'MENU_CATEGORIES'       => 'Film kategoriën',
'MENU_RSS'          	=> 'Mijn Rss Feeds',
'MENU_CONTROLPANEL'     => 'VCD-db Admin',
'MENU_REGISTER'       	=> 'Registreer',
'MENU_LOGOUT'          	=> 'Uitloggen',
'MENU_SUBMIT'         	=> 'Doorsturen',
'MENU_TOPUSERS'         => 'Top gebruikers',
'MENU_WISHLISTPUBLIC'	=> 'Overige verlanglijstjes',
'MENU_STATISTICS'		=> 'Statistieken',

/* Login */
'LOGIN'          		=> 'Login',
'LOGIN_USERNAME'        => 'Gebruikersnaam',
'LOGIN_PASSWORD'        => 'Wachtwoord',
'LOGIN_REMEMBER'        => 'Onthoud mij',
'LOGIN_INFO'            => 'Laat dit leeg als je je wachtwoord <b>niet</b> wil veranderen',

/* Register */
'REGISTER_TITLE'      	=> 'Registreren',
'REGISTER_FULLNAME'     => 'Volledige naam',
'REGISTER_EMAIL'        => 'Email',
'REGISTER_AGAIN'        => 'Wachtwoord opnieuw',
'REGISTER_DISABLED'     => 'Sorry, de Beheerder heeft nieuwe registraties op dit moment uitgeschakeld',
'REGISTER_OK' 			=> 'Registratie ok, je kunt nu inloggen in VCD-db.',

/* User Properties */
'PRO_NOTIFY'            => 'Stuur mij email als \'n film is toegevoegd?',
'PRO_SHOW_ADULT'     	=> 'Toon \'adult\' inhoud op de site?',
'PRO_RSS'               => 'RSS nieuws toestaan van mijn film-lijst?',
'PRO_WISHLIST'          => 'Anderen toestaan mijn wensenlijst te zien?',
'PRO_USE_INDEX'         => 'Gebruik index nummer veld voor eigen media ID\'s',
'PRO_SEEN_LIST'         => 'Bijhouden welke films ik heb gezien',
'PRO_PLAYOPTION'        => 'Gebruik player opties op client',
'PRO_NFO' 				=> 'Van het gebruik NFO- dossier?',

/* User Settings */
'SE_PLAYER'             => 'Player instellingen',
'SE_OWNFEED'            => 'Bekijk eigen feed',
'SE_CUSTOM'             => 'Voorpagina aanpassen',
'SE_SHOWSTAT'           => 'Statistieken',
'SE_SHOWSIDE'           => 'Laat nieuwe film in zijmenu zien',
'SE_SELECTRSS'          => 'Selecteer RSS feeds',
'SE_PAGELOOK' 			=> 'Web layout',
'SE_PAGEMODE' 			=> 'Select default template:',
'SE_UPDATED'			=> 'User information updated',
'SE_UPDATE_FAILED'		=> 'Failed to update',

/* Search */
'SEARCH'          		=> 'Zoek',
'SEARCH_TITLE'          => 'Op titel',
'SEARCH_ACTOR'          => 'Op acteur',
'SEARCH_DIRECTOR'       => 'Op regisseur',
'SEARCH_RESULTS'        => 'Zoekresultaten',
'SEARCH_EXTENDED'       => 'Gedetailleerd zoeken',
'SEARCH_NORESULT'       => 'Zoekresultaten zijn leeg',

/* Movie categories*/
'CAT_ACTION'          	=> 'Actie',
'CAT_ADULT'          	=> 'Volwassenen',
'CAT_ADVENTURE'       	=> 'Avontuur',
'CAT_ANIMATION'       	=> 'Tekenfilm',
'CAT_ANIME'         	=> 'Anime / Manga',
'CAT_COMEDY'          	=> 'Comedy',
'CAT_CRIME'          	=> 'Detective',
'CAT_DOCUMENTARY'       => 'Documentaire',
'CAT_DRAMA'          	=> 'Drama',
'CAT_FAMILY'          	=> 'Family',
'CAT_FANTASY'          	=> 'Fantasie',
'CAT_FILMNOIR'          => 'Film Noir',
'CAT_HORROR'          	=> 'Horror',
'CAT_JAMESBOND'       	=> 'James Bond',
'CAT_MUSICVIDEO'       	=> 'Muziek Video',
'CAT_MUSICAL'          	=> 'Musical',
'CAT_MYSTERY'          	=> 'Mystery',
'CAT_ROMANCE'          	=> 'Romantisch',
'CAT_SCIFI'          	=> 'Sci-Fi',
'CAT_SHORT'          	=> 'Kort film',
'CAT_THRILLER'          => 'Triller',
'CAT_TVSHOWS'          	=> 'TV Shows',
'CAT_WAR'          		=> 'Oorlog',
'CAT_WESTERN'           => 'Western',
'CAT_XRATED'          	=> 'X-Rated',

/* Movie Listings */
'M_MOVIE'          		=> 'De film',
'M_ACTORS'          	=> 'Bezetting',
'M_CATEGORY'            => 'Kategorie',
'M_YEAR'         		=> 'Productie jaar',
'M_COPIES'         		=> 'Kopiën',
'M_FROM'          		=> 'Van',
'M_TITLE'          		=> 'Titel',
'M_ALTTITLE'          	=> 'Alternatieve titel',
'M_GRADE'         		=> 'Waardering',
'M_DIRECTOR'          	=> 'Regisseur',
'M_COUNTRY'         	=> 'Productie land',
'M_RUNTIME'          	=> 'Speelduur',
'M_MINUTES'         	=> 'minuten',
'M_PLOT'          		=> 'Verhaallijn',
'M_NOPLOT'         		=> 'Geen verhaallijn beschikbaar',
'M_COVERS'          	=> 'CD Afbeeldingen',
'M_AVAILABLE'          	=> 'Beschikbare kopiën',
'M_MEDIA'         		=> 'Medium',
'M_NUM'          		=> 'Num CD\'s',
'M_DATE'          		=> 'Datum toegevoegd',
'M_OWNER'         		=> 'Eigenaar',
'M_NOACTORS'        	=> 'Geen acteurslijst beschikbaar',
'M_INFO'         		=> 'Film info',
'M_DETAILS'         	=> 'Details over mijn kopie',
'M_MEDIATYPE'           => 'Media type',
'M_COMMENT'         	=> 'Commentaar',
'M_PRIVATE'         	=> 'Markeer privé?',
'M_SCREENSHOTS'         => 'Screenshots',
'M_NOSCREENS'           => 'Geen screenshots beschikbaar',
'M_SHOW'         		=> 'Toon',
'M_HIDE'        		=> 'Verberg',
'M_CHANGE'        		=> 'Verander info',
'M_NOCOVERS'         	=> 'Geen CD-afbeeldingen beschikbaar',
'M_BYCAT'         		=> 'Titels kategorie',
'M_CURRCAT'         	=> 'Huidige kategorie',
'M_TEXTVIEW'         	=> 'Tekst beeld',
'M_IMAGEVIEW'         	=> 'Afbeeldingen beeld',
'M_MINEONLY'			=> 'Toon alleen mijn films',
'M_SIMILAR'				=> 'Similar movies',
'M_MEDIAINDEX'			=> 'Index nummer ',

/* IMDB */
'I_DETAILS'         	=> 'IMDB Details',
'I_PLOT'         		=> 'Verhaallijn, samenvatting',
'I_GALLERY'         	=> 'Foto overzicht',
'I_TRAILERS'         	=> 'Trailers',
'I_LINKS'         		=> 'IMDB Koppelingen',
'I_NOT'            		=> 'Geen IMDB info beschikbaar',

/* DVD Specific */
'DVD_REGION'			=> 'Region',
'DVD_FORMAT'			=> 'Format',
'DVD_ASPECT'			=> 'Aspect ratio',
'DVD_AUDIO'				=> 'Audio',
'DVD_SUBTITLES'			=> 'Subtitles',

/* My Movies */
'MY_EXPORT'          	=> 'Export gegevens',
'MY_EXCEL'          	=> 'Exporteer naar Excel',
'MY_XML'          		=> 'Exporteer naar XML',
'MY_XMLTHUMBS'          => 'Exporteer thumbnails als XML',
'MY_ACTIONS'         	=> 'Mijn akties',
'MY_JOIN'         		=> 'Disk toevoegen',
'MY_JOINMOVIES'         => 'Disk toevoegen films',
'MY_JOINSUSER'         	=> 'Selecteer gebruiker',
'MY_JOINSMEDIA'         => 'Selecteer media type',
'MY_JOINSCAT'         	=> 'Selecteer kategorie',
'MY_JOINSTYPE'        	=> 'Selecteer aktie',
'MY_JOINSHOW'        	=> 'Toon resultaten',
'MY_NORESULTS'          => 'Zoekterm leverde geen resultaten',
'MY_TEXTALL'			=> 'Printbeeld (Text)',
'MY_PWALL'        		=> 'Printbeeld (Alles)',
'MY_PWMOVIES'         	=> 'Printbeeld (Films)',
'MY_PWTV'         		=> 'Printbeeld (Tv Shows)',
'MY_PWBLUE'         	=> 'Printbeeld (Blue movies)',
'MY_J1'            		=> 'Films die ik heb, maar gebruiker niet',
'MY_J2'            		=> 'Films die gebruiker heeft, maar ik niet',
'MY_J3'            		=> 'Films die we beiden hebben',
'MY_OVERVIEW'         	=> 'Collectie overzicht',
'MY_INFO'         		=> 'Op deze pagina is alle informatie te vinden over mijn films.
                  Aan de rechter zijkant staan de acties die je kunt uitvoeren op je film collectie.
                  Je kunt ook een lijst naar Excel exporteren om te printen, of de XML export functies
                  gebruiken voor het maken van een backup of om alle gegevens van je collectie te verplaatsen
                  van de ene VCD-db naar een andere.',
'MY_KEYS'        		=> 'Bewerk eigen ID\'s',
'MY_SEENLIST'        	=> 'Bewerk lijst \'gezien\'',
'MY_HELPPICKER'         => 'Suggestie om te bekijken',
'MY_HELPPICKERINFO'     => 'Geen idee welke film te bekijken vandaag?<br/>Laat VCD-db helpen om een film te vinden.<br/>
                  Je kunt ook filters maken en gebruiken om het resultaat te beperken.',
'MY_FIND'         		=> 'Vind een film',
'MY_NOTSEEN'        	=> 'Alleen films voorstellen die ik nog niet heb gezien',
'MY_FRIENDS'		    => 'My friends who borrow CD\'s',

/* Manager window */
'MAN_BASIC'         	=> 'Algemene info',
'MAN_IMDB'       	    => 'IMDB info',
'MAN_EMPIRE'            => 'DVDEmpire info',
'MAN_COPY'          	=> 'Mijn kopie',
'MAN_COPIES'            => 'Mijn kopiën',
'MAN_NOCOPY'            => 'Je hebt geen kopiën.',
'MAN_1COPY'          	=> 'Kopie',
'MAN_ADDACT'          	=> 'Acteurs toevoegen',
'MAN_ADDTODB'           => 'Voeg nieuwe acteurs toe aan de DB',
'MAN_SAVETODB'          => 'Schrijf naar DB',
'MAN_SAVETODBNCD'       => 'Schrijf naar DB én film',
'MAN_INDB'          	=> 'Acteurs in DB',
'MAN_SEL'         		=> 'Geselecteerde acteurs',
'MAN_STARS' 			=> 'Stars',
'MAN_BROWSE'			=> 'Browse for file location',

/* Add movies */
'ADD_INFO'          	=> 'Kies methode om nieuwe film toe te voegen',
'ADD_IMDB'          	=> 'Haal gegevens van Internet Movie Database',
'ADD_IMDBTITLE'       	=> 'Geef criteria om te zoeken',
'ADD_MANUAL'          	=> 'Handmatig invoeren',
'ADD_LISTED'          	=> 'Voeg films toe die bij andere gebruiker reeds staan vermeld',
'ADD_XML'          		=> 'Voeg films toe vanuit geëxporteerd XML-bestand',
'ADD_XMLFILE'           => 'Selecteer XML-bestand om te importeren',
'ADD_XMLNOTE'           => '(<u>Waarschuwing:</u> alleen XML-bestanden die werden geëxporteerd vanuit een andere VCD-db installatie
                  kunnen worden gebruikt om je films hier te importeren. Je kunt je films exporteren
                  vanuit de "Mijn films" sectie. Probeer handmatige aanpassingen van de geëxporteerde
                  XML-bestanden te vermijden.) ',
'ADD_MAXFILESIZE'       => 'Max bestandsgrootte',
'ADD_DVDEMPIRE'       	=> 'Haal gegevens van Adult DVD Empire (X-rated films)',
'ADD_LISTEDSTEP1'       => 'Step 1<br/>Selecteer de titels die je wil toevoegen aan je lijst.<br/>bijpassend media type kan worden geselecteerd
                      in de volgende stap',
'ADD_LISTEDSTEP2'       => 'Step 2.<br/>Selecteer het bijpassende media type.',
'ADD_INDB'          	=> 'Films in VCD-DB',
'ADD_SELECTED'          => 'Geselecteerde titel',
'ADD_INFOLIST'          => 'Dubbel-klik op titel om te selecteren of gebruik pijltjestoetsen.<br/>Je kunt het toetsenbord gebruiken om
                  titels snel te vinden.',
'ADD_NOTITLES'          => 'Geen andere gebruiker heeft films toegevoegd aan VCD-db',

/* Add from XML */
'XML_CONFIRM'           => 'Bevestigen XML upload',
'XML_CONTAINS'          => 'XML-bestand bevat %d films.',
'XML_INFO1'          	=> 'Kies bevestigen om de films te verwerken en op te slaan in de database.<br/>
                  Of kies afbreken om hier uit te stappen. ',
'XML_INFO2'          	=> 'Als je thumbnails (posters) will meenemen met de films die je met deze keuze gaat
                  importeren in je XML-bestand, dan <b>MOET</b> je het thumbnails XML-bestand nu beschikbaar hebben!.<br/>
                  Posters kunnen niet worden geïmporteerd nadat je gereed bent met importeren van je films vanuit het huidige XML-bestand.
                  Als je reeds het XML-bestand hebt, check dan het veld hieronder en in de volgende stap na het importeren
                  van je films in ondertaande lijst, zal tevens aan je worden gevraagd om het thumbnails XML-bestand
                  opdat deze gegevens mee kunnen worden verwerkt.',
'XML_THUMBNAILS'      	=> 'Voeg thumbnails toe vanuit mijn thumbnails XML-bestand',
'XML_LIST'         		=> 'Volledige lijst van films, aangetroffen in XML-bestand',
'XML_ERROR'         	=> 'Geen titels gevonden in XML-bestand.<br/>Bestand kan beschadigd zijn, of het is gewoon een leeg bestand.
                     <br/>Zorg ervoor dat je <u>zeker</u> het XML-bestand kiest dat werd geëxporteerd vanuit VCD-db..',
'XML_RESULTS'         	=> 'XML upload resultaten.',
'XML_RESULTS2'          => 'Dit zijn de resultaten van je XML import.<br/>In totaal werden %d films geïmporteerd.',


/* Add from DVD Empire */
'EM_INFO'         		=> 'Informatie van AdultDVDEmpire.com ....',
'EM_DESC'         		=> 'DVDEmpire beschrijving',
'EM_SUBCAT'         	=> 'Adult categorieën',
'EM_DETAILS'         	=> 'Adultdvdempire.com details',
'EM_STARS'         		=> 'Pornstars',
'EM_NOTICE'         	=> 'Acteurs met rode markering zijn op dit moment niet beschikbaar in de VCD-DB.
                  Maar je kunt hun namen checken, waarna ze automatisch worden toegevoegd aan VCD-db
                  and associated with this movie.',
'EM_FETCH'         		=> 'Ook ophalen',

/* Loan System */
'LOAN_MOVIES'         	=> 'Films om uit te lenen',
'LOAN_TO'         		=> 'Film uitlenen aan',
'LOAN_ADDUSERS'         => 'Voeg gebruikers toe om uitleenen te verlengen',
'LOAN_NEWUSER'          => 'Nieuwe leen-gebruiker',
'LOAN_REGISTERUSER'     => 'Voeg nieuwe leen-gebruiker toe',
'LOAN_NAME'        		=> 'Naam',
'LOAN_SELECT'         	=> 'Selecteer leen-gebruiker',
'LOAN_MOVIELOANS'       => 'Geleende films ...',
'LOAN_REMINDER'         => 'Stuur herinnering',
'LOAN_HISTORY'          => 'Leen verleden',
'LOAN_HISTORY2'         => 'Zie: leen verleden',
'LOAN_SINCE'         	=> 'Vanaf',
'LOAN_TIME'         	=> 'Tijd sinds',
'LOAN_RETURN'         	=> 'Retour copy',
'LOAN_SUCCESS'          => 'Films met succes geleend',
'LOAN_OUT'         		=> 'Niet terug',
'LOAN_DATEIN'           => 'Datum in',
'LOAN_DATEOUT'         	=> 'Datum uit',
'LOAN_PERIOD'         	=> 'Leen periode',
'LOAN_BACK'         	=> 'Terug naar leen index',
'LOAN_DAY'         		=> 'dag',
'LOAN_DAYS'         	=> 'dagen',
'LOAN_TODAY'        	=> 'vanaf vandaag',


/* RSS */
'RSS'           		=> 'RSS Feeds',
'RSS_TITLE'         	=> 'RSS feeds van mijn kennissen/vrienden VCD-DB sites',
'RSS_SITE'         		=> 'RSS Site feed',
'RSS_USER'         		=> 'RSS Gebruiker feed',
'RSS_VIEW'         		=> 'Bekijk RSS feed',
'RSS_ADD'         		=> 'Toevoegen nieuwe feed',
'RSS_NOTE'        		=> 'Enter de <strong>excacte url</strong> van de VCD database van je vriend/kennis.<br/>
                  Indien RSS beschikbaar is op de site van je vriend/kennis, dan kun je  de
                  feeds waarin je geïnteresseerd bent selecteren en weergeven op jouw pagina.',
'RSS_FETCH'        		=> 'Ophalen RSS Lijst',
'RSS_NONE'         		=> 'Geen RSS feeds toegevoegd.',
'RSS_FOUND'        		=> 'De volgende RSS feeds werden aangetroffen, selecteer the feeds om toe te voegen:',
'RSS_NOTFOUND'          => 'Geen feeds gevonden in opgegeven locatie',


/* Wishlist */
'W_ADD'            	 	=> 'Voeg toe aan mijn verlanglijstje',
'W_ONLIST'         		=> 'Op je verlanglijstje',
'W_EMPTY'         		=> 'Je verlanglijstje is leeg',
'W_OWN'					=> 'I own a copy of this movie',
'W_NOTOWN'				=> 'I do not own a copy of this movie',


/* Comments */
'C_COMMENTS'        	=> 'Commentaar',
'C_ADD'            		=> 'Nieuw commentaar toevoegen',
'C_NONE'        		=> 'Geen commentaren beschikbaar',
'C_TYPE'         		=> 'Type je nieuw commentaar',
'C_YOUR'         		=> 'Jouw commentaar',
'C_POST'         		=> 'Plaats commentaar',
'C_ERROR'         		=> 'Je bent ingelogd om een commentaar te plaatsen',


/* Pornstars */
'P_NAME'        		=> 'Naam',
'P_WEB'            		=> 'Website',
'P_MOVIECOUNT'          => 'Movie aantal',



/* Seen List */
'S_SEENIT'        		 => 'Ik heb \'m gezien',
'S_NOTSEENIT'       	 => 'Ik heb \'m niet gezien',
'S_SEENITCLICK'          => 'klik markeren gezien',
'S_NOTSEENITCLICK'       => 'klik markeren niet gezien',

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
'MAIL_RETURNTOPIC'		=> 'Loan reminder',
'MAIL_RETURNMOVIES1'     => '%s, Ik wil je eraan herinneren mijn films terug te bezorgen.\n
                     Je hebt volgende films nog steeds:\n\n',
'MAIL_RETURNMOVIES2'     => 'Bezorg de disks alsjeblieft zo snel mogelijk terug\n Groetjes %s \n\n
                     nb. Dit is een automatisch e-mail bericht van het VCD-db systeem (http://vcddb.konni.com)',
'MAIL_NOTIFY'            => '<strong>Nieuwe film toegevoegd aan VCD-db</strong><br/>
                      Klik <a href="%s/?page=cd&vcd_id=%s">hier</a> om meer te zien ..
                      <p>nb. Dit is een automatisch e-mail bericht van het VCD-db systeem (http://vcddb.konni.com)</p>',
'MAIL_REGISTER'          => '%s, Registratie ok, je kunt nu inloggen in VCD-db.\n\nJe gebruikersnaam is %s en je wachtwoord is
                     %s.\n\nJe kunt altijd je wachtwoord veranderen nadat je hebt ingelogd.\n
                     Klik <a href="%s" target="_new">hier</a> om naar de VCD-db website te gaan.',


/* Player */
'PLAYER'         		=> 'Player',
'PLAYER_PATH'           => 'Pad',
'PLAYER_PARAM'          => 'Parameters',
'PLAYER_NOTE'           => 'Voer het volledige pad in naar de movieplayer op je systeem.
                     Je movieplayer dient parameters te ondersteunen zoals
                     BSPlayer voor Win32 of MPlayer voor Linux.<br/> BSPlayer kun je
                     <a href="http://www.bsplayer.org" target="_new">hier</a> gratis downloaden
                     en MPlayer <a href="http://www.MPlayerHQ.hu" target="_new">hier</a>.',



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
'X_CONTAINS'         => 'bevat',
'X_GRADE'         => 'IMDB rating meer dan',
'X_ANY'            => 'Willekeurig',
'X_TRYAGAIN'         => 'Probeer opnieuw',
'X_PROCEED'          => 'Doorgaan',
'X_SELECT'          => 'Selecteren',
'X_CONFIRM'          => 'Bevestigen',
'X_CANCEL'          => 'Afbreken',
'X_ATTENTION'          => 'Attentie!',
'X_STATUS'          => 'Status',
'X_SUCCESS'          => 'Succes',
'X_FAILURE'          => 'Fout',
'X_YES'          => 'Ja',
'X_NO'             => 'Nee',
'X_SHOWMORE'          => 'Toon meer',
'X_SHOWLESS'          => 'Toon minder',
'X_NEW'          => 'Nieuw',
'X_CHANGE'          => 'Verander',
'X_DELETE'          => 'Verwijder',
'X_UPDATE'          => 'Update',
'X_SAVEANDCLOSE'       => 'Bewaren en sluiten',
'X_CLOSE'          => 'Sluiten',
'X_EDIT'          => 'Bewerk',
'X_RESULTS'          => 'Resultaat',
'X_LATESTMOVIES'       => 'laatste films',
'X_LATESTTV'          => 'laatste TV shows',
'X_LATESTBLUE'          => 'laatste X-rated',
'X_MOVIES'          => 'films',
'X_NOCATS'          => 'Geen films werden toegevoegd.',
'X_NOUSERS'          => 'Geen actieve gebruikers',
'X_KEY'             => 'Toets',
'X_SAVENEXT'          => 'Opslaan en bewerk volgende',
'X_SAVE'             => 'Opslaan',
'X_SEEN'             => 'Gezien',
'X_TOGGLE'				=> 'Toggle preview',
'X_TOGGLE_ON'			=> 'on',
'X_TOGGLE_OFF'			=> 'off'


);

?>