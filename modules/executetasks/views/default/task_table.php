<?php

/** @var yii\web\View $this */
/** @var yii\data\ArrayDataProvider $dataProvider */
/** @var string $idOrganization */
/** @var int $period */
/** @var int $periodYear */

use app\modules\executetasks\models\ExecuteTasksChart;
use yii\bootstrap4\Html;
use yii\grid\GridView;
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'label' => 'Наименование',
            'format' => 'text',
            'value' => function($model) {
                return $model['department_index'] . ' ' . $model['department_name'];
            },
        ],
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
        [
            'format' => 'raw',
            'value' => function($model) use ($idOrganization, $period, $periodYear) {
                return Html::a('<i class="fas fa-angle-double-right"></i> Подробнее', 
                    ['/executetasks/default/data-organization', 'idOrganization'=>$idOrganization, 
                        'idDepartment'=>$model['id_department'], 'period'=>$period, 'periodYear'=>$periodYear], 
                    ['class' => 'btn btn-secondary link-detail', 'data-pjax'=>false]);
            },
        ],
    ],
    'tableOptions' => [
        'class' => 'table table-bordered table-dark table-striped',
    ],
]) ?>
