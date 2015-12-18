<?php
$menu_data = array(
    'Accueil' => 'index.php',
    'Contact' => 'contact.php',
    'Dashboard' => 'dashboard.php',
    'Inscription' => 'inscription.php',
);
?>


<ul>
    <?php
    foreach($menu_data as $name => $link) {
        echo "<li><a href=\"$link\">$name</a></li>";
    }
    ?>
</ul>