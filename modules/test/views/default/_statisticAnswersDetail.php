<?php

/* @var $this yii\web\View */
/* @var $resultQuery array */

?>
<table class="table">
    <tr>
        <th>Организация</th>
        <th>Всего</th>
        <th>Павильно отвечено</th>
    </tr>
    <?php foreach ($resultQuery as $result): ?>
        <tr>
            <td><?= $result['org_code'] ?></td>
            <td><?= $result['count_all'] ?></td>
            <td><?= $result['count_right'] ?></td>
        </tr>
    <?php endforeach; ?>

</table>
