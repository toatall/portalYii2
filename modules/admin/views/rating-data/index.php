<?php

use yii\bootstrap4\Html;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\rating\RatingMain $modelRatingMain */

$this->title = 'Рейтинги';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rating-data-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить рейтинг', ['create', 'idMain' => $modelRatingMain->id], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            //'note',
            'rating_year',
            [
                'attribute' => 'rating_period',
                'value' => function($model) { return $model->periodName; },
            ],
            //'rating_period',
            //'log_change',
            'date_create:datetime',
            //'author',

            ['class' => ActionColumn::class],
        ],
    ]); ?>


</div>
