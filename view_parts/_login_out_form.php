<?php ?>

<?php if(true) { ?>
    <form id="login" name="login">
        <label for="username">Pseudo : </label>
        <input type="text" name="username" id="username" value="" />
        <label for="password">Mot de passe : </label>
        <input type="password" name="password" id="password" />
        <input type="submit" name="dologin" id="dologin" value="Entrer"/>
    </form>
<?php } else { ?>
    <form id="logout" name="logout">
        <input type="submit" name="dologout" id="dologout" value="Quitter"/>
    </form>
<?php } ?>
