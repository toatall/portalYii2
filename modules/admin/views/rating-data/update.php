<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\rating\RatingData $model */

$this->title = 'Изменение рейтинга: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Рейтинги', 'url' => ['index', 'idMain' => $model->id_rating_main]];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="rating-data-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
