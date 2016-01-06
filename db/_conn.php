<?php
require_once(dirname(__FILE__) . '/_defines.php');

// Creation de l'objet PDO pour la connexion
// Il va nous servir tout au long du code pour l'utilisation de la DB
try {
    $pdo = new PDO(
        'mysql:host=' . PHPAT_DB_CONN_HOST . ';dbname=' . PHPAT_DB_DBNAME,
        PHPAT_DB_CONN_USER,
        PHPAT_DB_CONN_PWD,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'')
    );
} catch (PDOException $e) {
    echo 'Echec lors de la connexion à MySQL : (' . $e->getMessage() . ')<br/>';
    die();
}

/*if (!$mysqli->set_charset("utf8")) {
    printf("Erreur lors du chargement du jeu de caractères utf8 : %s\n", $mysqli->error);
}*/

?>


