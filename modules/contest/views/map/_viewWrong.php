<?php

/** @var yii\web\View $this */
/** @var array $model */

?>

<div class="card card-body border-top-0">
    <ol>
    <?php foreach ($model as $item): ?>
        <li>
            <strong><?= $item['place_name'] ?></strong>
            (ответов: <?= $item['count'] ?>)
        </li> 
    <?php endforeach; ?>
    </ol>
</div>