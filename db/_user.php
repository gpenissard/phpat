<?php
/**
 *  Opérations sur les utilisateurs
 * 	- authentification
 * 	- ajout d'utilisateur
 * 	- maj date dernière connexion
 */

require_once(dirname(__FILE__) . '/_conn.php');
require_once(dirname(__FILE__) . '/_common.php');
/**
 * Indique l'état connecté dans les appels de fonction
 */
define('CNX_STATE_LOGIN', 'CNX_STATE_LOGIN');
/**
 * Indique l'état deconnecté dans les appels de fonction
 */
define('CNX_STATE_LOGOUT', 'CNX_STATE_LOGOUT');
/**
 * Champ date_in de la table user
 */
define('USER_TB_DATE_IN', 'date_in');
/**
 * Champ date_out de la table user
 */
define('USER_TB_DATE_OUT', 'date_out');

/**
 * Liste des colonnes de la table user
 */
define('USER_TB_COL_ID', 'id');
define('USER_TB_COL_USERNAME', 'username');
define('USER_TB_COL_PASSWORD_HASH', 'password_hash');
define('USER_TB_COL_FIRSTNAME', 'firstname');
define('USER_TB_COL_LASTNAME', 'lastname');
define('USER_TB_COL_EMAIL', 'email');
$user_tb_cols = array(
    USER_TB_COL_ID,
    USER_TB_COL_USERNAME,
    USER_TB_COL_PASSWORD_HASH,
    USER_TB_COL_FIRSTNAME,
    USER_TB_COL_LASTNAME,
    USER_TB_COL_EMAIL,
);

define('USERCNX_TB_COL_ID', 'id');
define('USERCNX_TB_COL_USERID', 'user_id');
define('USERCNX_TB_COL_SESSIONID', 'session_id');
define('USERCNX_TB_COL_DATEIN', 'date_in');
define('USERCNX_TB_COL_DATELASTACCESS', 'date_last_access');
define('USERCNX_TB_COL_DATEOUT', 'date_out');
$usercnx_tb_cols = array(
    USERCNX_TB_COL_ID,
    USERCNX_TB_COL_USERID,
    USERCNX_TB_COL_SESSIONID,
    USERCNX_TB_COL_DATEIN,
    USERCNX_TB_COL_DATELASTACCESS,
    USERCNX_TB_COL_DATEOUT,
);

/**
 * Supprimer utilisateur
 * ATTENTION, pas d'avertissement ici !!!!
 * @param $username : Username
 * @return bool
 */
function user_delete($username) {
    global $pdo;
    $resultat = false; // Mode défensif
    $queryStr = 'DELETE FROM ' . PHPAT_DB_TB_USER. ' WHERE ' . get_tb_col_pair(USER_TB_COL_USERNAME);
    try {
        $sth = $pdo->prepare($queryStr);
        $params = array(
            COLON_CAR . USER_TB_COL_USERNAME => $username,
        );
        $res = $sth->execute($params);
        //$sth->debugDumpParams();
        //var_dump($res);
    } catch (PDOException $e) {
        echo "Echec tentative suppression de l'utilisateur $username : (" . $e->getMessage() . ')<br/>';
        exit();
    }
    $resultat = $res;
    return $resultat;
}

/**
 * Authentifier un utilisateur
 * On commence par rechercher l'utilisateur par son username
 * puis, s'il existe, on effectue le test d'authentification proprement dit
 * NB : Avec cette appproche, il faut lire le hash enregistré avant de faire la comparaison
 * @param $username
 * @param $password
 * @return array|bool
 */
function user_authenticate($username, $password) {
	global $pdo;
	$resultat = false; // Mode défensif
    $queryStr = 'SELECT * FROM ' . PHPAT_DB_TB_USER. ' WHERE ' . get_tb_col_pair(USER_TB_COL_USERNAME);
    try {
        $sth = $pdo->prepare($queryStr);
        $params = array(
            COLON_CAR . USER_TB_COL_USERNAME => $username,
        );
        $res = $sth->execute($params);
        //$sth->debugDumpParams();
        //var_dump($res);
    } catch (PDOException $e) {
        echo "Echec tentative d'authentification de l'utilisateur $username : (" . $e->getMessage() . ')<br/>';
        exit();
    }
	if ($res) {
		$user_data = $sth->fetch(PDO::FETCH_ASSOC);
        // Test de validité du mot de passe
        if (passwd_check($password, $user_data[USER_TB_COL_PASSWORD_HASH])) {
//            var_dump($user_data);
            // Retirer le hash des valeurs retournées
            unset($user_data[USER_TB_COL_PASSWORD_HASH]);
            $resultat = $user_data;
        }
    };
	return $resultat;
}

