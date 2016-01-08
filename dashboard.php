<?php
require_once '_defines.php';
require_once 'data/_main_data.php';
require_once 'db/_talkmsg_data.php';
$site_data[PAGE_ID] = 'dashboard';
$site_data[PAGE_IS_PUBLIC] = false; // Change le type de page publique -> privée
require_once 'common/_start.php';
require_once 'view_parts/_page_base.php';

/**
 * Gestion des likes
 */
if (array_key_exists(LIKE_ID, $_GET)) {
    // Il y a un paramètre like_id dans l'url
    // L,utilisateur aime le message numéro $_GET[LIKE_ID]
    $msg_id = $_GET[LIKE_ID];
//    var_dump($msg_id);
    // Stokker les préférences dans une variable de session à la clef LIKE_ID
    // On vérifie que cet élément est présent en session
    // S'il ne l'est pas on crée l'élment
    if ( ! array_key_exists(SESS_LIKES, $_SESSION)) {
        $_SESSION[SESS_LIKES] = array();
    }
    $does_like = array_key_exists($msg_id, $_SESSION[SESS_LIKES]);
    if ($does_like) {
        if ($_SESSION[SESS_LIKES][$msg_id] < 5) {
            // On incrémente le compteur
            $_SESSION[SESS_LIKES][$msg_id] = $_SESSION[SESS_LIKES][$msg_id] + 1 ;
        } else {
            // On supprime l'élément à cet id
            unset($_SESSION[SESS_LIKES][$msg_id]);
        }
    } else {
        // On créée un élément pour cet id avec 1 like
        $_SESSION[SESS_LIKES][$msg_id]= 1;
    }
    // Il faut se débarrasser du paramètre de queryString
    // On redirige vers la même page sans query string
    header('Location: dashboard.php');
    exit;
}
?>

<div id="main">
    <ul>
        <?php foreach ($talk_msg_data as $msg_id => $tmsg) { ?>
            <li class="tmsg_cont" style="background-color: <?php echo $tmsg['tmsg_color'] ?>">
                <div class="tmsg_head">
                    <span class="tmsg_time"><?php echo $tmsg['tmsg_time'] ?></span>
                    <span class="tmsg_username"><?php echo $tmsg['tmsg_user'] ?></span>
                    <?php $he_likes_msg = array_key_exists(SESS_LIKES, $_SESSION)
                        && array_key_exists($msg_id, $_SESSION[SESS_LIKES]); ?>
                    <a
                        href="?<?php echo LIKE_ID . '=' . $msg_id ?>"
                        class="<?php echo $he_likes_msg ? 'like_msg' : ''?>"
                    ><?php echo $he_likes_msg ? 'You like it ' . $_SESSION[SESS_LIKES][$msg_id] . ' times' : 'Like'; ?></a>
                </div>
                <p class="tmsg_body"><?php echo $tmsg['tmsg_body'] ?></p>
            </li>

        <?php } ?>
    </ul>
</div>

<?php
require_once 'view_parts/_page_bottom.php';
?>
