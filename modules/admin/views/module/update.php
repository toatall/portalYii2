<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\Module $model */

$this->title = 'Изменить модуль: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Модули', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="module-update">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
