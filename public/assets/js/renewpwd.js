"use strict";

$(function() {

	// Afficher / masquer le mot de passe
	$('.input-group-addon').click(function() {
		toogleViewPwd($(this).data('toggleView'));
		$(this).find('i')
			.toggleClass('glyphicon-eye-open')
			.toggleClass('glyphicon-eye-close');
	});
	// envoi du formulaire
	
});

function toogleViewPwd(fieldId) {
	var $field = $('#'+fieldId);
	if($field.attr('type') == "password")
		$field.attr('type', 'text');
	else
		$field.attr('type', 'password');
}