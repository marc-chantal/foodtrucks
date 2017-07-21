<!-- 
Pour que notre navigateur sache que foodtruck.local est à chercher sur notre ordi, 
on ajoute dans C:\Windows\System32\drivers\etc\hosts :
127.0.0.1       localhost
127.0.0.1       foodtruck.local

(/!\ Windows ne voudra pas qu'on modifie ce fichier...  mais on peut le copier 
ailleurs, le modifier et le recopier dans le répertoire d'origine...) 

On crée aussi un hôte virtuel dans C:\xampp\apache\conf\extra\httpd-vhosts.conf :
<VirtualHost *:80>
    ServerName foodtruck.local
    DocumentRoot "C:/xampp/htdocs/foodtrucks"
</VirtualHost>

-->


<!--
Architecture du site
	public
		assets (ressources)
			css
				nos feuilles de style
			js
				notre javascript
			img
				nos images
		vendor (dépendances)
			les trucs faits par des autres gens (bootstrap, composer...)
	private
		functions
			un fichier par fonction
		models
			des fonctions aussi, mais uniquement des requêtes
		views
			toutes les pages html (format php) qui seront envoyées au navigateur
		translations
			les fichiers de traduction
		app
			tous les fichiers qui seront utilisés pour démarrer l'application/le site
			config.php : 
-->
<?php
	// constantes, connexion à la bdd
	include_once '../private/app/init.php';

	// le haut d'la page html
	include_once VIEWS_DIRECTORY.'header.php';

	// le milieu d'la page html
	include_once VIEWS_DIRECTORY.$router[$page][0];

	// le bas d'la page html
	include_once VIEWS_DIRECTORY.'footer.php';
?>
