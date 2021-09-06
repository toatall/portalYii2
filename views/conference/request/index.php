<?php

/** @var \yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $dataProvider */

use yii\bootstrap4\Html;
use app\helpers\DateHelper;
use kartik\grid\GridView;
use app\models\conference\AbstractConference;
use kartik\grid\ActionColumn;

$this->title = 'Заявки для проведения мероприятий';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('/public/assets/portal/css/dayPost.css');
?>

<div class="conference-request">

    <div class="col border-bottom mb-2">
        <p class="display-4">
            <?= Html::encode($this->title) ?>
        </p>    
    </div>    
    
    <?php if (!Yii::$app->user->can('permConferenceApprove')): ?>
        <?= Html::a('Добавить заявку', ['/conference/request-create'], ['class' => 'btn btn-primary mb-2']) ?>
    <?php endif; ?>
            
    <?= GridView::widget([
        'responsive' => true,
        'responsiveWrap' => false,
        'dataProvider' => $dataProvider,        
        'tableOptions' => ['class' => 'table table-bordered'],
        'options' => [
            'style' => 'table-layout:fixed',
        ],        
        'columns' => [
           [
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
            ],
            [
                'attribute' => 'theme',                                
            ],  
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
                        return "<span class=\"badge badge-danger\">Пересечение!</span>"
                            . '<br />' . Html::a('Просмотр', ['/conference/view', 'id'=>$crossed->id], ['class'=>'btn btn-secondary btn-sm mv-link']);
                    }
                    return '<snap class="badge badge-success"><i class="fas fa-check"></i> Нет</span>';
                },
                'noWrap'=>true,
            ],              
            [
                'label' => 'Статус',
                'format' => 'raw',
                'value' => function($model) {
                    /** @var AbstractConference $model */                    
                    switch ($model->status) {
                        case AbstractConference::STATUS_APPROVE:                             
                            return '<span class="badge badge-secondary fa-1x"><i class="far fa-hourglass"></i> Согласование</span>';
                        case AbstractConference::STATUS_COMPLETE: 
                            return '<span class="badge badge-success fa-1x"><i class="fas fa-check"></i> Согласовано</span>';                            
                        case AbstractConference::STATUS_DENIED: 
                            return '<span class="badge badge-danger fa-1x" data-toggle="tooltip" data-trigger="hover" data-html="true" data-content="<span class=\'text-danger\'><strong>' . $model->denied_text . '</strong></span>"><i class="fas fa-times"></i> Отказано</span>';       
                    }
                    return '<span class="badge badge-warning fa-1x"><i class="fas fa-exclamation"></i> Неизвестный</span>';                           
                },
            ],
            [
                'class' => ActionColumn::class,
                'buttons' => [
                    'view' => function($url, $model) {
                        return Html::a('<i class="fas fa-eye"></i>', ['conference/request-view', 'id'=>$model->id], ['class' => 'mv-link']);
                    },
                    'update' => function($url, $model) {
                        return Html::a('<i class="fas fa-pencil-alt"></i>', ['conference/request-update', 'id'=>$model->id]);
                    },
                    'delete' => function($url, $model) {
                        return Html::a('<i class="fas fa-trash-alt"></i>', ['conference/request-delete', 'id'=>$model->id], 
                    [
                        'data' => [
                            'confirm' => 'Вы уверены, что хотите удалить?',
                            'method' => 'post',
                        ],
                    ]);
                    },
                ],
                'visibleButtons' => [
                    'update' => function($model) {
                        /** @var AbstractConference $model */
                        if ($model->editor !== Yii::$app->user->identity->username) {
                            return false;
                        }
                        if ($model->status !== AbstractConference::STATUS_COMPLETE) {
                            return true;
                        }
                    },
                    'delete' => function($model) {
                        /** @var AbstractConference $model */
                        if ($model->editor !== Yii::$app->user->identity->username) {
                            return false;
                        }
                        if ($model->status !== AbstractConference::STATUS_COMPLETE) {
                            return true;
                        }
                    },
                ],
            ],
        ],
    ]); ?>

</div>



