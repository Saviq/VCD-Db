<?php
/**
 * VCD-db - a web based VCD/DVD Catalog system
 * Copyright (C) 2003-2006 Konni - konni.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * @author  H��kon Birgsson <konni@konni.com>
 * @package Language
 * @version $Id$
 */

?>
<?
	/**
		English language file

	*/



$_ = array(

/* Language Identifier */
'LANG_TYPE' 			=> 'ENG',
'LANG_NAME' 			=> 'English',
'LANG_CHARSET'		 	=> 'UTF-8',

/* Menu system */
'MENU_MINE' 			=> 'My menu',
'MENU_SETTINGS' 		=> 'My settings',
'MENU_MOVIES' 			=> 'My movies',
'MENU_ADDMOVIE' 		=> 'Add new movie',
'MENU_LOANSYSTEM'		=> 'Loan system',
'MENU_WISHLIST' 		=> 'My Wishlist',
'MENU_CATEGORIES' 		=> 'Movie categories',
'MENU_RSS' 				=> 'My Rss Feeds',
'MENU_CONTROLPANEL' 	=> 'Control Panel',
'MENU_REGISTER' 		=> 'Register',
'MENU_LOGOUT' 			=> 'Logout',
'MENU_SUBMIT'			=> 'Submit',
'MENU_TOPUSERS'			=> 'Top users',
'MENU_WISHLISTPUBLIC'	=> 'Others wishlists',
'MENU_STATISTICS'		=> 'Statistics',

/* Login */
'LOGIN' 				=> 'Login',
'LOGIN_USERNAME' 		=> 'Username',
'LOGIN_PASSWORD' 		=> 'Password',
'LOGIN_REMEMBER' 		=> 'Remember me',
'LOGIN_INFO' 			=> 'Leave this empty if you do <b>not</b> want to change your password',

/* Register */
'REGISTER_TITLE'		=> 'Registration',
'REGISTER_FULLNAME' 	=> 'Full name',
'REGISTER_EMAIL' 		=> 'Email',
'REGISTER_AGAIN' 		=> 'Password again',
'REGISTER_DISABLED' 	=> 'Sorry, Administrator has disabled registration at the moment',
'REGISTER_OK' 			=> 'Registration was successful, you can now logon to VCD-db.',

/* User Properties */
'PRO_NOTIFY' 			=> 'Send me an email when new movie is added?',
'PRO_SHOW_ADULT' 		=> 'Show adult content on the site?',
'PRO_RSS' 				=> 'Allow RSS feed from my movie list?',
'PRO_WISHLIST' 			=> 'Allow others to see my wishlist?',
'PRO_USE_INDEX' 		=> 'Use index number fields for custom media ID\'s?',
'PRO_SEEN_LIST' 		=> 'Keep track of movies that I\'ve seen?',
'PRO_PLAYOPTION' 		=> 'Use client playback options?',
'PRO_NFO' 				=> 'Enable NFO files?',
'PRO_DEFAULT_IMAGE'		=> 'Should Image View be default?',

/* User Settings */
'SE_PLAYER' 			=> 'Player settings',
'SE_OWNFEED' 			=> 'View my own feed',
'SE_CUSTOM' 			=> 'Customize my frontpage',
'SE_SHOWSTAT' 			=> 'Show statistics',
'SE_SHOWSIDE' 			=> 'Show new movies in sidebar',
'SE_SELECTRSS' 			=> 'Select RSS feeds',
'SE_PAGELOOK' 			=> 'Web layout',
'SE_PAGEMODE' 			=> 'Select default template:',
'SE_UPDATED'			=> 'User information updated',
'SE_UPDATE_FAILED'		=> 'Failed to update',

/* Search */
'SEARCH' 				=> 'Search',
'SEARCH_TITLE' 			=> 'By title',
'SEARCH_ACTOR' 			=> 'By actor',
'SEARCH_DIRECTOR' 		=> 'By director',
'SEARCH_RESULTS' 		=> 'Search results',
'SEARCH_EXTENDED' 		=> 'Detailed search',
'SEARCH_NORESULT' 		=> 'Search returned no results',

/* Movie categories*/
'CAT_ACTION' 			=> 'Action',
'CAT_ADULT' 			=> 'Adult',
'CAT_ADVENTURE' 		=> 'Adventure',
'CAT_ANIMATION' 		=> 'Animation',
'CAT_ANIME' 			=> 'Anime / Manga',
'CAT_COMEDY' 			=> 'Comedy',
'CAT_CRIME' 			=> 'Crime',
'CAT_DOCUMENTARY' 		=> 'Documentary',
'CAT_DRAMA' 			=> 'Drama',
'CAT_FAMILY' 			=> 'Family',
'CAT_FANTASY' 			=> 'Fantasy',
'CAT_FILMNOIR' 			=> 'Film Noir',
'CAT_HORROR' 			=> 'Horror',
'CAT_JAMESBOND' 		=> 'James Bond',
'CAT_MUSICVIDEO' 		=> 'Music Video',
'CAT_MUSICAL' 			=> 'Musical',
'CAT_MYSTERY' 			=> 'Mystery',
'CAT_ROMANCE' 			=> 'Romance',
'CAT_SCIFI' 			=> 'Sci-Fi',
'CAT_SHORT' 			=> 'Short',
'CAT_THRILLER' 			=> 'Thriller',
'CAT_TVSHOWS' 			=> 'TV Shows',
'CAT_WAR' 				=> 'War',
'CAT_WESTERN' 			=> 'Western',
'CAT_XRATED' 			=> 'X-Rated',

/* Movie Listings */
'M_MOVIE' 				=> 'The Movie',
'M_ACTORS' 				=> 'Cast',
'M_CATEGORY'		    => 'Category',
'M_YEAR'				=> 'Production year',
'M_COPIES'				=> 'Copies',
'M_FROM' 				=> 'From',
'M_TITLE' 				=> 'Title',
'M_ALTTITLE' 			=> 'Alt title',
'M_GRADE'				=> 'Rating',
'M_DIRECTOR' 			=> 'Director',
'M_COUNTRY'				=> 'Production country',
'M_RUNTIME' 			=> 'Runtime',
'M_MINUTES'			 	=> 'minutes',
'M_PLOT' 				=> 'Plot',
'M_NOPLOT' 				=> 'No Plot summary available',
'M_COVERS' 				=> 'CD Covers',
'M_AVAILABLE' 			=> 'Available copies',
'M_MEDIA'			 	=> 'Medium',
'M_NUM' 				=> 'Num CD\'s',
'M_DATE' 				=> 'Date added',
'M_OWNER'			 	=> 'Owner',
'M_NOACTORS'		    => 'No actor listing available',
'M_INFO'			    => 'Movie information',
'M_DETAILS'			    => 'Details on my copy',
'M_MEDIATYPE'		    => 'Media type',
'M_COMMENT'			    => 'Comment',
'M_PRIVATE'				=> 'Mark private ?',
'M_SCREENSHOTS'			=> 'Screenshots',
'M_NOSCREENS'			=> 'No screenshots available',
'M_SHOW'				=> 'Show',
'M_HIDE'				=> 'Hide',
'M_CHANGE'				=> 'Change information',
'M_NOCOVERS'			=> 'No CD-Covers available',
'M_BYCAT'				=> 'Titles by category',
'M_CURRCAT'				=> 'Current category',
'M_TEXTVIEW'			=> 'Text view',
'M_IMAGEVIEW'			=> 'Image view',
'M_MINEONLY'			=> 'Show only my movies',
'M_SIMILAR'				=> 'Similar movies',
'M_MEDIAINDEX'			=> 'Media Index',

/* IMDB */
'I_DETAILS'				=> 'IMDB Details',
'I_PLOT'				=> 'Plot Summary',
'I_GALLERY'				=> 'Photo Gallery',
'I_TRAILERS'			=> 'Trailers',
'I_LINKS'				=> 'IMDB Links',
'I_NOT'					=> 'No IMDB information availble',

/* DVD Specific */
'DVD_REGION'			=> 'Region',
'DVD_FORMAT'			=> 'Format',
'DVD_ASPECT'			=> 'Aspect ratio',
'DVD_AUDIO'				=> 'Audio',
'DVD_SUBTITLES'			=> 'Subtitles',


/* My Movies */
'MY_EXPORT' 			=> 'Export data',
'MY_EXCEL' 				=> 'Export as Excel',
'MY_XML' 				=> 'Export as XML',
'MY_XMLTHUMBS'			=> 'Export thumbnails as XML',
'MY_ACTIONS'			=> 'My actions',
'MY_JOIN'				=> 'Disc join',
'MY_JOINMOVIES'			=> 'Disc join movies',
'MY_JOINSUSER'			=> 'Select user',
'MY_JOINSMEDIA'			=> 'Select media type',
'MY_JOINSCAT'			=> 'Select category',
'MY_JOINSTYPE'			=> 'Select action',
'MY_JOINSHOW'			=> 'Show results',
'MY_NORESULTS'			=> 'Query returned no results',
'MY_TEXTALL'			=> 'Printview (Textmode)',
'MY_PWALL'				=> 'Printview (All)',
'MY_PWMOVIES'			=> 'Printview (Movies)',
'MY_PWTV'				=> 'Printview (Tv Shows)',
'MY_PWBLUE'				=> 'Printview (Blue movies)',
'MY_J1'					=> 'Movies i got but user not',
'MY_J2'					=> 'Movies that user owns but i dont',
'MY_J3'					=> 'Movies we both own',
'MY_OVERVIEW'			=> 'Collection overview',
'MY_INFO'				=> 'On this page you can find out everything about my movies.
							To the right are actions you can run on your movie collection.
							You can also export your list as Excel for printing or use the XML
							export functions for backup or to move all your collection data from one
							VCD-db to another.',
'MY_KEYS'				=> 'Edit Custom ID\'s',
'MY_SEENLIST'			=> 'Edit Seen list',
'MY_HELPPICKER'			=> 'Pick a movie to watch',
'MY_HELPPICKERINFO'		=> 'Don\'t know what to watch tonight ?<br/>Let VCD-db help you find a movie.<br/>
							You can optionally create filters and use them to narrow the suggested selections
						    by VCD-db.',
'MY_FIND'				=> 'Find a movie',
'MY_NOTSEEN'			=> 'Suggest only movies I haven\'t seen',
'MY_FRIENDS'			=> 'My friends who borrow CD\'s',


/* Manager window */
'MAN_BASIC' 			=> 'Basic information',
'MAN_IMDB' 				=> 'IMDB info',
'MAN_EMPIRE' 			=> 'DVDEmpire info',
'MAN_COPY' 				=> 'My copy',
'MAN_COPIES' 			=> 'My copies',
'MAN_NOCOPY' 			=> 'You have no copies',
'MAN_1COPY' 			=> 'Copy',
'MAN_ADDACT' 			=> 'Add actors',
'MAN_ADDTODB' 			=> 'Add new actors to DB',
'MAN_SAVETODB' 			=> 'Save to DB',
'MAN_SAVETODBNCD' 		=> 'Save to DB and movie',
'MAN_INDB' 				=> 'Actors in database',
'MAN_SEL' 				=> 'Selected actors',
'MAN_STARS' 			=> 'Stars',
'MAN_BROWSE'			=> 'Browse for file location',
'MAN_ADDMEDIA'			=> 'Add...',

/* Add movies */
'ADD_INFO' 				=> 'Select method to add a new movie',
'ADD_IMDB' 				=> 'Fetch from Internet Movie Database',
'ADD_IMDBTITLE' 		=> 'Enter keywords to search by',
'ADD_MANUAL' 			=> 'Enter data manually',
'ADD_LISTED' 			=> 'Add movies already listed',
'ADD_XML' 				=> 'Add movies from exported XML file',
'ADD_XMLFILE' 			=> 'Select XML file to import',
'ADD_XMLNOTE' 			=> '(Please note, only XML files that have been exported from another VCD-db application
							can be used to import your movies here. You can export your movies from the "My movies"
							section. You should avoid manual editing of the exported XML files.) ',
