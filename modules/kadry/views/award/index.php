<?php

/** @var \yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var app\modules\kadry\models\Award $searchModel */

use app\modules\kadry\models\Award;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\bootstrap4\Html;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\widgets\Pjax;

$this->title = 'Награды и поощрения сотрудников налоговых органов округа';
?>

<p class="display-4 border-bottom"><?= $this->title ?></p>

<?php Pjax::begin(['timeout'=>false, 'enablePushState'=>false]) ?>

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
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [        
        [
            'attribute' => 'fio',
            'group' => true,
        ],         
        [
            'attribute' => 'dep_name',
            'group' => true,
        ],
        [
            'attribute' => 'post',
            'group' => true,
        ],
        [
            'attribute' => 'aw_name',
            'value' => function (Award $model) {
                return Html::tag('i', '', ['class' => 'fas fa-award text-warning fa-lg']) . ' ' . $model->aw_name;
            },
            'format' => 'raw',
        ],
        'aw_doc',
        'aw_doc_num', 
        'aw_date_doc:date',        
    ],
    'toolbar' => [
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

<?php Pjax::end() ?>