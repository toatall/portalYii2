<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use kartik\widgets\DatePicker;
use app\models\Organization;
use kartik\select2\Select2;
use kartik\widgets\TouchSpin;

/** @var yii\web\View $this */
/** @var app\models\RegEcr $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="reg-ecr-form mt-3">
    <div class="card card-body">

        <?php $form = ActiveForm::begin([
            'options'=> ['autocomplete'=>'off'],
        ]); ?>
        
        <div class="row">
            <div class="col">
                <?= $form->field($model, 'code_org')->widget(Select2::class, [
                    'data' => Organization::getDropDownList(true),
                ]) ?>
            </div>
            <div class="col-3">
                <?= $form->field($model, 'date_reg')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'todayHighlight' => true,
                        'todayBtn' => true,
                        'autoclose' => true,
                    ],
                ]) ?>                
            </div>
        </div>

        <div class="row">
            <div class="col">
                <?= $form->field($model, 'count_create')->widget(TouchSpin::class, []) ?>
            </div>
            <div class="col">
                <?= $form->field($model, 'count_vote')->widget(TouchSpin::class, []) ?>
            </div>
            <div class="col">
                <?= $form->field($model, 'avg_eval_a_1_1')->widget(TouchSpin::class, []) ?>
            </div>
            <div class="col">
                <?= $form->field($model, 'avg_eval_a_1_2')->widget(TouchSpin::class, []) ?>
            </div>
            <div class="col">
                <?= $form->field($model, 'avg_eval_a_1_3')->widget(TouchSpin::class, []) ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Описание</div>
            <div class="card-body">
                <strong>Дата</strong> - Дата регистрации<br />
                <strong>Кол-во вновь созданных ООО</strong> - Количество вновь созданных ООО<br />
                <strong>Кол-во опрошенных</strong> - Количество опрошенных представителей вновь созданных ООО (1 представитель в отношении 1 вновь созданного ООО)<br />
                <strong>Средняя оценка А 1.1</strong> - Средняя оценка респондентами по показателю А 1.1 "Среднее время регистрации, юридических лиц", дней (среднее арифметическое от общего количества опрошенных респондентов)<br />
                <strong>Средняя оценка А 1.2</strong> - Средняя оценка респондентами по показателю А 1.2 "Среднее количество процедур, необходимых для регистрации юридических лиц", штук (среднее арифметическое от общего количества опрошенных респондентов)<br />
                <strong>Средняя оценка А 1.3</strong> - Средняя оценка респондентами по показателю А 1.3 "Оценка деятельности органов власти по регистрации, юридических лиц", баллов (среднее арифметическое от общего количества опрошенных респондентов)<br />
            </div>
        </div>

        <div class="btn-group mt-3">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Отмена', ['/admin/regecr/index'], ['class' => 'btn btn-secondary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
