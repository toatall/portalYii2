<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\Department\Department $model */

$this->title = 'Изменение отдела: ' . $model->getConcatened();
$this->params['breadcrumbs'][] = ['label' => 'Отделы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->getConcatened(), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="department-update">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
