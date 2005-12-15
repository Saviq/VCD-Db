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
 * @author   Michael Sawicz <michal@sawicz.net>
 * @package Language
 * @version $Id$
 */
 
?>
<? 
	/** 
		Polish language file
	
	*/
	
$_ = array(

'LANG_TYPE' 			=> 'PL',
'LANG_NAME' 			=> 'Polski',
'LANG_CHARSET'			=> 'UTF-8',

/* Language Identifier */
'MENU_MINE' 			=> 'Moje menu',
'MENU_SETTINGS' 		=> 'Moje ustawienia',
'MENU_MOVIES' 			=> 'Moje filmy',
'MENU_ADDMOVIE' 		=> 'Dodaj nowy film',
'MENU_LOANSYSTEM' 		=> 'Wypożyczanie',
'MENU_WISHLIST' 		=> 'Moja lista życzeń', 
'MENU_CATEGORIES'		=> 'Gatunki filmowe',
'MENU_RSS' 				=> 'Moje kanały RSS',
'MENU_CONTROLPANEL' 	=> 'Panel administracyjny',
'MENU_REGISTER' 		=> 'Zarejestruj',
'MENU_LOGOUT' 			=> 'Wyloguj',
'MENU_SUBMIT' 			=> 'Prześlij',
'MENU_TOPUSERS' 		=> 'Najlepsi użytkownicy',
'MENU_WISHLISTPUBLIC' 	=> 'Listy życzeń innych',
'MENU_STATISTICS' 		=> 'Statystyki',

/* Login */
'LOGIN' 				=> 'Zaloguj',
'LOGIN_USERNAME' 		=> 'Użytkownik',
'LOGIN_PASSWORD' 		=> 'Hasło',
'LOGIN_REMEMBER' 		=> 'Pamiętaj mnie',
'LOGIN_INFO' 			=> 'Zostaw puste, jeśli <b>not</b> chcesz zmieniać hasła',

/* Register */
'REGISTER_TITLE' 		=> 'Rejestracja',
'REGISTER_FULLNAME' 	=> 'Imię',
'REGISTER_EMAIL' 		=> 'E-mail',
'REGISTER_AGAIN' 		=> 'Potwierdzenie hasła',
'REGISTER_DISABLED' 	=> 'Przykro nam, administrator wyłączył rejestrację użytkowników.',
'REGISTER_OK' 			=> 'Rejestracja udana, możesz się zalogować',

/* User Properties */
'PRO_NOTIFY' 			=> 'Wyślij do mnie e-mail kiedy dodany nowy film?',
'PRO_SHOW_ADULT' 		=> 'Pokazuj zawartość dla dorosłych?',
'PRO_RSS' 				=> 'Włącz kanał news moich filmów?',
'PRO_WISHLIST'			=> 'Czy inni mogą zobaczyć moją listę życzeń?',
'PRO_USE_INDEX' 		=> 'Używaj numerów wierszy jako numerów ID?',
'PRO_SEEN_LIST' 		=> 'Pilnuj, które filmy widziałem',
'PRO_PLAYOPTION' 		=> 'Użyj opcje odtwarzacza klienta',
'PRO_NFO' 				=> 'Używaj plików NFO?',

/* User Settings */
'SE_PLAYER' 			=> 'Ustawienia odtwarzacza',
'SE_OWNFEED' 			=> 'Zobacz swój kanał RSS',
'SE_CUSTOM' 			=> 'Ustaw stronę główną',
'SE_SHOWSTAT' 			=> 'Pokaż statystyki',
'SE_SHOWSIDE' 			=> 'Pokaż nowe filmy po prawej',
'SE_SELECTRSS' 			=> 'Wybierz kanały RSS',
'SE_PAGELOOK' 			=> 'Uk³ad strony',
'SE_PAGEMODE' 			=> 'Wybierz domy¶lny schemat:',

/* Search */
'SEARCH' 				=> 'Szukaj',
'SEARCH_TITLE'			=> 'Tytułu',
'SEARCH_ACTOR' 			=> 'Aktora',
'SEARCH_DIRECTOR' 		=> 'Reżysera',
'SEARCH_RESULTS' 		=> 'Wyniki szukania',
'SEARCH_EXTENDED' 		=> 'Szukanie zaawansowane',
'SEARCH_NORESULT' 		=> 'Nie znaleziono filmów',

/* Movie categories*/
'CAT_ACTION' 			=> 'Film akcji',
'CAT_ADULT' 			=> 'Dla dorosłych',
'CAT_ADVENTURE'		 	=> 'Przygodowy',
'CAT_ANIMATION' 		=> 'Animowany',
'CAT_ANIME' 			=> 'Anime / Manga',
'CAT_COMEDY' 			=> 'Komedia',
'CAT_CRIME' 			=> 'Kryminał',
'CAT_DOCUMENTARY' 		=> 'Dokumentalny',
'CAT_DRAMA' 			=> 'Dramat',
'CAT_FAMILY' 			=> 'Rodzinny',
'CAT_FANTASY' 			=> 'Fantasy',
'CAT_FILMNOIR' 			=> 'Czarna komedia',
'CAT_HORROR' 			=> 'Horror',
'CAT_JAMESBOND' 		=> 'James Bond',
'CAT_MUSICVIDEO' 		=> 'Teledysk',
'CAT_MUSICAL' 			=> 'Musical',
'CAT_MYSTERY' 			=> 'Zagadka',
'CAT_ROMANCE' 			=> 'Romans',
'CAT_SCIFI' 			=> 'Science-Fiction',
'CAT_SHORT' 			=> 'Krótki metraż',
'CAT_THRILLER' 			=> 'Thiller',
'CAT_TVSHOWS' 			=> 'Serial',
'CAT_WAR' 				=> 'Wojenny',
'CAT_WESTERN' 			=> 'Western',
'CAT_XRATED' 			=> 'Erotyczny',

/* Movie Listings */
'M_MOVIE' 				=> 'Film',
'M_ACTORS' 				=> 'Obsada',
'M_CATEGORY' 			=> 'Gatunek',
'M_YEAR' 				=> 'Rok produkcji',
'M_COPIES' 				=> 'Ilość kopii',
'M_FROM' 				=> 'Od',
'M_TITLE' 				=> 'Tytuł',
'M_ALTTITLE' 			=> 'Inny tytuł',
'M_GRADE' 				=> 'Ocena',
'M_DIRECTOR'			=> 'Reżyser',
'M_COUNTRY' 			=> 'Produkcja',
'M_RUNTIME' 			=> 'Czas trwania',
'M_MINUTES' 			=> 'minut',
'M_PLOT' 				=> 'Fabuła',
'M_NOPLOT' 				=> 'Fabuła niedostępna',
'M_COVERS' 				=> 'Okładki',
'M_AVAILABLE' 			=> 'Dostępne kopie',
'M_MEDIA' 				=> 'Nośnik',
'M_NUM' 				=> 'Ilość płyt',
'M_DATE' 				=> 'Data dodania',
'M_OWNER' 				=> 'Właściciel',
'M_NOACTORS' 			=> 'Obsada niedostępna',
'M_INFO' 				=> 'Informacje o płycie',
'M_DETAILS' 			=> 'Informacje o mojej kopii',
'M_MEDIATYPE' 			=> 'Typ nośnika',
'M_COMMENT' 			=> 'Komentarz',
'M_PRIVATE' 			=> 'Oznaczyć jako prywatny?',
'M_SCREENSHOTS'			=> 'Zrzuty ekranu',
'M_NOSCREENS' 			=> 'Zrzuty ekranu niedostępne',
'M_SHOW' 				=> 'Pokaż',
'M_HIDE' 				=> 'Schowaj',
'M_CHANGE' 				=> 'Zmień dane',
'M_NOCOVERS' 			=> 'Okładki niedostępne',
'M_BYCAT' 				=> 'Tytuły wg gatunków',
'M_CURRCAT' 			=> 'Bieżący gatunek',
'M_TEXTVIEW' 			=> 'Widok tekstowy',
'M_IMAGEVIEW' 			=> 'Widok obrazkowy',
'M_MINEONLY' 			=> 'Pokaż tylko moje filmy',
'M_SIMILAR' 			=> 'Podobne filmy',
'M_MEDIAINDEX'			=> 'Numer no¶nika',

/* IMDB */
'I_DETAILS' 			=> 'Szczegóły IMDb',
'I_PLOT' 				=> 'Fabuła',
'I_GALLERY' 			=> 'Galeria zdjęć',
'I_TRAILERS' 			=> 'Zwiastuny',
'I_LINKS' 				=> 'Linki IMDb',
'I_NOT' 				=> 'Informacje IMDb niedostępne',

/* DVD Specific */
'DVD_REGION'			=> 'Region',
'DVD_FORMAT'			=> 'Format',
'DVD_ASPECT'			=> 'Proporcje',
'DVD_AUDIO'				=> 'D¼wiêk',
'DVD_SUBTITLES'			=> 'Napisy',
	
/* My Movies */
'MY_EXPORT' 			=> 'Eksport danych',
'MY_EXCEL' 				=> 'Eksport do pliku Excela',
'MY_XML' 				=> 'Eksport do pliku XML',
'MY_XMLTHUMBS' 			=> 'Eksport miniatur do pliku XML',
'MY_ACTIONS' 			=> 'Czynności',
'MY_JOIN' 				=> 'Lista łączona',
'MY_JOINMOVIES' 		=> 'Pokaż listę łączoną',
'MY_JOINSUSER' 			=> 'Wybierz użytkownika',
'MY_JOINSMEDIA' 		=> 'Wybierz nośnik',
'MY_JOINSCAT' 			=> 'Wybierz gatunek',
'MY_JOINSTYPE' 			=> 'Wybierz działanie',
'MY_JOINSHOW' 			=> 'Pokaż wyniki',
'MY_NORESULTS' 			=> 'Nie znaleziono filmów',
'MY_TEXTALL'			=> 'Wydruk (Text)',
'MY_PWALL' 				=> 'Wydruk (Wszystko)',
'MY_PWMOVIES' 			=> 'Wydruk (Filmy)',
'MY_PWTV' 				=> 'Wydruk (Seriale)',
'MY_PWBLUE' 			=> 'Wydruk (Filmy błękitne)',
'MY_J1' 				=> 'Filmy, które ja mam a wybrany użytkownik nie',
'MY_J2' 				=> 'Filmy, które ma wybrany użytkownik a ja nie',
'MY_J3' 				=> 'Nasze wspólne filmy',
'MY_OVERVIEW' 			=> 'Przegląd kolekcji',
'MY_INFO' 				=> 'Na tej stronie możesz dowiedzieć się wszystkiego o swoich filmach.
							Po prawej znajdują się operacje, które możesz wykonać na swojej bazie.
							Możesz też wyeksportować dane do Excela, by je wydrukować, lub użyć funkcji
							eksportu XML aby zapisać kopię zapasową lub przenieść się do innej bazy VCD-Db.',
'MY_KEYS' 				=> 'Zmień własne numery ID',
'MY_SEENLIST' 			=> 'Zmień listę obejrzanych',
'MY_HELPPICKER' 		=> 'Wybierz film do obejrzenia',
'MY_HELPPICKERINFO' 	=> 'Nie wiesz, co dziś obejrzeć?<br/>VCD-db pomoże Ci znaleźć film.<br/>
						    Możesz stworzyć filtry, aby ograniczyć ilość filmów wybranych przez VCD-db.',
'MY_FIND' 				=> 'Szukaj filmu',
'MY_NOTSEEN' 			=> 'Pokaż tylko filmy, których nie widziałem',
'MY_FRIENDS' 			=> 'Ludzie, którym pożyczam filmy',

/* Manager window */
'MAN_BASIC' 			=> 'Podstawowe informacje',
'MAN_IMDB' 				=> 'Informacje IMDb',
'MAN_EMPIRE' 			=> 'Informacje DVDEmpire',
'MAN_COPY' 				=> 'Mój egzemplarz',
'MAN_COPIES' 			=> 'Moje egzemplarze',
'MAN_NOCOPY' 			=> 'Nie masz żadnych kopii',
'MAN_1COPY' 			=> 'Kopia',
'MAN_ADDACT' 			=> 'Dodaj aktorów',
'MAN_ADDTODB' 			=> 'Dodaj nowych aktorów do bazy',
'MAN_SAVETODB' 			=> 'Zapisz w bazie',
'MAN_SAVETODBNCD' 		=> 'Zapisz w bazie i na płytę',
'MAN_INDB' 				=> 'Aktorów w bazie',
'MAN_SEL' 				=> 'Wybrani aktorzy',
'MAN_STARS' 			=> 'Gwiazdy',
'MAN_BROWSE' 			=> 'Szukaj ścieżki do pliku',

/* Add movies */
'ADD_INFO' 				=> 'Wybierz metodę dodawania filmu',
'ADD_IMDB' 				=> 'Pobierz dane z IMDb',
'ADD_IMDBTITLE' 		=> 'Podaj ciąg do wyszukania',
'ADD_MANUAL' 			=> 'Ręcznie podaj dane',
'ADD_LISTED' 			=> 'Dodaj film, który jest już w bazie',
'ADD_XML' 				=> 'Dodaj filmy z pliku XML',
'ADD_XMLFILE' 			=> 'Wybierz plik do zaimportowania',
'ADD_XMLNOTE' 			=> '(Pamiętaj, tylko filmy, które zostały wyeksportowane do XML przez VCD-Db
							mogą być użyte do dodania tu filmu. Możesz eksportować do pliku XML w menu "Moje Filmy".
							Nie powinieneś wprowadzać zmian do pliku XML)',
