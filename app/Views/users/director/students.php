<h1>Moksleiviai</h1>
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

<? if (isset($student)) { ?>
    <form action="<?= base_url('/director/updateStudent/' . $student['id']) ?>" method="post">
        <fieldset>
            <legend>Redaguoti moksleivį:</legend>
            Email: <input type="text" name="email" value="<?= $student['email'] ?>"><br>
            Slaptažodis: <input type="text" name="password"><br>
            Vardas: <input type="text" name="firstname" value="<?= $student['firstname'] ?>"><br>
            Pavardė: <input type="text" name="lastname" value="<?= $student['lastname'] ?>"><br>
            Klasė: <select name="class_id">
                <option value="">-</option>
                <? foreach ($classes

                            as $class) { ?>
                    <option value="<?= $class['id'] ?>" <? if ($student['class_id'] == $class['id']) {
                        echo 'selected';
                    } ?> ><?= $class['title'] ?></option>
                <? } ?>
            </select><br/>
            <input type="submit" value="Išsaugoti">
        </fieldset>
    </form>
    <hr>
<? } ?>

<hr>
<form action="<?= base_url('/director/createStudent') ?>" method="post">
    <fieldset>
        <legend>Pridėti moksleivį:</legend>
        Email: <input type="text" name="email"><br>
        Slaptažodis: <input type="text" name="password"><br>
        Vardas: <input type="text" name="firstname"><br>
        Pavardė: <input type="text" name="lastname"><br>
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
<table>
    <tr>
        <th>ID</th>
        <th>el. paštas</th>
        <th>Vardas</th>
        <th>Pavardė</th>
        <th>Klasė</th>
        <th>Veiksmas</th>
    </tr>
    <? foreach ($students as $student) { ?>
        <tr>
            <td><?= $student['id'] ?></td>
            <td><?= $student['email'] ?? null ?></td>
            <td><?= $student['firstname'] ?? null ?></td>
            <td><?= $student['lastname'] ?? null ?></td>
            <td><?= $student['class'] ?? null ?></td>
            <td>
                <a href="<?= base_url('/director/students/' . $student['id']) ?>">REDAGUOTI</a> |
                <a href="<?= base_url('/director/deleteStudent/' . $student['id']) ?>">Ištrinti</a>
            </td>
        </tr>
    <? } ?>
</table>