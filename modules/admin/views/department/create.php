<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Department\Department */

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
