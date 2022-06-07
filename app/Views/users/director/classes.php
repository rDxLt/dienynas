<h1>Klasės</h1>
<ul>
    <li>
        <a href="<?= base_url('/director') ?>">Pradžia</a>
    </li>
</ul>
<hr>
<? if (isset($errors)) { ?>
    <?= $errors ?>
<? } ?>
<? if (isset($success)) { ?>
    <?= $success ?>
<? } ?>
<hr>
<form action="<?= base_url('/director/createClass') ?>" method="post">
    <fieldset>
        <legend>Pridėti klasę:</legend>
        Pamoka: <input type="text" name="title"><br>
        Maksimalus valandų kiekis per savaitę: <input type="number" name="max_week_lessons"><br>
        <input type="submit" value="Sukurti">
    </fieldset>
</form>
<hr>
<table>
    <tr>
        <th>ID</th>
        <th>Pavadinimas</th>
        <th>Maksimalus valandų skaičius per savaitę</th>
    </tr>
    <? foreach ($classes as $class) { ?>
        <tr>
            <td><?= $class['id'] ?></td>
            <td><?= $class['title'] ?></td>
            <td><?= $class['max_week_lessons'] ?></td>
        </tr>
    <? } ?>
</table>