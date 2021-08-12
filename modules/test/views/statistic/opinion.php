<?php
/** @var yii\data\ArrayDataProvider $dataProvider */
/** @var app\modules\test\models\Test $model */
/** @var yii\web\View $this */

use kartik\grid\GridView;
use kartik\widgets\StarRating;

$this->title = 'Результаты опроса';
$this->params['breadcrumbs'][] = $model->name;
?>

<?= GridView::widget([  
    'id' => 'grid-statistic-opinion',  
    'dataProvider' => $dataProvider,
    'columns' => [        
        [
            'label' => 'Оценка',
            'value' => function($model) { 
                return StarRating::widget([
                    'id' => 'star-rating-' . $model['id'],                   
                    'pluginOptions' => [                        
                        'readonly' => true,                                               
                    ],
                    'name' => 'rating',
                    'value' => $model['rating'],
                ]); 
            },
            'format' => 'raw',
        ],
        'note:text:Примечание',
        'author:text:Автор (учетная запись)',        
        'fio:text:Автор (ФИО)',      
        'date_create:datetime:Дата создания',
    ],
    'toolbar' => [
        '{export}',
    ],
    'pjax' => true,
    'exportConfig' => [
        GridView::EXCEL => [
            'filename' => "{$model->name} (результаты опроса)",  
        ],
        GridView::CSV => [
            'filename' => "{$model->name} (результаты опроса)",  
        ],        
    ],    
    'panel' => [
        'type' => GridView::TYPE_DEFAULT,
        'heading' => $this->title, 
    ],
]) ?>
