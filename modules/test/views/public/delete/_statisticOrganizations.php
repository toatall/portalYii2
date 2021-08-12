<?php

/* @var $this yii\web\View */
/* @var $resultQuery array */

?>
<table class="table table-bordered table-striped">
    <tr>
        <th>Налоговый орган</th>
        <th>Количество завершенных тестов</th>
        <th>Количество отвеченных вопросов</th>
        <th>Правильно отвеченных вопросов</th>
        <th>% правильных ответов</th>
    </tr>
    <?php foreach ($resultQuery as $result): ?>
        <tr>
            <td><?= $result['name'] . ' (' . $result['org_code'] . ')' ?></td>
            <td><?= $result['count_test'] ?></td>
            <td><?= $result['count_question'] ?></td>
            <td><?= $result['count_right'] ?></td>
            <td><?= round(($result['count_right']/$result['count_question'])*100) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
