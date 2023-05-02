<?php

/** @var yii\web\View $this */
/** @var yii\data\ArrayDataProvider $dataProvider */

use app\modules\executetasks\models\ExecuteTasksChart;
use yii\grid\GridView;

$totalCountTasks = ExecuteTasksChart::getTotalDataProvider($dataProvider->allModels, 'count_tasks');
$totalFinishTasks = ExecuteTasksChart::getTotalDataProvider($dataProvider->allModels, 'finish_tasks');
$totalPersent = 0;
if ($totalCountTasks > 0) {
    $totalPersent = Yii::$app->formatter->asPercent($totalFinishTasks / $totalCountTasks);
}
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'layout' => "{items}\n{pager}",
    'showFooter' => true,
    'footerRowOptions' => [
        'style' => 'font-weight: bold;',
    ],
    'columns' => [
        [
            'label' => 'Наименование',
            'attribute' => 'name',
            'format' => 'text',
            'footer' => 'Итого',
        ],
        [
            'label' => 'Количество задач',
            'attribute' => 'count_tasks',
            'format' => 'integer',
            'footer' => $totalCountTasks,
        ],   
        [
            'label' => 'Исполнено задач',
            'attribute' => 'finish_tasks',
            'format' => 'integer',
            'footer' => $totalFinishTasks,
        ],                    
        [
            'format' => 'percent',
            'value' => function($model) {
                if ($model['count_tasks'] > 0) {
                    return $model['finish_tasks'] / $model['count_tasks'];
                }
                return 0;
            },
            'footer' => $totalPersent,
        ],
    ],
    'tableOptions' => [
        'class' => 'table table-bordered table-striped',
    ],
]) ?>
