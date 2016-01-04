<?php
// Validation ici

/**
 * Created by PhpStorm.
 * User: gpnissar
 * Date: 2015-12-16
 * Time: 19:48
 */

?>

<form name="inscription" method="post">
    <label for="firstname">Prénom : </label><input type="text" name="firstname" id="firstname" value="<?php echo isset($_POST['firstname']) ? $_POST['firstname'] : ''; ?>"/><br>
    <label for="lastname">Nom : </label> <input type="text" name="lastname" id="lastname" value="<?php echo isset($_POST['lastname']) ? $_POST['lastname'] : ''; ?>"/><br>
    <label for="email">Courriel : </label><input type="text" name="email" id="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>"/><br>
    <label for="username">Pseudo : </label><input type="text" name="username" id="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>"/><br>
    <label for="password">Mot de passe : </label><input type="password" name="password" id="password" value="<?php echo isset($_POST['password']) ? $_POST['password'] : ''; ?>"/><br>
    <input type="submit" name="subscribe" id="subscribe" value="S'inscrire"/>
</form>
