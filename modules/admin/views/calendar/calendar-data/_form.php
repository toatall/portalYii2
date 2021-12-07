<?php

use app\models\calendar\Calendar;
use kartik\select2\Select2;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\web\JsExpression;

/** @var yii\web\View $this */
/** @var app\models\calendar\CalendarData $model */
/** @var yii\widgets\ActiveForm $form */

?>

<div class="calendar-form">

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'mv-form'],
    ]); ?>

    <?= $form->errorSummary($model) ?>
    
    <?= $form->field($model, 'type_text')->widget(Select2::class, [
        'data' => $model->dropDownTypeText(),
    ]) ?>

    <?= $form->field($model, 'description') ?>    
    

<?php 
$result = <<< JS
    function formatCalendarData(data) {
        return '<span class="badge-' + data.id + ' rounded" style="font-size: 1em; font-weight: normal; padding: 0.3rem;">' + data.text + '</span>';
    } 
JS; ?>
    <?= $form->field($model, 'color')->widget(Select2::class, [
        'data' => $modelCalendar->colorsDropdown(),
        'pluginOptions' => [
            'templateResult' => new JsExpression($result),
            'escapeMarkup' => new JsExpression('function(m) { return m; }'),
            'templateSelection' => new JsExpression($result),
        ]
    ]) ?>

    <?php if (Calendar::roleModerator()): ?>
    <?= $form->field($model, 'is_global')->checkbox([
        'template' => '<div class="custom-control custom-switch">{input} {label}</div><div>{error}</div>',
    ]) ?>
    <?php endif; ?>    

    <div class="border-top">
        <div class="btn-group  pt-1">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>



</div>
