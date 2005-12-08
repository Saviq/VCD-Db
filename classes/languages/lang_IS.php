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
		Icelandic language file
	
	*/


	
$_ = array(

/* Language Identifier */
'LANG_TYPE' 			=> 'IS',
'LANG_NAME' 			=> 'Íslenska',
'LANG_CHARSET'			=> 'iso-8859-1',

/* Menu system */
'MENU_MINE' 			=> 'Mín valmynd',
'MENU_SETTINGS' 		=> 'Mínar stillingar',
'MENU_MOVIES' 			=> 'Mínar myndir',
'MENU_ADDMOVIE' 		=> 'Bæta við mynd',
'MENU_LOANSYSTEM'		=> 'Lánakerfið',
'MENU_WISHLIST' 		=> 'Óskalistinn',
'MENU_CATEGORIES' 		=> 'Flokkar mynda',
'MENU_RSS' 				=> 'RSS straumar',
'MENU_CONTROLPANEL' 	=> 'Stjórnborð',
'MENU_REGISTER' 		=> 'Nýskráning',
'MENU_LOGOUT' 			=> 'Útskráning',
'MENU_SUBMIT'			=> 'Staðfesta',
'MENU_TOPUSERS'			=> 'Top notendur',
'MENU_WISHLISTPUBLIC'	=> 'Óskalisti annara',
'MENU_STATISTICS'		=> 'Tölfræði',

/* Login */
'LOGIN' 				=> 'Innskráning',
'LOGIN_USERNAME' 		=> 'Notandi',
'LOGIN_PASSWORD' 		=> 'Lykilorð',
'LOGIN_REMEMBER' 		=> 'Mundu mig',
'LOGIN_INFO' 			=> 'Hafðu þetta tómt ef þú vilt <b>ekki</b> breyta lykilorðinu þínu',

/* Register */
'REGISTER_TITLE'		=> 'Nýskráning',
'REGISTER_FULLNAME' 	=> 'Fullt nafn',
'REGISTER_EMAIL' 		=> 'Netfang',
'REGISTER_AGAIN' 		=> 'Lykilorð aftur',
'REGISTER_DISABLED' 	=> 'Þvi miður, kerfisstjóri leyfir ekki nýskráningar eins og er',
'REGISTER_OK' 			=> 'Skráning heppnaðist, þú getur núna skráð þig inn.',

/* User Properties */
'PRO_NOTIFY' 			=> 'Senda mér póst þegar að ný mynd er skráð?',
'PRO_SHOW_ADULT' 		=> 'Sýna fullorðins efni?',
'PRO_RSS' 				=> 'Leyfa RSS straum frá mínum lista?',
'PRO_WISHLIST' 			=> 'Leyfa öðrum að sjá óskalistann minn?',
'PRO_USE_INDEX' 		=> 'Nota sér auðkenni fyrir myndir?',
'PRO_SEEN_LIST' 		=> 'Halda utan um myndir sem ég hef séð?',
'PRO_PLAYOPTION' 		=> 'Nota afspilun frá VCD-db?',


/* User Settings */
'SE_PLAYER' 			=> 'Stilla spilara',
'SE_OWNFEED' 			=> 'Skoða minn XML straum',
'SE_CUSTOM' 			=> 'Stilla forsíðu',
'SE_SHOWSTAT' 			=> 'Sýna tölfræði',
'SE_SHOWSIDE' 			=> 'Sýna nýjar myndir í hægri valmynd',
'SE_SELECTRSS' 			=> 'Veldu RSS strauma',
'SE_PAGELOOK' 			=> 'Útlit vefs',
'SE_PAGEMODE' 			=> 'Veldu sjálfgefið sniðmát:',

/* Search */
'SEARCH' 				=> 'Leita',
'SEARCH_TITLE' 			=> 'Eftir titli',
'SEARCH_ACTOR' 			=> 'Eftir leikara',
'SEARCH_DIRECTOR' 		=> 'Eftir leikstjóra',
'SEARCH_RESULTS' 		=> 'Leitarniðurstöður',
'SEARCH_EXTENDED' 		=> 'Ítarleg leit',
'SEARCH_NORESULT' 		=> 'Leit skilaði engum niðurstöðum',

/* Movie categories*/
'CAT_ACTION' 			=> 'Spennumyndir',
'CAT_ADULT' 			=> 'Erótískar',
'CAT_ADVENTURE' 		=> 'Ævintýramyndir',
'CAT_ANIMATION' 		=> 'Teiknimyndir',
'CAT_ANIME' 			=> 'Anime / Manga',
'CAT_COMEDY' 			=> 'Gamanmyndir',
'CAT_CRIME' 			=> 'Sakamálamyndir',
'CAT_DOCUMENTARY' 		=> 'Heimildarmyndir',
'CAT_DRAMA' 			=> 'Drama',
'CAT_FAMILY' 			=> 'Fjöldskyldumyndir',
'CAT_FANTASY' 			=> 'Fantasía',
'CAT_FILMNOIR' 			=> 'Film Noir',
'CAT_HORROR' 			=> 'Hryllingsmyndir',
'CAT_JAMESBOND' 		=> 'James Bond',
'CAT_MUSICVIDEO' 		=> 'Tónlistarmyndbönd',
'CAT_MUSICAL' 			=> 'Söngvamyndir',
'CAT_MYSTERY' 			=> 'Ráðgáta',
'CAT_ROMANCE' 			=> 'Rómantik',
'CAT_SCIFI' 			=> 'Vísindarskáldsaga',
'CAT_SHORT' 			=> 'Stuttmyndir',
'CAT_THRILLER' 			=> 'Þriller',
'CAT_TVSHOWS' 			=> 'Sjónvarpsþættir',
'CAT_WAR' 				=> 'Stríðsmyndir',
'CAT_WESTERN' 			=> 'Vestrar',
'CAT_XRATED' 			=> 'Ljósbláar',

/* Movie Listings */
'M_MOVIE' 				=> 'Um myndina',
'M_ACTORS' 				=> 'Leikarar',
'M_CATEGORY'		    => 'Flokkur',
'M_YEAR'				=> 'Framleiðsluár',
'M_COPIES'				=> 'Eintök til',
'M_FROM' 				=> 'Frá',
'M_TITLE' 				=> 'Titill',
'M_ALTTITLE' 			=> 'Aukatitill',
'M_GRADE'				=> 'Einkunn',
'M_DIRECTOR' 			=> 'Leikstjóri',
'M_COUNTRY'				=> 'Framleiðsluland',
'M_RUNTIME' 			=> 'Lengd',
'M_MINUTES'			 	=> 'min.',
'M_PLOT' 				=> 'Söguþráður',
'M_NOPLOT' 				=> 'Enginn söguþráður til',
'M_COVERS' 				=> 'CD Covers',
'M_AVAILABLE' 			=> 'Eintök til',
'M_MEDIA'			 	=> 'Myndgæði',
'M_NUM' 				=> 'Fjöldi diska',
'M_DATE' 				=> 'Dags',
'M_OWNER'			 	=> 'Eigandi',
'M_NOACTORS'		    => 'Engir leikarar skráðir',
'M_INFO'			    => 'Nánari upplýsingar',
'M_DETAILS'			    => 'Upplýsingar um mitt eintak',
'M_MEDIATYPE'		    => 'Myndgæði',
'M_COMMENT'			    => 'Athugasemd',
'M_PRIVATE'				=> 'Merkja prívat?',
'M_SCREENSHOTS'			=> 'Skjámyndir',
'M_NOSCREENS'			=> 'Engar skjámyndir eru til',
'M_SHOW'				=> 'Sýna',
'M_HIDE'				=> 'Fela',
'M_CHANGE'				=> 'Breyta upplýsingum',
'M_NOCOVERS'			=> 'Enginn CD-Cover eru til',
'M_BYCAT'				=> 'Titlar eftir flokki',
'M_CURRCAT'				=> 'Valinn flokkur',
'M_TEXTVIEW'			=> 'Textasýn',
'M_IMAGEVIEW'			=> 'Myndasýn',
'M_MINEONLY'			=> 'Sýna bara mínar myndir',
'M_SIMILAR'				=> 'Svipaðir titlar',
'M_MEDIAINDEX'			=> 'Auðkenni',

/* IMDB */
'I_DETAILS'				=> 'IMDB Lýsing',
'I_PLOT'				=> 'Söguþráður',
'I_GALLERY'				=> 'Myndasafn',
'I_TRAILERS'			=> 'Myndbands brot',
'I_LINKS'				=> 'IMDB Tenglar',
'I_NOT'					=> 'Engar IMDB upplýsingar tiltækar',

/* DVD Specific */
'DVD_REGION'			=> 'Svæði',
'DVD_FORMAT'			=> 'Tegund',
'DVD_ASPECT'			=> 'Kóðun',
'DVD_AUDIO'				=> 'Hljóðrás',
'DVD_SUBTITLES'			=> 'Textar',

/* My Movies */
'MY_EXPORT' 			=> 'Flytja út gögn',
'MY_EXCEL' 				=> 'Flytja listann út í Excel',
'MY_XML' 				=> 'Flytja listann út sem XML',
'MY_XMLTHUMBS'			=> 'Flytja thumbnails út sem XML',
'MY_ACTIONS'			=> 'Mínar aðgerðir',
'MY_JOIN'				=> 'Samkeyrslur',
'MY_JOINMOVIES'			=> 'Samkeyrslur á myndum',
'MY_JOINSUSER'			=> 'Veldu notanda',
'MY_JOINSMEDIA'			=> 'Veldu myndgæði',
'MY_JOINSCAT'			=> 'Veldu yfirflokk',
'MY_JOINSTYPE'			=> 'Veldu tegund keyrslu',
'MY_JOINSHOW'			=> 'Sýna niðurstöður',
'MY_NORESULTS'			=> 'Keyrsla skilaði engum niðurstöðum',
'MY_TEXTALL'			=> 'Prentsýn (textasýn)',
'MY_PWALL'				=> 'Prentsýn allra mynda',
'MY_PWMOVIES'			=> 'Prentsýn kvikmynda',
'MY_PWTV'				=> 'Prentsýn þátta',
'MY_PWBLUE'				=> 'Prentsýn erótískra mynda',
'MY_J1'					=> 'Myndir sem ég á en notandi ekki',
'MY_J2'					=> 'Myndir sem notandi á en ég ekki',
'MY_J3'					=> 'Myndir sem báðir eiga',
'MY_OVERVIEW'			=> 'Yfirlit mynda',

'MY_INFO'				=> 'Á þessari siðu er allt að finna um mínar myndir
							Til hægri eru mögulegar aðgerðir sem snúa að þinum myndum.
							Einnig geturu hérna flutt út gögn á Excel formi til útprentunar eða notað XML útflutnings 
							möguleikana til að færa allar þinar myndir í heilu lagi frá einum VCD-db 
							gagnagrunni til annars.',
'MY_KEYS'				=> 'Stilla lykla',
'MY_SEENLIST'			=> 'Stilla séðar myndir',
'MY_HELPPICKER'			=> 'Finna mynd fyrir kvöldið',
'MY_HELPPICKERINFO'		=> 'Veistu ekki hvað þú átt að glápa á?<br/>Leyfðu VCD-db að finna mynd fyrir þig.<br/>
							Þú getur stillt skorður ef þú vilt til að þrengja úrtakið fyrir VCD-db.',
'MY_FIND'				=> 'Finna mynd',
'MY_NOTSEEN'			=> 'Stinga bara uppá myndum sem ég hef ekki séð',
'MY_FRIENDS'			=> 'Mínir lánþegar',

/* Manager window */
'MAN_BASIC' 			=> 'Grunnupplýsingar',
'MAN_IMDB' 				=> 'IMDB upplýsingar',
'MAN_EMPIRE' 			=> 'Empire upplýsingar',
'MAN_COPY' 				=> 'Mitt eintak',
'MAN_COPIES' 			=> 'Mín eintök',
'MAN_NOCOPY' 			=> 'Þú átt engin eintök',
'MAN_1COPY' 			=> 'Eintak',
'MAN_ADDACT' 			=> 'Bæta við leikurum',
'MAN_ADDTODB' 			=> 'Bæta við nýjum leikara í grunninn',
'MAN_SAVETODB' 			=> 'Vista í db',
'MAN_SAVETODBNCD' 		=> 'Vista í db og mynd',
'MAN_INDB' 				=> 'Leikarar í gagnagrunni',
'MAN_SEL' 				=> 'Valdir leikarar',
'MAN_STARS' 			=> 'Stjörnur',
'MAN_BROWSE'			=> 'Velja skjal',

/* Add movies */
'ADD_INFO' 				=> 'Veldu einn af eftirfarandi möguleikum til að skrá inn nýja mynd',
'ADD_IMDB' 				=> 'Sækja beint frá IMDB',
'ADD_IMDBTITLE' 		=> 'Sláðu inn titil til að leita eftir',
'ADD_MANUAL' 			=> 'Slá inn handvirkt',
'ADD_LISTED' 			=> 'Bæta við myndum sem er nú þegar skráðar',
'ADD_XML' 				=> 'Hlaða inn úr XML skjali',
'ADD_XMLFILE' 			=> 'Skjal til að hlaða inn',
'ADD_XMLNOTE' 			=> '(Athugið, aðeins XML skjöl sem hafa verið flutt út með VCD-DB geta verið notuð til að 
							flytja inn myndir. Þú getur flutt út myndir í XML form undir "Mínar myndir". 
							Forðist að breyta XML skjölum frá VCD-DB handvirkt.) ',
