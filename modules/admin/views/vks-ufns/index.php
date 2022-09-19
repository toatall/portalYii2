<?php

use yii\bootstrap5\Html;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\conference\VksUfnsSearch $searchModel */

$this->title = 'ВКС с УФНС';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vks-ufns-index">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'id' => 'grid-vks-ufns-index',
        'pjax' => true,
        'responsive' => false,
        'striped' => false,
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function($model, $index, $widget, $grid) {
            return $model->isFinished() ? ['class' => 'table-success'] : [];
        },
        'pager' => [
            'firstPageLabel' => 'Первая',
            'lastPageLabel' => 'Последняя',
        ],
        'columns' => [          
            'date_start',       
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
            
            //'time_start_msk:datetime',
            //'duration',
            //'is_confidential',
            //'place',
            //'note',
            'date_create:datetime',
            //'date_edit',
            //'date_delete',
            //'log_change',

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