'ADD_MAXFILESIZE' 		=> 'Max. ',
'ADD_DVDEMPIRE' 		=> 'Pobierz dane z Adult DVD Empire (filmy erotyczne)',
'ADD_LISTEDSTEP1' 		=> 'Krok 1<br/>Wybierz filmy, które chcesz dodać do listy.<br/>Nośnik wybierzesz w następnym kroku.',
'ADD_LISTEDSTEP2' 		=> 'Krok 2.<br/>Wybierz właściwy typ nośnika.',
'ADD_INDB' 				=> 'Filmów w bazie',
'ADD_SELECTED' 			=> 'Wybrane tytuły',
'ADD_INFOLIST' 			=> 'Kliknij dwukrotnie na tytule filmu lub użyj strzałek.<br/>Użyj klawiatury, by szybko znaleźć film.',
'ADD_NOTITLES' 			=> 'Nikt inny nie dodał filmów do bazy.',

/* Add from XML */
'XML_CONFIRM' 			=> 'Potwierdź wysłanie XML',
'XML_CONTAINS' 			=> 'Plik XML zawiera %d filmy(ów).',
'XML_INFO1' 			=> 'Naciśnij "Potwierdź", by dodać filmy do bazy<br/>lub naciśnij "Anuluj" by powrócić.',
'XML_INFO2' 			=> 'Jeśli chcesz dodać miniatury (plakaty) do filmów importowanych z pliku XML,
							<b>MUSISZ</b> posiadać plik z miniaturami!.<br/>
							Nie można zaimportować plakatów, kiedy zakończysz import z bieżącego pliku XML. 
							Jeśli masz gotowy plik XML z miniaturami, zaznacz poniżej, a w następnym kroku 
							po zaimportowaniu poniższych filmów, zostaniesz poproszony również o plik z miniaturami.',