'ADD_MAXFILESIZE'		=> 'Hámarks skrárstærð',
'ADD_DVDEMPIRE' 		=> 'Sækja beint frá adultdvdempire.com (bláar myndir)',
'ADD_LISTEDSTEP1' 		=> 'Skref 1. <br/>Veldu myndir sem þú vilt bæta við á listann þinn.<br/>
							Í næsta skrefi velur þú myndgæði. ',
'ADD_LISTEDSTEP2' 		=> 'Skref 2.<br/>Veldu viðeigandi myndgæði.',
'ADD_INDB' 				=> 'Myndir í grunni',
'ADD_SELECTED' 			=> 'Valdir titlar',
'ADD_INFOLIST' 			=> 'Tvísmelltu á mynd til að færa yfir, eða notaðu örina. <br/>
							Hægt er að nota lyklaborðið í valmyndinni vinstra megin <br/>
							til að flýta fyrir að finna ákveðna mynd.',
'ADD_NOTITLES' 			=> 'Enginn annar notandi hefur skráð myndir i grunninn.',

/* Add from XML */
'XML_CONFIRM' 			=> 'Staðfesta XML innflutning',
'XML_CONTAINS' 			=> 'XML skjal inniheldur %d myndir.',
'XML_INFO1' 			=> 'Ýttu á staðfesta til að lesa inn myndir og vista i grunninn.<br/>
							Eða ýttu á Hætta við til að hætta við. ',
