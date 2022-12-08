<?php

/** @var \yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var app\modules\kadry\models\Award $searchModel */

use app\modules\kadry\models\Award;
use kartik\form\ActiveForm;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\Pjax;

$this->title = 'Награды и поощрения сотрудников налоговых органов округа';

$isEditor = Yii::$app->user->can(Award::roleModerator()) || Yii::$app->user->can('admin');
?>

<p class="display-5 border-bottom"><?= $this->title ?></p>

<?php Pjax::begin(['id' => 'pjax-kadry-award-index', 'timeout'=>false, 'enablePushState'=>false]) ?>

<div class="card card-header">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1,
            'id' => 'award-form-search'
        ],
    ]); ?>

        <?php 
        $idOrgCode = Html::getInputId($searchModel, 'org_code');
        $this->registerJs(<<<JS
            $('#$idOrgCode').on('change', function() {
                $('#award-form-search').submit();
            });
        JS); 
        $result = <<< JS
            function format(data) {
                return '<i class="far fa-building text-primary"></i> ' + data.text;        
            } 
        JS;
        ?>
        <?= $form->field($searchModel, 'org_code')->widget(Select2::class, [
            'data' => ArrayHelper::map($searchModel->getOrganizations(), 'org_code', 'org_name'),
            'pluginOptions' => [
                'templateResult' => new JsExpression($result),
                'escapeMarkup' => new JsExpression('function(m) { return m; }'),
                'templateSelection' => new JsExpression($result),
            ],
        ])->label('Налоговый орган') ?>

    <?php ActiveForm::end(); ?>

</div>

<?= GridView::widget([
    'id' => 'grid-view-kadry-award-index',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [        
        [
            'attribute' => 'fio',
            'group' => true,
        ],         
        [
            'attribute' => 'dep_name',
            'value' => function($model) {
                return $model->dep_name == null ? '-' : $model->dep_name;
            },
            'group' => true,
        ],
        [
            'attribute' => 'post',
            'value' => function($model) {
                return $model->post == null ? '-' : $model->post;
            },
            'group' => true,            
        ],
        [
            'attribute' => 'aw_name',
            'value' => function (Award $model) {
                return Html::tag('i', '', ['class' => 'fas fa-award text-warning fa-lg']) . ' ' . $model->aw_name
                    . '<br />' . ($model->flag_dks ? '<span class="badge bg-success fs-6">ДКС</span>' : '');
            },
            'format' => 'raw',
        ],
        'aw_doc',
        'aw_doc_num', 
        'aw_date_doc:date', 
        'enc_dep_name',
        [
            'class' => ActionColumn::class,
            'template' => '{view} {update} {delete} {info}',
            'urlCreator' => function ($action, Award $model, $key, $index, $column) {
                return Url::to([$action, 'id' => $model->id]);
            },
            'buttonOptions' => [
                'class' => 'mv-link',
            ],
            'visibleButtons' => [
                // 'view' => false,
                'update' => function ($model, $key, $index) use ($isEditor) { 
                    return $isEditor && !$model->flag_dks;
                },
                'delete' =>  function ($model, $key, $index) use ($isEditor) { 
                    return $isEditor && !$model->flag_dks;
                },
                'info' => function ($model, $key, $index) use ($isEditor) { 
                    return $isEditor && $model->flag_dks;
                },
            ],
            'buttons' => [
                'info' => function($url, $model) use ($isEditor) {
                    return '<i class="fas fa-info text-secondary"'
                        . ' data-bs-title="Редактирование записи невозможно (редактирование необходимо произвести в &laquo;ПК ДКС&raquo;)"'
                        . ' data-bs-toggle="tooltip" ></i>';                   
                },
                'delete' => function($url, $model) {
                    return Html::a('<i class="fas fa-trash-alt"></i>', $url, [
                        'data-confirm' => 'Вы действительно хотите удалить данную запись?',
                        'class' => 'link-delete',
                        'title' => 'Удалить',
                    ]);
                }
            ],               
        ],
    ],
    'toolbar' => [
        'content' => ($isEditor)
                ? Html::a('Добавить', ['create', 'org' => $searchModel->org_code], ['class' => 'btn btn-success mx-2 mv-link']) : '',
        '{export}',
        '{toggleData}',
    ],
    'exportConfig' => [        
        GridView::EXCEL => [
            'filename' => "Награды и поощрения {$searchModel->org_code}",  
        ],      
    ],    
    'panel' => [
        'type' => GridView::TYPE_DEFAULT,
    ],
]) ?>

    <?php $this->registerJs(<<<JS
        
        $(modalViewer).off('onRequestJsonAfterAutoCloseModal');
        $(modalViewer).on('onRequestJsonAfterAutoCloseModal', function(event, data) {               
            $('#grid-view-kadry-award-index').yiiGridView('applyFilter');
        });
        
        $('#pjax-kadry-award-index [data-bs-toggle="tooltip"]').tooltip();

        $('.link-delete').on('click', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            const textConf = $(this).data('confirm');
            if (!confirm(textConf)) {
                return false;
            }
            $.ajax({
                url: url,
                method: 'post',
                error: function(xhr, status, error) {
                    alert(xhr.responseText);
                }
            })
            .done(function() {
                $('#grid-view-kadry-award-index').yiiGridView('applyFilter');
            });
            return false;
        });

    JS); ?>

<?php Pjax::end() ?>