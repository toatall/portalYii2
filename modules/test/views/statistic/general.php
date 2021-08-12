<?php
/** @var yii\data\ArrayDataProvider $dataProvider */
/** @var app\modules\test\models\Test $model */
/** @var yii\web\View $this */

use kartik\grid\GridView;

$this->title = 'Общая статистика';
$this->params['breadcrumbs'][] = $model->name;
?>

<?= GridView::widget([  
    'id' => 'grid-statistic-general',  
    'dataProvider' => $dataProvider,
    'columns' => [        
        'org_code:text:Код организации',
        'count_test:text:Количество завершенных тестов',
        'count_question:integer:Количество отвеченных вопросов',
        'count_right:integer:Правильно отвеченных вопросов',
        [
            'label' => '% правильных ответов',
            'value' => function($item) {            
                if ($item['count_question'] == 0) {
                    return 0;
                }   
                return round(intval($item['count_right']) / intval($item['count_question']) * 100, 2);               
            },
        ],
    ],    
    'toolbar' => [
        '{export}',
    ],
    'pjax' => true,
    'exportConfig' => [        
        GridView::EXCEL => [
            'filename' => "{$model->name} (общая статистика)",  
        ],
        GridView::CSV => [
            'filename' => "{$model->name} (общая статистика)",  
        ],        
    ],    
    'panel' => [
        'type' => GridView::TYPE_DEFAULT,
        'heading' => $this->title, 
    ],
]) ?>
