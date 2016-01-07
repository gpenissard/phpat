<?php
//var_dump($_POST); // Inspecter les données POST
$in_post = array_key_exists('register', $_POST); // En est en réception
//$in_post = ('POST' == $_SERVER['REQUEST_METHOD']); // On peut utiliser la méthode HTTP utilisée

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
$lastname_msg = ''; // Message de feedback validation, affiché si non vide
if (array_key_exists('lastname', $_POST)) {
    $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
    // Validation du nom: min 2 caracteres
    $lastname_ok = (1 === preg_match('/^[A-Za-z]{2,}$/', $lastname));
    if ( ! $lastname_ok) { // Si le prénom n'est pas valide
        $lastname_msg = 'Le nom ne doit contenir que des lettres (min 2).';
    }
//    var_dump($lastname);
//    var_dump($lastname_ok);
}
/**
 * Validation du genre
 */
$gender_ok = array_key_exists('gender', $_POST);
$gender_msg = ''; // Message de feedback validation, affiché si non vide
if ( ! $gender_ok) { // Si le prénom n'est pas valide
    $gender_msg = 'Le sexe n\'est pas coché.';
}

/**
 * Validation du courriel
 */
$email_ok = false;
$email_msg = '';
if (array_key_exists('email', $_POST)) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    $email_ok = (false !== $email);
    if ( ! $email_ok) { // Si le email n'est pas valide
        $email_msg = 'Le courriel n\'est pas valide.';
    }
//    var_dump($email);
//    var_dump($email_ok);
}

/**
 * Validation du pseudo
 */
$username_ok = false;
$username_msg = '';
if (array_key_exists('username', $_POST)) {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    // Validation du pseudo : min 4 caractères alpha ou chiffres
    $username_ok = (1 === preg_match('/^[a-zA-Z0-9]{2,}$/', $username));
    if ( ! $username_ok) { // Si le format du username n'est pas valide
        $username_msg = 'Le username ne doit contenir que des lettres (min 2).';
    } else {
        // Est ce que le username est libre ?
        require_once 'db/_user.php';
        $username_ok = ! username_exists($username);
        if ( ! $username_ok) {// Si le username est déjà utilisé
            $username_msg = "Le username" . $username . " est déjà pris";
        };
    }
//    var_dump($username);
//    var_dump($username_ok);
}

/**
 * Validation du mot de passe
 */
$password_ok = false;
$password_msg = '';
if (array_key_exists('password', $_POST)) {
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    // Validation du mot de passe: alpha, chiffres,caracteres speciaux, min de 4 caracteres
    $password_ok = (1 === preg_match('/^[a-zA-Z0-9%&$!*?]{4,}$/', $password));
    if ( ! $password_ok) { // Si le prénom n'est pas valide
        $password_msg = 'Le password ne doit contenir que des lettres et des caractères spéciaux (min 4).';
    }
//    var_dump($password);
//    var_dump($password_ok);
}

if ($firstname_ok && $lastname_ok && $gender_ok && $email_ok && $username_ok && $password_ok) {
    // On enregistre les données et s'en va sur une autre page
    require_once 'db/_user.php';
    $user_info = user_add($username, $password, $firstname, $lastname, $email);
    header("Location:index.php");
    exit;
}
?>
<form id="inscription" name="inscription" xmlns="http://www.w3.org/1999/html" method="post" novalidate="novalidate">
    <!--    Champ prenom-->
    <label for="firstname">Prénom : </label>
    <input type="text" name="firstname" id="firstname"
           class="<?php echo $in_post && ! $firstname_ok ? 'error' : '';?>"
           value="<?php echo array_key_exists('firstname', $_POST) ? $_POST['firstname'] : '' ?>"
    />
    <?php if ($in_post && ! $firstname_ok) {
        echo "<p class='error_msg'>$firstname_msg</p>";
    } ?>

    <!--    Champ nom-->
    <label for="lastname">Nom : </label>
    <input type="text" name="lastname" id="lastname"
           class="<?php echo $in_post && ! $lastname_ok ? 'error' : '';?>"
           value="<?php echo array_key_exists('lastname', $_POST) ? $_POST['lastname'] : '' ?>"
    />
    <?php if ($in_post && ! $lastname_ok) {
        echo "<p class='error_msg'>$lastname_msg</p>";
    } ?>

    <!--    Champ sexe-->
    <label>Sexe : </label>
    <label for="gender_male" >H</label>
    <input type="radio" name="gender" id="gender_male" value="gender_male"
        <?php echo (array_key_exists('gender', $_POST) && ($_POST['gender'] == 'gender_male')) ? 'checked="checked"' : '' ?>/>
    <label for="gender_female" >F</label>
    <input type="radio" name="gender" id="gender_female" value="gender_female"
        <?php echo (array_key_exists('gender', $_POST) && ($_POST['gender'] == 'gender_female')) ? 'checked="checked"' : '' ?>/>
    <?php if ($in_post && ! $gender_ok) {
        echo "<p class='error_msg'>$gender_msg</p>";
    } ?>

    <!--    Champ courriel -->
    <label for="email">Courriel : </label>
    <input type="email" name="email" id="email"
           value="<?php echo array_key_exists('email', $_POST) ? $_POST['email'] : '' ?>"
           class="<?php echo $in_post && ! $email_ok ? 'error' : '';?>"
    />
    <?php if ($in_post && ! $email_ok) {
        echo "<p class='error_msg'>$email_msg</p>";
    } ?>

    <!--    Champ pseudo -->
    <label for="username">Pseudo : </label>
    <input type="text" name="username" id="username"
           value="<?php echo array_key_exists('username', $_POST) ? $_POST['username'] : '' ?>"
           class="<?php echo $in_post && ! $username_ok ? 'error' : '';?>"
    />
    <?php if ($in_post && ! $username_ok) {
        echo "<p class='error_msg'>$username_msg</p>";
    } ?>

    <!--    Champ mot de passe -->
    <label for="password">Password : </label>
    <input type="password" name="password" id="password"
           value="<?php echo array_key_exists('password', $_POST) ? $_POST['password'] : '' ?>"
           class="<?php echo $in_post && ! $password_ok ? 'error' : '';?>"
    />
    <?php if ($in_post && ! $password_ok) {
        echo "<p class='error_msg'>$password_msg</p>";
    } ?>

    <!--    Submit -->
    <input type="submit" name="register" id="register" value="S'inscrire"/>
</form>