'XML_INFO2' 			=> 'Ef þú vilt að thumbnail myndir fylgi með innflutningi myndanna sem þú ert að flytja inn úr XML skjalinu,
							<b>verður</b> þú að eiga XML thumbnails skjalið tilbúið líka núna.<br/>
							Thumbnails geta ekki verið sjálfkrafa fluttir inn nema núna.
							Ef þú ert nú þegar með XML thumbnails skjalið, ýttu þá á tékkboxið hér fyrir neðan og finndu XML skjalið sem
							á að fylgja með myndunum þínum.  Þá munu myndir verða fluttar inn með viðkomandi titlum.',
'XML_THUMBNAILS'		=> 'Flytja inn thumbnails frá XML skjali',
'XML_LIST'				=> 'Eftirfarandi titlar fundust í XML skjalinu.',
'XML_ERROR'				=> 'Engar myndir fundust í XML skjalinu.<br/>Skjal gæti verið tómt eða skemmt.
			   				<br/>Gangtu úr skugga um að þú sért að nota XML skjal sem var flutt úr VCD-db ..',
'XML_RESULTS'			=> 'Niðurstöður XML innflutnings.',
'XML_RESULTS2'			=> 'Hér eru niðustöður XML innflutnings.<br/>Samtals %d myndir voru vistaðar í grunninn.',



