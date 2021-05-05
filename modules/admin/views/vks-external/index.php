<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

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
            //'id',
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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
