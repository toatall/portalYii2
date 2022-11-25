<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use kartik\widgets\TimePicker;
use kartik\widgets\DateTimePicker;
use kartik\widgets\Select2;

/** @var yii\web\View $this */
/** @var app\models\conference\VksKonturTalk $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="conference-form">

    <div class="card card-body">

        <?php $form = ActiveForm::begin([
            'options' => [
                'class' => 'mv-form',
            ],
        ]); ?>

        <div class="row">
            <div class="col-12">
                <?= Html::errorSummary($model, ['class' => 'alert alert-danger', 'encode' => false]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <?= $form->field($model, 'code_org')->widget(Select2::class, [
                    'data' => $model->getDropDownOrganizations(),
                ]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <?= $form->field($model, 'theme')->textInput(['maxlength' => true]) ?>
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
        </div>
        
        <hr />

        <div class="btn-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>           
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
