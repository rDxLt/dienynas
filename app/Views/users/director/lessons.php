<h1>Pamokos</h1>
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
<form action="<?= base_url('/director/createLesson') ?>" method="post">
    <fieldset>
        <legend>Pridėti pamoką:</legend>
        Pamoka: <input type="text" name="title"><br>
        <input type="submit" value="Sukurti">
    </fieldset>
</form>
<hr>
<? if (isset($lesson)) { ?>
    <form action="<?= base_url('/director/updateLesson/' . $lesson['id']) ?>" method="post">
        <fieldset>
            <legend>Redaguoti pamoką:</legend>
            Pamoka: <input type="text" name="title" value="<?= $lesson['title'] ?>"><br>
            <input type="submit" value="Atnaujinti">
        </fieldset>
    </form>
    <hr>
<? } ?>
<table>
    <tr>
        <th>ID</th>
        <th>Pavadinimas</th>
        <th>Veiksmai</th>
    </tr>
    <? foreach ($lessons as $lesson) { ?>
        <tr>
            <td><?= $lesson['id'] ?></td>
            <td><?= $lesson['title'] ?></td>
            <td>
                <a href="<?= base_url('/director/lessons/' . $lesson['id']) ?>">REDAGUOTI</a><br/>
                <a href="<?= base_url('/director/deleteLesson/' . $lesson['id']) ?>">IŠTRINTI</a><br/>
            </td>
        </tr>
    <? } ?>
</table>