<?php
/**
 *  Script de test des opérations principales sur les utilisateurs de la base de données p62_dbkitdem
 * 	- authentification d'un utilisateur
 * 	- ajout d'un utilisateur
 * 	- connexion / deconnexion d'un utilisateur
 * 	- parcours des utilisateurs
 */

require_once('_user.php');

//var_dump(get_tb_cols($user_tb_cols));


/**
 * Préparation de la table pour les tests
 */
table_truncate(PHPAT_DB_TB_USERCNX); // Vider la table des usercnx
//table_truncate(PHPAT_DB_TB_USER); // Vider la table des users
user_delete('gp');
user_delete('jiminy');
user_delete('pinocchio');


/**
 * Ajout (insertion) d'un nouvel utilisateur
 * UC1 : Ajout de l'utilisateur 'gp' après vérification de la disponibilité du username
 * UC2 : Seconde vérification de la disponibilité du même username -> échec
 */
// UC1 : Ajout d'un utilisateur si le username est disponible
if ( ! username_exists('gp')) {
    user_add('gp', 'gp', 'Gilles','Pénissard', 'gilles.penissard@isi-mtl.com');
};
// UC2 : En principe, le username est déjà pris ici
if (username_exists('gp')) {
    echo "Le username (gp) est pris.";
};

/**
 * Authentification d'un utilisateur
 * UC1 : Échec de l'authentification
 * UC2 : Réussite de l'authentification
 */

// UC1 : Tentative d'authentification avec un mot de passe incorrect
$gp_user_info = user_authenticate('gp','invalid_password');
// En principe, le réponse vaut false
if (false === $gp_user_info) {
    echo "<p>L'authentification de l'utilisateur 'gp' avec le mot de passe 'invalid_password' a échoué.</p>";
}

// UC2 : Tentative d'authentification réussie
$gp_user_info = user_authenticate('gp','gp');
// En principe la réponse ne vaut pas false et contient les paramètres de l'utilisateur authentifié
if (false === $gp_user_info) {
    echo "<p>L'authentification de l'utilisateur 'gp' avec le mot de passe 'gp' a échoué.</p>";
} else {
    echo "<p>L'authentification de l'utilisateur 'gp' avec le mot de passe 'gp' a réussi.</p>";
    echo "<p>Les paramètres de l'utilisateur sont :" . implode($gp_user_info, ',') . "</p>";
}


/**
 * Enregistrer plusieurs connexions et déconnexions et Lister les utilisateurs connectés
 *
 * UC1: Connexion utilisateur 'gp'
 * UC2: Déconnexion utilisateur 'gp'
 * UC3: Lister des utilisateurs connectés après ajouts et connexion
 */
// UC1 : Connexion utilisateur 'gp'
user_cnx_in_out($gp_user_info['id'], '_gp_', CNX_STATE_LOGIN);
// UC2 : Déconexion utilisateur 'gp'
user_cnx_in_out($gp_user_info['id'], '_gp_', CNX_STATE_LOGOUT);
// UCS3 :
// - Ajout de deux nouveaux utilisateurs puis connexion du premier de ceux-ci
// - Reconnexion de l'utilisateur 'gp'
// - Listage des utilisateurs connctés (2 le sont)
$pinocchio_user_id = user_add('pinocchio', 'pinocchio', 'Pinocchio','La marionetta', 'pinocchio.marionetta@isi-mtl.com');
$jiminy_user_id = user_add('jiminy', 'jiminy', 'Jiminy','Cricket', 'jiminy.cricket@isi-mtl.com');
// - Connection de l'utilisateur 'jiminy' et re-connexion de l'utilisateur 'gp'
user_cnx_in_out($jiminy_user_id, '_jiminy_', CNX_STATE_LOGIN);
user_cnx_in_out($gp_user_info['id'], '_gp_', CNX_STATE_LOGIN);
// - Listage des utilisateurs connctés (2 le sont)
$utilisateurs_connectes = user_list();
var_dump($utilisateurs_connectes);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
</head>
<body>

<?php
echo "<ul>";
echo "</ul>";
?>
</body>
</html>
