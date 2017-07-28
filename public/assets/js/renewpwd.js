"use strict";

$(function() {

	// Afficher / masquer le mot de passe
	$('.input-group-addon').click(function() {
		toogleViewPwd($(this));
	});
	// envoi du formulaire
	$('#btn-send').click(function() {
		sendRenewPwdRequest();
	});

});

function toogleViewPwd(element) {
	var $field = $('#'+element.data('toggleView'));
	if($field.attr('type') == "password")
		$field.attr('type', 'text');
	else
		$field.attr('type', 'password');
	element.find('i')
		.toggleClass('glyphicon-eye-open')
		.toggleClass('glyphicon-eye-close');
}

function sendRenewPwdRequest() {

	var send = true;

	// 1ère solution : on cible chaque élément grâce à son id
	// utile quand on veut contrôler chaque champ 
	var pwd_old = $('#pwd_old').val();
	var pwd_new = $('#pwd_new').val();
	var pwd_repeat = $('#pwd_repeat').val();

	// var values = {
	// 	"pwd_old" : pwd_old,
	// 	"pwd_new" : pwd_new,
	// 	"pwd_repeat" : pwd_repeat
	// };

	// console.log(values);

	// 2ème solution : on laisse faire jQuery qui va utiliser les attributs name
	// plus rapide à écrire
	// values = $('form').serializeArray();	// nous donnera un objet de type :
	// 										{ { "name1" : value1 }, { "name2" : value2 }, ... }
	// console.log(values);

// avec jQuery, on peut insérer des trucs dans, avant ou après notre élément
//
// 						<- pour insérer ici, before()
//	-----élément-----
//	|               |	<- pour insérer ici, prepend()
//	|    contenu    |
//	|               |	<- pour insérer ici, append()
//	-----------------
//	 					<- pour insérer ici, after()

	// Contrôle du formulaire
	// l'ancien mot de passe ne doit pas êre vide
	if(pwd_old.length <= 0) {	// xxx.length est l'équivalent javascript du empty(xxx) de php
		send = false;
		$('#pwd_old').before('<div class="text-danger">Veuillez entrer votre ancien mot de passe.</div>');
	}

	// le nouveau mot de passe
	// -> doit contenir au moins 8 caractères
	if(pwd_new.length < 8) {	// xxx.length est l'équivalent javascript du empty(xxx) de php
		send = false;
		$('#pwd_new').before('<div class="text-danger">Le mot de passe doit contenir au moins 8 caractères.</div>');
	}
    // -> doit contenir au plus 16 caractères
    else if(pwd_new.length > 16) {
		send = false;
		$('#pwd_new').before('<div class="text-danger">Le mot de passe doit contenir 16 caractères au plus.</div>');
	}
    // -> doit avoir au moins un caractère de type numérique
    if(!/[0-9]/.test(pwd_new)) {
		send = false;
		$('#pwd_new').before('<div class="text-danger">Le mot de passe doit avoir au moins un caractère de type numérique.</div>');
	}
    // -> doit avoir au moins un caractère en majuscule
    if(!/[A-Z]/.test(pwd_new)) {
		send = false;
		$('#pwd_new').before('<div class="text-danger">Le mot de passe doit avoir au moins un caractère en majuscule.</div>');
	}
    // -> doit avoir au moins un caractère spécial (#@!=+-_)
	// if(!/(#|@|!|=|\+|-|_)/.test(pwd_new)) {
	// 	send = false;
	// 	$('#pwd_new').before('<div class="text-danger">Le mot de passe doit avoir au moins un caractère spécial (#@!=+-_).</div>');
	// }
	if(!(pwd_new.includes('#') || pwd_new.includes('@') || pwd_new.includes('!') || pwd_new.includes('=') || pwd_new.includes('+') || pwd_new.includes('-') || pwd_new.includes('_'))) {
		send = false;
		$('#pwd_new').before('<div class="text-danger">Le mot de passe doit avoir au moins un caractère spécial (#@!=+-_).</div>');
	}

    // la répétition doit être identique au nouveau mot de passe 
    if(pwd_new!=pwd_repeat) {
    	send = false;
    	$('#pwd_repeat').before('<div class="text-danger">Les mots de passe entrés ne sont pas identiques.</div>');
    }

    //
    if(send) {
    	$.post(
    		'ajax.php',
    		$('form').serializeArray(),
    		function(response) { console.log(response); }
    	);
    }


}