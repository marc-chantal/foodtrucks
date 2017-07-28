<?php

include_once '../private/app/init-ajax.php';

// on accepte les requêtes POST uniquement
if($_SERVER['REQUEST_METHOD'] == 'POST') {

	print_r($_POST);
	$save = true;

	// récupération des données du $_POST
	$pwd_old = $_POST['pwd_old'];
	$pwd_new = $_POST['pwd_new'];
	$pwd_repeat = $_POST['pwd_repeat'];

	// contrôle des données
    if(empty($pwd_new)) {
        $save = false;
        // 1ère solution
        // echo "{\"state\":\"danger\", \"message\":\"Vous devez entrer un nouveau mot de passe.\"}";
        // 2ème solution
        echo json_encode(array(
        	"state" => "danger",
        	"message" => "Vous devez entrer un nouveau mot de passe."
      	));
    }
    elseif(strlen($pwd_new)<8 || strlen($pwd_new)>16) {
        $save = false;
        echo "{\"state\":\"danger\", \"message\":\"Le mot de passe doit avoir 8 caractères minimum et 16 caractères maximum.\"}";
    }
    elseif( !( preg_match('/[0-9]/', $pwd_new) && preg_match('/[A-Z]/', $pwd_new) && preg_match('/(#|@|!|=|\+|-|_)/', $pwd_new) ) ) {
        $save = false;
        echo json_encode(array(
        	"state" => "danger",
        	"message" => "Le mot de passe doit contenir au moins un chiffre, un lettre en majuscule et un caractère spécial (#, @, !, =, +, - ou _)."
      	));
    }
    elseif($pwd_new != $pwd_repeat) {
    	$save = false;
    }

    if($save) {
    	if(password_verify($pwd_old, getPwd($_SESSION['user']['id'])['password'])) {
    		changePwd($_SESSION['user']['id'], $pwd_new);
   			echo "Mot de passe changé !";
    	}
    	else
    		echo "C'est pas le bon mot de passe !";
    }
    else
    	echo "Échec du changement de mot de passe.";

}
else {
	echo "Vous n'avez pas l'autorisation d'afficher ce fichier.";
}

