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
		French language file
		Thanks to Cédric FOURNIER for the translation

	*/


$_ = array(

/* Language Identifier */
'LANG_TYPE' 		 	 =>  'FRA',
'LANG_NAME' 		 	 =>  'Français',
'LANG_CHARSET'			 =>  'iso-8859-1',

/* Menu system */
'MENU_MINE' 		 	 =>  'Menu',
'MENU_SETTINGS' 		 =>  'Mes paramètres',
'MENU_MOVIES' 		 	 =>  'Mes films',
'MENU_ADDMOVIE' 		 =>  'Ajouter film',
'MENU_LOANSYSTEM' 		 =>  'Gestions des prêts',
'MENU_WISHLIST' 		 =>  'Demandes',
'MENU_CATEGORIES' 		 =>  'Catégories de films',
'MENU_RSS' 		 		 =>  'Mes flux RSS',
'MENU_CONTROLPANEL' 	 =>  'Panneau de contrôle',
'MENU_REGISTER' 		 =>  'Créer un compte',
'MENU_LOGOUT' 		 	 =>  'Quitter',
'MENU_SUBMIT' 			 =>  'Accepter',
'MENU_TOPUSERS' 		 =>  'Utilisateurs',
'MENU_WISHLISTPUBLIC' 	 =>  'Demandes des autres',
'MENU_STATISTICS'		 =>  'Statistiques',

/* Login */
'LOGIN' 				 =>  'Login',
'LOGIN_USERNAME' 		 =>  'Nom d\'utilisateur',
'LOGIN_PASSWORD' 		 =>  'mot de passe',
'LOGIN_REMEMBER' 		 =>  'Mémoriser login',
'LOGIN_INFO' 		 	 =>  'Laisser cette case vide si vous ne voulez pas changer votre mot de passe',

/* Register */
'REGISTER_TITLE' 		 =>  'Enregistrement',
'REGISTER_FULLNAME' 	 =>  'Nom complet',
'REGISTER_EMAIL' 		 =>  'Email',
'REGISTER_AGAIN' 		 =>  'Re-taper le mot de passe',
'REGISTER_DISABLED' 	 =>  'Désolé, l\'administrateur de ce site a désactivé l\'enregistrement',
'REGISTER_OK' 		 	 =>  'Enregistrement terminé avec succès, vous pouvez maintenant vous connecter',

/* User Properties */
'PRO_NOTIFY' 			=> 'Envoyer un email lors de l\'ajout d\'un nouveau film ?',
'PRO_SHOW_ADULT' 		=> 'Voir le contenu réservé aux adultes sur le site ?',
'PRO_RSS' 				=> 'Autoriser les flux RSS depuis ma liste de films ?',
'PRO_WISHLIST'    		=> 'Autoriser les autres à voir ma liste de désidératas ?',
'PRO_USE_INDEX'   		=> 'Utiliser le numéro d\'indice comme identifiant des média personnels',
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
'SEARCH_DIRECTOR' 		 =>  'Par réalisateur',
'SEARCH_RESULTS' 		 =>  'Résultats',
'SEARCH_EXTENDED' 		 =>  'Recherche détaillée',
'SEARCH_NORESULT' 		 =>  'Aucun film trouvé',

/* Movie categories*/
'CAT_ACTION' 		 	 =>  'Action',
'CAT_ADULT' 		 	 =>  'Adulte',
'CAT_ADVENTURE' 		 =>  'Aventure',
'CAT_ANIMATION' 		 =>  'Animation',
'CAT_ANIME' 			 =>  'Anime / Manga',
'CAT_COMEDY' 			 =>  'Comédie',
'CAT_CRIME' 		 	 =>  'Meurtres',
'CAT_DOCUMENTARY' 		 =>  'Documentaire',
'CAT_DRAMA' 		 	 =>  'Drame',
'CAT_FAMILY' 		 	 =>  'Famille',
'CAT_FANTASY' 		 	 =>  'Fantastique',
'CAT_FILMNOIR' 		 	 =>  'Film Noir',
'CAT_HORROR' 		 	 =>  'Horreur',
'CAT_JAMESBOND' 		 =>  'James Bond',
'CAT_MUSICVIDEO' 		 =>  'Vidéo musicale',
'CAT_MUSICAL' 			 =>  'Musical',
'CAT_MYSTERY' 		 	 =>  'Mystère',
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
'M_CATEGORY' 			 =>  'Catégorie',
'M_YEAR' 				 =>  'Année de production',
'M_COPIES' 				 =>  'Nombre de disques',
'M_FROM' 				 =>  'Données de ',
'M_TITLE' 				 =>  'Titre',
'M_ALTTITLE' 			 =>  'Autre titre',
'M_GRADE' 				 =>  'Evaluation',
'M_DIRECTOR' 			 =>  'Réalisateur',
'M_COUNTRY' 			 =>  'Pays de production',
'M_RUNTIME' 			 =>  'Durée',
'M_MINUTES' 			 =>  'minutes',
'M_PLOT' 				 =>  'Description',
'M_NOPLOT' 				 =>  'Pas de description disponible',
'M_COVERS' 				 =>  'Jaquette',
'M_AVAILABLE' 			 =>  'Nombre dispo',
'M_MEDIA' 				 =>  'Média',
'M_NUM' 		 		 =>  'Nb. disques',
'M_DATE' 		 		 =>  'Date d\'ajout',
'M_OWNER' 		 		 =>  'Propriétaire',
'M_NOACTORS' 			 =>  'Aucun acteur n\'est enregistré pour ce film',
'M_INFO' 		 		 =>  'Informations sur le film',
'M_DETAILS' 		 	 =>  'Details de ma version',
'M_MEDIATYPE' 		 	 =>  'Média',
'M_COMMENT' 		 	 =>  'Commentaire',
'M_PRIVATE' 		 	 =>  'Marquer comme privée ?',
'M_SCREENSHOTS' 		 =>  'Copies d\'écrans',
'M_NOSCREENS' 	 		 =>  'Pas de copie d\'écran disponible',
'M_SHOW' 		 	 	 =>  'Voir',
'M_HIDE' 		 		 =>  'Cacher',
'M_CHANGE' 				 =>  'Changer les informations',
'M_NOCOVERS' 			 =>  'Pas de jaquette disponible',
'M_BYCAT' 				 =>  'Titres par catégories',
'M_CURRCAT' 			 =>  'Catégorie',
'M_TEXTVIEW' 		 	 =>  'Format texte',
'M_IMAGEVIEW' 			 =>  'Format images',
'M_MINEONLY'		     =>  'Show only my movies',
'M_SIMILAR'				 =>  'Similar movies',
'M_MEDIAINDEX'			 =>  'Numéro d\'indice',

/* IMDB */
'I_DETAILS' 		 	 =>  'Détails IMDB',
'I_PLOT' 		 		 =>  'Afficher le résumé',
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
'MY_EXPORT' 		 	 =>  'Exporter Données',
'MY_EXCEL' 		 		 =>  'Exporter au format Excel',
'MY_XML' 		 		 =>  'Exporter au format XML',
'MY_XMLTHUMBS' 		 	 =>  'Exporter les vignettes au format XML',
'MY_ACTIONS' 		 	 =>  'Mes actions',
'MY_JOIN' 		 		 =>  'Fusion de données',
'MY_JOINMOVIES' 		 =>  'Fusion de données des films',
'MY_JOINSUSER' 			 =>  'Sélectionner l\'utilisateur',
'MY_JOINSMEDIA' 		 =>  'Sélectionner le type de media',
'MY_JOINSCAT' 		 	 =>  'Sélectionner la catégorie',
'MY_JOINSTYPE' 		 	 =>  'Sélectionner une action',
'MY_JOINSHOW' 		 	 =>  'Voir résultats',
'MY_NORESULTS' 			 =>  'Pas de films trouvés',
'MY_TEXTALL'			 =>  'Aperçu (Text)',
'MY_PWALL' 		 		 =>  'Aperçu (Tous)',
'MY_PWMOVIES' 			 =>  'Aperçu (Films)',
'MY_PWTV' 		 		 =>  'Aperçu (Emissions TV)',
'MY_PWBLUE' 		 	 =>  'Aperçu (Films Bleus)',
'MY_J1' 		 		 =>  'Films que je possède, mais pas l\'utilisateur',
'MY_J2' 		 		 =>  'Films que possède l\'utilisateur, mais pas moi',
'MY_J3' 		 		 =>  'Films que nous possédons tous les deux',
'MY_OVERVIEW' 			 =>  'Vue d\'ensemble collection',
'MY_INFO' 		 		 =>  'Sur cette page, vous trouverez tous mes films DVD. Sur la droite, se trouvent les actions que vous pouvez faire sur votre collection. Vous pouvez aussi exporter votre liste au format Excel(r) pour l\'imprimer, ou utiliser l\'export XML pour faire une sauvegarde ou pour échanger vos films avec d\'autres base de données VCD-db.',
'MY_KEYS' 		 		 =>  'Editer les ID personnalisés',
'MY_SEENLIST' 		 	 =>  'Editer la liste des films vus',
'MY_HELPPICKER' 		 =>  'Sélectionner un film',
'MY_HELPPICKERINFO' 	 =>  'Vous ne savez pas quoi voir aujourd\'hui ?
							  Laisser cette base de donnée choisir pour vous
							  Vous pouvez aussi créer des filtres et les utiliser pour faciliter le choix de VCD-db.',
'MY_FIND' 				 =>  'Chercher un film',
'MY_NOTSEEN' 			 =>  'Suggérer uniquement les films que je n\'ai pas vus',
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
'MAN_ADDTODB' 			 =>  'Ajouter de nouveau acteurs à la base',
'MAN_SAVETODB' 			 =>  'Enregistrer dans la base',
'MAN_SAVETODBNCD' 		 =>  'Enregistrer dans la base et dans le film',
'MAN_INDB' 				 =>  'Acteurs dans la base',
'MAN_SEL' 				 =>  'Sélectionner un acteur',
'MAN_STARS' 			 =>  'Stars',
'MAN_BROWSE'			 => 'Browse for file location',
'MAN_ADDMEDIA'			 => 'Add...',

/* Add movies */
'ADD_INFO' 		 		 =>  'Sélectionner la méthode d\'ajout d\'un nouveau film',
'ADD_IMDB' 		 		 =>  'Récupérer depuis internet',
'ADD_IMDBTITLE' 		 =>  'Entrer le(s) mot(s) clé',
'ADD_MANUAL' 			 =>  'Rentrer les données manuellement',
'ADD_LISTED' 		 	 =>  'Ajouter des films déjà listés',
'ADD_XML' 		 		 =>  'Ajouter des films depuis un fichier XML',
'ADD_XMLFILE' 		 	 =>  'Sélectionner fichier XML ',
'ADD_XMLNOTE' 		 	 =>  '(Veuillez noter que seuls des fichier XML venant d\'une autre application VCD-db peuvent servir à importer vous films. Vous pouvez exporter vos films depuis la section "Mes films". Il est fortement déconseillé de modifier le fichier XML exporté).',
'ADD_MAXFILESIZE' 		 =>  'Taille max.',
'ADD_DVDEMPIRE' 		 =>  'Récupérer depuis DVD Empire (films X)',
'ADD_LISTEDSTEP1' 		 =>  'Etape 1 Sélectionner les titres à ajouter à la liste.
							  Vous pourrez choisir le média à l\'étape suivante.',
'ADD_LISTEDSTEP2' 		 =>  'Etape 2 Sélectionner le média.',
'ADD_INDB' 				 =>  'Films listés dans VCD-DB',
'ADD_SELECTED' 		 	 =>  'Sélectionner les titres',
'ADD_INFOLIST' 		 	 =>  'Double cliquez sur le titre pour le sélectionner ou utilisez les flèches.
							  Vous pouvez utiliser le clavier pour une recherche rapide',
'ADD_NOTITLES' 		 	 =>  'Aucun autre utilisateur n\'a ajouter de films',




/* Add from XML */
'XML_CONFIRM' 		 	 =>  'Confirmer le chargement XML',
'XML_CONTAINS' 		 	 =>  'Le fichier XML continent %d films.',
'XML_INFO1' 			 =>  'Appuyez sur confirmer pour ajouter les informations du film et enregistrer la base.
							  Ou sur Cancel pour annuler.',
'XML_INFO2' 		 	 =>  'Si vous voulez inclure une vignette (poster) au film que vous importer via un fichier XML, vous devez avoir dès maintenant accès au fichier XML contenant les vignettes!
							  Les posters ne peuvent être importés après la fin de l\'import depuis le fichier XML actuel. Si vous avez déjà le fichier XML des vignettes, cochez le champ ci-dessous, ainsi, après l\'import de vos films dans la liste ci-dessous, il vous sera demandé de choisir le fichier XML des vignettes.',
'XML_THUMBNAILS' 		 =>  'Insérer des vignettes depuis un fichier XML ',
'XML_LIST' 			 	 =>  'Liste complète des films présents dans le fichier XML.',
'XML_ERROR' 		 	 =>  'Aucun titres trouvés dans le fichier XML.
							  Le fichier est peut-être endommagéou simplement vide.
							  Assurez vous d\'avoir choisi le bon fichier XML pour l\'import des données.',
'XML_RESULTS' 		 	 =>  'Résultat d\'import XML.',
'XML_RESULTS2' 		 	 =>  'Voici le résultat de votre import XML.
							  Au total, %d films ont été récupérés.',




/* Add from DVD Empire */
'EM_INFO' 		 		 =>  'Informations de AdultDVDEmpire.com ....',
'EM_DESC' 		 		 =>  'Déscription DVDEmpire',
'EM_SUBCAT' 		 	 =>  'Catégories Adultes',
'EM_DETAILS' 		 	 =>  'Détails Adultdvdempire.com',
'EM_STARS' 				 =>  'Pornstars',
'EM_NOTICE' 			 =>  'Les acteurs marqués en rouge ne sont pas dans la base. Vous pouvez néanmoins choisir leur nom, et ils seront automatiquement ajoutés, et associés à ce film.',
'EM_FETCH' 				 =>  'Récupérer aussi ',



/* Loan System */
'LOAN_MOVIES' 			 =>  'Films à préter',
'LOAN_TO' 		 		 =>  'Prêter le(s) film(s) à ',
'LOAN_ADDUSERS' 		 =>  'Ajouter des emprunteurs pour poursuivre',
'LOAN_NEWUSER' 		 	 =>  'Nouvel emprunteur',
'LOAN_REGISTERUSER' 	 =>  'Ajouter un nouvel emprunteur',
'LOAN_NAME' 			 =>  'Nom',
'LOAN_SELECT' 		 	 =>  'Sélectionner l\'emprunteur',
'LOAN_MOVIELOANS' 		 =>  'Films prêtés ...',
'LOAN_REMINDER' 		 =>  'Envoyer un rappel',
'LOAN_HISTORY' 		 	 =>  'Historique des prêts',
'LOAN_HISTORY2' 		 =>  'Voir l\'historique des prêts',
'LOAN_SINCE' 			 =>  'Depuis',
'LOAN_TIME' 			 =>  'Temps écoulé',
'LOAN_RETURN' 			 =>  'Retour du film',
'LOAN_SUCCESS' 			 =>  'Fichier prêté',
'LOAN_OUT' 				 =>  'Pas encore rendu',
'LOAN_DATEIN' 			 =>  'Date retour',
'LOAN_DATEOUT' 			 =>  'Date sortie',
'LOAN_PERIOD' 			 =>  'Période de prêt',
'LOAN_BACK' 			 =>  'Retour à l\'index des prêts',
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
							  Si les flux RSS sont autorisés dans les autre bases, choisissez le flux souhaité, et affichez le sur votre page d\'accueil.',
'RSS_FETCH' 			 =>  'Récupérer la liste des RSS',
'RSS_NONE' 				 =>  'Aucun flux RSS ajouté.',
'RSS_FOUND' 			 =>  'Le(s) flux RSS suivant à(ont) été trouvé(s), sélectionnez selui souhaité :',
'RSS_NOTFOUND' 			 =>  'Aucun flux trouvé à l\'adresse',



/* Wishlist */
'W_ADD' 				 =>  'Ajouter à mes désidératas',
'W_ONLIST' 				 =>  'Dans vos désidératas',
'W_EMPTY' 				 =>  'Votre liste de désidératas est vide',
'W_OWN' 				 =>  'Je possède un exemplaire de ce film',
'W_NOTOWN' 				 =>  'Je ne possède pas ce film',


/* Comments */
'C_COMMENTS' 			 =>  'Commentaires',
'C_ADD' 				 =>  'Ajouter un nouveau commentaire',
'C_NONE' 				 =>  'Aucun commentaire.',
'C_TYPE' 				 =>  'Entrer ici votre commentaire',
'C_YOUR' 				 =>  'Votre commentaire',
'C_POST' 				 =>  'Valider commentaire',
'C_ERROR' 				 =>  'Vous devez être enregistré pour ajouter un commentaire',


/* Pornstars */
'P_NAME' 				 =>  'Nom',
'P_WEB' 				 =>  'Site Web',
'P_MOVIECOUNT' 			 =>  'Nb films',


'S_SEENIT' 				 =>  'Je l\'ai vu',
'S_NOTSEENIT' 			 =>  'Je ne l\'ai pas vu',
'S_SEENITCLICK' 		 =>  'Cliquer pour marquer comme vu',
'S_NOTSEENITCLICK' 		 =>  'Cliquer pour marquer comme pas vu',


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

'MAIL_RETURNTOPIC'	     => 'Loan reminder',
'MAIL_RETURNMOVIES1' 	 =>  '%s, je souhaite simplement te rappeler de me rendre un jour mes films.\n Tu a encore en ta possession les films suivants :\n \n',
'MAIL_RETURNMOVIES2' 	 =>  '%s\nEst-il possible de me rendre au plus vite mes DVDs\n MERCI\n\n nb. Ceci est un courriel automatique envoyé par le système de base de données (http://childweb.free.fr/videos/index.php5)',
'MAIL_NOTIFY' 			 =>  'De nouveau films on été ajoutésici pour en savoir plus ..
							  nb. Ceci est un courriel automatique envoyé par le système de base de données (http://childweb.free.fr/videos/index.php5)',

'MAIL_REGISTER' 		 =>  '%s, votre enregistrement à la base est terminé avec succès.\n\nVotre nom d\'utilisateur est %s et votre mot de passe est %s.\n\nVous avez la possibilité de changer de mot de passe après vous être connecté à la base.\nCliquez ici pour atteindre le site',







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
'X_GRADE' 		 		 =>  'Evaluation IMDB supérieure à',
'X_ANY' 				 =>  'n\'importe',
'X_TRYAGAIN' 			 =>  'Ré-essayez',
'X_PROCEED' 			 =>  'Valider',
'X_SELECT' 				 =>  'Séléctionner',
'X_CONFIRM' 			 =>  'Confirmer',
'X_CANCEL' 				 =>  'Annuler',
'X_ATTENTION' 			 =>  'Attention !',
'X_STATUS' 				 =>  'Status',
'X_SUCCESS' 			 =>  'Succès',
'X_FAILURE' 			 =>  'Problème',
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
'X_RESULTS' 			 =>  'Résultats',
'X_LATESTMOVIES' 		 =>  'derniers films',
'X_LATESTTV' 			 =>  'dernières émissions TV',
'X_LATESTBLUE' 			 =>  'derniers films X',
'X_MOVIES' 				 =>  'films',
'X_NOCATS' 				 =>  'Pas de films ajoutés.',
'X_NOUSERS' 			 =>  'Pas d\'utilisateurs actuellement',
'X_KEY' 				 =>  'Clé',
'X_SAVENEXT' 			 =>  'Enregistrer et éditer suivant',
'X_SAVE' 		 		 =>  'Enregistrer',
'X_SEEN' 			 	 =>  'Vu',
'X_TOGGLE'				=> 'Toggle preview',
'X_TOGGLE_ON'			=> 'on',
'X_TOGGLE_OFF'			=> 'off'


);
?>