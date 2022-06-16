<?php

/** @var yii\web\View $this */
/** @var yii\data\ArrayDataProvider $dataProvider */
/** @var string $department */
/** @var string $organization */
/** @var int $period */
/** @var int $periodYear */

use kartik\grid\GridView;
use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
?>

<div class="mt-3">
<?php Pjax::begin(['id' => 'pjax-execute-tasks-manage', 'timeout' => false, 'enablePushState' => false]) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'emptyText' => 'Нет данных',
        'columns' => [
            'id:text:ИД',
            'name:text:Наименование',
            'count_tasks',
            'date_create:datetime:Дата создания',
            'date_update:datetime:Дата изменения',
            [
                'label' => Html::a('<i class="fas fa-plus-circle"></i> Добавить', [
                    '/executetasks/manage/detail-create', 
                    'department' => $department,
                    'organization' => $organization,
                    'period' => $period,
                    'periodYear' => $periodYear,
                ], 
                ['class' => 'btn btn-success btn-sm mv-link']),
                'encodeLabel' => false,
                'format' => 'raw',
                'value' => function($model) {
                    $res = '';
                    $res .= Html::a('<i class="fas fa-pencil"></i> Изменить', 
                        ['/executetasks/manage/detail-update', 'id'=>$model['id']], 
                        ['class'=>'btn btn-primary btn-sm mv-link']);
                    return $res;
                },
            ],
        ],
    ]) ?>

<?php 
$url = Url::to(['/executetasks/manage/detail-index', 'department'=>$department, 'organization'=>$organization, 'period'=>$period, 'periodYear'=>$periodYear]);
$this->registerJs(<<<JS
    $(modalViewer).on('onRequestJsonAfterAutoCloseModal', function(event, data) {    
        $.pjax.reload({container:'#pjax-execute-tasks-manage', async: true, url: '$url' });        
    });
JS); ?>

<?php Pjax::end() ?>
</div>