'XML_THUMBNAILS' 		=> 'Wstaw miniatury z pliku XML z miniaturami',
'XML_LIST' 				=> 'Pełna lista filmów znalezionych w pliku XML.',
'XML_ERROR' 			=> 'Nie znaleziono tytułów lub plik XML jest pusty.<br/>Upewnij się, że ten plik XML był wyeksportowany z VCD-db..',
'XML_RESULTS' 			=> 'Wynik importu z pliku XML.',
'XML_RESULTS2' 			=> 'To wyniki importowania z pliku XML.<br/>Zaimportowano razem %d filmów.',

/* Add from Excel */
'EXCEL_CONFIRM' 		=> 'Potwierd¼ dodanie filmów z pliku',
'EXCEL_CONTAINS' 		=> 'Plik zawiera %d filmów.',
'EXCEL_INFO1' 			=> 'Wci¶nij potwierd¼ by za³adowaæ plik i zapisaæ do bazy.<br/>
                                        Lub anuluj je¶li chcesz. ',
'EXCEL_LIST'			=> 'Pe³na lista filmów znalezionych w pliku.',
'EXCEL_ERROR'			=> 'Nie znaleziono filmów w pliku.<br/>Plik mo¿e byæ uszkodzony albo pusty.
			   				<br/>Upewnij siê, ¿e wybra³e¶ plik eksportu z VCD-db...',
