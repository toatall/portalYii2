<?php
/** @var yii\data\ArrayDataProvider $dataProvider */
/** @var app\modules\test\models\Test $model */
/** @var string $orgCode */
/** @var yii\web\View $this */

use app\modules\test\models\TestResult;
use kartik\grid\ExpandRowColumn;
use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = 'Статистика по сотрудникам';
$this->params['breadcrumbs'][] = $model->name;
?>

<?= GridView::widget([  
    'id' => 'grid-statistic-general',  
    'dataProvider' => $dataProvider,
    'columns' => [        
         'username:text:Логин'
        ,'fio:text:ФИО'
        ,'date_create:datetime:Дата'
        ,'count_question:integer:Количество вопросов'
        ,'count_right:integer:Правильно отвеченых',
        [
            'label' => '% правильных ответов',
            'value' => function($item) {            
                if ($item['count_question'] == 0) {
                    return 0 . '%';
                }   
                return round(intval($item['count_right']) / intval($item['count_question']) * 100, 2) . '%';
            },
        ],
        [
            'label' => 'Статус',
            'value' => function($item) {
                if ($item['status'] == TestResult::STATUS_FINISH) {
                    return '<span class="text-success">Завершен</span>';
                }
                if ($item['status'] == TestResult::STATUS_CANCEL) {
                    return '<span class="text-danger">Отменено</span>';
                }
                return 'Неизвестный';
            },
            'format' => 'raw',
        ],        
        [
            'class' => ExpandRowColumn::class,
            'value' => function() { return GridView::ROW_COLLAPSED; },
            'detailUrl' => Url::to(['/test/result/view-ajax']),
        ],
    ],
    'toolbar' => [
        '{export}',
    ],
    'pjax' => true,
    'pjaxSettings' => [        
        'options'=>[
            'id' => 'pjax-grid-user-result',
            'enablePushState' => false,
            'timeout' => false,
        ],
    ],
    'exportConfig' => [
        GridView::EXCEL => [
            'filename' => "{$model->name} (статистика по сотрудникам {$orgCode})",  
        ],
        GridView::CSV => [
            'filename' => "{$model->name} (статистика по сотрудникам {$orgCode})",  
        ],        
    ],    
    'panel' => [
        'type' => GridView::TYPE_DEFAULT,
        'heading' => $this->title, 
    ],
    'rowOptions' => function($item) use ($model) {        
        if ($model->user_input) {
            return ['class' => $item['is_checked'] ? 'table-success' : 'table-warning'];
        }        
    },
]) ?>