'ADD_EXCEL' 			=> 'Add movies from Excel file',
'ADD_EXCELFILE' 		=> 'Select Excel file to import',
'ADD_EXCELNOTE' 		=> '(To find out the format for the Excel file, first <a href="./exec_query.php?action=export&type=excel">
							export your movies to an Excel file</a>.)',
'ADD_MAXFILESIZE'		=> 'Max filesize',
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
'XML_THUMBNAILS'		=> 'Insert thumbnails from my thumbnails XML file ',
'XML_LIST'				=> 'Full list of movies found in XML file.',
'XML_ERROR'				=> 'No titles found in XML file.<br/>File could be damaged or just plain empty.
			   				<br/>Make sure that you are using XML file that was exported from the VCD-db...',
'XML_RESULTS'			=> 'XML upload results.',
'XML_RESULTS2'			=> 'Here are the results on your XML import.<br/>Total %d movies were imported.',


/* Add from Excel */
'EXCEL_CONFIRM' 		=> 'Confirm Excel upload',
'EXCEL_CONTAINS' 		=> 'Excel file contains %d movies.',
'EXCEL_INFO1' 			=> 'Press confirm to process the movies and save to the database.<br/>
							Or press cancel to bail out. ',
'EXCEL_LIST'			=> 'Full list of movies found in Excel file.',
'EXCEL_ERROR'			=> 'No titles found in Excel file.<br/>File could be damaged or just plain empty.
			   				<br/>Make sure that you are using Excel file that was exported from the VCD-db...',
