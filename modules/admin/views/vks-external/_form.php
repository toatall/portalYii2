<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\TimePicker;
use kartik\widgets\DateTimePicker;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\conference\VksExternal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="conference-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'theme')->textInput(['maxlength' => true]) ?>

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
    
    <?= $form->field($model, 'responsible')->textInput() ?>
    
    <?= $form->field($model, 'format_holding')->widget(Select2::class, [
        'data' => $model->dropDownListFormat(),
    ]) ?>
    
    <?= $form->field($model, 'members_count')->textInput() ?>

    <?= $form->field($model, 'material_translation')->widget(Select2::class, [
        'data' => $model->dropDownListMaterials(),
    ]) ?>
    
    <?= $form->field($model, 'members_count_ufns')->textInput() ?>
    
    <?= $form->field($model, 'person_head')->textInput() ?>
    
    <?= $form->field($model, 'link_event')->textInput() ?>
    
    <?= $form->field($model, 'is_connect_vks_fns')->checkbox() ?>

    <?= $form->field($model, 'platform')->textInput() ?>
    
    <?= $form->field($model, 'full_name_support_ufns')->textInput() ?>
       
    <?= $form->field($model, 'date_test_vks')->widget(DateTimePicker::class, [
        'pluginOptions' => [
            'todayHighlight' => true,
            'todayBtn' => true,
            'autoclose' => true,
            'format' => 'dd.mm.yyyy hh:ii'
        ],
    ]) ?>
    
    <?= $form->field($model, 'count_notebooks')->textInput() ?>
    
    <?= $form->field($model, 'members_organization')->textarea(['rows' => 5]) ?>
    
    <?= $form->field($model, 'is_change_time_gymnastic')->checkbox() ?>
    
    <?= $form->field($model, 'note')->textarea(['rows' => 10]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
