<?php
    // Réception des données de formulaire de login/logout
//var_dump($_SESSION);
$username = null;
$password = null;
if (array_key_exists('dologin', $_POST)
&& array_key_exists('username', $_POST)
&& array_key_exists('password', $_POST)) { // User cherche à se connecter
require_once('db/_user.php');
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
if ($auth = user_authenticate($username,$password)) {//authentifié
    do_login($username); // Connecté
} else {
        // TODO Gérer le bla bla de authentification invalide ici
}
//    var_dump($auth);exit();
} elseif (array_key_exists('dologout', $_POST)) { // User cherche à se déconnecter
    do_logout(); // On le déconnecte
    header('Location:' . HOME_PAGE);
}

 //
?>

<?php if ( ! check_login() ) {
    // Si l'utilisateur n'est pas connecté
    ?>
    <form id="login" name="login" method="post">
        <label for="username">Pseudo : </label>
        <input type="text" name="username" id="username" value="" />
        <label for="password">Mot de passe : </label>
        <input type="password" name="password" id="password" />
        <input type="submit" name="dologin" id="dologin" value="Entrer"/>
    </form>
<?php } else { // Si l'utilisateur est connecté ?>
    <form id="logout" name="logout" method="post">
        <input type="submit" name="dologout" id="dologout" value="Quitter"/>
    </form>
<?php } ?>
