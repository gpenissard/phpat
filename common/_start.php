<?php
session_start();
var_dump($_SESSION);
require_once('_loginout.php'); // Ici la session est démarrée
if ( ! $site_data[PAGE_IS_PUBLIC] && ! check_login()) { // Redirection si pas connecté
    header('Location:' . INSCRIPTION_PAGE);
}
