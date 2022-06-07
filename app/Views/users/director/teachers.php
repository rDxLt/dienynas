<h1>Mokytojai</h1>
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
<form action="<?= base_url('/director/createTeacher') ?>" method="post">
    <fieldset>
        <legend>Pridėti mokytoją:</legend>
        Email: <input type="text" name="email"><br>
        Slaptažodis: <input type="text" name="password"><br>
        Vardas: <input type="text" name="firstname"><br>
        Pavardė: <input type="text" name="lastname"><br>
        Pamoka: <select name="lesson_id">
            <option value="">-</option>
            <? foreach ($lessons as $lesson) { ?>
                <option value="<?= $lesson['id'] ?>"><?= $lesson['title'] ?></option>
            <? } ?>
        </select><br/>
        Klasė: <select name="class_id">
            <option value="">-</option>
            <? foreach ($classes as $class) { ?>
                <option value="<?= $class['id'] ?>"><?= $class['title'] ?></option>
            <? } ?>
        </select><br/>
        <input type="submit" value="Sukurti">
    </fieldset>
</form>
<hr>
<h1>Mokytojai</h1>
<table>
    <tr>
        <th>ID</th>
        <th>el. paštas</th>
        <th>Vardas</th>
        <th>Pavardė</th>
        <th>Auklėtojas</th>
        <th>Dalyko mokytojas</th>
        <th>Veiksmas</th>
    </tr>
    <? foreach ($teachers as $teacher) { ?>
        <tr>
            <td><?= $teacher['id'] ?></td>
            <td><?= $teacher['email'] ?? null ?></td>
            <td><?= $teacher['firstname'] ?? null ?></td>
            <td><?= $teacher['lastname'] ?? null ?></td>
            <td><?= $teacher['class'] ?? null ?></td>
            <td><?= $teacher['lesson'] ?? null ?></td>
            <td>
                <a href="<?= base_url('/director/editTeacher/' . $teacher['id']) ?>">REDAGUOTI</a>
            </td>
        </tr>
    <? } ?>
</table>
