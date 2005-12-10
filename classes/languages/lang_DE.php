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
      German language file 
      Big Thanks to Stefan Schackmann for the translation !
   */ 


   
$_ = array( 

/* Language Identifier */ 
'LANG_TYPE'          	=> 'DE', 
'LANG_NAME'          	=> 'Deutsch', 
'LANG_CHARSET'			=> 'iso-8859-1',

/* Menu system */ 
'MENU_MINE'         	=> 'Meine VCD-DB', 
'MENU_SETTINGS'     	=> 'Meine Einstellungen', 
'MENU_MOVIES'       	=> 'Meine Filme', 
'MENU_ADDMOVIE'       	=> 'Film hinzuf&uuml;gen', 
'MENU_LOANSYSTEM'      	=> 'Meine Verleihliste', 
'MENU_WISHLIST'       	=> 'Meine Wunschliste', 
'MENU_CATEGORIES'       => 'Film Kategorien', 
'MENU_RSS'          	=> 'Meine Rss Feeds', 
'MENU_CONTROLPANEL'     => 'VCD-db Admin', 
'MENU_REGISTER'       	=> 'Registrieren', 
'MENU_LOGOUT'          	=> 'Ausloggen', 
'MENU_SUBMIT'         	=> 'Senden', 
'MENU_TOPUSERS'         => 'Aktivste User', 
'MENU_WISHLISTPUBLIC'	=> 'Alle Wunschlisten',
'MENU_STATISTICS'		=> 'Statistiken',

/* Login */ 
'LOGIN'					=> 'Login', 
'LOGIN_USERNAME'        => 'Username', 
'LOGIN_PASSWORD'        => 'Password', 
'LOGIN_REMEMBER'        => 'Erinnern', 
'LOGIN_INFO'            => '<b>Nur</b> zum &Auml;ndern des Passwortes ausf&uuml;llen', 

/* Register */ 
'REGISTER_TITLE'      	=> 'Registrierung', 
'REGISTER_FULLNAME'     => 'Voller Name', 
'REGISTER_EMAIL'        => 'Email', 
'REGISTER_AGAIN'        => 'Passwort (wiederholen)', 
'REGISTER_DISABLED'     => 'Zur Zeit keine Registrierung m&ouml;glich', 
'REGISTER_OK' 			=> 'Registrierung war erfolgreich, Anmeldung ist freigeschaltet.', 

/* User Properties */ 
'PRO_NOTIFY'            => 'Benachrichtigung &uuml;ber neu hinzugef&uuml;gte Filme senden?',
'PRO_SHOW_ADULT'        => 'Nicht Jugendfreie Inhalte anzeigen?',
'PRO_RSS'         	    => 'RSS feed meiner Filme erlauben?',
'PRO_EMAIL'  		    => 'Emaile mir, wenn es neue Filme gibt?', 
'PRO_WISHLIST'          => 'Anderen Usern meine Wunschliste zeigen?',
'PRO_USE_INDEX'         => 'Index Feld f&uuml;r eigene Medien ID\'s verwenden?',
'PRO_SEEN_LIST'         => 'Eigene \'Gesehene Filme\' Liste verwalten',
'PRO_PLAYOPTION'        => 'Zus&auml;tzliche Abspieloptionen verwenden',
'PRO_NFO' 				=> 'Benutzen Sie NFO Akte?',


/* User Settings */
'SE_PLAYER'             => 'Player Einstellungen', 
'SE_OWNFEED'            => 'Eigene RSS feeds anzeigen', 
'SE_CUSTOM'             => 'Hauptseite anpassen', 
'SE_SHOWSTAT'           => 'Statistiken anzeigen', 
'SE_SHOWSIDE'           => 'Neue Filme seitlich anzeigen', 
'SE_SELECTRSS'          => 'RSS feeds ausw&auml;hlen', 
'SE_PAGELOOK' 			=> 'Web layout',
'SE_PAGEMODE' 			=> 'Select default template:',

/* Search */ 
'SEARCH'          		=> 'Suche', 
'SEARCH_TITLE'          => 'nach Titel', 
'SEARCH_ACTOR'          => 'nach Schauspieler', 
'SEARCH_DIRECTOR'       => 'nach Regisseur', 
'SEARCH_RESULTS'        => 'Sucheergebnisse', 
'SEARCH_EXTENDED'       => 'Details anzeigen', 
'SEARCH_NORESULT'       => 'Suche war erfolglos', 

/* Movie categories*/ 
'CAT_ACTION'          	=> 'Action', 
'CAT_ADULT'          	=> 'Nicht Jugendfrei', 
'CAT_ADVENTURE'       	=> 'Abenteuer', 
'CAT_ANIMATION'       	=> 'Animation', 
'CAT_ANIME'         	=> 'Anime / Manga', 
'CAT_COMEDY'          	=> 'Comedy', 
'CAT_CRIME'          	=> 'Krimi', 
'CAT_DOCUMENTARY'       => 'Dokumentation', 
'CAT_DRAMA'          	=> 'Drama', 
'CAT_FAMILY'          	=> 'Familienfilm', 
'CAT_FANTASY'          	=> 'Fantasie', 
'CAT_FILMNOIR'          => 'Film Noir', 
'CAT_HORROR'          	=> 'Horror', 
'CAT_JAMESBOND'       	=> 'James Bond', 
'CAT_MUSICVIDEO'       	=> 'Musik Video', 
'CAT_MUSICAL'          	=> 'Musical', 
'CAT_MYSTERY'          	=> 'Mystery', 
'CAT_ROMANCE'          	=> 'Romanze', 
'CAT_SCIFI'          	=> 'Sci-Fi', 
'CAT_SHORT'          	=> 'Kurzfilm', 
'CAT_THRILLER'          => 'Thriller', 
'CAT_TVSHOWS'          	=> 'TV Show', 
'CAT_WAR'          		=> 'Kriegsfilm', 
'CAT_WESTERN'           => 'Western', 
'CAT_XRATED'          	=> 'Porno', 
'CAT_26'				=> 'Eastern',

/* Movie Listings */ 
'M_MOVIE'          		=> 'Der Film', 
'M_ACTORS'       	  	=> 'Besetzung', 
'M_CATEGORY'         	=> 'Kategorie', 
'M_YEAR'         		=> 'Produktionsjahr', 
'M_COPIES'        	 	=> 'Kopiën', 
'M_FROM'          		=> 'Von', 
'M_TITLE'         	 	=> 'Titel', 
'M_ALTTITLE'          	=> 'Alternativer Titel', 
'M_GRADE'        	 	=> 'Bewertung', 
'M_DIRECTOR'          	=> 'Regisseur', 
'M_COUNTRY'         	=> 'Produktionsland', 
'M_RUNTIME'          	=> 'Spieldauer', 
'M_MINUTES'         	=> 'Minuten', 
'M_PLOT'         	 	=> 'Inhaltsangabe', 
'M_NOPLOT'       	  	=> 'Keine Inhaltsangabe verf&uuml;gbar', 
'M_COVERS'          	=> 'CD Cover', 
'M_NOCOVERS'         	=> 'Kein CD Cover verf&uuml;gbar', 
'M_AVAILABLE'          	=> 'Verf&uuml;gbare Kopien', 
'M_MEDIA'        	 	=> 'Medium', 
'M_NUM'          		=> 'Num CD\'s', 
'M_DATE'         	 	=> 'Datum', 
'M_OWNER'        	 	=> 'Eigent&uuml;mer', 
'M_NOACTORS'        	=> 'Keine Schauspielerliste verf&uuml;gbar', 
'M_INFO'         		=> 'Film info', 
'M_DETAILS'         	=> 'Details meiner Kopie', 
'M_MEDIATYPE'           => 'Media type', 
'M_COMMENT'         	=> 'Kommentar', 
'M_PRIVATE'         	=> 'Als privat kennzeichnen?', 
'M_SCREENSHOTS'         => 'Screenshots', 
'M_NOSCREENS'           => 'Kein Screenshot verf&uuml;gbar', 
'M_SHOW'        	 	=> 'Anzeigen', 
'M_HIDE'        		=> 'Verstecken', 
'M_CHANGE'       	 	=> 'Information &auml;ndern', 
'M_BYCAT'        	 	=> 'Titel nach Kategorie', 
'M_CURRCAT'         	=> 'Aktuelle Kategorie', 
'M_TEXTVIEW'         	=> 'Text Liste', 
'M_IMAGEVIEW'         	=> 'Bild Liste', 
'M_MINEONLY'			=> 'Nur meine Filme anzeigen',
'M_SIMILAR'				=> '&Auml;hnliche Filme',
'M_MEDIAINDEX'			=> 'Medien ID',

/* IMDB */ 
'I_DETAILS'         	=> 'IMDB Details', 
'I_PLOT'         		=> 'Inhaltsangabe', 
'I_GALLERY'         	=> 'Foto &Uuml;bersicht', 
'I_TRAILERS'         	=> 'Trailers', 
'I_LINKS'        	 	=> 'IMDB Links', 
'I_NOT'           	 	=> 'Keine IMDB Info verf&uuml;gbar', 

/* DVD Specific */
'DVD_REGION'			=> 'Region',
'DVD_FORMAT'			=> 'Format',
'DVD_ASPECT'			=> 'Aspect ratio',
'DVD_AUDIO'				=> 'Audio',
'DVD_SUBTITLES'			=> 'Subtitles',

/* My Movies */ 
'MY_EXPORT'          	=> 'Daten exportieren',
'MY_EXCEL'          	=> 'als Excel exportieren', 
'MY_XML'          		=> 'als XML exportieren', 
'MY_XMLTHUMBS'          => 'Kleinbilder als XML exportieren', 
'MY_ACTIONS'         	=> 'Meine Aufgaben', 
'MY_JOIN'         		=> 'CD zusammenf&uuml;hren', 
'MY_JOINMOVIES'         => 'Film zusammenf&uuml;hren', 
'MY_JOINSUSER'         	=> 'Nutzer w&auml;len', 
'MY_JOINSMEDIA'         => 'Media Typ w&auml;hlen', 
'MY_JOINSCAT'         	=> 'Kategorie w&auml;hlen', 
'MY_JOINSTYPE'        	=> 'Aufgabe w&auml;hlen', 
'MY_JOINSHOW'        	=> 'Ergebnis anzeigen', 
'MY_NORESULTS'          => 'Keine Ergebnisse verf&uuml;gbar', 
'MY_TEXTALL'			=> 'Druckansicht (Text)',
'MY_PWALL'        		=> 'Druckansicht (Alles)', 
'MY_PWMOVIES'         	=> 'Druckansicht (Filme)', 
'MY_PWTV'         		=> 'Druckansicht (Tv Shows)', 
'MY_PWBLUE'         	=> 'Druckansicht (Blue movies)', 
'MY_J1'            		=> 'Filme, die ich habe, aber der User nicht', 
'MY_J2'           	 	=> 'Filme, die der User hat, aber ich nicht', 
'MY_J3'           	 	=> 'Filme, die ich und der User haben', 
'MY_OVERVIEW'         	=> '&Uuml;bersicht', 
'MY_INFO'        	 	=> 'Auf diesen Seiten steht alles &uuml;ber meine Filme. 
                  Auf der rechten Seite sind die m&ouml;glichen Aktionen aufgelistet. 
                  Ausserdem gibt es eine M&ouml;glichkeit, die Filmkolektion als Excel Datei zur
		  Druckausgabe oder als XML zum Backup oder Austausch zu exporteren.', 
'MY_KEYS'        		=> 'Eigene ID\'s editieren', 
'MY_SEENLIST'        	=> 'Meine \'gesehen\' Liste editieren', 
'MY_HELPPICKER'         => 'Einen Film vorschlagen', 
'MY_HELPPICKERINFO'     => 'Was guckst du?<br/>VCD-db kramt dir einen Film \'raus.<br/> 
                  Dazu gibt es noch Filter, welche die Suche gezielt eingrenzen.', 
'MY_FIND'        	 	=> 'Finde einen Film', 
'MY_NOTSEEN'        	=> 'Nur \'ungesehene\' Filme vorschlagen', 
'MY_FRIENDS'			=> 'Leute, die sich meine CD\'s ausleihen',

/* Manager window */ 
'MAN_BASIC'         	=> 'Algemeine Info',
'MAN_IMDB'       		=> 'IMDB Info', 
'MAN_EMPIRE'            => 'DVDEmpire Info', 
'MAN_COPY'          	=> 'Meine Kopie', 
'MAN_COPIES'            => 'Meine Kopien', 
'MAN_NOCOPY'            => 'Ich habe gar keine Kopie', 
'MAN_1COPY'          	=> 'Kopie', 
'MAN_ADDACT'          	=> 'Schauspieler hinzuf&uuml;gen',
'MAN_ADDTODB'           => 'Schauspieler zur DB hinzuf&uuml;gen', 
'MAN_SAVETODB'          => 'In die DB speichern', 
'MAN_SAVETODBNCD'       => 'In die DB und CD speichern', 
'MAN_INDB'          	=> 'Schauspieler in DB', 
'MAN_SEL'         		=> 'Ausgew&auml;hlte Schauspieler', 
'MAN_STARS' 			=> 'Stars',
'MAN_BROWSE'			=> 'Browse for file location',

/* Add movies */ 
'ADD_INFO'          	=> 'Folgende Datenquellen stehen zur Verf&uuml;gung', 
'ADD_IMDB'          	=> 'Einen Film aus der Internet Movie Database IMDB ausw&auml;hlen', 
'ADD_IMDBTITLE'       	=> 'Suchbegriff', 
'ADD_MANUAL'          	=> 'Daten manuell eingeben', 
'ADD_LISTED'          	=> 'Einen Film aus der lokalen Datenbank ausw&auml;hlen', 
'ADD_XML'          		=> 'Eine XML-Datei importieren', 
'ADD_XMLFILE'           => 'XML-Datei ausw&auml;hlen', 
'ADD_XMLNOTE'           => '(Hinweis: Nur XML-Dateien, die von der VCDDB exportiert wurden (oder exakt deren Format haben) 
                  k&ouml;nnen hier importiert werden. Die Export-Funktion steht unter \'Meine Filme\' zur Verf&uuml;gung.
		  Manuelles editieren der XML-Dateien sollte vermieden werden.)', 