'EXCEL_RESULTS'			=> 'Wyniki importu z Excela.',
'EXCEL_RESULTS2'		=> 'Poni¿ej znajdziesz wyniki importu z pliku Excela.<br/>Razem zaimportowano %d filmów.',

/* Add from DVD Empire */
'EM_INFO' 				=> 'Pobierz dane z AdultDVDEmpire.com ....',
'EM_DESC' 				=> 'Opis DVDEmpire',
'EM_SUBCAT' 			=> 'Kategorie dla dorosłych',
'EM_DETAILS' 			=> 'Szczegóły z Adultdvdempire.com',
'EM_STARS' 				=> 'Gwiazdy porno',
'EM_NOTICE' 			=> 'Aktorzy zaznaczeni na czerwono nie znajdują się teraz w bazie,
							ale możesz zaznaczyć ich nazwiska i zostaną oni automatycznie dodani do bazy
							oraz przypisani do tego filmu.',
'EM_FETCH' 				=> 'Pobierz także',

/* Loan System */
'LOAN_MOVIES' 			=> 'Filmy do pożyczenia',
'LOAN_TO' 				=> 'Pożycz filmy...',
'LOAN_ADDUSERS' 		=> 'Dodaj użytkowników, którym pożyczasz, aby kontynuować',
'LOAN_NEWUSER' 			=> 'Nowy pożyczający',
'LOAN_REGISTERUSER' 	=> 'Dodaj nowego pożyczającego',
'LOAN_NAME' 			=> 'Imię',
'LOAN_SELECT' 			=> 'Wybierz pożyczającego',
'LOAN_MOVIELOANS' 		=> 'Pożyczone filmy...',
'LOAN_REMINDER' 		=> 'Wyślij przypomnienie',
'LOAN_HISTORY' 			=> 'Historia wypożyczeń',
'LOAN_HISTORY2' 		=> 'Zobacz historię wypożyczeń',
'LOAN_SINCE' 			=> 'Od',
'LOAN_TIME' 			=> 'Od kiedy',
'LOAN_RETURN' 			=> 'Zwróć egzemplarz',
'LOAN_SUCCESS' 			=> 'Filmy wypożyczone',
'LOAN_OUT' 				=> 'Nie zwrócone',
'LOAN_DATEIN' 			=> 'Data zwrotu',
'LOAN_DATEOUT' 			=> 'Data wypożyczenia',
'LOAN_PERIOD' 			=> 'Czas wypożyczenia',
'LOAN_BACK' 			=> 'Powrót do listy wypożyczeń',
'LOAN_DAY' 				=> 'dzień',
'LOAN_DAYS' 			=> 'dni',
'LOAN_TODAY' 			=> 'od dzisiaj',

