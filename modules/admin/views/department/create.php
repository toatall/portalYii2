<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\Department\Department $model */

$this->title = 'Создание отдела';
$this->params['breadcrumbs'][] = ['label' => 'Отделы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="department-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
