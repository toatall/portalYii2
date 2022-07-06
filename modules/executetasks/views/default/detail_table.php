<?php

/** @var yii\web\View $this */
/** @var yii\data\ArrayDataProvider $dataProvider */

use app\modules\executetasks\models\ExecuteTasksChart;
use yii\grid\GridView;

?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'layout' => "{items}\n{pager}",
    'columns' => [
        'name:text:Наименование',
        'count_tasks:integer:Количество задач',
        'finish_tasks:integer:Исполнено задач',
        [
            'format' => 'percent',
            'value' => function($model) {
                if ($model['count_tasks'] > 0) {
                    return $model['finish_tasks'] / $model['count_tasks'];
                }
                return 0;
            },
            'footer' => ExecuteTasksChart::getTotalDataProvider($dataProvider->allModels, 'count_tasks'),
        ],
    ],
    'tableOptions' => [
        'class' => 'table table-bordered table-dark table-striped',
    ],
]) ?>