/* RSS */
'RSS' 					=> 'Kanały RSS',
'RSS_TITLE' 			=> 'Kanały RSS baz VCD-db moich znajomych',
'RSS_SITE' 				=> 'Kanały RSS Strony',
'RSS_USER' 				=> 'Kanał RSS Użytkownika',
'RSS_VIEW' 				=> 'Zobacz kanał RSS',
'RSS_ADD' 				=> 'Dodaj nowy kanał',
'RSS_NOTE'				=> 'Wpisz <strong>dokładny adres</strong> bazy VCD-db twojego znajomego.<br/>
							Jeśli na stronie włączona będzie obsługa kanałów RSS,
							będziesz mógł wybrać interesujące kanały i wyświetlać je na stronie.',
'RSS_FETCH' 			=> 'Pobierz listę kanałów',
'RSS_NONE' 				=> 'Nie dodano żadnych kanałów.',
'RSS_FOUND' 			=> 'Znaleziono następujące kanały RSS, wybierz te, które chcesz dodać:',
'RSS_NOTFOUND' 			=> 'Nie znaleziono kanałów RSS.',

/* Wishlist */
'W_ADD' 				=> 'Dodaj do mojej listy życzeń',
'W_ONLIST' 				=> 'W twojej liście życzeń',
'W_EMPTY' 				=> 'Twoja lista życzeń jest pusta',
'W_OWN' 				=> 'Posiadam egzemplarz tego filmu',
'W_NOTOWN' 				=> 'Nie posiadam tego filmu',

/* Comments */
'C_COMMENTS' 			=> 'Komentarze',
'C_ADD' 				=> 'Dodaj nowy komentarz',
'C_NONE' 				=> 'Nie ma żadnych komentarzy',
'C_TYPE'				=> 'Napisz swój nowy komentarz',
'C_YOUR' 				=> 'Twój komentarz',
'C_POST' 				=> 'Dodaj komentarz',
'C_ERROR' 				=> 'Musisz się zalogować, by dodać komentarz',

/* Pornstars */
'P_NAME' 				=> 'Imię',
'P_WEB' 				=> 'Strona',
'P_MOVIECOUNT' 			=> 'Ilość filmów',

/* Seen List */
'S_SEENIT' 				=> 'Widziałem',
'S_NOTSEENIT' 			=> 'Nie widziałem',
'S_SEENITCLICK' 		=> 'Kliknij by zaznaczyć, jako obejrzany',
'S_NOTSEENITCLICK' 		=> 'Kliknij by zaznaczyć, jako nie obejrzany',