'EXCEL_RESULTS'			=> 'Excel upload results.',
'EXCEL_RESULTS2'		=> 'Here are the results on your Excel import.<br/>Total %d movies were imported.',


/* Add from DVD Empire */
'EM_INFO'				=> 'Information from AdultDVDEmpire.com ....',
'EM_DESC'				=> 'DVDEmpire description',
'EM_SUBCAT'				=> 'Adult categories',
'EM_DETAILS'			=> 'Adultdvdempire.com details',
'EM_STARS'				=> 'Pornstars',
'EM_NOTICE'				=> 'Actors marked red are currently not in the VCD-DB.
							But you can check their names and they will be automatically added to the VCD-db
						    and associated with this movie.',
'EM_FETCH'				=> 'Fetch Also',

/* Loan System */
'LOAN_MOVIES'			=> 'Movies to borrow',
'LOAN_TO'				=> 'Borrow movies to',
'LOAN_ADDUSERS'			=> 'Add some users to borrow to continue',
'LOAN_NEWUSER'			=> 'New borrower',
'LOAN_REGISTERUSER'		=> 'Add new borrower',
'LOAN_NAME'				=> 'Name',
'LOAN_SELECT'			=> 'Select borrower',
'LOAN_MOVIELOANS'		=> 'Borrowed movies ...',
'LOAN_REMINDER'			=> 'Send reminder',
'LOAN_HISTORY'			=> 'Loan history',
'LOAN_HISTORY2'			=> 'See loan history',
'LOAN_SINCE'			=> 'Since',
'LOAN_TIME'				=> 'Time since',
'LOAN_RETURN'			=> 'Return copy',
'LOAN_SUCCESS'			=> 'Movies successfully loaned',
'LOAN_OUT'				=> 'Not returned',
'LOAN_DATEIN'			=> 'Date in',
'LOAN_DATEOUT'			=> 'Date out',
'LOAN_PERIOD'			=> 'Loan period',
'LOAN_BACK'				=> 'Back to loan index',
'LOAN_DAY'				=> 'day',
'LOAN_DAYS'				=> 'days',
'LOAN_TODAY'			=> 'from today',


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
'W_ADD'					=> 'Add to my wishlist',
'W_ONLIST'				=> 'On your wishlist',
'W_EMPTY'				=> 'Your wistlist is empty',
'W_OWN'					=> 'I own a copy of this movie',
'W_NOTOWN'				=> 'I do not own a copy of this movie',


