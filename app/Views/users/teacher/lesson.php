Pamoka: <?= $schedule['title'] ?><br/>
Kabinetas: <?= $schedule['cabinet'] ?><br/>
Klasė: <?= $teacher['title'] ?><br/>
Data: <?= $date ?><br/>
<hr>
Mokiniai:<br/>
<form action="<?= base_url('/teacher/saveLesson/' . $schedule['id'] . '/' . $date) ?>"
      method="post">
    <table>
        <? foreach ($students as $student) { ?>

            <?php
            $content = '';
            if ($student['grade'] != null) {
                $content = $student['grade'];
            } elseif ($student['attendance'] != null) {
                if ($student['attendance'] == 'late') {
                    $content = 'p';
                } else if ($student['attendance'] == 'missing') {
                    $content = 'n';
                }
            } else if ($student['message'] != null) {
                $content = $student['message'];
            }
            ?>
            <tr>
                <td><?= $student['firstname'] ?></td>
                <td><?= $student['lastname'] ?></td>
                <td>
                    <input name="content[<?= $student['id'] ?>]" value="<?= $content ?>" type="text"/>
                </td>
            </tr>
        <? } ?>
    </table>
    <input type="submit" value="Išsaugoti"/>
</form>