'ADD_MAXFILESIZE'       => 'Max Dateigr&ouml;&szlig;e', 
'ADD_DVDEMPIRE'       	=> 'Von DVD Empire (X-rated/Porno Filme) ausw&auml;hlen', 
'ADD_LISTEDSTEP1'       => 'Schritt 1<br/>Den gew&uuml;nschten Titel ausw&auml;hlen.', 
'ADD_LISTEDSTEP2'       => 'Schritt 2<br/>Das passende Medium w&auml;hlen.', 
'ADD_INDB'          	=> 'Filme in VCD-DB', 
'ADD_SELECTED'          => 'Ausgew&auml;hlte Titel', 
'ADD_INFOLIST'          => 'Auswahl durch Doppel-klick auf den Titel oder mit den Pfeiltasten.<br/>Eingabe von Buchstaben springt 
		  zum ersten Titel mit diesem Anfangsbuchstaben.', 
'ADD_NOTITLES'          => 'Kein anderer Nutzer hat Filme hinzugef&uuml;gt', 

/* Add from XML */ 
'XML_CONFIRM'           => 'XML Upload best&auml;tigen', 
'XML_CONTAINS'          => 'XML-Datei enth&auml;lt %d Filme.', 
'XML_INFO1'          	=> '\'Best&auml;tigen\' um die Filme in die Datenbank einzutragen oder<br/> 
                  \'Cancel\' zum Abbrechen.', 
