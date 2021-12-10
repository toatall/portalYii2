<?php
use kartik\datetime\DateTimePicker;
use kartik\range\RangeInput;
use kartik\time\TimePicker;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use kartik\widgets\DatePicker;

/** @var yii\web\View $this */
/** @var app\modules\test\models\Test $model */
/** @var yii\widgets\ActiveForm $form  */
?>

<div class="test-form">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_start')->widget(DateTimePicker::class, [
        'pluginOptions' => [
            'todayHighlight' => true,
            'todayBtn' => true,
            'autoclose' => true,
            'format' => 'dd.mm.yyyy hh:ii'
        ],
    ]) ?>

    <?= $form->field($model, 'date_end')->widget(DateTimePicker::class, [
        'pluginOptions' => [
            'todayHighlight' => true,
            'todayBtn' => true,
            'autoclose' => true,
            'format' => 'dd.mm.yyyy hh:ii'
        ],
    ]) ?>

    <?= $form->field($model, 'count_attempt')->widget(RangeInput::class, [
        'html5Options' => ['min' => 0, 'max' => 10],        
    ])->label($model->getAttributeLabel('count_attempt') . ' (0 - без органичений)') ?>

    <?= $form->field($model, 'count_questions')->textInput() ?>

    <?= $form->field($model, 'description')->textArea(['rows'=>5]) ?>
    
    <?= $form->field($model, 'time_limit')->widget(TimePicker::class, [
        'pluginOptions' => [
            'showMeridian' => false,
            'defaultTime' => false,
        ],        
        
    ])->label($model->getAttributeLabel('time_limit') . ' (ЧЧ:ММ)') ?>

    <?= $form->field($model, 'use_formula_filter') ?>

    <?= $form->field($model, 'show_right_answer')->checkbox() ?>

    <?= $form->field($model, 'finish_text')->textarea(['rows' => 5]) ?>
    
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
