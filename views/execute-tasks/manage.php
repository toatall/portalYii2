<?php

/** @var yii\web\View $this */

use app\models\ExecuteTasks;
use kartik\select2\Select2;
use yii\bootstrap4\Html;
use yii\helpers\Url;

$this->title = 'Управление данными по исполнению задач';

$this->params['breadcrumbs'][] = ['label' => 'Исполнение задач', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="execute-tasks-manage">

    <div class="col border-bottom mb-2">
        <p class="display-4">
            <?= $this->title ?>
        </p>    
    </div>

    <?= Html::beginForm('/execute-tasks/manage-form', 'get', ['id' => 'form-filter']) ?>
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
                        'options' => [
                            'placeholder' => 'Выберите отчетный год',
                        ],
                    ]) ?>
                </div>
                <div class="col">
                    <?= Select2::widget([
                        'name' => 'period',
                        'data' => ExecuteTasks::periods(),
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
                        'options' => [
                            'placeholder' => 'Выберите отдел',
                        ],
                    ]) ?>
                </div>
            </div>
            
        </div>
    </div>
    <?= Html::endForm() ?>
    
    <div id="result_form"></div>

</div>
<?php
$this->registerJs(<<<JS
    
    var fPeriod = $('[name="period"]');
    var fPeriodYear = $('[name="periodYear"]');
    var fDepartment = $('[name="department"]');

    function isValidateForm() {    
        if (fPeriod.val() != "" && fPeriodYear.val() != "" && fDepartment.val() != "") {
            return true;
        }
        else {
            return false;
        }        
    }

    function sendForm() {
        if (isValidateForm()) {
            $('#result_form').html('<i class="fa-3x fas fa-circle-notch fa-spin mt-3 text-primary"></i>');
            form = $('#form-filter');
            $.get({
                url: form.attr('action'),
                data: form.serialize()
            })
            .done(function(data) {
                $('#result_form').html(data);
            })
            .fail(function(err) {
                $('#result_form').html('<div class="alert alert-danger mt-3">' + err.responseText + '</div>');
            });           
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

JS); ?>