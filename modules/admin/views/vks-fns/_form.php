<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use kartik\widgets\TimePicker;
use kartik\widgets\DateTimePicker;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var app\models\conference\VksFns $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="vks-fns-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'theme')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'members_people')->textarea(['rows' => 5]) ?>

    <?= $form->field($model, 'date_start')->widget(DateTimePicker::class, [
        'pluginOptions' => [
            'todayHighlight' => true,
            'todayBtn' => true,
            'autoclose' => true,
            'format' => 'dd.mm.yyyy hh:ii'
        ],
    ]) ?>

    <?= $form->field($model, 'duration')->widget(TimePicker::class, [
        'pluginOptions' => [
            'showMeridian' => false,
            'defaultTime' => '01:00',
        ],
    ]) ?>
    
    <?= $form->field($model, 'arrPlace')->widget(Select2::class, [
        'data' => $model->dropDownListLocation(),
        'options' => [
            'multiple' => true,
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]) ?>

    <?= $form->field($model, 'time_start_msk')->checkbox() ?>

    <?= $form->field($model, 'note')->textarea(['rows' => 10]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
