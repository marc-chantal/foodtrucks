<?php

// liste des pages du site
$router = [
	// nom de la route => [ nom du fichier, true si accès restreint ]
	// page d'accueil
	"home"				=> ["home.php",				false],
	// gestion des foodtrucks
	"foodtrucks"		=> ["foodtrucks.php",		false], // liste de tous les foodtrucks
	"foodtruck"			=> ["foodtruck.php",		false], // page d'un foodtruck en particulier
	"add-foodtruck"		=> ["add-foodtruck.php",	true],
	"edit-foodtruck"	=> ["edit-foodtruck.php",	true],
	"delete-foodtruck"	=> ["delete-foodtruck.php",	true],
	// page de contact
	"contact"			=> ["contact.php",			false],
	// gestion des utilisateurs
	"profile"			=> ["profile.php",			true],
	"settings"			=> ["settings.php",			true],
	"register"			=> ["register.php",			false],
	"login"				=> ["login.php",			false],
	"lostpwd"			=> ["lostpwd.php",			false],
	"renewpwd"			=> ["renewpwd.php",			false],
	"logout"			=> [null,					true],
	// page d'erreur
	"404"				=> ["404.php",				false]
];