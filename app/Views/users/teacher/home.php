mokytojo aplinka

<hr>
<? if (isset($class)) { ?>
    Mano klasė: <?= $class['title'] ?><br/>
    Max pamokų skaičius per savaitę: <?= $class['max_week_lessons'] ?>
    <hr>
    <table>
        <tr>
            <th>ID</th>
            <th>el. paštas</th>
            <th>Vardas</th>
            <th>Pavardė</th>
        </tr>
        <? foreach ($students as $student) { ?>
            <tr>
                <td><?= $student['id'] ?></td>
                <td><?= $student['email'] ?? null ?></td>
                <td><?= $student['firstname'] ?? null ?></td>
                <td><?= $student['lastname'] ?? null ?></td>
            </tr>
        <? } ?>
    </table>
<? } ?>
<h2>Klasės pamokų tvarkaraštis</h2>
<hr>
<? if (isset($errors)) { ?>
    <?= $errors ?>
<? } ?>
<? if (isset($success)) { ?>
    <?= $success ?>
<? } ?>
<hr>
<form action="<?= base_url('/teacher/addLesson') ?>" method="post">
    <fieldset>
        <legend>Pridėti pamoką į tvarkaraštį:</legend>
        <table>
            <tr>
                <td>Savaitės diena</td>
                <td>
                    <select name="week_day">
                        <option>-</option>
                        <? foreach ($days as $day) { ?>
                            <option value="<?= $day ?>"><?= ucfirst($day) ?></option>
                        <? } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Pamokos eilė</td>
                <td>
                    <select name="lesson_number">
                        <option>-</option>
                        <? for ($i = 1; $i <= round($class['max_week_lessons'] / 5); $i++) { ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <? } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Pamoka</td>
                <td>
                    <select name="teacher_id">
                        <option>-</option>
                        <? foreach ($teachers as $teacher) { ?>
                            <option value="<?= $teacher['id'] ?>"><?= $teacher['lesson'] ?>
                                (<?= $teacher['firstname'] ?> <?= $teacher['lastname'] ?>)
                            </option>
                        <? } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Kabinetas</td>
                <td>
                    <input type="text" name="cabinet">
                </td>
            </tr>
        </table>
        <br>
        <input type="submit" value="Pridėti">
    </fieldset>
</form>
Tvarkaraščio užpildymas: <?= $count_lessons ?> / <?= $class['max_week_lessons'] ?>
<br/>
<br/>
<table border="1">
    <tr>
        <? foreach ($days as $day) { ?>
            <th>
                <?= ucfirst($day) ?>
            </th>
        <? } ?>
    </tr>
    <tr>
        <? foreach ($days as $day) { ?>
            <td>
                <table>
                    <? foreach ($schedule[$day] as $item) { ?>
                        <tr>
                            <td>(<?= $item['lesson_number'] ?>)</td>
                            <td><?= $item['title'] ?></td>
                            <td>
                                <a href="<?= base_url('teacher/removeLesson/' . $item['id']) ?>">X</a>
                            </td>
                        </tr>
                    <? } ?>
                </table>
            </td>
        <? } ?>
    </tr>
</table>
