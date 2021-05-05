<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\assets\fancybox\FancyboxAsset;

FancyboxAsset::register($this);

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Управление';
$this->params['breadcrumbs'][] = ['url' => '/events/contest-arts', 'label' => 'Конкурс "Навстречу искусству"'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contest-arts-index">

    <h1><?= Html::encode($this->title) ?></h1>  
    
    <p>
    <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['class' => ''],
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            'date_show',
            'department_name',
            //'department_ad_group',            
            [
                'attribute' => 'image_original',
                'value' => function($model) {
                    /* @var $model \app\modules\events\models\ContestArts */
                    if (empty($model->image_original)) {
                        return Yii::$app->formatter->nullDisplay;
                    }
                    return Html::a(Html::img($model->image_original, ['style' => 'height: 5em', 'class' => 'thumbnail']), $model->image_original, ['class' => 'fancybox'])
                        . "{$model->image_original_author} ({$model->image_original_title})";
                },
                'format' => 'raw',
            ],
            //'image_original_author',
            //'image_original_title',
            //'image_reproduced',
            [
                'attribute' => 'image_reproduced',
                'value' => function($model) {
                    /* @var $model \app\modules\events\models\ContestArts */
                    if (empty($model->image_reproduced)) {
                        return Yii::$app->formatter->nullDisplay;
                    }
                    return Html::a(Html::img($model->image_reproduced, ['style' => 'height: 5em', 'class' => 'thumbnail']), $model->image_reproduced, ['class' => 'fancybox'])
                        . "{$model->department_name} ({$model->image_reproduced_title})";
                },
                'format' => 'raw',
            ],
            //'description_original',
            //'description_reproduced',
            //'qr_code_file',
            'date_create:datetime',
            //'date_update',
            [
                'attribute' => '',
                'value' => function($model) {     
                    /* @var $model \app\modules\events\models\ContestArts */
                    $countNotSet = $model->countNotSetRight();
                    return Html::a('<i class="fas fa-check" title="Установить правльные ответы"></i>', ['set-right', 'id'=>$model->id], ['class' => 'btn btn-success mv-link'])
                        . '<br /><span class="label label-' . ($countNotSet > 0 ? 'warning' : 'success') . '">' . $countNotSet . '</span>';
                },
                'format' => 'raw',
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>