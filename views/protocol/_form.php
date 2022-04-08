<?php

use kartik\date\DatePicker;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Protocol $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="protocol-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data', 
            'autocomplete'=>'off',
        ],
    ]); ?>

    <div class="card">
        <div class="card-header">
            <strong>Основаная информация</strong>
        </div>
        <div class="card-body">
            
            <div class="mb-3">
                <?= Select2::widget([
                    'model' => $model,
                    'attribute' => 'type_protocol',
                    'data' => [
                        'Протокол коллегий ФНС России' => 'Протокол коллегий ФНС России',
                    ],                
                ]) ?>
            </div>

            <div class="row">        
                <div class="col">
                    <?= $form->field($model, 'date')->widget(DatePicker::class, [
                        'pluginOptions' => [
                            'todayHighlight' => true,
                            'todayBtn' => true,
                            'autoclose' => true,
                        ],
                    ]) ?>
                </div>
                <div class="col">
                    <?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            

            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <div class="card">
                <div class="card-header">Загрузка файлов</div>
                <div class="card-body">
                    <?= $form->field($model, 'uploadMainFiles[]')->widget(FileInput::class, [
                        'options' => [
                            'accept' => 'files/*',
                            'multiple' => true,
                        ],
                        'pluginOptions' => [
                            'showUpload' => false,
                            'showPreview' => false,
                        ],
                    ]) ?>
                    <?php if (!$model->isNewRecord && count($files = $model->getFilesMain())): ?>
                        <hr />
                        <div class="card card-body">
                            <?= $form->field($model, 'deleteMainFiles', [])
                                ->checkboxList($files, [
                                    'item' => function($index, $label, $name, $checked, $value) {
                                        return "<div class=\"checkbox\"><label><input type=\"checkbox\" name=\"{$name}\" value=\"" . basename($label) . "\"> " . basename($label) . " "
                                            . Html::a('(просмотр)', \Yii::$app->storage->getFileUrl($label), ['target' => '_blank']) . "</label></div>";
                                    },
                                ]) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <strong>Исполнение протокола</strong>
        </div>
        <div class="card-body">

            <?= $form->field($model, 'executor')->textarea(['rows' => 6]) ?>    
            
            <div class="card mb-3">
                <div class="card-header">Загрузка файлов</div>
                <div class="card-body">
                    <?= $form->field($model, 'uploadExecuteFiles[]')->widget(FileInput::class, [
                        'options' => [
                            'accept' => 'files/*',
                            'multiple' => true,
                        ],
                        'pluginOptions' => [
                            'showUpload' => false,
                            'showPreview' => false,
                        ],
                    ]) ?>
                    <?php if (!$model->isNewRecord && count($files = $model->getFilesExecute())): ?>
                        <hr />
                        <div class="card card-body">
                            <?= $form->field($model, 'deleteExecuteFiles', [])
                                ->checkboxList($files, [
                                    'item' => function($index, $label, $name, $checked, $value) {
                                        return "<div class=\"checkbox\"><label><input type=\"checkbox\" name=\"{$name}\" value=\"" . basename($label) . "\"> " . basename($label) . " "
                                            . Html::a('(просмотр)', \Yii::$app->storage->getFileUrl($label), ['target' => '_blank']) . "</label></div>";
                                    },
                                ]) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    


    


    

    <div class="btn-group mt-3">
        <?= Html::submitButton('Схранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>    

    <?php ActiveForm::end(); ?>

</div>
