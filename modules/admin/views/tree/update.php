<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\Tree $model */

$this->title = 'Изменить раздел: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Структура', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="tree-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
