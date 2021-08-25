<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\rating\RatingMain $model */

$this->title = 'Изменение вида рейтинга: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Виды рейтингов', 'url' => ['index', 'idTree' => $model->id_tree]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="rating-main-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
