login.php
<?php

// Définition des variables par défaut
$login       = null;
$password    = null;


// Cas où l'utilisateur envoie le formulaire (méthode POST)
// Contrôle du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $save = true;
    $user = false;

    // Recupérer les données de $_POST
    $token = isset($_POST['token']) ? $_POST['token'] : null;
    $login = isset($_POST['login']) ? $_POST['login'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    // Contrôler l'intégrité du token : le serveur qui contrôle le formulaire doit être celui qui a créé le formulaire
    if(!isset($_SESSION['token']) || empty($_SESSION['token']) || $_SESSION['token'] != $token) {
        $save = false;
        setFlashbag("danger", "Le token est invalide.");
    }

    // Contrôle de l'identifiant
    if(empty($login) || !filter_var($login, FILTER_VALIDATE_EMAIL)) {
        $save = false;
    }

    // Contrôle de l'existence de l'utilisateur dans la bdd
    if($save) {
        if(!($user = getUserByEmail($login))) {
            $save = false;
        }
    }

    // Contrôle du mot de passe
    if($save && $user) {
        $pwd_hash = $user->password; // mdp crypté, récupéré depuis la bdd
        $pwd_text = $_POST['password']; // mdp en clair récupéré depuis le formulaire
        // on contrôle le hash des deux mdp
        if(!password_verify($pwd_text, $pwd_hash)) {
            $save = false;
            setFlashbag("danger", "Erreur d'identification.");
        }
    }
    else setFlashbag("danger", "Erreur d'identification.");

    // Connexion si tout va bien
    if($save) {
        
        $_SESSION['user'] = [
            "id" => $user->id,
            "firstname" => $user->firstname,
            "lastname" => $user->lastname,
            "email" => $user->email,
            "roles" => explode(ROLES_GLUE, $user->roles)
        ];

        // Message de succès
        setFlashbag("info", "Bienvenue $user->firstname !");

        // Destruction du token
        unset($_SESSION['token']);

        // Redirection de l'utilisateur
        header("location: index.php?page=profile");
        exit;
    }
    else setFlashbag("danger", "Erreur d'identification.");
}

// Cas où l'utilisateur arrive sur la page sans envoyer le formulaire (méthode GET)
else {
    // Definition du token
    $_SESSION['token'] = getToken();
}
?>


<div class="page-header">
    <h2>Connexion</h2>
</div>

<div class="row">
    <div class="col-md-4 col-md-offset-4">

        <?php getFlashbag(); ?>

        <form method="post">

            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">

            <div class="form-group">
                <label for="login">Identifiant (adresse email)</label>
                <input  class="form-control" type="text" id="login" name="login">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input  class="form-control" type="password" id="password" name="password">
            </div>

            <br>
            <button type="submit" class="btn btn-info btn-block">Se connecter</button>
        </form>

        <p class="text-center">
            <a href="index.php?page=register">Je n'ai pas encore de compte</a>
        </p>

    </div>
</div>
