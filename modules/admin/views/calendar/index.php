<?php

use app\models\calendar\Calendar;
use kartik\date\DatePicker;
use yii\bootstrap4\Html;
use yii\grid\GridView;
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
                    return Html::tag('span', $model->date, ['class'=>'badge badge-' . $model->color, 'style'=>'font-size:1.3rem;']);                    
                },
                'format' => 'raw',
            ],       
            [
                'attribute' => 'date_create',
                'filter' => false,
                'format' => 'datetime',
            ],
            'userModel.fio:text:Автор',            
            [
                'value' => function(Calendar $model) {
                    return Html::a('<i class="fas fa-star text-white"></i> События', ['/admin/calendar/view', 'id' => $model->id], ['class' => 'btn btn-warning']);                    
                },
                'format' => 'raw',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Html::a('<i class="fas fa-plus-circle"></i> Добавить', ['/admin/calendar/create'], ['class' => 'btn btn-primary']),
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>

    <?php Pjax::end() ?>


</div>
