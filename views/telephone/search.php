<?php

/** @var \yii\web\View $this */

use yii\bootstrap5\Breadcrumbs;

/** @var \yii\db\ActiveQuery $result */

?>
<?php if ($result): ?>
<table class="table table-bordered">
    <tr>
        <th>Структура</th>
        <th>ФИО</th>        
        <th>Должность</th>
        <th>Телефон</th>
    </tr>
    <?php foreach ($result as $item): ?>
    <tr>
        <td>
            <?= Breadcrumbs::widget([
                'homeLink' => false,
                'links' => $item['path'],
            ]) ?>
        </td>
        <td><?= $item['user']['fio'] ?></td>        
        <td><?= $item['user']['post'] ?></td>
        <td>
            <?= $item['user']['telephone'] ?><br />
            <?= $item['user']['telephone_dop'] ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php else: ?>
    <div class="alert alert-info">Нет данных</div>
<?php endif; ?>