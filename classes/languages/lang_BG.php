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
 * @author  Hбkon Birgsson <konni@konni.com>
 * @package Language
 * @version $Id$
 */
 
?>
<? 
	/** 
		Bulgarian language file
	
	*/
	
$_ = array(

/* Language Identifier */
'LANG_TYPE' 			=> 'BG',
'LANG_NAME' 			=> 'Bulgarian',
'LANG_CHARSET'			=> 'windows-1251',

/* Menu system */
'MENU_MINE' 			=> 'Меню',
'MENU_SETTINGS' 		=> 'Настройки',
'MENU_MOVIES' 			=> 'Филми',
'MENU_ADDMOVIE' 		=> 'Прибави филм',
'MENU_LOANSYSTEM'		=> 'Система Заем',
'MENU_WISHLIST' 		=> 'Списък "Желания"',
'MENU_CATEGORIES' 		=> 'Категорий',
'MENU_RSS' 				=> 'My Rss Feeds',
'MENU_CONTROLPANEL' 	=> 'Контролен панел',
'MENU_REGISTER' 		=> 'Регистрирай се',
'MENU_LOGOUT' 			=> 'Излез',
'MENU_SUBMIT'			=> 'Прати',
'MENU_TOPUSERS'			=> 'TOP потребители',
'MENU_WISHLISTPUBLIC'	=> 'Списък "Желания" на други потребители',
'MENU_STATISTICS'		=> 'Статистики',

/* Login */
'LOGIN' 				=> 'Влез',
'LOGIN_USERNAME' 		=> 'Потребител',
'LOGIN_PASSWORD' 		=> 'Парола',
'LOGIN_REMEMBER' 		=> 'Запомни ме',
'LOGIN_INFO' 			=> 'ОСтави празно ако <b>не</b> искаш да сменяш паролата си',

/* Register */
'REGISTER_TITLE'		=> 'Регистрация',
'REGISTER_FULLNAME' 	=> 'Пълно име',
'REGISTER_EMAIL' 		=> 'E-mail',
'REGISTER_AGAIN' 		=> 'Повтори паролата',
'REGISTER_DISABLED' 	=> 'Oooops !...Не става !',
'REGISTER_OK' 			=> 'Регистрацията премина успешно, можете да влезете в системата.',

/* User Properties */
'PRO_NOTIFY' 			=> 'Прати ми писмо ако е прибавен нов филм?',
'PRO_SHOW_ADULT' 		=> 'Показвай съдържание за възрастни на сайта?',
'PRO_RSS' 				=> 'Разреши RSS feed от моята листа?',
'PRO_WISHLIST'   		=> 'Да позволя на другите да виждат моята листа "Желания" ?',
'PRO_USE_INDEX'  		=> 'Използвай полета за индекс за различни ID-та на файлове  ',
'PRO_SEEN_LIST'  		=> 'Запази пътя до филмите, които не съм гледал',
'PRO_PLAYOPTION' 	    => 'Използвай опций за възпройзвеждане на файл ',
'PRO_NFO' 				=> 'Използвай NFO ?',

/* User Settings */
'SE_PLAYER' 			=> 'Настройки на плейъра',
'SE_OWNFEED' 			=> 'View my own feed',
'SE_CUSTOM' 			=> 'Промяна на стартова страница',
'SE_SHOWSTAT' 			=> 'Покажи статистики',
'SE_SHOWSIDE' 			=> 'Показвай новите филми в страничния бар',
'SE_SELECTRSS' 			=> 'Избери RSS feeds',
'SE_PAGELOOK' 			=> 'Web layout',
'SE_PAGEMODE' 			=> 'Select default template:',

/* Search */
'SEARCH' 				=> 'Търси',
'SEARCH_TITLE' 			=> 'По заглавие',
'SEARCH_ACTOR' 			=> 'По актьор',
'SEARCH_DIRECTOR' 		=> 'По режисьор',
'SEARCH_RESULTS' 		=> 'Резултати',
'SEARCH_EXTENDED' 		=> 'Детайлно търсене',
'SEARCH_NORESULT' 		=> 'Засега го няма:):):)',

/* Movie categories*/
'CAT_ACTION' 			=> 'Екшън',
'CAT_ADULT' 			=> 'За мама и татко',
'CAT_ADVENTURE' 		=> 'Приключенски',
'CAT_ANIMATION' 		=> 'Анимация',
'CAT_ANIME' 			=> 'Аниме / Манга',
'CAT_COMEDY' 			=> 'Комедия',
'CAT_CRIME' 			=> 'Криминален',
'CAT_DOCUMENTARY' 		=> 'Документален',
'CAT_DRAMA' 			=> 'Драма',
'CAT_FAMILY' 			=> 'Семеен',
'CAT_FANTASY' 			=> 'Фентъзи',
'CAT_FILMNOIR' 			=> 'Film Noir',
'CAT_HORROR' 			=> 'Ужаси',
'CAT_JAMESBOND' 		=> 'Джеймс Бонд',
'CAT_MUSICVIDEO' 		=> 'Музика / Видео',
'CAT_MUSICAL' 			=> 'Мюзикъл',
'CAT_MYSTERY' 			=> 'Мистерия',
'CAT_ROMANCE' 			=> 'Романс',
'CAT_SCIFI' 			=> 'Научна фантастика',
'CAT_SHORT' 			=> 'Непълнометражен',
'CAT_THRILLER' 			=> 'Трилър',
'CAT_TVSHOWS' 			=> 'ТВ шоу',
'CAT_WAR' 				=> 'Военен',
'CAT_WESTERN' 			=> 'Уестърн',
'CAT_XRATED' 			=> 'XXX',

/* Movie Listings */
'M_MOVIE' 				=> 'Филмът',
'M_ACTORS' 				=> 'Участват',
'M_CATEGORY'		    => 'Категория',
'M_YEAR'				=> 'Година',
'M_COPIES'				=> 'Копия',
'M_FROM' 				=> 'От',
'M_TITLE' 				=> 'Заглавие',
'M_ALTTITLE' 			=> 'Alt заглавие',
'M_GRADE'				=> 'Рейтйнг',
'M_DIRECTOR' 			=> 'Режисьор',
'M_COUNTRY'				=> 'Държава',
'M_RUNTIME' 			=> 'Продължителност',
'M_MINUTES'			 	=> 'минути',
'M_PLOT' 				=> 'Сюжет',
'M_NOPLOT' 				=> 'Няма информация за сюжет',
'M_COVERS' 				=> 'Обложки',
'M_AVAILABLE' 			=> 'Налични копия',
'M_MEDIA'			 	=> 'Тип медия',
'M_NUM' 				=> 'Номер CD/та',
'M_DATE' 				=> 'Дата на прибавяне',
'M_OWNER'			 	=> 'Сложил',
'M_NOACTORS'		    => 'Няма инфо за актьори',
'M_INFO'			    => 'Информация',
'M_DETAILS'			    => 'Детайли за моето копие',
'M_MEDIATYPE'		    => 'Тип',
'M_COMMENT'			    => 'Коментар',
'M_PRIVATE'				=> 'Отбележи като личен ?',
'M_SCREENSHOTS'			=> 'Кадри',
'M_NOSCREENS'			=> 'Няма налични кадри',
'M_SHOW'				=> 'Покажи',
'M_HIDE'				=> 'Скрии',
'M_CHANGE'				=> 'Редактирай данните',
'M_NOCOVERS'			=> 'Няма налични обложки',
'M_BYCAT'				=> 'Заглавия по категорий',
'M_CURRCAT'				=> 'Текуща категория',
'M_TEXTVIEW'			=> 'Текстов вид',
'M_IMAGEVIEW'			=> 'Фотогалерия',
'M_MINEONLY'			=> 'Покажи само моите филми',
'M_SIMILAR'				=> 'Подобни',
'M_MEDIAINDEX'			=> 'Media Index',

/* IMDB */
'I_DETAILS'				=> 'IMDB Детайли',
'I_PLOT'				=> 'Обзорен сюжет',
'I_GALLERY'				=> 'Фотогалерия',
'I_TRAILERS'			=> 'Трейлъри',
'I_LINKS'				=> 'IMDB връзки',
'I_NOT'					=> 'IMDB не разполага с информация',

/* DVD Specific */
'DVD_REGION'			=> 'Region',
'DVD_FORMAT'			=> 'Format',
'DVD_ASPECT'			=> 'Aspect ratio',
'DVD_AUDIO'				=> 'Audio',
'DVD_SUBTITLES'			=> 'Subtitles',

/* My Movies */
'MY_EXPORT' 			=> 'Експортирай',
'MY_EXCEL' 				=> 'като Excel',
'MY_XML' 				=> 'Като XML',
'MY_XMLTHUMBS'			=> 'Експортирай фотогалерия като XML',
'MY_ACTIONS'			=> 'Действия',
'MY_JOIN'				=> 'Disc join',
'MY_JOINMOVIES'			=> 'Disc join филми',
'MY_JOINSUSER'			=> 'Избери потребител',
'MY_JOINSMEDIA'			=> 'Избери тип',
'MY_JOINSCAT'			=> 'Избери категория',
'MY_JOINSTYPE'			=> 'ИЗбери категория',
'MY_JOINSHOW'			=> 'Покажи резултати',
'MY_NORESULTS'			=> 'Проверката не намери резултати',
'MY_TEXTALL'			=> 'Общ изглед (Textmode)',
'MY_PWALL'				=> 'Общ изглед (Всички)',
'MY_PWMOVIES'			=> 'Общ изглед (Филми)',
'MY_PWTV'				=> 'Общ изглед (ТВ Шоу)',
'MY_PWBLUE'				=> 'Общ изглед (Сини филми)',
'MY_J1'					=> 'Филми, които имам, а потребителя няма',
'MY_J2'					=> 'Филми, които нямам, а потребителя има',
'MY_J3'					=> 'Филми, които всички имаме',
'MY_OVERVIEW'			=> 'Колекция - общо представяне',
'MY_INFO'				=> 'На тази страница ще намерите всичко за филмите.
							В дясно са действията, които можете да осъществите с колекцията.
							Можете да експортирате в ексел /за принтиране/ или да използвате XML
							експортиране за резервно копие или да преместите съдържанието на базата ви с данни
							в друга база.',
'MY_KEYS'				=> 'Редактирай дадено ID\'и',
'MY_SEENLIST'			=> 'Редактирай прегледаната листа',
'MY_HELPPICKER'			=> 'Избери филм за гледане',
'MY_HELPPICKERINFO'		=> 'Не знаеш какво да гледаш тази вечер ?<br/>Нека аз да избера вместо теб.<br/>
							Можете да създадете филтър, за да стесните търсенето
						    .',
'MY_FIND'				=> 'Намери филм',
'MY_NOTSEEN'			=> 'Предложи ми филм, който не съм гледал до момента',
'MY_FRIENDS'			=> 'Приятели, даващи CD/та на заем',


/* Manager window */
'MAN_BASIC' 			=> 'Базова информация',
'MAN_IMDB' 				=> 'IMDB инфо',
'MAN_EMPIRE' 			=> 'DVD Empire инфо',
'MAN_COPY' 				=> 'Моето копие',
'MAN_COPIES' 			=> 'Моите копия',
'MAN_NOCOPY' 			=> 'Вие нямате копия',
'MAN_1COPY' 			=> 'Копирай',
'MAN_ADDACT' 			=> 'Прибави актьори',
'MAN_ADDTODB' 			=> 'Прибави нови актьори към базата с данни',
'MAN_SAVETODB' 			=> 'Запази в базата с данни',
'MAN_SAVETODBNCD' 		=> 'Запази в базата с данни и филма',
'MAN_INDB' 				=> 'Актьори в базата с данни',
'MAN_SEL' 				=> 'Избрани актьори',
'MAN_STARS' 			=> 'Звезди',
'MAN_BROWSE'			=> 'Преглед за метоположение на файла',


/* Add movies */
'ADD_INFO' 				=> 'Избери начин на прибавяне',
'ADD_IMDB' 				=> 'Извличане от IMDB',
'ADD_IMDBTITLE' 		=> 'Търсене по ключова дума',
'ADD_MANUAL' 			=> 'Ръчно въвеждане',
'ADD_LISTED' 			=> 'Прибави филм, който е вече в листата',
'ADD_XML' 				=> 'Прибави филм от експртиран XML файл ',
'ADD_XMLFILE' 			=> 'Избери XML файл за импорт',
'ADD_XMLNOTE' 			=> '(Трябва да знаете, че само XML файлове експортирани от друго VCD-db приложение могат да импортират информация тук.Можете да експортирате вашите филми  от секция -моите филми-. Трябва да избягвате редакция на експортираните XML файлове. ) ',
'ADD_MAXFILESIZE'		=> 'Максимален размер на файла',
'ADD_DVDEMPIRE' 		=> 'Извлечи от Adult DVD Empire (Голи каки /леле-мале/)',
'ADD_LISTEDSTEP1' 		=> 'Стъпка 1-ва<br/>Избери заглавията, които искаш да прибавиш в листата си.<br/>Може да изберете
						    тип медиа при следващата стъпка.',
'ADD_LISTEDSTEP2' 		=> 'Стъпка 2-ра.<br/>Избери подходящ тип медия.',
'ADD_INDB' 				=> 'Филми в базата с данни',
'ADD_SELECTED' 			=> 'Избрани заглавия',
'ADD_INFOLIST' 			=> 'Кликни два пъти върху заглавие, за да избереш заглавие или използвай стрелките.<br/>Може да използваш клавиатурата
							за бързо намиране на заглавия.',
'ADD_NOTITLES' 			=> 'Никой от потребителите не е добавял филми в базата с данни',


/* Add from XML */
'XML_CONFIRM' 			=> 'Потвърди XML ъплоуд',
'XML_CONTAINS' 			=> 'XML файла съдържа %d филми.',
'XML_INFO1' 			=> 'Натисни потвърждавам за да продължите и запази в базата с данни.<br/>
							или откажи. ',
'XML_INFO2' 			=> 'Ако искате да включите фотогалерия (постери) с филмите, които искате да импортирате в XML 
							файла,
<b>трябва</b> също да имате XML файл за самата фотогалерия!.<br/>
							Плакатите не могат да се импортират след като сте свършили импортирането на филмите от текущия XML файл. 
							Ако вече имате XML файл, съдържащ фотогалерия, отбележете полето по-долу и при следващата стъпка, 
							след импортирането на филмите в листата долу, ще бъдете поканени да пратите вашия XML файл
							за продължение на операцията. ',
'XML_THUMBNAILS'		=> 'Вмъкни фотогалерия от XML файл, съдържащ такава ',
'XML_LIST'				=> 'Пълна листа от филми, намерени в XML файла.',
'XML_ERROR'				=> 'В XML файла не бяха открити заглавия.<br/>Файлът може да е повреден или просто празен.
			   				<br/>Сигурни ли сте, че използвате XML файл, екпортиран преди това от VCD-db..',
'XML_RESULTS'			=> 'Резултати от XML ъплоуда .',
'XML_RESULTS2'			=> 'Тук са резултатите от вашия XML импорт.<br/>Сумарно %d филма бяха импортирани.',


/* Add from DVD Empire */
'EM_INFO'				=> 'Информация ог AdultDVDEmpire.com ....',
'EM_DESC'				=> 'DVDEmpire описание',
'EM_SUBCAT'				=> 'Категории за възрастни',
'EM_DETAILS'			=> 'Adultdvdempire.com детайли',
'EM_STARS'				=> 'Порнозвезди',
'EM_NOTICE'				=> 'Актьорите маркирани в червено към момента не са във VCD-DB.
							Но можете да ги маркирате и те ще бъдат прибавени
						    и асоциирани с филма.',
'EM_FETCH'				=> 'Извлечи също',

/* Loan System */
'LOAN_MOVIES'			=> 'Филми за заем',
'LOAN_TO'				=> 'Дай на заем на',
'LOAN_ADDUSERS'			=> 'Добави длъжник',
'LOAN_NEWUSER'			=> 'Нов длъжник',
'LOAN_REGISTERUSER'		=> 'Добави длъжник',
'LOAN_NAME'				=> 'Име',
'LOAN_SELECT'			=> 'Избери',
'LOAN_MOVIELOANS'		=> 'Дадени назаем филми ...',
'LOAN_REMINDER'			=> 'Изпрати напомняне',
'LOAN_HISTORY'			=> 'Заем история',
'LOAN_HISTORY2'			=> 'Виж Заем историята ',
'LOAN_SINCE'			=> 'Преди',
'LOAN_TIME'				=> 'Време преди',
'LOAN_RETURN'			=> 'Върни копието',
'LOAN_SUCCESS'			=> 'Успешно дадени на заем',
'LOAN_OUT'				=> 'Не върнати',
'LOAN_DATEIN'			=> 'Date in',
'LOAN_DATEOUT'			=> 'Date out',
'LOAN_PERIOD'			=> 'Период за заем',
'LOAN_BACK'				=> 'Върни се в главно меню за заем',
'LOAN_DAY'				=> 'ден',
'LOAN_DAYS'				=> 'дни',
'LOAN_TODAY'			=> 'От днес',


/* RSS */
'RSS'					=> 'RSS синхронизация',
'RSS_TITLE'				=> 'RSS синхронизация от VCD-DB страници на мои приятели',
'RSS_SITE'				=> 'RSS страница за синхронизация',
'RSS_USER'				=> 'RSS на Потребител',
'RSS_VIEW'				=> 'Покажи RSS',
'RSS_ADD'				=> 'Добави',
'RSS_NOTE'				=> 'Въведи <strong>точен url</strong> на VCD базата на вашите приятели.<br/>
							Ако RSS е разрешен на страницата на вашите приятели,може да изберете
							това от което се интересувате и да го покажете на вашата страница.',
'RSS_FETCH'				=> 'Извлечи RSS листа',
'RSS_NONE'				=> 'Не бяха прибавени RSS листи.',
'RSS_FOUND'				=> 'Следните RSS листи бяха намерени, моля изберете тези които желаете да прибавите:',
'RSS_NOTFOUND'			=> 'Не бяха намерени',


/* Wishlist */
'W_ADD'					=> 'Прибави към моя списък "Желания"',
'W_ONLIST'				=> 'В твоя списък "Желания"',
'W_EMPTY'				=> 'Вашият списък "Желания" е празен',
'W_OWN'					=> 'Имам копие от този филм',
'W_NOTOWN'				=> 'Не притежавам копие от този филм',


/* Comments */
'C_COMMENTS'			=> 'Коментари',
'C_ADD'					=> 'Коментирай',
'C_NONE'				=> 'Няма коментар',
'C_TYPE'				=> 'Напиши своя коментар',
'C_YOUR'				=> 'Вашият коментар',
'C_POST'				=> 'Прати коментара',
'C_ERROR'				=> 'Трябва да сте се идентифицирали в системата, за да можете да коментирате.',


/* Pornstars */
'P_NAME'				=> 'Име',
'P_WEB'					=> 'Уеб страница',
'P_MOVIECOUNT'			=> 'Movie count',


/* Seen List */
'S_SEENIT'				=> 'Гледал съм го',
'S_NOTSEENIT'			=> 'Не съм го гледал',
'S_SEENITCLICK'			=> 'Кликни за маркиране като гледан',
'S_NOTSEENITCLICK'		=> 'Крикни за маркиране като негледан',

/* Mail messages */
'MAIL_RETURNTOPIC'		=> 'Loan reminder',
'MAIL_RETURNMOVIES1'	=> '%s, Потсто ти напомням, че имаш да ми връщаш филми.\n
							Все още имаш следните филми :\n\n',
'MAIL_RETURNMOVIES2'    => 'Моля върни дисковете колкота се може по-скоро\n Наздраве %s \n\n
							nb. това е автоматична поща от VCD-db системата (http://vcddb.konni.com)',
'MAIL_NOTIFY'  		    => '<strong>Нови филми бяха прибавени към VCD-db</strong><br/>
							 Кликни <a href="%s/?page=cd&vcd_id=%s">тук</a> за да видиш повече ..
							 <p>nb. това е автоматична поща от VCD-db системата (vcddb.konni.com)</p>',
'MAIL_REGISTER'		 	=> '%s, регистрацията във VCD-db премина успешно.\n\nТвоето потребителско име е %s а твоята парола е 
							%s.\n\nМожеш винаги да смениш паролата си когато си в системата.\n
							Кликни <a href="%s" target="_new">тук</a> за да отидеш във VCD-db страницата.',


/* Player */
'PLAYER'				=> 'Плейър',
'PLAYER_PATH'			=> 'Път',
'PLAYER_PARAM'			=> 'Параметри',
'PLAYER_NOTE'			=> 'Напиши пълния път до плейъра на твърдия диск.
							Твоя плейър трябва да може да приема параметри от командния промпт като
							BSPlayer за Win32 или MPlayer за Линукс.<br/>Можете да "изтеглите" BSPlayer от 
							<a href="http://www.bsplayer.org" target="_new">тук</a> 
							и MPlayer от <a href="http://www.MPlayerHQ.hu" target="_new">тук</a>.',



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
'X_CONTAINS'			=> 'Съдържа',
'X_GRADE'				=> 'IMDB rating more than',
'X_ANY'					=> 'Всички',
'X_TRYAGAIN'			=> 'Опитай отново',
'X_PROCEED' 			=> 'Продължи',
'X_SELECT' 				=> 'Избери',
'X_CONFIRM' 			=> 'Потвърди',
'X_CANCEL' 				=> 'Отказ',
'X_ATTENTION' 			=> 'Внимание!',
'X_STATUS' 				=> 'Статус',
'X_SUCCESS' 			=> 'Успешно',
'X_FAILURE' 			=> 'Неуспешно',
'X_YES' 				=> 'Да',
'X_NO' 					=> 'Не',
'X_SHOWMORE' 			=> 'Покажи повече',
'X_SHOWLESS' 			=> 'Покажи по-малко',
'X_NEW' 				=> 'Нов',
'X_CHANGE' 				=> 'Промени',
'X_DELETE' 				=> 'Изтрий',
'X_UPDATE' 				=> 'Обнови',
'X_SAVEANDCLOSE' 		=> 'Запази и затвори',
'X_CLOSE' 				=> 'Затвори',
'X_EDIT' 				=> 'Редакция',
'X_RESULTS' 			=> 'Резултати',
'X_LATESTMOVIES' 		=> 'Нови',
'X_LATESTTV' 			=> 'Нови ТВ шоу-та',
'X_LATESTBLUE' 			=> 'Нови Голи каки:)',
'X_MOVIES' 				=> 'филма',
'X_NOCATS' 				=> 'Няма нови прибавени филми.',
'X_NOUSERS' 			=> 'Няма активни потребители',
'X_KEY' 				=> 'Ключ',
'X_SAVENEXT' 			=> 'Запази и редактирай следващия',
'X_SAVE' 				=> 'Запази',
'X_SEEN' 				=> 'Прегледани'


);


?>