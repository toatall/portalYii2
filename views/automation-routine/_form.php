<?php

use kartik\file\FileInput;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\AutomationRoutine $model */
/** @var yii\widgets\ActiveForm $form */

$files = $model->getFiles();
?>

<div class="automation-routine-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',            
        ],
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'owners')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ftp_path')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 7]) ?>

    <div class="card">
        <div class="card-header">Загрузка ПМ</div>
        <div class="card-body">
            <?= $form->field($model, 'uploadFiles[]')->widget(FileInput::class, [
                'options' => [
                    'accept' => 'files/*',
                    'multiple' => true,
                ],
                'pluginOptions' => [
                    'showUpload' => false,
                    'showPreview' => false,
                    'theme' => 'fa5',
                ],
            ]) ?>
            <?php if (!$model->isNewRecord && count($files)): ?>
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

    <div class="card mt-2 mb-2">
        <div class="card-header"><?= $model->getAttributeLabel('uploadInstruction') ?></div>
        <div class="card-body">
            <?= $form->field($model, 'uploadInstruction')->widget(FileInput::class, [
                'options' => [
                    'accept' => 'files/*',
                    'multiple' => false,
                ],
                'pluginOptions' => [
                    'showUpload' => false,
                    'showPreview' => false,
                    'theme' => 'fa5',
                ],
            ]) ?>
            <?php if (!$model->isNewRecord): ?>
                <?= $form->field($model, 'deleteInstruction')->checkbox() ?>
            <?php endif; ?>
        </div>
    </div>
   
    

    <div class="btn-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
