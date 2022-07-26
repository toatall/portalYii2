<?php

/** @var yii\web\View $this */
/** @var yii\data\ArrayDataProvider $dataProvider */
/** @var string $idOrganization */
/** @var int $period */
/** @var int $periodYear */

use app\modules\executetasks\models\ExecuteTasksChart;
use yii\bootstrap4\Html;
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
    'rowOptions' => function ($model, $key, $index, $grid) {        
        return [
            'data-row' => 'true',            
        ];
    },
    'columns' => [
        [
            'label' => 'Наименование',
            'format' => 'raw',
            'value' => function($model) use ($idOrganization, $period, $periodYear) {
                return Html::a($model['department_index'] . ' ' . $model['department_name'], 
                    ['/executetasks/default/data-organization', 'idOrganization'=>$idOrganization, 
                    'idDepartment'=>$model['id_department'], 'period'=>$period, 'periodYear'=>$periodYear], [
                        'class' => 'link-dashed-white',
                    ]);
            },
            'contentOptions' => [
                'class' => 'text-white',
            ],
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
        'class' => 'table table-bordered table-dark table-striped',
    ],
]) ?>

<?php $this->registerJs(<<<JS
    $('tr[data-row="true"] a').click(function() {
        const countRows = $(this).parent('td').parent('tr').children('td').length;
        const tr = $(this).parent('td').parent('tr');
        const nextTr = tr.next('tr[data-detail="true"]');
        const key = tr.data('key');
        const url = $(this).attr('href');

        // скрыть дополнительную панель
        if (nextTr.length > 0) {
            nextTr.remove();
        }
        // показать дополнительную панель
        else {
            tr.after('<tr data-detail="true"><td colspan="' + countRows + '" id="detail_' + key + '"></td></tr>');
            $('#detail_' + key).html('<div class="spinner-border" role="status"><span class="sr-only>Loading...</span></div>');
            $.get(url)
            .done(function(data) {
                $('#detail_' + key).html(data.table);
            })
            .fail(function(err) {
                $('#detail_' + key).html('<div class="text-danger">' + err.responseText + '</div>');
            });
        }

        return false;
    });
JS); 

$this->registerCss(<<<CSS
    .link-dashed-white, .link-dashed-white:hover {
        color: white; 
        border-bottom: 1px white dashed;
        transition: all .5s ease-in-out;
    }

    .link-dashed-white:hover {
        opacity: .3;
        text-decoration: none;
    }
CSS);
?>