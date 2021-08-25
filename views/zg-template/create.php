<?php

/** @var yii\web\View $this */
/** @var app\models\zg\ZgTemplate $model */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Шаблоны ответов на однотипные обращения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="zg-template-create">

    <div class="col border-bottom mb-2">
        <p class="display-4">
            <?= $this->title ?>
        </p>    
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
