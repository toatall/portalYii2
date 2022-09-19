<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use kartik\widgets\TimePicker;
use kartik\widgets\DateTimePicker;
use kartik\widgets\Select2;

/** @var yii\web\View $this */
/** @var app\models\conference\Conference $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="conference-form">

    <div class="card card-body">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-12">
                <?= $form->field($model, 'theme')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-12">
                <?= $form->field($model, 'members_people')->textarea(['rows' => 5]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <?= $form->field($model, 'date_start')->widget(DateTimePicker::class, [
                    'pluginOptions' => [
                        'todayHighlight' => true,
                        'todayBtn' => true,
                        'autoclose' => true,
                        'format' => 'dd.mm.yyyy hh:ii'
                    ],
                ]) ?>                
            </div>
            <div class="col">
                <?= $form->field($model, 'duration')->widget(TimePicker::class, [
                    'pluginOptions' => [
                        'showMeridian' => false,
                        'defaultTime' => '01:00',
                    ],
                ]) ?>               
            </div>
            <div class="col">
                <?= $form->field($model, 'arrPlace')->widget(Select2::class, [
                    'data' => $model->dropDownListLocation(),
                    'options' => [
                        'multiple' => true,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]) ?>                
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <?= $form->field($model, 'is_confidential')->checkbox([
                    'template' => '<div class="form-check form-switch">{input} {label}</div><div>{error}</div>',
                ]) ?>
            </div>
            <div class="col-12">
                <?= $form->field($model, 'note')->textarea(['rows' => 10]) ?>
            </div>
        </div>
        
        <hr />

        <div class="btn-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
