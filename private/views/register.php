<?php

// Définition des variables par défaut
$login       = null;
$password    = null;
$firstname   = null;
$lastname    = null;
$gender      = null;
$birth_day   = null;
$birth_month = null;
$birth_year  = null;

// Cas où l'utilisateur envoie le formulaire (méthode POST)
// Contrôle du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $save = true;

    // Recupérer les données de $_POST
    $token = isset($_POST['token']) ? $_POST['token'] : null;
    $login = isset($_POST['login']) ? $_POST['login'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;
    $firstname = isset($_POST['firstname']) ? $_POST['firstname'] : null;
    $lastname = isset($_POST['lastname']) ? $_POST['lastname'] : null;
    $gender = isset($_POST['gender']) ? $_POST['gender'] : null;
    $birth_day = isset($_POST['birth']['day']) ? $_POST['birth']['day'] : null;
    $birth_month = isset($_POST['birth']['month']) ? $_POST['birth']['month'] : null;
    $birth_year = isset($_POST['birth']['year']) ? $_POST['birth']['year'] : null;
    $acceptTerms = isset($_POST['acceptTerms']) ? $_POST['acceptTerms'] : null;

    // Contrôler l'intégrité du token : le serveur qui contrôle le formulaire doit être celui qui a créé le formulaire
    if(!isset($_SESSION['token']) || empty($_SESSION['token']) || $_SESSION['token'] != $token) {
        $save = false;
        setFlashbag("danger", "Le token est invalide.");
    }

    // - Contrôle de l'adresse email
    // --
    // -> ne doit pas etre vide
    // -> doit avoir la syntaxe d'une adresse email valide
    if(empty($login)) {
        $save = false;
        setflashbag("danger", "Veuillez saisir un identifiant.");
    }
    elseif(!filter_var($login, FILTER_VALIDATE_EMAIL)) {
        $save = false;
        setFlashbag("danger", "Veuillez saisir une adresse email valide.");
    }


    // - Contrôle du mot de passe
    // --
    // -> doit contenir au moins 8 caractères
    // -> doit contenir au plus 16 caractères
    // -> doit avoir au moins un caractère de type numérique
    // -> doit avoir au moins un caractère en majuscule
    // -> doit avoir au moins un caractère spécial (#@!=+-_)
    if(empty($password)) {
        $save = false;
        setFlashbag("danger", "Veuillez entrer un mot de passe.");
    }
    elseif(strlen($password)<8 || strlen($password)>16) {
        $save = false;
        setFlashbag("danger", "Le mot de passe doit avoir 8 caractères au minimum et 16 au maximum.");
    }
    elseif( !( preg_match('/[0-9]/', $password) && preg_match('/[A-Z]/', $password) && preg_match('/(#|@|!|=|\+|-|_)/', $password) ) ) {
        $save = false;
        setFlashbag("danger", "Le mot de passe doit avoir au moins un caractère de type numérique, au moins un caractère en majuscule et comporter au moins un caractère spécial (#@!=+-_).");
    }
    // On Crypte le Mot de passe
    else $password = password_hash($password, PASSWORD_DEFAULT);

    // - Contrôle du prénom
    // --
    // -> doit être une chaine alphabétique
    // -> peut contenir un tiret
    // -> ne doit pas posséder de caractère numérique
    if(empty($firstname)) {
        $save = false;
        setFlashbag("danger", "Vous devez entrer votre prénom.");
    }
    elseif(!preg_match('/^[a-z]+-?[a-z]+$/i', $firstname)) {
        $save = false;
        setFlashbag("danger", "Le prénom ne doit comporter que des lettres ou des « tirets du 6 ».");
    }

    // - Contrôle du Nom de famille
    // --
    // -> doit être une chaîne alphabétique
    // -> peut contenir un tiret
    // -> ne doit pas posséder de caractère numérique
    if(empty($firstname)) {
        $save = false;
        setFlashbag("danger", "Vous devez entrer votre nom.");
    }
    elseif(!preg_match('/^[a-z]+-?[a-z]+$/i', $lastname)) {
        $save = false;
        setFlashbag("danger", "Le prénom ne doit comporter que des lettres ou des « tirets du 6 ».");
    }

    // - Contrôle de la date de naissance
    // --
    // -> doit être une date valide
    // -> doit être supérieur à 13ans au moment de l'inscription
    var_dump($birth_year);
    var_dump($birth_month);
    var_dump($birth_day);
    if( !( is_numeric($birth_year) && is_numeric($birth_month) && is_numeric($birth_day) ) ) {
        $save = false;
        setFlashbag("danger", "Veuillez entrer une date valide.");
    }
    elseif( !checkdate($birth_month, $birth_day, $birth_year) ) {
        $save = false;
        setFlashbag("danger", "Veuillez entrer une date valide.");
    }
    else {
        $birthday= $birth_year."-".$birth_month."-".$birth_day;
    }

    if (isset($birthday)) {
        $tz  = new DateTimeZone('Europe/Brussels');
        $age = DateTime::createFromFormat('Y-m-d', $birthday, $tz)
             ->diff(new DateTime('now', $tz))
             ->y;
        $minAge = 13;
        if ($age < $minAge) {
            $save = false;
            setFlashbag("danger", "Vous devez avoir au moins $minAge ans pou vous inscrire.");
        }
    }

    // - Contrôle le genre
    // --
    // -> Le champ doit possèder une valeur (M ou F)
    if( !preg_match('/^(M|F|T|A){1}$/', $gender) ) {
        $save = false;
        setFlashbag("danger", "Veuillez cocher une case de genre.");
    }

    // - Contrôle des condition d'utilisation du service
    // --
    // -> La checkbox doit être cochée.
    if( !$acceptTerms ) {
        $save = false;
        setFlashbag("danger", "Vous devez accepter les conditions d'utilisation pour vous enregistrer sur le site.");
    }

    // - Contrôle l'existance de l'utilisateur dans la BDD
    // -> L'adresse email ne doit pas etre présente dans la BDD (table users)
    if(userExists($login)) {
        $save = false;
        setFlashbag("danger", "Cet identifiant est déjà pris.");
    }

    // On enregistre l'utilisateur dans la BDD
    if($save) {
        echo '<div class="alert alert-success">Le formulaire est valide !</div>';
        // Enregistre l'utilisateur
        $idUser = addUser(array(
            'login' => $login,
            'password' => $password,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'gender' => $gender,
            'birthday' => $birthday
        ));

        // Identification de l'utilisateur
        $_SESSION['user'] = [
            "id" => $idUser,
            "firstname" => $firstname,
            "lastname" => $lastname,
            "email" => $login,
            "roles" => $default_users_roles
        ];

        // Message de succès
        setFlashbag("success", "Bienvenue $firstname !");

        // Destruction du token
        unset($_SESSION['token']);

        // Redirection de l'utilisateur
        header("location: index.php?page=profile");
        exit;
    }

}