/* Comments */
'C_COMMENTS'			=> 'Comments',
'C_ADD'					=> 'Post new comment',
'C_NONE'				=> 'No comments have been posted',
'C_TYPE'				=> 'Type in your new comment',
'C_YOUR'				=> 'Your comment',
'C_POST'				=> 'Post comment',
'C_ERROR'				=> 'You have be logged in to post a comment',


/* Pornstars */
'P_NAME'				=> 'Name',
'P_WEB'					=> 'Website',
'P_MOVIECOUNT'			=> 'Movie count',


/* Seen List */
'S_SEENIT'				=> 'I\'ve seen it',
'S_NOTSEENIT'			=> 'I\'ve not seen it',
'S_SEENITCLICK'			=> 'Click to mark seen',
'S_NOTSEENITCLICK'		=> 'Click to mark unseen',

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
'MAIL_RETURNMOVIES1'	=> '%s, just wanted to remind you to return my movies.\n
							You still have the following movies :\n\n',
'MAIL_RETURNMOVIES2'    => 'Please return the discs as soon as possible\n Cheers %s \n\n
							nb. this is an automated e-mail from the VCD-db system (http://vcddb.konni.com)',
'MAIL_NOTIFY'  		    => '<strong>New movie has been added to VCD-db</strong><br/>
							 Click <a href="%s/?page=cd&vcd_id=%s">here</a> to see more ..
							 <p>nb. this is an automated e-mail from the VCD-db (vcddb.konni.com)</p>',
'MAIL_REGISTER'		 	=> '%s, registration to VCD-db was successful.<br><br>Your username is %s and your password is
							%s.<br><br>You can always change your password after you have logged in.<br>
							Click <a href="%s" target="_new">here</a> to goto the VCD-db website.',


/* Player */
'PLAYER'				=> 'Player',
'PLAYER_PATH'			=> 'Path',
'PLAYER_PARAM'			=> 'Parameters',
'PLAYER_NOTE'			=> 'Enter the full path to your movie player on your harddrive.
							Your player must be able to take parameters as command line such as the
							BSPlayer for Win32 or the MPlayer for Linux.<br/>You can download BSPlayer for free
							<a href="http://www.bsplayer.org" target="_new">here</a>
							and the MPlayer <a href="http://www.MPlayerHQ.hu" target="_new">here</a>.',


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
'X_CONTAINS'			=> 'contains',
'X_GRADE'				=> 'IMDB rating more than',
'X_ANY'					=> 'Any',
'X_TRYAGAIN'			=> 'Try again',
'X_PROCEED' 			=> 'Proceed',
'X_SELECT' 				=> 'Select',
'X_CONFIRM' 			=> 'Confirm',
'X_CANCEL' 				=> 'Cancel',
'X_ATTENTION' 			=> 'Attention!',
'X_STATUS' 				=> 'Status',
'X_SUCCESS' 			=> 'Success',
'X_FAILURE' 			=> 'Failure',
'X_YES' 				=> 'Yes',
'X_NO' 					=> 'No',
'X_SHOWMORE' 			=> 'Show more',
'X_SHOWLESS' 			=> 'Show less',
'X_NEW' 				=> 'New',
'X_CHANGE' 				=> 'change',
'X_DELETE' 				=> 'delete',
'X_UPDATE' 				=> 'Update',
'X_SAVEANDCLOSE' 		=> 'Save and close',
'X_CLOSE' 				=> 'Close',
'X_EDIT' 				=> 'Edit',
'X_RESULTS' 			=> 'Results',
'X_LATESTMOVIES' 		=> 'latest movies',
'X_LATESTTV' 			=> 'latest TV shows',
'X_LATESTBLUE' 			=> 'latest X-rated',
'X_MOVIES' 				=> 'movies',
'X_NOCATS' 				=> 'No movies have been added.',
'X_NOUSERS' 			=> 'No active users',
'X_KEY' 				=> 'Key',
'X_SAVENEXT' 			=> 'Save and edit next',
'X_SAVE' 				=> 'Save',
'X_SEEN' 				=> 'Seen',
'X_FOOTER'				=> 'Page Loaded in %s sec. (<i>%d Queries</i>) &nbsp; Copyright (c)',
'X_FOOTER_LINK'			=> 'Check out the official VCD-db website',
'X_TOGGLE'				=> 'Toggle preview',
'X_TOGGLE_ON'			=> 'on',
'X_TOGGLE_OFF'			=> 'off'

);


?>