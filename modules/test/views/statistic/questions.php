<?php
/** @var yii\data\ArrayDataProvider $dataProvider */
/** @var app\modules\test\models\Test $model */
/** @var yii\web\View $this */


use kartik\grid\ExpandRowColumn;
use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = 'Статистика по вопросам';
$this->params['breadcrumbs'][] = $model->name;
?>

<?= GridView::widget([  
    'id' => 'grid-statistic-questions',  
    'dataProvider' => $dataProvider,
    'columns' => [        
        'name:text:Вопрос',
        'count_answered:integer:Количество ответов',
        'count_right:integer:Количество правильных ответов',        
        [
            'label' => '% правильных ответов',
            'value' => function($item) {            
                if ($item['count_answered'] == 0) {
                    return 0 . '%';
                }   
                return round(intval($item['count_right']) / intval($item['count_answered']) * 100, 2) . '%';
            },
        ],       
        [
            'class' => ExpandRowColumn::class,
            'value' => function() { return GridView::ROW_COLLAPSED; },
            'detailUrl' => Url::to(['/test/statistic/questions-ajax']),            
        ],
    ],
    'toolbar' => [
        '{export}',
    ],
    'pjax' => true,
    'exportConfig' => [
        GridView::EXCEL => [
            'filename' => "{$model->name} (статистика по вопросам)",  
        ],
        GridView::CSV => [
            'filename' => "{$model->name} (статистика по вопросам)",  
        ],        
    ],    
    'panel' => [
        'type' => GridView::TYPE_DEFAULT,
        'heading' => $this->title, 
    ],
]) ?>
