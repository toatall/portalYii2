<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\TimePicker;
use kartik\widgets\DateTimePicker;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\conference\Conference */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="conference-form">

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

    <?= $form->field($model, 'is_confidential')->checkbox() ?>

    <?= $form->field($model, 'note')->textarea(['rows' => 10]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
