<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $modelRatingMain \app\models\rating\RatingMain */

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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
