<?php

/** @var yii\web\View $this */
/** @var array $model */

?>

<div class="card card-body border-top-0">
    <ol>
    <?php foreach ($model as $item): ?>
        <li>
            <strong><?= $item['fio'] ?></strong>
            (<?= Yii::$app->formatter->asDatetime($item['date_create']) ?>)
        </li> 
    <?php endforeach; ?>
    </ol>
</div>