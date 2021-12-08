<?php

use app\models\calendar\Calendar;
use kartik\date\DatePicker;
use kartik\grid\ActionColumn;
use yii\bootstrap4\Html;
use kartik\grid\GridView;
use yii\bootstrap4\ButtonDropdown;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\CalendarResourceSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Календарь';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="calendar-index">

    <p class="display-4 border-bottom"><?= Html::encode($this->title) ?></h1>


    <?php Pjax::begin(['timeout'=>false]) ?>

    <?= $this->render('_search', ['model' => $searchModel]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',            
            [
                'attribute' => 'date',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'date',
                ]),
                'value' => function(Calendar $model) {
                    return Html::tag('span', $model->date, ['class'=>'badge badge-' . $model->color, 'style'=>'font-size:1rem;']);                    
                },
                'format' => 'raw',
            ],       
            [
                'attribute' => 'date_create',
                'filter' => false,
                'format' => 'datetime',
            ],
            //'userModel.fio:text:Автор',            
            [
                'label' => 'События',
                'value' => function(Calendar $model) {
                    //return $model->getDataByText();
                    return Html::ul($model->getDataByText(), ['tag' => 'ol', 'encode' => false]);
                },
                'format' => 'html',
            ],
            [
                'value' => function(Calendar $model) {
                    return Html::a('<i class="fas fa-table text-white"></i> Подробнее', ['/admin/calendar/view', 'id' => $model->id], ['class' => 'btn btn-secondary btn-sm']);                    
                },
                'format' => 'raw',
            ],            
            [
                'class' => ActionColumn::class,
                //'header' => Html::a('<i class="fas fa-plus-circle"></i> Добавить', ['/admin/calendar/create'], ['class' => 'btn btn-primary']),
                'header' => ButtonDropdown::widget([
                    'label' => '<i class="fas fa-plus-circle"></i> Добавить',
                    'encodeLabel' => false,
                    'dropdown' => [
                        'items' => [
                            ['label' => 'Добавить одну запись', 'url' => ['/admin/calendar/create']],
                            ['label' => 'Массовое добавление', 'url' => ['/admin/calendar/create-multi']],
                        ],
                    ],
                    'buttonOptions' => [
                        'class' => 'btn btn-primary',
                    ],
                ]),
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>

    <?php Pjax::end() ?>


</div>