'XML_INFO2'          	=> 'Wenn die Coverbilder mit abgespeichert werden sollen, <b>m&uuml;&szlig;</b> die entsprechende XML-Datei
		  vorliegen. Nachtr&auml;gliches importieren der Cover-Bilder ist <b>nicht</b> m&ouml;glich.<br/> 
                  Wenn die XML-Datei vorliegt, einfach das K&auml;stchen hierunter ankreuzen und im n&auml;chsten Schritt werden
		  die Coverbilder eingelesen.', 
'XML_THUMBNAILS'      	=> 'Coverbilder aus der XML-Datei hunzuf&uuml;gen', 
'XML_LIST'         	=> 'Vollst&auml;ndige Liste der Filme aus der XML-Datei', 
'XML_ERROR'         	=> 'Keine Filme in der XML-Datei gefunden.<br/>Datei ist m&ouml;glicherweise besch&auml;digt oder einfach nur leer. 
                     <br/>Verwenden sie eine von VCD-db exportierte XML-Datei..', 
'XML_RESULTS'         	=> 'XML Upload Ergebnisse', 
'XML_RESULTS2'          => 'Dies sind die Ergebnisse vom XML Import.<br/>Es wurden insgesamt %d Filme eingef&uuml;gt.', 


/* Add from DVD Empire */ 
'EM_INFO'         		=> 'Informationen von AdultDVDEmpire.com ....', 
'EM_DESC'         		=> 'DVDEmpire Beschreiebung', 
'EM_SUBCAT'         	=> 'Erwachsenen Kategorien', 
'EM_DETAILS'         	=> 'Adultdvdempire.com Details', 
'EM_STARS'       	  	=> 'Pornostars', 
'EM_NOTICE'         	=> 'Rot markierte Darsteller sind momentan nicht in der VCD-DB eingetragen. 
                  Durch Auswahl der Namen werden sie der Datenbank hinzugef&uuml;gt und mit diesem Film verbunden.', 
