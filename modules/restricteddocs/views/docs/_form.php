<?php

use app\modules\restricteddocs\models\RestrictedDocsOrgs;
use app\modules\restricteddocs\models\RestrictedDocsTypes;
use kartik\date\DatePicker;
use kartik\file\FileInput;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\restricteddocs\models\RestrictedDocs $model */
/** @var yii\bootstrap4\ActiveForm $form */
?>

<div class="restricted-docs-form card card-body">

    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'mv-form',
        ],
    ]); ?>

    <?= $form->errorSummary($model) ?>

    <div class="row">
        <div class="col">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>        
        </div>
    </div>

    <div class="row">
        <div class="col">
            <?= $form->field($model, 'doc_num')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col">
            <?= $form->field($model, 'doc_date')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'todayHighlight' => true,
                    'todayBtn' => true,
                    'autoclose' => true,                   
                ],
            ]) ?>
        </div>
    </div>

    <?= $form->field($model, 'privacy_sign_desc')->textarea(['rows' => 4])
        ->label($model->getAttributeLabel('privacy_sign_desc') . '* (налоговая тайна, персональные данные, банковская тайна и т.д.)') ?>

    <?= $form->field($model, 'owner')->textInput(['maxlength' => true]) ?>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <?= $model->getAttributeLabel('restrictedDocsOrgsVals') ?>
                </div>
                <div class="card-body">
                    <?= $form->field($model, 'restrictedDocsOrgsVals')->checkboxList(RestrictedDocsOrgs::dropDownList())->label(false) ?>
                </div>
            </div>            
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <?= $model->getAttributeLabel('restrictedDocsTypesVals') ?>
                </div>
                <div class="card-body">
                    <?= $form->field($model, 'restrictedDocsTypesVals')->checkboxList(RestrictedDocsTypes::dropDownList()) ?>
                </div>
            </div>            
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">Загрузка файлов</div>
        <div class="card-body">
            <?= $form->field($model, 'uploadFiles[]')->widget(FileInput::class, [
                'options' => [
                    'accept' => 'files/*',
                    'multiple' => true,
                ],
                'pluginOptions' => [
                    'showUpload' => false,
                    'showPreview' => false,
                ],
            ]) ?>
            <?php if (!$model->isNewRecord && count($files = $model->getFiles())): ?>
                <hr />
                <div class="card card-body">
                    <?= $form->field($model, 'deleteFiles', [])
                        ->checkboxList($files, [
                            'item' => function($index, $label, $name, $checked, $value) {
                                return "<div class=\"checkbox\"><label><input type=\"checkbox\" name=\"{$name}\" value=\"" . $label . "\"> " . basename($label) . " "
                                    . Html::a('(просмотр)', \Yii::$app->storage->getFileUrl($label), ['target' => '_blank']) . "</label></div>";
                            },
                        ]) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>


    <hr />
    
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
