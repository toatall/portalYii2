<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\modules\test\models\Test */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="test-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_start')->widget(DatePicker::class, []) ?>

    <?= $form->field($model, 'date_end')->widget(DatePicker::class, []) ?>

    <?= $form->field($model, 'count_attempt')->textInput() ?>

    <?= $form->field($model, 'count_questions')->textInput() ?>

    <?= $form->field($model, 'description')->textArea(['rows'=>5]) ?>

    <?= $form->field($model, 'time_limit')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