'EM_FETCH'       	  	=> 'Auch hinzuf&uuml;gen', 

/* Loan System */ 
'LOAN_MOVIES'         	=> 'Diese Filme ausleihen', 
'LOAN_TO'         		=> 'an', 
'LOAN_ADDUSERS'         => 'Es m&uuml;ssen Ausleiher zum System hinzugef&uuml;gt werden', 
'LOAN_NEWUSER'          => 'Neue Ausleiher', 
'LOAN_REGISTERUSER'     => 'Neuen Ausleiher hinzuf&uuml;gen', 
'LOAN_NAME'        		=> 'Name', 
'LOAN_SELECT'         	=> 'Ausleiher w&auml;hlen', 
'LOAN_MOVIELOANS'       => 'Verliehene Filme ...', 
'LOAN_REMINDER'         => 'Erinnerung senden', 
'LOAN_HISTORY'          => 'Verleihbericht', 
'LOAN_HISTORY2'         => 'Seit', 
'LOAN_SINCE'         	=> 'Seit', 
'LOAN_TIME'         	=> 'Verliehen seit', 
'LOAN_RETURN'         	=> 'Film zur&uuml;ck', 
'LOAN_SUCCESS'          => 'Film erfolgreich ausgeliehen??hmpf', 
'LOAN_OUT'      	   	=> 'Nicht zur&uuml;ck', 
'LOAN_DATEIN'           => 'Zur&uuml;ck am', 
'LOAN_DATEOUT'         	=> 'Ausgeliehen am', 
'LOAN_PERIOD'         	=> 'Verleihdauer', 
'LOAN_BACK'         	=> 'Verleihindex', 
'LOAN_DAY'         		=> 'Tag', 
'LOAN_DAYS'         	=> 'Tage', 
'LOAN_TODAY'        	=> 'Ab heute', 


