<?php

use app\models\rating\RatingMain;
use yii\bootstrap5\Html;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\Tree $modelTree */

$this->title = 'Виды рейтингов для раздела "' . $modelTree->name . '"';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rating-main-index">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <p>
        <?= Html::a('Создать новый вид рейтинга', ['create', 'idTree' => $modelTree->id], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'id' => 'grid-rating-index',
        'pjax' => true,
        'responsive' => false,
        'striped' => false,
        'dataProvider' => $dataProvider,
        'pager' => [
            'firstPageLabel' => 'Первая',
            'lastPageLabel' => 'Последняя',
        ],
        'dataProvider' => $dataProvider,
        'columns' => [            
            [
                'value' => function (RatingMain $model) {
                    return Html::a('Размещение рейтинга', ['/admin/rating-data/index', 'idMain' => $model->id], 
                        ['class' => 'btn btn-primary', 'data-pjax' => false]);
                },
                'format' => 'raw',
            ],
            'id',
            'name',
            'note',
            'date_create:datetime',
            [
                'class' => ActionColumn::class,
                'dropdown' => true,
            ],
        ],
        'toolbar' => [
            '{export}',
            '{toggleData}',
        ],
        'export' => [
            'showConfirmAlert' => false,
        ],
        'panel' => [
            'type' => GridView::TYPE_DEFAULT,       
        ],
    ]); ?>


</div>
