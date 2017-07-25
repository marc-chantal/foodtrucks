<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>FoOd TrUcKs</title>
		<!-- Bootstrap -->
		<link href="vendor/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
		<link href="assets/css/theme.css" rel="stylesheet">

	</head>
	<body>

		<div class="container">

			<div class="masthead">
				<h3 class="text-muted">Les Foodtrucks du coin <?php for($i=0;$i<rand(1,100);$i++) echo '!';?> </h3>

				<nav>
				<?php if( isset($_SESSION['user']['id']) && !empty($_SESSION['user']['id']) && is_numeric($_SESSION['user']['id']) ) :
				?>
					<a href="index.php?page=profile"><?php echo $_SESSION['user']['firstname']; ?></a>
				<?php else : ?>
					<a href="index.php?page=register">Inscription</a>
					<a href="index.php?page=login">Connexion</a>
				<?php endif; ?>
				</nav>

				<nav>
					<ul class="nav nav-justified">
						<li class="active"><a href="index.php?page=home">Home</a></li>
						<li><a href="index.php?page=foodtrucks">Foodtrucks</a></li>
						<li><a href="index.php?page=contact">Contact</a></li>
					</ul>
				</nav>
			</div>

			<div id="content">
				<?php getFlashbag(); ?>