/* RSS */ 
'RSS'           	=> 'RSS Feeds', 
'RSS_TITLE'        	=> 'RSS feeds von den VCD-DB sites meiner Freunde', 
'RSS_SITE'         	=> 'RSS Site feed', 
'RSS_USER'         	=> 'RSS User feed', 
'RSS_VIEW'         	=> 'RSS feed anzeigen', 
'RSS_ADD'         	=> 'RSS feed hinzuf&uuml;gen', 
'RSS_NOTE'        	=> 'Gebe die <strong>genaue Addresse</strong> der VCD Gegenstelle an.<br/> 
                  Wenn dort RSS feeds eingeschltet sind, sind diese Feeds auch hier verf&uuml;gbar
                  und werden natuerlich auch angezeigt.', 
'RSS_FETCH'        	=> 'RSS Liste holen', 
'RSS_NONE'         	=> 'Keine RSS feeds hinzugef&uumkl;gt.', 
'RSS_FOUND'        	=> 'Folgende RSS feeds wurden gefunden, welche sollen hinzugef&uuml;gt werden:', 
'RSS_NOTFOUND'          => 'Kein RSS feed gefunden', 


/* Wishlist */ 
'W_ADD'            	=> 'Zu meiner Wunschliste hinzuf&uuml;gen', 
'W_ONLIST'         	=> 'Auf der Wunschliste', 
'W_EMPTY'         	=> 'Wunschliste ist leer', 
'W_OWN'			=> 'Ich habe diesen Film',
'W_NOTOWN'		=> 'Ich habe diesen Film nicht',


/* Comments */ 
'C_COMMENTS'        	=> 'Kommentar', 
'C_ADD'            	=> 'Kommentar hinzuf&uuml;gen', 
'C_NONE'        	=> 'Kein kommentar eingetragen', 
'C_TYPE'         	=> 'Neuen Kommentar eintragen', 
'C_YOUR'         	=> 'Dein Kommentar', 
'C_POST'         	=> 'Kommentar senden', 
'C_ERROR'         	=> 'Nur eingeloggte User k&ouml;nnen kommentieren.', 


