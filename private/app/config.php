<?php
//               _________________
//       .-----.|                 |
//     _//_||  ||  LA CANTINETTE  |
//    [    -|  |'--;--------------'
//    '-()-()----()"()^^^^^^^()"()'
//   

// -------------------
// CONFIG DU PROGRAMME
// -------------------

// Adresse du serveur de base de données
// "localhost" est un alias de 127.0.0.1
$host = "127.0.0.1";

// Nom d'utilisateur de la base de données
$user ="root";

// Mot de passe associé à l'utilisateur
$pass = "";

// Nom de la base de données sur laquelle on va travailler
$database = "foodtrucks";



// ----------
// CONSTANTES
// ----------

// Mode d'exécution, "dev" ou "prod"
define("MODE", "dev");
// Chemins d'accès aux répertoires
define("VIEWS_DIRECTORY", "../private/views/");
define("FUNCTIONS_DIRECTORY", "../private/functions/");
define("MODELS_DIRECTORY", "../private/models/");
// Expressions régulières pour les noms de fichiers
define("FUNCTIONS_FILES", "/^fnc-.*\.php$/i");
define("MODELS_FILES", "/^mdl-.*\.php$/i");
// Utilisateurs
define("ROLES_GLUE", ",");
define("ROLES_USER", "USER");
define("ROLES_ADMIN", "ADMIN");



// --------------------
// VARIABLES PAR DÉFAUT
// --------------------

$default_page = "home";
$default_lang="fr";
$default_users_roles = [ROLES_USER];