/* Mail messages */
'MAIL_RETURNTOPIC'		=> 'Przypomnienie o po¿yczonych filmach',
'MAIL_RETURNMOVIES1' 	=> '%s, Chciałem przypomnieć ci o zwrocie moich filmów.\n
							Wciąż masz następujące moje filmy :\n\n',
'MAIL_RETURNMOVIES2' 	=> 'Proszę zwróć płyty najszybciej jak to możliwe\n Pozdrawiam, %s \n\n
							to automatycznie wygenerowana wiadomość z bazy VCD-db (http://vcddb.konni.com)',
'MAIL_NOTIFY' 			=> '<strong>Nowy film został dodany do bazy</strong><br/>
							Kliknij <a href="%s/?page=cd&vcd_id=%s">tutaj</a> by zobaczyć więcej...
							<p>to automatycznie wygenerowana wiadomość z bazy VCD-db (http://vcddb.konni.com)',
'MAIL_REGISTER' 		=> '%s, zostałeś zarejestrowany w bazie.\n\nTwoja nazwa użytkownika to %s a hasło to 
							%s.\n\nMożesz zmienić hasło po zalogowaniu.\n
							Kliknij <a href="%s" target="_new">tutaj</a> by przejść do bazy.',

/* Player */
'PLAYER' 				=> 'Odtwarzacz',
'PLAYER_PATH' 			=> 'Ścieżka dostępu',
'PLAYER_PARAM' 			=> 'Ustawienia',
'PLAYER_NOTE' 			=> 'Wpisz pełną ścieżkę dostępu do odtwarzacza na swoim dysku twardym.
							Twój odtwarzacz musi obsługiwać ustawienia z linii komend, podobnie jak MPlayer<br/>
							Możesz go ściągnąć za darmo <a href="http://www.MPlayerHQ.hu" target="_new">stąd</a>.',


/* Metadata */
'META_MY'				=> 'Moje dodatkowe dane',
'META_NAME'				=> 'Nazwa',
'META_DESC'				=> 'Opis',
'META_TYPE'				=> 'Typ danych',
'META_VALUE'			=> 'Warto¶æ',
'META_NONE'				=> 'Brak danych.',

/* Ignore List */
'IGN_LIST'				=> 'Lista ignorowanych',
'IGN_DESC'				=> 'Ignoruj wszystkie filmy od tych u¿ytkowników:',

/* Misc keywords */
'X_CONTAINS' 			=> 'zawiera',
'X_GRADE' 				=> 'Ocena IMDb wyższa niż',
'X_ANY' 				=> 'Dowolny',
'X_TRYAGAIN' 			=> 'Spróbuj ponownie',
'X_PROCEED' 			=> 'Dalej',
'X_SELECT' 				=> 'Wybierz',
'X_CONFIRM' 			=> 'Potwierdź',
'X_CANCEL' 				=> 'Anuluj',
'X_ATTENTION' 			=> 'Uwaga!',
'X_STATUS' 				=> 'Stan',
'X_SUCCESS' 			=> 'Udało się',
'X_FAILURE' 			=> 'Nie udało się',
'X_YES' 				=> 'Tak',
'X_NO' 					=> 'Nie',
'X_SHOWMORE' 			=> 'Pokaż więcej',
'X_SHOWLESS' 			=> 'Pokaż mniej',
'X_NEW' 				=> 'Nowy',
'X_CHANGE' 				=> 'zmień',
'X_DELETE' 				=> 'usuń',
'X_UPDATE' 				=> 'aktualizuj',
'X_SAVEANDCLOSE' 		=> 'Zapisz i zamknij',
'X_CLOSE' 				=> 'Zamknij',
'X_EDIT' 				=> 'Edytuj',
'X_RESULTS' 			=> 'Wyniki',
'X_LATESTMOVIES' 		=> 'najnowszych filmów',
'X_LATESTTV'			=> 'najnowsze seriale',
'X_LATESTBLUE' 			=> 'najnowsze erotyczne',
'X_MOVIES' 				=> 'filmy',
'X_NOCATS'				=> 'Nie dodano filmów.',
'X_NOUSERS' 			=> 'Brak aktywnych użytkowników',
'X_KEY' 				=> 'Klucz',
'X_SAVENEXT' 			=> 'Zapisz i edytuj następny',
'X_SAVE' 				=> 'Zapisz',
'X_SEEN' 				=> 'Obejrzany'



);

?>