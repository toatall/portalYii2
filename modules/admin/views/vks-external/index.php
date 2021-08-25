<?php

use yii\bootstrap4\Html;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;
use yii\helpers\StringHelper;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'ВКС внешние';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="conference-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [            
            [
                'attribute' => 'theme',
                'value' => function($model) {
                    return StringHelper::truncateWords($model->theme, 7);
                },
            ],
            'responsible',            
            'date_start',
            'duration',            
            'place',
            'format_holding',
            'date_create:datetime',            

            ['class' => ActionColumn::class],
        ],
    ]); ?>


</div>
