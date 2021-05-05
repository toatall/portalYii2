<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\helpers\DateHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \app\models\conference\ConferenceSearch */

$this->title = 'Собрания';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('/css/dayPost.css');
?>
<div class="conference-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-bordered'],
        'options' => [
            'style' => 'table-layout:fixed',
        ],
        'rowOptions' => function($model, $index, $widget, $grid) {
            return $model->isFinished() ? ['class' => 'bg-success'] : [];
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
            'theme',
            'members_people',
            'place',
            'duration',
            [
                'value' => function($model) {
                    return Html::a('Просмотр', ['conference/view', 'id'=>$model->id], ['class' => 'btn btn-default mv-link']);
                },
                'format' => 'raw',
            ],
        ],
    ]); ?>

</div>