/**
 *  Indiquer qu'un username est déjà pris
 * @param $username
 * @return array|bool
 */
function username_exists($username) {
    global $pdo;
    $resultat = false; // Par défaut n'existe pas
    $queryStr = 'SELECT * FROM ' . PHPAT_DB_TB_USER. ' WHERE ' . get_tb_col_pair(USER_TB_COL_USERNAME);
    try {
        $sth = $pdo->prepare($queryStr);
        $params = array(
            COLON_CAR . USER_TB_COL_USERNAME => $username,
        );
        $res = $sth->execute($params);
        //$sth->debugDumpParams();
        //var_dump($res);
        //var_dump($sth->rowCount());
    } catch (PDOException $e) {
        echo "Echec tentative d'authentification de l'utilisateur $username : (" . $e->getMessage() . ')<br/>';
        exit();
    }
    if ($res && ($sth->rowCount() > 0)) {
        $resultat = $sth->fetch(PDO::FETCH_ASSOC);
        // Retirer le hash des valeurs retournées
        unset($resultat[USER_TB_COL_PASSWORD_HASH]);
    }
    return $resultat;
}

/**
 * Insertion (ajout) d'un nouvel utilisateur
 * NB: Provoque aussi l'ajout d'en enregistrement dans la table usercnx;
 * @param $username : username utilisateur
 * @param $password : password utilisateur
 * @param $firstname : firstname utilisateur
 * @param $lastname : lastname utilisateur
 * @param $email : email utilisateur
 * @return array|bool : Un array contgenant les deux id d'insertion (user et usercnx) en cas de succès, false en cas d'échec
 */
function user_add($username, $password, $firstname, $lastname, $email) {
    global $pdo, $user_tb_cols;
    $resultat = false; // Mode défensif
    $queryStr = 'INSERT INTO ' . PHPAT_DB_TB_USER . '(' . get_tb_cols($user_tb_cols) . ') VALUES (' . get_tb_cols($user_tb_cols, COLON_CAR) . ')';
    $sth = $pdo->prepare($queryStr);
    $password_hash = passwd_encrypt($password);
    $params = array(
        COLON_CAR . USER_TB_COL_USERNAME => $username,
        COLON_CAR . USER_TB_COL_PASSWORD_HASH => $password_hash,
        COLON_CAR . USER_TB_COL_FIRSTNAME => $firstname,
        COLON_CAR . USER_TB_COL_LASTNAME => $lastname,
        COLON_CAR . USER_TB_COL_EMAIL => $email,
    );
    $res = $sth->execute($params);
    //$sth->debugDumpParams();
    //var_dump($params);
    //var_dump($res);
    if ( ! $res || ($sth->rowCount()  == 0)) {
        throw new Exception("Echec lors de la tentative d'ajout de l'utilisateur $username : (" . $sth->errorInfo()[0] . ")<br/>");
    }
    $inserted_user_id = $pdo->lastInsertId();
    if ($res) {
        $resultat = $inserted_user_id;
    };
    return $resultat;
}

/**
 * Etablit la connexion d'un utilisateur (login)
 * Stratégie :
 *  Une entrée est ajoutée dans la table USERCNX
 * @param $user_id : id du user
 * @session_id : L'i    d de session de l'utilisateur
 * @return bool|mixed
 * @throws Exception
 */
function user_log_in($user_id, $session_id) {
    global $pdo, $usercnx_tb_cols;
    $resultat = false;
    $queryStr = 'INSERT INTO ' . PHPAT_DB_TB_USERCNX . ' (' . get_tb_cols($usercnx_tb_cols) . ') VALUES (' . get_tb_cols($usercnx_tb_cols, COLON_CAR) . ')';
    $sth = $pdo->prepare($queryStr);
    $params = array(
        COLON_CAR . USERCNX_TB_COL_USERID => $user_id,
        COLON_CAR . USERCNX_TB_COL_SESSIONID => $session_id,
        COLON_CAR . USERCNX_TB_COL_DATEIN => date("Y-m-d H:i:s"),
        COLON_CAR . USERCNX_TB_COL_DATELASTACCESS => date("Y-m-d H:i:s"),
        COLON_CAR . USERCNX_TB_COL_DATEOUT => null,
    );
    $res = $sth->execute($params);
    $sth->debugDumpParams();
    var_dump($res);
    var_dump($params);
    if ( ! $res || ($sth->rowCount()  == 0)) {
        throw new Exception("Echec lors de la tentative d'ajout d'une connexion pour l'utilisateur $user_id : (" . $sth->errorInfo()[0] . ")<br/>");
    }
    $inserted_usercnx_id = $pdo->lastInsertId();
    if ($res) {
        $resultat = $inserted_usercnx_id;
    };
    return $resultat;
}