/* Add from DVD Empire */
'EM_INFO'				=> 'Upplýsingar frá adultdvdempire.com um myndina ....',
'EM_DESC'				=> 'DVDEmpire lýsing',
'EM_SUBCAT'				=> 'Undirflokkar',
'EM_DETAILS'			=> 'Nánari upplýsingar frá Adultdvdempire.com',
'EM_STARS'				=> 'Leikarar',
'EM_NOTICE'				=> 'Leikarar merktir með rauðu fundust ekki í grunninum.
							En það er hægt að haka við þá ef þú vilt bæta þeim við í grunninn og 
							tengja við þessa mynd.',
'EM_FETCH'				=> 'Sækja aukalega',

/* Loan System */
'LOAN_MOVIES'			=> 'Myndir til láns',
'LOAN_TO'				=> 'Lána myndir til',
'LOAN_ADDUSERS'			=> 'Byrjaðu á að búa til lánþega',
'LOAN_NEWUSER'			=> 'Nýr lánþegi',
'LOAN_REGISTERUSER'		=> 'Bæta við lánþega',
'LOAN_NAME'				=> 'Nafn',
'LOAN_SELECT'			=> 'Veldu lánþega',
'LOAN_MOVIELOANS'		=> 'Myndir í láni ...',
'LOAN_REMINDER'			=> 'Senda áminningu',
'LOAN_HISTORY'			=> 'Lánasaga',
'LOAN_HISTORY2'			=> 'Sjá lánasögu',
'LOAN_SINCE'			=> 'Síðan',
'LOAN_TIME'				=> 'Tími siðan',
'LOAN_RETURN'			=> 'Skila eintaki',
'LOAN_SUCCESS'			=> 'Myndir hafa verið settar í lán',
'LOAN_OUT'				=> 'Óskilað',
'LOAN_DATEIN'			=> 'Dags inn',
'LOAN_DATEOUT'			=> 'Dags út',
'LOAN_PERIOD'			=> 'Lánstími',
'LOAN_BACK'				=> 'Aftur á lánayfirlit',
'LOAN_DAY'				=> 'dagur',
'LOAN_DAYS'				=> 'dagar',
'LOAN_TODAY'			=> 'frá í dag',


