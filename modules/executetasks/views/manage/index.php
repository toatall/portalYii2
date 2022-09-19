<?php

/** @var yii\web\View $this */
/** @var yii\data\ArrayDataProvider $dataProvider */
/** @var string $department */
/** @var string $organization */
/** @var int $period */
/** @var int $periodYear */

use app\modules\executetasks\models\ExecuteTasks;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\bootstrap5\Html;
use yii\widgets\Pjax;

$this->title = 'Управление данными по исполнению задач';

$this->params['breadcrumbs'][] = ['label' => 'Исполнение задач', 'url' => ['/executetasks/default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="execute-tasks-manage">

    <div class="col border-bottom mb-2">
        <p class="display-4">
            <?= $this->title ?>
        </p>    
    </div>

    <?php Pjax::begin(['id' => 'pjax-execute-tasks-manage', 'timeout' => false, 'enablePushState' => true]) ?>

        <?= Html::beginForm('/executetasks/manage/index', 'get', ['id' => 'form-filter', 'data-pjax'=>true]) ?>
        <div class="card">
            <div class="card-header">
                Выберите реквизиты
            </div>
            <div class="card-body">
                <div class="row">                
                    <div class="col">
                        <?= Select2::widget([
                            'name' => 'periodYear',
                            'data' => ExecuteTasks::periodsYears(),
                            'value' => $periodYear,
                            'options' => [
                                'placeholder' => 'Выберите отчетный год',
                            ],
                        ]) ?>
                    </div>
                    <div class="col">
                        <?= Select2::widget([
                            'name' => 'period',
                            'data' => ExecuteTasks::periods(),
                            'value' => $period,
                            'options' => [
                                'placeholder' => 'Выберите отчетный период',
                            ],
                        ]) ?> 
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <?= Select2::widget([
                            'name' => 'department',
                            'data' => ExecuteTasks::dropDownDepartments(),
                            'value' => $department,
                            'options' => [
                                'placeholder' => 'Выберите отдел',
                            ],
                        ]) ?>
                    </div>
                    <div class="col">
                        <?= Select2::widget([
                            'name' => 'organization',
                            'data' => ExecuteTasks::dropDownOrganizations(),
                            'value' => $organization,
                            'options' => [
                                'placeholder' => 'Выберите налоговый орган',
                            ],
                        ]) ?>
                    </div>
                </div>
                
            </div>
        </div>
        <?= Html::endForm() ?>
    
        <div class="mt-3">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'emptyText' => 'Нет данных',
            'columns' => [
                'id:text:ИД',
                'name:text:Наименование',
                'count_tasks',
                'finish_tasks',
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
                    ['class' => 'btn btn-success btn-sm mv-link', 'id' => 'btn-detail-task-create']),
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
            'toolbar' => [
                '{export}',
                '{toggleData}',
            ],
            'export' => [
                'showConfirmAlert' => false,
            ],
            'panel' => [
                'type' => GridView::TYPE_DEFAULT,       
            ],
        ]) ?>
        </div>

<?php
$this->registerJs(<<<JS
    
    var fPeriod = $('[name="period"]');
    var fPeriodYear = $('[name="periodYear"]');
    var fDepartment = $('[name="department"]');
    var fOrg = $('[name="organization"]');

    function isValidateForm() {    
        if (fPeriod.val() != "" && fPeriodYear.val() != "" && fDepartment.val() != "" && fOrg.val() != "") {
            $('#btn-detail-task-create').show();
            return true;
        }
        else {
            $('#btn-detail-task-create').hide();
            return false;
        }        
    }
    
    isValidateForm();

    function sendForm() {
        if (isValidateForm()) {
            // $('#result_form').html('<i class="fa-3x fas fa-circle-notch fa-spin mt-3 text-primary"></i>');
            // form = $('#form-filter');
            // $.get({
            //     url: form.attr('action'),
            //     data: form.serialize()
            // })
            // .done(function(data) {
            //     $('#result_form').html(data);
            // })
            // .fail(function(err) {
            //     $('#result_form').html('<div class="alert alert-danger mt-3">' + err.responseText + '</div>');
            // });     
            $('#form-filter').submit();      
        }
    }

    fPeriod.on('change', function() {
        sendForm();
    });
    
    fPeriodYear.on('change', function() {
        sendForm();
    });

    fDepartment.on('change', function() {
        sendForm();
    });

    fOrg.on('change', function() {
        sendForm();
    });

    

JS); ?>

    <?php Pjax::end() ?>

</div>

<?php $this->registerJs(<<<JS
    $(modalViewer).off('onRequestJsonAfterAutoCloseModal');
    $(modalViewer).on('onRequestJsonAfterAutoCloseModal', function(event, data) {    
        //$.pjax.reload({container:'#pjax-execute-tasks-manage', async: true });        
        document.location.reload();
    });
JS); ?>