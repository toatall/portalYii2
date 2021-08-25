<?php

use app\models\rating\RatingMain;
use yii\bootstrap4\Html;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\Tree $modelTree */

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
                'value' => function (RatingMain $model) {
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

            ['class' => ActionColumn::class],
        ],
    ]); ?>


</div>
