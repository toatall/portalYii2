<?php

use yii\bootstrap5\Html;
use kartik\grid\GridView;
use app\helpers\DateHelper;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\conference\ConferenceSearch $searchModel */

$this->title = 'Собрания';
$this->params['breadcrumbs'][] = $this->title;
$accessShowAllFields = $searchModel->accessShowAllFields();
$this->registerCssFile('/public/assets/portal/css/dayPost.css');
?>

<div class="conference-index">

    <div class="col border-bottom mb-2">
        <p class="display-5">
            <?= Html::encode($this->title) ?>
        </p>    
    </div>    

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-bordered'],
        'options' => [
            'style' => 'table-layout:fixed',
        ],
        'rowOptions' => function($model, $index, $widget, $grid) {
            return $model->isFinished() ? ['class' => 'finished'] : [];
        },
        'columns' => [
            [
                'attribute' => 'Дата начала',
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
                'visible' => $accessShowAllFields,
            ],
            [
                'attribute' => 'members_people',
                'visible' => $accessShowAllFields,
            ],
            'place',
            'duration',
            [
                'value' => function($model) {
                    return Html::a('Просмотр', ['conference/view', 'id'=>$model->id], ['class' => 'btn btn-primary mv-link']);
                },
                'format' => 'raw',
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
