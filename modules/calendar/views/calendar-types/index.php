<?php
use yii\bootstrap5\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
?>
<div class="calendar-index">
    
    <?php Pjax::begin(['id'=>'pjax-calendar-types-index', 'timeout'=>false, 'enablePushState' => false]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'type_text',                        
            [
                'attribute' => 'date_create',
                'filter' => false,
                'format' => 'datetime',
            ],
            [
                'attribute' => 'date_update',
                'filter' => false,
                'format' => 'datetime',
            ],
            'author',                      
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Html::a('<i class="fas fa-plus-circle"></i> Добавить', ['/calendar/calendar-types/create'], 
                    ['class' => 'btn btn-primary', 'pjax' => true]),
                'template' => '{update} {delete}',   
                'buttons' => [
                    'update' => function($url, $model) {
                        return Html::a('<i class="fas fa-pencil-alt"></i>', ['update', 'id'=>$model->id], ['pjax'=>true]);
                    },
                    'delete' => function($url, $model) {
                        return Html::a('<i class="fas fa-trash text-danger"></i>', ['delete', 'id'=>$model->id], [                            
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить?',
                                'method' => 'post',                               
                            ],
                            'data-pjax' => true,
                        ]);
                    },
                ],                         
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

    <?php Pjax::end(); ?>

</div>
