<?php
/* @var $this yii\web\View */
/* @var $data array */

?>
<table class="table table-bordered">
    <tr>
        <th>Наименование</th>
        <th>Дата ответа</th>
    </tr>
    <?php foreach ($data as $item): ?>
    <tr>
        <td><?= $item['image_original_title'] ?>  (<?= $item['image_original_author'] ?>)</td>
        <td><?= Yii::$app->formatter->asDatetime($item['date_create']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>

