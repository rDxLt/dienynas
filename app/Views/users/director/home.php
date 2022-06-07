<h1>direktoriaus aplinka</h1> (<a href="<?= base_url('/home/logout') ?>">Atsijungti</a>)
<hr>
<? if (isset($errors)) { ?>
    <?= $errors ?>
<? } ?>
<? if (isset($success)) { ?>
    <?= $success ?>
<? } ?>

<ul>
    <li>
        <a href="<?= base_url('/director/teachers') ?>">Mokytojai</a>
    </li>
    <li>
        <a href="<?= base_url('/director/lessons') ?>">Pamokos</a>
    </li>
    <li>
        <a href="<?= base_url('/director/classes') ?>">Klasės</a>
    </li>
    <li>
        <a href="<?= base_url('/director/students') ?>">Moksleiviai</a>
    </li>
</ul>
