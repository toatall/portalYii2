<?php

use kartik\date\DatePicker;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/** @var $this yii\web\View */
/** @var $model app\models\ChangeLegislation */
/** @var $form yii\widgets\ActiveForm */
?>

<div class="change-legislation-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col">
            <?= $form->field($model, 'type_doc')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col">
            <?= $form->field($model, 'date_doc')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'todayHighlight' => true,
                    'todayBtn' => true,
                    'autoclose' => true,
                ],
            ]) ?>
        </div>
        <div class="col">
            <?= $form->field($model, 'number_doc')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    
    <?= $form->field($model, 'name')->textInput() ?>

    <div class="row">
        <div class="col">
            <?= $form->field($model, 'status_doc')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col">
            <?= $form->field($model, 'date_doc_1')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'todayHighlight' => true,
                    'todayBtn' => true,
                    'autoclose' => true,
                ],
            ]) ?>
            <?= $form->field($model, 'date_doc_2')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'todayHighlight' => true,
                    'todayBtn' => true,
                    'autoclose' => true,
                ],
            ]) ?>
            <?= $form->field($model, 'date_doc_3')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'todayHighlight' => true,
                    'todayBtn' => true,
                    'autoclose' => true,
                ],
            ]) ?>
        </div>
    </div>

    <div class="font-20px">
    <?= $form->field($model, 'text')->widget(CKEditor::class, [
        'editorOptions' => ElFinder::ckeditorOptions('elfinder', [
            'preset' => 'full',
            'fontSize_sizes' => '20/20px;22/22px;24/24px;26/26px;28/28px;36/36px;48/48px;72/72px',
        ]),        
    ]) ?>
    </div>

    <div class="btn-group mt-3">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