/* Pornstars */ 
'P_NAME'        	=> 'Name', 
'P_WEB'            	=> 'Website', 
'P_MOVIECOUNT'          => 'Anzahl der Filme', 



/* Seen List */
'S_SEENIT'		=> 'Schon gesehen', 
'S_NOTSEENIT'       	=> 'Nicht gesehen', 
'S_SEENITCLICK'         => 'Als gesehen markieren', 
'S_NOTSEENITCLICK'      => 'Als ungesehen markierren', 

/* Mail messages */ 
'MAIL_RETURNTOPIC'		=> 'Loan reminder',
'MAIL_RETURNMOVIES1'    => 'Hallo %s, wollte dich nur mal erinnern, mir meine Filme zur&uuml;ckzugeben.\n 
                     	Das ist die Liste:\n\n', 
'MAIL_RETURNMOVIES2'    => 'Hey %s, Jetzt sieh mal zu dass die Filme an Land kommen\n\n ps.: automatische Mail mit vordefiniertem Text', 
'MAIL_NOTIFY'           => '<strong>Neuer File in der VCD-db</strong><br/> 
                      <a href="%s/?page=cd&vcd_id=%s">Details</a> 
                      <p>nb. Das ist eine automatische Email der VCD-DB (vcddb.konni.com)</p>', 
'MAIL_REGISTER'         => '%s, Registrierung erfolgreich.\nUsername ist %s , Passwort ist %s.\n\n
		      Nach dem Einloggen ist das Passwort jederzeit &auml;nderbar.\n 
                      <a href="%s" target="_new">Hier</a> gehts zur VCD-DB website.', 


/* Player */ 
'PLAYER'         	=> 'Player', 
'PLAYER_PATH'           => 'Pfad', 
'PLAYER_PARAM'          => 'Parameter', 
'PLAYER_NOTE'		=> 'Absoluter Pfad des Players. 
                     Der Ploayer muss Commandline-Argumente akzeptieren wie z.B. BSPlayer f&uuml;r Win32 oder MPlayer f&uuml;r Linux.<br/>
		     Links: <a href="http://www.bsplayer.org" target="_new">BSPlayer gratis</a> oder 
		     <a href="http://www.MPlayerHQ.hu" target="_new">MPlayer gratis (win & lin, recommended)</a>.', 



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
'X_CONTAINS'		=> 'enth&auml;lt', 
'X_GRADE'		=> 'IMDB Bewertung h&ouml;her als', 
'X_ANY'			=> 'Alle', 
'X_TRYAGAIN'		=> 'Nochmal Versuchen', 
'X_PROCEED'		=> 'Weiter', 
'X_SELECT'		=> 'Auswahl', 
'X_CONFIRM'		=> 'OK', 
'X_CANCEL'		=> 'Abbrechen', 
'X_ATTENTION'		=> 'Vorsicht', 
'X_STATUS'		=> 'Status', 
'X_SUCCESS'		=> 'Erfolgreich', 
'X_FAILURE'		=> 'Fehler', 
'X_YES'			=> 'Ja', 
'X_NO'			=> 'Nein', 
'X_SHOWMORE'		=> 'mehr Anzeigen', 
'X_SHOWLESS'		=> 'weniger Anzeigen', 
'X_NEW'			=> 'Neu', 
'X_CHANGE'		=> '&Auml;ndern', 
'X_DELETE'		=> 'L&ouml;schen', 
'X_UPDATE'		=> 'Update', 
'X_SAVEANDCLOSE'	=> 'Speichern und Schliessen', 
'X_CLOSE'		=> 'Schliessen', 
'X_EDIT'		=> 'Editieren', 
'X_RESULTS'		=> 'Ergebniss', 
'X_LATESTMOVIES'	=> 'letzte Filme', 
'X_LATESTTV'		=> 'letzte TV Shows', 
'X_LATESTBLUE'          => 'letzte Pornos', 
'X_MOVIES'		=> 'Films',
'X_NOCATS'		=> 'Keine Filme verf&uuml;gbar.', 
'X_NOUSERS'		=> 'Keine aktiven User', 
'X_KEY'			=> 'Key', 
'X_SAVENEXT'		=> 'Sichern SpeicherNext??', 
'X_SAVE'		=> 'Speichern', 
'X_SEEN'		=> 'Gesehen' 


); 

?>
