<?php

use kartik\touchspin\TouchSpin;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use kartik\widgets\TimePicker;
use kartik\widgets\DateTimePicker;
use kartik\widgets\Select2;

/** @var yii\web\View $this */
/** @var app\models\conference\VksExternal $model */
/** @var yii\bootstrap4\ActiveForm $form */
?>

<div class="conference-form mt-3">

    <div class="card card-body">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col">
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

        <hr />

        <div class="row">
            <div class="col">
                <?= $form->field($model, 'responsible')->textInput() ?>
            </div>
            <div class="col-3">
                <?= $form->field($model, 'format_holding')->widget(Select2::class, [
                    'data' => $model->dropDownListFormat(),
                ]) ?>
            </div>
            <div class="col">
                <?= $form->field($model, 'person_head')->textInput() ?>
            </div>
        </div>
        
        <div class="row">
            <div class="col">
                <?= $form->field($model, 'members_count')->widget(TouchSpin::class, []) ?>
            </div>
            <div class="col">
                <?= $form->field($model, 'members_count_ufns')->widget(TouchSpin::class, []) ?>
            </div>
            <div class="col">
                <?= $form->field($model, 'material_translation')->widget(Select2::class, [
                    'data' => $model->dropDownListMaterials(),
                ]) ?>
            </div>
        </div>

        <hr />

        <div class="row">
            <div class="col">
                <?= $form->field($model, 'link_event')->textInput() ?>
            </div>
        </div>

        <div class="row">
            <?= $form->field($model, 'is_connect_vks_fns')->checkbox([
                'template' => '<div class="form-check form-switch">{input} {label}</div><div>{error}</div>',
            ]) ?>
        </div>

        <div class="row">
            <div class="col">
                <?= $form->field($model, 'platform')->textInput() ?>
            </div>
            <div class="col">
                <?= $form->field($model, 'full_name_support_ufns')->textInput() ?>
            </div>
            <div class="col-3">
                <?= $form->field($model, 'date_test_vks')->widget(DateTimePicker::class, [
                    'pluginOptions' => [
                        'todayHighlight' => true,
                        'todayBtn' => true,
                        'autoclose' => true,
                        'format' => 'dd.mm.yyyy hh:ii'
                    ],
                ]) ?>
            </div>
            <div class="col-2">
                <?= $form->field($model, 'count_notebooks')->widget(TouchSpin::class, []) ?>
            </div>
        </div>

        <hr />

        <div class="row">
            <div class="col-12">
                <?= $form->field($model, 'members_organization')->textarea(['rows' => 5]) ?>
            </div>
            <div class="col-12">
                <?= $form->field($model, 'is_change_time_gymnastic')->checkbox([
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
