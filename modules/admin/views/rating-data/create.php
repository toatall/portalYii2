<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\rating\RatingData */
/* @var $modelRatingMain \app\models\rating\RatingMain */

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
