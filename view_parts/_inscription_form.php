<?php
//var_dump($_POST); // Inspecter les données POST
$in_post = array_key_exists('register', $_POST); // En est en réception
/**
 * Validation du prenom
 */
$firstname_ok = false;
$firstname_msg = ''; // Message de feedback validation, affiché si non vide
if (array_key_exists('firstname', $_POST)) {
    $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
    // Validation du prenom : min 2 caractères
    $firstname_ok = (1 === preg_match('/^[A-Za-z]{2,}$/', $firstname));
    if ( ! $firstname_ok) { // Si le prénom n'est pas valide
        $firstname_msg = 'Le prénom ne doit contenir que des lettres (min 2).';
    }
//    var_dump($firstname);
//    var_dump($firstname_ok);
//    var_dump($firstname_msg);
}
/**
 * Validation du nom
 */
$lastname_ok = false;
if (array_key_exists('nom', $_POST)) {
    $lastname = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
    // Validation du nom: min 2 caracteres
    $lastname_ok = (1 === preg_match('/^[A-Za-z]{2,}$/', $lastname));
//    var_dump($lastname);
//    var_dump($lastname_ok);
}
/**
 * Validation du courriel
 */
$email_ok = false;
if (array_key_exists('email', $_POST)) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    $email_ok = (false !== $email);
//    var_dump($email);
//    var_dump($email_ok);
}

/**
 * Validation du pseudo
 */
$username_ok = false;
if (array_key_exists('username', $_POST)) {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    // Validation du pseudo : min 4 caractères alpha ou chiffres
    $username_ok = (1 === preg_match('/^[a-zA-Z0-9]{2,}$/', $username));
    if ($username_ok) {
        // Est ce que le psudo est libre
        require_once 'db/_user.php';
        if (username_exists($username)) {
            echo "Le username" . $username . " est déjà pris";
        };
    }
//    var_dump($username);
//    var_dump($username_ok);
}

/**
 * Validation du mot de passe
 */
$password_ok = false;
if (array_key_exists('password', $_POST)) {
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    // Validation du mot de passe: alpha, chiffres,caracteres speciaux, min de 4 caracteres
    $password_ok = (1 === preg_match('/^[a-zA-Z0-9%&$!*?]{4,}$/', $password));
//    var_dump($password);
//    var_dump($password_ok);
}

if ($firstname_ok && $lastname_ok && $email_ok && $username_ok && $password_ok) {
    // On enregistre les données et s'en va sur une autre page
    require_once 'db/_user.php';
    $user_info = user_add($username, $password, $firstname, $lastname, $email);
    header("Location:index.php");
    exit;
}
?>
<form id="inscription" name="inscription" xmlns="http://www.w3.org/1999/html" method="post">
    <label for="firstname">Prénom : </label>
    <input type="text" name="firstname" id="firstname"
           class="<?php echo $in_post && ! $firstname_ok ? 'error' : '';?>"
           value="<?php echo array_key_exists('firstname', $_POST) ? $_POST['firstname'] : '' ?>"/>
    <label for="nom">Nom : </label>
    <input type="text" name="nom" id="nom" value="<?php echo array_key_exists('nom', $_POST) ? $_POST['nom'] : '' ?>"/>
    <label for="email">Courriel : </label>
    <input type="email" name="email" id="email" value="<?php echo array_key_exists('email', $_POST) ? $_POST['email'] : '' ?>"/>
    <label for="username">Pseudo : </label>
    <input type="text" name="username" id="username" value="<?php echo array_key_exists('username', $_POST) ? $_POST['username'] : '' ?>"/>
    <label for="password">Password : </label>
    <input type="password" name="password" id="password" value="<?php echo array_key_exists('password', $_POST) ? $_POST['password'] : '' ?>"/>
    <input type="submit" name="register" id="register" value="S'inscrire"/>
</form>
