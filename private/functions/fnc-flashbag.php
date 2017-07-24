<?php
// gestion des flashbags

function setFlashbag($state, $message) {
	// s'il n'existe pas, créer le flashbag
	if( !isset($_SESSION['flashbag']) ) {
		$_SESSION['flashbag'] = [];
	}
	// ajouter le message au flashbag
	array_push($_SESSION['flashbag'], [
		"state" => $state,
		"message" => $message
	]);	
}

function getFlashbag() {
	if(!empty($_SESSION['flashbag'])) {
		// afficher les messages du flashbag
		foreach($_SESSION['flashbag'] as $value) {
			echo "<div class=\"alert alert-".$value['state']."\">";
			echo $value['message'];
			echo "</div>";
		}
		// enfin, supprimer le flashbag
		unset($_SESSION['flashbag']);
	}
}
