<?php

// on écrit le chemin complet, car il sera appelé depuis index.php
include_once '../private/app/config.php'; // constantes, variables et cie
include_once '../private/app/routes.php'; // liste des pages du site
include_once '../private/app/autoload.php'; // pour pouvoir charger fonctions et modèles, ci-dessous


// ----------
// AUTOLOADER
// ----------

// Autoload des fonctions / contrôleurs
autoload(FUNCTIONS_DIRECTORY, FUNCTIONS_FILES);

// Autoload des modèles
autoload(MODELS_DIRECTORY, MODELS_FILES);


// -------------------------
// INITIALISATION DE SESSION
// -------------------------

session_start();


// -------------
// CONNEXION BDD
// -------------

// On teste la connexion à la BDD
try {
    // Création de la connexion à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $user, $pass, [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    if (MODE === "dev") {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}
// Si la connexion échoue, on attrape l'exception (message d'erreur)
// et on arrête l'exécution du programme
catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}


// -------
// ROUTING
// -------

// Recupération de la page que l'utilisateur souhaite afficher
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}
// Si l'utilisateur arrive sur le site SANS DEFINIR le parametre
// page dans l'url, la variable $page prendra la valeur de $default_page
else {
    $page = $default_page;
}

// on teste l'existence de la page demandée dans le $router
if(!array_key_exists($page, $router)) {
	$page = "404";
}


// --------
// SÉCURITÉ
// --------

if (
    (isset($router[$page][1]) && $router[$page][1] === true)
    &&
    !(isset($_SESSION['user']['id']) && !empty($_SESSION['user']['id']) && is_numeric($_SESSION['user']['id']))
) {
    setFlashbag("warning","Vous n'êtes pas autorisé à afficher la page ".$page);
    header("location: index.php?page=login");
    exit; // <= /!\ NE PAS OUBLIER de mettre exit; après un header()
}


// --------------------------
// PSEUDO-PAGE DE DÉCONNEXION
// --------------------------

if($page == "logout") {
    session_destroy();
    header("location: index.php");
    exit; // <= /!\ NE PAS OUBLIER de mettre exit; après un header()
}
