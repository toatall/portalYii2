<?php

/** @var \yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $dataProvider */

use yii\bootstrap5\Html;
use kartik\grid\GridView;
use app\models\conference\AbstractConference;
use kartik\grid\ActionColumn;

$this->title = 'Согласование заявок для проведения мероприятий';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('/public/assets/portal/css/dayPost.css');
?>

<div class="conference-request">

    <div class="col border-bottom mb-2">
        <p class="display-4">
            <?= Html::encode($this->title) ?>
        </p>    
    </div>    
    
   
    <?= GridView::widget([        
        'dataProvider' => $dataProvider,        
        'tableOptions' => ['class' => 'table table-bordered'],
        'options' => [
            'style' => 'table-layout:fixed',
        ],
       
        'columns' => [           
            'date_start:date:Дата начала',
            'time_start:time:Время начала',
            'theme',                                
            [
                'attribute' => 'members_people',                
            ],  
            'place',
            'duration',            
            [
                'label' => 'Пересечение',
                'format' => 'raw',
                'value' => function($model) {
                    /** @var AbstractConference $model */
                    if (($crossed = $model->isCrossedI()) !== null) {
                        return "<span class=\"badge badge-danger\">Пересечение с {$crossed->typeLabel()} {$crossed->date_start}</span>"
                            . '<br />' . Html::a('Просмотр', ['/conference/view', 'id'=>$crossed->id], ['class'=>'btn btn-secondary btn-sm mv-link']);
                    }
                    return '<snap class="badge badge-success"><i class="fas fa-check"></i> Нет</span>';
                },
            ],            
            [
                'class' => ActionColumn::class,
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url, $model) {                      
                        return Html::a('Подробнее', ['/conference/request-approve-view', 'id'=>$model->id], [
                            'class' => 'btn btn-primary mv-link',
                            'data-toggle' => 'tooltip',
                            'title' => 'Согласовать или отказать в согласовании',
                            'data-html' => 'true',
                            'data-trigger' => 'hover',
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

</div>
<?php $this->registerJs(<<<JS

    $(modalViewer).on('onRequestJsonAfterAutoCloseModal', function() {
        window.location.reload();
    });

JS); ?>