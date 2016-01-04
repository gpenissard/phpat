<?php
require_once '_defines.php';
require_once 'data/_main_data.php';
require_once 'db/_talkmsg_data.php';
$site_data[PAGE_ID] = 'dashboard';
require_once 'view_parts/_page_base.php';
?>

<div id="main">
    <ul>
        <?php foreach ($talk_msg_data as $tmsg) { ?>
            <li class="tmsg_cont" style="background-color: <?php echo $tmsg['tmsg_color'] ?>">
                <div class="tmsg_head">
                    <span class="tmsg_time"><?php echo $tmsg['tmsg_time'] ?></span>
                    <span class="tmsg_username"><?php echo $tmsg['tmsg_user'] ?></span>
                </div>
                <p class="tmsg_body"><?php echo $tmsg['tmsg_body'] ?></p>
            </li>

        <?php } ?>
    </ul>
</div>

<?php
require_once 'view_parts/_page_bottom.php';
?>
