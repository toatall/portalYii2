<?php

use app\models\calendar\Calendar;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\web\JsExpression;

/** @var yii\web\View $this */
/** @var app\models\calendar\Calendar $model */
/** @var app\models\calendar\CalendarData $modelData */
/** @var yii\widgets\ActiveForm $form */

?>

<div class="calendar-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model) ?>
 
    <?= $form->field($model, 'dates')->textarea()->label('Введите даты (в формате ДД.ММ.ГГГГ) с разделителем "/", например 01.01.2021/01.02.2021') ?>    

<?php 
$result = <<< JS
    function formatCalendar(data) {
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

    <hr />

    <?= $form->field($modelData, 'type_text')->widget(Select2::class, [
        'data' => $modelData->dropDownTypeText(),
    ]) ?>

    <?= $form->field($modelData, 'description') ?>    
    

<?php 
$result = <<< JS
    function formatCalendarData(data) {
        return '<span class="badge-' + data.id + ' rounded" style="font-size: 1em; font-weight: normal; padding: 0.3rem;">' + data.text + '</span>';
    } 
JS; ?>
    <?= $form->field($modelData, 'color')->widget(Select2::class, [
        'data' => $model->colorsDropdown(),
        'pluginOptions' => [
            'templateResult' => new JsExpression($result),
            'escapeMarkup' => new JsExpression('function(m) { return m; }'),
            'templateSelection' => new JsExpression($result),
        ]
    ]) ?>

    <?php if (Calendar::roleModerator()): ?>
    <?= $form->field($modelData, 'is_global')->checkbox([
        'template' => '<div class="custom-control custom-switch">{input} {label}</div><div>{error}</div>',
    ]) ?>
    <?php endif; ?>    


    <div class="border-top">
        <div class="btn-group  pt-1">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Отмена', ['/admin/calendar/index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
