<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\rating\RatingData $model */
/** @var app\models\rating\RatingMain $modelRatingMain */

$this->title = 'Добавление рейтинга';
$this->params['breadcrumbs'][] = ['label' => 'Рейтинги', 'url' => ['index', 'idMain' => $modelRatingMain->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rating-data-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
