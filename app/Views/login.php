<? if (isset($errors)) { ?>
    <?= $errors ?>
<? } ?>
<h1>Prisijungimas</h1>

<form action="<?= base_url('/home/login') ?>" method="post">
    Email: <input type="text" name="email"><br>
    Password: <input type="text" name="password"><br>
    <input type="submit" value="Prisijungti">
</form>