/**
 * Termine la connexion d'un utilisateur (logout)
 * Stratégie :
 *  - Une entrée est recherchée dans la table USERCNX puis mise à jour
 * @param $user_id : id du user
 * @session_id : L'id de session de l'utilisateur
 * @return bool|mixed
 * @throws Exception
 */
function user_log_out($user_id, $session_id) {
    global $pdo;
    $resultat = false;
    $queryStr = 'UPDATE ' . PHPAT_DB_TB_USERCNX
        . ' SET ' . get_tb_col_pair(USERCNX_TB_COL_DATEOUT)
        . ' WHERE ' . get_tb_col_pair(USERCNX_TB_COL_SESSIONID)
           . ' AND ' . get_tb_col_pair(USERCNX_TB_COL_USERID);
    var_dump($queryStr);
    $sth = $pdo->prepare($queryStr);
    $params = array(
        COLON_CAR . USERCNX_TB_COL_USERID => $user_id,
        COLON_CAR . USERCNX_TB_COL_SESSIONID => $session_id,
        COLON_CAR . USERCNX_TB_COL_DATEOUT => date("Y-m-d H:i:s"),
    );
    $res = $sth->execute($params);
    $sth->debugDumpParams();
    var_dump($params);
    var_dump($res);
    if ( ! $res || ($sth->rowCount()  == 0)) {
        throw new Exception("Echec lors de la tentative de déconnexion pour l'utilisateur $user_id : (" . $sth->errorInfo()[0] . ")<br/>");
    }
    if ($res) {
        $resultat = true;
    };
    return $resultat;
}

/**
 * Etablir la connexion (login) ou la déconnexion (logout) d'un utilisateur
 * @param $user_id : id du user
 * @param $user_id : id du user
 * @param $cnx_state: état de connection CNX_STATE_LOGIN ou CNX_STATE_LOGOUT
 * @return bool|mixed
 * @throws Exception
 */
function user_cnx_in_out($user_id, $session_id, $cnx_state) {
    $resultat = false;
    switch ($cnx_state) {
        case CNX_STATE_LOGIN:
            $resultat = user_log_in($user_id, $session_id);
            break;
        case CNX_STATE_LOGOUT:
            $resultat = user_log_out($user_id, $session_id);
            break;
        default :
            throw new Exception("Error cnx_state parameter value is invalid ($cnx_state).");
    }
    return $resultat;
}


/**
 * Lister des utilisateurs connectés
 * NB : Pas de limite mise en place ici (à améliorer si le nb d'utilisateurs devient important
 * @param bool $cnx_state: (PAS GÉRÉ ENCORE) état de connection CNX_STATE_LOGIN ou CNX_STATE_LOGOUT
 * @return bool|mixed
 */
function user_list($cnx_state = false) {
    global $pdo;
    $resultat = false; // Par défaut n'existe pas
    $queryStr = 'SELECT u.' . USER_TB_COL_USERNAME
        . ' FROM ' . PHPAT_DB_TB_USER. ' AS u'
        . ' JOIN ' . PHPAT_DB_TB_USERCNX . ' AS uc ON (u.id = uc.user_id)'
        . ' WHERE uc.' . USERCNX_TB_COL_DATEOUT . ' IS NULL';
    try {
        $sth = $pdo->prepare($queryStr);
        $params = array(
//            COLON_CAR . USERCNX_TB_COL_DATEOUT => 'NULL',
        );
        $res = $sth->execute($params);
        $sth->debugDumpParams();
        var_dump($res);
        var_dump($sth->rowCount());
    } catch (PDOException $e) {
        echo "Echec tentative lister utilisateurs : (" . $e->getMessage() . ')<br/>';
        exit();
    }
    if ($res) {
        $resultat = $sth->fetchAll(PDO::FETCH_ASSOC);
    }
    return $resultat;
}
