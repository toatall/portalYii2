<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $modelTree \app\models\Tree */

$this->title = 'Виды рейтинга';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rating-main-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать новый вид рейтинга', ['create', 'idTree' => $modelTree->id], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'value' => function (\app\models\rating\RatingMain $model) {
                    return Html::a('Размещение рейтинга', ['/admin/rating-data/index', 'idMain' => $model->id], ['class' => 'btn btn-primary']);
                },
                'format' => 'raw',
            ],
            'id',
            'name',
            'note',
            //'log_change',
            'date_create:datetime',
            //'author',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