/* RSS */
'RSS'					=> 'RSS Straumar',
'RSS_TITLE'				=> 'RSS straumar frá öðrum VCD-DB félögum',
'RSS_SITE'				=> 'RSS Vef straumur',
'RSS_USER'				=> 'RSS Notanda straumur',
'RSS_VIEW'				=> 'Skoða RSS straum',
'RSS_ADD'				=> 'Bæta við RSS straum',
'RSS_NOTE'				=> 'Sláðu inn <strong>nákvæma slóð</strong> á VCD-DB til að tengjast.<br/>
							Ef RSS straumur er virkjaður á viðkomandi slóð geturu valið úr
							RSS straumum til að birta á síðunni þinni.',
'RSS_FETCH'				=> 'Sækja RSS Lista',
'RSS_NONE'				=> 'Engir RSS straumar hafa verið skilgreindir.',
'RSS_FOUND'				=> 'Eftirfarandi RSS straumar fundust, veldu þá sem þú vilt birta:',
'RSS_NOTFOUND'			=> 'Engar straumar fundust á slóðinni',


/* Wishlist */
'W_ADD'					=> 'Setja á óskalistann',
'W_ONLIST'				=> 'Er á óskalistanum',
'W_EMPTY'				=> 'Óskalistinn þinn er tómur',
'W_OWN'					=> 'Ég á eintak af þessari mynd',
'W_NOTOWN'				=> 'Ég á ekki eintak af þessari mynd',


/* Comments */
'C_COMMENTS'			=> 'Athugasemdir',
'C_ADD'					=> 'Skrá athugasemd',
'C_NONE'				=> 'Engar athugasemdir hafa verið sendar inn',
'C_TYPE'				=> 'Sláðu inn athugasemd',
'C_YOUR'				=> 'Þín athugasemd',
'C_POST'				=> 'Senda athugasemd',
'C_ERROR'				=> 'Skráðu þig inn áður en þú sendir inn athugasemd',


/* Pornstars */
'P_NAME'				=> 'Nafn',
'P_WEB'					=> 'Vefsíða',
'P_MOVIECOUNT'			=> 'Fjöldi mynda',

/* Seen List */
'S_SEENIT'				=> 'Búinn að sjá hana',
'S_NOTSEENIT'			=> 'Eftir að sjá hana',
'S_SEENITCLICK'			=> 'Smelltu til að merkja sem séða',
'S_NOTSEENITCLICK'		=> 'Smelltu til að merkja mynd óséða',