// Cas où l'utilisateur arrive sur la page sans envoyer le formulaire (méthode GET)
else {
    // Definition du token
    $_SESSION['token'] = getToken();
}
?>

<div class="page-header">
    <h2>Inscription</h2>
</div>

<div class="row">
    <div class="col-md-4 col-md-offset-4">

        <?php getFlashbag(); ?>

        <form method="post">

            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">

            <div class="form-group">
                <label for="login">Identifiant (adresse email)</label>
                <input  class="form-control" type="text" id="login" name="login" value="<?php echo $login; ?>">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input  class="form-control" type="password" id="password" name="password">
            </div>

            <div class="form-group">
                <label for="firstname">Prénom</label>
                <input  class="form-control" type="text" id="firstname" name="firstname" value="<?php echo $firstname; ?>">
            </div>

            <div class="form-group">
                <label for="lastname">Nom de famille</label>
                <input  class="form-control" type="text" id="lastname" name="lastname" value="<?php echo $lastname; ?>">
            </div>

            <div class="form-group">
                <label>Genre</label>
                <label><input type="radio" name="gender" value="F" <?php echo $gender == "F" ? "checked" : null; ?>> Féminin </label>
                <label><input type="radio" name="gender" value="M" <?php echo $gender == "M" ? "checked" : null; ?>> Masculin </label>
                <label><input type="radio" name="gender" value="T" <?php echo $gender == "T" ? "checked" : null; ?>> Trans </label>
                <label><input type="radio" name="gender" value="A" <?php echo $gender == "A" ? "checked" : null; ?>> Alien </label>
            </div>

            <div class="form-group">
                <label for="birthday">Date de naissance</label>
                <div class="row">
                    <div class="col-md-4">
                        <select  class="form-control" id="birthday" name="birth[day]">
                            <option value="">Jour</option>
                            <?php for($i=1; $i<=31; $i++): ?>
                                <option value="<?php echo str_pad($i, 2, 0, STR_PAD_LEFT); ?>"><?php
                                    echo str_pad($i, 2, 0, STR_PAD_LEFT);
                                ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <select  class="form-control" name="birth[month]">
                            <option value="">Mois</option>
                            <?php for($i=0; $i<12; $i++): ?>
                                <?php $month = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'semptembre', 'octobre', 'novembre', 'décembre']; ?>
                                <option value="<?php echo str_pad(($i+1), 2, 0, STR_PAD_LEFT); ?>"><?php echo $month[$i]; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <select  class="form-control" name="birth[year]">
                            <option value="">Années</option>
                            <?php for($i=date('Y'); $i>date('Y')-100; $i--): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div>
                <label>
                    <input type="checkbox" name="acceptTerms">
                    J'accepte les conditions d'utilisation du service.
                </label>

            </div>

            <br>
            <button type="submit" class="btn btn-info btn-block">Valider</button>
        </form>

        <p class="text-center">
            <a href="index.php?page=login">J'ai déjà un compte</a>
        </p>


    </div>
</div>
