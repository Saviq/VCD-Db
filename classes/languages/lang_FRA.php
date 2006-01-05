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
		French language file
		Thanks to C�dric FOURNIER for the translation

	*/


$_ = array(

/* Language Identifier */
'LANG_TYPE' 		 	 =>  'FRA',
'LANG_NAME' 		 	 =>  'Fran�ais',
'LANG_CHARSET'			 =>  'iso-8859-1',

/* Menu system */
'MENU_MINE' 		 	 =>  'Menu',
'MENU_SETTINGS' 		 =>  'Mes param�tres',
'MENU_MOVIES' 		 	 =>  'Mes films',
'MENU_ADDMOVIE' 		 =>  'Ajouter film',
'MENU_LOANSYSTEM' 		 =>  'Gestions des pr�ts',
'MENU_WISHLIST' 		 =>  'Demandes',
'MENU_CATEGORIES' 		 =>  'Cat�gories de films',
'MENU_RSS' 		 		 =>  'Mes flux RSS',
'MENU_CONTROLPANEL' 	 =>  'Panneau de contr�le',
'MENU_REGISTER' 		 =>  'Cr�er un compte',
'MENU_LOGOUT' 		 	 =>  'Quitter',
'MENU_SUBMIT' 			 =>  'Accepter',
'MENU_TOPUSERS' 		 =>  'Utilisateurs',
'MENU_WISHLISTPUBLIC' 	 =>  'Demandes des autres',
'MENU_STATISTICS'		 =>  'Statistiques',

/* Login */
'LOGIN' 				 =>  'Login',
'LOGIN_USERNAME' 		 =>  'Nom d\'utilisateur',
'LOGIN_PASSWORD' 		 =>  'mot de passe',
'LOGIN_REMEMBER' 		 =>  'M�moriser login',
'LOGIN_INFO' 		 	 =>  'Laisser cette case vide si vous ne voulez pas changer votre mot de passe',

/* Register */
'REGISTER_TITLE' 		 =>  'Enregistrement',
'REGISTER_FULLNAME' 	 =>  'Nom complet',
'REGISTER_EMAIL' 		 =>  'Email',
'REGISTER_AGAIN' 		 =>  'Re-taper le mot de passe',
'REGISTER_DISABLED' 	 =>  'D�sol�, l\'administrateur de ce site a d�sactiv� l\'enregistrement',
'REGISTER_OK' 		 	 =>  'Enregistrement termin� avec succ�s, vous pouvez maintenant vous connecter',

/* User Properties */
'PRO_NOTIFY' 			=> 'Envoyer un email lors de l\'ajout d\'un nouveau film ?',
'PRO_SHOW_ADULT' 		=> 'Voir le contenu r�serv� aux adultes sur le site ?',
'PRO_RSS' 				=> 'Autoriser les flux RSS depuis ma liste de films ?',
'PRO_WISHLIST'    		=> 'Autoriser les autres � voir ma liste de d�sid�ratas ?',
'PRO_USE_INDEX'   		=> 'Utiliser le num�ro d\'indice comme identifiant des m�dia personnels',
'PRO_SEEN_LIST'  		=> 'Conserver un historiques des films que j\'ai vus',
'PRO_PLAYOPTION' 		=> 'Autoriser l\'utilisation d\'un visualiseur',
'PRO_NFO' 				=> 'Employez le dossier de NFO?',

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
'SEARCH' 				 =>  'Rechercher',
'SEARCH_TITLE' 		 	 =>  'Par titre',
'SEARCH_ACTOR' 		 	 =>  'Par acteur',
'SEARCH_DIRECTOR' 		 =>  'Par r�alisateur',
'SEARCH_RESULTS' 		 =>  'R�sultats',
'SEARCH_EXTENDED' 		 =>  'Recherche d�taill�e',
'SEARCH_NORESULT' 		 =>  'Aucun film trouv�',

/* Movie categories*/
'CAT_ACTION' 		 	 =>  'Action',
'CAT_ADULT' 		 	 =>  'Adulte',
'CAT_ADVENTURE' 		 =>  'Aventure',
'CAT_ANIMATION' 		 =>  'Animation',
'CAT_ANIME' 			 =>  'Anime / Manga',
'CAT_COMEDY' 			 =>  'Com�die',
'CAT_CRIME' 		 	 =>  'Meurtres',
'CAT_DOCUMENTARY' 		 =>  'Documentaire',
'CAT_DRAMA' 		 	 =>  'Drame',
'CAT_FAMILY' 		 	 =>  'Famille',
'CAT_FANTASY' 		 	 =>  'Fantastique',
'CAT_FILMNOIR' 		 	 =>  'Film Noir',
'CAT_HORROR' 		 	 =>  'Horreur',
'CAT_JAMESBOND' 		 =>  'James Bond',
'CAT_MUSICVIDEO' 		 =>  'Vid�o musicale',
'CAT_MUSICAL' 			 =>  'Musical',
'CAT_MYSTERY' 		 	 =>  'Myst�re',
'CAT_ROMANCE' 		 	 =>  'Romance',
'CAT_SCIFI' 		 	 =>  'Science Fiction',
'CAT_SHORT' 		 	 =>  'Short',
'CAT_THRILLER' 		 	 =>  'Policier',
'CAT_TVSHOWS' 		 	 =>  'Emissions TV',
'CAT_WAR' 		 		 =>  'Guerre',
'CAT_WESTERN' 			 =>  'Western',
'CAT_XRATED' 		 	 =>  'Films X',

/* Movie Listings */
'M_MOVIE' 		 		 =>  'Film',
'M_ACTORS' 				 =>  'Distribution',
'M_CATEGORY' 			 =>  'Cat�gorie',
'M_YEAR' 				 =>  'Ann�e de production',
'M_COPIES' 				 =>  'Nombre de disques',
'M_FROM' 				 =>  'Donn�es de ',
'M_TITLE' 				 =>  'Titre',
'M_ALTTITLE' 			 =>  'Autre titre',
'M_GRADE' 				 =>  'Evaluation',
'M_DIRECTOR' 			 =>  'R�alisateur',
'M_COUNTRY' 			 =>  'Pays de production',
'M_RUNTIME' 			 =>  'Dur�e',
'M_MINUTES' 			 =>  'minutes',
'M_PLOT' 				 =>  'Description',
'M_NOPLOT' 				 =>  'Pas de description disponible',
'M_COVERS' 				 =>  'Jaquette',
'M_AVAILABLE' 			 =>  'Nombre dispo',
'M_MEDIA' 				 =>  'M�dia',
'M_NUM' 		 		 =>  'Nb. disques',
'M_DATE' 		 		 =>  'Date d\'ajout',
'M_OWNER' 		 		 =>  'Propri�taire',
'M_NOACTORS' 			 =>  'Aucun acteur n\'est enregistr� pour ce film',
'M_INFO' 		 		 =>  'Informations sur le film',
'M_DETAILS' 		 	 =>  'Details de ma version',
'M_MEDIATYPE' 		 	 =>  'M�dia',
'M_COMMENT' 		 	 =>  'Commentaire',
'M_PRIVATE' 		 	 =>  'Marquer comme priv�e ?',
'M_SCREENSHOTS' 		 =>  'Copies d\'�crans',
'M_NOSCREENS' 	 		 =>  'Pas de copie d\'�cran disponible',
'M_SHOW' 		 	 	 =>  'Voir',
'M_HIDE' 		 		 =>  'Cacher',
'M_CHANGE' 				 =>  'Changer les informations',
'M_NOCOVERS' 			 =>  'Pas de jaquette disponible',
'M_BYCAT' 				 =>  'Titres par cat�gories',
'M_CURRCAT' 			 =>  'Cat�gorie',
'M_TEXTVIEW' 		 	 =>  'Format texte',
'M_IMAGEVIEW' 			 =>  'Format images',
'M_MINEONLY'		     =>  'Show only my movies',
'M_SIMILAR'				 =>  'Similar movies',
'M_MEDIAINDEX'			 =>  'Num�ro d\'indice',

/* IMDB */
'I_DETAILS' 		 	 =>  'D�tails IMDB',
'I_PLOT' 		 		 =>  'Afficher le r�sum�',
'I_GALLERY' 		 	 =>  'Galerie de photos',
'I_TRAILERS' 		 	 =>  'Bande-annonces',
'I_LINKS' 		 	 	 =>  'Liens IMDB',
'I_NOT' 		 		 =>  'Aucune information provenant de IMDB disponible',

/* DVD Specific */
'DVD_REGION'			=> 'Region',
'DVD_FORMAT'			=> 'Format',
'DVD_ASPECT'			=> 'Aspect ratio',
'DVD_AUDIO'				=> 'Audio',
'DVD_SUBTITLES'			=> 'Subtitles',

/* My Movies */
'MY_EXPORT' 		 	 =>  'Exporter Donn�es',
'MY_EXCEL' 		 		 =>  'Exporter au format Excel',
'MY_XML' 		 		 =>  'Exporter au format XML',
'MY_XMLTHUMBS' 		 	 =>  'Exporter les vignettes au format XML',
'MY_ACTIONS' 		 	 =>  'Mes actions',
'MY_JOIN' 		 		 =>  'Fusion de donn�es',
'MY_JOINMOVIES' 		 =>  'Fusion de donn�es des films',
'MY_JOINSUSER' 			 =>  'S�lectionner l\'utilisateur',
'MY_JOINSMEDIA' 		 =>  'S�lectionner le type de media',
'MY_JOINSCAT' 		 	 =>  'S�lectionner la cat�gorie',
'MY_JOINSTYPE' 		 	 =>  'S�lectionner une action',
'MY_JOINSHOW' 		 	 =>  'Voir r�sultats',
'MY_NORESULTS' 			 =>  'Pas de films trouv�s',
'MY_TEXTALL'			 =>  'Aper�u (Text)',
'MY_PWALL' 		 		 =>  'Aper�u (Tous)',
'MY_PWMOVIES' 			 =>  'Aper�u (Films)',
'MY_PWTV' 		 		 =>  'Aper�u (Emissions TV)',
'MY_PWBLUE' 		 	 =>  'Aper�u (Films Bleus)',
'MY_J1' 		 		 =>  'Films que je poss�de, mais pas l\'utilisateur',
'MY_J2' 		 		 =>  'Films que poss�de l\'utilisateur, mais pas moi',
'MY_J3' 		 		 =>  'Films que nous poss�dons tous les deux',
'MY_OVERVIEW' 			 =>  'Vue d\'ensemble collection',
'MY_INFO' 		 		 =>  'Sur cette page, vous trouverez tous mes films DVD. Sur la droite, se trouvent les actions que vous pouvez faire sur votre collection. Vous pouvez aussi exporter votre liste au format Excel(r) pour l\'imprimer, ou utiliser l\'export XML pour faire une sauvegarde ou pour �changer vos films avec d\'autres base de donn�es VCD-db.',
'MY_KEYS' 		 		 =>  'Editer les ID personnalis�s',
'MY_SEENLIST' 		 	 =>  'Editer la liste des films vus',
'MY_HELPPICKER' 		 =>  'S�lectionner un film',
'MY_HELPPICKERINFO' 	 =>  'Vous ne savez pas quoi voir aujourd\'hui ?
							  Laisser cette base de donn�e choisir pour vous
							  Vous pouvez aussi cr�er des filtres et les utiliser pour faciliter le choix de VCD-db.',
'MY_FIND' 				 =>  'Chercher un film',
'MY_NOTSEEN' 			 =>  'Sugg�rer uniquement les films que je n\'ai pas vus',
'MY_FRIENDS'		     => 'My friends who borrow CD\'s',




/* Manager window */
'MAN_BASIC' 			 =>  'Informations de base',
'MAN_IMDB' 				 =>  'Infos IMDB',
'MAN_EMPIRE' 		     =>  'Infos DVDEmpire',
'MAN_COPY' 		 		 =>  'Ma copie',
'MAN_COPIES' 		 	 =>  'Mes copies',
'MAN_NOCOPY' 		 	 =>  'Vous n\'avez pas de copies',
'MAN_1COPY' 			 =>  'Copie',
'MAN_ADDACT' 			 =>  'Ajouter acteur',
'MAN_ADDTODB' 			 =>  'Ajouter de nouveau acteurs � la base',
'MAN_SAVETODB' 			 =>  'Enregistrer dans la base',
'MAN_SAVETODBNCD' 		 =>  'Enregistrer dans la base et dans le film',
'MAN_INDB' 				 =>  'Acteurs dans la base',
'MAN_SEL' 				 =>  'S�lectionner un acteur',
'MAN_STARS' 			 =>  'Stars',
'MAN_BROWSE'			 => 'Browse for file location',

/* Add movies */
'ADD_INFO' 		 		 =>  'S�lectionner la m�thode d\'ajout d\'un nouveau film',
'ADD_IMDB' 		 		 =>  'R�cup�rer depuis internet',
'ADD_IMDBTITLE' 		 =>  'Entrer le(s) mot(s) cl�',
'ADD_MANUAL' 			 =>  'Rentrer les donn�es manuellement',
'ADD_LISTED' 		 	 =>  'Ajouter des films d�j� list�s',
'ADD_XML' 		 		 =>  'Ajouter des films depuis un fichier XML',
'ADD_XMLFILE' 		 	 =>  'S�lectionner fichier XML ',
'ADD_XMLNOTE' 		 	 =>  '(Veuillez noter que seuls des fichier XML venant d\'une autre application VCD-db peuvent servir � importer vous films. Vous pouvez exporter vos films depuis la section "Mes films". Il est fortement d�conseill� de modifier le fichier XML export�).',
'ADD_MAXFILESIZE' 		 =>  'Taille max.',
'ADD_DVDEMPIRE' 		 =>  'R�cup�rer depuis DVD Empire (films X)',
'ADD_LISTEDSTEP1' 		 =>  'Etape 1 S�lectionner les titres � ajouter � la liste.
							  Vous pourrez choisir le m�dia � l\'�tape suivante.',
'ADD_LISTEDSTEP2' 		 =>  'Etape 2 S�lectionner le m�dia.',
'ADD_INDB' 				 =>  'Films list�s dans VCD-DB',
'ADD_SELECTED' 		 	 =>  'S�lectionner les titres',
'ADD_INFOLIST' 		 	 =>  'Double cliquez sur le titre pour le s�lectionner ou utilisez les fl�ches.
							  Vous pouvez utiliser le clavier pour une recherche rapide',
'ADD_NOTITLES' 		 	 =>  'Aucun autre utilisateur n\'a ajouter de films',




/* Add from XML */
'XML_CONFIRM' 		 	 =>  'Confirmer le chargement XML',
'XML_CONTAINS' 		 	 =>  'Le fichier XML continent %d films.',
'XML_INFO1' 			 =>  'Appuyez sur confirmer pour ajouter les informations du film et enregistrer la base.
							  Ou sur Cancel pour annuler.',
'XML_INFO2' 		 	 =>  'Si vous voulez inclure une vignette (poster) au film que vous importer via un fichier XML, vous devez avoir d�s maintenant acc�s au fichier XML contenant les vignettes!
							  Les posters ne peuvent �tre import�s apr�s la fin de l\'import depuis le fichier XML actuel. Si vous avez d�j� le fichier XML des vignettes, cochez le champ ci-dessous, ainsi, apr�s l\'import de vos films dans la liste ci-dessous, il vous sera demand� de choisir le fichier XML des vignettes.',
'XML_THUMBNAILS' 		 =>  'Ins�rer des vignettes depuis un fichier XML ',
'XML_LIST' 			 	 =>  'Liste compl�te des films pr�sents dans le fichier XML.',
'XML_ERROR' 		 	 =>  'Aucun titres trouv�s dans le fichier XML.
							  Le fichier est peut-�tre endommag�ou simplement vide.
							  Assurez vous d\'avoir choisi le bon fichier XML pour l\'import des donn�es.',
'XML_RESULTS' 		 	 =>  'R�sultat d\'import XML.',
'XML_RESULTS2' 		 	 =>  'Voici le r�sultat de votre import XML.
							  Au total, %d films ont �t� r�cup�r�s.',




/* Add from DVD Empire */
'EM_INFO' 		 		 =>  'Informations de AdultDVDEmpire.com ....',
'EM_DESC' 		 		 =>  'D�scription DVDEmpire',
'EM_SUBCAT' 		 	 =>  'Cat�gories Adultes',
'EM_DETAILS' 		 	 =>  'D�tails Adultdvdempire.com',
'EM_STARS' 				 =>  'Pornstars',
'EM_NOTICE' 			 =>  'Les acteurs marqu�s en rouge ne sont pas dans la base. Vous pouvez n�anmoins choisir leur nom, et ils seront automatiquement ajout�s, et associ�s � ce film.',
'EM_FETCH' 				 =>  'R�cup�rer aussi ',



/* Loan System */
'LOAN_MOVIES' 			 =>  'Films � pr�ter',
'LOAN_TO' 		 		 =>  'Pr�ter le(s) film(s) � ',
'LOAN_ADDUSERS' 		 =>  'Ajouter des emprunteurs pour poursuivre',
'LOAN_NEWUSER' 		 	 =>  'Nouvel emprunteur',
'LOAN_REGISTERUSER' 	 =>  'Ajouter un nouvel emprunteur',
'LOAN_NAME' 			 =>  'Nom',
'LOAN_SELECT' 		 	 =>  'S�lectionner l\'emprunteur',
'LOAN_MOVIELOANS' 		 =>  'Films pr�t�s ...',
'LOAN_REMINDER' 		 =>  'Envoyer un rappel',
'LOAN_HISTORY' 		 	 =>  'Historique des pr�ts',
'LOAN_HISTORY2' 		 =>  'Voir l\'historique des pr�ts',
'LOAN_SINCE' 			 =>  'Depuis',
'LOAN_TIME' 			 =>  'Temps �coul�',
'LOAN_RETURN' 			 =>  'Retour du film',
'LOAN_SUCCESS' 			 =>  'Fichier pr�t�',
'LOAN_OUT' 				 =>  'Pas encore rendu',
'LOAN_DATEIN' 			 =>  'Date retour',
'LOAN_DATEOUT' 			 =>  'Date sortie',
'LOAN_PERIOD' 			 =>  'P�riode de pr�t',
'LOAN_BACK' 			 =>  'Retour � l\'index des pr�ts',
'LOAN_DAY' 				 =>  'Jour',
'LOAN_DAYS' 			 =>  'Jours',
'LOAN_TODAY' 			 =>  'depuis maintenant',


/* RSS */
'RSS' 					 =>  'Flux RSS',
'RSS_TITLE' 			 =>  'Flux RSS d\'autres bases VCD-db.',
'RSS_SITE' 				 =>  'Adresse flux RSS',
'RSS_USER' 				 =>  'Utilisateur flux RSS',
'RSS_VIEW' 				 =>  'Voir le flux RSS',
'RSS_ADD' 				 =>  'Ajouter un nouveau flux',
'RSS_NOTE' 				 =>  'Entrer l\'adresse exacte du flux des autre bases VCD-db.
							  Si les flux RSS sont autoris�s dans les autre bases, choisissez le flux souhait�, et affichez le sur votre page d\'accueil.',
'RSS_FETCH' 			 =>  'R�cup�rer la liste des RSS',
'RSS_NONE' 				 =>  'Aucun flux RSS ajout�.',
'RSS_FOUND' 			 =>  'Le(s) flux RSS suivant �(ont) �t� trouv�(s), s�lectionnez selui souhait� :',
'RSS_NOTFOUND' 			 =>  'Aucun flux trouv� � l\'adresse',



/* Wishlist */
'W_ADD' 				 =>  'Ajouter � mes d�sid�ratas',
'W_ONLIST' 				 =>  'Dans vos d�sid�ratas',
'W_EMPTY' 				 =>  'Votre liste de d�sid�ratas est vide',
'W_OWN' 				 =>  'Je poss�de un exemplaire de ce film',
'W_NOTOWN' 				 =>  'Je ne poss�de pas ce film',


/* Comments */
'C_COMMENTS' 			 =>  'Commentaires',
'C_ADD' 				 =>  'Ajouter un nouveau commentaire',
'C_NONE' 				 =>  'Aucun commentaire.',
'C_TYPE' 				 =>  'Entrer ici votre commentaire',
'C_YOUR' 				 =>  'Votre commentaire',
'C_POST' 				 =>  'Valider commentaire',
'C_ERROR' 				 =>  'Vous devez �tre enregistr� pour ajouter un commentaire',


/* Pornstars */
'P_NAME' 				 =>  'Nom',
'P_WEB' 				 =>  'Site Web',
'P_MOVIECOUNT' 			 =>  'Nb films',


'S_SEENIT' 				 =>  'Je l\'ai vu',
'S_NOTSEENIT' 			 =>  'Je ne l\'ai pas vu',
'S_SEENITCLICK' 		 =>  'Cliquer pour marquer comme vu',
'S_NOTSEENITCLICK' 		 =>  'Cliquer pour marquer comme pas vu',


/* Statistics */
'STAT_TOP_MOVIES'		=> 'Movies in database',
'STAT_TOP_CATS'			=> 'Top categories',
'STAT_TOP_ACT'			=> 'Most active categories',
'STAT_TOP_COVERS'		=> 'Covers in database',
'STAT_TOTAL'			=> 'Total',
'STAT_TODAY'			=> 'Added today',
'STAT_WEEK'				=> 'Added in last 7 days',
'STAT_MONTH'			=> 'Added in last 30 days',

'MAIL_RETURNTOPIC'	     => 'Loan reminder',
'MAIL_RETURNMOVIES1' 	 =>  '%s, je souhaite simplement te rappeler de me rendre un jour mes films.\n Tu a encore en ta possession les films suivants :\n \n',
'MAIL_RETURNMOVIES2' 	 =>  '%s\nEst-il possible de me rendre au plus vite mes DVDs\n MERCI\n\n nb. Ceci est un courriel automatique envoy� par le syst�me de base de donn�es (http://childweb.free.fr/videos/index.php5)',
'MAIL_NOTIFY' 			 =>  'De nouveau films on �t� ajout�sici pour en savoir plus ..
							  nb. Ceci est un courriel automatique envoy� par le syst�me de base de donn�es (http://childweb.free.fr/videos/index.php5)',

'MAIL_REGISTER' 		 =>  '%s, votre enregistrement � la base est termin� avec succ�s.\n\nVotre nom d\'utilisateur est %s et votre mot de passe est %s.\n\nVous avez la possibilit� de changer de mot de passe apr�s vous �tre connect� � la base.\nCliquez ici pour atteindre le site',







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


'X_CONTAINS' 			 =>  'Contenu',
'X_GRADE' 		 		 =>  'Evaluation IMDB sup�rieure �',
'X_ANY' 				 =>  'n\'importe',
'X_TRYAGAIN' 			 =>  'R�-essayez',
'X_PROCEED' 			 =>  'Valider',
'X_SELECT' 				 =>  'S�l�ctionner',
'X_CONFIRM' 			 =>  'Confirmer',
'X_CANCEL' 				 =>  'Annuler',
'X_ATTENTION' 			 =>  'Attention !',
'X_STATUS' 				 =>  'Status',
'X_SUCCESS' 			 =>  'Succ�s',
'X_FAILURE' 			 =>  'Probl�me',
'X_YES' 				 =>  'Oui',
'X_NO' 					 =>  'Non',
'X_SHOWMORE' 			 =>  'Voir plus',
'X_SHOWLESS' 			 =>  'Voir moins',
'X_NEW' 				 =>  'Nouveau',
'X_CHANGE' 				 =>  'change',
'X_DELETE' 				 =>  'efface',
'X_UPDATE' 				 =>  'Modifier',
'X_SAVEANDCLOSE' 		 =>  'Enregistrer et quitter',
'X_CLOSE' 				 =>  'Quitter',
'X_EDIT' 				 =>  'Editer',
'X_RESULTS' 			 =>  'R�sultats',
'X_LATESTMOVIES' 		 =>  'derniers films',
'X_LATESTTV' 			 =>  'derni�res �missions TV',
'X_LATESTBLUE' 			 =>  'derniers films X',
'X_MOVIES' 				 =>  'films',
'X_NOCATS' 				 =>  'Pas de films ajout�s.',
'X_NOUSERS' 			 =>  'Pas d\'utilisateurs actuellement',
'X_KEY' 				 =>  'Cl�',
'X_SAVENEXT' 			 =>  'Enregistrer et �diter suivant',
'X_SAVE' 		 		 =>  'Enregistrer',
'X_SEEN' 			 	 =>  'Vu',
'X_FOOTER'				=> 'Page Loaded in %s sec. (<i>%d Queries</i>) &nbsp; Copyright (c)',
'X_FOOTER_LINK'			=> 'Check out the official VCD-db website'



);
?>