/* Mail messages */
'MAIL_RETURNTOPIC'		=> 'Áminning um skil',
'MAIL_RETURNMOVIES1'	=> '%s, vill minna þig á að skila myndunum mínum.\n
							Þú ert með eftirtalda diska í láni :\n\n',
'MAIL_RETURNMOVIES2'    => 'Vinsamlegast skilið diskunum sem fyrst\n Kveðjur %s \n\n
							ps. þetta er sjálfvirkur póstur frá VCD-db kerfinu (http://vcddb.konni.com)',

'MAIL_NOTIFY'  			=> '<strong>Ný mynd hefur verið skráð í grunninn</strong><br/>
							 Smelltu <a href="%s/?page=cd&vcd_id=%s">hér</a> til að kíkja á málið
							 <p>ps. þetta er sjálfvirkur póstur frá VCD kerfinu.</p>',
'MAIL_REGISTER'		 	=> '%s, skráning þín tókst í VCD-db kerfið.<br><br>Notandanafnið þitt er %s og lykilorðið 
							þitt er %s.<br><br>Þú getur síðan skipt um lykilorð eftir að þú hefur skráð þig inn.<br>
							VCD-db vefurinn er síðan <a href="%s" target="_new">hér</a>.',

/* Player */
'PLAYER'				=> 'Spilari',
'PLAYER_PATH'			=> 'Slóði',
'PLAYER_PARAM'			=> 'Færibreytur',
'PLAYER_NOTE'			=> 'Sláðu inn fullan slóða á spilara forritið þitt.
							Spilarinn verður að geta tekið inn færibreytur á skjöl eins og td.
							BSPlayer fyrir Windows eða MPlayer fyrir Linux.<br/>Þú getur náð í BS spilarann frítt 
							<a href="http://www.bsplayer.org" target="_new">hérna</a> 
							og MPlayer <a href="http://www.MPlayerHQ.hu" target="_new">hérna</a>.',


/* Metadata */
'META_MY'				=> 'Mín Aukagildi',
'META_NAME'				=> 'Lykill',
'META_DESC'				=> 'Lýsing',
'META_TYPE'				=> 'Tegund',
'META_VALUE'			=> 'Gildi',
'META_NONE'				=> 'Engin aukagildi skráð.',

/* Ignore List */
'IGN_LIST'				=> 'Útilokunar listi',
'IGN_DESC'				=> 'Ekki birta myndir frá eftirfarandi notendum:',

/* Misc keywords */
'X_CONTAINS'			=> 'inniheldur',
'X_GRADE'				=> 'IMDB einkunn meira en',
'X_ANY'					=> 'Allt',
'X_TRYAGAIN'			=> 'Reyndu aftur',
'X_PROCEED' 			=> 'Áfram',
'X_SELECT' 				=> 'Veldu',
'X_CONFIRM' 			=> 'Staðfesta',
'X_CANCEL' 				=> 'Hætta við',
'X_ATTENTION' 			=> 'Athugið!',
'X_STATUS' 				=> 'Staða',
'X_SUCCESS' 			=> 'Tókst',
'X_FAILURE' 			=> 'Mistókst',
'X_YES' 				=> 'Já',
'X_NO' 					=> 'Nei',
'X_SHOWMORE' 			=> 'Sýna meira',
'X_SHOWLESS' 			=> 'Sýna minna',
'X_NEW' 				=> 'Nýtt',
'X_CHANGE' 				=> 'breyta',
'X_DELETE' 				=> 'eyða',
'X_UPDATE' 				=> 'Uppfæra',
'X_SAVEANDCLOSE' 		=> 'Vista og loka',
'X_CLOSE' 				=> 'Loka',
'X_EDIT' 				=> 'Breyta',
'X_RESULTS' 			=> 'Niðurstöður',
'X_LATESTMOVIES' 		=> 'nýjustu myndirnar',
'X_LATESTTV' 			=> 'nýjustu þættirnir',
'X_LATESTBLUE' 			=> 'nýjustu XXX',
'X_MOVIES' 				=> 'myndir',
'X_NOCATS' 				=> 'Engar myndir til í grunni.',
'X_NOUSERS' 			=> 'Engir virkir notendur',
'X_KEY' 				=> 'Lykill',
'X_SAVENEXT' 			=> 'Vista og breyta næstu',
'X_SAVE' 				=> 'Vista',
'X_SEEN' 				=> 'Séð'

 

);

?>