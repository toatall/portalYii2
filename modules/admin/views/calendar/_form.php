<?php

use app\models\calendar\Calendar;
use app\models\Organization;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\web\JsExpression;

/** @var yii\web\View $this */
/** @var app\models\Calendar $model */
/** @var yii\widgets\ActiveForm $form */

?>

<div class="calendar-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model) ?>

    <?php if (Calendar::roleModerator()): ?>
    <?= $form->field($model, 'code_org')->widget(Select2::class, [
        'data' => Organization::getDropDownList(),
    ]) ?>    
    <?php endif; ?>

    <?= $form->field($model, 'date')->widget(DatePicker::class, [
        'pluginOptions' => [
            'todayHighlight' => true,
            'todayBtn' => true,
            'autoclose' => true,
        ],
        'options' => [
            'autocomplete' => 'off',
        ]
    ]) ?>    

<?php 
$result = <<< JS
    function format(data) {
        return '<span class="badge-' + data.id + ' rounded" style="font-size: 1em; font-weight: normal; padding: 0.3rem;">' + data.text + '</span>';
    } 
JS; ?>
    <?= $form->field($model, 'color')->widget(Select2::class, [
        'data' => $model->colorsDropdown(),
        'pluginOptions' => [
            'templateResult' => new JsExpression($result),
            'escapeMarkup' => new JsExpression('function(m) { return m; }'),
            'templateSelection' => new JsExpression($result),
        ]
    ]) ?>


    <div class="border-top">
        <div class="btn-group  pt-1">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Отмена', ['/admin/calendar/index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
