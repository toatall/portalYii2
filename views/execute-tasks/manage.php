<?php

use app\models\department\Department;
use app\models\ExecuteTasks;
use app\models\Organization;
use kartik\select2\Select2;
use yii\bootstrap4\Html;

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

    <?= Html::beginForm() ?>
    <div class="card">
        <div class="card-header">
            Выберите реквизиты
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <?= Select2::widget([
                        'name' => 'period',
                        'data' => ExecuteTasks::periods(),
                        'options' => [
                            'placeholder' => 'Выберите отчетный период',
                        ],
                    ]) ?> 
                </div>
                <div class="col">
                    <?= Select2::widget([
                        'name' => 'periodYear',
                        'data' => ExecuteTasks::periodsYears(),
                        'options' => [
                            'placeholder' => 'Выберите отчетный год',
                        ],
                    ]) ?>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col">
                    <?= Select2::widget([
                        'name' => 'org',
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

</div>