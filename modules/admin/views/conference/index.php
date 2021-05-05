<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Собрания';
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
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            //'type_conference',
            [
                'attribute' => 'theme',
                'value' => function($model) {
                    return \yii\helpers\StringHelper::truncateWords($model->theme, 7);
                },
            ],
            [
                'attribute' => 'members_people',
                'value' => function($model) {
                    return \yii\helpers\StringHelper::truncateWords($model->members_people, 5);
                },
                'format' => 'raw',
            ],
            //'responsible',
            //'members_organization',
            'date_start',
            //'time_start_msk:datetime',
            //'duration',
            //'is_confidential',
            //'place',
            //'note',
            'date_create:datetime',
            //'date_edit',
            //'date_delete',
            //'log_change',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
