<?php
/**
 *  Quelques fonctions utiles et génériques pour les scripts exploitant la base de données p62_dbkitdem
 * 	- get_tb_cols() liste les colonnes d'une table
 */

/**
 * Fournir la liste des colonnes d'un table donnée
 * @param $tablecols : Le tableau des colonnes de la table
 * @param $prefix : Un caractère éventuel (par ex. le : pour les paramètres de requètes) devant chanque nom de colonne
 * @param $with_id : Si oui ou non on veut la colonne id dans la liste, (false = défaut)
 * @return string : La liste des colonnes, séparées par des virgules, sous forme de chaîne
 */
function get_tb_cols(array $tablecols, $prefix = '', $with_id = false) {
    if ( ! $with_id) {
        array_shift($tablecols);
    }
    $sep = ',' . $prefix; // Séparateur
    $resultat = $prefix . implode($tablecols, $sep);
    return $resultat;
}

/**
 * Fournir une chaine au format `nom_col`=:nom_col
 * @param $col_name : Le nom de la colonne
 * @param $operator : L'opérateur à utiliser entre le nom de la colonne et le paramètre ('=' par défaut, peut être 'LIKE')
 * @return string
 */
function get_tb_col_pair($col_name, $operator = '=') {
    return ACCENT_GRAVE_CAR . $col_name . ACCENT_GRAVE_CAR . ' ' . $operator . ' ' . COLON_CAR . $col_name;
}

/**
 * Encrypter un mot de passe
 * @param $password : Le mot de passe à encrypter
 * @return string : La chaîne représentant le mot de passe encrypté
 * http://www.yiiframework.com/wiki/425/use-crypt-for-password-storage/
 */
function passwd_encrypt($password) {
    if (version_compare(PHP_VERSION, '5.5.0') >= 0) {
        // La version est supérieure ou égale à 5.5 : On peut utiliser password_hash() et password_verify()
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
    } else {
        $salt = '$2y$07$' . strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
        $password_hash = crypt($password, $salt);
    }
    return $password_hash;
}

/**
 * Comparer un mot de passe avec un hash préalablement enregistré
 * NB : Le hash enregistré est nécéssaire pour calculer le hash du password fourni
 * @param $password : Le mot de passe à vérifier
 * @param $password_hash : Le hash du mot de passe à vérifier
 * @return boolean : True si valide, false sinon
 */
function passwd_check($password, $password_hash) {
    if (version_compare(PHP_VERSION, '5.5.0') >= 0) {
        // La version est supérieure ou égale à 5.5 : On peut utiliser password_hash() et password_verify()
        $result = password_verify($password, $password_hash);
    } else {
        $result = ( $password_hash === crypt($password, $password_hash) );
    }
    return $result;
}


/**
 * Vider une table
 * ATTENTION, pas d'avertissement ici !!!!
 * @param $tablename : nom de table
 * @return bool
 */
function table_truncate($tablename) {
    global $pdo;
    $resultat = false;
    $queryStr = 'TRUNCATE TABLE '. $tablename;
    try {
        $pdo->query($queryStr);
    } catch (PDOException $e) {
        echo "Echec tentative vidage de la table $tablename: (" . $e->getMessage() . ')<br/>';
    }
    $resultat = true;
    return $resultat;
}
