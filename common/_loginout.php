<?php
define('SESS_CNX_USERNAME', 'sess_cnx_username');

/**
 * Vérifier si l'utilisateur est connecté
 * @return bool
 */
function check_login() {
    $result = array_key_exists(SESS_CNX_USERNAME, $_SESSION);
    return $result;
}

/**
 * Connecter l'utilisateur (à l'aide de son username)
 * @param $username
 */
function do_login($username) {
    $_SESSION[SESS_CNX_USERNAME] = $username;

}

/**
 * Déconnecter l'utilisateur (= supprimer la "variable" de session)
 */
function do_logout() {
    unset($_SESSION[SESS_CNX_USERNAME]);
}

