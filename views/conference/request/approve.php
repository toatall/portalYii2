<?php

/** @var \yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $dataProvider */

use yii\bootstrap4\Html;
use app\helpers\DateHelper;
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
        'rowOptions' => function($model) {
            /** @var AbstractConference $model */
            return $model->status == AbstractConference::STATUS_DENIED ? ['class' => 'bg-danger'] : [];
        },
        'columns' => [
           /*[
                'label' => 'Дата начала',
                'value' => function($model) {
                    return '<p class="calendar">' 
                        . date('d', strtotime($model->date_start)) . '<em>' 
                        . DateHelper::getMonthName(date('n', strtotime($model->date_start))) . '</em>'                        
                        . '</p>'; 
                },
                'format' => 'raw',
            ],          
            [
                'attribute' => 'time_start',
                'value' => function($model) {                    
                    return '<div class="text-center"><span style="font-size: 2em;"><i class="fas fa-clock"></i> ' . $model->time_start . '</span></div>';
                },
                'format' => 'raw',
            ],*/
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
                        return Html::a('Подробнее', ['/conference/approve-view', 'id'=>$model->id], [
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
    ]); ?>

</div>
<?php $this->registerJs(<<<JS
/*
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();        
    });
*/
JS); ?>