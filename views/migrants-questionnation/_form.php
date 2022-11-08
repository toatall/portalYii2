<?php

use kartik\date\DatePicker;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\MigrantsQuestionnation $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="migrants-questionnation-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'mv-form',
        ],
    ]); ?>

    <?= $form->field($model, 'ul_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ul_inn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ul_kpp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_send_notice')->widget(DatePicker::class, [
        'pluginOptions' => [
            'todayHighlight' => true,
            'todayBtn' => true,
            'autoclose' => true,
            
        ],
        'options' => [
            'autocomplete' => 'off',
        ],
    ]) ?>

    <?= $form->field($model, 'region_migrate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cause_migrate')->textarea(['rows' => 5]) ?>    

    <div class="form-group border-top pt-3">      
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
