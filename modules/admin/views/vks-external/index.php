<?php

use yii\bootstrap4\Html;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;
use kartik\select2\Select2;
use yii\helpers\StringHelper;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\conference\VksExternalSearch $searchModel */

$this->title = 'ВКС внешние';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="conference-index">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <p class="btn-group mt-3">
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'id' => 'grid-vks-external-index',
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
                    return StringHelper::truncateWords($model->theme, 7);
                },
            ],
            
            'responsible',            
            'duration',           
            [
                'attribute' => 'place',
                'filterType' => GridView::FILTER_SELECT2,
                'filterAttribute' => 'place',
                'filter' => $searchModel->dropDownListLocation(),
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'allowClear' => true,
                        'placeholder' => $searchModel->getAttributeLabel('place'),
                    ],
                ],
            ],
            [
                'attribute' => 'format_holding',
                'filterType' => GridView::FILTER_SELECT2,
                'filterAttribute' => 'format_holding',
                'filter' => $searchModel->dropDownListFormat(),
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'allowClear' => true,
                        'placeholder' => $searchModel->getAttributeLabel('format_holding'),
                    ],
                ],
            ],
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
